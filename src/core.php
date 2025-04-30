<?php
require_once __DIR__ . '/../vendor/autoload.php';

use RedBeanPHP\R;
use FastRoute\RouteCollector;
use Latte\Engine;
use App\Controllers;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

R::setup('mysql:host='.$_ENV['DB_HOST'].';dbname='.$_ENV['DB_DATABASE'], $_ENV['DB_USER'], $_ENV['DB_PASS']);
R::freeze(true);

$latte = new Engine();
$latte->setTempDirectory(__DIR__ . '/temp');

function render($template, $params = []) {
    global $latte;
    $latte->render(__DIR__ . "/views/$template.latte", $params);
}

$dispatcher = FastRoute\simpleDispatcher(function(RouteCollector $r) {
    require_once __DIR__ . '/Routes/routes.php';
});

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}

$uri = rawurldecode($uri);
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        header("HTTP/1.0 404 Not Found");
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        header("HTTP/1.0 405 Method Not Allowed");
        break;
    case FastRoute\Dispatcher::FOUND:
     $handler = $routeInfo[1];
     $vars = $routeInfo[2];
    
     if (is_string($handler) && strpos($handler, '::') !== false) {
        // Если обработчик задан как "Controller::method"
        [$controller, $method] = explode('::', $handler);
        $controller = "App\\Controllers\\$controller";
        (new $controller)->$method($vars);
     } elseif (is_array($handler)) {
        // Если обработчик задан как ['Controller', 'method']
        [$controller, $method] = $handler;
        $controller = "App\\Controllers\\$controller";
        (new $controller)->$method($vars);
     } elseif (is_callable($handler)) {
        // Если обработчик — замыкание
        call_user_func($handler, $vars);
     } else {
        throw new Exception('Invalid route handler');
     }
     break;
}
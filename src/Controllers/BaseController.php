<?php
namespace App\Controllers;
use RedBeanPHP\R;

class BaseController {
    protected $lang;
    protected $user;

    public function __construct() {
        $this->lang = $this->loadLanguage();
        $this->checkAuthorization();
    }

    private function loadLanguage() {
        session_start();
        $defaultLang = 'en';
        $selectedLang = $_SESSION['lang'] ?? $defaultLang;
        $langFile = __DIR__ . "/../langs/{$selectedLang}.php";

        if (file_exists($langFile)) {
            return require $langFile;
        }

        return require __DIR__ . "/../langs/{$defaultLang}.php";
    }
    
    protected function checkAuthorization() {
        if (!isset($_COOKIE['user'])) {
            $this->jsonError(401, 'Unauthorized');
        }

        $userData = json_decode($_COOKIE['user'], true);
        if (!$userData || !isset($userData['google_id'])) {
            $this->jsonError(401, 'Invalid session data');
        }

        $this->user = R::findOne('users', 'google_id = ?', [$userData['google_id']]);
        if (!$this->user) {
            $this->jsonError(403, 'User not found');
        }
    }

    protected function jsonError($code, $message) {
        http_response_code($code);
        echo json_encode(['error' => $message, 'code' => $code]);
        exit;
    }

    protected function validateOwnership($userId) {
        if ($this->user->id != $userId) {
            $this->jsonError(403, 'Access denied');
        }
    }
}
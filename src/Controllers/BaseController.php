<?php
namespace App\Controllers;
use RedBeanPHP\R;

class BaseController {
    protected $lang;
    protected $user;

    public function __construct() {
        $this->lang = $this->loadLanguage();
        $this->user =  json_decode($_COOKIE['user'] ?? '{}', true);
    }

    protected function setCookie($name, $value, $expire = 0) {
        setcookie($name, $value, $expire, "/");
    }

    protected function getCookie($name) {
        return isset($_COOKIE[$name]) ? $_COOKIE[$name] : null;
    }

    protected function deleteCookie($name) {
        setcookie($name, '', time() - 604800, "/");
    }

    protected function loadLanguage() {
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
            header('Location: /auth/signup');
            return;
        }

        $userData = json_decode($_COOKIE['user'], true);

        if ($userData['isStudent'] == true) {
            if (!$userData || (!isset($userData['google_id']))) {
                header('Location: /auth/signup');
            }
    
            $this->user = R::findOne('users', 'google_id = ?', [$userData['google_id']]);
            if (!$this->user) {
                header('Location: /auth/signup');
            }

            if ($this->user['token_confirmed'] == 0 || $this->user['token_confirmed'] == -1) {
                if ($userData['register'] == true) {
                    header('Location: /auth/student/complete');
                } else if ($_SERVER['REQUEST_URI'] != '/confirmation') {
                    header('Location: /confirmation');
                }
            }
        } else {
            header('Location: /');
        }
    }

    protected function checkTeacherAuthorization() {
        if (!isset($_COOKIE['user'])) {
            header('Location: /auth/teacher/auth');
            return;
        }

        $userData = json_decode($_COOKIE['user'], true);

        if ($userData['isStudent']) {
            header('Location: /auth/teacher/auth');
            return;
        }

        $this->user = R::findOne('teachers', 'id = ?', [$userData['id']]);
        if (!$this->user) {
            header('Location: /auth/teacher/auth');
        }
    }

    protected function checkAdminAutorization() {
        if (!isset($_COOKIE['admin'])) {
            header('Location: /auth/adminPanel');
            return;
        }
    }

    protected function jsonError($code, $message) {
        http_response_code($code);
        echo json_encode(['error' => true, 'message' => $message]);
        return;
    }

    protected function validateOwnership($userId) {
        if ($this->user['id'] != $userId) {
            $this->jsonError(403, 'Access denied');
        }
    }

    protected function validateAdmin($token) {
        $admin = R::findOne('admins', 'token = ?', [$token]);

        if (!$admin) {
            $this->jsonError(403, 'Access denied');
        }
    }
}
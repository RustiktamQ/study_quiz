<?php

namespace App\Controllers;

class BaseController {
    protected $lang;

    public function __construct() {
        $this->lang = $this->loadLanguage();
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
}
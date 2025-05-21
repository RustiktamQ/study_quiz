<?php

namespace App\Controllers;
use RedBeanPHP\R;

class LanguageController {
    public function switchLanguage($vars) {
        session_start();
        $supportedLanguages = ['en', 'ru', 'kz'];
        $lang = $vars['lang'] ?? 'en';

        if (in_array($lang, $supportedLanguages)) {
            $_SESSION['lang'] = $lang;
            if(isset($_SESSION['teacher'])) {
                $user = R::findOne('teachers', 'email = ?', [$_SESSION['teacher']['email']]);
                if($user) {
                  $user->lang = $lang;
                }
            }
        }

        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        header("Location: $referer");
        return;
    }
}
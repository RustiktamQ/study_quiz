<?php
$r->addRoute('GET', '/', ['HomeController', 'index']);
$r->addRoute('GET', '/lang/{lang}', ['LanguageController', 'switchLanguage']);
$r->addRoute('GET', '/auth/signup', ['AuthController', 'showRegister']);
$r->addRoute('GET', '/auth/teacher/signup', ['AuthController', 'showRegisterTeacher']);
$r->addRoute('GET', '/auth/teacher/signin', ['AuthController', 'showLoginTeacher']);
$r->addRoute('GET', '/auth/callback', ['AuthController', 'loginWithGoogle']);
$r->addRoute('GET', '/dashboard', ['HomeController', 'showDashboard']);
$r->addRoute('GET', '/learn', ['HomeController', 'showLearn']);
$r->addRoute('GET', '/quiz/{id}', ['HomeController', 'showQuiz']);
$r->addRoute('GET', '/quiz/{id}/fail', ['HomeController', 'showFailQuiz']);
$r->addRoute('GET','/api/getAllQuizzes/{student_id}', ['APIController','getAllQuizzes']);
$r->addRoute('GET', '/api/getQuiz/{quiz_id}', ['APIController', 'getQuiz']);
$r->addRoute('POST', '/quiz/start', ['APIController', 'startQuiz']);
$r->addRoute('POST', '/api/getNextQuestion', ['APIController', 'getNextQuestion']);
$r->addRoute('POST', '/api/getStatistics', ['APIController', 'getStatistics']);
$r->addRoute('POST', '/quiz/answer', ['APIController', 'answerQuestion']);
$r->addRoute('POST', '/api/save-profile', ['HomeController', 'saveProfile']);
$r->addRoute('GET', '/api/check-token-status', ['HomeController', 'checkTokenStatus']);
$r->addRoute('POST', '/api/register-teacher', ['AuthController', 'registerTeacher']);
$r->addRoute('POST', '/api/login-teacher', ['AuthController', 'loginTeacher']);
$r->addRoute('GET', '/logout', function() {
    if (isset($_COOKIE['user'])) {
        setcookie('user', '', time() - 604800, '/');
    }
    header('Location: /auth/signup');
    exit;
});
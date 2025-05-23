<?php
namespace App\Controllers;
use Google_Client;
use RedBeanPHP\R;

class AuthController extends BaseController {
    public function showauth()
    {
        $isAuthorized = isset($_COOKIE['user']);

        if ($isAuthorized) {
            header("Location: /dashboard");
        }

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';

        $client = new Google_Client();
        $client->setClientId($_ENV['GOOGLE_CLIENT_ID']);
        $client->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET']);
        $client->setRedirectUri($root.$_ENV['GOOGLE_REDIRECT_URI']);
        $client->addScope('email');
        $authUrl = $client->createAuthUrl();

        $this->renderPartial('auth/auth', [
            'lang' => $this->lang,
            'uri' => $authUrl,
            'ROOT_URL' => $root,
            'APP_NAME' => $_ENV['APP_NAME']
        ]);
    }

    public function showJoin($params)
    {
        $invitedTeacher = R::findOne('teachers', 'invite_code = ?', [$params['code']]);

        if (!$invitedTeacher) {
            header('Location: /');
        }

        if ($this->user) {
            $user = $this->user;
            $student = R::findOne('users', 'google_id = ?', [$user['google_id']]);
            $student->token = $params['code'];
            $student->token_confirmed = 0;
            R::store($student);
            header('Location: /dashboard/student');
            return;
        }

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';

        $client = new Google_Client();
        $client->setClientId($_ENV['GOOGLE_CLIENT_ID']);
        $client->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET']);
        // $client->setRedirectUri($root.$_ENV['GOOGLE_REDIRECT_URI']);
        $client->setRedirectUri($root . '/auth/callback/join?invite_code=' . $params['code']);
        $client->addScope('email');
        $authUrl = $client->createAuthUrl();

        $this->renderPartial('join', [
            'lang' => $this->lang,
            'APP_NAME' => $_ENV['APP_NAME'],
            'ROOT_URL' => $root,
            'domain' => $_ENV['ROOT_URL'],
            'teacher' => $invitedTeacher,
            'uri' => $authUrl,
            'devUri' => '/auth/callback/join?invite_code=' . $params['code']
        ]);
    }

    public function showTeacherAuth()
    {
        if (isset($_COOKIE['user'])) {
            $userData = json_decode($_COOKIE['user'], true);

            if ($userData['isStudent'] == false) {
                header('Location: /dashboard/teacher');
                return;
            }
        }

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';

        $client = new Google_Client();
        $client->setClientId($_ENV['GOOGLE_CLIENT_ID']);
        $client->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET']);
        // $client->setRedirectUri($root.$_ENV['GOOGLE_REDIRECT_URI']);
        $client->setRedirectUri($root . 'auth/callback/teacher');
        $client->addScope('email');
        $authUrl = $client->createAuthUrl();

        $this->renderPartial('auth/authTeacher', [
            'lang' => $this->lang,
            'uri' => $authUrl,
            'ROOT_URL' => $root,
            'APP_NAME' => $_ENV['APP_NAME']
        ]);
    }

    public function showStudentAuth() {

        if (isset($_COOKIE['user'])) {
            $userData = json_decode($_COOKIE['user'], true);

            if ($userData['isStudent'] == false) {
                header('Location: /dashboard/teacher');
                return;
            } else {
                header('Location: /dashboard/student');
                return;
            }
        }

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';

        $client = new Google_Client();
        $client->setClientId($_ENV['GOOGLE_CLIENT_ID']);
        $client->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET']);
        $client->setRedirectUri($root.$_ENV['GOOGLE_REDIRECT_URI']);
        $client->addScope('email');
        $authUrl = $client->createAuthUrl();

        $this->renderPartial('auth/auth', [
            'lang' => $this->lang,
            'uri' => $authUrl,
            'ROOT_URL' => $root,
            'APP_NAME' => $_ENV['APP_NAME'],
            'isTeacherAuth' => false
        ]);
    }

    public function showTeacherRegister()
    {
        if (isset($_COOKIE['user'])){
            $userData = json_decode($_COOKIE['user'], true);
            if ($userData['isStudent'] == true) {
                header("Location: /dashboard/student");
                return;
            }
        }

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';

        $this->renderPartial('auth/teacherRegister', [
            'lang' => $this->lang,
            'ROOT_URL' => $root,
            'APP_NAME' => $_ENV['APP_NAME'],
            'isTeacherAuth' => true
        ]);
    }

    public function showRegister()
    {
        if (isset($_COOKIE['user'])){
            $userData = json_decode($_COOKIE['user'], true);
            if ($userData['register'] == false && $userData['isStudent'] == true) {
                header("Location: /dashboard/student");
                return;
            }
        } else {
            header('Location: /');
        }

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';

        $student = R::findOne('users', 'google_id = ?', [$userData['google_id']]);

        $isDeny = false;
        if ($student->token_confirmed == 1) {
            header('Location: /dashboard/student');
        } else if ($student->token_confirmed == -1) {
            $isDeny = true;
            $student->token_confirmed = 0;
            R::store($student);
        }

        $this->renderPartial('auth/register', [
            'lang' => $this->lang,
            'ROOT_URL' => $root,
            'APP_NAME' => $_ENV['APP_NAME'],
            'isTeacherAuth' => true,
            'isDeny' => $isDeny
        ]);
    }

    public function teacherRegister() {
        header('Content-Type: application/json; charset=utf-8');
        $input = file_get_contents('php://input');
        $params = json_decode($input, true);

        if (!isset($params['user_id'], $params['token'], $params['school'])) {
            http_response_code(400);
            echo json_encode(['error' => true, 'message' => 'Missing required parameters']);
            return;
        }
        $this->validateOwnership($params['user_id']);

        $teacher = R::findOne('teachers', 'id = ?', [$params['user_id']]);

        if ($teacher) {
            $tokenExist = R::findOne('teachers', 'invite_code = ?', [$params['token']]);
            if ($tokenExist) {
                http_response_code(500);
                echo json_encode(['error' => true, 'message' => 'Token already exist']);
                return;
            }
            $teacher->invite_code = $params['token'];
            $teacher->school = $params['school'];
            R::store($teacher);
        } else {
            http_response_code(404);
            echo json_encode(['error' => true, 'message' => 'User not found']);
            return;
        }

        $cookie = json_decode($this->getCookie('user'));
        $cookie->register = NULL;
        $userData = $cookie;
        $this->setCookie('user', json_encode($userData), time() + 604800);

        http_response_code(200);
        echo json_encode(['success' => true]);
        return;
    }

    public function teacherAuthWithGoogle()
    {
        if (isset($_COOKIE['user'])){
            $userData = json_decode($_COOKIE['user'], true);
            if ($userData['isStudent'] == true) {
                header("Location: /dashboard/student");
            } else {
                header("Location: /dashboard/teacher");
            }
            return;
        }

        $client = new Google_Client();
        $client->setClientId($_ENV['GOOGLE_CLIENT_ID']);
        $client->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET']);
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';
        $client->setRedirectUri($root . 'auth/callback/teacher');
        $client->addScope('email');

        if (isset($_GET['code'])) {
            $accessToken = $client->fetchAccessTokenWithAuthCode($_GET['code']);
            $client->setAccessToken($accessToken);
            $oauth2 = new \Google_Service_Oauth2($client);
            $userInfo = $oauth2->userinfo->get();

            $teacher = R::findOne('teachers', 'email = ?', [$userInfo->email]);
            if (!$teacher) {
                $teacher = R::dispense('teachers');
                $teacher->name = $userInfo->name;
                $teacher->email = $userInfo->email;
                $teacher->picture = $userInfo->picture;
                $teacher->join_date = date('Y-m-d H:i:s');
                R::store($teacher);

                $userData = [
                    'id' => $teacher->id,
                    'name' => $teacher->name,
                    'email' => $teacher->email,
                    'lang' => $teacher->lang,
                    'picture' => $teacher->picture,
                    'isStudent' => false,
                    'school' => $teacher->school,
                    'register' => true
                ];

                $this->setCookie('user', json_encode($userData), time() + 604800);
                header('Location: /auth/teacher/register/complete');
            } else {
                $userData = [
                    'id' => $teacher->id,
                    'name' => $teacher->name,
                    'email' => $teacher->email,
                    'lang' => $teacher->lang,
                    'picture' => $teacher->picture,
                    'isStudent' => false,
                    'school' => $teacher->school,
                    'register' => false
                ];

                $this->setCookie('user', json_encode($userData), time() + 604800);
                header('Location: /dashboard/teacher');
            }
            return;
        } else {
            header('Location: /auth/teacher/auth');
        }
    }

    public function DEV_teacherAuthWithGoogle()
    {
        if (isset($_COOKIE['user'])){
            $userData = json_decode($_COOKIE['user'], true);
            if ($userData['isStudent'] == true) {
                header("Location: /dashboard/student");
            } else {
                header("Location: /dashboard/teacher");
            }
            return;
        }

        if (true) {
            $teacher = R::findOne('teachers', 'email = ?', ["aqylapp@gmail.com"]);

            $userData = [
                'id' => $teacher->id,
                'name' => $teacher->name,
                'email' => $teacher->email,
                'lang' => $teacher->lang,
                'picture' => $teacher->picture,
                'isStudent' => false,
                'school' => $teacher->school,
                'register' => false
            ];

            $this->setCookie('user', json_encode($userData), time() + 604800);
            header('Location: /dashboard/teacher');
        }
    }

    public function joinWithGoogle()
    {
        if (isset($_COOKIE['user'])){
            $userData = json_decode($_COOKIE['user'], true);
            if ($userData['isStudent'] == true) {
                header("Location: /dashboard/student");
            } else {
                header("Location: /dashboard/teacher");
            }
            return;
        }

        if (!isset($_GET['invite_code'])) {
            header("Location: /");
            return;
        }

        $invitedTeacher = R::findOne('teachers', 'invite_code = ?', [$_GET['invite_code']]);

        if (!$invitedTeacher) {
            header('Location: /');
        }

        $client = new Google_Client();
        $client->setClientId($_ENV['GOOGLE_CLIENT_ID']);
        $client->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET']);
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';
        $client->setRedirectUri($root.$_ENV['GOOGLE_REDIRECT_URI']);
        $client->addScope('email');
        $client->addScope('openid');
        $client->addScope('https://www.googleapis.com/auth/userinfo.email');

        if (isset($_GET['code'])) {
            $accessToken = $client->fetchAccessTokenWithAuthCode($_GET['code']);
            $client->setAccessToken($accessToken);
            $oauth2 = new \Google_Service_Oauth2($client);
            $userInfo = $oauth2->userinfo->get();
            $user = R::findOne('users', 'google_id = ?', [$userInfo->id]);

            if (!$user) {
                $user = R::dispense('users');
                $user->google_id = $userInfo->id;
                $user->name = $userInfo->email;
                $user->email = $userInfo->email;
                $user->picture = $userInfo->picture;
                $user->token = $_GET['invite_code']; 
                $user->token_confirmed = 0;
                $user->join_date = date('Y-m-d H:i:s');
                R::store($user);
            } else {
                $user->token = $_GET['invite_code']; 
                $user->token_confirmed = 0;
                R::store($user);
            }

            $userData = [
                'id' => $user->id,
                'google_id' => $user->google_id,
                'name' => $user->name,
                'email' => $user->email,
                'picture' => $user->picture,
                'isStudent' => true,
                'register' => true
            ];
            $this->setCookie('user', json_encode($userData), time() + 604800);
            header('Location: /auth/student/complete');
            return;
        }

        header('Location: ' . $client->createAuthUrl());
        return;
    }

    public function DEV_joinWithGoogle($params)
    {
        if (isset($_COOKIE['user'])){
            $userData = json_decode($_COOKIE['user'], true);
            if ($userData['isStudent'] == true) {
                header("Location: /dashboard/student");
            } else {
                header("Location: /dashboard/teacher");
            }
            return;
        }

        if (!isset($_GET['invite_code'])) {
            header("Location: /");
            return;
        }

        $invitedTeacher = R::findOne('teachers', 'invite_code = ?', [$_GET['invite_code']]);

        if (!$invitedTeacher) {
            header('Location: /');
        }

        $user = R::findOne('users', 'google_id = ?', ['114869489688930855296']);

        if (!$user) {
            $user = R::dispense('users');
            $user->google_id = $userInfo->id;
            $user->name = $userInfo->email;
            $user->email = $userInfo->email;
            $user->picture = $userInfo->picture;
            $user->token = $_GET['invite_code']; 
            $user->token_confirmed = 0;
            $user->join_date = date('Y-m-d H:i:s');
            R::store($user);
        } else {
            $user->token = $_GET['invite_code']; 
            $user->token_confirmed = 0;
            R::store($user);
        }

        $userData = [
            'id' => $user->id,
            'google_id' => $user->google_id,
            'name' => $user->name,
            'email' => $user->email,
            'picture' => $user->picture,
            'isStudent' => true,
            'register' => false
        ];
        $this->setCookie('user', json_encode($userData), time() + 604800);
        header('Location: /auth/student/complete');
        return;
    }

    public function loginWithGoogle()
    {
        if (isset($_COOKIE['user'])){
            $userData = json_decode($_COOKIE['user'], true);
            if ($userData['isStudent'] == true) {
                header("Location: /dashboard/student");
            } else {
                header("Location: /dashboard/teacher");
            }
            return;
        }

        $client = new Google_Client();
        $client->setClientId($_ENV['GOOGLE_CLIENT_ID']);
        $client->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET']);
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';
        $client->setRedirectUri($root.$_ENV['GOOGLE_REDIRECT_URI']);
        $client->addScope('email');
        $client->addScope('openid');
        $client->addScope('https://www.googleapis.com/auth/userinfo.email');

        if (isset($_GET['code'])) {
            $accessToken = $client->fetchAccessTokenWithAuthCode($_GET['code']);
            $client->setAccessToken($accessToken);
            $oauth2 = new \Google_Service_Oauth2($client);
            $userInfo = $oauth2->userinfo->get();
            $user = R::findOne('users', 'google_id = ?', [$userInfo->id]);

            if (!$user) {
                $user = R::dispense('users');
                $user->google_id = $userInfo->id;
                $user->name = $userInfo->email;
                $user->email = $userInfo->email;
                $user->picture = $userInfo->picture;
                $user->join_date = date('Y-m-d H:i:s');
                R::store($user);
            }

            $userData = [
                'id' => $user->id,
                'google_id' => $user->google_id,
                'name' => $user->name,
                'email' => $user->email,
                'picture' => $user->picture,
                'isStudent' => true,
                'register' => true
            ];
            $this->setCookie('user', json_encode($userData), time() + 604800);
            header('Location: /auth/student/complete');
            return;
        }

        header('Location: ' . $client->createAuthUrl());
        return;
    }

    public function DEV_loginWithGoogle()
    {
        if (isset($_COOKIE['user'])){
            $userData = json_decode($_COOKIE['user'], true);
            if ($userData['isStudent'] == true) {
                header("Location: /dashboard/student");
            } else {
                header("Location: /dashboard/teacher");
            }
            return;
        }


        $user = R::findOne('users', 'google_id = ?', ["114869489688930855296"]);

        $userData = [
            'id' => $user->id,
            'google_id' => $user->google_id,
            'name' => $user->name,
            'email' => $user->email,
            'picture' => $user->picture,
            'isStudent' => true,
            'register' => true
        ];

        $this->setCookie('user', json_encode($userData), time() + 604800);
        header('Location: /dashboard/student');
        return;
    }

    public function loginAdmin()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $email = $data['email'];
        $password = md5($data['password']);

        if (empty($email) || empty($password)) {
            echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
            return;
        }
        $admin = R::findOne('admins', 'email = ? AND password = ?', [$email, $password]);
        if (!$admin) {
            echo json_encode(['success' => false, 'message' => 'Invalid username or password']);
            return;
        }
        $adminToken = json_encode(['token' => md5($email . $password)]);

        $this->setCookie('admin', $adminToken, time() + 3600);
        echo json_encode(['success' => true, 'message' => 'success auth']);
    }

    private function renderPartial($template, $params = [])
    {
        $latte = new \Latte\Engine;
        $latte->render(__DIR__ . "/../views/{$template}.latte", $params);
    }
}
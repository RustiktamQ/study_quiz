<?
namespace App\Controllers;
use Google_Client;
use RedBeanPHP\R;

class AuthController extends BaseController {

    private function setCookie($name, $value, $expire = 0) {
        setcookie($name, $value, $expire, "/"); // Устанавливаем cookie на весь сайт
    }

    private function getCookie($name) {
        return isset($_COOKIE[$name]) ? $_COOKIE[$name] : null;
    }

    private function deleteCookie($name) {
        setcookie($name, '', time() - 604800, "/"); // Удаляем cookie
    }

    public function showRegister()
    {
        $isAuthorized = isset($_COOKIE['user']);
        $user = $isAuthorized ? json_decode($_COOKIE['user'], true) : ['name' => '', 'picture' => ''];
        $fullName = $user['name'] ?? 'Guest';
        $firstName = explode(' ', $fullName)[0] ?? '';
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';
        $client = new Google_Client();
        $client->setClientId($_ENV['GOOGLE_CLIENT_ID']);
        $client->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET']);
        $client->setRedirectUri($root.$_ENV['GOOGLE_REDIRECT_URI']);
        $client->addScope('email');
        $authUrl = $client->createAuthUrl();
        if ($isAuthorized) {
            header("Location: /dashboard");
            exit;
        } else {
            $this->renderPartial('register', [
                'lang' => $this->lang,
                'uri' => $authUrl,
                'fullname' => $fullName,
                'firstname' => $firstName,
                'picture' => $user['picture'],
                'isAuthorized' => $isAuthorized,
                'ROOT_URL' => $root,
                'APP_NAME' => $_ENV['APP_NAME']
            ]);
        }
    }

    public function showRegisterTeacher()
    {
        $isAuthorized = isset($_COOKIE['teacher']);
        $user = $isAuthorized ? json_decode($_COOKIE['teacher'], true) : ['name' => '', 'picture' => ''];
        $fullName = $user['name'] ?? 'Guest';
        $firstName = explode(' ', $fullName)[0] ?? '';
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';
        $client = new Google_Client();
        $client->setClientId($_ENV['GOOGLE_CLIENT_ID']);
        $client->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET']);
        $client->setRedirectUri($root.$_ENV['GOOGLE_REDIRECT_URI']);
        $client->addScope('email');
        $authUrl = $client->createAuthUrl();
        if ($isAuthorized) {
            header("Location: /dashboard");
            exit;
        } else {
            $this->renderPartial('dashboard/register', [
                'lang' => $this->lang,
                'uri' => $authUrl,
                'fullname' => $fullName,
                'firstname' => $firstName,
                'picture' => $user['picture'],
                'isAuthorized' => $isAuthorized,
                'ROOT_URL' => $root,
                'APP_NAME' => $_ENV['APP_NAME']
            ]);
        }
    }

    public function showLoginTeacher()
    {
        $isAuthorized = isset($_COOKIE['teacher']);
        $user = $isAuthorized ? json_decode($_COOKIE['teacher'], true) : ['name' => '', 'picture' => ''];
        $fullName = $user['name'] ?? 'Guest';
        $firstName = explode(' ', $fullName)[0] ?? '';
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';
        $client = new Google_Client();
        $client->setClientId($_ENV['GOOGLE_CLIENT_ID']);
        $client->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET']);
        $client->setRedirectUri($root.$_ENV['GOOGLE_REDIRECT_URI']);
        $client->addScope('email');
        $authUrl = $client->createAuthUrl();
        if ($isAuthorized) {
            header("Location: /dashboard");
            exit;
        } else {
            $this->renderPartial('dashboard/login', [
                'lang' => $this->lang,
                'uri' => $authUrl,
                'fullname' => $fullName,
                'firstname' => $firstName,
                'picture' => $user['picture'],
                'isAuthorized' => $isAuthorized,
                'ROOT_URL' => $root,
                'APP_NAME' => $_ENV['APP_NAME']
            ]);
        }
    }

    public function registerTeacher()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $firstname = $data['firstname'];
        $lastname = $data['lastname'];
        $email = $data['email'];
        $password = password_hash($data['password'], PASSWORD_BCRYPT);
        if (empty($firstname) || empty($lastname) || empty($email) || empty($password)) {
            echo json_encode(['success' => false, 'message' => $this->lang['allfie']]);
            return;
        }
        $existingUser = R::findOne('teachers', 'email = ?', [$email]);
        if ($existingUser) {
            echo json_encode(['success' => false, 'message' => $this->lang['email_already']]);
            return;
        }
        $teacher = R::dispense('teachers');
        $teacher->name = $firstname." ".$lastname;
        $teacher->firstname = $firstname;
        $teacher->lastname = $lastname;
        $teacher->email = $email;
        $teacher->password = $password;
        $teacher->token = bin2hex(random_bytes(16));
        R::store($teacher);
        echo json_encode(['success' => true, 'message' => 'Registration successful.']);
    }    

    public function loginTeacher()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $email = $data['email'];
        $password = $data['password'];
        if (empty($email) || empty($password)) {
            echo json_encode(['success' => false, 'message' => $this->lang['require']]);
            return;
        }
        $teacher = R::findOne('teachers', 'email = ?', [$email]);
        if (!$teacher || !password_verify($password, $teacher->password)) {
            echo json_encode(['success' => false, 'message' => $this->lang['invalidep']]);
            return;
        }
        $teacherData = [
            'id' => $teacher->id,
            'name' => $teacher->name,
            'email' => $teacher->email,
            'picture' => $teacher->picture
        ];
        $this->setCookie('teacher', json_encode($teacherData), time() + 604800); // 1 hour cookie
        echo json_encode(['success' => true, 'message' => $this->lang['lsuccess']]);
    }

    public function loginWithGoogle()
    {
        if (isset($_COOKIE['user'])){
            header("Location: /learn");
            exit;
        }

        $client = new Google_Client();
        $client->setClientId($_ENV['GOOGLE_CLIENT_ID']);
        $client->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET']);
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';
        $client->setRedirectUri($root.$_ENV['GOOGLE_REDIRECT_URI']);
        $client->addScope('email');

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
                $user->created_at = date('Y-m-d H:i:s');
                R::store($user);
            }

            $userData = [
                'id' => $user->id,
                'google_id' => $user->google_id,
                'name' => $user->name,
                'email' => $user->email,
                'picture' => $user->picture
            ];
            $this->setCookie('user', json_encode($userData), time() + 604800); // 1 hour cookie
            header('Location: /learn');
            exit;
        }

        header('Location: ' . $client->createAuthUrl());
        exit;
    }

    private function renderPartial($template, $params = [])
    {
        $latte = new \Latte\Engine;
        $latte->render(__DIR__ . "/../views/{$template}.latte", $params);
    }
}

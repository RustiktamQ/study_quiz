<?
namespace App\Controllers;
use Google_Client;
use RedBeanPHP\R;

class AuthController extends BaseController {

    private function setCookie($name, $value, $expire = 0) {
        setcookie($name, $value, $expire, "/");
    }

    private function getCookie($name) {
        return isset($_COOKIE[$name]) ? $_COOKIE[$name] : null;
    }

    private function deleteCookie($name) {
        setcookie($name, '', time() - 604800, "/");
    }

    public function showStudentAuth()
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

        $this->renderPartial('auth/studentAuth', [
            'lang' => $this->lang,
            'uri' => $authUrl,
            'ROOT_URL' => $root,
            'APP_NAME' => $_ENV['APP_NAME']
        ]);
    }


    public function showTeacherRegister()
    {
        if (isset($_COOKIE['user'])){
            header("Location: /dashboard/teacher");
            exit;
        }

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';

        $this->renderPartial('auth/teacherRegister', [
            'lang' => $this->lang,
            'APP_NAME' => $_ENV['APP_NAME'],
            'ROOT_URL' => $root,
            'domain' => $_ENV['ROOT_URL']
        ]);
    }

    public function showTeacherlogin()
    {
        if (isset($_COOKIE['user'])){
            header("Location: /dashboard/teacher");
            exit;
        }

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';

        $this->renderPartial('auth/teacherLogin', [
            'lang' => $this->lang,
            'APP_NAME' => $_ENV['APP_NAME'],
            'ROOT_URL' => $root,
            'domain' => $_ENV['ROOT_URL']
        ]);
    }


    public function registerTeacher($params)
    {        
        header('Content-Type: application/json; charset=utf-8');
        $input = file_get_contents('php://input');
        $params = json_decode($input, true);

        if (
            !isset($params['first_name']) || strlen($params['first_name']) < 1 ||
            !isset($params['last_name']) || strlen($params['first_name']) < 1 ||
            !isset($params['token']) || strlen($params['first_name']) < 1 ||
            !isset($params['email']) || strlen($params['first_name']) < 1 ||
            !isset($params['school']) || strlen($params['first_name']) < 1
        ) {
            http_response_code(400);
            echo json_encode(['error' => true, 'message' => 'Missing required parameters']);
            return;
        }

        try {
            $res = R::exec(
                'INSERT INTO `teachers`
                (`name`, `picture`, `firstname`, `lastname`, `email`, `lang`, `token`, `join_date`, `school`)
                 VALUES  (?, ?, ?, ?, ?, ?, ?, ?, ?)',
                [
                    $params['first_name'] . " " . $params['first_name'],
                    "https://vk.com/images/wall/deleted_avatar_50.png",
                    $params['first_name'],
                    $params['first_name'],
                    $params['email'],
                    'ru',
                    $params['token'],
                    date('Y-m-d'),
                    $params['school']
                ]
            );

            if ($res == 0) {
                http_response_code(404);
                echo json_encode(['error' => true, 'message' => 'register fail']);
                return;
            }
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => true, 'message' => 'This token is already used']);
            return;
        }

        $teacher = R::findOne('teachers', 'token = ?', [$params['token']]);

        $userData = [
            'id' => $teacher->id,
            'name' => $teacher->name,
            'email' => $teacher->email,
            'lang' => $teacher->lang,
            'picture' => $teacher->picture,
            'isStudent' => false,
            'school' => $teacher->school
        ];

        $this->setCookie('user', json_encode($userData), time() + 604800);
        http_response_code(200);
        echo json_encode(['success' => true]);
    }  

    public function loginTeacher($params)
    {
        header('Content-Type: application/json; charset=utf-8');
        $input = file_get_contents('php://input');
        $params = json_decode($input, true);

        if (
            !isset($params['email']) || strlen($params['email']) < 1
        ) {
            http_response_code(400);
            echo json_encode(['error' => true, 'message' => 'Missing required parameters']);
            return;
        }

        $teacher = R::findOne('teachers', 'email = ?', [$params['email']]);

        if ($teacher) {
            $userData = [
                'id' => $teacher->id,
                'name' => $teacher->name,
                'email' => $teacher->email,
                'lang' => $teacher->lang,
                'picture' => $teacher->picture,
                'isStudent' => false,
                'school' => $teacher->school
            ];
    
            $this->setCookie('user', json_encode($userData), time() + 604800);
            http_response_code(200);
            echo json_encode(['success' => true]);
        } else {
            http_response_code(404);
            echo json_encode(['error' => true, 'message' => 'user not found']);
        }
    }

    public function loginWithGoogle()
    {
        if (isset($_COOKIE['user'])){
            header("Location: /dashboard/student");
            exit;
        }

        // $client = new Google_Client();
        // $client->setClientId($_ENV['GOOGLE_CLIENT_ID']);
        // $client->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET']);
        // $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        // $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';
        // $client->setRedirectUri($root.$_ENV['GOOGLE_REDIRECT_URI']);
        // $client->addScope('email');

        // if (isset($_GET['code'])) {
        //     $accessToken = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        //     $client->setAccessToken($accessToken);
        //     $oauth2 = new \Google_Service_Oauth2($client);
        //     $userInfo = $oauth2->userinfo->get();
        //     $user = R::findOne('users', 'google_id = ?', [$userInfo->id]);

        //     if (!$user) {
        //         $user = R::dispense('users');
        //         $user->google_id = $userInfo->id;
        //         $user->name = $userInfo->email;
        //         $user->email = $userInfo->email;
        //         $user->picture = $userInfo->picture;
        //         $user->created_at = date('Y-m-d H:i:s');
        //         R::store($user);
        //     }

            $userData = [
                'id' => '21',
                'google_id' => '114869489688930855296',
                'name' => 'qwe w',
                'email' => 'rramilperm@gmail.com',
                'picture' => 'https://lh3.googleusercontent.com/a-/ALV-UjVY3GWCzg8vA4glNlvC9x83Yl3qYs9AsnTAL-re3cKw2o7JSvd5=s96-c',
                'isStudent' => true
            ];
            $this->setCookie('user', json_encode($userData), time() + 604800);
            header('Location: /');
            exit;
        //}

        header('Location: ' . $client->createAuthUrl());
        exit;
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
        $teacher = R::findOne('admins', 'email = ? AND password = ?', [$email, $password]);
        if (!$teacher) {
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

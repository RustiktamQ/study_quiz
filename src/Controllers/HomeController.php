<?php
namespace App\Controllers;
use RedBeanPHP\R;

class HomeController extends BaseController {
    
    private function renderPartial($template, $params = [])
    {
        $latte = new \Latte\Engine;
        $latte->render(__DIR__ . "/../views/{$template}.latte", $params);
    }

    public function index()
    {
        $lang = $this->lang;
        $isAuthorized = isset($_COOKIE['user']);
        $role = 'guest';
        if (!$isAuthorized) {
            header("Location: /auth/signup");
            exit;
        }
        
        if (isset($_COOKIE['user'])) {
            $user = json_decode($_COOKIE['user'], true);
            if (isset($user['google_id'])) {
                $user = R::findOne('users', 'google_id = ?', [$user['google_id']]);
                if($user) {
                    $role = "student";
                    $fullName = $user->name ?? 'Guest';
                    $firstName = explode(' ', $fullName)[0] ?? '';
                    $picture = $user->picture ?? 'https://vk.com/images/wall/deleted_avatar_50.png';
                    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
                    $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';
                    $this->renderPartial('index', [
                        'lang' => $lang,
                        'APP_NAME' => $_ENV['APP_NAME'],
                        'ROOT_URL' => $root,
                        'fullname' => $fullName,
                        'firstname' => $firstName,
                        'picture' => $picture,
                        'isAuthorized' => $isAuthorized,
                        'role' => $role
                    ]);
                    return;
                }
            }
        } else if (isset($_COOKIE['teacher'])) {
            $teacherData = json_decode($_COOKIE['teacher']);
            $user = R::findOne('users', 'google_id = ?', [$teacherData['google_id']]);
            if ($user) {
                $role = 'teacher';
                $fullName = $user->name ?? 'Guest';
                $firstName = explode(' ', $fullName)[0] ?? '';
                $picture = $user->picture ?? 'https://vk.com/images/wall/deleted_avatar_50.png';
                $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
                $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';
                $this->renderPartial('index', [
                    'lang' => $lang,
                    'APP_NAME' => $_ENV['APP_NAME'],
                    'ROOT_URL' => $root,
                    'fullname' => $fullName,
                    'firstname' => $firstName,
                    'picture' => $picture,
                    'isAuthorized' => $isAuthorized,
                    'role' => $role
                ]);
                return;
            }
        }

    }

    public function showDashboard()
    {
        $isAuthorized = isset($_COOKIE['teacher']);
        $user = R::findOne('teachers', 'email = ?', [$_COOKIE['teacher']['email']]);
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';
        if (isset($_COOKIE['teacher'])){
            $this->renderPartial('dashboard/dashboard', [
                'lang' => $this->lang,
                'APP_NAME' => $_ENV['APP_NAME'],
                'ROOT_URL' => $root,
                'domain' => $_ENV['ROOT_URL'],
                'fullname' => $user->name,
                'picture' => "https://vk.com/images/wall/deleted_avatar_50.png",
                'isAuthorized' => $isAuthorized
            ]);
        } elseif (isset($_COOKIE['user'])) {
            header("Location: /learn");
            exit;
        } else {
            header("Location: /auth/signup");
            exit;
        }
    }

    public function showLearn()
    {
        $isAuthorized = isset($_COOKIE['user']);
        if (!$isAuthorized) {
            header("Location: /auth/signup");
            exit;
        }
        
        if (isset($_COOKIE['user'])) {
            $user = json_decode($_COOKIE['user'], true);
            if (isset($user['google_id'])) {
                $user = R::findOne('users', 'google_id = ?', [$user['google_id']]);
            }
        }
        
        $picture = $user->picture ?? 'https://vk.com/images/wall/deleted_avatar_50.png';
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';
        if (is_null($user->token)) {
            $this->renderPartial('learn/confirm', [
                'lang' => $this->lang,
                'APP_NAME' => $_ENV['APP_NAME'],
                'ROOT_URL' => $root,
                'fullname' => $user->name,
                'picture' => $picture,
                'error_message' => 'Вы не ввели токен. Хотя какой дебил сможет обойти это, думаю это лишнее'
            ]);
            return;
        }
        $teacher = R::findOne('teachers', 'token = ?', [$user->token]);
        if (!$teacher) {
            $this->renderPartial('learn/confirm', [
                'lang' => $this->lang,
                'APP_NAME' => $_ENV['APP_NAME'],
                'ROOT_URL' => $root,
                'fullname' => $user->name,
                'picture' => $picture,
                'error_message' => 'Преподаватель не найден или токен неверный.'
            ]);
            return;
        }    
        if ($user->token_confirmed == 0) {
            $this->renderPartial('learn/waiting', [
                'lang' => $this->lang,
                'APP_NAME' => $_ENV['APP_NAME'],
                'ROOT_URL' => $root,
                'fullname' => $user->name,
                'picture' => $picture,
                'error_message' => 'Токен преподавателя не подтвержден.'
            ]);
            return;
        }
        if (isset($_COOKIE['user'])){
            $this->renderPartial('learn/index', [
                'lang' => $this->lang,
                'APP_NAME' => $_ENV['APP_NAME'],
                'ROOT_URL' => $root,
                'domain' => $_ENV['ROOT_URL'],
                'fullname' => $user->name,
                'picture' => $user->picture,
                'isAuthorized' => $isAuthorized,
                'userid' => $user->id
            ]);
        } else {
            header("Location: /auth/signup");
            exit;
        }
    }

    public function showQuiz($params)
    {
        $isAuthorized = isset($_COOKIE['user']);
        if (!$isAuthorized) {
            header("Location: /auth/signup");
            exit;
        }
        
        $quizid = $params['id'];
        $userData = json_decode($_COOKIE['user'], true);
        $user = R::findOne('users', 'google_id = ?', [$userData['google_id']]);
        $quiz = R::findOne('quizzes', 'id = ?', [$quizid]);
        
        if(!$quiz){
            header("Location: /learn");
            exit; 
        }

        $progress = R::findOne('progress', 'student_id = ? AND quiz_id = ?', [$user->id, $quizid]);
        if (!$progress) {
            $firstQuestion = R::findOne('questions', 'quiz_id = ? ORDER BY id ASC', [$quizid]);
            $progress = R::dispense('progress');
            $progress->student_id = $user->id;
            $progress->quiz_id = $quizid;
            $progress->current_question = $firstQuestion->id;
            $progress->score = 0;
            $progress->completed = 0;
            $progress->start_time = date('Y-m-d H:i:s');
            R::store($progress);
        }
        
        $question = R::findOne('questions', 'id = ?', [$progress->current_question]);
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';    

        $this->renderPartial('learn/quiz', [
            'lang' => $this->lang,
            'APP_NAME' => $_ENV['APP_NAME'],
            'ROOT_URL' => $root,
            'domain' => $_ENV['ROOT_URL'],
            'fullname' => $user->name,
            'picture' => $user->picture,
            'isAuthorized' => $isAuthorized,
            'userid' => $user->id,
            'quizid' => $quizid,
            'name' => $quiz->name,
            'question' => $question->question_text,
            'options' => json_decode($question->options, true),
            'explanation' => $question->explanation
        ]);   
    }

    public function showFailQuiz($params)
    {
        $isAuthorized = isset($_COOKIE['user']);
        if (!$isAuthorized) {
            header("Location: /auth/signup");
            exit;
        }
        $quizid = $params['id'];
        $user = R::findOne('users', 'google_id = ?', [$_COOKIE['user']['google_id']]);
        $quiz = R::findOne('quizzes', 'id = ?', [$quizid]);
        if(!$quiz){
            header("Location: /learn");
            exit; 
        }
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';       
        $this->renderPartial('learn/fail', [
            'lang' => $this->lang,
            'APP_NAME' => $_ENV['APP_NAME'],
            'ROOT_URL' => $root,
            'domain' => $_ENV['ROOT_URL'],
            'fullname' => $user->name,
            'picture' => $user->picture,
            'isAuthorized' => $isAuthorized,
            'userid' => $user->id,
            'quizid' => $quizid,
            'name' => $quiz->name
        ]);   
    }

    public function saveProfile() {
        $data = json_decode(file_get_contents('php://input'), true);
        $firstName = $data['firstName'] ?? '';
        $lastName = $data['lastName'] ?? '';
        $token = $data['token'] ?? '';
        if (empty($firstName) || empty($lastName) || empty($token)) {
            echo json_encode(['success' => false, 'message' => 'All fields are required.']);
            return;
        }
        $teacher = R::findOne('teachers', 'token = ?', [$token]);
        if (!$teacher){
            echo json_encode(['success' => false, 'message' => 'Enter the correct token']);
            return;
        }
        if (isset($_COOKIE['user'])) {
            $userData = json_decode($_COOKIE['user'], true);
            if (isset($userData['id'])) {
                $user = R::load('users', $userData['id']);
            } else {
                error_log("User ID not found in cookie.");
            }
        } else {
            error_log("User cookie not set.");
        }

        $user->name = $firstName." ". $lastName;
        $user->firstname = $firstName;
        $user->lastname = $lastName;
        $user->token = $token;
        $user->token_confirmed = 0;
        R::store($user);
        echo json_encode(['success' => true]);
    }
}

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
                'error_message' => 'Вы не ввели токен'
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
        $notificationCollection = $this->getAllNotifs($user->id);
        if (isset($_COOKIE['user'])){
            $this->renderPartial('learn/index', [
                'lang' => $this->lang,
                'APP_NAME' => $_ENV['APP_NAME'],
                'ROOT_URL' => $root,
                'domain' => $_ENV['ROOT_URL'],
                'fullname' => $user->name,
                'picture' => $user->picture,
                'isAuthorized' => $isAuthorized,
                'userid' => $user->id,
                'notifications' => $notificationCollection
            ]);
        } else {
            header("Location: /auth/signup");
            exit;
        }
    }

    public function showQuiz($params)
    {
        $this->checkAuthorization();
        
        $quizid = $params['id'];
        $user = $this->user;
        $quiz = R::findOne('quizzes', 'id = ?', [$quizid]);
        
        if(!$quiz){
            header("Location: /learn");
            exit; 
        }

        $progress = R::findOne('progress', 'student_id = ? AND quiz_id = ?', [$user->id, $quizid]);
        
        if ($progress && $progress->completed == 1) {
            header("Location: /quiz/complete/$quizid");
            exit;
        }

        $isNewQuiz = false;
        if (!$progress) {
            $isNewQuiz = true;
            $progress = R::dispense('progress');
            $progress->current_question = -1;
        }

        $question = null;
        if (!$isNewQuiz && $progress->current_question > 0) {
            $question = R::findOne('questions', 'id = ?', [$progress->current_question]);
        }

        if ($isNewQuiz || !$question) {
            $question = new \stdClass();
            $question->question_text = 'Welcome to the quiz!';
            $question->options = json_encode([':)', 'XD', ':D', ':O']);
            $question->explanation = 'Press Button to continue';
        }

        $notificationCollection = $this->getAllNotifs($user->id);
        
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';
        
        $this->renderPartial('learn/quiz', [
            'lang' => $this->lang,
            'APP_NAME' => $_ENV['APP_NAME'],
            'ROOT_URL' => $root,
            'domain' => $_ENV['ROOT_URL'],
            'fullname' => $user->name,
            'picture' => $user->picture,
            'userid' => $user->id,
            'quizid' => $quizid,
            'name' => $quiz->name,
            'question' => $question->question_text,
            'options' => json_decode($question->options, true),
            'explanation' => $question->explanation,
            'isNewQuiz' => $isNewQuiz,
            'notifications' => $notificationCollection
        ]); 
    }
    public function showCompleteQuiz($params)
    {
        $isAuthorized = isset($_COOKIE['user']);
        if (!$isAuthorized) {
            header("Location: /auth/signup");
            exit;
        }
        $quizid = $params['id'];
        $userData = json_decode($_COOKIE['user'], true);
        $user = R::findOne('users', 'google_id = ?', [$userData['google_id']]);

        if (!$user) {
            header("Location: /learn");
            exit; 
        }

        $quiz = R::findOne('quizzes', 'id = ?', [$quizid]);

        if (!$quiz) {
            header("Location: /learn");
            exit; 
        }

        $progress = R::findOne('progress', 'student_id = ? AND quiz_id = ? AND completed = 1', [$user['id'], $quizid]);
        if (!$progress) {
            header("Location: /learn");
            exit;
        }

        $progress = R::findOne('progress', 'student_id = ? AND quiz_id = ? AND completed = 1', [$user['id'], $quizid]);

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';       

        $notificationCollection = $this->getAllNotifs($user->id);
        $this->renderPartial('learn/complete', [
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
            'score' => $progress->score,
            'correct_answers' => $progress->correct_answers,
            'elapsed_time' => $progress->elapsed_time,
            'notifications' => $notificationCollection
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

    public function showAdminPanel() {
        if (isset($_COOKIE['admin'])) {
            $admin = json_decode($_COOKIE['admin'], true);
            if (isset($admin['token'])) {
                $user = R::findOne('admins', 'token = ?', [$admin['token']]);
                if($user) {
                    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
                    $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';
                    
                    $currentHour = date('H');
                    $greeting = 'Welcome!';
                    if ($currentHour >= 6 && $currentHour < 12) {
                        $greeting = "Good morning";
                    } elseif ($currentHour >= 12 && $currentHour < 18) {
                        $greeting = "Good afternoon";
                    } elseif ($currentHour >= 18 && $currentHour < 21) {
                        $greeting = "Good evening";
                    } elseif ($currentHour >= 21 && $currentHour < 24) {
                        $greeting = "Good night";
                    } else {
                        $greeting = "Good night";
                    }
                    
                    $this->renderPartial('admin/index', [
                        'lang' => $this->lang,
                        'APP_NAME' => $_ENV['APP_NAME'],
                        'ROOT_URL' => $root,
                        'domain' => $_ENV['ROOT_URL'],
                        'fullname' => $user->email,
                        'picture' => 'https://info.qbl.sys.kth.se/user_avatar.png',
                        'greeting' => $greeting
                    ]);
                    exit;
                }
            } else {
                header("Location: /auth/adminPanel");
                exit;
            }
        }
        header("Location: /auth/adminPanel");
    }

    public function showLoginAdminPanel() {
        if (isset($_COOKIE['admin'])) {
            $admin = json_decode($_COOKIE['admin'], true);
            if (isset($admin['token'])) {
                $user = R::findOne('admins', 'token = ?', [$admin['token']]);
                if($user) {
                    header("Location: /adminPanel");
                }
            } 
        }

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';
        
        $this->renderPartial('admin/auth', [
            'lang' => $this->lang,
            'APP_NAME' => $_ENV['APP_NAME'],
            'ROOT_URL' => $root,
            'domain' => $_ENV['ROOT_URL'],
            'picture' => 'https://info.qbl.sys.kth.se/user_avatar.png',
        ]); 
    }

    private function generateNotificationLink(array $notification): string
    {
        $baseUrl = $this->getBaseUrl();
        
        return match($notification['address_type']) {
            'quiz' => $baseUrl . '/quiz/' . $notification['created_at'],
            'message' => $baseUrl . '/messages/' . $notification['created_at'],
            default => $baseUrl . '/notifications',
        };
    }

    private function getBaseUrl(): string
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') 
            ? 'https' 
            : 'http';
        return $protocol . '://' . $_ENV['ROOT_URL'];
    }

    private function getAllNotifs($userId) {
        $allNotifs = R::getAll(
            'SELECT * FROM notifications 
             WHERE address_type = ? AND address_id = ? 
             ORDER BY created_at DESC',
            ["student", $userId]
        );
        
        $notificationDTOs = [];
        foreach ($allNotifs as $notif) {
            try {
                $link = $this->generateNotificationLink($notif);
                $notificationDTOs[] = new NotificationDTO(
                    message: $notif['message'],
                    link: $link,
                    createdAt: new \DateTimeImmutable($notif['created_at']),
                    isRead: (bool)$notif['checked']
                );
            } catch (\Exception $e) {
                error_log("Error creating NotificationDTO: " . $e->getMessage());
            }
        }
        
        return $notificationCollection = new NotificationCollection(
            items: $notificationDTOs,
            unreadCount: count(array_filter($notificationDTOs, fn($n) => !$n->isRead))
        );
    }
}

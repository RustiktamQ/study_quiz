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

    public function showStudentDashboard()
    {
        $this->checkAuthorization();
        $user = $this->user;

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';

        $progress = R::findOne('progress', 'student_id = ? AND completed = 0 ORDER BY start_time ASC', [$user->id]);
        if (!$progress) {
            $quizId = 0;
        } else {
            $quizId = $progress->quiz_id;
        }
        
        $quiz = R::findOne('quizzes', 'id = ?', [$quizId]);

        $statistics = R::getRow(
            'SELECT SUM(completed) AS quizzes_completed, 
                    SUM(answered) AS answers_completed, 
                    SUM(TIME_TO_SEC(elapsed_time)) AS time_spent 
             FROM progress 
             WHERE student_id = ? 
             GROUP BY student_id',
            [$user->id]
        );

        $statistics['time_spent']= $this->secondsToHours($statistics['time_spent']);

        $this->renderPartial('dashboard/student/index', [
            'lang' => $this->lang,
            'APP_NAME' => $_ENV['APP_NAME'],
            'ROOT_URL' => $root,
            'domain' => $_ENV['ROOT_URL'],
            'fullname' => $user->name,
            'firstname' => $user->firstname,
            'picture' => $user->picture,
            'quiz' => $quiz,
            'progress' => $progress,
            'statistics' => $statistics
        ]);
    }

    public function showStudentTeacher()
    {
        $this->checkAuthorization();
        $user = $this->user;

        $teacher = R::findOne('teachers', 'token = ?', [$user->token]);

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';

        $teacher->join_date = $this->formatDate($teacher->join_date);
        $this->renderPartial('dashboard/student/teacher', [
            'lang' => $this->lang,
            'APP_NAME' => $_ENV['APP_NAME'],
            'ROOT_URL' => $root,
            'domain' => $_ENV['ROOT_URL'],
            'fullname' => $user->name,
            'firstname' => $user->firstname,
            'picture' => $user->picture,
            'teacher' => $teacher
        ]);
    }

    public function showStudentLearn()
    {
        $this->checkAuthorization();
        $user = $this->user;

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';

        $this->renderPartial('dashboard/student/learn', [
            'lang' => $this->lang,
            'APP_NAME' => $_ENV['APP_NAME'],
            'ROOT_URL' => $root,
            'domain' => $_ENV['ROOT_URL'],
            'fullname' => $user->name,
            'firstname' => $user->firstname,
            'picture' => $user->picture
        ]);
    }

    public function showStudentSettings()
    {
        $this->checkAuthorization();
        $user = $this->user;

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';

        $this->renderPartial('dashboard/student/settings', [
            'lang' => $this->lang,
            'APP_NAME' => $_ENV['APP_NAME'],
            'ROOT_URL' => $root,
            'domain' => $_ENV['ROOT_URL'],
            'fullname' => $user->name,
            'firstname' => $user->firstname,
            'picture' => $user->picture,
            'user' => $user
        ]);
    }

    public function showStudentAnalytics()
    {
        $this->checkAuthorization();
        $user = $this->user;

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';

        $this->renderPartial('dashboard/student/analytics', [
            'lang' => $this->lang,
            'APP_NAME' => $_ENV['APP_NAME'],
            'ROOT_URL' => $root,
            'domain' => $_ENV['ROOT_URL'],
            'fullname' => $user->name,
            'firstname' => $user->firstname,
            'picture' => $user->picture,
        ]);
    }

    public function showQuiz($params)
    {
        $this->checkAuthorization();
        
        $quizid = $params['id'];
        $user = $this->user;
        $quiz = R::findOne('quizzes', 'id = ?', [$quizid]);
        
        if(!$quiz){
            header("Location: /dashboard/student/learn");
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
            'firstname' => $user->firstname,
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
        $this->checkAuthorization();
        $quizid = $params['id'];
        $userData = $this->user;
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
            'firstname' => $user->firstname,
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

    public function showTeacherDashboard()
    {
        $this->checkTeacherAuthorization();
        $user = $this->user;

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';

        $this->renderPartial('dashboard/teacher/index', [
            'lang' => $this->lang,
            'APP_NAME' => $_ENV['APP_NAME'],
            'ROOT_URL' => $root,
            'domain' => $_ENV['ROOT_URL'],
            'fullname' => $user->name,
            'firstname' => $user->firstname,
            'picture' => $user->picture
        ]);
    }

    public function showTeacherProfile()
    {
        $this->checkTeacherAuthorization();
        $user = $this->user;

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';

        $this->renderPartial('dashboard/teacher/profile/index', [
            'lang' => $this->lang,
            'APP_NAME' => $_ENV['APP_NAME'],
            'ROOT_URL' => $root,
            'domain' => $_ENV['ROOT_URL'],
            'fullname' => $user->name,
            'firstname' => $user->firstname,
            'picture' => $user->picture
        ]);
    }

    public function showTeacherSettings()
    {
        $this->checkTeacherAuthorization();
        $user = $this->user;

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';

        $this->renderPartial('dashboard/teacher/profile/settings/settings', [
            'lang' => $this->lang,
            'APP_NAME' => $_ENV['APP_NAME'],
            'ROOT_URL' => $root,
            'domain' => $_ENV['ROOT_URL'],
            'fullname' => $user->name,
            'firstname' => $user->firstname,
            'picture' => $user->picture,
            'token' => $user->token
        ]);
    }

    public function showTeacherOrigin()
    {
        $this->checkTeacherAuthorization();
        $user = $this->user;

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';

        $this->renderPartial('dashboard/teacher/origin/index', [
            'lang' => $this->lang,
            'APP_NAME' => $_ENV['APP_NAME'],
            'ROOT_URL' => $root,
            'domain' => $_ENV['ROOT_URL'],
            'fullname' => $user->name,
            'firstname' => $user->firstname,
            'picture' => $user->picture
        ]);
    }

    public function showTeacherStudents()
    {
        $this->checkTeacherAuthorization();
        $user = $this->user;

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';

        $this->renderPartial('dashboard/teacher/students/index', [
            'lang' => $this->lang,
            'APP_NAME' => $_ENV['APP_NAME'],
            'ROOT_URL' => $root,
            'domain' => $_ENV['ROOT_URL'],
            'fullname' => $user->name,
            'firstname' => $user->firstname,
            'picture' => $user->picture
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

    private function secondsToHours($seconds) {
        $hours = $seconds / 3600;
        return number_format($hours, 2, '.', '');
    }

    private function formatDate($date) {
        $dt = new \DateTime($date);
        $day = $dt->format('d');
        $month = $dt->format('F');
        $year = $dt->format('Y');
    
        return "$day $month, $year";
    }
}

<?php
namespace App\Controllers;
use RedBeanPHP\R;

class HomeController extends BaseController {
    
    private function renderPartial($template, $params = [])
    {
        $latte = new \Latte\Engine;
        $latte->render(__DIR__ . "/../views/{$template}.latte", $params);
    }

    public function showIndex()
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';

        $this->renderPartial('main/index', [
            'lang' => $this->lang,
            'APP_NAME' => $_ENV['APP_NAME'],
            'ROOT_URL' => $root,
            'domain' => $_ENV['ROOT_URL'],
        ]);
    }

    public function showMainStudents()
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';

        $this->renderPartial('main/students/index', [
            'lang' => $this->lang,
            'APP_NAME' => $_ENV['APP_NAME'],
            'ROOT_URL' => $root,
            'domain' => $_ENV['ROOT_URL'],
        ]);
    }

    public function showMainTeachers()
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';

        $this->renderPartial('main/teachers/index', [
            'lang' => $this->lang,
            'APP_NAME' => $_ENV['APP_NAME'],
            'ROOT_URL' => $root,
            'domain' => $_ENV['ROOT_URL'],
        ]);
    }

    public function showMainAi()
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';

        $this->renderPartial('main/ai/index', [
            'lang' => $this->lang,
            'APP_NAME' => $_ENV['APP_NAME'],
            'ROOT_URL' => $root,
            'domain' => $_ENV['ROOT_URL'],
        ]);
    }

    public function showMainPircing()
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';

        $this->renderPartial('main/pricing/index', [
            'lang' => $this->lang,
            'APP_NAME' => $_ENV['APP_NAME'],
            'ROOT_URL' => $root,
            'domain' => $_ENV['ROOT_URL'],
        ]);
    }

    public function showMainSelect()
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';

        $this->renderPartial('main/select/index', [
            'lang' => $this->lang,
            'APP_NAME' => $_ENV['APP_NAME'],
            'ROOT_URL' => $root,
            'domain' => $_ENV['ROOT_URL'],
        ]);
    }

    public function showMainAndroid()
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';

        $this->renderPartial('main/android/index', [
            'lang' => $this->lang,
            'APP_NAME' => $_ENV['APP_NAME'],
            'ROOT_URL' => $root,
            'domain' => $_ENV['ROOT_URL'],
        ]);
    }

    // ??
    public function showMainСhooseacc()
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';

        $this->renderPartial('main/сhooseacc/index', [
            'lang' => $this->lang,
            'APP_NAME' => $_ENV['APP_NAME'],
            'ROOT_URL' => $root,
            'domain' => $_ENV['ROOT_URL'],
        ]);
    }

    public function showMainPrivacy()
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';

        $this->renderPartial('main/privacy/index', [
            'lang' => $this->lang,
            'APP_NAME' => $_ENV['APP_NAME'],
            'ROOT_URL' => $root,
            'domain' => $_ENV['ROOT_URL'],
        ]);
    }

    public function showMainDMA()
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';

        $this->renderPartial('main/delete-my-account/index', [
            'lang' => $this->lang,
            'APP_NAME' => $_ENV['APP_NAME'],
            'ROOT_URL' => $root,
            'domain' => $_ENV['ROOT_URL'],
        ]);
    }

    public function showMainTerms()
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';

        $this->renderPartial('main/terms/index', [
            'lang' => $this->lang,
            'APP_NAME' => $_ENV['APP_NAME'],
            'ROOT_URL' => $root,
            'domain' => $_ENV['ROOT_URL'],
        ]);
    }

    public function showMainContact()
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';

        $this->renderPartial('main/contact/index', [
            'lang' => $this->lang,
            'APP_NAME' => $_ENV['APP_NAME'],
            'ROOT_URL' => $root,
            'domain' => $_ENV['ROOT_URL'],
        ]);
    }

    // STUDENT DAHSBOARD
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

        if (!$statistics) {
            $statistics['time_spent'] = 0;
            $statistics['quizzes_completed'] = 0;
            $statistics['answers_completed'] = 0;
        }

        $statistics['time_spent'] = $this->secondsToHours($statistics['time_spent']);

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

        $teacher = R::findOne('teachers', 'invite_code = ?', [$user->token]);

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
            return; 
        }

        $progress = R::findOne('progress', 'student_id = ? AND quiz_id = ?', [$user->id, $quizid]);
        
        if ($progress && $progress->completed == 1) {
            header("Location: /quiz/complete/$quizid");
            return;
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
            return; 
        }

        $quiz = R::findOne('quizzes', 'id = ?', [$quizid]);

        if (!$quiz) {
            header("Location: /learn");
            return; 
        }

        $progress = R::findOne('progress', 'student_id = ? AND quiz_id = ? AND completed = 1', [$user['id'], $quizid]);
        if (!$progress) {
            header("Location: /learn");
            return;
        }

        $progress = R::findOne('progress', 'student_id = ? AND quiz_id = ? AND completed = 1', [$user['id'], $quizid]);

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';       

        $notificationCollection = $this->getAllNotifs($user->id);
        $this->renderPartial('learn/1complete', [
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
            'score' => $progress->score,
            'correct_answers' => $progress->answered,
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
        $teacher = R::findOne('teachers', 'invite_code = ?', [$token]);
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

    // ADMIN PANEL
    public function showAdminPanel() {
        if (isset($_COOKIE['admin'])) {
            $admin = json_decode($_COOKIE['admin'], true);
            if (isset($admin['token'])) {
                $user = R::findOne('admins', 'token = ?', [$admin['token']]);
                if($user) {
                    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
                    $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';

                    $totalUsers = R::getAll('SELECT
                        (SELECT COUNT(*) FROM users) +
                        (SELECT COUNT(*) FROM teachers) AS total_users;
                    ')[0];

                    $bannedUsers = R::getAll('SELECT COUNT(*) as bannedUsers FROM ban_list')[0];

                    $usersGrowth = R::getAll('
                        SELECT COUNT(*) AS total_last_month
                        FROM (
                            SELECT join_date FROM users
                            WHERE join_date >= CURDATE() - INTERVAL 1 MONTH
                            UNION ALL
                            SELECT join_date FROM teachers
                            WHERE join_date >= CURDATE() - INTERVAL 1 MONTH
                        ) AS combined;
                    ')[0]['total_last_month'];
                    
                    $this->renderPartial('admin/index', [
                        'lang' => $this->lang,
                        'APP_NAME' => $_ENV['APP_NAME'],
                        'ROOT_URL' => $root,
                        'domain' => $_ENV['ROOT_URL'],
                        'fullname' => $user->email,
                        'picture' => 'https://info.qbl.sys.kth.se/user_avatar.png',
                        'totalUsers' => $totalUsers['total_users'],
                        'bannedUsers' => $bannedUsers['bannedUsers'],
                        'usersGrowth' => $usersGrowth
                    ]);
                    return;
                }
            } else {
                header("Location: /auth/adminPanel");
                return;
            }
        } else {
        header("Location: /auth/adminPanel");
        }
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

    public function showAdminUsers()
    {
        $this->checkAdminAutorization();
        $user = $this->user;

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';

        $students = R::getAll('SELECT * FROM users');
        $teachers = R::getAll('SELECT * FROM teachers');

        foreach ($students as &$student) {
            $student['role'] = 'student';
        }
        foreach ($teachers as &$teacher) {
            $teacher['role'] = 'teacher';
        }
        $users = array_merge($students, $teachers);

        $this->renderPartial('admin/users', [
            'lang' => $this->lang,
            'APP_NAME' => $_ENV['APP_NAME'],
            'ROOT_URL' => $root,
            'domain' => $_ENV['ROOT_URL'],
            'users' => $users
        ]);
    }

    public function showAdminUsersEdit($params)
    {
        $this->checkAdminAutorization();
        $user = $this->user;

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';

        $student = R::findOne('users', 'id = ?', [$params['id']]);

        $this->renderPartial('admin/edituser', [
            'lang' => $this->lang,
            'APP_NAME' => $_ENV['APP_NAME'],
            'ROOT_URL' => $root,
            'domain' => $_ENV['ROOT_URL'],
            'student' => $student
        ]);
    }

    public function showAdminTeachersEdit($params)
    {
        $this->checkAdminAutorization();
        $user = $this->user;

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';

        $teacher = R::findOne('teachers', 'id = ?', [$params['id']]);

        $this->renderPartial('admin/editteacher', [
            'lang' => $this->lang,
            'APP_NAME' => $_ENV['APP_NAME'],
            'ROOT_URL' => $root,
            'domain' => $_ENV['ROOT_URL'],
            'teacher' => $teacher
        ]);
    }

    public function showAdminBannedUsers()
    {
        $this->checkAdminAutorization();
        $user = $this->user;

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';

        $bannedUsers = R::getAll('
        SELECT users.id, users.name, users.email, users.join_date, banned_date, reason, expire_date FROM `ban_list`
        JOIN users ON user_id = users.id');

        $this->renderPartial('admin/bannedusers', [
            'lang' => $this->lang,
            'APP_NAME' => $_ENV['APP_NAME'],
            'ROOT_URL' => $root,
            'domain' => $_ENV['ROOT_URL'],
            'bannedUsers' => $bannedUsers
        ]);
    }

    public function showAdminQuizzes()
    {
        $this->checkAdminAutorization();
        $user = $this->user;

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';

        $quizzes = R::getAll('
        SELECT 
        quizzes.id, 
        quizzes.name AS `title`, 
        categories.name AS `subject`, 
        sub_categories.name AS `category`, 
        COUNT(questions_quizzes.question_id) AS `questions`
        FROM quizzes
        JOIN categories ON quizzes.category_id = categories.id
        JOIN sub_categories ON quizzes.sub_category_id = sub_categories.id
        LEFT JOIN questions_quizzes ON quizzes.id = questions_quizzes.quiz_id
        GROUP BY quizzes.id, quizzes.name, categories.name, sub_categories.name');

        $this->renderPartial('admin/quizzes', [
            'lang' => $this->lang,
            'APP_NAME' => $_ENV['APP_NAME'],
            'ROOT_URL' => $root,
            'domain' => $_ENV['ROOT_URL'],
            'quizzes' => $quizzes
        ]);
    }

    public function showAdminQuizzesCreate()
    {
        $this->checkAdminAutorization();
        $user = $this->user;

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';

        $categories = R::getAll('SELECT * FROM categories');
        $subCategories = R::getAll('SELECT * FROM sub_categories');

        $this->renderPartial('admin/createquiz', [
            'lang' => $this->lang,
            'APP_NAME' => $_ENV['APP_NAME'],
            'ROOT_URL' => $root,
            'domain' => $_ENV['ROOT_URL'],
            'categories' => $categories,
            'subCategories' => $subCategories
        ]);
    }

    public function showAdminQuizzesManage($params)
    {
        $this->checkAdminAutorization();
        $user = $this->user;

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';

        $quizId = $params['id'];

        $quiz = R::getAll('
            SELECT quizzes.name, categories.name as `category`, sub_categories.name as `subCategory`, quizzes.grade
            FROM quizzes
            JOIN categories ON categories.id = category_id
            JOIN sub_categories ON sub_categories.id = sub_category_id
            WHERE quizzes.id = ?
        ', [$quizId]);

        if (!$quiz) {
            header('Location: /adminPanel/quizzes');
            return;
        }

        $questions = R::getAll('
        SELECT questions.id, questions.question_text, questions.correct_answer, questions.options, questions.explanation 
        FROM `questions_quizzes`
        JOIN questions ON questions.id = question_id
        WHERE quiz_id = ?
        ', [$quizId]);

        $categories = R::getAll('SELECT * FROM categories');
        $subCategories = R::getAll('SELECT * FROM sub_categories');

        $this->renderPartial('admin/managequiz', [
            'lang' => $this->lang,
            'APP_NAME' => $_ENV['APP_NAME'],
            'ROOT_URL' => $root,
            'domain' => $_ENV['ROOT_URL'],
            'quiz' => $quiz[0],
            'questions' => $questions,
            'categories' =>  $categories,
            'subCategories' => $subCategories
        ]);
    }

    public function showAdminCategories()
    {
        $this->checkAdminAutorization();
        $user = $this->user;

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';

        $categories = R::getAll('
        SELECT categories.id, categories.name, COUNT(quizzes.category_id) as `count` FROM categories
        LEFT JOIN quizzes ON quizzes.category_id = categories.id
        GROUP BY categories.name');

        $this->renderPartial('admin/categories', [
            'lang' => $this->lang,
            'APP_NAME' => $_ENV['APP_NAME'],
            'ROOT_URL' => $root,
            'domain' => $_ENV['ROOT_URL'],
            'categories' => $categories
        ]);
    }

    public function showAdminCategoriesCreate()
    {
        $this->checkAdminAutorization();
        $user = $this->user;

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';

        $this->renderPartial('admin/createcategory', [
            'lang' => $this->lang,
            'APP_NAME' => $_ENV['APP_NAME'],
            'ROOT_URL' => $root,
            'domain' => $_ENV['ROOT_URL']
        ]);
    }

    public function showAdminCategoriesEdit($params)
    {
        $this->checkAdminAutorization();
        $user = $this->user;

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';

        $categoryId = $params['id'];

        $category = R::findOne('categories', 'id = ?', [$categoryId]);

        $this->renderPartial('admin/editcategory', [
            'lang' => $this->lang,
            'APP_NAME' => $_ENV['APP_NAME'],
            'ROOT_URL' => $root,
            'domain' => $_ENV['ROOT_URL'],
            'category' => $category
        ]);
    }

    public function showAdminSubCategories()
    {
        $this->checkAdminAutorization();
        $user = $this->user;

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';

        $subCategories = R::getAll('SELECT * FROM sub_categories');

        $this->renderPartial('admin/subcategory', [
            'lang' => $this->lang,
            'APP_NAME' => $_ENV['APP_NAME'],
            'ROOT_URL' => $root,
            'domain' => $_ENV['ROOT_URL'],
            'subCategories' => $subCategories
        ]);
    }

    public function showAdminSubCategoriesCreate()
    {
        $this->checkAdminAutorization();
        $user = $this->user;

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';

        $this->renderPartial('admin/createsubcategory', [
            'lang' => $this->lang,
            'APP_NAME' => $_ENV['APP_NAME'],
            'ROOT_URL' => $root,
            'domain' => $_ENV['ROOT_URL']
        ]);
    }

    public function showAdminSubCategoriesEdit($params)
    {
        $this->checkAdminAutorization();
        $user = $this->user;

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';

        $categoryId = $params['id'];

        $category = R::findOne('sub_categories', 'id = ?', [$categoryId]);

        $this->renderPartial('admin/editsubcategory', [
            'lang' => $this->lang,
            'APP_NAME' => $_ENV['APP_NAME'],
            'ROOT_URL' => $root,
            'domain' => $_ENV['ROOT_URL'],
            'subCategory' => $category
        ]);
    }


    public function showTeacherDashboard()
    {
        $this->checkTeacherAuthorization();
        $user = $this->user;

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';

        $notifs = R::getAll('SELECT *, users.name, users.picture FROM notifications 
        JOIN users ON users.id = home_id
        WHERE address_type = "teacher" AND address_id = ?
        ORDER BY created_at DESC LIMIT 20', [$user->id]);

        $stats = R::getRow('
            SELECT 
                COUNT(DISTINCT users.id) AS total_students,
                COALESCE(SUM(TIME_TO_SEC(progress.elapsed_time)), 0) AS total_time_spent,
                COUNT(DISTINCT CASE WHEN progress.completed = 0 THEN users.id END) AS students_incomplete_quiz,
                COUNT(CASE WHEN progress.completed = 1 THEN 1 END) AS completed_quizzes,
                COALESCE(SUM(progress.answered), 0) AS total_answered_questions
            FROM users
            LEFT JOIN progress ON users.id = progress.student_id
            WHERE users.token = ? AND users.token_confirmed = 1;
        ', [$user['invite_code']]);


        $stats['total_time_spent'] = $this->secondsToHours($stats['total_time_spent']);

        $this->renderPartial('dashboard/teacher/index', [
            'lang' => $this->lang,
            'APP_NAME' => $_ENV['APP_NAME'],
            'ROOT_URL' => $root,
            'domain' => $_ENV['ROOT_URL'],
            'teacher' => $user,
            'notifs' => $notifs,
            'stats' => $stats
        ]);
    }

    public function showTeacherProfile()
    {
        $this->checkTeacherAuthorization();
        $user = $this->user;

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';

        $notifs = R::getAll('SELECT *, users.name, users.picture FROM notifications 
        JOIN users ON users.id = home_id
        WHERE address_type = "teacher" AND address_id = ?
        ORDER BY created_at DESC LIMIT 20', [$user->id]);

        $this->renderPartial('dashboard/teacher/profile/index', [
            'lang' => $this->lang,
            'APP_NAME' => $_ENV['APP_NAME'],
            'ROOT_URL' => $root,
            'domain' => $_ENV['ROOT_URL'],
            'teacher' => $user,
            'notifs' => $notifs
        ]);
    }

    public function showTeacherSettings()
    {
        $this->checkTeacherAuthorization();
        $user = $this->user;

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';

        $notifs = R::getAll('SELECT *, users.name, users.picture FROM notifications 
        JOIN users ON users.id = home_id
        WHERE address_type = "teacher" AND address_id = ?
        ORDER BY created_at DESC LIMIT 20', [$user->id]);

        $this->renderPartial('dashboard/teacher/profile/settings', [
            'lang' => $this->lang,
            'APP_NAME' => $_ENV['APP_NAME'],
            'ROOT_URL' => $root,
            'domain' => $_ENV['ROOT_URL'],
            'teacher' => $user,
            'notifs' => $notifs
        ]);
    }

    public function showTeacherStudents($params)
    {
        $this->checkTeacherAuthorization();
        $user = $this->user;

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';

        $perPage = 10;
        $page = 1;
        if (isset($params['page']) && is_numeric($params['page']) && $params['page'] > 0) {
            $page = (int)$params['page'];
        }
        $offset = ($page - 1) * $perPage;

        $teacher = R::findOne('teachers', 'id = ?', [$user['id']]);
        $students = R::getAll('
            SELECT
                users.id, 
                users.name, 
                users.picture, 
                COALESCE(SUM(completed), 0) AS quizzes_completed, 
                COALESCE(SUM(answered), 0) AS answers_completed, 
                COALESCE(SUM(TIME_TO_SEC(elapsed_time)), 0) AS time_spent 
            FROM users
            LEFT JOIN progress ON users.id = progress.student_id
            WHERE users.token = ? AND users.token_confirmed = 1
            GROUP BY users.id, users.name, users.picture
            LIMIT ? OFFSET ?
        ', [$teacher['invite_code'], $perPage, $offset]);

        foreach ($students as &$student) {
            $student['time_spent'] = $this->secondsToHours($student['time_spent']);
        }
        unset($student);

        $notConfirmed = R::getAll('SELECT * FROM users WHERE token = ? AND token_confirmed = 0', [$teacher['invite_code']]);

        $notifs = R::getAll('SELECT *, users.name, users.picture FROM notifications 
        JOIN users ON users.id = home_id
        WHERE address_type = "teacher" AND address_id = ?
        ORDER BY created_at DESC LIMIT 20', [$user->id]);

        $totalStudents = R::getCell('SELECT COUNT(*) FROM users WHERE token = ? AND token_confirmed = 1', [$teacher['invite_code']]) + count($notConfirmed);
        $totalPages = ceil($totalStudents / $perPage);

        $this->renderPartial('dashboard/teacher/students/index', [
            'lang' => $this->lang,
            'APP_NAME' => $_ENV['APP_NAME'],
            'ROOT_URL' => $root,
            'domain' => $_ENV['ROOT_URL'],
            'teacher' => $user,
            'students' => $students,
            'notConfirmed' => $notConfirmed,
            'notifs' => $notifs,
            'page' => $page,
            'totalStudents' => $totalStudents,
            'totalPages' => $totalPages
        ]);
    }


    public function showTeacherStudentsInvite()
    {
        $this->checkTeacherAuthorization();
        $user = $this->user;

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';

        $inviteCode = R::findOne('teachers', 'id = ?', [$user['id']])->invite_code;

        $notifs = R::getAll('SELECT *, users.name, users.picture FROM notifications 
        JOIN users ON users.id = home_id
        WHERE address_type = "teacher" AND address_id = ?
        ORDER BY created_at DESC LIMIT 20', [$user->id]);

        $this->renderPartial('dashboard/teacher/students/invite', [
            'lang' => $this->lang,
            'APP_NAME' => $_ENV['APP_NAME'],
            'ROOT_URL' => $root,
            'domain' => $_ENV['ROOT_URL'],
            'teacher' => $user,
            'invite_code' => $inviteCode,
            'notifs' => $notifs
        ]);
    }

    public function showTeacherStudentsView($params)
    {
        $this->checkTeacherAuthorization();
        $user = $this->user;

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';

        $studentId = $params['id'];

        $student = R::findOne('users', 'id = ?', [$studentId]);
        $statistics = R::getRow(
            'SELECT SUM(completed) AS quizzes_completed, 
                    SUM(answered) AS answers_completed, 
                    SUM(TIME_TO_SEC(elapsed_time)) AS time_spent 
             FROM progress 
             WHERE student_id = ? 
             GROUP BY student_id',
            [$studentId]
        );

        $quizzes = R::getAll('SELECT quizzes.name, categories.name as `categoryName`, progress.start_time FROM quizzes
        JOIN categories ON categories.id = quizzes.category_id
        JOIN progress ON progress.quiz_id = quizzes.id
        WHERE progress.completed = 0 AND progress.student_id = ?', [$studentId]);

        if (isset($statistics['time_spent'])) {
            $statistics['time_spent'] = $this->secondsToHours($statistics['time_spent']);
        } else {
            $statistics['time_spent'] = 0;
        }

        if (!isset($statistics['quizzes_completed'])) {
            $statistics['quizzes_completed'] = 0;
        }

        if (!isset($statistics['answers_completed'])) {
            $statistics['answers_completed'] = 0;
        }

        $notifs = R::getAll('SELECT *, users.name, users.picture FROM notifications 
        JOIN users ON users.id = home_id
        WHERE address_type = "teacher" AND address_id = ?
        ORDER BY created_at DESC LIMIT 20', [$user->id]);

        $this->renderPartial('dashboard/teacher/students/view', [
            'lang' => $this->lang,
            'APP_NAME' => $_ENV['APP_NAME'],
            'ROOT_URL' => $root,
            'domain' => $_ENV['ROOT_URL'],
            'teacher' => $user,
            'student' => $student,
            'statistics' => $statistics,
            'quizzes' =>  $quizzes,
            'notifs' => $notifs
        ]);
    }

    public function showTeacherManual()
    {
        $this->checkTeacherAuthorization();
        $user = $this->user;

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';

        $inviteCode = R::findOne('teachers', 'id = ?', [$user['id']])->invite_code;

        $notifs = R::getAll('SELECT *, users.name, users.picture FROM notifications 
        JOIN users ON users.id = home_id
        WHERE address_type = "teacher" AND address_id = ?
        ORDER BY created_at DESC LIMIT 20', [$user->id]);

        $this->renderPartial('dashboard/teacher/manual', [
            'lang' => $this->lang,
            'APP_NAME' => $_ENV['APP_NAME'],
            'ROOT_URL' => $root,
            'domain' => $_ENV['ROOT_URL'],
            'teacher' => $user,
            'invite_code' => $inviteCode,
            'notifs' => $notifs
        ]);
    }

    public function showTeacherLern()
    {
        $this->checkTeacherAuthorization();
        $user = $this->user;

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';

        $notifs = R::getAll('SELECT *, users.name, users.picture FROM notifications 
        JOIN users ON users.id = home_id
        WHERE address_type = "teacher" AND address_id = ?
        ORDER BY created_at DESC LIMIT 20', [$user->id]);

        $categories = R::getAll('SELECT name FROM categories');

        $this->renderPartial('dashboard/teacher/learn', [
            'lang' => $this->lang,
            'APP_NAME' => $_ENV['APP_NAME'],
            'ROOT_URL' => $root,
            'domain' => $_ENV['ROOT_URL'],
            'teacher' => $user,
            'notifs' => $notifs,
            'categories' => $categories
        ]);
    }

    public function showConfirmation()
    {
        $this->checkAuthorization();
        $user = $this->user;

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $root = $protocol . '://' . $_ENV['ROOT_URL'] . '/';

        $student = R::findOne('users', 'id = ?', [$user['id']]);

        if ($student->token_confirmed == 1) {
            header('Location: /');
        }

        $this->renderPartial('confirmation', [
            'lang' => $this->lang,
            'APP_NAME' => $_ENV['APP_NAME'],
            'ROOT_URL' => $root,
            'domain' => $_ENV['ROOT_URL'],
            'fullname' => $user->name,
            'firstname' => $user->firstname,
            'picture' => $user->picture
        ]);
    }

    private function generateNotificationLink(array $notification) {
        $baseUrl = $this->getBaseUrl();
        
        return match($notification['address_type']) {
            'quiz' => $baseUrl . '/quiz/' . $notification['created_at'],
            'message' => $baseUrl . '/messages/' . $notification['created_at'],
            default => $baseUrl . '/notifications',
        };
    }

    private function getBaseUrl() {
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
        $monthNum = (int)$dt->format('m');
        $year = $dt->format('Y');
    
        $months = [
            1 => 'января', 2 => 'февраля', 3 => 'марта',
            4 => 'апреля', 5 => 'мая', 6 => 'июня',
            7 => 'июля', 8 => 'августа', 9 => 'сентября',
            10 => 'октября', 11 => 'ноября', 12 => 'декабря'
        ];
    
        $month = $months[$monthNum];
    
        return "$day $month, $year";
    }
      
}

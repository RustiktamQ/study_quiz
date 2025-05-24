<?php
namespace App\Controllers;
use RedBeanPHP\R; 

class APIController extends BaseController {
    public function getGradeQuizzes($params) {
        header('Content-Type: application/json; charset=utf-8');

        if (!isset($params['grade'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required parameters']);
            return;
        }

        $res = R::getAll(
            'SELECT quizzes.id, quizzes.name as quiz, categories.name as subject, sub_categories.name as category, grade
             FROM quizzes
             JOIN categories ON category_id = categories.id 
             JOIN sub_categories ON sub_category_id = sub_categories.id
             WHERE grade = ?', [$params['grade']]
        );

        if (!$res) {
            http_response_code(404);
            echo json_encode(['error' => 'No questions found for this quiz']);
            return;
        }

        echo json_encode($res, JSON_PRETTY_PRINT);
    }
    
    public function startQuiz($params) {
        header('Content-Type: application/json; charset=utf-8');
        $input = file_get_contents('php://input');
        $params = json_decode($input, true);
    
        if (!isset($params['student_id'], $params['quiz_id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required parameters']);
            return;
        }
        $this->validateOwnership($params['student_id']);
        $studentId = $params['student_id'];
        $quizId = $params['quiz_id'];
        $progress = R::findOne('progress', 'student_id = ? AND quiz_id = ? AND completed = ?', [$studentId, $quizId, 0]);
    
        if ($progress) {
            $currentQuestionId = $progress->current_question;
        } else {
            $progress = R::dispense('progress');
            $startQuestion = R::findOne('questions_quizzes', 'quiz_id = ?', [$quizId]);
            if (!$startQuestion) {
                http_response_code(404);
                echo json_encode(['error' => 'No questions found for this quiz']);
                return;
            }
            $progress->student_id = $studentId;
            $progress->quiz_id = $quizId;
            $progress->current_question = $startQuestion->question_id;
            $progress->score = 0;
            $progress->completed = 0;
            $progress->answered = 0;
            $progress->start_time = date('Y-m-d H:i:s');
            R::store($progress);
            $currentQuestionId = $startQuestion->question_id;
        }
    
        $question = R::findOne('questions', 'id = ?', [$currentQuestionId]);
        $query = "SELECT name FROM quizzes JOIN `questions_quizzes` ON quizzes.id = quiz_id WHERE question_id = $currentQuestionId";
        $quizTitle = R::getRow($query);
    
        if (!$question) {
            http_response_code(404);
            echo json_encode(['error' => 'Question not found']);
            return;
        }
    
        echo json_encode([
            'current_question' => $currentQuestionId,
            'score' => $progress->score,
            'time' => strtotime($progress->start_time),
            'question' => [
                'id' => $question->id,
                'title' => $quizTitle['name'],
                'content' => $question->question_text,
                'answers' => json_decode($question->options, true)
            ]
        ]);
    }
    
    public function answerQuestion($params) {
        header('Content-Type: application/json; charset=utf-8');
        $input = file_get_contents('php://input');
        $params = json_decode($input, true);
        
        if (!isset($params['student_id'], $params['quiz_id'], $params['question_id'], $params['answer'], $params['elapsed_time'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required parameters']);
            return;
        }
        $this->validateOwnership($params['student_id']);
        $studentId = $params['student_id'];
        $quizId = $params['quiz_id'];
        $questionId = $params['question_id'];
        $answer = $params['answer'];
        $elapsedTime = $params['elapsed_time'];

        $progress = R::findOne('progress', 'student_id = ? AND quiz_id = ?', [$studentId, $quizId]);

        if (!$progress) {
            http_response_code(404);
            echo json_encode(['error' => 'No active quiz found for this student', 'code' => '30']);
            return;
        } else if ($progress->completed) {
            http_response_code(409);
            echo json_encode(['error' => 'This quiz has been completed', 'code' => '20']);
            return;
        }

        $question = R::findOne('questions', 'id = ?', [$questionId]);

        if (!$question) {
            http_response_code(404);
            echo json_encode(['error' => 'Invalid question']);
            return;
        }

        $isCorrect = $answer === $question->correct_answer;

        if ($isCorrect) {
            $progress->answered++;
            $newScore = $this->calculateScore($progress);
        } else {
            $penalty = $this->calculatePenalty($progress->score);
            $newScore = max(0, $progress->score - $penalty);
        }
    
        $progress->score = $newScore;
        $progress->elapsed_time = $elapsedTime;
        $nextQuest = $this->getNextQuestionId(
            $quizId, 
            $progress->current_question,
            $progress
        );

        if (is_null($nextQuest) || $progress->score >= 100) {
            $user = R::findOne('users', 'id = ?', [$studentId]);
            $teacherId = R::findOne('teachers', 'invite_code = ?', [$user->token])->id;
            $quizName = R::findOne('quizzes', 'id = ?', [$quizId])->name;

            $message = "Ученик $user->name прошел квиз $quizName";
            $this->notify($teacherId, 'teacher', $message, 'Уведомление', $studentId);
            $progress->completed = 1;
            $progress->end_time = date('Y-m-d H:i:s');
            R::store($progress);
            echo json_encode([
                'score' => $progress->score,
                'status' => 2
            ]);
            return;
        }

        $progress->current_question = $nextQuest;
        R::store($progress);

        echo json_encode([
            'current_question' => $progress->current_question,
            'score' => $progress->score,
            'status' => $isCorrect,
            'explanation' => $question->explanation,
            'correct_answer' => $question->correct_answer,
        ]);
    }

    public function getQuestion($params) {
        header('Content-Type: application/json; charset=utf-8');
        $input = file_get_contents('php://input');
        $params = json_decode($input, true);
        
        if (!isset($params['question_id'])) {
            http_response_code(400);
            var_dump($params);
            echo json_encode(['error' => 'Missing required parameters']);
            return;
        }

        $questionId = $params['question_id'];
        $question = R::findOne('questions', 'id = ?', [$questionId]);

        if (!$question) {
            http_response_code(404);
            echo json_encode(['error' => 'Question not found']);
            return;
        }

        echo json_encode([
            'question_text' => $question-> question_text,
            'correct_answer' => $question-> correct_answer,
            'options' => $question -> options,
            'explanation' => $question -> explanation
        ]);
        return;
    }

    public function getNextQuestion($params) {
        header('Content-Type: application/json; charset=utf-8');
        $input = file_get_contents('php://input');
        $params = json_decode($input, true);
        
        if (!isset($params['user_id']) || !isset($params['quiz_id'])) {
            http_response_code(400);
            var_dump($params);
            echo json_encode(['error' => 'Missing required parameters']);
            return;
        }
        $this->validateOwnership($params['user_id']);
        $userId = $params['user_id'];
        $quizId = $params['quiz_id'];
        $progress = R::findOne('progress', 'student_id = ? AND quiz_id = ?', [$userId, $quizId]);
        $question = R::findOne('questions', 'id = ?', [$progress->current_question]);

        if ($progress->completed == 1) {
            http_response_code(200);
            echo json_encode([
                'completed' => 1,
                'quiz_id' => $quizId,
                'user_id' => $userId,
                'score' => $progress->score
            ]);
            return;
        }

        if (!$question) {
            http_response_code(404);
            echo json_encode(['error' => 'Question not found']);
            return;
        }

        echo json_encode([
            'question_id' => $question->id,
            'question_text' => $question->question_text,
            'correct_answer' => $question->correct_answer,
            'options' => $question->options,
            'score' => $progress->score,
            'explanation' => $question->explanation
        ]);
        return;
    }

    public function resetQuiz($params) {
        header('Content-Type: application/json; charset=utf-8');
        $input = file_get_contents('php://input');
        $params = json_decode($input, true);
        
        if (!isset($params['user_id']) || !isset($params['quiz_id'])) {
            http_response_code(400);
            var_dump($params);
            echo json_encode(['error' => 'Missing required parameters']);
            return;
        }
        $this->validateOwnership($params['user_id']);
        $userId = $params['user_id'];
        $quizId = $params['quiz_id'];
        $quiz = R::findOne('quizzes', 'id = ?', [$quizId]);

        if (!$quiz) {
            header("Location: /learn");
            return; 
        }

        $progress = R::findOne('progress', 'student_id = ? AND quiz_id = ? AND completed = 1', [$userId, $quizId]);
        if (!$progress) {
            http_response_code(404);
            echo json_encode([
                'status' => 0,
                'error' => 'Completed quizzes not found'
            ]);
            return;
        }

        R::trash($progress);
        http_response_code(200);
        echo json_encode([
            'status' => 1
        ]);
        return;
    }

    public function deleteTeacherAccount($params) {
        header('Content-Type: application/json; charset=utf-8');
        $input = file_get_contents('php://input');
        $params = json_decode($input, true);

        if (!isset($params['user_id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required parameters']);
            return;
        }

        $this->validateOwnership($params['user_id']);

        try {
            $res = R::exec(
                'DELETE FROM teachers WHERE id = ?',
                [$this->user['id']]
            );

            if ($res == 0) {
                http_response_code(404);
                echo json_encode(['error' => true, 'message' => 'not found teacher']);
                return;
            }
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => true, 'message' => 'Database error', 'details' => $e->getMessage()]);
            return;
        }

        http_response_code(200);
        echo json_encode(['success' => true]);
        return;
    }

    public function updateTeacherData($params) {
        header('Content-Type: application/json; charset=utf-8');
        $input = file_get_contents('php://input');
        $params = json_decode($input, true);

        if (!isset($params['user_id'])) {
            http_response_code(400);
            echo json_encode(['error' => true, 'message' => 'Missing required parameters']);
            return;
        }

        $this->validateOwnership($params['user_id']);


        if (isset($params['data']['lang'])) {
            try {
                $res = R::exec(
                    'UPDATE teachers SET lang = ? WHERE id = ?',
                    [$params['data']['lang'], $this->user['id']]
                );
    
                if ($res == 0) {
                    http_response_code(404);
                    echo json_encode(['error' => true, 'message' => 'not found teacher']);
                    return;
                }
            } catch (PDOException $e) {
                http_response_code(500);
                echo json_encode(['error' => true, 'message' => 'Database error', 'details' => $e->getMessage()]);
                return;
            }
        }

        if (isset($params['data']['token'])) {
            try {
                $res = R::exec(
                    'UPDATE teachers SET token = ? WHERE id = ?',
                    [$params['data']['token'], $this->user['id']]
                );
        
                if ($res == 0) {
                    http_response_code(404);
                    echo json_encode(['error' => true, 'message' => 'not found teacher']);
                    return;
                }
            } catch (\Exception $e) {
                http_response_code(500);
                echo json_encode(['error' => true, 'message' => 'This token is already used']);
                return;
            }
        }

        http_response_code(200);
        echo json_encode(['success' => true]);
    }

    public function updateStudentDataT($params) {
        header('Content-Type: application/json; charset=utf-8');
        $input = file_get_contents('php://input');
        $params = json_decode($input, true);
    
        if (
            !isset($params['user_id']) ||
            !isset($params['teacher_id']) ||
            !isset($params['data']) ||
            !isset($params['data']['student_name']) ||
            !isset($params['data']['student_grade']) 
        ) {
            http_response_code(400);
            echo json_encode(['error' => true, 'message' => 'Missing required parameters']);
            return;
        }

        $this->validateOwnership($params['teacher_id']);

        $student = R::findOne('users', 'id = ?', [$params['user_id']]);      

        if (!$student) {
            http_response_code(404);
            echo json_encode(['error' => 'Student not found']);
            return;
        }

        $check = R::findOne('teachers', 'token = ?', [$student->token]);

        if (!$check) {
            http_response_code(403);
            echo json_encode(['error' => 'Access denied']);
            return;
        }
    
        R::exec(
            'UPDATE users SET name = ?, grade = ? WHERE id = ?',
            [$params['data']['student_name'], $params['data']['student_grade'], $params['user_id']]
        );
        http_response_code(200);
        echo json_encode(['success' => true]);
    }

    public function updateStudentData($params) {
        header('Content-Type: application/json; charset=utf-8');
        $input = file_get_contents('php://input');
        $params = json_decode($input, true);
    
        if (
            !isset($params['user_id']) ||
            !isset($params['data']['first_name']) ||
            !isset($params['data']['last_name'])
        ) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required parameters']);
            return;
        }
    
        $this->validateOwnership($params['user_id']);
    
        $userId = $params['user_id'];
        $firstName = $params['data']['first_name'];
        $lastName = $params['data']['last_name'];
        $fullName = "$firstName $lastName";

        $student = R::findOne('users', 'id = ?', [$userId]);

        if (empty($firstName) || empty($lastName)) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing Fields']);
            return;
        }        

        if (!$student) {
            http_response_code(404);
            echo json_encode(['error' => 'Student not found']);
            return;
        }

        if ($student->id != $this->user['id']) {
            http_response_code(403);
            echo json_encode(['error' => 'Access denied']);
            return;
        }
    
        R::exec(
            'UPDATE users SET firstname = ?, lastname = ?, name = ? WHERE id = ?',
            [$firstName, $lastName, $fullName, $userId]
        );
        http_response_code(200);
        echo json_encode(['success' => true]);
    }

    public function promtToAI($params) {
        header('Content-Type: application/json; charset=utf-8');
        $input = file_get_contents('php://input');
        $params = json_decode($input, true);

        if (!isset($params['user_id']) || !isset($params['prompt'])) {
            http_response_code(400);
            var_dump($params);
            echo json_encode(['error' => 'Missing required parameters']);
            return;
        }

        $this->validateOwnership($params['user_id']);

        $url = $_ENV['AI_API_URL'] . $_ENV['AI_API_KEY'];

        $data = [
            "contents" => [
                [
                    "parts" => [
                        ["text" => $params['prompt']]
                    ]
                ]
            ]
        ];

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            http_response_code(500);
            echo json_encode(['error' => true, 'message' => curl_error($ch)]);
        } else {
            echo json_encode(['success' => true, 'message' => $response]);
        }

        curl_close($ch);
    }

    public function unlink($params) {
        header('Content-Type: application/json; charset=utf-8');
        $input = file_get_contents('php://input');
        $params = json_decode($input, true);

        if (!isset($params['student_id']) || !isset($params['teacher_id'])) {
            http_response_code(400);
            echo json_encode(['error' => true, 'message' => 'Missing required parameters']);
            return;
        }
        $this->validateOwnership($params['teacher_id']);

        R::exec(
            'UPDATE users SET token = ?, token_confirmed = ? WHERE id = ?',
            [NULL, 0, $params['student_id']]
        );
        http_response_code(200);
        echo json_encode(['success' => true]);
    }

    public function codeIsFree($params) {
        header('Content-Type: application/json; charset=utf-8');
        $input = file_get_contents('php://input');
        $params = json_decode($input, true);

        if (!isset($params['teacher_id']) || !isset($params['code'])) {
            http_response_code(400);
            echo json_encode(['error' => true, 'message' => 'Missing required parameters']);
            return;
        }
        $this->validateOwnership($params['teacher_id']);

        $student = R::findOne('teachers', 'invite_code = ?', [$params['code']]);
        if ($student) {
            http_response_code(500);
            echo json_encode(['error' => true, 'message' => 'Code is already exist']);
            return;
        }
        http_response_code(200);
        echo json_encode(['success' => true]);
    }

    public function setCode($params) {
        header('Content-Type: application/json; charset=utf-8');
        $input = file_get_contents('php://input');
        $params = json_decode($input, true);

        if (!isset($params['teacher_id']) || !isset($params['code'])) {
            http_response_code(400);
            echo json_encode(['error' => true, 'message' => 'Missing required parameters']);
            return;
        }
        $this->validateOwnership($params['teacher_id']);

        R::exec(
            'UPDATE teachers SET invite_code = ? WHERE id = ?',
            [$params['code'], $params['teacher_id']]
        );
        http_response_code(200);
        echo json_encode(['success' => true]);
    }

    public function getTeacherCode($params) {
        header('Content-Type: application/json; charset=utf-8');
        $input = file_get_contents('php://input');
        $params = json_decode($input, true);

        if (!isset($params['teacher_id'])) {
            http_response_code(400);
            echo json_encode(['error' => true, 'message' => 'Missing required parameters']);
            return;
        }
        $this->validateOwnership($params['teacher_id']);

        $code = R::findOne('teachers', 'id = ?', [$params['teacher_id']])->invite_code;
        http_response_code(200);
        echo json_encode(['success' => true, 'code' => $code]);
    }

    public function checkStudentConfirm($params) {
        header('Content-Type: application/json; charset=utf-8');
        $input = file_get_contents('php://input');
        $params = json_decode($input, true);

        if (!isset($params['user_id'])) {
            http_response_code(400);
            echo json_encode(['error' => true, 'message' => 'Missing required parameters']);
            return;
        }
        $this->validateOwnership($params['user_id']);

        $isConfirmed = R::findOne('users', 'id = ?', [$params['user_id']])->token_confirmed;

        if ($isConfirmed) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Аккаунт ещё на потверждении']);
        }
        http_response_code(200);
    }

    public function confirmStudent($params) {
        header('Content-Type: application/json; charset=utf-8');
        $input = file_get_contents('php://input');
        $params = json_decode($input, true);

        if (!isset($params['user_id']) || !isset($params['student_id'])) {
            http_response_code(400);
            echo json_encode(['error' => true, 'message' => 'Missing required parameters']);
            return;
        }
        $this->validateOwnership($params['user_id']);

        $student = R::findOne('users', 'id = ?', [$params['student_id']]);

        if ($student->token_confirmed == 1) {
            http_response_code(400);
            echo json_encode(['error' => true, 'message' => 'Student already confirmed']);
            return;
        }

        $teacher = R::findOne('teachers', 'id = ?', [$params['user_id']]);
        $total = R::getAll('SELECT COUNT(*) as total FROM users WHERE token = ? AND token_confirmed = 1', [$teacher->invite_code])[0]['total'];

        if ($total == $teacher['students_limit'] || $total > $teacher['students_limit'] ) {
            http_response_code(400);
            echo json_encode(['error' => true, 'message' => 'Достигнуто максмальное количество учеников!']);
            return;
        }
        
        $student->token_confirmed = 1;
        R::store($student);
        http_response_code(200);
        echo json_encode(['success' => false]);
    }

    public function denyStudent($params) {
        header('Content-Type: application/json; charset=utf-8');
        $input = file_get_contents('php://input');
        $params = json_decode($input, true);

        if (!isset($params['user_id']) || !isset($params['student_id'])) {
            http_response_code(400);
            echo json_encode(['error' => true, 'message' => 'Missing required parameters']);
            return;
        }
        $this->validateOwnership($params['user_id']);

        $student = R::findOne('users', 'id = ?', [$params['student_id']]);

        if ($student->token_confirmed) {
            http_response_code(400);
            echo json_encode(['error' => true, 'message' => 'Student already confirmed']);
            return;
        }

        $student->token_confirmed = -1;
        $student->token = NULL;
        R::store($student);
        http_response_code(200);
        echo json_encode(['success' => true]);
    }

    public function editAdminTeacher($params) {
        header('Content-Type: application/json; charset=utf-8');
        $input = file_get_contents('php://input');
        $params = json_decode($input, true);

        if (
            !isset($params['id']) ||
            !isset($params['name']) ||
            !isset($params['email']) ||
            !isset($params['invite_code']) ||
            !isset($params['school']) ||
            !isset($params['specialization']) ||
            !isset($params['token'])
        ) {
            http_response_code(400);
            echo json_encode(['error' => true, 'message' => 'Missing required parameters']);
            return;
        }
        $this->validateAdmin($params['token']);

        R::exec(
            'UPDATE `teachers` SET `name`=?, `email`=?, `invite_code`=?, `school`=?, `specialization`=? WHERE id = ?',
            [$params['name'], $params['email'], $params['invite_code'], $params['school'], $params['specialization'], $params['id']]
        );
        

        http_response_code(200);
        echo json_encode(['success' => true]);
    }

    public function editAdminUser($params) {
        header('Content-Type: application/json; charset=utf-8');
        $input = file_get_contents('php://input');
        $params = json_decode($input, true);
    
        if (
            !isset($params['id']) ||
            !isset($params['name']) ||
            !isset($params['email']) ||
            !isset($params['token']) ||
            !isset($params['grade']) ||
            !isset($params['adminToken'])
        ) {
            http_response_code(400);
            echo json_encode(['error' => true, 'message' => 'Missing required parameters']);
            return;
        }
    
        $this->validateAdmin($params['adminToken']);
    
        R::exec(
            'UPDATE `users` SET `name`=?, `email`=?, `token`=?,`grade`=? WHERE id = ?',
            [
                $params['name'],
                $params['email'],
                $params['token'],
                $params['grade'],
                $params['id']
            ]
        );
    
        http_response_code(200);
        echo json_encode(['success' => true]);
    }    

    public function banUser($params) {

    }

    public function adminCreateQuiz($params) {
        header('Content-Type: application/json; charset=utf-8');
        $input = file_get_contents('php://input');
        $params = json_decode($input, true);

        if (
            !isset($params['name']) || strlen($params['name']) === 0 ||
            !isset($params['category']) ||
            !isset($params['subCategory']) ||
            !isset($params['grade']) ||
            !isset($params['token'])
        ) {
            http_response_code(400);
            echo json_encode(['error' => true, 'message' => 'Missing required parameters']);
            return;
        }
        
        $this->validateAdmin($params['token']);

        R::exec(
            'INSERT INTO `quizzes`(`name`, `category_id`, `sub_category_id`, `grade`) 
            VALUES (?, ?, ?, ?)',
            [$params['name'], $params['category'], $params['subCategory'], $params['grade']]
        );
        http_response_code(200);
        echo json_encode(['success' => true]);
    }

    public function deleteAdminQuizzes($params) {
        header('Content-Type: application/json; charset=utf-8');
        $input = file_get_contents('php://input');
        $params = json_decode($input, true);

        if (
            !isset($params['quizId']) ||
            !isset($params['token'])
        ) {
            http_response_code(400);
            echo json_encode(['error' => true, 'message' => 'Missing required parameters']);
            return;
        }
        $this->validateAdmin($params['token']);

        R::exec(
            'DELETE FROM `quizzes` WHERE id = ?',
            [$params['quizId']]
        );
        http_response_code(200);
        echo json_encode(['success' => true]);
    }

    public function editAdminQuizzes($params) {
        header('Content-Type: application/json; charset=utf-8');
        $input = file_get_contents('php://input');
        $params = json_decode($input, true);

        if (
            !isset($params['id']) ||
            !isset($params['title']) ||
            !isset($params['category']) ||
            !isset($params['subCategory']) ||
            !isset($params['grade']) ||
            !isset($params['token'])
        ) {
            http_response_code(400);
            echo json_encode(['error' => true, 'message' => 'Missing required parameters']);
            return;
        }
        $this->validateAdmin($params['token']);

        R::exec(
            'UPDATE `quizzes` SET `name`= ?, `category_id`= ?, `sub_category_id`= ?, `grade`= ? WHERE id = ?',
            [$params['title'], $params['category'], $params['subCategory'], $params['grade'], $params['id']]
        );
        http_response_code(200);
        echo json_encode(['success' => true]);
    }

    public function addAdminQuestions($params) {
        header('Content-Type: application/json; charset=utf-8');
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        $input = file_get_contents('php://input');
        $params = json_decode($input, true);
        if (empty($params['token'])) {
            http_response_code(400);
            echo json_encode(['error' => true, 'message' => 'Missing token']);
            return;
        }
        $this->validateAdmin($params['token']);

        if (empty($params['quizId'])) {
            http_response_code(400);
            echo json_encode(['error' => true, 'message' => 'Missing quizId']);
            return;
        }
        $quizId = (int)$params['quizId'];

        $quiz = R::findOne('quizzes', 'id = ?', [$quizId]);
        if (!$quiz) {
            http_response_code(400);
            echo json_encode(['error' => true, 'message' => "Quiz with id {$quizId} does not exist"]);
            return;
        }

        $questions = [];
        if (isset($params['json'])) {
            if (is_string($params['json'])) {
                $decoded = json_decode($params['json'], true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    http_response_code(400);
                    echo json_encode(['error' => true, 'message' => 'Invalid JSON format: ' . json_last_error_msg()]);
                    return;
                }
                $questions = $decoded;
            } elseif (is_array($params['json'])) {
                $questions = $params['json'];
            } else {
                http_response_code(400);
                echo json_encode(['error' => true, 'message' => 'Parameter json must be an array or a JSON string']);
                return;
            }
        } else {
            $questions[] = [
                'text'        => $params['text']        ?? '',
                'options'     => $params['options']     ?? [],
                'correct'     => $params['correct']     ?? '',
                'explanation' => $params['explanation'] ?? ''
            ];
        }

        $successCount = 0;
        $errors = [];
        foreach ($questions as $i => $q) {
            $required = ['text','options','correct','explanation'];
            $missing  = array_diff($required, array_keys($q));
            if ($q['text']==='' || $q['correct']==='' || !is_array($q['options']) || empty($q['options'])) {
                $missing = array_merge($missing, ['text/correct/options cannot be empty or invalid']);
            }
            if (!empty($missing)) {
                $errors[] = ['index'=>$i,'error'=>'Missing or invalid: '.implode(', ',$missing)];
                continue;
            }

            try {
                R::exec(
                    'INSERT INTO `questions` (`question_text`,`correct_answer`,`options`,`explanation`) VALUES (?,?,?,?)',
                    [
                        $q['text'],
                        $q['correct'],
                        json_encode($q['options'], JSON_UNESCAPED_UNICODE),
                        $q['explanation']
                    ]
                );
                $questionId = R::getInsertID();

                R::exec(
                    'INSERT INTO `questions_quizzes` (`quiz_id`,`question_id`) VALUES (?,?)',
                    [$quizId, $questionId]
                );

                $successCount++;
            } catch (Exception $e) {
                $errors[] = ['index'=>$i,'error'=>$e->getMessage()];
            }
        }

        $response = [
            'success' => $successCount > 0,
            'added'   => $successCount,
            'total'   => count($questions),
        ];
        if (!empty($errors)) {
            $response['errors'] = $errors;
            http_response_code($successCount>0 ? 207 : 400);
        } else {
            http_response_code(200);
        }

        echo json_encode($response);
    }

    public function editAdminQuestions($params) {
        header('Content-Type: application/json; charset=utf-8');
        $input = file_get_contents('php://input');
        $params = json_decode($input, true);

        if (
            !isset($params['questionId']) ||
            !isset($params['text']) ||
            !isset($params['options']) ||
            !isset($params['correct']) ||
            !isset($params['explanation']) ||
            !isset($params['token'])
        ) {
            http_response_code(400);
            echo json_encode(['error' => true, 'message' => 'Missing required parameters']);
            return;
        }
        $this->validateAdmin($params['token']);

        R::exec(
            'UPDATE `questions` SET `question_text`= ?, `correct_answer`=?, `options`=?, `explanation`= ? WHERE `id`= ?',
            [$params['text'], $params['correct'], $params['options'], $params['explanation'], $params['questionId']]
        );

        http_response_code(200);
        echo json_encode(['success' => true]);
    }

    public function deleteAdminQuestions($params) {
        header('Content-Type: application/json; charset=utf-8');
        $input = file_get_contents('php://input');
        $params = json_decode($input, true);

        if (
            !isset($params['questionId']) ||
            !isset($params['token'])
        ) {
            http_response_code(400);
            echo json_encode(['error' => true, 'message' => 'Missing required parameters']);
            return;
        }
        $this->validateAdmin($params['token']);

        R::exec(
            'DELETE FROM `questions` WHERE id = ?',
            [$params['questionId']]
        );

        http_response_code(200);
        echo json_encode(['success' => true]);
    }

    public function addAdminCategory($params) {
        header('Content-Type: application/json; charset=utf-8');
        $input = file_get_contents('php://input');
        $params = json_decode($input, true);

        if (
            !isset($params['name']) ||
            !isset($params['token'])
        ) {
            http_response_code(400);
            echo json_encode(['error' => true, 'message' => 'Missing required parameters']);
            return;
        }
        $this->validateAdmin($params['token']);

        R::exec(
            'INSERT INTO `categories`(`name`) VALUES (?)',
            [$params['name']]
        );

        http_response_code(200);
        echo json_encode(['success' => true]);
    }

    public function editAdminCategory($params) {
        header('Content-Type: application/json; charset=utf-8');
        $input = file_get_contents('php://input');
        $params = json_decode($input, true);

        if (
            !isset($params['name']) ||
            !isset($params['catId']) ||
            !isset($params['token'])
        ) {
            http_response_code(400);
            echo json_encode(['error' => true, 'message' => 'Missing required parameters']);
            return;
        }
        $this->validateAdmin($params['token']);

        R::exec(
            'UPDATE `categories` SET `name`= ? WHERE `id`= ?',
            [$params['name'], $params['catId']]
        );

        http_response_code(200);
        echo json_encode(['success' => true]);
    }

    public function deleteAdminCategory($params) {
        header('Content-Type: application/json; charset=utf-8');
        $input = file_get_contents('php://input');
        $params = json_decode($input, true);

        if (
            !isset($params['catId']) ||
            !isset($params['token'])
        ) {
            http_response_code(400);
            echo json_encode(['error' => true, 'message' => 'Missing required parameters']);
            return;
        }
        $this->validateAdmin($params['token']);

        $quizzes = R::find('quizzes', ' category_id = ? ', [$params['catId']]);
        if ($quizzes) {
            http_response_code(500);
            echo json_encode(['error' => true, 'message' => 'Cannot delete category while it used in quiz']);
            return;
        }

        R::exec(
            'DELETE FROM `categories` WHERE id = ?',
            [$params['catId']]
        );

        http_response_code(200);
        echo json_encode(['success' => true]);
    }

    public function addAdminSubCategory($params) {
        header('Content-Type: application/json; charset=utf-8');
        $input = file_get_contents('php://input');
        $params = json_decode($input, true);

        if (
            !isset($params['name']) ||
            !isset($params['token'])
        ) {
            http_response_code(400);
            echo json_encode(['error' => true, 'message' => 'Missing required parameters']);
            return;
        }
        $this->validateAdmin($params['token']);

        R::exec(
            'INSERT INTO `sub_categories` (`name`) VALUES (?)',
            [$params['name']]
        );

        http_response_code(200);
        echo json_encode(['success' => true]);
    }

    public function editAdminSubCategory($params) {
        header('Content-Type: application/json; charset=utf-8');
        $input = file_get_contents('php://input');
        $params = json_decode($input, true);

        if (
            !isset($params['name']) ||
            !isset($params['catId']) ||
            !isset($params['token'])
        ) {
            http_response_code(400);
            echo json_encode(['error' => true, 'message' => 'Missing required parameters']);
            return;
        }
        $this->validateAdmin($params['token']);

        R::exec(
            'UPDATE `sub_categories` SET `name`= ? WHERE `id`= ?',
            [$params['name'], $params['catId']]
        );

        http_response_code(200);
        echo json_encode(['success' => true]);
    }

    public function deleteAdminSubCategory($params) {
        header('Content-Type: application/json; charset=utf-8');
        $input = file_get_contents('php://input');
        $params = json_decode($input, true);

        if (
            !isset($params['catId']) ||
            !isset($params['token'])
        ) {
            http_response_code(400);
            echo json_encode(['error' => true, 'message' => 'Missing required parameters']);
            return;
        }
        $this->validateAdmin($params['token']);

        $quizzes = R::find('quizzes', ' sub_categories = ? ', [$params['catId']]);
        if ($quizzes) {
            http_response_code(500);
            echo json_encode(['error' => true, 'message' => 'Cannot delete subCategory while it used in quiz']);
            return;
        }

        R::exec(
            'DELETE FROM `sub_categories` WHERE id = ?',
            [$params['catId']]
        );

        http_response_code(200);
        echo json_encode(['success' => true]);
    }

    public function banAdminUser($params) {
        header('Content-Type: application/json; charset=utf-8');
        $input = file_get_contents('php://input');
        $params = json_decode($input, true);
    
        if (
            !isset($params['id']) || 
            !isset($params['reason']) || 
            !isset($params['token'])
        ) {
            http_response_code(400);
            echo json_encode(['error' => true, 'message' => 'Missing required parameters']);
            return;
        }
    
        $this->validateAdmin($params['token']);

        $userId = $params['id'];
        $reason = $params['reason'];

        $user = R::findOne('users', 'id = ?', [$userId]);

        if (!$user) {
            http_response_code(404);
            echo json_encode(['error' => true, 'message' => 'User not found']);
            return;
        }
    
        $bannedDate = date('Y-m-d H:i:s');
        $expireDate = date('Y-m-d H:i:s', strtotime('+30 days'));
    
        R::exec(
            'INSERT INTO ban_list (user_id, reason, banned_date, expire_date) VALUES (?, ?, ?, ?)',
            [$userId, $reason, $bannedDate, $expireDate]
        );
    
        http_response_code(200);
        echo json_encode(['success' => true, 'message' => 'User banned successfully']);
    }
    
    public function pardon($params) {
        header('Content-Type: application/json; charset=utf-8');
        $input = file_get_contents('php://input');
        $params = json_decode($input, true);
    
        if (!isset($params['id']) || !isset($params['token'])) {
            http_response_code(400);
            echo json_encode(['error' => true, 'message' => 'Missing required parameters']);
            return;
        }
    
        $this->validateAdmin($params['token']);
    
        $userId = $params['id'];
        $user = R::findOne('users', 'id = ?', [$userId]);
    
        if (!$user) {
            http_response_code(404);
            echo json_encode(['error' => true, 'message' => 'User not found']);
            return;
        }
    
        R::exec(
            'DELETE FROM `ban_list` WHERE user_id = ?',
            [$userId]
        );
    
        http_response_code(200);
        echo json_encode(['success' => true, 'message' => 'User pardoned successfully']);
    }

    public function studentRegister() {
        header('Content-Type: application/json; charset=utf-8');
        $input = file_get_contents('php://input');
        $params = json_decode($input, true);

        if (!isset($params['user_id'], $params['token'])) {
            http_response_code(400);
            echo json_encode(['error' => true, 'message' => 'Missing required parameters']);
            return;
        }
        $this->validateOwnership($params['user_id']);

        $student = R::findOne('users', 'id = ?', [$params['user_id']]);
        $teacher = R::findOne('teachers', 'invite_code = ?', [$params['token']]);
        
        if ($student) {
            if (!$teacher) {
                http_response_code(500);
                echo json_encode(['error' => true, 'message' => 'Code is incorrect!']);
                return;
            }
            $student->token = $params['token'];
            R::store($student);
        } else {
            http_response_code(404);
            echo json_encode(['error' => true, 'message' => 'User not found']);
            return;
        }

        $cookie = json_decode($this->getCookie('user'));
        $cookie->register = NULL;
        $userData = $cookie;
        $this->setCookie('user', json_encode($userData), time() + 604800);

        $this->notify($teacher->id, 'teacher', 'Запрашивает доступ к вашему классу', 'Запрос', $student->id);

        http_response_code(200);
        echo json_encode(['success' => true]);
        return;
    }

    public function getMonthCompletedQuizzes() {
        header('Content-Type: application/json; charset=utf-8');
        $input = file_get_contents('php://input');
        $params = json_decode($input, true);

        if (!isset($params['user_id'])) {
            http_response_code(400);
            echo json_encode(['error' => true, 'message' => 'Missing required parameters']);
            return;
        }
        $this->validateOwnership($params['user_id']);

        $thisTeacher = R::findOne('teachers', 'id = ?', [$params['user_id']]);

        $monthStart = date('Y-m-01');
        $nextMonthStart = date('Y-m-01', strtotime('first day of next month'));

        $stats = R::getAll('SELECT DATE(progress.end_time) AS day, COUNT(*) AS quizzes_completed FROM users 
            JOIN progress ON progress.student_id = users.id 
            WHERE users.token = ? 
            AND progress.completed = 1 
            AND progress.end_time >= ?
            AND progress.end_time < ?
            GROUP BY day ORDER BY day',
        [$thisTeacher->invite_code, $monthStart, $nextMonthStart]);
        
        if (!$stats) {
            http_response_code(404);
            echo json_encode(['error' => true, 'message' => 'Users not found']);
            return;
        }

        http_response_code(200);
        echo json_encode(['success' => true, 'data' => $stats]);
        return;
    }

    private function calculateScore($progress) {
        $correctCount = $progress->answered;
        $correctScores = [
            0 => 0,
            1 => 10,
            2 => 19,
            3 => 28,
            4 => 36,
            5 => 43,
            6 => 49,
            7 => 55,
            8 => 60,
            9 => 64,
            10 => 68,
            11 => 72,
            12 => 75,
            13 => 78,
            14 => 81,
            15 => 83,
            16 => 85,
            17 => 87,
            18 => 89,
            19 => 90,
        ];
        
        if ($correctCount >= 19) {
            return 90 + 2 * ($correctCount - 19);
        }
        
        return $correctScores[$correctCount] ?? 0;
    }

    private function calculatePenalty($currentScore) {
        if ($currentScore >= 90) {
            return 2;
        }
        
        $range = floor($currentScore / 10);
        return ($range % 2 == 0) ? 3 : 4;
    }
    
    private function getToltalQuestions($quizId) {
        $questions = R::getRow(
            'SELECT COUNT(*) as questions FROM `questions_quizzes` WHERE `quiz_id` = ?',
            [$quizId]
        );
    
        return $questions['questions'] ?? 0;
    }
    
    private function getNextQuestionId($quizId, $currentQuestionId, $progress) {
        if (!empty($progress->shuffled_questions)) {
            $shuffledIds = json_decode($progress->shuffled_questions, true);
            $currentIndex = $progress->current_question_index;

            if ($currentIndex + 1 < count($shuffledIds)) {
                $nextIndex = $currentIndex + 1;
                $progress->current_question_index = $nextIndex;
                $progress->current_question = $shuffledIds[$nextIndex];
                R::store($progress);
                return $shuffledIds[$nextIndex];
            } else {
                if ($progress->score < 100) {
                    $allQuestionIds = R::getCol('SELECT question_id FROM questions_quizzes WHERE quiz_id = ?', [$quizId]);
                    shuffle($allQuestionIds);
                    $progress->shuffled_questions = json_encode($allQuestionIds);
                    $progress->current_question_index = 0;
                    $progress->current_question = $allQuestionIds[0];
                    R::store($progress);
                    return $allQuestionIds[0];
                } else {
                    return null;
                }
            }
        } else {
            $nextQuestion = R::findOne(
                'questions_quizzes',
                'quiz_id = ? AND question_id > ? ORDER BY question_id ASC LIMIT 1',
                [$quizId, $currentQuestionId]
            );
            if ($nextQuestion) {
                return $nextQuestion->question_id;
            } else {
                if ($progress->score < 100) {
                    $allQuestionIds = R::getCol('SELECT question_id FROM questions_quizzes WHERE quiz_id = ?', [$quizId]);
                    shuffle($allQuestionIds);
                    $progress->shuffled_questions = json_encode($allQuestionIds);
                    $progress->current_question_index = 0;
                    $progress->current_question = $allQuestionIds[0];
                    R::store($progress);
                    return $allQuestionIds[0];
                } else {
                    return null;
                }
            }
        }
    }

    private function getLastQuestionId($quizId) {
        $nextQuestion = R::findOne(
            'questions_quizzes',
            'quiz_id = ? ORDER BY question_id DESC LIMIT 1',
            [$quizId]
        );
        return $nextQuestion->question_id;
    }

    private function notify($addressId, $addressType, $message, $type, $homeId)
    {
        try {
            $notification = R::dispense('notifications');
            $notification->message = $message;
            $notification->address_type = $addressType;
            $notification->address_id = $addressId;
            $notification->created_at = date('Y-m-d H:i:s');
            $notification->type = $type;
            $notification->home_id = $homeId;
            
            return (bool)R::store($notification);
        } catch (\Exception $e) {
            throw new \RuntimeException("Failed to create notification: " . $e->getMessage());
        }
    }
}
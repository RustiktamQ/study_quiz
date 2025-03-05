<?php
namespace App\Controllers;
use RedBeanPHP\R; 

class APIController extends BaseController {
    public function getAllQuizzes($params) {
        header('Content-Type: application/json; charset=utf-8');
        $studentId = $params['student_id'];
        $quizzes = R::findAll('quizzes');
        $progress = R::findAll('progress', 'student_id = ?', [$studentId]);
        $progressMap = [];
        foreach ($progress as $p) {
            $progressMap[$p->quiz_id] = [
                'completed' => (bool)$p->completed,
                'current_question' => $p->current_question,
            ];
        }
        $result = [];
        foreach ($quizzes as $quiz) {
            $quizId = $quiz->id;
            $result[] = [
                'id' => $quizId,
                'name' => $quiz->name,
                'completed' => $progressMap[$quizId]['completed'] ?? false,
                'current_question' => $progressMap[$quizId]['current_question'] ?? null,
            ];
        }
        echo json_encode(['quizzes' => $result]);
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
        
        if (!isset($params['student_id'], $params['quiz_id'], $params['question_id'], $params['answer'], $params['acknowledged'])) {
            http_response_code(400);
            var_dump($params);
            echo json_encode(['error' => 'Missing required parameters']);
            return;
        }

        $studentId = $params['student_id'];
        $quizId = $params['quiz_id'];
        $questionId = $params['question_id'];
        $answer = $params['answer'];
        $acknowledged = $params['acknowledged'];

        $progress = R::findOne('progress', 'student_id = ? AND quiz_id = ?', [$studentId, $quizId]);

        if (!$progress) {
            http_response_code(404);
            echo json_encode(['error' => 'No active quiz found for this student']);
            return;
        } else if ($progress->completed) {
            http_response_code(409);
            echo json_encode(['error' => 'This quiz has been completed']);
            return;
        }

        $question = R::findOne('questions', 'id = ?', [$questionId]);

        if (!$question) {
            http_response_code(404);
            echo json_encode(['error' => 'Invalid question']);
            return;
        }

        $isCorrect = $answer === $question->correct_answer;

        if (!$isCorrect && !$acknowledged) {
            echo json_encode([
                'correct_answer' => $question->correct_answer,
                'explanation' => $question->explanation,
                'next_step' => 'acknowledge',
                'status' => false
            ]);
            return;
        }

        $newScore = $progress->score;
        if ($isCorrect) {
            $newScore = $this->calculateScore($quizId, $progress->score);
        }
        
        $progress->score = $newScore;

        $questionId = $progress->current_question;
        if ($questionId != $this->getLastQuestionId($quizId)) {
            $progress->current_question = $this->getNextQuestionId($quizId, $progress->current_question);
            $progress->answered++;
        } else {
            $progress->completed = 1;
        }

        R::store($progress);

        echo json_encode([
            'current_question' => $progress->current_question,
            'score' => $progress->score,
            'completed' => $progress->completed,
            'status' => true
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
            'options' => $question -> options
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
            'score' => $progress->score
        ]);
        return;
    }

    private function calculateScore($quizId, $currentScore) {
        $totalQuestions = $this->getToltalQuestions($quizId);
    
        if ($totalQuestions == 0) {
            return $currentScore;
        }
    
        return $currentScore + ceil(100 / $totalQuestions);
    }
    
    private function getToltalQuestions($quizId) {
        $questions = R::getRow(
            'SELECT COUNT(*) as questions FROM `questions_quizzes` WHERE `quiz_id` = ?',
            [$quizId]
        );
    
        return $questions['questions'] ?? 0;
    }
    
    private function getNextQuestionId($quizId, $currentQuestionId) {
        $nextQuestion = R::findOne(
            'questions_quizzes',
            'quiz_id = ? AND question_id > ? ORDER BY question_id ASC LIMIT 1',
            [$quizId, $currentQuestionId]
        );
        return $nextQuestion->question_id;
    }

    private function getLastQuestionId($quizId) {
        $nextQuestion = R::findOne(
            'questions_quizzes',
            'quiz_id = ? ORDER BY question_id DESC LIMIT 1',
            [$quizId]
        );
        return $nextQuestion->question_id;
    }

    private function reshuffleQuestions($quizId) {
        // TODO
    }
}
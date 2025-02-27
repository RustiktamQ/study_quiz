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
            $progress->student_id = $studentId;
            $progress->quiz_id = $quizId;
            $progress->current_question = 1;
            $progress->score = 0;
            $progress->completed = 0;
            $progress->start_time = date('Y-m-d H:i:s');
            R::store($progress);
            $currentQuestionId = 1;
        }
    
        // Загружаем первый вопрос
        $question = R::findOne('questions', 'quiz_id = ? AND id = ?', [$quizId, $currentQuestionId]);
    
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
                'title' => $question->question_text,
                'content' => $question->content,
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

        $progress = R::findOne('progress', 'student_id = ? AND quiz_id = ? AND completed = ?', [$studentId, $quizId, 0]);

        if (!$progress) {
            http_response_code(400);
            echo json_encode(['error' => 'No active quiz found for this student']);
            return;
        }

        $question = R::findOne('questions', 'id = ? AND quiz_id = ?', [$questionId, $quizId]);

        if (!$question) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid question']);
            return;
        }

        // Если ответ неверный
        $isCorrect = $answer === $question->correct_answer;

        // Если ответ неверный, проверяем, был ли подтвержден показ объяснения
        if (!$isCorrect && !$acknowledged) {
            // Возвращаем объяснение
            echo json_encode([
                'correct_answer' => $question->correct_answer,
                'explanation' => $question->explanation,
                'next_step' => 'acknowledge'
            ]);
            return;
        }

        // Если пользователь подтвердил объяснение или ответ правильный
        $newScore = $progress->score;
        if ($isCorrect) {
            $newScore = $this->calculateScore($progress->score, true);
        } else {
            $newScore = $this->calculateScore($progress->score, false);
        }

        $progress->score = $newScore;
        $progress->current_question++;

        // Проверяем, не набрал ли студент 100 очков
        if ($progress->score >= 100) {
            $progress->completed = 1;
        }

        // Если студент набрал меньше 100 очков, перемешиваем вопросы и продолжаем
        if ($progress->score < 100 && $progress->current_question > $this->getTotalQuestions($quizId)) {
            // Перемешиваем вопросы и начинаем с первого
            $progress->current_question = 1;
            $this->reshuffleQuestions($quizId);
        }

        R::store($progress);

        echo json_encode([
            'current_question' => $progress->current_question,
            'score' => $progress->score,
            'completed' => $progress->completed
        ]);
    }

    private function calculateScore($currentScore, $isCorrect) {
        // REFACTOR
        if ($isCorrect) {
            if ($currentScore < 10) return $currentScore + 9;
            if ($currentScore < 20) return $currentScore + 10;
            if ($currentScore < 30) return $currentScore + 9;
            if ($currentScore < 40) return $currentScore + 8;
            if ($currentScore < 50) return $currentScore + 7;
            if ($currentScore < 60) return $currentScore + 6;
            if ($currentScore < 70) return $currentScore + 5;
            if ($currentScore < 80) return $currentScore + 4;
            if ($currentScore < 90) return $currentScore + 3;
            return $currentScore + 2;
        }

        if ($currentScore < 10) return $currentScore - 3;
        if ($currentScore < 20) return $currentScore - 4;
        if ($currentScore < 30) return $currentScore - 3;
        if ($currentScore < 40) return $currentScore - 4;
        if ($currentScore < 50) return $currentScore - 3;
        if ($currentScore < 60) return $currentScore - 4;
        if ($currentScore < 70) return $currentScore - 3;
        if ($currentScore < 80) return $currentScore - 4;
        if ($currentScore < 90) return $currentScore - 2;
        return $currentScore;
    }

    private function getTotalQuestions($quizId) {
        $questions = R::find('questions', 'quiz_id = ?', [$quizId]);
        return count($questions);
    }

    private function reshuffleQuestions($quizId) {
        // Извлекаем все вопросы для квиза и перемешиваем их
        $questions = R::find('questions', 'quiz_id = ?', [$quizId]);
        shuffle($questions);

        // Здесь можно логировать или обновить порядок вопросов в базе, если необходимо
        // В зависимости от требований, можно сохранять порядок вопросов или использовать его только для текущей сессии.
    }
}
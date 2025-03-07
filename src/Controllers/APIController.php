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
            $progress->correct_answers = 0;
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
            $progress->correct_answers++;
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
            $progress->completed = 1;
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
            'score' => $progress->score
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
            exit; 
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

    private function calculateScore($progress) {
        $correctCount = $progress->correct_answers;
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

    private function reshuffleQuestions($quizId) {
        // TODO
    }
}
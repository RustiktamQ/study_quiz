export class ApiController {
    async startQuiz(student_id, quiz_id) {
        try {
            const response = await fetch(`${window.location.origin}/quiz/start`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    student_id,
                    quiz_id
                })
            });
    
            return await response.json();
        } catch (error) {
            return error.message;;
        }
    }

    async answerQuestion(student_id, quiz_id, answer, question_id, elapsed_time) {
        try {
            const response = await fetch(`${window.location.origin}/quiz/answer`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    student_id,
                    quiz_id,
                    answer,
                    question_id,
                    elapsed_time
                })
            });
            return await response.json();
        } catch (error) {
            return error.message;
        }
    }

    async getNextQuestion(user_id, quiz_id) {
        try {
            const response = await fetch(`${window.location.origin}/api/getNextQuestion`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    user_id,
                    quiz_id
                })
            });
    

    
            return await response.json();
        } catch (error) {
            return error.message;
        }
    }

    async resetQuiz(user_id, quiz_id) {
        try {
            const response = await fetch(`${window.location.origin}/api/resetQuiz`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    user_id,
                    quiz_id
                })
            });
    

    
            return await response.json();
        } catch (error) {
            return error.message;
        }
    }

    async updateStudentData(user_id, data) {
        try {
            const response = await fetch(`${window.location.origin}/api/settings/update`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    user_id,
                    data
                })
            });
    
            return await response.json();
        } catch (error) {
            return error.message;
        }
    }

    async getGradeQuizzes(grade) {
        try {
            const response = await fetch(`${window.location.origin}/api/getAllQuizzes/${grade}`, {
                method: "GET",
                headers: {
                    "Content-Type": "application/json"
                }
            });
    
            return await response.json();
        } catch (error) {
            return error.message;
        }
    }

    async promtToAI(user_id, prompt) {
        try {
            const response = await fetch(`${window.location.origin}/api/ai/prompt`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    user_id,
                    prompt
                })
            });
    
            return await response.json();
        } catch (error) {
            return error.message;
        }
    }
}
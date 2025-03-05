class ApiController {
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
    
            if (!response.ok) {
                throw new Error(`Error: ${response.status}`);
            }
    
            return await response.json();
        } catch (error) {
            return error.message;;
        }
    }

    async answerQuestion(student_id, quiz_id, answer, question_id) {
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
                    acknowledged: false
                })
            });
    
            if (!response.ok) {
                throw new Error(`Error: ${response.status}`);
            }
    
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
    
            if (!response.ok) {
                throw new Error(`Error: ${response.status}`);
            }
    
            return await response.json();
        } catch (error) {
            return error.message;
        }
    }
}
class ApiController {
    isStarted = false

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
                throw new Error(`Ошибка: ${response.status}`);
            }
    
            return await response.json();
        } catch (error) {
            console.error("Error on quiz start:", error);
            return null;
        }
    }

    async answerQuestion(student_id, quiz_id, answer) {
        if (!this.isStarted) {
            this.startQuiz(student_id, quiz_id);
            this.isStarted = false
        }

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
                    acknowledged: false
                })
            });
    
            if (!response.ok) {
                throw new Error(`Ошибка: ${response.status}`);
            }
    
            return await response.json();
        } catch (error) {
            console.error("Error on quiz answer:", error);
            return null;
        }
    }
}
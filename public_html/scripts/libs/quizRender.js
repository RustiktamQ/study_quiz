export class QuizRender {
    constructor() {}

    render() {
        document.getElementById('questions-answered').innerText = this.answered;
        document.getElementById('score').innerText = `${this.score} / 100`;
        document.getElementById('question-content').innerText = this.question_text;
        const answBtns = document.getElementsByClassName('answer-btn');
        for(let i = 0; i < answBtns.length; i++) {
            answBtns[i].value = this.answers[i];
            answBtns[i].innerText = this.answers[i];
        }
    }

    update(quizData) {
        this.question_text = quizData.question.content;
        this.answers = quizData.question.answers;
        this.answered = quizData.answered;
        this.score = quizData.score;
    }

    blur() {
        document.getElementById("quiz-inner-container").style.filter = "blur(0px)";  
        document.getElementById("start-quiz").style.display = "none";
    }
}
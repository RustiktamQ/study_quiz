export class QuizTimer {
    constructor() {
        this.timer = null;
        this.totalSeconds = 0;
    }

    getformatTime() {
        let hours = Math.floor(this.totalSeconds / 3600);
        let minutes = Math.floor((this.totalSeconds % 3600) / 60);
        let secs = this.totalSeconds % 60;
        return `${hours}hr. ${minutes}min. ${secs}s.`;
    }

    updateTimerDisplay() {
        document.getElementById("time").innerText = this.getformatTime();
    }

    startTimer(quizId, offset = 0) {
        if (this.timer === null) {
            this.totalSeconds += offset;
            this.totalSeconds++;
            localStorage.setItem(`timerSec-${quizId}`, this.totalSeconds);
            this.updateTimerDisplay();
            this.timer = setInterval(() => {
                this.totalSeconds++;
                localStorage.setItem(`timerSec-${quizId}`, this.totalSeconds);
                this.updateTimerDisplay();
            }, 1000);
        }
    }

    stopTimer() {
        clearInterval(this.timer);
        this.timer = null;
    }

    resetTimer(quizId) {
        this.stopTimer();
        this.totalSeconds = 0;
        this.updateTimerDisplay();
        localStorage.removeItem(`timerSec-${quizId}`);
    }
}

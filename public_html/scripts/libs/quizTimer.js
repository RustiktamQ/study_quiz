export class QuizTimer {
    constructor() {
        this.timer = null;
        this.totalSeconds = 0;
    }

    getformatTime(withHour = true) {
        let hours = Math.floor(this.totalSeconds / 3600);
        let minutes = Math.floor((this.totalSeconds % 3600) / 60);
        let secs = this.totalSeconds % 60;
        if (secs < 10) secs = `0${secs}`
        if (minutes < 10) minutes = `0${minutes}`
        if (hours < 10) hours = `0${hours}`
        if (withHour) return `${hours}:${minutes}:${secs}`;
        if (!withHour) return `${minutes}:${secs}`;
    }

    updateTimerDisplay() {
        document.getElementById("time").innerText = this.getformatTime(false);
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

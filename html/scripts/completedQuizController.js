import { ApiController} from "/scripts/libs/apiController.js";
import { getQuizId } from "/scripts/libs/getQuizId.js";
import { getUserData } from "/scripts/libs/getUserData.js";
const apiController = new ApiController();
const quizId = getQuizId();
const user = getUserData();

const userId = user.id;
const duration = 3 * 1000;
const animationEnd = Date.now() + duration;
const defaults = { startVelocity: 30, spread: 360, ticks: 60, zIndex: 0 };

function randomInRange(min, max) {
  return Math.random() * (max - min) + min;
}

const interval = setInterval(function() {
  const timeLeft = animationEnd - Date.now();

  if (timeLeft <= 0) {
    return clearInterval(interval);
  }

  const particleCount = 50 * (timeLeft / duration);

  confetti(
    Object.assign({}, defaults, {
      particleCount,
      origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 },
    })
  );
  confetti(
    Object.assign({}, defaults, {
      particleCount,
      origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 },
    })
  );
}, 250);

document.getElementById('repeatBtn').addEventListener('click', async () => {
  const repeatRes = await apiController.resetQuiz(userId, quizId);
  if (repeatRes.status == 1) {
    localStorage.removeItem(`quiz-${quizId}`);
    localStorage.removeItem(`timerSec-${quizId}`);
    window.location.href = `${window.location.origin}/quiz/1`;
  } else {
    console.log('some error at reset quiz');
  }
});

document.getElementById('otherQuizzesBtn').addEventListener('click', async () => {
  window.location.href = `${window.location.origin}/dashboard/student/learn`;
});
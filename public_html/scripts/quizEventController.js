import { ApiController } from "/scripts/libs/apiController.js";
import { QuizRender } from "/scripts/libs/quizRender.js";
import { QuizTimer } from "/scripts/libs/quizTimer.js";
import { getQuizId } from "/scripts/libs/getQuizId.js";
import { getUserData } from "/scripts/libs/getUserData.js";

const apiController = new ApiController();
const quizTimer = new QuizTimer();
const quizRender = new QuizRender();
const quizId = getQuizId();
const user = getUserData();

const userId = user.id;

if (isStarted(quizId)) {
    const quizData = JSON.parse(localStorage.getItem(`quiz-${quizId}`), true);
    quizRender.update(quizData);
    quizRender.render();
}

function isStarted(quizId) {
    return (localStorage.getItem(`quiz-${quizId}`) != null || 
    localStorage.getItem(`quiz-${quizId}`) != undefined);
}

$(document).ready(function() {
    if (!isStarted(quizId)) {
        document.getElementById("quiz-inner-container").style.filter = "blur(5px)"; 
        document.getElementById("start-quiz").style.display = "block"; 
    }

    const savedTimer = localStorage.getItem(`timerSec-${quizId}`)
    if (savedTimer > 1) {
        quizTimer.startTimer(quizId, savedTimer);
    }

    const buttons = document.querySelectorAll('.answer-btn');
    buttons.forEach(btn => {
        btn.addEventListener('click', () => {
            buttons.forEach(b => {
                b.removeAttribute('selected');
            });
            btn.setAttribute('selected', 'true');
        });
    });

    const positivePhrases = [
        "Awesome!",
        "Great job!",
        "Perfect!",
        "Excellent!",
        "Well done!",
        "Correct!",
        "You rock!",
        "Bravo!",
        "Fantastic!",
        "Spot on!"
    ];

    const icon = document.getElementById('title-icon');
    const svg = icon.querySelector('svg');
    const path = document.createElementNS("http://www.w3.org/2000/svg", "path");
    
    function getRandomPhrase() {
        return positivePhrases[Math.floor(Math.random() * positivePhrases.length)];
    }

    // quiz start callback
    document.getElementById('start-quiz').addEventListener("click", async () => {
        svg.innerHTML = '';
        const quizData =  await apiController.startQuiz(user.id, quizId);
        quizData.answered = 0;
        quizRender.blur();
        quizRender.update(quizData);
        quizRender.render(quizData);
        quizTimer.startTimer(quizId);
        localStorage.setItem(`quiz-${quizId}`, JSON.stringify(quizData));
    })

    // next btn callback
    document.getElementById('nextQuestion').addEventListener("click", async () => {
        svg.innerHTML = '';
        const quizData = JSON.parse(localStorage.getItem(`quiz-${quizId}`), true);
        quizRender.update(quizData);
        quizRender.render();

        const nextBtn = document.getElementById('nextQuestion');
        nextBtn.disabled = true;
        nextBtn.classList.add('hidden');

        const answBtn = document.getElementById("submit-answer");
        answBtn.disabled = false;
        answBtn.classList.remove('hidden');

        const buttons = document.querySelectorAll('.answer-btn');
        buttons.forEach(btn => {
            btn.disabled = false;
            btn.classList.remove('bg-sky-400', 'bg-opacity-80', 'border-sky-600', 'hover:bg-sky-500', 'focus:bg-sky-500');
            btn.removeAttribute('selected');
        });
    
        document.getElementById('question-content').className = 'text-2xl my-4';
        document.getElementById('question-title').innerText = quizData.question.title;
        icon.classList.remove('visible');
    })

    // Go it callback
    document.getElementById('submit-exp').addEventListener("click", async () => {
        const nextData = await apiController.getNextQuestion(userId, quizId);
        const quizData = JSON.parse(localStorage.getItem(`quiz-${quizId}`), true);
        quizData.current_question = nextData.question_id;
        quizData.question.id = nextData.question_id;
        quizData.question.answers = JSON.parse(nextData.options, true);
        quizData.question.content = nextData.question_text;
        quizData.score = nextData.score;
    
        if (nextData.error) {
            // do something
        } else if (nextData.status == 2) {
            window.location.replace(`${window.location.origin}/quiz/complete/${quizId}`);
        }
    
        const buttons = document.querySelectorAll('.answer-btn');
        buttons.forEach(btn => {
            btn.disabled = false;
            btn.classList.remove('bg-sky-400', 'bg-opacity-80', 'border-sky-600', 'hover:bg-sky-500', 'focus:bg-sky-500');
            btn.removeAttribute('selected');
        });
    
        document.getElementById('submit-answer').classList.remove('hidden');
        document.getElementById('expBlock').classList.add('hidden');
        document.getElementById('question-content').className = 'text-2xl my-4';
        document.getElementById('question-title').innerText = quizData.question.title;
        icon.classList.remove('visible');
        localStorage.setItem(`quiz-${quizId}`, JSON.stringify(quizData));
        quizRender.update(quizData);
        quizRender.render();
    })


    // answer callback
    document.getElementById("submit-answer").addEventListener("click", async () => {
        const currentUrl = window.location.pathname;
        const quizId = currentUrl.substring(currentUrl.lastIndexOf('/') + 1);
        const quizData = JSON.parse(localStorage.getItem(`quiz-${quizId}`), true);
        const answer = document.querySelector('.answer-btn[selected]')?.value;

        if (!answer) {
            return;
        }

        const answerData = await apiController.answerQuestion(userId, quizId, answer, quizData.question.id, quizTimer.getformatTime());
        quizData.answered++;

        if (answerData.error) {
            if (answerData.code == 30) {
                window.location.replace(`${window.location.origin}/quiz/complete/${quizId}`);
            }
        }
        if (answerData.status == 1) {
            const nextData = await apiController.getNextQuestion(userId, quizId);
            quizData.current_question = nextData.question_id;
            quizData.question.id = nextData.question_id;
            quizData.question.answers = JSON.parse(nextData.options, true);
            quizData.question.content = nextData.question_text;
            quizData.score = answerData.score;

            const qContent = document.getElementById('question-content')
            qContent.innerText = 'The correct answer is:';
            qContent.classList.add('text-sm', 'text-gray-600');
            qContent.classList.remove('text-2xl');

            buttons.forEach(btn => {
                if (btn.value != answerData.correct_answer)  {
                    btn.disabled = true;
                } else {
                    btn.classList.add('bg-sky-400', 'bg-opacity-80', 'border-sky-600', 'hover:bg-sky-500', 'focus:bg-sky-500');
                    btn.setAttribute('selected', '');
                }
            });
            const nextBtn = document.getElementById('nextQuestion');
            nextBtn.disabled = false;
            nextBtn.classList.remove('hidden');

            const thisBtn = document.getElementById("submit-answer");
            thisBtn.disabled = true;
            thisBtn.classList.add('hidden');

            icon.classList.remove('incorrect-icon');
            icon.classList.add('correct-icon');
            path.setAttribute("d", "M5 13l4 4L19 7");
            path.setAttribute("stroke-linecap", "round");
            path.setAttribute("stroke-linejoin", "round");
            document.getElementById('question-title').innerText = getRandomPhrase();

            localStorage.setItem(`quiz-${quizId}`, JSON.stringify(quizData));
        } else if (answerData.status == 0) {
            document.getElementById('submit-answer').classList.add('hidden');
            document.getElementById('expBlock').classList.remove('hidden');
            document.getElementById('explanation').innerText = answerData.explanation;
            document.getElementById('question-title').innerText = 'Sorry, incorrect...';
            const qContent = document.getElementById('question-content')
            qContent.innerText = 'The correct answer is:';
            qContent.classList.add('text-sm', 'text-gray-600');
            qContent.classList.remove('text-2xl');

            const buttons = document.querySelectorAll('.answer-btn');
            buttons.forEach(btn => {
                if (btn.value != answerData.correct_answer)  {
                    btn.disabled = true;
                } else {
                    btn.classList.add('bg-sky-400', 'bg-opacity-80', 'border-sky-600', 'hover:bg-sky-500', 'focus:bg-sky-500');
                    btn.setAttribute('selected', '');
                }
            });

            icon.classList.remove('correct-icon');
            icon.classList.add('incorrect-icon');
            path.setAttribute("d", "M18 6L6 18M6 6l12 12");
            path.setAttribute("stroke-linecap", "round");
            path.setAttribute("stroke-linejoin", "round");
            icon.innerHTML = '<path d="M18 6L6 18M6 6l12 12"/>';
        } else if (answerData.status == 2) {
            window.location.replace(`${window.location.origin}/quiz/complete/${quizId}`);
        }

        svg.appendChild(path);
        icon.classList.add('visible');
    });
});
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
 
const main = function() {
    const options = document.querySelectorAll('.option-button');
    const submitBtn = document.querySelector('.quiz-container button[type="submit"]');
    let selectedOption = null;

    if (!isStarted(quizId)) {
        document.getElementById("quiz-inner-container").style.filter = "blur(5px)"; 
        document.getElementById("start-quiz").style.display = "block"; 
        options.forEach(button => {
            button.disabled = true;
        });
    }

    const savedTimer = localStorage.getItem(`timerSec-${quizId}`)
    if (savedTimer > 1) {
        quizTimer.startTimer(quizId, savedTimer);
    }

    options.forEach(button => {
        button.addEventListener('click', () => {
            options.forEach(btn => {
                btn.classList.remove('!border-blue-500', '!bg-blue-100', 'ring-2', 'ring-blue-300')
                btn.removeAttribute('selected');
            }); 

            button.classList.add('!border-blue-500', '!bg-blue-100', 'ring-2', 'ring-blue-300');
            button.setAttribute('selected', 'true');
            selectedOption = button.textContent.trim(); 

            if (submitBtn) submitBtn.disabled = false;
        });
    });

    const positivePhrases = [
        "Отлично! 🎉",
        "Хорошая работа! 👍",
        "Идеально! ✅",
        "Превосходно! 🌟",
        "Молодец! 👏",
        "Верно! ✔️",
        "Ты крут! 💪",
        "Браво! 🥳",
        "Фантастика! ✨",
        "Точно в цель! 🎯"
    ];
    
    const negativePhrases = [
        "Не совсем так... 🤔",
        "Почти, но нет. 🙃",
        "Нужно постараться ещё. 💭",
        "Попробуй снова. 🔄",
        "Не угадал(а). ❌",
        "Это неверно. 🚫",
        "Чуть-чуть мимо. 🫤",
        "Ошибочка вышла. 😅",
        "Не то... 🤷‍♂️",
        "Промах. 🎯❌"
    ];
    

    const quizBox = document.getElementById('quizBox');
    const nextBtn = document.getElementById('nextQuestion');
    const thisBtn = document.getElementById('submit-answer');
    const messBox = document.getElementById('messBox');
    const mess = document.getElementById('mess');
    
    function getRandomPhrase(phrases) {
        return phrases[Math.floor(Math.random() * phrases.length)];
    }

    // quiz start callback
    document.getElementById('start-quiz').addEventListener("click", async () => {
        const quizData =  await apiController.startQuiz(user.id, quizId);
        quizData.answered = 0;
        quizRender.blur();
        quizRender.update(quizData);
        quizRender.render(quizData);
        quizTimer.startTimer(quizId);
        localStorage.setItem(`quiz-${quizId}`, JSON.stringify(quizData));
        options.forEach(button => {
            button.disabled = false;
        });
    })

    // next btn callback
    document.getElementById('nextQuestion').addEventListener("click", async () => {
        const quizData = JSON.parse(localStorage.getItem(`quiz-${quizId}`), true);
        quizRender.update(quizData);
        quizRender.render();

        const nextBtn = document.getElementById('nextQuestion');
        nextBtn.disabled = true;
        nextBtn.classList.add('hidden');

        const answBtn = document.getElementById("submit-answer");
        answBtn.disabled = false;
        answBtn.classList.remove('hidden');

        messBox.classList.add('hidden');
        options.forEach(button => {
            button.removeAttribute('selected');
            button.disabled = false;
            button.classList = 'answer-btn option-button w-full p-4 md:p-5 border-2 border-gray-300 rounded-xl text-gray-700 hover:border-blue-400 hover:text-blue-600 bg-white focus:border-blue-500 focus:text-blue-700 focus:bg-blue-50 flex items-center justify-center text-lg md:text-xl font-medium';
        });

        quizBox.classList = 'border border-gray-200 h-full rounded-3xl flex flex-col items-center justify-center p-6 md:p-10 bg-white';

        const nextData = await apiController.getNextQuestion(userId, quizId);
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

        document.getElementById('submit-answer').classList.remove('hidden');
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

        if (answerData.code == 30) {
            localStorage.removeItem(`quiz-${quizId}`);
            localStorage.removeItem(`timerSec-${quizId}`);
            location.reload();
        }

        if (answerData.status == 1) {
            const nextData = await apiController.getNextQuestion(userId, quizId);
            quizData.current_question = nextData.question_id;
            quizData.question.id = nextData.question_id;
            quizData.question.answers = JSON.parse(nextData.options, true);
            quizData.question.content = nextData.question_text;
            quizData.score = answerData.score;

            nextBtn.disabled = false;
            nextBtn.classList.remove('hidden');
            thisBtn.disabled = true;
            thisBtn.classList.add('hidden');
            messBox.classList.remove('hidden');

            messBox.classList = 'mt-6 text-center p-4 mb-8 md:mb-10 bg-green-100 border border-green-300 rounded-lg shadow-inner';
            mess.classList = 'text-lg font-semibold green-green-800 flex items-center justify-center gap-2';

            mess.innerText = getRandomPhrase(positivePhrases);

            options.forEach(button => {
                if (button.hasAttribute('selected')) {
                    button.classList = 'option-button correct-answer relative w-full p-4 md:p-5 border-2 rounded-xl flex items-center justify-center text-lg md:text-xl font-bold cursor-not-allowed';
                } else {
                    button.classList = 'option-button incorrect-answer w-full p-4 md:p-5 border-2 rounded-xl flex items-center justify-center text-lg md:text-xl font-medium cursor-not-allowed'
                }
                button.disabled = true;
            });

            quizBox.classList = 'border-4 border-dashed border-green-300 h-full rounded-3xl flex flex-col items-center justify-center p-6 md:p-10 bg-gradient-to-br from-green-50 via-emerald-50 to-teal-50';

            localStorage.setItem(`quiz-${quizId}`, JSON.stringify(quizData));
        } else if (answerData.status == 0) {
            document.getElementById('submit-answer').classList.add('hidden');

            nextBtn.disabled = false;
            nextBtn.classList.remove('hidden');
            thisBtn.disabled = true;
            thisBtn.classList.add('hidden');
            messBox.classList.remove('hidden');

            messBox.classList = 'mt-6 text-center p-4 mb-8 md:mb-10 bg-red-100 border border-red-300 rounded-lg shadow-inner';
            mess.classList = 'text-lg font-semibold red-green-800 flex items-center justify-center gap-2';

            mess.innerText = getRandomPhrase(negativePhrases);

            options.forEach(button => {
                if (button.hasAttribute('selected')) {
                    button.classList = 'option-button bad-answer relative w-full p-4 md:p-5 border-2 rounded-xl flex items-center justify-center text-lg md:text-xl font-bold cursor-not-allowed';
                } else {
                    button.classList = 'option-button incorrect-answer w-full p-4 md:p-5 border-2 rounded-xl flex items-center justify-center text-lg md:text-xl font-medium cursor-not-allowed'
                }
                button.disabled = true;
            });

            quizBox.classList = 'border-4 border-dashed border-red-300 h-full rounded-3xl flex flex-col items-center justify-center p-6 md:p-10 bg-gradient-to-br from-red-50 via-rose-50 to-pink-50';

        } else if (answerData.status == 2) {
            window.location.replace(`${window.location.origin}/quiz/complete/${quizId}`);
        }
    });
}();
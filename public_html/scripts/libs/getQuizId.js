export function getQuizId() {
    const currentUrl = window.location.pathname;
    const quizId = currentUrl.substring(currentUrl.lastIndexOf('/') + 1);
    return quizId;
}
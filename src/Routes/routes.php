<?php
// Main
$r->addRoute('GET', '/', ['HomeController', 'index']);
$r->addRoute('GET', '/lang/{lang}', ['LanguageController', 'switchLanguage']);
$r->addRoute('GET', '/confirmation', ['HomeController', 'showConfirmation']);

// Admin
$r->addRoute('GET', '/adminPanel', ['HomeController', 'showAdminPanel']);
$r->addRoute('GET', '/auth/adminPanel', ['HomeController', 'showLoginAdminPanel']);
$r->addRoute('GET', '/adminPanel/users', ['HomeController', 'showAdminUsers']);
$r->addRoute('GET', '/adminPanel/user/edit/{id}', ['HomeController', 'showAdminUsersEdit']);
$r->addRoute('GET', '/adminPanel/teacher/edit/{id}', ['HomeController', 'showAdminTeachersEdit']);
$r->addRoute('GET', '/adminPanel/users/banned', ['HomeController', 'showAdminBannedUsers']);
$r->addRoute('GET', '/adminPanel/quizzes', ['HomeController', 'showAdminQuizzes']);
$r->addRoute('GET', '/adminPanel/quizzes/create', ['HomeController', 'showAdminQuizzesCreate']);
$r->addRoute('GET', '/adminPanel/quizzes/manage', ['HomeController', 'showAdminQuizzesManage']);
$r->addRoute('GET', '/adminPanel/categories', ['HomeController', 'showAdminCategories']);
$r->addRoute('GET', '/adminPanel/categories/create', ['HomeController', 'showAdminCategoriesCreate']);
$r->addRoute('GET', '/adminPanel/grades', ['HomeController', 'showAdminGrades']);
$r->addRoute('GET', '/adminPanel/grades/create', ['HomeController', 'showAdminGradesCreate']);

$r->addRoute('POST', '/auth/adminPanel', ['AuthController', 'loginAdmin']);

// Auth
$r->addRoute('GET', '/auth/signup', ['AuthController', 'showStudentAuth']);
$r->addRoute('GET', '/auth/callback', ['AuthController', 'loginWithGoogle']);
$r->addRoute('GET', '/auth/callback/teacher', ['AuthController', 'teacherAuthWithGoogle']);
$r->addRoute('GET', '/auth/teacher/auth', ['AuthController', 'showTeacherAuth']);
$r->addRoute('GET', '/auth/teacher/register/complete', ['AuthController', 'showTeacherRegister']);

$r->addRoute('POST', '/api/register-teacher', ['AuthController', 'teacherRegister']);

// API
$r->addRoute('POST', '/api/save-profile', ['HomeController', 'saveProfile']);
$r->addRoute('GET', '/api/check-token-status', ['HomeController', 'checkTokenStatus']);
$r->addRoute('POST', '/api/settings/update', ['APIController', 'updateStudentData']);
$r->addRoute('POST', '/api/settings/teacher/update', ['APIController', 'updateTeacherData']);
$r->addRoute('POST', '/api/settings/teacher/delete', ['APIController', 'deleteTeacherAccount']);
$r->addRoute('POST', '/api/teacher/student/update', ['APIController', 'updateStudentDataT']);
$r->addRoute('POST', '/api/teacher/student/unlink', ['APIController', 'unlink']);
$r->addRoute('POST', '/api/teacher/check/code', ['APIController', 'codeIsFree']);
$r->addRoute('POST', '/api/teacher/set/code', ['APIController', 'setCode']);
$r->addRoute('POST', '/api/student/join/{code}', ['APIController', 'setCode']);

// Student dashboard
$r->addRoute('GET', '/dashboard/student', ['HomeController', 'showStudentDashboard']);
$r->addRoute('GET', '/dashboard/student/learn', ['HomeController', 'showStudentLearn']);
$r->addRoute('GET', '/dashboard/student/teacher', ['HomeController', 'showStudentTeacher']);
$r->addRoute('GET', '/dashboard/student/analytics', ['HomeController', 'showStudentAnalytics']);
$r->addRoute('GET', '/dashboard/student/settings', ['HomeController', 'showStudentSettings']);

// Teacher dashboard
$r->addRoute('GET', '/dashboard/teacher', ['HomeController', 'showTeacherDashboard']);
$r->addRoute('GET', '/dashboard/teacher/profile', ['HomeController', 'showTeacherProfile']);
$r->addRoute('GET', '/dashboard/teacher/settings', ['HomeController', 'showTeacherSettings']);
$r->addRoute('GET', '/dashboard/teacher/origin', ['HomeController', 'showTeacherOrigin']); // ???
$r->addRoute('GET', '/dashboard/teacher/students', ['HomeController', 'showTeacherStudents']);
$r->addRoute('GET', '/dashboard/teacher/students/add', ['HomeController', 'showTeacherStudentsAdd']);
$r->addRoute('GET', '/dashboard/teacher/students/edit/{id}', ['HomeController', 'showTeacherStudentsEdit']);
$r->addRoute('GET', '/dashboard/teacher/students/invite', ['HomeController', 'showTeacherStudentsInvite']);

// Quizzes
$r->addRoute('GET', '/quiz/{id}', ['HomeController', 'showQuiz']);
$r->addRoute('GET', '/quiz/complete/{id}', ['HomeController', 'showCompleteQuiz']);
$r->addRoute('POST', '/quiz/start', ['APIController', 'startQuiz']);
$r->addRoute('POST', '/quiz/answer', ['APIController', 'answerQuestion']);
$r->addRoute('GET', '/api/getAllQuizzes/{grade}', ['APIController', 'getGradeQuizzes']);
$r->addRoute('GET', '/api/getQuiz/{quiz_id}', ['APIController', 'getQuiz']);
$r->addRoute('POST', '/api/getQuestion', ['APIController', 'getQuestion']);
$r->addRoute('POST', '/api/getNextQuestion', ['APIController', 'getNextQuestion']);
$r->addRoute('POST', '/api/getStatistics', ['APIController', 'getStatistics']);
$r->addRoute('POST', '/api/resetQuiz', ['APIController', 'resetQuiz']);

// AI
$r->addRoute('POST', '/api/ai/prompt', ['APIController', 'promtToAI']);

$r->addRoute('GET', '/logout', function() {
    if (isset($_COOKIE['user'])) {
        setcookie('user', '', time() - 604800, '/');
    }
    header('Location: /auth/signup');
    exit;
});
<?php
// Main
$r->addRoute('GET', '/confirmation', ['HomeController', 'showConfirmation']);
$r->addRoute('GET', '/', ['HomeController', 'showIndex']);
$r->addRoute('GET', '/students', ['HomeController', 'showMainStudents']);
$r->addRoute('GET', '/teachers', ['HomeController', 'showMainTeachers']);
$r->addRoute('GET', '/ai', ['HomeController', 'showMainAi']);
$r->addRoute('GET', '/pricing', ['HomeController', 'showMainPircing']);
$r->addRoute('GET', '/select', ['HomeController', 'showMainSelect']);
$r->addRoute('GET', '/android', ['HomeController', 'showMainAndroid']);
$r->addRoute('GET', '/chooseacc', ['HomeController', 'showMainСhooseacc']);
$r->addRoute('GET', '/privacy', ['HomeController', 'showMainPrivacy']);
$r->addRoute('GET', '/delete-my-account', ['HomeController', 'showMainDMA']);
$r->addRoute('GET', '/terms', ['HomeController', 'showMainTerms']);
$r->addRoute('GET', '/contact', ['HomeController', 'showMainContact']);

// Admin
$r->addRoute('GET', '/adminPanel', ['HomeController', 'showAdminPanel']);
$r->addRoute('GET', '/adminPanel/users', ['HomeController', 'showAdminUsers']);
$r->addRoute('GET', '/adminPanel/user/edit/{id}', ['HomeController', 'showAdminUsersEdit']);
$r->addRoute('GET', '/adminPanel/teacher/edit/{id}', ['HomeController', 'showAdminTeachersEdit']);
$r->addRoute('GET', '/adminPanel/users/banned', ['HomeController', 'showAdminBannedUsers']);
$r->addRoute('GET', '/adminPanel/quizzes', ['HomeController', 'showAdminQuizzes']);
$r->addRoute('GET', '/adminPanel/quizzes/create', ['HomeController', 'showAdminQuizzesCreate']);
$r->addRoute('GET', '/adminPanel/quizzes/manage/{id}', ['HomeController', 'showAdminQuizzesManage']);
$r->addRoute('GET', '/adminPanel/categories', ['HomeController', 'showAdminCategories']);
$r->addRoute('GET', '/adminPanel/categories/create', ['HomeController', 'showAdminCategoriesCreate']);
$r->addRoute('GET', '/adminPanel/categories/edit/{id}', ['HomeController', 'showAdminCategoriesEdit']);
$r->addRoute('GET', '/adminPanel/subCategories', ['HomeController', 'showAdminSubCategories']);
$r->addRoute('GET', '/adminPanel/subCategories/create', ['HomeController', 'showAdminSubCategoriesCreate']);
$r->addRoute('GET', '/adminPanel/subCategories/edit/{id}', ['HomeController', 'showAdminSubCategoriesEdit']);

// Admin API
$r->addRoute('POST', '/api/admin/create/quiz', ['APIController', 'adminCreateQuiz']);
$r->addRoute('POST', '/adminPanel/quizzes/delete', ['APIController', 'deleteAdminQuizzes']);
$r->addRoute('POST', '/adminPanel/quizzes/edit', ['APIController', 'editAdminQuizzes']);
$r->addRoute('POST', '/adminPanel/questions/add', ['APIController', 'addAdminQuestions']);
$r->addRoute('POST', '/adminPanel/questions/edit', ['APIController', 'editAdminQuestions']);
$r->addRoute('POST', '/adminPanel/questions/delete', ['APIController', 'deleteAdminQuestions']);
$r->addRoute('POST', '/adminPanel/category/add', ['APIController', 'addAdminCategory']);
$r->addRoute('POST', '/adminPanel/category/edit', ['APIController', 'editAdminCategory']);
$r->addRoute('POST', '/adminPanel/category/delete', ['APIController', 'deleteAdminCategory']);
$r->addRoute('POST', '/adminPanel/subCategory/add', ['APIController', 'addAdminSubCategory']);
$r->addRoute('POST', '/adminPanel/subCategory/edit', ['APIController', 'editAdminSubCategory']);
$r->addRoute('POST', '/adminPanel/subCategory/delete', ['APIController', 'deleteAdminSubCategory']);
$r->addRoute('POST', '/api/admin/user/edit', ['APIController', 'editAdminUser']);
$r->addRoute('POST', '/api/admin/teacher/edit', ['APIController', 'editAdminTeacher']);
$r->addRoute('POST', '/api/admin/user/ban', ['APIController', 'banAdminUser']);
$r->addRoute('POST', '/api/admin/user/pardon', ['APIController', 'pardon']);

$r->addRoute('POST', '/auth/adminPanel', ['AuthController', 'loginAdmin']);

// Auth
$r->addRoute('GET', '/auth/adminPanel', ['HomeController', 'showLoginAdminPanel']);
$r->addRoute('GET', '/auth/signup', ['AuthController', 'showStudentAuth']);
$r->addRoute('GET', '/auth/callback', ['AuthController', 'loginWithGoogle']);
$r->addRoute('GET', '/auth/callback/join', ['AuthController', 'joinWithGoogle']);
$r->addRoute('GET', '/auth/callback/teacher', ['AuthController', 'teacherAuthWithGoogle']);
$r->addRoute('GET', '/auth/teacher/auth', ['AuthController', 'showTeacherAuth']);
$r->addRoute('GET', '/auth/teacher/register/complete', ['AuthController', 'showTeacherRegister']);
$r->addRoute('GET', '/auth/student/complete', ['AuthController', 'showRegister']);

$r->addRoute('POST', '/api/register-teacher', ['AuthController', 'teacherRegister']);
$r->addRoute('POST', '/api/register-student', ['APIController', 'studentRegister']);

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
$r->addRoute('POST', '/api/teacher/get/code', ['APIController', 'getTeacherCode']);
$r->addRoute('POST', '/api/student/check/confirm', ['APIController', 'checkStudentConfirm']);
$r->addRoute('POST', '/api/teacher/confirm', ['APIController', 'confirmStudent']);
$r->addRoute('POST', '/api/teacher/deny', ['APIController', 'denyStudent']);
$r->addRoute('GET', '/join/{code}', ['AuthController', 'showJoin']);
$r->addRoute('POST', '/stats/teacher/quizzes', ['APIController', 'getMonthCompletedQuizzes']);

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
$r->addRoute('GET', '/dashboard/teacher/students/view/{id}', ['HomeController', 'showTeacherStudentsView']);
$r->addRoute('GET', '/dashboard/teacher/students/list/{page}', ['HomeController', 'showTeacherStudents']);
$r->addRoute('GET', '/dashboard/teacher/students/invite', ['HomeController', 'showTeacherStudentsInvite']);
$r->addRoute('GET', '/dashboard/teacher/manual', ['HomeController', 'showTeacherManual']);
$r->addRoute('GET', '/dashboard/teacher/learn', ['HomeController', 'showTeacherLern']);

// Quizzes
$r->addRoute('GET', '/quiz/{id}', ['HomeController', 'showQuiz']);
$r->addRoute('GET', '/quiz/complete/{id}', ['HomeController', 'showCompleteQuiz']);
$r->addRoute('GET', '/api/getAllQuizzes/{grade}', ['APIController', 'getGradeQuizzes']);
$r->addRoute('POST', '/quiz/start', ['APIController', 'startQuiz']);
$r->addRoute('POST', '/quiz/answer', ['APIController', 'answerQuestion']);
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
    header('Location: /');
    return;
});
<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../auth/');
    exit;
}
$user = $_SESSION['user'];

require __DIR__ . "/partials/header.php";

$tr = [
    'dashboard' => 'Басқару тақтасы',
    'documentation' => 'Құжаттама',
    'students' => 'Оқушылар',
    'manage' => 'Басқару',
    'add_new' => 'Жаңасын қосу',
    'edit_existing' => 'Барын өңдеу',
    'invite_via_link' => 'Сілтеме арқылы шақыру',
    'account' => 'Аккаунт',
    'my_profile' => 'Менің профилім',
    'settings' => 'Баптаулар',
    'logout' => 'Шығу',
    'skills' => 'Дағдылар',
    'math' => 'Математика',
    'english' => 'Ағылшын',
    'kazakh' => 'Қазақ тілі',
    'my_school' => 'Менің мектебім',
    'for_teachers' => 'Мұғалімдерге арналған',
    'analytics' => 'Талдау',
    'quizzes' => 'Тесттер',
    'signed_in_as' => 'Тіркелген пайдаланушы',
    'welcome_back' => 'Қош келдіңіз',
    'questions_answered_year' => 'Жауап берілген сұрақтар (жыл)',
    'time_spent' => 'Өткізілген уақыт',
    'skills_mastered' => 'Меңгерілген дағдылар',
    'hours' => 'Сағат',
    'questions_answered' => 'Жауап берілген сұрақтар',
    'add_students_edit_and_more' => 'Оқушыларды қосу, өңдеу және тағы басқалар.',
    'view_all' => 'Барлығын қарау',
    'add_student' => 'Оқушы қосу',
    'skills_learned' => 'Үйренген дағдылар',
    'last_skill' => 'Соңғы дағды',
    'last_seen' => 'Соңғы рет көрінген',
    'study_plan' => 'Оқу жоспары',
    'spent_learning' => 'Оқу уақыты',
    'over_a_month_ago' => 'Бір айдан астам бұрын',
    'days_ago' => 'күн бұрын',
    'day_ago' => 'күн бұрын',
    'today' => 'Бүгін',
    'yesterday' => 'Кеше',
    'hours_plural' => 'Сағаттар',
    'hour_singular' => 'Сағат',
    'minute_plural' => 'Минуттар',
    'minute_singular' => 'Минут',
    'second_plural' => 'Секундтар',
    'second_singular' => 'Секунд',
    'edit' => 'Өңдеу',
    'prev' => 'Алдыңғы',
    'next' => 'Келесі',
    'results_plural' => 'Нәтижелер',
    'result_singular' => 'Нәтиже',
];

?>
<script>
    window.addEventListener("load", () => {
        const preloader = document.getElementById("preloader");
        const loadingMessage = document.getElementById("loading-message");
        clearTimeout(showMessageTimeout);
        preloader.style.transition = "opacity 0.5s ease";
        preloader.style.opacity = "0";
        setTimeout(() => {
            preloader.style.display = "none";
        }, 500);
    });
    const showMessageTimeout = setTimeout(() => {
        const loadingMessage = document.getElementById("loading-message");
        loadingMessage.style.opacity = "1";
    }, 10000);
</script>

<div id="preloader" class="preloader fixed inset-0 z-50 bg-white flex flex-col items-center justify-center">
    <div class="animate-spin inline-block w-8 h-8 border-[3px] border-current border-t-transparent text-green-600 rounded-full " role="status" aria-label="loading">
        <span class="sr-only"><?= $tr['Loading'] ?? 'Loading...'; ?></span>
    </div>
    <p id="loading-message" class="mt-4 text-gray-500 opacity-0 transition-opacity duration-500"><?= $tr['Still loading'] ?? 'Still loading...'; ?></p>
</div>

<?php include 'partials/sidebar.php'; ?>

<!-- Content -->
<div class="w-full lg:ps-64">
    <div class="p-4 sm:p-6 space-y-4 sm:space-y-6">
        <!-- Grid -->
        <div class="mb-4">
            <h1 class="font-bold text-black text-xl">
                <?= $tr['welcome_back'] ?? 'Welcome back 123'; ?>, 
                <span class="text-green-500"><?= $_SESSION['user']['name']; ?></span>
            </h1>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            <!-- Card -->
            <div class="flex flex-col bg-white border shadow-sm rounded-xl">
                <div class="p-4 md:p-5">
                    <div class="flex items-center gap-x-2">
                        <p class="text-xs uppercase tracking-wide text-gray-500">
                            <?= $tr['questions_answered_year'] ?? 'QUESTIONS THAT WE\'VE ANSWERED THIS YEAR'; ?>
                        </p>
                        <div class="hs-tooltip">
                            <div class="hs-tooltip-toggle">
                                <svg class="shrink-0 size-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
                                    <path d="M12 17h.01" />
                                </svg>
                                <span class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 transition-opacity inline-block absolute invisible z-10 py-1 px-2 bg-gray-900 text-xs font-medium text-white rounded shadow-sm" role="tooltip">
                                    <?= $tr['daily_users'] ?? 'The number of daily users'; ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-1 flex items-center gap-x-2">
                        <h3 class="text-xl sm:text-2xl font-medium text-gray-800">
                            72,540
                        </h3>
                        <span class="flex items-center gap-x-1 text-green-600">
                            <svg class="inline-block size-4 self-center" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="22 7 13.5 15.5 8.5 10.5 2 17" />
                                <polyline points="16 7 22 7 22 13" />
                            </svg>
                            <span class="inline-block text-sm">
                                1.7%
                            </span>
                        </span>
                    </div>
                </div>
            </div>
            <!-- End Card -->

            <!-- Card -->
            <div class="flex flex-col bg-white border shadow-sm rounded-xl">
                <div class="p-4 md:p-5">
                    <div class="flex items-center gap-x-2">
                        <p class="text-xs uppercase tracking-wide text-gray-500">
                            <?= $tr['students'] ?? 'Students'; ?>
                        </p>
                    </div>

                    <div class="mt-1 flex items-center gap-x-2">
                        <h3 class="text-xl sm:text-2xl font-medium text-gray-800">
                            18
                        </h3>
                    </div>
                </div>
            </div>
            <!-- End Card -->

            <!-- Card -->
            <div class="flex flex-col bg-white border shadow-sm rounded-xl">
                <div class="p-4 md:p-5">
                    <div class="flex items-center gap-x-2">
                        <p class="text-xs uppercase tracking-wide text-gray-500">
                            <?= $tr['time_spent'] ?? 'TIME SPENT'; ?>
                        </p>
                    </div>

                    <div class="mt-1 flex items-center gap-x-2">
                        <h3 class="text-xl sm:text-2xl font-medium text-gray-800">
                            72hr.
                        </h3>
                        <span class="flex items-center gap-x-1 text-red-600">
                            <svg class="inline-block size-4 self-center" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="22 17 13.5 8.5 8.5 13.5 2 7" />
                                <polyline points="16 17 22 17 22 11" />
                            </svg>
                            <span class="inline-block text-sm">
                                1.7%
                            </span>
                        </span>
                    </div>
                </div>
            </div>
            <!-- End Card -->

            <!-- Card -->
            <div class="flex flex-col bg-white border shadow-sm rounded-xl">
                <div class="p-4 md:p-5">
                    <div class="flex items-center gap-x-2">
                        <p class="text-xs uppercase tracking-wide text-gray-500">
                            <?= $tr['skills_mastered'] ?? 'SKILLS MASTERED'; ?>
                        </p>
                    </div>

                    <div class="mt-1 flex items-center gap-x-2">
                        <h3 class="text-xl sm:text-2xl font-medium text-gray-800">
                            560
                        </h3>
                    </div>
                </div>
            </div>
            <!-- End Card -->
        </div>
        <!-- End Grid -->

        <div class="grid lg:grid-cols-2 gap-4 sm:gap-6">
            <!-- Card -->
            <div class="p-4 md:p-5 min-h-[410px] flex flex-col bg-white border shadow-sm rounded-xl">
                <!-- Header -->
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-sm text-gray-500">
                            <?= $tr['questions_answered'] ?? 'Questions answered'; ?>
                        </h2>
                        <p class="text-xl sm:text-2xl font-medium text-gray-800">
                            7.3k
                        </p>
                    </div>

                    <div>
                        <span class="py-[5px] px-1.5 inline-flex items-center gap-x-1 text-xs font-medium rounded-md bg-red-100 text-red-800">
                            <svg class="inline-block size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 5v14" />
                                <path d="m19 12-7 7-7-7" />
                            </svg>
                            2%
                            </span>
                    </div>
                </div>
                <!-- End Header -->

                <div id="hs-single-area-chart-two"></div>
            </div>
            <!-- End Card -->

            <!-- Card -->
            <div class="p-4 md:p-5 min-h-[410px] flex flex-col bg-white border shadow-sm rounded-xl">
                <!-- Header -->
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-sm text-gray-500">
                            <?= $tr['skills_learned'] ?? 'Skills learned'; ?>
                        </h2>
                        <p class="text-xl sm:text-2xl font-medium text-gray-800">
                            1.3k
                        </p>
                    </div>

                    <div>
                        <span class="py-[5px] px-1.5 inline-flex items-center gap-x-1 text-xs font-medium rounded-md bg-red-100 text-red-800">
                            <svg class="inline-block size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 5v14" />
                                <path d="m19 12-7 7-7-7" />
                            </svg>
                            2%
                        </span>
                    </div>
                </div>
                <!-- End Header -->

                <div id="hs-single-area-chart"></div>
            </div>
            <!-- End Card -->
        </div>

        <!-- Card -->
        <div class="flex flex-col">
            <div class="-m-1.5 overflow-x-auto">
                <div class="p-1.5 min-w-full inline-block align-middle">
                    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                        <!-- Header -->
                        <div class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-b border-gray-200">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-800">
                                    <?= $tr['students'] ?? 'Students'; ?>
                                </h2>
                                <p class="text-sm text-gray-600">
                                    <?= $tr['add_students_edit_and_more'] ?? 'Add students, edit and more.'; ?>
                                </p>
                            </div>

                            <div>
                                <div class="inline-flex gap-x-2">
                                    <a class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none focus:outline-none focus:bg-gray-50" href="#">
                                        <?= $tr['view_all'] ?? 'View all'; ?>
                                    </a>

                                    <a class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-green-500 text-white transition hover:bg-green-600 focus:outline-none focus:bg-green-700 disabled:opacity-50 disabled:pointer-events-none" href="#">
                                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M5 12h14" />
                                            <path d="M12 5v14" />
                                        </svg>
                                        <?= $tr['add_student'] ?? 'Add student'; ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!-- End Header -->

                        <!-- Table -->
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="ps-6 py-3 text-start">
                                        <label for="hs-at-with-checkboxes-main" class="flex">
                                            <input type="checkbox" class="shrink-0 border-gray-300 rounded text-green-600 focus:ring-green-500 disabled:opacity-50 disabled:pointer-events-none" id="hs-at-with-checkboxes-main">
                                            <span class="sr-only"><?= $tr['checkbox'] ?? 'Checkbox'; ?></span>
                                        </label>
                                    </th>

                                    <th scope="col" class="ps-6 lg:ps-3 xl:ps-0 pe-6 py-3 text-start">
                                        <div class="flex items-center gap-x-2">
                                        <span class="text-xs font-semibold uppercase tracking-wide text-gray-800">
                                                <?= $tr['name'] ?? 'Name'; ?>
                                            </span>
                                        </div>
                                    </th>

                                    <th scope="col" class="px-6 py-3 text-start">
                                        <div class="flex items-center gap-x-2">
                                            <span class="text-xs font-semibold uppercase tracking-wide text-gray-800">
                                                <?= $tr['last_skill'] ?? 'Last Skill'; ?>
                                            </span>
                                        </div>
                                    </th>

                                    <th scope="col" class="px-6 py-3 text-start">
                                        <div class="flex items-center gap-x-2">
                                            <span class="text-xs font-semibold uppercase tracking-wide text-gray-800">
                                                <?= $tr['last_seen'] ?? 'Last Seen'; ?>
                                            </span>
                                        </div>
                                    </th>

                                    <th scope="col" class="px-6 py-3 text-start">
                                        <div class="flex items-center gap-x-2">
                                            <span class="text-xs font-semibold uppercase tracking-wide text-gray-800">
                                                <?= $tr['study_plan'] ?? 'Study Plan'; ?>
                                            </span>
                                        </div>
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200">
                                <!-- Example Row -->
                                <tr>
                                    <td class="ps-6 py-3 text-start">
                                        <input type="checkbox" class="shrink-0 border-gray-300 rounded text-green-600 focus:ring-green-500 disabled:opacity-50 disabled:pointer-events-none">
                                    </td>
                                    <td class="ps-6 lg:ps-3 xl:ps-0 pe-6 py-3 text-start">
                                        <span class="text-sm text-gray-800">John Doe</span>
                                    </td>
                                    <td class="px-6 py-3 text-start">
                                        <span class="text-sm text-gray-800"><?= $tr['math'] ?? 'Math'; ?></span>
                                    </td>
                                    <td class="px-6 py-3 text-start">
                                        <span class="text-sm text-gray-800"><?= $tr['yesterday'] ?? 'Yesterday'; ?></span>
                                    </td>
                                    <td class="px-6 py-3 text-start">
                                        <a href="#" class="text-sm text-green-600 hover:underline"><?= $tr['view_all'] ?? 'View all'; ?></a>
                                    </td>
                                </tr>
                                <!-- End Example Row -->
                            </tbody>
                        </table>
                        <!-- End Table -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'partials/footer.php'; ?>
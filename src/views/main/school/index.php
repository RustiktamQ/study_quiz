{include '../partials/header.latte'}

<main id="swup" class="transition-fade">

    <!-- Секция 1: Герой (Для Школ) -->
    <section class="relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-32 text-center">
            <article class="flex flex-col items-center">
                <span class="inline-block bg-green-100 text-green-700 text-sm font-semibold px-4 py-1 rounded-full mb-4 animate-on-load">Специально для Школ и Образовательных Центров</span>
                <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold text-gray-900 mb-6 leading-tight animate-on-load" style="animation-delay: 0.1s;">
                    <span class="block">Повысьте успеваемость и эффективность</span>
                    <span class="block text-green-600">всей школы с AqylApp AI</span>
                </h1>
                <p class="mt-4 text-lg md:text-xl text-gray-700 max-w-3xl mx-auto animate-on-load" style="animation-delay: 0.2s;">
                    Предоставьте учителям мощные AI-инструменты, ученикам — персонализированный путь к знаниям, а администрации — прозрачную аналитику для принятия верных решений. AqylApp масштабируется для нужд вашего учебного заведения.
                </p>
                <div class="mt-10 flex flex-col sm:flex-row justify-center gap-4 animate-on-load" style="animation-delay: 0.3s;">
                    <a href="#school-quote"
                       class="inline-block px-8 py-3 bg-green-600 text-white font-semibold rounded-xl shadow-md hover:bg-green-700 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-300 transform hover:scale-105 text-base md:text-lg"
                       role="button">
                        Запросить спец. предложение
                    </a>
                    <a href="#school-features"
                       class="inline-flex items-center px-6 py-3 text-gray-800 bg-white border border-gray-300 rounded-xl font-medium hover:bg-gray-100 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors duration-200 text-base md:text-lg">
                        Возможности для школ
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </a>
                </div>
            </article>
        </div>
    </section>

    <!-- Секция 2: Почему Школы Выбирают Нас? -->
    <section id="school-features" class="py-16 md:py-24 relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 md:mb-16 animate-on-load">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900">Почему школы и образовательные центры выбирают AqylApp?</h2>
                <p class="mt-4 text-lg text-gray-600 max-w-2xl mx-auto">Мы предлагаем комплексное решение для современного образования.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Преимущество 1: Скидки -->
                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm hover:shadow-lg transition-shadow duration-300 animate-on-load">
                    <div class="flex items-center justify-center h-12 w-12 rounded-xl bg-green-100 text-green-600 mb-4">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" /></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Выгодные Тарифы для Школ</h3>
                    <p class="text-gray-600 text-sm">Специальные ценовые предложения и гибкие пакеты лицензий для учебных заведений любого размера.</p>
                </div>

                <!-- Преимущество 2: Админ Панель -->
                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm hover:shadow-lg transition-shadow duration-300 animate-on-load" style="animation-delay: 0.1s;">
                    <div class="flex items-center justify-center h-12 w-12 rounded-xl bg-green-100 text-green-600 mb-4">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0V12a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 12V5.25" /></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Централизованное Управление</h3>
                    <p class="text-gray-600 text-sm">Административная панель для управления классами, учителями, учениками и отслеживания общего прогресса школы.</p>
                </div>

                <!-- Преимущество 3: Сертификаты -->
                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm hover:shadow-lg transition-shadow duration-300 animate-on-load" style="animation-delay: 0.2s;">
                    <div class="flex items-center justify-center h-12 w-12 rounded-xl bg-green-100 text-green-600 mb-4">
                       <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 0 1 3 3h-15a3 3 0 0 1 3-3m9 0v-4.5A3 3 0 0 0 13.5 11.25h-3A3 3 0 0 0 7.5 14.25v4.5m9 0h-9M12 11.25a1.125 1.125 0 1 1 0-2.25 1.125 1.125 0 0 1 0 2.25Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 5.146a5.23 5.23 0 0 1 4.374 0L15 3.375a.75.75 0 0 0-1.06-.01l-1.22 1.06-1.22-1.06a.75.75 0 0 0-1.06.01l.813 1.771Z" /></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Сертификаты об Успеваемости</h3>
                    <p class="text-gray-600 text-sm">Автоматическая генерация и выдача настраиваемых сертификатов за достижение определенных навыков или завершение тем.</p>
                </div>

                <!-- Преимущество 4: Поддержка -->
                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm hover:shadow-lg transition-shadow duration-300 animate-on-load" style="animation-delay: 0.3s;">
                    <div class="flex items-center justify-center h-12 w-12 rounded-xl bg-green-100 text-green-600 mb-4">
                       <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L1.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.25 18.75l.813 2.846L21 18.75l-2.846-.813a4.5 4.5 0 0 0-3.09-3.09L12.25 12l2.846.813a4.5 4.5 0 0 0 3.09 3.09L21 18.75Zm-3.75-3.75 1.324-1.325L18.75 12l-1.325-1.324a4.5 4.5 0 0 0-5.303-5.303L9 2.25 7.676 3.575a4.5 4.5 0 0 0-5.303 5.303L3.575 11.25 2.25 12l1.325 1.324a4.5 4.5 0 0 0 5.303 5.303L11.25 21l1.325-1.325a4.5 4.5 0 0 0 5.303-5.303l-1.325-1.324Z" /></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Приоритетная Поддержка</h3>
                    <p class="text-gray-600 text-sm">Выделенная линия поддержки и обучающие материалы для учителей и администраторов для легкого старта и эффективной работы.</p>
                </div>
            </div>
        </div>
    </section>


    <!-- Секция 3: Интерактивное "До и После" (для Школы) -->
    <section class="py-16 md:py-24 bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 text-center mb-12 animate-on-load">
                Трансформация образования: Школа До и После AqylApp
            </h2>

            <div class="flex justify-center mb-8 animate-on-load">
                <div class="inline-flex rounded-xl shadow-sm" role="group">
                    <button id="btn-before"
                            type="button"
                            class="py-2.5 px-5 text-sm font-medium border border-gray-200 rounded-l-xl focus:z-10 focus:ring-2 focus:ring-green-500 transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="inline h-5 w-5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
                        Стандартный Подход
                    </button>
                    <button id="btn-after"
                            type="button"
                            class="py-2.5 px-5 text-sm font-medium border-t border-b border-r border-gray-200 rounded-r-xl focus:z-10 focus:ring-2 focus:ring-green-500 transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="inline h-5 w-5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" /></svg>
                        С AqylApp для Школы
                    </button>
                </div>
            </div>

            <div class="max-w-4xl mx-auto relative">
                <!-- Состояние "До" -->
                <div id="content-before" class="interactive-content p-6 md:p-8 bg-gradient-to-br from-red-50 to-orange-50 border border-red-200 rounded-2xl shadow-md transition-all duration-300 ease-out">
                    <h3 class="text-xl font-semibold text-red-800 mb-4 flex items-center">
                        <svg class="h-6 w-6 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.008v.008H12v-.008Z" /></svg>
                        Типичные Вызовы:
                    </h3>
                    <ul class="space-y-3 text-gray-700">
                        <li class="flex items-start">
                            <span class="text-red-500 mr-2 mt-1 font-bold">»</span>
                            <span><strong class="font-semibold">"Один размер для всех":</strong> Сложно адаптировать темп и сложность под нужды каждого ученика в большом классе.</span>
                        </li>
                         <li class="flex items-start">
                            <span class="text-red-500 mr-2 mt-1 font-bold">»</span>
                            <span><strong class="font-semibold">Административная нагрузка:</strong> Учителя тратят много времени на проверку, отчетность отнимает силы у администрации.</span>
                        </li>
                         <li class="flex items-start">
                            <span class="text-red-500 mr-2 mt-1 font-bold">»</span>
                            <span><strong class="font-semibold">Неполная картина прогресса:</strong> Сложно увидеть общие тренды по школе, выявить системные пробелы в знаниях оперативно.</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-red-500 mr-2 mt-1 font-bold">»</span>
                            <span><strong class="font-semibold">Мотивационный разрыв:</strong> Ученики могут терять интерес из-за неподходящей сложности или отсутствия видимого прогресса.</span>
                        </li>
                    </ul>
                </div>

                 <!-- Состояние "После" -->
                 <div id="content-after" class="interactive-content p-6 md:p-8 bg-gradient-to-br from-green-50 to-emerald-50 border border-green-200 rounded-2xl shadow-md transition-all duration-300 ease-out hidden">
                    <h3 class="text-xl font-semibold text-green-800 mb-4 flex items-center">
                        <svg class="h-6 w-6 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" /></svg>
                        Преимущества AqylApp для Школы:
                    </h3>
                    <ul class="space-y-3 text-gray-700">
                         <li class="flex items-start">
                             <span class="text-green-600 mr-2 mt-1 font-bold">»</span>
                            <span><strong class="font-semibold">Адаптивное обучение для КАЖДОГО:</strong> AI создает уникальный образовательный путь, позволяя всем ученикам развиваться в своем темпе.</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-green-600 mr-2 mt-1 font-bold">»</span>
                            <span><strong class="font-semibold">Освобожденные учителя:</strong> Автоматизация рутины (проверка, отчеты) позволяет педагогам сосредоточиться на индивидуальной поддержке и творческих задачах.</span>
                        </li>
                         <li class="flex items-start">
                             <span class="text-green-600 mr-2 mt-1 font-bold">»</span>
                            <span><strong class="font-semibold">Полная аналитика для администрации:</strong> Наглядные дашборды показывают успеваемость по классам, предметам, выявляют тренды и проблемные зоны.</span>
                        </li>
                         <li class="flex items-start">
                             <span class="text-green-600 mr-2 mt-1 font-bold">»</span>
                            <span><strong class="font-semibold">Рост вовлеченности и результатов:</strong> Персонализация и геймификация повышают мотивацию, а своевременная обратная связь от AI улучшает понимание материала.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>


    <!-- Секция 4: Уникальные Функции для Школ -->
     <section class="py-16 md:py-24 bg-gray-100 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 md:mb-16 animate-on-load">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900">Инструменты для Школы Будущего</h2>
                <p class="mt-4 text-lg text-gray-600 max-w-2xl mx-auto">Функции, разработанные специально для образовательных учреждений.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Функция 1: Аналитика -->
                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex flex-col animate-on-load">
                    <div class="mb-4 text-green-500">
                       <svg class="h-10 w-10" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75c0 .621-.504 1.125-1.125 1.125h-2.25C3.504 21 3 20.496 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25c-.621 0-1.125-.504-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25c-.621 0-1.125-.504-1.125-1.125V4.125Z" /></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Школьная Аналитика 360°</h3>
                    <p class="text-gray-600 text-sm flex-grow">Отслеживайте общую успеваемость, активность учителей и учеников, выявляйте сильные и слабые стороны в освоении программы по всей школе.</p>
                     <a href="#" class="mt-4 text-sm font-medium text-green-600 hover:text-green-700 inline-flex items-center">Узнать больше <svg class="h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m13.5 4.5-6 6m0 0 6 6m-6-6h18" /></svg></a>
                </div>

                <!-- Функция 2: Генератор Сертификатов -->
                 <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex flex-col animate-on-load" style="animation-delay: 0.1s;">
                    <div class="mb-4 text-green-500">
                        <svg class="h-10 w-10" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 0 1 3 3h-15a3 3 0 0 1 3-3m9 0v-4.5A3 3 0 0 0 13.5 11.25h-3A3 3 0 0 0 7.5 14.25v4.5m9 0h-9" /><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 5.146a5.23 5.23 0 0 1 4.374 0L15 3.375a.75.75 0 0 0-1.06-.01l-1.22 1.06-1.22-1.06a.75.75 0 0 0-1.06.01l.813 1.771Z" /></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Конструктор Достижений</h3>
                    <p class="text-gray-600 text-sm flex-grow">Создавайте и автоматически выдавайте цифровые сертификаты с логотипом школы за освоение тем, победы в олимпиадах или другие успехи.</p>
                     <a href="#" class="mt-4 text-sm font-medium text-green-600 hover:text-green-700 inline-flex items-center">Узнать больше <svg class="h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m13.5 4.5-6 6m0 0 6 6m-6-6h18" /></svg></a>
                </div>

                 <!-- Функция 3: Интеграция -->
                 <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex flex-col animate-on-load" style="animation-delay: 0.2s;">
                    <div class="mb-4 text-green-500">
                        <svg class="h-10 w-10" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244" /></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Гибкая Интеграция</h3>
                    <p class="text-gray-600 text-sm flex-grow">Возможность интеграции с существующими школьными системами (LMS, электронные журналы) для бесшовного опыта (уточняйте возможности).</p>
                     <a href="#" class="mt-4 text-sm font-medium text-green-600 hover:text-green-700 inline-flex items-center">Обсудить интеграцию <svg class="h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m13.5 4.5-6 6m0 0 6 6m-6-6h18" /></svg></a>
                 </div>
            </div>
        </div>
    </section>

    <!-- Секция 5: Призыв к Действию (Запрос Предложения) -->
    <section id="school-quote" class="py-16 md:py-24 overflow-hidden">
         <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <svg class="h-16 w-16 mx-auto mb-6 text-green-500 animate-on-load" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205 3 1m1.5.5-1.5-.5M5.625 7.755l4.5-1.636" /></svg>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4 animate-on-load" style="animation-delay: 0.1s;">Сделайте шаг к инновационному образованию</h2>
            <p class="text-lg md:text-xl text-gray-700 max-w-2xl mx-auto mb-8 animate-on-load" style="animation-delay: 0.2s;">
                Узнайте, как AqylApp может быть адаптирован под уникальные потребности вашей школы. Заполните короткую форму, и наш специалист свяжется с вами для обсуждения индивидуального предложения и возможностей пилотного запуска.
            </p>
            <a href="#contact-form-or-link" <!-- Link to contact form or specific contact -->
               class="inline-block px-10 py-4 bg-green-600 text-white font-bold rounded-xl shadow-lg hover:bg-green-700 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-300 transform hover:scale-105 text-lg md:text-xl animate-on-load" style="animation-delay: 0.3s;"
               role="button">
                Получить консультацию и расчет
            </a>
            <p class="mt-4 text-sm text-gray-600 animate-on-load" style="animation-delay: 0.4s;">Это бесплатно и ни к чему не обязывает.</p>
         </div>
    </section>

     <!-- Секция 6: Безопасность и Конфиденциальность -->
    <section class="py-12 bg-white overflow-hidden">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center flex items-center justify-center">
            <svg class="h-8 w-8 text-gray-400 mr-3 flex-shrink-0 animate-on-load" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" /></svg>
            <p class="text-gray-600 text-sm animate-on-load" style="animation-delay: 0.1s;">
                Мы серьезно относимся к безопасности данных и соблюдаем нормы конфиденциальности. <a href="/ru/privacy-policy" class="text-green-600 hover:underline">Узнать больше о защите данных</a>.
            </p>
        </div>
    </section>

</main>

{include '../partials/footer.latte'}
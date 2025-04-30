{include '../partials/header.latte'}

<!--

СТРАНИЦУ ПОКА ОСТАВИТЬ НО ЧТОБЫ НА НЕЕ НИКТО НАХУЙ НЕ ЗАШЕЛ ЭТО ПОКА В РАЗРАБОТКЕ!!!!!!!!

-->

<main id="swup" class="transition-fade bg-white">

    <!-- Секция 1: Герой О нас -->
    <section class="relative bg-gray-50 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">
            <div class="text-center">
                <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold text-gray-900 mb-6 leading-tight animate-on-load">
                    <span class="block">Мы создаем будущее образования.</span>
                    <span class="block text-green-600">Вместе с вами.</span>
                </h1>
                <p class="mt-4 text-lg md:text-xl text-gray-600 max-w-3xl mx-auto animate-on-load">
                    AqylApp – это больше, чем просто приложение. Это наша страсть к обучению, вера в силу технологий и стремление помочь каждому учителю и ученику раскрыть свой потенциал.
                </p>
            </div>
        </div>
    </section>

    <!-- Секция 2: Наша История -->
    <section class="py-16 md:py-24 bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
             <h2 class="text-3xl md:text-4xl font-bold text-gray-900 text-center mb-12 animate-on-load">
                Наш Путь: От идеи к AI-помощнику
            </h2>
            <div class="relative">
                <!-- Линия времени (декоративная) -->
                <div class="hidden md:block absolute left-1/2 top-0 bottom-0 w-px bg-gray-300 transform -translate-x-1/2"></div>

                <!-- Этап 1 -->
                <div class="md:flex md:items-center md:justify-start mb-12 animate-on-load">
                    <div class="md:w-1/2 md:pr-8 text-center md:text-right">
                        <div class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-semibold mb-2">Этап 1: Искра</div>
                        <p class="text-gray-600">Все началось с простого наблюдения: учителя тратят огромное количество времени на рутинную проверку, а ученики нуждаются в более индивидуальном подходе. Мы поняли, что технологии могут помочь.</p>
                    </div>
                    <div class="hidden md:block relative w-8 h-8 bg-green-500 rounded-full border-4 border-white mx-auto md:mx-0 md:ml-[-1rem] md:mr-[-1rem] z-10"></div>
                    <div class="md:w-1/2 md:pl-8"></div>
                </div>

                 <!-- Этап 2 -->
                <div class="md:flex md:flex-row-reverse md:items-center md:justify-start mb-12 animate-on-load">
                    <div class="md:w-1/2 md:pl-8 text-center md:text-left">
                        <div class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-semibold mb-2">Этап 2: Разработка</div>
                         <p class="text-gray-600">Мы собрали команду энтузиастов – педагогов, разработчиков, AI-специалистов – и начали создавать прототип. Главной задачей было сделать инструмент простым, полезным и по-настоящему умным.</p>
                    </div>
                     <div class="hidden md:block relative w-8 h-8 bg-green-500 rounded-full border-4 border-white mx-auto md:mx-0 md:mr-[-1rem] md:ml-[-1rem] z-10"></div>
                    <div class="md:w-1/2 md:pr-8"></div>
                </div>

                <!-- Этап 3 -->
                <div class="md:flex md:items-center md:justify-start animate-on-load">
                    <div class="md:w-1/2 md:pr-8 text-center md:text-right">
                         <div class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-semibold mb-2">Этап 3: Запуск и Рост</div>
                        <p class="text-gray-600">После тщательного тестирования и обратной связи от первых пользователей, мы запустили AqylApp. Сегодня мы продолжаем развиваться, добавляя новые функции и улучшая AI на основе реального опыта учителей и учеников.</p>
                    </div>
                     <div class="hidden md:block relative w-8 h-8 bg-green-500 rounded-full border-4 border-white mx-auto md:mx-0 md:ml-[-1rem] md:mr-[-1rem] z-10"></div>
                    <div class="md:w-1/2 md:pl-8"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Секция 3: Миссия и Ценности -->
    <section class="py-16 md:py-24 bg-gradient-to-b from-green-50 to-white overflow-hidden">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4 animate-on-load">Наша Миссия</h2>
            <p class="text-lg md:text-xl text-gray-600 mb-10 animate-on-load">
                Сделать качественное, персонализированное образование доступным для каждого, расширяя возможности учителей с помощью умных технологий.
            </p>

             <div class="grid grid-cols-1 sm:grid-cols-3 gap-8">
                 <!-- Ценность 1 -->
                 <div class="animate-on-load">
                     <div class="flex items-center justify-center h-12 w-12 rounded-full bg-green-200 text-green-700 mx-auto mb-3">❤️</div>
                     <h3 class="text-lg font-semibold text-gray-800">Польза для Учителя</h3>
                     <p class="text-gray-500 text-sm">Мы создаем инструменты, которые экономят время и дают инсайты.</p>
                 </div>
                 <!-- Ценность 2 -->
                 <div class="animate-on-load">
                     <div class="flex items-center justify-center h-12 w-12 rounded-full bg-green-200 text-green-700 mx-auto mb-3">💡</div>
                     <h3 class="text-lg font-semibold text-gray-800">Рост Ученика</h3>
                     <p class="text-gray-500 text-sm">Мы помогаем каждому учиться в своем темпе и понимать глубже.</p>
                 </div>
                 <!-- Ценность 3 -->
                 <div class="animate-on-load">
                      <div class="flex items-center justify-center h-12 w-12 rounded-full bg-green-200 text-green-700 mx-auto mb-3">🚀</div>
                     <h3 class="text-lg font-semibold text-gray-800">Инновации и Простота</h3>
                     <p class="text-gray-500 text-sm">Мы используем AI, чтобы сделать сложные вещи простыми и доступными.</p>
                 </div>
             </div>
        </div>
    </section>


    <!-- Секция 4: Дорожная Карта -->
    <section class="py-16 md:py-24 bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
             <h2 class="text-3xl md:text-4xl font-bold text-gray-900 text-center mb-12 animate-on-load">
                Что дальше? Наш Roadmap
            </h2>
             <div class="max-w-3xl mx-auto">
                 <ul class="space-y-8">
                     <!-- Пункт 1 -->
                     <li class="flex items-start animate-on-load">
                         <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center bg-gray-200 rounded-full mr-4 mt-1">
                             <span class="text-gray-600 font-semibold">1</span>
                         </div>
                         <div>
                             <h3 class="text-lg font-semibold text-gray-800">Расширение Предметов</h3>
                             <p class="text-gray-600">Добавление поддержки новых школьных предметов и дисциплин.</p>
                         </div>
                     </li>
                     <!-- Пункт 2 -->
                      <li class="flex items-start animate-on-load">
                         <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center bg-gray-200 rounded-full mr-4 mt-1">
                             <span class="text-gray-600 font-semibold">2</span>
                         </div>
                         <div>
                             <h3 class="text-lg font-semibold text-gray-800">Улучшенный AI-Анализ</h3>
                             <p class="text-gray-600">Более глубокая аналитика прогресса, выявление стилей обучения и предоставление еще более точных рекомендаций.</p>
                         </div>
                     </li>
                     <!-- Пункт 3 -->
                      <li class="flex items-start animate-on-load">
                         <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center bg-gray-200 rounded-full mr-4 mt-1">
                             <span class="text-gray-600 font-semibold">3</span>
                         </div>
                         <div>
                             <h3 class="text-lg font-semibold text-gray-800">Интеграция с Платформами</h3>
                             <p class="text-gray-600">Возможность интеграции с популярными LMS и образовательными системами.</p>
                         </div>
                     </li>
                      <!-- Пункт 4 -->
                      <li class="flex items-start animate-on-load">
                         <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center bg-gray-200 rounded-full mr-4 mt-1">
                             <span class="text-gray-600 font-semibold">4</span>
                         </div>
                         <div>
                             <h3 class="text-lg font-semibold text-gray-800">Геймификация и Вовлечение</h3>
                             <p class="text-gray-600">Добавление новых игровых механик для повышения мотивации учеников.</p>
                         </div>
                     </li>
                 </ul>
             </div>
        </div>
    </section>

    <!-- Секция 5: Интересный Факт -->
    <section class="py-16 md:py-24 bg-green-600 text-white overflow-hidden">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
             <div class="animate-on-load mb-4">
                 <!-- Иконка Мозга/Мысли -->
                 <svg class="h-12 w-12 mx-auto text-green-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 19.151C5 21 6.5 21 7.5 21C9 21 10.016 20.195 10.91 18.868C11.913 17.406 12 16.002 12 16.002C12 16.002 12.087 17.406 13.09 18.868C13.984 20.195 15 21 16.5 21C17.5 21 19 21 19 19.151C19 16.198 16.5 15.002 16.5 15.002C16.5 15.002 19 14.002 19 10.849C19 9 17.5 9 16.5 9C15 9 13.984 9.805 13.09 11.132C12.087 12.594 12 13.998 12 13.998C12 13.998 11.913 12.594 10.91 11.132C10.016 9.805 9 9 7.5 9C6.5 9 5 9 5 10.849C5 14.002 7.5 15.002 7.5 15.002C7.5 15.002 5 16.198 5 19.151Z" /></svg>
             </div>
             <h2 class="text-2xl md:text-3xl font-bold mb-3 animate-on-load">Знаете ли вы?</h2>
             <p class="text-lg md:text-xl text-green-100 animate-on-load">
                Название "Aqyl" (Ақыл) в переводе с казахского языка означает "ум", "разум", "интеллект". Это отражает нашу главную цель – развивать интеллект и помогать учиться умнее с помощью технологий.
             </p>
        </div>
    </section>

     <!-- Секция 6: Присоединяйтесь к нам -->
    <section class="py-16 md:py-24 bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4 animate-on-load">Станьте частью нашей истории!</h2>
            <p class="text-lg md:text-xl text-gray-600 max-w-3xl mx-auto mb-8 animate-on-load">
                Мы всегда открыты к сотрудничеству, идеям и обратной связи. Если вы разделяете нашу страсть к образованию – свяжитесь с нами!
            </p>
            <a href="/contact" <!-- Или другая ссылка -->
               class="inline-block px-8 py-3 bg-green-500 text-white font-semibold rounded-2xl hover:bg-green-600 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors duration-200 text-base md:text-lg animate-on-load"
               role="button">
                Связаться с нами
            </a>
        </div>
    </section>


</main>

{include '../partials/footer.latte'}
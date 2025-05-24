-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.2:3306
-- Время создания: Май 24 2025 г., 14:47
-- Версия сервера: 10.1.48-MariaDB
-- Версия PHP: 8.1.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `study`
--

-- --------------------------------------------------------

--
-- Структура таблицы `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(92) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `admins`
--

INSERT INTO `admins` (`id`, `email`, `password`, `token`) VALUES
(1, 'root', '63a9f0ea7bb98050796b649e85481845', '4f5fba03a86607a215fe91bd47735689');

-- --------------------------------------------------------

--
-- Структура таблицы `ban_list`
--

CREATE TABLE `ban_list` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `banned_date` date NOT NULL,
  `expire_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(5, 'Математика'),
(7, 'Английский'),
(8, 'Казахский язык');

-- --------------------------------------------------------

--
-- Структура таблицы `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `message` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address_type` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address_id` int(11) NOT NULL,
  `home_id` int(11) NOT NULL,
  `created_at` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `notifications`
--

INSERT INTO `notifications` (`id`, `message`, `address_type`, `address_id`, `home_id`, `created_at`, `type`) VALUES
(1, 'Ученик Rustiktam RR прошел квиз DEV_QUIZ', 'teacher', 42, 1, '2025-05-23 12:17:21', 'Уведомление'),
(2, 'Ученик Rustiktam RR прошел квиз DEV_QUIZ', 'teacher', 42, 1, '2025-05-24 13:56:04', 'Уведомление');

-- --------------------------------------------------------

--
-- Структура таблицы `progress`
--

CREATE TABLE `progress` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `current_question` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  `completed` tinyint(1) DEFAULT '0',
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime NOT NULL,
  `answered` int(11) NOT NULL,
  `elapsed_time` time NOT NULL,
  `shuffled_questions` text NOT NULL,
  `current_question_index` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `progress`
--

INSERT INTO `progress` (`id`, `student_id`, `quiz_id`, `current_question`, `score`, `completed`, `start_time`, `end_time`, `answered`, `elapsed_time`, `shuffled_questions`, `current_question_index`) VALUES
(4, 1, 2, 2, 100, 1, '2025-05-24 13:55:14', '2025-05-24 13:56:04', 24, '00:00:50', '[\"1\",\"2\"]', 1),
(5, 1, 1, 2, 100, 1, '2025-05-24 13:55:14', '2025-05-24 13:57:04', 24, '00:00:50', '[\"1\",\"2\"]', 1),
(6, 1, 1, 2, 100, 1, '2025-05-24 13:55:14', '2025-05-06 13:56:04', 24, '00:00:50', '[\"1\",\"2\"]', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `question_text` text CHARACTER SET utf8mb4 NOT NULL,
  `correct_answer` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `explanation` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT 'Loaded from the database'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `questions`
--

INSERT INTO `questions` (`id`, `question_text`, `correct_answer`, `options`, `explanation`) VALUES
(1, 'Вопрос 1', 'Вариант 1', '[\"Вариант 1\",\"Вариант 2\"]', 'Пояснение 1'),
(2, 'Вопрос 2', 'Вариант B', '[\"Вариант A\",\"Вариант B\"]', 'Пояснение 2');

-- --------------------------------------------------------

--
-- Структура таблицы `questions_quizzes`
--

CREATE TABLE `questions_quizzes` (
  `id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `questions_quizzes`
--

INSERT INTO `questions_quizzes` (`id`, `quiz_id`, `question_id`) VALUES
(1, 1, 1),
(2, 1, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `quizzes`
--

CREATE TABLE `quizzes` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL,
  `sub_category_id` int(11) NOT NULL,
  `grade` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `quizzes`
--

INSERT INTO `quizzes` (`id`, `name`, `category_id`, `sub_category_id`, `grade`) VALUES
(1, 'DEV_QUIZ', 5, 1, 1),
(2, 'DEV_QUIZ2', 7, 1, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `sub_categories`
--

CREATE TABLE `sub_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `sub_categories`
--

INSERT INTO `sub_categories` (`id`, `name`) VALUES
(1, 'Основы');

-- --------------------------------------------------------

--
-- Структура таблицы `teachers`
--

CREATE TABLE `teachers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `picture` varchar(255) NOT NULL DEFAULT 'https://vk.com/images/wall/deleted_avatar_50.png',
  `firstname` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `email` varchar(125) NOT NULL,
  `lang` varchar(255) DEFAULT NULL,
  `invite_code` varchar(100) DEFAULT NULL,
  `join_date` date NOT NULL,
  `school` varchar(128) NOT NULL,
  `specialization` varchar(255) NOT NULL,
  `students_limit` int(11) NOT NULL DEFAULT '5'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `teachers`
--

INSERT INTO `teachers` (`id`, `name`, `picture`, `firstname`, `lastname`, `email`, `lang`, `invite_code`, `join_date`, `school`, `specialization`, `students_limit`) VALUES
(42, 'q', 'https://lh3.googleusercontent.com/a-/ALV-UjWh5GfCveVvX6Gsr5p6ukIAOkev35KGToJic21BHUEJGIwDdQ=s96-c', NULL, NULL, 'aqylapp@gmail.com', NULL, '123', '2025-05-12', '', '', 5),
(43, NULL, 'https://lh3.googleusercontent.com/a-/ALV-UjX3TMeLo72LJk4jOZIzgPNx3YHhmkHMohX10z840hnO79JDCYQ=s96-c', NULL, NULL, 'gorislavetsmilan228@gmail.com', NULL, NULL, '2025-05-13', '', '', 5);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `google_id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `picture` text,
  `token` varchar(255) DEFAULT NULL,
  `token_confirmed` varchar(2) DEFAULT '0',
  `join_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `grade` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `google_id`, `name`, `firstname`, `lastname`, `email`, `picture`, `token`, `token_confirmed`, `join_date`, `grade`) VALUES
(1, '114869489688930855296', 'Rustiktam RR', 'Rustam', 'Lox', 'rramilperm@gmail.com', 'https://i.pinimg.com/736x/66/bd/86/66bd86f50d704ae34618db13dc44f585.jpg', '123', '1', '2025-05-22 16:29:22', 0),
(2, '114869489688930855296', 'Rustiktam RR', 'Rustam', 'Lox', 'rramilperm@gmail.com', 'https://i.pinimg.com/736x/66/bd/86/66bd86f50d704ae34618db13dc44f585.jpg', '123', '1', '2025-05-22 16:29:22', 0),
(3, '114869489688930855296', 'Rustiktam RR', 'Rustam', 'Lox', 'rramilperm@gmail.com', 'https://i.pinimg.com/736x/66/bd/86/66bd86f50d704ae34618db13dc44f585.jpg', '123', '1', '2025-05-22 16:29:22', 0),
(4, '114869489688930855296', 'Rustiktam RR', 'Rustam', 'Lox', 'rramilperm@gmail.com', 'https://i.pinimg.com/736x/66/bd/86/66bd86f50d704ae34618db13dc44f585.jpg', '123', '1', '2025-05-22 16:29:22', 0),
(5, '114869489688930855296', 'Rustiktam RR', 'Rustam', 'Lox', 'rramilperm@gmail.com', 'https://i.pinimg.com/736x/66/bd/86/66bd86f50d704ae34618db13dc44f585.jpg', '123', '1', '2025-05-22 16:29:22', 0),
(6, '114869489688930855296', 'Rustiktam RR', 'Rustam', 'Lox', 'rramilperm@gmail.com', 'https://i.pinimg.com/736x/66/bd/86/66bd86f50d704ae34618db13dc44f585.jpg', '123', '1', '2025-05-22 16:29:22', 0),
(7, '114869489688930855296', 'Rustiktam RR', 'Rustam', 'Lox', 'rramilperm@gmail.com', 'https://i.pinimg.com/736x/66/bd/86/66bd86f50d704ae34618db13dc44f585.jpg', '123', '1', '2025-05-22 16:29:22', 0),
(8, '114869489688930855296', 'Rustiktam RR', 'Rustam', 'Lox', 'rramilperm@gmail.com', 'https://i.pinimg.com/736x/66/bd/86/66bd86f50d704ae34618db13dc44f585.jpg', '123', '1', '2025-05-22 16:29:22', 0),
(9, '114869489688930855296', 'Rustiktam RR', 'Rustam', 'Lox', 'rramilperm@gmail.com', 'https://i.pinimg.com/736x/66/bd/86/66bd86f50d704ae34618db13dc44f585.jpg', '123', '1', '2025-05-22 16:29:22', 0),
(10, '114869489688930855296', 'Rustiktam RR', 'Rustam', 'Lox', 'rramilperm@gmail.com', 'https://i.pinimg.com/736x/66/bd/86/66bd86f50d704ae34618db13dc44f585.jpg', '123', '1', '2025-05-22 16:29:22', 0),
(11, '114869489688930855296', 'Rustiktam RR', 'Rustam', 'Lox', 'rramilperm@gmail.com', 'https://i.pinimg.com/736x/66/bd/86/66bd86f50d704ae34618db13dc44f585.jpg', '123', '1', '2025-05-22 16:29:22', 0),
(12, '114869489688930855296', 'Rustiktam RR', 'Rustam', 'Lox', 'rramilperm@gmail.com', 'https://i.pinimg.com/736x/66/bd/86/66bd86f50d704ae34618db13dc44f585.jpg', '123', '1', '2025-05-22 16:29:22', 0),
(13, '114869489688930855296', 'Rustiktam RR', 'Rustam', 'Lox', 'rramilperm@gmail.com', 'https://i.pinimg.com/736x/66/bd/86/66bd86f50d704ae34618db13dc44f585.jpg', '123', '1', '2025-05-22 16:29:22', 0),
(14, '114869489688930855296', 'Rustiktam RR', 'Rustam', 'Lox', 'rramilperm@gmail.com', 'https://i.pinimg.com/736x/66/bd/86/66bd86f50d704ae34618db13dc44f585.jpg', '123', '1', '2025-05-22 16:29:22', 0);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `ban_list`
--
ALTER TABLE `ban_list`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `progress`
--
ALTER TABLE `progress`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_Progress_student_id` (`student_id`),
  ADD KEY `FK_Progress_quiz_id` (`quiz_id`);

--
-- Индексы таблицы `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `questions_quizzes`
--
ALTER TABLE `questions_quizzes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_QQ_quizze` (`quiz_id`),
  ADD KEY `FK_QQ_question_id_cascade` (`question_id`);

--
-- Индексы таблицы `quizzes`
--
ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_quizzes_category_id` (`category_id`),
  ADD KEY `FK_quizzes_sub_category_id` (`sub_category_id`);

--
-- Индексы таблицы `sub_categories`
--
ALTER TABLE `sub_categories`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_uniq` (`email`),
  ADD UNIQUE KEY `invite_code` (`invite_code`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `ban_list`
--
ALTER TABLE `ban_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `progress`
--
ALTER TABLE `progress`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `questions_quizzes`
--
ALTER TABLE `questions_quizzes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `sub_categories`
--
ALTER TABLE `sub_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `progress`
--
ALTER TABLE `progress`
  ADD CONSTRAINT `FK_Progress_quiz_id` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`),
  ADD CONSTRAINT `FK_Progress_student_id` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `questions_quizzes`
--
ALTER TABLE `questions_quizzes`
  ADD CONSTRAINT `FK_QQ_question_id_cascade` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_QQ_quizze` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`);

--
-- Ограничения внешнего ключа таблицы `quizzes`
--
ALTER TABLE `quizzes`
  ADD CONSTRAINT `FK_quizzes_category_id` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `FK_quizzes_sub_category_id` FOREIGN KEY (`sub_category_id`) REFERENCES `sub_categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

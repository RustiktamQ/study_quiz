-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.2:3306
-- Время создания: Май 08 2025 г., 12:10
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

--
-- Дамп данных таблицы `ban_list`
--

INSERT INTO `ban_list` (`id`, `user_id`, `reason`, `banned_date`, `expire_date`) VALUES
(1, 22, 'qqq', '2025-05-07', '2025-06-06');

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
(11, 'ожидает потверждения', 'teacher', 3, 21, '2026-04-27 16:49:57', 'запрос'),
(12, 'ожидает потверждения', 'teacher', 3, 21, '2025-04-27 16:49:57', 'запрос'),
(13, 'ожидает потверждения', 'teacher', 3, 21, '2025-04-27 16:49:57', 'запрос'),
(14, 'ожидает потверждения', 'teacher', 3, 21, '2025-04-27 16:49:57', 'запрос'),
(15, 'ожидает потверждения', 'teacher', 3, 21, '2025-04-27 16:49:57', 'запрос'),
(16, 'ожидает потверждения', 'teacher', 3, 21, '2025-04-27 16:49:57', 'запрос'),
(17, 'ожидает потверждения', 'teacher', 3, 21, '2025-04-27 16:49:57', 'запрос'),
(18, 'ожидает потверждения', 'teacher', 3, 21, '2025-04-27 16:49:57', 'запрос'),
(19, 'ожидает потверждения', 'teacher', 3, 21, '2025-04-27 16:49:57', 'запрос');

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
(98, 21, 3, 8, 19, 0, '2025-04-05 12:20:47', '0000-00-00 00:00:00', 2, '00:00:10', '[\"8\",\"9\",\"7\"]', 0),
(99, 21, 1, 1, 29, 0, '2025-04-06 16:08:04', '0000-00-00 00:00:00', 5, '00:30:16', '[\"16\",\"15\",\"2\",\"12\",\"11\",\"14\",\"13\",\"1\",\"3\",\"10\"]', 7),
(100, 21, 2, 5, 10, 0, '2025-04-08 19:49:51', '0000-00-00 00:00:00', 1, '00:03:10', '', 0),
(101, 21, 7, 23, 0, 0, '2025-04-23 18:27:34', '0000-00-00 00:00:00', 0, '00:00:00', '', 0),
(102, 21, 8, 25, 22, 0, '2025-04-23 18:31:47', '0000-00-00 00:00:00', 3, '00:00:32', '[\"25\",\"26\"]', 0);

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
(1, 'What is 5 + 3?', '8', '[\"6\", \"7\", \"8\", \"9\"]', 'Loaded from the database'),
(2, 'What is 9 - 4?', '5', '[\"3\", \"4\", \"5\", \"6\"]', 'Loaded from the database'),
(3, 'What is 6 x 2?', '12', '[\"10\", \"12\", \"14\", \"16\"]', 'Loaded from the database'),
(4, 'What is 15 ÷ 3?', '5', '[\"3\", \"4\", \"5\", \"6\"]', 'Loaded from the database'),
(5, 'What is 8 x 7?', '56', '[\"49\", \"54\", \"56\", \"64\"]', 'Loaded from the database'),
(6, 'What is the place value of 7 in 574?', 'Tens', '[\"Ones\", \"Tens\", \"Hundreds\", \"Thousands\"]', 'Loaded from the database'),
(7, 'What is the value of the digit 3 in 231?', 'Tens', '[\"Ones\", \"Tens\", \"Hundreds\", \"Thousands\"]', 'Loaded from the database'),
(8, 'What is 10 + 5?', '15', '[\"12\", \"13\", \"14\", \"15\"]', 'Loaded from the database'),
(9, 'What is 8 - 3?', '5', '[\"5\", \"6\", \"7\", \"8\"]', 'Loaded from the database'),
(10, 'What is 7 + 5?', '12', '[\"10\", \"11\", \"12\", \"13\"]', 'Adding 7 and 5 gives 12.'),
(11, 'What is 15 - 6?', '9', '[\"7\", \"8\", \"9\", \"10\"]', 'Subtracting 6 from 15 gives 9.'),
(12, 'What is 4 x 3?', '12', '[\"7\", \"10\", \"12\", \"14\"]', 'Multiplying 4 by 3 equals 12.'),
(13, 'What is 20 divided by 4?', '5', '[\"4\", \"5\", \"6\", \"7\"]', 'Dividing 20 by 4 gives 5.'),
(14, 'If you have 8 apples and share them equally among 4 friends, how many apples does each friend get?', '2', '[\"1\", \"2\", \"3\", \"4\"]', '8 divided by 4 equals 2 apples per friend.'),
(15, 'What is the value of the digit 3 in the number 352?', '30', '[\"3\", \"30\", \"300\", \"350\"]', 'The digit 3 is in the tens place, representing 30.'),
(16, 'How many inches are in one foot?', '12', '[\"10\", \"11\", \"12\", \"13\"]', 'One foot is equal to 12 inches.'),
(17, 'Тестовый вопрос', 'Array', 'Правильный ответ', 'Бла бла'),
(18, '1', '[\"q1\",\"q2\",\"q3\",\"q5\"]', 'q3', 'wwww'),
(19, '1', '[\"1\",\"1\",\"1\",\"1\"]', '1', '1'),
(20, 'Текст вопроса', 'Правильный ответ', '[\"\\u0412\\u0430\\u0440\\u0438\\u0430\\u043d\\u0442 1\",\"\\u0412\\u0430\\u0440\\u0438\\u0430\\u043d\\u0442 2\",\"\\u041f\\u0440\\u0430\\u0432\\u0438\\u043b\\u044c\\u043d\\u044b\\u0439 \\u043e\\u0442\\u0432\\u0435\\u0442\",\"\\u0412\\u0430\\u0440\\u0438\\u0430\\u043d\\u0442 4\"]', 'Обьяснение'),
(22, 'www', 'q', '[\"q\",\"q\",\"q\",\"q\"]', 'eqwe'),
(25, 'Как переводится Apple', 'Яблоко', '[\"\\u0425\\u0437\",\"2\",\"\\u042f\\u0431\\u043b\\u043e\\u043a\\u043e\",\"\\u0433\\u0440\\u0443\\u0448\\u0430\"]', 'вфывфы'),
(26, 'Столица Англии', 'Лондон', '[\"\\u041f\\u0430\\u0440\\u0438\\u0436\",\"\\u041c\\u043e\\u0441\\u043a\\u0432\\u0430\",\"\\u0424\\u0440\\u0430\\u043d\\u0446\\u0438\\u044f\",\"\\u041b\\u043e\\u043d\\u0434\\u043e\\u043d\"]', 'фывфыв');

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
(2, 1, 2),
(3, 1, 3),
(4, 2, 4),
(5, 2, 5),
(6, 2, 6),
(7, 3, 7),
(8, 3, 8),
(9, 3, 9),
(10, 1, 10),
(11, 1, 11),
(12, 1, 12),
(13, 1, 13),
(14, 1, 14),
(15, 1, 15),
(16, 1, 16),
(18, 6, 22),
(21, 8, 25),
(22, 8, 26);

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
(1, '3rd Grade Math Basics', 5, 1, 1),
(2, 'Advanced Multiplication', 5, 1, 2),
(3, 'Place Value Mastery', 5, 1, 2),
(6, 'zxc', 7, 1, 10),
(7, 'Литература Пушкин', 8, 3, 4),
(8, 'Простой квиз', 7, 3, 1);

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
(1, 'Core Concepts'),
(3, 'Advanced level');

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
(3, 'Name Surname', 'https://lh3.googleusercontent.com/a-/ALV-UjVY3GWCzg8vA4glNlvC9x83Yl3qYs9AsnTAL-re3cKw2o7JSvd5=s96-c', 'Name', 'Surname', 'test@testmail.io', 'ru', 'reset', '1900-01-01', 'qwe', 'Биология', 2),
(35, 'test1 test1', 'https://vk.com/images/wall/deleted_avatar_50.png', 'test1', 'test1', 'test@gmail.com', 'ru', NULL, '2025-05-05', 'science', '', 5),
(37, 'qwe qwe', 'https://vk.com/images/wall/deleted_avatar_50.png', 'qwe', 'qwe', 'qweqwe@gmail.com', 'ru', NULL, '2025-02-08', 'qweqwewqe', '', 5);

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
(21, '114869489688930855296', 'Rustiktam', '1q1', 'zxc', 'rramilperm@gmail.com', 'https://lh3.googleusercontent.com/a-/ALV-UjVY3GWCzg8vA4glNlvC9x83Yl3qYs9AsnTAL-re3cKw2o7JSvd5=s96-c', 'reset', '1', '2025-02-24 15:35:47', 8),
(22, '114869489688930855296', '1', '1q1', 'zxc', 'rramilperm@gmail.com', 'https://lh3.googleusercontent.com/a-/ALV-UjVY3GWCzg8vA4glNlvC9x83Yl3qYs9AsnTAL-re3cKw2o7JSvd5=s96-c', 'reset', '1', '2025-02-24 15:35:47', 8),
(23, '114869489688930855296', '2', '1q1', 'zxc', 'rramilperm@gmail.com', 'https://lh3.googleusercontent.com/a-/ALV-UjVY3GWCzg8vA4glNlvC9x83Yl3qYs9AsnTAL-re3cKw2o7JSvd5=s96-c', 'reset', '1', '2025-02-24 15:35:47', 8),
(24, '114869489688930855296', 'Rustiktam', '1q1', 'zxc', 'rramilperm@gmail.com', 'https://lh3.googleusercontent.com/a-/ALV-UjVY3GWCzg8vA4glNlvC9x83Yl3qYs9AsnTAL-re3cKw2o7JSvd5=s96-c', 'X5LQCURFPG', '1', '2025-02-24 15:35:47', 8),
(25, '114869489688930855296', 'Rustiktam', '1q1', 'zxc', 'rramilperm@gmail.com', 'https://lh3.googleusercontent.com/a-/ALV-UjVY3GWCzg8vA4glNlvC9x83Yl3qYs9AsnTAL-re3cKw2o7JSvd5=s96-c', 'X5LQCURFPG', '1', '2025-02-24 15:35:47', 8),
(26, '114869489688930855296', 'Rustiktam', '1q1', 'zxc', 'rramilperm@gmail.com', 'https://lh3.googleusercontent.com/a-/ALV-UjVY3GWCzg8vA4glNlvC9x83Yl3qYs9AsnTAL-re3cKw2o7JSvd5=s96-c', NULL, '0', '2025-02-24 15:35:47', 8),
(27, '114869489688930855296', 'Rustiktam', '1q1', 'zxc', 'rramilperm@gmail.com', 'https://lh3.googleusercontent.com/a-/ALV-UjVY3GWCzg8vA4glNlvC9x83Yl3qYs9AsnTAL-re3cKw2o7JSvd5=s96-c', 'X5LQCURFPG', '1', '2025-02-24 15:35:47', 8),
(28, '114869489688930855296', 'Rustiktam', '1q1', 'zxc', 'rramilperm@gmail.com', 'https://lh3.googleusercontent.com/a-/ALV-UjVY3GWCzg8vA4glNlvC9x83Yl3qYs9AsnTAL-re3cKw2o7JSvd5=s96-c', 'X5LQCURFPG', '1', '2025-02-24 15:35:47', 8),
(29, '114869489688930855296', 'Rustiktam', '1q1', 'zxc', 'rramilperm@gmail.com', 'https://lh3.googleusercontent.com/a-/ALV-UjVY3GWCzg8vA4glNlvC9x83Yl3qYs9AsnTAL-re3cKw2o7JSvd5=s96-c', 'X5LQCURFPG', '1', '2025-02-24 15:35:47', 8);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT для таблицы `progress`
--
ALTER TABLE `progress`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT для таблицы `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT для таблицы `questions_quizzes`
--
ALTER TABLE `questions_quizzes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT для таблицы `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `sub_categories`
--
ALTER TABLE `sub_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

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

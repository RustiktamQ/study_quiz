-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.2:3306
-- Время создания: Мар 17 2025 г., 11:23
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
-- Структура таблицы `access_tokens`
--

CREATE TABLE `access_tokens` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `used_at` int(11) DEFAULT NULL,
  `valid_till` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `used` int(11) DEFAULT NULL,
  `token` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
-- Структура таблицы `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `message` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address_type` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address_id` int(11) NOT NULL,
  `created_at` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `checked` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `notifications`
--

INSERT INTO `notifications` (`id`, `message`, `address_type`, `address_id`, `created_at`, `checked`) VALUES
(1, 'Student qwe w has completed the test \'Place Value Mastery\'', 'teachers', 3, '2025-03-10 14:05:22', 0),
(2, 'Student qwe w has completed the test \'Place Value Mastery\'', 'student', 21, '10.03.2025', 0),
(4, 'test2', 'student', 21, '11.03.2025', 1);

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
  `answered` int(11) NOT NULL,
  `correct_answers` int(11) NOT NULL,
  `elapsed_time` varchar(32) NOT NULL,
  `shuffled_questions` text NOT NULL,
  `current_question_index` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `progress`
--

INSERT INTO `progress` (`id`, `student_id`, `quiz_id`, `current_question`, `score`, `completed`, `start_time`, `answered`, `correct_answers`, `elapsed_time`, `shuffled_questions`, `current_question_index`) VALUES
(82, 21, 2, 6, 40, 0, '2025-03-07 16:41:55', 0, 5, '0hr. 2min. 12s.', '[\"5\",\"6\",\"4\"]', 1),
(84, 21, 1, 1, 0, 0, '2025-03-11 16:59:42', 0, 0, '', '', 0);

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
(16, 'How many inches are in one foot?', '12', '[\"10\", \"11\", \"12\", \"13\"]', 'One foot is equal to 12 inches.');

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
(16, 1, 16);

-- --------------------------------------------------------

--
-- Структура таблицы `quizzes`
--

CREATE TABLE `quizzes` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `quizzes`
--

INSERT INTO `quizzes` (`id`, `name`) VALUES
(1, '3rd Grade Math Basics'),
(2, 'Advanced Multiplication'),
(3, 'Place Value Mastery');

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
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `lang` varchar(255) DEFAULT NULL,
  `token` varchar(255) NOT NULL,
  `token_confirmed` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `teachers`
--

INSERT INTO `teachers` (`id`, `name`, `picture`, `firstname`, `lastname`, `email`, `password`, `lang`, `token`, `token_confirmed`) VALUES
(1, 'John Doe', 'https://vk.com/images/wall/deleted_avatar_50.png', 'John', 'Doe', 'teacher', 'teacher', 'ru', '123', 0),
(2, 'Иван Иванович', 'https://vk.com/images/wall/deleted_avatar_50.png', 'Иван', 'Иванович', 'me@kirillka.ru', '$2y$10$cs/wC9l2lqaaNQJmfQAkfemIAsx2buJWlVe5EaD2u05hjia0LyZbu', NULL, '1f487a371e9781ae8f84e77e1158305b', 0),
(3, 'Name Surname', 'https://vk.com/images/wall/deleted_avatar_50.png', 'Name', 'Surname', 'test@testmail.io', '$2y$10$atuXNps7M/OFk2OUaP1xeueSYEJMfApgwp8RxzRefzQ2KzgSs6zpi', 'ru', 'd303e5f985dd550f91bd067e3580821c', 0);

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
  `token_confirmed` varchar(255) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `google_id`, `name`, `firstname`, `lastname`, `email`, `picture`, `token`, `token_confirmed`, `created_at`) VALUES
(21, '114869489688930855296', 'qwe w', 'qwe', 'w', 'rramilperm@gmail.com', 'https://lh3.googleusercontent.com/a-/ALV-UjVY3GWCzg8vA4glNlvC9x83Yl3qYs9AsnTAL-re3cKw2o7JSvd5=s96-c', 'd303e5f985dd550f91bd067e3580821c', '1', '2025-02-24 15:35:47');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `access_tokens`
--
ALTER TABLE `access_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `admins`
--
ALTER TABLE `admins`
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
  ADD KEY `FK_QQ_question_id` (`question_id`);

--
-- Индексы таблицы `quizzes`
--
ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `access_tokens`
--
ALTER TABLE `access_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `progress`
--
ALTER TABLE `progress`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT для таблицы `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT для таблицы `questions_quizzes`
--
ALTER TABLE `questions_quizzes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT для таблицы `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

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
  ADD CONSTRAINT `FK_QQ_question_id` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`),
  ADD CONSTRAINT `FK_QQ_quizze` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

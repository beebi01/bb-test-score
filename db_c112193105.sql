-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2025-05-25 18:45:41
-- 伺服器版本： 10.4.32-MariaDB
-- PHP 版本： 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `db_c112193105`
--
CREATE DATABASE IF NOT EXISTS `db_c112193105` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `db_c112193105`;

-- --------------------------------------------------------

--
-- 資料表結構 `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `spot_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `comments`
--

INSERT INTO `comments` (`id`, `spot_id`, `user_id`, `comment`, `created_at`) VALUES
(4, 23, 9, 'test\n', '2025-05-23 14:35:35'),
(5, 21, 1, 'test', '2025-05-24 09:12:59'),
(11, 23, 1, 'test', '2025-05-24 10:31:16');

-- --------------------------------------------------------

--
-- 資料表結構 `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `spot_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `likes`
--

INSERT INTO `likes` (`id`, `user_id`, `spot_id`, `created_at`) VALUES
(50, 1, 21, '2025-05-24 09:39:07'),
(52, 1, 23, '2025-05-24 10:06:43'),
(58, 1, 24, '2025-05-25 16:08:14'),
(59, 3, 21, '2025-05-25 16:31:36');

-- --------------------------------------------------------

--
-- 資料表結構 `members`
--

CREATE TABLE `members` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `members`
--

INSERT INTO `members` (`id`, `username`, `password`, `email`, `created_at`) VALUES
(1, 'once_twice', '20151025', 'minatozakisana@kpopmail.com', '2025-05-20 14:51:01'),
(2, 'sone_snsd', '20070805', 'taenngu@kpopmail.com', '2025-05-20 14:51:01'),
(3, 'my_aespa', '20201117', 'minjeong@kpopmail.com', '2025-05-20 14:51:01'),
(4, 'heartlipped', '19950922', 'nayeon@kpopmail.com', '2025-05-20 14:51:01'),
(5, 'Sa.Shine_SANA', '19961229', 'kimsana@kpopmail.com', '2025-05-20 14:51:01'),
(6, 'SIRENx39', '19890309', 'taeyeon@kpopmail.com', '2025-05-20 14:51:01'),
(7, 'iberiswinter', '20010101', 'winter@kpopmail.com', '2025-05-20 14:51:01'),
(8, 'just_0411', '20000411', 'karina@kpopmail.com', '2025-05-20 14:51:01'),
(9, 'army_bts', '20130613', 'JKarmy@kpopmail.com', '2025-05-20 14:51:01'),
(10, 'moa_txt', '123456', 'BGmoa@kpopmail.com', '2025-05-20 14:51:01');

-- --------------------------------------------------------

--
-- 資料表結構 `spots`
--

CREATE TABLE `spots` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_by` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `spots`
--

INSERT INTO `spots` (`id`, `name`, `description`, `created_by`, `image`, `created_at`) VALUES
(21, '', 'try', 3, '桌布.jpg', '2025-05-21 15:51:18'),
(23, '', '很多阿米應該都知道防彈在練習生時期常去這間食堂\r\n因為以前Big Hit的練習室就在這棟樓的地下室和二三樓的地方\r\n所以防彈們在拍攝《防彈新人王》時曾說過，他們的三餐幾乎都來這裡解決\r\n身為ARMY來到首爾了當然要去踩點，要來去吃吃防彈最常吃的黑豬肉拌飯，感受一下他們的回憶!!', 9, '油井食堂.webp', '2025-05-23 10:38:36'),
(24, '', 'good', 1, '女大.jpg', '2025-05-24 10:07:02');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `spot_id` (`spot_id`),
  ADD KEY `user_id` (`user_id`);

--
-- 資料表索引 `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `spot_id` (`spot_id`);

--
-- 資料表索引 `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `spots`
--
ALTER TABLE `spots`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `spots`
--
ALTER TABLE `spots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- 已傾印資料表的限制式
--

--
-- 資料表的限制式 `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`spot_id`) REFERENCES `spots` (`id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `members` (`id`);

--
-- 資料表的限制式 `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `members` (`id`),
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`spot_id`) REFERENCES `spots` (`id`);

--
-- 資料表的限制式 `spots`
--
ALTER TABLE `spots`
  ADD CONSTRAINT `spots_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `members` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

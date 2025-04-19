-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2025-04-19 09:30:42
-- 伺服器版本： 10.4.32-MariaDB
-- PHP 版本： 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `system_project`
--

-- --------------------------------------------------------

--
-- 資料表結構 `advice`
--

CREATE TABLE `advice` (
  `advice_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `advice_title` varchar(20) DEFAULT NULL,
  `advice_content` text DEFAULT NULL,
  `agree` int(11) DEFAULT 0,
  `category` varchar(20) DEFAULT NULL,
  `advice_state` varchar(20) DEFAULT '未處理',
  `announce_date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `advice`
--

INSERT INTO `advice` (`advice_id`, `user_id`, `advice_title`, `advice_content`, `agree`, `category`, `advice_state`, `announce_date`) VALUES
(3, 412402002, '123', '學校廁所施工很吵! 希望學校能在假日的時候施工', 1, '設施改善', '未處理', '2025-03-30'),
(4, 412402002, '456', 'redvsgg', 0, '學術發展', '未處理', '2025-03-30'),
(9, 412402002, '111', '111', 1, '環保永續', '未處理', '2025-03-30'),
(16, 412402002, '55', 'qggo', 0, '學術發展', '未處理', '2025-03-30'),
(17, 412402002, '55', 'qggo', 0, '環保永續', '未處理', '2025-03-30'),
(18, 333333, '55', '33', 1, '設施改善', '未處理', '2025-03-30'),
(19, 333333, '教室好熱', '建議配備新冷氣', 3, '設施改善', '募資中', '2025-04-01'),
(20, 333333, '教室好臭', '學校應該買天然空氣清新器在教室', 1, '設施改善', '未處理', '2025-04-01'),
(21, 333333, '7899', '777777', 1, '學術發展', '未處理', '2025-04-08'),
(22, 333333, '6666', '666666', 0, '公益活動', '未處理', '2025-04-08'),
(23, 333333, '測試', '測試圖片', 2, '學術發展', '未處理', '2025-04-11'),
(24, 333333, 'gggg', '社團需要被企業看到，希望學校可以和更多企業合作', 3, 'club', '未處理', '2025-04-15'),
(25, 333333, '學術發展', '我們應該積極讓我們的學生去比賽，如果學校沒供給錢做研究，大學會沒有競爭力', 0, 'academic', '未處理', '2025-04-18'),
(26, 333333, '55', '超愛丟垃圾', 0, 'environment', '未處理', '2025-04-19'),
(27, 333333, '今天學校蚊子很多', '學校可以多多除蚊', 0, 'environment', '未處理', '2025-04-19'),
(28, 333333, '今天學校蚊子很多', '學校可以多多除蚊', 0, 'environment', '未處理', '2025-04-19'),
(29, 333333, '今天學校蚊子很多', '學校可以多多除蚊', 0, 'environment', '未處理', '2025-04-19'),
(30, 333333, '今天學校蚊子很多', '學校可以多多除蚊', 0, 'environment', '未處理', '2025-04-19'),
(31, 333333, 'ppp', 'ppp', 0, 'club', '未處理', '2025-04-19'),
(32, 333333, 'ppp', 'ppp', 0, 'welfare', '未處理', '2025-04-19'),
(33, 333333, 'ppp', 'ppp', 0, 'welfare', '未處理', '2025-04-19'),
(34, 333333, 'ppp', 'ppp', 0, 'welfare', '未處理', '2025-04-19');

-- --------------------------------------------------------

--
-- 資料表結構 `advice_image`
--

CREATE TABLE `advice_image` (
  `img_id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `advice_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `advice_image`
--

INSERT INTO `advice_image` (`img_id`, `file_name`, `file_path`, `advice_id`) VALUES
(5, 'messageImage_1682701968425.jpg', 'uploads/img_67f8f4ee2435c0.39011411.jpg', 23),
(6, '06.png', 'uploads/img_67fdfac3e5ea40.69014083.png', 24),
(7, '10.png', 'uploads/img_680227fa7f3479.36734974.png', 25),
(8, 'time_complexity.png', 'uploads/680350d5721402.04523504.png', 34);

-- --------------------------------------------------------

--
-- 資料表結構 `agree_record`
--

CREATE TABLE `agree_record` (
  `agree_record_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `advice_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `agree_record`
--

INSERT INTO `agree_record` (`agree_record_id`, `user_id`, `advice_id`) VALUES
(8, 412402002, 19),
(9, 333333, 19),
(10, 333333, 20),
(11, 333333, 3),
(12, 412402141, 19),
(13, 412402141, 18),
(14, 333333, 24),
(15, 345678, 24),
(16, 333333, 23),
(17, 333333, 9),
(18, 412402141, 24),
(19, 412402141, 21),
(20, 412402001, 23);

-- --------------------------------------------------------

--
-- 資料表結構 `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `advice_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment_content` text NOT NULL,
  `comment_time` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `comments`
--

INSERT INTO `comments` (`comment_id`, `advice_id`, `user_id`, `comment_content`, `comment_time`) VALUES
(2, 24, 333333, 'bobjsobmmos', '2025-04-18'),
(3, 24, 333333, 'agojogojaeojg', '2025-04-18'),
(4, 24, 333333, 'aobajobhojojhojojh', '2025-04-18'),
(5, 24, 333333, '這張圖好好笑', '2025-04-18'),
(6, 24, 333333, '好好笑', '2025-04-18'),
(7, 24, 333333, '企業看不見嗚嗚', '2025-04-18'),
(8, 24, 333333, '沒人看的見我們', '2025-04-18'),
(9, 24, 333333, '我也覺得我們要被看見', '2025-04-18'),
(10, 24, 333333, '需要贊助+1', '2025-04-18'),
(11, 24, 333333, '+1', '2025-04-18'),
(12, 24, 333333, '沒錯! 這篇不能沉', '2025-04-18'),
(13, 23, 333333, '讚', '2025-04-18'),
(14, 23, 333333, '鉛筆人', '2025-04-18'),
(15, 23, 333333, '我支持你!', '2025-04-18'),
(16, 23, 333333, '太強了', '2025-04-18'),
(17, 23, 333333, '要測試了嗎?', '2025-04-18'),
(18, 23, 333333, '學校應該對這件事很在乎吧', '2025-04-18'),
(19, 23, 333333, '我覺得這張圖蠻符合測試的', '2025-04-18'),
(20, 20, 333333, '確實廁所很臭', '2025-04-18'),
(21, 20, 333333, '廁所真的超臭\n像臭水溝一樣\n學生好可憐', '2025-04-18'),
(22, 20, 333333, '沒錯', '2025-04-18'),
(23, 20, 333333, '真的', '2025-04-18'),
(24, 20, 333333, '有夠可憐的學生', '2025-04-18'),
(25, 20, 333333, '超級可憐ㄟ', '2025-04-18'),
(26, 20, 333333, '可憐', '2025-04-18'),
(27, 20, 333333, '好可憐', '2025-04-18'),
(28, 20, 333333, '可憐到爆', '2025-04-18'),
(29, 20, 333333, '嗚嗚可憐', '2025-04-18'),
(30, 20, 333333, '可憐', '2025-04-18'),
(31, 20, 333333, '嗚嗚可憐的留言區', '2025-04-18'),
(32, 20, 333333, 'hk4g', '2025-04-18'),
(33, 20, 333333, '...', '2025-04-18'),
(34, 20, 333333, 'dk', '2025-04-18'),
(35, 20, 333333, '超可憐', '2025-04-18'),
(36, 20, 333333, '學校沒錢嗎', '2025-04-18'),
(37, 9, 333333, '小雞', '2025-04-18'),
(38, 9, 333333, '還有呢', '2025-04-18'),
(39, 9, 333333, '沒了', '2025-04-18'),
(40, 9, 333333, '可憐', '2025-04-18'),
(41, 9, 333333, '好多', '2025-04-18'),
(42, 9, 333333, '姑姑', '2025-04-18'),
(43, 9, 333333, '和我同學一樣的生肖', '2025-04-18'),
(44, 9, 333333, '吃雞永續發展嗎', '2025-04-18'),
(45, 9, 333333, '到底誰想到這麼好笑的東西', '2025-04-18'),
(46, 9, 333333, '永續發展', '2025-04-18'),
(47, 9, 333333, '校長有開永續發展的課程', '2025-04-18'),
(48, 9, 333333, '恩恩', '2025-04-18'),
(49, 9, 333333, '有點沒興趣', '2025-04-18'),
(50, 21, 333333, '好可憐', '2025-04-18'),
(51, 21, 333333, '好多', '2025-04-18'),
(52, 21, 333333, '好可憐', '2025-04-18'),
(53, 22, 333333, '好唷', '2025-04-18'),
(54, 19, 333333, '可憐，連冷氣錢都沒有', '2025-04-18'),
(55, 19, 333333, '太可憐了', '2025-04-18'),
(56, 19, 333333, '真的好可憐', '2025-04-18'),
(57, 19, 333333, '這隻雞和你一樣可憐', '2025-04-18'),
(58, 17, 333333, '這是甚麼?', '2025-04-18'),
(59, 25, 333333, '你有錢嗎', '2025-04-18'),
(60, 25, 333333, '沒錢就去地上玩沙好嗎', '2025-04-18'),
(61, 25, 333333, '去打工', '2025-04-18'),
(63, 4, 412402141, 'y04', '2025-04-18'),
(64, 4, 412402141, 'svvjojg', '2025-04-18'),
(65, 4, 412402141, 'fjaof', '2025-04-18'),
(66, 4, 412402141, 'dddddddd', '2025-04-18');

-- --------------------------------------------------------

--
-- 資料表結構 `files`
--

CREATE TABLE `files` (
  `file_id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `advice_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `files`
--

INSERT INTO `files` (`file_id`, `file_name`, `file_path`, `advice_id`) VALUES
(1, '公設檢查表.pdf', 'file_upload/680350d573a4d1.89863904.pdf', 34);

-- --------------------------------------------------------

--
-- 資料表結構 `funding`
--

CREATE TABLE `funding` (
  `funding_id` int(11) NOT NULL,
  `advice_id` int(11) NOT NULL,
  `money` int(11) NOT NULL DEFAULT 0,
  `target` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `funding`
--

INSERT INTO `funding` (`funding_id`, `advice_id`, `money`, `target`) VALUES
(3, 19, 0, 200);

-- --------------------------------------------------------

--
-- 資料表結構 `funding_comments`
--

CREATE TABLE `funding_comments` (
  `funding_comments_id` int(11) NOT NULL,
  `funding_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `comment_time` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `funding_people`
--

CREATE TABLE `funding_people` (
  `funding_people_id` int(11) NOT NULL,
  `people_name` varchar(20) NOT NULL,
  `donate_money` int(11) NOT NULL,
  `gmail` text DEFAULT NULL,
  `funding_id` int(11) NOT NULL,
  `check_login` int(1) NOT NULL DEFAULT 0 COMMENT '確定是否為訪客:0為訪客、1為已登入'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `password` varchar(20) NOT NULL,
  `name` varchar(20) NOT NULL,
  `level` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `department` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用戶表';

--
-- 傾印資料表的資料 `users`
--

INSERT INTO `users` (`user_id`, `password`, `name`, `level`, `email`, `department`) VALUES
(332478, '332478', '安教授', 'teacher', '332478@m365.fju.edu.tw', '資訊管理學系'),
(333333, '333333', '王傑明', 'teacher', 'abcd@gmail.com', '外國語文學院'),
(345678, '345678', '歐陽修', 'manager', '123@gmail.com', '學務處'),
(412380012, '444aa', '吉伊卡娃', 'student', 'a@gmail.com', '藝術學院'),
(412402001, '412402001', '王小明', 'student', 'example@gmail.com', '資訊管理學系'),
(412402002, 'sss', '吉伊卡娃二號', 'student', 'abc@gmail.com', '文學院'),
(412402141, 'sss', ':0', 'student', 'abcd@gmail.com', '管理學院');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `advice`
--
ALTER TABLE `advice`
  ADD PRIMARY KEY (`advice_id`),
  ADD KEY `fk_user_id` (`user_id`);

--
-- 資料表索引 `advice_image`
--
ALTER TABLE `advice_image`
  ADD PRIMARY KEY (`img_id`),
  ADD KEY `advice_id` (`advice_id`);

--
-- 資料表索引 `agree_record`
--
ALTER TABLE `agree_record`
  ADD PRIMARY KEY (`agree_record_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `advice_id` (`advice_id`);

--
-- 資料表索引 `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `advice_id` (`advice_id`),
  ADD KEY `user_id` (`user_id`);

--
-- 資料表索引 `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`file_id`),
  ADD KEY `advice_id` (`advice_id`);

--
-- 資料表索引 `funding`
--
ALTER TABLE `funding`
  ADD PRIMARY KEY (`funding_id`),
  ADD KEY `advice_id` (`advice_id`);

--
-- 資料表索引 `funding_comments`
--
ALTER TABLE `funding_comments`
  ADD PRIMARY KEY (`funding_comments_id`),
  ADD KEY `funding_id` (`funding_id`),
  ADD KEY `user_id` (`user_id`);

--
-- 資料表索引 `funding_people`
--
ALTER TABLE `funding_people`
  ADD PRIMARY KEY (`funding_people_id`),
  ADD KEY `funding_id` (`funding_id`);

--
-- 資料表索引 `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `advice`
--
ALTER TABLE `advice`
  MODIFY `advice_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `advice_image`
--
ALTER TABLE `advice_image`
  MODIFY `img_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `agree_record`
--
ALTER TABLE `agree_record`
  MODIFY `agree_record_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `files`
--
ALTER TABLE `files`
  MODIFY `file_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `funding`
--
ALTER TABLE `funding`
  MODIFY `funding_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `funding_comments`
--
ALTER TABLE `funding_comments`
  MODIFY `funding_comments_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `funding_people`
--
ALTER TABLE `funding_people`
  MODIFY `funding_people_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=412402142;

--
-- 已傾印資料表的限制式
--

--
-- 資料表的限制式 `advice`
--
ALTER TABLE `advice`
  ADD CONSTRAINT `advice_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- 資料表的限制式 `advice_image`
--
ALTER TABLE `advice_image`
  ADD CONSTRAINT `advice_image_ibfk_1` FOREIGN KEY (`advice_id`) REFERENCES `advice` (`advice_id`) ON DELETE CASCADE;

--
-- 資料表的限制式 `agree_record`
--
ALTER TABLE `agree_record`
  ADD CONSTRAINT `agree_record_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `agree_record_ibfk_2` FOREIGN KEY (`advice_id`) REFERENCES `advice` (`advice_id`) ON DELETE CASCADE;

--
-- 資料表的限制式 `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`advice_id`) REFERENCES `advice` (`advice_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- 資料表的限制式 `files`
--
ALTER TABLE `files`
  ADD CONSTRAINT `files_ibfk_1` FOREIGN KEY (`advice_id`) REFERENCES `advice` (`advice_id`);

--
-- 資料表的限制式 `funding`
--
ALTER TABLE `funding`
  ADD CONSTRAINT `funding_ibfk_1` FOREIGN KEY (`advice_id`) REFERENCES `advice` (`advice_id`) ON DELETE CASCADE;

--
-- 資料表的限制式 `funding_comments`
--
ALTER TABLE `funding_comments`
  ADD CONSTRAINT `funding_comments_ibfk_1` FOREIGN KEY (`funding_id`) REFERENCES `funding` (`funding_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `funding_comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- 資料表的限制式 `funding_people`
--
ALTER TABLE `funding_people`
  ADD CONSTRAINT `funding_people_ibfk_1` FOREIGN KEY (`funding_id`) REFERENCES `funding` (`funding_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

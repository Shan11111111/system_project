-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2025-04-18 09:06:36
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
  `agree` int(11) DEFAULT 0 COMMENT '是否同意匿名?',
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
(9, 412402002, '111', '111', 0, '環保永續', '未處理', '2025-03-30'),
(16, 412402002, '55', 'qggo', 0, '學術發展', '未處理', '2025-03-30'),
(17, 412402002, '55', 'qggo', 0, '環保永續', '未處理', '2025-03-30'),
(18, 333333, '55', '33', 1, '設施改善', '未處理', '2025-03-30'),
(19, 333333, '教室好熱', '建議配備新冷氣', 3, '設施改善', '募資中', '2025-04-01'),
(20, 333333, '教室好臭', '學校應該買天然空氣清新器在教室', 1, '設施改善', '未處理', '2025-04-01'),
(21, 333333, '7899', '777777', 0, '學術發展', '未處理', '2025-04-08'),
(22, 333333, '6666', '666666', 0, '公益活動', '未處理', '2025-04-08'),
(23, 333333, '測試', '測試圖片', 0, '學術發展', '未處理', '2025-04-11'),
(24, 333333, 'gggg', '社團需要被企業看到，希望學校可以和更多企業合作', 2, 'club', '未處理', '2025-04-15');

-- --------------------------------------------------------

--
-- 資料表結構 `advice_image`
--

CREATE TABLE `advice_image` (
  `img_id` int(11) NOT NULL,
  `img_name` varchar(255) NOT NULL,
  `img_path` text NOT NULL,
  `advice_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `advice_image`
--

INSERT INTO `advice_image` (`img_id`, `img_name`, `img_path`, `advice_id`) VALUES
(5, 'messageImage_1682701968425.jpg', 'uploads/img_67f8f4ee2435c0.39011411.jpg', 23),
(6, '06.png', 'uploads/img_67fdfac3e5ea40.69014083.png', 24);

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
(15, 345678, 24);

-- --------------------------------------------------------

--
-- 資料表結構 `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `advice_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment_content` text NOT NULL,
  `reply_to` int(11) NOT NULL DEFAULT 0 COMMENT '留言回覆給誰',
  `comment_time` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `files`
--

CREATE TABLE `files` (
  `file_id` int(11) NOT NULL,
  `file_name` text NOT NULL,
  `file_path` text NOT NULL,
  `advice_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  ADD KEY `funding_id` (`funding_id`);

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
  MODIFY `advice_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `advice_image`
--
ALTER TABLE `advice_image`
  MODIFY `img_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `agree_record`
--
ALTER TABLE `agree_record`
  MODIFY `agree_record_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `files`
--
ALTER TABLE `files`
  MODIFY `file_id` int(11) NOT NULL AUTO_INCREMENT;

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
  ADD CONSTRAINT `funding_comments_ibfk_1` FOREIGN KEY (`funding_id`) REFERENCES `funding` (`funding_id`) ON DELETE CASCADE;

--
-- 資料表的限制式 `funding_people`
--
ALTER TABLE `funding_people`
  ADD CONSTRAINT `funding_people_ibfk_1` FOREIGN KEY (`funding_id`) REFERENCES `funding` (`funding_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

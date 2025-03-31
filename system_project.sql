-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2025-03-31 05:23:50
-- 伺服器版本： 10.4.32-MariaDB
-- PHP 版本： 8.1.25

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
  `user_id` int(11) DEFAULT NULL,
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
(2, 412402001, '泳池超級臭', '學校趕快清理', 0, '環境', '未處理', '2025-03-23'),
(3, 412402001, '泳池地板髒', 'zzz', 2, '環境', '未處理', '2025-03-13'),
(4, 412402004, '泳池水很冰', 'xxx', 4, ' 環境', '未處理', '2025-03-04'),
(5, 412402001, '泳池不乾淨', '提議學生有薪水的打掃泳池', 3, '環境', '未處理', '2025-03-18'),
(7, 412402002, '改善宿舍網路', '希望宿舍提供更穩定的 Wi-Fi，晚上常常斷線影響讀書。', 15, '校園環境', '進行中', '2025-03-30'),
(8, 332478, '圖書館24小時開放', '期中期末考期間，圖書館應該延長開放時間，甚至24小時開放。', 20, '學術資源', '未處理', '2025-03-29'),
(9, 412402003, '餐廳菜單更新', '學校餐廳的餐點選擇太少，希望增加更多健康餐點選擇，例如低油低鹽餐。', 12, '生活服務', '未處理', '2025-03-28'),
(10, 412402005, '運動設施維護', '操場跑道有些破損，希望能重新整修，確保學生運動安全。', 18, '校園環境', '進行中', '2025-03-27'),
(11, 332478, '增加校內公車班次', '上下課尖峰時間，校內接駁車經常爆滿，希望能增加班次。', 25, '生活服務', '已結束', '2025-03-26'),
(12, 412402002, '123', '學校廁所施工很吵! 希望學校能在假日的時候施工', 0, '設施改善', '未處理', '2025-03-30'),
(13, 412402002, '456', 'redvsgg', 0, '學術發展', '未處理', '2025-03-30'),
(14, 412402002, 'gggg', 'chicken', 0, '設施改善', '未處理', '2025-03-30'),
(15, 412402002, '111', '111', 0, '環保永續', '未處理', '2025-03-30'),
(16, 412402002, '55', 'qggo', 0, '學術發展', '未處理', '2025-03-30'),
(17, 412402002, '55', 'qggo', 0, '環保永續', '未處理', '2025-03-30'),
(72, 412402005, '圖書館座位不足', '希望增加圖書館的自習座位，提高利用率。', 18, '設施改善', '未處理', '2025-03-30'),
(73, 412402004, '增加線上課程', '希望能有更多線上課程，方便學生自主學習。', 22, '學術發展', '未處理', '2025-03-30'),
(74, 412402003, '提升社團經費補助', '希望學校提供更多社團活動經費支援。', 15, '社團活動', '未處理', '2025-03-30'),
(75, 412402002, '定期舉辦公益活動', '希望每學期能安排愛心募捐或志工活動。', 19, '公益關懷', '未處理', '2025-03-30'),
(76, 412402001, '設立校內回收站', '希望能設立專門的回收站，提升資源回收率。', 13, '環保永續', '未處理', '2025-03-30'),
(77, 412402004, '增設無障礙設施', '希望學校能增設更多無障礙坡道與電梯。', 20, '設施改善', '未處理', '2025-03-30'),
(78, 412402002, '增加產學合作機會', '希望學校能引進更多企業合作計畫。', 24, '學術發展', '未處理', '2025-03-30'),
(79, 412402003, '開設創業輔導社團', '希望學校支持創業相關社團，提供資源。', 16, '社團活動', '未處理', '2025-03-30'),
(80, 412402001, '舉辦校內二手書市集', '希望定期舉辦二手書交易，減少浪費。', 21, '公益關懷', '未處理', '2025-03-30'),
(81, 412402003, '增設更多休息空間', '希望在校園內多設置一些休息長椅與綠蔭區。', 14, '其他', '未處理', '2025-03-30');

-- --------------------------------------------------------

--
-- 資料表結構 `advice_image`
--

CREATE TABLE `advice_image` (
  `img_id` int(11) NOT NULL,
  `img_name` varchar(255) NOT NULL,
  `img_data` blob NOT NULL,
  `advice_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `agree_record`
--

CREATE TABLE `agree_record` (
  `agree_record_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `advice_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `advice_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `comment_content` text DEFAULT NULL,
  `comment_time` date DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `comments`
--

INSERT INTO `comments` (`comment_id`, `advice_id`, `user_id`, `comment_content`, `comment_time`) VALUES
(1, 2, 412402001, '沒有人想去刷泳池...', '2025-03-23');

-- --------------------------------------------------------

--
-- 資料表結構 `funding`
--

CREATE TABLE `funding` (
  `funding_id` int(11) NOT NULL,
  `advice_id` int(11) DEFAULT NULL,
  `money` int(11) DEFAULT 0,
  `target` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `funding_people`
--

CREATE TABLE `funding_people` (
  `funding_people_id` int(11) NOT NULL,
  `people_name` varchar(20) DEFAULT NULL,
  `donate_money` int(11) DEFAULT NULL,
  `funding_id` int(11) DEFAULT NULL
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
  `email` text DEFAULT NULL,
  `department` varchar(20) DEFAULT NULL
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
(412402002, '412402002', '王明天', 'student', 'xmwang@example.com', '資訊工程學系'),
(412402003, '412402003', '陳美麗', 'student', 'mlchen@example.com', '企管系'),
(412402004, '412402004', '林教授', 'teacher', 'prof.lin@example.com', '英文系'),
(412402005, '412402005', '李主任', 'teacher', 'dir.lee@example.com', '醫學院'),
(412402006, 'sss', '吉伊卡娃二號', 'student', 'abc@gmail.com', '文學院');

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
-- 資料表索引 `funding`
--
ALTER TABLE `funding`
  ADD PRIMARY KEY (`funding_id`),
  ADD KEY `advice_id` (`advice_id`);

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
  MODIFY `advice_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `advice_image`
--
ALTER TABLE `advice_image`
  MODIFY `img_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `agree_record`
--
ALTER TABLE `agree_record`
  MODIFY `agree_record_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `funding`
--
ALTER TABLE `funding`
  MODIFY `funding_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `funding_people`
--
ALTER TABLE `funding_people`
  MODIFY `funding_people_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=412402007;

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
-- 資料表的限制式 `funding`
--
ALTER TABLE `funding`
  ADD CONSTRAINT `funding_ibfk_1` FOREIGN KEY (`advice_id`) REFERENCES `advice` (`advice_id`) ON DELETE CASCADE;

--
-- 資料表的限制式 `funding_people`
--
ALTER TABLE `funding_people`
  ADD CONSTRAINT `funding_people_ibfk_1` FOREIGN KEY (`funding_id`) REFERENCES `funding` (`funding_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

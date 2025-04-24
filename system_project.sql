-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2025-04-24 18:55:18
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
(24, 333333, 'gggg', '社團需要被企業看到，希望學校可以和更多企業合作', 3, 'club', '未處理', '2025-04-15'),
(25, 333333, '學術發展', '我們應該積極讓我們的學生去比賽，如果學校沒供給錢做研究，大學會沒有競爭力', 0, 'academic', '未處理', '2025-04-18'),
(26, 333333, '55', '超愛丟垃圾', 1, 'environment', '未處理', '2025-04-19'),
(27, 333333, '今天學校蚊子很多', '學校可以多多除蚊', 1, 'environment', '未處理', '2025-04-19'),
(32, 333333, 'ppp', 'ppp', 1, 'welfare', '未處理', '2025-04-19'),
(33, 333333, 'ppp', 'ppp', 0, 'welfare', '未處理', '2025-04-19'),
(34, 333333, 'ppp', 'ppp', 0, 'welfare', '未處理', '2025-04-19'),
(35, 333333, '改善廁所', '清潔太差了', 0, 'equipment', '未處理', '2025-02-02'),
(36, 333333, '延長開館', '讀書時間短', 0, 'equipment', '未處理', '2025-04-19'),
(37, 333333, '設立寫作班', '缺乏論文訓練', 1, 'academic', '未處理', '2025-04-19'),
(38, 333333, '開放研究室', '設備難借用', 0, 'academic', '未處理', '2025-04-19'),
(39, 333333, '舉辦淨灘', '環保很重要', 0, 'welfare', '未處理', '2025-04-19'),
(40, 333333, '交友', '舉辦聚餐交友', 0, 'other', '未處理', '2025-04-19'),
(41, 333333, '綠色校園', '綠色校園', 3, 'environment', '已分派', '2025-04-19'),
(42, 333333, '社團博覽會', '宣傳太少', 4, 'club', '已分派', '2025-04-19'),
(43, 333333, '設立休息區', '休息沒空間', 2, 'other', '未處理', '2025-04-19'),
(44, 333333, '舉辦文化週', '交流太少', 3, 'other', '已分派', '2025-04-19'),
(45, 333333, 'zjgojrzjjrg', 'zjgrogjigjjgojgji', 3, 'environment', '未處理', '2025-04-22'),
(46, 412402141, 'zjgojrzjjrg', 'jiojojoojjguihl', 1, 'welfare', '未處理', '2025-04-22'),
(47, 412402141, '55', 'gOEJOJejoto', 0, 'welfare', '未處理', '2025-04-22'),
(48, 412402141, 'jgozjojojoj', 'gjogjjgjgjojg', 0, 'academic', '未處理', '2025-04-22'),
(49, 412402141, 'jwojajgjagjojg', 'gojaogjjagjjagjgjoojgj', 1, 'environment', '未處理', '2025-04-22'),
(50, 412401011, '廁所施工聲音好吵', '請學校督促工人在課堂期間做好隔音', 3, 'equipment', '已分派', '2025-04-24'),
(51, 412401011, '輔仁醫院附近有變態', '我們發現輔仁醫院這裡有一位怪怪的老伯伯，他常來醫院附近和學生搭訕，我們覺得學校應該加強校安，希望可以加強校安人手。', 3, 'other', '已分派', '2025-04-24'),
(52, 412401011, '專題經費不足', '我們希望學校可以多給研究經費，尤其是我們要往更人工智慧發展，需要額外的金錢，我希望學校可以多給學生研究費用!尤其，教育部有給津貼，我覺得學校可以做更多的事情幫助學生更好!', 2, 'academic', '未處理', '2025-04-24'),
(53, 412401011, '捐血救人', '我們希望學校的捐血救人活動可以提供捐血一袋送禮物的活動', 3, 'welfare', '已分派', '2025-04-24'),
(54, 412401011, '請學校多宣傳捐血活動', '我們真的很需要有更多人捐血幫助血友症患者，我們希望學校能擴大宣傳或是幫助我們的活動實質的幫助!', 3, 'welfare', '未處理', '2025-04-24'),
(55, 412401011, '輔大應該積極推廣研究發展', '我認為學校應該提供更多經費幫助在研究計畫，例如織品系的紡織車不夠多，需要預約登記，時間不夠，無法在時間內完成。', 2, 'academic', '未處理', '2025-04-24'),
(56, 412401011, '輔大傳院跨國交流超炫!黃盟欽獲邀巴義兩國', '號外！號外！傳播學院獲獎喜訊不斷。創院以來第一人，影傳系藝術家黃盟欽老師遊走世界最前端，今年以新媒體科技結合影像創作，獲邀參加2024年10月15日在西班牙畢爾包舉辦的「BIDEODROMO國際實驗電影與錄影藝術節」，全台只有12位藝術家或團體參展。盟欽老師說：「能與來自世界各地的藝術家和觀眾交流，倍感榮幸與期待」。他不斷展現跨國藝術的交流能量，11月11日也獲邀到羅馬教廷參加我國駐教廷大使館主辦，由Sita Spada策展的「性靈相連」(Interconnected hearts) 數位藝術展，全台灣只有三人受邀。他以人工智慧對身體、科技和虛擬世界的影響，將數位人文與當代藝術融合，展現出科技與藝術的共融之美。簡直是超酷超炫的，輔大看似保守穩當，老師的靈魂一點都不保守。希望學校投入更多經費在傳播學院!', 3, 'academic', '已分派', '2025-04-24'),
(57, 412401011, '互動展覽經費補助', '我們希望學校能夠提供我們經費補助舉辦互動展覽，我們已經研發出來了互動展覽的展品，但因為手上的錢不夠租美術館，希望學校可以幫助我們!', 3, 'academic', '未處理', '2025-04-24'),
(58, 412401011, '舉辦下一次的社團展覽', '去年我們舉辦的社團展覽有很好的回響，今年需要一筆資金來籌辦社團展覽', 3, 'club', '已分派', '2025-04-24'),
(59, 412401011, '動漫電玩研習社的同學帶來活力四射的舞蹈', '動漫電玩研習社的同學帶來活力四射的舞蹈，請幫他們應援! 我們還需要100份應援球', 1, 'club', '未處理', '2025-04-24'),
(60, 412401011, '課外活動指導組的社團博覽會籌備團隊及燈光', '課外活動指導組的社團博覽會籌備團隊及燈光音響組工作人員，我們需要燈光設備兩台和麥可風五個', 1, 'club', '未處理', '2025-04-24');

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
(6, '06.png', 'uploads/img_67fdfac3e5ea40.69014083.png', 24),
(7, '10.png', 'uploads/img_680227fa7f3479.36734974.png', 25),
(8, 'time_complexity.png', 'uploads/680350d5721402.04523504.png', 34),
(9, '大頭貼.png', 'uploads/68036d038fa925.59403508.png', 35),
(10, '台大.jpg', 'uploads/68036d521ad146.54521166.jpg', 36),
(11, '女孩圖片.png', 'uploads/68036d82a39e01.78827698.png', 37),
(12, '女孩圖片.png', 'uploads/68036dab4c52b6.03091380.png', 38),
(13, 'Zitronenpresse_JuicySalif.jpg', 'uploads/68036ddc082fb0.18389448.jpg', 39),
(14, '男孩圖片.png', 'uploads/68036e09147ab0.60753746.png', 40),
(15, '台大.jpg', 'uploads/68036e60d02710.40235568.jpg', 41),
(16, '09.png', 'uploads/68036e7f4644e2.13730019.png', 42),
(17, '02.png', 'uploads/68036ef7ea0d94.98225831.png', 44),
(18, 'chicken_logo.png', 'uploads/68072a883e59b1.04497247.png', 45),
(19, 'chicken_logo.png', 'uploads/6807313b56a347.31007348.png', 46),
(20, 'chicken.png', 'uploads/68073acc8817c7.27225854.png', 49),
(21, '廁所.jpg', 'uploads/680a22a4120636.68855462.jpg', 50),
(22, '醫院.jpg', 'uploads/680a23d1f33015.59732567.jpg', 51),
(23, '學費.jpg', 'uploads/680a25293affa3.08263882.jpg', 52),
(24, '捐血.jpg', 'uploads/680a25cea7efc0.15229133.jpg', 53),
(25, '捐血救人.jpg', 'uploads/680a279d894cb5.40717671.jpg', 54),
(26, '研究發展.jpg', 'uploads/680a288819fa74.13965203.jpg', 55),
(27, '藝術展.jpg', 'uploads/680a297b717cc7.39125445.jpg', 56),
(28, '互動作品.jpg', 'uploads/680a2a02578ef4.49020897.jpg', 57),
(29, '社團活動.jpg', 'uploads/680a2a9f8a31d6.59051855.jpg', 58),
(30, '動漫社同學.jpg', 'uploads/680a2af8e01c45.29464488.jpg', 59),
(31, '1130923_021.jpg', 'uploads/680a2b4257d719.83630333.jpg', 60);

-- --------------------------------------------------------

--
-- 資料表結構 `advice_state`
--

CREATE TABLE `advice_state` (
  `advice_state_id` int(11) NOT NULL,
  `content` varchar(255) NOT NULL,
  `advice_id` int(11) NOT NULL,
  `state_time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 傾印資料表的資料 `advice_state`
--

INSERT INTO `advice_state` (`advice_state_id`, `content`, `advice_id`, `state_time`) VALUES
(1, '附議達標', 53, '2025-04-24 20:52:16'),
(2, '附議達標', 56, '2025-04-24 21:36:34'),
(3, '附議達標', 51, '2025-04-24 21:36:42'),
(4, '附議達標', 57, '2025-04-24 21:36:51'),
(5, '附議達標', 54, '2025-04-24 22:19:42'),
(6, '附議達標', 58, '2025-04-24 22:20:34'),
(7, '校方已回應', 51, '2025-04-25 00:39:43'),
(8, '校方已回應', 42, '2025-04-25 00:42:28');

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
(14, 333333, 24),
(15, 345678, 24),
(18, 412402141, 24),
(21, 333333, 44),
(22, 333333, 45),
(23, 333333, 42),
(24, 412402001, 45),
(25, 412402001, 43),
(26, 412402001, 41),
(27, 412402001, 27),
(28, 412402001, 42),
(29, 412402141, 45),
(31, 412402141, 41),
(32, 412402141, 42),
(33, 412402141, 32),
(34, 412402141, 37),
(35, 412402141, 44),
(36, 412402141, 26),
(37, 412402141, 49),
(38, 412402141, 46),
(39, 345678, 41),
(40, 345678, 42),
(41, 345678, 43),
(42, 345678, 44),
(43, 412401011, 50),
(44, 412401011, 56),
(45, 412401011, 55),
(46, 412401011, 60),
(47, 412401011, 53),
(48, 412401011, 54),
(49, 412401011, 58),
(50, 412401011, 51),
(51, 412401011, 57),
(52, 412401011, 59),
(53, 411100011, 53),
(54, 411100011, 52),
(55, 411100011, 58),
(56, 411100011, 57),
(57, 411100011, 51),
(58, 411100011, 56),
(59, 411100011, 50),
(60, 410101001, 50),
(61, 410101001, 53),
(62, 409100001, 54),
(63, 409100001, 55),
(64, 409100001, 56),
(65, 409100001, 51),
(66, 409100001, 57),
(67, 413010021, 52),
(68, 413010021, 54),
(69, 413010021, 58);

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
(59, 25, 333333, '你有錢嗎', '2025-04-18'),
(60, 25, 333333, '沒錢就去地上玩沙好嗎', '2025-04-18'),
(61, 25, 333333, '去打工', '2025-04-18'),
(67, 44, 333333, '超讚', '2025-04-19'),
(68, 44, 333333, '沒錯', '2025-04-19'),
(69, 44, 333333, '多一點交流', '2025-04-19'),
(70, 44, 333333, '好多蝴蝶', '2025-04-19'),
(71, 44, 333333, '好好笑', '2025-04-19'),
(72, 45, 412402141, 'jtojo', '2025-04-22'),
(73, 48, 412402141, 'fknefnwof', '2025-04-22');

-- --------------------------------------------------------

--
-- 資料表結構 `donation_record`
--

CREATE TABLE `donation_record` (
  `donation_id` int(11) NOT NULL,
  `donor` varchar(255) DEFAULT NULL,
  `project_id` int(11) NOT NULL,
  `donation_amount` decimal(10,2) DEFAULT NULL,
  `donation_time` datetime DEFAULT current_timestamp(),
  `email` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `donation_record`
--

INSERT INTO `donation_record` (`donation_id`, `donor`, `project_id`, `donation_amount`, `donation_time`, `email`) VALUES
(1, '王大明', 1, 1000.00, '2025-04-24 23:06:17', '409100001@gmail.com');

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
(1, '公設檢查表.pdf', 'file_upload/680350d573a4d1.89863904.pdf', 34),
(2, '選課類別變動申請書.pdf', 'file_upload/68072a883f9a56.08837058.pdf', 45),
(3, '選課類別變動申請書.pdf', 'file_upload/680a28881bb8b9.22274312.pdf', 55),
(4, '選課類別變動申請書.pdf', 'file_upload/680a297b72d683.04934517.pdf', 56);

-- --------------------------------------------------------

--
-- 資料表結構 `funding`
--

CREATE TABLE `funding` (
  `funding_id` int(11) NOT NULL,
  `advice_id` int(11) NOT NULL,
  `money` int(11) NOT NULL DEFAULT 0,
  `target` int(11) NOT NULL,
  `funding_time` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `funding`
--

INSERT INTO `funding` (`funding_id`, `advice_id`, `money`, `target`, `funding_time`) VALUES
(7, 24, 0, 2000, '2025-04-22'),
(8, 42, 0, 1000, '2025-04-22'),
(9, 45, 0, 1000, '2025-04-22');

-- --------------------------------------------------------

--
-- 資料表結構 `funding_comments`
--

CREATE TABLE `funding_comments` (
  `funding_comments_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment_text` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `funding_comments`
--

INSERT INTO `funding_comments` (`funding_comments_id`, `project_id`, `user_id`, `comment_text`, `created_at`) VALUES
(1, 2, 345678, '讚喔', '2025-04-24 23:36:40'),
(2, 2, 345678, '讚喔', '2025-04-24 23:37:24'),
(3, 2, 345678, '喔', '2025-04-24 23:41:47'),
(4, 2, 345678, '我認同!', '2025-04-24 23:43:17'),
(5, 2, 345678, '學校必須改善!', '2025-04-24 23:45:21'),
(6, 2, 345678, '真的很吵', '2025-04-24 23:47:24'),
(7, 2, 345678, '真的很吵', '2025-04-24 23:48:25'),
(8, 2, 345678, '真的', '2025-04-24 23:50:16'),
(9, 2, 345678, '讚', '2025-04-24 23:51:20'),
(10, 2, 345678, '?', '2025-04-24 23:51:46'),
(11, 4, 345678, '我遇到好多次，怪怪的人', '2025-04-24 23:53:47');

-- --------------------------------------------------------

--
-- 資料表結構 `funding_state`
--

CREATE TABLE `funding_state` (
  `funding_state_id` int(11) NOT NULL,
  `content` int(255) NOT NULL,
  `funding_id` int(11) NOT NULL,
  `state_time` int(11) NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `fundraising_projects`
--

CREATE TABLE `fundraising_projects` (
  `project_id` int(11) NOT NULL,
  `suggestion_assignments_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `funding_goal` int(11) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime DEFAULT NULL,
  `status` enum('進行中','已完成','已取消') DEFAULT '進行中'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `fundraising_projects`
--

INSERT INTO `fundraising_projects` (`project_id`, `suggestion_assignments_id`, `title`, `description`, `funding_goal`, `start_date`, `end_date`, `status`) VALUES
(1, 3, '綠色校園', '購買校園消臭劑置放在校園內', 9000, '2025-04-22 21:40:49', NULL, '進行中'),
(2, 6, '廁所施工聲音好吵', '我們會盡力監督', 0, '2025-04-24 21:29:40', NULL, '進行中'),
(3, 5, '社團博覽會', '幫助學生在社團活動發光發熱', 8000, '2025-04-24 21:43:54', NULL, '進行中'),
(4, 7, '輔仁醫院附近有變態', '防狼噴霧普發1000瓶', 20000, '2025-04-24 22:15:43', NULL, '進行中'),
(5, 8, '捐血救人', '用於招募志工與工讀金的費用', 35000, '2025-04-24 22:17:25', NULL, '進行中'),
(6, 10, '舉辦下一次的社團展覽', '幫助社團募資', 25000, '2025-04-24 22:22:11', NULL, '進行中');

-- --------------------------------------------------------

--
-- 資料表結構 `replies`
--

CREATE TABLE `replies` (
  `reply_id` int(11) NOT NULL,
  `suggestion_assignments_id` int(11) NOT NULL,
  `office_id` int(11) NOT NULL,
  `reply_text` text NOT NULL,
  `replied_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `replies`
--

INSERT INTO `replies` (`reply_id`, `suggestion_assignments_id`, `office_id`, `reply_text`, `replied_at`) VALUES
(1, 3, 904, '了解同學，我們已經開始準備募資計畫了', '2025-04-24 21:03:09'),
(2, 3, 904, '同學，我們已經發布募資了，如果有募資到目標量，我們會盡力執行，謝謝同學', '2025-04-24 21:08:37'),
(3, 6, 904, '同學，我們可能控制不了聲音的問題', '2025-04-24 21:09:50'),
(4, 5, 345678, '我們已提交提案了!', '2025-04-24 21:43:30'),
(5, 7, 345678, '同學你好，我們會加強那邊的安全', '2025-04-24 22:13:59'),
(6, 8, 345678, '同學您好，我們會幫助你們', '2025-04-24 22:18:01'),
(8, 7, 345678, '同學請注意安全', '2025-04-25 00:32:42'),
(9, 7, 345678, '同學請注意安全', '2025-04-25 00:39:43'),
(10, 5, 345678, '歡迎同學在下方多留言', '2025-04-25 00:42:28'),
(11, 5, 345678, '歡迎同學', '2025-04-25 00:42:38');

-- --------------------------------------------------------

--
-- 資料表結構 `suggestion_assignments`
--

CREATE TABLE `suggestion_assignments` (
  `suggestion_assignments_id` int(11) NOT NULL,
  `advice_id` int(11) DEFAULT NULL,
  `office_id` int(11) DEFAULT NULL,
  `proposal_text` text DEFAULT NULL,
  `funding_amount` int(11) DEFAULT NULL,
  `proposal_file_path` varchar(255) DEFAULT NULL,
  `submitted` tinyint(1) DEFAULT 0,
  `approved_by_admin` tinyint(1) DEFAULT NULL,
  `admin_feedback` text DEFAULT NULL,
  `status` enum('草擬中','審核中','已通過','被退回') DEFAULT '草擬中',
  `submitted_at` datetime DEFAULT NULL,
  `reviewed_at` datetime DEFAULT NULL,
  `notification` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `suggestion_assignments`
--

INSERT INTO `suggestion_assignments` (`suggestion_assignments_id`, `advice_id`, `office_id`, `proposal_text`, `funding_amount`, `proposal_file_path`, `submitted`, `approved_by_admin`, `admin_feedback`, `status`, `submitted_at`, `reviewed_at`, `notification`) VALUES
(3, 41, 904, '購買校園消臭劑置放在校園內', 9000, '../uploads/chicken.png', 1, 1, '可以，希望有效', '已通過', '2025-04-22 21:37:32', '2025-04-22 21:40:49', 0),
(4, 44, 123, '文化季經費:食材費', 12000, '../uploads/附件一113學年度(學士班)輔系開班一覽表 (1).pdf', 1, 0, '你們要買甚麼?請寫好', '被退回', '2025-04-22 21:46:56', '2025-04-24 21:18:08', 1),
(5, 42, 345678, '幫助學生在社團活動發光發熱', 8000, '../uploads/動漫社同學.jpg', 1, 1, '不錯喔，祝順利', '已通過', '2025-04-24 21:43:02', '2025-04-24 21:43:54', 0),
(6, 50, 904, '我們會盡力監督', 0, '../uploads/廁所.jpg', 1, 1, '加油!', '已通過', '2025-04-24 21:10:31', '2025-04-24 21:29:40', 1),
(7, 51, 345678, '防狼噴霧普發1000瓶', 20000, '../uploads/醫院.jpg', 1, 1, '好的，祝順利', '已通過', '2025-04-24 22:15:17', '2025-04-24 22:15:43', 0),
(8, 53, 345678, '用於招募志工與工讀金的費用', 35000, '../uploads/捐血.jpg', 1, 1, '不錯喔', '已通過', '2025-04-24 22:17:05', '2025-04-24 22:17:25', 0),
(9, 56, 905, NULL, NULL, NULL, 0, NULL, NULL, '草擬中', NULL, NULL, 0),
(10, 58, 345678, '幫助社團募資', 25000, '../uploads/社團活動.jpg', 1, 1, '加油', '已通過', '2025-04-24 22:21:57', '2025-04-24 22:22:11', 0);

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
(123, '123', '王教務', 'office', '09@m365.fju.edu.tw', '教務處'),
(904, '0904', '王小姐', 'office', 'ajofjijo@gmail.com', '環境發展中心'),
(905, '0905', '陳先生', 'office', 'pubwww@mail.fju.edu.tw', '研究發展處'),
(906, '0906', '陳先生', 'office', 'pubwww@mail.fju.edu.tw', '國際事務處'),
(907, '0907', '陳先生', 'office', 'pubwww@mail.fju.edu.tw', '人事處'),
(908, '0908', '陳先生', 'office', 'pubwww@mail.fju.edu.tw', '資訊處'),
(909, '0909', '張先生', 'office', 'pubwww@mail.fju.edu.tw', '資金與資源中心'),
(1111, '1111', '系統管理者', 'manager', 'pubwww@mail.fju.edu.tw', '0'),
(2222, '2222', '王安石', 'office', 'pubwww@mail.fju.edu.tw', '總務處'),
(332478, '332478', '安教授', 'teacher', '332478@m365.fju.edu.tw', '資訊管理學系'),
(333333, '333333', '王傑明', 'teacher', 'abcd@gmail.com', '外國語文學院'),
(345678, '345678', '歐陽修', 'office', '123@gmail.com', '學務處'),
(409100001, '409100001', '王靜', 'student', '409100001@gmail.com', '外國語文學院'),
(410101001, '410101001', '王安', 'student', 'abc@gmail.com', '傳播學院'),
(411100011, '411100011', '王傑明', 'student', 'abc@gmail.com', '文學院'),
(412380012, '444aa', '吉伊卡娃', 'student', 'a@gmail.com', '藝術學院'),
(412401011, '412401011', '王同學', 'student', 'abc@gmail.com', '藝術學院'),
(412402001, '412402001', '王小明', 'student', 'example@gmail.com', '資訊管理學系'),
(412402002, 'sss', '吉伊卡娃二號', 'student', 'abc@gmail.com', '文學院'),
(412402141, 'sss', ':0', 'student', 'abcd@gmail.com', '管理學院'),
(413010021, '413010021', '王靜', 'student', '1@gmail.com', '醫學院');

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
-- 資料表索引 `advice_state`
--
ALTER TABLE `advice_state`
  ADD PRIMARY KEY (`advice_state_id`);

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
-- 資料表索引 `donation_record`
--
ALTER TABLE `donation_record`
  ADD PRIMARY KEY (`donation_id`),
  ADD KEY `project_id` (`project_id`);

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
  ADD KEY `project_id` (`project_id`),
  ADD KEY `user_id` (`user_id`);

--
-- 資料表索引 `funding_state`
--
ALTER TABLE `funding_state`
  ADD PRIMARY KEY (`funding_state_id`);

--
-- 資料表索引 `fundraising_projects`
--
ALTER TABLE `fundraising_projects`
  ADD PRIMARY KEY (`project_id`),
  ADD KEY `suggestion_assignments_id` (`suggestion_assignments_id`);

--
-- 資料表索引 `replies`
--
ALTER TABLE `replies`
  ADD PRIMARY KEY (`reply_id`),
  ADD KEY `suggestion_assignments_id` (`suggestion_assignments_id`),
  ADD KEY `office_id` (`office_id`);

--
-- 資料表索引 `suggestion_assignments`
--
ALTER TABLE `suggestion_assignments`
  ADD PRIMARY KEY (`suggestion_assignments_id`),
  ADD KEY `advice_id` (`advice_id`),
  ADD KEY `office_id` (`office_id`);

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
  MODIFY `advice_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `advice_image`
--
ALTER TABLE `advice_image`
  MODIFY `img_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `advice_state`
--
ALTER TABLE `advice_state`
  MODIFY `advice_state_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `agree_record`
--
ALTER TABLE `agree_record`
  MODIFY `agree_record_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `donation_record`
--
ALTER TABLE `donation_record`
  MODIFY `donation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `files`
--
ALTER TABLE `files`
  MODIFY `file_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `funding`
--
ALTER TABLE `funding`
  MODIFY `funding_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `funding_comments`
--
ALTER TABLE `funding_comments`
  MODIFY `funding_comments_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `funding_state`
--
ALTER TABLE `funding_state`
  MODIFY `funding_state_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `fundraising_projects`
--
ALTER TABLE `fundraising_projects`
  MODIFY `project_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `replies`
--
ALTER TABLE `replies`
  MODIFY `reply_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `suggestion_assignments`
--
ALTER TABLE `suggestion_assignments`
  MODIFY `suggestion_assignments_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=413010022;

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
-- 資料表的限制式 `donation_record`
--
ALTER TABLE `donation_record`
  ADD CONSTRAINT `donation_record_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `fundraising_projects` (`project_id`) ON DELETE CASCADE;

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
  ADD CONSTRAINT `funding_comments_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `fundraising_projects` (`project_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `funding_comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- 資料表的限制式 `fundraising_projects`
--
ALTER TABLE `fundraising_projects`
  ADD CONSTRAINT `fundraising_projects_ibfk_1` FOREIGN KEY (`suggestion_assignments_id`) REFERENCES `suggestion_assignments` (`suggestion_assignments_id`);

--
-- 資料表的限制式 `replies`
--
ALTER TABLE `replies`
  ADD CONSTRAINT `replies_ibfk_1` FOREIGN KEY (`suggestion_assignments_id`) REFERENCES `suggestion_assignments` (`suggestion_assignments_id`),
  ADD CONSTRAINT `replies_ibfk_2` FOREIGN KEY (`office_id`) REFERENCES `users` (`user_id`);

--
-- 資料表的限制式 `suggestion_assignments`
--
ALTER TABLE `suggestion_assignments`
  ADD CONSTRAINT `suggestion_assignments_ibfk_1` FOREIGN KEY (`advice_id`) REFERENCES `advice` (`advice_id`),
  ADD CONSTRAINT `suggestion_assignments_ibfk_2` FOREIGN KEY (`office_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

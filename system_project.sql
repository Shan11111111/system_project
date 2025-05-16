-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2025-05-14 13:09:46
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
(24, 333333, 'gggg', '社團需要被企業看到，希望學校可以和更多企業合作', 3, 'club', '已分派', '2025-04-15'),
(25, 333333, '學術發展', '我們應該積極讓我們的學生去比賽，如果學校沒供給錢做研究，大學會沒有競爭力', 0, 'academic', '未處理', '2025-04-18'),
(26, 333333, '55', '超愛丟垃圾', 3, 'environment', '已分派', '2025-04-19'),
(27, 333333, '今天學校蚊子很多', '學校可以多多除蚊', 1, 'environment', '未處理', '2025-04-19'),
(35, 333333, '改善廁所', '清潔太差了', 0, 'equipment', '未處理', '2025-02-02'),
(36, 333333, '延長開館', '讀書時間短', 0, 'equipment', '未處理', '2025-04-19'),
(37, 333333, '設立寫作班', '缺乏論文訓練', 1, 'academic', '未處理', '2025-04-19'),
(38, 333333, '開放研究室', '設備難借用', 0, 'academic', '未處理', '2025-04-19'),
(39, 333333, '舉辦淨灘', '環保很重要', 0, 'welfare', '未處理', '2025-04-19'),
(40, 333333, '交友', '舉辦聚餐交友', 0, 'other', '未處理', '2025-04-19'),
(41, 333333, '綠色校園', '綠色校園', 3, 'environment', '已回覆', '2025-04-19'),
(42, 333333, '社團博覽會', '宣傳太少', 4, 'club', '已回覆', '2025-04-19'),
(43, 333333, '設立休息區', '休息沒空間', 2, 'other', '未處理', '2025-04-19'),
(44, 333333, '舉辦文化週', '交流太少', 3, 'other', '已分派', '2025-04-19'),
(45, 333333, 'zjgojrzjjrg', 'zjgrogjigjjgojgji', 3, 'environment', '已分派', '2025-04-22'),
(46, 412402141, 'zjgojrzjjrg', 'jiojojoojjguihl', 3, 'welfare', '已分派', '2025-04-22'),
(50, 412401011, '廁所施工聲音好吵', '請學校督促工人在課堂期間做好隔音', 3, 'equipment', '已回覆', '2025-04-24'),
(51, 412401011, '輔仁醫院附近有變態', '我們發現輔仁醫院這裡有一位怪怪的老伯伯，他常來醫院附近和學生搭訕，我們覺得學校應該加強校安，希望可以加強校安人手。', 3, 'other', '已回覆', '2025-04-24'),
(52, 412401011, '專題經費不足', '我們希望學校可以多給研究經費，尤其是我們要往更人工智慧發展，需要額外的金錢，我希望學校可以多給學生研究費用!尤其，教育部有給津貼，我覺得學校可以做更多的事情幫助學生更好!', 3, 'academic', '已回覆', '2025-04-24'),
(53, 412401011, '捐血救人', '我們希望學校的捐血救人活動可以提供捐血一袋送禮物的活動', 3, 'welfare', '已回覆', '2025-04-24'),
(54, 412401011, '請學校多宣傳捐血活動', '我們真的很需要有更多人捐血幫助血友症患者，我們希望學校能擴大宣傳或是幫助我們的活動實質的幫助!', 3, 'welfare', '已分派', '2025-04-24'),
(55, 412401011, '輔大應該積極推廣研究發展', '我認為學校應該提供更多經費幫助在研究計畫，例如織品系的紡織車不夠多，需要預約登記，時間不夠，無法在時間內完成。', 3, 'academic', '已分派', '2025-04-24'),
(56, 412401011, '輔大傳院跨國交流超炫!黃盟欽獲邀巴義兩國', '號外！號外！傳播學院獲獎喜訊不斷。創院以來第一人，影傳系藝術家黃盟欽老師遊走世界最前端，今年以新媒體科技結合影像創作，獲邀參加2024年10月15日在西班牙畢爾包舉辦的「BIDEODROMO國際實驗電影與錄影藝術節」，全台只有12位藝術家或團體參展。盟欽老師說：「能與來自世界各地的藝術家和觀眾交流，倍感榮幸與期待」。他不斷展現跨國藝術的交流能量，11月11日也獲邀到羅馬教廷參加我國駐教廷大使館主辦，由Sita Spada策展的「性靈相連」(Interconnected hearts) 數位藝術展，全台灣只有三人受邀。他以人工智慧對身體、科技和虛擬世界的影響，將數位人文與當代藝術融合，展現出科技與藝術的共融之美。簡直是超酷超炫的，輔大看似保守穩當，老師的靈魂一點都不保守。希望學校投入更多經費在傳播學院!', 3, 'academic', '已分派', '2025-04-24'),
(57, 412401011, '互動展覽經費補助', '我們希望學校能夠提供我們經費補助舉辦互動展覽，我們已經研發出來了互動展覽的展品，但因為手上的錢不夠租美術館，希望學校可以幫助我們!', 3, 'academic', '已分派', '2025-04-24'),
(58, 412401011, '舉辦下一次的社團展覽', '去年我們舉辦的社團展覽有很好的回響，今年需要一筆資金來籌辦社團展覽', 3, 'club', '已回覆', '2025-04-24'),
(59, 412401011, '動漫電玩研習社的同學帶來活力四射的舞蹈', '動漫電玩研習社的同學帶來活力四射的舞蹈，請幫他們應援! 我們還需要100份應援球', 1, 'club', '未處理', '2025-04-24'),
(60, 412401011, '課外活動指導組的社團博覽會籌備團隊及燈光', '課外活動指導組的社團博覽會籌備團隊及燈光音響組工作人員，我們需要燈光設備兩台和麥可風五個', 3, 'club', '已分派', '2025-04-24'),
(62, 412402141, '一起救地球', '我們希望學校能夠發起學生救地球團體，前往各地參與各式各樣的環保行動', 0, 'environment', '未處理', '2025-05-07'),
(63, 412402141, '一起玩音樂', '我們希望學校可以撥補一些場地與設備給我們熱門音樂社，我們的團員渴望有一個舞台，讓更多人發現他們的閃亮點!', 0, 'club', '未處理', '2025-05-07'),
(64, 412402141, '我們希望可以得到贊助', '親愛的校方、師長好，我們是輔仁大學享食冰箱的負責人，近期發現越來越少人捐贈食物，很多人都向我們反映，學生的手頭經濟不充裕，我們希望可以藉由這個贊助幫助我們學校惡肚子的學生，謝謝大家的幫助TwT', 0, 'other', '未處理', '2025-05-07'),
(65, 412402141, '增加夜間自習教室數量', '目前圖書館晚上座位不足，建議開放更多教室供夜間自習，或延長開放時間', 3, 'equipment', '未處理', '2025-05-09'),
(66, 412402141, '推出健康飲食選項', '希望學校合作餐廳提供更多低糖、低油、低鈉的餐點選擇，幫助學生維持健康飲食。', 3, 'other', '未處理', '2025-05-09'),
(67, 409100001, '設置校園雨天地墊防滑措施', '下雨天走廊地面濕滑，建議放置止滑墊或改善排水設計以防止學生滑倒。', 3, 'equipment', '未處理', '2025-05-09'),
(68, 409100001, '改善宿舍網路速度', '宿舍網路常常延遲嚴重，建議學校升級設備或提供多個頻道分流使用。', 1, 'equipment', '未處理', '2025-05-09'),
(69, 409100001, '設立二手書交換平台', '學生每學期都需購買新書，建議建立線上平台讓學長姐轉售舊書給學弟妹。', 3, 'environment', '已分派', '2025-05-09'),
(70, 409100001, '改善課堂投影片品質', '部分老師使用的投影片文字過多或排版不佳，建議統一培訓教學簡報設計原則。', 3, 'academic', '未處理', '2025-05-09'),
(71, 409100001, '改善校園APP介面', '目前校園APP使用體驗不佳，建議重新設計UI/UX，讓查課表、查成績更直覺。', 2, 'other', '未處理', '2025-05-09'),
(72, 409100001, '推行教室節能減碳計畫', '建議安裝節能燈具，並鼓勵學生下課關燈、空調溫度調整至26度以降低耗能。', 1, 'environment', '未處理', '2025-05-09'),
(73, 412301092, '校園電梯壅塞問題改善', '上下課時段電梯過於擁擠，建議改用單層停靠或分層使用方式疏通人流。', 2, 'academic', '未處理', '2025-05-09'),
(74, 412301092, '建議考試週實施減課制度', '期末週課業與考試壓力大，建議減少不必要課程，讓學生有更多準備時間。', 1, 'academic', '未處理', '2025-05-09'),
(75, 412301092, '設立創意實作補助計畫', '希望學校提供小額創作或研究補助，鼓勵學生提出創新點子並實踐。', 0, 'academic', '未處理', '2025-05-09'),
(76, 412301092, '環保愛地球', '希望學校發放免費的環保餐具', 0, 'environment', '未處理', '2025-05-09'),
(77, 333333, '建議開設更多跨領域選修課，例如 AI +', '建議開設更多跨領域選修課，例如 AI + 藝術設計、醫療 + 資訊科技', 1, 'academic', '未處理', '2025-05-13'),
(78, 412303291, '部分必修課程教師教學進度過快', '部分必修課程教師教學進度過快，建議進行教學回饋後調整進度。', 0, 'academic', '未處理', '2025-05-13');

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
(31, '1130923_021.jpg', 'uploads/680a2b4257d719.83630333.jpg', 60),
(32, 'S.png', 'uploads/68108aa9433c01.51546450.png', 61),
(33, 'S__23527436.jpg', 'uploads/681b2fc96c4037.95908169.jpg', 62),
(34, '唱歌.jpg', 'uploads/681b30ec11ba20.91962390.jpg', 63),
(35, '御飯糰.jpeg', 'uploads/681b323a6a1d22.86093358.jpeg', 64),
(36, 'S__9764870.jpg', 'uploads/681e08382e8382.13230659.jpg', 66),
(37, 'S__9764870.jpg', 'uploads/681e0a4aa13171.83662066.jpg', 73),
(38, 'S__9764870.jpg', 'uploads/681e0a6bb66216.78288731.jpg', 74),
(39, 'S__9764872.jpg', 'uploads/681e0a9a2e77a4.66277482.jpg', 75),
(40, 'S__9764870.jpg', 'uploads/6822d50ce136f5.35922575.jpg', 77),
(41, 'S__9764872.jpg', 'uploads/6822d55ac87ea7.81841874.jpg', 78);

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
(8, '校方已回應', 42, '2025-04-25 00:42:28'),
(9, '校方已回應', 41, '2025-04-26 19:57:49'),
(10, '校方已回應', 50, '2025-04-26 20:04:52'),
(11, '校方已回應', 53, '2025-04-26 20:40:12'),
(12, '校方已回應', 58, '2025-04-26 20:40:29'),
(13, '附議達標', 52, '2025-04-29 16:16:56'),
(14, '校方已回應', 52, '2025-04-29 16:19:28'),
(15, '附議達標', 55, '2025-04-29 18:56:48'),
(16, '附議達標', 60, '2025-05-07 17:59:54'),
(17, '附議達標', 46, '2025-05-09 22:10:05'),
(18, '附議達標', 70, '2025-05-09 22:14:18'),
(19, '附議達標', 66, '2025-05-09 22:14:29'),
(20, '附議達標', 26, '2025-05-09 22:15:34'),
(21, '附議達標', 69, '2025-05-09 22:17:32'),
(22, '附議達標', 67, '2025-05-09 22:19:38'),
(23, '附議達標', 65, '2025-05-09 22:32:22');

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
(1, 333333, 60),
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
(69, 413010021, 58),
(70, 412402012, 52),
(71, 333333, 55),
(72, 333333, 46),
(73, 412402141, 60),
(74, 409100001, 66),
(75, 409100001, 65),
(76, 409100001, 70),
(77, 409100001, 68),
(78, 409100001, 72),
(79, 412301092, 73),
(80, 412301092, 67),
(81, 412301092, 26),
(82, 412301092, 65),
(83, 412301092, 69),
(84, 412301092, 66),
(85, 412301092, 70),
(86, 412301092, 71),
(87, 412301092, 46),
(88, 412301092, 74),
(89, 512020220, 69),
(90, 512020220, 70),
(91, 512020220, 66),
(92, 512020220, 71),
(93, 512020220, 67),
(94, 512020220, 73),
(95, 512020220, 26),
(96, 333333, 69),
(97, 333333, 67),
(98, 412402141, 65),
(99, 412303291, 77);

-- --------------------------------------------------------

--
-- 資料表結構 `announcement`
--

CREATE TABLE `announcement` (
  `announcement_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` varchar(255) NOT NULL,
  `category` enum('建言','募資','系統') NOT NULL,
  `update_at` datetime NOT NULL DEFAULT current_timestamp(),
  `file_path` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `announcement`
--

INSERT INTO `announcement` (`announcement_id`, `title`, `content`, `category`, `update_at`, `file_path`, `user_id`) VALUES
(5, '122', '1212', '募資', '2025-05-13 14:33:36', 'file_upload/6822e7c01ddab_系統分析與設計.pdf', 1111),
(6, '知識', 'fff', '建言', '2025-05-13 15:17:33', 'file_upload/6822f20dd3fa3_公設檢查表.pdf', 345678),
(7, '系統維修(5/23-5/29)', '親愛的同學您好，請注意這段時間請勿登入系統，謝謝配合', '系統', '2025-05-14 18:45:30', 'file_upload/6824744a98a10_1747218631_公設檢查表.pdf', 1111),
(8, '處所請注意!請勿發布金額過高的募資', '近來發現處所提案的募資金額過高，請注意是否需要的真實量', '募資', '2025-05-14 18:47:15', 'file_upload/682474b3c6849_輔系公告 (1).pdf', 1111);

-- --------------------------------------------------------

--
-- 資料表結構 `collection`
--

CREATE TABLE `collection` (
  `collection_id` int(11) NOT NULL,
  `advice_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `collection_created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `collection`
--

INSERT INTO `collection` (`collection_id`, `advice_id`, `user_id`, `collection_created_at`) VALUES
(2, 58, 333333, '2025-05-03 23:45:45'),
(3, 66, 409100001, '2025-05-09 21:54:47'),
(4, 65, 412301092, '2025-05-09 22:04:03'),
(5, 65, 412402141, '2025-05-09 22:32:26'),
(6, 77, 412303291, '2025-05-13 13:15:37');

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
(73, 48, 412402141, 'fknefnwof', '2025-04-22'),
(74, 52, 904, '1111', '2025-04-29'),
(75, 61, 345678, '耶', '2025-05-06');

-- --------------------------------------------------------

--
-- 資料表結構 `donation_record`
--

CREATE TABLE `donation_record` (
  `donation_id` int(11) NOT NULL,
  `donor` varchar(255) DEFAULT '匿名',
  `project_id` int(11) NOT NULL,
  `donation_amount` decimal(10,2) DEFAULT NULL,
  `donation_time` datetime DEFAULT current_timestamp(),
  `email` text DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `donation_record`
--

INSERT INTO `donation_record` (`donation_id`, `donor`, `project_id`, `donation_amount`, `donation_time`, `email`, `user_id`) VALUES
(1, '王大明', 1, 1000.00, '2025-04-24 23:06:17', '409100001@gmail.com', 0),
(2, '王安石', 1, 500.00, '2025-04-26 19:31:52', 'abc@gmail.com', 0),
(3, '王鍾', 3, 500.00, '2025-04-26 19:39:28', 'abc@gmail.com', 0),
(4, '匿名', 2, 500.00, '2025-04-26 20:21:27', '', 0),
(5, '匿名', 2, 500.00, '2025-04-26 20:32:06', '', 0),
(6, '匿名', 2, 1000.00, '2025-04-29 13:44:22', '', 0),
(7, '匿名', 3, 5000.00, '2025-04-29 16:30:20', '', 0),
(8, '匿名', 3, 1000.00, '2025-05-06 14:04:25', '', NULL),
(9, '匿名', 3, 500.00, '2025-05-06 14:05:57', '', NULL),
(10, '匿名', 6, 500.00, '2025-05-06 14:11:21', '', NULL),
(11, '匿名', 6, 1000.00, '2025-05-06 14:12:38', '', NULL),
(12, '匿名', 6, 1000.00, '2025-05-06 14:14:08', '', NULL),
(13, '匿名', 6, 2000.00, '2025-05-06 14:18:12', '', NULL),
(14, '匿名', 6, 500.00, '2025-05-06 14:18:30', '', NULL),
(15, '匿名', 3, 1000.00, '2025-05-06 14:18:57', '', NULL),
(16, '匿名', 3, 100.00, '2025-05-06 14:46:19', '', 904),
(17, '匿名', 3, 100.00, '2025-05-13 15:24:41', '', 345678),
(18, '匿名', 9, 1000.00, '2025-05-14 18:35:47', '', 1111),
(19, '匿名', 5, 2000.00, '2025-05-14 18:35:58', '', 1111);

-- --------------------------------------------------------

--
-- 資料表結構 `execution_report`
--

CREATE TABLE `execution_report` (
  `execution_report_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `updated_time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `execution_report`
--

INSERT INTO `execution_report` (`execution_report_id`, `project_id`, `title`, `content`, `file_path`, `updated_time`) VALUES
(3, 2, '0504', '測試', '', '2025-05-04 11:04:57'),
(4, 2, '12212', '11111', '', '2025-05-04 11:04:57'),
(5, 2, '1212', '1212', 'file_upload/680a297b72d683.04934517.pdf', '2025-05-04 12:31:12'),
(6, 2, '11', '11111', 'file_upload/680a297b72d683.04934517.pdf', '2025-05-04 12:31:12'),
(7, 3, '知識', 'dsdsd', '', '2025-05-13 15:18:28'),
(8, 3, '知識', 'dsdsd', '', '2025-05-13 15:18:35'),
(9, 3, 'sss', 'sss', '', '2025-05-13 15:22:08'),
(10, 3, 'sss', 'sss', '', '2025-05-13 15:23:36'),
(11, 3, 'cdjcodoj', 'vsijodvjsojvj', '', '2025-05-13 15:23:45'),
(12, 3, 'fojsfjooojf', 'fojjgjoajogojg', '', '2025-05-13 15:27:00');

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
(4, '選課類別變動申請書.pdf', 'file_upload/680a297b72d683.04934517.pdf', 56),
(5, '輔系公告.pdf', 'file_upload/68108aa9461d27.10873957.pdf', 61),
(6, '選課類別變動申請書.pdf', 'file_upload/681e09131b8e24.27015869.pdf', 72);

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
(11, 4, 345678, '我遇到好多次，怪怪的人', '2025-04-24 23:53:47'),
(15, 2, 333333, 'sojgg', '2025-04-29 18:52:24'),
(16, 1, 333333, 'sgroggj', '2025-04-29 18:52:38'),
(17, 6, 333333, 'gsrogjsgoojg', '2025-04-29 18:53:00'),
(18, 6, 333333, '我捐了', '2025-05-06 14:29:28'),
(19, 3, 333333, '我捐了', '2025-05-06 14:29:42');

-- --------------------------------------------------------

--
-- 資料表結構 `funding_faq`
--

CREATE TABLE `funding_faq` (
  `funding_FAQ_id` int(15) NOT NULL,
  `project_id` int(15) NOT NULL,
  `user_id` int(11) NOT NULL,
  `question` varchar(100) NOT NULL,
  `reply` text NOT NULL,
  `updated_on` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `funding_faq`
--

INSERT INTO `funding_faq` (`funding_FAQ_id`, `project_id`, `user_id`, `question`, `reply`, `updated_on`) VALUES
(3, 5, 332478, '111', '111', '2025-04-28 21:18:53'),
(4, 5, 332478, '133', '333', '2025-04-26 22:17:18'),
(6, 5, 345678, '沒捐過血可以嗎?', '可以哦!一般來說，只要符合捐血條件（像年齡、體重、健康狀況OK），沒捐過血的人也可以第一次去捐血喔！', '2025-04-26 22:39:25'),
(7, 5, 345678, '該拔牙可以捐血嗎?', '如果你剛拔牙，暫時不能捐血喔！\r\n\r\n一般來說，拔牙後要暫緩捐血 7 天以上（有些地方規定是 7～14 天）', '2025-04-26 22:35:26');

-- --------------------------------------------------------

--
-- 資料表結構 `fundraising_extension_requests`
--

CREATE TABLE `fundraising_extension_requests` (
  `id` int(11) NOT NULL,
  `fundraising_project_id` int(11) NOT NULL,
  `requested_by_office_id` int(11) NOT NULL,
  `requested_extension_date` datetime NOT NULL,
  `status` enum('待審核','已接受','已拒絕') DEFAULT '待審核',
  `admin_response` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `reviewed_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `fundraising_extension_requests`
--

INSERT INTO `fundraising_extension_requests` (`id`, `fundraising_project_id`, `requested_by_office_id`, `requested_extension_date`, `status`, `admin_response`, `created_at`, `reviewed_at`) VALUES
(10, 1, 904, '2025-04-27 00:00:00', '已接受', '好', '2025-04-25 20:53:46', NULL),
(12, 6, 345678, '2025-05-10 00:00:00', '已接受', 'ok\r\n', '2025-04-29 16:40:06', NULL);

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
(1, 3, '綠色校園', '購買校園消臭劑置放在校園內', 9000, '2025-04-22 21:40:49', '2025-04-27 00:00:00', '進行中'),
(2, 6, '廁所施工聲音好吵', '我們會盡力監督', 10, '2025-04-24 21:29:40', '2025-04-28 20:58:32', '進行中'),
(3, 5, '社團博覽會', '幫助學生在社團活動發光發熱', 8000, '2025-04-24 21:43:54', '2025-05-24 20:58:32', '已完成'),
(4, 7, '輔仁醫院附近有變態', '防狼噴霧普發1000瓶', 20000, '2025-04-24 22:15:43', '2025-04-25 17:42:29', '進行中'),
(5, 8, '捐血救人', '用於招募志工與工讀金的費用', 35000, '2025-04-24 22:17:25', '2025-05-24 20:58:32', '進行中'),
(6, 10, '舉辦下一次的社團展覽', '幫助社團募資', 25000, '2025-04-24 22:22:11', '2025-05-10 00:00:00', '進行中'),
(9, 23, 'zjgojrzjjrg', '舉辦南非參訪大使團六天五夜', 65000, '2025-05-14 18:32:23', '2025-06-13 18:32:23', '進行中');

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
(4, 5, 345678, '我們已提交提案了!', '2025-04-24 21:43:30'),
(5, 7, 345678, '同學你好，我們會加強那邊的安全', '2025-04-24 22:13:59'),
(6, 8, 345678, '同學您好，我們會幫助你們', '2025-04-24 22:18:01'),
(8, 7, 345678, '同學請注意安全', '2025-04-25 00:32:42'),
(9, 7, 345678, '同學請注意安全', '2025-04-25 00:39:43'),
(10, 5, 345678, '歡迎同學在下方多留言', '2025-04-25 00:42:28'),
(11, 5, 345678, '歡迎同學', '2025-04-25 00:42:38'),
(12, 3, 904, '同學，我們正在籌備了', '2025-04-26 19:57:49'),
(13, 3, 904, '同學，我們正在籌備了', '2025-04-26 19:58:06'),
(17, 6, 904, '好的同學', '2025-04-26 20:11:57'),
(18, 6, 904, '我們有在督促了，施工大哥說會盡量', '2025-04-26 20:13:15'),
(19, 6, 904, '好的同學，加油!', '2025-04-26 20:15:25'),
(20, 3, 904, '我們正在籌備!', '2025-04-26 20:39:09'),
(21, 5, 345678, '正在籌備!', '2025-04-26 20:39:49'),
(22, 7, 345678, '我們會積極處理!', '2025-04-26 20:40:01'),
(23, 8, 345678, '我們會積極處理', '2025-04-26 20:40:12'),
(24, 10, 345678, '我們會積極處理!放心同學', '2025-04-26 20:40:29'),
(25, 6, 904, '測試123', '2025-04-28 23:19:13'),
(26, 12, 345678, '好的同學，我們正在積極處理', '2025-04-29 16:19:28');

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
(3, 41, 904, '購買校園消臭劑置放在校園內', 9000, 'uploads/chicken.png', 1, 1, '可以，希望有效', '已通過', '2025-04-22 21:37:32', '2025-04-22 21:40:49', 0),
(4, 44, 123, '文化季經費:食材費', 12000, 'uploads/附件一113學年度(學士班)輔系開班一覽表 (1).pdf', 1, 0, '你們要買甚麼?請寫好', '被退回', '2025-04-22 21:46:56', '2025-04-24 21:18:08', 0),
(5, 42, 345678, '幫助學生在社團活動發光發熱', 8000, 'uploads/動漫社同學.jpg', 1, 1, '不錯喔，祝順利', '已通過', '2025-04-24 21:43:02', '2025-04-24 21:43:54', 0),
(6, 50, 904, '我們會盡力監督', 0, 'uploads/廁所.jpg', 1, 1, '加油!', '已通過', '2025-04-24 21:10:31', '2025-04-24 21:29:40', 0),
(7, 51, 345678, '防狼噴霧普發1000瓶', 20000, 'uploads/醫院.jpg', 1, 1, '好的，祝順利', '已通過', '2025-04-24 22:15:17', '2025-04-24 22:15:43', 0),
(8, 53, 345678, '用於招募志工與工讀金的費用', 35000, 'uploads/捐血.jpg', 1, 1, '不錯喔', '已通過', '2025-04-24 22:17:05', '2025-04-24 22:17:25', 0),
(9, 56, 905, NULL, NULL, NULL, 0, NULL, NULL, '草擬中', NULL, NULL, 0),
(10, 58, 345678, '幫助社團募資', 25000, 'uploads/社團活動.jpg', 1, 1, '加油', '已通過', '2025-04-24 22:21:57', '2025-04-24 22:22:11', 0),
(11, 24, 345678, 'fjafoif', 2000, 'uploads/soap球.png', 1, 1, '', '已通過', '2025-04-29 16:42:12', '2025-04-29 16:46:20', 0),
(12, 52, 345678, 'fijoapofjiog', 3000, 'uploads/輔系公告 (1).pdf', 1, 1, 'ok', '已通過', '2025-04-29 16:24:48', '2025-04-29 16:25:04', 0),
(13, 57, 123, NULL, NULL, NULL, 0, NULL, NULL, '草擬中', NULL, NULL, 0),
(14, 54, 345678, 'cl3jfofjfjoof', 2000, 'uploads/1745924654_c01.png', 1, 0, '', '被退回', '2025-04-29 19:04:14', '2025-05-13 13:57:46', 0),
(15, 55, 904, NULL, NULL, NULL, 0, NULL, NULL, '草擬中', NULL, NULL, 0),
(16, 60, 345678, '校慶燈光秀創意提案：打造光影展演，結合投影技術與學生創意。', 12000, 'uploads/1747113507_公設檢查表.pdf', 1, NULL, NULL, '審核中', '2025-05-13 13:18:27', NULL, 0),
(20, 26, 904, NULL, NULL, NULL, 0, NULL, NULL, '草擬中', NULL, NULL, 0),
(21, 45, 904, NULL, NULL, NULL, 0, NULL, NULL, '草擬中', NULL, NULL, 0),
(22, 69, 345678, NULL, NULL, NULL, 0, NULL, NULL, '草擬中', NULL, NULL, 0),
(23, 46, 909, '舉辦南非參訪大使團六天五夜', 65000, 'uploads/1747218631_公設檢查表.pdf', 1, 1, '期待你們的表現!', '已通過', '2025-05-14 18:30:31', '2025-05-14 18:32:23', 1);

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
(1111, '1111', '系統管理者', 'manager', 'pubwww@mail.fju.edu.tw', '孵仁管理員'),
(2222, '2222', '王安石', 'office', 'pubwww@mail.fju.edu.tw', '總務處'),
(332478, '332478', '安教授', 'teacher', '332478@m365.fju.edu.tw', '資訊管理學系'),
(333333, '333333', '王傑明', 'teacher', 'abcd@gmail.com', '外國語文學院'),
(345678, '345678', '歐陽修', 'office', '123@gmail.com', '學務處'),
(409100001, '409100001', '王靜', 'student', '409100001@gmail.com', '外國語文學院'),
(410101001, '410101001', '王安', 'student', 'abc@gmail.com', '傳播學院'),
(411100011, '411100011', '王傑明', 'student', 'abc@gmail.com', '文學院'),
(412301092, '412301092', '412301092', 'student', 'abc@gmail.com', '傳播學院'),
(412303291, '412303291', 'cc', 'student', 'abc@gmail.com', '文學院'),
(412380012, '444aa', '吉伊卡娃', 'student', 'a@gmail.com', '藝術學院'),
(412401011, '412401011', '王同學', 'student', 'abc@gmail.com', '藝術學院'),
(412402001, '412402001', '王小明', 'student', 'example@gmail.com', '資訊管理學系'),
(412402002, 'sss', '吉伊卡娃二號', 'student', 'abc@gmail.com', '文學院'),
(412402012, '012', '郭同學', 'student', 'sss@gmail.com', '管理學院'),
(412402141, 'sss', ':0', 'student', 'abcd@gmail.com', '管理學院'),
(413010021, '413010021', '王靜', 'student', '1@gmail.com', '醫學院'),
(512020220, '512020220', '哀人', 'student', 'abc@gmail.com', '管理學院');

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
-- 資料表索引 `announcement`
--
ALTER TABLE `announcement`
  ADD PRIMARY KEY (`announcement_id`),
  ADD KEY `user_id` (`user_id`) USING BTREE;

--
-- 資料表索引 `collection`
--
ALTER TABLE `collection`
  ADD PRIMARY KEY (`collection_id`),
  ADD KEY `advice_id` (`advice_id`),
  ADD KEY `user_id` (`user_id`);

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
  ADD KEY `project_id` (`project_id`),
  ADD KEY `user_id` (`user_id`);

--
-- 資料表索引 `execution_report`
--
ALTER TABLE `execution_report`
  ADD PRIMARY KEY (`execution_report_id`),
  ADD KEY `fundraising_projects` (`project_id`);

--
-- 資料表索引 `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`file_id`),
  ADD KEY `advice_id` (`advice_id`);

--
-- 資料表索引 `funding_comments`
--
ALTER TABLE `funding_comments`
  ADD PRIMARY KEY (`funding_comments_id`),
  ADD KEY `project_id` (`project_id`),
  ADD KEY `user_id` (`user_id`);

--
-- 資料表索引 `funding_faq`
--
ALTER TABLE `funding_faq`
  ADD PRIMARY KEY (`funding_FAQ_id`),
  ADD KEY `project_id` (`project_id`),
  ADD KEY `user_id` (`user_id`);

--
-- 資料表索引 `fundraising_extension_requests`
--
ALTER TABLE `fundraising_extension_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fundraising_project_id` (`fundraising_project_id`),
  ADD KEY `requested_by_office_id` (`requested_by_office_id`);

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
  ADD PRIMARY KEY (`suggestion_assignments_id`);

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
  MODIFY `advice_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `advice_image`
--
ALTER TABLE `advice_image`
  MODIFY `img_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `advice_state`
--
ALTER TABLE `advice_state`
  MODIFY `advice_state_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `agree_record`
--
ALTER TABLE `agree_record`
  MODIFY `agree_record_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `announcement`
--
ALTER TABLE `announcement`
  MODIFY `announcement_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `collection`
--
ALTER TABLE `collection`
  MODIFY `collection_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `donation_record`
--
ALTER TABLE `donation_record`
  MODIFY `donation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `execution_report`
--
ALTER TABLE `execution_report`
  MODIFY `execution_report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `files`
--
ALTER TABLE `files`
  MODIFY `file_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `funding_comments`
--
ALTER TABLE `funding_comments`
  MODIFY `funding_comments_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `funding_faq`
--
ALTER TABLE `funding_faq`
  MODIFY `funding_FAQ_id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `fundraising_extension_requests`
--
ALTER TABLE `fundraising_extension_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `fundraising_projects`
--
ALTER TABLE `fundraising_projects`
  MODIFY `project_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `replies`
--
ALTER TABLE `replies`
  MODIFY `reply_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `suggestion_assignments`
--
ALTER TABLE `suggestion_assignments`
  MODIFY `suggestion_assignments_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- 已傾印資料表的限制式
--

--
-- 資料表的限制式 `collection`
--
ALTER TABLE `collection`
  ADD CONSTRAINT `collection_ibfk_1` FOREIGN KEY (`advice_id`) REFERENCES `advice` (`advice_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `collection_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 4.9.11
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 05, 2023 at 07:33 AM
-- Server version: 5.7.23-23
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vitalpbx_wp236`
--

-- --------------------------------------------------------

--
-- Table structure for table `chatbot`
--

CREATE TABLE `chatbot` (
  `uniqued_id` bigint(20) UNSIGNED NOT NULL,
  `chatid` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `msg_type` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `receive_msg` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `send_msg` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `msg_url` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `msg_latitude` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `msg_longitude` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `msg_name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `msg_address` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chatbot`
--

INSERT INTO `chatbot` (`uniqued_id`, `chatid`, `msg_type`, `receive_msg`, `send_msg`, `msg_url`, `msg_latitude`, `msg_longitude`, `msg_name`, `msg_address`) VALUES
(1, '1', 'text', 'Menu', '🚀 Hi, visit our website vitalpbx.com for more information..\r\n\r\n📌Please enter a number #️⃣ to receive information.\r\n\r\n1️⃣. Information from VitalPBX. ❔\r\n2️⃣. VitalPBX address. 📍\r\n3️⃣. VitalPBX Brochure pdf. 📄\r\n4️⃣. Favorite music. 🎧\r\n5️⃣. Introduction Video. ⏯️\r\n6️⃣. Contact information. 🙋‍♂️\r\n7️⃣. Attention hours. 🕜\r\n\r\nOr write a question such as: What is VitalPBX?\r\nAnd ChatGPT will answer you.', '', '', '', '', ''),
(2, '1', 'text', '1', 'VitalPBX is an enterprise-oriented, software-based unified communications system. It is built on the Asterisk platform, one of the most popular and robust telephony systems on the market. VitalPBX offers a wide variety of tools to manage and administer a complete telephony system, from the use of IP phones to the configuration of a voice network. VitalPBX is designed to offer a secure, flexible, scalable and easy-to-use environment so that users can enjoy a superior quality communications experience.', '', '', '', '', ''),
(3, '1', 'location', '2', '', '', '25.7954716164558', '-80.3308981174941', 'VitalPBX LLC', '2292 NW 82nd Ave HB 002998 Miami, Florida 33198'),
(4, '1', 'text_url', '3', 'https://downloads.vitalpbx.com/brochures/VITALPBXOVERVIEW_EN.pdf', '', '', '', '', ''),
(5, '1', 'text_url', '4', 'https://www.youtube.com/watch?v=VcjzHMhBtf0', '', '', '', '', ''),
(6, '1', 'text_url', '5', 'https://www.youtube.com/watch?v=TIlWzgWGHEg', '', '', '', '', ''),
(7, '1', 'text', '6', 'VitalPBX LLC\r\n2292 NW 82nd Ave HB 002998 \r\nMiami, Florida 33198\r\n\r\nPhone: +1(305) 560-5776\r\n\r\nEmail: sales@vitalpbx.com', '', '', '', '', ''),
(8, '1', 'text', '7', 'Hour of attention\r\nMonday to Friday from 8:00 AM to 5:00 PM\r\nSaturday from 8:00 AM to 1:00 PM\r\nGMT -6', '', '', '', '', ''),
(9, '1', 'text', 'menu', '🚀 Hi, visit our website vitalpbx.com for more information..\r\n\r\n📌Please enter a number #️⃣ to receive information.\r\n\r\n1️⃣. Information from VitalPBX. ❔\r\n2️⃣. VitalPBX address. 📍\r\n3️⃣. VitalPBX Brochure pdf. 📄\r\n4️⃣. Favorite music. 🎧\r\n5️⃣. Introduction Video. ⏯️\r\n6️⃣. Contact information. 🙋‍♂️\r\n7️⃣. Attention hours. 🕜\r\n\r\nOr write a question such as: What is VitalPBX?\r\nAnd ChatGPT will answer you.', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `chatbot_settings`
--

CREATE TABLE `chatbot_settings` (
  `uniqued_id` bigint(20) NOT NULL,
  `chatbotid` varchar(100) NOT NULL,
  `setting_name` varchar(100) NOT NULL,
  `setting_value` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `chatbot_settings`
--

INSERT INTO `chatbot_settings` (`uniqued_id`, `chatbotid`, `setting_name`, `setting_value`) VALUES
(1, '1', 'default_text', 'Menu'),
(2, '', 'default_chatbot', '1'),
(3, '1', 'default_chatgpt_text', 'chatgpt:'),
(4, '1', 'default_chatgpt', 'yes');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chatbot`
--
ALTER TABLE `chatbot`
  ADD PRIMARY KEY (`uniqued_id`);

--
-- Indexes for table `chatbot_settings`
--
ALTER TABLE `chatbot_settings`
  ADD PRIMARY KEY (`uniqued_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chatbot`
--
ALTER TABLE `chatbot`
  MODIFY `uniqued_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `chatbot_settings`
--
ALTER TABLE `chatbot_settings`
  MODIFY `uniqued_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

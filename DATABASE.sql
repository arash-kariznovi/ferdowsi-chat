-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 11, 2022 at 04:21 PM
-- Server version: 5.7.36-cll-lve
-- PHP Version: 7.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hashtad1_Messenger`
--

-- --------------------------------------------------------

--
-- Table structure for table `GroupConnection`
--

CREATE TABLE `GroupConnection` (
  `id` int(11) NOT NULL,
  `userId` bigint(20) NOT NULL,
  `chatId` bigint(20) NOT NULL,
  `isChannel` tinyint(1) NOT NULL DEFAULT '0',
  `isAdmin` tinyint(1) NOT NULL DEFAULT '0',
  `limits` text,
  `leftChat` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `GroupConnection`
--

INSERT INTO `GroupConnection` (`id`, `userId`, `chatId`, `isChannel`, `isAdmin`, `limits`, `leftChat`) VALUES
(1, 1, 1, 0, 1, NULL, 0),
(2, 3, 1, 0, 1, NULL, 0),
(3, 4, 1, 0, 0, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `Groups`
--

CREATE TABLE `Groups` (
  `id` bigint(20) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `groupName` varchar(150) NOT NULL,
  `bio` text,
  `avatarAddress` varchar(50) DEFAULT NULL,
  `chatHistory` tinyint(1) NOT NULL DEFAULT '1',
  `members` int(4) NOT NULL DEFAULT '1',
  `lastAction` datetime NOT NULL,
  `sendMessage` tinyint(1) NOT NULL DEFAULT '1',
  `sendFile` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `Groups`
--

INSERT INTO `Groups` (`id`, `username`, `groupName`, `bio`, `avatarAddress`, `chatHistory`, `members`, `lastAction`, `sendMessage`, `sendFile`) VALUES
(1, 'testgroup', 'Test Group', 'It\'s a test group', NULL, 1, 1, '2022-01-11 13:29:03', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `Messages`
--

CREATE TABLE `Messages` (
  `id` bigint(20) NOT NULL,
  `caption` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `file` varchar(150) DEFAULT '0',
  `fileType` varchar(50) DEFAULT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `fromId` bigint(20) NOT NULL,
  `toId` bigint(20) DEFAULT NULL,
  `groupId` bigint(20) DEFAULT NULL,
  `replyTo` bigint(20) DEFAULT NULL,
  `forwardedMessage` bigint(20) DEFAULT NULL,
  `seen` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `Messages`
--

CREATE TABLE `User` (
  `id` bigint(20) NOT NULL,
  `username` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  `firstName` varchar(150) NOT NULL,
  `lastName` varchar(150) DEFAULT NULL,
  `joinedAt` date NOT NULL,
  `picName` varchar(150) CHARACTER SET latin1 DEFAULT NULL,
  `studentId` bigint(14) DEFAULT NULL,
  `password` varchar(150) CHARACTER SET latin1 NOT NULL,
  `lastOnline` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `User`
--

INSERT INTO `User` (`id`, `username`, `firstName`, `lastName`, `joinedAt`, `picName`, `studentId`, `password`, `lastOnline`) VALUES
(1, 'administrator', 'Admin', 'Strator', '2021-12-21', NULL, 11111111111111, '1111111111', NULL),
(3, 'alirezahakim', 'Alireza', 'Hakim', '2021-12-21', NULL, 11111111111112, '123454321', NULL),
(4, 'mohammadFaghih', 'Mohammad', 'Faghihi', '2021-12-22', NULL, 11111111111113, '123456789', NULL),
(5, 'rezaJavdan', 'Reza', 'Javdan', '2021-12-29', NULL, 11111111111114, '0000000000', NULL),
(6, NULL, 'test', NULL, '2022-01-07', NULL, 11111111111115, '123321123', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `UserConnection`
--

CREATE TABLE `UserConnection` (
  `id` bigint(20) NOT NULL,
  `firstId` bigint(20) NOT NULL,
  `secondId` bigint(20) NOT NULL,
  `lastAction` datetime DEFAULT NULL,
  `Blocked` tinyint(1) NOT NULL DEFAULT '0',
  `notification` tinyint(1) NOT NULL DEFAULT '1',
  `CustomName` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `UserConnection`
--

INSERT INTO `UserConnection` (`id`, `firstId`, `secondId`, `lastAction`, `Blocked`, `notification`, `CustomName`) VALUES
(1, 1, 3, '2022-01-11 13:17:59', 0, 1, NULL),
(2, 3, 1, '2022-01-11 16:00:15', 0, 1, NULL),
(3, 1, 4, '2022-01-10 16:37:22', 0, 1, NULL),
(4, 1, 5, '2022-01-11 16:00:15', 0, 1, NULL),
(5, 4, 1, '2022-01-11 16:00:15', 0, 1, NULL),
(6, 5, 1, '2022-01-11 16:00:15', 0, 1, NULL),
(7, 3, 4, '2022-01-11 13:17:59', 0, 1, NULL),
(8, 4, 3, '2022-01-10 16:37:22', 0, 1, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `GroupConnection`
--
ALTER TABLE `GroupConnection`
  ADD PRIMARY KEY (`id`),
  ADD KEY `groupConnect` (`chatId`),
  ADD KEY `userConnect` (`userId`);

--
-- Indexes for table `Groups`
--
ALTER TABLE `Groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Messages`
--
ALTER TABLE `Messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `receiverId` (`toId`),
  ADD KEY `replyToMessage` (`replyTo`),
  ADD KEY `senderId` (`fromId`),
  ADD KEY `groupConnecter` (`groupId`),
  ADD KEY `forwardedId` (`forwardedMessage`);

--
-- Indexes for table `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `UserConnection`
--
ALTER TABLE `UserConnection`
  ADD PRIMARY KEY (`id`),
  ADD KEY `firstUserConnection` (`firstId`),
  ADD KEY `secondUserConnection` (`secondId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `GroupConnection`
--
ALTER TABLE `GroupConnection`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `Groups`
--
ALTER TABLE `Groups`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `Messages`
--
ALTER TABLE `Messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=214;

--
-- AUTO_INCREMENT for table `User`
--
ALTER TABLE `User`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `UserConnection`
--
ALTER TABLE `UserConnection`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `GroupConnection`
--
ALTER TABLE `GroupConnection`
  ADD CONSTRAINT `groupConnect` FOREIGN KEY (`chatId`) REFERENCES `Groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `userConnect` FOREIGN KEY (`userId`) REFERENCES `User` (`id`);

--
-- Constraints for table `Messages`
--
ALTER TABLE `Messages`
  ADD CONSTRAINT `forwardedId` FOREIGN KEY (`forwardedMessage`) REFERENCES `Messages` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `groupConnecter` FOREIGN KEY (`groupId`) REFERENCES `Groups` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `receiverId` FOREIGN KEY (`toId`) REFERENCES `User` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `replyToMessage` FOREIGN KEY (`replyTo`) REFERENCES `Messages` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `senderId` FOREIGN KEY (`fromId`) REFERENCES `User` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `UserConnection`
--
ALTER TABLE `UserConnection`
  ADD CONSTRAINT `firstUserConnection` FOREIGN KEY (`firstId`) REFERENCES `User` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `secondUserConnection` FOREIGN KEY (`secondId`) REFERENCES `User` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

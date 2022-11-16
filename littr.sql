SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE DATABASE IF NOT EXISTS `littr` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `littr`;

CREATE TABLE `followsystem` (
  `id` int(11) NOT NULL,
  `followed` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `inv` (
  `key_id` int(11) NOT NULL,
  `key_encrypt` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `posts` (
  `statusid` int(11) NOT NULL,
  `identifier` text NOT NULL,
  `content` varchar(300) NOT NULL,
  `media_path` text,
  `born` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `name` varchar(30) NOT NULL,
  `bio` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `born` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login` text NOT NULL,
  `rate_limit` datetime DEFAULT NULL,
  `pfp` text NOT NULL,
  `verified` int(11) DEFAULT NULL,
  `category` text,
  `admin_privileges` int(11) DEFAULT NULL,
  `suspended` int(11) DEFAULT NULL,
  `identifier` varchar(25) NOT NULL,
  `discord` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `_configuration` (
  `_tablename` text NOT NULL,
  `_title` text NOT NULL,
  `_descriptor` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


ALTER TABLE `inv`
  ADD PRIMARY KEY (`key_id`),
  ADD UNIQUE KEY `key_encrypt` (`key_encrypt`);

ALTER TABLE `posts`
  ADD PRIMARY KEY (`statusid`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);


ALTER TABLE `posts`
  MODIFY `statusid` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

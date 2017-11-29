-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Värd: 127.0.0.1
-- Tid vid skapande: 27 nov 2017 kl 22:10
-- Serverversion: 10.1.16-MariaDB
-- PHP-version: 7.0.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databas: `travlendar`
--
CREATE DATABASE IF NOT EXISTS `travlendar` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `travlendar`;

-- --------------------------------------------------------

--
-- Tabellstruktur `bike_mi`
--

CREATE TABLE `bike_mi` (
  `id` int(11) NOT NULL,
  `longitude` tinytext NOT NULL,
  `latitude` tinytext NOT NULL,
  `adress` tinytext NOT NULL,
  `zip` tinytext NOT NULL,
  `availablebikes` tinyint(4) NOT NULL,
  `availableslots` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellstruktur `events`
--

CREATE TABLE `events` (
  `user_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `title` tinytext NOT NULL,
  `description` text NOT NULL,
  `startadress` tinytext NOT NULL,
  `endadress` tinytext NOT NULL,
  `date` date NOT NULL,
  `start` time NOT NULL,
  `end` time NOT NULL,
  `context` enum('Work','Family','Leisure','') NOT NULL,
  `passengers` int(11) NOT NULL DEFAULT '0',
  `journey` text NOT NULL,
  `event_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellstruktur `flexible_breaks`
--

CREATE TABLE `flexible_breaks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `string` text NOT NULL,
  `start` time NOT NULL,
  `end` time NOT NULL,
  `duration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellstruktur `max_distances`
--

CREATE TABLE `max_distances` (
  `user_id` int(11) NOT NULL,
  `max_walk` int(11) NOT NULL,
  `max_bike` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellstruktur `means_of_transports`
--

CREATE TABLE `means_of_transports` (
  `user_id` int(11) NOT NULL,
  `available_walk` tinyint(1) NOT NULL DEFAULT '1',
  `earliest_walk` time NOT NULL DEFAULT '00:00:00',
  `latest_walk` time NOT NULL DEFAULT '24:00:00',
  `available_bike` tinyint(1) NOT NULL DEFAULT '1',
  `earliest_bike` time NOT NULL DEFAULT '00:00:00',
  `latest_bike` time NOT NULL DEFAULT '24:00:00',
  `available_shared_bike` tinyint(1) NOT NULL DEFAULT '1',
  `earliest_shared_bike` time NOT NULL DEFAULT '00:00:00',
  `latest_shared_bike` time NOT NULL DEFAULT '24:00:00',
  `available_shared_car` tinyint(1) NOT NULL DEFAULT '1',
  `earliest_shared_car` time NOT NULL DEFAULT '00:00:00',
  `latest_shared_car` time NOT NULL DEFAULT '24:00:00',
  `available_metro` tinyint(1) NOT NULL DEFAULT '1',
  `earliest_metro` time NOT NULL DEFAULT '00:00:00',
  `latest_metro` time NOT NULL DEFAULT '24:00:00',
  `available_bus` tinyint(1) NOT NULL DEFAULT '1',
  `earliest_bus` time NOT NULL DEFAULT '00:00:00',
  `latest_bus` time NOT NULL DEFAULT '24:00:00',
  `available_train` tinyint(1) NOT NULL DEFAULT '1',
  `earliest_train` time NOT NULL DEFAULT '00:00:00',
  `latest_train` time NOT NULL DEFAULT '24:00:00',
  `available_tram` tinyint(1) NOT NULL DEFAULT '1',
  `earliest_tram` time NOT NULL DEFAULT '00:00:00',
  `latest_tram` time NOT NULL DEFAULT '24:00:00',
  `available_taxi` tinyint(1) NOT NULL DEFAULT '1',
  `earliest_taxi` time NOT NULL DEFAULT '00:00:00',
  `latest_taxi` time NOT NULL DEFAULT '24:00:00',
  `available_car` tinyint(1) NOT NULL DEFAULT '1',
  `earliest_car` time NOT NULL DEFAULT '00:00:00',
  `latest_car` time NOT NULL DEFAULT '24:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellstruktur `public_transports`
--

CREATE TABLE `public_transports` (
  `user_id` int(11) NOT NULL,
  `free_period_metro` tinyint(1) NOT NULL DEFAULT '0',
  `free_period_metro_start` date DEFAULT NULL,
  `free_period_metro_end` date DEFAULT NULL,
  `free_period_bus` tinyint(1) NOT NULL DEFAULT '0',
  `free_period_bus_start` date DEFAULT NULL,
  `free_period_bus_end` date DEFAULT NULL,
  `free_period_train` tinyint(1) NOT NULL DEFAULT '0',
  `free_period_train_start` date DEFAULT NULL,
  `free_period_train_end` date DEFAULT NULL,
  `free_period_shared_bike` tinyint(1) NOT NULL DEFAULT '0',
  `free_period_shared_bike_start` date DEFAULT NULL,
  `free_period_shared_bike_end` date DEFAULT NULL,
  `free_period_shared_car` tinyint(1) NOT NULL DEFAULT '0',
  `free_period_shared_car_start` date DEFAULT NULL,
  `free_period_shared_car_end` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellstruktur `relevances`
--

CREATE TABLE `relevances` (
  `user_id` int(11) NOT NULL,
  `price` enum('none','low','medium','high') NOT NULL DEFAULT 'none',
  `carbon` enum('none','low','medium','high') NOT NULL DEFAULT 'none',
  `dryness` enum('none','low','medium','high') NOT NULL DEFAULT 'none',
  `speed` enum('none','low','medium','high') NOT NULL DEFAULT 'none'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellstruktur `strike_datas`
--

CREATE TABLE `strike_datas` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `means_of_transport` enum('Metro','Bus','Train','Tram') NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellstruktur `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` tinytext CHARACTER SET latin1 NOT NULL,
  `password` tinytext CHARACTER SET latin1 NOT NULL,
  `home` tinytext CHARACTER SET latin1 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Trigger `users`
--
DELIMITER $$
CREATE TRIGGER `delete_user` BEFORE DELETE ON `users` FOR EACH ROW BEGIN
	DELETE FROM `max_distances` WHERE `user_id` = OLD.id;
    DELETE FROM `relevances` WHERE `user_id` = OLD.id;
    DELETE FROM `means_of_transports` WHERE `user_id` = OLD.id;
    DELETE FROM `public_transports` WHERE `user_id` = OLD.id;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `new_user` AFTER INSERT ON `users` FOR EACH ROW BEGIN
	INSERT INTO `max_distances`(`user_id`,`max_walk`,`max_bike`) VALUES (NEW.id, 1000, 1000);
    INSERT INTO `relevances`(`user_id`) VALUES (NEW.id);
    INSERT INTO `means_of_transports`(`user_id`) VALUES (NEW.id);
    INSERT INTO `public_transports`(`user_id`) VALUES (NEW.id);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellstruktur `weather_datas`
--

CREATE TABLE `weather_datas` (
  `zip` tinyint(4) NOT NULL,
  `time` time NOT NULL,
  `latest_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `rain` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Index för dumpade tabeller
--

--
-- Index för tabell `bike_mi`
--
ALTER TABLE `bike_mi`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Index för tabell `flexible_breaks`
--
ALTER TABLE `flexible_breaks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Index för tabell `max_distances`
--
ALTER TABLE `max_distances`
  ADD PRIMARY KEY (`user_id`);

--
-- Index för tabell `means_of_transports`
--
ALTER TABLE `means_of_transports`
  ADD PRIMARY KEY (`user_id`);

--
-- Index för tabell `public_transports`
--
ALTER TABLE `public_transports`
  ADD PRIMARY KEY (`user_id`);

--
-- Index för tabell `relevances`
--
ALTER TABLE `relevances`
  ADD PRIMARY KEY (`user_id`);

--
-- Index för tabell `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `username` (`username`(20)),
  ADD KEY `id_2` (`id`);

--
-- Index för tabell `weather_datas`
--
ALTER TABLE `weather_datas`
  ADD PRIMARY KEY (`zip`);

--
-- AUTO_INCREMENT för dumpade tabeller
--

--
-- AUTO_INCREMENT för tabell `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT för tabell `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

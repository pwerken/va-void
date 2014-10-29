-- phpMyAdmin SQL Dump
-- version 4.2.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 26, 2014 at 10:45 AM
-- Server version: 5.5.39-1
-- PHP Version: 5.6.2-1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `void-api`
--
CREATE DATABASE IF NOT EXISTS `void-api` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `void-api`;

-- --------------------------------------------------------

--
-- Table structure for table `attributes`
--

DROP TABLE IF EXISTS `attributes`;
CREATE TABLE IF NOT EXISTS `attributes` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lorType` text COLLATE utf8_unicode_ci,
  `code` varchar(2) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attributes_items`
--

DROP TABLE IF EXISTS `attributes_items`;
CREATE TABLE IF NOT EXISTS `attributes_items` (
  `attribute_id` int(10) unsigned NOT NULL,
  `item_id` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `believes`
--

DROP TABLE IF EXISTS `believes`;
CREATE TABLE IF NOT EXISTS `believes` (
`id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=95 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `characters`
--

DROP TABLE IF EXISTS `characters`;
CREATE TABLE IF NOT EXISTS `characters` (
`id` int(10) unsigned NOT NULL,
  `player_id` int(10) unsigned NOT NULL COMMENT 'PLIN',
  `chin` int(2) unsigned NOT NULL DEFAULT '1',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `xp` decimal(4,1) unsigned NOT NULL DEFAULT '15.0',
  `faction_id` int(10) unsigned NOT NULL,
  `belief_id` int(10) unsigned NOT NULL,
  `group_id` int(10) unsigned NOT NULL,
  `world_id` int(10) unsigned NOT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comments` text COLLATE utf8_unicode_ci,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4059 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `characters_conditions`
--

DROP TABLE IF EXISTS `characters_conditions`;
CREATE TABLE IF NOT EXISTS `characters_conditions` (
  `character_id` int(10) unsigned NOT NULL,
  `condition_id` int(10) unsigned NOT NULL,
  `expiry` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `characters_powers`
--

DROP TABLE IF EXISTS `characters_powers`;
CREATE TABLE IF NOT EXISTS `characters_powers` (
  `character_id` int(10) unsigned NOT NULL,
  `power_id` int(10) unsigned NOT NULL,
  `expiry` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `characters_skills`
--

DROP TABLE IF EXISTS `characters_skills`;
CREATE TABLE IF NOT EXISTS `characters_skills` (
  `character_id` int(10) unsigned NOT NULL,
  `skill_id` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `characters_spells`
--

DROP TABLE IF EXISTS `characters_spells`;
CREATE TABLE IF NOT EXISTS `characters_spells` (
  `character_id` int(10) unsigned NOT NULL,
  `spell_id` int(10) unsigned NOT NULL,
  `level` int(10) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `conditions`
--

DROP TABLE IF EXISTS `conditions`;
CREATE TABLE IF NOT EXISTS `conditions` (
  `id` int(10) unsigned NOT NULL COMMENT 'COIN',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `player_text` text COLLATE utf8_unicode_ci NOT NULL,
  `cs_text` text COLLATE utf8_unicode_ci,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `factions`
--

DROP TABLE IF EXISTS `factions`;
CREATE TABLE IF NOT EXISTS `factions` (
`id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
CREATE TABLE IF NOT EXISTS `groups` (
`id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=359 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

DROP TABLE IF EXISTS `items`;
CREATE TABLE IF NOT EXISTS `items` (
  `id` int(10) unsigned NOT NULL COMMENT 'ITIN',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `dscription` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `player_text` text COLLATE utf8_unicode_ci,
  `cs_text` text COLLATE utf8_unicode_ci,
  `character_id` int(10) unsigned DEFAULT NULL,
  `expiry` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `manatypes`
--

DROP TABLE IF EXISTS `manatypes`;
CREATE TABLE IF NOT EXISTS `manatypes` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `players`
--

DROP TABLE IF EXISTS `players`;
CREATE TABLE IF NOT EXISTS `players` (
  `id` int(10) unsigned NOT NULL COMMENT 'PLIN',
  `account_type` enum('Participant','Referee','Infobalie','Super') CHARACTER SET utf8 NOT NULL DEFAULT 'Participant' COMMENT 'authorisation information',
  `username` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `first_name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `insertion` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `last_name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `gender` enum('F','M') CHARACTER SET utf8 DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `powers`
--

DROP TABLE IF EXISTS `powers`;
CREATE TABLE IF NOT EXISTS `powers` (
  `id` int(10) unsigned NOT NULL COMMENT 'POIN',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `player_text` text COLLATE utf8_unicode_ci NOT NULL,
  `cs_text` text COLLATE utf8_unicode_ci,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

DROP TABLE IF EXISTS `skills`;
CREATE TABLE IF NOT EXISTS `skills` (
`id` int(11) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cost` int(10) NOT NULL,
  `manatype_id` int(10) unsigned DEFAULT NULL,
  `mana_amount` int(10) DEFAULT NULL,
  `sort_order` int(10) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=258 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `spells`
--

DROP TABLE IF EXISTS `spells`;
CREATE TABLE IF NOT EXISTS `spells` (
`id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `short` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `spiritual` tinyint(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `worlds`
--

DROP TABLE IF EXISTS `worlds`;
CREATE TABLE IF NOT EXISTS `worlds` (
`id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attributes`
--
ALTER TABLE `attributes`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attributes_items`
--
ALTER TABLE `attributes_items`
 ADD PRIMARY KEY (`attribute_id`,`item_id`), ADD KEY `attributes_items_item_key` (`item_id`);

--
-- Indexes for table `believes`
--
ALTER TABLE `believes`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `characters`
--
ALTER TABLE `characters`
 ADD PRIMARY KEY (`id`),
 ADD KEY `player_idx` (`player_id`),
 ADD KEY `belief_idx` (`belief_id`),
 ADD KEY `faction_idx` (`faction_id`),
 ADD KEY `group_idx` (`group_id`),
 ADD KEY `world_idx` (`world_id`);

--
-- Indexes for table `characters_conditions`
--
ALTER TABLE `characters_conditions`
 ADD PRIMARY KEY (`character_id`,`condition_id`),
 ADD KEY `characters_conditions_condition_key` (`condition_id`);

--
-- Indexes for table `characters_powers`
--
ALTER TABLE `characters_powers`
 ADD PRIMARY KEY (`character_id`,`power_id`),
 ADD KEY `characters_powers_power_key` (`power_id`);

--
-- Indexes for table `characters_skills`
--
ALTER TABLE `characters_skills`
 ADD PRIMARY KEY (`character_id`,`skill_id`),
 ADD KEY `characters_skills_skill_key` (`skill_id`);

--
-- Indexes for table `characters_spells`
--
ALTER TABLE `characters_spells`
 ADD PRIMARY KEY (`character_id`,`spell_id`),
 ADD KEY `characters_spells_spell_key` (`spell_id`);

--
-- Indexes for table `conditions`
--
ALTER TABLE `conditions`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `factions`
--
ALTER TABLE `factions`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
 ADD PRIMARY KEY (`id`),
 ADD KEY `character_idx` (`character_id`);

--
-- Indexes for table `manatypes`
--
ALTER TABLE `manatypes`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `players`
--
ALTER TABLE `players`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `powers`
--
ALTER TABLE `powers`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
 ADD PRIMARY KEY (`id`),
 ADD KEY `manatype_id` (`manatype_id`);

--
-- Indexes for table `spells`
--
ALTER TABLE `spells`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `worlds`
--
ALTER TABLE `worlds`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `believes`
--
ALTER TABLE `believes`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `characters`
--
ALTER TABLE `characters`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `factions`
--
ALTER TABLE `factions`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `spells`
--
ALTER TABLE `spells`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `worlds`
--
ALTER TABLE `worlds`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attributes_items`
--
ALTER TABLE `attributes_items`
ADD CONSTRAINT `attributes_items_attribute_key` FOREIGN KEY (`attribute_id`) REFERENCES `attributes` (`id`) ON UPDATE CASCADE,
ADD CONSTRAINT `attributes_items_item_key` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `characters`
--
ALTER TABLE `characters`
ADD CONSTRAINT `characters_belief_key` FOREIGN KEY (`belief_id`) REFERENCES `believes` (`id`) ON UPDATE CASCADE,
ADD CONSTRAINT `characters_faction_key` FOREIGN KEY (`faction_id`) REFERENCES `factions` (`id`) ON UPDATE CASCADE,
ADD CONSTRAINT `characters_group_key` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON UPDATE CASCADE,
ADD CONSTRAINT `characters_player_key` FOREIGN KEY (`player_id`) REFERENCES `players` (`id`) ON UPDATE CASCADE,
ADD CONSTRAINT `characters_world_key` FOREIGN KEY (`world_id`) REFERENCES `worlds` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `characters_conditions`
--
ALTER TABLE `characters_conditions`
ADD CONSTRAINT `characters_conditions_character_key` FOREIGN KEY (`character_id`) REFERENCES `characters` (`id`) ON UPDATE CASCADE,
ADD CONSTRAINT `characters_conditions_condition_key` FOREIGN KEY (`condition_id`) REFERENCES `conditions` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `characters_powers`
--
ALTER TABLE `characters_powers`
ADD CONSTRAINT `characters_powers_character_key` FOREIGN KEY (`character_id`) REFERENCES `characters` (`id`) ON UPDATE CASCADE,
ADD CONSTRAINT `characters_powers_power_key` FOREIGN KEY (`power_id`) REFERENCES `powers` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `characters_skills`
--
ALTER TABLE `characters_skills`
ADD CONSTRAINT `characters_skills_character_key` FOREIGN KEY (`character_id`) REFERENCES `characters` (`id`) ON UPDATE CASCADE,
ADD CONSTRAINT `characters_skills_skill_key` FOREIGN KEY (`skill_id`) REFERENCES `skills` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `characters_spells`
--
ALTER TABLE `characters_spells`
ADD CONSTRAINT `characters_spells_character_key` FOREIGN KEY (`character_id`) REFERENCES `characters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `characters_spells_spell_key` FOREIGN KEY (`spell_id`) REFERENCES `spells` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
ADD CONSTRAINT `items_character_key` FOREIGN KEY (`character_id`) REFERENCES `characters` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `skills`
--
ALTER TABLE `skills`
ADD CONSTRAINT `skills_manatype_key` FOREIGN KEY (`manatype_id`) REFERENCES `manatypes` (`id`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

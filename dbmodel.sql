
-- ------
-- BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
-- teotihuacan implementation : © Jochen Walther boardgamearena@waltherjochen.de
-- 
-- This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
-- See http://en.boardgamearena.com/#!doc/Studio for more information.
-- -----

-- dbmodel.sql

-- This is the file where you are describing the database schema of your game
-- Basically, you just have to export from PhpMyAdmin your table structure and copy/paste
-- this export here.
-- Note that the database itself and the standard tables ("global", "stats", "gamelog" and "player") are
-- already created and must not be created here

-- Note: The database schema is created from this file when the game starts. If you modify this file,
--       you have to restart a game to see your changes in database.

-- Example 1: create a standard "card" table to be used with the "Deck" tools (see example game "hearts"):

-- CREATE TABLE IF NOT EXISTS `card` (
--   `card_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
--   `card_type` varchar(16) NOT NULL,
--   `card_type_arg` int(11) NOT NULL,
--   `card_location` varchar(16) NOT NULL,
--   `card_location_arg` int(11) NOT NULL,
--   PRIMARY KEY (`card_id`)
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- Example 2: add a custom field to the standard "player" table
-- ALTER TABLE `player` ADD `player_my_custom_field` INT UNSIGNED NOT NULL DEFAULT '0';

ALTER TABLE `player` ADD `cocoa` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `player` ADD `wood` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `player` ADD `stone` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `player` ADD `gold` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `player` ADD `temple_blue` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `player` ADD `temple_red` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `player` ADD `temple_green` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `player` ADD `avenue_of_dead` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `player` ADD `pyramid_track` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `player` ADD `techTiles_r1_c1` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `player` ADD `techTiles_r1_c2` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `player` ADD `techTiles_r1_c3` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `player` ADD `techTiles_r2_c1` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `player` ADD `techTiles_r2_c2` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `player` ADD `techTiles_r2_c3` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `player` ADD `startingTile0` INT DEFAULT NULL;
ALTER TABLE `player` ADD `startingTile1` INT DEFAULT NULL;
ALTER TABLE `player` ADD `startingDiscovery0` INT DEFAULT NULL;
ALTER TABLE `player` ADD `startingDiscovery1` INT DEFAULT NULL;
ALTER TABLE `player` ADD `startingResourceWood` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `player` ADD `startingResourceStone` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `player` ADD `startingResourceGold` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `player` ADD `enableUndo` INT UNSIGNED NOT NULL DEFAULT '1';
ALTER TABLE `player` ADD `enableAuto` INT UNSIGNED NOT NULL DEFAULT '1';

CREATE TABLE IF NOT EXISTS `card` (
    `card_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `card_type` varchar(16) NOT NULL,
    `card_type_arg` int(11) NOT NULL,
    `card_location` varchar(16) NOT NULL,
    `card_location_arg` int(11) NOT NULL,
    PRIMARY KEY (`card_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `map` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `actionboard_id` INT NOT NULL ,
    `player_id` INT NOT NULL ,
    `worker_id` INT NOT NULL ,
    `worker_power` INT NOT NULL ,
    `locked` BOOLEAN NOT NULL,
    `worship_pos` INT NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `temple_queue` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `queue` varchar(16) NOT NULL,
    `referrer` INT NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `discovery_queue` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `queue` varchar(16) NOT NULL,
    `referrer` INT NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `nobles` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `row0` INT NOT NULL DEFAULT '0',
    `row1` INT NOT NULL DEFAULT '0',
    `row2` INT NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `pyramid` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `column0` char NOT NULL,
    `column1` char NOT NULL,
    `column2` char NOT NULL,
    `column3` char NOT NULL,
    `column4` char NOT NULL,
    `column5` char NOT NULL,
    `column6` char NOT NULL,
    `column7` char NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;
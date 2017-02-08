CREATE DATABASE `mvc` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
use mvc;
create table `pages` (
  `id` tinyint(3) unsigned not null auto_increment,
  `alias` VARCHAR(100) NOT NULL,
  `title` VARCHAR(100) NOT NULL,
  `content` TEXT DEFAULT NULL,
  `is_published` TINYINT(1) UNSIGNED DEFAULT 0,
  PRIMARY KEY(`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

INSERT INTO `pages`
VALUES (1, 'about', 'About Us', 'Test content', 1),
       (2, 'test', 'Test page', 'Another test content', 1);

CREATE TABLE `messages` (
  `id` TINYINT(3) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `message` TEXT DEFAULT NULL,
  PRIMARY KEY(`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

CREATE TABLE  `users` (
  `id` SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `login` VARCHAR(45) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `role` VARCHAR(45) NOT NULL DEFAULT 'admin',
  `password` VARCHAR(32) NOT NULL,
  `is_active` TINYINT(1) UNSIGNED DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

ALTER TABLE `users` CHANGE `role` `role` VARCHAR(45) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'moderator';

CREATE TABLE `alias_tags` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(150) NOT NULL,
  `is_tag` TINYINT NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  UNIQUE(`name`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;




CREATE TABLE `categories` (
  `id` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent` SMALLINT UNSIGNED NOT NULL,
  `alias_id` INT UNSIGNED NOT NULL,
  `name` VARCHAR(50) NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (alias_id)
  REFERENCES alias_tags(id)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

INSERT INTO `alias_tags` (`name`, `is_tag`) VALUES ('curabitur', '1'), ('vestibulum', '1'), ('pellentesque', '1'), ('volutpat', '1');

CREATE TABLE `news` (
  `id` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` SMALLINT UNSIGNED NOT NULL,
  `alias_id` INT UNSIGNED NOT NULL,
  `title` VARCHAR(50) NOT NULL,
  `meta_keywords` VARCHAR(200) NOT NULL,
  `meta_description` VARCHAR(200) NOT NULL,
  `description` VARCHAR(250) NOT NULL,
  `img_title` VARCHAR(200) NOT NULL,
  `content` TEXT NOT NULL,
  `created_at`	TIMESTAMP,
  `updated_at`	TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (alias_id)
  REFERENCES alias_tags(id)
    ON UPDATE CASCADE ON DELETE CASCADE,
  FOREIGN KEY (category_id)
  REFERENCES categories(id)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8;


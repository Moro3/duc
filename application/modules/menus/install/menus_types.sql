-- phpMyAdmin SQL Dump
-- version 4.0.10
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Янв 21 2015 г., 02:11
-- Версия сервера: 5.5.35-log
-- Версия PHP: 5.4.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `ci_duc2`
--

-- --------------------------------------------------------

--
-- Структура таблицы `menus_types`
--

CREATE TABLE IF NOT EXISTS `menus_types` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `active` smallint(1) NOT NULL DEFAULT '0',
  `sorter` smallint(3) NOT NULL DEFAULT '0',
  `name` char(200) NOT NULL,
  `alias` char(50) NOT NULL,
  `driver` varchar(12) NOT NULL,
  `description` char(250) NOT NULL,
  `date_create` int(10) NOT NULL,
  `date_update` int(10) NOT NULL,
  `ip_create` char(15) NOT NULL,
  `ip_update` char(15) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `active` (`active`),
  KEY `name` (`name`),
  KEY `sorter` (`sorter`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `menus_types`
--

INSERT INTO `menus_types` (`id`, `active`, `sorter`, `name`, `alias`, `driver`, `description`, `date_create`, `date_update`, `ip_create`, `ip_update`) VALUES
(1, 1, 10, 'page', 'p', 'pages', 'Страница', 0, 0, '', ''),
(2, 1, 20, 'mod', 'm', 'mods', 'Модуль', 0, 0, '', ''),
(3, 1, 30, 'modroute', 'mr', 'mods_route', 'Маршрут модулей', 0, 0, '', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

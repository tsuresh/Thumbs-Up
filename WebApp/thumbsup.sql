-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 11, 2017 at 07:13 AM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `thumbsup`
--

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE IF NOT EXISTS `items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `orgid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `description` text NOT NULL,
  `pic` text NOT NULL,
  `price` varchar(50) NOT NULL,
  `rand` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `name`, `orgid`, `uid`, `description`, `pic`, `price`, `rand`) VALUES
(1, 'Devilled Chicken Pan Pizza', 1, 5, 'Devilled chicken in spicy sauce with a double layer of mozzarella cheese.', '', '1670', '10071669'),
(2, 'Sausage Delight Pan Pizza', 1, 5, 'Chicken sausages & onions with a double layer of mozzarella cheese. ', '', '1670', '4604056'),
(3, 'Chicken Biriyani', 2, 5, 'Chicken Biriyani', '', '320', '17925364'),
(4, 'Cheese Chicken Rotty', 2, 5, 'Cheese Chicken Rotty', '', '420', '25144337'),
(5, 'Chicken Charger', 5, 5, 'KFC Chicken Charger', '', '290', '52223814'),
(6, 'Griller Burger', 1, 5, 'KFC Griller Burger', '', '390', '36467449'),
(7, 'Chicken Royale Meal', 4, 5, 'Burger King Chicken Royale Meal', '', '690', '12608595');

-- --------------------------------------------------------

--
-- Table structure for table `offers`
--

CREATE TABLE IF NOT EXISTS `offers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `orgid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `description` text NOT NULL,
  `pic` text NOT NULL,
  `requiredpoints` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `offers`
--

INSERT INTO `offers` (`id`, `name`, `orgid`, `uid`, `description`, `pic`, `requiredpoints`) VALUES
(1, '10% off on Chicken Burgers', 5, 5, 'KFC Hot Heal', '', '100'),
(2, 'Free Medium Pizza with Every Large Pizza', 1, 5, 'Get a free medium pizza with Every Large Pizza your purchase', '', '50');

-- --------------------------------------------------------

--
-- Table structure for table `orgs`
--

CREATE TABLE IF NOT EXISTS `orgs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `uid` int(11) NOT NULL,
  `location` text NOT NULL,
  `category` enum('store','individual') NOT NULL DEFAULT 'individual',
  `points` varchar(50) NOT NULL DEFAULT '100',
  `verified` enum('0','1') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `points`
--

CREATE TABLE IF NOT EXISTS `points` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `itemid` int(11) NOT NULL,
  `userid` varchar(50) NOT NULL,
  `points` varchar(50) NOT NULL,
  `datetime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `points`
--

INSERT INTO `points` (`id`, `itemid`, `userid`, `points`, `datetime`) VALUES
(1, 6, '1', '20', '2017-06-11 10:11:39'),
(2, 7, '1', '5', '2017-06-11 10:12:55');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE IF NOT EXISTS `reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `itemid` int(11) NOT NULL,
  `uname` varchar(100) NOT NULL,
  `uid` int(11) NOT NULL,
  `quality` text NOT NULL,
  `taste` text NOT NULL,
  `price` text NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `itemid`, `uname`, `uid`, `quality`, `taste`, `price`, `description`) VALUES
(1, 6, 'Suresh Peiris ', 1, '2.5', '5.0', '2.0', 'Good taste and nice place to have dinner!'),
(2, 7, 'Suresh Peiris ', 1, '3.5', '3.5', '2.5', '');

-- --------------------------------------------------------

--
-- Table structure for table `stores`
--

CREATE TABLE IF NOT EXISTS `stores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `storename` varchar(100) NOT NULL,
  `location` varchar(100) NOT NULL,
  `uid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `stores`
--

INSERT INTO `stores` (`id`, `storename`, `location`, `uid`) VALUES
(1, 'Pizza Hut', 'Kottawa', 5),
(2, 'Pilawoos ', 'Bambalapitiya', 5),
(3, 'Pizza Hut', 'Nugegoda', 5),
(4, 'Burger King ', 'Mount Lavinia', 5),
(5, 'KFC', 'Kottawa', 5);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `acctype` enum('normal','vendor') NOT NULL DEFAULT 'normal',
  `hash` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `acctype`, `hash`) VALUES
(1, 'Suresh Peiris ', 'tsmpmail@gmail.com', '25d55ad283aa400af464c76d713c07ad', 'normal', '98263455707158f5bfea37e7c8d65827'),
(5, 'Suresh Peiris', 'sureshpeiris2@gmail.com', '25d55ad283aa400af464c76d713c07ad', 'vendor', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

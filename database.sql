-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 11, 2013 at 09:48 PM
-- Server version: 5.5.29
-- PHP Version: 5.4.6-1ubuntu1.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `tony`
--

-- --------------------------------------------------------

--
-- Table structure for table `city`
--

CREATE TABLE IF NOT EXISTS `city` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `province_id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `city`
--

INSERT INTO `city` (`id`, `province_id`, `name`) VALUES
(2, 1, 'Calgary'),
(3, 1, 'Edmonton'),
(4, 2, 'Vancouver'),
(5, 2, 'Whistler');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `users_id` int(11) NOT NULL,
  `image_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=339 ;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `users_id`, `image_id`, `comment`, `date`) VALUES
(282, 0, 0, 'gggg', '2013-02-28 03:23:30'),
(283, 0, 0, 'gggg', '2013-02-28 03:23:46'),
(317, 0, 0, 'sdadsadsadas', '2013-02-28 05:19:06'),
(318, 0, 0, 'aaaaaa', '2013-02-28 05:22:04'),
(335, 30, 97, 'haha', '2013-02-28 19:01:31'),
(336, 29, 99, 'ffffasddasdsa', '2013-03-05 05:53:49'),
(337, 29, 99, '321', '2013-03-06 05:33:29'),
(338, 29, 99, '123', '2013-03-06 05:33:31');

-- --------------------------------------------------------

--
-- Table structure for table `group`
--

CREATE TABLE IF NOT EXISTS `group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `group`
--

INSERT INTO `group` (`id`, `name`) VALUES
(1, 'Admin aaaa'),
(4, 'First Group');

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE IF NOT EXISTS `images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `users_id` int(11) NOT NULL,
  `title` varchar(250) NOT NULL,
  `description` text NOT NULL,
  `photo` varchar(200) NOT NULL,
  `date_add` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `photo_dir` varchar(250) NOT NULL,
  `lat` double NOT NULL,
  `lng` double NOT NULL,
  `acc` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=140 ;

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`id`, `users_id`, `title`, `description`, `photo`, `date_add`, `photo_dir`, `lat`, `lng`, `acc`) VALUES
(97, 30, '', 'A cool airplane', 'DSCN2307.JPG', '2013-02-28 06:10:09', '97', 0, 0, 0),
(99, 30, '', 'bla bla', 'DSCN2303.JPG', '2013-02-28 19:01:13', '99', 0, 0, 0),
(131, 29, '', 'V', '9060', '2013-03-11 03:46:26', '131', 51.0750703, -114.1378838, 61),
(135, 29, '', 'Hubdr', '8535', '2013-03-11 03:53:14', '135', 51.0751021, -114.1381158, 77),
(136, 29, '', '', 'DSC00006.jpg', '2013-03-11 21:21:31', '136', 0, 0, 0),
(137, 29, '', 'Cc', '1363057714712.jpg', '2013-03-12 03:08:47', '137', 51.0751132, -114.1380757, 27),
(138, 29, '', 'Hhh', '1363058136680.jpg', '2013-03-12 03:15:51', '138', 51.0762185, -114.1354887, 402),
(139, 29, '', 'Jj\n', '1363058251930.jpg', '2013-03-12 03:17:52', '139', 51.0750974, -114.1380571, 39);

-- --------------------------------------------------------

--
-- Table structure for table `province`
--

CREATE TABLE IF NOT EXISTS `province` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `province`
--

INSERT INTO `province` (`id`, `name`) VALUES
(1, 'Alberta'),
(2, 'British Columbia');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `added_by_user_id` int(11) NOT NULL,
  `created` date NOT NULL,
  `modified` date NOT NULL,
  `name` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `birthday` date NOT NULL,
  `sex` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `photo` varchar(200) NOT NULL,
  `photo_dir` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=32 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `group_id`, `city_id`, `added_by_user_id`, `created`, `modified`, `name`, `password`, `email`, `birthday`, `sex`, `username`, `photo`, `photo_dir`) VALUES
(4, 1, 3, 0, '2013-02-09', '2013-02-24', 'Frederico Schardong', 'bbdfa16a61515174a6fed833733c05e2ead62d90', 'frede.sch@gmail.com', '2008-11-09', 0, 'admin', 'DSCN2295.JPG', '4'),
(29, 4, 4, 4, '2013-02-25', '2013-02-27', 'Mr. 123', 'c4b9a8b618b9bf6b8902176c071301c14dfab506', '123@123.com', '1937-05-25', 0, '123', 'DSCN2298.JPG', '29'),
(30, 4, 2, 4, '2013-02-25', '2013-02-27', '321', '997b3bb3e82b8e92a9187a3b161612181696b6dc', '321@321.com', '2008-02-25', 0, '321', 'DSCN2296.JPG', '30'),
(31, 4, 2, 4, '2013-02-27', '2013-02-27', 'asd', '7eac55cf0eb4f461482b30e0b08503006e13919e', 'asd@asd.com', '2008-02-27', 0, 'asd', 'DSCN2302.JPG', '31');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

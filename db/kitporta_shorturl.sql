-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 05, 2012 at 03:22 PM
-- Server version: 5.0.95
-- PHP Version: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `kitporta_shorturl`
--

-- --------------------------------------------------------

--
-- Table structure for table `api_actives`
--

CREATE TABLE IF NOT EXISTS `api_actives` (
  `apiKey` varchar(50) NOT NULL,
  `ipAddress` varchar(16) NOT NULL,
  `expires` datetime NOT NULL,
  `clientId` varchar(40) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `api_logi`
--

CREATE TABLE IF NOT EXISTS `api_logi` (
  `apiUs` varchar(30) NOT NULL,
  `apiPas` varchar(50) NOT NULL,
  `clientId` varchar(40) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cl_auth`
--

CREATE TABLE IF NOT EXISTS `cl_auth` (
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `lastLogin` datetime NOT NULL,
  `rememberMe` varchar(40) NOT NULL,
  `clientId` varchar(35) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cl_client`
--

CREATE TABLE IF NOT EXISTS `cl_client` (
  `clId` varchar(35) NOT NULL,
  `company` varchar(30) NOT NULL,
  `c_email` varchar(30) NOT NULL,
  `address` varchar(30) NOT NULL,
  `address2` varchar(30) NOT NULL,
  `city` varchar(30) NOT NULL,
  `state` varchar(30) NOT NULL,
  `zip` varchar(10) NOT NULL,
  `status` int(11) NOT NULL,
  `cl_name` varchar(30) NOT NULL,
  `parentClient` varchar(40) NOT NULL,
  `sk` varchar(40) NOT NULL,
  KEY `clId` (`clId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `excl_ip`
--

CREATE TABLE IF NOT EXISTS `excl_ip` (
  `ip` varchar(30) NOT NULL,
  `clientId` varchar(35) NOT NULL,
  KEY `ip` (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `logging`
--

CREATE TABLE IF NOT EXISTS `logging` (
  `logId` int(11) NOT NULL auto_increment,
  `dateLogged` datetime NOT NULL,
  `referrer` varchar(20) NOT NULL,
  `postData` text NOT NULL,
  `getData` text NOT NULL,
  `response` text NOT NULL,
  `requestURL` varchar(250) NOT NULL,
  PRIMARY KEY  (`logId`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=498 ;

-- --------------------------------------------------------

--
-- Table structure for table `urls`
--

CREATE TABLE IF NOT EXISTS `urls` (
  `hId` varchar(35) NOT NULL,
  `fullurl` varchar(250) NOT NULL,
  `shorturl` varchar(100) NOT NULL,
  `dateCreated` datetime NOT NULL,
  `clientId` varchar(100) NOT NULL,
  `creatorIp` varchar(20) NOT NULL,
  `urlTitle` varchar(150) NOT NULL,
  UNIQUE KEY `shorturl` (`shorturl`),
  KEY `hId` (`hId`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `url_hits`
--

CREATE TABLE IF NOT EXISTS `url_hits` (
  `hId` varchar(35) NOT NULL,
  `dateHit` datetime NOT NULL,
  `ipAddress` varchar(20) NOT NULL,
  `referrer` varchar(250) NOT NULL,
  `shortURL` varchar(35) NOT NULL,
  `returner` varchar(40) NOT NULL,
  `userAgent` varchar(70) NOT NULL,
  `countryCode` varchar(50) NOT NULL,
  `country` varchar(50) NOT NULL,
  `flag` varchar(100) NOT NULL,
  `city` varchar(50) NOT NULL,
  `region` varchar(50) NOT NULL,
  `isp` varchar(50) NOT NULL,
  `lati` varchar(20) NOT NULL,
  `longi` varchar(20) NOT NULL,
  KEY `hId` (`hId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

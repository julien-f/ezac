-- phpMyAdmin SQL Dump
-- version OVH
-- http://www.phpmyadmin.net
--
-- Host: mysql51-42.perso
-- Generation Time: Apr 21, 2012 at 07:39 PM
-- Server version: 5.1.49
-- PHP Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `folkafonfdbv`
--

-- --------------------------------------------------------

--
-- Table structure for table `hebergement_evenement`
--

DROP TABLE IF EXISTS `hebergement_evenement`;
CREATE TABLE IF NOT EXISTS `hebergement_evenement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `lieu` text NOT NULL,
  `dates` text NOT NULL,
  `infos` text NOT NULL,
  `infos_contact` text NOT NULL,
  `site_officiel` varchar(511) NOT NULL,
  `banniere` varchar(127) NOT NULL,
  `code_couleur` varchar(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `hebergement_hotes`
--

DROP TABLE IF EXISTS `hebergement_hotes`;
CREATE TABLE IF NOT EXISTS `hebergement_hotes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_evenement` int(11) NOT NULL,
  `qui` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `adresse` varchar(511) COLLATE latin1_general_ci NOT NULL,
  `telephone` varchar(15) COLLATE latin1_general_ci NOT NULL,
  `email` varchar(127) COLLATE latin1_general_ci NOT NULL,
  `nb_places` smallint(6) NOT NULL,
  `couchage` text COLLATE latin1_general_ci NOT NULL,
  `remarque` text COLLATE latin1_general_ci NOT NULL,
  `date_inscription` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=39 ;

-- --------------------------------------------------------

--
-- Table structure for table `hebergement_invites`
--

DROP TABLE IF EXISTS `hebergement_invites`;
CREATE TABLE IF NOT EXISTS `hebergement_invites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `qui` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `telephone` varchar(15) COLLATE latin1_general_ci NOT NULL,
  `email` varchar(127) COLLATE latin1_general_ci NOT NULL,
  `origine` varchar(127) COLLATE latin1_general_ci NOT NULL,
  `date_inscription` datetime NOT NULL,
  `id_hote` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=86 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

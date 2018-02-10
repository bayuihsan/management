-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 13, 2015 at 01:45 PM
-- Server version: 5.5.36
-- PHP Version: 5.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `money`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE IF NOT EXISTS `accounts` (
  `accounts_id` int(11) NOT NULL AUTO_INCREMENT,
  `accounts_name` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `opening_balance` double NOT NULL,
  `note` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`accounts_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `chart_of_accounts`
--

CREATE TABLE IF NOT EXISTS `chart_of_accounts` (
  `chart_id` int(11) NOT NULL AUTO_INCREMENT,
  `accounts_name` varchar(30) COLLATE utf8_bin NOT NULL,
  `accounts_type` varchar(7) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`chart_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=16 ;

--
-- Dumping data for table `chart_of_accounts`
--

INSERT INTO `chart_of_accounts` (`chart_id`, `accounts_name`, `accounts_type`) VALUES
(1, 'Domain & Hosting', 'Expense'),
(2, 'Phone Bill', 'Expense'),
(3, 'Advertise', 'Expense'),
(4, 'Tax', 'Expense'),
(5, 'House Rent', 'Expense'),
(6, 'Medical', 'Expense'),
(7, 'Repair', 'Expense'),
(8, 'Maintainance', 'Expense'),
(9, 'Salary', 'Income'),
(10, 'Regular Income', 'Income'),
(11, 'Other Income', 'Income'),
(12, 'Product Sell', 'Income'),
(13, 'Rent', 'Income'),
(14, 'Bank Interest', 'Income'),
(15, 'Domain Sell', 'Income');

-- --------------------------------------------------------

--
-- Table structure for table `language`
--

CREATE TABLE IF NOT EXISTS `language` (
  `phrase_id` int(11) NOT NULL AUTO_INCREMENT,
  `phrase` longtext COLLATE utf8_unicode_ci NOT NULL,
  `english` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`phrase_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `payee_payers`
--

CREATE TABLE IF NOT EXISTS `payee_payers` (
  `trace_id` int(11) NOT NULL AUTO_INCREMENT,
  `payee_payers` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(5) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`trace_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=16 ;

--
-- Dumping data for table `payee_payers`
--

INSERT INTO `payee_payers` (`trace_id`, `payee_payers`, `type`) VALUES
(1, 'Google', 'Payee'),
(2, 'Go Daddy', 'Payee'),
(3, 'Bluehost', 'Payee'),
(4, 'Employee', 'Payee'),
(5, 'Univercity', 'Payee'),
(6, 'Odesk', 'Payer'),
(7, 'Envato', 'Payer'),
(8, 'Forex', 'Payer'),
(9, 'Standard Chartered Bank', 'Payer'),
(10, 'City Bank', 'Payer'),
(11, 'Client', 'Payer');

-- --------------------------------------------------------

--
-- Table structure for table `payment_method`
--

CREATE TABLE IF NOT EXISTS `payment_method` (
  `p_method_id` int(11) NOT NULL AUTO_INCREMENT,
  `p_method_name` varchar(20) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`p_method_id`),
  UNIQUE KEY `p_method_name` (`p_method_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=7 ;

--
-- Dumping data for table `payment_method`
--

INSERT INTO `payment_method` (`p_method_id`, `p_method_name`) VALUES
(3, 'Cash'),
(4, 'Check'),
(5, 'Credit Card'),
(6, 'Debit Card'),
(1, 'Paypal'),
(2, 'Skrill');

-- --------------------------------------------------------

--
-- Table structure for table `repeat_transaction`
--

CREATE TABLE IF NOT EXISTS `repeat_transaction` (
  `trans_id` int(11) NOT NULL AUTO_INCREMENT,
  `account` varchar(30) COLLATE utf8_bin NOT NULL,
  `type` enum('Income','Expense') COLLATE utf8_bin NOT NULL,
  `category` varchar(30) COLLATE utf8_bin NOT NULL,
  `amount` double NOT NULL,
  `payer` varchar(30) COLLATE utf8_bin NOT NULL,
  `payee` varchar(30) COLLATE utf8_bin NOT NULL,
  `p_method` varchar(20) COLLATE utf8_bin NOT NULL,
  `ref` varchar(60) COLLATE utf8_bin NOT NULL,
  `status` enum('paid','unpaid','pending','receive') COLLATE utf8_bin NOT NULL,
  `description` text COLLATE utf8_bin NOT NULL,
  `date` date NOT NULL,
  `pdate` date DEFAULT NULL,
  PRIMARY KEY (`trans_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) NOT NULL,
  `settings` text COLLATE utf8_bin NOT NULL,
  `value` text COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `settings`, `value`) VALUES
(1, 'company_name', 'Tricky Code'),
(2, 'language', 'English'),
(3, 'currency_code', '$'),
(4, 'email_address', ''),
(5, 'address', ''),
(6, 'phone', ''),
(7, 'website', ''),
(8, 'logo_path', 'logo.png'),
(9, 'timezone', 'Africa/Abidjan');

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE IF NOT EXISTS `transaction` (
  `trans_id` int(11) NOT NULL AUTO_INCREMENT,
  `accounts_name` varchar(30) COLLATE utf8_bin NOT NULL,
  `trans_date` date NOT NULL,
  `type` enum('Income','Expense','Transfer') COLLATE utf8_bin NOT NULL,
  `category` varchar(30) COLLATE utf8_bin NOT NULL,
  `amount` double NOT NULL,
  `payer` varchar(30) COLLATE utf8_bin NOT NULL,
  `payee` varchar(30) COLLATE utf8_bin NOT NULL,
  `p_method` varchar(20) COLLATE utf8_bin NOT NULL,
  `ref` varchar(64) COLLATE utf8_bin NOT NULL,
  `note` text COLLATE utf8_bin NOT NULL,
  `dr` double NOT NULL,
  `cr` double NOT NULL,
  `bal` double NOT NULL,
  PRIMARY KEY (`trans_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(15) COLLATE utf8_bin NOT NULL,
  `fullname` varchar(30) COLLATE utf8_bin NOT NULL,
  `email` varchar(60) COLLATE utf8_bin NOT NULL,
  `user_type` enum('Admin','Employee') COLLATE utf8_bin NOT NULL,
  `password` varchar(64) COLLATE utf8_bin NOT NULL,
  `creation_date` datetime NOT NULL,
  `last_login` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

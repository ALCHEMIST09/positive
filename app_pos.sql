-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 25, 2016 at 04:11 PM
-- Server version: 5.6.12-log
-- PHP Version: 5.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `app_pos`
--
CREATE DATABASE IF NOT EXISTS `app_pos` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `app_pos`;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `created` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `stock_name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`, `created`) VALUES
(5, 'Scottish Wedge', '2016-08-10'),
(6, 'Miranda', '2016-08-10'),
(7, 'Category', '2016-08-12'),
(8, 'Gaga Flat', '2016-09-07');

-- --------------------------------------------------------

--
-- Table structure for table `collection`
--

CREATE TABLE IF NOT EXISTS `collection` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `collection`
--

INSERT INTO `collection` (`id`, `name`, `description`) VALUES
(1, 'Administrators', 'All Permissions'),
(2, 'Managers', 'Some Permissions'),
(3, 'Employee', 'Limited Permissions');

-- --------------------------------------------------------

--
-- Table structure for table `collection2permission`
--

CREATE TABLE IF NOT EXISTS `collection2permission` (
  `collection_id` int(11) unsigned NOT NULL,
  `permission_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`collection_id`,`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `collection2permission`
--

INSERT INTO `collection2permission` (`collection_id`, `permission_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(2, 8),
(2, 11),
(2, 15);

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE IF NOT EXISTS `customer` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `phone` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `name`, `phone`) VALUES
(2, 'Customer One', '0713312536'),
(3, 'New Customer', '0722335335'),
(5, 'Another Customer', '0722123456');

-- --------------------------------------------------------

--
-- Table structure for table `deposit`
--

CREATE TABLE IF NOT EXISTS `deposit` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cus_id` int(11) unsigned NOT NULL,
  `customer` varchar(150) NOT NULL,
  `phone` varchar(150) NOT NULL,
  `stk_id` int(11) unsigned NOT NULL,
  `stk_cat` varchar(150) NOT NULL,
  `item` varchar(150) NOT NULL,
  `color` varchar(150) NOT NULL,
  `size` varchar(150) NOT NULL,
  `quantity` varchar(150) NOT NULL,
  `unit_price` varchar(150) NOT NULL,
  `date` date NOT NULL,
  `amount` varchar(150) NOT NULL,
  `sale_value` varchar(150) NOT NULL,
  `balance` varchar(150) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cus_id` (`cus_id`),
  KEY `stk_id` (`stk_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `deposit`
--

INSERT INTO `deposit` (`id`, `cus_id`, `customer`, `phone`, `stk_id`, `stk_cat`, `item`, `color`, `size`, `quantity`, `unit_price`, `date`, `amount`, `sale_value`, `balance`) VALUES
(2, 3, 'New Customer', '0722335335', 9, 'Scottish Wedge', 'SW-135', 'Green', '39', '2', '2000', '2016-09-06', '3000', '4000', '1000'),
(7, 2, 'Customer One', '0713312536', 10, 'Miranda', 'MR-126', 'Black', '42', '2', '1500', '2016-09-06', '1800', '3000', '1200');

-- --------------------------------------------------------

--
-- Table structure for table `permission`
--

CREATE TABLE IF NOT EXISTS `permission` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

--
-- Dumping data for table `permission`
--

INSERT INTO `permission` (`id`, `name`, `description`) VALUES
(1, 'admin', 'admin'),
(2, 'create_user', 'Add a user to the system'),
(3, 'edit_user', 'Change details of a user in the system'),
(4, 'delete_user', 'Delete user from the system'),
(5, 'add_stock', 'Add stock items to the System'),
(6, 'edit_stock', 'Edit details of stock items in the system'),
(7, 'delete_stock', 'Delete stock entries from the system'),
(8, 'add_sale', 'Record a sale transaction into the system'),
(9, 'edit_sale', 'Edit details of a sale transaction in the system'),
(10, 'delete_sale', 'Delete a sale recorded in the system'),
(11, 'view_reports', 'View reports of sales activity in the system'),
(12, 'create_category', 'Create category used to classify stock items'),
(13, 'edit_category', 'Edit category used to classify stock items'),
(14, 'delete_category', 'Delete category used to classify stock items'),
(15, 'return_sale', 'Return a sold item back to stock'),
(16, 'add_deposit', 'Record deposit payment'),
(17, 'update_deposit', 'Increment existing deposit amount'),
(18, 'edit_deposit', 'Edit details of deposit payment'),
(19, 'delete_deposit', 'Delete a deposit payment'),
(20, 'add_customer', 'Add customer to the system'),
(21, 'edit_customer', 'Edit customer details'),
(22, 'delete_customer', 'Delete customer from system');

-- --------------------------------------------------------

--
-- Table structure for table `sale`
--

CREATE TABLE IF NOT EXISTS `sale` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `stk_id` int(11) unsigned NOT NULL,
  `stk_cat` varchar(150) NOT NULL,
  `item` varchar(150) NOT NULL,
  `quantity` varchar(150) NOT NULL,
  `unit_price` varchar(150) NOT NULL,
  `discount` varchar(150) NOT NULL DEFAULT '0',
  `total` varchar(150) NOT NULL,
  `date` datetime NOT NULL,
  `receipt` varchar(150) NOT NULL,
  `cashier` varchar(150) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `stk_id` (`stk_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=30 ;

--
-- Dumping data for table `sale`
--

INSERT INTO `sale` (`id`, `stk_id`, `stk_cat`, `item`, `quantity`, `unit_price`, `discount`, `total`, `date`, `receipt`, `cashier`) VALUES
(14, 7, 'Scottish Wedge', 'SW-135', '1', '1500', '0', '1500', '2016-08-11 23:11:26', '160811001', 'Code Warrior'),
(15, 10, 'Miranda', 'MR-126', '10', '1200', '0', '12000', '2016-08-12 05:05:42', '160812001', 'Code Warrior'),
(16, 9, 'Scottish Wedge', 'SW-135', '10', '1000', '0', '10000', '2016-08-12 16:04:52', '160812002', 'Code Warrior'),
(21, 11, 'Category', 'CD123', '3', '1000', '0', '3000', '2016-08-13 13:01:58', '160813001', 'Code Warrior'),
(22, 10, 'Miranda', 'MR-126', '1', '1900', '0300', '1600', '2016-08-13 13:01:55', '160813002', 'Code Warrior'),
(23, 9, 'Scottish Wedge', 'SW-135', '14', '900', '400', '12200', '2016-08-13 13:01:33', '160813003', 'Code Warrior'),
(24, 7, 'Scottish Wedge', 'SW-135', '3', '1200', '300', '3300', '2016-08-13 13:01:08', '160813004', 'Code Warrior'),
(25, 11, 'Category', 'CD123', '23', '1200', '0', '27600', '2016-08-13 13:01:50', '160813005', 'Code Warrior'),
(26, 11, 'Category', 'CD123', '1', '1200', '0', '1200', '2016-08-13 13:01:27', '160813006', 'Code Warrior'),
(27, 7, 'Scottish Wedge', 'SW-135', '2', '1500', '0', '3000', '2016-09-07 12:12:30', '160907001', 'Code Warrior'),
(28, 10, 'Miranda', 'MR-126', '2', '1500', '0', '3000', '2016-09-07 19:07:08', '160907002', 'Code Warrior'),
(29, 12, 'Gaga Flat', 'GL-222', '10', '1500', '0', '15000', '2016-09-07 20:08:46', '160907003', 'Code Warrior');

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

CREATE TABLE IF NOT EXISTS `stock` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cat_id` int(11) unsigned NOT NULL,
  `code` varchar(150) NOT NULL,
  `size` varchar(150) NOT NULL,
  `color` varchar(150) NOT NULL,
  `units` varchar(150) NOT NULL,
  `created` datetime NOT NULL,
  `last_updated` datetime DEFAULT NULL,
  `buy_price` varchar(150) NOT NULL,
  `total_cost` varchar(150) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `stock_name` (`code`),
  KEY `code_name` (`code`),
  KEY `color` (`color`),
  KEY `size` (`size`),
  KEY `cat_id` (`cat_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `stock`
--

INSERT INTO `stock` (`id`, `cat_id`, `code`, `size`, `color`, `units`, `created`, `last_updated`, `buy_price`, `total_cost`) VALUES
(7, 5, 'SW-135', '36', 'Light Blue', '93', '2016-08-10 12:12:03', '2016-08-10 21:09:31', '836', '77748'),
(9, 5, 'SW-135', '39', 'Green', '4', '2016-08-11 07:07:37', '0000-00-00 00:00:00', '800', '3200'),
(10, 6, 'MR-126', '42', 'Black', '7', '2016-08-10 07:07:51', '2016-08-11 07:07:47', '900', '0'),
(11, 7, 'CD123', '44', 'Yellow', '26', '2016-08-12 17:05:44', '0000-00-00 00:00:00', '900', '23400'),
(12, 8, 'GL-222', '36', 'White', '20', '2016-09-07 16:04:40', '2016-09-07 20:08:18', '2000', '40000');

-- --------------------------------------------------------

--
-- Table structure for table `stock_category`
--

CREATE TABLE IF NOT EXISTS `stock_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(150) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user2collection`
--

CREATE TABLE IF NOT EXISTS `user2collection` (
  `user_id` int(11) unsigned NOT NULL,
  `collection_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`collection_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user2collection`
--

INSERT INTO `user2collection` (`user_id`, `collection_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `fname` varchar(150) NOT NULL,
  `lname` varchar(150) NOT NULL,
  `username` varchar(150) NOT NULL,
  `password` varchar(150) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uname` (`username`),
  KEY `passwd` (`password`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fname`, `lname`, `username`, `password`) VALUES
(1, 'Code', 'Warrior', 'CodeWarrior', '99tky3Hl8YygF25We=k1x'),
(2, 'first', 'last', 'username', 'pass');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `deposit`
--
ALTER TABLE `deposit`
  ADD CONSTRAINT `deposit_ibfk_1` FOREIGN KEY (`cus_id`) REFERENCES `customer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `deposit_ibfk_2` FOREIGN KEY (`stk_id`) REFERENCES `stock` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sale`
--
ALTER TABLE `sale`
  ADD CONSTRAINT `sale_ibfk_1` FOREIGN KEY (`stk_id`) REFERENCES `stock` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `stock`
--
ALTER TABLE `stock`
  ADD CONSTRAINT `stock_ibfk_1` FOREIGN KEY (`cat_id`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

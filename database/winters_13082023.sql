-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 14, 2023 at 05:43 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `winters`
--

-- --------------------------------------------------------

--
-- Table structure for table `address_types`
--

CREATE TABLE `address_types` (
  `id` int(11) NOT NULL,
  `description` varchar(250) NOT NULL,
  `cr_date` varchar(250) NOT NULL,
  `cr_time` varchar(250) NOT NULL,
  `mod_date` varchar(250) NOT NULL,
  `mod_time` varchar(250) NOT NULL,
  `status_add_type` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `address_types`
--

INSERT INTO `address_types` (`id`, `description`, `cr_date`, `cr_time`, `mod_date`, `mod_time`, `status_add_type`) VALUES
(1, 'Home', '2023-08-11', '01:46:36pm', '', '', 0),
(2, 'Business', '2023-08-11', '01:49:40pm', '', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `ctype` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `csince` date NOT NULL,
  `address_bill` text NOT NULL,
  `address_ship` text NOT NULL,
  `city` varchar(250) NOT NULL,
  `province` varchar(250) NOT NULL,
  `postal` varchar(250) NOT NULL,
  `status_cust` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `ctype`, `email`, `csince`, `address_bill`, `address_ship`, `city`, `province`, `postal`, `status_cust`) VALUES
(6, 'Robin Joseph', 'Home', 'robinjo1002@rediffmail.com', '0000-00-00', '7 Edgewood Pl NW', 'Calgary Farmer market', 'Calgary', 'Alberta', 'T3A 2T8', 0),
(7, 'Robin Joseph', 'Business', 'robinjo1776@gmail.com', '0000-00-00', '107 Castlebrook Dr NE', 'Edmonton', 'Calgary', 'Alberta', 'T3J 1S8', 0),
(12, 'Alberta Meat and Poultry', 'Business', 'alberta@meat.com', '2023-07-25', 'West Bengal', '4A/4, Jagannath Apartment, Jagannath Ghosh road Kasba', 'Kolkata', 'Alberta', '700042', 0),
(13, 'Virat Kohli', 'Business', 'bbtbr@getgt.com', '2023-08-11', 'jjtu', 'jutj', 'jut', 'jut', '674jjut', 0);

-- --------------------------------------------------------

--
-- Table structure for table `customer_type`
--

CREATE TABLE `customer_type` (
  `id` int(11) NOT NULL,
  `ctype` varchar(250) NOT NULL,
  `status_ctype` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cust_addresses`
--

CREATE TABLE `cust_addresses` (
  `id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `cust_type` varchar(250) NOT NULL,
  `created` varchar(250) NOT NULL,
  `modified` varchar(250) NOT NULL,
  `address1` varchar(250) NOT NULL,
  `address2` varchar(250) NOT NULL,
  `city` varchar(250) NOT NULL,
  `province` varchar(250) NOT NULL,
  `status_cust_address` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cust_addresses`
--

INSERT INTO `cust_addresses` (`id`, `name`, `cust_type`, `created`, `modified`, `address1`, `address2`, `city`, `province`, `status_cust_address`) VALUES
(7, 'Alberta Meat and Poultry', 'Home', '2023-08-13', '', 'Calgary Farmer market', 'Gujarat', 'Calgary', 'Ontario', 0),
(9, 'Calgary Foods', 'Home', '2023-08-13', '', 'fredd lane', 'hugo drive', 'deer foot', 'Alberta', 0),
(10, 'Calgary Foods', 'Home', '2023-08-13', '', 'fredd lane', 'hugo drive', 'deer foot', 'Alberta', 0),
(11, 'Calgary Foods', 'Business', '2023-08-13', '', 'hyrhyr', 'hryhyr', 'calgary', 'ab', 0);

-- --------------------------------------------------------

--
-- Table structure for table `cust_phones`
--

CREATE TABLE `cust_phones` (
  `id` int(11) NOT NULL,
  `cust_name` varchar(250) NOT NULL,
  `type` varchar(250) NOT NULL,
  `name` varchar(250) NOT NULL,
  `method` varchar(250) NOT NULL,
  `detail` varchar(250) NOT NULL,
  `mod_date` varchar(250) NOT NULL,
  `mod_time` varchar(250) NOT NULL,
  `status_cust_phone` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cust_phones`
--

INSERT INTO `cust_phones` (`id`, `cust_name`, `type`, `name`, `method`, `detail`, `mod_date`, `mod_time`, `status_cust_phone`) VALUES
(1, '', '', 'Robin', '', 'Assistant employee', '', '', 0),
(2, '', '', 'Robin', 'Moble', 'Assistant employee', '', '', 0),
(3, '', 'Co-worker', 'Ranjit', 'Phone', 'Assistant director', '', '', 0),
(4, '', 'betb', 'btebte', 'bteb', 'betbteb', '', '', 0),
(5, '', 'ngng', 'nhnh', 'gg', 'nnh', '', '', 0),
(31, 'Harry', '', '', '', '', '', '', 0),
(42, 'Robin', '', '', '', '', '', '', 0),
(52, '', 'big shot', 'Robin', 'email', 'vsfvfs', '', '', 0),
(53, '', 'friend', 'Robin', 'skype', 'nhfnhfn', '', '', 0),
(54, 'Robin', 'cadc', 'cdaca', 'cadca', 'cadca', '', '', 0),
(55, 'Robin', 'Friend', 'Nikhil', 'Phone', 'vfsvs', '', '', 0),
(56, 'Robin', 'Friend', 'Nikhil', 'Phone', 'vfsvs', '', '', 0),
(57, 'Calgary Foods', 'Partner Business', 'Amrish Fast Foods', 'Mobile', 'Take second left from Highway 91', '', '', 0),
(58, 'Calgary Foods', 'vsf', 'vsf', 'vsf', 'vsf', '', '', 0),
(59, 'Calgary Foods', 'vsf', 'vsf', 'vsf', 'vsf', '', '', 0),
(60, 'Calgary Foods', 'nfhn', 'cnhfn', 'nfh', 'nfh', '', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `dashboard`
--

CREATE TABLE `dashboard` (
  `id` int(11) NOT NULL,
  `company` varchar(250) NOT NULL,
  `sname` varchar(250) NOT NULL,
  `store_no` int(11) NOT NULL,
  `city` varchar(250) NOT NULL,
  `via` varchar(250) NOT NULL,
  `tpud` varchar(250) NOT NULL,
  `ccount` int(11) NOT NULL,
  `pcount` int(11) NOT NULL,
  `dnotes` varchar(250) NOT NULL,
  `prod1` int(11) NOT NULL,
  `prod2` int(11) NOT NULL,
  `prod3` int(11) NOT NULL,
  `prod4` int(11) NOT NULL,
  `frtotal` int(11) NOT NULL,
  `frcases` int(11) NOT NULL,
  `org4c` int(11) NOT NULL,
  `torg` int(11) NOT NULL,
  `broast` int(11) NOT NULL,
  `droast` int(11) NOT NULL,
  `troast` int(11) NOT NULL,
  `drums` int(11) NOT NULL,
  `sturkey` int(11) NOT NULL,
  `bbreast` int(11) NOT NULL,
  `bthigh` int(11) NOT NULL,
  `bbroth` int(11) NOT NULL,
  `tpcases` int(11) NOT NULL,
  `tcases` int(11) NOT NULL,
  `comments` text NOT NULL,
  `pstatus` int(1) NOT NULL,
  `istatus` int(1) NOT NULL,
  `sstatus` int(1) NOT NULL,
  `status_dash` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `orderid` varchar(250) NOT NULL,
  `code` varchar(250) NOT NULL,
  `weight` float NOT NULL,
  `qty` int(11) NOT NULL,
  `minw` float NOT NULL,
  `maxw` float NOT NULL,
  `status_item` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `orderid`, `code`, `weight`, `qty`, `minw`, `maxw`, `status_item`) VALUES
(1, '', '', 11.34, 3, 0, 0, 0),
(2, '1', 'T18-FR-W', 3.6, 7, 0, 0, 0),
(3, 'OR220678', 'T18-FR-W', 2.1, 2, 0, 0, 0),
(4, 'OR220678', 'T18-FR-W', 4.33, 5, 0, 0, 0),
(5, 'OR220678', 'T18-FR-W', 12.54, 7, 2, 3, 0),
(6, 'OR2020670', 'T18-ORG-W', 22.33, 3, 4, 5, 0),
(8, 'OR2020670', 'T18-R-W', 12.43, 5, 2, 4, 0),
(9, 'OR220678', 'T18-FR-W', 4.5, 2, 2, 3, 0),
(10, 'OR220678', 'T18-FR-W', 9.4, 1, 2, 3, 0);

-- --------------------------------------------------------

--
-- Table structure for table `loads`
--

CREATE TABLE `loads` (
  `id` int(11) NOT NULL,
  `pn` int(11) NOT NULL,
  `pd` varchar(250) NOT NULL,
  `ld` date NOT NULL,
  `cases` int(11) NOT NULL,
  `birds` int(11) NOT NULL,
  `status_load` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loads`
--

INSERT INTO `loads` (`id`, `pn`, `pd`, `ld`, `cases`, `birds`, `status_load`) VALUES
(1, 85450, 'FR 4-5.99 kg', '1900-12-06', 22, 55, 0),
(4, 85453, 'FR 9-10.99  kg', '2023-08-04', 300, 600, 0),
(6, 12, 'Fried Turkeys', '2023-08-05', 4, 7, 0),
(7, 46773, 'Fried Turkeys', '2023-07-27', 4, 90, 0);

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `id` int(11) NOT NULL,
  `sid` varchar(250) NOT NULL,
  `uname` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `uname` varchar(250) NOT NULL,
  `message` varchar(250) NOT NULL,
  `ndate` varchar(250) NOT NULL,
  `status_all` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `uname`, `message`, `ndate`, `status_all`) VALUES
(1, 'admin', 'New customer was created', '2023-07-06', 1),
(2, 'admin', 'New customer was created', '2023-07-06', 1),
(3, 'admin', 'Product was updated', '0000-00-00', 1),
(4, 'admin', 'Product was deleted', '0000-00-00', 1),
(5, 'admin', 'New product added', '0000-00-00', 1),
(6, 'admin', 'New product added', '31-07-2023', 1),
(7, 'admin', 'New product added', '31-07-2023', 1),
(8, 'admin', 'New product added', '2023-07-31', 1),
(9, 'admin', 'New product added', '2023-07-31', 1),
(10, 'admin', 'Order was added', '2023-08-10', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `orderno` varchar(250) NOT NULL,
  `invoiceno` varchar(250) NOT NULL,
  `customer` varchar(250) NOT NULL,
  `address_bill` text NOT NULL,
  `address_ship` text NOT NULL,
  `orderedby` varchar(250) NOT NULL,
  `terms` varchar(250) NOT NULL,
  `shipvia` varchar(250) NOT NULL,
  `orderdate` varchar(250) NOT NULL,
  `invoicedate` varchar(250) NOT NULL,
  `reqdate` varchar(250) NOT NULL,
  `shipdate` varchar(250) NOT NULL,
  `istatus` int(1) NOT NULL,
  `status_ord` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `orderno`, `invoiceno`, `customer`, `address_bill`, `address_ship`, `orderedby`, `terms`, `shipvia`, `orderdate`, `invoicedate`, `reqdate`, `shipdate`, `istatus`, `status_ord`) VALUES
(4, 'OR220678', '2098442', 'Safeway', '34 Falconridge Dr NE', '34 Falconridge Dr NE', 'Shane', 'Net due 14 days', 'DHL', '0000-00-00', '2023-07-24', '2023-07-19', '2023-08-16', 1, 1),
(9, 'OR2020670', '4086445', 'Amaranth Whole Foods', 'Arbour Lake', 'Arbour Lake', 'Matt', 'Net due 14 days', 'DRL', '2023-06-21', '2023-07-24', '2023-06-07', '2023-06-01', 1, 1),
(14, 'OR653653', '869855', 'Himanta', 'Himanta', 'Himanta', 'Ben', 'Net due', 'DHL', '2023-07-24', '', '2023-07-15', '2023-08-05', 0, 1),
(15, '5435345', '533', 'Hugo', '45 red deer ln', '45 red deer ln', 'Al', 'in 45 days', 'Purolator', '2023-08-10', '', '2023-08-22', '2023-09-08', 0, 1),
(16, '7574647', '447', 'Daniel', '32 Japan cl NE', '32 Japan cl NE', 'Fay', 'Net due', 'Purolator', '2023-08-10', '', '2023-08-02', '2023-10-06', 0, 1),
(17, 'OR8578578', '8555', 'Nutty Professor', '9 Cottonwood dr ', '9 Cottonwood dr ', 'Art', 'in 8 days', 'HJK', '2023-08-10', '', '2023-08-07', '2023-09-08', 0, 1),
(18, '9869869', '55', 'Harald', '8 Songsparrow Rd', '8 Songsparrow Rd', 'george', '7 weeks', 'DHL', '2023-08-10', '', '2023-08-08', '2023-09-09', 0, 1),
(19, '998987', '55', '7 Edgewood Pl NW', '1196 Gateway rd', '1196 Gateway rd', 'Luan', 'Net due', 'Purolator', '2023-08-10', '', '2023-08-01', '2023-09-09', 0, 1),
(20, '5646456', '64', 'yujuy', 'jyu', 'juy', 'jy', 'jyu', 'jy', '2023-08-10', '', '2023-08-01', '2023-10-05', 0, 1),
(21, '656456', '64', 'ujuj', 'jujyu', 'juyjy', 'uyjyu', 'jyu', 'juyj', '2023-08-10', '', '2023-08-08', '2023-09-08', 0, 1),
(22, '6746746', '47', 'mjgjm', 'mj', 'nfh', 'nhf', 'nfh', 'nfh', '2023-08-10', '', '2023-08-08', '2023-09-08', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `code` varchar(250) NOT NULL,
  `description` text NOT NULL,
  `price` float NOT NULL,
  `pd` varchar(250) NOT NULL,
  `bbd` varchar(250) NOT NULL,
  `minw` int(11) NOT NULL,
  `maxw` int(11) NOT NULL,
  `unit` varchar(250) NOT NULL,
  `upc` varchar(250) NOT NULL,
  `items` int(11) NOT NULL,
  `comments` text NOT NULL,
  `status_prod` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `code`, `description`, `price`, `pd`, `bbd`, `minw`, `maxw`, `unit`, `upc`, `items`, `comments`, `status_prod`) VALUES
(1, 'T18-FR-W', '3kg', 4, '02/15/2023', '11/17/2023', 2, 5, 'kg', '358451326947465', 4, 'In good condition', 0),
(2, 'T18-ORG-W', 'Grade A Fresh Heirloom Organic Turkey', 12.89, '05/17/2023', '09/22/2023', 4, 5, 'kg', '019001519485453031020019131521101421910812767855', 6, 'In acceptable condition', 0),
(3, 'T18-R-W', 'Fresh Turkey Roast', 21, '06/12/2023', '12/15/2023', 2, 4, 'kg', '019001519485452331020015351521101321910812756234', 3, 'In good condition', 0),
(6, '458320', '5kg', 200, '07/11/2023', '07/04/2023', 2, 9, 'kg', '019001519485452331020015351521101321910812756234', 6, 'In good condition for past 5 days', 0),
(7, '6356365', 'Boneless turkey', 200, '07/19/2023', '08/05/2023', 2, 6, 'kg', 'HJ636365647', 3, 'good', 0),
(8, '354345', 'turkey beast 8kg', 34, '07/04/2023', '08/16/2023', 3, 5, 'kg', '019001519485452331020015351521101321910812756234', 3, 'GEORGIA', 0),
(9, '746747', 'turkey beast 8kg', 7, '07/04/2023', '08/18/2023', 5, 7, 'kg', '019001519485452331020015351521101321910812756234', 3, 'geethte', 0),
(10, '64564', 'turkey beast 1kg', 6, '07/10/2023', '08/18/2023', 6, 4, 'kg', '019001519485453031020019131521101421910812767855', 5, 'hello', 0),
(11, '67757', 'Boneless turkey', 45, '07/04/2023', '07/29/2023', 4, 7, 'kg', '019001519485452331020015351521101321910812756234', 6, 'good', 0),
(12, '737357', 'Boneless turkey', 57, '07/03/2023', '08/05/2023', 5, 7, 'kg', '019001519485452331020015351521101321910812756234', 3, 'ukkyyu', 0),
(13, '855794', 'turkey beast 1kg', 44, '07/25/2023', '08/05/2023', 4, 9, 'kg', '019001519485452331020015351521101321910812756234', 4, 'good', 0),
(14, '7654846784', 'Boneless turkey', 45, '07/10/2023', '08/05/2023', 6, 8, 'kg', '019001519485452331020015351521101321910812756234', 3, 'GEORGIA', 0),
(15, '9598595', 'Boneless turkey', 55, '07/03/2023', '08/05/2023', 5, 9, 'kg', '019001519485452331020015351521101321910812756234', 3, 'good', 0),
(16, '54747', '3kg', 45, '07/03/2023', '08/05/2023', 6, 7, '', '', 6, 'ukkyyu', 0),
(17, '74674746', 'Boneless turkey', 55, '07/11/2023', '08/24/2023', 4, 7, 'kg', '019001519485453031020019131521101421910812767855', 4, 'ukkyyu', 0),
(18, '78847874', 'Boneless turkey', 55, '07/10/2023', '09/08/2023', 2, 6, 'kg', '019001519485452331020015351521101321910812756234', 3, 'hello', 0),
(19, '5747647467', 'turkey beast 8kg', 74, '07/03/2023', '08/04/2023', 2, 3, 'kg', '019001519485452331020015351521101321910812756234', 2, 'GEORGIA', 0),
(20, '653735735', 'turkey beast 8kg', 200, '07/03/2023', '08/05/2023', 5, 7, 'kg', '019001519485452331020015351521101321910812756234', 4, 'ukkyyu', 0);

-- --------------------------------------------------------

--
-- Table structure for table `provinces`
--

CREATE TABLE `provinces` (
  `id` int(11) NOT NULL,
  `short_name` varchar(250) NOT NULL,
  `english` varchar(250) NOT NULL,
  `french` varchar(250) NOT NULL,
  `status_prov` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `provinces`
--

INSERT INTO `provinces` (`id`, `short_name`, `english`, `french`, `status_prov`) VALUES
(1, '', 'Alberta', 'Alberta', 0),
(2, 'ON', 'Ontario', 'Ontario', 0);

-- --------------------------------------------------------

--
-- Table structure for table `scan`
--

CREATE TABLE `scan` (
  `id` int(11) NOT NULL,
  `orderid` varchar(250) NOT NULL,
  `products` text NOT NULL,
  `code` varchar(250) NOT NULL,
  `description` text NOT NULL,
  `price` int(11) NOT NULL,
  `pd` varchar(250) NOT NULL,
  `bbd` varchar(250) NOT NULL,
  `minw` int(11) NOT NULL,
  `maxw` int(11) NOT NULL,
  `items` int(11) NOT NULL,
  `status_scan` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `scan`
--

INSERT INTO `scan` (`id`, `orderid`, `products`, `code`, `description`, `price`, `pd`, `bbd`, `minw`, `maxw`, `items`, `status_scan`) VALUES
(17, 'OR220678', '358451326947465', 'T18-FR-W', '3kg', 85, '2023-07-05', '2023-08-18', 2, 3, 5, 0),
(20, 'OR2020670', '019001519485453031020019131521101421910812767855', 'T18-ORG-W', 'Grade A Fresh Heirloom Organic Turkey', 13, '', '', 4, 5, 6, 0),
(21, 'OR2020670', '019001519485452331020015351521101321910812756234', 'T18-R-W', 'Fresh Turkey Roast', 21, '', '', 2, 4, 3, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `uname` varchar(250) NOT NULL,
  `sname` varchar(250) NOT NULL,
  `utype` varchar(250) NOT NULL,
  `pic` varchar(250) NOT NULL,
  `phone` varchar(250) NOT NULL,
  `add1` varchar(250) NOT NULL,
  `add2` varchar(250) NOT NULL,
  `pcode` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `pass` varchar(250) NOT NULL,
  `cpass` varchar(250) NOT NULL,
  `con` varchar(250) NOT NULL,
  `status_user` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `uname`, `sname`, `utype`, `pic`, `phone`, `add1`, `add2`, `pcode`, `email`, `pass`, `cpass`, `con`, `status_user`) VALUES
(1, 'Robin', 'admin', 'Joseph', 'Super Admin', 'face8.jpg64c031ed30b507.63575408.jpg', '09605556454', '4A/4, Jagannath Apartments', 'Jagannath Ghosh road, Kasba', '700042', 'robinjo1002@rediffmail.com', 'kuber', 'kuber', '06/30/2023', 0),
(3, 'Jeena', 'jeena', 'Jain', 'Super Admin', 'pexels-andrea-piacquadio-774909.jpg64a6407ae3e058.14598039.jpg', '09656443567', 'A 302 Libra', 'Marve Road Malad W  Mumbai', '700042', 'kuberjain34@gmail.com', 'admin', 'admin', '06/30/2023', 0),
(5, 'Robin', 'robin', 'Joseph', 'Admin', '../../uploads/red-t-shirt.jpeg', '4379713752', '7 Edgewood Pl NW', '', 'T3A 2T8', 'robinjo1002@rediffmail.com', 'admin', 'admin', '07/25/2023', 0),
(6, 'Kuber', 'kub678', 'Jain', 'Super Admin', 'face8.jpg64c03193e99ae3.88186494.jpg', '04379713752', '1195 Gateway road', '', 'K2C2X1', 'kub@gyjj.com', 'kuber', 'kuber', '07/25/2023', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address_types`
--
ALTER TABLE `address_types`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_type`
--
ALTER TABLE `customer_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cust_addresses`
--
ALTER TABLE `cust_addresses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cust_phones`
--
ALTER TABLE `cust_phones`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dashboard`
--
ALTER TABLE `dashboard`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loads`
--
ALTER TABLE `loads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `provinces`
--
ALTER TABLE `provinces`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `scan`
--
ALTER TABLE `scan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `address_types`
--
ALTER TABLE `address_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `customer_type`
--
ALTER TABLE `customer_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cust_addresses`
--
ALTER TABLE `cust_addresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `cust_phones`
--
ALTER TABLE `cust_phones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `dashboard`
--
ALTER TABLE `dashboard`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3647;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `loads`
--
ALTER TABLE `loads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `provinces`
--
ALTER TABLE `provinces`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `scan`
--
ALTER TABLE `scan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

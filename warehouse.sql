-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 21, 2017 at 01:50 PM
-- Server version: 10.1.19-MariaDB
-- PHP Version: 5.6.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `warehouse`
--

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `emp_id` char(10) NOT NULL,
  `emp_name` varchar(50) NOT NULL,
  `email` text NOT NULL,
  `department` varchar(25) DEFAULT 'Unassigned',
  `salary` double DEFAULT NULL,
  `contact` bigint(10) NOT NULL,
  `address` varchar(100) NOT NULL,
  `password` char(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`emp_id`, `emp_name`, `email`, `department`, `salary`, `contact`, `address`, `password`) VALUES
('1MXFNIR85F', 'Nisheet', 'nisheet1.sinvhal@gmail.com', 'Food', 20000, 9029292828, 'Powai', '53b9fcc0557c4f75317f94c8f9dc29ca7b2cd3ff5404a32910130c89463ea34c'),
('THEMANAGER', 'Nisheet Sinvhal', 'nisheet@warehouse.com', 'Managing', 30000, 9029911111, 'Palava City', '2cf24dba5fb0a30e26e83b2ac5b9e29e1b161e5c1fa7425e73043362938b9824'),
('WTQPNT3LV6', 'Parth', 'parth@gmail.com', 'Transport', 17700, 9282839030, 'And', '6f97721d273918145c7ff3a15a1437b53321bef46998a982f195d2c0a46acb6d'),
('XG1K2W9BHH', 'Rudresh', 'rudresh@gmail.com', 'Food', 1500, 8494030223, 'Borivali', '6f97721d273918145c7ff3a15a1437b53321bef46998a982f195d2c0a46acb6d');

-- --------------------------------------------------------

--
-- Table structure for table `food`
--

CREATE TABLE `food` (
  `foodId` varchar(5) NOT NULL,
  `food_name` varchar(80) NOT NULL,
  `section` varchar(15) DEFAULT NULL,
  `s_id` int(11) NOT NULL,
  `addedon` date NOT NULL,
  `expiry_date` date NOT NULL,
  `qty` int(11) NOT NULL,
  `price` double NOT NULL,
  `sold` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `food`
--

INSERT INTO `food` (`foodId`, `food_name`, `section`, `s_id`, `addedon`, `expiry_date`, `qty`, `price`, `sold`) VALUES
('2Q9CC', 'Meats&More_ChickenSausage_500g', 'Frozen', 6, '2016-10-10', '2016-10-12', 20, 500, 0),
('4GN4R', 'Meats&More_ChickenSausage_1000g', 'Frozen', 6, '2016-10-10', '2016-10-20', 3, 2000, 2),
('7QDWT', 'NewBakery_BrownBread_400g', 'Dairy', 9, '2016-10-15', '2016-10-26', 50, 35, 0),
('AJH3Z', 'F-Mart_Yogurt_250g', 'Dairy', 8, '2016-10-14', '2016-10-21', 30, 100, 10),
('CBXZT', 'F-Mart_Cheese_200g', 'Dairy', 8, '2016-10-14', '2016-10-27', 35, 40, 0),
('DPR7B', 'Meats&More_ChickenSausage_200g', 'Frozen', 6, '2016-10-10', '2016-10-17', 15, 200, 0),
('JTXT6', 'Kellogs_Cornflakes_400mg', 'Dairy', 3, '2016-10-10', '2016-11-30', 20, 300, 10),
('OWO2N', 'Kellogs_Cornflakes_400mg', 'Dairy', 3, '2016-10-13', '2016-10-31', 25, 250, 0),
('RBUA7', 'Britannia_MarieGold_500g', 'Dairy', 3, '2016-10-07', '2016-10-21', 50, 10, 0);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` varchar(5) NOT NULL,
  `s_id` int(11) NOT NULL,
  `foodId` varchar(5) NOT NULL,
  `qty` int(11) NOT NULL,
  `addedon` date NOT NULL,
  `delivery_date` date NOT NULL,
  `status` varchar(15) NOT NULL DEFAULT 'Undelivered'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `s_id`, `foodId`, `qty`, `addedon`, `delivery_date`, `status`) VALUES
('3YYK1', 5, '6RVXF', 2, '2016-10-06', '2016-10-09', 'deleted'),
('JFBO3', 5, 'AJH3Z', 10, '2016-10-14', '2016-10-18', 'delivered'),
('OWBL6', 5, 'YTW60', 10, '2016-10-07', '2016-10-12', 'delivered'),
('VMKEB', 7, '4GN4R', 2, '2016-10-10', '2016-10-16', 'deleted'),
('YUGZB', 5, 'JTXT6', 10, '2016-10-14', '2016-10-20', 'Undelivered');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `s_id` int(11) NOT NULL,
  `email` varchar(96) NOT NULL,
  `store_name` varchar(50) NOT NULL,
  `store_type` varchar(10) NOT NULL,
  `address` text NOT NULL,
  `password` char(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`s_id`, `email`, `store_name`, `store_type`, `address`, `password`) VALUES
(3, 'blahblah@gmail.com', 'Foodie', 'seller', 'Powai', '2cf24dba5fb0a30e26e83b2ac5b9e29e1b161e5c1fa7425e73043362938b9824'),
(5, 'blu@gmail.com', 'Gubble', 'buyer', 'Malad', '6f97721d273918145c7ff3a15a1437b53321bef46998a982f195d2c0a46acb6d'),
(6, 'nisheet1.sinvhal@gmail.com', 'Meat''s & More', 'seller', 'Shop No.7,Hiranandani Gardens,Powai,Mumbai-400076', '7456eae98aa49ed07d4d7f4106ef2709a1fbde8c38dd52b70f961dcc3955204e'),
(7, 'nisheet@gmail.com', 'Nature Valley', 'buyer', 'Matunga', '2cf24dba5fb0a30e26e83b2ac5b9e29e1b161e5c1fa7425e73043362938b9824'),
(8, 'elvisp@gmail.com', 'F-Mart', 'seller', 'Malad', '8b7293f6dd93853c83bf8c87ec5349a41789fa166a9431f108edb538e6fd82fd'),
(9, 'bakery@gmail.com', 'New Bakery', 'seller', 'Andheri', '65e84be33532fb784c48129675f9eff3a682b27168c0ea744b2cf58ee02337c5');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`emp_id`);

--
-- Indexes for table `food`
--
ALTER TABLE `food`
  ADD PRIMARY KEY (`foodId`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`s_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `s_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

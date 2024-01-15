-- note to users. update inventory constraint inig import.










-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 15, 2024 at 03:30 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mimi pet store`
--

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `cus_id` int(10) NOT NULL,
  `cus_name` varchar(255) DEFAULT NULL,
  `cus_phone` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`cus_id`, `cus_name`, `cus_phone`) VALUES
(1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `emp_id` int(10) NOT NULL,
  `emp_name` varchar(255) NOT NULL,
  `emp_user` varchar(255) NOT NULL,
  `emp_pass` varchar(255) NOT NULL,
  `emp_status` varchar(255) NOT NULL DEFAULT 'Active',
  `emp_type` enum('Admin','Employee') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`emp_id`, `emp_name`, `emp_user`, `emp_pass`, `emp_status`, `emp_type`) VALUES
(1, 'yanyan', 'yanyan', 'yap', 'Active', 'Employee');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `inv_id` int(10) NOT NULL,
  `inv_item_qty` int(15) NOT NULL,
  `inv_item_status` varchar(255) NOT NULL DEFAULT 'available',
  `prod_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`inv_id`, `inv_item_qty`, `inv_item_status`, `prod_id`) VALUES
(1, 19, 'available', 1),
(2, 8, 'available', 2);

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `invoice_id` int(10) NOT NULL,
  `invoice_date` date NOT NULL DEFAULT current_timestamp(),
  `invoice_status` varchar(255) NOT NULL,
  `emp_id` int(10) NOT NULL,
  `cus_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoice`
--

INSERT INTO `invoice` (`invoice_id`, `invoice_date`, `invoice_status`, `emp_id`, `cus_id`) VALUES
(1, '2024-01-15', 'closed', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `prod_id` int(10) NOT NULL,
  `prod_name` varchar(255) NOT NULL,
  `prod_desc` varchar(255) NOT NULL,
  `prod_price` double NOT NULL,
  `prod_brand` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`prod_id`, `prod_name`, `prod_desc`, `prod_price`, `prod_brand`) VALUES
(1, 'Original(20kgs)', 'Dog Food for Adult', 3550, 'AOZI'),
(2, 'Beef and Liver(20kgs)', 'Dog food for Puppy and Adult', 2750, 'AZU');

-- --------------------------------------------------------

--
-- Table structure for table `purchase`
--

CREATE TABLE `purchase` (
  `pur_id` int(10) NOT NULL,
  `pur_qty` int(10) NOT NULL,
  `pur_price` double NOT NULL,
  `pur_status` varchar(255) NOT NULL,
  `invoice_id` int(10) NOT NULL,
  `prod_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase`
--

INSERT INTO `purchase` (`pur_id`, `pur_qty`, `pur_price`, `pur_status`, `invoice_id`, `prod_id`) VALUES
(1, 1, 3550, 'done', 1, 1),
(2, 2, 2750, 'done', 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `requested`
--

CREATE TABLE `requested` (
  `request_id` int(10) NOT NULL,
  `request_qty` int(10) NOT NULL,
  `request_price` double NOT NULL,
  `req_id` int(10) DEFAULT NULL,
  `prod_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `requested`
--

INSERT INTO `requested` (`request_id`, `request_qty`, `request_price`, `req_id`, `prod_id`) VALUES
(3, 20, 3550, 1, 1),
(4, 10, 2750, 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `requisition`
--

CREATE TABLE `requisition` (
  `req_id` int(10) NOT NULL,
  `req_stat` varchar(255) NOT NULL,
  `req_date` date NOT NULL,
  `emp_id` int(10) NOT NULL,
  `sup_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `requisition`
--

INSERT INTO `requisition` (`req_id`, `req_stat`, `req_date`, `emp_id`, `sup_id`) VALUES
(1, 'RECEIVED', '2024-01-15', 1, 1),
(2, 'RECEIVED', '2024-01-15', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `sup_id` int(10) NOT NULL,
  `sup_name` varchar(255) NOT NULL,
  `sup_phone` varchar(255) NOT NULL,
  `sup_email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`sup_id`, `sup_name`, `sup_phone`, `sup_email`) VALUES
(1, 'Caminade Pet Shop', '09334653687', 'caminade.pet@gmail.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`cus_id`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`emp_id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`inv_id`),
  ADD KEY `fk_prod` (`prod_id`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`invoice_id`),
  ADD KEY `fk_emp` (`emp_id`),
  ADD KEY `fk_cus` (`cus_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`prod_id`);

--
-- Indexes for table `purchase`
--
ALTER TABLE `purchase`
  ADD PRIMARY KEY (`pur_id`),
  ADD KEY `fk_invoice` (`invoice_id`),
  ADD KEY `fk_product` (`prod_id`);

--
-- Indexes for table `requested`
--
ALTER TABLE `requested`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `fk_req` (`req_id`),
  ADD KEY `req_prod` (`prod_id`);

--
-- Indexes for table `requisition`
--
ALTER TABLE `requisition`
  ADD PRIMARY KEY (`req_id`),
  ADD KEY `req_emp` (`emp_id`),
  ADD KEY `req_sup` (`sup_id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`sup_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `cus_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `emp_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `inv_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `invoice_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `prod_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `purchase`
--
ALTER TABLE `purchase`
  MODIFY `pur_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `requested`
--
ALTER TABLE `requested`
  MODIFY `request_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `requisition`
--
ALTER TABLE `requisition`
  MODIFY `req_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `sup_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `invoice`
--
ALTER TABLE `invoice`
  ADD CONSTRAINT `fk_cus` FOREIGN KEY (`cus_id`) REFERENCES `customer` (`cus_id`),
  ADD CONSTRAINT `fk_emp` FOREIGN KEY (`emp_id`) REFERENCES `employee` (`emp_id`);

--
-- Constraints for table `purchase`
--
ALTER TABLE `purchase`
  ADD CONSTRAINT `fk_invoice` FOREIGN KEY (`invoice_id`) REFERENCES `invoice` (`invoice_id`),
  ADD CONSTRAINT `fk_product` FOREIGN KEY (`prod_id`) REFERENCES `product` (`prod_id`);

--
-- Constraints for table `requested`
--
ALTER TABLE `requested`
  ADD CONSTRAINT `fk_req` FOREIGN KEY (`req_id`) REFERENCES `requisition` (`req_id`),
  ADD CONSTRAINT `req_prod` FOREIGN KEY (`prod_id`) REFERENCES `product` (`prod_id`);

--
-- Constraints for table `requisition`
--
ALTER TABLE `requisition`
  ADD CONSTRAINT `req_emp` FOREIGN KEY (`emp_id`) REFERENCES `employee` (`emp_id`),
  ADD CONSTRAINT `req_sup` FOREIGN KEY (`sup_id`) REFERENCES `supplier` (`sup_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

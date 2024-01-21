-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Generation Time: Jan 20, 2024 at 11:40 PM
-- Server version: 11.1.2-MariaDB-1:11.1.2+maria~ubu2204
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `developmentdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `Ads`
--

CREATE TABLE `Ads` (
                       `id` int(11) NOT NULL,
                       `productName` varchar(255) NOT NULL,
                       `productDescription` varchar(1000) NOT NULL,
                       `productPrice` decimal(10,2) NOT NULL,
                       `postedDate` date NOT NULL DEFAULT current_timestamp(),
                       `productImageURI` varchar(1000) NOT NULL,
                       `productStatus` enum('Available','Sold') NOT NULL DEFAULT 'Available',
                       `userID` int(11) NOT NULL,
                       `buyerID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Ads`
--

INSERT INTO `Ads` (`id`, `productName`, `productDescription`, `productPrice`, `postedDate`, `productImageURI`, `productStatus`, `userID`, `buyerID`) VALUES
                                                                                                                                                         (32, 'Iphone 12 pro max', 'Apple Iphone 12 pro max in White colour and new condition.', 850.00, '2024-01-13', '/images/iphone12.jpg', 'Sold', 1, NULL),
                                                                                                                                                         (33, 'LMAX bicycle', 'Green coloured beautiful bicycle', 450.00, '2024-01-13', '/images/bicycle.jpg', 'Sold', 1, NULL),
                                                                                                                                                         (34, 'Rich Dad Poor Dad', 'Rich Dad Poor Dad is a 1997 book written by Robert T. Kiyosaki and Sharon Lechter.', 30.00, '2024-01-15', '/images/book.jpg', 'Sold', 2, 1),
                                                                                                                                                         (35, 'Nike Air Mags', 'Electroluminescent outsole, space-age materials, rechargeable internal battery for 3,000 hours.', 15000.00, '2024-01-15', '/images/nike.jpg', 'Available', 2, NULL),
                                                                                                                                                         (36, 'Apple MacBook Pro 14 2023', 'MacBook Pro 14 and the new M2 Pro offers better performance, and fast WLAN is finally supported.', 3000.00, '2024-01-16', '/images/macbook.jpg', 'Sold', 1, NULL),
                                                                                                                                                         (37, 'Airpods Pro', 'Apple Airpods Pro Gen 2, the best airpods out there with good sound quality.', 200.00, '2024-01-19', '/images/airpods.jpg', 'Sold', 1, NULL),
                                                                                                                                                         (38, 'BMW S1000', 'BMW S1000 Motorbike, very good looking totally new. No scratches.', 20000.00, '2024-01-19', '/images/BMW.jpg', 'Available', 2, NULL),
                                                                                                                                                         (39, 'Samsung Galaxy S23 Ultra', 'Samsung Galaxy S23 Ultra, Gold/Bronze colour phone  totally new. ', 1000.00, '2024-01-19', '/images/galaxy.jpg', 'Sold', 2, 3),
                                                                                                                                                         (40, 'Samsung Galaxy Note 20', 'Samsung Galaxy Note 20, Bronze color very good condition totally new.', 850.00, '2024-01-19', '/images/note20.jpg', 'Sold', 2, 5),
                                                                                                                                                         (45, 'Table', 'A wooden family table', 150.00, '2024-01-20', '/images/EZM-2024-01-20-Muhammad.Table-jpg', 'Available', 1, NULL),
                                                                                                                                                         (48, 'Iphone 11', 'Iphone 11, Green Colour. Good condition', 650.00, '2024-01-20', '/images/EZM-2024-01-20-Muhammad.jpg', 'Available', 1, NULL),
                                                                                                                                                         (49, 'Pathe Film Voucher', 'SpiderMan-I want to go home. Movie ticket.', 20.00, '2024-01-20', '/images/EZM-2024-01-20-Muhammad.Pathe Film Voucher-jpg', 'Sold', 1, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Ads`
--
ALTER TABLE `Ads`
    ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Ads`
--
ALTER TABLE `Ads`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Generation Time: Jan 21, 2024 at 02:45 PM
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
                                                                                                                                                         (32, 'Iphone 12 pro max', 'Apple Iphone 12 pro max in White colour and new condition.', 850.00, '2024-01-13', '/images/iphone12.jpg', 'Available', 1, NULL),
                                                                                                                                                         (33, 'LMAX bicycle', 'Green coloured beautiful bicycle', 450.00, '2024-01-13', '/images/bicycle.jpg', 'Available', 1, NULL),
                                                                                                                                                         (34, 'Rich Dad Poor Dad', 'Rich Dad Poor Dad is a 1997 book written by Robert T. Kiyosaki and Sharon Lechter.', 30.00, '2024-01-15', '/images/book.jpg', 'Sold', 2, 1),
                                                                                                                                                         (35, 'Nike Air Mags', 'Electroluminescent outsole, space-age materials, rechargeable internal battery for 3,000 hours and more.\n', 15000.00, '2024-01-15', '/images/nike.jpg', 'Sold', 2, 1),
                                                                                                                                                         (36, 'Apple MacBook Pro 14 2023', 'MacBook Pro 14 and the new M2 Pro offers better performance, and fast WLAN is finally supported.', 3000.00, '2024-01-16', '/images/macbook.jpg', 'Available', 3, NULL),
                                                                                                                                                         (37, 'Airpods Pro', 'Apple Airpods Pro Gen 2, the best airpods out there with good sound quality.', 200.00, '2024-01-19', '/images/airpods.jpg', 'Available', 2, NULL),
                                                                                                                                                         (38, 'BMW S1000', 'BMW S1000 Motorbike, very good looking totally new. No scratches beautiful colour.', 30000.00, '2024-01-19', '/images/BMW.jpg', 'Sold', 2, 3),
                                                                                                                                                         (39, 'Samsung Galaxy S23 Ultra', 'Samsung Galaxy S23 Ultra, Gold/Bronze colour phone  totally new. ', 1000.00, '2024-01-19', '/images/galaxy.jpg', 'Sold', 2, 3),
                                                                                                                                                         (40, 'Samsung Galaxy Note 20', 'Samsung Galaxy Note 20, Bronze color very good condition totally new.', 850.00, '2024-01-19', '/images/note20.jpg', 'Sold', 2, 5),
                                                                                                                                                         (48, 'Iphone 11', 'Iphone 11, Green Colour. Good condition', 650.00, '2024-01-20', '/images/EZM-2024-01-20-Muhammad.jpg', 'Sold', 1, 3),
                                                                                                                                                         (49, 'Pathe Film Voucher', 'SpiderMan-I want to go home. Movie ticket.', 20.00, '2024-01-20', '/images/EZM-2024-01-20-Muhammad.Pathe Film Voucher-jpg', 'Sold', 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `User`
--

CREATE TABLE `User` (
                        `userID` int(11) NOT NULL,
                        `firstName` varchar(255) NOT NULL,
                        `lastName` varchar(255) NOT NULL,
                        `email` varchar(255) NOT NULL,
                        `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `User`
--

INSERT INTO `User` (`userID`, `firstName`, `lastName`, `email`, `password`) VALUES
                                                                                (1, 'Muhammad', 'Faizan', 'faizan@inholland.nl', '$2y$10$cSYU3woXgACVnRYAH.eadek3ykVKCwL4/eMYd1Kh6v9ctfPuB3P6q'),
                                                                                (2, 'John', 'Wick', 'john@wick.com', '$2y$10$FfwQku3UoFmo0zATmMx64uJu2C.0ZI2y7c3fE3.DZvJfxmugzMe3i'),
                                                                                (3, 'Dawood', 'Ikram', 'dawood@inholland.nl', '$2y$10$qWEk16LvcqndcXQkoHJpYuVvNOLpgjKZpTZEFwsnB4bqU1OItvO1i'),
                                                                                (4, 'Dipika', 'Bhandari', 'dipika@inholland.nl', '$2y$10$zkHzdpEt92b7YzTbx0WU9uZl34XH0EfMZDOthhLxwafp4kbdqR1cO'),
                                                                                (5, 'Ayaz', 'Vahid', 'ayaz@such.nl', '$2y$10$pIX1tGVtma5LuOrqvS89i.cyotUXzDHDd6lPx0fOPZpsZ5GJU3pOq'),
                                                                                (12, 'Fatame', 'Sadat', 'fatame@inholland.nl', '$2y$10$ESe0.iObxrwvFMLK1kqcbODLFT/U2KSgTmMCrZOOs.gxIzoK5cAIu');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Ads`
--
ALTER TABLE `Ads`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `User`
--
ALTER TABLE `User`
    ADD PRIMARY KEY (`userID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Ads`
--
ALTER TABLE `Ads`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `User`
--
ALTER TABLE `User`
    MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

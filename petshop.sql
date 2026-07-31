-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 30, 2025 at 06:16 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `petshop`
--
CREATE DATABASE IF NOT EXISTS `petshop` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `petshop`;

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `c_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`c_id`, `name`, `image`) VALUES
(1, 'อาหารหมา', 'images/D.png'),
(2, 'อาหารแมว', 'images/C.png'),
(3, 'อาหารหนูแฮมสเตอร์', 'images/B.png'),
(4, 'อาหารกระต่าย', 'images/R.png'),
(5, 'อุปกรณ์สัตว์เลี้ยง', 'images/A.png');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','shipped','completed') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `fullname`, `phone`, `address`, `total_price`, `created_at`, `status`) VALUES
(1, 2, 'รมิดา กนกฆะนะรัชต์', '0643296226', '571 ม.1 ต.บรบือ อ.บรบือ จ.มหาสารคาม 44130', 8470.00, '2025-09-21 17:56:02', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`item_id`, `order_id`, `product_name`, `price`, `quantity`) VALUES
(1, 1, 'อาหารสุนัข Smartheart Adult Roast Beef', 55.00, 1),
(2, 1, 'อาหารสุนัข Royal Canin Mini Adult', 70.00, 1),
(3, 1, 'อาหารสุนัขโต แบบแห้ง PEDIGREE', 65.00, 1),
(4, 1, 'คอนโดแมว Catry รุ่น Ice Cream', 3990.00, 1),
(5, 1, 'คอกกั้นสุนัข ไซส์ L', 2890.00, 1),
(6, 1, 'บ้านสุนัขพลาสติก ขนาดกลาง', 1400.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `p_id` int(11) NOT NULL,
  `c_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `stock` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`p_id`, `c_id`, `name`, `price`, `image`, `description`, `created_at`, `stock`) VALUES
(1, 1, 'อาหารสุนัข Smartheart Adult Roast Beef', 55.00, 'foodD/1.jpg', 'อาหารสุนัขสูตร Roast Beef ขนาด 20kg', '2025-09-18 23:22:04', 99),
(2, 1, 'อาหารสุนัขโต แบบแห้ง PEDIGREE', 65.00, 'foodD/2.jpg', 'อาหารสุนัขโต แบบแห้ง Pedigree 20kg', '2025-09-18 23:22:04', 99),
(3, 1, 'อาหารสุนัข Royal Canin Mini Adult', 70.00, 'foodD/3.jpg', 'อาหารสุนัข Royal Canin Mini Adult 8+', '2025-09-18 23:22:04', 92),
(4, 1, 'อาหารสุนัขทุกสายพันธุ์ Prowild', 80.00, 'foodD/4.jpg', 'Prowild Deep Sea Recipe', '2025-09-18 23:22:04', 100),
(5, 1, 'อาหารสุนัข DOG n joy Complete', 90.00, 'foodD/5.jpg', 'อาหารสุนัข DOG n joy Complete สูตรสมดุล', '2025-09-18 23:22:04', 100),
(6, 1, 'อาหารสุนัข Hill’s Science Diet Puppy Small & Mini', 100.00, 'foodD/6.jpg', 'อาหารลูกสุนัข Small & Mini', '2025-09-18 23:22:04', 100),
(7, 1, 'อาหารสุนัข LuvCare Puppy Small Breed 9kg', 120.00, 'foodD/7.jpg', 'อาหารลูกสุนัขสายพันธุ์เล็ก LuvCare 9kg', '2025-09-18 23:22:04', 100),
(8, 1, 'อาหารสุนัข Buzz Beyond', 150.00, 'foodD/8.jpg', 'อาหารสุนัข Buzz Beyond สูตรเนื้อแกะ', '2025-09-18 23:22:04', 100),
(9, 1, 'อาหารสุนัข Supercoat', 160.00, 'foodD/9.jpg', 'อาหารสุนัข Supercoat Small Breed Adult', '2025-09-18 23:22:04', 100),
(10, 1, 'อาหารสุนัข JerHigh', 200.00, 'foodD/10.jpg', 'JerHigh Meat Meals สูตร Mini', '2025-09-18 23:22:04', 100),
(11, 2, 'อาหารแมว Buzz Premium Advance Nutrition', 309.00, 'foodC/1.jpg', 'อาหารแมวสูตร Hair & Skin', '2025-09-18 23:22:17', 100),
(12, 2, 'อาหารแมว Royal Canin Hairball Care 400g', 149.00, 'foodC/2.webp', 'อาหารแมว Royal Canin สูตรลดก้อนขน', '2025-09-18 23:22:17', 100),
(13, 2, 'อาหารแมว Kaniva ชนิดเม็ด', 209.00, 'foodC/3.webp', 'อาหารแมว Kaniva ครบคุณค่า', '2025-09-18 23:22:17', 100),
(14, 2, 'อาหารแมว Whiskas Dry Cat', 119.00, 'foodC/4.webp', 'Whiskas อาหารแมวชนิดแห้ง', '2025-09-18 23:22:17', 100),
(15, 2, 'อาหารแมว Purina Friskies', 119.00, 'foodC/5.webp', 'อาหารแมว Purina Friskies', '2025-09-18 23:22:17', 100),
(16, 2, 'อาหารแมว Taste of The Wild Cat', 495.00, 'foodC/6.webp', 'อาหารแมว Taste of The Wild ทุกช่วงวัย', '2025-09-18 23:22:17', 100),
(17, 2, 'อาหารแมว Hill’s Science Diet 1-6 ปี', 559.00, 'foodC/7.webp', 'อาหารแมว Hill’s Science Diet สูตรไก่', '2025-09-18 23:22:17', 100),
(18, 2, 'อาหารแมว Purina ONE Indoor Advantage', 225.00, 'foodC/8.webp', 'อาหารแมวสูตร Indoor Advantage', '2025-09-18 23:22:17', 100),
(19, 2, 'อาหารแมว MAXIMA CAT MAINTENANCE', 195.00, 'foodC/9.webp', 'อาหารแมวเนื้อบดคุณภาพสูง', '2025-09-18 23:22:17', 100),
(20, 2, 'อาหารแมว Clover ทุกช่วงวัย 1.5kg', 455.00, 'foodC/10.webp', 'อาหารแมว Clover สำหรับแมวทุกวัย', '2025-09-18 23:22:17', 100),
(21, 3, 'อาหารหนู Smartheart Gold Select Muesli', 120.00, 'foodH/1.jpg', 'อาหารหนูแฮมสเตอร์ สูตรโกลด์ มูสลี่', '2025-09-18 23:22:27', 100),
(22, 3, 'อาหารหนู Crispy Muesli Hamster', 150.00, 'foodH/2.jpg', 'อาหารหนูแฮมสเตอร์ สูตรมูสลี่', '2025-09-18 23:22:27', 100),
(23, 3, 'อาหารหนู GEX Premium Food', 260.00, 'foodH/3.jpg', 'อาหารหนู GEX สูตร Premium สำหรับ Dwarf', '2025-09-18 23:22:27', 100),
(24, 3, 'อาหารหนู Hamster Nature', 230.00, 'foodH/4.jpg', 'อาหารหนู Hamster Nature ครบถ้วนสารอาหาร', '2025-09-18 23:22:27', 100),
(25, 3, 'อาหารหนู SmartHeart Hamster Treat', 140.00, 'foodH/5.jpg', 'ขนมหนูแฮมสเตอร์ SmartHeart Treat', '2025-09-18 23:22:27', 100),
(26, 3, 'อาหารหนู Beaphar Care+ Dwarf Hamster', 330.00, 'foodH/6.jpg', 'อาหารหนู Beaphar Care+ สูตร Dwarf', '2025-09-18 23:22:27', 100),
(27, 3, 'อาหารหนู Bucataste สูตร H1', 169.00, 'foodH/7.jpg', 'อาหารหนูแฮมสเตอร์ Bucataste H1', '2025-09-18 23:22:27', 100),
(28, 3, 'อาหารหนู Puur Mini Hamster', 119.00, 'foodH/8.jpg', 'อาหารหนู Mini Hamster Puur', '2025-09-18 23:22:27', 100),
(29, 3, 'อาหารหนู BUDDY HAMSTER & GERBIL', 75.00, 'foodH/9.jpg', 'อาหารหนู Buddy Hamster & Gerbil', '2025-09-18 23:22:27', 100),
(30, 3, 'อาหารหนู BokDok Hamster Food', 45.00, 'foodH/10.jpg', 'อาหารหนูแฮมสเตอร์ BokDok', '2025-09-18 23:22:27', 100),
(31, 4, 'อาหารกระต่าย Smartheart ผักและธัญพืช', 119.00, 'foodR/1.jpg', 'Smartheart สูตรผักและธัญพืช', '2025-09-18 23:22:37', 100),
(32, 4, 'อาหารกระต่าย Rabbit Diet', 99.00, 'foodR/2.jpg', 'Rabbit Diet อาหารกระต่ายครบถ้วน', '2025-09-18 23:22:37', 100),
(33, 4, 'อาหารกระต่าย Oxbow สำหรับเด็ก', 435.00, 'foodR/3.jpg', 'Oxbow Young Rabbit Food', '2025-09-18 23:22:37', 100),
(34, 4, 'อาหารกระต่าย เอโปร ไอดี ฟอร์มูล่า', 170.00, 'foodR/4.jpg', 'อาหารกระต่าย สูตรควบคุมกลิ่น', '2025-09-18 23:22:37', 100),
(35, 4, 'อาหารกระต่าย RANDOLPH BUNNY SENIOR', 295.00, 'foodR/5.webp', 'อาหารกระต่ายแก่ Randolph Bunny Senior', '2025-09-18 23:22:37', 100),
(36, 4, 'อาหารกระต่าย Beaphar XtraVital', 210.00, 'foodR/6.jpg', 'อาหารกระต่าย Beaphar XtraVital', '2025-09-18 23:22:37', 100),
(37, 4, 'อาหารกระต่าย Care+ 250g', 290.00, 'foodR/7.jpg', 'อาหารกระต่าย Care+ ขนาด 250 กรัม', '2025-09-18 23:22:37', 100),
(38, 4, 'อาหารกระต่าย RANDOLPH RABBIT SHOW', 245.00, 'foodR/8.webp', 'อาหารกระต่ายสูตรบำรุงขน', '2025-09-18 23:22:37', 100),
(39, 5, 'บ้านสุนัขพลาสติก ขนาดกลาง', 1400.00, 'As/1.webp', 'บ้านสุนัขพลาสติก 60x74.5x66 ซม.', '2025-09-18 23:22:45', 99),
(40, 5, 'คอกกั้นสุนัข ไซส์ L', 2890.00, 'As/2.webp', 'คอกกั้นสุนัข สีเทา/ขาว ขนาดใหญ่', '2025-09-18 23:22:45', 99),
(41, 5, 'น้ำพุแมว CATIT Stainless 3 ลิตร', 1290.00, 'As/3.webp', 'น้ำพุแมวสแตนเลส 3 ลิตร', '2025-09-18 23:22:45', 100),
(42, 5, 'คอนโดแมว Catry รุ่น Ice Cream', 3990.00, 'As/4.webp', 'คอนโดแมว Catry Ice Cream', '2025-09-18 23:22:45', 99),
(43, 5, 'ปลอกคอแมว KAFBO POM', 290.00, 'As/5.webp', 'ปลอกคอแมว KAFBO POM สี KIWI', '2025-09-18 23:22:45', 100),
(44, 5, 'กรรไกรตัดเล็บสัตว์เลี้ยง THAI SUN SPORT', 450.00, 'As/6.png', 'กรรไกรตัดเล็บสัตว์เลี้ยง รุ่น PET0019', '2025-09-18 23:22:45', 100),
(45, 5, 'ถุงกันข่วน THAI SUN SPORT', 450.00, 'As/7.webp', 'ถุงกันข่วนสำหรับอาบน้ำสัตว์เลี้ยง', '2025-09-18 23:22:45', 100),
(46, 5, 'SOOS Wooden Exercise Wheel', 500.00, 'As/8.webp', 'วงล้อของเล่นไม้สำหรับหนูแฮมสเตอร์', '2025-09-18 23:22:45', 100),
(47, 5, 'TBD Hamster Water Bottle & Food Bowl', 149.00, 'As/9.webp', 'ขวดน้ำและชามอาหารสำหรับหนู', '2025-09-18 23:22:45', 100),
(48, 5, 'Pet Carrier Box กล่องขนส่งสัตว์เลี้ยง', 799.00, 'As/10.jpg', 'กล่องขนส่งสัตว์เลี้ยง ขนาด 32x48x35 ซม.', '2025-09-18 23:22:45', 100);

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image_path`) VALUES
(1, 1, 'foodD/1.jpg'),
(2, 1, 'foodD/1.2.webp'),
(3, 1, 'foodD/1.3.webp'),
(4, 2, 'foodD/2.jpg'),
(5, 2, 'foodD/2.1.webp'),
(6, 2, 'foodD/2.2.webp'),
(7, 3, 'foodD/3.jpg'),
(8, 3, 'foodD/3.1.jpg'),
(9, 3, 'foodD/3.2.jpg'),
(10, 4, 'foodD/4.jpg'),
(11, 4, 'foodD/4.1.webp'),
(12, 4, 'foodD/4.2.webp'),
(13, 5, 'foodD/5.jpg'),
(14, 5, 'foodD/5.1.webp'),
(15, 5, 'foodD/5.2.webp'),
(16, 6, 'foodD/6.jpg'),
(17, 6, 'foodD/6.1.webp'),
(18, 6, 'foodD/6.2.webp'),
(19, 7, 'foodD/7.jpg'),
(20, 7, 'foodD/7.1.webp'),
(21, 7, 'foodD/7.2.webp'),
(22, 8, 'foodD/8.jpg'),
(23, 8, 'foodD/8.1.webp'),
(24, 8, 'foodD/8.2.webp'),
(25, 9, 'foodD/9.jpg'),
(26, 9, 'foodD/9.1.webp'),
(27, 9, 'foodD/9.2.webp'),
(28, 10, 'foodD/10.jpg'),
(29, 10, 'foodD/10.1.jpg'),
(30, 10, 'foodD/10.2.webp'),
(31, 11, 'foodC/1.jpg'),
(32, 11, 'foodC/1.1.webp'),
(33, 11, 'foodC/1.2.webp'),
(34, 12, 'foodC/2.webp'),
(35, 12, 'foodC/2.1.jpg'),
(36, 12, 'foodC/2.2.jpg'),
(37, 13, 'foodC/3.webp'),
(38, 13, 'foodC/3.1.webp'),
(39, 13, 'foodC/3.2.webp'),
(40, 14, 'foodC/4.webp'),
(41, 14, 'foodC/4.1.jpg'),
(42, 14, 'foodC/4.2.webp'),
(43, 15, 'foodC/5.webp'),
(44, 15, 'foodC/5.1.webp'),
(45, 15, 'foodC/5.2.webp'),
(46, 16, 'foodC/6.webp'),
(47, 16, 'foodC/6.1.webp'),
(48, 16, 'foodC/6.2.webp'),
(49, 17, 'foodC/7.webp'),
(50, 17, 'foodC/7.1.webp'),
(51, 17, 'foodC/7.2.webp'),
(52, 18, 'foodC/8.webp'),
(53, 18, 'foodC/8.1.webp'),
(54, 18, 'foodC/8.2.webp'),
(55, 19, 'foodC/9.webp'),
(56, 19, 'foodC/9.1.webp'),
(57, 19, 'foodC/9.2.webp'),
(58, 20, 'foodC/10.webp'),
(59, 20, 'foodC/10.1.webp'),
(60, 20, 'foodC/10.2.webp'),
(61, 21, 'foodH/1.jpg'),
(62, 21, 'foodH/1.1.webp'),
(63, 21, 'foodH/1.2.webp'),
(64, 22, 'foodH/2.jpg'),
(65, 22, 'foodH/2.1.webp'),
(66, 22, 'foodH/2.2.webp'),
(67, 23, 'foodH/3.jpg'),
(68, 23, 'foodH/3.1.webp'),
(69, 23, 'foodH/3.2.webp'),
(70, 24, 'foodH/4.jpg'),
(71, 24, 'foodH/4.1.webp'),
(72, 24, 'foodH/4.2.webp'),
(73, 25, 'foodH/5.jpg'),
(74, 25, 'foodH/5.1.jpg'),
(75, 25, 'foodH/5.2.jpg'),
(76, 26, 'foodH/6.jpg'),
(77, 26, 'foodH/6.1.webp'),
(78, 26, 'foodH/6.2.webp'),
(79, 27, 'foodH/7.jpg'),
(80, 27, 'foodH/7.1.webp'),
(81, 27, 'foodH/7.2.webp'),
(82, 28, 'foodH/8.jpg'),
(83, 28, 'foodH/8.1.webp'),
(84, 28, 'foodH/8.2.webp'),
(85, 29, 'foodH/9.jpg'),
(86, 29, 'foodH/9.1.webp'),
(87, 29, 'foodH/9.2.webp'),
(88, 30, 'foodH/10.jpg'),
(89, 30, 'foodH/10.1.webp'),
(90, 30, 'foodH/10.2.webp'),
(91, 31, 'foodR/1.jpg'),
(92, 31, 'foodR/1.1.webp'),
(93, 31, 'foodR/1.2.webp'),
(94, 32, 'foodR/2.jpg'),
(95, 32, 'foodR/2.1.webp'),
(96, 32, 'foodR/2.2.webp'),
(97, 33, 'foodR/3.jpg'),
(98, 33, 'foodR/3.1.webp'),
(99, 33, 'foodR/3.2.webp'),
(100, 34, 'foodR/4.jpg'),
(101, 34, 'foodR/4.1.webp'),
(102, 34, 'foodR/4.2.webp'),
(103, 35, 'foodR/5.webp'),
(104, 35, 'foodR/5.1.webp'),
(105, 35, 'foodR/5.2.webp'),
(106, 36, 'foodR/6.jpg'),
(107, 36, 'foodR/6.1.webp'),
(108, 36, 'foodR/6.2.webp'),
(109, 37, 'foodR/7.jpg'),
(110, 37, 'foodR/7.1.webp'),
(111, 37, 'foodR/7.2.webp'),
(112, 38, 'foodR/8.webp'),
(113, 38, 'foodR/8.1.webp'),
(114, 38, 'foodR/8.2.webp'),
(115, 39, 'AS/1.webp'),
(116, 39, 'AS/1.1.webp'),
(117, 39, 'AS/1.2.webp'),
(118, 40, 'AS/2.webp'),
(119, 40, 'AS/2.1.webp'),
(120, 40, 'AS/2.2.webp'),
(121, 41, 'AS/3.webp'),
(122, 41, 'AS/3.1.webp'),
(123, 41, 'AS/3.2.webp'),
(124, 42, 'AS/4.webp'),
(125, 42, 'AS/4.1.webp'),
(126, 42, 'AS/4.2.webp'),
(127, 43, 'AS/5.webp'),
(128, 43, 'AS/5.1.webp'),
(129, 43, 'AS/5.2.webp'),
(130, 44, 'AS/6.png'),
(131, 44, 'AS/6.1.webp'),
(132, 44, 'AS/6.2.webp'),
(133, 45, 'AS/7.webp'),
(134, 45, 'AS/7.1.webp'),
(135, 45, 'AS/7.2.webp'),
(136, 46, 'AS/8.webp'),
(137, 46, 'AS/8.1.webp'),
(138, 46, 'AS/8.2.webp'),
(139, 47, 'AS/9.webp'),
(140, 47, 'AS/9.1.webp'),
(141, 47, 'AS/9.2.webp'),
(142, 48, 'AS/10.jpg'),
(143, 48, 'AS/10.1.webp'),
(144, 48, 'AS/10.2.webp');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `birthday` date DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `address1` varchar(255) DEFAULT NULL,
  `address2` varchar(255) DEFAULT NULL,
  `zipcode` varchar(10) DEFAULT NULL,
  `delivery_phone` varchar(20) DEFAULT NULL,
  `interest` text DEFAULT NULL,
  `pet_name` varchar(100) DEFAULT NULL,
  `newsletter` enum('Yes','No') DEFAULT 'No',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `phone`, `email`, `birthday`, `username`, `password`, `address1`, `address2`, `zipcode`, `delivery_phone`, `interest`, `pet_name`, `newsletter`, `created_at`) VALUES
(1, 'แพรวา ริโยธา', '0647485978', '66010916042@msu.ac.th', '2005-03-21', 'pearwa', '$2y$10$rjA/ypfLniD7.fPUYLSlSe/xId9rzCV325TugjWXm.6PGrSAZaiYu', '111 ม.1 ', 'ต.ในเมือง อ.เมือง จ.ขอนแก่น', '40000', '0647485978', 'cat', 'จิบิโก๊ะ', 'Yes', '2025-09-17 09:00:26'),
(2, 'รมิดา กนกฆะนะรัชต์', '0643296226', '66010916019@msu.ac.th', '2004-01-06', 'kwang123', '$2y$10$lxPoliJpS9jH06iQA3yslO.G6oBZqdm.FlimkUT9baAj05FwQ0wVm', '571 ม.1', 'ต.บรบือ อ.บรบือ จ.มหาสารคาม', '44130', '064329626', 'dog', 'แพนด้า หมูมัน', 'Yes', '2025-09-17 09:02:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`c_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`p_id`),
  ADD KEY `fk_products_category` (`c_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `c_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `p_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=165;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`p_id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_category` FOREIGN KEY (`c_id`) REFERENCES `category` (`c_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`p_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 10, 2026 at 03:52 AM
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
-- Database: `ecommerce_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`id`, `user_id`, `product_id`, `quantity`, `added_at`) VALUES
(4, 2, 1, 2, '2026-02-04 02:22:12'),
(5, 2, 2, 2, '2026-02-04 02:50:18'),
(6, 3, 1, 3, '2026-02-25 01:02:26'),
(7, 3, 3, 1, '2026-02-25 01:03:50'),
(8, 3, 4, 10, '2026-02-25 01:04:01'),
(9, 3, 2, 5, '2026-02-25 01:05:33'),
(10, 3, 6, 1, '2026-02-25 04:29:51'),
(11, 3, 5, 1, '2026-02-25 05:50:08'),
(15, 5, 2, 1, '2026-04-10 00:57:20'),
(16, 5, 1, 1, '2026-04-10 00:57:32');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `is_active`, `created_at`) VALUES
(1, 'Electronics', 'electronics', 1, '2026-01-28 04:26:12'),
(2, 'Fashion', 'fashion', 1, '2026-01-28 04:26:12'),
(3, 'Home & Kitchen', 'home-kitchen', 1, '2026-01-28 04:26:12'),
(4, 'Books', 'books', 1, '2026-01-28 04:26:12'),
(5, 'Sports', 'sports', 1, '2026-01-28 04:26:12');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_number` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `slug` varchar(200) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `stock_quantity` int(11) DEFAULT 0,
  `main_image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `slug`, `description`, `price`, `category_id`, `stock_quantity`, `main_image`, `is_active`, `created_at`) VALUES
(1, 'iPhone 14 Pro', 'iphone-14-pro', 'Latest iPhone with advanced camera', 19999000.00, 1, 50, 'iphone14.jpeg', 1, '2026-01-28 04:26:12'),
(2, 'Samsung Galaxy S23', 'samsung-galaxy-s23', 'Powerful Android smartphone', 15999000.00, 1, 30, 'samsunggalaxy23.jpeg', 1, '2026-01-28 04:26:12'),
(3, 'Nike Air Max 270', 'nike-air-max-270', 'Comfortable running shoes', 2500000.00, 2, 100, 'nikeairmax270.jpg', 1, '2026-01-28 04:26:12'),
(4, 'MacBook Pro M2', 'macbook-pro-m2', 'Professional laptop for creators', 29999000.00, 1, 20, 'macbookprom2.jpeg', 1, '2026-01-28 04:26:12'),
(5, 'The Psychology of Money', 'psychology-of-money', 'Best-selling financial book', 150000.00, 4, 200, 'book.jpeg', 1, '2026-01-28 04:26:12'),
(6, 'Kitchen Set 5 Pcs', 'kitchen-set-5pcs', 'Complete kitchen utensil set', 500000.00, 3, 75, 'kitchenset.jpeg', 1, '2026-01-28 04:26:12'),
(7, 'Football Nike Premier', 'football-nike-premier', 'Official match football', 350000.00, 5, 40, 'Footballnikepremier.jpeg', 1, '2026-01-28 04:26:12'),
(8, 'Levi\'s Jeans 501', 'levis-jeans-501', 'Classic denim jeans', 1200000.00, 2, 60, 'levijeans501.jpeg', 1, '2026-01-28 04:26:12');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `full_name`, `created_at`) VALUES
(1, 'admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin User', '2026-01-28 04:26:12'),
(2, 'dan', 'afdan6567@gmail.com', '$2y$10$dbF8JCksUaRG1E9pIMDoAOa.T4qo.fvJKprn.4vj69E569UeVcuIS', 'afdan', '2026-02-04 02:21:51'),
(3, 'aaa', 'aaa@kontol', '$2y$10$o1exFHseoJLeqp.uklHW9OEMCg9.OLPkZCW2ikvUXZnMvENWEd0nm', 'aaa', '2026-02-25 01:01:53'),
(4, 'afdan', 'afdanarrasyd@gmail.com', '$2y$10$E7cYBO6ydSVbSHy45G6cUuL2bkhB7qWqktcjrVaejvTKQmPhVVPWu', 'afdan', '2026-04-08 01:51:44'),
(5, 'el', 'hafiszafauzi@gmail.com', '$2y$10$PMIPb.ewbLLmsUi.Qqlwt.F6Vg.fa6ADuN.QJlrPcJSpVDETy1DKe', 'leon', '2026-04-10 00:54:50');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_number` (`order_number`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `carts_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

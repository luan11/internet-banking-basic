-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 05-Maio-2019 às 10:09
-- Versão do servidor: 10.1.36-MariaDB
-- versão do PHP: 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ib-basic`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `ibb_transacts`
--

CREATE TABLE `ibb_transacts` (
  `id_ibbTransacts` int(11) NOT NULL,
  `action_ibbTransacts` varchar(255) NOT NULL,
  `date_ibbTransacts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `value_ibbTransacts` decimal(10,2) NOT NULL,
  `ip_ibbTransacts` varchar(100) NOT NULL,
  `userId_ibbTransacts` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `ibb_users`
--

CREATE TABLE `ibb_users` (
  `id_ibbUsers` int(11) NOT NULL,
  `firstName_ibbUsers` varchar(255) NOT NULL,
  `lastName_ibbUsers` varchar(255) NOT NULL,
  `email_ibbUsers` varchar(100) NOT NULL,
  `account_ibbUsers` varchar(10) NOT NULL,
  `password_ibbUsers` varchar(60) NOT NULL,
  `balance_ibbUsers` decimal(10,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `ibb_users`
--

INSERT INTO `ibb_users` (`id_ibbUsers`, `firstName_ibbUsers`, `lastName_ibbUsers`, `email_ibbUsers`, `account_ibbUsers`, `password_ibbUsers`, `balance_ibbUsers`) VALUES
(1, 'Admin', ' ', 'luaannovais@live.com', 'admin_luan', '$2y$10$yrD3kYhEeeq.Ezk4w8/89O7Mpt7gorxF4E4XXXQyTHFVIWJSvvgr.', '1000000.00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ibb_transacts`
--
ALTER TABLE `ibb_transacts`
  ADD PRIMARY KEY (`id_ibbTransacts`),
  ADD KEY `userId_ibbTransacts` (`userId_ibbTransacts`);

--
-- Indexes for table `ibb_users`
--
ALTER TABLE `ibb_users`
  ADD PRIMARY KEY (`id_ibbUsers`),
  ADD UNIQUE KEY `account_ibbUsers` (`account_ibbUsers`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ibb_transacts`
--
ALTER TABLE `ibb_transacts`
  MODIFY `id_ibbTransacts` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ibb_users`
--
ALTER TABLE `ibb_users`
  MODIFY `id_ibbUsers` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `ibb_transacts`
--
ALTER TABLE `ibb_transacts`
  ADD CONSTRAINT `ibb_transacts_ibfk_1` FOREIGN KEY (`userId_ibbTransacts`) REFERENCES `ibb_users` (`id_ibbUsers`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

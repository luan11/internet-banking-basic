-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: 18-Maio-2019 às 22:42
-- Versão do servidor: 10.3.14-MariaDB
-- versão do PHP: 7.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `id9518470_ibbasic`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `ibb_transacts`
--

CREATE TABLE `ibb_transacts` (
  `id_ibbTransacts` int(11) NOT NULL,
  `action_ibbTransacts` varchar(255) NOT NULL,
  `date_ibbTransacts` timestamp NOT NULL DEFAULT current_timestamp(),
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
  `balance_ibbUsers` decimal(10,2) NOT NULL DEFAULT 0.00,
  `role_ibbUsers` varchar(255) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `ibb_users`
--

INSERT INTO `ibb_users` (`id_ibbUsers`, `firstName_ibbUsers`, `lastName_ibbUsers`, `email_ibbUsers`, `account_ibbUsers`, `password_ibbUsers`, `balance_ibbUsers`, `role_ibbUsers`) VALUES
(1, 'Admin', ' ', 'luaannovais@live.com', 'admin_luan', '$2y$10$yrD3kYhEeeq.Ezk4w8/89O7Mpt7gorxF4E4XXXQyTHFVIWJSvvgr.', '300.00', 'admin'),
(2, 'Conta', 'Teste', 'email@email.com', 'UYRo32xS6v', '$2y$10$PUt6AQsLtXUQ6ljvtyeUSeeSjz3cIMtytHKNH5pz.dMT5MpMKto/i', '150.00', 'user'),
(3, 'Celular', 'Teste', 'teste@cel.com', 'uZZTWmzx6d', '$2y$10$4CRzmcQIRmUwxUFFG8c85.vTsGFP4Rm/WBGT8WTfZg3gPbiGH28va', '541.00', 'user'),
(4, 'Julia', 'Villela', 'teste123@gmail.com', 'Qe6I4na0CY', '$2y$10$EHvhEDui9Ys3vumKNZGcSewSDZOBRgmp0Rcfy4C.IFoAyHhGlZ9Q.', '0.00', 'user'),
(5, 'Ricardo', 'Teste', 'rteste@gmail.com', 'vhFmsLtFXa', '$2y$10$8aIG58t5xsUJCLXmMlBVi.7.fCpofZWmLVVAdLnyxI0OnNdLYgpJe', '250.00', 'user'),
(6, 'Jailson', 'Santos', 'professorobam@hotmail.com', 'phPvGs46Cf', '$2y$10$Lyqp1t1ewz7blBHnaLiXnecFsfcNlqywVk8ZuGlWAF8/AeIb/ZNlO', '75150.00', 'user'),
(8, 'Barack', 'Obama', 'profesorobama@hotmail.com', 'CJ4DjPA2Qc', '$2y$10$pN9a6P2xzSSKzjvlykQ3pOOIen5YixQOWMtzUXXCvljy/ZDVMp9di', '50.00', 'user'),
(9, 'Gianelson', 'Alves', 'gisnake7@hotmail.com', 'zlDo9zCfZN', '$2y$10$zo0eoNdczQMrIIdbqK15QOgeq0JI2i6VD29sCVhx.Zyl4jDBIkg7O', '475000.00', 'user');

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
  MODIFY `id_ibbTransacts` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `ibb_users`
--
ALTER TABLE `ibb_users`
  MODIFY `id_ibbUsers` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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

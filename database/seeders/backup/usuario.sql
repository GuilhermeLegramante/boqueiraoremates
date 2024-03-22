-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 21-Mar-2024 às 16:42
-- Versão do servidor: 10.11.7-MariaDB-cll-lve
-- versão do PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `u670690796_boqueirao`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `idusuario` int(11) DEFAULT NULL,
  `nome` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `login` varchar(20) NOT NULL,
  `senha` varchar(191) NOT NULL,
  `admin` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `usuario`
--

INSERT INTO `users` (`id`, `name`, `email`, `username`, `created_at`, `updated_at`) VALUES
(1, 'Suporte', 'guilhermelegramante@gmail.com', 'guilherme', '2022-12-23 11:43:50', '2022-12-23 11:43:50'),
(2, 'Emerson Hoisler', 'emerson@boqueiraoremates.com', 'emerson', '2022-12-23 11:43:50', '2022-12-23 11:43:50'),
(3, 'Alessandra', 'alessandra@boqueiraoremates.com', 'alessandra', '2022-12-23 11:43:50', '2022-12-23 11:43:50'),
(4, 'Pedro', 'pedro@boqueiraoremates.com', 'pedro', '2022-12-23 11:43:50', '2022-12-23 11:43:50'),
(5, 'Edson Vargas', 'edson@boqueiraoremates.com', 'edson', '2022-12-23 11:43:50', '2022-12-23 11:43:50'),
(6, 'Rakelly Ramos', 'rakelly@boqueiraoremates.com', 'rakelly', '2023-06-06 09:52:42', '2023-06-06 09:52:42'),
(9, 'Rafael Santos Oliveira', 'rafaoliveira2177@gmail.com', 'rafael', '2022-12-23 09:08:35', '2023-06-06 11:01:26');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_idusuario_foreign` (`idusuario`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_idusuario_foreign` FOREIGN KEY (`idusuario`) REFERENCES `usuario` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

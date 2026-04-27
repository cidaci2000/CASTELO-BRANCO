-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 27/04/2026 às 12:34
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `flwrs`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `login`
--

CREATE TABLE `login` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha_hash` varchar(255) NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `ultimo_login` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome_completo` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha_hash` varchar(255) NOT NULL,
  `data_nascimento` date DEFAULT NULL,
  `genero` enum('f','m','outro') DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `cep` varchar(10) DEFAULT NULL,
  `numero` varchar(10) DEFAULT NULL,
  `termos_aceitos` tinyint(1) NOT NULL DEFAULT 0,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `tipo_usuario` varchar(20) DEFAULT 'cliente',
  `ativo` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome_completo`, `email`, `senha_hash`, `data_nascimento`, `genero`, `telefone`, `cep`, `numero`, `termos_aceitos`, `data_cadastro`, `data_atualizacao`, `tipo_usuario`, `ativo`) VALUES
(1, 'JOAO VITOR', 'pati@gmail.com', '$2y$10$GbMmdbCjvFAZO2ihfcUp2.BmgIkHAioC7HR5PZXIDv4BRHQ9YecFy', '2009-05-23', NULL, '4555555555', '85807-670', '25', 1, '2026-04-24 14:12:30', '2026-04-24 14:12:30', 'cliente', 1),
(2, 'admin', 'admin@gmail.com', '$2y$10$FPMA42yZSanu.dxthq/.pOI8Hi03sE0KvBdQR8R7PRd71hHpUvY9m', '1998-02-24', 'f', '(45) 5555-5555', '85807-670', '25', 1, '2026-04-24 14:19:12', '2026-04-27 10:09:50', 'admin', 1),
(3, 'eduarda', 'eduarda@gmail.com', '$2y$10$m1RQgpfI5b/N6AiY2GvZKOtnIBzLzccRuZrmzOT.ejbzdKz8uD9YK', '2009-08-31', 'f', '(45) 5555-5555', '85807-670', '23', 1, '2026-04-24 15:07:04', '2026-04-24 15:07:04', 'cliente', 1);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `login`
--
ALTER TABLE `login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

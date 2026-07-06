-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 06/07/2026 às 15:24
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
-- Banco de dados: `elite`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `agendamentos`
--

CREATE TABLE `agendamentos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `cliente_nome` varchar(100) NOT NULL,
  `cliente_email` varchar(100) NOT NULL,
  `cliente_telefone` varchar(20) NOT NULL,
  `servico` varchar(50) NOT NULL,
  `data` date NOT NULL,
  `horario` time NOT NULL,
  `status` enum('agendado','cancelado','concluido') DEFAULT 'agendado',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `agendamentos`
--

INSERT INTO `agendamentos` (`id`, `usuario_id`, `cliente_nome`, `cliente_email`, `cliente_telefone`, `servico`, `data`, `horario`, `status`, `created_at`) VALUES
(1, 1, 'pedro', 'pedro@gmail.com', '45555555', 'corte', '2026-04-17', '12:00:00', 'cancelado', '2026-04-10 11:39:44'),
(2, 2, 'Igor', 'igor@gmail.com', '45555555', 'combo', '2026-04-22', '11:00:00', 'agendado', '2026-04-10 11:44:45'),
(3, 3, 'JOAO VITOR', 'pedro@gmail.com', '45555555', 'barba', '2026-05-07', '12:00:00', 'agendado', '2026-05-05 13:21:49'),
(4, 4, 'JOAO VITOR', 'pedro@gmail.com', '45555555', 'corte', '2026-05-27', '17:00:00', 'agendado', '2026-05-25 14:49:21'),
(5, 7, 'Pedro Henrique', 'lavinia@gmail.com', '44444444444444', 'corte_barba', '2026-07-08', '14:26:00', 'agendado', '2026-07-06 13:22:22');

-- --------------------------------------------------------

--
-- Estrutura para tabela `barbeiros`
--

CREATE TABLE `barbeiros` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `telefone` varchar(30) DEFAULT NULL,
  `especialidade` varchar(100) DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `configuracoes`
--

CREATE TABLE `configuracoes` (
  `id` int(11) NOT NULL,
  `nome_barbearia` varchar(100) DEFAULT NULL,
  `telefone` varchar(30) DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `horario` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pagamentos`
--

CREATE TABLE `pagamentos` (
  `id` int(11) NOT NULL,
  `agendamento_id` int(11) DEFAULT NULL,
  `valor` decimal(10,2) DEFAULT NULL,
  `forma_pagamento` varchar(50) DEFAULT NULL,
  `data` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `servicos`
--

CREATE TABLE `servicos` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `preco` decimal(10,2) NOT NULL,
  `duracao` int(11) NOT NULL,
  `ativo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `servicos`
--

INSERT INTO `servicos` (`id`, `nome`, `descricao`, `preco`, `duracao`, `ativo`) VALUES
(1, 'Corte Masculino', 'Corte tradicional masculino', 45.00, 30, 1),
(2, 'Barba Completa', 'Barba feita na navalha', 35.00, 25, 1),
(3, 'Corte + Barba', 'Combo completo', 70.00, 55, 1),
(4, 'Pintura de louro', 'kldfjlkdsajfkdj', 120.00, 30, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `tipo` enum('cliente','admin') DEFAULT 'cliente',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `tipo`, `created_at`) VALUES
(1, 'pedro', 'pedro@gmail.com', '$2y$10$QN8OFYDd19XH6L2vSHNGzuBEVye6F06JSVW0thQdOw4IwgyLoo2HC', 'cliente', '2026-04-10 11:38:31'),
(2, 'joao', 'joaovictor@gmail.com', '$2y$10$Fa8.OlR2NcR.eHsvnv6al.ooHE9K5WG1ukDgBKf/b.XzOYTYFKIda', 'cliente', '2026-04-10 11:43:38'),
(3, 'marcel', 'marcel@gmail.com', '$2y$10$ZUnzIL4L4B2.GJUetO9my.3Jd2tpwOY7L4i1IIBvACZFkGPkhrWMW', 'admin', '2026-05-05 13:20:31'),
(4, 'APARECIDA DA SILVA FERREIRA', 'aparecida.ferreira@gmail.com', '$2y$10$sfLvw3VfyHO5rb7u8UqC/.xDABqtlNZYioKlYj50QHSLSLJ.C.s8.', 'cliente', '2026-05-25 14:34:00'),
(5, 'Pati', 'pati@gmail.com', '$2y$10$TNcSfffiA5Jn5TC3Rc67TefB1b./UP52j7RQwJRpjztRa.HDeE0NC', 'cliente', '2026-05-25 14:51:03'),
(6, 'Louise', 'louise@gmail.com', '$2y$10$Fhqwj5xnVIRUvOpLRU0/tul6U8DH5q3cbk7uwdETI9PxQT3IQ21hK', 'cliente', '2026-07-06 12:20:41'),
(7, 'Lavinia', 'lavinia@gmail.com', '$2y$10$ZbxTJjB2q2d2hsdSU5A.uOpn4uMuAPSM95ifaFlYI.VLFfjCaEfV6', 'cliente', '2026-07-06 13:20:34');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `agendamentos`
--
ALTER TABLE `agendamentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `barbeiros`
--
ALTER TABLE `barbeiros`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `configuracoes`
--
ALTER TABLE `configuracoes`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `pagamentos`
--
ALTER TABLE `pagamentos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `servicos`
--
ALTER TABLE `servicos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `agendamentos`
--
ALTER TABLE `agendamentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `barbeiros`
--
ALTER TABLE `barbeiros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `configuracoes`
--
ALTER TABLE `configuracoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pagamentos`
--
ALTER TABLE `pagamentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `servicos`
--
ALTER TABLE `servicos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `agendamentos`
--
ALTER TABLE `agendamentos`
  ADD CONSTRAINT `agendamentos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

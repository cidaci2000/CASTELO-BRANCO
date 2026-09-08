-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 08/09/2026 às 16:29
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
-- Banco de dados: `consultorio`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `agendamentos`
--

CREATE TABLE `agendamentos` (
  `id` int(11) NOT NULL,
  `medico_id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `paciente_nome` varchar(255) NOT NULL,
  `paciente_email` varchar(255) NOT NULL,
  `paciente_telefone` varchar(20) NOT NULL,
  `data_consulta` date NOT NULL,
  `hora_consulta` time NOT NULL,
  `observacoes` text DEFAULT NULL,
  `status` enum('agendado','confirmado','cancelado','realizado') DEFAULT 'agendado',
  `data_agendamento` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `agendamentos`
--

INSERT INTO `agendamentos` (`id`, `medico_id`, `usuario_id`, `paciente_nome`, `paciente_email`, `paciente_telefone`, `data_consulta`, `hora_consulta`, `observacoes`, `status`, `data_agendamento`) VALUES
(1, 2, NULL, 'João Silva', 'joao@email.com', '(11) 98765-4321', '2026-09-09', '10:00:00', 'Primeira consulta - check-up', 'confirmado', '2026-09-08 12:29:06'),
(2, 1, NULL, 'Maria Oliveira', 'maria@email.com', '(11) 91234-5678', '2026-09-10', '14:30:00', 'Exame de rotina', 'agendado', '2026-09-08 12:29:06'),
(3, 3, NULL, 'João Silva', 'joao@email.com', '(11) 98765-4321', '2026-09-03', '09:00:00', 'Consulta pediátrica', 'realizado', '2026-09-08 12:29:06'),
(5, 1, 5, 'JOAO VITOR', 'clara@gmail.com', '455555555', '2026-09-17', '15:16:00', 'teste111', 'agendado', '2026-09-08 14:16:22'),
(6, 5, 6, 'Luani', 'luani@gmail.com', '(45) 55555-5555', '2026-09-10', '15:28:00', '', 'agendado', '2026-09-08 14:28:41');

-- --------------------------------------------------------

--
-- Estrutura para tabela `medicos`
--

CREATE TABLE `medicos` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `especialidade` varchar(255) NOT NULL,
  `crm` varchar(50) NOT NULL,
  `descricao` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `status` enum('ativo','inativo') DEFAULT 'ativo',
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `medicos`
--

INSERT INTO `medicos` (`id`, `nome`, `especialidade`, `crm`, `descricao`, `foto`, `status`, `data_cadastro`, `data_atualizacao`) VALUES
(1, 'Dra. Aparecida Silva', 'Radiologista', '123456', 'Especialista em radiologia com 15 anos de experiência', NULL, 'ativo', '2026-09-08 12:29:06', '2026-09-08 12:29:06'),
(2, 'Dr. Nikolas Santos', 'Cardiologista', '58448', 'Especialista em cardiologia e saúde do coração', NULL, 'ativo', '2026-09-08 12:29:06', '2026-09-08 12:29:06'),
(3, 'Dra. Luani Costa', 'Pediatra', '44544', 'Pediatra dedicada ao cuidado das crianças', NULL, 'ativo', '2026-09-08 12:29:06', '2026-09-08 12:29:06'),
(5, 'marcel oliveira', 'Dentista', '12558963', 'mariela', '6aa019b82b348_20260908162040.jpeg', 'ativo', '2026-09-08 14:20:40', '2026-09-08 14:20:40');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `cpf` varchar(14) DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL,
  `tipo` enum('paciente','medico','admin') DEFAULT 'paciente',
  `status` enum('ativo','inativo','pendente') DEFAULT 'ativo',
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `telefone`, `cpf`, `data_nascimento`, `tipo`, `status`, `data_cadastro`, `data_atualizacao`) VALUES
(3, 'Admin Sistema', 'admin@clinica.com', '$2y$10$yTolEQY0Cw9XZcYXHiDwhOD99AGe0roXIfcrqpCZkmv9k9wBJlb7q', '(11) 4000-0001', '111.222.333-44', '1980-01-01', 'admin', 'ativo', '2026-09-08 12:29:06', '2026-09-08 13:05:46'),
(5, 'JOAO VITOR', 'clara@gmail.com', '$2y$10$yTolEQY0Cw9XZcYXHiDwhOD99AGe0roXIfcrqpCZkmv9k9wBJlb7q', '(45) 77777-7777', '444.444.444-44', '1970-08-14', 'paciente', 'ativo', '2026-09-08 12:30:55', '2026-09-08 12:30:55'),
(6, 'Luani', 'luani@gmail.com', '$2y$10$QVB0m5uk4i0QPMBGylkiVO7jfiDs56JvwxNGBOnlEE6mrRZcgNeXa', '(45) 55555-5555', '761.387.809-06', '1999-03-22', 'paciente', 'ativo', '2026-09-08 14:26:57', '2026-09-08 14:26:57');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `agendamentos`
--
ALTER TABLE `agendamentos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_agendamento` (`medico_id`,`data_consulta`,`hora_consulta`),
  ADD KEY `idx_agendamento_medico` (`medico_id`),
  ADD KEY `idx_agendamento_usuario` (`usuario_id`);

--
-- Índices de tabela `medicos`
--
ALTER TABLE `medicos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `crm` (`crm`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `cpf` (`cpf`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `agendamentos`
--
ALTER TABLE `agendamentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `medicos`
--
ALTER TABLE `medicos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `agendamentos`
--
ALTER TABLE `agendamentos`
  ADD CONSTRAINT `fk_agendamentos_medico` FOREIGN KEY (`medico_id`) REFERENCES `medicos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_agendamentos_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 25/09/2026 às 12:32
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
-- Estrutura para tabela `carrinho`
--

CREATE TABLE `carrinho` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `sessao_id` varchar(100) DEFAULT NULL,
  `produto_id` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `enderecos`
--

CREATE TABLE `enderecos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `tipo` enum('residencial','comercial','entrega') DEFAULT 'entrega',
  `cep` varchar(10) NOT NULL,
  `logradouro` varchar(150) DEFAULT NULL,
  `numero` varchar(10) NOT NULL,
  `complemento` varchar(100) DEFAULT NULL,
  `bairro` varchar(100) DEFAULT NULL,
  `cidade` varchar(100) DEFAULT NULL,
  `estado` char(2) DEFAULT NULL,
  `principal` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `enderecos`
--

INSERT INTO `enderecos` (`id`, `usuario_id`, `tipo`, `cep`, `logradouro`, `numero`, `complemento`, `bairro`, `cidade`, `estado`, `principal`, `created_at`, `updated_at`) VALUES
(1, 2, 'entrega', '85807-670', 'araucaria', '123', '101', 'centro', 'Cascavel', 'PR', 1, '2026-08-31 11:22:32', '2026-08-31 11:22:32');

-- --------------------------------------------------------

--
-- Estrutura para tabela `historico_login`
--

CREATE TABLE `historico_login` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `status` enum('sucesso','falha') NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `motivo_falha` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `logs_atividades`
--

CREATE TABLE `logs_atividades` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `acao` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `dados_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`dados_json`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `endereco_id` int(11) DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `frete` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('pendente','pago','enviado','entregue','cancelado') NOT NULL DEFAULT 'pendente',
  `forma_pagamento` varchar(50) DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `pedidos`
--

INSERT INTO `pedidos` (`id`, `usuario_id`, `endereco_id`, `subtotal`, `frete`, `total`, `status`, `forma_pagamento`, `observacoes`, `created_at`, `updated_at`) VALUES
(1, 2, NULL, 199.90, 0.00, 199.90, 'pendente', 'a_definir', NULL, '2026-09-24 11:29:45', '2026-09-24 11:29:45'),
(2, 2, NULL, 179.80, 0.00, 179.80, 'pendente', 'a_definir', NULL, '2026-09-24 11:31:27', '2026-09-24 11:31:27'),
(3, 2, NULL, 110.00, 0.00, 110.00, 'pendente', 'a_definir', NULL, '2026-09-24 11:35:33', '2026-09-24 11:35:33'),
(4, NULL, NULL, 110.00, 0.00, 110.00, 'pendente', 'a_definir', NULL, '2026-09-25 10:28:07', '2026-09-25 10:28:07');

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedido_itens`
--

CREATE TABLE `pedido_itens` (
  `id` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `produto_id` int(11) NOT NULL,
  `nome_produto` varchar(150) NOT NULL,
  `preco_unitario` decimal(10,2) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `pedido_itens`
--

INSERT INTO `pedido_itens` (`id`, `pedido_id`, `produto_id`, `nome_produto`, `preco_unitario`, `quantidade`, `subtotal`) VALUES
(1, 1, 1, 'Campos de Verão', 89.90, 1, 89.90),
(2, 1, 4, 'rosinha', 110.00, 1, 110.00),
(3, 2, 1, 'Campos de Verão', 89.90, 2, 179.80),
(4, 3, 5, 'Lírios Encantados', 110.00, 1, 110.00),
(5, 4, 5, 'Lírios Encantados', 110.00, 1, 110.00);

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text NOT NULL,
  `modelo` enum('ba','a','pe','be','pf') NOT NULL COMMENT 'ba=buquê afetivo, a=arranjo, pe=presente especial, be=buquê especial, pf=palavras em flor',
  `preco` decimal(10,2) NOT NULL,
  `imagem` varchar(255) DEFAULT NULL COMMENT 'Caminho da imagem do produto',
  `imagem_tipo` varchar(50) DEFAULT NULL COMMENT 'Tipo da imagem (jpg, png, etc)',
  `status` enum('ativo','inativo') DEFAULT 'ativo',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`id`, `nome`, `descricao`, `modelo`, `preco`, `imagem`, `imagem_tipo`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Campos de Verão', 'Girassóis, folhagens e flores do campo em tons de amarelo e verde.', 'ba', 89.90, 'uploads/campos_verao.jpg', NULL, 'ativo', '2026-06-15 12:18:38', '2026-06-15 12:18:38'),
(2, 'Rosas Suaves', 'Buquê com rosas coral, eucalipto e detalhes em branco.', 'ba', 129.90, 'uploads/rosas_suaves.jpg', NULL, 'ativo', '2026-06-15 12:18:38', '2026-06-15 12:18:38'),
(3, 'Minimalismo Verde', 'Folhagens selecionadas e flores secas em vaso de cerâmica.', 'a', 112.00, 'uploads/minimalismo_verde.jpg', NULL, 'ativo', '2026-06-15 12:18:38', '2026-06-15 12:18:38'),
(4, 'rosinha', 'rosinhas bbbbbbbbbb', 'ba', 110.00, 'uploads/6a2ff77554a2a_rosinha.jpg', 'jpg', 'ativo', '2026-06-15 13:00:37', '2026-06-15 13:00:37'),
(5, 'Lírios Encantados', 'Lírios Encantados do bosque', 'a', 110.00, 'uploads/6a30037deb9e9_L__rios_Encantados.webp', 'webp', 'ativo', '2026-06-15 13:51:57', '2026-06-15 13:51:57');

-- --------------------------------------------------------

--
-- Estrutura para tabela `sessoes`
--

CREATE TABLE `sessoes` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `token_sessao` varchar(128) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expires_at` datetime NOT NULL,
  `last_activity` datetime DEFAULT NULL
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
  `status` enum('ativo','inativo','bloqueado') DEFAULT 'ativo',
  `tipo` enum('admin','usuario') NOT NULL DEFAULT 'usuario',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome_completo`, `email`, `senha_hash`, `data_nascimento`, `genero`, `telefone`, `cep`, `numero`, `termos_aceitos`, `status`, `tipo`, `created_at`, `updated_at`) VALUES
(2, 'JOAO VITOR', 'clara@gmail.com', '$2y$10$6uwygNZum0eg8KbHwoJe1ubptH95INRTLJkbWCFbhUQbKP9aUItoy', NULL, 'm', '(77) 77777-7777', '85807-670', '22', 1, 'ativo', 'usuario', '2026-06-15 12:12:09', '2026-06-15 12:12:09'),
(3, 'Louise', 'admin@gmail.com', '$2y$10$xhn2ODP2qwKSlR1GxPxiGOdmHWxfWR9x4WFAL99w0ab1WHf5QlIj6', '2026-06-06', 'f', '(45) 5555-5555', '44444-444', '45', 1, 'ativo', 'admin', '2026-06-15 12:13:23', '2026-06-15 13:12:09'),
(4, 'Lavínia Rigatti Nitsche', 'lavyizhere@gmail.com', '$2y$10$9BzUx1KreSaFFeDODK7usurxF7qfQVMJjF62N41vtOPODYqSnT2vC', '2009-04-04', 'f', '45998003071', '85807020', '4140', 1, 'ativo', 'usuario', '2026-06-15 13:45:13', '2026-06-15 13:45:13');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `carrinho`
--
ALTER TABLE `carrinho`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `produto_id` (`produto_id`),
  ADD KEY `sessao_id` (`sessao_id`);

--
-- Índices de tabela `enderecos`
--
ALTER TABLE `enderecos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `historico_login`
--
ALTER TABLE `historico_login`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `logs_atividades`
--
ALTER TABLE `logs_atividades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `endereco_id` (`endereco_id`);

--
-- Índices de tabela `pedido_itens`
--
ALTER TABLE `pedido_itens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedido_id` (`pedido_id`),
  ADD KEY `produto_id` (`produto_id`);

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_modelo` (`modelo`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_nome` (`nome`);

--
-- Índices de tabela `sessoes`
--
ALTER TABLE `sessoes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token_sessao` (`token_sessao`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_tipo` (`tipo`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `carrinho`
--
ALTER TABLE `carrinho`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `enderecos`
--
ALTER TABLE `enderecos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `historico_login`
--
ALTER TABLE `historico_login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `logs_atividades`
--
ALTER TABLE `logs_atividades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `pedido_itens`
--
ALTER TABLE `pedido_itens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `sessoes`
--
ALTER TABLE `sessoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `carrinho`
--
ALTER TABLE `carrinho`
  ADD CONSTRAINT `carrinho_produto_fk` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `carrinho_usuario_fk` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `enderecos`
--
ALTER TABLE `enderecos`
  ADD CONSTRAINT `enderecos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `historico_login`
--
ALTER TABLE `historico_login`
  ADD CONSTRAINT `historico_login_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `logs_atividades`
--
ALTER TABLE `logs_atividades`
  ADD CONSTRAINT `logs_atividades_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;

--
-- Restrições para tabelas `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_endereco_fk` FOREIGN KEY (`endereco_id`) REFERENCES `enderecos` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `pedidos_usuario_fk` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;

--
-- Restrições para tabelas `pedido_itens`
--
ALTER TABLE `pedido_itens`
  ADD CONSTRAINT `itens_pedido_fk` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `itens_produto_fk` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `sessoes`
--
ALTER TABLE `sessoes`
  ADD CONSTRAINT `sessoes_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 04/09/2026 às 14:15
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
-- Banco de dados: `sportlife`
--

DELIMITER $$
--
-- Procedimentos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_inscrever_aula` (IN `p_aula_id` INT, IN `p_aluno_id` INT)   BEGIN
    DECLARE vagas INT;
    
    -- Verificar vagas disponíveis
    SELECT vagas_disponiveis INTO vagas 
    FROM aulas 
    WHERE id = p_aula_id AND status = 'agendada';
    
    IF vagas > 0 THEN
        -- Inserir inscrição
        INSERT INTO inscricoes_aulas (aula_id, aluno_id, status)
        VALUES (p_aula_id, p_aluno_id, 'confirmada');
        
        -- Atualizar vagas
        UPDATE aulas 
        SET vagas_disponiveis = vagas_disponiveis - 1 
        WHERE id = p_aula_id;
        
        SELECT 'Inscrição realizada com sucesso!' as mensagem;
    ELSE
        SELECT 'Não há vagas disponíveis!' as mensagem;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_registrar_progresso` (IN `p_aluno_id` INT, IN `p_treino_id` INT, IN `p_peso` DECIMAL(5,2), IN `p_altura` DECIMAL(5,2), IN `p_observacao` TEXT)   BEGIN
    INSERT INTO progresso_aluno (aluno_id, treino_id, data_registro, peso, altura, observacao)
    VALUES (p_aluno_id, p_treino_id, CURDATE(), p_peso, p_altura, p_observacao);
    
    SELECT 'Progresso registrado com sucesso!' as mensagem;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `aulas`
--

CREATE TABLE `aulas` (
  `id` int(11) NOT NULL,
  `instrutor_id` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `modalidade` varchar(50) NOT NULL,
  `data_aula` datetime NOT NULL,
  `duracao` int(11) DEFAULT 60,
  `capacidade_maxima` int(11) DEFAULT 20,
  `vagas_disponiveis` int(11) DEFAULT 20,
  `local` varchar(100) DEFAULT NULL,
  `status` enum('agendada','em_andamento','finalizada','cancelada') DEFAULT 'agendada',
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `aulas`
--

INSERT INTO `aulas` (`id`, `instrutor_id`, `titulo`, `descricao`, `modalidade`, `data_aula`, `duracao`, `capacidade_maxima`, `vagas_disponiveis`, `local`, `status`, `data_criacao`, `data_atualizacao`) VALUES
(1, 2, 'Musculação Avançada', 'Treino focado em hipertrofia para alunos avançados', 'Musculação', '2026-09-06 08:07:08', 60, 15, 15, 'Sala 1', 'agendada', '2026-09-04 11:07:08', '2026-09-04 11:07:08'),
(2, 2, 'Treino Funcional', 'Treino funcional com exercícios dinâmicos', 'Funcional', '2026-09-07 08:07:08', 45, 20, 20, 'Sala 3', 'agendada', '2026-09-04 11:07:08', '2026-09-04 11:07:08'),
(3, 2, 'Yoga e Flexibilidade', 'Aula de yoga para relaxamento e flexibilidade', 'Yoga', '2026-09-08 08:07:08', 60, 12, 12, 'Sala 2', 'agendada', '2026-09-04 11:07:08', '2026-09-04 11:07:08');

-- --------------------------------------------------------

--
-- Estrutura para tabela `avaliacoes_fisicas`
--

CREATE TABLE `avaliacoes_fisicas` (
  `id` int(11) NOT NULL,
  `aluno_id` int(11) NOT NULL,
  `instrutor_id` int(11) NOT NULL,
  `data_avaliacao` date NOT NULL,
  `peso` decimal(5,2) DEFAULT NULL,
  `altura` decimal(5,2) DEFAULT NULL,
  `imc` decimal(5,2) DEFAULT NULL,
  `percentual_gordura` decimal(5,2) DEFAULT NULL,
  `massa_muscular` decimal(5,2) DEFAULT NULL,
  `circunferencia_braco` decimal(5,2) DEFAULT NULL,
  `circunferencia_peito` decimal(5,2) DEFAULT NULL,
  `circunferencia_cintura` decimal(5,2) DEFAULT NULL,
  `circunferencia_quadril` decimal(5,2) DEFAULT NULL,
  `circunferencia_perna` decimal(5,2) DEFAULT NULL,
  `flexibilidade` decimal(5,2) DEFAULT NULL,
  `observacao` text DEFAULT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `exercicios`
--

CREATE TABLE `exercicios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `grupo_muscular` varchar(50) NOT NULL,
  `equipamento` varchar(50) DEFAULT NULL,
  `imagem_url` varchar(255) DEFAULT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `dificuldade` enum('facil','medio','dificil') DEFAULT 'medio',
  `ativo` tinyint(1) DEFAULT 1,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `exercicios`
--

INSERT INTO `exercicios` (`id`, `nome`, `descricao`, `grupo_muscular`, `equipamento`, `imagem_url`, `video_url`, `dificuldade`, `ativo`, `data_criacao`) VALUES
(1, 'Supino Reto', 'Exercício para peitoral realizado no banco reto', 'Peitoral', 'Barra/Halteres', NULL, NULL, 'medio', 1, '2026-09-04 11:07:08'),
(2, 'Agachamento', 'Exercício para membros inferiores', 'Pernas', 'Barra', NULL, NULL, 'dificil', 1, '2026-09-04 11:07:08'),
(3, 'Rosca Direta', 'Exercício para bíceps', 'Bíceps', 'Halteres', NULL, NULL, 'facil', 1, '2026-09-04 11:07:08'),
(4, 'Puxada Frontal', 'Exercício para costas', 'Costas', 'Polia', NULL, NULL, 'medio', 1, '2026-09-04 11:07:08'),
(5, 'Desenvolvimento', 'Exercício para ombros', 'Ombros', 'Halteres', NULL, NULL, 'medio', 1, '2026-09-04 11:07:08'),
(6, 'Leg Press', 'Exercício para pernas', 'Pernas', 'Máquina', NULL, NULL, 'facil', 1, '2026-09-04 11:07:08'),
(7, 'Crucifixo', 'Exercício para peitoral', 'Peitoral', 'Halteres', NULL, NULL, 'medio', 1, '2026-09-04 11:07:08'),
(8, 'Remada Curvada', 'Exercício para costas', 'Costas', 'Barra', NULL, NULL, 'dificil', 1, '2026-09-04 11:07:08');

-- --------------------------------------------------------

--
-- Estrutura para tabela `inscricoes_aulas`
--

CREATE TABLE `inscricoes_aulas` (
  `id` int(11) NOT NULL,
  `aula_id` int(11) NOT NULL,
  `aluno_id` int(11) NOT NULL,
  `status` enum('confirmada','presente','falta','cancelada') DEFAULT 'confirmada',
  `data_inscricao` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Acionadores `inscricoes_aulas`
--
DELIMITER $$
CREATE TRIGGER `trg_cancelar_inscricao` AFTER UPDATE ON `inscricoes_aulas` FOR EACH ROW BEGIN
    IF NEW.status = 'cancelada' AND OLD.status != 'cancelada' THEN
        UPDATE aulas 
        SET vagas_disponiveis = vagas_disponiveis + 1 
        WHERE id = NEW.aula_id;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `acao` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `logs`
--

INSERT INTO `logs` (`id`, `usuario_id`, `acao`, `descricao`, `ip`, `user_agent`, `data_criacao`) VALUES
(1, 4, 'cadastro_usuario', 'Novo usuário cadastrado: julia@gmail.com', NULL, NULL, '2026-09-04 11:31:54'),
(2, 5, 'cadastro_usuario', 'Novo usuário cadastrado: joao1@gmail.com', NULL, NULL, '2026-09-04 11:45:45');

-- --------------------------------------------------------

--
-- Estrutura para tabela `notificacoes`
--

CREATE TABLE `notificacoes` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `mensagem` text NOT NULL,
  `tipo` enum('info','success','warning','error') DEFAULT 'info',
  `lida` tinyint(1) DEFAULT 0,
  `link` varchar(255) DEFAULT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `progresso_aluno`
--

CREATE TABLE `progresso_aluno` (
  `id` int(11) NOT NULL,
  `aluno_id` int(11) NOT NULL,
  `treino_id` int(11) NOT NULL,
  `data_registro` date NOT NULL,
  `peso` decimal(5,2) DEFAULT NULL,
  `altura` decimal(5,2) DEFAULT NULL,
  `percentual_gordura` decimal(5,2) DEFAULT NULL,
  `massa_muscular` decimal(5,2) DEFAULT NULL,
  `observacao` text DEFAULT NULL,
  `foto_antes` varchar(255) DEFAULT NULL,
  `foto_depois` varchar(255) DEFAULT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `treinos`
--

CREATE TABLE `treinos` (
  `id` int(11) NOT NULL,
  `instrutor_id` int(11) NOT NULL,
  `aluno_id` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `objetivo` varchar(50) DEFAULT 'Hipertrofia',
  `nivel` enum('iniciante','intermediario','avancado') DEFAULT 'iniciante',
  `duracao_semanas` int(11) DEFAULT 4,
  `status` enum('rascunho','ativo','concluido','arquivado') DEFAULT 'rascunho',
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `treino_exercicios`
--

CREATE TABLE `treino_exercicios` (
  `id` int(11) NOT NULL,
  `treino_id` int(11) NOT NULL,
  `exercicio_id` int(11) NOT NULL,
  `series` int(11) DEFAULT 3,
  `repeticoes` varchar(20) DEFAULT '12',
  `carga` varchar(20) DEFAULT '0kg',
  `descanso` varchar(20) DEFAULT '60s',
  `ordem` int(11) DEFAULT 0,
  `observacao` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome_completo` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL,
  `cpf` varchar(14) DEFAULT NULL,
  `modalidade` varchar(50) NOT NULL DEFAULT 'Musculação',
  `tipo_usuario` enum('admin','instrutor','usuario') NOT NULL DEFAULT 'usuario',
  `foto_perfil` varchar(255) DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT 1,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome_completo`, `email`, `senha`, `telefone`, `data_nascimento`, `cpf`, `modalidade`, `tipo_usuario`, `foto_perfil`, `ativo`, `data_criacao`, `data_atualizacao`) VALUES
(1, 'Administrador', 'admin@sportlife.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '(11) 99999-9999', NULL, NULL, 'Administração', 'admin', NULL, 1, '2026-09-04 11:07:08', '2026-09-04 11:07:08'),
(2, 'Carlos Instrutor', 'carlos@sportlife.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '(11) 98888-8888', NULL, NULL, 'Musculação', 'instrutor', NULL, 1, '2026-09-04 11:07:08', '2026-09-04 11:07:08'),
(3, 'João Aluno', 'joao@sportlife.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '(11) 97777-7777', NULL, NULL, 'Musculação', 'usuario', NULL, 1, '2026-09-04 11:07:08', '2026-09-04 11:07:08'),
(4, 'Julia', 'julia@gmail.com', '$2y$10$U0lnnnbi0wOCJ9jFDdHSUu7jZaxCXFnyGZJjXotWlwQ.MuUuevkA6', NULL, NULL, NULL, 'Luta', 'admin', NULL, 1, '2026-09-04 11:31:54', '2026-09-04 11:33:07'),
(5, 'JOAO VITOR', 'joao1@gmail.com', '$2y$10$F3PunSHb5K5KSrePXgPeIOz2TnXNKhTTm.d0lLgaH86wcdzXhxQPy', '4555555555', '1997-03-14', '761.387.809-06', 'Yoga', 'usuario', NULL, 1, '2026-09-04 11:45:45', '2026-09-04 11:45:45');

--
-- Acionadores `usuarios`
--
DELIMITER $$
CREATE TRIGGER `trg_log_usuario` AFTER INSERT ON `usuarios` FOR EACH ROW BEGIN
    INSERT INTO logs (usuario_id, acao, descricao)
    VALUES (NEW.id, 'cadastro_usuario', CONCAT('Novo usuário cadastrado: ', NEW.email));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `view_aulas_hoje`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `view_aulas_hoje` (
`id` int(11)
,`titulo` varchar(100)
,`descricao` text
,`data_aula` datetime
,`modalidade` varchar(50)
,`local` varchar(100)
,`instrutor` varchar(100)
,`vagas_disponiveis` int(11)
,`capacidade_maxima` int(11)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `view_modalidade_alunos`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `view_modalidade_alunos` (
`modalidade` varchar(50)
,`total_alunos` bigint(21)
,`total_instrutores` bigint(21)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `view_top_instrutores`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `view_top_instrutores` (
`id` int(11)
,`nome_completo` varchar(100)
,`modalidade` varchar(50)
,`total_alunos` bigint(21)
,`alunos_ativos` bigint(21)
);

-- --------------------------------------------------------

--
-- Estrutura para view `view_aulas_hoje`
--
DROP TABLE IF EXISTS `view_aulas_hoje`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_aulas_hoje`  AS SELECT `a`.`id` AS `id`, `a`.`titulo` AS `titulo`, `a`.`descricao` AS `descricao`, `a`.`data_aula` AS `data_aula`, `a`.`modalidade` AS `modalidade`, `a`.`local` AS `local`, `u`.`nome_completo` AS `instrutor`, `a`.`vagas_disponiveis` AS `vagas_disponiveis`, `a`.`capacidade_maxima` AS `capacidade_maxima` FROM (`aulas` `a` left join `usuarios` `u` on(`a`.`instrutor_id` = `u`.`id`)) WHERE cast(`a`.`data_aula` as date) = curdate() AND `a`.`status` = 'agendada' ORDER BY `a`.`data_aula` ASC ;

-- --------------------------------------------------------

--
-- Estrutura para view `view_modalidade_alunos`
--
DROP TABLE IF EXISTS `view_modalidade_alunos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_modalidade_alunos`  AS SELECT `usuarios`.`modalidade` AS `modalidade`, count(0) AS `total_alunos`, count(case when `usuarios`.`tipo_usuario` = 'instrutor' then 1 end) AS `total_instrutores` FROM `usuarios` WHERE `usuarios`.`ativo` = 1 GROUP BY `usuarios`.`modalidade` ;

-- --------------------------------------------------------

--
-- Estrutura para view `view_top_instrutores`
--
DROP TABLE IF EXISTS `view_top_instrutores`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_top_instrutores`  AS SELECT `u`.`id` AS `id`, `u`.`nome_completo` AS `nome_completo`, `u`.`modalidade` AS `modalidade`, count(`t`.`id`) AS `total_alunos`, count(distinct `t`.`aluno_id`) AS `alunos_ativos` FROM (`usuarios` `u` left join `treinos` `t` on(`u`.`id` = `t`.`instrutor_id` and `t`.`status` = 'ativo')) WHERE `u`.`tipo_usuario` = 'instrutor' GROUP BY `u`.`id` ORDER BY count(`t`.`id`) DESC ;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `aulas`
--
ALTER TABLE `aulas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_instrutor` (`instrutor_id`),
  ADD KEY `idx_data_aula` (`data_aula`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_modalidade` (`modalidade`);

--
-- Índices de tabela `avaliacoes_fisicas`
--
ALTER TABLE `avaliacoes_fisicas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `instrutor_id` (`instrutor_id`),
  ADD KEY `idx_aluno` (`aluno_id`),
  ADD KEY `idx_data` (`data_avaliacao`);

--
-- Índices de tabela `exercicios`
--
ALTER TABLE `exercicios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_grupo_muscular` (`grupo_muscular`),
  ADD KEY `idx_dificuldade` (`dificuldade`);

--
-- Índices de tabela `inscricoes_aulas`
--
ALTER TABLE `inscricoes_aulas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_inscricao` (`aula_id`,`aluno_id`),
  ADD KEY `idx_aula` (`aula_id`),
  ADD KEY `idx_aluno` (`aluno_id`),
  ADD KEY `idx_status` (`status`);

--
-- Índices de tabela `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_usuario` (`usuario_id`),
  ADD KEY `idx_acao` (`acao`),
  ADD KEY `idx_data` (`data_criacao`);

--
-- Índices de tabela `notificacoes`
--
ALTER TABLE `notificacoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_usuario` (`usuario_id`),
  ADD KEY `idx_lida` (`lida`),
  ADD KEY `idx_data` (`data_criacao`);

--
-- Índices de tabela `progresso_aluno`
--
ALTER TABLE `progresso_aluno`
  ADD PRIMARY KEY (`id`),
  ADD KEY `treino_id` (`treino_id`),
  ADD KEY `idx_aluno` (`aluno_id`),
  ADD KEY `idx_data` (`data_registro`);

--
-- Índices de tabela `treinos`
--
ALTER TABLE `treinos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_instrutor` (`instrutor_id`),
  ADD KEY `idx_aluno` (`aluno_id`),
  ADD KEY `idx_status` (`status`);

--
-- Índices de tabela `treino_exercicios`
--
ALTER TABLE `treino_exercicios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_treino` (`treino_id`),
  ADD KEY `idx_exercicio` (`exercicio_id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `cpf` (`cpf`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_tipo_usuario` (`tipo_usuario`),
  ADD KEY `idx_modalidade` (`modalidade`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `aulas`
--
ALTER TABLE `aulas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `avaliacoes_fisicas`
--
ALTER TABLE `avaliacoes_fisicas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `exercicios`
--
ALTER TABLE `exercicios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `inscricoes_aulas`
--
ALTER TABLE `inscricoes_aulas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `notificacoes`
--
ALTER TABLE `notificacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `progresso_aluno`
--
ALTER TABLE `progresso_aluno`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `treinos`
--
ALTER TABLE `treinos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `treino_exercicios`
--
ALTER TABLE `treino_exercicios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `aulas`
--
ALTER TABLE `aulas`
  ADD CONSTRAINT `aulas_ibfk_1` FOREIGN KEY (`instrutor_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `avaliacoes_fisicas`
--
ALTER TABLE `avaliacoes_fisicas`
  ADD CONSTRAINT `avaliacoes_fisicas_ibfk_1` FOREIGN KEY (`aluno_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `avaliacoes_fisicas_ibfk_2` FOREIGN KEY (`instrutor_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `inscricoes_aulas`
--
ALTER TABLE `inscricoes_aulas`
  ADD CONSTRAINT `inscricoes_aulas_ibfk_1` FOREIGN KEY (`aula_id`) REFERENCES `aulas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inscricoes_aulas_ibfk_2` FOREIGN KEY (`aluno_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;

--
-- Restrições para tabelas `notificacoes`
--
ALTER TABLE `notificacoes`
  ADD CONSTRAINT `notificacoes_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `progresso_aluno`
--
ALTER TABLE `progresso_aluno`
  ADD CONSTRAINT `progresso_aluno_ibfk_1` FOREIGN KEY (`aluno_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `progresso_aluno_ibfk_2` FOREIGN KEY (`treino_id`) REFERENCES `treinos` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `treinos`
--
ALTER TABLE `treinos`
  ADD CONSTRAINT `treinos_ibfk_1` FOREIGN KEY (`instrutor_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `treinos_ibfk_2` FOREIGN KEY (`aluno_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `treino_exercicios`
--
ALTER TABLE `treino_exercicios`
  ADD CONSTRAINT `treino_exercicios_ibfk_1` FOREIGN KEY (`treino_id`) REFERENCES `treinos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `treino_exercicios_ibfk_2` FOREIGN KEY (`exercicio_id`) REFERENCES `exercicios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 14/05/2026 às 16:22
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
-- Banco de dados: `cinevortex`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `avaliacoes`
--

CREATE TABLE `avaliacoes` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `filme_id` int(11) NOT NULL,
  `nota` int(11) NOT NULL CHECK (`nota` >= 1 and `nota` <= 5),
  `comentario` text DEFAULT NULL,
  `data_avaliacao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `filmes`
--

CREATE TABLE `filmes` (
  `id` int(11) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `ano` int(11) NOT NULL,
  `descricao` text DEFAULT NULL,
  `genero` varchar(100) DEFAULT NULL,
  `url_imagem` varchar(500) DEFAULT NULL,
  `id_usuario_criador` int(11) DEFAULT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `filmes`
--

INSERT INTO `filmes` (`id`, `titulo`, `ano`, `descricao`, `genero`, `url_imagem`, `id_usuario_criador`, `data_cadastro`) VALUES
(1, 'Interestelar', 2014, 'Uma equipe de exploradores viaja através de um buraco de minhoca no espaço.', 'Ficção Científica, Drama', 'https://image.tmdb.org/t/p/w500/gEU2QniE6E77NI6lCU6MxlNBvIx.jpg', NULL, '2026-05-11 13:22:00'),
(2, 'O Poderoso Chefão', 1972, 'A família Corleone luta pelo poder na máfia de Nova York.', 'Drama, Crime', 'https://image.tmdb.org/t/p/w500/3bhkrj58Vtu7enYsRolD1fZdja1.jpg', NULL, '2026-05-11 13:22:00'),
(3, 'Cidade de Deus', 2002, 'Crescer em meio à violência do Rio de Janeiro nos anos 70.', 'Drama, Crime', 'https://image.tmdb.org/t/p/w500/k7eYdEKnRRCrC1Y1XaR0xh2FpP8.jpg', NULL, '2026-05-11 13:22:00'),
(4, 'A Origem', 2010, 'Um ladrão que invade sonhos é contratado para plantar uma ideia.', 'Ação, Ficção Científica', 'https://image.tmdb.org/t/p/w500/edv5CZvWj09upOsy2Y6IwDhK8bt.jpg', NULL, '2026-05-11 13:22:00'),
(5, 'Clube da Luta', 1999, 'Um homem deprimido se junta a um clube de luta subterrâneo.', 'Drama', 'https://image.tmdb.org/t/p/w500/pB8BM7pdSp6B6Ih7QZ4DrQ3PmJK.jpg', NULL, '2026-05-11 13:22:00'),
(6, 'Matrix', 1999, 'Um hacker descobre a verdade sobre a realidade e seu papel na guerra contra os controladores.', 'Ação, Ficção Científica', 'https://image.tmdb.org/t/p/w500/f89U3ADr1oiB1s9GkdPOEpXUk5H.jpg', NULL, '2026-05-11 13:22:00'),
(7, 'Os Bons Companheiros', 1990, 'A ascensão e queda de um gângster na máfia de Nova York.', 'Drama, Crime', 'https://image.tmdb.org/t/p/w500/aKuFiU82s5KJfGqHk7jF1gMRPcP.jpg', NULL, '2026-05-11 13:22:00'),
(8, 'Parasita', 2019, 'Uma família pobre se infiltra na casa de uma família rica.', 'Drama, Suspense', 'https://image.tmdb.org/t/p/w500/7IiTTgloJzvGI1TAYymCfbfl3vT.jpg', NULL, '2026-05-11 13:22:00'),
(9, 'O Irlandês', 2019, 'Um veterano de guerra se torna assassino de aluguel para a máfia.', 'Drama, Crime', 'https://image.tmdb.org/t/p/w500/mbEwJ1ks5QHFYTRlNl9U2z0FcXK.jpg', NULL, '2026-05-11 13:22:00'),
(10, 'John Wick', 2014, 'Um ex-assassino volta à ativa para se vingar dos que mataram seu cachorro.', 'Ação', 'https://image.tmdb.org/t/p/w500/fZPSd91yGE9fCcCe6OoQr6E3BEv.jpg', NULL, '2026-05-11 13:22:00');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `tipo` enum('user','admin') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `data_cadastro`, `tipo`) VALUES
(1, 'APARECIDA DA SILVA FERREIRA', 'aparecida.ferreira@gmail.com', '$2y$10$nCMNMMHq9XBM7nzUFP6Pj.2T5qSXGlWzCzD9ABIpbFGFkRYt2crvC', '2026-05-14 13:20:06', 'user'),
(2, 'admin', 'admin@gmail.com', '$2y$10$r.y0TQ8oRK4GUGr1VC4hd.RZf1DwJg5PyG6vGD21Q7f.DwkjEphMW', '2026-05-14 13:50:50', 'admin');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios_filmes`
--

CREATE TABLE `usuarios_filmes` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `filme_id` int(11) NOT NULL,
  `status` enum('quero_ver','assistido','favorito') DEFAULT 'quero_ver',
  `nota` int(11) DEFAULT NULL CHECK (`nota` >= 1 and `nota` <= 5),
  `comentario` text DEFAULT NULL,
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `avaliacoes`
--
ALTER TABLE `avaliacoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_avaliacoes_filme` (`filme_id`),
  ADD KEY `idx_avaliacoes_usuario` (`usuario_id`);

--
-- Índices de tabela `filmes`
--
ALTER TABLE `filmes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario_criador` (`id_usuario_criador`),
  ADD KEY `idx_filmes_titulo` (`titulo`),
  ADD KEY `idx_filmes_ano` (`ano`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices de tabela `usuarios_filmes`
--
ALTER TABLE `usuarios_filmes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_usuario_filme` (`usuario_id`,`filme_id`),
  ADD KEY `idx_usuarios_filmes_usuario` (`usuario_id`),
  ADD KEY `idx_usuarios_filmes_filme` (`filme_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `avaliacoes`
--
ALTER TABLE `avaliacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `filmes`
--
ALTER TABLE `filmes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `usuarios_filmes`
--
ALTER TABLE `usuarios_filmes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `avaliacoes`
--
ALTER TABLE `avaliacoes`
  ADD CONSTRAINT `avaliacoes_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `avaliacoes_ibfk_2` FOREIGN KEY (`filme_id`) REFERENCES `filmes` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `filmes`
--
ALTER TABLE `filmes`
  ADD CONSTRAINT `filmes_ibfk_1` FOREIGN KEY (`id_usuario_criador`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;

--
-- Restrições para tabelas `usuarios_filmes`
--
ALTER TABLE `usuarios_filmes`
  ADD CONSTRAINT `usuarios_filmes_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `usuarios_filmes_ibfk_2` FOREIGN KEY (`filme_id`) REFERENCES `filmes` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

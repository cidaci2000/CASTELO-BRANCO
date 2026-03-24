-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 24/03/2026 às 15:56
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
-- Banco de dados: `skate_fest_competition`
--

DELIMITER $$
--
-- Procedimentos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `atualizar_notas` (IN `p_skatista_id` INT, IN `p_kickflip` DECIMAL(3,1), IN `p_heelflip` DECIMAL(3,1), IN `p_tre_flip` DECIMAL(3,1), IN `p_varial` DECIMAL(3,1), IN `p_laser` DECIMAL(3,1))   BEGIN
    DECLARE exit handler for sqlexception
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;
    
    START TRANSACTION;
    
    -- Validar se skatista existe
    IF NOT EXISTS (SELECT 1 FROM skatistas WHERE id = p_skatista_id) THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Skatista não encontrado';
    END IF;
    
    -- Validar notas
    IF p_kickflip NOT BETWEEN 0 AND 10 OR
       p_heelflip NOT BETWEEN 0 AND 10 OR
       p_tre_flip NOT BETWEEN 0 AND 10 OR
       p_varial NOT BETWEEN 0 AND 10 OR
       p_laser NOT BETWEEN 0 AND 10 THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Todas as notas devem estar entre 0 e 10';
    END IF;
    
    -- Atualizar notas
    UPDATE skatistas 
    SET 
        kickflip = p_kickflip,
        heelflip = p_heelflip,
        tre_flip = p_tre_flip,
        varial = p_varial,
        laser = p_laser
    WHERE id = p_skatista_id;
    
    COMMIT;
    
    -- Retornar dados atualizados
    SELECT * FROM skatistas WHERE id = p_skatista_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `cadastrar_skatista` (IN `p_nome` VARCHAR(100), IN `p_pais` VARCHAR(50), IN `p_idade` INT, IN `p_kickflip` DECIMAL(3,1), IN `p_heelflip` DECIMAL(3,1), IN `p_tre_flip` DECIMAL(3,1), IN `p_varial` DECIMAL(3,1), IN `p_laser` DECIMAL(3,1))   BEGIN
    DECLARE exit handler for sqlexception
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;
    
    START TRANSACTION;
    
    -- Validar idades
    IF p_idade < 10 OR p_idade > 60 THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Idade deve estar entre 10 e 60 anos';
    END IF;
    
    -- Validar notas
    IF p_kickflip NOT BETWEEN 0 AND 10 OR
       p_heelflip NOT BETWEEN 0 AND 10 OR
       p_tre_flip NOT BETWEEN 0 AND 10 OR
       p_varial NOT BETWEEN 0 AND 10 OR
       p_laser NOT BETWEEN 0 AND 10 THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Todas as notas devem estar entre 0 e 10';
    END IF;
    
    -- Inserir skatista
    INSERT INTO skatistas (
        nome, pais, idade,
        kickflip, heelflip, tre_flip, varial, laser
    ) VALUES (
        p_nome, p_pais, p_idade,
        p_kickflip, p_heelflip, p_tre_flip, p_varial, p_laser
    );
    
    COMMIT;
    
    -- Retornar o ID do skatista criado
    SELECT LAST_INSERT_ID() AS skatista_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `resetar_competicao` ()   BEGIN
    DECLARE exit handler for sqlexception
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;
    
    START TRANSACTION;
    
    DELETE FROM skatistas;
    ALTER TABLE skatistas AUTO_INCREMENT = 1;
    
    COMMIT;
END$$

--
-- Funções
--
CREATE DEFINER=`root`@`localhost` FUNCTION `calcular_media_skatista` (`p_skatista_id` INT) RETURNS DECIMAL(4,2) DETERMINISTIC READS SQL DATA BEGIN
    DECLARE v_media DECIMAL(4,2);
    
    SELECT media_geral INTO v_media
    FROM skatistas
    WHERE id = p_skatista_id;
    
    RETURN IFNULL(v_media, 0);
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `estatisticas_competicao`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `estatisticas_competicao` (
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `ranking_completo`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `ranking_completo` (
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `ranking_podium`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `ranking_podium` (
);

-- --------------------------------------------------------

--
-- Estrutura para tabela `skatistas`
--

CREATE TABLE `skatistas` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `pais` varchar(50) NOT NULL,
  `idade` int(11) NOT NULL,
  `nota_kickflip` decimal(3,1) NOT NULL,
  `nota_heelflip` decimal(3,1) NOT NULL,
  `nota_treflip` decimal(3,1) NOT NULL,
  `nota_varial` decimal(3,1) NOT NULL,
  `nota_laser` decimal(3,1) NOT NULL,
  `media` decimal(3,1) NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `skatistas`
--

INSERT INTO `skatistas` (`id`, `nome`, `pais`, `idade`, `nota_kickflip`, `nota_heelflip`, `nota_treflip`, `nota_varial`, `nota_laser`, `media`, `data_cadastro`) VALUES
(1, 'APARECIDA DA SILVA FERREIRA', 'Brasil', 18, 2.0, 2.0, 2.0, 2.0, 2.0, 2.0, '2026-03-24 14:53:47'),
(2, 'igor', 'Inglaterra', 11, 4.0, 4.0, 4.0, 4.0, 4.0, 4.0, '2026-03-24 14:54:23'),
(3, 'GABY', 'BRASIL', 17, 5.0, 5.0, 5.0, 5.0, 5.0, 5.0, '2026-03-24 14:55:24');

-- --------------------------------------------------------

--
-- Estrutura para view `estatisticas_competicao`
--
DROP TABLE IF EXISTS `estatisticas_competicao`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `estatisticas_competicao`  AS SELECT count(0) AS `total_participantes`, round(avg(`skatistas`.`media_geral`),2) AS `media_geral_competicao`, max(`skatistas`.`media_geral`) AS `maior_media`, min(`skatistas`.`media_geral`) AS `menor_media`, max(`skatistas`.`pontuacao_total`) AS `maior_pontuacao`, (select `skatistas`.`nome` from `skatistas` order by `skatistas`.`media_geral` desc limit 1) AS `lider_atual`, (select `skatistas`.`media_geral` from `skatistas` order by `skatistas`.`media_geral` desc limit 1) AS `pontuacao_lider` FROM `skatistas` ;

-- --------------------------------------------------------

--
-- Estrutura para view `ranking_completo`
--
DROP TABLE IF EXISTS `ranking_completo`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `ranking_completo`  AS SELECT `skatistas`.`id` AS `id`, `skatistas`.`nome` AS `nome`, `skatistas`.`pais` AS `pais`, `skatistas`.`idade` AS `idade`, `skatistas`.`kickflip` AS `kickflip`, `skatistas`.`heelflip` AS `heelflip`, `skatistas`.`tre_flip` AS `tre_flip`, `skatistas`.`varial` AS `varial`, `skatistas`.`laser` AS `laser`, `skatistas`.`pontuacao_total` AS `pontuacao_total`, `skatistas`.`media_geral` AS `media_geral`, `skatistas`.`data_cadastro` AS `data_cadastro`, rank() over ( order by `skatistas`.`media_geral` desc,`skatistas`.`pontuacao_total` desc) AS `posicao_ranking` FROM `skatistas` ORDER BY `skatistas`.`media_geral` DESC ;

-- --------------------------------------------------------

--
-- Estrutura para view `ranking_podium`
--
DROP TABLE IF EXISTS `ranking_podium`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `ranking_podium`  AS SELECT `ranking_completo`.`id` AS `id`, `ranking_completo`.`nome` AS `nome`, `ranking_completo`.`pais` AS `pais`, `ranking_completo`.`idade` AS `idade`, `ranking_completo`.`kickflip` AS `kickflip`, `ranking_completo`.`heelflip` AS `heelflip`, `ranking_completo`.`tre_flip` AS `tre_flip`, `ranking_completo`.`varial` AS `varial`, `ranking_completo`.`laser` AS `laser`, `ranking_completo`.`pontuacao_total` AS `pontuacao_total`, `ranking_completo`.`media_geral` AS `media_geral`, `ranking_completo`.`data_cadastro` AS `data_cadastro`, `ranking_completo`.`posicao_ranking` AS `posicao_ranking` FROM `ranking_completo` WHERE `ranking_completo`.`posicao_ranking` <= 3 ORDER BY `ranking_completo`.`posicao_ranking` ASC ;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `skatistas`
--
ALTER TABLE `skatistas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_media` (`media`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `skatistas`
--
ALTER TABLE `skatistas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

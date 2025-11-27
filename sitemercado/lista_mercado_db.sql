-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 24/11/2025 às 21:08
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `lista_mercado_db`
--
CREATE DATABASE IF NOT EXISTS `lista_mercado_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `lista_mercado_db`;

-- --------------------------------------------------------

--
-- Estrutura para tabela `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nome_categoria` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `categorias`
--

INSERT INTO `categorias` (`id`, `nome_categoria`) VALUES
(5, 'Bebidas'),
(7, 'Higiene Pessoal'),
(4, 'Hortifruti'),
(1, 'Laticínios e Frios'),
(2, 'Limpeza'),
(3, 'Massas e Grãos'),
(6, 'Padaria');

-- --------------------------------------------------------

--
-- Estrutura para tabela `corredores`
--

CREATE TABLE `corredores` (
  `id` int(11) NOT NULL,
  `supermercado_id` int(11) NOT NULL,
  `numero_corredor` int(11) NOT NULL,
  `nome_secao` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `corredores`
--

INSERT INTO `corredores` (`id`, `supermercado_id`, `numero_corredor`, `nome_secao`) VALUES
(1, 1, 1, 'Hortifruti'),
(2, 1, 2, 'Laticínios e Frios'),
(3, 1, 3, 'Padaria'),
(4, 1, 4, 'Bebidas'),
(5, 1, 5, 'Limpeza'),
(6, 1, 6, 'Higiene Pessoal'),
(7, 1, 7, 'Massas e Mercearia'),
(8, 2, 1, 'Limpeza e Higiene'),
(9, 2, 2, 'Massas e Mercearia'),
(10, 2, 8, 'Hortifruti'),
(11, 2, 9, 'Laticínios, Frios e Padaria'),
(12, 2, 10, 'Bebidas');

-- --------------------------------------------------------

--
-- Estrutura para tabela `itensdalista`
--

CREATE TABLE `itensdalista` (
  `id` int(11) NOT NULL,
  `lista_id` int(11) NOT NULL,
  `item_mestre_id` int(11) DEFAULT NULL,
  `nome_item_personalizado` varchar(100) DEFAULT NULL,
  `categoria_id` int(11) DEFAULT NULL,
  `quantidade` varchar(50) DEFAULT '1',
  `comprado` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `itensdalista`
--

INSERT INTO `itensdalista` (`id`, `lista_id`, `item_mestre_id`, `nome_item_personalizado`, `categoria_id`, `quantidade`, `comprado`) VALUES
(1, 1, NULL, 'Ovo', 1, '1', 0),
(2, 1, NULL, 'Sabão', 2, '1', 0),
(3, 2, NULL, 'Ovo', 1, '1', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `itensmestre`
--

CREATE TABLE `itensmestre` (
  `id` int(11) NOT NULL,
  `nome_item` varchar(100) NOT NULL,
  `categoria_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `itensmestre`
--

INSERT INTO `itensmestre` (`id`, `nome_item`, `categoria_id`) VALUES
(1, 'Leite Integral', 1),
(2, 'Queijo Prato', 1),
(3, 'Sabão em Pó', 2),
(4, 'Água Sanitária', 2),
(5, 'Macarrão Espaguete', 3),
(6, 'Arroz', 3),
(7, 'Maçã', 4),
(8, 'Refrigerante', 5),
(9, 'Pão Francês', 6),
(10, 'Shampoo', 7);

-- --------------------------------------------------------

--
-- Estrutura para tabela `listas`
--

CREATE TABLE `listas` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `nome_lista` varchar(100) NOT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `valor_total` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `listas`
--

INSERT INTO `listas` (`id`, `usuario_id`, `nome_lista`, `data_criacao`, `valor_total`) VALUES
(1, 1, 'Compras', '2025-11-06 22:28:06', NULL),
(2, 1, 'João', '2025-11-24 19:25:53', 20.00);

-- --------------------------------------------------------

--
-- Estrutura para tabela `mapeamentocorredores`
--

CREATE TABLE `mapeamentocorredores` (
  `id` int(11) NOT NULL,
  `corredor_id` int(11) NOT NULL,
  `categoria_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `mapeamentocorredores`
--

INSERT INTO `mapeamentocorredores` (`id`, `corredor_id`, `categoria_id`) VALUES
(1, 1, 4),
(2, 2, 1),
(3, 3, 6),
(4, 4, 5),
(5, 5, 2),
(6, 6, 7),
(7, 7, 3),
(8, 8, 2),
(9, 8, 7),
(10, 9, 3),
(11, 10, 4),
(12, 11, 1),
(13, 11, 6),
(14, 12, 5);

-- --------------------------------------------------------

--
-- Estrutura para tabela `supermercados`
--

CREATE TABLE `supermercados` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `url_mapa_imagem` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `supermercados`
--

INSERT INTO `supermercados` (`id`, `nome`, `endereco`, `url_mapa_imagem`) VALUES
(1, 'Supermercado Central', 'Rua A, 123', 'mapas/mercado-1.png'),
(2, 'Mercado do Bairro', 'Av. B, 456', 'mapas/mercado-2.png');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha_hash`, `created_at`) VALUES
(1, 'João', 'joao@gmail.com', '$2y$10$XUBWaQj9IPHme6nsB0QoUOJ69RG1NcFBAOSR.IRpmvW6wHq5sW./2', '2025-11-06 22:27:23');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome_categoria` (`nome_categoria`);

--
-- Índices de tabela `corredores`
--
ALTER TABLE `corredores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supermercado_id` (`supermercado_id`);

--
-- Índices de tabela `itensdalista`
--
ALTER TABLE `itensdalista`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lista_id` (`lista_id`),
  ADD KEY `item_mestre_id` (`item_mestre_id`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- Índices de tabela `itensmestre`
--
ALTER TABLE `itensmestre`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome_item` (`nome_item`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- Índices de tabela `listas`
--
ALTER TABLE `listas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `mapeamentocorredores`
--
ALTER TABLE `mapeamentocorredores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `corredor_id` (`corredor_id`,`categoria_id`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- Índices de tabela `supermercados`
--
ALTER TABLE `supermercados`
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
-- AUTO_INCREMENT de tabela `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `corredores`
--
ALTER TABLE `corredores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `itensdalista`
--
ALTER TABLE `itensdalista`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `itensmestre`
--
ALTER TABLE `itensmestre`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `listas`
--
ALTER TABLE `listas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `mapeamentocorredores`
--
ALTER TABLE `mapeamentocorredores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de tabela `supermercados`
--
ALTER TABLE `supermercados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `corredores`
--
ALTER TABLE `corredores`
  ADD CONSTRAINT `corredores_ibfk_1` FOREIGN KEY (`supermercado_id`) REFERENCES `supermercados` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `itensdalista`
--
ALTER TABLE `itensdalista`
  ADD CONSTRAINT `itensdalista_ibfk_1` FOREIGN KEY (`lista_id`) REFERENCES `listas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `itensdalista_ibfk_2` FOREIGN KEY (`item_mestre_id`) REFERENCES `itensmestre` (`id`),
  ADD CONSTRAINT `itensdalista_ibfk_3` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE SET NULL;

--
-- Restrições para tabelas `itensmestre`
--
ALTER TABLE `itensmestre`
  ADD CONSTRAINT `itensmestre_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`);

--
-- Restrições para tabelas `listas`
--
ALTER TABLE `listas`
  ADD CONSTRAINT `listas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `mapeamentocorredores`
--
ALTER TABLE `mapeamentocorredores`
  ADD CONSTRAINT `mapeamentocorredores_ibfk_1` FOREIGN KEY (`corredor_id`) REFERENCES `corredores` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `mapeamentocorredores_ibfk_2` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

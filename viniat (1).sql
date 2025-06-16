-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 16-Jun-2025 às 22:34
-- Versão do servidor: 10.4.32-MariaDB
-- versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `viniat`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `armazem`
--

CREATE TABLE `armazem` (
  `id_armazem` int(11) NOT NULL,
  `morada` varchar(255) NOT NULL,
  `area_total_m2` decimal(10,2) NOT NULL,
  `criado_por` varchar(100) NOT NULL,
  `data_criacao` datetime DEFAULT current_timestamp(),
  `modificado_por` varchar(100) DEFAULT NULL,
  `data_modificacao` datetime DEFAULT NULL,
  `estado_registo` varchar(10) DEFAULT 'ativo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `armazem`
--

INSERT INTO `armazem` (`id_armazem`, `morada`, `area_total_m2`, `criado_por`, `data_criacao`, `modificado_por`, `data_modificacao`, `estado_registo`) VALUES
(1, 'Rua do Vinho, 123 - Lisboa', 1500.50, 'admin', '2025-06-07 14:11:44', NULL, NULL, 'ativo');

-- --------------------------------------------------------

--
-- Estrutura da tabela `casta`
--

CREATE TABLE `casta` (
  `id_casta` int(11) NOT NULL,
  `nome_casta` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `casta`
--

INSERT INTO `casta` (`id_casta`, `nome_casta`) VALUES
(1, 'Touriga Nacional'),
(2, 'Aragonez'),
(3, 'Castelão'),
(4, 'Field blend Vinhas Velhas'),
(5, 'Syrah'),
(6, 'Alvarinho'),
(7, 'Antão Vaz'),
(8, 'Loureiro'),
(9, 'Bical'),
(10, 'Baga'),
(11, 'Gouveio');

-- --------------------------------------------------------

--
-- Estrutura da tabela `cliente`
--

CREATE TABLE `cliente` (
  `id_cliente` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `nif` varchar(15) DEFAULT NULL,
  `morada` varchar(255) DEFAULT NULL,
  `genero` varchar(20) DEFAULT NULL,
  `criado_por` varchar(100) NOT NULL,
  `data_criacao` datetime DEFAULT current_timestamp(),
  `modificado_por` varchar(100) DEFAULT NULL,
  `data_modificacao` datetime DEFAULT NULL,
  `estado_registo` varchar(10) DEFAULT 'ativo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `cliente`
--

INSERT INTO `cliente` (`id_cliente`, `nome`, `email`, `senha`, `nif`, `morada`, `genero`, `criado_por`, `data_criacao`, `modificado_por`, `data_modificacao`, `estado_registo`) VALUES
(1, 'Rui Ferreira', '', '', '123456789', 'Rua das Uvas, Lisboa', 'Masculino', 'admin', '2025-06-07 14:11:44', NULL, NULL, 'ativo'),
(4, 'João', 'joao@exemplo.com', '$2y$10$Bj43FVrL172q9GkWQR3JIerOGCt35807ajWA1Bs6Q71u.Y3oMrJbu', '123456789', 'Rua da Ameijôa nº39', 'Masculino', 'registo', '2025-06-13 20:15:52', NULL, NULL, 'ativo'),
(5, 'Gonçalo', 'goncalo@exemplo.com', '$2y$10$og65hMJ/FH4kfhw.xW.EOeXboNQ2k0o0nktIAhO8Mv0gGatHDdGHC', '240785612', 'Rua do Marinheiro nº3', 'Masculino', 'registo', '2025-06-13 20:19:11', NULL, NULL, 'ativo');

-- --------------------------------------------------------

--
-- Estrutura da tabela `empregado`
--

CREATE TABLE `empregado` (
  `id_empregado` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `numero_empregado` varchar(50) NOT NULL,
  `genero` varchar(20) DEFAULT NULL,
  `data_nascimento` date NOT NULL,
  `morada_residencia` varchar(255) DEFAULT NULL,
  `nacionalidade` varchar(50) DEFAULT NULL,
  `area_funcional` varchar(100) DEFAULT NULL,
  `categoria_funcional` varchar(100) DEFAULT NULL,
  `criado_por` varchar(100) NOT NULL,
  `data_criacao` datetime DEFAULT current_timestamp(),
  `modificado_por` varchar(100) DEFAULT NULL,
  `data_modificacao` datetime DEFAULT NULL,
  `estado_registo` varchar(10) DEFAULT 'ativo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `empregado`
--

INSERT INTO `empregado` (`id_empregado`, `nome`, `numero_empregado`, `genero`, `data_nascimento`, `morada_residencia`, `nacionalidade`, `area_funcional`, `categoria_funcional`, `criado_por`, `data_criacao`, `modificado_por`, `data_modificacao`, `estado_registo`) VALUES
(3, 'Vitor Silva', '134', 'Masculino', '1998-01-06', 'Rua da Madeira nº22', 'Portuguesa', NULL, NULL, '', '2025-06-16 21:07:09', NULL, NULL, 'ativo'),
(4, 'Bruna Rodrigues', '278', 'Feminino', '2000-05-10', 'Rua do Quadro nº2', 'Portuguesa', NULL, NULL, '', '2025-06-16 21:30:20', NULL, NULL, 'ativo');

-- --------------------------------------------------------

--
-- Estrutura da tabela `encomenda_cliente`
--

CREATE TABLE `encomenda_cliente` (
  `id_encomenda` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `data_encomenda` datetime NOT NULL DEFAULT current_timestamp(),
  `morada_entrega` varchar(255) DEFAULT NULL,
  `data_prevista_entrega` date DEFAULT NULL,
  `valor_total_produto` decimal(10,2) DEFAULT NULL,
  `valor_total_transporte` decimal(10,2) DEFAULT NULL,
  `valor_total_impostos` decimal(10,2) DEFAULT NULL,
  `estado` varchar(20) DEFAULT NULL,
  `tracking_id` varchar(100) NOT NULL,
  `transportadora` varchar(20) NOT NULL,
  `criado_por` varchar(100) NOT NULL,
  `data_criacao` datetime DEFAULT current_timestamp(),
  `modificado_por` varchar(100) DEFAULT NULL,
  `data_modificacao` datetime DEFAULT NULL,
  `estado_registo` varchar(10) DEFAULT 'ativo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `encomenda_cliente`
--

INSERT INTO `encomenda_cliente` (`id_encomenda`, `id_cliente`, `data_encomenda`, `morada_entrega`, `data_prevista_entrega`, `valor_total_produto`, `valor_total_transporte`, `valor_total_impostos`, `estado`, `tracking_id`, `transportadora`, `criado_por`, `data_criacao`, `modificado_por`, `data_modificacao`, `estado_registo`) VALUES
(1, 1, '2025-06-14 23:07:17', 'Rua das Uvas, Lisboa', '2025-06-10', 25.00, 5.00, 6.90, '0000-00-00 00:00:00', '', '', 'admin', '2025-06-07 14:11:44', 'teste', '2025-06-07 18:26:26', 'ativo'),
(126, 4, '2025-06-16 19:43:55', 'Morada do Cliente', '2025-06-19', 150.00, 5.00, 34.50, 'Entregue', 'TRK-C5ED0587', 'DHL', '4', '2025-06-16 19:43:55', NULL, NULL, 'ativo'),
(127, 4, '2025-06-16 19:44:20', 'Morada do Cliente', '2025-06-19', 18.00, 5.00, 4.14, 'Entregue', 'TRK-D11C2DD6', 'DHL', '4', '2025-06-16 19:44:20', NULL, NULL, 'ativo'),
(128, 4, '2025-06-16 20:09:36', 'Morada do Cliente', '2025-06-19', 55.00, 5.00, 12.65, 'Entregue', 'TRK-69B866CB', 'DHL', '4', '2025-06-16 20:09:36', NULL, NULL, 'ativo'),
(129, 4, '2025-06-16 20:37:30', 'Morada do Cliente', '2025-06-19', 45.00, 5.00, 10.35, 'Entregue', 'TRK-91651EEC', 'MRW', '4', '2025-06-16 20:37:30', NULL, NULL, 'ativo');

-- --------------------------------------------------------

--
-- Estrutura da tabela `encomenda_cliente_produto`
--

CREATE TABLE `encomenda_cliente_produto` (
  `id_encomenda` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `quantidade` int(11) DEFAULT NULL,
  `valor_unitario` decimal(10,2) DEFAULT NULL,
  `valor_iva` decimal(10,2) DEFAULT NULL,
  `valor_total` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `encomenda_cliente_produto`
--

INSERT INTO `encomenda_cliente_produto` (`id_encomenda`, `id_produto`, `quantidade`, `valor_unitario`, `valor_iva`, `valor_total`) VALUES
(126, 43, 1, 150.00, 34.50, 150.00),
(127, 40, 1, 18.00, 4.14, 18.00),
(128, 41, 1, 55.00, 12.65, 55.00),
(129, 39, 1, 45.00, 10.35, 45.00);

-- --------------------------------------------------------

--
-- Estrutura da tabela `encomenda_fornecedor`
--

CREATE TABLE `encomenda_fornecedor` (
  `id_encomenda` int(11) NOT NULL,
  `id_fornecedor` int(11) NOT NULL,
  `data_criacao` datetime NOT NULL,
  `data_prevista_entrega` date DEFAULT NULL,
  `total_encomenda` decimal(10,2) DEFAULT NULL,
  `total_iva` decimal(10,2) DEFAULT NULL,
  `estado` varchar(20) DEFAULT NULL,
  `criado_por` varchar(100) NOT NULL,
  `data_criacao_registo` datetime DEFAULT current_timestamp(),
  `modificado_por` varchar(100) DEFAULT NULL,
  `data_modificacao` datetime DEFAULT NULL,
  `estado_registo` varchar(10) DEFAULT 'ativo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `encomenda_fornecedor`
--

INSERT INTO `encomenda_fornecedor` (`id_encomenda`, `id_fornecedor`, `data_criacao`, `data_prevista_entrega`, `total_encomenda`, `total_iva`, `estado`, `criado_por`, `data_criacao_registo`, `modificado_por`, `data_modificacao`, `estado_registo`) VALUES
(1, 1, '2025-06-01 00:00:00', '2025-06-10', 250.00, 57.50, 'confirmada', 'admin', '2025-06-07 14:11:44', NULL, NULL, 'ativo');

-- --------------------------------------------------------

--
-- Estrutura da tabela `encomenda_fornecedor_produto`
--

CREATE TABLE `encomenda_fornecedor_produto` (
  `id_encomenda` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `valor_unitario` decimal(10,2) DEFAULT NULL,
  `valor_iva` decimal(10,2) DEFAULT NULL,
  `valor_total` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `fatura_fornecedor`
--

CREATE TABLE `fatura_fornecedor` (
  `id_fatura` int(11) NOT NULL,
  `id_encomenda` int(11) DEFAULT NULL,
  `data_emissao` date NOT NULL,
  `data_validade` date DEFAULT NULL,
  `data_pagamento` date DEFAULT NULL,
  `total_faturado` decimal(10,2) DEFAULT NULL,
  `total_iva` decimal(10,2) DEFAULT NULL,
  `estado` varchar(20) DEFAULT NULL,
  `criado_por` varchar(100) NOT NULL,
  `data_criacao` datetime DEFAULT current_timestamp(),
  `modificado_por` varchar(100) DEFAULT NULL,
  `data_modificacao` datetime DEFAULT NULL,
  `estado_registo` varchar(10) DEFAULT 'ativo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `fatura_fornecedor`
--

INSERT INTO `fatura_fornecedor` (`id_fatura`, `id_encomenda`, `data_emissao`, `data_validade`, `data_pagamento`, `total_faturado`, `total_iva`, `estado`, `criado_por`, `data_criacao`, `modificado_por`, `data_modificacao`, `estado_registo`) VALUES
(1, 1, '2025-06-02', '2025-07-02', '2025-06-05', 250.00, 57.50, 'emitida', 'admin', '2025-06-07 14:11:44', NULL, NULL, 'ativo');

-- --------------------------------------------------------

--
-- Estrutura da tabela `fatura_venda`
--

CREATE TABLE `fatura_venda` (
  `id_fatura` int(11) NOT NULL,
  `id_encomenda` int(11) DEFAULT NULL,
  `data_emissao` date NOT NULL,
  `data_validade` date DEFAULT NULL,
  `data_pagamento` date DEFAULT NULL,
  `total_faturado` decimal(10,2) DEFAULT NULL,
  `total_iva` decimal(10,2) DEFAULT NULL,
  `estado` varchar(20) DEFAULT NULL,
  `criado_por` varchar(100) NOT NULL,
  `data_criacao` datetime DEFAULT current_timestamp(),
  `modificado_por` varchar(100) DEFAULT NULL,
  `data_modificacao` datetime DEFAULT NULL,
  `estado_registo` varchar(10) DEFAULT 'ativo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `fatura_venda`
--

INSERT INTO `fatura_venda` (`id_fatura`, `id_encomenda`, `data_emissao`, `data_validade`, `data_pagamento`, `total_faturado`, `total_iva`, `estado`, `criado_por`, `data_criacao`, `modificado_por`, `data_modificacao`, `estado_registo`) VALUES
(1, 1, '2025-06-06', '2025-07-06', '2025-06-07', 30.00, 6.90, 'emitida', 'admin', '2025-06-07 14:11:44', NULL, NULL, 'ativo');

-- --------------------------------------------------------

--
-- Estrutura da tabela `fornecedor`
--

CREATE TABLE `fornecedor` (
  `id_fornecedor` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `nif` varchar(15) NOT NULL,
  `morada` varchar(255) DEFAULT NULL,
  `nome_responsavel` varchar(100) DEFAULT NULL,
  `criado_por` varchar(100) NOT NULL,
  `data_criacao` datetime DEFAULT current_timestamp(),
  `modificado_por` varchar(100) DEFAULT NULL,
  `data_modificacao` datetime DEFAULT NULL,
  `estado_registo` varchar(10) DEFAULT 'ativo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `fornecedor`
--

INSERT INTO `fornecedor` (`id_fornecedor`, `nome`, `nif`, `morada`, `nome_responsavel`, `criado_por`, `data_criacao`, `modificado_por`, `data_modificacao`, `estado_registo`) VALUES
(1, 'Adega do Norte', '507000000', 'Braga', 'Carlos Mendes', 'admin', '2025-06-07 14:11:44', NULL, NULL, 'ativo'),
(2, 'Quinta Sul', '508000111', 'Évora', 'Mariana Lopes', 'admin', '2025-06-07 14:11:44', NULL, NULL, 'ativo');

-- --------------------------------------------------------

--
-- Estrutura da tabela `ocorrencia`
--

CREATE TABLE `ocorrencia` (
  `id_ocorrencia` int(11) NOT NULL,
  `id_encomenda` int(11) NOT NULL,
  `data_registo` date NOT NULL,
  `data_resolucao` date DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `registado_por` varchar(100) DEFAULT NULL,
  `resolvido_por` varchar(100) DEFAULT NULL,
  `motivo` varchar(255) DEFAULT NULL,
  `estado` varchar(20) DEFAULT NULL,
  `criado_por` varchar(100) NOT NULL,
  `data_criacao` datetime DEFAULT current_timestamp(),
  `modificado_por` varchar(100) DEFAULT NULL,
  `data_modificacao` datetime DEFAULT NULL,
  `estado_registo` varchar(10) DEFAULT 'ativo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `ocorrencia`
--

INSERT INTO `ocorrencia` (`id_ocorrencia`, `id_encomenda`, `data_registo`, `data_resolucao`, `descricao`, `registado_por`, `resolvido_por`, `motivo`, `estado`, `criado_por`, `data_criacao`, `modificado_por`, `data_modificacao`, `estado_registo`) VALUES
(1, 1, '2025-06-07', NULL, 'Embalagem danificada', 'Rui Ferreira', NULL, 'Produto danificado', 'pendente', 'admin', '2025-06-07 14:11:44', NULL, NULL, 'ativo');

-- --------------------------------------------------------

--
-- Estrutura da tabela `produto`
--

CREATE TABLE `produto` (
  `id_produto` int(11) NOT NULL,
  `nome_vinho` varchar(100) NOT NULL,
  `lista_castas` varchar(100) NOT NULL,
  `regiao` varchar(50) DEFAULT NULL,
  `valor` decimal(10,2) NOT NULL,
  `criado_por` varchar(100) NOT NULL,
  `data_criacao` datetime DEFAULT current_timestamp(),
  `modificado_por` varchar(100) DEFAULT NULL,
  `data_modificacao` datetime DEFAULT NULL,
  `estado_registo` varchar(10) DEFAULT 'ativo',
  `imagem` varchar(255) DEFAULT NULL,
  `tipo_vinho` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `produto`
--

INSERT INTO `produto` (`id_produto`, `nome_vinho`, `lista_castas`, `regiao`, `valor`, `criado_por`, `data_criacao`, `modificado_por`, `data_modificacao`, `estado_registo`, `imagem`, `tipo_vinho`) VALUES
(25, 'Filipa Pato 3B Blanc de Blancs', '', 'Bairrada', 12.50, 'teste', '2025-06-13 00:40:46', NULL, NULL, 'ativo', 'imagens/684b657ea2cb9.jpg', 'Espumante'),
(26, 'Caves São João Bruto', '', 'Bairrada', 10.00, 'teste', '2025-06-13 00:41:18', NULL, NULL, 'ativo', 'imagens/684b659e827ba.jpg', 'Espumante'),
(27, 'Vértice Gouveio', '', 'Douro', 18.00, 'teste', '2025-06-13 00:42:01', NULL, NULL, 'ativo', 'imagens/684b65c977239.jpg', 'Espumante'),
(28, 'Murganheira Reserva Bruto', '', 'Távora-Varosa', 14.00, 'teste', '2025-06-13 00:42:53', NULL, NULL, 'ativo', 'imagens/684b65fd3d476.jpg', 'Espumante'),
(29, 'Monte da Ravasqueira', '', 'Alentejo', 7.50, 'teste', '2025-06-13 00:44:01', NULL, NULL, 'ativo', 'imagens/684b66410ac43.jpg', 'Rosé'),
(30, 'Quinta da Alorna', '', 'Tejo', 6.00, 'teste', '2025-06-13 00:44:41', NULL, NULL, 'ativo', 'imagens/684b666979ed4.jpg', 'Rosé'),
(31, 'Mateus Original', '', 'Bairrada', 4.50, 'teste', '2025-06-13 00:45:34', NULL, NULL, 'ativo', 'imagens/684b669ed66d3.jpg', 'Rosé'),
(32, 'Vinha Formal (Luis Pato)', '', 'Bairrada', 18.00, 'teste', '2025-06-13 00:46:47', NULL, NULL, 'ativo', 'imagens/684b66e77ed84.jpg', 'Branco'),
(33, 'Muros Antigos Loureiro', '', 'Vinho Verde', 9.00, 'teste', '2025-06-13 00:47:55', NULL, NULL, 'ativo', 'imagens/684b672be59f8.jpg', 'Branco'),
(34, 'Esporão Reserva', '', 'Alentejo', 15.00, 'teste', '2025-06-13 00:48:46', NULL, NULL, 'ativo', 'imagens/684b675e85705.jpg', 'Branco'),
(35, 'Quinta da Aveleda Loureiro & Alvarinho', '', 'Vinho Verde', 7.00, 'teste', '2025-06-13 00:50:57', NULL, NULL, 'ativo', 'imagens/684b67e1d6fa5.jpg', 'Branco'),
(36, 'Soalheiro Alvarinho', '', 'Vinho Verde', 13.00, 'teste', '2025-06-13 00:52:08', NULL, NULL, 'ativo', 'imagens/684b682818178.jpg', 'Branco'),
(37, 'CARM Reserva', '', 'Douro', 20.00, 'teste', '2025-06-13 00:52:48', NULL, NULL, 'ativo', 'imagens/684b6850d21d8.jpg', 'Tinto'),
(38, 'Herdade do Peso Colheita', '', 'Alentejo', 12.00, 'teste', '2025-06-13 00:53:41', NULL, NULL, 'ativo', 'imagens/684b6885d205b.jpg', 'Tinto'),
(39, 'Quinta da Leda', '', 'Douro', 45.00, 'teste', '2025-06-13 00:54:17', NULL, NULL, 'ativo', 'imagens/684e360ebafff.jpg', 'Tinto'),
(40, 'Esporão Reserva', '', 'Alentejo', 18.00, 'teste', '2025-06-13 00:55:25', NULL, NULL, 'ativo', 'imagens/684b68edd1717.jpg', 'Tinto'),
(41, 'Vale Meão', '', 'Douro Superior', 55.00, 'teste', '2025-06-13 00:55:59', NULL, NULL, 'ativo', 'imagens/684e35efc39f1.jpg', 'Tinto'),
(42, 'Quinta do Crasto Reserva Vinhas Velhas', '', 'Douro', 35.00, 'teste', '2025-06-13 00:57:01', NULL, NULL, 'ativo', 'imagens/684e35da52df0.jpg', 'Tinto'),
(43, 'Pêra Manca', '', 'Alentejo', 150.00, 'teste', '2025-06-13 00:57:29', NULL, NULL, 'ativo', 'imagens/684e35bb417cc.jpg', 'Tinto'),
(44, 'Barca Velha', '', 'Douro', 250.00, 'teste', '2025-06-13 00:57:53', NULL, NULL, 'ativo', 'imagens/68503a00e81c2.jpg', 'Tinto');

-- --------------------------------------------------------

--
-- Estrutura da tabela `produto_casta`
--

CREATE TABLE `produto_casta` (
  `id_produto` int(11) NOT NULL,
  `id_casta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `produto_casta`
--

INSERT INTO `produto_casta` (`id_produto`, `id_casta`) VALUES
(25, 9),
(26, 10),
(27, 11),
(28, 11),
(29, 5),
(30, 1),
(31, 10),
(32, 8),
(33, 8),
(34, 7),
(35, 8),
(36, 6),
(37, 1),
(38, 5),
(39, 1),
(40, 2),
(41, 1),
(42, 4),
(43, 2),
(44, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `produto_fornecedor`
--

CREATE TABLE `produto_fornecedor` (
  `id_produto` int(11) NOT NULL,
  `id_fornecedor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `promocao`
--

CREATE TABLE `promocao` (
  `id_promocao` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `data_criacao` date NOT NULL,
  `data_validade` date DEFAULT NULL,
  `valor_promocao` decimal(10,2) DEFAULT NULL,
  `motivo_promocao` text DEFAULT NULL,
  `estado` varchar(20) DEFAULT NULL,
  `criado_por` varchar(100) NOT NULL,
  `data_criacao_registo` datetime DEFAULT current_timestamp(),
  `modificado_por` varchar(100) DEFAULT NULL,
  `data_modificacao` datetime DEFAULT NULL,
  `estado_registo` varchar(10) DEFAULT 'ativo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `stock_produto`
--

CREATE TABLE `stock_produto` (
  `id_stock` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `data_ultima_entrada` date DEFAULT NULL,
  `data_ultima_saida` date DEFAULT NULL,
  `criado_por` varchar(100) NOT NULL,
  `data_criacao` datetime DEFAULT current_timestamp(),
  `modificado_por` varchar(100) DEFAULT NULL,
  `data_modificacao` datetime DEFAULT NULL,
  `estado_registo` varchar(10) DEFAULT 'ativo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `stock_produto`
--

INSERT INTO `stock_produto` (`id_stock`, `id_produto`, `quantidade`, `data_ultima_entrada`, `data_ultima_saida`, `criado_por`, `data_criacao`, `modificado_por`, `data_modificacao`, `estado_registo`) VALUES
(7, 25, 35, '2025-06-13', NULL, 'teste', '2025-06-13 00:40:46', NULL, NULL, 'ativo'),
(8, 26, 30, '2025-06-13', NULL, 'teste', '2025-06-13 00:41:18', NULL, NULL, 'ativo'),
(9, 27, 23, '2025-06-13', NULL, 'teste', '2025-06-13 00:42:01', NULL, NULL, 'ativo'),
(10, 28, 27, '2025-06-13', NULL, 'teste', '2025-06-13 00:42:53', NULL, NULL, 'ativo'),
(11, 29, 38, '2025-06-13', NULL, 'teste', '2025-06-13 00:44:01', NULL, NULL, 'ativo'),
(12, 30, 44, '2025-06-13', NULL, 'teste', '2025-06-13 00:44:41', NULL, NULL, 'ativo'),
(13, 31, 90, '2025-06-13', NULL, 'teste', '2025-06-13 00:45:34', NULL, NULL, 'ativo'),
(14, 32, 15, '2025-06-13', NULL, 'teste', '2025-06-13 00:46:47', NULL, NULL, 'ativo'),
(15, 33, 41, '2025-06-13', NULL, 'teste', '2025-06-13 00:47:55', NULL, NULL, 'ativo'),
(16, 34, 18, '2025-06-13', NULL, 'teste', '2025-06-13 00:48:46', NULL, NULL, 'ativo'),
(17, 35, 21, '2025-06-13', NULL, 'teste', '2025-06-13 00:50:57', NULL, NULL, 'ativo'),
(18, 36, 12, '2025-06-13', NULL, 'teste', '2025-06-13 00:52:08', NULL, NULL, 'ativo'),
(19, 37, 3, '2025-06-13', NULL, 'teste', '2025-06-13 00:52:48', NULL, NULL, 'ativo'),
(20, 38, 20, '2025-06-13', NULL, 'teste', '2025-06-13 00:53:41', NULL, NULL, 'ativo'),
(21, 39, 2, '2025-06-15', NULL, 'teste', '2025-06-13 00:54:17', NULL, NULL, 'ativo'),
(22, 40, 5, '2025-06-13', NULL, 'teste', '2025-06-13 00:55:25', NULL, NULL, 'ativo'),
(23, 41, 9, '2025-06-15', NULL, 'teste', '2025-06-13 00:55:59', NULL, NULL, 'ativo'),
(24, 42, 34, '2025-06-15', NULL, 'teste', '2025-06-13 00:57:01', NULL, NULL, 'ativo'),
(25, 43, 7, '2025-06-15', NULL, 'teste', '2025-06-13 00:57:29', NULL, NULL, 'ativo'),
(26, 44, 6, '2025-06-16', NULL, 'teste', '2025-06-13 00:57:53', NULL, NULL, 'ativo');

-- --------------------------------------------------------

--
-- Estrutura da tabela `transportadora`
--

CREATE TABLE `transportadora` (
  `id_transportadora` int(11) NOT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `nif` varchar(15) DEFAULT NULL,
  `morada` varchar(255) DEFAULT NULL,
  `nome_responsavel` varchar(100) DEFAULT NULL,
  `criado_por` varchar(100) NOT NULL,
  `data_criacao` datetime DEFAULT current_timestamp(),
  `modificado_por` varchar(100) DEFAULT NULL,
  `data_modificacao` datetime DEFAULT NULL,
  `estado_registo` varchar(10) DEFAULT 'ativo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `transportadora`
--

INSERT INTO `transportadora` (`id_transportadora`, `nome`, `nif`, `morada`, `nome_responsavel`, `criado_por`, `data_criacao`, `modificado_por`, `data_modificacao`, `estado_registo`) VALUES
(1, 'Cargas Express', '501112223', 'Porto', 'Vítor Teixeira', 'admin', '2025-06-07 14:11:44', NULL, NULL, 'ativo');

-- --------------------------------------------------------

--
-- Estrutura da tabela `transportadora_distrito`
--

CREATE TABLE `transportadora_distrito` (
  `id_transportadora` int(11) NOT NULL,
  `distrito` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `transportadora_distrito`
--

INSERT INTO `transportadora_distrito` (`id_transportadora`, `distrito`) VALUES
(1, 'Lisboa'),
(1, 'Setúbal');

-- --------------------------------------------------------

--
-- Estrutura da tabela `transporte`
--

CREATE TABLE `transporte` (
  `id_transporte` int(11) NOT NULL,
  `id_transportadora` int(11) NOT NULL,
  `id_encomenda` int(11) NOT NULL,
  `data_saida` date DEFAULT NULL,
  `data_entrega` date DEFAULT NULL,
  `estado` varchar(20) DEFAULT NULL,
  `custo_total` decimal(10,2) DEFAULT NULL,
  `criado_por` varchar(100) NOT NULL,
  `data_criacao` datetime DEFAULT current_timestamp(),
  `modificado_por` varchar(100) DEFAULT NULL,
  `data_modificacao` datetime DEFAULT NULL,
  `estado_registo` varchar(10) DEFAULT 'ativo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `transporte`
--

INSERT INTO `transporte` (`id_transporte`, `id_transportadora`, `id_encomenda`, `data_saida`, `data_entrega`, `estado`, `custo_total`, `criado_por`, `data_criacao`, `modificado_por`, `data_modificacao`, `estado_registo`) VALUES
(1, 1, 1, '2025-06-07', NULL, 'em trânsito', 5.00, 'admin', '2025-06-07 14:11:44', NULL, NULL, 'ativo');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `armazem`
--
ALTER TABLE `armazem`
  ADD PRIMARY KEY (`id_armazem`);

--
-- Índices para tabela `casta`
--
ALTER TABLE `casta`
  ADD PRIMARY KEY (`id_casta`);

--
-- Índices para tabela `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id_cliente`);

--
-- Índices para tabela `empregado`
--
ALTER TABLE `empregado`
  ADD PRIMARY KEY (`id_empregado`);

--
-- Índices para tabela `encomenda_cliente`
--
ALTER TABLE `encomenda_cliente`
  ADD PRIMARY KEY (`id_encomenda`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- Índices para tabela `encomenda_cliente_produto`
--
ALTER TABLE `encomenda_cliente_produto`
  ADD PRIMARY KEY (`id_encomenda`,`id_produto`),
  ADD KEY `id_produto` (`id_produto`);

--
-- Índices para tabela `encomenda_fornecedor`
--
ALTER TABLE `encomenda_fornecedor`
  ADD PRIMARY KEY (`id_encomenda`),
  ADD KEY `id_fornecedor` (`id_fornecedor`);

--
-- Índices para tabela `encomenda_fornecedor_produto`
--
ALTER TABLE `encomenda_fornecedor_produto`
  ADD PRIMARY KEY (`id_encomenda`,`id_produto`),
  ADD KEY `id_produto` (`id_produto`);

--
-- Índices para tabela `fatura_fornecedor`
--
ALTER TABLE `fatura_fornecedor`
  ADD PRIMARY KEY (`id_fatura`),
  ADD KEY `id_encomenda` (`id_encomenda`);

--
-- Índices para tabela `fatura_venda`
--
ALTER TABLE `fatura_venda`
  ADD PRIMARY KEY (`id_fatura`),
  ADD KEY `id_encomenda` (`id_encomenda`);

--
-- Índices para tabela `fornecedor`
--
ALTER TABLE `fornecedor`
  ADD PRIMARY KEY (`id_fornecedor`);

--
-- Índices para tabela `ocorrencia`
--
ALTER TABLE `ocorrencia`
  ADD PRIMARY KEY (`id_ocorrencia`),
  ADD KEY `id_encomenda` (`id_encomenda`);

--
-- Índices para tabela `produto`
--
ALTER TABLE `produto`
  ADD PRIMARY KEY (`id_produto`);

--
-- Índices para tabela `produto_casta`
--
ALTER TABLE `produto_casta`
  ADD PRIMARY KEY (`id_produto`,`id_casta`),
  ADD KEY `id_casta` (`id_casta`);

--
-- Índices para tabela `produto_fornecedor`
--
ALTER TABLE `produto_fornecedor`
  ADD PRIMARY KEY (`id_produto`,`id_fornecedor`),
  ADD KEY `id_fornecedor` (`id_fornecedor`);

--
-- Índices para tabela `promocao`
--
ALTER TABLE `promocao`
  ADD PRIMARY KEY (`id_promocao`),
  ADD KEY `id_produto` (`id_produto`);

--
-- Índices para tabela `stock_produto`
--
ALTER TABLE `stock_produto`
  ADD PRIMARY KEY (`id_stock`),
  ADD KEY `id_produto` (`id_produto`);

--
-- Índices para tabela `transportadora`
--
ALTER TABLE `transportadora`
  ADD PRIMARY KEY (`id_transportadora`);

--
-- Índices para tabela `transportadora_distrito`
--
ALTER TABLE `transportadora_distrito`
  ADD PRIMARY KEY (`id_transportadora`,`distrito`);

--
-- Índices para tabela `transporte`
--
ALTER TABLE `transporte`
  ADD PRIMARY KEY (`id_transporte`),
  ADD KEY `id_transportadora` (`id_transportadora`),
  ADD KEY `id_encomenda` (`id_encomenda`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `armazem`
--
ALTER TABLE `armazem`
  MODIFY `id_armazem` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `casta`
--
ALTER TABLE `casta`
  MODIFY `id_casta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `empregado`
--
ALTER TABLE `empregado`
  MODIFY `id_empregado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `encomenda_cliente`
--
ALTER TABLE `encomenda_cliente`
  MODIFY `id_encomenda` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=130;

--
-- AUTO_INCREMENT de tabela `encomenda_fornecedor`
--
ALTER TABLE `encomenda_fornecedor`
  MODIFY `id_encomenda` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `fatura_fornecedor`
--
ALTER TABLE `fatura_fornecedor`
  MODIFY `id_fatura` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `fatura_venda`
--
ALTER TABLE `fatura_venda`
  MODIFY `id_fatura` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `fornecedor`
--
ALTER TABLE `fornecedor`
  MODIFY `id_fornecedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `ocorrencia`
--
ALTER TABLE `ocorrencia`
  MODIFY `id_ocorrencia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `produto`
--
ALTER TABLE `produto`
  MODIFY `id_produto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT de tabela `promocao`
--
ALTER TABLE `promocao`
  MODIFY `id_promocao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `stock_produto`
--
ALTER TABLE `stock_produto`
  MODIFY `id_stock` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de tabela `transportadora`
--
ALTER TABLE `transportadora`
  MODIFY `id_transportadora` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `transporte`
--
ALTER TABLE `transporte`
  MODIFY `id_transporte` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `encomenda_cliente`
--
ALTER TABLE `encomenda_cliente`
  ADD CONSTRAINT `encomenda_cliente_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`);

--
-- Limitadores para a tabela `encomenda_cliente_produto`
--
ALTER TABLE `encomenda_cliente_produto`
  ADD CONSTRAINT `encomenda_cliente_produto_ibfk_1` FOREIGN KEY (`id_encomenda`) REFERENCES `encomenda_cliente` (`id_encomenda`),
  ADD CONSTRAINT `encomenda_cliente_produto_ibfk_2` FOREIGN KEY (`id_produto`) REFERENCES `produto` (`id_produto`);

--
-- Limitadores para a tabela `encomenda_fornecedor`
--
ALTER TABLE `encomenda_fornecedor`
  ADD CONSTRAINT `encomenda_fornecedor_ibfk_1` FOREIGN KEY (`id_fornecedor`) REFERENCES `fornecedor` (`id_fornecedor`);

--
-- Limitadores para a tabela `encomenda_fornecedor_produto`
--
ALTER TABLE `encomenda_fornecedor_produto`
  ADD CONSTRAINT `encomenda_fornecedor_produto_ibfk_1` FOREIGN KEY (`id_encomenda`) REFERENCES `encomenda_fornecedor` (`id_encomenda`),
  ADD CONSTRAINT `encomenda_fornecedor_produto_ibfk_2` FOREIGN KEY (`id_produto`) REFERENCES `produto` (`id_produto`);

--
-- Limitadores para a tabela `fatura_fornecedor`
--
ALTER TABLE `fatura_fornecedor`
  ADD CONSTRAINT `fatura_fornecedor_ibfk_1` FOREIGN KEY (`id_encomenda`) REFERENCES `encomenda_fornecedor` (`id_encomenda`);

--
-- Limitadores para a tabela `fatura_venda`
--
ALTER TABLE `fatura_venda`
  ADD CONSTRAINT `fatura_venda_ibfk_1` FOREIGN KEY (`id_encomenda`) REFERENCES `encomenda_cliente` (`id_encomenda`);

--
-- Limitadores para a tabela `ocorrencia`
--
ALTER TABLE `ocorrencia`
  ADD CONSTRAINT `ocorrencia_ibfk_1` FOREIGN KEY (`id_encomenda`) REFERENCES `encomenda_cliente` (`id_encomenda`);

--
-- Limitadores para a tabela `produto_casta`
--
ALTER TABLE `produto_casta`
  ADD CONSTRAINT `produto_casta_ibfk_1` FOREIGN KEY (`id_produto`) REFERENCES `produto` (`id_produto`),
  ADD CONSTRAINT `produto_casta_ibfk_2` FOREIGN KEY (`id_casta`) REFERENCES `casta` (`id_casta`);

--
-- Limitadores para a tabela `produto_fornecedor`
--
ALTER TABLE `produto_fornecedor`
  ADD CONSTRAINT `produto_fornecedor_ibfk_1` FOREIGN KEY (`id_produto`) REFERENCES `produto` (`id_produto`),
  ADD CONSTRAINT `produto_fornecedor_ibfk_2` FOREIGN KEY (`id_fornecedor`) REFERENCES `fornecedor` (`id_fornecedor`);

--
-- Limitadores para a tabela `promocao`
--
ALTER TABLE `promocao`
  ADD CONSTRAINT `promocao_ibfk_1` FOREIGN KEY (`id_produto`) REFERENCES `produto` (`id_produto`);

--
-- Limitadores para a tabela `stock_produto`
--
ALTER TABLE `stock_produto`
  ADD CONSTRAINT `stock_produto_ibfk_1` FOREIGN KEY (`id_produto`) REFERENCES `produto` (`id_produto`);

--
-- Limitadores para a tabela `transportadora_distrito`
--
ALTER TABLE `transportadora_distrito`
  ADD CONSTRAINT `transportadora_distrito_ibfk_1` FOREIGN KEY (`id_transportadora`) REFERENCES `transportadora` (`id_transportadora`);

--
-- Limitadores para a tabela `transporte`
--
ALTER TABLE `transporte`
  ADD CONSTRAINT `transporte_ibfk_1` FOREIGN KEY (`id_transportadora`) REFERENCES `transportadora` (`id_transportadora`),
  ADD CONSTRAINT `transporte_ibfk_2` FOREIGN KEY (`id_encomenda`) REFERENCES `encomenda_cliente` (`id_encomenda`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

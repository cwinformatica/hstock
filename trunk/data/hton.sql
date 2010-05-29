-- phpMyAdmin SQL Dump
-- version 3.2.5
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tempo de Geração: Mai 29, 2010 as 10:58 PM
-- Versão do Servidor: 5.1.43
-- Versão do PHP: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Banco de Dados: `hton`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `componentes`
--

CREATE TABLE IF NOT EXISTS `componentes` (
  `codigo` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `descricao` text COLLATE utf8_unicode_ci NOT NULL,
  `unidade` enum('PÇ','KG','LT','MT') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'PÇ',
  `saldo` decimal(10,6) NOT NULL DEFAULT '0.000000',
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `componentes`
--

INSERT INTO `componentes` (`codigo`, `descricao`, `unidade`, `saldo`) VALUES
('HPCAP0270', 'CAPACITOR 100 NF', 'PÇ', 3000.000000),
('HPRES0205', 'RESISTOR 18K 5%', 'PÇ', 300.000000),
('HPRES0441', 'RESISTOR 1K 5%', 'PÇ', 123.000000),
('HPTRT0052', 'TRANSISTOR BC847', 'PÇ', 987.000000),
('HPTRT0053', 'TRANSISTOR BC857', 'PÇ', 7646.000000),
('HTABR0000', 'ABRAÇADEIRA INSULOK T18R 100MM', 'PÇ', 124.000000),
('HTADS0028', 'FITA DEMARCAÇÃO 50MMX 30MTS', 'PÇ', 9874.000000);

-- --------------------------------------------------------

--
-- Estrutura da tabela `kits`
--

CREATE TABLE IF NOT EXISTS `kits` (
  `componente_codigo` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `produto_final_id` int(11) NOT NULL,
  `quantidade` decimal(10,6) NOT NULL,
  PRIMARY KEY (`componente_codigo`,`produto_final_id`),
  KEY `produto_final_id` (`produto_final_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `kits`
--

INSERT INTO `kits` (`componente_codigo`, `produto_final_id`, `quantidade`) VALUES
('HPCAP0270', 3, 2.000000),
('HPRES0205', 3, 3.000000),
('HPRES0441', 3, 3.000000),
('HPTRT0052', 3, 6.000000),
('HPTRT0053', 3, 7.000000);

-- --------------------------------------------------------

--
-- Estrutura da tabela `modulos`
--

CREATE TABLE IF NOT EXISTS `modulos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Extraindo dados da tabela `modulos`
--

INSERT INTO `modulos` (`id`, `nome`) VALUES
(1, 'Administração'),
(2, 'Estoque'),
(3, 'PCP');

-- --------------------------------------------------------

--
-- Estrutura da tabela `ops`
--

CREATE TABLE IF NOT EXISTS `ops` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` enum('Em aberto','Aguardando por componentes','Em produção','Concluída') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Em aberto',
  `produto_final_id` int(11) NOT NULL,
  `quantidade` decimal(10,6) NOT NULL DEFAULT '0.000000',
  `data` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `produto_final_id` (`produto_final_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Extraindo dados da tabela `ops`
--

INSERT INTO `ops` (`id`, `status`, `produto_final_id`, `quantidade`, `data`) VALUES
(1, 'Em aberto', 3, 500.000000, '2010-05-29 16:53:05');

-- --------------------------------------------------------

--
-- Estrutura da tabela `permissoes`
--

CREATE TABLE IF NOT EXISTS `permissoes` (
  `usuario_id` int(11) NOT NULL,
  `modulo_id` int(11) NOT NULL,
  PRIMARY KEY (`usuario_id`,`modulo_id`),
  KEY `modulo_id` (`modulo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `permissoes`
--

INSERT INTO `permissoes` (`usuario_id`, `modulo_id`) VALUES
(1, 1),
(1, 2),
(1, 3);

-- --------------------------------------------------------

--
-- Estrutura da tabela `produtos_finais`
--

CREATE TABLE IF NOT EXISTS `produtos_finais` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `descricao` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Extraindo dados da tabela `produtos_finais`
--

INSERT INTO `produtos_finais` (`id`, `nome`, `descricao`) VALUES
(3, 'B-SU V1.0', 'PLACA MONTADA DISPLAY B-SU V1.0');

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `nome` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `username`, `password`, `nome`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'Administrador');

--
-- Restrições para as tabelas dumpadas
--

--
-- Restrições para a tabela `kits`
--
ALTER TABLE `kits`
  ADD CONSTRAINT `kits_ibfk_2` FOREIGN KEY (`produto_final_id`) REFERENCES `produtos_finais` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kits_ibfk_1` FOREIGN KEY (`componente_codigo`) REFERENCES `componentes` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para a tabela `ops`
--
ALTER TABLE `ops`
  ADD CONSTRAINT `ops_ibfk_1` FOREIGN KEY (`produto_final_id`) REFERENCES `produtos_finais` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para a tabela `permissoes`
--
ALTER TABLE `permissoes`
  ADD CONSTRAINT `permissoes_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `permissoes_ibfk_1` FOREIGN KEY (`modulo_id`) REFERENCES `modulos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

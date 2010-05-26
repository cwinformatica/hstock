-- phpMyAdmin SQL Dump
-- version 3.3.2
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tempo de Geração: Mai 26, 2010 as 04:09 PM
-- Versão do Servidor: 5.1.45
-- Versão do PHP: 5.3.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Banco de Dados: `hton`
--
CREATE DATABASE IF NOT EXISTS `hton`;
USE `hton`;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `componentes`
--

INSERT INTO `componentes` (`codigo`, `descricao`, `unidade`, `saldo`) VALUES
('HTABR0000', 'ABRAÇADEIRA INSULOK T18R 100MM', 'PÇ', 967.000000),
('HTADS0028', 'FITA DEMARCAÇÃO 50MMX 30MTS', 'PÇ', 6.000000);

-- --------------------------------------------------------

--
-- Estrutura da tabela `kits`
--

CREATE TABLE IF NOT EXISTS `kits` (
  `componente_codigo` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `produto_final_id` int(11) NOT NULL,
  `quantidade` decimal(10,2) NOT NULL,
  PRIMARY KEY (`componente_codigo`,`produto_final_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `kits`
--


-- --------------------------------------------------------

--
-- Estrutura da tabela `produtos_finais`
--

CREATE TABLE IF NOT EXISTS `produtos_finais` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `descricao` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Extraindo dados da tabela `produtos_finais`
--


-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `nome` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `admin` enum('Sim','Não') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Não',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `username`, `password`, `nome`, `admin`) VALUES
(1, 'stefano', '317a58affea472972b63bffdd3392ae0', 'Stefano Martins', 'Sim');

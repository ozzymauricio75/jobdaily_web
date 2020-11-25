-- phpMyAdmin SQL Dump
-- version 2.9.2
-- http://www.phpmyadmin.net
-- 
-- Servidor: localhost
-- Tiempo de generación: 24-11-2020 a las 19:52:44
-- Versión del servidor: 5.0.27
-- Versión de PHP: 5.2.1
-- 
-- Base de datos: `jobdaily`
-- 

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_cuotas_creditos_bancos`
-- 

CREATE TABLE `job_cuotas_creditos_bancos` (
  `codigo` int(9) unsigned zerofill NOT NULL auto_increment COMMENT 'Codigo interno asignado por el usuario',
  `codigo_credito` int(9) unsigned zerofill NOT NULL COMMENT 'Codigo del credito del banco',
  `numero_cuota` int(3) unsigned zerofill NOT NULL COMMENT 'numero de la cuota credito',
  `fecha_cuota` date NOT NULL COMMENT 'Fecha pago de cuota',
  `interes` decimal(15,2) unsigned NOT NULL COMMENT 'interes del credito',
  `interes_pagado` decimal(15,2) unsigned NOT NULL COMMENT 'interes pagado del credito',
  `abono_capital` decimal(15,2) unsigned NOT NULL COMMENT 'abono capital del credito',
  `abono_capital_pagado` decimal(15,2) unsigned NOT NULL COMMENT 'abono capital pagado del credito',
  `saldo_capital` decimal(15,2) unsigned NOT NULL COMMENT 'saldo de capital del credito',
  `saldo_capital_pagado` decimal(15,2) unsigned NOT NULL COMMENT 'saldo de capital pagado del credito',
  `observaciones` varchar(255) collate latin1_spanish_ci default NULL COMMENT 'Observacion general del credito',
  `estado_cuota` enum('0','1','2') collate latin1_spanish_ci NOT NULL default '0' COMMENT '0->Pagado 1->Por pagar 2->Abonada',
  PRIMARY KEY  (`codigo`,`codigo_credito`),
  UNIQUE KEY `codigo` (`codigo`,`codigo_credito`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1 ;

-- 
-- Volcar la base de datos para la tabla `job_cuotas_creditos_bancos`
-- 


-- phpMyAdmin SQL Dump
-- version 2.9.2
-- http://www.phpmyadmin.net
-- 
-- Servidor: localhost
-- Tiempo de generación: 04-06-2020 a las 21:41:39
-- Versión del servidor: 5.0.27
-- Versión de PHP: 5.2.1
-- 
-- Base de datos: `jobdaily`
-- 
DROP DATABASE `jobdaily`;
CREATE DATABASE `jobdaily` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `jobdaily`;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_abonos_items_movimientos_contables`
-- 

DROP TABLE IF EXISTS `job_abonos_items_movimientos_contables`;
CREATE TABLE `job_abonos_items_movimientos_contables` (
  `codigo_sucursal` mediumint(5) unsigned zerofill NOT NULL COMMENT 'Codigo de la sucursal donse se genera el movimiento',
  `documento_identidad_tercero` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Número del documento de identidad',
  `codigo_tipo_comprobante` smallint(2) unsigned zerofill NOT NULL COMMENT 'Codigo del tipo de comprobante',
  `numero_comprobante` varchar(20) collate latin1_spanish_ci NOT NULL COMMENT 'Número de comprobante contable',
  `codigo_tipo_documento` smallint(3) unsigned zerofill NOT NULL COMMENT 'Id tabla consecutivo de documentos',
  `consecutivo_documento` int(8) unsigned zerofill NOT NULL COMMENT 'Consecutivo del documento',
  `fecha_contabilizacion` date NOT NULL COMMENT 'Fecha en que se contabiliza la transaccion',
  `consecutivo_item` int(9) unsigned zerofill NOT NULL COMMENT 'Consecutivo interno para la base de datos',
  `codigo_sucursal_saldo` mediumint(5) unsigned zerofill NOT NULL COMMENT 'Codigo de la sucursal donse se genera el movimiento',
  `documento_identidad_tercero_saldo` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Número del documento de identidad',
  `codigo_tipo_comprobante_saldo` smallint(2) unsigned zerofill NOT NULL COMMENT 'Codigo del tipo de comprobante',
  `numero_comprobante_saldo` varchar(20) collate latin1_spanish_ci NOT NULL COMMENT 'Número de comprobante contable',
  `codigo_tipo_documento_saldo` smallint(3) unsigned zerofill NOT NULL COMMENT 'Id tabla consecutivo de documentos',
  `consecutivo_documento_saldo` int(8) unsigned zerofill NOT NULL COMMENT 'Consecutivo del documento',
  `fecha_contabilizacion_saldo` date NOT NULL COMMENT 'Fecha en que se contabiliza la transaccion',
  `consecutivo_saldo` int(9) unsigned zerofill NOT NULL COMMENT 'Consecutivo interno para la base de datos',
  `fecha_vencimiento_saldo` date NOT NULL COMMENT 'Fecha en la que se produce el vencimiento de la cuota',
  `consecutivo` int(9) unsigned zerofill NOT NULL COMMENT 'Consecutivo de abonos por saldo',
  `fecha_pago_abono` date NOT NULL COMMENT 'Fecha en la que se produce el pago de la cuota',
  `valor` int(9) unsigned NOT NULL default '0' COMMENT 'Valor de la cuota',
  PRIMARY KEY  (`codigo_sucursal_saldo`,`documento_identidad_tercero_saldo`,`codigo_tipo_comprobante_saldo`,`numero_comprobante_saldo`,`codigo_tipo_documento_saldo`,`consecutivo_documento_saldo`,`fecha_contabilizacion_saldo`,`consecutivo_saldo`,`fecha_vencimiento_saldo`,`consecutivo`),
  KEY `abonos_items_movimientos_contables_item` (`codigo_sucursal`,`documento_identidad_tercero`,`codigo_tipo_comprobante`,`numero_comprobante`,`codigo_tipo_documento`,`consecutivo_documento`,`fecha_contabilizacion`,`consecutivo_item`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_abonos_items_movimientos_contables`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_actividades_economicas`
-- 

DROP TABLE IF EXISTS `job_actividades_economicas`;
CREATE TABLE `job_actividades_economicas` (
  `codigo_iso` varchar(2) collate latin1_spanish_ci NOT NULL COMMENT 'Llave principal de la tabla paises',
  `codigo_dane_departamento` varchar(2) collate latin1_spanish_ci NOT NULL COMMENT 'Código DANE',
  `codigo_dane_municipio` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'Código DANE',
  `codigo_dian` smallint(4) unsigned zerofill NOT NULL COMMENT 'id de la tabla actividades economicas DIAN',
  `codigo_actividad_municipio` int(5) unsigned zerofill NOT NULL default '00000' COMMENT 'Codigo de la actividad economica del municipio',
  `codigo_interno` smallint(4) unsigned zerofill default NULL COMMENT 'Código para uso interno de la empresa',
  `descripcion` varchar(255) collate latin1_spanish_ci NOT NULL default '' COMMENT 'Detalle que describe de la tasa',
  PRIMARY KEY  (`codigo_iso`,`codigo_dane_departamento`,`codigo_dane_municipio`,`codigo_dian`,`codigo_actividad_municipio`),
  KEY `actividad_economica_codigo_dian` (`codigo_dian`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_actividades_economicas`
-- 

INSERT INTO `job_actividades_economicas` (`codigo_iso`, `codigo_dane_departamento`, `codigo_dane_municipio`, `codigo_dian`, `codigo_actividad_municipio`, `codigo_interno`, `descripcion`) VALUES 
('', '', '', 0000, 00000, 0000, '');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_actividades_economicas_dian`
-- 

DROP TABLE IF EXISTS `job_actividades_economicas_dian`;
CREATE TABLE `job_actividades_economicas_dian` (
  `codigo_dian` smallint(4) unsigned zerofill NOT NULL COMMENT 'Codigo DIAN',
  `descripcion` varchar(255) collate latin1_spanish_ci NOT NULL COMMENT 'Detalle que describe el la actividad economica de la dian',
  PRIMARY KEY  (`codigo_dian`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_actividades_economicas_dian`
-- 

INSERT INTO `job_actividades_economicas_dian` (`codigo_dian`, `descripcion`) VALUES 
(0000, '');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_aficiones`
-- 

DROP TABLE IF EXISTS `job_aficiones`;
CREATE TABLE `job_aficiones` (
  `codigo` smallint(4) unsigned zerofill NOT NULL COMMENT 'Código interno que identifica la aficción',
  `descripcion` varchar(255) collate latin1_spanish_ci NOT NULL COMMENT 'Detalle que describe la aficción ',
  PRIMARY KEY  (`codigo`),
  UNIQUE KEY `descripcion` (`descripcion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_aficiones`
-- 

INSERT INTO `job_aficiones` (`codigo`, `descripcion`) VALUES 
(0000, '');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_aficiones_aspirante`
-- 

DROP TABLE IF EXISTS `job_aficiones_aspirante`;
CREATE TABLE `job_aficiones_aspirante` (
  `documento_identidad_aspirante` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo que identifica el tercero',
  `codigo_aficion` smallint(4) unsigned zerofill NOT NULL COMMENT 'CÃ³digo interno que identifica la aficciÃ³n',
  PRIMARY KEY  (`documento_identidad_aspirante`,`codigo_aficion`),
  KEY `aficiones_aspirante_aficiones` (`codigo_aficion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_aficiones_aspirante`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_anexos_contables`
-- 

DROP TABLE IF EXISTS `job_anexos_contables`;
CREATE TABLE `job_anexos_contables` (
  `codigo` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'Código del anexo que permite dividir las cuentas',
  `descripcion` varchar(255) collate latin1_spanish_ci NOT NULL COMMENT 'Detalle que describe el anexo contable',
  PRIMARY KEY  (`codigo`),
  UNIQUE KEY `codigo` (`codigo`),
  UNIQUE KEY `descripcion` (`descripcion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_anexos_contables`
-- 

INSERT INTO `job_anexos_contables` (`codigo`, `descripcion`) VALUES 
('', '');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_archivos`
-- 

DROP TABLE IF EXISTS `job_archivos`;
CREATE TABLE `job_archivos` (
  `codigo_sucursal` mediumint(5) unsigned zerofill NOT NULL COMMENT 'CÃ³digo interno de la sucursal a la cual pertenece',
  `consecutivo` int(9) unsigned zerofill NOT NULL COMMENT 'Consecutivo interno para la base de datos',
  `nombre` char(20) collate latin1_spanish_ci NOT NULL COMMENT 'Nombre completo del archivo',
  `descripcion` varchar(255) collate latin1_spanish_ci default NULL COMMENT 'Descripcion del archivo, opcional',
  PRIMARY KEY  (`codigo_sucursal`,`consecutivo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_archivos`
-- 

INSERT INTO `job_archivos` (`codigo_sucursal`, `consecutivo`, `nombre`, `descripcion`) VALUES 
(00000, 000000000, '', '');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_articulos`
-- 

DROP TABLE IF EXISTS `job_articulos`;
CREATE TABLE `job_articulos` (
  `codigo` varchar(20) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo del articulo asignado por la empresa',
  `codigo_proveedor` varchar(20) collate latin1_spanish_ci NOT NULL COMMENT 'Referencia o codigo del proveedor o codigo asignado por la empresa',
  `codigo_barras` bigint(13) unsigned zerofill default NULL COMMENT 'Referencia o codigo del proveedor o codigo asignado por la empresa',
  `descripcion` varchar(255) collate latin1_spanish_ci NOT NULL default '' COMMENT 'Descripcion del arti­culo',
  `tipo_articulo` enum('1','2','3','4') collate latin1_spanish_ci NOT NULL default '1' COMMENT '1->Producto terminado, 2->Obsequio(solo se maneja en el kardex), 3->Activo fijo, 4->Materia prima',
  `ficha_tecnica` text collate latin1_spanish_ci NOT NULL COMMENT 'Caracteristicas tecnicas de un articulo',
  `alto` int(8) unsigned default NULL COMMENT 'Altura del producto en milimetros',
  `ancho` int(8) unsigned default NULL COMMENT 'Ancho del producto en milimetros',
  `profundidad` int(8) unsigned default NULL COMMENT 'Profundidad del producto en milimetros',
  `peso` int(8) unsigned default NULL COMMENT 'Peso del producto en gramos',
  `garantia` varchar(255) collate latin1_spanish_ci default NULL COMMENT 'Detalle que describe la garantia de un producto',
  `garantia_partes` varchar(255) collate latin1_spanish_ci default NULL COMMENT 'Detalle que describe la garantia de las partes mas importantes',
  `codigo_impuesto_compra` smallint(3) unsigned zerofill NOT NULL default '000' COMMENT 'llave foranea a la tabla tasas',
  `codigo_impuesto_venta` smallint(3) unsigned zerofill NOT NULL default '000' COMMENT 'llave foranea a la tabla tasas',
  `codigo_marca` smallint(4) unsigned zerofill default '0000' COMMENT 'llave foranea a la tabla marcas',
  `codigo_estructura_grupo` smallint(4) unsigned zerofill NOT NULL default '0000' COMMENT 'llave foranea a la tabla estructura de grupos',
  `manejo_inventario` enum('1','2') collate latin1_spanish_ci NOT NULL default '1' COMMENT 'Manejo inventario: 1->Inventario valorizado 2->Solo maneja Kardex',
  `detalle_kardex` enum('1','2') collate latin1_spanish_ci NOT NULL COMMENT '1->Lleva kardex por codigo interno, 2->Lleva kardex por serie',
  `codigo_unidad_venta` int(6) unsigned zerofill NOT NULL default '000000' COMMENT 'Llave foranea a la tabla unidades',
  `codigo_unidad_compra` int(6) unsigned zerofill NOT NULL default '000000' COMMENT 'Llave foranea a la tabla unidades',
  `codigo_unidad_presentacion` int(6) unsigned zerofill NOT NULL default '000000' COMMENT 'Llave foranea a la tabla unidades',
  `codigo_iso` varchar(2) collate latin1_spanish_ci NOT NULL COMMENT 'Llave foranea a la tabla paises',
  `activo` enum('0','1') collate latin1_spanish_ci NOT NULL default '1' COMMENT '0->El codigo esta inactivo, 1->El codigo esta activo',
  `imprime_listas` enum('1','2','3') collate latin1_spanish_ci NOT NULL default '1' COMMENT '1->Siempre imprime, 2->Solo sí hay existencia, 3->Nunca imprime',
  `fecha_creacion` date NOT NULL COMMENT 'Fecha en la cual se crea el articulo',
  PRIMARY KEY  (`codigo`,`codigo_proveedor`),
  KEY `articulo_estructura` (`codigo_estructura_grupo`),
  KEY `articulo_marca` (`codigo_marca`),
  KEY `articulo_tasa_compra` (`codigo_impuesto_compra`),
  KEY `articulo_tasa_venta` (`codigo_impuesto_venta`),
  KEY `articulo_unidad_venta` (`codigo_unidad_venta`),
  KEY `articulo_unidad_compra` (`codigo_unidad_compra`),
  KEY `articulo_unidad_presentacion` (`codigo_unidad_presentacion`),
  KEY `articulo_pais` (`codigo_iso`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_articulos`
-- 

INSERT INTO `job_articulos` (`codigo`, `codigo_proveedor`, `codigo_barras`, `descripcion`, `tipo_articulo`, `ficha_tecnica`, `alto`, `ancho`, `profundidad`, `peso`, `garantia`, `garantia_partes`, `codigo_impuesto_compra`, `codigo_impuesto_venta`, `codigo_marca`, `codigo_estructura_grupo`, `manejo_inventario`, `detalle_kardex`, `codigo_unidad_venta`, `codigo_unidad_compra`, `codigo_unidad_presentacion`, `codigo_iso`, `activo`, `imprime_listas`, `fecha_creacion`) VALUES 
('', '', NULL, '', '1', '', 0, 0, 0, 0, '', '', 000, 000, 0000, 0000, '1', '1', 000000, 000000, 000000, '0', '0', '3', '0000-00-00');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_asignacion_turnos`
-- 

DROP TABLE IF EXISTS `job_asignacion_turnos`;
CREATE TABLE `job_asignacion_turnos` (
  `codigo_empresa` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa donde va a laborar',
  `documento_identidad_empleado` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Documento de identidad empleado en la sucursales contrato empleados',
  `fecha_ingreso` date NOT NULL COMMENT 'Fecha en la que ingreso el empleado a la empresa',
  `codigo_sucursal` mediumint(5) unsigned zerofill NOT NULL COMMENT 'Id de la tabla de sucursales',
  `fecha_ingreso_sucursal` date NOT NULL COMMENT 'Fecha en la que inicia labores en la sucursal el empleado',
  `consecutivo` int(8) unsigned zerofill NOT NULL COMMENT 'Consecutivo interno para la base de datos',
  `codigo_turno` smallint(4) unsigned zerofill NOT NULL COMMENT 'Id de la tabla de turnos laborales',
  `fecha_inicial` date NOT NULL COMMENT 'Fecha en que inicia el turno',
  `fecha_final` date NOT NULL COMMENT 'Fecha en que finaliza el turno',
  `dominicales` enum('0','1') collate latin1_spanish_ci default '0' COMMENT 'Trabaja domingos 0->No 1->Si',
  `festivos` enum('0','1') collate latin1_spanish_ci default '0' COMMENT 'Trabaja festivos 0->No 1->Si',
  `codigo_usuario_registra` smallint(4) unsigned zerofill NOT NULL COMMENT 'Codigo del Usuario que genera el registro',
  `codigo_usuario_modifica` smallint(4) unsigned zerofill default NULL COMMENT 'Codigo del ultimo Usuario que modifica el registro',
  PRIMARY KEY  (`codigo_empresa`,`documento_identidad_empleado`,`fecha_ingreso`,`codigo_sucursal`,`fecha_ingreso_sucursal`,`consecutivo`),
  KEY `turno_laboral` (`codigo_turno`),
  KEY `asignacion_turnos_usuarios_registra` (`codigo_usuario_registra`),
  KEY `asignacion_turnos_usuarios_modifica` (`codigo_usuario_modifica`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_asignacion_turnos`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_aspirantes`
-- 

DROP TABLE IF EXISTS `job_aspirantes`;
CREATE TABLE `job_aspirantes` (
  `documento_identidad` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo que identifica el tercero',
  `tipo_sangre` char(5) collate latin1_spanish_ci default NULL COMMENT 'Tipo de sangre del aspirante ejemplo: B+',
  `codigo_cargo` smallint(3) unsigned zerofill NOT NULL COMMENT 'Consecutivo interno para la base de datos del cargo que desempeÃ±a',
  `fecha_ingreso` date NOT NULL COMMENT 'Fecha de inicio de labores en la empresa',
  `fecha_inicio_vivienda` date NOT NULL COMMENT 'Fecha que se mudo a la casa',
  `derecho_sobre_vivienda` enum('1','2','3','4') collate latin1_spanish_ci NOT NULL default '1' COMMENT '1->arrendamiento 2->propiedad 3->familiar 4->comodato',
  `relacion_laboral` enum('1','2','3','4') collate latin1_spanish_ci NOT NULL default '1' COMMENT '1->Aspirante 2->Directamnete 3->Contrato 4->Prestacion de servicos',
  `canon_arrendo` double(11,2) default NULL COMMENT 'Valor del canon de arrendamiento',
  `nombre_arrendatario` varchar(255) collate latin1_spanish_ci NOT NULL COMMENT 'Nombre del arrendador de la vivienda',
  `codigo_iso_arrendatario` varchar(2) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo ISO en la tabla de municipios',
  `codigo_dane_departamento_arrendatario` varchar(2) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo dane departamento en la tabla de municipios',
  `codigo_dane_municipio_arrendatario` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo dane municipio en la tabla de municipios',
  `telefono_arrendatario` varchar(15) collate latin1_spanish_ci default NULL COMMENT 'Numero de telefono de la empresa',
  `codigo_iso_mayor_estadia` varchar(2) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo ISO en la tabla de municipios',
  `codigo_dane_departamento_mayor_estadia` varchar(2) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo dane departamento en la tabla de municipios',
  `codigo_dane_municipio_mayor_estadia` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo dane municipio en la tabla de municipios',
  `codigo_dane_profesion` smallint(4) unsigned zerofill NOT NULL COMMENT 'Codigo interno que identifica la profesion del aspirante',
  `aspiracion_salarial` double(11,2) default NULL COMMENT 'Aspiracion salarial de la persona',
  `pensionado` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT '0->No 1->Si',
  `ingreso_pension` double(11,2) default NULL COMMENT 'Valor del ingeso por pension',
  `experiencia_laboral` varchar(20) collate latin1_spanish_ci default NULL COMMENT 'Experiencia laboral que posee la persona',
  `recomendacion_interna` varchar(100) collate latin1_spanish_ci default NULL COMMENT 'Nombre de la persona que lo recomineda y trabaja en la empresa',
  `estatura` int(3) default NULL COMMENT 'estatura en centimetros de la persona',
  `peso` int(3) default NULL COMMENT 'peso en kilogramos de la persona',
  `talla_camisa` varchar(5) collate latin1_spanish_ci default NULL COMMENT 'talla de la camisa',
  `anteojos` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT '0->No 1->Si',
  `talla_pantalon` varchar(5) collate latin1_spanish_ci default NULL COMMENT 'talla del pantalon',
  `talla_calzado` varchar(5) collate latin1_spanish_ci default NULL COMMENT 'talla del calzado',
  `digitador` varchar(255) collate latin1_spanish_ci default NULL COMMENT 'Descripcion de algunos programa que conoce',
  `programacion` varchar(255) collate latin1_spanish_ci default NULL COMMENT 'Descripcion de lenguajes de programacion que conoce',
  `hojas_calculo` enum('1','2','3','4') collate latin1_spanish_ci NOT NULL default '1' COMMENT '1->No sabe 2->Regular 3->Bien 4->Muy bien',
  `procesadores_texto` enum('1','2','3','4') collate latin1_spanish_ci NOT NULL default '1' COMMENT '1->No sabe 2->Regular 3->Bien 4->Muy bien',
  `diseno_diapositivas` enum('1','2','3','4') collate latin1_spanish_ci NOT NULL default '1' COMMENT '1->No sabe 2->Regular 3->Bien 4->Muy bien',
  `codigo_iso_nacimiento` varchar(2) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo ISO en la tabla de municipios',
  `codigo_dane_departamento_nacimiento` varchar(2) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo dane departamento en la tabla de municipios',
  `codigo_dane_municipio_nacimiento` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo dane municipio en la tabla de municipios',
  `estado_civil` enum('1','2','3','4','5') collate latin1_spanish_ci NOT NULL default '1' COMMENT '1->soltero(a) 2->casado(a) 3->union libre 4->divorciado(a) 5->viudo(a)',
  `clase_libreta_militar` enum('1','2','3') collate latin1_spanish_ci NOT NULL default '1' COMMENT '1->No tiene 2->Primera 3->Segunda',
  `libreta_militar` int(15) default NULL COMMENT 'Numero de la libreta militar',
  `distrito_militar` int(3) default NULL COMMENT 'Numero del distrito militar',
  `permiso_conducir` int(15) default NULL COMMENT 'Numero de la licencia de conduccion',
  `categoria_permiso_conducir` enum('1','2','3','4','5','6','7') collate latin1_spanish_ci NOT NULL default '1' COMMENT '1->No tiene 2->primera- 3->segunda 4->tercera 5->cuarta 6->quinta 7->sexta',
  `codigo_entidad_salud` int(8) unsigned zerofill NOT NULL COMMENT 'Consecutivo interno para la base de datos de entidades parafiscales',
  `codigo_entidad_pension` int(8) unsigned zerofill NOT NULL COMMENT 'Consecutivo interno para la base de datos de entidades parafiscales',
  `codigo_entidad_cesantias` int(8) unsigned zerofill NOT NULL COMMENT 'Consecutivo interno para la base de datos de entidades parafiscales',
  PRIMARY KEY  (`documento_identidad`),
  KEY `aspirantes_municipios` (`codigo_iso_nacimiento`,`codigo_dane_departamento_nacimiento`,`codigo_dane_municipio_nacimiento`),
  KEY `aspirantes_cargos` (`codigo_cargo`),
  KEY `aspirantes_municipio_arrendatario` (`codigo_iso_arrendatario`,`codigo_dane_departamento_arrendatario`,`codigo_dane_municipio_arrendatario`),
  KEY `aspirantes_municipio_mayor_estadia` (`codigo_iso_mayor_estadia`,`codigo_dane_departamento_mayor_estadia`,`codigo_dane_municipio_mayor_estadia`),
  KEY `aspirantes_profesion` (`codigo_dane_profesion`),
  KEY `aspirantes_entidad_cesantias` (`codigo_entidad_cesantias`),
  KEY `aspirantes_entidad_pension` (`codigo_entidad_pension`),
  KEY `aspirantes_entidad_salud` (`codigo_entidad_salud`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_aspirantes`
-- 

INSERT INTO `job_aspirantes` (`documento_identidad`, `tipo_sangre`, `codigo_cargo`, `fecha_ingreso`, `fecha_inicio_vivienda`, `derecho_sobre_vivienda`, `relacion_laboral`, `canon_arrendo`, `nombre_arrendatario`, `codigo_iso_arrendatario`, `codigo_dane_departamento_arrendatario`, `codigo_dane_municipio_arrendatario`, `telefono_arrendatario`, `codigo_iso_mayor_estadia`, `codigo_dane_departamento_mayor_estadia`, `codigo_dane_municipio_mayor_estadia`, `codigo_dane_profesion`, `aspiracion_salarial`, `pensionado`, `ingreso_pension`, `experiencia_laboral`, `recomendacion_interna`, `estatura`, `peso`, `talla_camisa`, `anteojos`, `talla_pantalon`, `talla_calzado`, `digitador`, `programacion`, `hojas_calculo`, `procesadores_texto`, `diseno_diapositivas`, `codigo_iso_nacimiento`, `codigo_dane_departamento_nacimiento`, `codigo_dane_municipio_nacimiento`, `estado_civil`, `clase_libreta_militar`, `libreta_militar`, `distrito_militar`, `permiso_conducir`, `categoria_permiso_conducir`, `codigo_entidad_salud`, `codigo_entidad_pension`, `codigo_entidad_cesantias`) VALUES 
('0', '', 000, '0000-00-00', '0000-00-00', '1', '1', 0.00, '', '', '', '', '', '', '', '', 0000, 0.00, '0', 0.00, '', '', 0, 0, '0', '0', '', '', '', '', '1', '1', '1', '', '', '', '1', '1', 0, 0, 0, '1', 00000000, 00000000, 00000000);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_auxiliares_contables`
-- 

DROP TABLE IF EXISTS `job_auxiliares_contables`;
CREATE TABLE `job_auxiliares_contables` (
  `codigo_empresa` smallint(3) unsigned zerofill NOT NULL COMMENT 'Llave foranes de la tabla empresas',
  `codigo_anexo_contable` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'Código del anexo que permite dividir las cuentas',
  `codigo` int(8) unsigned zerofill NOT NULL COMMENT 'Código donde se acumulara la información',
  `descripcion` varchar(255) collate latin1_spanish_ci NOT NULL COMMENT 'Detalle que describe el anexo contable',
  PRIMARY KEY  (`codigo_empresa`,`codigo_anexo_contable`,`codigo`),
  KEY `auxiliar_anexo` (`codigo_anexo_contable`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_auxiliares_contables`
-- 

INSERT INTO `job_auxiliares_contables` (`codigo_empresa`, `codigo_anexo_contable`, `codigo`, `descripcion`) VALUES 
(000, '', 00000000, '');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_auxilio_transporte`
-- 

DROP TABLE IF EXISTS `job_auxilio_transporte`;
CREATE TABLE `job_auxilio_transporte` (
  `codigo` smallint(3) unsigned zerofill NOT NULL COMMENT 'CÃ³digo interno que identifica el auxilio de transporte',
  `fecha` date NOT NULL COMMENT 'Fecha a partir de la cual empieza a regir el valor del auxilio de transporte',
  `valor` decimal(11,2) NOT NULL COMMENT 'Valor del auxilio de transporte de acuerdo a la fecha',
  PRIMARY KEY  (`codigo`),
  UNIQUE KEY `fecha` (`fecha`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_auxilio_transporte`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_bancos`
-- 

DROP TABLE IF EXISTS `job_bancos`;
CREATE TABLE `job_bancos` (
  `codigo` smallint(2) unsigned zerofill NOT NULL COMMENT 'Código interno asignado al banco',
  `documento_identidad_tercero` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Llave de la tabla terceros',
  `descripcion` varchar(30) collate latin1_spanish_ci NOT NULL COMMENT 'Nombre que describe el banco',
  PRIMARY KEY  (`codigo`),
  KEY `bancos_tercero` (`documento_identidad_tercero`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_bancos`
-- 

INSERT INTO `job_bancos` (`codigo`, `documento_identidad_tercero`, `descripcion`) VALUES 
(00, '0', '');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_bitacora`
-- 

DROP TABLE IF EXISTS `job_bitacora`;
CREATE TABLE `job_bitacora` (
  `codigo_sucursal_conexion` mediumint(5) unsigned zerofill NOT NULL COMMENT 'Codigo de la sucursal',
  `codigo_usuario_conexion` smallint(4) unsigned zerofill NOT NULL COMMENT 'Consecutivo interno para la base de datos del usuario que realiza la conexiÃ³n',
  `fecha_conexion` datetime NOT NULL COMMENT 'Fecha y hora de la conexiÃ³n',
  `fecha_operacion` datetime NOT NULL COMMENT 'Fecha y hora de la operaciÃ³n',
  `consecutivo` int(8) NOT NULL COMMENT 'Consecutivo para registros que se ejecuten en una misma hora',
  `id_componente_padre` varchar(8) collate latin1_spanish_ci NOT NULL,
  `id_registro` varchar(255) collate latin1_spanish_ci NOT NULL,
  `componente` text collate latin1_spanish_ci NOT NULL COMMENT 'Nombre del componente requerido por el usuario',
  `consulta` text collate latin1_spanish_ci NOT NULL COMMENT 'Detalles de la sintÃ¡xis SQL de la(s) consulta(s) generada(s) por el componente',
  `mensaje` text collate latin1_spanish_ci COMMENT 'Mensaje de error (si existe) devuelto por el motor de bases de datos',
  PRIMARY KEY  (`codigo_sucursal_conexion`,`codigo_usuario_conexion`,`fecha_conexion`,`fecha_operacion`,`consecutivo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_bitacora`
-- 

INSERT INTO `job_bitacora` (`codigo_sucursal_conexion`, `codigo_usuario_conexion`, `fecha_conexion`, `fecha_operacion`, `consecutivo`, `id_componente_padre`, `id_registro`, `componente`, `consulta`, `mensaje`) VALUES 
(00000, 0000, '2020-06-04 13:36:14', '2020-06-04 13:36:14', 1, '', '', 'Iniciar sesión', 'INSERT INTO job_conexiones (codigo_sucursal,codigo_usuario,fecha,ip) VALUES (''null'',''0000'',''2020-06-04 13:36:14'',''127.0.0.1'')', NULL),
(00000, 0000, '2020-06-04 14:23:31', '2020-06-04 14:23:31', 1, '', '', 'Iniciar sesión', 'INSERT INTO job_conexiones (codigo_sucursal,codigo_usuario,fecha,ip) VALUES (''null'',''0000'',''2020-06-04 14:23:31'',''127.0.0.1'')', NULL),
(00000, 0000, '2020-06-04 16:22:11', '2020-06-04 16:22:11', 1, '', '', 'Iniciar sesión', 'INSERT INTO job_conexiones (codigo_sucursal,codigo_usuario,fecha,ip) VALUES (''null'',''0000'',''2020-06-04 16:22:11'',''127.0.0.1'')', NULL),
(00000, 0000, '2020-06-04 17:10:56', '2020-06-04 17:10:56', 1, '', '', 'Iniciar sesión', 'INSERT INTO job_conexiones (codigo_sucursal,codigo_usuario,fecha,ip) VALUES (''null'',''0000'',''2020-06-04 17:10:56'',''127.0.0.1'')', NULL),
(00000, 0000, '2020-06-04 20:04:53', '2020-06-04 20:04:53', 1, '', '', 'Iniciar sesión', 'INSERT INTO job_conexiones (codigo_sucursal,codigo_usuario,fecha,ip) VALUES (''null'',''0000'',''2020-06-04 20:04:53'',''127.0.0.1'')', NULL),
(00000, 0000, '2020-06-04 20:29:18', '2020-06-04 20:29:18', 1, '', '', 'Iniciar sesión', 'INSERT INTO job_conexiones (codigo_sucursal,codigo_usuario,fecha,ip) VALUES (''null'',''0000'',''2020-06-04 20:29:18'',''127.0.0.1'')', NULL);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_bodegas`
-- 

DROP TABLE IF EXISTS `job_bodegas`;
CREATE TABLE `job_bodegas` (
  `codigo` smallint(4) unsigned zerofill NOT NULL COMMENT 'CÃ³digo interno de la bodega',
  `codigo_sucursal` mediumint(5) unsigned zerofill NOT NULL COMMENT 'CÃ³digo interno de la sucursal',
  `nombre` varchar(60) collate latin1_spanish_ci NOT NULL COMMENT 'Nombre que identifica la bodega',
  `descripcion` varchar(60) collate latin1_spanish_ci NOT NULL COMMENT 'Nombre que describe la bodega',
  `codigo_tipo_bodega` smallint(3) unsigned zerofill NOT NULL COMMENT 'Localizacion donde se encuentra ubicado el articulo',
  `tipo_inventario` enum('1','2','3','4','5','6','7') collate latin1_spanish_ci NOT NULL default '1' COMMENT 'Tipo inventario de la bodega 1->Inventario,2->Obsequio,3->ConsignaciÃ³n,4->Prestamo a terceros,5->Servicio Tecnico,6->Prestamo Clientes,7->ConsignaciÃ³n Clientes',
  PRIMARY KEY  (`codigo`,`codigo_sucursal`),
  KEY `bodega_sucursal` (`codigo_sucursal`),
  KEY `tipo_bodega` (`codigo_tipo_bodega`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_bodegas`
-- 

INSERT INTO `job_bodegas` (`codigo`, `codigo_sucursal`, `nombre`, `descripcion`, `codigo_tipo_bodega`, `tipo_inventario`) VALUES 
(0000, 00000, '', '', 000, '1');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_movimientos_contables`
-- 

DROP TABLE IF EXISTS `job_buscador_movimientos_contables`;
CREATE TABLE `job_buscador_movimientos_contables` (
  `id` varbinary(79) default NULL,
  `TERCERO` varchar(329) default NULL,
  `TIPO_DOCUMENTO` varchar(255) default NULL,
  `CONSECUTIVO_DOCUMENTO` int(8) unsigned default NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Volcar la base de datos para la tabla `job_buscador_movimientos_contables`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_saldos_movimientos_contables`
-- 

DROP TABLE IF EXISTS `job_buscador_saldos_movimientos_contables`;
CREATE TABLE `job_buscador_saldos_movimientos_contables` (
  `id_tercero` varchar(12) default NULL,
  `id_cuenta` varchar(15) default NULL,
  `id_consecutivo` varbinary(38) default NULL,
  `consecutivo` int(8) unsigned default NULL,
  `id_documento` smallint(3) unsigned zerofill default NULL,
  `id_item_movimiento` varbinary(91) default NULL,
  `id_saldo` varbinary(102) default NULL,
  `saldo` int(9) unsigned default NULL,
  `fecha_vencimiento` date default NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Volcar la base de datos para la tabla `job_buscador_saldos_movimientos_contables`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_cancelacion_nomina_por_pagar_pension_empleado`
-- 

DROP TABLE IF EXISTS `job_cancelacion_nomina_por_pagar_pension_empleado`;
CREATE TABLE `job_cancelacion_nomina_por_pagar_pension_empleado` (
  `codigo_sucursal` mediumint(5) unsigned zerofill NOT NULL COMMENT 'Codigo del almacen donde va a laborar',
  `ano_generacion` int(4) unsigned zerofill NOT NULL COMMENT 'Codigo interno que identifica la planilla',
  `mes_generacion` int(2) unsigned zerofill NOT NULL COMMENT 'Codigo interno que identifica la planilla',
  `codigo_planilla` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo interno que identifica la planilla',
  `periodo_pago` enum('1','2','3','4','5','6','7','8','9') collate latin1_spanish_ci NOT NULL COMMENT '1->Mensual 2->Primera quincena 3->Segunda quincena 4->Primera semana 5->Segunda semana 6->Tercer semana 7->Cuarta semana 8->Quinta semana 9->Fecha unica',
  `documento_identidad_empleado` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Documento de identidad que identifica el empleado en terceras personas',
  `codigo_empresa` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo del almacen donde va a laborar',
  `fecha_ingreso_empresa` date NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado',
  `fecha_ingreso_sucursal` date NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado',
  `fecha_pago_planilla` date NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado',
  `valor_movimiento` decimal(11,2) NOT NULL COMMENT 'Valor del movimiento',
  `codigo_transaccion_contable` int(8) unsigned zerofill NOT NULL COMMENT 'Id de la tabla de transacciones contables',
  `codigo_contable` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo contable de la transaccion contable',
  `sentido` enum('D','C') collate latin1_spanish_ci NOT NULL COMMENT 'D->Debito C->Credito',
  `codigo_empresa_auxiliar` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa del auxiliar contable',
  `codigo_anexo_contable` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo del anexo que permite dividir las cuentas',
  `codigo_auxiliar_contable` int(8) unsigned zerofill NOT NULL COMMENT 'Codigo donde se acumulara la informacion',
  `fecha_registro` datetime NOT NULL COMMENT 'Fecha de generación del registro',
  `codigo_usuario_registra` smallint(4) unsigned zerofill NOT NULL COMMENT 'Codigo usuario que genera el registro',
  PRIMARY KEY  (`codigo_sucursal`,`ano_generacion`,`mes_generacion`,`codigo_planilla`,`periodo_pago`,`documento_identidad_empleado`),
  KEY `cancelacion_movimiento_pension_empleado` (`ano_generacion`,`mes_generacion`,`codigo_planilla`,`periodo_pago`,`fecha_pago_planilla`,`documento_identidad_empleado`,`codigo_sucursal`),
  KEY `cancelacion_pension_sucursal_contrato_empleado` (`codigo_empresa`,`documento_identidad_empleado`,`fecha_ingreso_empresa`,`codigo_sucursal`,`fecha_ingreso_sucursal`),
  KEY `cancelacion_pension_transaccion_contable` (`codigo_transaccion_contable`),
  KEY `cancelacion_pension_codigo_contable` (`codigo_contable`),
  KEY `cancelacion_pension_anexo` (`codigo_anexo_contable`),
  KEY `cancelacion_pension_auxiliar_contable` (`codigo_empresa_auxiliar`,`codigo_anexo_contable`,`codigo_auxiliar_contable`),
  KEY `cancelacion_pension_usuario` (`codigo_usuario_registra`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_cancelacion_nomina_por_pagar_pension_empleado`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_cancelacion_nomina_por_pagar_salud_empleado`
-- 

DROP TABLE IF EXISTS `job_cancelacion_nomina_por_pagar_salud_empleado`;
CREATE TABLE `job_cancelacion_nomina_por_pagar_salud_empleado` (
  `codigo_sucursal` mediumint(5) unsigned zerofill NOT NULL COMMENT 'Codigo del almacen donde va a laborar',
  `ano_generacion` int(4) unsigned zerofill NOT NULL COMMENT 'Codigo interno que identifica la planilla',
  `mes_generacion` int(2) unsigned zerofill NOT NULL COMMENT 'Codigo interno que identifica la planilla',
  `codigo_planilla` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo interno que identifica la planilla',
  `periodo_pago` enum('1','2','3','4','5','6','7','8','9') collate latin1_spanish_ci NOT NULL COMMENT '1->Mensual 2->Primera quincena 3->Segunda quincena 4->Primera semana 5->Segunda semana 6->Tercer semana 7->Cuarta semana 8->Quinta semana 9->Fecha unica',
  `documento_identidad_empleado` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Documento de identidad que identifica el empleado en terceras personas',
  `codigo_empresa` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo del almacen donde va a laborar',
  `fecha_ingreso_empresa` date NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado',
  `fecha_ingreso_sucursal` date NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado',
  `fecha_pago_planilla` date NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado',
  `valor_movimiento` decimal(11,2) NOT NULL COMMENT 'Valor del movimiento',
  `codigo_transaccion_contable` int(8) unsigned zerofill NOT NULL COMMENT 'Id de la tabla de transacciones contables',
  `codigo_contable` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo contable de la transaccion contable',
  `sentido` enum('D','C') collate latin1_spanish_ci NOT NULL COMMENT 'D->Debito C->Credito',
  `codigo_empresa_auxiliar` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa del auxiliar contable',
  `codigo_anexo_contable` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo del anexo que permite dividir las cuentas',
  `codigo_auxiliar_contable` int(8) unsigned zerofill NOT NULL COMMENT 'Codigo donde se acumulara la informacion',
  `fecha_generacion` datetime NOT NULL COMMENT 'Fecha de generación del registro',
  `codigo_usuario_registra` smallint(4) unsigned zerofill NOT NULL COMMENT 'Codigo usuario que genera el registro',
  PRIMARY KEY  (`codigo_sucursal`,`ano_generacion`,`mes_generacion`,`codigo_planilla`,`periodo_pago`,`documento_identidad_empleado`),
  KEY `cancelacion_movimiento_salud_empleado` (`ano_generacion`,`mes_generacion`,`codigo_planilla`,`periodo_pago`,`fecha_pago_planilla`,`documento_identidad_empleado`,`codigo_sucursal`),
  KEY `cancelacion_salud_sucursal_contrato_empleado` (`codigo_empresa`,`documento_identidad_empleado`,`fecha_ingreso_empresa`,`codigo_sucursal`,`fecha_ingreso_sucursal`),
  KEY `cancelacion_salud_transaccion_contable` (`codigo_transaccion_contable`),
  KEY `cancelacion_salud_codigo_contable` (`codigo_contable`),
  KEY `cancelacion_salud_anexo` (`codigo_anexo_contable`),
  KEY `cancelacion_salud_auxiliar_contable` (`codigo_empresa_auxiliar`,`codigo_anexo_contable`,`codigo_auxiliar_contable`),
  KEY `cancelacion_salud_usuario` (`codigo_usuario_registra`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_cancelacion_nomina_por_pagar_salud_empleado`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_cargo_contrato_empleados`
-- 

DROP TABLE IF EXISTS `job_cargo_contrato_empleados`;
CREATE TABLE `job_cargo_contrato_empleados` (
  `codigo_empresa` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa donde se vincula el empleado',
  `documento_identidad_empleado` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'CÃ³digo interno que identifica el empleado en terceras personas',
  `fecha_ingreso` date NOT NULL COMMENT 'Fecha de ingreso del empleado',
  `codigo_sucursal` mediumint(5) unsigned zerofill NOT NULL COMMENT 'Codigo del almacen donde va a laborar',
  `fecha_ingreso_sucursal` date NOT NULL COMMENT 'Fecha en la que inicia labores en la sucursal el empleado',
  `fecha_inicia_cargo` date NOT NULL COMMENT 'Fecha en la que inicia labores en el cargo asignado',
  `codigo_cargo` smallint(3) unsigned zerofill NOT NULL COMMENT 'CÃ³digo de que identifica el cargo',
  `fecha_termina` date NOT NULL COMMENT 'Fecha en la que termina labores con el cargo asignado',
  `documento_identidad_jefe_inmediato` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Documento identidad del jefe inmediato(Tabla terceros)',
  PRIMARY KEY  (`codigo_empresa`,`documento_identidad_empleado`,`fecha_ingreso`,`codigo_sucursal`,`fecha_ingreso_sucursal`,`fecha_inicia_cargo`),
  KEY `sucursal_contrato_codigo_cargo_empleado` (`codigo_cargo`),
  KEY `sucursal_contrato_documento_jefe_inmediato` (`documento_identidad_jefe_inmediato`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_cargo_contrato_empleados`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_cargos`
-- 

DROP TABLE IF EXISTS `job_cargos`;
CREATE TABLE `job_cargos` (
  `codigo` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo interno asignado por el usuario',
  `nombre` varchar(50) collate latin1_spanish_ci NOT NULL COMMENT 'Nombre del cargo',
  `interno` enum('0','1') collate latin1_spanish_ci NOT NULL default '1' COMMENT 'Cargo interno 0->No, 1->Si',
  PRIMARY KEY  (`codigo`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_cargos`
-- 

INSERT INTO `job_cargos` (`codigo`, `nombre`, `interno`) VALUES 
(000, '', '0');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_causaciones_prestaciones_sociales`
-- 

DROP TABLE IF EXISTS `job_causaciones_prestaciones_sociales`;
CREATE TABLE `job_causaciones_prestaciones_sociales` (
  `concepto` enum('1','2','3','4') collate latin1_spanish_ci NOT NULL COMMENT '1->prima 2->vacaciones  3->cesantias  4->intereses/cesantias ',
  `fecha_generacion` datetime NOT NULL COMMENT 'Fecha en la se genero el retiro',
  `fecha_liquidacion` date NOT NULL COMMENT 'Fecha en la hasta donde se va a liquidar',
  `fecha_contabilizacion` date NOT NULL COMMENT 'Fecha en la se generara la contabilizacion',
  `codigo_empresa` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo del almacen donde va a laborar',
  `documento_identidad_empleado` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Documento de identidad que identifica el empleado en terceras personas',
  `fecha_ingreso` date NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado',
  `codigo_sucursal` mediumint(5) unsigned zerofill NOT NULL COMMENT 'Codigo del almacen donde va a laborar',
  `fecha_ingreso_sucursal` date NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado',
  `codigo_sucursal_documento` mediumint(5) unsigned zerofill NOT NULL COMMENT 'Codigo del almacen donde va a laborar',
  `codigo_tipo_documento` smallint(3) unsigned zerofill NOT NULL COMMENT 'Código asignado por el usuario',
  `identidad_tercero_documento` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Documento de identidad que identifica el empleado en terceras personas',
  `fecha_generacion_consecutivo` date NOT NULL COMMENT 'Fecha de genracion del consecutico',
  `consecutivo_documento` int(9) unsigned zerofill NOT NULL COMMENT 'Consecutivo del documento',
  `codigo_tipo_comprobante` smallint(2) unsigned zerofill NOT NULL COMMENT 'tipo de  comprobante seleccionado por el usuario',
  `numero_comprobante` varchar(20) collate latin1_spanish_ci NOT NULL COMMENT 'Número de comprobante contable',
  `codigo_empresa_auxiliar` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa para la llave de auxiliares',
  `codigo_anexo_contable` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'Código del anexo que permite dividir las cuentas',
  `codigo_auxiliar_contable` int(8) unsigned zerofill NOT NULL COMMENT 'Código donde se acumulara la información',
  `fecha_inicio` date NOT NULL COMMENT 'Fecha en donde se empieza a calcular el valor a pagar',
  `fecha_final` date NOT NULL COMMENT 'Fecha en donde se empieza a calcular el valor a pagar',
  `dias_liquidados` smallint(3) NOT NULL COMMENT 'Dias laborados en el periodo',
  `salario_base` decimal(11,2) NOT NULL COMMENT 'Valor con el que se va a liquidar el movimiento',
  `periodo_pago` smallint(3) NOT NULL COMMENT 'Dias laborados en el periodo',
  `codigo_transaccion_contable` int(8) unsigned zerofill NOT NULL COMMENT 'codigo de la tabla de transacciones contables',
  `codigo_plan_contable` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo de la cuenta contable segun el PUC',
  `sentido` enum('D','C') collate latin1_spanish_ci NOT NULL COMMENT 'D->Débito C->Crédito',
  `valor_movimiento` decimal(11,2) NOT NULL COMMENT 'Valor diario del registro',
  `codigo_usuario_registra` smallint(4) unsigned zerofill NOT NULL COMMENT 'Codigo del Usuario que genera el registro',
  `codigo_usuario_modifica` smallint(4) unsigned zerofill NOT NULL COMMENT 'Codigo del ultimo Usuario que modifica el registro',
  PRIMARY KEY  (`codigo_empresa`,`documento_identidad_empleado`,`fecha_liquidacion`,`codigo_transaccion_contable`),
  KEY `causaciones_prestaciones_sociales_sucursal_contrato` (`codigo_empresa`,`documento_identidad_empleado`,`fecha_ingreso`,`codigo_sucursal`,`fecha_ingreso_sucursal`),
  KEY `causaciones_prestaciones_sociales_tipo_documento` (`codigo_tipo_documento`),
  KEY `causaciones_prestaciones_sociales_auxiliar_contable` (`codigo_empresa_auxiliar`,`codigo_anexo_contable`,`codigo_auxiliar_contable`),
  KEY `causaciones_prestaciones_sociales_consecutivo_documentos` (`codigo_sucursal_documento`,`codigo_tipo_documento`,`identidad_tercero_documento`,`fecha_generacion_consecutivo`,`consecutivo_documento`),
  KEY `causaciones_prestaciones_sociales_transaccion` (`codigo_transaccion_contable`),
  KEY `causaciones_prestaciones_sociales_registra` (`codigo_usuario_registra`),
  KEY `causaciones_prestaciones_sociales_modifica` (`codigo_usuario_modifica`),
  KEY `causaciones_prestaciones_sociales_tipo_comprobante` (`codigo_tipo_comprobante`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_causaciones_prestaciones_sociales`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_componentes`
-- 

DROP TABLE IF EXISTS `job_componentes`;
CREATE TABLE `job_componentes` (
  `id` varchar(8) collate latin1_spanish_ci NOT NULL COMMENT 'Identificador del componente',
  `padre` varchar(8) collate latin1_spanish_ci default NULL COMMENT 'Identificador del padre del componente: NULL = Componente principal',
  `id_modulo` char(32) collate latin1_spanish_ci NOT NULL COMMENT 'Identificador del modulo al cual pertenece',
  `requiere_item` enum('0','1') collate latin1_spanish_ci NOT NULL default '1' COMMENT 'Si el componente requiere item: 0->No, 1->Si',
  `global` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT 'Todos los usuarios lo pueden cargar sin verificar permisos: 0=No, 1=Si',
  `visible` enum('0','1') collate latin1_spanish_ci NOT NULL default '1' COMMENT 'El componente debe aparecer en el menÃº: 0=No, 1=Si',
  `orden` smallint(4) unsigned zerofill NOT NULL default '0000' COMMENT 'Orden en el que debe presentarse en el menÃº Ã³ en los listados',
  `tabla_principal` varchar(255) collate latin1_spanish_ci default NULL COMMENT 'Nombre de la tabla principal con la que se relaciona el componente',
  `carpeta` varchar(255) collate latin1_spanish_ci default NULL COMMENT 'Carpeta donde estÃ¡ almacenado el archivo',
  `archivo` varchar(255) collate latin1_spanish_ci default NULL COMMENT 'Archivo que se debe cargar al seleccionar el componente: NULL = No genera enlace o acciÃ³n',
  `tipo_enlace` enum('1','2') collate latin1_spanish_ci NOT NULL default '1' COMMENT '1->Muestra opciones de seleccion 2->Abre formulario directamente',
  PRIMARY KEY  (`id`),
  KEY `componente_modulo` (`id_modulo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_componentes`
-- 

INSERT INTO `job_componentes` (`id`, `padre`, `id_modulo`, `requiere_item`, `global`, `visible`, `orden`, `tabla_principal`, `carpeta`, `archivo`, `tipo_enlace`) VALUES 
('ADICACDI', 'GESTACDI', 'CONTABILIDAD', '0', '0', '0', 0010, 'actividades_economicas_dian', 'actividades_economicas_dian', 'adicionar', '1'),
('ADICAECO', 'GESTAECO', 'CONTABILIDAD', '0', '0', '0', 0010, 'actividades_economicas', 'actividades_economicas', 'adicionar', '1'),
('ADICAFIC', 'GESTAFIC', 'NOMINA', '0', '0', '0', 0025, 'aficiones', 'aficiones', 'adicionar', '1'),
('ADICANCO', 'GESTANCO', 'CONTABILIDAD', '0', '0', '0', 0005, 'anexos_contables', 'anexos_contables', 'adicionar', '1'),
('ADICARTI', 'GESTARTI', 'INVENTARIO', '0', '0', '0', 0010, 'articulos', 'articulos', 'adicionar', '1'),
('ADICASPI', 'GESTASPI', 'NOMINA', '0', '0', '0', 0010, NULL, 'aspirantes', 'adicionar', '1'),
('ADICASTU', 'GESTASTU', 'NOMINA', '0', '0', '0', 0010, NULL, 'asignacion_turnos', 'adicionar', '1'),
('ADICAUCO', 'GESTAUCO', 'CONTABILIDAD', '0', '0', '0', 0005, 'auxiliares_contables', 'auxiliares_contables', 'adicionar', '1'),
('ADICAXTP', 'GESTAXTP', 'NOMINA', '0', '0', '0', 0010, NULL, 'auxilio_transporte', 'adicionar', '1'),
('ADICBANC', 'GESTBANC', 'CONTABILIDAD', '0', '0', '0', 0010, 'bancos', 'bancos', 'adicionar', '1'),
('ADICBARR', 'GESTBARR', 'ADMINISTRACION', '0', '0', '0', 0010, NULL, 'barrios', 'adicionar', '1'),
('ADICBODE', 'GESTBODE', 'ADMINISTRACION', '0', '0', '0', 0020, NULL, 'bodegas', 'adicionar', '1'),
('ADICCAPR', 'GESTCAPR', 'PROVEEDORES', '0', '0', '0', 0010, 'cargos', 'cargos', 'adicionar', '1'),
('ADICCAPS', 'GESTCAPS', 'NOMINA', '0', '0', '0', 0005, 'causaciones_prestaciones_sociales', 'causaciones_prestaciones_sociales', 'adicionar', '1'),
('ADICCARG', 'GESTCARG', 'ADMINISTRACION', '0', '0', '0', 0020, NULL, 'cargos', 'adicionar', '1'),
('ADICCDCO', 'GESTCDCO', 'CONTABILIDAD', '0', '0', '0', 0010, 'conceptos_devolucion_compras', 'conceptos_devolucion_compras', 'adicionar', '1'),
('ADICCGPS', 'GESTCGPS', 'NOMINA', '0', '0', '0', 0010, NULL, 'gastos_prestaciones_sociales', 'adicionar', '1'),
('ADICCOCO', 'GESTCOCO', 'CONTABILIDAD', '1', '0', '0', 0010, NULL, 'conceptos_contabilizacion', 'adicionar', '1'),
('ADICCODI', 'GESTCODI', 'CONTABILIDAD', '0', '0', '0', 0005, 'conceptos_dian', 'conceptos_dian', 'adicionar', '1'),
('ADICCOPR', 'GESTCOPR', 'NOMINA', '0', '0', '0', 0010, NULL, 'conceptos_prestamos', 'adicionar', '1'),
('ADICCORR', 'GESTCORR', 'ADMINISTRACION', '0', '0', '0', 0010, NULL, 'corregimientos', 'adicionar', '1'),
('ADICCUBA', 'GESTCUBA', 'CONTABILIDAD', '0', '0', '0', 0010, 'cuentas_bancarias', 'cuentas_bancarias', 'adicionar', '1'),
('ADICDEEM', 'GESTDEEM', 'NOMINA', '0', '0', '0', 0010, 'departamentos_empresa', 'departamentos_empresa', 'adicionar', '1'),
('ADICDEPA', 'GESTDEPA', 'ADMINISTRACION', '0', '0', '0', 0010, NULL, 'departamentos', 'adicionar', '1'),
('ADICDEPO', 'GESTDEPO', 'NOMINA', '0', '0', '0', 0010, NULL, 'deportes', 'adicionar', '1'),
('ADICDOFE', 'GESTDOFE', 'NOMINA', '0', '0', '0', 0010, NULL, 'dominicales_festivos', 'adicionar', '1'),
('ADICEMPR', 'GESTEMPR', 'ADMINISTRACION', '0', '0', '0', 0020, NULL, 'empresas', 'adicionar', '1'),
('ADICENPF', 'GESTENPF', 'NOMINA', '0', '0', '0', 0010, 'entidades_parafiscales', 'entidades_parafiscales', 'adicionar', '1'),
('ADICESCO', 'GESTESCO', 'NOMINA', '0', '0', '0', 0010, NULL, 'escolaridad', 'adicionar', '1'),
('ADICESGR', 'GESTESGR', 'INVENTARIO', '0', '0', '0', 0025, 'estructura_grupos', 'estructura_grupos', 'adicionar', '1'),
('ADICESME', 'GESTESME', 'INVENTARIO', '0', '0', '0', 0005, 'estado_mercancia', 'estado_mercancia', 'adicionar', '1'),
('ADICEXRE', 'GESTEXRE', 'NOMINA', '0', '0', '0', 0010, NULL, 'extras_recargos', 'adicionar', '1'),
('ADICFEPL', 'GESTFEPL', 'NOMINA', '0', '0', '0', 0010, NULL, 'fechas_planillas', 'adicionar', '1'),
('ADICFODI', 'GESTFODI', 'CONTABILIDAD', '0', '0', '0', 0005, 'formatos_dian', 'formatos_dian', 'adicionar', '1'),
('ADICGRUP', 'GESTGRUP', 'INVENTARIO', '0', '0', '0', 0010, 'grupos', 'grupos', 'adicionar', '1'),
('ADICIDIO', 'GESTIDIO', 'NOMINA', '0', '0', '0', 0010, NULL, 'idiomas', 'adicionar', '1'),
('ADICINEM', 'GESTINEM', 'NOMINA', '0', '0', '0', 0010, NULL, 'empleados', 'adicionar', '1'),
('ADICLIEM', 'GESTLIEM', 'NOMINA', '0', '0', '0', 0005, 'liquidar_empleado', 'liquidar_empleado', 'adicionar', '1'),
('ADICMARC', 'GESTMARC', 'INVENTARIO', '0', '0', '0', 0010, 'marcas', 'marcas', 'adicionar', '1'),
('ADICMOCO', 'GESTMOCO', 'CONTABILIDAD', '1', '0', '0', 0025, NULL, 'movimientos_contables', 'adicionar', '1'),
('ADICMOIN', 'GESTMOIN', 'NOMINA', '0', '0', '0', 0010, NULL, 'motivos_incapacidad', 'adicionar', '1'),
('ADICMORE', 'GESTMORE', 'NOMINA', '0', '0', '0', 0010, NULL, 'motivos_retiro', 'adicionar', '1'),
('ADICMUNI', 'GESTMUNI', 'ADMINISTRACION', '0', '0', '0', 0010, NULL, 'municipios', 'adicionar', '1'),
('ADICNMIG', 'GESTNMIG', 'NOMINA', '0', '0', '0', 0010, NULL, 'nomina_migracion', 'adicionar', '1'),
('ADICNOLA', 'GESTNOLA', 'NOMINA', '0', '0', '0', 0010, NULL, 'motivos_tiempos_no_laborar', 'adicionar', '1'),
('ADICNOMA', 'GESTNOMA', 'NOMINA', '0', '0', '0', 0010, NULL, 'novedades_manuales', 'adicionar', '1'),
('ADICPAIS', 'GESTPAIS', 'ADMINISTRACION', '0', '0', '0', 0010, NULL, 'paises', 'adicionar', '1'),
('ADICPECO', 'GESTPECO', 'CONTABILIDAD', '1', '0', '0', 0005, NULL, 'periodos_contables', 'adicionar', '1'),
('ADICPERF', 'GESTPERF', 'ADMINISTRACION', '0', '0', '0', 0020, NULL, 'perfiles', 'adicionar', '1'),
('ADICPLAN', 'GESTPLAN', 'NOMINA', '0', '0', '0', 0010, NULL, 'planillas', 'adicionar', '1'),
('ADICPLCO', 'GESTPLCO', 'CONTABILIDAD', '1', '0', '0', 0010, NULL, 'plan_contable', 'adicionar', '1'),
('ADICPPPR', 'GESTPPPR', 'PROVEEDORES', '0', '0', '0', 0010, 'plazos_pago_proveedores', 'plazos_pago_proveedores', 'adicionar', '1'),
('ADICPRIV', 'GESTPRIV', 'ADMINISTRACION', '1', '0', '0', 0020, NULL, 'privilegios', 'adicionar', '1'),
('ADICPRMA', 'GESTPRMA', 'PROVEEDORES', '0', '0', '0', 0010, 'proveedores_marcas', 'proveedores_marcas', 'adicionar', '1'),
('ADICPROF', 'GESTPROF', 'ADMINISTRACION', '1', '0', '0', 0020, NULL, 'profesiones_oficios', 'adicionar', '1'),
('ADICPROV', 'GESTPROV', 'PROVEEDORES', '0', '0', '0', 0010, 'proveedores', 'proveedores', 'adicionar', '1'),
('ADICPTEM', 'GESTPTEM', 'NOMINA', '0', '0', '0', 0010, 'control_prestamos_empleados', 'prestamos_empleados', 'adicionar', '1'),
('ADICPTTE', 'GESTPTTE', 'NOMINA', '0', '0', '0', 0010, 'control_prestamos_terceros', 'prestamos_terceros', 'adicionar', '1'),
('ADICRECE', 'SUBMPRSO', 'NOMINA', '0', '0', '0', 0005, 'movimiento_retiro_cesantias', 'retiro_cesantias', 'adicionar', '1'),
('ADICREDI', 'GESTREDI', 'CONTABILIDAD', '0', '0', '0', 0010, 'resoluciones_dian', 'resoluciones_dian', 'adicionar', '1'),
('ADICREGC', 'GESTREGC', 'CONTABILIDAD', '0', '0', '0', 0005, 'resoluciones_gran_contribuyente', 'resoluciones_gran_contribuyente', 'adicionar', '1'),
('ADICREIC', 'GESTREIC', 'CONTABILIDAD', '0', '0', '0', 0005, 'resoluciones_ica', 'resoluciones_ica', 'adicionar', '1'),
('ADICREIN', 'GESTREIN', 'NOMINA', '0', '0', '0', 0010, NULL, 'reporte_incapacidades', 'adicionar', '1'),
('ADICRENL', 'GESTRENL', 'NOMINA', '0', '0', '0', 0010, NULL, 'tiempos_no_laborados_dias', 'adicionar', '1'),
('ADICRERF', 'GESTRERF', 'CONTABILIDAD', '0', '0', '0', 0005, 'resoluciones_retefuente', 'resoluciones_retefuente', 'adicionar', '1'),
('ADICRETH', 'GESTRETH', 'NOMINA', '0', '0', '0', 0010, NULL, 'tiempos_no_laborados_horas', 'adicionar', '1'),
('ADICSCDE', 'GESTSCDE', 'NOMINA', '0', '0', '0', 0010, NULL, 'secciones_departamentos', 'adicionar', '1'),
('ADICSECB', 'GESTSECB', 'ADMINISTRACION', '1', '0', '0', 0020, NULL, 'secciones', 'adicionar', '1'),
('ADICSECC', 'GESTSECB', 'ADMINISTRACION', '1', '0', '0', 0005, NULL, 'secciones', 'adicionar', '1'),
('ADICSERV', 'GESTSERV', 'PROVEEDORES', '0', '0', '0', 0010, 'servicios', 'servicios', 'adicionar', '1'),
('ADICSMLV', 'GESTSMLV', 'NOMINA', '0', '0', '0', 0010, NULL, 'salario_minimo', 'adicionar', '1'),
('ADICSRVD', 'GESTSRVD', 'ADMINISTRACION', '1', '0', '0', 0020, NULL, 'servidores', 'adicionar', '1'),
('ADICSUCU', 'GESTSUCU', 'ADMINISTRACION', '1', '0', '0', 0020, NULL, 'sucursales', 'adicionar', '1'),
('ADICTABL', 'GESTTABL', 'ADMINISTRACION', '1', '0', '0', 0020, NULL, 'tablas', 'adicionar', '1'),
('ADICTASA', 'GESTTASA', 'CONTABILIDAD', '0', '0', '0', 0005, 'tasas', 'tasas', 'adicionar', '1'),
('ADICTCOM', 'GESTTCOM', 'CONTABILIDAD', '0', '0', '0', 0010, 'tipos_compra', 'tipos_compra', 'adicionar', '1'),
('ADICTDVC', 'GESTTDVC', 'CONTABILIDAD', '0', '0', '0', 0010, 'tipos_devoluciones_compra', 'tipos_devoluciones_compra', 'adicionar', '1'),
('ADICTERC', 'GESTTERC', 'CONTABILIDAD', '0', '0', '0', 0005, 'terceros', 'terceros', 'adicionar', '1'),
('ADICTERM', 'GESTTERM', 'ADMINISTRACION', '1', '0', '0', 0020, NULL, 'terminales', 'adicionar', '1'),
('ADICTIBO', 'GESTTIBO', 'ADMINISTRACION', '1', '0', '0', 0020, NULL, 'tipos_bodegas', 'adicionar', '1'),
('ADICTICO', 'GESTTICO', 'CONTABILIDAD', '0', '0', '0', 0005, 'tipos_comprobantes', 'tipos_comprobantes', 'adicionar', '1'),
('ADICTICT', 'GESTTICT', 'NOMINA', '0', '0', '0', 0010, NULL, 'tipos_contrato', 'adicionar', '1'),
('ADICTIDB', 'GESTTIDB', 'CONTABILIDAD', '0', '0', '0', 0005, 'tipos_documentos_bancarios', 'tipos_documentos_bancarios', 'adicionar', '1'),
('ADICTIDI', 'GESTTIDI', 'ADMINISTRACION', '1', '0', '0', 0020, NULL, 'tipos_documento_identidad', 'adicionar', '1'),
('ADICTIDO', 'GESTTIDO', 'CONTABILIDAD', '0', '0', '0', 0005, 'tipos_documentos', 'tipos_documentos', 'adicionar', '1'),
('ADICTIMO', 'GESTTIMO', 'CONTABILIDAD', '0', '0', '0', 0005, 'tipos_moneda', 'tipos_moneda', 'adicionar', '1'),
('ADICTIUN', 'GESTTIUN', 'INVENTARIO', '0', '0', '0', 0010, 'tipos_unidades', 'tipos_unidades', 'adicionar', '1'),
('ADICTRCO', 'GESTTRCO', 'NOMINA', '0', '0', '0', 0010, NULL, 'transacciones_contables_empleado', 'adicionar', '1'),
('ADICTRTI', 'GESTTRTI', 'NOMINA', '0', '0', '0', 0010, NULL, 'transacciones_tiempo', 'adicionar', '1'),
('ADICTULA', 'GESTTULA', 'NOMINA', '0', '0', '0', 0010, NULL, 'turnos_laborales', 'adicionar', '1'),
('ADICUNID', 'GESTUNID', 'INVENTARIO', '0', '0', '0', 0010, 'unidades', 'unidades', 'adicionar', '1'),
('ADICUSUA', 'GESTUSUA', 'ADMINISTRACION', '1', '0', '0', 0020, NULL, 'usuarios', 'adicionar', '1'),
('ADICVATO', 'GESTVATO', 'NOMINA', '0', '0', '0', 0010, NULL, 'liquidacion_vacaciones', 'adicionar', '1'),
('ADICVITA', 'GESTVITA', 'CONTABILIDAD', '0', '0', '0', 0005, 'vigencia_tasas', 'vigencia_tasas', 'adicionar', '1'),
('ANULMOCO', 'GESTMOCO', 'CONTABILIDAD', '1', '0', '0', 0020, NULL, 'movimientos_contables', 'anular', '1'),
('AUTOPAGO', 'GESTVATO', 'NOMINA', '0', '0', '0', 0030, NULL, 'liquidacion_vacaciones', 'autorizar_pago', '1'),
('CACLUSUA', 'GESTUSUA', 'ADMINISTRACION', '1', '0', '0', 0200, NULL, 'usuarios', 'cambioclave', '1'),
('CONSACDI', 'GESTACDI', 'CONTABILIDAD', '1', '0', '0', 0020, 'actividades_economicas_dian', 'actividades_economicas_dian', 'consultar', '1'),
('CONSAECO', 'GESTAECO', 'CONTABILIDAD', '1', '0', '0', 0020, 'actividades_economicas', 'actividades_economicas', 'consultar', '1'),
('CONSAFIC', 'GESTAFIC', 'NOMINA', '1', '0', '0', 0010, 'aficiones', 'aficiones', 'consultar', '1'),
('CONSANCO', 'GESTANCO', 'CONTABILIDAD', '1', '0', '0', 0010, 'anexos_contables', 'anexos_contables', 'consultar', '1'),
('CONSARTI', 'GESTARTI', 'INVENTARIO', '1', '0', '0', 0020, 'articulos', 'articulos', 'consultar', '1'),
('CONSASPI', 'GESTASPI', 'NOMINA', '0', '0', '0', 0020, NULL, 'aspirantes', 'consultar', '1'),
('CONSASTU', 'GESTASTU', 'NOMINA', '0', '0', '0', 0020, NULL, 'asignacion_turnos', 'consultar', '1'),
('CONSAUCO', 'GESTAUCO', 'CONTABILIDAD', '1', '0', '0', 0010, 'auxiliares_contables', 'auxiliares_contables', 'consultar', '1'),
('CONSAXTP', 'GESTAXTP', 'NOMINA', '0', '0', '0', 0020, NULL, 'auxilio_transporte', 'consultar', '1'),
('CONSBANC', 'GESTBANC', 'CONTABILIDAD', '1', '0', '0', 0020, 'bancos', 'bancos', 'consultar', '1'),
('CONSBARR', 'GESTBARR', 'ADMINISTRACION', '0', '0', '0', 0020, NULL, 'barrios', 'consultar', '1'),
('CONSBITA', 'GESTBITA', 'ADMINISTRACION', '0', '0', '0', 0010, NULL, 'bitacora', 'consultar', '1'),
('CONSBODE', 'GESTBODE', 'ADMINISTRACION', '0', '0', '0', 0030, NULL, 'bodegas', 'consultar', '1'),
('CONSCAPR', 'GESTCAPR', 'PROVEEDORES', '1', '0', '0', 0020, 'cargos', 'cargos', 'consultar', '1'),
('CONSCAPS', 'GESTCAPS', 'NOMINA', '0', '0', '0', 0006, 'causaciones_prestaciones_sociales', 'causaciones_prestaciones_sociales', 'consultar', '1'),
('CONSCARG', 'GESTCARG', 'ADMINISTRACION', '0', '0', '0', 0020, NULL, 'cargos', 'consultar', '1'),
('CONSCDCO', 'GESTCDCO', 'CONTABILIDAD', '1', '0', '0', 0020, 'conceptos_devolucion_compras', 'conceptos_devolucion_compras', 'consultar', '1'),
('CONSCGPS', 'GESTCGPS', 'NOMINA', '0', '0', '0', 0025, NULL, 'gastos_prestaciones_sociales', 'consultar', '1'),
('CONSCOCO', 'GESTCOCO', 'CONTABILIDAD', '1', '0', '0', 0020, NULL, 'conceptos_contabilizacion', 'consultar', '1'),
('CONSCODI', 'GESTCODI', 'CONTABILIDAD', '1', '0', '0', 0010, 'conceptos_dian', 'conceptos_dian', 'consultar', '1'),
('CONSCOPR', 'GESTCOPR', 'NOMINA', '0', '0', '0', 0020, NULL, 'conceptos_prestamos', 'consultar', '1'),
('CONSCORR', 'GESTCORR', 'ADMINISTRACION', '0', '0', '0', 0020, NULL, 'corregimientos', 'consultar', '1'),
('CONSCUBA', 'GESTCUBA', 'CONTABILIDAD', '1', '0', '0', 0020, 'cuentas_bancarias', 'cuentas_bancarias', 'consultar', '1'),
('CONSDEEM', 'GESTDEEM', 'NOMINA', '1', '0', '0', 0020, 'departamentos_empresa', 'departamentos_empresa', 'consultar', '1'),
('CONSDEPA', 'GESTDEPA', 'ADMINISTRACION', '0', '0', '0', 0020, NULL, 'departamentos', 'consultar', '1'),
('CONSDEPO', 'GESTDEPO', 'NOMINA', '0', '0', '0', 0025, NULL, 'deportes', 'consultar', '1'),
('CONSDOFE', 'GESTDOFE', 'NOMINA', '0', '0', '0', 0020, NULL, 'dominicales_festivos', 'consultar', '1'),
('CONSEMPR', 'GESTEMPR', 'ADMINISTRACION', '0', '0', '0', 0020, NULL, 'empresas', 'consultar', '1'),
('CONSENPF', 'GESTENPF', 'NOMINA', '1', '0', '0', 0020, 'entidades_parafiscales', 'entidades_parafiscales', 'consultar', '1'),
('CONSESCO', 'GESTESCO', 'NOMINA', '0', '0', '0', 0025, NULL, 'escolaridad', 'consultar', '1'),
('CONSESGR', 'GESTESGR', 'INVENTARIO', '1', '0', '0', 0010, 'estructura_grupos', 'estructura_grupos', 'consultar', '1'),
('CONSESME', 'GESTESME', 'INVENTARIO', '1', '0', '0', 0010, 'estado_mercancia', 'estado_mercancia', 'consultar', '1'),
('CONSEXRE', 'GESTEXRE', 'NOMINA', '0', '0', '0', 0020, NULL, 'extras_recargos', 'consultar', '1'),
('CONSFEPL', 'GESTFEPL', 'NOMINA', '0', '0', '0', 0025, NULL, 'fechas_planillas', 'consultar', '1'),
('CONSFODI', 'GESTFODI', 'CONTABILIDAD', '1', '0', '0', 0010, 'formatos_dian', 'formatos_dian', 'consultar', '1'),
('CONSGRUP', 'GESTGRUP', 'INVENTARIO', '1', '0', '0', 0020, 'grupos', 'grupos', 'consultar', '1'),
('CONSIDIO', 'GESTIDIO', 'NOMINA', '0', '0', '0', 0025, NULL, 'idiomas', 'consultar', '1'),
('CONSINEM', 'GESTINEM', 'NOMINA', '0', '0', '0', 0020, NULL, 'empleados', 'consultar', '1'),
('CONSLIEM', 'GESTLIEM', 'NOMINA', '0', '0', '0', 0006, 'liquidar_empleado', 'liquidar_empleado', 'consultar', '1'),
('CONSMARC', 'GESTMARC', 'INVENTARIO', '1', '0', '0', 0020, 'marcas', 'marcas', 'consultar', '1'),
('CONSMOCO', 'GESTMOCO', 'CONTABILIDAD', '1', '0', '0', 0010, NULL, 'movimientos_contables', 'consultar', '1'),
('CONSMOIN', 'GESTMOIN', 'NOMINA', '0', '0', '0', 0025, NULL, 'motivos_incapacidad', 'consultar', '1'),
('CONSMORE', 'GESTMORE', 'NOMINA', '0', '0', '0', 0025, NULL, 'motivos_retiro', 'consultar', '1'),
('CONSMUNI', 'GESTMUNI', 'ADMINISTRACION', '0', '0', '0', 0020, NULL, 'municipios', 'consultar', '1'),
('CONSNMIG', 'GESTNMIG', 'NOMINA', '0', '0', '0', 0020, NULL, 'nomina_migracion', 'consultar', '1'),
('CONSNOLA', 'GESTNOLA', 'NOMINA', '0', '0', '0', 0025, NULL, 'motivos_tiempos_no_laborar', 'consultar', '1'),
('CONSNOMA', 'GESTNOMA', 'NOMINA', '0', '0', '0', 0020, NULL, 'novedades_manuales', 'consultar', '1'),
('CONSPAIS', 'GESTPAIS', 'ADMINISTRACION', '0', '0', '0', 0020, NULL, 'paises', 'consultar', '1'),
('CONSPECO', 'GESTPECO', 'CONTABILIDAD', '1', '0', '0', 0010, NULL, 'periodos_contables', 'consultar', '1'),
('CONSPERF', 'GESTPERF', 'ADMINISTRACION', '0', '0', '0', 0020, NULL, 'perfiles', 'consultar', '1'),
('CONSPLAN', 'GESTPLAN', 'NOMINA', '0', '0', '0', 0025, NULL, 'planillas', 'consultar', '1'),
('CONSPLCO', 'GESTPLCO', 'CONTABILIDAD', '1', '0', '0', 0020, NULL, 'plan_contable', 'consultar', '1'),
('CONSPPPR', 'GESTPPPR', 'PROVEEDORES', '1', '0', '0', 0020, 'plazos_pago_proveedores', 'plazos_pago_proveedores', 'consultar', '1'),
('CONSPRIV', 'GESTPRIV', 'ADMINISTRACION', '1', '0', '0', 0030, NULL, 'privilegios', 'consultar', '1'),
('CONSPRMA', 'GESTPRMA', 'PROVEEDORES', '1', '0', '0', 0020, 'proveedores_marcas', 'proveedores_marcas', 'consultar', '1'),
('CONSPROF', 'GESTPROF', 'ADMINISTRACION', '1', '0', '0', 0030, NULL, 'profesiones_oficios', 'consultar', '1'),
('CONSPROV', 'GESTPROV', 'PROVEEDORES', '1', '0', '0', 0020, 'proveedores', 'proveedores', 'consultar', '1'),
('CONSPTEM', 'GESTPTEM', 'NOMINA', '1', '0', '0', 0020, 'control_prestamos_empleados', 'prestamos_empleados', 'consultar', '1'),
('CONSPTTE', 'GESTPTTE', 'NOMINA', '1', '0', '0', 0020, 'control_prestamos_terceros', 'prestamos_terceros', 'consultar', '1'),
('CONSRECE', 'SUBMPRSO', 'NOMINA', '0', '0', '0', 0006, 'movimiento_retiro_cesantias', 'retiro_cesantias', 'consultar', '1'),
('CONSREDI', 'GESTREDI', 'CONTABILIDAD', '1', '0', '0', 0020, 'resoluciones_dian', 'resoluciones_dian', 'consultar', '1'),
('CONSREGC', 'GESTREGC', 'CONTABILIDAD', '1', '0', '0', 0010, 'resoluciones_gran_contribuyente', 'resoluciones_gran_contribuyente', 'consultar', '1'),
('CONSREIC', 'GESTREIC', 'CONTABILIDAD', '1', '0', '0', 0010, 'resoluciones_ica', 'resoluciones_ica', 'consultar', '1'),
('CONSREIN', 'GESTREIN', 'NOMINA', '0', '0', '0', 0020, NULL, 'reporte_incapacidades', 'consultar', '1'),
('CONSRENL', 'GESTRENL', 'NOMINA', '0', '0', '0', 0020, NULL, 'tiempos_no_laborados_dias', 'consultar', '1'),
('CONSRERF', 'GESTRERF', 'CONTABILIDAD', '1', '0', '0', 0010, 'resoluciones_retefuente', 'resoluciones_retefuente', 'consultar', '1'),
('CONSRETH', 'GESTRETH', 'NOMINA', '0', '0', '0', 0020, NULL, 'tiempos_no_laborados_horas', 'consultar', '1'),
('CONSSCDE', 'GESTSCDE', 'NOMINA', '0', '0', '0', 0020, NULL, 'secciones_departamentos', 'consultar', '1'),
('CONSSECB', 'GESTSECB', 'ADMINISTRACION', '1', '0', '0', 0030, NULL, 'secciones', 'consultar', '1'),
('CONSSECC', 'GESTSECB', 'ADMINISTRACION', '1', '0', '0', 0010, NULL, 'secciones', 'consultar', '1'),
('CONSSERV', 'GESTSERV', 'PROVEEDORES', '1', '0', '0', 0020, 'servicios', 'servicios', 'consultar', '1'),
('CONSSMLV', 'GESTSMLV', 'NOMINA', '0', '0', '0', 0020, NULL, 'salario_minimo', 'consultar', '1'),
('CONSSRVD', 'GESTSRVD', 'ADMINISTRACION', '1', '0', '0', 0030, NULL, 'servidores', 'consultar', '1'),
('CONSSUCU', 'GESTSUCU', 'ADMINISTRACION', '1', '0', '0', 0030, NULL, 'sucursales', 'consultar', '1'),
('CONSTABL', 'GESTTABL', 'ADMINISTRACION', '1', '0', '0', 0030, NULL, 'tablas', 'consultar', '1'),
('CONSTASA', 'GESTTASA', 'CONTABILIDAD', '1', '0', '0', 0010, 'tasas', 'tasas', 'consultar', '1'),
('CONSTCOM', 'GESTTCOM', 'CONTABILIDAD', '1', '0', '0', 0020, 'tipos_compra', 'tipos_compra', 'consultar', '1'),
('CONSTDVC', 'GESTTDVC', 'CONTABILIDAD', '1', '0', '0', 0020, 'tipos_devoluciones_compra', 'tipos_devoluciones_compra', 'consultar', '1'),
('CONSTERC', 'GESTTERC', 'CONTABILIDAD', '1', '0', '0', 0010, 'terceros', 'terceros', 'consultar', '1'),
('CONSTERM', 'GESTTERM', 'ADMINISTRACION', '1', '0', '0', 0030, NULL, 'terminales', 'consultar', '1'),
('CONSTIBO', 'GESTTIBO', 'ADMINISTRACION', '1', '0', '0', 0030, NULL, 'tipos_bodegas', 'consultar', '1'),
('CONSTICO', 'GESTTICO', 'CONTABILIDAD', '1', '0', '0', 0010, 'tipos_comprobantes', 'tipos_comprobantes', 'consultar', '1'),
('CONSTICT', 'GESTTICT', 'NOMINA', '0', '0', '0', 0020, NULL, 'tipos_contrato', 'consultar', '1'),
('CONSTIDB', 'GESTTIDB', 'CONTABILIDAD', '1', '0', '0', 0010, 'tipos_documentos_bancarios', 'tipos_documentos_bancarios', 'consultar', '1'),
('CONSTIDI', 'GESTTIDI', 'ADMINISTRACION', '1', '0', '0', 0030, NULL, 'tipos_documento_identidad', 'consultar', '1'),
('CONSTIDO', 'GESTTIDO', 'CONTABILIDAD', '1', '0', '0', 0010, 'tipos_documentos', 'tipos_documentos', 'consultar', '1'),
('CONSTIMO', 'GESTTIMO', 'CONTABILIDAD', '1', '0', '0', 0010, 'tipos_moneda', 'tipos_moneda', 'consultar', '1'),
('CONSTIUN', 'GESTTIUN', 'INVENTARIO', '1', '0', '0', 0020, 'tipos_unidades', 'tipos_unidades', 'consultar', '1'),
('CONSTRCO', 'GESTTRCO', 'NOMINA', '0', '0', '0', 0020, NULL, 'transacciones_contables_empleado', 'consultar', '1'),
('CONSTRTI', 'GESTTRTI', 'NOMINA', '0', '0', '0', 0025, NULL, 'transacciones_tiempo', 'consultar', '1'),
('CONSTULA', 'GESTTULA', 'NOMINA', '0', '0', '0', 0025, NULL, 'turnos_laborales', 'consultar', '1'),
('CONSUCU', 'GESTSUCU', 'ADMINISTRACION', '1', '0', '0', 0030, NULL, 'sucursales', 'consultar', '1'),
('CONSUNID', 'GESTUNID', 'INVENTARIO', '1', '0', '0', 0020, 'unidades', 'unidades', 'consultar', '1'),
('CONSUSUA', 'GESTUSUA', 'ADMINISTRACION', '1', '0', '0', 0030, NULL, 'usuarios', 'consultar', '1'),
('CONSVATO', 'GESTVATO', 'NOMINA', '0', '0', '0', 0020, NULL, 'liquidacion_vacaciones', 'consultar', '1'),
('CONSVITA', 'GESTVITA', 'CONTABILIDAD', '1', '0', '0', 0010, 'vigencia_tasas', 'vigencia_tasas', 'consultar', '1'),
('DESCARCH', NULL, 'ADMINISTRACION', '1', '1', '0', 0550, NULL, 'principal', 'descargar_archivo', '1'),
('ELIMACDI', 'GESTACDI', 'CONTABILIDAD', '1', '0', '0', 0040, 'actividades_economicas_dian', 'actividades_economicas_dian', 'eliminar', '1'),
('ELIMAECO', 'GESTAECO', 'CONTABILIDAD', '1', '0', '0', 0040, 'actividades_economicas', 'actividades_economicas', 'eliminar', '1'),
('ELIMAFIC', 'GESTAFIC', 'NOMINA', '1', '0', '0', 0020, 'aficiones', 'aficiones', 'eliminar', '1'),
('ELIMANCO', 'GESTANCO', 'CONTABILIDAD', '1', '0', '0', 0020, 'anexos_contables', 'anexos_contables', 'eliminar', '1'),
('ELIMARTI', 'GESTARTI', 'INVENTARIO', '1', '0', '0', 0030, 'articulos', 'articulos', 'eliminar', '1'),
('ELIMASPI', 'GESTASPI', 'NOMINA', '0', '0', '0', 0040, NULL, 'aspirantes', 'eliminar', '1'),
('ELIMASTU', 'GESTASTU', 'NOMINA', '0', '0', '0', 0040, NULL, 'asignacion_turnos', 'eliminar', '1'),
('ELIMAUCO', 'GESTAUCO', 'CONTABILIDAD', '1', '0', '0', 0020, 'auxiliares_contables', 'auxiliares_contables', 'eliminar', '1'),
('ELIMAXTP', 'GESTAXTP', 'NOMINA', '0', '0', '0', 0040, NULL, 'auxilio_transporte', 'eliminar', '1'),
('ELIMBANC', 'GESTBANC', 'CONTABILIDAD', '1', '0', '0', 0040, 'bancos', 'bancos', 'eliminar', '1'),
('ELIMBARR', 'GESTBARR', 'ADMINISTRACION', '0', '0', '0', 0040, NULL, 'barrios', 'eliminar', '1'),
('ELIMBODE', 'GESTBODE', 'ADMINISTRACION', '0', '0', '0', 0050, NULL, 'bodegas', 'eliminar', '1'),
('ELIMCAPR', 'GESTCAPR', 'PROVEEDORES', '1', '0', '0', 0040, 'cargos', 'cargos', 'eliminar', '1'),
('ELIMCAPS', 'GESTCAPS', 'NOMINA', '0', '0', '0', 0007, 'causaciones_prestaciones_sociales', 'causaciones_prestaciones_sociales', 'eliminar', '1'),
('ELIMCARG', 'GESTCARG', 'ADMINISTRACION', '0', '0', '0', 0020, NULL, 'cargos', 'eliminar', '1'),
('ELIMCDCO', 'GESTCDCO', 'CONTABILIDAD', '1', '0', '0', 0040, 'conceptos_devolucion_compras', 'conceptos_devolucion_compras', 'eliminar', '1'),
('ELIMCGPS', 'GESTCGPS', 'NOMINA', '0', '0', '0', 0040, NULL, 'gastos_prestaciones_sociales', 'eliminar', '1'),
('ELIMCOCO', 'GESTCOCO', 'CONTABILIDAD', '1', '0', '0', 0040, NULL, 'conceptos_contabilizacion', 'eliminar', '1'),
('ELIMCODI', 'GESTCODI', 'CONTABILIDAD', '1', '0', '0', 0020, 'conceptos_dian', 'conceptos_dian', 'eliminar', '1'),
('ELIMCOPR', 'GESTCOPR', 'NOMINA', '0', '0', '0', 0040, NULL, 'conceptos_prestamos', 'eliminar', '1'),
('ELIMCORR', 'GESTCORR', 'ADMINISTRACION', '0', '0', '0', 0040, NULL, 'corregimientos', 'eliminar', '1'),
('ELIMCUBA', 'GESTCUBA', 'CONTABILIDAD', '1', '0', '0', 0040, 'cuentas_bancarias', 'cuentas_bancarias', 'eliminar', '1'),
('ELIMDEEM', 'GESTDEEM', 'NOMINA', '1', '0', '0', 0040, 'departamentos_empresa', 'departamentos_empresa', 'eliminar', '1'),
('ELIMDEPA', 'GESTDEPA', 'ADMINISTRACION', '0', '0', '0', 0040, NULL, 'departamentos', 'eliminar', '1'),
('ELIMDEPO', 'GESTDEPO', 'NOMINA', '0', '0', '0', 0040, NULL, 'deportes', 'eliminar', '1'),
('ELIMDOFE', 'GESTDOFE', 'NOMINA', '0', '0', '0', 0040, NULL, 'dominicales_festivos', 'eliminar', '1'),
('ELIMEMPR', 'GESTEMPR', 'ADMINISTRACION', '0', '0', '0', 0020, NULL, 'empresas', 'eliminar', '1'),
('ELIMENPF', 'GESTENPF', 'NOMINA', '1', '0', '0', 0040, 'entidades_parafiscales', 'entidades_parafiscales', 'eliminar', '1'),
('ELIMESCO', 'GESTESCO', 'NOMINA', '0', '0', '0', 0040, NULL, 'escolaridad', 'eliminar', '1'),
('ELIMESGR', 'GESTESGR', 'INVENTARIO', '1', '0', '0', 0020, 'estructura_grupos', 'estructura_grupos', 'eliminar', '1'),
('ELIMESME', 'GESTESME', 'INVENTARIO', '1', '0', '0', 0020, 'estado_mercancia', 'estado_mercancia', 'eliminar', '1'),
('ELIMEXRE', 'GESTEXRE', 'NOMINA', '0', '0', '0', 0040, NULL, 'extras_recargos', 'eliminar', '1'),
('ELIMFEPL', 'GESTFEPL', 'NOMINA', '0', '0', '0', 0040, NULL, 'fechas_planillas', 'eliminar', '1'),
('ELIMFODI', 'GESTFODI', 'CONTABILIDAD', '1', '0', '0', 0020, 'formatos_dian', 'formatos_dian', 'eliminar', '1'),
('ELIMGRUP', 'GESTGRUP', 'INVENTARIO', '1', '0', '0', 0040, 'grupos', 'grupos', 'eliminar', '1'),
('ELIMIDIO', 'GESTIDIO', 'NOMINA', '0', '0', '0', 0040, NULL, 'idiomas', 'eliminar', '1'),
('ELIMLIEM', 'GESTLIEM', 'NOMINA', '0', '0', '0', 0007, 'liquidar_empleado', 'liquidar_empleado', 'eliminar', '1'),
('ELIMMARC', 'GESTMARC', 'INVENTARIO', '1', '0', '0', 0040, 'marcas', 'marcas', 'eliminar', '1'),
('ELIMMOIN', 'GESTMOIN', 'NOMINA', '0', '0', '0', 0040, NULL, 'motivos_incapacidad', 'eliminar', '1'),
('ELIMMORE', 'GESTMORE', 'NOMINA', '0', '0', '0', 0040, NULL, 'motivos_retiro', 'eliminar', '1'),
('ELIMMUNI', 'GESTMUNI', 'ADMINISTRACION', '0', '0', '0', 0040, NULL, 'municipios', 'eliminar', '1'),
('ELIMNMIG', 'GESTNMIG', 'NOMINA', '0', '0', '0', 0040, NULL, 'nomina_migracion', 'eliminar', '1'),
('ELIMNOLA', 'GESTNOLA', 'NOMINA', '0', '0', '0', 0040, NULL, 'motivos_tiempos_no_laborar', 'eliminar', '1'),
('ELIMNOMA', 'GESTNOMA', 'NOMINA', '0', '0', '0', 0040, NULL, 'novedades_manuales', 'eliminar', '1'),
('ELIMPAIS', 'GESTPAIS', 'ADMINISTRACION', '0', '0', '0', 0040, NULL, 'paises', 'eliminar', '1'),
('ELIMPECO', 'GESTPECO', 'CONTABILIDAD', '1', '0', '0', 0020, NULL, 'periodos_contables', 'eliminar', '1'),
('ELIMPERF', 'GESTPERF', 'ADMINISTRACION', '0', '0', '0', 0020, NULL, 'perfiles', 'eliminar', '1'),
('ELIMPLAN', 'GESTPLAN', 'NOMINA', '0', '0', '0', 0040, NULL, 'planillas', 'eliminar', '1'),
('ELIMPLCO', 'GESTPLCO', 'CONTABILIDAD', '1', '0', '0', 0040, NULL, 'plan_contable', 'eliminar', '1'),
('ELIMPPPR', 'GESTPPPR', 'PROVEEDORES', '1', '0', '0', 0040, 'plazos_pago_proveedores', 'plazos_pago_proveedores', 'eliminar', '1'),
('ELIMPRIV', 'GESTPRIV', 'ADMINISTRACION', '1', '0', '0', 0050, NULL, 'privilegios', 'eliminar', '1'),
('ELIMPRMA', 'GESTPRMA', 'PROVEEDORES', '1', '0', '0', 0040, 'proveedores_marcas', 'proveedores_marcas', 'eliminar', '1'),
('ELIMPROF', 'GESTPROF', 'ADMINISTRACION', '1', '0', '0', 0050, NULL, 'profesiones_oficios', 'eliminar', '1'),
('ELIMPROV', 'GESTPROV', 'PROVEEDORES', '1', '0', '0', 0040, 'proveedores', 'proveedores', 'eliminar', '1'),
('ELIMPTEM', 'GESTPTEM', 'NOMINA', '1', '0', '0', 0040, 'control_prestamos_empleados', 'prestamos_empleados', 'eliminar', '1'),
('ELIMPTTE', 'GESTPTTE', 'NOMINA', '1', '0', '0', 0040, 'control_prestamos_terceros', 'prestamos_terceros', 'eliminar', '1'),
('ELIMRECE', 'SUBMPRSO', 'NOMINA', '0', '0', '0', 0007, 'movimiento_retiro_cesantias', 'retiro_cesantias', 'eliminar', '1'),
('ELIMREDI', 'GESTREDI', 'CONTABILIDAD', '1', '0', '0', 0040, 'resoluciones_dian', 'resoluciones_dian', 'eliminar', '1'),
('ELIMREGC', 'GESTREGC', 'CONTABILIDAD', '1', '0', '0', 0020, 'resoluciones_gran_contribuyente', 'resoluciones_gran_contribuyente', 'eliminar', '1'),
('ELIMREIC', 'GESTREIC', 'CONTABILIDAD', '1', '0', '0', 0020, 'resoluciones_ica', 'resoluciones_ica', 'eliminar', '1'),
('ELIMREIN', 'GESTREIN', 'NOMINA', '0', '0', '0', 0040, NULL, 'reporte_incapacidades', 'eliminar', '1'),
('ELIMRENL', 'GESTRENL', 'NOMINA', '0', '0', '0', 0040, NULL, 'tiempos_no_laborados_dias', 'eliminar', '1'),
('ELIMRERF', 'GESTRERF', 'CONTABILIDAD', '1', '0', '0', 0020, 'resoluciones_retefuente', 'resoluciones_retefuente', 'eliminar', '1'),
('ELIMRETH', 'GESTRETH', 'NOMINA', '0', '0', '0', 0040, NULL, 'tiempos_no_laborados_horas', 'eliminar', '1'),
('ELIMSCDE', 'GESTSCDE', 'NOMINA', '0', '0', '0', 0040, NULL, 'secciones_departamentos', 'eliminar', '1'),
('ELIMSECB', 'GESTSECB', 'ADMINISTRACION', '1', '0', '0', 0050, NULL, 'secciones', 'eliminar', '1'),
('ELIMSECC', 'GESTSECB', 'ADMINISTRACION', '1', '0', '0', 0020, NULL, 'secciones', 'eliminar', '1'),
('ELIMSERV', 'GESTSERV', 'PROVEEDORES', '1', '0', '0', 0040, 'servicios', 'servicios', 'eliminar', '1'),
('ELIMSMLV', 'GESTSMLV', 'NOMINA', '0', '0', '0', 0040, NULL, 'salario_minimo', 'eliminar', '1'),
('ELIMSRVD', 'GESTSRVD', 'ADMINISTRACION', '1', '0', '0', 0050, NULL, 'servidores', 'eliminar', '1'),
('ELIMSUCU', 'GESTSUCU', 'ADMINISTRACION', '1', '0', '0', 0050, NULL, 'sucursales', 'eliminar', '1'),
('ELIMTABL', 'GESTTABL', 'ADMINISTRACION', '1', '0', '0', 0050, NULL, 'tablas', 'eliminar', '1'),
('ELIMTASA', 'GESTTASA', 'CONTABILIDAD', '1', '0', '0', 0020, 'tasas', 'tasas', 'eliminar', '1'),
('ELIMTCOM', 'GESTTCOM', 'CONTABILIDAD', '1', '0', '0', 0040, 'tipos_compra', 'tipos_compra', 'eliminar', '1'),
('ELIMTDVC', 'GESTTDVC', 'CONTABILIDAD', '1', '0', '0', 0040, 'tipos_devoluciones_compra', 'tipos_devoluciones_compra', 'eliminar', '1'),
('ELIMTERC', 'GESTTERC', 'CONTABILIDAD', '1', '0', '0', 0020, 'terceros', 'terceros', 'eliminar', '1'),
('ELIMTERM', 'GESTTERM', 'ADMINISTRACION', '1', '0', '0', 0050, NULL, 'terminales', 'eliminar', '1'),
('ELIMTIBO', 'GESTTIBO', 'ADMINISTRACION', '1', '0', '0', 0050, NULL, 'tipos_bodegas', 'eliminar', '1'),
('ELIMTICO', 'GESTTICO', 'CONTABILIDAD', '1', '0', '0', 0020, 'tipos_comprobantes', 'tipos_comprobantes', 'eliminar', '1'),
('ELIMTICT', 'GESTTICT', 'NOMINA', '0', '0', '0', 0040, NULL, 'tipos_contrato', 'eliminar', '1'),
('ELIMTIDB', 'GESTTIDB', 'CONTABILIDAD', '1', '0', '0', 0020, 'tipos_documentos_bancarios', 'tipos_documentos_bancarios', 'eliminar', '1'),
('ELIMTIDI', 'GESTTIDI', 'ADMINISTRACION', '1', '0', '0', 0050, NULL, 'tipos_documento_identidad', 'eliminar', '1'),
('ELIMTIDO', 'GESTTIDO', 'CONTABILIDAD', '1', '0', '0', 0020, 'tipos_documentos', 'tipos_documentos', 'eliminar', '1'),
('ELIMTIMO', 'GESTTIMO', 'CONTABILIDAD', '1', '0', '0', 0020, 'tipos_moneda', 'tipos_moneda', 'eliminar', '1'),
('ELIMTIUN', 'GESTTIUN', 'INVENTARIO', '1', '0', '0', 0040, 'tipos_unidades', 'tipos_unidades', 'eliminar', '1'),
('ELIMTRCO', 'GESTTRCO', 'NOMINA', '0', '0', '0', 0040, NULL, 'transacciones_contables_empleado', 'eliminar', '1'),
('ELIMTRTI', 'GESTTRTI', 'NOMINA', '0', '0', '0', 0040, NULL, 'transacciones_tiempo', 'eliminar', '1'),
('ELIMTULA', 'GESTTULA', 'NOMINA', '0', '0', '0', 0040, NULL, 'turnos_laborales', 'eliminar', '1'),
('ELIMUNID', 'GESTUNID', 'INVENTARIO', '1', '0', '0', 0040, 'unidades', 'unidades', 'eliminar', '1'),
('ELIMUSUA', 'GESTUSUA', 'ADMINISTRACION', '1', '0', '0', 0050, NULL, 'usuarios', 'eliminar', '1'),
('ELIMVATO', 'GESTVATO', 'NOMINA', '0', '0', '0', 0040, NULL, 'liquidacion_vacaciones', 'eliminar', '1'),
('ELIMVITA', 'GESTVITA', 'CONTABILIDAD', '1', '0', '0', 0020, 'vigencia_tasas', 'vigencia_tasas', 'eliminar', '1'),
('EXPOTASA', 'GESTTASA', 'CONTABILIDAD', '0', '0', '0', 0030, 'tasas', 'tasas', 'listar', '1'),
('EXPOVITA', 'GESTVITA', 'CONTABILIDAD', '0', '0', '0', 0030, 'vigencia_tasas', 'vigencia_tasas', 'listar', '1'),
('GESTACDI', 'SUBMFINA', 'CONTABILIDAD', '1', '0', '1', 0004, 'actividades_economicas_dian', 'actividades_economicas_dian', 'menu', '1'),
('GESTAECO', 'SUBMFINA', 'CONTABILIDAD', '1', '0', '1', 0005, 'actividades_economicas', 'actividades_economicas', 'menu', '1'),
('GESTAFIC', 'SUBMDCRH', 'NOMINA', '1', '0', '1', 0170, 'aficiones', 'aficiones', 'menu', '1'),
('GESTANCO', 'SUBMINCO', 'CONTABILIDAD', '1', '0', '1', 0010, 'anexos_contables', 'anexos_contables', 'menu', '1'),
('GESTARTI', 'SUBMDCIN', 'INVENTARIO', '0', '0', '1', 0010, 'articulos', 'articulos', 'menu', '1'),
('GESTASPI', 'SUBMDCRH', 'NOMINA', '0', '0', '1', 0010, NULL, 'aspirantes', 'menu', '1'),
('GESTASTU', 'SUBMNOMI', 'NOMINA', '0', '0', '1', 0010, NULL, 'asignacion_turnos', 'menu', '1'),
('GESTAUCO', 'SUBMINCO', 'CONTABILIDAD', '1', '0', '1', 0015, 'auxiliares_contables', 'auxiliares_contables', 'menu', '1'),
('GESTAXTP', 'SUBMPRAN', 'NOMINA', '0', '0', '1', 0075, NULL, 'auxilio_transporte', 'menu', '1'),
('GESTBANC', 'SUBMFINA', 'CONTABILIDAD', '1', '0', '1', 0010, 'bancos', 'bancos', 'menu', '1'),
('GESTBARR', 'SUBMUBIG', 'ADMINISTRACION', '0', '0', '1', 0500, NULL, 'barrios', 'menu', '1'),
('GESTBITA', 'SUBMSEGU', 'ADMINISTRACION', '0', '0', '1', 0500, NULL, 'bitacora', 'menu', '1'),
('GESTBODE', 'SUBMESTC', 'ADMINISTRACION', '0', '0', '1', 0300, NULL, 'bodegas', 'menu', '1'),
('GESTCAPR', 'SUBMDCPV', 'PROVEEDORES', '1', '0', '1', 0090, 'cargos', 'cargos', 'menu', '1'),
('GESTCAPS', 'SUBMPRSO', 'NOMINA', '0', '0', '1', 0001, NULL, 'causaciones_prestaciones_sociales', 'menu', '1'),
('GESTCARG', 'SUBMDCAD', 'ADMINISTRACION', '0', '0', '1', 0300, NULL, 'cargos', 'menu', '1'),
('GESTCDCO', 'SUBMICPR', 'CONTABILIDAD', '1', '0', '1', 0040, 'conceptos_devolucion_compras', 'conceptos_devolucion_compras', 'menu', '1'),
('GESTCGPS', 'SUBMDCRH', 'NOMINA', '0', '0', '1', 0030, NULL, 'gastos_prestaciones_sociales', 'menu', '1'),
('GESTCHEM', 'SUBMPAGO', 'NOMINA', '0', '0', '1', 0030, NULL, 'contabilizar_planilla', 'forma_pago_cheque_empleado', '2'),
('GESTCHSU', 'SUBMPAGO', 'NOMINA', '0', '0', '1', 0020, NULL, 'contabilizar_planilla', 'forma_pago_cheque_sucursal', '2'),
('GESTCOCO', 'SUBMICPR', 'CONTABILIDAD', '1', '0', '1', 0020, NULL, 'conceptos_contabilizacion', 'menu', '1'),
('GESTCODI', 'SUBMINTR', 'CONTABILIDAD', '1', '0', '1', 0025, 'conceptos_dian', 'conceptos_dian', 'menu', '1'),
('GESTCOPR', 'SUBMDCRH', 'NOMINA', '0', '0', '1', 0045, NULL, 'conceptos_prestamos', 'menu', '1'),
('GESTCORR', 'SUBMUBIG', 'ADMINISTRACION', '0', '0', '1', 0400, NULL, 'corregimientos', 'menu', '1'),
('GESTCUBA', 'SUBMFINA', 'CONTABILIDAD', '1', '0', '1', 0020, 'cuentas_bancarias', 'cuentas_bancarias', 'menu', '1'),
('GESTDCPL', 'SUBMPRPL', 'NOMINA', '0', '0', '1', 0040, NULL, 'descontabilizar_planilla', 'descontabilizar_planilla', '2'),
('GESTDEEM', 'SUBMDCRH', 'NOMINA', '0', '0', '1', 0080, 'departamentos_empresa', 'departamentos_empresa', 'menu', '1'),
('GESTDEPA', 'SUBMUBIG', 'ADMINISTRACION', '0', '0', '1', 0200, NULL, 'departamentos', 'menu', '1'),
('GESTDEPO', 'SUBMDCRH', 'NOMINA', '0', '0', '1', 0160, NULL, 'deportes', 'menu', '1'),
('GESTDOFE', 'SUBMPRAN', 'NOMINA', '0', '0', '1', 0065, NULL, 'dominicales_festivos', 'menu', '1'),
('GESTELMO', 'SUBMPRPL', 'NOMINA', '0', '0', '1', 0045, NULL, 'descontabilizar_planilla', 'eliminar_movimientos', '2'),
('GESTEMPR', 'SUBMESTC', 'ADMINISTRACION', '0', '0', '1', 0100, NULL, 'empresas', 'menu', '1'),
('GESTENPF', 'SUBMDCRH', 'NOMINA', '1', '0', '1', 0110, 'entidades_parafiscales', 'entidades_parafiscales', 'menu', '1'),
('GESTESCO', 'SUBMDCRH', 'NOMINA', '0', '0', '1', 0150, NULL, 'escolaridad', 'menu', '1'),
('GESTESGR', 'SUBMDCIN', 'INVENTARIO', '0', '0', '1', 0040, 'estructura_grupos', 'estructura_grupos', 'menu', '1'),
('GESTESME', 'SUBMDCIN', 'INVENTARIO', '1', '0', '1', 0040, 'estado_mercancia', 'estado_mercancia', 'menu', '1'),
('GESTEXRE', 'SUBMNOMI', 'NOMINA', '0', '0', '1', 0030, NULL, 'extras_recargos', 'menu', '1'),
('GESTFEPL', 'SUBMDCRH', 'NOMINA', '0', '0', '1', 0070, NULL, 'fechas_planillas', 'menu', '1'),
('GESTFODI', 'SUBMINTR', 'CONTABILIDAD', '1', '0', '1', 0020, 'formatos_dian', 'formatos_dian', 'menu', '1'),
('GESTGENE', 'SUBMPAGO', 'NOMINA', '0', '0', '1', 0010, NULL, 'contabilizar_planilla', 'forma_pago_general', '2'),
('GESTGRUP', 'SUBMDCIN', 'INVENTARIO', '0', '0', '1', 0030, 'grupos', 'grupos', 'menu', '1'),
('GESTIDIO', 'SUBMDCRH', 'NOMINA', '0', '0', '1', 0140, NULL, 'idiomas', 'menu', '1'),
('GESTINEM', 'SUBMNOMI', 'NOMINA', '0', '0', '1', 0005, NULL, 'empleados', 'menu', '1'),
('GESTLIEM', 'SUBMNOMI', 'NOMINA', '0', '0', '1', 0070, NULL, 'liquidar_empleado', 'menu', '1'),
('GESTLQPR', 'SUBMPRPL', 'NOMINA', '0', '0', '1', 0025, NULL, 'liquidar_prima', 'liquidar_prima', '2'),
('GESTLQSL', 'SUBMPRPL', 'NOMINA', '0', '0', '1', 0010, NULL, 'liquidar_salario', 'liquidar_salario', '2'),
('GESTLQSP', 'SUBMPRPL', 'NOMINA', '0', '0', '1', 0020, NULL, 'liquidar_salud_pension', 'liquidar_salud_pension', '2'),
('GESTLSRE', 'SUBMPRPL', 'NOMINA', '0', '0', '1', 0017, NULL, 'liquidar_salario_retroactivo', 'liquidar_salario_retroactivo', '2'),
('GESTMARC', 'SUBMDCIN', 'INVENTARIO', '0', '0', '1', 0050, 'marcas', 'marcas', 'menu', '1'),
('GESTMOCO', 'SUBMOPCO', 'CONTABILIDAD', '1', '0', '1', 8000, NULL, 'movimientos_contables', 'menu', '1'),
('GESTMOIN', 'SUBMDCRH', 'NOMINA', '0', '0', '1', 0130, NULL, 'motivos_incapacidad', 'menu', '1'),
('GESTMORE', 'SUBMDCRH', 'NOMINA', '0', '0', '1', 0120, NULL, 'motivos_retiro', 'menu', '1'),
('GESTMUNI', 'SUBMUBIG', 'ADMINISTRACION', '0', '0', '1', 0300, NULL, 'municipios', 'menu', '1'),
('GESTNMIG', 'SUBMPENO', 'NOMINA', '0', '0', '1', 0100, NULL, 'nomina_migracion', 'menu', '1'),
('GESTNOLA', 'SUBMDCRH', 'NOMINA', '0', '0', '1', 0135, NULL, 'motivos_tiempos_no_laborar', 'menu', '1'),
('GESTNOMA', 'SUBMPRPL', 'NOMINA', '0', '0', '1', 0015, NULL, 'novedades_manuales', 'menu', '1'),
('GESTPAEF', 'SUBMPAGO', 'NOMINA', '0', '0', '1', 0030, NULL, 'contabilizar_planilla', 'forma_pago_efectivo', '2'),
('GESTPAIS', 'SUBMUBIG', 'ADMINISTRACION', '0', '0', '1', 0100, NULL, 'paises', 'menu', '1'),
('GESTPECO', 'SUBMINCO', 'CONTABILIDAD', '1', '0', '1', 0003, NULL, 'periodos_contables', 'menu', '1'),
('GESTPERF', 'SUBMACCE', 'ADMINISTRACION', '0', '0', '1', 0100, NULL, 'perfiles', 'menu', '1'),
('GESTPLAN', 'SUBMDCRH', 'NOMINA', '0', '0', '1', 0060, NULL, 'planillas', 'menu', '1'),
('GESTPLCO', 'SUBMINCO', 'CONTABILIDAD', '1', '0', '1', 0005, NULL, 'plan_contable', 'menu', '1'),
('GESTPPPR', 'SUBMDCPV', 'PROVEEDORES', '1', '0', '1', 0110, 'plazos_pago_proveedores', 'plazos_pago_proveedores', 'menu', '1'),
('GESTPREF', 'SUBMACCE', 'ADMINISTRACION', '0', '0', '1', 0200, NULL, 'principal', NULL, '1'),
('GESTPRIV', 'SUBMACCE', 'ADMINISTRACION', '0', '0', '1', 0400, NULL, 'privilegios', 'menu', '1'),
('GESTPRMA', 'SUBMDCPV', 'PROVEEDORES', '1', '0', '1', 0100, 'proveedores_marcas', 'proveedores_marcas', 'menu', '1'),
('GESTPROF', 'SUBMDCAD', 'ADMINISTRACION', '0', '0', '1', 0100, NULL, 'profesiones_oficios', 'menu', '1'),
('GESTPROV', 'SUBMDCPV', 'PROVEEDORES', '1', '0', '1', 0010, 'proveedores', 'proveedores', 'menu', '1'),
('GESTPTEM', 'SUBMNOMI', 'NOMINA', '1', '0', '1', 0045, 'control_prestamos_empleados', 'prestamos_empleados', 'menu', '1'),
('GESTPTTE', 'SUBMNOMI', 'NOMINA', '1', '0', '1', 0046, 'control_prestamos_terceros', 'prestamos_terceros', 'menu', '1'),
('GESTRECE', 'SUBMPRSO', 'NOMINA', '0', '0', '1', 0002, NULL, 'retiro_cesantias', 'menu', '1'),
('GESTREDI', 'SUBMINTR', 'CONTABILIDAD', '1', '0', '1', 0005, 'resoluciones_dian', 'resoluciones_dian', 'menu', '1'),
('GESTREGC', 'SUBMINTR', 'CONTABILIDAD', '1', '0', '1', 0010, 'resoluciones_gran_contribuyente', 'resoluciones_gran_contribuyente', 'menu', '1'),
('GESTREIC', 'SUBMINTR', 'CONTABILIDAD', '1', '0', '1', 0010, 'resoluciones_ica', 'resoluciones_ica', 'menu', '1'),
('GESTREIN', 'SUBMNOMI', 'NOMINA', '0', '0', '1', 0020, NULL, 'reporte_incapacidades', 'menu', '1'),
('GESTRENL', 'SUBMRETL', 'NOMINA', '0', '0', '1', 0020, NULL, 'tiempos_no_laborados_dias', 'menu', '1'),
('GESTRERF', 'SUBMINTR', 'CONTABILIDAD', '1', '0', '1', 0010, 'resoluciones_retefuente', 'resoluciones_retefuente', 'menu', '1'),
('GESTRETH', 'SUBMRETL', 'NOMINA', '0', '0', '1', 0030, NULL, 'tiempos_no_laborados_horas', 'menu', '1'),
('GESTSCDE', 'SUBMDCRH', 'NOMINA', '0', '0', '1', 0090, NULL, 'secciones_departamentos', 'menu', '1'),
('GESTSECB', 'SUBMESTC', 'ADMINISTRACION', '0', '0', '1', 0400, NULL, 'secciones', 'menu', '1'),
('GESTSERV', 'SUBMDCPV', 'PROVEEDORES', '0', '0', '1', 0040, 'servicios', 'servicios', 'menu', '1'),
('GESTSMLV', 'SUBMPRAN', 'NOMINA', '0', '0', '1', 0075, NULL, 'salario_minimo', 'menu', '1'),
('GESTSRVD', 'SUBMDISP', 'ADMINISTRACION', '0', '0', '1', 0100, NULL, 'servidores', 'menu', '1'),
('GESTSUCU', 'SUBMESTC', 'ADMINISTRACION', '0', '0', '1', 0200, NULL, 'sucursales', 'menu', '1'),
('GESTTABL', 'SUBMDCAD', 'ADMINISTRACION', '0', '0', '1', 0600, NULL, 'tablas', 'menu', '1'),
('GESTTASA', 'SUBMTASA', 'CONTABILIDAD', '1', '0', '1', 0005, 'tasas', 'tasas', 'menu', '1'),
('GESTTCOM', 'SUBMICPR', 'CONTABILIDAD', '1', '0', '1', 0010, 'tipos_compra', 'tipos_compra', 'menu', '1'),
('GESTTDVC', 'SUBMICPR', 'CONTABILIDAD', '1', '0', '1', 0030, 'tipos_devoluciones_compra', 'tipos_devoluciones_compra', 'menu', '1'),
('GESTTERC', 'SUBMINCO', 'CONTABILIDAD', '1', '0', '1', 0001, 'terceros', 'terceros', 'menu', '1'),
('GESTTERM', 'SUBMDISP', 'ADMINISTRACION', '0', '0', '1', 0200, NULL, 'terminales', 'menu', '1'),
('GESTTIBO', 'SUBMDCAD', 'ADMINISTRACION', '0', '0', '1', 0200, NULL, 'tipos_bodegas', 'menu', '1'),
('GESTTICO', 'SUBMINCO', 'CONTABILIDAD', '1', '0', '1', 0020, 'tipos_comprobantes', 'tipos_comprobantes', 'menu', '1'),
('GESTTICT', 'SUBMDCRH', 'NOMINA', '0', '0', '1', 0100, NULL, 'tipos_contrato', 'menu', '1'),
('GESTTIDB', 'SUBMINCO', 'CONTABILIDAD', '1', '0', '1', 0027, 'tipos_documentos_bancarios', 'tipos_documentos_bancarios', 'menu', '1'),
('GESTTIDI', 'SUBMDCAD', 'ADMINISTRACION', '0', '0', '1', 0200, NULL, 'tipos_documento_identidad', 'menu', '1'),
('GESTTIDO', 'SUBMINCO', 'CONTABILIDAD', '1', '0', '1', 0025, 'tipos_documentos', 'tipos_documentos', 'menu', '1'),
('GESTTIMO', 'SUBMFINA', 'CONTABILIDAD', '1', '0', '1', 0035, 'tipos_moneda', 'tipos_moneda', 'menu', '1'),
('GESTTIUN', 'SUBMDCIN', 'INVENTARIO', '1', '0', '1', 0060, 'tipos_unidades', 'tipos_unidades', 'menu', '1'),
('GESTTLPL', 'SUBMDCRH', 'NOMINA', '0', '0', '1', 0065, NULL, 'titulos_planillas', 'titulos_planillas', '2'),
('GESTTRCO', 'SUBMDCRH', 'NOMINA', '0', '0', '1', 0020, NULL, 'transacciones_contables_empleado', 'menu', '1'),
('GESTTRTI', 'SUBMDCRH', 'NOMINA', '0', '0', '1', 0040, NULL, 'transacciones_tiempo', 'menu', '1'),
('GESTTULA', 'SUBMDCRH', 'NOMINA', '0', '0', '1', 0050, NULL, 'turnos_laborales', 'menu', '1'),
('GESTUNID', 'SUBMDCIN', 'INVENTARIO', '1', '0', '1', 0070, 'unidades', 'unidades', 'menu', '1'),
('GESTUSUA', 'SUBMACCE', 'ADMINISTRACION', '0', '0', '1', 0300, NULL, 'usuarios', 'menu', '1'),
('GESTVATO', 'SUBMPRSO', 'NOMINA', '0', '0', '1', 0002, NULL, 'liquidacion_vacaciones', 'menu', '1'),
('GESTVITA', 'SUBMTASA', 'CONTABILIDAD', '1', '0', '1', 0010, 'vigencia_tasas', 'vigencia_tasas', 'menu', '1'),
('LIQUPAGA', 'GESTVATO', 'NOMINA', '0', '0', '0', 0040, NULL, 'liquidacion_vacaciones', 'pagar', '1'),
('LISTACDI', 'GESTACDI', 'CONTABILIDAD', '1', '0', '0', 0050, 'actividades_economicas_dian', 'actividades_economicas_dian', 'listar', '1'),
('LISTAECO', 'GESTAECO', 'CONTABILIDAD', '1', '0', '0', 0050, 'actividades_economicas', 'actividades_economicas', 'listar', '1'),
('LISTAPLA', 'SUBMPRPL', 'NOMINA', '0', '0', '1', 0060, NULL, 'lista_planilla', 'lista_planilla', '2'),
('LISTASPI', 'GESTASPI', 'NOMINA', '0', '0', '0', 0040, NULL, 'aspirantes', 'listar', '1'),
('LISTASTU', 'GESTASTU', 'NOMINA', '0', '0', '0', 0050, NULL, 'asignacion_turnos', 'listar', '1'),
('LISTBANC', 'GESTBANC', 'CONTABILIDAD', '1', '0', '0', 0050, 'bancos', 'bancos', 'listar', '1'),
('LISTBARR', 'GESTBARR', 'ADMINISTRACION', '0', '0', '0', 0040, NULL, 'barrios', 'listar', '1'),
('LISTBITA', 'GESTBITA', 'ADMINISTRACION', '0', '0', '0', 0010, NULL, 'bitacora', 'listar', '1'),
('LISTCAPE', 'GESTCAPS', 'NOMINA', '0', '0', '0', 0008, 'causaciones_prestaciones_sociales', 'causaciones_prestaciones_sociales', 'listar_movimientos_empleado', '1'),
('LISTCMPE', 'GESTCAPS', 'NOMINA', '0', '0', '0', 0008, 'causaciones_prestaciones_sociales', 'causaciones_prestaciones_sociales', 'listar_movimientos_empresas', '1'),
('LISTCOPA', 'SUBMPRPL', 'NOMINA', '0', '0', '1', 0080, NULL, 'comprobante_pago', 'comprobante_pago', '2'),
('LISTCORR', 'GESTCORR', 'ADMINISTRACION', '0', '0', '0', 0040, NULL, 'corregimientos', 'listar', '1'),
('LISTCPPR', 'SUBMPRPL', 'NOMINA', '0', '0', '1', 0085, NULL, 'comprobante_pago_prima', 'comprobante_pago_prima', '2'),
('LISTCUBA', 'GESTCUBA', 'CONTABILIDAD', '1', '0', '0', 0050, 'cuentas_bancarias', 'cuentas_bancarias', 'listar', '1'),
('LISTDEEM', 'GESTDEEM', 'NOMINA', '1', '0', '0', 0040, 'departamentos_empresa', 'departamentos_empresa', 'listar', '1'),
('LISTDEPA', 'GESTDEPA', 'ADMINISTRACION', '0', '0', '0', 0050, NULL, 'departamentos', 'listar', '1'),
('LISTENPF', 'GESTENPF', 'NOMINA', '1', '0', '0', 0050, 'entidades_parafiscales', 'entidades_parafiscales', 'listar', '1'),
('LISTEXRE', 'GESTEXRE', 'NOMINA', '0', '0', '0', 0040, NULL, 'extras_recargos', 'listar', '1'),
('LISTINEM', 'GESTINEM', 'NOMINA', '0', '0', '0', 0050, NULL, 'empleados', 'listar', '1'),
('LISTMOPR', 'SUBMRENO', 'NOMINA', '0', '0', '1', 0400, NULL, 'lista_movimientos_prima', 'lista_movimientos_prima', '2'),
('LISTMUNI', 'GESTMUNI', 'ADMINISTRACION', '0', '0', '0', 0050, NULL, 'municipios', 'listar', '1'),
('LISTNMIG', 'GESTNMIG', 'NOMINA', '0', '0', '0', 0050, NULL, 'nomina_migracion', 'listar', '1'),
('LISTNOMA', 'GESTNOMA', 'NOMINA', '0', '0', '0', 0040, NULL, 'novedades_manuales', 'listar', '1'),
('LISTPAIS', 'GESTPAIS', 'ADMINISTRACION', '0', '0', '0', 0050, NULL, 'paises', 'listar', '1'),
('LISTPGPR', 'SUBMRENO', 'NOMINA', '0', '0', '1', 0200, NULL, 'lista_prestamo_empleado', 'lista_prestamo_empleado', '2'),
('LISTPLCO', 'GESTPLCO', 'CONTABILIDAD', '1', '0', '0', 0050, NULL, 'plan_contable', 'listar', '1'),
('LISTPLEM', 'SUBMPRPL', 'NOMINA', '0', '0', '1', 0070, NULL, 'lista_planilla_empleado', 'lista_planilla_empleado', '2'),
('LISTPTEM', 'GESTPTEM', 'NOMINA', '1', '0', '0', 0050, 'control_prestamos_empleados', 'prestamos_empleados', 'listar', '1'),
('LISTPTTE', 'GESTPTTE', 'NOMINA', '1', '0', '0', 0050, 'control_prestamos_terceros', 'prestamos_terceros', 'listar', '1'),
('LISTREIN', 'GESTREIN', 'NOMINA', '0', '0', '0', 0050, NULL, 'reporte_incapacidades', 'listar', '1'),
('LISTRENL', 'GESTRENL', 'NOMINA', '0', '0', '0', 0050, NULL, 'tiempos_no_laborados_dias', 'listar', '1'),
('LISTRETH', 'GESTRETH', 'NOMINA', '0', '0', '0', 0040, NULL, 'tiempos_no_laborados_horas', 'listar', '1'),
('LISTSCDE', 'GESTSCDE', 'NOMINA', '0', '0', '0', 0050, NULL, 'secciones_departamentos', 'listar', '1'),
('LISTTERC', 'GESTTERC', 'CONTABILIDAD', '1', '0', '0', 0025, 'terceros', 'terceros', 'listar', '1'),
('LISTTICT', 'GESTTICT', 'NOMINA', '0', '0', '0', 0050, NULL, 'tipos_contrato', 'listar', '1'),
('LISTTRCO', 'GESTTRCO', 'NOMINA', '0', '0', '0', 0040, NULL, 'transacciones_contables_empleado', 'listar', '1'),
('LISTTRTI', 'GESTTRTI', 'NOMINA', '0', '0', '0', 0040, NULL, 'transacciones_tiempo', 'listar', '1'),
('LISTTULA', 'GESTTULA', 'NOMINA', '0', '0', '0', 0040, NULL, 'turnos_laborales', 'listar', '1'),
('LISTVATO', 'GESTVATO', 'NOMINA', '0', '0', '0', 0050, NULL, 'liquidacion_vacaciones', 'listar', '1'),
('MENUADMI', NULL, 'ADMINISTRACION', '1', '0', '1', 1000, NULL, 'principal', NULL, '1'),
('MENUCLIE', NULL, 'ADMINISTRACION', '1', '0', '1', 0400, NULL, 'principal', NULL, '1'),
('MENUCONT', NULL, 'ADMINISTRACION', '1', '0', '1', 0900, NULL, 'principal', NULL, '1'),
('MENUFINS', NULL, 'ADMINISTRACION', '1', '1', '1', 1100, NULL, 'principal', 'finalizar', '1'),
('MENUINSE', NULL, 'ADMINISTRACION', '1', '1', '0', 0000, NULL, 'principal', 'iniciar', '1'),
('MENUINVE', NULL, 'ADMINISTRACION', '1', '0', '1', 0100, NULL, 'principal', NULL, '1'),
('MENUNOMI', NULL, 'ADMINISTRACION', '1', '0', '1', 0800, NULL, 'principal', NULL, '1'),
('MENUPRIN', NULL, 'ADMINISTRACION', '1', '1', '1', 0001, NULL, 'principal', 'principal', '1'),
('MENUPROV', NULL, 'ADMINISTRACION', '1', '0', '1', 0200, NULL, 'principal', NULL, '1'),
('MODIACDI', 'GESTACDI', 'CONTABILIDAD', '1', '0', '0', 0030, 'actividades_economicas_dian', 'actividades_economicas_dian', 'modificar', '1'),
('MODIAECO', 'GESTAECO', 'CONTABILIDAD', '1', '0', '0', 0030, 'actividades_economicas', 'actividades_economicas', 'modificar', '1'),
('MODIAFIC', 'GESTAFIC', 'NOMINA', '1', '0', '0', 0015, 'aficiones', 'aficiones', 'modificar', '1'),
('MODIANCO', 'GESTANCO', 'CONTABILIDAD', '1', '0', '0', 0015, 'anexos_contables', 'anexos_contables', 'modificar', '1'),
('MODIARTI', 'GESTARTI', 'INVENTARIO', '1', '0', '0', 0010, 'articulos', 'articulos', 'modificar', '1'),
('MODIASPI', 'GESTASPI', 'NOMINA', '0', '0', '0', 0030, NULL, 'aspirantes', 'modificar', '1'),
('MODIASTU', 'GESTASTU', 'NOMINA', '0', '0', '0', 0030, NULL, 'asignacion_turnos', 'modificar', '1'),
('MODIAUCO', 'GESTAUCO', 'CONTABILIDAD', '1', '0', '0', 0015, 'auxiliares_contables', 'auxiliares_contables', 'modificar', '1'),
('MODIAXTP', 'GESTAXTP', 'NOMINA', '0', '0', '0', 0030, NULL, 'auxilio_transporte', 'modificar', '1'),
('MODIBANC', 'GESTBANC', 'CONTABILIDAD', '1', '0', '0', 0030, 'bancos', 'bancos', 'modificar', '1'),
('MODIBARR', 'GESTBARR', 'ADMINISTRACION', '0', '0', '0', 0030, NULL, 'barrios', 'modificar', '1'),
('MODIBODE', 'GESTBODE', 'ADMINISTRACION', '0', '0', '0', 0040, NULL, 'bodegas', 'modificar', '1'),
('MODICAPR', 'GESTCAPR', 'PROVEEDORES', '1', '0', '0', 0030, 'cargos', 'cargos', 'modificar', '1'),
('MODICAPS', 'GESTCAPS', 'NOMINA', '0', '0', '0', 0008, 'causaciones_prestaciones_sociales', 'causaciones_prestaciones_sociales', 'modificar', '1'),
('MODICARG', 'GESTCARG', 'ADMINISTRACION', '0', '0', '0', 0020, NULL, 'cargos', 'modificar', '1'),
('MODICDCO', 'GESTCDCO', 'CONTABILIDAD', '1', '0', '0', 0030, 'conceptos_devolucion_compras', 'conceptos_devolucion_compras', 'modificar', '1'),
('MODICGPS', 'GESTCGPS', 'NOMINA', '0', '0', '0', 0030, NULL, 'gastos_prestaciones_sociales', 'modificar', '1'),
('MODICOCO', 'GESTCOCO', 'CONTABILIDAD', '1', '0', '0', 0030, NULL, 'conceptos_contabilizacion', 'modificar', '1'),
('MODICODI', 'GESTCODI', 'CONTABILIDAD', '1', '0', '0', 0015, 'conceptos_dian', 'conceptos_dian', 'modificar', '1'),
('MODICOPR', 'GESTCOPR', 'NOMINA', '0', '0', '0', 0030, NULL, 'conceptos_prestamos', 'modificar', '1');
INSERT INTO `job_componentes` (`id`, `padre`, `id_modulo`, `requiere_item`, `global`, `visible`, `orden`, `tabla_principal`, `carpeta`, `archivo`, `tipo_enlace`) VALUES 
('MODICORR', 'GESTCORR', 'ADMINISTRACION', '0', '0', '0', 0030, NULL, 'corregimientos', 'modificar', '1'),
('MODICUBA', 'GESTCUBA', 'CONTABILIDAD', '1', '0', '0', 0030, 'cuentas_bancarias', 'cuentas_bancarias', 'modificar', '1'),
('MODIDEEM', 'GESTDEEM', 'NOMINA', '1', '0', '0', 0030, 'departamentos_empresa', 'departamentos_empresa', 'modificar', '1'),
('MODIDEPA', 'GESTDEPA', 'ADMINISTRACION', '0', '0', '0', 0030, NULL, 'departamentos', 'modificar', '1'),
('MODIDEPO', 'GESTDEPO', 'NOMINA', '0', '0', '0', 0030, NULL, 'deportes', 'modificar', '1'),
('MODIDOFE', 'GESTDOFE', 'NOMINA', '0', '0', '0', 0030, NULL, 'dominicales_festivos', 'modificar', '1'),
('MODIEMPR', 'GESTEMPR', 'ADMINISTRACION', '0', '0', '0', 0020, NULL, 'empresas', 'modificar', '1'),
('MODIENPF', 'GESTENPF', 'NOMINA', '1', '0', '0', 0030, 'entidades_parafiscales', 'entidades_parafiscales', 'modificar', '1'),
('MODIESCO', 'GESTESCO', 'NOMINA', '0', '0', '0', 0030, NULL, 'escolaridad', 'modificar', '1'),
('MODIESGR', 'GESTESGR', 'INVENTARIO', '1', '0', '0', 0015, 'estructura_grupos', 'estructura_grupos', 'modificar', '1'),
('MODIESME', 'GESTESME', 'INVENTARIO', '1', '0', '0', 0015, 'estado_mercancia', 'estado_mercancia', 'modificar', '1'),
('MODIEXRE', 'GESTEXRE', 'NOMINA', '0', '0', '0', 0030, NULL, 'extras_recargos', 'modificar', '1'),
('MODIFEPL', 'GESTFEPL', 'NOMINA', '0', '0', '0', 0030, NULL, 'fechas_planillas', 'modificar', '1'),
('MODIFODI', 'GESTFODI', 'CONTABILIDAD', '1', '0', '0', 0015, 'formatos_dian', 'formatos_dian', 'modificar', '1'),
('MODIGRUP', 'GESTGRUP', 'INVENTARIO', '1', '0', '0', 0030, 'grupos', 'grupos', 'modificar', '1'),
('MODIIDIO', 'GESTIDIO', 'NOMINA', '0', '0', '0', 0030, NULL, 'idiomas', 'modificar', '1'),
('MODIINEM', 'GESTINEM', 'NOMINA', '0', '0', '0', 0030, NULL, 'empleados', 'modificar', '1'),
('MODILIEM', 'GESTLIEM', 'NOMINA', '0', '0', '0', 0008, 'liquidar_empleado', 'liquidar_empleado', 'modificar', '1'),
('MODIMARC', 'GESTMARC', 'INVENTARIO', '1', '0', '0', 0030, 'marcas', 'marcas', 'modificar', '1'),
('MODIMOCO', 'GESTMOCO', 'CONTABILIDAD', '1', '0', '0', 0015, NULL, 'movimientos_contables', 'modificar', '1'),
('MODIMOIN', 'GESTMOIN', 'NOMINA', '0', '0', '0', 0030, NULL, 'motivos_incapacidad', 'modificar', '1'),
('MODIMORE', 'GESTMORE', 'NOMINA', '0', '0', '0', 0030, NULL, 'motivos_retiro', 'modificar', '1'),
('MODIMUNI', 'GESTMUNI', 'ADMINISTRACION', '0', '0', '0', 0030, NULL, 'municipios', 'modificar', '1'),
('MODINMIG', 'GESTNMIG', 'NOMINA', '0', '0', '0', 0030, NULL, 'nomina_migracion', 'modificar', '1'),
('MODINOLA', 'GESTNOLA', 'NOMINA', '0', '0', '0', 0030, NULL, 'motivos_tiempos_no_laborar', 'modificar', '1'),
('MODINOMA', 'GESTNOMA', 'NOMINA', '0', '0', '0', 0030, NULL, 'novedades_manuales', 'modificar', '1'),
('MODIPAIS', 'GESTPAIS', 'ADMINISTRACION', '0', '0', '0', 0030, NULL, 'paises', 'modificar', '1'),
('MODIPECO', 'GESTPECO', 'CONTABILIDAD', '1', '0', '0', 0015, NULL, 'periodos_contables', 'modificar', '1'),
('MODIPERF', 'GESTPERF', 'ADMINISTRACION', '0', '0', '0', 0020, NULL, 'perfiles', 'modificar', '1'),
('MODIPLAN', 'GESTPLAN', 'NOMINA', '0', '0', '0', 0030, NULL, 'planillas', 'modificar', '1'),
('MODIPLCO', 'GESTPLCO', 'CONTABILIDAD', '1', '0', '0', 0030, NULL, 'plan_contable', 'modificar', '1'),
('MODIPPPR', 'GESTPPPR', 'PROVEEDORES', '1', '0', '0', 0030, 'plazos_pago_proveedores', 'plazos_pago_proveedores', 'modificar', '1'),
('MODIPREM', 'PREFEMPR', 'ADMINISTRACION', '0', '0', '0', 0020, NULL, 'preferencias_empresas', 'modificar', '1'),
('MODIPRIV', 'GESTPRIV', 'ADMINISTRACION', '1', '0', '0', 0040, NULL, 'privilegios', 'modificar', '1'),
('MODIPRMA', 'GESTPRMA', 'PROVEEDORES', '1', '0', '0', 0030, 'proveedores_marcas', 'proveedores_marcas', 'modificar', '1'),
('MODIPROF', 'GESTPROF', 'ADMINISTRACION', '1', '0', '0', 0040, NULL, 'profesiones_oficios', 'modificar', '1'),
('MODIPROV', 'GESTPROV', 'PROVEEDORES', '1', '0', '0', 0030, 'proveedores', 'proveedores', 'modificar', '1'),
('MODIPRSU', 'PREFSUCU', 'ADMINISTRACION', '1', '0', '0', 0300, NULL, 'preferencias_sucursales', 'modificar', '1'),
('MODIPRUS', 'PREFUSUA', 'ADMINISTRACION', '1', '0', '0', 0300, NULL, 'preferencias_usuarios', 'modificar', '1'),
('MODIPTEM', 'GESTPTEM', 'NOMINA', '1', '0', '0', 0030, 'control_prestamos_empleados', 'prestamos_empleados', 'modificar', '1'),
('MODIPTTE', 'GESTPTTE', 'NOMINA', '1', '0', '0', 0030, 'control_prestamos_terceros', 'prestamos_terceros', 'modificar', '1'),
('MODIRECE', 'SUBMPRSO', 'NOMINA', '0', '0', '0', 0008, 'movimiento_retiro_cesantias', 'retiro_cesantias', 'modificar', '1'),
('MODIREDI', 'GESTREDI', 'CONTABILIDAD', '1', '0', '0', 0030, 'resoluciones_dian', 'resoluciones_dian', 'modificar', '1'),
('MODIREGC', 'GESTREGC', 'CONTABILIDAD', '1', '0', '0', 0015, 'resoluciones_gran_contribuyente', 'resoluciones_gran_contribuyente', 'modificar', '1'),
('MODIREIC', 'GESTREIC', 'CONTABILIDAD', '1', '0', '0', 0015, 'resoluciones_ica', 'resoluciones_ica', 'modificar', '1'),
('MODIREIN', 'GESTREIN', 'NOMINA', '0', '0', '0', 0030, NULL, 'reporte_incapacidades', 'modificar', '1'),
('MODIRENL', 'GESTRENL', 'NOMINA', '0', '0', '0', 0030, NULL, 'tiempos_no_laborados_dias', 'modificar', '1'),
('MODIRERF', 'GESTRERF', 'CONTABILIDAD', '1', '0', '0', 0015, 'resoluciones_retefuente', 'resoluciones_retefuente', 'modificar', '1'),
('MODIRETH', 'GESTRETH', 'NOMINA', '0', '0', '0', 0030, NULL, 'tiempos_no_laborados_horas', 'modificar', '1'),
('MODISCDE', 'GESTSCDE', 'NOMINA', '0', '0', '0', 0030, NULL, 'secciones_departamentos', 'modificar', '1'),
('MODISECB', 'GESTSECB', 'ADMINISTRACION', '1', '0', '0', 0040, NULL, 'secciones', 'modificar', '1'),
('MODISECC', 'GESTSECB', 'ADMINISTRACION', '1', '0', '0', 0015, NULL, 'secciones', 'modificar', '1'),
('MODISERV', 'GESTSERV', 'PROVEEDORES', '1', '0', '0', 0030, 'servicios', 'servicios', 'modificar', '1'),
('MODISMLV', 'GESTSMLV', 'NOMINA', '0', '0', '0', 0030, NULL, 'salario_minimo', 'modificar', '1'),
('MODISRVD', 'GESTSRVD', 'ADMINISTRACION', '1', '0', '0', 0040, NULL, 'servidores', 'modificar', '1'),
('MODISUCU', 'GESTSUCU', 'ADMINISTRACION', '1', '0', '0', 0040, NULL, 'sucursales', 'modificar', '1'),
('MODITABL', 'GESTTABL', 'ADMINISTRACION', '1', '0', '0', 0040, NULL, 'tablas', 'modificar', '1'),
('MODITASA', 'GESTTASA', 'CONTABILIDAD', '1', '0', '0', 0015, 'tasas', 'tasas', 'modificar', '1'),
('MODITCOM', 'GESTTCOM', 'CONTABILIDAD', '1', '0', '0', 0030, 'tipos_compra', 'tipos_compra', 'modificar', '1'),
('MODITDVC', 'GESTTDVC', 'CONTABILIDAD', '1', '0', '0', 0030, 'tipos_devoluciones_compra', 'tipos_devoluciones_compra', 'modificar', '1'),
('MODITERC', 'GESTTERC', 'CONTABILIDAD', '1', '0', '0', 0015, 'terceros', 'terceros', 'modificar', '1'),
('MODITERM', 'GESTTERM', 'ADMINISTRACION', '1', '0', '0', 0040, NULL, 'terminales', 'modificar', '1'),
('MODITIBO', 'GESTTIBO', 'ADMINISTRACION', '1', '0', '0', 0040, NULL, 'tipos_bodegas', 'modificar', '1'),
('MODITICO', 'GESTTICO', 'CONTABILIDAD', '1', '0', '0', 0015, 'tipos_comprobantes', 'tipos_comprobantes', 'modificar', '1'),
('MODITICT', 'GESTTICT', 'NOMINA', '0', '0', '0', 0030, NULL, 'tipos_contrato', 'modificar', '1'),
('MODITIDB', 'GESTTIDB', 'CONTABILIDAD', '1', '0', '0', 0015, 'tipos_documentos_bancarios', 'tipos_documentos_bancarios', 'modificar', '1'),
('MODITIDI', 'GESTTIDI', 'ADMINISTRACION', '1', '0', '0', 0040, NULL, 'tipos_documento_identidad', 'modificar', '1'),
('MODITIDO', 'GESTTIDO', 'CONTABILIDAD', '1', '0', '0', 0015, 'tipos_documentos', 'tipos_documentos', 'modificar', '1'),
('MODITIMO', 'GESTTIMO', 'CONTABILIDAD', '1', '0', '0', 0015, 'tipos_moneda', 'tipos_moneda', 'modificar', '1'),
('MODITIUN', 'GESTTIUN', 'INVENTARIO', '1', '0', '0', 0030, 'tipos_unidades', 'tipos_unidades', 'modificar', '1'),
('MODITRCO', 'GESTTRCO', 'NOMINA', '0', '0', '0', 0030, NULL, 'transacciones_contables_empleado', 'modificar', '1'),
('MODITRTI', 'GESTTRTI', 'NOMINA', '0', '0', '0', 0030, NULL, 'transacciones_tiempo', 'modificar', '1'),
('MODITULA', 'GESTTULA', 'NOMINA', '0', '0', '0', 0030, NULL, 'turnos_laborales', 'modificar', '1'),
('MODIUNID', 'GESTUNID', 'INVENTARIO', '1', '0', '0', 0030, 'unidades', 'unidades', 'modificar', '1'),
('MODIUSUA', 'GESTUSUA', 'ADMINISTRACION', '1', '0', '0', 0040, NULL, 'usuarios', 'modificar', '1'),
('MODIVATO', 'GESTVATO', 'NOMINA', '0', '0', '0', 0030, NULL, 'liquidacion_vacaciones', 'modificar', '1'),
('MODIVITA', 'GESTVITA', 'CONTABILIDAD', '1', '0', '0', 0015, 'vigencia_tasas', 'vigencia_tasas', 'modificar', '1'),
('PAGAPLAN', 'SUBMPRPL', 'NOMINA', '0', '0', '1', 0050, NULL, 'contabilizar_planilla', 'pargar_planilla', '2'),
('PREFEMPR', 'GESTPREF', 'ADMINISTRACION', '0', '0', '1', 0200, NULL, 'preferencias_empresas', 'menu', '1'),
('PREFGLOB', 'GESTPREF', 'ADMINISTRACION', '1', '0', '1', 0100, NULL, 'preferencias_globales', 'modificar', '2'),
('PREFSUCU', 'GESTPREF', 'ADMINISTRACION', '1', '0', '1', 0300, NULL, 'preferencias_sucursales', 'menu', '1'),
('PREFUSUA', 'GESTPREF', 'ADMINISTRACION', '1', '0', '1', 0400, NULL, 'preferencias_usuarios', 'menu', '1'),
('REPOBACO', 'SUBMBALC', 'CONTABILIDAD', '0', '0', '1', 0015, '', 'balances', 'comprobacion', '2'),
('REPOBALC', 'SUBMBALC', 'CONTABILIDAD', '0', '0', '1', 0005, '', 'balances', 'general', '2'),
('REPOBPYG', 'SUBMBALC', 'CONTABILIDAD', '0', '0', '1', 0010, '', 'balances', 'pyg', '2'),
('REPODIMC', 'SUBMMOVI', 'CONTABILIDAD', '0', '0', '1', 0020, '', 'movimientos_contables', 'diario_comprobante', '2'),
('REPODIMD', 'SUBMMOVI', 'CONTABILIDAD', '0', '0', '1', 0010, '', 'movimientos_contables', 'diario_documento', '2'),
('REPOESCU', 'SUBMBALC', 'CONTABILIDAD', '0', '0', '1', 0020, '', 'balances', 'estado_cuenta', '2'),
('REPOMOCO', 'GESTMOCO', 'CONTABILIDAD', '1', '0', '0', 0005, NULL, 'movimientos_contables', 'reportes', '1'),
('REPOSCXC', 'SUBMSADO', 'CONTABILIDAD', '0', '0', '1', 0010, '', 'saldos', 'cxc', '2'),
('REPOSCXP', 'SUBMSADO', 'CONTABILIDAD', '0', '0', '1', 0020, '', 'saldos', 'cxp', '2'),
('REPOSMDO', 'SUBMSADO', 'CONTABILIDAD', '0', '0', '1', 0030, '', 'saldos', 'movimiento_documentos', '2'),
('RETIINEM', 'GESTINEM', 'NOMINA', '0', '0', '0', 0040, NULL, 'empleados', 'retirar', '1'),
('SUBMACCE', 'MENUADMI', 'ADMINISTRACION', '1', '0', '1', 0200, NULL, 'principal', NULL, '1'),
('SUBMBALC', 'MENUCONT', 'CONTABILIDAD', '1', '0', '1', 1000, NULL, 'principal', NULL, '1'),
('SUBMCOMP', 'MENUPROV', 'PROVEEDORES', '1', '0', '1', 1000, NULL, 'principal', NULL, '1'),
('SUBMCUXP', 'MENUPROV', 'PROVEEDORES', '1', '0', '1', 3000, NULL, 'principal', NULL, '1'),
('SUBMDCAD', 'MENUADMI', 'ADMINISTRACION', '1', '0', '1', 0500, NULL, 'principal', NULL, '1'),
('SUBMDCCO', 'MENUCONT', 'CONTABILIDAD', '1', '0', '1', 9000, NULL, 'principal', NULL, '1'),
('SUBMDCIN', 'MENUINVE', 'INVENTARIO', '1', '0', '1', 5000, NULL, 'principal', NULL, '1'),
('SUBMDCPV', 'MENUPROV', 'PROVEEDORES', '1', '0', '1', 5000, NULL, 'principal', NULL, '1'),
('SUBMDCRH', 'MENUNOMI', 'NOMINA', '1', '0', '1', 9000, NULL, 'principal', NULL, '1'),
('SUBMDISP', 'MENUADMI', 'ADMINISTRACION', '1', '0', '1', 0300, NULL, 'principal', NULL, '1'),
('SUBMESTC', 'MENUADMI', 'ADMINISTRACION', '1', '0', '1', 0100, NULL, 'principal', NULL, '1'),
('SUBMFINA', 'SUBMDCCO', 'CONTABILIDAD', '1', '0', '1', 0025, NULL, 'principal', NULL, '1'),
('SUBMICPR', 'SUBMDCCO', 'CONTABILIDAD', '1', '0', '1', 0010, NULL, 'principal', NULL, '1'),
('SUBMINCL', 'SUBMDCCO', 'CONTABILIDAD', '1', '0', '1', 0015, NULL, 'principal', NULL, '1'),
('SUBMINCO', 'SUBMDCCO', 'CONTABILIDAD', '1', '0', '1', 0005, NULL, 'principal', NULL, '1'),
('SUBMINTR', 'SUBMDCCO', 'CONTABILIDAD', '1', '0', '1', 0020, NULL, 'principal', NULL, '1'),
('SUBMMOVI', 'MENUCONT', 'CONTABILIDAD', '1', '0', '1', 2000, NULL, 'principal', NULL, '1'),
('SUBMNOMI', 'MENUNOMI', 'NOMINA', '1', '0', '1', 0100, NULL, 'principal', NULL, '1'),
('SUBMOPCO', 'MENUCONT', 'CONTABILIDAD', '1', '0', '1', 0500, NULL, 'principal', NULL, '1'),
('SUBMOPER', 'MENUINVE', 'INVENTARIO', '1', '0', '1', 4000, NULL, 'principal', NULL, '1'),
('SUBMPAGO', 'SUBMPRPL', 'NOMINA', '1', '0', '1', 0030, NULL, 'contabilizar_planilla', NULL, '1'),
('SUBMPENO', 'MENUNOMI', 'NOMINA', '1', '0', '1', 0280, NULL, 'principal', NULL, '1'),
('SUBMPRAN', 'MENUNOMI', 'NOMINA', '1', '0', '1', 0300, NULL, 'principal', NULL, '1'),
('SUBMPRPL', 'MENUNOMI', 'NOMINA', '1', '0', '1', 0200, NULL, 'principal', NULL, '1'),
('SUBMPRSO', 'SUBMNOMI', 'NOMINA', '1', '0', '1', 0060, NULL, 'retiro_cesantias', NULL, '1'),
('SUBMRENO', 'MENUNOMI', 'NOMINA', '1', '0', '1', 0250, NULL, 'principal', NULL, '1'),
('SUBMRETL', 'SUBMNOMI', 'NOMINA', '1', '0', '1', 0020, NULL, 'tiempos_no_laborados_dias', NULL, '1'),
('SUBMSADO', 'MENUCONT', 'CONTABILIDAD', '1', '0', '1', 1500, NULL, 'principal', NULL, '1'),
('SUBMSEGU', 'MENUADMI', 'ADMINISTRACION', '1', '0', '1', 0400, NULL, 'principal', NULL, '1'),
('SUBMTASA', 'SUBMDCCO', 'CONTABILIDAD', '1', '0', '1', 0030, NULL, 'principal', NULL, '1'),
('SUBMUBIG', 'SUBMDCAD', 'ADMINISTRACION', '1', '0', '1', 0600, NULL, 'principal', NULL, '1'),
('VISUIMAG', NULL, 'ADMINISTRACION', '1', '1', '0', 0500, NULL, 'principal', 'visualizar', '1');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_componentes_perfil`
-- 

DROP TABLE IF EXISTS `job_componentes_perfil`;
CREATE TABLE `job_componentes_perfil` (
  `id_perfil` smallint(4) unsigned zerofill NOT NULL COMMENT 'Consecutivo interno para la base de datos del perfil',
  `id_componente` varchar(8) collate latin1_spanish_ci NOT NULL COMMENT 'Identificador del componente',
  PRIMARY KEY  (`id_perfil`,`id_componente`),
  KEY `componente_perfil_componente` (`id_componente`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_componentes_perfil`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_componentes_usuario`
-- 

DROP TABLE IF EXISTS `job_componentes_usuario`;
CREATE TABLE `job_componentes_usuario` (
  `id` int(8) unsigned zerofill NOT NULL auto_increment COMMENT 'Consecutivo interno de la base de datos',
  `id_perfil` int(8) unsigned zerofill NOT NULL COMMENT 'Identificador de la tabla perfil usuario',
  `id_componente` varchar(8) collate latin1_spanish_ci NOT NULL COMMENT 'Identificador del componente',
  PRIMARY KEY  (`id`,`id_perfil`,`id_componente`),
  KEY `componente_usuario_perfil` (`id_perfil`),
  KEY `componente_usuario_componente` (`id_componente`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1 ;

-- 
-- Volcar la base de datos para la tabla `job_componentes_usuario`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_conceptos_contabilizacion_compras`
-- 

DROP TABLE IF EXISTS `job_conceptos_contabilizacion_compras`;
CREATE TABLE `job_conceptos_contabilizacion_compras` (
  `codigo` smallint(4) unsigned zerofill NOT NULL COMMENT 'Consecutivo interno para la base de datos',
  `descripcion` varchar(255) collate latin1_spanish_ci default NULL COMMENT 'Descripcion del concepto',
  `regimen_ventas_empresa` enum('1','2') collate latin1_spanish_ci NOT NULL default '1' COMMENT '1 Común - 2 Simplificado',
  `regimen_persona` enum('1','2') collate latin1_spanish_ci NOT NULL default '1' COMMENT '1 Común - 2 Simplificado',
  `codigo_tipo_compra` smallint(4) unsigned zerofill NOT NULL COMMENT 'código según la tabla de tipo de compra',
  `codigo_tasa_iva` smallint(3) unsigned zerofill NOT NULL COMMENT 'código según la tabla de tipo de compra',
  `codigo_contable_compras` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'Id Código contable compras: código del plan contable para compras',
  `codigo_contable_iva` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'Código contable iva: Código del plan contable para el iva (opcional)',
  `codigo_contable_iva_debito` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'Código contable iva: Código del plan contable para el iva teorico debito (opcional)',
  `codigo_contable_iva_credito` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'Código contable iva: Código del plan contable para el iva teorico credito (opcional)',
  `codigo_contable_compras_uvt` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'Id Código contable compras: código del plan contable para compras de UVT',
  `codigo_contable_iva_uvt` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'Código contable iva: Código del plan contable para el iva de UVT',
  PRIMARY KEY  (`codigo`),
  UNIQUE KEY `descripcion` (`descripcion`),
  KEY `tipo_compra` (`codigo_tipo_compra`),
  KEY `tasa_iva` (`codigo_tasa_iva`),
  KEY `llave_codigo_contable_iva` (`codigo_contable_iva`),
  KEY `llave_codigo_contable_compras` (`codigo_contable_compras`),
  KEY `concepto_contabilizacion_id_cuenta_iva_debito` (`codigo_contable_iva_debito`),
  KEY `concepto_contabilizacion_id_cuenta_iva_credito` (`codigo_contable_iva_credito`),
  KEY `llave_codigo_contable_iva_uvt` (`codigo_contable_iva_uvt`),
  KEY `llave_codigo_contable_compras_uvt` (`codigo_contable_compras_uvt`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_conceptos_contabilizacion_compras`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_conceptos_devolucion_compras`
-- 

DROP TABLE IF EXISTS `job_conceptos_devolucion_compras`;
CREATE TABLE `job_conceptos_devolucion_compras` (
  `codigo` smallint(4) unsigned zerofill NOT NULL COMMENT 'Consecutivo interno para la base de datos',
  `descripcion` varchar(255) collate latin1_spanish_ci default NULL COMMENT 'Descripcion del concepto',
  `regimen_ventas_empresa` enum('1','2') collate latin1_spanish_ci NOT NULL default '1' COMMENT 'Regimen empresa: 1 Comun - 2 Simplificado',
  `regimen_persona` enum('1','2') collate latin1_spanish_ci NOT NULL default '1' COMMENT 'Regimen persona: 1 Comun - 2 Simplificado',
  `codigo_tipo_devolucion` smallint(4) unsigned zerofill NOT NULL COMMENT 'codigo segun la tabla de tipo de devoluciones',
  `codigo_tasa_iva` smallint(3) unsigned zerofill NOT NULL COMMENT 'codigo segun la tabla de tipo de compra',
  `codigo_contable_compras` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo contable compras: codigo del plan contable',
  `codigo_contable_iva` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo contable iva    : Codigo del plan contable',
  PRIMARY KEY  (`codigo`),
  KEY `conceptos_devolucion_tipo_compra` (`codigo_tipo_devolucion`),
  KEY `conceptos_devolucion_tasa_iva` (`codigo_tasa_iva`),
  KEY `conceptos_devolucion_iva` (`codigo_contable_iva`),
  KEY `conceptos_devolucion_compras` (`codigo_contable_compras`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_conceptos_devolucion_compras`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_conceptos_dian`
-- 

DROP TABLE IF EXISTS `job_conceptos_dian`;
CREATE TABLE `job_conceptos_dian` (
  `codigo` smallint(4) unsigned zerofill NOT NULL COMMENT 'Codigo DIAN',
  `codigo_formato_dian` smallint(4) unsigned zerofill NOT NULL COMMENT 'Codigo de la tabla de formatos_dian',
  `descripcion` varchar(255) collate latin1_spanish_ci NOT NULL COMMENT 'Detalle que describe el anexo contable',
  `valor_base` decimal(17,4) unsigned default NULL COMMENT 'Valos base sobre el cual se va a separar la informacion',
  `valor_a_informar` enum('1','2','3') collate latin1_spanish_ci NOT NULL COMMENT '1-> Saldos a fin de año 2->Solo acumulado del año según sentido de la cuenta 3->Acumulado Db y Cr según sentido de la cuenta',
  `identificacion_valores_mayores` int(15) NOT NULL COMMENT 'Documento de identidad requerido por la DIAN para los valores de menores cuantías al valor base acumulados',
  `concepto_razon_social` varchar(255) collate latin1_spanish_ci NOT NULL COMMENT 'Concepto que debe describirse para las menores cuantí­as acumuladas requerido por la DIAN',
  `tipo_documento` smallint(4) NOT NULL COMMENT 'Tipo de documento que debe reportarse para las menores cuantí­as acumuladas requerido por la DIAN',
  PRIMARY KEY  (`codigo`),
  KEY `concepto_formato_dian` (`codigo_formato_dian`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_conceptos_dian`
-- 

INSERT INTO `job_conceptos_dian` (`codigo`, `codigo_formato_dian`, `descripcion`, `valor_base`, `valor_a_informar`, `identificacion_valores_mayores`, `concepto_razon_social`, `tipo_documento`) VALUES 
(0000, 0000, '', 0.0000, '1', 0, '', 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_conceptos_prestamos`
-- 

DROP TABLE IF EXISTS `job_conceptos_prestamos`;
CREATE TABLE `job_conceptos_prestamos` (
  `codigo` smallint(4) unsigned zerofill NOT NULL COMMENT 'Código que identifica el concepto de prestamo',
  `descripcion` varchar(255) collate latin1_spanish_ci NOT NULL COMMENT 'Detalle que describe el concepto del prestamo',
  PRIMARY KEY  (`codigo`),
  UNIQUE KEY `descripcion` (`descripcion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_conceptos_prestamos`
-- 

INSERT INTO `job_conceptos_prestamos` (`codigo`, `descripcion`) VALUES 
(0000, '');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_conceptos_resoluciones_dian`
-- 

DROP TABLE IF EXISTS `job_conceptos_resoluciones_dian`;
CREATE TABLE `job_conceptos_resoluciones_dian` (
  `codigo` int(8) unsigned zerofill NOT NULL auto_increment COMMENT 'Consecutivo interno de la base de datos',
  `nombre` varchar(255) collate latin1_spanish_ci NOT NULL COMMENT 'Nombre del concepto',
  PRIMARY KEY  (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=7 ;

-- 
-- Volcar la base de datos para la tabla `job_conceptos_resoluciones_dian`
-- 

INSERT INTO `job_conceptos_resoluciones_dian` (`codigo`, `nombre`) VALUES 
(00000001, 'Factura por computador'),
(00000002, 'Factura POS'),
(00000003, 'Factura en papel'),
(00000004, 'Factura electrónica'),
(00000005, ''),
(00000006, '');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_conceptos_transacciones_contables_empleado`
-- 

DROP TABLE IF EXISTS `job_conceptos_transacciones_contables_empleado`;
CREATE TABLE `job_conceptos_transacciones_contables_empleado` (
  `codigo` smallint(4) unsigned zerofill NOT NULL COMMENT 'Código interno que identifica el concepto de transacción contable',
  `descripcion` varchar(255) collate latin1_spanish_ci NOT NULL COMMENT 'Descripción del concepto contable',
  PRIMARY KEY  (`codigo`),
  UNIQUE KEY `descripcion` (`descripcion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_conceptos_transacciones_contables_empleado`
-- 

INSERT INTO `job_conceptos_transacciones_contables_empleado` (`codigo`, `descripcion`) VALUES 
(0008, 'Aporte pensión voluntaria'),
(0007, 'Aporte solidaridad pensión'),
(0003, 'Auxilio-transporte'),
(0038, 'Auxilios'),
(0010, 'Bancaria'),
(0044, 'Cancelación nomina por pagar pensión'),
(0041, 'Cancelación nomina por pagar salud'),
(0015, 'Cesantía causación prestación'),
(0013, 'Cesantía pago gasto'),
(0012, 'Cesantía pago prestación'),
(0014, 'Cesantía traslado fondo'),
(0016, 'Cesantí­a causación gasto'),
(0045, 'Cuenta por pagar pensión'),
(0042, 'Cuenta por pagar salud'),
(0011, 'Descuentos empleados de terceros'),
(0035, 'Incapacidad atep'),
(0032, 'Incapacidad general ambulatoria'),
(0033, 'Incapacidad general prorroga'),
(0034, 'Incapacidad hospitalaria'),
(0031, 'Incapacidad tres dí­as'),
(0039, 'Indemnización'),
(0020, 'Intereses causación gasto'),
(0019, 'Intereses causación prestación'),
(0018, 'Intereses pago gasto'),
(0017, 'Intereses pago prestación'),
(0029, 'Licencia maternidad'),
(0030, 'Licencia paternidad'),
(0006, 'Nomina por pagar pensión'),
(0005, 'Nomina por pagar salud'),
(0002, 'Otros-salario'),
(0046, 'Pago a entidad de pensión'),
(0043, 'Pago a entidad de salud'),
(0040, 'Prestamos de terceros'),
(0009, 'Prestamos empresa'),
(0024, 'Prima causación gasto'),
(0023, 'Prima causación prestación'),
(0022, 'Prima pago gasto'),
(0021, 'Prima pago prestación'),
(0037, 'Retención en la fuente'),
(0001, 'Salario'),
(0004, 'Solo-contable'),
(0036, 'Unidad capitación'),
(0028, 'Vacación causación gasto'),
(0027, 'Vacación causación prestación'),
(0026, 'Vacación pago gasto'),
(0025, 'Vacación pago prestación');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_conceptos_transacciones_tiempo`
-- 

DROP TABLE IF EXISTS `job_conceptos_transacciones_tiempo`;
CREATE TABLE `job_conceptos_transacciones_tiempo` (
  `codigo` smallint(3) unsigned zerofill NOT NULL COMMENT 'cÃ³digo interno que identifica la transacciÃ³n de tiempo',
  `descripcion` varchar(255) collate latin1_spanish_ci NOT NULL COMMENT 'Descripcion del concepto contable',
  `tipo` enum('1','2','3','4','5') collate latin1_spanish_ci NOT NULL default '1' COMMENT '1-> Horas laborales 2-> Licencias-ausencias-permisos no remunerados 3-> Incapacidades 4-> Licencias-ausencias-permisos remunerados 5-> Vacaciones',
  PRIMARY KEY  (`codigo`),
  UNIQUE KEY `descripcion` (`descripcion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_conceptos_transacciones_tiempo`
-- 

INSERT INTO `job_conceptos_transacciones_tiempo` (`codigo`, `descripcion`, `tipo`) VALUES 
(001, 'Horas Normales', '1'),
(002, 'Horas Extras', '1'),
(003, 'Recargos - Nocturnos', '1'),
(004, 'Dominical - Festivos', '1'),
(005, 'Extras - Nocturnas', '1'),
(006, 'Extras-festivos', '1'),
(007, 'Extras-festivas-nocturnas', '1'),
(008, 'Festivo-nocturno', '1'),
(009, 'Licencias', '2'),
(010, 'Suspensiones', '2'),
(011, 'Ausencias', '2'),
(012, 'Permiso-remunerado', '2'),
(013, 'Vacaciones', '5'),
(014, 'Incapacidad-tres-días', '3'),
(015, 'Incapacidad-general-ambulatoria', '3'),
(016, 'Incapacidad-general-prorroga hasta 89 dias', '3'),
(017, 'Incapacidad-general-prorroga hasta 179 dias', '3'),
(018, 'Incapacidad-general-prorroga mayor o igual a 180 dias', '3'),
(019, 'Incapacidad-hospitalaria', '3');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_conexiones`
-- 

DROP TABLE IF EXISTS `job_conexiones`;
CREATE TABLE `job_conexiones` (
  `codigo_sucursal` mediumint(5) unsigned zerofill NOT NULL COMMENT 'Codigo de la sucursal',
  `codigo_usuario` smallint(4) unsigned zerofill NOT NULL COMMENT 'Consecutivo interno para la base de datos del usuario que realiza la conexiÃ³n',
  `fecha` datetime NOT NULL COMMENT 'Fecha y hora de la conexiÃ³n',
  `ip` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'DirecciÃ³n IP desde la cual se realiza la conexiÃ³n',
  `proxy` varchar(15) collate latin1_spanish_ci default NULL COMMENT 'DirecciÃ³n IP del proxy, si lo hay, desde el cual se realiza la conexiÃ³n',
  `navegador` varchar(255) collate latin1_spanish_ci default NULL COMMENT 'IdentificaciÃ³n del navegador',
  `sistema` varchar(255) collate latin1_spanish_ci default NULL COMMENT 'Sistema operativo del cliente',
  PRIMARY KEY  (`codigo_sucursal`,`codigo_usuario`,`fecha`),
  KEY `conexion_usuario` (`codigo_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_conexiones`
-- 

INSERT INTO `job_conexiones` (`codigo_sucursal`, `codigo_usuario`, `fecha`, `ip`, `proxy`, `navegador`, `sistema`) VALUES 
(00000, 0000, '2020-06-04 13:36:14', '127.0.0.1', NULL, NULL, NULL),
(00000, 0000, '2020-06-04 14:23:31', '127.0.0.1', NULL, NULL, NULL),
(00000, 0000, '2020-06-04 16:22:11', '127.0.0.1', NULL, NULL, NULL),
(00000, 0000, '2020-06-04 17:10:56', '127.0.0.1', NULL, NULL, NULL),
(00000, 0000, '2020-06-04 20:04:53', '127.0.0.1', NULL, NULL, NULL),
(00000, 0000, '2020-06-04 20:29:18', '127.0.0.1', NULL, NULL, NULL);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_consecutivo_cheques`
-- 

DROP TABLE IF EXISTS `job_consecutivo_cheques`;
CREATE TABLE `job_consecutivo_cheques` (
  `codigo_sucursal` mediumint(5) unsigned zerofill NOT NULL COMMENT 'Codigo de la sucursal a la que pertenece la cuenta',
  `codigo_tipo_documento` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo del tipo de documento',
  `codigo_banco` smallint(2) unsigned zerofill NOT NULL COMMENT 'id de la tabla bancos',
  `numero` varchar(30) collate latin1_spanish_ci NOT NULL COMMENT 'Numero de la cuenta',
  `consecutivo` int(9) unsigned zerofill NOT NULL COMMENT 'Numero de cheque',
  `codigo_sucursal_cuenta` mediumint(5) unsigned zerofill NOT NULL COMMENT 'Codigo de la sucursal a la que pertenece la cuenta',
  `codigo_tipo_documento_cuenta` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo del tipo de documento',
  `codigo_sucursal_banco` smallint(2) unsigned zerofill NOT NULL COMMENT 'codigo de la tabla bancos',
  `codigo_iso_cuenta` varchar(2) collate latin1_spanish_ci NOT NULL COMMENT 'Llave principal de la tabla paises',
  `codigo_dane_departamento_cuenta` varchar(2) collate latin1_spanish_ci NOT NULL COMMENT 'Código DANE',
  `codigo_dane_municipio_cuenta` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'Código DANE',
  `codigo_banco_cuenta` smallint(2) unsigned zerofill NOT NULL COMMENT 'id de la tabla bancos',
  `numero_cuenta` varchar(30) collate latin1_spanish_ci NOT NULL COMMENT 'Numero de la cuenta',
  `id_tabla` smallint(5) unsigned NOT NULL COMMENT 'id de la tabla que genera el cheque',
  `llave_tabla` varchar(500) collate latin1_spanish_ci NOT NULL default '' COMMENT 'Llave del registro de la tabla que genero el cheque',
  PRIMARY KEY  (`codigo_sucursal`,`codigo_tipo_documento`,`codigo_banco`,`numero`,`consecutivo`),
  KEY `consecutivo_cheques_cuentas_bancarias` (`codigo_sucursal_cuenta`,`codigo_tipo_documento_cuenta`,`codigo_sucursal_banco`,`codigo_iso_cuenta`,`codigo_dane_departamento_cuenta`,`codigo_dane_municipio_cuenta`,`codigo_banco_cuenta`,`numero_cuenta`),
  KEY `consecutivo_cheques_tablas` (`id_tabla`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_consecutivo_cheques`
-- 

INSERT INTO `job_consecutivo_cheques` (`codigo_sucursal`, `codigo_tipo_documento`, `codigo_banco`, `numero`, `consecutivo`, `codigo_sucursal_cuenta`, `codigo_tipo_documento_cuenta`, `codigo_sucursal_banco`, `codigo_iso_cuenta`, `codigo_dane_departamento_cuenta`, `codigo_dane_municipio_cuenta`, `codigo_banco_cuenta`, `numero_cuenta`, `id_tabla`, `llave_tabla`) VALUES 
(00000, 000, 00, '', 000000000, 00000, 000, 00, '', '', '', 00, '', 0, '');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_consecutivo_documentos`
-- 

DROP TABLE IF EXISTS `job_consecutivo_documentos`;
CREATE TABLE `job_consecutivo_documentos` (
  `codigo_sucursal` mediumint(5) unsigned zerofill NOT NULL COMMENT 'Código de la sucursal a la cual pertenece',
  `codigo_tipo_documento` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo del tipo de documento',
  `documento_identidad_tercero` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Número del documento de identidad',
  `fecha_registro` date NOT NULL COMMENT 'Fecha de generacion del documento',
  `consecutivo` int(8) unsigned NOT NULL COMMENT 'Numero consecutivo',
  `id_tabla` smallint(5) unsigned NOT NULL COMMENT 'id de la tabla que genera el documento',
  `llave_tabla` varchar(255) collate latin1_spanish_ci NOT NULL COMMENT 'Llave de tabla que genero el documento',
  `codigo_sucursal_archivo` mediumint(5) unsigned zerofill NOT NULL COMMENT 'Código de la sucursal que genera el archivo',
  `consecutivo_archivo` int(9) unsigned zerofill NOT NULL COMMENT 'Consecutivo interno para la base de datos del archivo',
  PRIMARY KEY  (`codigo_sucursal`,`codigo_tipo_documento`,`documento_identidad_tercero`,`fecha_registro`,`consecutivo`),
  KEY `consecutivo_documentos_tipos_documentos` (`codigo_tipo_documento`),
  KEY `consecutivo_documentos_tablas` (`id_tabla`),
  KEY `consecutivo_documentos_archivos` (`codigo_sucursal_archivo`,`consecutivo_archivo`),
  KEY `consecutivo_documentos_tercero` (`documento_identidad_tercero`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_consecutivo_documentos`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_contrato_empleados`
-- 

DROP TABLE IF EXISTS `job_contrato_empleados`;
CREATE TABLE `job_contrato_empleados` (
  `codigo_empresa` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa donde va a laborar',
  `documento_identidad_empleado` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'CÃ³digo interno que identifica el empleado en terceras personas',
  `fecha_ingreso` date NOT NULL COMMENT 'Fecha ingreso del empleado',
  `fecha_contrato` date NOT NULL COMMENT 'Fecha de inicio de contrato o prologa de contrato',
  `codigo_tipo_contrato` smallint(3) unsigned zerofill NOT NULL COMMENT 'CÃ³digo que identifica el tipo de contrato en la tabla',
  `fecha_cambio_contrato` date NOT NULL COMMENT 'Fecha en la que termina o finaliza el contrato labores el empleado',
  PRIMARY KEY  (`codigo_empresa`,`documento_identidad_empleado`,`fecha_ingreso`,`fecha_contrato`),
  KEY `contrato_codigo_tipo_contrato` (`codigo_tipo_contrato`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_contrato_empleados`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_control_prestamos_empleados`
-- 

DROP TABLE IF EXISTS `job_control_prestamos_empleados`;
CREATE TABLE `job_control_prestamos_empleados` (
  `fecha_generacion` date NOT NULL COMMENT 'Fecha en la se genero el prestamo',
  `codigo_empresa` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa donde va a laborar',
  `documento_identidad_empleado` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Documento de identidad empleado en la sucursales contrato empleados',
  `fecha_ingreso` date NOT NULL COMMENT 'Fecha en la que ingreso el empleado a la empresa',
  `codigo_sucursal` mediumint(5) unsigned zerofill NOT NULL COMMENT 'Id de la tabla de sucursales',
  `fecha_ingreso_sucursal` date NOT NULL COMMENT 'Fecha en la que inicia labores en la sucursal el empleado',
  `consecutivo` int(8) unsigned zerofill NOT NULL COMMENT 'Consecutivo interno para la base de datos',
  `codigo_tipo_documento` smallint(3) unsigned zerofill NOT NULL COMMENT 'Código asignado por el usuario',
  `consecutivo_documento` int(8) unsigned zerofill NOT NULL COMMENT 'Consecutivo del documento',
  `codigo_transaccion_contable_descontar` int(8) unsigned zerofill NOT NULL COMMENT 'codigo de la transaccion cuenta por descontar al empleado',
  `codigo_transaccion_contable_cobrar` int(8) unsigned zerofill NOT NULL COMMENT 'codigo de la transaccion cuenta por cobrar empleado',
  `concepto_prestamo` smallint(4) unsigned zerofill NOT NULL COMMENT 'Código que identifica el concepto de prestamo',
  `observaciones` varchar(500) collate latin1_spanish_ci NOT NULL COMMENT 'Descripción del prestamo',
  `valor_total` decimal(11,2) NOT NULL COMMENT 'valor total del prestamo',
  `valor_pago` decimal(11,2) default NULL COMMENT 'Valor que se le descontará al empleado dependiendo del periodo seleccionado',
  `forma_pago` enum('1','2','3','4','5','6','7','8','9') collate latin1_spanish_ci NOT NULL COMMENT '1->mensual 2->primera quincena 3->segunda quincena 4->primera semana 5->segunda semana 6-> tercera semana 7-> cuarta semana 8-> quinta semana 9->  proporcional quincenal',
  `fecha_registro` datetime NOT NULL COMMENT 'Fecha en que se genera el registro',
  `codigo_usuario_registra` smallint(4) unsigned zerofill NOT NULL COMMENT 'Codigo del Usuario que genera el registro',
  `fecha_modificacion` timestamp NOT NULL default '0000-00-00 00:00:00' COMMENT 'Fecha en que se modifica el registro',
  `codigo_usuario_modifica` smallint(4) unsigned zerofill NOT NULL COMMENT 'Codigo del ultimo Usuario que modifica el registro',
  `valor_saldo` decimal(11,2) NOT NULL COMMENT 'Saldo del prestamo',
  PRIMARY KEY  (`documento_identidad_empleado`,`consecutivo`,`fecha_generacion`,`concepto_prestamo`),
  KEY `sucursal_contrato_control_prestamos_empleados` (`codigo_empresa`,`documento_identidad_empleado`,`fecha_ingreso`,`codigo_sucursal`,`fecha_ingreso_sucursal`),
  KEY `prestamos_tipo_documento` (`codigo_tipo_documento`),
  KEY `prestamos_transaccion_cuenta_por_descotar_empleado` (`codigo_transaccion_contable_descontar`),
  KEY `prestamos_transaccion_contable_por_cobrar_empleado` (`codigo_transaccion_contable_cobrar`),
  KEY `control_prestamos_concepto_prestamo` (`concepto_prestamo`),
  KEY `control_prestamos_empleados_consecutivo_documentos` (`codigo_sucursal`,`codigo_tipo_documento`,`documento_identidad_empleado`,`fecha_generacion`,`consecutivo_documento`),
  KEY `control_prestamos_empleados_usuario_registra` (`codigo_usuario_registra`),
  KEY `control_prestamos_empleados_usuario_modifica` (`codigo_usuario_modifica`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_control_prestamos_empleados`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_control_prestamos_terceros`
-- 

DROP TABLE IF EXISTS `job_control_prestamos_terceros`;
CREATE TABLE `job_control_prestamos_terceros` (
  `limite_descuento` enum('0','1','2') collate latin1_spanish_ci NOT NULL COMMENT '0-> Descuento ilimitado 1-> Fecha limite  2->valor tope',
  `fecha_generacion` datetime NOT NULL COMMENT 'Fecha en la se genero el prestamo',
  `fecha_inicio_descuento` date NOT NULL COMMENT 'Fecha en la se genero el prestamo',
  `codigo_sucursal` mediumint(5) unsigned zerofill NOT NULL COMMENT 'Id de la tabla de sucursales',
  `codigo_empresa` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa donde va a laborar',
  `documento_identidad_empleado` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Documento de identidad empleado en la sucursales contrato empleados',
  `documento_identidad_tercero` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Documento de identidad empleado en la sucursales contrato empleados',
  `autorizacion_descuento_nomina` enum('0','1') collate latin1_spanish_ci NOT NULL COMMENT '0 -> No 1 -> Si',
  `obligacion` varchar(10) collate latin1_spanish_ci NOT NULL COMMENT 'Numero de obligación',
  `valor_tope_descuento` decimal(11,2) NOT NULL COMMENT 'Valor que sele va a descontar al empleado',
  `periodo_pago` enum('1','2','3','4','5','6','7','8','9') collate latin1_spanish_ci NOT NULL COMMENT '1->mensual 2->primera quincena 3->segunda quincena 4->primera semana 5->segunda semana 6-> tercera semana 7-> cuarta semana 8-> quinta semana 9->Proporcional quincenal',
  `valor_descontar_mensual` decimal(11,2) NOT NULL COMMENT 'Valor con el que se va a liquidar el movimiento',
  `valor_descontar_primera_quincena` decimal(11,2) NOT NULL COMMENT 'Valor con el que se va a liquidar el movimiento',
  `valor_descontar_segunda_quincena` decimal(11,2) NOT NULL COMMENT 'Valor con el que se va a liquidar el movimiento',
  `valor_descontar_primera_semana` decimal(11,2) NOT NULL COMMENT 'Valor con el que se va a liquidar el movimiento',
  `valor_descontar_segunda_semana` decimal(11,2) NOT NULL COMMENT 'Valor con el que se va a liquidar el movimiento',
  `valor_descontar_tercera_semana` decimal(11,2) NOT NULL COMMENT 'Valor con el que se va a liquidar el movimiento',
  `valor_descontar_cuarta_semana` decimal(11,2) NOT NULL COMMENT 'Valor con el que se va a liquidar el movimiento',
  `descuento_ilimitado` enum('0','1') collate latin1_spanish_ci NOT NULL COMMENT '0-> No 1-> Si',
  `fecha_limite_descuento` date NOT NULL COMMENT 'Fecha hasta la cual se hace el descuento',
  `estado` enum('0','1','2') collate latin1_spanish_ci NOT NULL COMMENT '0-> activa 1-> suspendida 2-> cancelada',
  `codigo_usuario_registra` smallint(4) unsigned zerofill NOT NULL COMMENT 'Codigo del Usuario que genera el registro',
  `codigo_usuario_modifica` smallint(4) unsigned zerofill NOT NULL COMMENT 'Codigo del ultimo Usuario que modifica el registro',
  `transaccion_contable_descuento` int(8) unsigned zerofill NOT NULL COMMENT 'codigo de la transaccion contable empleado cuenta por cobrar descuento',
  `transaccion_contable_empleado` int(8) unsigned zerofill NOT NULL COMMENT 'codigo de la transaccion contable empleado cuenta por cobrar empleado',
  `transaccion_contable_pagar_tercero` int(8) unsigned zerofill NOT NULL COMMENT 'codigo de la transaccion contable empleado cuenta por pagar tercero',
  `transaccion_contable_pago_tercero` int(8) unsigned zerofill NOT NULL COMMENT 'codigo de la transaccion contable empleado cuenta pago tercero',
  PRIMARY KEY  (`codigo_empresa`,`documento_identidad_empleado`,`obligacion`),
  KEY `control_prestamos_terceros_sucursal` (`codigo_sucursal`),
  KEY `control_prestamos_terceros_ingreso_empleado` (`documento_identidad_empleado`,`codigo_empresa`),
  KEY `control_prestamos_terceros_terceros` (`documento_identidad_tercero`),
  KEY `control_prestamos_transaccion_contable_descuento` (`transaccion_contable_descuento`),
  KEY `control_prestamos_transaccion_contable_empleado` (`transaccion_contable_empleado`),
  KEY `control_prestamos_transaccion_contable_pagar_tercero` (`transaccion_contable_pagar_tercero`),
  KEY `control_prestamos_transaccion_contable_pago_tercero` (`transaccion_contable_pago_tercero`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_control_prestamos_terceros`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_conyugue_aspirante`
-- 

DROP TABLE IF EXISTS `job_conyugue_aspirante`;
CREATE TABLE `job_conyugue_aspirante` (
  `documento_identidad_aspirante` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo que identifica el tercero',
  `codigo_tipo_documento` smallint(3) unsigned zerofill NOT NULL COMMENT 'Consecutivo interno para la base de datos del tipo de documento de identificacion',
  `documento_identidad` varchar(12) collate latin1_spanish_ci default NULL COMMENT 'Numero del documento de identidad',
  `primer_nombre` varchar(20) collate latin1_spanish_ci default NULL COMMENT 'Primer nombre de la persona',
  `segundo_nombre` varchar(20) collate latin1_spanish_ci default NULL COMMENT 'Segundo nombre de la persona',
  `primer_apellido` varchar(20) collate latin1_spanish_ci default NULL COMMENT 'Primer apellido de la persona',
  `segundo_apellido` varchar(20) collate latin1_spanish_ci default NULL COMMENT 'Segundo apellido de la persona',
  `telefono` varchar(20) collate latin1_spanish_ci default NULL COMMENT 'Numero de telefono de la oficina de la persona',
  `codigo_dane_profesion` smallint(4) unsigned zerofill NOT NULL COMMENT 'Consecutivo interno para la base de datos del departamento interno',
  `codigo_cargo` smallint(3) unsigned zerofill NOT NULL COMMENT 'Consecutivo interno para la base de datos del cargo que desempeÃ±a la persona',
  `empresa` varchar(70) collate latin1_spanish_ci default NULL COMMENT 'Nombre de la empresa donde labora la persona',
  `celular` varchar(20) collate latin1_spanish_ci default NULL COMMENT 'Numero del telefono celular de la persona',
  PRIMARY KEY  (`documento_identidad_aspirante`),
  UNIQUE KEY `documento_identidad` (`documento_identidad`),
  KEY `tipo_documento_conyugue` (`codigo_tipo_documento`),
  KEY `profesion_conyugue` (`codigo_dane_profesion`),
  KEY `cargo_conyugue` (`codigo_cargo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_conyugue_aspirante`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_cuenta_por_pagar_pension_entidad`
-- 

DROP TABLE IF EXISTS `job_cuenta_por_pagar_pension_entidad`;
CREATE TABLE `job_cuenta_por_pagar_pension_entidad` (
  `codigo_sucursal` mediumint(5) unsigned zerofill NOT NULL COMMENT 'Codigo del almacen donde va a laborar',
  `ano_generacion` int(4) unsigned zerofill NOT NULL COMMENT 'Codigo interno que identifica la planilla',
  `mes_generacion` int(2) unsigned zerofill NOT NULL COMMENT 'Codigo interno que identifica la planilla',
  `codigo_planilla` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo interno que identifica la planilla',
  `periodo_pago` enum('1','2','3','4','5','6','7','8','9') collate latin1_spanish_ci NOT NULL COMMENT '1->Mensual 2->Primera quincena 3->Segunda quincena 4->Primera semana 5->Segunda semana 6->Tercer semana 7->Cuarta semana 8->Quinta semana 9->Fecha unica',
  `documento_identidad_empleado` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Documento de identidad que identifica el empleado en terceras personas',
  `fecha_pago_planilla` date NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado',
  `documento_identidad_entidad` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Documento de identidad de la entidad de pension',
  `codigo_empresa` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo del almacen donde va a laborar',
  `fecha_ingreso_empresa` date NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado',
  `fecha_ingreso_sucursal` date NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado',
  `codigo_entidad_parafiscal` int(8) unsigned zerofill NOT NULL COMMENT 'Codigo de la entidad parafiscal',
  `codigo_transaccion_contable` int(8) unsigned zerofill NOT NULL COMMENT 'Id de la tabla de transacciones contables',
  `codigo_contable` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo contable de la transaccion contable',
  `sentido` enum('D','C') collate latin1_spanish_ci NOT NULL COMMENT 'D->Debito C->Credito',
  `codigo_empresa_auxiliar` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa del auxiliar contable',
  `codigo_anexo_contable` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo del anexo que permite dividir las cuentas',
  `codigo_auxiliar_contable` int(8) unsigned zerofill NOT NULL COMMENT 'Codigo donde se acumulara la informacion',
  `valor_movimiento` decimal(11,2) NOT NULL COMMENT 'Valor del movimiento',
  `fecha_registro` datetime NOT NULL COMMENT 'Fecha de generación del registro',
  `codigo_usuario_registra` smallint(4) unsigned zerofill NOT NULL COMMENT 'Codigo usuario que genera el registro',
  PRIMARY KEY  (`codigo_sucursal`,`ano_generacion`,`mes_generacion`,`codigo_planilla`,`periodo_pago`,`documento_identidad_empleado`),
  KEY `cuenta_por_pagar_movimiento_pension_empleado` (`ano_generacion`,`mes_generacion`,`codigo_planilla`,`periodo_pago`,`fecha_pago_planilla`,`documento_identidad_empleado`,`codigo_sucursal`),
  KEY `cuenta_por_pagar_pension_entidad_documento_tercero` (`documento_identidad_entidad`),
  KEY `cuenta_por_pagar_pension_sucursal_contrato_empleado` (`codigo_empresa`,`documento_identidad_empleado`,`fecha_ingreso_empresa`,`codigo_sucursal`,`fecha_ingreso_sucursal`),
  KEY `cuenta_por_pagar_pension_transaccion_contable` (`codigo_transaccion_contable`),
  KEY `cuenta_por_pagar_pension_codigo_contable` (`codigo_contable`),
  KEY `cuenta_por_pagar_pension_auxiliar_contable` (`codigo_empresa_auxiliar`,`codigo_anexo_contable`,`codigo_auxiliar_contable`),
  KEY `cuenta_por_pagar_pension_usuario` (`codigo_usuario_registra`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_cuenta_por_pagar_pension_entidad`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_cuenta_por_pagar_salud_entidad`
-- 

DROP TABLE IF EXISTS `job_cuenta_por_pagar_salud_entidad`;
CREATE TABLE `job_cuenta_por_pagar_salud_entidad` (
  `codigo_sucursal` mediumint(5) unsigned zerofill NOT NULL COMMENT 'Codigo del almacen donde va a laborar',
  `ano_generacion` int(4) unsigned zerofill NOT NULL COMMENT 'Codigo interno que identifica la planilla',
  `mes_generacion` int(2) unsigned zerofill NOT NULL COMMENT 'Codigo interno que identifica la planilla',
  `codigo_planilla` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo interno que identifica la planilla',
  `periodo_pago` enum('1','2','3','4','5','6','7','8','9') collate latin1_spanish_ci NOT NULL COMMENT '1->Mensual 2->Primera quincena 3->Segunda quincena 4->Primera semana 5->Segunda semana 6->Tercer semana 7->Cuarta semana 8->Quinta semana 9->Fecha unica',
  `documento_identidad_empleado` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Documento de identidad que identifica el empleado en terceras personas',
  `documento_identidad_entidad` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Documento de identidad de la entidad de salud',
  `codigo_empresa` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo del almacen donde va a laborar',
  `fecha_ingreso_empresa` date NOT NULL COMMENT 'Fecha en la que inicia labores en la empresa el empleado',
  `fecha_ingreso_sucursal` date NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado',
  `codigo_entidad_parafiscal` int(8) unsigned zerofill NOT NULL COMMENT 'Codigo de la entidad parafiscal',
  `fecha_pago_planilla` date NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado',
  `valor_movimiento` decimal(11,2) NOT NULL COMMENT 'Valor del movimiento',
  `codigo_transaccion_contable` int(8) unsigned zerofill NOT NULL COMMENT 'Id de la tabla de transacciones contables',
  `codigo_contable` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo contable de la transaccion contable',
  `sentido` enum('D','C') collate latin1_spanish_ci NOT NULL COMMENT 'D->Debito C->Credito',
  `codigo_empresa_auxiliar` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa del auxiliar contable',
  `codigo_anexo_contable` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo del anexo que permite dividir las cuentas',
  `codigo_auxiliar_contable` int(8) unsigned zerofill NOT NULL COMMENT 'Codigo donde se acumulara la informacion',
  `fecha_generacion` datetime NOT NULL COMMENT 'Fceha de generación del registro',
  `codigo_usuario_registra` smallint(4) unsigned zerofill NOT NULL COMMENT 'Codigo usuario que genera el registro',
  PRIMARY KEY  (`codigo_sucursal`,`ano_generacion`,`mes_generacion`,`codigo_planilla`,`periodo_pago`,`documento_identidad_empleado`),
  KEY `cuenta_por_pagar_movimiento_salud_empleado` (`ano_generacion`,`mes_generacion`,`codigo_planilla`,`periodo_pago`,`fecha_pago_planilla`,`documento_identidad_empleado`,`codigo_sucursal`),
  KEY `cuenta_por_pagar_salud_entidad_documento_tercero` (`documento_identidad_entidad`),
  KEY `cuenta_por_pagar_salud_sucursal_contrato_empleado` (`codigo_empresa`,`documento_identidad_empleado`,`fecha_ingreso_empresa`,`codigo_sucursal`,`fecha_ingreso_sucursal`),
  KEY `cuenta_por_pagar_salud_transaccion_contable` (`codigo_transaccion_contable`),
  KEY `cuenta_por_pagar_salud_codigo_contable` (`codigo_contable`),
  KEY `cuenta_por_pagar_salud_auxiliar_contable` (`codigo_empresa_auxiliar`,`codigo_anexo_contable`,`codigo_auxiliar_contable`),
  KEY `cuenta_por_pagar_salud_usuario` (`codigo_usuario_registra`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_cuenta_por_pagar_salud_entidad`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_cuentas_bancarias`
-- 

DROP TABLE IF EXISTS `job_cuentas_bancarias`;
CREATE TABLE `job_cuentas_bancarias` (
  `codigo_sucursal` mediumint(5) unsigned zerofill NOT NULL COMMENT 'Codigo de la sucursal a la que pertenece la cuenta',
  `codigo_tipo_documento` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo del tipo de documento',
  `codigo_sucursal_banco` smallint(2) unsigned zerofill NOT NULL COMMENT 'codigo de la tabla bancos',
  `codigo_iso` varchar(2) collate latin1_spanish_ci NOT NULL COMMENT 'Llave principal de la tabla paises',
  `codigo_dane_departamento` varchar(2) collate latin1_spanish_ci NOT NULL COMMENT 'Código DANE',
  `codigo_dane_municipio` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'Código DANE',
  `codigo_banco` smallint(2) unsigned zerofill NOT NULL COMMENT 'id de la tabla bancos',
  `numero` varchar(30) collate latin1_spanish_ci NOT NULL COMMENT 'Numero de la cuenta',
  `codigo_plan_contable` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'Código definido por el PUC(Plan unico de cuentas)',
  `codigo_empresa_auxiliar` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa para la llave de auxiliares',
  `codigo_anexo_contable` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'Código del anexo para la llave de auxiliares',
  `codigo_auxiliar_contable` int(8) unsigned zerofill NOT NULL COMMENT 'Código del auxiliar si aplica',
  `estado` enum('0','1') collate latin1_spanish_ci NOT NULL default '1' COMMENT 'Estado de la cuenta bancaria: 1->Activa, 2->Inactiva',
  `plantilla` text collate latin1_spanish_ci NOT NULL COMMENT 'Plantilla para impresion de cheques',
  PRIMARY KEY  (`codigo_sucursal`,`codigo_tipo_documento`,`codigo_sucursal_banco`,`codigo_iso`,`codigo_dane_departamento`,`codigo_dane_municipio`,`codigo_banco`,`numero`),
  KEY `cuentas_bancarias_tipo_documento` (`codigo_tipo_documento`),
  KEY `cuentas_bancarias_banco` (`codigo_banco`),
  KEY `cuentas_bancarias_sucursal_banco` (`codigo_sucursal_banco`,`codigo_banco`,`codigo_iso`,`codigo_dane_departamento`,`codigo_dane_municipio`),
  KEY `cuentas_bancarias_cuenta` (`codigo_plan_contable`),
  KEY `cuentas_bancarias_auxiliar_contable` (`codigo_empresa_auxiliar`,`codigo_anexo_contable`,`codigo_auxiliar_contable`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_cuentas_bancarias`
-- 

INSERT INTO `job_cuentas_bancarias` (`codigo_sucursal`, `codigo_tipo_documento`, `codigo_sucursal_banco`, `codigo_iso`, `codigo_dane_departamento`, `codigo_dane_municipio`, `codigo_banco`, `numero`, `codigo_plan_contable`, `codigo_empresa_auxiliar`, `codigo_anexo_contable`, `codigo_auxiliar_contable`, `estado`, `plantilla`) VALUES 
(00000, 000, 00, '', '', '', 00, '', '', 000, '', 00000000, '0', '');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_datos_liquidaciones_empleado`
-- 

DROP TABLE IF EXISTS `job_datos_liquidaciones_empleado`;
CREATE TABLE `job_datos_liquidaciones_empleado` (
  `codigo_empresa` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa donde va a laborar',
  `documento_identidad_empleado` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Documento de identidad empleado en la sucursales contrato empleados',
  `fecha_generacion` datetime NOT NULL COMMENT 'Fecha en la se genero el retiro',
  `motivo_retiro` smallint(4) unsigned zerofill NOT NULL COMMENT 'Código que identifica el concepto de prestamo',
  `codigo_empresa_auxiliar` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa para la llave de auxiliares',
  `codigo_anexo_contable` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'Código del anexo que permite dividir las cuentas',
  `codigo_auxiliar_contable` int(8) unsigned zerofill NOT NULL COMMENT 'Código donde se acumulara la información',
  `condigo_transaccion` int(8) unsigned zerofill NOT NULL COMMENT 'Id de la tabla de transacciones contables',
  `codigo_plan_contable` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo de la cuenta contable segun el PUC',
  `sentido` enum('D','C') collate latin1_spanish_ci NOT NULL COMMENT 'D->Débito C->Crédito',
  `valor` decimal(11,2) NOT NULL COMMENT 'Valor diario del registro',
  PRIMARY KEY  (`codigo_empresa`,`documento_identidad_empleado`,`fecha_generacion`,`motivo_retiro`,`condigo_transaccion`),
  KEY `datos_liquidaciones_empleado_plan_contable` (`codigo_plan_contable`),
  KEY `datos_liquidaciones_empleado_transaccion` (`condigo_transaccion`),
  KEY `datos_liquidaciones_empleado_auxiliar_contable` (`codigo_empresa_auxiliar`,`codigo_anexo_contable`,`codigo_auxiliar_contable`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_datos_liquidaciones_empleado`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_departamento_seccion_contrato_empleado`
-- 

DROP TABLE IF EXISTS `job_departamento_seccion_contrato_empleado`;
CREATE TABLE `job_departamento_seccion_contrato_empleado` (
  `codigo_empresa` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo del almacen donde va a laborar',
  `documento_identidad_empleado` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'CÃ³digo interno que identifica el empleado en terceras personas',
  `fecha_ingreso` date NOT NULL COMMENT 'Fecha en la que inicia labores en la empresa el empleado',
  `codigo_sucursal` mediumint(5) unsigned zerofill NOT NULL COMMENT 'Codigo del almacen donde va a laborar',
  `fecha_ingreso_sucursal` date NOT NULL COMMENT 'Fecha en la que inicia labores en la sucursal el empleado',
  `fecha_inicia_departamento_seccion` date NOT NULL COMMENT 'Fecha en la que inicia labores en el departamento y seccion asignado',
  `codigo_departamento_empresa` smallint(4) unsigned zerofill NOT NULL COMMENT 'CÃ³digo del departamento de la empresa donde va a laborar',
  `codigo_seccion_empresa` smallint(4) unsigned zerofill NOT NULL COMMENT 'CÃ³digo asignado usuario',
  `fecha_termina` date NOT NULL COMMENT 'Fecha en la que termina labores en el departamento asignado',
  PRIMARY KEY  (`codigo_empresa`,`documento_identidad_empleado`,`fecha_ingreso`,`codigo_sucursal`,`fecha_ingreso_sucursal`,`fecha_inicia_departamento_seccion`),
  KEY `sucursal_contrato_seccion_empleado` (`codigo_departamento_empresa`,`codigo_seccion_empresa`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_departamento_seccion_contrato_empleado`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_departamentos`
-- 

DROP TABLE IF EXISTS `job_departamentos`;
CREATE TABLE `job_departamentos` (
  `codigo_iso` varchar(2) collate latin1_spanish_ci NOT NULL COMMENT 'Llave principal de la tabla paises',
  `codigo_dane_departamento` varchar(2) collate latin1_spanish_ci NOT NULL COMMENT 'CÃ³digo DANE',
  `codigo_interno` smallint(3) unsigned zerofill default NULL COMMENT 'CÃ³digo para uso interno de la empresa (opcional)',
  `nombre` varchar(255) collate latin1_spanish_ci NOT NULL COMMENT 'Nombre completo',
  PRIMARY KEY  (`codigo_iso`,`codigo_dane_departamento`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_departamentos`
-- 

INSERT INTO `job_departamentos` (`codigo_iso`, `codigo_dane_departamento`, `codigo_interno`, `nombre`) VALUES 
('', '0', 000, '');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_departamentos_empresa`
-- 

DROP TABLE IF EXISTS `job_departamentos_empresa`;
CREATE TABLE `job_departamentos_empresa` (
  `codigo` smallint(4) unsigned zerofill NOT NULL COMMENT 'Código que identifica el departamento',
  `nombre` varchar(50) collate latin1_spanish_ci NOT NULL default '' COMMENT 'Nombre del departamento',
  `riesgos_profesionales` decimal(7,4) default NULL COMMENT 'Porcentaje para la liquidacion de riesgos profesionales',
  `codigo_gasto` int(5) unsigned zerofill NOT NULL default '00000' COMMENT 'Codigo de la tabla gastos prestaciones sociales',
  PRIMARY KEY  (`codigo`),
  UNIQUE KEY `nombre` (`nombre`),
  KEY `gasto_departamento_empresa` (`codigo_gasto`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_departamentos_empresa`
-- 

INSERT INTO `job_departamentos_empresa` (`codigo`, `nombre`, `riesgos_profesionales`, `codigo_gasto`) VALUES 
(0000, '', NULL, 00000);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_deportes`
-- 

DROP TABLE IF EXISTS `job_deportes`;
CREATE TABLE `job_deportes` (
  `codigo` smallint(4) unsigned zerofill NOT NULL COMMENT 'Código interno que identifica el deporte',
  `descripcion` varchar(255) collate latin1_spanish_ci NOT NULL COMMENT 'Detalle que describe el deporte ',
  PRIMARY KEY  (`codigo`),
  UNIQUE KEY `descripcion` (`descripcion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_deportes`
-- 

INSERT INTO `job_deportes` (`codigo`, `descripcion`) VALUES 
(0000, '');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_deportes_aspirante`
-- 

DROP TABLE IF EXISTS `job_deportes_aspirante`;
CREATE TABLE `job_deportes_aspirante` (
  `documento_identidad_aspirante` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo que identifica el tercero',
  `codigo_deporte` smallint(4) unsigned zerofill NOT NULL COMMENT 'CÃ³digo interno que identifica el deporte',
  PRIMARY KEY  (`documento_identidad_aspirante`,`codigo_deporte`),
  KEY `deportes_aspirante_deportes` (`codigo_deporte`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_deportes_aspirante`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_domingos_festivos`
-- 

DROP TABLE IF EXISTS `job_domingos_festivos`;
CREATE TABLE `job_domingos_festivos` (
  `anio` varchar(10) collate latin1_spanish_ci NOT NULL COMMENT 'anio de la generacion',
  `fecha` date NOT NULL COMMENT 'Fecha del Domingo o festivo',
  `tipo` enum('1','2') collate latin1_spanish_ci NOT NULL default '1' COMMENT '1->Domingo 2->Festivo',
  `descripcion` varchar(50) collate latin1_spanish_ci default NULL COMMENT 'Descripcion de la fecha',
  PRIMARY KEY  (`fecha`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_domingos_festivos`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_empresas`
-- 

DROP TABLE IF EXISTS `job_empresas`;
CREATE TABLE `job_empresas` (
  `codigo` smallint(3) unsigned zerofill NOT NULL COMMENT 'CÃ³digo interno de la empresa',
  `razon_social` varchar(60) collate latin1_spanish_ci NOT NULL COMMENT 'Razon social que identifica la empresa',
  `nombre_corto` char(10) collate latin1_spanish_ci NOT NULL COMMENT 'Nombre corto que identifica la empresa en consultas',
  `fecha_cierre` date default NULL COMMENT 'Fecha que estuvo activa la empresa',
  `activo` enum('0','1') collate latin1_spanish_ci NOT NULL default '1' COMMENT 'Indicador de estado de la empresa: 0=Inactiva, 1=Activa',
  `documento_identidad_tercero` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Nit o docuemtno identidad de la empresa en la tabla terceros',
  `regimen` enum('1','2') collate latin1_spanish_ci default '1' COMMENT '1->Regimen comun 2->Regimen simplificado',
  `retiene_fuente` enum('0','1') collate latin1_spanish_ci default '0' COMMENT 'Realiza retencion en la fuente 0->No 1->Si',
  `autoretenedor` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT 'Autoretenedor 0->No 1->Si',
  `numero_retefuente` varchar(255) collate latin1_spanish_ci NOT NULL COMMENT 'Numero de la resoluciÃ³n',
  `retiene_iva` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT 'Retiene IVA 0->No 1->Si',
  `retiene_ica` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT 'Retiene ICA 0->No 1->Si',
  `autoretenedor_ica` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT 'Autoretenedor ICA 0->No 1->Si',
  `gran_contribuyente` enum('0','1') collate latin1_spanish_ci NOT NULL COMMENT 'Empresa esta catalogada como gran contribuyente por la DIAN 0->No 1-Si',
  `numero_resolucion_contribuyente` varchar(255) collate latin1_spanish_ci NOT NULL COMMENT 'Numero de la resoluciÃ³n del gran contribuyente',
  `codigo_iso_primaria` varchar(2) collate latin1_spanish_ci NOT NULL COMMENT 'Llave principal de la tabla paises',
  `codigo_dane_departamento_primaria` varchar(2) collate latin1_spanish_ci NOT NULL COMMENT 'CÃ³digo DANE',
  `codigo_dane_municipio_primaria` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'CÃ³digo DANE',
  `codigo_dian_primaria` smallint(4) unsigned zerofill NOT NULL COMMENT 'id de la tabla actividades economicas DIAN',
  `codigo_actividad_municipio_primaria` int(5) unsigned zerofill default NULL COMMENT 'Codigo de la actividad economica del municipio',
  `codigo_iso_secundaria` varchar(2) collate latin1_spanish_ci NOT NULL COMMENT 'Llave principal de la tabla paises',
  `codigo_dane_departamento_secundaria` varchar(2) collate latin1_spanish_ci NOT NULL COMMENT 'CÃ³digo DANE',
  `codigo_dane_municipio_secundaria` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'CÃ³digo DANE',
  `codigo_dian_secundaria` smallint(4) unsigned zerofill NOT NULL COMMENT 'id de la tabla actividades economicas DIAN',
  `codigo_actividad_municipio_secundaria` int(5) unsigned zerofill default NULL COMMENT 'Codigo de la actividad economica del municipio',
  PRIMARY KEY  (`codigo`),
  KEY `empresas_tercero` (`documento_identidad_tercero`),
  KEY `empresas_actividad_principal` (`codigo_iso_primaria`,`codigo_dane_departamento_primaria`,`codigo_dane_municipio_primaria`,`codigo_dian_primaria`,`codigo_actividad_municipio_primaria`),
  KEY `empresas_actividad_secundaria` (`codigo_iso_secundaria`,`codigo_dane_departamento_secundaria`,`codigo_dane_municipio_secundaria`,`codigo_dian_secundaria`,`codigo_actividad_municipio_secundaria`),
  KEY `empresas_resolucion_contribuyente` (`numero_resolucion_contribuyente`),
  KEY `empresas_resolucion_retefuente` (`numero_retefuente`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_empresas`
-- 

INSERT INTO `job_empresas` (`codigo`, `razon_social`, `nombre_corto`, `fecha_cierre`, `activo`, `documento_identidad_tercero`, `regimen`, `retiene_fuente`, `autoretenedor`, `numero_retefuente`, `retiene_iva`, `retiene_ica`, `autoretenedor_ica`, `gran_contribuyente`, `numero_resolucion_contribuyente`, `codigo_iso_primaria`, `codigo_dane_departamento_primaria`, `codigo_dane_municipio_primaria`, `codigo_dian_primaria`, `codigo_actividad_municipio_primaria`, `codigo_iso_secundaria`, `codigo_dane_departamento_secundaria`, `codigo_dane_municipio_secundaria`, `codigo_dian_secundaria`, `codigo_actividad_municipio_secundaria`) VALUES 
(000, '', '', '0000-00-00', '1', '', '1', '0', '0', '0', '0', '0', '0', '0', '0', '', '', '', 0000, 00000, '', '', '', 0000, 00000);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_empresas_aspirante`
-- 

DROP TABLE IF EXISTS `job_empresas_aspirante`;
CREATE TABLE `job_empresas_aspirante` (
  `documento_identidad_aspirante` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo que identifica el tercero',
  `consecutivo` int(8) NOT NULL COMMENT 'Consecutivo interno para la base de datos',
  `nombre` varchar(255) collate latin1_spanish_ci NOT NULL COMMENT 'Nombre de la empresa',
  `codigo_iso_actividad` varchar(2) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo ISO en la tabla de actividades econimicas',
  `codigo_dane_departamento_actividad` varchar(2) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo dane departamento en la tabla de actividades econimicas',
  `codigo_dane_municipio_actividad` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo dane municipio en la tabla de actividades econimicas',
  `codigo_dian_actividad` smallint(4) unsigned zerofill NOT NULL COMMENT 'Codigo DIAN en la tabla de actividades econimicas',
  `codigo_actividad_economica` int(5) unsigned zerofill default NULL COMMENT 'Codigo actividad municipio en la tabla de actividades econimicas',
  `direccion` varchar(50) collate latin1_spanish_ci default NULL COMMENT 'Direccion ddonde esta ubicada la empresa',
  `telefono` varchar(15) collate latin1_spanish_ci default NULL COMMENT 'Numero de telefono de la empresa',
  `codigo_departamento_empresa` smallint(4) unsigned zerofill NOT NULL COMMENT 'Consecutivo interno para la base de datos del departamento interno',
  `codigo_cargo` smallint(3) unsigned zerofill NOT NULL COMMENT 'Consecutivo interno para la base de datos del cargo que desempeÃ±a',
  `jefe_inmediato` varchar(50) collate latin1_spanish_ci default NULL COMMENT 'jefe inmediato en la empresa donde laboro',
  `fecha_inicial` date NOT NULL COMMENT 'Fecha de inicio de labores en la empresa',
  `fecha_final` date default NULL COMMENT 'Fecha que termino labores en la empresa',
  `horario_laboral` enum('1','2','3') collate latin1_spanish_ci NOT NULL default '1' COMMENT ' 1->Diurno 2->Nocturno 3->Ambos',
  `codigo_tipo_contrato` smallint(3) unsigned zerofill NOT NULL COMMENT 'Id de la tabla de tipos de contrato',
  `codigo_motivo_retiro` smallint(3) unsigned zerofill NOT NULL COMMENT 'Consecutivo interno para la base de datos del motivo de retiro',
  `logros_obtenidos` mediumtext collate latin1_spanish_ci COMMENT 'Descripcion de los logros optenidos en la empresa',
  PRIMARY KEY  (`documento_identidad_aspirante`,`consecutivo`),
  UNIQUE KEY `documento_identidad_aspirante` (`documento_identidad_aspirante`,`nombre`),
  KEY `empresas_aspirante_motivo_retiro` (`codigo_motivo_retiro`),
  KEY `empresas_aspirante_tipos_contrato` (`codigo_tipo_contrato`),
  KEY `empresas_aspirante_codigo_departamento_empresa` (`codigo_departamento_empresa`),
  KEY `empresas_aspirante_actividad_economica` (`codigo_iso_actividad`,`codigo_dane_departamento_actividad`,`codigo_dane_municipio_actividad`,`codigo_dian_actividad`,`codigo_actividad_economica`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_empresas_aspirante`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_entidades_parafiscales`
-- 

DROP TABLE IF EXISTS `job_entidades_parafiscales`;
CREATE TABLE `job_entidades_parafiscales` (
  `codigo` int(8) unsigned zerofill NOT NULL COMMENT 'Consecutivo interno que identifica la entidad en la base de datos',
  `codigo_ruaf` varchar(50) collate latin1_spanish_ci default NULL COMMENT 'Registro unico de afialiado(RUAF) asignado por el ministerio de la proteccion social',
  `documento_identidad_tercero` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Número del documento de identidad del tercero',
  `nombre` varchar(100) collate latin1_spanish_ci NOT NULL COMMENT 'Nombre de la entidad',
  `salud` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT 'Aporta 0->No 1->Si',
  `pension` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT 'Aporta 0->No 1->Si',
  `cesantias` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT 'Aporta 0->No 1->Si',
  `caja` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT 'Aporta 0->No 1->Si',
  `sena` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT 'Aporta 0->No 1->Si',
  `icbf` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT 'Aporta  0->No 1->Si',
  `riesgos_profesionales` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT 'Riesgos profesionales  0->No 1->Si',
  `asistencia_social` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT 'Asistencia social  0->No 1->Si',
  PRIMARY KEY  (`codigo`),
  UNIQUE KEY `nombre` (`nombre`),
  KEY `entidad_parafiscal_tercero` (`documento_identidad_tercero`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_entidades_parafiscales`
-- 

INSERT INTO `job_entidades_parafiscales` (`codigo`, `codigo_ruaf`, `documento_identidad_tercero`, `nombre`, `salud`, `pension`, `cesantias`, `caja`, `sena`, `icbf`, `riesgos_profesionales`, `asistencia_social`) VALUES 
(00000000, '', '0', '', '1', '1', '1', '0', '0', '0', '0', '0');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_entidades_pension_empleados`
-- 

DROP TABLE IF EXISTS `job_entidades_pension_empleados`;
CREATE TABLE `job_entidades_pension_empleados` (
  `codigo_empresa` smallint(3) unsigned zerofill NOT NULL COMMENT 'CÃ³digo de la empresa en la tabla ingresos',
  `documento_identidad_empleado` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Documento identidad del empleado en la tabla ingresos',
  `fecha_ingreso` date NOT NULL COMMENT 'Fecha de ingreso a la empresa del empleado',
  `fecha_inicio_pension` date NOT NULL COMMENT 'Fecha en la que inicia relacion con la entidad',
  `codigo_entidad_pension` int(8) unsigned zerofill NOT NULL COMMENT 'Codigo de la entidad parafiscal en la base de datos',
  PRIMARY KEY  (`codigo_empresa`,`documento_identidad_empleado`,`fecha_ingreso`,`fecha_inicio_pension`),
  KEY `entidad_pension_empleado` (`codigo_entidad_pension`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_entidades_pension_empleados`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_entidades_salud_empleados`
-- 

DROP TABLE IF EXISTS `job_entidades_salud_empleados`;
CREATE TABLE `job_entidades_salud_empleados` (
  `codigo_empresa` smallint(3) unsigned zerofill NOT NULL COMMENT 'CÃ³digo de la empresa en la tabla ingresos',
  `documento_identidad_empleado` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Documento identidad del empleado en la tabla ingresos',
  `fecha_ingreso` date NOT NULL COMMENT 'Fecha de ingreso a la empresa del empleado',
  `fecha_inicio_salud` date NOT NULL COMMENT 'Fecha en la que inicia relacion con la entidad',
  `codigo_entidad_salud` int(8) unsigned zerofill NOT NULL COMMENT 'Consecutivo interno que identifica la entidad en la base de datos',
  `direccion_atencion` varchar(100) collate latin1_spanish_ci default NULL COMMENT 'Direccion donde atienden las citas normales',
  `direccion_urgencia` varchar(100) collate latin1_spanish_ci default NULL COMMENT 'Direccion donde atienden las urgencias',
  PRIMARY KEY  (`codigo_empresa`,`documento_identidad_empleado`,`fecha_ingreso`,`fecha_inicio_salud`),
  KEY `codigo_entidad_salud_ingreso_empleado` (`codigo_entidad_salud`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_entidades_salud_empleados`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_escolaridad`
-- 

DROP TABLE IF EXISTS `job_escolaridad`;
CREATE TABLE `job_escolaridad` (
  `codigo` smallint(4) unsigned zerofill NOT NULL COMMENT 'Consecutivo interno para la base de datos del codigo de la escolaridad',
  `descripcion` varchar(255) collate latin1_spanish_ci NOT NULL COMMENT 'Detalle que identifica la escolaridad',
  PRIMARY KEY  (`codigo`),
  UNIQUE KEY `descripcion` (`descripcion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_escolaridad`
-- 

INSERT INTO `job_escolaridad` (`codigo`, `descripcion`) VALUES 
(0000, '');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_estado_mercancia`
-- 

DROP TABLE IF EXISTS `job_estado_mercancia`;
CREATE TABLE `job_estado_mercancia` (
  `codigo` smallint(3) unsigned zerofill NOT NULL auto_increment COMMENT 'Código interno de la base de datos',
  `descripcion` varchar(255) collate latin1_spanish_ci NOT NULL default '' COMMENT 'Descripción de la marca',
  PRIMARY KEY  (`codigo`),
  UNIQUE KEY `descripcion` (`descripcion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1 ;

-- 
-- Volcar la base de datos para la tabla `job_estado_mercancia`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_estructura_grupos`
-- 

DROP TABLE IF EXISTS `job_estructura_grupos`;
CREATE TABLE `job_estructura_grupos` (
  `codigo` smallint(4) unsigned zerofill NOT NULL COMMENT 'Código utilizado por la empresa',
  `codigo_padre` smallint(4) unsigned zerofill default NULL COMMENT 'Consecutivo interno para la base de datos del grupo padre dentro de la estructura de grupos (NULL: Grupo principal)',
  `codigo_grupo` smallint(4) unsigned zerofill NOT NULL COMMENT 'Consecutivo interno para la base de datos del grupo al que pertenece',
  `descripcion` varchar(255) collate latin1_spanish_ci NOT NULL default '' COMMENT 'Descripción del grupo',
  `orden` int(5) default '0' COMMENT 'Orden a salir en los listados',
  PRIMARY KEY  (`codigo`),
  UNIQUE KEY `descripcion` (`descripcion`),
  KEY `estructura_relacion_grupo` (`codigo_grupo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_estructura_grupos`
-- 

INSERT INTO `job_estructura_grupos` (`codigo`, `codigo_padre`, `codigo_grupo`, `descripcion`, `orden`) VALUES 
(0000, NULL, 0000, '', 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_estudios_aspirante`
-- 

DROP TABLE IF EXISTS `job_estudios_aspirante`;
CREATE TABLE `job_estudios_aspirante` (
  `documento_identidad_aspirante` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo que identifica el tercero',
  `consecutivo` int(8) NOT NULL COMMENT 'Consecutivo interno para la base de datos',
  `codigo_escolaridad` smallint(4) unsigned zerofill NOT NULL COMMENT 'Codigo interno que identifica la escoloaridad del aspirante',
  `titulo` varchar(255) collate latin1_spanish_ci default NULL COMMENT 'Titulo otorgado ',
  `fecha_inicio` date default NULL COMMENT 'Fecha que inicio los estudios',
  `fecha_fin` date default NULL COMMENT 'Fecha que finalizo los estudios',
  `codigo_iso_estudios` varchar(2) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo ISO en la tabla de municipios',
  `codigo_dane_departamento_estudios` varchar(2) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo dane departamento en la tabla de municipios',
  `codigo_dane_municipio_estudios` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo dane municipio en la tabla de municipios',
  `intensidad_horaria` int(2) default NULL COMMENT 'Horas de clase al dia',
  `horario` enum('1','2','3') collate latin1_spanish_ci NOT NULL default '1' COMMENT '1->Diurno 2->Nocturno 3->Sabatino',
  `institucion` varchar(255) collate latin1_spanish_ci NOT NULL COMMENT 'Nombre de la institucion donde realizo los estudios',
  PRIMARY KEY  (`documento_identidad_aspirante`,`consecutivo`),
  KEY `escolaridad_aspirantes` (`codigo_escolaridad`),
  KEY `aspirantes_municipio_escolaridad` (`codigo_iso_estudios`,`codigo_dane_departamento_estudios`,`codigo_dane_municipio_estudios`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_estudios_aspirante`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_familia_aspirante`
-- 

DROP TABLE IF EXISTS `job_familia_aspirante`;
CREATE TABLE `job_familia_aspirante` (
  `documento_identidad_aspirante` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo que identifica el tercero',
  `consecutivo` int(8) NOT NULL COMMENT 'Consecutivo interno para la base de datos',
  `codigo_tipo_documento` smallint(3) unsigned zerofill NOT NULL COMMENT 'Consecutivo interno para la base de datos del tipo de documento de identificacion',
  `documento_identidad` varchar(12) collate latin1_spanish_ci default NULL COMMENT 'Numero del documento de identidad',
  `nombre_completo` varchar(255) collate latin1_spanish_ci NOT NULL COMMENT 'Nombre del familiar del aspirante',
  `codigo_dane_profesion` smallint(4) unsigned zerofill NOT NULL COMMENT 'Codigo interno que identifica la profesion del aspirante',
  `parentesco` enum('1','2','3','4','5','6') collate latin1_spanish_ci NOT NULL default '6' COMMENT '1->Hijo(a) 2->Madre 3->Padre 4->hermano(a) 5->abuelo(a) 6->otro',
  `fecha_nacimiento` date NOT NULL COMMENT 'Fecha de nacimiento',
  `genero` enum('M','F') collate latin1_spanish_ci NOT NULL default 'M' COMMENT 'M->Masculino F->Femenino',
  `depende_economicamente` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT '0->No 1->Si',
  PRIMARY KEY  (`documento_identidad_aspirante`,`consecutivo`),
  UNIQUE KEY `documento_identidad_aspirante` (`documento_identidad_aspirante`,`documento_identidad`),
  KEY `familia_tipo_documento` (`codigo_tipo_documento`),
  KEY `familia_profesion` (`codigo_dane_profesion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_familia_aspirante`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_fechas_planillas`
-- 

DROP TABLE IF EXISTS `job_fechas_planillas`;
CREATE TABLE `job_fechas_planillas` (
  `codigo_planilla` smallint(3) unsigned zerofill NOT NULL COMMENT 'Código interno de la planilla de pago',
  `fecha` date NOT NULL COMMENT 'fecha que se va a pagar la planilla',
  PRIMARY KEY  (`codigo_planilla`,`fecha`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_fechas_planillas`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_fechas_prestamos_empleados`
-- 

DROP TABLE IF EXISTS `job_fechas_prestamos_empleados`;
CREATE TABLE `job_fechas_prestamos_empleados` (
  `documento_identidad_empleado` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Documento de identidad empleado en la sucursales contrato empleados',
  `fecha_generacion` date NOT NULL COMMENT 'Fecha en la se genero el prestamo',
  `consecutivo` int(8) unsigned zerofill NOT NULL COMMENT 'Consecutivo interno para la base de datos',
  `concepto_prestamo` smallint(4) unsigned zerofill NOT NULL COMMENT 'Código que identifica el concepto de prestamo',
  `fecha_pago` date NOT NULL COMMENT 'Fecha de acuerdo de pago',
  `valor_saldo` decimal(11,2) NOT NULL COMMENT 'valor del saldo actual',
  `descuento` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT '0->No 1->Si ',
  `pagada` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT '0->No 1->Si ',
  `valor_descuento` decimal(11,2) default NULL COMMENT 'Valor que se le descontará al empleado dependiendo del periodo seleccionado',
  PRIMARY KEY  (`documento_identidad_empleado`,`consecutivo`,`fecha_generacion`,`concepto_prestamo`,`fecha_pago`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_fechas_prestamos_empleados`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_forma_pago_planillas_efectivo`
-- 

DROP TABLE IF EXISTS `job_forma_pago_planillas_efectivo`;
CREATE TABLE `job_forma_pago_planillas_efectivo` (
  `codigo_sucursal` mediumint(5) unsigned zerofill NOT NULL COMMENT 'tabla planilla de la tabla de sucursales',
  `ano_generacion` int(4) unsigned zerofill NOT NULL COMMENT 'Código interno que identifica la planilla',
  `mes_generacion` int(2) unsigned zerofill NOT NULL COMMENT 'Código interno que identifica la planilla',
  `codigo_planilla` smallint(3) unsigned zerofill NOT NULL COMMENT 'Código interno que identifica la planilla',
  `periodo_pago` enum('1','2','3','4','5','6','7','8','9') collate latin1_spanish_ci NOT NULL COMMENT '1->Mensual 2->Primera quincena 3->Segunda quincena 4->Primera semana 5->Segunda semana 6->Tercer semana 7->Cuarta semana 8->Quinta semana 9->Fecha unica',
  `fecha_pago_planilla` date NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado',
  `valor_movimiento` decimal(11,2) NOT NULL COMMENT 'Valor diario del registro',
  `codigo_contable` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo de la cuenta contable segun el PUC',
  `sentido` enum('D','C') collate latin1_spanish_ci NOT NULL COMMENT 'D->Debito C->Credito',
  `codigo_empresa_auxiliar` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa para la llave de auxiliares',
  `codigo_anexo_contable` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo del anexo que permite dividir las cuentas',
  `codigo_auxiliar_contable` int(8) unsigned zerofill NOT NULL COMMENT 'Codigo donde se acumulara la informacion',
  `codigo_tipo_documento` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo del tipo de documento',
  `documento_identidad_tercero` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Número del documento de identidad',
  `fecha_consecutivo` date NOT NULL COMMENT 'Fecha de generacion del consecutivo documento',
  `consecutivo` int(9) unsigned zerofill NOT NULL COMMENT 'Consecutivo del documento',
  `fecha_registro` datetime NOT NULL COMMENT 'Fecha en que se genera el registro',
  `codigo_usuario_registra` smallint(4) unsigned zerofill NOT NULL COMMENT 'Codigo del usuario que genera el registro',
  `pagada` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT 'Pagada 0->No 1->Si',
  PRIMARY KEY  (`codigo_sucursal`,`ano_generacion`,`mes_generacion`,`codigo_planilla`,`periodo_pago`),
  KEY `forma_pago_planillas_efectivo_planilla` (`codigo_planilla`),
  KEY `forma_pago_planillas_efectivo_auxiliar_contable` (`codigo_empresa_auxiliar`,`codigo_anexo_contable`,`codigo_auxiliar_contable`),
  KEY `forma_pago_planillas_efectivo_plan_contable` (`codigo_contable`),
  KEY `forma_pago_planillas_efectivo_consecutivo` (`codigo_sucursal`,`codigo_tipo_documento`,`documento_identidad_tercero`,`fecha_consecutivo`,`consecutivo`),
  KEY `forma_pago_planillas_efectivo_usuario` (`codigo_usuario_registra`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_forma_pago_planillas_efectivo`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_forma_pago_planillas_nomina`
-- 

DROP TABLE IF EXISTS `job_forma_pago_planillas_nomina`;
CREATE TABLE `job_forma_pago_planillas_nomina` (
  `codigo_sucursal` mediumint(5) unsigned zerofill NOT NULL COMMENT 'Codigo de la sucursal que recibe el pago',
  `ano_generacion` int(4) unsigned zerofill NOT NULL COMMENT 'Código interno que identifica la planilla',
  `mes_generacion` int(2) unsigned zerofill NOT NULL COMMENT 'Código interno que identifica la planilla',
  `codigo_planilla` smallint(3) unsigned zerofill NOT NULL COMMENT 'Código interno que identifica la planilla',
  `periodo_pago` enum('1','2','3','4','5','6','7','8','9') collate latin1_spanish_ci NOT NULL COMMENT '1->Mensual 2->Primera quincena 3->Segunda quincena 4->Primera semana 5->Segunda semana 6->Tercer semana 7->Cuarta semana 8->Quinta semana 9->Fecha unica',
  `codigo_sucursal_genera` mediumint(5) unsigned zerofill NOT NULL COMMENT 'Codigo de la sucursal que genera el pago',
  `fecha_pago_planilla` date NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado',
  `valor_movimiento` decimal(11,2) NOT NULL COMMENT 'Valor diario del registro',
  `codigo_contable` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo de la cuenta contable segun el PUC',
  `sentido` enum('D','C') collate latin1_spanish_ci NOT NULL COMMENT 'D->Debito C->Credito',
  `codigo_empresa_auxiliar` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa para la llave de auxiliares',
  `codigo_anexo_contable` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo del anexo que permite dividir las cuentas',
  `codigo_auxiliar_contable` int(8) unsigned zerofill NOT NULL COMMENT 'Codigo donde se acumulara la informacion',
  `codigo_tipo_documento` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo del tipo de documento',
  `documento_identidad_tercero` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Número del documento de identidad',
  `fecha_consecutivo` date NOT NULL COMMENT 'Fecha de generacion del consecutivo documento',
  `consecutivo` int(9) unsigned zerofill NOT NULL COMMENT 'Consecutivo del documento',
  `fecha_registro` datetime NOT NULL COMMENT 'Fecha en que se genera el registro',
  `codigo_usuario_registra` smallint(4) unsigned zerofill NOT NULL COMMENT 'Codigo del usuario que genera el registro',
  `pagada` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT 'Pagada 0->No 1->Si',
  PRIMARY KEY  (`codigo_sucursal`,`ano_generacion`,`mes_generacion`,`codigo_planilla`,`periodo_pago`),
  KEY `forma_pago_planillas_nomina_planilla` (`codigo_planilla`),
  KEY `forma_pago_planillas_nomina_auxiliar_contable` (`codigo_empresa_auxiliar`,`codigo_anexo_contable`,`codigo_auxiliar_contable`),
  KEY `forma_pago_planillas_nomina_plan_contable` (`codigo_contable`),
  KEY `forma_pago_planillas_nomina_consecutivo` (`codigo_sucursal`,`codigo_tipo_documento`,`documento_identidad_tercero`,`fecha_consecutivo`,`consecutivo`),
  KEY `forma_pago_planillas_nomina_usuario` (`codigo_usuario_registra`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_forma_pago_planillas_nomina`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_forma_pago_planillas_sucursal`
-- 

DROP TABLE IF EXISTS `job_forma_pago_planillas_sucursal`;
CREATE TABLE `job_forma_pago_planillas_sucursal` (
  `codigo_sucursal` mediumint(5) unsigned zerofill NOT NULL COMMENT 'tabla planilla de la tabla de sucursales',
  `ano_generacion` int(4) unsigned zerofill NOT NULL COMMENT 'Código interno que identifica la planilla',
  `mes_generacion` int(2) unsigned zerofill NOT NULL COMMENT 'Código interno que identifica la planilla',
  `codigo_planilla` smallint(3) unsigned zerofill NOT NULL COMMENT 'Código interno que identifica la planilla',
  `periodo_pago` enum('1','2','3','4','5','6','7','8','9') collate latin1_spanish_ci NOT NULL COMMENT '1->Mensual 2->Primera quincena 3->Segunda quincena 4->Primera semana 5->Segunda semana 6->Tercer semana 7->Cuarta semana 8->Quinta semana 9->Fecha unica',
  `fecha_pago_planilla` date NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado',
  `valor_movimiento` decimal(11,2) NOT NULL COMMENT 'Valor diario del registro',
  `codigo_contable` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo de la cuenta contable segun el PUC',
  `sentido` enum('D','C') collate latin1_spanish_ci NOT NULL COMMENT 'D->Debito C->Credito',
  `codigo_empresa_auxiliar` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa para la llave de auxiliares',
  `codigo_anexo_contable` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo del anexo que permite dividir las cuentas',
  `codigo_auxiliar_contable` int(8) unsigned zerofill NOT NULL COMMENT 'Codigo donde se acumulara la informacion',
  `codigo_tipo_documento` smallint(3) unsigned zerofill NOT NULL COMMENT 'Código asignado por el usuario',
  `codigo_sucursal_banco` smallint(2) unsigned zerofill NOT NULL COMMENT 'codigo de la tabla bancos',
  `codigo_iso` varchar(2) collate latin1_spanish_ci NOT NULL COMMENT 'Llave principal de la tabla paises',
  `codigo_dane_departamento` varchar(2) collate latin1_spanish_ci NOT NULL COMMENT 'Código DANE',
  `codigo_dane_municipio` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'Código DANE',
  `codigo_banco` smallint(2) unsigned zerofill NOT NULL COMMENT 'id de la tabla bancos',
  `numero_cuenta` varchar(30) collate latin1_spanish_ci NOT NULL COMMENT 'Numero de la cuenta',
  `documento_identidad_tercero` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Número del documento de identidad',
  `fecha_consecutivo` date NOT NULL COMMENT 'Fecha de generacion del consecutivo documento',
  `consecutivo_documento` int(9) unsigned zerofill NOT NULL COMMENT 'Consecutivo del documento',
  `consecutivo_cheque` int(9) unsigned zerofill NOT NULL COMMENT 'consecutivo del cheque',
  `fecha_registro` datetime NOT NULL COMMENT 'Fecha en que se genera el registro',
  `codigo_usuario_registra` smallint(4) unsigned zerofill NOT NULL COMMENT 'Codigo del usuario que genera el registro',
  `pagada` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT 'Pagada 0->No 1->Si',
  PRIMARY KEY  (`codigo_sucursal`,`ano_generacion`,`mes_generacion`,`codigo_planilla`,`periodo_pago`),
  KEY `forma_pago_planillas_sucursal_planilla` (`codigo_planilla`),
  KEY `forma_pago_planillas_sucursal_cuentas_bancarias` (`codigo_sucursal`,`codigo_tipo_documento`,`codigo_sucursal_banco`,`codigo_iso`,`codigo_dane_departamento`,`codigo_dane_municipio`,`codigo_banco`,`numero_cuenta`),
  KEY `forma_pago_planillas_sucursal_consecutivo_documento` (`codigo_sucursal`,`codigo_tipo_documento`,`documento_identidad_tercero`,`fecha_consecutivo`,`consecutivo_documento`),
  KEY `forma_pago_planillas_sucursal_auxiliar_contable` (`codigo_empresa_auxiliar`,`codigo_anexo_contable`,`codigo_auxiliar_contable`),
  KEY `forma_pago_planillas_sucursal_plan_contable` (`codigo_contable`),
  KEY `forma_pago_planillas_sucursal_consecutivo_cheque` (`codigo_sucursal`,`codigo_tipo_documento`,`codigo_banco`,`numero_cuenta`,`consecutivo_cheque`),
  KEY `forma_pago_planillas_sucursal_usuario` (`codigo_usuario_registra`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_forma_pago_planillas_sucursal`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_formatos_dian`
-- 

DROP TABLE IF EXISTS `job_formatos_dian`;
CREATE TABLE `job_formatos_dian` (
  `codigo` smallint(4) unsigned zerofill NOT NULL COMMENT 'Código asignado por la DIAN',
  `descripcion` varchar(255) collate latin1_spanish_ci NOT NULL COMMENT 'Detalle que describe el formato DIAN',
  PRIMARY KEY  (`codigo`),
  UNIQUE KEY `descripcion` (`descripcion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_formatos_dian`
-- 

INSERT INTO `job_formatos_dian` (`codigo`, `descripcion`) VALUES 
(0000, '');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_gastos_prestaciones_sociales`
-- 

DROP TABLE IF EXISTS `job_gastos_prestaciones_sociales`;
CREATE TABLE `job_gastos_prestaciones_sociales` (
  `codigo` int(5) unsigned zerofill NOT NULL COMMENT 'Codigo del registro',
  `descripcion` varchar(255) collate latin1_spanish_ci NOT NULL COMMENT 'Descripcion del registro',
  `cesantia_pago_prestacion` int(8) unsigned zerofill NOT NULL default '00000000' COMMENT 'Codigo de la transaccion contable',
  `cesantia_pago_gasto` int(8) unsigned zerofill NOT NULL default '00000000' COMMENT 'Codigo de la transaccion contable',
  `cesantia_traslado_fondo` int(8) unsigned zerofill NOT NULL default '00000000' COMMENT 'Codigo de la transaccion contable',
  `cesantia_causacion_prestacion` int(8) unsigned zerofill NOT NULL default '00000000' COMMENT 'Codigo de la transaccion contable',
  `cesantia_causacion_gasto` int(8) unsigned zerofill NOT NULL default '00000000' COMMENT 'Codigo de la transaccion contable',
  `intereses_pago_prestacion` int(8) unsigned zerofill NOT NULL default '00000000' COMMENT 'Codigo de la transaccion contable',
  `intereses_pago_gasto` int(8) unsigned zerofill NOT NULL default '00000000' COMMENT 'Codigo de la transaccion contable',
  `intereses_causacion_prestacion` int(8) unsigned zerofill NOT NULL default '00000000' COMMENT 'Codigo de la transaccion contable',
  `intereses_causacion_gasto` int(8) unsigned zerofill NOT NULL default '00000000' COMMENT 'Codigo de la transaccion contable',
  `prima_pago_prestacion` int(8) unsigned zerofill NOT NULL default '00000000' COMMENT 'Codigo de la transaccion contable',
  `prima_pago_gasto` int(8) unsigned zerofill NOT NULL default '00000000' COMMENT 'Codigo de la transaccion contable',
  `prima_causacion_prestacion` int(8) unsigned zerofill NOT NULL default '00000000' COMMENT 'Codigo de la transaccion contable',
  `prima_causacion_gasto` int(8) unsigned zerofill NOT NULL default '00000000' COMMENT 'Codigo de la transaccion contable',
  `vacacion_pago_prestacion_disfrute` int(8) unsigned zerofill NOT NULL default '00000000' COMMENT 'Codigo de la transaccion contable',
  `vacacion_pago_prestacion_liquidacion` int(8) unsigned zerofill NOT NULL default '00000000' COMMENT 'Codigo de la transaccion contable',
  `vacacion_pago_gasto_disfrute` int(8) unsigned zerofill NOT NULL default '00000000' COMMENT 'Codigo de la transaccion contable',
  `vacacion_pago_gasto_liquidacion` int(8) unsigned zerofill NOT NULL default '00000000' COMMENT 'Codigo de la transaccion contable',
  `vacacion_causacion_prestacion` int(8) unsigned zerofill NOT NULL default '00000000' COMMENT 'Codigo de la transaccion contable',
  `vacacion_causacion_gasto` int(8) unsigned zerofill NOT NULL default '00000000' COMMENT 'Codigo de la transaccion contable',
  PRIMARY KEY  (`codigo`),
  UNIQUE KEY `descripcion` (`descripcion`),
  KEY `gastos_prestaciones_sociales_transacciones_contables_empleado_1` (`cesantia_pago_prestacion`),
  KEY `gastos_prestaciones_sociales_transacciones_contables_empleado_2` (`cesantia_pago_gasto`),
  KEY `gastos_prestaciones_sociales_transacciones_contables_empleado_3` (`cesantia_traslado_fondo`),
  KEY `gastos_prestaciones_sociales_transacciones_contables_empleado_4` (`cesantia_causacion_prestacion`),
  KEY `gastos_prestaciones_sociales_transacciones_contables_empleado_5` (`cesantia_causacion_gasto`),
  KEY `gastos_prestaciones_sociales_transacciones_contables_empleado_6` (`prima_pago_prestacion`),
  KEY `gastos_prestaciones_sociales_transacciones_contables_empleado_7` (`prima_pago_gasto`),
  KEY `gastos_prestaciones_sociales_transacciones_contables_empleado_8` (`prima_causacion_prestacion`),
  KEY `gastos_prestaciones_sociales_transacciones_contables_empleado_9` (`prima_causacion_gasto`),
  KEY `gastos_prestaciones_transacciones_contables_10_1` (`vacacion_pago_prestacion_disfrute`),
  KEY `gastos_prestaciones_transacciones_contables_10_2` (`vacacion_pago_prestacion_liquidacion`),
  KEY `gastos_prestaciones_transacciones_contables_11_1` (`vacacion_pago_gasto_disfrute`),
  KEY `gastos_prestaciones_transacciones_contables_11_2` (`vacacion_pago_gasto_liquidacion`),
  KEY `gastos_prestaciones_sociales_transacciones_contables_empleado_12` (`vacacion_causacion_prestacion`),
  KEY `gastos_prestaciones_sociales_transacciones_contables_empleado_13` (`vacacion_causacion_gasto`),
  KEY `gastos_prestaciones_sociales_transacciones_contables_empleado_14` (`intereses_pago_prestacion`),
  KEY `gastos_prestaciones_sociales_transacciones_contables_empleado_15` (`intereses_pago_gasto`),
  KEY `gastos_prestaciones_sociales_transacciones_contables_empleado_16` (`intereses_causacion_prestacion`),
  KEY `gastos_prestaciones_sociales_transacciones_contables_empleado_17` (`intereses_causacion_gasto`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_gastos_prestaciones_sociales`
-- 

INSERT INTO `job_gastos_prestaciones_sociales` (`codigo`, `descripcion`, `cesantia_pago_prestacion`, `cesantia_pago_gasto`, `cesantia_traslado_fondo`, `cesantia_causacion_prestacion`, `cesantia_causacion_gasto`, `intereses_pago_prestacion`, `intereses_pago_gasto`, `intereses_causacion_prestacion`, `intereses_causacion_gasto`, `prima_pago_prestacion`, `prima_pago_gasto`, `prima_causacion_prestacion`, `prima_causacion_gasto`, `vacacion_pago_prestacion_disfrute`, `vacacion_pago_prestacion_liquidacion`, `vacacion_pago_gasto_disfrute`, `vacacion_pago_gasto_liquidacion`, `vacacion_causacion_prestacion`, `vacacion_causacion_gasto`) VALUES 
(00000, '', 00000000, 00000000, 00000000, 00000000, 00000000, 00000000, 00000000, 00000000, 00000000, 00000000, 00000000, 00000000, 00000000, 00000000, 00000000, 00000000, 00000000, 00000000, 00000000);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_grupos`
-- 

DROP TABLE IF EXISTS `job_grupos`;
CREATE TABLE `job_grupos` (
  `codigo` smallint(4) unsigned zerofill NOT NULL default '0000' COMMENT 'Código utilizado por la empresa',
  `descripcion` varchar(255) collate latin1_spanish_ci NOT NULL default '' COMMENT 'Descripcin del grupo',
  `orden` int(5) NOT NULL COMMENT 'Orden asignado por el usuario',
  PRIMARY KEY  (`codigo`),
  UNIQUE KEY `codigo` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_grupos`
-- 

INSERT INTO `job_grupos` (`codigo`, `descripcion`, `orden`) VALUES 
(0000, '', 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_idiomas`
-- 

DROP TABLE IF EXISTS `job_idiomas`;
CREATE TABLE `job_idiomas` (
  `codigo` int(3) unsigned zerofill NOT NULL COMMENT 'Descripcion que identifica el idioma',
  `descripcion` varchar(255) collate latin1_spanish_ci NOT NULL COMMENT 'Detalle que describe el idioma ',
  PRIMARY KEY  (`codigo`),
  UNIQUE KEY `descripcion` (`descripcion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_idiomas`
-- 

INSERT INTO `job_idiomas` (`codigo`, `descripcion`) VALUES 
(000, '');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_idiomas_aspirante`
-- 

DROP TABLE IF EXISTS `job_idiomas_aspirante`;
CREATE TABLE `job_idiomas_aspirante` (
  `documento_identidad_aspirante` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo que identifica el tercero',
  `consecutivo` int(8) NOT NULL COMMENT 'Consecutivo interno para la base de datos',
  `codigo_idioma` int(3) unsigned zerofill NOT NULL COMMENT 'Descripcion que identifica el idioma',
  `habla` enum('1','2','3','4') collate latin1_spanish_ci default NULL COMMENT '1->No aplica 2->Regular 3->Bien 4->Excelente',
  `escritura` enum('1','2','3','4') collate latin1_spanish_ci default NULL COMMENT '1->No aplica 2->Regular 3->Bien 4->Excelente',
  `lectura` enum('1','2','3','4') collate latin1_spanish_ci default NULL COMMENT '1->No aplica 2->Regular 3->Bien 4->Excelente',
  PRIMARY KEY  (`documento_identidad_aspirante`,`consecutivo`),
  UNIQUE KEY `documento_identidad_aspirante` (`documento_identidad_aspirante`,`codigo_idioma`),
  KEY `idioma_aspirante_idiomas` (`codigo_idioma`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_idiomas_aspirante`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_imagenes`
-- 

DROP TABLE IF EXISTS `job_imagenes`;
CREATE TABLE `job_imagenes` (
  `id_asociado` int(9) unsigned zerofill NOT NULL COMMENT 'Llave primaria de la tabla asociada segÃºn la categorÃ­a',
  `categoria` enum('1','2','3') collate latin1_spanish_ci NOT NULL COMMENT 'Clase de imagen: 1->Usuarios, 2->ArtÃ­culos, 3->Firma digital',
  `contenido` mediumblob NOT NULL COMMENT 'Lista de valores (datos) de las columnas',
  `tipo` varchar(255) collate latin1_spanish_ci NOT NULL COMMENT 'TIpo de archivo (MIME)',
  `extension` enum('png','jpg','gif') collate latin1_spanish_ci NOT NULL COMMENT 'ExtensiÃ³n que determina el tipo de imagen',
  `ancho` smallint(4) NOT NULL COMMENT 'Ancho de la imagen en pixeles',
  `alto` smallint(4) NOT NULL COMMENT 'Alto de la imagen en pixeles',
  PRIMARY KEY  (`id_asociado`,`categoria`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_imagenes`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_ingreso_empleados`
-- 

DROP TABLE IF EXISTS `job_ingreso_empleados`;
CREATE TABLE `job_ingreso_empleados` (
  `codigo_empresa` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa donde va a laborar',
  `documento_identidad_empleado` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Documento de identidad empleado en la tabla aspirantes',
  `fecha_ingreso` date NOT NULL COMMENT 'Fecha en la que inicia labores el empleado',
  `fecha_vencimiento_contrato` date default NULL COMMENT 'Fecha en la cual se termina el contrato',
  `fecha_retiro` date default NULL COMMENT 'Fecha en la cual es retiraddo de la empresa',
  `codigo_motivo_retiro` smallint(4) unsigned zerofill NOT NULL COMMENT 'Codigo del motivo del retiro',
  `riesgo_profesional` decimal(7,4) NOT NULL COMMENT 'Porcentaje para la liquidaciÃ³n de riesgos profesionales',
  `manejo_auxilio_transporte` enum('1','2','3','4','5') collate latin1_spanish_ci NOT NULL default '1' COMMENT '1->Pago por ley con descuentos 2->Pago por ley sin descuentos 3->Pago mayor a dos SMLV con descuentos 4->Pago mayor a dos SMLV sin descuentos 5-> No recibe auxilio de transporte',
  `estado` enum('1','2') collate latin1_spanish_ci NOT NULL default '1' COMMENT 'Estado del empleado 1->Activo 2->Retirado',
  `codigo_sucursal_activo` mediumint(5) unsigned zerofill NOT NULL COMMENT 'Codigo del la sucursal actual de trabajo',
  PRIMARY KEY  (`codigo_empresa`,`documento_identidad_empleado`,`fecha_ingreso`),
  KEY `ingreso_empleado_documento_identidad` (`documento_identidad_empleado`),
  KEY `ingreso_empleado_motivo_retiro` (`codigo_motivo_retiro`),
  KEY `sucursal_contrato_empleado_actual` (`codigo_sucursal_activo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_ingreso_empleados`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_ingresos_varios_empleados`
-- 

DROP TABLE IF EXISTS `job_ingresos_varios_empleados`;
CREATE TABLE `job_ingresos_varios_empleados` (
  `codigo_empresa` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa donde va a laborar',
  `documento_identidad_empleado` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Documento de identidad empleado en la tabla aspirantes',
  `fecha_ingreso` date NOT NULL COMMENT 'Fecha en la que inicia labores el empleado',
  `codigo_transaccion_tiempo` int(8) unsigned zerofill NOT NULL COMMENT 'Consecutivo interno para la base de datos',
  `fecha_inicio_transacion_tiempo` date NOT NULL COMMENT 'Fecha en la que la transaccion de tiempo',
  `fecha_final_transacion_tiempo` date NOT NULL COMMENT 'Fecha en la que se quita la transacion de tiempo',
  `estado` enum('0','1') collate latin1_spanish_ci default '1' COMMENT 'Estado de la transacion de tiempo 0-> Inativa 1->Activa',
  `periodo_pago` enum('1','2','3','4') collate latin1_spanish_ci default '1' COMMENT 'Estado de la transacion de tiempo 1-> Proporcional 2-> segunda quincena 3-> Mensual 4-> Semanal',
  `valor` decimal(11,2) NOT NULL default '0.00' COMMENT 'Valor del ingreso vario que recibira el empleado',
  PRIMARY KEY  (`codigo_empresa`,`documento_identidad_empleado`,`fecha_ingreso`,`fecha_inicio_transacion_tiempo`,`codigo_transaccion_tiempo`),
  KEY `ingresos_varios_empleados_codigo_transaccion_contable` (`codigo_transaccion_tiempo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_ingresos_varios_empleados`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_items_movimientos_contables`
-- 

DROP TABLE IF EXISTS `job_items_movimientos_contables`;
CREATE TABLE `job_items_movimientos_contables` (
  `codigo_sucursal` mediumint(5) unsigned zerofill NOT NULL COMMENT 'Codigo de la sucursal donse se genera el movimiento',
  `documento_identidad_tercero` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Número del documento de identidad',
  `codigo_tipo_comprobante` smallint(2) unsigned zerofill NOT NULL COMMENT 'Codigo del tipo de comprobante',
  `numero_comprobante` varchar(20) collate latin1_spanish_ci NOT NULL COMMENT 'Número de comprobante contable',
  `codigo_tipo_documento` smallint(3) unsigned zerofill NOT NULL COMMENT 'Id tabla consecutivo de documentos',
  `consecutivo_documento` int(8) unsigned zerofill NOT NULL COMMENT 'Consecutivo del documento',
  `fecha_contabilizacion` date NOT NULL COMMENT 'Fecha en que se contabiliza la transaccion',
  `consecutivo` int(9) unsigned zerofill NOT NULL COMMENT 'Consecutivo interno para la base de datos',
  `codigo_sucursal_contabiliza` mediumint(5) unsigned zerofill NOT NULL COMMENT 'Codigo de la sucursal donse se contabiliza el movimiento',
  `codigo_plan_contable` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo de la cuenta contable segun el PUC',
  `codigo_empresa_auxiliar` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa para la llave de auxiliares',
  `codigo_anexo_contable` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo del anexo para la llave de auxiliares',
  `codigo_auxiliar_contable` int(8) unsigned zerofill NOT NULL COMMENT 'Código donde se acumulara la información',
  `documento_identidad_tercero_saldo` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Número del documento de identidad del tercero que afecta la cuenta si aplica',
  `sentido` enum('D','C') collate latin1_spanish_ci default NULL COMMENT 'Sentido del movimiento contable: D->Debito, C->Credito',
  `valor` int(9) unsigned NOT NULL default '0' COMMENT 'Valor contable del movimiento',
  `valor_base1` int(9) unsigned NOT NULL default '0' COMMENT 'Valor con el que se liquida la transaccion para las cuentas del iva y retefuente',
  `valor_base2` int(9) unsigned NOT NULL default '0' COMMENT 'Valor con el que se liquida la transaccion para las cuentas de reteiva y reteica',
  `codigo_tipo_documento_soporte` smallint(3) unsigned zerofill NOT NULL default '000' COMMENT 'Consecutivo interno para la base de datos del tipo de documento de soporte de la transaccion',
  `numero_documento_soporte` varchar(15) collate latin1_spanish_ci default NULL COMMENT 'Numero de documento de soporte con el que se realiza la transaccion',
  `codigo_tipo_documento_bancario` smallint(4) unsigned zerofill NOT NULL default '0000' COMMENT 'Consecutivo interno para la base de datos del tipo de documento bancario de la transaccion, si aplica',
  `numero_documento_bancario` varchar(15) collate latin1_spanish_ci default NULL COMMENT 'Numero de documento bancario con el que se realiza la transaccion, si aplica',
  `documento_identidad_tercero_fiador1` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Número del documento de identidad del tercero1 si aplica',
  `documento_identidad_tercero_fiador2` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Número del documento de identidad del tercero2 si aplica',
  `codigo_sucursal_cheque` mediumint(5) unsigned zerofill NOT NULL COMMENT 'Codigo de la sucursal a la que pertenece la cuenta',
  `codigo_tipo_documento_cheque` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo del tipo de documento',
  `codigo_banco_cheque` smallint(2) unsigned zerofill NOT NULL COMMENT 'id de la tabla bancos',
  `numero_cheque` varchar(30) collate latin1_spanish_ci NOT NULL COMMENT 'Numero de la cuenta',
  `consecutivo_cheque` int(9) unsigned zerofill NOT NULL COMMENT 'Numero de cheque',
  PRIMARY KEY  (`codigo_sucursal`,`documento_identidad_tercero`,`codigo_tipo_comprobante`,`numero_comprobante`,`codigo_tipo_documento`,`consecutivo_documento`,`fecha_contabilizacion`,`consecutivo`),
  KEY `items_movimientos_contables_contabiliza` (`codigo_sucursal_contabiliza`),
  KEY `items_movimientos_contables_cuenta` (`codigo_plan_contable`),
  KEY `items_movimientos_contables_auxiliar` (`codigo_empresa_auxiliar`,`codigo_anexo_contable`,`codigo_auxiliar_contable`),
  KEY `items_movimientos_contables_tercero_cuenta` (`documento_identidad_tercero`),
  KEY `items_movimientos_contables_documento_soporte` (`codigo_tipo_documento_soporte`),
  KEY `items_movimientos_contables_documento_bancario` (`codigo_tipo_documento_bancario`),
  KEY `items_movimientos_contables_fiador1` (`documento_identidad_tercero_fiador1`),
  KEY `items_movimientos_contables_fiador2` (`documento_identidad_tercero_fiador2`),
  KEY `items_movimientos_contables_consecutivo_cheques` (`codigo_sucursal_cheque`,`codigo_tipo_documento_cheque`,`codigo_banco_cheque`,`numero_cheque`,`consecutivo_cheque`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_items_movimientos_contables`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_liquidaciones_empleado`
-- 

DROP TABLE IF EXISTS `job_liquidaciones_empleado`;
CREATE TABLE `job_liquidaciones_empleado` (
  `fecha_generacion` datetime NOT NULL COMMENT 'Fecha en la se genero el retiro',
  `fecha_liquidacion` date NOT NULL COMMENT 'Fecha en la hasta donde se va a liquidar',
  `fecha_contabilizacion` date NOT NULL COMMENT 'Fecha en la se generara la contabilizacion',
  `codigo_empresa` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa donde va a laborar',
  `documento_identidad_empleado` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Documento de identidad empleado en la sucursales contrato empleados',
  `fecha_ingreso` date NOT NULL COMMENT 'Fecha en la que ingreso el empleado a la empresa',
  `codigo_sucursal` mediumint(5) unsigned zerofill NOT NULL COMMENT 'Id de la tabla de sucursales',
  `fecha_ingreso_sucursal` date NOT NULL COMMENT 'Fecha en la que inicia labores en la sucursal el empleado',
  `fecha_generacion_consecutivo` date NOT NULL COMMENT 'Fecha de genracion del consecutico',
  `codigo_tipo_documento` smallint(3) unsigned zerofill NOT NULL COMMENT 'Código asignado por el usuario',
  `consecutivo_documento` int(9) unsigned zerofill NOT NULL COMMENT 'Consecutivo del documento',
  `codigo_empresa_auxiliar` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa para la llave de auxiliares',
  `codigo_anexo_contable` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'Código del anexo que permite dividir las cuentas',
  `codigo_auxiliar_contable` int(8) unsigned zerofill NOT NULL COMMENT 'Código donde se acumulara la información',
  `fecha_inicio_cesantias` date NOT NULL COMMENT 'Fecha en donde se empieza a calcular el valor a pagar',
  `fecha_final_cesantias` date NOT NULL COMMENT 'Fecha en donde se empieza a calcular el valor a pagar',
  `dias_liquidados_cesantias` smallint(3) NOT NULL COMMENT 'Dias laborados en el periodo',
  `salario_base_cesantias` decimal(11,2) NOT NULL COMMENT 'Valor con el que se va a liquidar el movimiento',
  `periodo_pago_cesantias` smallint(3) NOT NULL COMMENT 'Dias laborados en el periodo',
  `fecha_inicio_interes_cesantias` date NOT NULL COMMENT 'Fecha en donde se empieza a calcular el valor a pagar',
  `fecha_final_interes_cesantias` date NOT NULL COMMENT 'Fecha en donde se empieza a calcular el valor a pagar',
  `dias_liquidados_interes_cesantias` smallint(3) NOT NULL COMMENT 'Dias laborados en el periodo',
  `salario_base_interes_cesantias` decimal(11,2) NOT NULL COMMENT 'Valor con el que se va a liquidar el movimiento',
  `periodo_pago_interes_cesantias` smallint(3) NOT NULL COMMENT 'Dias laborados en el periodo',
  `fecha_inicio_primas` date NOT NULL COMMENT 'Fecha en donde se empieza a calcular el valor a pagar',
  `fecha_final_primas` date NOT NULL COMMENT 'Fecha en donde se empieza a calcular el valor a pagar',
  `dias_liquidados_primas` smallint(3) NOT NULL COMMENT 'Dias laborados en el periodo',
  `salario_base_primas` decimal(11,2) NOT NULL COMMENT 'Valor con el que se va a liquidar el movimiento',
  `periodo_pago_primas` smallint(3) NOT NULL COMMENT 'Dias laborados en el periodo',
  `fecha_inicio_vacaciones` date NOT NULL COMMENT 'Fecha en donde se empieza a calcular el valor a pagar',
  `fecha_final_vacaciones` date NOT NULL COMMENT 'Fecha en donde se empieza a calcular el valor a pagar',
  `dias_liquidados_vacaciones` smallint(3) NOT NULL COMMENT 'Dias laborados en el periodo',
  `salario_base_vacaciones` decimal(11,2) NOT NULL COMMENT 'Valor con el que se va a liquidar el movimiento',
  `periodo_pago_vacaciones` smallint(3) NOT NULL COMMENT 'Dias laborados en el periodo',
  `motivo_retiro` smallint(4) unsigned zerofill NOT NULL COMMENT 'Código que identifica el concepto de prestamo',
  `observaciones` varchar(500) collate latin1_spanish_ci NOT NULL COMMENT 'Descripción del prestamo',
  `autorizado` enum('0','1') collate latin1_spanish_ci NOT NULL COMMENT '0 -> No 1 -> Si',
  `pagado` enum('0','1') collate latin1_spanish_ci NOT NULL COMMENT '0 -> No 1 -> Si',
  `codigo_usuario_registra` smallint(4) unsigned zerofill NOT NULL COMMENT 'Codigo del Usuario que genera el registro',
  `codigo_usuario_modifica` smallint(4) unsigned zerofill NOT NULL COMMENT 'Codigo del ultimo Usuario que modifica el registro',
  PRIMARY KEY  (`codigo_empresa`,`documento_identidad_empleado`,`fecha_generacion`,`motivo_retiro`),
  KEY `liquidaciones_empleado_sucursal_contrato` (`codigo_empresa`,`documento_identidad_empleado`,`fecha_ingreso`,`codigo_sucursal`,`fecha_ingreso_sucursal`),
  KEY `liquidaciones_empleado_tipo_documento` (`codigo_tipo_documento`),
  KEY `liquidaciones_empleado_auxiliar_contable` (`codigo_empresa_auxiliar`,`codigo_anexo_contable`,`codigo_auxiliar_contable`),
  KEY `liquidaciones_empleado_consecutivo_documentos` (`codigo_sucursal`,`codigo_tipo_documento`,`documento_identidad_empleado`,`fecha_generacion_consecutivo`,`consecutivo_documento`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_liquidaciones_empleado`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_liquidaciones_movimientos_auxilio_transporte`
-- 

DROP TABLE IF EXISTS `job_liquidaciones_movimientos_auxilio_transporte`;
CREATE TABLE `job_liquidaciones_movimientos_auxilio_transporte` (
  `codigo_empresa` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa donde va a laborar',
  `documento_identidad_empleado` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Documento de identidad empleado en la sucursales contrato empleados',
  `fecha_generacion` datetime NOT NULL COMMENT 'Fecha en la se genero el retiro',
  `motivo_retiro` smallint(4) unsigned zerofill NOT NULL COMMENT 'Código que identifica el concepto de prestamo',
  `codigo_transaccion_contable` int(8) unsigned zerofill NOT NULL COMMENT 'Id de la tabla de transacciones contables',
  `fecha_inicio_pago` date NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado',
  `fecha_hasta_pago` date NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado',
  `codigo_empresa_auxiliar` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa del auxiliar contable',
  `codigo_anexo_contable` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'Cédigo del anexo que permite dividir las cuentas',
  `codigo_auxiliar_contable` int(8) unsigned zerofill NOT NULL COMMENT 'CÃ³digo donde se acumulara la informaciÃ³n',
  `codigo_contable` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo contable de la transaccion contable',
  `sentido` enum('D','C') collate latin1_spanish_ci NOT NULL COMMENT 'D->Débito C->Crédito',
  `dias_trabajados` smallint(3) NOT NULL COMMENT 'Dias laborados en el periodo',
  `dias_auxilio` smallint(3) NOT NULL COMMENT 'Dias que se cancelan de auxlio de trasnporte',
  `salario_mensual` decimal(11,2) NOT NULL COMMENT 'Valor mensual del salario',
  `valor_movimiento` decimal(11,2) NOT NULL COMMENT 'Valor diario del registro',
  PRIMARY KEY  (`codigo_empresa`,`documento_identidad_empleado`,`fecha_generacion`,`motivo_retiro`,`codigo_transaccion_contable`),
  KEY `liquidaciones_movimientos_auxilio_transaccion` (`codigo_transaccion_contable`),
  KEY `liquidaciones_auxilio_anexo` (`codigo_anexo_contable`),
  KEY `liquidaciones_salario_auxilio_contable` (`codigo_empresa_auxiliar`,`codigo_anexo_contable`,`codigo_auxiliar_contable`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_liquidaciones_movimientos_auxilio_transporte`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_liquidaciones_movimientos_conceptos_vacaciones`
-- 

DROP TABLE IF EXISTS `job_liquidaciones_movimientos_conceptos_vacaciones`;
CREATE TABLE `job_liquidaciones_movimientos_conceptos_vacaciones` (
  `concepto` enum('1','2','3','4') collate latin1_spanish_ci NOT NULL COMMENT '1->Salarios 2->Auxilio de transporte 3->Salud  4->Pension',
  `estado_liquidacion` enum('1','2','3','4') collate latin1_spanish_ci NOT NULL COMMENT '1->Activa  2->Denegada 3->Autorizada para pagar 4->Pagada',
  `fecha_inicio_tiempo` date NOT NULL COMMENT 'Fecha inicio en que inicia las vacaciones',
  `codigo_empresa` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa donde va a laborar',
  `documento_identidad_empleado` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Documento de identidad empleado en la sucursales contrato empleados',
  `fecha_generacion` datetime NOT NULL COMMENT 'Fecha en la se genero el retiro',
  `codigo_transaccion_contable` int(8) unsigned zerofill NOT NULL COMMENT 'Id de la tabla de transacciones contables',
  `fecha_ingreso` date NOT NULL COMMENT 'Fecha en la que inicia labores el empleado',
  `codigo_sucursal` mediumint(5) unsigned zerofill NOT NULL COMMENT 'Codigo de la sucursal donde labora el empleado',
  `fecha_ingreso_sucursal` date NOT NULL COMMENT 'Fecha en la que inicia labores en la sucursal el empleado',
  `fecha_inicio_pago` date NOT NULL COMMENT 'Fecha en la que inicia el calculo del movimiento',
  `fecha_hasta_pago` date NOT NULL COMMENT 'Fecha en la que final del calculo del movimiento',
  `codigo_empresa_auxiliar` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa del auxiliar contable',
  `codigo_anexo_contable` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'Código del anexo que permite dividir las cuentas',
  `codigo_auxiliar_contable` int(8) unsigned zerofill NOT NULL COMMENT 'Código donde se acumulara la información',
  `codigo_contable` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo contable de la transaccion contable',
  `sentido` enum('D','C') collate latin1_spanish_ci NOT NULL COMMENT 'D->DÃ©bito C->CrÃ©dito',
  `dias_trabajados` smallint(3) NOT NULL COMMENT 'Dias laborados en el periodo',
  `salario_mensual` decimal(11,2) NOT NULL COMMENT 'Valor mensual del salario',
  `valor_movimiento` decimal(11,2) NOT NULL COMMENT 'Valor diario del registro',
  `ibc` decimal(11,2) NOT NULL COMMENT 'Solo se registra si el concepto es salud o pension',
  `porcentaje_tasa` decimal(7,4) NOT NULL default '0.0000' COMMENT 'Porcentaje de la tasa, si el concepto es salud o pension',
  `codigo_usuario_registra` smallint(4) unsigned zerofill NOT NULL COMMENT 'Codigo del Usuario que genera el registro',
  `codigo_usuario_modifica` smallint(4) unsigned zerofill default NULL COMMENT 'Codigo del ultimo Usuario que modifica el registro',
  PRIMARY KEY  (`codigo_sucursal`,`documento_identidad_empleado`,`fecha_inicio_tiempo`,`codigo_transaccion_contable`),
  KEY `liquidaciones_movimientos_conceptos_sucursal_contrato` (`codigo_empresa`,`documento_identidad_empleado`,`fecha_ingreso`,`codigo_sucursal`,`fecha_ingreso_sucursal`),
  KEY `liquidaciones_movimientos_conceptos_transaccion_contable` (`codigo_transaccion_contable`),
  KEY `liquidaciones_movimientos_conceptos_plan_contable` (`codigo_contable`),
  KEY `liquidaciones_movimientos_conceptos_usuarios_registra` (`codigo_usuario_registra`),
  KEY `liquidaciones_movimientos_conceptos_usuarios_modifica` (`codigo_usuario_modifica`),
  KEY `liquidaciones_movimientos_conceptos_anexo` (`codigo_anexo_contable`),
  KEY `mliquidaciones_movimientos_conceptos_contable` (`codigo_empresa_auxiliar`,`codigo_anexo_contable`,`codigo_auxiliar_contable`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_liquidaciones_movimientos_conceptos_vacaciones`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_liquidaciones_movimientos_pension`
-- 

DROP TABLE IF EXISTS `job_liquidaciones_movimientos_pension`;
CREATE TABLE `job_liquidaciones_movimientos_pension` (
  `codigo_empresa` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa donde va a laborar',
  `documento_identidad_empleado` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Documento de identidad empleado en la sucursales contrato empleados',
  `fecha_generacion` datetime NOT NULL COMMENT 'Fecha en la se genero el retiro',
  `motivo_retiro` smallint(4) unsigned zerofill NOT NULL COMMENT 'Código que identifica el concepto de prestamo',
  `codigo_transaccion_contable` int(8) unsigned zerofill NOT NULL COMMENT 'Id de la tabla de transacciones contables',
  `fecha_inicio_pago` date NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado',
  `fecha_hasta_pago` date NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado',
  `codigo_empresa_auxiliar` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa del auxiliar contable',
  `codigo_anexo_contable` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo del anexo que permite dividir las cuentas',
  `codigo_auxiliar_contable` int(8) unsigned zerofill NOT NULL COMMENT 'Codigo donde se acumulara la informacion',
  `codigo_entidad_pension` int(8) unsigned zerofill NOT NULL COMMENT 'Codigo de la entidad parafiscal',
  `codigo_contable` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo contable de la transaccion contable',
  `sentido` enum('D','C') collate latin1_spanish_ci NOT NULL COMMENT 'D->Debito C->Credito',
  `dias_trabajados` smallint(3) NOT NULL COMMENT 'Dias laborados en el periodo',
  `salario_mensual` decimal(11,2) NOT NULL COMMENT 'Valor mensual del salario',
  `valor_movimiento` decimal(11,2) NOT NULL COMMENT 'Valor diario del registro',
  `ibc_pension` decimal(11,2) NOT NULL COMMENT 'Valor sobre el cual se calcula el aporte',
  `porcentaje_tasa_pension` decimal(7,4) NOT NULL default '0.0000' COMMENT 'Porcentaje de la tasa',
  PRIMARY KEY  (`codigo_empresa`,`documento_identidad_empleado`,`fecha_generacion`,`motivo_retiro`,`codigo_transaccion_contable`),
  KEY `liquidaciones_movimiento_pension_transaccion_contable` (`codigo_transaccion_contable`),
  KEY `liquidaciones_movimiento_pension_anexo` (`codigo_anexo_contable`),
  KEY `liquidaciones_movimiento_pension_auxiliar_contable` (`codigo_empresa_auxiliar`,`codigo_anexo_contable`,`codigo_auxiliar_contable`),
  KEY `liquidaciones_movimiento_pension_entidades_parafiscales` (`codigo_entidad_pension`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_liquidaciones_movimientos_pension`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_liquidaciones_movimientos_salarios`
-- 

DROP TABLE IF EXISTS `job_liquidaciones_movimientos_salarios`;
CREATE TABLE `job_liquidaciones_movimientos_salarios` (
  `codigo_empresa` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa donde va a laborar',
  `documento_identidad_empleado` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Documento de identidad empleado en la sucursales contrato empleados',
  `fecha_generacion` datetime NOT NULL COMMENT 'Fecha en la se genero el retiro',
  `motivo_retiro` smallint(4) unsigned zerofill NOT NULL COMMENT 'Código que identifica el concepto de prestamo',
  `codigo_transaccion_contable` int(8) unsigned zerofill NOT NULL COMMENT 'Id de la tabla de transacciones contables',
  `fecha_inicio_pago` date NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado',
  `fecha_hasta_pago` date NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado',
  `codigo_empresa_auxiliar` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa del auxiliar contable',
  `codigo_anexo_contable` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'Código del anexo que permite dividir las cuentas',
  `codigo_auxiliar_contable` int(8) unsigned zerofill NOT NULL COMMENT 'Código donde se acumulara la informaciÃ³n',
  `codigo_contable` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo contable de la transaccion contable',
  `sentido` enum('D','C') collate latin1_spanish_ci NOT NULL COMMENT 'D->DÃ©bito C->CrÃ©dito',
  `dias_trabajados` smallint(3) NOT NULL COMMENT 'Dias laborados en el periodo',
  `salario_mensual` decimal(11,2) NOT NULL COMMENT 'Valor mensual del salario',
  `valor_movimiento` decimal(11,2) NOT NULL COMMENT 'Valor diario del registro',
  PRIMARY KEY  (`codigo_empresa`,`documento_identidad_empleado`,`fecha_generacion`,`motivo_retiro`,`codigo_transaccion_contable`),
  KEY `liquidaciones_movimientos_salarios_transaccion` (`codigo_transaccion_contable`),
  KEY `liquidaciones_salario_anexo` (`codigo_anexo_contable`),
  KEY `liquidaciones_salario_auxiliar_contable` (`codigo_empresa_auxiliar`,`codigo_anexo_contable`,`codigo_auxiliar_contable`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_liquidaciones_movimientos_salarios`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_liquidaciones_movimientos_salud`
-- 

DROP TABLE IF EXISTS `job_liquidaciones_movimientos_salud`;
CREATE TABLE `job_liquidaciones_movimientos_salud` (
  `codigo_empresa` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa donde va a laborar',
  `documento_identidad_empleado` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Documento de identidad empleado en la sucursales contrato empleados',
  `fecha_generacion` datetime NOT NULL COMMENT 'Fecha en la se genero el retiro',
  `motivo_retiro` smallint(4) unsigned zerofill NOT NULL COMMENT 'Código que identifica el concepto de prestamo',
  `codigo_transaccion_contable` int(8) unsigned zerofill NOT NULL COMMENT 'Id de la tabla de transacciones contables',
  `fecha_inicio_pago` date NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado',
  `fecha_hasta_pago` date NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado',
  `codigo_empresa_auxiliar` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa del auxiliar contable',
  `codigo_anexo_contable` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo del anexo que permite dividir las cuentas',
  `codigo_auxiliar_contable` int(8) unsigned zerofill NOT NULL COMMENT 'Codigo donde se acumulara la informacion',
  `codigo_entidad_salud` int(8) unsigned zerofill NOT NULL COMMENT 'Codigo de la entidad parafiscal',
  `codigo_contable` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo contable de la transaccion contable',
  `sentido` enum('D','C') collate latin1_spanish_ci NOT NULL COMMENT 'D->Debito C->Credito',
  `dias_trabajados` smallint(3) NOT NULL COMMENT 'Dias laborados en el periodo',
  `salario_mensual` decimal(11,2) NOT NULL COMMENT 'Valor mensual del salario',
  `valor_movimiento` decimal(11,2) NOT NULL COMMENT 'Valor diario del registro',
  `ibc_salud` decimal(11,2) NOT NULL COMMENT 'Valor sobre el cual se calcula el aporte',
  `porcentaje_tasa_salud` decimal(7,4) NOT NULL default '0.0000' COMMENT 'Porcentaje de la tasa',
  PRIMARY KEY  (`codigo_empresa`,`documento_identidad_empleado`,`fecha_generacion`,`motivo_retiro`,`codigo_transaccion_contable`),
  KEY `liquidaciones_movimiento_salud_transaccion_contable` (`codigo_transaccion_contable`),
  KEY `liquidaciones_movimiento_salud_anexo` (`codigo_anexo_contable`),
  KEY `liquidaciones_movimiento_salud_auxiliar_contable` (`codigo_empresa_auxiliar`,`codigo_anexo_contable`,`codigo_auxiliar_contable`),
  KEY `liquidaciones_movimiento_salud_entidades_parafiscales` (`codigo_entidad_salud`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_liquidaciones_movimientos_salud`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_liquidaciones_prestamos_empleados`
-- 

DROP TABLE IF EXISTS `job_liquidaciones_prestamos_empleados`;
CREATE TABLE `job_liquidaciones_prestamos_empleados` (
  `codigo_empresa` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa donde va a laborar',
  `documento_identidad_empleado` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Documento de identidad empleado en la sucursales contrato empleados',
  `fecha_generacion` date NOT NULL COMMENT 'Fecha en la segenero el movimiento de tiempo',
  `motivo_retiro` smallint(4) unsigned zerofill NOT NULL COMMENT 'Código que identifica el concepto de prestamo',
  `codigo_empresa_auxiliar` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa del auxiliar contable',
  `codigo_anexo_contable` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'CÃ³digo del anexo que permite dividir las cuentas',
  `codigo_auxiliar_contable` int(8) unsigned zerofill NOT NULL COMMENT 'Código donde se acumulara la información',
  `fecha_ingreso` date NOT NULL COMMENT 'Fecha en la que ingreso el empleado a la empresa',
  `fecha_ingreso_sucursal` date NOT NULL COMMENT 'Fecha en la que inicia labores en la sucursal el empleado',
  `consecutivo` int(8) unsigned zerofill NOT NULL COMMENT 'Consecutivo interno para la base de datos',
  `codigo_transaccion_contable` int(8) unsigned zerofill NOT NULL COMMENT 'codigo de la transaccion contable empleado',
  `codigo_contable` int(4) NOT NULL COMMENT 'Codigo contable de la transaccion contable',
  `sentido` enum('D','C') collate latin1_spanish_ci NOT NULL COMMENT 'D->Débito C->Crédito',
  `concepto_prestamo` smallint(4) unsigned zerofill NOT NULL COMMENT 'Código que identifica el concepto de prestamo',
  `valor_movimiento` decimal(11,2) default NULL COMMENT 'Valor que se le descontará al empleado dependiendo del periodo seleccionado'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_liquidaciones_prestamos_empleados`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_localidades`
-- 

DROP TABLE IF EXISTS `job_localidades`;
CREATE TABLE `job_localidades` (
  `codigo_iso` varchar(2) collate latin1_spanish_ci NOT NULL COMMENT 'Llave principal de la tabla paises',
  `codigo_dane_departamento` varchar(2) collate latin1_spanish_ci NOT NULL COMMENT 'CÃ³digo DANE',
  `codigo_dane_municipio` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'CÃ³digo DANE',
  `tipo` enum('B','C') collate latin1_spanish_ci NOT NULL default 'B' COMMENT 'Tipo de localidad: B=Barrio, C=Corregimiento',
  `codigo_dane_localidad` varchar(3) collate latin1_spanish_ci NOT NULL default '' COMMENT 'CÃ³digo DANE (sÃ³lo para corregimientos)',
  `codigo_interno` int(8) unsigned zerofill default NULL COMMENT 'CÃ³digo para uso interno de la empresa (opcional)',
  `nombre` varchar(255) collate latin1_spanish_ci NOT NULL default '' COMMENT 'Nombre completo',
  `comuna` tinyint(2) NOT NULL default '0' COMMENT 'Comuna a la que pertenece (sÃ³lo para barrios)',
  `estrato` tinyint(1) NOT NULL default '0' COMMENT 'Estrato al que pertenece (sÃ³lo para barrios)',
  PRIMARY KEY  (`codigo_iso`,`codigo_dane_departamento`,`codigo_dane_municipio`,`tipo`,`codigo_dane_localidad`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_localidades`
-- 

INSERT INTO `job_localidades` (`codigo_iso`, `codigo_dane_departamento`, `codigo_dane_municipio`, `tipo`, `codigo_dane_localidad`, `codigo_interno`, `nombre`, `comuna`, `estrato`) VALUES 
('', '', '', 'C', '', 00000000, '', 0, 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_marcas`
-- 

DROP TABLE IF EXISTS `job_marcas`;
CREATE TABLE `job_marcas` (
  `codigo` smallint(4) unsigned zerofill NOT NULL COMMENT 'Código manejado por la empresa',
  `descripcion` varchar(255) collate latin1_spanish_ci NOT NULL default '' COMMENT 'Descripción de la marca',
  `orden` int(5) unsigned zerofill default NULL COMMENT 'Orden para los listados',
  PRIMARY KEY  (`codigo`),
  UNIQUE KEY `descripcion` (`descripcion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_marcas`
-- 

INSERT INTO `job_marcas` (`codigo`, `descripcion`, `orden`) VALUES 
(0000, '', 00000);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_modulos`
-- 

DROP TABLE IF EXISTS `job_modulos`;
CREATE TABLE `job_modulos` (
  `id` char(32) collate latin1_spanish_ci NOT NULL COMMENT 'Identificador del mÃ³dulo',
  `nombre` varchar(32) collate latin1_spanish_ci NOT NULL COMMENT 'Nombre del mÃ³dulo',
  `descripcion` varchar(255) collate latin1_spanish_ci NOT NULL COMMENT 'DescripciÃ³n del mÃ³dulo',
  `carpeta` varchar(255) collate latin1_spanish_ci default NULL COMMENT 'Carpeta donde estarÃ¡n almacenados los componentes del mÃ³dulo',
  `url` varchar(255) collate latin1_spanish_ci default NULL COMMENT 'URL del mÃ³dulo',
  `version` char(10) collate latin1_spanish_ci default NULL COMMENT 'VersiÃ³n del mÃ³dulo (Formato: AAAAMMDD+consecutivo. Ej: 2008031501)',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_modulos`
-- 

INSERT INTO `job_modulos` (`id`, `nombre`, `descripcion`, `carpeta`, `url`, `version`) VALUES 
('ADMINISTRACION', 'Administracion', 'Operaciones y datos de control relacionados con el acceso a la aplicacion y la integracion de sus componentes', 'administracion', NULL, NULL),
('CLIENTES', 'Clientes', 'Operaciones y datos de control relacionados con los clientes', 'clientes', NULL, NULL),
('CONTABILIDAD', 'Contabilidad', 'Menu de contabilidad', 'contabilidad', NULL, NULL),
('EXTENSIONES', 'Extensiones', 'Extensiones de uso general de la aplicación', 'extensiones', NULL, NULL),
('FINANCIERA', 'Financiera', 'Operaciones y datos de control relacionados con lo financiero', 'financiera', NULL, NULL),
('INVENTARIO', 'Inventario', 'Operaciones y datos de control relacionados con el inventario', 'inventarios', NULL, NULL),
('LOGISTICA', 'Logistica', 'Operaciones y datos de control relacionados con la logistica', 'logistica', NULL, NULL),
('NOMINA', 'Nomina', 'Operaciones y datos de control relacionados con la nomina', 'nomina', NULL, NULL),
('PROVEEDORES', 'Proveedores', 'Operaciones y datos de control relacionados con los proveedores', 'proveedores', NULL, NULL);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_motivos_incapacidad`
-- 

DROP TABLE IF EXISTS `job_motivos_incapacidad`;
CREATE TABLE `job_motivos_incapacidad` (
  `codigo` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo interno identifica el motivo de la incapacidad',
  `descripcion` varchar(255) collate latin1_spanish_ci NOT NULL COMMENT 'Detalle que describe el motivo de incapacidad',
  PRIMARY KEY  (`codigo`),
  UNIQUE KEY `descripcion` (`descripcion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_motivos_incapacidad`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_motivos_retiro`
-- 

DROP TABLE IF EXISTS `job_motivos_retiro`;
CREATE TABLE `job_motivos_retiro` (
  `codigo` smallint(4) unsigned zerofill NOT NULL COMMENT 'CÃ³digo interno que identifica el motivo de retiro',
  `descripcion` varchar(50) collate latin1_spanish_ci NOT NULL COMMENT 'Detalle que identifica el motivo de retiro',
  `indemniza` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT 'Genera indemnizacion 0->No 1->Si',
  PRIMARY KEY  (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_motivos_retiro`
-- 

INSERT INTO `job_motivos_retiro` (`codigo`, `descripcion`, `indemniza`) VALUES 
(0000, '', '0');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_motivos_tiempo_no_laborado`
-- 

DROP TABLE IF EXISTS `job_motivos_tiempo_no_laborado`;
CREATE TABLE `job_motivos_tiempo_no_laborado` (
  `codigo` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo interno identifica el motivo de la incapacidad',
  `descripcion` varchar(255) collate latin1_spanish_ci NOT NULL COMMENT 'Detalle que describe el motivo de incapacidad',
  PRIMARY KEY  (`codigo`),
  UNIQUE KEY `descripcion` (`descripcion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_motivos_tiempo_no_laborado`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_movimiento_control_prestamos_empleados`
-- 

DROP TABLE IF EXISTS `job_movimiento_control_prestamos_empleados`;
CREATE TABLE `job_movimiento_control_prestamos_empleados` (
  `ano_generacion` int(4) unsigned zerofill NOT NULL COMMENT 'anio de la generacion la planilla',
  `mes_generacion` int(2) unsigned zerofill NOT NULL COMMENT 'Mes de generacion de la planilla',
  `codigo_planilla` smallint(3) unsigned zerofill NOT NULL COMMENT 'Id de la tabla de planillas',
  `periodo_pago` enum('1','2','3','4','5','6','7','8','9') collate latin1_spanish_ci NOT NULL COMMENT '1->primera semana 2->segunda semana 3->tercera semana 4->cuarta semana 5-> quinta semana 6-> proporcional quincena 7-> primera quincena 8-> segunda quincena 9-> mensual',
  `fecha_pago_planilla` date NOT NULL COMMENT 'Fecha rango de pago de la planilla',
  `documento_identidad_empleado` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Documento de identidad empleado en la sucursales contrato empleados',
  `codigo_sucursal` mediumint(5) unsigned zerofill NOT NULL COMMENT 'Id de la tabla de sucursales',
  `codigo_transaccion_contable` int(8) unsigned zerofill NOT NULL COMMENT 'codigo de la transaccion contable empleado',
  `codigo_empresa_auxiliar` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa del auxiliar contable',
  `codigo_anexo_contable` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'CÃ³digo del anexo que permite dividir las cuentas',
  `codigo_auxiliar_contable` int(8) unsigned zerofill NOT NULL COMMENT 'Código donde se acumulara la información',
  `fecha_generacion` date NOT NULL COMMENT 'Fecha en la segenero el movimiento de tiempo',
  `codigo_empresa` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa donde va a laborar',
  `fecha_ingreso` date NOT NULL COMMENT 'Fecha en la que ingreso el empleado a la empresa',
  `fecha_ingreso_sucursal` date NOT NULL COMMENT 'Fecha en la que inicia labores en la sucursal el empleado',
  `consecutivo` int(8) unsigned zerofill NOT NULL COMMENT 'Consecutivo interno para la base de datos',
  `codigo_contable` int(4) NOT NULL COMMENT 'Codigo contable de la transaccion contable',
  `sentido` enum('D','C') collate latin1_spanish_ci NOT NULL COMMENT 'D->Débito C->Crédito',
  `fecha_generacion_control` date NOT NULL COMMENT 'Fecha en la se genero el prestamo',
  `consecutivo_fecha_pago` int(8) unsigned zerofill NOT NULL COMMENT 'Consecutivo de la fecha con la que esta relacionada el pago',
  `fecha_pago` date NOT NULL COMMENT 'Fecha de acuerdo de pago',
  `concepto_prestamo` smallint(4) unsigned zerofill NOT NULL COMMENT 'Código que identifica el concepto de prestamo',
  `valor_descuento` decimal(11,2) default NULL COMMENT 'Valor que se le descontará al empleado dependiendo del periodo seleccionado',
  `contabilizado` enum('0','1','2') collate latin1_spanish_ci NOT NULL default '0' COMMENT 'Esta contabilizado 0->No 1->Si 2->Leido para liquidar salario',
  `codigo_usuario_registra` smallint(4) unsigned zerofill NOT NULL COMMENT 'Codigo del Usuario que genera el registro',
  PRIMARY KEY  (`ano_generacion`,`mes_generacion`,`codigo_planilla`,`periodo_pago`,`fecha_pago_planilla`,`documento_identidad_empleado`,`codigo_sucursal`,`consecutivo`),
  KEY `movimiento_tiempos_laborados_fechas_de_pago` (`documento_identidad_empleado`,`consecutivo_fecha_pago`,`fecha_generacion_control`,`concepto_prestamo`,`fecha_pago`),
  KEY `movimiento_cuenta_por_descotar_empleado` (`codigo_transaccion_contable`),
  KEY `movimiento_control_prestamos_empleados_usuario_registra` (`codigo_usuario_registra`),
  KEY `movimiento_control_prestamos_empleados_contable` (`codigo_empresa_auxiliar`,`codigo_anexo_contable`,`codigo_auxiliar_contable`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_movimiento_control_prestamos_empleados`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_movimiento_cuenta_pago_tercero`
-- 

DROP TABLE IF EXISTS `job_movimiento_cuenta_pago_tercero`;
CREATE TABLE `job_movimiento_cuenta_pago_tercero` (
  `documento_identidad_empleado` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Documento de identidad empleado en la sucursales contrato empleados',
  `codigo_sucursal` mediumint(5) unsigned zerofill NOT NULL COMMENT 'Id de la tabla de sucursales',
  `obligacion` varchar(10) collate latin1_spanish_ci NOT NULL COMMENT 'Documento de identidad empleado en la sucursales contrato empleados',
  `fecha_pago_planilla` date NOT NULL COMMENT 'Fecha rango de pago de la planilla',
  `documento_identidad_tercero` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Documento de identidad del tercero',
  `fecha_generacion` datetime NOT NULL COMMENT 'Fecha en la se genero el retiro',
  `codigo_empresa_auxiliar` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa del auxiliar contable',
  `codigo_anexo_contable` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'Código del anexo que permite dividir las cuentas',
  `codigo_auxiliar_contable` int(8) unsigned zerofill NOT NULL COMMENT 'Código donde se acumulara la información',
  `codigo_empresa` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa donde va a laborar',
  `codigo_transaccion_contable` int(8) unsigned zerofill NOT NULL COMMENT 'codigo de la transaccion contable empleado',
  `valor_movimiento` decimal(11,2) default NULL COMMENT 'Valor que se le descontará al empleado dependiendo del periodo seleccionado',
  `codigo_contable` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo del plan contable',
  `sentido` enum('D','C') collate latin1_spanish_ci NOT NULL COMMENT 'D->Débito C->Crédito',
  `contabilizado` enum('0','1','2') collate latin1_spanish_ci NOT NULL default '0' COMMENT 'Esta contabilizado 0->No 1->Si 2->Leido para liquidar salario',
  `codigo_usuario_registra` smallint(4) unsigned zerofill NOT NULL COMMENT 'Codigo del Usuario que genera el registro',
  PRIMARY KEY  (`documento_identidad_empleado`,`codigo_sucursal`,`obligacion`,`fecha_pago_planilla`),
  KEY `movimiento_cuenta_pago_tercero_documento_identidad` (`documento_identidad_tercero`),
  KEY `movimiento_cuenta_pago_tercero_sucursal` (`codigo_sucursal`),
  KEY `movimiento_cuenta_pago_tercero_relacion` (`codigo_empresa`,`documento_identidad_empleado`,`obligacion`),
  KEY `movimiento_cuenta_pago_tercero_transaccion_contable` (`codigo_transaccion_contable`),
  KEY `movimiento_cuenta_pago_tercero_contable` (`codigo_empresa_auxiliar`,`codigo_anexo_contable`,`codigo_auxiliar_contable`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_movimiento_cuenta_pago_tercero`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_movimiento_cuenta_por_cobrar_descuento`
-- 

DROP TABLE IF EXISTS `job_movimiento_cuenta_por_cobrar_descuento`;
CREATE TABLE `job_movimiento_cuenta_por_cobrar_descuento` (
  `ano_generacion` int(4) unsigned zerofill NOT NULL COMMENT 'anio de la generacion la planilla',
  `mes_generacion` int(2) unsigned zerofill NOT NULL COMMENT 'Mes de generacion de la planilla',
  `codigo_planilla` smallint(3) unsigned zerofill NOT NULL COMMENT 'Id de la tabla de planillas',
  `periodo_pago` enum('1','2','3','4','5','6','7','8','9') collate latin1_spanish_ci NOT NULL COMMENT '1->primera semana 2->segunda semana 3->tercera semana 4->cuarta semana 5-> quinta semana 6-> proporcional quincena 7-> primera quincena 8-> segunda quincena 9-> mensual',
  `fecha_pago_planilla` date NOT NULL COMMENT 'Fecha rango de pago de la planilla',
  `codigo_sucursal` mediumint(5) unsigned zerofill NOT NULL COMMENT 'Id de la tabla de sucursales',
  `codigo_empresa_auxiliar` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa del auxiliar contable',
  `codigo_anexo_contable` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'Código del anexo que permite dividir las cuentas',
  `codigo_auxiliar_contable` int(8) unsigned zerofill NOT NULL COMMENT 'Código donde se acumulara la información',
  `fecha_generacion` date NOT NULL COMMENT 'Fecha en la que se genero el movimiento de control de prestamos de terceros',
  `codigo_empresa` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa donde va a laborar',
  `documento_identidad_empleado` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Documento de identidad empleado en la sucursales contrato empleados',
  `obligacion` varchar(10) collate latin1_spanish_ci NOT NULL COMMENT 'Documento de identidad empleado en la sucursales contrato empleados',
  `valor_movimiento` decimal(11,2) default NULL COMMENT 'Valor que se le descontará al empleado dependiendo del periodo seleccionado',
  `codigo_transaccion_contable` int(8) unsigned zerofill NOT NULL COMMENT 'codigo de la transaccion contable empleado',
  `codigo_contable` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo del plan contable',
  `sentido` enum('D','C') collate latin1_spanish_ci NOT NULL COMMENT 'D->Débito C->Crédito',
  `contabilizado` enum('0','1','2') collate latin1_spanish_ci NOT NULL default '0' COMMENT 'Esta contabilizado 0->No 1->Si 2->Leido para liquidar salario',
  `codigo_usuario_registra` smallint(4) unsigned zerofill NOT NULL COMMENT 'Codigo del Usuario que genera el registro',
  PRIMARY KEY  (`ano_generacion`,`mes_generacion`,`codigo_planilla`,`periodo_pago`,`fecha_pago_planilla`,`documento_identidad_empleado`,`codigo_sucursal`,`obligacion`),
  KEY `cuenta_por_cobrar_descuento_sucursal` (`codigo_sucursal`),
  KEY `cuenta_por_cobrar_descuento_relacion` (`codigo_empresa`,`documento_identidad_empleado`,`obligacion`),
  KEY `movimiento_cuenta_por_cobrar_descuento_transaccion_contable` (`codigo_transaccion_contable`),
  KEY `movimiento_cuenta_por_cobrar_descuento_contable` (`codigo_empresa_auxiliar`,`codigo_anexo_contable`,`codigo_auxiliar_contable`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_movimiento_cuenta_por_cobrar_descuento`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_movimiento_cuenta_por_cobrar_empleado`
-- 

DROP TABLE IF EXISTS `job_movimiento_cuenta_por_cobrar_empleado`;
CREATE TABLE `job_movimiento_cuenta_por_cobrar_empleado` (
  `documento_identidad_empleado` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Documento de identidad empleado en la sucursales contrato empleados',
  `codigo_sucursal` mediumint(5) unsigned zerofill NOT NULL COMMENT 'Id de la tabla de sucursales',
  `obligacion` varchar(10) collate latin1_spanish_ci NOT NULL COMMENT 'Documento de identidad empleado en la sucursales contrato empleados',
  `fecha_pago_planilla` date NOT NULL COMMENT 'Fecha rango de pago de la planilla',
  `fecha_generacion` datetime NOT NULL COMMENT 'Fecha en la se genero el retiro',
  `codigo_empresa` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa donde va a laborar',
  `codigo_transaccion_contable` int(8) unsigned zerofill NOT NULL COMMENT 'codigo de la transaccion contable empleado',
  `valor_movimiento` decimal(11,2) default NULL COMMENT 'Valor que se le descontará al empleado dependiendo del periodo seleccionado',
  `codigo_empresa_auxiliar` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa del auxiliar contable',
  `codigo_anexo_contable` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'Código del anexo que permite dividir las cuentas',
  `codigo_auxiliar_contable` int(8) unsigned zerofill NOT NULL COMMENT 'Código donde se acumulara la información',
  `codigo_contable` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo del plan contable',
  `sentido` enum('D','C') collate latin1_spanish_ci NOT NULL COMMENT 'D->Débito C->Crédito',
  `contabilizado` enum('0','1','2') collate latin1_spanish_ci NOT NULL default '0' COMMENT 'Esta contabilizado 0->No 1->Si 2->Leido para liquidar salario',
  `codigo_usuario_registra` smallint(4) unsigned zerofill NOT NULL COMMENT 'Codigo del Usuario que genera el registro',
  PRIMARY KEY  (`documento_identidad_empleado`,`codigo_sucursal`,`obligacion`,`fecha_pago_planilla`),
  KEY `cuenta_por_comprar_empleado_sucursal` (`codigo_sucursal`),
  KEY `cuenta_por_comprar_empleado_relacion` (`codigo_empresa`,`documento_identidad_empleado`,`obligacion`),
  KEY `cuenta_por_comprar_empleado_transaccion_contable` (`codigo_transaccion_contable`),
  KEY `movimiento_cuenta_por_cobrar_empleado_transaccion_contable` (`codigo_empresa_auxiliar`,`codigo_anexo_contable`,`codigo_auxiliar_contable`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_movimiento_cuenta_por_cobrar_empleado`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_movimiento_cuenta_por_pagar_tercero`
-- 

DROP TABLE IF EXISTS `job_movimiento_cuenta_por_pagar_tercero`;
CREATE TABLE `job_movimiento_cuenta_por_pagar_tercero` (
  `documento_identidad_empleado` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Documento de identidad empleado en la sucursales contrato empleados',
  `codigo_sucursal` mediumint(5) unsigned zerofill NOT NULL COMMENT 'Id de la tabla de sucursales',
  `obligacion` varchar(10) collate latin1_spanish_ci NOT NULL COMMENT 'Documento de identidad empleado en la sucursales contrato empleados',
  `fecha_pago_planilla` date NOT NULL COMMENT 'Fecha rango de pago de la planilla',
  `fecha_generacion` datetime NOT NULL COMMENT 'Fecha en la se genero el retiro',
  `codigo_empresa_auxiliar` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa del auxiliar contable',
  `codigo_anexo_contable` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'Código del anexo que permite dividir las cuentas',
  `codigo_auxiliar_contable` int(8) unsigned zerofill NOT NULL COMMENT 'Código donde se acumulara la información',
  `codigo_empresa` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa donde va a laborar',
  `documento_identidad_tercero` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Documento de identidad del tercero',
  `codigo_transaccion_contable` int(8) unsigned zerofill NOT NULL COMMENT 'codigo de la transaccion contable empleado',
  `valor_movimiento` decimal(11,2) default NULL COMMENT 'Valor que se le descontará al empleado dependiendo del periodo seleccionado',
  `codigo_contable` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo del plan contable',
  `sentido` enum('D','C') collate latin1_spanish_ci NOT NULL COMMENT 'D->Débito C->Crédito',
  `contabilizado` enum('0','1','2') collate latin1_spanish_ci NOT NULL default '0' COMMENT 'Esta contabilizado 0->No 1->Si 2->Leido para liquidar salario',
  `codigo_usuario_registra` smallint(4) unsigned zerofill NOT NULL COMMENT 'Codigo del Usuario que genera el registro',
  PRIMARY KEY  (`documento_identidad_empleado`,`codigo_sucursal`,`obligacion`,`fecha_pago_planilla`),
  KEY `movimiento_cuenta_por_pagar_documento_identidad` (`documento_identidad_tercero`),
  KEY `movimiento_cuenta_por_pagar_tercero_sucursal` (`codigo_sucursal`),
  KEY `movimiento_cuenta_por_pagar_tercero_relacion` (`codigo_empresa`,`documento_identidad_empleado`,`obligacion`),
  KEY `movimiento_cuenta_por_pagar_tercero_transaccion_contable` (`codigo_transaccion_contable`),
  KEY `movimiento_cuenta_por_pagar_tercero_contable` (`codigo_empresa_auxiliar`,`codigo_anexo_contable`,`codigo_auxiliar_contable`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_movimiento_cuenta_por_pagar_tercero`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_movimiento_liquidacion_vacaciones`
-- 

DROP TABLE IF EXISTS `job_movimiento_liquidacion_vacaciones`;
CREATE TABLE `job_movimiento_liquidacion_vacaciones` (
  `forma_liquidacion` enum('1','2') collate latin1_spanish_ci NOT NULL COMMENT '1->Afecta en planilla  2->Liquidacion de total de vacaciones  3->cesantias  4->intereses/cesantias ',
  `estado_liquidacion` enum('1','2','3','4') collate latin1_spanish_ci NOT NULL COMMENT '1->Activa  2->Denegada 3->Autorizada para pagar 4->Pagada',
  `fecha_generacion` datetime NOT NULL COMMENT 'Fecha en la se genero el tiempo',
  `fecha_inicio_tiempo` date NOT NULL COMMENT 'Fecha inicio en que inicia las vacaciones',
  `fecha_final_tiempo` date NOT NULL COMMENT 'Fecha inicio en que finaliza las vacaciones',
  `codigo_empresa` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa donde labora el empleado',
  `documento_identidad_empleado` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Documento de identidad empleado en la tabla sucursal contrato empleado',
  `fecha_ingreso` date NOT NULL COMMENT 'Fecha en la que inicia labores el empleado',
  `codigo_sucursal` mediumint(5) unsigned zerofill NOT NULL COMMENT 'Codigo de la sucursal donde labora el empleado',
  `fecha_ingreso_sucursal` date NOT NULL COMMENT 'Fecha en la que inicia labores en la sucursal el empleado',
  `codigo_empresa_auxiliar` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa para la llave de auxiliares',
  `codigo_anexo_contable` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'Código del anexo que permite dividir las cuentas',
  `codigo_auxiliar_contable` int(8) unsigned zerofill NOT NULL COMMENT 'Código donde se acumulara la información',
  `codigo_transaccion_contable` int(8) unsigned zerofill NOT NULL COMMENT 'Id de la tabla de transacciones contables del Empleado',
  `codigo_transaccion_tiempo` int(8) unsigned zerofill NOT NULL COMMENT 'Id de la tabla de transacciones de tiempo',
  `codigo_contable` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo contable de la transaccion contable',
  `sentido` enum('D','C') collate latin1_spanish_ci NOT NULL COMMENT 'D->Débito C->Crédito',
  `dias_tomados` smallint(3) NOT NULL COMMENT 'Número de días que el empleado ha tomado de los 15 días hábiles de descanso remunerado por cada año de trabajo',
  `dias_disfrutado` smallint(3) NOT NULL COMMENT 'Número de días que el empleado esta realmente por fuera de su obligacion laboral',
  `valor_movimiento` decimal(11,2) NOT NULL COMMENT 'Valor diario del registro',
  `codigo_usuario_registra` smallint(4) unsigned zerofill NOT NULL COMMENT 'Codigo del Usuario que genera el registro',
  `codigo_usuario_modifica` smallint(4) unsigned zerofill default NULL COMMENT 'Codigo del ultimo Usuario que modifica el registro',
  PRIMARY KEY  (`codigo_sucursal`,`documento_identidad_empleado`,`fecha_inicio_tiempo`),
  KEY `movimiento_liquidacion_vacaciones_sucursal_contrato` (`codigo_empresa`,`documento_identidad_empleado`,`fecha_ingreso`,`codigo_sucursal`,`fecha_ingreso_sucursal`),
  KEY `movimiento_liquidacion_vacaciones_transaccion_tiempo` (`codigo_transaccion_tiempo`),
  KEY `movimiento_liquidacion_vacaciones_transaccion_contable` (`codigo_transaccion_contable`),
  KEY `movimiento_liquidacion_vacaciones_plan_contable` (`codigo_contable`),
  KEY `movimiento_liquidacion_vacaciones_usuarios_registra` (`codigo_usuario_registra`),
  KEY `movimiento_liquidacion_vacaciones_usuarios_modifica` (`codigo_usuario_modifica`),
  KEY `movimiento_liquidacion_vacaciones_anexo` (`codigo_anexo_contable`),
  KEY `movimiento_liquidacion_vacaciones_auxiliar_contable` (`codigo_empresa_auxiliar`,`codigo_anexo_contable`,`codigo_auxiliar_contable`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_movimiento_liquidacion_vacaciones`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_movimiento_liquidaciones_empleado`
-- 

DROP TABLE IF EXISTS `job_movimiento_liquidaciones_empleado`;
CREATE TABLE `job_movimiento_liquidaciones_empleado` (
  `codigo_empresa` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa donde va a laborar',
  `documento_identidad_empleado` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Documento de identidad empleado en la sucursales contrato empleados',
  `fecha_generacion` datetime NOT NULL COMMENT 'Fecha en la se genero el retiro',
  `motivo_retiro` smallint(4) unsigned zerofill NOT NULL COMMENT 'Código que identifica el concepto de prestamo',
  `flujo_efectivo` enum('1','2','3') collate latin1_spanish_ci NOT NULL COMMENT '1->No afecta flujo 2->Caja 3->Bancos',
  `codigo_plan_contable` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo de la cuenta contable segun el PUC',
  `sentido` enum('D','C') collate latin1_spanish_ci NOT NULL default 'D' COMMENT 'D->Débito C->Crédito',
  `codigo_sucursal_pertence` mediumint(5) unsigned zerofill NOT NULL COMMENT 'codigo de la sucursal con la que se relaciona la sucursal',
  `tipo_documento_cuenta_bancaria` smallint(3) unsigned zerofill NOT NULL COMMENT 'Código asignado por el usuario',
  `codigo_sucursal_banco` smallint(2) unsigned zerofill NOT NULL COMMENT 'codigo de la tabla bancos',
  `codigo_iso` varchar(2) collate latin1_spanish_ci NOT NULL COMMENT 'Llave principal de la tabla paises',
  `codigo_dane_departamento` varchar(2) collate latin1_spanish_ci NOT NULL COMMENT 'Código DANE',
  `codigo_dane_municipio` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'Código DANE',
  `codigo_banco` smallint(2) unsigned zerofill NOT NULL COMMENT 'id de la tabla bancos',
  `consecutivo_cheque` int(8) unsigned zerofill NOT NULL COMMENT 'Consecutivo interno para la base de datos',
  `numero` varchar(30) collate latin1_spanish_ci NOT NULL COMMENT 'Numero de la cuenta',
  PRIMARY KEY  (`codigo_empresa`,`documento_identidad_empleado`,`fecha_generacion`,`motivo_retiro`),
  KEY `movimiento_liquidaciones_empleado_plan_contable` (`codigo_plan_contable`),
  KEY `movimiento_liquidaciones_empleado_consecutivo_cheques` (`codigo_sucursal_pertence`,`tipo_documento_cuenta_bancaria`,`codigo_banco`,`numero`,`consecutivo_cheque`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_movimiento_liquidaciones_empleado`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_movimiento_novedades_manuales`
-- 

DROP TABLE IF EXISTS `job_movimiento_novedades_manuales`;
CREATE TABLE `job_movimiento_novedades_manuales` (
  `fecha_generacion` date NOT NULL COMMENT 'Fecha en que se genero la novedad',
  `codigo_empresa` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa donde va a laborar',
  `documento_identidad_empleado` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Documento de identidad empleado en la sucursales contrato empleados',
  `fecha_ingreso` date NOT NULL COMMENT 'Fecha en la que ingreso el empleado a la empresa',
  `codigo_sucursal` mediumint(5) unsigned zerofill NOT NULL COMMENT 'Id de la tabla de sucursales',
  `fecha_ingreso_sucursal` date NOT NULL COMMENT 'Fecha en la que inicia labores en la sucursal el empleado',
  `consecutivo` int(8) unsigned zerofill NOT NULL COMMENT 'Consecutivo interno para la base de datos',
  `codigo_planilla` smallint(3) unsigned zerofill NOT NULL COMMENT 'Id de la tabla de planillas',
  `codigo_transaccion_contable` int(8) unsigned zerofill NOT NULL COMMENT 'Id de la tabla de transacciones contables',
  `codigo_contable` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo contable de la transaccion contable',
  `sentido` enum('D','C') collate latin1_spanish_ci NOT NULL COMMENT 'D->Débito C->Crédito',
  `ano_generacion` int(4) unsigned zerofill NOT NULL COMMENT 'Ano de la generacion la planilla',
  `mes_generacion` int(2) unsigned zerofill NOT NULL COMMENT 'Mes de generacion de la planilla',
  `fecha_pago_planilla` date NOT NULL COMMENT 'Fecha rango de pago de la planilla',
  `codigo_empresa_auxiliar` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa para la llave de auxiliares',
  `codigo_anexo_contable` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'Código del anexo que permite dividir las cuentas',
  `codigo_auxiliar_contable` int(8) unsigned zerofill NOT NULL COMMENT 'Código donde se acumulara la información',
  `periodo_pago` enum('1','2','3','4','5','6','7','8','9') collate latin1_spanish_ci NOT NULL COMMENT '1->Mensual 2->Primera quincena 3->Segunda quincena 4->Primera semana 5->Segunda semana 6->Tercer semana 7->Cuarta semana 8->Quinta semana 9->Fecha unica',
  `contabilizado` enum('0','1','2') collate latin1_spanish_ci NOT NULL default '0' COMMENT 'Esta contabilizado 0->No 1->Si 2->Leido para liquidar salario',
  `valor_movimiento` decimal(15,2) NOT NULL COMMENT 'Valor hora del salario',
  `codigo_usuario_registra` smallint(4) unsigned zerofill NOT NULL COMMENT 'Codigo del Usuario que genera el registro',
  `codigo_usuario_modifica` smallint(4) unsigned zerofill NOT NULL COMMENT 'Codigo del ultimo Usuario que modifica el registro',
  PRIMARY KEY  (`ano_generacion`,`mes_generacion`,`codigo_planilla`,`periodo_pago`,`documento_identidad_empleado`,`consecutivo`),
  KEY `job_movimiento_novedades_manuales_contrato_empleados` (`codigo_empresa`,`documento_identidad_empleado`,`fecha_ingreso`,`codigo_sucursal`,`fecha_ingreso_sucursal`),
  KEY `job_movimiento_novedades_manuales_planillas` (`codigo_planilla`),
  KEY `job_movimiento_novedades_manuales_transacciones_empleado` (`codigo_transaccion_contable`),
  KEY `job_movimiento_novedades_manuales_usuarios_ingresa` (`codigo_usuario_registra`),
  KEY `job_movimiento_novedades_manuales_usuarios_modifica` (`codigo_usuario_modifica`),
  KEY `job_movimiento_novedades_manuales_anexos_contables` (`codigo_anexo_contable`),
  KEY `job_movimiento_novedades_manuales_auxiliares_contables` (`codigo_empresa_auxiliar`,`codigo_anexo_contable`,`codigo_auxiliar_contable`),
  KEY `movimiento_novedades_manuales_auxiliares_plan_contable` (`codigo_contable`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_movimiento_novedades_manuales`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_movimiento_retiro_cesantias`
-- 

DROP TABLE IF EXISTS `job_movimiento_retiro_cesantias`;
CREATE TABLE `job_movimiento_retiro_cesantias` (
  `documento_identidad_empleado` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Documento de identidad empleado en la sucursales contrato empleados',
  `fecha_generacion` datetime NOT NULL COMMENT 'Fecha en la se genero el prestamo',
  `consecutivo` int(8) unsigned zerofill NOT NULL COMMENT 'Consecutivo interno para la base de datos',
  `concepto_retiro` smallint(4) unsigned zerofill NOT NULL COMMENT 'Código que identifica el concepto de prestamo',
  `flujo_efectivo` enum('1','2','3') collate latin1_spanish_ci NOT NULL COMMENT '1->No afecta flujo 2->Caja 3->Bancos',
  `codigo_plan_contable` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo de la cuenta contable segun el PUC',
  `codigo_sucursal_pertence` mediumint(5) unsigned zerofill NOT NULL COMMENT 'codigo de la sucursal con la que se relaciona la sucursal',
  `tipo_documento_cuenta_bancaria` smallint(3) unsigned zerofill NOT NULL COMMENT 'Código asignado por el usuario',
  `codigo_sucursal_banco` smallint(2) unsigned zerofill NOT NULL COMMENT 'codigo de la tabla bancos',
  `codigo_iso` varchar(2) collate latin1_spanish_ci NOT NULL COMMENT 'Llave principal de la tabla paises',
  `codigo_dane_departamento` varchar(2) collate latin1_spanish_ci NOT NULL COMMENT 'Código DANE',
  `codigo_dane_municipio` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'Código DANE',
  `codigo_banco` smallint(2) unsigned zerofill NOT NULL COMMENT 'id de la tabla bancos',
  `numero` varchar(30) collate latin1_spanish_ci NOT NULL COMMENT 'Numero de la cuenta',
  KEY `movimiento_retiro_cesantias` (`documento_identidad_empleado`,`consecutivo`,`fecha_generacion`,`concepto_retiro`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_movimiento_retiro_cesantias`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_movimiento_tiempos_laborados`
-- 

DROP TABLE IF EXISTS `job_movimiento_tiempos_laborados`;
CREATE TABLE `job_movimiento_tiempos_laborados` (
  `fecha_generacion` datetime NOT NULL COMMENT 'Fecha en la se genero el movimiento de tiempo',
  `codigo_empresa` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa donde va a laborar',
  `documento_identidad_empleado` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Documento de identidad empleado en la sucursales contrato empleados',
  `fecha_ingreso` date NOT NULL COMMENT 'Fecha en la que ingreso el empleado a la empresa',
  `codigo_sucursal` mediumint(5) unsigned zerofill NOT NULL COMMENT 'Id de la tabla de sucursales',
  `fecha_ingreso_sucursal` date NOT NULL COMMENT 'Fecha en la que inicia labores en la sucursal el empleado',
  `consecutivo` int(8) unsigned zerofill NOT NULL COMMENT 'Consecutivo interno para la base de datos',
  `codigo_planilla` smallint(3) unsigned zerofill NOT NULL COMMENT 'Id de la tabla de planillas',
  `fecha_inicio` date NOT NULL COMMENT 'Fecha en que inicia el turno',
  `hora_inicio` time NOT NULL COMMENT 'Hora en que inicia el turno',
  `fecha_fin` date NOT NULL COMMENT 'Fecha en que finaliza el turno',
  `hora_fin` time NOT NULL COMMENT 'Hora en que finaliza el turno',
  `codigo_transaccion_tiempo` int(8) unsigned zerofill NOT NULL COMMENT 'Id de la tabla de transacciones de tiempo',
  `codigo_transaccion_contable` int(8) unsigned zerofill NOT NULL COMMENT 'Id de la tabla de transacciones contables',
  `codigo_contable` int(4) NOT NULL COMMENT 'Codigo contable de la transaccion contable',
  `tasa` decimal(7,4) NOT NULL default '0.0000' COMMENT 'Porcentaje que corresponde sobre la hora de salario',
  `sentido` enum('D','C') collate latin1_spanish_ci NOT NULL COMMENT 'D->Debito C->Credito',
  `ano_generacion` int(4) unsigned zerofill NOT NULL COMMENT 'Anio de la generacion la planilla',
  `mes_generacion` int(2) unsigned zerofill NOT NULL COMMENT 'Mes de generacion de la planilla',
  `fecha_pago_planilla` date NOT NULL COMMENT 'Fecha rango de pago de la planilla',
  `codigo_empresa_auxiliar` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa para la llave de auxiliares',
  `codigo_anexo_contable` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo del anexo que permite dividir las cuentas',
  `codigo_auxiliar_contable` int(8) unsigned zerofill NOT NULL COMMENT 'Codigo donde se acumulara la informacion',
  `periodo_pago` enum('1','2','3','4','5','6','7','8','9') collate latin1_spanish_ci NOT NULL COMMENT '1->Mensual 2->Primera quincena 3->Segunda quincena 4->Primera semana 5->Segunda semana 6->Tercer semana 7->Cuarta semana 8->Quinta semana 9->Fecha unica',
  `cantidad_horas` smallint(3) NOT NULL COMMENT 'Cantidad de horas trabajadas',
  `cantidad_minutos` smallint(4) NOT NULL COMMENT 'Cantidad de minutos trabajadas',
  `valor_hora_salario` decimal(15,2) NOT NULL COMMENT 'Valor hora del salario',
  `valor_hora_recargo` decimal(15,2) NOT NULL COMMENT 'Valor diario del registro',
  `valor_movimiento` decimal(15,2) NOT NULL default '0.00' COMMENT 'Valor del movimiento que se genero',
  `contabilizado` enum('0','1','2') collate latin1_spanish_ci NOT NULL default '0' COMMENT 'Esta contabilizado 0->No 1->Si 2->Leido para liquidar salario',
  `codigo_usuario_registra` smallint(4) unsigned zerofill NOT NULL COMMENT 'Codigo del Usuario que genera el registro',
  `codigo_usuario_modifica` smallint(4) unsigned zerofill NOT NULL COMMENT 'Codigo del ultimo Usuario que modifica el registro',
  PRIMARY KEY  (`codigo_empresa`,`documento_identidad_empleado`,`fecha_ingreso`,`codigo_sucursal`,`fecha_ingreso_sucursal`,`consecutivo`),
  KEY `movimiento_tiempos_laborados_codigo_planilla` (`codigo_planilla`),
  KEY `movimientos_tiempos_laborados_transaccion_contable` (`codigo_transaccion_contable`),
  KEY `movimientos_tiempos_laborados_transaccion_tiempo` (`codigo_transaccion_tiempo`),
  KEY `tiempos_laborados_usuarios_registra` (`codigo_usuario_registra`),
  KEY `tiempos_laborados_usuarios_modifica` (`codigo_usuario_modifica`),
  KEY `tiempos_laborados_anexo` (`codigo_anexo_contable`),
  KEY `movimiento_tiempos_laborados_auxiliar_contable` (`codigo_empresa_auxiliar`,`codigo_anexo_contable`,`codigo_auxiliar_contable`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_movimiento_tiempos_laborados`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_movimiento_tiempos_no_laborados_dias`
-- 

DROP TABLE IF EXISTS `job_movimiento_tiempos_no_laborados_dias`;
CREATE TABLE `job_movimiento_tiempos_no_laborados_dias` (
  `fecha_generacion` datetime NOT NULL COMMENT 'Fecha en la se genero el tiempo',
  `codigo_empresa` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa donde labora el empleado',
  `documento_identidad_empleado` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Documento de identidad empleado en la tabla sucursal contrato empleado',
  `fecha_ingreso` date NOT NULL COMMENT 'Fecha en la que inicia labores el empleado',
  `codigo_sucursal` mediumint(5) unsigned zerofill NOT NULL COMMENT 'Codigo de la sucursal donde labora el empleado',
  `fecha_ingreso_sucursal` date NOT NULL COMMENT 'Fecha en la que inicia labores en la sucursal el empleado',
  `ano_generacion` int(4) unsigned zerofill NOT NULL COMMENT 'Año de la generacion la planilla',
  `mes_generacion` int(2) unsigned zerofill NOT NULL COMMENT 'Mes de generacion de la planilla',
  `codigo_planilla` smallint(3) unsigned zerofill NOT NULL COMMENT 'Código interno que identifica la planilla',
  `fecha_pago_planilla` date NOT NULL COMMENT 'Fecha rango de pago de la planilla',
  `codigo_empresa_auxiliar` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa para la llave de auxiliares',
  `codigo_anexo_contable` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'CÃ³digo del anexo que permite dividir las cuentas',
  `codigo_auxiliar_contable` int(8) unsigned zerofill NOT NULL COMMENT 'Código donde se acumulara la información',
  `periodo_pago` enum('1','2','3','4','5','6','7','8','9') collate latin1_spanish_ci NOT NULL COMMENT '1->Mensual 2->Primera quincena 3->Segunda quincena 4->Primera semana 5->Segunda semana 6->Tercer semana 7->Cuarta semana 8->Quinta semana 9->Fecha unica',
  `contabilizado` enum('0','1','2') collate latin1_spanish_ci NOT NULL default '1' COMMENT '0->No 1->Si 2->Leido para liquidar salario',
  `fecha_inicio_tiempo` date NOT NULL COMMENT 'Fecha inicio del reporte',
  `fecha_tiempo` date NOT NULL COMMENT 'Fecha que cubre la incapacidad',
  `codigo_transaccion_tiempo` int(8) unsigned zerofill NOT NULL COMMENT 'Id de la tabla de transacciones de tiempo',
  `codigo_transaccion_contable` int(8) unsigned zerofill NOT NULL COMMENT 'Id de la tabla de transacciones contables del Empleado',
  `codigo_contable` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo contable de la transaccion contable',
  `sentido` enum('D','C') collate latin1_spanish_ci NOT NULL COMMENT 'D->Débito C->Crédito',
  `dias_no_laborados` smallint(3) NOT NULL COMMENT 'Número de días reportados',
  `valor_dia` decimal(11,2) NOT NULL COMMENT 'Valor diario del registro',
  `codigo_motivo_no_laboral` smallint(3) unsigned zerofill NOT NULL COMMENT 'Id de la tabla de motivos de incapacidad',
  `codigo_usuario_registra` smallint(4) unsigned zerofill NOT NULL COMMENT 'Codigo del Usuario que genera el registro',
  `codigo_usuario_modifica` smallint(4) unsigned zerofill default NULL COMMENT 'Codigo del ultimo Usuario que modifica el registro',
  PRIMARY KEY  (`documento_identidad_empleado`,`fecha_tiempo`),
  KEY `movimiento_tiempos_no_laborados_dias_sucursal_contrato` (`codigo_empresa`,`documento_identidad_empleado`,`fecha_ingreso`,`codigo_sucursal`,`fecha_ingreso_sucursal`),
  KEY `movimiento_tiempos_no_laborados_dias_codigo_planilla` (`codigo_planilla`),
  KEY `movimiento_tiempos_no_laborados_dias_motivo` (`codigo_motivo_no_laboral`),
  KEY `movimiento_tiempos_no_laborados_dias_transaccion_tiempo` (`codigo_transaccion_tiempo`),
  KEY `movimiento_tiempos_no_laborados_dias_transaccion_contable` (`codigo_transaccion_contable`),
  KEY `movimiento_tiempos_no_laborados_dias_plan_contable` (`codigo_contable`),
  KEY `movimiento_tiempos_no_laborados_dias_usuarios_registra` (`codigo_usuario_registra`),
  KEY `movimiento_tiempos_no_laborados_dias_usuarios_modifica` (`codigo_usuario_modifica`),
  KEY `movimiento_tiempos_no_laborados_dias_anexo` (`codigo_anexo_contable`),
  KEY `movimiento_tiempos_no_laborados_dias_auxiliar_contable` (`codigo_empresa_auxiliar`,`codigo_anexo_contable`,`codigo_auxiliar_contable`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_movimiento_tiempos_no_laborados_dias`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_movimiento_tiempos_no_laborados_horas`
-- 

DROP TABLE IF EXISTS `job_movimiento_tiempos_no_laborados_horas`;
CREATE TABLE `job_movimiento_tiempos_no_laborados_horas` (
  `fecha_generacion` datetime NOT NULL COMMENT 'Fecha en la segenero el movimiento de tiempo',
  `codigo_empresa` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa donde va a laborar',
  `documento_identidad_empleado` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Documento de identidad empleado en la sucursales contrato empleados',
  `fecha_ingreso` date NOT NULL COMMENT 'Fecha en la que ingreso el empleado a la empresa',
  `codigo_sucursal` mediumint(5) unsigned zerofill NOT NULL COMMENT 'Id de la tabla de sucursales',
  `fecha_ingreso_sucursal` date NOT NULL COMMENT 'Fecha en la que inicia labores en la sucursal el empleado',
  `consecutivo` int(8) unsigned zerofill NOT NULL COMMENT 'Consecutivo interno para la base de datos',
  `codigo_planilla` smallint(3) unsigned zerofill NOT NULL COMMENT 'Id de la tabla de planillas',
  `fecha_registro` date NOT NULL COMMENT 'Fecha en la que ingreso el empleado a la empresa',
  `hora_inicio` time NOT NULL COMMENT 'Hora en que inicia el turno',
  `hora_fin` time NOT NULL COMMENT 'Hora en que finaliza el turno',
  `codigo_transaccion_tiempo` int(8) unsigned zerofill NOT NULL COMMENT 'Id de la tabla de transacciones de tiempo',
  `codigo_transaccion_contable` int(8) unsigned zerofill NOT NULL COMMENT 'Id de la tabla de transacciones contables',
  `codigo_contable` int(4) NOT NULL COMMENT 'Codigo contable de la transaccion contable',
  `sentido` enum('D','C') collate latin1_spanish_ci NOT NULL COMMENT 'D->Debito C->Credito',
  `tasa` decimal(7,4) NOT NULL default '0.0000' COMMENT 'Porcentaje que corresponde sobre la hora de salario',
  `ano_generacion` int(4) unsigned zerofill NOT NULL COMMENT 'Anio de la generacion la planilla',
  `mes_generacion` int(2) unsigned zerofill NOT NULL COMMENT 'Mes de generacion de la planilla',
  `fecha_pago_planilla` date NOT NULL COMMENT 'Fecha rango de pago de la planilla',
  `codigo_empresa_auxiliar` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa para la llave de auxiliares',
  `codigo_anexo_contable` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo del anexo que permite dividir las cuentas',
  `codigo_auxiliar_contable` int(8) unsigned zerofill NOT NULL COMMENT 'Codigo donde se acumulara la informacion',
  `periodo_pago` enum('1','2','3','4','5','6','7','8','9') collate latin1_spanish_ci NOT NULL COMMENT '1->Mensual 2->Primera quincena 3->Segunda quincena 4->Primera semana 5->Segunda semana 6->Tercer semana 7->Cuarta semana 8->Quinta semana 9->Fecha unica',
  `cantidad_minutos` smallint(4) NOT NULL COMMENT 'Cantidad de minutos trabajadas',
  `valor_hora_salario` decimal(15,2) NOT NULL COMMENT 'Valor hora del salario',
  `valor_movimiento` decimal(15,2) NOT NULL default '0.00' COMMENT 'Valor del movimiento que se genero',
  `contabilizado` enum('0','1','2') collate latin1_spanish_ci NOT NULL default '0' COMMENT 'Esta contabilizado 0->No 1->Si 2->Leido para liquidar salario',
  `codigo_motivo_no_laboral` smallint(3) unsigned zerofill NOT NULL COMMENT 'Id de la tabla de motivos de incapacidad',
  `codigo_usuario_registra` smallint(4) unsigned zerofill NOT NULL COMMENT 'Codigo del Usuario que genera el registro',
  `codigo_usuario_modifica` smallint(4) unsigned zerofill NOT NULL COMMENT 'Codigo del ultimo Usuario que modifica el registro',
  PRIMARY KEY  (`codigo_empresa`,`documento_identidad_empleado`,`fecha_ingreso`,`codigo_sucursal`,`fecha_ingreso_sucursal`,`consecutivo`),
  KEY `movimiento_tiempos_no_laborados_horas_motivo` (`codigo_motivo_no_laboral`),
  KEY `movimiento_tiempos_no_laborados_horas_codigo_planilla` (`codigo_planilla`),
  KEY `movimiento_tiempos_no_laborados_horas_transaccion_contable` (`codigo_transaccion_contable`),
  KEY `movimiento_tiempos_no_laborados_horas_transaccion_tiempo` (`codigo_transaccion_tiempo`),
  KEY `movimiento_tiempos_no_laborados_horass_usuarios_registra` (`codigo_usuario_registra`),
  KEY `movimiento_tiempos_no_laborados_horas_usuarios_modifica` (`codigo_usuario_modifica`),
  KEY `movimiento_tiempos_no_laborados_horas_anexo` (`codigo_anexo_contable`),
  KEY `movimiento_tiempos_no_laborados_horas_auxiliar_contable` (`codigo_empresa_auxiliar`,`codigo_anexo_contable`,`codigo_auxiliar_contable`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_movimiento_tiempos_no_laborados_horas`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_movimientos_auxilio_transporte`
-- 

DROP TABLE IF EXISTS `job_movimientos_auxilio_transporte`;
CREATE TABLE `job_movimientos_auxilio_transporte` (
  `codigo_sucursal` mediumint(5) unsigned zerofill NOT NULL COMMENT 'Codigo del almacen donde va a laborar',
  `ano_generacion` int(4) unsigned zerofill NOT NULL COMMENT 'Código interno que identifica la planilla',
  `mes_generacion` int(2) unsigned zerofill NOT NULL COMMENT 'Código interno que identifica la planilla',
  `codigo_planilla` smallint(3) unsigned zerofill NOT NULL COMMENT 'Código interno que identifica la planilla',
  `periodo_pago` enum('1','2','3','4','5','6','7','8') collate latin1_spanish_ci NOT NULL COMMENT '1->Mensual 2->Primera quincena 3->Segunda quincena 4->Primera semana 5->Segunda semana 6->Tercer semana 7->Cuarta semana 8->Quinta semana',
  `codigo_transaccion_contable` int(8) unsigned zerofill NOT NULL COMMENT 'Id de la tabla de transacciones contables',
  `consecutivo` smallint(4) unsigned zerofill NOT NULL COMMENT 'Consecutivo para el movimiento',
  `codigo_empresa` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo del almacen donde va a laborar',
  `documento_identidad_empleado` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Documento de identidad que identifica el empleado en terceras personas',
  `fecha_ingreso_empresa` date NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado',
  `fecha_ingreso_sucursal` date NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado',
  `fecha_pago_planilla` date NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado',
  `fecha_ingreso_planilla` date NOT NULL COMMENT 'Fecha en la que genera la planilla',
  `fecha_inicio_pago` date NOT NULL COMMENT 'Fecha en la que inicia liquidación',
  `fecha_hasta_pago` date NOT NULL COMMENT 'Fecha en la que termina liquidación',
  `codigo_empresa_auxiliar` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa del auxiliar contable',
  `codigo_anexo_contable` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'Código del anexo que permite dividir las cuentas',
  `codigo_auxiliar_contable` int(8) unsigned zerofill NOT NULL COMMENT 'Código donde se acumulara la información',
  `codigo_contable` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo contable de la transaccion contable',
  `sentido` enum('D','C') collate latin1_spanish_ci NOT NULL COMMENT 'D->Débito C->Crédito',
  `codigo_transaccion_tiempo` int(8) unsigned zerofill NOT NULL COMMENT 'Codigo de la tabla de transacciones de tiempo',
  `dias_trabajados` smallint(3) NOT NULL COMMENT 'Dias laborados en el periodo',
  `dias_auxilio` smallint(3) NOT NULL COMMENT 'Dias que se cancelan de auxlio de trasnporte',
  `salario_mensual` decimal(11,2) NOT NULL COMMENT 'Valor mensual del salario',
  `valor_movimiento` decimal(11,2) NOT NULL COMMENT 'Valor diario del registro',
  `contabilizado` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT '0->No 1->Si',
  `codigo_usuario_genera` smallint(4) unsigned zerofill NOT NULL COMMENT 'Codigo usuario que genera el registro',
  PRIMARY KEY  (`codigo_sucursal`,`ano_generacion`,`mes_generacion`,`codigo_planilla`,`periodo_pago`,`codigo_transaccion_contable`,`consecutivo`),
  KEY `movimiento_auxilio_sucursal_contrato` (`codigo_empresa`,`documento_identidad_empleado`,`fecha_ingreso_empresa`,`codigo_sucursal`,`fecha_ingreso_sucursal`),
  KEY `movimiento_auxilio_codigo_planilla` (`codigo_planilla`),
  KEY `movimiento_auxilio_transaccion_contable` (`codigo_transaccion_contable`),
  KEY `movimiento_auxilio_transaccion_tiempo` (`codigo_transaccion_tiempo`),
  KEY `movimiento_auxilio_anexo` (`codigo_anexo_contable`),
  KEY `movimiento_auxilio_auxiliar_contable` (`codigo_empresa_auxiliar`,`codigo_anexo_contable`,`codigo_auxiliar_contable`),
  KEY `movimiento_auxilio_plan_contable` (`codigo_contable`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_movimientos_auxilio_transporte`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_movimientos_contables`
-- 

DROP TABLE IF EXISTS `job_movimientos_contables`;
CREATE TABLE `job_movimientos_contables` (
  `codigo_sucursal` mediumint(5) unsigned zerofill NOT NULL COMMENT 'Codigo de la sucursal donse se genera el movimiento',
  `documento_identidad_tercero` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Número del documento de identidad',
  `codigo_tipo_comprobante` smallint(2) unsigned zerofill NOT NULL COMMENT 'Codigo del tipo de comprobante',
  `numero_comprobante` varchar(20) collate latin1_spanish_ci NOT NULL COMMENT 'Número de comprobante contable',
  `codigo_tipo_documento` smallint(3) unsigned zerofill NOT NULL COMMENT 'Id tabla consecutivo de documentos',
  `consecutivo_documento` int(8) unsigned zerofill NOT NULL COMMENT 'Consecutivo del documento',
  `fecha_contabilizacion` date NOT NULL COMMENT 'Fecha en que se contabiliza la transaccion',
  `fecha_transaccion` date NOT NULL COMMENT 'Fecha en la que se genera la transaccion',
  `fecha_documento` date NOT NULL COMMENT 'Fecha del documento que genera',
  `estado` enum('1','2') collate latin1_spanish_ci default '1' COMMENT 'Estado en que se encuentra el movimiento: 1->Activo, 2->Anulado',
  `observaciones` varchar(255) collate latin1_spanish_ci default NULL COMMENT 'Observaciones para el movimiento',
  `codigo_usuario_genera` smallint(4) unsigned zerofill NOT NULL COMMENT 'Codigo del usuario que genera',
  PRIMARY KEY  (`codigo_sucursal`,`documento_identidad_tercero`,`codigo_tipo_comprobante`,`numero_comprobante`,`codigo_tipo_documento`,`consecutivo_documento`,`fecha_contabilizacion`),
  KEY `movimientos_contables_usuario` (`codigo_usuario_genera`),
  KEY `movimientos_contables_comprobante` (`codigo_tipo_comprobante`),
  KEY `movimientos_contables_tercero` (`documento_identidad_tercero`),
  KEY `movimientos_contables_consecutivo_documentos` (`codigo_sucursal`,`codigo_tipo_documento`,`documento_identidad_tercero`,`fecha_contabilizacion`,`consecutivo_documento`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_movimientos_contables`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_movimientos_nomina_migracion`
-- 

DROP TABLE IF EXISTS `job_movimientos_nomina_migracion`;
CREATE TABLE `job_movimientos_nomina_migracion` (
  `ano_generacion` int(4) unsigned zerofill NOT NULL COMMENT 'Codigo interno que identifica la planilla',
  `mes_generacion` int(2) unsigned zerofill NOT NULL COMMENT 'Codigo interno que identifica la planilla',
  `codigo_planilla` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo interno que identifica la planilla',
  `periodo_pago` enum('1','2','3','4','5','6','7','8','9') collate latin1_spanish_ci NOT NULL COMMENT '1->Mensual 2->Primera quincena 3->Segunda quincena 4->Primera semana 5->Segunda semana 6->Tercer semana 7->Cuarta semana 8->Quinta semana 9->Fecha unica',
  `codigo_transaccion_contable` int(8) unsigned zerofill NOT NULL COMMENT 'Codigo de la tabla de transacciones contables empleados',
  `consecutivo` smallint(4) unsigned zerofill NOT NULL COMMENT 'Consecutivo para el movimiento',
  `codigo_empresa` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo del almacen donde va a laborar',
  `documento_identidad_empleado` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Documento de identidad que identifica el empleado en terceras personas',
  `fecha_ingreso_empresa` date NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado',
  `codigo_sucursal` mediumint(5) unsigned zerofill NOT NULL COMMENT 'Codigo del almacen donde va a laborar',
  `fecha_ingreso_sucursal` date NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado',
  `fecha_pago_planilla` date NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado',
  `codigo_empresa_auxiliar` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa del auxiliar contable',
  `codigo_anexo_contable` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo del anexo que permite dividir las cuentas',
  `codigo_auxiliar_contable` int(8) unsigned zerofill NOT NULL COMMENT 'Codigo donde se acumulara la informacion',
  `codigo_contable` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo contable de la transaccion contable',
  `sentido` enum('D','C') collate latin1_spanish_ci NOT NULL COMMENT 'D->Debito C->Credito',
  `valor_movimiento` decimal(11,2) NOT NULL COMMENT 'Valor diario del registro',
  `contabilizado` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT '0->No 1->Si',
  `codigo_usuario_genera` smallint(4) unsigned zerofill NOT NULL COMMENT 'Codigo usuario que genera el registro',
  `fecha_registro` datetime NOT NULL COMMENT 'Fecha de generaciÃ³n del movimiento',
  `codigo_usuario_modifica` smallint(4) unsigned zerofill NOT NULL COMMENT 'Codigo usuario que genera el registro',
  `fecha_modificacion` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP COMMENT 'Fecha de modificaciÃ³n de l movimiento',
  PRIMARY KEY  (`ano_generacion`,`mes_generacion`,`codigo_planilla`,`periodo_pago`,`codigo_transaccion_contable`,`consecutivo`),
  KEY `movimiento_nomina_migracion_sucursal_contrato_empleado` (`codigo_empresa`,`documento_identidad_empleado`,`fecha_ingreso_empresa`,`codigo_sucursal`,`fecha_ingreso_sucursal`),
  KEY `movimiento_nomina_migracion_codigo_planilla` (`codigo_planilla`),
  KEY `movimiento_nomina_migracion_transaccion_contable` (`codigo_transaccion_contable`),
  KEY `movimiento_nomina_migracion_anexo` (`codigo_anexo_contable`),
  KEY `movimiento_nomina_migracion_auxiliar_contable` (`codigo_empresa_auxiliar`,`codigo_anexo_contable`,`codigo_auxiliar_contable`),
  KEY `movimiento_nomina_migracion_usuario` (`codigo_usuario_genera`),
  KEY `movimiento_nomina_migracion_usuario_modifica` (`codigo_usuario_modifica`),
  KEY `movimiento_nomina_migracion_plan_contable` (`codigo_contable`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_movimientos_nomina_migracion`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_movimientos_pension`
-- 

DROP TABLE IF EXISTS `job_movimientos_pension`;
CREATE TABLE `job_movimientos_pension` (
  `ano_generacion` int(4) unsigned zerofill NOT NULL COMMENT 'Codigo interno que identifica la planilla',
  `mes_generacion` int(2) unsigned zerofill NOT NULL COMMENT 'Codigo interno que identifica la planilla',
  `codigo_planilla` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo interno que identifica la planilla',
  `periodo_pago` enum('1','2','3','4','5','6','7','8','9') collate latin1_spanish_ci NOT NULL COMMENT '1->Mensual 2->Primera quincena 3->Segunda quincena 4->Primera semana 5->Segunda semana 6->Tercer semana 7->Cuarta semana 8->Quinta semana 9->Fecha unica',
  `codigo_transaccion_contable` int(8) unsigned zerofill NOT NULL COMMENT 'Id de la tabla de transacciones contables',
  `codigo_empresa` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo del almacen donde va a laborar',
  `documento_identidad_empleado` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Documento de identidad que identifica el empleado en terceras personas',
  `fecha_ingreso_empresa` date NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado',
  `codigo_sucursal` mediumint(5) unsigned zerofill NOT NULL COMMENT 'Codigo del almacen donde va a laborar',
  `fecha_ingreso_sucursal` date NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado',
  `fecha_pago_planilla` date NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado',
  `fecha_ingreso_planilla` date NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado',
  `fecha_inicio_pago` date NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado',
  `fecha_hasta_pago` date NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado',
  `codigo_empresa_auxiliar` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa del auxiliar contable',
  `codigo_anexo_contable` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'CÃ³digo del anexo que permite dividir las cuentas',
  `codigo_auxiliar_contable` int(8) unsigned zerofill NOT NULL COMMENT 'CÃ³digo donde se acumulara la informacion',
  `codigo_entidad_pension` int(8) unsigned zerofill NOT NULL COMMENT 'Codigo de la entidad parafiscal',
  `codigo_contable` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo contable de la transaccion contable',
  `sentido` enum('D','C') collate latin1_spanish_ci NOT NULL COMMENT 'D->Debito C->Credito',
  `codigo_transaccion_tiempo` int(8) unsigned zerofill NOT NULL COMMENT 'Codigo de la tabla de transacciones de tiempo',
  `dias_trabajados` smallint(3) NOT NULL COMMENT 'Dias laborados en el periodo',
  `salario` decimal(11,2) NOT NULL COMMENT 'Valor mensual del salario',
  `valor_movimiento` decimal(11,2) NOT NULL COMMENT 'Valor diario del registro',
  `ibc_pension` decimal(11,2) NOT NULL COMMENT 'Valor sobre el cual se calcula el aporte',
  `porcentaje_tasa_pension` decimal(7,4) NOT NULL default '0.0000' COMMENT 'Porcentaje de la tasa',
  `contabilizado` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT '0->No 1->Si',
  `codigo_usuario_genera` smallint(4) unsigned zerofill NOT NULL COMMENT 'Codigo usuario que genera el registro',
  PRIMARY KEY  (`ano_generacion`,`mes_generacion`,`codigo_planilla`,`periodo_pago`,`fecha_pago_planilla`,`documento_identidad_empleado`,`codigo_sucursal`),
  KEY `movimiento_pension_sucursal_contrato` (`codigo_empresa`,`documento_identidad_empleado`,`fecha_ingreso_empresa`,`codigo_sucursal`,`fecha_ingreso_sucursal`),
  KEY `movimiento_pension_codigo_planilla` (`codigo_planilla`),
  KEY `movimiento_pension_transaccion_contable` (`codigo_transaccion_contable`),
  KEY `movimiento_pension_transaccion_tiempo` (`codigo_transaccion_tiempo`),
  KEY `movimiento_pension_anexo` (`codigo_anexo_contable`),
  KEY `movimiento_pension_auxiliar_contable` (`codigo_empresa_auxiliar`,`codigo_anexo_contable`,`codigo_auxiliar_contable`),
  KEY `movimiento_pension_entidades_parafiscales` (`codigo_entidad_pension`),
  KEY `movimiento_pension_usuario` (`codigo_usuario_genera`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_movimientos_pension`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_movimientos_prestamos_empleados`
-- 

DROP TABLE IF EXISTS `job_movimientos_prestamos_empleados`;
CREATE TABLE `job_movimientos_prestamos_empleados` (
  `documento_identidad_empleado` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Documento de identidad empleado en la sucursales contrato empleados',
  `fecha_generacion` date NOT NULL COMMENT 'Fecha en la se genero el prestamo',
  `consecutivo` int(8) unsigned zerofill NOT NULL COMMENT 'Consecutivo interno para la base de datos',
  `concepto_prestamo` smallint(4) unsigned zerofill NOT NULL COMMENT 'Código que identifica el concepto de prestamo',
  `codigo_empresa_auxiliar` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa del auxiliar contable',
  `codigo_anexo_contable` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'CÃ³digo del anexo que permite dividir las cuentas',
  `codigo_auxiliar_contable` int(8) unsigned zerofill NOT NULL COMMENT 'Código donde se acumulara la información',
  `flujo_efectivo` enum('1','2','3') collate latin1_spanish_ci NOT NULL COMMENT '1->No afecta flujo 2->Caja 3->Bancos',
  `codigo_plan_contable` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo de la cuenta contable segun el PUC',
  `valor_movimiento` decimal(11,2) unsigned NOT NULL COMMENT 'Valor total del prestamo',
  `sentido` enum('D','C') collate latin1_spanish_ci NOT NULL default 'D' COMMENT 'D->Débito C->Crédito',
  `codigo_sucursal_pertence` mediumint(5) unsigned zerofill NOT NULL COMMENT 'codigo de la sucursal con la que se relaciona la sucursal',
  `tipo_documento_cuenta_bancaria` smallint(3) unsigned zerofill NOT NULL COMMENT 'Código asignado por el usuario',
  `codigo_sucursal_banco` smallint(2) unsigned zerofill NOT NULL COMMENT 'codigo de la tabla bancos',
  `codigo_iso` varchar(2) collate latin1_spanish_ci NOT NULL COMMENT 'Llave principal de la tabla paises',
  `codigo_dane_departamento` varchar(2) collate latin1_spanish_ci NOT NULL COMMENT 'Código DANE',
  `codigo_dane_municipio` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'Código DANE',
  `codigo_banco` smallint(2) unsigned zerofill NOT NULL COMMENT 'id de la tabla bancos',
  `numero` varchar(30) collate latin1_spanish_ci NOT NULL COMMENT 'Numero de la cuenta',
  PRIMARY KEY  (`documento_identidad_empleado`,`consecutivo`,`fecha_generacion`,`concepto_prestamo`),
  KEY `movimientos_prestamos_bancarias` (`codigo_sucursal_pertence`,`tipo_documento_cuenta_bancaria`,`codigo_sucursal_banco`,`codigo_iso`,`codigo_dane_departamento`,`codigo_dane_municipio`,`codigo_banco`,`numero`),
  KEY `movimientos_prestamos_empleados_contable` (`codigo_empresa_auxiliar`,`codigo_anexo_contable`,`codigo_auxiliar_contable`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_movimientos_prestamos_empleados`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_movimientos_prima`
-- 

DROP TABLE IF EXISTS `job_movimientos_prima`;
CREATE TABLE `job_movimientos_prima` (
  `ano_generacion` int(4) unsigned zerofill NOT NULL COMMENT 'Codigo interno que identifica la planilla',
  `mes_generacion` int(2) unsigned zerofill NOT NULL COMMENT 'Codigo interno que identifica la planilla',
  `codigo_planilla` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo interno que identifica la planilla',
  `periodo_pago` enum('1','2','3','4','5','6','7','8','9') collate latin1_spanish_ci NOT NULL COMMENT '1->Mensual 2->Primera quincena 3->Segunda quincena 4->Primera semana 5->Segunda semana 6->Tercer semana 7->Cuarta semana 8->Quinta semana 9->Fecha unica',
  `codigo_transaccion_contable` int(8) unsigned zerofill NOT NULL COMMENT 'Id de la tabla de transacciones contables',
  `codigo_empresa` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo del almacen donde va a laborar',
  `documento_identidad_empleado` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Documento de identidad que identifica el empleado en terceras personas',
  `fecha_ingreso_empresa` date NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado',
  `codigo_sucursal` mediumint(5) unsigned zerofill NOT NULL COMMENT 'Codigo del almacen donde va a laborar',
  `fecha_ingreso_sucursal` date NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado',
  `fecha_pago_planilla` date NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado',
  `fecha_inicio_pago` date NOT NULL COMMENT 'Fecha en la que inicia el periodo a liquidar',
  `fecha_hasta_pago` date NOT NULL COMMENT 'Fecha en la que termina el periodo a liquidar',
  `fecha_inicio_promedio` date NOT NULL COMMENT 'Fecha en la que inicia el calculo de promedios para liquidar',
  `fecha_hasta_promedio` date NOT NULL COMMENT 'Fecha en la que termina el calculo de promedios para liquidar',
  `codigo_empresa_auxiliar` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa del auxiliar contable',
  `codigo_anexo_contable` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo del anexo que permite dividir las cuentas',
  `codigo_auxiliar_contable` int(8) unsigned zerofill NOT NULL COMMENT 'Codigo donde se acumulara la informacion',
  `codigo_contable` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo contable de la transaccion contable',
  `sentido` enum('D','C') collate latin1_spanish_ci NOT NULL COMMENT 'D->Debito C->Credito',
  `dias_liquidados` smallint(3) NOT NULL COMMENT 'Dias liquidados en el periodo',
  `dias_promedio` smallint(3) NOT NULL COMMENT 'Dias tomados para el promedio',
  `salario` decimal(11,2) NOT NULL COMMENT 'Valor mensual del salario',
  `salario_promedio` decimal(11,2) NOT NULL COMMENT 'Valor del salario promedio',
  `auxilio_transporte` decimal(11,2) NOT NULL COMMENT 'Valor del auxilio de tranporte',
  `valor_ingresos_promedio` decimal(11,2) NOT NULL COMMENT 'Valor de todos los ingresos que acumulan para prima del periodo liquidado',
  `valor_movimiento` decimal(11,2) NOT NULL COMMENT 'Valor diario del registro',
  `contabilizado` enum('0','1','2') collate latin1_spanish_ci NOT NULL default '0' COMMENT '0->No 1->Si 2->Trasladado',
  `codigo_usuario_genera` smallint(4) unsigned zerofill NOT NULL COMMENT 'Codigo usuario que genera el registro',
  `fecha_registro` datetime NOT NULL COMMENT 'Fecha de generación de la planilla',
  PRIMARY KEY  (`ano_generacion`,`mes_generacion`,`codigo_planilla`,`periodo_pago`,`fecha_pago_planilla`,`documento_identidad_empleado`,`codigo_sucursal`),
  KEY `movimiento_prima_sucursal_contrato_empleado` (`codigo_empresa`,`documento_identidad_empleado`,`fecha_ingreso_empresa`,`codigo_sucursal`,`fecha_ingreso_sucursal`),
  KEY `movimiento_prima_codigo_planilla` (`codigo_planilla`),
  KEY `movimiento_prima_transaccion_contable` (`codigo_transaccion_contable`),
  KEY `movimiento_prima_anexo` (`codigo_anexo_contable`),
  KEY `movimiento_prima_auxiliar_contable` (`codigo_empresa_auxiliar`,`codigo_anexo_contable`,`codigo_auxiliar_contable`),
  KEY `movimiento_prima_usuario` (`codigo_usuario_genera`),
  KEY `movimiento_prima_plan_contable` (`codigo_contable`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_movimientos_prima`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_movimientos_salario_retroactivo`
-- 

DROP TABLE IF EXISTS `job_movimientos_salario_retroactivo`;
CREATE TABLE `job_movimientos_salario_retroactivo` (
  `ano_generacion` int(4) unsigned zerofill NOT NULL COMMENT 'Año de la generacion la planilla',
  `mes_generacion` int(2) unsigned zerofill NOT NULL COMMENT 'Mes de generacion de la planilla',
  `codigo_planilla` smallint(3) unsigned zerofill NOT NULL COMMENT 'Código interno que identifica la planilla',
  `periodo_pago` enum('1','2','3','4','5','6','7','8','9') collate latin1_spanish_ci NOT NULL COMMENT '1->Mensual 2->Primera quincena 3->Segunda quincena 4->Primera semana 5->Segunda semana 6->Tercer semana 7->Cuarta semana 8->Quinta semana 9->Fecha unica',
  `documento_identidad_empleado` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Documento de identidad que identifica el empleado en terceras personas',
  `codigo_transaccion_contable` int(8) unsigned zerofill NOT NULL COMMENT 'Id de la tabla de transacciones contables',
  `codigo_empresa` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo del almacen donde va a laborar',
  `fecha_ingreso_empresa` date NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado',
  `codigo_sucursal` mediumint(5) unsigned zerofill NOT NULL COMMENT 'Codigo del almacen donde va a laborar',
  `fecha_ingreso_sucursal` date NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado',
  `fecha_salario` date NOT NULL COMMENT 'Fecha en la que asignan salario',
  `fecha_pago_planilla` date NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado',
  `fecha_inicio_pago` date NOT NULL COMMENT 'Fecha inicial de liquidacion',
  `fecha_hasta_pago` date NOT NULL COMMENT 'Fecha final de liquidación',
  `codigo_empresa_auxiliar` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa del auxiliar contable',
  `codigo_anexo_contable` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'Código del anexo que permite dividir las cuentas',
  `codigo_auxiliar_contable` int(8) unsigned zerofill NOT NULL COMMENT 'Código donde se acumulara la información',
  `codigo_contable` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo contable de la transaccion contable',
  `sentido` enum('D','C') collate latin1_spanish_ci NOT NULL COMMENT 'D->Débito C->Crédito',
  `fecha_inicio_retroactivo` date NOT NULL COMMENT 'Fecha a partir de la cual se genera retroactivo',
  `dias_retroactivo` smallint(3) NOT NULL COMMENT 'Dias de retroactividad para calcular pago',
  `valor_movimiento` decimal(11,2) NOT NULL COMMENT 'Valor a cancelar por salario retroactivo',
  `contabilizado` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT '0->No 1->Si',
  `codigo_usuario_genera` smallint(4) unsigned zerofill NOT NULL COMMENT 'Codigo usuario que genera el registro',
  `fecha_registro` datetime NOT NULL COMMENT 'Fecha en la que se genera el movimiento',
  PRIMARY KEY  (`codigo_sucursal`,`ano_generacion`,`mes_generacion`,`codigo_planilla`,`periodo_pago`,`documento_identidad_empleado`,`codigo_transaccion_contable`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_movimientos_salario_retroactivo`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_movimientos_salarios`
-- 

DROP TABLE IF EXISTS `job_movimientos_salarios`;
CREATE TABLE `job_movimientos_salarios` (
  `codigo_sucursal` mediumint(5) unsigned zerofill NOT NULL COMMENT 'Codigo del almacen donde va a laborar',
  `ano_generacion` int(4) unsigned zerofill NOT NULL COMMENT 'Año de la generacion la planilla',
  `mes_generacion` int(2) unsigned zerofill NOT NULL COMMENT 'Mes de generacion de la planilla',
  `codigo_planilla` smallint(3) unsigned zerofill NOT NULL COMMENT 'Código interno que identifica la planilla',
  `periodo_pago` enum('1','2','3','4','5','6','7','8') collate latin1_spanish_ci NOT NULL COMMENT '1->Mensual 2->Primera quincena 3->Segunda quincena 4->Primera semana 5->Segunda semana 6->Tercer semana 7->Cuarta semana 8->Quinta semana 9->Fecha unica',
  `codigo_transaccion_contable` int(8) unsigned zerofill NOT NULL COMMENT 'Id de la tabla de transacciones contables',
  `consecutivo` smallint(4) unsigned zerofill NOT NULL COMMENT 'Consecutivo para el movimiento',
  `codigo_empresa` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo del almacen donde va a laborar',
  `documento_identidad_empleado` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Documento de identidad que identifica el empleado en terceras personas',
  `fecha_ingreso_empresa` date NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado',
  `fecha_ingreso_sucursal` date NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado',
  `fecha_pago_planilla` date NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado',
  `fecha_ingreso_planilla` date NOT NULL COMMENT 'Fecha en la que genera la planilla',
  `fecha_inicio_pago` date NOT NULL COMMENT 'Fecha inicial de liquidacion',
  `fecha_hasta_pago` date NOT NULL COMMENT 'Fecha final de liquidación',
  `codigo_empresa_auxiliar` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa del auxiliar contable',
  `codigo_anexo_contable` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'Código del anexo que permite dividir las cuentas',
  `codigo_auxiliar_contable` int(8) unsigned zerofill NOT NULL COMMENT 'Código donde se acumulara la información',
  `codigo_contable` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo contable de la transaccion contable',
  `sentido` enum('D','C') collate latin1_spanish_ci NOT NULL COMMENT 'D->Débito C->Crédito',
  `codigo_transaccion_tiempo` int(8) unsigned zerofill NOT NULL COMMENT 'Codigo de la tabla de transacciones de tiempo',
  `dias_trabajados` smallint(3) NOT NULL COMMENT 'Dias laborados en el periodo',
  `salario_mensual` decimal(11,2) NOT NULL COMMENT 'Valor mensual del salario',
  `valor_movimiento` decimal(11,2) NOT NULL COMMENT 'Valor diario del registro',
  `tabla_genera_movimiento` varchar(255) collate latin1_spanish_ci default NULL COMMENT 'Nombre de la tabla de donde proviene el movimiento',
  `llave_tabla_genera_movimiento` varchar(255) collate latin1_spanish_ci default NULL COMMENT 'Llave de la tabla de donde proviene el movimiento',
  `contabilizado` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT '0->No 1->Si 2->Leido para calcular salario',
  `codigo_usuario_genera` smallint(4) unsigned zerofill NOT NULL COMMENT 'Codigo usuario que genera el registro',
  PRIMARY KEY  (`codigo_sucursal`,`ano_generacion`,`mes_generacion`,`codigo_planilla`,`periodo_pago`,`codigo_transaccion_contable`,`consecutivo`),
  KEY `movimiento_salario_sucursal_contrato` (`codigo_empresa`,`documento_identidad_empleado`,`fecha_ingreso_empresa`,`codigo_sucursal`,`fecha_ingreso_sucursal`),
  KEY `movimiento_salario_codigo_planilla` (`codigo_planilla`),
  KEY `movimiento_salario_transaccion_contable` (`codigo_transaccion_contable`),
  KEY `movimiento_salario_transaccion_tiempo` (`codigo_transaccion_tiempo`),
  KEY `movimiento_salario_anexo` (`codigo_anexo_contable`),
  KEY `movimiento_salario_auxiliar_contable` (`codigo_empresa_auxiliar`,`codigo_anexo_contable`,`codigo_auxiliar_contable`),
  KEY `movimiento_salario_plan_contable` (`codigo_contable`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_movimientos_salarios`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_movimientos_salud`
-- 

DROP TABLE IF EXISTS `job_movimientos_salud`;
CREATE TABLE `job_movimientos_salud` (
  `ano_generacion` int(4) unsigned zerofill NOT NULL COMMENT 'Codigo interno que identifica la planilla',
  `mes_generacion` int(2) unsigned zerofill NOT NULL COMMENT 'Codigo interno que identifica la planilla',
  `codigo_planilla` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo interno que identifica la planilla',
  `periodo_pago` enum('1','2','3','4','5','6','7','8','9') collate latin1_spanish_ci NOT NULL COMMENT '1->Mensual 2->Primera quincena 3->Segunda quincena 4->Primera semana 5->Segunda semana 6->Tercer semana 7->Cuarta semana 8->Quinta semana 9->Fecha unica',
  `codigo_transaccion_contable` int(8) unsigned zerofill NOT NULL COMMENT 'Id de la tabla de transacciones contables',
  `codigo_empresa` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo del almacen donde va a laborar',
  `documento_identidad_empleado` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Documento de identidad que identifica el empleado en terceras personas',
  `fecha_ingreso_empresa` date NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado',
  `codigo_sucursal` mediumint(5) unsigned zerofill NOT NULL COMMENT 'Codigo del almacen donde va a laborar',
  `fecha_ingreso_sucursal` date NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado',
  `fecha_pago_planilla` date NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado',
  `fecha_ingreso_planilla` date NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado',
  `fecha_inicio_pago` date NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado',
  `fecha_hasta_pago` date NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado',
  `codigo_empresa_auxiliar` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa del auxiliar contable',
  `codigo_anexo_contable` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo del anexo que permite dividir las cuentas',
  `codigo_auxiliar_contable` int(8) unsigned zerofill NOT NULL COMMENT 'Codigo donde se acumulara la informacion',
  `codigo_entidad_salud` int(8) unsigned zerofill NOT NULL COMMENT 'Codigo de la entidad parafiscal',
  `codigo_contable` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo contable de la transaccion contable',
  `sentido` enum('D','C') collate latin1_spanish_ci NOT NULL COMMENT 'D->Debito C->Credito',
  `codigo_transaccion_tiempo` int(8) unsigned zerofill NOT NULL COMMENT 'Codigo de la tabla de transacciones de tiempo',
  `dias_trabajados` smallint(3) NOT NULL COMMENT 'Dias laborados en el periodo',
  `salario` decimal(11,2) NOT NULL COMMENT 'Valor mensual del salario',
  `valor_movimiento` decimal(11,2) NOT NULL COMMENT 'Valor diario del registro',
  `ibc_salud` decimal(11,2) NOT NULL COMMENT 'Valor sobre el cual se calcula el aporte',
  `porcentaje_tasa_salud` decimal(7,4) NOT NULL default '0.0000' COMMENT 'Porcentaje de la tasa',
  `contabilizado` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT '0->No 1->Si',
  `codigo_usuario_genera` smallint(4) unsigned zerofill NOT NULL COMMENT 'Codigo usuario que genera el registro',
  PRIMARY KEY  (`ano_generacion`,`mes_generacion`,`codigo_planilla`,`periodo_pago`,`fecha_pago_planilla`,`documento_identidad_empleado`,`codigo_sucursal`),
  KEY `movimiento_salud_sucursal_contrato_empleado` (`codigo_empresa`,`documento_identidad_empleado`,`fecha_ingreso_empresa`,`codigo_sucursal`,`fecha_ingreso_sucursal`),
  KEY `movimiento_salud_codigo_planilla` (`codigo_planilla`),
  KEY `movimiento_salud_transaccion_contable` (`codigo_transaccion_contable`),
  KEY `movimiento_salud_transaccion_tiempo` (`codigo_transaccion_tiempo`),
  KEY `movimiento_salud_anexo` (`codigo_anexo_contable`),
  KEY `movimiento_salud_auxiliar_contable` (`codigo_empresa_auxiliar`,`codigo_anexo_contable`,`codigo_auxiliar_contable`),
  KEY `movimiento_salud_entidades_parafiscales` (`codigo_entidad_salud`),
  KEY `movimiento_salud_usuario` (`codigo_usuario_genera`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_movimientos_salud`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_municipios`
-- 

DROP TABLE IF EXISTS `job_municipios`;
CREATE TABLE `job_municipios` (
  `codigo_iso` varchar(2) collate latin1_spanish_ci NOT NULL COMMENT 'Llave principal de la tabla paises',
  `codigo_dane_departamento` varchar(2) collate latin1_spanish_ci NOT NULL COMMENT 'CÃ³digo DANE',
  `codigo_dane_municipio` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'CÃ³digo DANE',
  `codigo_interno` int(4) unsigned zerofill default NULL COMMENT 'CÃ³digo para uso interno de la empresa (opcional)',
  `nombre` varchar(255) collate latin1_spanish_ci NOT NULL default '' COMMENT 'Nombre completo',
  `capital` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT 'El municipio es la capital del departamento: 0=No, 1=Si',
  `comunas` tinyint(3) NOT NULL default '0' COMMENT 'NÃºmero de comunas en las cuales se divide el municipio',
  PRIMARY KEY  (`codigo_iso`,`codigo_dane_departamento`,`codigo_dane_municipio`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_municipios`
-- 

INSERT INTO `job_municipios` (`codigo_iso`, `codigo_dane_departamento`, `codigo_dane_municipio`, `codigo_interno`, `nombre`, `capital`, `comunas`) VALUES 
('', '', '', 0000, '', '0', 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_pago_liquidaciones_vacaciones`
-- 

DROP TABLE IF EXISTS `job_pago_liquidaciones_vacaciones`;
CREATE TABLE `job_pago_liquidaciones_vacaciones` (
  `codigo_sucursal` mediumint(5) unsigned zerofill NOT NULL COMMENT 'Codigo de la sucursal donde labora el empleado',
  `documento_identidad_empleado` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Documento de identidad empleado en la tabla sucursal contrato empleado',
  `fecha_inicio_tiempo` date NOT NULL COMMENT 'Fecha inicio en que inicia las vacaciones',
  `codigo_empresa` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa donde labora el empleado',
  `fecha_ingreso_sucursal` date NOT NULL COMMENT 'Fecha en la que inicia labores en la sucursal el empleado',
  `fecha_ingreso` date NOT NULL COMMENT 'Fecha en la que inicia labores el empleado',
  `codigo_empresa_auxiliar` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa para la llave de auxiliares',
  `codigo_anexo_contable` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'Código del anexo que permite dividir las cuentas',
  `codigo_auxiliar_contable` int(8) unsigned zerofill NOT NULL COMMENT 'Código donde se acumulara la información',
  `flujo_efectivo` enum('1','2','3') collate latin1_spanish_ci NOT NULL COMMENT '1->No afecta flujo 2->Caja 3->Bancos',
  `codigo_contable` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo de la cuenta contable segun el PUC',
  `sentido` enum('D','C') collate latin1_spanish_ci NOT NULL default 'D' COMMENT 'D->Débito C->Crédito',
  `codigo_sucursal_pertence` mediumint(5) unsigned zerofill NOT NULL COMMENT 'codigo de la sucursal con la que se relaciona la sucursal',
  `tipo_documento_cuenta_bancaria` smallint(3) unsigned zerofill NOT NULL COMMENT 'Código asignado por el usuario',
  `codigo_sucursal_banco` smallint(2) unsigned zerofill NOT NULL COMMENT 'codigo de la tabla bancos',
  `codigo_iso` varchar(2) collate latin1_spanish_ci NOT NULL COMMENT 'Llave principal de la tabla paises',
  `codigo_dane_departamento` varchar(2) collate latin1_spanish_ci NOT NULL COMMENT 'Código DANE',
  `codigo_dane_municipio` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'Código DANE',
  `codigo_banco` smallint(2) unsigned zerofill NOT NULL COMMENT 'id de la tabla bancos',
  `consecutivo_cheque` int(8) unsigned zerofill NOT NULL COMMENT 'Consecutivo interno para la base de datos',
  `numero` varchar(30) collate latin1_spanish_ci NOT NULL COMMENT 'Numero de la cuenta',
  `codigo_tipo_documento` smallint(3) unsigned zerofill NOT NULL COMMENT 'relacionado con el condigo de la tabla tipos documentos',
  `consecutivo_documento` int(9) unsigned zerofill NOT NULL COMMENT 'Consecutivo del documento',
  `codigo_tipo_documento_consecutivo_documento` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo del tipo de documento',
  `codigo_sucursal_consecutivo_documento` mediumint(5) unsigned zerofill NOT NULL COMMENT 'tabla planilla de la tabla de sucursales',
  `fecha_registro_consecutivo_documento` date NOT NULL COMMENT 'Fecha de generacion del consecutivo documento',
  `documento_identidad_tercero_consecutivo_documento` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Número del documento de identidad',
  `valor_movimiento` decimal(11,2) NOT NULL COMMENT 'Valor diario del registro',
  `codigo_usuario_registra` smallint(4) unsigned zerofill NOT NULL COMMENT 'Codigo del Usuario que genera el registro',
  `codigo_usuario_modifica` smallint(4) unsigned zerofill default NULL COMMENT 'Codigo del ultimo Usuario que modifica el registro',
  PRIMARY KEY  (`codigo_sucursal`,`documento_identidad_empleado`,`fecha_inicio_tiempo`),
  KEY `pago_liquidaciones_vacaciones_sucursal_contrato` (`codigo_empresa`,`documento_identidad_empleado`,`fecha_ingreso`,`codigo_sucursal`,`fecha_ingreso_sucursal`),
  KEY `pago_liquidaciones_vacaciones_plan_contable` (`codigo_contable`),
  KEY `pago_liquidaciones_vacaciones_usuarios_registra` (`codigo_usuario_registra`),
  KEY `pago_liquidaciones_vacaciones_usuarios_modifica` (`codigo_usuario_modifica`),
  KEY `pago_liquidaciones_vacaciones_anexo` (`codigo_anexo_contable`),
  KEY `pago_liquidaciones_vacaciones_auxiliar_contable` (`codigo_empresa_auxiliar`,`codigo_anexo_contable`,`codigo_auxiliar_contable`),
  KEY `pago_liquidaciones_vacaciones_cuentas_bancarias` (`codigo_sucursal_pertence`,`tipo_documento_cuenta_bancaria`,`codigo_sucursal_banco`,`codigo_iso`,`codigo_dane_departamento`,`codigo_dane_municipio`,`codigo_banco`,`numero`),
  KEY `pago_liquidaciones_vacaciones_tipo_documento` (`codigo_tipo_documento`),
  KEY `pago_liquidaciones_vacaciones_consecutivo_cheques` (`codigo_sucursal_pertence`,`tipo_documento_cuenta_bancaria`,`codigo_banco`,`numero`,`consecutivo_cheque`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_pago_liquidaciones_vacaciones`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_paises`
-- 

DROP TABLE IF EXISTS `job_paises`;
CREATE TABLE `job_paises` (
  `codigo_iso` varchar(2) collate latin1_spanish_ci NOT NULL COMMENT 'CÃ³digo ISO',
  `codigo_interno` smallint(3) unsigned zerofill default NULL COMMENT 'CÃ³digo para uso interno de la empresa (opcional)',
  `nombre` varchar(255) collate latin1_spanish_ci NOT NULL COMMENT 'Nombre completo',
  PRIMARY KEY  (`codigo_iso`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_paises`
-- 

INSERT INTO `job_paises` (`codigo_iso`, `codigo_interno`, `nombre`) VALUES 
('', 000, '');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_perfiles`
-- 

DROP TABLE IF EXISTS `job_perfiles`;
CREATE TABLE `job_perfiles` (
  `id` smallint(4) unsigned zerofill NOT NULL auto_increment COMMENT 'Consecutivo interno de la base de datos',
  `codigo` smallint(4) unsigned zerofill NOT NULL COMMENT 'CÃ³digo asignado al perfil',
  `nombre` varchar(255) collate latin1_spanish_ci NOT NULL default '' COMMENT 'Nombre del perfil',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `codigo` (`codigo`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=2 ;

-- 
-- Volcar la base de datos para la tabla `job_perfiles`
-- 

INSERT INTO `job_perfiles` (`id`, `codigo`, `nombre`) VALUES 
(0001, 0001, 'GLOBAL');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_perfiles_usuario`
-- 

DROP TABLE IF EXISTS `job_perfiles_usuario`;
CREATE TABLE `job_perfiles_usuario` (
  `id` int(8) unsigned zerofill NOT NULL auto_increment COMMENT 'Consecutivo interno de la base de datos',
  `codigo_usuario` smallint(4) unsigned zerofill NOT NULL COMMENT 'Consecutivo interno de la base de datos para el usuario',
  `codigo_sucursal` mediumint(5) unsigned zerofill NOT NULL COMMENT 'Consecutivo interno de la base de datos para la sucursal',
  `id_perfil` smallint(4) unsigned zerofill NOT NULL COMMENT 'Consecutivo interno de la base de datos para el perfil',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `codigo_usuario` (`codigo_usuario`,`codigo_sucursal`),
  KEY `perfiles_usuario_sucursal` (`codigo_sucursal`),
  KEY `perfiles_usuario_perfil` (`id_perfil`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=2 ;

-- 
-- Volcar la base de datos para la tabla `job_perfiles_usuario`
-- 

INSERT INTO `job_perfiles_usuario` (`id`, `codigo_usuario`, `codigo_sucursal`, `id_perfil`) VALUES 
(00000001, 0000, 00000, 0001);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_periodos_contables`
-- 

DROP TABLE IF EXISTS `job_periodos_contables`;
CREATE TABLE `job_periodos_contables` (
  `codigo_sucursal` mediumint(5) unsigned zerofill NOT NULL COMMENT 'Codigo de la sucursal',
  `id_modulo` char(32) collate latin1_spanish_ci NOT NULL COMMENT 'ID del modulo relacionado',
  `fecha_inicio` date NOT NULL COMMENT 'Fecha inicial del periodo contable',
  `fecha_fin` date NOT NULL COMMENT 'Fecha final del periodo contable',
  `estado` enum('0','1') collate latin1_spanish_ci NOT NULL COMMENT 'Estado del periodo contable 0->Inactivo 1->Activo',
  PRIMARY KEY  (`codigo_sucursal`,`fecha_inicio`,`fecha_fin`,`id_modulo`),
  KEY `periodo_contable_modulo` (`id_modulo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_periodos_contables`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_plan_contable`
-- 

DROP TABLE IF EXISTS `job_plan_contable`;
CREATE TABLE `job_plan_contable` (
  `codigo_contable` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'Código definido por el PUC(Plan unico de cuentas)',
  `descripcion` varchar(255) collate latin1_spanish_ci NOT NULL COMMENT 'Detalle que describe la cuenta contable',
  `codigo_contable_padre` varchar(15) collate latin1_spanish_ci default '' COMMENT 'Cuenta de nivel superior dentro de la estructura',
  `naturaleza_cuenta` enum('D','C') collate latin1_spanish_ci NOT NULL COMMENT 'Naturaleza cuenta: D->Debito C->Credito',
  `clase_cuenta` enum('1','2') collate latin1_spanish_ci NOT NULL COMMENT '1->Cuenta de movimiento la cual no podra ser padre y registra transacciones, 2->Cuenta mayor donde no se pueden registrar transacciones',
  `tipo_cuenta` enum('1','2','3') collate latin1_spanish_ci NOT NULL COMMENT '1->Cuenta de balance 2->Ganancias y perdidas 3->Orden',
  `maneja_tercero` enum('0','1') collate latin1_spanish_ci NOT NULL COMMENT 'La cuenta maneja saldos por tercero 0->No 1->Si',
  `maneja_saldos` enum('0','1') collate latin1_spanish_ci NOT NULL COMMENT 'La cuenta maneja saldos por documentos 0->No 1->Si',
  `maneja_subsistema` enum('0','1') collate latin1_spanish_ci NOT NULL COMMENT 'La cuenta maneja subsistema 0->No 1->Si',
  `codigo_anexo_contable` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'Id de la tabla de anexos contables(ver tabla de subcuentas), es opcional',
  `codigo_tasa_aplicar_1` smallint(3) unsigned zerofill NOT NULL COMMENT 'Para las cuentas de impuestos y gravamenes que requieren un valor base a ser reportado, como el iva, la retencion en la fuente, ica y demas se debe colocar el codigo de tasa ',
  `codigo_tasa_aplicar_2` smallint(3) unsigned zerofill NOT NULL COMMENT 'Para las cuentas de impuestos y gravamenes que requieren un valor base a ser reportado, como el iva, la retencion en la fuente, ica y demas se debe colocar el codigo de tasa ',
  `codigo_concepto_dian` smallint(4) unsigned zerofill NOT NULL COMMENT 'Código del concepto asignado por la DIAN para los informes de medios magneticos ',
  `tipo_certificado` enum('1','2','3','4') collate latin1_spanish_ci NOT NULL COMMENT 'Con este parametro se identifican las cuentas de retenciones  para las cuales se requiere expedir el certificado a terceros, 1->No aplica, 2-> Retencion en la fuente 3-> industria y comercio (ica), 4-> Retencion de iva',
  `causacion_automatica` enum('0','1') collate latin1_spanish_ci NOT NULL COMMENT 'Para la cuenta se realiza un proceso de causacion 0->No 1->Si',
  `flujo_efectivo` enum('1','2','3') collate latin1_spanish_ci NOT NULL COMMENT '1->No afecta flujo 2->Caja 3->Bancos',
  `codigo_contable_consolida` varchar(15) collate latin1_spanish_ci default '' COMMENT 'Cuenta donde consolida saldos',
  `codigo_sucursal` mediumint(5) unsigned zerofill NOT NULL COMMENT 'Código de la sucursal si la cuenta requiere contabilización por una sucursal específica sin importar el origen del movimiento ',
  `codigo_moneda_extranjera` smallint(3) unsigned zerofill NOT NULL COMMENT 'Código de la moneda extranjera en caso de que la cuenta requiera saldos por moneda extranjera',
  `equivalencia` varchar(25) collate latin1_spanish_ci default NULL COMMENT 'Codigo contable un sistema anterior si se migrara la información',
  PRIMARY KEY  (`codigo_contable`),
  KEY `plan_anexo_contable` (`codigo_anexo_contable`),
  KEY `plan_tasa_aplicar_1` (`codigo_tasa_aplicar_1`),
  KEY `plan_tasa_aplicar_2` (`codigo_tasa_aplicar_2`),
  KEY `plan_concepto_dian` (`codigo_concepto_dian`),
  KEY `plan_sucursal` (`codigo_sucursal`),
  KEY `plan_moneda` (`codigo_moneda_extranjera`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_plan_contable`
-- 

INSERT INTO `job_plan_contable` (`codigo_contable`, `descripcion`, `codigo_contable_padre`, `naturaleza_cuenta`, `clase_cuenta`, `tipo_cuenta`, `maneja_tercero`, `maneja_saldos`, `maneja_subsistema`, `codigo_anexo_contable`, `codigo_tasa_aplicar_1`, `codigo_tasa_aplicar_2`, `codigo_concepto_dian`, `tipo_certificado`, `causacion_automatica`, `flujo_efectivo`, `codigo_contable_consolida`, `codigo_sucursal`, `codigo_moneda_extranjera`, `equivalencia`) VALUES 
('', '', '', 'D', '1', '1', '0', '0', '0', '0', 000, 000, 0000, '', '0', '1', '0', 00000, 000, NULL);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_planillas`
-- 

DROP TABLE IF EXISTS `job_planillas`;
CREATE TABLE `job_planillas` (
  `codigo` smallint(3) unsigned zerofill NOT NULL COMMENT 'CÃ³digo interno que identifica la planilla',
  `descripcion` varchar(255) collate latin1_spanish_ci NOT NULL COMMENT 'DescripciÃ³n de la planilla',
  `periodo_pago` enum('1','2','3','4') collate latin1_spanish_ci NOT NULL default '1' COMMENT '1->Mensual 2->Quincenal 3->Semanal 4->Fecha unica',
  PRIMARY KEY  (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_planillas`
-- 

INSERT INTO `job_planillas` (`codigo`, `descripcion`, `periodo_pago`) VALUES 
(000, '', '');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_plazos_pago_proveedores`
-- 

DROP TABLE IF EXISTS `job_plazos_pago_proveedores`;
CREATE TABLE `job_plazos_pago_proveedores` (
  `codigo` smallint(3) unsigned zerofill NOT NULL COMMENT 'Consecutivo interno para la base de datos',
  `nombre` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'Nombre de la forma de pago a credito asignado por el usuario',
  `descripcion` varchar(255) collate latin1_spanish_ci NOT NULL COMMENT 'Descripcion de la forma de pago a credito',
  `periodo` smallint(2) unsigned NOT NULL COMMENT 'Periodicidad de dias para los pagos dentro del intervalo inicial-final',
  `inicial` enum('0','30','60','90','120','150','180','210','240','270') collate latin1_spanish_ci NOT NULL default '0' COMMENT 'Plazo para pago inicial: 0,30,60,90,120,150,180,210,240,270',
  `final` enum('0','30','60','90','120','150','180','210','240','270') collate latin1_spanish_ci NOT NULL default '0' COMMENT 'Plazo para pago final: 0,30,60,90,120,150,180,210,240,270',
  `numero_cuotas` smallint(3) NOT NULL COMMENT 'Numero de cuotas para el plazo definido',
  `orden` smallint(3) unsigned NOT NULL COMMENT 'Orden en el cual salen los datos',
  PRIMARY KEY  (`codigo`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_plazos_pago_proveedores`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_preferencias`
-- 

DROP TABLE IF EXISTS `job_preferencias`;
CREATE TABLE `job_preferencias` (
  `codigo_empresa` smallint(3) unsigned zerofill NOT NULL COMMENT 'Llave de la tabla empresa',
  `codigo_sucursal` mediumint(5) unsigned zerofill NOT NULL COMMENT 'Llave de la tabla sucursal',
  `codigo_usuario` smallint(4) unsigned zerofill NOT NULL COMMENT 'Llave de la tabla usuario',
  `tipo_preferencia` enum('1','2','3','4') collate latin1_spanish_ci NOT NULL default '1' COMMENT '1-Global, 2-Empresa, 3-Sucursal, 4-Usuario',
  `variable` varchar(255) collate latin1_spanish_ci NOT NULL COMMENT 'Nonbre de la preferencia',
  `valor` varchar(255) collate latin1_spanish_ci NOT NULL COMMENT 'Valor de la preferencia',
  PRIMARY KEY  (`codigo_empresa`,`codigo_sucursal`,`codigo_usuario`,`tipo_preferencia`,`variable`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_preferencias`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_profesiones_oficios`
-- 

DROP TABLE IF EXISTS `job_profesiones_oficios`;
CREATE TABLE `job_profesiones_oficios` (
  `codigo_dane` smallint(4) unsigned zerofill NOT NULL COMMENT 'CÃ³digo universal que identifica una profesion u oficio aprobado por el DANE ',
  `descripcion` varchar(255) collate latin1_spanish_ci NOT NULL COMMENT 'Detalle que identifica la profesiÃ³n u oficio',
  PRIMARY KEY  (`codigo_dane`),
  UNIQUE KEY `descripcion` (`descripcion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_profesiones_oficios`
-- 

INSERT INTO `job_profesiones_oficios` (`codigo_dane`, `descripcion`) VALUES 
(0000, '');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_proveedores`
-- 

DROP TABLE IF EXISTS `job_proveedores`;
CREATE TABLE `job_proveedores` (
  `documento_identidad` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Llave principal de la tabla de terceros',
  `fabricante` enum('0','1') collate latin1_spanish_ci NOT NULL COMMENT 'Proveedor fabrica la mercancía 0->No 1->Si',
  `distribuidor` enum('0','1') collate latin1_spanish_ci NOT NULL COMMENT 'Comercializa varias marcas 0->No 1->Si',
  `servicios_tecnicos` enum('0','1') collate latin1_spanish_ci NOT NULL COMMENT 'Proveedor de servicios tecnicos 0->No 1->Si',
  `transporte` enum('0','1') collate latin1_spanish_ci NOT NULL COMMENT 'Proveedor de transporte de mercancia 0->No 1->Si',
  `publicidad` enum('0','1') collate latin1_spanish_ci NOT NULL COMMENT 'Proveedor de publicidad 0->No 1->Si',
  `servicios_especiales` enum('0','1') collate latin1_spanish_ci NOT NULL COMMENT 'Proveedor de servicios especializados 0->No 1->Si. Son los proveedores que prestan servicios que no tienen que ver con el proceso comercial de la empresa pero si con el administrativo',
  `codigo_servicio` smallint(3) unsigned zerofill NOT NULL COMMENT 'Tipo de servicio especializado',
  `fecha_inicio_cobro` enum('1','2') collate latin1_spanish_ci NOT NULL COMMENT 'Fecha en que inicia el cobro del proveedor 1->Fecha de la factura 2->Fecha de recibo de la mercancia',
  `codigo_plazo_pago_contado` smallint(3) unsigned zerofill NOT NULL default '000' COMMENT 'Numero de dias para pago de contado',
  `codigo_plazo_pago_credito` smallint(3) unsigned zerofill NOT NULL default '000' COMMENT 'Numero de dias para pago a credito',
  `tasa_pago_credito` decimal(5,2) unsigned default '0.00' COMMENT 'Tasa de interes que cobra el proveedor para pagos a credito',
  `porcentaje_primera_cuota` decimal(5,2) unsigned default '0.00' COMMENT 'Porcentaje por vencimiento primera cuota en la cuenta por pagar',
  `porcentaje_ultima_cuota` decimal(5,2) unsigned default '0.00' COMMENT 'Porcentaje por vencimiento ultima cuota en la cuenta por pagar',
  `pagos_anticipados` enum('0','1') collate latin1_spanish_ci NOT NULL COMMENT 'Se autoriza pagos anticipados 0->No 1->Si',
  `pagos_efectivo` enum('0','1') collate latin1_spanish_ci NOT NULL COMMENT 'Se autoriza pagos en efectivo 0->No 1->Si',
  `transferencia_electronica` enum('0','1') collate latin1_spanish_ci NOT NULL COMMENT 'Se autoriza transferencias electronicas 0->No 1->Si',
  `tarjeta_credito` enum('0','1') collate latin1_spanish_ci NOT NULL COMMENT 'Se autoriza pagos con tarjeta de credito 0->No 1->Si',
  `triangulacion_bancaria` enum('0','1') collate latin1_spanish_ci NOT NULL COMMENT 'Se autoriza pagos por triangulacion bancaria 0->No 1->Si',
  `tiempo_respuesta` smallint(3) unsigned default '0' COMMENT 'Numero de dias que tarda el proveedor en enviar la mercancia o prestar un servicio una vez generada una orden',
  `porcentaje_flete` decimal(5,2) unsigned default '0.00' COMMENT 'Si el proveedor cobra fletes en la factura se coloca el porcentaje sobre la compra',
  `valor_flete` int(6) unsigned default '0' COMMENT 'Si el proveedor cobra fletes en la factura se coloca un valor fijo',
  `porcentaje_seguro` decimal(5,2) unsigned default '0.00' COMMENT 'Si el proveedor cobra seguro en la factura se coloca el porcentaje sobre la compra',
  `valor_seguro` int(6) unsigned default '0' COMMENT 'Si el proveedor cobra seguro en la factura se coloca un valor fijo',
  `regimen` enum('1','2') collate latin1_spanish_ci default '1' COMMENT '1->Regimen comun 2->Regimen simplificado',
  `retiene_fuente` enum('0','1') collate latin1_spanish_ci default '0' COMMENT 'Realiza retencion en la fuente 0->No 1->Si',
  `autoretenedor` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT 'Autoretenedor 0->No 1->Si',
  `retiene_iva` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT 'Retiene IVA 0->No 1->Si',
  `retiene_ica` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT 'Retiene ICA 0->No 1->Si',
  `gran_contribuyente` enum('0','1') collate latin1_spanish_ci NOT NULL COMMENT 'Empresa esta catalogada como gran contribuyente por la DIAN 0->No 1-Si',
  `autoretenedor_ica` enum('0','1') collate latin1_spanish_ci NOT NULL COMMENT 'Autoretenedor Ica 0->No 1-Si',
  `codigo_iso_principal` varchar(2) collate latin1_spanish_ci NOT NULL COMMENT 'Llave principal de la tabla paises',
  `codigo_dane_departamento_principal` varchar(2) collate latin1_spanish_ci NOT NULL COMMENT 'Código DANE',
  `codigo_dane_municipio_principal` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'Código DANE',
  `codigo_dian_principal` smallint(4) unsigned zerofill NOT NULL COMMENT 'id de la tabla actividades economicas DIAN',
  `codigo_actividad_municipio_principal` int(5) unsigned zerofill default NULL COMMENT 'Codigo de la actividad economica del municipio',
  `codigo_iso_secundario` varchar(2) collate latin1_spanish_ci NOT NULL COMMENT 'Llave principal de la tabla paises',
  `codigo_dane_departamento_secundario` varchar(2) collate latin1_spanish_ci NOT NULL COMMENT 'Código DANE',
  `codigo_dane_municipio_secundario` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'Código DANE',
  `codigo_dian_secundario` smallint(4) unsigned zerofill NOT NULL COMMENT 'id de la tabla actividades economicas DIAN',
  `codigo_actividad_municipio_secundario` int(5) unsigned zerofill default NULL COMMENT 'Codigo de la actividad economica del municipio',
  `forma_iva` enum('1','2','3') collate latin1_spanish_ci NOT NULL default '1' COMMENT 'Forma en que se le paga el IVA: 1->Distribuido 2->Primera cuota 3->Separado',
  `forma_liquidacion_descuento_en_linea` enum('1','2') collate latin1_spanish_ci NOT NULL default '1' COMMENT '1-> liquida el valor neto desde el valor unitario 2-> liquida el valor neto con el valor total',
  `forma_liquidacion_descuento_global` enum('1','2','3') collate latin1_spanish_ci NOT NULL default '1' COMMENT '1-> liquida el valor neto desde el valor unitario 2-> liquida el valor neto con el valor total 3-> realiza el calculo al final de la factura',
  `forma_liquidacion_tasa_credito` enum('1','2') collate latin1_spanish_ci NOT NULL default '1' COMMENT '1-> liquida el valor despues descuentos en linea 2-> liquida el valor despues descuentos globales',
  PRIMARY KEY  (`documento_identidad`),
  KEY `proveedor_servicios` (`codigo_servicio`),
  KEY `proveedor_actividad_principal` (`codigo_iso_principal`,`codigo_dane_departamento_principal`,`codigo_dane_municipio_principal`,`codigo_dian_principal`,`codigo_actividad_municipio_principal`),
  KEY `proveedor_actividad_secundaria` (`codigo_iso_secundario`,`codigo_dane_departamento_secundario`,`codigo_dane_municipio_secundario`,`codigo_dian_secundario`,`codigo_actividad_municipio_secundario`),
  KEY `proveedor_forma_pago_contado` (`codigo_plazo_pago_contado`),
  KEY `proveedor_forma_pago_credito` (`codigo_plazo_pago_credito`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_proveedores`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_proveedores_marcas`
-- 

DROP TABLE IF EXISTS `job_proveedores_marcas`;
CREATE TABLE `job_proveedores_marcas` (
  `documento_identidad_proveedor` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo interno del proveedor',
  `codigo_marca` smallint(4) unsigned zerofill NOT NULL COMMENT 'Codigo interno de la marca',
  PRIMARY KEY  (`documento_identidad_proveedor`,`codigo_marca`),
  KEY `marca` (`codigo_marca`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_proveedores_marcas`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_referencias_aspirante`
-- 

DROP TABLE IF EXISTS `job_referencias_aspirante`;
CREATE TABLE `job_referencias_aspirante` (
  `documento_identidad_aspirante` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo que identifica el tercero',
  `consecutivo` int(8) NOT NULL COMMENT 'Consecutivo interno para la base de datos',
  `nombre` varchar(100) collate latin1_spanish_ci NOT NULL COMMENT 'Nombre de la presona que hace la recomendacion',
  `codigo_dane_profesion` smallint(4) unsigned zerofill NOT NULL COMMENT 'Codigo interno que identifica la profesion de la persona que hace la referrencia',
  `direccion` varchar(50) collate latin1_spanish_ci default NULL COMMENT 'Direccion de la persona quien hace la referencia',
  `telefono` varchar(20) collate latin1_spanish_ci default NULL COMMENT 'Numero de telefono de la persona quien hace la referencia',
  PRIMARY KEY  (`documento_identidad_aspirante`,`consecutivo`),
  KEY `referencias_profesion` (`codigo_dane_profesion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_referencias_aspirante`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_referencias_proveedor`
-- 

DROP TABLE IF EXISTS `job_referencias_proveedor`;
CREATE TABLE `job_referencias_proveedor` (
  `codigo_articulo` varchar(20) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo de la tabla de articulos',
  `referencia` varchar(30) collate latin1_spanish_ci NOT NULL COMMENT 'Referencia ó codigo asignada por el proveedor, puede ser el codigo del articulo',
  `documento_identidad_proveedor` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Documento identidad del proveedor',
  `codigo_barras` bigint(13) NOT NULL COMMENT 'Codigo de barras del articulo(EAN 13)',
  `principal` enum('0','1') collate latin1_spanish_ci NOT NULL COMMENT 'Referencia principal 0->No 1->Si',
  PRIMARY KEY  (`codigo_articulo`,`referencia`,`documento_identidad_proveedor`),
  KEY `referencia_tercero` (`documento_identidad_proveedor`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_referencias_proveedor`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_reporte_incapacidades`
-- 

DROP TABLE IF EXISTS `job_reporte_incapacidades`;
CREATE TABLE `job_reporte_incapacidades` (
  `codigo_empresa` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa donde labora el empleado',
  `documento_identidad_empleado` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Documento de identidad empleado en la tabla sucursal contrato empleado',
  `fecha_ingreso` date NOT NULL COMMENT 'Fecha en la que inicia labores el empleado',
  `codigo_sucursal` mediumint(5) unsigned zerofill NOT NULL COMMENT 'Codigo de la sucursal donde labora el empleado',
  `fecha_ingreso_sucursal` date NOT NULL COMMENT 'Fecha en la que inicia labores en la sucursal el empleado',
  `ano_generacion` int(4) unsigned zerofill NOT NULL COMMENT 'AÃ±o de la generacion la planilla',
  `mes_generacion` int(2) unsigned zerofill NOT NULL COMMENT 'Mes de generacion de la planilla',
  `codigo_planilla` smallint(3) unsigned zerofill NOT NULL COMMENT 'CÃ³digo interno que identifica la planilla',
  `fecha_pago_planilla` date NOT NULL COMMENT 'Fecha rango de pago de la planilla',
  `codigo_empresa_auxiliar` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa para la llave de auxiliares',
  `codigo_anexo_contable` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'CÃ³digo del anexo que permite dividir las cuentas',
  `codigo_auxiliar_contable` int(8) unsigned zerofill NOT NULL COMMENT 'CÃ³digo donde se acumulara la informaciÃ³n',
  `periodo_pago` enum('1','2','3','4','5','6','7','8','9') collate latin1_spanish_ci NOT NULL COMMENT '1->Mensual 2->Primera quincena 3->Segunda quincena 4->Primera semana 5->Segunda semana 6->Tercer semana 7->Cuarta semana 8->Quinta semana 9->Fecha unica',
  `contabilizado` enum('0','1','2') collate latin1_spanish_ci NOT NULL default '1' COMMENT '0->No 1->Si 2->Leido para liquidar salario',
  `fecha_incapacidad` date NOT NULL COMMENT 'Fecha que cubre la incapacidad',
  `fecha_reporte_incapacidad` date NOT NULL COMMENT 'Fecha en que se reporta la incapacidad',
  `fecha_inicial_incapacidad` date NOT NULL COMMENT 'Fecha en que inicia la incapacidad',
  `codigo_transaccion_tiempo` int(8) unsigned zerofill NOT NULL COMMENT 'Id de la tabla de transacciones de tiempo',
  `codigo_transaccion_contable` int(8) unsigned zerofill NOT NULL COMMENT 'Id de la tabla de transacciones contables del Empleado',
  `codigo_contable` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo contable de la transaccion contable',
  `sentido` enum('D','C') collate latin1_spanish_ci NOT NULL COMMENT 'D->DÃ©bito C->CrÃ©dito',
  `dias_incapacidad` smallint(3) NOT NULL COMMENT 'NÃºmero de dÃ­as reportados',
  `valor_dia` decimal(11,2) NOT NULL COMMENT 'Valor diario del registro',
  `dividendo` smallint(2) unsigned NOT NULL default '0' COMMENT 'Valor para liquidar incapacidades',
  `divisor` smallint(2) unsigned NOT NULL default '0' COMMENT 'Valor sobre el que se divide',
  `valor_movimiento` decimal(11,2) NOT NULL COMMENT 'Valor diario del registro',
  `codigo_motivo_incapacidad` smallint(3) unsigned zerofill NOT NULL COMMENT 'Id de la tabla de motivos de incapacidad',
  `codigo_entidad_parafiscal` int(8) unsigned zerofill NOT NULL COMMENT 'Codigo de la entidad parafiscal',
  `numero_incapacidad` varchar(10) collate latin1_spanish_ci NOT NULL COMMENT 'NÃºmero reportado en el documento de autorizaciÃ³n de la EPS',
  `fecha_registro` datetime NOT NULL COMMENT 'Fecha en que se genera el registro',
  `codigo_usuario_registra` smallint(4) unsigned zerofill NOT NULL COMMENT 'Codigo del Usuario que genera el registro',
  `codigo_usuario_modifica` smallint(4) unsigned zerofill default NULL COMMENT 'Codigo del ultimo Usuario que modifica el registro',
  PRIMARY KEY  (`documento_identidad_empleado`,`fecha_incapacidad`),
  KEY `reporte_incapacidad_sucursal_contrato` (`codigo_empresa`,`documento_identidad_empleado`,`fecha_ingreso`,`codigo_sucursal`,`fecha_ingreso_sucursal`),
  KEY `reporte_incapacidades_codigo_planilla` (`codigo_planilla`),
  KEY `reporte_incapacidad_motivo` (`codigo_motivo_incapacidad`),
  KEY `reporte_incapacidad_transaccion_tiempo` (`codigo_transaccion_tiempo`),
  KEY `reporte_incapacidad_transaccion_contable` (`codigo_transaccion_contable`),
  KEY `reporte_incapacidad_plan_contable` (`codigo_contable`),
  KEY `reporte_incapacidad_entidades_parafiscales` (`codigo_entidad_parafiscal`),
  KEY `reporte_incapacidades_usuarios_registra` (`codigo_usuario_registra`),
  KEY `reporte_incapacidades_usuarios_modifica` (`codigo_usuario_modifica`),
  KEY `reporte_incapacidades_anexo` (`codigo_anexo_contable`),
  KEY `reporte_incapacidades_auxiliar_contable` (`codigo_empresa_auxiliar`,`codigo_anexo_contable`,`codigo_auxiliar_contable`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_reporte_incapacidades`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_resoluciones_dian`
-- 

DROP TABLE IF EXISTS `job_resoluciones_dian`;
CREATE TABLE `job_resoluciones_dian` (
  `codigo_sucursal` mediumint(5) unsigned zerofill NOT NULL COMMENT 'Sucursal a la cual se le asigna la resolución',
  `numero` varchar(20) collate latin1_spanish_ci NOT NULL COMMENT 'Numero asignado por la DIAN para la resolucion',
  `prefijo` varchar(8) collate latin1_spanish_ci NOT NULL COMMENT 'Prefijo el cual el cual identifica los almacenes y/o cajas',
  `fecha_inicia` date NOT NULL COMMENT 'Fecha a partir de la cual inicia el funcionamiento de la resolución DIAN',
  `fecha_termina` date NOT NULL COMMENT 'Fecha donde termina el funcionamiento de la resolución DIAN',
  `factura_inicial` int(8) unsigned zerofill NOT NULL COMMENT 'Número con el cual inica la facturación segun la resolución',
  `factura_final` int(8) unsigned zerofill NOT NULL COMMENT 'Número con el cual finaliza la facturación segun la resolución',
  `rango` int(10) NOT NULL COMMENT 'Numero de facturas faltantes para el final de la facturación',
  `codigo_concepto_resolucion_dian` int(8) unsigned zerofill NOT NULL COMMENT 'Codigo de la tabla job_conceptos_resoluciones_dian',
  `codigo_tipo_documento` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la tabla job_tipos_documentos',
  `tipo_resolucion` enum('1','2','3') collate latin1_spanish_ci NOT NULL default '1' COMMENT '1->Autorizada 2->Habiltada 3->En tramite',
  `estado` enum('0','1') collate latin1_spanish_ci NOT NULL default '1' COMMENT '0->Inactiva 1->Activa',
  PRIMARY KEY  (`codigo_sucursal`,`numero`),
  KEY `resolucion_dian_concepto_dian` (`codigo_concepto_resolucion_dian`),
  KEY `resolucion_dian_tipo_documento` (`codigo_tipo_documento`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_resoluciones_dian`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_resoluciones_gran_contribuyente`
-- 

DROP TABLE IF EXISTS `job_resoluciones_gran_contribuyente`;
CREATE TABLE `job_resoluciones_gran_contribuyente` (
  `numero_resolucion` varchar(255) collate latin1_spanish_ci NOT NULL COMMENT 'Numero de la resolución del gran contribuyente',
  `descripcion` varchar(255) collate latin1_spanish_ci default NULL COMMENT 'Descripción para la resolución del gran contribuyente',
  `fecha` date NOT NULL COMMENT 'Fecha de la resolución del gran contribuyente',
  PRIMARY KEY  (`numero_resolucion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_resoluciones_gran_contribuyente`
-- 

INSERT INTO `job_resoluciones_gran_contribuyente` (`numero_resolucion`, `descripcion`, `fecha`) VALUES 
('0', '', '0000-00-00');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_resoluciones_ica`
-- 

DROP TABLE IF EXISTS `job_resoluciones_ica`;
CREATE TABLE `job_resoluciones_ica` (
  `numero_resolucion` varchar(255) collate latin1_spanish_ci NOT NULL COMMENT 'Numero de la resolución',
  `codigo_sucursal` mediumint(5) unsigned zerofill NOT NULL COMMENT 'Código interno de la sucursal',
  `fecha` date NOT NULL COMMENT 'Fecha de la resolución',
  PRIMARY KEY  (`numero_resolucion`,`codigo_sucursal`),
  KEY `resoluciones_ica_sucursal` (`codigo_sucursal`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_resoluciones_ica`
-- 

INSERT INTO `job_resoluciones_ica` (`numero_resolucion`, `codigo_sucursal`, `fecha`) VALUES 
('0', 00000, '0000-00-00');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_resoluciones_retefuente`
-- 

DROP TABLE IF EXISTS `job_resoluciones_retefuente`;
CREATE TABLE `job_resoluciones_retefuente` (
  `numero_retefuente` varchar(255) collate latin1_spanish_ci NOT NULL COMMENT 'Numero de la resolución',
  `descripcion` varchar(255) collate latin1_spanish_ci default NULL COMMENT 'Descripción para la resolución',
  `fecha` date NOT NULL COMMENT 'Fecha de la resolución',
  PRIMARY KEY  (`numero_retefuente`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_resoluciones_retefuente`
-- 

INSERT INTO `job_resoluciones_retefuente` (`numero_retefuente`, `descripcion`, `fecha`) VALUES 
('0', '', '0000-00-00');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_retiro_cesantias`
-- 

DROP TABLE IF EXISTS `job_retiro_cesantias`;
CREATE TABLE `job_retiro_cesantias` (
  `fecha_generacion` datetime NOT NULL COMMENT 'Fecha en la se genero el retiro',
  `fecha_liquidacion` date NOT NULL COMMENT 'Fecha en la hasta donde se va a liquidar',
  `fecha_contabilizacion` date NOT NULL COMMENT 'Fecha en la se generara la contabilizacion',
  `codigo_empresa` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa donde va a laborar',
  `documento_identidad_empleado` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Documento de identidad empleado en la sucursales contrato empleados',
  `fecha_ingreso` date NOT NULL COMMENT 'Fecha en la que ingreso el empleado a la empresa',
  `codigo_sucursal` mediumint(5) unsigned zerofill NOT NULL COMMENT 'Id de la tabla de sucursales',
  `fecha_ingreso_sucursal` date NOT NULL COMMENT 'Fecha en la que inicia labores en la sucursal el empleado',
  `consecutivo` int(8) unsigned zerofill NOT NULL COMMENT 'Consecutivo interno para la base de datos',
  `codigo_tipo_documento` smallint(3) unsigned zerofill NOT NULL COMMENT 'Código asignado por el usuario',
  `consecutivo_documento` int(9) unsigned zerofill NOT NULL COMMENT 'Consecutivo del documento',
  `codigo_transaccion_contable` int(8) unsigned zerofill NOT NULL COMMENT 'codigo de la transaccion contable empleado',
  `codigo_contable` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo contable de la transaccion contable',
  `concepto_retiro` smallint(4) unsigned zerofill NOT NULL COMMENT 'Código que identifica el concepto de prestamo',
  `observaciones` varchar(500) collate latin1_spanish_ci NOT NULL COMMENT 'Descripción del prestamo',
  `sentido` enum('D','C') collate latin1_spanish_ci NOT NULL COMMENT 'D->Débito C->Crédito',
  `valor_retiro` decimal(11,2) NOT NULL COMMENT 'valor total del prestamo',
  `autorizado` enum('0','1') collate latin1_spanish_ci NOT NULL COMMENT '0 -> No 1 -> Si',
  `pagado` enum('0','1') collate latin1_spanish_ci NOT NULL COMMENT '0 -> No 1 -> Si',
  `fecha_ultima_planilla` date NOT NULL COMMENT 'Fecha de la ultima planilla hasta donde se contabilizo el retiro',
  `codigo_usuario_registra` smallint(4) unsigned zerofill NOT NULL COMMENT 'Codigo del Usuario que genera el registro',
  `codigo_usuario_modifica` smallint(4) unsigned zerofill NOT NULL COMMENT 'Codigo del ultimo Usuario que modifica el registro',
  PRIMARY KEY  (`documento_identidad_empleado`,`consecutivo`,`fecha_generacion`,`concepto_retiro`),
  KEY `sucursal_contrato_retiro_cesantias` (`codigo_empresa`,`documento_identidad_empleado`,`fecha_ingreso`,`codigo_sucursal`,`fecha_ingreso_sucursal`),
  KEY `retiro_cesantias_tipo_documento` (`codigo_tipo_documento`),
  KEY `retiro_cesantias_transaccion_contable` (`codigo_transaccion_contable`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_retiro_cesantias`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_salario_minimo`
-- 

DROP TABLE IF EXISTS `job_salario_minimo`;
CREATE TABLE `job_salario_minimo` (
  `codigo` smallint(3) unsigned zerofill NOT NULL COMMENT 'Consecutivo interno identificar el salario minimo',
  `fecha` date NOT NULL COMMENT 'Fecha a partir de la cual empieza a regir el valor del salario minimo',
  `valor` decimal(15,2) NOT NULL COMMENT 'Valor del salario minimo de acuerdo a la fecha',
  PRIMARY KEY  (`codigo`),
  UNIQUE KEY `fecha` (`fecha`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_salario_minimo`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_salario_sucursal_contrato`
-- 

DROP TABLE IF EXISTS `job_salario_sucursal_contrato`;
CREATE TABLE `job_salario_sucursal_contrato` (
  `codigo_empresa` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo del almacen donde va a laborar',
  `documento_identidad_empleado` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Documento de identidad que identifica el empleado en terceras personas',
  `fecha_ingreso` date NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado',
  `codigo_sucursal` mediumint(5) unsigned zerofill NOT NULL COMMENT 'Codigo del almacen donde va a laborar',
  `fecha_ingreso_sucursal` date NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado',
  `fecha_salario` date NOT NULL COMMENT 'Fecha en la que asignan salario',
  `fecha_registro` datetime NOT NULL COMMENT 'Fecha del sistema en la que se asigna el salario',
  `fecha_retroactivo` date NOT NULL COMMENT 'Fecha a partir de la cual se hace retroactivo en salario',
  `salario` decimal(11,2) NOT NULL COMMENT 'Salario que devengara el empleado mensualmente',
  `valor_dia` decimal(11,2) NOT NULL COMMENT 'Valor del dia',
  `valor_hora` decimal(11,2) NOT NULL default '0.00' COMMENT 'Valor de la hora',
  PRIMARY KEY  (`codigo_empresa`,`documento_identidad_empleado`,`fecha_ingreso`,`fecha_ingreso_sucursal`,`fecha_salario`),
  KEY `sucursal_contrato_salario_sucursal_contrato` (`codigo_empresa`,`documento_identidad_empleado`,`fecha_ingreso`,`codigo_sucursal`,`fecha_ingreso_sucursal`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_salario_sucursal_contrato`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_saldos_items_movimientos_contables`
-- 

DROP TABLE IF EXISTS `job_saldos_items_movimientos_contables`;
CREATE TABLE `job_saldos_items_movimientos_contables` (
  `codigo_sucursal` mediumint(5) unsigned zerofill NOT NULL COMMENT 'Codigo de la sucursal donse se genera el movimiento',
  `documento_identidad_tercero` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Número del documento de identidad',
  `codigo_tipo_comprobante` smallint(2) unsigned zerofill NOT NULL COMMENT 'Codigo del tipo de comprobante',
  `numero_comprobante` varchar(20) collate latin1_spanish_ci NOT NULL COMMENT 'Número de comprobante contable',
  `codigo_tipo_documento` smallint(3) unsigned zerofill NOT NULL COMMENT 'Id tabla consecutivo de documentos',
  `consecutivo_documento` int(8) unsigned zerofill NOT NULL COMMENT 'Consecutivo del documento',
  `fecha_contabilizacion` date NOT NULL COMMENT 'Fecha en que se contabiliza la transaccion',
  `consecutivo` int(9) unsigned zerofill NOT NULL COMMENT 'Consecutivo interno para la base de datos',
  `fecha_vencimiento` date NOT NULL COMMENT 'Fecha en la que se produce el vencimiento de la cuota',
  `valor` int(9) unsigned NOT NULL default '0' COMMENT 'Valor de la cuota',
  PRIMARY KEY  (`codigo_sucursal`,`documento_identidad_tercero`,`codigo_tipo_comprobante`,`numero_comprobante`,`codigo_tipo_documento`,`consecutivo_documento`,`fecha_contabilizacion`,`consecutivo`,`fecha_vencimiento`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_saldos_items_movimientos_contables`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_secciones`
-- 

DROP TABLE IF EXISTS `job_secciones`;
CREATE TABLE `job_secciones` (
  `codigo_sucursal` mediumint(5) unsigned zerofill NOT NULL COMMENT 'CÃ³digo interno de la sucursal',
  `codigo_bodega` smallint(4) unsigned zerofill NOT NULL COMMENT 'CÃ³digo interno de la bodega',
  `codigo` smallint(4) unsigned zerofill NOT NULL COMMENT 'CÃ³digo asignado usuario',
  `nombre` varchar(60) collate latin1_spanish_ci NOT NULL COMMENT 'Nombre que identifica la seccion',
  `descripcion` varchar(60) collate latin1_spanish_ci NOT NULL COMMENT 'Nombre que describe la seccion',
  PRIMARY KEY  (`codigo_sucursal`,`codigo_bodega`,`codigo`),
  KEY `secciones_bodegas` (`codigo_bodega`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_secciones`
-- 

INSERT INTO `job_secciones` (`codigo_sucursal`, `codigo_bodega`, `codigo`, `nombre`, `descripcion`) VALUES 
(00000, 0000, 0000, '', '');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_secciones_departamentos`
-- 

DROP TABLE IF EXISTS `job_secciones_departamentos`;
CREATE TABLE `job_secciones_departamentos` (
  `codigo_departamento_empresa` smallint(4) unsigned zerofill NOT NULL COMMENT 'Codigo de la tabla departamentos_empresa',
  `codigo` smallint(4) unsigned zerofill NOT NULL COMMENT 'CÃ³digo interno que identifica la secciÃ³n',
  `nombre` varchar(50) collate latin1_spanish_ci NOT NULL COMMENT 'Nombre la secciÃ³n',
  PRIMARY KEY  (`codigo`,`codigo_departamento_empresa`),
  UNIQUE KEY `codigo` (`codigo`,`codigo_departamento_empresa`,`nombre`),
  KEY `secciones_departamento_empresa` (`codigo_departamento_empresa`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_secciones_departamentos`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_seleccion_items_movimientos_contables`
-- 

DROP TABLE IF EXISTS `job_seleccion_items_movimientos_contables`;
CREATE TABLE `job_seleccion_items_movimientos_contables` (
  `id_movimiento` varbinary(79) default NULL,
  `id` varbinary(91) default NULL,
  `codigo_plan_contable` varchar(15) default NULL,
  `sentido` enum('D','C') default NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Volcar la base de datos para la tabla `job_seleccion_items_movimientos_contables`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_servicios`
-- 

DROP TABLE IF EXISTS `job_servicios`;
CREATE TABLE `job_servicios` (
  `codigo` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo con el cual se identifica el tipo de servicio',
  `descripcion` char(30) collate latin1_spanish_ci NOT NULL COMMENT 'Descricpion que identifica el servicio',
  PRIMARY KEY  (`codigo`),
  UNIQUE KEY `descripcion` (`descripcion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_servicios`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_servidores`
-- 

DROP TABLE IF EXISTS `job_servidores`;
CREATE TABLE `job_servidores` (
  `id` smallint(3) unsigned zerofill NOT NULL auto_increment COMMENT 'Consecutivo interno de la base de datos',
  `ip` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'DrirecciÃ³n IP de la servidor',
  `codigo_sucursal` mediumint(5) unsigned zerofill NOT NULL COMMENT 'Consecutivo interno de la base de datos',
  `nombre_netbios` varchar(50) collate latin1_spanish_ci NOT NULL COMMENT 'Nombre NetBIOS',
  `nombre_tcpip` varchar(50) collate latin1_spanish_ci NOT NULL COMMENT 'NONBRE TCPIP',
  `descripcion` varchar(50) collate latin1_spanish_ci default NULL COMMENT 'DescripciÃ³n de la servidor',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `ip` (`ip`),
  UNIQUE KEY `nombre_netbios` (`nombre_netbios`),
  UNIQUE KEY `nombre_tcpip` (`nombre_tcpip`),
  UNIQUE KEY `descripcion` (`descripcion`),
  KEY `servidor_sucursal` (`codigo_sucursal`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1 ;

-- 
-- Volcar la base de datos para la tabla `job_servidores`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_sesiones`
-- 

DROP TABLE IF EXISTS `job_sesiones`;
CREATE TABLE `job_sesiones` (
  `id` char(32) collate latin1_spanish_ci NOT NULL COMMENT 'Identificador de la sesiÃ³n',
  `expiracion` int(10) unsigned NOT NULL COMMENT 'Fecha de expiraciÃ³n (en formato Unix Timestamp) de la sesiÃ³n por inactividad',
  `contenido` text collate latin1_spanish_ci NOT NULL COMMENT 'Variables definidas en la sesiÃ³n con sus respectivos valores',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_sesiones`
-- 

INSERT INTO `job_sesiones` (`id`, `expiracion`, `contenido`) VALUES 
('56ee820dcb364da3193261ffe50d6726', 1591322486, 'usuario|s:5:"admin";contrasena|s:32:"21232f297a57a5a743894a0e4a801fc3";sucursal|s:4:"null";cliente|s:9:"127.0.0.1";perfil|s:8:"00000001";sucursal_conexion|s:4:"null";codigo_usuario|s:4:"0000";fecha_conexion|s:19:"2020-06-04 20:29:18";menu|s:15823:"<ul id="menuGeneral" class="menu">\n   <li id="MENUPRIN" class="menuPrincipal"><a href="/jobdaily_web/publico/index.php?componente=MENUPRIN">Inicio</a>\n   </li>\n   <li id="MENUINVE" class="menuPrincipal">Inventario\n   <ul class="subMenu">\n    <li id="SUBMOPER">Operaciones diarias\n    </li>\n    <li id="SUBMDCIN">Datos de control\n     <ul>\n    <li id="GESTARTI"><a href="/jobdaily_web/publico/index.php?componente=GESTARTI">Articulos</a>\n    </li>\n    <li id="GESTGRUP"><a href="/jobdaily_web/publico/index.php?componente=GESTGRUP">Grupos</a>\n    </li>\n    <li id="GESTESGR"><a href="/jobdaily_web/publico/index.php?componente=GESTESGR">Estructura de grupos</a>\n    </li>\n    <li id="GESTESME"><a href="/jobdaily_web/publico/index.php?componente=GESTESME">Estado de mercancia</a>\n    </li>\n    <li id="GESTMARC"><a href="/jobdaily_web/publico/index.php?componente=GESTMARC">Marcas</a>\n    </li>\n    <li id="GESTTIUN"><a href="/jobdaily_web/publico/index.php?componente=GESTTIUN">Tipo de Unidad de medida</a>\n    </li>\n    <li id="GESTUNID"><a href="/jobdaily_web/publico/index.php?componente=GESTUNID">Unidades de medida</a>\n    </li>\n     </ul>\n    </li>\n   </ul>\n   </li>\n   <li id="MENUPROV" class="menuPrincipal">Proveedores\n   </li>\n   <li id="MENUCLIE" class="menuPrincipal">Clientes\n   </li>\n   <li id="MENUNOMI" class="menuPrincipal">Recursos Humanos\n   <ul class="subMenu">\n    <li id="SUBMNOMI">Nomina\n     <ul>\n    <li id="GESTINEM"><a href="/jobdaily_web/publico/index.php?componente=GESTINEM">Ingreso de empleados</a>\n    </li>\n    <li id="GESTASTU"><a href="/jobdaily_web/publico/index.php?componente=GESTASTU">Asignacion turnos</a>\n    </li>\n    <li id="GESTREIN"><a href="/jobdaily_web/publico/index.php?componente=GESTREIN">Reporte de incapacidades</a>\n    </li>\n    <li id="SUBMRETL">Tiempos no laborados\n     <ul>\n    <li id="GESTRENL"><a href="/jobdaily_web/publico/index.php?componente=GESTRENL">Reporte de tiempos no laborados por dias</a>\n    </li>\n    <li id="GESTRETH"><a href="/jobdaily_web/publico/index.php?componente=GESTRETH">Reporte de tiempos no laborados por horas</a>\n    </li>\n     </ul>\n    </li>\n    <li id="GESTEXRE"><a href="/jobdaily_web/publico/index.php?componente=GESTEXRE">Normales, Extras y Recargos</a>\n    </li>\n    <li id="GESTPTEM"><a href="/jobdaily_web/publico/index.php?componente=GESTPTEM">Prestamos empleados</a>\n    </li>\n    <li id="GESTPTTE"><a href="/jobdaily_web/publico/index.php?componente=GESTPTTE">Prestamos terceros</a>\n    </li>\n    <li id="SUBMPRSO">Prestaciones sociales\n     <ul>\n    <li id="GESTCAPS"><a href="/jobdaily_web/publico/index.php?componente=GESTCAPS">Causacion de prestaciones sociales</a>\n    </li>\n    <li id="GESTRECE"><a href="/jobdaily_web/publico/index.php?componente=GESTRECE">Retiro de cesantias</a>\n    </li>\n    <li id="GESTVATO"><a href="/jobdaily_web/publico/index.php?componente=GESTVATO">Liquidación de vacaciones</a>\n    </li>\n     </ul>\n    </li>\n    <li id="GESTLIEM"><a href="/jobdaily_web/publico/index.php?componente=GESTLIEM">Liquidar Empleado</a>\n    </li>\n     </ul>\n    </li>\n    <li id="SUBMPRPL">Procesos planilla\n     <ul>\n    <li id="GESTLQSL"><a id="GESTLQSL" href="#">Liquidacion Salario</a>\n    </li>\n    <li id="GESTNOMA"><a href="/jobdaily_web/publico/index.php?componente=GESTNOMA">Novedades manuales</a>\n    </li>\n    <li id="GESTLSRE"><a id="GESTLSRE" href="#">Salario retroactivo</a>\n    </li>\n    <li id="GESTLQSP"><a id="GESTLQSP" href="#">Liquidar Salud y Pension</a>\n    </li>\n    <li id="GESTLQPR"><a id="GESTLQPR" href="#">Liquidar prima</a>\n    </li>\n    <li id="SUBMPAGO">Forma de pago\n     <ul>\n    <li id="GESTGENE"><a id="GESTGENE" href="#">Nomina por pagar</a>\n    </li>\n    <li id="GESTCHSU"><a id="GESTCHSU" href="#">Cheque por planilla</a>\n    </li>\n    <li id="GESTCHEM"><a id="GESTCHEM" href="#">Cheque por empleado</a>\n    </li>\n    <li id="GESTPAEF"><a id="GESTPAEF" href="#">Pago en Efectivo</a>\n    </li>\n     </ul>\n    </li>\n    <li id="GESTDCPL"><a id="GESTDCPL" href="#">Descontabilizar planilla</a>\n    </li>\n    <li id="GESTELMO"><a id="GESTELMO" href="#">Eliminar Movimientos</a>\n    </li>\n    <li id="PAGAPLAN"><a id="PAGAPLAN" href="#">Pagar planilla</a>\n    </li>\n    <li id="LISTAPLA"><a id="LISTAPLA" href="#">Lista planilla</a>\n    </li>\n    <li id="LISTPLEM"><a id="LISTPLEM" href="#">Lista planilla por empleados</a>\n    </li>\n    <li id="LISTCOPA"><a id="LISTCOPA" href="#">Comprobante de pago</a>\n    </li>\n    <li id="LISTCPPR"><a id="LISTCPPR" href="#">Comprobante pago prima</a>\n    </li>\n     </ul>\n    </li>\n    <li id="SUBMRENO">Listados\n     <ul>\n    <li id="LISTPGPR"><a id="LISTPGPR" href="#">Pagos de prestamos por empleado</a>\n    </li>\n    <li id="LISTMOPR"><a id="LISTMOPR" href="#">Lista movimientos para prima</a>\n    </li>\n     </ul>\n    </li>\n    <li id="SUBMPENO">Proceso especiales\n     <ul>\n    <li id="GESTNMIG"><a href="/jobdaily_web/publico/index.php?componente=GESTNMIG">Movimientos migración nomina</a>\n    </li>\n     </ul>\n    </li>\n    <li id="SUBMPRAN">Proceso anuales\n     <ul>\n    <li id="GESTDOFE"><a href="/jobdaily_web/publico/index.php?componente=GESTDOFE">Domingos y Festivos</a>\n    </li>\n    <li id="GESTAXTP"><a href="/jobdaily_web/publico/index.php?componente=GESTAXTP">Auxilio transporte</a>\n    </li>\n    <li id="GESTSMLV"><a href="/jobdaily_web/publico/index.php?componente=GESTSMLV">Salario Minimo</a>\n    </li>\n     </ul>\n    </li>\n    <li id="SUBMDCRH">Datos de control\n     <ul>\n    <li id="GESTASPI"><a href="/jobdaily_web/publico/index.php?componente=GESTASPI">Aspirantes</a>\n    </li>\n    <li id="GESTTRCO"><a href="/jobdaily_web/publico/index.php?componente=GESTTRCO">Transacciones contables</a>\n    </li>\n    <li id="GESTCGPS"><a href="/jobdaily_web/publico/index.php?componente=GESTCGPS">Cuentas gastos prestaciones sociales</a>\n    </li>\n    <li id="GESTTRTI"><a href="/jobdaily_web/publico/index.php?componente=GESTTRTI">Transacciones de tiempo</a>\n    </li>\n    <li id="GESTCOPR"><a href="/jobdaily_web/publico/index.php?componente=GESTCOPR">Conceptos prestamos</a>\n    </li>\n    <li id="GESTPLAN"><a href="/jobdaily_web/publico/index.php?componente=GESTPLAN">Planillas</a>\n    </li>\n    <li id="GESTTLPL"><a id="GESTTLPL" href="#">Titulos de planillas</a>\n    </li>\n    <li id="GESTFEPL"><a href="/jobdaily_web/publico/index.php?componente=GESTFEPL">Fecha planillas</a>\n    </li>\n    <li id="GESTDEEM"><a href="/jobdaily_web/publico/index.php?componente=GESTDEEM">Departamentos empresa</a>\n    </li>\n    <li id="GESTSCDE"><a href="/jobdaily_web/publico/index.php?componente=GESTSCDE">Secciones</a>\n    </li>\n    <li id="GESTTICT"><a href="/jobdaily_web/publico/index.php?componente=GESTTICT">Tipos de contrato</a>\n    </li>\n    <li id="GESTENPF"><a href="/jobdaily_web/publico/index.php?componente=GESTENPF">Entidades parafiscales</a>\n    </li>\n    <li id="GESTMORE"><a href="/jobdaily_web/publico/index.php?componente=GESTMORE">Motivos de Retiro</a>\n    </li>\n    <li id="GESTMOIN"><a href="/jobdaily_web/publico/index.php?componente=GESTMOIN">Motivos de incapacidad</a>\n    </li>\n    <li id="GESTNOLA"><a href="/jobdaily_web/publico/index.php?componente=GESTNOLA">Motivos tiempos no laborados</a>\n    </li>\n    <li id="GESTIDIO"><a href="/jobdaily_web/publico/index.php?componente=GESTIDIO">Idiomas</a>\n    </li>\n    <li id="GESTESCO"><a href="/jobdaily_web/publico/index.php?componente=GESTESCO">Escolaridades</a>\n    </li>\n    <li id="GESTDEPO"><a href="/jobdaily_web/publico/index.php?componente=GESTDEPO">Deportes</a>\n    </li>\n    <li id="GESTAFIC"><a href="/jobdaily_web/publico/index.php?componente=GESTAFIC">Aficiones</a>\n    </li>\n     </ul>\n    </li>\n   </ul>\n   </li>\n   <li id="MENUCONT" class="menuPrincipal">Contabilidad\n   <ul class="subMenu">\n    <li id="SUBMOPCO">Operaciones contables\n     <ul>\n    <li id="GESTMOCO"><a href="/jobdaily_web/publico/index.php?componente=GESTMOCO">Movimientos contables</a>\n    </li>\n     </ul>\n    </li>\n    <li id="SUBMBALC">Balances\n     <ul>\n    <li id="REPOBALC"><a id="REPOBALC" href="#">Balance general</a>\n    </li>\n    <li id="REPOBPYG"><a id="REPOBPYG" href="#">Balance de perdidas y ganancias</a>\n    </li>\n    <li id="REPOBACO"><a id="REPOBACO" href="#">Balance de comprobación</a>\n    </li>\n    <li id="REPOESCU"><a id="REPOESCU" href="#">Estado de cuenta</a>\n    </li>\n     </ul>\n    </li>\n    <li id="SUBMSADO">Saldos\n     <ul>\n    <li id="REPOSCXC"><a id="REPOSCXC" href="#">Saldos cuentas por cobrar</a>\n    </li>\n    <li id="REPOSCXP"><a id="REPOSCXP" href="#">Saldos cuentas por pagar</a>\n    </li>\n    <li id="REPOSMDO"><a id="REPOSMDO" href="#">Saldos de movimientos por documento</a>\n    </li>\n     </ul>\n    </li>\n    <li id="SUBMMOVI">Movimientos\n     <ul>\n    <li id="REPODIMD"><a id="REPODIMD" href="#">Diario de movimientos por documento</a>\n    </li>\n    <li id="REPODIMC"><a id="REPODIMC" href="#">Diario de movimientos por comprobante</a>\n    </li>\n     </ul>\n    </li>\n    <li id="SUBMDCCO">Datos de control\n     <ul>\n    <li id="SUBMINCO">Información contable general\n     <ul>\n    <li id="GESTTERC"><a href="/jobdaily_web/publico/index.php?componente=GESTTERC">Terceros</a>\n    </li>\n    <li id="GESTPECO"><a href="/jobdaily_web/publico/index.php?componente=GESTPECO">Periodos contables</a>\n    </li>\n    <li id="GESTPLCO"><a href="/jobdaily_web/publico/index.php?componente=GESTPLCO">Plan contable</a>\n    </li>\n    <li id="GESTANCO"><a href="/jobdaily_web/publico/index.php?componente=GESTANCO">Anexos contables</a>\n    </li>\n    <li id="GESTAUCO"><a href="/jobdaily_web/publico/index.php?componente=GESTAUCO">Auxiliares contables</a>\n    </li>\n    <li id="GESTTICO"><a href="/jobdaily_web/publico/index.php?componente=GESTTICO">Tipos de comprobantes</a>\n    </li>\n    <li id="GESTTIDO"><a href="/jobdaily_web/publico/index.php?componente=GESTTIDO">Tipos de documentos</a>\n    </li>\n    <li id="GESTTIDB"><a href="/jobdaily_web/publico/index.php?componente=GESTTIDB">Tipos de documentos bancarios</a>\n    </li>\n     </ul>\n    </li>\n    <li id="SUBMICPR">Información contable proveedores\n     <ul>\n    <li id="GESTTCOM"><a href="/jobdaily_web/publico/index.php?componente=GESTTCOM">Tipos de compras</a>\n    </li>\n    <li id="GESTCOCO"><a href="/jobdaily_web/publico/index.php?componente=GESTCOCO">Conceptos de contabilización compras</a>\n    </li>\n    <li id="GESTTDVC"><a href="/jobdaily_web/publico/index.php?componente=GESTTDVC">Tipos devoluciones de compras</a>\n    </li>\n    <li id="GESTCDCO"><a href="/jobdaily_web/publico/index.php?componente=GESTCDCO">Conceptos de contabilización devoluciones compras</a>\n    </li>\n     </ul>\n    </li>\n    <li id="SUBMINCL">Información contable clientes\n    </li>\n    <li id="SUBMINTR">Información tributaria\n     <ul>\n    <li id="GESTREDI"><a href="/jobdaily_web/publico/index.php?componente=GESTREDI">Resoluciones de la DIAN</a>\n    </li>\n    <li id="GESTREGC"><a href="/jobdaily_web/publico/index.php?componente=GESTREGC">Resoluciones gran contribuyente</a>\n    </li>\n    <li id="GESTREIC"><a href="/jobdaily_web/publico/index.php?componente=GESTREIC">Resoluciones ICA</a>\n    </li>\n    <li id="GESTRERF"><a href="/jobdaily_web/publico/index.php?componente=GESTRERF">Resoluciones reteción en la fuente</a>\n    </li>\n    <li id="GESTFODI"><a href="/jobdaily_web/publico/index.php?componente=GESTFODI">Formatos de la DIAN</a>\n    </li>\n    <li id="GESTCODI"><a href="/jobdaily_web/publico/index.php?componente=GESTCODI">Conceptos de la DIAN</a>\n    </li>\n     </ul>\n    </li>\n    <li id="SUBMFINA">Financiera\n     <ul>\n    <li id="GESTACDI"><a href="/jobdaily_web/publico/index.php?componente=GESTACDI">Actividad economica DIAN</a>\n    </li>\n    <li id="GESTAECO"><a href="/jobdaily_web/publico/index.php?componente=GESTAECO">Actividades Economicas Por Municipio</a>\n    </li>\n    <li id="GESTBANC"><a href="/jobdaily_web/publico/index.php?componente=GESTBANC">Bancos</a>\n    </li>\n    <li id="GESTCUBA"><a href="/jobdaily_web/publico/index.php?componente=GESTCUBA">Cuentas bancarias</a>\n    </li>\n    <li id="GESTTIMO"><a href="/jobdaily_web/publico/index.php?componente=GESTTIMO">Tipos de moneda</a>\n    </li>\n     </ul>\n    </li>\n    <li id="SUBMTASA">Mantenimiento Tasas\n     <ul>\n    <li id="GESTTASA"><a href="/jobdaily_web/publico/index.php?componente=GESTTASA">Tasas</a>\n    </li>\n    <li id="GESTVITA"><a href="/jobdaily_web/publico/index.php?componente=GESTVITA">Vigencia de tasas</a>\n    </li>\n     </ul>\n    </li>\n     </ul>\n    </li>\n   </ul>\n   </li>\n   <li id="MENUADMI" class="menuPrincipal">Administración\n   <ul class="subMenu">\n    <li id="SUBMESTC">Estructura corporativa\n     <ul>\n    <li id="GESTEMPR"><a href="/jobdaily_web/publico/index.php?componente=GESTEMPR">Empresas</a>\n    </li>\n    <li id="GESTSUCU"><a href="/jobdaily_web/publico/index.php?componente=GESTSUCU">Almacenes</a>\n    </li>\n    <li id="GESTBODE"><a href="/jobdaily_web/publico/index.php?componente=GESTBODE">Bodegas</a>\n    </li>\n    <li id="GESTSECB"><a href="/jobdaily_web/publico/index.php?componente=GESTSECB">Secciones</a>\n    </li>\n     </ul>\n    </li>\n    <li id="SUBMACCE">Acceso\n     <ul>\n    <li id="GESTPERF"><a href="/jobdaily_web/publico/index.php?componente=GESTPERF">Perfiles</a>\n    </li>\n    <li id="GESTPREF">Preferencias\n     <ul>\n    <li id="PREFGLOB"><a id="PREFGLOB" href="#">Globales</a>\n    </li>\n    <li id="PREFEMPR"><a href="/jobdaily_web/publico/index.php?componente=PREFEMPR">Empresas</a>\n    </li>\n    <li id="PREFSUCU"><a href="/jobdaily_web/publico/index.php?componente=PREFSUCU">Almacén</a>\n    </li>\n    <li id="PREFUSUA"><a href="/jobdaily_web/publico/index.php?componente=PREFUSUA">Usuario</a>\n    </li>\n     </ul>\n    </li>\n    <li id="GESTUSUA"><a href="/jobdaily_web/publico/index.php?componente=GESTUSUA">Usuarios</a>\n    </li>\n    <li id="GESTPRIV"><a href="/jobdaily_web/publico/index.php?componente=GESTPRIV">Privilegios</a>\n    </li>\n     </ul>\n    </li>\n    <li id="SUBMDISP">Dispositivos\n     <ul>\n    <li id="GESTSRVD"><a href="/jobdaily_web/publico/index.php?componente=GESTSRVD">Servidores</a>\n    </li>\n    <li id="GESTTERM"><a href="/jobdaily_web/publico/index.php?componente=GESTTERM">Terminales</a>\n    </li>\n     </ul>\n    </li>\n    <li id="SUBMSEGU">Seguridad\n     <ul>\n    <li id="GESTBITA"><a href="/jobdaily_web/publico/index.php?componente=GESTBITA">Registro de conexiones</a>\n    </li>\n     </ul>\n    </li>\n    <li id="SUBMDCAD">Datos de control\n     <ul>\n    <li id="GESTPROF"><a href="/jobdaily_web/publico/index.php?componente=GESTPROF">Profesiones u oficios</a>\n    </li>\n    <li id="GESTTIBO"><a href="/jobdaily_web/publico/index.php?componente=GESTTIBO">Tipos de bodegas</a>\n    </li>\n    <li id="GESTTIDI"><a href="/jobdaily_web/publico/index.php?componente=GESTTIDI">Tipos de documentos de identidad</a>\n    </li>\n    <li id="GESTCARG"><a href="/jobdaily_web/publico/index.php?componente=GESTCARG">Cargos</a>\n    </li>\n    <li id="GESTTABL"><a href="/jobdaily_web/publico/index.php?componente=GESTTABL">Tablas</a>\n    </li>\n    <li id="SUBMUBIG">Ubicación geográfica\n     <ul>\n    <li id="GESTPAIS"><a href="/jobdaily_web/publico/index.php?componente=GESTPAIS">Paises</a>\n    </li>\n    <li id="GESTDEPA"><a href="/jobdaily_web/publico/index.php?componente=GESTDEPA">Departamentos</a>\n    </li>\n    <li id="GESTMUNI"><a href="/jobdaily_web/publico/index.php?componente=GESTMUNI">Municipios</a>\n    </li>\n    <li id="GESTCORR"><a href="/jobdaily_web/publico/index.php?componente=GESTCORR">Corregimientos</a>\n    </li>\n    <li id="GESTBARR"><a href="/jobdaily_web/publico/index.php?componente=GESTBARR">Barrios</a>\n    </li>\n     </ul>\n    </li>\n     </ul>\n    </li>\n   </ul>\n   </li>\n   <li id="MENUFINS" class="menuPrincipal"><a href="/jobdaily_web/publico/index.php?componente=MENUFINS">Finalizar sesión</a>\n   </li>\n</ul>\n";');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_sucursal_contrato_empleados`
-- 

DROP TABLE IF EXISTS `job_sucursal_contrato_empleados`;
CREATE TABLE `job_sucursal_contrato_empleados` (
  `codigo_empresa` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo del almacen donde va a laborar',
  `documento_identidad_empleado` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Documento de identidad que identifica el empleado en terceras personas',
  `fecha_ingreso` date NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado',
  `codigo_sucursal` mediumint(5) unsigned zerofill NOT NULL COMMENT 'Codigo del almacen donde va a laborar',
  `fecha_ingreso_sucursal` date NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado',
  `codigo_empresa_auxiliar` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la empresa del axuliar contable',
  `codigo_anexo_contable` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'CÃ³digo del anexo que permite dividir las cuentas',
  `codigo_auxiliar` int(8) unsigned zerofill NOT NULL COMMENT 'CÃ³digo donde se acumulara la informaciÃ³n',
  `codigo_planilla` smallint(3) unsigned zerofill NOT NULL COMMENT 'CÃ³digo de la planilla',
  `salario_mensual` decimal(11,2) default NULL COMMENT 'Salario que devengara el empleado mensualmente',
  `valor_hora` decimal(11,2) default NULL COMMENT 'Valor de la hora que devengara el empleado',
  `dias_mes` smallint(3) NOT NULL COMMENT 'Numero de dias que trabajara en el mes',
  `horas_mes` smallint(3) NOT NULL COMMENT 'Numero de horas que trabajara en el mes',
  `codigo_turno_laboral` smallint(4) unsigned zerofill NOT NULL COMMENT 'Id de la tabla de turnos laborales',
  `codigo_motivo_retiro` smallint(4) unsigned zerofill NOT NULL COMMENT 'Id de la tabla de motivos de retiro',
  `fecha_retiro` date default NULL COMMENT 'Fecha en la cual es retiraddo de la empresa',
  `codigo_transaccion_salario` int(8) unsigned zerofill NOT NULL COMMENT 'Id de la transaccion contable que genera horas normales',
  `codigo_transaccion_auxilio_transporte` int(8) unsigned zerofill NOT NULL COMMENT 'Id de la transaccion contable que genera horas normales',
  `forma_pago_auxilio` enum('1','2') collate latin1_spanish_ci default '1' COMMENT 'Solo para las planillas quincenales 1-> Proporcional 2->Segunda quincena',
  `codigo_transaccion_salud` int(8) unsigned zerofill NOT NULL COMMENT 'Id de la transaccion contable que genera horas normales',
  `forma_descuento_salud` enum('1','2') collate latin1_spanish_ci default '1' COMMENT 'Solo para las planillas quincenales 1-> Proporcional 2->Segunda quincena',
  `codigo_transaccion_pension` int(8) unsigned zerofill NOT NULL COMMENT 'Id de la transaccion contable que genera horas normales',
  `forma_descuento_pension` enum('1','2') collate latin1_spanish_ci default '1' COMMENT 'Solo para las planillas quincenales 1-> Proporcional 2->Segunda quincena',
  `codigo_transaccion_normales` int(8) unsigned zerofill NOT NULL COMMENT 'codigo de la transaccion tiempo que genera horas normales',
  `codigo_transaccion_extras` int(8) unsigned zerofill NOT NULL COMMENT 'codigo de la transaccion tiempo que genera horas extras',
  `codigo_transaccion_recargo_nocturno` int(8) unsigned zerofill NOT NULL COMMENT 'codigo de la transaccion tiempo que genera horas recargo nocturno',
  `codigo_transaccion_extras_nocturnas` int(8) unsigned zerofill NOT NULL COMMENT 'codigo de la transaccion tiempo que genera horas extras nocturnas',
  `codigo_transaccion_dominicales` int(8) unsigned zerofill NOT NULL COMMENT 'codigo de la transaccion tiempo que genera horas dominicales y festivos',
  `codigo_transaccion_extras_dominicales` int(8) unsigned zerofill NOT NULL COMMENT 'codigo de la transaccion tiempo que genera horas extras dominicales',
  `codigo_transaccion_recargo_noche_dominicales` int(8) unsigned zerofill NOT NULL COMMENT 'codigo de la transaccion tiempo que genera recargo nocturno dominicales y festivos',
  `codigo_transaccion_extras_noche_dominicales` int(8) unsigned zerofill NOT NULL COMMENT 'codigo de la transaccion tiempo que genera horas extra dominicales nocturnas',
  PRIMARY KEY  (`codigo_empresa`,`documento_identidad_empleado`,`fecha_ingreso`,`codigo_sucursal`,`fecha_ingreso_sucursal`),
  KEY `sucursal_codigo_transaccion_normales` (`codigo_transaccion_normales`),
  KEY `sucursal_codigo_transaccion_extras` (`codigo_transaccion_extras`),
  KEY `sucursal_codigo_transaccion_recargo_nocturno` (`codigo_transaccion_recargo_nocturno`),
  KEY `sucursal_codigo_transaccion_extras_nocturnas` (`codigo_transaccion_extras_nocturnas`),
  KEY `sucursal_codigo_transaccion_dominicales` (`codigo_transaccion_dominicales`),
  KEY `sucursal_codigo_transaccion_extras_dominicales` (`codigo_transaccion_extras_dominicales`),
  KEY `sucursal_codigo_transaccion_recargo_noche_dominicales` (`codigo_transaccion_recargo_noche_dominicales`),
  KEY `sucursal_codigo_transaccion_extras_noche_dominicales` (`codigo_transaccion_extras_noche_dominicales`),
  KEY `sucursal_contrato_anexo_contable_empleado` (`codigo_anexo_contable`),
  KEY `sucursal_contrato_auxiliar_contable_empleado` (`codigo_empresa_auxiliar`,`codigo_anexo_contable`,`codigo_auxiliar`),
  KEY `sucursal_contrato_planilla_empleado` (`codigo_planilla`),
  KEY `sucursal_contrato_turno_empleado` (`codigo_turno_laboral`),
  KEY `sucursal_contrato_motivo_retiro_empleado` (`codigo_motivo_retiro`),
  KEY `sucursal_contrato_transaccion_salario` (`codigo_transaccion_salario`),
  KEY `sucursal_contrato_transaccion_auxilio` (`codigo_transaccion_auxilio_transporte`),
  KEY `sucursal_contrato_transaccion_salud` (`codigo_transaccion_salud`),
  KEY `sucursal_contrato_transaccion_pension` (`codigo_transaccion_pension`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_sucursal_contrato_empleados`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_sucursales`
-- 

DROP TABLE IF EXISTS `job_sucursales`;
CREATE TABLE `job_sucursales` (
  `codigo` mediumint(5) unsigned zerofill NOT NULL COMMENT 'CÃ³digo interno de la sucursal',
  `codigo_empresa` smallint(3) unsigned zerofill NOT NULL COMMENT 'CÃ³digo interno de la empresa con la que se relaciona el almacen',
  `nombre` varchar(60) collate latin1_spanish_ci NOT NULL COMMENT 'Nombre que identifica el almacen',
  `nombre_corto` char(10) collate latin1_spanish_ci NOT NULL COMMENT 'Nombre que identifica el almacen en consultas',
  `fecha_cierre` date default NULL COMMENT 'Fecha que estuvo activo el almacen',
  `activo` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT 'Indicador de estado del almacen: 0=Inactiva, 1=Activa',
  `codigo_iso` varchar(2) collate latin1_spanish_ci NOT NULL COMMENT 'Llave principal de la tabla paises',
  `codigo_dane_departamento` varchar(2) collate latin1_spanish_ci NOT NULL COMMENT 'CÃ³digo DANE',
  `codigo_dane_municipio` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'CÃ³digo DANE',
  `direccion_residencia` varchar(60) collate latin1_spanish_ci NOT NULL COMMENT 'Direccion donde se encuentra la persona o empresa',
  `telefono_1` varchar(15) collate latin1_spanish_ci default NULL COMMENT 'Primer numero de telefono del lugar de residencia',
  `telefono_2` varchar(15) collate latin1_spanish_ci default NULL COMMENT 'Segundo numero de telefono del lugar de residencia',
  `celular` varchar(15) collate latin1_spanish_ci default NULL COMMENT 'Numero de telefono celular',
  `codigo_empresa_consolida` smallint(3) unsigned zerofill default NULL COMMENT 'Codigo interno empresa que consolida',
  `codigo_sucursal_consolida` mediumint(5) unsigned zerofill default NULL COMMENT 'Codigo interno sucursal que consolida',
  `tipo_empresa` enum('1','2','3','4') collate latin1_spanish_ci NOT NULL default '1' COMMENT 'Indicador de tipo empresa: 1=Distribuidoras mayoristas, 2=Ventas publico, 3=Ambas, 4=Empresa soporte',
  `orden` mediumint(5) unsigned zerofill NOT NULL COMMENT 'Orden sucursales en listados',
  `maneja_kardex` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT 'Indicador como se maneja el kardex: 0=No, 1=Si',
  `realiza_orden_compra` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT 'Sucursal autorizada para realizar ordenes de compras',
  `inventarios_mercancia` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT 'Permite el manejo de inventarios: 0=No, 1=Si',
  `cartera_clientes_mayoristas` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT 'Permite el manejo de cartera de clientes mayoristas: 0=No, 1=Si',
  `cartera_clientes_detallistas` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT 'Permite el manejo de cartera de clientes minoristas: 0=No, 1=Si',
  `cuentas_pagar_proveedores` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT 'Permite el manejo de cuentas por pagar proveedores: 0=No, 1=Si',
  `nomina` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT 'Permite el manejo de  nÃ³mina: 0=No, 1=Si',
  `contabilidad` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT 'permite el manejo contable: 0=No, 1=Si',
  PRIMARY KEY  (`codigo`),
  UNIQUE KEY `codigo_empresa` (`codigo_empresa`,`nombre`),
  KEY `sucursal_id_municipio` (`codigo_iso`,`codigo_dane_departamento`,`codigo_dane_municipio`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_sucursales`
-- 

INSERT INTO `job_sucursales` (`codigo`, `codigo_empresa`, `nombre`, `nombre_corto`, `fecha_cierre`, `activo`, `codigo_iso`, `codigo_dane_departamento`, `codigo_dane_municipio`, `direccion_residencia`, `telefono_1`, `telefono_2`, `celular`, `codigo_empresa_consolida`, `codigo_sucursal_consolida`, `tipo_empresa`, `orden`, `maneja_kardex`, `realiza_orden_compra`, `inventarios_mercancia`, `cartera_clientes_mayoristas`, `cartera_clientes_detallistas`, `cuentas_pagar_proveedores`, `nomina`, `contabilidad`) VALUES 
(00000, 000, '', '', '0000-00-00', '1', '', '', '', '', '', '', '', 000, 00000, '1', 00000, '0', '0', '0', '0', '0', '0', '0', '0');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_sucursales_bancos`
-- 

DROP TABLE IF EXISTS `job_sucursales_bancos`;
CREATE TABLE `job_sucursales_bancos` (
  `codigo` smallint(2) unsigned zerofill NOT NULL COMMENT 'Codigo de la sucursal bancaria',
  `codigo_iso` varchar(2) collate latin1_spanish_ci NOT NULL COMMENT 'Llave principal de la tabla paises',
  `codigo_dane_departamento` varchar(2) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo dane del departamento',
  `codigo_dane_municipio` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'Código dane del municipio',
  `codigo_banco` smallint(2) unsigned zerofill NOT NULL COMMENT 'codigo de la tabla bancos',
  `nombre_sucursal` varchar(50) collate latin1_spanish_ci NOT NULL COMMENT 'Nombre de la sucursal del banco',
  `direccion` varchar(50) collate latin1_spanish_ci default NULL COMMENT 'Direccion donde se ubica la sucursal',
  `telefono` varchar(15) collate latin1_spanish_ci default NULL COMMENT 'Telefono de la sucursal',
  `contacto` varchar(255) collate latin1_spanish_ci default NULL COMMENT 'Contacto de la sucursal',
  `correo` varchar(255) collate latin1_spanish_ci default NULL COMMENT 'Correo del contacto de la sucursal',
  `celular` varchar(20) collate latin1_spanish_ci default NULL COMMENT 'Celular del contacto de la sucursal',
  PRIMARY KEY  (`codigo`,`codigo_banco`,`codigo_iso`,`codigo_dane_departamento`,`codigo_dane_municipio`),
  KEY `sucursales_bancos_id_banco` (`codigo_banco`),
  KEY `sucursales_bancos_id_municipio` (`codigo_iso`,`codigo_dane_departamento`,`codigo_dane_municipio`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_sucursales_bancos`
-- 

INSERT INTO `job_sucursales_bancos` (`codigo`, `codigo_iso`, `codigo_dane_departamento`, `codigo_dane_municipio`, `codigo_banco`, `nombre_sucursal`, `direccion`, `telefono`, `contacto`, `correo`, `celular`) VALUES 
(00, '', '', '', 00, '', '', '', '', '', '');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_tablas`
-- 

DROP TABLE IF EXISTS `job_tablas`;
CREATE TABLE `job_tablas` (
  `id` smallint(5) unsigned zerofill NOT NULL auto_increment COMMENT 'Id principal de tabla',
  `nombre_tabla` varchar(255) collate latin1_spanish_ci NOT NULL COMMENT 'Nombre de la tabla',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `nombre_tabla` (`nombre_tabla`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=3 ;

-- 
-- Volcar la base de datos para la tabla `job_tablas`
-- 

INSERT INTO `job_tablas` (`id`, `nombre_tabla`) VALUES 
(00002, '');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_tasas`
-- 

DROP TABLE IF EXISTS `job_tasas`;
CREATE TABLE `job_tasas` (
  `codigo` smallint(3) unsigned zerofill NOT NULL COMMENT 'CÃ³digo para uso interno de la empresa',
  `descripcion` varchar(255) collate latin1_spanish_ci NOT NULL default '' COMMENT 'Detalle que describe de la tasa',
  PRIMARY KEY  (`codigo`),
  UNIQUE KEY `descripcion` (`descripcion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_tasas`
-- 

INSERT INTO `job_tasas` (`codigo`, `descripcion`) VALUES 
(000, '');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_terceros`
-- 

DROP TABLE IF EXISTS `job_terceros`;
CREATE TABLE `job_terceros` (
  `documento_identidad` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'NÃºmero del documento de identidad',
  `codigo_tipo_documento` smallint(3) unsigned zerofill NOT NULL COMMENT 'Consecutivo interno para la base de datos del tipo de documento de identidad',
  `codigo_iso_municipio_documento` varchar(2) collate latin1_spanish_ci NOT NULL COMMENT 'Llave principal de la tabla paises',
  `codigo_dane_departamento_documento` varchar(2) collate latin1_spanish_ci NOT NULL COMMENT 'CÃ³digo DANE',
  `codigo_dane_municipio_documento` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'CÃ³digo DANE',
  `tipo_persona` enum('1','2','3','4') collate latin1_spanish_ci NOT NULL default '1' COMMENT 'Tipo de persona: 1=Natural, 2=Juridica, 3=CÃ³digo interno, 4 =Natural Comerciante',
  `primer_nombre` varchar(15) collate latin1_spanish_ci default NULL COMMENT 'Primer nombre (persona natural)',
  `segundo_nombre` varchar(15) collate latin1_spanish_ci default NULL COMMENT 'Segundo nombre (persona natural)',
  `primer_apellido` varchar(20) collate latin1_spanish_ci default NULL COMMENT 'Primer apellido (persona natural)',
  `segundo_apellido` varchar(20) collate latin1_spanish_ci default NULL COMMENT 'Segundo apellido (persona natural)',
  `razon_social` varchar(255) collate latin1_spanish_ci default NULL COMMENT 'Razon social (persona jurÃ­dica)',
  `nombre_comercial` varchar(255) collate latin1_spanish_ci default NULL COMMENT 'Nombre comercial (persona jurÃ­dica)',
  `fecha_nacimiento` date default NULL COMMENT 'Fecha de nacimiento de la persona Ã³ constituciÃ³n de la sociedad',
  `codigo_iso_localidad` varchar(2) collate latin1_spanish_ci NOT NULL COMMENT 'Llave principal de la tabla paises',
  `codigo_dane_departamento_localidad` varchar(2) collate latin1_spanish_ci NOT NULL COMMENT 'CÃ³digo DANE',
  `codigo_dane_municipio_localidad` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'CÃ³digo DANE',
  `tipo_localidad` enum('B','C') collate latin1_spanish_ci NOT NULL default 'B' COMMENT 'Tipo de localidad: B=Barrio, C=Corregimiento',
  `codigo_dane_localidad` varchar(3) collate latin1_spanish_ci default NULL COMMENT 'CÃ³digo DANE para el barrio o corregimiento',
  `direccion_principal` varchar(50) collate latin1_spanish_ci default NULL COMMENT 'DirecciÃ³n de residencia',
  `telefono_principal` varchar(15) collate latin1_spanish_ci default NULL COMMENT 'NÃºmero de telÃ©fono',
  `celular` varchar(20) collate latin1_spanish_ci default NULL COMMENT 'NÃºmero de celular',
  `celular2` varchar(20) collate latin1_spanish_ci default NULL COMMENT 'NÃºmero de celular 2',
  `fax` varchar(20) collate latin1_spanish_ci default NULL COMMENT 'NÃºmero de fax',
  `correo` varchar(255) collate latin1_spanish_ci default NULL COMMENT 'Direccion de correo electronico',
  `correo2` varchar(255) collate latin1_spanish_ci default NULL COMMENT 'Direccion de correo electronico 2',
  `sitio_web` varchar(50) collate latin1_spanish_ci default NULL COMMENT 'DirecciÃ³n del sitio web',
  `genero` enum('M','F','N') collate latin1_spanish_ci NOT NULL default 'N' COMMENT 'GÃ©nero: M=Masculino, F=Femenino, N=No aplica',
  `activo` enum('0','1') collate latin1_spanish_ci NOT NULL default '1' COMMENT 'El tercero estÃ¡ activo 0=No, 1=Si',
  `fecha_ingreso` date NOT NULL default '0000-00-00' COMMENT 'Fecha ingreso al sistema',
  PRIMARY KEY  (`documento_identidad`),
  KEY `tercero_tipo_documento` (`codigo_tipo_documento`),
  KEY `tercero_municipio_documento` (`codigo_iso_municipio_documento`,`codigo_dane_departamento_documento`,`codigo_dane_municipio_documento`),
  KEY `tercero_municipio_residencia` (`codigo_iso_localidad`,`codigo_dane_departamento_localidad`,`codigo_dane_municipio_localidad`,`tipo_localidad`,`codigo_dane_localidad`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_terceros`
-- 

INSERT INTO `job_terceros` (`documento_identidad`, `codigo_tipo_documento`, `codigo_iso_municipio_documento`, `codigo_dane_departamento_documento`, `codigo_dane_municipio_documento`, `tipo_persona`, `primer_nombre`, `segundo_nombre`, `primer_apellido`, `segundo_apellido`, `razon_social`, `nombre_comercial`, `fecha_nacimiento`, `codigo_iso_localidad`, `codigo_dane_departamento_localidad`, `codigo_dane_municipio_localidad`, `tipo_localidad`, `codigo_dane_localidad`, `direccion_principal`, `telefono_principal`, `celular`, `celular2`, `fax`, `correo`, `correo2`, `sitio_web`, `genero`, `activo`, `fecha_ingreso`) VALUES 
('0', 000, '', '', '', '3', '', '', '', '', '', '', '0000-00-00', '', '', '', 'B', '', '', '', '', NULL, '', '', NULL, '', 'M', '0', '0000-00-00');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_terminales`
-- 

DROP TABLE IF EXISTS `job_terminales`;
CREATE TABLE `job_terminales` (
  `id` smallint(3) unsigned zerofill NOT NULL auto_increment COMMENT 'Consecutivo interno de la base de datos',
  `id_servidor` smallint(3) unsigned zerofill NOT NULL COMMENT 'Consecutivo interno de la base de datos para el servidor al que pertenece',
  `ip` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'DrirecciÃ³n IP de la terminal',
  `nombre_netbios` varchar(50) collate latin1_spanish_ci NOT NULL COMMENT 'Nombre NetBIOS',
  `nombre_tcpip` varchar(50) collate latin1_spanish_ci NOT NULL COMMENT 'Nombre TCP/IP',
  `descripcion` varchar(50) collate latin1_spanish_ci default NULL COMMENT 'DescripciÃ³n de la terminal',
  PRIMARY KEY  (`id`),
  KEY `terminal_servidor` (`id_servidor`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1 ;

-- 
-- Volcar la base de datos para la tabla `job_terminales`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_tipos_bodegas`
-- 

DROP TABLE IF EXISTS `job_tipos_bodegas`;
CREATE TABLE `job_tipos_bodegas` (
  `codigo` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo del tipo de bodega',
  `nombre` varchar(60) collate latin1_spanish_ci NOT NULL COMMENT 'Nombre que identifica el tipo de bodega',
  `descripcion` varchar(60) collate latin1_spanish_ci NOT NULL COMMENT 'Nombre que describe el tipo de bodega',
  PRIMARY KEY  (`codigo`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_tipos_bodegas`
-- 

INSERT INTO `job_tipos_bodegas` (`codigo`, `nombre`, `descripcion`) VALUES 
(000, '', '');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_tipos_compra`
-- 

DROP TABLE IF EXISTS `job_tipos_compra`;
CREATE TABLE `job_tipos_compra` (
  `codigo` smallint(4) unsigned zerofill NOT NULL COMMENT 'Código asignado por el usuario al tipo de compra',
  `descripcion` varchar(150) collate latin1_spanish_ci NOT NULL COMMENT 'Nombre del tipo de compra asignado por el usuario',
  `codigo_contable_cuentas_pagar` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'codigo del plan contable que relaciona con la cuenta por pagar',
  `codigo_contable_retefuente` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'codigo del plan contable que relaciona con la Retencion en la fuente',
  `codigo_contable_reteiva` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'codigo del plan contable que relaciona con la Retencion iva',
  `codigo_contable_seguro` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'codigo del plan contable que relaciona con los seguros por transporte de mercancia',
  `codigo_contable_fletes` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'codigo del plan contable que relaciona con los fletes por transporte de mercancia',
  `codigo_contable_iva_seguro` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'codigo del plan contable que relaciona con el iva del seguro por transporte de mercancia',
  `codigo_contable_iva_fletes` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'codigo del plan contable que relaciona con el iva del flete por transporte de mercancia',
  `codigo_contable_iva_diferencia` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'codigo del plan contable que relaciona con la cuenta del IVA en caso de que el proveedor factura Iva y el movimiento de articulos no tenga ningun IVA',
  `concepto_compra` enum('1','2','3','4','5') collate latin1_spanish_ci NOT NULL default '1' COMMENT 'Concepto compra: solo son válidos los siguientes conceptos 1->Compras directas, 2->Compras obsequio, 3->Compras filiales, 4->Compras canje, 5->Compras en consignación',
  `codigo_tipo_documento_nota_debito` smallint(3) unsigned zerofill NOT NULL COMMENT 'código con el que se relaciona el tipo de documento en la nota debito',
  `valor_base_nota_debito` decimal(15,2) NOT NULL COMMENT 'Valor minimo por sobre el cual se empiezan a generar las notas debito',
  `codigo_contable_compra_nota_debito` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'codigo del plan contable que relaciona con la cuenta de compras en la nota debito',
  `codigo_contable_iva_nota_debito` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'codigo del plan contable que relaciona con la cuenta del IVA de la nota debito',
  `codigo_contable_cuentas_pagar_nota_debito` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'codigo del plan contable que relaciona con la cuenta por pagar de la nota debito',
  `codigo_contable_retefuente_nota_debito` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'codigo del plan contable que relaciona con la Retencion en la fuente de la nota debito',
  `codigo_contable_reteiva_nota_debito` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'codigo del plan contable que relaciona con la Retencion iva de la nota debito',
  `codigo_contable_reteica_nota_debito` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'codigo del plan contable que relaciona con la Retención ICA de la nota debito',
  `codigo_tipo_documento_nota_credito` smallint(3) unsigned zerofill NOT NULL COMMENT 'código con el que se relaciona el tipo de documento en la nota credito',
  `valor_base_nota_credito` decimal(15,2) NOT NULL COMMENT 'Valor minimo por sobre el cual se empiezan a generar las notas credito',
  `codigo_contable_compra_nota_credito` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'codigo del plan contable que relaciona con la cuenta de compras en la nota credito',
  `codigo_contable_iva_nota_credito` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'codigo del plan contable que relaciona con la cuenta del IVA de la nota credito',
  `codigo_contable_cuentas_pagar_nota_credito` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'codigo del plan contable que relaciona con la cuenta por pagar de la nota credito',
  `codigo_contable_retefuente_nota_credito` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'codigo del plan contable que relaciona con la Retencion en la fuente de la nota credito',
  `codigo_contable_reteiva_nota_credito` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'codigo del plan contable que relaciona con la Retencion iva de la nota credito',
  `codigo_contable_reteica_nota_credito` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'codigo del plan contable que relaciona con la Retencion ICA de la nota credito',
  `codigo_contable_inventario_provision` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'codigo del plan contable que relaciona plan contable inventario provision',
  `codigo_contable_puente_provision` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'codigo del plan contable que relaciona con plan contable puente provision',
  `codigo_contable_retefuente_provision` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'codigo del plan contable que relaciona con la Retencion en la fuente de la provision',
  PRIMARY KEY  (`codigo`),
  UNIQUE KEY `descripcion` (`descripcion`),
  KEY `tipo_compra_codigo_cuentas_pagar` (`codigo_contable_cuentas_pagar`),
  KEY `tipo_compra_codigo_retefuente` (`codigo_contable_retefuente`),
  KEY `tipo_compra_codigo_reteiva` (`codigo_contable_reteiva`),
  KEY `tipo_compra_codigo_seguro` (`codigo_contable_seguro`),
  KEY `tipo_compra_codigo_fletes` (`codigo_contable_fletes`),
  KEY `tipo_compra_codigo_iva_seguro` (`codigo_contable_iva_seguro`),
  KEY `tipo_compra_codigo_iva_fletes` (`codigo_contable_iva_fletes`),
  KEY `tipo_compra_codigo_iva_diferencia` (`codigo_contable_iva_diferencia`),
  KEY `tipo_compra_codigo_compra_nota_debito` (`codigo_contable_compra_nota_debito`),
  KEY `tipo_compra_codigo_iva_nota_debito` (`codigo_contable_iva_nota_debito`),
  KEY `tipo_compra_codigo_cuentas_pagar_nota_debito` (`codigo_contable_cuentas_pagar_nota_debito`),
  KEY `tipo_compra_codigo_retefuente_nota_debito` (`codigo_contable_retefuente_nota_debito`),
  KEY `tipo_compra_codigo_reteiva_nota_debito` (`codigo_contable_reteiva_nota_debito`),
  KEY `tipo_compra_codigo_reteica_nota_debito` (`codigo_contable_reteica_nota_debito`),
  KEY `tipo_compra_codigo_compra_nota_credito` (`codigo_contable_compra_nota_credito`),
  KEY `tipo_compra_codigo_iva_nota_credito` (`codigo_contable_iva_nota_credito`),
  KEY `tipo_compra_codigo_cuentas_pagar_nota_credito` (`codigo_contable_cuentas_pagar_nota_credito`),
  KEY `tipo_compra_codigo_retefuente_nota_credito` (`codigo_contable_retefuente_nota_credito`),
  KEY `tipo_compra_codigo_reteiva_nota_credito` (`codigo_contable_reteiva_nota_credito`),
  KEY `tipo_compra_codigo_reteica_nota_credito` (`codigo_contable_reteica_nota_credito`),
  KEY `tipo_compra_tipo_documento_nota_debito` (`codigo_tipo_documento_nota_debito`),
  KEY `tipo_compra_tipo_documento_nota_credito` (`codigo_tipo_documento_nota_credito`),
  KEY `tipo_compra_codigo_contable_inventario` (`codigo_contable_inventario_provision`),
  KEY `tipo_compra_codigo_contable_puente` (`codigo_contable_puente_provision`),
  KEY `tipo_compra_codigo_contable_retefuente_provision` (`codigo_contable_retefuente_provision`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_tipos_compra`
-- 

INSERT INTO `job_tipos_compra` (`codigo`, `descripcion`, `codigo_contable_cuentas_pagar`, `codigo_contable_retefuente`, `codigo_contable_reteiva`, `codigo_contable_seguro`, `codigo_contable_fletes`, `codigo_contable_iva_seguro`, `codigo_contable_iva_fletes`, `codigo_contable_iva_diferencia`, `concepto_compra`, `codigo_tipo_documento_nota_debito`, `valor_base_nota_debito`, `codigo_contable_compra_nota_debito`, `codigo_contable_iva_nota_debito`, `codigo_contable_cuentas_pagar_nota_debito`, `codigo_contable_retefuente_nota_debito`, `codigo_contable_reteiva_nota_debito`, `codigo_contable_reteica_nota_debito`, `codigo_tipo_documento_nota_credito`, `valor_base_nota_credito`, `codigo_contable_compra_nota_credito`, `codigo_contable_iva_nota_credito`, `codigo_contable_cuentas_pagar_nota_credito`, `codigo_contable_retefuente_nota_credito`, `codigo_contable_reteiva_nota_credito`, `codigo_contable_reteica_nota_credito`, `codigo_contable_inventario_provision`, `codigo_contable_puente_provision`, `codigo_contable_retefuente_provision`) VALUES 
(0000, '', '0', '0', '0', '0', '0', '0', '0', '', '1', 000, 0.00, '0', '0', '0', '0', '0', '0', 000, 0.00, '0', '0', '0', '0', '0', '0', '', '', '');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_tipos_comprobantes`
-- 

DROP TABLE IF EXISTS `job_tipos_comprobantes`;
CREATE TABLE `job_tipos_comprobantes` (
  `codigo` smallint(2) unsigned zerofill NOT NULL COMMENT 'Codigo asignado por el usuario para el comprobante',
  `descripcion` varchar(255) collate latin1_spanish_ci NOT NULL COMMENT 'Detalle que describe el tipo de comprobante',
  PRIMARY KEY  (`codigo`),
  UNIQUE KEY `descripcion` (`descripcion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_tipos_comprobantes`
-- 

INSERT INTO `job_tipos_comprobantes` (`codigo`, `descripcion`) VALUES 
(00, '');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_tipos_contrato`
-- 

DROP TABLE IF EXISTS `job_tipos_contrato`;
CREATE TABLE `job_tipos_contrato` (
  `codigo` smallint(3) unsigned zerofill NOT NULL COMMENT 'CÃ³digo interno que identifica el tipo de contrato',
  `descripcion` varchar(255) collate latin1_spanish_ci NOT NULL COMMENT 'Detalle que describe el tipo de contrato',
  `termino_contrato` enum('1','2','3','4') collate latin1_spanish_ci NOT NULL COMMENT '1->TÃ©rmino fijo menor a un aÃ±o 2->TÃ©rmino indefinido 3-> Sin relacion laboral 4-> Obra labor',
  `tipo_contratacion` enum('1','2','3','4','5','6','7','8','9','10') collate latin1_spanish_ci NOT NULL COMMENT '1->Integral 2->Al destajo 3->Practicante 4->PasantÃ­as 5->PrestaciÃ³n de servicios 6->Cooperativa de trabajo asociado 7->BÃ¡sico menor al minimo 8-> BÃ¡sico mayor al minimo 9-> Comision con BÃ¡sico 10-> Comision sin BÃ¡sico',
  `sueldo_ajusta_minimo` enum('0','1') collate latin1_spanish_ci NOT NULL COMMENT '0->No 1->Si',
  PRIMARY KEY  (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_tipos_contrato`
-- 

INSERT INTO `job_tipos_contrato` (`codigo`, `descripcion`, `termino_contrato`, `tipo_contratacion`, `sueldo_ajusta_minimo`) VALUES 
(000, '', '3', '1', '0');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_tipos_devoluciones_compra`
-- 

DROP TABLE IF EXISTS `job_tipos_devoluciones_compra`;
CREATE TABLE `job_tipos_devoluciones_compra` (
  `codigo` smallint(4) unsigned zerofill NOT NULL COMMENT 'Consecutivo interno para la base de datos',
  `descripcion` varchar(150) collate latin1_spanish_ci NOT NULL COMMENT 'Nombre del tipo devolucion compra asignado por el usuario',
  `concepto_compra` enum('1','2','3','4','5') collate latin1_spanish_ci NOT NULL default '1' COMMENT 'Concepto compra: solo son validos los siguientes conceptos 1->Compras directas, 2->Compras obsequio, 3->Compras filiales, 4->Compras canje, 5->Compras en consignacion',
  `codigo_contable_cuentas_pagar` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'codigo del plan contable que relaciona con la cuenta por pagar',
  `codigo_contable_retefuente` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'codigo del plan contable que relaciona con la Retencion en la fuente',
  `codigo_contable_reteiva` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'codigo del plan contable que relaciona con la Retencion iva',
  `codigo_contable_seguro` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'codigo del plan contable que relaciona con los seguros por transporte de mercancia',
  `codigo_contable_fletes` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'codigo del plan contable que relaciona con los fletes por transporte de mercancia',
  `codigo_contable_iva_seguro` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'codigo del plan contable que relaciona con el iva del seguro por transporte de mercancia',
  `codigo_contable_iva_fletes` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'codigo del plan contable que relaciona con el iva del flete por transporte de mercancia',
  PRIMARY KEY  (`codigo`),
  UNIQUE KEY `descripcion` (`descripcion`),
  KEY `codigo_contable_cuentas_pagar` (`codigo_contable_cuentas_pagar`),
  KEY `codigo_contable_retefuente` (`codigo_contable_retefuente`),
  KEY `codigo_contable_reteiva` (`codigo_contable_reteiva`),
  KEY `codigo_contable_seguro` (`codigo_contable_seguro`),
  KEY `codigo_contable_fletes` (`codigo_contable_fletes`),
  KEY `codigo_contable_iva_seguro` (`codigo_contable_iva_seguro`),
  KEY `codigo_contable_iva_fletes` (`codigo_contable_iva_fletes`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_tipos_devoluciones_compra`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_tipos_documento_identidad`
-- 

DROP TABLE IF EXISTS `job_tipos_documento_identidad`;
CREATE TABLE `job_tipos_documento_identidad` (
  `codigo` smallint(3) unsigned zerofill NOT NULL COMMENT 'CÃ³digo asignado por el usuario',
  `codigo_dian` smallint(3) unsigned zerofill NOT NULL COMMENT 'CÃ³digo manejo por la DIAN',
  `descripcion` varchar(255) collate latin1_spanish_ci NOT NULL COMMENT 'Detalle que identifica el tipo de documento de identidad',
  `tipo_persona` enum('1','2','3','4') collate latin1_spanish_ci NOT NULL default '1' COMMENT 'Tipo de persona para la que aplica el documento: 1->Natural,2->Juridica,3->Codigo interno,4->Natural Comerciante',
  PRIMARY KEY  (`codigo`),
  UNIQUE KEY `codigo_dian` (`codigo_dian`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_tipos_documento_identidad`
-- 

INSERT INTO `job_tipos_documento_identidad` (`codigo`, `codigo_dian`, `descripcion`, `tipo_persona`) VALUES 
(000, 000, '', '3');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_tipos_documentos`
-- 

DROP TABLE IF EXISTS `job_tipos_documentos`;
CREATE TABLE `job_tipos_documentos` (
  `codigo` smallint(3) unsigned zerofill NOT NULL COMMENT 'Código asignado por el usuario',
  `codigo_comprobante` smallint(2) unsigned zerofill NOT NULL COMMENT 'id de la tabla tipos comprobantes',
  `descripcion` varchar(255) collate latin1_spanish_ci NOT NULL COMMENT 'Detalle que identifica el documento para su impresion',
  `observaciones` varchar(255) collate latin1_spanish_ci default NULL COMMENT 'Observaciones para el tipo de documento (opcional)',
  `abreviaturas` char(3) collate latin1_spanish_ci NOT NULL COMMENT 'Detalle corto que identifica el tipo de documento',
  `tipo` smallint(2) NOT NULL COMMENT 'dependiendo de los módulos este dato permite realizar algunos controles',
  `manejo_automatico` enum('1','2','3','4') collate latin1_spanish_ci NOT NULL default '1' COMMENT '1->No tiene manejo automatico, 2->El consecutivo se maneja de manera automática(se verifica en la tabla consecutivos de documento), 3-> Consecutivo por mes, 4-> Documento externo ',
  `control_titulo` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT '0->No imprime titulos 1->El documento imprime títulos',
  `genera_cheque` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT '0->No genera cheques 1->El documento genera cheques',
  `aplica_notas` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT 'Si el documento aplica para las notas: 0->No aplica, 1->Aplica',
  `sentido_contable` enum('0','1','2') collate latin1_spanish_ci NOT NULL default '0' COMMENT 'Sentido contable del documento: 0->No aplica, 1->Debito, 2->Credito',
  `sentido_inventario` enum('0','1','2') collate latin1_spanish_ci NOT NULL default '0' COMMENT 'Sentido para el inventario del documento: 0->No aplica, 1->Entrada, 2->Salida',
  `equivalencia` varchar(25) collate latin1_spanish_ci default NULL COMMENT 'Codigo o identificación del tipo de documento un sistema anterior si se migrara la información',
  PRIMARY KEY  (`codigo`),
  UNIQUE KEY `descripcion` (`descripcion`),
  UNIQUE KEY `abreviaturas` (`abreviaturas`),
  KEY `tipos_documentos_id_comprobante` (`codigo_comprobante`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_tipos_documentos`
-- 

INSERT INTO `job_tipos_documentos` (`codigo`, `codigo_comprobante`, `descripcion`, `observaciones`, `abreviaturas`, `tipo`, `manejo_automatico`, `control_titulo`, `genera_cheque`, `aplica_notas`, `sentido_contable`, `sentido_inventario`, `equivalencia`) VALUES 
(000, 00, '', '', '0', 0, '1', '0', '0', '0', '0', '0', NULL);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_tipos_documentos_bancarios`
-- 

DROP TABLE IF EXISTS `job_tipos_documentos_bancarios`;
CREATE TABLE `job_tipos_documentos_bancarios` (
  `codigo` smallint(4) unsigned zerofill NOT NULL COMMENT 'Codigo de documento bancario',
  `descripcion` varchar(150) collate latin1_spanish_ci NOT NULL COMMENT 'Detalle que describe el tipo de documento bancario',
  PRIMARY KEY  (`codigo`),
  UNIQUE KEY `descripcion` (`descripcion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_tipos_documentos_bancarios`
-- 

INSERT INTO `job_tipos_documentos_bancarios` (`codigo`, `descripcion`) VALUES 
(0000, '');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_tipos_moneda`
-- 

DROP TABLE IF EXISTS `job_tipos_moneda`;
CREATE TABLE `job_tipos_moneda` (
  `codigo` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo asignado por la empresa',
  `codigo_dian` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo asignado por la DIAN',
  `nombre` varchar(30) collate latin1_spanish_ci NOT NULL COMMENT 'Nombre de la moneda',
  PRIMARY KEY  (`codigo`),
  UNIQUE KEY `codigo_dian` (`codigo_dian`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_tipos_moneda`
-- 

INSERT INTO `job_tipos_moneda` (`codigo`, `codigo_dian`, `nombre`) VALUES 
(000, 000, '');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_tipos_unidades`
-- 

DROP TABLE IF EXISTS `job_tipos_unidades`;
CREATE TABLE `job_tipos_unidades` (
  `codigo` smallint(2) unsigned zerofill NOT NULL COMMENT 'Codigo interno manejado por la empresa',
  `nombre` varchar(255) collate latin1_spanish_ci NOT NULL COMMENT 'Nombre que identifica el tipo de unidad de medida',
  PRIMARY KEY  (`codigo`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_tipos_unidades`
-- 

INSERT INTO `job_tipos_unidades` (`codigo`, `nombre`) VALUES 
(00, '');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_titulos_planillas`
-- 

DROP TABLE IF EXISTS `job_titulos_planillas`;
CREATE TABLE `job_titulos_planillas` (
  `columna` smallint(3) unsigned NOT NULL COMMENT 'Columna en la planilla',
  `nombre` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'Nombre de la columna en la planilla',
  `descripcion` varchar(255) collate latin1_spanish_ci default '' COMMENT 'Descripcion de la columna en planilla',
  PRIMARY KEY  (`columna`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_titulos_planillas`
-- 

INSERT INTO `job_titulos_planillas` (`columna`, `nombre`, `descripcion`) VALUES 
(1, 'Sal.devengado', ''),
(2, 'Aux.transp.', ''),
(3, 'Product.', ''),
(4, 'H.Extras', ''),
(5, 'Incap.', ''),
(6, 'Aux.vehiculos', ''),
(7, 'Aux.extraord.', ''),
(8, 'Prima vacacion', ''),
(9, 'Salud', ''),
(10, 'Pension', ''),
(11, 'Desc.empresa', ''),
(12, 'Otros Dsctos', '');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_transacciones_contables_empleado`
-- 

DROP TABLE IF EXISTS `job_transacciones_contables_empleado`;
CREATE TABLE `job_transacciones_contables_empleado` (
  `codigo` int(8) unsigned zerofill NOT NULL COMMENT 'Consecutivo interno para la base de datos',
  `nombre` varchar(40) collate latin1_spanish_ci NOT NULL COMMENT 'Detalle corto del tipo de transacción',
  `descripcion` varchar(255) collate latin1_spanish_ci NOT NULL COMMENT 'Detalle que describe la transaccioón',
  `codigo_contable` varchar(15) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo del plan contable',
  `sentido` enum('C','D') collate latin1_spanish_ci NOT NULL default 'C' COMMENT 'C->Credito D->Debito',
  `codigo_concepto_transaccion_contable` smallint(4) unsigned zerofill NOT NULL COMMENT 'Codigo de la tabla conceptos_transacciones_contables',
  `acumula_cesantias` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT '0->No 1->Si',
  `acumula_prima` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT '0->No 1->Si',
  `acumula_vacaciones` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT '0->No 1->Si',
  `ibc_salud` enum('0','1','2') collate latin1_spanish_ci NOT NULL default '0' COMMENT '0->No 1->Si 2->Si mayor del 40%',
  `ibc_pension` enum('0','1','2') collate latin1_spanish_ci NOT NULL default '0' COMMENT '0->No 1->Si 2->Si mayor del 40%',
  `ibc_arp` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT '0->No 1->Si',
  `ibc_icbf` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT '0->No 1->Si',
  `ibc_caja_compensacion` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT '0->No 1->Si',
  `ibc_sena` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT '0->No 1->Si',
  `certificado_ingresos_retenciones` varchar(4) collate latin1_spanish_ci default NULL COMMENT 'Níºmero de í­tem del certificado de ingresos y retenciones',
  `tipo_retencion` enum('1','2','3') collate latin1_spanish_ci NOT NULL default '1' COMMENT '1->No 2->Retención salarios 3->Retención vacaciones',
  `columna_planilla` varchar(10) collate latin1_spanish_ci NOT NULL COMMENT 'Columna en la planilla de pagos',
  PRIMARY KEY  (`codigo`),
  UNIQUE KEY `descripcion` (`descripcion`),
  KEY `transaccion_empleado_codigo_contable` (`codigo_contable`),
  KEY `transaccion_empleado_concepto_contable` (`codigo_concepto_transaccion_contable`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_transacciones_contables_empleado`
-- 

INSERT INTO `job_transacciones_contables_empleado` (`codigo`, `nombre`, `descripcion`, `codigo_contable`, `sentido`, `codigo_concepto_transaccion_contable`, `acumula_cesantias`, `acumula_prima`, `acumula_vacaciones`, `ibc_salud`, `ibc_pension`, `ibc_arp`, `ibc_icbf`, `ibc_caja_compensacion`, `ibc_sena`, `certificado_ingresos_retenciones`, `tipo_retencion`, `columna_planilla`) VALUES 
(00000000, '', '', '', 'D', 0001, '0', '0', '0', '0', '0', '0', '0', '0', '0', '', '1', '');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_transacciones_tiempo`
-- 

DROP TABLE IF EXISTS `job_transacciones_tiempo`;
CREATE TABLE `job_transacciones_tiempo` (
  `codigo` int(8) unsigned zerofill NOT NULL COMMENT 'Consecutivo interno para la base de datos',
  `nombre` varchar(40) collate latin1_spanish_ci NOT NULL COMMENT 'Descripcion corta de la transacciÃ³n',
  `descripcion` varchar(255) collate latin1_spanish_ci NOT NULL COMMENT 'Detalle que describe la transacciÃ³n',
  `codigo_concepto_transaccion_tiempo` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la tabla conceptos_transacciones_tiempo',
  `tasa` decimal(7,4) NOT NULL default '0.0000' COMMENT 'Porcentaje que corresponde sobre la hora de salario',
  `codigo_transaccion_contable` int(8) unsigned zerofill NOT NULL COMMENT 'Codigo interno que identifica la transaccion contable ',
  `resta_salario` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT '0->No 1->Si',
  `resta_auxilio_transporte` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT '0->No 1->Si',
  `resta_cesantias` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT '0->No 1->Si',
  `resta_prima` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT '0->No 1->Si',
  `resta_vacaciones` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT '0->No 1->Si',
  `extras_empleado` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT 'Estara activo para el empleado 0->No 1->Si',
  `dividendo` smallint(2) unsigned NOT NULL default '0' COMMENT 'Valor para liquidar incapacidades tipo de concepto: incapacidades',
  `divisor` smallint(2) unsigned NOT NULL default '0' COMMENT 'Valor sobre el que se divide para liquidar incapacidades tipo de concepto: incapacidades',
  PRIMARY KEY  (`codigo`),
  UNIQUE KEY `descripcion` (`descripcion`),
  KEY `transaccion_tiempo_transaccion_contable` (`codigo_transaccion_contable`),
  KEY `concepto_transaccion_tiempo` (`codigo_concepto_transaccion_tiempo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_transacciones_tiempo`
-- 

INSERT INTO `job_transacciones_tiempo` (`codigo`, `nombre`, `descripcion`, `codigo_concepto_transaccion_tiempo`, `tasa`, `codigo_transaccion_contable`, `resta_salario`, `resta_auxilio_transporte`, `resta_cesantias`, `resta_prima`, `resta_vacaciones`, `extras_empleado`, `dividendo`, `divisor`) VALUES 
(00000000, '', '', 001, 0.0000, 00000000, '0', '0', '0', '0', '0', '0', 0, 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_turnos_laborales`
-- 

DROP TABLE IF EXISTS `job_turnos_laborales`;
CREATE TABLE `job_turnos_laborales` (
  `codigo` smallint(4) unsigned zerofill NOT NULL COMMENT 'Codigo del turno laboral',
  `descripcion` varchar(250) collate latin1_spanish_ci NOT NULL COMMENT 'Id de la tabla de terceros',
  `permite_festivos` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT 'Permite festivos 0->No 1->Si',
  `paga_dominical` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT 'Paga dominical 0->No 1->Si',
  `paga_festivo` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT 'Paga festivo 0->No 1->Si',
  `tipo_turno_lunes` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT 'Jornada continua 0->No 1->Si',
  `dia_descanso_lunes` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT 'Dia de descanso 0->No 1->Si',
  `hora_inicial_turno1_lunes` time default NULL COMMENT 'Hora en que inicia el turno en la primera parte',
  `hora_final_turno1_lunes` time default NULL COMMENT 'Hora en que finaliza el turno en la primera parte',
  `hora_inicial_turno2_lunes` time default NULL COMMENT 'Hora en que inicia el turno en la segunda parte',
  `hora_final_turno2_lunes` time default NULL COMMENT 'Hora en que finaliza el turno en la segunda parte',
  `tipo_turno_martes` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT 'Jornada continua 0->No 1->Si',
  `dia_descanso_martes` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT 'Dia de descanso 0->No 1->Si',
  `hora_inicial_turno1_martes` time default NULL COMMENT 'Hora en que inicia el turno en la primera parte',
  `hora_final_turno1_martes` time default NULL COMMENT 'Hora en que finaliza el turno en la primera parte',
  `hora_inicial_turno2_martes` time default NULL COMMENT 'Hora en que inicia el turno en la segunda parte',
  `hora_final_turno2_martes` time default NULL COMMENT 'Hora en que finaliza el turno en la segunda parte',
  `tipo_turno_miercoles` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT 'Jornada continua 0->No 1->Si',
  `dia_descanso_miercoles` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT 'Dia de descanso 0->No 1->Si',
  `hora_inicial_turno1_miercoles` time default NULL COMMENT 'Hora en que inicia el turno en la primera parte',
  `hora_final_turno1_miercoles` time default NULL COMMENT 'Hora en que finaliza el turno en la primera parte',
  `hora_inicial_turno2_miercoles` time default NULL COMMENT 'Hora en que inicia el turno en la segunda parte',
  `hora_final_turno2_miercoles` time default NULL COMMENT 'Hora en que finaliza el turno en la segunda parte',
  `tipo_turno_jueves` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT 'Jornada continua 0->No 1->Si',
  `dia_descanso_jueves` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT 'Dia de descanso 0->No 1->Si',
  `hora_inicial_turno1_jueves` time default NULL COMMENT 'Hora en que inicia el turno en la primera parte',
  `hora_final_turno1_jueves` time default NULL COMMENT 'Hora en que finaliza el turno en la primera parte',
  `hora_inicial_turno2_jueves` time default NULL COMMENT 'Hora en que inicia el turno en la segunda parte',
  `hora_final_turno2_jueves` time default NULL COMMENT 'Hora en que finaliza el turno en la segunda parte',
  `tipo_turno_viernes` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT 'Jornada continua 0->No 1->Si',
  `dia_descanso_viernes` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT 'Dia de descanso 0->No 1->Si',
  `hora_inicial_turno1_viernes` time default NULL COMMENT 'Hora en que inicia el turno en la primera parte',
  `hora_final_turno1_viernes` time default NULL COMMENT 'Hora en que finaliza el turno en la primera parte',
  `hora_inicial_turno2_viernes` time default NULL COMMENT 'Hora en que inicia el turno en la segunda parte',
  `hora_final_turno2_viernes` time default NULL COMMENT 'Hora en que finaliza el turno en la segunda parte',
  `tipo_turno_sabado` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT 'Jornada continua 0->No 1->Si',
  `dia_descanso_sabado` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT 'Dia de descanso 0->No 1->Si',
  `hora_inicial_turno1_sabado` time default NULL COMMENT 'Hora en que inicia el turno en la primera parte',
  `hora_final_turno1_sabado` time default NULL COMMENT 'Hora en que finaliza el turno en la primera parte',
  `hora_inicial_turno2_sabado` time default NULL COMMENT 'Hora en que inicia el turno en la segunda parte',
  `hora_final_turno2_sabado` time default NULL COMMENT 'Hora en que finaliza el turno en la segunda parte',
  `tipo_turno_domingo` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT 'Jornada continua 0->No 1->Si',
  `dia_descanso_domingo` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT 'Dia de descanso 0->No 1->Si',
  `hora_inicial_turno1_domingo` time default NULL COMMENT 'Hora en que inicia el turno en la primera parte',
  `hora_final_turno1_domingo` time default NULL COMMENT 'Hora en que finaliza el turno en la primera parte',
  `hora_inicial_turno2_domingo` time default NULL COMMENT 'Hora en que inicia el turno en la segunda parte',
  `hora_final_turno2_domingo` time default NULL COMMENT 'Hora en que finaliza el turno en la segunda parte',
  PRIMARY KEY  (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_turnos_laborales`
-- 

INSERT INTO `job_turnos_laborales` (`codigo`, `descripcion`, `permite_festivos`, `paga_dominical`, `paga_festivo`, `tipo_turno_lunes`, `dia_descanso_lunes`, `hora_inicial_turno1_lunes`, `hora_final_turno1_lunes`, `hora_inicial_turno2_lunes`, `hora_final_turno2_lunes`, `tipo_turno_martes`, `dia_descanso_martes`, `hora_inicial_turno1_martes`, `hora_final_turno1_martes`, `hora_inicial_turno2_martes`, `hora_final_turno2_martes`, `tipo_turno_miercoles`, `dia_descanso_miercoles`, `hora_inicial_turno1_miercoles`, `hora_final_turno1_miercoles`, `hora_inicial_turno2_miercoles`, `hora_final_turno2_miercoles`, `tipo_turno_jueves`, `dia_descanso_jueves`, `hora_inicial_turno1_jueves`, `hora_final_turno1_jueves`, `hora_inicial_turno2_jueves`, `hora_final_turno2_jueves`, `tipo_turno_viernes`, `dia_descanso_viernes`, `hora_inicial_turno1_viernes`, `hora_final_turno1_viernes`, `hora_inicial_turno2_viernes`, `hora_final_turno2_viernes`, `tipo_turno_sabado`, `dia_descanso_sabado`, `hora_inicial_turno1_sabado`, `hora_final_turno1_sabado`, `hora_inicial_turno2_sabado`, `hora_final_turno2_sabado`, `tipo_turno_domingo`, `dia_descanso_domingo`, `hora_inicial_turno1_domingo`, `hora_final_turno1_domingo`, `hora_inicial_turno2_domingo`, `hora_final_turno2_domingo`) VALUES 
(0000, '', '0', '0', '0', '0', '0', NULL, NULL, NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '0', '0', NULL, NULL, NULL, NULL),
(0001, 'Jornada Normal', '1', '1', '1', '0', '0', '07:00:00', '12:00:00', '13:30:00', '17:00:00', '0', '0', '07:00:00', '12:00:00', '13:30:00', '17:00:00', '0', '0', '07:00:00', '12:00:00', '13:30:00', '17:00:00', '0', '0', '07:00:00', '12:00:00', '13:30:00', '17:00:00', '0', '0', '07:00:00', '12:00:00', '13:30:00', '17:00:00', '1', '0', '07:30:00', '12:00:00', '00:00:00', '00:00:00', '0', '1', '00:00:00', '00:00:00', '00:00:00', '00:00:00');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_unidades`
-- 

DROP TABLE IF EXISTS `job_unidades`;
CREATE TABLE `job_unidades` (
  `codigo` int(6) unsigned zerofill NOT NULL COMMENT 'Código interno generado por el sistema',
  `codigo_tipo_unidad` smallint(2) unsigned zerofill NOT NULL COMMENT 'Tipo de unidad de medida',
  `nombre` varchar(255) collate latin1_spanish_ci NOT NULL COMMENT 'Nombre de la unidad de medida',
  `factor_conversion` decimal(8,4) unsigned NOT NULL default '1.0000' COMMENT 'Factor de conversion en relación a otra unidad',
  `codigo_unidad_principal` int(6) unsigned NOT NULL COMMENT 'Si es la unidad principal va en cero, de lo contrario va el código definido como unidad principal, el sistema guarda el código interno de la unidad de medida principal',
  PRIMARY KEY  (`codigo`),
  UNIQUE KEY `nombre` (`nombre`),
  KEY `unidad_tipo_unidad` (`codigo_tipo_unidad`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_unidades`
-- 

INSERT INTO `job_unidades` (`codigo`, `codigo_tipo_unidad`, `nombre`, `factor_conversion`, `codigo_unidad_principal`) VALUES 
(000000, 00, '', 0.0000, 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_usuarios`
-- 

DROP TABLE IF EXISTS `job_usuarios`;
CREATE TABLE `job_usuarios` (
  `codigo` smallint(4) unsigned zerofill NOT NULL COMMENT 'Consecutivo interno para la base de datos',
  `usuario` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Nombre de acceso (login)',
  `contrasena` char(32) collate latin1_spanish_ci NOT NULL COMMENT 'ContraseÃ±a',
  `nombre` char(50) collate latin1_spanish_ci NOT NULL COMMENT 'Nombre completo',
  `correo` varchar(255) collate latin1_spanish_ci NOT NULL COMMENT 'DirecciÃ³n de correo electrÃ³nico',
  `cambiar_contrasena` enum('0','1') collate latin1_spanish_ci NOT NULL default '1' COMMENT 'Puede cambiar la contraseÃ±a: 0=No, 1=Si',
  `fecha_cambio_contrasena` datetime default NULL COMMENT 'Fecha del Ãºltimo cambio de contraseÃ±a',
  `cambio_contrasena_minimo` smallint(4) unsigned NOT NULL default '0' COMMENT 'MÃ­nimo nÃºmero de dÃ­as que deben transcurrir antes de cambiar la contraseÃ±a: 0=No aplica',
  `cambio_contrasena_maximo` smallint(4) unsigned NOT NULL default '0' COMMENT 'MÃ¡ximo nÃºmero de dÃ­as de que pueden transcurrir sin cambiar la contraseÃ±a: 0=No aplica',
  `fecha_expiracion` datetime default NULL COMMENT 'Fecha mÃ¡xima hasta la cual el usuario puede acceder a la aplicaciÃ³n: NULL = No aplica',
  `activo` enum('0','1') collate latin1_spanish_ci NOT NULL default '1' COMMENT 'El usuario se encuentra activo y puede acceder a la aplicaciÃ³n: 0 = No, 1= Si',
  PRIMARY KEY  (`codigo`),
  UNIQUE KEY `usuario` (`usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_usuarios`
-- 

INSERT INTO `job_usuarios` (`codigo`, `usuario`, `contrasena`, `nombre`, `correo`, `cambiar_contrasena`, `fecha_cambio_contrasena`, `cambio_contrasena_minimo`, `cambio_contrasena_maximo`, `fecha_expiracion`, `activo`) VALUES 
(0000, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'Administrador Principal', 'info@saeltda.com.co', '1', NULL, 0, 0, NULL, '1'),
(0001, 'MONICA', 'dcb7503dc842ca17dfbcff3fbd8e5a34', 'Monica Maria Valencia Artunduaga', 'administracion@enfriar.com', '1', NULL, 0, 0, NULL, '1'),
(0002, 'cama', '0e0039a085090e72488a015bda465dc7', 'Carlos Alberto Medrano Araujo', 'gerencia@enfriar.com.co', '1', NULL, 0, 0, NULL, '1');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_vehiculo_aspirante`
-- 

DROP TABLE IF EXISTS `job_vehiculo_aspirante`;
CREATE TABLE `job_vehiculo_aspirante` (
  `documento_identidad_aspirante` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo que identifica el tercero',
  `consecutivo` int(8) NOT NULL COMMENT 'Consecutivo interno para la base de datos',
  `tipo` enum('1','2','3','4','5','6') collate latin1_spanish_ci NOT NULL default '1' COMMENT '1->Motocicleta 2->Particular 3->Servicio publico 4->carga pequeÃ±o 5->carga grande 6->Bus Buseta',
  `matricula` varchar(20) collate latin1_spanish_ci NOT NULL COMMENT 'Matricula del vehiculo',
  `modelo` varchar(50) collate latin1_spanish_ci default NULL COMMENT 'Modelo del vehiculo',
  `marca` varchar(20) collate latin1_spanish_ci default NULL COMMENT 'Marca del vehiculo',
  `pignorado` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT '0->Si 1->No',
  PRIMARY KEY  (`documento_identidad_aspirante`,`consecutivo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_vehiculo_aspirante`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_vigencia_tasas`
-- 

DROP TABLE IF EXISTS `job_vigencia_tasas`;
CREATE TABLE `job_vigencia_tasas` (
  `codigo_tasa` smallint(3) unsigned zerofill NOT NULL COMMENT 'Codigo de la tabla de tasas',
  `fecha` date NOT NULL default '0000-00-00' COMMENT 'Fecha a partir de la cual empieza a regir los porcentajes y valores base',
  `porcentaje` decimal(7,4) NOT NULL default '0.0000' COMMENT 'Porcentaje de la tasa',
  `valor_base` decimal(15,2) NOT NULL default '0.00' COMMENT 'Valor base a partir del cual se aplica el porcentaje anterior',
  PRIMARY KEY  (`codigo_tasa`,`fecha`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_vigencia_tasas`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_vivienda_aspirante`
-- 

DROP TABLE IF EXISTS `job_vivienda_aspirante`;
CREATE TABLE `job_vivienda_aspirante` (
  `documento_identidad_aspirante` varchar(12) collate latin1_spanish_ci NOT NULL COMMENT 'Codigo que identifica el tercero',
  `consecutivo` int(8) NOT NULL COMMENT 'Consecutivo interno para la base de datos',
  `tipo` enum('1','2','3','4','5') collate latin1_spanish_ci NOT NULL default '1' COMMENT '1->Casa 2->Apto 3->Mejora 4->Lote 5->Edificio',
  `hipoteca` enum('0','1') collate latin1_spanish_ci NOT NULL default '0' COMMENT '0->No 1->Si',
  `direccion` varchar(50) collate latin1_spanish_ci default NULL COMMENT 'Direccion de la propiedad ',
  `codigo_iso_barrio` varchar(2) collate latin1_spanish_ci NOT NULL COMMENT 'Llave principal de la tabla paises',
  `codigo_dane_departamento_barrio` varchar(2) collate latin1_spanish_ci NOT NULL COMMENT 'CÃ³digo DANE',
  `codigo_dane_municipio_barrio` varchar(3) collate latin1_spanish_ci NOT NULL COMMENT 'CÃ³digo DANE',
  `tipo_barrio` enum('B','C') collate latin1_spanish_ci NOT NULL default 'B' COMMENT 'Tipo de localidad: B=Barrio, C=Corregimiento',
  `codigo_dane_localidad_barrio` varchar(3) collate latin1_spanish_ci default NULL COMMENT 'CÃ³digo DANE (sÃ³lo para corregimientos)',
  `telefono` varchar(15) collate latin1_spanish_ci default NULL COMMENT 'Numero telefonico que tiene la vivienda',
  PRIMARY KEY  (`documento_identidad_aspirante`,`consecutivo`),
  KEY `vivienda_barrio` (`codigo_iso_barrio`,`codigo_dane_departamento_barrio`,`codigo_dane_municipio_barrio`,`tipo_barrio`,`codigo_dane_localidad_barrio`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- 
-- Volcar la base de datos para la tabla `job_vivienda_aspirante`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `sem_totalizador_saldos_movimientos_contables`
-- 

DROP TABLE IF EXISTS `sem_totalizador_saldos_movimientos_contables`;
CREATE TABLE `sem_totalizador_saldos_movimientos_contables` (
  `id_tercero` varchar(12) default NULL,
  `id_cuenta` varchar(15) default NULL,
  `id_consecutivo` varbinary(38) default NULL,
  `consecutivo` int(8) unsigned default NULL,
  `id_documento` smallint(3) unsigned zerofill default NULL,
  `id_saldo` varbinary(102) default NULL,
  `saldo` int(9) unsigned default NULL,
  `id_abono` varbinary(114) default NULL,
  `abono` int(9) unsigned default NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Volcar la base de datos para la tabla `sem_totalizador_saldos_movimientos_contables`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_agrupar_causaciones_prestaciones_sociales`
-- 

DROP VIEW IF EXISTS `job_agrupar_causaciones_prestaciones_sociales`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_agrupar_causaciones_prestaciones_sociales` AS select concat(`jobdaily`.`job_causaciones_prestaciones_sociales`.`codigo_empresa`,_utf8'|',`jobdaily`.`job_causaciones_prestaciones_sociales`.`fecha_liquidacion`,_utf8'|',`jobdaily`.`job_causaciones_prestaciones_sociales`.`documento_identidad_empleado`,_utf8'|',`jobdaily`.`job_causaciones_prestaciones_sociales`.`codigo_transaccion_contable`) AS `id`,`jobdaily`.`job_causaciones_prestaciones_sociales`.`codigo_sucursal` AS `codigo_sucursal`,`jobdaily`.`job_causaciones_prestaciones_sociales`.`concepto` AS `concepto`,`jobdaily`.`job_causaciones_prestaciones_sociales`.`codigo_empresa` AS `codigo_empresa`,`jobdaily`.`job_causaciones_prestaciones_sociales`.`valor_movimiento` AS `valor_movimiento`,`jobdaily`.`job_causaciones_prestaciones_sociales`.`documento_identidad_empleado` AS `documento_identidad_empleado`,`jobdaily`.`job_causaciones_prestaciones_sociales`.`fecha_liquidacion` AS `fecha_liquidacion`,`jobdaily`.`job_causaciones_prestaciones_sociales`.`fecha_contabilizacion` AS `fecha_contabilizacion`,`jobdaily`.`job_causaciones_prestaciones_sociales`.`codigo_tipo_documento` AS `codigo_tipo_documento`,`jobdaily`.`job_causaciones_prestaciones_sociales`.`codigo_tipo_comprobante` AS `codigo_tipo_comprobante`,`jobdaily`.`job_causaciones_prestaciones_sociales`.`numero_comprobante` AS `numero_comprobante` from `jobdaily`.`job_causaciones_prestaciones_sociales` group by `jobdaily`.`job_causaciones_prestaciones_sociales`.`codigo_empresa`,`jobdaily`.`job_causaciones_prestaciones_sociales`.`documento_identidad_empleado`,`jobdaily`.`job_causaciones_prestaciones_sociales`.`concepto`,`jobdaily`.`job_causaciones_prestaciones_sociales`.`fecha_liquidacion`;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_abonos_movimientos_contables`
-- 

DROP VIEW IF EXISTS `job_buscador_abonos_movimientos_contables`;
CREATE ALGORITHM=MERGE DEFINER=`jobdaily`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_abonos_movimientos_contables` AS select concat(`aimc`.`codigo_sucursal_saldo`,_latin1'|',`aimc`.`documento_identidad_tercero_saldo`,_latin1'|',`aimc`.`codigo_tipo_comprobante_saldo`,_latin1'|',`aimc`.`numero_comprobante_saldo`,_latin1'|',`aimc`.`codigo_tipo_documento_saldo`,_latin1'|',`aimc`.`consecutivo_documento_saldo`,_latin1'|',`aimc`.`fecha_contabilizacion_saldo`,_latin1'|',`aimc`.`consecutivo_saldo`,_latin1'|',`aimc`.`fecha_vencimiento_saldo`,_latin1'|',`aimc`.`consecutivo`) AS `id`,`aimc`.`valor` AS `valor`,`aimc`.`fecha_pago_abono` AS `fecha_pago`,`aimc`.`codigo_tipo_documento` AS `codigo_tipo_documento`,`aimc`.`consecutivo_documento` AS `consecutivo_documento`,concat(`aimc`.`codigo_sucursal_saldo`,_latin1'|',`aimc`.`documento_identidad_tercero_saldo`,_latin1'|',`aimc`.`codigo_tipo_comprobante_saldo`,_latin1'|',`aimc`.`numero_comprobante_saldo`,_latin1'|',`aimc`.`codigo_tipo_documento_saldo`,_latin1'|',`aimc`.`consecutivo_documento_saldo`,_latin1'|',`aimc`.`fecha_contabilizacion_saldo`,_latin1'|',`aimc`.`consecutivo_saldo`,_latin1'|',`aimc`.`fecha_vencimiento_saldo`) AS `id_saldo`,concat(`aimc`.`codigo_sucursal`,_latin1'|',`aimc`.`documento_identidad_tercero`,_latin1'|',`aimc`.`codigo_tipo_comprobante`,_latin1'|',`aimc`.`numero_comprobante`,_latin1'|',`aimc`.`codigo_tipo_documento`,_latin1'|',`aimc`.`consecutivo_documento`,_latin1'|',`aimc`.`fecha_contabilizacion`,_latin1'|',`aimc`.`consecutivo_item`) AS `id_item_movimiento` from ((`jobdaily`.`job_abonos_items_movimientos_contables` `aimc` join `jobdaily`.`job_items_movimientos_contables` `imc`) join `jobdaily`.`job_movimientos_contables` `mc`) where ((`imc`.`codigo_sucursal` = `aimc`.`codigo_sucursal`) and (`imc`.`documento_identidad_tercero` = `aimc`.`documento_identidad_tercero`) and (`imc`.`codigo_tipo_comprobante` = `aimc`.`codigo_tipo_comprobante`) and (`imc`.`numero_comprobante` = `aimc`.`numero_comprobante`) and (`imc`.`codigo_tipo_documento` = `aimc`.`codigo_tipo_documento`) and (`imc`.`consecutivo_documento` = `aimc`.`consecutivo_documento`) and (`imc`.`fecha_contabilizacion` = `aimc`.`fecha_contabilizacion`) and (`imc`.`consecutivo` = `aimc`.`consecutivo_item`) and (`mc`.`codigo_sucursal` = `imc`.`codigo_sucursal`) and (`mc`.`documento_identidad_tercero` = `imc`.`documento_identidad_tercero`) and (`mc`.`codigo_tipo_comprobante` = `imc`.`codigo_tipo_comprobante`) and (`mc`.`numero_comprobante` = `imc`.`numero_comprobante`) and (`mc`.`codigo_tipo_documento` = `imc`.`codigo_tipo_documento`) and (`mc`.`consecutivo_documento` = `imc`.`consecutivo_documento`) and (`mc`.`fecha_contabilizacion` = `mc`.`fecha_contabilizacion`) and (`mc`.`estado` <> _latin1'2'));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_actividades_economicas`
-- 

DROP VIEW IF EXISTS `job_buscador_actividades_economicas`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_actividades_economicas` AS select concat(`jobdaily`.`job_actividades_economicas`.`codigo_iso`,_utf8'|',`jobdaily`.`job_actividades_economicas`.`codigo_dane_departamento`,_utf8'|',`jobdaily`.`job_actividades_economicas`.`codigo_dane_municipio`,_utf8'|',`jobdaily`.`job_actividades_economicas`.`codigo_dian`,_utf8'|',`jobdaily`.`job_actividades_economicas`.`codigo_actividad_municipio`) AS `id`,`jobdaily`.`job_municipios`.`nombre` AS `municipios`,`jobdaily`.`job_actividades_economicas`.`codigo_iso` AS `codigo_iso`,`jobdaily`.`job_actividades_economicas`.`codigo_dane_departamento` AS `departamento`,`jobdaily`.`job_actividades_economicas`.`codigo_dane_municipio` AS `municipio`,`jobdaily`.`job_actividades_economicas`.`codigo_actividad_municipio` AS `actividad_municipio`,`jobdaily`.`job_actividades_economicas_dian`.`codigo_dian` AS `codigo_dian`,`jobdaily`.`job_actividades_economicas`.`codigo_interno` AS `codigo_interno`,`jobdaily`.`job_actividades_economicas`.`descripcion` AS `descripcion`,`jobdaily`.`job_actividades_economicas_dian`.`descripcion` AS `actividad_dian` from ((`jobdaily`.`job_actividades_economicas` join `jobdaily`.`job_actividades_economicas_dian`) join `jobdaily`.`job_municipios`) where ((`jobdaily`.`job_actividades_economicas`.`codigo_iso` = `jobdaily`.`job_municipios`.`codigo_iso`) and (`jobdaily`.`job_actividades_economicas`.`codigo_dane_departamento` = `jobdaily`.`job_municipios`.`codigo_dane_departamento`) and (`jobdaily`.`job_actividades_economicas`.`codigo_dane_municipio` = `jobdaily`.`job_municipios`.`codigo_dane_municipio`) and (`jobdaily`.`job_actividades_economicas`.`codigo_dian` = `jobdaily`.`job_actividades_economicas_dian`.`codigo_dian`) and (`jobdaily`.`job_actividades_economicas`.`codigo_dian` <> 0));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_actividades_economicas_dian`
-- 

DROP VIEW IF EXISTS `job_buscador_actividades_economicas_dian`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_actividades_economicas_dian` AS select `jobdaily`.`job_actividades_economicas_dian`.`codigo_dian` AS `id`,`jobdaily`.`job_actividades_economicas_dian`.`codigo_dian` AS `codigo_dian`,`jobdaily`.`job_actividades_economicas_dian`.`descripcion` AS `descripcion` from `jobdaily`.`job_actividades_economicas_dian` where (`jobdaily`.`job_actividades_economicas_dian`.`codigo_dian` <> 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_aficiones`
-- 

DROP VIEW IF EXISTS `job_buscador_aficiones`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_aficiones` AS select `jobdaily`.`job_aficiones`.`codigo` AS `id`,`jobdaily`.`job_aficiones`.`codigo` AS `CODIGO`,`jobdaily`.`job_aficiones`.`descripcion` AS `DESCRIPCION` from `jobdaily`.`job_aficiones` where (`jobdaily`.`job_aficiones`.`codigo` > 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_anexos_contables`
-- 

DROP VIEW IF EXISTS `job_buscador_anexos_contables`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_anexos_contables` AS select `jobdaily`.`job_anexos_contables`.`codigo` AS `id`,`jobdaily`.`job_anexos_contables`.`codigo` AS `codigo_anexo`,`jobdaily`.`job_anexos_contables`.`descripcion` AS `descripcion` from `jobdaily`.`job_anexos_contables` where (`jobdaily`.`job_anexos_contables`.`codigo` <> _latin1'');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_articulos`
-- 

DROP VIEW IF EXISTS `job_buscador_articulos`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_articulos` AS select `jobdaily`.`job_articulos`.`codigo` AS `id`,`jobdaily`.`job_articulos`.`codigo` AS `codigo`,`jobdaily`.`job_articulos`.`descripcion` AS `descripcion`,`jobdaily`.`job_marcas`.`descripcion` AS `marca`,`jobdaily`.`job_referencias_proveedor`.`referencia` AS `referencia`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),`jobdaily`.`job_terceros`.`primer_nombre`,_latin1''),_latin1' ',if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1''),_latin1' ',if((`jobdaily`.`job_terceros`.`primer_apellido` is not null),`jobdaily`.`job_terceros`.`primer_apellido`,_latin1''),_latin1' ',if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1''),_latin1' ',if((`jobdaily`.`job_terceros`.`razon_social` is not null),`jobdaily`.`job_terceros`.`razon_social`,_latin1'')) AS `proveedor` from ((((`jobdaily`.`job_articulos` join `jobdaily`.`job_marcas`) join `jobdaily`.`job_referencias_proveedor`) join `jobdaily`.`job_terceros`) join `jobdaily`.`job_proveedores`) where ((`jobdaily`.`job_articulos`.`codigo_marca` = `jobdaily`.`job_marcas`.`codigo`) and (`jobdaily`.`job_articulos`.`codigo` = `jobdaily`.`job_referencias_proveedor`.`codigo_articulo`) and (`jobdaily`.`job_proveedores`.`documento_identidad` = `jobdaily`.`job_referencias_proveedor`.`documento_identidad_proveedor`) and (`jobdaily`.`job_proveedores`.`documento_identidad` = `jobdaily`.`job_terceros`.`documento_identidad`) and (`jobdaily`.`job_articulos`.`codigo` <> 0));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_asignacion_turnos`
-- 

DROP VIEW IF EXISTS `job_buscador_asignacion_turnos`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_asignacion_turnos` AS select `jobdaily`.`job_asignacion_turnos`.`consecutivo` AS `id`,`jobdaily`.`job_sucursales`.`nombre` AS `SUCURSAL`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`primer_apellido` is not null),concat(`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),concat(`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1' '),_latin1'')) AS `EMPLEADO`,`jobdaily`.`job_turnos_laborales`.`descripcion` AS `DESCRIPCION_TURNO`,`jobdaily`.`job_asignacion_turnos`.`fecha_inicial` AS `FECHA_INICIAL`,`jobdaily`.`job_asignacion_turnos`.`fecha_final` AS `FECHA_FINAL` from (((((`jobdaily`.`job_terceros` join `jobdaily`.`job_aspirantes`) join `jobdaily`.`job_sucursal_contrato_empleados`) join `jobdaily`.`job_asignacion_turnos`) join `jobdaily`.`job_sucursales`) join `jobdaily`.`job_turnos_laborales`) where ((`jobdaily`.`job_sucursal_contrato_empleados`.`documento_identidad_empleado` = `jobdaily`.`job_aspirantes`.`documento_identidad`) and (`jobdaily`.`job_aspirantes`.`documento_identidad` = `jobdaily`.`job_terceros`.`documento_identidad`) and (`jobdaily`.`job_asignacion_turnos`.`documento_identidad_empleado` = `jobdaily`.`job_sucursal_contrato_empleados`.`documento_identidad_empleado`) and (`jobdaily`.`job_asignacion_turnos`.`codigo_sucursal` = `jobdaily`.`job_sucursales`.`codigo`) and (`jobdaily`.`job_asignacion_turnos`.`codigo_turno` = `jobdaily`.`job_turnos_laborales`.`codigo`));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_aspirantes`
-- 

DROP VIEW IF EXISTS `job_buscador_aspirantes`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_aspirantes` AS select `jobdaily`.`job_terceros`.`documento_identidad` AS `id`,`jobdaily`.`job_terceros`.`documento_identidad` AS `NUMERO_DOCUMENTO`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),`jobdaily`.`job_terceros`.`primer_nombre`,_latin1''),_latin1' ',if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1''),_latin1' ',if((`jobdaily`.`job_terceros`.`primer_apellido` is not null),`jobdaily`.`job_terceros`.`primer_apellido`,_latin1''),_latin1' ',if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1''),_latin1' ',if((`jobdaily`.`job_terceros`.`razon_social` is not null),`jobdaily`.`job_terceros`.`razon_social`,_latin1'')) AS `NOMBRE_COMPLETO` from (`jobdaily`.`job_terceros` join `jobdaily`.`job_aspirantes`) where (`jobdaily`.`job_aspirantes`.`documento_identidad` = `jobdaily`.`job_terceros`.`documento_identidad`);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_auxiliares_contables`
-- 

DROP VIEW IF EXISTS `job_buscador_auxiliares_contables`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_auxiliares_contables` AS select concat(`jobdaily`.`job_auxiliares_contables`.`codigo_empresa`,_utf8'|',`jobdaily`.`job_auxiliares_contables`.`codigo_anexo_contable`,_utf8'|',`jobdaily`.`job_auxiliares_contables`.`codigo`) AS `id`,concat(`jobdaily`.`job_auxiliares_contables`.`codigo_empresa`,_utf8'|',`jobdaily`.`job_auxiliares_contables`.`codigo_anexo_contable`) AS `codigo_id`,`jobdaily`.`job_auxiliares_contables`.`codigo` AS `codigo`,`jobdaily`.`job_auxiliares_contables`.`descripcion` AS `descripcion`,`jobdaily`.`job_auxiliares_contables`.`codigo_anexo_contable` AS `codigo_anexo_contable`,`jobdaily`.`job_anexos_contables`.`descripcion` AS `anexo_contable`,`jobdaily`.`job_empresas`.`razon_social` AS `empresa`,`jobdaily`.`job_empresas`.`codigo` AS `id_empresa` from ((`jobdaily`.`job_auxiliares_contables` join `jobdaily`.`job_anexos_contables`) join `jobdaily`.`job_empresas`) where ((`jobdaily`.`job_auxiliares_contables`.`codigo_anexo_contable` = `jobdaily`.`job_anexos_contables`.`codigo`) and (`jobdaily`.`job_auxiliares_contables`.`codigo_empresa` = `jobdaily`.`job_empresas`.`codigo`) and (`jobdaily`.`job_auxiliares_contables`.`codigo` > 0));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_auxilio_transporte`
-- 

DROP VIEW IF EXISTS `job_buscador_auxilio_transporte`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_auxilio_transporte` AS select `jobdaily`.`job_auxilio_transporte`.`codigo` AS `id`,`jobdaily`.`job_auxilio_transporte`.`fecha` AS `FECHA`,`jobdaily`.`job_auxilio_transporte`.`valor` AS `VALOR` from `jobdaily`.`job_auxilio_transporte`;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_bancos`
-- 

DROP VIEW IF EXISTS `job_buscador_bancos`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_bancos` AS select `jobdaily`.`job_bancos`.`codigo` AS `id`,if(((`jobdaily`.`job_terceros`.`tipo_persona` = _latin1'1') or (`jobdaily`.`job_terceros`.`tipo_persona` = _latin1'4')),concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' ',if(((`jobdaily`.`job_terceros`.`segundo_nombre` is not null) and (`jobdaily`.`job_terceros`.`segundo_nombre` <> _latin1'')),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1''),`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' ',if(((`jobdaily`.`job_terceros`.`segundo_apellido` is not null) and (`jobdaily`.`job_terceros`.`segundo_apellido` <> _latin1'')),`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1'')),`jobdaily`.`job_terceros`.`razon_social`) AS `tercero`,`jobdaily`.`job_terceros`.`documento_identidad` AS `documento_identidad`,concat(`jobdaily`.`job_localidades`.`nombre`,_latin1', ',`jobdaily`.`job_municipios`.`nombre`,_latin1', ',`jobdaily`.`job_departamentos`.`nombre`,_latin1', ',`jobdaily`.`job_paises`.`nombre`) AS `ciudad_residencia` from (((((`jobdaily`.`job_bancos` join `jobdaily`.`job_terceros`) join `jobdaily`.`job_localidades`) join `jobdaily`.`job_municipios`) join `jobdaily`.`job_departamentos`) join `jobdaily`.`job_paises`) where ((`jobdaily`.`job_bancos`.`codigo` > 0) and (`jobdaily`.`job_bancos`.`documento_identidad_tercero` = `jobdaily`.`job_terceros`.`documento_identidad`) and (`jobdaily`.`job_terceros`.`codigo_iso_localidad` = `jobdaily`.`job_paises`.`codigo_iso`) and (`jobdaily`.`job_terceros`.`codigo_iso_localidad` = `jobdaily`.`job_departamentos`.`codigo_iso`) and (`jobdaily`.`job_terceros`.`codigo_dane_departamento_localidad` = `jobdaily`.`job_departamentos`.`codigo_dane_departamento`) and (`jobdaily`.`job_terceros`.`codigo_iso_localidad` = `jobdaily`.`job_municipios`.`codigo_iso`) and (`jobdaily`.`job_terceros`.`codigo_dane_departamento_localidad` = `jobdaily`.`job_municipios`.`codigo_dane_departamento`) and (`jobdaily`.`job_terceros`.`codigo_dane_municipio_localidad` = `jobdaily`.`job_municipios`.`codigo_dane_municipio`) and (`jobdaily`.`job_terceros`.`codigo_iso_localidad` = `jobdaily`.`job_localidades`.`codigo_iso`) and (`jobdaily`.`job_terceros`.`codigo_dane_departamento_localidad` = `jobdaily`.`job_localidades`.`codigo_dane_departamento`) and (`jobdaily`.`job_terceros`.`codigo_dane_municipio_localidad` = `jobdaily`.`job_localidades`.`codigo_dane_municipio`) and (`jobdaily`.`job_terceros`.`tipo_localidad` = `jobdaily`.`job_localidades`.`tipo`) and (`jobdaily`.`job_terceros`.`codigo_dane_localidad` = `jobdaily`.`job_localidades`.`codigo_dane_localidad`));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_barrios`
-- 

DROP VIEW IF EXISTS `job_buscador_barrios`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_barrios` AS select concat(`jobdaily`.`job_localidades`.`codigo_iso`,_latin1'|',`jobdaily`.`job_localidades`.`codigo_dane_departamento`,_latin1'|',`jobdaily`.`job_localidades`.`codigo_dane_municipio`,_latin1'|',`jobdaily`.`job_localidades`.`tipo`,_latin1'|',`jobdaily`.`job_localidades`.`codigo_dane_localidad`) AS `id`,concat(`jobdaily`.`job_localidades`.`codigo_iso`,_latin1'|',`jobdaily`.`job_localidades`.`codigo_dane_departamento`,_latin1'|',`jobdaily`.`job_localidades`.`codigo_dane_municipio`,_latin1'|',`jobdaily`.`job_localidades`.`tipo`) AS `codigo`,`jobdaily`.`job_localidades`.`nombre` AS `nombre`,`jobdaily`.`job_localidades`.`codigo_dane_localidad` AS `codigo_localidad`,`jobdaily`.`job_localidades`.`codigo_interno` AS `codigo_interno`,`jobdaily`.`job_localidades`.`estrato` AS `estrato`,`jobdaily`.`job_localidades`.`comuna` AS `comuna`,`jobdaily`.`job_municipios`.`nombre` AS `municipio`,`jobdaily`.`job_departamentos`.`nombre` AS `departamento`,`jobdaily`.`job_paises`.`nombre` AS `pais` from (((`jobdaily`.`job_localidades` join `jobdaily`.`job_municipios`) join `jobdaily`.`job_departamentos`) join `jobdaily`.`job_paises`) where ((`jobdaily`.`job_localidades`.`codigo_iso` = `jobdaily`.`job_municipios`.`codigo_iso`) and (`jobdaily`.`job_localidades`.`codigo_dane_departamento` = `jobdaily`.`job_municipios`.`codigo_dane_departamento`) and (`jobdaily`.`job_localidades`.`codigo_dane_municipio` = `jobdaily`.`job_municipios`.`codigo_dane_municipio`) and (`jobdaily`.`job_localidades`.`codigo_iso` = `jobdaily`.`job_departamentos`.`codigo_iso`) and (`jobdaily`.`job_localidades`.`codigo_dane_departamento` = `jobdaily`.`job_departamentos`.`codigo_dane_departamento`) and (`jobdaily`.`job_localidades`.`codigo_iso` = `jobdaily`.`job_paises`.`codigo_iso`) and (`jobdaily`.`job_localidades`.`tipo` = _latin1'B')) order by `jobdaily`.`job_localidades`.`nombre`;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_bodegas`
-- 

DROP VIEW IF EXISTS `job_buscador_bodegas`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_bodegas` AS select concat(`jobdaily`.`job_bodegas`.`codigo`,_utf8'|',`jobdaily`.`job_bodegas`.`codigo_sucursal`) AS `id`,`jobdaily`.`job_bodegas`.`codigo` AS `codigo`,`jobdaily`.`job_bodegas`.`codigo_sucursal` AS `codigo_sucursal`,`jobdaily`.`job_bodegas`.`nombre` AS `nombre`,`jobdaily`.`job_bodegas`.`descripcion` AS `descripcion`,`jobdaily`.`job_bodegas`.`codigo_tipo_bodega` AS `tipo_bodega`,`jobdaily`.`job_sucursales`.`nombre` AS `sucursal` from (`jobdaily`.`job_bodegas` join `jobdaily`.`job_sucursales`) where ((`jobdaily`.`job_bodegas`.`codigo_sucursal` = `jobdaily`.`job_sucursales`.`codigo`) and (`jobdaily`.`job_bodegas`.`codigo` > 0));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_cargos`
-- 

DROP VIEW IF EXISTS `job_buscador_cargos`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_cargos` AS select `jobdaily`.`job_cargos`.`codigo` AS `id`,`jobdaily`.`job_cargos`.`codigo` AS `codigo`,`jobdaily`.`job_cargos`.`nombre` AS `nombre` from `jobdaily`.`job_cargos` where (`jobdaily`.`job_cargos`.`codigo` <> 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_cargos_externos`
-- 

DROP VIEW IF EXISTS `job_buscador_cargos_externos`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_cargos_externos` AS select `jobdaily`.`job_cargos`.`codigo` AS `id`,`jobdaily`.`job_cargos`.`codigo` AS `codigo`,`jobdaily`.`job_cargos`.`nombre` AS `nombre` from `jobdaily`.`job_cargos` where ((`jobdaily`.`job_cargos`.`interno` = _latin1'0') and (`jobdaily`.`job_cargos`.`codigo` <> 0));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_causaciones_prestaciones_sociales`
-- 

DROP VIEW IF EXISTS `job_buscador_causaciones_prestaciones_sociales`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_causaciones_prestaciones_sociales` AS select concat(`jobdaily`.`job_causaciones_prestaciones_sociales`.`codigo_empresa`,_utf8'|',`jobdaily`.`job_causaciones_prestaciones_sociales`.`fecha_liquidacion`,_utf8'|',`jobdaily`.`job_causaciones_prestaciones_sociales`.`documento_identidad_empleado`,_utf8'|',`jobdaily`.`job_causaciones_prestaciones_sociales`.`codigo_transaccion_contable`) AS `id`,`jobdaily`.`job_empresas`.`razon_social` AS `EMPRESA`,`jobdaily`.`job_causaciones_prestaciones_sociales`.`fecha_liquidacion` AS `FECHA_LIQUIDACION` from (`jobdaily`.`job_causaciones_prestaciones_sociales` join `jobdaily`.`job_empresas`) where (`jobdaily`.`job_causaciones_prestaciones_sociales`.`codigo_empresa` = `jobdaily`.`job_empresas`.`codigo`) group by `jobdaily`.`job_causaciones_prestaciones_sociales`.`codigo_empresa`,`jobdaily`.`job_causaciones_prestaciones_sociales`.`fecha_liquidacion`;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_conceptos_contabilizacion`
-- 

DROP VIEW IF EXISTS `job_buscador_conceptos_contabilizacion`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_conceptos_contabilizacion` AS select `ccp`.`codigo` AS `id`,`ccp`.`descripcion` AS `DESCRIPCION`,if((`ccp`.`regimen_ventas_empresa` = 1),_utf8'Regimen comun',_utf8'Regimen simplificado') AS `REGIMEN_VENTAS`,if((`ccp`.`regimen_persona` = 1),_utf8'Regimen comun',_utf8'Regimen simplificado') AS `REGIMEN_PERSONA` from `jobdaily`.`job_conceptos_contabilizacion_compras` `CCP`;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_conceptos_devolucion_compras`
-- 

DROP VIEW IF EXISTS `job_buscador_conceptos_devolucion_compras`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_conceptos_devolucion_compras` AS select `cdc`.`codigo` AS `id`,`cdc`.`codigo` AS `codigo`,`cdc`.`descripcion` AS `DESCRIPCION`,if((`cdc`.`regimen_ventas_empresa` = 1),_utf8'Regimen comun',_utf8'Regimen simplificado') AS `REGIMEN_VENTAS`,if((`cdc`.`regimen_persona` = 1),_utf8'Regimen comun',_utf8'Regimen simplificado') AS `REGIMEN_PERSONA` from `jobdaily`.`job_conceptos_devolucion_compras` `CDC`;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_conceptos_dian`
-- 

DROP VIEW IF EXISTS `job_buscador_conceptos_dian`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_conceptos_dian` AS select `jobdaily`.`job_conceptos_dian`.`codigo` AS `id`,`jobdaily`.`job_conceptos_dian`.`codigo` AS `codigo`,`jobdaily`.`job_conceptos_dian`.`descripcion` AS `descripcion` from `jobdaily`.`job_conceptos_dian` where (`jobdaily`.`job_conceptos_dian`.`codigo` > 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_conceptos_prestamos`
-- 

DROP VIEW IF EXISTS `job_buscador_conceptos_prestamos`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_conceptos_prestamos` AS select `jobdaily`.`job_conceptos_prestamos`.`codigo` AS `id`,`jobdaily`.`job_conceptos_prestamos`.`codigo` AS `CODIGO`,`jobdaily`.`job_conceptos_prestamos`.`descripcion` AS `DESCRIPCION` from `jobdaily`.`job_conceptos_prestamos` where (`jobdaily`.`job_conceptos_prestamos`.`codigo` > 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_conexiones`
-- 

DROP VIEW IF EXISTS `job_buscador_conexiones`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_conexiones` AS select concat(`jobdaily`.`job_conexiones`.`codigo_sucursal`,_utf8'|',`jobdaily`.`job_conexiones`.`codigo_usuario`,_utf8'|',`jobdaily`.`job_conexiones`.`fecha`) AS `id`,date_format(`jobdaily`.`job_conexiones`.`fecha`,_utf8'%Y/%m/%d') AS `fecha`,date_format(`jobdaily`.`job_conexiones`.`fecha`,_utf8'%T') AS `hora`,`jobdaily`.`job_usuarios`.`nombre` AS `USUARIO`,`jobdaily`.`job_conexiones`.`ip` AS `IP`,`jobdaily`.`job_conexiones`.`proxy` AS `PROXY` from ((`jobdaily`.`job_usuarios` join `jobdaily`.`job_sucursales`) join `jobdaily`.`job_conexiones`) where ((`jobdaily`.`job_conexiones`.`codigo_usuario` = `jobdaily`.`job_usuarios`.`codigo`) and (`jobdaily`.`job_conexiones`.`codigo_sucursal` = `jobdaily`.`job_sucursales`.`codigo`));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_control_prestamos_empleados`
-- 

DROP VIEW IF EXISTS `job_buscador_control_prestamos_empleados`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_control_prestamos_empleados` AS select concat(`jobdaily`.`job_control_prestamos_terceros`.`codigo_empresa`,_utf8'|',`jobdaily`.`job_control_prestamos_terceros`.`documento_identidad_empleado`,_utf8'|',`jobdaily`.`job_control_prestamos_terceros`.`obligacion`) AS `id`,`jobdaily`.`job_sucursales`.`codigo` AS `id_sucursal`,if(((`jobdaily`.`job_terceros`.`tipo_persona` = _latin1'1') or (`jobdaily`.`job_terceros`.`tipo_persona` = _latin1'4')),concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' ',if(((`jobdaily`.`job_terceros`.`segundo_nombre` is not null) and (`jobdaily`.`job_terceros`.`segundo_nombre` <> _latin1'')),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1''),`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' ',if(((`jobdaily`.`job_terceros`.`segundo_apellido` is not null) and (`jobdaily`.`job_terceros`.`segundo_apellido` <> _latin1'')),`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1'')),`jobdaily`.`job_terceros`.`razon_social`) AS `EMPLEADO`,`jobdaily`.`job_control_prestamos_terceros`.`obligacion` AS `OBLIGACION` from ((`jobdaily`.`job_terceros` join `jobdaily`.`job_control_prestamos_terceros`) join `jobdaily`.`job_sucursales`) where ((`jobdaily`.`job_control_prestamos_terceros`.`documento_identidad_empleado` = `jobdaily`.`job_terceros`.`documento_identidad`) and (`jobdaily`.`job_control_prestamos_terceros`.`codigo_sucursal` = `jobdaily`.`job_sucursales`.`codigo`));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_corregimientos`
-- 

DROP VIEW IF EXISTS `job_buscador_corregimientos`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_corregimientos` AS select concat(`jobdaily`.`job_localidades`.`codigo_iso`,_latin1'|',`jobdaily`.`job_localidades`.`codigo_dane_departamento`,_latin1'|',`jobdaily`.`job_localidades`.`codigo_dane_municipio`,_latin1'|',`jobdaily`.`job_localidades`.`tipo`,_latin1'|',`jobdaily`.`job_localidades`.`codigo_dane_localidad`) AS `id`,`jobdaily`.`job_localidades`.`nombre` AS `nombre`,`jobdaily`.`job_localidades`.`codigo_dane_localidad` AS `codigo_localidad`,`jobdaily`.`job_localidades`.`codigo_interno` AS `codigo_interno`,`jobdaily`.`job_localidades`.`estrato` AS `estrato`,`jobdaily`.`job_localidades`.`comuna` AS `comuna`,`jobdaily`.`job_municipios`.`nombre` AS `municipio`,`jobdaily`.`job_departamentos`.`nombre` AS `departamento`,`jobdaily`.`job_paises`.`nombre` AS `pais` from (((`jobdaily`.`job_localidades` join `jobdaily`.`job_municipios`) join `jobdaily`.`job_departamentos`) join `jobdaily`.`job_paises`) where ((`jobdaily`.`job_localidades`.`codigo_iso` = `jobdaily`.`job_municipios`.`codigo_iso`) and (`jobdaily`.`job_localidades`.`codigo_dane_departamento` = `jobdaily`.`job_municipios`.`codigo_dane_departamento`) and (`jobdaily`.`job_localidades`.`codigo_dane_municipio` = `jobdaily`.`job_municipios`.`codigo_dane_municipio`) and (`jobdaily`.`job_localidades`.`codigo_iso` = `jobdaily`.`job_departamentos`.`codigo_iso`) and (`jobdaily`.`job_localidades`.`codigo_dane_departamento` = `jobdaily`.`job_departamentos`.`codigo_dane_departamento`) and (`jobdaily`.`job_localidades`.`codigo_iso` = `jobdaily`.`job_paises`.`codigo_iso`) and (`jobdaily`.`job_localidades`.`tipo` = _latin1'C')) order by `jobdaily`.`job_localidades`.`nombre`;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_cuentas_bancarias`
-- 

DROP VIEW IF EXISTS `job_buscador_cuentas_bancarias`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_cuentas_bancarias` AS select concat(`jobdaily`.`job_cuentas_bancarias`.`codigo_sucursal`,_utf8'|',`jobdaily`.`job_cuentas_bancarias`.`codigo_tipo_documento`,_utf8'|',`jobdaily`.`job_cuentas_bancarias`.`codigo_sucursal_banco`,_utf8'|',`jobdaily`.`job_cuentas_bancarias`.`codigo_iso`,_utf8'|',`jobdaily`.`job_cuentas_bancarias`.`codigo_dane_departamento`,_utf8'|',`jobdaily`.`job_cuentas_bancarias`.`codigo_dane_municipio`,_utf8'|',`jobdaily`.`job_cuentas_bancarias`.`codigo_banco`,_utf8'|',`jobdaily`.`job_cuentas_bancarias`.`numero`) AS `id`,concat(`jobdaily`.`job_cuentas_bancarias`.`codigo_empresa_auxiliar`,_utf8'|',`jobdaily`.`job_cuentas_bancarias`.`codigo_anexo_contable`,_utf8'|',`jobdaily`.`job_cuentas_bancarias`.`codigo_auxiliar_contable`) AS `id_auxiliar`,`jobdaily`.`job_sucursales`.`codigo` AS `id_sucursal`,`jobdaily`.`job_tipos_documentos`.`codigo` AS `id_documento`,`jobdaily`.`job_sucursales`.`nombre` AS `SUCURSAL`,`jobdaily`.`job_bancos`.`descripcion` AS `BANCO`,`jobdaily`.`job_sucursales_bancos`.`nombre_sucursal` AS `SUCURSALES_BANCOS`,`jobdaily`.`job_cuentas_bancarias`.`numero` AS `NUMERO`,`jobdaily`.`job_tipos_documentos`.`descripcion` AS `TIPO_DOCUMENTO`,`jobdaily`.`job_cuentas_bancarias`.`codigo_plan_contable` AS `codigo_plan_contable` from ((((`jobdaily`.`job_cuentas_bancarias` join `jobdaily`.`job_bancos`) join `jobdaily`.`job_sucursales_bancos`) join `jobdaily`.`job_tipos_documentos`) join `jobdaily`.`job_sucursales`) where ((`jobdaily`.`job_cuentas_bancarias`.`codigo_banco` = `jobdaily`.`job_bancos`.`codigo`) and (`jobdaily`.`job_sucursales`.`codigo` = `jobdaily`.`job_cuentas_bancarias`.`codigo_sucursal`) and (`jobdaily`.`job_cuentas_bancarias`.`codigo_sucursal_banco` = `jobdaily`.`job_sucursales_bancos`.`codigo`) and (`jobdaily`.`job_cuentas_bancarias`.`codigo_banco` = `jobdaily`.`job_sucursales_bancos`.`codigo_banco`) and (`jobdaily`.`job_tipos_documentos`.`codigo` = `jobdaily`.`job_cuentas_bancarias`.`codigo_tipo_documento`) and (`jobdaily`.`job_cuentas_bancarias`.`numero` <> _latin1''));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_departamentos`
-- 

DROP VIEW IF EXISTS `job_buscador_departamentos`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_departamentos` AS select concat(`jobdaily`.`job_departamentos`.`codigo_iso`,_latin1'|',`jobdaily`.`job_departamentos`.`codigo_dane_departamento`) AS `id`,`jobdaily`.`job_departamentos`.`codigo_dane_departamento` AS `codigo_dane`,`jobdaily`.`job_departamentos`.`codigo_interno` AS `codigo_interno`,`jobdaily`.`job_departamentos`.`nombre` AS `nombre`,`jobdaily`.`job_paises`.`nombre` AS `pais` from (`jobdaily`.`job_departamentos` join `jobdaily`.`job_paises`) where ((`jobdaily`.`job_departamentos`.`codigo_iso` = `jobdaily`.`job_paises`.`codigo_iso`) and (`jobdaily`.`job_departamentos`.`codigo_dane_departamento` <> _latin1''));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_departamentos_empresa`
-- 

DROP VIEW IF EXISTS `job_buscador_departamentos_empresa`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_departamentos_empresa` AS select `jobdaily`.`job_departamentos_empresa`.`codigo` AS `id`,`jobdaily`.`job_departamentos_empresa`.`codigo` AS `CODIGO_DEPARTAMENTO`,`jobdaily`.`job_departamentos_empresa`.`nombre` AS `NOMBRE_DEPARTAMENTO`,concat(format(`jobdaily`.`job_departamentos_empresa`.`riesgos_profesionales`,2),_utf8'%') AS `RIESGOS_PROFESIONALES` from `jobdaily`.`job_departamentos_empresa` where (`jobdaily`.`job_departamentos_empresa`.`codigo` > 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_deportes`
-- 

DROP VIEW IF EXISTS `job_buscador_deportes`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_deportes` AS select `jobdaily`.`job_deportes`.`codigo` AS `id`,`jobdaily`.`job_deportes`.`codigo` AS `CODIGO`,`jobdaily`.`job_deportes`.`descripcion` AS `DESCRIPCION` from `jobdaily`.`job_deportes` where (`jobdaily`.`job_deportes`.`codigo` > 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_domingos_festivos`
-- 

DROP VIEW IF EXISTS `job_buscador_domingos_festivos`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_domingos_festivos` AS select `jobdaily`.`job_domingos_festivos`.`anio` AS `id`,`jobdaily`.`job_domingos_festivos`.`anio` AS `FECHA` from `jobdaily`.`job_domingos_festivos` group by `jobdaily`.`job_domingos_festivos`.`anio`;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_empresas`
-- 

DROP VIEW IF EXISTS `job_buscador_empresas`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_empresas` AS select `jobdaily`.`job_empresas`.`codigo` AS `id`,`jobdaily`.`job_empresas`.`codigo` AS `codigo`,`jobdaily`.`job_empresas`.`razon_social` AS `razon_social`,`jobdaily`.`job_empresas`.`nombre_corto` AS `nombre_corto`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),`jobdaily`.`job_terceros`.`primer_nombre`,_latin1''),_latin1' ',if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1''),_latin1' ',if((`jobdaily`.`job_terceros`.`primer_apellido` is not null),`jobdaily`.`job_terceros`.`primer_apellido`,_latin1''),_latin1' ',if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1''),_latin1' ',if((`jobdaily`.`job_terceros`.`razon_social` is not null),`jobdaily`.`job_terceros`.`razon_social`,_latin1'')) AS `nombre_completo` from (`jobdaily`.`job_empresas` join `jobdaily`.`job_terceros`) where ((`jobdaily`.`job_empresas`.`documento_identidad_tercero` = `jobdaily`.`job_terceros`.`documento_identidad`) and (`jobdaily`.`job_empresas`.`codigo` <> 0));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_entidades_parafiscales`
-- 

DROP VIEW IF EXISTS `job_buscador_entidades_parafiscales`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_entidades_parafiscales` AS select `jobdaily`.`job_entidades_parafiscales`.`codigo` AS `id`,`jobdaily`.`job_terceros`.`documento_identidad` AS `DOCUMENTO_TERCERO`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),concat(concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`primer_apellido` is not null),concat(`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),concat(`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1''),_latin1'')),`jobdaily`.`job_terceros`.`razon_social`)) AS `NOMBRE_TERCERO` from (`jobdaily`.`job_entidades_parafiscales` join `jobdaily`.`job_terceros`) where ((`jobdaily`.`job_entidades_parafiscales`.`documento_identidad_tercero` = `jobdaily`.`job_terceros`.`documento_identidad`) and (`jobdaily`.`job_entidades_parafiscales`.`codigo` <> 0));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_escolaridad`
-- 

DROP VIEW IF EXISTS `job_buscador_escolaridad`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_escolaridad` AS select `jobdaily`.`job_escolaridad`.`codigo` AS `id`,`jobdaily`.`job_escolaridad`.`codigo` AS `CODIGO`,`jobdaily`.`job_escolaridad`.`descripcion` AS `DESCRIPCION` from `jobdaily`.`job_escolaridad` where (`jobdaily`.`job_escolaridad`.`codigo` > 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_estado_mercancia`
-- 

DROP VIEW IF EXISTS `job_buscador_estado_mercancia`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_estado_mercancia` AS select `jobdaily`.`job_estado_mercancia`.`codigo` AS `id`,`jobdaily`.`job_estado_mercancia`.`codigo` AS `codigo`,`jobdaily`.`job_estado_mercancia`.`descripcion` AS `descripcion` from `jobdaily`.`job_estado_mercancia`;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_estructura_grupos`
-- 

DROP VIEW IF EXISTS `job_buscador_estructura_grupos`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_estructura_grupos` AS select `jobdaily`.`job_estructura_grupos`.`codigo` AS `id`,`jobdaily`.`job_estructura_grupos`.`codigo` AS `CODIGO`,`jobdaily`.`job_estructura_grupos`.`descripcion` AS `DESCRIPCION` from `jobdaily`.`job_estructura_grupos`;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_fechas_planillas`
-- 

DROP VIEW IF EXISTS `job_buscador_fechas_planillas`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_fechas_planillas` AS select concat(`jobdaily`.`job_fechas_planillas`.`codigo_planilla`,_utf8'|',date_format(`jobdaily`.`job_fechas_planillas`.`fecha`,_utf8'%Y')) AS `id`,`jobdaily`.`job_planillas`.`descripcion` AS `planilla`,`jobdaily`.`job_fechas_planillas`.`codigo_planilla` AS `codigo_planilla`,date_format(`jobdaily`.`job_fechas_planillas`.`fecha`,_utf8'%Y') AS `ano` from (`jobdaily`.`job_fechas_planillas` join `jobdaily`.`job_planillas`) where (`jobdaily`.`job_fechas_planillas`.`codigo_planilla` = `jobdaily`.`job_planillas`.`codigo`) group by `jobdaily`.`job_fechas_planillas`.`codigo_planilla`,date_format(`jobdaily`.`job_fechas_planillas`.`fecha`,_utf8'%Y');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_formatos_dian`
-- 

DROP VIEW IF EXISTS `job_buscador_formatos_dian`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_formatos_dian` AS select `jobdaily`.`job_formatos_dian`.`codigo` AS `id`,`jobdaily`.`job_formatos_dian`.`codigo` AS `codigo`,`jobdaily`.`job_formatos_dian`.`descripcion` AS `descripcion` from `jobdaily`.`job_formatos_dian` where (`jobdaily`.`job_formatos_dian`.`codigo` > 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_gastos_prestaciones_sociales`
-- 

DROP VIEW IF EXISTS `job_buscador_gastos_prestaciones_sociales`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_gastos_prestaciones_sociales` AS select `jobdaily`.`job_gastos_prestaciones_sociales`.`codigo` AS `id`,`jobdaily`.`job_gastos_prestaciones_sociales`.`codigo` AS `CODIGO`,`jobdaily`.`job_gastos_prestaciones_sociales`.`descripcion` AS `DESCRIPCION` from `jobdaily`.`job_gastos_prestaciones_sociales` where (`jobdaily`.`job_gastos_prestaciones_sociales`.`codigo` <> 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_grupos`
-- 

DROP VIEW IF EXISTS `job_buscador_grupos`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_grupos` AS select `jobdaily`.`job_grupos`.`codigo` AS `id`,`jobdaily`.`job_grupos`.`codigo` AS `codigo`,`jobdaily`.`job_grupos`.`descripcion` AS `descripcion`,`jobdaily`.`job_grupos`.`orden` AS `orden` from `jobdaily`.`job_grupos` where (`jobdaily`.`job_grupos`.`codigo` <> 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_idiomas`
-- 

DROP VIEW IF EXISTS `job_buscador_idiomas`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_idiomas` AS select `jobdaily`.`job_idiomas`.`codigo` AS `id`,`jobdaily`.`job_idiomas`.`codigo` AS `CODIGO`,`jobdaily`.`job_idiomas`.`descripcion` AS `DESCRIPCION` from `jobdaily`.`job_idiomas` where (`jobdaily`.`job_idiomas`.`codigo` > 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_ingreso_empleados`
-- 

DROP VIEW IF EXISTS `job_buscador_ingreso_empleados`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_ingreso_empleados` AS select concat(`jobdaily`.`job_ingreso_empleados`.`codigo_empresa`,_utf8'|',`jobdaily`.`job_ingreso_empleados`.`documento_identidad_empleado`,_utf8'|',`jobdaily`.`job_ingreso_empleados`.`fecha_ingreso`) AS `id`,`jobdaily`.`job_terceros`.`documento_identidad` AS `NUMERO_DOCUMENTO`,concat(`jobdaily`.`job_sucursal_contrato_empleados`.`codigo_sucursal`,_utf8'|',`jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso_sucursal`) AS `codigo_sucursal`,`jobdaily`.`job_terceros`.`primer_nombre` AS `primer_nombre`,`jobdaily`.`job_terceros`.`segundo_nombre` AS `segundo_nombre`,`jobdaily`.`job_terceros`.`primer_apellido` AS `primer_apellido`,`jobdaily`.`job_terceros`.`segundo_apellido` AS `segundo_apellido`,`jobdaily`.`job_terceros`.`razon_social` AS `razon_social`,if(((`jobdaily`.`job_terceros`.`tipo_persona` = _latin1'1') or (`jobdaily`.`job_terceros`.`tipo_persona` = _latin1'4')),concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' ',if(((`jobdaily`.`job_terceros`.`segundo_nombre` is not null) and (`jobdaily`.`job_terceros`.`segundo_nombre` <> _latin1'')),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1''),`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' ',if(((`jobdaily`.`job_terceros`.`segundo_apellido` is not null) and (`jobdaily`.`job_terceros`.`segundo_apellido` <> _latin1'')),`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1'')),`jobdaily`.`job_terceros`.`razon_social`) AS `NOMBRE_COMPLETO` from ((`jobdaily`.`job_terceros` join `jobdaily`.`job_ingreso_empleados`) join `jobdaily`.`job_sucursal_contrato_empleados`) where ((`jobdaily`.`job_ingreso_empleados`.`documento_identidad_empleado` = `jobdaily`.`job_terceros`.`documento_identidad`) and (`jobdaily`.`job_ingreso_empleados`.`documento_identidad_empleado` > 0) and (`jobdaily`.`job_ingreso_empleados`.`documento_identidad_empleado` = `jobdaily`.`job_sucursal_contrato_empleados`.`documento_identidad_empleado`) and (`jobdaily`.`job_ingreso_empleados`.`codigo_empresa` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_empresa`) and (`jobdaily`.`job_ingreso_empleados`.`fecha_ingreso` = `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso`));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_marcas`
-- 

DROP VIEW IF EXISTS `job_buscador_marcas`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_marcas` AS select `jobdaily`.`job_marcas`.`codigo` AS `id`,`jobdaily`.`job_marcas`.`codigo` AS `codigo`,`jobdaily`.`job_marcas`.`descripcion` AS `descripcion`,`jobdaily`.`job_marcas`.`orden` AS `orden` from `jobdaily`.`job_marcas` where (`jobdaily`.`job_marcas`.`codigo` <> 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_motivos_incapacidad`
-- 

DROP VIEW IF EXISTS `job_buscador_motivos_incapacidad`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_motivos_incapacidad` AS select `jobdaily`.`job_motivos_incapacidad`.`codigo` AS `id`,`jobdaily`.`job_motivos_incapacidad`.`codigo` AS `CODIGO`,`jobdaily`.`job_motivos_incapacidad`.`descripcion` AS `DESCRIPCION` from `jobdaily`.`job_motivos_incapacidad`;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_motivos_retiro`
-- 

DROP VIEW IF EXISTS `job_buscador_motivos_retiro`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_motivos_retiro` AS select `jobdaily`.`job_motivos_retiro`.`codigo` AS `id`,`jobdaily`.`job_motivos_retiro`.`codigo` AS `CODIGO`,`jobdaily`.`job_motivos_retiro`.`descripcion` AS `DESCRIPCION` from `jobdaily`.`job_motivos_retiro` where (`jobdaily`.`job_motivos_retiro`.`codigo` > 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_movimiento_liquidacion_vacaciones`
-- 

DROP VIEW IF EXISTS `job_buscador_movimiento_liquidacion_vacaciones`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_movimiento_liquidacion_vacaciones` AS select concat(`jobdaily`.`job_movimiento_liquidacion_vacaciones`.`codigo_sucursal`,_utf8'|',`jobdaily`.`job_movimiento_liquidacion_vacaciones`.`documento_identidad_empleado`,_utf8'|',`jobdaily`.`job_movimiento_liquidacion_vacaciones`.`fecha_inicio_tiempo`) AS `id`,`jobdaily`.`job_sucursales`.`codigo` AS `id_sucursal`,`jobdaily`.`job_sucursales`.`nombre` AS `ALMACEN`,if(((`jobdaily`.`job_terceros`.`tipo_persona` = _latin1'1') or (`jobdaily`.`job_terceros`.`tipo_persona` = _latin1'4')),concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' ',if(((`jobdaily`.`job_terceros`.`segundo_nombre` is not null) and (`jobdaily`.`job_terceros`.`segundo_nombre` <> _latin1'')),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1''),`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' ',if(((`jobdaily`.`job_terceros`.`segundo_apellido` is not null) and (`jobdaily`.`job_terceros`.`segundo_apellido` <> _latin1'')),`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1'')),`jobdaily`.`job_terceros`.`razon_social`) AS `EMPLEADO`,date_format(`jobdaily`.`job_movimiento_liquidacion_vacaciones`.`fecha_inicio_tiempo`,_utf8'%Y-%m-%d') AS `FECHA_INCAPACIDAD_TIEMPO` from ((((`jobdaily`.`job_terceros` join `jobdaily`.`job_aspirantes`) join `jobdaily`.`job_sucursal_contrato_empleados`) join `jobdaily`.`job_movimiento_liquidacion_vacaciones`) join `jobdaily`.`job_sucursales`) where ((`jobdaily`.`job_sucursal_contrato_empleados`.`documento_identidad_empleado` = `jobdaily`.`job_aspirantes`.`documento_identidad`) and (`jobdaily`.`job_aspirantes`.`documento_identidad` = `jobdaily`.`job_terceros`.`documento_identidad`) and (`jobdaily`.`job_movimiento_liquidacion_vacaciones`.`documento_identidad_empleado` = `jobdaily`.`job_sucursal_contrato_empleados`.`documento_identidad_empleado`) and (`jobdaily`.`job_movimiento_liquidacion_vacaciones`.`codigo_sucursal` = `jobdaily`.`job_sucursales`.`codigo`)) group by `jobdaily`.`job_movimiento_liquidacion_vacaciones`.`codigo_sucursal`,`jobdaily`.`job_movimiento_liquidacion_vacaciones`.`documento_identidad_empleado`,`jobdaily`.`job_movimiento_liquidacion_vacaciones`.`fecha_inicio_tiempo`;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_movimiento_novedades_manuales`
-- 

DROP VIEW IF EXISTS `job_buscador_movimiento_novedades_manuales`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_movimiento_novedades_manuales` AS select concat(`jobdaily`.`job_movimiento_novedades_manuales`.`ano_generacion`,_utf8'|',`jobdaily`.`job_movimiento_novedades_manuales`.`mes_generacion`,_utf8'|',`jobdaily`.`job_movimiento_novedades_manuales`.`codigo_planilla`,_utf8'|',`jobdaily`.`job_movimiento_novedades_manuales`.`periodo_pago`,_utf8'|',`jobdaily`.`job_movimiento_novedades_manuales`.`documento_identidad_empleado`,_utf8'|',`jobdaily`.`job_movimiento_novedades_manuales`.`consecutivo`,_utf8'|',`jobdaily`.`job_movimiento_novedades_manuales`.`codigo_sucursal`,_utf8'|',`jobdaily`.`job_movimiento_novedades_manuales`.`fecha_pago_planilla`) AS `id`,`jobdaily`.`job_sucursales`.`codigo` AS `id_sucursal`,`jobdaily`.`job_sucursales`.`nombre` AS `SUCURSAL`,if(((`jobdaily`.`job_terceros`.`tipo_persona` = _latin1'1') or (`jobdaily`.`job_terceros`.`tipo_persona` = _latin1'4')),concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' ',if(((`jobdaily`.`job_terceros`.`segundo_nombre` is not null) and (`jobdaily`.`job_terceros`.`segundo_nombre` <> _latin1'')),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1''),`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' ',if(((`jobdaily`.`job_terceros`.`segundo_apellido` is not null) and (`jobdaily`.`job_terceros`.`segundo_apellido` <> _latin1'')),`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1'')),`jobdaily`.`job_terceros`.`razon_social`) AS `EMPLEADO`,`jobdaily`.`job_transacciones_contables_empleado`.`nombre` AS `TRANSACCION_CONTABLE`,`jobdaily`.`job_movimiento_novedades_manuales`.`valor_movimiento` AS `VALOR` from (((`jobdaily`.`job_terceros` join `jobdaily`.`job_movimiento_novedades_manuales`) join `jobdaily`.`job_sucursales`) join `jobdaily`.`job_transacciones_contables_empleado`) where ((`jobdaily`.`job_movimiento_novedades_manuales`.`documento_identidad_empleado` = `jobdaily`.`job_terceros`.`documento_identidad`) and (`jobdaily`.`job_movimiento_novedades_manuales`.`codigo_sucursal` = `jobdaily`.`job_sucursales`.`codigo`) and (`jobdaily`.`job_movimiento_novedades_manuales`.`codigo_transaccion_contable` = `jobdaily`.`job_transacciones_contables_empleado`.`codigo`)) group by `jobdaily`.`job_movimiento_novedades_manuales`.`documento_identidad_empleado`,`jobdaily`.`job_movimiento_novedades_manuales`.`fecha_generacion`;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_movimiento_tiempos_laborados`
-- 

DROP VIEW IF EXISTS `job_buscador_movimiento_tiempos_laborados`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_movimiento_tiempos_laborados` AS select concat(`jobdaily`.`job_movimiento_tiempos_laborados`.`fecha_inicio`,_utf8'|',`jobdaily`.`job_movimiento_tiempos_laborados`.`documento_identidad_empleado`) AS `id`,`jobdaily`.`job_sucursales`.`codigo` AS `id_sucursal`,`jobdaily`.`job_sucursales`.`nombre` AS `sucursal`,if(((`jobdaily`.`job_terceros`.`tipo_persona` = _latin1'1') or (`jobdaily`.`job_terceros`.`tipo_persona` = _latin1'4')),concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' ',if(((`jobdaily`.`job_terceros`.`segundo_nombre` is not null) and (`jobdaily`.`job_terceros`.`segundo_nombre` <> _latin1'')),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1''),`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' ',if(((`jobdaily`.`job_terceros`.`segundo_apellido` is not null) and (`jobdaily`.`job_terceros`.`segundo_apellido` <> _latin1'')),`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1'')),`jobdaily`.`job_terceros`.`razon_social`) AS `empleado`,`jobdaily`.`job_transacciones_contables_empleado`.`nombre` AS `transaccion_contable`,`jobdaily`.`job_transacciones_tiempo`.`nombre` AS `transaccion`,`jobdaily`.`job_movimiento_tiempos_laborados`.`fecha_inicio` AS `fecha_inicio`,`jobdaily`.`job_movimiento_tiempos_laborados`.`hora_inicio` AS `hora_inicio`,`jobdaily`.`job_movimiento_tiempos_laborados`.`fecha_fin` AS `fecha_fin`,`jobdaily`.`job_movimiento_tiempos_laborados`.`hora_fin` AS `hora_fin`,sec_to_time(sum((`jobdaily`.`job_movimiento_tiempos_laborados`.`cantidad_minutos` * 60))) AS `cantidad` from ((((`jobdaily`.`job_terceros` join `jobdaily`.`job_movimiento_tiempos_laborados`) join `jobdaily`.`job_sucursales`) join `jobdaily`.`job_transacciones_contables_empleado`) join `jobdaily`.`job_transacciones_tiempo`) where ((`jobdaily`.`job_movimiento_tiempos_laborados`.`documento_identidad_empleado` = `jobdaily`.`job_terceros`.`documento_identidad`) and (`jobdaily`.`job_movimiento_tiempos_laborados`.`codigo_sucursal` = `jobdaily`.`job_sucursales`.`codigo`) and (`jobdaily`.`job_movimiento_tiempos_laborados`.`codigo_transaccion_contable` = `jobdaily`.`job_transacciones_contables_empleado`.`codigo`) and (`jobdaily`.`job_movimiento_tiempos_laborados`.`codigo_transaccion_tiempo` = `jobdaily`.`job_transacciones_tiempo`.`codigo`)) group by `jobdaily`.`job_movimiento_tiempos_laborados`.`documento_identidad_empleado`,`jobdaily`.`job_movimiento_tiempos_laborados`.`fecha_inicio`;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_movimiento_tiempos_no_laborados_dias`
-- 

DROP VIEW IF EXISTS `job_buscador_movimiento_tiempos_no_laborados_dias`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_movimiento_tiempos_no_laborados_dias` AS select concat(`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`documento_identidad_empleado`,_latin1'|',`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`fecha_inicio_tiempo`,_latin1'|',`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`codigo_sucursal`) AS `id`,`jobdaily`.`job_sucursales`.`codigo` AS `id_sucursal`,`jobdaily`.`job_sucursales`.`nombre` AS `ALMACEN`,if(((`jobdaily`.`job_terceros`.`tipo_persona` = _latin1'1') or (`jobdaily`.`job_terceros`.`tipo_persona` = _latin1'4')),concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' ',if(((`jobdaily`.`job_terceros`.`segundo_nombre` is not null) and (`jobdaily`.`job_terceros`.`segundo_nombre` <> _latin1'')),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1''),`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' ',if(((`jobdaily`.`job_terceros`.`segundo_apellido` is not null) and (`jobdaily`.`job_terceros`.`segundo_apellido` <> _latin1'')),`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1'')),`jobdaily`.`job_terceros`.`razon_social`) AS `EMPLEADO`,date_format(`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`fecha_inicio_tiempo`,_latin1'%Y-%m-%d') AS `FECHA_INCAPACIDAD_TIEMPO` from ((((`jobdaily`.`job_terceros` join `jobdaily`.`job_aspirantes`) join `jobdaily`.`job_sucursal_contrato_empleados`) join `jobdaily`.`job_movimiento_tiempos_no_laborados_dias`) join `jobdaily`.`job_sucursales`) where ((`jobdaily`.`job_sucursal_contrato_empleados`.`documento_identidad_empleado` = `jobdaily`.`job_aspirantes`.`documento_identidad`) and (`jobdaily`.`job_aspirantes`.`documento_identidad` = `jobdaily`.`job_terceros`.`documento_identidad`) and (`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`documento_identidad_empleado` = `jobdaily`.`job_sucursal_contrato_empleados`.`documento_identidad_empleado`) and (`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`codigo_sucursal` = `jobdaily`.`job_sucursales`.`codigo`)) group by `jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`fecha_inicio_tiempo`;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_municipios`
-- 

DROP VIEW IF EXISTS `job_buscador_municipios`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_municipios` AS select concat(`jobdaily`.`job_departamentos`.`codigo_iso`,_latin1'|',`jobdaily`.`job_municipios`.`codigo_dane_departamento`,_latin1'|',`jobdaily`.`job_municipios`.`codigo_dane_municipio`) AS `id`,concat(`jobdaily`.`job_departamentos`.`codigo_dane_departamento`,`jobdaily`.`job_municipios`.`codigo_dane_municipio`) AS `codigo_dane`,`jobdaily`.`job_municipios`.`codigo_interno` AS `codigo_interno`,`jobdaily`.`job_municipios`.`nombre` AS `nombre`,`jobdaily`.`job_departamentos`.`nombre` AS `departamento`,`jobdaily`.`job_paises`.`nombre` AS `pais` from ((`jobdaily`.`job_municipios` join `jobdaily`.`job_departamentos`) join `jobdaily`.`job_paises`) where ((`jobdaily`.`job_municipios`.`codigo_dane_departamento` = `jobdaily`.`job_departamentos`.`codigo_dane_departamento`) and (`jobdaily`.`job_departamentos`.`codigo_iso` = `jobdaily`.`job_paises`.`codigo_iso`) and (`jobdaily`.`job_municipios`.`codigo_dane_municipio` <> _latin1'')) order by `jobdaily`.`job_municipios`.`nombre`;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_nomina_migracion`
-- 

DROP VIEW IF EXISTS `job_buscador_nomina_migracion`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_nomina_migracion` AS select concat(`jobdaily`.`job_movimientos_nomina_migracion`.`ano_generacion`,_utf8'|',`jobdaily`.`job_movimientos_nomina_migracion`.`mes_generacion`,_utf8'|',`jobdaily`.`job_movimientos_nomina_migracion`.`codigo_planilla`,_utf8'|',`jobdaily`.`job_movimientos_nomina_migracion`.`periodo_pago`,_utf8'|',`jobdaily`.`job_movimientos_nomina_migracion`.`codigo_transaccion_contable`,_utf8'|',`jobdaily`.`job_movimientos_nomina_migracion`.`consecutivo`) AS `id`,`jobdaily`.`job_movimientos_nomina_migracion`.`documento_identidad_empleado` AS `documento`,`job_menu_terceros`.`NOMBRE_COMPLETO` AS `empleado`,`jobdaily`.`job_transacciones_contables_empleado`.`descripcion` AS `transaccion_contable`,`jobdaily`.`job_movimientos_nomina_migracion`.`fecha_pago_planilla` AS `fecha_pago_planilla`,format(`jobdaily`.`job_movimientos_nomina_migracion`.`valor_movimiento`,0) AS `valor_movimiento` from ((`jobdaily`.`job_movimientos_nomina_migracion` join `jobdaily`.`job_transacciones_contables_empleado`) join `jobdaily`.`job_menu_terceros`) where ((`jobdaily`.`job_movimientos_nomina_migracion`.`codigo_transaccion_contable` = `jobdaily`.`job_transacciones_contables_empleado`.`codigo`) and (`jobdaily`.`job_movimientos_nomina_migracion`.`documento_identidad_empleado` = `job_menu_terceros`.`id`));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_paises`
-- 

DROP VIEW IF EXISTS `job_buscador_paises`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_paises` AS select `jobdaily`.`job_paises`.`codigo_iso` AS `id`,`jobdaily`.`job_paises`.`codigo_iso` AS `codigo_iso`,`jobdaily`.`job_paises`.`codigo_interno` AS `codigo_interno`,`jobdaily`.`job_paises`.`nombre` AS `nombre` from `jobdaily`.`job_paises` where (`jobdaily`.`job_paises`.`codigo_iso` <> _latin1'');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_perfiles`
-- 

DROP VIEW IF EXISTS `job_buscador_perfiles`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_perfiles` AS select `jobdaily`.`job_perfiles`.`id` AS `id`,`jobdaily`.`job_perfiles`.`codigo` AS `codigo`,`jobdaily`.`job_perfiles`.`nombre` AS `nombre` from `jobdaily`.`job_perfiles`;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_periodos_contables`
-- 

DROP VIEW IF EXISTS `job_buscador_periodos_contables`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_periodos_contables` AS select concat(`jobdaily`.`job_periodos_contables`.`codigo_sucursal`,_utf8'|',`jobdaily`.`job_periodos_contables`.`fecha_inicio`,_utf8'|',`jobdaily`.`job_periodos_contables`.`fecha_fin`) AS `id`,`jobdaily`.`job_sucursales`.`codigo` AS `id_sucursal`,`jobdaily`.`job_modulos`.`id` AS `id_modulo`,`jobdaily`.`job_sucursales`.`nombre` AS `SUCURSAL`,`jobdaily`.`job_modulos`.`id` AS `MODULO`,`jobdaily`.`job_periodos_contables`.`fecha_inicio` AS `FECHA_INICIO`,`jobdaily`.`job_periodos_contables`.`fecha_fin` AS `FECHA_FIN`,concat(_latin1'ESTADO_',`jobdaily`.`job_periodos_contables`.`estado`) AS `ESTADO` from ((`jobdaily`.`job_periodos_contables` join `jobdaily`.`job_sucursales`) join `jobdaily`.`job_modulos`) where ((`jobdaily`.`job_periodos_contables`.`codigo_sucursal` = `jobdaily`.`job_sucursales`.`codigo`) and (`jobdaily`.`job_periodos_contables`.`id_modulo` = `jobdaily`.`job_modulos`.`id`));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_plan_contable`
-- 

DROP VIEW IF EXISTS `job_buscador_plan_contable`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_plan_contable` AS select `jobdaily`.`job_plan_contable`.`codigo_contable` AS `id`,`jobdaily`.`job_plan_contable`.`codigo_contable` AS `codigo_contable`,`jobdaily`.`job_plan_contable`.`descripcion` AS `descripcion`,`jobdaily`.`job_plan_contable`.`codigo_contable_padre` AS `cuenta_padre` from `jobdaily`.`job_plan_contable` where (`jobdaily`.`job_plan_contable`.`codigo_contable` <> _latin1'');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_planillas`
-- 

DROP VIEW IF EXISTS `job_buscador_planillas`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_planillas` AS select `jobdaily`.`job_planillas`.`codigo` AS `id`,`jobdaily`.`job_planillas`.`codigo` AS `CODIGO`,`jobdaily`.`job_planillas`.`descripcion` AS `DESCRIPCION` from `jobdaily`.`job_planillas` where (`jobdaily`.`job_planillas`.`codigo` > 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_plazos_pago_proveedores`
-- 

DROP VIEW IF EXISTS `job_buscador_plazos_pago_proveedores`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_plazos_pago_proveedores` AS select `jobdaily`.`job_plazos_pago_proveedores`.`codigo` AS `id`,`jobdaily`.`job_plazos_pago_proveedores`.`nombre` AS `nombre`,if((`jobdaily`.`job_plazos_pago_proveedores`.`inicial` = _latin1'0'),1,`jobdaily`.`job_plazos_pago_proveedores`.`inicial`) AS `inicial`,if((`jobdaily`.`job_plazos_pago_proveedores`.`final` = _latin1'0'),1,`jobdaily`.`job_plazos_pago_proveedores`.`final`) AS `final` from `jobdaily`.`job_plazos_pago_proveedores` where (`jobdaily`.`job_plazos_pago_proveedores`.`codigo` <> _utf8'0');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_privilegios`
-- 

DROP VIEW IF EXISTS `job_buscador_privilegios`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_privilegios` AS select `jobdaily`.`job_perfiles_usuario`.`id` AS `id`,`jobdaily`.`job_usuarios`.`nombre` AS `usuario`,`jobdaily`.`job_sucursales`.`nombre` AS `sucursal` from ((`jobdaily`.`job_perfiles_usuario` join `jobdaily`.`job_usuarios`) join `jobdaily`.`job_sucursales`) where ((`jobdaily`.`job_perfiles_usuario`.`codigo_usuario` = `jobdaily`.`job_usuarios`.`codigo`) and (`jobdaily`.`job_perfiles_usuario`.`codigo_sucursal` = `jobdaily`.`job_sucursales`.`codigo`));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_profesiones_oficios`
-- 

DROP VIEW IF EXISTS `job_buscador_profesiones_oficios`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_profesiones_oficios` AS select `jobdaily`.`job_profesiones_oficios`.`codigo_dane` AS `id`,`jobdaily`.`job_profesiones_oficios`.`descripcion` AS `descripcion` from `jobdaily`.`job_profesiones_oficios` where (`jobdaily`.`job_profesiones_oficios`.`codigo_dane` <> 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_proveedores`
-- 

DROP VIEW IF EXISTS `job_buscador_proveedores`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_proveedores` AS select `jobdaily`.`job_proveedores`.`documento_identidad` AS `id`,`jobdaily`.`job_terceros`.`documento_identidad` AS `documento_identidad`,`jobdaily`.`job_terceros`.`primer_nombre` AS `primer_nombre`,`jobdaily`.`job_terceros`.`segundo_nombre` AS `segundo_nombre`,`jobdaily`.`job_terceros`.`primer_apellido` AS `primer_apellido`,`jobdaily`.`job_terceros`.`segundo_apellido` AS `segundo_apellido`,`jobdaily`.`job_terceros`.`razon_social` AS `razon_social`,`jobdaily`.`job_terceros`.`nombre_comercial` AS `nombre_comercial`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),concat(concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`primer_apellido` is not null),concat(`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),concat(`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1''),_latin1'')),`jobdaily`.`job_terceros`.`razon_social`)) AS `nombre_completo` from (`jobdaily`.`job_proveedores` join `jobdaily`.`job_terceros`) where (`jobdaily`.`job_proveedores`.`documento_identidad` = `jobdaily`.`job_terceros`.`documento_identidad`);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_proveedores_marcas`
-- 

DROP VIEW IF EXISTS `job_buscador_proveedores_marcas`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_proveedores_marcas` AS select concat(`jobdaily`.`job_proveedores_marcas`.`documento_identidad_proveedor`,_utf8'|',`jobdaily`.`job_proveedores_marcas`.`codigo_marca`) AS `id`,`jobdaily`.`job_proveedores`.`documento_identidad` AS `id_proveedor`,`jobdaily`.`job_marcas`.`codigo` AS `id_marca`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),`jobdaily`.`job_terceros`.`primer_nombre`,_latin1''),_latin1' ',if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1''),_latin1' ',if((`jobdaily`.`job_terceros`.`primer_apellido` is not null),`jobdaily`.`job_terceros`.`primer_apellido`,_latin1''),_latin1' ',if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1''),_latin1' ',if((`jobdaily`.`job_terceros`.`razon_social` is not null),`jobdaily`.`job_terceros`.`razon_social`,_latin1'')) AS `proveedor`,`jobdaily`.`job_marcas`.`descripcion` AS `marca` from (((`jobdaily`.`job_proveedores_marcas` join `jobdaily`.`job_terceros`) join `jobdaily`.`job_proveedores`) join `jobdaily`.`job_marcas`) where ((`jobdaily`.`job_proveedores_marcas`.`documento_identidad_proveedor` = `jobdaily`.`job_proveedores`.`documento_identidad`) and (`jobdaily`.`job_proveedores_marcas`.`codigo_marca` = `jobdaily`.`job_marcas`.`codigo`) and (`jobdaily`.`job_proveedores`.`documento_identidad` = `jobdaily`.`job_terceros`.`documento_identidad`));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_reporte_incapacidades`
-- 

DROP VIEW IF EXISTS `job_buscador_reporte_incapacidades`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_reporte_incapacidades` AS select concat(`jobdaily`.`job_reporte_incapacidades`.`documento_identidad_empleado`,_utf8'|',`jobdaily`.`job_reporte_incapacidades`.`fecha_inicial_incapacidad`) AS `id`,`jobdaily`.`job_sucursales`.`codigo` AS `id_sucursal`,`jobdaily`.`job_sucursales`.`nombre` AS `ALMACEN`,if(((`jobdaily`.`job_terceros`.`tipo_persona` = _latin1'1') or (`jobdaily`.`job_terceros`.`tipo_persona` = _latin1'4')),concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' ',if(((`jobdaily`.`job_terceros`.`segundo_nombre` is not null) and (`jobdaily`.`job_terceros`.`segundo_nombre` <> _latin1'')),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1''),`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' ',if(((`jobdaily`.`job_terceros`.`segundo_apellido` is not null) and (`jobdaily`.`job_terceros`.`segundo_apellido` <> _latin1'')),`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1'')),`jobdaily`.`job_terceros`.`razon_social`) AS `EMPLEADO`,`jobdaily`.`job_reporte_incapacidades`.`fecha_inicial_incapacidad` AS `FECHA_INCAPACIDAD_INICIO` from ((((`jobdaily`.`job_terceros` join `jobdaily`.`job_aspirantes`) join `jobdaily`.`job_sucursal_contrato_empleados`) join `jobdaily`.`job_reporte_incapacidades`) join `jobdaily`.`job_sucursales`) where ((`jobdaily`.`job_sucursal_contrato_empleados`.`documento_identidad_empleado` = `jobdaily`.`job_aspirantes`.`documento_identidad`) and (`jobdaily`.`job_aspirantes`.`documento_identidad` = `jobdaily`.`job_terceros`.`documento_identidad`) and (`jobdaily`.`job_reporte_incapacidades`.`documento_identidad_empleado` = `jobdaily`.`job_sucursal_contrato_empleados`.`documento_identidad_empleado`) and (`jobdaily`.`job_reporte_incapacidades`.`codigo_sucursal` = `jobdaily`.`job_sucursales`.`codigo`)) group by `jobdaily`.`job_reporte_incapacidades`.`documento_identidad_empleado`,`jobdaily`.`job_reporte_incapacidades`.`fecha_inicial_incapacidad`;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_resoluciones_dian`
-- 

DROP VIEW IF EXISTS `job_buscador_resoluciones_dian`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_resoluciones_dian` AS select concat(`jobdaily`.`job_resoluciones_dian`.`codigo_sucursal`,_utf8'|',`jobdaily`.`job_resoluciones_dian`.`numero`) AS `id`,`jobdaily`.`job_resoluciones_dian`.`codigo_sucursal` AS `id_sucursal`,`jobdaily`.`job_resoluciones_dian`.`fecha_inicia` AS `fecha_inicia`,`jobdaily`.`job_resoluciones_dian`.`fecha_termina` AS `fecha_termina`,`jobdaily`.`job_sucursales`.`nombre` AS `sucursal`,`jobdaily`.`job_resoluciones_dian`.`numero` AS `numero`,`jobdaily`.`job_tipos_documentos`.`descripcion` AS `tipo_documento` from ((`jobdaily`.`job_resoluciones_dian` join `jobdaily`.`job_sucursales`) join `jobdaily`.`job_tipos_documentos`) where ((`jobdaily`.`job_resoluciones_dian`.`codigo_sucursal` = `jobdaily`.`job_sucursales`.`codigo`) and (`jobdaily`.`job_resoluciones_dian`.`codigo_tipo_documento` = `jobdaily`.`job_tipos_documentos`.`codigo`));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_resoluciones_gran_contribuyente`
-- 

DROP VIEW IF EXISTS `job_buscador_resoluciones_gran_contribuyente`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_resoluciones_gran_contribuyente` AS select `jobdaily`.`job_resoluciones_gran_contribuyente`.`numero_resolucion` AS `id`,`jobdaily`.`job_resoluciones_gran_contribuyente`.`numero_resolucion` AS `numero`,`jobdaily`.`job_resoluciones_gran_contribuyente`.`descripcion` AS `descripcion`,`jobdaily`.`job_resoluciones_gran_contribuyente`.`fecha` AS `fecha` from `jobdaily`.`job_resoluciones_gran_contribuyente` where (`jobdaily`.`job_resoluciones_gran_contribuyente`.`numero_resolucion` <> 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_resoluciones_ica`
-- 

DROP VIEW IF EXISTS `job_buscador_resoluciones_ica`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_resoluciones_ica` AS select concat(`jobdaily`.`job_resoluciones_ica`.`numero_resolucion`,_utf8'|',`jobdaily`.`job_resoluciones_ica`.`codigo_sucursal`) AS `id`,`jobdaily`.`job_resoluciones_ica`.`numero_resolucion` AS `numero`,`jobdaily`.`job_sucursales`.`nombre` AS `sucursal`,`jobdaily`.`job_resoluciones_ica`.`codigo_sucursal` AS `codigo_sucursal`,`jobdaily`.`job_resoluciones_ica`.`fecha` AS `fecha` from (`jobdaily`.`job_resoluciones_ica` join `jobdaily`.`job_sucursales`) where ((`jobdaily`.`job_resoluciones_ica`.`codigo_sucursal` = `jobdaily`.`job_sucursales`.`codigo`) and (`jobdaily`.`job_resoluciones_ica`.`numero_resolucion` <> 0));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_resoluciones_retefuente`
-- 

DROP VIEW IF EXISTS `job_buscador_resoluciones_retefuente`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_resoluciones_retefuente` AS select `jobdaily`.`job_resoluciones_retefuente`.`numero_retefuente` AS `id`,`jobdaily`.`job_resoluciones_retefuente`.`numero_retefuente` AS `numero`,`jobdaily`.`job_resoluciones_retefuente`.`descripcion` AS `descripcion`,`jobdaily`.`job_resoluciones_retefuente`.`fecha` AS `fecha` from `jobdaily`.`job_resoluciones_retefuente` where (`jobdaily`.`job_resoluciones_retefuente`.`numero_retefuente` <> 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_retiro_cesantias`
-- 

DROP VIEW IF EXISTS `job_buscador_retiro_cesantias`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_retiro_cesantias` AS select concat(`jobdaily`.`job_retiro_cesantias`.`documento_identidad_empleado`,_utf8'|',`jobdaily`.`job_retiro_cesantias`.`consecutivo`,_utf8'|',`jobdaily`.`job_retiro_cesantias`.`fecha_generacion`,_utf8'|',`jobdaily`.`job_retiro_cesantias`.`concepto_retiro`) AS `id`,`jobdaily`.`job_sucursales`.`codigo` AS `id_sucursal`,`jobdaily`.`job_sucursales`.`nombre` AS `SUCURSAL_LABORA`,if(((`jobdaily`.`job_terceros`.`tipo_persona` = _latin1'1') or (`jobdaily`.`job_terceros`.`tipo_persona` = _latin1'4')),concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' ',if(((`jobdaily`.`job_terceros`.`segundo_nombre` is not null) and (`jobdaily`.`job_terceros`.`segundo_nombre` <> _latin1'')),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1''),`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' ',if(((`jobdaily`.`job_terceros`.`segundo_apellido` is not null) and (`jobdaily`.`job_terceros`.`segundo_apellido` <> _latin1'')),`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1'')),`jobdaily`.`job_terceros`.`razon_social`) AS `nombre_empleado`,`jobdaily`.`job_retiro_cesantias`.`fecha_generacion` AS `fecha_generacion`,`jobdaily`.`job_retiro_cesantias`.`valor_retiro` AS `valor_retiro` from ((`jobdaily`.`job_retiro_cesantias` join `jobdaily`.`job_terceros`) join `jobdaily`.`job_sucursales`) where ((`jobdaily`.`job_retiro_cesantias`.`documento_identidad_empleado` = `jobdaily`.`job_terceros`.`documento_identidad`) and (`jobdaily`.`job_retiro_cesantias`.`codigo_sucursal` = `jobdaily`.`job_sucursales`.`codigo`));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_salario_minimo`
-- 

DROP VIEW IF EXISTS `job_buscador_salario_minimo`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_salario_minimo` AS select `jobdaily`.`job_salario_minimo`.`codigo` AS `id`,`jobdaily`.`job_salario_minimo`.`fecha` AS `FECHA`,`jobdaily`.`job_salario_minimo`.`valor` AS `VALOR` from `jobdaily`.`job_salario_minimo`;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_secciones`
-- 

DROP VIEW IF EXISTS `job_buscador_secciones`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_secciones` AS select concat(`jobdaily`.`job_secciones`.`codigo_sucursal`,_utf8'|',`jobdaily`.`job_secciones`.`codigo_bodega`,_utf8'|',`jobdaily`.`job_secciones`.`codigo`) AS `id`,`jobdaily`.`job_secciones`.`codigo` AS `codigo`,`jobdaily`.`job_secciones`.`nombre` AS `nombre`,`jobdaily`.`job_secciones`.`descripcion` AS `descripcion`,`jobdaily`.`job_secciones`.`codigo_sucursal` AS `codigo_sucursal`,`jobdaily`.`job_sucursales`.`nombre` AS `sucursal`,`jobdaily`.`job_secciones`.`codigo_bodega` AS `codigo_bodega`,`jobdaily`.`job_bodegas`.`nombre` AS `bodega` from ((`jobdaily`.`job_secciones` join `jobdaily`.`job_bodegas`) join `jobdaily`.`job_sucursales`) where ((`jobdaily`.`job_secciones`.`codigo_bodega` = `jobdaily`.`job_bodegas`.`codigo`) and (`jobdaily`.`job_secciones`.`codigo_sucursal` = `jobdaily`.`job_bodegas`.`codigo_sucursal`) and (`jobdaily`.`job_secciones`.`codigo_sucursal` = `jobdaily`.`job_sucursales`.`codigo`) and (`jobdaily`.`job_secciones`.`codigo` <> 0)) order by `jobdaily`.`job_secciones`.`nombre`;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_secciones_departamentos`
-- 

DROP VIEW IF EXISTS `job_buscador_secciones_departamentos`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_secciones_departamentos` AS select concat(`jobdaily`.`job_secciones_departamentos`.`codigo`,_utf8'|',`jobdaily`.`job_secciones_departamentos`.`codigo_departamento_empresa`) AS `id`,`jobdaily`.`job_secciones_departamentos`.`codigo` AS `CODIGO`,`jobdaily`.`job_secciones_departamentos`.`nombre` AS `NOMBRE`,`jobdaily`.`job_departamentos_empresa`.`codigo` AS `DEPARTAMENTO_EMPRESA` from (`jobdaily`.`job_secciones_departamentos` join `jobdaily`.`job_departamentos_empresa`) where (`jobdaily`.`job_secciones_departamentos`.`codigo_departamento_empresa` = `jobdaily`.`job_departamentos_empresa`.`codigo`);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_servicios`
-- 

DROP VIEW IF EXISTS `job_buscador_servicios`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_servicios` AS select `jobdaily`.`job_servicios`.`codigo` AS `id`,`jobdaily`.`job_servicios`.`codigo` AS `codigo`,`jobdaily`.`job_servicios`.`descripcion` AS `descripcion` from `jobdaily`.`job_servicios` where (`jobdaily`.`job_servicios`.`codigo` > 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_servidores`
-- 

DROP VIEW IF EXISTS `job_buscador_servidores`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_servidores` AS select `jobdaily`.`job_servidores`.`id` AS `id`,`jobdaily`.`job_servidores`.`ip` AS `ip`,`jobdaily`.`job_sucursales`.`nombre` AS `sucursal`,`jobdaily`.`job_servidores`.`nombre_netbios` AS `nombre_netbios`,`jobdaily`.`job_servidores`.`nombre_tcpip` AS `nombre_tcpip` from (`jobdaily`.`job_servidores` join `jobdaily`.`job_sucursales`) where (`jobdaily`.`job_servidores`.`codigo_sucursal` = `jobdaily`.`job_sucursales`.`codigo`);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_sucursales`
-- 

DROP VIEW IF EXISTS `job_buscador_sucursales`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_sucursales` AS select `jobdaily`.`job_sucursales`.`codigo` AS `id`,`jobdaily`.`job_sucursales`.`codigo` AS `codigo`,`jobdaily`.`job_sucursales`.`nombre` AS `nombre`,`jobdaily`.`job_sucursales`.`nombre_corto` AS `nombre_corto`,`jobdaily`.`job_empresas`.`razon_social` AS `empresa`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`primer_apellido` is not null),concat(`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),concat(`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1' '),_latin1'')),`jobdaily`.`job_terceros`.`razon_social`)) AS `tercero` from ((`jobdaily`.`job_sucursales` join `jobdaily`.`job_terceros`) join `jobdaily`.`job_empresas`) where ((`jobdaily`.`job_sucursales`.`codigo_empresa` = `jobdaily`.`job_empresas`.`codigo`) and (`jobdaily`.`job_empresas`.`documento_identidad_tercero` = `jobdaily`.`job_terceros`.`documento_identidad`) and (`jobdaily`.`job_sucursales`.`codigo` <> 0));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_tablas`
-- 

DROP VIEW IF EXISTS `job_buscador_tablas`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_tablas` AS select `jobdaily`.`job_tablas`.`id` AS `id`,`jobdaily`.`job_tablas`.`nombre_tabla` AS `tabla` from `jobdaily`.`job_tablas` where (`jobdaily`.`job_tablas`.`id` <> 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_tasas`
-- 

DROP VIEW IF EXISTS `job_buscador_tasas`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_tasas` AS select `jobdaily`.`job_tasas`.`codigo` AS `id`,`jobdaily`.`job_tasas`.`codigo` AS `codigo`,`jobdaily`.`job_tasas`.`descripcion` AS `descripcion` from `jobdaily`.`job_tasas` where (`jobdaily`.`job_tasas`.`codigo` <> 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_terceros`
-- 

DROP VIEW IF EXISTS `job_buscador_terceros`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_terceros` AS select `jobdaily`.`job_terceros`.`documento_identidad` AS `id`,`jobdaily`.`job_terceros`.`documento_identidad` AS `DOCUMENTO_IDENTIDAD`,`jobdaily`.`job_terceros`.`primer_nombre` AS `PRIMER_NOMBRE`,`jobdaily`.`job_terceros`.`segundo_nombre` AS `SEGUNDO_NOMBRE`,`jobdaily`.`job_terceros`.`primer_apellido` AS `PRIMER_APELLIDO`,`jobdaily`.`job_terceros`.`segundo_apellido` AS `SEGUNDO_APELLIDO`,`jobdaily`.`job_terceros`.`razon_social` AS `RAZON_SOCIAL`,if(((`jobdaily`.`job_terceros`.`tipo_persona` = _latin1'1') or (`jobdaily`.`job_terceros`.`tipo_persona` = _latin1'4')),concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' ',if(((`jobdaily`.`job_terceros`.`segundo_nombre` is not null) and (`jobdaily`.`job_terceros`.`segundo_nombre` <> _latin1'')),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1''),`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' ',if(((`jobdaily`.`job_terceros`.`segundo_apellido` is not null) and (`jobdaily`.`job_terceros`.`segundo_apellido` <> _latin1'')),`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1'')),`jobdaily`.`job_terceros`.`razon_social`) AS `NOMBRE_COMPLETO` from `jobdaily`.`job_terceros` where (`jobdaily`.`job_terceros`.`documento_identidad` <> _latin1'0') order by `jobdaily`.`job_terceros`.`primer_nombre`,`jobdaily`.`job_terceros`.`razon_social`;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_terminales`
-- 

DROP VIEW IF EXISTS `job_buscador_terminales`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_terminales` AS select `jobdaily`.`job_terminales`.`id` AS `id`,`jobdaily`.`job_terminales`.`ip` AS `ip`,`jobdaily`.`job_terminales`.`nombre_netbios` AS `nombre_netbios`,`jobdaily`.`job_terminales`.`nombre_tcpip` AS `nombre_tcpip` from `jobdaily`.`job_terminales`;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_tipo_contrato`
-- 

DROP VIEW IF EXISTS `job_buscador_tipo_contrato`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_tipo_contrato` AS select `jobdaily`.`job_tipos_contrato`.`codigo` AS `id`,`jobdaily`.`job_tipos_contrato`.`descripcion` AS `DESCRIPCION`,concat(if((`jobdaily`.`job_tipos_contrato`.`termino_contrato` = 1),_utf8'Termino fijo',_utf8'Termino indefinido')) AS `TERMINO_CONTRATO` from `jobdaily`.`job_tipos_contrato` where (`jobdaily`.`job_tipos_contrato`.`codigo` <> 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_tipos_bodegas`
-- 

DROP VIEW IF EXISTS `job_buscador_tipos_bodegas`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_tipos_bodegas` AS select `jobdaily`.`job_tipos_bodegas`.`codigo` AS `id`,`jobdaily`.`job_tipos_bodegas`.`nombre` AS `nombre`,`jobdaily`.`job_tipos_bodegas`.`descripcion` AS `descripcion` from `jobdaily`.`job_tipos_bodegas` where (`jobdaily`.`job_tipos_bodegas`.`codigo` <> 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_tipos_comprobantes`
-- 

DROP VIEW IF EXISTS `job_buscador_tipos_comprobantes`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_tipos_comprobantes` AS select `jobdaily`.`job_tipos_comprobantes`.`codigo` AS `id`,`jobdaily`.`job_tipos_comprobantes`.`codigo` AS `codigo`,`jobdaily`.`job_tipos_comprobantes`.`descripcion` AS `descripcion` from `jobdaily`.`job_tipos_comprobantes` where (`jobdaily`.`job_tipos_comprobantes`.`codigo` <> 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_tipos_devoluciones_compra`
-- 

DROP VIEW IF EXISTS `job_buscador_tipos_devoluciones_compra`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_tipos_devoluciones_compra` AS select `tdc`.`codigo` AS `id`,`tdc`.`codigo` AS `codigo`,`tdc`.`descripcion` AS `nombre`,if((`tdc`.`concepto_compra` = _latin1'1'),_utf8'Compras directas',if((`tdc`.`concepto_compra` = _latin1'2'),_utf8'Compras obsequio',if((`tdc`.`concepto_compra` = _latin1'3'),_utf8'Compras filiales',if((`tdc`.`concepto_compra` = _latin1'4'),_utf8'Compras canje',_utf8'Compras en consignacion')))) AS `concepto` from `jobdaily`.`job_tipos_devoluciones_compra` `TDC`;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_tipos_documento_identidad`
-- 

DROP VIEW IF EXISTS `job_buscador_tipos_documento_identidad`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_tipos_documento_identidad` AS select `jobdaily`.`job_tipos_documento_identidad`.`codigo` AS `id`,`jobdaily`.`job_tipos_documento_identidad`.`codigo_dian` AS `codigo_dian`,`jobdaily`.`job_tipos_documento_identidad`.`codigo` AS `codigo_interno`,`jobdaily`.`job_tipos_documento_identidad`.`descripcion` AS `descripcion` from `jobdaily`.`job_tipos_documento_identidad` where (`jobdaily`.`job_tipos_documento_identidad`.`codigo` <> 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_tipos_documentos`
-- 

DROP VIEW IF EXISTS `job_buscador_tipos_documentos`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_tipos_documentos` AS select `jobdaily`.`job_tipos_documentos`.`codigo` AS `id`,`jobdaily`.`job_tipos_comprobantes`.`descripcion` AS `comprobante`,`jobdaily`.`job_tipos_documentos`.`codigo` AS `codigo`,`jobdaily`.`job_tipos_documentos`.`descripcion` AS `descripcion`,`jobdaily`.`job_tipos_documentos`.`observaciones` AS `observaciones`,`jobdaily`.`job_tipos_documentos`.`abreviaturas` AS `abreviaturas` from (`jobdaily`.`job_tipos_documentos` join `jobdaily`.`job_tipos_comprobantes`) where ((`jobdaily`.`job_tipos_documentos`.`codigo` <> _utf8'0') and (`jobdaily`.`job_tipos_documentos`.`codigo_comprobante` = `jobdaily`.`job_tipos_comprobantes`.`codigo`));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_tipos_documentos_bancarios`
-- 

DROP VIEW IF EXISTS `job_buscador_tipos_documentos_bancarios`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_tipos_documentos_bancarios` AS select `jobdaily`.`job_tipos_documentos_bancarios`.`codigo` AS `id`,`jobdaily`.`job_tipos_documentos_bancarios`.`codigo` AS `CODIGO`,`jobdaily`.`job_tipos_documentos_bancarios`.`descripcion` AS `DESCRIPCION` from `jobdaily`.`job_tipos_documentos_bancarios` where (`jobdaily`.`job_tipos_documentos_bancarios`.`codigo` <> _utf8'0');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_tipos_moneda`
-- 

DROP VIEW IF EXISTS `job_buscador_tipos_moneda`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_tipos_moneda` AS select `jobdaily`.`job_tipos_moneda`.`codigo` AS `id`,`jobdaily`.`job_tipos_moneda`.`codigo` AS `codigo`,`jobdaily`.`job_tipos_moneda`.`codigo_dian` AS `codigo_dian`,`jobdaily`.`job_tipos_moneda`.`nombre` AS `nombre` from `jobdaily`.`job_tipos_moneda` where (`jobdaily`.`job_tipos_moneda`.`codigo` > 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_tipos_unidades`
-- 

DROP VIEW IF EXISTS `job_buscador_tipos_unidades`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_tipos_unidades` AS select `jobdaily`.`job_tipos_unidades`.`codigo` AS `id`,`jobdaily`.`job_tipos_unidades`.`nombre` AS `nombre`,`jobdaily`.`job_tipos_unidades`.`codigo` AS `codigo` from `jobdaily`.`job_tipos_unidades` where (`jobdaily`.`job_tipos_unidades`.`codigo` <> 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_transacciones_contables_empleado`
-- 

DROP VIEW IF EXISTS `job_buscador_transacciones_contables_empleado`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_transacciones_contables_empleado` AS select `jobdaily`.`job_transacciones_contables_empleado`.`codigo` AS `id`,`jobdaily`.`job_transacciones_contables_empleado`.`nombre` AS `NOMBRE`,`jobdaily`.`job_conceptos_transacciones_contables_empleado`.`descripcion` AS `CONCEPTO_CONTABLE`,concat(`jobdaily`.`job_plan_contable`.`codigo_contable`,_latin1' ',`jobdaily`.`job_plan_contable`.`descripcion`) AS `CODIGO_CONTABLE` from ((`jobdaily`.`job_transacciones_contables_empleado` join `jobdaily`.`job_plan_contable`) join `jobdaily`.`job_conceptos_transacciones_contables_empleado`) where ((`jobdaily`.`job_transacciones_contables_empleado`.`codigo_concepto_transaccion_contable` = `jobdaily`.`job_conceptos_transacciones_contables_empleado`.`codigo`) and (`jobdaily`.`job_transacciones_contables_empleado`.`codigo_contable` = `jobdaily`.`job_plan_contable`.`codigo_contable`) and (`jobdaily`.`job_transacciones_contables_empleado`.`codigo` > 0));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_transacciones_tiempo`
-- 

DROP VIEW IF EXISTS `job_buscador_transacciones_tiempo`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_transacciones_tiempo` AS select `jobdaily`.`job_transacciones_tiempo`.`codigo` AS `id`,`jobdaily`.`job_transacciones_tiempo`.`nombre` AS `NOMBRE`,`jobdaily`.`job_conceptos_transacciones_tiempo`.`descripcion` AS `CONCEPTO_TIEMPO`,`jobdaily`.`job_transacciones_contables_empleado`.`nombre` AS `TRANSACCION_CONTABLE` from ((`jobdaily`.`job_transacciones_contables_empleado` join `jobdaily`.`job_transacciones_tiempo`) join `jobdaily`.`job_conceptos_transacciones_tiempo`) where ((`jobdaily`.`job_transacciones_tiempo`.`codigo_transaccion_contable` = `jobdaily`.`job_transacciones_contables_empleado`.`codigo`) and (`jobdaily`.`job_transacciones_tiempo`.`codigo_concepto_transaccion_tiempo` = `jobdaily`.`job_conceptos_transacciones_tiempo`.`codigo`) and (`jobdaily`.`job_transacciones_tiempo`.`codigo` > 0));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_unidades`
-- 

DROP VIEW IF EXISTS `job_buscador_unidades`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_unidades` AS select `jobdaily`.`job_unidades`.`codigo` AS `id`,`jobdaily`.`job_unidades`.`codigo` AS `codigo`,`jobdaily`.`job_tipos_unidades`.`nombre` AS `tipo_unidad`,`jobdaily`.`job_unidades`.`nombre` AS `nombre` from (`jobdaily`.`job_unidades` join `jobdaily`.`job_tipos_unidades`) where ((`jobdaily`.`job_unidades`.`codigo_tipo_unidad` = `jobdaily`.`job_tipos_unidades`.`codigo`) and (`jobdaily`.`job_unidades`.`codigo` <> 0));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_usuarios`
-- 

DROP VIEW IF EXISTS `job_buscador_usuarios`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_usuarios` AS select `jobdaily`.`job_usuarios`.`codigo` AS `id`,`jobdaily`.`job_usuarios`.`usuario` AS `usuario`,`jobdaily`.`job_usuarios`.`nombre` AS `nombre`,`jobdaily`.`job_usuarios`.`activo` AS `activo` from `jobdaily`.`job_usuarios` where (`jobdaily`.`job_usuarios`.`activo` <> _latin1'0');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_buscador_vigencia_tasas`
-- 

DROP VIEW IF EXISTS `job_buscador_vigencia_tasas`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_buscador_vigencia_tasas` AS select concat(`jobdaily`.`job_vigencia_tasas`.`codigo_tasa`,_utf8'|',`jobdaily`.`job_vigencia_tasas`.`fecha`) AS `id`,`jobdaily`.`job_vigencia_tasas`.`codigo_tasa` AS `codigo`,`jobdaily`.`job_tasas`.`descripcion` AS `tasa`,`jobdaily`.`job_vigencia_tasas`.`fecha` AS `fecha`,`jobdaily`.`job_vigencia_tasas`.`porcentaje` AS `porcentaje`,`jobdaily`.`job_vigencia_tasas`.`valor_base` AS `valor_base` from (`jobdaily`.`job_tasas` join `jobdaily`.`job_vigencia_tasas`) where ((`jobdaily`.`job_vigencia_tasas`.`codigo_tasa` = `jobdaily`.`job_tasas`.`codigo`) and (`jobdaily`.`job_vigencia_tasas`.`codigo_tasa` <> 0));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_consulta_bitacora`
-- 

DROP VIEW IF EXISTS `job_consulta_bitacora`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_consulta_bitacora` AS select concat(`jobdaily`.`job_bitacora`.`codigo_sucursal_conexion`,_utf8'|',`jobdaily`.`job_bitacora`.`codigo_usuario_conexion`,_utf8'|',`jobdaily`.`job_bitacora`.`fecha_conexion`) AS `id`,date_format(`jobdaily`.`job_bitacora`.`fecha_operacion`,_utf8'%Y/%m/%d') AS `FECHA`,date_format(`jobdaily`.`job_bitacora`.`fecha_operacion`,_utf8'%T') AS `HORA`,`jobdaily`.`job_bitacora`.`componente` AS `COMPONENTE`,`jobdaily`.`job_bitacora`.`consulta` AS `CONSULTA`,`jobdaily`.`job_bitacora`.`mensaje` AS `MENSAJE` from `jobdaily`.`job_bitacora`;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_consulta_contrato_empleado`
-- 

DROP VIEW IF EXISTS `job_consulta_contrato_empleado`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_consulta_contrato_empleado` AS select `jobdaily`.`job_ingreso_empleados`.`codigo_empresa` AS `codigo_empresa`,`jobdaily`.`job_ingreso_empleados`.`documento_identidad_empleado` AS `documento_identidad_empleado`,`job_menu_terceros`.`NOMBRE_COMPLETO` AS `nombre_empleado`,`jobdaily`.`job_ingreso_empleados`.`fecha_ingreso` AS `fecha_ingreso`,`jobdaily`.`job_ingreso_empleados`.`fecha_vencimiento_contrato` AS `fecha_vencimiento_contrato`,`jobdaily`.`job_ingreso_empleados`.`fecha_retiro` AS `fecha_retiro_empresa`,`jobdaily`.`job_ingreso_empleados`.`codigo_motivo_retiro` AS `codigo_motivo_retiro_empresa`,`jobdaily`.`job_ingreso_empleados`.`riesgo_profesional` AS `riesgo_profesional`,`jobdaily`.`job_ingreso_empleados`.`manejo_auxilio_transporte` AS `manejo_auxilio_transporte`,`jobdaily`.`job_ingreso_empleados`.`estado` AS `estado`,`jobdaily`.`job_contrato_empleados`.`fecha_contrato` AS `fecha_contrato`,`jobdaily`.`job_contrato_empleados`.`codigo_tipo_contrato` AS `codigo_tipo_contrato`,`jobdaily`.`job_tipos_contrato`.`tipo_contratacion` AS `tipo_salario`,`jobdaily`.`job_contrato_empleados`.`fecha_cambio_contrato` AS `fecha_cambio_contrato`,`jobdaily`.`job_sucursal_contrato_empleados`.`codigo_sucursal` AS `codigo_sucursal`,`jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso_sucursal` AS `fecha_ingreso_sucursal`,`jobdaily`.`job_sucursal_contrato_empleados`.`codigo_empresa_auxiliar` AS `codigo_empresa_auxiliar`,`jobdaily`.`job_sucursal_contrato_empleados`.`codigo_anexo_contable` AS `codigo_anexo_contable`,`jobdaily`.`job_sucursal_contrato_empleados`.`codigo_auxiliar` AS `codigo_auxiliar`,`jobdaily`.`job_sucursal_contrato_empleados`.`codigo_planilla` AS `codigo_planilla`,`jobdaily`.`job_sucursal_contrato_empleados`.`codigo_turno_laboral` AS `codigo_turno_laboral`,`jobdaily`.`job_sucursal_contrato_empleados`.`codigo_motivo_retiro` AS `codigo_motivo_retiro_sucursal`,`jobdaily`.`job_sucursal_contrato_empleados`.`fecha_retiro` AS `fecha_retiro_sucursal`,`jobdaily`.`job_sucursal_contrato_empleados`.`codigo_transaccion_salario` AS `codigo_transaccion_salario`,`jobdaily`.`job_sucursal_contrato_empleados`.`codigo_transaccion_auxilio_transporte` AS `codigo_transaccion_auxilio_transporte`,`jobdaily`.`job_sucursal_contrato_empleados`.`forma_pago_auxilio` AS `forma_pago_auxilio`,`jobdaily`.`job_sucursal_contrato_empleados`.`codigo_transaccion_salud` AS `codigo_transaccion_salud`,`jobdaily`.`job_sucursal_contrato_empleados`.`forma_descuento_salud` AS `forma_descuento_salud`,`jobdaily`.`job_sucursal_contrato_empleados`.`codigo_transaccion_pension` AS `codigo_transaccion_pension`,`jobdaily`.`job_sucursal_contrato_empleados`.`forma_descuento_pension` AS `forma_descuento_pension`,`jobdaily`.`job_sucursal_contrato_empleados`.`codigo_transaccion_normales` AS `codigo_transaccion_normales`,`jobdaily`.`job_sucursal_contrato_empleados`.`codigo_transaccion_extras` AS `codigo_transaccion_extras`,`jobdaily`.`job_sucursal_contrato_empleados`.`codigo_transaccion_recargo_nocturno` AS `codigo_transaccion_recargo_nocturno`,`jobdaily`.`job_sucursal_contrato_empleados`.`codigo_transaccion_extras_nocturnas` AS `codigo_transaccion_extras_nocturnas`,`jobdaily`.`job_sucursal_contrato_empleados`.`codigo_transaccion_dominicales` AS `codigo_transaccion_dominicales`,`jobdaily`.`job_sucursal_contrato_empleados`.`codigo_transaccion_extras_dominicales` AS `codigo_transaccion_extras_dominicales`,`jobdaily`.`job_sucursal_contrato_empleados`.`codigo_transaccion_recargo_noche_dominicales` AS `codigo_transaccion_recargo_noche_dominicales`,`jobdaily`.`job_sucursal_contrato_empleados`.`codigo_transaccion_extras_noche_dominicales` AS `codigo_transaccion_extras_noche_dominicales`,`jobdaily`.`job_salario_sucursal_contrato`.`fecha_salario` AS `fecha_salario`,`jobdaily`.`job_salario_sucursal_contrato`.`fecha_retroactivo` AS `fecha_retroactivo`,`jobdaily`.`job_salario_sucursal_contrato`.`salario` AS `salario`,`jobdaily`.`job_salario_sucursal_contrato`.`valor_dia` AS `valor_dia`,`jobdaily`.`job_salario_sucursal_contrato`.`valor_hora` AS `valor_hora`,`jobdaily`.`job_cargo_contrato_empleados`.`fecha_inicia_cargo` AS `fecha_inicia_cargo`,`jobdaily`.`job_cargo_contrato_empleados`.`codigo_cargo` AS `codigo_cargo`,`jobdaily`.`job_cargo_contrato_empleados`.`fecha_termina` AS `fecha_termina_cargo`,`jobdaily`.`job_cargo_contrato_empleados`.`documento_identidad_jefe_inmediato` AS `documento_identidad_jefe_inmediato`,`jobdaily`.`job_departamento_seccion_contrato_empleado`.`fecha_inicia_departamento_seccion` AS `fecha_inicia_departamento_seccion`,`jobdaily`.`job_departamento_seccion_contrato_empleado`.`codigo_departamento_empresa` AS `codigo_departamento_empresa`,`jobdaily`.`job_departamento_seccion_contrato_empleado`.`codigo_seccion_empresa` AS `codigo_seccion_empresa`,`jobdaily`.`job_departamento_seccion_contrato_empleado`.`fecha_termina` AS `fecha_termina_seccion` from (((((((`jobdaily`.`job_ingreso_empleados` join `jobdaily`.`job_contrato_empleados`) join `jobdaily`.`job_sucursal_contrato_empleados`) join `jobdaily`.`job_salario_sucursal_contrato`) join `jobdaily`.`job_cargo_contrato_empleados`) join `jobdaily`.`job_departamento_seccion_contrato_empleado`) join `jobdaily`.`job_menu_terceros`) join `jobdaily`.`job_tipos_contrato`) where ((`job_menu_terceros`.`id` = `jobdaily`.`job_ingreso_empleados`.`documento_identidad_empleado`) and (`jobdaily`.`job_ingreso_empleados`.`codigo_empresa` = `jobdaily`.`job_contrato_empleados`.`codigo_empresa`) and (`jobdaily`.`job_ingreso_empleados`.`documento_identidad_empleado` = `jobdaily`.`job_contrato_empleados`.`documento_identidad_empleado`) and (`jobdaily`.`job_ingreso_empleados`.`fecha_ingreso` = `jobdaily`.`job_contrato_empleados`.`fecha_ingreso`) and (`jobdaily`.`job_ingreso_empleados`.`codigo_empresa` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_empresa`) and (`jobdaily`.`job_ingreso_empleados`.`documento_identidad_empleado` = `jobdaily`.`job_sucursal_contrato_empleados`.`documento_identidad_empleado`) and (`jobdaily`.`job_ingreso_empleados`.`fecha_ingreso` = `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso`) and (`jobdaily`.`job_sucursal_contrato_empleados`.`codigo_empresa` = `jobdaily`.`job_salario_sucursal_contrato`.`codigo_empresa`) and (`jobdaily`.`job_sucursal_contrato_empleados`.`documento_identidad_empleado` = `jobdaily`.`job_salario_sucursal_contrato`.`documento_identidad_empleado`) and (`jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso` = `jobdaily`.`job_salario_sucursal_contrato`.`fecha_ingreso`) and (`jobdaily`.`job_sucursal_contrato_empleados`.`codigo_sucursal` = `jobdaily`.`job_salario_sucursal_contrato`.`codigo_sucursal`) and (`jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso_sucursal` = `jobdaily`.`job_salario_sucursal_contrato`.`fecha_ingreso_sucursal`) and (`jobdaily`.`job_sucursal_contrato_empleados`.`codigo_empresa` = `jobdaily`.`job_cargo_contrato_empleados`.`codigo_empresa`) and (`jobdaily`.`job_sucursal_contrato_empleados`.`documento_identidad_empleado` = `jobdaily`.`job_cargo_contrato_empleados`.`documento_identidad_empleado`) and (`jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso` = `jobdaily`.`job_cargo_contrato_empleados`.`fecha_ingreso`) and (`jobdaily`.`job_sucursal_contrato_empleados`.`codigo_sucursal` = `jobdaily`.`job_cargo_contrato_empleados`.`codigo_sucursal`) and (`jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso_sucursal` = `jobdaily`.`job_cargo_contrato_empleados`.`fecha_ingreso_sucursal`) and (`jobdaily`.`job_contrato_empleados`.`codigo_tipo_contrato` = `jobdaily`.`job_tipos_contrato`.`codigo`) and (`jobdaily`.`job_sucursal_contrato_empleados`.`codigo_empresa` = `jobdaily`.`job_departamento_seccion_contrato_empleado`.`codigo_empresa`) and (`jobdaily`.`job_sucursal_contrato_empleados`.`documento_identidad_empleado` = `jobdaily`.`job_departamento_seccion_contrato_empleado`.`documento_identidad_empleado`) and (`jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso` = `jobdaily`.`job_departamento_seccion_contrato_empleado`.`fecha_ingreso`) and (`jobdaily`.`job_sucursal_contrato_empleados`.`codigo_sucursal` = `jobdaily`.`job_departamento_seccion_contrato_empleado`.`codigo_sucursal`) and (`jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso_sucursal` = `jobdaily`.`job_departamento_seccion_contrato_empleado`.`fecha_ingreso_sucursal`));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_consulta_datos_planilla`
-- 

DROP VIEW IF EXISTS `job_consulta_datos_planilla`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_consulta_datos_planilla` AS (select 1 AS `tabla`,_utf8'job_movimientos_salarios' AS `nombre_tabla`,`jobdaily`.`job_movimientos_salarios`.`ano_generacion` AS `ano_generacion`,`jobdaily`.`job_movimientos_salarios`.`mes_generacion` AS `mes_generacion`,`jobdaily`.`job_movimientos_salarios`.`codigo_planilla` AS `codigo_planilla`,`jobdaily`.`job_movimientos_salarios`.`periodo_pago` AS `periodo_pago`,`jobdaily`.`job_movimientos_salarios`.`codigo_transaccion_contable` AS `codigo_transaccion_contable`,`jobdaily`.`job_transacciones_contables_empleado`.`acumula_cesantias` AS `acumula_cesantias`,`jobdaily`.`job_transacciones_contables_empleado`.`acumula_prima` AS `acumula_prima`,`jobdaily`.`job_transacciones_contables_empleado`.`acumula_vacaciones` AS `acumula_vacaciones`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_salud` AS `ibc_salud`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_pension` AS `ibc_pension`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_arp` AS `ibc_arp`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_icbf` AS `ibc_icbf`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_caja_compensacion` AS `ibc_caja_compensacion`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_sena` AS `ibc_sena`,`jobdaily`.`job_transacciones_contables_empleado`.`codigo_concepto_transaccion_contable` AS `concepto_contable`,`jobdaily`.`job_transacciones_contables_empleado`.`columna_planilla` AS `columna_planilla`,0 AS `codigo_transaccion_tiempo`,0 AS `resta_salario`,0 AS `resta_auxilio_transporte`,0 AS `resta_cesantias`,0 AS `resta_prima`,0 AS `resta_vacaciones`,0 AS `extras_empleado`,0 AS `concepto_tiempo`,(`jobdaily`.`job_movimientos_salarios`.`documento_identidad_empleado` * 1) AS `documento_identidad_empleado`,`jobdaily`.`job_movimientos_salarios`.`codigo_contable` AS `codigo_contable`,`jobdaily`.`job_movimientos_salarios`.`sentido` AS `sentido`,`jobdaily`.`job_movimientos_salarios`.`codigo_empresa` AS `codigo_empresa_auxiliar`,`jobdaily`.`job_movimientos_salarios`.`codigo_anexo_contable` AS `codigo_anexo_contable`,`jobdaily`.`job_movimientos_salarios`.`codigo_auxiliar_contable` AS `codigo_auxiliar_contable`,`jobdaily`.`job_movimientos_salarios`.`valor_movimiento` AS `valor_movimiento`,`jobdaily`.`job_movimientos_salarios`.`dias_trabajados` AS `dias_trabajados`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),concat(concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`primer_apellido` is not null),concat(`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),concat(`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1''),_latin1'')),`jobdaily`.`job_terceros`.`razon_social`)) AS `nombre_empleado`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),concat(concat(`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),concat(`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1' '),_latin1' '),concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1'')),`jobdaily`.`job_terceros`.`razon_social`)) AS `apellido_empleado`,`jobdaily`.`job_departamentos_empresa`.`codigo` AS `codigo_departamento_empresa`,`jobdaily`.`job_departamentos_empresa`.`nombre` AS `departamento_empresa`,`jobdaily`.`job_sucursal_contrato_empleados`.`salario_mensual` AS `salario_empleado`,`jobdaily`.`job_sucursal_contrato_empleados`.`codigo_empresa` AS `codigo_empresa`,`jobdaily`.`job_sucursal_contrato_empleados`.`codigo_sucursal` AS `codigo_sucursal`,`jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso` AS `fecha_ingreso_empresa`,`jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso_sucursal` AS `fecha_ingreso_sucursal`,`jobdaily`.`job_movimientos_salarios`.`contabilizado` AS `contabilizado`,0 AS `consecutivo`,0 AS `fecha_incapacidad`,`jobdaily`.`job_movimientos_salarios`.`fecha_ingreso_planilla` AS `fecha_registro`,`jobdaily`.`job_movimientos_salarios`.`fecha_pago_planilla` AS `fecha_pago_planilla`,_utf8'' AS `obligacion` from (((((`jobdaily`.`job_movimientos_salarios` join `jobdaily`.`job_departamento_seccion_contrato_empleado`) join `jobdaily`.`job_departamentos_empresa`) join `jobdaily`.`job_sucursal_contrato_empleados`) join `jobdaily`.`job_terceros`) join `jobdaily`.`job_transacciones_contables_empleado`) where ((`jobdaily`.`job_movimientos_salarios`.`documento_identidad_empleado` = `jobdaily`.`job_terceros`.`documento_identidad`) and (`jobdaily`.`job_movimientos_salarios`.`codigo_empresa` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_empresa`) and (`jobdaily`.`job_movimientos_salarios`.`documento_identidad_empleado` = `jobdaily`.`job_sucursal_contrato_empleados`.`documento_identidad_empleado`) and (`jobdaily`.`job_movimientos_salarios`.`fecha_ingreso_empresa` = `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso`) and (`jobdaily`.`job_movimientos_salarios`.`codigo_sucursal` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_sucursal`) and (`jobdaily`.`job_movimientos_salarios`.`fecha_ingreso_sucursal` = `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso_sucursal`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`codigo_empresa` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_empresa`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`documento_identidad_empleado` = `jobdaily`.`job_sucursal_contrato_empleados`.`documento_identidad_empleado`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`fecha_ingreso` = `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`codigo_sucursal` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_sucursal`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`fecha_ingreso_sucursal` = `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso_sucursal`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`codigo_departamento_empresa` = `jobdaily`.`job_departamentos_empresa`.`codigo`) and (`jobdaily`.`job_movimientos_salarios`.`codigo_transaccion_contable` = `jobdaily`.`job_transacciones_contables_empleado`.`codigo`))) union (select 2 AS `tabla`,_utf8'job_movimientos_salud' AS `nombre_tabla`,`jobdaily`.`job_movimientos_salud`.`ano_generacion` AS `ano_generacion`,`jobdaily`.`job_movimientos_salud`.`mes_generacion` AS `mes_generacion`,`jobdaily`.`job_movimientos_salud`.`codigo_planilla` AS `codigo_planilla`,`jobdaily`.`job_movimientos_salud`.`periodo_pago` AS `periodo_pago`,`jobdaily`.`job_movimientos_salud`.`codigo_transaccion_contable` AS `codigo_transaccion_contable`,`jobdaily`.`job_transacciones_contables_empleado`.`acumula_cesantias` AS `acumula_cesantias`,`jobdaily`.`job_transacciones_contables_empleado`.`acumula_prima` AS `acumula_prima`,`jobdaily`.`job_transacciones_contables_empleado`.`acumula_vacaciones` AS `acumula_vacaciones`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_salud` AS `ibc_salud`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_pension` AS `ibc_pension`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_arp` AS `ibc_arp`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_icbf` AS `ibc_icbf`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_caja_compensacion` AS `ibc_caja_compensacion`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_sena` AS `ibc_sena`,`jobdaily`.`job_transacciones_contables_empleado`.`codigo_concepto_transaccion_contable` AS `concepto_contable`,`jobdaily`.`job_transacciones_contables_empleado`.`columna_planilla` AS `columna_planilla`,0 AS `codigo_transaccion_tiempo`,0 AS `resta_salario`,0 AS `resta_auxilio_transporte`,0 AS `resta_cesantias`,0 AS `resta_prima`,0 AS `resta_vacaciones`,0 AS `extras_empleado`,0 AS `concepto_tiempo`,(`jobdaily`.`job_movimientos_salud`.`documento_identidad_empleado` * 1) AS `documento_identidad_empleado`,`jobdaily`.`job_movimientos_salud`.`codigo_contable` AS `codigo_contable`,`jobdaily`.`job_movimientos_salud`.`sentido` AS `sentido`,`jobdaily`.`job_movimientos_salud`.`codigo_empresa` AS `codigo_empresa_auxiliar`,`jobdaily`.`job_movimientos_salud`.`codigo_anexo_contable` AS `codigo_anexo_contable`,`jobdaily`.`job_movimientos_salud`.`codigo_auxiliar_contable` AS `codigo_auxiliar_contable`,`jobdaily`.`job_movimientos_salud`.`valor_movimiento` AS `valor_movimiento`,0 AS `dias_trabajados`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),concat(concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`primer_apellido` is not null),concat(`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),concat(`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1''),_latin1'')),`jobdaily`.`job_terceros`.`razon_social`)) AS `nombre_empleado`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),concat(concat(`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),concat(`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1' '),_latin1' '),concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1'')),`jobdaily`.`job_terceros`.`razon_social`)) AS `apellido_empleado`,`jobdaily`.`job_departamentos_empresa`.`codigo` AS `codigo_departamento_empresa`,`jobdaily`.`job_departamentos_empresa`.`nombre` AS `departamento_empresa`,`jobdaily`.`job_sucursal_contrato_empleados`.`salario_mensual` AS `salario_empleado`,`jobdaily`.`job_sucursal_contrato_empleados`.`codigo_empresa` AS `codigo_empresa`,`jobdaily`.`job_sucursal_contrato_empleados`.`codigo_sucursal` AS `codigo_sucursal`,`jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso` AS `fecha_ingreso_empresa`,`jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso_sucursal` AS `fecha_ingreso_sucursal`,`jobdaily`.`job_movimientos_salud`.`contabilizado` AS `contabilizado`,0 AS `consecutivo`,0 AS `fecha_incapacidad`,`jobdaily`.`job_movimientos_salud`.`fecha_ingreso_planilla` AS `fecha_registro`,`jobdaily`.`job_movimientos_salud`.`fecha_pago_planilla` AS `fecha_pago_planilla`,_utf8'' AS `obligacion` from (((((`jobdaily`.`job_movimientos_salud` join `jobdaily`.`job_departamento_seccion_contrato_empleado`) join `jobdaily`.`job_departamentos_empresa`) join `jobdaily`.`job_sucursal_contrato_empleados`) join `jobdaily`.`job_terceros`) join `jobdaily`.`job_transacciones_contables_empleado`) where ((`jobdaily`.`job_movimientos_salud`.`documento_identidad_empleado` = `jobdaily`.`job_terceros`.`documento_identidad`) and (`jobdaily`.`job_movimientos_salud`.`codigo_empresa` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_empresa`) and (`jobdaily`.`job_movimientos_salud`.`documento_identidad_empleado` = `jobdaily`.`job_sucursal_contrato_empleados`.`documento_identidad_empleado`) and (`jobdaily`.`job_movimientos_salud`.`fecha_ingreso_empresa` = `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso`) and (`jobdaily`.`job_movimientos_salud`.`codigo_sucursal` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_sucursal`) and (`jobdaily`.`job_movimientos_salud`.`fecha_ingreso_sucursal` = `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso_sucursal`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`codigo_empresa` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_empresa`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`documento_identidad_empleado` = `jobdaily`.`job_sucursal_contrato_empleados`.`documento_identidad_empleado`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`fecha_ingreso` = `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`codigo_sucursal` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_sucursal`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`fecha_ingreso_sucursal` = `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso_sucursal`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`codigo_departamento_empresa` = `jobdaily`.`job_departamentos_empresa`.`codigo`) and (`jobdaily`.`job_movimientos_salud`.`codigo_transaccion_contable` = `jobdaily`.`job_transacciones_contables_empleado`.`codigo`))) union (select 3 AS `tabla`,_utf8'job_movimientos_pension' AS `nombre_tabla`,`jobdaily`.`job_movimientos_pension`.`ano_generacion` AS `ano_generacion`,`jobdaily`.`job_movimientos_pension`.`mes_generacion` AS `mes_generacion`,`jobdaily`.`job_movimientos_pension`.`codigo_planilla` AS `codigo_planilla`,`jobdaily`.`job_movimientos_pension`.`periodo_pago` AS `periodo_pago`,`jobdaily`.`job_movimientos_pension`.`codigo_transaccion_contable` AS `codigo_transaccion_contable`,`jobdaily`.`job_transacciones_contables_empleado`.`acumula_cesantias` AS `acumula_cesantias`,`jobdaily`.`job_transacciones_contables_empleado`.`acumula_prima` AS `acumula_prima`,`jobdaily`.`job_transacciones_contables_empleado`.`acumula_vacaciones` AS `acumula_vacaciones`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_salud` AS `ibc_salud`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_pension` AS `ibc_pension`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_arp` AS `ibc_arp`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_icbf` AS `ibc_icbf`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_caja_compensacion` AS `ibc_caja_compensacion`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_sena` AS `ibc_sena`,`jobdaily`.`job_transacciones_contables_empleado`.`codigo_concepto_transaccion_contable` AS `concepto_contable`,`jobdaily`.`job_transacciones_contables_empleado`.`columna_planilla` AS `columna_planilla`,0 AS `codigo_transaccion_tiempo`,0 AS `resta_salario`,0 AS `resta_auxilio_transporte`,0 AS `resta_cesantias`,0 AS `resta_prima`,0 AS `resta_vacaciones`,0 AS `extras_empleado`,0 AS `concepto_tiempo`,(`jobdaily`.`job_movimientos_pension`.`documento_identidad_empleado` * 1) AS `documento_identidad_empleado`,`jobdaily`.`job_movimientos_pension`.`codigo_contable` AS `codigo_contable`,`jobdaily`.`job_movimientos_pension`.`sentido` AS `sentido`,`jobdaily`.`job_movimientos_pension`.`codigo_empresa` AS `codigo_empresa_auxiliar`,`jobdaily`.`job_movimientos_pension`.`codigo_anexo_contable` AS `codigo_anexo_contable`,`jobdaily`.`job_movimientos_pension`.`codigo_auxiliar_contable` AS `codigo_auxiliar_contable`,`jobdaily`.`job_movimientos_pension`.`valor_movimiento` AS `valor_movimiento`,0 AS `dias_trabajados`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),concat(concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`primer_apellido` is not null),concat(`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),concat(`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1''),_latin1'')),`jobdaily`.`job_terceros`.`razon_social`)) AS `nombre_empleado`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),concat(concat(`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),concat(`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1' '),_latin1' '),concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1'')),`jobdaily`.`job_terceros`.`razon_social`)) AS `apellido_empleado`,`jobdaily`.`job_departamentos_empresa`.`codigo` AS `codigo_departamento_empresa`,`jobdaily`.`job_departamentos_empresa`.`nombre` AS `departamento_empresa`,`jobdaily`.`job_sucursal_contrato_empleados`.`salario_mensual` AS `salario_empleado`,`jobdaily`.`job_sucursal_contrato_empleados`.`codigo_empresa` AS `codigo_empresa`,`jobdaily`.`job_sucursal_contrato_empleados`.`codigo_sucursal` AS `codigo_sucursal`,`jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso` AS `fecha_ingreso_empresa`,`jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso_sucursal` AS `fecha_ingreso_sucursal`,`jobdaily`.`job_movimientos_pension`.`contabilizado` AS `contabilizado`,0 AS `consecutivo`,0 AS `fecha_incapacidad`,`jobdaily`.`job_movimientos_pension`.`fecha_ingreso_planilla` AS `fecha_registro`,`jobdaily`.`job_movimientos_pension`.`fecha_pago_planilla` AS `fecha_pago_planilla`,_utf8'' AS `obligacion` from (((((`jobdaily`.`job_movimientos_pension` join `jobdaily`.`job_departamento_seccion_contrato_empleado`) join `jobdaily`.`job_departamentos_empresa`) join `jobdaily`.`job_sucursal_contrato_empleados`) join `jobdaily`.`job_terceros`) join `jobdaily`.`job_transacciones_contables_empleado`) where ((`jobdaily`.`job_movimientos_pension`.`documento_identidad_empleado` = `jobdaily`.`job_terceros`.`documento_identidad`) and (`jobdaily`.`job_movimientos_pension`.`codigo_empresa` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_empresa`) and (`jobdaily`.`job_movimientos_pension`.`documento_identidad_empleado` = `jobdaily`.`job_sucursal_contrato_empleados`.`documento_identidad_empleado`) and (`jobdaily`.`job_movimientos_pension`.`fecha_ingreso_empresa` = `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso`) and (`jobdaily`.`job_movimientos_pension`.`codigo_sucursal` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_sucursal`) and (`jobdaily`.`job_movimientos_pension`.`fecha_ingreso_sucursal` = `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso_sucursal`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`codigo_empresa` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_empresa`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`documento_identidad_empleado` = `jobdaily`.`job_sucursal_contrato_empleados`.`documento_identidad_empleado`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`fecha_ingreso` = `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`codigo_sucursal` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_sucursal`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`fecha_ingreso_sucursal` = `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso_sucursal`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`codigo_departamento_empresa` = `jobdaily`.`job_departamentos_empresa`.`codigo`) and (`jobdaily`.`job_movimientos_pension`.`codigo_transaccion_contable` = `jobdaily`.`job_transacciones_contables_empleado`.`codigo`))) union (select 4 AS `tabla`,_utf8'job_movimiento_tiempos_laborados' AS `nombre_tabla`,`jobdaily`.`job_movimiento_tiempos_laborados`.`ano_generacion` AS `ano_generacion`,`jobdaily`.`job_movimiento_tiempos_laborados`.`mes_generacion` AS `mes_generacion`,`jobdaily`.`job_movimiento_tiempos_laborados`.`codigo_planilla` AS `codigo_planilla`,`jobdaily`.`job_movimiento_tiempos_laborados`.`periodo_pago` AS `periodo_pago`,`jobdaily`.`job_movimiento_tiempos_laborados`.`codigo_transaccion_contable` AS `codigo_transaccion_contable`,`jobdaily`.`job_transacciones_contables_empleado`.`acumula_cesantias` AS `acumula_cesantias`,`jobdaily`.`job_transacciones_contables_empleado`.`acumula_prima` AS `acumula_prima`,`jobdaily`.`job_transacciones_contables_empleado`.`acumula_vacaciones` AS `acumula_vacaciones`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_salud` AS `ibc_salud`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_pension` AS `ibc_pension`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_arp` AS `ibc_arp`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_icbf` AS `ibc_icbf`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_caja_compensacion` AS `ibc_caja_compensacion`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_sena` AS `ibc_sena`,`jobdaily`.`job_transacciones_contables_empleado`.`codigo_concepto_transaccion_contable` AS `concepto_contable`,`jobdaily`.`job_transacciones_contables_empleado`.`columna_planilla` AS `columna_planilla`,`jobdaily`.`job_movimiento_tiempos_laborados`.`codigo_transaccion_tiempo` AS `codigo_transaccion_tiempo`,`jobdaily`.`job_transacciones_tiempo`.`resta_salario` AS `resta_salario`,`jobdaily`.`job_transacciones_tiempo`.`resta_auxilio_transporte` AS `resta_auxilio_transporte`,`jobdaily`.`job_transacciones_tiempo`.`resta_cesantias` AS `resta_cesantias`,`jobdaily`.`job_transacciones_tiempo`.`resta_prima` AS `resta_prima`,`jobdaily`.`job_transacciones_tiempo`.`resta_vacaciones` AS `resta_vacaciones`,`jobdaily`.`job_transacciones_tiempo`.`extras_empleado` AS `extras_empleado`,`jobdaily`.`job_conceptos_transacciones_tiempo`.`tipo` AS `concepto_tiempo`,(`jobdaily`.`job_movimiento_tiempos_laborados`.`documento_identidad_empleado` * 1) AS `documento_identidad_empleado`,`jobdaily`.`job_movimiento_tiempos_laborados`.`codigo_contable` AS `codigo_contable`,`jobdaily`.`job_movimiento_tiempos_laborados`.`sentido` AS `sentido`,`jobdaily`.`job_movimiento_tiempos_laborados`.`codigo_empresa_auxiliar` AS `codigo_empresa_auxiliar`,`jobdaily`.`job_movimiento_tiempos_laborados`.`codigo_anexo_contable` AS `codigo_anexo_contable`,`jobdaily`.`job_movimiento_tiempos_laborados`.`codigo_auxiliar_contable` AS `codigo_auxiliar_contable`,`jobdaily`.`job_movimiento_tiempos_laborados`.`valor_movimiento` AS `valor_movimiento`,0 AS `dias_trabajados`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),concat(concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`primer_apellido` is not null),concat(`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),concat(`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1''),_latin1'')),`jobdaily`.`job_terceros`.`razon_social`)) AS `nombre_empleado`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),concat(concat(`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),concat(`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1' '),_latin1' '),concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1'')),`jobdaily`.`job_terceros`.`razon_social`)) AS `apellido_empleado`,`jobdaily`.`job_departamentos_empresa`.`codigo` AS `codigo_departamento_empresa`,`jobdaily`.`job_departamentos_empresa`.`nombre` AS `departamento_empresa`,`jobdaily`.`job_sucursal_contrato_empleados`.`salario_mensual` AS `salario_empleado`,`jobdaily`.`job_sucursal_contrato_empleados`.`codigo_empresa` AS `codigo_empresa`,`jobdaily`.`job_sucursal_contrato_empleados`.`codigo_sucursal` AS `codigo_sucursal`,`jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso` AS `fecha_ingreso_empresa`,`jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso_sucursal` AS `fecha_ingreso_sucursal`,`jobdaily`.`job_movimiento_tiempos_laborados`.`contabilizado` AS `contabilizado`,`jobdaily`.`job_movimiento_tiempos_laborados`.`consecutivo` AS `consecutivo`,0 AS `fecha_incapacidad`,`jobdaily`.`job_movimiento_tiempos_laborados`.`fecha_generacion` AS `fecha_registro`,`jobdaily`.`job_movimiento_tiempos_laborados`.`fecha_pago_planilla` AS `fecha_pago_planilla`,_utf8'' AS `obligacion` from (((((((`jobdaily`.`job_movimiento_tiempos_laborados` join `jobdaily`.`job_departamento_seccion_contrato_empleado`) join `jobdaily`.`job_departamentos_empresa`) join `jobdaily`.`job_sucursal_contrato_empleados`) join `jobdaily`.`job_terceros`) join `jobdaily`.`job_transacciones_contables_empleado`) join `jobdaily`.`job_transacciones_tiempo`) join `jobdaily`.`job_conceptos_transacciones_tiempo`) where ((`jobdaily`.`job_movimiento_tiempos_laborados`.`documento_identidad_empleado` = `jobdaily`.`job_terceros`.`documento_identidad`) and (`jobdaily`.`job_movimiento_tiempos_laborados`.`codigo_empresa` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_empresa`) and (`jobdaily`.`job_movimiento_tiempos_laborados`.`documento_identidad_empleado` = `jobdaily`.`job_sucursal_contrato_empleados`.`documento_identidad_empleado`) and (`jobdaily`.`job_movimiento_tiempos_laborados`.`fecha_ingreso` = `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso`) and (`jobdaily`.`job_movimiento_tiempos_laborados`.`codigo_sucursal` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_sucursal`) and (`jobdaily`.`job_movimiento_tiempos_laborados`.`fecha_ingreso_sucursal` = `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso_sucursal`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`codigo_empresa` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_empresa`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`documento_identidad_empleado` = `jobdaily`.`job_sucursal_contrato_empleados`.`documento_identidad_empleado`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`fecha_ingreso` = `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`codigo_sucursal` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_sucursal`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`fecha_ingreso_sucursal` = `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso_sucursal`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`codigo_departamento_empresa` = `jobdaily`.`job_departamentos_empresa`.`codigo`) and (`jobdaily`.`job_movimiento_tiempos_laborados`.`codigo_transaccion_contable` = `jobdaily`.`job_transacciones_contables_empleado`.`codigo`) and (`jobdaily`.`job_movimiento_tiempos_laborados`.`codigo_transaccion_tiempo` = `jobdaily`.`job_transacciones_tiempo`.`codigo`) and (`jobdaily`.`job_conceptos_transacciones_tiempo`.`codigo` = `jobdaily`.`job_transacciones_tiempo`.`codigo_concepto_transaccion_tiempo`))) union (select 5 AS `tabla`,_utf8'job_reporte_incapacidades' AS `nombre_tabla`,`jobdaily`.`job_reporte_incapacidades`.`ano_generacion` AS `ano_generacion`,`jobdaily`.`job_reporte_incapacidades`.`mes_generacion` AS `mes_generacion`,`jobdaily`.`job_reporte_incapacidades`.`codigo_planilla` AS `codigo_planilla`,`jobdaily`.`job_reporte_incapacidades`.`periodo_pago` AS `periodo_pago`,`jobdaily`.`job_reporte_incapacidades`.`codigo_transaccion_contable` AS `codigo_transaccion_contable`,`jobdaily`.`job_transacciones_contables_empleado`.`acumula_cesantias` AS `acumula_cesantias`,`jobdaily`.`job_transacciones_contables_empleado`.`acumula_prima` AS `acumula_prima`,`jobdaily`.`job_transacciones_contables_empleado`.`acumula_vacaciones` AS `acumula_vacaciones`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_salud` AS `ibc_salud`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_pension` AS `ibc_pension`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_arp` AS `ibc_arp`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_icbf` AS `ibc_icbf`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_caja_compensacion` AS `ibc_caja_compensacion`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_sena` AS `ibc_sena`,`jobdaily`.`job_transacciones_contables_empleado`.`codigo_concepto_transaccion_contable` AS `concepto_contable`,`jobdaily`.`job_transacciones_contables_empleado`.`columna_planilla` AS `columna_planilla`,`jobdaily`.`job_reporte_incapacidades`.`codigo_transaccion_tiempo` AS `codigo_transaccion_tiempo`,`jobdaily`.`job_transacciones_tiempo`.`resta_salario` AS `resta_salario`,`jobdaily`.`job_transacciones_tiempo`.`resta_auxilio_transporte` AS `resta_auxilio_transporte`,`jobdaily`.`job_transacciones_tiempo`.`resta_cesantias` AS `resta_cesantias`,`jobdaily`.`job_transacciones_tiempo`.`resta_prima` AS `resta_prima`,`jobdaily`.`job_transacciones_tiempo`.`resta_vacaciones` AS `resta_vacaciones`,`jobdaily`.`job_transacciones_tiempo`.`extras_empleado` AS `extras_empleado`,`jobdaily`.`job_conceptos_transacciones_tiempo`.`tipo` AS `concepto_tiempo`,(`jobdaily`.`job_reporte_incapacidades`.`documento_identidad_empleado` * 1) AS `documento_identidad_empleado`,`jobdaily`.`job_reporte_incapacidades`.`codigo_contable` AS `codigo_contable`,`jobdaily`.`job_reporte_incapacidades`.`sentido` AS `sentido`,`jobdaily`.`job_reporte_incapacidades`.`codigo_empresa_auxiliar` AS `codigo_empresa_auxiliar`,`jobdaily`.`job_reporte_incapacidades`.`codigo_anexo_contable` AS `codigo_anexo_contable`,`jobdaily`.`job_reporte_incapacidades`.`codigo_auxiliar_contable` AS `codigo_auxiliar_contable`,`jobdaily`.`job_reporte_incapacidades`.`valor_movimiento` AS `valor_movimiento`,0 AS `dias_trabajados`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),concat(concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`primer_apellido` is not null),concat(`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),concat(`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1''),_latin1'')),`jobdaily`.`job_terceros`.`razon_social`)) AS `nombre_empleado`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),concat(concat(`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),concat(`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1' '),_latin1' '),concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1'')),`jobdaily`.`job_terceros`.`razon_social`)) AS `apellido_empleado`,`jobdaily`.`job_departamentos_empresa`.`codigo` AS `codigo_departamento_empresa`,`jobdaily`.`job_departamentos_empresa`.`nombre` AS `departamento_empresa`,`jobdaily`.`job_sucursal_contrato_empleados`.`salario_mensual` AS `salario_empleado`,`jobdaily`.`job_sucursal_contrato_empleados`.`codigo_empresa` AS `codigo_empresa`,`jobdaily`.`job_sucursal_contrato_empleados`.`codigo_sucursal` AS `codigo_sucursal`,`jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso` AS `fecha_ingreso_empresa`,`jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso_sucursal` AS `fecha_ingreso_sucursal`,`jobdaily`.`job_reporte_incapacidades`.`contabilizado` AS `contabilizado`,0 AS `consecutivo`,`jobdaily`.`job_reporte_incapacidades`.`fecha_incapacidad` AS `fecha_incapacidad`,`jobdaily`.`job_reporte_incapacidades`.`fecha_registro` AS `fecha_registro`,`jobdaily`.`job_reporte_incapacidades`.`fecha_pago_planilla` AS `fecha_pago_planilla`,_utf8'' AS `obligacion` from (((((((`jobdaily`.`job_reporte_incapacidades` join `jobdaily`.`job_departamento_seccion_contrato_empleado`) join `jobdaily`.`job_departamentos_empresa`) join `jobdaily`.`job_sucursal_contrato_empleados`) join `jobdaily`.`job_terceros`) join `jobdaily`.`job_transacciones_contables_empleado`) join `jobdaily`.`job_transacciones_tiempo`) join `jobdaily`.`job_conceptos_transacciones_tiempo`) where ((`jobdaily`.`job_reporte_incapacidades`.`documento_identidad_empleado` = `jobdaily`.`job_terceros`.`documento_identidad`) and (`jobdaily`.`job_reporte_incapacidades`.`codigo_empresa` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_empresa`) and (`jobdaily`.`job_reporte_incapacidades`.`documento_identidad_empleado` = `jobdaily`.`job_sucursal_contrato_empleados`.`documento_identidad_empleado`) and (`jobdaily`.`job_reporte_incapacidades`.`fecha_ingreso` = `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso`) and (`jobdaily`.`job_reporte_incapacidades`.`codigo_sucursal` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_sucursal`) and (`jobdaily`.`job_reporte_incapacidades`.`fecha_ingreso_sucursal` = `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso_sucursal`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`codigo_empresa` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_empresa`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`documento_identidad_empleado` = `jobdaily`.`job_sucursal_contrato_empleados`.`documento_identidad_empleado`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`fecha_ingreso` = `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`codigo_sucursal` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_sucursal`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`fecha_ingreso_sucursal` = `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso_sucursal`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`codigo_departamento_empresa` = `jobdaily`.`job_departamentos_empresa`.`codigo`) and (`jobdaily`.`job_reporte_incapacidades`.`codigo_transaccion_contable` = `jobdaily`.`job_transacciones_contables_empleado`.`codigo`) and (`jobdaily`.`job_reporte_incapacidades`.`codigo_transaccion_tiempo` = `jobdaily`.`job_transacciones_tiempo`.`codigo`) and (`jobdaily`.`job_conceptos_transacciones_tiempo`.`codigo` = `jobdaily`.`job_transacciones_tiempo`.`codigo_concepto_transaccion_tiempo`))) union (select 6 AS `tabla`,_utf8'job_movimientos_auxilio_transporte' AS `nombre_tabla`,`jobdaily`.`job_movimientos_auxilio_transporte`.`ano_generacion` AS `ano_generacion`,`jobdaily`.`job_movimientos_auxilio_transporte`.`mes_generacion` AS `mes_generacion`,`jobdaily`.`job_movimientos_auxilio_transporte`.`codigo_planilla` AS `codigo_planilla`,`jobdaily`.`job_movimientos_auxilio_transporte`.`periodo_pago` AS `periodo_pago`,`jobdaily`.`job_movimientos_auxilio_transporte`.`codigo_transaccion_contable` AS `codigo_transaccion_contable`,`jobdaily`.`job_transacciones_contables_empleado`.`acumula_cesantias` AS `acumula_cesantias`,`jobdaily`.`job_transacciones_contables_empleado`.`acumula_prima` AS `acumula_prima`,`jobdaily`.`job_transacciones_contables_empleado`.`acumula_vacaciones` AS `acumula_vacaciones`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_salud` AS `ibc_salud`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_pension` AS `ibc_pension`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_arp` AS `ibc_arp`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_icbf` AS `ibc_icbf`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_caja_compensacion` AS `ibc_caja_compensacion`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_sena` AS `ibc_sena`,`jobdaily`.`job_transacciones_contables_empleado`.`codigo_concepto_transaccion_contable` AS `concepto_contable`,`jobdaily`.`job_transacciones_contables_empleado`.`columna_planilla` AS `columna_planilla`,0 AS `codigo_transaccion_tiempo`,0 AS `resta_salario`,0 AS `resta_auxilio_transporte`,0 AS `resta_cesantias`,0 AS `resta_prima`,0 AS `resta_vacaciones`,0 AS `extras_empleado`,0 AS `concepto_tiempo`,(`jobdaily`.`job_movimientos_auxilio_transporte`.`documento_identidad_empleado` * 1) AS `documento_identidad_empleado`,`jobdaily`.`job_movimientos_auxilio_transporte`.`codigo_contable` AS `codigo_contable`,`jobdaily`.`job_movimientos_auxilio_transporte`.`sentido` AS `sentido`,`jobdaily`.`job_movimientos_auxilio_transporte`.`codigo_empresa` AS `codigo_empresa_auxiliar`,`jobdaily`.`job_movimientos_auxilio_transporte`.`codigo_anexo_contable` AS `codigo_anexo_contable`,`jobdaily`.`job_movimientos_auxilio_transporte`.`codigo_auxiliar_contable` AS `codigo_auxiliar_contable`,`jobdaily`.`job_movimientos_auxilio_transporte`.`valor_movimiento` AS `valor_movimiento`,0 AS `dias_trabajados`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),concat(concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`primer_apellido` is not null),concat(`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),concat(`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1''),_latin1'')),`jobdaily`.`job_terceros`.`razon_social`)) AS `nombre_empleado`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),concat(concat(`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),concat(`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1' '),_latin1' '),concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1'')),`jobdaily`.`job_terceros`.`razon_social`)) AS `apellido_empleado`,`jobdaily`.`job_departamentos_empresa`.`codigo` AS `codigo_departamento_empresa`,`jobdaily`.`job_departamentos_empresa`.`nombre` AS `departamento_empresa`,`jobdaily`.`job_sucursal_contrato_empleados`.`salario_mensual` AS `salario_empleado`,`jobdaily`.`job_sucursal_contrato_empleados`.`codigo_empresa` AS `codigo_empresa`,`jobdaily`.`job_sucursal_contrato_empleados`.`codigo_sucursal` AS `codigo_sucursal`,`jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso` AS `fecha_ingreso_empresa`,`jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso_sucursal` AS `fecha_ingreso_sucursal`,`jobdaily`.`job_movimientos_auxilio_transporte`.`contabilizado` AS `contabilizado`,0 AS `consecutivo`,0 AS `fecha_incapacidad`,`jobdaily`.`job_movimientos_auxilio_transporte`.`fecha_ingreso_planilla` AS `fecha_registro`,`jobdaily`.`job_movimientos_auxilio_transporte`.`fecha_pago_planilla` AS `fecha_pago_planilla`,_utf8'' AS `obligacion` from (((((`jobdaily`.`job_movimientos_auxilio_transporte` join `jobdaily`.`job_departamento_seccion_contrato_empleado`) join `jobdaily`.`job_departamentos_empresa`) join `jobdaily`.`job_sucursal_contrato_empleados`) join `jobdaily`.`job_terceros`) join `jobdaily`.`job_transacciones_contables_empleado`) where ((`jobdaily`.`job_movimientos_auxilio_transporte`.`documento_identidad_empleado` = `jobdaily`.`job_terceros`.`documento_identidad`) and (`jobdaily`.`job_movimientos_auxilio_transporte`.`codigo_empresa` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_empresa`) and (`jobdaily`.`job_movimientos_auxilio_transporte`.`documento_identidad_empleado` = `jobdaily`.`job_sucursal_contrato_empleados`.`documento_identidad_empleado`) and (`jobdaily`.`job_movimientos_auxilio_transporte`.`fecha_ingreso_empresa` = `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso`) and (`jobdaily`.`job_movimientos_auxilio_transporte`.`codigo_sucursal` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_sucursal`) and (`jobdaily`.`job_movimientos_auxilio_transporte`.`fecha_ingreso_sucursal` = `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso_sucursal`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`codigo_empresa` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_empresa`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`documento_identidad_empleado` = `jobdaily`.`job_sucursal_contrato_empleados`.`documento_identidad_empleado`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`fecha_ingreso` = `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`codigo_sucursal` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_sucursal`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`fecha_ingreso_sucursal` = `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso_sucursal`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`codigo_departamento_empresa` = `jobdaily`.`job_departamentos_empresa`.`codigo`) and (`jobdaily`.`job_movimientos_auxilio_transporte`.`codigo_transaccion_contable` = `jobdaily`.`job_transacciones_contables_empleado`.`codigo`))) union (select 7 AS `tabla`,_utf8'job_movimiento_control_prestamos_empleados' AS `nombre_tabla`,`jobdaily`.`job_movimiento_control_prestamos_empleados`.`ano_generacion` AS `ano_generacion`,`jobdaily`.`job_movimiento_control_prestamos_empleados`.`mes_generacion` AS `mes_generacion`,`jobdaily`.`job_movimiento_control_prestamos_empleados`.`codigo_planilla` AS `codigo_planilla`,`jobdaily`.`job_movimiento_control_prestamos_empleados`.`periodo_pago` AS `periodo_pago`,`jobdaily`.`job_movimiento_control_prestamos_empleados`.`codigo_transaccion_contable` AS `codigo_transaccion_contable`,`jobdaily`.`job_transacciones_contables_empleado`.`acumula_cesantias` AS `acumula_cesantias`,`jobdaily`.`job_transacciones_contables_empleado`.`acumula_prima` AS `acumula_prima`,`jobdaily`.`job_transacciones_contables_empleado`.`acumula_vacaciones` AS `acumula_vacaciones`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_salud` AS `ibc_salud`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_pension` AS `ibc_pension`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_arp` AS `ibc_arp`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_icbf` AS `ibc_icbf`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_caja_compensacion` AS `ibc_caja_compensacion`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_sena` AS `ibc_sena`,`jobdaily`.`job_transacciones_contables_empleado`.`codigo_concepto_transaccion_contable` AS `concepto_contable`,`jobdaily`.`job_transacciones_contables_empleado`.`columna_planilla` AS `columna_planilla`,0 AS `codigo_transaccion_tiempo`,0 AS `resta_salario`,0 AS `resta_auxilio_transporte`,0 AS `resta_cesantias`,0 AS `resta_prima`,0 AS `resta_vacaciones`,0 AS `extras_empleado`,0 AS `concepto_tiempo`,(`jobdaily`.`job_movimiento_control_prestamos_empleados`.`documento_identidad_empleado` * 1) AS `documento_identidad_empleado`,`jobdaily`.`job_movimiento_control_prestamos_empleados`.`codigo_contable` AS `codigo_contable`,`jobdaily`.`job_movimiento_control_prestamos_empleados`.`sentido` AS `sentido`,`jobdaily`.`job_movimiento_control_prestamos_empleados`.`codigo_empresa_auxiliar` AS `codigo_empresa_auxiliar`,`jobdaily`.`job_movimiento_control_prestamos_empleados`.`codigo_anexo_contable` AS `codigo_anexo_contable`,`jobdaily`.`job_movimiento_control_prestamos_empleados`.`codigo_auxiliar_contable` AS `codigo_auxiliar_contable`,`jobdaily`.`job_movimiento_control_prestamos_empleados`.`valor_descuento` AS `valor_movimiento`,0 AS `dias_trabajados`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),concat(concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`primer_apellido` is not null),concat(`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),concat(`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1''),_latin1'')),`jobdaily`.`job_terceros`.`razon_social`)) AS `nombre_empleado`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),concat(concat(`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),concat(`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1' '),_latin1' '),concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1'')),`jobdaily`.`job_terceros`.`razon_social`)) AS `apellido_empleado`,`jobdaily`.`job_departamentos_empresa`.`codigo` AS `codigo_departamento_empresa`,`jobdaily`.`job_departamentos_empresa`.`nombre` AS `departamento_empresa`,`jobdaily`.`job_sucursal_contrato_empleados`.`salario_mensual` AS `salario_empleado`,`jobdaily`.`job_sucursal_contrato_empleados`.`codigo_empresa` AS `codigo_empresa`,`jobdaily`.`job_sucursal_contrato_empleados`.`codigo_sucursal` AS `codigo_sucursal`,`jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso` AS `fecha_ingreso_empresa`,`jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso_sucursal` AS `fecha_ingreso_sucursal`,`jobdaily`.`job_movimiento_control_prestamos_empleados`.`contabilizado` AS `contabilizado`,0 AS `consecutivo`,0 AS `fecha_incapacidad`,`jobdaily`.`job_movimiento_control_prestamos_empleados`.`fecha_generacion` AS `fecha_registro`,`jobdaily`.`job_movimiento_control_prestamos_empleados`.`fecha_pago_planilla` AS `fecha_pago_planilla`,_utf8'' AS `obligacion` from (((((`jobdaily`.`job_movimiento_control_prestamos_empleados` join `jobdaily`.`job_departamento_seccion_contrato_empleado`) join `jobdaily`.`job_departamentos_empresa`) join `jobdaily`.`job_sucursal_contrato_empleados`) join `jobdaily`.`job_terceros`) join `jobdaily`.`job_transacciones_contables_empleado`) where ((`jobdaily`.`job_movimiento_control_prestamos_empleados`.`documento_identidad_empleado` = `jobdaily`.`job_terceros`.`documento_identidad`) and (`jobdaily`.`job_movimiento_control_prestamos_empleados`.`codigo_empresa` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_empresa`) and (`jobdaily`.`job_movimiento_control_prestamos_empleados`.`documento_identidad_empleado` = `jobdaily`.`job_sucursal_contrato_empleados`.`documento_identidad_empleado`) and (`jobdaily`.`job_movimiento_control_prestamos_empleados`.`fecha_ingreso` = `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso`) and (`jobdaily`.`job_movimiento_control_prestamos_empleados`.`codigo_sucursal` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_sucursal`) and (`jobdaily`.`job_movimiento_control_prestamos_empleados`.`fecha_ingreso_sucursal` = `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso_sucursal`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`codigo_empresa` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_empresa`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`documento_identidad_empleado` = `jobdaily`.`job_sucursal_contrato_empleados`.`documento_identidad_empleado`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`fecha_ingreso` = `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`codigo_sucursal` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_sucursal`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`fecha_ingreso_sucursal` = `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso_sucursal`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`codigo_departamento_empresa` = `jobdaily`.`job_departamentos_empresa`.`codigo`) and (`jobdaily`.`job_movimiento_control_prestamos_empleados`.`codigo_transaccion_contable` = `jobdaily`.`job_transacciones_contables_empleado`.`codigo`))) union (select 8 AS `tabla`,_utf8'job_movimiento_novedades_manuales' AS `nombre_tabla`,`jobdaily`.`job_movimiento_novedades_manuales`.`ano_generacion` AS `ano_generacion`,`jobdaily`.`job_movimiento_novedades_manuales`.`mes_generacion` AS `mes_generacion`,`jobdaily`.`job_movimiento_novedades_manuales`.`codigo_planilla` AS `codigo_planilla`,`jobdaily`.`job_movimiento_novedades_manuales`.`periodo_pago` AS `periodo_pago`,`jobdaily`.`job_movimiento_novedades_manuales`.`codigo_transaccion_contable` AS `codigo_transaccion_contable`,`jobdaily`.`job_transacciones_contables_empleado`.`acumula_cesantias` AS `acumula_cesantias`,`jobdaily`.`job_transacciones_contables_empleado`.`acumula_prima` AS `acumula_prima`,`jobdaily`.`job_transacciones_contables_empleado`.`acumula_vacaciones` AS `acumula_vacaciones`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_salud` AS `ibc_salud`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_pension` AS `ibc_pension`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_arp` AS `ibc_arp`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_icbf` AS `ibc_icbf`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_caja_compensacion` AS `ibc_caja_compensacion`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_sena` AS `ibc_sena`,`jobdaily`.`job_transacciones_contables_empleado`.`codigo_concepto_transaccion_contable` AS `concepto_contable`,`jobdaily`.`job_transacciones_contables_empleado`.`columna_planilla` AS `columna_planilla`,0 AS `codigo_transaccion_tiempo`,0 AS `resta_salario`,0 AS `resta_auxilio_transporte`,0 AS `resta_cesantias`,0 AS `resta_prima`,0 AS `resta_vacaciones`,0 AS `extras_empleado`,0 AS `concepto_tiempo`,(`jobdaily`.`job_movimiento_novedades_manuales`.`documento_identidad_empleado` * 1) AS `documento_identidad_empleado`,`jobdaily`.`job_movimiento_novedades_manuales`.`codigo_contable` AS `codigo_contable`,`jobdaily`.`job_movimiento_novedades_manuales`.`sentido` AS `sentido`,`jobdaily`.`job_movimiento_novedades_manuales`.`codigo_empresa_auxiliar` AS `codigo_empresa_auxiliar`,`jobdaily`.`job_movimiento_novedades_manuales`.`codigo_anexo_contable` AS `codigo_anexo_contable`,`jobdaily`.`job_movimiento_novedades_manuales`.`codigo_auxiliar_contable` AS `codigo_auxiliar_contable`,`jobdaily`.`job_movimiento_novedades_manuales`.`valor_movimiento` AS `valor_movimiento`,0 AS `dias_trabajados`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),concat(concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`primer_apellido` is not null),concat(`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),concat(`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1''),_latin1'')),`jobdaily`.`job_terceros`.`razon_social`)) AS `nombre_empleado`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),concat(concat(`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),concat(`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1' '),_latin1' '),concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1'')),`jobdaily`.`job_terceros`.`razon_social`)) AS `apellido_empleado`,`jobdaily`.`job_departamentos_empresa`.`codigo` AS `codigo_departamento_empresa`,`jobdaily`.`job_departamentos_empresa`.`nombre` AS `departamento_empresa`,`jobdaily`.`job_sucursal_contrato_empleados`.`salario_mensual` AS `salario_empleado`,`jobdaily`.`job_sucursal_contrato_empleados`.`codigo_empresa` AS `codigo_empresa`,`jobdaily`.`job_sucursal_contrato_empleados`.`codigo_sucursal` AS `codigo_sucursal`,`jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso` AS `fecha_ingreso_empresa`,`jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso_sucursal` AS `fecha_ingreso_sucursal`,`jobdaily`.`job_movimiento_novedades_manuales`.`contabilizado` AS `contabilizado`,0 AS `consecutivo`,0 AS `fecha_incapacidad`,`jobdaily`.`job_movimiento_novedades_manuales`.`fecha_generacion` AS `fecha_registro`,`jobdaily`.`job_movimiento_novedades_manuales`.`fecha_pago_planilla` AS `fecha_pago_planilla`,_utf8'' AS `obligacion` from (((((`jobdaily`.`job_movimiento_novedades_manuales` join `jobdaily`.`job_departamento_seccion_contrato_empleado`) join `jobdaily`.`job_departamentos_empresa`) join `jobdaily`.`job_sucursal_contrato_empleados`) join `jobdaily`.`job_terceros`) join `jobdaily`.`job_transacciones_contables_empleado`) where ((`jobdaily`.`job_movimiento_novedades_manuales`.`documento_identidad_empleado` = `jobdaily`.`job_terceros`.`documento_identidad`) and (`jobdaily`.`job_movimiento_novedades_manuales`.`codigo_empresa` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_empresa`) and (`jobdaily`.`job_movimiento_novedades_manuales`.`documento_identidad_empleado` = `jobdaily`.`job_sucursal_contrato_empleados`.`documento_identidad_empleado`) and (`jobdaily`.`job_movimiento_novedades_manuales`.`fecha_ingreso` = `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso`) and (`jobdaily`.`job_movimiento_novedades_manuales`.`codigo_sucursal` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_sucursal`) and (`jobdaily`.`job_movimiento_novedades_manuales`.`fecha_ingreso_sucursal` = `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso_sucursal`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`codigo_empresa` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_empresa`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`documento_identidad_empleado` = `jobdaily`.`job_sucursal_contrato_empleados`.`documento_identidad_empleado`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`fecha_ingreso` = `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`codigo_sucursal` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_sucursal`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`fecha_ingreso_sucursal` = `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso_sucursal`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`codigo_departamento_empresa` = `jobdaily`.`job_departamentos_empresa`.`codigo`) and (`jobdaily`.`job_movimiento_novedades_manuales`.`codigo_transaccion_contable` = `jobdaily`.`job_transacciones_contables_empleado`.`codigo`))) union (select 9 AS `tabla`,_utf8'job_movimiento_cuenta_por_cobrar_descuento' AS `nombre_tabla`,`jobdaily`.`job_movimiento_cuenta_por_cobrar_descuento`.`ano_generacion` AS `ano_generacion`,`jobdaily`.`job_movimiento_cuenta_por_cobrar_descuento`.`mes_generacion` AS `mes_generacion`,`jobdaily`.`job_movimiento_cuenta_por_cobrar_descuento`.`codigo_planilla` AS `codigo_planilla`,`jobdaily`.`job_movimiento_cuenta_por_cobrar_descuento`.`periodo_pago` AS `periodo_pago`,`jobdaily`.`job_movimiento_cuenta_por_pagar_tercero`.`codigo_transaccion_contable` AS `codigo_transaccion_contable`,`jobdaily`.`job_transacciones_contables_empleado`.`acumula_cesantias` AS `acumula_cesantias`,`jobdaily`.`job_transacciones_contables_empleado`.`acumula_prima` AS `acumula_prima`,`jobdaily`.`job_transacciones_contables_empleado`.`acumula_vacaciones` AS `acumula_vacaciones`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_salud` AS `ibc_salud`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_pension` AS `ibc_pension`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_arp` AS `ibc_arp`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_icbf` AS `ibc_icbf`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_caja_compensacion` AS `ibc_caja_compensacion`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_sena` AS `ibc_sena`,`jobdaily`.`job_transacciones_contables_empleado`.`codigo_concepto_transaccion_contable` AS `concepto_contable`,`jobdaily`.`job_transacciones_contables_empleado`.`columna_planilla` AS `columna_planilla`,0 AS `codigo_transaccion_tiempo`,0 AS `resta_salario`,0 AS `resta_auxilio_transporte`,0 AS `resta_cesantias`,0 AS `resta_prima`,0 AS `resta_vacaciones`,0 AS `extras_empleado`,0 AS `concepto_tiempo`,(`jobdaily`.`job_movimiento_cuenta_por_cobrar_descuento`.`documento_identidad_empleado` * 1) AS `documento_identidad_empleado`,`jobdaily`.`job_movimiento_cuenta_por_cobrar_descuento`.`codigo_contable` AS `codigo_contable`,`jobdaily`.`job_movimiento_cuenta_por_cobrar_descuento`.`sentido` AS `sentido`,0 AS `codigo_empresa_auxiliar`,_utf8'' AS `codigo_anexo_contable`,0 AS `codigo_auxiliar_contable`,`jobdaily`.`job_movimiento_cuenta_por_cobrar_descuento`.`valor_movimiento` AS `valor_movimiento`,0 AS `dias_trabajados`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),concat(concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`primer_apellido` is not null),concat(`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),concat(`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1''),_latin1'')),`jobdaily`.`job_terceros`.`razon_social`)) AS `nombre_empleado`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),concat(concat(`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),concat(`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1' '),_latin1' '),concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1'')),`jobdaily`.`job_terceros`.`razon_social`)) AS `apellido_empleado`,`jobdaily`.`job_departamentos_empresa`.`codigo` AS `codigo_departamento_empresa`,`jobdaily`.`job_departamentos_empresa`.`nombre` AS `departamento_empresa`,`jobdaily`.`job_sucursal_contrato_empleados`.`salario_mensual` AS `salario_empleado`,`jobdaily`.`job_sucursal_contrato_empleados`.`codigo_empresa` AS `codigo_empresa`,`jobdaily`.`job_sucursal_contrato_empleados`.`codigo_sucursal` AS `codigo_sucursal`,`jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso` AS `fecha_ingreso_empresa`,`jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso_sucursal` AS `fecha_ingreso_sucursal`,`jobdaily`.`job_movimiento_cuenta_por_cobrar_descuento`.`contabilizado` AS `contabilizado`,0 AS `consecutivo`,0 AS `fecha_incapacidad`,`jobdaily`.`job_movimiento_cuenta_por_cobrar_descuento`.`fecha_generacion` AS `fecha_registro`,`jobdaily`.`job_movimiento_cuenta_por_cobrar_descuento`.`fecha_pago_planilla` AS `fecha_pago_planilla`,`jobdaily`.`job_movimiento_cuenta_por_cobrar_descuento`.`obligacion` AS `obligacion` from ((((((`jobdaily`.`job_movimiento_cuenta_por_cobrar_descuento` join `jobdaily`.`job_departamento_seccion_contrato_empleado`) join `jobdaily`.`job_departamentos_empresa`) join `jobdaily`.`job_sucursal_contrato_empleados`) join `jobdaily`.`job_terceros`) join `jobdaily`.`job_transacciones_contables_empleado`) join `jobdaily`.`job_movimiento_cuenta_por_pagar_tercero`) where ((`jobdaily`.`job_movimiento_cuenta_por_cobrar_descuento`.`documento_identidad_empleado` = `jobdaily`.`job_terceros`.`documento_identidad`) and (`jobdaily`.`job_movimiento_cuenta_por_cobrar_descuento`.`codigo_empresa` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_empresa`) and (`jobdaily`.`job_movimiento_cuenta_por_cobrar_descuento`.`documento_identidad_empleado` = `jobdaily`.`job_sucursal_contrato_empleados`.`documento_identidad_empleado`) and (`jobdaily`.`job_movimiento_cuenta_por_cobrar_descuento`.`codigo_sucursal` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_sucursal`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`codigo_empresa` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_empresa`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`documento_identidad_empleado` = `jobdaily`.`job_sucursal_contrato_empleados`.`documento_identidad_empleado`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`fecha_ingreso` = `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`codigo_sucursal` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_sucursal`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`fecha_ingreso_sucursal` = `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso_sucursal`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`codigo_departamento_empresa` = `jobdaily`.`job_departamentos_empresa`.`codigo`) and (`jobdaily`.`job_movimiento_cuenta_por_pagar_tercero`.`codigo_transaccion_contable` = `jobdaily`.`job_transacciones_contables_empleado`.`codigo`) and (`jobdaily`.`job_movimiento_cuenta_por_pagar_tercero`.`codigo_empresa` = `jobdaily`.`job_movimiento_cuenta_por_cobrar_descuento`.`codigo_empresa`) and (`jobdaily`.`job_movimiento_cuenta_por_pagar_tercero`.`documento_identidad_empleado` = `jobdaily`.`job_movimiento_cuenta_por_cobrar_descuento`.`documento_identidad_empleado`) and (`jobdaily`.`job_movimiento_cuenta_por_pagar_tercero`.`obligacion` = `jobdaily`.`job_movimiento_cuenta_por_cobrar_descuento`.`obligacion`))) union (select 10 AS `tabla`,_utf8'job_movimiento_tiempos_no_laborados_dias' AS `nombre_tabla`,`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`ano_generacion` AS `ano_generacion`,`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`mes_generacion` AS `mes_generacion`,`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`codigo_planilla` AS `codigo_planilla`,`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`periodo_pago` AS `periodo_pago`,`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`codigo_transaccion_contable` AS `codigo_transaccion_contable`,`jobdaily`.`job_transacciones_contables_empleado`.`acumula_cesantias` AS `acumula_cesantias`,`jobdaily`.`job_transacciones_contables_empleado`.`acumula_prima` AS `acumula_prima`,`jobdaily`.`job_transacciones_contables_empleado`.`acumula_vacaciones` AS `acumula_vacaciones`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_salud` AS `ibc_salud`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_pension` AS `ibc_pension`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_arp` AS `ibc_arp`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_icbf` AS `ibc_icbf`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_caja_compensacion` AS `ibc_caja_compensacion`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_sena` AS `ibc_sena`,`jobdaily`.`job_transacciones_contables_empleado`.`codigo_concepto_transaccion_contable` AS `concepto_contable`,`jobdaily`.`job_transacciones_contables_empleado`.`columna_planilla` AS `columna_planilla`,`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`codigo_transaccion_tiempo` AS `codigo_transaccion_tiempo`,`jobdaily`.`job_transacciones_tiempo`.`resta_salario` AS `resta_salario`,`jobdaily`.`job_transacciones_tiempo`.`resta_auxilio_transporte` AS `resta_auxilio_transporte`,`jobdaily`.`job_transacciones_tiempo`.`resta_cesantias` AS `resta_cesantias`,`jobdaily`.`job_transacciones_tiempo`.`resta_prima` AS `resta_prima`,`jobdaily`.`job_transacciones_tiempo`.`resta_vacaciones` AS `resta_vacaciones`,`jobdaily`.`job_transacciones_tiempo`.`extras_empleado` AS `extras_empleado`,`jobdaily`.`job_conceptos_transacciones_tiempo`.`tipo` AS `concepto_tiempo`,(`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`documento_identidad_empleado` * 1) AS `documento_identidad_empleado`,`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`codigo_contable` AS `codigo_contable`,`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`sentido` AS `sentido`,`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`codigo_empresa_auxiliar` AS `codigo_empresa_auxiliar`,`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`codigo_anexo_contable` AS `codigo_anexo_contable`,`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`codigo_auxiliar_contable` AS `codigo_auxiliar_contable`,`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`valor_dia` AS `valor_movimiento`,0 AS `dias_trabajados`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),concat(concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`primer_apellido` is not null),concat(`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),concat(`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1''),_latin1'')),`jobdaily`.`job_terceros`.`razon_social`)) AS `nombre_empleado`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),concat(concat(`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),concat(`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1' '),_latin1' '),concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1'')),`jobdaily`.`job_terceros`.`razon_social`)) AS `apellido_empleado`,`jobdaily`.`job_departamentos_empresa`.`codigo` AS `codigo_departamento_empresa`,`jobdaily`.`job_departamentos_empresa`.`nombre` AS `departamento_empresa`,`jobdaily`.`job_sucursal_contrato_empleados`.`salario_mensual` AS `salario_empleado`,`jobdaily`.`job_sucursal_contrato_empleados`.`codigo_empresa` AS `codigo_empresa`,`jobdaily`.`job_sucursal_contrato_empleados`.`codigo_sucursal` AS `codigo_sucursal`,`jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso` AS `fecha_ingreso_empresa`,`jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso_sucursal` AS `fecha_ingreso_sucursal`,`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`contabilizado` AS `contabilizado`,0 AS `consecutivo`,`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`fecha_tiempo` AS `fecha_incapacidad`,`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`fecha_generacion` AS `fecha_registro`,`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`fecha_pago_planilla` AS `fecha_pago_planilla`,_utf8'' AS `obligacion` from (((((((`jobdaily`.`job_movimiento_tiempos_no_laborados_dias` join `jobdaily`.`job_departamento_seccion_contrato_empleado`) join `jobdaily`.`job_departamentos_empresa`) join `jobdaily`.`job_sucursal_contrato_empleados`) join `jobdaily`.`job_terceros`) join `jobdaily`.`job_transacciones_contables_empleado`) join `jobdaily`.`job_transacciones_tiempo`) join `jobdaily`.`job_conceptos_transacciones_tiempo`) where ((`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`documento_identidad_empleado` = `jobdaily`.`job_terceros`.`documento_identidad`) and (`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`codigo_empresa` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_empresa`) and (`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`documento_identidad_empleado` = `jobdaily`.`job_sucursal_contrato_empleados`.`documento_identidad_empleado`) and (`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`fecha_ingreso` = `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso`) and (`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`codigo_sucursal` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_sucursal`) and (`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`fecha_ingreso_sucursal` = `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso_sucursal`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`codigo_empresa` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_empresa`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`documento_identidad_empleado` = `jobdaily`.`job_sucursal_contrato_empleados`.`documento_identidad_empleado`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`fecha_ingreso` = `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`codigo_sucursal` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_sucursal`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`fecha_ingreso_sucursal` = `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso_sucursal`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`codigo_departamento_empresa` = `jobdaily`.`job_departamentos_empresa`.`codigo`) and (`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`codigo_transaccion_contable` = `jobdaily`.`job_transacciones_contables_empleado`.`codigo`) and (`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`codigo_transaccion_tiempo` = `jobdaily`.`job_transacciones_tiempo`.`codigo`) and (`jobdaily`.`job_conceptos_transacciones_tiempo`.`codigo` = `jobdaily`.`job_transacciones_tiempo`.`codigo_concepto_transaccion_tiempo`))) union (select 11 AS `tabla`,_utf8'movimiento_tiempos_no_laborados_horas' AS `nombre_tabla`,`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`ano_generacion` AS `ano_generacion`,`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`mes_generacion` AS `mes_generacion`,`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`codigo_planilla` AS `codigo_planilla`,`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`periodo_pago` AS `periodo_pago`,`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`codigo_transaccion_contable` AS `codigo_transaccion_contable`,`jobdaily`.`job_transacciones_contables_empleado`.`acumula_cesantias` AS `acumula_cesantias`,`jobdaily`.`job_transacciones_contables_empleado`.`acumula_prima` AS `acumula_prima`,`jobdaily`.`job_transacciones_contables_empleado`.`acumula_vacaciones` AS `acumula_vacaciones`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_salud` AS `ibc_salud`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_pension` AS `ibc_pension`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_arp` AS `ibc_arp`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_icbf` AS `ibc_icbf`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_caja_compensacion` AS `ibc_caja_compensacion`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_sena` AS `ibc_sena`,`jobdaily`.`job_transacciones_contables_empleado`.`codigo_concepto_transaccion_contable` AS `concepto_contable`,`jobdaily`.`job_transacciones_contables_empleado`.`columna_planilla` AS `columna_planilla`,`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`codigo_transaccion_tiempo` AS `codigo_transaccion_tiempo`,`jobdaily`.`job_transacciones_tiempo`.`resta_salario` AS `resta_salario`,`jobdaily`.`job_transacciones_tiempo`.`resta_auxilio_transporte` AS `resta_auxilio_transporte`,`jobdaily`.`job_transacciones_tiempo`.`resta_cesantias` AS `resta_cesantias`,`jobdaily`.`job_transacciones_tiempo`.`resta_prima` AS `resta_prima`,`jobdaily`.`job_transacciones_tiempo`.`resta_vacaciones` AS `resta_vacaciones`,`jobdaily`.`job_transacciones_tiempo`.`extras_empleado` AS `extras_empleado`,`jobdaily`.`job_conceptos_transacciones_tiempo`.`tipo` AS `concepto_tiempo`,(`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`documento_identidad_empleado` * 1) AS `documento_identidad_empleado`,`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`codigo_contable` AS `codigo_contable`,`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`sentido` AS `sentido`,`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`codigo_empresa_auxiliar` AS `codigo_empresa_auxiliar`,`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`codigo_anexo_contable` AS `codigo_anexo_contable`,`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`codigo_auxiliar_contable` AS `codigo_auxiliar_contable`,`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`valor_movimiento` AS `valor_movimiento`,0 AS `dias_trabajados`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),concat(concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`primer_apellido` is not null),concat(`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),concat(`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1''),_latin1'')),`jobdaily`.`job_terceros`.`razon_social`)) AS `nombre_empleado`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),concat(concat(`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),concat(`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1' '),_latin1' '),concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1'')),`jobdaily`.`job_terceros`.`razon_social`)) AS `apellido_empleado`,`jobdaily`.`job_departamentos_empresa`.`codigo` AS `codigo_departamento_empresa`,`jobdaily`.`job_departamentos_empresa`.`nombre` AS `departamento_empresa`,`jobdaily`.`job_sucursal_contrato_empleados`.`salario_mensual` AS `salario_empleado`,`jobdaily`.`job_sucursal_contrato_empleados`.`codigo_empresa` AS `codigo_empresa`,`jobdaily`.`job_sucursal_contrato_empleados`.`codigo_sucursal` AS `codigo_sucursal`,`jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso` AS `fecha_ingreso_empresa`,`jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso_sucursal` AS `fecha_ingreso_sucursal`,`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`contabilizado` AS `contabilizado`,0 AS `consecutivo`,`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`fecha_registro` AS `fecha_incapacidad`,`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`fecha_generacion` AS `fecha_registro`,`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`fecha_pago_planilla` AS `fecha_pago_planilla`,_utf8'' AS `obligacion` from (((((((`jobdaily`.`job_movimiento_tiempos_no_laborados_horas` join `jobdaily`.`job_departamento_seccion_contrato_empleado`) join `jobdaily`.`job_departamentos_empresa`) join `jobdaily`.`job_sucursal_contrato_empleados`) join `jobdaily`.`job_terceros`) join `jobdaily`.`job_transacciones_contables_empleado`) join `jobdaily`.`job_transacciones_tiempo`) join `jobdaily`.`job_conceptos_transacciones_tiempo`) where ((`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`documento_identidad_empleado` = `jobdaily`.`job_terceros`.`documento_identidad`) and (`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`codigo_empresa` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_empresa`) and (`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`documento_identidad_empleado` = `jobdaily`.`job_sucursal_contrato_empleados`.`documento_identidad_empleado`) and (`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`fecha_ingreso` = `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso`) and (`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`codigo_sucursal` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_sucursal`) and (`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`fecha_ingreso_sucursal` = `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso_sucursal`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`codigo_empresa` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_empresa`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`documento_identidad_empleado` = `jobdaily`.`job_sucursal_contrato_empleados`.`documento_identidad_empleado`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`fecha_ingreso` = `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`codigo_sucursal` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_sucursal`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`fecha_ingreso_sucursal` = `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso_sucursal`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`codigo_departamento_empresa` = `jobdaily`.`job_departamentos_empresa`.`codigo`) and (`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`codigo_transaccion_contable` = `jobdaily`.`job_transacciones_contables_empleado`.`codigo`) and (`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`codigo_transaccion_tiempo` = `jobdaily`.`job_transacciones_tiempo`.`codigo`) and (`jobdaily`.`job_conceptos_transacciones_tiempo`.`codigo` = `jobdaily`.`job_transacciones_tiempo`.`codigo_concepto_transaccion_tiempo`))) union (select 12 AS `tabla`,_utf8'movimientos_salario_retroactivo' AS `nombre_tabla`,`jobdaily`.`job_movimientos_salario_retroactivo`.`ano_generacion` AS `ano_generacion`,`jobdaily`.`job_movimientos_salario_retroactivo`.`mes_generacion` AS `mes_generacion`,`jobdaily`.`job_movimientos_salario_retroactivo`.`codigo_planilla` AS `codigo_planilla`,`jobdaily`.`job_movimientos_salario_retroactivo`.`periodo_pago` AS `periodo_pago`,`jobdaily`.`job_movimientos_salario_retroactivo`.`codigo_transaccion_contable` AS `codigo_transaccion_contable`,`jobdaily`.`job_transacciones_contables_empleado`.`acumula_cesantias` AS `acumula_cesantias`,`jobdaily`.`job_transacciones_contables_empleado`.`acumula_prima` AS `acumula_prima`,`jobdaily`.`job_transacciones_contables_empleado`.`acumula_vacaciones` AS `acumula_vacaciones`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_salud` AS `ibc_salud`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_pension` AS `ibc_pension`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_arp` AS `ibc_arp`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_icbf` AS `ibc_icbf`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_caja_compensacion` AS `ibc_caja_compensacion`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_sena` AS `ibc_sena`,`jobdaily`.`job_transacciones_contables_empleado`.`codigo_concepto_transaccion_contable` AS `concepto_contable`,`jobdaily`.`job_transacciones_contables_empleado`.`columna_planilla` AS `columna_planilla`,0 AS `codigo_transaccion_tiempo`,0 AS `resta_salario`,0 AS `resta_auxilio_transporte`,0 AS `resta_cesantias`,0 AS `resta_prima`,0 AS `resta_vacaciones`,0 AS `extras_empleado`,0 AS `concepto_tiempo`,(`jobdaily`.`job_movimientos_salario_retroactivo`.`documento_identidad_empleado` * 1) AS `documento_identidad_empleado`,`jobdaily`.`job_movimientos_salario_retroactivo`.`codigo_contable` AS `codigo_contable`,`jobdaily`.`job_movimientos_salario_retroactivo`.`sentido` AS `sentido`,`jobdaily`.`job_movimientos_salario_retroactivo`.`codigo_empresa_auxiliar` AS `codigo_empresa_auxiliar`,`jobdaily`.`job_movimientos_salario_retroactivo`.`codigo_anexo_contable` AS `codigo_anexo_contable`,`jobdaily`.`job_movimientos_salario_retroactivo`.`codigo_auxiliar_contable` AS `codigo_auxiliar_contable`,`jobdaily`.`job_movimientos_salario_retroactivo`.`valor_movimiento` AS `valor_movimiento`,0 AS `dias_trabajados`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),concat(concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`primer_apellido` is not null),concat(`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),concat(`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1''),_latin1'')),`jobdaily`.`job_terceros`.`razon_social`)) AS `nombre_empleado`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),concat(concat(`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),concat(`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1' '),_latin1' '),concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1'')),`jobdaily`.`job_terceros`.`razon_social`)) AS `apellido_empleado`,`jobdaily`.`job_departamentos_empresa`.`codigo` AS `codigo_departamento_empresa`,`jobdaily`.`job_departamentos_empresa`.`nombre` AS `departamento_empresa`,`jobdaily`.`job_sucursal_contrato_empleados`.`salario_mensual` AS `salario_empleado`,`jobdaily`.`job_sucursal_contrato_empleados`.`codigo_empresa` AS `codigo_empresa`,`jobdaily`.`job_sucursal_contrato_empleados`.`codigo_sucursal` AS `codigo_sucursal`,`jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso` AS `fecha_ingreso_empresa`,`jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso_sucursal` AS `fecha_ingreso_sucursal`,`jobdaily`.`job_movimientos_salario_retroactivo`.`contabilizado` AS `contabilizado`,0 AS `consecutivo`,_utf8'' AS `fecha_incapacidad`,`jobdaily`.`job_movimientos_salario_retroactivo`.`fecha_registro` AS `fecha_registro`,`jobdaily`.`job_movimientos_salario_retroactivo`.`fecha_pago_planilla` AS `fecha_pago_planilla`,_utf8'' AS `obligacion` from (((((`jobdaily`.`job_movimientos_salario_retroactivo` join `jobdaily`.`job_departamento_seccion_contrato_empleado`) join `jobdaily`.`job_departamentos_empresa`) join `jobdaily`.`job_sucursal_contrato_empleados`) join `jobdaily`.`job_terceros`) join `jobdaily`.`job_transacciones_contables_empleado`) where ((`jobdaily`.`job_movimientos_salario_retroactivo`.`documento_identidad_empleado` = `jobdaily`.`job_terceros`.`documento_identidad`) and (`jobdaily`.`job_movimientos_salario_retroactivo`.`codigo_empresa` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_empresa`) and (`jobdaily`.`job_movimientos_salario_retroactivo`.`documento_identidad_empleado` = `jobdaily`.`job_sucursal_contrato_empleados`.`documento_identidad_empleado`) and (`jobdaily`.`job_movimientos_salario_retroactivo`.`fecha_ingreso_empresa` = `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso`) and (`jobdaily`.`job_movimientos_salario_retroactivo`.`codigo_sucursal` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_sucursal`) and (`jobdaily`.`job_movimientos_salario_retroactivo`.`fecha_ingreso_sucursal` = `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso_sucursal`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`codigo_empresa` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_empresa`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`documento_identidad_empleado` = `jobdaily`.`job_sucursal_contrato_empleados`.`documento_identidad_empleado`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`fecha_ingreso` = `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`codigo_sucursal` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_sucursal`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`fecha_ingreso_sucursal` = `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso_sucursal`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`codigo_departamento_empresa` = `jobdaily`.`job_departamentos_empresa`.`codigo`) and (`jobdaily`.`job_movimientos_salario_retroactivo`.`codigo_transaccion_contable` = `jobdaily`.`job_transacciones_contables_empleado`.`codigo`))) union (select 13 AS `tabla`,_utf8'movimientos_prima' AS `nombre_tabla`,`jobdaily`.`job_movimientos_prima`.`ano_generacion` AS `ano_generacion`,`jobdaily`.`job_movimientos_prima`.`mes_generacion` AS `mes_generacion`,`jobdaily`.`job_movimientos_prima`.`codigo_planilla` AS `codigo_planilla`,`jobdaily`.`job_movimientos_prima`.`periodo_pago` AS `periodo_pago`,`jobdaily`.`job_movimientos_prima`.`codigo_transaccion_contable` AS `codigo_transaccion_contable`,`jobdaily`.`job_transacciones_contables_empleado`.`acumula_cesantias` AS `acumula_cesantias`,`jobdaily`.`job_transacciones_contables_empleado`.`acumula_prima` AS `acumula_prima`,`jobdaily`.`job_transacciones_contables_empleado`.`acumula_vacaciones` AS `acumula_vacaciones`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_salud` AS `ibc_salud`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_pension` AS `ibc_pension`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_arp` AS `ibc_arp`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_icbf` AS `ibc_icbf`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_caja_compensacion` AS `ibc_caja_compensacion`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_sena` AS `ibc_sena`,`jobdaily`.`job_transacciones_contables_empleado`.`codigo_concepto_transaccion_contable` AS `concepto_contable`,`jobdaily`.`job_transacciones_contables_empleado`.`columna_planilla` AS `columna_planilla`,0 AS `codigo_transaccion_tiempo`,0 AS `resta_salario`,0 AS `resta_auxilio_transporte`,0 AS `resta_cesantias`,0 AS `resta_prima`,0 AS `resta_vacaciones`,0 AS `extras_empleado`,0 AS `concepto_tiempo`,(`jobdaily`.`job_movimientos_prima`.`documento_identidad_empleado` * 1) AS `documento_identidad_empleado`,`jobdaily`.`job_movimientos_prima`.`codigo_contable` AS `codigo_contable`,`jobdaily`.`job_movimientos_prima`.`sentido` AS `sentido`,`jobdaily`.`job_movimientos_prima`.`codigo_empresa_auxiliar` AS `codigo_empresa_auxiliar`,`jobdaily`.`job_movimientos_prima`.`codigo_anexo_contable` AS `codigo_anexo_contable`,`jobdaily`.`job_movimientos_prima`.`codigo_auxiliar_contable` AS `codigo_auxiliar_contable`,`jobdaily`.`job_movimientos_prima`.`valor_movimiento` AS `valor_movimiento`,`jobdaily`.`job_movimientos_prima`.`dias_liquidados` AS `dias_trabajados`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),concat(concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`primer_apellido` is not null),concat(`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),concat(`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1''),_latin1'')),`jobdaily`.`job_terceros`.`razon_social`)) AS `nombre_empleado`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),concat(concat(`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),concat(`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1' '),_latin1' '),concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1'')),`jobdaily`.`job_terceros`.`razon_social`)) AS `apellido_empleado`,`jobdaily`.`job_departamentos_empresa`.`codigo` AS `codigo_departamento_empresa`,`jobdaily`.`job_departamentos_empresa`.`nombre` AS `departamento_empresa`,`jobdaily`.`job_sucursal_contrato_empleados`.`salario_mensual` AS `salario_empleado`,`jobdaily`.`job_sucursal_contrato_empleados`.`codigo_empresa` AS `codigo_empresa`,`jobdaily`.`job_sucursal_contrato_empleados`.`codigo_sucursal` AS `codigo_sucursal`,`jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso` AS `fecha_ingreso_empresa`,`jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso_sucursal` AS `fecha_ingreso_sucursal`,`jobdaily`.`job_movimientos_prima`.`contabilizado` AS `contabilizado`,0 AS `consecutivo`,_utf8'' AS `fecha_incapacidad`,`jobdaily`.`job_movimientos_prima`.`fecha_registro` AS `fecha_registro`,`jobdaily`.`job_movimientos_prima`.`fecha_pago_planilla` AS `fecha_pago_planilla`,_utf8'' AS `obligacion` from (((((`jobdaily`.`job_movimientos_prima` join `jobdaily`.`job_departamento_seccion_contrato_empleado`) join `jobdaily`.`job_departamentos_empresa`) join `jobdaily`.`job_sucursal_contrato_empleados`) join `jobdaily`.`job_terceros`) join `jobdaily`.`job_transacciones_contables_empleado`) where ((`jobdaily`.`job_movimientos_prima`.`documento_identidad_empleado` = `jobdaily`.`job_terceros`.`documento_identidad`) and (`jobdaily`.`job_movimientos_prima`.`codigo_empresa` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_empresa`) and (`jobdaily`.`job_movimientos_prima`.`documento_identidad_empleado` = `jobdaily`.`job_sucursal_contrato_empleados`.`documento_identidad_empleado`) and (`jobdaily`.`job_movimientos_prima`.`fecha_ingreso_empresa` = `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso`) and (`jobdaily`.`job_movimientos_prima`.`codigo_sucursal` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_sucursal`) and (`jobdaily`.`job_movimientos_prima`.`fecha_ingreso_sucursal` = `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso_sucursal`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`codigo_empresa` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_empresa`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`documento_identidad_empleado` = `jobdaily`.`job_sucursal_contrato_empleados`.`documento_identidad_empleado`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`fecha_ingreso` = `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`codigo_sucursal` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_sucursal`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`fecha_ingreso_sucursal` = `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso_sucursal`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`codigo_departamento_empresa` = `jobdaily`.`job_departamentos_empresa`.`codigo`) and (`jobdaily`.`job_movimientos_prima`.`codigo_transaccion_contable` = `jobdaily`.`job_transacciones_contables_empleado`.`codigo`))) union (select 14 AS `tabla`,_utf8'movimientos_nomina_migracion' AS `nombre_tabla`,`jobdaily`.`job_movimientos_nomina_migracion`.`ano_generacion` AS `ano_generacion`,`jobdaily`.`job_movimientos_nomina_migracion`.`mes_generacion` AS `mes_generacion`,`jobdaily`.`job_movimientos_nomina_migracion`.`codigo_planilla` AS `codigo_planilla`,`jobdaily`.`job_movimientos_nomina_migracion`.`periodo_pago` AS `periodo_pago`,`jobdaily`.`job_movimientos_nomina_migracion`.`codigo_transaccion_contable` AS `codigo_transaccion_contable`,`jobdaily`.`job_transacciones_contables_empleado`.`acumula_cesantias` AS `acumula_cesantias`,`jobdaily`.`job_transacciones_contables_empleado`.`acumula_prima` AS `acumula_prima`,`jobdaily`.`job_transacciones_contables_empleado`.`acumula_vacaciones` AS `acumula_vacaciones`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_salud` AS `ibc_salud`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_pension` AS `ibc_pension`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_arp` AS `ibc_arp`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_icbf` AS `ibc_icbf`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_caja_compensacion` AS `ibc_caja_compensacion`,`jobdaily`.`job_transacciones_contables_empleado`.`ibc_sena` AS `ibc_sena`,`jobdaily`.`job_transacciones_contables_empleado`.`codigo_concepto_transaccion_contable` AS `concepto_contable`,`jobdaily`.`job_transacciones_contables_empleado`.`columna_planilla` AS `columna_planilla`,0 AS `codigo_transaccion_tiempo`,0 AS `resta_salario`,0 AS `resta_auxilio_transporte`,0 AS `resta_cesantias`,0 AS `resta_prima`,0 AS `resta_vacaciones`,0 AS `extras_empleado`,0 AS `concepto_tiempo`,(`jobdaily`.`job_movimientos_nomina_migracion`.`documento_identidad_empleado` * 1) AS `documento_identidad_empleado`,`jobdaily`.`job_movimientos_nomina_migracion`.`codigo_contable` AS `codigo_contable`,`jobdaily`.`job_movimientos_nomina_migracion`.`sentido` AS `sentido`,`jobdaily`.`job_movimientos_nomina_migracion`.`codigo_empresa_auxiliar` AS `codigo_empresa_auxiliar`,`jobdaily`.`job_movimientos_nomina_migracion`.`codigo_anexo_contable` AS `codigo_anexo_contable`,`jobdaily`.`job_movimientos_nomina_migracion`.`codigo_auxiliar_contable` AS `codigo_auxiliar_contable`,`jobdaily`.`job_movimientos_nomina_migracion`.`valor_movimiento` AS `valor_movimiento`,0 AS `dias_trabajados`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),concat(concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`primer_apellido` is not null),concat(`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),concat(`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1''),_latin1'')),`jobdaily`.`job_terceros`.`razon_social`)) AS `nombre_empleado`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),concat(concat(`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),concat(`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1' '),_latin1' '),concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1'')),`jobdaily`.`job_terceros`.`razon_social`)) AS `apellido_empleado`,`jobdaily`.`job_departamentos_empresa`.`codigo` AS `codigo_departamento_empresa`,`jobdaily`.`job_departamentos_empresa`.`nombre` AS `departamento_empresa`,`jobdaily`.`job_sucursal_contrato_empleados`.`salario_mensual` AS `salario_empleado`,`jobdaily`.`job_sucursal_contrato_empleados`.`codigo_empresa` AS `codigo_empresa`,`jobdaily`.`job_sucursal_contrato_empleados`.`codigo_sucursal` AS `codigo_sucursal`,`jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso` AS `fecha_ingreso_empresa`,`jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso_sucursal` AS `fecha_ingreso_sucursal`,`jobdaily`.`job_movimientos_nomina_migracion`.`contabilizado` AS `contabilizado`,0 AS `consecutivo`,_utf8'' AS `fecha_incapacidad`,`jobdaily`.`job_movimientos_nomina_migracion`.`fecha_registro` AS `fecha_registro`,`jobdaily`.`job_movimientos_nomina_migracion`.`fecha_pago_planilla` AS `fecha_pago_planilla`,_utf8'' AS `obligacion` from (((((`jobdaily`.`job_movimientos_nomina_migracion` join `jobdaily`.`job_departamento_seccion_contrato_empleado`) join `jobdaily`.`job_departamentos_empresa`) join `jobdaily`.`job_sucursal_contrato_empleados`) join `jobdaily`.`job_terceros`) join `jobdaily`.`job_transacciones_contables_empleado`) where ((`jobdaily`.`job_movimientos_nomina_migracion`.`documento_identidad_empleado` = `jobdaily`.`job_terceros`.`documento_identidad`) and (`jobdaily`.`job_movimientos_nomina_migracion`.`codigo_empresa` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_empresa`) and (`jobdaily`.`job_movimientos_nomina_migracion`.`documento_identidad_empleado` = `jobdaily`.`job_sucursal_contrato_empleados`.`documento_identidad_empleado`) and (`jobdaily`.`job_movimientos_nomina_migracion`.`fecha_ingreso_empresa` = `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso`) and (`jobdaily`.`job_movimientos_nomina_migracion`.`codigo_sucursal` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_sucursal`) and (`jobdaily`.`job_movimientos_nomina_migracion`.`fecha_ingreso_sucursal` = `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso_sucursal`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`codigo_empresa` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_empresa`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`documento_identidad_empleado` = `jobdaily`.`job_sucursal_contrato_empleados`.`documento_identidad_empleado`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`fecha_ingreso` = `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`codigo_sucursal` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_sucursal`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`fecha_ingreso_sucursal` = `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso_sucursal`) and (`jobdaily`.`job_departamento_seccion_contrato_empleado`.`codigo_departamento_empresa` = `jobdaily`.`job_departamentos_empresa`.`codigo`) and (`jobdaily`.`job_movimientos_nomina_migracion`.`codigo_transaccion_contable` = `jobdaily`.`job_transacciones_contables_empleado`.`codigo`)));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_consulta_departamento_gastos_empleados`
-- 

DROP VIEW IF EXISTS `job_consulta_departamento_gastos_empleados`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_consulta_departamento_gastos_empleados` AS select `jobdaily`.`job_departamentos_empresa`.`codigo` AS `codigo_empresa`,`jobdaily`.`job_departamentos_empresa`.`nombre` AS `nombre_departamento`,`jobdaily`.`job_departamentos_empresa`.`riesgos_profesionales` AS `riesgos_profesionales`,`jobdaily`.`job_gastos_prestaciones_sociales`.`codigo` AS `codigo_gasto_prestacion`,`jobdaily`.`job_gastos_prestaciones_sociales`.`descripcion` AS `descripcion_gasto`,`jobdaily`.`job_gastos_prestaciones_sociales`.`cesantia_pago_prestacion` AS `cesantia_pago_prestacion`,`jobdaily`.`job_gastos_prestaciones_sociales`.`cesantia_pago_gasto` AS `cesantia_pago_gasto`,`jobdaily`.`job_gastos_prestaciones_sociales`.`cesantia_traslado_fondo` AS `cesantia_traslado_fondo`,`jobdaily`.`job_gastos_prestaciones_sociales`.`cesantia_causacion_prestacion` AS `cesantia_causacion_prestacion`,`jobdaily`.`job_gastos_prestaciones_sociales`.`cesantia_causacion_gasto` AS `cesantia_causacion_gasto`,`jobdaily`.`job_gastos_prestaciones_sociales`.`intereses_pago_prestacion` AS `intereses_pago_prestacion`,`jobdaily`.`job_gastos_prestaciones_sociales`.`intereses_pago_gasto` AS `intereses_pago_gasto`,`jobdaily`.`job_gastos_prestaciones_sociales`.`intereses_causacion_prestacion` AS `intereses_causacion_prestacion`,`jobdaily`.`job_gastos_prestaciones_sociales`.`intereses_causacion_gasto` AS `intereses_causacion_gasto`,`jobdaily`.`job_gastos_prestaciones_sociales`.`prima_pago_prestacion` AS `prima_pago_prestacion`,`jobdaily`.`job_gastos_prestaciones_sociales`.`prima_pago_gasto` AS `prima_pago_gasto`,`jobdaily`.`job_gastos_prestaciones_sociales`.`prima_causacion_prestacion` AS `prima_causacion_prestacion`,`jobdaily`.`job_gastos_prestaciones_sociales`.`prima_causacion_gasto` AS `prima_causacion_gasto`,`jobdaily`.`job_gastos_prestaciones_sociales`.`vacacion_pago_prestacion_disfrute` AS `vacacion_pago_prestacion_disfrute`,`jobdaily`.`job_gastos_prestaciones_sociales`.`vacacion_pago_prestacion_liquidacion` AS `vacacion_pago_prestacion_liquidacion`,`jobdaily`.`job_gastos_prestaciones_sociales`.`vacacion_pago_gasto_disfrute` AS `vacacion_pago_gasto_disfrute`,`jobdaily`.`job_gastos_prestaciones_sociales`.`vacacion_pago_gasto_liquidacion` AS `vacacion_pago_gasto_liquidacion`,`jobdaily`.`job_gastos_prestaciones_sociales`.`vacacion_causacion_prestacion` AS `vacacion_causacion_prestacion`,`jobdaily`.`job_gastos_prestaciones_sociales`.`vacacion_causacion_gasto` AS `vacacion_causacion_gasto` from (`jobdaily`.`job_departamentos_empresa` join `jobdaily`.`job_gastos_prestaciones_sociales`) where (`jobdaily`.`job_departamentos_empresa`.`codigo_gasto` = `jobdaily`.`job_gastos_prestaciones_sociales`.`codigo`);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_consulta_movimiento_tiempos_laborados`
-- 

DROP VIEW IF EXISTS `job_consulta_movimiento_tiempos_laborados`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_consulta_movimiento_tiempos_laborados` AS select `jobdaily`.`job_movimiento_tiempos_laborados`.`fecha_generacion` AS `fecha_generacion`,`jobdaily`.`job_movimiento_tiempos_laborados`.`codigo_empresa` AS `codigo_empresa`,(`jobdaily`.`job_movimiento_tiempos_laborados`.`documento_identidad_empleado` * 1) AS `documento_identidad_empleado`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),concat(concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`primer_apellido` is not null),concat(`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),concat(`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1''),_latin1'')),`jobdaily`.`job_terceros`.`razon_social`)) AS `nombre_empleado`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),concat(concat(`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),concat(`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1' '),_latin1' '),concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1'')),`jobdaily`.`job_terceros`.`razon_social`)) AS `apellido_empleado`,`jobdaily`.`job_movimiento_tiempos_laborados`.`fecha_ingreso` AS `fecha_ingreso`,`jobdaily`.`job_movimiento_tiempos_laborados`.`codigo_sucursal` AS `codigo_sucursal`,`jobdaily`.`job_movimiento_tiempos_laborados`.`fecha_ingreso_sucursal` AS `fecha_ingreso_sucursal`,`jobdaily`.`job_movimiento_tiempos_laborados`.`consecutivo` AS `consecutivo`,`jobdaily`.`job_movimiento_tiempos_laborados`.`codigo_planilla` AS `codigo_planilla`,`jobdaily`.`job_movimiento_tiempos_laborados`.`fecha_inicio` AS `fecha_inicio`,`jobdaily`.`job_movimiento_tiempos_laborados`.`hora_inicio` AS `hora_inicio`,`jobdaily`.`job_movimiento_tiempos_laborados`.`fecha_fin` AS `fecha_fin`,`jobdaily`.`job_movimiento_tiempos_laborados`.`hora_fin` AS `hora_fin`,`jobdaily`.`job_movimiento_tiempos_laborados`.`codigo_transaccion_tiempo` AS `codigo_transaccion_tiempo`,`jobdaily`.`job_movimiento_tiempos_laborados`.`codigo_transaccion_contable` AS `codigo_transaccion_contable`,`jobdaily`.`job_movimiento_tiempos_laborados`.`codigo_contable` AS `codigo_contable`,`jobdaily`.`job_movimiento_tiempos_laborados`.`tasa` AS `tasa`,`jobdaily`.`job_movimiento_tiempos_laborados`.`sentido` AS `sentido`,`jobdaily`.`job_movimiento_tiempos_laborados`.`ano_generacion` AS `ano_generacion`,`jobdaily`.`job_movimiento_tiempos_laborados`.`mes_generacion` AS `mes_generacion`,`jobdaily`.`job_movimiento_tiempos_laborados`.`fecha_pago_planilla` AS `fecha_pago_planilla`,`jobdaily`.`job_movimiento_tiempos_laborados`.`codigo_empresa_auxiliar` AS `codigo_empresa_auxiliar`,`jobdaily`.`job_movimiento_tiempos_laborados`.`codigo_anexo_contable` AS `codigo_anexo_contable`,`jobdaily`.`job_movimiento_tiempos_laborados`.`codigo_auxiliar_contable` AS `codigo_auxiliar_contable`,`jobdaily`.`job_movimiento_tiempos_laborados`.`periodo_pago` AS `periodo_pago`,`jobdaily`.`job_movimiento_tiempos_laborados`.`cantidad_horas` AS `cantidad_horas`,`jobdaily`.`job_movimiento_tiempos_laborados`.`cantidad_minutos` AS `cantidad_minutos`,`jobdaily`.`job_movimiento_tiempos_laborados`.`valor_hora_salario` AS `valor_hora_salario`,`jobdaily`.`job_movimiento_tiempos_laborados`.`valor_hora_recargo` AS `valor_hora_recargo`,`jobdaily`.`job_movimiento_tiempos_laborados`.`valor_movimiento` AS `valor_movimiento`,`jobdaily`.`job_movimiento_tiempos_laborados`.`contabilizado` AS `contabilizado`,`jobdaily`.`job_movimiento_tiempos_laborados`.`codigo_usuario_registra` AS `codigo_usuario_registra`,`jobdaily`.`job_movimiento_tiempos_laborados`.`codigo_usuario_modifica` AS `codigo_usuario_modifica` from (`jobdaily`.`job_movimiento_tiempos_laborados` join `jobdaily`.`job_terceros`) where (`jobdaily`.`job_movimiento_tiempos_laborados`.`documento_identidad_empleado` = `jobdaily`.`job_terceros`.`documento_identidad`);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_consulta_movimientos_nomina_migracion`
-- 

DROP VIEW IF EXISTS `job_consulta_movimientos_nomina_migracion`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_consulta_movimientos_nomina_migracion` AS select `jobdaily`.`job_movimientos_nomina_migracion`.`ano_generacion` AS `ano_generacion`,`jobdaily`.`job_movimientos_nomina_migracion`.`mes_generacion` AS `mes_generacion`,`jobdaily`.`job_movimientos_nomina_migracion`.`codigo_planilla` AS `codigo_planilla`,`jobdaily`.`job_movimientos_nomina_migracion`.`periodo_pago` AS `periodo_pago`,`jobdaily`.`job_movimientos_nomina_migracion`.`codigo_transaccion_contable` AS `codigo_transaccion_contable`,`jobdaily`.`job_movimientos_nomina_migracion`.`consecutivo` AS `consecutivo`,`jobdaily`.`job_movimientos_nomina_migracion`.`codigo_empresa` AS `codigo_empresa`,(`jobdaily`.`job_movimientos_nomina_migracion`.`documento_identidad_empleado` * 1) AS `documento_identidad_empleado`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),concat(concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`primer_apellido` is not null),concat(`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),concat(`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1''),_latin1'')),`jobdaily`.`job_terceros`.`razon_social`)) AS `nombre_empleado`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),concat(concat(`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),concat(`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1' '),_latin1' '),concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1'')),`jobdaily`.`job_terceros`.`razon_social`)) AS `apellido_empleado`,`jobdaily`.`job_movimientos_nomina_migracion`.`fecha_ingreso_empresa` AS `fecha_ingreso_empresa`,`jobdaily`.`job_movimientos_nomina_migracion`.`codigo_sucursal` AS `codigo_sucursal`,`jobdaily`.`job_movimientos_nomina_migracion`.`fecha_ingreso_sucursal` AS `fecha_ingreso_sucursal`,`jobdaily`.`job_movimientos_nomina_migracion`.`fecha_pago_planilla` AS `fecha_pago_planilla`,`jobdaily`.`job_movimientos_nomina_migracion`.`codigo_empresa_auxiliar` AS `codigo_empresa_auxiliar`,`jobdaily`.`job_movimientos_nomina_migracion`.`codigo_anexo_contable` AS `codigo_anexo_contable`,`jobdaily`.`job_movimientos_nomina_migracion`.`codigo_auxiliar_contable` AS `codigo_auxiliar_contable`,`jobdaily`.`job_movimientos_nomina_migracion`.`codigo_contable` AS `codigo_contable`,`jobdaily`.`job_movimientos_nomina_migracion`.`sentido` AS `sentido`,`jobdaily`.`job_movimientos_nomina_migracion`.`valor_movimiento` AS `valor_movimiento`,`jobdaily`.`job_movimientos_nomina_migracion`.`contabilizado` AS `contabilizado`,`jobdaily`.`job_movimientos_nomina_migracion`.`codigo_usuario_genera` AS `codigo_usuario_genera`,`jobdaily`.`job_movimientos_nomina_migracion`.`fecha_registro` AS `fecha_registro`,`jobdaily`.`job_movimientos_nomina_migracion`.`codigo_usuario_modifica` AS `codigo_usuario_modifica`,`jobdaily`.`job_movimientos_nomina_migracion`.`fecha_modificacion` AS `fecha_modificacion` from (`jobdaily`.`job_movimientos_nomina_migracion` join `jobdaily`.`job_terceros`) where (`jobdaily`.`job_movimientos_nomina_migracion`.`documento_identidad_empleado` = `jobdaily`.`job_terceros`.`documento_identidad`);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_consulta_movimientos_novedades_manuales`
-- 

DROP VIEW IF EXISTS `job_consulta_movimientos_novedades_manuales`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_consulta_movimientos_novedades_manuales` AS select `jobdaily`.`job_movimiento_novedades_manuales`.`fecha_generacion` AS `fecha_generacion`,`jobdaily`.`job_movimiento_novedades_manuales`.`codigo_empresa` AS `codigo_empresa`,`jobdaily`.`job_movimiento_novedades_manuales`.`documento_identidad_empleado` AS `documento_identidad_empleado`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),concat(concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`primer_apellido` is not null),concat(`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),concat(`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1''),_latin1'')),`jobdaily`.`job_terceros`.`razon_social`)) AS `nombre_empleado`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),concat(concat(`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),concat(`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1' '),_latin1' '),concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1'')),`jobdaily`.`job_terceros`.`razon_social`)) AS `apellido_empleado`,`jobdaily`.`job_movimiento_novedades_manuales`.`fecha_ingreso` AS `fecha_ingreso`,`jobdaily`.`job_movimiento_novedades_manuales`.`codigo_sucursal` AS `codigo_sucursal`,`jobdaily`.`job_movimiento_novedades_manuales`.`fecha_ingreso_sucursal` AS `fecha_ingreso_sucursal`,`jobdaily`.`job_movimiento_novedades_manuales`.`consecutivo` AS `consecutivo`,`jobdaily`.`job_movimiento_novedades_manuales`.`codigo_planilla` AS `codigo_planilla`,`jobdaily`.`job_movimiento_novedades_manuales`.`codigo_transaccion_contable` AS `codigo_transaccion_contable`,`jobdaily`.`job_movimiento_novedades_manuales`.`codigo_contable` AS `codigo_contable`,`jobdaily`.`job_movimiento_novedades_manuales`.`sentido` AS `sentido`,`jobdaily`.`job_movimiento_novedades_manuales`.`ano_generacion` AS `ano_generacion`,`jobdaily`.`job_movimiento_novedades_manuales`.`mes_generacion` AS `mes_generacion`,`jobdaily`.`job_movimiento_novedades_manuales`.`fecha_pago_planilla` AS `fecha_pago_planilla`,`jobdaily`.`job_movimiento_novedades_manuales`.`codigo_empresa_auxiliar` AS `codigo_empresa_auxiliar`,`jobdaily`.`job_movimiento_novedades_manuales`.`codigo_anexo_contable` AS `codigo_anexo_contable`,`jobdaily`.`job_movimiento_novedades_manuales`.`codigo_auxiliar_contable` AS `codigo_auxiliar_contable`,`jobdaily`.`job_movimiento_novedades_manuales`.`periodo_pago` AS `periodo_pago`,`jobdaily`.`job_movimiento_novedades_manuales`.`contabilizado` AS `contabilizado`,`jobdaily`.`job_movimiento_novedades_manuales`.`valor_movimiento` AS `valor_movimiento` from (`jobdaily`.`job_movimiento_novedades_manuales` join `jobdaily`.`job_terceros`) where (`jobdaily`.`job_movimiento_novedades_manuales`.`documento_identidad_empleado` = `jobdaily`.`job_terceros`.`documento_identidad`);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_consulta_prestamos_empleados`
-- 

DROP VIEW IF EXISTS `job_consulta_prestamos_empleados`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_consulta_prestamos_empleados` AS select `jobdaily`.`job_control_prestamos_empleados`.`fecha_generacion` AS `fecha_generacion`,`jobdaily`.`job_control_prestamos_empleados`.`codigo_empresa` AS `codigo_empresa`,(`jobdaily`.`job_control_prestamos_empleados`.`documento_identidad_empleado` * 1) AS `documento_identidad_empleado`,`jobdaily`.`job_control_prestamos_empleados`.`fecha_ingreso` AS `fecha_ingreso`,`jobdaily`.`job_control_prestamos_empleados`.`codigo_sucursal` AS `codigo_sucursal`,`jobdaily`.`job_control_prestamos_empleados`.`fecha_ingreso_sucursal` AS `fecha_ingreso_sucursal`,`jobdaily`.`job_control_prestamos_empleados`.`consecutivo` AS `consecutivo`,`jobdaily`.`job_control_prestamos_empleados`.`codigo_tipo_documento` AS `codigo_tipo_documento`,`jobdaily`.`job_control_prestamos_empleados`.`consecutivo_documento` AS `consecutivo_documento`,`jobdaily`.`job_control_prestamos_empleados`.`codigo_transaccion_contable_descontar` AS `codigo_transaccion_contable_descontar`,`jobdaily`.`job_control_prestamos_empleados`.`codigo_transaccion_contable_cobrar` AS `codigo_transaccion_contable_cobrar`,`jobdaily`.`job_control_prestamos_empleados`.`concepto_prestamo` AS `concepto_prestamo`,`jobdaily`.`job_control_prestamos_empleados`.`observaciones` AS `observaciones`,`jobdaily`.`job_control_prestamos_empleados`.`valor_total` AS `valor_total`,`jobdaily`.`job_control_prestamos_empleados`.`valor_pago` AS `valor_pago`,`jobdaily`.`job_control_prestamos_empleados`.`forma_pago` AS `forma_pago`,`jobdaily`.`job_control_prestamos_empleados`.`fecha_registro` AS `fecha_registro`,`jobdaily`.`job_control_prestamos_empleados`.`codigo_usuario_registra` AS `codigo_usuario_registra`,`jobdaily`.`job_control_prestamos_empleados`.`fecha_modificacion` AS `fecha_modificacion`,`jobdaily`.`job_control_prestamos_empleados`.`codigo_usuario_modifica` AS `codigo_usuario_modifica`,`jobdaily`.`job_control_prestamos_empleados`.`valor_saldo` AS `valor_saldo`,`jobdaily`.`job_conceptos_prestamos`.`descripcion` AS `descripcion_concepto_prestamo`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),concat(concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`primer_apellido` is not null),concat(`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),concat(`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1''),_latin1'')),`jobdaily`.`job_terceros`.`razon_social`)) AS `nombre_empleado`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),concat(concat(`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),concat(`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1' '),_latin1' '),concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1'')),`jobdaily`.`job_terceros`.`razon_social`)) AS `apellido_empleado` from ((`jobdaily`.`job_terceros` join `jobdaily`.`job_control_prestamos_empleados`) join `jobdaily`.`job_conceptos_prestamos`) where ((`jobdaily`.`job_control_prestamos_empleados`.`concepto_prestamo` = `jobdaily`.`job_conceptos_prestamos`.`codigo`) and (`jobdaily`.`job_control_prestamos_empleados`.`documento_identidad_empleado` = `jobdaily`.`job_terceros`.`documento_identidad`));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_consulta_prestamos_terceros`
-- 

DROP VIEW IF EXISTS `job_consulta_prestamos_terceros`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_consulta_prestamos_terceros` AS select `jobdaily`.`job_control_prestamos_terceros`.`limite_descuento` AS `limite_descuento`,`jobdaily`.`job_control_prestamos_terceros`.`fecha_generacion` AS `fecha_generacion`,`jobdaily`.`job_control_prestamos_terceros`.`fecha_inicio_descuento` AS `fecha_inicio_descuento`,`jobdaily`.`job_control_prestamos_terceros`.`codigo_sucursal` AS `codigo_sucursal`,`jobdaily`.`job_control_prestamos_terceros`.`codigo_empresa` AS `codigo_empresa`,(`jobdaily`.`job_control_prestamos_terceros`.`documento_identidad_empleado` * 1) AS `documento_identidad_empleado`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),concat(concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`primer_apellido` is not null),concat(`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),concat(`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1''),_latin1'')),`jobdaily`.`job_terceros`.`razon_social`)) AS `nombre_empleado`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),concat(concat(`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),concat(`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1' '),_latin1' '),concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1'')),`jobdaily`.`job_terceros`.`razon_social`)) AS `apellido_empleado`,`jobdaily`.`job_control_prestamos_terceros`.`documento_identidad_tercero` AS `documento_identidad_tercero`,`jobdaily`.`job_control_prestamos_terceros`.`autorizacion_descuento_nomina` AS `autorizacion_descuento_nomina`,`jobdaily`.`job_control_prestamos_terceros`.`obligacion` AS `obligacion`,`jobdaily`.`job_control_prestamos_terceros`.`valor_tope_descuento` AS `valor_tope_descuento`,`jobdaily`.`job_control_prestamos_terceros`.`periodo_pago` AS `periodo_pago`,`jobdaily`.`job_control_prestamos_terceros`.`valor_descontar_mensual` AS `valor_descontar_mensual`,`jobdaily`.`job_control_prestamos_terceros`.`valor_descontar_primera_quincena` AS `valor_descontar_primera_quincena`,`jobdaily`.`job_control_prestamos_terceros`.`valor_descontar_segunda_quincena` AS `valor_descontar_segunda_quincena`,`jobdaily`.`job_control_prestamos_terceros`.`valor_descontar_primera_semana` AS `valor_descontar_primera_semana`,`jobdaily`.`job_control_prestamos_terceros`.`valor_descontar_segunda_semana` AS `valor_descontar_segunda_semana`,`jobdaily`.`job_control_prestamos_terceros`.`valor_descontar_tercera_semana` AS `valor_descontar_tercera_semana`,`jobdaily`.`job_control_prestamos_terceros`.`valor_descontar_cuarta_semana` AS `valor_descontar_cuarta_semana`,`jobdaily`.`job_control_prestamos_terceros`.`descuento_ilimitado` AS `descuento_ilimitado`,`jobdaily`.`job_control_prestamos_terceros`.`fecha_limite_descuento` AS `fecha_limite_descuento`,`jobdaily`.`job_control_prestamos_terceros`.`estado` AS `estado`,`jobdaily`.`job_control_prestamos_terceros`.`codigo_usuario_registra` AS `codigo_usuario_registra`,`jobdaily`.`job_control_prestamos_terceros`.`codigo_usuario_modifica` AS `codigo_usuario_modifica`,`jobdaily`.`job_control_prestamos_terceros`.`transaccion_contable_descuento` AS `transaccion_contable_descuento`,`jobdaily`.`job_control_prestamos_terceros`.`transaccion_contable_empleado` AS `transaccion_contable_empleado`,`jobdaily`.`job_control_prestamos_terceros`.`transaccion_contable_pagar_tercero` AS `transaccion_contable_pagar_tercero`,`jobdaily`.`job_control_prestamos_terceros`.`transaccion_contable_pago_tercero` AS `transaccion_contable_pago_tercero` from (`jobdaily`.`job_control_prestamos_terceros` join `jobdaily`.`job_terceros`) where (`jobdaily`.`job_control_prestamos_terceros`.`documento_identidad_empleado` = `jobdaily`.`job_terceros`.`documento_identidad`);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_consulta_reporte_incapacidades`
-- 

DROP VIEW IF EXISTS `job_consulta_reporte_incapacidades`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_consulta_reporte_incapacidades` AS select `jobdaily`.`job_reporte_incapacidades`.`codigo_empresa` AS `codigo_empresa`,(`jobdaily`.`job_reporte_incapacidades`.`documento_identidad_empleado` * 1) AS `documento_identidad_empleado`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),concat(concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`primer_apellido` is not null),concat(`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),concat(`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1''),_latin1'')),`jobdaily`.`job_terceros`.`razon_social`)) AS `nombre_empleado`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),concat(concat(`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),concat(`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1' '),_latin1' '),concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1'')),`jobdaily`.`job_terceros`.`razon_social`)) AS `apellido_empleado`,`jobdaily`.`job_reporte_incapacidades`.`fecha_ingreso` AS `fecha_ingreso`,`jobdaily`.`job_reporte_incapacidades`.`codigo_sucursal` AS `codigo_sucursal`,`jobdaily`.`job_reporte_incapacidades`.`fecha_ingreso_sucursal` AS `fecha_ingreso_sucursaL`,`jobdaily`.`job_reporte_incapacidades`.`ano_generacion` AS `ano_generacion`,`jobdaily`.`job_reporte_incapacidades`.`mes_generacion` AS `mes_generacion`,`jobdaily`.`job_reporte_incapacidades`.`codigo_planilla` AS `codigo_planilla`,`jobdaily`.`job_reporte_incapacidades`.`fecha_pago_planilla` AS `fecha_pago_planilla`,`jobdaily`.`job_reporte_incapacidades`.`codigo_empresa_auxiliar` AS `codigo_empresa_auxiliar`,`jobdaily`.`job_reporte_incapacidades`.`codigo_anexo_contable` AS `codigo_anexo_contable`,`jobdaily`.`job_reporte_incapacidades`.`codigo_auxiliar_contable` AS `codigo_auxiliar_contable`,`jobdaily`.`job_reporte_incapacidades`.`periodo_pago` AS `periodo_pago`,`jobdaily`.`job_reporte_incapacidades`.`contabilizado` AS `contabilizado`,`jobdaily`.`job_reporte_incapacidades`.`fecha_incapacidad` AS `fecha_incapacidad`,`jobdaily`.`job_reporte_incapacidades`.`fecha_reporte_incapacidad` AS `fecha_reporte_incapacidad`,`jobdaily`.`job_reporte_incapacidades`.`fecha_inicial_incapacidad` AS `fecha_inicial_incapacidad`,`jobdaily`.`job_reporte_incapacidades`.`codigo_transaccion_tiempo` AS `codigo_transaccion_tiempo`,`jobdaily`.`job_reporte_incapacidades`.`codigo_transaccion_contable` AS `codigo_transaccion_contable`,`jobdaily`.`job_reporte_incapacidades`.`codigo_contable` AS `codigo_contable`,`jobdaily`.`job_reporte_incapacidades`.`sentido` AS `sentido`,`jobdaily`.`job_reporte_incapacidades`.`dias_incapacidad` AS `dias_incapacidad`,`jobdaily`.`job_reporte_incapacidades`.`valor_dia` AS `valor_dia`,`jobdaily`.`job_reporte_incapacidades`.`dividendo` AS `dividendo`,`jobdaily`.`job_reporte_incapacidades`.`divisor` AS `divisor`,`jobdaily`.`job_reporte_incapacidades`.`valor_movimiento` AS `valor_movimiento`,`jobdaily`.`job_reporte_incapacidades`.`codigo_motivo_incapacidad` AS `codigo_motivo_incapacidad`,`jobdaily`.`job_reporte_incapacidades`.`codigo_entidad_parafiscal` AS `codigo_entidad_parafiscal`,`jobdaily`.`job_reporte_incapacidades`.`numero_incapacidad` AS `numero_incapacidad`,`jobdaily`.`job_reporte_incapacidades`.`fecha_registro` AS `fecha_registro`,`jobdaily`.`job_reporte_incapacidades`.`codigo_usuario_registra` AS `codigo_usuario_registra`,`jobdaily`.`job_reporte_incapacidades`.`codigo_usuario_modifica` AS `codigo_usuario_modifica` from (`jobdaily`.`job_reporte_incapacidades` join `jobdaily`.`job_terceros`) where (`jobdaily`.`job_reporte_incapacidades`.`documento_identidad_empleado` = `jobdaily`.`job_terceros`.`documento_identidad`);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_consulta_tiempos_no_laborados_dias`
-- 

DROP VIEW IF EXISTS `job_consulta_tiempos_no_laborados_dias`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_consulta_tiempos_no_laborados_dias` AS select `jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`fecha_generacion` AS `fecha_generacion`,`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`codigo_empresa` AS `codigo_empresa`,(`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`documento_identidad_empleado` * 1) AS `documento_identidad_empleado`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),concat(concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`primer_apellido` is not null),concat(`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),concat(`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1''),_latin1'')),`jobdaily`.`job_terceros`.`razon_social`)) AS `nombre_empleado`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),concat(concat(`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),concat(`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1' '),_latin1' '),concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1'')),`jobdaily`.`job_terceros`.`razon_social`)) AS `apellido_empleado`,`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`fecha_ingreso` AS `fecha_ingreso`,`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`codigo_sucursal` AS `codigo_sucursal`,`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`fecha_ingreso_sucursal` AS `fecha_ingreso_sucursal`,`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`ano_generacion` AS `ano_generacion`,`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`mes_generacion` AS `mes_generacion`,`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`codigo_planilla` AS `codigo_planilla`,`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`fecha_pago_planilla` AS `fecha_pago_planilla`,`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`codigo_empresa_auxiliar` AS `codigo_empresa_auxiliar`,`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`codigo_anexo_contable` AS `codigo_anexo_contable`,`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`codigo_auxiliar_contable` AS `codigo_auxiliar_contable`,`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`periodo_pago` AS `periodo_pago`,`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`contabilizado` AS `contabilizado`,`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`fecha_inicio_tiempo` AS `fecha_inicio_tiempo`,`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`fecha_tiempo` AS `fecha_tiempo`,`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`codigo_transaccion_tiempo` AS `codigo_transaccion_tiempo`,`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`codigo_transaccion_contable` AS `codigo_transaccion_contable`,`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`codigo_contable` AS `codigo_contable`,`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`sentido` AS `sentido`,`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`dias_no_laborados` AS `dias_no_laborados`,`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`valor_dia` AS `valor_dia`,`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`codigo_motivo_no_laboral` AS `codigo_motivo_no_laboral`,`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`codigo_usuario_registra` AS `codigo_usuario_registra`,`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`codigo_usuario_modifica` AS `codigo_usuario_modifica` from (`jobdaily`.`job_movimiento_tiempos_no_laborados_dias` join `jobdaily`.`job_terceros`) where (`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`documento_identidad_empleado` = `jobdaily`.`job_terceros`.`documento_identidad`);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_consulta_tiempos_no_laborados_horas`
-- 

DROP VIEW IF EXISTS `job_consulta_tiempos_no_laborados_horas`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_consulta_tiempos_no_laborados_horas` AS select `jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`fecha_generacion` AS `fecha_generacion`,`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`codigo_empresa` AS `codigo_empresa`,(`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`documento_identidad_empleado` * 1) AS `documento_identidad_empleado`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),concat(concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`primer_apellido` is not null),concat(`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),concat(`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1''),_latin1'')),`jobdaily`.`job_terceros`.`razon_social`)) AS `nombre_empleado`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),concat(concat(`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),concat(`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1' '),_latin1' '),concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1'')),`jobdaily`.`job_terceros`.`razon_social`)) AS `apellido_empleado`,`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`fecha_ingreso` AS `fecha_ingreso`,`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`codigo_sucursal` AS `codigo_sucursal`,`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`fecha_ingreso_sucursal` AS `fecha_ingreso_sucursal`,`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`consecutivo` AS `consecutivo`,`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`codigo_planilla` AS `codigo_planilla`,`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`fecha_registro` AS `fecha_registro`,`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`hora_inicio` AS `hora_inicio`,`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`hora_fin` AS `hora_fin`,`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`codigo_transaccion_tiempo` AS `codigo_transaccion_tiempo`,`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`codigo_transaccion_contable` AS `codigo_transaccion_contable`,`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`codigo_contable` AS `codigo_contable`,`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`sentido` AS `sentido`,`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`tasa` AS `tasa`,`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`ano_generacion` AS `ano_generacion`,`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`mes_generacion` AS `mes_generacion`,`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`fecha_pago_planilla` AS `fecha_pago_planilla`,`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`codigo_empresa_auxiliar` AS `codigo_empresa_auxiliar`,`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`codigo_anexo_contable` AS `codigo_anexo_contable`,`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`codigo_auxiliar_contable` AS `codigo_auxiliar_contable`,`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`periodo_pago` AS `periodo_pago`,`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`cantidad_minutos` AS `cantidad_minutos`,`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`valor_hora_salario` AS `valor_hora_salario`,`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`valor_movimiento` AS `valor_movimiento`,`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`contabilizado` AS `contabilizado`,`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`codigo_motivo_no_laboral` AS `codigo_motivo_no_laboral` from (`jobdaily`.`job_terceros` join `jobdaily`.`job_movimiento_tiempos_no_laborados_horas`) where (`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`documento_identidad_empleado` = `jobdaily`.`job_terceros`.`documento_identidad`);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_listado_cuentas_bancarias`
-- 

DROP VIEW IF EXISTS `job_listado_cuentas_bancarias`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_listado_cuentas_bancarias` AS select `s`.`nombre` AS `nombre_almacen`,`s`.`codigo` AS `codigo_almacen`,`b`.`descripcion` AS `nombre_banco`,`sb`.`nombre_sucursal` AS `nombre_sucursal`,`sb`.`codigo` AS `codigo_sucursal`,`sb`.`codigo_banco` AS `codigo_banco`,`sb`.`codigo_iso` AS `codigo_iso`,`sb`.`codigo_dane_departamento` AS `codigo_dane_departamento`,`sb`.`codigo_dane_municipio` AS `codigo_dane_municipio`,`cb`.`numero` AS `numero`,`td`.`descripcion` AS `tipo_documento`,concat(`pc`.`codigo_contable`,_latin1'-',`pc`.`descripcion`) AS `cuenta`,`ac`.`descripcion` AS `auxiliar`,`cb`.`estado` AS `estado` from (((((((`jobdaily`.`job_cuentas_bancarias` `cb` join `jobdaily`.`job_sucursales` `s`) join `jobdaily`.`job_tipos_documentos` `td`) join `jobdaily`.`job_bancos` `b`) join `jobdaily`.`job_sucursales_bancos` `sb`) join `jobdaily`.`job_municipios` `m`) join `jobdaily`.`job_plan_contable` `pc`) join `jobdaily`.`job_auxiliares_contables` `ac`) where ((`cb`.`numero` <> _latin1'') and (`cb`.`codigo_sucursal` = `s`.`codigo`) and (`cb`.`codigo_tipo_documento` = `td`.`codigo`) and (`cb`.`codigo_banco` = `b`.`codigo`) and (`cb`.`codigo_sucursal_banco` = `sb`.`codigo`) and (`cb`.`codigo_banco` = `sb`.`codigo_banco`) and (`cb`.`codigo_iso` = `sb`.`codigo_iso`) and (`cb`.`codigo_dane_departamento` = `sb`.`codigo_dane_departamento`) and (`cb`.`codigo_dane_municipio` = `sb`.`codigo_dane_municipio`) and (`cb`.`codigo_iso` = `m`.`codigo_iso`) and (`cb`.`codigo_dane_departamento` = `m`.`codigo_dane_departamento`) and (`cb`.`codigo_dane_municipio` = `m`.`codigo_dane_municipio`) and (`cb`.`codigo_plan_contable` = `pc`.`codigo_contable`) and (`cb`.`codigo_empresa_auxiliar` = `ac`.`codigo_empresa`) and (`cb`.`codigo_anexo_contable` = `ac`.`codigo_anexo_contable`) and (`cb`.`codigo_auxiliar_contable` = `ac`.`codigo`)) order by `b`.`codigo`,`sb`.`codigo`,`pc`.`codigo_contable`;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_listado_empleados`
-- 

DROP VIEW IF EXISTS `job_listado_empleados`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_listado_empleados` AS (select `jobdaily`.`job_ingreso_empleados`.`fecha_ingreso` AS `fecha_ingreso`,`jobdaily`.`job_ingreso_empleados`.`documento_identidad_empleado` AS `documento_identidad_empleado`,`jobdaily`.`job_ingreso_empleados`.`codigo_empresa` AS `codigo_empresa`,`jobdaily`.`job_ingreso_empleados`.`codigo_sucursal_activo` AS `codigo_sucursal_activo`,`jobdaily`.`job_empresas`.`razon_social` AS `razon_social`,`jobdaily`.`job_sucursales`.`nombre` AS `nombre_sucursal`,if(((`jobdaily`.`job_terceros`.`tipo_persona` = _latin1'1') or (`jobdaily`.`job_terceros`.`tipo_persona` = _latin1'4')),concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' ',if(((`jobdaily`.`job_terceros`.`segundo_nombre` is not null) and (`jobdaily`.`job_terceros`.`segundo_nombre` <> _latin1'')),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1''),`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' ',if(((`jobdaily`.`job_terceros`.`segundo_apellido` is not null) and (`jobdaily`.`job_terceros`.`segundo_apellido` <> _latin1'')),`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1'')),`jobdaily`.`job_terceros`.`razon_social`) AS `nombre_completo`,`jobdaily`.`job_terceros`.`fecha_nacimiento` AS `fecha_nacimiento`,(select max(`jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso_sucursal`) AS `max(job_sucursal_contrato_empleados.fecha_ingreso_sucursal)` from `jobdaily`.`job_sucursal_contrato_empleados` where ((`jobdaily`.`job_ingreso_empleados`.`codigo_empresa` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_empresa`) and (`jobdaily`.`job_ingreso_empleados`.`fecha_ingreso` = `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso`) and (`jobdaily`.`job_ingreso_empleados`.`documento_identidad_empleado` = `jobdaily`.`job_sucursal_contrato_empleados`.`documento_identidad_empleado`) and (`jobdaily`.`job_ingreso_empleados`.`codigo_sucursal_activo` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_sucursal`))) AS `fecha_ingreso_sucursal`,(select `jobdaily`.`job_sucursal_contrato_empleados`.`salario_mensual` AS `salario_mensual` from `jobdaily`.`job_sucursal_contrato_empleados` where ((`jobdaily`.`job_ingreso_empleados`.`codigo_empresa` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_empresa`) and (`jobdaily`.`job_ingreso_empleados`.`fecha_ingreso` = `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso`) and (`jobdaily`.`job_ingreso_empleados`.`documento_identidad_empleado` = `jobdaily`.`job_sucursal_contrato_empleados`.`documento_identidad_empleado`) and (`jobdaily`.`job_ingreso_empleados`.`codigo_sucursal_activo` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_sucursal`) and `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso_sucursal` in (select max(`jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso_sucursal`) AS `max(job_sucursal_contrato_empleados.fecha_ingreso_sucursal)` from `jobdaily`.`job_sucursal_contrato_empleados` where ((`jobdaily`.`job_ingreso_empleados`.`codigo_empresa` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_empresa`) and (`jobdaily`.`job_ingreso_empleados`.`fecha_ingreso` = `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso`) and (`jobdaily`.`job_ingreso_empleados`.`documento_identidad_empleado` = `jobdaily`.`job_sucursal_contrato_empleados`.`documento_identidad_empleado`) and (`jobdaily`.`job_ingreso_empleados`.`codigo_sucursal_activo` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_sucursal`))))) AS `salario`,`jobdaily`.`job_contrato_empleados`.`codigo_tipo_contrato` AS `codigo_tipo_contrato`,`jobdaily`.`job_tipos_contrato`.`descripcion` AS `tipo_contrato`,(select `jobdaily`.`job_departamento_seccion_contrato_empleado`.`codigo_departamento_empresa` AS `codigo_departamento_empresa` from `jobdaily`.`job_departamento_seccion_contrato_empleado` where ((`jobdaily`.`job_ingreso_empleados`.`codigo_empresa` = `jobdaily`.`job_departamento_seccion_contrato_empleado`.`codigo_empresa`) and (`jobdaily`.`job_ingreso_empleados`.`fecha_ingreso` = `jobdaily`.`job_departamento_seccion_contrato_empleado`.`fecha_ingreso`) and (`jobdaily`.`job_ingreso_empleados`.`documento_identidad_empleado` = `jobdaily`.`job_departamento_seccion_contrato_empleado`.`documento_identidad_empleado`) and (`jobdaily`.`job_ingreso_empleados`.`codigo_sucursal_activo` = `jobdaily`.`job_departamento_seccion_contrato_empleado`.`codigo_sucursal`) and `jobdaily`.`job_departamento_seccion_contrato_empleado`.`fecha_ingreso_sucursal` in (select max(`jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso_sucursal`) AS `max(job_sucursal_contrato_empleados.fecha_ingreso_sucursal)` from `jobdaily`.`job_sucursal_contrato_empleados` where ((`jobdaily`.`job_ingreso_empleados`.`codigo_empresa` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_empresa`) and (`jobdaily`.`job_ingreso_empleados`.`fecha_ingreso` = `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso`) and (`jobdaily`.`job_ingreso_empleados`.`documento_identidad_empleado` = `jobdaily`.`job_sucursal_contrato_empleados`.`documento_identidad_empleado`) and (`jobdaily`.`job_ingreso_empleados`.`codigo_sucursal_activo` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_sucursal`))))) AS `codigo_departamento_empresa`,(select `jobdaily`.`job_departamentos_empresa`.`nombre` AS `nombre` from `jobdaily`.`job_departamentos_empresa` where `jobdaily`.`job_departamentos_empresa`.`codigo` in (select `jobdaily`.`job_departamento_seccion_contrato_empleado`.`codigo_departamento_empresa` AS `codigo_departamento_empresa` from `jobdaily`.`job_departamento_seccion_contrato_empleado` where ((`jobdaily`.`job_ingreso_empleados`.`codigo_empresa` = `jobdaily`.`job_departamento_seccion_contrato_empleado`.`codigo_empresa`) and (`jobdaily`.`job_ingreso_empleados`.`fecha_ingreso` = `jobdaily`.`job_departamento_seccion_contrato_empleado`.`fecha_ingreso`) and (`jobdaily`.`job_ingreso_empleados`.`documento_identidad_empleado` = `jobdaily`.`job_departamento_seccion_contrato_empleado`.`documento_identidad_empleado`) and (`jobdaily`.`job_ingreso_empleados`.`codigo_sucursal_activo` = `jobdaily`.`job_departamento_seccion_contrato_empleado`.`codigo_sucursal`) and `jobdaily`.`job_departamento_seccion_contrato_empleado`.`fecha_ingreso_sucursal` in (select max(`jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso_sucursal`) AS `max(job_sucursal_contrato_empleados.fecha_ingreso_sucursal)` from `jobdaily`.`job_sucursal_contrato_empleados` where ((`jobdaily`.`job_ingreso_empleados`.`codigo_empresa` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_empresa`) and (`jobdaily`.`job_ingreso_empleados`.`fecha_ingreso` = `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso`) and (`jobdaily`.`job_ingreso_empleados`.`documento_identidad_empleado` = `jobdaily`.`job_sucursal_contrato_empleados`.`documento_identidad_empleado`) and (`jobdaily`.`job_ingreso_empleados`.`codigo_sucursal_activo` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_sucursal`)))))) AS `departamento_empresa`,(select `jobdaily`.`job_secciones_departamentos`.`nombre` AS `nombre` from `jobdaily`.`job_secciones_departamentos` where `jobdaily`.`job_secciones_departamentos`.`codigo` in (select `jobdaily`.`job_departamento_seccion_contrato_empleado`.`codigo_seccion_empresa` AS `codigo_seccion_empresa` from `jobdaily`.`job_departamento_seccion_contrato_empleado` where ((`jobdaily`.`job_ingreso_empleados`.`codigo_empresa` = `jobdaily`.`job_departamento_seccion_contrato_empleado`.`codigo_empresa`) and (`jobdaily`.`job_ingreso_empleados`.`fecha_ingreso` = `jobdaily`.`job_departamento_seccion_contrato_empleado`.`fecha_ingreso`) and (`jobdaily`.`job_ingreso_empleados`.`documento_identidad_empleado` = `jobdaily`.`job_departamento_seccion_contrato_empleado`.`documento_identidad_empleado`) and (`jobdaily`.`job_ingreso_empleados`.`codigo_sucursal_activo` = `jobdaily`.`job_departamento_seccion_contrato_empleado`.`codigo_sucursal`) and `jobdaily`.`job_departamento_seccion_contrato_empleado`.`fecha_ingreso_sucursal` in (select max(`jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso_sucursal`) AS `max(job_sucursal_contrato_empleados.fecha_ingreso_sucursal)` from `jobdaily`.`job_sucursal_contrato_empleados` where ((`jobdaily`.`job_ingreso_empleados`.`codigo_empresa` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_empresa`) and (`jobdaily`.`job_ingreso_empleados`.`fecha_ingreso` = `jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso`) and (`jobdaily`.`job_ingreso_empleados`.`documento_identidad_empleado` = `jobdaily`.`job_sucursal_contrato_empleados`.`documento_identidad_empleado`) and (`jobdaily`.`job_ingreso_empleados`.`codigo_sucursal_activo` = `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_sucursal`)))))) AS `seccion_empresa` from (((((`jobdaily`.`job_terceros` join `jobdaily`.`job_ingreso_empleados`) join `jobdaily`.`job_empresas`) join `jobdaily`.`job_sucursales`) join `jobdaily`.`job_contrato_empleados`) join `jobdaily`.`job_tipos_contrato`) where ((`jobdaily`.`job_ingreso_empleados`.`documento_identidad_empleado` = `jobdaily`.`job_terceros`.`documento_identidad`) and (`jobdaily`.`job_ingreso_empleados`.`codigo_empresa` = `jobdaily`.`job_empresas`.`codigo`) and (`jobdaily`.`job_ingreso_empleados`.`codigo_sucursal_activo` = `jobdaily`.`job_sucursales`.`codigo`) and (`jobdaily`.`job_ingreso_empleados`.`codigo_empresa` = `jobdaily`.`job_contrato_empleados`.`codigo_empresa`) and (`jobdaily`.`job_ingreso_empleados`.`fecha_ingreso` = `jobdaily`.`job_contrato_empleados`.`fecha_ingreso`) and (`jobdaily`.`job_ingreso_empleados`.`documento_identidad_empleado` = `jobdaily`.`job_contrato_empleados`.`documento_identidad_empleado`) and (`jobdaily`.`job_tipos_contrato`.`codigo` = `jobdaily`.`job_contrato_empleados`.`codigo_tipo_contrato`)));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_actividades_economicas`
-- 

DROP VIEW IF EXISTS `job_menu_actividades_economicas`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_actividades_economicas` AS select concat(`jobdaily`.`job_actividades_economicas`.`codigo_iso`,_utf8'|',`jobdaily`.`job_actividades_economicas`.`codigo_dane_departamento`,_utf8'|',`jobdaily`.`job_actividades_economicas`.`codigo_dane_municipio`,_utf8'|',`jobdaily`.`job_actividades_economicas`.`codigo_dian`,_utf8'|',`jobdaily`.`job_actividades_economicas`.`codigo_actividad_municipio`) AS `id`,`jobdaily`.`job_municipios`.`nombre` AS `MUNICIPIOS`,`jobdaily`.`job_actividades_economicas`.`codigo_actividad_municipio` AS `ACTIVIDAD_MUNICIPIO`,`jobdaily`.`job_actividades_economicas_dian`.`codigo_dian` AS `CODIGO_DIAN`,`jobdaily`.`job_actividades_economicas`.`codigo_interno` AS `CODIGO_INTERNO`,`jobdaily`.`job_actividades_economicas`.`descripcion` AS `DESCRIPCION`,`jobdaily`.`job_actividades_economicas_dian`.`descripcion` AS `ACTIVIDAD_DIAN` from ((`jobdaily`.`job_actividades_economicas` join `jobdaily`.`job_actividades_economicas_dian`) join `jobdaily`.`job_municipios`) where ((`jobdaily`.`job_actividades_economicas`.`codigo_iso` = `jobdaily`.`job_municipios`.`codigo_iso`) and (`jobdaily`.`job_actividades_economicas`.`codigo_dane_departamento` = `jobdaily`.`job_municipios`.`codigo_dane_departamento`) and (`jobdaily`.`job_actividades_economicas`.`codigo_dane_municipio` = `jobdaily`.`job_municipios`.`codigo_dane_municipio`) and (`jobdaily`.`job_actividades_economicas`.`codigo_dian` = `jobdaily`.`job_actividades_economicas_dian`.`codigo_dian`) and (`jobdaily`.`job_actividades_economicas`.`codigo_dian` <> 0));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_actividades_economicas_dian`
-- 

DROP VIEW IF EXISTS `job_menu_actividades_economicas_dian`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_actividades_economicas_dian` AS select `jobdaily`.`job_actividades_economicas_dian`.`codigo_dian` AS `id`,`jobdaily`.`job_actividades_economicas_dian`.`codigo_dian` AS `CODIGO_DIAN`,`jobdaily`.`job_actividades_economicas_dian`.`descripcion` AS `DESCRIPCION` from `jobdaily`.`job_actividades_economicas_dian` where (`jobdaily`.`job_actividades_economicas_dian`.`codigo_dian` <> 0) order by `jobdaily`.`job_actividades_economicas_dian`.`codigo_dian`;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_aficiones`
-- 

DROP VIEW IF EXISTS `job_menu_aficiones`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_aficiones` AS select `jobdaily`.`job_aficiones`.`codigo` AS `id`,`jobdaily`.`job_aficiones`.`codigo` AS `CODIGO`,`jobdaily`.`job_aficiones`.`descripcion` AS `DESCRIPCION` from `jobdaily`.`job_aficiones` where (`jobdaily`.`job_aficiones`.`codigo` > 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_anexos_contables`
-- 

DROP VIEW IF EXISTS `job_menu_anexos_contables`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_anexos_contables` AS select `jobdaily`.`job_anexos_contables`.`codigo` AS `id`,`jobdaily`.`job_anexos_contables`.`codigo` AS `CODIGO_ANEXO`,`jobdaily`.`job_anexos_contables`.`descripcion` AS `DESCRIPCION` from `jobdaily`.`job_anexos_contables` where (`jobdaily`.`job_anexos_contables`.`codigo` <> _latin1'');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_articulos`
-- 

DROP VIEW IF EXISTS `job_menu_articulos`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_articulos` AS select `jobdaily`.`job_articulos`.`codigo` AS `id`,`jobdaily`.`job_articulos`.`codigo` AS `CODIGO`,`jobdaily`.`job_referencias_proveedor`.`referencia` AS `REFERENCIA`,concat(if(isnull((select `jobdaily`.`job_estructura_grupos`.`descripcion` AS `descripcion` from `jobdaily`.`job_estructura_grupos` where (`jobdaily`.`job_estructura_grupos`.`codigo` = (select `jobdaily`.`job_estructura_grupos`.`codigo_padre` AS `codigo_padre` from `jobdaily`.`job_estructura_grupos` where (`jobdaily`.`job_estructura_grupos`.`codigo` = `jobdaily`.`job_articulos`.`codigo_estructura_grupo`))))),_utf8'',(select `jobdaily`.`job_estructura_grupos`.`codigo_padre` AS `codigo_padre` from `jobdaily`.`job_estructura_grupos` where (`jobdaily`.`job_estructura_grupos`.`codigo` = `jobdaily`.`job_articulos`.`codigo_estructura_grupo`))),_utf8' ',(select `jobdaily`.`job_estructura_grupos`.`descripcion` AS `descripcion` from `jobdaily`.`job_estructura_grupos` where (`jobdaily`.`job_estructura_grupos`.`codigo` = `jobdaily`.`job_articulos`.`codigo_estructura_grupo`)),_utf8' ',`jobdaily`.`job_articulos`.`descripcion`) AS `DESCRIPCION`,`jobdaily`.`job_marcas`.`descripcion` AS `MARCA`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),`jobdaily`.`job_terceros`.`primer_nombre`,_latin1''),_latin1' ',if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1''),_latin1' ',if((`jobdaily`.`job_terceros`.`primer_apellido` is not null),`jobdaily`.`job_terceros`.`primer_apellido`,_latin1''),_latin1' ',if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1''),_latin1' ',if((`jobdaily`.`job_terceros`.`razon_social` is not null),`jobdaily`.`job_terceros`.`razon_social`,_latin1'')) AS `PROVEEDOR` from ((((`jobdaily`.`job_articulos` join `jobdaily`.`job_marcas`) join `jobdaily`.`job_referencias_proveedor`) join `jobdaily`.`job_terceros`) join `jobdaily`.`job_proveedores`) where ((`jobdaily`.`job_articulos`.`codigo_marca` = `jobdaily`.`job_marcas`.`codigo`) and (`jobdaily`.`job_articulos`.`codigo` = `jobdaily`.`job_referencias_proveedor`.`codigo_articulo`) and (`jobdaily`.`job_proveedores`.`documento_identidad` = `jobdaily`.`job_referencias_proveedor`.`documento_identidad_proveedor`) and (`jobdaily`.`job_proveedores`.`documento_identidad` = `jobdaily`.`job_terceros`.`documento_identidad`) and (`jobdaily`.`job_articulos`.`codigo` <> _latin1''));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_asignacion_turnos`
-- 

DROP VIEW IF EXISTS `job_menu_asignacion_turnos`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_asignacion_turnos` AS select `jobdaily`.`job_asignacion_turnos`.`consecutivo` AS `id`,`jobdaily`.`job_sucursales`.`nombre` AS `SUCURSAL`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`primer_apellido` is not null),concat(`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),concat(`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1' '),_latin1'')) AS `EMPLEADO`,`jobdaily`.`job_turnos_laborales`.`descripcion` AS `DESCRIPCION_TURNO`,`jobdaily`.`job_asignacion_turnos`.`fecha_inicial` AS `FECHA_INICIAL`,`jobdaily`.`job_asignacion_turnos`.`fecha_final` AS `FECHA_FINAL` from (((((`jobdaily`.`job_terceros` join `jobdaily`.`job_aspirantes`) join `jobdaily`.`job_sucursal_contrato_empleados`) join `jobdaily`.`job_asignacion_turnos`) join `jobdaily`.`job_sucursales`) join `jobdaily`.`job_turnos_laborales`) where ((`jobdaily`.`job_sucursal_contrato_empleados`.`documento_identidad_empleado` = `jobdaily`.`job_aspirantes`.`documento_identidad`) and (`jobdaily`.`job_aspirantes`.`documento_identidad` = `jobdaily`.`job_terceros`.`documento_identidad`) and (`jobdaily`.`job_asignacion_turnos`.`documento_identidad_empleado` = `jobdaily`.`job_sucursal_contrato_empleados`.`documento_identidad_empleado`) and (`jobdaily`.`job_asignacion_turnos`.`codigo_sucursal` = `jobdaily`.`job_sucursales`.`codigo`) and (`jobdaily`.`job_asignacion_turnos`.`codigo_turno` = `jobdaily`.`job_turnos_laborales`.`codigo`));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_aspirantes`
-- 

DROP VIEW IF EXISTS `job_menu_aspirantes`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_aspirantes` AS select `jobdaily`.`job_terceros`.`documento_identidad` AS `id`,`jobdaily`.`job_terceros`.`documento_identidad` AS `NUMERO_DOCUMENTO`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),`jobdaily`.`job_terceros`.`primer_nombre`,_latin1''),_latin1' ',if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1''),_latin1' ',if((`jobdaily`.`job_terceros`.`primer_apellido` is not null),`jobdaily`.`job_terceros`.`primer_apellido`,_latin1''),_latin1' ',if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1''),_latin1' ',if((`jobdaily`.`job_terceros`.`razon_social` is not null),`jobdaily`.`job_terceros`.`razon_social`,_latin1'')) AS `NOMBRE_COMPLETO` from (`jobdaily`.`job_terceros` join `jobdaily`.`job_aspirantes`) where (`jobdaily`.`job_aspirantes`.`documento_identidad` = `jobdaily`.`job_terceros`.`documento_identidad`);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_auxiliares_contables`
-- 

DROP VIEW IF EXISTS `job_menu_auxiliares_contables`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_auxiliares_contables` AS select concat(`jobdaily`.`job_auxiliares_contables`.`codigo_empresa`,_utf8'|',`jobdaily`.`job_auxiliares_contables`.`codigo_anexo_contable`,_utf8'|',`jobdaily`.`job_auxiliares_contables`.`codigo`) AS `id`,`jobdaily`.`job_auxiliares_contables`.`codigo` AS `CODIGO`,`jobdaily`.`job_auxiliares_contables`.`descripcion` AS `DESCRIPCION`,`jobdaily`.`job_anexos_contables`.`descripcion` AS `ANEXO_CONTABLE`,`jobdaily`.`job_empresas`.`razon_social` AS `EMPRESA`,`jobdaily`.`job_empresas`.`codigo` AS `id_empresa` from ((`jobdaily`.`job_auxiliares_contables` join `jobdaily`.`job_anexos_contables`) join `jobdaily`.`job_empresas`) where ((`jobdaily`.`job_auxiliares_contables`.`codigo_anexo_contable` = `jobdaily`.`job_anexos_contables`.`codigo`) and (`jobdaily`.`job_auxiliares_contables`.`codigo_empresa` = `jobdaily`.`job_empresas`.`codigo`) and (`jobdaily`.`job_auxiliares_contables`.`codigo` > 0));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_auxilio_transporte`
-- 

DROP VIEW IF EXISTS `job_menu_auxilio_transporte`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_auxilio_transporte` AS select `jobdaily`.`job_auxilio_transporte`.`codigo` AS `id`,`jobdaily`.`job_auxilio_transporte`.`codigo` AS `CODIGO`,`jobdaily`.`job_auxilio_transporte`.`fecha` AS `FECHA`,concat(_utf8'$ ',format(`jobdaily`.`job_auxilio_transporte`.`valor`,0)) AS `VALOR` from `jobdaily`.`job_auxilio_transporte`;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_bancos`
-- 

DROP VIEW IF EXISTS `job_menu_bancos`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_bancos` AS select `jobdaily`.`job_bancos`.`codigo` AS `id`,`jobdaily`.`job_bancos`.`codigo` AS `CODIGO`,`jobdaily`.`job_bancos`.`descripcion` AS `DESCRIPCION`,if(((`jobdaily`.`job_terceros`.`tipo_persona` = _latin1'1') or (`jobdaily`.`job_terceros`.`tipo_persona` = _latin1'4')),concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' ',if(((`jobdaily`.`job_terceros`.`segundo_nombre` is not null) and (`jobdaily`.`job_terceros`.`segundo_nombre` <> _latin1'')),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1''),`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' ',if(((`jobdaily`.`job_terceros`.`segundo_apellido` is not null) and (`jobdaily`.`job_terceros`.`segundo_apellido` <> _latin1'')),`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1'')),`jobdaily`.`job_terceros`.`razon_social`) AS `TERCERO`,`jobdaily`.`job_terceros`.`documento_identidad` AS `DOCUMENTO_IDENTIDAD`,concat(`jobdaily`.`job_localidades`.`nombre`,_latin1', ',`jobdaily`.`job_municipios`.`nombre`,_latin1', ',`jobdaily`.`job_departamentos`.`nombre`,_latin1', ',`jobdaily`.`job_paises`.`nombre`) AS `CIUDAD_RESIDENCIA` from (((((`jobdaily`.`job_bancos` join `jobdaily`.`job_terceros`) join `jobdaily`.`job_localidades`) join `jobdaily`.`job_municipios`) join `jobdaily`.`job_departamentos`) join `jobdaily`.`job_paises`) where ((`jobdaily`.`job_bancos`.`codigo` > 0) and (`jobdaily`.`job_bancos`.`documento_identidad_tercero` = `jobdaily`.`job_terceros`.`documento_identidad`) and (`jobdaily`.`job_terceros`.`codigo_iso_localidad` = `jobdaily`.`job_paises`.`codigo_iso`) and (`jobdaily`.`job_terceros`.`codigo_iso_localidad` = `jobdaily`.`job_departamentos`.`codigo_iso`) and (`jobdaily`.`job_terceros`.`codigo_dane_departamento_localidad` = `jobdaily`.`job_departamentos`.`codigo_dane_departamento`) and (`jobdaily`.`job_terceros`.`codigo_iso_localidad` = `jobdaily`.`job_municipios`.`codigo_iso`) and (`jobdaily`.`job_terceros`.`codigo_dane_departamento_localidad` = `jobdaily`.`job_municipios`.`codigo_dane_departamento`) and (`jobdaily`.`job_terceros`.`codigo_dane_municipio_localidad` = `jobdaily`.`job_municipios`.`codigo_dane_municipio`) and (`jobdaily`.`job_terceros`.`codigo_iso_localidad` = `jobdaily`.`job_localidades`.`codigo_iso`) and (`jobdaily`.`job_terceros`.`codigo_dane_departamento_localidad` = `jobdaily`.`job_localidades`.`codigo_dane_departamento`) and (`jobdaily`.`job_terceros`.`codigo_dane_municipio_localidad` = `jobdaily`.`job_localidades`.`codigo_dane_municipio`) and (`jobdaily`.`job_terceros`.`tipo_localidad` = `jobdaily`.`job_localidades`.`tipo`) and (`jobdaily`.`job_terceros`.`codigo_dane_localidad` = `jobdaily`.`job_localidades`.`codigo_dane_localidad`));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_barrios`
-- 

DROP VIEW IF EXISTS `job_menu_barrios`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_barrios` AS select concat(`jobdaily`.`job_localidades`.`codigo_iso`,_latin1'|',`jobdaily`.`job_localidades`.`codigo_dane_departamento`,_latin1'|',`jobdaily`.`job_localidades`.`codigo_dane_municipio`,_latin1'|',`jobdaily`.`job_localidades`.`tipo`,_latin1'|',`jobdaily`.`job_localidades`.`codigo_dane_localidad`) AS `id`,`jobdaily`.`job_localidades`.`nombre` AS `NOMBRE`,`jobdaily`.`job_municipios`.`nombre` AS `MUNICIPIO`,`jobdaily`.`job_departamentos`.`nombre` AS `DEPARTAMENTO`,`jobdaily`.`job_paises`.`nombre` AS `PAIS` from (((`jobdaily`.`job_localidades` join `jobdaily`.`job_municipios`) join `jobdaily`.`job_departamentos`) join `jobdaily`.`job_paises`) where ((`jobdaily`.`job_localidades`.`codigo_iso` = `jobdaily`.`job_municipios`.`codigo_iso`) and (`jobdaily`.`job_localidades`.`codigo_dane_departamento` = `jobdaily`.`job_municipios`.`codigo_dane_departamento`) and (`jobdaily`.`job_localidades`.`codigo_dane_municipio` = `jobdaily`.`job_municipios`.`codigo_dane_municipio`) and (`jobdaily`.`job_localidades`.`codigo_iso` = `jobdaily`.`job_departamentos`.`codigo_iso`) and (`jobdaily`.`job_localidades`.`codigo_dane_departamento` = `jobdaily`.`job_departamentos`.`codigo_dane_departamento`) and (`jobdaily`.`job_localidades`.`codigo_iso` = `jobdaily`.`job_paises`.`codigo_iso`) and (`jobdaily`.`job_localidades`.`tipo` = _latin1'B')) order by `jobdaily`.`job_localidades`.`nombre`;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_bodegas`
-- 

DROP VIEW IF EXISTS `job_menu_bodegas`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_bodegas` AS select concat(`jobdaily`.`job_bodegas`.`codigo`,_utf8'|',`jobdaily`.`job_bodegas`.`codigo_sucursal`) AS `id`,`jobdaily`.`job_bodegas`.`codigo` AS `CODIGO`,`jobdaily`.`job_bodegas`.`nombre` AS `NOMBRE`,`jobdaily`.`job_bodegas`.`descripcion` AS `DESCRIPCION`,`jobdaily`.`job_sucursales`.`nombre` AS `SUCURSAL` from (`jobdaily`.`job_bodegas` join `jobdaily`.`job_sucursales`) where ((`jobdaily`.`job_bodegas`.`codigo_sucursal` = `jobdaily`.`job_sucursales`.`codigo`) and (`jobdaily`.`job_bodegas`.`codigo` > 0));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_cargos`
-- 

DROP VIEW IF EXISTS `job_menu_cargos`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_cargos` AS select `jobdaily`.`job_cargos`.`codigo` AS `id`,`jobdaily`.`job_cargos`.`codigo` AS `CODIGO`,`jobdaily`.`job_cargos`.`nombre` AS `NOMBRE`,concat(_latin1'INTERNO_',`jobdaily`.`job_cargos`.`interno`) AS `INTERNO` from `jobdaily`.`job_cargos` where (`jobdaily`.`job_cargos`.`codigo` <> 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_cargos_externos`
-- 

DROP VIEW IF EXISTS `job_menu_cargos_externos`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_cargos_externos` AS select `jobdaily`.`job_cargos`.`codigo` AS `id`,`jobdaily`.`job_cargos`.`codigo` AS `CODIGO`,`jobdaily`.`job_cargos`.`nombre` AS `NOMBRE` from `jobdaily`.`job_cargos` where ((`jobdaily`.`job_cargos`.`interno` = _latin1'0') and (`jobdaily`.`job_cargos`.`codigo` <> 0));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_causaciones_prestaciones_sociales`
-- 

DROP VIEW IF EXISTS `job_menu_causaciones_prestaciones_sociales`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_causaciones_prestaciones_sociales` AS select concat(`jobdaily`.`job_causaciones_prestaciones_sociales`.`codigo_empresa`,_utf8'|',`jobdaily`.`job_causaciones_prestaciones_sociales`.`fecha_liquidacion`,_utf8'|',`jobdaily`.`job_causaciones_prestaciones_sociales`.`documento_identidad_empleado`,_utf8'|',`jobdaily`.`job_causaciones_prestaciones_sociales`.`codigo_transaccion_contable`) AS `id`,`jobdaily`.`job_empresas`.`razon_social` AS `EMPRESAS`,`jobdaily`.`job_causaciones_prestaciones_sociales`.`fecha_liquidacion` AS `FECHA_LIQUIDACION` from (`jobdaily`.`job_causaciones_prestaciones_sociales` join `jobdaily`.`job_empresas`) where (`jobdaily`.`job_causaciones_prestaciones_sociales`.`codigo_empresa` = `jobdaily`.`job_empresas`.`codigo`) group by `jobdaily`.`job_causaciones_prestaciones_sociales`.`codigo_empresa`,`jobdaily`.`job_causaciones_prestaciones_sociales`.`fecha_liquidacion`;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_conceptos_contabilizacion`
-- 

DROP VIEW IF EXISTS `job_menu_conceptos_contabilizacion`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_conceptos_contabilizacion` AS select `ccp`.`codigo` AS `id`,`ccp`.`descripcion` AS `DESCRIPCION`,concat(_latin1'REGIMEN_',`ccp`.`regimen_ventas_empresa`) AS `REGIMEN_VENTAS`,concat(_latin1'REGIMEN_',`ccp`.`regimen_persona`) AS `REGIMEN_PERSONA` from `jobdaily`.`job_conceptos_contabilizacion_compras` `CCP`;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_conceptos_devolucion_compras`
-- 

DROP VIEW IF EXISTS `job_menu_conceptos_devolucion_compras`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_conceptos_devolucion_compras` AS select `cdc`.`codigo` AS `id`,`cdc`.`codigo` AS `CODIGO`,`cdc`.`descripcion` AS `DESCRIPCION`,if((`cdc`.`regimen_ventas_empresa` = 1),_utf8'Regimen comun',_utf8'Regimen simplificado') AS `REGIMEN_VENTAS`,if((`cdc`.`regimen_persona` = 1),_utf8'Regimen comun',_utf8'Regimen simplificado') AS `REGIMEN_PERSONA` from `jobdaily`.`job_conceptos_devolucion_compras` `CDC`;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_conceptos_dian`
-- 

DROP VIEW IF EXISTS `job_menu_conceptos_dian`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_conceptos_dian` AS select `jobdaily`.`job_conceptos_dian`.`codigo` AS `id`,`jobdaily`.`job_formatos_dian`.`descripcion` AS `FORMATO_DIAN`,`jobdaily`.`job_conceptos_dian`.`codigo` AS `CODIGO_DIAN`,`jobdaily`.`job_conceptos_dian`.`descripcion` AS `DESCRIPCION`,format(`jobdaily`.`job_conceptos_dian`.`valor_base`,0) AS `VALOR_BASE` from (`jobdaily`.`job_formatos_dian` join `jobdaily`.`job_conceptos_dian`) where ((`jobdaily`.`job_conceptos_dian`.`codigo_formato_dian` = `jobdaily`.`job_formatos_dian`.`codigo`) and (`jobdaily`.`job_conceptos_dian`.`codigo` > 0));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_conceptos_prestamos`
-- 

DROP VIEW IF EXISTS `job_menu_conceptos_prestamos`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_conceptos_prestamos` AS select `jobdaily`.`job_conceptos_prestamos`.`codigo` AS `id`,`jobdaily`.`job_conceptos_prestamos`.`codigo` AS `CODIGO`,`jobdaily`.`job_conceptos_prestamos`.`descripcion` AS `DESCRIPCION` from `jobdaily`.`job_conceptos_prestamos` where (`jobdaily`.`job_conceptos_prestamos`.`codigo` > 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_conexiones`
-- 

DROP VIEW IF EXISTS `job_menu_conexiones`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_conexiones` AS select concat(`jobdaily`.`job_conexiones`.`codigo_sucursal`,_utf8'|',`jobdaily`.`job_conexiones`.`codigo_usuario`,_utf8'|',`jobdaily`.`job_conexiones`.`fecha`) AS `id`,`jobdaily`.`job_conexiones`.`fecha` AS `id_fecha`,date_format(`jobdaily`.`job_conexiones`.`fecha`,_utf8'%Y/%m/%d') AS `FECHA`,date_format(`jobdaily`.`job_conexiones`.`fecha`,_utf8'%T') AS `HORA`,`jobdaily`.`job_usuarios`.`nombre` AS `USUARIO`,`jobdaily`.`job_conexiones`.`ip` AS `IP`,`jobdaily`.`job_conexiones`.`proxy` AS `PROXY` from ((`jobdaily`.`job_usuarios` join `jobdaily`.`job_sucursales`) join `jobdaily`.`job_conexiones`) where ((`jobdaily`.`job_conexiones`.`codigo_usuario` = `jobdaily`.`job_usuarios`.`codigo`) and (`jobdaily`.`job_conexiones`.`codigo_sucursal` = `jobdaily`.`job_sucursales`.`codigo`));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_control_prestamos_empleados`
-- 

DROP VIEW IF EXISTS `job_menu_control_prestamos_empleados`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_control_prestamos_empleados` AS select concat(`jobdaily`.`job_control_prestamos_empleados`.`documento_identidad_empleado`,_utf8'|',`jobdaily`.`job_control_prestamos_empleados`.`fecha_generacion`,_utf8'|',`jobdaily`.`job_control_prestamos_empleados`.`consecutivo`,_utf8'|',`jobdaily`.`job_control_prestamos_empleados`.`concepto_prestamo`) AS `id`,`jobdaily`.`job_sucursales`.`codigo` AS `id_sucursal`,`jobdaily`.`job_sucursales`.`nombre` AS `SUCURSAL_LABORA`,if(((`jobdaily`.`job_terceros`.`tipo_persona` = _latin1'1') or (`jobdaily`.`job_terceros`.`tipo_persona` = _latin1'4')),concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' ',if(((`jobdaily`.`job_terceros`.`segundo_nombre` is not null) and (`jobdaily`.`job_terceros`.`segundo_nombre` <> _latin1'')),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1''),`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' ',if(((`jobdaily`.`job_terceros`.`segundo_apellido` is not null) and (`jobdaily`.`job_terceros`.`segundo_apellido` <> _latin1'')),`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1'')),`jobdaily`.`job_terceros`.`razon_social`) AS `EMPLEADO`,`jobdaily`.`job_conceptos_prestamos`.`descripcion` AS `CONCEPTO_PRESTAMO`,format(`jobdaily`.`job_control_prestamos_empleados`.`valor_total`,0) AS `VALOR_PRESTAMO` from (((`jobdaily`.`job_terceros` join `jobdaily`.`job_control_prestamos_empleados`) join `jobdaily`.`job_conceptos_prestamos`) join `jobdaily`.`job_sucursales`) where ((`jobdaily`.`job_control_prestamos_empleados`.`documento_identidad_empleado` = `jobdaily`.`job_terceros`.`documento_identidad`) and (`jobdaily`.`job_control_prestamos_empleados`.`concepto_prestamo` = `jobdaily`.`job_conceptos_prestamos`.`codigo`) and (`jobdaily`.`job_control_prestamos_empleados`.`codigo_sucursal` = `jobdaily`.`job_sucursales`.`codigo`));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_control_prestamos_terceros`
-- 

DROP VIEW IF EXISTS `job_menu_control_prestamos_terceros`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_control_prestamos_terceros` AS select concat(`jobdaily`.`job_control_prestamos_terceros`.`codigo_empresa`,_utf8'|',`jobdaily`.`job_control_prestamos_terceros`.`documento_identidad_empleado`,_utf8'|',`jobdaily`.`job_control_prestamos_terceros`.`obligacion`) AS `id`,`jobdaily`.`job_sucursales`.`codigo` AS `id_sucursal`,`jobdaily`.`job_sucursales`.`nombre` AS `SUCURSAL_LABORA`,if(((`jobdaily`.`job_terceros`.`tipo_persona` = _latin1'1') or (`jobdaily`.`job_terceros`.`tipo_persona` = _latin1'4')),concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' ',if(((`jobdaily`.`job_terceros`.`segundo_nombre` is not null) and (`jobdaily`.`job_terceros`.`segundo_nombre` <> _latin1'')),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1''),`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' ',if(((`jobdaily`.`job_terceros`.`segundo_apellido` is not null) and (`jobdaily`.`job_terceros`.`segundo_apellido` <> _latin1'')),`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1'')),`jobdaily`.`job_terceros`.`razon_social`) AS `EMPLEADO`,concat(`jobdaily`.`job_transacciones_contables_empleado`.`codigo_contable`,_latin1' - ',`jobdaily`.`job_transacciones_contables_empleado`.`descripcion`) AS `TRANSACCION_CONTABLE_DESCUENTO`,`jobdaily`.`job_control_prestamos_terceros`.`obligacion` AS `OBLIGACION`,concat(_latin1'ESTADO_',`jobdaily`.`job_control_prestamos_terceros`.`estado`) AS `ESTADO` from (((`jobdaily`.`job_terceros` join `jobdaily`.`job_control_prestamos_terceros`) join `jobdaily`.`job_sucursales`) join `jobdaily`.`job_transacciones_contables_empleado`) where ((`jobdaily`.`job_control_prestamos_terceros`.`documento_identidad_empleado` = `jobdaily`.`job_terceros`.`documento_identidad`) and (`jobdaily`.`job_control_prestamos_terceros`.`codigo_sucursal` = `jobdaily`.`job_sucursales`.`codigo`) and (`jobdaily`.`job_control_prestamos_terceros`.`transaccion_contable_descuento` = `jobdaily`.`job_transacciones_contables_empleado`.`codigo`));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_corregimientos`
-- 

DROP VIEW IF EXISTS `job_menu_corregimientos`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_corregimientos` AS select concat(`jobdaily`.`job_localidades`.`codigo_iso`,_latin1'|',`jobdaily`.`job_localidades`.`codigo_dane_departamento`,_latin1'|',`jobdaily`.`job_localidades`.`codigo_dane_municipio`,_latin1'|',`jobdaily`.`job_localidades`.`tipo`,_latin1'|',`jobdaily`.`job_localidades`.`codigo_dane_localidad`) AS `id`,`jobdaily`.`job_localidades`.`nombre` AS `NOMBRE`,`jobdaily`.`job_municipios`.`nombre` AS `MUNICIPIO`,`jobdaily`.`job_departamentos`.`nombre` AS `DEPARTAMENTO`,`jobdaily`.`job_paises`.`nombre` AS `PAIS` from (((`jobdaily`.`job_localidades` join `jobdaily`.`job_municipios`) join `jobdaily`.`job_departamentos`) join `jobdaily`.`job_paises`) where ((`jobdaily`.`job_localidades`.`codigo_iso` = `jobdaily`.`job_municipios`.`codigo_iso`) and (`jobdaily`.`job_localidades`.`codigo_dane_departamento` = `jobdaily`.`job_municipios`.`codigo_dane_departamento`) and (`jobdaily`.`job_localidades`.`codigo_dane_municipio` = `jobdaily`.`job_municipios`.`codigo_dane_municipio`) and (`jobdaily`.`job_localidades`.`codigo_iso` = `jobdaily`.`job_departamentos`.`codigo_iso`) and (`jobdaily`.`job_localidades`.`codigo_dane_departamento` = `jobdaily`.`job_departamentos`.`codigo_dane_departamento`) and (`jobdaily`.`job_localidades`.`codigo_iso` = `jobdaily`.`job_paises`.`codigo_iso`) and (`jobdaily`.`job_localidades`.`tipo` = _latin1'C')) order by `jobdaily`.`job_localidades`.`nombre`;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_cuentas_bancarias`
-- 

DROP VIEW IF EXISTS `job_menu_cuentas_bancarias`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_cuentas_bancarias` AS select concat(`cb`.`codigo_sucursal`,_utf8'|',`cb`.`codigo_tipo_documento`,_utf8'|',`cb`.`codigo_sucursal_banco`,_utf8'|',`cb`.`codigo_iso`,_utf8'|',`cb`.`codigo_dane_departamento`,_utf8'|',`cb`.`codigo_dane_municipio`,_utf8'|',`cb`.`codigo_banco`,_utf8'|',`cb`.`numero`) AS `id`,`s`.`nombre` AS `SUCURSAL`,`b`.`descripcion` AS `BANCO`,`sb`.`nombre_sucursal` AS `SUCURSALES_BANCOS`,`cb`.`numero` AS `NUMERO`,`td`.`descripcion` AS `TIPO_DOCUMENTO` from ((((`jobdaily`.`job_cuentas_bancarias` `cb` join `jobdaily`.`job_bancos` `b`) join `jobdaily`.`job_sucursales_bancos` `sb`) join `jobdaily`.`job_tipos_documentos` `td`) join `jobdaily`.`job_sucursales` `s`) where ((`cb`.`codigo_banco` = `b`.`codigo`) and (`s`.`codigo` = `cb`.`codigo_sucursal`) and (`cb`.`codigo_sucursal_banco` = `sb`.`codigo`) and (`cb`.`codigo_banco` = `sb`.`codigo_banco`) and (`td`.`codigo` = `cb`.`codigo_tipo_documento`) and (`cb`.`numero` <> _latin1''));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_departamentos`
-- 

DROP VIEW IF EXISTS `job_menu_departamentos`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_departamentos` AS select concat(`jobdaily`.`job_departamentos`.`codigo_iso`,_latin1'|',`jobdaily`.`job_departamentos`.`codigo_dane_departamento`) AS `id`,`jobdaily`.`job_departamentos`.`codigo_iso` AS `id_codigo_iso`,`jobdaily`.`job_departamentos`.`codigo_dane_departamento` AS `CODIGO_DANE`,`jobdaily`.`job_departamentos`.`nombre` AS `NOMBRE`,`jobdaily`.`job_paises`.`nombre` AS `PAIS` from (`jobdaily`.`job_departamentos` join `jobdaily`.`job_paises`) where ((`jobdaily`.`job_departamentos`.`codigo_iso` = `jobdaily`.`job_paises`.`codigo_iso`) and (`jobdaily`.`job_departamentos`.`codigo_dane_departamento` <> _latin1''));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_departamentos_empresa`
-- 

DROP VIEW IF EXISTS `job_menu_departamentos_empresa`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_departamentos_empresa` AS select `jobdaily`.`job_departamentos_empresa`.`codigo` AS `id`,`jobdaily`.`job_departamentos_empresa`.`codigo` AS `CODIGO_DEPARTAMENTO`,`jobdaily`.`job_departamentos_empresa`.`nombre` AS `NOMBRE_DEPARTAMENTO`,concat(format(`jobdaily`.`job_departamentos_empresa`.`riesgos_profesionales`,2),_utf8'%') AS `RIESGOS_PROFESIONALES` from `jobdaily`.`job_departamentos_empresa` where (`jobdaily`.`job_departamentos_empresa`.`codigo` > 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_deportes`
-- 

DROP VIEW IF EXISTS `job_menu_deportes`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_deportes` AS select `jobdaily`.`job_deportes`.`codigo` AS `id`,`jobdaily`.`job_deportes`.`codigo` AS `CODIGO`,`jobdaily`.`job_deportes`.`descripcion` AS `DESCRIPCION` from `jobdaily`.`job_deportes` where (`jobdaily`.`job_deportes`.`codigo` > 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_domingos_festivos`
-- 

DROP VIEW IF EXISTS `job_menu_domingos_festivos`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_domingos_festivos` AS select `jobdaily`.`job_domingos_festivos`.`anio` AS `id`,`jobdaily`.`job_domingos_festivos`.`anio` AS `FECHA` from `jobdaily`.`job_domingos_festivos` group by `jobdaily`.`job_domingos_festivos`.`anio`;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_empresas`
-- 

DROP VIEW IF EXISTS `job_menu_empresas`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_empresas` AS select `jobdaily`.`job_empresas`.`codigo` AS `id`,`jobdaily`.`job_empresas`.`codigo` AS `CODIGO`,`jobdaily`.`job_empresas`.`razon_social` AS `RAZON_SOCIAL`,if((`jobdaily`.`job_empresas`.`activo` = 0),_utf8'Inactiva',_utf8'Activa') AS `ACTIVO`,if((`jobdaily`.`job_empresas`.`regimen` = 1),_utf8'Comun',_utf8'Simplificado') AS `REGIMEN`,`jobdaily`.`job_terceros`.`documento_identidad` AS `TERCERO` from (`jobdaily`.`job_empresas` join `jobdaily`.`job_terceros`) where ((`jobdaily`.`job_empresas`.`documento_identidad_tercero` = `jobdaily`.`job_terceros`.`documento_identidad`) and (`jobdaily`.`job_empresas`.`codigo` <> 0));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_entidades_parafiscales`
-- 

DROP VIEW IF EXISTS `job_menu_entidades_parafiscales`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_entidades_parafiscales` AS select `jobdaily`.`job_entidades_parafiscales`.`codigo` AS `id`,`jobdaily`.`job_entidades_parafiscales`.`codigo` AS `CODIGO`,`jobdaily`.`job_entidades_parafiscales`.`nombre` AS `NOMBRE`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),concat(concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`primer_apellido` is not null),concat(`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),concat(`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1''),_latin1'')),`jobdaily`.`job_terceros`.`razon_social`)) AS `NOMBRE_TERCERO` from (`jobdaily`.`job_entidades_parafiscales` join `jobdaily`.`job_terceros`) where ((`jobdaily`.`job_entidades_parafiscales`.`documento_identidad_tercero` = `jobdaily`.`job_terceros`.`documento_identidad`) and (`jobdaily`.`job_entidades_parafiscales`.`codigo` <> 0));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_escolaridad`
-- 

DROP VIEW IF EXISTS `job_menu_escolaridad`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_escolaridad` AS select `jobdaily`.`job_escolaridad`.`codigo` AS `id`,`jobdaily`.`job_escolaridad`.`codigo` AS `CODIGO`,`jobdaily`.`job_escolaridad`.`descripcion` AS `DESCRIPCION` from `jobdaily`.`job_escolaridad` where (`jobdaily`.`job_escolaridad`.`codigo` > 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_estado_mercancia`
-- 

DROP VIEW IF EXISTS `job_menu_estado_mercancia`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_estado_mercancia` AS select `jobdaily`.`job_estado_mercancia`.`codigo` AS `id`,`jobdaily`.`job_estado_mercancia`.`codigo` AS `CODIGO`,`jobdaily`.`job_estado_mercancia`.`descripcion` AS `DESCRIPCION` from `jobdaily`.`job_estado_mercancia`;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_estructura_grupos`
-- 

DROP VIEW IF EXISTS `job_menu_estructura_grupos`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_estructura_grupos` AS (select `a`.`codigo` AS `id`,`a`.`codigo` AS `CODIGO`,`a`.`descripcion` AS `DESCRIPCION`,_utf8'' AS `PADRE`,`c`.`descripcion` AS `RELACION`,`a`.`orden` AS `ORDEN` from ((`jobdaily`.`job_estructura_grupos` `a` join `jobdaily`.`job_estructura_grupos` `b`) join `jobdaily`.`job_grupos` `c`) where (isnull(`a`.`codigo_padre`) and (`a`.`codigo_grupo` = `c`.`codigo`))) union (select `a`.`codigo` AS `id`,`a`.`codigo` AS `CODIGO`,`a`.`descripcion` AS `DESCRIPCION`,`b`.`descripcion` AS `PADRE`,`c`.`descripcion` AS `RELACION`,`a`.`orden` AS `ORDEN` from ((`jobdaily`.`job_estructura_grupos` `a` join `jobdaily`.`job_estructura_grupos` `b`) join `jobdaily`.`job_grupos` `c`) where ((`a`.`codigo_padre` = `b`.`codigo`) and (`a`.`codigo_grupo` = `c`.`codigo`)));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_fechas_planillas`
-- 

DROP VIEW IF EXISTS `job_menu_fechas_planillas`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_fechas_planillas` AS select concat(`fp`.`codigo_planilla`,_utf8'|',date_format(`fp`.`fecha`,_utf8'%Y')) AS `id`,`p`.`descripcion` AS `PLANILLA`,date_format(`fp`.`fecha`,_utf8'%Y') AS `ANO` from (`jobdaily`.`job_fechas_planillas` `FP` join `jobdaily`.`job_planillas` `P`) where (`p`.`codigo` = `fp`.`codigo_planilla`) group by `fp`.`codigo_planilla`,date_format(`fp`.`fecha`,_utf8'%Y');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_formatos_dian`
-- 

DROP VIEW IF EXISTS `job_menu_formatos_dian`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_formatos_dian` AS select `jobdaily`.`job_formatos_dian`.`codigo` AS `id`,`jobdaily`.`job_formatos_dian`.`codigo` AS `CODIGO_DIAN`,`jobdaily`.`job_formatos_dian`.`descripcion` AS `DESCRIPCION` from `jobdaily`.`job_formatos_dian` where (`jobdaily`.`job_formatos_dian`.`codigo` > 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_gastos_prestaciones_sociales`
-- 

DROP VIEW IF EXISTS `job_menu_gastos_prestaciones_sociales`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_gastos_prestaciones_sociales` AS select `jobdaily`.`job_gastos_prestaciones_sociales`.`codigo` AS `id`,`jobdaily`.`job_gastos_prestaciones_sociales`.`codigo` AS `CODIGO`,`jobdaily`.`job_gastos_prestaciones_sociales`.`descripcion` AS `DESCRIPCION` from `jobdaily`.`job_gastos_prestaciones_sociales` where (`jobdaily`.`job_gastos_prestaciones_sociales`.`codigo` <> 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_grupos`
-- 

DROP VIEW IF EXISTS `job_menu_grupos`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_grupos` AS select `jobdaily`.`job_grupos`.`codigo` AS `id`,`jobdaily`.`job_grupos`.`codigo` AS `CODIGO`,`jobdaily`.`job_grupos`.`descripcion` AS `DESCRIPCION`,`jobdaily`.`job_grupos`.`orden` AS `ORDEN` from `jobdaily`.`job_grupos` where (`jobdaily`.`job_grupos`.`codigo` <> 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_idiomas`
-- 

DROP VIEW IF EXISTS `job_menu_idiomas`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_idiomas` AS select `jobdaily`.`job_idiomas`.`codigo` AS `id`,`jobdaily`.`job_idiomas`.`codigo` AS `CODIGO`,`jobdaily`.`job_idiomas`.`descripcion` AS `DESCRIPCION` from `jobdaily`.`job_idiomas` where (`jobdaily`.`job_idiomas`.`codigo` > 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_ingreso_empleados`
-- 

DROP VIEW IF EXISTS `job_menu_ingreso_empleados`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_ingreso_empleados` AS select concat(`jobdaily`.`job_ingreso_empleados`.`codigo_empresa`,_utf8'|',`jobdaily`.`job_ingreso_empleados`.`documento_identidad_empleado`,_utf8'|',`jobdaily`.`job_ingreso_empleados`.`fecha_ingreso`) AS `id`,`jobdaily`.`job_ingreso_empleados`.`documento_identidad_empleado` AS `NUMERO_DOCUMENTO`,if(((`jobdaily`.`job_terceros`.`tipo_persona` = _latin1'1') or (`jobdaily`.`job_terceros`.`tipo_persona` = _latin1'4')),concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' ',if(((`jobdaily`.`job_terceros`.`segundo_nombre` is not null) and (`jobdaily`.`job_terceros`.`segundo_nombre` <> _latin1'')),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1''),`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' ',if(((`jobdaily`.`job_terceros`.`segundo_apellido` is not null) and (`jobdaily`.`job_terceros`.`segundo_apellido` <> _latin1'')),`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1'')),`jobdaily`.`job_terceros`.`razon_social`) AS `NOMBRE_COMPLETO`,concat(_latin1'ESTADO_',`jobdaily`.`job_ingreso_empleados`.`estado`) AS `ESTADO` from (`jobdaily`.`job_terceros` join `jobdaily`.`job_ingreso_empleados`) where ((`jobdaily`.`job_ingreso_empleados`.`documento_identidad_empleado` = `jobdaily`.`job_terceros`.`documento_identidad`) and (`jobdaily`.`job_ingreso_empleados`.`documento_identidad_empleado` > 0));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_liquidar_empleado`
-- 

DROP VIEW IF EXISTS `job_menu_liquidar_empleado`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_liquidar_empleado` AS select concat(`jobdaily`.`job_liquidaciones_empleado`.`codigo_empresa`,_utf8'|',`jobdaily`.`job_liquidaciones_empleado`.`documento_identidad_empleado`,_utf8'|',`jobdaily`.`job_liquidaciones_empleado`.`fecha_generacion`,_utf8'|',`jobdaily`.`job_liquidaciones_empleado`.`motivo_retiro`) AS `id`,`jobdaily`.`job_sucursales`.`codigo` AS `id_sucursal`,`jobdaily`.`job_sucursales`.`nombre` AS `SUCURSAL_LABORA`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),concat(concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`primer_apellido` is not null),concat(`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),concat(`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1''),_latin1'')),`jobdaily`.`job_terceros`.`razon_social`)) AS `NOMBRE_EMPLEADO`,date_format(`jobdaily`.`job_liquidaciones_empleado`.`fecha_generacion`,_utf8'%Y/%m/%d') AS `FECHA_GENERACION` from ((`jobdaily`.`job_liquidaciones_empleado` join `jobdaily`.`job_terceros`) join `jobdaily`.`job_sucursales`) where ((`jobdaily`.`job_liquidaciones_empleado`.`documento_identidad_empleado` = `jobdaily`.`job_terceros`.`documento_identidad`) and (`jobdaily`.`job_liquidaciones_empleado`.`codigo_sucursal` = `jobdaily`.`job_sucursales`.`codigo`));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_marcas`
-- 

DROP VIEW IF EXISTS `job_menu_marcas`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_marcas` AS select `jobdaily`.`job_marcas`.`codigo` AS `id`,`jobdaily`.`job_marcas`.`codigo` AS `CODIGO`,`jobdaily`.`job_marcas`.`descripcion` AS `DESCRIPCION` from `jobdaily`.`job_marcas` where (`jobdaily`.`job_marcas`.`codigo` <> 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_motivos_incapacidad`
-- 

DROP VIEW IF EXISTS `job_menu_motivos_incapacidad`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_motivos_incapacidad` AS select `jobdaily`.`job_motivos_incapacidad`.`codigo` AS `id`,`jobdaily`.`job_motivos_incapacidad`.`codigo` AS `CODIGO`,`jobdaily`.`job_motivos_incapacidad`.`descripcion` AS `DESCRIPCION` from `jobdaily`.`job_motivos_incapacidad`;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_motivos_retiro`
-- 

DROP VIEW IF EXISTS `job_menu_motivos_retiro`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_motivos_retiro` AS select `jobdaily`.`job_motivos_retiro`.`codigo` AS `id`,`jobdaily`.`job_motivos_retiro`.`codigo` AS `CODIGO`,`jobdaily`.`job_motivos_retiro`.`descripcion` AS `DESCRIPCION` from `jobdaily`.`job_motivos_retiro` where (`jobdaily`.`job_motivos_retiro`.`codigo` > 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_movimiento_liquidacion_vacaciones`
-- 

DROP VIEW IF EXISTS `job_menu_movimiento_liquidacion_vacaciones`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_movimiento_liquidacion_vacaciones` AS select concat(`jobdaily`.`job_movimiento_liquidacion_vacaciones`.`codigo_sucursal`,_utf8'|',`jobdaily`.`job_movimiento_liquidacion_vacaciones`.`documento_identidad_empleado`,_utf8'|',`jobdaily`.`job_movimiento_liquidacion_vacaciones`.`fecha_inicio_tiempo`,_utf8'|',`jobdaily`.`job_movimiento_liquidacion_vacaciones`.`estado_liquidacion`) AS `id`,`jobdaily`.`job_sucursales`.`codigo` AS `id_sucursal`,`jobdaily`.`job_sucursales`.`nombre` AS `ALMACEN`,if(((`jobdaily`.`job_terceros`.`tipo_persona` = _latin1'1') or (`jobdaily`.`job_terceros`.`tipo_persona` = _latin1'4')),concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' ',if(((`jobdaily`.`job_terceros`.`segundo_nombre` is not null) and (`jobdaily`.`job_terceros`.`segundo_nombre` <> _latin1'')),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1''),`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' ',if(((`jobdaily`.`job_terceros`.`segundo_apellido` is not null) and (`jobdaily`.`job_terceros`.`segundo_apellido` <> _latin1'')),`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1'')),`jobdaily`.`job_terceros`.`razon_social`) AS `EMPLEADO`,date_format(`jobdaily`.`job_movimiento_liquidacion_vacaciones`.`fecha_inicio_tiempo`,_utf8'%Y-%m-%d') AS `FECHA_INCAPACIDAD_TIEMPO`,concat(_latin1'ESTADO_',`jobdaily`.`job_movimiento_liquidacion_vacaciones`.`estado_liquidacion`) AS `ESTADO` from ((((`jobdaily`.`job_terceros` join `jobdaily`.`job_aspirantes`) join `jobdaily`.`job_sucursal_contrato_empleados`) join `jobdaily`.`job_movimiento_liquidacion_vacaciones`) join `jobdaily`.`job_sucursales`) where ((`jobdaily`.`job_sucursal_contrato_empleados`.`documento_identidad_empleado` = `jobdaily`.`job_aspirantes`.`documento_identidad`) and (`jobdaily`.`job_aspirantes`.`documento_identidad` = `jobdaily`.`job_terceros`.`documento_identidad`) and (`jobdaily`.`job_movimiento_liquidacion_vacaciones`.`documento_identidad_empleado` = `jobdaily`.`job_sucursal_contrato_empleados`.`documento_identidad_empleado`) and (`jobdaily`.`job_movimiento_liquidacion_vacaciones`.`codigo_sucursal` = `jobdaily`.`job_sucursales`.`codigo`)) group by `jobdaily`.`job_movimiento_liquidacion_vacaciones`.`codigo_sucursal`,`jobdaily`.`job_movimiento_liquidacion_vacaciones`.`documento_identidad_empleado`,`jobdaily`.`job_movimiento_liquidacion_vacaciones`.`fecha_inicio_tiempo`;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_movimiento_novedades_manuales`
-- 

DROP VIEW IF EXISTS `job_menu_movimiento_novedades_manuales`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_movimiento_novedades_manuales` AS select concat(`jobdaily`.`job_movimiento_novedades_manuales`.`ano_generacion`,_utf8'|',`jobdaily`.`job_movimiento_novedades_manuales`.`mes_generacion`,_utf8'|',`jobdaily`.`job_movimiento_novedades_manuales`.`codigo_planilla`,_utf8'|',`jobdaily`.`job_movimiento_novedades_manuales`.`periodo_pago`,_utf8'|',`jobdaily`.`job_movimiento_novedades_manuales`.`documento_identidad_empleado`,_utf8'|',`jobdaily`.`job_movimiento_novedades_manuales`.`consecutivo`,_utf8'|',`jobdaily`.`job_movimiento_novedades_manuales`.`codigo_sucursal`,_utf8'|',`jobdaily`.`job_movimiento_novedades_manuales`.`fecha_pago_planilla`) AS `id`,`jobdaily`.`job_sucursales`.`codigo` AS `id_sucursal`,`jobdaily`.`job_sucursales`.`nombre` AS `SUCURSAL`,if(((`jobdaily`.`job_terceros`.`tipo_persona` = _latin1'1') or (`jobdaily`.`job_terceros`.`tipo_persona` = _latin1'4')),concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' ',if(((`jobdaily`.`job_terceros`.`segundo_nombre` is not null) and (`jobdaily`.`job_terceros`.`segundo_nombre` <> _latin1'')),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1''),`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' ',if(((`jobdaily`.`job_terceros`.`segundo_apellido` is not null) and (`jobdaily`.`job_terceros`.`segundo_apellido` <> _latin1'')),`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1'')),`jobdaily`.`job_terceros`.`razon_social`) AS `EMPLEADO`,`jobdaily`.`job_planillas`.`descripcion` AS `PLANILLA`,`jobdaily`.`job_movimiento_novedades_manuales`.`fecha_pago_planilla` AS `FECHA_PAGO` from (((`jobdaily`.`job_terceros` join `jobdaily`.`job_movimiento_novedades_manuales`) join `jobdaily`.`job_sucursales`) join `jobdaily`.`job_planillas`) where ((`jobdaily`.`job_movimiento_novedades_manuales`.`documento_identidad_empleado` = `jobdaily`.`job_terceros`.`documento_identidad`) and (`jobdaily`.`job_movimiento_novedades_manuales`.`codigo_sucursal` = `jobdaily`.`job_sucursales`.`codigo`) and (`jobdaily`.`job_movimiento_novedades_manuales`.`codigo_planilla` = `jobdaily`.`job_planillas`.`codigo`)) group by `jobdaily`.`job_movimiento_novedades_manuales`.`documento_identidad_empleado`,`jobdaily`.`job_movimiento_novedades_manuales`.`ano_generacion`,`jobdaily`.`job_movimiento_novedades_manuales`.`mes_generacion`,`jobdaily`.`job_movimiento_novedades_manuales`.`codigo_planilla`,`jobdaily`.`job_movimiento_novedades_manuales`.`periodo_pago`,`jobdaily`.`job_movimiento_novedades_manuales`.`codigo_sucursal`;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_movimiento_tiempos_laborados`
-- 

DROP VIEW IF EXISTS `job_menu_movimiento_tiempos_laborados`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_movimiento_tiempos_laborados` AS select concat(`jobdaily`.`job_movimiento_tiempos_laborados`.`fecha_inicio`,_utf8'|',`jobdaily`.`job_movimiento_tiempos_laborados`.`documento_identidad_empleado`) AS `id`,`jobdaily`.`job_sucursales`.`codigo` AS `id_sucursal`,`jobdaily`.`job_sucursales`.`nombre` AS `SUCURSAL`,if(((`jobdaily`.`job_terceros`.`tipo_persona` = _latin1'1') or (`jobdaily`.`job_terceros`.`tipo_persona` = _latin1'4')),concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' ',if(((`jobdaily`.`job_terceros`.`segundo_nombre` is not null) and (`jobdaily`.`job_terceros`.`segundo_nombre` <> _latin1'')),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1''),`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' ',if(((`jobdaily`.`job_terceros`.`segundo_apellido` is not null) and (`jobdaily`.`job_terceros`.`segundo_apellido` <> _latin1'')),`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1'')),`jobdaily`.`job_terceros`.`razon_social`) AS `EMPLEADO`,`jobdaily`.`job_movimiento_tiempos_laborados`.`fecha_inicio` AS `FECHA_INICIO`,sec_to_time(sum((`jobdaily`.`job_movimiento_tiempos_laborados`.`cantidad_minutos` * 60))) AS `CANTIDAD` from ((((`jobdaily`.`job_terceros` join `jobdaily`.`job_movimiento_tiempos_laborados`) join `jobdaily`.`job_sucursales`) join `jobdaily`.`job_transacciones_contables_empleado`) join `jobdaily`.`job_transacciones_tiempo`) where ((`jobdaily`.`job_movimiento_tiempos_laborados`.`documento_identidad_empleado` = `jobdaily`.`job_terceros`.`documento_identidad`) and (`jobdaily`.`job_movimiento_tiempos_laborados`.`codigo_sucursal` = `jobdaily`.`job_sucursales`.`codigo`) and (`jobdaily`.`job_movimiento_tiempos_laborados`.`codigo_transaccion_contable` = `jobdaily`.`job_transacciones_contables_empleado`.`codigo`) and (`jobdaily`.`job_movimiento_tiempos_laborados`.`codigo_transaccion_tiempo` = `jobdaily`.`job_transacciones_tiempo`.`codigo`)) group by `jobdaily`.`job_movimiento_tiempos_laborados`.`documento_identidad_empleado`,`jobdaily`.`job_movimiento_tiempos_laborados`.`fecha_inicio`;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_movimiento_tiempos_no_laborados_dias`
-- 

DROP VIEW IF EXISTS `job_menu_movimiento_tiempos_no_laborados_dias`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_movimiento_tiempos_no_laborados_dias` AS select concat(`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`documento_identidad_empleado`,_utf8'|',`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`fecha_inicio_tiempo`,_utf8'|',`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`codigo_sucursal`) AS `id`,`jobdaily`.`job_sucursales`.`codigo` AS `id_sucursal`,`jobdaily`.`job_sucursales`.`nombre` AS `ALMACEN`,if(((`jobdaily`.`job_terceros`.`tipo_persona` = _latin1'1') or (`jobdaily`.`job_terceros`.`tipo_persona` = _latin1'4')),concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' ',if(((`jobdaily`.`job_terceros`.`segundo_nombre` is not null) and (`jobdaily`.`job_terceros`.`segundo_nombre` <> _latin1'')),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1''),`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' ',if(((`jobdaily`.`job_terceros`.`segundo_apellido` is not null) and (`jobdaily`.`job_terceros`.`segundo_apellido` <> _latin1'')),`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1'')),`jobdaily`.`job_terceros`.`razon_social`) AS `EMPLEADO`,date_format(`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`fecha_inicio_tiempo`,_utf8'%Y-%m-%d') AS `FECHA_INCAPACIDAD_TIEMPO` from ((((`jobdaily`.`job_terceros` join `jobdaily`.`job_aspirantes`) join `jobdaily`.`job_sucursal_contrato_empleados`) join `jobdaily`.`job_movimiento_tiempos_no_laborados_dias`) join `jobdaily`.`job_sucursales`) where ((`jobdaily`.`job_sucursal_contrato_empleados`.`documento_identidad_empleado` = `jobdaily`.`job_aspirantes`.`documento_identidad`) and (`jobdaily`.`job_aspirantes`.`documento_identidad` = `jobdaily`.`job_terceros`.`documento_identidad`) and (`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`documento_identidad_empleado` = `jobdaily`.`job_sucursal_contrato_empleados`.`documento_identidad_empleado`) and (`jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`codigo_sucursal` = `jobdaily`.`job_sucursales`.`codigo`)) group by `jobdaily`.`job_movimiento_tiempos_no_laborados_dias`.`fecha_inicio_tiempo`;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_movimiento_tiempos_no_laborados_horas`
-- 

DROP VIEW IF EXISTS `job_menu_movimiento_tiempos_no_laborados_horas`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_movimiento_tiempos_no_laborados_horas` AS select concat(`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`codigo_sucursal`,_utf8'|',`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`hora_fin`,_utf8'|',`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`hora_inicio`,_utf8'|',`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`fecha_registro`,_utf8'|',`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`documento_identidad_empleado`) AS `id`,`jobdaily`.`job_sucursales`.`codigo` AS `id_sucursal`,`jobdaily`.`job_sucursales`.`nombre` AS `SUCURSAL`,if(((`jobdaily`.`job_terceros`.`tipo_persona` = _latin1'1') or (`jobdaily`.`job_terceros`.`tipo_persona` = _latin1'4')),concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' ',if(((`jobdaily`.`job_terceros`.`segundo_nombre` is not null) and (`jobdaily`.`job_terceros`.`segundo_nombre` <> _latin1'')),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1''),`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' ',if(((`jobdaily`.`job_terceros`.`segundo_apellido` is not null) and (`jobdaily`.`job_terceros`.`segundo_apellido` <> _latin1'')),`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1'')),`jobdaily`.`job_terceros`.`razon_social`) AS `EMPLEADO`,`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`fecha_registro` AS `FECHA_RFEPORTE`,sec_to_time(sum((`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`cantidad_minutos` * 60))) AS `CANTIDAD` from ((`jobdaily`.`job_terceros` join `jobdaily`.`job_movimiento_tiempos_no_laborados_horas`) join `jobdaily`.`job_sucursales`) where ((`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`documento_identidad_empleado` = `jobdaily`.`job_terceros`.`documento_identidad`) and (`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`codigo_sucursal` = `jobdaily`.`job_sucursales`.`codigo`)) group by `jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`fecha_registro`,`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`hora_inicio`,`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`hora_fin` order by `jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`documento_identidad_empleado`,`jobdaily`.`job_movimiento_tiempos_no_laborados_horas`.`fecha_registro`;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_municipios`
-- 

DROP VIEW IF EXISTS `job_menu_municipios`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_municipios` AS select concat(`jobdaily`.`job_departamentos`.`codigo_iso`,_latin1'|',`jobdaily`.`job_municipios`.`codigo_dane_departamento`,_latin1'|',`jobdaily`.`job_municipios`.`codigo_dane_municipio`) AS `id`,concat(`jobdaily`.`job_departamentos`.`codigo_dane_departamento`,`jobdaily`.`job_municipios`.`codigo_dane_municipio`) AS `CODIGO_DANE`,`jobdaily`.`job_municipios`.`nombre` AS `NOMBRE`,`jobdaily`.`job_departamentos`.`nombre` AS `DEPARTAMENTO`,`jobdaily`.`job_paises`.`nombre` AS `PAIS` from ((`jobdaily`.`job_municipios` join `jobdaily`.`job_departamentos`) join `jobdaily`.`job_paises`) where ((`jobdaily`.`job_municipios`.`codigo_dane_departamento` = `jobdaily`.`job_departamentos`.`codigo_dane_departamento`) and (`jobdaily`.`job_departamentos`.`codigo_iso` = `jobdaily`.`job_paises`.`codigo_iso`) and (`jobdaily`.`job_municipios`.`codigo_dane_municipio` <> _latin1'')) order by `jobdaily`.`job_municipios`.`nombre`;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_nomina_migracion`
-- 

DROP VIEW IF EXISTS `job_menu_nomina_migracion`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_nomina_migracion` AS select concat(`jobdaily`.`job_movimientos_nomina_migracion`.`ano_generacion`,_utf8'|',`jobdaily`.`job_movimientos_nomina_migracion`.`mes_generacion`,_utf8'|',`jobdaily`.`job_movimientos_nomina_migracion`.`codigo_planilla`,_utf8'|',`jobdaily`.`job_movimientos_nomina_migracion`.`periodo_pago`,_utf8'|',`jobdaily`.`job_movimientos_nomina_migracion`.`codigo_transaccion_contable`,_utf8'|',`jobdaily`.`job_movimientos_nomina_migracion`.`consecutivo`) AS `id`,`jobdaily`.`job_movimientos_nomina_migracion`.`documento_identidad_empleado` AS `DOCUMENTO`,`job_menu_terceros`.`NOMBRE_COMPLETO` AS `EMPLEADO`,`jobdaily`.`job_transacciones_contables_empleado`.`descripcion` AS `TRANSACCION_CONTABLE`,`jobdaily`.`job_movimientos_nomina_migracion`.`fecha_pago_planilla` AS `FECHA_PAGO_PLANILLA`,format(`jobdaily`.`job_movimientos_nomina_migracion`.`valor_movimiento`,0) AS `VALOR_MOVIMIENTO` from ((`jobdaily`.`job_movimientos_nomina_migracion` join `jobdaily`.`job_transacciones_contables_empleado`) join `jobdaily`.`job_menu_terceros`) where ((`jobdaily`.`job_movimientos_nomina_migracion`.`codigo_transaccion_contable` = `jobdaily`.`job_transacciones_contables_empleado`.`codigo`) and (`jobdaily`.`job_movimientos_nomina_migracion`.`documento_identidad_empleado` = `job_menu_terceros`.`id`));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_paises`
-- 

DROP VIEW IF EXISTS `job_menu_paises`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_paises` AS select `jobdaily`.`job_paises`.`codigo_iso` AS `id`,`jobdaily`.`job_paises`.`codigo_iso` AS `CODIGO_ISO`,`jobdaily`.`job_paises`.`nombre` AS `NOMBRE` from `jobdaily`.`job_paises` where (`jobdaily`.`job_paises`.`codigo_iso` <> _latin1'');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_perfiles`
-- 

DROP VIEW IF EXISTS `job_menu_perfiles`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_perfiles` AS select `jobdaily`.`job_perfiles`.`id` AS `id`,`jobdaily`.`job_perfiles`.`codigo` AS `CODIGO`,`jobdaily`.`job_perfiles`.`nombre` AS `NOMBRE` from `jobdaily`.`job_perfiles`;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_periodos_contables`
-- 

DROP VIEW IF EXISTS `job_menu_periodos_contables`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_periodos_contables` AS select concat(`jobdaily`.`job_periodos_contables`.`codigo_sucursal`,_utf8'|',`jobdaily`.`job_periodos_contables`.`id_modulo`,_utf8'|',`jobdaily`.`job_periodos_contables`.`fecha_inicio`,_utf8'|',`jobdaily`.`job_periodos_contables`.`fecha_fin`) AS `id`,`jobdaily`.`job_sucursales`.`codigo` AS `id_sucursal`,`jobdaily`.`job_modulos`.`id` AS `id_modulo`,`jobdaily`.`job_sucursales`.`nombre` AS `SUCURSAL`,`jobdaily`.`job_modulos`.`id` AS `MODULO`,`jobdaily`.`job_periodos_contables`.`fecha_inicio` AS `FECHA_INICIO`,`jobdaily`.`job_periodos_contables`.`fecha_fin` AS `FECHA_FIN`,concat(_latin1'ESTADO_',`jobdaily`.`job_periodos_contables`.`estado`) AS `ESTADO` from ((`jobdaily`.`job_periodos_contables` join `jobdaily`.`job_sucursales`) join `jobdaily`.`job_modulos`) where ((`jobdaily`.`job_periodos_contables`.`codigo_sucursal` = `jobdaily`.`job_sucursales`.`codigo`) and (`jobdaily`.`job_periodos_contables`.`id_modulo` = `jobdaily`.`job_modulos`.`id`));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_plan_contable`
-- 

DROP VIEW IF EXISTS `job_menu_plan_contable`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_plan_contable` AS select `b`.`codigo_contable` AS `id`,`b`.`codigo_contable` AS `CODIGO_CONTABLE`,`b`.`descripcion` AS `DESCRIPCION`,(select `a`.`codigo_contable` AS `codigo_contable` from `jobdaily`.`job_plan_contable` `a` where (`a`.`codigo_contable` = `b`.`codigo_contable_padre`)) AS `CUENTA_PADRE`,if((`b`.`naturaleza_cuenta` = _latin1'D'),_utf8'Debito',_utf8'Credito') AS `NATURALEZA_CUENTA`,`c`.`codigo` AS `CODIGO_ANEXO` from (`jobdaily`.`job_plan_contable` `b` join `jobdaily`.`job_anexos_contables` `c`) where ((`b`.`codigo_anexo_contable` = `c`.`codigo`) and (`b`.`codigo_contable` <> _latin1''));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_planillas`
-- 

DROP VIEW IF EXISTS `job_menu_planillas`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_planillas` AS select `jobdaily`.`job_planillas`.`codigo` AS `id`,`jobdaily`.`job_planillas`.`codigo` AS `CODIGO`,`jobdaily`.`job_planillas`.`descripcion` AS `DESCRIPCION` from `jobdaily`.`job_planillas` where (`jobdaily`.`job_planillas`.`codigo` > 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_plazos_pago_proveedores`
-- 

DROP VIEW IF EXISTS `job_menu_plazos_pago_proveedores`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_plazos_pago_proveedores` AS select `jobdaily`.`job_plazos_pago_proveedores`.`codigo` AS `id`,`jobdaily`.`job_plazos_pago_proveedores`.`nombre` AS `NOMBRE`,if((`jobdaily`.`job_plazos_pago_proveedores`.`inicial` = _latin1'0'),1,`jobdaily`.`job_plazos_pago_proveedores`.`inicial`) AS `INICIAL`,if((`jobdaily`.`job_plazos_pago_proveedores`.`final` = _latin1'0'),1,`jobdaily`.`job_plazos_pago_proveedores`.`final`) AS `FINAL`,`jobdaily`.`job_plazos_pago_proveedores`.`periodo` AS `PERIODO`,`jobdaily`.`job_plazos_pago_proveedores`.`numero_cuotas` AS `CUOTAS` from `jobdaily`.`job_plazos_pago_proveedores` where (`jobdaily`.`job_plazos_pago_proveedores`.`codigo` <> _utf8'0');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_preferencias_empresas`
-- 

DROP VIEW IF EXISTS `job_menu_preferencias_empresas`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_preferencias_empresas` AS select `job_menu_empresas`.`CODIGO` AS `id`,`job_menu_empresas`.`RAZON_SOCIAL` AS `RAZON_SOCIAL` from `jobdaily`.`job_menu_empresas`;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_preferencias_sucursales`
-- 

DROP VIEW IF EXISTS `job_menu_preferencias_sucursales`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_preferencias_sucursales` AS select `jobdaily`.`job_sucursales`.`codigo` AS `id`,`jobdaily`.`job_sucursales`.`nombre` AS `SUCURSAL`,`job_menu_empresas`.`RAZON_SOCIAL` AS `EMPRESA` from (`jobdaily`.`job_sucursales` join `jobdaily`.`job_menu_empresas`) where (`jobdaily`.`job_sucursales`.`codigo_empresa` = `job_menu_empresas`.`id`);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_preferencias_usuario`
-- 

DROP VIEW IF EXISTS `job_menu_preferencias_usuario`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_preferencias_usuario` AS select `jobdaily`.`job_usuarios`.`codigo` AS `id`,`jobdaily`.`job_usuarios`.`nombre` AS `NOMBRE`,`jobdaily`.`job_usuarios`.`usuario` AS `USUARIO` from `jobdaily`.`job_usuarios` where (`jobdaily`.`job_usuarios`.`codigo` > 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_privilegios`
-- 

DROP VIEW IF EXISTS `job_menu_privilegios`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_privilegios` AS select `jobdaily`.`job_perfiles_usuario`.`id` AS `id`,`jobdaily`.`job_usuarios`.`nombre` AS `USUARIO`,`jobdaily`.`job_sucursales`.`nombre` AS `SUCURSAL`,(select `jobdaily`.`job_perfiles`.`nombre` AS `nombre` from `jobdaily`.`job_perfiles` where (`jobdaily`.`job_perfiles`.`id` = `jobdaily`.`job_perfiles_usuario`.`id_perfil`)) AS `PERFIL` from ((`jobdaily`.`job_perfiles_usuario` join `jobdaily`.`job_usuarios`) join `jobdaily`.`job_sucursales`) where ((`jobdaily`.`job_perfiles_usuario`.`codigo_usuario` = `jobdaily`.`job_usuarios`.`codigo`) and (`jobdaily`.`job_perfiles_usuario`.`codigo_sucursal` = `jobdaily`.`job_sucursales`.`codigo`));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_profesiones_oficios`
-- 

DROP VIEW IF EXISTS `job_menu_profesiones_oficios`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_profesiones_oficios` AS select `jobdaily`.`job_profesiones_oficios`.`codigo_dane` AS `id`,`jobdaily`.`job_profesiones_oficios`.`codigo_dane` AS `CODIGO_DANE`,`jobdaily`.`job_profesiones_oficios`.`descripcion` AS `DESCRIPCION` from `jobdaily`.`job_profesiones_oficios` where (`jobdaily`.`job_profesiones_oficios`.`codigo_dane` <> 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_proveedores`
-- 

DROP VIEW IF EXISTS `job_menu_proveedores`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_proveedores` AS select `jobdaily`.`job_proveedores`.`documento_identidad` AS `id`,`jobdaily`.`job_terceros`.`documento_identidad` AS `DOCUMENTO_PROVEEDOR`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),concat(concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`primer_apellido` is not null),concat(`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),concat(`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1''),_latin1'')),`jobdaily`.`job_terceros`.`razon_social`)) AS `PROVEEDOR`,concat(_latin1'<a href="mailto:',`jobdaily`.`job_terceros`.`correo`,_latin1'">',`jobdaily`.`job_terceros`.`correo`,_latin1'</a>') AS `CORREO` from (`jobdaily`.`job_proveedores` join `jobdaily`.`job_terceros`) where (`jobdaily`.`job_proveedores`.`documento_identidad` = `jobdaily`.`job_terceros`.`documento_identidad`);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_proveedores_marcas`
-- 

DROP VIEW IF EXISTS `job_menu_proveedores_marcas`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_proveedores_marcas` AS select concat(`jobdaily`.`job_proveedores_marcas`.`documento_identidad_proveedor`,_utf8'|',`jobdaily`.`job_proveedores_marcas`.`codigo_marca`) AS `id`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),`jobdaily`.`job_terceros`.`primer_nombre`,_latin1''),_latin1' ',if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1''),_latin1' ',if((`jobdaily`.`job_terceros`.`primer_apellido` is not null),`jobdaily`.`job_terceros`.`primer_apellido`,_latin1''),_latin1' ',if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1''),_latin1' ',if((`jobdaily`.`job_terceros`.`razon_social` is not null),`jobdaily`.`job_terceros`.`razon_social`,_latin1'')) AS `PROVEEDOR`,`jobdaily`.`job_marcas`.`descripcion` AS `MARCA` from (((`jobdaily`.`job_proveedores_marcas` join `jobdaily`.`job_terceros`) join `jobdaily`.`job_proveedores`) join `jobdaily`.`job_marcas`) where ((`jobdaily`.`job_proveedores_marcas`.`documento_identidad_proveedor` = `jobdaily`.`job_proveedores`.`documento_identidad`) and (`jobdaily`.`job_proveedores`.`documento_identidad` = `jobdaily`.`job_terceros`.`documento_identidad`) and (`jobdaily`.`job_proveedores_marcas`.`codigo_marca` = `jobdaily`.`job_marcas`.`codigo`));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_reporte_incapacidades`
-- 

DROP VIEW IF EXISTS `job_menu_reporte_incapacidades`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_reporte_incapacidades` AS select concat(`jobdaily`.`job_reporte_incapacidades`.`documento_identidad_empleado`,_utf8'|',`jobdaily`.`job_reporte_incapacidades`.`fecha_inicial_incapacidad`) AS `id`,`jobdaily`.`job_sucursales`.`codigo` AS `id_sucursal`,`jobdaily`.`job_sucursales`.`nombre` AS `ALMACEN`,if(((`jobdaily`.`job_terceros`.`tipo_persona` = _latin1'1') or (`jobdaily`.`job_terceros`.`tipo_persona` = _latin1'4')),concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' ',if(((`jobdaily`.`job_terceros`.`segundo_nombre` is not null) and (`jobdaily`.`job_terceros`.`segundo_nombre` <> _latin1'')),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1''),`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' ',if(((`jobdaily`.`job_terceros`.`segundo_apellido` is not null) and (`jobdaily`.`job_terceros`.`segundo_apellido` <> _latin1'')),`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1'')),`jobdaily`.`job_terceros`.`razon_social`) AS `EMPLEADO`,`jobdaily`.`job_reporte_incapacidades`.`fecha_inicial_incapacidad` AS `FECHA_INCAPACIDAD_INICIO` from ((((`jobdaily`.`job_terceros` join `jobdaily`.`job_aspirantes`) join `jobdaily`.`job_sucursal_contrato_empleados`) join `jobdaily`.`job_reporte_incapacidades`) join `jobdaily`.`job_sucursales`) where ((`jobdaily`.`job_sucursal_contrato_empleados`.`documento_identidad_empleado` = `jobdaily`.`job_aspirantes`.`documento_identidad`) and (`jobdaily`.`job_aspirantes`.`documento_identidad` = `jobdaily`.`job_terceros`.`documento_identidad`) and (`jobdaily`.`job_reporte_incapacidades`.`documento_identidad_empleado` = `jobdaily`.`job_sucursal_contrato_empleados`.`documento_identidad_empleado`) and (`jobdaily`.`job_reporte_incapacidades`.`codigo_sucursal` = `jobdaily`.`job_sucursales`.`codigo`)) group by `jobdaily`.`job_reporte_incapacidades`.`documento_identidad_empleado`,`jobdaily`.`job_reporte_incapacidades`.`fecha_inicial_incapacidad`;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_resoluciones_dian`
-- 

DROP VIEW IF EXISTS `job_menu_resoluciones_dian`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_resoluciones_dian` AS select concat(`jobdaily`.`job_resoluciones_dian`.`codigo_sucursal`,_utf8'|',`jobdaily`.`job_resoluciones_dian`.`numero`) AS `id`,`jobdaily`.`job_sucursales`.`codigo` AS `id_sucursal`,`jobdaily`.`job_sucursales`.`nombre` AS `SUCURSAL`,`jobdaily`.`job_resoluciones_dian`.`numero` AS `NUMERO`,`jobdaily`.`job_tipos_documentos`.`descripcion` AS `TIPO_DOCUMENTO`,`jobdaily`.`job_resoluciones_dian`.`fecha_inicia` AS `FECHA_INICIA`,`jobdaily`.`job_resoluciones_dian`.`fecha_termina` AS `FECHA_TERMINA`,`jobdaily`.`job_resoluciones_dian`.`prefijo` AS `PREFIJO`,format(`jobdaily`.`job_resoluciones_dian`.`factura_inicial`,0) AS `FACTURA_INICIAL`,format(`jobdaily`.`job_resoluciones_dian`.`factura_final`,0) AS `FACTURA_FINAL` from ((`jobdaily`.`job_resoluciones_dian` join `jobdaily`.`job_sucursales`) join `jobdaily`.`job_tipos_documentos`) where ((`jobdaily`.`job_resoluciones_dian`.`codigo_sucursal` = `jobdaily`.`job_sucursales`.`codigo`) and (`jobdaily`.`job_resoluciones_dian`.`codigo_tipo_documento` = `jobdaily`.`job_tipos_documentos`.`codigo`));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_resoluciones_gran_contribuyente`
-- 

DROP VIEW IF EXISTS `job_menu_resoluciones_gran_contribuyente`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_resoluciones_gran_contribuyente` AS select `jobdaily`.`job_resoluciones_gran_contribuyente`.`numero_resolucion` AS `id`,`jobdaily`.`job_resoluciones_gran_contribuyente`.`numero_resolucion` AS `NUMERO`,`jobdaily`.`job_resoluciones_gran_contribuyente`.`descripcion` AS `DESCRIPCION`,`jobdaily`.`job_resoluciones_gran_contribuyente`.`fecha` AS `FECHA` from `jobdaily`.`job_resoluciones_gran_contribuyente` where (`jobdaily`.`job_resoluciones_gran_contribuyente`.`numero_resolucion` <> 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_resoluciones_ica`
-- 

DROP VIEW IF EXISTS `job_menu_resoluciones_ica`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_resoluciones_ica` AS select concat(`jobdaily`.`job_resoluciones_ica`.`numero_resolucion`,_utf8'|',`jobdaily`.`job_resoluciones_ica`.`codigo_sucursal`) AS `id`,`jobdaily`.`job_resoluciones_ica`.`numero_resolucion` AS `NUMERO`,`jobdaily`.`job_sucursales`.`nombre` AS `SUCURSAL`,`jobdaily`.`job_resoluciones_ica`.`fecha` AS `FECHA` from (`jobdaily`.`job_resoluciones_ica` join `jobdaily`.`job_sucursales`) where ((`jobdaily`.`job_resoluciones_ica`.`codigo_sucursal` = `jobdaily`.`job_sucursales`.`codigo`) and (`jobdaily`.`job_resoluciones_ica`.`numero_resolucion` <> 0));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_resoluciones_retefuente`
-- 

DROP VIEW IF EXISTS `job_menu_resoluciones_retefuente`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_resoluciones_retefuente` AS select `jobdaily`.`job_resoluciones_retefuente`.`numero_retefuente` AS `id`,`jobdaily`.`job_resoluciones_retefuente`.`numero_retefuente` AS `NUMERO`,`jobdaily`.`job_resoluciones_retefuente`.`descripcion` AS `DESCRIPCION`,`jobdaily`.`job_resoluciones_retefuente`.`fecha` AS `FECHA` from `jobdaily`.`job_resoluciones_retefuente` where (`jobdaily`.`job_resoluciones_retefuente`.`numero_retefuente` <> 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_retiro_cesantias`
-- 

DROP VIEW IF EXISTS `job_menu_retiro_cesantias`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_retiro_cesantias` AS select concat(`jobdaily`.`job_retiro_cesantias`.`documento_identidad_empleado`,_utf8'|',`jobdaily`.`job_retiro_cesantias`.`consecutivo`,_utf8'|',`jobdaily`.`job_retiro_cesantias`.`fecha_generacion`,_utf8'|',`jobdaily`.`job_retiro_cesantias`.`concepto_retiro`) AS `id`,`jobdaily`.`job_sucursales`.`codigo` AS `id_sucursal`,`jobdaily`.`job_sucursales`.`nombre` AS `SUCURSAL_LABORA`,if(((`jobdaily`.`job_terceros`.`tipo_persona` = _latin1'1') or (`jobdaily`.`job_terceros`.`tipo_persona` = _latin1'4')),concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' ',if(((`jobdaily`.`job_terceros`.`segundo_nombre` is not null) and (`jobdaily`.`job_terceros`.`segundo_nombre` <> _latin1'')),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1''),`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' ',if(((`jobdaily`.`job_terceros`.`segundo_apellido` is not null) and (`jobdaily`.`job_terceros`.`segundo_apellido` <> _latin1'')),`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1'')),`jobdaily`.`job_terceros`.`razon_social`) AS `NOMBRE_EMPLEADO`,date_format(`jobdaily`.`job_retiro_cesantias`.`fecha_generacion`,_utf8'%Y-%m-%d') AS `FECHA_GENERACION`,`jobdaily`.`job_retiro_cesantias`.`valor_retiro` AS `VALOR_RETIRO` from ((`jobdaily`.`job_retiro_cesantias` join `jobdaily`.`job_terceros`) join `jobdaily`.`job_sucursales`) where ((`jobdaily`.`job_retiro_cesantias`.`documento_identidad_empleado` = `jobdaily`.`job_terceros`.`documento_identidad`) and (`jobdaily`.`job_retiro_cesantias`.`codigo_sucursal` = `jobdaily`.`job_sucursales`.`codigo`));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_salario_minimo`
-- 

DROP VIEW IF EXISTS `job_menu_salario_minimo`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_salario_minimo` AS select `jobdaily`.`job_salario_minimo`.`codigo` AS `id`,`jobdaily`.`job_salario_minimo`.`codigo` AS `CODIGO`,`jobdaily`.`job_salario_minimo`.`fecha` AS `FECHA`,concat(_utf8'$ ',format(`jobdaily`.`job_salario_minimo`.`valor`,0)) AS `VALOR` from `jobdaily`.`job_salario_minimo`;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_secciones`
-- 

DROP VIEW IF EXISTS `job_menu_secciones`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_secciones` AS select concat(`jobdaily`.`job_secciones`.`codigo_sucursal`,_utf8'|',`jobdaily`.`job_secciones`.`codigo_bodega`,_utf8'|',`jobdaily`.`job_secciones`.`codigo`) AS `id`,`jobdaily`.`job_secciones`.`codigo` AS `CODIGO`,`jobdaily`.`job_secciones`.`nombre` AS `NOMBRE`,`jobdaily`.`job_secciones`.`descripcion` AS `DESCRIPCION`,`jobdaily`.`job_bodegas`.`nombre` AS `BODEGA` from ((`jobdaily`.`job_secciones` join `jobdaily`.`job_bodegas`) join `jobdaily`.`job_sucursales`) where ((`jobdaily`.`job_secciones`.`codigo_bodega` = `jobdaily`.`job_bodegas`.`codigo`) and (`jobdaily`.`job_secciones`.`codigo_sucursal` = `jobdaily`.`job_bodegas`.`codigo_sucursal`) and (`jobdaily`.`job_secciones`.`codigo_sucursal` = `jobdaily`.`job_sucursales`.`codigo`) and (`jobdaily`.`job_secciones`.`codigo` <> 0)) order by `jobdaily`.`job_secciones`.`nombre`;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_secciones_departamentos`
-- 

DROP VIEW IF EXISTS `job_menu_secciones_departamentos`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_secciones_departamentos` AS select concat(`jobdaily`.`job_secciones_departamentos`.`codigo`,_utf8'|',`jobdaily`.`job_secciones_departamentos`.`codigo_departamento_empresa`) AS `id`,`jobdaily`.`job_secciones_departamentos`.`codigo` AS `CODIGO`,`jobdaily`.`job_secciones_departamentos`.`nombre` AS `NOMBRE`,`jobdaily`.`job_departamentos_empresa`.`nombre` AS `DEPARTAMENTO_EMPRESA` from (`jobdaily`.`job_secciones_departamentos` join `jobdaily`.`job_departamentos_empresa`) where (`jobdaily`.`job_secciones_departamentos`.`codigo_departamento_empresa` = `jobdaily`.`job_departamentos_empresa`.`codigo`);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_servicios`
-- 

DROP VIEW IF EXISTS `job_menu_servicios`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_servicios` AS select `jobdaily`.`job_servicios`.`codigo` AS `id`,`jobdaily`.`job_servicios`.`codigo` AS `CODIGO`,`jobdaily`.`job_servicios`.`descripcion` AS `DESCRIPCION` from `jobdaily`.`job_servicios` where (`jobdaily`.`job_servicios`.`codigo` > 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_servidores`
-- 

DROP VIEW IF EXISTS `job_menu_servidores`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_servidores` AS select `jobdaily`.`job_servidores`.`id` AS `id`,`jobdaily`.`job_servidores`.`ip` AS `IP`,`jobdaily`.`job_sucursales`.`nombre` AS `SUCURSAL`,`jobdaily`.`job_servidores`.`nombre_netbios` AS `NOMBRE_NETBIOS`,`jobdaily`.`job_servidores`.`nombre_tcpip` AS `NOMBRE_TCPIP` from (`jobdaily`.`job_servidores` join `jobdaily`.`job_sucursales`) where (`jobdaily`.`job_servidores`.`codigo_sucursal` = `jobdaily`.`job_sucursales`.`codigo`);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_sucursales`
-- 

DROP VIEW IF EXISTS `job_menu_sucursales`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_sucursales` AS select `jobdaily`.`job_sucursales`.`codigo` AS `id`,`jobdaily`.`job_sucursales`.`orden` AS `ORDEN`,`jobdaily`.`job_sucursales`.`codigo` AS `CODIGO`,`jobdaily`.`job_sucursales`.`nombre` AS `NOMBRE`,`jobdaily`.`job_empresas`.`razon_social` AS `EMPRESA`,`jobdaily`.`job_terceros`.`documento_identidad` AS `TERCERO` from ((`jobdaily`.`job_sucursales` join `jobdaily`.`job_empresas`) join `jobdaily`.`job_terceros`) where ((`jobdaily`.`job_sucursales`.`codigo_empresa` = `jobdaily`.`job_empresas`.`codigo`) and (`jobdaily`.`job_empresas`.`documento_identidad_tercero` = `jobdaily`.`job_terceros`.`documento_identidad`) and (`jobdaily`.`job_sucursales`.`codigo` <> 0));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_tablas`
-- 

DROP VIEW IF EXISTS `job_menu_tablas`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_tablas` AS select `jobdaily`.`job_tablas`.`id` AS `id`,`jobdaily`.`job_tablas`.`nombre_tabla` AS `NOMBRE_TABLA` from `jobdaily`.`job_tablas` where (`jobdaily`.`job_tablas`.`id` <> 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_tasas`
-- 

DROP VIEW IF EXISTS `job_menu_tasas`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_tasas` AS select `jobdaily`.`job_tasas`.`codigo` AS `id`,`jobdaily`.`job_tasas`.`codigo` AS `CODIGO`,`jobdaily`.`job_tasas`.`descripcion` AS `DESCRIPCION` from `jobdaily`.`job_tasas` where (`jobdaily`.`job_tasas`.`codigo` <> 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_terceros`
-- 

DROP VIEW IF EXISTS `job_menu_terceros`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_terceros` AS select `jobdaily`.`job_terceros`.`documento_identidad` AS `id`,`jobdaily`.`job_terceros`.`documento_identidad` AS `DOCUMENTO_IDENTIDAD`,`jobdaily`.`job_terceros`.`primer_nombre` AS `PRIMER_NOMBRE`,`jobdaily`.`job_terceros`.`segundo_nombre` AS `SEGUNDO_NOMBRE`,`jobdaily`.`job_terceros`.`primer_apellido` AS `PRIMER_APELLIDO`,`jobdaily`.`job_terceros`.`segundo_apellido` AS `SEGUNDO_APELLIDO`,`jobdaily`.`job_terceros`.`razon_social` AS `RAZON_SOCIAL`,if(((`jobdaily`.`job_terceros`.`tipo_persona` = _latin1'1') or (`jobdaily`.`job_terceros`.`tipo_persona` = _latin1'4')),concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' ',if(((`jobdaily`.`job_terceros`.`segundo_nombre` is not null) and (`jobdaily`.`job_terceros`.`segundo_nombre` <> _latin1'')),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1''),`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' ',if(((`jobdaily`.`job_terceros`.`segundo_apellido` is not null) and (`jobdaily`.`job_terceros`.`segundo_apellido` <> _latin1'')),`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1'')),`jobdaily`.`job_terceros`.`razon_social`) AS `NOMBRE_COMPLETO` from `jobdaily`.`job_terceros` where (`jobdaily`.`job_terceros`.`documento_identidad` <> _latin1'0') order by `jobdaily`.`job_terceros`.`primer_nombre`,`jobdaily`.`job_terceros`.`razon_social`;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_terminales`
-- 

DROP VIEW IF EXISTS `job_menu_terminales`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_terminales` AS select `jobdaily`.`job_terminales`.`id` AS `id`,`jobdaily`.`job_terminales`.`ip` AS `IP`,`jobdaily`.`job_terminales`.`nombre_netbios` AS `NOMBRE_NETBIOS`,`jobdaily`.`job_terminales`.`nombre_tcpip` AS `NOMBRE_TCPIP` from `jobdaily`.`job_terminales`;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_tipo_contrato`
-- 

DROP VIEW IF EXISTS `job_menu_tipo_contrato`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_tipo_contrato` AS select `jobdaily`.`job_tipos_contrato`.`codigo` AS `id`,`jobdaily`.`job_tipos_contrato`.`codigo` AS `CODIGO`,`jobdaily`.`job_tipos_contrato`.`descripcion` AS `DESCRIPCION`,concat(if((`jobdaily`.`job_tipos_contrato`.`termino_contrato` = 1),_utf8'Termino fijo',_utf8'Termino indefinido')) AS `TERMINO_CONTRATO` from `jobdaily`.`job_tipos_contrato` where (`jobdaily`.`job_tipos_contrato`.`codigo` <> 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_tipos_bodegas`
-- 

DROP VIEW IF EXISTS `job_menu_tipos_bodegas`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_tipos_bodegas` AS select `jobdaily`.`job_tipos_bodegas`.`codigo` AS `id`,`jobdaily`.`job_tipos_bodegas`.`nombre` AS `NOMBRE`,`jobdaily`.`job_tipos_bodegas`.`descripcion` AS `DESCRIPCION` from `jobdaily`.`job_tipos_bodegas` where (`jobdaily`.`job_tipos_bodegas`.`codigo` <> 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_tipos_compras`
-- 

DROP VIEW IF EXISTS `job_menu_tipos_compras`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_tipos_compras` AS select `tc`.`codigo` AS `id`,`tc`.`codigo` AS `CODIGO`,`tc`.`descripcion` AS `DESCRIPCION`,concat(_latin1'CONCEPTO_',`tc`.`concepto_compra`) AS `CONCEPTO` from `jobdaily`.`job_tipos_compra` `TC` where (`tc`.`codigo` <> _utf8'0');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_tipos_comprobantes`
-- 

DROP VIEW IF EXISTS `job_menu_tipos_comprobantes`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_tipos_comprobantes` AS select `jobdaily`.`job_tipos_comprobantes`.`codigo` AS `id`,`jobdaily`.`job_tipos_comprobantes`.`codigo` AS `CODIGO_INTERNO`,`jobdaily`.`job_tipos_comprobantes`.`descripcion` AS `DESCRIPCION` from `jobdaily`.`job_tipos_comprobantes` where (`jobdaily`.`job_tipos_comprobantes`.`codigo` <> 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_tipos_devoluciones_compra`
-- 

DROP VIEW IF EXISTS `job_menu_tipos_devoluciones_compra`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_tipos_devoluciones_compra` AS select `tdc`.`codigo` AS `id`,`tdc`.`codigo` AS `CODIGO`,`tdc`.`descripcion` AS `NOMBRE`,if((`tdc`.`concepto_compra` = _latin1'1'),_utf8'Compras directas',if((`tdc`.`concepto_compra` = _latin1'2'),_utf8'Compras obsequio',if((`tdc`.`concepto_compra` = _latin1'3'),_utf8'Compras filiales',if((`tdc`.`concepto_compra` = _latin1'4'),_utf8'Compras canje',_utf8'Compras en consignacion')))) AS `CONCEPTO` from `jobdaily`.`job_tipos_devoluciones_compra` `TDC`;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_tipos_documento_identidad`
-- 

DROP VIEW IF EXISTS `job_menu_tipos_documento_identidad`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_tipos_documento_identidad` AS select `jobdaily`.`job_tipos_documento_identidad`.`codigo` AS `id`,`jobdaily`.`job_tipos_documento_identidad`.`codigo` AS `CODIGO_INTERNO`,`jobdaily`.`job_tipos_documento_identidad`.`codigo_dian` AS `CODIGO_DIAN`,`jobdaily`.`job_tipos_documento_identidad`.`descripcion` AS `DESCRIPCION`,concat(_latin1'TIPO_PERSONA_',`jobdaily`.`job_tipos_documento_identidad`.`tipo_persona`) AS `TIPO_PERSONA` from `jobdaily`.`job_tipos_documento_identidad` where (`jobdaily`.`job_tipos_documento_identidad`.`codigo` <> 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_tipos_documentos`
-- 

DROP VIEW IF EXISTS `job_menu_tipos_documentos`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_tipos_documentos` AS select `jobdaily`.`job_tipos_documentos`.`codigo` AS `id`,`jobdaily`.`job_tipos_comprobantes`.`descripcion` AS `COMPROBANTE`,`jobdaily`.`job_tipos_documentos`.`codigo` AS `CODIGO`,`jobdaily`.`job_tipos_documentos`.`descripcion` AS `DESCRIPCION`,`jobdaily`.`job_tipos_documentos`.`observaciones` AS `OBSERVACIONES`,`jobdaily`.`job_tipos_documentos`.`abreviaturas` AS `ABREVIATURAS` from (`jobdaily`.`job_tipos_documentos` join `jobdaily`.`job_tipos_comprobantes`) where ((`jobdaily`.`job_tipos_documentos`.`codigo` <> _utf8'0') and (`jobdaily`.`job_tipos_comprobantes`.`codigo` = `jobdaily`.`job_tipos_documentos`.`codigo_comprobante`));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_tipos_documentos_bancarios`
-- 

DROP VIEW IF EXISTS `job_menu_tipos_documentos_bancarios`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_tipos_documentos_bancarios` AS select `jobdaily`.`job_tipos_documentos_bancarios`.`codigo` AS `id`,`jobdaily`.`job_tipos_documentos_bancarios`.`codigo` AS `CODIGO`,`jobdaily`.`job_tipos_documentos_bancarios`.`descripcion` AS `DESCRIPCION` from `jobdaily`.`job_tipos_documentos_bancarios` where (`jobdaily`.`job_tipos_documentos_bancarios`.`codigo` <> _utf8'0');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_tipos_moneda`
-- 

DROP VIEW IF EXISTS `job_menu_tipos_moneda`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_tipos_moneda` AS select `jobdaily`.`job_tipos_moneda`.`codigo` AS `id`,`jobdaily`.`job_tipos_moneda`.`codigo` AS `CODIGO`,`jobdaily`.`job_tipos_moneda`.`codigo_dian` AS `CODIGO_DIAN`,`jobdaily`.`job_tipos_moneda`.`nombre` AS `NOMBRE` from `jobdaily`.`job_tipos_moneda` where (`jobdaily`.`job_tipos_moneda`.`codigo` > 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_transacciones_contables_empleado`
-- 

DROP VIEW IF EXISTS `job_menu_transacciones_contables_empleado`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_transacciones_contables_empleado` AS select `jobdaily`.`job_transacciones_contables_empleado`.`codigo` AS `id`,`jobdaily`.`job_transacciones_contables_empleado`.`nombre` AS `NOMBRE`,`jobdaily`.`job_conceptos_transacciones_contables_empleado`.`descripcion` AS `CONCEPTO_CONTABLE`,concat(`jobdaily`.`job_plan_contable`.`codigo_contable`,_latin1' ',`jobdaily`.`job_plan_contable`.`descripcion`) AS `CODIGO_CONTABLE` from ((`jobdaily`.`job_transacciones_contables_empleado` join `jobdaily`.`job_plan_contable`) join `jobdaily`.`job_conceptos_transacciones_contables_empleado`) where ((`jobdaily`.`job_transacciones_contables_empleado`.`codigo_concepto_transaccion_contable` = `jobdaily`.`job_conceptos_transacciones_contables_empleado`.`codigo`) and (`jobdaily`.`job_transacciones_contables_empleado`.`codigo_contable` = `jobdaily`.`job_plan_contable`.`codigo_contable`) and (`jobdaily`.`job_transacciones_contables_empleado`.`codigo` > 0));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_transacciones_tiempo`
-- 

DROP VIEW IF EXISTS `job_menu_transacciones_tiempo`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_transacciones_tiempo` AS select `jobdaily`.`job_transacciones_tiempo`.`codigo` AS `id`,`jobdaily`.`job_transacciones_tiempo`.`nombre` AS `NOMBRE`,`jobdaily`.`job_conceptos_transacciones_tiempo`.`descripcion` AS `CONCEPTO_TIEMPO`,`jobdaily`.`job_transacciones_contables_empleado`.`nombre` AS `TRANSACCION_CONTABLE` from ((`jobdaily`.`job_transacciones_contables_empleado` join `jobdaily`.`job_transacciones_tiempo`) join `jobdaily`.`job_conceptos_transacciones_tiempo`) where ((`jobdaily`.`job_transacciones_tiempo`.`codigo_transaccion_contable` = `jobdaily`.`job_transacciones_contables_empleado`.`codigo`) and (`jobdaily`.`job_transacciones_tiempo`.`codigo_concepto_transaccion_tiempo` = `jobdaily`.`job_conceptos_transacciones_tiempo`.`codigo`) and (`jobdaily`.`job_transacciones_tiempo`.`codigo` > 0));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_unidades`
-- 

DROP VIEW IF EXISTS `job_menu_unidades`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_unidades` AS (select `a`.`codigo` AS `id`,`a`.`nombre` AS `NOMBRE`,`b`.`nombre` AS `TIPO_UNIDAD`,_utf8'' AS `PRINCIPAL`,`a`.`factor_conversion` AS `FACTOR_CONVERSION` from (`jobdaily`.`job_unidades` `a` join `jobdaily`.`job_tipos_unidades` `b`) where ((`a`.`codigo_tipo_unidad` = `b`.`codigo`) and (`a`.`codigo_unidad_principal` = _utf8'0') and (`a`.`nombre` <> _latin1''))) union (select `a`.`codigo` AS `id`,`a`.`nombre` AS `NOMBRE`,`b`.`nombre` AS `TIPO_UNIDAD`,`c`.`nombre` AS `PRINCIPAL`,`a`.`factor_conversion` AS `FACTOR_CONVERSION` from ((`jobdaily`.`job_unidades` `a` join `jobdaily`.`job_unidades` `c`) join `jobdaily`.`job_tipos_unidades` `b`) where ((`a`.`codigo_unidad_principal` = `c`.`codigo`) and (`a`.`codigo_tipo_unidad` = `b`.`codigo`) and (`a`.`nombre` <> _latin1'')));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_usuarios`
-- 

DROP VIEW IF EXISTS `job_menu_usuarios`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_usuarios` AS select `jobdaily`.`job_usuarios`.`codigo` AS `id`,`jobdaily`.`job_usuarios`.`usuario` AS `USUARIO`,`jobdaily`.`job_usuarios`.`nombre` AS `NOMBRE`,`jobdaily`.`job_usuarios`.`activo` AS `ACTIVO` from `jobdaily`.`job_usuarios` where (`jobdaily`.`job_usuarios`.`activo` <> _latin1'0');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_menu_vigencia_tasas`
-- 

DROP VIEW IF EXISTS `job_menu_vigencia_tasas`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_menu_vigencia_tasas` AS select concat(`jobdaily`.`job_vigencia_tasas`.`codigo_tasa`,_utf8'|',`jobdaily`.`job_vigencia_tasas`.`fecha`) AS `id`,`jobdaily`.`job_vigencia_tasas`.`codigo_tasa` AS `CODIGO`,`jobdaily`.`job_tasas`.`descripcion` AS `TASA`,`jobdaily`.`job_vigencia_tasas`.`fecha` AS `FECHA`,`jobdaily`.`job_vigencia_tasas`.`porcentaje` AS `PORCENTAJE`,format(`jobdaily`.`job_vigencia_tasas`.`valor_base`,0) AS `VALOR_BASE` from (`jobdaily`.`job_tasas` join `jobdaily`.`job_vigencia_tasas`) where ((`jobdaily`.`job_vigencia_tasas`.`codigo_tasa` = `jobdaily`.`job_tasas`.`codigo`) and (`jobdaily`.`job_vigencia_tasas`.`codigo_tasa` <> 0));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_prestamos_empleados_pagados`
-- 

DROP VIEW IF EXISTS `job_prestamos_empleados_pagados`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_prestamos_empleados_pagados` AS select `jobdaily`.`job_control_prestamos_empleados`.`fecha_generacion` AS `fecha_prestamo`,`jobdaily`.`job_control_prestamos_empleados`.`codigo_empresa` AS `codigo_empresa_prestamo`,(`jobdaily`.`job_control_prestamos_empleados`.`documento_identidad_empleado` * 1) AS `documento_identidad_empleado`,`jobdaily`.`job_control_prestamos_empleados`.`fecha_ingreso` AS `fecha_ingreso_empresa_prestamo`,`jobdaily`.`job_control_prestamos_empleados`.`codigo_sucursal` AS `codigo_sucursal_ingreso_prestamo`,`jobdaily`.`job_control_prestamos_empleados`.`fecha_ingreso_sucursal` AS `fecha_ingreso_sucursal_prestamo`,`jobdaily`.`job_control_prestamos_empleados`.`consecutivo` AS `consecutivo_prestamo`,`jobdaily`.`job_control_prestamos_empleados`.`codigo_tipo_documento` AS `codigo_tipo_documento`,`jobdaily`.`job_control_prestamos_empleados`.`consecutivo_documento` AS `consecutivo_documento`,`jobdaily`.`job_control_prestamos_empleados`.`codigo_transaccion_contable_descontar` AS `codigo_transaccion_contable_descontar`,`jobdaily`.`job_control_prestamos_empleados`.`codigo_transaccion_contable_cobrar` AS `codigo_transaccion_contable_cobrar`,`jobdaily`.`job_control_prestamos_empleados`.`concepto_prestamo` AS `codigo_concepto_prestamo`,`jobdaily`.`job_conceptos_prestamos`.`descripcion` AS `descripcion_concepto_prestamo`,`jobdaily`.`job_control_prestamos_empleados`.`observaciones` AS `obervaciones_prestamo`,`jobdaily`.`job_control_prestamos_empleados`.`valor_total` AS `valor_prestamo`,`jobdaily`.`job_control_prestamos_empleados`.`valor_pago` AS `valor_cuota`,`jobdaily`.`job_control_prestamos_empleados`.`forma_pago` AS `forma_pago`,`jobdaily`.`job_control_prestamos_empleados`.`fecha_registro` AS `fecha_grabacion_prestamo`,`jobdaily`.`job_control_prestamos_empleados`.`codigo_usuario_registra` AS `codigo_usuario_genera_prestamo`,`jobdaily`.`job_control_prestamos_empleados`.`fecha_modificacion` AS `fecha_modificacion_prestamo`,`jobdaily`.`job_control_prestamos_empleados`.`codigo_usuario_modifica` AS `codigo_usuario_modifica_prestamo`,`jobdaily`.`job_control_prestamos_empleados`.`valor_saldo` AS `valor_saldo_prestamo`,`jobdaily`.`job_movimiento_control_prestamos_empleados`.`ano_generacion` AS `ano_generacion`,`jobdaily`.`job_movimiento_control_prestamos_empleados`.`mes_generacion` AS `mes_generacion`,`jobdaily`.`job_movimiento_control_prestamos_empleados`.`codigo_planilla` AS `codigo_planilla`,`jobdaily`.`job_movimiento_control_prestamos_empleados`.`periodo_pago` AS `periodo_pago`,`jobdaily`.`job_movimiento_control_prestamos_empleados`.`fecha_pago_planilla` AS `fecha_pago_planilla`,`jobdaily`.`job_movimiento_control_prestamos_empleados`.`codigo_sucursal` AS `codigo_sucursal_pago`,`jobdaily`.`job_movimiento_control_prestamos_empleados`.`codigo_transaccion_contable` AS `codigo_transaccion_contable_pago`,(select `jobdaily`.`job_transacciones_contables_empleado`.`descripcion` AS `descripcion` from `jobdaily`.`job_transacciones_contables_empleado` where (`jobdaily`.`job_transacciones_contables_empleado`.`codigo` = `jobdaily`.`job_movimiento_control_prestamos_empleados`.`codigo_transaccion_contable`)) AS `descripcion_transaccion_contable_pago`,`jobdaily`.`job_movimiento_control_prestamos_empleados`.`codigo_empresa_auxiliar` AS `codigo_empresa_auxiliar`,`jobdaily`.`job_movimiento_control_prestamos_empleados`.`codigo_anexo_contable` AS `codigo_anexo_contable`,`jobdaily`.`job_movimiento_control_prestamos_empleados`.`codigo_auxiliar_contable` AS `codigo_auxiliar_contable`,`jobdaily`.`job_movimiento_control_prestamos_empleados`.`fecha_generacion` AS `fecha_generacion_pago`,`jobdaily`.`job_movimiento_control_prestamos_empleados`.`codigo_empresa` AS `codigo_empresa_pago`,`jobdaily`.`job_movimiento_control_prestamos_empleados`.`fecha_ingreso_sucursal` AS `fecha_ingreso_sucursal_pago`,`jobdaily`.`job_movimiento_control_prestamos_empleados`.`consecutivo` AS `consecutivo_pago`,`jobdaily`.`job_movimiento_control_prestamos_empleados`.`codigo_contable` AS `codigo_contable`,`jobdaily`.`job_movimiento_control_prestamos_empleados`.`sentido` AS `sentido`,`jobdaily`.`job_movimiento_control_prestamos_empleados`.`fecha_generacion_control` AS `fecha_generacion_control`,`jobdaily`.`job_movimiento_control_prestamos_empleados`.`consecutivo_fecha_pago` AS `consecutivo_fecha_pago`,`jobdaily`.`job_movimiento_control_prestamos_empleados`.`fecha_pago` AS `fecha_pactada_pago`,`jobdaily`.`job_movimiento_control_prestamos_empleados`.`valor_descuento` AS `valor_descuento`,`jobdaily`.`job_movimiento_control_prestamos_empleados`.`contabilizado` AS `contabilizado`,`jobdaily`.`job_movimiento_control_prestamos_empleados`.`codigo_usuario_registra` AS `codigo_usuario_descuenta`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),concat(concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`primer_apellido` is not null),concat(`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),concat(`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1''),_latin1'')),`jobdaily`.`job_terceros`.`razon_social`)) AS `nombre_empleado`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),concat(concat(`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),concat(`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1' '),_latin1' '),concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1'')),`jobdaily`.`job_terceros`.`razon_social`)) AS `apellido_empleado` from ((((`jobdaily`.`job_terceros` join `jobdaily`.`job_movimiento_control_prestamos_empleados`) join `jobdaily`.`job_control_prestamos_empleados`) join `jobdaily`.`job_fechas_prestamos_empleados`) join `jobdaily`.`job_conceptos_prestamos`) where ((`jobdaily`.`job_movimiento_control_prestamos_empleados`.`documento_identidad_empleado` = `jobdaily`.`job_fechas_prestamos_empleados`.`documento_identidad_empleado`) and (`jobdaily`.`job_movimiento_control_prestamos_empleados`.`consecutivo_fecha_pago` = `jobdaily`.`job_fechas_prestamos_empleados`.`consecutivo`) and (`jobdaily`.`job_movimiento_control_prestamos_empleados`.`fecha_generacion_control` = `jobdaily`.`job_fechas_prestamos_empleados`.`fecha_generacion`) and (`jobdaily`.`job_movimiento_control_prestamos_empleados`.`concepto_prestamo` = `jobdaily`.`job_fechas_prestamos_empleados`.`concepto_prestamo`) and (`jobdaily`.`job_movimiento_control_prestamos_empleados`.`fecha_pago` = `jobdaily`.`job_fechas_prestamos_empleados`.`fecha_pago`) and (`jobdaily`.`job_movimiento_control_prestamos_empleados`.`concepto_prestamo` = `jobdaily`.`job_conceptos_prestamos`.`codigo`) and (`jobdaily`.`job_fechas_prestamos_empleados`.`documento_identidad_empleado` = `jobdaily`.`job_control_prestamos_empleados`.`documento_identidad_empleado`) and (`jobdaily`.`job_fechas_prestamos_empleados`.`consecutivo` = `jobdaily`.`job_control_prestamos_empleados`.`consecutivo`) and (`jobdaily`.`job_fechas_prestamos_empleados`.`fecha_generacion` = `jobdaily`.`job_control_prestamos_empleados`.`fecha_generacion`) and (`jobdaily`.`job_fechas_prestamos_empleados`.`concepto_prestamo` = `jobdaily`.`job_control_prestamos_empleados`.`concepto_prestamo`) and (`jobdaily`.`job_movimiento_control_prestamos_empleados`.`documento_identidad_empleado` = `jobdaily`.`job_terceros`.`documento_identidad`));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_prestamos_terceros_pagados`
-- 

DROP VIEW IF EXISTS `job_prestamos_terceros_pagados`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_prestamos_terceros_pagados` AS select `jobdaily`.`job_control_prestamos_terceros`.`limite_descuento` AS `limite_descuento`,`jobdaily`.`job_control_prestamos_terceros`.`fecha_generacion` AS `fecha_generacion`,`jobdaily`.`job_control_prestamos_terceros`.`fecha_inicio_descuento` AS `fecha_inicio_descuento`,`jobdaily`.`job_control_prestamos_terceros`.`codigo_sucursal` AS `codigo_sucursal`,`jobdaily`.`job_control_prestamos_terceros`.`codigo_empresa` AS `codigo_empresa`,(`jobdaily`.`job_control_prestamos_terceros`.`documento_identidad_tercero` * 1) AS `documento_identidad_tercero`,(`jobdaily`.`job_control_prestamos_terceros`.`documento_identidad_empleado` * 1) AS `documento_identidad_empleado`,`jobdaily`.`job_control_prestamos_terceros`.`autorizacion_descuento_nomina` AS `autorizacion_descuento_nomina`,`jobdaily`.`job_control_prestamos_terceros`.`obligacion` AS `obligacion`,`jobdaily`.`job_control_prestamos_terceros`.`valor_tope_descuento` AS `valor_tope_descuento`,`jobdaily`.`job_control_prestamos_terceros`.`periodo_pago` AS `periodo_pago_prestamo`,`jobdaily`.`job_control_prestamos_terceros`.`valor_descontar_mensual` AS `valor_descontar_mensual`,`jobdaily`.`job_control_prestamos_terceros`.`valor_descontar_primera_quincena` AS `valor_descontar_primera_quincena`,`jobdaily`.`job_control_prestamos_terceros`.`valor_descontar_segunda_quincena` AS `valor_descontar_segunda_quincena`,`jobdaily`.`job_control_prestamos_terceros`.`valor_descontar_primera_semana` AS `valor_descontar_primera_semana`,`jobdaily`.`job_control_prestamos_terceros`.`valor_descontar_segunda_semana` AS `valor_descontar_segunda_semana`,`jobdaily`.`job_control_prestamos_terceros`.`valor_descontar_tercera_semana` AS `valor_descontar_tercera_semana`,`jobdaily`.`job_control_prestamos_terceros`.`valor_descontar_cuarta_semana` AS `valor_descontar_cuarta_semana`,`jobdaily`.`job_control_prestamos_terceros`.`descuento_ilimitado` AS `descuento_ilimitado`,`jobdaily`.`job_control_prestamos_terceros`.`fecha_limite_descuento` AS `fecha_limite_descuento`,`jobdaily`.`job_control_prestamos_terceros`.`estado` AS `estado`,`jobdaily`.`job_control_prestamos_terceros`.`codigo_usuario_registra` AS `codigo_usuario_registra`,`jobdaily`.`job_control_prestamos_terceros`.`codigo_usuario_modifica` AS `codigo_usuario_modifica`,`jobdaily`.`job_control_prestamos_terceros`.`transaccion_contable_descuento` AS `transaccion_contable_descuento`,(select `jobdaily`.`job_transacciones_contables_empleado`.`descripcion` AS `descripcion` from `jobdaily`.`job_transacciones_contables_empleado` where (`jobdaily`.`job_transacciones_contables_empleado`.`codigo` = `jobdaily`.`job_control_prestamos_terceros`.`transaccion_contable_descuento`)) AS `descripcion_transaccion_contable_descuento`,`jobdaily`.`job_control_prestamos_terceros`.`transaccion_contable_empleado` AS `transaccion_contable_empleado`,(select `jobdaily`.`job_transacciones_contables_empleado`.`descripcion` AS `descripcion` from `jobdaily`.`job_transacciones_contables_empleado` where (`jobdaily`.`job_transacciones_contables_empleado`.`codigo` = `jobdaily`.`job_control_prestamos_terceros`.`transaccion_contable_empleado`)) AS `descripcion_transaccion_contable_empleado`,`jobdaily`.`job_control_prestamos_terceros`.`transaccion_contable_pagar_tercero` AS `transaccion_contable_pagar_tercero`,(select `jobdaily`.`job_transacciones_contables_empleado`.`descripcion` AS `descripcion` from `jobdaily`.`job_transacciones_contables_empleado` where (`jobdaily`.`job_transacciones_contables_empleado`.`codigo` = `jobdaily`.`job_control_prestamos_terceros`.`transaccion_contable_pagar_tercero`)) AS `descripcion_transaccion_contable_pagar_tercero`,`jobdaily`.`job_control_prestamos_terceros`.`transaccion_contable_pago_tercero` AS `transaccion_contable_pago_tercero`,(select `jobdaily`.`job_transacciones_contables_empleado`.`descripcion` AS `descripcion` from `jobdaily`.`job_transacciones_contables_empleado` where (`jobdaily`.`job_transacciones_contables_empleado`.`codigo` = `jobdaily`.`job_control_prestamos_terceros`.`transaccion_contable_pago_tercero`)) AS `descripcion_transaccion_contable_pago_tercero`,`jobdaily`.`job_movimiento_cuenta_por_cobrar_descuento`.`ano_generacion` AS `ano_generacion`,`jobdaily`.`job_movimiento_cuenta_por_cobrar_descuento`.`mes_generacion` AS `mes_generacion`,`jobdaily`.`job_movimiento_cuenta_por_cobrar_descuento`.`codigo_planilla` AS `codigo_planilla`,`jobdaily`.`job_movimiento_cuenta_por_cobrar_descuento`.`periodo_pago` AS `periodo_pago`,`jobdaily`.`job_movimiento_cuenta_por_cobrar_descuento`.`fecha_pago_planilla` AS `fecha_pago_planilla`,`jobdaily`.`job_movimiento_cuenta_por_cobrar_descuento`.`codigo_sucursal` AS `codigo_sucursal_pago`,`jobdaily`.`job_movimiento_cuenta_por_cobrar_descuento`.`codigo_empresa_auxiliar` AS `codigo_empresa_auxiliar`,`jobdaily`.`job_movimiento_cuenta_por_cobrar_descuento`.`codigo_anexo_contable` AS `codigo_anexo_contable`,`jobdaily`.`job_movimiento_cuenta_por_cobrar_descuento`.`codigo_auxiliar_contable` AS `codigo_auxiliar_contable`,`jobdaily`.`job_movimiento_cuenta_por_cobrar_descuento`.`codigo_contable` AS `codigo_contable_cuenta_cobrar_descuento`,(select `jobdaily`.`job_plan_contable`.`descripcion` AS `descripcion` from `jobdaily`.`job_plan_contable` where (`jobdaily`.`job_plan_contable`.`codigo_contable` = `jobdaily`.`job_movimiento_cuenta_por_cobrar_descuento`.`codigo_contable`)) AS `descripcion_codigo_contable_cuenta_cobrar_descuento`,`jobdaily`.`job_movimiento_cuenta_por_cobrar_descuento`.`sentido` AS `sentido_cuenta_cobrar_descuento`,`jobdaily`.`job_movimiento_cuenta_por_cobrar_descuento`.`valor_movimiento` AS `valor_descuento`,`jobdaily`.`job_movimiento_cuenta_por_cobrar_descuento`.`contabilizado` AS `contabilizado_cuenta_cobrar_descuento`,`jobdaily`.`job_movimiento_cuenta_por_cobrar_descuento`.`codigo_usuario_registra` AS `usuario_cuenta_cobrar_descuento`,`jobdaily`.`job_movimiento_cuenta_por_cobrar_empleado`.`codigo_contable` AS `codigo_contable_descuento_empleado`,(select `jobdaily`.`job_plan_contable`.`descripcion` AS `descripcion` from `jobdaily`.`job_plan_contable` where (`jobdaily`.`job_plan_contable`.`codigo_contable` = `jobdaily`.`job_movimiento_cuenta_por_cobrar_empleado`.`codigo_contable`)) AS `descripcion_codigo_contable_cuenta_cobrar_empleado`,`jobdaily`.`job_movimiento_cuenta_por_cobrar_empleado`.`sentido` AS `sentido_descuento_empleado`,`jobdaily`.`job_movimiento_cuenta_por_cobrar_empleado`.`contabilizado` AS `contabilizado_descuento_empleado`,`jobdaily`.`job_movimiento_cuenta_por_pagar_tercero`.`codigo_contable` AS `codigo_contable_pagar_tercero`,(select `jobdaily`.`job_plan_contable`.`descripcion` AS `descripcion` from `jobdaily`.`job_plan_contable` where (`jobdaily`.`job_plan_contable`.`codigo_contable` = `jobdaily`.`job_movimiento_cuenta_por_pagar_tercero`.`codigo_contable`)) AS `descripcion_codigo_contable_cuenta_pagar_tercero`,`jobdaily`.`job_movimiento_cuenta_por_pagar_tercero`.`sentido` AS `sentido_cuenta_pagar_tercero`,`jobdaily`.`job_movimiento_cuenta_por_pagar_tercero`.`contabilizado` AS `contabilizado_cuenta_pagar_tecero`,(select concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),concat(concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`primer_apellido` is not null),concat(`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),concat(`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1''),_latin1'')),`jobdaily`.`job_terceros`.`razon_social`)) AS `CONCAT(
                        IF(job_terceros.primer_nombre IS NOT NULL,
                            CONCAT(
                                CONCAT(job_terceros.primer_nombre,' '),
                                IF(job_terceros.segundo_nombre IS NOT` from `jobdaily`.`job_terceros` where (`jobdaily`.`job_terceros`.`documento_identidad` = `jobdaily`.`job_control_prestamos_terceros`.`documento_identidad_empleado`)) AS `nombre_empleado`,(select concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),concat(concat(`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),concat(`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1''),_latin1'')),`jobdaily`.`job_terceros`.`razon_social`)) AS `CONCAT(
                        IF(job_terceros.primer_nombre IS NOT NULL,
                            CONCAT(
                                CONCAT(job_terceros.primer_apellido,' '),
                                IF(job_terceros.segundo_apellido IS` from `jobdaily`.`job_terceros` where (`jobdaily`.`job_terceros`.`documento_identidad` = `jobdaily`.`job_control_prestamos_terceros`.`documento_identidad_empleado`)) AS `apellido_empleado`,(select concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),concat(concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`primer_apellido` is not null),concat(`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),concat(`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1''),_latin1'')),`jobdaily`.`job_terceros`.`razon_social`)) AS `CONCAT(
                        IF(job_terceros.primer_nombre IS NOT NULL,
                            CONCAT(
                                CONCAT(job_terceros.primer_nombre,' '),
                                IF(job_terceros.segundo_nombre IS NOT` from `jobdaily`.`job_terceros` where (`jobdaily`.`job_terceros`.`documento_identidad` = `jobdaily`.`job_control_prestamos_terceros`.`documento_identidad_tercero`)) AS `tercero` from (((`jobdaily`.`job_control_prestamos_terceros` join `jobdaily`.`job_movimiento_cuenta_por_cobrar_descuento`) join `jobdaily`.`job_movimiento_cuenta_por_cobrar_empleado`) join `jobdaily`.`job_movimiento_cuenta_por_pagar_tercero`) where ((`jobdaily`.`job_movimiento_cuenta_por_cobrar_descuento`.`codigo_empresa` = `jobdaily`.`job_control_prestamos_terceros`.`codigo_empresa`) and (`jobdaily`.`job_movimiento_cuenta_por_cobrar_descuento`.`documento_identidad_empleado` = `jobdaily`.`job_control_prestamos_terceros`.`documento_identidad_empleado`) and (`jobdaily`.`job_movimiento_cuenta_por_cobrar_descuento`.`obligacion` = `jobdaily`.`job_control_prestamos_terceros`.`obligacion`) and (`jobdaily`.`job_movimiento_cuenta_por_cobrar_descuento`.`codigo_empresa` = `jobdaily`.`job_movimiento_cuenta_por_cobrar_empleado`.`codigo_empresa`) and (`jobdaily`.`job_movimiento_cuenta_por_cobrar_descuento`.`documento_identidad_empleado` = `jobdaily`.`job_movimiento_cuenta_por_cobrar_empleado`.`documento_identidad_empleado`) and (`jobdaily`.`job_movimiento_cuenta_por_cobrar_descuento`.`obligacion` = `jobdaily`.`job_movimiento_cuenta_por_cobrar_empleado`.`obligacion`) and (`jobdaily`.`job_movimiento_cuenta_por_cobrar_descuento`.`fecha_pago_planilla` = `jobdaily`.`job_movimiento_cuenta_por_cobrar_empleado`.`fecha_pago_planilla`) and (`jobdaily`.`job_movimiento_cuenta_por_cobrar_descuento`.`codigo_empresa` = `jobdaily`.`job_movimiento_cuenta_por_pagar_tercero`.`codigo_empresa`) and (`jobdaily`.`job_movimiento_cuenta_por_cobrar_descuento`.`documento_identidad_empleado` = `jobdaily`.`job_movimiento_cuenta_por_pagar_tercero`.`documento_identidad_empleado`) and (`jobdaily`.`job_movimiento_cuenta_por_cobrar_descuento`.`obligacion` = `jobdaily`.`job_movimiento_cuenta_por_pagar_tercero`.`obligacion`) and (`jobdaily`.`job_movimiento_cuenta_por_cobrar_descuento`.`fecha_pago_planilla` = `jobdaily`.`job_movimiento_cuenta_por_pagar_tercero`.`fecha_pago_planilla`));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_relacion_proveedores_marcas`
-- 

DROP VIEW IF EXISTS `job_relacion_proveedores_marcas`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_relacion_proveedores_marcas` AS select `jobdaily`.`job_articulos`.`codigo` AS `id`,concat(`jobdaily`.`job_articulos`.`codigo`,_latin1' ',`jobdaily`.`job_articulos`.`descripcion`,_latin1'|',`jobdaily`.`job_articulos`.`codigo`) AS `descripcion`,`jobdaily`.`job_proveedores_marcas`.`documento_identidad_proveedor` AS `id_proveedor` from ((((`jobdaily`.`job_proveedores_marcas` join `jobdaily`.`job_terceros`) join `jobdaily`.`job_proveedores`) join `jobdaily`.`job_marcas`) join `jobdaily`.`job_articulos`) where ((`jobdaily`.`job_articulos`.`codigo_marca` = `jobdaily`.`job_proveedores_marcas`.`codigo_marca`) and (`jobdaily`.`job_proveedores_marcas`.`documento_identidad_proveedor` = `jobdaily`.`job_proveedores`.`documento_identidad`) and (`jobdaily`.`job_proveedores`.`documento_identidad` = `jobdaily`.`job_terceros`.`documento_identidad`) and (`jobdaily`.`job_proveedores_marcas`.`codigo_marca` = `jobdaily`.`job_marcas`.`codigo`));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_seleccion_actividades_economicas`
-- 

DROP VIEW IF EXISTS `job_seleccion_actividades_economicas`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_seleccion_actividades_economicas` AS select concat(`jobdaily`.`job_actividades_economicas`.`codigo_iso`,_utf8'|',`jobdaily`.`job_actividades_economicas`.`codigo_dane_departamento`,_utf8'|',`jobdaily`.`job_actividades_economicas`.`codigo_dane_municipio`,_utf8'|',`jobdaily`.`job_actividades_economicas`.`codigo_dian`,_utf8'|',`jobdaily`.`job_actividades_economicas`.`codigo_actividad_municipio`) AS `id`,concat(`jobdaily`.`job_actividades_economicas`.`codigo_dian`,_utf8' - ',`jobdaily`.`job_actividades_economicas`.`descripcion`,_utf8'|',`jobdaily`.`job_actividades_economicas`.`codigo_iso`,_utf8',',`jobdaily`.`job_actividades_economicas`.`codigo_dane_departamento`,_utf8',',`jobdaily`.`job_actividades_economicas`.`codigo_dane_municipio`,_utf8',',`jobdaily`.`job_actividades_economicas`.`codigo_dian`,_utf8',',`jobdaily`.`job_actividades_economicas`.`codigo_actividad_municipio`) AS `descripcion` from ((`jobdaily`.`job_actividades_economicas` join `jobdaily`.`job_actividades_economicas_dian`) join `jobdaily`.`job_municipios`) where ((`jobdaily`.`job_actividades_economicas`.`codigo_iso` = `jobdaily`.`job_municipios`.`codigo_iso`) and (`jobdaily`.`job_actividades_economicas`.`codigo_dane_departamento` = `jobdaily`.`job_municipios`.`codigo_dane_departamento`) and (`jobdaily`.`job_actividades_economicas`.`codigo_dane_municipio` = `jobdaily`.`job_municipios`.`codigo_dane_municipio`) and (`jobdaily`.`job_actividades_economicas`.`codigo_dian` = `jobdaily`.`job_actividades_economicas_dian`.`codigo_dian`) and (`jobdaily`.`job_actividades_economicas`.`codigo_dian` <> 0));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_seleccion_actividades_economicas_dian`
-- 

DROP VIEW IF EXISTS `job_seleccion_actividades_economicas_dian`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_seleccion_actividades_economicas_dian` AS select `jobdaily`.`job_actividades_economicas_dian`.`codigo_dian` AS `id`,concat(`jobdaily`.`job_actividades_economicas_dian`.`codigo_dian`,_utf8' -',`jobdaily`.`job_actividades_economicas_dian`.`descripcion`,_utf8'|',`jobdaily`.`job_actividades_economicas_dian`.`codigo_dian`) AS `descripcion` from `jobdaily`.`job_actividades_economicas_dian` where (`jobdaily`.`job_actividades_economicas_dian`.`codigo_dian` <> 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_seleccion_aficiones`
-- 

DROP VIEW IF EXISTS `job_seleccion_aficiones`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_seleccion_aficiones` AS select `jobdaily`.`job_aficiones`.`codigo` AS `id`,concat(`jobdaily`.`job_aficiones`.`codigo`,_utf8' ',`jobdaily`.`job_aficiones`.`descripcion`,_utf8'|',`jobdaily`.`job_aficiones`.`codigo`) AS `descripcion` from `jobdaily`.`job_aficiones` where (`jobdaily`.`job_aficiones`.`codigo` > 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_seleccion_anexos_contables`
-- 

DROP VIEW IF EXISTS `job_seleccion_anexos_contables`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_seleccion_anexos_contables` AS select `jobdaily`.`job_anexos_contables`.`codigo` AS `id`,concat(`jobdaily`.`job_anexos_contables`.`descripcion`,_latin1'|',`jobdaily`.`job_anexos_contables`.`codigo`) AS `descripcion` from `jobdaily`.`job_anexos_contables` where (`jobdaily`.`job_anexos_contables`.`codigo` <> _latin1'');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_seleccion_articulos`
-- 

DROP VIEW IF EXISTS `job_seleccion_articulos`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_seleccion_articulos` AS select `jobdaily`.`job_articulos`.`codigo` AS `id`,concat(`jobdaily`.`job_articulos`.`codigo`,_latin1' ',`jobdaily`.`job_articulos`.`descripcion`,_latin1'|',`jobdaily`.`job_articulos`.`codigo`) AS `descripcion` from `jobdaily`.`job_articulos` where (`jobdaily`.`job_articulos`.`codigo` <> _latin1'');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_seleccion_aspirantes`
-- 

DROP VIEW IF EXISTS `job_seleccion_aspirantes`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_seleccion_aspirantes` AS select `jobdaily`.`job_aspirantes`.`documento_identidad` AS `id`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' '),_latin1' ',if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1''),_latin1' ',if((`jobdaily`.`job_terceros`.`primer_apellido` is not null),`jobdaily`.`job_terceros`.`primer_apellido`,_latin1''),_latin1' ',if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1''),_latin1'|',`jobdaily`.`job_aspirantes`.`documento_identidad`) AS `nombre_completo` from (`jobdaily`.`job_terceros` join `jobdaily`.`job_aspirantes`) where ((`jobdaily`.`job_aspirantes`.`documento_identidad` = `jobdaily`.`job_terceros`.`documento_identidad`) and (`jobdaily`.`job_aspirantes`.`documento_identidad` > 0));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_seleccion_auxiliares_contables`
-- 

DROP VIEW IF EXISTS `job_seleccion_auxiliares_contables`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_seleccion_auxiliares_contables` AS select concat(`jobdaily`.`job_auxiliares_contables`.`codigo_empresa`,_utf8'|',`jobdaily`.`job_auxiliares_contables`.`codigo_anexo_contable`,_utf8'|',`jobdaily`.`job_auxiliares_contables`.`codigo`) AS `id`,concat(`jobdaily`.`job_empresas`.`razon_social`,_latin1',',`jobdaily`.`job_anexos_contables`.`descripcion`,_latin1',',`jobdaily`.`job_auxiliares_contables`.`descripcion`) AS `descripcion`,`jobdaily`.`job_auxiliares_contables`.`codigo` AS `codigo`,`jobdaily`.`job_anexos_contables`.`codigo` AS `anexo_contable`,`jobdaily`.`job_empresas`.`codigo` AS `empresa` from ((`jobdaily`.`job_auxiliares_contables` join `jobdaily`.`job_anexos_contables`) join `jobdaily`.`job_empresas`) where ((`jobdaily`.`job_auxiliares_contables`.`codigo_anexo_contable` = `jobdaily`.`job_anexos_contables`.`codigo`) and (`jobdaily`.`job_auxiliares_contables`.`codigo_empresa` = `jobdaily`.`job_empresas`.`codigo`) and (`jobdaily`.`job_auxiliares_contables`.`codigo` > 0));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_seleccion_bancos`
-- 

DROP VIEW IF EXISTS `job_seleccion_bancos`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_seleccion_bancos` AS select `jobdaily`.`job_bancos`.`codigo` AS `id`,concat(`jobdaily`.`job_bancos`.`codigo`,_utf8' : ',`jobdaily`.`job_bancos`.`descripcion`,_utf8'|',`jobdaily`.`job_bancos`.`codigo`) AS `descripcion` from `jobdaily`.`job_bancos` where (`jobdaily`.`job_bancos`.`codigo` > 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_seleccion_barrios`
-- 

DROP VIEW IF EXISTS `job_seleccion_barrios`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_seleccion_barrios` AS select concat(`jobdaily`.`job_localidades`.`codigo_iso`,_latin1'|',`jobdaily`.`job_localidades`.`codigo_dane_departamento`,_latin1'|',`jobdaily`.`job_localidades`.`codigo_dane_municipio`,_latin1'|',`jobdaily`.`job_localidades`.`tipo`,_latin1'|',`jobdaily`.`job_localidades`.`codigo_dane_localidad`) AS `id`,concat(concat(`jobdaily`.`job_localidades`.`codigo_dane_departamento`,`jobdaily`.`job_localidades`.`codigo_dane_municipio`,`jobdaily`.`job_localidades`.`codigo_dane_localidad`),_latin1'-',`jobdaily`.`job_localidades`.`nombre`,_latin1'-',`jobdaily`.`job_municipios`.`nombre`,_latin1', ',`jobdaily`.`job_departamentos`.`nombre`,_latin1', ',`jobdaily`.`job_paises`.`nombre`,_latin1'|',concat(`jobdaily`.`job_departamentos`.`codigo_iso`,_latin1',',`jobdaily`.`job_municipios`.`codigo_dane_departamento`,_latin1',',`jobdaily`.`job_municipios`.`codigo_dane_municipio`,`jobdaily`.`job_localidades`.`codigo_dane_localidad`)) AS `nombre`,`jobdaily`.`job_localidades`.`codigo_dane_localidad` AS `codigo` from (((`jobdaily`.`job_localidades` join `jobdaily`.`job_municipios`) join `jobdaily`.`job_departamentos`) join `jobdaily`.`job_paises`) where ((`jobdaily`.`job_localidades`.`codigo_iso` = `jobdaily`.`job_municipios`.`codigo_iso`) and (`jobdaily`.`job_localidades`.`codigo_dane_departamento` = `jobdaily`.`job_municipios`.`codigo_dane_departamento`) and (`jobdaily`.`job_localidades`.`codigo_dane_municipio` = `jobdaily`.`job_municipios`.`codigo_dane_municipio`) and (`jobdaily`.`job_localidades`.`codigo_iso` = `jobdaily`.`job_departamentos`.`codigo_iso`) and (`jobdaily`.`job_localidades`.`codigo_dane_departamento` = `jobdaily`.`job_departamentos`.`codigo_dane_departamento`) and (`jobdaily`.`job_localidades`.`codigo_iso` = `jobdaily`.`job_paises`.`codigo_iso`) and (`jobdaily`.`job_localidades`.`tipo` = _latin1'B')) order by concat(concat(`jobdaily`.`job_localidades`.`codigo_dane_departamento`,`jobdaily`.`job_localidades`.`codigo_dane_municipio`,`jobdaily`.`job_localidades`.`codigo_dane_localidad`),_latin1'-',`jobdaily`.`job_localidades`.`nombre`,_latin1'-',`jobdaily`.`job_municipios`.`nombre`,_latin1', ',`jobdaily`.`job_departamentos`.`nombre`,_latin1', ',`jobdaily`.`job_paises`.`nombre`,_latin1'|',concat(`jobdaily`.`job_departamentos`.`codigo_iso`,_latin1',',`jobdaily`.`job_municipios`.`codigo_dane_departamento`,_latin1',',`jobdaily`.`job_municipios`.`codigo_dane_municipio`,`jobdaily`.`job_localidades`.`codigo_dane_localidad`));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_seleccion_cargos`
-- 

DROP VIEW IF EXISTS `job_seleccion_cargos`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_seleccion_cargos` AS select `jobdaily`.`job_cargos`.`codigo` AS `id`,concat(`jobdaily`.`job_cargos`.`codigo`,_utf8' - ',`jobdaily`.`job_cargos`.`nombre`,_utf8'|',`jobdaily`.`job_cargos`.`codigo`) AS `descripcion` from `jobdaily`.`job_cargos`;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_seleccion_contrato_empleados`
-- 

DROP VIEW IF EXISTS `job_seleccion_contrato_empleados`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_seleccion_contrato_empleados` AS select concat(`jobdaily`.`job_control_prestamos_empleados`.`documento_identidad_empleado`,_utf8'|',`jobdaily`.`job_control_prestamos_empleados`.`fecha_generacion`,_utf8'|',`jobdaily`.`job_control_prestamos_empleados`.`concepto_prestamo`) AS `id`,concat(if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`primer_apellido` is not null),concat(`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),concat(`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1' '),_latin1''),_latin1'|',`jobdaily`.`job_control_prestamos_empleados`.`documento_identidad_empleado`) AS `EMPLEADO` from ((((`jobdaily`.`job_terceros` join `jobdaily`.`job_aspirantes`) join `jobdaily`.`job_sucursal_contrato_empleados`) join `jobdaily`.`job_control_prestamos_empleados`) join `jobdaily`.`job_conceptos_prestamos`) where ((`jobdaily`.`job_sucursal_contrato_empleados`.`documento_identidad_empleado` = `jobdaily`.`job_aspirantes`.`documento_identidad`) and (`jobdaily`.`job_aspirantes`.`documento_identidad` = `jobdaily`.`job_terceros`.`documento_identidad`) and (`jobdaily`.`job_control_prestamos_empleados`.`documento_identidad_empleado` = `jobdaily`.`job_sucursal_contrato_empleados`.`documento_identidad_empleado`) and (`jobdaily`.`job_control_prestamos_empleados`.`concepto_prestamo` = `jobdaily`.`job_conceptos_prestamos`.`codigo`));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_seleccion_corregimientos`
-- 

DROP VIEW IF EXISTS `job_seleccion_corregimientos`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_seleccion_corregimientos` AS select concat(`jobdaily`.`job_localidades`.`codigo_iso`,_latin1'|',`jobdaily`.`job_localidades`.`codigo_dane_departamento`,_latin1'|',`jobdaily`.`job_localidades`.`codigo_dane_municipio`,_latin1'|',`jobdaily`.`job_localidades`.`tipo`,_latin1'|',`jobdaily`.`job_localidades`.`codigo_dane_localidad`) AS `id`,concat(concat(`jobdaily`.`job_localidades`.`codigo_dane_departamento`,`jobdaily`.`job_localidades`.`codigo_dane_municipio`,`jobdaily`.`job_localidades`.`codigo_dane_localidad`),_latin1'-',`jobdaily`.`job_localidades`.`nombre`,_latin1'-',`jobdaily`.`job_municipios`.`nombre`,_latin1', ',`jobdaily`.`job_departamentos`.`nombre`,_latin1', ',`jobdaily`.`job_paises`.`nombre`,_latin1'|',concat(`jobdaily`.`job_departamentos`.`codigo_iso`,_latin1',',`jobdaily`.`job_municipios`.`codigo_dane_departamento`,_latin1',',`jobdaily`.`job_municipios`.`codigo_dane_municipio`,`jobdaily`.`job_localidades`.`codigo_dane_localidad`)) AS `nombre`,`jobdaily`.`job_localidades`.`codigo_dane_localidad` AS `codigo` from (((`jobdaily`.`job_localidades` join `jobdaily`.`job_municipios`) join `jobdaily`.`job_departamentos`) join `jobdaily`.`job_paises`) where ((`jobdaily`.`job_localidades`.`codigo_iso` = `jobdaily`.`job_municipios`.`codigo_iso`) and (`jobdaily`.`job_localidades`.`codigo_dane_departamento` = `jobdaily`.`job_municipios`.`codigo_dane_departamento`) and (`jobdaily`.`job_localidades`.`codigo_dane_municipio` = `jobdaily`.`job_municipios`.`codigo_dane_municipio`) and (`jobdaily`.`job_localidades`.`codigo_iso` = `jobdaily`.`job_departamentos`.`codigo_iso`) and (`jobdaily`.`job_localidades`.`codigo_dane_departamento` = `jobdaily`.`job_departamentos`.`codigo_dane_departamento`) and (`jobdaily`.`job_localidades`.`codigo_iso` = `jobdaily`.`job_paises`.`codigo_iso`) and (`jobdaily`.`job_localidades`.`tipo` = _latin1'C')) order by concat(concat(`jobdaily`.`job_localidades`.`codigo_dane_departamento`,`jobdaily`.`job_localidades`.`codigo_dane_municipio`,`jobdaily`.`job_localidades`.`codigo_dane_localidad`),_latin1'-',`jobdaily`.`job_localidades`.`nombre`,_latin1'-',`jobdaily`.`job_municipios`.`nombre`,_latin1', ',`jobdaily`.`job_departamentos`.`nombre`,_latin1', ',`jobdaily`.`job_paises`.`nombre`,_latin1'|',concat(`jobdaily`.`job_departamentos`.`codigo_iso`,_latin1',',`jobdaily`.`job_municipios`.`codigo_dane_departamento`,_latin1',',`jobdaily`.`job_municipios`.`codigo_dane_municipio`,`jobdaily`.`job_localidades`.`codigo_dane_localidad`));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_seleccion_departamentos_empresa`
-- 

DROP VIEW IF EXISTS `job_seleccion_departamentos_empresa`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_seleccion_departamentos_empresa` AS select `jobdaily`.`job_departamentos_empresa`.`codigo` AS `id`,concat(`jobdaily`.`job_departamentos_empresa`.`codigo`,_utf8' - ',`jobdaily`.`job_departamentos_empresa`.`nombre`,_utf8'|',`jobdaily`.`job_departamentos_empresa`.`codigo`) AS `descripcion` from `jobdaily`.`job_departamentos_empresa` where (`jobdaily`.`job_departamentos_empresa`.`codigo` > 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_seleccion_deportes`
-- 

DROP VIEW IF EXISTS `job_seleccion_deportes`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_seleccion_deportes` AS select `jobdaily`.`job_deportes`.`codigo` AS `id`,concat(`jobdaily`.`job_deportes`.`codigo`,_utf8' ',`jobdaily`.`job_deportes`.`descripcion`,_utf8'|',`jobdaily`.`job_deportes`.`codigo`) AS `descripcion` from `jobdaily`.`job_deportes` where (`jobdaily`.`job_deportes`.`codigo` > 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_seleccion_empleados`
-- 

DROP VIEW IF EXISTS `job_seleccion_empleados`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_seleccion_empleados` AS select `jobdaily`.`job_ingreso_empleados`.`documento_identidad_empleado` AS `id`,concat(`jobdaily`.`job_terceros`.`documento_identidad`,_latin1' - ',if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),`jobdaily`.`job_terceros`.`primer_nombre`,_latin1''),_latin1' ',if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1''),_latin1' ',if((`jobdaily`.`job_terceros`.`primer_apellido` is not null),`jobdaily`.`job_terceros`.`primer_apellido`,_latin1''),_latin1' ',if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1''),_latin1' ',if((`jobdaily`.`job_terceros`.`razon_social` is not null),`jobdaily`.`job_terceros`.`razon_social`,_latin1''),_latin1'|',`jobdaily`.`job_terceros`.`documento_identidad`) AS `NOMBRE_COMPLETO`,`jobdaily`.`job_ingreso_empleados`.`codigo_sucursal_activo` AS `id_sucursal`,`jobdaily`.`job_ingreso_empleados`.`codigo_empresa` AS `id_empresa` from ((`jobdaily`.`job_terceros` join `jobdaily`.`job_ingreso_empleados`) join `jobdaily`.`job_aspirantes`) where ((`jobdaily`.`job_ingreso_empleados`.`documento_identidad_empleado` = `jobdaily`.`job_aspirantes`.`documento_identidad`) and (`jobdaily`.`job_aspirantes`.`documento_identidad` = `jobdaily`.`job_terceros`.`documento_identidad`) and (`jobdaily`.`job_ingreso_empleados`.`documento_identidad_empleado` > 0) and (`jobdaily`.`job_ingreso_empleados`.`estado` = _latin1'1'));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_seleccion_empresas`
-- 

DROP VIEW IF EXISTS `job_seleccion_empresas`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_seleccion_empresas` AS select `jobdaily`.`job_empresas`.`codigo` AS `id`,concat(`jobdaily`.`job_empresas`.`razon_social`,_utf8'|',`jobdaily`.`job_empresas`.`codigo`) AS `nombre` from `jobdaily`.`job_empresas` where (`jobdaily`.`job_empresas`.`codigo` <> 0) order by `jobdaily`.`job_empresas`.`razon_social`;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_seleccion_entidades_parafiscales_cesantias`
-- 

DROP VIEW IF EXISTS `job_seleccion_entidades_parafiscales_cesantias`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_seleccion_entidades_parafiscales_cesantias` AS select `jobdaily`.`job_entidades_parafiscales`.`codigo` AS `id`,concat(`jobdaily`.`job_entidades_parafiscales`.`codigo`,_utf8'-',`jobdaily`.`job_entidades_parafiscales`.`nombre`,_utf8'|',`jobdaily`.`job_entidades_parafiscales`.`codigo`) AS `descripcion` from `jobdaily`.`job_entidades_parafiscales` where ((`jobdaily`.`job_entidades_parafiscales`.`cesantias` = _latin1'1') and (`jobdaily`.`job_entidades_parafiscales`.`codigo` <> 0));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_seleccion_entidades_parafiscales_pension`
-- 

DROP VIEW IF EXISTS `job_seleccion_entidades_parafiscales_pension`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_seleccion_entidades_parafiscales_pension` AS select `jobdaily`.`job_entidades_parafiscales`.`codigo` AS `id`,concat(`jobdaily`.`job_entidades_parafiscales`.`codigo`,_utf8'-',`jobdaily`.`job_entidades_parafiscales`.`nombre`,_utf8'|',`jobdaily`.`job_entidades_parafiscales`.`codigo`) AS `descripcion` from `jobdaily`.`job_entidades_parafiscales` where ((`jobdaily`.`job_entidades_parafiscales`.`pension` = _latin1'1') and (`jobdaily`.`job_entidades_parafiscales`.`codigo` <> 0));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_seleccion_entidades_parafiscales_salud`
-- 

DROP VIEW IF EXISTS `job_seleccion_entidades_parafiscales_salud`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_seleccion_entidades_parafiscales_salud` AS select `jobdaily`.`job_entidades_parafiscales`.`codigo` AS `id`,concat(`jobdaily`.`job_entidades_parafiscales`.`codigo`,_utf8'-',`jobdaily`.`job_entidades_parafiscales`.`nombre`,_utf8'|',`jobdaily`.`job_entidades_parafiscales`.`codigo`) AS `descripcion` from `jobdaily`.`job_entidades_parafiscales` where ((`jobdaily`.`job_entidades_parafiscales`.`salud` = _latin1'1') and (`jobdaily`.`job_entidades_parafiscales`.`codigo` <> 0));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_seleccion_escolaridad`
-- 

DROP VIEW IF EXISTS `job_seleccion_escolaridad`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_seleccion_escolaridad` AS select `jobdaily`.`job_escolaridad`.`codigo` AS `id`,concat(`jobdaily`.`job_escolaridad`.`codigo`,_utf8' ',`jobdaily`.`job_escolaridad`.`descripcion`,_utf8'|',`jobdaily`.`job_escolaridad`.`codigo`) AS `descripcion` from `jobdaily`.`job_escolaridad` where (`jobdaily`.`job_escolaridad`.`codigo` > 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_seleccion_fechas_planillas`
-- 

DROP VIEW IF EXISTS `job_seleccion_fechas_planillas`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_seleccion_fechas_planillas` AS select `jobdaily`.`job_fechas_planillas`.`codigo_planilla` AS `codigo_planilla`,date_format(`jobdaily`.`job_fechas_planillas`.`fecha`,_utf8'%Y') AS `ano`,date_format(`jobdaily`.`job_fechas_planillas`.`fecha`,_utf8'%m') AS `mes`,date_format(`jobdaily`.`job_fechas_planillas`.`fecha`,_utf8'%d') AS `dia`,`jobdaily`.`job_fechas_planillas`.`fecha` AS `fecha` from `jobdaily`.`job_fechas_planillas`;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_seleccion_idiomas`
-- 

DROP VIEW IF EXISTS `job_seleccion_idiomas`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_seleccion_idiomas` AS select `jobdaily`.`job_idiomas`.`codigo` AS `id`,concat(`jobdaily`.`job_idiomas`.`codigo`,_utf8' ',`jobdaily`.`job_idiomas`.`descripcion`,_utf8'|',`jobdaily`.`job_idiomas`.`codigo`) AS `descripcion` from `jobdaily`.`job_idiomas` where (`jobdaily`.`job_idiomas`.`codigo` > 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_seleccion_localidades`
-- 

DROP VIEW IF EXISTS `job_seleccion_localidades`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_seleccion_localidades` AS select concat(`jobdaily`.`job_localidades`.`codigo_iso`,_latin1'|',`jobdaily`.`job_localidades`.`codigo_dane_departamento`,_latin1'|',`jobdaily`.`job_localidades`.`codigo_dane_municipio`,_latin1'|',`jobdaily`.`job_localidades`.`tipo`,_latin1'|',`jobdaily`.`job_localidades`.`codigo_dane_localidad`) AS `id`,concat(concat(`jobdaily`.`job_localidades`.`codigo_dane_departamento`,`jobdaily`.`job_localidades`.`codigo_dane_municipio`,`jobdaily`.`job_localidades`.`codigo_dane_localidad`),_latin1'-',`jobdaily`.`job_localidades`.`nombre`,_latin1'-',`jobdaily`.`job_municipios`.`nombre`,_latin1', ',`jobdaily`.`job_departamentos`.`nombre`,_latin1', ',`jobdaily`.`job_paises`.`nombre`,_latin1'|',concat(`jobdaily`.`job_departamentos`.`codigo_iso`,_latin1',',`jobdaily`.`job_municipios`.`codigo_dane_departamento`,_latin1',',`jobdaily`.`job_municipios`.`codigo_dane_municipio`,_latin1',',`jobdaily`.`job_localidades`.`tipo`,_latin1',',`jobdaily`.`job_localidades`.`codigo_dane_localidad`)) AS `nombre`,`jobdaily`.`job_localidades`.`codigo_dane_localidad` AS `codigo`,concat(`jobdaily`.`job_localidades`.`codigo_iso`,_latin1',',`jobdaily`.`job_localidades`.`codigo_dane_departamento`,_latin1',',`jobdaily`.`job_localidades`.`codigo_dane_municipio`,_latin1',',`jobdaily`.`job_localidades`.`tipo`,_latin1',',`jobdaily`.`job_localidades`.`codigo_dane_localidad`) AS `llave_primaria` from (((`jobdaily`.`job_localidades` join `jobdaily`.`job_municipios`) join `jobdaily`.`job_departamentos`) join `jobdaily`.`job_paises`) where ((`jobdaily`.`job_localidades`.`codigo_iso` = `jobdaily`.`job_municipios`.`codigo_iso`) and (`jobdaily`.`job_localidades`.`codigo_dane_departamento` = `jobdaily`.`job_municipios`.`codigo_dane_departamento`) and (`jobdaily`.`job_localidades`.`codigo_dane_municipio` = `jobdaily`.`job_municipios`.`codigo_dane_municipio`) and (`jobdaily`.`job_localidades`.`codigo_iso` = `jobdaily`.`job_departamentos`.`codigo_iso`) and (`jobdaily`.`job_localidades`.`codigo_dane_departamento` = `jobdaily`.`job_departamentos`.`codigo_dane_departamento`) and (`jobdaily`.`job_localidades`.`codigo_iso` = `jobdaily`.`job_paises`.`codigo_iso`)) order by concat(concat(`jobdaily`.`job_localidades`.`codigo_dane_departamento`,`jobdaily`.`job_localidades`.`codigo_dane_municipio`,`jobdaily`.`job_localidades`.`codigo_dane_localidad`),_latin1'-',`jobdaily`.`job_localidades`.`nombre`,_latin1'-',`jobdaily`.`job_municipios`.`nombre`,_latin1', ',`jobdaily`.`job_departamentos`.`nombre`,_latin1', ',`jobdaily`.`job_paises`.`nombre`,_latin1'|',concat(`jobdaily`.`job_departamentos`.`codigo_iso`,_latin1',',`jobdaily`.`job_municipios`.`codigo_dane_departamento`,_latin1',',`jobdaily`.`job_municipios`.`codigo_dane_municipio`,_latin1',',`jobdaily`.`job_localidades`.`tipo`,_latin1',',`jobdaily`.`job_localidades`.`codigo_dane_localidad`));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_seleccion_motivos_incapacidad`
-- 

DROP VIEW IF EXISTS `job_seleccion_motivos_incapacidad`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_seleccion_motivos_incapacidad` AS select `jobdaily`.`job_motivos_incapacidad`.`codigo` AS `id`,concat(`jobdaily`.`job_motivos_incapacidad`.`descripcion`,_utf8'|',`jobdaily`.`job_motivos_incapacidad`.`codigo`) AS `descripcion` from `jobdaily`.`job_motivos_incapacidad` where (`jobdaily`.`job_motivos_incapacidad`.`codigo` > 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_seleccion_municipios`
-- 

DROP VIEW IF EXISTS `job_seleccion_municipios`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_seleccion_municipios` AS select concat(`jobdaily`.`job_departamentos`.`codigo_iso`,_latin1'|',`jobdaily`.`job_municipios`.`codigo_dane_departamento`,_latin1'|',`jobdaily`.`job_municipios`.`codigo_dane_municipio`) AS `id`,concat(concat(`jobdaily`.`job_departamentos`.`codigo_dane_departamento`,`jobdaily`.`job_municipios`.`codigo_dane_municipio`),_latin1'-',`jobdaily`.`job_municipios`.`nombre`,_latin1', ',`jobdaily`.`job_departamentos`.`nombre`,_latin1', ',`jobdaily`.`job_paises`.`nombre`,_latin1'|',concat(`jobdaily`.`job_departamentos`.`codigo_iso`,_latin1',',`jobdaily`.`job_municipios`.`codigo_dane_departamento`,_latin1',',`jobdaily`.`job_municipios`.`codigo_dane_municipio`)) AS `nombre`,`jobdaily`.`job_municipios`.`codigo_iso` AS `pais`,`jobdaily`.`job_municipios`.`codigo_dane_departamento` AS `departamento`,`jobdaily`.`job_municipios`.`codigo_dane_municipio` AS `codigo`,concat(`jobdaily`.`job_departamentos`.`codigo_iso`,_latin1',',`jobdaily`.`job_municipios`.`codigo_dane_departamento`,_latin1',',`jobdaily`.`job_municipios`.`codigo_dane_municipio`) AS `llave_primaria` from ((`jobdaily`.`job_municipios` join `jobdaily`.`job_departamentos`) join `jobdaily`.`job_paises`) where ((`jobdaily`.`job_municipios`.`codigo_dane_departamento` = `jobdaily`.`job_departamentos`.`codigo_dane_departamento`) and (`jobdaily`.`job_departamentos`.`codigo_iso` = `jobdaily`.`job_paises`.`codigo_iso`) and (`jobdaily`.`job_municipios`.`codigo_dane_municipio` <> _latin1'')) order by concat(concat(`jobdaily`.`job_departamentos`.`codigo_dane_departamento`,`jobdaily`.`job_municipios`.`codigo_dane_municipio`),_latin1'-',`jobdaily`.`job_municipios`.`nombre`,_latin1', ',`jobdaily`.`job_departamentos`.`nombre`,_latin1', ',`jobdaily`.`job_paises`.`nombre`,_latin1'|',concat(`jobdaily`.`job_departamentos`.`codigo_iso`,_latin1',',`jobdaily`.`job_municipios`.`codigo_dane_departamento`,_latin1',',`jobdaily`.`job_municipios`.`codigo_dane_municipio`));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_seleccion_plan_contable`
-- 

DROP VIEW IF EXISTS `job_seleccion_plan_contable`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_seleccion_plan_contable` AS select `jobdaily`.`job_plan_contable`.`codigo_contable` AS `id`,concat(`jobdaily`.`job_plan_contable`.`codigo_contable`,_latin1', ',`jobdaily`.`job_plan_contable`.`descripcion`,_latin1'|',`jobdaily`.`job_plan_contable`.`codigo_contable`) AS `codigo_contable` from `jobdaily`.`job_plan_contable` where ((`jobdaily`.`job_plan_contable`.`clase_cuenta` = 1) and (`jobdaily`.`job_plan_contable`.`codigo_contable` <> _latin1''));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_seleccion_plan_contable_credito`
-- 

DROP VIEW IF EXISTS `job_seleccion_plan_contable_credito`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_seleccion_plan_contable_credito` AS select `jobdaily`.`job_plan_contable`.`codigo_contable` AS `id`,concat(`jobdaily`.`job_plan_contable`.`codigo_contable`,_latin1', ',`jobdaily`.`job_plan_contable`.`descripcion`,_latin1'|',`jobdaily`.`job_plan_contable`.`codigo_contable`) AS `codigo_contable` from `jobdaily`.`job_plan_contable` where ((`jobdaily`.`job_plan_contable`.`naturaleza_cuenta` = _latin1'C') and (`jobdaily`.`job_plan_contable`.`codigo_contable` <> 0));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_seleccion_plan_contable_debito`
-- 

DROP VIEW IF EXISTS `job_seleccion_plan_contable_debito`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_seleccion_plan_contable_debito` AS select `jobdaily`.`job_plan_contable`.`codigo_contable` AS `id`,concat(`jobdaily`.`job_plan_contable`.`codigo_contable`,_latin1', ',`jobdaily`.`job_plan_contable`.`descripcion`,_latin1'|',`jobdaily`.`job_plan_contable`.`codigo_contable`) AS `codigo_contable` from `jobdaily`.`job_plan_contable` where ((`jobdaily`.`job_plan_contable`.`naturaleza_cuenta` = _latin1'D') and (`jobdaily`.`job_plan_contable`.`codigo_contable` <> _latin1''));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_seleccion_plan_contable_flujo_bancos`
-- 

DROP VIEW IF EXISTS `job_seleccion_plan_contable_flujo_bancos`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_seleccion_plan_contable_flujo_bancos` AS select `jobdaily`.`job_plan_contable`.`codigo_contable` AS `id`,concat(`jobdaily`.`job_plan_contable`.`codigo_contable`,_latin1', ',`jobdaily`.`job_plan_contable`.`descripcion`,_latin1'|',`jobdaily`.`job_plan_contable`.`codigo_contable`) AS `codigo_contable` from `jobdaily`.`job_plan_contable` where ((`jobdaily`.`job_plan_contable`.`flujo_efectivo` = _latin1'3') and (`jobdaily`.`job_plan_contable`.`codigo_contable` <> _latin1''));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_seleccion_plan_contable_transacciones`
-- 

DROP VIEW IF EXISTS `job_seleccion_plan_contable_transacciones`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_seleccion_plan_contable_transacciones` AS select `a`.`codigo_contable` AS `id`,concat(`a`.`codigo_contable`,_latin1', ',if((`a`.`codigo_contable_padre` = _latin1''),_latin1'',(select concat(if((isnull(`b`.`descripcion`) or (char_length(`b`.`codigo_contable`) < 3)),_latin1'',concat(`b`.`descripcion`,_latin1'-')),if((isnull(`c`.`descripcion`) or (char_length(`c`.`codigo_contable`) < 3)),_latin1'',concat(`c`.`descripcion`,_latin1'-')),if((isnull(`d`.`descripcion`) or (char_length(`d`.`codigo_contable`) < 3)),_latin1'',`d`.`descripcion`)) AS `CONCAT(
                        IF(b.descripcion IS NULL OR CHAR_LENGTH(b.codigo_contable) < 3, '', CONCAT(b.descripcion, '-')),
                        IF(c.descripcion IS NULL OR CHAR_LENGTH(c.codigo_contable) < 3, '', CONCAT(c.descripcion, '-')),
   ` from ((`jobdaily`.`job_plan_contable` `b` left join `jobdaily`.`job_plan_contable` `c` on((`b`.`codigo_contable` = `c`.`codigo_contable_padre`))) left join `jobdaily`.`job_plan_contable` `d` on((`c`.`codigo_contable` = `d`.`codigo_contable_padre`))) where (`d`.`codigo_contable` = `a`.`codigo_contable_padre`))),_latin1'-',`a`.`descripcion`,_latin1'|',`a`.`codigo_contable`) AS `cuenta` from `jobdaily`.`job_plan_contable` `a` where ((`a`.`clase_cuenta` = 1) and (`a`.`codigo_contable` <> _latin1''));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_seleccion_planillas`
-- 

DROP VIEW IF EXISTS `job_seleccion_planillas`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_seleccion_planillas` AS select `jobdaily`.`job_planillas`.`codigo` AS `id`,concat(`jobdaily`.`job_planillas`.`descripcion`,_utf8'|',`jobdaily`.`job_planillas`.`codigo`) AS `descripcion` from `jobdaily`.`job_planillas` where (`jobdaily`.`job_planillas`.`codigo` > 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_seleccion_profesiones`
-- 

DROP VIEW IF EXISTS `job_seleccion_profesiones`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_seleccion_profesiones` AS select `jobdaily`.`job_profesiones_oficios`.`codigo_dane` AS `id`,concat(`jobdaily`.`job_profesiones_oficios`.`codigo_dane`,_utf8' - ',`jobdaily`.`job_profesiones_oficios`.`descripcion`,_utf8'|',`jobdaily`.`job_profesiones_oficios`.`codigo_dane`) AS `descripcion` from `jobdaily`.`job_profesiones_oficios` where (`jobdaily`.`job_profesiones_oficios`.`codigo_dane` > 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_seleccion_proveedores`
-- 

DROP VIEW IF EXISTS `job_seleccion_proveedores`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_seleccion_proveedores` AS select `jobdaily`.`job_proveedores`.`documento_identidad` AS `id`,concat(`jobdaily`.`job_terceros`.`documento_identidad`,_latin1'-',if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),concat(concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' '),if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`primer_apellido` is not null),concat(`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),concat(`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1' '),_latin1'')),`jobdaily`.`job_terceros`.`razon_social`),_latin1'|',`jobdaily`.`job_proveedores`.`documento_identidad`) AS `nombre`,`jobdaily`.`job_proveedores`.`publicidad` AS `publicidad` from (`jobdaily`.`job_terceros` join `jobdaily`.`job_proveedores`) where (`jobdaily`.`job_terceros`.`documento_identidad` = `jobdaily`.`job_proveedores`.`documento_identidad`) order by `jobdaily`.`job_terceros`.`primer_nombre`;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_seleccion_resolucion_dian`
-- 

DROP VIEW IF EXISTS `job_seleccion_resolucion_dian`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_seleccion_resolucion_dian` AS select concat(`jobdaily`.`job_resoluciones_dian`.`codigo_sucursal`,_utf8'|',`jobdaily`.`job_resoluciones_dian`.`numero`) AS `id`,`jobdaily`.`job_resoluciones_dian`.`estado` AS `id_estado`,`jobdaily`.`job_resoluciones_dian`.`codigo_sucursal` AS `id_sucursal`,`jobdaily`.`job_tipos_documentos`.`codigo` AS `id_tipo_documento`,concat(_utf8'Almacen: ',`jobdaily`.`job_sucursales`.`nombre`,_utf8' - ',_utf8'Numero: ',`jobdaily`.`job_resoluciones_dian`.`numero`,_utf8' - ',_utf8'Fecha: ',`jobdaily`.`job_resoluciones_dian`.`fecha_inicia`,_utf8' - ',_utf8'Prefijo: ',`jobdaily`.`job_resoluciones_dian`.`prefijo`) AS `descripcion` from ((`jobdaily`.`job_resoluciones_dian` join `jobdaily`.`job_sucursales`) join `jobdaily`.`job_tipos_documentos`) where ((`jobdaily`.`job_resoluciones_dian`.`codigo_sucursal` = `jobdaily`.`job_sucursales`.`codigo`) and (`jobdaily`.`job_resoluciones_dian`.`codigo_tipo_documento` = `jobdaily`.`job_tipos_documentos`.`codigo`));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_seleccion_resoluciones_contribuyente`
-- 

DROP VIEW IF EXISTS `job_seleccion_resoluciones_contribuyente`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_seleccion_resoluciones_contribuyente` AS select `jobdaily`.`job_resoluciones_gran_contribuyente`.`numero_resolucion` AS `id`,concat(_utf8'Numero: ',`jobdaily`.`job_resoluciones_gran_contribuyente`.`numero_resolucion`,_utf8'-',_utf8'Fecha: ',`jobdaily`.`job_resoluciones_gran_contribuyente`.`fecha`,_utf8'|',`jobdaily`.`job_resoluciones_gran_contribuyente`.`numero_resolucion`) AS `descripcion` from `jobdaily`.`job_resoluciones_gran_contribuyente` where (`jobdaily`.`job_resoluciones_gran_contribuyente`.`numero_resolucion` <> 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_seleccion_resoluciones_retefuente`
-- 

DROP VIEW IF EXISTS `job_seleccion_resoluciones_retefuente`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_seleccion_resoluciones_retefuente` AS select `jobdaily`.`job_resoluciones_retefuente`.`numero_retefuente` AS `id`,concat(_utf8'Numero: ',`jobdaily`.`job_resoluciones_retefuente`.`numero_retefuente`,_utf8'-',_utf8'Fecha: ',`jobdaily`.`job_resoluciones_retefuente`.`fecha`,_utf8'|',`jobdaily`.`job_resoluciones_retefuente`.`numero_retefuente`) AS `descripcion` from `jobdaily`.`job_resoluciones_retefuente` where (`jobdaily`.`job_resoluciones_retefuente`.`numero_retefuente` <> 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_seleccion_resoluciones_reteica`
-- 

DROP VIEW IF EXISTS `job_seleccion_resoluciones_reteica`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_seleccion_resoluciones_reteica` AS select concat(`jobdaily`.`job_resoluciones_ica`.`numero_resolucion`,_utf8'|',`jobdaily`.`job_resoluciones_ica`.`codigo_sucursal`) AS `id`,concat(_utf8'Numero: ',`jobdaily`.`job_resoluciones_ica`.`numero_resolucion`,_utf8'-',_utf8'Fecha: ',`jobdaily`.`job_resoluciones_ica`.`fecha`,_utf8'|',`jobdaily`.`job_resoluciones_ica`.`numero_resolucion`) AS `descripcion` from `jobdaily`.`job_resoluciones_ica` where (`jobdaily`.`job_resoluciones_ica`.`numero_resolucion` <> 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_seleccion_secciones_departamentos`
-- 

DROP VIEW IF EXISTS `job_seleccion_secciones_departamentos`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_seleccion_secciones_departamentos` AS select concat(`jobdaily`.`job_secciones_departamentos`.`codigo`,_utf8'|',`jobdaily`.`job_secciones_departamentos`.`codigo_departamento_empresa`) AS `id`,concat(`jobdaily`.`job_secciones_departamentos`.`nombre`,_utf8'|',`jobdaily`.`job_secciones_departamentos`.`codigo`,_utf8',',`jobdaily`.`job_secciones_departamentos`.`codigo_departamento_empresa`) AS `NOMBRE` from `jobdaily`.`job_secciones_departamentos`;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_seleccion_sucursales`
-- 

DROP VIEW IF EXISTS `job_seleccion_sucursales`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_seleccion_sucursales` AS select `jobdaily`.`job_sucursales`.`codigo` AS `id`,concat(`jobdaily`.`job_sucursales`.`nombre`,_utf8'|',`jobdaily`.`job_sucursales`.`codigo`) AS `nombre` from `jobdaily`.`job_sucursales` where (`jobdaily`.`job_sucursales`.`codigo` <> 0) order by `jobdaily`.`job_sucursales`.`nombre`;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_seleccion_sucursales_bancos`
-- 

DROP VIEW IF EXISTS `job_seleccion_sucursales_bancos`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_seleccion_sucursales_bancos` AS select concat(`jobdaily`.`job_sucursales_bancos`.`codigo`,_utf8'|',`jobdaily`.`job_sucursales_bancos`.`codigo_banco`,_utf8'|',`jobdaily`.`job_sucursales_bancos`.`codigo_iso`,_utf8'|',`jobdaily`.`job_sucursales_bancos`.`codigo_dane_departamento`,_utf8'|',`jobdaily`.`job_sucursales_bancos`.`codigo_dane_municipio`) AS `id`,`jobdaily`.`job_sucursales_bancos`.`nombre_sucursal` AS `nombre_sucursal`,`jobdaily`.`job_sucursales_bancos`.`codigo_banco` AS `codigo_banco` from (`jobdaily`.`job_sucursales_bancos` join `jobdaily`.`job_bancos`) where ((`jobdaily`.`job_sucursales_bancos`.`codigo_banco` = `jobdaily`.`job_bancos`.`codigo`) and (`jobdaily`.`job_bancos`.`codigo` <> 0));

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_seleccion_terceros`
-- 

DROP VIEW IF EXISTS `job_seleccion_terceros`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_seleccion_terceros` AS select `jobdaily`.`job_terceros`.`documento_identidad` AS `id`,concat(`jobdaily`.`job_terceros`.`documento_identidad`,_latin1', ',if((`jobdaily`.`job_terceros`.`primer_nombre` is not null),concat(`jobdaily`.`job_terceros`.`primer_nombre`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`segundo_nombre` is not null),concat(`jobdaily`.`job_terceros`.`segundo_nombre`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`primer_apellido` is not null),concat(`jobdaily`.`job_terceros`.`primer_apellido`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`segundo_apellido` is not null),concat(`jobdaily`.`job_terceros`.`segundo_apellido`,_latin1' '),_latin1''),if((`jobdaily`.`job_terceros`.`razon_social` is not null),`jobdaily`.`job_terceros`.`razon_social`,_latin1''),_latin1'|',`jobdaily`.`job_terceros`.`documento_identidad`) AS `nombre` from `jobdaily`.`job_terceros` where (`jobdaily`.`job_terceros`.`documento_identidad` <> _latin1'0') order by `jobdaily`.`job_terceros`.`primer_nombre`;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_seleccion_terceros_juridicos_codigo_interno`
-- 

DROP VIEW IF EXISTS `job_seleccion_terceros_juridicos_codigo_interno`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_seleccion_terceros_juridicos_codigo_interno` AS select `jobdaily`.`job_terceros`.`documento_identidad` AS `id`,concat(`jobdaily`.`job_terceros`.`documento_identidad`,_latin1', ',if((`jobdaily`.`job_terceros`.`razon_social` is not null),`jobdaily`.`job_terceros`.`razon_social`,_latin1''),_latin1'|',`jobdaily`.`job_terceros`.`documento_identidad`) AS `nombre` from `jobdaily`.`job_terceros` where ((`jobdaily`.`job_terceros`.`documento_identidad` <> _latin1'0') and (`jobdaily`.`job_terceros`.`tipo_persona` <> _latin1'1')) order by `jobdaily`.`job_terceros`.`razon_social`;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_seleccion_tipo_contrato`
-- 

DROP VIEW IF EXISTS `job_seleccion_tipo_contrato`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_seleccion_tipo_contrato` AS select `jobdaily`.`job_tipos_contrato`.`codigo` AS `id`,concat(`jobdaily`.`job_tipos_contrato`.`descripcion`,_utf8'|',`jobdaily`.`job_tipos_contrato`.`codigo`) AS `descripcion` from `jobdaily`.`job_tipos_contrato` where (`jobdaily`.`job_tipos_contrato`.`codigo` <> 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_seleccion_todo_plan_contable`
-- 

DROP VIEW IF EXISTS `job_seleccion_todo_plan_contable`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_seleccion_todo_plan_contable` AS select `jobdaily`.`job_plan_contable`.`codigo_contable` AS `id`,concat(`jobdaily`.`job_plan_contable`.`codigo_contable`,_latin1', ',`jobdaily`.`job_plan_contable`.`descripcion`,_latin1'|',`jobdaily`.`job_plan_contable`.`codigo_contable`) AS `codigo_contable` from `jobdaily`.`job_plan_contable` where (`jobdaily`.`job_plan_contable`.`codigo_contable` <> _latin1'');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_seleccion_transacciones_contables_empleado`
-- 

DROP VIEW IF EXISTS `job_seleccion_transacciones_contables_empleado`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_seleccion_transacciones_contables_empleado` AS select `jobdaily`.`job_transacciones_contables_empleado`.`codigo` AS `id`,concat(`jobdaily`.`job_transacciones_contables_empleado`.`nombre`,_utf8'|',`jobdaily`.`job_transacciones_contables_empleado`.`codigo`) AS `nombre`,`jobdaily`.`job_transacciones_contables_empleado`.`codigo_contable` AS `codigo_contable` from `jobdaily`.`job_transacciones_contables_empleado` order by `jobdaily`.`job_transacciones_contables_empleado`.`codigo`;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_seleccion_transacciones_tiempo`
-- 

DROP VIEW IF EXISTS `job_seleccion_transacciones_tiempo`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_seleccion_transacciones_tiempo` AS select `jobdaily`.`job_transacciones_tiempo`.`codigo` AS `id`,concat(`jobdaily`.`job_transacciones_tiempo`.`nombre`,_utf8'|',`jobdaily`.`job_transacciones_tiempo`.`codigo`) AS `nombre` from `jobdaily`.`job_transacciones_tiempo` order by `jobdaily`.`job_transacciones_tiempo`.`codigo`;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_seleccion_unidades`
-- 

DROP VIEW IF EXISTS `job_seleccion_unidades`;
CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_seleccion_unidades` AS select `jobdaily`.`job_unidades`.`codigo` AS `id`,concat(`jobdaily`.`job_unidades`.`codigo`,_utf8' - ',`jobdaily`.`job_unidades`.`nombre`,_utf8'|',`jobdaily`.`job_unidades`.`codigo`) AS `descripcion` from `jobdaily`.`job_unidades` where (`jobdaily`.`job_unidades`.`codigo` <> 0);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_ultimo_contrato_empleado`
-- 

DROP VIEW IF EXISTS `job_ultimo_contrato_empleado`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_ultimo_contrato_empleado` AS select `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_empresa` AS `codigo_empresa`,`jobdaily`.`job_sucursal_contrato_empleados`.`documento_identidad_empleado` AS `documento_identidad_empleado`,`jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso` AS `fecha_ingreso`,`jobdaily`.`job_sucursal_contrato_empleados`.`codigo_sucursal` AS `codigo_sucursal`,max(`jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso_sucursal`) AS `fecha_ingreso_sucursal`,`jobdaily`.`job_sucursal_contrato_empleados`.`codigo_planilla` AS `codigo_planilla` from (`jobdaily`.`job_sucursal_contrato_empleados` join `jobdaily`.`job_ingreso_empleados`) where ((`jobdaily`.`job_sucursal_contrato_empleados`.`codigo_empresa` = `jobdaily`.`job_ingreso_empleados`.`codigo_empresa`) and (`jobdaily`.`job_sucursal_contrato_empleados`.`documento_identidad_empleado` = `jobdaily`.`job_ingreso_empleados`.`documento_identidad_empleado`) and (`jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso` = `jobdaily`.`job_ingreso_empleados`.`fecha_ingreso`) and (`jobdaily`.`job_sucursal_contrato_empleados`.`codigo_sucursal` = `jobdaily`.`job_ingreso_empleados`.`codigo_sucursal_activo`)) group by `jobdaily`.`job_sucursal_contrato_empleados`.`codigo_empresa`,`jobdaily`.`job_sucursal_contrato_empleados`.`documento_identidad_empleado`,`jobdaily`.`job_sucursal_contrato_empleados`.`fecha_ingreso`,`jobdaily`.`job_sucursal_contrato_empleados`.`codigo_sucursal`;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `job_vista_control_contrato`
-- 

DROP VIEW IF EXISTS `job_vista_control_contrato`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jobdaily`.`job_vista_control_contrato` AS select `p`.`documento_identidad_empleado` AS `documento_identidad_empleado`,`p`.`consecutivo` AS `consecutivo`,`p`.`fecha_generacion` AS `fecha_generacion`,`p`.`concepto_prestamo` AS `concepto_prestamo`,`p`.`codigo_empresa` AS `codigo_empresa`,`p`.`fecha_ingreso` AS `fecha_ingreso`,`p`.`codigo_sucursal` AS `codigo_sucursal`,`p`.`fecha_ingreso_sucursal` AS `fecha_ingreso_sucursal`,`p`.`codigo_tipo_documento` AS `codigo_tipo_documento`,`p`.`codigo_transaccion_contable_descontar` AS `codigo_transaccion_contable_descontar`,`p`.`codigo_transaccion_contable_cobrar` AS `transaccion_contable_cobrar`,`p`.`valor_total` AS `valor_total`,`p`.`forma_pago` AS `forma_pago`,if(isnull((select sum(`ms`.`valor_descuento`) AS `SUM(MS.valor_descuento)` from `jobdaily`.`job_movimiento_control_prestamos_empleados` `MS` where ((`ms`.`documento_identidad_empleado` = `p`.`documento_identidad_empleado`) and (`ms`.`consecutivo_fecha_pago` = `p`.`consecutivo`) and (`ms`.`fecha_generacion_control` = `p`.`fecha_generacion`) and (`ms`.`concepto_prestamo` = `p`.`concepto_prestamo`)) group by `p`.`documento_identidad_empleado`,`ms`.`concepto_prestamo`)),0,(select sum(`ms`.`valor_descuento`) AS `SUM(MS.valor_descuento)` from `jobdaily`.`job_movimiento_control_prestamos_empleados` `MS` where ((`ms`.`documento_identidad_empleado` = `p`.`documento_identidad_empleado`) and (`ms`.`consecutivo_fecha_pago` = `p`.`consecutivo`) and (`ms`.`fecha_generacion_control` = `p`.`fecha_generacion`) and (`ms`.`concepto_prestamo` = `p`.`concepto_prestamo`)) group by `p`.`documento_identidad_empleado`,`ms`.`concepto_prestamo`)) AS `valor_pago` from `jobdaily`.`job_control_prestamos_empleados` `P` where (if(isnull((select sum(`ms`.`valor_descuento`) AS `SUM(MS.valor_descuento)` from `jobdaily`.`job_movimiento_control_prestamos_empleados` `MS` where ((`ms`.`documento_identidad_empleado` = `p`.`documento_identidad_empleado`) and (`ms`.`consecutivo_fecha_pago` = `p`.`consecutivo`) and (`ms`.`fecha_generacion_control` = `p`.`fecha_generacion`) and (`ms`.`concepto_prestamo` = `p`.`concepto_prestamo`)) group by `p`.`documento_identidad_empleado`,`ms`.`concepto_prestamo`)),0,(select sum(`ms`.`valor_descuento`) AS `SUM(MS.valor_descuento)` from `jobdaily`.`job_movimiento_control_prestamos_empleados` `MS` where ((`ms`.`documento_identidad_empleado` = `p`.`documento_identidad_empleado`) and (`ms`.`consecutivo_fecha_pago` = `p`.`consecutivo`) and (`ms`.`fecha_generacion_control` = `p`.`fecha_generacion`) and (`ms`.`concepto_prestamo` = `p`.`concepto_prestamo`)) group by `p`.`documento_identidad_empleado`,`ms`.`concepto_prestamo`)) < `p`.`valor_total`);

-- 
-- Filtros para las tablas descargadas (dump)
-- 

-- 
-- Filtros para la tabla `job_abonos_items_movimientos_contables`
-- 
ALTER TABLE `job_abonos_items_movimientos_contables`
  ADD CONSTRAINT `abonos_items_movimientos_contables_item` FOREIGN KEY (`codigo_sucursal`, `documento_identidad_tercero`, `codigo_tipo_comprobante`, `numero_comprobante`, `codigo_tipo_documento`, `consecutivo_documento`, `fecha_contabilizacion`, `consecutivo_item`) REFERENCES `job_items_movimientos_contables` (`codigo_sucursal`, `documento_identidad_tercero`, `codigo_tipo_comprobante`, `numero_comprobante`, `codigo_tipo_documento`, `consecutivo_documento`, `fecha_contabilizacion`, `consecutivo`) ON UPDATE CASCADE,
  ADD CONSTRAINT `abonos_items_movimientos_contables_saldo` FOREIGN KEY (`codigo_sucursal_saldo`, `documento_identidad_tercero_saldo`, `codigo_tipo_comprobante_saldo`, `numero_comprobante_saldo`, `codigo_tipo_documento_saldo`, `consecutivo_documento_saldo`, `fecha_contabilizacion_saldo`, `consecutivo_saldo`, `fecha_vencimiento_saldo`) REFERENCES `job_saldos_items_movimientos_contables` (`codigo_sucursal`, `documento_identidad_tercero`, `codigo_tipo_comprobante`, `numero_comprobante`, `codigo_tipo_documento`, `consecutivo_documento`, `fecha_contabilizacion`, `consecutivo`, `fecha_vencimiento`) ON UPDATE CASCADE;

-- 
-- Filtros para la tabla `job_actividades_economicas`
-- 
ALTER TABLE `job_actividades_economicas`
  ADD CONSTRAINT `actividad_economica_codigo_dian` FOREIGN KEY (`codigo_dian`) REFERENCES `job_actividades_economicas_dian` (`codigo_dian`) ON UPDATE CASCADE,
  ADD CONSTRAINT `actividad_economica_id_municipio` FOREIGN KEY (`codigo_iso`, `codigo_dane_departamento`, `codigo_dane_municipio`) REFERENCES `job_municipios` (`codigo_iso`, `codigo_dane_departamento`, `codigo_dane_municipio`) ON UPDATE CASCADE;

-- 
-- Filtros para la tabla `job_aficiones_aspirante`
-- 
ALTER TABLE `job_aficiones_aspirante`
  ADD CONSTRAINT `aficiones_aspirante` FOREIGN KEY (`documento_identidad_aspirante`) REFERENCES `job_aspirantes` (`documento_identidad`) ON UPDATE CASCADE,
  ADD CONSTRAINT `aficiones_aspirante_aficiones` FOREIGN KEY (`codigo_aficion`) REFERENCES `job_aficiones` (`codigo`) ON UPDATE CASCADE;

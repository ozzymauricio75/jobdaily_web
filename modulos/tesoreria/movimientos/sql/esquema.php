<?php
/**
*
* Copyright (C) 2020 Jobdaily
* Raul Mauricio Oidor Lozano <ozzymauricio75@gmail.com>
*
* Este archivo es parte de:
* Jobdaily:: Sofware empresarial a la medida
*
* Este programa es software libre: usted puede redistribuirlo y/o
* modificarlo  bajo los términos de la Licencia Pública General GNU
* publicada por la Fundación para el Software Libre, ya sea la versión 3
* de la Licencia, o (a su elección) cualquier versión posterior.
*
* Este programa se distribuye con la esperanza de que sea útil, pero
* SIN GARANTÍA ALGUNA; ni siquiera la garantía implícita MERCANTIL o
* de APTITUD PARA UN PROPÓSITO DETERMINADO. Consulte los detalles de
* la Licencia Pública General GNU para obtener una información más
* detallada.
*
* Debería haber recibido una copia de la Licencia Pública General GNU
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
**/
$borrarSiempre = false;

// Definición de tablas
$tablas["saldos_movimientos"]  = array(
    "codigo"                      => "INT(9) UNSIGNED ZEROFILL AUTO_INCREMENT NOT NULL COMMENT 'Codigo interno asignado por el usuario'",
    "codigo_movimiento"           => "INT(9) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la tabla movimientos de tesoreria'",
    "cuenta_origen"               => "VARCHAR(30) NULL COMMENT 'Numero de la cuenta bancaria origen'",
    "cuenta_destino"              => "VARCHAR(30) NULL COMMENT 'Numero de la cuenta bancaria destino'",
    "saldo"                       => "DECIMAL(15,4) UNSIGNED  NOT NULL COMMENT 'Valor total del movimiento'",
    "fecha_saldo"                 => "DATETIME NOT NULL COMMENT 'Fecha ingreso al sistema'",
    "codigo_usuario_registra"     => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id del usuario que genera el registro'",
    "observaciones"               => "VARCHAR(255) COMMENT 'Observacion general del movimiento'",
    "estado"                      => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT '0->Activo 1->Anulado'",
);

$tablas["movimientos_tesoreria"]  = array(
    "codigo"                      => "INT(9) UNSIGNED ZEROFILL AUTO_INCREMENT NOT NULL COMMENT 'Codigo interno asignado por el usuario'",
    "sentido"                     => "ENUM('D','C') COMMENT 'Indicador de sentido del movimiento: D=Debito, C=Credito'",
    "codigo_proyecto"             => "INT(9) NULL COMMENT 'Codigo interno del proyecto'",
    "numero_credito"              => "VARCHAR(30) NULL COMMENT 'Número del credito'",
    "codigo_grupo_tesoreria"      => "SMALLINT(3) NOT NULL COMMENT 'Codigo interno del grupo tesoreria'",
    "codigo_concepto_tesoreria"   => "SMALLINT(3) NOT NULL COMMENT 'Codigo interno del concepto tesoreria'",
    "cuenta_proveedor"            => "VARCHAR(50) NULL COMMENT 'Numero de la cuenta bancaria del proveedor'",
    "cuenta_origen"               => "VARCHAR(30) NULL COMMENT 'Numero de la cuenta bancaria origen'",
    "cuenta_destino"              => "VARCHAR(30) NULL COMMENT 'Numero de la cuenta bancaria destino'",
    "valor_movimiento"            => "DECIMAL(15,4) UNSIGNED  NOT NULL COMMENT 'Valor total del movimiento'",
    "documento_identidad_tercero" => "VARCHAR(12) NULL COMMENT 'Número del documento de identidad del tercero'",
    "fecha_registra"              => "DATE NOT NULL COMMENT 'Fecha ingreso al sistema'",
    "codigo_usuario_registra"     => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id del usuario que genera el registro'",
    "observaciones"               => "VARCHAR(255) COMMENT 'Observacion general del movimiento'",
    "estado"                      => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT '0->Activo 1->Anulado'",
);

// Definición de llaves primarias
$llavesPrimarias["saldos_movimientos"]    = "codigo";
$llavesPrimarias["movimientos_tesoreria"] = "codigo";

// Definición de las llaves unicas
$llavesUnicas["saldos_movimientos"] = array(
    "codigo,codigo_movimiento"
);

$llavesUnicas["movimientos_tesoreria"] = array(
    "codigo,sentido,codigo_proyecto,codigo_grupo_tesoreria,codigo_concepto_tesoreria,cuenta_proveedor,cuenta_origen,valor_movimiento,
    documento_identidad_tercero,fecha_registra"
);

$registros["componentes"] = array(
    /*array(
        "id"              => "SUBMMOTE",
        "padre"           => "MENUTESO",
        "id_modulo"       => "TESORERIA",
        "orden"           => "3000",
        "carpeta"         => "movimientos",
        "archivo"         => "menu",
        "global"          => "0",
        "requiere_item"   => "0",
        "tipo_enlace"     => "1"
    ),*/
    array(
        "id"              => "ADICMVTE",
        "padre"           => "SUBMMOTE",
        "id_modulo"       => "TESORERIA",
        "visible"         => "0",
        "orden"           => "10",
        "carpeta"         => "movimientos",
        "archivo"         => "adicionar",
        "requiere_item"   => "0",
        "tabla_principal" => "movimientos_tesoreria",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "CONSMVTE",
        "padre"           => "SUBMMOTE",
        "id_modulo"       => "TESORERIA",
        "visible"         => "0",
        "orden"           => "20",
        "carpeta"         => "movimientos",
        "archivo"         => "consultar",
        "requiere_item"   => "1",
        "tabla_principal" => "movimientos_tesoreria",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "MODIMVTE",
        "padre"           => "SUBMMOTE",
        "id_modulo"       => "TESORERIA",
        "visible"         => "0",
        "orden"           => "30",
        "carpeta"         => "movimientos",
        "archivo"         => "modificar",
        "requiere_item"   => "1",
        "tabla_principal" => "movimientos_tesoreria",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "ELIMMVTE",
        "padre"           => "SUBMMOTE",
        "id_modulo"       => "TESORERIA",
        "visible"         => "0",
        "orden"           => "40",
        "carpeta"         => "movimientos",
        "archivo"         => "eliminar",
        "requiere_item"   => "1",
        "tabla_principal" => "movimientos_tesoreria",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "ANULMVTE",
        "padre"           => "SUBMMOTE",
        "id_modulo"       => "TESORERIA",
        "visible"         => "0",
        "orden"           => "50",
        "carpeta"         => "movimientos",
        "archivo"         => "anular",
        "requiere_item"   => "1",
        "tabla_principal" => "movimientos_tesoreria",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "REPOMVTE",
        "padre"           => "SUBMMOTE",
        "id_modulo"       => "TESORERIA",
        "visible"         => "0",
        "orden"           => "50",
        "carpeta"         => "movimientos",
        "archivo"         => "reporte",
        "global"          => "0",
        "tipo_enlace"     => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_movimientos_tesoreria AS
        SELECT job_movimientos_tesoreria.codigo AS id,
            job_movimientos_tesoreria.codigo AS CODIGO,
            job_grupos_tesoreria.nombre_grupo AS GRUPO_TESORERIA,
            job_conceptos_tesoreria.nombre_concepto AS CONCEPTO_TESORERIA,
            job_movimientos_tesoreria.cuenta_origen AS CUENTA_ORIGEN,
            job_movimientos_tesoreria.cuenta_destino AS CUENTA_DESTINO_PROPIA,
            FORMAT(job_movimientos_tesoreria.valor_movimiento,0) AS VALOR_MOVIMIENTO,
            job_movimientos_tesoreria.cuenta_proveedor AS CUENTA_DESTINO_PROVEEDOR,
            job_movimientos_tesoreria.fecha_registra AS FECHA_REGISTRO,
            job_movimientos_tesoreria.observaciones AS OBSERVACIONES,
            CONCAT('ESTADO_',job_movimientos_tesoreria.estado) AS ESTADO
        FROM job_movimientos_tesoreria,
             job_grupos_tesoreria,
             job_conceptos_tesoreria   
        WHERE job_grupos_tesoreria.codigo = job_movimientos_tesoreria.codigo_grupo_tesoreria
        AND   job_conceptos_tesoreria.codigo = job_movimientos_tesoreria.codigo_concepto_tesoreria 
        AND   job_movimientos_tesoreria.codigo != 0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_movimientos_tesoreria AS
        SELECT job_movimientos_tesoreria.codigo AS id,
            job_movimientos_tesoreria.codigo AS codigo,
            job_grupos_tesoreria.nombre_grupo AS grupo_tesoreria,
            job_conceptos_tesoreria.nombre_concepto AS concepto_tesoreria,
            job_movimientos_tesoreria.cuenta_origen AS cuenta_origen,
            job_movimientos_tesoreria.cuenta_destino AS cuenta_destino,
            job_movimientos_tesoreria.valor_movimiento AS valor_movimiento,
            job_movimientos_tesoreria.cuenta_proveedor AS cuenta_proveedor,
            job_movimientos_tesoreria.fecha_registra AS fecha_registra,
            job_movimientos_tesoreria.observaciones AS observaciones,
            CONCAT('ESTADO_',job_movimientos_tesoreria.estado) AS estado
        FROM job_movimientos_tesoreria,
             job_grupos_tesoreria,
             job_conceptos_tesoreria   
        WHERE job_grupos_tesoreria.codigo = job_movimientos_tesoreria.codigo_grupo_tesoreria
        AND   job_conceptos_tesoreria.codigo = job_movimientos_tesoreria.codigo_concepto_tesoreria 
        AND   job_movimientos_tesoreria.codigo != 0;"
    )
)
?>

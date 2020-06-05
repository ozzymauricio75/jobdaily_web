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
* modificarlo  bajo los terminos de la Licencia Publica General GNU
* publicada por la Fundacion para el Software Libre, ya sea la version 3
* de la Licencia, o (a su eleccion) cualquier version posterior.
*
* Este programa se distribuye con la esperanza de que sea util, pero
* SIN GARANTIA ALGUNA; ni siquiera la garantia implicita MERCANTIL o
* de APTITUD PARA UN PROPOSITO DETERMINADO. Consulte los detalles de
* la Licencia Publica General GNU para obtener una informacion mas
* detallada.
*
* Deberia haber recibido una copia de la Licencia Publica General GNU
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
**/

$borrarSiempre = false;

$tablas["conceptos_devolucion_compras"] = array(
    "codigo"                  => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo interno para la base de datos'",
    "descripcion"             => "VARCHAR(255) NULL COMMENT 'Descripcion del concepto'",
    "regimen_ventas_empresa"  => "ENUM('1','2') NOT NULL DEFAULT '1' COMMENT 'Regimen empresa: 1 Comun - 2 Simplificado'",
    "regimen_persona"         => "ENUM('1','2') NOT NULL DEFAULT '1' COMMENT 'Regimen persona: 1 Comun - 2 Simplificado'",
    "codigo_tipo_devolucion"  => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'codigo segun la tabla de tipo de devoluciones'",
    "codigo_tasa_iva"         => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'codigo segun la tabla de tipo de compra'",
    "codigo_contable_compras" => "VARCHAR(15) NOT NULL COMMENT 'Codigo contable compras: codigo del plan contable'",
    "codigo_contable_iva"     => "VARCHAR(15) NOT NULL COMMENT 'Codigo contable iva    : Codigo del plan contable'"
);

// Definicion de llaves primarias
$llavesPrimarias["conceptos_devolucion_compras"] = "codigo";

// Definicion de llaves foraneas
$llavesForaneas["conceptos_devolucion_compras"]  = array(
    array(
        // Nombre de la llave
        "conceptos_devolucion_tipo_compra",
        // Nombre del campo clave de la tabla local
        "codigo_tipo_devolucion",
        // Nombre de la tabla relacionada
        "tipos_devoluciones_compra",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    ),array(
        // Nombre de la llave
        "conceptos_devolucion_tasa_iva",
        // Nombre del campo clave de la tabla local
        "codigo_tasa_iva",
        // Nombre de la tabla relacionada
        "tasas",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    ),array(
        // Nombre de la llave
        "conceptos_devolucion_iva",
        // Nombre del campo clave de la tabla local
        "codigo_contable_iva",
        // Nombre de la tabla relacionada
        "plan_contable",
        // Nombre del campo clave en la tabla relacionada
        "codigo_contable"
    ),array(
        // Nombre de la llave
        "conceptos_devolucion_compras",
        // Nombre del campo clave de la tabla local
        "codigo_contable_compras",
        // Nombre de la tabla relacionada
        "plan_contable",
        // Nombre del campo clave en la tabla relacionada
        "codigo_contable"
    )
);

// Insercion de datos iniciales
$registros["componentes"] = array(
    array(
        "id"              => "GESTCDCO",
        "padre"           => "SUBMICPR",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "1",
        "orden"           => "0040",
        "carpeta"         => "conceptos_devolucion_compras",
        "archivo"         => "menu",
        "requiere_item"   => "1",
        "tabla_principal" => "conceptos_devolucion_compras",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "ADICCDCO",
        "padre"           => "GESTCDCO",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "0",
        "orden"           => "0010",
        "carpeta"         => "conceptos_devolucion_compras",
        "archivo"         => "adicionar",
        "requiere_item"   => "0",
        "tabla_principal" => "conceptos_devolucion_compras",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "CONSCDCO",
        "padre"           => "GESTCDCO",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "0",
        "orden"           => "0020",
        "carpeta"         => "conceptos_devolucion_compras",
        "archivo"         => "consultar",
        "requiere_item"   => "1",
        "tabla_principal" => "conceptos_devolucion_compras",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "MODICDCO",
        "padre"           => "GESTCDCO",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "0",
        "orden"           => "0030",
        "carpeta"         => "conceptos_devolucion_compras",
        "archivo"         => "modificar",
        "requiere_item"   => "1",
        "tabla_principal" => "conceptos_devolucion_compras",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "ELIMCDCO",
        "padre"           => "GESTCDCO",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "0",
        "orden"           => "0040",
        "carpeta"         => "conceptos_devolucion_compras",
        "archivo"         => "eliminar",
        "requiere_item"   => "1",
        "tabla_principal" => "conceptos_devolucion_compras",
        "tipo_enlace"     => "1"
    )
);

$vistas = array(
    array("CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_conceptos_devolucion_compras AS
            SELECT
                CDC.codigo AS id,
                CDC.codigo AS CODIGO,
                CDC.descripcion AS DESCRIPCION,
                IF(CDC.regimen_ventas_empresa=1, 'Regimen comun','Regimen simplificado') AS REGIMEN_VENTAS,
                IF(CDC.regimen_persona=1, 'Regimen comun', 'Regimen simplificado') AS REGIMEN_PERSONA
            FROM
                job_conceptos_devolucion_compras AS CDC;"
    ),
    array("CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_conceptos_devolucion_compras AS
            SELECT
                CDC.codigo AS id,
                CDC.codigo AS codigo,
                CDC.descripcion AS DESCRIPCION,
                IF(CDC.regimen_ventas_empresa=1, 'Regimen comun','Regimen simplificado') AS REGIMEN_VENTAS,
                IF(CDC.regimen_persona=1, 'Regimen comun', 'Regimen simplificado') AS REGIMEN_PERSONA
            FROM
                job_conceptos_devolucion_compras AS CDC;"
    )
);
?>

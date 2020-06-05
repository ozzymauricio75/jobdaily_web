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

$tablas["conceptos_contabilizacion_compras"] = array(
    "codigo"                        => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo interno para la base de datos'",
    "descripcion"                   => "VARCHAR(255) NULL COMMENT 'Descripcion del concepto'",
    "regimen_ventas_empresa"        => "ENUM('1','2') NOT NULL DEFAULT '1' COMMENT '1 Común - 2 Simplificado'",
    "regimen_persona"               => "ENUM('1','2') NOT NULL DEFAULT '1' COMMENT '1 Común - 2 Simplificado'",
    "codigo_tipo_compra"            => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'código según la tabla de tipo de compra'",
    "codigo_tasa_iva"               => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'código según la tabla de tipo de compra'",
    "codigo_contable_compras"       => "VARCHAR(15) NOT NULL COMMENT 'Id Código contable compras: código del plan contable para compras'",
    "codigo_contable_iva"           => "VARCHAR(15) NOT NULL COMMENT 'Código contable iva: Código del plan contable para el iva (opcional)'",
    "codigo_contable_iva_debito"    => "VARCHAR(15) NOT NULL COMMENT 'Código contable iva: Código del plan contable para el iva teorico debito (opcional)'",
    "codigo_contable_iva_credito"   => "VARCHAR(15) NOT NULL COMMENT 'Código contable iva: Código del plan contable para el iva teorico credito (opcional)'",
    "codigo_contable_compras_uvt"   => "VARCHAR(15) NOT NULL COMMENT 'Id Código contable compras: código del plan contable para compras de UVT'",
    "codigo_contable_iva_uvt"       => "VARCHAR(15) NOT NULL COMMENT 'Código contable iva: Código del plan contable para el iva de UVT'"
);

// Definicion de llaves primarias
$llavesPrimarias["conceptos_contabilizacion_compras"] = "codigo";

// Definicion de campos unicos
$llavesUnicas["conceptos_contabilizacion_compras"] = array(
    "descripcion"
);

// Definicion de llaves foraneas
$llavesForaneas["conceptos_contabilizacion_compras"]  = array(
    array(
        // Nombre de la llave
        "tipo_compra",
        // Nombre del campo clave de la tabla local
        "codigo_tipo_compra",
        // Nombre de la tabla relacionada
        "tipos_compra",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave
        "tasa_iva",
        // Nombre del campo clave de la tabla local
        "codigo_tasa_iva",
        // Nombre de la tabla relacionada
        "tasas",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave
        "llave_codigo_contable_iva",
        // Nombre del campo clave de la tabla local
        "codigo_contable_iva",
        // Nombre de la tabla relacionada
        "plan_contable",
        // Nombre del campo clave en la tabla relacionada
        "codigo_contable"
    ),
    array(
        // Nombre de la llave
        "llave_codigo_contable_compras",
        // Nombre del campo clave de la tabla local
        "codigo_contable_compras",
        // Nombre de la tabla relacionada
        "plan_contable",
        // Nombre del campo clave en la tabla relacionada
        "codigo_contable"
    ),
    array(
        // Nombre de la llave
        "concepto_contabilizacion_id_cuenta_iva_debito",
        // Nombre del campo clave de la tabla local
        "codigo_contable_iva_debito",
        // Nombre de la tabla relacionada
        "plan_contable",
        // Nombre del campo clave en la tabla relacionada
        "codigo_contable"
    ),
    array(
        // Nombre de la llave
        "concepto_contabilizacion_id_cuenta_iva_credito",
        // Nombre del campo clave de la tabla local
        "codigo_contable_iva_credito",
        // Nombre de la tabla relacionada
        "plan_contable",
        // Nombre del campo clave en la tabla relacionada
        "codigo_contable"
    ),
    array(
        // Nombre de la llave
        "llave_codigo_contable_iva_uvt",
        // Nombre del campo clave de la tabla local
        "codigo_contable_iva_uvt",
        // Nombre de la tabla relacionada
        "plan_contable",
        // Nombre del campo clave en la tabla relacionada
        "codigo_contable"
    ),
    array(
        // Nombre de la llave
        "llave_codigo_contable_compras_uvt",
        // Nombre del campo clave de la tabla local
        "codigo_contable_compras_uvt",
        // Nombre de la tabla relacionada
        "plan_contable",
        // Nombre del campo clave en la tabla relacionada
        "codigo_contable"
    )
);


// Insercion de datos iniciales
$registros["componentes"] = array(
    array(
        "id"        => "GESTCOCO",
        "padre"     => "SUBMICPR",
        "id_modulo" => "CONTABILIDAD",
        "visible"   => "1",
        "orden"     => "0020",
        "carpeta"   => "conceptos_contabilizacion",
        "archivo"   => "menu"
    ),
    array(
        "id"        => "ADICCOCO",
        "padre"     => "GESTCOCO",
        "id_modulo" => "CONTABILIDAD",
        "visible"   => "0",
        "orden"     => "0010",
        "carpeta"   => "conceptos_contabilizacion",
        "archivo"   => "adicionar"
    ),
    array(
        "id"        => "CONSCOCO",
        "padre"     => "GESTCOCO",
        "id_modulo" => "CONTABILIDAD",
        "visible"   => "0",
        "orden"     => "0020",
        "carpeta"   => "conceptos_contabilizacion",
        "archivo"   => "consultar"
    ),
    array(
        "id"        => "MODICOCO",
        "padre"     => "GESTCOCO",
        "id_modulo" => "CONTABILIDAD",
        "visible"   => "0",
        "orden"     => "0030",
        "carpeta"   => "conceptos_contabilizacion",
        "archivo"   => "modificar"
    ),
    array(
        "id"        => "ELIMCOCO",
        "padre"     => "GESTCOCO",
        "id_modulo" => "CONTABILIDAD",
        "visible"   => "0",
        "orden"     => "0040",
        "carpeta"   => "conceptos_contabilizacion",
        "archivo"   => "eliminar"
    )
);

$vistas = array(
    array("CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_conceptos_contabilizacion
            AS
              SELECT
                CCP.codigo AS id,
                CCP.descripcion AS DESCRIPCION,
                CONCAT('REGIMEN_',CCP.regimen_ventas_empresa) AS REGIMEN_VENTAS,
                CONCAT('REGIMEN_',CCP.regimen_persona) AS REGIMEN_PERSONA
              FROM
                job_conceptos_contabilizacion_compras AS CCP;"
    ),
    array("CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_conceptos_contabilizacion
            AS
              SELECT
                CCP.codigo AS id,
                CCP.descripcion AS DESCRIPCION,
                IF(CCP.regimen_ventas_empresa=1, 'Regimen comun','Regimen simplificado') AS REGIMEN_VENTAS,
                IF(CCP.regimen_persona=1, 'Regimen comun', 'Regimen simplificado') AS REGIMEN_PERSONA
              FROM
                job_conceptos_contabilizacion_compras CCP;"
    )
);
?>

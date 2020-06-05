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

$tablas["tipos_devoluciones_compra"] = array(
    "codigo"                         => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo interno para la base de datos'",
    "descripcion"                    => "VARCHAR(150) NOT NULL COMMENT 'Nombre del tipo devolucion compra asignado por el usuario'",
    "concepto_compra"                => "ENUM('1','2','3','4','5') NOT NULL DEFAULT '1' COMMENT 'Concepto compra: solo son validos los siguientes conceptos 1->Compras directas, 2->Compras obsequio, 3->Compras filiales, 4->Compras canje, 5->Compras en consignacion'",
    "codigo_contable_cuentas_pagar"  => "VARCHAR(15) NOT NULL COMMENT 'codigo del plan contable que relaciona con la cuenta por pagar'",
    "codigo_contable_retefuente"     => "VARCHAR(15) NOT NULL COMMENT 'codigo del plan contable que relaciona con la Retencion en la fuente'",
    "codigo_contable_reteiva"        => "VARCHAR(15) NOT NULL COMMENT 'codigo del plan contable que relaciona con la Retencion iva'",
    "codigo_contable_seguro"         => "VARCHAR(15) NOT NULL COMMENT 'codigo del plan contable que relaciona con los seguros por transporte de mercancia'",
    "codigo_contable_fletes"         => "VARCHAR(15) NOT NULL COMMENT 'codigo del plan contable que relaciona con los fletes por transporte de mercancia'",
    "codigo_contable_iva_seguro"     => "VARCHAR(15) NOT NULL COMMENT 'codigo del plan contable que relaciona con el iva del seguro por transporte de mercancia'",
    "codigo_contable_iva_fletes"     => "VARCHAR(15) NOT NULL COMMENT 'codigo del plan contable que relaciona con el iva del flete por transporte de mercancia'"
);

// Definicion de llaves primarias
$llavesPrimarias["tipos_devoluciones_compra"] = "codigo";

// Definicion llaves unicas
$llavesUnicas["tipos_devoluciones_compra"] = array(
    "descripcion"
);

// Definicion de llaves foraneas
$llavesForaneas["tipos_devoluciones_compra"]  = array(
    array(
        // Nombre de la llave
        "codigo_contable_cuentas_pagar",
        // Nombre del campo clave de la tabla local
        "codigo_contable_cuentas_pagar",
        // Nombre de la tabla relacionada
        "plan_contable",
        // Nombre del campo clave en la tabla relacionada
        "codigo_contable"
    ),
    array(
        // Nombre de la llave
        "codigo_contable_retefuente",
        // Nombre del campo clave de la tabla local
        "codigo_contable_retefuente",
        // Nombre de la tabla relacionada
        "plan_contable",
        // Nombre del campo clave en la tabla relacionada
        "codigo_contable"
    ),
    array(
        // Nombre de la llave
        "codigo_contable_reteiva",
        // Nombre del campo clave de la tabla local
        "codigo_contable_reteiva",
        // Nombre de la tabla relacionada
        "plan_contable",
        // Nombre del campo clave en la tabla relacionada
        "codigo_contable"
    ),
    array(
        // Nombre de la llave
        "codigo_contable_seguro",
        // Nombre del campo clave de la tabla local
        "codigo_contable_seguro",
        // Nombre de la tabla relacionada
        "plan_contable",
        // Nombre del campo clave en la tabla relacionada
        "codigo_contable"
    ),
    array(
        // Nombre de la llave
        "codigo_contable_fletes",
        // Nombre del campo clave de la tabla local
        "codigo_contable_fletes",
        // Nombre de la tabla relacionada
        "plan_contable",
        // Nombre del campo clave en la tabla relacionada
        "codigo_contable"
    ),
    array(
        // Nombre de la llave
        "codigo_contable_iva_seguro",
        // Nombre del campo clave de la tabla local
        "codigo_contable_iva_seguro",
        // Nombre de la tabla relacionada
        "plan_contable",
        // Nombre del campo clave en la tabla relacionada
        "codigo_contable"
    ),
    array(
        // Nombre de la llave
        "codigo_contable_iva_fletes",
        // Nombre del campo clave de la tabla local
        "codigo_contable_iva_fletes",
        // Nombre de la tabla relacionada
        "plan_contable",
        // Nombre del campo clave en la tabla relacionada
        "codigo_contable"
    )
);

// Insercion de datos iniciales
$registros["componentes"] = array(
    array(
        "id"              => "GESTTDVC",
        "padre"           => "SUBMICPR",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "1",
        "orden"           => "0030",
        "carpeta"         => "tipos_devoluciones_compra",
        "archivo"         => "menu",
        "requiere_item"   => "1",
        "tabla_principal" => "tipos_devoluciones_compra",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "ADICTDVC",
        "padre"           => "GESTTDVC",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "0",
        "orden"           => "0010",
        "carpeta"         => "tipos_devoluciones_compra",
        "archivo"         => "adicionar",
        "requiere_item"   => "0",
        "tabla_principal" => "tipos_devoluciones_compra",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "CONSTDVC",
        "padre"           => "GESTTDVC",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "0",
        "orden"           => "0020",
        "carpeta"         => "tipos_devoluciones_compra",
        "archivo"         => "consultar",
        "requiere_item"   => "1",
        "tabla_principal" => "tipos_devoluciones_compra",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "MODITDVC",
        "padre"           => "GESTTDVC",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "0",
        "orden"           => "0030",
        "carpeta"         => "tipos_devoluciones_compra",
        "archivo"         => "modificar",
        "requiere_item"   => "1",
        "tabla_principal" => "tipos_devoluciones_compra",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "ELIMTDVC",
        "padre"           => "GESTTDVC",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "0",
        "orden"           => "0040",
        "carpeta"         => "tipos_devoluciones_compra",
        "archivo"         => "eliminar",
        "requiere_item"   => "1",
        "tabla_principal" => "tipos_devoluciones_compra",
        "tipo_enlace"     => "1"
    )
);

$vistas = array(
    array("CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_tipos_devoluciones_compra AS
            SELECT
                TDC.codigo AS id,
                TDC.codigo AS CODIGO,
                TDC.descripcion AS NOMBRE,
                IF(
                TDC.concepto_compra='1','Compras directas',
                IF(TDC.concepto_compra='2','Compras obsequio',
                IF(TDC.concepto_compra='3','Compras filiales',
                IF(TDC.concepto_compra='4','Compras canje','Compras en consignacion'
                )))
                ) AS CONCEPTO
            FROM
                job_tipos_devoluciones_compra AS TDC;"
    ),
    array("CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_tipos_devoluciones_compra AS
            SELECT
                TDC.codigo AS id,
                TDC.codigo AS codigo,
                TDC.descripcion AS nombre,
                IF(
                TDC.concepto_compra='1','Compras directas',
                IF(TDC.concepto_compra='2','Compras obsequio',
                IF(TDC.concepto_compra='3','Compras filiales',
                IF(TDC.concepto_compra='4','Compras canje','Compras en consignacion'
                )))
                ) AS concepto
            FROM
                job_tipos_devoluciones_compra AS TDC;"
    )
);
?>

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

// Definicion de tablas
$tablas["tipos_compra"] = array(
    "codigo"                                     => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código asignado por el usuario al tipo de compra'",
    "descripcion"                                => "VARCHAR(150) NOT NULL COMMENT 'Nombre del tipo de compra asignado por el usuario'",
    "codigo_contable_cuentas_pagar"              => "VARCHAR(15) NOT NULL COMMENT 'codigo del plan contable que relaciona con la cuenta por pagar'",
    "codigo_contable_retefuente"                 => "VARCHAR(15) NOT NULL COMMENT 'codigo del plan contable que relaciona con la Retencion en la fuente'",
    "codigo_contable_reteiva"                    => "VARCHAR(15) NOT NULL COMMENT 'codigo del plan contable que relaciona con la Retencion iva'",
    "codigo_contable_seguro"                     => "VARCHAR(15) NOT NULL COMMENT 'codigo del plan contable que relaciona con los seguros por transporte de mercancia'",
    "codigo_contable_fletes"                     => "VARCHAR(15) NOT NULL COMMENT 'codigo del plan contable que relaciona con los fletes por transporte de mercancia'",
    "codigo_contable_iva_seguro"                 => "VARCHAR(15) NOT NULL COMMENT 'codigo del plan contable que relaciona con el iva del seguro por transporte de mercancia'",
    "codigo_contable_iva_fletes"                 => "VARCHAR(15) NOT NULL COMMENT 'codigo del plan contable que relaciona con el iva del flete por transporte de mercancia'",
    "codigo_contable_iva_diferencia"             => "VARCHAR(15) NOT NULL COMMENT 'codigo del plan contable que relaciona con la cuenta del IVA en caso de que el proveedor factura Iva y el movimiento de articulos no tenga ningun IVA'",
    "concepto_compra"                            => "ENUM('1','2','3','4','5') NOT NULL default '1' COMMENT 'Concepto compra: solo son válidos los siguientes conceptos 1->Compras directas, 2->Compras obsequio, 3->Compras filiales, 4->Compras canje, 5->Compras en consignación'",
    "codigo_tipo_documento_nota_debito"          => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'código con el que se relaciona el tipo de documento en la nota debito'",
    "valor_base_nota_debito"                     => "DECIMAL(15,2) NOT NULL COMMENT 'Valor minimo por sobre el cual se empiezan a generar las notas debito'",
    "codigo_contable_compra_nota_debito"         => "VARCHAR(15) NOT NULL COMMENT 'codigo del plan contable que relaciona con la cuenta de compras en la nota debito'",
    "codigo_contable_iva_nota_debito"            => "VARCHAR(15) NOT NULL COMMENT 'codigo del plan contable que relaciona con la cuenta del IVA de la nota debito'",
    "codigo_contable_cuentas_pagar_nota_debito"  => "VARCHAR(15) NOT NULL COMMENT 'codigo del plan contable que relaciona con la cuenta por pagar de la nota debito'",
    "codigo_contable_retefuente_nota_debito"     => "VARCHAR(15) NOT NULL COMMENT 'codigo del plan contable que relaciona con la Retencion en la fuente de la nota debito'",
    "codigo_contable_reteiva_nota_debito"        => "VARCHAR(15) NOT NULL COMMENT 'codigo del plan contable que relaciona con la Retencion iva de la nota debito'",
    "codigo_contable_reteica_nota_debito"        => "VARCHAR(15) NOT NULL COMMENT 'codigo del plan contable que relaciona con la Retención ICA de la nota debito'",
    "codigo_tipo_documento_nota_credito"         => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'código con el que se relaciona el tipo de documento en la nota credito'",
    "valor_base_nota_credito"                    => "DECIMAL(15,2) NOT NULL COMMENT 'Valor minimo por sobre el cual se empiezan a generar las notas credito'",
    "codigo_contable_compra_nota_credito"        => "VARCHAR(15) NOT NULL COMMENT 'codigo del plan contable que relaciona con la cuenta de compras en la nota credito'",
    "codigo_contable_iva_nota_credito"           => "VARCHAR(15) NOT NULL COMMENT 'codigo del plan contable que relaciona con la cuenta del IVA de la nota credito'",
    "codigo_contable_cuentas_pagar_nota_credito" => "VARCHAR(15) NOT NULL COMMENT 'codigo del plan contable que relaciona con la cuenta por pagar de la nota credito'",
    "codigo_contable_retefuente_nota_credito"    => "VARCHAR(15) NOT NULL COMMENT 'codigo del plan contable que relaciona con la Retencion en la fuente de la nota credito'",
    "codigo_contable_reteiva_nota_credito"       => "VARCHAR(15) NOT NULL COMMENT 'codigo del plan contable que relaciona con la Retencion iva de la nota credito'",
    "codigo_contable_reteica_nota_credito"       => "VARCHAR(15) NOT NULL COMMENT 'codigo del plan contable que relaciona con la Retencion ICA de la nota credito'",
    "codigo_contable_inventario_provision"       => "VARCHAR(15) NOT NULL COMMENT 'codigo del plan contable que relaciona plan contable inventario provision'",
    "codigo_contable_puente_provision"           => "VARCHAR(15) NOT NULL COMMENT 'codigo del plan contable que relaciona con plan contable puente provision'",
    "codigo_contable_retefuente_provision"       => "VARCHAR(15) NOT NULL COMMENT 'codigo del plan contable que relaciona con la Retencion en la fuente de la provision'"
);

// Definicion de llaves primarias
$llavesPrimarias["tipos_compra"] = "codigo";

// Definicion de campos unicos
$llavesUnicas["tipos_compra"] = array(
    "descripcion"
);

// Definicion de llaves foraneas
$llavesForaneas["tipos_compra"]  = array(
    array(
        // Nombre de la llave
        "tipo_compra_codigo_cuentas_pagar",
        // Nombre del campo clave de la tabla local
        "codigo_contable_cuentas_pagar",
        // Nombre de la tabla relacionada
        "plan_contable",
        // Nombre del campo clave en la tabla relacionada
        "codigo_contable"
    ),
    array(
        // Nombre de la llave
        "tipo_compra_codigo_retefuente",
        // Nombre del campo clave de la tabla local
        "codigo_contable_retefuente",
        // Nombre de la tabla relacionada
        "plan_contable",
        // Nombre del campo clave en la tabla relacionada
        "codigo_contable"
    ),
    array(
        // Nombre de la llave
        "tipo_compra_codigo_reteiva",
        // Nombre del campo clave de la tabla local
        "codigo_contable_reteiva",
        // Nombre de la tabla relacionada
        "plan_contable",
        // Nombre del campo clave en la tabla relacionada
        "codigo_contable"
    ),
    array(
        // Nombre de la llave
        "tipo_compra_codigo_seguro",
        // Nombre del campo clave de la tabla local
        "codigo_contable_seguro",
        // Nombre de la tabla relacionada
        "plan_contable",
        // Nombre del campo clave en la tabla relacionada
        "codigo_contable"
    ),
    array(
        // Nombre de la llave
        "tipo_compra_codigo_fletes",
        // Nombre del campo clave de la tabla local
        "codigo_contable_fletes",
        // Nombre de la tabla relacionada
        "plan_contable",
        // Nombre del campo clave en la tabla relacionada
        "codigo_contable"
    ),
    array(
        // Nombre de la llave
        "tipo_compra_codigo_iva_seguro",
        // Nombre del campo clave de la tabla local
        "codigo_contable_iva_seguro",
        // Nombre de la tabla relacionada
        "plan_contable",
        // Nombre del campo clave en la tabla relacionada
        "codigo_contable"
    ),
    array(
        // Nombre de la llave
        "tipo_compra_codigo_iva_fletes",
        // Nombre del campo clave de la tabla local
        "codigo_contable_iva_fletes",
        // Nombre de la tabla relacionada
        "plan_contable",
        // Nombre del campo clave en la tabla relacionada
        "codigo_contable"
    ),
    array(
        // Nombre de la llave
        "tipo_compra_codigo_iva_diferencia",
        // Nombre del campo clave de la tabla local
        "codigo_contable_iva_diferencia",
        // Nombre de la tabla relacionada
        "plan_contable",
        // Nombre del campo clave en la tabla relacionada
        "codigo_contable"
    ),
    array(
        // Nombre de la llave
        "tipo_compra_codigo_compra_nota_debito",
        // Nombre del campo clave de la tabla local
        "codigo_contable_compra_nota_debito",
        // Nombre de la tabla relacionada
        "plan_contable",
        // Nombre del campo clave en la tabla relacionada
        "codigo_contable"
    ),
    array(
        // Nombre de la llave
        "tipo_compra_codigo_iva_nota_debito",
        // Nombre del campo clave de la tabla local
        "codigo_contable_iva_nota_debito",
        // Nombre de la tabla relacionada
        "plan_contable",
        // Nombre del campo clave en la tabla relacionada
        "codigo_contable"
    ),
    array(
        // Nombre de la llave
        "tipo_compra_codigo_cuentas_pagar_nota_debito",
        // Nombre del campo clave de la tabla local
        "codigo_contable_cuentas_pagar_nota_debito",
        // Nombre de la tabla relacionada
        "plan_contable",
        // Nombre del campo clave en la tabla relacionada
        "codigo_contable"
    ),
    array(
        // Nombre de la llave
        "tipo_compra_codigo_retefuente_nota_debito",
        // Nombre del campo clave de la tabla local
        "codigo_contable_retefuente_nota_debito",
        // Nombre de la tabla relacionada
        "plan_contable",
        // Nombre del campo clave en la tabla relacionada
        "codigo_contable"
    ),
    array(
        // Nombre de la llave
        "tipo_compra_codigo_reteiva_nota_debito",
        // Nombre del campo clave de la tabla local
        "codigo_contable_reteiva_nota_debito",
        // Nombre de la tabla relacionada
        "plan_contable",
        // Nombre del campo clave en la tabla relacionada
        "codigo_contable"
    ),
    array(
        // Nombre de la llave
        "tipo_compra_codigo_reteica_nota_debito",
        // Nombre del campo clave de la tabla local
        "codigo_contable_reteica_nota_debito",
        // Nombre de la tabla relacionada
        "plan_contable",
        // Nombre del campo clave en la tabla relacionada
        "codigo_contable"
    ),
    array(
        // Nombre de la llave
        "tipo_compra_codigo_compra_nota_credito",
        // Nombre del campo clave de la tabla local
        "codigo_contable_compra_nota_credito",
        // Nombre de la tabla relacionada
        "plan_contable",
        // Nombre del campo clave en la tabla relacionada
        "codigo_contable"
    ),
    array(
        // Nombre de la llave
        "tipo_compra_codigo_iva_nota_credito",
        // Nombre del campo clave de la tabla local
        "codigo_contable_iva_nota_credito",
        // Nombre de la tabla relacionada
        "plan_contable",
        // Nombre del campo clave en la tabla relacionada
        "codigo_contable"
    ),
    array(
        // Nombre de la llave
        "tipo_compra_codigo_cuentas_pagar_nota_credito",
        // Nombre del campo clave de la tabla local
        "codigo_contable_cuentas_pagar_nota_credito",
        // Nombre de la tabla relacionada
        "plan_contable",
        // Nombre del campo clave en la tabla relacionada
        "codigo_contable"
    ),
    array(
        // Nombre de la llave
        "tipo_compra_codigo_retefuente_nota_credito",
        // Nombre del campo clave de la tabla local
        "codigo_contable_retefuente_nota_credito",
        // Nombre de la tabla relacionada
        "plan_contable",
        // Nombre del campo clave en la tabla relacionada
        "codigo_contable"
    ),
    array(
        // Nombre de la llave
        "tipo_compra_codigo_reteiva_nota_credito",
        // Nombre del campo clave de la tabla local
        "codigo_contable_reteiva_nota_credito",
        // Nombre de la tabla relacionada
        "plan_contable",
        // Nombre del campo clave en la tabla relacionada
        "codigo_contable"
    ),
    array(
        // Nombre de la llave
        "tipo_compra_codigo_reteica_nota_credito",
        // Nombre del campo clave de la tabla local
        "codigo_contable_reteica_nota_credito",
        // Nombre de la tabla relacionada
        "plan_contable",
        // Nombre del campo clave en la tabla relacionada
        "codigo_contable"
    ),
    array(
        // Nombre de la llave
        "tipo_compra_tipo_documento_nota_debito",
        // Nombre del campo clave de la tabla local
        "codigo_tipo_documento_nota_debito",
        // Nombre de la tabla relacionada
        "tipos_documentos",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave
        "tipo_compra_tipo_documento_nota_credito",
        /// Nombre del campo clave de la tabla local
        "codigo_tipo_documento_nota_credito",
        // Nombre de la tabla relacionada
        "tipos_documentos",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave
        "tipo_compra_codigo_contable_inventario",
        // Nombre del campo clave de la tabla local
        "codigo_contable_inventario_provision",
        // Nombre de la tabla relacionada
        "plan_contable",
        // Nombre del campo clave en la tabla relacionada
        "codigo_contable"
    ),
    array(
        // Nombre de la llave
        "tipo_compra_codigo_contable_puente",
        // Nombre del campo clave de la tabla local
        "codigo_contable_puente_provision",
        // Nombre de la tabla relacionada
        "plan_contable",
        // Nombre del campo clave en la tabla relacionada
        "codigo_contable"
    ),
    array(
        // Nombre de la llave
        "tipo_compra_codigo_contable_retefuente_provision",
        // Nombre del campo clave de la tabla local
        "codigo_contable_retefuente_provision",
        // Nombre de la tabla relacionada
        "plan_contable",
        // Nombre del campo clave en la tabla relacionada
        "codigo_contable"
    )
);

// Insercion de datos iniciales
$registros["tipos_compra"] = array(
    array(
        "codigo"                                     => "0",
        "descripcion"                                => "",
        "codigo_contable_cuentas_pagar"              => "0",
        "codigo_contable_retefuente"                 => "0",
        "codigo_contable_reteiva"                    => "0",
        "codigo_contable_seguro"                     => "0",
        "codigo_contable_fletes"                     => "0",
        "codigo_contable_iva_seguro"                 => "0",
        "codigo_contable_iva_fletes"                 => "0",
        "concepto_compra"                            => "1",
        "codigo_tipo_documento_nota_debito"          => "0",
        "valor_base_nota_debito"                     => "0",
        "codigo_contable_compra_nota_debito"         => "0",
        "codigo_contable_iva_nota_debito"            => "0",
        "codigo_contable_cuentas_pagar_nota_debito"  => "0",
        "codigo_contable_retefuente_nota_debito"     => "0",
        "codigo_contable_reteiva_nota_debito"        => "0",
        "codigo_contable_reteica_nota_debito"        => "0",
        "codigo_tipo_documento_nota_credito"         => "0",
        "valor_base_nota_credito"                    => "0",
        "codigo_contable_compra_nota_credito"        => "0",
        "codigo_contable_iva_nota_credito"           => "0",
        "codigo_contable_cuentas_pagar_nota_credito" => "0",
        "codigo_contable_retefuente_nota_credito"    => "0",
        "codigo_contable_reteiva_nota_credito"       => "0",
        "codigo_contable_reteica_nota_credito"       => "0",
    )
);

$registros["componentes"] = array(
    array(
        "id"              => "GESTTCOM",
        "padre"           => "SUBMICPR",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "1",
        "orden"           => "0010",
        "carpeta"         => "tipos_compra",
        "archivo"         => "menu",
        "requiere_item"   => "1",
        "tabla_principal" => "tipos_compra",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "ADICTCOM",
        "padre"           => "GESTTCOM",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "0",
        "orden"           => "0010",
        "carpeta"         => "tipos_compra",
        "archivo"         => "adicionar",
        "requiere_item"   => "0",
        "tabla_principal" => "tipos_compra",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "CONSTCOM",
        "padre"           => "GESTTCOM",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "0",
        "orden"           => "0020",
        "carpeta"         => "tipos_compra",
        "archivo"         => "consultar",
        "requiere_item"   => "1",
        "tabla_principal" => "tipos_compra",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "MODITCOM",
        "padre"           => "GESTTCOM",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "0",
        "orden"           => "0030",
        "carpeta"         => "tipos_compra",
        "archivo"         => "modificar",
        "requiere_item"   => "1",
        "tabla_principal" => "tipos_compra",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "ELIMTCOM",
        "padre"           => "GESTTCOM",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "0",
        "orden"           => "0040",
        "carpeta"         => "tipos_compra",
        "archivo"         => "eliminar",
        "requiere_item"   => "1",
        "tabla_principal" => "tipos_compra",
        "tipo_enlace"     => "1"
    )
);

$vistas = array(
    array("CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_tipos_compras AS
            SELECT  TC.codigo AS id,
                    TC.codigo AS CODIGO,
                    TC.descripcion AS DESCRIPCION,
                    CONCAT('CONCEPTO_',TC.concepto_compra) AS CONCEPTO
            FROM    job_tipos_compra AS TC
            WHERE   TC.codigo != '0'"
    ),
    array("CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_tipos_compras AS
            SELECT  TC.codigo AS id,
                    TC.codigo AS CODIGO,
                    TC.descripcion AS DESCRIPCION
            FROM    job_tipos_compra AS TC
            WHERE   TC.codigo != '0'"
    )
);
?>

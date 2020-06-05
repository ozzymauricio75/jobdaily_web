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
* publicada por la Fundacion para el Software Libre, ya sea la version 3
* de la Licencia, o (a su eleccion) cualquier version posterior.
*
* Este programa se distribuye con la esperanza de que sea útil, pero
* SIN GARANTÍA ALGUNA; ni siquiera la garantía implícita MERCANTIL o
* de APTITUD PARA UN PROPoSITO DETERMINADO. Consulte los detalles de
* la Licencia Pública General GNU para obtener una informacion más
* detallada.
*
* Debería haber recibido una copia de la Licencia Pública General GNU
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
**/

$borrarSiempre   = false;

// Definicion de tablas
$tablas["movimientos_contables"]  = array(
    "codigo_sucursal"                     => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la sucursal donse se genera el movimiento'",
    "documento_identidad_tercero"         => "VARCHAR(12) NOT NULL COMMENT 'Número del documento de identidad'",
    "codigo_tipo_comprobante"             => "SMALLINT(2) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo del tipo de comprobante'",
    "numero_comprobante"                  => "VARCHAR(20) NOT NULL COMMENT 'Número de comprobante contable'",
    "codigo_tipo_documento"               => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id tabla consecutivo de documentos'",
    "consecutivo_documento"               => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo del documento'",
    "fecha_contabilizacion"               => "DATE NOT NULL COMMENT 'Fecha en que se contabiliza la transaccion'",
    ////////////////////////////////
    "fecha_transaccion"                   => "DATE NOT NULL COMMENT 'Fecha en la que se genera la transaccion'",
    "fecha_documento"                     => "DATE NOT NULL COMMENT 'Fecha del documento que genera'",
    "estado"                              => "ENUM('1','2') DEFAULT '1' COMMENT 'Estado en que se encuentra el movimiento: 1->Activo, 2->Anulado'",
    "observaciones"                       => "VARCHAR(255) COMMENT 'Observaciones para el movimiento'",
    "codigo_usuario_genera"               => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo del usuario que genera'"
);

$tablas["items_movimientos_contables"] = array(
    //////////LAVE DEL MOVIMIENTO CONTABLE//////////////////
    "codigo_sucursal"                     => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la sucursal donse se genera el movimiento'",
    "documento_identidad_tercero"         => "VARCHAR(12) NOT NULL COMMENT 'Número del documento de identidad'",
    "codigo_tipo_comprobante"             => "SMALLINT(2) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo del tipo de comprobante'",
    "numero_comprobante"                  => "VARCHAR(20) NOT NULL COMMENT 'Número de comprobante contable'",
    "codigo_tipo_documento"               => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id tabla consecutivo de documentos'",
    "consecutivo_documento"               => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo del documento'",
    "fecha_contabilizacion"               => "DATE NOT NULL COMMENT 'Fecha en que se contabiliza la transaccion'",
    ////////////////////////////////////////////////////////
    "consecutivo"                         => "INT(9) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo interno para la base de datos'",
    "codigo_sucursal_contabiliza"         => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la sucursal donse se contabiliza el movimiento'",
    "codigo_plan_contable"                => "VARCHAR(15) NOT NULL COMMENT 'Codigo de la cuenta contable segun el PUC'",
    /////////////LLAVE DEL AUXILIAR CONTABLE////////////////
    "codigo_empresa_auxiliar"             => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa para la llave de auxiliares'",
    "codigo_anexo_contable"               => "VARCHAR(3) NOT NULL COMMENT 'Codigo del anexo para la llave de auxiliares'",
    "codigo_auxiliar_contable"            => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código donde se acumulara la información'",
    ////////////////////////////////////////////////////////
    "documento_identidad_tercero_saldo"   => "VARCHAR(12) NOT NULL COMMENT 'Número del documento de identidad del tercero que afecta la cuenta si aplica'",
    "sentido"                             => "ENUM('D','C') COMMENT 'Sentido del movimiento contable: D->Debito, C->Credito'",
    "valor"                               => "INT(9) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Valor contable del movimiento'",  
    "valor_base1"                         => "INT(9) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Valor con el que se liquida la transaccion para las cuentas del iva y retefuente'",
    "valor_base2"                         => "INT(9) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Valor con el que se liquida la transaccion para las cuentas de reteiva y reteica'",
    "codigo_tipo_documento_soporte"       => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL DEFAULT '0' COMMENT 'Consecutivo interno para la base de datos del tipo de documento de soporte de la transaccion'",
    "numero_documento_soporte"            => "VARCHAR(15) DEFAULT NULL COMMENT 'Numero de documento de soporte con el que se realiza la transaccion'",
    "codigo_tipo_documento_bancario"      => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL DEFAULT '0' COMMENT 'Consecutivo interno para la base de datos del tipo de documento bancario de la transaccion, si aplica'",
    "numero_documento_bancario"           => "VARCHAR(15) DEFAULT NULL COMMENT 'Numero de documento bancario con el que se realiza la transaccion, si aplica'",
    "documento_identidad_tercero_fiador1" => "VARCHAR(12) NOT NULL COMMENT 'Número del documento de identidad del tercero1 si aplica'",
    "documento_identidad_tercero_fiador2" => "VARCHAR(12) NOT NULL COMMENT 'Número del documento de identidad del tercero2 si aplica'",
    //////////////LLAVE DEL CONSECUTIVO CHEQUE//////////////
    "codigo_sucursal_cheque"              => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la sucursal a la que pertenece la cuenta'",
    "codigo_tipo_documento_cheque"        => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo del tipo de documento'",
    "codigo_banco_cheque"                 => "SMALLINT(2) UNSIGNED ZEROFILL NOT NULL COMMENT 'id de la tabla bancos'",
    "numero_cheque"                       => "VARCHAR(30) NOT NULL COMMENT 'Numero de la cuenta'",
    "consecutivo_cheque"                  => "INT(9) UNSIGNED ZEROFILL NOT NULL COMMENT 'Numero de cheque'"
);

$tablas["saldos_items_movimientos_contables"] = array(
    /////////////LLAVE DEL ITEM///////////////////////
    "codigo_sucursal"                     => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la sucursal donse se genera el movimiento'",
    "documento_identidad_tercero"         => "VARCHAR(12) NOT NULL COMMENT 'Número del documento de identidad'",
    "codigo_tipo_comprobante"             => "SMALLINT(2) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo del tipo de comprobante'",
    "numero_comprobante"                  => "VARCHAR(20) NOT NULL COMMENT 'Número de comprobante contable'",
    "codigo_tipo_documento"               => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id tabla consecutivo de documentos'",
    "consecutivo_documento"               => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo del documento'",
    "fecha_contabilizacion"               => "DATE NOT NULL COMMENT 'Fecha en que se contabiliza la transaccion'",
    "consecutivo"                         => "INT(9) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo interno para la base de datos'",
    ////////////////////////////////////////
    "fecha_vencimiento"                   => "DATE NOT NULL COMMENT 'Fecha en la que se produce el vencimiento de la cuota'",
    "valor"                               => "INT(9) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Valor de la cuota'"
);

$tablas["abonos_items_movimientos_contables"] = array(
    /////////////LLAVE DEL ITEM///////////////////////
    "codigo_sucursal"                     => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la sucursal donse se genera el movimiento'",
    "documento_identidad_tercero"         => "VARCHAR(12) NOT NULL COMMENT 'Número del documento de identidad'",
    "codigo_tipo_comprobante"             => "SMALLINT(2) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo del tipo de comprobante'",
    "numero_comprobante"                  => "VARCHAR(20) NOT NULL COMMENT 'Número de comprobante contable'",
    "codigo_tipo_documento"               => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id tabla consecutivo de documentos'",
    "consecutivo_documento"               => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo del documento'",
    "fecha_contabilizacion"               => "DATE NOT NULL COMMENT 'Fecha en que se contabiliza la transaccion'",
    "consecutivo_item"                    => "INT(9) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo interno para la base de datos'",
    /////////////LLAVE DEL SALDO//////////////////////
    "codigo_sucursal_saldo"               => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la sucursal donse se genera el movimiento'",
    "documento_identidad_tercero_saldo"   => "VARCHAR(12) NOT NULL COMMENT 'Número del documento de identidad'",
    "codigo_tipo_comprobante_saldo"       => "SMALLINT(2) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo del tipo de comprobante'",
    "numero_comprobante_saldo"            => "VARCHAR(20) NOT NULL COMMENT 'Número de comprobante contable'",
    "codigo_tipo_documento_saldo"         => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id tabla consecutivo de documentos'",
    "consecutivo_documento_saldo"         => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo del documento'",
    "fecha_contabilizacion_saldo"         => "DATE NOT NULL COMMENT 'Fecha en que se contabiliza la transaccion'",
    "consecutivo_saldo"                   => "INT(9) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo interno para la base de datos'",
    "fecha_vencimiento_saldo"             => "DATE NOT NULL COMMENT 'Fecha en la que se produce el vencimiento de la cuota'",
    //////////////////////////////////////////////////
    "consecutivo"                         => "INT(9) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo de abonos por saldo'",
    "fecha_pago_abono"                    => "DATE NOT NULL COMMENT 'Fecha en la que se produce el pago de la cuota'",
    "valor"                               => "INT(9) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Valor de la cuota'"
);

$tablas["consecutivo_cheques"] = array(
    "codigo_sucursal"                   => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la sucursal a la que pertenece la cuenta'",
    "codigo_tipo_documento"             => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo del tipo de documento'",
    "codigo_banco"                      => "SMALLINT(2) UNSIGNED ZEROFILL NOT NULL COMMENT 'id de la tabla bancos'",
    "numero"                            => "VARCHAR(30) NOT NULL COMMENT 'Numero de la cuenta'",
    "consecutivo"                       => "INT(9) UNSIGNED ZEROFILL NOT NULL COMMENT 'Numero de cheque'",
/////////////LLAVE DE CUENTAS BANCARIAS//////////
    "codigo_sucursal_cuenta"            => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la sucursal a la que pertenece la cuenta'",
    "codigo_tipo_documento_cuenta"      => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo del tipo de documento'",
    "codigo_sucursal_banco"             => "SMALLINT(2) UNSIGNED ZEROFILL NOT NULL COMMENT 'codigo de la tabla bancos'",
    "codigo_iso_cuenta"                 => "VARCHAR(2) NOT NULL COMMENT 'Llave principal de la tabla paises'",
    "codigo_dane_departamento_cuenta"   => "VARCHAR(2) NOT NULL COMMENT 'Código DANE'",
    "codigo_dane_municipio_cuenta"      => "VARCHAR(3) NOT NULL COMMENT 'Código DANE'",
    "codigo_banco_cuenta"               => "SMALLINT(2) UNSIGNED ZEROFILL NOT NULL COMMENT 'id de la tabla bancos'",
    "numero_cuenta"                     => "VARCHAR(30) NOT NULL COMMENT 'Numero de la cuenta'",
    /////////LLAVE DEL ITEM DEL MOVIMIENTO///////////
    "id_tabla"                          => "SMALLINT(5) UNSIGNED NOT NULL COMMENT 'id de la tabla que genera el cheque'",
    "llave_tabla"                       => "VARCHAR(500) NOT NULL DEFAULT '' COMMENT 'Llave del registro de la tabla que genero el cheque'",
    /////////////////////////////////////////////////
    
);

//// Definicion de llaves primarias ////
$llavesPrimarias["movimientos_contables"]              = "codigo_sucursal,documento_identidad_tercero,codigo_tipo_comprobante,numero_comprobante,codigo_tipo_documento,consecutivo_documento,fecha_contabilizacion";
$llavesPrimarias["items_movimientos_contables"]        = "codigo_sucursal,documento_identidad_tercero,codigo_tipo_comprobante,numero_comprobante,codigo_tipo_documento,consecutivo_documento,fecha_contabilizacion,consecutivo";
$llavesPrimarias["saldos_items_movimientos_contables"] = "codigo_sucursal,documento_identidad_tercero,codigo_tipo_comprobante,numero_comprobante,codigo_tipo_documento,consecutivo_documento,fecha_contabilizacion,consecutivo,fecha_vencimiento";
$llavesPrimarias["abonos_items_movimientos_contables"] = "codigo_sucursal_saldo,documento_identidad_tercero_saldo,codigo_tipo_comprobante_saldo,numero_comprobante_saldo,codigo_tipo_documento_saldo,consecutivo_documento_saldo,fecha_contabilizacion_saldo,consecutivo_saldo,fecha_vencimiento_saldo,consecutivo";
$llavesPrimarias["consecutivo_cheques"]                = "codigo_sucursal,codigo_tipo_documento,codigo_banco,numero,consecutivo";

// Definicion de llaves Foraneas
$llavesForaneas["movimientos_contables"] = array(
    array(
        // Nombre de la llave foranea
        "movimientos_contables_usuario",
        // Nombre del campo en la tabla actual
        "codigo_usuario_genera",
        // Nombre de la tabla relacionada
        "usuarios",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "movimientos_contables_sucursal_genera",
        // Nombre del campo en la tabla actual
        "codigo_sucursal",
        // Nombre de la tabla relacionada
        "sucursales",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "movimientos_contables_comprobante",
        // Nombre del campo en la tabla actual
        "codigo_tipo_comprobante",
        // Nombre de la tabla relacionada
        "tipos_comprobantes",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "movimientos_contables_tercero",
        // Nombre del campo en la tabla actual
        "documento_identidad_tercero",
        // Nombre de la tabla relacionada
        "terceros",
        // Nombre del campo de la tabla relacionada
        "documento_identidad"
    ),
    array(
        // Nombre de la llave foranea
        "movimientos_contables_consecutivo_documentos",
        // Nombre del campo en la tabla actual
        "codigo_sucursal,codigo_tipo_documento,documento_identidad_tercero,fecha_contabilizacion,consecutivo_documento",
        // Nombre de la tabla relacionada
        "consecutivo_documentos",
        // Nombre del campo de la tabla relacionada
        "codigo_sucursal,codigo_tipo_documento,documento_identidad_tercero,fecha_registro,consecutivo"
    )
);


// Definicion de llaves Foraneas
$llavesForaneas["items_movimientos_contables"] = array(
    array(
        // Nombre de la llave foranea
        "items_movimientos_contables_movimiento",
        // Nombre del campo en la tabla actual
        "codigo_sucursal,documento_identidad_tercero,codigo_tipo_comprobante,numero_comprobante,codigo_tipo_documento,consecutivo_documento,fecha_contabilizacion",
        // Nombre de la tabla relacionada
        "movimientos_contables",
        // Nombre del campo de la tabla relacionada
        "codigo_sucursal,documento_identidad_tercero,codigo_tipo_comprobante,numero_comprobante,codigo_tipo_documento,consecutivo_documento,fecha_contabilizacion"
    ),
    array(
        // Nombre de la llave foranea
        "items_movimientos_contables_contabiliza",
        // Nombre del campo en la tabla actual
        "codigo_sucursal_contabiliza",
        // Nombre de la tabla relacionada
        "sucursales",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "items_movimientos_contables_cuenta",
        // Nombre del campo en la tabla actual
        "codigo_plan_contable",
        // Nombre de la tabla relacionada
        "plan_contable",
        // Nombre del campo de la tabla relacionada
        "codigo_contable"
    ),
    array(
        // Nombre de la llave foranea
        "items_movimientos_contables_auxiliar",
        // Nombre del campo en la tabla actual
        "codigo_empresa_auxiliar,codigo_anexo_contable,codigo_auxiliar_contable",
        // Nombre de la tabla relacionada
        "auxiliares_contables",
        // Nombre del campo de la tabla relacionada
        "codigo_empresa,codigo_anexo_contable,codigo"
    ),
    array(
        // Nombre de la llave foranea
        "items_movimientos_contables_tercero_cuenta",
        // Nombre del campo en la tabla actual
        "documento_identidad_tercero",
        // Nombre de la tabla relacionada
        "terceros",
        // Nombre del campo de la tabla relacionada
        "documento_identidad"
    ),
    array(
        // Nombre de la llave foranea
        "items_movimientos_contables_documento_soporte",
        // Nombre del campo en la tabla actual
        "codigo_tipo_documento_soporte",
        // Nombre de la tabla relacionada
        "tipos_documentos",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "items_movimientos_contables_documento_bancario",
        // Nombre del campo en la tabla actual
        "codigo_tipo_documento_bancario",
        // Nombre de la tabla relacionada
        "tipos_documentos_bancarios",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "items_movimientos_contables_fiador1",
        // Nombre del campo en la tabla actual
        "documento_identidad_tercero_fiador1",
        // Nombre de la tabla relacionada
        "terceros",
        // Nombre del campo de la tabla relacionada
        "documento_identidad"
    ),
    array(
        // Nombre de la llave foranea
        "items_movimientos_contables_fiador2",
        // Nombre del campo en la tabla actual
        "documento_identidad_tercero_fiador2",
        // Nombre de la tabla relacionada
        "terceros",
        // Nombre del campo de la tabla relacionada
        "documento_identidad"
    ),
    array(
        // Nombre de la llave foranea
        "items_movimientos_contables_consecutivo_cheques",
        // Nombre del campo en la tabla actual
        "codigo_sucursal_cheque,codigo_tipo_documento_cheque,codigo_banco_cheque,numero_cheque,consecutivo_cheque",
        // Nombre de la tabla relacionada
        "consecutivo_cheques",
        // Nombre del campo de la tabla relacionada
        "codigo_sucursal,codigo_tipo_documento,codigo_banco,numero,consecutivo"
    ),
);

$llavesForaneas["saldos_items_movimientos_contables"] = array(
    array(
        // Nombre de la llave foranea
        "saldos_items_movimientos_contables_item",
        // Nombre del campo en la tabla actual
        "codigo_sucursal,documento_identidad_tercero,codigo_tipo_comprobante,numero_comprobante,codigo_tipo_documento,consecutivo_documento,fecha_contabilizacion,consecutivo",
        // Nombre de la tabla relacionada
        "items_movimientos_contables",
        // Nombre del campo de la tabla relacionada
        "codigo_sucursal,documento_identidad_tercero,codigo_tipo_comprobante,numero_comprobante,codigo_tipo_documento,consecutivo_documento,fecha_contabilizacion,consecutivo"
    )
);

$llavesForaneas["abonos_items_movimientos_contables"] = array(
    array(
        // Nombre de la llave foranea
        "abonos_items_movimientos_contables_item",
        // Nombre del campo en la tabla actual
        "codigo_sucursal,documento_identidad_tercero,codigo_tipo_comprobante,numero_comprobante,codigo_tipo_documento,consecutivo_documento,fecha_contabilizacion,consecutivo_item",
        // Nombre de la tabla relacionada
        "items_movimientos_contables",
        // Nombre del campo de la tabla relacionada
        "codigo_sucursal,documento_identidad_tercero,codigo_tipo_comprobante,numero_comprobante,codigo_tipo_documento,consecutivo_documento,fecha_contabilizacion,consecutivo"
    ),
    array(
        // Nombre de la llave foranea
        "abonos_items_movimientos_contables_saldo",
        // Nombre del campo en la tabla actual
        "codigo_sucursal_saldo,documento_identidad_tercero_saldo,codigo_tipo_comprobante_saldo,numero_comprobante_saldo,codigo_tipo_documento_saldo,consecutivo_documento_saldo,fecha_contabilizacion_saldo,consecutivo_saldo,fecha_vencimiento_saldo",
        // Nombre de la tabla relacionada
        "saldos_items_movimientos_contables",
        // Nombre del campo de la tabla relacionada
        "codigo_sucursal,documento_identidad_tercero,codigo_tipo_comprobante,numero_comprobante,codigo_tipo_documento,consecutivo_documento,fecha_contabilizacion,consecutivo,fecha_vencimiento"
    )
);

$llavesForaneas["consecutivo_cheques"] = array(
    array(
        // Nombre de la llave foranea
        "consecutivo_cheques_cuentas_bancarias",
        // Nombre del campo en la tabla actual
        "codigo_sucursal_cuenta,codigo_tipo_documento_cuenta,codigo_sucursal_banco,codigo_iso_cuenta,codigo_dane_departamento_cuenta,codigo_dane_municipio_cuenta,codigo_banco_cuenta,numero_cuenta",
        // Nombre de la tabla relacionada
        "cuentas_bancarias",
        // Nombre del campo de la tabla relacionada
        "codigo_sucursal,codigo_tipo_documento,codigo_sucursal_banco,codigo_iso,codigo_dane_departamento,codigo_dane_municipio,codigo_banco,numero"
    ),
    array(
        // Nombre de la llave
        "consecutivo_cheques_tablas",
        // Nombre del campo clave de la tabla local
        "id_tabla",
        // Nombre de la tabla relacionada
        "tablas",
        // Nombre del campo clave en la tabla relacionada
        "id"
    ),
);

$registros["consecutivo_cheques"] = array(
    array(
        "codigo_sucursal"                 => '0',
        "codigo_tipo_documento"           => '0',
        "codigo_banco"                    => '0',
        "numero"                          => '',
        "consecutivo"                     => '0',
        "codigo_sucursal_cuenta"          => '0',
        "codigo_tipo_documento_cuenta"    => '0',
        "codigo_sucursal_banco"           => '0',
        "codigo_iso_cuenta"               => '',
        "codigo_dane_departamento_cuenta" => '',
        "codigo_dane_municipio_cuenta"    => '',
        "codigo_banco_cuenta"             => '0',
        "numero_cuenta"                   => '',
        "id_tabla"                        => '00000',
        "llave_tabla"                     => ''
    )
);


/*** Insercion de datos iniciales***/
$registros["componentes"] = array(
    array(
        "id"        => "GESTMOCO",
        "padre"     => "SUBMOPCO",
        "id_modulo" => "CONTABILIDAD",
        "visible"   => "1",
        "orden"     => "8000",
        "carpeta"   => "movimientos_contables",
        "archivo"   => "menu"
    ),
    array(
        "id"        => "ADICMOCO",
        "padre"     => "GESTMOCO",
        "id_modulo" => "CONTABILIDAD",
        "visible"   => "0",
        "orden"     => "0025",
        "carpeta"   => "movimientos_contables",
        "archivo"   => "adicionar"
    ),
    array(
        "id"        => "CONSMOCO",
        "padre"     => "GESTMOCO",
        "id_modulo" => "CONTABILIDAD",
        "visible"   => "0",
        "orden"     => "0010",
        "carpeta"   => "movimientos_contables",
        "archivo"   => "consultar"
    ),
    array(
        "id"        => "MODIMOCO",
        "padre"     => "GESTMOCO",
        "id_modulo" => "CONTABILIDAD",
        "visible"   => "0",
        "orden"     => "0015",
        "carpeta"   => "movimientos_contables",
        "archivo"   => "modificar"
    ),
    array(
        "id"        => "ANULMOCO",
        "padre"     => "GESTMOCO",
        "id_modulo" => "CONTABILIDAD",
        "visible"   => "0",
        "orden"     => "0020",
        "carpeta"   => "movimientos_contables",
        "archivo"   => "anular"
    ),
    array(
        "id"        => "REPOMOCO",
        "padre"     => "GESTMOCO",
        "id_modulo" => "CONTABILIDAD",
        "visible"   => "0",
        "orden"     => "0005",
        "carpeta"   => "movimientos_contables",
        "archivo"   => "reportes"
    ),
    array(
        "id"                => "REPODIMD",
        "padre"             => "SUBMMOVI",
        "id_modulo"         => "CONTABILIDAD",
        "visible"           => "1",
        "orden"             => "0010",
        "carpeta"           => "movimientos_contables",
        "archivo"           => "diario_documento",
        "requiere_item"     => "0",
        "tabla_principal"   => "",
        "tipo_enlace"       => "2"
    ), array(
        "id"                => "REPODIMC",
        "padre"             => "SUBMMOVI",
        "id_modulo"         => "CONTABILIDAD",
        "visible"           => "1",
        "orden"             => "0020",
        "carpeta"           => "movimientos_contables",
        "archivo"           => "diario_comprobante",
        "requiere_item"     => "0",
        "tabla_principal"   => "",
        "tipo_enlace"       => "2"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_movimientos_contables AS
        SELECT CONCAT (b.codigo_sucursal,'|',b.documento_identidad_tercero,'|',b.codigo_tipo_comprobante,'|',b.numero_comprobante,'|',b.codigo_tipo_documento,'|',b.consecutivo_documento,'|',b.fecha_contabilizacion) AS id,
            suc.codigo AS id_sucursal,
            c.documento_identidad AS id_tercero,
            b.codigo_tipo_comprobante AS id_tipo_comprobante,
            b.codigo_tipo_documento AS id_tipo_documento,
            b.observaciones AS id_observaciones,
            IF(CONCAT(IF(c.primer_nombre IS NOT NULL, c.primer_nombre, ''),' ',
            IF(c.segundo_nombre IS NOT NULL, c.segundo_nombre, ''),' ',
            IF(c.primer_apellido IS NOT NULL, c.primer_apellido, ''),' ',
            IF(c.segundo_apellido IS NOT NULL, c.segundo_apellido, ''),' ',
            IF(c.razon_social IS NOT NULL, c.razon_social, ''))!='',CONCAT(IF(c.primer_nombre IS NOT NULL, c.primer_nombre, ''),' ',
            IF(c.segundo_nombre IS NOT NULL, c.segundo_nombre, ''),' ',
            IF(c.primer_apellido IS NOT NULL, c.primer_apellido, ''),' ',
            IF(c.segundo_apellido IS NOT NULL, c.segundo_apellido, ''),' ',
            IF(c.razon_social IS NOT NULL, c.razon_social, '')),\"No aplica\") AS TERCERO,
            d.descripcion AS TIPO_DOCUMENTO,
            b.fecha_contabilizacion AS FECHA,
            a.consecutivo AS CONSECUTIVO_DOCUMENTO,
            suc.nombre AS SUCURSAL,
        IF(((SELECT SUM(valor) FROM job_items_movimientos_contables AS imc
        WHERE b.codigo_sucursal=imc.codigo_sucursal AND b.documento_identidad_tercero=imc.documento_identidad_tercero AND
        b.codigo_tipo_comprobante=imc.codigo_tipo_comprobante AND b.numero_comprobante=imc.numero_comprobante AND
        b.codigo_tipo_documento=imc.codigo_tipo_documento AND b.consecutivo_documento=imc.consecutivo_documento AND
        b.fecha_contabilizacion=imc.fecha_contabilizacion AND imc.sentido='1')
        -(SELECT SUM(valor) FROM job_items_movimientos_contables AS imc
        WHERE b.codigo_sucursal=imc.codigo_sucursal AND b.documento_identidad_tercero=imc.documento_identidad_tercero AND
        b.codigo_tipo_comprobante=imc.codigo_tipo_comprobante AND b.numero_comprobante=imc.numero_comprobante AND
        b.codigo_tipo_documento=imc.codigo_tipo_documento AND b.consecutivo_documento=imc.consecutivo_documento AND
        b.fecha_contabilizacion=imc.fecha_contabilizacion AND imc.sentido='2'))=0,
        FORMAT((SELECT SUM(valor) FROM job_items_movimientos_contables AS imc
        WHERE b.codigo_sucursal=imc.codigo_sucursal AND b.documento_identidad_tercero=imc.documento_identidad_tercero AND
        b.codigo_tipo_comprobante=imc.codigo_tipo_comprobante AND b.numero_comprobante=imc.numero_comprobante AND
        b.codigo_tipo_documento=imc.codigo_tipo_documento AND b.consecutivo_documento=imc.consecutivo_documento AND
        b.fecha_contabilizacion=imc.fecha_contabilizacion AND imc.sentido='1'),2),\"ESTADO_1\") AS CUADRE
        FROM    job_consecutivo_documentos AS a,job_movimientos_contables AS b,job_terceros AS c,job_tipos_documentos AS d,job_tablas AS e,
                job_sucursales AS suc
        WHERE   a.id_tabla = e.id AND        
                a.codigo_tipo_documento = d.codigo AND
                a.codigo_tipo_documento = b.codigo_tipo_documento AND
                a.codigo_sucursal = b.codigo_sucursal AND
                a.consecutivo = b.consecutivo_documento AND
                b.documento_identidad_tercero = c.documento_identidad AND
                b.codigo_sucursal = suc.codigo;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_movimientos_contables AS
        SELECT CONCAT (b.codigo_sucursal,'|',b.documento_identidad_tercero,'|',b.codigo_tipo_comprobante,'|',b.numero_comprobante,'|',b.codigo_tipo_documento,'|',b.consecutivo_documento,'|',b.fecha_contabilizacion) AS id,
        CONCAT(IF(c.primer_nombre IS NOT NULL, c.primer_nombre, ''),' ',
        IF(c.segundo_nombre IS NOT NULL, c.segundo_nombre, ''),' ',
        IF(c.primer_apellido IS NOT NULL, c.primer_apellido, ''),' ',
        IF(c.segundo_apellido IS NOT NULL, c.segundo_apellido, ''),' ',
        IF(c.razon_social IS NOT NULL, c.razon_social, '')) AS TERCERO,
        d.descripcion AS TIPO_DOCUMENTO,
        a.consecutivo AS CONSECUTIVO_DOCUMENTO
        FROM    job_consecutivo_documentos AS a,
                job_movimientos_contables AS b,
                job_terceros AS c,
                job_tipos_documentos AS d,
                job_tablas AS e
        WHERE   a.id_tabla = e.id AND
                a.codigo_tipo_documento = d.codigo AND
                b.codigo_tipo_documento = d.codigo AND
                a.codigo_tipo_documento = b.codigo_tipo_documento AND
                a.codigo_sucursal = b.codigo_sucursal AND
                a.consecutivo = b.consecutivo_documento AND
                b.documento_identidad_tercero = c.documento_identidad;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_saldos_movimientos_contables AS
        SELECT  imc.documento_identidad_tercero_saldo AS id_tercero,
                imc.codigo_plan_contable AS id_cuenta,
                CONCAT (cd.codigo_sucursal,'|',cd.codigo_tipo_documento,'|',cd.fecha_registro,'|',cd.consecutivo) AS id_consecutivo,
                cd.consecutivo AS consecutivo,
                cd.codigo_tipo_documento AS id_documento,
                CONCAT (imc.codigo_sucursal,'|',imc.documento_identidad_tercero,'|',imc.codigo_tipo_comprobante,'|',imc.numero_comprobante,'|',imc.codigo_tipo_documento,'|',imc.consecutivo_documento,'|',imc.fecha_contabilizacion,'|',imc.consecutivo) AS id_item_movimiento,
                CONCAT (simc.codigo_sucursal,'|',simc.documento_identidad_tercero,'|',simc.codigo_tipo_comprobante,'|',simc.numero_comprobante,'|',simc.codigo_tipo_documento,'|',simc.consecutivo_documento,'|',simc.fecha_contabilizacion,'|',simc.consecutivo,'|',simc.fecha_vencimiento) AS id_saldo,
                simc.valor AS saldo,
                simc.fecha_vencimiento AS fecha_vencimiento
        FROM    job_movimientos_contables AS mc,
                job_items_movimientos_contables AS imc,
                job_saldos_items_movimientos_contables AS simc,
                job_tablas AS tb,
                job_consecutivo_documentos AS cd
        WHERE   mc.codigo_sucursal=imc.codigo_sucursal AND mc.documento_identidad_tercero=imc.documento_identidad_tercero AND
                mc.codigo_tipo_comprobante=imc.codigo_tipo_comprobante AND mc.numero_comprobante=imc.numero_comprobante AND
                mc.codigo_tipo_documento=imc.codigo_tipo_documento AND mc.consecutivo_documento=imc.consecutivo_documento AND
                mc.fecha_contabilizacion=imc.fecha_contabilizacion AND imc.codigo_sucursal=simc.codigo_sucursal AND
                imc.documento_identidad_tercero=simc.documento_identidad_tercero AND imc.codigo_tipo_comprobante=simc.codigo_tipo_comprobante AND
                imc.numero_comprobante=simc.numero_comprobante AND imc.codigo_tipo_documento=simc.codigo_tipo_documento AND
                imc.consecutivo_documento=simc.consecutivo_documento AND imc.fecha_contabilizacion=simc.fecha_contabilizacion AND
                imc.consecutivo=simc.consecutivo AND mc.estado != '2' AND
                cd.codigo_tipo_documento = mc.codigo_tipo_documento AND
                cd.codigo_sucursal = mc.codigo_sucursal AND
                cd.consecutivo = mc.consecutivo_documento AND
                cd.id_tabla = tb.id AND tb.nombre_tabla = 'movimientos_contables';"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_abonos_movimientos_contables AS
        SELECT  CONCAT (aimc.codigo_sucursal_saldo,'|',aimc.documento_identidad_tercero_saldo,'|',aimc.codigo_tipo_comprobante_saldo,'|',aimc.numero_comprobante_saldo,'|',aimc.codigo_tipo_documento_saldo,'|',aimc.consecutivo_documento_saldo,'|',aimc.fecha_contabilizacion_saldo,'|',aimc.consecutivo_saldo,'|',aimc.fecha_vencimiento_saldo,'|',aimc.consecutivo) AS id,
                aimc.valor AS valor,
                aimc.fecha_pago_abono AS fecha_pago,
                aimc.codigo_tipo_documento AS codigo_tipo_documento,
                aimc.consecutivo_documento AS consecutivo_documento,
                CONCAT (aimc.codigo_sucursal_saldo,'|',aimc.documento_identidad_tercero_saldo,'|',aimc.codigo_tipo_comprobante_saldo,'|',aimc.numero_comprobante_saldo,'|',aimc.codigo_tipo_documento_saldo,'|',aimc.consecutivo_documento_saldo,'|',aimc.fecha_contabilizacion_saldo,'|',aimc.consecutivo_saldo,'|',aimc.fecha_vencimiento_saldo) AS id_saldo,
                CONCAT (aimc.codigo_sucursal,'|',aimc.documento_identidad_tercero,'|',aimc.codigo_tipo_comprobante,'|',aimc.numero_comprobante,'|',aimc.codigo_tipo_documento,'|',aimc.consecutivo_documento,'|',aimc.fecha_contabilizacion,'|',aimc.consecutivo_item) AS id_item_movimiento
        FROM    job_abonos_items_movimientos_contables AS aimc,
                job_items_movimientos_contables AS imc,
                job_movimientos_contables AS mc
        WHERE   imc.codigo_sucursal=aimc.codigo_sucursal AND imc.documento_identidad_tercero=aimc.documento_identidad_tercero AND
                imc.codigo_tipo_comprobante=aimc.codigo_tipo_comprobante AND imc.numero_comprobante=aimc.numero_comprobante AND
                imc.codigo_tipo_documento=aimc.codigo_tipo_documento AND imc.consecutivo_documento=aimc.consecutivo_documento AND
                imc.fecha_contabilizacion=aimc.fecha_contabilizacion AND imc.consecutivo=aimc.consecutivo_item AND
                mc.codigo_sucursal=imc.codigo_sucursal AND mc.documento_identidad_tercero=imc.documento_identidad_tercero AND
                mc.codigo_tipo_comprobante=imc.codigo_tipo_comprobante AND mc.numero_comprobante=imc.numero_comprobante AND
                mc.codigo_tipo_documento=imc.codigo_tipo_documento AND mc.consecutivo_documento=imc.consecutivo_documento AND
                mc.fecha_contabilizacion = mc.fecha_contabilizacion AND mc.estado != '2';"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM=MERGE VIEW job_totalizador_saldos_movimientos_contables AS
        SELECT id_tercero,
               id_cuenta,
               id_consecutivo,
               consecutivo,
               id_documento,
               job_buscador_saldos_movimientos_contables.id_saldo,
               saldo,
               job_buscador_abonos_movimientos_contables.id AS id_abono,
               job_buscador_abonos_movimientos_contables.valor AS abono
        FROM
            (job_buscador_saldos_movimientos_contables
            LEFT JOIN
            job_buscador_abonos_movimientos_contables
            ON job_buscador_saldos_movimientos_contables.id_saldo = job_buscador_abonos_movimientos_contables.id_saldo);"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM=MERGE VIEW job_seleccion_items_movimientos_contables AS
        SELECT  CONCAT (mc.codigo_sucursal,'|',mc.documento_identidad_tercero,'|',mc.codigo_tipo_comprobante,'|',mc.numero_comprobante,'|',mc.codigo_tipo_documento,'|',mc.consecutivo_documento,'|',mc.fecha_contabilizacion) AS id_movimiento,
                CONCAT (imc.codigo_sucursal,'|',imc.documento_identidad_tercero,'|',imc.codigo_tipo_comprobante,'|',imc.numero_comprobante,'|',imc.codigo_tipo_documento,'|',imc.consecutivo_documento,'|',imc.fecha_contabilizacion,'|',imc.consecutivo) AS id,
                imc.codigo_plan_contable AS codigo_plan_contable,
                imc.sentido AS sentido
        FROM    job_items_movimientos_contables AS imc, job_movimientos_contables AS mc
        WHERE   mc.codigo_sucursal=imc.codigo_sucursal AND mc.documento_identidad_tercero=imc.documento_identidad_tercero AND
                mc.codigo_tipo_comprobante=imc.codigo_tipo_comprobante AND mc.numero_comprobante=imc.numero_comprobante AND
                mc.codigo_tipo_documento=imc.codigo_tipo_documento AND mc.consecutivo_documento=imc.consecutivo_documento AND
                mc.fecha_contabilizacion=imc.fecha_contabilizacion AND mc.estado='1';"
    )
);

?>

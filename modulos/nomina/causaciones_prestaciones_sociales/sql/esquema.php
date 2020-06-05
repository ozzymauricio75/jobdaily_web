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

$borrarSiempre   = false;

$tablas["causaciones_prestaciones_sociales"] = array(
    "concepto"                          => "ENUM('1','2','3','4') NOT NULL  COMMENT '1->prima 2->vacaciones  3->cesantias  4->intereses/cesantias '",
    /////////fechas de control//////////////////////////////////
    "fecha_generacion"                  => "DATETIME NOT NULL COMMENT 'Fecha en la se genero el retiro'",
    "fecha_liquidacion"                 => "DATE NOT NULL COMMENT 'Fecha en la hasta donde se va a liquidar'",
    "fecha_contabilizacion"             => "DATE NOT NULL COMMENT 'Fecha en la se generara la contabilizacion'",
    ////contrato_sucursal_empleado//////////////////////////////
    "codigo_empresa"                    => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo del almacen donde va a laborar'",
    "documento_identidad_empleado"      => "VARCHAR(12) NOT NULL COMMENT 'Documento de identidad que identifica el empleado en terceras personas'",
    "fecha_ingreso"                     => "DATE NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado'",
    "codigo_sucursal"                   => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo del almacen donde va a laborar'",
    "fecha_ingreso_sucursal"            => "DATE NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado'",
    ///Datos consecutivo documento//////////////////////////////
    "codigo_sucursal_documento"         => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo del almacen donde va a laborar'",
    "codigo_tipo_documento"             => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código asignado por el usuario'",
    "identidad_tercero_documento"       => "VARCHAR(12) NOT NULL COMMENT 'Documento de identidad que identifica el empleado en terceras personas'",
    "fecha_generacion_consecutivo"      => "DATE NOT NULL COMMENT 'Fecha de genracion del consecutico'",
    "consecutivo_documento"             => "INT(9) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo del documento'",
    //////Tipo de comprobante///////////////////////////////////
    "codigo_tipo_comprobante"           => "SMALLINT(2) UNSIGNED ZEROFILL NOT NULL COMMENT 'tipo de  comprobante seleccionado por el usuario'",
    "numero_comprobante"                => "VARCHAR(20) NOT NULL COMMENT 'Número de comprobante contable'",
    /////Datos Auxiliar contable///////////////////////////////
    "codigo_empresa_auxiliar"           => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa para la llave de auxiliares'",
    "codigo_anexo_contable"             => "VARCHAR(3) NOT NULL COMMENT 'Código del anexo que permite dividir las cuentas'",
    "codigo_auxiliar_contable"          => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código donde se acumulara la información'",
    ///////////Informacion Prestaciones Sociales//////////////
    "fecha_inicio"                      => "DATE NOT NULL COMMENT 'Fecha en donde se empieza a calcular el valor a pagar'",
    "fecha_final"                       => "DATE NOT NULL COMMENT 'Fecha en donde se empieza a calcular el valor a pagar'",
    "dias_liquidados"                   => "SMALLINT(3) NOT NULL COMMENT 'Dias laborados en el periodo'",
    "salario_base"                      => "DECIMAL(11,2) NOT NULL COMMENT 'Valor con el que se va a liquidar el movimiento'",
    "periodo_pago"                      => "SMALLINT(3) NOT NULL COMMENT 'Dias laborados en el periodo'",
    ///////Informacion Contable///////////////////////////////
    "codigo_transaccion_contable"       => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'codigo de la tabla de transacciones contables'",
    "codigo_plan_contable"              => "VARCHAR(15) NOT NULL COMMENT 'Codigo de la cuenta contable segun el PUC'",
    "sentido"                           => "ENUM('D','C') NOT NULL COMMENT 'D->Débito C->Crédito'",
    "valor_movimiento"                  => "DECIMAL(11,2) NOT NULL COMMENT 'Valor diario del registro'",
    //////Informacion Adicional///////////////////////////////
    "codigo_usuario_registra"           => "SMALLINT(4) UNSIGNED ZEROFILL  NOT NULL COMMENT 'Codigo del Usuario que genera el registro'",
    "codigo_usuario_modifica"           => "SMALLINT(4) UNSIGNED ZEROFILL  NOT NULL COMMENT 'Codigo del ultimo Usuario que modifica el registro'",
  );

$llavesPrimarias["causaciones_prestaciones_sociales"]  = "codigo_empresa,documento_identidad_empleado,fecha_liquidacion,codigo_transaccion_contable";

$llavesForaneas["causaciones_prestaciones_sociales"] = array(
        array(
            //  Nombre de la llave foranea
            "causaciones_prestaciones_sociales_sucursal_contrato",
            //  Nombre del campo en la tabla actual
            "codigo_empresa,documento_identidad_empleado,fecha_ingreso,codigo_sucursal,fecha_ingreso_sucursal",
            //  Nombre de la tabla relacionada
            "sucursal_contrato_empleados",
            //  Nombre del campo de la tabla relacionada
            "codigo_empresa,documento_identidad_empleado,fecha_ingreso,codigo_sucursal,fecha_ingreso_sucursal"
        ),
        array(
            // Nombre de la llave foranea
            "causaciones_prestaciones_sociales_tipo_documento",
            // Nombre del campo en la tabla actual
            "codigo_tipo_documento",
            // Nombre de la tabla relacionada
            "tipos_documentos",
            // Nombre del campo de la tabla relacionada
            "codigo"
        ),
        array(
            // Nombre de la llave foranea
            "causaciones_prestaciones_sociales_auxiliar_contable",
            // Nombre del campo en la tabla actual
            "codigo_empresa_auxiliar,codigo_anexo_contable,codigo_auxiliar_contable",
            // Nombre de la tabla relacionada
            "auxiliares_contables",
            // Nombre del campo de la tabla relacionada
            "codigo_empresa,codigo_anexo_contable,codigo"
        ),
        array(
            // Nombre de la llave foranea
            "causaciones_prestaciones_sociales_consecutivo_documentos",
            // Nombre del campo en la tabla actual
            "codigo_sucursal_documento,codigo_tipo_documento,identidad_tercero_documento,fecha_generacion_consecutivo,consecutivo_documento",
            // Nombre de la tabla relacionada
            "consecutivo_documentos",
            // Nombre del campo de la tabla relacionada
            "codigo_sucursal,codigo_tipo_documento,documento_identidad_tercero,fecha_registro,consecutivo"
        ),
        array(
            // Nombre de la llave foranea
            "causaciones_prestaciones_sociales_transaccion",
            // Nombre del campo en la tabla actual
            "codigo_transaccion_contable",
            // Nombre de la tabla relacionada
            "transacciones_contables_empleado",
            // Nombre del campo de la tabla relacionada
            "codigo"
        ),
        array(
            // Nombre de la llave foranea
            "causaciones_prestaciones_sociales_registra",
            // Nombre del campo en la tabla actual
            "codigo_usuario_registra",
            // Nombre de la tabla relacionada
            "usuarios",
            // Nombre del campo de la tabla relacionada
            "codigo"
        ),
        array(
            // Nombre de la llave foranea
            "causaciones_prestaciones_sociales_modifica",
            // Nombre del campo en la tabla actual
            "codigo_usuario_modifica",
            // Nombre de la tabla relacionada
            "usuarios",
            // Nombre del campo de la tabla relacionada
            "codigo"
        ),
        array(
            // Nombre de la llave foranea
            "causaciones_prestaciones_sociales_tipo_comprobante",
            // Nombre del campo en la tabla actual
            "codigo_tipo_comprobante",
            // Nombre de la tabla relacionada
            "tipos_comprobantes",
            // Nombre del campo de la tabla relacionada
            "codigo"
        )
);

$registros["componentes"] = array(

     array(
        "id"            => "GESTCAPS",
        "padre"         => "SUBMPRSO",
        "id_modulo"     => "NOMINA",
        "orden"         => "01",
        "visible"       => "1",
        "carpeta"       => "causaciones_prestaciones_sociales",
        "global"        => "0",
        "archivo"       => "menu",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
   array(
        "id"                => "ADICCAPS",
        "padre"             => "GESTCAPS",
        "id_modulo"         => "NOMINA",
        "visible"           => "0",
        "orden"             => "0005",
        "carpeta"           => "causaciones_prestaciones_sociales",
        "archivo"           => "adicionar",
        "requiere_item"     => "0",
        "tabla_principal"   => "causaciones_prestaciones_sociales",
        "tipo_enlace"       => "1"
    ),
      array(
        "id"                => "CONSCAPS",
        "padre"             => "GESTCAPS",
        "id_modulo"         => "NOMINA",
        "visible"           => "0",
        "orden"             => "0006",
        "carpeta"           => "causaciones_prestaciones_sociales",
        "archivo"           => "consultar",
        "requiere_item"     => "0",
        "tabla_principal"   => "causaciones_prestaciones_sociales",
        "tipo_enlace"       => "1"
    ),
      array(
        "id"                => "ELIMCAPS",
        "padre"             => "GESTCAPS",
        "id_modulo"         => "NOMINA",
        "visible"           => "0",
        "orden"             => "0007",
        "carpeta"           => "causaciones_prestaciones_sociales",
        "archivo"           => "eliminar",
        "requiere_item"     => "0",
        "tabla_principal"   => "causaciones_prestaciones_sociales",
        "tipo_enlace"       => "1"
    ),
    array(
        "id"                => "MODICAPS",
        "padre"             => "GESTCAPS",
        "id_modulo"         => "NOMINA",
        "visible"           => "0",
        "orden"             => "0008",
        "carpeta"           => "causaciones_prestaciones_sociales",
        "archivo"           => "modificar",
        "requiere_item"     => "0",
        "tabla_principal"   => "causaciones_prestaciones_sociales",
        "tipo_enlace"       => "1"
    ),
    array(
        "id"                => "LISTCMPE",
        "padre"             => "GESTCAPS",
        "id_modulo"         => "NOMINA",
        "visible"           => "0",
        "orden"             => "0008",
        "carpeta"           => "causaciones_prestaciones_sociales",
        "archivo"           => "listar_movimientos_empresas",
        "requiere_item"     => "0",
        "tabla_principal"   => "causaciones_prestaciones_sociales",
        "tipo_enlace"       => "1"
    ),
    array(
        "id"                => "LISTCAPE",
        "padre"             => "GESTCAPS",
        "id_modulo"         => "NOMINA",
        "visible"           => "0",
        "orden"             => "0008",
        "carpeta"           => "causaciones_prestaciones_sociales",
        "archivo"           => "listar_movimientos_empleado",
        "requiere_item"     => "0",
        "tabla_principal"   => "causaciones_prestaciones_sociales",
        "tipo_enlace"       => "1"
    )

);

$vistas = array(
    array(
        " CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_causaciones_prestaciones_sociales AS
          SELECT CONCAT(job_causaciones_prestaciones_sociales.codigo_empresa,'|',job_causaciones_prestaciones_sociales.fecha_liquidacion,'|',job_causaciones_prestaciones_sociales.documento_identidad_empleado,'|',job_causaciones_prestaciones_sociales.codigo_transaccion_contable) AS id,
          job_empresas.razon_social AS EMPRESAS,
          job_causaciones_prestaciones_sociales.fecha_liquidacion AS FECHA_LIQUIDACION
          FROM job_causaciones_prestaciones_sociales,job_empresas
          WHERE
          job_causaciones_prestaciones_sociales.codigo_empresa = job_empresas.codigo
          GROUP BY job_causaciones_prestaciones_sociales.codigo_empresa,job_causaciones_prestaciones_sociales.fecha_liquidacion"
     ),
     array(
        " CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_causaciones_prestaciones_sociales AS
          SELECT CONCAT(job_causaciones_prestaciones_sociales.codigo_empresa,'|',job_causaciones_prestaciones_sociales.fecha_liquidacion,'|',job_causaciones_prestaciones_sociales.documento_identidad_empleado,'|',job_causaciones_prestaciones_sociales.codigo_transaccion_contable) AS id,
          job_empresas.razon_social AS EMPRESA,
          job_causaciones_prestaciones_sociales.fecha_liquidacion AS FECHA_LIQUIDACION
          FROM job_causaciones_prestaciones_sociales,job_empresas
          WHERE
          job_causaciones_prestaciones_sociales.codigo_empresa = job_empresas.codigo
          GROUP BY job_causaciones_prestaciones_sociales.codigo_empresa,job_causaciones_prestaciones_sociales.fecha_liquidacion"
     ),
     array(
        " CREATE OR REPLACE ALGORITHM = MERGE VIEW job_agrupar_causaciones_prestaciones_sociales AS
          SELECT CONCAT(job_causaciones_prestaciones_sociales.codigo_empresa,'|',job_causaciones_prestaciones_sociales.fecha_liquidacion,'|',job_causaciones_prestaciones_sociales.documento_identidad_empleado,'|',job_causaciones_prestaciones_sociales.codigo_transaccion_contable) AS id,
          job_causaciones_prestaciones_sociales.codigo_sucursal AS codigo_sucursal,
          job_causaciones_prestaciones_sociales.concepto AS concepto,
          job_causaciones_prestaciones_sociales.codigo_empresa AS codigo_empresa,
          job_causaciones_prestaciones_sociales.valor_movimiento AS valor_movimiento,
          job_causaciones_prestaciones_sociales.documento_identidad_empleado AS documento_identidad_empleado,
          job_causaciones_prestaciones_sociales.fecha_liquidacion AS fecha_liquidacion,
          job_causaciones_prestaciones_sociales.fecha_contabilizacion AS fecha_contabilizacion,
          job_causaciones_prestaciones_sociales.codigo_tipo_documento AS codigo_tipo_documento,
          job_causaciones_prestaciones_sociales.codigo_tipo_comprobante AS codigo_tipo_comprobante,
          job_causaciones_prestaciones_sociales.numero_comprobante AS  numero_comprobante
          FROM job_causaciones_prestaciones_sociales
          GROUP BY job_causaciones_prestaciones_sociales.codigo_empresa,job_causaciones_prestaciones_sociales.documento_identidad_empleado,job_causaciones_prestaciones_sociales.concepto,job_causaciones_prestaciones_sociales.fecha_liquidacion"
     )

 );

?>

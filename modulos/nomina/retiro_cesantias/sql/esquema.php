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

$tablas["retiro_cesantias"] = array(
    "fecha_generacion"               => "DATETIME NOT NULL COMMENT 'Fecha en la se genero el retiro'",
    "fecha_liquidacion"              => "DATE NOT NULL COMMENT 'Fecha en la hasta donde se va a liquidar'",
    "fecha_contabilizacion"          => "DATE NOT NULL COMMENT 'Fecha en la se generara la contabilizacion'",
    //contrato_sucursal_empleado//
    "codigo_empresa"                 => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa donde va a laborar'",
    "documento_identidad_empleado"   => "VARCHAR(12) NOT NULL COMMENT 'Documento de identidad empleado en la sucursales contrato empleados'",
    "fecha_ingreso"                  => "DATE NOT NULL COMMENT 'Fecha en la que ingreso el empleado a la empresa'",
    "codigo_sucursal"                => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id de la tabla de sucursales'",
    "fecha_ingreso_sucursal"         => "DATE NOT NULL COMMENT 'Fecha en la que inicia labores en la sucursal el empleado'",
    //////////////////////////////
    "consecutivo"                    => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo interno para la base de datos'",
    "codigo_tipo_documento"          => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código asignado por el usuario'",
    "consecutivo_documento"          => "INT(9) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo del documento'",
    /////////////////////////////
    "codigo_transaccion_contable"    => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'codigo de la transaccion contable empleado'",
    ////////////////////////////
    "codigo_contable"                => "VARCHAR(15) NOT NULL COMMENT 'Codigo contable de la transaccion contable'",
    "concepto_retiro"                => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código que identifica el concepto de prestamo'",
    "observaciones"                  => "VARCHAR(500) NOT NULL COMMENT 'Descripción del prestamo'",
    "sentido"                        => "ENUM('D','C') NOT NULL COMMENT 'D->Débito C->Crédito'",
    "valor_retiro"                   => "DECIMAL(11,2) NOT NULL COMMENT 'valor total del prestamo'",
    "autorizado"                     => "ENUM('0','1') NOT NULL COMMENT '0 -> No 1 -> Si'",
    "pagado"                         => "ENUM('0','1') NOT NULL COMMENT '0 -> No 1 -> Si'",
    "fecha_ultima_planilla"          => "DATE NOT NULL COMMENT 'Fecha de la ultima planilla hasta donde se contabilizo el retiro'",
    "codigo_usuario_registra"        => "SMALLINT(4) UNSIGNED ZEROFILL  NOT NULL COMMENT 'Codigo del Usuario que genera el registro'",
    "codigo_usuario_modifica"        => "SMALLINT(4) UNSIGNED ZEROFILL  NOT NULL COMMENT 'Codigo del ultimo Usuario que modifica el registro'",
  );

$tablas["movimiento_retiro_cesantias"] = array(
    ///////////////////////////////////
    "documento_identidad_empleado"   => "VARCHAR(12) NOT NULL COMMENT 'Documento de identidad empleado en la sucursales contrato empleados'",
    "fecha_generacion"               => "DATETIME NOT NULL COMMENT 'Fecha en la se genero el prestamo'",
    "consecutivo"                    => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo interno para la base de datos'",
    "concepto_retiro"                => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código que identifica el concepto de prestamo'",
    //////DATOS CUENTA AFECTA/////////
    "flujo_efectivo"                 => "ENUM('1','2','3') NOT NULL COMMENT '1->No afecta flujo 2->Caja 3->Bancos'",
    "codigo_plan_contable"           => "VARCHAR(15) NOT NULL COMMENT 'Codigo de la cuenta contable segun el PUC'",
    /////////CUENTA BANCARIA//////////
    "codigo_sucursal_pertence"       => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'codigo de la sucursal con la que se relaciona la sucursal'",
    "tipo_documento_cuenta_bancaria" => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código asignado por el usuario'",
    "codigo_sucursal_banco"          => "SMALLINT(2) UNSIGNED ZEROFILL NOT NULL COMMENT 'codigo de la tabla bancos'",
    "codigo_iso"                     => "VARCHAR(2) NOT NULL COMMENT 'Llave principal de la tabla paises'",
    "codigo_dane_departamento"       => "VARCHAR(2) NOT NULL COMMENT 'Código DANE'",
    "codigo_dane_municipio"          => "VARCHAR(3) NOT NULL COMMENT 'Código DANE'",
    "codigo_banco"                   => "SMALLINT(2) UNSIGNED ZEROFILL NOT NULL COMMENT 'id de la tabla bancos'",
    "numero"                         => "VARCHAR(30) NOT NULL COMMENT 'Numero de la cuenta'"
);


$llavesPrimarias["retiro_cesantias"] = "documento_identidad_empleado,consecutivo,fecha_generacion,concepto_retiro";

$llavesForaneas["retiro_cesantias"] = array(
    array(
        //  Nombre de la llave foranea
        "sucursal_contrato_retiro_cesantias",
        //  Nombre del campo en la tabla actual
        "codigo_empresa,documento_identidad_empleado,fecha_ingreso,codigo_sucursal,fecha_ingreso_sucursal",
        //  Nombre de la tabla relacionada
        "sucursal_contrato_empleados",
        //  Nombre del campo de la tabla relacionada
        "codigo_empresa,documento_identidad_empleado,fecha_ingreso,codigo_sucursal,fecha_ingreso_sucursal"
    ),
     array(
        // Nombre de la llave foranea
        "retiro_cesantias_tipo_documento",
        // Nombre del campo en la tabla actual
        "codigo_tipo_documento",
        // Nombre de la tabla relacionada
        "tipos_documentos",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
      array(
        // Nombre de la llave foranea
        "retiro_cesantias_transaccion_contable",
        // Nombre del campo en la tabla actual
        "codigo_transaccion_contable",
        // Nombre de la tabla relacionada
        "transacciones_contables_empleado",
        // Nombre del campo de la tabla relacionada
        "codigo"
    )
);

$llavesForaneas["movimiento_retiro_cesantias"] = array(
      array(
        //  Nombre de la llave foranea
        "movimiento_retiro_cesantias",
        //  Nombre del campo en la tabla actual
        "documento_identidad_empleado,consecutivo,fecha_generacion,concepto_retiro",
        //  Nombre de la tabla relacionada
        "retiro_cesantias",
        //  Nombre del campo de la tabla relacionada
        "documento_identidad_empleado,consecutivo,fecha_generacion,concepto_retiro"
    )
);

$registros["componentes"] = array(

    array(
        "id"        => "SUBMPRSO",
        "padre"     => "SUBMNOMI",
        "id_modulo" => "NOMINA",
        "visible"   => "1",
        "orden"     => "60",
        "carpeta"   => "retiro_cesantias",
        "archivo"   => "NULL",
        "global"    => "0"
    ),
     array(
        "id"            => "GESTRECE",
        "padre"         => "SUBMPRSO",
        "id_modulo"     => "NOMINA",
        "orden"         => "02",
        "visible"       => "1",
        "carpeta"       => "retiro_cesantias",
        "global"        => "0",
        "archivo"       => "menu",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
   array(
        "id"                => "ADICRECE",
        "padre"             => "SUBMPRSO",
        "id_modulo"         => "NOMINA",
        "visible"           => "0",
        "orden"             => "0005",
        "carpeta"           => "retiro_cesantias",
        "archivo"           => "adicionar",
        "requiere_item"     => "0",
        "tabla_principal"   => "movimiento_retiro_cesantias",
        "tipo_enlace"       => "1"
    ),
      array(
        "id"                => "CONSRECE",
        "padre"             => "SUBMPRSO",
        "id_modulo"         => "NOMINA",
        "visible"           => "0",
        "orden"             => "0006",
        "carpeta"           => "retiro_cesantias",
        "archivo"           => "consultar",
        "requiere_item"     => "0",
        "tabla_principal"   => "movimiento_retiro_cesantias",
        "tipo_enlace"       => "1"
    ),
      array(
        "id"                => "ELIMRECE",
        "padre"             => "SUBMPRSO",
        "id_modulo"         => "NOMINA",
        "visible"           => "0",
        "orden"             => "0007",
        "carpeta"           => "retiro_cesantias",
        "archivo"           => "eliminar",
        "requiere_item"     => "0",
        "tabla_principal"   => "movimiento_retiro_cesantias",
        "tipo_enlace"       => "1"
    ),
      array(
        "id"                => "MODIRECE",
        "padre"             => "SUBMPRSO",
        "id_modulo"         => "NOMINA",
        "visible"           => "0",
        "orden"             => "0008",
        "carpeta"           => "retiro_cesantias",
        "archivo"           => "modificar",
        "requiere_item"     => "0",
        "tabla_principal"   => "movimiento_retiro_cesantias",
        "tipo_enlace"       => "1"
    )
);


$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_retiro_cesantias AS
        SELECT CONCAT(job_retiro_cesantias.documento_identidad_empleado,'|',job_retiro_cesantias.consecutivo,'|',job_retiro_cesantias.fecha_generacion,'|',job_retiro_cesantias.concepto_retiro) AS id,
        job_sucursales.codigo AS id_sucursal,
        job_sucursales.nombre AS SUCURSAL_LABORA,
        IF(job_terceros.tipo_persona = '1' OR job_terceros.tipo_persona = '4',
            CONCAT(
                job_terceros.primer_nombre,' ',
                IF (job_terceros.segundo_nombre IS NOT NULL AND job_terceros.segundo_nombre != '',CONCAT(job_terceros.segundo_nombre,' '),''),
                job_terceros.primer_apellido,' ',
                IF (job_terceros.segundo_apellido IS NOT NULL AND job_terceros.segundo_apellido != '',job_terceros.segundo_apellido,'')
            ),
            job_terceros.razon_social
        ) AS NOMBRE_EMPLEADO,
        DATE_FORMAT(job_retiro_cesantias.fecha_generacion,'%Y-%m-%d') AS FECHA_GENERACION,
        job_retiro_cesantias.valor_retiro AS VALOR_RETIRO
        FROM  job_retiro_cesantias,job_terceros,job_sucursales
        WHERE
              job_retiro_cesantias.documento_identidad_empleado = job_terceros.documento_identidad
              AND job_retiro_cesantias.codigo_sucursal = job_sucursales.codigo;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_retiro_cesantias AS
        SELECT CONCAT(job_retiro_cesantias.documento_identidad_empleado,'|',job_retiro_cesantias.consecutivo,'|',job_retiro_cesantias.fecha_generacion,'|',job_retiro_cesantias.concepto_retiro) AS id,
        job_sucursales.codigo AS id_sucursal,
        job_sucursales.nombre AS SUCURSAL_LABORA,
        IF(job_terceros.tipo_persona = '1' OR job_terceros.tipo_persona = '4',
            CONCAT(
                job_terceros.primer_nombre,' ',
                IF (job_terceros.segundo_nombre IS NOT NULL AND job_terceros.segundo_nombre != '',CONCAT(job_terceros.segundo_nombre,' '),''),
                job_terceros.primer_apellido,' ',
                IF (job_terceros.segundo_apellido IS NOT NULL AND job_terceros.segundo_apellido != '',job_terceros.segundo_apellido,'')
            ),
        job_terceros.razon_social
        ) AS nombre_empleado,
        job_retiro_cesantias.fecha_generacion AS fecha_generacion,
        job_retiro_cesantias.valor_retiro AS valor_retiro
        FROM  job_retiro_cesantias,job_terceros,job_sucursales
        WHERE
            job_retiro_cesantias.documento_identidad_empleado = job_terceros.documento_identidad
            AND job_retiro_cesantias.codigo_sucursal = job_sucursales.codigo;"
    )
 );

?>

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
* de APTITUD PARA UN PROPÓITO DETERMINADO. Consulte los detalles de
* la Licencia Pública General GNU para obtener una información más
* detallada.
*
* Debería haber recibido una copia de la Licencia Pública General GNU
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
**/

$borrarSiempre   = false;

// Definición de tablas
$tablas["control_prestamos_empleados"] = array(
    "fecha_generacion"                      => "DATE NOT NULL COMMENT 'Fecha en la se genero el prestamo'",
    "codigo_empresa"                        => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa donde va a laborar'",
    "documento_identidad_empleado"          => "VARCHAR(12) NOT NULL COMMENT 'Documento de identidad empleado en la sucursales contrato empleados'",
    "fecha_ingreso"                         => "DATE NOT NULL COMMENT 'Fecha en la que ingreso el empleado a la empresa'",
    "codigo_sucursal"                       => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id de la tabla de sucursales'",
    "fecha_ingreso_sucursal"                => "DATE NOT NULL COMMENT 'Fecha en la que inicia labores en la sucursal el empleado'",
    "consecutivo"                           => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo interno para la base de datos'",
    "codigo_tipo_documento"                 => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código asignado por el usuario'",
    "consecutivo_documento"                 => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo del documento'",
    "codigo_transaccion_contable_descontar" => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'codigo de la transaccion cuenta por descontar al empleado'",
    "codigo_transaccion_contable_cobrar"    => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'codigo de la transaccion cuenta por cobrar empleado'",
    "concepto_prestamo"                     => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código que identifica el concepto de prestamo'",
    //////////////////////////////
    "observaciones"                         => "VARCHAR(500) NOT NULL COMMENT 'Descripción del prestamo'",
    "valor_total"                           => "DECIMAL(11,2) NOT NULL COMMENT 'valor total del prestamo'",
    "valor_pago"                            => "DECIMAL(11,2) NULL COMMENT 'Valor que se le descontará al empleado dependiendo del periodo seleccionado'",
    "forma_pago"                            => "ENUM('1','2','3','4','5','6','7','8','9') NOT NULL COMMENT '1->mensual 2->primera quincena 3->segunda quincena 4->primera semana 5->segunda semana 6-> tercera semana 7-> cuarta semana 8-> quinta semana 9->  proporcional quincenal'",
    "fecha_registro"                        => "DATETIME NOT NULL COMMENT 'Fecha en que se genera el registro'",
    "codigo_usuario_registra"               => "SMALLINT(4) UNSIGNED ZEROFILL  NOT NULL COMMENT 'Codigo del Usuario que genera el registro'",
    "fecha_modificacion"                    => "TIMESTAMP DEFAULT '0000-00-00' COMMENT 'Fecha en que se modifica el registro'",
    "codigo_usuario_modifica"               => "SMALLINT(4) UNSIGNED ZEROFILL  NOT NULL COMMENT 'Codigo del ultimo Usuario que modifica el registro'",
    "valor_saldo"                           => "DECIMAL(11,2) NOT NULL COMMENT 'Saldo del prestamo'"
 );

$tablas["movimientos_prestamos_empleados"]= array(
    ////////////////////////////////////
    "documento_identidad_empleado"   => "VARCHAR(12) NOT NULL COMMENT 'Documento de identidad empleado en la sucursales contrato empleados'",
    "fecha_generacion"               => "DATE NOT NULL COMMENT 'Fecha en la se genero el prestamo'",
    "consecutivo"                    => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo interno para la base de datos'",
    "concepto_prestamo"              => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código que identifica el concepto de prestamo'",
    ////////////////////////////////////
    "codigo_empresa_auxiliar"        => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa del auxiliar contable'",
    "codigo_anexo_contable"          => "VARCHAR(3) NOT NULL COMMENT 'CÃ³digo del anexo que permite dividir las cuentas'",
    "codigo_auxiliar_contable"       => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código donde se acumulara la información'",
    //////DATOS CUENTA AFECTA///////////
    "flujo_efectivo"                 => "ENUM('1','2','3') NOT NULL COMMENT '1->No afecta flujo 2->Caja 3->Bancos'",
    "codigo_plan_contable"           => "VARCHAR(15) NOT NULL COMMENT 'Codigo de la cuenta contable segun el PUC'",
    "valor_movimiento"               => "DECIMAL(11,2) UNSIGNED NOT NULL COMMENT 'Valor total del prestamo'",
    "sentido"                        => "ENUM('D','C') NOT NULL DEFAULT 'D'  COMMENT 'D->Débito C->Crédito'",
    /////////CUENTA BANCARIA////////////
    "codigo_sucursal_pertence"       => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'codigo de la sucursal con la que se relaciona la sucursal'",
    "tipo_documento_cuenta_bancaria" => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código asignado por el usuario'",
    "codigo_sucursal_banco"          => "SMALLINT(2) UNSIGNED ZEROFILL NOT NULL COMMENT 'codigo de la tabla bancos'",
    "codigo_iso"                     => "VARCHAR(2) NOT NULL COMMENT 'Llave principal de la tabla paises'",
    "codigo_dane_departamento"       => "VARCHAR(2) NOT NULL COMMENT 'Código DANE'",
    "codigo_dane_municipio"          => "VARCHAR(3) NOT NULL COMMENT 'Código DANE'",
    "codigo_banco"                   => "SMALLINT(2) UNSIGNED ZEROFILL NOT NULL COMMENT 'id de la tabla bancos'",
    "numero"                         => "VARCHAR(30) NOT NULL COMMENT 'Numero de la cuenta'"
);

$tablas["fechas_prestamos_empleados"] = array(
    //////////////////////////////
    "documento_identidad_empleado"   => "VARCHAR(12) NOT NULL COMMENT 'Documento de identidad empleado en la sucursales contrato empleados'",
    "fecha_generacion"               => "DATE NOT NULL COMMENT 'Fecha en la se genero el prestamo'",
    "consecutivo"                    => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo interno para la base de datos'",
    "concepto_prestamo"              => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código que identifica el concepto de prestamo'",
    //////////////////////////////
    "fecha_pago"                     => "DATE NOT NULL COMMENT 'Fecha de acuerdo de pago'",
    "valor_saldo"                    => "DECIMAL(11,2) NOT NULL COMMENT 'valor del saldo actual'",
    "descuento"                      => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT '0->No 1->Si '",
    "pagada"                         => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT '0->No 1->Si '",
    "valor_descuento"                => "DECIMAL(11,2) NULL COMMENT 'Valor que se le descontará al empleado dependiendo del periodo seleccionado'",
 );

$tablas["movimiento_control_prestamos_empleados"] = array(
    /////////llave primaria////////
    "ano_generacion"               => "INT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'anio de la generacion la planilla'",
    "mes_generacion"               => "INT(2) UNSIGNED ZEROFILL NOT NULL COMMENT 'Mes de generacion de la planilla'",
    "codigo_planilla"              => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id de la tabla de planillas'",
    "periodo_pago"                 => "ENUM('1','2','3','4','5','6','7','8','9') NOT NULL COMMENT '1->primera semana 2->segunda semana 3->tercera semana 4->cuarta semana 5-> quinta semana 6-> proporcional quincena 7-> primera quincena 8-> segunda quincena 9-> mensual'",
    "fecha_pago_planilla"          => "DATE NOT NULL COMMENT 'Fecha rango de pago de la planilla'",
    "documento_identidad_empleado" => "VARCHAR(12) NOT NULL COMMENT 'Documento de identidad empleado en la sucursales contrato empleados'",
    "codigo_sucursal"              => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id de la tabla de sucursales'",
    ///////////////////////////////
    "codigo_transaccion_contable"   => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'codigo de la transaccion contable empleado'",
    "codigo_empresa_auxiliar"       => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa del auxiliar contable'",
    "codigo_anexo_contable"         => "VARCHAR(3) NOT NULL COMMENT 'CÃ³digo del anexo que permite dividir las cuentas'",
    "codigo_auxiliar_contable"      => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código donde se acumulara la información'",
    ///////////////////////////////
    ///////////////////////////////
    "fecha_generacion"             => "DATE NOT NULL COMMENT 'Fecha en la segenero el movimiento de tiempo'",
    "codigo_empresa"               => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa donde va a laborar'",
    "fecha_ingreso"                => "DATE NOT NULL COMMENT 'Fecha en la que ingreso el empleado a la empresa'",
    "fecha_ingreso_sucursal"       => "DATE NOT NULL COMMENT 'Fecha en la que inicia labores en la sucursal el empleado'",
    "consecutivo"                  => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo interno para la base de datos'",
    ///////////////////////////////
    "codigo_contable"              => "INT(4) NOT NULL COMMENT 'Codigo contable de la transaccion contable'",
    "sentido"                      => "ENUM('D','C') NOT NULL COMMENT 'D->Débito C->Crédito'",
    /////tabla fechas de pago//////
    "fecha_generacion_control"     => "DATE NOT NULL COMMENT 'Fecha en la se genero el prestamo'",
    "consecutivo_fecha_pago"       => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo de la fecha con la que esta relacionada el pago'",
    "fecha_pago"                   => "DATE NOT NULL COMMENT 'Fecha de acuerdo de pago'",
    "concepto_prestamo"            => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código que identifica el concepto de prestamo'",
    "valor_descuento"              => "DECIMAL(11,2) NULL COMMENT 'Valor que se le descontará al empleado dependiendo del periodo seleccionado'",
    ///////Datos de control////////
    "contabilizado"                => "ENUM('0','1','2') NOT NULL DEFAULT '0' COMMENT 'Esta contabilizado 0->No 1->Si 2->Leido para liquidar salario'",
    "codigo_usuario_registra"      => "SMALLINT(4) UNSIGNED ZEROFILL  NOT NULL COMMENT 'Codigo del Usuario que genera el registro'"
  );

////Definición de llaves primarias ////
$llavesPrimarias["control_prestamos_empleados"]            =  "documento_identidad_empleado,consecutivo,fecha_generacion,concepto_prestamo";
$llavesPrimarias["movimientos_prestamos_empleados"]        =  "documento_identidad_empleado,consecutivo,fecha_generacion,concepto_prestamo";
$llavesPrimarias["fechas_prestamos_empleados"]             =  "documento_identidad_empleado,consecutivo,fecha_generacion,concepto_prestamo,fecha_pago";
$llavesPrimarias["movimiento_control_prestamos_empleados"] =  "ano_generacion,mes_generacion,codigo_planilla,periodo_pago,fecha_pago_planilla,documento_identidad_empleado,codigo_sucursal,consecutivo";

///// Definicion de llaves Foraneas ////

$llavesForaneas["control_prestamos_empleados"] = array(
   array(
        //  Nombre de la llave foranea
        "sucursal_contrato_control_prestamos_empleados",
        //  Nombre del campo en la tabla actual
        "codigo_empresa,documento_identidad_empleado,fecha_ingreso,codigo_sucursal,fecha_ingreso_sucursal",
        //  Nombre de la tabla relacionada
        "sucursal_contrato_empleados",
        //  Nombre del campo de la tabla relacionada
        "codigo_empresa,documento_identidad_empleado,fecha_ingreso,codigo_sucursal,fecha_ingreso_sucursal"
    ),
     array(
        // Nombre de la llave foranea
        "prestamos_tipo_documento",
        // Nombre del campo en la tabla actual
        "codigo_tipo_documento",
        // Nombre de la tabla relacionada
        "tipos_documentos",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "prestamos_transaccion_cuenta_por_descotar_empleado",
        // Nombre del campo en la tabla actual
        "codigo_transaccion_contable_descontar",
        // Nombre de la tabla relacionada
        "transacciones_contables_empleado",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "prestamos_transaccion_contable_por_cobrar_empleado",
        // Nombre del campo en la tabla actual
        "codigo_transaccion_contable_cobrar",
        // Nombre de la tabla relacionada
        "transacciones_contables_empleado",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
      array(
        // Nombre de la llave foranea
        "control_prestamos_concepto_prestamo",
        // Nombre del campo en la tabla actual
        "concepto_prestamo",
        // Nombre de la tabla relacionada
        "conceptos_prestamos",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "control_prestamos_empleados_consecutivo_documentos",
        // Nombre del campo en la tabla actual
        "codigo_sucursal,codigo_tipo_documento,documento_identidad_empleado,fecha_generacion,consecutivo_documento",
        // Nombre de la tabla relacionada
        "consecutivo_documentos",
        // Nombre del campo de la tabla relacionada
        "codigo_sucursal,codigo_tipo_documento,documento_identidad_tercero,fecha_registro,consecutivo"
    ),
    array(
        // Nombre de la llave foranea
        "control_prestamos_empleados_usuario_registra",
        // Nombre del campo en la tabla actual
        "codigo_usuario_registra",
        // Nombre de la tabla relacionada
        "usuarios",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "control_prestamos_empleados_usuario_modifica",
        // Nombre del campo en la tabla actual
        "codigo_usuario_modifica",
        // Nombre de la tabla relacionada
        "usuarios",
        // Nombre del campo de la tabla relacionada
        "codigo"
    )
);

$llavesForaneas["movimientos_prestamos_empleados"] = array(
     array(
          // Nombre de la llave foranea
         "movimientos_prestamos_bancarias",
         // Nombre del campo en la tabla actual
         "codigo_sucursal_pertence,tipo_documento_cuenta_bancaria,codigo_sucursal_banco,codigo_iso,codigo_dane_departamento,codigo_dane_municipio,codigo_banco,numero",
         // Nombre de la tabla relacionada
         "cuentas_bancarias",
         // Nombre del campo de la tabla relacionada
         "codigo_sucursal,codigo_tipo_documento,codigo_sucursal_banco,codigo_iso,codigo_dane_departamento,codigo_dane_municipio,codigo_banco,numero"
     ),
     array(
        //  Nombre de la llave foranea
        "movimientos_prestamos_empleados_controles_prestamo",
        //  Nombre del campo en la tabla actual
        "documento_identidad_empleado,consecutivo,fecha_generacion,concepto_prestamo",
        //  Nombre de la tabla relacionada
        "control_prestamos_empleados",
        //  Nombre del campo de la tabla relacionada
        "documento_identidad_empleado,consecutivo,fecha_generacion,concepto_prestamo"
    ),
    array(
        // Nombre de la llave foranea
        "movimientos_prestamos_empleados_contable",
        // Nombre del campo en la tabla actual
        "codigo_empresa_auxiliar,codigo_anexo_contable,codigo_auxiliar_contable",
        // Nombre de la tabla relacionada
        "auxiliares_contables",
        // Nombre del campo de la tabla relacionada
        "codigo_empresa,codigo_anexo_contable,codigo"
    )
);

$llavesForaneas["fechas_prestamos_empleados"] = array(
      array(
        //  Nombre de la llave foranea
        "fechas_prestamos_empleados_llave_control_prestamos_empleados",
        //  Nombre del campo en la tabla actual
        "documento_identidad_empleado,consecutivo,fecha_generacion,concepto_prestamo",
        //  Nombre de la tabla relacionada
        "control_prestamos_empleados",
        //  Nombre del campo de la tabla relacionada
        "documento_identidad_empleado,consecutivo,fecha_generacion,concepto_prestamo"
    )

);
$llavesForaneas["movimiento_control_prestamos_empleados"] = array(
    array(
        //  Nombre de la llave foranea
        "movimiento_tiempos_laborados_fechas_de_pago",
        //  Nombre del campo en la tabla actual
        "documento_identidad_empleado,consecutivo_fecha_pago,fecha_generacion_control,concepto_prestamo,fecha_pago",
        //  Nombre de la tabla relacionada
        "fechas_prestamos_empleados",
        //  Nombre del campo de la tabla relacionada
       "documento_identidad_empleado,consecutivo,fecha_generacion,concepto_prestamo,fecha_pago"
    ),
    array(
        // Nombre de la llave foranea
        "movimiento_cuenta_por_descotar_empleado",
        // Nombre del campo en la tabla actual
        "codigo_transaccion_contable",
        // Nombre de la tabla relacionada
        "transacciones_contables_empleado",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "movimiento_control_prestamos_empleados_usuario_registra",
        // Nombre del campo en la tabla actual
        "codigo_usuario_registra",
        // Nombre de la tabla relacionada
        "usuarios",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "movimiento_control_prestamos_empleados_contable",
        // Nombre del campo en la tabla actual
        "codigo_empresa_auxiliar,codigo_anexo_contable,codigo_auxiliar_contable",
        // Nombre de la tabla relacionada
        "auxiliares_contables",
        // Nombre del campo de la tabla relacionada
        "codigo_empresa,codigo_anexo_contable,codigo"
    )
);

// Inserción de datos iniciales***/
$registros["componentes"] = array(
    array(
        "id"                => "GESTPTEM",
        "padre"             => "SUBMNOMI",
        "id_modulo"         => "NOMINA",
        "visible"           => "1",
        "orden"             => "0045",
        "carpeta"           => "prestamos_empleados",
        "archivo"           => "menu",
        "requiere_item"     => "1",
        "tabla_principal"   => "control_prestamos_empleados",
        "tipo_enlace"       => "1"
    ),
    array(
        "id"                => "ADICPTEM",
        "padre"             => "GESTPTEM",
        "id_modulo"         => "NOMINA",
        "visible"           => "0",
        "orden"             => "10",
        "carpeta"           => "prestamos_empleados",
        "archivo"           => "adicionar",
        "requiere_item"     => "0",
        "tabla_principal"   => "control_prestamos_empleados",
        "tipo_enlace"       => "1"
    ),
    array(
        "id"                => "CONSPTEM",
        "padre"             => "GESTPTEM",
        "id_modulo"         => "NOMINA",
        "visible"           => "0",
        "orden"             => "20",
        "carpeta"           => "prestamos_empleados",
        "archivo"           => "consultar",
        "requiere_item"     => "1",
        "tabla_principal"   => "control_prestamos_empleados",
        "tipo_enlace"       => "1"
    ),
    array(
        "id"                => "MODIPTEM",
        "padre"             => "GESTPTEM",
        "id_modulo"         => "NOMINA",
        "visible"           => "0",
        "orden"             => "30",
        "carpeta"           => "prestamos_empleados",
        "archivo"           => "modificar",
        "requiere_item"     => "1",
        "tabla_principal"   => "control_prestamos_empleados",
        "tipo_enlace"       => "1"

    ),
    array(
        "id"                => "ELIMPTEM",
        "padre"             => "GESTPTEM",
        "id_modulo"         => "NOMINA",
        "visible"           => "0",
        "orden"             => "40",
        "carpeta"           => "prestamos_empleados",
        "archivo"           => "eliminar",
        "requiere_item"     => "1",
        "tabla_principal"   => "control_prestamos_empleados",
        "tipo_enlace"       => "1"
    ),
    array(
        "id"                => "LISTPTEM",
        "padre"             => "GESTPTEM",
        "id_modulo"         => "NOMINA",
        "visible"           => "0",
        "orden"             => "50",
        "carpeta"           => "prestamos_empleados",
        "archivo"           => "listar",
        "requiere_item"     => "1",
        "tabla_principal"   => "control_prestamos_empleados",
        "tipo_enlace"       => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_control_prestamos_empleados AS
        SELECT CONCAT(job_control_prestamos_empleados.documento_identidad_empleado,'|',job_control_prestamos_empleados.fecha_generacion,'|',job_control_prestamos_empleados.consecutivo,'|',job_control_prestamos_empleados.concepto_prestamo) AS id,
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
                ) AS EMPLEADO,
                job_conceptos_prestamos.descripcion AS CONCEPTO_PRESTAMO,
                FORMAT(job_control_prestamos_empleados.valor_total,0) AS VALOR_PRESTAMO
        FROM    job_terceros,job_control_prestamos_empleados, job_conceptos_prestamos,job_sucursales
        WHERE   job_control_prestamos_empleados.documento_identidad_empleado = job_terceros.documento_identidad
                AND job_control_prestamos_empleados.concepto_prestamo = job_conceptos_prestamos.codigo
                AND job_control_prestamos_empleados.codigo_sucursal = job_sucursales.codigo;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_control_prestamos_empleados AS
        SELECT CONCAT(job_control_prestamos_empleados.documento_identidad_empleado,'|',job_control_prestamos_empleados.fecha_generacion,'|',job_control_prestamos_empleados.consecutivo,'|',job_control_prestamos_empleados.concepto_prestamo) AS id,
                job_sucursales.codigo AS id_sucursal,
                IF(job_terceros.tipo_persona = '1' OR job_terceros.tipo_persona = '4',
                    CONCAT(
                        job_terceros.primer_nombre,' ',
                        IF (job_terceros.segundo_nombre IS NOT NULL AND job_terceros.segundo_nombre != '',CONCAT(job_terceros.segundo_nombre,' '),''),
                        job_terceros.primer_apellido,' ',
                        IF (job_terceros.segundo_apellido IS NOT NULL AND job_terceros.segundo_apellido != '',job_terceros.segundo_apellido,'')
                    ),
                    job_terceros.razon_social
                ) AS EMPLEADO,
                job_conceptos_prestamos.descripcion AS concepto_prestamo,
                FORMAT(job_control_prestamos_empleados.valor_total,0) AS valor_prestamo
        FROM    job_terceros,job_control_prestamos_empleados, job_conceptos_prestamos,job_sucursales
        WHERE   job_control_prestamos_empleados.documento_identidad_empleado = job_terceros.documento_identidad
                AND job_control_prestamos_empleados.concepto_prestamo = job_conceptos_prestamos.codigo
                AND job_control_prestamos_empleados.codigo_sucursal = job_sucursales.codigo;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW  job_seleccion_contrato_empleados AS
        SELECT  CONCAT(job_control_prestamos_empleados.documento_identidad_empleado,'|',job_control_prestamos_empleados.fecha_generacion,'|',job_control_prestamos_empleados.concepto_prestamo) AS id,
                CONCAT(
                    IF(job_terceros.primer_nombre IS NOT NULL,CONCAT(job_terceros.primer_nombre,' '),''),
                    IF(job_terceros.segundo_nombre IS NOT NULL,CONCAT(job_terceros.segundo_nombre,' '),''),
                    IF(job_terceros.primer_apellido IS NOT NULL,CONCAT(job_terceros.primer_apellido,' '),''),
                    IF(job_terceros.segundo_apellido IS NOT NULL,CONCAT(job_terceros.segundo_apellido,' '),''),'|',
                    job_control_prestamos_empleados.documento_identidad_empleado
                ) AS EMPLEADO
        FROM    job_terceros,job_aspirantes,job_sucursal_contrato_empleados, job_control_prestamos_empleados, job_conceptos_prestamos
        WHERE   job_sucursal_contrato_empleados.documento_identidad_empleado = job_aspirantes.documento_identidad
                AND job_aspirantes.documento_identidad = job_terceros.documento_identidad
                AND job_control_prestamos_empleados.documento_identidad_empleado = job_sucursal_contrato_empleados.documento_identidad_empleado
                AND job_control_prestamos_empleados.concepto_prestamo = job_conceptos_prestamos.codigo;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_vista_control_contrato  AS
         SELECT  -- llave de control de prestamos --
                P.documento_identidad_empleado    AS documento_identidad_empleado,
                P.consecutivo                     AS consecutivo,
                P.fecha_generacion                AS fecha_generacion,
                P.concepto_prestamo               AS concepto_prestamo,
                -- datos de control de prestamo  --
                P.codigo_empresa                  AS codigo_empresa,
                P.fecha_ingreso                   AS fecha_ingreso,
                P.codigo_sucursal                 AS codigo_sucursal,
                P.fecha_ingreso_sucursal          AS fecha_ingreso_sucursal,
                P.codigo_tipo_documento           AS codigo_tipo_documento,
                P.codigo_transaccion_contable_descontar  AS codigo_transaccion_contable_descontar,
                P.codigo_transaccion_contable_cobrar     AS transaccion_contable_cobrar,
                P.valor_total                     AS valor_total,
                P.forma_pago                      AS forma_pago,

                IF(
                    (
                        SELECT  SUM(MS.valor_descuento)
                        FROM job_movimiento_control_prestamos_empleados AS MS

                         WHERE
                             MS.documento_identidad_empleado = P.documento_identidad_empleado  AND--
                             MS.consecutivo_fecha_pago       = P.consecutivo                   AND
                             MS.fecha_generacion_control     = P.fecha_generacion              AND
                             MS.concepto_prestamo            = P.concepto_prestamo
                            GROUP BY P.documento_identidad_empleado,MS.concepto_prestamo
                    ) IS NULL,
                    0,
                    (
                    SELECT  SUM(MS.valor_descuento)
                        FROM job_movimiento_control_prestamos_empleados AS MS

                         WHERE
                             MS.documento_identidad_empleado = P.documento_identidad_empleado  AND--
                             MS.consecutivo_fecha_pago       = P.consecutivo                   AND
                             MS.fecha_generacion_control     = P.fecha_generacion              AND
                             MS.concepto_prestamo            = P.concepto_prestamo
                            GROUP BY P.documento_identidad_empleado,MS.concepto_prestamo
                    )
                ) AS valor_pago

        FROM   job_control_prestamos_empleados AS P

        WHERE    IF((SELECT  SUM(MS.valor_descuento)
                        FROM job_movimiento_control_prestamos_empleados AS MS

                         WHERE
                             MS.documento_identidad_empleado = P.documento_identidad_empleado  AND--
                             MS.consecutivo_fecha_pago       = P.consecutivo                   AND
                             MS.fecha_generacion_control     = P.fecha_generacion              AND
                             MS.concepto_prestamo            = P.concepto_prestamo
                            GROUP BY P.documento_identidad_empleado,MS.concepto_prestamo
                    ) IS NULL,0,(SELECT  SUM(MS.valor_descuento)
                        FROM job_movimiento_control_prestamos_empleados AS MS

                         WHERE
                             MS.documento_identidad_empleado = P.documento_identidad_empleado  AND--
                             MS.consecutivo_fecha_pago       = P.consecutivo                   AND
                             MS.fecha_generacion_control     = P.fecha_generacion              AND
                             MS.concepto_prestamo            = P.concepto_prestamo
                            GROUP BY P.documento_identidad_empleado,MS.concepto_prestamo
                    )) <  P.valor_total;"
        ),
        array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_prestamos_empleados_pagados AS
        SELECT
            job_control_prestamos_empleados.fecha_generacion AS fecha_prestamo,
            job_control_prestamos_empleados.codigo_empresa AS codigo_empresa_prestamo,
            (job_control_prestamos_empleados.documento_identidad_empleado * 1) AS documento_identidad_empleado,
            job_control_prestamos_empleados.fecha_ingreso AS fecha_ingreso_empresa_prestamo,
            job_control_prestamos_empleados.codigo_sucursal AS codigo_sucursal_ingreso_prestamo,
            job_control_prestamos_empleados.fecha_ingreso_sucursal AS fecha_ingreso_sucursal_prestamo,
            job_control_prestamos_empleados.consecutivo AS consecutivo_prestamo,
            job_control_prestamos_empleados.codigo_tipo_documento AS codigo_tipo_documento,
            job_control_prestamos_empleados.consecutivo_documento AS consecutivo_documento,
            job_control_prestamos_empleados.codigo_transaccion_contable_descontar AS codigo_transaccion_contable_descontar,
            job_control_prestamos_empleados.codigo_transaccion_contable_cobrar AS codigo_transaccion_contable_cobrar,
            job_control_prestamos_empleados.concepto_prestamo AS codigo_concepto_prestamo,
            job_conceptos_prestamos.descripcion AS descripcion_concepto_prestamo,
            job_control_prestamos_empleados.observaciones AS obervaciones_prestamo,
            job_control_prestamos_empleados.valor_total AS valor_prestamo,
            job_control_prestamos_empleados.valor_pago AS valor_cuota,
            job_control_prestamos_empleados.forma_pago AS forma_pago,
            job_control_prestamos_empleados.fecha_registro AS fecha_grabacion_prestamo,
            job_control_prestamos_empleados.codigo_usuario_registra AS codigo_usuario_genera_prestamo,
            job_control_prestamos_empleados.fecha_modificacion AS fecha_modificacion_prestamo,
            job_control_prestamos_empleados.codigo_usuario_modifica AS codigo_usuario_modifica_prestamo,
            job_control_prestamos_empleados.valor_saldo AS valor_saldo_prestamo,
            job_movimiento_control_prestamos_empleados.ano_generacion AS ano_generacion,
            job_movimiento_control_prestamos_empleados.mes_generacion AS mes_generacion,
            job_movimiento_control_prestamos_empleados.codigo_planilla AS codigo_planilla,
            job_movimiento_control_prestamos_empleados.periodo_pago AS periodo_pago,
            job_movimiento_control_prestamos_empleados.fecha_pago_planilla AS fecha_pago_planilla,
            job_movimiento_control_prestamos_empleados.codigo_sucursal AS codigo_sucursal_pago,
            job_movimiento_control_prestamos_empleados.codigo_transaccion_contable AS codigo_transaccion_contable_pago,
            (
              SELECT
                descripcion
              FROM
                job_transacciones_contables_empleado
              WHERE
                job_transacciones_contables_empleado.codigo = job_movimiento_control_prestamos_empleados.codigo_transaccion_contable
            ) AS descripcion_transaccion_contable_pago,
            job_movimiento_control_prestamos_empleados.codigo_empresa_auxiliar AS codigo_empresa_auxiliar,
            job_movimiento_control_prestamos_empleados.codigo_anexo_contable AS codigo_anexo_contable,
            job_movimiento_control_prestamos_empleados.codigo_auxiliar_contable AS codigo_auxiliar_contable,
            job_movimiento_control_prestamos_empleados.fecha_generacion AS fecha_generacion_pago,
            job_movimiento_control_prestamos_empleados.codigo_empresa AS codigo_empresa_pago,
            job_movimiento_control_prestamos_empleados.fecha_ingreso_sucursal AS fecha_ingreso_sucursal_pago,
            job_movimiento_control_prestamos_empleados.consecutivo AS consecutivo_pago,
            job_movimiento_control_prestamos_empleados.codigo_contable AS codigo_contable,
            job_movimiento_control_prestamos_empleados.sentido AS sentido,
            job_movimiento_control_prestamos_empleados.fecha_generacion_control AS fecha_generacion_control,
            job_movimiento_control_prestamos_empleados.consecutivo_fecha_pago AS consecutivo_fecha_pago,
            job_movimiento_control_prestamos_empleados.fecha_pago AS fecha_pactada_pago,
            job_movimiento_control_prestamos_empleados.valor_descuento AS valor_descuento,
            job_movimiento_control_prestamos_empleados.contabilizado AS contabilizado,
            job_movimiento_control_prestamos_empleados.codigo_usuario_registra AS codigo_usuario_descuenta,
            CONCAT(
                IF(job_terceros.primer_nombre IS NOT NULL,
                    CONCAT(
                        CONCAT(job_terceros.primer_nombre,' '),
                        IF(job_terceros.segundo_nombre IS NOT NULL,CONCAT(job_terceros.segundo_nombre,' '),''),
                        IF(job_terceros.primer_apellido IS NOT NULL,CONCAT(job_terceros.primer_apellido,' '),''),
                        IF(job_terceros.segundo_apellido IS NOT NULL,CONCAT(job_terceros.segundo_apellido,''),'')
                    ),
                    job_terceros.razon_social
                )
            ) AS nombre_empleado,
            CONCAT(
                IF(job_terceros.primer_nombre IS NOT NULL,
                    CONCAT(
                        CONCAT(job_terceros.primer_apellido,' '),
                        IF(job_terceros.segundo_apellido IS NOT NULL,CONCAT(job_terceros.segundo_apellido,' '),' '),
                        CONCAT(job_terceros.primer_nombre,' '),
                        IF(job_terceros.segundo_nombre IS NOT NULL,CONCAT(job_terceros.segundo_nombre,' '),'')
                    ),
                    job_terceros.razon_social
                )
            ) AS apellido_empleado
        FROM
            job_terceros,
            job_movimiento_control_prestamos_empleados,
            job_control_prestamos_empleados,
            job_fechas_prestamos_empleados,
            job_conceptos_prestamos
        WHERE
            job_movimiento_control_prestamos_empleados.documento_identidad_empleado = job_fechas_prestamos_empleados.documento_identidad_empleado AND
            job_movimiento_control_prestamos_empleados.consecutivo_fecha_pago = job_fechas_prestamos_empleados.consecutivo AND
            job_movimiento_control_prestamos_empleados.fecha_generacion_control = job_fechas_prestamos_empleados.fecha_generacion AND
            job_movimiento_control_prestamos_empleados.concepto_prestamo = job_fechas_prestamos_empleados.concepto_prestamo AND
            job_movimiento_control_prestamos_empleados.fecha_pago = job_fechas_prestamos_empleados.fecha_pago AND
            job_movimiento_control_prestamos_empleados.concepto_prestamo = job_conceptos_prestamos.codigo AND
            job_fechas_prestamos_empleados.documento_identidad_empleado= job_control_prestamos_empleados.documento_identidad_empleado AND
            job_fechas_prestamos_empleados.consecutivo = job_control_prestamos_empleados.consecutivo AND
            job_fechas_prestamos_empleados.fecha_generacion = job_control_prestamos_empleados.fecha_generacion AND
            job_fechas_prestamos_empleados.concepto_prestamo = job_control_prestamos_empleados.concepto_prestamo AND
            job_movimiento_control_prestamos_empleados.documento_identidad_empleado = job_terceros.documento_identidad
        "
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_consulta_prestamos_empleados AS
        SELECT
            job_control_prestamos_empleados.fecha_generacion AS fecha_generacion,
            job_control_prestamos_empleados.codigo_empresa AS codigo_empresa,
            (job_control_prestamos_empleados.documento_identidad_empleado * 1) AS documento_identidad_empleado,
            job_control_prestamos_empleados.fecha_ingreso AS fecha_ingreso,
            job_control_prestamos_empleados.codigo_sucursal AS codigo_sucursal,
            job_control_prestamos_empleados.fecha_ingreso_sucursal AS fecha_ingreso_sucursal,
            job_control_prestamos_empleados.consecutivo AS consecutivo,
            job_control_prestamos_empleados.codigo_tipo_documento AS codigo_tipo_documento,
            job_control_prestamos_empleados.consecutivo_documento AS consecutivo_documento,
            job_control_prestamos_empleados.codigo_transaccion_contable_descontar AS codigo_transaccion_contable_descontar,
            job_control_prestamos_empleados.codigo_transaccion_contable_cobrar AS codigo_transaccion_contable_cobrar,
            job_control_prestamos_empleados.concepto_prestamo AS concepto_prestamo,
            job_control_prestamos_empleados.observaciones AS observaciones,
            job_control_prestamos_empleados.valor_total AS valor_total,
            job_control_prestamos_empleados.valor_pago AS valor_pago,
            job_control_prestamos_empleados.forma_pago AS forma_pago,
            job_control_prestamos_empleados.fecha_registro AS fecha_registro,
            job_control_prestamos_empleados.codigo_usuario_registra AS codigo_usuario_registra,
            job_control_prestamos_empleados.fecha_modificacion AS fecha_modificacion,
            job_control_prestamos_empleados.codigo_usuario_modifica AS codigo_usuario_modifica,
            job_control_prestamos_empleados.valor_saldo AS valor_saldo,
            job_conceptos_prestamos.descripcion AS descripcion_concepto_prestamo,
            CONCAT(
                IF(job_terceros.primer_nombre IS NOT NULL,
                    CONCAT(
                        CONCAT(job_terceros.primer_nombre,' '),
                        IF(job_terceros.segundo_nombre IS NOT NULL,CONCAT(job_terceros.segundo_nombre,' '),''),
                        IF(job_terceros.primer_apellido IS NOT NULL,CONCAT(job_terceros.primer_apellido,' '),''),
                        IF(job_terceros.segundo_apellido IS NOT NULL,CONCAT(job_terceros.segundo_apellido,''),'')
                    ),
                    job_terceros.razon_social
                )
            ) AS nombre_empleado,
            CONCAT(
                IF(job_terceros.primer_nombre IS NOT NULL,
                    CONCAT(
                        CONCAT(job_terceros.primer_apellido,' '),
                        IF(job_terceros.segundo_apellido IS NOT NULL,CONCAT(job_terceros.segundo_apellido,' '),' '),
                        CONCAT(job_terceros.primer_nombre,' '),
                        IF(job_terceros.segundo_nombre IS NOT NULL,CONCAT(job_terceros.segundo_nombre,' '),'')
                    ),
                    job_terceros.razon_social
                )
            ) AS apellido_empleado
        FROM
            job_terceros,
            job_control_prestamos_empleados,
            job_conceptos_prestamos
        WHERE
            job_control_prestamos_empleados.concepto_prestamo = job_conceptos_prestamos.codigo AND
            job_control_prestamos_empleados.documento_identidad_empleado = job_terceros.documento_identidad;
        "
    )
);
?>

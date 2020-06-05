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

$tablas["liquidaciones_empleado"] = array(
    /////////fechas de control/////////
    "fecha_generacion"                  => "DATETIME NOT NULL COMMENT 'Fecha en la se genero el retiro'",
    "fecha_liquidacion"                 => "DATE NOT NULL COMMENT 'Fecha en la hasta donde se va a liquidar'",
    "fecha_contabilizacion"             => "DATE NOT NULL COMMENT 'Fecha en la se generara la contabilizacion'",
    ////contrato_sucursal_empleado////
    "codigo_empresa"                    => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa donde va a laborar'",
    "documento_identidad_empleado"      => "VARCHAR(12) NOT NULL COMMENT 'Documento de identidad empleado en la sucursales contrato empleados'",
    "fecha_ingreso"                     => "DATE NOT NULL COMMENT 'Fecha en la que ingreso el empleado a la empresa'",
    "codigo_sucursal"                   => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id de la tabla de sucursales'",
    "fecha_ingreso_sucursal"            => "DATE NOT NULL COMMENT 'Fecha en la que inicia labores en la sucursal el empleado'",
    ///Datos consecutivo documento///
    "fecha_generacion_consecutivo"      => "DATE NOT NULL COMMENT 'Fecha de genracion del consecutico'",
    "codigo_tipo_documento"             => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código asignado por el usuario'",
    "consecutivo_documento"             => "INT(9) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo del documento'",
    /////Datos Auxiliar contable///////
    "codigo_empresa_auxiliar"           => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa para la llave de auxiliares'",
    "codigo_anexo_contable"             => "VARCHAR(3) NOT NULL COMMENT 'Código del anexo que permite dividir las cuentas'",
    "codigo_auxiliar_contable"          => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código donde se acumulara la información'",
    ///informacion de transacciones////
    ///////////Cesantias//////////////
    "fecha_inicio_cesantias"            => "DATE NOT NULL COMMENT 'Fecha en donde se empieza a calcular el valor a pagar'",
    "fecha_final_cesantias"             => "DATE NOT NULL COMMENT 'Fecha en donde se empieza a calcular el valor a pagar'",
    "dias_liquidados_cesantias"         => "SMALLINT(3) NOT NULL COMMENT 'Dias laborados en el periodo'",
    "salario_base_cesantias"            => "DECIMAL(11,2) NOT NULL COMMENT 'Valor con el que se va a liquidar el movimiento'",
    "periodo_pago_cesantias"            => "SMALLINT(3) NOT NULL COMMENT 'Dias laborados en el periodo'",
    //////Intereses/Cesantias//////////
    "fecha_inicio_interes_cesantias"    => "DATE NOT NULL COMMENT 'Fecha en donde se empieza a calcular el valor a pagar'",
    "fecha_final_interes_cesantias"     => "DATE NOT NULL COMMENT 'Fecha en donde se empieza a calcular el valor a pagar'",
    "dias_liquidados_interes_cesantias" => "SMALLINT(3) NOT NULL COMMENT 'Dias laborados en el periodo'",
    "salario_base_interes_cesantias"    => "DECIMAL(11,2) NOT NULL COMMENT 'Valor con el que se va a liquidar el movimiento'",
    "periodo_pago_interes_cesantias"    => "SMALLINT(3) NOT NULL COMMENT 'Dias laborados en el periodo'",
    ////////Primas Servicio////////////
    "fecha_inicio_primas"               => "DATE NOT NULL COMMENT 'Fecha en donde se empieza a calcular el valor a pagar'",
    "fecha_final_primas"                => "DATE NOT NULL COMMENT 'Fecha en donde se empieza a calcular el valor a pagar'",
    "dias_liquidados_primas"            => "SMALLINT(3) NOT NULL COMMENT 'Dias laborados en el periodo'",
    "salario_base_primas"               => "DECIMAL(11,2) NOT NULL COMMENT 'Valor con el que se va a liquidar el movimiento'",
    "periodo_pago_primas"               => "SMALLINT(3) NOT NULL COMMENT 'Dias laborados en el periodo'",
     ////////Vacaciones////////////////
    "fecha_inicio_vacaciones"           => "DATE NOT NULL COMMENT 'Fecha en donde se empieza a calcular el valor a pagar'",
    "fecha_final_vacaciones"            => "DATE NOT NULL COMMENT 'Fecha en donde se empieza a calcular el valor a pagar'",
    "dias_liquidados_vacaciones"        => "SMALLINT(3) NOT NULL COMMENT 'Dias laborados en el periodo'",
    "salario_base_vacaciones"           => "DECIMAL(11,2) NOT NULL COMMENT 'Valor con el que se va a liquidar el movimiento'",
    "periodo_pago_vacaciones"           => "SMALLINT(3) NOT NULL COMMENT 'Dias laborados en el periodo'",
    //////Informacion Adicional///////
    "motivo_retiro"                     => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código que identifica el concepto de prestamo'",
    "observaciones"                     => "VARCHAR(500) NOT NULL COMMENT 'Descripción del prestamo'",
    "autorizado"                        => "ENUM('0','1') NOT NULL COMMENT '0 -> No 1 -> Si'",
    "pagado"                            => "ENUM('0','1') NOT NULL COMMENT '0 -> No 1 -> Si'",
    "codigo_usuario_registra"           => "SMALLINT(4) UNSIGNED ZEROFILL  NOT NULL COMMENT 'Codigo del Usuario que genera el registro'",
    "codigo_usuario_modifica"           => "SMALLINT(4) UNSIGNED ZEROFILL  NOT NULL COMMENT 'Codigo del ultimo Usuario que modifica el registro'",
  );

$tablas["movimiento_liquidaciones_empleado"] = array(
    ///////////////////////////////////
    "codigo_empresa"                 => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa donde va a laborar'",
    "documento_identidad_empleado"   => "VARCHAR(12) NOT NULL COMMENT 'Documento de identidad empleado en la sucursales contrato empleados'",
    "fecha_generacion"               => "DATETIME NOT NULL COMMENT 'Fecha en la se genero el retiro'",
    "motivo_retiro"                  => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código que identifica el concepto de prestamo'",
    //////DATOS CUENTA AFECTA/////////
    "flujo_efectivo"                 => "ENUM('1','2','3') NOT NULL COMMENT '1->No afecta flujo 2->Caja 3->Bancos'",
    "codigo_plan_contable"           => "VARCHAR(15) NOT NULL COMMENT 'Codigo de la cuenta contable segun el PUC'",
    "sentido"                        => "ENUM('D','C') NOT NULL DEFAULT 'D'  COMMENT 'D->Débito C->Crédito'",
    /////////CUENTA BANCARIA//////////
    "codigo_sucursal_pertence"       => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'codigo de la sucursal con la que se relaciona la sucursal'",
    "tipo_documento_cuenta_bancaria" => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código asignado por el usuario'",
    "codigo_sucursal_banco"          => "SMALLINT(2) UNSIGNED ZEROFILL NOT NULL COMMENT 'codigo de la tabla bancos'",
    "codigo_iso"                     => "VARCHAR(2) NOT NULL COMMENT 'Llave principal de la tabla paises'",
    "codigo_dane_departamento"       => "VARCHAR(2) NOT NULL COMMENT 'Código DANE'",
    "codigo_dane_municipio"          => "VARCHAR(3) NOT NULL COMMENT 'Código DANE'",
    "codigo_banco"                   => "SMALLINT(2) UNSIGNED ZEROFILL NOT NULL COMMENT 'id de la tabla bancos'",
    "consecutivo_cheque"             => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo interno para la base de datos'",
    "numero"                         => "VARCHAR(30) NOT NULL COMMENT 'Numero de la cuenta'"
);

$tablas["datos_liquidaciones_empleado"] = array(
     ///////////////////////////////////
    "codigo_empresa"                 => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa donde va a laborar'",
    "documento_identidad_empleado"   => "VARCHAR(12) NOT NULL COMMENT 'Documento de identidad empleado en la sucursales contrato empleados'",
    "fecha_generacion"               => "DATETIME NOT NULL COMMENT 'Fecha en la se genero el retiro'",
    "motivo_retiro"                  => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código que identifica el concepto de prestamo'",
    /////Datos Auxiliar contable///////
    "codigo_empresa_auxiliar"        => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa para la llave de auxiliares'",
    "codigo_anexo_contable"          => "VARCHAR(3) NOT NULL COMMENT 'Código del anexo que permite dividir las cuentas'",
    "codigo_auxiliar_contable"       => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código donde se acumulara la información'",
    //////////////////////////////////
    "condigo_transaccion"            => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id de la tabla de transacciones contables'",
    "codigo_plan_contable"           => "VARCHAR(15) NOT NULL COMMENT 'Codigo de la cuenta contable segun el PUC'",
    "sentido"                        => "ENUM('D','C') NOT NULL COMMENT 'D->Débito C->Crédito'",
    "valor"                          => "DECIMAL(11,2) NOT NULL COMMENT 'Valor diario del registro'"
);
///////////Tablas de liquidaciones por salarios pendientes/////////////

$tablas["liquidaciones_movimientos_salarios"] = array(
     ///////////////////////////////////
    "codigo_empresa"                => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa donde va a laborar'",
    "documento_identidad_empleado"  => "VARCHAR(12) NOT NULL COMMENT 'Documento de identidad empleado en la sucursales contrato empleados'",
    "fecha_generacion"              => "DATETIME NOT NULL COMMENT 'Fecha en la se genero el retiro'",
    "motivo_retiro"                 => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código que identifica el concepto de prestamo'",
    "codigo_transaccion_contable"   => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id de la tabla de transacciones contables'",
    ///////////////////////////////
    "fecha_inicio_pago"             => "DATE NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado'",
    "fecha_hasta_pago"              => "DATE NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado'",
    ///////////////////////////////
    "codigo_empresa_auxiliar"       => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa del auxiliar contable'",
    "codigo_anexo_contable"         => "VARCHAR(3) NOT NULL COMMENT 'Código del anexo que permite dividir las cuentas'",
    ///tabla_auxiliares_contables
    "codigo_auxiliar_contable"      => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código donde se acumulara la informaciÃ³n'",
    ///////////////////////////////
    "codigo_contable"               => "VARCHAR(15) NOT NULL COMMENT 'Codigo contable de la transaccion contable'",
    "sentido"                       => "ENUM('D','C') NOT NULL COMMENT 'D->DÃ©bito C->CrÃ©dito'",
    "dias_trabajados"               => "SMALLINT(3) NOT NULL COMMENT 'Dias laborados en el periodo'",
    "salario_mensual"               => "DECIMAL(11,2) NOT NULL COMMENT 'Valor mensual del salario'",
    "valor_movimiento"              => "DECIMAL(11,2) NOT NULL COMMENT 'Valor diario del registro'",
);

$tablas["liquidaciones_movimientos_auxilio_transporte"] = array(
     ///////////////////////////////////
    "codigo_empresa"                => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa donde va a laborar'",
    "documento_identidad_empleado"  => "VARCHAR(12) NOT NULL COMMENT 'Documento de identidad empleado en la sucursales contrato empleados'",
    "fecha_generacion"              => "DATETIME NOT NULL COMMENT 'Fecha en la se genero el retiro'",
    "motivo_retiro"                 => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código que identifica el concepto de prestamo'",
    "codigo_transaccion_contable"   => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id de la tabla de transacciones contables'",
    ///////////////////////////////
    "fecha_inicio_pago"             => "DATE NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado'",
    "fecha_hasta_pago"              => "DATE NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado'",
    ///////////////////////////////
    "codigo_empresa_auxiliar"       => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa del auxiliar contable'",
    "codigo_anexo_contable"         => "VARCHAR(3) NOT NULL COMMENT 'Cédigo del anexo que permite dividir las cuentas'",
    ///tabla_auxiliares_contables
    "codigo_auxiliar_contable"      => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'CÃ³digo donde se acumulara la informaciÃ³n'",
    ///////////////////////////////
    "codigo_contable"               => "VARCHAR(15) NOT NULL COMMENT 'Codigo contable de la transaccion contable'",
    "sentido"                       => "ENUM('D','C') NOT NULL COMMENT 'D->Débito C->Crédito'",
    "dias_trabajados"               => "SMALLINT(3) NOT NULL COMMENT 'Dias laborados en el periodo'",
    "dias_auxilio"                  => "SMALLINT(3) NOT NULL COMMENT 'Dias que se cancelan de auxlio de trasnporte'",
    "salario_mensual"               => "DECIMAL(11,2) NOT NULL COMMENT 'Valor mensual del salario'",
    "valor_movimiento"              => "DECIMAL(11,2) NOT NULL COMMENT 'Valor diario del registro'",
);

$tablas["liquidaciones_movimientos_salud"] = array(
     ///////////////////////////////////
    "codigo_empresa"                => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa donde va a laborar'",
    "documento_identidad_empleado"  => "VARCHAR(12) NOT NULL COMMENT 'Documento de identidad empleado en la sucursales contrato empleados'",
    "fecha_generacion"              => "DATETIME NOT NULL COMMENT 'Fecha en la se genero el retiro'",
    "motivo_retiro"                 => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código que identifica el concepto de prestamo'",
    "codigo_transaccion_contable"   => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id de la tabla de transacciones contables'",
    ///////////////////////////////
    "fecha_inicio_pago"             => "DATE NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado'",
    "fecha_hasta_pago"              => "DATE NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado'",
    ///////////////////////////////
    "codigo_empresa_auxiliar"       => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa del auxiliar contable'",
    "codigo_anexo_contable"         => "VARCHAR(3) NOT NULL COMMENT 'Codigo del anexo que permite dividir las cuentas'",
    "codigo_auxiliar_contable"      => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo donde se acumulara la informacion'",
    ///////////////////////////////
    "codigo_entidad_salud"          => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la entidad parafiscal'",
    ///////////////////////////////
    "codigo_contable"               => "VARCHAR(15) NOT NULL COMMENT 'Codigo contable de la transaccion contable'",
    "sentido"                       => "ENUM('D','C') NOT NULL COMMENT 'D->Debito C->Credito'",
    "dias_trabajados"               => "SMALLINT(3) NOT NULL COMMENT 'Dias laborados en el periodo'",
    "salario_mensual"               => "DECIMAL(11,2) NOT NULL COMMENT 'Valor mensual del salario'",
    "valor_movimiento"              => "DECIMAL(11,2) NOT NULL COMMENT 'Valor diario del registro'",
    "ibc_salud"                     => "DECIMAL(11,2) NOT NULL COMMENT 'Valor sobre el cual se calcula el aporte'",
    "porcentaje_tasa_salud"         => "DECIMAL(7,4) NOT NULL DEFAULT '0.0000' COMMENT 'Porcentaje de la tasa'",
);

$tablas["liquidaciones_movimientos_pension"] = array(
     ///////////////////////////////////
    "codigo_empresa"                => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa donde va a laborar'",
    "documento_identidad_empleado"  => "VARCHAR(12) NOT NULL COMMENT 'Documento de identidad empleado en la sucursales contrato empleados'",
    "fecha_generacion"              => "DATETIME NOT NULL COMMENT 'Fecha en la se genero el retiro'",
    "motivo_retiro"                 => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código que identifica el concepto de prestamo'",
    "codigo_transaccion_contable"   => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id de la tabla de transacciones contables'",
    ///////////////////////////////
    "fecha_inicio_pago"             => "DATE NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado'",
    "fecha_hasta_pago"              => "DATE NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado'",
    ///////////////////////////////
    "codigo_empresa_auxiliar"       => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa del auxiliar contable'",
    "codigo_anexo_contable"         => "VARCHAR(3) NOT NULL COMMENT 'Codigo del anexo que permite dividir las cuentas'",
    "codigo_auxiliar_contable"      => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo donde se acumulara la informacion'",
    ///////////////////////////////
    "codigo_entidad_pension"        => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la entidad parafiscal'",
    ///////////////////////////////
    "codigo_contable"               => "VARCHAR(15) NOT NULL COMMENT 'Codigo contable de la transaccion contable'",
    "sentido"                       => "ENUM('D','C') NOT NULL COMMENT 'D->Debito C->Credito'",
    "dias_trabajados"               => "SMALLINT(3) NOT NULL COMMENT 'Dias laborados en el periodo'",
    "salario_mensual"               => "DECIMAL(11,2) NOT NULL COMMENT 'Valor mensual del salario'",
    "valor_movimiento"              => "DECIMAL(11,2) NOT NULL COMMENT 'Valor diario del registro'",
    "ibc_pension"                   => "DECIMAL(11,2) NOT NULL COMMENT 'Valor sobre el cual se calcula el aporte'",
    "porcentaje_tasa_pension"       => "DECIMAL(7,4) NOT NULL DEFAULT '0.0000' COMMENT 'Porcentaje de la tasa'",
);

$tablas["liquidaciones_prestamos_empleados"] = array(
    ////////////////////////////////
    "codigo_empresa"                => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa donde va a laborar'",
    "documento_identidad_empleado"  => "VARCHAR(12) NOT NULL COMMENT 'Documento de identidad empleado en la sucursales contrato empleados'",
    "fecha_generacion"              => "DATETIME NOT NULL COMMENT 'Fecha en la se genero el retiro'",
    "motivo_retiro"                 => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código que identifica el concepto de prestamo'",
    ///////////////////////////////
    "codigo_empresa_auxiliar"       => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa del auxiliar contable'",
    "codigo_anexo_contable"         => "VARCHAR(3) NOT NULL COMMENT 'CÃ³digo del anexo que permite dividir las cuentas'",
    "codigo_auxiliar_contable"      => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código donde se acumulara la información'",
    ///////////////////////////////
    "fecha_generacion"              => "DATE NOT NULL COMMENT 'Fecha en la segenero el movimiento de tiempo'",
    "codigo_empresa"                => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa donde va a laborar'",
    "fecha_ingreso"                 => "DATE NOT NULL COMMENT 'Fecha en la que ingreso el empleado a la empresa'",
    "fecha_ingreso_sucursal"        => "DATE NOT NULL COMMENT 'Fecha en la que inicia labores en la sucursal el empleado'",
    "consecutivo"                   => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo interno para la base de datos'",
    ///////////////////////////////
    "codigo_transaccion_contable"   => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'codigo de la transaccion contable empleado'",
    "codigo_contable"               => "INT(4) NOT NULL COMMENT 'Codigo contable de la transaccion contable'",
    "sentido"                       => "ENUM('D','C') NOT NULL COMMENT 'D->Débito C->Crédito'",
    //////////////////////////////
    "concepto_prestamo"             => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código que identifica el concepto de prestamo'",
    "valor_movimiento"              => "DECIMAL(11,2) NULL COMMENT 'Valor que se le descontará al empleado dependiendo del periodo seleccionado'",
  );


$llavesPrimarias["liquidaciones_empleado"]                       = "codigo_empresa,documento_identidad_empleado,fecha_generacion,motivo_retiro";
$llavesPrimarias["movimiento_liquidaciones_empleado"]            = "codigo_empresa,documento_identidad_empleado,fecha_generacion,motivo_retiro";
$llavesPrimarias["datos_liquidaciones_empleado"]                 = "codigo_empresa,documento_identidad_empleado,fecha_generacion,motivo_retiro,condigo_transaccion";
$llavesPrimarias["liquidaciones_movimientos_salarios"]           = "codigo_empresa,documento_identidad_empleado,fecha_generacion,motivo_retiro,codigo_transaccion_contable";
$llavesPrimarias["liquidaciones_movimientos_auxilio_transporte"] = "codigo_empresa,documento_identidad_empleado,fecha_generacion,motivo_retiro,codigo_transaccion_contable";
$llavesPrimarias["liquidaciones_movimientos_salud"]              = "codigo_empresa,documento_identidad_empleado,fecha_generacion,motivo_retiro,codigo_transaccion_contable";
$llavesPrimarias["liquidaciones_movimientos_pension"]            = "codigo_empresa,documento_identidad_empleado,fecha_generacion,motivo_retiro,codigo_transaccion_contable";

$llavesForaneas["liquidaciones_empleado"] = array(
        array(
            //  Nombre de la llave foranea
            "liquidaciones_empleado_sucursal_contrato",
            //  Nombre del campo en la tabla actual
            "codigo_empresa,documento_identidad_empleado,fecha_ingreso,codigo_sucursal,fecha_ingreso_sucursal",
            //  Nombre de la tabla relacionada
            "sucursal_contrato_empleados",
            //  Nombre del campo de la tabla relacionada
            "codigo_empresa,documento_identidad_empleado,fecha_ingreso,codigo_sucursal,fecha_ingreso_sucursal"
        ),
        array(
            // Nombre de la llave foranea
            "liquidaciones_empleado_tipo_documento",
            // Nombre del campo en la tabla actual
            "codigo_tipo_documento",
            // Nombre de la tabla relacionada
            "tipos_documentos",
            // Nombre del campo de la tabla relacionada
            "codigo"
        ),
        array(
            // Nombre de la llave foranea
            "liquidaciones_empleado_auxiliar_contable",
            // Nombre del campo en la tabla actual
            "codigo_empresa_auxiliar,codigo_anexo_contable,codigo_auxiliar_contable",
            // Nombre de la tabla relacionada
            "auxiliares_contables",
            // Nombre del campo de la tabla relacionada
            "codigo_empresa,codigo_anexo_contable,codigo"
        ),
        array(
            // Nombre de la llave foranea
            "liquidaciones_empleado_consecutivo_documentos",
            // Nombre del campo en la tabla actual
            "codigo_sucursal,codigo_tipo_documento,documento_identidad_empleado,fecha_generacion_consecutivo,consecutivo_documento",
            // Nombre de la tabla relacionada
            "consecutivo_documentos",
            // Nombre del campo de la tabla relacionada
            "codigo_sucursal,codigo_tipo_documento,documento_identidad_tercero,fecha_registro,consecutivo"
        )
);

$llavesForaneas["movimiento_liquidaciones_empleado"] = array(
        array(
        //  Nombre de la llave foranea
        "liquidaciones_empleado_movimiento_liquidaciones_empleado",
        //  Nombre del campo en la tabla actual
        "codigo_empresa,documento_identidad_empleado,fecha_generacion,motivo_retiro",
        //  Nombre de la tabla relacionada
        "liquidaciones_empleado",
        //  Nombre del campo de la tabla relacionada
        "codigo_empresa,documento_identidad_empleado,fecha_generacion,motivo_retiro"
        ),
        array(
        //  Nombre de la llave foranea
        "movimiento_liquidaciones_empleado_plan_contable",
        //  Nombre del campo en la tabla actual
        "codigo_plan_contable",
        //  Nombre de la tabla relacionada
        "plan_contable",
        //  Nombre del campo de la tabla relacionada
        "codigo_contable"
        ),
        array(
        //  Nombre de la llave foranea
        "movimiento_liquidaciones_empleado_consecutivo_cheques",
        //  Nombre del campo en la tabla actual
        "codigo_sucursal_pertence,tipo_documento_cuenta_bancaria,codigo_banco,numero,consecutivo_cheque",
        //  Nombre de la tabla relacionada
        "consecutivo_cheques",
        //  Nombre del campo de la tabla relacionada
        "codigo_sucursal,codigo_tipo_documento,codigo_banco,numero,consecutivo"
        )
 );

$llavesForaneas["datos_liquidaciones_empleado"] = array(
         array(
        //  Nombre de la llave foranea
        "liquidaciones_empleado_datos_liquidaciones_empleado",
        //  Nombre del campo en la tabla actual
        "codigo_empresa,documento_identidad_empleado,fecha_generacion,motivo_retiro",
        //  Nombre de la tabla relacionada
        "liquidaciones_empleado",
        //  Nombre del campo de la tabla relacionada
        "codigo_empresa,documento_identidad_empleado,fecha_generacion,motivo_retiro"
        ),
        array(
        //  Nombre de la llave foranea
        "datos_liquidaciones_empleado_plan_contable",
        //  Nombre del campo en la tabla actual
        "codigo_plan_contable",
        //  Nombre de la tabla relacionada
        "plan_contable",
        //  Nombre del campo de la tabla relacionada
        "codigo_contable"
        ),
        array(
        //  Nombre de la llave foranea
        "datos_liquidaciones_empleado_transaccion",
        //  Nombre del campo en la tabla actual
        "condigo_transaccion",
        //  Nombre de la tabla relacionada
        "transacciones_contables_empleado",
        //  Nombre del campo de la tabla relacionada
        "codigo"
       ),
       array(
            // Nombre de la llave foranea
            "datos_liquidaciones_empleado_auxiliar_contable",
            // Nombre del campo en la tabla actual
            "codigo_empresa_auxiliar,codigo_anexo_contable,codigo_auxiliar_contable",
            // Nombre de la tabla relacionada
            "auxiliares_contables",
            // Nombre del campo de la tabla relacionada
            "codigo_empresa,codigo_anexo_contable,codigo"
        )
);

$llavesForaneas["liquidaciones_movimientos_salarios"] = array(
         array(
        //  Nombre de la llave foranea
        "liquidaciones_movimientos_salarios_empleado",
        //  Nombre del campo en la tabla actual
        "codigo_empresa,documento_identidad_empleado,fecha_generacion,motivo_retiro",
        //  Nombre de la tabla relacionada
        "liquidaciones_empleado",
        //  Nombre del campo de la tabla relacionada
        "codigo_empresa,documento_identidad_empleado,fecha_generacion,motivo_retiro"
        ),
        array(
        //  Nombre de la llave foranea
        "liquidaciones_movimientos_salarios_transaccion",
        //  Nombre del campo en la tabla actual
        "codigo_transaccion_contable",
        //  Nombre de la tabla relacionada
        "transacciones_contables_empleado",
        //  Nombre del campo de la tabla relacionada
        "codigo"
       ),
       array(
        // Nombre de la llave foranea
        "liquidaciones_salario_anexo",
        // Nombre del campo en la tabla actual
        "codigo_anexo_contable",
        // Nombre de la tabla relacionada
        "anexos_contables",
        // Nombre del campo de la tabla relacionada
        "codigo"
      ),
      array(
        // Nombre de la llave foranea
        "liquidaciones_salario_auxiliar_contable",
        // Nombre del campo en la tabla actual
        "codigo_empresa_auxiliar,codigo_anexo_contable,codigo_auxiliar_contable",
        // Nombre de la tabla relacionada
        "auxiliares_contables",
        // Nombre del campo de la tabla relacionada
        "codigo_empresa,codigo_anexo_contable,codigo"
     )
);

$llavesForaneas["liquidaciones_movimientos_auxilio_transporte"] = array(
         array(
        //  Nombre de la llave foranea
        "liquidaciones_movimientos_auxilio_empleado",
        //  Nombre del campo en la tabla actual
        "codigo_empresa,documento_identidad_empleado,fecha_generacion,motivo_retiro",
        //  Nombre de la tabla relacionada
        "liquidaciones_empleado",
        //  Nombre del campo de la tabla relacionada
        "codigo_empresa,documento_identidad_empleado,fecha_generacion,motivo_retiro"
        ),
        array(
        //  Nombre de la llave foranea
        "liquidaciones_movimientos_auxilio_transaccion",
        //  Nombre del campo en la tabla actual
        "codigo_transaccion_contable",
        //  Nombre de la tabla relacionada
        "transacciones_contables_empleado",
        //  Nombre del campo de la tabla relacionada
        "codigo"
       ),
       array(
        // Nombre de la llave foranea
        "liquidaciones_auxilio_anexo",
        // Nombre del campo en la tabla actual
        "codigo_anexo_contable",
        // Nombre de la tabla relacionada
        "anexos_contables",
        // Nombre del campo de la tabla relacionada
        "codigo"
      ),
      array(
        // Nombre de la llave foranea
        "liquidaciones_salario_auxilio_contable",
        // Nombre del campo en la tabla actual
        "codigo_empresa_auxiliar,codigo_anexo_contable,codigo_auxiliar_contable",
        // Nombre de la tabla relacionada
        "auxiliares_contables",
        // Nombre del campo de la tabla relacionada
        "codigo_empresa,codigo_anexo_contable,codigo"
     )
);

$llavesForaneas["liquidaciones_movimientos_salud"] = array(
    array(
        // Nombre de la llave foranea
        "liquidaciones_movimiento_salud_transaccion_contable",
        // Nombre del campo en la tabla actual
        "codigo_transaccion_contable",
        // Nombre de la tabla relacionada
        "transacciones_contables_empleado",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "liquidaciones_movimiento_salud_anexo",
        // Nombre del campo en la tabla actual
        "codigo_anexo_contable",
        // Nombre de la tabla relacionada
        "anexos_contables",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "liquidaciones_movimiento_salud_auxiliar_contable",
        // Nombre del campo en la tabla actual
        "codigo_empresa_auxiliar,codigo_anexo_contable,codigo_auxiliar_contable",
        // Nombre de la tabla relacionada
        "auxiliares_contables",
        // Nombre del campo de la tabla relacionada
        "codigo_empresa,codigo_anexo_contable,codigo"
    ),
    array(
        // Nombre de la llave foranea
        "liquidaciones_movimiento_salud_entidades_parafiscales",
        // Nombre del campo en la tabla actual
        "codigo_entidad_salud",
        // Nombre de la tabla relacionada
        "entidades_parafiscales",
        // Nombre del campo de la tabla relacionada
        "codigo"
    )
);

$llavesForaneas["liquidaciones_movimientos_pension"] = array(
    array(
        // Nombre de la llave foranea
        "liquidaciones_movimiento_pension_transaccion_contable",
        // Nombre del campo en la tabla actual
        "codigo_transaccion_contable",
        // Nombre de la tabla relacionada
        "transacciones_contables_empleado",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "liquidaciones_movimiento_pension_anexo",
        // Nombre del campo en la tabla actual
        "codigo_anexo_contable",
        // Nombre de la tabla relacionada
        "anexos_contables",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "liquidaciones_movimiento_pension_auxiliar_contable",
        // Nombre del campo en la tabla actual
        "codigo_empresa_auxiliar,codigo_anexo_contable,codigo_auxiliar_contable",
        // Nombre de la tabla relacionada
        "auxiliares_contables",
        // Nombre del campo de la tabla relacionada
        "codigo_empresa,codigo_anexo_contable,codigo"
    ),
    array(
        // Nombre de la llave foranea
        "liquidaciones_movimiento_pension_entidades_parafiscales",
        // Nombre del campo en la tabla actual
        "codigo_entidad_pension",
        // Nombre de la tabla relacionada
        "entidades_parafiscales",
        // Nombre del campo de la tabla relacionada
        "codigo"
    )
);

$registros["componentes"] = array(

     array(
        "id"            => "GESTLIEM",
        "padre"         => "SUBMNOMI",
        "id_modulo"     => "NOMINA",
        "orden"         => "70",
        "visible"       => "1",
        "carpeta"       => "liquidar_empleado",
        "global"        => "0",
        "archivo"       => "menu",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
   array(
        "id"                => "ADICLIEM",
        "padre"             => "GESTLIEM",
        "id_modulo"         => "NOMINA",
        "visible"           => "0",
        "orden"             => "0005",
        "carpeta"           => "liquidar_empleado",
        "archivo"           => "adicionar",
        "requiere_item"     => "0",
        "tabla_principal"   => "liquidar_empleado",
        "tipo_enlace"       => "1"
    ),
      array(
        "id"                => "CONSLIEM",
        "padre"             => "GESTLIEM",
        "id_modulo"         => "NOMINA",
        "visible"           => "0",
        "orden"             => "0006",
        "carpeta"           => "liquidar_empleado",
        "archivo"           => "consultar",
        "requiere_item"     => "0",
        "tabla_principal"   => "liquidar_empleado",
        "tipo_enlace"       => "1"
    ),
      array(
        "id"                => "ELIMLIEM",
        "padre"             => "GESTLIEM",
        "id_modulo"         => "NOMINA",
        "visible"           => "0",
        "orden"             => "0007",
        "carpeta"           => "liquidar_empleado",
        "archivo"           => "eliminar",
        "requiere_item"     => "0",
        "tabla_principal"   => "liquidar_empleado",
        "tipo_enlace"       => "1"
    ),
      array(
        "id"                => "MODILIEM",
        "padre"             => "GESTLIEM",
        "id_modulo"         => "NOMINA",
        "visible"           => "0",
        "orden"             => "0008",
        "carpeta"           => "liquidar_empleado",
        "archivo"           => "modificar",
        "requiere_item"     => "0",
        "tabla_principal"   => "liquidar_empleado",
        "tipo_enlace"       => "1"
    )
);


$vistas = array(
    array(
        " CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_liquidar_empleado AS
          SELECT CONCAT(job_liquidaciones_empleado.codigo_empresa,'|',job_liquidaciones_empleado.documento_identidad_empleado,'|',job_liquidaciones_empleado.fecha_generacion,'|',job_liquidaciones_empleado.motivo_retiro) AS id,
          job_sucursales.codigo AS id_sucursal,
          job_sucursales.nombre AS SUCURSAL_LABORA,
          CONCAT( IF(job_terceros.primer_nombre IS NOT NULL,
                            CONCAT(
                                CONCAT(job_terceros.primer_nombre,' '),
                                IF(job_terceros.segundo_nombre IS NOT NULL,CONCAT(job_terceros.segundo_nombre,' '),''),
                                IF(job_terceros.primer_apellido IS NOT NULL,CONCAT(job_terceros.primer_apellido,' '),''),
                                IF(job_terceros.segundo_apellido IS NOT NULL,CONCAT(job_terceros.segundo_apellido,''),'')
                            ),
                            job_terceros.razon_social
                        )
                    ) AS NOMBRE_EMPLEADO,
           DATE_FORMAT(job_liquidaciones_empleado.fecha_generacion, '%Y/%m/%d') AS FECHA_GENERACION

           FROM job_liquidaciones_empleado,job_terceros,job_sucursales
           WHERE
           job_liquidaciones_empleado.documento_identidad_empleado = job_terceros.documento_identidad AND
           job_liquidaciones_empleado.codigo_sucursal = job_sucursales.codigo"
     ),
     array(
        " CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_retiro_cesantias AS
          SELECT CONCAT(job_liquidaciones_empleado.codigo_empresa,'|',job_liquidaciones_empleado.documento_identidad_empleado,'|',job_liquidaciones_empleado.fecha_generacion,'|',job_liquidaciones_empleado.motivo_retiro) AS id,
          job_sucursales.codigo AS id_sucursal,
          job_sucursales.nombre AS SUCURSAL_LABORA,
          CONCAT( IF(job_terceros.primer_nombre IS NOT NULL,
                            CONCAT(
                                CONCAT(job_terceros.primer_nombre,' '),
                                IF(job_terceros.segundo_nombre IS NOT NULL,CONCAT(job_terceros.segundo_nombre,' '),''),
                                IF(job_terceros.primer_apellido IS NOT NULL,CONCAT(job_terceros.primer_apellido,' '),''),
                                IF(job_terceros.segundo_apellido IS NOT NULL,CONCAT(job_terceros.segundo_apellido,''),'')
                            ),
                            job_terceros.razon_social
                        )
                    ) AS NOMBRE_EMPLEADO,
           DATE_FORMAT(job_liquidaciones_empleado.fecha_generacion, '%Y/%m/%d') AS FECHA_GENERACION

           FROM job_liquidaciones_empleado,job_terceros,job_sucursales
           WHERE
           job_liquidaciones_empleado.documento_identidad_empleado = job_terceros.documento_identidad AND
           job_liquidaciones_empleado.codigo_sucursal = job_sucursales.codigo"
     )
 );

?>

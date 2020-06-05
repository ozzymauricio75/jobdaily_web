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
$tablas["control_prestamos_terceros"] = array(
    "limite_descuento"                      => "ENUM('0','1','2') NOT NULL COMMENT '0-> Descuento ilimitado 1-> Fecha limite  2->valor tope'",
    "fecha_generacion"                      => "DATETIME NOT NULL COMMENT 'Fecha en la se genero el prestamo'",
    "fecha_inicio_descuento"                => "DATE NOT NULL COMMENT 'Fecha en la se genero el prestamo'",
    //contrato_sucursal_empleado//
    "codigo_sucursal"                       => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id de la tabla de sucursales'",
    "codigo_empresa"                        => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa donde va a laborar'",
    "documento_identidad_empleado"          => "VARCHAR(12) NOT NULL COMMENT 'Documento de identidad empleado en la sucursales contrato empleados'",
    //////////////////////////////
    "documento_identidad_tercero"           => "VARCHAR(12) NOT NULL COMMENT 'Documento de identidad empleado en la sucursales contrato empleados'",
    "autorizacion_descuento_nomina"         => "ENUM('0','1') NOT NULL COMMENT '0 -> No 1 -> Si'",
    "obligacion"                            => "VARCHAR(10) NOT NULL COMMENT 'Numero de obligación'",
    "valor_tope_descuento"                  => "DECIMAL(11,2) NOT NULL COMMENT 'Valor que sele va a descontar al empleado'",
    "periodo_pago"                          => "ENUM('1','2','3','4','5','6','7','8','9') NOT NULL COMMENT '1->mensual 2->primera quincena 3->segunda quincena 4->primera semana 5->segunda semana 6-> tercera semana 7-> cuarta semana 8-> quinta semana 9->Proporcional quincenal'",
    //////////////////////////////
    "valor_descontar_mensual"               => "DECIMAL(11,2) NOT NULL COMMENT 'Valor con el que se va a liquidar el movimiento'",
    "valor_descontar_primera_quincena"      => "DECIMAL(11,2) NOT NULL COMMENT 'Valor con el que se va a liquidar el movimiento'",
    "valor_descontar_segunda_quincena"      => "DECIMAL(11,2) NOT NULL COMMENT 'Valor con el que se va a liquidar el movimiento'",
    "valor_descontar_primera_semana"        => "DECIMAL(11,2) NOT NULL COMMENT 'Valor con el que se va a liquidar el movimiento'",
    "valor_descontar_segunda_semana"        => "DECIMAL(11,2) NOT NULL COMMENT 'Valor con el que se va a liquidar el movimiento'",
    "valor_descontar_tercera_semana"        => "DECIMAL(11,2) NOT NULL COMMENT 'Valor con el que se va a liquidar el movimiento'",
    "valor_descontar_cuarta_semana"         => "DECIMAL(11,2) NOT NULL COMMENT 'Valor con el que se va a liquidar el movimiento'",
    ///////////////////////////////
    "descuento_ilimitado"                   => "ENUM('0','1') NOT NULL COMMENT '0-> No 1-> Si'",
    "fecha_limite_descuento"                => "DATE NOT NULL COMMENT 'Fecha hasta la cual se hace el descuento'",
    //////////////////////////////
    "estado"                                => "ENUM('0','1','2') NOT NULL COMMENT '0-> activa 1-> suspendida 2-> cancelada'",
    "codigo_usuario_registra"               => "SMALLINT(4) UNSIGNED ZEROFILL  NOT NULL COMMENT 'Codigo del Usuario que genera el registro'",
    "codigo_usuario_modifica"               => "SMALLINT(4) UNSIGNED ZEROFILL  NOT NULL COMMENT 'Codigo del ultimo Usuario que modifica el registro'",
    "transaccion_contable_descuento"        => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'codigo de la transaccion contable empleado cuenta por cobrar descuento'",
    "transaccion_contable_empleado"         => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'codigo de la transaccion contable empleado cuenta por cobrar empleado'",
    "transaccion_contable_pagar_tercero"    => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'codigo de la transaccion contable empleado cuenta por pagar tercero'",
    "transaccion_contable_pago_tercero"     => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'codigo de la transaccion contable empleado cuenta pago tercero'",
 );

$tablas["movimiento_cuenta_por_cobrar_descuento"] = array(
    /////////llave primaria////////
    "ano_generacion"               => "INT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'anio de la generacion la planilla'",
    "mes_generacion"               => "INT(2) UNSIGNED ZEROFILL NOT NULL COMMENT 'Mes de generacion de la planilla'",
    "codigo_planilla"              => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id de la tabla de planillas'",
    "periodo_pago"                 => "ENUM('1','2','3','4','5','6','7','8','9') NOT NULL COMMENT '1->primera semana 2->segunda semana 3->tercera semana 4->cuarta semana 5-> quinta semana 6-> proporcional quincena 7-> primera quincena 8-> segunda quincena 9-> mensual'",
    "fecha_pago_planilla"          => "DATE NOT NULL COMMENT 'Fecha rango de pago de la planilla'",
    "codigo_sucursal"              => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id de la tabla de sucursales'",
    ////////////////////////////////
    "codigo_empresa_auxiliar"      => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa del auxiliar contable'",
    "codigo_anexo_contable"        => "VARCHAR(3) NOT NULL COMMENT 'Código del anexo que permite dividir las cuentas'",
    "codigo_auxiliar_contable"     => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código donde se acumulara la información'",
    ///////////////////////////////
    "fecha_generacion"             => "DATE NOT NULL COMMENT 'Fecha en la que se genero el movimiento de control de prestamos de terceros'",
    //////////////////////////////
    "codigo_empresa"               => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa donde va a laborar'",
    "documento_identidad_empleado" => "VARCHAR(12) NOT NULL COMMENT 'Documento de identidad empleado en la sucursales contrato empleados'",
    "obligacion"                   => "VARCHAR(10) NOT NULL COMMENT 'Documento de identidad empleado en la sucursales contrato empleados'",
    "valor_movimiento"             => "DECIMAL(11,2) NULL COMMENT 'Valor que se le descontará al empleado dependiendo del periodo seleccionado'",
    ///////////////////////////////
    "codigo_transaccion_contable"  => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'codigo de la transaccion contable empleado'",
    "codigo_contable"              => "VARCHAR(15)  NOT NULL COMMENT 'Codigo del plan contable'",
    "sentido"                      => "ENUM('D','C') NOT NULL COMMENT 'D->Débito C->Crédito'",
    ///////Datos de control////////
    "contabilizado"                => "ENUM('0','1','2') NOT NULL DEFAULT '0' COMMENT 'Esta contabilizado 0->No 1->Si 2->Leido para liquidar salario'",
    "codigo_usuario_registra"      => "SMALLINT(4) UNSIGNED ZEROFILL  NOT NULL COMMENT 'Codigo del Usuario que genera el registro'",
);

$tablas["movimiento_cuenta_por_cobrar_empleado"] = array(
    ////Llave Primaria///////////
    "documento_identidad_empleado" => "VARCHAR(12) NOT NULL COMMENT 'Documento de identidad empleado en la sucursales contrato empleados'",
    "codigo_sucursal"              => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id de la tabla de sucursales'",
    "obligacion"                   => "VARCHAR(10) NOT NULL COMMENT 'Documento de identidad empleado en la sucursales contrato empleados'",
    "fecha_pago_planilla"          => "DATE NOT NULL COMMENT 'Fecha rango de pago de la planilla'",
    ////////////////////////////
    "fecha_generacion"             => "DATETIME NOT NULL COMMENT 'Fecha en la se genero el retiro'",
    "codigo_empresa"               => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa donde va a laborar'",
    "codigo_transaccion_contable"  => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'codigo de la transaccion contable empleado'",
    "valor_movimiento"             => "DECIMAL(11,2) NULL COMMENT 'Valor que se le descontará al empleado dependiendo del periodo seleccionado'",
     ////////////////////////////////
    "codigo_empresa_auxiliar"      => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa del auxiliar contable'",
    "codigo_anexo_contable"        => "VARCHAR(3) NOT NULL COMMENT 'Código del anexo que permite dividir las cuentas'",
    "codigo_auxiliar_contable"     => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código donde se acumulara la información'",
    ///////////////////////////////
    "codigo_contable"              => "VARCHAR(15)  NOT NULL COMMENT 'Codigo del plan contable'",
    "sentido"                      => "ENUM('D','C') NOT NULL COMMENT 'D->Débito C->Crédito'",
    ///////Datos de control////////
    "contabilizado"                => "ENUM('0','1','2') NOT NULL DEFAULT '0' COMMENT 'Esta contabilizado 0->No 1->Si 2->Leido para liquidar salario'",
    "codigo_usuario_registra"      => "SMALLINT(4) UNSIGNED ZEROFILL  NOT NULL COMMENT 'Codigo del Usuario que genera el registro'",
);

$tablas["movimiento_cuenta_por_pagar_tercero"] = array(
    ////Llave Primaria///////////
    "documento_identidad_empleado" => "VARCHAR(12) NOT NULL COMMENT 'Documento de identidad empleado en la sucursales contrato empleados'",
    "codigo_sucursal"              => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id de la tabla de sucursales'",
    "obligacion"                   => "VARCHAR(10) NOT NULL COMMENT 'Documento de identidad empleado en la sucursales contrato empleados'",
    "fecha_pago_planilla"          => "DATE NOT NULL COMMENT 'Fecha rango de pago de la planilla'",
    ////////////////////////////////
    "fecha_generacion"             => "DATETIME NOT NULL COMMENT 'Fecha en la se genero el retiro'",
    "codigo_empresa_auxiliar"      => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa del auxiliar contable'",
    "codigo_anexo_contable"        => "VARCHAR(3) NOT NULL COMMENT 'Código del anexo que permite dividir las cuentas'",
    "codigo_auxiliar_contable"     => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código donde se acumulara la información'",
    //////////////////////////////
    "codigo_empresa"               => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa donde va a laborar'",
    "documento_identidad_tercero"  => "VARCHAR(12) NOT NULL COMMENT 'Documento de identidad del tercero'",
    "codigo_transaccion_contable"  => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'codigo de la transaccion contable empleado'",
    "valor_movimiento"             => "DECIMAL(11,2) NULL COMMENT 'Valor que se le descontará al empleado dependiendo del periodo seleccionado'",
    ///////////////////////////////
    "codigo_contable"              => "VARCHAR(15)  NOT NULL COMMENT 'Codigo del plan contable'",
    "sentido"                      => "ENUM('D','C') NOT NULL COMMENT 'D->Débito C->Crédito'",
    ///////Datos de control////////
    "contabilizado"                => "ENUM('0','1','2') NOT NULL DEFAULT '0' COMMENT 'Esta contabilizado 0->No 1->Si 2->Leido para liquidar salario'",
    "codigo_usuario_registra"      => "SMALLINT(4) UNSIGNED ZEROFILL  NOT NULL COMMENT 'Codigo del Usuario que genera el registro'",
  );

$tablas["movimiento_cuenta_pago_tercero"] = array(
     ////Llave Primaria///////////
    "documento_identidad_empleado" => "VARCHAR(12) NOT NULL COMMENT 'Documento de identidad empleado en la sucursales contrato empleados'",
    "codigo_sucursal"              => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id de la tabla de sucursales'",
    "obligacion"                   => "VARCHAR(10) NOT NULL COMMENT 'Documento de identidad empleado en la sucursales contrato empleados'",
    "fecha_pago_planilla"          => "DATE NOT NULL COMMENT 'Fecha rango de pago de la planilla'",
    ////////////////////////////////
    "documento_identidad_tercero"  => "VARCHAR(12) NOT NULL COMMENT 'Documento de identidad del tercero'",
    "fecha_generacion"             => "DATETIME NOT NULL COMMENT 'Fecha en la se genero el retiro'",
    "codigo_empresa_auxiliar"      => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa del auxiliar contable'",
    "codigo_anexo_contable"        => "VARCHAR(3) NOT NULL COMMENT 'Código del anexo que permite dividir las cuentas'",
    "codigo_auxiliar_contable"     => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código donde se acumulara la información'",
    //////////////////////////////
    "codigo_empresa"               => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa donde va a laborar'",
    "documento_identidad_empleado" => "VARCHAR(12) NOT NULL COMMENT 'Documento de identidad empleado en la sucursales contrato empleados'",
    "codigo_transaccion_contable"  => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'codigo de la transaccion contable empleado'",
    "obligacion"                   => "VARCHAR(10) NOT NULL COMMENT 'Documento de identidad empleado en la sucursales contrato empleados'",
    "valor_movimiento"             => "DECIMAL(11,2) NULL COMMENT 'Valor que se le descontará al empleado dependiendo del periodo seleccionado'",
    ///////////////////////////////
    "codigo_contable"              => "VARCHAR(15)  NOT NULL COMMENT 'Codigo del plan contable'",
    "sentido"                      => "ENUM('D','C') NOT NULL COMMENT 'D->Débito C->Crédito'",
    ///////Datos de control////////
    "contabilizado"                => "ENUM('0','1','2') NOT NULL DEFAULT '0' COMMENT 'Esta contabilizado 0->No 1->Si 2->Leido para liquidar salario'",
    "codigo_usuario_registra"      => "SMALLINT(4) UNSIGNED ZEROFILL  NOT NULL COMMENT 'Codigo del Usuario que genera el registro'",
);


////Definición de llaves primarias ////
$llavesPrimarias["control_prestamos_terceros"]             =  "codigo_empresa,documento_identidad_empleado,obligacion";
$llavesPrimarias["movimiento_cuenta_por_cobrar_descuento"] =  "ano_generacion,mes_generacion,codigo_planilla,periodo_pago,fecha_pago_planilla,documento_identidad_empleado,codigo_sucursal,obligacion";
$llavesPrimarias["movimiento_cuenta_por_cobrar_empleado"]  =  "documento_identidad_empleado,codigo_sucursal,obligacion,fecha_pago_planilla";
$llavesPrimarias["movimiento_cuenta_por_pagar_tercero"]    =  "documento_identidad_empleado,codigo_sucursal,obligacion,fecha_pago_planilla";
$llavesPrimarias["movimiento_cuenta_pago_tercero"]         =  "documento_identidad_empleado,codigo_sucursal,obligacion,fecha_pago_planilla";

///// Definicion de llaves Foraneas ////
$llavesForaneas["control_prestamos_terceros"] = array(
   array(
        //  Nombre de la llave foranea
        "control_prestamos_terceros_sucursal",
        //  Nombre del campo en la tabla actual
        "codigo_sucursal",
        //  Nombre de la tabla relacionada
        "sucursales",
        //  Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        //  Nombre de la llave foranea
        "control_prestamos_terceros_ingreso_empleado",
        //  Nombre del campo en la tabla actual
        "documento_identidad_empleado,codigo_empresa",
        //  Nombre de la tabla relacionada
        "ingreso_empleados",
        //  Nombre del campo de la tabla relacionada
        "documento_identidad_empleado,codigo_empresa"
    ),
    array(
        //  Nombre de la llave foranea
        "control_prestamos_terceros_terceros",
        //  Nombre del campo en la tabla actual
        "documento_identidad_tercero",
        //  Nombre de la tabla relacionada
        "terceros",
        //  Nombre del campo de la tabla relacionada
        "documento_identidad"
    ),
    array(
        //  Nombre de la llave foranea
        "control_prestamos_transaccion_contable_descuento",
        //  Nombre del campo en la tabla actual
        "transaccion_contable_descuento",
        //  Nombre de la tabla relacionada
        "transacciones_contables_empleado",
        //  Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        //  Nombre de la llave foranea
        "control_prestamos_transaccion_contable_empleado",
        //  Nombre del campo en la tabla actual
        "transaccion_contable_empleado",
        //  Nombre de la tabla relacionada
        "transacciones_contables_empleado",
        //  Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        //  Nombre de la llave foranea
        "control_prestamos_transaccion_contable_pagar_tercero",
        //  Nombre del campo en la tabla actual
        "transaccion_contable_pagar_tercero",
        //  Nombre de la tabla relacionada
        "transacciones_contables_empleado",
        //  Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        //  Nombre de la llave foranea
        "control_prestamos_transaccion_contable_pago_tercero",
        //  Nombre del campo en la tabla actual
        "transaccion_contable_pago_tercero",
        //  Nombre de la tabla relacionada
        "transacciones_contables_empleado",
        //  Nombre del campo de la tabla relacionada
        "codigo"
    )
 );

$llavesForaneas["movimiento_cuenta_por_cobrar_descuento"] = array(
    array(
        //  Nombre de la llave foranea
        "cuenta_por_cobrar_descuento_sucursal",
        //  Nombre del campo en la tabla actual
        "codigo_sucursal",
        //  Nombre de la tabla relacionada
        "sucursales",
        //  Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        //  Nombre de la llave foranea
        "cuenta_por_cobrar_descuento_relacion",
        //  Nombre del campo en la tabla actual
        "codigo_empresa,documento_identidad_empleado,obligacion",
        //  Nombre de la tabla relacionada
        "control_prestamos_terceros",
        //  Nombre del campo de la tabla relacionada
        "codigo_empresa,documento_identidad_empleado,obligacion"
    ),array(
        //  Nombre de la llave foranea
        "movimiento_cuenta_por_cobrar_descuento_transaccion_contable",
        //  Nombre del campo en la tabla actual
        "codigo_transaccion_contable",
        //  Nombre de la tabla relacionada
        "transacciones_contables_empleado",
        //  Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "movimiento_cuenta_por_cobrar_descuento_contable",
        // Nombre del campo en la tabla actual
        "codigo_empresa_auxiliar,codigo_anexo_contable,codigo_auxiliar_contable",
        // Nombre de la tabla relacionada
        "auxiliares_contables",
        // Nombre del campo de la tabla relacionada
        "codigo_empresa,codigo_anexo_contable,codigo"
    )
);
///////////////////////////////////////////////////////////////////
$llavesForaneas["movimiento_cuenta_por_cobrar_empleado"] = array(
    array(
        //  Nombre de la llave foranea
        "cuenta_por_comprar_empleado_sucursal",
        //  Nombre del campo en la tabla actual
        "codigo_sucursal",
        //  Nombre de la tabla relacionada
        "sucursales",
        //  Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        //  Nombre de la llave foranea
        "cuenta_por_comprar_empleado_relacion",
        //  Nombre del campo en la tabla actual
        "codigo_empresa,documento_identidad_empleado,obligacion",
        //  Nombre de la tabla relacionada
        "control_prestamos_terceros",
        //  Nombre del campo de la tabla relacionada
        "codigo_empresa,documento_identidad_empleado,obligacion"
    ),array(
        //  Nombre de la llave foranea
        "cuenta_por_comprar_empleado_transaccion_contable",
        //  Nombre del campo en la tabla actual
        "codigo_transaccion_contable",
        //  Nombre de la tabla relacionada
        "transacciones_contables_empleado",
        //  Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "movimiento_cuenta_por_cobrar_empleado_transaccion_contable",
        // Nombre del campo en la tabla actual
        "codigo_empresa_auxiliar,codigo_anexo_contable,codigo_auxiliar_contable",
        // Nombre de la tabla relacionada
        "auxiliares_contables",
        // Nombre del campo de la tabla relacionada
        "codigo_empresa,codigo_anexo_contable,codigo"
    )
);
///////////////////////////////////////////////////////////////////
$llavesForaneas["movimiento_cuenta_por_pagar_tercero"] = array(
    array(
        //  Nombre de la llave foranea
        "movimiento_cuenta_por_pagar_documento_identidad",
        //  Nombre del campo en la tabla actual
        "documento_identidad_tercero",
        //  Nombre de la tabla relacionada
        "terceros",
        //  Nombre del campo de la tabla relacionada
        "documento_identidad"
    ),
    array(
        //  Nombre de la llave foranea
        "movimiento_cuenta_por_pagar_tercero_sucursal",
        //  Nombre del campo en la tabla actual
        "codigo_sucursal",
        //  Nombre de la tabla relacionada
        "sucursales",
        //  Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        //  Nombre de la llave foranea
        "movimiento_cuenta_por_pagar_tercero_relacion",
        //  Nombre del campo en la tabla actual
        "codigo_empresa,documento_identidad_empleado,obligacion",
        //  Nombre de la tabla relacionada
        "control_prestamos_terceros",
        //  Nombre del campo de la tabla relacionada
        "codigo_empresa,documento_identidad_empleado,obligacion"
    ),array(
        //  Nombre de la llave foranea
        "movimiento_cuenta_por_pagar_tercero_transaccion_contable",
        //  Nombre del campo en la tabla actual
        "codigo_transaccion_contable",
        //  Nombre de la tabla relacionada
        "transacciones_contables_empleado",
        //  Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "movimiento_cuenta_por_pagar_tercero_contable",
        // Nombre del campo en la tabla actual
        "codigo_empresa_auxiliar,codigo_anexo_contable,codigo_auxiliar_contable",
        // Nombre de la tabla relacionada
        "auxiliares_contables",
        // Nombre del campo de la tabla relacionada
        "codigo_empresa,codigo_anexo_contable,codigo"
    )
);
///////////////////////////////////////////////////////////////////
$llavesForaneas["movimiento_cuenta_pago_tercero"] = array(
     array(
        //  Nombre de la llave foranea
        "movimiento_cuenta_pago_tercero_documento_identidad",
        //  Nombre del campo en la tabla actual
        "documento_identidad_tercero",
        //  Nombre de la tabla relacionada
        "terceros",
        //  Nombre del campo de la tabla relacionada
        "documento_identidad"
    ),
    array(
        //  Nombre de la llave foranea
        "movimiento_cuenta_pago_tercero_sucursal",
        //  Nombre del campo en la tabla actual
        "codigo_sucursal",
        //  Nombre de la tabla relacionada
        "sucursales",
        //  Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        //  Nombre de la llave foranea
        "movimiento_cuenta_pago_tercero_relacion",
        //  Nombre del campo en la tabla actual
        "codigo_empresa,documento_identidad_empleado,obligacion",
        //  Nombre de la tabla relacionada
        "control_prestamos_terceros",
        //  Nombre del campo de la tabla relacionada
        "codigo_empresa,documento_identidad_empleado,obligacion"
    ),array(
        //  Nombre de la llave foranea
        "movimiento_cuenta_pago_tercero_transaccion_contable",
        //  Nombre del campo en la tabla actual
        "codigo_transaccion_contable",
        //  Nombre de la tabla relacionada
        "transacciones_contables_empleado",
        //  Nombre del campo de la tabla relacionada
        "codigo"
    ),array(
        // Nombre de la llave foranea
        "movimiento_cuenta_pago_tercero_contable",
        // Nombre del campo en la tabla actual
        "codigo_empresa_auxiliar,codigo_anexo_contable,codigo_auxiliar_contable",
        // Nombre de la tabla relacionada
        "auxiliares_contables",
        // Nombre del campo de la tabla relacionada
        "codigo_empresa,codigo_anexo_contable,codigo"
    )

);


////Inserción de datos iniciales
$registros["componentes"] = array(
    array(
        "id"                => "GESTPTTE",
        "padre"             => "SUBMNOMI",
        "id_modulo"         => "NOMINA",
        "visible"           => "1",
        "orden"             => "0046",
        "carpeta"           => "prestamos_terceros",
        "archivo"           => "menu",
        "requiere_item"     => "1",
        "tabla_principal"   => "control_prestamos_terceros",
        "tipo_enlace"       => "1"
    ),
    array(
        "id"                => "ADICPTTE",
        "padre"             => "GESTPTTE",
        "id_modulo"         => "NOMINA",
        "visible"           => "0",
        "orden"             => "10",
        "carpeta"           => "prestamos_terceros",
        "archivo"           => "adicionar",
        "requiere_item"     => "0",
        "tabla_principal"   => "control_prestamos_terceros",
        "tipo_enlace"       => "1"
    ),
    array(
        "id"                => "CONSPTTE",
        "padre"             => "GESTPTTE",
        "id_modulo"         => "NOMINA",
        "visible"           => "0",
        "orden"             => "20",
        "carpeta"           => "prestamos_terceros",
        "archivo"           => "consultar",
        "requiere_item"     => "1",
        "tabla_principal"   => "control_prestamos_terceros",
        "tipo_enlace"       => "1"
    ),
    array(
        "id"                => "MODIPTTE",
        "padre"             => "GESTPTTE",
        "id_modulo"         => "NOMINA",
        "visible"           => "0",
        "orden"             => "30",
        "carpeta"           => "prestamos_terceros",
        "archivo"           => "modificar",
        "requiere_item"     => "1",
        "tabla_principal"   => "control_prestamos_terceros",
        "tipo_enlace"       => "1"
    ),
    array(
        "id"                => "ELIMPTTE",
        "padre"             => "GESTPTTE",
        "id_modulo"         => "NOMINA",
        "visible"           => "0",
        "orden"             => "40",
        "carpeta"           => "prestamos_terceros",
        "archivo"           => "eliminar",
        "requiere_item"     => "1",
        "tabla_principal"   => "control_prestamos_terceros",
        "tipo_enlace"       => "1"
    ),
    array(
        "id"                => "LISTPTTE",
        "padre"             => "GESTPTTE",
        "id_modulo"         => "NOMINA",
        "visible"           => "0",
        "orden"             => "50",
        "carpeta"           => "prestamos_terceros",
        "archivo"           => "listar",
        "requiere_item"     => "1",
        "tabla_principal"   => "control_prestamos_terceros",
        "tipo_enlace"       => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_control_prestamos_terceros AS
         SELECT CONCAT(job_control_prestamos_terceros.codigo_empresa,'|',job_control_prestamos_terceros.documento_identidad_empleado,'|',job_control_prestamos_terceros.obligacion) AS id,
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
                CONCAT(job_transacciones_contables_empleado.codigo_contable,' - ',job_transacciones_contables_empleado.descripcion) AS TRANSACCION_CONTABLE_DESCUENTO,
                job_control_prestamos_terceros.obligacion AS OBLIGACION,
                CONCAT('ESTADO_',
                 job_control_prestamos_terceros.estado
                ) AS ESTADO


        FROM    job_terceros,job_control_prestamos_terceros,job_sucursales,job_transacciones_contables_empleado
        WHERE   job_control_prestamos_terceros.documento_identidad_empleado = job_terceros.documento_identidad
                AND job_control_prestamos_terceros.codigo_sucursal = job_sucursales.codigo
                AND job_control_prestamos_terceros.transaccion_contable_descuento = job_transacciones_contables_empleado.codigo;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_control_prestamos_empleados AS
        SELECT CONCAT(job_control_prestamos_terceros.codigo_empresa,'|',job_control_prestamos_terceros.documento_identidad_empleado,'|',job_control_prestamos_terceros.obligacion) AS id,
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

                job_control_prestamos_terceros.obligacion AS OBLIGACION


        FROM    job_terceros,job_control_prestamos_terceros,job_sucursales
        WHERE   job_control_prestamos_terceros.documento_identidad_empleado = job_terceros.documento_identidad
                AND job_control_prestamos_terceros.codigo_sucursal = job_sucursales.codigo;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_prestamos_terceros_pagados AS SELECT
            job_control_prestamos_terceros.limite_descuento AS limite_descuento,
            job_control_prestamos_terceros.fecha_generacion AS fecha_generacion,
            job_control_prestamos_terceros.fecha_inicio_descuento AS fecha_inicio_descuento,
            job_control_prestamos_terceros.codigo_sucursal AS codigo_sucursal,
            job_control_prestamos_terceros.codigo_empresa AS codigo_empresa,
            (job_control_prestamos_terceros.documento_identidad_tercero * 1) AS documento_identidad_tercero,
            (job_control_prestamos_terceros.documento_identidad_empleado * 1) AS documento_identidad_empleado,
            job_control_prestamos_terceros.autorizacion_descuento_nomina AS autorizacion_descuento_nomina,
            job_control_prestamos_terceros.obligacion AS obligacion,
            job_control_prestamos_terceros.valor_tope_descuento AS valor_tope_descuento,
            job_control_prestamos_terceros.periodo_pago AS periodo_pago_prestamo,
            job_control_prestamos_terceros.valor_descontar_mensual AS valor_descontar_mensual,
            job_control_prestamos_terceros.valor_descontar_primera_quincena AS valor_descontar_primera_quincena,
            job_control_prestamos_terceros.valor_descontar_segunda_quincena AS valor_descontar_segunda_quincena,
            job_control_prestamos_terceros.valor_descontar_primera_semana AS valor_descontar_primera_semana,
            job_control_prestamos_terceros.valor_descontar_segunda_semana AS valor_descontar_segunda_semana,
            job_control_prestamos_terceros.valor_descontar_tercera_semana AS valor_descontar_tercera_semana,
            job_control_prestamos_terceros.valor_descontar_cuarta_semana AS valor_descontar_cuarta_semana,
            job_control_prestamos_terceros.descuento_ilimitado AS descuento_ilimitado,
            job_control_prestamos_terceros.fecha_limite_descuento AS fecha_limite_descuento,
            job_control_prestamos_terceros.estado AS estado,
            job_control_prestamos_terceros.codigo_usuario_registra AS codigo_usuario_registra,
            job_control_prestamos_terceros.codigo_usuario_modifica AS codigo_usuario_modifica,
            job_control_prestamos_terceros.transaccion_contable_descuento AS transaccion_contable_descuento,
            (
                SELECT descripcion
                FROM job_transacciones_contables_empleado
                WHERE job_transacciones_contables_empleado.codigo=job_control_prestamos_terceros.transaccion_contable_descuento
            ) AS descripcion_transaccion_contable_descuento,
            job_control_prestamos_terceros.transaccion_contable_empleado AS transaccion_contable_empleado,
            (
                SELECT descripcion
                FROM job_transacciones_contables_empleado
                WHERE job_transacciones_contables_empleado.codigo=job_control_prestamos_terceros.transaccion_contable_empleado
            ) AS descripcion_transaccion_contable_empleado,
            job_control_prestamos_terceros.transaccion_contable_pagar_tercero AS transaccion_contable_pagar_tercero,
            (
                SELECT descripcion
                FROM job_transacciones_contables_empleado
                WHERE job_transacciones_contables_empleado.codigo=job_control_prestamos_terceros.transaccion_contable_pagar_tercero
            ) AS descripcion_transaccion_contable_pagar_tercero,
            job_control_prestamos_terceros.transaccion_contable_pago_tercero AS transaccion_contable_pago_tercero,
            (
                SELECT descripcion
                FROM job_transacciones_contables_empleado
                WHERE job_transacciones_contables_empleado.codigo=job_control_prestamos_terceros.transaccion_contable_pago_tercero
            ) AS descripcion_transaccion_contable_pago_tercero,

            job_movimiento_cuenta_por_cobrar_descuento.ano_generacion AS ano_generacion,
            job_movimiento_cuenta_por_cobrar_descuento.mes_generacion AS mes_generacion,
            job_movimiento_cuenta_por_cobrar_descuento.codigo_planilla AS codigo_planilla,
            job_movimiento_cuenta_por_cobrar_descuento.periodo_pago AS periodo_pago,
            job_movimiento_cuenta_por_cobrar_descuento.fecha_pago_planilla AS fecha_pago_planilla,
            job_movimiento_cuenta_por_cobrar_descuento.codigo_sucursal AS codigo_sucursal_pago,
            job_movimiento_cuenta_por_cobrar_descuento.codigo_empresa_auxiliar AS codigo_empresa_auxiliar,
            job_movimiento_cuenta_por_cobrar_descuento.codigo_anexo_contable AS codigo_anexo_contable,
            job_movimiento_cuenta_por_cobrar_descuento.codigo_auxiliar_contable AS codigo_auxiliar_contable,
            job_movimiento_cuenta_por_cobrar_descuento.codigo_contable AS codigo_contable_cuenta_cobrar_descuento,
            (
                SELECT descripcion
                FROM job_plan_contable
                WHERE job_plan_contable.codigo_contable=job_movimiento_cuenta_por_cobrar_descuento.codigo_contable
            ) AS descripcion_codigo_contable_cuenta_cobrar_descuento,
            job_movimiento_cuenta_por_cobrar_descuento.sentido AS sentido_cuenta_cobrar_descuento,
            job_movimiento_cuenta_por_cobrar_descuento.valor_movimiento AS valor_descuento,
            job_movimiento_cuenta_por_cobrar_descuento.contabilizado AS contabilizado_cuenta_cobrar_descuento,
            job_movimiento_cuenta_por_cobrar_descuento.codigo_usuario_registra AS usuario_cuenta_cobrar_descuento,

            job_movimiento_cuenta_por_cobrar_empleado.codigo_contable AS codigo_contable_descuento_empleado,
            (
                SELECT descripcion
                FROM job_plan_contable
                WHERE job_plan_contable.codigo_contable=job_movimiento_cuenta_por_cobrar_empleado.codigo_contable
            ) AS descripcion_codigo_contable_cuenta_cobrar_empleado,
            job_movimiento_cuenta_por_cobrar_empleado.sentido AS sentido_descuento_empleado,
            job_movimiento_cuenta_por_cobrar_empleado.contabilizado AS contabilizado_descuento_empleado,

            job_movimiento_cuenta_por_pagar_tercero.codigo_contable AS codigo_contable_pagar_tercero,
            (
                SELECT descripcion
                FROM job_plan_contable
                WHERE job_plan_contable.codigo_contable=job_movimiento_cuenta_por_pagar_tercero.codigo_contable
            ) AS descripcion_codigo_contable_cuenta_pagar_tercero,
            job_movimiento_cuenta_por_pagar_tercero.sentido AS sentido_cuenta_pagar_tercero,
            job_movimiento_cuenta_por_pagar_tercero.contabilizado AS contabilizado_cuenta_pagar_tecero,
            (
                SELECT
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
                    )
                FROM
                    job_terceros
                WHERE
                    job_terceros.documento_identidad = job_control_prestamos_terceros.documento_identidad_empleado
            ) AS nombre_empleado,
            (
                SELECT
                    CONCAT(
                        IF(job_terceros.primer_nombre IS NOT NULL,
                            CONCAT(
                                CONCAT(job_terceros.primer_apellido,' '),
                                IF(job_terceros.segundo_apellido IS NOT NULL,CONCAT(job_terceros.segundo_apellido,' '),''),
                                IF(job_terceros.primer_nombre IS NOT NULL,CONCAT(job_terceros.primer_nombre,' '),''),
                                IF(job_terceros.segundo_nombre IS NOT NULL,CONCAT(job_terceros.segundo_nombre,''),'')
                            ),
                            job_terceros.razon_social
                        )
                    )
                FROM
                    job_terceros
                WHERE
                    job_terceros.documento_identidad = job_control_prestamos_terceros.documento_identidad_empleado
            ) AS apellido_empleado,
            (
                SELECT
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
                    )
                FROM
                    job_terceros
                WHERE
                    job_terceros.documento_identidad = job_control_prestamos_terceros.documento_identidad_tercero
            ) AS tercero
        FROM
            job_control_prestamos_terceros,
            job_movimiento_cuenta_por_cobrar_descuento,
            job_movimiento_cuenta_por_cobrar_empleado,
            job_movimiento_cuenta_por_pagar_tercero
        WHERE
            job_movimiento_cuenta_por_cobrar_descuento.codigo_empresa = job_control_prestamos_terceros.codigo_empresa AND
            job_movimiento_cuenta_por_cobrar_descuento.documento_identidad_empleado = job_control_prestamos_terceros.documento_identidad_empleado AND
            job_movimiento_cuenta_por_cobrar_descuento.obligacion = job_control_prestamos_terceros.obligacion AND

            job_movimiento_cuenta_por_cobrar_descuento.codigo_empresa = job_movimiento_cuenta_por_cobrar_empleado.codigo_empresa AND
            job_movimiento_cuenta_por_cobrar_descuento.documento_identidad_empleado = job_movimiento_cuenta_por_cobrar_empleado.documento_identidad_empleado AND
            job_movimiento_cuenta_por_cobrar_descuento.obligacion = job_movimiento_cuenta_por_cobrar_empleado.obligacion AND
            job_movimiento_cuenta_por_cobrar_descuento.fecha_pago_planilla = job_movimiento_cuenta_por_cobrar_empleado.fecha_pago_planilla AND

            job_movimiento_cuenta_por_cobrar_descuento.codigo_empresa = job_movimiento_cuenta_por_pagar_tercero.codigo_empresa AND
            job_movimiento_cuenta_por_cobrar_descuento.documento_identidad_empleado = job_movimiento_cuenta_por_pagar_tercero.documento_identidad_empleado AND
            job_movimiento_cuenta_por_cobrar_descuento.obligacion = job_movimiento_cuenta_por_pagar_tercero.obligacion AND
            job_movimiento_cuenta_por_cobrar_descuento.fecha_pago_planilla = job_movimiento_cuenta_por_pagar_tercero.fecha_pago_planilla
        "
    ),
    array("CREATE OR REPLACE ALGORITHM = MERGE VIEW job_consulta_prestamos_terceros AS
        SELECT
            job_control_prestamos_terceros.limite_descuento AS limite_descuento,
            job_control_prestamos_terceros.fecha_generacion AS fecha_generacion,
            job_control_prestamos_terceros.fecha_inicio_descuento AS fecha_inicio_descuento,
            job_control_prestamos_terceros.codigo_sucursal AS codigo_sucursal,
            job_control_prestamos_terceros.codigo_empresa AS codigo_empresa,
            (job_control_prestamos_terceros.documento_identidad_empleado * 1) AS documento_identidad_empleado,
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
            ) AS apellido_empleado,
            job_control_prestamos_terceros.documento_identidad_tercero AS documento_identidad_tercero,
            job_control_prestamos_terceros.autorizacion_descuento_nomina AS autorizacion_descuento_nomina,
            job_control_prestamos_terceros.obligacion AS obligacion,
            job_control_prestamos_terceros.valor_tope_descuento AS valor_tope_descuento,
            job_control_prestamos_terceros.periodo_pago AS periodo_pago,
            job_control_prestamos_terceros.valor_descontar_mensual AS valor_descontar_mensual,
            job_control_prestamos_terceros.valor_descontar_primera_quincena AS valor_descontar_primera_quincena,
            job_control_prestamos_terceros.valor_descontar_segunda_quincena AS valor_descontar_segunda_quincena,
            job_control_prestamos_terceros.valor_descontar_primera_semana AS valor_descontar_primera_semana,
            job_control_prestamos_terceros.valor_descontar_segunda_semana AS valor_descontar_segunda_semana,
            job_control_prestamos_terceros.valor_descontar_tercera_semana AS valor_descontar_tercera_semana,
            job_control_prestamos_terceros.valor_descontar_cuarta_semana AS valor_descontar_cuarta_semana,
            job_control_prestamos_terceros.descuento_ilimitado AS descuento_ilimitado,
            job_control_prestamos_terceros.fecha_limite_descuento AS fecha_limite_descuento,
            job_control_prestamos_terceros.estado AS estado,
            job_control_prestamos_terceros.codigo_usuario_registra AS codigo_usuario_registra,
            job_control_prestamos_terceros.codigo_usuario_modifica AS codigo_usuario_modifica,
            job_control_prestamos_terceros.transaccion_contable_descuento AS transaccion_contable_descuento,
            job_control_prestamos_terceros.transaccion_contable_empleado AS transaccion_contable_empleado,
            job_control_prestamos_terceros.transaccion_contable_pagar_tercero AS transaccion_contable_pagar_tercero,
            job_control_prestamos_terceros.transaccion_contable_pago_tercero AS transaccion_contable_pago_tercero
        FROM
         job_control_prestamos_terceros,
         job_terceros
        WHERE
            job_control_prestamos_terceros.documento_identidad_empleado=job_terceros.documento_identidad;"
    )
);

?>

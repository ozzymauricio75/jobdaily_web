<?php
/**
*
* Copyright (C) 2020 Jobdaily
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

$borrarSiempre = true;

$tablas["forma_pago_planillas_nomina"] = array(
    ///////////////////////////
    "codigo_sucursal"                                   => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la sucursal que recibe el pago'",
    "ano_generacion"                                    => "INT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código interno que identifica la planilla'",
    "mes_generacion"                                    => "INT(2) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código interno que identifica la planilla'",
    "codigo_planilla"                                   => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código interno que identifica la planilla'",
    "periodo_pago"                                      => "ENUM('1','2','3','4','5','6','7','8','9') NOT NULL COMMENT '1->Mensual 2->Primera quincena 3->Segunda quincena 4->Primera semana 5->Segunda semana 6->Tercer semana 7->Cuarta semana 8->Quinta semana 9->Fecha unica'",
    ///////////////////////
    "codigo_sucursal_genera"                            => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la sucursal que genera el pago'",
    "fecha_pago_planilla"                               => "DATE NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado'",
    "valor_movimiento"                                  => "DECIMAL(11,2) NOT NULL COMMENT 'Valor diario del registro'",
    "codigo_contable"                                   => "VARCHAR(15) NOT NULL COMMENT 'Codigo de la cuenta contable segun el PUC'",
    "sentido"                                           => "ENUM('D','C') NOT NULL COMMENT 'D->Debito C->Credito'",
    "codigo_empresa_auxiliar"                           => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa para la llave de auxiliares'",
    "codigo_anexo_contable"                             => "VARCHAR(3) NOT NULL COMMENT 'Codigo del anexo que permite dividir las cuentas'",
    "codigo_auxiliar_contable"                          => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo donde se acumulara la informacion'",
    ///llave_consecutivo docuemento
    //"codigo_sucursal" Este campo forma parte de la llave primaria de la tabla actual
    "codigo_tipo_documento"                             => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo del tipo de documento'",
    "documento_identidad_tercero"                       => "VARCHAR(12) NOT NULL COMMENT 'Número del documento de identidad'",
    "fecha_consecutivo"                                 => "DATE NOT NULL COMMENT 'Fecha de generacion del consecutivo documento'",
    "consecutivo"                                       => "INT(9) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo del documento'",
    //////////
    "fecha_registro"                                    => "DATETIME NOT NULL COMMENT 'Fecha en que se genera el registro'",
    "codigo_usuario_registra"                           => "SMALLINT(4) UNSIGNED ZEROFILL  NOT NULL COMMENT 'Codigo del usuario que genera el registro'",
    "pagada"                                            => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT 'Pagada 0->No 1->Si'"
);

$tablas["forma_pago_planillas_efectivo"] = array(
    "codigo_sucursal"                                   => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'tabla planilla de la tabla de sucursales'",
    "ano_generacion"                                    => "INT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código interno que identifica la planilla'",
    "mes_generacion"                                    => "INT(2) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código interno que identifica la planilla'",
    "codigo_planilla"                                   => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código interno que identifica la planilla'",
    "periodo_pago"                                      => "ENUM('1','2','3','4','5','6','7','8','9') NOT NULL COMMENT '1->Mensual 2->Primera quincena 3->Segunda quincena 4->Primera semana 5->Segunda semana 6->Tercer semana 7->Cuarta semana 8->Quinta semana 9->Fecha unica'",
    "fecha_pago_planilla"                               => "DATE NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado'",
    "valor_movimiento"                                  => "DECIMAL(11,2) NOT NULL COMMENT 'Valor diario del registro'",
    "codigo_contable"                                   => "VARCHAR(15) NOT NULL COMMENT 'Codigo de la cuenta contable segun el PUC'",
    "sentido"                                           => "ENUM('D','C') NOT NULL COMMENT 'D->Debito C->Credito'",
    "codigo_empresa_auxiliar"                           => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa para la llave de auxiliares'",
    "codigo_anexo_contable"                             => "VARCHAR(3) NOT NULL COMMENT 'Codigo del anexo que permite dividir las cuentas'",
    "codigo_auxiliar_contable"                          => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo donde se acumulara la informacion'",
    ///llave_consecutivo docuemento/////
    //"codigo_sucursal" Este campo forma parte de la llave primaria de la tabla actual
    "codigo_tipo_documento"                             => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo del tipo de documento'",
    "documento_identidad_tercero"                       => "VARCHAR(12) NOT NULL COMMENT 'Número del documento de identidad'",
    "fecha_consecutivo"                                 => "DATE NOT NULL COMMENT 'Fecha de generacion del consecutivo documento'",
    "consecutivo"                                       => "INT(9) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo del documento'",
    //////////
    "fecha_registro"                                    => "DATETIME NOT NULL COMMENT 'Fecha en que se genera el registro'",
    "codigo_usuario_registra"                           => "SMALLINT(4) UNSIGNED ZEROFILL  NOT NULL COMMENT 'Codigo del usuario que genera el registro'",
    "pagada"                                            => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT 'Pagada 0->No 1->Si'"
);

$tablas["forma_pago_planillas_sucursal"] = array(
    "codigo_sucursal"                                   => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'tabla planilla de la tabla de sucursales'",
    "ano_generacion"                                    => "INT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código interno que identifica la planilla'",
    "mes_generacion"                                    => "INT(2) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código interno que identifica la planilla'",
    "codigo_planilla"                                   => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código interno que identifica la planilla'",
    "periodo_pago"                                      => "ENUM('1','2','3','4','5','6','7','8','9') NOT NULL COMMENT '1->Mensual 2->Primera quincena 3->Segunda quincena 4->Primera semana 5->Segunda semana 6->Tercer semana 7->Cuarta semana 8->Quinta semana 9->Fecha unica'",
    "fecha_pago_planilla"                               => "DATE NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado'",
    "valor_movimiento"                                  => "DECIMAL(11,2) NOT NULL COMMENT 'Valor diario del registro'",
    "codigo_contable"                                   => "VARCHAR(15) NOT NULL COMMENT 'Codigo de la cuenta contable segun el PUC'",
    "sentido"                                           => "ENUM('D','C') NOT NULL COMMENT 'D->Debito C->Credito'",
    "codigo_empresa_auxiliar"                           => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa para la llave de auxiliares'",
    "codigo_anexo_contable"                             => "VARCHAR(3) NOT NULL COMMENT 'Codigo del anexo que permite dividir las cuentas'",
    "codigo_auxiliar_contable"                          => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo donde se acumulara la informacion'",
     //////////////////////////
    //"codigo_sucursal"
    "codigo_tipo_documento"                             => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código asignado por el usuario'",
    "codigo_sucursal_banco"                             => "SMALLINT(2) UNSIGNED ZEROFILL NOT NULL COMMENT 'codigo de la tabla bancos'",
    "codigo_iso"                                        => "VARCHAR(2) NOT NULL COMMENT 'Llave principal de la tabla paises'",
    "codigo_dane_departamento"                          => "VARCHAR(2) NOT NULL COMMENT 'Código DANE'",
    "codigo_dane_municipio"                             => "VARCHAR(3) NOT NULL COMMENT 'Código DANE'",
    "codigo_banco"                                      => "SMALLINT(2) UNSIGNED ZEROFILL NOT NULL COMMENT 'id de la tabla bancos'",
    "numero_cuenta"                                     => "VARCHAR(30) NOT NULL COMMENT 'Numero de la cuenta'",
    /////////////////////////////////////
    //"codigo_sucursal" Este campo forma parte de la llave primaria de la tabla actual
    //"codigo_tipo_documento"
    "documento_identidad_tercero"                       => "VARCHAR(12) NOT NULL COMMENT 'Número del documento de identidad'",
    "fecha_consecutivo"                                 => "DATE NOT NULL COMMENT 'Fecha de generacion del consecutivo documento'",
    "consecutivo_documento"                             => "INT(9) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo del documento'",
     ////llave de consecutivo cheque//////
    //"codigo_sucursal"
    //"codigo_tipo_documento"                             => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código asignado por el usuario'",
    //"codigo_banco"                                      => "SMALLINT(2) UNSIGNED ZEROFILL NOT NULL COMMENT 'id de la tabla bancos'",
    //"numero_cuenta"                                     => "VARCHAR(30) NOT NULL COMMENT 'Numero de la cuenta'",
    "consecutivo_cheque"                                => "INT(9) UNSIGNED ZEROFILL NOT NULL COMMENT 'consecutivo del cheque'",
     //////////
    "fecha_registro"                                    => "DATETIME NOT NULL COMMENT 'Fecha en que se genera el registro'",
    "codigo_usuario_registra"                           => "SMALLINT(4) UNSIGNED ZEROFILL  NOT NULL COMMENT 'Codigo del usuario que genera el registro'",
    "pagada"                                            => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT 'Pagada 0->No 1->Si'"
);

$tablas["forma_pago_planillas_empleado"] = array(
    "codigo_sucursal"                                   => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'tabla planilla de la tabla de sucursales'",
    "ano_generacion"                                    => "INT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código interno que identifica la planilla'",
    "mes_generacion"                                    => "INT(2) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código interno que identifica la planilla'",
    "codigo_planilla"                                   => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código interno que identifica la planilla'",
    "periodo_pago"                                      => "ENUM('1','2','3','4','5','6','7','8','9') NOT NULL COMMENT '1->Mensual 2->Primera quincena 3->Segunda quincena 4->Primera semana 5->Segunda semana 6->Tercer semana 7->Cuarta semana 8->Quinta semana 9->Fecha unica'",
    "documento_identidad_empleado"                      => "VARCHAR(12) NOT NULL COMMENT 'Documento de identidad que identifica el empleado en terceras personas'",
    //////////////////////////
    "codigo_empresa"                                    => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo del almacen donde va a laborar'",
    "fecha_ingreso_empresa"                             => "DATE NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado'",
    "fecha_ingreso_sucursal"                            => "DATE NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado'",
    //////////////////////////
    "fecha_pago_planilla"                               => "DATE NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado'",
    "valor_movimiento"                                  => "DECIMAL(11,2) NOT NULL COMMENT 'Valor diario del registro'",
    "codigo_contable"                                   => "VARCHAR(15) NOT NULL COMMENT 'Codigo de la cuenta contable segun el PUC'",
    "sentido"                                           => "ENUM('D','C') NOT NULL COMMENT 'D->Debito C->Credito'",
    "codigo_empresa_auxiliar"                           => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa para la llave de auxiliares'",
    "codigo_anexo_contable"                             => "VARCHAR(3) NOT NULL COMMENT 'Codigo del anexo que permite dividir las cuentas'",
    "codigo_auxiliar_contable"                          => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo donde se acumulara la informacion'",
    ////////////////////////////
    //"codigo_sucursal"
    "codigo_tipo_documento"                             => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código asignado por el usuario'",
    "codigo_sucursal_banco"                             => "SMALLINT(2) UNSIGNED ZEROFILL NOT NULL COMMENT 'codigo de la tabla bancos'",
    "codigo_iso"                                        => "VARCHAR(2) NOT NULL COMMENT 'Llave principal de la tabla paises'",
    "codigo_dane_departamento"                          => "VARCHAR(2) NOT NULL COMMENT 'Código DANE'",
    "codigo_dane_municipio"                             => "VARCHAR(3) NOT NULL COMMENT 'Código DANE'",
    "codigo_banco"                                      => "SMALLINT(2) UNSIGNED ZEROFILL NOT NULL COMMENT 'id de la tabla bancos'",
    "numero_cuenta"                                     => "VARCHAR(30) NOT NULL COMMENT 'Numero de la cuenta'",
    /////////////////////////////////////
    ///llave_consecutivo docuemento/////
    //"codigo_sucursal"
    //"codigo_tipo_documento"                             => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código asignado por el usuario'",
    "documento_identidad_tercero"                       => "VARCHAR(12) NOT NULL COMMENT 'Número del documento de identidad'",
    "fecha_consecutivo"                                 => "DATE NOT NULL COMMENT 'Fecha de generacion del consecutivo documento'",
    "consecutivo_documento"                             => "INT(9) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo del documento'",
    ////llave de consecutivo cheque//////
    //"codigo_banco"
    "consecutivo_cheque"                                => "INT(9) UNSIGNED ZEROFILL NOT NULL COMMENT 'consecutivo del cheque'",
     //////////
    "fecha_registro"                                    => "DATETIME NOT NULL COMMENT 'Fecha en que se genera el registro'",
    "codigo_usuario_registra"                           => "SMALLINT(4) UNSIGNED ZEROFILL  NOT NULL COMMENT 'Codigo del usuario que genera el registro'",
    "pagada"                                            => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT 'Pagada 0->No 1->Si'"
);

$tablas["cancelacion_nomina_por_pagar_salud_empleado"] = array(
    "codigo_sucursal"               => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo del almacen donde va a laborar'",
    "ano_generacion"                => "INT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo interno que identifica la planilla'",
    "mes_generacion"                => "INT(2) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo interno que identifica la planilla'",
    "codigo_planilla"               => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo interno que identifica la planilla'",
    "periodo_pago"                  => "ENUM('1','2','3','4','5','6','7','8','9') NOT NULL COMMENT '1->Mensual 2->Primera quincena 3->Segunda quincena 4->Primera semana 5->Segunda semana 6->Tercer semana 7->Cuarta semana 8->Quinta semana 9->Fecha unica'",
    "documento_identidad_empleado"  => "VARCHAR(12) NOT NULL COMMENT 'Documento de identidad que identifica el empleado en terceras personas'",
    "codigo_empresa"                => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo del almacen donde va a laborar'",
    "fecha_ingreso_empresa"         => "DATE NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado'",
    "fecha_ingreso_sucursal"        => "DATE NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado'",
    "fecha_pago_planilla"           => "DATE NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado'",
    "valor_movimiento"              => "DECIMAL(11,2) NOT NULL COMMENT 'Valor del movimiento'",
    "codigo_transaccion_contable"   => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id de la tabla de transacciones contables'",
    "codigo_contable"               => "VARCHAR(15) NOT NULL COMMENT 'Codigo contable de la transaccion contable'",
    "sentido"                       => "ENUM('D','C') NOT NULL COMMENT 'D->Debito C->Credito'",
    "codigo_empresa_auxiliar"       => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa del auxiliar contable'",
    "codigo_anexo_contable"         => "VARCHAR(3) NOT NULL COMMENT 'Codigo del anexo que permite dividir las cuentas'",
    "codigo_auxiliar_contable"      => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo donde se acumulara la informacion'",
    "fecha_generacion"              => "DATETIME NOT NULL COMMENT 'Fecha de generación del registro'",
    "codigo_usuario_registra"       => "SMALLINT(4) UNSIGNED ZEROFILL  NOT NULL COMMENT 'Codigo usuario que genera el registro'"
);

$tablas["cuenta_por_pagar_salud_entidad"] = array(
    "codigo_sucursal"               => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo del almacen donde va a laborar'",
    "ano_generacion"                => "INT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo interno que identifica la planilla'",
    "mes_generacion"                => "INT(2) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo interno que identifica la planilla'",
    "codigo_planilla"               => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo interno que identifica la planilla'",
    "periodo_pago"                  => "ENUM('1','2','3','4','5','6','7','8','9') NOT NULL COMMENT '1->Mensual 2->Primera quincena 3->Segunda quincena 4->Primera semana 5->Segunda semana 6->Tercer semana 7->Cuarta semana 8->Quinta semana 9->Fecha unica'",
    "documento_identidad_empleado"  => "VARCHAR(12) NOT NULL COMMENT 'Documento de identidad que identifica el empleado en terceras personas'",
    "documento_identidad_entidad"   => "VARCHAR(12) NOT NULL COMMENT 'Documento de identidad de la entidad de salud'",
    "codigo_empresa"                => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo del almacen donde va a laborar'",
    "fecha_ingreso_empresa"         => "DATE NOT NULL COMMENT 'Fecha en la que inicia labores en la empresa el empleado'",
    "fecha_ingreso_sucursal"        => "DATE NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado'",
    "codigo_entidad_parafiscal"     => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la entidad parafiscal'",
    "fecha_pago_planilla"           => "DATE NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado'",
    "valor_movimiento"              => "DECIMAL(11,2) NOT NULL COMMENT 'Valor del movimiento'",
    "codigo_transaccion_contable"   => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id de la tabla de transacciones contables'",
    "codigo_contable"               => "VARCHAR(15) NOT NULL COMMENT 'Codigo contable de la transaccion contable'",
    "sentido"                       => "ENUM('D','C') NOT NULL COMMENT 'D->Debito C->Credito'",
    "codigo_empresa_auxiliar"       => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa del auxiliar contable'",
    "codigo_anexo_contable"         => "VARCHAR(3) NOT NULL COMMENT 'Codigo del anexo que permite dividir las cuentas'",
    "codigo_auxiliar_contable"      => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo donde se acumulara la informacion'",
    "fecha_generacion"              => "DATETIME NOT NULL COMMENT 'Fceha de generación del registro'",
    "codigo_usuario_registra"         => "SMALLINT(4) UNSIGNED ZEROFILL  NOT NULL COMMENT 'Codigo usuario que genera el registro'"
);

$tablas["cancelacion_nomina_por_pagar_pension_empleado"] = array(
    "codigo_sucursal"               => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo del almacen donde va a laborar'",
    "ano_generacion"                => "INT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo interno que identifica la planilla'",
    "mes_generacion"                => "INT(2) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo interno que identifica la planilla'",
    "codigo_planilla"               => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo interno que identifica la planilla'",
    "periodo_pago"                  => "ENUM('1','2','3','4','5','6','7','8','9') NOT NULL COMMENT '1->Mensual 2->Primera quincena 3->Segunda quincena 4->Primera semana 5->Segunda semana 6->Tercer semana 7->Cuarta semana 8->Quinta semana 9->Fecha unica'",
    "documento_identidad_empleado"  => "VARCHAR(12) NOT NULL COMMENT 'Documento de identidad que identifica el empleado en terceras personas'",
    "codigo_empresa"                => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo del almacen donde va a laborar'",
    "fecha_ingreso_empresa"         => "DATE NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado'",
    "fecha_ingreso_sucursal"        => "DATE NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado'",
    "fecha_pago_planilla"           => "DATE NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado'",
    "valor_movimiento"              => "DECIMAL(11,2) NOT NULL COMMENT 'Valor del movimiento'",
    "codigo_transaccion_contable"   => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id de la tabla de transacciones contables'",
    "codigo_contable"               => "VARCHAR(15) NOT NULL COMMENT 'Codigo contable de la transaccion contable'",
    "sentido"                       => "ENUM('D','C') NOT NULL COMMENT 'D->Debito C->Credito'",
    "codigo_empresa_auxiliar"       => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa del auxiliar contable'",
    "codigo_anexo_contable"         => "VARCHAR(3) NOT NULL COMMENT 'Codigo del anexo que permite dividir las cuentas'",
    "codigo_auxiliar_contable"      => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo donde se acumulara la informacion'",
    "fecha_registro"                => "DATETIME NOT NULL COMMENT 'Fecha de generación del registro'",
    "codigo_usuario_registra"         => "SMALLINT(4) UNSIGNED ZEROFILL  NOT NULL COMMENT 'Codigo usuario que genera el registro'"
);

$tablas["cuenta_por_pagar_pension_entidad"] = array(
    "codigo_sucursal"               => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo del almacen donde va a laborar'",
    "ano_generacion"                => "INT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo interno que identifica la planilla'",
    "mes_generacion"                => "INT(2) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo interno que identifica la planilla'",
    "codigo_planilla"               => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo interno que identifica la planilla'",
    "periodo_pago"                  => "ENUM('1','2','3','4','5','6','7','8','9') NOT NULL COMMENT '1->Mensual 2->Primera quincena 3->Segunda quincena 4->Primera semana 5->Segunda semana 6->Tercer semana 7->Cuarta semana 8->Quinta semana 9->Fecha unica'",
    "documento_identidad_empleado"  => "VARCHAR(12) NOT NULL COMMENT 'Documento de identidad que identifica el empleado en terceras personas'",
    "fecha_pago_planilla"           => "DATE NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado'",
    "documento_identidad_entidad"   => "VARCHAR(12) NOT NULL COMMENT 'Documento de identidad de la entidad de pension'",
    "codigo_empresa"                => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo del almacen donde va a laborar'",
    "fecha_ingreso_empresa"         => "DATE NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado'",
    "fecha_ingreso_sucursal"        => "DATE NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado'",
    "codigo_entidad_parafiscal"     => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la entidad parafiscal'",
    "codigo_transaccion_contable"   => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id de la tabla de transacciones contables'",
    "codigo_contable"               => "VARCHAR(15) NOT NULL COMMENT 'Codigo contable de la transaccion contable'",
    "sentido"                       => "ENUM('D','C') NOT NULL COMMENT 'D->Debito C->Credito'",
    "codigo_empresa_auxiliar"       => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa del auxiliar contable'",
    "codigo_anexo_contable"         => "VARCHAR(3) NOT NULL COMMENT 'Codigo del anexo que permite dividir las cuentas'",
    "codigo_auxiliar_contable"      => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo donde se acumulara la informacion'",
    "valor_movimiento"              => "DECIMAL(11,2) NOT NULL COMMENT 'Valor del movimiento'",
    "fecha_registro"                => "DATETIME NOT NULL COMMENT 'Fecha de generación del registro'",
    "codigo_usuario_registra"         => "SMALLINT(4) UNSIGNED ZEROFILL  NOT NULL COMMENT 'Codigo usuario que genera el registro'"
);

$llavesPrimarias["forma_pago_planillas_nomina"]   = "codigo_sucursal,ano_generacion,mes_generacion,codigo_planilla,periodo_pago";
$llavesPrimarias["forma_pago_planillas_efectivo"] = "codigo_sucursal,ano_generacion,mes_generacion,codigo_planilla,periodo_pago";
$llavesPrimarias["forma_pago_planillas_sucursal"] = "codigo_sucursal,ano_generacion,mes_generacion,codigo_planilla,periodo_pago";
$llavesPrimarias["forma_pago_planillas_empleado"] = "codigo_sucursal,ano_generacion,mes_generacion,codigo_planilla,periodo_pago,documento_identidad_empleado";

$llavesPrimarias["cancelacion_nomina_por_pagar_salud_empleado"] = "codigo_sucursal,ano_generacion,mes_generacion,codigo_planilla,periodo_pago,documento_identidad_empleado";

$llavesPrimarias["cuenta_por_pagar_salud_entidad"] = "codigo_sucursal,ano_generacion,mes_generacion,codigo_planilla,periodo_pago,documento_identidad_empleado";

$llavesPrimarias["cancelacion_nomina_por_pagar_pension_empleado"] = "codigo_sucursal,ano_generacion,mes_generacion,codigo_planilla,periodo_pago,documento_identidad_empleado";

$llavesPrimarias["cuenta_por_pagar_pension_entidad"] = "codigo_sucursal,ano_generacion,mes_generacion,codigo_planilla,periodo_pago,documento_identidad_empleado";

$llavesForaneas["forma_pago_planillas_nomina"] = array(
    array(
        //  Nombre de la llave foranea
        "forma_pago_planillas_nomina_sucursal",
        //  Nombre del campo en la tabla actual
        "codigo_sucursal",
        //  Nombre de la tabla relacionada
        "sucursales",
        //  Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "forma_pago_planillas_nomina_planilla",
        // Nombre del campo en la tabla actual
        "codigo_planilla",
        // Nombre de la tabla relacionada
        "planillas",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "forma_pago_planillas_nomina_auxiliar_contable",
        // Nombre del campo en la tabla actual
        "codigo_empresa_auxiliar,codigo_anexo_contable,codigo_auxiliar_contable",
        // Nombre de la tabla relacionada
        "auxiliares_contables",
        // Nombre del campo de la tabla relacionada
        "codigo_empresa,codigo_anexo_contable,codigo"
    ),
    array(
        // Nombre de la llave foranea
        "forma_pago_planillas_nomina_plan_contable",
        // Nombre del campo en la tabla actual
        "codigo_contable",
        // Nombre de la tabla relacionada
        "plan_contable",
        // Nombre del campo de la tabla relacionada
        "codigo_contable"
    ),
    array(
        // Nombre de la llave foranea
        "forma_pago_planillas_nomina_consecutivo",
        // Nombre del campo en la tabla actual
        "codigo_sucursal,codigo_tipo_documento,documento_identidad_tercero,fecha_consecutivo,consecutivo",
        // Nombre de la tabla relacionada
        "consecutivo_documentos",
        // Nombre del campo de la tabla relacionada
        "codigo_sucursal,codigo_tipo_documento,documento_identidad_tercero,fecha_registro,consecutivo"
    ),
    array(
        // Nombre de la llave foranea
        "forma_pago_planillas_nomina_usuario",
        // Nombre del campo en la tabla actual
        "codigo_usuario_registra",
        // Nombre de la tabla relacionada
        "usuarios",
        // Nombre del campo de la tabla relacionada
        "codigo"
    )
);

$llavesForaneas["forma_pago_planillas_efectivo"] = array(
    array(
        //  Nombre de la llave foranea
        "forma_pago_planillas_efectivo_sucursal",
        //  Nombre del campo en la tabla actual
        "codigo_sucursal",
        //  Nombre de la tabla relacionada
        "sucursales",
        //  Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "forma_pago_planillas_efectivo_planilla",
        // Nombre del campo en la tabla actual
        "codigo_planilla",
        // Nombre de la tabla relacionada
        "planillas",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "forma_pago_planillas_efectivo_auxiliar_contable",
        // Nombre del campo en la tabla actual
        "codigo_empresa_auxiliar,codigo_anexo_contable,codigo_auxiliar_contable",
        // Nombre de la tabla relacionada
        "auxiliares_contables",
        // Nombre del campo de la tabla relacionada
        "codigo_empresa,codigo_anexo_contable,codigo"
    ),
    array(
        // Nombre de la llave foranea
        "forma_pago_planillas_efectivo_plan_contable",
        // Nombre del campo en la tabla actual
        "codigo_contable",
        // Nombre de la tabla relacionada
        "plan_contable",
        // Nombre del campo de la tabla relacionada
        "codigo_contable"
    ),
    array(
        // Nombre de la llave foranea
        "forma_pago_planillas_efectivo_consecutivo",
        // Nombre del campo en la tabla actual
        "codigo_sucursal,codigo_tipo_documento,documento_identidad_tercero,fecha_consecutivo,consecutivo",
        // Nombre de la tabla relacionada
        "consecutivo_documentos",
        // Nombre del campo de la tabla relacionada
        "codigo_sucursal,codigo_tipo_documento,documento_identidad_tercero,fecha_registro,consecutivo"
    ),
    array(
        // Nombre de la llave foranea
        "forma_pago_planillas_efectivo_usuario",
        // Nombre del campo en la tabla actual
        "codigo_usuario_registra",
        // Nombre de la tabla relacionada
        "usuarios",
        // Nombre del campo de la tabla relacionada
        "codigo"
    )
);

$llavesForaneas["forma_pago_planillas_sucursal"] = array(
    array(
        //  Nombre de la llave foranea
        "forma_pago_planillas_sucursal_sucursal",
        //  Nombre del campo en la tabla actual
        "codigo_sucursal",
        //  Nombre de la tabla relacionada
        "sucursales",
        //  Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        //  Nombre de la llave foranea
        "forma_pago_planillas_sucursal_planilla",
        //  Nombre del campo en la tabla actual
        "codigo_planilla",
        //  Nombre de la tabla relacionada
        "planillas",
        //  Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "forma_pago_planillas_sucursal_cuentas_bancarias",
        // Nombre del campo en la tabla actual
        "codigo_sucursal,codigo_tipo_documento,codigo_sucursal_banco,codigo_iso,codigo_dane_departamento,codigo_dane_municipio,codigo_banco,numero_cuenta",
        // Nombre de la tabla relacionada
        "cuentas_bancarias",
        // Nombre del campo de la tabla relacionada
        "codigo_sucursal,codigo_tipo_documento,codigo_sucursal_banco,codigo_iso,codigo_dane_departamento,codigo_dane_municipio,codigo_banco,numero"
    ),
    array(
        // Nombre de la llave foranea
        "forma_pago_planillas_sucursal_consecutivo_documento",
        // Nombre del campo en la tabla actual
        "codigo_sucursal,codigo_tipo_documento,documento_identidad_tercero,fecha_consecutivo,consecutivo_documento",
        // Nombre de la tabla relacionada
        "consecutivo_documentos",
        // Nombre del campo de la tabla relacionada
        "codigo_sucursal,codigo_tipo_documento,documento_identidad_tercero,fecha_registro,consecutivo"
    ),
    array(
        // Nombre de la llave foranea
        "forma_pago_planillas_empleado_consecutivo_cheque",
        // Nombre del campo en la tabla actual
        "codigo_sucursal,codigo_tipo_documento,codigo_banco,numero_cuenta,consecutivo_cheque",
        // Nombre de la tabla relacionada
        "consecutivo_cheques",
        // Nombre del campo de la tabla relacionada
        "codigo_sucursal,codigo_tipo_documento,codigo_banco,numero,consecutivo"
    ),
    array(
        // Nombre de la llave foranea
        "forma_pago_planillas_sucursal_auxiliar_contable",
        // Nombre del campo en la tabla actual
        "codigo_empresa_auxiliar,codigo_anexo_contable,codigo_auxiliar_contable",
        // Nombre de la tabla relacionada
        "auxiliares_contables",
        // Nombre del campo de la tabla relacionada
        "codigo_empresa,codigo_anexo_contable,codigo"
    ),
    array(
        // Nombre de la llave foranea
        "forma_pago_planillas_sucursal_plan_contable",
        // Nombre del campo en la tabla actual
        "codigo_contable",
        // Nombre de la tabla relacionada
        "plan_contable",
        // Nombre del campo de la tabla relacionada
        "codigo_contable"
    ),
    array(
        // Nombre de la llave foranea
        "forma_pago_planillas_sucursal_consecutivo_cheque",
        // Nombre del campo en la tabla actual
        "codigo_sucursal,codigo_tipo_documento,codigo_banco,numero_cuenta,consecutivo_cheque",
        // Nombre de la tabla relacionada
        "consecutivo_cheques",
        // Nombre del campo de la tabla relacionada
        "codigo_sucursal,codigo_tipo_documento,codigo_banco,numero,consecutivo"
    ),
    array(
        // Nombre de la llave foranea
        "forma_pago_planillas_sucursal_usuario",
        // Nombre del campo en la tabla actual
        "codigo_usuario_registra",
        // Nombre de la tabla relacionada
        "usuarios",
        // Nombre del campo de la tabla relacionada
        "codigo"
    )
);


$llavesForaneas["forma_pago_planillas_empleado"] = array(
    array(
        //  Nombre de la llave foranea
        "forma_pago_planillas_empleado_sucursal",
        //  Nombre del campo en la tabla actual
        "codigo_sucursal",
        //  Nombre de la tabla relacionada
        "sucursales",
        //  Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "forma_pago_planillas_empleado_cuentas_bancarias",
        // Nombre del campo en la tabla actual
        "codigo_sucursal,codigo_tipo_documento,codigo_sucursal_banco,codigo_iso,codigo_dane_departamento,codigo_dane_municipio,codigo_banco,numero_cuenta",
        // Nombre de la tabla relacionada
        "cuentas_bancarias",
        // Nombre del campo de la tabla relacionada
        "codigo_sucursal,codigo_tipo_documento,codigo_sucursal_banco,codigo_iso,codigo_dane_departamento,codigo_dane_municipio,codigo_banco,numero"
    ),
    array(
        // Nombre de la llave foranea
        "forma_pago_planillas_empleado_consecutivo_documento",
        // Nombre del campo en la tabla actual
        "codigo_sucursal,codigo_tipo_documento,documento_identidad_tercero,fecha_consecutivo,consecutivo_documento",
        // Nombre de la tabla relacionada
        "consecutivo_documentos",
        // Nombre del campo de la tabla relacionada
        "codigo_sucursal,codigo_tipo_documento,documento_identidad_tercero,fecha_registro,consecutivo"
    ),
    array(
        // Nombre de la llave foranea
        "forma_pago_planillas_empleado_sucursal_contrato_empleado",
        // Nombre del campo en la tabla actual
        "codigo_empresa,documento_identidad_empleado,fecha_ingreso_empresa,codigo_sucursal,fecha_ingreso_sucursal",
        // Nombre de la tabla relacionada
        "sucursal_contrato_empleados",
        // Nombre del campo de la tabla relacionada
        "codigo_empresa,documento_identidad_empleado,fecha_ingreso,codigo_sucursal,fecha_ingreso_sucursal"
    ),
    array(
        // Nombre de la llave foranea
        "forma_pago_planillas_empleado_consecutivo_cheque",
        // Nombre del campo en la tabla actual
        "codigo_sucursal,codigo_tipo_documento,codigo_banco,numero_cuenta,consecutivo_cheque",
        // Nombre de la tabla relacionada
        "consecutivo_cheques",
        // Nombre del campo de la tabla relacionada
        "codigo_sucursal,codigo_tipo_documento,codigo_banco,numero,consecutivo"
    ),
    array(
        // Nombre de la llave foranea
        "forma_pago_planillas_empleado_auxiliar_contable",
        // Nombre del campo en la tabla actual
        "codigo_empresa_auxiliar,codigo_anexo_contable,codigo_auxiliar_contable",
        // Nombre de la tabla relacionada
        "auxiliares_contables",
        // Nombre del campo de la tabla relacionada
        "codigo_empresa,codigo_anexo_contable,codigo"
    ),
    array(
        // Nombre de la llave foranea
        "forma_pago_planillas_empleado_usuario",
        // Nombre del campo en la tabla actual
        "codigo_usuario_registra",
        // Nombre de la tabla relacionada
        "usuarios",
        // Nombre del campo de la tabla relacionada
        "codigo"
    )
);

$llavesForaneas["cancelacion_nomina_por_pagar_salud_empleado"] = array(
    array(
        //Nombre de la relación
        "cancelacion_movimiento_salud_empleado",
        //Campo en la tabla actual
        "ano_generacion,mes_generacion,codigo_planilla,periodo_pago,fecha_pago_planilla,documento_identidad_empleado,codigo_sucursal",
        //Nombre de la tabla relacionada
        "movimientos_pension",
        //Campo en la tabla relacionada
        "ano_generacion,mes_generacion,codigo_planilla,periodo_pago,fecha_pago_planilla,documento_identidad_empleado,codigo_sucursal"
    ),
    array(
        //Nombre de la relación
        "cancelacion_salud_sucursal_contrato_empleado",
        //Campo en la tabla actual
        "codigo_empresa,documento_identidad_empleado,fecha_ingreso_empresa,codigo_sucursal,fecha_ingreso_sucursal",
        //Nombre de la tabla relacionada
        "sucursal_contrato_empleados",
        //Campo en la tabla relacionada
        "codigo_empresa,documento_identidad_empleado,fecha_ingreso,codigo_sucursal,fecha_ingreso_sucursal"
    ),
    array(
        //Nombre de la relación
        "cancelacion_salud_transaccion_contable",
        //Campo en la tabla actual
        "codigo_transaccion_contable",
        //Nombre de la tabla relacionada
        "transacciones_contables_empleado",
        //Campo en la tabla relacionada
        "codigo"
    ),
    array(
        //Nombre de la relación
        "cancelacion_salud_codigo_contable",
        //Campo en la tabla actual
        "codigo_contable",
        //Nombre de la tabla relacionada
        "plan_contable",
        //Campo en la tabla relacionada
        "codigo_contable"
    ),
    array(
        // Nombre de la llave foranea
        "cancelacion_salud_anexo",
        // Nombre del campo en la tabla actual
        "codigo_anexo_contable",
        // Nombre de la tabla relacionada
        "anexos_contables",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "cancelacion_salud_auxiliar_contable",
        // Nombre del campo en la tabla actual
        "codigo_empresa_auxiliar,codigo_anexo_contable,codigo_auxiliar_contable",
        // Nombre de la tabla relacionada
        "auxiliares_contables",
        // Nombre del campo de la tabla relacionada
        "codigo_empresa,codigo_anexo_contable,codigo"
    ),
    array(
        // Nombre de la llave foranea
        "cancelacion_salud_usuario",
        // Nombre del campo en la tabla actual
        "codigo_usuario_registra",
        // Nombre de la tabla relacionada
        "usuarios",
        // Nombre del campo de la tabla relacionada
        "codigo"
    )
);

$llavesForaneas["cuenta_por_pagar_salud_entidad"] = array(
    array(
        //Nombre de la relación
        "cuenta_por_pagar_movimiento_salud_empleado",
        //Campo en la tabla actual
        "ano_generacion,mes_generacion,codigo_planilla,periodo_pago,fecha_pago_planilla,documento_identidad_empleado,codigo_sucursal",
        //Nombre de la tabla relacionada
        "movimientos_pension",
        //Campo en la tabla relacionada
        "ano_generacion,mes_generacion,codigo_planilla,periodo_pago,fecha_pago_planilla,documento_identidad_empleado,codigo_sucursal"
    ),
    array(
        // Nombre de la llave
        "cuenta_por_pagar_salud_entidad_documento_tercero",
        // Nombre del campo clave de la tabla local
        "documento_identidad_entidad",
        // Nombre de la tabla relacionada
        "terceros",
        // Nombre del campo clave en la tabla relacionada
        "documento_identidad"
    ),
    array(
        //Nombre de la relación
        "cuenta_por_pagar_salud_sucursal_contrato_empleado",
        //Campo en la tabla actual
        "codigo_empresa,documento_identidad_empleado,fecha_ingreso_empresa,codigo_sucursal,fecha_ingreso_sucursal",
        //Nombre de la tabla relacionada
        "sucursal_contrato_empleados",
        //Campo en la tabla relacionada
        "codigo_empresa,documento_identidad_empleado,fecha_ingreso,codigo_sucursal,fecha_ingreso_sucursal"
    ),
    array(
        //Nombre de la relación
        "cuenta_por_pagar_salud_transaccion_contable",
        //Campo en la tabla actual
        "codigo_transaccion_contable",
        //Nombre de la tabla relacionada
        "transacciones_contables_empleado",
        //Campo en la tabla relacionada
        "codigo"
    ),
    array(
        //Nombre de la relación
        "cuenta_por_pagar_salud_codigo_contable",
        //Campo en la tabla actual
        "codigo_contable",
        //Nombre de la tabla relacionada
        "plan_contable",
        //Campo en la tabla relacionada
        "codigo_contable"
    ),
    array(
        // Nombre de la llave foranea
        "cuenta_por_pagar_salud_auxiliar_contable",
        // Nombre del campo en la tabla actual
        "codigo_empresa_auxiliar,codigo_anexo_contable,codigo_auxiliar_contable",
        // Nombre de la tabla relacionada
        "auxiliares_contables",
        // Nombre del campo de la tabla relacionada
        "codigo_empresa,codigo_anexo_contable,codigo"
    ),
    array(
        // Nombre de la llave foranea
        "cuenta_por_pagar_salud_usuario",
        // Nombre del campo en la tabla actual
        "codigo_usuario_registra",
        // Nombre de la tabla relacionada
        "usuarios",
        // Nombre del campo de la tabla relacionada
        "codigo"
    )
);

$llavesForaneas["cancelacion_nomina_por_pagar_pension_empleado"] = array(
    array(
        //Nombre de la relación
        "cancelacion_movimiento_pension_empleado",
        //Campo en la tabla actual
        "ano_generacion,mes_generacion,codigo_planilla,periodo_pago,fecha_pago_planilla,documento_identidad_empleado,codigo_sucursal",
        //Nombre de la tabla relacionada
        "movimientos_pension",
        //Campo en la tabla relacionada
        "ano_generacion,mes_generacion,codigo_planilla,periodo_pago,fecha_pago_planilla,documento_identidad_empleado,codigo_sucursal"
    ),
    array(
        //Nombre de la relación
        "cancelacion_pension_sucursal_contrato_empleado",
        //Campo en la tabla actual
        "codigo_empresa,documento_identidad_empleado,fecha_ingreso_empresa,codigo_sucursal,fecha_ingreso_sucursal",
        //Nombre de la tabla relacionada
        "sucursal_contrato_empleados",
        //Campo en la tabla relacionada
        "codigo_empresa,documento_identidad_empleado,fecha_ingreso,codigo_sucursal,fecha_ingreso_sucursal"
    ),
    array(
        //Nombre de la relación
        "cancelacion_pension_transaccion_contable",
        //Campo en la tabla actual
        "codigo_transaccion_contable",
        //Nombre de la tabla relacionada
        "transacciones_contables_empleado",
        //Campo en la tabla relacionada
        "codigo"
    ),
    array(
        //Nombre de la relación
        "cancelacion_pension_codigo_contable",
        //Campo en la tabla actual
        "codigo_contable",
        //Nombre de la tabla relacionada
        "plan_contable",
        //Campo en la tabla relacionada
        "codigo_contable"
    ),
    array(
        // Nombre de la llave foranea
        "cancelacion_pension_anexo",
        // Nombre del campo en la tabla actual
        "codigo_anexo_contable",
        // Nombre de la tabla relacionada
        "anexos_contables",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "cancelacion_pension_auxiliar_contable",
        // Nombre del campo en la tabla actual
        "codigo_empresa_auxiliar,codigo_anexo_contable,codigo_auxiliar_contable",
        // Nombre de la tabla relacionada
        "auxiliares_contables",
        // Nombre del campo de la tabla relacionada
        "codigo_empresa,codigo_anexo_contable,codigo"
    ),
    array(
        // Nombre de la llave foranea
        "cancelacion_pension_usuario",
        // Nombre del campo en la tabla actual
        "codigo_usuario_registra",
        // Nombre de la tabla relacionada
        "usuarios",
        // Nombre del campo de la tabla relacionada
        "codigo"
    )
);

$llavesForaneas["cuenta_por_pagar_pension_entidad"] = array(
    array(
        //Nombre de la relación
        "cuenta_por_pagar_movimiento_pension_empleado",
        //Campo en la tabla actual
        "ano_generacion,mes_generacion,codigo_planilla,periodo_pago,fecha_pago_planilla,documento_identidad_empleado,codigo_sucursal",
        //Nombre de la tabla relacionada
        "movimientos_pension",
        //Campo en la tabla relacionada
        "ano_generacion,mes_generacion,codigo_planilla,periodo_pago,fecha_pago_planilla,documento_identidad_empleado,codigo_sucursal"
    ),
    array(
        // Nombre de la llave
        "cuenta_por_pagar_pension_entidad_documento_tercero",
        // Nombre del campo clave de la tabla local
        "documento_identidad_entidad",
        // Nombre de la tabla relacionada
        "terceros",
        // Nombre del campo clave en la tabla relacionada
        "documento_identidad"
    ),
    array(
        //Nombre de la relación
        "cuenta_por_pagar_pension_sucursal_contrato_empleado",
        //Campo en la tabla actual
        "codigo_empresa,documento_identidad_empleado,fecha_ingreso_empresa,codigo_sucursal,fecha_ingreso_sucursal",
        //Nombre de la tabla relacionada
        "sucursal_contrato_empleados",
        //Campo en la tabla relacionada
        "codigo_empresa,documento_identidad_empleado,fecha_ingreso,codigo_sucursal,fecha_ingreso_sucursal"
    ),
    array(
        //Nombre de la relación
        "cuenta_por_pagar_pension_transaccion_contable",
        //Campo en la tabla actual
        "codigo_transaccion_contable",
        //Nombre de la tabla relacionada
        "transacciones_contables_empleado",
        //Campo en la tabla relacionada
        "codigo"
    ),
    array(
        //Nombre de la relación
        "cuenta_por_pagar_pension_codigo_contable",
        //Campo en la tabla actual
        "codigo_contable",
        //Nombre de la tabla relacionada
        "plan_contable",
        //Campo en la tabla relacionada
        "codigo_contable"
    ),
    array(
        // Nombre de la llave foranea
        "cuenta_por_pagar_pension_auxiliar_contable",
        // Nombre del campo en la tabla actual
        "codigo_empresa_auxiliar,codigo_anexo_contable,codigo_auxiliar_contable",
        // Nombre de la tabla relacionada
        "auxiliares_contables",
        // Nombre del campo de la tabla relacionada
        "codigo_empresa,codigo_anexo_contable,codigo"
    ),
    array(
        // Nombre de la llave foranea
        "cuenta_por_pagar_pension_usuario",
        // Nombre del campo en la tabla actual
        "codigo_usuario_registra",
        // Nombre de la tabla relacionada
        "usuarios",
        // Nombre del campo de la tabla relacionada
        "codigo"
    )
);

$registros["componentes"] = array(
     array(
        "id"        => "SUBMPAGO",
        "padre"     => "SUBMPRPL",
        "id_modulo" => "NOMINA",
        "visible"   => "1",
        "orden"     => "30",
        "carpeta"   => "contabilizar_planilla",
        "archivo"   => "NULL",
        "global"    => "0"
    ),
    array(
        "id"            => "PAGAPLAN",
        "padre"         => "SUBMPRPL",
        "id_modulo"     => "NOMINA",
        "orden"         => "50",
        "visible"       => "1",
        "carpeta"       => "contabilizar_planilla",
        "global"        => "0",
        "archivo"       => "pargar_planilla",
        "requiere_item" => "0",
        "tipo_enlace"   => "2"
    ),
    array(
        "id"            => "GESTGENE",
        "padre"         => "SUBMPAGO",
        "id_modulo"     => "NOMINA",
        "orden"         => "10",
        "visible"       => "1",
        "carpeta"       => "contabilizar_planilla",
        "global"        => "0",
        "archivo"       => "forma_pago_general",
        "requiere_item" => "0",
        "tipo_enlace"   => "2"
    ),
     array(
        "id"            => "GESTCHSU",
        "padre"         => "SUBMPAGO",
        "id_modulo"     => "NOMINA",
        "orden"         => "20",
        "visible"       => "1",
        "carpeta"       => "contabilizar_planilla",
        "global"        => "0",
        "archivo"       => "forma_pago_cheque_sucursal",
        "requiere_item" => "0",
        "tipo_enlace"   => "2"
    ),
    array(
        "id"            => "GESTCHEM",
        "padre"         => "SUBMPAGO",
        "id_modulo"     => "NOMINA",
        "orden"         => "30",
        "visible"       => "1",
        "carpeta"       => "contabilizar_planilla",
        "global"        => "0",
        "archivo"       => "forma_pago_cheque_empleado",
        "requiere_item" => "0",
        "tipo_enlace"   => "2"
    ),
    array(
        "id"            => "GESTPAEF",
        "padre"         => "SUBMPAGO",
        "id_modulo"     => "NOMINA",
        "orden"         => "30",
        "visible"       => "1",
        "carpeta"       => "contabilizar_planilla",
        "global"        => "0",
        "archivo"       => "forma_pago_efectivo",
        "requiere_item" => "0",
        "tipo_enlace"   => "2"
    )
);
?>

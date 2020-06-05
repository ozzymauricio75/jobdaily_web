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

$borrarSiempre = false;

//  Definición de tablas
$tablas["ingreso_empleados"] = array(
    "codigo_empresa"               => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa donde va a laborar'",
    "documento_identidad_empleado" => "VARCHAR(12) NOT NULL COMMENT 'Documento de identidad empleado en la tabla aspirantes'",
    "fecha_ingreso"                => "DATE NOT NULL COMMENT 'Fecha en la que inicia labores el empleado'",
    "fecha_vencimiento_contrato"   => "DATE NULL COMMENT 'Fecha en la cual se termina el contrato'",
    "fecha_retiro"                 => "DATE NULL COMMENT 'Fecha en la cual es retiraddo de la empresa'",
    "codigo_motivo_retiro"         => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo del motivo del retiro'",
    "riesgo_profesional"           => "DECIMAL(7,4) NOT NULL COMMENT 'Porcentaje para la liquidación de riesgos profesionales'",
    "manejo_auxilio_transporte"    => "ENUM('1','2','3','4','5') NOT NULL DEFAULT '1' COMMENT '1->Pago por ley con descuentos 2->Pago por ley sin descuentos 3->Pago mayor a dos SMLV con descuentos 4->Pago mayor a dos SMLV sin descuentos 5-> No recibe auxilio de transporte'",
    "estado"                       => "ENUM('1','2') NOT NULL DEFAULT '1' COMMENT 'Estado del empleado 1->Activo 2->Retirado'",
    "codigo_sucursal_activo"       => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo del la sucursal actual de trabajo'"
);

$tablas["contrato_empleados"] = array(
    "codigo_empresa"               => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa donde va a laborar'",
    "documento_identidad_empleado" => "VARCHAR(12) NOT NULL COMMENT 'Código interno que identifica el empleado en terceras personas'",
    "fecha_ingreso"                => "DATE NOT NULL COMMENT 'Fecha ingreso del empleado'",
    "fecha_contrato"               => "DATE NOT NULL COMMENT 'Fecha de inicio de contrato o prologa de contrato'",
    "codigo_tipo_contrato"         => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código que identifica el tipo de contrato en la tabla'",
    "fecha_cambio_contrato"        => "DATE NOT NULL COMMENT 'Fecha en la que termina o finaliza el contrato labores el empleado'",
);

$tablas["sucursal_contrato_empleados"] = array(
    "codigo_empresa"                               => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo del almacen donde va a laborar'",
    "documento_identidad_empleado"                 => "VARCHAR(12) NOT NULL COMMENT 'Documento de identidad que identifica el empleado en terceras personas'",
    "fecha_ingreso"                                => "DATE NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado'",
    "codigo_sucursal"                              => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo del almacen donde va a laborar'",
    "fecha_ingreso_sucursal"                       => "DATE NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado'",
    /////////////////////////////
    "codigo_empresa_auxiliar"                      => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa del axuliar contable'",
    "codigo_anexo_contable"                        => "VARCHAR(3) NOT NULL COMMENT 'Código del anexo que permite dividir las cuentas'",
    "codigo_auxiliar"                              => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código donde se acumulara la información'",
    /////////////////////////////
    "codigo_planilla"                              => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código de la planilla'",
    "salario_mensual"                              => "DECIMAL(11,2) NULL COMMENT 'Salario que devengara el empleado mensualmente'",
    "valor_hora"                                   => "DECIMAL(11,2) NULL COMMENT 'Valor de la hora que devengara el empleado'",
    "dias_mes"                                     => "SMALLINT(3) NOT NULL COMMENT 'Numero de dias que trabajara en el mes'",
    "horas_mes"                                    => "SMALLINT(3) NOT NULL COMMENT 'Numero de horas que trabajara en el mes'",
    "codigo_turno_laboral"                         => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id de la tabla de turnos laborales'",
    "codigo_motivo_retiro"                         => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id de la tabla de motivos de retiro'",
    "fecha_retiro"                                 => "DATE NULL COMMENT 'Fecha en la cual es retiraddo de la empresa'",
    "codigo_transaccion_salario"                   => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id de la transaccion contable que genera horas normales'",
    "codigo_transaccion_auxilio_transporte"        => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id de la transaccion contable que genera horas normales'",
    "forma_pago_auxilio"                           => "ENUM('1','2') DEFAULT '1' COMMENT 'Solo para las planillas quincenales 1-> Proporcional 2->Segunda quincena'",
    "codigo_transaccion_salud"                     => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id de la transaccion contable que genera horas normales'",
    "forma_descuento_salud"                        => "ENUM('1','2') DEFAULT '1' COMMENT 'Solo para las planillas quincenales 1-> Proporcional 2->Segunda quincena'",
    "codigo_transaccion_pension"                   => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id de la transaccion contable que genera horas normales'",
    "forma_descuento_pension"                      => "ENUM('1','2') DEFAULT '1' COMMENT 'Solo para las planillas quincenales 1-> Proporcional 2->Segunda quincena'",
    "codigo_transaccion_normales"                  => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'codigo de la transaccion tiempo que genera horas normales'",
    "codigo_transaccion_extras"                    => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'codigo de la transaccion tiempo que genera horas extras'",
    "codigo_transaccion_recargo_nocturno"          => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'codigo de la transaccion tiempo que genera horas recargo nocturno'",
    "codigo_transaccion_extras_nocturnas"          => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'codigo de la transaccion tiempo que genera horas extras nocturnas'",
    "codigo_transaccion_dominicales"               => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'codigo de la transaccion tiempo que genera horas dominicales y festivos'",
    "codigo_transaccion_extras_dominicales"        => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'codigo de la transaccion tiempo que genera horas extras dominicales'",
    "codigo_transaccion_recargo_noche_dominicales" => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'codigo de la transaccion tiempo que genera recargo nocturno dominicales y festivos'",
    "codigo_transaccion_extras_noche_dominicales"  => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'codigo de la transaccion tiempo que genera horas extra dominicales nocturnas'",
);

$tablas["salario_sucursal_contrato"] = array(
    "codigo_empresa"               => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo del almacen donde va a laborar'",
    "documento_identidad_empleado" => "VARCHAR(12) NOT NULL COMMENT 'Documento de identidad que identifica el empleado en terceras personas'",
    "fecha_ingreso"                => "DATE NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado'",
    "codigo_sucursal"              => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo del almacen donde va a laborar'",
    "fecha_ingreso_sucursal"       => "DATE NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado'",
    "fecha_salario"                => "DATE NOT NULL COMMENT 'Fecha en la que asignan salario'",
    "fecha_registro"               => "DATETIME NOT NULL COMMENT 'Fecha del sistema en la que se asigna el salario'",
    "fecha_retroactivo"            => "DATE NOT NULL COMMENT 'Fecha a partir de la cual se hace retroactivo en salario'",
    "salario"                      => "DECIMAL(11,2) NOT NULL COMMENT 'Salario que devengara el empleado mensualmente'",
    "valor_dia"                    => "DECIMAL(11,2) NOT NULL COMMENT 'Valor del dia'",
    "valor_hora"                   => "DECIMAL(11,2) NOT NULL DEFAULT 0 COMMENT 'Valor de la hora'"
);

$tablas["cargo_contrato_empleados"] = array(
    "codigo_empresa"                     => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa donde se vincula el empleado'",
    "documento_identidad_empleado"       => "VARCHAR(12) NOT NULL COMMENT 'Código interno que identifica el empleado en terceras personas'",
    "fecha_ingreso"                      => "DATE NOT NULL COMMENT 'Fecha de ingreso del empleado'",
    "codigo_sucursal"                    => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo del almacen donde va a laborar'",
    "fecha_ingreso_sucursal"             => "DATE NOT NULL COMMENT 'Fecha en la que inicia labores en la sucursal el empleado'",
    "fecha_inicia_cargo"                 => "DATE NOT NULL COMMENT 'Fecha en la que inicia labores en el cargo asignado'",
    "codigo_cargo"                       => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código de que identifica el cargo'",
    "fecha_termina"                      => "DATE NOT NULL COMMENT 'Fecha en la que termina labores con el cargo asignado'",
    "documento_identidad_jefe_inmediato" => "VARCHAR(12) NOT NULL COMMENT 'Documento identidad del jefe inmediato(Tabla terceros)'"
);

$tablas["departamento_seccion_contrato_empleado"] = array(
    "codigo_empresa"                    => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo del almacen donde va a laborar'",
    "documento_identidad_empleado"      => "VARCHAR(12) NOT NULL COMMENT 'Código interno que identifica el empleado en terceras personas'",
    "fecha_ingreso"                     => "DATE NOT NULL COMMENT 'Fecha en la que inicia labores en la empresa el empleado'",
    "codigo_sucursal"                   => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo del almacen donde va a laborar'",
    "fecha_ingreso_sucursal"            => "DATE NOT NULL COMMENT 'Fecha en la que inicia labores en la sucursal el empleado'",
    "fecha_inicia_departamento_seccion" => "DATE NOT NULL COMMENT 'Fecha en la que inicia labores en el departamento y seccion asignado'",
    "codigo_departamento_empresa"       => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código del departamento de la empresa donde va a laborar'",
    "codigo_seccion_empresa"            => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código asignado usuario'",
    "fecha_termina"                     => "DATE NOT NULL COMMENT 'Fecha en la que termina labores en el departamento asignado'"
);

$tablas["entidades_salud_empleados"] = array(
    "codigo_empresa"               => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código de la empresa en la tabla ingresos'",
    "documento_identidad_empleado" => "VARCHAR(12) NOT NULL COMMENT 'Documento identidad del empleado en la tabla ingresos'",
    "fecha_ingreso"                => "DATE NOT NULL COMMENT 'Fecha de ingreso a la empresa del empleado'",
    "fecha_inicio_salud"           => "DATE NOT NULL COMMENT 'Fecha en la que inicia relacion con la entidad'",
    "codigo_entidad_salud"         => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo interno que identifica la entidad en la base de datos'",
    "direccion_atencion"           => "VARCHAR(100) NULL COMMENT 'Direccion donde atienden las citas normales'",
    "direccion_urgencia"           => "VARCHAR(100) NULL COMMENT 'Direccion donde atienden las urgencias'"
);

$tablas["entidades_pension_empleados"] = array(
    "codigo_empresa"               => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código de la empresa en la tabla ingresos'",
    "documento_identidad_empleado" => "VARCHAR(12) NOT NULL COMMENT 'Documento identidad del empleado en la tabla ingresos'",
    "fecha_ingreso"                => "DATE NOT NULL COMMENT 'Fecha de ingreso a la empresa del empleado'",
    "fecha_inicio_pension"         => "DATE NOT NULL COMMENT 'Fecha en la que inicia relacion con la entidad'",
    "codigo_entidad_pension"       => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la entidad parafiscal en la base de datos'"
);

$tablas["ingresos_varios_empleados"] =array(
    "codigo_empresa"                 => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa donde va a laborar'",
    "documento_identidad_empleado"   => "VARCHAR(12) NOT NULL COMMENT 'Documento de identidad empleado en la tabla aspirantes'",
    "fecha_ingreso"                  => "DATE NOT NULL COMMENT 'Fecha en la que inicia labores el empleado'",
    "codigo_transaccion_tiempo"      => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo interno para la base de datos'",
    "fecha_inicio_transacion_tiempo" => "DATE NOT NULL COMMENT 'Fecha en la que la transaccion de tiempo'",
    "fecha_final_transacion_tiempo"  => "DATE NOT NULL COMMENT 'Fecha en la que se quita la transacion de tiempo'",
    "estado"                         => "ENUM('0','1') DEFAULT '1' COMMENT 'Estado de la transacion de tiempo 0-> Inativa 1->Activa'",
    "periodo_pago"                   => "ENUM('1','2','3','4') DEFAULT '1' COMMENT 'Estado de la transacion de tiempo 1-> Proporcional 2-> segunda quincena 3-> Mensual 4-> Semanal'",
    "valor"                          => "DECIMAL(11,2) NOT NULL DEFAULT 0 COMMENT 'Valor del ingreso vario que recibira el empleado'"
);

//  Definición de llaves primarias
$llavesPrimarias["ingreso_empleados"]                      = "codigo_empresa,documento_identidad_empleado,fecha_ingreso";
$llavesPrimarias["contrato_empleados"]                     = "codigo_empresa,documento_identidad_empleado,fecha_ingreso,fecha_contrato";
$llavesPrimarias["sucursal_contrato_empleados"]            = "codigo_empresa,documento_identidad_empleado,fecha_ingreso,codigo_sucursal,fecha_ingreso_sucursal";
$llavesPrimarias["cargo_contrato_empleados"]               = "codigo_empresa,documento_identidad_empleado,fecha_ingreso,codigo_sucursal,fecha_ingreso_sucursal,fecha_inicia_cargo";
$llavesPrimarias["departamento_seccion_contrato_empleado"] = "codigo_empresa,documento_identidad_empleado,fecha_ingreso,codigo_sucursal,fecha_ingreso_sucursal,fecha_inicia_departamento_seccion";
$llavesPrimarias["entidades_salud_empleados"]              = "codigo_empresa,documento_identidad_empleado,fecha_ingreso,fecha_inicio_salud";
$llavesPrimarias["entidades_pension_empleados"]            = "codigo_empresa,documento_identidad_empleado,fecha_ingreso,fecha_inicio_pension";
$llavesPrimarias["ingresos_varios_empleados"]              = "codigo_empresa,documento_identidad_empleado,fecha_ingreso,fecha_inicio_transacion_tiempo,codigo_transaccion_tiempo";
$llavesPrimarias["salario_sucursal_contrato"]              = "codigo_empresa,documento_identidad_empleado,fecha_ingreso,fecha_ingreso_sucursal,fecha_salario";

//  Definición de llaves Foraneas
$llavesForaneas["ingreso_empleados"] = array(
    array(
        //  Nombre de la llave foranea
        "ingreso_empleado_empresa",
        //  Nombre del campo en la tabla actual
        "codigo_empresa",
        //  Nombre de la tabla relacionada
        "empresas",
        //  Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        //  Nombre de la llave foranea
        "ingreso_empleado_documento_identidad",
        //  Nombre del campo en la tabla actual
        "documento_identidad_empleado",
        //  Nombre de la tabla relacionada
        "aspirantes",
        //  Nombre del campo de la tabla relacionada
        "documento_identidad"
    ),
    array(
        //  Nombre de la llave foranea
        "ingreso_empleado_motivo_retiro",
        //  Nombre del campo en la tabla actual
        "codigo_motivo_retiro",
        //  Nombre de la tabla relacionada
        "motivos_retiro",
        //  Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        //  Nombre de la llave foranea
        "sucursal_contrato_empleado_actual",
        //  Nombre del campo en la tabla actual
        "codigo_sucursal_activo",
        //  Nombre de la tabla relacionada
        "sucursales",
        //  Nombre del campo de la tabla relacionada
        "codigo"
    )
);

$llavesForaneas["contrato_empleados"] = array(
    array(
        //  Nombre de la llave foranea
        "contrato_ingreso_empleado",
        //  Nombre del campo en la tabla actual
        "codigo_empresa,documento_identidad_empleado,fecha_ingreso",
        //  Nombre de la tabla relacionada
        "ingreso_empleados",
        //  Nombre del campo de la tabla relacionada
        "codigo_empresa,documento_identidad_empleado,fecha_ingreso"
    ),
    array(
        //  Nombre de la llave foranea
        "contrato_codigo_tipo_contrato",
        //  Nombre del campo en la tabla actual
        "codigo_tipo_contrato",
        //  Nombre de la tabla relacionada
        "tipos_contrato",
        //  Nombre del campo de la tabla relacionada
        "codigo"
    )
);

$llavesForaneas["sucursal_contrato_empleados"] = array(
    array(
        //  Nombre de la llave foranea
        "sucursal_codigo_transaccion_normales",
        //  Nombre del campo en la tabla actual
        "codigo_transaccion_normales",
        //  Nombre de la tabla relacionada
        "transacciones_tiempo",
        //  Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        //  Nombre de la llave foranea
        "sucursal_codigo_transaccion_extras",
        //  Nombre del campo en la tabla actual
        "codigo_transaccion_extras",
        //  Nombre de la tabla relacionada
        "transacciones_tiempo",
        //  Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        //  Nombre de la llave foranea
        "sucursal_codigo_transaccion_recargo_nocturno",
        //  Nombre del campo en la tabla actual
        "codigo_transaccion_recargo_nocturno",
        //  Nombre de la tabla relacionada
        "transacciones_tiempo",
        //  Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        //  Nombre de la llave foranea
        "sucursal_codigo_transaccion_extras_nocturnas",
        //  Nombre del campo en la tabla actual
        "codigo_transaccion_extras_nocturnas",
        //  Nombre de la tabla relacionada
        "transacciones_tiempo",
        //  Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        //  Nombre de la llave foranea
        "sucursal_codigo_transaccion_dominicales",
        //  Nombre del campo en la tabla actual
        "codigo_transaccion_dominicales",
        //  Nombre de la tabla relacionada
        "transacciones_tiempo",
        //  Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        //  Nombre de la llave foranea
        "sucursal_codigo_transaccion_extras_dominicales",
        //  Nombre del campo en la tabla actual
        "codigo_transaccion_extras_dominicales",
        //  Nombre de la tabla relacionada
        "transacciones_tiempo",
        //  Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        //  Nombre de la llave foranea
        "sucursal_codigo_transaccion_recargo_noche_dominicales",
        //  Nombre del campo en la tabla actual
        "codigo_transaccion_recargo_noche_dominicales",
        //  Nombre de la tabla relacionada
        "transacciones_tiempo",
        //  Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        //  Nombre de la llave foranea
        "sucursal_codigo_transaccion_extras_noche_dominicales",
        //  Nombre del campo en la tabla actual
        "codigo_transaccion_extras_noche_dominicales",
        //  Nombre de la tabla relacionada
        "transacciones_tiempo",
        //  Nombre del campo de la tabla relacionada
        "codigo"
    ),//////////////////////////////
    array(
        //  Nombre de la llave foranea
        "sucursal_contrato_ingreso_empleado",
        //  Nombre del campo en la tabla actual
        "codigo_empresa,documento_identidad_empleado,fecha_ingreso",
        //  Nombre de la tabla relacionada
        "ingreso_empleados",
        //  Nombre del campo de la tabla relacionada
        "codigo_empresa,documento_identidad_empleado,fecha_ingreso"
    ),
    array(
        //  Nombre de la llave foranea
        "sucursal_contrato_anexo_contable_empleado",
        //  Nombre del campo en la tabla actual
        "codigo_anexo_contable",
        //  Nombre de la tabla relacionada
        "anexos_contables",
        //  Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        //  Nombre de la llave foranea
        "sucursal_contrato_auxiliar_contable_empleado",
        //  Nombre del campo en la tabla actual
        "codigo_empresa_auxiliar,codigo_anexo_contable,codigo_auxiliar",
        //  Nombre de la tabla relacionada
        "auxiliares_contables",
        //  Nombre del campo de la tabla relacionada
        "codigo_empresa,codigo_anexo_contable,codigo"
    ),
    array(
        //  Nombre de la llave foranea
        "sucursal_contrato_planilla_empleado",
        //  Nombre del campo en la tabla actual
        "codigo_planilla",
        //  Nombre de la tabla relacionada
        "planillas",
        //  Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        //  Nombre de la llave foranea
        "sucursal_contrato_turno_empleado",
        //  Nombre del campo en la tabla actual
        "codigo_turno_laboral",
        //  Nombre de la tabla relacionada
        "turnos_laborales",
        //  Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        //  Nombre de la llave foranea
        "sucursal_contrato_motivo_retiro_empleado",
        //  Nombre del campo en la tabla actual
        "codigo_motivo_retiro",
        //  Nombre de la tabla relacionada
        "motivos_retiro",
        //  Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        //  Nombre de la llave foranea
        "sucursal_contrato_transaccion_salario",
        //  Nombre del campo en la tabla actual
        "codigo_transaccion_salario",
        //  Nombre de la tabla relacionada
        "transacciones_contables_empleado",
        //  Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        //  Nombre de la llave foranea
        "sucursal_contrato_transaccion_auxilio",
        //  Nombre del campo en la tabla actual
        "codigo_transaccion_auxilio_transporte",
        //  Nombre de la tabla relacionada
        "transacciones_contables_empleado",
        //  Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        //  Nombre de la llave foranea
        "sucursal_contrato_transaccion_salud",
        //  Nombre del campo en la tabla actual
        "codigo_transaccion_salud",
        //  Nombre de la tabla relacionada
        "transacciones_contables_empleado",
        //  Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        //  Nombre de la llave foranea
        "sucursal_contrato_transaccion_pension",
        //  Nombre del campo en la tabla actual
        "codigo_transaccion_pension",
        //  Nombre de la tabla relacionada
        "transacciones_contables_empleado",
        //  Nombre del campo de la tabla relacionada
        "codigo"
    )
);

$llavesForaneas["cargo_contrato_empleados"] = array(
    array(
        //  Nombre de la llave foranea
        "sucursal_contrato_cargo_empleado",
        //  Nombre del campo en la tabla actual
        "codigo_empresa,documento_identidad_empleado,fecha_ingreso,codigo_sucursal,fecha_ingreso_sucursal",
        //  Nombre de la tabla relacionada
        "sucursal_contrato_empleados",
        //  Nombre del campo de la tabla relacionada
        "codigo_empresa,documento_identidad_empleado,fecha_ingreso,codigo_sucursal,fecha_ingreso_sucursal"
    ),
    array(
        //  Nombre de la llave foranea
        "sucursal_contrato_codigo_cargo_empleado",
        //  Nombre del campo en la tabla actual
        "codigo_cargo",
        //  Nombre de la tabla relacionada
        "cargos",
        //  Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        //  Nombre de la llave foranea
        "sucursal_contrato_documento_jefe_inmediato",
        //  Nombre del campo en la tabla actual
        "documento_identidad_jefe_inmediato",
        //  Nombre de la tabla relacionada
        "aspirantes",
        //  Nombre del campo de la tabla relacionada
        "documento_identidad"
    )
);

$llavesForaneas["departamento_seccion_contrato_empleado"] = array(
    array(
        //  Nombre de la llave foranea
        "sucursal_contrato_departamento_seccion",
        //  Nombre del campo en la tabla actual
        "codigo_empresa,documento_identidad_empleado,fecha_ingreso,codigo_sucursal,fecha_ingreso_sucursal",
        //  Nombre de la tabla relacionada
        "sucursal_contrato_empleados",
        //  Nombre del campo de la tabla relacionada
        "codigo_empresa,documento_identidad_empleado,fecha_ingreso,codigo_sucursal,fecha_ingreso_sucursal"
    ),
    array(
        //  Nombre de la llave foranea
        "sucursal_contrato_departamento_empleado",
        //  Nombre del campo en la tabla actual
        "codigo_departamento_empresa",
        //  Nombre de la tabla relacionada
        "departamentos_empresa",
        //  Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        //  Nombre de la llave foranea
        "sucursal_contrato_seccion_empleado",
        //  Nombre del campo en la tabla actual
        "codigo_departamento_empresa,codigo_seccion_empresa",
        //  Nombre de la tabla relacionada
        "secciones_departamentos",
        //  Nombre del campo de la tabla relacionada
        "codigo_departamento_empresa,codigo"
    )
);

$llavesForaneas["entidades_salud_empleados"] = array(
    array(
        //  Nombre de la llave foranea
        "codigo_entidad_salud_ingreso_empleado",
        //  Nombre del campo en la tabla actual
        "codigo_entidad_salud",
        //  Nombre de la tabla relacionada
        "entidades_parafiscales",
        //  Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        //  Nombre de la llave foranea
        "llave_entidad_salud_ingreso_empleado",
        //  Nombre del campo en la tabla actual
        "codigo_empresa,documento_identidad_empleado,fecha_ingreso",
        //  Nombre de la tabla relacionada
        "ingreso_empleados",
        //  Nombre del campo de la tabla relacionada
        "codigo_empresa,documento_identidad_empleado,fecha_ingreso"
    )
);

$llavesForaneas["entidades_pension_empleados"] = array(
    array(
        //  Nombre de la llave foranea
        "entidad_pension_empleado",
        //  Nombre del campo en la tabla actual
        "codigo_entidad_pension",
        //  Nombre de la tabla relacionada
        "entidades_parafiscales",
        //  Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        //  Nombre de la llave foranea
        "entidad_pension_ingreso_empleado",
        //  Nombre del campo en la tabla actual
        "codigo_empresa,documento_identidad_empleado,fecha_ingreso",
        //  Nombre de la tabla relacionada
        "ingreso_empleados",
        //  Nombre del campo de la tabla relacionada
        "codigo_empresa,documento_identidad_empleado,fecha_ingreso"
    )
);

$llavesForaneas["ingresos_varios_empleados"] = array(
    array(
        //  Nombre de la llave foranea
        "ingresos_varios_empleados_relacion_ingreso_empleados",
        //  Nombre del campo en la tabla actual
        "codigo_empresa,documento_identidad_empleado,fecha_ingreso",
        //  Nombre de la tabla relacionada
        "ingreso_empleados",
        //  Nombre del campo de la tabla relacionada
        "codigo_empresa,documento_identidad_empleado,fecha_ingreso"
    ),
    array(
        //  Nombre de la llave foranea
        "ingresos_varios_empleados_codigo_transaccion_contable",
        //  Nombre del campo en la tabla actual
        "codigo_transaccion_tiempo",
        //  Nombre de la tabla relacionada
        "transacciones_contables_empleado",
        //  Nombre del campo de la tabla relacionada
        "codigo"
    )
);

$llavesForaneas["salario_sucursal_contrato"] = array(
    array(
        //  Nombre de la llave foranea
        "sucursal_contrato_salario_sucursal_contrato",
        //  Nombre del campo en la tabla actual
        "codigo_empresa,documento_identidad_empleado,fecha_ingreso,codigo_sucursal,fecha_ingreso_sucursal",
        //  Nombre de la tabla relacionada
        "sucursal_contrato_empleados",
        //  Nombre del campo de la tabla relacionada
        "codigo_empresa,documento_identidad_empleado,fecha_ingreso,codigo_sucursal,fecha_ingreso_sucursal"
     )
 );

$registros["componentes"] = array(
    array(
        "id"            => "GESTINEM",
        "padre"         => "SUBMNOMI",
        "id_modulo"     => "NOMINA",
        "orden"         => "5",
        "visible"       => "1",
        "carpeta"       => "empleados",
        "global"        => "0",
        "archivo"       => "menu",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "ADICINEM",
        "padre"         => "GESTINEM",
        "id_modulo"     => "NOMINA",
        "orden"         => "10",
        "visible"       => "0",
        "carpeta"       => "empleados",
        "global"        => "0",
        "archivo"       => "adicionar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "CONSINEM",
        "padre"         => "GESTINEM",
        "id_modulo"     => "NOMINA",
        "orden"         => "20",
        "visible"       => "0",
        "carpeta"       => "empleados",
        "global"        => "0",
        "archivo"       => "consultar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "MODIINEM",
        "padre"         => "GESTINEM",
        "id_modulo"     => "NOMINA",
        "orden"         => "30",
        "visible"       => "0",
        "carpeta"       => "empleados",
        "global"        => "0",
        "archivo"       => "modificar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "RETIINEM",
        "padre"         => "GESTINEM",
        "id_modulo"     => "NOMINA",
        "orden"         => "40",
        "visible"       => "0",
        "carpeta"       => "empleados",
        "global"        => "0",
        "archivo"       => "retirar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "LISTINEM",
        "padre"         => "GESTINEM",
        "id_modulo"     => "NOMINA",
        "orden"         => "50",
        "visible"       => "0",
        "carpeta"       => "empleados",
        "global"        => "0",
        "archivo"       => "listar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_ingreso_empleados AS
        SELECT CONCAT(job_ingreso_empleados.codigo_empresa,'|',job_ingreso_empleados.documento_identidad_empleado,'|',job_ingreso_empleados.fecha_ingreso) AS id,
        job_ingreso_empleados.documento_identidad_empleado AS NUMERO_DOCUMENTO,
        IF(job_terceros.tipo_persona = '1' OR job_terceros.tipo_persona = '4',
            CONCAT(
                job_terceros.primer_nombre,' ',
                IF (job_terceros.segundo_nombre IS NOT NULL AND job_terceros.segundo_nombre != '',CONCAT(job_terceros.segundo_nombre,' '),''),
                job_terceros.primer_apellido,' ',
                IF (job_terceros.segundo_apellido IS NOT NULL AND job_terceros.segundo_apellido != '',job_terceros.segundo_apellido,'')
            ),
            job_terceros.razon_social
        ) AS NOMBRE_COMPLETO,
        CONCAT('ESTADO_',job_ingreso_empleados.estado) AS ESTADO
        FROM job_terceros, job_ingreso_empleados
        WHERE job_ingreso_empleados.documento_identidad_empleado = job_terceros.documento_identidad
        AND job_ingreso_empleados.documento_identidad_empleado  > 0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_ingreso_empleados AS
        SELECT CONCAT(job_ingreso_empleados.codigo_empresa,'|',job_ingreso_empleados.documento_identidad_empleado,'|',job_ingreso_empleados.fecha_ingreso) AS id,
        job_terceros.documento_identidad AS NUMERO_DOCUMENTO,
        CONCAT(job_sucursal_contrato_empleados.codigo_sucursal,'|',job_sucursal_contrato_empleados.fecha_ingreso_sucursal) AS codigo_sucursal,
        job_terceros.primer_nombre,
        job_terceros.segundo_nombre,
        job_terceros.primer_apellido,
        job_terceros.segundo_apellido,
        job_terceros.razon_social,
        IF(job_terceros.tipo_persona = '1' OR job_terceros.tipo_persona = '4',
            CONCAT(
                job_terceros.primer_nombre,' ',
                IF (job_terceros.segundo_nombre IS NOT NULL AND job_terceros.segundo_nombre != '',CONCAT(job_terceros.segundo_nombre,' '),''),
                job_terceros.primer_apellido,' ',
                IF (job_terceros.segundo_apellido IS NOT NULL AND job_terceros.segundo_apellido != '',job_terceros.segundo_apellido,'')
            ),
            job_terceros.razon_social
        )AS NOMBRE_COMPLETO
        FROM job_terceros, job_ingreso_empleados, job_sucursal_contrato_empleados
        WHERE
            job_ingreso_empleados.documento_identidad_empleado = job_terceros.documento_identidad
            AND job_ingreso_empleados.documento_identidad_empleado  > 0
                AND job_ingreso_empleados.documento_identidad_empleado = job_sucursal_contrato_empleados.documento_identidad_empleado
            AND job_ingreso_empleados.codigo_empresa = job_sucursal_contrato_empleados.codigo_empresa
                AND job_ingreso_empleados.fecha_ingreso = job_sucursal_contrato_empleados.fecha_ingreso;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_empleados AS
        SELECT job_ingreso_empleados.documento_identidad_empleado AS id,
        CONCAT( job_terceros.documento_identidad,' - ',
                IF(job_terceros.primer_nombre IS NOT NULL,job_terceros.primer_nombre,''),' ',
                IF(job_terceros.segundo_nombre IS NOT NULL,job_terceros.segundo_nombre,''),' ',
                IF(job_terceros.primer_apellido IS NOT NULL,job_terceros.primer_apellido,''),' ',
                IF(job_terceros.segundo_apellido IS NOT NULL,job_terceros.segundo_apellido,''),' ',
                IF(job_terceros.razon_social IS NOT NULL,job_terceros.razon_social,''),'|',job_terceros.documento_identidad
            ) AS NOMBRE_COMPLETO,
        job_ingreso_empleados.codigo_sucursal_activo AS id_sucursal,
        job_ingreso_empleados.codigo_empresa AS id_empresa
        FROM job_terceros, job_ingreso_empleados, job_aspirantes
        WHERE job_ingreso_empleados.documento_identidad_empleado = job_aspirantes.documento_identidad
        AND job_aspirantes.documento_identidad = job_terceros.documento_identidad
        AND job_ingreso_empleados.documento_identidad_empleado  > 0
        AND job_ingreso_empleados.estado = '1';"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_ultimo_contrato_empleado AS SELECT
            job_sucursal_contrato_empleados.codigo_empresa,
            job_sucursal_contrato_empleados.documento_identidad_empleado,
            job_sucursal_contrato_empleados.fecha_ingreso,
            job_sucursal_contrato_empleados.codigo_sucursal,
            MAX(job_sucursal_contrato_empleados.fecha_ingreso_sucursal) AS fecha_ingreso_sucursal,
            job_sucursal_contrato_empleados.codigo_planilla
        FROM
            job_sucursal_contrato_empleados, job_ingreso_empleados
        WHERE
            job_sucursal_contrato_empleados.codigo_empresa = job_ingreso_empleados.codigo_empresa AND
            job_sucursal_contrato_empleados.documento_identidad_empleado = job_ingreso_empleados.documento_identidad_empleado AND
            job_sucursal_contrato_empleados.fecha_ingreso = job_ingreso_empleados.fecha_ingreso AND
            job_sucursal_contrato_empleados.codigo_sucursal = job_ingreso_empleados.codigo_sucursal_activo
        GROUP BY
            codigo_empresa,
            documento_identidad_empleado,
            fecha_ingreso,
            codigo_sucursal;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_listado_empleados AS (
        SELECT  job_ingreso_empleados.fecha_ingreso,
                job_ingreso_empleados.documento_identidad_empleado,
                job_ingreso_empleados.codigo_empresa,
                job_ingreso_empleados.codigo_sucursal_activo,
                job_empresas.razon_social,
                job_sucursales.nombre AS nombre_sucursal,

                IF(job_terceros.tipo_persona = '1' OR job_terceros.tipo_persona = '4',
                    CONCAT(
                        job_terceros.primer_nombre,' ',
                        IF (job_terceros.segundo_nombre IS NOT NULL AND job_terceros.segundo_nombre != '',CONCAT(job_terceros.segundo_nombre,' '),''),
                        job_terceros.primer_apellido,' ',
                        IF (job_terceros.segundo_apellido IS NOT NULL AND job_terceros.segundo_apellido != '',job_terceros.segundo_apellido,'')
                    ),
                    job_terceros.razon_social
                ) AS nombre_completo,
                job_terceros.fecha_nacimiento AS fecha_nacimiento,

                (SELECT max(job_sucursal_contrato_empleados.fecha_ingreso_sucursal)
                FROM job_sucursal_contrato_empleados WHERE job_ingreso_empleados.codigo_empresa = job_sucursal_contrato_empleados.codigo_empresa AND
                job_ingreso_empleados.fecha_ingreso = job_sucursal_contrato_empleados.fecha_ingreso AND
                job_ingreso_empleados.documento_identidad_empleado = job_sucursal_contrato_empleados.documento_identidad_empleado AND
                job_ingreso_empleados.codigo_sucursal_activo = job_sucursal_contrato_empleados.codigo_sucursal) AS fecha_ingreso_sucursal,

                (SELECT job_sucursal_contrato_empleados.salario_mensual
                FROM job_sucursal_contrato_empleados WHERE job_ingreso_empleados.codigo_empresa = job_sucursal_contrato_empleados.codigo_empresa AND
                job_ingreso_empleados.fecha_ingreso = job_sucursal_contrato_empleados.fecha_ingreso AND
                job_ingreso_empleados.documento_identidad_empleado = job_sucursal_contrato_empleados.documento_identidad_empleado AND
                job_ingreso_empleados.codigo_sucursal_activo = job_sucursal_contrato_empleados.codigo_sucursal AND job_sucursal_contrato_empleados.fecha_ingreso_sucursal IN (
                SELECT max(job_sucursal_contrato_empleados.fecha_ingreso_sucursal)
                FROM job_sucursal_contrato_empleados WHERE job_ingreso_empleados.codigo_empresa = job_sucursal_contrato_empleados.codigo_empresa AND
                job_ingreso_empleados.fecha_ingreso = job_sucursal_contrato_empleados.fecha_ingreso AND
                job_ingreso_empleados.documento_identidad_empleado = job_sucursal_contrato_empleados.documento_identidad_empleado AND
                job_ingreso_empleados.codigo_sucursal_activo = job_sucursal_contrato_empleados.codigo_sucursal
                )) AS salario,

                job_contrato_empleados.codigo_tipo_contrato,
                job_tipos_contrato.descripcion AS tipo_contrato,

                (SELECT job_departamento_seccion_contrato_empleado.codigo_departamento_empresa
                FROM  job_departamento_seccion_contrato_empleado
                WHERE job_ingreso_empleados.codigo_empresa = job_departamento_seccion_contrato_empleado.codigo_empresa AND
                job_ingreso_empleados.fecha_ingreso = job_departamento_seccion_contrato_empleado.fecha_ingreso AND
                job_ingreso_empleados.documento_identidad_empleado = job_departamento_seccion_contrato_empleado.documento_identidad_empleado AND
                job_ingreso_empleados.codigo_sucursal_activo = job_departamento_seccion_contrato_empleado.codigo_sucursal AND fecha_ingreso_sucursal IN
                (SELECT max(job_sucursal_contrato_empleados.fecha_ingreso_sucursal)
                FROM job_sucursal_contrato_empleados WHERE job_ingreso_empleados.codigo_empresa = job_sucursal_contrato_empleados.codigo_empresa AND
                job_ingreso_empleados.fecha_ingreso = job_sucursal_contrato_empleados.fecha_ingreso AND
                job_ingreso_empleados.documento_identidad_empleado = job_sucursal_contrato_empleados.documento_identidad_empleado AND
                job_ingreso_empleados.codigo_sucursal_activo = job_sucursal_contrato_empleados.codigo_sucursal)
                ) AS codigo_departamento_empresa,

                (SELECT job_departamentos_empresa.nombre FROM job_departamentos_empresa
                WHERE codigo IN (SELECT job_departamento_seccion_contrato_empleado.codigo_departamento_empresa
                FROM  job_departamento_seccion_contrato_empleado
                WHERE job_ingreso_empleados.codigo_empresa = job_departamento_seccion_contrato_empleado.codigo_empresa AND
                job_ingreso_empleados.fecha_ingreso = job_departamento_seccion_contrato_empleado.fecha_ingreso AND
                job_ingreso_empleados.documento_identidad_empleado = job_departamento_seccion_contrato_empleado.documento_identidad_empleado AND
                job_ingreso_empleados.codigo_sucursal_activo = job_departamento_seccion_contrato_empleado.codigo_sucursal AND fecha_ingreso_sucursal IN
                (SELECT max(job_sucursal_contrato_empleados.fecha_ingreso_sucursal)
                FROM job_sucursal_contrato_empleados WHERE job_ingreso_empleados.codigo_empresa = job_sucursal_contrato_empleados.codigo_empresa AND
                job_ingreso_empleados.fecha_ingreso = job_sucursal_contrato_empleados.fecha_ingreso AND
                job_ingreso_empleados.documento_identidad_empleado = job_sucursal_contrato_empleados.documento_identidad_empleado AND
                job_ingreso_empleados.codigo_sucursal_activo = job_sucursal_contrato_empleados.codigo_sucursal)
                )) AS departamento_empresa,

                (SELECT job_secciones_departamentos.nombre FROM job_secciones_departamentos
                WHERE codigo IN (SELECT job_departamento_seccion_contrato_empleado.codigo_seccion_empresa
                FROM  job_departamento_seccion_contrato_empleado
                WHERE job_ingreso_empleados.codigo_empresa = job_departamento_seccion_contrato_empleado.codigo_empresa AND
                job_ingreso_empleados.fecha_ingreso = job_departamento_seccion_contrato_empleado.fecha_ingreso AND
                job_ingreso_empleados.documento_identidad_empleado = job_departamento_seccion_contrato_empleado.documento_identidad_empleado AND
                job_ingreso_empleados.codigo_sucursal_activo = job_departamento_seccion_contrato_empleado.codigo_sucursal AND fecha_ingreso_sucursal IN
                (SELECT max(job_sucursal_contrato_empleados.fecha_ingreso_sucursal)
                FROM job_sucursal_contrato_empleados WHERE job_ingreso_empleados.codigo_empresa = job_sucursal_contrato_empleados.codigo_empresa AND
                job_ingreso_empleados.fecha_ingreso = job_sucursal_contrato_empleados.fecha_ingreso AND
                job_ingreso_empleados.documento_identidad_empleado = job_sucursal_contrato_empleados.documento_identidad_empleado AND
                job_ingreso_empleados.codigo_sucursal_activo = job_sucursal_contrato_empleados.codigo_sucursal)
                )) AS seccion_empresa

        FROM    job_terceros,
                job_ingreso_empleados,
                job_empresas, job_sucursales,
                job_contrato_empleados,
                job_tipos_contrato
        WHERE   job_ingreso_empleados.documento_identidad_empleado = job_terceros.documento_identidad AND
                job_ingreso_empleados.codigo_empresa = job_empresas.codigo AND
                job_ingreso_empleados.codigo_sucursal_activo = job_sucursales.codigo AND
                job_ingreso_empleados.codigo_empresa = job_contrato_empleados.codigo_empresa AND
                job_ingreso_empleados.fecha_ingreso = job_contrato_empleados.fecha_ingreso AND
                job_ingreso_empleados.documento_identidad_empleado = job_contrato_empleados.documento_identidad_empleado AND
                job_tipos_contrato.codigo = job_contrato_empleados.codigo_tipo_contrato);"

    ),
    array("CREATE OR REPLACE ALGORITHM = MERGE VIEW job_consulta_contrato_empleado AS
    SELECT
        job_ingreso_empleados.codigo_empresa AS codigo_empresa,
        job_ingreso_empleados.documento_identidad_empleado AS documento_identidad_empleado,
        job_menu_terceros.NOMBRE_COMPLETO AS nombre_empleado,
        job_ingreso_empleados.fecha_ingreso AS fecha_ingreso,
        job_ingreso_empleados.fecha_vencimiento_contrato AS fecha_vencimiento_contrato,
        job_ingreso_empleados.fecha_retiro AS fecha_retiro_empresa,
        job_ingreso_empleados.codigo_motivo_retiro AS codigo_motivo_retiro_empresa,
        job_ingreso_empleados.riesgo_profesional AS riesgo_profesional,
        job_ingreso_empleados.manejo_auxilio_transporte AS manejo_auxilio_transporte,
        job_ingreso_empleados.estado AS estado,

        job_contrato_empleados.fecha_contrato AS fecha_contrato,
        job_contrato_empleados.codigo_tipo_contrato AS codigo_tipo_contrato,
        job_tipos_contrato.tipo_contratacion AS tipo_salario,
        job_contrato_empleados.fecha_cambio_contrato AS fecha_cambio_contrato,

        job_sucursal_contrato_empleados.codigo_sucursal AS codigo_sucursal,
        job_sucursal_contrato_empleados.fecha_ingreso_sucursal AS fecha_ingreso_sucursal,
        job_sucursal_contrato_empleados.codigo_empresa_auxiliar AS codigo_empresa_auxiliar,
        job_sucursal_contrato_empleados.codigo_anexo_contable AS codigo_anexo_contable,
        job_sucursal_contrato_empleados.codigo_auxiliar AS codigo_auxiliar,
        job_sucursal_contrato_empleados.codigo_planilla AS codigo_planilla,
        job_sucursal_contrato_empleados.codigo_turno_laboral AS codigo_turno_laboral,
        job_sucursal_contrato_empleados.codigo_motivo_retiro AS codigo_motivo_retiro_sucursal,
        job_sucursal_contrato_empleados.fecha_retiro AS fecha_retiro_sucursal,
        job_sucursal_contrato_empleados.codigo_transaccion_salario AS codigo_transaccion_salario,
        job_sucursal_contrato_empleados.codigo_transaccion_auxilio_transporte AS codigo_transaccion_auxilio_transporte,
        job_sucursal_contrato_empleados.forma_pago_auxilio AS forma_pago_auxilio,
        job_sucursal_contrato_empleados.codigo_transaccion_salud AS codigo_transaccion_salud,
        job_sucursal_contrato_empleados.forma_descuento_salud AS forma_descuento_salud,
        job_sucursal_contrato_empleados.codigo_transaccion_pension AS codigo_transaccion_pension,
        job_sucursal_contrato_empleados.forma_descuento_pension AS forma_descuento_pension,
        job_sucursal_contrato_empleados.codigo_transaccion_normales AS codigo_transaccion_normales,
        job_sucursal_contrato_empleados.codigo_transaccion_extras AS codigo_transaccion_extras,
        job_sucursal_contrato_empleados.codigo_transaccion_recargo_nocturno AS codigo_transaccion_recargo_nocturno,
        job_sucursal_contrato_empleados.codigo_transaccion_extras_nocturnas AS codigo_transaccion_extras_nocturnas,
        job_sucursal_contrato_empleados.codigo_transaccion_dominicales AS codigo_transaccion_dominicales,
        job_sucursal_contrato_empleados.codigo_transaccion_extras_dominicales AS codigo_transaccion_extras_dominicales,
        job_sucursal_contrato_empleados.codigo_transaccion_recargo_noche_dominicales AS codigo_transaccion_recargo_noche_dominicales,
        job_sucursal_contrato_empleados.codigo_transaccion_extras_noche_dominicales AS codigo_transaccion_extras_noche_dominicales,

        job_salario_sucursal_contrato.fecha_salario AS fecha_salario,
        job_salario_sucursal_contrato.fecha_retroactivo AS fecha_retroactivo,
        job_salario_sucursal_contrato.salario AS salario,
        job_salario_sucursal_contrato.valor_dia AS valor_dia,
        job_salario_sucursal_contrato.valor_hora AS valor_hora,

        job_cargo_contrato_empleados.fecha_inicia_cargo AS fecha_inicia_cargo,
        job_cargo_contrato_empleados.codigo_cargo AS codigo_cargo,
        job_cargo_contrato_empleados.fecha_termina AS fecha_termina_cargo,
        job_cargo_contrato_empleados.documento_identidad_jefe_inmediato AS documento_identidad_jefe_inmediato,

        job_departamento_seccion_contrato_empleado.fecha_inicia_departamento_seccion AS fecha_inicia_departamento_seccion,
        job_departamento_seccion_contrato_empleado.codigo_departamento_empresa AS codigo_departamento_empresa,
        job_departamento_seccion_contrato_empleado.codigo_seccion_empresa AS codigo_seccion_empresa,
        job_departamento_seccion_contrato_empleado.fecha_termina AS fecha_termina_seccion

    FROM
        job_ingreso_empleados,
        job_contrato_empleados,
        job_sucursal_contrato_empleados,
        job_salario_sucursal_contrato,
        job_cargo_contrato_empleados,
        job_departamento_seccion_contrato_empleado,
        job_menu_terceros,
        job_tipos_contrato
    WHERE
        job_menu_terceros.id = job_ingreso_empleados.documento_identidad_empleado AND

        job_ingreso_empleados.codigo_empresa = job_contrato_empleados.codigo_empresa AND
        job_ingreso_empleados.documento_identidad_empleado = job_contrato_empleados.documento_identidad_empleado AND
        job_ingreso_empleados.fecha_ingreso = job_contrato_empleados.fecha_ingreso AND

        job_ingreso_empleados.codigo_empresa = job_sucursal_contrato_empleados.codigo_empresa AND
        job_ingreso_empleados.documento_identidad_empleado = job_sucursal_contrato_empleados.documento_identidad_empleado AND
        job_ingreso_empleados.fecha_ingreso = job_sucursal_contrato_empleados.fecha_ingreso AND

        job_sucursal_contrato_empleados.codigo_empresa = job_salario_sucursal_contrato.codigo_empresa AND
        job_sucursal_contrato_empleados.documento_identidad_empleado = job_salario_sucursal_contrato.documento_identidad_empleado AND
        job_sucursal_contrato_empleados.fecha_ingreso = job_salario_sucursal_contrato.fecha_ingreso AND
        job_sucursal_contrato_empleados.codigo_sucursal = job_salario_sucursal_contrato.codigo_sucursal AND
        job_sucursal_contrato_empleados.fecha_ingreso_sucursal = job_salario_sucursal_contrato.fecha_ingreso_sucursal AND

        job_sucursal_contrato_empleados.codigo_empresa = job_cargo_contrato_empleados.codigo_empresa AND
        job_sucursal_contrato_empleados.documento_identidad_empleado = job_cargo_contrato_empleados.documento_identidad_empleado AND
        job_sucursal_contrato_empleados.fecha_ingreso = job_cargo_contrato_empleados.fecha_ingreso AND
        job_sucursal_contrato_empleados.codigo_sucursal = job_cargo_contrato_empleados.codigo_sucursal AND
        job_sucursal_contrato_empleados.fecha_ingreso_sucursal = job_cargo_contrato_empleados.fecha_ingreso_sucursal AND

        job_contrato_empleados.codigo_tipo_contrato = job_tipos_contrato.codigo AND

        job_sucursal_contrato_empleados.codigo_empresa = job_departamento_seccion_contrato_empleado.codigo_empresa AND
        job_sucursal_contrato_empleados.documento_identidad_empleado = job_departamento_seccion_contrato_empleado.documento_identidad_empleado AND
        job_sucursal_contrato_empleados.fecha_ingreso = job_departamento_seccion_contrato_empleado.fecha_ingreso AND
        job_sucursal_contrato_empleados.codigo_sucursal = job_departamento_seccion_contrato_empleado.codigo_sucursal AND
        job_sucursal_contrato_empleados.fecha_ingreso_sucursal = job_departamento_seccion_contrato_empleado.fecha_ingreso_sucursal;")
);
?>

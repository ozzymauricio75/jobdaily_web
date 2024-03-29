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
$tablas["movimientos_prima"] = array(
    "ano_generacion"                => "INT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo interno que identifica la planilla'",
    "mes_generacion"                => "INT(2) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo interno que identifica la planilla'",
    "codigo_planilla"               => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo interno que identifica la planilla'",
    "periodo_pago"                  => "ENUM('1','2','3','4','5','6','7','8','9') NOT NULL COMMENT '1->Mensual 2->Primera quincena 3->Segunda quincena 4->Primera semana 5->Segunda semana 6->Tercer semana 7->Cuarta semana 8->Quinta semana 9->Fecha unica'",
    "codigo_transaccion_contable"   => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id de la tabla de transacciones contables'",
     ///////////////////////////////
    "codigo_empresa"                => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo del almacen donde va a laborar'",
    "documento_identidad_empleado"  => "VARCHAR(12) NOT NULL COMMENT 'Documento de identidad que identifica el empleado en terceras personas'",
    "fecha_ingreso_empresa"         => "DATE NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado'",
    "codigo_sucursal"               => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo del almacen donde va a laborar'",
    "fecha_ingreso_sucursal"        => "DATE NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado'",
    ///////////////////////////////
    "fecha_pago_planilla"           => "DATE NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado'",
    "fecha_inicio_pago"             => "DATE NOT NULL COMMENT 'Fecha en la que inicia el periodo a liquidar'",
    "fecha_hasta_pago"              => "DATE NOT NULL COMMENT 'Fecha en la que termina el periodo a liquidar'",
    "fecha_inicio_promedio"         => "DATE NOT NULL COMMENT 'Fecha en la que inicia el calculo de promedios para liquidar'",
    "fecha_hasta_promedio"          => "DATE NOT NULL COMMENT 'Fecha en la que termina el calculo de promedios para liquidar'",
    ///////////////////////////////
    "codigo_empresa_auxiliar"       => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa del auxiliar contable'",
    "codigo_anexo_contable"         => "VARCHAR(3) NOT NULL COMMENT 'Codigo del anexo que permite dividir las cuentas'",
    "codigo_auxiliar_contable"      => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo donde se acumulara la informacion'",
    ///////////////////////////////
    "codigo_contable"               => "VARCHAR(15) NOT NULL COMMENT 'Codigo contable de la transaccion contable'",
    "sentido"                       => "ENUM('D','C') NOT NULL COMMENT 'D->Debito C->Credito'",
    "dias_liquidados"               => "SMALLINT(3) NOT NULL COMMENT 'Dias liquidados en el periodo'",
    "dias_promedio"                 => "SMALLINT(3) NOT NULL COMMENT 'Dias tomados para el promedio'",
    "salario"                       => "DECIMAL(11,2) NOT NULL COMMENT 'Valor mensual del salario'",
    "salario_promedio"              => "DECIMAL(11,2) NOT NULL COMMENT 'Valor del salario promedio'",
    "auxilio_transporte"            => "DECIMAL(11,2) NOT NULL COMMENT 'Valor del auxilio de tranporte'",
    "valor_ingresos_promedio"       => "DECIMAL(11,2) NOT NULL COMMENT 'Valor de todos los ingresos que acumulan para prima del periodo liquidado'",
    "valor_movimiento"              => "DECIMAL(11,2) NOT NULL COMMENT 'Valor diario del registro'",
    "contabilizado"                 => "ENUM('0','1','2') NOT NULL DEFAULT '0' COMMENT '0->No 1->Si 2->Trasladado'",
    "codigo_usuario_genera"         => "SMALLINT(4) UNSIGNED ZEROFILL  NOT NULL COMMENT 'Codigo usuario que genera el registro'",
    "fecha_registro"                => "DATETIME NOT NULL COMMENT 'Fecha de generación de la planilla'",
);


// Definición de llaves primarias
$llavesPrimarias["movimientos_prima"] = "ano_generacion,mes_generacion,codigo_planilla,periodo_pago,fecha_pago_planilla,documento_identidad_empleado,codigo_sucursal";

// Definición de llaves Foraneas
$llavesForaneas["movimientos_prima"] = array(
    array(
        // Nombre de la llave foranea
        "movimiento_prima_sucursal_contrato_empleado",
        // Nombre del campo en la tabla actual
        "codigo_empresa,documento_identidad_empleado,fecha_ingreso_empresa,codigo_sucursal,fecha_ingreso_sucursal",
        // Nombre de la tabla relacionada
        "sucursal_contrato_empleados",
        // Nombre del campo de la tabla relacionada
        "codigo_empresa,documento_identidad_empleado,fecha_ingreso,codigo_sucursal,fecha_ingreso_sucursal"
    ),
    array(
        // Nombre de la llave foranea
        "movimiento_prima_codigo_planilla",
        // Nombre del campo en la tabla actual
        "codigo_planilla",
        // Nombre de la tabla relacionada
        "planillas",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "movimiento_prima_transaccion_contable",
        // Nombre del campo en la tabla actual
        "codigo_transaccion_contable",
        // Nombre de la tabla relacionada
        "transacciones_contables_empleado",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "movimiento_prima_anexo",
        // Nombre del campo en la tabla actual
        "codigo_anexo_contable",
        // Nombre de la tabla relacionada
        "anexos_contables",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "movimiento_prima_auxiliar_contable",
        // Nombre del campo en la tabla actual
        "codigo_empresa_auxiliar,codigo_anexo_contable,codigo_auxiliar_contable",
        // Nombre de la tabla relacionada
        "auxiliares_contables",
        // Nombre del campo de la tabla relacionada
        "codigo_empresa,codigo_anexo_contable,codigo"
    ),
    array(
        // Nombre de la llave foranea
        "movimiento_prima_usuario",
        // Nombre del campo en la tabla actual
        "codigo_usuario_genera",
        // Nombre de la tabla relacionada
        "usuarios",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "movimiento_prima_plan_contable",
        // Nombre del campo en la tabla actual
        "codigo_contable",
        // Nombre de la tabla relacionada
        "plan_contable",
        // Nombre del campo de la tabla relacionada
        "codigo_contable"
    )
);


$registros["componentes"] = array(
   array(
        "id"            => "GESTLQPR",
        "padre"         => "SUBMPRPL",
        "id_modulo"     => "NOMINA",
        "orden"         => "25",
        "visible"       => "1",
        "carpeta"       => "liquidar_prima",
        "global"        => "0",
        "archivo"       => "liquidar_prima",
        "requiere_item" => "0",
        "tipo_enlace"   => "2"
    )
);

?>

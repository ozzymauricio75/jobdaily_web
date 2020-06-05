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
* modificarlo  bajo los t√©rminos de la Licencia P√∫blica General GNU
* publicada por la Fundaci√≥n para el Software Libre, ya sea la versi√≥n 3
* de la Licencia, o (a su elecci√≥n) cualquier versi√≥n posterior.
*
* Este programa se distribuye con la esperanza de que sea √∫til, pero
* SIN GARANT√çA ALGUNA; ni siquiera la garant√≠a impl√≠cita MERCANTIL o
* de APTITUD PARA UN PROP√ìITO DETERMINADO. Consulte los detalles de
* la Licencia P√∫blica General GNU para obtener una informaci√≥n m√°s
* detallada.
*
* Deber√≠a haber recibido una copia de la Licencia P√∫blica General GNU
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
**/

$borrarSiempre = false;
// DefiniciÛn de tablas

$tablas["movimiento_liquidacion_vacaciones"] = array(
    "forma_liquidacion"             => "ENUM('1','2') NOT NULL  COMMENT '1->Afecta en planilla  2->Liquidacion de total de vacaciones  3->cesantias  4->intereses/cesantias '",
    "estado_liquidacion"            => "ENUM('1','2','3','4')  NOT NULL  COMMENT '1->Activa  2->Denegada 3->Autorizada para pagar 4->Pagada'",
    "fecha_generacion"              => "DATETIME NOT NULL COMMENT 'Fecha en la se genero el tiempo'",
    "fecha_inicio_tiempo"           => "DATE NOT NULL COMMENT 'Fecha inicio en que inicia las vacaciones'",
    "fecha_final_tiempo"            => "DATE NOT NULL COMMENT 'Fecha inicio en que finaliza las vacaciones'",
    /////LLAVE_EN_TABLA_SUCURSAL_CONTRATO/////
    "codigo_empresa"                => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa donde labora el empleado'",
    "documento_identidad_empleado"  => "VARCHAR(12) NOT NULL COMMENT 'Documento de identidad empleado en la tabla sucursal contrato empleado'",
    "fecha_ingreso"                 => "DATE NOT NULL COMMENT 'Fecha en la que inicia labores el empleado'",
    "codigo_sucursal"               => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la sucursal donde labora el empleado'",
    "fecha_ingreso_sucursal"        => "DATE NOT NULL COMMENT 'Fecha en la que inicia labores en la sucursal el empleado'",
    ///////////////////////////////
    "codigo_empresa_auxiliar"       => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa para la llave de auxiliares'",
    "codigo_anexo_contable"         => "VARCHAR(3) NOT NULL COMMENT 'CÛdigo del anexo que permite dividir las cuentas'",
    "codigo_auxiliar_contable"      => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'CÛdigo donde se acumulara la informaciÛn'",
    ///////////////////////////////
    "codigo_transaccion_contable"   => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id de la tabla de transacciones contables del Empleado'",
    "codigo_transaccion_tiempo"     => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id de la tabla de transacciones de tiempo'",
    "codigo_contable"               => "VARCHAR(15) NOT NULL COMMENT 'Codigo contable de la transaccion contable'",
    "sentido"                       => "ENUM('D','C') NOT NULL COMMENT 'D->DÈbito C->CrÈdito'",
    ///////////////////////////////
    "dias_tomados"                  => "SMALLINT(3) NOT NULL COMMENT 'N˙mero de dÌas que el empleado ha tomado de los 15 dÌas h·biles de descanso remunerado por cada aÒo de trabajo'",
    "dias_disfrutado"               => "SMALLINT(3) NOT NULL COMMENT 'N˙mero de dÌas que el empleado esta realmente por fuera de su obligacion laboral'",
    "valor_movimiento"              => "DECIMAL(11,2) NOT NULL COMMENT 'Valor diario del registro'",
    "codigo_usuario_registra"       => "SMALLINT(4) UNSIGNED ZEROFILL  NOT NULL COMMENT 'Codigo del Usuario que genera el registro'",
    "codigo_usuario_modifica"       => "SMALLINT(4) UNSIGNED ZEROFILL  NULL COMMENT 'Codigo del ultimo Usuario que modifica el registro'"
);

$tablas["liquidaciones_movimientos_conceptos_vacaciones"] = array(
    "concepto"                      => "ENUM('1','2','3','4') NOT NULL  COMMENT '1->Salarios 2->Auxilio de transporte 3->Salud  4->Pension'",
    "estado_liquidacion"            => "ENUM('1','2','3','4') NOT NULL  COMMENT '1->Activa  2->Denegada 3->Autorizada para pagar 4->Pagada'",
    ///////////////////////////////////
    "fecha_inicio_tiempo"           => "DATE NOT NULL COMMENT 'Fecha inicio en que inicia las vacaciones'",
    "codigo_empresa"                => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa donde va a laborar'",
    "documento_identidad_empleado"  => "VARCHAR(12) NOT NULL COMMENT 'Documento de identidad empleado en la sucursales contrato empleados'",
    "fecha_generacion"              => "DATETIME NOT NULL COMMENT 'Fecha en la se genero el retiro'",
    "codigo_transaccion_contable"   => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id de la tabla de transacciones contables'",
     /////LLAVE_EN_TABLA_SUCURSAL_CONTRATO/////
    "fecha_ingreso"                 => "DATE NOT NULL COMMENT 'Fecha en la que inicia labores el empleado'",
    "codigo_sucursal"               => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la sucursal donde labora el empleado'",
    "fecha_ingreso_sucursal"        => "DATE NOT NULL COMMENT 'Fecha en la que inicia labores en la sucursal el empleado'",
    ///////////////////////////////
    "fecha_inicio_pago"             => "DATE NOT NULL COMMENT 'Fecha en la que inicia el calculo del movimiento'",
    "fecha_hasta_pago"              => "DATE NOT NULL COMMENT 'Fecha en la que final del calculo del movimiento'",
    ///////////////////////////////
    "codigo_empresa_auxiliar"       => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa del auxiliar contable'",
    "codigo_anexo_contable"         => "VARCHAR(3) NOT NULL COMMENT 'CÛdigo del anexo que permite dividir las cuentas'",
    ///tabla_auxiliares_contables
    "codigo_auxiliar_contable"      => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'CÛdigo donde se acumulara la informaciÛn'",
    ///////////////////////////////
    "codigo_contable"               => "VARCHAR(15) NOT NULL COMMENT 'Codigo contable de la transaccion contable'",
    "sentido"                       => "ENUM('D','C') NOT NULL COMMENT 'D->D√©bito C->Cr√©dito'",
    "dias_trabajados"               => "SMALLINT(3) NOT NULL COMMENT 'Dias laborados en el periodo'",
    "salario_mensual"               => "DECIMAL(11,2) NOT NULL COMMENT 'Valor mensual del salario'",
    "valor_movimiento"              => "DECIMAL(11,2) NOT NULL COMMENT 'Valor diario del registro'",
    "ibc"                           => "DECIMAL(11,2) NOT NULL COMMENT 'Solo se registra si el concepto es salud o pension'",
    "porcentaje_tasa"               => "DECIMAL(7,4) NOT NULL DEFAULT '0.0000' COMMENT 'Porcentaje de la tasa, si el concepto es salud o pension'",
    "codigo_usuario_registra"       => "SMALLINT(4) UNSIGNED ZEROFILL  NOT NULL COMMENT 'Codigo del Usuario que genera el registro'",
    "codigo_usuario_modifica"       => "SMALLINT(4) UNSIGNED ZEROFILL  NULL COMMENT 'Codigo del ultimo Usuario que modifica el registro'"
);

$tablas["pago_liquidaciones_vacaciones"] = array(
    "codigo_sucursal"                                   => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la sucursal donde labora el empleado'",
    "documento_identidad_empleado"                      => "VARCHAR(12) NOT NULL COMMENT 'Documento de identidad empleado en la tabla sucursal contrato empleado'",
    "fecha_inicio_tiempo"                               => "DATE NOT NULL COMMENT 'Fecha inicio en que inicia las vacaciones'",
    /////LLAVE_EN_TABLA_SUCURSAL_CONTRATO/////
    "codigo_empresa"                                    => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa donde labora el empleado'",
    "fecha_ingreso_sucursal"                            => "DATE NOT NULL COMMENT 'Fecha en la que inicia labores en la sucursal el empleado'",
    "fecha_ingreso"                                     => "DATE NOT NULL COMMENT 'Fecha en la que inicia labores el empleado'",
    ///////////////////////////////
    "codigo_empresa_auxiliar"                           => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa para la llave de auxiliares'",
    "codigo_anexo_contable"                             => "VARCHAR(3) NOT NULL COMMENT 'CÛdigo del anexo que permite dividir las cuentas'",
    "codigo_auxiliar_contable"                          => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'CÛdigo donde se acumulara la informaciÛn'",
    //////DATOS CUENTA AFECTA/////////
    "flujo_efectivo"                                    => "ENUM('1','2','3') NOT NULL COMMENT '1->No afecta flujo 2->Caja 3->Bancos'",
    "codigo_contable"                                   => "VARCHAR(15) NOT NULL COMMENT 'Codigo de la cuenta contable segun el PUC'",
    "sentido"                                           => "ENUM('D','C') NOT NULL DEFAULT 'D'  COMMENT 'D->DÈbito C->CrÈdito'",
    /////////CUENTA BANCARIA//////////
    "codigo_sucursal_pertence"                          => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'codigo de la sucursal con la que se relaciona la sucursal'",
    "tipo_documento_cuenta_bancaria"                    => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'CÛdigo asignado por el usuario'",
    "codigo_sucursal_banco"                             => "SMALLINT(2) UNSIGNED ZEROFILL NOT NULL COMMENT 'codigo de la tabla bancos'",
    "codigo_iso"                                        => "VARCHAR(2) NOT NULL COMMENT 'Llave principal de la tabla paises'",
    "codigo_dane_departamento"                          => "VARCHAR(2) NOT NULL COMMENT 'CÛdigo DANE'",
    "codigo_dane_municipio"                             => "VARCHAR(3) NOT NULL COMMENT 'CÛdigo DANE'",
    "codigo_banco"                                      => "SMALLINT(2) UNSIGNED ZEROFILL NOT NULL COMMENT 'id de la tabla bancos'",
    "consecutivo_cheque"                                => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo interno para la base de datos'",
    "numero"                                            => "VARCHAR(30) NOT NULL COMMENT 'Numero de la cuenta'",
    "codigo_tipo_documento"                             => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'relacionado con el condigo de la tabla tipos documentos'",
    ///llave_consecutivo docuemento/////
    "consecutivo_documento"                             => "INT(9) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo del documento'",
    "codigo_tipo_documento_consecutivo_documento"       => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo del tipo de documento'",
    "codigo_sucursal_consecutivo_documento"             => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'tabla planilla de la tabla de sucursales'",
    "fecha_registro_consecutivo_documento"              => "DATE NOT NULL COMMENT 'Fecha de generacion del consecutivo documento'",
    "documento_identidad_tercero_consecutivo_documento" => "VARCHAR(12) NOT NULL COMMENT 'N˙mero del documento de identidad'",
    /////////////////////////////////
    "valor_movimiento"                                  => "DECIMAL(11,2) NOT NULL COMMENT 'Valor diario del registro'",
    "codigo_usuario_registra"                           => "SMALLINT(4) UNSIGNED ZEROFILL  NOT NULL COMMENT 'Codigo del Usuario que genera el registro'",
    "codigo_usuario_modifica"                           => "SMALLINT(4) UNSIGNED ZEROFILL  NULL COMMENT 'Codigo del ultimo Usuario que modifica el registro'"
);

// DefiniciÛn de llaves primarias
$llavesPrimarias["movimiento_liquidacion_vacaciones"]              = "codigo_sucursal,documento_identidad_empleado,fecha_inicio_tiempo";
$llavesPrimarias["pago_liquidaciones_vacaciones"]                  = "codigo_sucursal,documento_identidad_empleado,fecha_inicio_tiempo";
$llavesPrimarias["liquidaciones_movimientos_conceptos_vacaciones"] = "codigo_sucursal,documento_identidad_empleado,fecha_inicio_tiempo,codigo_transaccion_contable";
// Definici{on de llaves Foraneas

$llavesForaneas["movimiento_liquidacion_vacaciones"] = array(
    array(
        // Nombre de la llave foranea
        "movimiento_liquidacion_vacaciones_sucursal_contrato",
        // Nombre del campo en la tabla actual
        "codigo_empresa,documento_identidad_empleado,fecha_ingreso,codigo_sucursal,fecha_ingreso_sucursal",
        // Nombre de la tabla relacionada
        "sucursal_contrato_empleados",
        // Nombre del campo de la tabla relacionada
        "codigo_empresa,documento_identidad_empleado,fecha_ingreso,codigo_sucursal,fecha_ingreso_sucursal"
    ),
    array(
        // Nombre de la llave foranea
        "movimiento_liquidacion_vacaciones_transaccion_tiempo",
        // Nombre del campo en la tabla actual
        "codigo_transaccion_tiempo",
        // Nombre de la tabla relacionada
        "transacciones_tiempo",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "movimiento_liquidacion_vacaciones_transaccion_contable",
        // Nombre del campo en la tabla actual
        "codigo_transaccion_contable",
        // Nombre de la tabla relacionada
        "transacciones_contables_empleado",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "movimiento_liquidacion_vacaciones_plan_contable",
        // Nombre del campo en la tabla actual
        "codigo_contable",
        // Nombre de la tabla relacionada
        "plan_contable",
        // Nombre del campo de la tabla relacionada
        "codigo_contable"
    ),
    array(
        // Nombre de la llave foranea
        "movimiento_liquidacion_vacaciones_usuarios_registra",
        // Nombre del campo en la tabla actual
        "codigo_usuario_registra",
        // Nombre de la tabla relacionada
        "usuarios",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "movimiento_liquidacion_vacaciones_usuarios_modifica",
        // Nombre del campo en la tabla actual
        "codigo_usuario_modifica",
        // Nombre de la tabla relacionada
        "usuarios",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "movimiento_liquidacion_vacaciones_anexo",
        // Nombre del campo en la tabla actual
        "codigo_anexo_contable",
        // Nombre de la tabla relacionada
        "anexos_contables",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "movimiento_liquidacion_vacaciones_auxiliar_contable",
        // Nombre del campo en la tabla actual
        "codigo_empresa_auxiliar,codigo_anexo_contable,codigo_auxiliar_contable",
        // Nombre de la tabla relacionada
        "auxiliares_contables",
        // Nombre del campo de la tabla relacionada
        "codigo_empresa,codigo_anexo_contable,codigo"
    )

);

$llavesForaneas["pago_liquidaciones_vacaciones"] = array(
    array(
        // Nombre de la llave foranea
        "pago_liquidaciones_vacaciones_sucursal_contrato",
        // Nombre del campo en la tabla actual
        "codigo_empresa,documento_identidad_empleado,fecha_ingreso,codigo_sucursal,fecha_ingreso_sucursal",
        // Nombre de la tabla relacionada
        "sucursal_contrato_empleados",
        // Nombre del campo de la tabla relacionada
        "codigo_empresa,documento_identidad_empleado,fecha_ingreso,codigo_sucursal,fecha_ingreso_sucursal"
    ),
    array(
        // Nombre de la llave foranea
        "pago_liquidaciones_vacaciones_plan_contable",
        // Nombre del campo en la tabla actual
        "codigo_contable",
        // Nombre de la tabla relacionada
        "plan_contable",
        // Nombre del campo de la tabla relacionada
        "codigo_contable"
    ),
    array(
        // Nombre de la llave foranea
        "pago_liquidaciones_vacaciones_usuarios_registra",
        // Nombre del campo en la tabla actual
        "codigo_usuario_registra",
        // Nombre de la tabla relacionada
        "usuarios",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "pago_liquidaciones_vacaciones_usuarios_modifica",
        // Nombre del campo en la tabla actual
        "codigo_usuario_modifica",
        // Nombre de la tabla relacionada
        "usuarios",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "pago_liquidaciones_vacaciones_anexo",
        // Nombre del campo en la tabla actual
        "codigo_anexo_contable",
        // Nombre de la tabla relacionada
        "anexos_contables",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "pago_liquidaciones_vacaciones_auxiliar_contable",
        // Nombre del campo en la tabla actual
        "codigo_empresa_auxiliar,codigo_anexo_contable,codigo_auxiliar_contable",
        // Nombre de la tabla relacionada
        "auxiliares_contables",
        // Nombre del campo de la tabla relacionada
        "codigo_empresa,codigo_anexo_contable,codigo"
    ),
    array(
        // Nombre de la llave foranea
        "pago_liquidaciones_vacaciones_cuentas_bancarias",
        // Nombre del campo en la tabla actual
        "codigo_sucursal_pertence,tipo_documento_cuenta_bancaria,codigo_sucursal_banco,codigo_iso,codigo_dane_departamento,codigo_dane_municipio,codigo_banco,numero",
        // Nombre de la tabla relacionada
        "cuentas_bancarias",
        // Nombre del campo de la tabla relacionada
        "codigo_sucursal,codigo_tipo_documento,codigo_sucursal_banco,codigo_iso,codigo_dane_departamento,codigo_dane_municipio,codigo_banco,numero"
    ),
    array(
        // Nombre de la llave foranea
        "pago_liquidaciones_vacaciones_tipo_documento",
        // Nombre del campo en la tabla actual
        "codigo_tipo_documento",
        // Nombre de la tabla relacionada
        "tipos_documentos",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        //  Nombre de la llave foranea
        "pago_liquidaciones_vacaciones_consecutivo_cheques",
        //  Nombre del campo en la tabla actual
        "codigo_sucursal_pertence,tipo_documento_cuenta_bancaria,codigo_banco,numero,consecutivo_cheque",
        //  Nombre de la tabla relacionada
        "consecutivo_cheques",
        //  Nombre del campo de la tabla relacionada
        "codigo_sucursal,codigo_tipo_documento,codigo_banco,numero,consecutivo"
    )
);


$llavesForaneas["liquidaciones_movimientos_conceptos_vacaciones"] = array(
    array(
        // Nombre de la llave foranea
        "liquidaciones_movimientos_conceptos_sucursal_contrato",
        // Nombre del campo en la tabla actual
        "codigo_empresa,documento_identidad_empleado,fecha_ingreso,codigo_sucursal,fecha_ingreso_sucursal",
        // Nombre de la tabla relacionada
        "sucursal_contrato_empleados",
        // Nombre del campo de la tabla relacionada
        "codigo_empresa,documento_identidad_empleado,fecha_ingreso,codigo_sucursal,fecha_ingreso_sucursal"
    ),array(
        // Nombre de la llave foranea
        "liquidaciones_movimientos_conceptos_transaccion_contable",
        // Nombre del campo en la tabla actual
        "codigo_transaccion_contable",
        // Nombre de la tabla relacionada
        "transacciones_contables_empleado",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),array(
        // Nombre de la llave foranea
        "liquidaciones_movimientos_conceptos_plan_contable",
        // Nombre del campo en la tabla actual
        "codigo_contable",
        // Nombre de la tabla relacionada
        "plan_contable",
        // Nombre del campo de la tabla relacionada
        "codigo_contable"
    ),array(
        // Nombre de la llave foranea
        "liquidaciones_movimientos_conceptos_usuarios_registra",
        // Nombre del campo en la tabla actual
        "codigo_usuario_registra",
        // Nombre de la tabla relacionada
        "usuarios",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),array(
        // Nombre de la llave foranea
        "liquidaciones_movimientos_conceptos_usuarios_modifica",
        // Nombre del campo en la tabla actual
        "codigo_usuario_modifica",
        // Nombre de la tabla relacionada
        "usuarios",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),array(
        // Nombre de la llave foranea
        "liquidaciones_movimientos_conceptos_anexo",
        // Nombre del campo en la tabla actual
        "codigo_anexo_contable",
        // Nombre de la tabla relacionada
        "anexos_contables",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),array(
        // Nombre de la llave foranea
        "mliquidaciones_movimientos_conceptos_contable",
        // Nombre del campo en la tabla actual
        "codigo_empresa_auxiliar,codigo_anexo_contable,codigo_auxiliar_contable",
        // Nombre de la tabla relacionada
        "auxiliares_contables",
        // Nombre del campo de la tabla relacionada
        "codigo_empresa,codigo_anexo_contable,codigo"
    )
);


$registros["componentes"] = array(
   
    array(
        "id"            => "GESTVATO",
        "padre"         => "SUBMPRSO",
        "id_modulo"     => "NOMINA",
        "orden"         => "02",
        "visible"       => "1",
        "carpeta"       => "liquidacion_vacaciones",
        "global"        => "0",
        "archivo"       => "menu",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "ADICVATO",
        "padre"         => "GESTVATO",
        "id_modulo"     => "NOMINA",
        "orden"         => "10",
        "visible"       => "0",
        "carpeta"       => "liquidacion_vacaciones",
        "global"        => "0",
        "archivo"       => "adicionar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "CONSVATO",
        "padre"         => "GESTVATO",
        "id_modulo"     => "NOMINA",
        "orden"         => "20",
        "visible"       => "0",
        "carpeta"       => "liquidacion_vacaciones",
        "global"        => "0",
        "archivo"       => "consultar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "AUTOPAGO",
        "padre"         => "GESTVATO",
        "id_modulo"     => "NOMINA",
        "orden"         => "30",
        "visible"       => "0",
        "carpeta"       => "liquidacion_vacaciones",
        "global"        => "0",
        "archivo"       => "autorizar_pago",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "LIQUPAGA",
        "padre"         => "GESTVATO",
        "id_modulo"     => "NOMINA",
        "orden"         => "40",
        "visible"       => "0",
        "carpeta"       => "liquidacion_vacaciones",
        "global"        => "0",
        "archivo"       => "pagar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "MODIVATO",
        "padre"         => "GESTVATO",
        "id_modulo"     => "NOMINA",
        "orden"         => "30",
        "visible"       => "0",
        "carpeta"       => "liquidacion_vacaciones",
        "global"        => "0",
        "archivo"       => "modificar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "ELIMVATO",
        "padre"         => "GESTVATO",
        "id_modulo"     => "NOMINA",
        "orden"         => "40",
        "visible"       => "0",
        "carpeta"       => "liquidacion_vacaciones",
        "global"        => "0",
        "archivo"       => "eliminar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "LISTVATO",
        "padre"         => "GESTVATO",
        "id_modulo"     => "NOMINA",
        "orden"         => "50",
        "visible"       => "0",
        "carpeta"       => "liquidacion_vacaciones",
        "global"        => "0",
        "archivo"       => "listar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_movimiento_liquidacion_vacaciones AS
        SELECT CONCAT(job_movimiento_liquidacion_vacaciones.codigo_sucursal,'|',job_movimiento_liquidacion_vacaciones.documento_identidad_empleado,'|',job_movimiento_liquidacion_vacaciones.fecha_inicio_tiempo,'|',job_movimiento_liquidacion_vacaciones.estado_liquidacion) AS id,
        job_sucursales.codigo AS id_sucursal,
        job_sucursales.nombre AS ALMACEN,
        IF(job_terceros.tipo_persona = '1' OR job_terceros.tipo_persona = '4',
            CONCAT(
                job_terceros.primer_nombre,' ',
                IF (job_terceros.segundo_nombre IS NOT NULL AND job_terceros.segundo_nombre != '',CONCAT(job_terceros.segundo_nombre,' '),''),
                job_terceros.primer_apellido,' ',
                IF (job_terceros.segundo_apellido IS NOT NULL AND job_terceros.segundo_apellido != '',job_terceros.segundo_apellido,'')
            ),
            job_terceros.razon_social
        ) AS EMPLEADO,
        DATE_FORMAT(job_movimiento_liquidacion_vacaciones.fecha_inicio_tiempo, '%Y-%m-%d') AS FECHA_INCAPACIDAD_TIEMPO,
       CONCAT('ESTADO_', job_movimiento_liquidacion_vacaciones.estado_liquidacion) AS ESTADO

        FROM job_terceros,job_aspirantes,job_sucursal_contrato_empleados,job_movimiento_liquidacion_vacaciones, job_sucursales
        WHERE job_sucursal_contrato_empleados.documento_identidad_empleado = job_aspirantes.documento_identidad
        AND job_aspirantes.documento_identidad = job_terceros.documento_identidad
        AND job_movimiento_liquidacion_vacaciones.documento_identidad_empleado = job_sucursal_contrato_empleados.documento_identidad_empleado
        AND job_movimiento_liquidacion_vacaciones.codigo_sucursal = job_sucursales.codigo
        GROUP BY job_movimiento_liquidacion_vacaciones.codigo_sucursal,job_movimiento_liquidacion_vacaciones.documento_identidad_empleado,job_movimiento_liquidacion_vacaciones.fecha_inicio_tiempo;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_movimiento_liquidacion_vacaciones AS
        SELECT CONCAT(job_movimiento_liquidacion_vacaciones.codigo_sucursal,'|',job_movimiento_liquidacion_vacaciones.documento_identidad_empleado,'|',job_movimiento_liquidacion_vacaciones.fecha_inicio_tiempo) AS id,
        job_sucursales.codigo AS id_sucursal,
        job_sucursales.nombre AS ALMACEN,
        IF(job_terceros.tipo_persona = '1' OR job_terceros.tipo_persona = '4',
            CONCAT(
                job_terceros.primer_nombre,' ',
                IF (job_terceros.segundo_nombre IS NOT NULL AND job_terceros.segundo_nombre != '',CONCAT(job_terceros.segundo_nombre,' '),''),
                job_terceros.primer_apellido,' ',
                IF (job_terceros.segundo_apellido IS NOT NULL AND job_terceros.segundo_apellido != '',job_terceros.segundo_apellido,'')
            ),
            job_terceros.razon_social
        ) AS EMPLEADO,
        DATE_FORMAT(job_movimiento_liquidacion_vacaciones.fecha_inicio_tiempo, '%Y-%m-%d') AS FECHA_INCAPACIDAD_TIEMPO

        FROM job_terceros,job_aspirantes,job_sucursal_contrato_empleados,job_movimiento_liquidacion_vacaciones, job_sucursales
        WHERE job_sucursal_contrato_empleados.documento_identidad_empleado = job_aspirantes.documento_identidad
        AND job_aspirantes.documento_identidad = job_terceros.documento_identidad
        AND job_movimiento_liquidacion_vacaciones.documento_identidad_empleado = job_sucursal_contrato_empleados.documento_identidad_empleado
        AND job_movimiento_liquidacion_vacaciones.codigo_sucursal = job_sucursales.codigo
        GROUP BY job_movimiento_liquidacion_vacaciones.codigo_sucursal,job_movimiento_liquidacion_vacaciones.documento_identidad_empleado,job_movimiento_liquidacion_vacaciones.fecha_inicio_tiempo;"
    )
);
?>

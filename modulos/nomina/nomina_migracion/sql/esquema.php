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

$tablas["movimientos_nomina_migracion"] = array(
    "ano_generacion"                => "INT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo interno que identifica la planilla'",
    "mes_generacion"                => "INT(2) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo interno que identifica la planilla'",
    "codigo_planilla"               => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo interno que identifica la planilla'",
    "periodo_pago"                  => "ENUM('1','2','3','4','5','6','7','8','9') NOT NULL COMMENT '1->Mensual 2->Primera quincena 3->Segunda quincena 4->Primera semana 5->Segunda semana 6->Tercer semana 7->Cuarta semana 8->Quinta semana 9->Fecha unica'",
    "codigo_transaccion_contable"   => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la tabla de transacciones contables empleados'",
    "consecutivo"                   => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo para el movimiento'",
     ///////////////////////////////
    "codigo_empresa"                => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo del almacen donde va a laborar'",
    "documento_identidad_empleado"  => "VARCHAR(12) NOT NULL COMMENT 'Documento de identidad que identifica el empleado en terceras personas'",
    "fecha_ingreso_empresa"         => "DATE NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado'",
    "codigo_sucursal"               => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo del almacen donde va a laborar'",
    "fecha_ingreso_sucursal"        => "DATE NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado'",
    ///////////////////////////////
    "fecha_pago_planilla"           => "DATE NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado'",
    ///////////////////////////////
    "codigo_empresa_auxiliar"       => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa del auxiliar contable'",
    "codigo_anexo_contable"         => "VARCHAR(3) NOT NULL COMMENT 'Codigo del anexo que permite dividir las cuentas'",
    "codigo_auxiliar_contable"      => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo donde se acumulara la informacion'",
    ///////////////////////////////
    "codigo_contable"               => "VARCHAR(15) NOT NULL COMMENT 'Codigo contable de la transaccion contable'",
    "sentido"                       => "ENUM('D','C') NOT NULL COMMENT 'D->Debito C->Credito'",
    "valor_movimiento"              => "DECIMAL(11,2) NOT NULL COMMENT 'Valor diario del registro'",
    "contabilizado"                 => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT '0->No 1->Si'",
    "codigo_usuario_genera"         => "SMALLINT(4) UNSIGNED ZEROFILL  NOT NULL COMMENT 'Codigo usuario que genera el registro'",
    "fecha_registro"                => "DATETIME NOT NULL COMMENT 'Fecha de generación del movimiento'",
    "codigo_usuario_modifica"       => "SMALLINT(4) UNSIGNED ZEROFILL  NOT NULL COMMENT 'Codigo usuario que genera el registro'",
    "fecha_modificacion"            => "TIMESTAMP NOT NULL COMMENT 'Fecha de modificación de l movimiento'",
);


// Definición de llaves primarias
$llavesPrimarias["movimientos_nomina_migracion"] = "ano_generacion,mes_generacion,codigo_planilla,periodo_pago,codigo_transaccion_contable,consecutivo";

// Definición de llaves Foraneas
$llavesForaneas["movimientos_nomina_migracion"] = array(
    array(
        // Nombre de la llave foranea
        "movimiento_nomina_migracion_sucursal_contrato_empleado",
        // Nombre del campo en la tabla actual
        "codigo_empresa,documento_identidad_empleado,fecha_ingreso_empresa,codigo_sucursal,fecha_ingreso_sucursal",
        // Nombre de la tabla relacionada
        "sucursal_contrato_empleados",
        // Nombre del campo de la tabla relacionada
        "codigo_empresa,documento_identidad_empleado,fecha_ingreso,codigo_sucursal,fecha_ingreso_sucursal"
    ),
    array(
        // Nombre de la llave foranea
        "movimiento_nomina_migracion_codigo_planilla",
        // Nombre del campo en la tabla actual
        "codigo_planilla",
        // Nombre de la tabla relacionada
        "planillas",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "movimiento_nomina_migracion_transaccion_contable",
        // Nombre del campo en la tabla actual
        "codigo_transaccion_contable",
        // Nombre de la tabla relacionada
        "transacciones_contables_empleado",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "movimiento_nomina_migracion_anexo",
        // Nombre del campo en la tabla actual
        "codigo_anexo_contable",
        // Nombre de la tabla relacionada
        "anexos_contables",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "movimiento_nomina_migracion_auxiliar_contable",
        // Nombre del campo en la tabla actual
        "codigo_empresa_auxiliar,codigo_anexo_contable,codigo_auxiliar_contable",
        // Nombre de la tabla relacionada
        "auxiliares_contables",
        // Nombre del campo de la tabla relacionada
        "codigo_empresa,codigo_anexo_contable,codigo"
    ),
    array(
        // Nombre de la llave foranea
        "movimiento_nomina_migracion_usuario",
        // Nombre del campo en la tabla actual
        "codigo_usuario_genera",
        // Nombre de la tabla relacionada
        "usuarios",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "movimiento_nomina_migracion_usuario_modifica",
        // Nombre del campo en la tabla actual
        "codigo_usuario_modifica",
        // Nombre de la tabla relacionada
        "usuarios",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "movimiento_nomina_migracion_plan_contable",
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
        "id"            => "GESTNMIG",
        "padre"         => "SUBMPENO",
        "id_modulo"     => "NOMINA",
        "visible"       => "1",
        "orden"         => "100",
        "carpeta"       => "nomina_migracion",
        "archivo"       => "menu",
        "global"        => "0",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "ADICNMIG",
        "padre"         => "GESTNMIG",
        "id_modulo"     => "NOMINA",
        "visible"       => "0",
        "orden"         => "10",
        "carpeta"       => "nomina_migracion",
        "archivo"       => "adicionar",
        "global"        => "0",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "CONSNMIG",
        "padre"         => "GESTNMIG",
        "id_modulo"     => "NOMINA",
        "visible"       => "0",
        "orden"         => "20",
        "carpeta"       => "nomina_migracion",
        "archivo"       => "consultar",
        "global"        => "0",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "MODINMIG",
        "padre"         => "GESTNMIG",
        "id_modulo"     => "NOMINA",
        "visible"       => "0",
        "orden"         => "30",
        "carpeta"       => "nomina_migracion",
        "archivo"       => "modificar",
        "global"        => "0",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "ELIMNMIG",
        "padre"         => "GESTNMIG",
        "id_modulo"     => "NOMINA",
        "visible"       => "0",
        "orden"         => "40",
        "carpeta"       => "nomina_migracion",
        "archivo"       => "eliminar",
        "global"        => "0",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "LISTNMIG",
        "padre"         => "GESTNMIG",
        "id_modulo"     => "NOMINA",
        "visible"       => "0",
        "orden"         => "50",
        "carpeta"       => "nomina_migracion",
        "archivo"       => "listar",
        "global"        => "0",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    )
);

$vistas = array(
    array("CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_nomina_migracion AS
        SELECT
            CONCAT(
                ano_generacion,'|',mes_generacion,'|',codigo_planilla,'|',periodo_pago,'|',codigo_transaccion_contable,'|',consecutivo
            ) AS id,
            job_movimientos_nomina_migracion.documento_identidad_empleado AS DOCUMENTO,
            job_menu_terceros.NOMBRE_COMPLETO AS EMPLEADO,
            job_transacciones_contables_empleado.descripcion AS TRANSACCION_CONTABLE,
            job_movimientos_nomina_migracion.fecha_pago_planilla AS FECHA_PAGO_PLANILLA,
            FORMAT(job_movimientos_nomina_migracion.valor_movimiento,0) AS VALOR_MOVIMIENTO
        FROM
            job_movimientos_nomina_migracion,
            job_transacciones_contables_empleado,
            job_menu_terceros
        WHERE
            job_movimientos_nomina_migracion.codigo_transaccion_contable = job_transacciones_contables_empleado.codigo AND
            job_movimientos_nomina_migracion.documento_identidad_empleado  = job_menu_terceros.id;"
    ),
    array("CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_nomina_migracion AS
        SELECT
            CONCAT(
                ano_generacion,'|',mes_generacion,'|',codigo_planilla,'|',periodo_pago,'|',codigo_transaccion_contable,'|',consecutivo
            ) AS id,
            job_movimientos_nomina_migracion.documento_identidad_empleado AS documento,
            job_menu_terceros.NOMBRE_COMPLETO AS empleado,
            job_transacciones_contables_empleado.descripcion AS transaccion_contable,
            job_movimientos_nomina_migracion.fecha_pago_planilla AS fecha_pago_planilla,
            FORMAT(job_movimientos_nomina_migracion.valor_movimiento,0) AS valor_movimiento
        FROM
            job_movimientos_nomina_migracion,
            job_transacciones_contables_empleado,
            job_menu_terceros
        WHERE
            job_movimientos_nomina_migracion.codigo_transaccion_contable = job_transacciones_contables_empleado.codigo AND
            job_movimientos_nomina_migracion.documento_identidad_empleado  = job_menu_terceros.id;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_consulta_movimientos_nomina_migracion AS
        SELECT
            job_movimientos_nomina_migracion.ano_generacion AS ano_generacion,
            job_movimientos_nomina_migracion.mes_generacion AS mes_generacion,
            job_movimientos_nomina_migracion.codigo_planilla AS codigo_planilla,
            job_movimientos_nomina_migracion.periodo_pago AS periodo_pago,
            job_movimientos_nomina_migracion.codigo_transaccion_contable AS codigo_transaccion_contable,
            job_movimientos_nomina_migracion.consecutivo AS consecutivo,
            job_movimientos_nomina_migracion.codigo_empresa AS codigo_empresa,
            (job_movimientos_nomina_migracion.documento_identidad_empleado * 1) AS documento_identidad_empleado,
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
            job_movimientos_nomina_migracion.fecha_ingreso_empresa AS fecha_ingreso_empresa,
            job_movimientos_nomina_migracion.codigo_sucursal AS codigo_sucursal,
            job_movimientos_nomina_migracion.fecha_ingreso_sucursal AS fecha_ingreso_sucursal,
            job_movimientos_nomina_migracion.fecha_pago_planilla AS fecha_pago_planilla,
            job_movimientos_nomina_migracion.codigo_empresa_auxiliar AS codigo_empresa_auxiliar,
            job_movimientos_nomina_migracion.codigo_anexo_contable AS codigo_anexo_contable,
            job_movimientos_nomina_migracion.codigo_auxiliar_contable AS codigo_auxiliar_contable,
            job_movimientos_nomina_migracion.codigo_contable AS codigo_contable,
            job_movimientos_nomina_migracion.sentido AS sentido,
            job_movimientos_nomina_migracion.valor_movimiento AS valor_movimiento,
            job_movimientos_nomina_migracion.contabilizado AS contabilizado,
            job_movimientos_nomina_migracion.codigo_usuario_genera AS codigo_usuario_genera,
            job_movimientos_nomina_migracion.fecha_registro AS fecha_registro,
            job_movimientos_nomina_migracion.codigo_usuario_modifica AS codigo_usuario_modifica,
            job_movimientos_nomina_migracion.fecha_modificacion AS fecha_modificacion
        FROM
            job_movimientos_nomina_migracion,
            job_terceros
        WHERE
            job_movimientos_nomina_migracion.documento_identidad_empleado = job_terceros.documento_identidad;"
    )
);
?>

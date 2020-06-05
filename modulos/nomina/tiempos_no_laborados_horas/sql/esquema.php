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
$tablas["movimiento_tiempos_no_laborados_horas"] = array(
    "fecha_generacion"             => "DATETIME NOT NULL COMMENT 'Fecha en la segenero el movimiento de tiempo'",
    "codigo_empresa"               => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa donde va a laborar'",
    "documento_identidad_empleado" => "VARCHAR(12) NOT NULL COMMENT 'Documento de identidad empleado en la sucursales contrato empleados'",
    "fecha_ingreso"                => "DATE NOT NULL COMMENT 'Fecha en la que ingreso el empleado a la empresa'",
    "codigo_sucursal"              => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id de la tabla de sucursales'",
    "fecha_ingreso_sucursal"       => "DATE NOT NULL COMMENT 'Fecha en la que inicia labores en la sucursal el empleado'",
    "consecutivo"                  => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo interno para la base de datos'",
    ///////////////////////////////
    "codigo_planilla"              => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id de la tabla de planillas'",
    ///////////////////////////////
    "fecha_registro"               => "DATE NOT NULL COMMENT 'Fecha en la que ingreso el empleado a la empresa'",
    "hora_inicio"                  => "TIME NOT NULL COMMENT 'Hora en que inicia el turno'",
    "hora_fin"                     => "TIME NOT NULL COMMENT 'Hora en que finaliza el turno'",
    ///////////////////////////////
    "codigo_transaccion_tiempo"    => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id de la tabla de transacciones de tiempo'",
    "codigo_transaccion_contable"  => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id de la tabla de transacciones contables'",
    "codigo_contable"              => "INT(4) NOT NULL COMMENT 'Codigo contable de la transaccion contable'",
    "sentido"                      => "ENUM('D','C') NOT NULL COMMENT 'D->Debito C->Credito'",
    "tasa"                         => "DECIMAL(7,4) NOT NULL DEFAULT '0' COMMENT 'Porcentaje que corresponde sobre la hora de salario'",
    ///////////////////////////////
    "ano_generacion"               => "INT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Anio de la generacion la planilla'",
    "mes_generacion"               => "INT(2) UNSIGNED ZEROFILL NOT NULL COMMENT 'Mes de generacion de la planilla'",
    "fecha_pago_planilla"          => "DATE NOT NULL COMMENT 'Fecha rango de pago de la planilla'",
    "codigo_empresa_auxiliar"      => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa para la llave de auxiliares'",
    "codigo_anexo_contable"        => "VARCHAR(3) NOT NULL COMMENT 'Codigo del anexo que permite dividir las cuentas'",
    "codigo_auxiliar_contable"     => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo donde se acumulara la informacion'",
    "periodo_pago"                 => "ENUM('1','2','3','4','5','6','7','8','9') NOT NULL COMMENT '1->Mensual 2->Primera quincena 3->Segunda quincena 4->Primera semana 5->Segunda semana 6->Tercer semana 7->Cuarta semana 8->Quinta semana 9->Fecha unica'",
    ///////////////////////////////
    "cantidad_minutos"             => "SMALLINT(4) NOT NULL COMMENT 'Cantidad de minutos trabajadas'",
    "valor_hora_salario"           => "DECIMAL(15,2) NOT NULL COMMENT 'Valor hora del salario'",
    "valor_movimiento"             => "DECIMAL(15,2) NOT NULL DEFAULT 0 COMMENT 'Valor del movimiento que se genero'",
    "contabilizado"                => "ENUM('0','1','2') NOT NULL DEFAULT '0' COMMENT 'Esta contabilizado 0->No 1->Si 2->Leido para liquidar salario'",
    "codigo_motivo_no_laboral"     => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id de la tabla de motivos de incapacidad'",
    "codigo_usuario_registra"      => "SMALLINT(4) UNSIGNED ZEROFILL  NOT NULL COMMENT 'Codigo del Usuario que genera el registro'",
    "codigo_usuario_modifica"      => "SMALLINT(4) UNSIGNED ZEROFILL  NOT NULL COMMENT 'Codigo del ultimo Usuario que modifica el registro'"
);

// Definicion de llaves primarias
$llavesPrimarias["movimiento_tiempos_no_laborados_horas"] = "codigo_empresa,documento_identidad_empleado,fecha_ingreso,codigo_sucursal,fecha_ingreso_sucursal,consecutivo";

// Definicion de llaves Foraneas
$llavesForaneas["movimiento_tiempos_no_laborados_horas"] = array(
    array(
        // Nombre de la llave foranea
        "movimiento_tiempos_no_laborados_horas_ingreso_empleados",
        // Nombre del campo en la tabla actual
        "codigo_empresa,documento_identidad_empleado,fecha_ingreso,codigo_sucursal,fecha_ingreso_sucursal",
        // Nombre de la tabla relacionada
        "sucursal_contrato_empleados",
        // Nombre del campo de la tabla relacionada
        "codigo_empresa,documento_identidad_empleado,fecha_ingreso,codigo_sucursal,fecha_ingreso_sucursal"
    ),
    array(
        // Nombre de la llave foranea
        "movimiento_tiempos_no_laborados_horas_motivo",
        // Nombre del campo en la tabla actual
        "codigo_motivo_no_laboral",
        // Nombre de la tabla relacionada
        "motivos_tiempo_no_laborado",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "movimiento_tiempos_no_laborados_horas_codigo_planilla",
        // Nombre del campo en la tabla actual
        "codigo_planilla",
        // Nombre de la tabla relacionada
        "planillas",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "movimiento_tiempos_no_laborados_horas_transaccion_contable",
        // Nombre del campo en la tabla actual
        "codigo_transaccion_contable",
        // Nombre de la tabla relacionada
        "transacciones_contables_empleado",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "movimiento_tiempos_no_laborados_horas_transaccion_tiempo",
        // Nombre del campo en la tabla actual
        "codigo_transaccion_tiempo",
        // Nombre de la tabla relacionada
        "transacciones_tiempo",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "movimiento_tiempos_no_laborados_horass_usuarios_registra",
        // Nombre del campo en la tabla actual
        "codigo_usuario_registra",
        // Nombre de la tabla relacionada
        "usuarios",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "movimiento_tiempos_no_laborados_horas_usuarios_modifica",
        // Nombre del campo en la tabla actual
        "codigo_usuario_modifica",
        // Nombre de la tabla relacionada
        "usuarios",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "movimiento_tiempos_no_laborados_horas_anexo",
        // Nombre del campo en la tabla actual
        "codigo_anexo_contable",
        // Nombre de la tabla relacionada
        "anexos_contables",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "movimiento_tiempos_no_laborados_horas_auxiliar_contable",
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
        "id"            => "GESTRETH",
        "padre"         => "SUBMRETL",
        "id_modulo"     => "NOMINA",
        "orden"         => "30",
        "visible"       => "1",
        "carpeta"       => "tiempos_no_laborados_horas",
        "global"        => "0",
        "archivo"       => "menu",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "ADICRETH",
        "padre"         => "GESTRETH",
        "id_modulo"     => "NOMINA",
        "orden"         => "10",
        "visible"       => "0",
        "carpeta"       => "tiempos_no_laborados_horas",
        "global"        => "0",
        "archivo"       => "adicionar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "CONSRETH",
        "padre"         => "GESTRETH",
        "id_modulo"     => "NOMINA",
        "orden"         => "20",
        "visible"       => "0",
        "carpeta"       => "tiempos_no_laborados_horas",
        "global"        => "0",
        "archivo"       => "consultar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "MODIRETH",
        "padre"         => "GESTRETH",
        "id_modulo"     => "NOMINA",
        "orden"         => "30",
        "visible"       => "0",
        "carpeta"       => "tiempos_no_laborados_horas",
        "global"        => "0",
        "archivo"       => "modificar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "ELIMRETH",
        "padre"         => "GESTRETH",
        "id_modulo"     => "NOMINA",
        "orden"         => "40",
        "visible"       => "0",
        "carpeta"       => "tiempos_no_laborados_horas",
        "global"        => "0",
        "archivo"       => "eliminar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "LISTRETH",
        "padre"         => "GESTRETH",
        "id_modulo"     => "NOMINA",
        "orden"         => "40",
        "visible"       => "0",
        "carpeta"       => "tiempos_no_laborados_horas",
        "global"        => "0",
        "archivo"       => "listar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_movimiento_tiempos_no_laborados_horas AS
        SELECT CONCAT(job_movimiento_tiempos_no_laborados_horas.codigo_sucursal,'|',job_movimiento_tiempos_no_laborados_horas.hora_fin,'|',job_movimiento_tiempos_no_laborados_horas.hora_inicio,'|',job_movimiento_tiempos_no_laborados_horas.fecha_registro,'|',job_movimiento_tiempos_no_laborados_horas.documento_identidad_empleado) AS id,
        job_sucursales.codigo AS id_sucursal,
        job_sucursales.nombre AS SUCURSAL,
        IF(job_terceros.tipo_persona = '1' OR job_terceros.tipo_persona = '4',
            CONCAT(
                job_terceros.primer_nombre,' ',
                IF (job_terceros.segundo_nombre IS NOT NULL AND job_terceros.segundo_nombre != '',CONCAT(job_terceros.segundo_nombre,' '),''),
                job_terceros.primer_apellido,' ',
                IF (job_terceros.segundo_apellido IS NOT NULL AND job_terceros.segundo_apellido != '',job_terceros.segundo_apellido,'')
            ),
            job_terceros.razon_social
        ) AS EMPLEADO,
        job_movimiento_tiempos_no_laborados_horas.fecha_registro AS FECHA_RFEPORTE,
        SEC_TO_TIME(SUM((job_movimiento_tiempos_no_laborados_horas.cantidad_minutos*60))) AS CANTIDAD
        FROM job_terceros,job_movimiento_tiempos_no_laborados_horas, job_sucursales
        WHERE job_movimiento_tiempos_no_laborados_horas.documento_identidad_empleado = job_terceros.documento_identidad
        AND job_movimiento_tiempos_no_laborados_horas.codigo_sucursal = job_sucursales.codigo
        GROUP BY job_movimiento_tiempos_no_laborados_horas.fecha_registro,job_movimiento_tiempos_no_laborados_horas.hora_inicio,job_movimiento_tiempos_no_laborados_horas.hora_fin
        ORDER BY job_movimiento_tiempos_no_laborados_horas.documento_identidad_empleado, job_movimiento_tiempos_no_laborados_horas.fecha_registro;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_movimiento_tiempos_no_laborados_horas AS
        SELECT CONCAT(job_movimiento_tiempos_no_laborados_horas.codigo_sucursal,'|',job_movimiento_tiempos_no_laborados_horas.hora_fin,'|',job_movimiento_tiempos_no_laborados_horas.hora_inicio,'|',job_movimiento_tiempos_no_laborados_horas.fecha_registro,'|',job_movimiento_tiempos_no_laborados_horas.documento_identidad_empleado) AS id,
        job_sucursales.codigo AS id_sucursal,
        job_sucursales.nombre AS SUCURSAL,
        IF(job_terceros.tipo_persona = '1' OR job_terceros.tipo_persona = '4',
            CONCAT(
                job_terceros.primer_nombre,' ',
                IF (job_terceros.segundo_nombre IS NOT NULL AND job_terceros.segundo_nombre != '',CONCAT(job_terceros.segundo_nombre,' '),''),
                job_terceros.primer_apellido,' ',
                IF (job_terceros.segundo_apellido IS NOT NULL AND job_terceros.segundo_apellido != '',job_terceros.segundo_apellido,'')
            ),
            job_terceros.razon_social
        ) AS EMPLEADO,
        job_movimiento_tiempos_no_laborados_horas.fecha_registro AS FECHA_RFEPORTE,
        SEC_TO_TIME(SUM((job_movimiento_tiempos_no_laborados_horas.cantidad_minutos*60))) AS CANTIDAD
        FROM job_terceros,job_movimiento_tiempos_no_laborados_horas, job_sucursales
        WHERE job_movimiento_tiempos_no_laborados_horas.documento_identidad_empleado = job_terceros.documento_identidad
        AND job_movimiento_tiempos_no_laborados_horas.codigo_sucursal = job_sucursales.codigo
        ORDER BY job_movimiento_tiempos_no_laborados_horas.documento_identidad_empleado, job_movimiento_tiempos_no_laborados_horas.fecha_registro;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_consulta_tiempos_no_laborados_horas AS
            SELECT
                job_movimiento_tiempos_no_laborados_horas.fecha_generacion AS fecha_generacion,
                job_movimiento_tiempos_no_laborados_horas.codigo_empresa AS  codigo_empresa,
                (job_movimiento_tiempos_no_laborados_horas.documento_identidad_empleado * 1) AS documento_identidad_empleado,
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
                job_movimiento_tiempos_no_laborados_horas.fecha_ingreso AS fecha_ingreso,
                job_movimiento_tiempos_no_laborados_horas.codigo_sucursal AS codigo_sucursal,
                job_movimiento_tiempos_no_laborados_horas.fecha_ingreso_sucursal AS fecha_ingreso_sucursal,
                job_movimiento_tiempos_no_laborados_horas.consecutivo AS consecutivo,
                job_movimiento_tiempos_no_laborados_horas.codigo_planilla AS codigo_planilla,
                job_movimiento_tiempos_no_laborados_horas.fecha_registro AS fecha_registro,
                job_movimiento_tiempos_no_laborados_horas.hora_inicio AS hora_inicio,
                job_movimiento_tiempos_no_laborados_horas.hora_fin AS hora_fin,
                job_movimiento_tiempos_no_laborados_horas.codigo_transaccion_tiempo AS codigo_transaccion_tiempo,
                job_movimiento_tiempos_no_laborados_horas.codigo_transaccion_contable AS codigo_transaccion_contable,
                job_movimiento_tiempos_no_laborados_horas.codigo_contable AS codigo_contable,
                job_movimiento_tiempos_no_laborados_horas.sentido AS sentido,
                job_movimiento_tiempos_no_laborados_horas.tasa AS tasa,
                job_movimiento_tiempos_no_laborados_horas.ano_generacion AS ano_generacion,
                job_movimiento_tiempos_no_laborados_horas.mes_generacion AS mes_generacion,
                job_movimiento_tiempos_no_laborados_horas.fecha_pago_planilla AS fecha_pago_planilla,
                job_movimiento_tiempos_no_laborados_horas.codigo_empresa_auxiliar AS codigo_empresa_auxiliar,
                job_movimiento_tiempos_no_laborados_horas.codigo_anexo_contable AS codigo_anexo_contable,
                job_movimiento_tiempos_no_laborados_horas.codigo_auxiliar_contable AS codigo_auxiliar_contable,
                job_movimiento_tiempos_no_laborados_horas.periodo_pago AS periodo_pago,
                job_movimiento_tiempos_no_laborados_horas.cantidad_minutos AS cantidad_minutos,
                job_movimiento_tiempos_no_laborados_horas.valor_hora_salario AS valor_hora_salario,
                job_movimiento_tiempos_no_laborados_horas.valor_movimiento AS valor_movimiento,
                job_movimiento_tiempos_no_laborados_horas.contabilizado AS contabilizado,
                job_movimiento_tiempos_no_laborados_horas.codigo_motivo_no_laboral AS codigo_motivo_no_laboral
            FROM
                job_terceros,
                job_movimiento_tiempos_no_laborados_horas
            WHERE
                job_movimiento_tiempos_no_laborados_horas.documento_identidad_empleado = job_terceros.documento_identidad;"
    )
);

/*VISTAS FINALES 
CREATE OR REPLACE ALGORITHM = MERGE VIEW `job_buscador_movimiento_tiempos_no_laborados_dias` AS select concat(`job_movimiento_tiempos_no_laborados_dias`.`documento_identidad_empleado`,_latin1'|',`job_movimiento_tiempos_no_laborados_dias`.`fecha_inicio_tiempo`,_latin1'|',`job_movimiento_tiempos_no_laborados_dias`.`codigo_sucursal`) AS `id`,`job_sucursales`.`codigo` AS `id_sucursal`,`job_sucursales`.`nombre` AS `ALMACEN`,if(((`job_terceros`.`tipo_persona` = _latin1'1') or (`job_terceros`.`tipo_persona` = _latin1'4')),concat(`job_terceros`.`primer_nombre`,_latin1' ',if(((`job_terceros`.`segundo_nombre` is not null) and (`job_terceros`.`segundo_nombre` <> _latin1'')),concat(`job_terceros`.`segundo_nombre`,_latin1' '),_latin1''),`job_terceros`.`primer_apellido`,_latin1' ',if(((`job_terceros`.`segundo_apellido` is not null) and (`job_terceros`.`segundo_apellido` <> _latin1'')),`job_terceros`.`segundo_apellido`,_latin1'')),`job_terceros`.`razon_social`) AS `EMPLEADO`,date_format(`job_movimiento_tiempos_no_laborados_dias`.`fecha_inicio_tiempo`,_latin1'%Y-%m-%d') AS `FECHA_INCAPACIDAD_TIEMPO` from ((((`job_terceros` join `job_aspirantes`) join `job_sucursal_contrato_empleados`) join `job_movimiento_tiempos_no_laborados_dias`) join `job_sucursales`) where ((`job_sucursal_contrato_empleados`.`documento_identidad_empleado` = `job_aspirantes`.`documento_identidad`) and (`job_aspirantes`.`documento_identidad` = `job_terceros`.`documento_identidad`) and (`job_movimiento_tiempos_no_laborados_dias`.`documento_identidad_empleado` = `job_sucursal_contrato_empleados`.`documento_identidad_empleado`) and (`job_movimiento_tiempos_no_laborados_dias`.`codigo_sucursal` = `job_sucursales`.`codigo`)) group by `job_movimiento_tiempos_no_laborados_dias`.`fecha_inicio_tiempo`;
*/
?>

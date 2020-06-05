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

$borrarSiempre = false;

// Definición de tablas
$tablas["movimiento_tiempos_no_laborados_dias"] = array(
    "fecha_generacion"              => "DATETIME NOT NULL COMMENT 'Fecha en la se genero el tiempo'",
    /////LLAVE_EN_TABLA_SUCURSAL_CONTRATO/////
    "codigo_empresa"                => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa donde labora el empleado'",
    "documento_identidad_empleado"  => "VARCHAR(12) NOT NULL COMMENT 'Documento de identidad empleado en la tabla sucursal contrato empleado'",
    "fecha_ingreso"                 => "DATE NOT NULL COMMENT 'Fecha en la que inicia labores el empleado'",
    "codigo_sucursal"               => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la sucursal donde labora el empleado'",
    "fecha_ingreso_sucursal"        => "DATE NOT NULL COMMENT 'Fecha en la que inicia labores en la sucursal el empleado'",
    ///////////////////////////////
    "ano_generacion"                => "INT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Año de la generacion la planilla'",
    "mes_generacion"                => "INT(2) UNSIGNED ZEROFILL NOT NULL COMMENT 'Mes de generacion de la planilla'",
    "codigo_planilla"               => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código interno que identifica la planilla'",
    "fecha_pago_planilla"           => "DATE NOT NULL COMMENT 'Fecha rango de pago de la planilla'",
    "codigo_empresa_auxiliar"       => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa para la llave de auxiliares'",
    "codigo_anexo_contable"         => "VARCHAR(3) NOT NULL COMMENT 'CÃ³digo del anexo que permite dividir las cuentas'",
    "codigo_auxiliar_contable"      => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código donde se acumulara la información'",
    "periodo_pago"                  => "ENUM('1','2','3','4','5','6','7','8','9') NOT NULL COMMENT '1->Mensual 2->Primera quincena 3->Segunda quincena 4->Primera semana 5->Segunda semana 6->Tercer semana 7->Cuarta semana 8->Quinta semana 9->Fecha unica'",
    "contabilizado"                 => "ENUM('0','1','2') NOT NULL DEFAULT '1' COMMENT '0->No 1->Si 2->Leido para liquidar salario'",
    ///////////////////////////////
    "fecha_inicio_tiempo"           => "DATE NOT NULL COMMENT 'Fecha inicio del reporte'",
    "fecha_tiempo"                  => "DATE NOT NULL COMMENT 'Fecha que cubre la incapacidad'",
    "codigo_transaccion_tiempo"     => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id de la tabla de transacciones de tiempo'",
    "codigo_transaccion_contable"   => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id de la tabla de transacciones contables del Empleado'",
    "codigo_contable"               => "VARCHAR(15) NOT NULL COMMENT 'Codigo contable de la transaccion contable'",
    "sentido"                       => "ENUM('D','C') NOT NULL COMMENT 'D->Débito C->Crédito'",
    "dias_no_laborados"             => "SMALLINT(3) NOT NULL COMMENT 'Número de días reportados'",
    "valor_dia"                     => "DECIMAL(11,2) NOT NULL COMMENT 'Valor diario del registro'",
    "codigo_motivo_no_laboral"      => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id de la tabla de motivos de incapacidad'",
    "codigo_usuario_registra"       => "SMALLINT(4) UNSIGNED ZEROFILL  NOT NULL COMMENT 'Codigo del Usuario que genera el registro'",
    "codigo_usuario_modifica"       => "SMALLINT(4) UNSIGNED ZEROFILL  NULL COMMENT 'Codigo del ultimo Usuario que modifica el registro'"
);

// DefiniciÃ³n de llaves primarias
$llavesPrimarias["movimiento_tiempos_no_laborados_dias"] = "documento_identidad_empleado,fecha_tiempo";

// Definici{on de llaves Foraneas
$llavesForaneas["movimiento_tiempos_no_laborados_dias"] = array(
    array(
        // Nombre de la llave foranea
        "movimiento_tiempos_no_laborados_dias_sucursal_contrato",
        // Nombre del campo en la tabla actual
        "codigo_empresa,documento_identidad_empleado,fecha_ingreso,codigo_sucursal,fecha_ingreso_sucursal",
        // Nombre de la tabla relacionada
        "sucursal_contrato_empleados",
        // Nombre del campo de la tabla relacionada
        "codigo_empresa,documento_identidad_empleado,fecha_ingreso,codigo_sucursal,fecha_ingreso_sucursal"
    ),
    array(
        // Nombre de la llave foranea
        "movimiento_tiempos_no_laborados_dias_codigo_planilla",
        // Nombre del campo en la tabla actual
        "codigo_planilla",
        // Nombre de la tabla relacionada
        "planillas",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "movimiento_tiempos_no_laborados_dias_motivo",
        // Nombre del campo en la tabla actual
        "codigo_motivo_no_laboral",
        // Nombre de la tabla relacionada
        "motivos_tiempo_no_laborado",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "movimiento_tiempos_no_laborados_dias_transaccion_tiempo",
        // Nombre del campo en la tabla actual
        "codigo_transaccion_tiempo",
        // Nombre de la tabla relacionada
        "transacciones_tiempo",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "movimiento_tiempos_no_laborados_dias_transaccion_contable",
        // Nombre del campo en la tabla actual
        "codigo_transaccion_contable",
        // Nombre de la tabla relacionada
        "transacciones_contables_empleado",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "movimiento_tiempos_no_laborados_dias_plan_contable",
        // Nombre del campo en la tabla actual
        "codigo_contable",
        // Nombre de la tabla relacionada
        "plan_contable",
        // Nombre del campo de la tabla relacionada
        "codigo_contable"
    ),
   array(
        // Nombre de la llave foranea
        "movimiento_tiempos_no_laborados_dias_usuarios_registra",
        // Nombre del campo en la tabla actual
        "codigo_usuario_registra",
        // Nombre de la tabla relacionada
        "usuarios",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "movimiento_tiempos_no_laborados_dias_usuarios_modifica",
        // Nombre del campo en la tabla actual
        "codigo_usuario_modifica",
        // Nombre de la tabla relacionada
        "usuarios",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "movimiento_tiempos_no_laborados_dias_anexo",
        // Nombre del campo en la tabla actual
        "codigo_anexo_contable",
        // Nombre de la tabla relacionada
        "anexos_contables",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "movimiento_tiempos_no_laborados_dias_auxiliar_contable",
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
        "id"        => "SUBMRETL",
        "padre"     => "SUBMNOMI",
        "id_modulo" => "NOMINA",
        "visible"   => "1",
        "orden"     => "20",
        "carpeta"   => "tiempos_no_laborados_dias",
        "archivo"   => "NULL",
        "global"    => "0"
    ),
    array(
        "id"            => "GESTRENL",
        "padre"         => "SUBMRETL",
        "id_modulo"     => "NOMINA",
        "orden"         => "20",
        "visible"       => "1",
        "carpeta"       => "tiempos_no_laborados_dias",
        "global"        => "0",
        "archivo"       => "menu",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "ADICRENL",
        "padre"         => "GESTRENL",
        "id_modulo"     => "NOMINA",
        "orden"         => "10",
        "visible"       => "0",
        "carpeta"       => "tiempos_no_laborados_dias",
        "global"        => "0",
        "archivo"       => "adicionar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "CONSRENL",
        "padre"         => "GESTRENL",
        "id_modulo"     => "NOMINA",
        "orden"         => "20",
        "visible"       => "0",
        "carpeta"       => "tiempos_no_laborados_dias",
        "global"        => "0",
        "archivo"       => "consultar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "MODIRENL",
        "padre"         => "GESTRENL",
        "id_modulo"     => "NOMINA",
        "orden"         => "30",
        "visible"       => "0",
        "carpeta"       => "tiempos_no_laborados_dias",
        "global"        => "0",
        "archivo"       => "modificar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "ELIMRENL",
        "padre"         => "GESTRENL",
        "id_modulo"     => "NOMINA",
        "orden"         => "40",
        "visible"       => "0",
        "carpeta"       => "tiempos_no_laborados_dias",
        "global"        => "0",
        "archivo"       => "eliminar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "LISTRENL",
        "padre"         => "GESTRENL",
        "id_modulo"     => "NOMINA",
        "orden"         => "50",
        "visible"       => "0",
        "carpeta"       => "tiempos_no_laborados_dias",
        "global"        => "0",
        "archivo"       => "listar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_movimiento_tiempos_no_laborados_dias AS
        SELECT CONCAT(job_movimiento_tiempos_no_laborados_dias.documento_identidad_empleado,'|',job_movimiento_tiempos_no_laborados_dias.fecha_inicio_tiempo,'|',job_movimiento_tiempos_no_laborados_dias.codigo_sucursal) AS id,
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
        DATE_FORMAT(job_movimiento_tiempos_no_laborados_dias.fecha_inicio_tiempo, '%Y-%m-%d') AS FECHA_INCAPACIDAD_TIEMPO

        FROM job_terceros,job_aspirantes,job_sucursal_contrato_empleados,job_movimiento_tiempos_no_laborados_dias, job_sucursales
        WHERE job_sucursal_contrato_empleados.documento_identidad_empleado = job_aspirantes.documento_identidad
        AND job_aspirantes.documento_identidad = job_terceros.documento_identidad
        AND job_movimiento_tiempos_no_laborados_dias.documento_identidad_empleado = job_sucursal_contrato_empleados.documento_identidad_empleado
        AND job_movimiento_tiempos_no_laborados_dias.codigo_sucursal = job_sucursales.codigo
        GROUP BY job_movimiento_tiempos_no_laborados_dias.fecha_inicio_tiempo;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_movimiento_tiempos_no_laborados_dias AS
        SELECT CONCAT(job_movimiento_tiempos_no_laborados_dias.documento_identidad_empleado,'|',job_movimiento_tiempos_no_laborados_dias.fecha_inicio_tiempo,'|',job_movimiento_tiempos_no_laborados_dias.codigo_sucursal) AS id,
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

        DATE_FORMAT(job_movimiento_tiempos_no_laborados_dias.fecha_inicio_tiempo, '%Y-%m-%d') AS FECHA_INCAPACIDAD_TIEMPO

        FROM job_terceros,job_aspirantes,job_sucursal_contrato_empleados,job_movimiento_tiempos_no_laborados_dias, job_sucursales
        WHERE job_sucursal_contrato_empleados.documento_identidad_empleado = job_aspirantes.documento_identidad
        AND job_aspirantes.documento_identidad = job_terceros.documento_identidad
        AND job_movimiento_tiempos_no_laborados_dias.documento_identidad_empleado = job_sucursal_contrato_empleados.documento_identidad_empleado
        AND job_movimiento_tiempos_no_laborados_dias.codigo_sucursal = job_sucursales.codigo
        GROUP BY job_movimiento_tiempos_no_laborados_dias.fecha_inicio_tiempo;"
    ),
    array("CREATE OR REPLACE ALGORITHM = MERGE VIEW job_consulta_tiempos_no_laborados_dias AS
        SELECT
            job_movimiento_tiempos_no_laborados_dias.fecha_generacion AS fecha_generacion,
            job_movimiento_tiempos_no_laborados_dias.codigo_empresa AS codigo_empresa,
            (job_movimiento_tiempos_no_laborados_dias.documento_identidad_empleado * 1) AS documento_identidad_empleado,
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
            job_movimiento_tiempos_no_laborados_dias.fecha_ingreso AS fecha_ingreso,
            job_movimiento_tiempos_no_laborados_dias.codigo_sucursal AS codigo_sucursal,
            job_movimiento_tiempos_no_laborados_dias.fecha_ingreso_sucursal AS fecha_ingreso_sucursal,
            job_movimiento_tiempos_no_laborados_dias.ano_generacion AS ano_generacion,
            job_movimiento_tiempos_no_laborados_dias.mes_generacion AS mes_generacion,
            job_movimiento_tiempos_no_laborados_dias.codigo_planilla AS codigo_planilla,
            job_movimiento_tiempos_no_laborados_dias.fecha_pago_planilla AS fecha_pago_planilla,
            job_movimiento_tiempos_no_laborados_dias.codigo_empresa_auxiliar AS codigo_empresa_auxiliar,
            job_movimiento_tiempos_no_laborados_dias.codigo_anexo_contable AS codigo_anexo_contable,
            job_movimiento_tiempos_no_laborados_dias.codigo_auxiliar_contable AS codigo_auxiliar_contable,
            job_movimiento_tiempos_no_laborados_dias.periodo_pago AS periodo_pago,
            job_movimiento_tiempos_no_laborados_dias.contabilizado AS contabilizado,
            job_movimiento_tiempos_no_laborados_dias.fecha_inicio_tiempo AS fecha_inicio_tiempo,
            job_movimiento_tiempos_no_laborados_dias.fecha_tiempo AS fecha_tiempo,
            job_movimiento_tiempos_no_laborados_dias.codigo_transaccion_tiempo AS codigo_transaccion_tiempo,
            job_movimiento_tiempos_no_laborados_dias.codigo_transaccion_contable AS codigo_transaccion_contable,
            job_movimiento_tiempos_no_laborados_dias.codigo_contable AS codigo_contable,
            job_movimiento_tiempos_no_laborados_dias.sentido AS sentido,
            job_movimiento_tiempos_no_laborados_dias.dias_no_laborados AS dias_no_laborados,
            job_movimiento_tiempos_no_laborados_dias.valor_dia AS valor_dia,
            job_movimiento_tiempos_no_laborados_dias.codigo_motivo_no_laboral AS codigo_motivo_no_laboral,
            job_movimiento_tiempos_no_laborados_dias.codigo_usuario_registra AS codigo_usuario_registra,
            job_movimiento_tiempos_no_laborados_dias.codigo_usuario_modifica AS codigo_usuario_modifica
        FROM
            job_movimiento_tiempos_no_laborados_dias,
            job_terceros
        WHERE
            job_movimiento_tiempos_no_laborados_dias.documento_identidad_empleado = job_terceros.documento_identidad;"
    )
);
?>

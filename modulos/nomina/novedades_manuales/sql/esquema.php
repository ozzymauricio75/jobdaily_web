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
$tablas["movimiento_novedades_manuales"] = array(
    "fecha_generacion"             => "DATE NOT NULL COMMENT 'Fecha en que se genero la novedad'",
    "codigo_empresa"               => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa donde va a laborar'",
    "documento_identidad_empleado" => "VARCHAR(12) NOT NULL COMMENT 'Documento de identidad empleado en la sucursales contrato empleados'",
    "fecha_ingreso"                => "DATE NOT NULL COMMENT 'Fecha en la que ingreso el empleado a la empresa'",
    "codigo_sucursal"              => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id de la tabla de sucursales'",
    "fecha_ingreso_sucursal"       => "DATE NOT NULL COMMENT 'Fecha en la que inicia labores en la sucursal el empleado'",
    "consecutivo"                  => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo interno para la base de datos'",
    ///////////////////////////////
    "codigo_planilla"              => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id de la tabla de planillas'",
    ///////////////////////////////
    "codigo_transaccion_contable"  => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id de la tabla de transacciones contables'",
    "codigo_contable"              => "VARCHAR(15) NOT NULL COMMENT 'Codigo contable de la transaccion contable'",
    "sentido"                      => "ENUM('D','C') NOT NULL COMMENT 'D->Débito C->Crédito'",
    ///////////////////////////////
    "ano_generacion"               => "INT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Ano de la generacion la planilla'",
    "mes_generacion"               => "INT(2) UNSIGNED ZEROFILL NOT NULL COMMENT 'Mes de generacion de la planilla'",
    "fecha_pago_planilla"          => "DATE NOT NULL COMMENT 'Fecha rango de pago de la planilla'",
    "codigo_empresa_auxiliar"      => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa para la llave de auxiliares'",
    "codigo_anexo_contable"        => "VARCHAR(3) NOT NULL COMMENT 'Código del anexo que permite dividir las cuentas'",
    "codigo_auxiliar_contable"     => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código donde se acumulara la información'",
    "periodo_pago"                 => "ENUM('1','2','3','4','5','6','7','8','9') NOT NULL COMMENT '1->Mensual 2->Primera quincena 3->Segunda quincena 4->Primera semana 5->Segunda semana 6->Tercer semana 7->Cuarta semana 8->Quinta semana 9->Fecha unica'",
    ///////////////////////////////
    "contabilizado"                => "ENUM('0','1','2') NOT NULL DEFAULT '0' COMMENT 'Esta contabilizado 0->No 1->Si 2->Leido para liquidar salario'",
    "valor_movimiento"             => "DECIMAL(15,2) NOT NULL COMMENT 'Valor hora del salario'",
    "codigo_usuario_registra"      => "SMALLINT(4) UNSIGNED ZEROFILL  NOT NULL COMMENT 'Codigo del Usuario que genera el registro'",
    "codigo_usuario_modifica"      => "SMALLINT(4) UNSIGNED ZEROFILL  NOT NULL COMMENT 'Codigo del ultimo Usuario que modifica el registro'"
);

// Definicion de llaves primarias
$llavesPrimarias["movimiento_novedades_manuales"] = "ano_generacion,mes_generacion,codigo_planilla,periodo_pago,documento_identidad_empleado,consecutivo";
// Definicion de llaves Foraneas
$llavesForaneas["movimiento_novedades_manuales"] = array(
    array(
        // Nombre de la llave foranea
        "job_movimiento_novedades_manuales_contrato_empleados",
        // Nombre del campo en la tabla actual
        "codigo_empresa,documento_identidad_empleado,fecha_ingreso,codigo_sucursal,fecha_ingreso_sucursal",
        // Nombre de la tabla relacionada
        "sucursal_contrato_empleados",
        // Nombre del campo de la tabla relacionada
        "codigo_empresa,documento_identidad_empleado,fecha_ingreso,codigo_sucursal,fecha_ingreso_sucursal"
    ),
    array(
        // Nombre de la llave foranea
        "job_movimiento_novedades_manuales_planillas",
        // Nombre del campo en la tabla actual
        "codigo_planilla",
        // Nombre de la tabla relacionada
        "planillas",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "job_movimiento_novedades_manuales_transacciones_empleado",
        // Nombre del campo en la tabla actual
        "codigo_transaccion_contable",
        // Nombre de la tabla relacionada
        "transacciones_contables_empleado",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "job_movimiento_novedades_manuales_usuarios_ingresa",
        // Nombre del campo en la tabla actual
        "codigo_usuario_registra",
        // Nombre de la tabla relacionada
        "usuarios",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "job_movimiento_novedades_manuales_usuarios_modifica",
        // Nombre del campo en la tabla actual
        "codigo_usuario_modifica",
        // Nombre de la tabla relacionada
        "usuarios",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "job_movimiento_novedades_manuales_anexos_contables",
        // Nombre del campo en la tabla actual
        "codigo_anexo_contable",
        // Nombre de la tabla relacionada
        "anexos_contables",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "job_movimiento_novedades_manuales_auxiliares_contables",
        // Nombre del campo en la tabla actual
        "codigo_empresa_auxiliar,codigo_anexo_contable,codigo_auxiliar_contable",
        // Nombre de la tabla relacionada
        "auxiliares_contables",
        // Nombre del campo de la tabla relacionada
        "codigo_empresa,codigo_anexo_contable,codigo"
    ),
    array(
        // Nombre de la llave foranea
        "movimiento_novedades_manuales_auxiliares_plan_contable",
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
        "id"            => "GESTNOMA",
        "padre"         => "SUBMPRPL",
        "id_modulo"     => "NOMINA",
        "orden"         => "15",
        "visible"       => "1",
        "carpeta"       => "novedades_manuales",
        "global"        => "0",
        "archivo"       => "menu",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "ADICNOMA",
        "padre"         => "GESTNOMA",
        "id_modulo"     => "NOMINA",
        "orden"         => "10",
        "visible"       => "0",
        "carpeta"       => "novedades_manuales",
        "global"        => "0",
        "archivo"       => "adicionar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "CONSNOMA",
        "padre"         => "GESTNOMA",
        "id_modulo"     => "NOMINA",
        "orden"         => "20",
        "visible"       => "0",
        "carpeta"       => "novedades_manuales",
        "global"        => "0",
        "archivo"       => "consultar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "MODINOMA",
        "padre"         => "GESTNOMA",
        "id_modulo"     => "NOMINA",
        "orden"         => "30",
        "visible"       => "0",
        "carpeta"       => "novedades_manuales",
        "global"        => "0",
        "archivo"       => "modificar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "ELIMNOMA",
        "padre"         => "GESTNOMA",
        "id_modulo"     => "NOMINA",
        "orden"         => "40",
        "visible"       => "0",
        "carpeta"       => "novedades_manuales",
        "global"        => "0",
        "archivo"       => "eliminar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "LISTNOMA",
        "padre"         => "GESTNOMA",
        "id_modulo"     => "NOMINA",
        "orden"         => "40",
        "visible"       => "0",
        "carpeta"       => "novedades_manuales",
        "global"        => "0",
        "archivo"       => "listar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_movimiento_novedades_manuales AS
        SELECT CONCAT(job_movimiento_novedades_manuales.ano_generacion,'|',job_movimiento_novedades_manuales.mes_generacion,'|',job_movimiento_novedades_manuales.codigo_planilla,'|',job_movimiento_novedades_manuales.periodo_pago,'|',job_movimiento_novedades_manuales.documento_identidad_empleado,'|',job_movimiento_novedades_manuales.consecutivo,'|',job_movimiento_novedades_manuales.codigo_sucursal,'|',job_movimiento_novedades_manuales.fecha_pago_planilla) AS id,
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
        job_planillas.descripcion AS PLANILLA,
        job_movimiento_novedades_manuales.fecha_pago_planilla AS FECHA_PAGO

        FROM job_terceros,job_movimiento_novedades_manuales, job_sucursales,job_planillas
        WHERE job_movimiento_novedades_manuales.documento_identidad_empleado = job_terceros.documento_identidad
        AND job_movimiento_novedades_manuales.codigo_sucursal = job_sucursales.codigo
        AND job_movimiento_novedades_manuales.codigo_planilla = job_planillas.codigo
        GROUP BY job_movimiento_novedades_manuales.documento_identidad_empleado,job_movimiento_novedades_manuales.ano_generacion,job_movimiento_novedades_manuales.mes_generacion,job_movimiento_novedades_manuales.codigo_planilla,job_movimiento_novedades_manuales.periodo_pago,job_movimiento_novedades_manuales.codigo_sucursal;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_movimiento_novedades_manuales AS
        SELECT CONCAT(job_movimiento_novedades_manuales.ano_generacion,'|',job_movimiento_novedades_manuales.mes_generacion,'|',job_movimiento_novedades_manuales.codigo_planilla,'|',job_movimiento_novedades_manuales.periodo_pago,'|',job_movimiento_novedades_manuales.documento_identidad_empleado,'|',job_movimiento_novedades_manuales.consecutivo,'|',job_movimiento_novedades_manuales.codigo_sucursal,'|',job_movimiento_novedades_manuales.fecha_pago_planilla) AS id,
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
        job_transacciones_contables_empleado.nombre AS TRANSACCION_CONTABLE,
        job_movimiento_novedades_manuales.valor_movimiento AS VALOR

        FROM job_terceros,job_movimiento_novedades_manuales, job_sucursales,job_transacciones_contables_empleado
        WHERE job_movimiento_novedades_manuales.documento_identidad_empleado = job_terceros.documento_identidad
        AND job_movimiento_novedades_manuales.codigo_sucursal = job_sucursales.codigo
        AND job_movimiento_novedades_manuales.codigo_transaccion_contable = job_transacciones_contables_empleado.codigo
        GROUP BY job_movimiento_novedades_manuales.documento_identidad_empleado, job_movimiento_novedades_manuales.fecha_generacion;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_consulta_movimientos_novedades_manuales AS
            SELECT
                job_movimiento_novedades_manuales.fecha_generacion AS fecha_generacion,
                job_movimiento_novedades_manuales.codigo_empresa AS codigo_empresa,
                job_movimiento_novedades_manuales.documento_identidad_empleado AS documento_identidad_empleado,
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
                job_movimiento_novedades_manuales.fecha_ingreso AS fecha_ingreso,
                job_movimiento_novedades_manuales.codigo_sucursal AS codigo_sucursal,
                job_movimiento_novedades_manuales.fecha_ingreso_sucursal AS fecha_ingreso_sucursal,
                job_movimiento_novedades_manuales.consecutivo AS consecutivo,
                job_movimiento_novedades_manuales.codigo_planilla AS codigo_planilla,
                job_movimiento_novedades_manuales.codigo_transaccion_contable AS codigo_transaccion_contable,
                job_movimiento_novedades_manuales.codigo_contable AS codigo_contable,
                job_movimiento_novedades_manuales.sentido AS sentido,
                job_movimiento_novedades_manuales.ano_generacion AS ano_generacion,
                job_movimiento_novedades_manuales.mes_generacion AS mes_generacion,
                job_movimiento_novedades_manuales.fecha_pago_planilla AS fecha_pago_planilla,
                job_movimiento_novedades_manuales.codigo_empresa_auxiliar AS codigo_empresa_auxiliar,
                job_movimiento_novedades_manuales.codigo_anexo_contable AS codigo_anexo_contable,
                job_movimiento_novedades_manuales.codigo_auxiliar_contable AS codigo_auxiliar_contable,
                job_movimiento_novedades_manuales.periodo_pago AS periodo_pago,
                job_movimiento_novedades_manuales.contabilizado AS contabilizado,
                job_movimiento_novedades_manuales.valor_movimiento AS valor_movimiento
            FROM
                job_movimiento_novedades_manuales,
                job_terceros
            WHERE
                job_movimiento_novedades_manuales.documento_identidad_empleado = job_terceros.documento_identidad;"
    )
);
?>

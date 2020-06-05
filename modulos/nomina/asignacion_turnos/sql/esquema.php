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

// Definición de tablas
$tablas["asignacion_turnos"] = array(
    "codigo_empresa"               => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa donde va a laborar'",
    "documento_identidad_empleado" => "VARCHAR(12) NOT NULL COMMENT 'Documento de identidad empleado en la sucursales contrato empleados'",
    "fecha_ingreso"                => "DATE NOT NULL COMMENT 'Fecha en la que ingreso el empleado a la empresa'",
    "codigo_sucursal"              => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id de la tabla de sucursales'",
    "fecha_ingreso_sucursal"       => "DATE NOT NULL COMMENT 'Fecha en la que inicia labores en la sucursal el empleado'",
    ///////////////////////////////
    "consecutivo"                  => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo interno para la base de datos'",
    "codigo_turno"                 => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id de la tabla de turnos laborales'",
    "fecha_inicial"                => "DATE NOT NULL COMMENT 'Fecha en que inicia el turno'",
    "fecha_final"                  => "DATE NOT NULL COMMENT 'Fecha en que finaliza el turno'",
    "dominicales"                  => "ENUM('0','1') NULL DEFAULT '0' COMMENT 'Trabaja domingos 0->No 1->Si'",
    "festivos"                     => "ENUM('0','1') NULL DEFAULT '0' COMMENT 'Trabaja festivos 0->No 1->Si'",
    "codigo_usuario_registra"      => "SMALLINT(4) UNSIGNED ZEROFILL  NOT NULL COMMENT 'Codigo del Usuario que genera el registro'",
    "codigo_usuario_modifica"      => "SMALLINT(4) UNSIGNED ZEROFILL  NULL COMMENT 'Codigo del ultimo Usuario que modifica el registro'"
);

// Definición de llaves primarias
$llavesPrimarias["asignacion_turnos"] = "codigo_empresa,documento_identidad_empleado,fecha_ingreso,codigo_sucursal,fecha_ingreso_sucursal,consecutivo";

// Definici{on de llaves Foraneas
$llavesForaneas["asignacion_turnos"] = array(
    array(
        // Nombre de la llave foranea
        "turno_laboral",
        // Nombre del campo en la tabla actual
        "codigo_turno",
        // Nombre de la tabla relacionada
        "turnos_laborales",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "turnos_ingreso_empleado",
        // Nombre del campo en la tabla actual
        "codigo_empresa,documento_identidad_empleado,fecha_ingreso,codigo_sucursal,fecha_ingreso_sucursal",
        // Nombre de la tabla relacionada
        "sucursal_contrato_empleados",
        // Nombre del campo de la tabla relacionada
        "codigo_empresa,documento_identidad_empleado,fecha_ingreso,codigo_sucursal,fecha_ingreso_sucursal"
    ),
    array(
        // Nombre de la llave foranea
        "asignacion_turnos_usuarios_registra",
        // Nombre del campo en la tabla actual
        "codigo_usuario_registra",
        // Nombre de la tabla relacionada
        "usuarios",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "asignacion_turnos_usuarios_modifica",
        // Nombre del campo en la tabla actual
        "codigo_usuario_modifica",
        // Nombre de la tabla relacionada
        "usuarios",
        // Nombre del campo de la tabla relacionada
        "codigo"
    )
);

// Insercion de registros
$registros["componentes"] = array(
    array(
        "id"            => "GESTASTU",
        "padre"         => "SUBMNOMI",
        "id_modulo"     => "NOMINA",
        "orden"         => "10",
        "visible"       => "1",
        "carpeta"       => "asignacion_turnos",
        "global"        => "0",
        "archivo"       => "menu",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "ADICASTU",
        "padre"         => "GESTASTU",
        "id_modulo"     => "NOMINA",
        "orden"         => "10",
        "visible"       => "0",
        "carpeta"       => "asignacion_turnos",
        "global"        => "0",
        "archivo"       => "adicionar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "CONSASTU",
        "padre"         => "GESTASTU",
        "id_modulo"     => "NOMINA",
        "orden"         => "20",
        "visible"       => "0",
        "carpeta"       => "asignacion_turnos",
        "global"        => "0",
        "archivo"       => "consultar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "MODIASTU",
        "padre"         => "GESTASTU",
        "id_modulo"     => "NOMINA",
        "orden"         => "30",
        "visible"       => "0",
        "carpeta"       => "asignacion_turnos",
        "global"        => "0",
        "archivo"       => "modificar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "ELIMASTU",
        "padre"         => "GESTASTU",
        "id_modulo"     => "NOMINA",
        "orden"         => "40",
        "visible"       => "0",
        "carpeta"       => "asignacion_turnos",
        "global"        => "0",
        "archivo"       => "eliminar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "LISTASTU",
        "padre"         => "GESTASTU",
        "id_modulo"     => "NOMINA",
        "orden"         => "50",
        "visible"       => "0",
        "carpeta"       => "asignacion_turnos",
        "global"        => "0",
        "archivo"       => "listar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_asignacion_turnos AS
        SELECT job_asignacion_turnos.consecutivo AS id,
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
        job_turnos_laborales.descripcion AS DESCRIPCION_TURNO,
        job_asignacion_turnos.fecha_inicial AS FECHA_INICIAL,
        job_asignacion_turnos.fecha_final AS FECHA_FINAL
        FROM job_terceros,job_aspirantes,job_sucursal_contrato_empleados,job_asignacion_turnos, job_sucursales,job_turnos_laborales
        WHERE job_sucursal_contrato_empleados.documento_identidad_empleado = job_aspirantes.documento_identidad
        AND job_aspirantes.documento_identidad = job_terceros.documento_identidad
        AND job_asignacion_turnos.documento_identidad_empleado = job_sucursal_contrato_empleados.documento_identidad_empleado
        AND job_asignacion_turnos.codigo_sucursal = job_sucursales.codigo
        AND job_asignacion_turnos.codigo_turno=job_turnos_laborales.codigo;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_asignacion_turnos AS
        SELECT job_asignacion_turnos.consecutivo AS id,
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
        job_turnos_laborales.descripcion AS DESCRIPCION_TURNO,
        job_asignacion_turnos.fecha_inicial AS FECHA_INICIAL,
        job_asignacion_turnos.fecha_final AS FECHA_FINAL
        FROM job_terceros,job_aspirantes,job_sucursal_contrato_empleados,job_asignacion_turnos, job_sucursales,job_turnos_laborales
        WHERE job_sucursal_contrato_empleados.documento_identidad_empleado = job_aspirantes.documento_identidad
        AND job_aspirantes.documento_identidad = job_terceros.documento_identidad
        AND job_asignacion_turnos.documento_identidad_empleado = job_sucursal_contrato_empleados.documento_identidad_empleado
        AND job_asignacion_turnos.codigo_sucursal = job_sucursales.codigo
        AND job_asignacion_turnos.codigo_turno=job_turnos_laborales.codigo;"
    )
);

/***
    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_asignacion_turnos AS
    SELECT job_asignacion_turnos.consecutivo AS id,
    job_sucursales.nombre AS SUCURSAL,
    CONCAT(
        IF(job_terceros.primer_nombre IS NOT NULL,CONCAT(job_terceros.primer_nombre,' '),''),
        IF(job_terceros.segundo_nombre IS NOT NULL,CONCAT(job_terceros.segundo_nombre,' '),''),
        IF(job_terceros.primer_apellido IS NOT NULL,CONCAT(job_terceros.primer_apellido,' '),''),
        IF(job_terceros.segundo_apellido IS NOT NULL,CONCAT(job_terceros.segundo_apellido,' '),'')
    ) AS EMPLEADO,
    job_turnos_laborales.descripcion AS DESCRIPCION_TURNO,
    job_asignacion_turnos.fecha_inicial AS FECHA_INICIAL,
    job_asignacion_turnos.fecha_final AS FECHA_FINAL
    FROM job_terceros,job_aspirantes,job_sucursal_contrato_empleados,job_asignacion_turnos, job_sucursales,job_turnos_laborales
    WHERE job_sucursal_contrato_empleados.documento_identidad_empleado = job_aspirantes.documento_identidad
    AND job_aspirantes.documento_identidad = job_terceros.documento_identidad
    AND job_asignacion_turnos.documento_identidad_empleado = job_sucursal_contrato_empleados.documento_identidad_empleado
    AND job_asignacion_turnos.codigo_sucursal = job_sucursales.codigo
    AND job_asignacion_turnos.codigo_turno=job_turnos_laborales.codigo;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_asignacion_turnos AS
    SELECT job_asignacion_turnos.consecutivo AS id,
    job_sucursales.nombre AS SUCURSAL,
    CONCAT(
        IF(job_terceros.primer_nombre IS NOT NULL,CONCAT(job_terceros.primer_nombre,' '),''),
        IF(job_terceros.segundo_nombre IS NOT NULL,CONCAT(job_terceros.segundo_nombre,' '),''),
        IF(job_terceros.primer_apellido IS NOT NULL,CONCAT(job_terceros.primer_apellido,' '),''),
        IF(job_terceros.segundo_apellido IS NOT NULL,CONCAT(job_terceros.segundo_apellido,' '),'')
    ) AS EMPLEADO,
    job_turnos_laborales.descripcion AS DESCRIPCION_TURNO,
    job_asignacion_turnos.fecha_inicial AS FECHA_INICIAL,
    job_asignacion_turnos.fecha_final AS FECHA_FINAL
    FROM job_terceros,job_aspirantes,job_sucursal_contrato_empleados,job_asignacion_turnos, job_sucursales,job_turnos_laborales
    WHERE job_sucursal_contrato_empleados.documento_identidad_empleado = job_aspirantes.documento_identidad
    AND job_aspirantes.documento_identidad = job_terceros.documento_identidad
    AND job_asignacion_turnos.documento_identidad_empleado = job_sucursal_contrato_empleados.documento_identidad_empleado
    AND job_asignacion_turnos.codigo_sucursal = job_sucursales.codigo
    AND job_asignacion_turnos.codigo_turno=job_turnos_laborales.codigo;
***/
?>

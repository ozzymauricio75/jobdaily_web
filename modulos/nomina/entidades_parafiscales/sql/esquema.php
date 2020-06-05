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
$tablas["entidades_parafiscales"] = array(
    "codigo"                      => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo interno que identifica la entidad en la base de datos'",
    "codigo_ruaf"                 => "VARCHAR(50) NULL COMMENT 'Registro unico de afialiado(RUAF) asignado por el ministerio de la proteccion social'",
    /*tabla terceros*/
    "documento_identidad_tercero" => "VARCHAR(12) NOT NULL COMMENT 'Número del documento de identidad del tercero'",
    /*****************/
    "nombre"                      => "VARCHAR(100) NOT NULL COMMENT 'Nombre de la entidad'",
    "salud"                       => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT 'Aporta 0->No 1->Si'",
    "pension"                     => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT 'Aporta 0->No 1->Si'",
    "cesantias"                   => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT 'Aporta 0->No 1->Si'",
    "caja"                        => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT 'Aporta 0->No 1->Si'",
    "sena"                        => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT 'Aporta 0->No 1->Si'",
    "icbf"                        => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT 'Aporta  0->No 1->Si'",
    "riesgos_profesionales"       => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT 'Riesgos profesionales  0->No 1->Si'",
    "asistencia_social"           => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT 'Asistencia social  0->No 1->Si'"
);


// Tabla de informacion personal

// Definición de llaves primarias
$llavesPrimarias["entidades_parafiscales"] = "codigo";

$llavesUnicas["entidades_parafiscales"] = array(
    "nombre"
);

// Definición de llaves foráneas
$llavesForaneas["entidades_parafiscales"] = array(
    array(
        // Nombre de la llave
        "entidad_parafiscal_tercero",
        // Nombre del campo clave de la tabla local
        "documento_identidad_tercero",
        // Nombre de la tabla relacionada
        "terceros",
        // Nombre del campo clave en la tabla relacionada
        "documento_identidad"
    )
 );

$registros["entidades_parafiscales"] = array(
    array(
        "codigo"                      => "0",
        "codigo_ruaf"                 => "",
        "documento_identidad_tercero" => "0",
        "nombre"                      => "",
        "salud"                       => "1",
        "pension"                     => "1",
        "cesantias"                   => "1"
    )
);

// Inserción de datos iniciales***/
$registros["componentes"] = array(
    array(
        "id"                => "GESTENPF",
        "padre"             => "SUBMDCRH",
        "id_modulo"         => "NOMINA",
        "visible"           => "1",
        "orden"             => "110",
        "carpeta"           => "entidades_parafiscales",
        "archivo"           => "menu",
        "requiere_item"     => "1",
        "tabla_principal"   => "entidades_parafiscales",
        "tipo_enlace"       => "1"
    ),
    array(
        "id"                => "ADICENPF",
        "padre"             => "GESTENPF",
        "id_modulo"         => "NOMINA",
        "visible"           => "0",
        "orden"             => "10",
        "carpeta"           => "entidades_parafiscales",
        "archivo"           => "adicionar",
        "requiere_item"     => "0",
        "tabla_principal"   => "entidades_parafiscales",
        "tipo_enlace"       => "1"
    ),
    array(
        "id"                => "CONSENPF",
        "padre"             => "GESTENPF",
        "id_modulo"         => "NOMINA",
        "visible"           => "0",
        "orden"             => "20",
        "carpeta"           => "entidades_parafiscales",
        "archivo"           => "consultar",
        "requiere_item"     => "1",
        "tabla_principal"   => "entidades_parafiscales",
        "tipo_enlace"       => "1"
    ),
    array(
        "id"                => "MODIENPF",
        "padre"             => "GESTENPF",
        "id_modulo"         => "NOMINA",
        "visible"           => "0",
        "orden"             => "30",
        "carpeta"           => "entidades_parafiscales",
        "archivo"           => "modificar",
        "requiere_item"     => "1",
        "tabla_principal"   => "entidades_parafiscales",
        "tipo_enlace"       => "1"
    ),
    array(
        "id"                => "ELIMENPF",
        "padre"             => "GESTENPF",
        "id_modulo"         => "NOMINA",
        "visible"           => "0",
        "orden"             => "40",
        "carpeta"           => "entidades_parafiscales",
        "archivo"           => "eliminar",
        "requiere_item"     => "1",
        "tabla_principal"   => "entidades_parafiscales",
        "tipo_enlace"       => "1"
    ),
    array(
        "id"                => "LISTENPF",
        "padre"             => "GESTENPF",
        "id_modulo"         => "NOMINA",
        "visible"           => "0",
        "orden"             => "50",
        "carpeta"           => "entidades_parafiscales",
        "archivo"           => "listar",
        "requiere_item"     => "1",
        "tabla_principal"   => "entidades_parafiscales",
        "tipo_enlace"       => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_entidades_parafiscales AS
        SELECT job_entidades_parafiscales.codigo AS id,
            job_entidades_parafiscales.codigo AS CODIGO,
            job_entidades_parafiscales.codigo_ruaf AS CODIGO_RUAF,
            job_entidades_parafiscales.nombre AS NOMBRE,
            IF(job_terceros.tipo_persona = '1' OR job_terceros.tipo_persona = '4',
                CONCAT(
                    job_terceros.primer_nombre,' ',
                    IF (job_terceros.segundo_nombre IS NOT NULL AND job_terceros.segundo_nombre != '',CONCAT(job_terceros.segundo_nombre,' '),''),
                    job_terceros.primer_apellido,' ',
                    IF (job_terceros.segundo_apellido IS NOT NULL AND job_terceros.segundo_apellido != '',job_terceros.segundo_apellido,'')
                ),
                job_terceros.razon_social
            ) AS NOMBRE_TERCERO
        FROM
            job_entidades_parafiscales, job_terceros
        WHERE
            job_entidades_parafiscales.documento_identidad_tercero = job_terceros.documento_identidad
            AND job_entidades_parafiscales.codigo !=0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_entidades_parafiscales AS
        SELECT job_entidades_parafiscales.codigo AS id,
            job_entidades_parafiscales.codigo AS codigo,
            job_entidades_parafiscales.nombre AS nombre,
            job_entidades_parafiscales.codigo_ruaf AS codigo_ruaf,
            job_terceros.documento_identidad AS documento_tercero,
            IF(job_terceros.tipo_persona = '1' OR job_terceros.tipo_persona = '4',
                CONCAT(
                    job_terceros.primer_nombre,' ',
                    IF (job_terceros.segundo_nombre IS NOT NULL AND job_terceros.segundo_nombre != '',CONCAT(job_terceros.segundo_nombre,' '),''),
                    job_terceros.primer_apellido,' ',
                    IF (job_terceros.segundo_apellido IS NOT NULL AND job_terceros.segundo_apellido != '',job_terceros.segundo_apellido,'')
                ),
                job_terceros.razon_social
            ) AS NOMBRE_TERCERO
        FROM
            job_entidades_parafiscales, job_terceros
        WHERE
            job_entidades_parafiscales.documento_identidad_tercero = job_terceros.documento_identidad
            AND job_entidades_parafiscales.codigo !=0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_entidades_parafiscales_salud AS
        SELECT job_entidades_parafiscales.codigo AS id,
            CONCAT(
                job_entidades_parafiscales.codigo,
                '-',
                job_entidades_parafiscales.nombre,
                '|',
                job_entidades_parafiscales.codigo
            ) AS descripcion

        FROM
            job_entidades_parafiscales
        WHERE
            job_entidades_parafiscales.salud = '1'
            AND job_entidades_parafiscales.codigo !=0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_entidades_parafiscales_cesantias AS
        SELECT job_entidades_parafiscales.codigo AS id,
            CONCAT(
                job_entidades_parafiscales.codigo,
                '-',
                job_entidades_parafiscales.nombre,
                '|',
                job_entidades_parafiscales.codigo
            ) AS descripcion
        FROM
            job_entidades_parafiscales
        WHERE
            job_entidades_parafiscales.cesantias = '1'
            AND job_entidades_parafiscales.codigo !=0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_entidades_parafiscales_pension AS
        SELECT job_entidades_parafiscales.codigo AS id,
            CONCAT(
                job_entidades_parafiscales.codigo,
                '-',
                job_entidades_parafiscales.nombre,
                '|',
                job_entidades_parafiscales.codigo
            ) AS descripcion
        FROM
            job_entidades_parafiscales
        WHERE
            job_entidades_parafiscales.pension = '1'
            AND job_entidades_parafiscales.codigo !=0;"
    )
);
/***
    DROP TABLE IF EXISTS job_menu_entidades_parafiscales;
    DROP TABLE IF EXISTS job_buscador_entidades_parafiscales;
    DROP TABLE IF EXISTS job_seleccion_entidades_parafiscales_salud;
    DROP TABLE IF EXISTS job_seleccion_entidades_parafiscales_cesantias;
    DROP TABLE IF EXISTS job_seleccion_entidades_parafiscales_pension;


    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_entidades_parafiscales AS
    SELECT job_entidades_parafiscales.codigo AS id,
        job_entidades_parafiscales.codigo AS CODIGO,
        job_entidades_parafiscales.nombre AS NOMBRE,
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
        ) AS NOMBRE_TERCERO
    FROM
        job_entidades_parafiscales, job_terceros
    WHERE
        job_entidades_parafiscales.documento_identidad_tercero = job_terceros.documento_identidad
        AND job_entidades_parafiscales.codigo !=0;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_entidades_parafiscales AS
    SELECT job_entidades_parafiscales.codigo AS id,
        job_terceros.documento_identidad AS DOCUMENTO_TERCERO,
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
        ) AS NOMBRE_TERCERO
    FROM
        job_entidades_parafiscales, job_terceros
    WHERE
        job_entidades_parafiscales.documento_identidad_tercero = job_terceros.documento_identidad
        AND job_entidades_parafiscales.codigo !=0;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_entidades_parafiscales_salud AS
    SELECT job_entidades_parafiscales.codigo AS id,
        CONCAT(
            job_entidades_parafiscales.codigo,
            "-",
            job_entidades_parafiscales.nombre,
            "|",
            job_entidades_parafiscales.codigo
        ) AS descripcion
    FROM
        job_entidades_parafiscales
    WHERE
        job_entidades_parafiscales.salud = '1'
        AND job_entidades_parafiscales.codigo !=0;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_entidades_parafiscales_cesantias AS
    SELECT job_entidades_parafiscales.codigo AS id,
        CONCAT(
            job_entidades_parafiscales.codigo,
            "-",
            job_entidades_parafiscales.nombre,
            "|",
            job_entidades_parafiscales.codigo
        ) AS descripcion
    FROM
        job_entidades_parafiscales
    WHERE
        job_entidades_parafiscales.cesantias = '1'
        AND job_entidades_parafiscales.codigo !=0;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_entidades_parafiscales_pension AS
    SELECT job_entidades_parafiscales.codigo AS id,
        CONCAT(
            job_entidades_parafiscales.codigo,
            "-",
            job_entidades_parafiscales.nombre,
            "|",
            job_entidades_parafiscales.codigo
        ) AS descripcion
    FROM
        job_entidades_parafiscales
    WHERE
        job_entidades_parafiscales.pension = '1'
        AND job_entidades_parafiscales.codigo !=0;

***/
?>

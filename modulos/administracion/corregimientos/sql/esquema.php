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
* de APTITUD PARA UN PROPÓSITO DETERMINADO. Consulte los detalles de
* la Licencia Pública General GNU para obtener una información más
* detallada.
*
* Debería haber recibido una copia de la Licencia Pública General GNU
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
**/

// Eliminar la tabla y crearla de nuevo cada vez que se ejecute el script de creación
$borrarSiempre = false;

// Definición de tablas
$tablas["localidades"]   = array(
    "codigo_iso"               => "VARCHAR(2) NOT NULL COMMENT 'Llave principal de la tabla paises'",
    "codigo_dane_departamento" => "VARCHAR(2) NOT NULL COMMENT 'Código DANE'",
    "codigo_dane_municipio"    => "VARCHAR(3) NOT NULL COMMENT 'Código DANE'",
    "tipo"                     => "ENUM('B','C') NOT NULL DEFAULT 'B' COMMENT 'Tipo de localidad: B=Barrio, C=Corregimiento'",
    "codigo_dane_localidad"    => "VARCHAR(3) COMMENT 'Código DANE (sólo para corregimientos)'",
    "codigo_interno"           => "INT(8) UNSIGNED ZEROFILL COMMENT 'Código para uso interno de la empresa (opcional)'",
    "nombre"                   => "VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'Nombre completo'",
    "comuna"                   => "TINYINT(2) NOT NULL DEFAULT '0' COMMENT 'Comuna a la que pertenece (sólo para barrios)'",
    "estrato"                  => "TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'Estrato al que pertenece (sólo para barrios)'"
);;

// Definición de llaves primarias
$llavesPrimarias["localidades"]   = "codigo_iso,codigo_dane_departamento,codigo_dane_municipio,tipo,codigo_dane_localidad";

// Definición de llaves foráneas
$llavesForaneas["localidades"]   = array(
    array(
        // Nombre de la llave
        "localidad_municipio",
        // Nombre del campo clave de la tabla local
        "codigo_iso,codigo_dane_departamento,codigo_dane_municipio",
        // Nombre de la tabla relacionada
        "municipios",
        // Nombre del campo clave en la tabla relacionada
        "codigo_iso,codigo_dane_departamento,codigo_dane_municipio"
    )
);

// Inserción de datos iniciales
$registros["localidades"] = array(
    array(
        "codigo_iso"               => "",
        "codigo_dane_departamento" => "",
        "codigo_dane_municipio"    => "",
        "tipo"                     => "C",
        "codigo_dane_localidad"  => "",
        "codigo_interno"           => "0",
        "nombre"                   => "",
        "comuna"                   => "0",
        "estrato"                  => "0"
    )
);

$registros["componentes"] = array(
    array(
        "id"            => "GESTCORR",
        "padre"         => "SUBMUBIG",
        "id_modulo"     => "ADMINISTRACION",
        "visible"       => "1",
        "orden"         => "400",
        "carpeta"       => "corregimientos",
        "archivo"       => "menu",
        "global"        => "0",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "ADICCORR",
        "padre"         => "GESTCORR",
        "id_modulo"     => "ADMINISTRACION",
        "visible"       => "0",
        "orden"         => "10",
        "carpeta"       => "corregimientos",
        "archivo"       => "adicionar",
        "global"        => "0",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "CONSCORR",
        "padre"         => "GESTCORR",
        "id_modulo"     => "ADMINISTRACION",
        "visible"       => "0",
        "orden"         => "20",
        "carpeta"       => "corregimientos",
        "archivo"       => "consultar",
        "global"        => "0",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "MODICORR",
        "padre"         => "GESTCORR",
        "id_modulo"     => "ADMINISTRACION",
        "visible"       => "0",
        "orden"         => "30",
        "carpeta"       => "corregimientos",
        "archivo"       => "modificar",
        "global"        => "0",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "ELIMCORR",
        "padre"         => "GESTCORR",
        "id_modulo"     => "ADMINISTRACION",
        "visible"       => "0",
        "orden"         => "40",
        "carpeta"       => "corregimientos",
        "archivo"       => "eliminar",
        "global"        => "0",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "LISTCORR",
        "padre"         => "GESTCORR",
        "id_modulo"     => "ADMINISTRACION",
        "visible"       => "0",
        "orden"         => "40",
        "carpeta"       => "corregimientos",
        "archivo"       => "listar",
        "global"        => "0",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_corregimientos AS
        SELECT
            CONCAT(job_localidades.codigo_iso, \"|\",job_localidades.codigo_dane_departamento,\"|\",job_localidades.codigo_dane_municipio,\"|\",job_localidades.tipo,\"|\",job_localidades.codigo_dane_localidad) AS id,
            job_localidades.nombre AS NOMBRE,
            job_municipios.nombre AS MUNICIPIO,
            job_departamentos.nombre AS DEPARTAMENTO,
            job_paises.nombre AS PAIS
        FROM
            job_localidades,
            job_municipios,
            job_departamentos,
            job_paises
        WHERE
            job_localidades.codigo_iso = job_municipios.codigo_iso AND
            job_localidades.codigo_dane_departamento = job_municipios.codigo_dane_departamento AND
            job_localidades.codigo_dane_municipio = job_municipios.codigo_dane_municipio AND
            job_localidades.codigo_iso = job_departamentos.codigo_iso AND
            job_localidades.codigo_dane_departamento = job_departamentos.codigo_dane_departamento AND
            job_localidades.codigo_iso = job_paises.codigo_iso AND
            job_localidades.tipo = 'C' AND job_localidades.codigo_dane_localidad != 0 AND job_localidades.codigo_dane_localidad != \"\"
            ORDER BY NOMBRE ASC;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_corregimientos AS
        SELECT
            CONCAT(job_localidades.codigo_iso, \"|\",job_localidades.codigo_dane_departamento,\"|\",job_localidades.codigo_dane_municipio,\"|\",job_localidades.tipo,\"|\",job_localidades.codigo_dane_localidad) AS id,
            job_localidades.nombre AS nombre,
            job_localidades.codigo_dane_localidad AS codigo_localidad,
            job_localidades.codigo_interno AS codigo_interno,
            job_localidades.estrato AS estrato,
            job_localidades.comuna AS comuna,
            job_municipios.nombre AS municipio,
            job_departamentos.nombre AS departamento,
            job_paises.nombre AS pais
        FROM
            job_localidades,
            job_municipios,
            job_departamentos,
            job_paises
        WHERE
            job_localidades.codigo_iso = job_municipios.codigo_iso AND
            job_localidades.codigo_dane_departamento = job_municipios.codigo_dane_departamento AND
            job_localidades.codigo_dane_municipio = job_municipios.codigo_dane_municipio AND
            job_localidades.codigo_iso = job_departamentos.codigo_iso AND
            job_localidades.codigo_dane_departamento = job_departamentos.codigo_dane_departamento AND
            job_localidades.codigo_iso = job_paises.codigo_iso AND
            job_localidades.tipo = 'C'
            ORDER BY NOMBRE ASC;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_corregimientos AS
        SELECT
            CONCAT(job_localidades.codigo_iso, \"|\",job_localidades.codigo_dane_departamento,\"|\",job_localidades.codigo_dane_municipio,\"|\",tipo,\"|\",job_localidades.codigo_dane_localidad) AS id,
            CONCAT(
                CONCAT(job_localidades.codigo_dane_departamento, job_localidades.codigo_dane_municipio,job_localidades.codigo_dane_localidad)
                                ,'-',job_localidades.nombre
                                ,'-',job_municipios.nombre,
                ', ', job_departamentos.nombre,
                ', ',job_paises.nombre,
                '|', CONCAT(job_departamentos.codigo_iso,\",\",job_municipios.codigo_dane_departamento,\",\",job_municipios.codigo_dane_municipio,job_localidades.codigo_dane_localidad)
            ) AS nombre ,
            job_localidades.codigo_dane_localidad AS codigo
        FROM
            job_localidades,
            job_municipios,
            job_departamentos,
            job_paises
        WHERE
            job_localidades.codigo_iso = job_municipios.codigo_iso AND
            job_localidades.codigo_dane_departamento = job_municipios.codigo_dane_departamento AND
            job_localidades.codigo_dane_municipio = job_municipios.codigo_dane_municipio AND
            job_localidades.codigo_iso = job_departamentos.codigo_iso AND
            job_localidades.codigo_dane_departamento = job_departamentos.codigo_dane_departamento AND
            job_localidades.codigo_iso = job_paises.codigo_iso AND
            job_localidades.tipo = 'C'
            ORDER BY NOMBRE ASC;"
    )
)

/** Sentencia para la creación de la vista requerida

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_corregimientos AS
    SELECT
        CONCAT(job_localidades.codigo_iso, "|",job_localidades.codigo_dane_departamento,"|",job_localidades.codigo_dane_municipio,"|",job_localidades.tipo,"|",job_localidades.codigo_dane_localidad) AS id,
        job_localidades.nombre AS NOMBRE,
        job_municipios.nombre AS MUNICIPIO,
        job_departamentos.nombre AS DEPARTAMENTO,
        job_paises.nombre AS PAIS
    FROM
        job_localidades,
        job_municipios,
        job_departamentos,
        job_paises
    WHERE
        job_localidades.codigo_iso = job_municipios.codigo_iso AND
        job_localidades.codigo_dane_departamento = job_municipios.codigo_dane_departamento AND
        job_localidades.codigo_dane_municipio = job_municipios.codigo_dane_municipio AND
        job_localidades.codigo_iso = job_departamentos.codigo_iso AND
        job_localidades.codigo_dane_departamento = job_departamentos.codigo_dane_departamento AND
        job_localidades.codigo_iso = job_paises.codigo_iso AND
        job_localidades.tipo = 'C'
        ORDER BY NOMBRE ASC;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_corregimientos AS
    SELECT
        CONCAT(job_localidades.codigo_iso, "|",job_localidades.codigo_dane_departamento,"|",job_localidades.codigo_dane_municipio,"|",job_localidades.tipo,"|",job_localidades.codigo_dane_localidad) AS id,
        job_localidades.nombre AS nombre,
        job_localidades.codigo_dane_localidad AS codigo_localidad,
        job_localidades.codigo_interno AS codigo_interno,
        job_localidades.estrato AS estrato,
        job_localidades.comuna AS comuna,
        job_municipios.nombre AS municipio,
        job_departamentos.nombre AS departamento,
        job_paises.nombre AS pais
    FROM
        job_localidades,
        job_municipios,
        job_departamentos,
        job_paises
    WHERE
        job_localidades.codigo_iso = job_municipios.codigo_iso AND
        job_localidades.codigo_dane_departamento = job_municipios.codigo_dane_departamento AND
        job_localidades.codigo_dane_municipio = job_municipios.codigo_dane_municipio AND
        job_localidades.codigo_iso = job_departamentos.codigo_iso AND
        job_localidades.codigo_dane_departamento = job_departamentos.codigo_dane_departamento AND
        job_localidades.codigo_iso = job_paises.codigo_iso AND
        job_localidades.tipo = 'C'
        ORDER BY NOMBRE ASC;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_corregimientos AS
    SELECT
        CONCAT(job_localidades.codigo_iso, "|",job_localidades.codigo_dane_departamento,"|",job_localidades.codigo_dane_municipio,"|",tipo,"|",job_localidades.codigo_dane_localidad) AS id,
        CONCAT(
            CONCAT(job_localidades.codigo_dane_departamento, job_localidades.codigo_dane_municipio,job_localidades.codigo_dane_localidad)
                            ,'-',job_localidades.nombre
                            ,'-',job_municipios.nombre,
            ', ', job_departamentos.nombre,
            ', ',job_paises.nombre,
            '|', CONCAT(job_departamentos.codigo_iso,",",job_municipios.codigo_dane_departamento,",",job_municipios.codigo_dane_municipio,job_localidades.codigo_dane_localidad)
        ) AS nombre ,
        job_localidades.codigo_dane_localidad AS codigo
    FROM
        job_localidades,
        job_municipios,
        job_departamentos,
        job_paises
    WHERE
        job_localidades.codigo_iso = job_municipios.codigo_iso AND
        job_localidades.codigo_dane_departamento = job_municipios.codigo_dane_departamento AND
        job_localidades.codigo_dane_municipio = job_municipios.codigo_dane_municipio AND
        job_localidades.codigo_iso = job_departamentos.codigo_iso AND
        job_localidades.codigo_dane_departamento = job_departamentos.codigo_dane_departamento AND
        job_localidades.codigo_iso = job_paises.codigo_iso AND
        job_localidades.tipo = 'C'
        ORDER BY NOMBRE ASC;
*/
?>

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

$borrarSiempre = false;

// Definición de tablas
$tablas["actividades_economicas"] = array(
    "codigo_iso"                 => "VARCHAR(2) NOT NULL COMMENT 'Llave principal de la tabla paises'",
    "codigo_dane_departamento"   => "VARCHAR(2) NOT NULL COMMENT 'Código DANE'",
    "codigo_dane_municipio"      => "VARCHAR(3) NOT NULL COMMENT 'Código DANE'",
    "codigo_dian"                => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'id de la tabla actividades economicas DIAN'",
    "codigo_actividad_municipio" => "INT(5) UNSIGNED ZEROFILL NULL COMMENT'Codigo de la actividad economica del municipio'",
    "codigo_interno"             => "SMALLINT(4) UNSIGNED ZEROFILL NULL COMMENT 'Código para uso interno de la empresa'",
    "descripcion"                => "VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'Detalle que describe de la tasa'"
);

// Definición de llaves primarias
$llavesPrimarias["actividades_economicas"] = "codigo_iso,codigo_dane_departamento,codigo_dane_municipio,codigo_dian,codigo_actividad_municipio";

// Definición de llaves foraneas
$llavesForaneas["actividades_economicas"] = array(
    array(
        // Nombre de la llave
        "actividad_economica_codigo_dian",
        // Nombre del campo clave de la tabla local
        "codigo_dian",
        // Nombre de la tabla relacionada
        "actividades_economicas_dian",
        // Nombre del campo clave en la tabla relacionada
        "codigo_dian"
    ),
    array(
        // Nombre de la llave
        "actividad_economica_id_municipio",
        // Nombre del campo clave de la tabla local
        "codigo_iso,codigo_dane_departamento,codigo_dane_municipio",
        // Nombre de la tabla relacionada
        "municipios",
        // Nombre del campo clave en la tabla relacionada
        "codigo_iso,codigo_dane_departamento,codigo_dane_municipio"
    )
);

// Inserción de id=0***/
$registros["actividades_economicas"] = array(
    array(
        "codigo_iso"                 => "",
        "codigo_dane_departamento"   => "",
        "codigo_dane_municipio"      => "",
        "codigo_dian"                => "0",
        "codigo_actividad_municipio" => "0",
        "codigo_interno"             => "0",
        "descripcion"                => ""
    )
);

// Inserción de datos iniciales
$registros["componentes"] = array(
    array(
        "id"              => "GESTAECO",
        "padre"           => "SUBMFINA",
        "id_modulo"       => "CONTABILIDAD",
        "orden"           => "0005",
        "visible"         => "1",
        "carpeta"         => "actividades_economicas",
        "archivo"         => "menu",
        "requiere_item"   => "1",
        "tabla_principal" => "actividades_economicas",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "ADICAECO",
        "padre"           => "GESTAECO",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "0",
        "orden"           => "10",
        "carpeta"         => "actividades_economicas",
        "archivo"         => "adicionar",
        "requiere_item"   => "0",
        "tabla_principal" => "actividades_economicas",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "CONSAECO",
        "padre"           => "GESTAECO",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "0",
        "orden"           => "20",
        "carpeta"         => "actividades_economicas",
        "archivo"         => "consultar",
        "requiere_item"   => "1",
        "tabla_principal" => "actividades_economicas",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "MODIAECO",
        "padre"           => "GESTAECO",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "0",
        "orden"           => "30",
        "carpeta"         => "actividades_economicas",
        "archivo"         => "modificar",
        "requiere_item"   => "1",
        "tabla_principal" => "actividades_economicas",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "ELIMAECO",
        "padre"           => "GESTAECO",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "0",
        "orden"           => "40",
        "carpeta"         => "actividades_economicas",
        "archivo"         => "eliminar",
        "requiere_item"   => "1",
        "tabla_principal" => "actividades_economicas",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "LISTAECO",
        "padre"           => "GESTAECO",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "0",
        "orden"           => "50",
        "carpeta"         => "actividades_economicas",
        "archivo"         => "listar",
        "requiere_item"   => "1",
        "tabla_principal" => "actividades_economicas",
        "tipo_enlace"     => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_actividades_economicas AS SELECT
            CONCAT(job_actividades_economicas.codigo_iso,\"|\",job_actividades_economicas.codigo_dane_departamento,\"|\",job_actividades_economicas.codigo_dane_municipio,\"|\",job_actividades_economicas.codigo_dian,\"|\",job_actividades_economicas.codigo_actividad_municipio) AS id,
            job_municipios.nombre AS MUNICIPIOS,
            job_actividades_economicas.codigo_actividad_municipio AS ACTIVIDAD_MUNICIPIO,
            job_actividades_economicas_dian.codigo_dian AS CODIGO_DIAN,
            job_actividades_economicas.codigo_interno AS CODIGO_INTERNO,
            job_actividades_economicas.descripcion AS DESCRIPCION,
            job_actividades_economicas_dian.descripcion AS ACTIVIDAD_DIAN
        FROM
            job_actividades_economicas,
            job_actividades_economicas_dian,
            job_municipios
        WHERE
            job_actividades_economicas.codigo_iso = job_municipios.codigo_iso AND
            job_actividades_economicas.codigo_dane_departamento = job_municipios.codigo_dane_departamento AND
            job_actividades_economicas.codigo_dane_municipio = job_municipios.codigo_dane_municipio AND
            job_actividades_economicas.codigo_dian = job_actividades_economicas_dian.codigo_dian AND
            job_actividades_economicas.codigo_dian != 0;"
    ),
    array(
       "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_actividades_economicas AS
        SELECT
            CONCAT(job_actividades_economicas.codigo_iso, \"|\",job_actividades_economicas.codigo_dane_departamento,\"|\",job_actividades_economicas.codigo_dane_municipio,\"|\",job_actividades_economicas.codigo_dian,\"|\",job_actividades_economicas.codigo_actividad_municipio) AS id,
            job_municipios.nombre AS municipios,
            job_actividades_economicas.codigo_iso AS codigo_iso,
            job_actividades_economicas.codigo_dane_departamento AS departamento,
            job_actividades_economicas.codigo_dane_municipio AS municipio,
            job_actividades_economicas.codigo_actividad_municipio AS actividad_municipio,
            job_actividades_economicas_dian.codigo_dian AS codigo_dian,
            job_actividades_economicas.codigo_interno AS codigo_interno,
            job_actividades_economicas.descripcion AS descripcion,
            job_actividades_economicas_dian.descripcion AS actividad_dian
        FROM
            job_actividades_economicas,
            job_actividades_economicas_dian,
            job_municipios
        WHERE
            job_actividades_economicas.codigo_iso = job_municipios.codigo_iso AND
            job_actividades_economicas.codigo_dane_departamento = job_municipios.codigo_dane_departamento AND
            job_actividades_economicas.codigo_dane_municipio = job_municipios.codigo_dane_municipio AND
            job_actividades_economicas.codigo_dian = job_actividades_economicas_dian.codigo_dian AND
            job_actividades_economicas.codigo_dian != 0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_actividades_economicas AS
        SELECT
            CONCAT (job_actividades_economicas.codigo_iso,'|',job_actividades_economicas.codigo_dane_departamento,'|',job_actividades_economicas.codigo_dane_municipio,'|',job_actividades_economicas.codigo_dian,'|',job_actividades_economicas.codigo_actividad_municipio) AS id,
            CONCAT(job_actividades_economicas.codigo_dian,' - ',job_actividades_economicas.descripcion,'|',job_actividades_economicas.codigo_iso,',',job_actividades_economicas.codigo_dane_departamento,',',job_actividades_economicas.codigo_dane_municipio,',',job_actividades_economicas.codigo_dian,',',job_actividades_economicas.codigo_actividad_municipio) AS descripcion
        FROM
            job_actividades_economicas,
            job_actividades_economicas_dian,
            job_municipios
        WHERE
            job_actividades_economicas.codigo_iso = job_municipios.codigo_iso AND
            job_actividades_economicas.codigo_dane_departamento = job_municipios.codigo_dane_departamento AND
            job_actividades_economicas.codigo_dane_municipio = job_municipios.codigo_dane_municipio AND
            job_actividades_economicas.codigo_dian = job_actividades_economicas_dian.codigo_dian AND
            job_actividades_economicas.codigo_dian != 0;"
    )
);

/*
    DROP TABLE IF EXISTS job_menu_actividades_economicas;
    DROP TABLE IF EXISTS job_buscador_actividades_economicas;
    DROP TABLE IF EXISTS job_seleccion_actividades_economicas;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_actividades_economicas AS SELECT
        CONCAT(job_actividades_economicas.codigo_iso,"|",job_actividades_economicas.codigo_dane_departamento,"|",job_actividades_economicas.codigo_dane_municipio,"|",job_actividades_economicas.codigo_dian,"|",job_actividades_economicas.codigo_actividad_municipio) AS id,
        job_municipios.nombre AS MUNICIPIOS,
        job_actividades_economicas.codigo_actividad_municipio AS ACTIVIDAD_MUNICIPIO,
        job_actividades_economicas_dian.codigo_dian AS CODIGO_DIAN,
        job_actividades_economicas.codigo_interno AS CODIGO_INTERNO,
        job_actividades_economicas.descripcion AS DESCRIPCION,
        job_actividades_economicas_dian.descripcion AS ACTIVIDAD_DIAN
    FROM
        job_actividades_economicas,
        job_actividades_economicas_dian,
        job_municipios
    WHERE
        job_actividades_economicas.codigo_iso = job_municipios.codigo_iso AND
        job_actividades_economicas.codigo_dane_departamento = job_municipios.codigo_dane_departamento AND
        job_actividades_economicas.codigo_dane_municipio = job_municipios.codigo_dane_municipio AND
        job_actividades_economicas.codigo_dian = job_actividades_economicas_dian.codigo_dian AND
        job_actividades_economicas.codigo_dian != 0;

   CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_actividades_economicas AS
    SELECT
        CONCAT(job_actividades_economicas.codigo_iso, "|",job_actividades_economicas.codigo_dane_departamento,"|",job_actividades_economicas.codigo_dane_municipio,"|",job_actividades_economicas.codigo_dian,"|",job_actividades_economicas.codigo_actividad_municipio) AS id,
        job_municipios.nombre AS municipios,
        job_actividades_economicas.codigo_iso AS codigo_iso,
        job_actividades_economicas.codigo_dane_departamento AS departamento,
        job_actividades_economicas.codigo_dane_municipio AS municipio,
        job_actividades_economicas.codigo_actividad_municipio AS actividad_municipio,
        job_actividades_economicas_dian.codigo_dian AS codigo_dian,
        job_actividades_economicas.codigo_interno AS codigo_interno,
        job_actividades_economicas.descripcion AS descripcion,
        job_actividades_economicas_dian.descripcion AS actividad_dian
    FROM
        job_actividades_economicas,
        job_actividades_economicas_dian,
        job_municipios
    WHERE
        job_actividades_economicas.codigo_iso = job_municipios.codigo_iso AND
        job_actividades_economicas.codigo_dane_departamento = job_municipios.codigo_dane_departamento AND
        job_actividades_economicas.codigo_dane_municipio = job_municipios.codigo_dane_municipio AND
        job_actividades_economicas.codigo_dian = job_actividades_economicas_dian.codigo_dian AND
        job_actividades_economicas.codigo_dian != 0;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_actividades_economicas AS
    SELECT
        CONCAT(job_actividades_economicas.codigo_iso,'|',job_actividades_economicas.codigo_dane_departamento,'|',job_actividades_economicas.codigo_dane_municipio,'|',job_actividades_economicas.codigo_dian,'|',job_actividades_economicas.codigo_actividad_municipio) AS id,
        CONCAT(job_actividades_economicas.codigo_dian,' - ',job_actividades_economicas.descripcion,'|',job_actividades_economicas.codigo_iso,',',job_actividades_economicas.codigo_dane_departamento,',',job_actividades_economicas.codigo_dane_municipio,',',job_actividades_economicas.codigo_dian,',',job_actividades_economicas.codigo_actividad_municipio) AS descripcion
    FROM
        job_actividades_economicas,
        job_actividades_economicas_dian,
        job_municipios
    WHERE
        job_actividades_economicas.codigo_iso = job_municipios.codigo_iso AND
        job_actividades_economicas.codigo_dane_departamento = job_municipios.codigo_dane_departamento AND
        job_actividades_economicas.codigo_dane_municipio = job_municipios.codigo_dane_municipio AND
        job_actividades_economicas.codigo_dian = job_actividades_economicas_dian.codigo_dian AND
        job_actividades_economicas.codigo_dian != 0;
*/

?>

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
$tablas["actividades_economicas_dian"] = array(
    "codigo_dian" => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo DIAN'",
    "descripcion" => "VARCHAR(255) NOT NULL COMMENT 'Detalle que describe el la actividad economica de la dian'"
);

// Definición de llaves primarias
$llavesPrimarias["actividades_economicas_dian"] = "codigo_dian";

$registros["actividades_economicas_dian"] = array(
    array(
        "codigo_dian" => "0",
        "descripcion" => ""
    )
);

// Inserción de datos iniciales
$registros["componentes"] = array(
    array(
        "id"              => "GESTACDI",
        "padre"           => "SUBMFINA",
        "id_modulo"       => "CONTABILIDAD",
        "orden"           => "0004",
        "visible"         => "1",
        "carpeta"         => "actividades_economicas_dian",
        "archivo"         => "menu",
        "requiere_item"   => "1",
        "tabla_principal" => "actividades_economicas_dian",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "ADICACDI",
        "padre"           => "GESTACDI",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "0",
        "orden"           => "10",
        "carpeta"         => "actividades_economicas_dian",
        "archivo"         => "adicionar",
        "requiere_item"   => "0",
        "tabla_principal" => "actividades_economicas_dian",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "CONSACDI",
        "padre"           => "GESTACDI",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "0",
        "orden"           => "20",
        "carpeta"         => "actividades_economicas_dian",
        "archivo"         => "consultar",
        "requiere_item"   => "1",
        "tabla_principal" => "actividades_economicas_dian",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "MODIACDI",
        "padre"           => "GESTACDI",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "0",
        "orden"           => "30",
        "carpeta"         => "actividades_economicas_dian",
        "archivo"         => "modificar",
        "requiere_item"   => "1",
        "tabla_principal" => "actividades_economicas_dian",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "ELIMACDI",
        "padre"           => "GESTACDI",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "0",
        "orden"           => "40",
        "carpeta"         => "actividades_economicas_dian",
        "archivo"         => "eliminar",
        "requiere_item"   => "1",
        "tabla_principal" => "actividades_economicas_dian",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "LISTACDI",
        "padre"           => "GESTACDI",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "0",
        "orden"           => "50",
        "carpeta"         => "actividades_economicas_dian",
        "archivo"         => "listar",
        "requiere_item"   => "1",
        "tabla_principal" => "actividades_economicas_dian",
        "tipo_enlace"     => "1"
    )
);
$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_actividades_economicas_dian AS
        SELECT  job_actividades_economicas_dian.codigo_dian AS id,
                job_actividades_economicas_dian.codigo_dian AS CODIGO_DIAN,
                job_actividades_economicas_dian.descripcion AS DESCRIPCION
        FROM    job_actividades_economicas_dian
        WHERE   job_actividades_economicas_dian.codigo_dian !=0
                ORDER BY job_actividades_economicas_dian.codigo_dian;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_actividades_economicas_dian AS
        SELECT  job_actividades_economicas_dian.codigo_dian AS id,
                job_actividades_economicas_dian.codigo_dian AS codigo_dian,
                job_actividades_economicas_dian.descripcion AS descripcion
        FROM    job_actividades_economicas_dian
        WHERE   job_actividades_economicas_dian.codigo_dian !=0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_actividades_economicas_dian AS
        SELECT  job_actividades_economicas_dian.codigo_dian AS id,
                CONCAT(
                    job_actividades_economicas_dian.codigo_dian,' -',job_actividades_economicas_dian.descripcion,
                    '|',job_actividades_economicas_dian.codigo_dian
                ) AS descripcion
        FROM    job_actividades_economicas_dian
        WHERE   job_actividades_economicas_dian.codigo_dian !=0;"
    )
);
/***
    DROP TABLE IF EXISTS job_menu_actividades_economicas_dian;
    DROP TABLE IF EXISTS job_buscador_actividades_economicas_dian;
    DROP TABLE IF EXISTS job_seleccion_actividades_economicas_dian;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_actividades_economicas_dian AS
    SELECT  job_actividades_economicas_dian.codigo_dian AS id,
            job_actividades_economicas_dian.codigo_dian AS CODIGO_DIAN,
            job_actividades_economicas_dian.descripcion AS DESCRIPCION
    FROM    job_actividades_economicas_dian
    WHERE   job_actividades_economicas_dian.codigo_dian !=0
            ORDER BY job_actividades_economicas_dian.codigo_dian;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_actividades_economicas_dian AS
    SELECT  job_actividades_economicas_dian.codigo_dian AS id,
            job_actividades_economicas_dian.codigo_dian AS codigo_dian,
            job_actividades_economicas_dian.descripcion AS descripcion
    FROM    job_actividades_economicas_dian
    WHERE   job_actividades_economicas_dian.codigo_dian !=0;


    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_actividades_economicas_dian AS
    SELECT  job_actividades_economicas_dian.codigo_dian AS id,
            CONCAT(
                job_actividades_economicas_dian.codigo_dian,' -',job_actividades_economicas_dian.descripcion,
                '|',job_actividades_economicas_dian.codigo_dian
            ) AS descripcion

    FROM    job_actividades_economicas_dian
    WHERE   job_actividades_economicas_dian.codigo_dian !=0;

***/
?>

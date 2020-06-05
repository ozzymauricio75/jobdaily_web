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
$tablas["idiomas"] = array(
    "codigo"      => "INT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Descripcion que identifica el idioma'",
    "descripcion"  => "VARCHAR(255) NOT NULL COMMENT 'Detalle que describe el idioma '"
);

// Definición de llaves primarias
$llavesPrimarias["idiomas"] = "codigo";

// Definición de campos únicos
$llavesUnicas["idiomas"] = array(
    "descripcion"
);

$registros["idiomas"] = array(
    array(
        "codigo"        => "0",
        "descripcion"   => ""
    )
);

$registros["componentes"] = array(
    array(
        "id"            => "GESTIDIO",
        "padre"         => "SUBMDCRH",
        "id_modulo"     => "NOMINA",
        "orden"         => "140",
        "visible"       => "1",
        "carpeta"       => "idiomas",
        "global"        => "0",
        "archivo"       => "menu",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "ADICIDIO",
        "padre"         => "GESTIDIO",
        "id_modulo"     => "NOMINA",
        "orden"         => "10",
        "visible"       => "0",
        "carpeta"       => "idiomas",
        "global"        => "0",
        "archivo"       => "adicionar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "CONSIDIO",
        "padre"         => "GESTIDIO",
        "id_modulo"     => "NOMINA",
        "orden"         => "25",
        "visible"       => "0",
        "carpeta"       => "idiomas",
        "global"        => "0",
        "archivo"       => "consultar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "MODIIDIO",
        "padre"         => "GESTIDIO",
        "id_modulo"     => "NOMINA",
        "orden"         => "30",
        "visible"       => "0",
        "carpeta"       => "idiomas",
        "global"        => "0",
        "archivo"       => "modificar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "ELIMIDIO",
        "padre"         => "GESTIDIO",
        "id_modulo"     => "NOMINA",
        "orden"         => "40",
        "visible"       => "0",
        "carpeta"       => "idiomas",
        "global"        => "0",
        "archivo"       => "eliminar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_idiomas AS
        SELECT codigo AS id,
        codigo AS CODIGO,
        descripcion AS DESCRIPCION
        FROM job_idiomas
        WHERE codigo > 0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_idiomas AS
        SELECT codigo AS id,
        codigo AS CODIGO,
        descripcion AS DESCRIPCION
        FROM job_idiomas
        WHERE codigo > 0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_idiomas AS
        SELECT job_idiomas.codigo AS id,
        CONCAT(job_idiomas.codigo, ' ',
        job_idiomas.descripcion, '|', job_idiomas.codigo) AS descripcion
        FROM job_idiomas
        WHERE codigo > 0 ;"
    )
);
// Sentencia para la creaciÓn de la vista requerida
/***
    DROP TABLE IF EXISTS job_menu_idiomas;
    DROP TABLE IF EXISTS job_buscador_idiomas;
    DROP TABLE IF EXISTS job_seleccion_idiomas;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_idiomas AS
    SELECT codigo AS id,
    codigo AS CODIGO,
    descripcion AS DESCRIPCION
    FROM job_idiomas
    WHERE codigo > 0;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_idiomas AS
    SELECT codigo AS id,
    codigo AS CODIGO,
    descripcion AS DESCRIPCION
    FROM job_idiomas
    WHERE codigo > 0;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_idiomas AS
    SELECT job_idiomas.codigo AS id,
    CONCAT(job_idiomas.codigo, ' ',
    job_idiomas.descripcion, '|', job_idiomas.codigo) AS descripcion
    FROM job_idiomas
    WHERE codigo > 0 ;
***/
?>

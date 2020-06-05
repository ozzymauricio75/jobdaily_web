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
$tablas["deportes"] = array(
    "codigo"        => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código interno que identifica el deporte'",
    "descripcion"   => "VARCHAR(255) NOT NULL COMMENT 'Detalle que describe el deporte '"
);

// DefiniciÃ³n de llaves primarias
$llavesPrimarias["deportes"] = "codigo";

// DefiniciÃ³n de campos Ãºnicos
$llavesUnicas["deportes"] = array(
    "descripcion"
);

$registros["deportes"] = array(
    array(
        "codigo"        => "0",
        "descripcion"   => ""
    )
);

// Inserción de datos iniciales
$registros["componentes"] = array(
    array(
        "id"            => "GESTDEPO",
        "padre"         => "SUBMDCRH",
        "id_modulo"     => "NOMINA",
        "orden"         => "160",
        "visible"       => "1",
        "carpeta"       => "deportes",
        "global"        => "0",
        "archivo"       => "menu",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "ADICDEPO",
        "padre"         => "GESTDEPO",
        "id_modulo"     => "NOMINA",
        "orden"         => "10",
        "visible"       => "0",
        "carpeta"       => "deportes",
        "global"        => "0",
        "archivo"       => "adicionar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "CONSDEPO",
        "padre"         => "GESTDEPO",
        "id_modulo"     => "NOMINA",
        "orden"         => "25",
        "visible"       => "0",
        "carpeta"       => "deportes",
        "global"        => "0",
        "archivo"       => "consultar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "MODIDEPO",
        "padre"         => "GESTDEPO",
        "id_modulo"     => "NOMINA",
        "orden"         => "30",
        "visible"       => "0",
        "carpeta"       => "deportes",
        "global"        => "0",
        "archivo"       => "modificar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "ELIMDEPO",
        "padre"         => "GESTDEPO",
        "id_modulo"     => "NOMINA",
        "orden"         => "40",
        "visible"       => "0",
        "carpeta"       => "deportes",
        "global"        => "0",
        "archivo"       => "eliminar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM=MERGE VIEW job_menu_deportes AS
        SELECT codigo AS id,
        codigo AS CODIGO,
        descripcion AS DESCRIPCION
        FROM job_deportes
        WHERE codigo > 0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM=MERGE VIEW job_buscador_deportes AS
        SELECT codigo AS id,
        codigo AS CODIGO,
        descripcion AS DESCRIPCION
        FROM job_deportes
        WHERE codigo > 0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM=MERGE VIEW job_seleccion_deportes AS
        SELECT job_deportes.codigo AS id,
        CONCAT(job_deportes.codigo, ' ',
        job_deportes.descripcion, '|', job_deportes.codigo) AS descripcion
        FROM job_deportes
        WHERE codigo > 0 ;"
    )
);
// Sentencia para la creación de la vista requerida
/***
    DROP TABLE IF EXISTS job_menu_deportes;
    DROP TABLE IF EXISTS job_buscador_deportes;
    DROP TABLE IF EXISTS job_seleccion_deportes;
     
    CREATE OR REPLACE ALGORITHM=MERGE VIEW job_menu_deportes AS
    SELECT codigo AS id,
    codigo AS CODIGO,
    descripcion AS DESCRIPCION
    FROM job_deportes
    WHERE codigo > 0;

    CREATE OR REPLACE ALGORITHM=MERGE VIEW job_buscador_deportes AS
    SELECT codigo AS id,
    codigo AS CODIGO,
    descripcion AS DESCRIPCION
    FROM job_deportes
    WHERE codigo > 0;

    CREATE OR REPLACE ALGORITHM=MERGE VIEW job_seleccion_deportes AS
    SELECT job_deportes.codigo AS id,
    CONCAT(job_deportes.codigo, ' ',
    job_deportes.descripcion, '|', job_deportes.codigo) AS descripcion
    FROM job_deportes
    WHERE codigo > 0 ;
***/
?>

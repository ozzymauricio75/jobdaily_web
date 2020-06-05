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
* modificarlo  bajo los t�rminos de la Licencia P�blica General GNU
* publicada por la Fundaci�n para el Software Libre, ya sea la versi�n 3
* de la Licencia, o (a su elecci�n) cualquier versi�n posterior.
*
* Este programa se distribuye con la esperanza de que sea �til, pero
* SIN GARANT�A ALGUNA; ni siquiera la garant�a impl�cita MERCANTIL o
* de APTITUD PARA UN PROP�SITO DETERMINADO. Consulte los detalles de
* la Licencia P�blica General GNU para obtener una informaci�n m�s
* detallada.
*
* Deber�a haber recibido una copia de la Licencia P�blica General GNU
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
**/

$borrarSiempre = false;

// Definici�n de tablas
$tablas["deportes"] = array(
    "codigo"        => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'C�digo interno que identifica el deporte'",
    "descripcion"   => "VARCHAR(255) NOT NULL COMMENT 'Detalle que describe el deporte '"
);

// Definición de llaves primarias
$llavesPrimarias["deportes"] = "codigo";

// Definición de campos únicos
$llavesUnicas["deportes"] = array(
    "descripcion"
);

$registros["deportes"] = array(
    array(
        "codigo"        => "0",
        "descripcion"   => ""
    )
);

// Inserci�n de datos iniciales
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
// Sentencia para la creaci�n de la vista requerida
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

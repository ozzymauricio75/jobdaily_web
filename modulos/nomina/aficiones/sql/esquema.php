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
$tablas["aficiones"] = array(
    "codigo"  		=> "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código interno que identifica la aficción'",
    "descripcion"	=> "VARCHAR(255) NOT NULL COMMENT 'Detalle que describe la aficción '"
);

// Definición de llaves primarias
$llavesPrimarias["aficiones"] = "codigo";

// Definición de campos únicos
$llavesUnicas["aficiones"] = array(
    "descripcion"
);

$registros["aficiones"] = array(
    array(
        "codigo"        => "0",
        "descripcion"   => ""
    )
);

// Inserción de datos iniciales***/
$registros["componentes"] = array(
    array(
        "id"                => "GESTAFIC",
        "padre"             => "SUBMDCRH",
        "id_modulo"         => "NOMINA",
        "visible"           => "1",
        "orden"             => "170",
        "carpeta"           => "aficiones",
        "archivo"           => "menu",
        "requiere_item"     => "1",
        "tabla_principal"   => "aficiones",
        "tipo_enlace"       => "1"
    ),
    array(
        "id"                => "ADICAFIC",
        "padre"             => "GESTAFIC",
        "id_modulo"         => "NOMINA",
        "visible"           => "0",
        "orden"             => "0025",
        "carpeta"           => "aficiones",
        "archivo"           => "adicionar",
        "requiere_item"     => "0",
        "tabla_principal"   => "aficiones",
        "tipo_enlace"       => "1"
    ),
    array(
        "id"                => "CONSAFIC",
        "padre"             => "GESTAFIC",
        "id_modulo"         => "NOMINA",
        "visible"           => "0",
        "orden"             => "0010",
        "carpeta"           => "aficiones",
        "archivo"           => "consultar",
        "requiere_item"     => "1",
        "tabla_principal"   => "aficiones",
        "tipo_enlace"       => "1"
    ),
    array(
        "id"                => "MODIAFIC",
        "padre"             => "GESTAFIC",
        "id_modulo"         => "NOMINA",
        "visible"           => "0",
        "orden"             => "0015",
        "carpeta"           => "aficiones",
        "archivo"           => "modificar",
        "requiere_item"     => "1",
        "tabla_principal"   => "aficiones",
        "tipo_enlace"       => "1"
    ),
    array(
        "id"                => "ELIMAFIC",
        "padre"             => "GESTAFIC",
        "id_modulo"         => "NOMINA",
        "visible"           => "0",
        "orden"             => "0020",
        "carpeta"           => "aficiones",
        "archivo"           => "eliminar",
        "requiere_item"     => "1",
        "tabla_principal"   => "aficiones",
        "tipo_enlace"       => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_aficiones AS
        SELECT codigo AS id,
        codigo AS CODIGO,
        descripcion AS DESCRIPCION
        FROM job_aficiones
        WHERE codigo > 0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_aficiones AS
        SELECT codigo AS id,
        codigo AS CODIGO,
        descripcion AS DESCRIPCION
        FROM job_aficiones
        WHERE codigo > 0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_aficiones AS
        SELECT job_aficiones.codigo AS id,
        CONCAT(job_aficiones.codigo, ' ',
        job_aficiones.descripcion, '|', job_aficiones.codigo) AS descripcion
        FROM job_aficiones
        WHERE codigo > 0 ;"
    )
);
// Sentencia para la creaciÓn de la vista requerida
/***
    DROP TABLE IF EXISTS job_menu_aficiones;
    DROP TABLE IF EXISTS job_buscador_aficiones;
    DROP TABLE IF EXISTS job_seleccion_aficiones;
    
    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_aficiones AS
    SELECT codigo AS id,
    codigo AS CODIGO,
    descripcion AS DESCRIPCION
    FROM job_aficiones
    WHERE codigo > 0;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_aficiones AS
    SELECT codigo AS id,
    codigo AS CODIGO,
    descripcion AS DESCRIPCION
    FROM job_aficiones
    WHERE codigo > 0;
     
    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_aficiones AS
    SELECT job_aficiones.codigo AS id,
    CONCAT(job_aficiones.codigo, ' ',
    job_aficiones.descripcion, '|', job_aficiones.codigo) AS descripcion
    FROM job_aficiones
    WHERE codigo > 0 ;
***/
?>

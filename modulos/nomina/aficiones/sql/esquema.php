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
* de APTITUD PARA UN PROP�ITO DETERMINADO. Consulte los detalles de
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
$tablas["aficiones"] = array(
    "codigo"  		=> "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'C�digo interno que identifica la aficci�n'",
    "descripcion"	=> "VARCHAR(255) NOT NULL COMMENT 'Detalle que describe la aficci�n '"
);

// Definici�n de llaves primarias
$llavesPrimarias["aficiones"] = "codigo";

// Definici�n de campos �nicos
$llavesUnicas["aficiones"] = array(
    "descripcion"
);

$registros["aficiones"] = array(
    array(
        "codigo"        => "0",
        "descripcion"   => ""
    )
);

// Inserci�n de datos iniciales***/
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
// Sentencia para la creaci�n de la vista requerida
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

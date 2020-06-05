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
$tablas["formatos_dian"] = array(
    "codigo"      => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'C�digo asignado por la DIAN'",
    "descripcion" => "VARCHAR(255) NOT NULL COMMENT 'Detalle que describe el formato DIAN'"
);

// Definici�n de llaves primarias
$llavesPrimarias["formatos_dian"] = "codigo";

 // Definici�n de campos �nicos
$llavesUnicas["formatos_dian"] = array(
    "descripcion"
);

// Inserci�n de datos iniciales id=0***/
$registros["formatos_dian"] = array(
    array(
        "codigo" => 0,
        "descripcion" => ""
    )
);

// Inserci�n de datos iniciales
$registros["componentes"] = array(
    array(
        "id"              => "GESTFODI",
        "padre"           => "SUBMINTR",
        "id_modulo"       => "CONTABILIDAD",
        "orden"           => "0020",
        "visible"         => "1",
        "carpeta"         => "formatos_dian",
        "archivo"         => "menu",
        "requiere_item"   => "1",
        "tabla_principal" => "formatos_dian",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "ADICFODI",
        "padre"           => "GESTFODI",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "0",
        "orden"           => "0005",
        "carpeta"         => "formatos_dian",
        "archivo"         => "adicionar",
        "requiere_item"   => "0",
        "tabla_principal" => "formatos_dian",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "CONSFODI",
        "padre"           => "GESTFODI",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "0",
        "orden"           => "0010",
        "carpeta"         => "formatos_dian",
        "archivo"         => "consultar",
        "requiere_item"   => "1",
        "tabla_principal" => "formatos_dian",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "MODIFODI",
        "padre"           => "GESTFODI",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "0",
        "orden"           => "0015",
        "carpeta"         => "formatos_dian",
        "archivo"         => "modificar",
        "requiere_item"   => "1",
        "tabla_principal" => "formatos_dian",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "ELIMFODI",
        "padre"           => "GESTFODI",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "0",
        "orden"           => "0020",
        "carpeta"         => "formatos_dian",
        "archivo"         => "eliminar",
        "requiere_item"   => "1",
        "tabla_principal" => "formatos_dian",
        "tipo_enlace"     => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_formatos_dian AS 
        SELECT codigo AS id,
        codigo AS CODIGO_DIAN,
        descripcion AS DESCRIPCION 
        FROM job_formatos_dian
        WHERE codigo>0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_formatos_dian AS 
        SELECT codigo AS id,
        codigo AS codigo,
        descripcion AS descripcion 
        FROM job_formatos_dian
        WHERE codigo>0;"
    )
);

/***
	CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_formatos_dian AS 
	SELECT codigo AS id,
	codigo AS CODIGO_DIAN,
	descripcion AS DESCRIPCION 
	FROM job_formatos_dian
    WHERE codigo>0;

	CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_formatos_dian AS 
	SELECT codigo AS id,
	codigo AS codigo,
	descripcion AS descripcion 
	FROM job_formatos_dian
    WHERE codigo>0;
***/
?>

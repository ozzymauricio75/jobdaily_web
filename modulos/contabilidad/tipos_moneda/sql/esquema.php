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

// Definici�n de tablas ***/
$tablas["tipos_moneda"] = array(
    "codigo"      => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo asignado por la empresa'",
    "codigo_dian" => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo asignado por la DIAN'",
    "nombre"      => "VARCHAR(30) NOT NULL COMMENT 'Nombre de la moneda'"
);

// Definici�n de llaves primarias ***/
$llavesPrimarias["tipos_moneda"] = "codigo";

 // Definici�n de campos �nicos ***/
$llavesUnicas["tipos_moneda"] = array(
	"codigo_dian",
    "nombre",
);

// Inserci�n de datos iniciales id=0***/
$registros["tipos_moneda"] = array(
    array(
        "codigo"      => 0,       
        "codigo_dian" => 0,
        "nombre"      => ""
    )
);


// Inserci�n de datos iniciales ***/
$registros["componentes"] = array(
    array(
        "id"        	  => "GESTTIMO",
        "padre"     	  => "SUBMFINA",
        "id_modulo" 	  => "CONTABILIDAD",
        "orden"     	  => "0035",
        "visible"   	  => "1",
        "carpeta"   	  => "tipos_moneda",
        "archivo"   	  => "menu",
        "requiere_item"   => "1",
        "tabla_principal" => "tipos_moneda",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "ADICTIMO",
        "padre"     	  => "GESTTIMO",
        "id_modulo" 	  => "CONTABILIDAD",
        "visible"   	  => "0",
        "orden"     	  => "0005",
        "carpeta"   	  => "tipos_moneda",
        "archivo"   	  => "adicionar",
        "requiere_item"   => "0",
        "tabla_principal" => "tipos_moneda",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "CONSTIMO",
        "padre"     	  => "GESTTIMO",
        "id_modulo" 	  => "CONTABILIDAD",
        "visible"  	 	  => "0",
        "orden"     	  => "0010",
        "carpeta"   	  => "tipos_moneda",
        "archivo"   	  => "consultar",
        "requiere_item"   => "1",
        "tabla_principal" => "tipos_moneda",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "MODITIMO",
        "padre"     	  => "GESTTIMO",
        "id_modulo" 	  => "CONTABILIDAD",
        "visible"   	  => "0",
        "orden"     	  => "0015",
        "carpeta"   	  => "tipos_moneda",
        "archivo"   	  => "modificar",
        "requiere_item"   => "1",
        "tabla_principal" => "tipos_moneda",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "ELIMTIMO",
        "padre"     	  => "GESTTIMO",
        "id_modulo" 	  => "CONTABILIDAD",
        "visible"  		  => "0",
        "orden"     	  => "0020",
        "carpeta"   	  => "tipos_moneda",
        "archivo"   	  => "eliminar",
        "requiere_item"   => "1",
        "tabla_principal" => "tipos_moneda",
        "tipo_enlace"     => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_tipos_moneda AS 
        SELECT codigo AS id,
        codigo AS CODIGO,
        codigo_dian AS CODIGO_DIAN,
        nombre AS NOMBRE
        FROM job_tipos_moneda
        WHERE codigo > 0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_tipos_moneda AS 
        SELECT codigo AS id,
        codigo AS codigo,
        codigo_dian AS codigo_dian,
        nombre AS nombre
        FROM job_tipos_moneda
        WHERE codigo > 0;"
    )
);
/***
	CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_tipos_moneda AS 
	SELECT codigo AS id,
	codigo AS CODIGO,
	codigo_dian AS CODIGO_DIAN,
	nombre AS NOMBRE
	FROM job_tipos_moneda
    WHERE codigo > 0;

	CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_tipos_moneda AS 
	SELECT codigo AS id,
	codigo AS codigo,
	codigo_dian AS codigo_dian,
	nombre AS nombre
	FROM job_tipos_moneda
    WHERE codigo > 0;
***/
?>

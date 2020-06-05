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

/*** Definici�n de tablas ***/
$tablas["tipos_unidades"] = array(
    "codigo" => "SMALLINT(2) UNSIGNED ZEROFILL  NOT NULL  COMMENT 'Codigo interno manejado por la empresa'",
    "nombre" => "VARCHAR(255) NOT NULL COMMENT 'Nombre que identifica el tipo de unidad de medida'"
);

/*** Definici�n de llaves primarias***/
$llavesPrimarias["tipos_unidades"] = "codigo";

/*** Definici�n de  campos �nicos***/
$llavesUnicas["tipos_unidades"] = array(
    "nombre"
);

/*** Inserci�n de datos iniciales ***/
$registros["tipos_unidades"] = array(
    array(
		"codigo" => "0",
		"nombre" => ""
    )    
);

/*** Inserci�n de datos iniciales ***/
$registros["componentes"] = array(
    array(
        "id"        	  => "GESTTIUN",
        "padre"     	  => "SUBMDCIN",
        "id_modulo" 	  => "INVENTARIO",
        "visible"   	  => "1",
        "orden"     	  => "60",
        "carpeta"   	  => "tipos_unidades",
        "archivo"   	  => "menu",
        "requiere_item"   => "1",
        "tabla_principal" => "tipos_unidades",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "ADICTIUN",
        "padre"     	  => "GESTTIUN",
        "id_modulo" 	  => "INVENTARIO",
        "visible"   	  => "0",
        "orden"     	  => "10",
        "carpeta"   	  => "tipos_unidades",
        "archivo"   	  => "adicionar",
        "requiere_item"   => "0",
        "tabla_principal" => "tipos_unidades",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "CONSTIUN",
        "padre"     	  => "GESTTIUN",
        "id_modulo" 	  => "INVENTARIO",
        "visible"   	  => "0",
        "orden"     	  => "20",
        "carpeta"   	  => "tipos_unidades",
        "archivo"   	  => "consultar",
        "requiere_item"   => "1",
        "tabla_principal" => "tipos_unidades",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "MODITIUN",
        "padre"     	  => "GESTTIUN",
        "id_modulo" 	  => "INVENTARIO",
        "visible"   	  => "0",
        "orden"     	  => "30",
        "carpeta"   	  => "tipos_unidades",
        "archivo"   	  => "modificar",
        "requiere_item"   => "1",
        "tabla_principal" => "tipos_unidades",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "ELIMTIUN",
        "padre"     	  => "GESTTIUN",
        "id_modulo" 	  => "INVENTARIO",
        "visible"   	  => "0",
        "orden"     	  => "40",
        "carpeta"   	  => "tipos_unidades",
        "archivo"   	  => "eliminar",
        "requiere_item"   => "1",
        "tabla_principal" => "tipos_unidades",
        "tipo_enlace"     => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_tipos_unidades AS
        SELECT codigo AS id,
        codigo AS CODIGO,
        nombre AS NOMBRE
        FROM job_tipos_unidades
        WHERE codigo != 0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_tipos_unidades AS
        SELECT codigo AS id,
        nombre AS nombre,
        codigo AS codigo
        FROM job_tipos_unidades
        WHERE codigo != 0;"
    )
);

/***
	CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_tipos_unidades AS
	SELECT codigo AS id,
	codigo AS CODIGO,
	nombre AS NOMBRE
	FROM job_tipos_unidades
	WHERE codigo != 0;

	CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_tipos_unidades AS
	SELECT codigo AS id,
	nombre AS nombre,
	codigo AS codigo
	FROM job_tipos_unidades
	WHERE codigo != 0;

***/
?>

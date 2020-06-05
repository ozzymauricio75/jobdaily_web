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

/*** Definición de tablas ***/
$tablas["tipos_unidades"] = array(
    "codigo" => "SMALLINT(2) UNSIGNED ZEROFILL  NOT NULL  COMMENT 'Codigo interno manejado por la empresa'",
    "nombre" => "VARCHAR(255) NOT NULL COMMENT 'Nombre que identifica el tipo de unidad de medida'"
);

/*** Definición de llaves primarias***/
$llavesPrimarias["tipos_unidades"] = "codigo";

/*** Definición de  campos únicos***/
$llavesUnicas["tipos_unidades"] = array(
    "nombre"
);

/*** Inserción de datos iniciales ***/
$registros["tipos_unidades"] = array(
    array(
		"codigo" => "0",
		"nombre" => ""
    )    
);

/*** Inserción de datos iniciales ***/
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

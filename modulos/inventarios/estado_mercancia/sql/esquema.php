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
$tablas["estado_mercancia"] = array(
    "codigo"          => "SMALLINT(3) UNSIGNED ZEROFILL AUTO_INCREMENT NOT NULL COMMENT 'Código interno de la base de datos'",
    "descripcion" => "VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'Descripción de la marca'",
);

// Definición de llaves primarias
$llavesPrimarias["estado_mercancia"] = "codigo";

// Definición de campos únicos
$llavesUnicas["estado_mercancia"] = array(
    "descripcion"
);

// Inserción de datos iniciales***/
$registros["componentes"] = array(
    array(
        "id"        	  => "GESTESME",
        "padre"     	  => "SUBMDCIN",
        "id_modulo" 	  => "INVENTARIO",
        "visible"   	  => "1",
        "orden"     	  => "0040",
        "carpeta"   	  => "estado_mercancia",
        "archivo"   	  => "menu",
        "requiere_item"   => "1",
        "tabla_principal" => "estado_mercancia",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "ADICESME",
        "padre"     	  => "GESTESME",
        "id_modulo" 	  => "INVENTARIO",
        "visible"   	  => "0",
        "orden"     	  => "0005",
        "carpeta"   	  => "estado_mercancia",
        "archivo"   	  => "adicionar",
        "requiere_item"   => "0",
        "tabla_principal" => "estado_mercancia",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "CONSESME",
        "padre"     	  => "GESTESME",
        "id_modulo" 	  => "INVENTARIO",
        "visible"   	  => "0",
        "orden"     	  => "0010",
        "carpeta"   	  => "estado_mercancia",
        "archivo"   	  => "consultar",
        "requiere_item"   => "1",
        "tabla_principal" => "estado_mercancia",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "MODIESME",
        "padre"     	  => "GESTESME",
        "id_modulo" 	  => "INVENTARIO",
        "visible"   	  => "0",
        "orden"     	  => "0015",
        "carpeta"   	  => "estado_mercancia",
        "archivo"   	  => "modificar",
        "requiere_item"   => "1",
        "tabla_principal" => "estado_mercancia",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "ELIMESME",
        "padre"     	  => "GESTESME",
        "id_modulo" 	  => "INVENTARIO",
        "visible"   	  => "0",
        "orden"     	  => "0020",
        "carpeta"   	  => "estado_mercancia",
        "archivo"   	  => "eliminar",
        "requiere_item"   => "1",
        "tabla_principal" => "estado_mercancia",
        "tipo_enlace"     => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_estado_mercancia AS
        SELECT 	codigo AS id,
                codigo AS CODIGO,
                descripcion AS DESCRIPCION
        FROM job_estado_mercancia;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_estado_mercancia AS
        SELECT 	codigo AS id,
                codigo, 
                descripcion 
        FROM job_estado_mercancia;"
    )
);
/***
	CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_estado_mercancia AS
	SELECT 	codigo AS id,
			codigo AS CODIGO,
			descripcion AS DESCRIPCION
	FROM job_estado_mercancia;

	CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_estado_mercancia AS
	SELECT 	codigo AS id,
			codigo, 
			descripcion 
	FROM job_estado_mercancia;

***/

?>
			

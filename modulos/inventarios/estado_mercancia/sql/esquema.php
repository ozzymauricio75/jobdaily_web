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
$tablas["estado_mercancia"] = array(
    "codigo"          => "SMALLINT(3) UNSIGNED ZEROFILL AUTO_INCREMENT NOT NULL COMMENT 'C�digo interno de la base de datos'",
    "descripcion" => "VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'Descripci�n de la marca'",
);

// Definici�n de llaves primarias
$llavesPrimarias["estado_mercancia"] = "codigo";

// Definici�n de campos �nicos
$llavesUnicas["estado_mercancia"] = array(
    "descripcion"
);

// Inserci�n de datos iniciales***/
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
			

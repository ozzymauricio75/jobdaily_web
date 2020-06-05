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
$tablas["cargos"] = array(
    "codigo"  => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo interno asignado por el usuario'",
    "nombre"  => "VARCHAR(50) NOT NULL COMMENT 'Nombre del cargo'",
    "interno" => "ENUM('0','1') NOT NULL DEFAULT '1' COMMENT 'Cargo interno 0->No 1->Si'"
);

// Definición de llaves primarias
$llavesPrimarias["cargos"]   = "codigo";

// Definición de las llaves unicas
$llavesUnicas["cargos"] = array(
    "nombre"
);

// Inserción de datos iniciales
$registros["cargos"] = array(
    array(
		"codigo"  => "0",
		"nombre"  => "",
		"interno" => "0"
    )
);

// Inserción de datos iniciales
$registros["componentes"] = array(
    array(
        "id"        	  => "GESTCAPR",
        "padre"     	  => "SUBMDCPV",
        "id_modulo" 	  => "PROVEEDORES",
        "orden"     	  => "90",
        "visible"   	  => "1",
        "carpeta"   	  => "cargos",
        "archivo"   	  => "menu",
        "requiere_item"   => "1",
        "tabla_principal" => "cargos",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "ADICCAPR",
        "padre"     	  => "GESTCAPR",
        "id_modulo" 	  => "PROVEEDORES",
        "visible"   	  => "0",
        "orden"     	  => "10",
        "carpeta"   	  => "cargos",
        "archivo"   	  => "adicionar",
        "requiere_item"   => "0",
        "tabla_principal" => "cargos",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "CONSCAPR",
        "padre"     	  => "GESTCAPR",
        "id_modulo" 	  => "PROVEEDORES",
        "visible"   	  => "0",
        "orden"     	  => "20",
        "carpeta"   	  => "cargos",
        "archivo"   	  => "consultar",
        "requiere_item"   => "1",
        "tabla_principal" => "cargos",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "MODICAPR",
        "padre"     	  => "GESTCAPR",
        "id_modulo" 	  => "PROVEEDORES",
        "visible"   	  => "0",
        "orden"     	  => "30",
        "carpeta"   	  => "cargos",
        "archivo"   	  => "modificar",
        "requiere_item"   => "1",
        "tabla_principal" => "cargos",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "ELIMCAPR",
        "padre"     	  => "GESTCAPR",
        "id_modulo" 	  => "PROVEEDORES",
        "visible"   	  => "0",
        "orden"    	 	  => "40",
        "carpeta"   	  => "cargos",
        "archivo"   	  => "eliminar",
        "requiere_item"   => "1",
        "tabla_principal" => "cargos",
        "tipo_enlace"     => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_cargos_externos AS
        SELECT codigo AS id,
            codigo AS CODIGO,
            nombre AS NOMBRE
        FROM
            job_cargos
        WHERE
            interno = '0' 
            AND codigo != 0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_cargos_externos AS
        SELECT codigo AS id,
            codigo AS codigo,
            nombre AS nombre
        FROM
            job_cargos
        WHERE
            interno = '0' 
            AND codigo != 0;"
    )
);
/***
	CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_cargos_externos AS
	SELECT codigo AS id,
        codigo AS CODIGO,
        nombre AS NOMBRE
	FROM
        job_cargos
	WHERE
        interno = '0' 
        AND codigo != 0;

	CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_cargos_externos AS
	SELECT codigo AS id,
        codigo AS codigo,
        nombre AS nombre
	FROM
        job_cargos
	WHERE
        interno = '0' 
        AND codigo != 0;

***/
?>

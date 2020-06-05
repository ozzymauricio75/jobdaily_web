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

//  Definición de tablas
$tablas["tasas"] = array(
    "codigo"      => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código para uso interno de la empresa'",
    "descripcion" => "VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'Detalle que describe de la tasa'"
);

//  Definición de llaves primarias
$llavesPrimarias["tasas"] = "codigo";

 //  Definición de campos únicos
$llavesUnicas["tasas"] = array(
    "descripcion"
);

//  Inserción de datos iniciales
$registros["tasas"] = array(
    array(
		"codigo"      => "0",
		"descripcion" => ""
    )
);

//  Inserción de datos iniciales
$registros["componentes"] = array(
    array(
        "id"        	  => "GESTTASA",
        "padre"     	  => "SUBMTASA",
        "id_modulo" 	  => "CONTABILIDAD",
        "orden"     	  => "0005",
        "visible"   	  => "1",
        "carpeta"   	  => "tasas",
        "archivo"   	  => "menu",
        "requiere_item"   => "1",
        "tabla_principal" => "tasas",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "ADICTASA",
        "padre"     	  => "GESTTASA",
        "id_modulo"  	  => "CONTABILIDAD",
        "visible"   	  => "0",
        "orden"     	  => "0005",
        "carpeta"   	  => "tasas",
        "archivo"   	  => "adicionar",
        "requiere_item"   => "0",
        "tabla_principal" => "tasas",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "CONSTASA",
        "padre"     	  => "GESTTASA",
        "id_modulo" 	  => "CONTABILIDAD",
        "visible"   	  => "0",
        "orden"     	  => "0010",
        "carpeta"   	  => "tasas",
        "archivo"  	      => "consultar",
        "requiere_item"   => "1",
        "tabla_principal" => "tasas",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "MODITASA",
        "padre"     	  => "GESTTASA",
        "id_modulo" 	  => "CONTABILIDAD",
        "visible"   	  => "0",
        "orden"     	  => "0015",
        "carpeta"   	  => "tasas",
        "archivo"   	  => "modificar",
        "requiere_item"   => "1",
        "tabla_principal" => "tasas",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "ELIMTASA",
        "padre"     	  => "GESTTASA",
        "id_modulo" 	  => "CONTABILIDAD",
        "visible"   	  => "0",
        "orden"     	  => "0020",
        "carpeta"   	  => "tasas",
        "archivo"   	  => "eliminar",
        "requiere_item"   => "1",
        "tabla_principal" => "tasas",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "EXPOTASA",
        "padre"     	  => "GESTTASA",
        "id_modulo" 	  => "CONTABILIDAD",
        "visible"   	  => "0",
        "orden"     	  => "0030",
        "carpeta"   	  => "tasas",
        "archivo"   	  => "listar",
        "requiere_item"   => "0",
        "tabla_principal" => "tasas",
        "tipo_enlace"     => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_tasas AS 
        SELECT codigo AS id,
        codigo AS CODIGO,
        descripcion AS DESCRIPCION
        FROM job_tasas
        WHERE codigo != 0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_tasas AS 
        SELECT codigo AS id, 
        codigo AS codigo,
        descripcion AS descripcion
        FROM job_tasas
        WHERE codigo != 0;"
    )
);
/*** 
	CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_tasas AS 
	SELECT codigo AS id,
	codigo AS CODIGO,
	descripcion AS DESCRIPCION
	FROM job_tasas
	WHERE codigo != 0;

	CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_tasas AS 
	SELECT codigo AS id, 
	codigo AS codigo,
	descripcion AS descripcion
	FROM job_tasas
	WHERE codigo != 0;
***/

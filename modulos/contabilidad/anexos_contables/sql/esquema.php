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
$tablas["anexos_contables"] = array(
    "codigo"       => "VARCHAR(3) NOT NULL COMMENT 'Código del anexo que permite dividir las cuentas'",
    "descripcion"  => "VARCHAR(255) NOT NULL COMMENT 'Detalle que describe el anexo contable'"
);

// Definición de llaves primarias
$llavesPrimarias["anexos_contables"] = "codigo";

 // Definición de campos únicos
$llavesUnicas["anexos_contables"] = array(
	"codigo",
    "descripcion"
);

// Inserción de datos iniciales sucursales
$registros["anexos_contables"] = array(
	array(
		"codigo"      => "",
		"descripcion" => ""
	)
);

// Inserción de datos iniciales
$registros["componentes"] = array(
    array(
        "id"        	  => "GESTANCO",
        "padre"     	  => "SUBMINCO",
        "id_modulo" 	  => "CONTABILIDAD",
        "orden"     	  => "0010",
        "visible"   	  => "1",
        "carpeta"   	  => "anexos_contables",
        "archivo"   	  => "menu",
        "requiere_item"   => "1",
        "tabla_principal" => "anexos_contables",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "ADICANCO",
        "padre"     	  => "GESTANCO",
        "id_modulo" 	  => "CONTABILIDAD",
        "visible"   	  => "0",
        "orden"     	  => "0005",
        "carpeta"  	 	  => "anexos_contables",
        "archivo"   	  => "adicionar",
        "requiere_item"   => "0",
        "tabla_principal" => "anexos_contables",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "CONSANCO",
        "padre"     	  => "GESTANCO",
        "id_modulo" 	  => "CONTABILIDAD",
        "visible"   	  => "0",
        "orden"     	  => "0010",
        "carpeta"   	  => "anexos_contables",
        "archivo"   	  => "consultar",
        "requiere_item"   => "1",
        "tabla_principal" => "anexos_contables",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "MODIANCO",
        "padre"     	  => "GESTANCO",
        "id_modulo" 	  => "CONTABILIDAD",
        "visible"   	  => "0",
        "orden"     	  => "0015",
        "carpeta"   	  => "anexos_contables",
        "archivo"   	  => "modificar",
        "requiere_item"   => "1",
        "tabla_principal" => "anexos_contables",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "ELIMANCO",
        "padre"     	  => "GESTANCO",
        "id_modulo" 	  => "CONTABILIDAD",
        "visible"   	  => "0",
        "orden"     	  => "0020",
        "carpeta"   	  => "anexos_contables",
        "archivo"   	  => "eliminar",
        "requiere_item"   => "1",
        "tabla_principal" => "anexos_contables",
        "tipo_enlace"     => "1"
    )
);
$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_anexos_contables AS 
        SELECT 	codigo AS id,
                codigo AS CODIGO_ANEXO,
                descripcion AS DESCRIPCION 
        FROM 	job_anexos_contables
        WHERE 	codigo != '';"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_anexos_contables AS 
        SELECT 	codigo AS id,
                codigo AS codigo_anexo,
                descripcion AS descripcion 
        FROM 	job_anexos_contables
        WHERE 	codigo != '';"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_anexos_contables AS
        SELECT 	codigo AS id,
                CONCAT(descripcion,'|',codigo)AS descripcion
        FROM 	job_anexos_contables
        WHERE 	codigo != '';"
    )
);
/***
    DROP TABLE IF EXISTS job_menu_anexos_contables;
    DROP TABLE IF EXISTS job_buscador_anexos_contables;
    DROP TABLE IF EXISTS job_seleccion_anexos_contables;
 
	CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_anexos_contables AS 
	SELECT 	codigo AS id,
			codigo AS CODIGO_ANEXO,
			descripcion AS DESCRIPCION 
	FROM 	job_anexos_contables
	WHERE 	codigo != '';

	CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_anexos_contables AS 
	SELECT 	codigo AS id,
			codigo AS codigo_anexo,
			descripcion AS descripcion 
	FROM 	job_anexos_contables
	WHERE 	codigo != '';
	 
	CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_anexos_contables AS
	SELECT 	codigo AS id,
			CONCAT(descripcion,'|',codigo)AS descripcion
	FROM 	job_anexos_contables
	WHERE 	codigo != '';

***/
?>

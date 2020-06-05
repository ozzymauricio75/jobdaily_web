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
$tablas["anexos_contables"] = array(
    "codigo"       => "VARCHAR(3) NOT NULL COMMENT 'C�digo del anexo que permite dividir las cuentas'",
    "descripcion"  => "VARCHAR(255) NOT NULL COMMENT 'Detalle que describe el anexo contable'"
);

// Definici�n de llaves primarias
$llavesPrimarias["anexos_contables"] = "codigo";

 // Definici�n de campos �nicos
$llavesUnicas["anexos_contables"] = array(
	"codigo",
    "descripcion"
);

// Inserci�n de datos iniciales sucursales
$registros["anexos_contables"] = array(
	array(
		"codigo"      => "",
		"descripcion" => ""
	)
);

// Inserci�n de datos iniciales
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

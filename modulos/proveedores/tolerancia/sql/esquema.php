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
* de APTITUD PARA UN PROP�SITO DETERMINADO. Consulte los detalles de
* la Licencia P�blica General GNU para obtener una informaci�n m�s
* detallada.
*
* Deber�a haber recibido una copia de la Licencia P�blica General GNU
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
**/
// Eliminar la tabla y crearla de nuevo cada vez que se ejecute el script de creaci�n
$borrarSiempre = false;

// Definici�n de tablas
$tablas["tolerancia"] = array(
    "codigo"     => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo con el cual se identifica la tolerancia en la orden de compra'",
    "porcentaje" => "DECIMAL(7,2) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Porcentaje permitido para la orden de compra'"
);

// Definici�n de llaves primarias
$llavesPrimarias["tolerancia"]   = "codigo";

// Definici�n de campos �nicos
$llavesUnicas["tolerancia"]   = array(
    "porcentaje"
);

// Inserci�n de id=0
$registros["tolerancia"]   = array(
    array(
        "codigo"     => "0",
        "porcentaje" => ""
    )
);

// Inserci�n de datos iniciales
$registros["componentes"]   = array(
    array(
        "id"        	  => "GESTTOLE",
        "padre"    	      => "SUBMDCPV",
        "id_modulo"	      => "PROVEEDORES",
        "orden"     	  => "0050",
        "carpeta"   	  => "tolerancia",
        "archivo"   	  => "menu",
        "requiere_item"   => "0",
        "tabla_principal" => "tolerancia",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "ADICTOLE",
        "padre"     	  => "GESTTOLE",
        "id_modulo" 	  => "PROVEEDORES",
        "visible"   	  => "0",
        "orden"     	  => "10",
        "carpeta"   	  => "tolerancia",
        "archivo"   	  => "adicionar",
        "requiere_item"   => "0",
        "tabla_principal" => "tolerancia",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "CONSTOLE",
        "padre"     	  => "GESTTOLE",
        "id_modulo" 	  => "PROVEEDORES",
        "visible"   	  => "0",
        "orden"     	  => "20",
        "carpeta"   	  => "tolerancia",
        "archivo"   	  => "consultar",
        "requiere_item"   => "1",
        "tabla_principal" => "tolerancia",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "MODITOLE",
        "padre"     	  => "GESTTOLE",
        "id_modulo" 	  => "PROVEEDORES",
        "visible"   	  => "0",
        "orden"     	  => "30",
        "carpeta"   	  => "tolerancia",
        "archivo"   	  => "modificar",
        "requiere_item"   => "1",
        "tabla_principal" => "tolerancia",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "ELIMTOLE",
        "padre"     	  => "GESTTOLE",
        "id_modulo" 	  => "PROVEEDORES",
        "visible"   	  => "0",
        "orden"     	  => "40",
        "carpeta"   	  => "tolerancia",
        "archivo"   	  => "eliminar",
        "requiere_item"   => "1",
        "tabla_principal" => "tolerancia",
        "tipo_enlace"     => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_tolerancia AS SELECT 
            codigo AS id, 
            codigo AS CODIGO, 
            porcentaje AS PORCENTAJE 
        FROM
            job_tolerancia
        WHERE
            codigo > 0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_tolerancia AS SELECT 
            codigo AS id,
            codigo, 
            porcentaje 
        FROM 
            job_tolerancia
        WHERE
            codigo > 0;"
    )
);

/*** Sentencias para la creaci�n de las vistas requeridas

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_servicios AS SELECT 
        codigo AS id, 
        codigo AS CODIGO, 
        descripcion AS DESCRIPCION 
    FROM
        job_servicios
    WHERE
        codigo > 0;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_servicios AS SELECT 
        codigo AS id,
        codigo, 
        descripcion 
    FROM 
        job_servicios
    WHERE
        codigo > 0;

***/

?>

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
$tablas["servicios"]   = array(
    "codigo"      => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo con el cual se identifica el tipo de servicio'",
    "descripcion" => "CHAR(30) NOT NULL COMMENT 'Descricpion que identifica el servicio'"
);

// Definici�n de llaves primarias
$llavesPrimarias["servicios"]   = "codigo";

// Definici�n de campos �nicos
$llavesUnicas["servicios"]   = array(
    "descripcion"
);

// Inserci�n de id=0
$registros["servicios"]   = array(
    array(
        "codigo"      => "0",
        "descripcion" => ""
    )
);

// Inserci�n de datos iniciales
$registros["componentes"]   = array(
    array(
        "id"        	  => "GESTSERV",
        "padre"    	      => "SUBMDCPV",
        "id_modulo"	      => "PROVEEDORES",
        "orden"     	  => "0040",
        "carpeta"   	  => "servicios",
        "archivo"   	  => "menu",
        "requiere_item"   => "0",
        "tabla_principal" => "servicios",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "ADICSERV",
        "padre"     	  => "GESTSERV",
        "id_modulo" 	  => "PROVEEDORES",
        "visible"   	  => "0",
        "orden"     	  => "10",
        "carpeta"   	  => "servicios",
        "archivo"   	  => "adicionar",
        "requiere_item"   => "0",
        "tabla_principal" => "servicios",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "CONSSERV",
        "padre"     	  => "GESTSERV",
        "id_modulo" 	  => "PROVEEDORES",
        "visible"   	  => "0",
        "orden"     	  => "20",
        "carpeta"   	  => "servicios",
        "archivo"   	  => "consultar",
        "requiere_item"   => "1",
        "tabla_principal" => "servicios",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "MODISERV",
        "padre"     	  => "GESTSERV",
        "id_modulo" 	  => "PROVEEDORES",
        "visible"   	  => "0",
        "orden"     	  => "30",
        "carpeta"   	  => "servicios",
        "archivo"   	  => "modificar",
        "requiere_item"   => "1",
        "tabla_principal" => "servicios",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "ELIMSERV",
        "padre"     	  => "GESTSERV",
        "id_modulo" 	  => "PROVEEDORES",
        "visible"   	  => "0",
        "orden"     	  => "40",
        "carpeta"   	  => "servicios",
        "archivo"   	  => "eliminar",
        "requiere_item"   => "1",
        "tabla_principal" => "servicios",
        "tipo_enlace"     => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_servicios AS SELECT 
            codigo AS id, 
            codigo AS CODIGO, 
            descripcion AS DESCRIPCION 
        FROM
            job_servicios
        WHERE
            codigo > 0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_servicios AS SELECT 
            codigo AS id,
            codigo, 
            descripcion 
        FROM 
            job_servicios
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

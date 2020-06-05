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
$tablas["grupos"] = array(
    "codigo"      => "SMALLINT(4) UNSIGNED ZEROFILL NULL COMMENT 'C�digo utilizado por la empresa'",
    "descripcion" => "VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'Descripcin del grupo'",
    "orden"       => "INT(5) NOT NULL COMMENT 'Orden asignado por el usuario'"
);

// Definici�n de llaves primarias
$llavesPrimarias["grupos"] = "codigo";

// Definici�n de campos �nicos
$llavesUnicas["grupos"] = array(
    "codigo"
);

// Inserci�n de datos iniciales***/
$registros["grupos"] = array(
    array(
		"codigo"      => "0",
		"descripcion" => "",
        "orden"       => "0"
    )
);

// Inserci�n de datos iniciales***/
$registros["componentes"] = array(
    array(
        "id"        	  => "GESTGRUP",
        "padre"     	  => "SUBMDCIN",
        "id_modulo" 	  => "INVENTARIO",
        "visible"   	  => "1",
        "orden"     	  => "0030",
        "carpeta"   	  => "grupos",
        "archivo"   	  => "menu",
        "requiere_item"   => "0",
        "tabla_principal" => "grupos",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "ADICGRUP",
        "padre"     	  => "GESTGRUP",
        "id_modulo" 	  => "INVENTARIO",
        "visible"   	  => "0",
        "orden"     	  => "0010",
        "carpeta"   	  => "grupos",
        "archivo"   	  => "adicionar",
        "requiere_item"   => "0",
        "tabla_principal" => "grupos",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "CONSGRUP",
        "padre"     	  => "GESTGRUP",
        "id_modulo" 	  => "INVENTARIO",
        "visible"   	  => "0",
        "orden"     	  => "0020",
        "carpeta"   	  => "grupos",
        "archivo"   	  => "consultar",
        "requiere_item"   => "1",
        "tabla_principal" => "grupos",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "MODIGRUP",
        "padre"     	  => "GESTGRUP",
        "id_modulo" 	  => "INVENTARIO",
        "visible"   	  => "0",
        "orden"     	  => "0030",
        "carpeta"   	  => "grupos",
        "archivo"   	  => "modificar",
        "requiere_item"   => "1",
        "tabla_principal" => "grupos",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "ELIMGRUP",
        "padre"     	  => "GESTGRUP",
        "id_modulo" 	  => "INVENTARIO",
        "visible"   	  => "0",
        "orden"     	  => "0040",
        "carpeta"   	  => "grupos",
        "archivo"   	  => "eliminar",
        "requiere_item"   => "1",
        "tabla_principal" => "grupos",
        "tipo_enlace"     => "1"
    )
);
$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_grupos AS
        SELECT job_grupos.codigo AS id,
        job_grupos.codigo AS CODIGO,
        job_grupos.descripcion AS DESCRIPCION,
        job_grupos.orden AS ORDEN
        FROM job_grupos
        WHERE codigo != 0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_grupos AS
        SELECT codigo AS id, codigo, descripcion, orden
        FROM job_grupos
        WHERE codigo != 0;"
    )
);
/***
     CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_grupos AS
     SELECT job_grupos.codigo AS id,
     job_grupos.codigo AS CODIGO,
     job_grupos.descripcion AS DESCRIPCION,
     job_grupos.orden AS ORDEN
     FROM job_grupos
     WHERE codigo != 0;

     CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_grupos AS
     SELECT codigo AS id, codigo, descripcion, orden
     FROM job_grupos
     WHERE codigo != 0;
***/

?>

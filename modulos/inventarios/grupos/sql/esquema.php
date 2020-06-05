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
$tablas["grupos"] = array(
    "codigo"      => "SMALLINT(4) UNSIGNED ZEROFILL NULL COMMENT 'Código utilizado por la empresa'",
    "descripcion" => "VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'Descripcin del grupo'",
    "orden"       => "INT(5) NOT NULL COMMENT 'Orden asignado por el usuario'"
);

// Definición de llaves primarias
$llavesPrimarias["grupos"] = "codigo";

// Definición de campos únicos
$llavesUnicas["grupos"] = array(
    "codigo"
);

// Inserción de datos iniciales***/
$registros["grupos"] = array(
    array(
		"codigo"      => "0",
		"descripcion" => "",
        "orden"       => "0"
    )
);

// Inserción de datos iniciales***/
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

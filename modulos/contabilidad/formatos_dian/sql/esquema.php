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
$tablas["formatos_dian"] = array(
    "codigo"      => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código asignado por la DIAN'",
    "descripcion" => "VARCHAR(255) NOT NULL COMMENT 'Detalle que describe el formato DIAN'"
);

// Definición de llaves primarias
$llavesPrimarias["formatos_dian"] = "codigo";

 // Definición de campos Únicos
$llavesUnicas["formatos_dian"] = array(
    "descripcion"
);

// Inserción de datos iniciales id=0***/
$registros["formatos_dian"] = array(
    array(
        "codigo" => 0,
        "descripcion" => ""
    )
);

// Inserción de datos iniciales
$registros["componentes"] = array(
    array(
        "id"              => "GESTFODI",
        "padre"           => "SUBMINTR",
        "id_modulo"       => "CONTABILIDAD",
        "orden"           => "0020",
        "visible"         => "1",
        "carpeta"         => "formatos_dian",
        "archivo"         => "menu",
        "requiere_item"   => "1",
        "tabla_principal" => "formatos_dian",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "ADICFODI",
        "padre"           => "GESTFODI",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "0",
        "orden"           => "0005",
        "carpeta"         => "formatos_dian",
        "archivo"         => "adicionar",
        "requiere_item"   => "0",
        "tabla_principal" => "formatos_dian",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "CONSFODI",
        "padre"           => "GESTFODI",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "0",
        "orden"           => "0010",
        "carpeta"         => "formatos_dian",
        "archivo"         => "consultar",
        "requiere_item"   => "1",
        "tabla_principal" => "formatos_dian",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "MODIFODI",
        "padre"           => "GESTFODI",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "0",
        "orden"           => "0015",
        "carpeta"         => "formatos_dian",
        "archivo"         => "modificar",
        "requiere_item"   => "1",
        "tabla_principal" => "formatos_dian",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "ELIMFODI",
        "padre"           => "GESTFODI",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "0",
        "orden"           => "0020",
        "carpeta"         => "formatos_dian",
        "archivo"         => "eliminar",
        "requiere_item"   => "1",
        "tabla_principal" => "formatos_dian",
        "tipo_enlace"     => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_formatos_dian AS 
        SELECT codigo AS id,
        codigo AS CODIGO_DIAN,
        descripcion AS DESCRIPCION 
        FROM job_formatos_dian
        WHERE codigo>0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_formatos_dian AS 
        SELECT codigo AS id,
        codigo AS codigo,
        descripcion AS descripcion 
        FROM job_formatos_dian
        WHERE codigo>0;"
    )
);

/***
	CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_formatos_dian AS 
	SELECT codigo AS id,
	codigo AS CODIGO_DIAN,
	descripcion AS DESCRIPCION 
	FROM job_formatos_dian
    WHERE codigo>0;

	CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_formatos_dian AS 
	SELECT codigo AS id,
	codigo AS codigo,
	descripcion AS descripcion 
	FROM job_formatos_dian
    WHERE codigo>0;
***/
?>

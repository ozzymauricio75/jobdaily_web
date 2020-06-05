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
// Eliminar la tabla y crearla de nuevo cada vez que se ejecute el script de creación
$borrarSiempre = false;

// Definición de tablas
$tablas ["tipos_bodegas"] = array(
    "codigo"      => "SMALLINT(3) UNSIGNED ZEROFILL  NOT NULL COMMENT 'Codigo del tipo de bodega'",
    "nombre"      => "VARCHAR(60) NOT NULL COMMENT 'Nombre que identifica el tipo de bodega'",
    "descripcion" => "VARCHAR(60) NOT NULL COMMENT 'Nombre que describe el tipo de bodega'"
);

// Definición de llaves primarias
$llavesPrimarias["tipos_bodegas"] = "codigo";

// Definición de campos únicos
$llavesUnicas["tipos_bodegas"] = array(
    "nombre"
);

// Inserción de datos iniciales
$registros ["tipos_bodegas"] = array(
	array(
		"codigo"      => "0",
		"nombre"      => "",
		"descripcion" => ""
    )
);

$registros["componentes"] = array(
    array(
        "id"           => "GESTTIBO",
        "padre"        => "SUBMDCAD",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "1",
        "orden"        => "200",
        "carpeta"      => "tipos_bodegas",
        "archivo"      => "menu",
        "global"       => "0",
        "requiere_item" => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "ADICTIBO",
        "padre"        => "GESTTIBO",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "0",
        "orden"        => "20",
        "carpeta"      => "tipos_bodegas",
        "archivo"      => "adicionar",
        "global"       => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "CONSTIBO",
        "padre"        => "GESTTIBO",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "0",
        "orden"        => "30",
        "carpeta"      => "tipos_bodegas",
        "archivo"      => "consultar",
        "global"       => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "MODITIBO",
        "padre"        => "GESTTIBO",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "0",
        "orden"        => "40",
        "carpeta"      => "tipos_bodegas",
        "archivo"      => "modificar",
        "global"       => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "ELIMTIBO",
        "padre"        => "GESTTIBO",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "0",
        "orden"        => "50",
        "carpeta"      => "tipos_bodegas",
        "archivo"      => "eliminar",
        "global"       => "0",
        "tipo_enlace"  => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_tipos_bodegas AS
        SELECT job_tipos_bodegas.codigo AS id,
        job_tipos_bodegas.nombre AS NOMBRE,
        job_tipos_bodegas.descripcion AS DESCRIPCION
        FROM job_tipos_bodegas
        WHERE job_tipos_bodegas.codigo != 0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_tipos_bodegas AS
        SELECT job_tipos_bodegas.codigo AS id,
        job_tipos_bodegas.nombre AS nombre,
        job_tipos_bodegas.descripcion AS descripcion
        FROM job_tipos_bodegas
        WHERE job_tipos_bodegas.codigo != 0;"
    )
);

/***
    DROP TABLES IF EXISTS job_menu_tipos_bodegas;
    DROP TABLES IF EXISTS job_buscador_tipos_bodegas;
    
    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_tipos_bodegas AS
	SELECT job_tipos_bodegas.codigo AS id,
	job_tipos_bodegas.nombre AS NOMBRE,
	job_tipos_bodegas.descripcion AS DESCRIPCION
	FROM job_tipos_bodegas
	WHERE job_tipos_bodegas.codigo != 0;

	CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_tipos_bodegas AS
    SELECT job_tipos_bodegas.codigo AS id,
	job_tipos_bodegas.nombre AS nombre,
	job_tipos_bodegas.descripcion AS descripcion
	FROM job_tipos_bodegas
	WHERE job_tipos_bodegas.codigo != 0;
***/
?>

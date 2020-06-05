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
$tablas ["secciones"] = array(
    "codigo_sucursal"  => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código interno de la sucursal'",
    "codigo_bodega"    => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código interno de la bodega'",
    "codigo"           => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código asignado usuario'",
    "nombre"           => "VARCHAR(60) NOT NULL COMMENT 'Nombre que identifica la seccion'",
    "descripcion"      => "VARCHAR(60) NOT NULL COMMENT 'Nombre que describe la seccion'"
);

// Definición de llaves primarias
$llavesPrimarias["secciones"] = "codigo_sucursal,codigo_bodega,codigo";

//  Definición de llaves foráneas
$llavesForaneas["secciones"] = array(
    array(
        // Nombre de la llave
        "secciones_bodegas",
        // Nombre del campo clave de la tabla local
        "codigo_bodega",
        // Nombre de la tabla relacionada
        "bodegas",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    ),
     array(
        // Nombre de la llave
        "secciones_sucursal",
        // Nombre del campo clave de la tabla local
        "codigo_sucursal",
        // Nombre de la tabla relacionada
        "sucursales",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    )
);

$registros["secciones"]= array(

array(
        "codigo_sucursal" => "0",
        "codigo_bodega"   => "0",
        "codigo"          => "0",
        "nombre"          => "",
        "descripcion"     => ""
     )
);

$registros["componentes"] = array(
    array(
        "id"           => "GESTSECB",
        "padre"        => "SUBMESTC",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "1",
        "orden"        => "400",
        "carpeta"      => "secciones",
        "archivo"      => "menu",
        "global"       => "0",
        "requiere_item" => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "ADICSECB",
        "padre"        => "GESTSECB",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "0",
        "orden"        => "20",
        "carpeta"      => "secciones",
        "archivo"      => "adicionar",
        "global"       => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "CONSSECB",
        "padre"        => "GESTSECB",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "0",
        "orden"        => "30",
        "carpeta"      => "secciones",
        "archivo"      => "consultar",
        "global"       => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "MODISECB",
        "padre"        => "GESTSECB",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "0",
        "orden"        => "40",
        "carpeta"      => "secciones",
        "archivo"      => "modificar",
        "global"       => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "ELIMSECB",
        "padre"        => "GESTSECB",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "0",
        "orden"        => "50",
        "carpeta"      => "secciones",
        "archivo"      => "eliminar",
        "global"       => "0",
        "tipo_enlace"  => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_secciones AS
        SELECT
            CONCAT(job_secciones.codigo_sucursal,\"|\",job_secciones.codigo_bodega,\"|\",job_secciones.codigo) AS id,

            job_secciones.codigo AS CODIGO,
            job_secciones.nombre AS NOMBRE,
            job_secciones.descripcion AS DESCRIPCION,
            job_bodegas.nombre AS BODEGA
        FROM
            job_secciones, job_bodegas , job_sucursales
        WHERE
            job_secciones.codigo_bodega = job_bodegas.codigo
            AND job_secciones.codigo_sucursal = job_bodegas.codigo_sucursal
            AND job_secciones.codigo_sucursal = job_sucursales.codigo
            AND job_secciones.codigo != 0
            ORDER BY NOMBRE ASC;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_secciones AS
        SELECT
            CONCAT(job_secciones.codigo_sucursal,\"|\",job_secciones.codigo_bodega,\"|\",job_secciones.codigo) AS id,
            job_secciones.codigo AS codigo,
            job_secciones.nombre AS nombre,
            job_secciones.descripcion AS descripcion,
            job_secciones.codigo_sucursal AS codigo_sucursal,
            job_sucursales.nombre AS sucursal,
            job_secciones.codigo_bodega AS codigo_bodega,
            job_bodegas.nombre AS bodega
        FROM
            job_secciones, job_bodegas, job_sucursales
        WHERE
            job_secciones.codigo_bodega = job_bodegas.codigo
            AND job_secciones.codigo_sucursal = job_bodegas.codigo_sucursal
            AND job_secciones.codigo_sucursal = job_sucursales.codigo
            AND job_secciones.codigo != 0
            ORDER BY NOMBRE ASC;"
    )
);
/*
    DROP TABLE IF EXISTS job_menu_secciones;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_secciones AS
    SELECT
	    CONCAT(job_secciones.codigo_sucursal,"|",job_secciones.codigo_bodega,"|",job_secciones.codigo) AS id,

        job_secciones.codigo AS CODIGO,
	    job_secciones.nombre AS NOMBRE,
	    job_secciones.descripcion AS DESCRIPCION,
	    job_bodegas.nombre AS BODEGA

    FROM

   	    job_secciones, job_bodegas , job_sucursales

    WHERE

	    job_secciones.codigo_bodega = job_bodegas.codigo
        AND job_secciones.codigo_sucursal = job_bodegas.codigo_sucursal
        AND job_secciones.codigo_sucursal = job_sucursales.codigo
    	AND job_secciones.codigo != 0
        ORDER BY NOMBRE ASC;



    DROP TABLE IF EXISTS job_buscador_secciones;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_secciones AS
    SELECT

		CONCAT(job_secciones.codigo_sucursal,"|",job_secciones.codigo_bodega,"|",job_secciones.codigo) AS id,
		job_secciones.codigo AS codigo,
		job_secciones.nombre AS nombre,
		job_secciones.descripcion AS descripcion,
		job_secciones.codigo_sucursal AS codigo_sucursal,
        job_sucursales.nombre AS sucursal,
        job_secciones.codigo_bodega AS codigo_bodega,
		job_bodegas.nombre AS bodega

    FROM
   	    job_secciones, job_bodegas, job_sucursales

    WHERE

	    job_secciones.codigo_bodega = job_bodegas.codigo
        AND job_secciones.codigo_sucursal = job_bodegas.codigo_sucursal
        AND job_secciones.codigo_sucursal = job_sucursales.codigo
    	AND job_secciones.codigo != 0
        ORDER BY NOMBRE ASC;

*/
?>

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
$tablas ["bodegas"] = array(
    "codigo"             => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código interno de la bodega'",
    "codigo_sucursal"    => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código interno de la sucursal'",
    "nombre"             => "VARCHAR(60) NOT NULL COMMENT 'Nombre que identifica la bodega'",
    "descripcion"        => "VARCHAR(60) NOT NULL COMMENT 'Nombre que describe la bodega'",
    "codigo_tipo_bodega" => "SMALLINT(3) UNSIGNED ZEROFILL  NOT NULL COMMENT 'Localizacion donde se encuentra ubicado el articulo'",
    "tipo_inventario"    => "ENUM('1','2','3','4','5','6','7') NOT NULL DEFAULT '1' COMMENT 'Tipo inventario de la bodega 1->Inventario,2->Obsequio,3->Consignación,4->Prestamo a terceros,5->Servicio Tecnico,6->Prestamo Clientes,7->Consignación Clientes'"
);

// Definición de llaves primarias
$llavesPrimarias["bodegas"] = "codigo,codigo_sucursal";


//  Definición de llaves foráneas
$llavesForaneas["bodegas"] = array(
    array(
        // Nombre de la llave
        "bodega_sucursal",
        // Nombre del campo clave de la tabla local
        "codigo_sucursal",
        // Nombre de la tabla relacionada
        "sucursales",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave
        "tipo_bodega",
        // Nombre del campo clave de la tabla local
        "codigo_tipo_bodega",
        // Nombre de la tabla relacionada
        "tipos_bodegas",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    )
);

// Inserción de datos iniciales

$registros["bodegas"] = array(
    array(
        "codigo"              => "0",
        "codigo_sucursal"     => "0",
        "nombre"              => "",
        "descripcion"         => "",
        "codigo_tipo_bodega"  => ""
    )
);

$registros["componentes"] = array(
    array(
        "id"           => "GESTBODE",
        "padre"        => "SUBMESTC",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "1",
        "orden"        => "300",
        "carpeta"      => "bodegas",
        "archivo"      => "menu",
        "global"       => "0",
        "requiere_item" => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "ADICBODE",
        "padre"        => "GESTBODE",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "0",
        "orden"        => "20",
        "carpeta"      => "bodegas",
        "archivo"      => "adicionar",
        "global"       => "0",
        "requiere_item" => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "CONSBODE",
        "padre"        => "GESTBODE",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "0",
        "orden"        => "30",
        "carpeta"      => "bodegas",
        "archivo"      => "consultar",
        "global"       => "0",
        "requiere_item" => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "MODIBODE",
        "padre"        => "GESTBODE",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "0",
        "orden"        => "40",
        "carpeta"      => "bodegas",
        "archivo"      => "modificar",
        "global"       => "0",
        "requiere_item" => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "ELIMBODE",
        "padre"        => "GESTBODE",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "0",
        "orden"        => "50",
        "carpeta"      => "bodegas",
        "archivo"      => "eliminar",
        "global"       => "0",
        "requiere_item" => "0",
        "tipo_enlace"  => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_bodegas AS
        SELECT  CONCAT(job_bodegas.codigo,\"|\",job_bodegas.codigo_sucursal) AS id,
                job_bodegas.codigo AS CODIGO,
                job_bodegas.nombre AS NOMBRE,
                job_bodegas.descripcion AS DESCRIPCION,
                job_sucursales.nombre AS SUCURSAL
        FROM    job_bodegas,
                job_sucursales
        WHERE   job_bodegas.codigo_sucursal = job_sucursales.codigo AND
                job_bodegas.codigo > 0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_bodegas AS
        SELECT  CONCAT(job_bodegas.codigo,\"|\",job_bodegas.codigo_sucursal) AS id,
                job_bodegas.codigo AS codigo,
                job_bodegas.codigo_sucursal AS codigo_sucursal,
                job_bodegas.nombre AS nombre,
                job_bodegas.descripcion AS descripcion,
                job_bodegas.codigo_tipo_bodega AS tipo_bodega,
                job_sucursales.nombre AS sucursal
        FROM    job_bodegas,
                job_sucursales
        WHERE   job_bodegas.codigo_sucursal = job_sucursales.codigo AND
                job_bodegas.codigo > 0;"
    )
);
/***
    DROP TABLE IF EXISTS job_menu_bodegas;
    DROP TABLE IF EXISTS job_buscador_bodegas;
    
	CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_bodegas AS
	SELECT  CONCAT(job_bodegas.codigo,"|",job_bodegas.codigo_sucursal) AS id,
            job_bodegas.codigo AS CODIGO,
            job_bodegas.nombre AS NOMBRE,
            job_bodegas.descripcion AS DESCRIPCION,
            job_sucursales.nombre AS SUCURSAL
	FROM    job_bodegas,
            job_sucursales
    WHERE   job_bodegas.codigo_sucursal = job_sucursales.codigo AND
            job_bodegas.codigo > 0;
    

	CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_bodegas AS
    SELECT  CONCAT(job_bodegas.codigo,"|",job_bodegas.codigo_sucursal) AS id,
            job_bodegas.codigo AS codigo,
            job_bodegas.codigo_sucursal AS codigo_sucursal,
            job_bodegas.nombre AS nombre,
            job_bodegas.descripcion AS descripcion,
            job_bodegas.codigo_tipo_bodega AS tipo_bodega,
            job_sucursales.nombre AS sucursal
    FROM    job_bodegas,
            job_sucursales
    WHERE   job_bodegas.codigo_sucursal = job_sucursales.codigo AND
            job_bodegas.codigo > 0;

***/
?>

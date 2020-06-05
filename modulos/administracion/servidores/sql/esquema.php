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
$borrarSiempre = false;

// Definición de tablas
$tablas["servidores"] = array(
    "id"              => "SMALLINT(3) UNSIGNED ZEROFILL AUTO_INCREMENT NOT NULL COMMENT 'Consecutivo interno de la base de datos'",
    "ip"              => "VARCHAR(15) NOT NULL COMMENT 'Drirección IP de la servidor'",
    "codigo_sucursal" => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo interno de la base de datos'",
    "nombre_netbios"  => "VARCHAR(50) NOT NULL COMMENT 'Nombre NetBIOS'",
    "nombre_tcpip"    => "VARCHAR(50) NOT NULL COMMENT 'NONBRE TCPIP'",
    "descripcion"     => "VARCHAR(50) NULL COMMENT 'Descripción de la servidor'"
);


// Definición de llaves primarias
$llavesPrimarias["servidores"]   = "id";

// Definición de llaves primarias
$llavesUnicas["servidores"]   =  array(
    "ip",
    "nombre_netbios",
    "nombre_tcpip",
    "descripcion"
);

// Definición de llaves foraneas
$llavesForaneas["servidores"]   = array(
    array(
        // Nombre de la llave
        "servidor_sucursal",
        // Nombre del campo en la tabla actual
        "codigo_sucursal",
        // Nombre de la tabla relacionada
        "sucursales",
        // Nombre del campo de la tabla relacionada
        "codigo"
    )
);

$registros["componentes"] = array(
    array(
        "id"           => "GESTSRVD",
        "padre"        => "SUBMDISP",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "1",
        "orden"        => "100",
        "carpeta"      => "servidores",
        "archivo"      => "menu",
        "global"       => "0",
        "requiere_item" => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "ADICSRVD",
        "padre"        => "GESTSRVD",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "0",
        "orden"        => "20",
        "carpeta"      => "servidores",
        "archivo"      => "adicionar",
        "global"       => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "CONSSRVD",
        "padre"        => "GESTSRVD",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "0",
        "orden"        => "30",
        "carpeta"      => "servidores",
        "archivo"      => "consultar",
        "global"       => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "MODISRVD",
        "padre"        => "GESTSRVD",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "0",
        "orden"        => "40",
        "carpeta"      => "servidores",
        "archivo"      => "modificar",
        "global"       => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "ELIMSRVD",
        "padre"        => "GESTSRVD",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "0",
        "orden"        => "50",
        "carpeta"      => "servidores",
        "archivo"      => "eliminar",
        "global"       => "0",
        "tipo_enlace"  => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_servidores AS
        SELECT job_servidores.id AS id,
        job_servidores.ip AS IP,
        job_sucursales.nombre AS SUCURSAL,
        job_servidores.nombre_netbios AS NOMBRE_NETBIOS,
        job_servidores.nombre_tcpip AS NOMBRE_TCPIP
        FROM job_servidores, job_sucursales
        WHERE job_servidores.codigo_sucursal = job_sucursales.codigo;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_servidores AS
        SELECT job_servidores.id AS id,
        job_servidores.ip AS ip,
        job_sucursales.nombre as sucursal,
        job_servidores.nombre_netbios AS nombre_netbios,
        job_servidores.nombre_tcpip AS nombre_tcpip
        FROM job_servidores, job_sucursales
        WHERE job_servidores.codigo_sucursal = job_sucursales.codigo;"
    )
);

/***
Sentencia para la creación de la vista requerida
CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_servidores AS
SELECT job_servidores.id AS id,
job_servidores.ip AS IP,
job_sucursales.nombre AS SUCURSAL,
job_servidores.nombre_netbios AS NOMBRE_NETBIOS,
job_servidores.nombre_tcpip AS NOMBRE_TCPIP
FROM job_servidores, job_sucursales
WHERE job_servidores.codigo_sucursal = job_sucursales.codigo;


CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_servidores AS
SELECT job_servidores.id AS id,
job_servidores.ip AS ip,
job_sucursales.nombre as sucursal,
job_servidores.nombre_netbios AS nombre_netbios,
job_servidores.nombre_tcpip AS nombre_tcpip
FROM job_servidores, job_sucursales
WHERE job_servidores.codigo_sucursal = job_sucursales.codigo;

***/
?>

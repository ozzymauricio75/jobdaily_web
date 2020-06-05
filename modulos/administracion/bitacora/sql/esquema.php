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

$tablas["conexiones"] = array(
    "codigo_sucursal" => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la sucursal'",
    "codigo_usuario"  => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo interno para la base de datos del usuario que realiza la conexión'",
    "fecha"           => "DATETIME NOT NULL COMMENT 'Fecha y hora de la conexión'",
    "ip"              => "VARCHAR(15) NOT NULL COMMENT 'Dirección IP desde la cual se realiza la conexión'",
    "proxy"           => "VARCHAR(15) COMMENT 'Dirección IP del proxy, si lo hay, desde el cual se realiza la conexión'",
    "navegador"       => "VARCHAR(255) COMMENT 'Identificación del navegador'",
    "sistema"         => "VARCHAR(255) COMMENT 'Sistema operativo del cliente'"
);

$tablas["bitacora"] = array(
    "codigo_sucursal_conexion" => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la sucursal'",
    "codigo_usuario_conexion"  => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo interno para la base de datos del usuario que realiza la conexión'",
    "fecha_conexion"           => "DATETIME NOT NULL COMMENT 'Fecha y hora de la conexión'",
    "fecha_operacion"          => "DATETIME NOT NULL COMMENT 'Fecha y hora de la operación'",
    "consecutivo"              => "INT(8) NOT NULL COMMENT 'Consecutivo para registros que se ejecuten en una misma hora'",
    "id_componente_padre"      => "VARCHAR(8) NOT NULL",
    "id_registro"              => "VARCHAR(255) NOT NULL",
    "componente"               => "TEXT NOT NULL COMMENT 'Nombre del componente requerido por el usuario'",
    "consulta"                 => "TEXT NOT NULL COMMENT 'Detalles de la sintáxis SQL de la(s) consulta(s) generada(s) por el componente'",
    "mensaje"                  => "TEXT COMMENT 'Mensaje de error (si existe) devuelto por el motor de bases de datos'"
);

// Definición de llaves primarias
$llavesPrimarias["conexiones"]  = "codigo_sucursal,codigo_usuario,fecha";
$llavesPrimarias["bitacora"]    = "codigo_sucursal_conexion,codigo_usuario_conexion,fecha_conexion,fecha_operacion,consecutivo";

// Definición de llaves foráneas
$llavesForaneas["conexiones"] = array(
    array(
        // Nombre de la llave
        "conexion_usuario",
        // Nombre del campo clave de la tabla local
        "codigo_usuario",
        // Nombre de la tabla relacionada
        "usuarios",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave
        "conexion_sucursal",
        // Nombre del campo clave de la tabla local
        "codigo_sucursal",
        // Nombre de la tabla relacionada
        "sucursales",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    )
);

$llavesForaneas["bitacora"] = array(
    array(
        // Nombre de la llave
        "bitacora_conexion",
        // Nombre del campo clave de la tabla local
        "codigo_sucursal_conexion,codigo_usuario_conexion,fecha_conexion",
        // Nombre de la tabla relacionada
        "conexiones",
        // Nombre del campo clave en la tabla relacionada
        "codigo_sucursal,codigo_usuario,fecha"
    )
);

$registros["componentes"] = array(
    array(
        "id"           => "GESTBITA",
        "padre"        => "SUBMSEGU",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "1",
        "orden"        => "500",
        "carpeta"      => "bitacora",
        "archivo"      => "menu",
        "global"       => "0",
        "requiere_item" => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "CONSBITA",
        "padre"        => "GESTBITA",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "0",
        "orden"        => "10",
        "carpeta"      => "bitacora",
        "archivo"      => "consultar",
        "global"       => "0",
        "requiere_item" => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "LISTBITA",
        "padre"        => "GESTBITA",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "0",
        "orden"        => "10",
        "carpeta"      => "bitacora",
        "archivo"      => "listar",
        "global"       => "0",
        "requiere_item" => "0",
        "tipo_enlace"  => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_conexiones AS
        SELECT
            CONCAT(job_conexiones.codigo_sucursal,\"|\",job_conexiones.codigo_usuario,\"|\",job_conexiones.fecha) AS id,
            job_conexiones.fecha AS id_fecha,
            DATE_FORMAT(job_conexiones.fecha, '%Y/%m/%d') AS FECHA,
            DATE_FORMAT(job_conexiones.fecha, '%T') AS HORA,
            job_usuarios.nombre AS USUARIO,
            job_conexiones.ip AS IP,
            job_conexiones.proxy AS PROXY
        FROM
            job_usuarios,job_sucursales, job_conexiones
        WHERE
            job_conexiones.codigo_usuario = job_usuarios.codigo AND
            job_conexiones.codigo_sucursal = job_sucursales.codigo;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_conexiones AS
            SELECT
            CONCAT(job_conexiones.codigo_sucursal,\"|\",job_conexiones.codigo_usuario,\"|\",job_conexiones.fecha) AS id,
            DATE_FORMAT(job_conexiones.fecha, '%Y/%m/%d') AS fecha,
            DATE_FORMAT(job_conexiones.fecha, '%T') AS hora,
            job_usuarios.nombre AS USUARIO,
            job_conexiones.ip AS IP,
            job_conexiones.proxy AS PROXY
        FROM
            job_usuarios, job_sucursales, job_conexiones
        WHERE
            job_conexiones.codigo_usuario  = job_usuarios.codigo AND
            job_conexiones.codigo_sucursal = job_sucursales.codigo;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_consulta_bitacora AS
        SELECT
            CONCAT(job_bitacora.codigo_sucursal_conexion,\"|\",job_bitacora.codigo_usuario_conexion,\"|\",job_bitacora.fecha_conexion) AS id,
            DATE_FORMAT(fecha_operacion, '%Y/%m/%d') AS FECHA,
            DATE_FORMAT(fecha_operacion, '%T') AS HORA,
            componente AS COMPONENTE,
            consulta AS CONSULTA,
            mensaje AS MENSAJE
        FROM job_bitacora;"
    )
);

/*** Sentencias para la creación de las vistas requeridas

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_conexiones AS
    SELECT
        CONCAT(job_conexiones.codigo_sucursal,"|",job_conexiones.codigo_usuario,"|",job_conexiones.fecha) AS id,
        job_conexiones.fecha AS id_fecha,
        DATE_FORMAT(job_conexiones.fecha, '%Y/%m/%d') AS FECHA,
        DATE_FORMAT(job_conexiones.fecha, '%T') AS HORA,
        job_usuarios.nombre AS USUARIO,
        job_conexiones.ip AS IP,
        job_conexiones.proxy AS PROXY
    FROM
        job_usuarios,job_sucursales, job_conexiones
    WHERE
        job_conexiones.codigo_usuario = job_usuarios.codigo AND
        job_conexiones.codigo_sucursal = job_sucursales.codigo;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_conexiones AS
    SELECT
        CONCAT(job_conexiones.codigo_sucursal,"|",job_conexiones.codigo_usuario,"|",job_conexiones.fecha) AS id,
        DATE_FORMAT(job_conexiones.fecha, '%Y/%m/%d') AS fecha,
        DATE_FORMAT(job_conexiones.fecha, '%T') AS hora,
        job_usuarios.nombre AS USUARIO,
        job_conexiones.ip AS IP,
        job_conexiones.proxy AS PROXY
    FROM
        job_usuarios, job_sucursales, job_conexiones
    WHERE
        job_conexiones.codigo_usuario  = job_usuarios.codigo AND
        job_conexiones.codigo_sucursal = job_sucursales.codigo;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_consulta_bitacora AS
    SELECT
        CONCAT(job_bitacora.codigo_sucursal_conexion,"|",job_bitacora.codigo_usuario_conexion,"|",job_bitacora.fecha_conexion) AS id,
        DATE_FORMAT(fecha_operacion, '%Y/%m/%d') AS FECHA,
        DATE_FORMAT(fecha_operacion, '%T') AS HORA,
        componente AS COMPONENTE,
        consulta AS CONSULTA,
        mensaje AS MENSAJE
    FROM job_bitacora;
***/
?>

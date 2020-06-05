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

$tablas["perfiles_usuario"]   = array(
    "id"              => "INT(8) UNSIGNED ZEROFILL AUTO_INCREMENT NOT NULL COMMENT 'Consecutivo interno de la base de datos'",
    "codigo_usuario"  => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo interno de la base de datos para el usuario'",
    "codigo_sucursal" => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo interno de la base de datos para la sucursal'",
    "id_perfil"       => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo interno de la base de datos para el perfil'",
);

$tablas["componentes_usuario"]   = array(
    "id"            => "INT(8) UNSIGNED ZEROFILL AUTO_INCREMENT NOT NULL COMMENT 'Consecutivo interno de la base de datos'",
	"id_perfil"     => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Identificador de la tabla perfil usuario'",
    "id_componente" => "VARCHAR(8) NOT NULL COMMENT 'Identificador del componente'",
);

// Definición de llaves primarias
$llavesPrimarias["perfiles_usuario"]    = "id";
$llavesPrimarias["componentes_usuario"] = "id, id_perfil, id_componente";

// Definición de llaves primarias
$llavesUnicas["perfiles_usuario"] = array(
    "codigo_usuario, codigo_sucursal"
);

$llavesForaneas["perfiles_usuario"] = array(
    array(
        // Nombre de la llave
        "perfiles_usuario_usuario",
        // Nombre del campo clave de la tabla local
        "codigo_usuario",
        // Nombre de la tabla relacionada
        "usuarios",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave
        "perfiles_usuario_sucursal",
        // Nombre del campo clave de la tabla local
        "codigo_sucursal",
        // Nombre de la tabla relacionada
        "sucursales",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave
        "perfiles_usuario_perfil",
        // Nombre del campo clave de la tabla local
        "id_perfil",
        // Nombre de la tabla relacionada
        "perfiles",
        // Nombre del campo clave en la tabla relacionada
        "id"
    )
);

$llavesForaneas["componentes_usuario"] = array(
    array(
        // Nombre de la llave
        "componente_usuario_perfil",
        // Nombre del campo clave de la tabla local
        "id_perfil",
        // Nombre de la tabla relacionada
        "perfiles_usuario",
        // Nombre del campo clave en la tabla relacionada
        "id"
    ),
    array(
        // Nombre de la llave
        "componente_usuario_componente",
        // Nombre del campo clave de la tabla local
        "id_componente",
        // Nombre de la tabla relacionada
        "componentes",
        // Nombre del campo clave en la tabla relacionada
        "id"
    )
);

$registros["componentes"] = array(
    array(
        "id"           => "GESTPRIV",
        "padre"        => "SUBMACCE",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "1",
        "orden"        => "400",
        "carpeta"      => "privilegios",
        "archivo"      => "menu",
        "global"       => "0",
        "requiere_item" => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "ADICPRIV",
        "padre"        => "GESTPRIV",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "0",
        "orden"        => "20",
        "carpeta"      => "privilegios",
        "archivo"      => "adicionar",
        "global"       => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "CONSPRIV",
        "padre"        => "GESTPRIV",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "0",
        "orden"        => "30",
        "carpeta"      => "privilegios",
        "archivo"      => "consultar",
        "global"       => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "MODIPRIV",
        "padre"        => "GESTPRIV",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "0",
        "orden"        => "40",
        "carpeta"      => "privilegios",
        "archivo"      => "modificar",
        "global"       => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "ELIMPRIV",
        "padre"        => "GESTPRIV",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "0",
        "orden"        => "50",
        "carpeta"      => "privilegios",
        "archivo"      => "eliminar",
        "global"       => "0",
        "tipo_enlace"  => "1"
    )
);

$vistas = array(
    array( 
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_privilegios AS
        SELECT
            job_perfiles_usuario.id AS id,
            job_usuarios.nombre AS USUARIO,
            job_sucursales.nombre AS SUCURSAL,
            (SELECT nombre FROM job_perfiles WHERE id=job_perfiles_usuario.id_perfil) AS PERFIL
        FROM
            job_perfiles_usuario,
            job_usuarios,
            job_sucursales
        WHERE
            job_perfiles_usuario.codigo_usuario = job_usuarios.codigo AND
            job_perfiles_usuario.codigo_sucursal = job_sucursales.codigo;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_privilegios AS
        SELECT job_perfiles_usuario.id AS id, job_usuarios.nombre AS usuario, job_sucursales.nombre AS sucursal
        FROM job_perfiles_usuario, job_usuarios, job_sucursales
        WHERE job_perfiles_usuario.codigo_usuario = job_usuarios.codigo AND job_perfiles_usuario.codigo_sucursal = job_sucursales.codigo;"
    )
);
/***Sentencias para la creación de las vistas requeridas

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_privilegios AS
    SELECT
        job_perfiles_usuario.id AS id,
        job_usuarios.nombre AS USUARIO,
        job_sucursales.nombre AS SUCURSAL,
        (SELECT nombre FROM job_perfiles WHERE id=job_perfiles_usuario.id_perfil) AS PERFIL
    FROM
        job_perfiles_usuario,
        job_usuarios,
        job_sucursales
    WHERE
        job_perfiles_usuario.codigo_usuario = job_usuarios.codigo AND
        job_perfiles_usuario.codigo_sucursal = job_sucursales.codigo;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_privilegios AS
    SELECT job_perfiles_usuario.id AS id, job_usuarios.nombre AS usuario, job_sucursales.nombre AS sucursal
    FROM job_perfiles_usuario, job_usuarios, job_sucursales
    WHERE job_perfiles_usuario.codigo_usuario = job_usuarios.codigo AND job_perfiles_usuario.codigo_sucursal = job_sucursales.codigo;

***/
?>

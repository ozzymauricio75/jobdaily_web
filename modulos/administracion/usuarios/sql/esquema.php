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
// Eliminar la tabla y crearla de nuevo cada vez que se ejecute el script de creacion
$borrarSiempre = false;

// Definicion de tablas
$tablas["usuarios"]   = array(
    "codigo"                   => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo interno para la base de datos'",
    "usuario"                  => "VARCHAR(12) NOT NULL COMMENT 'Nombre de acceso (login)'",
    "contrasena"               => "CHAR(32) NOT NULL COMMENT 'Contraseña'",
    "nombre"                   => "CHAR(50) NOT NULL COMMENT 'Nombre completo'",
    "correo"                   => "VARCHAR(255) NOT NULL COMMENT 'Dirección de correo electrónico'",
    "cambiar_contrasena"       => "ENUM('0','1') NOT NULL DEFAULT '1' COMMENT 'Puede cambiar la contraseña: 0=No, 1=Si'",
    "fecha_cambio_contrasena"  => "DATETIME DEFAULT NULL COMMENT 'Fecha del último cambio de contraseña'",
    "cambio_contrasena_minimo" => "SMALLINT(4) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Mínimo número de días que deben transcurrir antes de cambiar la contraseña: 0=No aplica'",
    "cambio_contrasena_maximo" => "SMALLINT(4) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Máximo número de días de que pueden transcurrir sin cambiar la contraseña: 0=No aplica'",
    "fecha_expiracion"         => "DATETIME DEFAULT NULL COMMENT 'Fecha máxima hasta la cual el usuario puede acceder a la aplicación: NULL = No aplica'",
    "activo"                   => "ENUM('0','1') NOT NULL DEFAULT '1' COMMENT 'El usuario se encuentra activo y puede acceder a la aplicación: 0 = No, 1= Si'"
);

// Definicion de llaves primarias
$llavesPrimarias["usuarios"] = "codigo";

// Definicion de campos únicos
$llavesUnicas["usuarios"] = array(
    "usuario"
);

// Insercion de datos iniciales
/*$registros["usuarios"] = array(
    array(
        "codigo"     => "0",
        "usuario"    => "admin",
        "contrasena" => "21232f297a57a5a743894a0e4a801fc3", // Versión cifrada con MD5
        "nombre"     => "Administrador Principal",
        "correo"     => "info@saeltda.com.co"
    )
);*/

$registros["componentes"] = array(
    array(
        "id"           => "GESTUSUA",
        "padre"        => "SUBMACCE",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "1",
        "orden"        => "300",
        "carpeta"      => "usuarios",
        "archivo"      => "menu",
        "global"       => "0",
        "requiere_item" => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "ADICUSUA",
        "padre"        => "GESTUSUA",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "0",
        "orden"        => "20",
        "carpeta"      => "usuarios",
        "archivo"      => "adicionar",
        "global"       => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "CONSUSUA",
        "padre"        => "GESTUSUA",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "0",
        "orden"        => "30",
        "carpeta"      => "usuarios",
        "archivo"      => "consultar",
        "global"       => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "MODIUSUA",
        "padre"        => "GESTUSUA",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "0",
        "orden"        => "40",
        "carpeta"      => "usuarios",
        "archivo"      => "modificar",
        "global"       => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "ELIMUSUA",
        "padre"        => "GESTUSUA",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "0",
        "orden"        => "50",
        "carpeta"      => "usuarios",
        "archivo"      => "eliminar",
        "global"       => "0",
        "tipo_enlace"  => "1"
    )
);
$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_usuarios AS
        SELECT codigo AS id,
        usuario AS USUARIO,
        nombre AS NOMBRE
        FROM job_usuarios;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_usuarios AS
        SELECT codigo AS id,
        usuario,
        nombre
        FROM job_usuarios;"
    )
);
/* Sentencia para la creación de la vista requerida

    DROP TABLE IF EXISTS job_menu_usuarios;
    DROP TABLE IF EXISTS job_buscador_usuarios;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_usuarios AS
    SELECT codigo AS id,
    usuario AS USUARIO,
    nombre AS NOMBRE, 
    activo AS ACTIVO
    FROM job_usuarios WHERE activo != '0';

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_usuarios AS
    SELECT codigo AS id,
    usuario,
    nombre,
    activo
    FROM job_usuarios WHERE activo != '0';

*/
?>

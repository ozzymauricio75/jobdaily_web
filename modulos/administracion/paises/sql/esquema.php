<?php
/**
*
* Copyright (C) 2020 Jobdaily
*
* Este archivo es parte de:
* Jobdaily:: Sofware empresarial a la medida
*
* Este programa es software libre: usted puede redistribuirlo y/o
* modificarlo  bajo los terminos de la Licencia Publica General GNU
* publicada por la Fundacion para el Software Libre, ya sea la version 3
* de la Licencia, o (a su eleccion) cualquier version posterior.
*
* Este programa se distribuye con la esperanza de que sea util, pero
* SIN GARANTIA ALGUNA; ni siquiera la garantia implicita MERCANTIL o
* de APTITUD PARA UN PROPOSITO DETERMINADO. Consulte los detalles de
* la Licencia Publica General GNU para obtener una informacion mas
* detallada.
*
* Deberia haber recibido una copia de la Licencia Publica General GNU
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
**/

// Eliminar la tabla y crearla de nuevo cada vez que se ejecute el script de creacion
$borrarSiempre = false;

// Definicion de tablas
$tablas["paises"]   = array(
    "codigo_iso"     => "VARCHAR(2) NOT NULL COMMENT 'Código ISO'",
    "codigo_interno" => "SMALLINT(3) UNSIGNED ZEROFILL COMMENT 'Código para uso interno de la empresa (opcional)'",
    "nombre"         => "VARCHAR(255) NOT NULL COMMENT'Nombre completo'"
);

// Definicion de llaves primarias
$llavesPrimarias["paises"]   = "codigo_iso";

// Insercion de datos iniciales
$registros["paises"] = array(
    array(
        "codigo_iso"     => "",
        "codigo_interno" => "0",
        "nombre"         => ""
    )
);


$registros["componentes"] = array(
    array(
        "id"            => "GESTPAIS",
        "padre"         => "SUBMUBIG",
        "id_modulo"     => "ADMINISTRACION",
        "visible"       => "1",
        "orden"         => "100",
        "carpeta"       => "paises",
        "archivo"       => "menu",
        "global"        => "0",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "ADICPAIS",
        "padre"         => "GESTPAIS",
        "id_modulo"     => "ADMINISTRACION",
        "visible"       => "0",
        "orden"         => "10",
        "carpeta"       => "paises",
        "archivo"       => "adicionar",
        "global"        => "0",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "CONSPAIS",
        "padre"         => "GESTPAIS",
        "id_modulo"     => "ADMINISTRACION",
        "visible"       => "0",
        "orden"         => "20",
        "carpeta"       => "paises",
        "archivo"       => "consultar",
        "global"        => "0",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "MODIPAIS",
        "padre"         => "GESTPAIS",
        "id_modulo"     => "ADMINISTRACION",
        "visible"       => "0",
        "orden"         => "30",
        "carpeta"       => "paises",
        "archivo"       => "modificar",
        "global"        => "0",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "ELIMPAIS",
        "padre"         => "GESTPAIS",
        "id_modulo"     => "ADMINISTRACION",
        "visible"       => "0",
        "orden"         => "40",
        "carpeta"       => "paises",
        "archivo"       => "eliminar",
        "global"        => "0",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "LISTPAIS",
        "padre"         => "GESTPAIS",
        "id_modulo"     => "ADMINISTRACION",
        "visible"       => "0",
        "orden"         => "50",
        "carpeta"       => "paises",
        "archivo"       => "listar",
        "global"        => "0",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_paises AS
        SELECT codigo_iso AS id,
        codigo_iso AS CODIGO_ISO,
        nombre AS NOMBRE
        FROM job_paises
        WHERE codigo_iso !='';"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_paises AS
        SELECT codigo_iso AS id,
        codigo_iso,
        codigo_interno,
        nombre
        FROM job_paises
        WHERE codigo_iso !='';"
    )
);
?>

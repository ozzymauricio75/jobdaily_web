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
/*
$tablas["tallas"] = array(
    "codigo"            => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código manejado por la empresa'",
    "descripcion_corta" => "CHAR(15) NOT NULL DEFAULT '' COMMENT 'Descripción corta de la talla'",
    "descripcion"       => "VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'Descripción de la talla'",
    "orden"             => "INT(5) UNSIGNED ZEROFILL NULL COMMENT 'Orden para los listados'"
);

$tablas["estructura_grupo_talla"] = array(
    "codigo_estructura_grupo"  => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código estrutura de grupo manejado por la empresa'",
    "codigo_talla"             => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código de talla manejado por la empresa'"
);

// Definición de llaves primarias
$llavesPrimarias["tallas"] = "codigo";
$llavesPrimarias["estructura_grupo_talla"] = "codigo_estructura_grupo,codigo_talla";

// Definición de campos únicos
$llavesUnicas["tallas"] = array(
    "descripcion_corta"
);

// Inserción de datos iniciales
$registros["tallas"] = array(
    array(
        "codigo"            => "0",
        "descripcion_corta" => "",
        "descripcion"       => "",
        "orden"             => "0"
    )
);

$registros["estructura_grupo_talla"] = array(
    array(
        "codigo_estructura_grupo" => "0",
        "codigo_talla"            => ""
    )
);

// Inserción de datos iniciales
$registros["componentes"] = array(
    array(
        "id"              => "GESTTALL",
        "padre"           => "SUBMDCIN",
        "id_modulo"       => "INVENTARIO",
        "visible"         => "1",
        "orden"           => "55",
        "carpeta"         => "tallas",
        "archivo"         => "menu",
        "requiere_item"   => "0",
        "tabla_principal" => "tallas",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "ADICTALL",
        "padre"           => "GESTTALL",
        "id_modulo"       => "INVENTARIO",
        "visible"         => "0",
        "orden"           => "0010",
        "carpeta"         => "tallas",
        "archivo"         => "adicionar",
        "requiere_item"   => "0",
        "tabla_principal" => "tallas",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "CONSTALL",
        "padre"           => "GESTTALL",
        "id_modulo"       => "INVENTARIO",
        "visible"         => "0",
        "orden"           => "0020",
        "carpeta"         => "tallas",
        "archivo"         => "consultar",
        "requiere_item"   => "1",
        "tabla_principal" => "tallas",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "MODITALL",
        "padre"           => "GESTTALL",
        "id_modulo"       => "INVENTARIO",
        "visible"         => "0",
        "orden"           => "0030",
        "carpeta"         => "tallas",
        "archivo"         => "modificar",
        "requiere_item"   => "1",
        "tabla_principal" => "tallas",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "ELIMTALL",
        "padre"           => "GESTTALL",
        "id_modulo"       => "INVENTARIO",
        "visible"         => "0",
        "orden"           => "0040",
        "carpeta"         => "tallas",
        "archivo"         => "eliminar",
        "requiere_item"   => "1",
        "tabla_principal" => "tallas",
        "tipo_enlace"     => "1"
    )
);

$vistas = array(     
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_tallas AS
        SELECT  job_tallas.codigo AS id,
                job_tallas.codigo AS CODIGO,
                job_tallas.descripcion AS DESCRIPCION,
                job_tallas.descripcion_corta AS DESCRIPCION_CORTA
        FROM    job_tallas
        WHERE   job_tallas.codigo != 0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_tallas AS
        SELECT codigo AS id, codigo, descripcion, descripcion_corta, orden
        FROM    job_tallas
        WHERE   job_tallas.codigo != 0;"
    )
);*/

/***
    DROP TABLE IF EXISTS job_menu_marcas;
    DROP TABLE IF EXISTS job_buscador_marcas;
***/

?>


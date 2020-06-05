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
$tablas["marcas"] = array(
    "codigo"      => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código manejado por la empresa'",
    "descripcion" => "VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'Descripción de la marca'",
    "orden"       => "INT(5) UNSIGNED ZEROFILL NULL COMMENT 'Orden para los listados'"
);

// Definición de llaves primarias
$llavesPrimarias["marcas"] = "codigo";

// Definición de campos únicos
$llavesUnicas["marcas"] = array(
    "descripcion"
);

// Inserción de datos iniciales***/
$registros["marcas"] = array(
    array(
        "codigo"      => "0",
        "descripcion" => "",
        "orden"       => "0"
    )
);

// Inserción de datos iniciales***/
$registros["componentes"] = array(
    array(
        "id"              => "GESTMARC",
        "padre"           => "SUBMDCIN",
        "id_modulo"       => "INVENTARIO",
        "visible"         => "1",
        "orden"           => "0050",
        "carpeta"         => "marcas",
        "archivo"         => "menu",
        "requiere_item"   => "0",
        "tabla_principal" => "marcas",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "ADICMARC",
        "padre"           => "GESTMARC",
        "id_modulo"       => "INVENTARIO",
        "visible"         => "0",
        "orden"           => "0010",
        "carpeta"         => "marcas",
        "archivo"         => "adicionar",
        "requiere_item"   => "0",
        "tabla_principal" => "marcas",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "CONSMARC",
        "padre"           => "GESTMARC",
        "id_modulo"       => "INVENTARIO",
        "visible"         => "0",
        "orden"           => "0020",
        "carpeta"         => "marcas",
        "archivo"         => "consultar",
        "requiere_item"   => "1",
        "tabla_principal" => "marcas",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "MODIMARC",
        "padre"           => "GESTMARC",
        "id_modulo"       => "INVENTARIO",
        "visible"         => "0",
        "orden"           => "0030",
        "carpeta"         => "marcas",
        "archivo"         => "modificar",
        "requiere_item"   => "1",
        "tabla_principal" => "marcas",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "ELIMMARC",
        "padre"           => "GESTMARC",
        "id_modulo"       => "INVENTARIO",
        "visible"         => "0",
        "orden"           => "0040",
        "carpeta"         => "marcas",
        "archivo"         => "eliminar",
        "requiere_item"   => "1",
        "tabla_principal" => "marcas",
        "tipo_enlace"     => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_marcas_prueba AS
        SELECT  job_marcas.codigo AS id,
                job_marcas.codigo AS CODIGO,
                job_marcas.descripcion AS DESCRIPCION
        FROM    job_marcas
        WHERE   job_marcas.codigo != 0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_marcas_prueba AS
        SELECT codigo AS id, codigo, descripcion, orden
        FROM    job_marcas
        WHERE   job_marcas.codigo != 0;"
    )
);

$vistas = array(     
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_marcas AS
        SELECT  job_marcas.codigo AS id,
                job_marcas.codigo AS CODIGO,
                job_marcas.descripcion AS DESCRIPCION
        FROM    job_marcas
        WHERE   job_marcas.codigo != 0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_marcas AS
        SELECT codigo AS id, codigo, descripcion, orden
        FROM    job_marcas
        WHERE   job_marcas.codigo != 0;"
    )
);
/***
    DROP TABLE IF EXISTS job_menu_marcas;
    DROP TABLE IF EXISTS job_buscador_marcas;
$vistas = array(     
    array(
        "vista" =>
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_marcas AS
        SELECT  job_marcas.codigo AS id,
                job_marcas.codigo AS CODIGO,
                job_marcas.descripcion AS DESCRIPCION
        FROM    job_marcas
        WHERE   job_marcas.codigo != 0;"
    ),
    array(
        "vistas" => 
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_marcas AS
        SELECT codigo AS id, codigo, descripcion, orden
        FROM    job_marcas
        WHERE   job_marcas.codigo != 0;"
    )
);
***/

?>


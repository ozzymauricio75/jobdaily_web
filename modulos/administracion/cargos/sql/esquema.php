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
$tablas["cargos"] = array(
    "codigo " => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo interno asignado por el usuario'",
    "nombre"  => "VARCHAR(50) NOT NULL COMMENT 'Nombre del cargo'",
    "interno" => "ENUM('0','1') NOT NULL DEFAULT '1' COMMENT 'Cargo interno 0->No, 1->Si'"
);

// Definición de llaves primarias
$llavesPrimarias["cargos"]   = "codigo";

// Definición de las llaves unicas
$llavesUnicas["cargos"] = array(
    "nombre"
);

// Inserción de datos iniciales
$registros["cargos"] = array(
    array(
        "codigo"  => "0",
        "nombre"  => "",
        "interno" => "0"
    )
);

$registros["componentes"] = array(
    array(
        "id"           => "GESTCARG",
        "padre"        => "SUBMDCAD",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "1",
        "orden"        => "300",
        "carpeta"      => "cargos",
        "archivo"      => "menu",
        "global"       => "0",
        "requiere_item" => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "ADICCARG",
        "padre"        => "GESTCARG",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "0",
        "orden"        => "20",
        "carpeta"      => "cargos",
        "archivo"      => "adicionar",
        "global"       => "0",
        "requiere_item" => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "CONSCARG",
        "padre"        => "GESTCARG",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "0",
        "orden"        => "20",
        "carpeta"      => "cargos",
        "archivo"      => "consultar",
        "global"       => "0",
        "requiere_item" => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "MODICARG",
        "padre"        => "GESTCARG",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "0",
        "orden"        => "20",
        "carpeta"      => "cargos",
        "archivo"      => "modificar",
        "global"       => "0",
        "requiere_item" => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "ELIMCARG",
        "padre"        => "GESTCARG",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "0",
        "orden"        => "20",
        "carpeta"      => "cargos",
        "archivo"      => "eliminar",
        "global"       => "0",
        "requiere_item" => "0",
        "tipo_enlace"  => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_cargos AS
        SELECT codigo AS id,
            codigo AS CODIGO,
            nombre AS NOMBRE,
            CONCAT('INTERNO_',interno) AS INTERNO
        FROM job_cargos
        WHERE codigo != 0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_cargos AS
        SELECT codigo AS id,
        codigo AS codigo,
        nombre AS nombre
        FROM job_cargos
        WHERE codigo != 0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_cargos AS
        SELECT codigo AS id,
        CONCAT(codigo,' - ',nombre,'|',codigo) AS descripcion
        FROM job_cargos;"
    )
)
/***
    DROP TABLE IF EXISTS job_menu_cargos;
    DROP TABLE IF EXISTS job_buscador_cargos;
    DROP TABLE IF EXISTS job_seleccion_cargos;
    
    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_cargos AS
    SELECT codigo AS id,
        codigo AS CODIGO,
        nombre AS NOMBRE,
        CONCAT('INTERNO_',interno) AS INTERNO
    FROM job_cargos
    WHERE codigo != 0;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_cargos AS
    SELECT codigo AS id,
    codigo AS codigo,
    nombre AS nombre
    FROM job_cargos
    WHERE codigo != 0;
    
    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_cargos AS
    SELECT codigo AS id,
    CONCAT(codigo,' - ',nombre,'|',codigo) AS descripcion
    FROM job_cargos;

***/
?>

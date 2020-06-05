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
$tablas["tipos_documento_identidad"] = array(
    "codigo"       => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código asignado por el usuario'",
    "codigo_dian"  => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código manejo por la DIAN'",
    "descripcion"  => "VARCHAR(255) NOT NULL COMMENT 'Detalle que identifica el tipo de documento de identidad'",
    "tipo_persona" => "ENUM('1','2','3','4') NOT NULL DEFAULT '1' COMMENT'Tipo de persona para la que aplica el documento: 1->Natural,2->Juridica,3->Codigo interno,4->Natural Comerciante'"
);

// Definición de llaves primarias
$llavesPrimarias["tipos_documento_identidad"] = "codigo";

// Definición de llaves primarias
$llavesUnicas["tipos_documento_identidad"] = array(
    "codigo_dian"
);

// Inserción de datos iniciales
$registros["tipos_documento_identidad"] = array(
    array(
        "codigo"       => "0",
        "codigo_dian"  => "0",
        "descripcion"  => "",
        "tipo_persona" => "3"
    )
);

$registros["componentes"] = array(
    array(
        "id"           => "GESTTIDI",
        "padre"        => "SUBMDCAD",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "1",
        "orden"        => "200",
        "carpeta"      => "tipos_documento_identidad",
        "archivo"      => "menu",
        "global"       => "0",
        "requiere_item" => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "ADICTIDI",
        "padre"        => "GESTTIDI",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "0",
        "orden"        => "20",
        "carpeta"      => "tipos_documento_identidad",
        "archivo"      => "adicionar",
        "global"       => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "CONSTIDI",
        "padre"        => "GESTTIDI",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "0",
        "orden"        => "30",
        "carpeta"      => "tipos_documento_identidad",
        "archivo"      => "consultar",
        "global"       => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "MODITIDI",
        "padre"        => "GESTTIDI",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "0",
        "orden"        => "40",
        "carpeta"      => "tipos_documento_identidad",
        "archivo"      => "modificar",
        "global"       => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "ELIMTIDI",
        "padre"        => "GESTTIDI",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "0",
        "orden"        => "50",
        "carpeta"      => "tipos_documento_identidad",
        "archivo"      => "eliminar",
        "global"       => "0",
        "tipo_enlace"  => "1"
    )
);


$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_tipos_documento_identidad AS
        SELECT  codigo AS id,
                codigo AS CODIGO_INTERNO,
                codigo_dian AS CODIGO_DIAN,
                descripcion AS DESCRIPCION,
                CONCAT(
                    'TIPO_PERSONA_',tipo_persona
                ) AS TIPO_PERSONA
        FROM    job_tipos_documento_identidad
        WHERE   codigo != 0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_tipos_documento_identidad AS
        SELECT  codigo AS id,
                codigo_dian AS codigo_dian,
                codigo AS codigo_interno,
                descripcion AS descripcion
        FROM    job_tipos_documento_identidad
        WHERE   codigo != 0;"
    )
);
/*
    DROP TABLE IF EXISTS job_menu_tipos_documento_identidad;
    DROP TABLE IF EXISTS job_buscador_tipos_documento_identidad;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_tipos_documento_identidad AS
    SELECT  codigo AS id,
            codigo AS CODIGO_INTERNO,
            codigo_dian AS CODIGO_DIAN,
            descripcion AS DESCRIPCION,
            CONCAT(
                'TIPO_PERSONA_',tipo_persona
            ) AS TIPO_PERSONA

    FROM    job_tipos_documento_identidad

    WHERE   codigo != 0;


    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_tipos_documento_identidad AS
    SELECT  codigo AS id,
            codigo_dian AS codigo_dian,
            codigo AS codigo_interno,
            descripcion AS descripcion

    FROM    job_tipos_documento_identidad

    WHERE   codigo != 0;
*/
?>

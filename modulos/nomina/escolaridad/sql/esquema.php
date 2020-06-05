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

$borrarSiempre = false;

// Definición de tablas
$tablas["escolaridad"] = array(
    "codigo"        => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código interno que identifica la escolaridad'",
    "descripcion"   => "VARCHAR(255) NOT NULL COMMENT 'Detalle que describe la escolaridad'"
);

// Definicion de llaves primarias
$llavesPrimarias["escolaridad"] = "codigo";

// Definicion de campos unicos
$llavesUnicas["escolaridad"] = array(
    "descripcion"
);

$registros["escolaridad"] = array(
    array(
        "codigo"        => "0",
        "descripcion"   => ""
    )
);

// Insercion de datos iniciales
$registros["componentes"] = array(
    array(
        "id"            => "GESTESCO",
        "padre"         => "SUBMDCRH",
        "id_modulo"     => "NOMINA",
        "orden"         => "150",
        "visible"       => "1",
        "carpeta"       => "escolaridad",
        "global"        => "0",
        "archivo"       => "menu",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "ADICESCO",
        "padre"         => "GESTESCO",
        "id_modulo"     => "NOMINA",
        "orden"         => "10",
        "visible"       => "0",
        "carpeta"       => "escolaridad",
        "global"        => "0",
        "archivo"       => "adicionar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "CONSESCO",
        "padre"         => "GESTESCO",
        "id_modulo"     => "NOMINA",
        "orden"         => "25",
        "visible"       => "0",
        "carpeta"       => "escolaridad",
        "global"        => "0",
        "archivo"       => "consultar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "MODIESCO",
        "padre"         => "GESTESCO",
        "id_modulo"     => "NOMINA",
        "orden"         => "30",
        "visible"       => "0",
        "carpeta"       => "escolaridad",
        "global"        => "0",
        "archivo"       => "modificar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "ELIMESCO",
        "padre"         => "GESTESCO",
        "id_modulo"     => "NOMINA",
        "orden"         => "40",
        "visible"       => "0",
        "carpeta"       => "escolaridad",
        "global"        => "0",
        "archivo"       => "eliminar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM=MERGE VIEW job_menu_escolaridad AS
        SELECT codigo AS id,
        codigo AS CODIGO,
        descripcion AS DESCRIPCION
        FROM job_escolaridad
        WHERE codigo > 0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM=MERGE VIEW job_buscador_escolaridad AS
        SELECT codigo AS id,
        codigo AS CODIGO,
        descripcion AS DESCRIPCION
        FROM job_escolaridad
        WHERE codigo > 0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM=MERGE VIEW job_seleccion_escolaridad AS
        SELECT job_escolaridad.codigo AS id,
        CONCAT(job_escolaridad.codigo, ' ',
        job_escolaridad.descripcion, '|', job_escolaridad.codigo) AS descripcion
        FROM job_escolaridad
        WHERE codigo > 0 ;"
    )
);

?>

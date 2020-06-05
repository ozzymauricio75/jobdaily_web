<?php
/**
*
* Copyright (C) 2010 sem Ltda
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
$tablas["auxilio_transporte"] = array(
    "codigo" => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código interno que identifica el auxilio de transporte'",
    "fecha"  => "DATE NOT NULL COMMENT 'Fecha a partir de la cual empieza a regir el valor del auxilio de transporte'",
    "valor"  => "DECIMAL(11,2) NOT NULL COMMENT 'Valor del auxilio de transporte de acuerdo a la fecha'"
);

// Definición de llaves primarias
$llavesPrimarias["auxilio_transporte"] = "codigo";

// Definicion de llaves Unicas
$llavesUnicas["auxilio_transporte"] = array(
    "fecha"
);

$registros["componentes"] = array(
    array(
        "id"            => "GESTAXTP",
        "padre"         => "SUBMPRAN",
        "id_modulo"     => "NOMINA",
        "orden"         => "75",
        "visible"       => "1",
        "carpeta"       => "auxilio_transporte",
        "global"        => "0",
        "archivo"       => "menu",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "ADICAXTP",
        "padre"         => "GESTAXTP",
        "id_modulo"     => "NOMINA",
        "orden"         => "10",
        "visible"       => "0",
        "carpeta"       => "auxilio_transporte",
        "global"        => "0",
        "archivo"       => "adicionar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "CONSAXTP",
        "padre"         => "GESTAXTP",
        "id_modulo"     => "NOMINA",
        "orden"         => "20",
        "visible"       => "0",
        "carpeta"       => "auxilio_transporte",
        "global"        => "0",
        "archivo"       => "consultar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "MODIAXTP",
        "padre"         => "GESTAXTP",
        "id_modulo"     => "NOMINA",
        "orden"         => "30",
        "visible"       => "0",
        "carpeta"       => "auxilio_transporte",
        "global"        => "0",
        "archivo"       => "modificar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "ELIMAXTP",
        "padre"         => "GESTAXTP",
        "id_modulo"     => "NOMINA",
        "orden"         => "40",
        "visible"       => "0",
        "carpeta"       => "auxilio_transporte",
        "global"        => "0",
        "archivo"       => "eliminar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_auxilio_transporte AS
        SELECT codigo AS id,
        codigo AS CODIGO,
        fecha AS FECHA,
        CONCAT('$ ',FORMAT(valor,0)) AS VALOR
        FROM job_auxilio_transporte;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_auxilio_transporte AS
        SELECT codigo AS id,
        fecha AS FECHA,
        valor AS VALOR
        FROM job_auxilio_transporte;"
    )
);
// Sentencia para la creación de la vista requerida
/***
    DROP TABLE IF EXISTS job_menu_auxilio_transporte;
    DROP TABLE IF EXISTS job_buscador_auxilio_transporte;
    
    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_auxilio_transporte AS
    SELECT codigo AS id,
    codigo AS CODIGO,
    fecha AS FECHA,
    CONCAT('$ ',FORMAT(valor,0)) AS VALOR
    FROM job_auxilio_transporte;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_auxilio_transporte AS
    SELECT codigo AS id,
    fecha AS FECHA,
    valor AS VALOR
    FROM job_auxilio_transporte;
***/
?>

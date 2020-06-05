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
$tablas["salario_minimo"] = array(
    "codigo" => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo interno identificar el salario minimo'",
    "fecha"  => "DATE NOT NULL COMMENT 'Fecha a partir de la cual empieza a regir el valor del salario minimo'",
    "valor"  => "DECIMAL(15,2) NOT NULL COMMENT 'Valor del salario minimo de acuerdo a la fecha'"
);

// Definición de llaves primarias
$llavesPrimarias["salario_minimo"] = "codigo";

// Definicion de llaves Unicas
$llavesUnicas["salario_minimo"] = array(
    "fecha"
);

$registros["componentes"] = array(
    array(
        "id"            => "GESTSMLV",
        "padre"         => "SUBMPRAN",
        "id_modulo"     => "NOMINA",
        "orden"         => "75",
        "visible"       => "1",
        "carpeta"       => "salario_minimo",
        "global"        => "0",
        "archivo"       => "menu",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "ADICSMLV",
        "padre"         => "GESTSMLV",
        "id_modulo"     => "NOMINA",
        "orden"         => "10",
        "visible"       => "0",
        "carpeta"       => "salario_minimo",
        "global"        => "0",
        "archivo"       => "adicionar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "CONSSMLV",
        "padre"         => "GESTSMLV",
        "id_modulo"     => "NOMINA",
        "orden"         => "20",
        "visible"       => "0",
        "carpeta"       => "salario_minimo",
        "global"        => "0",
        "archivo"       => "consultar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "MODISMLV",
        "padre"         => "GESTSMLV",
        "id_modulo"     => "NOMINA",
        "orden"         => "30",
        "visible"       => "0",
        "carpeta"       => "salario_minimo",
        "global"        => "0",
        "archivo"       => "modificar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "ELIMSMLV",
        "padre"         => "GESTSMLV",
        "id_modulo"     => "NOMINA",
        "orden"         => "40",
        "visible"       => "0",
        "carpeta"       => "salario_minimo",
        "global"        => "0",
        "archivo"       => "eliminar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_salario_minimo AS
        SELECT codigo AS id,
        codigo AS CODIGO,
        fecha AS FECHA,
        CONCAT('$ ',FORMAT(valor,0)) AS VALOR
        FROM job_salario_minimo;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_salario_minimo AS
        SELECT codigo AS id,
        fecha AS FECHA,
        valor AS VALOR
        FROM job_salario_minimo;"
    )
);
// Sentencia para la creación de la vista requerida
/***
    DROP TABLE IF EXISTS job_menu_salario_minimo;
    DROP TABLE IF EXISTS job_buscador_salario_minimo;
    
    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_salario_minimo AS
    SELECT codigo AS id,
    codigo AS CODIGO,
    fecha AS FECHA,
    CONCAT('$ ',FORMAT(valor,0)) AS VALOR
    FROM job_salario_minimo;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_salario_minimo AS
    SELECT codigo AS id,
    fecha AS FECHA,
    valor AS VALOR
    FROM job_salario_minimo;
    
***/
?>

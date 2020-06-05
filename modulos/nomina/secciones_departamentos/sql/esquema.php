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

// Definición de tablas ***/
$tablas["secciones_departamentos"] = array(
    "codigo_departamento_empresa" => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la tabla departamentos_empresa'",
    "codigo"                      => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código interno que identifica la sección'",
    "nombre"                      => "VARCHAR(50) NOT NULL COMMENT 'Nombre la sección'"
);

// Definición de llaves primarias ***/
$llavesPrimarias["secciones_departamentos"] = "codigo,codigo_departamento_empresa";

// Definición de llaves primarias ***/
$llavesUnicas["secciones_departamentos"] = array(
    "codigo,codigo_departamento_empresa,nombre"
);

// Definición de llaves primarias ***/
$llavesForaneas["secciones_departamentos"] = array (
    array(
        //Nombre de la llave foranes
        "secciones_departamento_empresa",
        //Nombre del campo en la tabla actual
        "codigo_departamento_empresa",
        //Nombre de la tabla relcaionada
        "departamentos_empresa",
        //Nombre del campo en la tabla relacionada
        "codigo"
    )
);

$registros["componentes"] = array(
    array(
        "id"            => "GESTSCDE",
        "padre"         => "SUBMDCRH",
        "id_modulo"     => "NOMINA",
        "orden"         => "90",
        "visible"       => "1",
        "carpeta"       => "secciones_departamentos",
        "global"        => "0",
        "archivo"       => "menu",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "ADICSCDE",
        "padre"         => "GESTSCDE",
        "id_modulo"     => "NOMINA",
        "orden"         => "10",
        "visible"       => "0",
        "carpeta"       => "secciones_departamentos",
        "global"        => "0",
        "archivo"       => "adicionar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "CONSSCDE",
        "padre"         => "GESTSCDE",
        "id_modulo"     => "NOMINA",
        "orden"         => "20",
        "visible"       => "0",
        "carpeta"       => "secciones_departamentos",
        "global"        => "0",
        "archivo"       => "consultar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "MODISCDE",
        "padre"         => "GESTSCDE",
        "id_modulo"     => "NOMINA",
        "orden"         => "30",
        "visible"       => "0",
        "carpeta"       => "secciones_departamentos",
        "global"        => "0",
        "archivo"       => "modificar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "ELIMSCDE",
        "padre"         => "GESTSCDE",
        "id_modulo"     => "NOMINA",
        "orden"         => "40",
        "visible"       => "0",
        "carpeta"       => "secciones_departamentos",
        "global"        => "0",
        "archivo"       => "eliminar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "LISTSCDE",
        "padre"         => "GESTSCDE",
        "id_modulo"     => "NOMINA",
        "orden"         => "50",
        "visible"       => "0",
        "carpeta"       => "secciones_departamentos",
        "global"        => "0",
        "archivo"       => "listar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_secciones_departamentos AS
        SELECT
            CONCAT(job_secciones_departamentos.codigo,'|',job_secciones_departamentos.codigo_departamento_empresa) AS id,
            job_secciones_departamentos.codigo AS CODIGO,
            job_secciones_departamentos.nombre AS NOMBRE,
            job_departamentos_empresa.nombre AS DEPARTAMENTO_EMPRESA
        FROM
            job_secciones_departamentos, job_departamentos_empresa
        WHERE
            job_secciones_departamentos.codigo_departamento_empresa = job_departamentos_empresa.codigo;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_secciones_departamentos AS
        SELECT
            CONCAT(job_secciones_departamentos.codigo,'|',job_secciones_departamentos.codigo_departamento_empresa) AS id,
            job_secciones_departamentos.codigo AS CODIGO,
            job_secciones_departamentos.nombre AS NOMBRE,
            job_departamentos_empresa.codigo AS DEPARTAMENTO_EMPRESA
        FROM
            job_secciones_departamentos, job_departamentos_empresa
        WHERE
            job_secciones_departamentos.codigo_departamento_empresa = job_departamentos_empresa.codigo;"
    )
);
/***  Sentencia para la creacion de la vista requerida

    DROP TABLE IF EXISTS job_menu_secciones_departamentos;
    DROP TABLE IF EXISTS job_buscador_secciones_departamentos;
    DROP TABLE IF EXISTS job_seleccion_secciones_departamentos;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_secciones_departamentos AS
    SELECT
        CONCAT(job_secciones_departamentos.codigo,"|",job_secciones_departamentos.codigo_departamento_empresa) AS id,
        job_secciones_departamentos.codigo AS CODIGO,
        job_secciones_departamentos.nombre AS NOMBRE,
        job_departamentos_empresa.nombre AS DEPARTAMENTO_EMPRESA
    FROM
        job_secciones_departamentos, job_departamentos_empresa
    WHERE
        job_secciones_departamentos.codigo_departamento_empresa = job_departamentos_empresa.codigo;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_secciones_departamentos AS
    SELECT
        CONCAT(job_secciones_departamentos.codigo,"|",job_secciones_departamentos.codigo_departamento_empresa) AS id,
        job_secciones_departamentos.codigo AS CODIGO,
        job_secciones_departamentos.nombre AS NOMBRE,
        job_departamentos_empresa.codigo AS DEPARTAMENTO_EMPRESA
    FROM
        job_secciones_departamentos, job_departamentos_empresa
    WHERE
        job_secciones_departamentos.codigo_departamento_empresa = job_departamentos_empresa.codigo;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_secciones_departamentos AS
    SELECT
        CONCAT(job_secciones_departamentos.codigo,"|",job_secciones_departamentos.codigo_departamento_empresa) AS id,
        CONCAT(job_secciones_departamentos.nombre,'|',job_secciones_departamentos.codigo,",",job_secciones_departamentos.codigo_departamento_empresa) AS NOMBRE
    FROM job_secciones_departamentos;
***/
?>

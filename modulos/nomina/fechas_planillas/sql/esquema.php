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

$borrarSiempre = false;

// Definicion de tablas
$tablas["fechas_planillas"] = array(
    "codigo_planilla" => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código interno de la planilla de pago'",
    "fecha"           => "DATE NOT NULL COMMENT 'fecha que se va a pagar la planilla'"
);

// Definicion de llaves primarias
$llavesPrimarias["fechas_planillas"] = "codigo_planilla,fecha";

$llavesForaneas["fechas_planillas"] = array(
    array(
        // Nombre de la llave foranea
        "planillas_pago_fecha",
        // Nombre del campo en la tabla actual
        "codigo_planilla",
        // Nombre de la tabla relacionada
        "planillas",
        // Nombre del campo de la tabla relacionada
        "codigo"
    )
);

$registros["componentes"] = array(
    array(
        "id"            => "GESTFEPL",
        "padre"         => "SUBMDCRH",
        "id_modulo"     => "NOMINA",
        "orden"         => "70",
        "visible"       => "1",
        "carpeta"       => "fechas_planillas",
        "global"        => "0",
        "archivo"       => "menu",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "ADICFEPL",
        "padre"         => "GESTFEPL",
        "id_modulo"     => "NOMINA",
        "orden"         => "10",
        "visible"       => "0",
        "carpeta"       => "fechas_planillas",
        "global"        => "0",
        "archivo"       => "adicionar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "CONSFEPL",
        "padre"         => "GESTFEPL",
        "id_modulo"     => "NOMINA",
        "orden"         => "25",
        "visible"       => "0",
        "carpeta"       => "fechas_planillas",
        "global"        => "0",
        "archivo"       => "consultar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "MODIFEPL",
        "padre"         => "GESTFEPL",
        "id_modulo"     => "NOMINA",
        "orden"         => "30",
        "visible"       => "0",
        "carpeta"       => "fechas_planillas",
        "global"        => "0",
        "archivo"       => "modificar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "ELIMFEPL",
        "padre"         => "GESTFEPL",
        "id_modulo"     => "NOMINA",
        "orden"         => "40",
        "visible"       => "0",
        "carpeta"       => "fechas_planillas",
        "global"        => "0",
        "archivo"       => "eliminar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW  job_menu_fechas_planillas AS
        SELECT
                CONCAT(FP.codigo_planilla,'|',DATE_FORMAT(FP.fecha,'%Y')) AS id,
                P.descripcion AS PLANILLA,
                DATE_FORMAT(fecha,'%Y') AS ANO
        FROM    job_fechas_planillas AS FP, job_planillas AS P
        WHERE   P.codigo=FP.codigo_planilla
        GROUP BY codigo_planilla, ANO;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW  job_buscador_fechas_planillas AS
        SELECT
            CONCAT(job_fechas_planillas.codigo_planilla,'|',DATE_FORMAT(job_fechas_planillas.fecha,'%Y')) AS id,
            job_planillas.descripcion AS planilla,
            job_fechas_planillas.codigo_planilla AS codigo_planilla,
            DATE_FORMAT(job_fechas_planillas.fecha,'%Y') AS ano
         FROM
            job_fechas_planillas, job_planillas
        WHERE
            job_fechas_planillas.codigo_planilla = job_planillas.codigo
            GROUP BY  codigo_planilla,ano;"
    ),
    array("CREATE OR REPLACE ALGORITHM = MERGE VIEW  job_seleccion_fechas_planillas AS
            SELECT  codigo_planilla,
                    DATE_FORMAT(fecha,'%Y') AS ano,
                    DATE_FORMAT(fecha,'%m') AS mes,
                    DATE_FORMAT(fecha,'%d') AS dia,
                    fecha AS fecha
            FROM    job_fechas_planillas;"
    )
);
?>

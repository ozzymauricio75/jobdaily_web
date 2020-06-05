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
$tablas["motivos_tiempo_no_laborado"] = array(
    "codigo"        => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo interno identifica el motivo de la incapacidad'",
    "descripcion"	=> "VARCHAR(255) NOT NULL COMMENT 'Detalle que describe el motivo de incapacidad'"
);

// Definición de llaves primarias
$llavesPrimarias["motivos_tiempo_no_laborado"] = "codigo";

// Definición de campos únicos
$llavesUnicas["motivos_tiempo_no_laborado"] = array(
    "descripcion"
);

$registros["componentes"] = array(
    array(
        "id"            => "GESTNOLA",
        "padre"         => "SUBMDCRH",
        "id_modulo"     => "NOMINA",
        "orden"         => "135",
        "visible"       => "1",
        "carpeta"       => "motivos_tiempos_no_laborar",
        "global"        => "0",
        "archivo"       => "menu",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "ADICNOLA",
        "padre"         => "GESTNOLA",
        "id_modulo"     => "NOMINA",
        "orden"         => "10",
        "visible"       => "0",
        "carpeta"       => "motivos_tiempos_no_laborar",
        "global"        => "0",
        "archivo"       => "adicionar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "CONSNOLA",
        "padre"         => "GESTNOLA",
        "id_modulo"     => "NOMINA",
        "orden"         => "25",
        "visible"       => "0",
        "carpeta"       => "motivos_tiempos_no_laborar",
        "global"        => "0",
        "archivo"       => "consultar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "MODINOLA",
        "padre"         => "GESTNOLA",
        "id_modulo"     => "NOMINA",
        "orden"         => "30",
        "visible"       => "0",
        "carpeta"       => "motivos_tiempos_no_laborar",
        "global"        => "0",
        "archivo"       => "modificar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "ELIMNOLA",
        "padre"         => "GESTNOLA",
        "id_modulo"     => "NOMINA",
        "orden"         => "40",
        "visible"       => "0",
        "carpeta"       => "motivos_tiempos_no_laborar",
        "global"        => "0",
        "archivo"       => "eliminar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_motivos_tiempo_no_laborado AS
        SELECT codigo AS id,
        job_motivos_tiempo_no_laborado.codigo AS CODIGO,
        job_motivos_tiempo_no_laborado.descripcion AS DESCRIPCION
        FROM job_motivos_tiempo_no_laborado;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_motivos_tiempo_no_laborado AS
        SELECT codigo AS id,
        job_motivos_tiempo_no_laborado.codigo AS CODIGO,
        job_motivos_tiempo_no_laborado.descripcion AS DESCRIPCION
        FROM job_motivos_tiempo_no_laborado;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_motivos_tiempo_no_laborado AS
        SELECT job_motivos_tiempo_no_laborado.codigo AS id,
        CONCAT(job_motivos_tiempo_no_laborado.descripcion, '|', job_motivos_tiempo_no_laborado.codigo) AS descripcion
        FROM job_motivos_tiempo_no_laborado
        WHERE codigo > 0;"
    )
);
// Sentencia para la creaciÓn de la vista requerida
/***
    DROP TABLE IF EXISTS job_menu_motivos_incapacidad;
    DROP TABLE IF EXISTS job_buscador_motivos_incapacidad;
    DROP TABLE IF EXISTS job_seleccion_motivos_incapacidad;
    
    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_motivos_incapacidad AS
    SELECT codigo AS id,
    job_motivos_incapacidad.codigo AS CODIGO,
    job_motivos_incapacidad.descripcion AS DESCRIPCION
    FROM job_motivos_incapacidad;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_motivos_incapacidad AS
    SELECT codigo AS id,
    job_motivos_incapacidad.codigo AS CODIGO,
    job_motivos_incapacidad.descripcion AS DESCRIPCION
    FROM job_motivos_incapacidad;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_motivos_incapacidad AS
    SELECT job_motivos_incapacidad.codigo AS id,
    CONCAT(job_motivos_incapacidad.descripcion, '|', job_motivos_incapacidad.codigo) AS descripcion
    FROM job_motivos_incapacidad
    WHERE codigo > 0;
***/
?>

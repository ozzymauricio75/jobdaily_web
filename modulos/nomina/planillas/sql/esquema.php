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
$tablas["planillas"] = array(
    "codigo"        => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código interno que identifica la planilla'",
    "descripcion"   => "VARCHAR(255) NOT NULL COMMENT 'Descripción de la planilla'",
    "periodo_pago"  => "ENUM('1','2','3','4') NOT NULL DEFAULT '1' COMMENT '1->Mensual 2->Quincenal 3->Semanal 4->Fecha unica'"
);

// Definición de llaves primarias
$llavesPrimarias["planillas"] = "codigo";


// Inserción de datos iniciales***/
$registros["planillas"] = array(
    array(
        "descripcion"  => "",
        "periodo_pago" => ""
    )
);

$registros["componentes"] = array(
    array(
        "id"            => "GESTPLAN",
        "padre"         => "SUBMDCRH",
        "id_modulo"     => "NOMINA",
        "orden"         => "60",
        "visible"       => "1",
        "carpeta"       => "planillas",
        "global"        => "0",
        "archivo"       => "menu",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "ADICPLAN",
        "padre"         => "GESTPLAN",
        "id_modulo"     => "NOMINA",
        "orden"         => "10",
        "visible"       => "0",
        "carpeta"       => "planillas",
        "global"        => "0",
        "archivo"       => "adicionar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "CONSPLAN",
        "padre"         => "GESTPLAN",
        "id_modulo"     => "NOMINA",
        "orden"         => "25",
        "visible"       => "0",
        "carpeta"       => "planillas",
        "global"        => "0",
        "archivo"       => "consultar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "MODIPLAN",
        "padre"         => "GESTPLAN",
        "id_modulo"     => "NOMINA",
        "orden"         => "30",
        "visible"       => "0",
        "carpeta"       => "planillas",
        "global"        => "0",
        "archivo"       => "modificar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "ELIMPLAN",
        "padre"         => "GESTPLAN",
        "id_modulo"     => "NOMINA",
        "orden"         => "40",
        "visible"       => "0",
        "carpeta"       => "planillas",
        "global"        => "0",
        "archivo"       => "eliminar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_planillas AS
        SELECT codigo AS id,
        codigo as CODIGO,
        descripcion AS DESCRIPCION
        FROM job_planillas
        WHERE codigo > 0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_planillas AS
        SELECT codigo AS id,
        codigo as CODIGO,
        descripcion AS DESCRIPCION
        FROM job_planillas
        WHERE codigo > 0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_planillas AS
        SELECT codigo AS id,
        CONCAT(descripcion,'|',codigo)AS descripcion
        FROM job_planillas
        WHERE codigo > 0;"
    )
);
?>

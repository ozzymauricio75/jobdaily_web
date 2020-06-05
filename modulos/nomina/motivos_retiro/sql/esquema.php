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
$tablas["motivos_retiro"] = array(
    "codigo"	  => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código interno que identifica el motivo de retiro'",
    "descripcion" => "VARCHAR(50) NOT NULL COMMENT 'Detalle que identifica el motivo de retiro'",
    "indemniza"   => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT 'Genera indemnizacion 0->No 1->Si'"
);

// Definición de llaves primarias
$llavesPrimarias["motivos_retiro"] = "codigo";


$registros["motivos_retiro"] = array(
    array(
        "codigo"        => "0",
        "descripcion"   => "",
        "indemniza"     => "0",
    )
);

// Inserción de datos iniciales***/
$registros["componentes"] = array(
    array(
        "id"            => "GESTMORE",
        "padre"         => "SUBMDCRH",
        "id_modulo"     => "NOMINA",
        "orden"         => "120",
        "visible"       => "1",
        "carpeta"       => "motivos_retiro",
        "global"        => "0",
        "archivo"       => "menu",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "ADICMORE",
        "padre"         => "GESTMORE",
        "id_modulo"     => "NOMINA",
        "orden"         => "10",
        "visible"       => "0",
        "carpeta"       => "motivos_retiro",
        "global"        => "0",
        "archivo"       => "adicionar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "CONSMORE",
        "padre"         => "GESTMORE",
        "id_modulo"     => "NOMINA",
        "orden"         => "25",
        "visible"       => "0",
        "carpeta"       => "motivos_retiro",
        "global"        => "0",
        "archivo"       => "consultar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "MODIMORE",
        "padre"         => "GESTMORE",
        "id_modulo"     => "NOMINA",
        "orden"         => "30",
        "visible"       => "0",
        "carpeta"       => "motivos_retiro",
        "global"        => "0",
        "archivo"       => "modificar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "ELIMMORE",
        "padre"         => "GESTMORE",
        "id_modulo"     => "NOMINA",
        "orden"         => "40",
        "visible"       => "0",
        "carpeta"       => "motivos_retiro",
        "global"        => "0",
        "archivo"       => "eliminar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_motivos_retiro AS
        SELECT codigo AS id,
        job_motivos_retiro.codigo AS CODIGO,
        job_motivos_retiro.descripcion AS DESCRIPCION
        FROM job_motivos_retiro
        WHERE codigo>0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_motivos_retiro AS
        SELECT codigo AS id,
        job_motivos_retiro.codigo AS CODIGO,
        job_motivos_retiro.descripcion AS DESCRIPCION
        FROM job_motivos_retiro
        WHERE codigo>0;"
    )
);
?>

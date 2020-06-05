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
$tablas["conceptos_prestamos"] = array(
    "codigo"                    => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código que identifica el concepto de prestamo'",
    "descripcion"               => "VARCHAR(255) NOT NULL COMMENT 'Detalle que describe el concepto del prestamo'"
);

// Definicion de llaves primarias
$llavesPrimarias["conceptos_prestamos"] = "codigo";

// Definicion de campos Unicos
$llavesUnicas["conceptos_prestamos"] = array(
    "descripcion"
);

$registros["conceptos_prestamos"] = array(
    array(
        "codigo"        => "0",
        "descripcion"   => ""
    )
);

// Insercion de datos iniciales
$registros["componentes"] = array(
    array(
        "id"            => "GESTCOPR",
        "padre"         => "SUBMDCRH",
        "id_modulo"     => "NOMINA",
        "orden"         => "45",
        "visible"       => "1",
        "carpeta"       => "conceptos_prestamos",
        "global"        => "0",
        "archivo"       => "menu",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "ADICCOPR",
        "padre"         => "GESTCOPR",
        "id_modulo"     => "NOMINA",
        "orden"         => "10",
        "visible"       => "0",
        "carpeta"       => "conceptos_prestamos",
        "global"        => "0",
        "archivo"       => "adicionar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "CONSCOPR",
        "padre"         => "GESTCOPR",
        "id_modulo"     => "NOMINA",
        "orden"         => "20",
        "visible"       => "0",
        "carpeta"       => "conceptos_prestamos",
        "global"        => "0",
        "archivo"       => "consultar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "MODICOPR",
        "padre"         => "GESTCOPR",
        "id_modulo"     => "NOMINA",
        "orden"         => "30",
        "visible"       => "0",
        "carpeta"       => "conceptos_prestamos",
        "global"        => "0",
        "archivo"       => "modificar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "ELIMCOPR",
        "padre"         => "GESTCOPR",
        "id_modulo"     => "NOMINA",
        "orden"         => "40",
        "visible"       => "0",
        "carpeta"       => "conceptos_prestamos",
        "global"        => "0",
        "archivo"       => "eliminar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM=MERGE VIEW job_menu_conceptos_prestamos AS
        SELECT codigo AS id,
        codigo AS CODIGO,
        descripcion AS DESCRIPCION
        FROM job_conceptos_prestamos
        WHERE codigo > 0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM=MERGE VIEW job_buscador_conceptos_prestamos AS
        SELECT codigo AS id,
        codigo AS CODIGO,
        descripcion AS DESCRIPCION
        FROM job_conceptos_prestamos
        WHERE codigo > 0;"
    )
);
?>

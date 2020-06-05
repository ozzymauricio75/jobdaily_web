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

$tablas["titulos_planillas"] = array(
    "columna"     => "SMALLINT(3) UNSIGNED NOT NULL COMMENT 'Columna en la planilla'",
    "nombre"      => "VARCHAR(15) NOT NULL COMMENT 'Nombre de la columna en la planilla'",
    "descripcion" => "VARCHAR(255) NULL DEFAULT '' COMMENT 'Descripcion de la columna en planilla'"
);

$llavesPrimarias["titulos_planillas"] = "columna";

$registros["componentes"] = array(
    array(
        "id"            => "GESTTLPL",
        "padre"         => "SUBMDCRH",
        "id_modulo"     => "NOMINA",
        "orden"         => "65",
        "visible"       => "1",
        "carpeta"       => "titulos_planillas",
        "global"        => "0",
        "archivo"       => "titulos_planillas",
        "requiere_item" => "0",
        "tipo_enlace"   => "2"
    )
);

$registros["titulos_planillas"] = array(
    array(
        "columna" => 1,
        "nombre" => $textos["DEVENGADO"]
    ),
    array(
        "columna" => 2,
        "nombre" => $textos["AUXILIO"]
    ),
    array(
        "columna" => 3,
        "nombre" => $textos["PRODUCTIVIDAD"]
    ),
    array(
        "columna" => 4,
        "nombre" => $textos["HORAS_EXTRAS"]
    ),
    array(
        "columna" => 5,
        "nombre" => $textos["INCAPACIDAD"]
    ),
    array(
        "columna" => 6,
        "nombre" => $textos["AUXILIO_VEHICULOS"]
    ),
    array(
        "columna" => 7,
        "nombre" => $textos["AUXILIO_EXTRAORDINARIO"]
    ),
    array(
        "columna" => 8,
        "nombre" => $textos["PRIMA_VACACIONES"]
    ),
    array(
        "columna" => 9,
        "nombre" => $textos["SALUD"]
    ),
    array(
        "columna" => 10,
        "nombre" => $textos["PENSION"]
    ),
    array(
        "columna" => 11,
        "nombre" => $textos["DESCUENTOS_VARIOS"]
    ),
    array(
        "columna" => 12,
        "nombre" => $textos["OTROS_DESCUENTOS"]
    )
);
?>

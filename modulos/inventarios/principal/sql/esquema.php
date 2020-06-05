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
* de APTITUD PARA UN PROPÓSITO DETERMINADO. Consulte los detalles de
* la Licencia Pública General GNU para obtener una información más
* detallada.
*
* Debería haber recibido una copia de la Licencia Pública General GNU
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
**/

/*** Inserción de datos iniciales ***/
$registros["componentes"] = array(
    array(
        "id"        => "SUBMDCIN",
        "padre"     => "MENUINVE",
        "id_modulo" => "INVENTARIO",
        "visible"   => "1",
        "orden"     => "5000",
        "carpeta"   => "principal",
        "archivo"   => "NULL"
    ),
    array(
        "id"        => "SUBMOPER",
        "padre"     => "MENUINVE",
        "id_modulo" => "INVENTARIO",
        "visible"   => "1",
        "orden"     => "4000",
        "carpeta"   => "principal",
        "archivo"   => "NULL"
    )/*,
    array(
        "id"        => "GESTARTI",
        "padre"     => "SUBMDCIN",
        "id_modulo" => "INVENTARIO",
        "orden"     => "0005",
        "carpeta"   => "articulos",
        "archivo"   => "menu"
    ),
    array(
        "id"        => "GESTGRUP",
        "padre"     => "SUBMDCIN",
        "id_modulo" => "INVENTARIO",
        "orden"     => "0015",
        "carpeta"   => "grupos",
        "archivo"   => "menu"
    ),
    array(
        "id"        => "GESTESGR",
        "padre"     => "SUBMDCIN",
        "id_modulo" => "INVENTARIO",
        "orden"     => "0020",
        "carpeta"   => "estructura_grupos",
        "archivo"   => "menu"
    ),
    array(
        "id"        => "GESTMARC",
        "padre"     => "SUBMDCIN",
        "id_modulo" => "INVENTARIO",
        "orden"     => "0025",
        "carpeta"   => "marcas",
        "archivo"   => "menu"
    ),
    array(
        "id"        => "GESTUNID",
        "padre"     => "SUBMDCIN",
        "id_modulo" => "INVENTARIO",
        "orden"     => "0035",
        "carpeta"   => "unidades",
        "archivo"   => "menu"
    )*/
);
?>

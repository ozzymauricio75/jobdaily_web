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

$borrarSiempre = false;

//  Inserción de datos iniciales
$registros["componentes"] = array(
    array(
        "id"              => "GESTTERC",
        "padre"           => "SUBMINCO",
        "id_modulo"       => "CONTABILIDAD",
        "orden"           => "0001",
        "visible"         => "1",
        "carpeta"         => "terceros",
        "archivo"         => "menu",
        "requiere_item"   => "1",
        "tabla_principal" => "terceros",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "ADICTERC",
        "padre"           => "GESTTERC",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "0",
        "orden"           => "0005",
        "carpeta"         => "terceros",
        "archivo"         => "adicionar",
        "requiere_item"   => "0",
        "tabla_principal" => "terceros",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "CONSTERC",
        "padre"           => "GESTTERC",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "0",
        "orden"           => "0010",
        "carpeta"         => "terceros",
        "archivo"         => "consultar",
        "requiere_item"   => "1",
        "tabla_principal" => "terceros",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "MODITERC",
        "padre"           => "GESTTERC",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "0",
        "orden"           => "0015",
        "carpeta"         => "terceros",
        "archivo"         => "modificar",
        "requiere_item"   => "1",
        "tabla_principal" => "terceros",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "ELIMTERC",
        "padre"           => "GESTTERC",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "0",
        "orden"           => "0020",
        "carpeta"         => "terceros",
        "archivo"         => "eliminar",
        "requiere_item"   => "1",
        "tabla_principal" => "terceros",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "LISTTERC",
        "padre"           => "GESTTERC",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "0",
        "orden"           => "0025",
        "carpeta"         => "terceros",
        "archivo"         => "listar",
        "requiere_item"   => "1",
        "tabla_principal" => "terceros",
        "tipo_enlace"     => "1"
    )
);

?>

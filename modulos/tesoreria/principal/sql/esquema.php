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

/*** Inserción de datos iniciales ***/
$registros["componentes"] = array(
    array(
        "id"              => "SUBMMOTE",
        "padre"           => "MENUTESO",
        "id_modulo"       => "TESORERIA",
        "orden"           => "3000",
        "carpeta"         => "movimientos",
        "archivo"         => "menu",
        "global"          => "0",
        "requiere_item"   => "0",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"               => "SUBMCRED",
        "padre"            => "MENUTESO",
        "id_modulo"        => "TESORERIA",
        "orden"            => "5000",
        "carpeta"          => "principal",
        "archivo"          => "NULL"
    ),
    array(
        "id"               => "SUBMDCTE",
        "padre"            => "MENUTESO",
        "id_modulo"        => "TESORERIA",
        "orden"            => "9000",
        "carpeta"          => "principal",
        "archivo"          => "NULL"
    )
);
?>

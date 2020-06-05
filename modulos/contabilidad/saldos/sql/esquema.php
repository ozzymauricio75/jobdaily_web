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


/*** Inserción de datos iniciales***/
$registros["componentes"] = array(
    array(
        "id"                => "REPOSCXC",
        "padre"             => "SUBMSADO",
        "id_modulo"         => "CONTABILIDAD",
        "visible"           => "1",
        "orden"             => "0010",
        "carpeta"           => "saldos",
        "archivo"           => "cxc",
        "requiere_item"     => "0",
        "tabla_principal"   => "",
        "tipo_enlace"       => "2"
    ), array(
        "id"                => "REPOSCXP",
        "padre"             => "SUBMSADO",
        "id_modulo"         => "CONTABILIDAD",
        "visible"           => "1",
        "orden"             => "0020",
        "carpeta"           => "saldos",
        "archivo"           => "cxp",
        "requiere_item"     => "0",
        "tabla_principal"   => "",
        "tipo_enlace"       => "2"
    ), array(
        "id"                => "REPOSMDO",
        "padre"             => "SUBMSADO",
        "id_modulo"         => "CONTABILIDAD",
        "visible"           => "1",
        "orden"             => "0030",
        "carpeta"           => "saldos",
        "archivo"           => "movimiento_documentos",
        "requiere_item"     => "0",
        "tabla_principal"   => "",
        "tipo_enlace"       => "2"
    )
);

?>

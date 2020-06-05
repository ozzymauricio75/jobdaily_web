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

$registros["componentes"] = array(
    array(
        "id"                => "REPOBALC",
        "padre"             => "SUBMBALC",
        "id_modulo"         => "CONTABILIDAD",
        "visible"           => "1",
        "orden"             => "0005",
        "carpeta"           => "balances",
        "archivo"           => "general",
        "requiere_item"     => "0",
        "tabla_principal"   => "",
        "tipo_enlace"       => "2"

    ), array(
        "id"                => "REPOBPYG",
        "padre"             => "SUBMBALC",
        "id_modulo"         => "CONTABILIDAD",
        "visible"           => "1",
        "orden"             => "0010",
        "carpeta"           => "balances",
        "archivo"           => "pyg",
        "requiere_item"     => "0",
        "tabla_principal"   => "",
        "tipo_enlace"       => "2"

    ), array(
        "id"                => "REPOBACO",
        "padre"             => "SUBMBALC",
        "id_modulo"         => "CONTABILIDAD",
        "visible"           => "1",
        "orden"             => "0015",
        "carpeta"           => "balances",
        "archivo"           => "comprobacion",
        "requiere_item"     => "0",
        "tabla_principal"   => "",
        "tipo_enlace"       => "2"
    ), array(
        "id"                => "REPOESCU",
        "padre"             => "SUBMBALC",
        "id_modulo"         => "CONTABILIDAD",
        "visible"           => "1",
        "orden"             => "0020",
        "carpeta"           => "balances",
        "archivo"           => "estado_cuenta",
        "requiere_item"     => "0",
        "tabla_principal"   => "",
        "tipo_enlace"       => "2"

    )/*, array(
        "id"                => "REPOMABA",
        "padre"             => "SUBMBALC",
        "id_modulo"         => "CONTABILIDAD",
        "visible"           => "1",
        "orden"             => "0025",
        "carpeta"           => "balances",
        "archivo"           => "reporte_mayor_balance",
        "requiere_item"     => "0",
        "tabla_principal"   => "",
        "tipo_enlace"       => "2"
    )*/
);

?>

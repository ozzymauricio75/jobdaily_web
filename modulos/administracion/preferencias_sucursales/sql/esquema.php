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

$registros["componentes"] = array(
    array(
        "id"           => "GESTPREF",
        "padre"        => "SUBMACCE",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "1",
        "orden"        => "200",
        "carpeta"      => "principal",
        "global"       => "0",
        "requiere_item" => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "PREFSUCU",
        "padre"        => "GESTPREF",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "1",
        "orden"        => "300",
        "carpeta"      => "preferencias_sucursales",
        "archivo"      => "menu",
        "global"       => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "MODIPRSU",
        "padre"        => "PREFSUCU",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "0",
        "orden"        => "300",
        "carpeta"      => "preferencias_sucursales",
        "archivo"      => "modificar",
        "global"       => "0",
        "tipo_enlace"  => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_preferencias_sucursales AS
        SELECT
            job_sucursales.codigo AS id,
            job_sucursales.nombre AS SUCURSAL,
            job_menu_empresas.RAZON_SOCIAL AS EMPRESA
        FROM
            job_sucursales,
            job_menu_empresas
        WHERE
            job_sucursales.codigo_empresa = job_menu_empresas.id;"
    )
);

?>

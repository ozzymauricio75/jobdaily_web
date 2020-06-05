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

$registros["componentes"] = array(
    array(
        "id"            => "GESTDCPL",
        "padre"         => "SUBMPRPL",
        "id_modulo"     => "NOMINA",
        "orden"         => "40",
        "visible"       => "1",
        "carpeta"       => "descontabilizar_planilla",
        "global"        => "0",
        "archivo"       => "descontabilizar_planilla",
        "requiere_item" => "0",
        "tipo_enlace"   => "2"
    ),
      array(
        "id"            => "GESTELMO",
        "padre"         => "SUBMPRPL",
        "id_modulo"     => "NOMINA",
        "orden"         => "45",
        "visible"       => "1",
        "carpeta"       => "descontabilizar_planilla",
        "global"        => "0",
        "archivo"       => "eliminar_movimientos",
        "requiere_item" => "0",
        "tipo_enlace"   => "2"
    )
);
?>

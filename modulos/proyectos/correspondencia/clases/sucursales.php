<?php

/**
*
* Copyright (C) 2007 LinuxCali Ltda
* Carlos A. Ramirez R. <carlosrr@linuxcali.com>
*
* Este archivo es parte de PROTOTIPO
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

require_once $GLOBAL["MODULOS"]."/base/clases/sql.php";
require_once $GLOBAL["MODULOS"]."/base/clases/operacion.php";
require_once $GLOBAL["MODULOS"]."/administracion/acceso/privilegios/clases/privilegio.php";
require_once $GLOBAL["MODULOS"]."/base/clases/util.php";

class Sucursal
{
    /* Agregar un nuevo sucursal a la base de datos */
    public static function agregarSucursal($params)
    {
        return Util::agregar($params, "sucursales", "Sucursal", array("codigo"));
    }

    /* Eliminar una sucursal de la base de datos */
    public static function eliminarSucursal($params)
    {
        return Util::eliminar("sucursales", $params, "Sucursal");
    }

    /* Actualizar informacion de una sucursal de la base de datos */
    public static function actualizarSucursal($params)
    {
        return Util::modificar($params, "sucursales", "Sucursal", array("codigo"));
    }

    public static function getId($codigo)
    {
        return Util::getValorColumna("id", "sucursales", "codigo", $codigo);
    }
}

?>
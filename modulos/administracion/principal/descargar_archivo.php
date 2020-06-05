<?php

/**
*
* Copyright (C) 2020 Jobdaily
* Raul Mauricio Oidor Lozano <ozzymauricio75@gmail.com>
* Juli�n A. Mondrag�n Q. <jmondragon@felinux.com.co>
*
* Este archivo es parte de:
* Jobdaily:: Sofware empresarial a la medida
*
* Este programa es software libre: usted puede redistribuirlo y/o
* modificarlo  bajo los t�rminos de la Licencia P�blica General GNU
* publicada por la Fundaci�n para el Software Libre, ya sea la versi�n 3
* de la Licencia, o (a su elecci�n) cualquier versi�n posterior.
*
* Este programa se distribuye con la esperanza de que sea �til, pero
* SIN GARANT�A ALGUNA; ni siquiera la garant�a impl�cita MERCANTIL o
* de APTITUD PARA UN PROP�SITO DETERMINADO. Consulte los detalles de
* la Licencia P�blica General GNU para obtener una informaci�n m�s
* detallada.
*
* Deber�a haber recibido una copia de la Licencia P�blica General GNU
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
**/

if (!empty($url_id)) {

    if (!isset($url_temporal)) {
        $url_temporal = 0;
    }
    $llave_archivo   = explode("|",$url_id);
    $codigo_sucursal = $llave_archivo[0];
    $consecutivo     = $llave_archivo[1];

    $consulta       = SQL::seleccionar(array("archivos"), array("nombre","descripcion"), "codigo_sucursal = '$codigo_sucursal' AND consecutivo='$consecutivo'");
    $archivo        = SQL::filaEnObjeto($consulta);
    $nombre_archivo = $rutasGlobales["archivos"] ."/". $archivo->nombre;
    $tamano         = @filesize($nombre_archivo);

    if (is_readable($nombre_archivo)) {
        $abierto    = fopen($nombre_archivo, "rb");
        header("Content-Length: $tamano");
        header("Content-Disposition: attachment; filename=$archivo->nombre");
        while (!feof($abierto)) {
            echo fread($abierto, 1024);
        }
        fclose($abierto);

        if ($url_temporal) {
            unlink($nombre_archivo);
            SQL::eliminar("archivos", "codigo_sucursal = '$codigo_sucursal' AND consecutivo='$consecutivo'");
        }
    }

    exit();
}

?>

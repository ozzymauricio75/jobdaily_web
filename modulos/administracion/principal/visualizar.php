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
    $llave = explode("|",$url_id);

    $id_asociado = $llave[0];
    $categoria   = $llave[1];

    $consulta      = SQL::seleccionar(array("imagenes"), array("tipo","contenido","extension"), "id_asociado = '$id_asociado' AND categoria='$categoria'");
    $imagen        = SQL::filaEnObjeto($consulta);
    header("Content-Length: ".strlen($imagen->contenido));
    header("Content-Type: ".$imagen->tipo);
    header("Content-Disposition: inline; filename=".$url_id.".".$imagen->extension);
    echo $imagen->contenido;
    exit();
}

?>

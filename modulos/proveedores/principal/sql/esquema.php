<?php

/**
*
* Copyright (C) 2020 Jobdaily
* Raul Mauricio Oidor Lozano <ozzymauricio75@gmail.com>
* 
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

/*** Inserci�n de datos iniciales ***/
$registros["componentes"] = array(
    array(
        "id"        => "SUBMCOMP",
        "padre"     => "MENUPROV",
        "id_modulo" => "PROVEEDORES",
        "orden"     => "1000",
        "carpeta"   => "principal",
        "archivo"   => "NULL"
    ),
    array(
        "id"        => "SUBMCUXP",
        "padre"     => "MENUPROV",
        "id_modulo" => "PROVEEDORES",
        "orden"     => "3000",
        "carpeta"   => "principal",
        "archivo"   => "NULL"
    ),
    array(
        "id"        => "SUBMDCPV",
        "padre"     => "MENUPROV",
        "id_modulo" => "PROVEEDORES",
        "orden"     => "5000",
        "carpeta"   => "principal",
        "archivo"   => "NULL"
    )
);
?>

<?php

/**
*
* Copyright (C) 2020 Jobdaily
* Raul Mauricio Oidor Lozano <ozzymauricio75@gmail.com>
* 
* Este archivo es parte de:
* Jobdaily :: Software empresarial a la medida
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

/*** Eliminar la tabla y crearla de nuevo cada vez que se ejecute el script de creaci�n ***/
$borrarSiempre   = false;

/*** Definici�n de tablas ***/
$tablas["notas"]   = array(
    "id"             => "SMALLINT(3) UNSIGNED ZEROFILL AUTO_INCREMENT NOT NULL COMMENT 'Consecutivo interno para la base de datos'",
    "codigo_usuario" => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Llave foranea del usuario al que pertenece la nota'",
    "fecha"		     => "DATETIME NOT NULL COMMENT 'Fecha y hora de creaci�n de la nota'",
    "nota"           => "VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'Contenido de la nota'"
);

/*** Definici�n de llaves primarias ***/
$llavesPrimarias["notas"]   = "id";

/*** Definici�n de llaves for�neas ***/
$llavesForaneas["notas"]   = array(
    array(
        /*** Nombre de la llave ***/
        "notas_usuarios",
        /*** Nombre del campo clave de la tabla local ***/
        "codigo_usuario",
        /*** Nombre de la tabla relacionada ***/
        "usuarios",
        /*** Nombre del campo clave en la tabla relacionada ***/
        "codigo"
    )
);

/*** Inserci�n de datos iniciales ***/
$registros["componentes"]   = array(
    array(
        "id"        => "GESTNOTA",
        "padre"     => "MENUINSE",
        "id_modulo" => "EXTENSIONES",
        "orden"     => "0100",
        "carpeta"   => "notas",
        "archivo"   => "menu"
    ),
    array(
        "id"        => "ADICNOTA",
        "padre"     => "GESTNOTA",
        "id_modulo" => "EXTENSIONES",
        "visible"   => "0",
        "orden"     => "0005",
        "carpeta"   => "notas",
        "archivo"   => "adicionar"
    ),
    array(
        "id"        => "CONSNOTA",
        "padre"     => "GESTNOTA",
        "id_modulo" => "EXTENSIONES",
        "visible"   => "0",
        "orden"     => "0010",
        "carpeta"   => "notas",
        "archivo"   => "consultar"
    ),
    array(
        "id"        => "MODINOTA",
        "padre"     => "GESTNOTA",
        "id_modulo" => "EXTENSIONES",
        "visible"   => "0",
        "orden"     => "0015",
        "carpeta"   => "notas",
        "archivo"   => "modificar"
    ),
    array(
        "id"        => "ELIMNOTA",
        "padre"     => "GESTNOTA",
        "id_modulo" => "EXTENSIONES",
        "visible"   => "0",
        "orden"     => "0020",
        "carpeta"   => "notas",
        "archivo"   => "eliminar"
    )
);

/*** Sentencias para la creaci�n de las vistas requeridas

CREATE OR REPLACE VIEW job_menu_notas AS SELECT `id` , `nota` AS NOTAS FROM `job_notas` ORDER BY `fecha` ASC;

***/

?>
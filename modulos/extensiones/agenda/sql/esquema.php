<?php

/**
*
* Copyright (C) 2020 Jobdaily
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
* SIN GARANTIA ALGUNA; ni siquiera la garant�a impl�cita MERCANTIL o
* de APTITUD PARA UN PROPOSITO DETERMINADO. Consulte los detalles de
* la Licencia P�blica General GNU para obtener una informaci�n m�s
* detallada.
*
* Deber�a haber recibido una copia de la Licencia P�blica General GNU
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
**/
$borrarSiempre   = false;

/*** Definici�n de tablas ***/
$tablas["agenda"] = array(
    "id"             => "SMALLINT(3) UNSIGNED ZEROFILL AUTO_INCREMENT NOT NULL COMMENT 'Consecutivo interno de la base de datos'",
    "codigo_usuario" => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Usuario al que pertenece el apunte de la agenda (Llave foranea tabla usuarios)'",
    "fecha"          => "DATE NOT NULL COMMENT 'Fecha de inicio del apunte'",
    "hora_inicio"    => "TIME NOT NULL COMMENT 'Hora de inicio del apunte'",
    "duracion"       => "INT( 3 ) NOT NULL COMMENT 'Tiempo de duracion del apunte'",
    "titulo"		 => "VARCHAR( 255 ) NOT NULL COMMENT 'Titulo del apunte'",
    "descripcion"	 => "VARCHAR( 255 ) NOT NULL COMMENT 'Descripci�n del apunte'"
);


/*** Definici�n de llaves primarias ***/
$llavesPrimarias["agenda"]   = "id";

/*** Definici�n de llaves for�neas ***/
$llavesForaneas["agenda"]   = array(
    array(
        /*** Nombre de la llave ***/
        "agenda_usuarios",
        /*** Nombre del campo clave de la tabla local ***/
        "codigo_usuario",
        /*** Nombre de la tabla relacionada ***/
        "usuarios",
        /*** Nombre del campo clave en la tabla relacionada ***/
        "codigo"
    )
);

/*** Inserci�n de datos iniciales ***/
$registros["componentes"] = array(
    array(
        "id"        => "GESTAGEN",
        "padre"     => "MENUINSE",
        "id_modulo" => "EXTENSIONES",
        "orden"     => "0001",
        "visible"   => "0",
        "carpeta"   => "agenda",
        "archivo"   => "menu"
    ),
    array(
        "id"        => "ADICAGEN",
        "padre"     => "GESTAGEN",
        "id_modulo" => "EXTENSIONES",
        "visible"   => "0",
        "orden"     => "0005",
        "carpeta"   => "agenda",
        "archivo"   => "adicionar"
    ),
    array(
        "id"        => "CONSAGEN",
        "padre"     => "GESTAGEN",
        "id_modulo" => "EXTENSIONES",
        "visible"   => "0",
        "orden"     => "0010",
        "carpeta"   => "agenda",
        "archivo"   => "consultar"
    ),
    array(
        "id"        => "MODIAGEN",
        "padre"     => "GESTAGEN",
        "id_modulo" => "EXTENSIONES",
        "visible"   => "0",
        "orden"     => "0015",
        "carpeta"   => "agenda",
        "archivo"   => "modificar"
    ),
    array(
        "id"        => "ELIMAGEN",
        "padre"     => "GESTAGEN",
        "id_modulo" => "EXTENSIONES",
        "visible"   => "0",
        "orden"     => "0020",
        "carpeta"   => "agenda",
        "archivo"   => "eliminar"
    )
);

/*** Sentencia para la creaci�n de la vista requerida ***/
/*** 
CREATE OR REPLACE VIEW `job_menu_agenda` AS
SELECT `id` , `hora_inicio` AS HORA_INICIO, `titulo` AS TITULO
FROM `job_agenda`;

CREATE OR REPLACE VIEW `job_buscador_agenda` AS
SELECT `id` , `fecha` , `hora_inicio` , `titulo`
FROM `job_agenda`;

***/
?>

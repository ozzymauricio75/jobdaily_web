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

// Eliminar la tabla y crearla de nuevo cada vez que se ejecute el script de creaci�n
$borrarSiempre = false;

// Definici�n de tablas
$tablas ["consecutivo_documentos"] = array(
    "codigo_sucursal"             => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'C�digo de la sucursal a la cual pertenece'",
    "codigo_tipo_documento"       => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo del tipo de documento'",
    "documento_identidad_tercero" => "VARCHAR(12) NOT NULL COMMENT 'N�mero del documento de identidad'",
    "fecha_registro"              => "DATE NOT NULL COMMENT 'Fecha de generacion del documento'",
    "consecutivo"                 => "INT(8) UNSIGNED NOT NULL COMMENT 'Numero consecutivo'",
    "id_tabla"                    => "SMALLINT(5) UNSIGNED NOT NULL COMMENT 'id de la tabla que genera el documento'",
    "llave_tabla"                 => "VARCHAR(255) NOT NULL COMMENT 'Llave de tabla que genero el documento'",
    "codigo_sucursal_archivo"     => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'C�digo de la sucursal que genera el archivo'",
    "consecutivo_archivo"         => "INT(9) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo interno para la base de datos del archivo'"
);

// Definici�n de llaves primarias
$llavesPrimarias["consecutivo_documentos"] = "codigo_sucursal,codigo_tipo_documento,documento_identidad_tercero,fecha_registro,consecutivo";

//  Definici�n de llaves for�neas
$llavesForaneas["consecutivo_documentos"] = array(
    array(
        // Nombre de la llave
        "consecutivo_documentos_sucursal",
        // Nombre del campo clave de la tabla local
        "codigo_sucursal",
        // Nombre de la tabla relacionada
        "sucursales",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave
        "consecutivo_documentos_tipos_documentos",
        // Nombre del campo clave de la tabla local
        "codigo_tipo_documento",
        // Nombre de la tabla relacionada
        "tipos_documentos",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave
        "consecutivo_documentos_tablas",
        // Nombre del campo clave de la tabla local
        "id_tabla",
        // Nombre de la tabla relacionada
        "tablas",
        // Nombre del campo clave en la tabla relacionada
        "id"
    ),
    array(
        // Nombre de la llave
        "consecutivo_documentos_archivos",
        // Nombre del campo clave de la tabla local
        "codigo_sucursal_archivo,consecutivo_archivo",
        // Nombre de la tabla relacionada
        "archivos",
        // Nombre del campo clave en la tabla relacionada
        "codigo_sucursal,consecutivo"
    ),
    array(
        // Nombre de la llave foranea
        "consecutivo_documentos_tercero",
        // Nombre del campo en la tabla actual
        "documento_identidad_tercero",
        // Nombre de la tabla relacionada
        "terceros",
        // Nombre del campo de la tabla relacionada
        "documento_identidad"
    )
);

?>

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

// Eliminar la tabla y crearla de nuevo cada vez que se ejecute el script de creación
$borrarSiempre = false;

// Definición de tablas
$tablas ["consecutivo_documentos"] = array(
    "codigo_sucursal"             => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código de la sucursal a la cual pertenece'",
    "codigo_tipo_documento"       => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo del tipo de documento'",
    "documento_identidad_tercero" => "VARCHAR(12) NOT NULL COMMENT 'Número del documento de identidad'",
    "fecha_registro"              => "DATE NOT NULL COMMENT 'Fecha de generacion del documento'",
    "consecutivo"                 => "INT(8) UNSIGNED NOT NULL COMMENT 'Numero consecutivo'",
    "id_tabla"                    => "SMALLINT(5) UNSIGNED NOT NULL COMMENT 'id de la tabla que genera el documento'",
    "llave_tabla"                 => "VARCHAR(255) NOT NULL COMMENT 'Llave de tabla que genero el documento'",
    "codigo_sucursal_archivo"     => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código de la sucursal que genera el archivo'",
    "consecutivo_archivo"         => "INT(9) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo interno para la base de datos del archivo'"
);

// Definición de llaves primarias
$llavesPrimarias["consecutivo_documentos"] = "codigo_sucursal,codigo_tipo_documento,documento_identidad_tercero,fecha_registro,consecutivo";

//  Definición de llaves foráneas
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

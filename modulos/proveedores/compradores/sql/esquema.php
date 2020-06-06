<?php

/**
*
* Copyright (C) 2020 Raul Mauricio Oidor Lozano
* Raul Mauricio Oidor Lozano <ozzymauricio75@gmail.com>
*
* Este archivo es parte de:
* PANCE :: Software empresarial a la medida
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
$borrarSiempre = array();

$borrarSiempre["compradores"] = false;
$tablas["compradores"] = array(
    "codigo"                     => "INT(9) UNSIGNED ZEROFILL AUTO_INCREMENT NOT NULL COMMENT 'Llave principal'",
    "documento_tercero"          => "VARCHAR(12) NOT NULL COMMENT 'Id de la tabla terceros'",
    "activo"                     => "ENUM('0','1') NOT NULL DEFAULT '1' COMMENT 'El comprador está activo 0=No, 1=Si'",
    "id_usuario_registra"        => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL DEFAULT '0' COMMENT 'Id del usuario que genera el registro'",
    "fecha_registra"             => "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Fecha ingreso al sistema'",
    "fecha_modificacion"         => "TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Fecha ultima modificación'"
);

$llavesPrimarias["compradores"] = "codigo";

$llavesForaneas["compradores"] = array(
    array(
        // Nombre de la llave
        "compradores_documento_tercero",
        // Nombre del campo clave de la tabla local
        "documento_tercero",
        // Nombre de la tabla relacionada
        "terceros",
        // Nombre del campo clave en la tabla relacionada
        "documento_identidad"
    ),
    array(
        // Nombre de la llave
        "compradores_usuario_registra",
        // Nombre del campo clave de la tabla local
        "id_usuario_registra",
        // Nombre de la tabla relacionada
        "usuarios",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave
        "compradores_tercero",
        // Nombre del campo clave de la tabla local
        "documento_tercero",
        // Nombre de la tabla relacionada
        "terceros",
        // Nombre del campo clave en la tabla relacionada
        "documento_identidad"
    )
);

//  Inserción de datos iniciales
$registros["componentes"] = array(
    array(
        "id"              => "GESTCMPR",
        "padre"           => "SUBMDCPV",
        "id_modulo"       => "PROVEEDORES",
        "orden"           => "38",
        "visible"         => "1",
        "carpeta"         => "compradores",
        "archivo"         => "menu"
    ),
    array(
        "id"              => "ADICCMPR",
        "padre"           => "GESTCMPR",
        "id_modulo"       => "PROVEEDORES",
        "visible"         => "0",
        "orden"           => "0005",
        "carpeta"         => "compradores",
        "archivo"         => "adicionar"
    ),
    array(
        "id"              => "CONSCMPR",
        "padre"           => "GESTCMPR",
        "id_modulo"       => "PROVEEDORES",
        "visible"         => "0",
        "orden"           => "0010",
        "carpeta"         => "compradores",
        "archivo"         => "consultar"
    ),
    array(
        "id"              => "MODICMPR",
        "padre"           => "GESTCMPR",
        "id_modulo"       => "PROVEEDORES",
        "visible"         => "0",
        "orden"           => "0015",
        "carpeta"         => "compradores",
        "archivo"         => "modificar"
    ),
    array(
        "id"              => "ELIMCMPR",
        "padre"           => "GESTCMPR",
        "id_modulo"       => "PROVEEDORES",
        "visible"         => "0",
        "orden"           => "0020",
        "carpeta"         => "compradores",
        "archivo"         => "eliminar"
    ),
    array(
        "id"              => "LISTCMPR",
        "padre"           => "GESTCMPR",
        "id_modulo"       => "PROVEEDORES",
        "visible"         => "0",
        "orden"           => "0025",
        "carpeta"         => "compradores"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_compradores AS
        SELECT
            job_terceros.documento_identidad AS id,
            job_compradores.activo AS id_activo,
            job_terceros.documento_identidad AS DOCUMENTO_IDENTIDAD,
            CONCAT(IF(job_terceros.primer_nombre IS NOT NULL,job_terceros.primer_nombre,''),' ',
                    IF(job_terceros.segundo_nombre IS NOT NULL,job_terceros.segundo_nombre,''),' ',
                    IF(job_terceros.primer_apellido IS NOT NULL,job_terceros.primer_apellido,''),' ',
                    IF(job_terceros.segundo_apellido IS NOT NULL,job_terceros.segundo_apellido,''),' ',
                    IF(job_terceros.razon_social IS NOT NULL,job_terceros.razon_social,''))AS NOMBRE_COMPLETO,
            IF (job_compradores.activo = '1',
                'Activo',
                'Inactivo'
            ) AS ACTIVO
        FROM
            job_terceros,
            job_compradores
        WHERE
            job_compradores.documento_tercero = job_terceros.documento_identidad AND
            job_terceros.comprador > 0
        ORDER BY
            job_terceros.primer_nombre, job_terceros.razon_social;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_compradores AS
        SELECT
            job_terceros.documento_identidad AS id,
            job_compradores.activo AS id_activo,
            job_terceros.documento_identidad AS DOCUMENTO_IDENTIDAD,
            CONCAT(IF(job_terceros.primer_nombre IS NOT NULL,job_terceros.primer_nombre,''),' ',
                    IF(job_terceros.segundo_nombre IS NOT NULL,job_terceros.segundo_nombre,''),' ',
                    IF(job_terceros.primer_apellido IS NOT NULL,job_terceros.primer_apellido,''),' ',
                    IF(job_terceros.segundo_apellido IS NOT NULL,job_terceros.segundo_apellido,''),' ',
                    IF(job_terceros.razon_social IS NOT NULL,job_terceros.razon_social,''))AS NOMBRE_COMPLETO,
            IF (job_compradores.activo = '1',
                'Activo',
                'Inactivo'
            ) AS ACTIVO
        FROM
            job_terceros,
            job_compradores
        WHERE
            job_compradores.documento_tercero = job_terceros.documento_identidad AND
            job_terceros.comprador > 0
        ORDER BY
            job_terceros.primer_nombre, job_terceros.razon_social;"
    ),
    /*array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_compradores AS
        SELECT  job_compradores.id AS id,
            job_compradores.activo AS id_activo,
            CONCAT(
                job_terceros.documento_identidad, ', ',
                if(job_terceros.primer_nombre is not null, CONCAT(job_terceros.primer_nombre, ' '), ''),
                if(job_terceros.segundo_nombre is not null, CONCAT(job_terceros.segundo_nombre, ' '), ''),
                if(job_terceros.primer_apellido is not null, CONCAT(job_terceros.primer_apellido, ' '), ''),
                if(job_terceros.segundo_apellido is not null, CONCAT(job_terceros.segundo_apellido, ' '), ''),
                if(job_terceros.razon_social is not null, job_terceros.razon_social, ''),
                '|',job_terceros.id
            ) AS nombre

        FROM
            job_terceros,
            job_compradores
        WHERE
            job_compradores.id_tercero = job_terceros.id AND
            job_compradores.id
        ORDER BY
            job_terceros.primer_nombre ASC;"
    )*/
);
?>

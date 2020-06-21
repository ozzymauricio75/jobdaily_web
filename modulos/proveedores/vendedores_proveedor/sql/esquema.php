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

$borrarSiempre["vendedores_proveedor"] = false;
$tablas["vendedores_proveedor"] = array(
    "codigo"                     => "INT(6) UNSIGNED ZEROFILL AUTO_INCREMENT NOT NULL COMMENT 'Codigo interno'",
    "documento_proveedor"        => "VARCHAR(12) NOT NULL COMMENT 'Id de la tabla terceros, Llave principal'",
    "primer_nombre"              => "VARCHAR(15) DEFAULT NULL COMMENT 'Primer nombre (persona natural)'",
    "segundo_nombre"             => "VARCHAR(15) DEFAULT NULL COMMENT 'Segundo nombre (persona natural)'",
    "primer_apellido"            => "VARCHAR(20) DEFAULT NULL COMMENT 'Primer apellido (persona natural)'",
    "segundo_apellido"           => "VARCHAR(20) DEFAULT NULL COMMENT 'Segundo apellido (persona natural)'",
    "celular"                    => "VARCHAR(20) DEFAULT NULL COMMENT 'Número de celular'",
    "correo"                     => "VARCHAR(255) DEFAULT NULL COMMENT 'Direccion de correo electronico'",
    "activo"                     => "ENUM('0','1') NOT NULL DEFAULT '1' COMMENT 'El comprador esta activo 0=No, 1=Si'",
    "id_usuario_registra"        => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL DEFAULT '0' COMMENT 'Id del usuario que genera el registro'",
    "fecha_registra"             => "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Fecha ingreso al sistema'",
    "fecha_modificacion"         => "TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Fecha ultima modificación'"
);

$llavesPrimarias["vendedores_proveedor"] = "codigo,documento_proveedor,primer_nombre,primer_apellido";

$llavesForaneas["vendedores_proveedor"] = array(
    array(
        // Nombre de la llave
        "vendedores_proveedor_documento_tercero",
        // Nombre del campo clave de la tabla local
        "documento_proveedor",
        // Nombre de la tabla relacionada
        "terceros",
        // Nombre del campo clave en la tabla relacionada
        "documento_identidad"
    ),
    array(
        // Nombre de la llave
        "vendedores_proveedor_usuario_registra",
        // Nombre del campo clave de la tabla local
        "id_usuario_registra",
        // Nombre de la tabla relacionada
        "usuarios",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    ),
);

//  Inserción de datos iniciales
$registros["componentes"] = array(
    array(
        "id"              => "GESTVPRO",
        "padre"           => "SUBMDCPV",
        "id_modulo"       => "PROVEEDORES",
        "orden"           => "12",
        "visible"         => "1",
        "carpeta"         => "vendedores_proveedor",
        "archivo"         => "menu"
    ),
    array(
        "id"              => "ADICVDPR",
        "padre"           => "GESTVPRO",
        "id_modulo"       => "PROVEEDORES",
        "visible"         => "0",
        "orden"           => "0005",
        "carpeta"         => "vendedores_proveedor",
        "archivo"         => "adicionar"
    ),
    array(
        "id"              => "CONSVDPR",
        "padre"           => "GESTVPRO",
        "id_modulo"       => "PROVEEDORES",
        "visible"         => "0",
        "orden"           => "0010",
        "carpeta"         => "vendedores_proveedor",
        "archivo"         => "consultar"
    ),
    array(
        "id"              => "MODIVDPR",
        "padre"           => "GESTVPRO",
        "id_modulo"       => "PROVEEDORES",
        "visible"         => "0",
        "orden"           => "0015",
        "carpeta"         => "vendedores_proveedor",
        "archivo"         => "modificar"
    ),
    array(
        "id"              => "ELIMVDPR",
        "padre"           => "GESTVPRO",
        "id_modulo"       => "PROVEEDORES",
        "visible"         => "0",
        "orden"           => "0020",
        "carpeta"         => "vendedores_proveedor",
        "archivo"         => "eliminar"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_vendedores_proveedor AS
        SELECT
            job_vendedores_proveedor.codigo AS id,
            CONCAT(IF(job_vendedores_proveedor.primer_nombre IS NOT NULL,job_vendedores_proveedor.primer_nombre,''),' ',
                    IF(job_vendedores_proveedor.segundo_nombre IS NOT NULL,job_vendedores_proveedor.segundo_nombre,''),' ',
                    IF(job_vendedores_proveedor.primer_apellido IS NOT NULL,job_vendedores_proveedor.primer_apellido,''),' ',
                    IF(job_vendedores_proveedor.segundo_apellido IS NOT NULL,job_vendedores_proveedor.segundo_apellido,''))
                    AS NOMBRE_COMPLETO,
            CONCAT(IF(job_terceros.primer_nombre IS NOT NULL,job_terceros.primer_nombre,''),' ',
                    IF(job_terceros.segundo_nombre IS NOT NULL,job_terceros.segundo_nombre,''),' ',
                    IF(job_terceros.primer_apellido IS NOT NULL,job_terceros.primer_apellido,''),' ',
                    IF(job_terceros.segundo_apellido IS NOT NULL,job_terceros.segundo_apellido,''),' ',
                    IF(job_terceros.razon_social IS NOT NULL,job_terceros.razon_social,''))AS PROVEEDOR,
                    job_terceros.documento_identidad AS DOCUMENTO,
            IF (job_vendedores_proveedor.activo = '1',
                'Activo',
                'Inactivo'
            ) AS ACTIVO
        FROM
            job_terceros,
            job_vendedores_proveedor
        WHERE
            job_vendedores_proveedor.documento_proveedor = job_terceros.documento_identidad AND
            job_vendedores_proveedor.codigo > '0'
        ORDER BY
            job_vendedores_proveedor.primer_nombre;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_vendedores_proveedor AS
        SELECT
            job_vendedores_proveedor.codigo AS id,
            CONCAT(IF(job_vendedores_proveedor.primer_nombre IS NOT NULL,job_vendedores_proveedor.primer_nombre,''),' ',
                    IF(job_vendedores_proveedor.segundo_nombre IS NOT NULL,job_vendedores_proveedor.segundo_nombre,''),' ',
                    IF(job_vendedores_proveedor.primer_apellido IS NOT NULL,job_vendedores_proveedor.primer_apellido,''),' ',
                    IF(job_vendedores_proveedor.segundo_apellido IS NOT NULL,job_vendedores_proveedor.segundo_apellido,''))
                    AS vendedor,
            CONCAT(IF(job_terceros.primer_nombre IS NOT NULL,job_terceros.primer_nombre,''),' ',
                    IF(job_terceros.segundo_nombre IS NOT NULL,job_terceros.segundo_nombre,''),' ',
                    IF(job_terceros.primer_apellido IS NOT NULL,job_terceros.primer_apellido,''),' ',
                    IF(job_terceros.segundo_apellido IS NOT NULL,job_terceros.segundo_apellido,''),' ',
                    IF(job_terceros.razon_social IS NOT NULL,job_terceros.razon_social,''))AS proveedor,
                    job_terceros.documento_identidad AS documento_proveedor,
            IF (job_vendedores_proveedor.activo = '1',
                'Activo',
                'Inactivo'
            ) AS activo
        FROM
            job_terceros,
            job_vendedores_proveedor
        WHERE
            job_vendedores_proveedor.documento_proveedor = job_terceros.documento_identidad AND
            job_vendedores_proveedor.codigo > 0
        ORDER BY
            job_vendedores_proveedor.primer_nombre;"
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

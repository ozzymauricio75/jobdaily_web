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
/*
// Eliminar la tabla y crearla de nuevo cada vez que se ejecute el script de creación
$borrarSiempre = false;

// Definición de tablas
$tablas["referencias_proveedor"] = array(
    "codigo_articulo"               => "VARCHAR(20) NOT NULL  COMMENT 'Codigo de la tabla de articulos'",
    "referencia"                    => "VARCHAR(30) NOT NULL COMMENT 'Referencia ó codigo asignada por el proveedor, puede ser el codigo del articulo'",
    "documento_identidad_proveedor" => "VARCHAR(12) NOT NULL COMMENT 'Documento identidad del proveedor'",
    "codigo_barras"                 => "BIGINT(13) NOT NULL COMMENT 'Codigo de barras del articulo(EAN 13)'",
    "principal"                     => "ENUM('0','1') NOT NULL COMMENT 'Referencia principal 0->No 1->Si'"
);

// Definición de llaves primarias
$llavesPrimarias["referencias_proveedor"] = "codigo_articulo,referencia,documento_identidad_proveedor";

$llavesForaneas["referencias_proveedor"] = array(
    array(
        // Nombre de la llave
        "referencia_articulo",
        // Nombre del campo clave de la tabla local
        "codigo_articulo",
        // Nombre de la tabla relacionada
        "articulos",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave
        "referencia_tercero",
        // Nombre del campo clave de la tabla local
        "documento_identidad_proveedor",
        // Nombre de la tabla relacionada
        "proveedores",
        // Nombre del campo clave en la tabla relacionada
        "documento_identidad"
    )
);

// Inserción de datos iniciales
$registros["componentes"]= array(
    array(
        "id"        => "GESTREFE",
        "padre"     => "SUBMDCIN",
        "id_modulo" => "INVENTARIO",
        "visible"   => "1",
        "orden"     => "20",
        "carpeta"   => "referencias",
        "archivo"   => "menu"
    ),
    array(
        "id"        => "ADICREFE",
        "padre"     => "GESTREFE",
        "id_modulo" => "INVENTARIO",
        "visible"   => "0",
        "orden"     => "10",
        "carpeta"   => "referencias",
        "archivo"   => "adicionar"
    ),
    array(
        "id"        => "CONSREFE",
        "padre"     => "GESTREFE",
        "id_modulo" => "INVENTARIO",
        "visible"   => "0",
        "orden"     => "20",
        "carpeta"   => "referencias",
        "archivo"   => "consultar"
    ),
    array(
        "id"        => "MODIREFE",
        "padre"     => "GESTREFE",
        "id_modulo" => "INVENTARIO",
        "visible"   => "0",
        "orden"     => "30",
        "carpeta"   => "referencias",
        "archivo"   => "modificar"
    ),
    array(
        "id"        => "ELIMREFE",
        "padre"     => "GESTREFE",
        "id_modulo" => "INVENTARIO",
        "visible"   => "0",
        "orden"     => "40",
        "carpeta"   => "referencias",
        "archivo"   => "eliminar"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_referencias_por_proveedor AS SELECT 
            CONCAT(job_referencias_por_proveedor.codigo_articulo,'|',job_referencias_por_proveedor.referencia,"|",job_terceros.documento_identidad) AS id,
            job_articulos.codigo AS CODIGO_ARTICULO,
            job_terceros.documento_identidad AS DOCUMENTO_PROVEEDOR,
            CONCAT(
                if(job_terceros.primer_nombre is not null,job_terceros.primer_nombre,''),
                ' ', 
                if(job_terceros.segundo_nombre is not null,job_terceros.segundo_nombre,''),
                ' ',
                if(job_terceros.primer_apellido is not null,job_terceros.primer_apellido,''),
                ' ',
                if(job_terceros.segundo_apellido is not null,job_terceros.segundo_apellido,''),
                ' ',
                if(job_terceros.razon_social is not null,job_terceros.razon_social,'')
            )  AS PROVEEDOR,
            job_referencias_por_proveedor.referencia AS REFERENCIA,
            job_referencias_por_proveedor.codigo_barras AS CODIGO_BARRAS
            FROM 
                job_referencias_por_proveedor, job_terceros, job_proveedores, job_articulos
            WHERE
                job_referencias_por_proveedor.codigo_articulo = job_articulos.codigo AND
                job_proveedores.documento_identidad = job_terceros.documento_identidad AND
                job_referencias_por_proveedor.documento_identidad_proveedor = job_proveedores.documento_identidad;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_referencias_por_proveedor AS SELECT
            CONCAT(job_referencias_por_proveedor.codigo_articulo,'|',job_referencias_por_proveedor.referencia,"|",job_terceros.documento_identidad) AS id,
            job_terceros.documento_identidad AS documento_identidad,
            job_articulos.codigo AS codigo,
            job_referencias_por_proveedor.referencia AS referencia,
            CONCAT(
                if(job_terceros.primer_nombre is not null,job_terceros.primer_nombre,''),
                ' ',
                if(job_terceros.segundo_nombre is not null,job_terceros.segundo_nombre,''),
                ' ',
                if(job_terceros.primer_apellido is not null,job_terceros.primer_apellido,''),
                ' ', 
                if(job_terceros.segundo_apellido is not null,job_terceros.segundo_apellido,''),
                ' ',
                if(job_terceros.razon_social is not null,job_terceros.razon_social,' ')
            ) AS proveedor
            FROM 
                job_referencias_por_proveedor, job_terceros, job_proveedores, job_articulos
            WHERE
                job_referencias_por_proveedor.codigo_articulo = job_articulos.codigo AND
                job_proveedores.documento_identidad = job_terceros.documento_identidad AND
                job_referencias_por_proveedor.documento_identidad_proveedor = job_proveedores.documento_identidad;"
    )
);*/
// Definición de vistas
/***
    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_referencias_por_proveedor AS SELECT 
        CONCAT(job_referencias_por_proveedor.codigo_articulo,'|',job_referencias_por_proveedor.referencia,"|",job_terceros.documento_identidad) AS id,
        job_articulos.codigo AS CODIGO_ARTICULO,
        job_terceros.documento_identidad AS DOCUMENTO_PROVEEDOR,
        CONCAT(
            if(job_terceros.primer_nombre is not null,job_terceros.primer_nombre,''),
            ' ', 
            if(job_terceros.segundo_nombre is not null,job_terceros.segundo_nombre,''),
            ' ',
            if(job_terceros.primer_apellido is not null,job_terceros.primer_apellido,''),
            ' ',
            if(job_terceros.segundo_apellido is not null,job_terceros.segundo_apellido,''),
            ' ',
            if(job_terceros.razon_social is not null,job_terceros.razon_social,'')
        )  AS PROVEEDOR,
        job_referencias_por_proveedor.referencia AS REFERENCIA,
        job_referencias_por_proveedor.codigo_barras AS CODIGO_BARRAS
        FROM 
            job_referencias_por_proveedor, job_terceros, job_proveedores, job_articulos
        WHERE
            job_referencias_por_proveedor.codigo_articulo = job_articulos.codigo AND
            job_proveedores.documento_identidad = job_terceros.documento_identidad AND
            job_referencias_por_proveedor.documento_identidad_proveedor = job_proveedores.documento_identidad;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_referencias_por_proveedor AS SELECT
        CONCAT(job_referencias_por_proveedor.codigo_articulo,'|',job_referencias_por_proveedor.referencia,"|",job_terceros.documento_identidad) AS id,
        job_terceros.documento_identidad AS documento_identidad,
        job_articulos.codigo AS codigo,
        job_referencias_por_proveedor.referencia AS referencia,
        CONCAT(
            if(job_terceros.primer_nombre is not null,job_terceros.primer_nombre,''),
            ' ',
            if(job_terceros.segundo_nombre is not null,job_terceros.segundo_nombre,''),
            ' ',
            if(job_terceros.primer_apellido is not null,job_terceros.primer_apellido,''),
            ' ', 
            if(job_terceros.segundo_apellido is not null,job_terceros.segundo_apellido,''),
            ' ',
            if(job_terceros.razon_social is not null,job_terceros.razon_social,' ')
        ) AS proveedor
        FROM 
            job_referencias_por_proveedor, job_terceros, job_proveedores, job_articulos
        WHERE
            job_referencias_por_proveedor.codigo_articulo = job_articulos.codigo AND
            job_proveedores.documento_identidad = job_terceros.documento_identidad AND
            job_referencias_por_proveedor.documento_identidad_proveedor = job_proveedores.documento_identidad;
***/
?>

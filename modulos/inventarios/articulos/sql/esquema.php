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

$borrarSiempre = false;

// Definición de tablas

$tablas["articulos"] = array(
    "codigo"                        => "INT(9) UNSIGNED ZEROFILL AUTO_INCREMENT NOT NULL COMMENT 'Codigo interno'",
    "descripcion"                   => "VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'Descripcion del arti­culo'",
    "tipo_articulo"                 => "ENUM('1','2') NOT NULL DEFAULT '1' COMMENT '1->Materia prima, 2->Producto terminado'",
    "ficha_tecnica"                 => "TEXT NOT NULL DEFAULT '' COMMENT 'Caracteristicas tecnicas de un articulo'",
    "alto"                          => "INT(8) UNSIGNED COMMENT 'Altura del producto en milimetros'",
    "ancho"                         => "INT(8) UNSIGNED COMMENT 'Ancho del producto en milimetros'",
    "profundidad"                   => "INT(8) UNSIGNED COMMENT 'Profundidad del producto en milimetros'",
    "peso"                          => "INT(8) UNSIGNED COMMENT 'Peso del producto en gramos'",   
    "garantia"                      => "VARCHAR(255) NULL COMMENT 'Detalle que describe la garantia de un producto'",
    "garantia_partes"               => "VARCHAR(255) NULL COMMENT 'Detalle que describe la garantia de las partes mas importantes'",
    "codigo_impuesto_compra"        => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL DEFAULT '000' COMMENT 'llave foranea a la tabla tasas'",
    "codigo_impuesto_venta"         => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL DEFAULT '000' COMMENT 'llave foranea a la tabla tasas'",
    "codigo_marca"                  => "SMALLINT(4) UNSIGNED ZEROFILL NULL DEFAULT '0000' COMMENT 'llave foranea a la tabla marcas'",
    "codigo_estructura_grupo"       => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL DEFAULT '0000' COMMENT 'llave foranea a la tabla estructura de grupos'",
    "manejo_inventario"             => "ENUM('1','2') NOT NULL DEFAULT '1' COMMENT 'Manejo inventario: 1->Inventario valorizado 2->Solo maneja Kardex'",
    "detalle_kardex"                => "ENUM('1','2') NOT NULL COMMENT '1->Lleva kardex por codigo interno, 2->Lleva kardex por serie'",
    "codigo_unidad_venta"           => "INT(6) UNSIGNED ZEROFILL NOT NULL DEFAULT '000' COMMENT 'Llave foranea a la tabla unidades'",
    "codigo_unidad_compra"          => "INT(6) UNSIGNED ZEROFILL NOT NULL DEFAULT '000' COMMENT 'Llave foranea a la tabla unidades'",
    "codigo_unidad_presentacion"    => "INT(6) UNSIGNED ZEROFILL NOT NULL DEFAULT '000' COMMENT 'Llave foranea a la tabla unidades'",
    "codigo_iso"                    => "VARCHAR(2) NOT NULL COMMENT 'Llave foranea a la tabla paises'",
    "activo"                        => "ENUM('0','1') NOT NULL DEFAULT '1' COMMENT '0->El codigo esta inactivo, 1->El codigo esta activo'",
    "imprime_listas"                => "ENUM('1','2','3') NOT NULL DEFAULT '1' COMMENT '1->Siempre imprime, 2->Solo sí hay existencia, 3->Nunca imprime'",
    "fecha_creacion"                => "DATE NULL COMMENT 'Fecha en la cual se crea el articulo'"
);

// Definición de llaves primarias
$llavesPrimarias["articulos"] = "codigo";

// Definición de llaves unicas
$llavesPrimarias["articulos"] = "codigo,descripcion";

// Definición de llaves foráneas
$llavesForaneas["articulos"] = array(
    array(
        // Nombre de la llave
        "articulo_estructura",
        // Nombre del campo de la tabla local
        "codigo_estructura_grupo",
        // Nombre de la tabla relacionada
        "estructura_grupos",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave
        "articulo_marca",
        // Nombre del campo clave de la tabla local
        "codigo_marca",
        // Nombre de la tabla relacionada
        "marcas",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave
        "articulo_tasa_compra",
        // Nombre del campo clave de la tabla local
        "codigo_impuesto_compra",
        // Nombre de la tabla relacionada
        "tasas",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave
        "articulo_tasa_venta",
        // Nombre del campo clave de la tabla local
        "codigo_impuesto_venta",
        // Nombre de la tabla relacionada
        "tasas",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave
        "articulo_unidad_venta",
        // Nombre del campo clave de la tabla local
        "codigo_unidad_venta",
        // Nombre de la tabla relacionada
        "unidades",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave
        "articulo_unidad_compra",
        // Nombre del campo clave de la tabla local
        "codigo_unidad_compra",
        // Nombre de la tabla relacionada
        "unidades",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave
        "articulo_unidad_presentacion",
        // Nombre del campo clave de la tabla local
        "codigo_unidad_presentacion",
        // Nombre de la tabla relacionada
        "unidades",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
     ),
     array(
        // Nombre de la llave
        "articulo_pais",
        // Nombre del campo clave de la tabla local
        "codigo_iso",
        // Nombre de la tabla relacionada
        "paises",
        // Nombre del campo clave en la tabla relacionada
        "codigo_iso"
      )
);

// Definición de tablas
$tablas["referencias_proveedor"] = array(
    "codigo_articulo"               => "INT(9) UNSIGNED ZEROFILL AUTO_INCREMENT NOT NULL COMMENT 'Codigo interno'",
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

// Definición de tablas
$tablas["articulos_proveedor"] = array(
    "codigo_articulo"               => "INT(9) UNSIGNED ZEROFILL AUTO_INCREMENT NOT NULL COMMENT 'Codigo interno'",
    "documento_identidad_proveedor" => "VARCHAR(12) NOT NULL COMMENT 'Documento identidad del proveedor'",
    "fecha_modificacion"            => "DATE NULL COMMENT 'Fecha en la cual se modifica el articulo'"
);

// Definición de llaves primarias
$llavesPrimarias["articulos_proveedor"] = "codigo_articulo,documento_identidad_proveedor";

$llavesForaneas["articulos_proveedor"] = array(
    array(
        // Nombre de la llave
        "codigo_articulo",
        // Nombre del campo clave de la tabla local
        "codigo_articulo",
        // Nombre de la tabla relacionada
        "articulos",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave
        "articulo_tercero",
        // Nombre del campo clave de la tabla local
        "documento_identidad_proveedor",
        // Nombre de la tabla relacionada
        "proveedores",
        // Nombre del campo clave en la tabla relacionada
        "documento_identidad"
    )
);

// Definición de tablas
$tablas["lista_precio_articulos"] = array(
    "codigo"                  => "INT(9) UNSIGNED ZEROFILL AUTO_INCREMENT NOT NULL COMMENT 'Llave primaria'",
    "codigo_articulo"         => "INT(9) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo interno'",
    "fecha"                   => "DATE NOT NULL COMMENT 'Fecha en que inicia la lista de precio'",
    "costo"                   => "DECIMAL(11,2) NOT NULL COMMENT 'Precio'",
    "codigo_usuario_registra" => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL DEFAULT '0' COMMENT 'Id del usuario que genera el registro'",
    "fecha_registra"          => "DATETIME NOT NULL COMMENT 'Fecha ingreso al sistema'",
    "fecha_modificacion"      => "TIMESTAMP NOT NULL COMMENT 'Fecha ultima modificación'"
);

// Definición de llaves primarias
$llavesPrimarias["lista_precio_articulos"] = "codigo";

$llavesUnicas["lista_precio_articulos"] = array(
    "codigo_articulo,fecha"
);

$llavesForaneas["lista_precio_articulos"] = array(
    array(
        "lista_precio_articulos_codigo_articulo",
        "codigo_articulo",
        "articulos",
        "codigo"
    ),
    array(
        "lista_precio_articulos_usuarios",
        "codigo_usuario_registra",
        "usuarios",
        "codigo"
    )
);

// Inserción de datos iniciales
$registros["articulos"] = array(
    array(
		"codigo"             => "",
		"descripcion"                => "",
		"tipo_articulo"              => "1",
		"ficha_tecnica"              => "",
		"alto"                       => "0",
		"ancho"                      => "0",
		"profundidad"                => "0",
		"peso"                       => "0",
		"garantia"                   => "",
		"garantia_partes"            => "",
		"codigo_impuesto_compra"     => "0",
		"codigo_impuesto_venta"      => "0",
		"codigo_marca"               => "0",
		"codigo_estructura_grupo"    => "0",
		"manejo_inventario"          => "1",
		"detalle_kardex"             => "1",
		"codigo_unidad_venta"        => "0",
		"codigo_unidad_compra"       => "0",
		"codigo_unidad_presentacion" => "0",
		"codigo_iso"                 => "0",
		"activo"                     => "0",
		"imprime_listas"             => "3"
    )
);

// Inserción de datos iniciales
$registros["componentes"] = array(
    array(
        "id"        	  => "GESTARTI",
        "padre"     	  => "SUBMDCIN",
        "id_modulo" 	  => "INVENTARIO",
        "visible"   	  => "1",
        "orden"     	  => "10",
        "carpeta"   	  => "articulos",
        "archivo"   	  => "menu",
        "requiere_item"   => "0",
        "tabla_principal" => "articulos",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "ADICARTI",
        "padre"     	  => "GESTARTI",
        "id_modulo" 	  => "INVENTARIO",
        "visible"   	  => "0",
        "orden"     	  => "10",
        "carpeta"   	  => "articulos",
        "archivo"   	  => "adicionar",
        "requiere_item"   => "0",
        "tabla_principal" => "articulos",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "EXISARTI",
        "padre"           => "GESTARTI",
        "id_modulo"       => "INVENTARIO",
        "visible"         => "0",
        "orden"           => "12",
        "carpeta"         => "articulos",
        "archivo"         => "existente",
        "requiere_item"   => "0",
        "tabla_principal" => "articulos",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "MODIARTI",
        "padre"     	  => "GESTARTI",
        "id_modulo" 	  => "INVENTARIO",
        "visible"  	      => "0",
        "orden"     	  => "15",
        "carpeta"   	  => "articulos",
        "archivo"  	 	  => "modificar",
        "requiere_item"   => "1",
        "tabla_principal" => "articulos",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "CONSARTI",
        "padre"     	  => "GESTARTI",
        "id_modulo" 	  => "INVENTARIO",
        "visible"   	  => "0",
        "orden"     	  => "20",
        "carpeta"   	  => "articulos",
        "archivo"   	  => "consultar",
        "requiere_item"   => "1",
        "tabla_principal" => "articulos",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "ELIMARTI",
        "padre"     	  => "GESTARTI",
        "id_modulo" 	  => "INVENTARIO",
        "visible"   	  => "0",
        "orden"     	  => "30",
        "carpeta"   	  => "articulos",
        "archivo"   	  => "eliminar",
        "requiere_item"   => "1",
        "tabla_principal" => "articulos",
        "tipo_enlace"     => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_articulos AS
        SELECT 	CONCAT(
                    job_referencias_proveedor.codigo_articulo,
                    '|',job_referencias_proveedor.documento_identidad_proveedor
                ) AS id,
                job_articulos.codigo AS CODIGO_INTERNO,
                job_referencias_proveedor.referencia AS CODIGO_PROVEEDOR,
                job_articulos.descripcion AS DESCRIPCION,
                job_marcas.descripcion AS MARCA,
                FORMAT(job_lista_precio_articulos.costo,2) AS COSTO,
                CONCAT(
                    if(job_terceros.primer_nombre is not null, job_terceros.primer_nombre, ''),' ',
                    if(job_terceros.segundo_nombre is not null, job_terceros.segundo_nombre, ''),' ',
                    if(job_terceros.primer_apellido is not null, job_terceros.primer_apellido, ''),' ',
                    if(job_terceros.segundo_apellido is not null, job_terceros.segundo_apellido, ''),' ',
                    if(job_terceros.razon_social is not null, job_terceros.razon_social, '')
                ) AS PROVEEDOR,
                job_referencias_proveedor.documento_identidad_proveedor AS NIT

        FROM 	job_articulos,
                job_marcas,
                job_referencias_proveedor,
                job_terceros,
                job_proveedores,
                job_lista_precio_articulos
        WHERE  	job_articulos.codigo_marca = job_marcas.codigo AND
                job_articulos.codigo = job_referencias_proveedor.codigo_articulo AND
                job_proveedores.documento_identidad = job_referencias_proveedor.documento_identidad_proveedor AND
                job_referencias_proveedor.principal = '1' AND
                job_proveedores.documento_identidad = job_terceros.documento_identidad AND
                job_articulos.codigo = job_lista_precio_articulos.codigo_articulo AND
                job_articulos.codigo != '' AND job_articulos.activo ='1';"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_articulos AS
        SELECT 	CONCAT(
                    job_referencias_proveedor.codigo_articulo,
                    '|',job_referencias_proveedor.documento_identidad_proveedor
                ) AS id,
                job_articulos.codigo AS CODIGO,
                job_articulos.descripcion AS DESCRIPCION,
                job_marcas.descripcion AS MARCA,
                job_referencias_proveedor.referencia AS REFERENCIA,
                FORMAT(job_lista_precio_articulos.costo,2) AS COSTO,
                CONCAT(
                    if(job_terceros.primer_nombre is not null, job_terceros.primer_nombre, ''),' ',
                    if(job_terceros.segundo_nombre is not null, job_terceros.segundo_nombre, ''),' ',
                    if(job_terceros.primer_apellido is not null, job_terceros.primer_apellido, ''),' ',
                    if(job_terceros.segundo_apellido is not null, job_terceros.segundo_apellido, ''),' ',
                    if(job_terceros.razon_social is not null, job_terceros.razon_social, '')
                ) AS PROVEEDOR
        FROM 	job_articulos,
                job_marcas,
                job_referencias_proveedor,
                job_terceros,
                job_proveedores,
                job_lista_precio_articulos
        WHERE  	job_articulos.codigo_marca = job_marcas.codigo AND
                job_articulos.codigo = job_referencias_proveedor.codigo_articulo AND
                job_proveedores.documento_identidad = job_referencias_proveedor.documento_identidad_proveedor AND
                job_proveedores.documento_identidad = job_terceros.documento_identidad AND
                job_articulos.codigo = job_lista_precio_articulos.codigo_articulo AND
                job_articulos.codigo != 0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_articulos AS
        SELECT 	job_articulos.codigo AS id,
                CONCAT(
                    job_articulos.codigo, ' ',
                    job_articulos.descripcion,
                    '|', job_articulos.codigo
                ) AS descripcion
        FROM
            job_articulos	
        WHERE
            job_articulos.codigo != '';"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_referencias_proveedor AS
        SELECT  job_referencias_proveedor.codigo_articulo AS id,
                job_referencias_proveedor.documento_identidad_proveedor AS proveedor,
                CONCAT(
                    job_referencias_proveedor.referencia,
                    '|',job_referencias_proveedor.documento_identidad_proveedor
                ) AS referencia
        FROM
            job_referencias_proveedor   
        WHERE
            job_referencias_proveedor.codigo_articulo != '';"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_referencias_por_proveedor AS
        SELECT  job_referencias_proveedor.codigo_articulo AS id,
                job_referencias_proveedor.documento_identidad_proveedor AS proveedor,
                job_referencias_proveedor.referencia AS referencia
        FROM
            job_referencias_proveedor,
            job_articulos_proveedor   
        WHERE
            job_referencias_proveedor.codigo_articulo = job_articulos_proveedor.codigo_articulo;"
    )  
);
/***
    DROP TABLE IF EXISTS job_menu_articulos;
    DROP TABLE IF EXISTS job_buscador_articulos;
    DROP TABLE IF EXISTS job_seleccion_articulos;
    DROP TABLE IF EXISTS job_proveedor_marca;
    
	CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_articulos AS
	SELECT 	job_articulos.codigo AS id,
			job_articulos.codigo AS CODIGO,
			job_referencias_proveedor.referencia AS REFERENCIA,
			CONCAT(
                (
                    if (
                        (
                            SELECT descripcion FROM job_estructura_grupos WHERE codigo =
                            (
                                SELECT codigo_padre FROM job_estructura_grupos WHERE codigo=job_articulos.codigo_estructura_grupo
                            )
                        )
                        IS NULL,
                        "",
                        (
                            SELECT codigo_padre FROM job_estructura_grupos WHERE codigo=job_articulos.codigo_estructura_grupo
                        )
                    )
                ),
                ' ',
                (
                    SELECT descripcion FROM job_estructura_grupos WHERE codigo=job_articulos.codigo_estructura_grupo
                ),
                ' ',
                job_articulos.descripcion
            )  AS DESCRIPCION,
			job_marcas.descripcion AS MARCA,
			CONCAT(
				if(job_terceros.primer_nombre is not null, job_terceros.primer_nombre, ''),' ',
				if(job_terceros.segundo_nombre is not null, job_terceros.segundo_nombre, ''),' ',
				if(job_terceros.primer_apellido is not null, job_terceros.primer_apellido, ''),' ',
				if(job_terceros.segundo_apellido is not null, job_terceros.segundo_apellido, ''),' ',
				if(job_terceros.razon_social is not null, job_terceros.razon_social, '')
			) AS PROVEEDOR
	
	FROM 	job_articulos,
			job_marcas,
			job_referencias_proveedor,
			job_terceros,
			job_proveedores
	
	WHERE  	job_articulos.codigo_marca = job_marcas.codigo AND
			job_articulos.codigo = job_referencias_proveedor.codigo_articulo AND
			job_proveedores.documento_identidad = job_referencias_proveedor.documento_identidad_proveedor AND
			job_proveedores.documento_identidad = job_terceros.documento_identidad AND
			job_articulos.codigo != '';

	CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_articulos AS
	SELECT 	job_articulos.codigo AS id,
			job_articulos.codigo AS codigo,
			job_articulos.descripcion AS descripcion,
			job_marcas.descripcion AS marca,
			job_referencias_proveedor.referencia AS referencia,
			CONCAT(
				if(job_terceros.primer_nombre is not null, job_terceros.primer_nombre, ''),' ',
				if(job_terceros.segundo_nombre is not null, job_terceros.segundo_nombre, ''),' ',
				if(job_terceros.primer_apellido is not null, job_terceros.primer_apellido, ''),' ',
				if(job_terceros.segundo_apellido is not null, job_terceros.segundo_apellido, ''),' ',
				if(job_terceros.razon_social is not null, job_terceros.razon_social, '')
			) AS proveedor
	
	FROM 	job_articulos,
			job_marcas,
			job_referencias_proveedor,
			job_terceros,
			job_proveedores

	WHERE  	job_articulos.codigo_marca = job_marcas.codigo AND
			job_articulos.codigo = job_referencias_proveedor.codigo_articulo AND
			job_proveedores.documento_identidad = job_referencias_proveedor.documento_identidad_proveedor AND
			job_proveedores.documento_identidad = job_terceros.documento_identidad AND
			job_articulos.codigo != 0;

	CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_articulos AS
	SELECT 	job_articulos.codigo AS id,
			CONCAT(
				job_articulos.codigo, ' ',
				job_articulos.descripcion,
				'|', job_articulos.codigo
			) AS descripcion
	FROM
        job_articulos	
	WHERE
        job_articulos.codigo != '';	
***/
?>

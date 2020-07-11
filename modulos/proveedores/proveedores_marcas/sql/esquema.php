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
$tablas["proveedores_marcas"]   = array(
    "documento_identidad_proveedor" => "VARCHAR(12) NOT NULL COMMENT 'Codigo interno del proveedor'",
    "codigo_marca"                  => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo interno de la marca'"
);

// Definición de llaves primarias
$llavesPrimarias["proveedores_marcas"] =  "documento_identidad_proveedor,codigo_marca";

// Definición de llaves foráneas
$llavesForaneas["proveedores_marcas"] = array(
    array(
        // Nombre de la llave
        "proveedor",
        // Nombre del campo clave de la tabla local
        "documento_identidad_proveedor",
        // Nombre de la tabla relacionada
        "proveedores",
        // Nombre del campo clave en la tabla relacionada
        "documento_identidad"
    ),
    array(
        // Nombre de la llave
        "marca",
        // Nombre del campo clave de la tabla local
        "codigo_marca",
        // Nombre de la tabla relacionada
        "marcas",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    )
);

// Inserción de datos iniciales
$registros["componentes"]   = array(
    array(
        "id"        	  => "GESTPRMA",
        "padre"     	  => "SUBMDCPV",
        "id_modulo" 	  => "PROVEEDORES",
        "orden"     	  => "100",
        "carpeta"   	  => "proveedores_marcas",
        "archivo"   	  => "menu",
        "requiere_item"   => "1",
        "tabla_principal" => "proveedores_marcas",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "ADICPRMA",
        "padre"     	  => "GESTPRMA",
        "id_modulo" 	  => "PROVEEDORES",
        "visible"   	  => "0",
        "orden"     	  => "10",
        "carpeta"   	  => "proveedores_marcas",
        "archivo"   	  => "adicionar",
        "requiere_item"   => "0",
        "tabla_principal" => "proveedores_marcas",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "CONSPRMA",
        "padre"     	  => "GESTPRMA",
        "id_modulo" 	  => "PROVEEDORES",
        "visible"   	  => "0",
        "orden"     	  => "20",
        "carpeta"   	  => "proveedores_marcas",
        "archivo"   	  => "consultar",
        "requiere_item"   => "1",
        "tabla_principal" => "proveedores_marcas",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "MODIPRMA",
        "padre"           => "GESTPRMA",
        "id_modulo" 	  => "PROVEEDORES",
        "visible"   	  => "0",
        "orden"     	  => "30",
        "carpeta"   	  => "proveedores_marcas",
        "archivo"   	  => "modificar",
        "requiere_item"   => "1",
        "tabla_principal" => "proveedores_marcas",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "ELIMPRMA",
        "padre"     	  => "GESTPRMA",
        "id_modulo" 	  => "PROVEEDORES",
        "visible"   	  => "0",
        "orden"     	  => "40",
        "carpeta"   	  => "proveedores_marcas",
        "archivo"   	  => "eliminar",
        "requiere_item"   => "1",
        "tabla_principal" => "proveedores_marcas",
        "tipo_enlace"     => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_proveedores_marcas AS
        SELECT	
                CONCAT(job_proveedores_marcas.documento_identidad_proveedor,\"|\",job_proveedores_marcas.codigo_marca) AS id,
                CONCAT(if (job_terceros.primer_nombre is not null, job_terceros.primer_nombre,''),' ',
                       if (job_terceros.segundo_nombre is not null, job_terceros.segundo_nombre, ''), ' ',
                       if (job_terceros.primer_apellido is not null, job_terceros.primer_apellido, ''), ' ',
                       if (job_terceros.segundo_apellido is not null, job_terceros.segundo_apellido, ''), ' ',
                       if (job_terceros.razon_social is not null, job_terceros.razon_social, '')) AS PROVEEDOR,
                job_marcas.descripcion AS MARCA
        
        FROM 	job_proveedores_marcas,
                job_terceros,
                job_proveedores,
                job_marcas
        
        WHERE 	job_proveedores_marcas.documento_identidad_proveedor = job_proveedores.documento_identidad AND
                job_proveedores.documento_identidad = job_terceros.documento_identidad AND
                job_proveedores_marcas.codigo_marca = job_marcas.codigo;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_proveedores_marcas AS
        SELECT 	
                CONCAT(job_proveedores_marcas.documento_identidad_proveedor,\"|\",job_proveedores_marcas.codigo_marca) AS id,
                job_proveedores.documento_identidad AS id_proveedor,
                job_marcas.codigo AS id_marca,
                CONCAT(if (job_terceros.primer_nombre is not null, job_terceros.primer_nombre,''),' ',
                       if (job_terceros.segundo_nombre is not null, job_terceros.segundo_nombre, ''), ' ',
                       if (job_terceros.primer_apellido is not null, job_terceros.primer_apellido, ''), ' ',
                       if (job_terceros.segundo_apellido is not null, job_terceros.segundo_apellido, ''), ' ',
                       if (job_terceros.razon_social is not null, job_terceros.razon_social, '')) AS proveedor,
                job_marcas.descripcion AS marca
        
        FROM 	job_proveedores_marcas,
                job_terceros,
                job_proveedores,
                job_marcas
        
        WHERE 	job_proveedores_marcas.documento_identidad_proveedor = job_proveedores.documento_identidad AND
                job_proveedores_marcas.codigo_marca = job_marcas.codigo AND
                job_proveedores.documento_identidad = job_terceros.documento_identidad;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_relacion_proveedores_marcas AS
        SELECT 	job_articulos.codigo AS id,
                CONCAT(
                    job_articulos.codigo, ' ', job_articulos.descripcion, '|', job_articulos.codigo
                ) AS descripcion,
                job_proveedores_marcas.documento_identidad_proveedor AS id_proveedor
        
        FROM 	((((job_proveedores_marcas join job_terceros) join job_proveedores) join job_marcas) join job_articulos)
        
        WHERE 	((job_articulos.codigo_marca = job_proveedores_marcas.codigo_marca) AND 
                (job_proveedores_marcas.documento_identidad_proveedor = job_proveedores.documento_identidad) AND 
                (job_proveedores.documento_identidad = job_terceros.documento_identidad) AND 
                (job_proveedores_marcas.codigo_marca = job_marcas.codigo));"
    )
);
/***
    REALES
    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_proveedores_marcas AS
        SELECT  
                CONCAT(job_proveedores_marcas.documento_identidad_proveedor,"|",job_proveedores_marcas.codigo_marca) AS id,
                CONCAT(if (job_terceros.primer_nombre is not null, job_terceros.primer_nombre,''),' ',
                       if (job_terceros.segundo_nombre is not null, job_terceros.segundo_nombre, ''), ' ',
                       if (job_terceros.primer_apellido is not null, job_terceros.primer_apellido, ''), ' ',
                       if (job_terceros.segundo_apellido is not null, job_terceros.segundo_apellido, ''), ' ',
                       if (job_terceros.razon_social is not null, job_terceros.razon_social, '')) AS PROVEEDOR,
                job_marcas.descripcion AS MARCA
        
        FROM    job_proveedores_marcas,
                job_terceros,
                job_proveedores,
                job_marcas
        
        WHERE   job_proveedores_marcas.documento_identidad_proveedor = job_proveedores.documento_identidad AND
                job_proveedores.documento_identidad = job_terceros.documento_identidad AND
                job_proveedores_marcas.codigo_marca = job_marcas.codigo;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_proveedores_marcas AS
        SELECT  
                CONCAT(job_proveedores_marcas.documento_identidad_proveedor,"|",job_proveedores_marcas.codigo_marca) AS id,
                job_proveedores.documento_identidad AS id_proveedor,
                job_marcas.codigo AS id_marca,
                CONCAT(if (job_terceros.primer_nombre is not null, job_terceros.primer_nombre,''),' ',
                       if (job_terceros.segundo_nombre is not null, job_terceros.segundo_nombre, ''), ' ',
                       if (job_terceros.primer_apellido is not null, job_terceros.primer_apellido, ''), ' ',
                       if (job_terceros.segundo_apellido is not null, job_terceros.segundo_apellido, ''), ' ',
                       if (job_terceros.razon_social is not null, job_terceros.razon_social, '')) AS proveedor,
                job_marcas.descripcion AS marca
        
        FROM    job_proveedores_marcas,
                job_terceros,
                job_proveedores,
                job_marcas
        
        WHERE   job_proveedores_marcas.documento_identidad_proveedor = job_proveedores.documento_identidad AND
                job_proveedores_marcas.codigo_marca = job_marcas.codigo AND
                job_proveedores.documento_identidad = job_terceros.documento_identidad;            

/////NO FUNCIONANA/////////////////////
    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_proveedores_marcas AS
    SELECT	
            CONCAT(job_proveedores_marcas.documento_identidad_proveedor,"|",job_proveedores_marcas.codigo_marca) AS id,
			CONCAT(if (job_terceros.primer_nombre is not null, job_terceros.primer_nombre,''),' ',
				   if (job_terceros.segundo_nombre is not null, job_terceros.segundo_nombre, ''), ' ',
				   if (job_terceros.primer_apellido is not null, job_terceros.primer_apellido, ''), ' ',
				   if (job_terceros.segundo_apellido is not null, job_terceros.segundo_apellido, ''), ' ',
				   if (job_terceros.razon_social is not null, job_terceros.razon_social, '')) AS PROVEEDOR,
			job_marcas.descripcion AS MARCA
    
    FROM 	job_proveedores_marcas,
			job_terceros,
			job_proveedores,
			job_marcas
    
    WHERE 	job_proveedores_marcas.documento_identidad_proveedor = job_proveedores.documento_identidad AND
			job_proveedores.documento_identidad = job_terceros.documento_identidad AND
			job_proveedores_marcas.codigo_marca = job_marcas.codigo;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_proveedores_marcas AS
    SELECT 	
            CONCAT(job_proveedores_marcas.documento_identidad_proveedor,"|",job_proveedores_marcas.codigo_marca) AS id,
            job_proveedores.documento_identidad AS id_proveedor,
            job_marcas.codigo AS id_marca,
			CONCAT(if (job_terceros.primer_nombre is not null, job_terceros.primer_nombre,''),' ',
				   if (job_terceros.segundo_nombre is not null, job_terceros.segundo_nombre, ''), ' ',
				   if (job_terceros.primer_apellido is not null, job_terceros.primer_apellido, ''), ' ',
				   if (job_terceros.segundo_apellido is not null, job_terceros.segundo_apellido, ''), ' ',
				   if (job_terceros.razon_social is not null, job_terceros.razon_social, '')) AS proveedor,
			job_marcas.descripcion AS marca
    
    FROM 	job_proveedores_marcas,
			job_terceros,
			job_proveedores,
			job_marcas
    
    WHERE 	job_proveedores_marcas.documento_identidad_proveedor = job_proveedores.documento_identidad AND
			job_proveedores_marcas.codigo_marca = job_marcas.codigo AND
			job_proveedores.documento_identidad = job_terceros.documento_identidad;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_relacion_proveedores_marcas AS
    SELECT 	job_articulos.codigo AS id,
			CONCAT(
				job_articulos.codigo, ' ', job_articulos.descripcion, '|', job_articulos.codigo
			) AS descripcion,
			job_proveedores_marcas.id_proveedor AS id_proveedor
    
    FROM 	((((job_proveedores_marcas join job_terceros) join job_proveedores) join job_marcas) join job_articulos)
    
    WHERE 	((job_articulos.id_marca = job_proveedores_marcas.id_marca) AND 
			(job_proveedores_marcas.id_proveedor = job_proveedores.id) AND 
			(job_proveedores.id_tercero = job_terceros.id) AND 
			(job_proveedores_marcas.id_marca = job_marcas.id));

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_relacion_proveedores_marcas_ordenes AS
    SELECT 	job_articulos.id AS id,
			CONCAT(
				job_articulos.codigo_interno, ' ', job_articulos.descripcion, '|', 
                CONCAT(
                    job_articulos.id,
                    "-_",
                    job_articulos.id,
                    "-_",
                    job_articulos.id,
                    "-_",
                    job_articulos.id,
                    "-_",
                    job_articulos.id,
                    "-_",
                    job_articulos.id,
                    "-_",
                )
			) AS descripcion,
			job_proveedores_marcas.id_proveedor AS id_proveedor
    
    FROM 	((((job_proveedores_marcas join job_terceros) join job_proveedores) join job_marcas) join job_articulos)
    
    WHERE 	((job_articulos.id_marca = job_proveedores_marcas.id_marca) AND 
			(job_proveedores_marcas.id_proveedor = job_proveedores.id) AND 
			(job_proveedores.id_tercero = job_terceros.id) AND 
			(job_proveedores_marcas.id_marca = job_marcas.id));

***/
?>

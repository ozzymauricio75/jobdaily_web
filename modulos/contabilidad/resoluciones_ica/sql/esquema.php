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
* de APTITUD PARA UN PROPÓITO DETERMINADO. Consulte los detalles de
* la Licencia Pública General GNU para obtener una información más
* detallada.
*
* Debería haber recibido una copia de la Licencia Pública General GNU
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
**/

$borrarSiempre = false;

//  Definición de tablas
$tablas["resoluciones_ica"] = array(
    "numero_resolucion" => "VARCHAR(255) NOT NULL COMMENT 'Numero de la resolución'",
    "codigo_sucursal"   => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código interno de la sucursal'",
    "fecha"             => "DATE NOT NULL COMMENT 'Fecha de la resolución'"
);
//  Definición de llaves primarias
$llavesPrimarias["resoluciones_ica"] = "numero_resolucion,codigo_sucursal";

//  Definición de llaves foraneas
$llavesForaneas["resoluciones_ica"] = array(
    array(
        //  Nombre de la llave
        "resoluciones_ica_sucursal",
        //  Nombre del campo clave de la tabla local
        "codigo_sucursal",
        //  Nombre de la tabla relacionada
        "sucursales",
        //  Nombre del campo clave en la tabla relacionada
        "codigo"
    )
);

//  Insertar registro cero para la tabla

$registros["resoluciones_ica"] = array(
    array(
        "numero_resolucion" => "0",
        "codigo_sucursal"   => "",
        "fecha"             => "0000-00-00"
    )
);

//  Inserción de datos iniciales
$registros["componentes"] = array(
    array(
        "id"        	  => "GESTREIC",
        "padre"     	  => "SUBMINTR",
        "id_modulo" 	  => "CONTABILIDAD",
        "orden"     	  => "0010",
        "visible"  		  => "1",
        "carpeta"   	  => "resoluciones_ica",
        "archivo"   	  => "menu",
        "requiere_item"   => "1",
        "tabla_principal" => "resoluciones_ica",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "ADICREIC",
        "padre"     	  => "GESTREIC",
        "id_modulo" 	  => "CONTABILIDAD",
        "visible"   	  => "0",
        "orden"     	  => "0005",
        "carpeta"   	  => "resoluciones_ica",
        "archivo"   	  => "adicionar",
        "requiere_item"   => "0",
        "tabla_principal" => "resoluciones_ica",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "CONSREIC",
        "padre"     	  => "GESTREIC",
        "id_modulo" 	  => "CONTABILIDAD",
        "visible"   	  => "0",
        "orden"     	  => "0010",
        "carpeta"   	  => "resoluciones_ica",
        "archivo"   	  => "consultar",
        "requiere_item"   => "1",
        "tabla_principal" => "resoluciones_ica",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "MODIREIC",
        "padre"     	  => "GESTREIC",
        "id_modulo" 	  => "CONTABILIDAD",
        "visible"   	  => "0",
        "orden"     	  => "0015",
        "carpeta"   	  => "resoluciones_ica",
        "archivo"   	  => "modificar",
        "requiere_item"   => "1",
        "tabla_principal" => "resoluciones_ica",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "ELIMREIC",
        "padre"     	  => "GESTREIC",
        "id_modulo" 	  => "CONTABILIDAD",
        "visible"   	  => "0",
        "orden"     	  => "0020",
        "carpeta"   	  => "resoluciones_ica",
        "archivo"  	      => "eliminar",
        "requiere_item"   => "1",
        "tabla_principal" => "resoluciones_ica",
        "tipo_enlace"     => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_resoluciones_ica AS
        SELECT 	CONCAT(job_resoluciones_ica.numero_resolucion,'|',job_resoluciones_ica.codigo_sucursal) AS id,
                job_resoluciones_ica.numero_resolucion AS NUMERO,
                job_sucursales.nombre AS SUCURSAL,
                job_resoluciones_ica.fecha AS FECHA
        FROM 	job_resoluciones_ica,
                job_sucursales
        WHERE 	job_resoluciones_ica.codigo_sucursal = job_sucursales.codigo
                AND job_resoluciones_ica.numero_resolucion != 0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_resoluciones_ica AS
        SELECT 	CONCAT(job_resoluciones_ica.numero_resolucion,'|',job_resoluciones_ica.codigo_sucursal) AS id,
                job_resoluciones_ica.numero_resolucion AS numero,
                job_sucursales.nombre AS sucursal,
                job_resoluciones_ica.codigo_sucursal AS codigo_sucursal,
                job_resoluciones_ica.fecha AS fecha
        FROM 	job_resoluciones_ica,
                job_sucursales
        WHERE 	job_resoluciones_ica.codigo_sucursal = job_sucursales.codigo
                AND job_resoluciones_ica.numero_resolucion != 0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_resoluciones_reteica AS
        SELECT 	CONCAT(job_resoluciones_ica.numero_resolucion,'|',job_resoluciones_ica.codigo_sucursal) AS id,
                CONCAT(
                    'Numero: ',job_resoluciones_ica.numero_resolucion,'-','Fecha: ',job_resoluciones_ica.fecha,
                    '|',job_resoluciones_ica.numero_resolucion
                ) AS descripcion
        FROM 	job_resoluciones_ica
        WHERE 	job_resoluciones_ica.numero_resolucion != 0;"
    )
);

/***
   DROP TABLE IF EXISTS job_menu_resoluciones_ica; 
   DROP TABLE IF EXISTS job_buscador_resoluciones_ica;
   DROP TABLE IF EXISTS job_seleccion_resoluciones_reteica;
   
    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_resoluciones_ica AS
    SELECT 	CONCAT(job_resoluciones_ica.numero_resolucion,"|",job_resoluciones_ica.codigo_sucursal) AS id,
			job_resoluciones_ica.numero_resolucion AS NUMERO,
			job_sucursales.nombre AS SUCURSAL,
			job_resoluciones_ica.fecha AS FECHA
    
    FROM 	job_resoluciones_ica,
			job_sucursales
    
    WHERE 	job_resoluciones_ica.codigo_sucursal = job_sucursales.codigo
			AND job_resoluciones_ica.numero_resolucion != 0;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_resoluciones_ica AS
    SELECT 	CONCAT(job_resoluciones_ica.numero_resolucion,"|",job_resoluciones_ica.codigo_sucursal) AS id,
			job_resoluciones_ica.numero_resolucion AS numero,
			job_sucursales.nombre AS sucursal,
            job_resoluciones_ica.codigo_sucursal AS codigo_sucursal,
			job_resoluciones_ica.fecha AS fecha
    
    FROM 	job_resoluciones_ica,
			job_sucursales
    
    WHERE 	job_resoluciones_ica.codigo_sucursal = job_sucursales.codigo
			AND job_resoluciones_ica.numero_resolucion != 0;
     
    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_resoluciones_reteica AS
	SELECT 	CONCAT(job_resoluciones_ica.numero_resolucion,"|",job_resoluciones_ica.codigo_sucursal) AS id,
			CONCAT(
				'Numero: ',job_resoluciones_ica.numero_resolucion,'-','Fecha: ',job_resoluciones_ica.fecha,
				'|',job_resoluciones_ica.numero_resolucion
			) AS descripcion
    
    FROM 	job_resoluciones_ica
    
    WHERE 	job_resoluciones_ica.numero_resolucion != 0;
***/
?>

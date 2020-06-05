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

// Definición de tablas
$tablas["resoluciones_gran_contribuyente"] = array(
    "numero_resolucion" => "VARCHAR(255) NOT NULL COMMENT 'Numero de la resolución del gran contribuyente'",
    "descripcion"       => "VARCHAR(255) COMMENT 'Descripción para la resolución del gran contribuyente'",
    "fecha"             => "DATE NOT NULL COMMENT 'Fecha de la resolución del gran contribuyente'"
);
// Definición de llaves primarias
$llavesPrimarias["resoluciones_gran_contribuyente"] = "numero_resolucion";

// Insertar registro cero para la tabla
$registros["resoluciones_gran_contribuyente"] = array(
    array(
        "numero_resolucion" => "0",
        "descripcion"       => "",
        "fecha"             => "0000-00-00"
    )
);

// Inserción de datos iniciales
$registros["componentes"] = array(
    array(
        "id"              => "GESTREGC",
        "padre"           => "SUBMINTR",
        "id_modulo"       => "CONTABILIDAD",
        "orden"           => "0010",
        "visible"         => "1",
        "carpeta"         => "resoluciones_gran_contribuyente",
        "archivo"         => "menu",
        "requiere_item"   => "1",
        "tabla_principal" => "resoluciones_gran_contribuyente",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "ADICREGC",
        "padre"           => "GESTREGC",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "0",
        "orden"           => "0005",
        "carpeta"         => "resoluciones_gran_contribuyente",
        "archivo"         => "adicionar",
        "requiere_item"   => "0",
        "tabla_principal" => "resoluciones_gran_contribuyente",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "CONSREGC",
        "padre"           => "GESTREGC",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "0",
        "orden"           => "0010",
        "carpeta"         => "resoluciones_gran_contribuyente",
        "archivo"         => "consultar",
        "requiere_item"   => "1",
        "tabla_principal" => "resoluciones_gran_contribuyente",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "MODIREGC",
        "padre"           => "GESTREGC",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "0",
        "orden"           => "0015",
        "carpeta"         => "resoluciones_gran_contribuyente",
        "archivo"         => "modificar",
        "requiere_item"   => "1",
        "tabla_principal" => "resoluciones_gran_contribuyente",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "ELIMREGC",
        "padre"           => "GESTREGC",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "0",
        "orden"           => "0020",
        "carpeta"         => "resoluciones_gran_contribuyente",
        "archivo"         => "eliminar",
        "requiere_item"   => "1",
        "tabla_principal" => "resoluciones_gran_contribuyente",
        "tipo_enlace"     => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_resoluciones_gran_contribuyente AS
        SELECT 	job_resoluciones_gran_contribuyente.numero_resolucion AS id,
                job_resoluciones_gran_contribuyente.numero_resolucion AS NUMERO,
                job_resoluciones_gran_contribuyente.descripcion AS DESCRIPCION,
                job_resoluciones_gran_contribuyente.fecha AS FECHA
        FROM 	job_resoluciones_gran_contribuyente
        WHERE 	job_resoluciones_gran_contribuyente.numero_resolucion != 0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_resoluciones_gran_contribuyente AS
        SELECT 	job_resoluciones_gran_contribuyente.numero_resolucion AS id,
                job_resoluciones_gran_contribuyente.numero_resolucion AS numero,
                job_resoluciones_gran_contribuyente.descripcion AS descripcion,
                job_resoluciones_gran_contribuyente.fecha AS fecha
        FROM 	job_resoluciones_gran_contribuyente
        WHERE 	job_resoluciones_gran_contribuyente.numero_resolucion !=0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_resoluciones_contribuyente AS
        SELECT 	job_resoluciones_gran_contribuyente.numero_resolucion AS id,
                CONCAT(
                    'Numero: ',job_resoluciones_gran_contribuyente.numero_resolucion,'-','Fecha: ',job_resoluciones_gran_contribuyente.fecha,
                    '|',job_resoluciones_gran_contribuyente.numero_resolucion
                ) AS descripcion
        FROM 	job_resoluciones_gran_contribuyente
        WHERE 	job_resoluciones_gran_contribuyente.numero_resolucion !=0;"
    )
);
/***
    
    DROP TABLE IF EXISTS job_menu_resoluciones_gran_contribuyente;
    DROP TABLE IF EXISTS job_buscador_resoluciones_gran_contribuyente;
    DROP TABLE IF EXISTS job_seleccion_resoluciones_contribuyente;
    
    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_resoluciones_gran_contribuyente AS
    SELECT 	job_resoluciones_gran_contribuyente.numero_resolucion AS id,
			job_resoluciones_gran_contribuyente.numero_resolucion AS NUMERO,
			job_resoluciones_gran_contribuyente.descripcion AS DESCRIPCION,
			job_resoluciones_gran_contribuyente.fecha AS FECHA
    
    FROM 	job_resoluciones_gran_contribuyente
   
    WHERE 	job_resoluciones_gran_contribuyente.numero_resolucion != 0;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_resoluciones_gran_contribuyente AS
    SELECT 	job_resoluciones_gran_contribuyente.numero_resolucion AS id,
			job_resoluciones_gran_contribuyente.numero_resolucion AS numero,
			job_resoluciones_gran_contribuyente.descripcion AS descripcion,
			job_resoluciones_gran_contribuyente.fecha AS fecha
   
    FROM 	job_resoluciones_gran_contribuyente
   
    WHERE 	job_resoluciones_gran_contribuyente.numero_resolucion !=0;
    
    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_resoluciones_contribuyente AS
	SELECT 	job_resoluciones_gran_contribuyente.numero_resolucion AS id,
			CONCAT(
				'Numero: ',job_resoluciones_gran_contribuyente.numero_resolucion,'-','Fecha: ',job_resoluciones_gran_contribuyente.fecha,
				'|',job_resoluciones_gran_contribuyente.numero_resolucion
			) AS descripcion
    
    FROM 	job_resoluciones_gran_contribuyente
    
    WHERE 	job_resoluciones_gran_contribuyente.numero_resolucion !=0;

***/
?>

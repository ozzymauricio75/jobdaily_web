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
* de APTITUD PARA UN PROP�ITO DETERMINADO. Consulte los detalles de
* la Licencia P�blica General GNU para obtener una informaci�n m�s
* detallada.
*
* Deber�a haber recibido una copia de la Licencia P�blica General GNU
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
**/

$borrarSiempre = false;

// Definici�n de tablas
$tablas["resoluciones_gran_contribuyente"] = array(
    "numero_resolucion" => "VARCHAR(255) NOT NULL COMMENT 'Numero de la resoluci�n del gran contribuyente'",
    "descripcion"       => "VARCHAR(255) COMMENT 'Descripci�n para la resoluci�n del gran contribuyente'",
    "fecha"             => "DATE NOT NULL COMMENT 'Fecha de la resoluci�n del gran contribuyente'"
);
// Definici�n de llaves primarias
$llavesPrimarias["resoluciones_gran_contribuyente"] = "numero_resolucion";

// Insertar registro cero para la tabla
$registros["resoluciones_gran_contribuyente"] = array(
    array(
        "numero_resolucion" => "0",
        "descripcion"       => "",
        "fecha"             => "0000-00-00"
    )
);

// Inserci�n de datos iniciales
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

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
$tablas["resoluciones_retefuente"] = array(
    "numero_retefuente" => "VARCHAR(255) NOT NULL COMMENT 'Numero de la resoluci�n'",
    "descripcion"       => "VARCHAR(255) COMMENT 'Descripci�n para la resoluci�n'",
    "fecha"             => "DATE NOT NULL COMMENT 'Fecha de la resoluci�n'"
);
// Definici�n de llaves primarias
$llavesPrimarias["resoluciones_retefuente"] = "numero_retefuente";

// Insertar registro cero para la tabla
$registros["resoluciones_retefuente"] = array(
    array(
        "numero_retefuente" => "0",
        "descripcion"       => "",
        "fecha"             => "0000-00-00"
    )
);

// Inserci�n de datos iniciales
$registros["componentes"] = array(
    array(
        "id"              => "GESTRERF",
        "padre"           => "SUBMINTR",
        "id_modulo"       => "CONTABILIDAD",
        "orden"           => "0010",
        "visible"         => "1",
        "carpeta"         => "resoluciones_retefuente",
        "archivo"         => "menu",
        "requiere_item"   => "1",
        "tabla_principal" => "resoluciones_retefuente",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "ADICRERF",
        "padre"           => "GESTRERF",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "0",
        "orden"           => "0005",
        "carpeta"         => "resoluciones_retefuente",
        "archivo"         => "adicionar",
        "requiere_item"   => "0",
        "tabla_principal" => "resoluciones_retefuente",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "CONSRERF",
        "padre"           => "GESTRERF",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "0",
        "orden"           => "0010",
        "carpeta"         => "resoluciones_retefuente",
        "archivo"         => "consultar",
        "requiere_item"   => "1",
        "tabla_principal" => "resoluciones_retefuente",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "MODIRERF",
        "padre"           => "GESTRERF",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "0",
        "orden"           => "0015",
        "carpeta"         => "resoluciones_retefuente",
        "archivo"         => "modificar",
        "requiere_item"   => "1",
        "tabla_principal" => "resoluciones_retefuente",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "ELIMRERF",
        "padre"           => "GESTRERF",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "0",
        "orden"           => "0020",
        "carpeta"         => "resoluciones_retefuente",
        "archivo"         => "eliminar",
        "requiere_item"   => "1",
        "tabla_principal" => "resoluciones_retefuente",
        "tipo_enlace"     => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_resoluciones_retefuente AS
        SELECT 	job_resoluciones_retefuente.numero_retefuente AS id,
                job_resoluciones_retefuente.numero_retefuente AS NUMERO,
                job_resoluciones_retefuente.descripcion AS DESCRIPCION,
                job_resoluciones_retefuente.fecha AS FECHA        
        FROM 	job_resoluciones_retefuente
        WHERE 	job_resoluciones_retefuente.numero_retefuente!= 0;"
    ),
    array(

        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_resoluciones_retefuente AS
        SELECT 	job_resoluciones_retefuente.numero_retefuente AS id,
                job_resoluciones_retefuente.numero_retefuente AS numero,
                job_resoluciones_retefuente.descripcion AS descripcion,
                job_resoluciones_retefuente.fecha AS fecha
        FROM 	job_resoluciones_retefuente
        WHERE 	job_resoluciones_retefuente.numero_retefuente != 0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_resoluciones_retefuente AS
        SELECT 	job_resoluciones_retefuente.numero_retefuente AS id,
                CONCAT(
                    'Numero: ',job_resoluciones_retefuente.numero_retefuente,'-','Fecha: ',job_resoluciones_retefuente.fecha,
                    '|',job_resoluciones_retefuente.numero_retefuente
                ) AS descripcion
        FROM 	job_resoluciones_retefuente
        WHERE 	job_resoluciones_retefuente.numero_retefuente != 0;"
    )
);
/***
    
    DROP TABLE IF EXISTS job_menu_resoluciones_retefuente;
    DROP TABLE IF EXISTS job_buscador_resoluciones_retefuente;
    DROP TABLE IF EXISTS job_seleccion_resoluciones_retefuente;
    
    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_resoluciones_retefuente AS
    SELECT 	job_resoluciones_retefuente.numero_retefuente AS id,
			job_resoluciones_retefuente.numero_retefuente AS NUMERO,
			job_resoluciones_retefuente.descripcion AS DESCRIPCION,
			job_resoluciones_retefuente.fecha AS FECHA
    
    FROM 	job_resoluciones_retefuente
    
    WHERE 	job_resoluciones_retefuente.numero_retefuente!= 0;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_resoluciones_retefuente AS
    SELECT 	job_resoluciones_retefuente.numero_retefuente AS id,
			job_resoluciones_retefuente.numero_retefuente AS numero,
			job_resoluciones_retefuente.descripcion AS descripcion,
			job_resoluciones_retefuente.fecha AS fecha
    
    FROM 	job_resoluciones_retefuente
    
    WHERE 	job_resoluciones_retefuente.numero_retefuente != 0;
     
    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_resoluciones_retefuente AS
    SELECT 	job_resoluciones_retefuente.numero_retefuente AS id,
			CONCAT(
				'Numero: ',job_resoluciones_retefuente.numero_retefuente,'-','Fecha: ',job_resoluciones_retefuente.fecha,
				'|',job_resoluciones_retefuente.numero_retefuente
			) AS descripcion
    
    FROM 	job_resoluciones_retefuente
    
    WHERE 	job_resoluciones_retefuente.numero_retefuente != 0;
    
***/
?>

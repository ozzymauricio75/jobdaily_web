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
$tablas["domingos_festivos"] = array(
    "anio"	      => "VARCHAR(10) NOT NULL COMMENT 'anio de la generacion'",
 	"fecha"       => "DATE NOT NULL COMMENT 'Fecha del Domingo o festivo'",
    "tipo"        => "ENUM('1','2')  NOT NULL DEFAULT '1' COMMENT '1->Domingo 2->Festivo'",
    "descripcion" => "VARCHAR(50) NULL COMMENT 'Descripcion de la fecha'"
);

// Definición de llaves primarias
$llavesPrimarias["domingos_festivos"] = "fecha";

// Inserción de datos iniciales***/
$registros["componentes"] = array(
    array(
        "id"            => "GESTDOFE",
        "padre"         => "SUBMPRAN",
        "id_modulo"     => "NOMINA",
        "orden"         => "65",
        "visible"       => "1",
        "carpeta"       => "dominicales_festivos",
        "global"        => "0",
        "archivo"       => "menu",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "ADICDOFE",
        "padre"         => "GESTDOFE",
        "id_modulo"     => "NOMINA",
        "orden"         => "10",
        "visible"       => "0",
        "carpeta"       => "dominicales_festivos",
        "global"        => "0",
        "archivo"       => "adicionar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "CONSDOFE",
        "padre"         => "GESTDOFE",
        "id_modulo"     => "NOMINA",
        "orden"         => "20",
        "visible"       => "0",
        "carpeta"       => "dominicales_festivos",
        "global"        => "0",
        "archivo"       => "consultar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "MODIDOFE",
        "padre"         => "GESTDOFE",
        "id_modulo"     => "NOMINA",
        "orden"         => "30",
        "visible"       => "0",
        "carpeta"       => "dominicales_festivos",
        "global"        => "0",
        "archivo"       => "modificar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "ELIMDOFE",
        "padre"         => "GESTDOFE",
        "id_modulo"     => "NOMINA",
        "orden"         => "40",
        "visible"       => "0",
        "carpeta"       => "dominicales_festivos",
        "global"        => "0",
        "archivo"       => "eliminar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_domingos_festivos AS
        SELECT anio AS id,
        anio AS FECHA	
        FROM job_domingos_festivos	
        GROUP BY anio;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_domingos_festivos AS
        SELECT anio AS id,
        anio AS FECHA	
        FROM job_domingos_festivos	
        GROUP BY anio;"
    )
);
// Sentencia para la creación de la vista requerida
/***
    DROP TABLE IF EXISTS job_menu_domingos_festivos;
    DROP TABLE IF EXISTS job_buscador_domingos_festivos;
    
    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_domingos_festivos AS
    SELECT anio AS id,
    anio AS FECHA	
    FROM job_domingos_festivos	
	GROUP BY anio;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_domingos_festivos AS
    SELECT anio AS id,
    anio AS FECHA	
    FROM job_domingos_festivos	
	GROUP BY anio;
***/
?>

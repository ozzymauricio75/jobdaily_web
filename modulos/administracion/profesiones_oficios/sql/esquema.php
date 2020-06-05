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
$tablas["profesiones_oficios"] = array(
    "codigo_dane"    => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código universal que identifica una profesion u oficio aprobado por el DANE '",
    "descripcion"    => "VARCHAR(255) NOT NULL COMMENT 'Detalle que identifica la profesión u oficio'"
);


// Definición de llaves primarias
$llavesPrimarias["profesiones_oficios"]   = "codigo_dane";

// Definición de las llaves unicas
$llavesUnicas["profesiones_oficios"] = array(
    "descripcion"
);

// Registro inicial
$registros["profesiones_oficios"] = array(
    array(
        "codigo_dane" => "0",
        "descripcion" => ""
    )
);

$registros["componentes"] = array(
    array(
        "id"           => "GESTPROF",
        "padre"        => "SUBMDCAD",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "1",
        "orden"        => "100",
        "carpeta"      => "profesiones_oficios",
        "archivo"      => "menu",
        "global"       => "0",
        "requiere_item" => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "ADICPROF",
        "padre"        => "GESTPROF",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "0",
        "orden"        => "20",
        "carpeta"      => "profesiones_oficios",
        "archivo"      => "adicionar",
        "global"       => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "CONSPROF",
        "padre"        => "GESTPROF",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "0",
        "orden"        => "30",
        "carpeta"      => "profesiones_oficios",
        "archivo"      => "consultar",
        "global"       => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "MODIPROF",
        "padre"        => "GESTPROF",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "0",
        "orden"        => "40",
        "carpeta"      => "profesiones_oficios",
        "archivo"      => "modificar",
        "global"       => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "ELIMPROF",
        "padre"        => "GESTPROF",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "0",
        "orden"        => "50",
        "carpeta"      => "profesiones_oficios",
        "archivo"      => "eliminar",
        "global"       => "0",
        "tipo_enlace"  => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_profesiones_oficios AS
        SELECT codigo_dane AS id,
        codigo_dane AS CODIGO_DANE,
        descripcion AS DESCRIPCION
        FROM job_profesiones_oficios
        WHERE codigo_dane != 0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_profesiones_oficios AS
        SELECT codigo_dane AS id,
        descripcion AS descripcion
        FROM job_profesiones_oficios
        WHERE codigo_dane != 0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_profesiones AS
        SELECT codigo_dane AS id,
        CONCAT(codigo_dane,' - ',descripcion,'|',codigo_dane) AS descripcion
        FROM job_profesiones_oficios
        WHERE codigo_dane > 0;"
    )
);

/***
    DROP TABLE IF EXISTS job_menu_profesiones_oficios;
    DROP TABLE IF EXISTS job_buscador_profesiones_oficios;
    DROP TABLE IF EXISTS job_seleccion_profesiones;
    
	CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_profesiones_oficios AS
	SELECT codigo_dane AS id,
	codigo_dane AS CODIGO_DANE,
	descripcion AS DESCRIPCION
	FROM job_profesiones_oficios
	WHERE codigo_dane != 0;

	CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_profesiones_oficios AS
	SELECT codigo_dane AS id,
	descripcion AS descripcion
	FROM job_profesiones_oficios
	WHERE codigo_dane != 0;
    
    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_profesiones AS
    SELECT codigo_dane AS id,
    CONCAT(codigo_dane,' - ',descripcion,'|',codigo_dane) AS descripcion
    FROM job_profesiones_oficios
    WHERE codigo_dane > 0;

***/
?>

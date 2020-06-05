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
$tablas["vigencia_tasas"] = array(
	"codigo_tasa" => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la tabla de tasas'",
    "fecha"       => "DATE COMMENT 'Fecha a partir de la cual empieza a regir los porcentajes y valores base'",
    "porcentaje"  => "DECIMAL(7,4) NOT NULL DEFAULT '0.0000' COMMENT 'Porcentaje de la tasa'",
    "valor_base"  => "DECIMAL(15,2) NOT NULL DEFAULT '0' COMMENT 'Valor base a partir del cual se aplica el porcentaje anterior'"
);

// Definición de llaves primarias
$llavesPrimarias["vigencia_tasas"] = "codigo_tasa,fecha";

// Definición de llaves foraneas***/
$llavesForaneas["vigencia_tasas"] = array(
    array(
        // Nombre de la llave
        "tasa_vigente",
        // Nombre del campo clave de la tabla local
        "codigo_tasa",
        // Nombre de la tabla relacionada
        "tasas",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    )
);

// Inserción de datos iniciales
$registros["componentes"] = array(
    array(
        "id"        	  => "GESTVITA",
        "padre"     	  => "SUBMTASA",
        "id_modulo" 	  => "CONTABILIDAD",
        "orden"     	  => "0010",
        "visible"   	  => "1",
        "carpeta"   	  => "vigencia_tasas",
        "archivo"   	  => "menu",
        "requiere_item"   => "1",
        "tabla_principal" => "vigencia_tasas",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "ADICVITA",
        "padre"     	  => "GESTVITA",
        "id_modulo" 	  => "CONTABILIDAD",
        "visible"   	  => "0",
        "orden"     	  => "0005",
        "carpeta"   	  => "vigencia_tasas",
        "archivo"   	  => "adicionar",
        "requiere_item"   => "0",
        "tabla_principal" => "vigencia_tasas",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "CONSVITA",
        "padre"     	  => "GESTVITA",
        "id_modulo" 	  => "CONTABILIDAD",
        "visible"   	  => "0",
        "orden"     	  => "0010",
        "carpeta"   	  => "vigencia_tasas",
        "archivo"   	  => "consultar",
        "requiere_item"   => "1",
        "tabla_principal" => "vigencia_tasas",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "MODIVITA",
        "padre"     	  => "GESTVITA",
        "id_modulo" 	  => "CONTABILIDAD",
        "visible"   	  => "0",
        "orden"     	  => "0015",
        "carpeta"   	  => "vigencia_tasas",
        "archivo"   	  => "modificar",
        "requiere_item"   => "1",
        "tabla_principal" => "vigencia_tasas",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "ELIMVITA",
        "padre"     	  => "GESTVITA",
        "id_modulo" 	  => "CONTABILIDAD",
        "visible"   	  => "0",
        "orden"     	  => "0020",
        "carpeta"   	  => "vigencia_tasas",
        "archivo"   	  => "eliminar",
         "requiere_item"  => "1",
        "tabla_principal" => "vigencia_tasas",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "EXPOVITA",
        "padre"     	  => "GESTVITA",
        "id_modulo" 	  => "CONTABILIDAD",
        "visible"   	  => "0",
        "orden"     	  => "0030",
        "carpeta"   	  => "vigencia_tasas",
        "archivo"   	  => "listar",
        "requiere_item"   => "0",
        "tabla_principal" => "vigencia_tasas",
        "tipo_enlace"     => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_vigencia_tasas AS
        SELECT CONCAT(job_vigencia_tasas.codigo_tasa,'|',job_vigencia_tasas.fecha) AS id,
        job_vigencia_tasas.codigo_tasa AS CODIGO,
        job_tasas.descripcion AS TASA,
        job_vigencia_tasas.fecha AS FECHA,
        job_vigencia_tasas.porcentaje AS PORCENTAJE,
        FORMAT(job_vigencia_tasas.valor_base, 0) AS VALOR_BASE
        FROM job_tasas, job_vigencia_tasas
        WHERE job_vigencia_tasas.codigo_tasa = job_tasas.codigo
        AND job_vigencia_tasas.codigo_tasa !=0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_vigencia_tasas AS
        SELECT CONCAT(job_vigencia_tasas.codigo_tasa,'|',job_vigencia_tasas.fecha) AS id,
        job_vigencia_tasas.codigo_tasa AS codigo,
        job_tasas.descripcion AS tasa,
        job_vigencia_tasas.fecha AS fecha,
        job_vigencia_tasas.porcentaje AS porcentaje,
        job_vigencia_tasas.valor_base AS valor_base
        FROM job_tasas, job_vigencia_tasas
        WHERE job_vigencia_tasas.codigo_tasa = job_tasas.codigo
        AND job_vigencia_tasas.codigo_tasa !=0;"
    )
);
/***
	CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_vigencia_tasas AS
	SELECT CONCAT(job_vigencia_tasas.codigo_tasa,'|',job_vigencia_tasas.fecha) AS id,
    job_vigencia_tasas.codigo_tasa AS CODIGO,
    job_tasas.descripcion AS TASA,
	job_vigencia_tasas.fecha AS FECHA,
	job_vigencia_tasas.porcentaje AS PORCENTAJE,
	FORMAT(job_vigencia_tasas.valor_base, 0) AS VALOR_BASE
	FROM job_tasas, job_vigencia_tasas
	WHERE job_vigencia_tasas.codigo_tasa = job_tasas.codigo
    AND job_vigencia_tasas.codigo_tasa !=0;

	CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_vigencia_tasas AS
	SELECT CONCAT(job_vigencia_tasas.codigo_tasa,'|',job_vigencia_tasas.fecha) AS id,
    job_vigencia_tasas.codigo_tasa AS codigo,
	job_tasas.descripcion AS tasa,
	job_vigencia_tasas.fecha AS fecha,
	job_vigencia_tasas.porcentaje AS porcentaje,
	job_vigencia_tasas.valor_base AS valor_base
	FROM job_tasas, job_vigencia_tasas
	WHERE job_vigencia_tasas.codigo_tasa = job_tasas.codigo
    AND job_vigencia_tasas.codigo_tasa !=0;

***/

?>

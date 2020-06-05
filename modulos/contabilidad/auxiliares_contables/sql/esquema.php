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
$tablas["auxiliares_contables"] = array(
	"codigo_empresa"        => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Llave foranes de la tabla empresas'",
    "codigo_anexo_contable" => "VARCHAR(3) NOT NULL COMMENT 'Código del anexo que permite dividir las cuentas'",
    "codigo"                => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código donde se acumulara la información'",
    "descripcion"           => "VARCHAR(255) NOT NULL COMMENT 'Detalle que describe el anexo contable'"
);
// Definición de llaves primarias
$llavesPrimarias["auxiliares_contables"] = "codigo_empresa,codigo_anexo_contable,codigo";

// Definición de llaves foraneas
$llavesForaneas["auxiliares_contables"] = array(
    array(
        // Nombre de la llave
        "auxiliar_empresa",
        // Nombre del campo clave de la tabla local
        "codigo_empresa",
        // Nombre de la tabla relacionada
        "empresas",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave
        "auxiliar_anexo",
        // Nombre del campo clave de la tabla local
        "codigo_anexo_contable",
        // Nombre de la tabla relacionada
        "anexos_contables",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    )
);

// Inserción de datos iniciales
$registros["auxiliares_contables"] = array(
    array(
        "codigo_empresa"        => "0",
        "codigo_anexo_contable" => "",
        "codigo"                => "0",
        "descripcion"           => ""
    )
);

// Inserción de datos iniciales
$registros["componentes"] = array(
    array(
        "id"        	  => "GESTAUCO",
        "padre"     	  => "SUBMINCO",
        "id_modulo" 	  => "CONTABILIDAD",
        "orden"    		  => "0015",
        "visible"   	  => "1",
        "carpeta"   	  => "auxiliares_contables",
        "archivo"   	  => "menu",
        "requiere_item"   => "1",
        "tabla_principal" => "auxiliares_contables",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "ADICAUCO",
        "padre"     	  => "GESTAUCO",
        "id_modulo" 	  => "CONTABILIDAD",
        "visible"   	  => "0",
        "orden"     	  => "0005",
        "carpeta"   	  => "auxiliares_contables",
        "archivo"   	  => "adicionar",
        "requiere_item"   => "0",
        "tabla_principal" => "auxiliares_contables",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "CONSAUCO",
        "padre"     	  => "GESTAUCO",
        "id_modulo" 	  => "CONTABILIDAD",
        "visible"   	  => "0",
        "orden"     	  => "0010",
        "carpeta"   	  => "auxiliares_contables",
        "archivo"   	  => "consultar",
        "requiere_item"   => "1",
        "tabla_principal" => "auxiliares_contables",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "MODIAUCO",
        "padre"     	  => "GESTAUCO",
        "id_modulo" 	  => "CONTABILIDAD",
        "visible"   	  => "0",
        "orden"     	  => "0015",
        "carpeta"   	  => "auxiliares_contables",
        "archivo"   	  => "modificar",
        "requiere_item"   => "1",
        "tabla_principal" => "auxiliares_contables",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "ELIMAUCO",
        "padre"     	  => "GESTAUCO",
        "id_modulo" 	  => "CONTABILIDAD",
        "visible"   	  => "0",
        "orden"     	  => "0020",
        "carpeta"   	  => "auxiliares_contables",
        "archivo"   	  => "eliminar",
        "requiere_item"   => "1",
        "tabla_principal" => "auxiliares_contables",
        "tipo_enlace"     => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_auxiliares_contables AS
        SELECT 	CONCAT(job_auxiliares_contables.codigo_empresa,\"|\",job_auxiliares_contables.codigo_anexo_contable,\"|\",job_auxiliares_contables.codigo) AS id,
                job_auxiliares_contables.codigo AS CODIGO,
                job_auxiliares_contables.descripcion AS DESCRIPCION,
                job_anexos_contables.descripcion AS ANEXO_CONTABLE,
                job_empresas.razon_social AS EMPRESA,
                job_empresas.codigo AS id_empresa
        
        FROM 	job_auxiliares_contables,
                job_anexos_contables,
                job_empresas
        
        WHERE 	job_auxiliares_contables.codigo_anexo_contable = job_anexos_contables.codigo AND
                job_auxiliares_contables.codigo_empresa = job_empresas.codigo AND
                job_auxiliares_contables.codigo > 0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_auxiliares_contables AS
        SELECT 	CONCAT(job_auxiliares_contables.codigo_empresa,\"|\",job_auxiliares_contables.codigo_anexo_contable,\"|\",job_auxiliares_contables.codigo) AS id,
                CONCAT(job_auxiliares_contables.codigo_empresa,\"|\",job_auxiliares_contables.codigo_anexo_contable) AS codigo_id,
                job_auxiliares_contables.codigo AS codigo,
                job_auxiliares_contables.descripcion AS descripcion,
                job_auxiliares_contables.codigo_anexo_contable AS codigo_anexo_contable,
                job_anexos_contables.descripcion AS anexo_contable,
                job_empresas.razon_social AS empresa,
                job_empresas.codigo AS id_empresa

        FROM 	job_auxiliares_contables,
                job_anexos_contables,
                job_empresas

        WHERE 	job_auxiliares_contables.codigo_anexo_contable = job_anexos_contables.codigo AND
                job_auxiliares_contables.codigo_empresa = job_empresas.codigo AND
                job_auxiliares_contables.codigo > 0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_auxiliares_contables AS
        SELECT 	CONCAT(job_auxiliares_contables.codigo_empresa,\"|\",job_auxiliares_contables.codigo_anexo_contable,\"|\",job_auxiliares_contables.codigo) AS id,
                CONCAT(job_empresas.razon_social,',',job_anexos_contables.descripcion,',',job_auxiliares_contables.descripcion) as descripcion,
                job_auxiliares_contables.codigo AS codigo,
                job_anexos_contables.codigo AS anexo_contable,
                job_empresas.codigo AS empresa
        FROM 	job_auxiliares_contables,
                job_anexos_contables,
                job_empresas

        WHERE 	job_auxiliares_contables.codigo_anexo_contable = job_anexos_contables.codigo AND
                job_auxiliares_contables.codigo_empresa = job_empresas.codigo AND
                job_auxiliares_contables.codigo > 0;"
    )
);

/***
	CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_auxiliares_contables AS
	SELECT 	CONCAT(job_auxiliares_contables.codigo_empresa,"|",job_auxiliares_contables.codigo_anexo_contable,"|",job_auxiliares_contables.codigo) AS id,
			job_auxiliares_contables.codigo AS CODIGO,
			job_auxiliares_contables.descripcion AS DESCRIPCION,
			job_anexos_contables.descripcion AS ANEXO_CONTABLE,
			job_empresas.razon_social AS EMPRESA,
			job_empresas.codigo AS id_empresa
	
	FROM 	job_auxiliares_contables,
			job_anexos_contables,
			job_empresas
	
	WHERE 	job_auxiliares_contables.codigo_anexo_contable = job_anexos_contables.codigo AND
			job_auxiliares_contables.codigo_empresa = job_empresas.codigo AND
			job_auxiliares_contables.codigo > 0;

	CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_auxiliares_contables AS
	SELECT 	CONCAT(job_auxiliares_contables.codigo_empresa,"|",job_auxiliares_contables.codigo_anexo_contable,"|",job_auxiliares_contables.codigo) AS id,
			CONCAT(job_auxiliares_contables.codigo_empresa,"|",job_auxiliares_contables.codigo_anexo_contable) AS codigo_id,
            job_auxiliares_contables.codigo AS codigo,
			job_auxiliares_contables.descripcion AS descripcion,
            job_auxiliares_contables.codigo_anexo_contable AS codigo_anexo_contable,
			job_anexos_contables.descripcion AS anexo_contable,
			job_empresas.razon_social AS empresa,
			job_empresas.codigo AS id_empresa

	FROM 	job_auxiliares_contables,
			job_anexos_contables,
			job_empresas

	WHERE 	job_auxiliares_contables.codigo_anexo_contable = job_anexos_contables.codigo AND
			job_auxiliares_contables.codigo_empresa = job_empresas.codigo AND
			job_auxiliares_contables.codigo > 0;

            
    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_auxiliares_contables AS
	SELECT 	CONCAT(job_auxiliares_contables.codigo_empresa,"|",job_auxiliares_contables.codigo_anexo_contable,"|",job_auxiliares_contables.codigo) AS id,
			CONCAT(job_empresas.razon_social,',',job_anexos_contables.descripcion,',',job_auxiliares_contables.descripcion) as descripcion,
            job_auxiliares_contables.codigo AS codigo,
			job_anexos_contables.codigo AS anexo_contable,
			job_empresas.codigo AS empresa
	FROM 	job_auxiliares_contables,
			job_anexos_contables,
			job_empresas

	WHERE 	job_auxiliares_contables.codigo_anexo_contable = job_anexos_contables.codigo AND
			job_auxiliares_contables.codigo_empresa = job_empresas.codigo AND
			job_auxiliares_contables.codigo > 0;
***/
?>

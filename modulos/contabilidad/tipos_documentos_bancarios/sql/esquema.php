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

/*** Definición de tablas ***/
$tablas["tipos_documentos_bancarios"] = array(
	"codigo"      => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de documento bancario'",
    "descripcion" => "VARCHAR(150) NOT NULL COMMENT 'Detalle que describe el tipo de documento bancario'"
);

/*** Definición de llaves primarias ***/
$llavesPrimarias["tipos_documentos_bancarios"] = "codigo";

/*** Definición de campos únicos ***/
$llavesUnicas["tipos_documentos_bancarios"] = array(
    "descripcion"
);

$registros["tipos_documentos_bancarios"] = array(
    array(
        "codigo"      => 0,
        "descripcion" => ""
    )
);



/*** Inserción de datos iniciales ***/
$registros["componentes"] = array(
    array(
        "id"        	  => "GESTTIDB",
        "padre"     	  => "SUBMINCO",
        "id_modulo" 	  => "CONTABILIDAD",
        "orden"     	  => "0027",
        "visible"   	  => "1",
        "carpeta"   	  => "tipos_documentos_bancarios",
        "archivo"   	  => "menu",
        "requiere_item"   => "1",
        "tabla_principal" => "tipos_documentos_bancarios",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "ADICTIDB",
        "padre"     	  => "GESTTIDB",
        "id_modulo" 	  => "CONTABILIDAD",
        "visible"   	  => "0",
        "orden"     	  => "0005",
        "carpeta"         => "tipos_documentos_bancarios",
        "archivo"   	  => "adicionar",
        "requiere_item"   => "0",
        "tabla_principal" => "tipos_documentos_bancarios",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "CONSTIDB",
        "padre"     	  => "GESTTIDB",
        "id_modulo" 	  => "CONTABILIDAD",
        "visible"   	  => "0",
        "orden"     	  => "0010",
        "carpeta"   	  => "tipos_documentos_bancarios",
        "archivo"   	  => "consultar",
        "requiere_item"   => "1",
        "tabla_principal" => "tipos_documentos_bancarios",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "MODITIDB",
        "padre"     	  => "GESTTIDB",
        "id_modulo" 	  => "CONTABILIDAD",
        "visible"   	  => "0",
        "orden"     	  => "0015",
        "carpeta"   	  => "tipos_documentos_bancarios",
        "archivo"   	  => "modificar",
        "requiere_item"   => "1",
        "tabla_principal" => "tipos_documentos_bancarios",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "ELIMTIDB",
        "padre"     	  => "GESTTIDB",
        "id_modulo" 	  => "CONTABILIDAD",
        "visible"   	  => "0",
        "orden"     	  => "0020",
        "carpeta"   	  => "tipos_documentos_bancarios",
        "archivo"   	  => "eliminar",
        "requiere_item"   => "1",
        "tabla_principal" => "tipos_documentos_bancarios",
        "tipo_enlace"     => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_tipos_documentos_bancarios AS
        SELECT codigo AS id,
        codigo AS CODIGO,
        descripcion AS DESCRIPCION
        FROM job_tipos_documentos_bancarios
        WHERE codigo != '0';"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_tipos_documentos_bancarios AS
        SELECT codigo AS id,
        codigo AS CODIGO,
        descripcion AS DESCRIPCION
        FROM job_tipos_documentos_bancarios
        WHERE codigo != '0';"
    )
);

/***
	CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_tipos_documentos_bancarios AS
	SELECT codigo AS id,
	codigo AS CODIGO,
	descripcion AS DESCRIPCION
	FROM job_tipos_documentos_bancarios
	WHERE codigo != '0';

	CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_tipos_documentos_bancarios AS
	SELECT codigo AS id,
	codigo AS CODIGO,
	descripcion AS DESCRIPCION
	FROM job_tipos_documentos_bancarios
	WHERE codigo != '0';
***/
?>

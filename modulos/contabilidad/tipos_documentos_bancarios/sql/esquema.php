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
* de APTITUD PARA UN PROP�SITO DETERMINADO. Consulte los detalles de
* la Licencia P�blica General GNU para obtener una informaci�n m�s
* detallada.
*
* Deber�a haber recibido una copia de la Licencia P�blica General GNU
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
**/

$borrarSiempre = false;

/*** Definici�n de tablas ***/
$tablas["tipos_documentos_bancarios"] = array(
	"codigo"      => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de documento bancario'",
    "descripcion" => "VARCHAR(150) NOT NULL COMMENT 'Detalle que describe el tipo de documento bancario'"
);

/*** Definici�n de llaves primarias ***/
$llavesPrimarias["tipos_documentos_bancarios"] = "codigo";

/*** Definici�n de campos �nicos ***/
$llavesUnicas["tipos_documentos_bancarios"] = array(
    "descripcion"
);

$registros["tipos_documentos_bancarios"] = array(
    array(
        "codigo"      => 0,
        "descripcion" => ""
    )
);



/*** Inserci�n de datos iniciales ***/
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

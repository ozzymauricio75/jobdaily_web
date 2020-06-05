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
$tablas["conceptos_dian"] = array(
    "codigo"                         => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo DIAN'",
	"codigo_formato_dian"            => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la tabla de formatos_dian'",
    "descripcion"                    => "VARCHAR(255) NOT NULL COMMENT 'Detalle que describe el anexo contable'",
    "valor_base"                     => "DECIMAL(17,4) UNSIGNED COMMENT 'Valos base sobre el cual se va a separar la informacion'",
    "valor_a_informar"               => "ENUM('1','2','3') NOT NULL COMMENT '1-> Saldos a fin de a�o 2->Solo acumulado del a�o seg�n sentido de la cuenta 3->Acumulado Db y Cr seg�n sentido de la cuenta'",
    "identificacion_valores_mayores" => "INT(15) NOT NULL COMMENT 'Documento de identidad requerido por la DIAN para los valores de menores cuant�as al valor base acumulados'",
    "concepto_razon_social"          => "VARCHAR(255) NOT NULL COMMENT 'Concepto que debe describirse para las menores cuant�as acumuladas requerido por la DIAN'",
    "tipo_documento"                 => "SMALLINT(4) NOT NULL COMMENT 'Tipo de documento que debe reportarse para las menores cuant�as acumuladas requerido por la DIAN'"
);

// Definici�n de llaves primarias
$llavesPrimarias["conceptos_dian"] = "codigo";

// Definici�n de llaves foraneas
$llavesForaneas["conceptos_dian"] = array(
    array(
        // Nombre de la llave
        "concepto_formato_dian",
        // Nombre del campo clave de la tabla local
        "codigo_formato_dian",
        // Nombre de la tabla relacionada
        "formatos_dian",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    )
);

// Inserci�n de datos iniciales id=0***/
$registros["conceptos_dian"] = array(
    array(
        "codigo"                         => 0,
        "codigo_formato_dian"            => 0,
        "descripcion"                    => "",
        "valor_base"                     => 0,
        "valor_a_informar"               => '1',
        "identificacion_valores_mayores" => 0,
        "concepto_razon_social"          => "",
        "tipo_documento"                 => 0
    )
);

// Inserci�n de datos iniciales
$registros["componentes"] = array(
    array(
        "id"        	  => "GESTCODI",
        "padre"     	  => "SUBMINTR",
        "id_modulo" 	  => "CONTABILIDAD",
        "orden"     	  => "0025",
        "visible"   	  => "1",
        "carpeta"   	  => "conceptos_dian",
        "archivo"   	  => "menu",
        "requiere_item"   => "1",
        "tabla_principal" => "conceptos_dian",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "ADICCODI",
        "padre"     	  => "GESTCODI",
        "id_modulo" 	  => "CONTABILIDAD",
        "visible"   	  => "0",
        "orden"     	  => "0005",
        "carpeta"   	  => "conceptos_dian",
        "archivo"   	  => "adicionar",
        "requiere_item"   => "0",
        "tabla_principal" => "conceptos_dian",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "CONSCODI",
        "padre"     	  => "GESTCODI",
        "id_modulo" 	  => "CONTABILIDAD",
        "visible"   	  => "0",
        "orden"     	  => "0010",
        "carpeta"   	  => "conceptos_dian",
        "archivo"   	  => "consultar",
         "requiere_item"  => "1",
        "tabla_principal" => "conceptos_dian",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "MODICODI",
        "padre"     	  => "GESTCODI",
        "id_modulo" 	  => "CONTABILIDAD",
        "visible"   	  => "0",
        "orden"     	  => "0015",
        "carpeta"   	  => "conceptos_dian",
        "archivo"   	  => "modificar",
         "requiere_item"  => "1",
        "tabla_principal" => "conceptos_dian",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "ELIMCODI",
        "padre"     	  => "GESTCODI",
        "id_modulo" 	  => "CONTABILIDAD",
        "visible"   	  => "0",
        "orden"     	  => "0020",
        "carpeta"    	  => "conceptos_dian",
        "archivo"   	  => "eliminar",
         "requiere_item"  => "1",
        "tabla_principal" => "conceptos_dian",
        "tipo_enlace"     => "1"
    )
);


$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_conceptos_dian AS 
        SELECT job_conceptos_dian.codigo AS id,
        job_formatos_dian.descripcion AS FORMATO_DIAN,
        job_conceptos_dian.codigo AS CODIGO_DIAN,
        job_conceptos_dian.descripcion AS DESCRIPCION,
        FORMAT(job_conceptos_dian.valor_base,0) AS VALOR_BASE
        FROM job_formatos_dian, job_conceptos_dian
        WHERE job_conceptos_dian.codigo_formato_dian = job_formatos_dian.codigo AND
        job_conceptos_dian.codigo > 0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_conceptos_dian AS 
        SELECT codigo AS id,
        codigo AS codigo,
        descripcion AS descripcion 
        FROM job_conceptos_dian WHERE
        job_conceptos_dian.codigo > 0;"
    )
);
/***
	CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_conceptos_dian AS 
	SELECT job_conceptos_dian.codigo AS id,
	job_formatos_dian.descripcion AS FORMATO_DIAN,
	job_conceptos_dian.codigo AS CODIGO_DIAN,
	job_conceptos_dian.descripcion AS DESCRIPCION,
	FORMAT(job_conceptos_dian.valor_base,0) AS VALOR_BASE
	FROM job_formatos_dian, job_conceptos_dian
	WHERE job_conceptos_dian.codigo_formato_dian = job_formatos_dian.codigo AND
    job_conceptos_dian.codigo > 0;

	CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_conceptos_dian AS 
	SELECT codigo AS id,
	codigo AS codigo,
	descripcion AS descripcion 
	FROM job_conceptos_dian WHERE
    job_conceptos_dian.codigo > 0;
     
***/
?>

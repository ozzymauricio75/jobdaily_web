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

/*** Definici�n de tablas ***/
$tablas["unidades"] = array(
    "codigo"                  => "INT(6) UNSIGNED ZEROFILL NOT NULL COMMENT 'C�digo interno generado por el sistema'",
    "codigo_tipo_unidad"      => "SMALLINT(2) UNSIGNED ZEROFILL  NOT NULL COMMENT 'Tipo de unidad de medida'",
    "nombre"                  => "VARCHAR(255) NOT NULL COMMENT 'Nombre de la unidad de medida'",
    "factor_conversion"       => "DECIMAL(8,4) UNSIGNED NOT NULL DEFAULT '1' COMMENT 'Factor de conversion en relaci�n a otra unidad'",
    "codigo_unidad_principal" => "INT(6) UNSIGNED NOT NULL COMMENT 'Si es la unidad principal va en cero, de lo contrario va el c�digo definido como unidad principal, el sistema guarda el c�digo interno de la unidad de medida principal'"
);

/*** Definici�n de llaves primarias***/
$llavesPrimarias["unidades"] = "codigo";

/*** Definici�n de  campos �nicos***/
$llavesUnicas["unidades"] = array(
    "nombre"
);

/*** Definici�n de llaves foraneas***/
$llavesForaneas["unidades"] = array(
    array(
        /*** Nombre de la llave ***/
        "unidad_tipo_unidad",
        /*** Nombre del campo clave de la tabla local ***/
        "codigo_tipo_unidad",
        /*** Nombre de la tabla relacionada ***/
        "tipos_unidades",
        /*** Nombre del campo clave en la tabla relacionada ***/
        "codigo"
    )
);

/*** Inserci�n de datos iniciales ***/
$registros["unidades"] = array(
    array(
        "codigo"                  => "0",
        "codigo_tipo_unidad"      => "0",		
		"nombre"                  => "",
		"factor_conversion"       => "0",
		"codigo_unidad_principal" => "0"
    )
);

/*** Inserci�n de datos iniciales ***/
$registros["componentes"] = array(
    array(
        "id"        	  => "GESTUNID",
        "padre"     	  => "SUBMDCIN",
        "id_modulo" 	  => "INVENTARIO",
        "visible"   	  => "1",
        "orden"     	  => "70",
        "carpeta"   	  => "unidades",
        "archivo"   	  => "menu",
        "requiere_item"   => "1",
        "tabla_principal" => "unidades",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"           	  => "ADICUNID",
        "padre"     	  => "GESTUNID",
        "id_modulo" 	  => "INVENTARIO",
        "visible"   	  => "0",
        "orden"     	  => "10",
        "carpeta"   	  => "unidades",
        "archivo"   	  => "adicionar",
        "requiere_item"   => "0",
        "tabla_principal" => "unidades",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "CONSUNID",
        "padre"     	  => "GESTUNID",
        "id_modulo" 	  => "INVENTARIO",
        "visible"   	  => "0",
        "orden"     	  => "20",
        "carpeta"   	  => "unidades",
        "archivo"   	  => "consultar",
        "requiere_item"   => "1",
        "tabla_principal" => "unidades",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "MODIUNID",
        "padre"     	  => "GESTUNID",
        "id_modulo" 	  => "INVENTARIO",
        "visible"   	  => "0",
        "orden"     	  => "30",
        "carpeta"   	  => "unidades",
        "archivo"   	  => "modificar",
        "requiere_item"   => "1",
        "tabla_principal" => "unidades",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "ELIMUNID",
        "padre"     	  => "GESTUNID",
        "id_modulo" 	  => "INVENTARIO",
        "visible"   	  => "0",
        "orden"     	  => "40",
        "carpeta"     	  => "unidades",
        "archivo"   	  => "eliminar",
        "requiere_item"   => "1",
        "tabla_principal" => "unidades",
        "tipo_enlace"     => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_unidades AS(
            SELECT
                a.codigo AS id,
                a.nombre AS NOMBRE,
                b.nombre AS TIPO_UNIDAD,
                '' AS PRINCIPAL,
                a.factor_conversion AS FACTOR_CONVERSION
            FROM
                job_unidades AS a, job_tipos_unidades AS b
            WHERE
                a.codigo_tipo_unidad = b.codigo AND
                a.codigo_unidad_principal='0' AND
                a.nombre!=''
        )
        UNION(
            SELECT
                a.codigo AS id,
                a.nombre AS NOMBRE,
                b.nombre AS TIPO_UNIDAD,
                c.nombre AS PRINCIPAL,
                a.factor_conversion AS FACTOR_CONVERSION
            FROM
                job_unidades AS a, job_unidades AS c, job_tipos_unidades AS b
            WHERE
                a.codigo_unidad_principal=c.codigo AND
                a.codigo_tipo_unidad = b.codigo AND
                a.nombre!=''
        );"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_unidades AS
        SELECT job_unidades.codigo AS id,
        job_unidades.codigo AS codigo,
        job_tipos_unidades.nombre AS tipo_unidad,
        job_unidades.nombre AS nombre
        FROM job_unidades, job_tipos_unidades
        WHERE job_unidades.codigo_tipo_unidad = job_tipos_unidades.codigo
        AND job_unidades.codigo != 0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_unidades AS
        SELECT job_unidades.codigo AS id,
        CONCAT(job_unidades.codigo, ' - ',
        job_unidades.nombre, '|', job_unidades.codigo) AS descripcion
        FROM job_unidades
        WHERE job_unidades.codigo != 0;"
    )
);
/*** 
	CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_unidades AS(
        SELECT
            a.codigo AS id,
            a.nombre AS NOMBRE,
            b.nombre AS TIPO_UNIDAD,
            '' AS PRINCIPAL,
            a.factor_conversion AS FACTOR_CONVERSION
        FROM
            job_unidades AS a, job_tipos_unidades AS b
        WHERE
            a.codigo_tipo_unidad = b.codigo AND
            a.codigo_unidad_principal='0' AND
            a.nombre!=''
    )
    UNION(
        SELECT
            a.codigo AS id,
            a.nombre AS NOMBRE,
            b.nombre AS TIPO_UNIDAD,
            c.nombre AS PRINCIPAL,
            a.factor_conversion AS FACTOR_CONVERSION
        FROM
            job_unidades AS a, job_unidades AS c, job_tipos_unidades AS b
        WHERE
            a.codigo_unidad_principal=c.codigo AND
            a.codigo_tipo_unidad = b.codigo AND
            a.nombre!=''
    );



     CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_unidades AS
     SELECT job_unidades.codigo AS id,
     job_unidades.codigo AS codigo,
     job_tipos_unidades.nombre AS tipo_unidad,
     job_unidades.nombre AS nombre
     FROM job_unidades, job_tipos_unidades
     WHERE job_unidades.codigo_tipo_unidad = job_tipos_unidades.codigo
	 AND job_unidades.codigo != 0;

     CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_unidades AS
      SELECT job_unidades.codigo AS id,
      CONCAT(job_unidades.codigo, ' - ',
      job_unidades.nombre, '|', job_unidades.codigo) AS descripcion
      FROM job_unidades
      WHERE job_unidades.codigo != 0;

***/
?>

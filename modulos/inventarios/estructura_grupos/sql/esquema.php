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

//  Definici�n de tablas
$tablas["estructura_grupos"] = array(
    "codigo"       => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'C�digo utilizado por la empresa'",
    "codigo_padre" => "SMALLINT(4) UNSIGNED ZEROFILL COMMENT 'Consecutivo interno para la base de datos del grupo padre dentro de la estructura de grupos (NULL: Grupo principal)'",
    "codigo_grupo" => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo interno para la base de datos del grupo al que pertenece'",    
    "descripcion"  => "VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'Descripci�n del grupo'",
    "orden"        => "INT(5) NULL DEFAULT '0' COMMENT 'Orden a salir en los listados'"
);

//  Definici�n de llaves primarias
$llavesPrimarias["estructura_grupos"] = "codigo";

//  Definici�n de campos �nicos
$llavesUnicas["estructura_grupos"] = array(
    "descripcion"
);

//  Definici{on de llaves Foraneas
$llavesForaneas["estructura_grupos"] = array(
    array(
        //  Nombre de la llave foranea
        "estructura_relacion_grupo",
        //  Nombre del campo en la tabla actual
        "codigo_grupo",
        //  Nombre de la tabla relacionada
        "grupos",
        //  Nombre del campo de la tabla relacionada
        "codigo"
    )
);

//  Inserci�n de datos iniciales***/
$registros["estructura_grupos"] = array(
    array(
		"codigo"       => "0",
		"codigo_padre" => "NULL",
		"codigo_grupo" => "0",
		"descripcion"  => ""
	)
);
//  Inserci�n de datos iniciales***/
$registros["componentes"] = array(
    array(
        "id"        	  => "GESTESGR",
        "padre"     	  => "SUBMDCIN",
        "id_modulo" 	  => "INVENTARIO",
        "visible"   	  => "1",
        "orden"     	  => "40",
        "carpeta"   	  => "estructura_grupos",
        "archivo"   	  => "menu",
        "requiere_item"   => "0",
        "tabla_principal" => "estructura_grupos",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "ADICESGR",
        "padre"     	  => "GESTESGR",
        "id_modulo" 	  => "INVENTARIO",
        "visible"   	  => "0",
        "orden"     	  => "0025",
        "carpeta"   	  => "estructura_grupos",
        "archivo"   	  => "adicionar",
        "requiere_item"   => "0",
        "tabla_principal" => "estructura_grupos",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "CONSESGR",
        "padre"     	  => "GESTESGR",
        "id_modulo" 	  => "INVENTARIO",
        "visible"   	  => "0",
        "orden"     	  => "0010",
        "carpeta"   	  => "estructura_grupos",
        "archivo"   	  => "consultar",
        "requiere_item"   => "1",
        "tabla_principal" => "estructura_grupos",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "MODIESGR",
        "padre"     	  => "GESTESGR",
        "id_modulo" 	  => "INVENTARIO",
        "visible"   	  => "0",
        "orden"     	  => "0015",
        "carpeta"   	  => "estructura_grupos",
        "archivo"   	  => "modificar",
        "requiere_item"   => "1",
        "tabla_principal" => "estructura_grupos",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "ELIMESGR",
        "padre"     	  => "GESTESGR",
        "id_modulo" 	  => "INVENTARIO",
        "visible"   	  => "0",
        "orden"     	  => "0020",
        "carpeta"   	  => "estructura_grupos",
        "archivo"   	  => "eliminar",
        "requiere_item"   => "1",
        "tabla_principal" => "estructura_grupos",
        "tipo_enlace"     => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_estructura_grupos AS
        (
            SELECT 
                a.codigo AS id,
                a.codigo AS CODIGO,
                a.descripcion AS DESCRIPCION,
                '' AS PADRE,
                c.descripcion AS RELACION,
                a.orden AS ORDEN
            FROM 
                job_estructura_grupos AS a,
                job_estructura_grupos AS b,
                job_grupos AS c
            WHERE 
                a.codigo_padre IS NULL AND
                a.codigo_grupo = c.codigo
        )
        UNION
        (
            SELECT 
                a.codigo AS id,
                a.codigo AS CODIGO,
                a.descripcion AS DESCRIPCION,
                b.descripcion AS PADRE,
                c.descripcion AS RELACION,
                a.orden AS ORDEN
            FROM 
                job_estructura_grupos AS a,
                job_estructura_grupos AS b,
                job_grupos AS c
            WHERE 
                a.codigo_padre = b.codigo AND
                a.codigo_grupo = c.codigo
        );"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_estructura_grupos AS
        SELECT job_estructura_grupos.codigo AS id,
        job_estructura_grupos.codigo AS CODIGO,
        job_estructura_grupos.descripcion AS DESCRIPCION
        FROM job_estructura_grupos;"
    )
);
/***
    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_estructura_grupos AS
    (
        SELECT 
            a.codigo AS id,
            a.codigo AS CODIGO,
            a.descripcion AS DESCRIPCION,
            '' AS PADRE,
            c.descripcion AS RELACION,
            a.orden AS ORDEN
        FROM 
            job_estructura_grupos AS a,
            job_estructura_grupos AS b,
            job_grupos AS c
        WHERE 
            a.codigo_padre IS NULL AND
            a.codigo_grupo = c.codigo
    )
    UNION
    (
        SELECT 
            a.codigo AS id,
            a.codigo AS CODIGO,
            a.descripcion AS DESCRIPCION,
            b.descripcion AS PADRE,
            c.descripcion AS RELACION,
            a.orden AS ORDEN
        FROM 
            job_estructura_grupos AS a,
            job_estructura_grupos AS b,
            job_grupos AS c
        WHERE 
            a.codigo_padre = b.codigo AND
            a.codigo_grupo = c.codigo
    );

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_estructura_grupos AS
    SELECT job_estructura_grupos.codigo AS id,
    job_estructura_grupos.codigo AS CODIGO,
    job_estructura_grupos.descripcion AS DESCRIPCION
    FROM job_estructura_grupos;
***/

?>

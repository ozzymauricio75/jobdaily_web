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

$borrarSiempre   = false;

// Definición de tablas
$tablas["resoluciones_dian"] = array(
    "codigo_sucursal"      	          => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Sucursal a la cual se le asigna la resolución'",
	"numero"                          => "VARCHAR(20) NOT NULL COMMENT 'Numero asignado por la DIAN para la resolucion'",
    "prefijo"                         => "VARCHAR(8) NOT NULL COMMENT 'Prefijo el cual el cual identifica los almacenes y/o cajas'",
    "fecha_inicia"                    => "DATE NOT NULL COMMENT 'Fecha a partir de la cual inicia el funcionamiento de la resolución DIAN'",
    "fecha_termina"                   => "DATE NOT NULL COMMENT 'Fecha donde termina el funcionamiento de la resolución DIAN'",
    "factura_inicial"                 => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Número con el cual inica la facturación segun la resolución'",
    "factura_final"                   => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Número con el cual finaliza la facturación segun la resolución'",
    "rango"				              => "INT(10) NOT NULL COMMENT'Numero de facturas faltantes para el final de la facturación'",
    "codigo_concepto_resolucion_dian" => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la tabla job_conceptos_resoluciones_dian'",
    "codigo_tipo_documento"	          => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la tabla job_tipos_documentos'",
    "tipo_resolucion"                 => "ENUM('1','2','3') NOT NULL DEFAULT '1' COMMENT '1->Autorizada 2->Habiltada 3->En tramite'",
    "estado"                          => "ENUM('0','1') NOT NULL DEFAULT '1' COMMENT '0->Inactiva 1->Activa'"
);

// Definición de llaves primarias
$llavesPrimarias["resoluciones_dian"] = "codigo_sucursal,numero";

// Definición de llaves foraneas
$llavesForaneas["resoluciones_dian"] = array(
    array(
        // Nombre de la llave
        "resolucion_dian_sucursal",
        // Nombre del campo clave de la tabla local
        "codigo_sucursal",
        // Nombre de la tabla relacionada
        "sucursales",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave
        "resolucion_dian_concepto_dian",
        // Nombre del campo clave de la tabla local
        "codigo_concepto_resolucion_dian",
        // Nombre de la tabla relacionada
        "conceptos_resoluciones_dian",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave
        "resolucion_dian_tipo_documento",
        // Nombre del campo clave de la tabla local
        "codigo_tipo_documento",
        // Nombre de la tabla relacionada
        "tipos_documentos",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    )
);


// Definición de tablas
$tablas["conceptos_resoluciones_dian"] = array(
	"codigo" => "INT(8) UNSIGNED ZEROFILL AUTO_INCREMENT NOT NULL COMMENT 'Consecutivo interno de la base de datos'",
	"nombre" => "VARCHAR(255) NOT NULL COMMENT 'Nombre del concepto'"
);

// Definición de llaves primarias
$llavesPrimarias["conceptos_resoluciones_dian"] = "codigo";

// Inserción de datos iniciales
$registros["conceptos_resoluciones_dian"] = array(
    array(
		"codigo" => "0",
		"nombre" => "",
    ),
    array(
		"codigo" => "1",
		"nombre" => "Factura por computador",
	),
    array(
		"codigo" => "2",
		"nombre" => "Factura POS",
	),
    array(
		"codigo" => "3",
		"nombre" => "Factura en papel",
	),
    array(
		"codigo" => "4",
		"nombre" => "Factura electrónica",
	)
);

// Inserción de datos iniciales
$registros["componentes"] = array(
    array(
        "id"              => "GESTREDI",
        "padre"           => "SUBMINTR",
        "id_modulo"       => "CONTABILIDAD",
        "orden"           => "5",
        "visible"         => "1",
        "carpeta"         => "resoluciones_dian",
        "archivo"         => "menu",
        "requiere_item"   => "1",
        "tabla_principal" => "resoluciones_dian",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "ADICREDI",
        "padre"           => "GESTREDI",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "0",
        "orden"           => "10",
        "carpeta"         => "resoluciones_dian",
        "archivo"         => "adicionar",
        "requiere_item"   => "0",
        "tabla_principal" => "resoluciones_dian",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "CONSREDI",
        "padre"           => "GESTREDI",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "0",
        "orden"           => "20",
        "carpeta"         => "resoluciones_dian",
        "archivo"         => "consultar",
        "requiere_item"   => "1",
        "tabla_principal" => "resoluciones_dian",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "MODIREDI",
        "padre"           => "GESTREDI",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "0",
        "orden"           => "30",
        "carpeta"         => "resoluciones_dian",
        "archivo"         => "modificar",
        "requiere_item"   => "1",
        "tabla_principal" => "resoluciones_dian",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "ELIMREDI",
        "padre"           => "GESTREDI",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "0",
        "orden"           => "40",
        "carpeta"         => "resoluciones_dian",
        "archivo"         => "eliminar",
        "requiere_item"   => "1",
        "tabla_principal" => "resoluciones_dian",
        "tipo_enlace"     => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_resoluciones_dian AS
         SELECT CONCAT(
                    job_resoluciones_dian.codigo_sucursal,
                    '|',
                    job_resoluciones_dian.numero
                ) AS id,
                job_sucursales.codigo AS id_sucursal,
                job_sucursales.nombre AS SUCURSAL,
                job_resoluciones_dian.numero AS NUMERO,
                job_tipos_documentos.descripcion AS TIPO_DOCUMENTO,
                job_resoluciones_dian.fecha_inicia AS FECHA_INICIA,
                job_resoluciones_dian.fecha_termina AS FECHA_TERMINA,
                job_resoluciones_dian.prefijo AS PREFIJO,
                FORMAT(job_resoluciones_dian.factura_inicial,0) AS FACTURA_INICIAL,
                FORMAT(job_resoluciones_dian.factura_final,0) AS FACTURA_FINAL

        FROM 	job_resoluciones_dian,
                job_sucursales,
                job_tipos_documentos
        WHERE 	job_resoluciones_dian.codigo_sucursal = job_sucursales.codigo AND
                job_resoluciones_dian.codigo_tipo_documento = job_tipos_documentos.codigo;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_resoluciones_dian AS
         SELECT	CONCAT(
                    job_resoluciones_dian.codigo_sucursal,
                    '|',
                    job_resoluciones_dian.numero
                ) AS id,
                job_resoluciones_dian.codigo_sucursal AS id_sucursal,
                job_resoluciones_dian.fecha_inicia AS fecha_inicia,
                job_resoluciones_dian.fecha_termina AS fecha_termina,
                job_sucursales.nombre AS sucursal,
                job_resoluciones_dian.numero AS numero,
                job_tipos_documentos.descripcion AS tipo_documento

        FROM 	job_resoluciones_dian,
                job_sucursales,
                job_tipos_documentos

        WHERE 	job_resoluciones_dian.codigo_sucursal = job_sucursales.codigo AND
                job_resoluciones_dian.codigo_tipo_documento = job_tipos_documentos.codigo;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_resolucion_dian AS
         SELECT CONCAT(
                    job_resoluciones_dian.codigo_sucursal,
                    '|',
                    job_resoluciones_dian.numero
                ) AS id,
                job_resoluciones_dian.estado AS id_estado,
                job_resoluciones_dian.codigo_sucursal AS id_sucursal,
                job_tipos_documentos.codigo AS id_tipo_documento,
                CONCAT(
                    'Almacen: ',job_sucursales.nombre, ' - ',
                    'Numero: ',job_resoluciones_dian.numero, ' - ',
                    'Fecha: ',job_resoluciones_dian.fecha_inicia, ' - ',
                    'Prefijo: ',job_resoluciones_dian.prefijo
                ) AS descripcion

        FROM 	job_resoluciones_dian,
                job_sucursales,
                job_tipos_documentos
        WHERE   job_resoluciones_dian.codigo_sucursal = job_sucursales.codigo AND
                job_resoluciones_dian.codigo_tipo_documento = job_tipos_documentos.codigo;"
    )
);

?>

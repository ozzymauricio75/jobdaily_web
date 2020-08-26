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
// Eliminar la tabla y crearla de nuevo cada vez que se ejecute el script de creación
/*$borrarSiempre = false;

$borrarSiempre["aprobaciones"] = false;
//Definicion de tablas
$tablas ["aprobaciones"] = array(
    "codigo"                        => "INT(9) UNSIGNED ZEROFILL AUTO_INCREMENT NOT NULL COMMENT 'Codigo interno del proyecto'",
    /*tabla proyectos*/
/*    "codigo_proyecto"               => "INT(9) UNSIGNED ZEROFILL NOT NULL DEFAULT '0' COMMENT 'Codigo interno del proyecto'",
    "documento_identidad_proveedor" => "VARCHAR(12) NOT NULL COMMENT 'Llave principal de la tabla de terceros'",
    /***************/
/*    "numero_orden_compra"           => "INT(9) UNSIGNED ZEROFILL NOT NULL COMMENT 'Numero consecutivo de la orden de compra'",
    "codigo_tipo_documento"         => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código asignado por el usuario'",
    "numero_documento_proveedor"    => "VARCHAR(15) NOT NULL COMMENT 'Número del documento enviado por el proveedor'",
    "valor_documento"               => "DECIMAL(15,2) NULL COMMENT 'Valor del documento del proveedor'",
    "estado_residente"              => "ENUM('0','1','2') NOT NULL DEFAULT '0' COMMENT '0->No Aprobado 1->Aprobado residente 2-> Anulado'",
    "estado_director"               => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT '0->No Aprobado 1->Aprobado director'",
    /******************/
/*    "fecha_registro_residente"      => "DATE NOT NULL COMMENT 'Fecha ingreso al sistema x el residente'",
    "fecha_registro_director"       => "DATE NOT NULL COMMENT 'Fecha ingreso al sistema x el director'",
    "observaciones"                 => "VARCHAR(234) COMMENT 'Observacion general para la orden de compra'",
    "estado_factura"                => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT '0->No Cruzado 1->Cruzada'"
);

// Definición de llaves primarias
$llavesPrimarias["aprobaciones"] = "codigo";

// Definición de campos únicos
$llavesUnicas["aprobaciones"] = array(
    "codigo_proyecto,documento_identidad_proveedor,numero_orden_compra,numero_documento_proveedor"
);

// Definición de llaves foráneas
// Definición de llaves foráneas
$llavesForaneas["aprobaciones"] = array(
    array(
        // Nombre de la llave
        "aprobaciones_proyectos",
        // Nombre del campo clave de la tabla local
        "codigo_proyecto",
        // Nombre de la tabla relacionada
        "proyectos",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    ),
     array(
        // Nombre de la llave
        "aprobaciones_proveedor",
        // Nombre del campo clave de la tabla local
        "documento_identidad_proveedor",
        // Nombre de la tabla relacionada
        "proveedores",
        // Nombre del campo clave en la tabla relacionada
        "documento_identidad"
    ),
    array(
        // Nombre de la llave
        "aprobaciones_tipo_documentos",
        // Nombre del campo clave de la tabla local
        "codigo_tipo_documento",
        // Nombre de la tabla relacionada
        "tipos_documentos",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    )
);

$registros["componentes"] = array(
    array(
        "id"              => "GESTAPDO",
        "padre"           => "SUBMCOMP",
        "id_modulo"       => "PROVEEDORES",
        "visible"         => "1",
        "orden"           => "805",
        "carpeta"         => "aprobaciones",
        "archivo"         => "menu",
        "requiere_item"   => "0",
        "tabla_principal" => "aprobaciones",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "ADICAPRO",
        "padre"           => "GESTAPDO",
        "id_modulo"       => "PROVEEDORES",
        "visible"         => "0",
        "orden"           => "20",
        "carpeta"         => "aprobaciones",
        "archivo"         => "adicionar",
        "global"          => "0",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "CONSAPRO",
        "padre"           => "GESTAPDO",
        "id_modulo"       => "PROVEEDORES",
        "visible"         => "0",
        "orden"           => "30",
        "carpeta"         => "aprobaciones",
        "archivo"         => "consultar",
        "global"          => "0",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "MODIAPRO",
        "padre"           => "GESTAPDO",
        "id_modulo"       => "PROVEEDORES",
        "visible"         => "0",
        "orden"           => "40",
        "carpeta"         => "aprobaciones",
        "archivo"         => "modificar",
        "global"          => "0",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "ANULAPRO",
        "padre"           => "GESTAPDO",
        "id_modulo"       => "PROVEEDORES",
        "visible"         => "0",
        "orden"           => "50",
        "carpeta"         => "aprobaciones",
        "archivo"         => "anular",
        "global"          => "0",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "ELIMAPRO",
        "padre"           => "GESTAPDO",
        "id_modulo"       => "PROVEEDORES",
        "visible"         => "0",
        "orden"           => "60",
        "carpeta"         => "aprobaciones",
        "archivo"         => "eliminar",
        "global"          => "0",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "APREAPRO",
        "padre"           => "GESTAPDO",
        "id_modulo"       => "PROVEEDORES",
        "visible"         => "0",
        "orden"           => "70",
        "carpeta"         => "aprobaciones",
        "archivo"         => "aprobar_residente",
        "global"          => "0",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "APDIAPRO",
        "padre"           => "GESTAPDO",
        "id_modulo"       => "PROVEEDORES",
        "visible"         => "0",
        "orden"           => "80",
        "carpeta"         => "aprobaciones",
        "archivo"         => "aprobar_director",
        "global"          => "0",
        "tipo_enlace"     => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_aprobaciones AS
        SELECT  job_aprobaciones.codigo AS id,
                job_aprobaciones.codigo AS CODIGO,
                job_proyectos.nombre AS PROYECTO,

                CONCAT(
                    IF(job_terceros.primer_nombre IS NOT NULL,
                        CONCAT(
                            CONCAT(job_terceros.primer_nombre,' '),
                            IF(job_terceros.segundo_nombre IS NOT NULL,CONCAT(job_terceros.segundo_nombre,' '),''),
                            IF(job_terceros.primer_apellido IS NOT NULL,CONCAT(job_terceros.primer_apellido,' '),''),
                            IF(job_terceros.segundo_apellido IS NOT NULL,CONCAT(job_terceros.segundo_apellido,''),'')
                        ),
                        job_terceros.razon_social
                    )
                ) AS RAZON_SOCIAL,
                job_aprobaciones.numero_orden_compra AS ORDEN_COMPRA,
                FORMAT(job_aprobaciones.valor_documento,0) AS VALOR,
                job_tipos_documentos.descripcion AS TIPO_DOCUMENTO,
                job_aprobaciones.numero_documento_proveedor AS FACTURA,
                CONCAT('ESTADO_RESIDENTE_',job_aprobaciones.estado_residente) AS ESTADO_RESIDENTE,
                CONCAT('ESTADO_DIRECTOR_',job_aprobaciones.estado_director) AS ESTADO_DIRECTOR,
                job_aprobaciones.fecha_registro_residente AS FECHA_REGISTRO_RESIDENTE,
                job_aprobaciones.fecha_registro_director AS FECHA_REGISTRO_DIRECTOR,
                job_aprobaciones.observaciones AS OBSERVACIONES 
        FROM    job_proyectos,
                job_aprobaciones,
                job_terceros,
                job_tipos_documentos,
                job_tipos_documento_identidad
        WHERE   
                job_aprobaciones.codigo_proyecto = job_proyectos.codigo
                AND job_aprobaciones.documento_identidad_proveedor = job_terceros.documento_identidad
                AND job_aprobaciones.codigo_tipo_documento = job_tipos_documentos.codigo
                AND job_tipos_documento_identidad.codigo = job_terceros.codigo_tipo_documento 
                AND job_aprobaciones.codigo != 0 
        ORDER BY
                job_aprobaciones.estado_director,job_aprobaciones.codigo ASC;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_aprobaciones AS
        SELECT  job_aprobaciones.codigo AS id,
                job_aprobaciones.codigo AS codigo,
                job_proyectos.nombre AS proyecto,

                CONCAT(
                    IF(job_terceros.primer_nombre IS NOT NULL,
                        CONCAT(
                            CONCAT(job_terceros.primer_nombre,' '),
                            IF(job_terceros.segundo_nombre IS NOT NULL,CONCAT(job_terceros.segundo_nombre,' '),''),
                            IF(job_terceros.primer_apellido IS NOT NULL,CONCAT(job_terceros.primer_apellido,' '),''),
                            IF(job_terceros.segundo_apellido IS NOT NULL,CONCAT(job_terceros.segundo_apellido,''),'')
                        ),
                        job_terceros.razon_social
                    )
                ) AS razon_social,
                job_aprobaciones.numero_orden_compra AS orden_compra,
                FORMAT(job_aprobaciones.valor_documento,0) AS valor,
                job_tipos_documentos.descripcion AS tipo_documento,
                job_aprobaciones.numero_documento_proveedor AS factura,
                CONCAT('ESTADO_RESIDENTE_',job_aprobaciones.estado_residente) AS estado_residente,
                CONCAT('ESTADO_DIRECTOR_',job_aprobaciones.estado_director) AS estado_director,
                job_aprobaciones.fecha_registro_residente AS fecha_registro_residente,
                job_aprobaciones.fecha_registro_director AS fecha_registro_director,
                job_aprobaciones.observaciones AS observaciones,
                job_aprobaciones.estado_factura AS estado_factura
        FROM    job_proyectos,
                job_aprobaciones,
                job_terceros,
                job_tipos_documentos,
                job_tipos_documento_identidad
        WHERE   
                job_aprobaciones.codigo_proyecto = job_proyectos.codigo
                AND job_aprobaciones.documento_identidad_proveedor = job_terceros.documento_identidad
                AND job_aprobaciones.codigo_tipo_documento = job_tipos_documentos.codigo
                AND job_tipos_documento_identidad.codigo = job_terceros.codigo_tipo_documento 
                AND job_aprobaciones.codigo != 0 
        ORDER BY
                job_aprobaciones.estado_director,job_aprobaciones.codigo ASC;"
    )
)

/*************************************************/

/***
    DROP TABLE IF EXISTS job_menu_proyectos;
    DROP TABLE IF EXISTS job_buscador_proyectos;
    DROP TABLE IF EXISTS job_seleccion_proyectos;

	CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_proyectos AS
	SELECT 	job_proyectos.codigo AS id,
			job_proyectos.orden AS ORDEN,
			job_proyectos.codigo AS CODIGO,
			job_proyectos.nombre AS NOMBRE,
			job_empresas.razon_social AS EMPRESA,
			job_terceros.documento_identidad AS TERCERO

	FROM 	job_proyectos,
			job_empresas,
			job_terceros

	WHERE 	job_proyectos.codigo_empresa = job_empresas.codigo
			AND job_empresas.documento_identidad_tercero = job_terceros.documento_identidad
			AND job_proyectos.codigo != 0;

	CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_proyectos AS
	SELECT 	job_proyectos.codigo AS id,
			job_proyectos.codigo AS codigo, job_proyectos.nombre AS nombre,
			job_proyectos.nombre_corto AS nombre_corto,
			job_empresas.razon_social AS empresa,
			CONCAT(
			IF(job_terceros.primer_nombre IS NOT NULL,(
					CONCAT(
						IF(job_terceros.primer_nombre IS NOT NULL,CONCAT(job_terceros.primer_nombre,' '),''),
						IF(job_terceros.segundo_nombre IS NOT NULL,CONCAT(job_terceros.segundo_nombre,' '),''),
						IF(job_terceros.primer_apellido IS NOT NULL,CONCAT(job_terceros.primer_apellido,' '),''),
						IF(job_terceros.segundo_apellido IS NOT NULL,CONCAT(job_terceros.segundo_apellido,' '),'')
					)
				),
				job_terceros.razon_social
			)
			) AS tercero

	FROM 	job_proyectos,
			job_terceros,
			job_empresas

	WHERE 	job_proyectos.codigo_empresa = job_empresas.codigo
			AND	job_empresas.documento_identidad_tercero = job_terceros.documento_identidad
			AND job_proyectos.codigo != 0;

	CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_proyectos AS
	SELECT 	job_proyectos.codigo AS id,
			CONCAT(job_proyectos.nombre,'|', job_proyectos.codigo) AS nombre

	FROM 	job_proyectos

	WHERE 	job_proyectos.codigo != 0 ORDER BY job_proyectos.nombre ASC;
***/
?>

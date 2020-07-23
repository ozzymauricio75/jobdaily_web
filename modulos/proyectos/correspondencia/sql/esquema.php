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
$borrarSiempre = false;

// Definición de tablas
$tablas ["correspondencia"] = array(
    "codigo"                        => "INT(9) UNSIGNED ZEROFILL AUTO_INCREMENT NOT NULL COMMENT 'Codigo interno del proyecto'",

    /*tabla proyectos*/
    "codigo_proyecto"               => "INT(9) UNSIGNED ZEROFILL NOT NULL DEFAULT '0' COMMENT 'Codigo interno del proyecto'",
    "documento_identidad_proveedor" => "VARCHAR(12) NOT NULL COMMENT 'Llave principal de la tabla de terceros'",
    /***************/
    "numero_orden_compra"           => "INT(9) UNSIGNED ZEROFILL NOT NULL COMMENT 'Numero consecutivo de la orden de compra'",
    "codigo_tipo_documento"         => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código asignado por el usuario'",
    "numero_documento_proveedor"    => "VARCHAR(15) NOT NULL COMMENT 'Número del documento enviado por el proveedor'",
    "valor_documento"               => "DECIMAL(15,2) NULL COMMENT 'Valor del documento del proveedor'",
    "estado"                        => "ENUM('0','1','2','3') NOT NULL DEFAULT '0' COMMENT '0->Recepcionado 1->Entregado 2->Anulado'",
    /******************/
    "fecha_recepcion"               => "DATE NOT NULL COMMENT 'Fecha ingreso al sistema'",
    "fecha_vencimiento"             => "DATE NOT NULL COMMENT 'Fecha ingreso al sistema'",
    "fecha_envio"                   => "DATE NOT NULL COMMENT 'Fecha ultima modificación'",
    "observaciones"                 => "VARCHAR(234) COMMENT 'Observacion general para la orden de compra'"
);

// Definición de llaves primarias
$llavesPrimarias["correspondencia"] = "codigo";

// Definición de campos únicos

$llavesUnicas["correspondencia"] = array(
    "codigo_proyecto,documento_identidad_proveedor,numero_documento_proveedor"
);

// Definición de llaves foráneas
$llavesForaneas["correspondencia"] = array(
    array(
        // Nombre de la llave
        "correspondencia_proyectos",
        // Nombre del campo clave de la tabla local
        "codigo_proyecto",
        // Nombre de la tabla relacionada
        "proyectos",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    ),
     array(
        // Nombre de la llave
        "correspondencia_proveedor",
        // Nombre del campo clave de la tabla local
        "documento_identidad_proveedor",
        // Nombre de la tabla relacionada
        "proveedores",
        // Nombre del campo clave en la tabla relacionada
        "documento_identidad"
    ),
    array(
        // Nombre de la llave
        "correspondencia_tipo_documentos",
        // Nombre del campo clave de la tabla local
        "codigo_tipo_documento",
        // Nombre de la tabla relacionada
        "tipos_documentos",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    )
);

// Inserción de datos iniciales proyectos

/*$registros["correspondencia"] = array(
	array(
		"codigo"                        => "0",
		"codigo_proyecto"               => "0",
		"documento_identidad_proveedor" => "0",
        "codigo_tipo_documento"         => "0",
		"numero_documento_proveedor"    => "0",
		"valor_documento"               => "0",
		"estado"                        => "",
        "fecha_recepcion"               => "0000-00-00",
        "fecha_vencimiento"             => "0000-00-00",
        "fecha_envio"                   => "0000-00-00",
        "observaciones"                 => ""
	)
);*/

$registros["componentes"] = array(
    array(
        "id"            => "GESTCORR",
        "padre"         => "MENUPROY",
        "id_modulo"     => "PROYECTOS",
        "visible"       => "1",
        "orden"         => "130",
        "carpeta"       => "correspondencia",
        "archivo"       => "menu",
        "global"        => "0",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"           => "ADICCORR",
        "padre"        => "GESTCORR",
        "id_modulo"    => "PROYECTOS",
        "visible"      => "0",
        "orden"        => "20",
        "carpeta"      => "correspondencia",
        "archivo"      => "adicionar",
        "global"       => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "CONSCORR",
        "padre"        => "GESTCORR",
        "id_modulo"    => "PROYECTOS",
        "visible"      => "0",
        "orden"        => "30",
        "carpeta"      => "correspondencia",
        "archivo"      => "consultar",
        "global"       => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "MODICORR",
        "padre"        => "GESTCORR",
        "id_modulo"    => "PROYECTOS",
        "visible"      => "0",
        "orden"        => "40",
        "carpeta"      => "correspondencia",
        "archivo"      => "modificar",
        "global"       => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "ANULCORR",
        "padre"        => "GESTCORR",
        "id_modulo"    => "PROYECTOS",
        "visible"      => "0",
        "orden"        => "50",
        "carpeta"      => "correspondencia",
        "archivo"      => "anular",
        "global"       => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "ELIMCORR",
        "padre"        => "GESTCORR",
        "id_modulo"    => "PROYECTOS",
        "visible"      => "0",
        "orden"        => "60",
        "carpeta"      => "correspondencia",
        "archivo"      => "eliminar",
        "global"       => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "RECICORR",
        "padre"        => "GESTCORR",
        "id_modulo"    => "PROYECTOS",
        "visible"      => "0",
        "orden"        => "70",
        "carpeta"      => "correspondencia",
        "archivo"      => "recibir",
        "global"       => "0",
        "tipo_enlace"  => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_correspondencia AS
        SELECT  job_correspondencia.codigo AS id,
                job_correspondencia.codigo AS CODIGO,
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
                job_correspondencia.numero_orden_compra AS ORDEN_COMPRA,
                FORMAT(job_correspondencia.valor_documento,0) AS VALOR,
                job_tipos_documentos.descripcion AS TIPO_DOCUMENTO,
                job_correspondencia.numero_documento_proveedor AS FACTURA,
                CONCAT('ESTADO_',job_correspondencia.estado) AS ESTADO,
                job_correspondencia.fecha_recepcion AS FECHA_RECEPCION,
                job_correspondencia.fecha_vencimiento AS FECHA_VENCIMIENTO,
                job_correspondencia.fecha_envio AS FECHA_ENVIO,
                job_correspondencia.observaciones AS OBSERVACIONES 
        FROM    job_proyectos,
                job_correspondencia,
                job_terceros,
                job_tipos_documentos,
                job_tipos_documento_identidad
        WHERE   
                job_correspondencia.codigo_proyecto = job_proyectos.codigo
                AND job_correspondencia.documento_identidad_proveedor = job_terceros.documento_identidad
                AND job_correspondencia.codigo_tipo_documento = job_tipos_documentos.codigo
                AND job_tipos_documento_identidad.codigo = job_terceros.codigo_tipo_documento 
                AND job_correspondencia.codigo != 0 
        ORDER BY
                job_correspondencia.estado,job_correspondencia.codigo ASC;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_correspondencia AS
        SELECT  job_correspondencia.codigo AS id,
                job_correspondencia.codigo AS codigo,
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
                ) AS RAZON_SOCIAL,
                job_correspondencia.numero_orden_compra AS orden_compra,
                FORMAT(job_correspondencia.valor_documento,0) AS valor,
                job_tipos_documentos.descripcion AS tipo_documento,
                job_correspondencia.numero_documento_proveedor AS factura,
                CONCAT('ESTADO_',job_correspondencia.estado) AS estado,
                job_correspondencia.fecha_recepcion AS fecha_recepcion,
                job_correspondencia.fecha_vencimiento AS fecha_vencimiento,
                job_correspondencia.fecha_envio AS fecha_envio,
                job_correspondencia.observaciones AS observaciones 
        FROM    job_proyectos,
                job_correspondencia,
                job_terceros,
                job_tipos_documentos,
                job_tipos_documento_identidad
        WHERE   
                job_correspondencia.codigo_proyecto = job_proyectos.codigo
                AND job_correspondencia.documento_identidad_proveedor = job_terceros.documento_identidad
                AND job_correspondencia.codigo_tipo_documento = job_tipos_documentos.codigo
                AND job_tipos_documento_identidad.codigo = job_terceros.codigo_tipo_documento 
                AND job_correspondencia.codigo != 0;"
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

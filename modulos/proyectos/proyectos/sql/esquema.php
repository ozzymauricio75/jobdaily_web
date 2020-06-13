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
$tablas ["proyectos"] = array(
    "codigo"                   => "INT(9) UNSIGNED ZEROFILL AUTO_INCREMENT NOT NULL COMMENT 'Codigo interno del proyecto'",
    /*tabla empresas*/
    "codigo_empresa_ejecuta"   => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo interno de la empresa que ejecuta el proyecto'",
    "codigo_sucursal_ejecuta"  => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo interno consorcio o uniopn temporal que ejecuta proyecto'",
    /***************/
    "nombre"                   => "VARCHAR(60) NOT NULL COMMENT 'Nombre que identifica el proyecto'",
    "fecha_cierre"             => "DATE DEFAULT NULL COMMENT 'Fecha que estuvo activo el proyecto'",
    "activo"                   => "ENUM('0','1') NOT NULL DEFAULT '1' COMMENT 'Indicador de estado del proyecto: 0=Cerrado, 1=Abierto'",
    /*tabla municipios*/
    "codigo_iso"               => "VARCHAR(2) NOT NULL COMMENT 'Llave principal de la tabla paises'",
    "codigo_dane_departamento" => "VARCHAR(2) NOT NULL COMMENT 'Codigo DANE'",
    "codigo_dane_municipio"    => "VARCHAR(3) NOT NULL COMMENT 'Codigo DANE'",
    /******************/
    "direccion_proyecto"       => "VARCHAR(60) NULL COMMENT 'Direccion donde se encuentra ubicado el proyecto'",
    "valor_proyecto"           => "DECIMAL(15,2) NULL COMMENT 'Valor del proyecto'"
);

// Definición de llaves primarias
$llavesPrimarias["proyectos"] = "codigo";

// Definición de campos únicos

$llavesUnicas["proyectos"] = array(
    "codigo_empresa_ejecuta,codigo_sucursal_ejecuta,nombre",
);

// Definición de llaves foráneas
$llavesForaneas["proyectos"] = array(
    array(
        // Nombre de la llave
        "proyecto_empresa_ejecuta",
        // Nombre del campo clave de la tabla local
        "codigo_empresa_ejecuta",
        // Nombre de la tabla relacionada
        "empresas",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    ),
     array(
        // Nombre de la llave
        "proyecto_sucursal_ejecuta",
        // Nombre del campo clave de la tabla local
        "codigo_sucursal_ejecuta",
        // Nombre de la tabla relacionada
        "sucursales",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave
        "sucursal_id_municipio",
        // Nombre del campo clave de la tabla local
        "codigo_iso,codigo_dane_departamento,codigo_dane_municipio",
        // Nombre de la tabla relacionada
        "municipios",
        // Nombre del campo clave en la tabla relacionada
        "codigo_iso,codigo_dane_departamento,codigo_dane_municipio"
    )
);

// Inserción de datos iniciales proyectos

$registros["proyectos"] = array(
	array(
		"codigo"                        => "0",
		"codigo_empresa_ejecuta"        => "0",
		"codigo_sucursal_ejecuta"       => "0",
        "nombre"                    	=> "",
		"fecha_cierre"              	=> "0000-00-00",
		"activo"                    	=> "1",
		"codigo_iso"                    => "",
        "codigo_dane_departamento"      => "",
        "codigo_dane_municipio"         => "",
		"direccion_proyecto"         	=> "",
        "valor_proyecto"                => 0,
	)
);

$registros["componentes"] = array(
    array(
        "id"            => "SUBMPROY",
        "padre"         => "MENUPROY",
        "id_modulo"     => "PROYECTOS",
        "visible"       => "1",
        "orden"         => "120",
        "carpeta"       => "proyectos",
        "archivo"       => "menu",
        "global"        => "0",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"           => "ADICPROY",
        "padre"        => "SUBMPROY",
        "id_modulo"    => "PROYECTOS",
        "visible"      => "0",
        "orden"        => "20",
        "carpeta"      => "proyectos",
        "archivo"      => "adicionar",
        "global"       => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "CONSPROY",
        "padre"        => "SUBMPROY",
        "id_modulo"    => "PROYECTOS",
        "visible"      => "0",
        "orden"        => "30",
        "carpeta"      => "proyectos",
        "archivo"      => "consultar",
        "global"       => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "MODIPROY",
        "padre"        => "SUBMPROY",
        "id_modulo"    => "PROYECTOS",
        "visible"      => "0",
        "orden"        => "40",
        "carpeta"      => "proyectos",
        "archivo"      => "modificar",
        "global"       => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "ELIMPROY",
        "padre"        => "SUBMPROY",
        "id_modulo"    => "PROYECTOS",
        "visible"      => "0",
        "orden"        => "50",
        "carpeta"      => "proyectos",
        "archivo"      => "eliminar",
        "global"       => "0",
        "tipo_enlace"  => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_proyectos AS
        SELECT  job_proyectos.codigo AS id,
                job_proyectos.codigo AS CODIGO,
                job_proyectos.nombre AS NOMBRE,
                FORMAT(job_proyectos.valor_proyecto,0) AS VALOR_PROYECTO,
                job_terceros.razon_social AS EMPRESA,
                job_sucursales.nombre AS CONSORCIO,
                job_terceros.documento_identidad AS TERCERO,
                IF(job_proyectos.activo = '0', 'Cerrado', 'Abierto') AS ESTADO

        FROM    job_proyectos,
                job_empresas,
                job_terceros,
                job_sucursales

        WHERE   job_proyectos.codigo_empresa_ejecuta = job_empresas.codigo
                AND job_proyectos.codigo_sucursal_ejecuta = job_sucursales.codigo
                AND job_empresas.documento_identidad_tercero = job_terceros.documento_identidad
                AND job_proyectos.codigo != 0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_proyectos AS
        SELECT 	job_proyectos.codigo AS id,
                job_proyectos.nombre AS nombre,
                job_empresas.razon_social AS empresa,
                job_sucursales.nombre AS sucursal,
                job_proyectos.valor_proyecto AS valor_proyecto,
                IF(job_proyectos.activo = '0', 'Cerrado', 'Abierto') AS activo,
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
                )) AS tercero

        FROM 	job_proyectos,
                job_terceros,
                job_empresas,
                job_sucursales

        WHERE 	job_proyectos.codigo_empresa_ejecuta = job_empresas.codigo
                AND job_proyectos.codigo_sucursal_ejecuta = job_sucursales.codigo
                AND	job_empresas.documento_identidad_tercero = job_terceros.documento_identidad
                AND job_proyectos.codigo != 0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_proyectos AS
        SELECT 	job_proyectos.codigo AS id,
                CONCAT(job_proyectos.nombre,'|', job_proyectos.codigo) AS nombre
        FROM 	job_proyectos
        WHERE 	job_proyectos.codigo != 0 ORDER BY job_proyectos.nombre ASC;"
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

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
$tablas ["sucursales"] = array(
    "codigo"                       => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código interno de la sucursal'",
    /*tabla empresas*/
    "codigo_empresa"               => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código interno de la empresa con la que se relaciona el almacen'",
    /***************/
    "nombre"                       => "VARCHAR(60) NOT NULL COMMENT 'Nombre que identifica el almacen'",
    "nombre_corto"                 => "CHAR(10) NOT NULL COMMENT 'Nombre que identifica el almacen en consultas'",
    "fecha_cierre"                 => "DATE DEFAULT NULL COMMENT 'Fecha que estuvo activo el almacen'",
    "activo"                       => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT 'Indicador de estado del almacen: 0=Inactiva, 1=Activa'",
    /*tabla municipios*/
    "codigo_iso"                   => "VARCHAR(2) NOT NULL COMMENT 'Llave principal de la tabla paises'",
    "codigo_dane_departamento"     => "VARCHAR(2) NOT NULL COMMENT 'Código DANE'",
    "codigo_dane_municipio"        => "VARCHAR(3) NOT NULL COMMENT 'Código DANE'",
    /******************/
    "direccion_residencia"         => "VARCHAR(60) NOT NULL COMMENT 'Direccion donde se encuentra la persona o empresa'",
    "telefono_1"                   => "VARCHAR(15) NULL COMMENT 'Primer numero de telefono del lugar de residencia'",
    "telefono_2"                   => "VARCHAR(15) NULL COMMENT 'Segundo numero de telefono del lugar de residencia'",
    "celular"                      => "VARCHAR(15) NULL COMMENT 'Numero de telefono celular'",
    "codigo_empresa_consolida"     => "SMALLINT(3) UNSIGNED ZEROFILL COMMENT 'Codigo interno empresa que consolida'",
    "codigo_sucursal_consolida"    => "MEDIUMINT(5) UNSIGNED ZEROFILL COMMENT 'Codigo interno sucursal que consolida'",
    "tipo_empresa"                 => "ENUM('1','2','3','4') NOT NULL DEFAULT '1' COMMENT 'Indicador de tipo empresa: 1=Distribuidoras mayoristas, 2=Ventas publico, 3=Ambas, 4=Empresa soporte'",
    "orden"                        => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Orden sucursales en listados'",
    "maneja_kardex"                => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT 'Indicador como se maneja el kardex: 0=No, 1=Si'",
    "realiza_orden_compra"         => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT 'Sucursal autorizada para realizar ordenes de compras'",
    "inventarios_mercancia"        => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT 'Permite el manejo de inventarios: 0=No, 1=Si'",
    "cartera_clientes_mayoristas"  => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT 'Permite el manejo de cartera de clientes mayoristas: 0=No, 1=Si'",
    "cartera_clientes_detallistas" => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT 'Permite el manejo de cartera de clientes minoristas: 0=No, 1=Si'",
    "cuentas_pagar_proveedores"    => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT 'Permite el manejo de cuentas por pagar proveedores: 0=No, 1=Si'",
    "nomina"                       => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT 'Permite el manejo de  nómina: 0=No, 1=Si'",
    "contabilidad"                 => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT 'permite el manejo contable: 0=No, 1=Si'",
    "tipo"                         => "ENUM('0','1','2') NOT NULL DEFAULT '0' COMMENT 'Indicador de tipo 0=Principal 1=Consorcio 2=Union temporal'"
);

// Definición de llaves primarias
$llavesPrimarias["sucursales"] = "codigo";

// Definición de campos únicos

$llavesUnicas["sucursales"] = array(
    "codigo_empresa,nombre"
);

// Definición de llaves foráneas
$llavesForaneas["sucursales"] = array(
    array(
        // Nombre de la llave
        "sucursal_codigo_empresa",
        // Nombre del campo clave de la tabla local
        "codigo_empresa",
        // Nombre de la tabla relacionada
        "empresas",
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

// Inserción de datos iniciales sucursales

$registros["sucursales"] = array(
	array(
		"codigo"                        => "0",
		"codigo_empresa"                => "0",
		"nombre"                    	=> "",
		"fecha_cierre"              	=> "0000-00-00",
		"nombre_corto"              	=> "",
		"activo"                    	=> "1",
		"codigo_iso"                    => "",
        "codigo_dane_departamento"      => "",
        "codigo_dane_municipio"         => "",
		"direccion_residencia"      	=> "",
		"telefono_1"                	=> "",
		"telefono_2"                	=> "",
		"celular"                   	=> "",
		"codigo_empresa_consolida"  	=> "0",
		"codigo_sucursal_consolida" 	=> "0",
		"tipo_empresa"              	=> "1",
		"orden"                     	=> "0",
		"maneja_kardex"             	=> "0",
		"realiza_orden_compra"      	=> "0",
		"inventarios_mercancia"     	=> "0",
		"cartera_clientes_mayoristas"	=> "0",
		"cartera_clientes_detallistas" 	=> "0",
		"cuentas_pagar_proveedores"    	=> "0",
		"nomina"                     	=> "0",
		"contabilidad"               	=> "0"
	)
);

$registros["componentes"] = array(
    array(
        "id"            => "GESTSUCU",
        "padre"         => "SUBMESTC",
        "id_modulo"     => "ADMINISTRACION",
        "visible"       => "1",
        "orden"         => "200",
        "carpeta"       => "sucursales",
        "archivo"       => "menu",
        "global"        => "0",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"           => "ADICSUCU",
        "padre"        => "GESTSUCU",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "0",
        "orden"        => "20",
        "carpeta"      => "sucursales",
        "archivo"      => "adicionar",
        "global"       => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "CONSSUCU",
        "padre"        => "GESTSUCU",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "0",
        "orden"        => "30",
        "carpeta"      => "sucursales",
        "archivo"      => "consultar",
        "global"       => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "MODISUCU",
        "padre"        => "GESTSUCU",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "0",
        "orden"        => "40",
        "carpeta"      => "sucursales",
        "archivo"      => "modificar",
        "global"       => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "ELIMSUCU",
        "padre"        => "GESTSUCU",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "0",
        "orden"        => "50",
        "carpeta"      => "sucursales",
        "archivo"      => "eliminar",
        "global"       => "0",
        "tipo_enlace"  => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_sucursales AS
        SELECT 	job_sucursales.codigo AS id,
                job_sucursales.codigo AS CODIGO,
                job_sucursales.nombre AS NOMBRE,
                IF (job_sucursales.tipo ='0',
                    'Principal',
                    IF (job_sucursales.tipo ='1',
                        'Consorcio',
                        'Unión temporal'
                    )
                ) AS TIPO
                
        FROM 	job_sucursales,
                job_empresas,
                job_terceros

        WHERE 	job_empresas.documento_identidad_tercero = job_terceros.documento_identidad
                AND job_sucursales.codigo_empresa = job_empresas.codigo
                AND job_sucursales.tipo <= '2'
                AND job_sucursales.codigo != 0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_sucursales AS
        SELECT 	job_sucursales.codigo AS id,
                job_sucursales.codigo AS codigo, job_sucursales.nombre AS nombre,
                job_sucursales.nombre_corto AS nombre_corto,
                IF (job_sucursales.tipo ='0',
                    'Principal',
                    IF (job_sucursales.tipo ='1',
                        'Consorcio',
                        'Unión temporal'
                    )
                ) AS tipo

        FROM 	job_sucursales,
                job_terceros,
                job_empresas

       WHERE    job_empresas.documento_identidad_tercero = job_terceros.documento_identidad
                AND job_sucursales.codigo_empresa = job_empresas.codigo
                AND job_sucursales.tipo <= '2'
                AND job_sucursales.codigo != 0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_sucursales AS
        SELECT 	job_sucursales.codigo AS id,
                CONCAT(job_sucursales.nombre,'|', job_sucursales.codigo) AS nombre
        FROM 	job_sucursales
        WHERE 	job_sucursales.codigo != 0 ORDER BY job_sucursales.nombre ASC;"
    )
)

/*************************************************/

/***
    DROP TABLE IF EXISTS job_menu_sucursales;
    DROP TABLE IF EXISTS job_buscador_sucursales;
    DROP TABLE IF EXISTS job_seleccion_sucursales;

	CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_sucursales AS
	SELECT 	job_sucursales.codigo AS id,
			job_sucursales.orden AS ORDEN,
			job_sucursales.codigo AS CODIGO,
			job_sucursales.nombre AS NOMBRE,
			job_empresas.razon_social AS EMPRESA,
			job_terceros.documento_identidad AS TERCERO

	FROM 	job_sucursales,
			job_empresas,
			job_terceros

	WHERE 	job_sucursales.codigo_empresa = job_empresas.codigo
			AND job_empresas.documento_identidad_tercero = job_terceros.documento_identidad
			AND job_sucursales.codigo != 0;

	CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_sucursales AS
	SELECT 	job_sucursales.codigo AS id,
			job_sucursales.codigo AS codigo, job_sucursales.nombre AS nombre,
			job_sucursales.nombre_corto AS nombre_corto,
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

	FROM 	job_sucursales,
			job_terceros,
			job_empresas

	WHERE 	job_sucursales.codigo_empresa = job_empresas.codigo
			AND	job_empresas.documento_identidad_tercero = job_terceros.documento_identidad
			AND job_sucursales.codigo != 0;

	CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_sucursales AS
	SELECT 	job_sucursales.codigo AS id,
			CONCAT(job_sucursales.nombre,'|', job_sucursales.codigo) AS nombre

	FROM 	job_sucursales

	WHERE 	job_sucursales.codigo != 0 ORDER BY job_sucursales.nombre ASC;
***/
?>

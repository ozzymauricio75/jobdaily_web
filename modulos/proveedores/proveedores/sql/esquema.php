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
* de APTITUD PARA UN PROPÓITO DETERMINADO. Consulte los detalles de
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
$tablas ["proveedores"] = array(
    "documento_identidad"                  => "VARCHAR(12) NOT NULL COMMENT 'Llave principal de la tabla de terceros'",
    "fabricante"                           => "ENUM('0','1') NOT NULL COMMENT 'Proveedor fabrica la mercancía 0->No 1->Si'",
    "distribuidor"                         => "ENUM('0','1') NOT NULL COMMENT 'Comercializa varias marcas 0->No 1->Si'",
    "servicios_tecnicos"                   => "ENUM('0','1') NOT NULL COMMENT 'Proveedor de servicios tecnicos 0->No 1->Si'",
    "transporte"                           => "ENUM('0','1') NOT NULL COMMENT 'Proveedor de transporte de mercancia 0->No 1->Si'",
    "publicidad"                           => "ENUM('0','1') NOT NULL COMMENT 'Proveedor de publicidad 0->No 1->Si'",
    "servicios_especiales"                 => "ENUM('0','1') NOT NULL COMMENT 'Proveedor de servicios especializados 0->No 1->Si. Son los proveedores que prestan servicios que no tienen que ver con el proceso comercial de la empresa pero si con el administrativo'",
    "codigo_servicio"                      => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Tipo de servicio especializado'",
    "fecha_inicio_cobro"                   => "ENUM('1','2') NOT NULL COMMENT 'Fecha en que inicia el cobro del proveedor 1->Fecha de la factura 2->Fecha de recibo de la mercancia'",
    "codigo_plazo_pago_contado"            => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL DEFAULT '0' COMMENT 'Numero de dias para pago de contado'",
    "codigo_plazo_pago_credito"            => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL DEFAULT '0' COMMENT 'Numero de dias para pago a credito'",
    "tasa_pago_credito"                    => "DECIMAL(5,2) UNSIGNED NULL DEFAULT '0' COMMENT 'Tasa de interes que cobra el proveedor para pagos a credito'",
    "porcentaje_primera_cuota"             => "DECIMAL(5,2) UNSIGNED NULL DEFAULT '0' COMMENT 'Porcentaje por vencimiento primera cuota en la cuenta por pagar'",
    "porcentaje_ultima_cuota"              => "DECIMAL(5,2) UNSIGNED NULL DEFAULT '0' COMMENT 'Porcentaje por vencimiento ultima cuota en la cuenta por pagar'",
    "pagos_anticipados"                    => "ENUM('0','1') NOT NULL COMMENT 'Se autoriza pagos anticipados 0->No 1->Si'",
    "pagos_efectivo"                       => "ENUM('0','1') NOT NULL COMMENT 'Se autoriza pagos en efectivo 0->No 1->Si'",
    "transferencia_electronica"            => "ENUM('0','1') NOT NULL COMMENT 'Se autoriza transferencias electronicas 0->No 1->Si'",
    "tarjeta_credito"                      => "ENUM('0','1') NOT NULL COMMENT 'Se autoriza pagos con tarjeta de credito 0->No 1->Si'",
    "triangulacion_bancaria"               => "ENUM('0','1') NOT NULL COMMENT'Se autoriza pagos por triangulacion bancaria 0->No 1->Si'",
    "tiempo_respuesta"                     => "SMALLINT(3) UNSIGNED NULL DEFAULT '0' COMMENT 'Numero de dias que tarda el proveedor en enviar la mercancia o prestar un servicio una vez generada una orden'",
    "porcentaje_flete"                     => "DECIMAL(5,2) UNSIGNED NULL DEFAULT '0' COMMENT 'Si el proveedor cobra fletes en la factura se coloca el porcentaje sobre la compra'",
    "valor_flete"                          => "INT(6) UNSIGNED NULL DEFAULT '0' COMMENT 'Si el proveedor cobra fletes en la factura se coloca un valor fijo'",
    "porcentaje_seguro"                    => "DECIMAL(5,2) UNSIGNED NULL DEFAULT '0' COMMENT 'Si el proveedor cobra seguro en la factura se coloca el porcentaje sobre la compra'",
    "valor_seguro"                         => "INT(6) UNSIGNED NULL DEFAULT '0' COMMENT 'Si el proveedor cobra seguro en la factura se coloca un valor fijo'",
    "regimen"                              => "ENUM('1','2') DEFAULT '1' COMMENT '1->Regimen comun 2->Regimen simplificado'",
    "retiene_fuente"                       => "ENUM('0','1') DEFAULT '0' COMMENT 'Realiza retencion en la fuente 0->No 1->Si'",
    "autoretenedor"                        => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT 'Autoretenedor 0->No 1->Si'",
    "retiene_iva"                          => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT 'Retiene IVA 0->No 1->Si'",
    "retiene_ica"                          => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT 'Retiene ICA 0->No 1->Si'",
    "gran_contribuyente"                   => "ENUM('0','1') NOT NULL COMMENT 'Empresa esta catalogada como gran contribuyente por la DIAN 0->No 1-Si'",
    "autoretenedor_ica"                    => "ENUM('0','1') NOT NULL COMMENT 'Autoretenedor Ica 0->No 1-Si'",
    // Llave actividad econima principal
    "codigo_iso_principal"                 => "VARCHAR(2) NOT NULL COMMENT 'Llave principal de la tabla paises'",
    "codigo_dane_departamento_principal"   => "VARCHAR(2) NOT NULL COMMENT 'Código DANE'",
    "codigo_dane_municipio_principal"      => "VARCHAR(3) NOT NULL COMMENT 'Código DANE'",
    "codigo_dian_principal"                => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'id de la tabla actividades economicas DIAN'",
    "codigo_actividad_municipio_principal" => "INT(5) UNSIGNED ZEROFILL NULL COMMENT'Codigo de la actividad economica del municipio'",
    // Llave actividad economica secundaria
    "codigo_iso_secundario"                 => "VARCHAR(2) NOT NULL COMMENT 'Llave principal de la tabla paises'",
    "codigo_dane_departamento_secundario"   => "VARCHAR(2) NOT NULL COMMENT 'Código DANE'",
    "codigo_dane_municipio_secundario"      => "VARCHAR(3) NOT NULL COMMENT 'Código DANE'",
    "codigo_dian_secundario"                => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'id de la tabla actividades economicas DIAN'",
    "codigo_actividad_municipio_secundario" => "INT(5) UNSIGNED ZEROFILL NULL COMMENT'Codigo de la actividad economica del municipio'",
    /******/
    "forma_iva"                            => "ENUM('1','2','3','4') NOT NULL DEFAULT '1' COMMENT 'Forma en que se le paga el IVA: 1->Total 2->Distribuido 3->Primera cuota 4->Separado'",
    "forma_liquidacion_descuento_en_linea" => "ENUM('1','2') NOT NULL DEFAULT '1' COMMENT '1-> liquida el valor neto desde el valor unitario 2-> liquida el valor neto con el valor total'",
    "forma_liquidacion_descuento_global"   => "ENUM('1','2','3') NOT NULL DEFAULT '1' COMMENT '1-> liquida el valor neto desde el valor unitario 2-> liquida el valor neto con el valor total 3-> realiza el calculo al final de la factura'",
    "forma_liquidacion_tasa_credito"       => "ENUM('1','2') NOT NULL DEFAULT '1' COMMENT '1-> liquida el valor despues descuentos en linea 2-> liquida el valor despues descuentos globales'"
);

$tablas["cuentas_bancarias_proveedores"] = array(
    "documento_identidad_proveedor" => "VARCHAR(12) NOT NULL COMMENT 'Codigo interno de proveedor al que pertenece la cuenta'",
    "codigo_banco"                  => "SMALLINT(2) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo interno del banco donde se encuentra la cuenta'",
    "cuenta"                        => "VARCHAR(50) NOT NULL COMMENT 'Numero de la cuenta bancaria'",
    "tipo_cuenta"                   => "ENUM('1','2') NOT NULL COMMENT 'Tipo de cuenta: 1->Cuenta de ahorro, 2->Cuenta corriente'"
);

// Definición de llaves primarias
$llavesPrimarias["proveedores"]                   = "documento_identidad";
$llavesPrimarias["cuentas_bancarias_proveedores"] = "documento_identidad_proveedor,codigo_banco,cuenta,tipo_cuenta";

// Definición de llaves foráneas
$llavesForaneas["proveedores"] = array(
    array(
        // Nombre de la llave
        "proveedor_tercero",
        // Nombre del campo clave de la tabla local
        "documento_identidad",
        // Nombre de la tabla relacionada
        "terceros",
        // Nombre del campo clave en la tabla relacionada
        "documento_identidad"
    ),
    array(
        // Nombre de la llave
        "proveedor_servicios",
        // Nombre del campo clave de la tabla local
        "codigo_servicio",
        // Nombre de la tabla relacionada
        "servicios",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave
        "proveedor_actividad_principal",
        // Nombre del campo clave de la tabla local
        "codigo_iso_principal,codigo_dane_departamento_principal,codigo_dane_municipio_principal,codigo_dian_principal,codigo_actividad_municipio_principal",
        // Nombre de la tabla relacionada
        "actividades_economicas",
        // Nombre del campo clave en la tabla relacionada
        "codigo_iso,codigo_dane_departamento,codigo_dane_municipio,codigo_dian,codigo_actividad_municipio"
    ),
    array(
        // Nombre de la llave
        "proveedor_actividad_secundaria",
        // Nombre del campo clave de la tabla local
        "codigo_iso_secundario,codigo_dane_departamento_secundario,codigo_dane_municipio_secundario,codigo_dian_secundario,codigo_actividad_municipio_secundario",
        // Nombre de la tabla relacionada
        "actividades_economicas",
        // Nombre del campo clave en la tabla relacionada
        "codigo_iso,codigo_dane_departamento,codigo_dane_municipio,codigo_dian,codigo_actividad_municipio"
    ),
    array(
        // Nombre de la llave
        "proveedor_forma_pago_contado",
        // Nombre del campo clave de la tabla local
        "codigo_plazo_pago_contado",
        // Nombre de la tabla relacionada
        "plazos_pago_proveedores",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave
        "proveedor_forma_pago_credito",
        // Nombre del campo clave de la tabla local
        "codigo_plazo_pago_credito",
        // Nombre de la tabla relacionada
        "plazos_pago_proveedores",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    )
);

$llavesForaneas["cuentas_bancarias_proveedores"]   = array(
    array(
        // nombre de la llave
        "proveedor_cuenta",
        // Nombre del campo en la tabla actual
        "documento_identidad_proveedor",
        // Nombre de la tabla relacionada
        "proveedores",
        // nombre del campo en la tabla relacionada
        "documento_identidad"
    ),
    array(
        // Nombre de la llave
        "banco_cuenta",
        // Nombre del campo de la tabla local
        "codigo_banco",
        // Nombre de la tabla relacionada
        "bancos",
        // Nombre del campo la tabla relacionada
        "codigo"
    )
);

// Inserción de datos iniciales
$registros["componentes"] = array(
    array(
        "id"        	  => "GESTPROV",
        "padre"     	  => "SUBMDCPV",
        "id_modulo" 	  => "PROVEEDORES",
        "orden"     	  => "10",
        "carpeta"   	  => "proveedores",
        "archivo"   	  => "menu",
        "requiere_item"   => "1",
        "tabla_principal" => "proveedores",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "ADICPROV",
        "padre"     	  => "GESTPROV",
        "id_modulo" 	  => "PROVEEDORES",
        "visible"   	  => "0",
        "orden"     	  => "10",
        "carpeta"   	  => "proveedores",
        "archivo"   	  => "adicionar",
        "requiere_item"   => "0",
        "tabla_principal" => "proveedores",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "CONSPROV",
        "padre"     	  => "GESTPROV",
        "id_modulo" 	  => "PROVEEDORES",
        "visible"   	  => "0",
        "orden"     	  => "20",
        "carpeta"   	  => "proveedores",
        "archivo"   	  => "consultar",
        "requiere_item"   => "1",
        "tabla_principal" => "proveedores",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "MODIPROV",
        "padre"        	  => "GESTPROV",
        "id_modulo" 	  => "PROVEEDORES",
        "visible"   	  => "0",
        "orden"     	  => "30",
        "carpeta"   	  => "proveedores",
        "archivo"   	  => "modificar",
        "requiere_item"   => "1",
        "tabla_principal" => "proveedores",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "ELIMPROV",
        "padre"     	  => "GESTPROV",
        "id_modulo" 	  => "PROVEEDORES",
        "visible"   	  => "0",
        "orden"     	  => "40",
        "carpeta"   	  => "proveedores",
        "archivo"   	  => "eliminar",
        "requiere_item"   => "1",
        "tabla_principal" => "proveedores",
        "tipo_enlace"     => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_proveedores AS
        SELECT 	job_proveedores.documento_identidad AS id,
                job_terceros.documento_identidad AS DOCUMENTO_PROVEEDOR,
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
                ) AS PROVEEDOR,
                CONCAT('<a href=\"mailto:',job_terceros.correo,'\">',job_terceros.correo ,'</a>') AS CORREO
        
        FROM 	job_proveedores,
                job_terceros
        
        WHERE  	job_proveedores.documento_identidad = job_terceros.documento_identidad;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_proveedores AS 
        SELECT	job_proveedores.documento_identidad AS id,
                job_terceros.documento_identidad AS documento_identidad,
                job_terceros.primer_nombre AS primer_nombre,
                job_terceros.segundo_nombre AS segundo_nombre,
                job_terceros.primer_apellido AS primer_apellido,
                job_terceros.segundo_apellido AS segundo_apellido,
                job_terceros.razon_social AS razon_social,
                job_terceros.nombre_comercial AS nombre_comercial,
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
                ) AS nombre_completo

        FROM 	job_proveedores,
                job_terceros
        
        WHERE 	job_proveedores.documento_identidad = job_terceros.documento_identidad;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_proveedores AS
        SELECT 	job_proveedores.documento_identidad AS id,
                CONCAT(
                    job_terceros.documento_identidad, '-',
                    IF(
                        job_terceros.primer_nombre is not null,
                        CONCAT(
                            CONCAT(job_terceros.primer_nombre,' '),
                            IF(job_terceros.segundo_nombre is not null,CONCAT(job_terceros.segundo_nombre,' '),''),
                            IF(job_terceros.primer_apellido is not null,CONCAT(job_terceros.primer_apellido,' '),''),
                            IF(job_terceros.segundo_apellido is not null,CONCAT(job_terceros.segundo_apellido,' '),'')
                        ),
                        job_terceros.razon_social
                    ),
                    '|',
                    job_proveedores.documento_identidad
                ) AS nombre,
                job_proveedores.publicidad AS publicidad
        
        FROM 	job_terceros,
                job_proveedores
        
        WHERE 	job_terceros.documento_identidad = job_proveedores.documento_identidad
                ORDER BY job_terceros.primer_nombre ASC;"
    )
);
/***
    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_proveedores AS
    SELECT 	job_proveedores.documento_identidad AS id,
			job_terceros.documento_identidad AS DOCUMENTO_PROVEEDOR,
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
			) AS PROVEEDOR,
			CONCAT('<a href="mailto:',job_terceros.correo,'">',job_terceros.correo ,'</a>') AS CORREO
    
    FROM 	job_proveedores,
			job_terceros
    
    WHERE  	job_proveedores.documento_identidad = job_terceros.documento_identidad;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_proveedores AS 
    SELECT	job_proveedores.documento_identidad AS id,
			job_terceros.documento_identidad AS documento_identidad,
			job_terceros.primer_nombre AS primer_nombre,
			job_terceros.segundo_nombre AS segundo_nombre,
			job_terceros.primer_apellido AS primer_apellido,
			job_terceros.segundo_apellido AS segundo_apellido,
			job_terceros.razon_social AS razon_social,
			job_terceros.nombre_comercial AS nombre_comercial,
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
			) AS nombre_completo

    FROM 	job_proveedores,
			job_terceros
    
    WHERE 	job_proveedores.documento_identidad = job_terceros.documento_identidad;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_proveedores AS
    SELECT 	job_proveedores.documento_identidad AS id,
			CONCAT(
				job_terceros.documento_identidad, '-',
				IF(
					job_terceros.primer_nombre is not null,
					CONCAT(
						CONCAT(job_terceros.primer_nombre,' '),
						IF(job_terceros.segundo_nombre is not null,CONCAT(job_terceros.segundo_nombre,' '),''),
						IF(job_terceros.primer_apellido is not null,CONCAT(job_terceros.primer_apellido,' '),''),
						IF(job_terceros.segundo_apellido is not null,CONCAT(job_terceros.segundo_apellido,' '),'')
					),
					job_terceros.razon_social
				),
				'|',
				job_proveedores.documento_identidad
			) AS nombre,
			job_proveedores.publicidad AS publicidad
    
    FROM 	job_terceros,
			job_proveedores
    
    WHERE 	job_terceros.documento_identidad = job_proveedores.documento_identidad
			ORDER BY job_terceros.primer_nombre ASC;
***/
?>

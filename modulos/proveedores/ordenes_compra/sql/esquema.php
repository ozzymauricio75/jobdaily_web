<?php
/**
*
* Copyright (C) 2020 Jobdaily
* Raul Mauricio Oidor Lozano <ozzymauricio75@gmail.com>
*
* Este archivo es parte de:
* Jobdaily :: Software empresarial a la medida
*
* Este programa es software libre: usted puede redistribuirlo y/o
* modificarlo  bajo los terminos de la Licencia Publica General GNU
* publicada por la Fundacion para el Software Libre, ya sea la version 3
* de la Licencia, o (a su eleccion) cualquier version posterior.
*
* Este programa se distribuye con la esperanza de que sea util, pero
* SIN GARANTIA ALGUNA; ni siquiera la garantia implicita MERCANTIL o
* de APTITUD PARA UN PROPOSITO DETERMINADO. Consulte los detalles de
* la Licencia Publica General GNU para obtener una informacion mas
* detallada.
*
* Deberia haber recibido una copia de la Licencia Publica General GNU
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
**/
//$borrarSiempre["ordenes_compra"] = false;
$borrarSiempre = false;
//  Definicion de tablas
$tablas["ordenes_compra"] = array(
    "codigo"                           => "INT(9) UNSIGNED ZEROFILL AUTO_INCREMENT NOT NULL COMMENT 'Llave primaria'",
    "codigo_sucursal"                  => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id de la tabla de sucursales'",
    "codigo_tipo_documento"            => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id de la tabla tipo de documento'",
    "fecha_documento"                  => "DATE NOT NULL COMMENT 'Fecha de generacion del documento'",
    "prefijo_codigo_proyecto"          => "INT(9) UNSIGNED ZEROFILL NOT NULL COMMENT 'Numero prefijo de la orden, que es el codigo del proyecto'",
    "numero_consecutivo"               => "INT(9) UNSIGNED ZEROFILL NOT NULL COMMENT 'Numero consecutivo'",
    "documento_identidad_proveedor"    => "VARCHAR(12) NOT NULL COMMENT 'Llave principal de la tabla de terceros'",
    "codigo_comprador"                 => "INT(9) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id de la tabla compradores'",
    "cantidad_registros"               => "INT(9) UNSIGNED ZEROFILL NOT NULL COMMENT 'Cantidad de Items para la orden de compra'",
    "cantidad_cumplidos"               => "INT(9) UNSIGNED ZEROFILL NOT NULL COMMENT 'Cantidad de Items cumplidos en la orden de compra'",
    "estado"                           => "ENUM('0','1','2','3') NOT NULL DEFAULT '1' COMMENT '0->Grabada total 1->Entrega parcial 2->Anulada 3->Cumplida'",
    "codigo_usuario_orden_compra"      => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id del usuario que genero la orden de compra'",
    "codigo_usuario_anula"             => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id del usuario que anula el registro'",
    "estado_aprobada"                  => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT '0->No ha sido aprobada 1->Ya fue aprobada'",
    "codigo_usuario_aprueba"           => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id del usuario que aprueba la orden de compra'",
    "codigo_moneda"                    => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id de la tabla monedas'",
    "descuento_global1"                => "DECIMAL(7,4) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Descuento global 1'",
    "descuento_global2"                => "DECIMAL(7,4) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Descuento global 2'",
    "descuento_global3"                => "DECIMAL(7,4) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Descuento global 3'",
    "descuento_financiero_fijo"        => "DECIMAL(7,4) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Descuento financiero fijo por pago'",
    "descuento_financiero_pronto_pago" => "DECIMAL(7,4) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Descuento financiero por pronto pago'",
    "numero_dias_pronto_pago"          => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL DEFAULT '0' COMMENT 'Numero de dias para tomar el descuento financiero pronto pago del proveedor'",
    "iva_incluido"                     => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT 'El articulo lleva iva incluido 0->No 1->Si'",
    "codigo_numero_dias_pago"          => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL DEFAULT '0' COMMENT ' Numero de dias para pago al proveedor'",
    "observaciones"                    => "VARCHAR(234) COMMENT 'Observacion general para la orden de compra'",
    "imprimio"                         => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT 'La orden de compra ya se impirmio 0->No 1->Si'",
    "fecha_registra"                   => "DATETIME NOT NULL COMMENT 'Fecha ingreso al sistema'",
    "fecha_modificacion"               => "TIMESTAMP NOT NULL COMMENT 'Fecha ultima modificación'",
    "solicitante"                      => "VARCHAR(120) NOT NULL  COMMENT 'Nombre del solicitante de la orden de compra'"
);

$borrarSiempre["movimiento_ordenes_compra"] = false;
$tablas["movimiento_ordenes_compra"] = array(
    "codigo"                  => "INT(9) UNSIGNED ZEROFILL AUTO_INCREMENT NOT NULL COMMENT 'Llave primaria'",
    "codigo_orden_compra"     => "INT(9) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id de la tabla ordenes_compra'",
    "consecutivo"             => "INT(9) UNSIGNED ZEROFILL NOT NULL COMMENT 'Numero consecutivo del movimiento'",
    "codigo_articulo"         => "INT(9) UNSIGNED ZEROFILL COMMENT 'Codigo del articulo asignado por la empresa'",
    "referencia_articulo"     => "VARCHAR(30) NOT NULL COMMENT 'Referencia del producto a realizar orden de compra'",
    "codigo_sucursal_destino" => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código interno de la sucursal'",
    "estado"                  => "ENUM('0','1','2','3') NOT NULL DEFAULT '1' COMMENT '0->Grabada total 1->Entrega parcial 2->Anulada 3->Cumplida'",
    "observaciones"           => "VARCHAR(78) COMMENT 'Observacion para el articulo en el pedido'",
    "codigo_unidad_medida"    => "INT(6) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id de la tabla unidades'",
    "cantidad_total"          => "DECIMAL(15,4) UNSIGNED  NOT NULL COMMENT 'Cantidad total solicitada en unidades del articulo'",
    "valor_total"             => "DECIMAL(15,4) UNSIGNED  NOT NULL COMMENT 'Valor total de la orden de compra del articulo'",
    "descuento_global1"       => "DECIMAL(7,4)  UNSIGNED  NOT NULL DEFAULT '0' COMMENT 'Porcentaje descuento global1 del articulo'",
    "valor_descuento_global1" => "DECIMAL(15,4) UNSIGNED  NOT NULL DEFAULT '0' COMMENT 'Valor descuento global1 del articulo'",
    "descuento_global2"       => "DECIMAL(7,4)  UNSIGNED  NOT NULL DEFAULT '0' COMMENT 'Porcentaje descuento global2 del articulo'",
    "valor_descuento_global2" => "DECIMAL(15,4) UNSIGNED  NOT NULL DEFAULT '0' COMMENT 'Valor descuento global2 del articulo'",
    "descuento_global3"       => "DECIMAL(7,4)  UNSIGNED  NOT NULL DEFAULT '0' COMMENT 'Porcentaje descuento global3 del articulo'",
    "valor_descuento_global3" => "DECIMAL(15,4) UNSIGNED  NOT NULL DEFAULT '0' COMMENT 'Valor descuento global3 del articulo'",
    "valor_unitario"          => "DECIMAL(15,4) UNSIGNED  NOT NULL COMMENT 'Valor unitario del articulo'",
    "neto_pagar"              => "DECIMAL(15,4) UNSIGNED  NOT NULL COMMENT 'Neto a pagar del pedido del articulo'",
    "valor_iva"               => "DECIMAL(15,4) UNSIGNED  NOT NULL COMMENT 'Valor del iva del pedido del articulo'",
    "codigo_tasa_impuesto"    => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id de la tabla tasas'",
    "porcentaje_impuesto"     => "DECIMAL(7,4) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Porcentaje de la tasa de impuesto del articulo'",
    "iva_incluido"            => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT 'El articulo llave iva incluido 0->No 1->Si'",
    "fecha_entrega"           => "DATE COMMENT 'Fecha de despacho para el articulo'",
    "fecha_registra"          => "DATETIME NOT NULL COMMENT 'Fecha ingreso al sistema'",
    "fecha_modificacion"      => "TIMESTAMP NOT NULL COMMENT 'Fecha ultima modificación'",
    "codigo_usuario_registra" => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id del usuario que genera el registro'",
    "codigo_vendedor"         => "INT(6) UNSIGNED NOT NULL COMMENT 'Codigo interno del vendedor tabla vendedores'"
);

$borrarSiempre["cruce_orden_compra"] = false;
//Definicion de tablas
$tablas["cruce_orden_compra"] = array(
    //////// LLAVE CONSECUTIVO ////////
    "codigo"                         => "INT(9) UNSIGNED ZEROFILL AUTO_INCREMENT NOT NULL COMMENT 'Numero consecutivo del registro'",
    "codigo_prefijo_proyecto"        => "INT(9) UNSIGNED ZEROFILL NOT NULL COMMENT 'Numero prefijo de la orden, que es el codigo del proyecto'",
    "codigo_orden_compra"            => "INT(9) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id de la tabla ordenes_compra'",
    "codigo_sucursal"                => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código de la sucursal a la cual pertenece'",
    "fecha_registro"                 => "DATETIME NOT NULL COMMENT 'Fecha ingreso al sistema'",
    "codigo_usuario_registra"        => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id del Usuario que ingresa la mercancia'",
    "documento_identidad_proveedor"  => "VARCHAR(12) NOT NULL COMMENT 'Llave principal de la tabla de proveedores'"
); 

$borrarSiempre["movimiento_cruce_orden_compra"] = false;
//Definicion de tablas
$tablas["movimiento_cruce_orden_compra"] = array(
    ///////////////////////////////////
    "codigo"                         => "INT(9) UNSIGNED ZEROFILL AUTO_INCREMENT NOT NULL COMMENT 'Numero consecutivo del registro'",
    "codigo_cruce_orden_compra"      => "INT(9) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id de la tabla cruce ordenes_compra'",
    "codigo_articulo"                => "INT(9) UNSIGNED ZEROFILL COMMENT 'Codigo del articulo asignado por la empresa'",
    "cantidad_total"                 => "DECIMAL(15,4) UNSIGNED  NOT NULL DEFAULT '0' COMMENT 'Cantidad total solicitada en unidades del articulo'",
    "valor_total"                    => "DECIMAL(15,4) UNSIGNED  NOT NULL DEFAULT '0' COMMENT 'Valor total de la orden de compra del articulo'",
    "valor_descuento_global1"        => "DECIMAL(15,4) UNSIGNED  NOT NULL DEFAULT '0' COMMENT 'Valor descuento global1 del articulo'",
    "neto_pagar"                     => "DECIMAL(15,4) UNSIGNED  NOT NULL COMMENT 'Neto a pagar del pedido del articulo'",
    "valor_iva"                      => "DECIMAL(15,4) UNSIGNED  NOT NULL COMMENT 'Valor del iva del pedido del articulo'",
    "codigo_tipo_documento"          => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo del tipo de documento'",
    "numero_factura_proveedor"       => "VARCHAR(15) NOT NULL COMMENT 'Número de la factura enviada por el proveedor'",
    "numero_remision_proveedor"      => "VARCHAR(15) NOT NULL COMMENT 'Número de la remisión con la cual el proveedor envió la mercancía '",
    "observaciones"                  => "VARCHAR(255) NULL COMMENT 'Información suministrada por el usuario sobre el documento a cruzar'",
    "fecha_registro"                 => "DATETIME NOT NULL COMMENT 'Fecha ingreso al sistema'",
    "codigo_usuario_registra"        => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id del Usuario que ingresa la mercancia'"
);

// Definicion de llaves primarias
$llavesPrimarias["ordenes_compra"]                = "codigo";
$llavesPrimarias["movimiento_ordenes_compra"]     = "codigo";
$llavesPrimarias["cruce_orden_compra"]            = "codigo";
$llavesPrimarias["movimiento_cruce_orden_compra"] = "codigo";

//Definición llaves unicas
$llavesUnicas["ordenes_compra"] = array(
    "codigo_sucursal,codigo_tipo_documento,fecha_documento,numero_consecutivo",
);
$llavesUnicas["movimiento_ordenes_compra"] = array(
    "codigo_orden_compra,consecutivo"
);
$llavesUnicas["cruce_orden_compra"] = array(
    "codigo_prefijo_proyecto,codigo_orden_compra,codigo_sucursal,
                fecha_registro,documento_identidad_proveedor"
);

//  Definicion de llaves Foraneas
$llavesForaneas["ordenes_compra"] = array(
    array(
        // Nombre de la llave
        "ordenes_compra_sucursal",
        // Nombre del campo clave de la tabla local
        "codigo_sucursal",
        // Nombre de la tabla relacionada
        "sucursales",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave
        "ordenes_compra_tipo_documento",
        // Nombre del campo clave de la tabla local
        "codigo_tipo_documento",
        // Nombre de la tabla relacionada
        "tipos_documentos",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave
        "ordenes_compra_moneda",
        // Nombre del campo clave de la tabla local
        "codigo_moneda",
        // Nombre de la tabla relacionada
        "tipos_moneda",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave
        "ordenes_compra_compradores",
        // Nombre del campo clave de la tabla local
        "codigo_comprador",
        // Nombre de la tabla relacionada
        "compradores",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave
        "ordenes_compra_usuario_registra",
        // Nombre del campo clave de la tabla local
        "codigo_usuario_orden_compra",
        // Nombre de la tabla relacionada
        "usuarios",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave
        "ordenes_compra_usuario_anula",
        // Nombre del campo clave de la tabla local
        "codigo_usuario_anula",
        // Nombre de la tabla relacionada
        "usuarios",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave
        "ordenes_compra_usuario_aprueba",
        // Nombre del campo clave de la tabla local
        "codigo_usuario_aprueba",
        // Nombre de la tabla relacionada
        "usuarios",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave
        "ordenes_compra_dias_pago",
        // Nombre del campo clave de la tabla local
        "codigo_numero_dias_pago",
        // Nombre de la tabla relacionada
        "plazos_pago_proveedores",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    )
);
$llavesForaneas["movimiento_ordenes_compra"] = array(
    array(
        // Nombre de la llave
        "movimientos_ordenes_compra_encabezado",
        // Nombre del campo clave de la tabla local
        "codigo_orden_compra",
        // Nombre de la tabla relacionada
        "ordenes_compra",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave
        "movimientos_ordenes_compra_codigo_articulo",
        // Nombre del campo clave de la tabla local
        "codigo_articulo",
        // Nombre de la tabla relacionada
        "articulos",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave
        "movimientos_ordenes_compra_tasas",
        // Nombre del campo clave de la tabla local
        "codigo_tasa_impuesto",
        // Nombre de la tabla relacionada
        "tasas",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave
        "movimientos_ordenes_compra_sucursales",
        // Nombre del campo clave de la tabla local
        "codigo_sucursal_destino",
        // Nombre de la tabla relacionada
        "sucursales",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave
        "movimientos_ordenes_compra_unidades",
        // Nombre del campo clave de la tabla local
        "codigo_unidad_medida",
        // Nombre de la tabla relacionada
        "unidades",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave
        "movimientos_ordenes_compra_usuario_registra",
        // Nombre del campo clave de la tabla local
        "codigo_usuario_registra",
        // Nombre de la tabla relacionada
        "usuarios",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave
        "movimientos_ordenes_compra_vendedor",
        // Nombre del campo clave de la tabla local
        "codigo_vendedor",
        // Nombre de la tabla relacionada
        "vendedores_proveedor",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    )
);
//Definicion de llaves foraneas
$llavesForaneas["cruce_orden_compra"] = array(
    array(
        // Nombre de la llave
        "cruce_orden_compra_prefijo_codigo_proyecto",
        // Nombre del campo clave de la tabla local
        "codigo_prefijo_proyecto",
        // Nombre de la tabla relacionada
        "proyectos",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    ),
   array(
        // Nombre de la llave
        "cruce_orden_compra_codigo_sucursal",
        // Nombre del campo clave de la tabla local
        "codigo_sucursal",
        // Nombre de la tabla relacionada
        "sucursales",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave
        "cruce_orden_compra_proveedor",
        // Nombre del campo clave de la tabla local
        "documento_identidad_proveedor",
        // Nombre de la tabla relacionada
        "proveedores",
        // Nombre del campo clave en la tabla relacionada
        "documento_identidad"
    ),
    array(
        // Nombre de la llave
        "cruce_orden_compra_usuario_registra",
        // Nombre del campo clave de la tabla local
        "codigo_usuario_registra",
        // Nombre de la tabla relacionada
        "usuarios",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    )
); 
//Definicion de llaves foraneas
$llavesForaneas["movimiento_cruce_orden_compra"] = array(   
    array(
        // Nombre de la llave
        "movimiento_cruce_orden_compra_codigo_orden_compra",
        // Nombre del campo clave de la tabla local
        "codigo_cruce_orden_compra",
        // Nombre de la tabla relacionada
        "cruce_orden_compra",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave
        "movimiento_cruce_orden_compra_codigo_tipo_documento",
        // Nombre del campo clave de la tabla local
        "codigo_tipo_documento",
        // Nombre de la tabla relacionada
        "tipos_documentos",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave
        "movimiento_cruce_orden_compra_codigo_articulo",
        // Nombre del campo clave de la tabla local
        "codigo_articulo",
        // Nombre de la tabla relacionada
        "articulos",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave
        "movimiento_cruce_orden_compra_usuario_registra",
        // Nombre del campo clave de la tabla local
        "codigo_usuario_registra",
        // Nombre de la tabla relacionada
        "usuarios",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    )
);
//  Insercion de datos iniciales
$registros["componentes"] = array(
    array(
        "id"              => "GESTOCPR",
        "padre"           => "SUBMCOMP",
        "id_modulo"       => "PROVEEDORES",
        "visible"         => "1",
        "orden"           => "800",
        "carpeta"         => "ordenes_compra",
        "archivo"         => "menu",
        "requiere_item"   => "0",
        "tabla_principal" => "ordenes_compra",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "ADICOCPR",
        "padre"           => "GESTOCPR",
        "id_modulo"       => "PROVEEDORES",
        "visible"         => "0",
        "orden"           => "008",
        "carpeta"         => "ordenes_compra",
        "archivo"         => "adicionar",
        "requiere_item"   => "1",
        "tabla_principal" => "ordenes_compra",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "CONSOCPR",
        "padre"           => "GESTOCPR",
        "id_modulo"       => "PROVEEDORES",
        "visible"         => "0",
        "orden"           => "0010",
        "carpeta"         => "ordenes_compra",
        "archivo"         => "consultar",
        "requiere_item"   => "1",
        "tabla_principal" => "ordenes_compra",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "ANULORCO",
        "padre"           => "GESTOCPR",
        "id_modulo"       => "PROVEEDORES",
        "visible"         => "0",
        "orden"           => "0011",
        "carpeta"         => "ordenes_compra",
        "archivo"         => "anular",
        "requiere_item"   => "1",
        "tabla_principal" => "ordenes_compra",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "CRUCORCO",
        "padre"           => "GESTOCPR",
        "id_modulo"       => "PROVEEDORES",
        "visible"         => "0",
        "orden"           => "0012",
        "carpeta"         => "ordenes_compra",
        "archivo"         => "cruzar",
        "requiere_item"   => "1",
        "tabla_principal" => "ordenes_compra",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "REPOORCO",
        "padre"           => "GESTOCPR",
        "id_modulo"       => "PROVEEDORES",
        "visible"         => "0",
        "orden"           => "0014",
        "carpeta"         => "ordenes_compra",
        "archivo"         => "reporte"
    )
);
$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_ordenes_compra AS
            SELECT
                job_ordenes_compra.codigo AS id,
                job_ordenes_compra.fecha_documento AS FECHA_DOCUMENTO,
                job_ordenes_compra.numero_consecutivo AS DOCUMENTO,
                job_sucursales.nombre AS SUCURSAL,
                job_proyectos.nombre AS PROYECTO,
                FORMAT(SUM(job_movimiento_ordenes_compra.cantidad_total),0) AS TOTAL_UNIDADES,
                FORMAT(SUM(job_movimiento_ordenes_compra.valor_total),0) AS SUBTOTAL,
                FORMAT(SUM(job_movimiento_ordenes_compra.valor_iva),0) AS VALOR_IVA,
                FORMAT(SUM(job_movimiento_ordenes_compra.neto_pagar),0) AS NETO_PAGAR,
                CONCAT('ESTADO_',job_ordenes_compra.estado) AS ESTADO,
                
                CONCAT(
                    job_terceros.documento_identidad,
                    ' ',
                    IF(job_tipos_documento_identidad.tipo_persona = '1' OR job_tipos_documento_identidad.tipo_persona = '4',
                        CONCAT(
                            IF (job_terceros.primer_nombre IS NOT NULL AND job_terceros.primer_nombre != '',CONCAT(job_terceros.primer_nombre),''),
                            IF (job_terceros.segundo_nombre IS NOT NULL AND job_terceros.segundo_nombre != '',CONCAT(' ',job_terceros.segundo_nombre),''),
                            IF (job_terceros.primer_apellido IS NOT NULL AND job_terceros.primer_apellido != '',CONCAT(' ',job_terceros.primer_apellido),''),
                            IF (job_terceros.segundo_apellido IS NOT NULL AND job_terceros.segundo_apellido != '',CONCAT(' ',job_terceros.segundo_apellido),'')
                        ),
                        job_terceros.razon_social
                    )
                ) AS PROVEEDOR
                
            FROM
                job_ordenes_compra,
                job_sucursales,
                job_tipos_documentos,
                job_tipos_documento_identidad,
                job_terceros,
                job_proyectos,
                job_compradores,
                job_movimiento_ordenes_compra
            WHERE
                job_ordenes_compra.codigo_sucursal = job_sucursales.codigo AND
                job_ordenes_compra.documento_identidad_proveedor = job_terceros.documento_identidad AND
                job_ordenes_compra.codigo_tipo_documento = job_tipos_documentos.codigo AND
                job_tipos_documento_identidad.codigo = job_terceros.codigo_tipo_documento AND
                job_proyectos.codigo = job_ordenes_compra.prefijo_codigo_proyecto AND
                job_compradores.codigo = job_ordenes_compra.codigo_comprador AND
                job_ordenes_compra.codigo = job_movimiento_ordenes_compra.codigo_orden_compra AND
                job_ordenes_compra.codigo > 0
            
            GROUP BY 
                job_movimiento_ordenes_compra.codigo_orden_compra
            ORDER BY
                job_ordenes_compra.numero_consecutivo, job_ordenes_compra.estado DESC;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_ordenes_compra AS
            SELECT
                job_ordenes_compra.codigo AS id,
                job_ordenes_compra.fecha_documento AS FECHA_DOCUMENTO,
                job_ordenes_compra.numero_consecutivo AS DOCUMENTO,
                job_sucursales.nombre AS SUCURSAL,
                job_proyectos.nombre AS PROYECTO,
                FORMAT(SUM(job_movimiento_ordenes_compra.cantidad_total),0) AS TOTAL_UNIDADES,
                FORMAT(SUM(job_movimiento_ordenes_compra.valor_total),0) AS SUBTOTAL,
                FORMAT(SUM(job_movimiento_ordenes_compra.valor_iva),0) AS VALOR_IVA,
                FORMAT(SUM(job_movimiento_ordenes_compra.neto_pagar),0) AS NETO_PAGAR,
                CONCAT('ESTADO_',job_ordenes_compra.estado) AS ESTADO,
                
                CONCAT(
                    job_terceros.documento_identidad,
                    ' ',
                    IF(job_tipos_documento_identidad.tipo_persona = '1' OR job_tipos_documento_identidad.tipo_persona = '4',
                        CONCAT(
                            IF (job_terceros.primer_nombre IS NOT NULL AND job_terceros.primer_nombre != '',CONCAT(job_terceros.primer_nombre),''),
                            IF (job_terceros.segundo_nombre IS NOT NULL AND job_terceros.segundo_nombre != '',CONCAT(' ',job_terceros.segundo_nombre),''),
                            IF (job_terceros.primer_apellido IS NOT NULL AND job_terceros.primer_apellido != '',CONCAT(' ',job_terceros.primer_apellido),''),
                            IF (job_terceros.segundo_apellido IS NOT NULL AND job_terceros.segundo_apellido != '',CONCAT(' ',job_terceros.segundo_apellido),'')
                        ),
                        job_terceros.razon_social
                    )
                ) AS PROVEEDOR
                
            FROM
                job_ordenes_compra,
                job_sucursales,
                job_tipos_documentos,
                job_tipos_documento_identidad,
                job_terceros,
                job_proyectos,
                job_compradores,
                job_movimiento_ordenes_compra
            WHERE
                job_ordenes_compra.codigo_sucursal = job_sucursales.codigo AND
                job_ordenes_compra.documento_identidad_proveedor = job_terceros.documento_identidad AND
                job_ordenes_compra.codigo_tipo_documento = job_tipos_documentos.codigo AND
                job_tipos_documento_identidad.codigo = job_terceros.codigo_tipo_documento AND
                job_proyectos.codigo = job_ordenes_compra.prefijo_codigo_proyecto AND
                job_compradores.codigo = job_ordenes_compra.codigo_comprador AND
                job_ordenes_compra.codigo = job_movimiento_ordenes_compra.codigo_orden_compra AND
                job_ordenes_compra.codigo > 0
            
            GROUP BY 
                job_movimiento_ordenes_compra.codigo_orden_compra;"
    )
);
?>

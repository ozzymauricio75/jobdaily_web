-- Vista general de movimientos contables de proveedores
-- Adicione la consulta que provee los movimientos de su modulo con la sentencia 'UNION ALL'
-- Campos requeridos:
--      - id_plan_contable
--      - codigo_contable
--      - descripcion
--      - sentido_cuenta
--      - clase_cuenta
--      - tipo_cuenta
--      - id_auxiliar_contable
--      - sentido
--      - id_tercero
--      - estado
--      - fecha_contabilizacion
--      - id_tipo_documento
--      - numero_consecutivo
--      - id_tipo_comprobante
--      - numero_comprobante
--      - SucursalGenera
--      - valor
--      - valor_base1
--      - valor_base2
--      - id_tabla
--      - id_registro

CREATE OR REPLACE ALGORITHM = MERGE VIEW job_proveedores_movimientos_consolidados AS
    (SELECT job_movimiento_notas_varias_contables.id_plan_contable AS id_plan_contable,
            pp.codigo_contable AS codigo_contable,
            pp.descripcion AS descripcion,
            pp.naturaleza_cuenta AS sentido_cuenta,
            pp.clase_cuenta AS clase_cuenta,
            pp.tipo_cuenta AS tipo_cuenta,
            job_movimiento_notas_varias_contables.id_auxiliar AS id_auxiliar_contable,
            job_movimiento_notas_varias_contables.sentido AS sentido,
            job_terceros.id AS id_tercero,
            job_movimiento_notas_varias_contables.estado_movimiento AS estado,
            job_notas_varias_contables.fecha_contabiliza AS fecha_contabilizacion,
            job_consecutivo_documentos.id_tipo_documento AS id_tipo_documento,
            job_consecutivo_documentos.numero_consecutivo AS numero_consecutivo,
            job_tipos_documentos.id_comprobante AS id_tipo_comprobante,
            '1' AS numero_comprobante,
            job_notas_varias_contables.id_sucursal AS sucursalGenera,
            job_movimiento_notas_varias_contables.valor_contable AS valor,
            job_movimiento_notas_varias_contables.valor_base1 AS valor_base1,
            job_movimiento_notas_varias_contables.valor_base2 AS valor_base2,
            job_consecutivo_documentos.id_tabla AS id_tabla,
            job_consecutivo_documentos.id_registro_tabla AS id_registro

    FROM    job_notas_varias_contables,
            job_movimiento_notas_varias_contables,
            job_proveedores,
            job_terceros,
            job_sucursales,
            job_plan_contable pp,
            job_auxiliares_contables,
            job_tipos_documentos,
            job_tipos_comprobantes,
            job_tablas,
            job_consecutivo_documentos

	WHERE   job_movimiento_notas_varias_contables.id_nota = job_notas_varias_contables.id
            AND job_movimiento_notas_varias_contables.id_plan_contable = pp.id
            AND job_movimiento_notas_varias_contables.id_auxiliar = job_auxiliares_contables.id
            AND job_notas_varias_contables.id_proveedor = job_proveedores.id
            AND job_proveedores.id_tercero = job_terceros.id
            AND job_notas_varias_contables.id_sucursal = job_sucursales.id
            AND job_notas_varias_contables.id_tipo_documento = job_consecutivo_documentos.id_tipo_documento
            AND job_consecutivo_documentos.id_tipo_documento = job_tipos_documentos.id
            AND job_notas_varias_contables.id_tipo_documento = job_tipos_documentos.id
            AND job_consecutivo_documentos.id_sucursal = job_sucursales.id
            AND job_consecutivo_documentos.id_tabla = job_tablas.id
            AND job_tablas.nombre_tabla = 'notas_varias_contables'
            AND job_tipos_documentos.id_comprobante = job_tipos_comprobantes.id)
	
    UNION ALL
    (SELECT job_notas_varias_contables.id_plan_contable AS id_plan_contable,
            pp.codigo_contable AS codigo_contable,
            pp.descripcion AS descripcion,
            pp.naturaleza_cuenta AS sentido_cuenta,
            pp.clase_cuenta AS clase_cuenta,
            pp.tipo_cuenta AS tipo_cuenta,
            job_notas_varias_contables.id_auxiliar AS id_auxiliar_contable,
            job_notas_varias_contables.sentido AS sentido,
            job_terceros.id AS id_tercero,
            job_notas_varias_contables.estado AS estado,
            job_notas_varias_contables.fecha_contabiliza AS fecha_contabilizacion,
            job_consecutivo_documentos.id_tipo_documento AS id_tipo_documento,
            job_consecutivo_documentos.numero_consecutivo AS numero_consecutivo,
            job_tipos_documentos.id_comprobante AS id_tipo_comprobante,
            '1' AS numero_comprobante,
            job_notas_varias_contables.id_sucursal AS sucursalGenera,
            job_notas_varias_contables.valor AS valor,
            '0' AS valor_base1,
            '0' AS valor_base2,
            job_consecutivo_documentos.id_tabla AS id_tabla,
            job_consecutivo_documentos.id_registro_tabla AS id_registro

	FROM    job_notas_varias_contables,
            job_proveedores,
            job_terceros,
            job_sucursales,
            job_plan_contable pp,
            job_auxiliares_contables,
            job_tipos_documentos,
            job_tipos_comprobantes,
            job_tablas,
            job_consecutivo_documentos
	
    WHERE   job_notas_varias_contables.id_plan_contable = pp.id
            AND job_notas_varias_contables.id_auxiliar = job_auxiliares_contables.id
            AND job_notas_varias_contables.id_proveedor = job_proveedores.id
            AND job_proveedores.id_tercero = job_terceros.id
            AND job_notas_varias_contables.id_sucursal = job_sucursales.id
            AND job_consecutivo_documentos.id_registro_tabla = job_notas_varias_contables.id
            AND job_notas_varias_contables.id_tipo_documento = job_consecutivo_documentos.id_tipo_documento
            AND job_consecutivo_documentos.id_tipo_documento = job_tipos_documentos.id
            AND job_consecutivo_documentos.id_sucursal = job_sucursales.id
            AND job_consecutivo_documentos.id_tabla = job_tablas.id
            AND job_tablas.nombre_tabla = 'notas_varias_contables'
            AND job_tipos_documentos.id_comprobante = job_tipos_comprobantes.id)
    
    UNION ALL
    (SELECT job_cuentas_cobrar_devoluciones_proveedores.id_plan_contable AS id_plan_contable,
            pp.codigo_contable AS codigo_contable,
            pp.descripcion AS descripcion,
            pp.naturaleza_cuenta AS sentido_cuenta,
            pp.clase_cuenta AS clase_cuenta,
            pp.tipo_cuenta AS tipo_cuenta,
            job_cuentas_cobrar_devoluciones_proveedores.id_auxiliar AS id_auxiliar_contable,
            'C' AS sentido,
            job_terceros.id AS id_tercero,
            '1' AS estado,
            job_cuentas_cobrar_devoluciones_proveedores.fecha_registro AS fecha_contabilizacion,
            job_consecutivo_documentos.id_tipo_documento AS id_tipo_documento,
            job_consecutivo_documentos.numero_consecutivo AS numero_consecutivo,
            job_cuentas_cobrar_devoluciones_proveedores.id_tipo_comprobante AS id_tipo_comprobante,
            '1' AS numero_comprobante,
            job_cuentas_cobrar_devoluciones_proveedores.id_sucursal AS sucursalGenera,
            job_cuentas_cobrar_devoluciones_proveedores.valor AS valor,
            '0' AS valor_base1,
            '0' AS valor_base2,
            job_tablas.id AS id_tabla,
            job_cuentas_cobrar_devoluciones_proveedores.id AS id_registro

    FROM    job_cuentas_cobrar_devoluciones_proveedores,
            job_proveedores,
            job_terceros,
            job_sucursales,
            job_consecutivo_documentos,
            job_tipos_documentos,
            job_tipos_comprobantes,
            job_tablas,
            job_plan_contable pp,
            job_auxiliares_contables

    WHERE   job_cuentas_cobrar_devoluciones_proveedores.id_plan_contable = pp.id
            AND job_cuentas_cobrar_devoluciones_proveedores.id_auxiliar = job_auxiliares_contables.id
            AND job_cuentas_cobrar_devoluciones_proveedores.id_proveedor = job_proveedores.id
            AND job_proveedores.id_tercero = job_terceros.id
            AND job_cuentas_cobrar_devoluciones_proveedores.id_tipo_comprobante = job_tipos_comprobantes.id
            AND job_cuentas_cobrar_devoluciones_proveedores.id_sucursal = job_sucursales.id
            AND job_cuentas_cobrar_devoluciones_proveedores.id = job_consecutivo_documentos.id_registro_tabla
            AND job_sucursales.id = job_consecutivo_documentos.id_sucursal
            AND job_consecutivo_documentos.id_tipo_documento = job_tipos_documentos.id
            AND job_tablas.nombre_tabla = 'cuentas_cobrar_devoluciones_proveedores'
            AND job_tablas.id = job_consecutivo_documentos.id_tabla)
    
    UNION ALL
    (SELECT job_cuentas_por_pagar_proveedores.id_plan_contable AS id_plan_contable,
            pp.codigo_contable AS codigo_contable,
            pp.descripcion AS descripcion,
            pp.naturaleza_cuenta AS sentido_cuenta,
            pp.clase_cuenta AS clase_cuenta,
            pp.tipo_cuenta AS tipo_cuenta,
            job_cuentas_por_pagar_proveedores.id_auxiliar AS id_auxiliar_contable,
            'C' AS sentido,
            job_terceros.id AS id_tercero,
            '1' AS estado,
            job_cuentas_por_pagar_proveedores.fecha_contabiliza AS fecha_contabilizacion,
            job_cuentas_por_pagar_proveedores.id_tipo_documento AS id_tipo_documento,
            job_cuentas_por_pagar_proveedores.numero_factura AS numero_consecutivo,
            '0' AS id_tipo_comprobante,
            '1' AS numero_comprobante,
            job_cuentas_por_pagar_proveedores.id_sucursal AS sucursalGenera,
            job_cuentas_por_pagar_proveedores.valor AS valor,
            '0' AS valor_base1,
            '0' AS valor_base2,
            job_tablas.id AS id_tabla,
            job_cuentas_por_pagar_proveedores.id AS id_registro

    FROM    job_cuentas_por_pagar_proveedores,
            job_proveedores,
            job_terceros,
            job_sucursales,
            job_plan_contable pp,
            job_auxiliares_contables,
            job_tablas,
            job_tipos_documentos

	WHERE   job_cuentas_por_pagar_proveedores.id_plan_contable = pp.id
            AND job_cuentas_por_pagar_proveedores.id_auxiliar = job_auxiliares_contables.id
            AND job_cuentas_por_pagar_proveedores.id_proveedor = job_proveedores.id
            AND job_proveedores.id_tercero = job_terceros.id
            AND job_cuentas_por_pagar_proveedores.id_sucursal = job_sucursales.id
            AND job_cuentas_por_pagar_proveedores.id_tipo_documento = job_tipos_documentos.id
            AND job_tablas.nombre_tabla = 'cuentas_por_pagar_proveedores'
            AND job_cuentas_por_pagar_proveedores.id != 0)

    UNION ALL
    (SELECT job_movimiento_contable_proveedores.id_plan_contable AS id_plan_contable,
            pp.codigo_contable AS codigo_contable,
            pp.descripcion AS descripcion,
            pp.naturaleza_cuenta AS sentido_cuenta,
            pp.clase_cuenta AS clase_cuenta,
            pp.tipo_cuenta AS tipo_cuenta,
            job_movimiento_contable_proveedores.id_auxiliar AS id_auxiliar_contable,
            job_movimiento_contable_proveedores.sentido AS sentido,
            job_terceros.id AS id_tercero,
            '1' AS estado,
            job_cuentas_por_pagar_proveedores.fecha_contabiliza AS fecha_contabilizacion,
             job_cuentas_por_pagar_proveedores.id_tipo_documento AS id_tipo_documento,
            job_cuentas_por_pagar_proveedores.numero_factura AS numero_consecutivo,
            '0' AS id_tipo_comprobante,
            '1' AS numero_comprobante,
            job_cuentas_por_pagar_proveedores.id_sucursal AS sucursalGenera,
            job_movimiento_contable_proveedores.valor_contable AS valor,
            job_movimiento_contable_proveedores.valor_base1 AS valor_base1,
            job_movimiento_contable_proveedores.valor_base2 AS valor_base2,
            job_tablas.id AS id_tabla,
            job_cuentas_por_pagar_proveedores.id AS id_registro

	FROM    job_cuentas_por_pagar_proveedores,
            job_movimiento_contable_proveedores,
            job_proveedores,
            job_terceros,
            job_sucursales,
            job_plan_contable pp,
            job_auxiliares_contables,
            job_tablas,
            job_tipos_documentos

    WHERE   job_cuentas_por_pagar_proveedores.id = job_movimiento_contable_proveedores.id_cxp_proveedores
            AND job_movimiento_contable_proveedores.id_plan_contable = pp.id
            AND job_movimiento_contable_proveedores.id_auxiliar = job_auxiliares_contables.id
            AND job_cuentas_por_pagar_proveedores.id_proveedor = job_proveedores.id
            AND job_proveedores.id_tercero = job_terceros.id
            AND job_cuentas_por_pagar_proveedores.id_sucursal = job_sucursales.id
            AND job_cuentas_por_pagar_proveedores.id_tipo_documento = job_tipos_documentos.id
            AND job_tablas.nombre_tabla = 'cuentas_por_pagar_proveedores'
            AND job_cuentas_por_pagar_proveedores.id != 0)

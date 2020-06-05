/***
Vista de cuentas por pagar a proveedores con los abonos a cada vencimiento de la facturación
adiconar la sentencia UNION ALL al final de cada consulta adicionada a la vista.
los campos necesarios para la vista son:
* ID_PROVEEDOR
* ID_TERCERO
* SUCURSAL
* ID_CUENTA_PAGAR
* NUMERO FACTURA
* FECHA_FACTURA
* ID_VENCIMIENTO
* FECHA_VENCIMIENTO
* VALOR_VENCIMIENTO
* TIPO_DOCUENTO
* CONSECUTIVO_DOCUMENTO
* DESCRIPCION_DOCUMENTO(Descrición del tipo documento y el numero consecutivo)
* TABLA (tabla q genera la afectación ya sea a la factura o aun vencimiento de la misma)
* ID_ABONO
* VALOR_ABONO
* SALDO
* ESTADO(1->Activo, 2->Inactivo)

Al final en el SELECT que consulta la tabla de vencimientos cuentas por pagar proveedores
adicionar el SELECT NOT IN para la vista sentencia sql adicionada a la tabla.
***/

CREATE OR REPLACE ALGORITHM = MERGE VIEW job_consulta_cxp_proveedores AS
    (SELECT job_cuentas_por_pagar_proveedores.id_proveedor AS id_proveedor,
            job_terceros.id AS id_tercero,
            job_cuentas_por_pagar_proveedores.id_sucursal AS id_sucursal,
            job_cuentas_por_pagar_proveedores.id AS id_cxp,
            job_cuentas_por_pagar_proveedores.numero_factura AS numero_factura,
            job_cuentas_por_pagar_proveedores.fecha_factura AS fecha_factura,
            job_vencimiento_cuentas_por_pagar.id AS id_vencimiento,
            job_vencimiento_cuentas_por_pagar.fecha_vencimiento AS fecha_vencimiento,
            job_vencimiento_cuentas_por_pagar.valor_cuota AS valor_vencimiento,
            job_consecutivo_documentos.id_tipo_documento AS id_tipo_documento,
            job_consecutivo_documentos.numero_consecutivo AS consecutivo_documento,
            CONCAT(job_tipos_documentos.descripcion,' - ','No.',job_consecutivo_documentos.numero_consecutivo) AS descripcion_documento,
            'movimientos_pagos_proveedores' AS tabla,
            job_movimientos_pagos_proveedores.id AS id_abono,
            job_movimientos_pagos_proveedores.valor AS valor_abono,
            '1' AS estado

    FROM    job_cuentas_por_pagar_proveedores,
            job_sucursales,
            job_terceros,
            job_proveedores,
            job_vencimiento_cuentas_por_pagar,
            job_movimientos_pagos_proveedores,
            job_pagos_proveedores,
            job_consecutivo_documentos,
            job_tipos_documentos,
            job_tablas

    WHERE   job_cuentas_por_pagar_proveedores.id = job_vencimiento_cuentas_por_pagar.id_cxp_proveedores
            AND job_movimientos_pagos_proveedores.id_pago = job_pagos_proveedores.id
            AND job_movimientos_pagos_proveedores.id_vencimiento = job_vencimiento_cuentas_por_pagar.id
            AND job_cuentas_por_pagar_proveedores.id_proveedor = job_proveedores.id
            AND job_proveedores.id_tercero = job_terceros.id
            AND job_cuentas_por_pagar_proveedores.id_sucursal = job_sucursales.id
            AND job_cuentas_por_pagar_proveedores.id != 0
            AND job_consecutivo_documentos.id_registro_tabla = job_pagos_proveedores.id
            AND job_consecutivo_documentos.id_sucursal = job_sucursales.id
            AND job_consecutivo_documentos.id_tipo_documento = job_tipos_documentos.id
            AND job_consecutivo_documentos.id_tabla = job_tablas.id
            AND job_tablas.nombre_tabla = 'pagos_proveedores')

    UNION ALL

    (SELECT job_cuentas_por_pagar_proveedores.id_proveedor AS id_proveedor,
            job_terceros.id AS id_tercero,
            job_cuentas_por_pagar_proveedores.id_sucursal AS id_sucursal,
            job_cuentas_por_pagar_proveedores.id AS id_cxp,
            job_cuentas_por_pagar_proveedores.numero_factura AS numero_factura,
            job_cuentas_por_pagar_proveedores.fecha_factura AS fecha_factura,
            job_vencimiento_cuentas_por_pagar.id AS id_vencimiento,
            job_vencimiento_cuentas_por_pagar.fecha_vencimiento AS fecha_vencimiento,
            job_vencimiento_cuentas_por_pagar.valor_cuota AS valor_vencimiento,
            job_consecutivo_documentos.id_tipo_documento AS id_tipo_documento,
            job_consecutivo_documentos.numero_consecutivo AS consecutivo_documento,
            CONCAT(job_tipos_documentos.descripcion,' - ','No.',job_consecutivo_documentos.numero_consecutivo) AS descripcion_documento,
            'movimiento_notas_varias_contables' AS tabla,
            job_notas_varias_contables.id AS id_abono,
            job_notas_varias_contables.valor AS valor_abono,
            job_notas_varias_contables.estado AS estado

    FROM    job_cuentas_por_pagar_proveedores,
            job_sucursales,
            job_terceros,
            job_proveedores,
            job_vencimiento_cuentas_por_pagar,
            job_movimiento_notas_varias_contables,
            job_notas_varias_contables,
            job_consecutivo_documentos,
            job_tipos_documentos,
            job_tablas

    WHERE   job_cuentas_por_pagar_proveedores.id = job_movimiento_notas_varias_contables.id_cxp_proveedores
            AND job_movimiento_notas_varias_contables.id_nota = job_notas_varias_contables.id
            AND job_movimiento_notas_varias_contables.id_vencimiento_cxp = job_vencimiento_cuentas_por_pagar.id
            AND job_notas_varias_contables.id_proveedor = job_proveedores.id
            AND job_proveedores.id_tercero = job_terceros.id
            AND job_consecutivo_documentos.id_registro_tabla = job_notas_varias_contables.id
            AND job_notas_varias_contables.id_sucursal = job_consecutivo_documentos.id_sucursal
            AND job_consecutivo_documentos.id_sucursal = job_sucursales.id
            AND job_notas_varias_contables.id_tipo_documento = job_tipos_documentos.id
            AND job_consecutivo_documentos.id_tabla = job_tablas.id
            AND job_tablas.nombre_tabla = 'notas_varias_contables')

    UNION ALL

    (SELECT job_cuentas_por_pagar_proveedores.id_proveedor AS id_proveedor,
            job_terceros.id AS id_tercero,
            job_cuentas_por_pagar_proveedores.id_sucursal AS id_sucursal,
            job_cuentas_por_pagar_proveedores.id AS id_cxp,
            job_cuentas_por_pagar_proveedores.numero_factura AS numero_factura,
            job_cuentas_por_pagar_proveedores.fecha_factura AS fecha_factura,
            job_vencimiento_cuentas_por_pagar.id AS id_vencimiento,
            job_vencimiento_cuentas_por_pagar.fecha_vencimiento AS fecha_vencimiento,
            job_vencimiento_cuentas_por_pagar.valor_cuota AS valor_vencimiento,
            '0' AS consecutivo_documento,
            '0' AS id_tipo_documento,
            '' AS descripcion_documento,
            'vencimiento_cuentas_por_pagar' AS tabla,
            'NULL' AS id_abono,
            '0' AS valor_abono,
            '1' AS estado

     FROM   job_cuentas_por_pagar_proveedores,
            job_sucursales,
            job_terceros,
            job_proveedores,
            job_vencimiento_cuentas_por_pagar

     WHERE  job_cuentas_por_pagar_proveedores.id = job_vencimiento_cuentas_por_pagar.id_cxp_proveedores
            AND job_cuentas_por_pagar_proveedores.id_proveedor = job_proveedores.id
            AND job_proveedores.id_tercero = job_terceros.id
            AND job_cuentas_por_pagar_proveedores.id_sucursal = job_sucursales.id
            AND job_cuentas_por_pagar_proveedores.id != 0
            AND job_vencimiento_cuentas_por_pagar.id NOT IN (SELECT id_vencimiento FROM job_movimientos_pagos_proveedores WHERE id != 0 GROUP BY id_vencimiento))

    UNION ALL

    (SELECT job_cuentas_por_pagar_proveedores.id_proveedor AS id_proveedor,
            job_terceros.id AS id_tercero,
            job_cuentas_por_pagar_proveedores.id_sucursal AS id_sucursal,
            job_cuentas_por_pagar_proveedores.id AS id_cxp,
            job_cuentas_por_pagar_proveedores.numero_factura AS numero_factura,
            job_cuentas_por_pagar_proveedores.fecha_factura AS fecha_factura,
            job_vencimiento_notas_facturacion.id AS id_vencimiento,
            job_vencimiento_notas_facturacion.fecha_vencimiento AS fecha_vencimiento,
            job_vencimiento_notas_facturacion.valor AS valor_vencimiento,
            job_consecutivo_documentos.numero_consecutivo AS consecutivo_documento,
            job_notas_temporales_cxp_proveedores.id_tipo_documento AS id_tipo_documento,
            CONCAT(job_tipos_documentos.descripcion,' - ',job_consecutivo_documentos.numero_consecutivo) AS descripcion_documento,
            'vencimiento_notas_facturacion' AS tabla,
            'NULL' AS id_abono,
            '0' AS valor_abono,
            '1' AS estado

     FROM   job_cuentas_por_pagar_proveedores,
            job_sucursales,
            job_terceros,
            job_proveedores,
            job_vencimiento_notas_facturacion,
            job_notas_temporales_cxp_proveedores,
            job_tipos_documentos,
            job_consecutivo_documentos,
            job_tablas

     WHERE  job_cuentas_por_pagar_proveedores.id = job_notas_temporales_cxp_proveedores.id_cxp_proveedores
            AND job_vencimiento_notas_facturacion.id_nota_facturacion = job_notas_temporales_cxp_proveedores.id
            AND job_cuentas_por_pagar_proveedores.id_proveedor = job_proveedores.id
            AND job_proveedores.id_tercero = job_terceros.id
            AND job_cuentas_por_pagar_proveedores.id_sucursal = job_sucursales.id
            AND job_notas_temporales_cxp_proveedores.id_tipo_documento = job_tipos_documentos.id
            AND job_tipos_documentos.id = job_consecutivo_documentos.id_tipo_documento
            AND job_tablas.nombre_tabla = 'cuentas_por_pagar_proveedores'
            AND job_tablas.id = job_consecutivo_documentos.id_tabla
            AND job_consecutivo_documentos.id_registro_tabla = job_cuentas_por_pagar_proveedores.id)

    UNION ALL

    (SELECT job_cruce_contabilizacion_devoluciones.id_proveedor AS id_proveedor,
            job_terceros.id AS id_tercero,
            job_cruce_contabilizacion_devoluciones.id_sucursal AS id_sucursal,
            job_cruce_contabilizacion_devoluciones.id AS id_cxp,
            '' AS numero_factura,
            job_cruce_contabilizacion_devoluciones.fecha_contabiliza AS fecha_factura,
            job_documentos_proveedores_cruzados.id_vencimiento_afectado AS id_vencimiento,
            '0000-00-00' AS fecha_vencimiento,
            job_documentos_proveedores_cruzados.valor_vencimiento_afectado AS valor_vencimiento,
            job_consecutivo_documentos.id_tipo_documento AS id_tipo_documento,
            job_consecutivo_documentos.numero_consecutivo AS consecutivo_documento,
            CONCAT(job_tipos_documentos.descripcion,' - ','No.',job_consecutivo_documentos.numero_consecutivo) AS descripcion_documento,
            job_documentos_proveedores_cruzados.tabla_vencimiento_afectada AS tabla,
            job_documentos_proveedores_cruzados.id_vencimiento_afecta AS id_abono,
            job_documentos_proveedores_cruzados.valor_cruce AS valor_abono,
            '1' AS estado

    FROM    job_cruce_contabilizacion_devoluciones,
            job_sucursales,
            job_terceros,
            job_proveedores,
            job_documentos_proveedores_cruzados,
            job_consecutivo_documentos,
            job_tipos_documentos,
            job_tablas

    WHERE   job_cruce_contabilizacion_devoluciones.id = job_documentos_proveedores_cruzados.id_cruce_documentos
            AND job_cruce_contabilizacion_devoluciones.id_proveedor = job_proveedores.id
            AND job_proveedores.id_tercero = job_terceros.id
            AND job_cruce_contabilizacion_devoluciones.id_sucursal = job_sucursales.id
            AND job_consecutivo_documentos.id_registro_tabla = job_cruce_contabilizacion_devoluciones.id
            AND job_cruce_contabilizacion_devoluciones.id_tipo_documento = job_tipos_documentos.id
            AND job_tipos_documentos.id = job_consecutivo_documentos.id_tipo_documento
            AND job_tablas.nombre_tabla = 'cruce_contabilizacion_devoluciones'
            AND job_consecutivo_documentos.id_tabla = job_tablas.id
            AND job_documentos_proveedores_cruzados.estado != 2)

     UNION ALL

    (SELECT job_cruce_contabilizacion_devoluciones.id_proveedor AS id_proveedor,
            job_terceros.id AS id_tercero,
            job_cruce_contabilizacion_devoluciones.id_sucursal AS id_sucursal,
            job_cruce_contabilizacion_devoluciones.id AS id_cxp,
            '' AS numero_factura,
            job_cruce_contabilizacion_devoluciones.fecha_contabiliza AS fecha_factura,
            job_documentos_proveedores_cruzados.id_vencimiento_afectado AS id_vencimiento,
            '0000-00-00' AS fecha_vencimiento,
            job_documentos_proveedores_cruzados.valor_vencimiento_afectado AS valor_vencimiento,
            job_consecutivo_documentos.id_tipo_documento AS id_tipo_documento,
            job_consecutivo_documentos.numero_consecutivo AS consecutivo_documento,
            CONCAT(job_tipos_documentos.descripcion,' - ','No.',job_consecutivo_documentos.numero_consecutivo) AS descripcion_documento,
            job_documentos_proveedores_cruzados.tabla_vencimiento_afectada AS tabla,
            job_documentos_proveedores_cruzados.id_vencimiento_afecta AS id_abono,
            job_documentos_proveedores_cruzados.valor_cruce AS valor_abono,
            '1' AS estado

    FROM    job_cruce_contabilizacion_devoluciones,
            job_sucursales,
            job_terceros,
            job_proveedores,
            job_documentos_proveedores_cruzados,
            job_consecutivo_documentos,
            job_tipos_documentos,
            job_tablas

    WHERE   job_cruce_contabilizacion_devoluciones.id = job_documentos_proveedores_cruzados.id_cruce_documentos
            AND job_cruce_contabilizacion_devoluciones.id_proveedor = job_proveedores.id
            AND job_proveedores.id_tercero = job_terceros.id
            AND job_cruce_contabilizacion_devoluciones.id_sucursal = job_sucursales.id
            AND job_consecutivo_documentos.id_registro_tabla = job_cruce_contabilizacion_devoluciones.id
            AND job_cruce_contabilizacion_devoluciones.id_tipo_documento = job_tipos_documentos.id
            AND job_tipos_documentos.id = job_consecutivo_documentos.id_tipo_documento
            AND job_tablas.nombre_tabla = 'cruce_contabilizacion_devoluciones'
            AND job_consecutivo_documentos.id_tabla = job_tablas.id
            AND job_documentos_proveedores_cruzados.estado != 1)

    UNION ALL

    (SELECT job_cuentas_cobrar_devoluciones_proveedores.id_proveedor AS id_proveedor,
            job_terceros.id AS id_tercero,
            job_cuentas_cobrar_devoluciones_proveedores.id_sucursal AS id_sucursal,
            job_cuentas_cobrar_devoluciones_proveedores.id AS id_cxp,
            '' AS numero_factura,
            '' AS fecha_factura,
            job_vencimientos_devoluciones_compras.id AS id_vencimiento,
            job_vencimientos_devoluciones_compras.fecha_vencimiento AS fecha_vencimiento,
            job_vencimientos_devoluciones_compras.valor AS valor_vencimiento,
            job_consecutivo_documentos.numero_consecutivo AS consecutivo_documento,
            job_consecutivo_documentos.id_tipo_documento AS id_tipo_documento,
            CONCAT(job_tipos_documentos.descripcion,' - ',job_consecutivo_documentos.numero_consecutivo) AS descripcion_documento,
            'vencimientos_devoluciones_compras' AS tabla,
            '0' AS id_abono,
            '0' AS valor_abono,
            '1' AS estado

     FROM   job_cuentas_cobrar_devoluciones_proveedores,
            job_sucursales,
            job_terceros,
            job_proveedores,
            job_vencimientos_devoluciones_compras,
            job_tipos_documentos,
            job_consecutivo_documentos,
            job_tablas

     WHERE  job_cuentas_cobrar_devoluciones_proveedores.id = job_vencimientos_devoluciones_compras.id_cuenta_cobrar
            AND job_cuentas_cobrar_devoluciones_proveedores.id_proveedor = job_proveedores.id
            AND job_proveedores.id_tercero = job_terceros.id
            AND job_cuentas_cobrar_devoluciones_proveedores.id_sucursal = job_consecutivo_documentos.id_sucursal
            AND job_consecutivo_documentos.id_registro_tabla = job_cuentas_cobrar_devoluciones_proveedores.id
            AND job_consecutivo_documentos.id_sucursal = job_sucursales.id
            AND job_consecutivo_documentos.id_tipo_documento = job_tipos_documentos.id
            AND job_consecutivo_documentos.id_tabla = job_tablas.id
            AND job_tablas.nombre_tabla = 'cuentas_cobrar_devoluciones_proveedores'
)

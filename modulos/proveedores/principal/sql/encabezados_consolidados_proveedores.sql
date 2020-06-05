/***
Vista de movimientos contables consolidados adiconar la sentencia UNION ALL al final de cada consulta adicionada a la vista.
los campos necesarios para la vista son:
* ID_PROVEEDOR
* ID_TERCERO
* SUCURSAL
* ID_CXP
* NUMERO FACTURA
* FECHA_FACTURA
* VALOR
* TIPO_DOCUENTO
* CONSECUTIVO_DOCUMENTO
* DESCRIPCION_DOCUMENTO(Descrición del tipo documento y el numero consecutivo)
* TABLA (tabla q genera la afectación ya sea a la factura o aun vencimiento de la misma)
* ID_REGISTRO_TABLA
* ID_VENCIMIENTO
* ESTADO(1->Activo, 2->Inactivo)
* ENCABEZADO(1->Si, 2->No)

Al final en el SELECT que consulta la tabla de vencimientos cuentas por pagar proveedores
adicionar el SELECT NOT IN para la vista sentencia sql adicionada a la tabla.
***/


CREATE OR REPLACE ALGORITHM = MERGE VIEW job_movimientos_consolidados_proveedores AS
    (SELECT job_cuentas_por_pagar_proveedores.id_proveedor AS id_proveedor,
            job_pagos_proveedores.id_tercero AS id_tercero,
            job_cuentas_por_pagar_proveedores.id_sucursal AS id_sucursal,
            job_cuentas_por_pagar_proveedores.id AS id_cxp,
            job_cuentas_por_pagar_proveedores.numero_factura AS numero_factura,
            job_pagos_proveedores.valor AS valor,
            job_consecutivo_documentos.id_tipo_documento AS id_tipo_documento,
            job_consecutivo_documentos.numero_consecutivo AS consecutivo_documento,
            CONCAT(job_tipos_documentos.descripcion,' - ','No.',job_consecutivo_documentos.numero_consecutivo) AS descripcion_documento,
            'pagos_proveedores' AS tabla,
            job_pagos_proveedores.id AS id_registro_tabla,
            '0' AS id_vencimiento,
            '' AS tabla_vencimientos,
            '1' AS estado,
            '1' AS encabezado

    FROM    job_cuentas_por_pagar_proveedores,
            job_sucursales,
            job_terceros,
            job_proveedores,
            job_vencimiento_cuentas_por_pagar,
            job_movimientos_pagos_proveedores ,
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
            job_pagos_proveedores.id_tercero AS id_tercero,
            job_cuentas_por_pagar_proveedores.id_sucursal AS id_sucursal,
            job_cuentas_por_pagar_proveedores.id AS id_cxp,
            job_cuentas_por_pagar_proveedores.numero_factura AS numero_factura,
            job_movimientos_pagos_proveedores.valor AS valor,
            job_consecutivo_documentos.id_tipo_documento AS id_tipo_documento,
            job_consecutivo_documentos.numero_consecutivo AS consecutivo_documento,
            CONCAT(job_tipos_documentos.descripcion,' - ','No.',job_consecutivo_documentos.numero_consecutivo) AS descripcion_documento,
            'pagos_proveedores' AS tabla,
            job_pagos_proveedores.id AS id_registro_tabla,
            job_movimientos_pagos_proveedores.id_vencimiento AS id_vencimiento,
            '' AS tabla_vencimientos,
            '1' AS estado,
            '2' AS encabezado

    FROM    job_cuentas_por_pagar_proveedores,
            job_sucursales,
            job_terceros,
            job_proveedores,
            job_vencimiento_cuentas_por_pagar,
            job_movimientos_pagos_proveedores ,
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

    (SELECT job_notas_varias_contables.id_proveedor AS id_proveedor,
            job_terceros.id AS id_tercero,
            job_notas_varias_contables.id_sucursal AS id_sucursal,
            job_cuentas_por_pagar_proveedores.id AS id_cxp,
            job_cuentas_por_pagar_proveedores.numero_factura AS numero_factura,
            job_notas_varias_contables.valor AS valor,
            job_notas_varias_contables.id_tipo_documento AS id_tipo_documento,
            job_consecutivo_documentos.numero_consecutivo AS consecutivo_documento,
            CONCAT(job_tipos_documentos.descripcion,' - ','No.',job_consecutivo_documentos.numero_consecutivo) AS descripcion_documento,
            'notas_varias_contables' AS tabla,
            job_notas_varias_contables.id AS id_registro_tabla,
            '0' AS id_vencimiento,
            '' AS tabla_vencimientos,
            job_notas_varias_contables.estado AS estado,
            '1' AS encabezado

    FROM    job_cuentas_por_pagar_proveedores,
            job_sucursales,
            job_terceros,
            job_proveedores,
            job_vencimiento_cuentas_por_pagar,
            job_movimiento_notas_varias_contables ,
            job_notas_varias_contables,
            job_consecutivo_documentos,
            job_tipos_documentos,
            job_tablas

    WHERE   job_cuentas_por_pagar_proveedores.id = job_movimiento_notas_varias_contables.id_cxp_proveedores
            AND job_movimiento_notas_varias_contables.id_nota = job_notas_varias_contables.id
            AND job_movimiento_notas_varias_contables.id_vencimiento_cxp = job_vencimiento_cuentas_por_pagar.id
            AND job_cuentas_por_pagar_proveedores.id_proveedor = job_proveedores.id
            AND job_proveedores.id_tercero = job_terceros.id
            AND job_cuentas_por_pagar_proveedores.id != 0
            AND job_consecutivo_documentos.id_registro_tabla = job_notas_varias_contables.id
            AND job_notas_varias_contables.id_sucursal = job_sucursales.id
            AND job_sucursales.id = job_consecutivo_documentos.id_sucursal
            AND job_notas_varias_contables.id_tipo_documento = job_tipos_documentos.id
            AND job_tipos_documentos.id = job_consecutivo_documentos.id_tipo_documento
            AND job_consecutivo_documentos.id_tabla = job_tablas.id
            AND job_tablas.nombre_tabla = 'notas_varias_contables'
            AND job_notas_varias_contables.estado != 2
            GROUP BY job_notas_varias_contables.id)

    UNION ALL
    (SELECT job_notas_varias_contables.id_proveedor AS id_proveedor,
            job_terceros.id AS id_tercero,
            job_notas_varias_contables.id_sucursal AS id_sucursal,
            job_cuentas_por_pagar_proveedores.id AS id_cxp,
            job_cuentas_por_pagar_proveedores.numero_factura AS numero_factura,
            job_movimiento_notas_varias_contables.valor_contable AS valor,
            job_notas_varias_contables.id_tipo_documento AS id_tipo_documento,
            job_consecutivo_documentos.numero_consecutivo AS consecutivo_documento,
            CONCAT(job_tipos_documentos.descripcion,' - ','No.',job_consecutivo_documentos.numero_consecutivo) AS descripcion_documento,
            'notas_varias_contables' AS tabla,
            job_notas_varias_contables.id AS id_registro_tabla,
            job_movimiento_notas_varias_contables.id_vencimiento_cxp AS id_vencimiento,
            '' AS tabla_vencimientos,
            job_movimiento_notas_varias_contables.estado_movimiento AS estado,
            '2' AS encabezado

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
            AND job_cuentas_por_pagar_proveedores.id_proveedor = job_proveedores.id
            AND job_proveedores.id_tercero = job_terceros.id
            AND job_cuentas_por_pagar_proveedores.id_sucursal = job_sucursales.id
            AND job_cuentas_por_pagar_proveedores.id != 0
            AND job_consecutivo_documentos.id_registro_tabla = job_notas_varias_contables.id
            AND job_notas_varias_contables.id_sucursal = job_consecutivo_documentos.id_sucursal
            AND job_consecutivo_documentos.id_sucursal = job_sucursales.id
            AND job_notas_varias_contables.id_tipo_documento = job_tipos_documentos.id
            AND job_consecutivo_documentos.id_tabla = job_tablas.id
            AND job_tablas.nombre_tabla = 'notas_varias_contables'
            AND job_notas_varias_contables.estado != 2)

    UNION ALL
    (SELECT job_cuentas_por_pagar_proveedores.id_proveedor AS id_proveedor,
            job_terceros.id AS id_tercero,
            job_cuentas_por_pagar_proveedores.id_sucursal AS id_sucursal,
            job_cuentas_por_pagar_proveedores.id AS id_cxp,
            job_cuentas_por_pagar_proveedores.numero_factura AS numero_factura,
            job_cuentas_por_pagar_proveedores.valor AS valor,
            job_cuentas_por_pagar_proveedores.id_tipo_documento AS id_tipo_documento,
            job_cuentas_por_pagar_proveedores.numero_factura AS consecutivo_documento,
            CONCAT(job_tipos_documentos.descripcion,' - ','No.',job_cuentas_por_pagar_proveedores.numero_factura) AS descripcion_documento,
            'cuentas_por_pagar_proveedores' AS tabla,
            job_cuentas_por_pagar_proveedores.id AS id_registro_tabla,
            '0' AS id_vencimiento,
            '' AS tabla_vencimientos,
            '1' AS estado ,
            '1' AS encabezado

    FROM    job_cuentas_por_pagar_proveedores,
            job_sucursales,
            job_terceros,
            job_proveedores,
            job_tipos_documentos

    WHERE   job_cuentas_por_pagar_proveedores.id_proveedor = job_proveedores.id
            AND job_proveedores.id_tercero = job_terceros.id
            AND job_cuentas_por_pagar_proveedores.id_sucursal = job_sucursales.id
            AND job_tipos_documentos.id = job_cuentas_por_pagar_proveedores.id_tipo_documento
            AND job_cuentas_por_pagar_proveedores.id != 0)

    UNION ALL
    (SELECT job_cuentas_por_pagar_proveedores.id_proveedor AS id_proveedor,
            job_terceros.id AS id_tercero,
            job_cuentas_por_pagar_proveedores.id_sucursal AS id_sucursal,
            job_cuentas_por_pagar_proveedores.id AS id_cxp,
            job_cuentas_por_pagar_proveedores.numero_factura AS numero_factura,
            job_cuentas_por_pagar_proveedores.valor AS valor,
            job_cuentas_por_pagar_proveedores.id_tipo_documento AS id_tipo_documento,
            job_cuentas_por_pagar_proveedores.numero_factura AS consecutivo_documento,
            CONCAT(job_tipos_documentos.descripcion,' - ','No.',job_cuentas_por_pagar_proveedores.numero_factura) AS descripcion_documento,
            'cuentas_por_pagar_proveedores' AS tabla,
            job_cuentas_por_pagar_proveedores.id AS id_registro_tabla,
            job_vencimiento_cuentas_por_pagar.id AS id_vencimiento,
            'vencimiento_cuentas_por_pagar' AS tabla_vencimientos,
            '1' AS estado ,
            '2' AS encabezado

     FROM   job_cuentas_por_pagar_proveedores,
            job_vencimiento_cuentas_por_pagar,
            job_sucursales,
            job_terceros,
            job_proveedores,
            job_tipos_documentos

     WHERE  job_cuentas_por_pagar_proveedores.id = job_vencimiento_cuentas_por_pagar.id_cxp_proveedores
            AND job_cuentas_por_pagar_proveedores.id_proveedor = job_proveedores.id
            AND job_proveedores.id_tercero = job_terceros.id
            AND job_cuentas_por_pagar_proveedores.id_sucursal = job_sucursales.id
            AND job_tipos_documentos.id = job_cuentas_por_pagar_proveedores.id_tipo_documento
            AND job_cuentas_por_pagar_proveedores.id != 0)

    UNION ALL
    (SELECT job_cuentas_por_pagar_proveedores.id_proveedor AS id_proveedor,
            job_terceros.id AS id_tercero,
            job_cuentas_por_pagar_proveedores.id_sucursal AS id_sucursal,
            '0' AS id_cxp,
            '0' AS numero_factura,
            job_notas_temporales_cxp_proveedores.valor AS valor,
            job_notas_temporales_cxp_proveedores.id_tipo_documento AS id_tipo_documento,
            job_consecutivo_documentos.numero_consecutivo AS consecutivo_documento,
            CONCAT(job_tipos_documentos.descripcion,' - ','No.', job_consecutivo_documentos.numero_consecutivo) AS descripcion_documento,
            'notas_temporales_cxp_proveedores' AS tabla,
            job_notas_temporales_cxp_proveedores.id AS id_registro_tabla,
            job_notas_temporales_cxp_proveedores.id AS id_vencimiento,
            'vencimiento_notas_facturacion' AS tabla_vencimientos,
            '1' AS estado ,
            '1' AS encabezado

     FROM   job_cuentas_por_pagar_proveedores,
            job_vencimiento_notas_facturacion,
            job_sucursales,
            job_terceros,
            job_proveedores,
            job_tipos_documentos,
            job_notas_temporales_cxp_proveedores,
            job_consecutivo_documentos,
            job_tablas

     WHERE  job_cuentas_por_pagar_proveedores.id = job_notas_temporales_cxp_proveedores.id_cxp_proveedores
            AND job_cuentas_por_pagar_proveedores.id_proveedor = job_proveedores.id
            AND job_proveedores.id_tercero = job_terceros.id
            AND job_cuentas_por_pagar_proveedores.id_sucursal = job_sucursales.id
            AND job_tipos_documentos.id = job_notas_temporales_cxp_proveedores.id_tipo_documento
            AND job_consecutivo_documentos.id_sucursal = job_sucursales.id
            AND job_consecutivo_documentos.id_tipo_documento = job_tipos_documentos.id
            AND job_tablas.nombre_tabla = 'cuentas_por_pagar_proveedores'
            AND job_consecutivo_documentos.id_tabla = job_tablas.id
            AND job_consecutivo_documentos.id_registro_tabla = job_cuentas_por_pagar_proveedores.id)

    UNION ALL
    (SELECT job_cuentas_cobrar_devoluciones_proveedores.id_proveedor AS id_proveedor,
            job_terceros.id AS id_tercero,
            job_cuentas_cobrar_devoluciones_proveedores.id_sucursal AS id_sucursal,
            job_cuentas_cobrar_devoluciones_proveedores.id AS id_cxp,
            '' AS numero_factura,
            job_cuentas_cobrar_devoluciones_proveedores.valor AS valor,
            job_consecutivo_documentos.id_tipo_documento AS id_tipo_documento,
            job_consecutivo_documentos.numero_consecutivo AS consecutivo_documento,
            CONCAT(job_tipos_documentos.descripcion,' - ','No.',job_consecutivo_documentos.numero_consecutivo) AS descripcion_documento,
            'cuentas_cobrar_devoluciones_proveedores' AS tabla,
            job_cuentas_cobrar_devoluciones_proveedores.id AS id_registro_tabla,
            '0' AS id_vencimiento,
            '' AS tabla_vencimientos,
            '1' AS estado ,
            '1' AS encabezado

    FROM    job_cuentas_cobrar_devoluciones_proveedores,
            job_sucursales,
            job_terceros,
            job_proveedores,
            job_tipos_documentos,
            job_consecutivo_documentos,
            job_tablas,
            job_vencimientos_devoluciones_compras

    WHERE   job_cuentas_cobrar_devoluciones_proveedores.id_proveedor = job_proveedores.id
            AND job_proveedores.id_tercero = job_terceros.id
            AND job_cuentas_cobrar_devoluciones_proveedores.id_sucursal = job_sucursales.id
            AND job_consecutivo_documentos.id_registro_tabla = job_cuentas_cobrar_devoluciones_proveedores.id
            AND job_consecutivo_documentos.id_sucursal = job_cuentas_cobrar_devoluciones_proveedores.id_sucursal
            AND job_consecutivo_documentos.id_tipo_documento = job_tipos_documentos.id
            AND job_consecutivo_documentos.id_tabla = job_tablas.id
            AND job_tablas.nombre_tabla = 'cuentas_cobrar_devoluciones_proveedores'
            AND job_cuentas_cobrar_devoluciones_proveedores.id != 0)

    UNION ALL
    (SELECT job_cuentas_cobrar_devoluciones_proveedores.id_proveedor AS id_proveedor,
            job_terceros.id AS id_tercero,
            job_cuentas_cobrar_devoluciones_proveedores.id_sucursal AS id_sucursal,
            job_cuentas_cobrar_devoluciones_proveedores.id AS id_cxp,
            '' AS numero_factura,
            job_cuentas_cobrar_devoluciones_proveedores.valor AS valor,
            job_consecutivo_documentos.id_tipo_documento AS id_tipo_documento,
            job_consecutivo_documentos.numero_consecutivo AS consecutivo_documento,
            CONCAT(job_tipos_documentos.descripcion,' - ','No.',job_consecutivo_documentos.numero_consecutivo) AS descripcion_documento,
            'cuentas_cobrar_devoluciones_proveedores' AS tabla,
            job_cuentas_cobrar_devoluciones_proveedores.id AS id_registro_tabla,
            job_vencimientos_devoluciones_compras.id AS id_vencimiento,
            'vencimientos_devoluciones_compras' AS tabla_vencimientos,
            '1' AS estado ,
            '2' AS encabezado

    FROM    job_cuentas_cobrar_devoluciones_proveedores,
            job_sucursales,
            job_terceros,
            job_proveedores,
            job_tipos_documentos,
            job_consecutivo_documentos,
            job_tablas,
            job_vencimientos_devoluciones_compras

    WHERE   job_cuentas_cobrar_devoluciones_proveedores.id = job_vencimientos_devoluciones_compras.id_cuenta_cobrar
            AND job_cuentas_cobrar_devoluciones_proveedores.id_proveedor = job_proveedores.id
            AND job_proveedores.id_tercero = job_terceros.id
            AND job_cuentas_cobrar_devoluciones_proveedores.id_sucursal = job_sucursales.id
            AND job_consecutivo_documentos.id_registro_tabla = job_cuentas_cobrar_devoluciones_proveedores.id
            AND job_consecutivo_documentos.id_sucursal = job_cuentas_cobrar_devoluciones_proveedores.id_sucursal
            AND job_consecutivo_documentos.id_tipo_documento = job_tipos_documentos.id
            AND job_consecutivo_documentos.id_tabla = job_tablas.id
            AND job_tablas.nombre_tabla = 'cuentas_cobrar_devoluciones_proveedores'
            AND job_cuentas_cobrar_devoluciones_proveedores.id != 0)

-- Vista general de movimientos de articulos
-- Adicione la consulta que provee los movimientos de su modulo con la sentencia 'UNION ALL'
-- Campos requeridos:
--      - id_articulo
--      - sentido -> 1:Entrada, 2->Salida
--      - cantidad
--      - fecha -> En formato 'AAAA-MM-DD hh:mm:ss'
--      - id_tipo_documento
--      - numero_consecutivo
--      - id_bodega
--      - valor(debe ser el valor neto del articulo)
--      - modulo
--      - id_registro

CREATE OR REPLACE ALGORITHM = MERGE VIEW job_movimientos_articulos AS

    (SELECT mem.id_articulo,
            '1' AS sentido,
            mem.cantidad,
            rmc.fecha_ingreso AS fecha,
            cd.id_tipo_documento,
            cd.numero_consecutivo,
            mem.id_bodega,
            aoc.valor_total_neto AS valor,
            'GESTENTR' AS modulo,
            rmc.id AS id_registro

    FROM    job_recibo_mercancia_compras AS rmc,
            job_movimiento_entrada_mercancia AS mem,
			job_articulos_ordenes_compra aoc,
            job_tablas as t,
            job_consecutivo_documentos as cd

    WHERE   t.nombre_tabla  = 'recibo_mercancia_compras' AND
            cd.id_tabla = t.id AND
            cd.id_registro_tabla = rmc.id AND
            mem.id_recibo_mercancia_compras = rmc.id AND
			mem.id_orden_compra_articulo = aoc.id)

    UNION ALL

    (SELECT job_despacho_articulos_clientes_mayoristas.id_articulo,
           '2' AS sentido,
           job_despacho_articulos_clientes_mayoristas.cantidad AS cantidad,
           job_despachos_clientes_mayoristas.fecha_despacho AS fecha,
           job_consecutivo_documentos.id_tipo_documento AS id_tipo_documento,
           job_consecutivo_documentos.numero_consecutivo AS numero_consecutivo,
           job_despacho_articulos_clientes_mayoristas.id_bodega AS id_bodega,
           job_despacho_articulos_clientes_mayoristas.valor_unitario AS valor,
           'GESTDPMA' AS modulo,
           job_despachos_clientes_mayoristas.id AS id_registro

    FROM   job_despacho_articulos_clientes_mayoristas,job_despachos_clientes_mayoristas,job_tipos_documentos,
           job_consecutivo_documentos,job_tablas,job_sucursales

    WHERE  job_despacho_articulos_clientes_mayoristas.id_despacho = job_despachos_clientes_mayoristas.id
           AND job_despachos_clientes_mayoristas.id_tipo_documento = job_tipos_documentos.id
           AND job_consecutivo_documentos.id_tipo_documento = job_tipos_documentos.id
           AND job_tablas.nombre_tabla = 'despachos_clientes_mayoristas'
           AND job_consecutivo_documentos.id_tabla = job_tablas.id
           AND job_consecutivo_documentos.id_registro_tabla = job_despachos_clientes_mayoristas.id
           AND job_despachos_clientes_mayoristas.id_sucursal = job_sucursales.id
           AND job_consecutivo_documentos.id_sucursal = job_sucursales.id
           AND job_despacho_articulos_clientes_mayoristas.estado = 2
           AND job_despacho_articulos_clientes_mayoristas.estado_facturado BETWEEN 2 AND 3)

    UNION ALL

    (SELECT job_articulos_traslados_positivos.id_articulo,
           '1' AS sentido,
           job_articulos_traslados_positivos.cantidad AS cantidad,
           job_traslados_positivos.fecha_entrada AS fecha,
           job_consecutivo_documentos.id_tipo_documento AS id_tipo_documento,
           job_consecutivo_documentos.numero_consecutivo AS numero_consecutivo,
           job_articulos_traslados_positivos.id_bodega AS id_bodega,
           job_articulos_traslados_positivos.valor_unitario AS valor,
           'GESTDPMA' AS modulo,
           job_traslados_positivos.id AS id_registro

    FROM   job_articulos_traslados_positivos,job_traslados_positivos,job_tipos_documentos,
           job_consecutivo_documentos,job_tablas,job_sucursales

    WHERE  job_articulos_traslados_positivos.id_traslado_positivo = job_traslados_positivos.id
           AND job_traslados_positivos.id_tipo_documento = job_tipos_documentos.id
           AND job_consecutivo_documentos.id_tipo_documento = job_tipos_documentos.id
           AND job_tablas.nombre_tabla = 'traslados_positivos'
           AND job_consecutivo_documentos.id_tabla = job_tablas.id
           AND job_consecutivo_documentos.id_registro_tabla = job_traslados_positivos.id
           AND job_traslados_positivos.id_sucursal = job_sucursales.id
           AND job_consecutivo_documentos.id_sucursal = job_sucursales.id)
	
	UNION ALL
	
	(SELECT job_articulos_devoluciones_compras.id_articulo,
           '2' AS sentido,
           job_articulos_devoluciones_compras.cantidad AS cantidad,
           job_devoluciones_compras.fecha AS fecha,
           job_consecutivo_documentos.id_tipo_documento AS id_tipo_documento,
           job_consecutivo_documentos.numero_consecutivo AS numero_consecutivo,
           job_articulos_devoluciones_compras.id_bodega AS id_bodega,
           job_articulos_devoluciones_compras.costo AS valor,
           'GESTDECO' AS modulo,
           job_devoluciones_compras.id AS id_registro

    FROM   job_devoluciones_compras,
		   job_articulos_devoluciones_compras,
		   job_tipos_documentos,
           job_consecutivo_documentos,
		   job_tablas,
		   job_bodegas,
		   job_sucursales

    WHERE  job_articulos_devoluciones_compras.id_devolucion = job_devoluciones_compras.id
           AND job_consecutivo_documentos.id_tipo_documento = job_tipos_documentos.id
           AND job_tablas.nombre_tabla = 'devoluciones_compras'
           AND job_consecutivo_documentos.id_tabla = job_tablas.id
           AND job_consecutivo_documentos.id_registro_tabla = job_devoluciones_compras.id
		   AND job_articulos_devoluciones_compras.id_bodega = job_bodegas.id
		   AND job_bodegas.id_sucursal = job_sucursales.id
           AND job_sucursales.id = job_consecutivo_documentos.id_sucursal)

SELECT 
      pance_articulos_ordenes_compra.id,
      pance_articulos_ordenes_compra.codigo_articulo,
      pance_articulos_ordenes_compra.id_sucursal,
      pance_articulos_ordenes_compra.id_orden, 
      pance_articulos_ordenes_compra.id_proveedor,
      pance_articulos_ordenes_compra.unidades,
      (pance_kardex.existencia-pance_articulos_ordenes_compra.unidades) AS cantidad
FROM 
      pance_articulos_ordenes_compra
WHERE
      pance_articulos_ordenes_compra.id_proveedor = '1' AND 
      (SELECT 
	    pance_kardex.existencia 
       FROM 
	    pance_kardex
       WHERE 
	    pance_kardex.id_articulo = pance_articulos_ordenes_compra.codigo_articulo AND 
	    ((pance_kardex.existencia-pance_articulos_ordenes_compra.unidades)>0)
      )





SELECT 
      pance_articulos_ordenes_compra.id,
      pance_articulos_ordenes_compra.codigo_articulo,
      pance_articulos_ordenes_compra.id_sucursal,
      pance_articulos_ordenes_compra.id_orden, 
      pance_articulos_ordenes_compra.id_proveedor,
      pance_articulos_ordenes_compra.unidades,
      (pance_kardex.existencia-pance_articulos_ordenes_compra.unidades) AS cantidad
FROM 
      pance_articulos_ordenes_compra, pance_kardex
WHERE
      pance_articulos_ordenes_compra.id_proveedor = '1' AND
      pance_articulos_ordenes_compra.codigo_articulo = pance_kardex.id_articulo AND
      ((pance_articulos_ordenes_compra.unidades-pance_kardex.existencia)>0)








SELECT 
      (pance_kardex.existencia-pance_articulos_ordenes_compra.unidades)
FROM
      pance_kardex, pance_articulos_ordenes_compra
WHERE
      pance_kardex.id_articulo = pance_articulos_ordenes_compra.codigo_articulo
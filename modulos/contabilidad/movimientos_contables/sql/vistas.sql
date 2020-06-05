CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_movimientos_contables AS
SELECT CONCAT (b.codigo_sucursal,'|',b.documento_identidad_tercero,'|',b.codigo_tipo_comprobante,'|',b.numero_comprobante,'|',b.codigo_tipo_documento,'|',b.consecutivo_documento,'|',b.fecha_contabilizacion) AS id,
    suc.codigo AS id_sucursal,
    c.documento_identidad AS id_tercero,
    b.codigo_tipo_comprobante AS id_tipo_comprobante,
    b.codigo_tipo_documento AS id_tipo_documento,
    b.observaciones AS id_observaciones,
    IF(CONCAT(IF(c.primer_nombre IS NOT NULL, c.primer_nombre, ''),' ',
    IF(c.segundo_nombre IS NOT NULL, c.segundo_nombre, ''),' ',
    IF(c.primer_apellido IS NOT NULL, c.primer_apellido, ''),' ',
    IF(c.segundo_apellido IS NOT NULL, c.segundo_apellido, ''),' ',
    IF(c.razon_social IS NOT NULL, c.razon_social, ''))!='',CONCAT(IF(c.primer_nombre IS NOT NULL, c.primer_nombre, ''),' ',
    IF(c.segundo_nombre IS NOT NULL, c.segundo_nombre, ''),' ',
    IF(c.primer_apellido IS NOT NULL, c.primer_apellido, ''),' ',
    IF(c.segundo_apellido IS NOT NULL, c.segundo_apellido, ''),' ',
    IF(c.razon_social IS NOT NULL, c.razon_social, '')),"No aplica") AS TERCERO,
    d.descripcion AS TIPO_DOCUMENTO,
    b.fecha_contabilizacion AS FECHA,
    a.consecutivo AS CONSECUTIVO_DOCUMENTO,
    suc.nombre AS SUCURSAL,
IF(((SELECT SUM(valor) FROM job_items_movimientos_contables AS imc
WHERE b.codigo_sucursal=imc.codigo_sucursal AND b.documento_identidad_tercero=imc.documento_identidad_tercero AND
b.codigo_tipo_comprobante=imc.codigo_tipo_comprobante AND b.numero_comprobante=imc.numero_comprobante AND
b.codigo_tipo_documento=imc.codigo_tipo_documento AND b.consecutivo_documento=imc.consecutivo_documento AND
b.fecha_contabilizacion=imc.fecha_contabilizacion AND imc.sentido='1')
-(SELECT SUM(valor) FROM job_items_movimientos_contables AS imc
WHERE b.codigo_sucursal=imc.codigo_sucursal AND b.documento_identidad_tercero=imc.documento_identidad_tercero AND
b.codigo_tipo_comprobante=imc.codigo_tipo_comprobante AND b.numero_comprobante=imc.numero_comprobante AND
b.codigo_tipo_documento=imc.codigo_tipo_documento AND b.consecutivo_documento=imc.consecutivo_documento AND
b.fecha_contabilizacion=imc.fecha_contabilizacion AND imc.sentido='2'))=0,
FORMAT((SELECT SUM(valor) FROM job_items_movimientos_contables AS imc
WHERE b.codigo_sucursal=imc.codigo_sucursal AND b.documento_identidad_tercero=imc.documento_identidad_tercero AND
b.codigo_tipo_comprobante=imc.codigo_tipo_comprobante AND b.numero_comprobante=imc.numero_comprobante AND
b.codigo_tipo_documento=imc.codigo_tipo_documento AND b.consecutivo_documento=imc.consecutivo_documento AND
b.fecha_contabilizacion=imc.fecha_contabilizacion AND imc.sentido='1'),2),"ESTADO_1") AS CUADRE
FROM    job_consecutivo_documentos AS a,job_movimientos_contables AS b,job_terceros AS c,job_tipos_documentos AS d,job_sucursales AS suc
WHERE   d.codigo = b.codigo_tipo_documento AND
        b.codigo_sucursal = suc.codigo AND
        b.documento_identidad_tercero = c.documento_identidad AND
        a.codigo_sucursal = b.codigo_sucursal AND 
        a.codigo_tipo_documento = b.codigo_tipo_documento AND
        a.fecha_registro = b.fecha_contabilizacion AND
        a.consecutivo = b.consecutivo_documento;


CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_movimientos_contables AS
SELECT CONCAT (b.codigo_sucursal,'|',b.documento_identidad_tercero,'|',b.codigo_tipo_comprobante,'|',b.numero_comprobante,'|',b.codigo_tipo_documento,'|',b.consecutivo_documento,'|',b.fecha_contabilizacion) AS id,
CONCAT(IF(c.primer_nombre IS NOT NULL, c.primer_nombre, ''),' ',
IF(c.segundo_nombre IS NOT NULL, c.segundo_nombre, ''),' ',
IF(c.primer_apellido IS NOT NULL, c.primer_apellido, ''),' ',
IF(c.segundo_apellido IS NOT NULL, c.segundo_apellido, ''),' ',
IF(c.razon_social IS NOT NULL, c.razon_social, '')) AS TERCERO,
d.descripcion AS TIPO_DOCUMENTO,
a.consecutivo AS CONSECUTIVO_DOCUMENTO
FROM    job_consecutivo_documentos AS a,
        job_movimientos_contables AS b,
        job_terceros AS c,
        job_tipos_documentos AS d,
        job_tablas AS e
WHERE   a.id_tabla = e.id AND
        a.codigo_tipo_documento = d.codigo AND
        b.codigo_tipo_documento = d.codigo AND
        a.codigo_tipo_documento = b.codigo_tipo_documento AND
        a.codigo_sucursal = b.codigo_sucursal AND
        a.consecutivo = b.consecutivo_documento AND
        b.documento_identidad_tercero = c.documento_identidad;
        

CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_saldos_movimientos_contables AS
SELECT  imc.documento_identidad_tercero_saldo AS id_tercero,
        imc.codigo_plan_contable AS id_cuenta,
        CONCAT (cd.codigo_sucursal,'|',cd.codigo_tipo_documento,'|',cd.fecha_registro,'|',cd.consecutivo) AS id_consecutivo,
        cd.consecutivo AS consecutivo,
        cd.codigo_tipo_documento AS id_documento,
        CONCAT (imc.codigo_sucursal,'|',imc.documento_identidad_tercero,'|',imc.codigo_tipo_comprobante,'|',imc.numero_comprobante,'|',imc.codigo_tipo_documento,'|',imc.consecutivo_documento,'|',imc.fecha_contabilizacion,'|',imc.consecutivo) AS id_item_movimiento,
        CONCAT (simc.codigo_sucursal,'|',simc.documento_identidad_tercero,'|',simc.codigo_tipo_comprobante,'|',simc.numero_comprobante,'|',simc.codigo_tipo_documento,'|',simc.consecutivo_documento,'|',simc.fecha_contabilizacion,'|',simc.consecutivo,'|',simc.fecha_vencimiento) AS id_saldo,
        simc.valor AS saldo,
        simc.fecha_vencimiento AS fecha_vencimiento
FROM    job_movimientos_contables AS mc,
        job_items_movimientos_contables AS imc,
        job_saldos_items_movimientos_contables AS simc,
        job_tablas AS tb,
        job_consecutivo_documentos AS cd
WHERE   mc.codigo_sucursal=imc.codigo_sucursal AND mc.documento_identidad_tercero=imc.documento_identidad_tercero AND
        mc.codigo_tipo_comprobante=imc.codigo_tipo_comprobante AND mc.numero_comprobante=imc.numero_comprobante AND
        mc.codigo_tipo_documento=imc.codigo_tipo_documento AND mc.consecutivo_documento=imc.consecutivo_documento AND
        mc.fecha_contabilizacion=imc.fecha_contabilizacion AND imc.codigo_sucursal=simc.codigo_sucursal AND
        imc.documento_identidad_tercero=simc.documento_identidad_tercero AND imc.codigo_tipo_comprobante=simc.codigo_tipo_comprobante AND
        imc.numero_comprobante=simc.numero_comprobante AND imc.codigo_tipo_documento=simc.codigo_tipo_documento AND
        imc.consecutivo_documento=simc.consecutivo_documento AND imc.fecha_contabilizacion=simc.fecha_contabilizacion AND
        imc.consecutivo=simc.consecutivo AND mc.estado != '2' AND
        cd.codigo_tipo_documento = mc.codigo_tipo_documento AND
        cd.codigo_sucursal = mc.codigo_sucursal AND
        cd.consecutivo = mc.consecutivo_documento AND
        cd.id_tabla = tb.id AND tb.nombre_tabla = 'movimientos_contables';


CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_abonos_movimientos_contables AS
SELECT  CONCAT (aimc.codigo_sucursal_saldo,'|',aimc.documento_identidad_tercero_saldo,'|',aimc.codigo_tipo_comprobante_saldo,'|',aimc.numero_comprobante_saldo,'|',aimc.codigo_tipo_documento_saldo,'|',aimc.consecutivo_documento_saldo,'|',aimc.fecha_contabilizacion_saldo,'|',aimc.consecutivo_saldo,'|',aimc.fecha_vencimiento_saldo,'|',aimc.consecutivo) AS id,
        aimc.valor AS valor,
        aimc.fecha_pago_abono AS fecha_pago,
        aimc.codigo_tipo_documento AS codigo_tipo_documento,
        aimc.consecutivo_documento AS consecutivo_documento,
        CONCAT (aimc.codigo_sucursal_saldo,'|',aimc.documento_identidad_tercero_saldo,'|',aimc.codigo_tipo_comprobante_saldo,'|',aimc.numero_comprobante_saldo,'|',aimc.codigo_tipo_documento_saldo,'|',aimc.consecutivo_documento_saldo,'|',aimc.fecha_contabilizacion_saldo,'|',aimc.consecutivo_saldo,'|',aimc.fecha_vencimiento_saldo) AS id_saldo,
        CONCAT (aimc.codigo_sucursal,'|',aimc.documento_identidad_tercero,'|',aimc.codigo_tipo_comprobante,'|',aimc.numero_comprobante,'|',aimc.codigo_tipo_documento,'|',aimc.consecutivo_documento,'|',aimc.fecha_contabilizacion,'|',aimc.consecutivo_item) AS id_item_movimiento
FROM    job_abonos_items_movimientos_contables AS aimc,
        job_items_movimientos_contables AS imc,
        job_movimientos_contables AS mc
WHERE   imc.codigo_sucursal=aimc.codigo_sucursal AND imc.documento_identidad_tercero=aimc.documento_identidad_tercero AND
        imc.codigo_tipo_comprobante=aimc.codigo_tipo_comprobante AND imc.numero_comprobante=aimc.numero_comprobante AND
        imc.codigo_tipo_documento=aimc.codigo_tipo_documento AND imc.consecutivo_documento=aimc.consecutivo_documento AND
        imc.fecha_contabilizacion=aimc.fecha_contabilizacion AND imc.consecutivo=aimc.consecutivo_item AND
        mc.codigo_sucursal=imc.codigo_sucursal AND mc.documento_identidad_tercero=imc.documento_identidad_tercero AND
        mc.codigo_tipo_comprobante=imc.codigo_tipo_comprobante AND mc.numero_comprobante=imc.numero_comprobante AND
        mc.codigo_tipo_documento=imc.codigo_tipo_documento AND mc.consecutivo_documento=imc.consecutivo_documento AND
        mc.fecha_contabilizacion = mc.fecha_contabilizacion AND mc.estado != '2';


CREATE OR REPLACE ALGORITHM=MERGE VIEW job_totalizador_saldos_movimientos_contables AS
SELECT id_tercero,
       id_cuenta,
       id_consecutivo,
       consecutivo,
       id_documento,
       job_buscador_saldos_movimientos_contables.id_saldo,
       saldo,
       job_buscador_abonos_movimientos_contables.id AS id_abono,
       job_buscador_abonos_movimientos_contables.valor AS abono
FROM
    (job_buscador_saldos_movimientos_contables
    LEFT JOIN
    job_buscador_abonos_movimientos_contables
    ON job_buscador_saldos_movimientos_contables.id_saldo = job_buscador_abonos_movimientos_contables.id_saldo);


CREATE OR REPLACE ALGORITHM=MERGE VIEW job_seleccion_items_movimientos_contables AS
SELECT  CONCAT (mc.codigo_sucursal,'|',mc.documento_identidad_tercero,'|',mc.codigo_tipo_comprobante,'|',mc.numero_comprobante,'|',mc.codigo_tipo_documento,'|',mc.consecutivo_documento,'|',mc.fecha_contabilizacion) AS id_movimiento,
        CONCAT (imc.codigo_sucursal,'|',imc.documento_identidad_tercero,'|',imc.codigo_tipo_comprobante,'|',imc.numero_comprobante,'|',imc.codigo_tipo_documento,'|',imc.consecutivo_documento,'|',imc.fecha_contabilizacion,'|',imc.consecutivo) AS id,
        imc.codigo_plan_contable AS codigo_plan_contable,
        imc.sentido AS sentido
FROM    job_items_movimientos_contables AS imc, job_movimientos_contables AS mc
WHERE   mc.codigo_sucursal=imc.codigo_sucursal AND mc.documento_identidad_tercero=imc.documento_identidad_tercero AND
        mc.codigo_tipo_comprobante=imc.codigo_tipo_comprobante AND mc.numero_comprobante=imc.numero_comprobante AND
        mc.codigo_tipo_documento=imc.codigo_tipo_documento AND mc.consecutivo_documento=imc.consecutivo_documento AND
        mc.fecha_contabilizacion=imc.fecha_contabilizacion AND mc.estado='1';
        



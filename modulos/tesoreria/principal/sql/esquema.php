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

/*** Inserción de datos iniciales ***/
$registros["componentes"] = array(
    array(
        "id"              => "SUBMMOTE",
        "padre"           => "MENUTESO",
        "id_modulo"       => "TESORERIA",
        "orden"           => "3000",
        "carpeta"         => "movimientos",
        "archivo"         => "menu",
        "global"          => "0",
        "requiere_item"   => "0",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"               => "SUBMCRED",
        "padre"            => "MENUTESO",
        "id_modulo"        => "TESORERIA",
        "orden"            => "5000",
        "carpeta"          => "principal",
        "archivo"          => "NULL"
    ),
    array(
        "id"               => "SUBMDCTE",
        "padre"            => "MENUTESO",
        "id_modulo"        => "TESORERIA",
        "orden"            => "9000",
        "carpeta"          => "principal",
        "archivo"          => "NULL"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_movimientos_contables_consolidados AS
    (
    SELECT  imc.codigo_plan_contable AS codigo_contable,
            pc.descripcion AS descripcion_cuenta,
            pc.naturaleza_cuenta AS sentido_cuenta,
            pc.clase_cuenta AS clase_cuenta,
            pc.tipo_cuenta AS tipo_cuenta,
            imc.codigo_empresa_auxiliar AS codigo_empresa_auxiliar,
            imc.codigo_anexo_contable AS codigo_anexo_contable,
            imc.codigo_auxiliar_contable AS codigo_auxiliar_contable,
            imc.sentido AS sentido_movimiento,
            mc.documento_identidad_tercero AS documento_identidad_tercero,
            mc.estado AS estado,
            mc.fecha_contabilizacion AS fecha_contabilizacion,
            mc.codigo_tipo_documento AS codigo_tipo_documento,
            mc.consecutivo_documento AS numero_consecutivo,
            mc.codigo_tipo_comprobante AS codigo_tipo_comprobante,
            mc.numero_comprobante AS numero_comprobante,
            mc.codigo_sucursal AS codigo_sucursal_genera,
            mc.observaciones AS detalle,
            imc.valor AS valor,
            imc.valor_base1 AS valor_base1,
            imc.valor_base2 AS valor_base2,
            imc.consecutivo AS consecutivo_item,
            cd.id_tabla AS id_tabla,
            cd.llave_tabla AS llave_registro
    FROM    job_movimientos_contables AS mc, job_items_movimientos_contables AS imc,
            job_plan_contable AS pc, job_consecutivo_documentos AS cd
    WHERE   imc.codigo_plan_contable = pc.codigo_contable AND cd.codigo_sucursal = mc.codigo_sucursal
            AND cd.codigo_tipo_documento = mc.codigo_tipo_documento AND cd.fecha_registro = mc.fecha_contabilizacion
            AND cd.consecutivo = mc.consecutivo_documento AND mc.codigo_sucursal=imc.codigo_sucursal
            AND mc.documento_identidad_tercero=imc.documento_identidad_tercero AND mc.codigo_tipo_comprobante=imc.codigo_tipo_comprobante
            AND mc.numero_comprobante=imc.numero_comprobante AND mc.codigo_tipo_documento=imc.codigo_tipo_documento
            AND mc.consecutivo_documento=imc.consecutivo_documento AND mc.fecha_contabilizacion=imc.fecha_contabilizacion
    )

    UNION ALL

    (
    SELECT  mnm.codigo_contable AS codigo_contable,
            pc.descripcion AS descripcion_cuenta,
            pc.naturaleza_cuenta AS sentido_cuenta,
            pc.clase_cuenta AS clase_cuenta,
            pc.tipo_cuenta AS tipo_cuenta,
            mnm.codigo_empresa_auxiliar AS codigo_empresa_auxiliar,
            mnm.codigo_anexo_contable AS codigo_anexo_contable,
            mnm.codigo_auxiliar_contable AS codigo_auxiliar_contable,
            mnm.sentido AS sentido_movimiento,
            mnm.documento_identidad_empleado AS documento_identidad_tercero,
            1 AS estado,
            mnm.fecha_generacion AS fecha_contabilizacion,
            0 AS codigo_tipo_documento,
            0 AS numero_consecutivo,
            0 AS codigo_tipo_comprobante,
            0 AS numero_comprobante,
            mnm.codigo_sucursal AS codigo_sucursal_genera,
            '' AS detalle,
            mnm.valor_movimiento AS valor,
            0 AS valor_base1,
            0 AS valor_base2,
            0 AS consecutivo_item,
            0 AS id_tabla,
            '' AS llave_registro
    FROM    job_movimiento_novedades_manuales AS mnm, job_plan_contable AS pc
    WHERE   mnm.codigo_contable = pc.codigo_contable AND mnm.contabilizado = '1'
    )

    UNION ALL

    (
    SELECT  mpe.codigo_plan_contable AS codigo_contable,
            pc.descripcion AS descripcion_cuenta,
            pc.naturaleza_cuenta AS sentido_cuenta,
            pc.clase_cuenta AS clase_cuenta,
            pc.tipo_cuenta AS tipo_cuenta,
            0 AS codigo_empresa_auxiliar,
            '' AS codigo_anexo_contable,
            0 AS codigo_auxiliar_contable,
            mpe.sentido AS sentido_movimiento,
            cpe.documento_identidad_empleado AS documento_identidad_tercero,
            1 AS estado,
            cpe.fecha_generacion AS fecha_contabilizacion,
            cpe.codigo_tipo_documento AS codigo_tipo_documento,
            cpe.consecutivo_documento AS numero_consecutivo,
            0 AS codigo_tipo_comprobante,
            0 AS numero_comprobante,
            cpe.codigo_sucursal AS codigo_sucursal_genera,
            cpe.observaciones AS detalle,
            cpe.valor_total AS valor,
            0 AS valor_base1,
            0 AS valor_base2,
            cpe.consecutivo AS consecutivo_item,
            cd.id_tabla AS id_tabla,
            cd.llave_tabla AS llave_registro
    FROM    job_control_prestamos_empleados AS cpe, job_movimientos_prestamos_empleados AS mpe, job_plan_contable AS pc, job_consecutivo_documentos AS cd
    WHERE   mpe.codigo_plan_contable = pc.codigo_contable AND cd.codigo_sucursal = cpe.codigo_sucursal
            AND cd.codigo_tipo_documento = cpe.codigo_tipo_documento AND cd.documento_identidad_tercero = cpe.documento_identidad_empleado
            AND cd.fecha_registro = cpe.fecha_generacion AND cd.consecutivo = cpe.consecutivo_documento
            AND cpe.documento_identidad_empleado = mpe.documento_identidad_empleado AND cpe.consecutivo = mpe.consecutivo
            AND cpe.fecha_generacion = mpe.fecha_generacion AND cpe.concepto_prestamo = mpe.concepto_prestamo
    )

    UNION ALL

    (
    SELECT  mcpe.codigo_contable AS codigo_contable,
            pc.descripcion AS descripcion_cuenta,
            pc.naturaleza_cuenta AS sentido_cuenta,
            pc.clase_cuenta AS clase_cuenta,
            pc.tipo_cuenta AS tipo_cuenta,
            mcpe.codigo_empresa_auxiliar AS codigo_empresa_auxiliar,
            mcpe.codigo_anexo_contable AS codigo_anexo_contable,
            mcpe.codigo_auxiliar_contable AS codigo_auxiliar_contable,
            mcpe.sentido AS sentido_movimiento,
            mcpe.documento_identidad_empleado AS documento_identidad_tercero,
            1 AS estado,
            mcpe.fecha_generacion AS fecha_contabilizacion,
            0 AS codigo_tipo_documento,
            0 AS numero_consecutivo,
            0 AS codigo_tipo_comprobante,
            0 AS numero_comprobante,
            mcpe.codigo_sucursal AS codigo_sucursal_genera,
            '' AS detalle,
            mcpe.valor_descuento AS valor,
            0 AS valor_base1,
            0 AS valor_base2,
            mcpe.consecutivo_fecha_pago AS consecutivo_item,
            0 AS id_tabla,
            '' AS llave_registro
    FROM    job_movimiento_control_prestamos_empleados AS mcpe, job_plan_contable AS pc
    WHERE   mcpe.codigo_contable = pc.codigo_contable AND mcpe.contabilizado = '1'
    )

    UNION ALL

    (
    SELECT  ms.codigo_contable AS codigo_contable,
            pc.descripcion AS descripcion_cuenta,
            pc.naturaleza_cuenta AS sentido_cuenta,
            pc.clase_cuenta AS clase_cuenta,
            pc.tipo_cuenta AS tipo_cuenta,
            ms.codigo_empresa_auxiliar AS codigo_empresa_auxiliar,
            ms.codigo_anexo_contable AS codigo_anexo_contable,
            ms.codigo_auxiliar_contable AS codigo_auxiliar_contable,
            ms.sentido AS sentido_movimiento,
            ms.documento_identidad_empleado AS documento_identidad_tercero,
            1 AS estado,
            ms.fecha_pago_planilla AS fecha_contabilizacion,
            0 AS codigo_tipo_documento,
            0 AS numero_consecutivo,
            0 AS codigo_tipo_comprobante,
            0 AS numero_comprobante,
            ms.codigo_sucursal AS codigo_sucursal_genera,
            '' AS detalle,
            ms.valor_movimiento AS valor,
            0 AS valor_base1,
            0 AS valor_base2,
            0 AS consecutivo_item,
            0 AS id_tabla,
            '' AS llave_registro
    FROM    job_movimientos_salud AS ms, job_plan_contable AS pc
    WHERE   ms.codigo_contable = pc.codigo_contable AND ms.contabilizado = '1'
    )

    UNION ALL

    (
    SELECT  mp.codigo_contable AS codigo_contable,
            pc.descripcion AS descripcion_cuenta,
            pc.naturaleza_cuenta AS sentido_cuenta,
            pc.clase_cuenta AS clase_cuenta,
            pc.tipo_cuenta AS tipo_cuenta,
            mp.codigo_empresa_auxiliar AS codigo_empresa_auxiliar,
            mp.codigo_anexo_contable AS codigo_anexo_contable,
            mp.codigo_auxiliar_contable AS codigo_auxiliar_contable,
            mp.sentido AS sentido_movimiento,
            mp.documento_identidad_empleado AS documento_identidad_tercero,
            1 AS estado,
            mp.fecha_pago_planilla AS fecha_contabilizacion,
            0 AS codigo_tipo_documento,
            0 AS numero_consecutivo,
            0 AS codigo_tipo_comprobante,
            0 AS numero_comprobante,
            mp.codigo_sucursal AS codigo_sucursal_genera,
            '' AS detalle,
            mp.valor_movimiento AS valor,
            0 AS valor_base1,
            0 AS valor_base2,
            0 AS consecutivo_item,
            0 AS id_tabla,
            '' AS llave_registro
    FROM    job_movimientos_pension AS mp, job_plan_contable AS pc
    WHERE   mp.codigo_contable = pc.codigo_contable AND mp.contabilizado = '1'
    )

    UNION ALL

    (
    SELECT  ms.codigo_contable AS codigo_contable,
            pc.descripcion AS descripcion_cuenta,
            pc.naturaleza_cuenta AS sentido_cuenta,
            pc.clase_cuenta AS clase_cuenta,
            pc.tipo_cuenta AS tipo_cuenta,
            ms.codigo_empresa_auxiliar AS codigo_empresa_auxiliar,
            ms.codigo_anexo_contable AS codigo_anexo_contable,
            ms.codigo_auxiliar_contable AS codigo_auxiliar_contable,
            ms.sentido AS sentido_movimiento,
            ms.documento_identidad_empleado AS documento_identidad_tercero,
            1 AS estado,
            ms.fecha_pago_planilla AS fecha_contabilizacion,
            0 AS codigo_tipo_documento,
            0 AS numero_consecutivo,
            0 AS codigo_tipo_comsrobante,
            0 AS numero_comprobante,
            ms.codigo_sucursal AS codigo_sucursal_genera,
            '' AS detalle,
            ms.valor_movimiento AS valor,
            0 AS valor_base1,
            0 AS valor_base2,
            0 AS consecutivo_item,
            0 AS id_tabla,
            '' AS llave_registro
    FROM    job_movimientos_salarios AS ms, job_plan_contable AS pc
    WHERE   ms.codigo_contable = pc.codigo_contable AND ms.contabilizado = '1'
    )

    UNION ALL

    (
    SELECT  mat.codigo_contable AS codigo_contable,
            pc.descripcion AS descripcion_cuenta,
            pc.naturaleza_cuenta AS sentido_cuenta,
            pc.clase_cuenta AS clase_cuenta,
            pc.tipo_cuenta AS tipo_cuenta,
            mat.codigo_empresa_auxiliar AS codigo_empresa_auxiliar,
            mat.codigo_anexo_contable AS codigo_anexo_contable,
            mat.codigo_auxiliar_contable AS codigo_auxiliar_contable,
            mat.sentido AS sentido_movimiento,
            mat.documento_identidad_empleado AS documento_identidad_tercero,
            1 AS estado,
            mat.fecha_pago_planilla AS fecha_contabilizacion,
            0 AS codigo_tipo_documento,
            0 AS numero_consecutivo,
            0 AS codigo_tipo_comatrobante,
            0 AS numero_comprobante,
            mat.codigo_sucursal AS codigo_sucursal_genera,
            '' AS detalle,
            mat.valor_movimiento AS valor,
            0 AS valor_base1,
            0 AS valor_base2,
            0 AS consecutivo_item,
            0 AS id_tabla,
            '' AS llave_registro
    FROM    job_movimientos_auxilio_transporte AS mat, job_plan_contable AS pc
    WHERE   mat.codigo_contable = pc.codigo_contable AND mat.contabilizado = '1'
    )

    UNION ALL

    (
    SELECT  ri.codigo_contable AS codigo_contable,
            pc.descripcion AS descripcion_cuenta,
            pc.naturaleza_cuenta AS sentido_cuenta,
            pc.clase_cuenta AS clase_cuenta,
            pc.tipo_cuenta AS tipo_cuenta,
            ri.codigo_empresa_auxiliar AS codigo_empresa_auxiliar,
            ri.codigo_anexo_contable AS codigo_anexo_contable,
            ri.codigo_auxiliar_contable AS codigo_auxiliar_contable,
            ri.sentido AS sentido_movimiento,
            ri.documento_identidad_empleado AS documento_identidad_tercero,
            1 AS estado,
            ri.fecha_pago_planilla AS fecha_contabilizacion,
            0 AS codigo_tipo_documento,
            0 AS numero_consecutivo,
            0 AS codigo_tipo_corirobante,
            0 AS numero_comprobante,
            ri.codigo_sucursal AS codigo_sucursal_genera,
            '' AS detalle,
            ri.valor_movimiento AS valor,
            0 AS valor_base1,
            0 AS valor_base2,
            0 AS consecutivo_item,
            0 AS id_tabla,
            '' AS llave_registro
    FROM    job_reporte_incapacidades AS ri, job_plan_contable AS pc
    WHERE   ri.codigo_contable = pc.codigo_contable AND ri.contabilizado = '1'
    )

    UNION ALL

    (
    SELECT  mtl.codigo_contable AS codigo_contable,
            pc.descripcion AS descripcion_cuenta,
            pc.naturaleza_cuenta AS sentido_cuenta,
            pc.clase_cuenta AS clase_cuenta,
            pc.tipo_cuenta AS tipo_cuenta,
            mtl.codigo_empresa_auxiliar AS codigo_empresa_auxiliar,
            mtl.codigo_anexo_contable AS codigo_anexo_contable,
            mtl.codigo_auxiliar_contable AS codigo_auxiliar_contable,
            mtl.sentido AS sentido_movimiento,
            mtl.documento_identidad_empleado AS documento_identidad_tercero,
            1 AS estado,
            mtl.fecha_pago_planilla AS fecha_contabilizacion,
            0 AS codigo_tipo_documento,
            0 AS numero_consecutivo,
            0 AS codigo_tipo_comprobante,
            0 AS numero_comprobante,
            mtl.codigo_sucursal AS codigo_sucursal_genera,
            '' AS detalle,
            mtl.valor_movimiento AS valor,
            0 AS valor_base1,
            0 AS valor_base2,
            mtl.consecutivo AS consecutivo_item,
            0 AS id_tabla,
            '' AS llave_registro
    FROM    job_movimiento_tiempos_laborados AS mtl, job_plan_contable AS pc
    WHERE   mtl.codigo_contable = pc.codigo_contable AND mtl.contabilizado = '1'
    )

    UNION ALL

    (
    SELECT  mtnld.codigo_contable AS codigo_contable,
            pc.descripcion AS descripcion_cuenta,
            pc.naturaleza_cuenta AS sentido_cuenta,
            pc.clase_cuenta AS clase_cuenta,
            pc.tipo_cuenta AS tipo_cuenta,
            mtnld.codigo_empresa_auxiliar AS codigo_empresa_auxiliar,
            mtnld.codigo_anexo_contable AS codigo_anexo_contable,
            mtnld.codigo_auxiliar_contable AS codigo_auxiliar_contable,
            mtnld.sentido AS sentido_movimiento,
            mtnld.documento_identidad_empleado AS documento_identidad_tercero,
            1 AS estado,
            mtnld.fecha_pago_planilla AS fecha_contabilizacion,
            0 AS codigo_tipo_documento,
            0 AS numero_consecutivo,
            0 AS codigo_tipo_comprobante,
            0 AS numero_comprobante,
            mtnld.codigo_sucursal AS codigo_sucursal_genera,
            '' AS detalle,
            mtnld.valor_dia AS valor,
            0 AS valor_base1,
            0 AS valor_base2,
            0 AS consecutivo_item,
            0 AS id_tabla,
            '' AS llave_registro
    FROM    job_movimiento_tiempos_no_laborados_dias AS mtnld, job_plan_contable AS pc
    WHERE   mtnld.codigo_contable = pc.codigo_contable AND mtnld.contabilizado = '1'
    )

    UNION ALL

    (
    SELECT  mtnlh.codigo_contable AS codigo_contable,
            pc.descripcion AS descripcion_cuenta,
            pc.naturaleza_cuenta AS sentido_cuenta,
            pc.clase_cuenta AS clase_cuenta,
            pc.tipo_cuenta AS tipo_cuenta,
            mtnlh.codigo_empresa_auxiliar AS codigo_empresa_auxiliar,
            mtnlh.codigo_anexo_contable AS codigo_anexo_contable,
            mtnlh.codigo_auxiliar_contable AS codigo_auxiliar_contable,
            mtnlh.sentido AS sentido_movimiento,
            mtnlh.documento_identidad_empleado AS documento_identidad_tercero,
            1 AS estado,
            mtnlh.fecha_pago_planilla AS fecha_contabilizacion,
            0 AS codigo_tipo_documento,
            0 AS numero_consecutivo,
            0 AS codigo_tipo_comprobante,
            0 AS numero_comprobante,
            mtnlh.codigo_sucursal AS codigo_sucursal_genera,
            '' AS detalle,
            mtnlh.valor_movimiento AS valor,
            0 AS valor_base1,
            0 AS valor_base2,
            mtnlh.consecutivo AS consecutivo_item,
            0 AS id_tabla,
            '' AS llave_registro
    FROM    job_movimiento_tiempos_no_laborados_horas AS mtnlh, job_plan_contable AS pc
    WHERE   mtnlh.codigo_contable = pc.codigo_contable AND mtnlh.contabilizado = '1'
    )

    UNION ALL

    (
    SELECT  mccd.codigo_contable AS codigo_contable,
            pc.descripcion AS descripcion_cuenta,
            pc.naturaleza_cuenta AS sentido_cuenta,
            pc.clase_cuenta AS clase_cuenta,
            pc.tipo_cuenta AS tipo_cuenta,
            0 AS codigo_empresa_auxiliar,
            '' AS codigo_anexo_contable,
            0 AS codigo_auxiliar_contable,
            mccd.sentido AS sentido_movimiento,
            mccd.documento_identidad_empleado AS documento_identidad_tercero,
            1 AS estado,
            mccd.fecha_pago_planilla AS fecha_contabilizacion,
            0 AS codigo_tipo_documento,
            0 AS numero_consecutivo,
            0 AS codigo_tipo_comprobante,
            0 AS numero_comprobante,
            mccd.codigo_sucursal AS codigo_sucursal_genera,
            '' AS detalle,
            mccd.valor_movimiento AS valor,
            0 AS valor_base1,
            0 AS valor_base2,
            0 AS consecutivo_item,
            0 AS id_tabla,
            '' AS llave_registro
    FROM    job_movimiento_cuenta_por_cobrar_descuento AS mccd, job_plan_contable AS pc
    WHERE   mccd.codigo_contable = pc.codigo_contable AND mccd.contabilizado = '1'
    )

    UNION ALL

    (
    SELECT  mcce.codigo_contable AS codigo_contable,
            pc.descripcion AS descripcion_cuenta,
            pc.naturaleza_cuenta AS sentido_cuenta,
            pc.clase_cuenta AS clase_cuenta,
            pc.tipo_cuenta AS tipo_cuenta,
            0 AS codigo_empresa_auxiliar,
            '' AS codigo_anexo_contable,
            0 AS codigo_auxiliar_contable,
            mcce.sentido AS sentido_movimiento,
            mcce.documento_identidad_empleado AS documento_identidad_tercero,
            1 AS estado,
            mcce.fecha_generacion AS fecha_contabilizacion,
            0 AS codigo_tipo_documento,
            0 AS numero_consecutivo,
            0 AS codigo_tipo_comprobante,
            0 AS numero_comprobante,
            mcce.codigo_sucursal AS codigo_sucursal_genera,
            '' AS detalle,
            mcce.valor_movimiento AS valor,
            0 AS valor_base1,
            0 AS valor_base2,
            0 AS consecutivo_item,
            0 AS id_tabla,
            '' AS llave_registro
    FROM    job_movimiento_cuenta_por_cobrar_empleado AS mcce, job_plan_contable AS pc
    WHERE   mcce.codigo_contable = pc.codigo_contable AND mcce.contabilizado = '1'
    )

    UNION ALL

    (
    SELECT  mcpt.codigo_contable AS codigo_contable,
            pc.descripcion AS descripcion_cuenta,
            pc.naturaleza_cuenta AS sentido_cuenta,
            pc.clase_cuenta AS clase_cuenta,
            pc.tipo_cuenta AS tipo_cuenta,
            0 AS codigo_empresa_auxiliar,
            '' AS codigo_anexo_contable,
            0 AS codigo_auxiliar_contable,
            mcpt.sentido AS sentido_movimiento,
            mcpt.documento_identidad_tercero AS documento_identidad_tercero,
            1 AS estado,
            mcpt.fecha_generacion AS fecha_contabilizacion,
            0 AS codigo_tipo_documento,
            0 AS numero_consecutivo,
            0 AS codigo_tipo_comprobante,
            0 AS numero_comprobante,
            mcpt.codigo_sucursal AS codigo_sucursal_genera,
            '' AS detalle,
            mcpt.valor_movimiento AS valor,
            0 AS valor_base1,
            0 AS valor_base2,
            0 AS consecutivo_item,
            0 AS id_tabla,
            '' AS llave_registro
    FROM    job_movimiento_cuenta_por_pagar_tercero AS mcpt, job_plan_contable AS pc
    WHERE   mcpt.codigo_contable = pc.codigo_contable AND mcpt.contabilizado = '1'
    )

    UNION ALL

    (
    SELECT  mcpt.codigo_contable AS codigo_contable,
            pc.descripcion AS descripcion_cuenta,
            pc.naturaleza_cuenta AS sentido_cuenta,
            pc.clase_cuenta AS clase_cuenta,
            pc.tipo_cuenta AS tipo_cuenta,
            0 AS codigo_empresa_auxiliar,
            '' AS codigo_anexo_contable,
            0 AS codigo_auxiliar_contable,
            mcpt.sentido AS sentido_movimiento,
            mcpt.documento_identidad_tercero AS documento_identidad_tercero,
            1 AS estado,
            mcpt.fecha_generacion AS fecha_contabilizacion,
            0 AS codigo_tipo_documento,
            0 AS numero_consecutivo,
            0 AS codigo_tipo_comprobante,
            0 AS numero_comprobante,
            mcpt.codigo_sucursal AS codigo_sucursal_genera,
            '' AS detalle,
            mcpt.valor_movimiento AS valor,
            0 AS valor_base1,
            0 AS valor_base2,
            0 AS consecutivo_item,
            0 AS id_tabla,
            '' AS llave_registro
    FROM    job_movimiento_cuenta_pago_tercero AS mcpt, job_plan_contable AS pc
    WHERE   mcpt.codigo_contable = pc.codigo_contable AND mcpt.contabilizado = '1'
    )

    UNION ALL

    (
    SELECT  fppn.codigo_plan_contable AS codigo_contable,
            pc.descripcion AS descripcion_cuenta,
            pc.naturaleza_cuenta AS sentido_cuenta,
            pc.clase_cuenta AS clase_cuenta,
            pc.tipo_cuenta AS tipo_cuenta,
            fppn.codigo_empresa_auxiliar AS codigo_empresa_auxiliar,
            fppn.codigo_anexo_contable AS codigo_anexo_contable,
            fppn.codigo_auxiliar_contable AS codigo_auxiliar_contable,
            fppn.sentido AS sentido_movimiento,
            fppn.documento_identidad_tercero_consecutivo_documento AS documento_identidad_tercero,
            1 AS estado,
            fppn.fecha_pago_planilla AS fecha_contabilizacion,
            fppn.codigo_tipo_documento_consecutivo_documento AS codigo_tipo_documento,
            fppn.consecutivo_documento AS numero_consecutivo,
            0 AS codigo_tipo_comprobante,
            0 AS numero_comprobante,
            fpp.codigo_sucursal_genera AS codigo_sucursal_genera,
            '' AS detalle,
            fppn.valor_movimiento AS valor,
            0 AS valor_base1,
            0 AS valor_base2,
            0 AS consecutivo_item,
            0 AS id_tabla,
            '' AS llave_registro
    FROM    job_forma_pago_planillas_nomina AS fppn, job_forma_pago_planillas AS fpp, job_plan_contable AS pc
    WHERE   fppn.codigo_plan_contable = pc.codigo_contable AND fpp.pagado = '1'
            AND fpp.forma_pago = fppn.forma_pago AND fpp.ano_generacion = fppn.ano_generacion
            AND fpp.mes_generacion = fppn.mes_generacion AND fpp.codigo_planilla = fppn.codigo_planilla
            AND fpp.periodo_pago = fppn.periodo_pago AND fpp.fecha_pago_planilla = fppn.fecha_pago_planilla
            AND fpp.codigo_sucursal_recibe = fppn.codigo_sucursal
    )

    UNION ALL

    (
    SELECT  fppe.codigo_plan_contable AS codigo_contable,
            pc.descripcion AS descripcion_cuenta,
            pc.naturaleza_cuenta AS sentido_cuenta,
            pc.clase_cuenta AS clase_cuenta,
            pc.tipo_cuenta AS tipo_cuenta,
            fppe.codigo_empresa_auxiliar AS codigo_empresa_auxiliar,
            fppe.codigo_anexo_contable AS codigo_anexo_contable,
            fppe.codigo_auxiliar_contable AS codigo_auxiliar_contable,
            fppe.sentido AS sentido_movimiento,
            fppe.documento_identidad_tercero_consecutivo_documento AS documento_identidad_tercero,
            1 AS estado,
            fppe.fecha_pago_planilla AS fecha_contabilizacion,
            fppe.codigo_tipo_documento_consecutivo_documento AS codigo_tipo_documento,
            fppe.consecutivo_documento AS numero_consecutivo,
            0 AS codigo_tipo_comprobante,
            0 AS numero_comprobante,
            fpp.codigo_sucursal_genera AS codigo_sucursal_genera,
            '' AS detalle,
            fppe.valor_movimiento AS valor,
            0 AS valor_base1,
            0 AS valor_base2,
            0 AS consecutivo_item,
            0 AS id_tabla,
            '' AS llave_registro
    FROM    job_forma_pago_planillas_efectivo AS fppe, job_forma_pago_planillas AS fpp, job_plan_contable AS pc
    WHERE   fppe.codigo_plan_contable = pc.codigo_contable AND fpp.pagado = '1'
            AND fpp.forma_pago = fppe.forma_pago AND fpp.ano_generacion = fppe.ano_generacion
            AND fpp.mes_generacion = fppe.mes_generacion AND fpp.codigo_planilla = fppe.codigo_planilla
            AND fpp.periodo_pago = fppe.periodo_pago AND fpp.fecha_pago_planilla = fppe.fecha_pago_planilla
            AND fpp.codigo_sucursal_recibe = fppe.codigo_sucursal
    )

    UNION ALL

    (
    SELECT  fpps.codigo_plan_contable AS codigo_contable,
            pc.descripcion AS descripcion_cuenta,
            pc.naturaleza_cuenta AS sentido_cuenta,
            pc.clase_cuenta AS clase_cuenta,
            pc.tipo_cuenta AS tipo_cuenta,
            fpps.codigo_empresa_auxiliar AS codigo_empresa_auxiliar,
            fpps.codigo_anexo_contable AS codigo_anexo_contable,
            fpps.codigo_auxiliar_contable AS codigo_auxiliar_contable,
            fpps.sentido AS sentido_movimiento,
            fpps.documento_identidad_tercero_consecutivo_documento AS documento_identidad_tercero,
            1 AS estado,
            fpps.fecha_pago_planilla AS fecha_contabilizacion,
            fpps.codigo_tipo_documento_consecutivo_documento AS codigo_tipo_documento,
            fpps.consecutivo_documento AS numero_consecutivo,
            0 AS codigo_tipo_comprobante,
            0 AS numero_comprobante,
            fpp.codigo_sucursal_genera AS codigo_sucursal_genera,
            '' AS detalle,
            fpps.valor_movimiento AS valor,
            0 AS valor_base1,
            0 AS valor_base2,
            0 AS consecutivo_item,
            0 AS id_tabla,
            '' AS llave_registro
    FROM    job_forma_pago_planillas_sucursal AS fpps, job_forma_pago_planillas AS fpp, job_plan_contable AS pc
    WHERE   fpps.codigo_plan_contable = pc.codigo_contable AND fpp.pagado = '1'
            AND fpp.forma_pago = fpps.forma_pago AND fpp.ano_generacion = fpps.ano_generacion
            AND fpp.mes_generacion = fpps.mes_generacion AND fpp.codigo_planilla = fpps.codigo_planilla
            AND fpp.periodo_pago = fpps.periodo_pago AND fpp.fecha_pago_planilla = fpps.fecha_pago_planilla
            AND fpp.codigo_sucursal_recibe = fpps.codigo_sucursal
    )

    UNION ALL

    (
    SELECT  fppe.codigo_plan_contable AS codigo_contable,
            pc.descripcion AS descripcion_cuenta,
            pc.naturaleza_cuenta AS sentido_cuenta,
            pc.clase_cuenta AS clase_cuenta,
            pc.tipo_cuenta AS tipo_cuenta,
            fppe.codigo_empresa_auxiliar AS codigo_empresa_auxiliar,
            fppe.codigo_anexo_contable AS codigo_anexo_contable,
            fppe.codigo_auxiliar_contable AS codigo_auxiliar_contable,
            fppe.sentido AS sentido_movimiento,
            fppe.documento_identidad_tercero_consecutivo_documento AS documento_identidad_tercero,
            1 AS estado,
            fppe.fecha_pago_planilla AS fecha_contabilizacion,
            fppe.codigo_tipo_documento_consecutivo_documento AS codigo_tipo_documento,
            fppe.consecutivo_documento AS numero_consecutivo,
            0 AS codigo_tipo_comprobante,
            0 AS numero_comprobante,
            fpp.codigo_sucursal_genera AS codigo_sucursal_genera,
            '' AS detalle,
            fppe.valor_movimiento AS valor,
            0 AS valor_base1,
            0 AS valor_base2,
            0 AS consecutivo_item,
            0 AS id_tabla,
            '' AS llave_registro
    FROM    job_forma_pago_planillas_empleado AS fppe, job_forma_pago_planillas AS fpp, job_plan_contable AS pc
    WHERE   fppe.codigo_plan_contable = pc.codigo_contable AND fpp.pagado = '1'
            AND fpp.ano_generacion = fppe.ano_generacion
            AND fpp.mes_generacion = fppe.mes_generacion AND fpp.codigo_planilla = fppe.codigo_planilla
            AND fpp.periodo_pago = fppe.periodo_pago AND fpp.fecha_pago_planilla = fppe.fecha_pago_planilla
            AND fpp.codigo_sucursal_recibe = fppe.codigo_sucursal
    )

    UNION ALL

    (
    SELECT  cnpse.codigo_contable AS codigo_contable,
            pc.descripcion AS descripcion_cuenta,
            pc.naturaleza_cuenta AS sentido_cuenta,
            pc.clase_cuenta AS clase_cuenta,
            pc.tipo_cuenta AS tipo_cuenta,
            cnpse.codigo_empresa_auxiliar AS codigo_empresa_auxiliar,
            cnpse.codigo_anexo_contable AS codigo_anexo_contable,
            cnpse.codigo_auxiliar_contable AS codigo_auxiliar_contable,
            cnpse.sentido AS sentido_movimiento,
            cnpse.documento_identidad_empleado AS documento_identidad_tercero,
            1 AS estado,
            cnpse.fecha_pago_planilla AS fecha_contabilizacion,
            0 AS codigo_tipo_documento,
            0 AS numero_consecutivo,
            0 AS codigo_tipo_comprobante,
            0 AS numero_comprobante,
            cnpse.codigo_sucursal AS codigo_sucursal_genera,
            '' AS detalle,
            cnpse.valor_movimiento AS valor,
            0 AS valor_base1,
            0 AS valor_base2,
            0 AS consecutivo_item,
            0 AS id_tabla,
            '' AS llave_registro
    FROM    job_cancelacion_nomina_por_pagar_salud_empleado AS cnpse, job_plan_contable AS pc
    WHERE   cnpse.codigo_contable = pc.codigo_contable
    )

    UNION ALL

    (
    SELECT  cpse.codigo_contable AS codigo_contable,
            pc.descripcion AS descripcion_cuenta,
            pc.naturaleza_cuenta AS sentido_cuenta,
            pc.clase_cuenta AS clase_cuenta,
            pc.tipo_cuenta AS tipo_cuenta,
            cpse.codigo_empresa_auxiliar AS codigo_empresa_auxiliar,
            cpse.codigo_anexo_contable AS codigo_anexo_contable,
            cpse.codigo_auxiliar_contable AS codigo_auxiliar_contable,
            cpse.sentido AS sentido_movimiento,
            cpse.documento_identidad_entidad AS documento_identidad_tercero,
            1 AS estado,
            cpse.fecha_pago_planilla AS fecha_contabilizacion,
            0 AS codigo_tipo_documento,
            0 AS numero_consecutivo,
            0 AS codigo_tipo_comprobante,
            0 AS numero_comprobante,
            cpse.codigo_sucursal AS codigo_sucursal_genera,
            '' AS detalle,
            cpse.valor_movimiento AS valor,
            0 AS valor_base1,
            0 AS valor_base2,
            0 AS consecutivo_item,
            0 AS id_tabla,
            '' AS llave_registro
    FROM    job_cuenta_por_pagar_salud_entidad AS cpse, job_plan_contable AS pc
    WHERE   cpse.codigo_contable = pc.codigo_contable
    )

    UNION ALL

    (
    SELECT  cnppe.codigo_contable AS codigo_contable,
            pc.descripcion AS descripcion_cuenta,
            pc.naturaleza_cuenta AS sentido_cuenta,
            pc.clase_cuenta AS clase_cuenta,
            pc.tipo_cuenta AS tipo_cuenta,
            cnppe.codigo_empresa_auxiliar AS codigo_empresa_auxiliar,
            cnppe.codigo_anexo_contable AS codigo_anexo_contable,
            cnppe.codigo_auxiliar_contable AS codigo_auxiliar_contable,
            cnppe.sentido AS sentido_movimiento,
            cnppe.documento_identidad_empleado AS documento_identidad_tercero,
            1 AS estado,
            cnppe.fecha_pago_planilla AS fecha_contabilizacion,
            0 AS codigo_tipo_documento,
            0 AS numero_consecutivo,
            0 AS codigo_tipo_comprobante,
            0 AS numero_comprobante,
            cnppe.codigo_sucursal AS codigo_sucursal_genera,
            '' AS detalle,
            cnppe.valor_movimiento AS valor,
            0 AS valor_base1,
            0 AS valor_base2,
            0 AS consecutivo_item,
            0 AS id_tabla,
            '' AS llave_registro
    FROM    job_cancelacion_nomina_por_pagar_pension_empleado AS cnppe, job_plan_contable AS pc
    WHERE   cnppe.codigo_contable = pc.codigo_contable
    )

    UNION ALL

    (
    SELECT  cppe.codigo_contable AS codigo_contable,
            pc.descripcion AS descripcion_cuenta,
            pc.naturaleza_cuenta AS sentido_cuenta,
            pc.clase_cuenta AS clase_cuenta,
            pc.tipo_cuenta AS tipo_cuenta,
            cppe.codigo_empresa_auxiliar AS codigo_empresa_auxiliar,
            cppe.codigo_anexo_contable AS codigo_anexo_contable,
            cppe.codigo_auxiliar_contable AS codigo_auxiliar_contable,
            cppe.sentido AS sentido_movimiento,
            cppe.documento_identidad_entidad AS documento_identidad_tercero,
            1 AS estado,
            cppe.fecha_pago_planilla AS fecha_contabilizacion,
            0 AS codigo_tipo_documento,
            0 AS numero_consecutivo,
            0 AS codigo_tipo_comprobante,
            0 AS numero_comprobante,
            cppe.codigo_sucursal AS codigo_sucursal_genera,
            '' AS detalle,
            cppe.valor_movimiento AS valor,
            0 AS valor_base1,
            0 AS valor_base2,
            0 AS consecutivo_item,
            0 AS id_tabla,
            '' AS llave_registro
    FROM    job_cuenta_por_pagar_pension_entidad AS cppe, job_plan_contable AS pc
    WHERE   cppe.codigo_contable = pc.codigo_contable
    )"
    )
);
?>

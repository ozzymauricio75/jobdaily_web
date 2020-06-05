UNION
(
    SELECT
                8 AS tabla,
                job_movimiento_novedades_manuales.ano_generacion AS ano_generacion,
                job_movimiento_novedades_manuales.mes_generacion AS mes_generacion,
                job_movimiento_novedades_manuales.codigo_planilla AS codigo_planilla,
                job_movimiento_novedades_manuales.periodo_pago AS periodo_pago,
                job_movimiento_novedades_manuales.codigo_transaccion_contable AS codigo_transaccion_contable,
                job_transacciones_contables_empleado.columna_planilla AS columna_planilla,
                0 AS codigo_transaccion_tiempo,
                job_movimiento_novedades_manuales.documento_identidad_empleado AS documento_identidad_empleado,
                job_movimiento_novedades_manuales.codigo_contable AS codigo_contable,
                job_movimiento_novedades_manuales.sentido AS sentido,
                job_movimiento_novedades_manuales.codigo_empresa_auxiliar AS codigo_empresa_auxliliar,
                job_movimiento_novedades_manuales.codigo_anexo_contable AS codigo_anexo_contable,
                job_movimiento_novedades_manuales.codigo_auxiliar_contable AS codigo_auxiliar_contable,
                job_movimiento_novedades_manuales.valor_noveda AS valor_movimiento,
                0 AS dias_trabajados,
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
                ) AS nombre_empleado,
                job_departamentos_empresa.nombre AS departamento_empresa,
                job_sucursal_contrato_empleados.salario_mensual AS salario_empleado,
                job_sucursal_contrato_empleados.codigo_empresa AS codigo_empresa,
                job_sucursal_contrato_empleados.codigo_sucursal AS codigo_sucursal,
                job_sucursal_contrato_empleados.fecha_ingreso AS fecha_ingreso_empresa,
                job_sucursal_contrato_empleados.fecha_ingreso_sucursal AS fecha_ingreso_sucursal,
                job_movimiento_novedades_manuales.contabilizado AS contabilizado,
                0 AS consecutivo,
                0 AS fecha_incapacidad
            FROM
                job_movimiento_novedades_manuales,
                job_departamento_seccion_contrato_empleado,
                job_departamentos_empresa,
                job_sucursal_contrato_empleados,
                job_terceros,
                job_transacciones_contables_empleado
            WHERE
                job_movimiento_novedades_manuales.documento_identidad_empleado = job_terceros.documento_identidad AND
                job_movimiento_novedades_manuales.codigo_empresa = job_sucursal_contrato_empleados.codigo_empresa AND
                job_movimiento_novedades_manuales.documento_identidad_empleado = job_sucursal_contrato_empleados.documento_identidad_empleado AND
                job_movimiento_novedades_manuales.fecha_ingreso = job_sucursal_contrato_empleados.fecha_ingreso AND
                job_movimiento_novedades_manuales.codigo_sucursal = job_sucursal_contrato_empleados.codigo_sucursal AND
                job_movimiento_novedades_manuales.fecha_ingreso_sucursal = job_sucursal_contrato_empleados.fecha_ingreso_sucursal AND
                job_departamento_seccion_contrato_empleado.codigo_empresa = job_sucursal_contrato_empleados.codigo_empresa AND
                job_departamento_seccion_contrato_empleado.documento_identidad_empleado = job_sucursal_contrato_empleados.documento_identidad_empleado AND
                job_departamento_seccion_contrato_empleado.fecha_ingreso = job_sucursal_contrato_empleados.fecha_ingreso AND
                job_departamento_seccion_contrato_empleado.codigo_sucursal = job_sucursal_contrato_empleados.codigo_sucursal AND
                job_departamento_seccion_contrato_empleado.fecha_ingreso_sucursal = job_sucursal_contrato_empleados.fecha_ingreso_sucursal AND
                job_departamento_seccion_contrato_empleado.codigo_departamento_empresa = job_departamentos_empresa.codigo AND
                job_movimiento_novedades_manuales.codigo_transaccion_contable = job_transacciones_contables_empleado.codigo
)
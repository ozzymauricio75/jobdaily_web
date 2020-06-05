   CREATE OR REPLACE ALGORITHM = MERGE VIEW job_vista_control_contrato  AS
         SELECT  -- llave de control de prestamos --
                P.documento_identidad_empleado    AS documento_identidad_empleado,
                P.consecutivo                     AS consecutivo,
                P.fecha_generacion                AS fecha_generacion,
                P.concepto_prestamo               AS concepto_prestamo,
                -- datos de control de prestamo  --
                P.codigo_empresa                  AS codigo_empresa,
                P.fecha_ingreso                   AS fecha_ingreso,
                P.codigo_sucursal                 AS codigo_sucursal,
                P.fecha_ingreso_sucursal          AS fecha_ingreso_sucursal,
                P.codigo_tipo_documento           AS codigo_tipo_documento,
                P.codigo_transaccion_contable_descontar  AS codigo_transaccion_contable_descontar,
                P.codigo_transaccion_contable_cobrar     AS transaccion_contable_cobrar,
                P.valor_total                     AS valor_total,
                P.forma_pago                      AS forma_pago,
                P.codigo_planilla                 AS codigo_planilla,

                IF(
                    (
                        SELECT  SUM(MS.valor_descuento)
                        FROM job_movimiento_control_prestamos_empleados AS MS

                         WHERE
                             MS.documento_identidad_empleado = P.documento_identidad_empleado  AND--
                             MS.consecutivo_fecha_pago       = P.consecutivo                   AND
                             MS.fecha_generacion_control     = P.fecha_generacion              AND
                             MS.concepto_prestamo            = P.concepto_prestamo
                            GROUP BY P.documento_identidad_empleado,MS.concepto_prestamo
                    ) IS NULL,
                    0,
                    (
                    SELECT  SUM(MS.valor_descuento)
                        FROM job_movimiento_control_prestamos_empleados AS MS

                         WHERE
                             MS.documento_identidad_empleado = P.documento_identidad_empleado  AND--
                             MS.consecutivo_fecha_pago       = P.consecutivo                   AND
                             MS.fecha_generacion_control     = P.fecha_generacion              AND
                             MS.concepto_prestamo            = P.concepto_prestamo
                            GROUP BY P.documento_identidad_empleado,MS.concepto_prestamo
                    )
                ) AS valor_pago

        FROM   job_control_prestamos_empleados AS P

        WHERE    IF((SELECT  SUM(MS.valor_descuento)
                        FROM job_movimiento_control_prestamos_empleados AS MS

                         WHERE
                             MS.documento_identidad_empleado = P.documento_identidad_empleado  AND--
                             MS.consecutivo_fecha_pago       = P.consecutivo                   AND
                             MS.fecha_generacion_control     = P.fecha_generacion              AND
                             MS.concepto_prestamo            = P.concepto_prestamo
                            GROUP BY P.documento_identidad_empleado,MS.concepto_prestamo
                    ) IS NULL,0,(SELECT  SUM(MS.valor_descuento)
                        FROM job_movimiento_control_prestamos_empleados AS MS

                         WHERE
                             MS.documento_identidad_empleado = P.documento_identidad_empleado  AND--
                             MS.consecutivo_fecha_pago       = P.consecutivo                   AND
                             MS.fecha_generacion_control     = P.fecha_generacion              AND
                             MS.concepto_prestamo            = P.concepto_prestamo
                            GROUP BY P.documento_identidad_empleado,MS.concepto_prestamo
                    )) <  P.valor_total




------------------------------------------------------------------------------------------------------
 SELECT
                7 AS tabla,
                job_movimiento_control_prestamos_empleados.ano_generacion AS ano_generacion,
                job_movimiento_control_prestamos_empleados.mes_generacion AS mes_generacion,
                job_movimiento_control_prestamos_empleados.codigo_planilla AS codigo_planilla,
                job_movimiento_control_prestamos_empleados.periodo_pago AS periodo_pago,
                job_movimiento_control_prestamos_empleados.codigo_transaccion_contable AS codigo_transaccion_contable,
                job_transacciones_contables_empleado.columna_planilla AS columna_planilla,
                0 AS codigo_transaccion_tiempo,
                job_movimiento_control_prestamos_empleados.documento_identidad_empleado AS documento_identidad_empleado,
                job_movimiento_control_prestamos_empleados.codigo_contable AS codigo_contable,
                job_movimiento_control_prestamos_empleados.sentido AS sentido,
                job_movimiento_control_prestamos_empleados.codigo_empresa_auxiliar AS codigo_empresa_auxliliar,
                job_movimiento_control_prestamos_empleados.codigo_anexo_contable AS codigo_anexo_contable,
                job_movimiento_control_prestamos_empleados.codigo_auxiliar_contable AS codigo_auxiliar_contable,
                job_movimiento_control_prestamos_empleados.valor_descuento AS valor_movimiento,
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
                job_movimiento_control_prestamos_empleados.contabilizado AS contabilizado,
                0 AS consecutivo,
                0 AS fecha_incapacidad
            FROM
                job_movimiento_control_prestamos_empleados,
                job_departamento_seccion_contrato_empleado,
                job_departamentos_empresa,
                job_sucursal_contrato_empleados,
                job_terceros,
                job_transacciones_contables_empleado
            WHERE
                job_movimiento_control_prestamos_empleados.documento_identidad_empleado = job_terceros.documento_identidad AND
                job_movimiento_control_prestamos_empleados.codigo_empresa = job_sucursal_contrato_empleados.codigo_empresa AND
                job_movimiento_control_prestamos_empleados.documento_identidad_empleado = job_sucursal_contrato_empleados.documento_identidad_empleado AND
                job_movimiento_control_prestamos_empleados.fecha_ingreso = job_sucursal_contrato_empleados.fecha_ingreso AND
                job_movimiento_control_prestamos_empleados.codigo_sucursal = job_sucursal_contrato_empleados.codigo_sucursal AND
                job_movimiento_control_prestamos_empleados.fecha_ingreso_sucursal = job_sucursal_contrato_empleados.fecha_ingreso_sucursal AND
                job_departamento_seccion_contrato_empleado.codigo_empresa = job_sucursal_contrato_empleados.codigo_empresa AND
                job_departamento_seccion_contrato_empleado.documento_identidad_empleado = job_sucursal_contrato_empleados.documento_identidad_empleado AND
                job_departamento_seccion_contrato_empleado.fecha_ingreso = job_sucursal_contrato_empleados.fecha_ingreso AND
                job_departamento_seccion_contrato_empleado.codigo_sucursal = job_sucursal_contrato_empleados.codigo_sucursal AND
                job_departamento_seccion_contrato_empleado.fecha_ingreso_sucursal = job_sucursal_contrato_empleados.fecha_ingreso_sucursal AND
                job_departamento_seccion_contrato_empleado.codigo_departamento_empresa = job_departamentos_empresa.codigo AND
                job_movimiento_control_prestamos_empleados.codigo_transaccion_contable = job_transacciones_contables_empleado.codigo

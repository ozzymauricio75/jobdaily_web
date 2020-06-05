/*
Seleccionar todasl las tablas que interfieran en la generación de una planilla
Los datos que debe tener la vista son:

ano_generacion
mes_generacion
codigo_planilla
periodo
codigo_transaccion_contable
codigo_contable
sentido
documento_identidad_empleado
codigo_sucursal
dias_trabajados
salario
valor_movimiento
fecha_pago
codigo_empresa_auxiliar
codigo_anexo_contable
codigo_auxiliar_contable
dias_trabajados

*/
CREATE OR REPLACE ALGORITHM = MERGE VIEW job_consulta_datos_planilla AS
(
    SELECT
        1 AS tabla,
        job_movimientos_salarios.ano_generacion AS ano_generacion,
        job_movimientos_salarios.mes_generacion AS mes_generacion,
        job_movimientos_salarios.codigo_planilla AS codigo_planilla,
        job_movimientos_salarios.periodo_pago AS periodo_pago,
        job_movimientos_salarios.codigo_transaccion_contable AS codigo_transaccion_contable,
        job_transacciones_contables_empleado.columna_planilla AS columna_planilla,
        0 AS codigo_transaccion_tiempo,
        job_movimientos_salarios.documento_identidad_empleado AS documento_identidad_empleado,
        job_movimientos_salarios.codigo_contable AS codigo_contable,
        job_movimientos_salarios.sentido AS sentido,
        job_movimientos_salarios.codigo_empresa AS codigo_empresa_auxliliar,
        job_movimientos_salarios.codigo_anexo_contable AS codigo_anexo_contable,
        job_movimientos_salarios.codigo_auxiliar_contable AS codigo_auxiliar_contable,
        job_movimientos_salarios.valor_movimiento AS valor_movimiento,
        job_movimientos_salarios.dias_trabajados AS dias_trabajados,
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
        job_movimientos_salarios.contabilizado AS contabilizado,
        0 AS consecutivo,
        0 AS fecha_incapacidad
    FROM
        job_movimientos_salarios,
        job_departamento_seccion_contrato_empleado,
        job_departamentos_empresa,
        job_sucursal_contrato_empleados,
        job_terceros,
        job_transacciones_contables_empleado
    WHERE
        job_movimientos_salarios.documento_identidad_empleado = job_terceros.documento_identidad AND
        job_movimientos_salarios.codigo_empresa = job_sucursal_contrato_empleados.codigo_empresa AND
        job_movimientos_salarios.documento_identidad_empleado = job_sucursal_contrato_empleados.documento_identidad_empleado AND
        job_movimientos_salarios.fecha_ingreso_empresa = job_sucursal_contrato_empleados.fecha_ingreso AND
        job_movimientos_salarios.codigo_sucursal = job_sucursal_contrato_empleados.codigo_sucursal AND
        job_movimientos_salarios.fecha_ingreso_sucursal = job_sucursal_contrato_empleados.fecha_ingreso_sucursal AND
        job_departamento_seccion_contrato_empleado.codigo_empresa = job_sucursal_contrato_empleados.codigo_empresa AND
        job_departamento_seccion_contrato_empleado.documento_identidad_empleado = job_sucursal_contrato_empleados.documento_identidad_empleado AND
        job_departamento_seccion_contrato_empleado.fecha_ingreso = job_sucursal_contrato_empleados.fecha_ingreso AND
        job_departamento_seccion_contrato_empleado.codigo_sucursal = job_sucursal_contrato_empleados.codigo_sucursal AND
        job_departamento_seccion_contrato_empleado.fecha_ingreso_sucursal = job_sucursal_contrato_empleados.fecha_ingreso_sucursal AND
        job_departamento_seccion_contrato_empleado.codigo_departamento_empresa = job_departamentos_empresa.codigo AND
        job_movimientos_salarios.codigo_transaccion_contable = job_transacciones_contables_empleado.codigo

)
UNION
(
    SELECT
        2 AS tabla,
        job_movimientos_salud.ano_generacion AS ano_generacion,
        job_movimientos_salud.mes_generacion AS mes_generacion,
        job_movimientos_salud.codigo_planilla AS codigo_planilla,
        job_movimientos_salud.periodo_pago AS periodo_pago,
        job_movimientos_salud.codigo_transaccion_contable AS codigo_transaccion_contable,
        job_transacciones_contables_empleado.columna_planilla AS columna_planilla,
        0 AS codigo_transaccion_tiempo,
        job_movimientos_salud.documento_identidad_empleado AS documento_identidad_empleado,
        job_movimientos_salud.codigo_contable AS codigo_contable,
        job_movimientos_salud.sentido AS sentido,
        job_movimientos_salud.codigo_empresa AS codigo_empresa_auxliliar,
        job_movimientos_salud.codigo_anexo_contable AS codigo_anexo_contable,
        job_movimientos_salud.codigo_auxiliar_contable AS codigo_auxiliar_contable,
        job_movimientos_salud.valor_movimiento AS valor_movimiento,
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
        job_movimientos_salud.contabilizado AS contabilizado,
        0 AS consecutivo,
        0 AS fecha_incapacidad
    FROM
        job_movimientos_salud,
        job_departamento_seccion_contrato_empleado,
        job_departamentos_empresa,
        job_sucursal_contrato_empleados,
        job_terceros,
        job_transacciones_contables_empleado
    WHERE
        job_movimientos_salud.documento_identidad_empleado = job_terceros.documento_identidad AND
        job_movimientos_salud.codigo_empresa = job_sucursal_contrato_empleados.codigo_empresa AND
        job_movimientos_salud.documento_identidad_empleado = job_sucursal_contrato_empleados.documento_identidad_empleado AND
        job_movimientos_salud.fecha_ingreso_empresa = job_sucursal_contrato_empleados.fecha_ingreso AND
        job_movimientos_salud.codigo_sucursal = job_sucursal_contrato_empleados.codigo_sucursal AND
        job_movimientos_salud.fecha_ingreso_sucursal = job_sucursal_contrato_empleados.fecha_ingreso_sucursal AND
        job_departamento_seccion_contrato_empleado.codigo_empresa = job_sucursal_contrato_empleados.codigo_empresa AND
        job_departamento_seccion_contrato_empleado.documento_identidad_empleado = job_sucursal_contrato_empleados.documento_identidad_empleado AND
        job_departamento_seccion_contrato_empleado.fecha_ingreso = job_sucursal_contrato_empleados.fecha_ingreso AND
        job_departamento_seccion_contrato_empleado.codigo_sucursal = job_sucursal_contrato_empleados.codigo_sucursal AND
        job_departamento_seccion_contrato_empleado.fecha_ingreso_sucursal = job_sucursal_contrato_empleados.fecha_ingreso_sucursal AND
        job_departamento_seccion_contrato_empleado.codigo_departamento_empresa = job_departamentos_empresa.codigo AND
        job_movimientos_salud.codigo_transaccion_contable = job_transacciones_contables_empleado.codigo
)
UNION
(
    SELECT
        3 AS tabla,
        job_movimientos_pension.ano_generacion AS ano_generacion,
        job_movimientos_pension.mes_generacion AS mes_generacion,
        job_movimientos_pension.codigo_planilla AS codigo_planilla,
        job_movimientos_pension.periodo_pago AS periodo_pago,
        job_movimientos_pension.codigo_transaccion_contable AS codigo_transaccion_contable,
        job_transacciones_contables_empleado.columna_planilla AS columna_planilla,
        0 AS codigo_transaccion_tiempo,
        job_movimientos_pension.documento_identidad_empleado AS documento_identidad_empleado,
        job_movimientos_pension.codigo_contable AS codigo_contable,
        job_movimientos_pension.sentido AS sentido,
        job_movimientos_pension.codigo_empresa AS codigo_empresa_auxliliar,
        job_movimientos_pension.codigo_anexo_contable AS codigo_anexo_contable,
        job_movimientos_pension.codigo_auxiliar_contable AS codigo_auxiliar_contable,
        job_movimientos_pension.valor_movimiento AS valor_movimiento,
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
        job_movimientos_pension.contabilizado AS contabilizado,
        0 AS consecutivo,
        0 AS fecha_incapacidad
    FROM
        job_movimientos_pension,
        job_departamento_seccion_contrato_empleado,
        job_departamentos_empresa,
        job_sucursal_contrato_empleados,
        job_terceros,
        job_transacciones_contables_empleado
    WHERE
        job_movimientos_pension.documento_identidad_empleado = job_terceros.documento_identidad AND
        job_movimientos_pension.codigo_empresa = job_sucursal_contrato_empleados.codigo_empresa AND
        job_movimientos_pension.documento_identidad_empleado = job_sucursal_contrato_empleados.documento_identidad_empleado AND
        job_movimientos_pension.fecha_ingreso_empresa = job_sucursal_contrato_empleados.fecha_ingreso AND
        job_movimientos_pension.codigo_sucursal = job_sucursal_contrato_empleados.codigo_sucursal AND
        job_movimientos_pension.fecha_ingreso_sucursal = job_sucursal_contrato_empleados.fecha_ingreso_sucursal AND
        job_departamento_seccion_contrato_empleado.codigo_empresa = job_sucursal_contrato_empleados.codigo_empresa AND
        job_departamento_seccion_contrato_empleado.documento_identidad_empleado = job_sucursal_contrato_empleados.documento_identidad_empleado AND
        job_departamento_seccion_contrato_empleado.fecha_ingreso = job_sucursal_contrato_empleados.fecha_ingreso AND
        job_departamento_seccion_contrato_empleado.codigo_sucursal = job_sucursal_contrato_empleados.codigo_sucursal AND
        job_departamento_seccion_contrato_empleado.fecha_ingreso_sucursal = job_sucursal_contrato_empleados.fecha_ingreso_sucursal AND
        job_departamento_seccion_contrato_empleado.codigo_departamento_empresa = job_departamentos_empresa.codigo AND
        job_movimientos_pension.codigo_transaccion_contable = job_transacciones_contables_empleado.codigo
)
UNION
(
    SELECT
        4 AS tabla,
        job_movimiento_tiempos_laborados.ano_generacion AS ano_generacion,
        job_movimiento_tiempos_laborados.mes_generacion AS mes_generacion,
        job_movimiento_tiempos_laborados.codigo_planilla AS codigo_planilla,
        job_movimiento_tiempos_laborados.periodo_pago AS periodo_pago,
        job_movimiento_tiempos_laborados.codigo_transaccion_contable AS codigo_transaccion_contable,
        job_transacciones_contables_empleado.columna_planilla AS columna_planilla,
        job_movimiento_tiempos_laborados.codigo_transaccion_tiempo AS codigo_transaccion_tiempo,
        job_movimiento_tiempos_laborados.documento_identidad_empleado AS documento_identidad_empleado,
        job_movimiento_tiempos_laborados.codigo_contable AS codigo_contable,
        job_movimiento_tiempos_laborados.sentido AS sentido,
        job_movimiento_tiempos_laborados.codigo_empresa_auxiliar AS codigo_empresa_auxliliar,
        job_movimiento_tiempos_laborados.codigo_anexo_contable AS codigo_anexo_contable,
        job_movimiento_tiempos_laborados.codigo_auxiliar_contable AS codigo_auxiliar_contable,
        (job_movimiento_tiempos_laborados.valor_hora_recargo * job_movimiento_tiempos_laborados.cantidad) AS valor_movimiento,
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
        job_movimiento_tiempos_laborados.contabilizado AS contabilizado,
        job_movimiento_tiempos_laborados.consecutivo AS consecutivo,
        0 AS fecha_incapacidad
    FROM
        job_movimiento_tiempos_laborados,
        job_departamento_seccion_contrato_empleado,
        job_departamentos_empresa,
        job_sucursal_contrato_empleados,
        job_terceros,
        job_transacciones_contables_empleado
    WHERE
        job_movimiento_tiempos_laborados.documento_identidad_empleado = job_terceros.documento_identidad AND
        job_movimiento_tiempos_laborados.codigo_empresa = job_sucursal_contrato_empleados.codigo_empresa AND
        job_movimiento_tiempos_laborados.documento_identidad_empleado = job_sucursal_contrato_empleados.documento_identidad_empleado AND
        job_movimiento_tiempos_laborados.fecha_ingreso = job_sucursal_contrato_empleados.fecha_ingreso AND
        job_movimiento_tiempos_laborados.codigo_sucursal = job_sucursal_contrato_empleados.codigo_sucursal AND
        job_movimiento_tiempos_laborados.fecha_ingreso_sucursal = job_sucursal_contrato_empleados.fecha_ingreso_sucursal AND
        job_departamento_seccion_contrato_empleado.codigo_empresa = job_sucursal_contrato_empleados.codigo_empresa AND
        job_departamento_seccion_contrato_empleado.documento_identidad_empleado = job_sucursal_contrato_empleados.documento_identidad_empleado AND
        job_departamento_seccion_contrato_empleado.fecha_ingreso = job_sucursal_contrato_empleados.fecha_ingreso AND
        job_departamento_seccion_contrato_empleado.codigo_sucursal = job_sucursal_contrato_empleados.codigo_sucursal AND
        job_departamento_seccion_contrato_empleado.fecha_ingreso_sucursal = job_sucursal_contrato_empleados.fecha_ingreso_sucursal AND
        job_departamento_seccion_contrato_empleado.codigo_departamento_empresa = job_departamentos_empresa.codigo AND
        job_movimiento_tiempos_laborados.codigo_transaccion_contable = job_transacciones_contables_empleado.codigo
)
UNION
(
    SELECT
        5 AS tabla,
        job_reporte_incapacidades.ano_generacion AS ano_generacion,
        job_reporte_incapacidades.mes_generacion AS mes_generacion,
        job_reporte_incapacidades.codigo_planilla AS codigo_planilla,
        job_reporte_incapacidades.periodo_pago AS periodo_pago,
        job_reporte_incapacidades.codigo_transaccion_contable AS codigo_transaccion_contable,
        job_transacciones_contables_empleado.columna_planilla AS columna_planilla,
        job_reporte_incapacidades.codigo_transaccion_tiempo AS codigo_transaccion_tiempo,
        job_reporte_incapacidades.documento_identidad_empleado AS documento_identidad_empleado,
        job_reporte_incapacidades.codigo_contable AS codigo_contable,
        job_reporte_incapacidades.sentido AS sentido,
        job_reporte_incapacidades.codigo_empresa_auxiliar AS codigo_empresa_auxliliar,
        job_reporte_incapacidades.codigo_anexo_contable AS codigo_anexo_contable,
        job_reporte_incapacidades.codigo_auxiliar_contable AS codigo_auxiliar_contable,
        job_reporte_incapacidades.valor_movimiento AS valor_movimiento,
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
        job_reporte_incapacidades.contabilizado AS contabilizado,
        0 AS consecutivo,
        job_reporte_incapacidades.fecha_incapacidad AS fecha_incapacidad
    FROM
        job_reporte_incapacidades,
        job_departamento_seccion_contrato_empleado,
        job_departamentos_empresa,
        job_sucursal_contrato_empleados,
        job_terceros,
        job_transacciones_contables_empleado
    WHERE
        job_reporte_incapacidades.documento_identidad_empleado = job_terceros.documento_identidad AND
        job_reporte_incapacidades.codigo_empresa = job_sucursal_contrato_empleados.codigo_empresa AND
        job_reporte_incapacidades.documento_identidad_empleado = job_sucursal_contrato_empleados.documento_identidad_empleado AND
        job_reporte_incapacidades.fecha_ingreso = job_sucursal_contrato_empleados.fecha_ingreso AND
        job_reporte_incapacidades.codigo_sucursal = job_sucursal_contrato_empleados.codigo_sucursal AND
        job_reporte_incapacidades.fecha_ingreso_sucursal = job_sucursal_contrato_empleados.fecha_ingreso_sucursal AND
        job_departamento_seccion_contrato_empleado.codigo_empresa = job_sucursal_contrato_empleados.codigo_empresa AND
        job_departamento_seccion_contrato_empleado.documento_identidad_empleado = job_sucursal_contrato_empleados.documento_identidad_empleado AND
        job_departamento_seccion_contrato_empleado.fecha_ingreso = job_sucursal_contrato_empleados.fecha_ingreso AND
        job_departamento_seccion_contrato_empleado.codigo_sucursal = job_sucursal_contrato_empleados.codigo_sucursal AND
        job_departamento_seccion_contrato_empleado.fecha_ingreso_sucursal = job_sucursal_contrato_empleados.fecha_ingreso_sucursal AND
        job_departamento_seccion_contrato_empleado.codigo_departamento_empresa = job_departamentos_empresa.codigo AND
        job_reporte_incapacidades.codigo_transaccion_contable = job_transacciones_contables_empleado.codigo
)
UNION
(
    SELECT
        6 AS tabla,
        job_movimientos_auxilio_transporte.ano_generacion AS ano_generacion,
        job_movimientos_auxilio_transporte.mes_generacion AS mes_generacion,
        job_movimientos_auxilio_transporte.codigo_planilla AS codigo_planilla,
        job_movimientos_auxilio_transporte.periodo_pago AS periodo_pago,
        job_movimientos_auxilio_transporte.codigo_transaccion_contable AS codigo_transaccion_contable,
        job_transacciones_contables_empleado.columna_planilla AS columna_planilla,
        0 AS codigo_transaccion_tiempo,
        job_movimientos_auxilio_transporte.documento_identidad_empleado AS documento_identidad_empleado,
        job_movimientos_auxilio_transporte.codigo_contable AS codigo_contable,
        job_movimientos_auxilio_transporte.sentido AS sentido,
        job_movimientos_auxilio_transporte.codigo_empresa AS codigo_empresa_auxliliar,
        job_movimientos_auxilio_transporte.codigo_anexo_contable AS codigo_anexo_contable,
        job_movimientos_auxilio_transporte.codigo_auxiliar_contable AS codigo_auxiliar_contable,
        job_movimientos_auxilio_transporte.valor_movimiento AS valor_movimiento,
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
        job_movimientos_auxilio_transporte.contabilizado AS contabilizado,
        0 AS consecutivo,
        0 AS fecha_incapacidad
    FROM
        job_movimientos_auxilio_transporte,
        job_departamento_seccion_contrato_empleado,
        job_departamentos_empresa,
        job_sucursal_contrato_empleados,
        job_terceros,
        job_transacciones_contables_empleado
    WHERE
        job_movimientos_auxilio_transporte.documento_identidad_empleado = job_terceros.documento_identidad AND
        job_movimientos_auxilio_transporte.codigo_empresa = job_sucursal_contrato_empleados.codigo_empresa AND
        job_movimientos_auxilio_transporte.documento_identidad_empleado = job_sucursal_contrato_empleados.documento_identidad_empleado AND
        job_movimientos_auxilio_transporte.fecha_ingreso_empresa = job_sucursal_contrato_empleados.fecha_ingreso AND
        job_movimientos_auxilio_transporte.codigo_sucursal = job_sucursal_contrato_empleados.codigo_sucursal AND
        job_movimientos_auxilio_transporte.fecha_ingreso_sucursal = job_sucursal_contrato_empleados.fecha_ingreso_sucursal AND
        job_departamento_seccion_contrato_empleado.codigo_empresa = job_sucursal_contrato_empleados.codigo_empresa AND
        job_departamento_seccion_contrato_empleado.documento_identidad_empleado = job_sucursal_contrato_empleados.documento_identidad_empleado AND
        job_departamento_seccion_contrato_empleado.fecha_ingreso = job_sucursal_contrato_empleados.fecha_ingreso AND
        job_departamento_seccion_contrato_empleado.codigo_sucursal = job_sucursal_contrato_empleados.codigo_sucursal AND
        job_departamento_seccion_contrato_empleado.fecha_ingreso_sucursal = job_sucursal_contrato_empleados.fecha_ingreso_sucursal AND
        job_departamento_seccion_contrato_empleado.codigo_departamento_empresa = job_departamentos_empresa.codigo AND
        job_movimientos_auxilio_transporte.codigo_transaccion_contable = job_transacciones_contables_empleado.codigo
)
UNION
(
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
)
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
UNION
(
    SELECT
                9 AS tabla,
                job_movimiento_cuenta_por_cobrar_descuento.ano_generacion AS ano_generacion,
                job_movimiento_cuenta_por_cobrar_descuento.mes_generacion AS mes_generacion,
                job_movimiento_cuenta_por_cobrar_descuento.codigo_planilla AS codigo_planilla,
                job_movimiento_cuenta_por_cobrar_descuento.periodo_pago AS periodo_pago,
                job_movimiento_cuenta_por_cobrar_descuento.codigo_transaccion_contable AS codigo_transaccion_contable,
                job_transacciones_contables_empleado.columna_planilla AS columna_planilla,
                0 AS codigo_transaccion_tiempo,
                job_movimiento_cuenta_por_cobrar_descuento.documento_identidad_empleado AS documento_identidad_empleado,
                job_movimiento_cuenta_por_cobrar_descuento.codigo_contable AS codigo_contable,
                job_movimiento_cuenta_por_cobrar_descuento.sentido AS sentido,
                0  AS codigo_empresa_auxliliar,
                "" AS codigo_anexo_contable,
                0  AS codigo_auxiliar_contable,
                job_movimiento_cuenta_por_cobrar_descuento.valor_movimiento AS valor_movimiento,
                0  AS dias_trabajados,
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
                job_movimiento_cuenta_por_cobrar_descuento.contabilizado AS contabilizado,
                0 AS consecutivo,
                0 AS fecha_incapacidad
            FROM
                job_movimiento_cuenta_por_cobrar_descuento,
                job_departamento_seccion_contrato_empleado,
                job_departamentos_empresa,
                job_sucursal_contrato_empleados,
                job_terceros,
                job_transacciones_contables_empleado
            WHERE
                job_movimiento_cuenta_por_cobrar_descuento.documento_identidad_empleado = job_terceros.documento_identidad AND
                job_movimiento_cuenta_por_cobrar_descuento.codigo_empresa = job_sucursal_contrato_empleados.codigo_empresa AND
                job_movimiento_cuenta_por_cobrar_descuento.documento_identidad_empleado = job_sucursal_contrato_empleados.documento_identidad_empleado AND
                job_movimiento_cuenta_por_cobrar_descuento.codigo_sucursal = job_sucursal_contrato_empleados.codigo_sucursal AND
                job_departamento_seccion_contrato_empleado.codigo_empresa = job_sucursal_contrato_empleados.codigo_empresa AND
                job_departamento_seccion_contrato_empleado.documento_identidad_empleado = job_sucursal_contrato_empleados.documento_identidad_empleado AND
                job_departamento_seccion_contrato_empleado.fecha_ingreso = job_sucursal_contrato_empleados.fecha_ingreso AND
                job_departamento_seccion_contrato_empleado.codigo_sucursal = job_sucursal_contrato_empleados.codigo_sucursal AND
                job_departamento_seccion_contrato_empleado.fecha_ingreso_sucursal = job_sucursal_contrato_empleados.fecha_ingreso_sucursal AND
                job_departamento_seccion_contrato_empleado.codigo_departamento_empresa = job_departamentos_empresa.codigo AND
                job_movimiento_cuenta_por_cobrar_descuento.codigo_transaccion_contable = job_transacciones_contables_empleado.codigo
)
UNION
(
        SELECT
            10 AS tabla,
            job_reporte_tiempo_no_laborado.ano_generacion AS ano_generacion,
            job_reporte_tiempo_no_laborado.mes_generacion AS mes_generacion,
            job_reporte_tiempo_no_laborado.codigo_planilla AS codigo_planilla,
            job_reporte_tiempo_no_laborado.periodo_pago AS periodo_pago,
            job_reporte_tiempo_no_laborado.codigo_transaccion_contable AS codigo_transaccion_contable,
            job_transacciones_contables_empleado.columna_planilla AS columna_planilla,
            job_reporte_tiempo_no_laborado.codigo_transaccion_tiempo AS codigo_transaccion_tiempo,
            job_reporte_tiempo_no_laborado.documento_identidad_empleado AS documento_identidad_empleado,
            job_reporte_tiempo_no_laborado.codigo_contable AS codigo_contable,
            job_reporte_tiempo_no_laborado.sentido AS sentido,
            job_reporte_tiempo_no_laborado.codigo_empresa_auxiliar AS codigo_empresa_auxliliar,
            job_reporte_tiempo_no_laborado.codigo_anexo_contable AS codigo_anexo_contable,
            job_reporte_tiempo_no_laborado.codigo_auxiliar_contable AS codigo_auxiliar_contable,
            job_reporte_tiempo_no_laborado.valor_dia AS valor_movimiento,
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
            job_reporte_tiempo_no_laborado.contabilizado AS contabilizado,
            0 AS consecutivo,
            job_reporte_tiempo_no_laborado.fecha_tiempo AS fecha_incapacidad
        FROM
            job_reporte_tiempo_no_laborado,
            job_departamento_seccion_contrato_empleado,
            job_departamentos_empresa,
            job_sucursal_contrato_empleados,
            job_terceros,
            job_transacciones_contables_empleado
        WHERE
            job_reporte_tiempo_no_laborado.documento_identidad_empleado = job_terceros.documento_identidad AND
            job_reporte_tiempo_no_laborado.codigo_empresa = job_sucursal_contrato_empleados.codigo_empresa AND
            job_reporte_tiempo_no_laborado.documento_identidad_empleado = job_sucursal_contrato_empleados.documento_identidad_empleado AND
            job_reporte_tiempo_no_laborado.fecha_ingreso = job_sucursal_contrato_empleados.fecha_ingreso AND
            job_reporte_tiempo_no_laborado.codigo_sucursal = job_sucursal_contrato_empleados.codigo_sucursal AND
            job_reporte_tiempo_no_laborado.fecha_ingreso_sucursal = job_sucursal_contrato_empleados.fecha_ingreso_sucursal AND
            job_departamento_seccion_contrato_empleado.codigo_empresa = job_sucursal_contrato_empleados.codigo_empresa AND
            job_departamento_seccion_contrato_empleado.documento_identidad_empleado = job_sucursal_contrato_empleados.documento_identidad_empleado AND
            job_departamento_seccion_contrato_empleado.fecha_ingreso = job_sucursal_contrato_empleados.fecha_ingreso AND
            job_departamento_seccion_contrato_empleado.codigo_sucursal = job_sucursal_contrato_empleados.codigo_sucursal AND
            job_departamento_seccion_contrato_empleado.fecha_ingreso_sucursal = job_sucursal_contrato_empleados.fecha_ingreso_sucursal AND
            job_departamento_seccion_contrato_empleado.codigo_departamento_empresa = job_departamentos_empresa.codigo AND
            job_reporte_tiempo_no_laborado.codigo_transaccion_contable = job_transacciones_contables_empleado.codigo
)
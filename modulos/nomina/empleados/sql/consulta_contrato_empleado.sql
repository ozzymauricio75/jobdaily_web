CREATE OR REPLACE ALGORITHM = MERGE VIEW job_consulta_contrato_empleado AS
    SELECT
        job_ingreso_empleados.codigo_empresa AS codigo_empresa,
        job_ingreso_empleados.documento_identidad_empleado AS documento_identidad_empleado,
        job_menu_terceros.NOMBRE_COMPLETO AS nombre_empleado,
        job_ingreso_empleados.fecha_ingreso AS fecha_ingreso,
        job_ingreso_empleados.fecha_vencimiento_contrato AS fecha_vencimiento_contrato,
        job_ingreso_empleados.fecha_retiro AS fecha_retiro_empresa,
        job_ingreso_empleados.codigo_motivo_retiro AS codigo_motivo_retiro_empresa,
        job_ingreso_empleados.riesgo_profesional AS riesgo_profesional,
        job_ingreso_empleados.manejo_auxilio_transporte AS manejo_auxilio_transporte,
        job_ingreso_empleados.estado AS estado,

        job_contrato_empleados.fecha_contrato AS fecha_contrato,
        job_contrato_empleados.codigo_tipo_contrato AS codigo_tipo_contrato,
        job_contrato_empleados.fecha_cambio_contrato AS fecha_cambio_contrato,

        job_sucursal_contrato_empleados.codigo_sucursal AS codigo_sucursal,
        job_sucursal_contrato_empleados.fecha_ingreso_sucursal AS fecha_ingreso_sucursal,
        job_sucursal_contrato_empleados.codigo_empresa_auxiliar AS codigo_empresa_auxiliar,
        job_sucursal_contrato_empleados.codigo_anexo_contable AS codigo_anexo_contable,
        job_sucursal_contrato_empleados.codigo_auxiliar AS codigo_auxiliar,
        job_sucursal_contrato_empleados.codigo_planilla AS codigo_planilla,
        job_sucursal_contrato_empleados.codigo_turno_laboral AS codigo_turno_laboral,
        job_sucursal_contrato_empleados.codigo_motivo_retiro AS codigo_motivo_retiro_sucursal,
        job_sucursal_contrato_empleados.fecha_retiro AS fecha_retiro_sucursal,
        job_sucursal_contrato_empleados.codigo_transaccion_salario AS codigo_transaccion_salario,
        job_sucursal_contrato_empleados.codigo_transaccion_auxilio_transporte AS codigo_transaccion_auxilio_transporte,
        job_sucursal_contrato_empleados.forma_pago_auxilio AS forma_pago_auxilio,
        job_sucursal_contrato_empleados.codigo_transaccion_salud AS codigo_transaccion_salud,
        job_sucursal_contrato_empleados.forma_descuento_salud AS forma_descuento_salud,
        job_sucursal_contrato_empleados.codigo_transaccion_pension AS codigo_transaccion_pension,
        job_sucursal_contrato_empleados.forma_descuento_pension AS forma_descuento_pension,
        job_sucursal_contrato_empleados.codigo_transaccion_normales AS codigo_transaccion_normales,
        job_sucursal_contrato_empleados.codigo_transaccion_extras AS codigo_transaccion_extras,
        job_sucursal_contrato_empleados.codigo_transaccion_recargo_nocturno AS codigo_transaccion_recargo_nocturno,
        job_sucursal_contrato_empleados.codigo_transaccion_extras_nocturnas AS codigo_transaccion_extras_nocturnas,
        job_sucursal_contrato_empleados.codigo_transaccion_dominicales AS codigo_transaccion_dominicales,
        job_sucursal_contrato_empleados.codigo_transaccion_extras_dominicales AS codigo_transaccion_extras_dominicales,
        job_sucursal_contrato_empleados.codigo_transaccion_recargo_noche_dominicales AS codigo_transaccion_recargo_noche_dominicales,
        job_sucursal_contrato_empleados.codigo_transaccion_extras_noche_dominicales AS codigo_transaccion_extras_noche_dominicales,

        job_salario_sucursal_contrato.fecha_salario AS fecha_salario,
        job_salario_sucursal_contrato.salario AS salario,
        job_salario_sucursal_contrato.valor_dia AS valor_dia,
        job_salario_sucursal_contrato.valor_hora AS valor_hora,
        job_salario_sucursal_contrato.fecha_cambio_salario AS fecha_cambio_salario,

        job_cargo_contrato_empleados.fecha_inicia_cargo AS fecha_inicia_cargo,
        job_cargo_contrato_empleados.codigo_cargo AS codigo_cargo,
        job_cargo_contrato_empleados.fecha_termina AS fecha_termina_cargo,
        job_cargo_contrato_empleados.documento_identidad_jefe_inmediato AS documento_identidad_jefe_inmediato,

        job_departamento_seccion_contrato_empleado.fecha_inicia_departamento_seccion AS fecha_inicia_departamento_seccion,
        job_departamento_seccion_contrato_empleado.codigo_departamento_empresa AS codigo_departamento_empresa,
        job_departamento_seccion_contrato_empleado.codigo_seccion_empresa AS codigo_seccion_empresa,
        job_departamento_seccion_contrato_empleado.fecha_termina AS fecha_termina_seccion

    FROM
        job_ingreso_empleados,
        job_contrato_empleados,
        job_sucursal_contrato_empleados,
        job_salario_sucursal_contrato,
        job_cargo_contrato_empleados,
        job_departamento_seccion_contrato_empleado,
        job_menu_terceros
    WHERE
        job_menu_terceros.id = job_ingreso_empleados.documento_identidad_empleado AND

        job_ingreso_empleados.codigo_empresa = job_contrato_empleados.codigo_empresa AND
        job_ingreso_empleados.documento_identidad_empleado = job_contrato_empleados.documento_identidad_empleado AND
        job_ingreso_empleados.fecha_ingreso = job_contrato_empleados.fecha_ingreso AND

        job_ingreso_empleados.codigo_empresa = job_sucursal_contrato_empleados.codigo_empresa AND
        job_ingreso_empleados.documento_identidad_empleado = job_sucursal_contrato_empleados.documento_identidad_empleado AND
        job_ingreso_empleados.fecha_ingreso = job_sucursal_contrato_empleados.fecha_ingreso AND

        job_sucursal_contrato_empleados.codigo_empresa = job_salario_sucursal_contrato.codigo_empresa AND
        job_sucursal_contrato_empleados.documento_identidad_empleado = job_salario_sucursal_contrato.documento_identidad_empleado AND
        job_sucursal_contrato_empleados.fecha_ingreso = job_salario_sucursal_contrato.fecha_ingreso AND
        job_sucursal_contrato_empleados.codigo_sucursal = job_salario_sucursal_contrato.codigo_sucursal AND
        job_sucursal_contrato_empleados.fecha_ingreso_sucursal = job_salario_sucursal_contrato.fecha_ingreso_sucursal AND

        job_sucursal_contrato_empleados.codigo_empresa = job_cargo_contrato_empleados.codigo_empresa AND
        job_sucursal_contrato_empleados.documento_identidad_empleado = job_cargo_contrato_empleados.documento_identidad_empleado AND
        job_sucursal_contrato_empleados.fecha_ingreso = job_cargo_contrato_empleados.fecha_ingreso AND
        job_sucursal_contrato_empleados.codigo_sucursal = job_cargo_contrato_empleados.codigo_sucursal AND
        job_sucursal_contrato_empleados.fecha_ingreso_sucursal = job_cargo_contrato_empleados.fecha_ingreso_sucursal AND

        job_sucursal_contrato_empleados.codigo_empresa = job_departamento_seccion_contrato_empleado.codigo_empresa AND
        job_sucursal_contrato_empleados.documento_identidad_empleado = job_departamento_seccion_contrato_empleado.documento_identidad_empleado AND
        job_sucursal_contrato_empleados.fecha_ingreso = job_departamento_seccion_contrato_empleado.fecha_ingreso AND
        job_sucursal_contrato_empleados.codigo_sucursal = job_departamento_seccion_contrato_empleado.codigo_sucursal AND
        job_sucursal_contrato_empleados.fecha_ingreso_sucursal = job_departamento_seccion_contrato_empleado.fecha_ingreso_sucursal;

     CREATE OR REPLACE ALGORITHM = MERGE VIEW job_consulta_contrato_empleado AS SELECT

        job_ingreso_empleados.codigo_empresa AS codigo_empresa,
        job_ingreso_empleados.documento_identidad_empleado AS documento_identidad,
        job_ingreso_empleados.fecha_ingreso AS fecha_ingreso,
        job_ingreso_empleados.fecha_vencimiento_contrato AS fecha_vencimiento_contrato,
        job_ingreso_empleados.fecha_retiro AS fecha_retiro,
        job_ingreso_empleados.codigo_motivo_retiro  AS codigo_motivo_retiro,
        job_ingreso_empleados.riesgo_profesional AS riesgo_profesional,
        job_ingreso_empleados.manejo_auxilio_transporte AS manejo_auxilio_transporte,
        job_ingreso_empleados.estado AS estado,

        job_contrato_empleados.fecha_contrato AS fecha_contrato,
        job_contrato_empleados.codigo_tipo_contrato  AS codigo_tipo_contrato,
        job_contrato_empleados.fecha_cambio_contrato AS fecha_cambio_contrato,

        job_sucursal_contrato_empleados.codigo_sucursal AS codigo_sucursal,
        job_sucursal_contrato_empleados.fecha_ingreso_sucursal AS fecha_ingreso_sucursal,
        job_sucursal_contrato_empleados.codigo_anexo_contable AS codigo_anexo_contable,
        job_sucursal_contrato_empleados.codigo_auxiliar AS codigo_auxiliar,
        job_sucursal_contrato_empleados.codigo_planilla AS codigo_planilla,
        job_sucursal_contrato_empleados.salario_mensual AS salario_mensual,
        job_sucursal_contrato_empleados.valor_hora AS valor_hora,
        job_sucursal_contrato_empleados.codigo_turno_laboral AS codigo_turno,
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

        job_cargo_contrato_empleados.fecha_inicia_cargo AS fecha_inicia_cargo,
        job_cargo_contrato_empleados.codigo_cargo AS codigo_cargo,
        job_cargo_contrato_empleados.fecha_termina AS fecha_termina_cargo,

        job_departamento_seccion_contrato_empleado.fecha_inicia_departamento_seccion AS fecha_inicia_departamento_seccion,
        job_departamento_seccion_contrato_empleado.codigo_departamento_empresa AS codigo_departamento_empresa,
        job_departamento_seccion_contrato_empleado.codigo_seccion_empresa AS codigo_seccion_empresa,
        job_departamento_seccion_contrato_empleado.fecha_termina AS fecha_termina,

        job_entidades_salud_empleados.fecha_inicio_salud AS fecha_inicio_salud,
        job_entidades_salud_empleados.direccion_atencion AS direccion_atencion_salud,
        job_entidades_salud_empleados.direccion_urgencia AS direccion_urgencia_salud
    FROM
        job_ingreso_empleados,
        job_contrato_empleados,
        job_sucursal_contrato_empleados,
        job_sucursales,
        job_auxiliares_contables,
        job_planillas,
        job_turnos_laborales,
        job_motivos_retiro,
        job_transacciones_contables_empleado,
        job_transacciones_tiempo,
        job_cargo_contrato_empleados,
        job_cargos,
        job_terceros,
        job_departamento_seccion_contrato_empleado,
        job_secciones_departamentos,
        job_entidades_salud_empleados,
        job_entidades_parafiscales
    WHERE
        job_ingreso_empleados.codigo_empresa = job_contrato_empleados.codigo_empresa AND
        job_ingreso_empleados.documento_identidad_empleado = job_contrato_empleados.documento_identidad_empleado AND
        job_ingreso_empleados.fecha_ingreso = job_contrato_empleados.fecha_ingreso AND

        job_ingreso_empleados.codigo_empresa = job_sucursal_contrato_empleados.codigo_empresa AND
        job_ingreso_empleados.documento_identidad_empleado = job_sucursal_contrato_empleados.documento_identidad_empleado AND
        job_ingreso_empleados.fecha_ingreso = job_sucursal_contrato_empleados.fecha_ingreso AND

        job_sucursal_contrato_empleados.codigo_sucursal = job_sucursales.codigo AND
        job_sucursal_contrato_empleados.codigo_anexo_contable = job_auxiliares_contables.codigo_anexo_contable AND
        job_sucursal_contrato_empleados.codigo_auxiliar = job_auxiliares_contables.codigo AND
        job_sucursal_contrato_empleados.codigo_planilla = job_planillas.codigo AND
        job_sucursal_contrato_empleados.codigo_turno_laboral = job_turnos_laborales.codigo AND
        job_sucursal_contrato_empleados.codigo_motivo_retiro = job_motivos_retiro.codigo AND
        job_sucursal_contrato_empleados.codigo_transaccion_salario = job_transacciones_contables_empleado.codigo AND
        job_sucursal_contrato_empleados.codigo_transaccion_auxilio_transporte = job_transacciones_contables_empleado.codigo AND
        job_sucursal_contrato_empleados.codigo_transaccion_salud = job_transacciones_contables_empleado.codigo AND
        job_sucursal_contrato_empleados.codigo_transaccion_pension = job_transacciones_contables_empleado.codigo AND
        job_sucursal_contrato_empleados.codigo_transaccion_normales = job_transacciones_tiempo.codigo AND
        job_sucursal_contrato_empleados.codigo_transaccion_extras = job_transacciones_tiempo.codigo AND
        job_sucursal_contrato_empleados.codigo_transaccion_recargo_nocturno = job_transacciones_tiempo.codigo AND
        job_sucursal_contrato_empleados.codigo_transaccion_extras_nocturnas = job_transacciones_tiempo.codigo AND
        job_sucursal_contrato_empleados.codigo_transaccion_dominicales = job_transacciones_tiempo.codigo AND
        job_sucursal_contrato_empleados.codigo_transaccion_extras_dominicales = job_transacciones_tiempo.codigo AND
        job_sucursal_contrato_empleados.codigo_transaccion_recargo_noche_dominicales = job_transacciones_tiempo.codigo AND
        job_sucursal_contrato_empleados.codigo_transaccion_extras_noche_dominicales = job_transacciones_tiempo.codigo AND
        job_sucursal_contrato_empleados.codigo_empresa = job_cargo_contrato_empleados.codigo_empresa AND

        job_sucursal_contrato_empleados.documento_identidad_empleado = job_cargo_contrato_empleados.documento_identidad_empleado AND
        job_sucursal_contrato_empleados.fecha_ingreso = job_cargo_contrato_empleados.fecha_ingreso AND
        job_sucursal_contrato_empleados.codigo_sucursal = job_cargo_contrato_empleados.codigo_sucursal AND
        job_sucursal_contrato_empleados.fecha_ingreso_sucursal = job_cargo_contrato_empleados.fecha_ingreso_sucursal AND

        job_cargo_contrato_empleados.codigo_cargo = job_cargos.codigo AND

        job_cargo_contrato_empleados.documento_identidad_jefe_inmediato = job_terceros.documento_identidad AND

        job_sucursal_contrato_empleados.codigo_empresa = job_departamento_seccion_contrato_empleado.codigo_empresa AND
        job_sucursal_contrato_empleados.documento_identidad_empleado = job_departamento_seccion_contrato_empleado.documento_identidad_empleado AND
        job_sucursal_contrato_empleados.fecha_ingreso = job_departamento_seccion_contrato_empleado.fecha_ingreso AND
        job_sucursal_contrato_empleados.codigo_sucursal = job_departamento_seccion_contrato_empleado.codigo_sucursal AND
        job_sucursal_contrato_empleados.fecha_ingreso_sucursal = job_departamento_seccion_contrato_empleado.fecha_ingreso_sucursal AND

        job_departamento_seccion_contrato_empleado.codigo_departamento_empresa = job_secciones_departamentos.codigo_departamento_empresa AND
        job_departamento_seccion_contrato_empleado.codigo_seccion_empresa = job_secciones_departamentos.codigo AND

        job_entidades_salud_empleados.codigo_empresa = job_ingreso_empleados.codigo_empresa AND
        job_entidades_salud_empleados.documento_identidad_empleado = job_ingreso_empleados.documento_identidad_empleado AND
        job_entidades_salud_empleados.fecha_ingreso = job_ingreso_empleados.fecha_ingreso AND

        job_entidades_salud_empleados.codigo_entidad_salud = job_entidades_parafiscales.codigo;

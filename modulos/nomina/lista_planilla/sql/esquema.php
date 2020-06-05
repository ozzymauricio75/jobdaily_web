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

$registros["componentes"] = array(
    array(
        "id"            => "LISTAPLA",
        "padre"         => "SUBMPRPL",
        "id_modulo"     => "NOMINA",
        "orden"         => "60",
        "visible"       => "1",
        "carpeta"       => "lista_planilla",
        "global"        => "0",
        "archivo"       => "lista_planilla",
        "requiere_item" => "0",
        "tipo_enlace"   => "2"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_consulta_datos_planilla AS
        (
            SELECT
                1 AS tabla,
                'job_movimientos_salarios' AS nombre_tabla,
                job_movimientos_salarios.ano_generacion AS ano_generacion,
                job_movimientos_salarios.mes_generacion AS mes_generacion,
                job_movimientos_salarios.codigo_planilla AS codigo_planilla,
                job_movimientos_salarios.periodo_pago AS periodo_pago,
                job_movimientos_salarios.codigo_transaccion_contable AS codigo_transaccion_contable,
                job_transacciones_contables_empleado.acumula_cesantias AS acumula_cesantias,
                job_transacciones_contables_empleado.acumula_prima AS acumula_prima,
                job_transacciones_contables_empleado.acumula_vacaciones AS acumula_vacaciones,
                job_transacciones_contables_empleado.ibc_salud AS ibc_salud,
                job_transacciones_contables_empleado.ibc_pension AS ibc_pension,
                job_transacciones_contables_empleado.ibc_arp AS ibc_arp,
                job_transacciones_contables_empleado.ibc_icbf AS ibc_icbf,
                job_transacciones_contables_empleado.ibc_caja_compensacion AS ibc_caja_compensacion,
                job_transacciones_contables_empleado.ibc_sena AS ibc_sena,
                job_transacciones_contables_empleado.codigo_concepto_transaccion_contable AS concepto_contable,
                job_transacciones_contables_empleado.columna_planilla AS columna_planilla,
                0 AS codigo_transaccion_tiempo,
                0 AS resta_salario,
                0 AS resta_auxilio_transporte,
                0 AS resta_cesantias,
                0 AS resta_prima,
                0 AS resta_vacaciones,
                0 AS extras_empleado,
                0 AS concepto_tiempo,
                (job_movimientos_salarios.documento_identidad_empleado*1) AS documento_identidad_empleado,
                job_movimientos_salarios.codigo_contable AS codigo_contable,
                job_movimientos_salarios.sentido AS sentido,
                job_movimientos_salarios.codigo_empresa AS codigo_empresa_auxiliar,
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
                CONCAT(
                    IF(job_terceros.primer_nombre IS NOT NULL,
                        CONCAT(
                            CONCAT(job_terceros.primer_apellido,' '),
                            IF(job_terceros.segundo_apellido IS NOT NULL,CONCAT(job_terceros.segundo_apellido,' '),' '),
                            CONCAT(job_terceros.primer_nombre,' '),
                            IF(job_terceros.segundo_nombre IS NOT NULL,CONCAT(job_terceros.segundo_nombre,' '),'')
                        ),
                        job_terceros.razon_social
                    )
                ) AS apellido_empleado,
                job_departamentos_empresa.codigo AS codigo_departamento_empresa,
                job_departamentos_empresa.nombre AS departamento_empresa,
                job_sucursal_contrato_empleados.salario_mensual AS salario_empleado,
                job_sucursal_contrato_empleados.codigo_empresa AS codigo_empresa,
                job_sucursal_contrato_empleados.codigo_sucursal AS codigo_sucursal,
                job_sucursal_contrato_empleados.fecha_ingreso AS fecha_ingreso_empresa,
                job_sucursal_contrato_empleados.fecha_ingreso_sucursal AS fecha_ingreso_sucursal,
                job_movimientos_salarios.contabilizado AS contabilizado,
                0 AS consecutivo,
                0 AS fecha_incapacidad,
                job_movimientos_salarios.fecha_ingreso_planilla AS fecha_registro,
                job_movimientos_salarios.fecha_pago_planilla AS fecha_pago_planilla,
                '' AS obligacion
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
                'job_movimientos_salud' AS nombre_tabla,
                job_movimientos_salud.ano_generacion AS ano_generacion,
                job_movimientos_salud.mes_generacion AS mes_generacion,
                job_movimientos_salud.codigo_planilla AS codigo_planilla,
                job_movimientos_salud.periodo_pago AS periodo_pago,
                job_movimientos_salud.codigo_transaccion_contable AS codigo_transaccion_contable,
                job_transacciones_contables_empleado.acumula_cesantias AS acumula_cesantias,
                job_transacciones_contables_empleado.acumula_prima AS acumula_prima,
                job_transacciones_contables_empleado.acumula_vacaciones AS acumula_vacaciones,
                job_transacciones_contables_empleado.ibc_salud AS ibc_salud,
                job_transacciones_contables_empleado.ibc_pension AS ibc_pension,
                job_transacciones_contables_empleado.ibc_arp AS ibc_arp,
                job_transacciones_contables_empleado.ibc_icbf AS ibc_icbf,
                job_transacciones_contables_empleado.ibc_caja_compensacion AS ibc_caja_compensacion,
                job_transacciones_contables_empleado.ibc_sena AS ibc_sena,
                job_transacciones_contables_empleado.codigo_concepto_transaccion_contable AS concepto_contable,
                job_transacciones_contables_empleado.columna_planilla AS columna_planilla,
                0 AS codigo_transaccion_tiempo,
                0 AS resta_salario,
                0 AS resta_auxilio_transporte,
                0 AS resta_cesantias,
                0 AS resta_prima,
                0 AS resta_vacaciones,
                0 AS extras_empleado,
                0 AS concepto_tiempo,
                (job_movimientos_salud.documento_identidad_empleado*1) AS documento_identidad_empleado,
                job_movimientos_salud.codigo_contable AS codigo_contable,
                job_movimientos_salud.sentido AS sentido,
                job_movimientos_salud.codigo_empresa AS codigo_empresa_auxiliar,
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
                CONCAT(
                    IF(job_terceros.primer_nombre IS NOT NULL,
                        CONCAT(
                            CONCAT(job_terceros.primer_apellido,' '),
                            IF(job_terceros.segundo_apellido IS NOT NULL,CONCAT(job_terceros.segundo_apellido,' '),' '),
                            CONCAT(job_terceros.primer_nombre,' '),
                            IF(job_terceros.segundo_nombre IS NOT NULL,CONCAT(job_terceros.segundo_nombre,' '),'')
                        ),
                        job_terceros.razon_social
                    )
                ) AS apellido_empleado,
                job_departamentos_empresa.codigo AS codigo_departamento_empresa,
                job_departamentos_empresa.nombre AS departamento_empresa,
                job_sucursal_contrato_empleados.salario_mensual AS salario_empleado,
                job_sucursal_contrato_empleados.codigo_empresa AS codigo_empresa,
                job_sucursal_contrato_empleados.codigo_sucursal AS codigo_sucursal,
                job_sucursal_contrato_empleados.fecha_ingreso AS fecha_ingreso_empresa,
                job_sucursal_contrato_empleados.fecha_ingreso_sucursal AS fecha_ingreso_sucursal,
                job_movimientos_salud.contabilizado AS contabilizado,
                0 AS consecutivo,
                0 AS fecha_incapacidad,
                job_movimientos_salud.fecha_ingreso_planilla AS fecha_registro,
                job_movimientos_salud.fecha_pago_planilla AS fecha_pago_planilla,
                '' AS obligacion
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
                'job_movimientos_pension' AS nombre_tabla,
                job_movimientos_pension.ano_generacion AS ano_generacion,
                job_movimientos_pension.mes_generacion AS mes_generacion,
                job_movimientos_pension.codigo_planilla AS codigo_planilla,
                job_movimientos_pension.periodo_pago AS periodo_pago,
                job_movimientos_pension.codigo_transaccion_contable AS codigo_transaccion_contable,
                job_transacciones_contables_empleado.acumula_cesantias AS acumula_cesantias,
                job_transacciones_contables_empleado.acumula_prima AS acumula_prima,
                job_transacciones_contables_empleado.acumula_vacaciones AS acumula_vacaciones,
                job_transacciones_contables_empleado.ibc_salud AS ibc_salud,
                job_transacciones_contables_empleado.ibc_pension AS ibc_pension,
                job_transacciones_contables_empleado.ibc_arp AS ibc_arp,
                job_transacciones_contables_empleado.ibc_icbf AS ibc_icbf,
                job_transacciones_contables_empleado.ibc_caja_compensacion AS ibc_caja_compensacion,
                job_transacciones_contables_empleado.ibc_sena AS ibc_sena,
                job_transacciones_contables_empleado.codigo_concepto_transaccion_contable AS concepto_contable,
                job_transacciones_contables_empleado.columna_planilla AS columna_planilla,
                0 AS codigo_transaccion_tiempo,
                0 AS resta_salario,
                0 AS resta_auxilio_transporte,
                0 AS resta_cesantias,
                0 AS resta_prima,
                0 AS resta_vacaciones,
                0 AS extras_empleado,
                0 AS concepto_tiempo,
                (job_movimientos_pension.documento_identidad_empleado*1) AS documento_identidad_empleado,
                job_movimientos_pension.codigo_contable AS codigo_contable,
                job_movimientos_pension.sentido AS sentido,
                job_movimientos_pension.codigo_empresa AS codigo_empresa_auxiliar,
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
                CONCAT(
                    IF(job_terceros.primer_nombre IS NOT NULL,
                        CONCAT(
                            CONCAT(job_terceros.primer_apellido,' '),
                            IF(job_terceros.segundo_apellido IS NOT NULL,CONCAT(job_terceros.segundo_apellido,' '),' '),
                            CONCAT(job_terceros.primer_nombre,' '),
                            IF(job_terceros.segundo_nombre IS NOT NULL,CONCAT(job_terceros.segundo_nombre,' '),'')
                        ),
                        job_terceros.razon_social
                    )
                ) AS apellido_empleado,
                job_departamentos_empresa.codigo AS codigo_departamento_empresa,
                job_departamentos_empresa.nombre AS departamento_empresa,
                job_sucursal_contrato_empleados.salario_mensual AS salario_empleado,
                job_sucursal_contrato_empleados.codigo_empresa AS codigo_empresa,
                job_sucursal_contrato_empleados.codigo_sucursal AS codigo_sucursal,
                job_sucursal_contrato_empleados.fecha_ingreso AS fecha_ingreso_empresa,
                job_sucursal_contrato_empleados.fecha_ingreso_sucursal AS fecha_ingreso_sucursal,
                job_movimientos_pension.contabilizado AS contabilizado,
                0 AS consecutivo,
                0 AS fecha_incapacidad,
                job_movimientos_pension.fecha_ingreso_planilla AS fecha_registro,
                job_movimientos_pension.fecha_pago_planilla AS fecha_pago_planilla,
                '' AS obligacion
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
                'job_movimiento_tiempos_laborados' AS nombre_tabla,
                job_movimiento_tiempos_laborados.ano_generacion AS ano_generacion,
                job_movimiento_tiempos_laborados.mes_generacion AS mes_generacion,
                job_movimiento_tiempos_laborados.codigo_planilla AS codigo_planilla,
                job_movimiento_tiempos_laborados.periodo_pago AS periodo_pago,
                job_movimiento_tiempos_laborados.codigo_transaccion_contable AS codigo_transaccion_contable,
                job_transacciones_contables_empleado.acumula_cesantias AS acumula_cesantias,
                job_transacciones_contables_empleado.acumula_prima AS acumula_prima,
                job_transacciones_contables_empleado.acumula_vacaciones AS acumula_vacaciones,
                job_transacciones_contables_empleado.ibc_salud AS ibc_salud,
                job_transacciones_contables_empleado.ibc_pension AS ibc_pension,
                job_transacciones_contables_empleado.ibc_arp AS ibc_arp,
                job_transacciones_contables_empleado.ibc_icbf AS ibc_icbf,
                job_transacciones_contables_empleado.ibc_caja_compensacion AS ibc_caja_compensacion,
                job_transacciones_contables_empleado.ibc_sena AS ibc_sena,
                job_transacciones_contables_empleado.codigo_concepto_transaccion_contable AS concepto_contable,
                job_transacciones_contables_empleado.columna_planilla AS columna_planilla,
                job_movimiento_tiempos_laborados.codigo_transaccion_tiempo AS codigo_transaccion_tiempo,
                job_transacciones_tiempo.resta_salario AS resta_salario,
                job_transacciones_tiempo.resta_auxilio_transporte AS resta_auxilio_transporte,
                job_transacciones_tiempo.resta_cesantias AS resta_cesantias,
                job_transacciones_tiempo.resta_prima AS resta_prima,
                job_transacciones_tiempo.resta_vacaciones AS resta_vacaciones,
                job_transacciones_tiempo.extras_empleado AS extras_empleado,
                job_conceptos_transacciones_tiempo.tipo AS concepto_tiempo,
                (job_movimiento_tiempos_laborados.documento_identidad_empleado*1) AS documento_identidad_empleado,
                job_movimiento_tiempos_laborados.codigo_contable AS codigo_contable,
                job_movimiento_tiempos_laborados.sentido AS sentido,
                job_movimiento_tiempos_laborados.codigo_empresa_auxiliar AS codigo_empresa_auxiliar,
                job_movimiento_tiempos_laborados.codigo_anexo_contable AS codigo_anexo_contable,
                job_movimiento_tiempos_laborados.codigo_auxiliar_contable AS codigo_auxiliar_contable,
                job_movimiento_tiempos_laborados.valor_movimiento AS valor_movimiento,
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
                CONCAT(
                    IF(job_terceros.primer_nombre IS NOT NULL,
                        CONCAT(
                            CONCAT(job_terceros.primer_apellido,' '),
                            IF(job_terceros.segundo_apellido IS NOT NULL,CONCAT(job_terceros.segundo_apellido,' '),' '),
                            CONCAT(job_terceros.primer_nombre,' '),
                            IF(job_terceros.segundo_nombre IS NOT NULL,CONCAT(job_terceros.segundo_nombre,' '),'')
                        ),
                        job_terceros.razon_social
                    )
                ) AS apellido_empleado,
                job_departamentos_empresa.codigo AS codigo_departamento_empresa,
                job_departamentos_empresa.nombre AS departamento_empresa,
                job_sucursal_contrato_empleados.salario_mensual AS salario_empleado,
                job_sucursal_contrato_empleados.codigo_empresa AS codigo_empresa,
                job_sucursal_contrato_empleados.codigo_sucursal AS codigo_sucursal,
                job_sucursal_contrato_empleados.fecha_ingreso AS fecha_ingreso_empresa,
                job_sucursal_contrato_empleados.fecha_ingreso_sucursal AS fecha_ingreso_sucursal,
                job_movimiento_tiempos_laborados.contabilizado AS contabilizado,
                job_movimiento_tiempos_laborados.consecutivo AS consecutivo,
                0 AS fecha_incapacidad,
                job_movimiento_tiempos_laborados.fecha_generacion AS fecha_registro,
                job_movimiento_tiempos_laborados.fecha_pago_planilla AS fecha_pago_planilla,
                '' AS obligacion
            FROM
                job_movimiento_tiempos_laborados,
                job_departamento_seccion_contrato_empleado,
                job_departamentos_empresa,
                job_sucursal_contrato_empleados,
                job_terceros,
                job_transacciones_contables_empleado,
                job_transacciones_tiempo,
                job_conceptos_transacciones_tiempo
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
                job_movimiento_tiempos_laborados.codigo_transaccion_contable = job_transacciones_contables_empleado.codigo AND
                job_movimiento_tiempos_laborados.codigo_transaccion_tiempo = job_transacciones_tiempo.codigo AND
                job_conceptos_transacciones_tiempo.codigo = job_transacciones_tiempo.codigo_concepto_transaccion_tiempo
        )
        UNION
        (
            SELECT
                5 AS tabla,
                'job_reporte_incapacidades' AS nombre_tabla,
                job_reporte_incapacidades.ano_generacion AS ano_generacion,
                job_reporte_incapacidades.mes_generacion AS mes_generacion,
                job_reporte_incapacidades.codigo_planilla AS codigo_planilla,
                job_reporte_incapacidades.periodo_pago AS periodo_pago,
                job_reporte_incapacidades.codigo_transaccion_contable AS codigo_transaccion_contable,
                job_transacciones_contables_empleado.acumula_cesantias AS acumula_cesantias,
                job_transacciones_contables_empleado.acumula_prima AS acumula_prima,
                job_transacciones_contables_empleado.acumula_vacaciones AS acumula_vacaciones,
                job_transacciones_contables_empleado.ibc_salud AS ibc_salud,
                job_transacciones_contables_empleado.ibc_pension AS ibc_pension,
                job_transacciones_contables_empleado.ibc_arp AS ibc_arp,
                job_transacciones_contables_empleado.ibc_icbf AS ibc_icbf,
                job_transacciones_contables_empleado.ibc_caja_compensacion AS ibc_caja_compensacion,
                job_transacciones_contables_empleado.ibc_sena AS ibc_sena,
                job_transacciones_contables_empleado.codigo_concepto_transaccion_contable AS concepto_contable,
                job_transacciones_contables_empleado.columna_planilla AS columna_planilla,
                job_reporte_incapacidades.codigo_transaccion_tiempo AS codigo_transaccion_tiempo,
                job_transacciones_tiempo.resta_salario AS resta_salario,
                job_transacciones_tiempo.resta_auxilio_transporte AS resta_auxilio_transporte,
                job_transacciones_tiempo.resta_cesantias AS resta_cesantias,
                job_transacciones_tiempo.resta_prima AS resta_prima,
                job_transacciones_tiempo.resta_vacaciones AS resta_vacaciones,
                job_transacciones_tiempo.extras_empleado AS extras_empleado,
                job_conceptos_transacciones_tiempo.tipo AS concepto_tiempo,
                (job_reporte_incapacidades.documento_identidad_empleado*1) AS documento_identidad_empleado,
                job_reporte_incapacidades.codigo_contable AS codigo_contable,
                job_reporte_incapacidades.sentido AS sentido,
                job_reporte_incapacidades.codigo_empresa_auxiliar AS codigo_empresa_auxiliar,
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
                CONCAT(
                    IF(job_terceros.primer_nombre IS NOT NULL,
                        CONCAT(
                            CONCAT(job_terceros.primer_apellido,' '),
                            IF(job_terceros.segundo_apellido IS NOT NULL,CONCAT(job_terceros.segundo_apellido,' '),' '),
                            CONCAT(job_terceros.primer_nombre,' '),
                            IF(job_terceros.segundo_nombre IS NOT NULL,CONCAT(job_terceros.segundo_nombre,' '),'')
                        ),
                        job_terceros.razon_social
                    )
                ) AS apellido_empleado,
                job_departamentos_empresa.codigo AS codigo_departamento_empresa,
                job_departamentos_empresa.nombre AS departamento_empresa,
                job_sucursal_contrato_empleados.salario_mensual AS salario_empleado,
                job_sucursal_contrato_empleados.codigo_empresa AS codigo_empresa,
                job_sucursal_contrato_empleados.codigo_sucursal AS codigo_sucursal,
                job_sucursal_contrato_empleados.fecha_ingreso AS fecha_ingreso_empresa,
                job_sucursal_contrato_empleados.fecha_ingreso_sucursal AS fecha_ingreso_sucursal,
                job_reporte_incapacidades.contabilizado AS contabilizado,
                0 AS consecutivo,
                job_reporte_incapacidades.fecha_incapacidad AS fecha_incapacidad,
                job_reporte_incapacidades.fecha_registro AS fecha_registro,
                job_reporte_incapacidades.fecha_pago_planilla AS fecha_pago_planilla,
                '' AS obligacion
            FROM
                job_reporte_incapacidades,
                job_departamento_seccion_contrato_empleado,
                job_departamentos_empresa,
                job_sucursal_contrato_empleados,
                job_terceros,
                job_transacciones_contables_empleado,
                job_transacciones_tiempo,
                job_conceptos_transacciones_tiempo
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
                job_reporte_incapacidades.codigo_transaccion_contable = job_transacciones_contables_empleado.codigo AND
                job_reporte_incapacidades.codigo_transaccion_tiempo = job_transacciones_tiempo.codigo AND
                job_conceptos_transacciones_tiempo.codigo = job_transacciones_tiempo.codigo_concepto_transaccion_tiempo
        )
        UNION
        (
            SELECT
                6 AS tabla,
                'job_movimientos_auxilio_transporte' AS nombre_tabla,
                job_movimientos_auxilio_transporte.ano_generacion AS ano_generacion,
                job_movimientos_auxilio_transporte.mes_generacion AS mes_generacion,
                job_movimientos_auxilio_transporte.codigo_planilla AS codigo_planilla,
                job_movimientos_auxilio_transporte.periodo_pago AS periodo_pago,
                job_movimientos_auxilio_transporte.codigo_transaccion_contable AS codigo_transaccion_contable,
                job_transacciones_contables_empleado.acumula_cesantias AS acumula_cesantias,
                job_transacciones_contables_empleado.acumula_prima AS acumula_prima,
                job_transacciones_contables_empleado.acumula_vacaciones AS acumula_vacaciones,
                job_transacciones_contables_empleado.ibc_salud AS ibc_salud,
                job_transacciones_contables_empleado.ibc_pension AS ibc_pension,
                job_transacciones_contables_empleado.ibc_arp AS ibc_arp,
                job_transacciones_contables_empleado.ibc_icbf AS ibc_icbf,
                job_transacciones_contables_empleado.ibc_caja_compensacion AS ibc_caja_compensacion,
                job_transacciones_contables_empleado.ibc_sena AS ibc_sena,
                job_transacciones_contables_empleado.codigo_concepto_transaccion_contable AS concepto_contable,
                job_transacciones_contables_empleado.columna_planilla AS columna_planilla,
                0 AS codigo_transaccion_tiempo,
                0 AS resta_salario,
                0 AS resta_auxilio_transporte,
                0 AS resta_cesantias,
                0 AS resta_prima,
                0 AS resta_vacaciones,
                0 AS extras_empleado,
                0 AS concepto_tiempo,
                (job_movimientos_auxilio_transporte.documento_identidad_empleado*1) AS documento_identidad_empleado,
                job_movimientos_auxilio_transporte.codigo_contable AS codigo_contable,
                job_movimientos_auxilio_transporte.sentido AS sentido,
                job_movimientos_auxilio_transporte.codigo_empresa AS codigo_empresa_auxiliar,
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
                CONCAT(
                    IF(job_terceros.primer_nombre IS NOT NULL,
                        CONCAT(
                            CONCAT(job_terceros.primer_apellido,' '),
                            IF(job_terceros.segundo_apellido IS NOT NULL,CONCAT(job_terceros.segundo_apellido,' '),' '),
                            CONCAT(job_terceros.primer_nombre,' '),
                            IF(job_terceros.segundo_nombre IS NOT NULL,CONCAT(job_terceros.segundo_nombre,' '),'')
                        ),
                        job_terceros.razon_social
                    )
                ) AS apellido_empleado,
                job_departamentos_empresa.codigo AS codigo_departamento_empresa,
                job_departamentos_empresa.nombre AS departamento_empresa,
                job_sucursal_contrato_empleados.salario_mensual AS salario_empleado,
                job_sucursal_contrato_empleados.codigo_empresa AS codigo_empresa,
                job_sucursal_contrato_empleados.codigo_sucursal AS codigo_sucursal,
                job_sucursal_contrato_empleados.fecha_ingreso AS fecha_ingreso_empresa,
                job_sucursal_contrato_empleados.fecha_ingreso_sucursal AS fecha_ingreso_sucursal,
                job_movimientos_auxilio_transporte.contabilizado AS contabilizado,
                0 AS consecutivo,
                0 AS fecha_incapacidad,
                job_movimientos_auxilio_transporte.fecha_ingreso_planilla AS fecha_registro,
                job_movimientos_auxilio_transporte.fecha_pago_planilla AS fecha_pago_planilla,
                '' AS obligacion
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
                'job_movimiento_control_prestamos_empleados' AS nombre_tabla,
                job_movimiento_control_prestamos_empleados.ano_generacion AS ano_generacion,
                job_movimiento_control_prestamos_empleados.mes_generacion AS mes_generacion,
                job_movimiento_control_prestamos_empleados.codigo_planilla AS codigo_planilla,
                job_movimiento_control_prestamos_empleados.periodo_pago AS periodo_pago,
                job_movimiento_control_prestamos_empleados.codigo_transaccion_contable AS codigo_transaccion_contable,
                job_transacciones_contables_empleado.acumula_cesantias AS acumula_cesantias,
                job_transacciones_contables_empleado.acumula_prima AS acumula_prima,
                job_transacciones_contables_empleado.acumula_vacaciones AS acumula_vacaciones,
                job_transacciones_contables_empleado.ibc_salud AS ibc_salud,
                job_transacciones_contables_empleado.ibc_pension AS ibc_pension,
                job_transacciones_contables_empleado.ibc_arp AS ibc_arp,
                job_transacciones_contables_empleado.ibc_icbf AS ibc_icbf,
                job_transacciones_contables_empleado.ibc_caja_compensacion AS ibc_caja_compensacion,
                job_transacciones_contables_empleado.ibc_sena AS ibc_sena,
                job_transacciones_contables_empleado.codigo_concepto_transaccion_contable AS concepto_contable,
                job_transacciones_contables_empleado.columna_planilla AS columna_planilla,
                0 AS codigo_transaccion_tiempo,
                0 AS resta_salario,
                0 AS resta_auxilio_transporte,
                0 AS resta_cesantias,
                0 AS resta_prima,
                0 AS resta_vacaciones,
                0 AS extras_empleado,
                0 AS concepto_tiempo,
                (job_movimiento_control_prestamos_empleados.documento_identidad_empleado*1) AS documento_identidad_empleado,
                job_movimiento_control_prestamos_empleados.codigo_contable AS codigo_contable,
                job_movimiento_control_prestamos_empleados.sentido AS sentido,
                job_movimiento_control_prestamos_empleados.codigo_empresa_auxiliar AS codigo_empresa_auxiliar,
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
                CONCAT(
                    IF(job_terceros.primer_nombre IS NOT NULL,
                        CONCAT(
                            CONCAT(job_terceros.primer_apellido,' '),
                            IF(job_terceros.segundo_apellido IS NOT NULL,CONCAT(job_terceros.segundo_apellido,' '),' '),
                            CONCAT(job_terceros.primer_nombre,' '),
                            IF(job_terceros.segundo_nombre IS NOT NULL,CONCAT(job_terceros.segundo_nombre,' '),'')
                        ),
                        job_terceros.razon_social
                    )
                ) AS apellido_empleado,
                job_departamentos_empresa.codigo AS codigo_departamento_empresa,
                job_departamentos_empresa.nombre AS departamento_empresa,
                job_sucursal_contrato_empleados.salario_mensual AS salario_empleado,
                job_sucursal_contrato_empleados.codigo_empresa AS codigo_empresa,
                job_sucursal_contrato_empleados.codigo_sucursal AS codigo_sucursal,
                job_sucursal_contrato_empleados.fecha_ingreso AS fecha_ingreso_empresa,
                job_sucursal_contrato_empleados.fecha_ingreso_sucursal AS fecha_ingreso_sucursal,
                job_movimiento_control_prestamos_empleados.contabilizado AS contabilizado,
                0 AS consecutivo,
                0 AS fecha_incapacidad,
                job_movimiento_control_prestamos_empleados.fecha_generacion AS fecha_registro,
                job_movimiento_control_prestamos_empleados.fecha_pago_planilla AS fecha_pago_planilla,
                '' AS obligacion
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
            )UNION
            (
            SELECT
                8 AS tabla,
                'job_movimiento_novedades_manuales' AS nombre_tabla,
                job_movimiento_novedades_manuales.ano_generacion AS ano_generacion,
                job_movimiento_novedades_manuales.mes_generacion AS mes_generacion,
                job_movimiento_novedades_manuales.codigo_planilla AS codigo_planilla,
                job_movimiento_novedades_manuales.periodo_pago AS periodo_pago,
                job_movimiento_novedades_manuales.codigo_transaccion_contable AS codigo_transaccion_contable,
                job_transacciones_contables_empleado.acumula_cesantias AS acumula_cesantias,
                job_transacciones_contables_empleado.acumula_prima AS acumula_prima,
                job_transacciones_contables_empleado.acumula_vacaciones AS acumula_vacaciones,
                job_transacciones_contables_empleado.ibc_salud AS ibc_salud,
                job_transacciones_contables_empleado.ibc_pension AS ibc_pension,
                job_transacciones_contables_empleado.ibc_arp AS ibc_arp,
                job_transacciones_contables_empleado.ibc_icbf AS ibc_icbf,
                job_transacciones_contables_empleado.ibc_caja_compensacion AS ibc_caja_compensacion,
                job_transacciones_contables_empleado.ibc_sena AS ibc_sena,
                job_transacciones_contables_empleado.codigo_concepto_transaccion_contable AS concepto_contable,
                job_transacciones_contables_empleado.columna_planilla AS columna_planilla,
                0 AS codigo_transaccion_tiempo,
                0 AS resta_salario,
                0 AS resta_auxilio_transporte,
                0 AS resta_cesantias,
                0 AS resta_prima,
                0 AS resta_vacaciones,
                0 AS extras_empleado,
                0 AS concepto_tiempo,
                (job_movimiento_novedades_manuales.documento_identidad_empleado*1) AS documento_identidad_empleado,
                job_movimiento_novedades_manuales.codigo_contable AS codigo_contable,
                job_movimiento_novedades_manuales.sentido AS sentido,
                job_movimiento_novedades_manuales.codigo_empresa_auxiliar AS codigo_empresa_auxiliar,
                job_movimiento_novedades_manuales.codigo_anexo_contable AS codigo_anexo_contable,
                job_movimiento_novedades_manuales.codigo_auxiliar_contable AS codigo_auxiliar_contable,
                job_movimiento_novedades_manuales.valor_movimiento AS valor_movimiento,
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
                CONCAT(
                    IF(job_terceros.primer_nombre IS NOT NULL,
                        CONCAT(
                            CONCAT(job_terceros.primer_apellido,' '),
                            IF(job_terceros.segundo_apellido IS NOT NULL,CONCAT(job_terceros.segundo_apellido,' '),' '),
                            CONCAT(job_terceros.primer_nombre,' '),
                            IF(job_terceros.segundo_nombre IS NOT NULL,CONCAT(job_terceros.segundo_nombre,' '),'')
                        ),
                        job_terceros.razon_social
                    )
                ) AS apellido_empleado,
                job_departamentos_empresa.codigo AS codigo_departamento_empresa,
                job_departamentos_empresa.nombre AS departamento_empresa,
                job_sucursal_contrato_empleados.salario_mensual AS salario_empleado,
                job_sucursal_contrato_empleados.codigo_empresa AS codigo_empresa,
                job_sucursal_contrato_empleados.codigo_sucursal AS codigo_sucursal,
                job_sucursal_contrato_empleados.fecha_ingreso AS fecha_ingreso_empresa,
                job_sucursal_contrato_empleados.fecha_ingreso_sucursal AS fecha_ingreso_sucursal,
                job_movimiento_novedades_manuales.contabilizado AS contabilizado,
                0 AS consecutivo,
                0 AS fecha_incapacidad,
                job_movimiento_novedades_manuales.fecha_generacion AS fecha_registro,
                job_movimiento_novedades_manuales.fecha_pago_planilla AS fecha_pago_planilla,
                '' AS obligacion
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
                'job_movimiento_cuenta_por_cobrar_descuento' AS nombre_tabla,
                job_movimiento_cuenta_por_cobrar_descuento.ano_generacion AS ano_generacion,
                job_movimiento_cuenta_por_cobrar_descuento.mes_generacion AS mes_generacion,
                job_movimiento_cuenta_por_cobrar_descuento.codigo_planilla AS codigo_planilla,
                job_movimiento_cuenta_por_cobrar_descuento.periodo_pago AS periodo_pago,
                job_movimiento_cuenta_por_pagar_tercero.codigo_transaccion_contable AS codigo_transaccion_contable,
                job_transacciones_contables_empleado.acumula_cesantias AS acumula_cesantias,
                job_transacciones_contables_empleado.acumula_prima AS acumula_prima,
                job_transacciones_contables_empleado.acumula_vacaciones AS acumula_vacaciones,
                job_transacciones_contables_empleado.ibc_salud AS ibc_salud,
                job_transacciones_contables_empleado.ibc_pension AS ibc_pension,
                job_transacciones_contables_empleado.ibc_arp AS ibc_arp,
                job_transacciones_contables_empleado.ibc_icbf AS ibc_icbf,
                job_transacciones_contables_empleado.ibc_caja_compensacion AS ibc_caja_compensacion,
                job_transacciones_contables_empleado.ibc_sena AS ibc_sena,
                job_transacciones_contables_empleado.codigo_concepto_transaccion_contable AS concepto_contable,
                job_transacciones_contables_empleado.columna_planilla AS columna_planilla,
                0 AS codigo_transaccion_tiempo,
                0 AS resta_salario,
                0 AS resta_auxilio_transporte,
                0 AS resta_cesantias,
                0 AS resta_prima,
                0 AS resta_vacaciones,
                0 AS extras_empleado,
                0 AS concepto_tiempo,
                (job_movimiento_cuenta_por_cobrar_descuento.documento_identidad_empleado*1) AS documento_identidad_empleado,
                job_movimiento_cuenta_por_cobrar_descuento.codigo_contable AS codigo_contable,
                job_movimiento_cuenta_por_cobrar_descuento.sentido AS sentido,
                0  AS codigo_empresa_auxiliar,
                '' AS codigo_anexo_contable,
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
                CONCAT(
                    IF(job_terceros.primer_nombre IS NOT NULL,
                        CONCAT(
                            CONCAT(job_terceros.primer_apellido,' '),
                            IF(job_terceros.segundo_apellido IS NOT NULL,CONCAT(job_terceros.segundo_apellido,' '),' '),
                            CONCAT(job_terceros.primer_nombre,' '),
                            IF(job_terceros.segundo_nombre IS NOT NULL,CONCAT(job_terceros.segundo_nombre,' '),'')
                        ),
                        job_terceros.razon_social
                    )
                ) AS apellido_empleado,
                job_departamentos_empresa.codigo AS codigo_departamento_empresa,
                job_departamentos_empresa.nombre AS departamento_empresa,
                job_sucursal_contrato_empleados.salario_mensual AS salario_empleado,
                job_sucursal_contrato_empleados.codigo_empresa AS codigo_empresa,
                job_sucursal_contrato_empleados.codigo_sucursal AS codigo_sucursal,
                job_sucursal_contrato_empleados.fecha_ingreso AS fecha_ingreso_empresa,
                job_sucursal_contrato_empleados.fecha_ingreso_sucursal AS fecha_ingreso_sucursal,
                job_movimiento_cuenta_por_cobrar_descuento.contabilizado AS contabilizado,
                0 AS consecutivo,
                0 AS fecha_incapacidad,
                job_movimiento_cuenta_por_cobrar_descuento.fecha_generacion AS fecha_registro,
                job_movimiento_cuenta_por_cobrar_descuento.fecha_pago_planilla AS fecha_pago_planilla,
                job_movimiento_cuenta_por_cobrar_descuento.obligacion AS obligacion
            FROM
                job_movimiento_cuenta_por_cobrar_descuento,
                job_departamento_seccion_contrato_empleado,
                job_departamentos_empresa,
                job_sucursal_contrato_empleados,
                job_terceros,
                job_transacciones_contables_empleado,
                job_movimiento_cuenta_por_pagar_tercero
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
                job_movimiento_cuenta_por_pagar_tercero.codigo_transaccion_contable = job_transacciones_contables_empleado.codigo AND
                job_movimiento_cuenta_por_pagar_tercero.codigo_empresa = job_movimiento_cuenta_por_cobrar_descuento.codigo_empresa AND
                job_movimiento_cuenta_por_pagar_tercero.documento_identidad_empleado = job_movimiento_cuenta_por_cobrar_descuento.documento_identidad_empleado AND
                job_movimiento_cuenta_por_pagar_tercero.obligacion = job_movimiento_cuenta_por_cobrar_descuento.obligacion

        )
        UNION
        (
            SELECT
                10 AS tabla,
                'job_movimiento_tiempos_no_laborados_dias' AS nombre_tabla,
                job_movimiento_tiempos_no_laborados_dias.ano_generacion AS ano_generacion,
                job_movimiento_tiempos_no_laborados_dias.mes_generacion AS mes_generacion,
                job_movimiento_tiempos_no_laborados_dias.codigo_planilla AS codigo_planilla,
                job_movimiento_tiempos_no_laborados_dias.periodo_pago AS periodo_pago,
                job_movimiento_tiempos_no_laborados_dias.codigo_transaccion_contable AS codigo_transaccion_contable,
                job_transacciones_contables_empleado.acumula_cesantias AS acumula_cesantias,
                job_transacciones_contables_empleado.acumula_prima AS acumula_prima,
                job_transacciones_contables_empleado.acumula_vacaciones AS acumula_vacaciones,
                job_transacciones_contables_empleado.ibc_salud AS ibc_salud,
                job_transacciones_contables_empleado.ibc_pension AS ibc_pension,
                job_transacciones_contables_empleado.ibc_arp AS ibc_arp,
                job_transacciones_contables_empleado.ibc_icbf AS ibc_icbf,
                job_transacciones_contables_empleado.ibc_caja_compensacion AS ibc_caja_compensacion,
                job_transacciones_contables_empleado.ibc_sena AS ibc_sena,
                job_transacciones_contables_empleado.codigo_concepto_transaccion_contable AS concepto_contable,
                job_transacciones_contables_empleado.columna_planilla AS columna_planilla,
                job_movimiento_tiempos_no_laborados_dias.codigo_transaccion_tiempo AS codigo_transaccion_tiempo,
                job_transacciones_tiempo.resta_salario AS resta_salario,
                job_transacciones_tiempo.resta_auxilio_transporte AS resta_auxilio_transporte,
                job_transacciones_tiempo.resta_cesantias AS resta_cesantias,
                job_transacciones_tiempo.resta_prima AS resta_prima,
                job_transacciones_tiempo.resta_vacaciones AS resta_vacaciones,
                job_transacciones_tiempo.extras_empleado AS extras_empleado,
                job_conceptos_transacciones_tiempo.tipo AS concepto_tiempo,
                (job_movimiento_tiempos_no_laborados_dias.documento_identidad_empleado*1) AS documento_identidad_empleado,
                job_movimiento_tiempos_no_laborados_dias.codigo_contable AS codigo_contable,
                job_movimiento_tiempos_no_laborados_dias.sentido AS sentido,
                job_movimiento_tiempos_no_laborados_dias.codigo_empresa_auxiliar AS codigo_empresa_auxiliar,
                job_movimiento_tiempos_no_laborados_dias.codigo_anexo_contable AS codigo_anexo_contable,
                job_movimiento_tiempos_no_laborados_dias.codigo_auxiliar_contable AS codigo_auxiliar_contable,
                job_movimiento_tiempos_no_laborados_dias.valor_dia AS valor_movimiento,
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
                CONCAT(
                    IF(job_terceros.primer_nombre IS NOT NULL,
                        CONCAT(
                            CONCAT(job_terceros.primer_apellido,' '),
                            IF(job_terceros.segundo_apellido IS NOT NULL,CONCAT(job_terceros.segundo_apellido,' '),' '),
                            CONCAT(job_terceros.primer_nombre,' '),
                            IF(job_terceros.segundo_nombre IS NOT NULL,CONCAT(job_terceros.segundo_nombre,' '),'')
                        ),
                        job_terceros.razon_social
                    )
                ) AS apellido_empleado,
                job_departamentos_empresa.codigo AS codigo_departamento_empresa,
                job_departamentos_empresa.nombre AS departamento_empresa,
                job_sucursal_contrato_empleados.salario_mensual AS salario_empleado,
                job_sucursal_contrato_empleados.codigo_empresa AS codigo_empresa,
                job_sucursal_contrato_empleados.codigo_sucursal AS codigo_sucursal,
                job_sucursal_contrato_empleados.fecha_ingreso AS fecha_ingreso_empresa,
                job_sucursal_contrato_empleados.fecha_ingreso_sucursal AS fecha_ingreso_sucursal,
                job_movimiento_tiempos_no_laborados_dias.contabilizado AS contabilizado,
                0 AS consecutivo,
                job_movimiento_tiempos_no_laborados_dias.fecha_tiempo AS fecha_incapacidad,
                job_movimiento_tiempos_no_laborados_dias.fecha_generacion AS fecha_registro,
                job_movimiento_tiempos_no_laborados_dias.fecha_pago_planilla AS fecha_pago_planilla,
                '' AS obligacion
            FROM
                job_movimiento_tiempos_no_laborados_dias,
                job_departamento_seccion_contrato_empleado,
                job_departamentos_empresa,
                job_sucursal_contrato_empleados,
                job_terceros,
                job_transacciones_contables_empleado,
                job_transacciones_tiempo,
                job_conceptos_transacciones_tiempo
            WHERE
                job_movimiento_tiempos_no_laborados_dias.documento_identidad_empleado = job_terceros.documento_identidad AND
                job_movimiento_tiempos_no_laborados_dias.codigo_empresa = job_sucursal_contrato_empleados.codigo_empresa AND
                job_movimiento_tiempos_no_laborados_dias.documento_identidad_empleado = job_sucursal_contrato_empleados.documento_identidad_empleado AND
                job_movimiento_tiempos_no_laborados_dias.fecha_ingreso = job_sucursal_contrato_empleados.fecha_ingreso AND
                job_movimiento_tiempos_no_laborados_dias.codigo_sucursal = job_sucursal_contrato_empleados.codigo_sucursal AND
                job_movimiento_tiempos_no_laborados_dias.fecha_ingreso_sucursal = job_sucursal_contrato_empleados.fecha_ingreso_sucursal AND
                job_departamento_seccion_contrato_empleado.codigo_empresa = job_sucursal_contrato_empleados.codigo_empresa AND
                job_departamento_seccion_contrato_empleado.documento_identidad_empleado = job_sucursal_contrato_empleados.documento_identidad_empleado AND
                job_departamento_seccion_contrato_empleado.fecha_ingreso = job_sucursal_contrato_empleados.fecha_ingreso AND
                job_departamento_seccion_contrato_empleado.codigo_sucursal = job_sucursal_contrato_empleados.codigo_sucursal AND
                job_departamento_seccion_contrato_empleado.fecha_ingreso_sucursal = job_sucursal_contrato_empleados.fecha_ingreso_sucursal AND
                job_departamento_seccion_contrato_empleado.codigo_departamento_empresa = job_departamentos_empresa.codigo AND
                job_movimiento_tiempos_no_laborados_dias.codigo_transaccion_contable = job_transacciones_contables_empleado.codigo AND
                job_movimiento_tiempos_no_laborados_dias.codigo_transaccion_tiempo = job_transacciones_tiempo.codigo AND
                job_conceptos_transacciones_tiempo.codigo = job_transacciones_tiempo.codigo_concepto_transaccion_tiempo
        )
        UNION
        (
            SELECT
                11 AS tabla,
                'movimiento_tiempos_no_laborados_horas' AS nombre_tabla,
                job_movimiento_tiempos_no_laborados_horas.ano_generacion AS ano_generacion,
                job_movimiento_tiempos_no_laborados_horas.mes_generacion AS mes_generacion,
                job_movimiento_tiempos_no_laborados_horas.codigo_planilla AS codigo_planilla,
                job_movimiento_tiempos_no_laborados_horas.periodo_pago AS periodo_pago,
                job_movimiento_tiempos_no_laborados_horas.codigo_transaccion_contable AS codigo_transaccion_contable,
                job_transacciones_contables_empleado.acumula_cesantias AS acumula_cesantias,
                job_transacciones_contables_empleado.acumula_prima AS acumula_prima,
                job_transacciones_contables_empleado.acumula_vacaciones AS acumula_vacaciones,
                job_transacciones_contables_empleado.ibc_salud AS ibc_salud,
                job_transacciones_contables_empleado.ibc_pension AS ibc_pension,
                job_transacciones_contables_empleado.ibc_arp AS ibc_arp,
                job_transacciones_contables_empleado.ibc_icbf AS ibc_icbf,
                job_transacciones_contables_empleado.ibc_caja_compensacion AS ibc_caja_compensacion,
                job_transacciones_contables_empleado.ibc_sena AS ibc_sena,
                job_transacciones_contables_empleado.codigo_concepto_transaccion_contable AS concepto_contable,
                job_transacciones_contables_empleado.columna_planilla AS columna_planilla,
                job_movimiento_tiempos_no_laborados_horas.codigo_transaccion_tiempo AS codigo_transaccion_tiempo,
                job_transacciones_tiempo.resta_salario AS resta_salario,
                job_transacciones_tiempo.resta_auxilio_transporte AS resta_auxilio_transporte,
                job_transacciones_tiempo.resta_cesantias AS resta_cesantias,
                job_transacciones_tiempo.resta_prima AS resta_prima,
                job_transacciones_tiempo.resta_vacaciones AS resta_vacaciones,
                job_transacciones_tiempo.extras_empleado AS extras_empleado,
                job_conceptos_transacciones_tiempo.tipo AS concepto_tiempo,
                (job_movimiento_tiempos_no_laborados_horas.documento_identidad_empleado*1) AS documento_identidad_empleado,
                job_movimiento_tiempos_no_laborados_horas.codigo_contable AS codigo_contable,
                job_movimiento_tiempos_no_laborados_horas.sentido AS sentido,
                job_movimiento_tiempos_no_laborados_horas.codigo_empresa_auxiliar AS codigo_empresa_auxiliar,
                job_movimiento_tiempos_no_laborados_horas.codigo_anexo_contable AS codigo_anexo_contable,
                job_movimiento_tiempos_no_laborados_horas.codigo_auxiliar_contable AS codigo_auxiliar_contable,
                job_movimiento_tiempos_no_laborados_horas.valor_movimiento AS valor_movimiento,
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
                CONCAT(
                    IF(job_terceros.primer_nombre IS NOT NULL,
                        CONCAT(
                            CONCAT(job_terceros.primer_apellido,' '),
                            IF(job_terceros.segundo_apellido IS NOT NULL,CONCAT(job_terceros.segundo_apellido,' '),' '),
                            CONCAT(job_terceros.primer_nombre,' '),
                            IF(job_terceros.segundo_nombre IS NOT NULL,CONCAT(job_terceros.segundo_nombre,' '),'')
                        ),
                        job_terceros.razon_social
                    )
                ) AS apellido_empleado,
                job_departamentos_empresa.codigo AS codigo_departamento_empresa,
                job_departamentos_empresa.nombre AS departamento_empresa,
                job_sucursal_contrato_empleados.salario_mensual AS salario_empleado,
                job_sucursal_contrato_empleados.codigo_empresa AS codigo_empresa,
                job_sucursal_contrato_empleados.codigo_sucursal AS codigo_sucursal,
                job_sucursal_contrato_empleados.fecha_ingreso AS fecha_ingreso_empresa,
                job_sucursal_contrato_empleados.fecha_ingreso_sucursal AS fecha_ingreso_sucursal,
                job_movimiento_tiempos_no_laborados_horas.contabilizado AS contabilizado,
                0 AS consecutivo,
                job_movimiento_tiempos_no_laborados_horas.fecha_registro AS fecha_incapacidad,
                job_movimiento_tiempos_no_laborados_horas.fecha_generacion AS fecha_registro,
                job_movimiento_tiempos_no_laborados_horas.fecha_pago_planilla AS fecha_pago_planilla,
                '' AS obligacion
            FROM
                job_movimiento_tiempos_no_laborados_horas,
                job_departamento_seccion_contrato_empleado,
                job_departamentos_empresa,
                job_sucursal_contrato_empleados,
                job_terceros,
                job_transacciones_contables_empleado,
                job_transacciones_tiempo,
                job_conceptos_transacciones_tiempo
            WHERE
                job_movimiento_tiempos_no_laborados_horas.documento_identidad_empleado = job_terceros.documento_identidad AND
                job_movimiento_tiempos_no_laborados_horas.codigo_empresa = job_sucursal_contrato_empleados.codigo_empresa AND
                job_movimiento_tiempos_no_laborados_horas.documento_identidad_empleado = job_sucursal_contrato_empleados.documento_identidad_empleado AND
                job_movimiento_tiempos_no_laborados_horas.fecha_ingreso = job_sucursal_contrato_empleados.fecha_ingreso AND
                job_movimiento_tiempos_no_laborados_horas.codigo_sucursal = job_sucursal_contrato_empleados.codigo_sucursal AND
                job_movimiento_tiempos_no_laborados_horas.fecha_ingreso_sucursal = job_sucursal_contrato_empleados.fecha_ingreso_sucursal AND
                job_departamento_seccion_contrato_empleado.codigo_empresa = job_sucursal_contrato_empleados.codigo_empresa AND
                job_departamento_seccion_contrato_empleado.documento_identidad_empleado = job_sucursal_contrato_empleados.documento_identidad_empleado AND
                job_departamento_seccion_contrato_empleado.fecha_ingreso = job_sucursal_contrato_empleados.fecha_ingreso AND
                job_departamento_seccion_contrato_empleado.codigo_sucursal = job_sucursal_contrato_empleados.codigo_sucursal AND
                job_departamento_seccion_contrato_empleado.fecha_ingreso_sucursal = job_sucursal_contrato_empleados.fecha_ingreso_sucursal AND
                job_departamento_seccion_contrato_empleado.codigo_departamento_empresa = job_departamentos_empresa.codigo AND
                job_movimiento_tiempos_no_laborados_horas.codigo_transaccion_contable = job_transacciones_contables_empleado.codigo AND
                job_movimiento_tiempos_no_laborados_horas.codigo_transaccion_tiempo = job_transacciones_tiempo.codigo AND
                job_conceptos_transacciones_tiempo.codigo = job_transacciones_tiempo.codigo_concepto_transaccion_tiempo
        )
        UNION
        (
            SELECT
                12 AS tabla,
                'movimientos_salario_retroactivo' AS nombre_tabla,
                job_movimientos_salario_retroactivo.ano_generacion AS ano_generacion,
                job_movimientos_salario_retroactivo.mes_generacion AS mes_generacion,
                job_movimientos_salario_retroactivo.codigo_planilla AS codigo_planilla,
                job_movimientos_salario_retroactivo.periodo_pago AS periodo_pago,
                job_movimientos_salario_retroactivo.codigo_transaccion_contable AS codigo_transaccion_contable,
                job_transacciones_contables_empleado.acumula_cesantias AS acumula_cesantias,
                job_transacciones_contables_empleado.acumula_prima AS acumula_prima,
                job_transacciones_contables_empleado.acumula_vacaciones AS acumula_vacaciones,
                job_transacciones_contables_empleado.ibc_salud AS ibc_salud,
                job_transacciones_contables_empleado.ibc_pension AS ibc_pension,
                job_transacciones_contables_empleado.ibc_arp AS ibc_arp,
                job_transacciones_contables_empleado.ibc_icbf AS ibc_icbf,
                job_transacciones_contables_empleado.ibc_caja_compensacion AS ibc_caja_compensacion,
                job_transacciones_contables_empleado.ibc_sena AS ibc_sena,
                job_transacciones_contables_empleado.codigo_concepto_transaccion_contable AS concepto_contable,
                job_transacciones_contables_empleado.columna_planilla AS columna_planilla,
                0 AS codigo_transaccion_tiempo,
                0 AS resta_salario,
                0 AS resta_auxilio_transporte,
                0 AS resta_cesantias,
                0 AS resta_prima,
                0 AS resta_vacaciones,
                0 AS extras_empleado,
                0 AS concepto_tiempo,
                (job_movimientos_salario_retroactivo.documento_identidad_empleado*1) AS documento_identidad_empleado,
                job_movimientos_salario_retroactivo.codigo_contable AS codigo_contable,
                job_movimientos_salario_retroactivo.sentido AS sentido,
                job_movimientos_salario_retroactivo.codigo_empresa_auxiliar AS codigo_empresa_auxiliar,
                job_movimientos_salario_retroactivo.codigo_anexo_contable AS codigo_anexo_contable,
                job_movimientos_salario_retroactivo.codigo_auxiliar_contable AS codigo_auxiliar_contable,
                job_movimientos_salario_retroactivo.valor_movimiento AS valor_movimiento,
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
                CONCAT(
                    IF(job_terceros.primer_nombre IS NOT NULL,
                        CONCAT(
                            CONCAT(job_terceros.primer_apellido,' '),
                            IF(job_terceros.segundo_apellido IS NOT NULL,CONCAT(job_terceros.segundo_apellido,' '),' '),
                            CONCAT(job_terceros.primer_nombre,' '),
                            IF(job_terceros.segundo_nombre IS NOT NULL,CONCAT(job_terceros.segundo_nombre,' '),'')
                        ),
                        job_terceros.razon_social
                    )
                ) AS apellido_empleado,
                job_departamentos_empresa.codigo AS codigo_departamento_empresa,
                job_departamentos_empresa.nombre AS departamento_empresa,
                job_sucursal_contrato_empleados.salario_mensual AS salario_empleado,
                job_sucursal_contrato_empleados.codigo_empresa AS codigo_empresa,
                job_sucursal_contrato_empleados.codigo_sucursal AS codigo_sucursal,
                job_sucursal_contrato_empleados.fecha_ingreso AS fecha_ingreso_empresa,
                job_sucursal_contrato_empleados.fecha_ingreso_sucursal AS fecha_ingreso_sucursal,
                job_movimientos_salario_retroactivo.contabilizado AS contabilizado,
                0 AS consecutivo,
                '' AS fecha_incapacidad,
                job_movimientos_salario_retroactivo.fecha_registro AS fecha_registro,
                job_movimientos_salario_retroactivo.fecha_pago_planilla AS fecha_pago_planilla,
                '' AS obligacion
            FROM
                job_movimientos_salario_retroactivo,
                job_departamento_seccion_contrato_empleado,
                job_departamentos_empresa,
                job_sucursal_contrato_empleados,
                job_terceros,
                job_transacciones_contables_empleado
            WHERE
                job_movimientos_salario_retroactivo.documento_identidad_empleado = job_terceros.documento_identidad AND
                job_movimientos_salario_retroactivo.codigo_empresa = job_sucursal_contrato_empleados.codigo_empresa AND
                job_movimientos_salario_retroactivo.documento_identidad_empleado = job_sucursal_contrato_empleados.documento_identidad_empleado AND
                job_movimientos_salario_retroactivo.fecha_ingreso_empresa = job_sucursal_contrato_empleados.fecha_ingreso AND
                job_movimientos_salario_retroactivo.codigo_sucursal = job_sucursal_contrato_empleados.codigo_sucursal AND
                job_movimientos_salario_retroactivo.fecha_ingreso_sucursal = job_sucursal_contrato_empleados.fecha_ingreso_sucursal AND
                job_departamento_seccion_contrato_empleado.codigo_empresa = job_sucursal_contrato_empleados.codigo_empresa AND
                job_departamento_seccion_contrato_empleado.documento_identidad_empleado = job_sucursal_contrato_empleados.documento_identidad_empleado AND
                job_departamento_seccion_contrato_empleado.fecha_ingreso = job_sucursal_contrato_empleados.fecha_ingreso AND
                job_departamento_seccion_contrato_empleado.codigo_sucursal = job_sucursal_contrato_empleados.codigo_sucursal AND
                job_departamento_seccion_contrato_empleado.fecha_ingreso_sucursal = job_sucursal_contrato_empleados.fecha_ingreso_sucursal AND
                job_departamento_seccion_contrato_empleado.codigo_departamento_empresa = job_departamentos_empresa.codigo AND
                job_movimientos_salario_retroactivo.codigo_transaccion_contable = job_transacciones_contables_empleado.codigo
        )
        UNION
        (
            SELECT
                13 AS tabla,
                'movimientos_prima' AS nombre_tabla,
                job_movimientos_prima.ano_generacion AS ano_generacion,
                job_movimientos_prima.mes_generacion AS mes_generacion,
                job_movimientos_prima.codigo_planilla AS codigo_planilla,
                job_movimientos_prima.periodo_pago AS periodo_pago,
                job_movimientos_prima.codigo_transaccion_contable AS codigo_transaccion_contable,
                job_transacciones_contables_empleado.acumula_cesantias AS acumula_cesantias,
                job_transacciones_contables_empleado.acumula_prima AS acumula_prima,
                job_transacciones_contables_empleado.acumula_vacaciones AS acumula_vacaciones,
                job_transacciones_contables_empleado.ibc_salud AS ibc_salud,
                job_transacciones_contables_empleado.ibc_pension AS ibc_pension,
                job_transacciones_contables_empleado.ibc_arp AS ibc_arp,
                job_transacciones_contables_empleado.ibc_icbf AS ibc_icbf,
                job_transacciones_contables_empleado.ibc_caja_compensacion AS ibc_caja_compensacion,
                job_transacciones_contables_empleado.ibc_sena AS ibc_sena,
                job_transacciones_contables_empleado.codigo_concepto_transaccion_contable AS concepto_contable,
                job_transacciones_contables_empleado.columna_planilla AS columna_planilla,
                0 AS codigo_transaccion_tiempo,
                0 AS resta_salario,
                0 AS resta_auxilio_transporte,
                0 AS resta_cesantias,
                0 AS resta_prima,
                0 AS resta_vacaciones,
                0 AS extras_empleado,
                0 AS concepto_tiempo,
                (job_movimientos_prima.documento_identidad_empleado*1) AS documento_identidad_empleado,
                job_movimientos_prima.codigo_contable AS codigo_contable,
                job_movimientos_prima.sentido AS sentido,
                job_movimientos_prima.codigo_empresa_auxiliar AS codigo_empresa_auxiliar,
                job_movimientos_prima.codigo_anexo_contable AS codigo_anexo_contable,
                job_movimientos_prima.codigo_auxiliar_contable AS codigo_auxiliar_contable,
                job_movimientos_prima.valor_movimiento AS valor_movimiento,
                job_movimientos_prima.dias_liquidados AS dias_trabajados,
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
                CONCAT(
                    IF(job_terceros.primer_nombre IS NOT NULL,
                        CONCAT(
                            CONCAT(job_terceros.primer_apellido,' '),
                            IF(job_terceros.segundo_apellido IS NOT NULL,CONCAT(job_terceros.segundo_apellido,' '),' '),
                            CONCAT(job_terceros.primer_nombre,' '),
                            IF(job_terceros.segundo_nombre IS NOT NULL,CONCAT(job_terceros.segundo_nombre,' '),'')
                        ),
                        job_terceros.razon_social
                    )
                ) AS apellido_empleado,
                job_departamentos_empresa.codigo AS codigo_departamento_empresa,
                job_departamentos_empresa.nombre AS departamento_empresa,
                job_sucursal_contrato_empleados.salario_mensual AS salario_empleado,
                job_sucursal_contrato_empleados.codigo_empresa AS codigo_empresa,
                job_sucursal_contrato_empleados.codigo_sucursal AS codigo_sucursal,
                job_sucursal_contrato_empleados.fecha_ingreso AS fecha_ingreso_empresa,
                job_sucursal_contrato_empleados.fecha_ingreso_sucursal AS fecha_ingreso_sucursal,
                job_movimientos_prima.contabilizado AS contabilizado,
                0 AS consecutivo,
                '' AS fecha_incapacidad,
                job_movimientos_prima.fecha_registro AS fecha_registro,
                job_movimientos_prima.fecha_pago_planilla AS fecha_pago_planilla,
                '' AS obligacion
            FROM
                job_movimientos_prima,
                job_departamento_seccion_contrato_empleado,
                job_departamentos_empresa,
                job_sucursal_contrato_empleados,
                job_terceros,
                job_transacciones_contables_empleado
            WHERE
                job_movimientos_prima.documento_identidad_empleado = job_terceros.documento_identidad AND
                job_movimientos_prima.codigo_empresa = job_sucursal_contrato_empleados.codigo_empresa AND
                job_movimientos_prima.documento_identidad_empleado = job_sucursal_contrato_empleados.documento_identidad_empleado AND
                job_movimientos_prima.fecha_ingreso_empresa = job_sucursal_contrato_empleados.fecha_ingreso AND
                job_movimientos_prima.codigo_sucursal = job_sucursal_contrato_empleados.codigo_sucursal AND
                job_movimientos_prima.fecha_ingreso_sucursal = job_sucursal_contrato_empleados.fecha_ingreso_sucursal AND
                job_departamento_seccion_contrato_empleado.codigo_empresa = job_sucursal_contrato_empleados.codigo_empresa AND
                job_departamento_seccion_contrato_empleado.documento_identidad_empleado = job_sucursal_contrato_empleados.documento_identidad_empleado AND
                job_departamento_seccion_contrato_empleado.fecha_ingreso = job_sucursal_contrato_empleados.fecha_ingreso AND
                job_departamento_seccion_contrato_empleado.codigo_sucursal = job_sucursal_contrato_empleados.codigo_sucursal AND
                job_departamento_seccion_contrato_empleado.fecha_ingreso_sucursal = job_sucursal_contrato_empleados.fecha_ingreso_sucursal AND
                job_departamento_seccion_contrato_empleado.codigo_departamento_empresa = job_departamentos_empresa.codigo AND
                job_movimientos_prima.codigo_transaccion_contable = job_transacciones_contables_empleado.codigo
        )
        UNION
        (
            SELECT
                14 AS tabla,
                'movimientos_nomina_migracion' AS nombre_tabla,
                job_movimientos_nomina_migracion.ano_generacion AS ano_generacion,
                job_movimientos_nomina_migracion.mes_generacion AS mes_generacion,
                job_movimientos_nomina_migracion.codigo_planilla AS codigo_planilla,
                job_movimientos_nomina_migracion.periodo_pago AS periodo_pago,
                job_movimientos_nomina_migracion.codigo_transaccion_contable AS codigo_transaccion_contable,
                job_transacciones_contables_empleado.acumula_cesantias AS acumula_cesantias,
                job_transacciones_contables_empleado.acumula_prima AS acumula_prima,
                job_transacciones_contables_empleado.acumula_vacaciones AS acumula_vacaciones,
                job_transacciones_contables_empleado.ibc_salud AS ibc_salud,
                job_transacciones_contables_empleado.ibc_pension AS ibc_pension,
                job_transacciones_contables_empleado.ibc_arp AS ibc_arp,
                job_transacciones_contables_empleado.ibc_icbf AS ibc_icbf,
                job_transacciones_contables_empleado.ibc_caja_compensacion AS ibc_caja_compensacion,
                job_transacciones_contables_empleado.ibc_sena AS ibc_sena,
                job_transacciones_contables_empleado.codigo_concepto_transaccion_contable AS concepto_contable,
                job_transacciones_contables_empleado.columna_planilla AS columna_planilla,
                0 AS codigo_transaccion_tiempo,
                0 AS resta_salario,
                0 AS resta_auxilio_transporte,
                0 AS resta_cesantias,
                0 AS resta_prima,
                0 AS resta_vacaciones,
                0 AS extras_empleado,
                0 AS concepto_tiempo,
                (job_movimientos_nomina_migracion.documento_identidad_empleado*1) AS documento_identidad_empleado,
                job_movimientos_nomina_migracion.codigo_contable AS codigo_contable,
                job_movimientos_nomina_migracion.sentido AS sentido,
                job_movimientos_nomina_migracion.codigo_empresa_auxiliar AS codigo_empresa_auxiliar,
                job_movimientos_nomina_migracion.codigo_anexo_contable AS codigo_anexo_contable,
                job_movimientos_nomina_migracion.codigo_auxiliar_contable AS codigo_auxiliar_contable,
                job_movimientos_nomina_migracion.valor_movimiento AS valor_movimiento,
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
                CONCAT(
                    IF(job_terceros.primer_nombre IS NOT NULL,
                        CONCAT(
                            CONCAT(job_terceros.primer_apellido,' '),
                            IF(job_terceros.segundo_apellido IS NOT NULL,CONCAT(job_terceros.segundo_apellido,' '),' '),
                            CONCAT(job_terceros.primer_nombre,' '),
                            IF(job_terceros.segundo_nombre IS NOT NULL,CONCAT(job_terceros.segundo_nombre,' '),'')
                        ),
                        job_terceros.razon_social
                    )
                ) AS apellido_empleado,
                job_departamentos_empresa.codigo AS codigo_departamento_empresa,
                job_departamentos_empresa.nombre AS departamento_empresa,
                job_sucursal_contrato_empleados.salario_mensual AS salario_empleado,
                job_sucursal_contrato_empleados.codigo_empresa AS codigo_empresa,
                job_sucursal_contrato_empleados.codigo_sucursal AS codigo_sucursal,
                job_sucursal_contrato_empleados.fecha_ingreso AS fecha_ingreso_empresa,
                job_sucursal_contrato_empleados.fecha_ingreso_sucursal AS fecha_ingreso_sucursal,
                job_movimientos_nomina_migracion.contabilizado AS contabilizado,
                0 AS consecutivo,
                '' AS fecha_incapacidad,
                job_movimientos_nomina_migracion.fecha_registro AS fecha_registro,
                job_movimientos_nomina_migracion.fecha_pago_planilla AS fecha_pago_planilla,
                '' AS obligacion
            FROM
                job_movimientos_nomina_migracion,
                job_departamento_seccion_contrato_empleado,
                job_departamentos_empresa,
                job_sucursal_contrato_empleados,
                job_terceros,
                job_transacciones_contables_empleado
            WHERE
                job_movimientos_nomina_migracion.documento_identidad_empleado = job_terceros.documento_identidad AND
                job_movimientos_nomina_migracion.codigo_empresa = job_sucursal_contrato_empleados.codigo_empresa AND
                job_movimientos_nomina_migracion.documento_identidad_empleado = job_sucursal_contrato_empleados.documento_identidad_empleado AND
                job_movimientos_nomina_migracion.fecha_ingreso_empresa = job_sucursal_contrato_empleados.fecha_ingreso AND
                job_movimientos_nomina_migracion.codigo_sucursal = job_sucursal_contrato_empleados.codigo_sucursal AND
                job_movimientos_nomina_migracion.fecha_ingreso_sucursal = job_sucursal_contrato_empleados.fecha_ingreso_sucursal AND
                job_departamento_seccion_contrato_empleado.codigo_empresa = job_sucursal_contrato_empleados.codigo_empresa AND
                job_departamento_seccion_contrato_empleado.documento_identidad_empleado = job_sucursal_contrato_empleados.documento_identidad_empleado AND
                job_departamento_seccion_contrato_empleado.fecha_ingreso = job_sucursal_contrato_empleados.fecha_ingreso AND
                job_departamento_seccion_contrato_empleado.codigo_sucursal = job_sucursal_contrato_empleados.codigo_sucursal AND
                job_departamento_seccion_contrato_empleado.fecha_ingreso_sucursal = job_sucursal_contrato_empleados.fecha_ingreso_sucursal AND
                job_departamento_seccion_contrato_empleado.codigo_departamento_empresa = job_departamentos_empresa.codigo AND
                job_movimientos_nomina_migracion.codigo_transaccion_contable = job_transacciones_contables_empleado.codigo
        )"
    )
);
?>

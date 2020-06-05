<?php
    function movimientos_reporte_incapacidades($documento_empleado,$codigo_sucursal,$codigo_empresa,$tipo_acumula,$fecha_inicial_transaccion,$fecha_final_transaccion)
    {
        $datos_incapacidades_mes = array();
        ///////Sumatoria de incapacidades que acumulen para primas////////////
        $codigo_empresa = SQL::obtenerValor("sucursales","codigo_empresa","codigo='$codigo_sucursal'");
        $condicion_incapacidad = "codigo_empresa='$codigo_empresa' AND codigo_sucursal='$codigo_sucursal' AND documento_identidad_empleado='$documento_empleado' AND fecha_incapacidad>='$fecha_inicial_transaccion' AND fecha_incapacidad<='$fecha_final_transaccion'";
        $consulta_movimiento_incapacidades =  SQL::seleccionar(array("reporte_incapacidades"),array("*"),$condicion_incapacidad);

        while($datos_movimineto_incapacidas = SQL::filaEnObjeto($consulta_movimiento_incapacidades)){
            $transaccion_contable = $datos_movimineto_incapacidas->codigo_transaccion_contable;
            $acumula = SQL::obtenerValor("transacciones_contables_empleado",$tipo_acumula,"codigo='$transaccion_contable'");
            //echo var_dump($acumula_cesantias);
            if($acumula == '1'){

                if(!isset($datos_incapacidades_mes[(int)$datos_movimineto_incapacidas->mes_generacion])){
                    $datos_incapacidades_mes[(int)$datos_movimineto_incapacidas->mes_generacion] = $datos_movimineto_incapacidas->valor_movimiento;
                }else{
                    $datos_incapacidades_mes[(int)$datos_movimineto_incapacidas->mes_generacion] += $datos_movimineto_incapacidas->valor_movimiento;
                }
            }
        }
        //////////////////////////////////////////////////////////////////////////
        return $datos_incapacidades_mes;
    }

    function movimientos_tiempos_laborados($documento_empleado,$codigo_sucursal,$codigo_empresa,$tipo_acumula,$fecha_inicial_transaccion,$fecha_final_transaccion)
    {
        $datos_tiempo_laborados_mes = array();
        /////// Sumatoria tiempos laborados que acumulan para Cesantias ////////////
        $codigo_empresa = SQL::obtenerValor("sucursales","codigo_empresa","codigo='$codigo_sucursal'");
        $condicion_tiempo = "codigo_empresa='$codigo_empresa' AND codigo_sucursal='$codigo_sucursal' AND documento_identidad_empleado='$documento_empleado' AND fecha_inicio>='$fecha_inicial_transaccion' AND fecha_inicio<='$fecha_final_transaccion'";
        $consulta_movimiento_tiempo =  SQL::seleccionar(array("movimiento_tiempos_laborados"),array("*"),$condicion_tiempo);
        
        while($datos_movimiento_tiempo = SQL::filaEnObjeto($consulta_movimiento_tiempo)){

            $transaccion_contable = $datos_movimiento_tiempo->codigo_transaccion_contable;
            $transaccion_tiempo   = $datos_movimiento_tiempo->codigo_transaccion_tiempo;
            $codigo_concepto_transaccion_tiempo = SQL::obtenerValor("transacciones_tiempo","codigo_concepto_transaccion_tiempo","codigo='$transaccion_tiempo'");
            $acumula = SQL::obtenerValor("transacciones_contables_empleado",$tipo_acumula,"codigo='$transaccion_contable'");
             //echo var_dump($codigo_concepto_transaccion_tiempo);
            if($acumula == '1' && $codigo_concepto_transaccion_tiempo!='1'){
               
                if(!isset($datos_tiempo_laborados_mes[(int)$datos_movimiento_tiempo->mes_generacion])){
                    $datos_tiempo_laborados_mes[(int)$datos_movimiento_tiempo->mes_generacion] = $datos_movimiento_tiempo->valor_hora_recargo;
                }else{
                    $datos_tiempo_laborados_mes[(int)$datos_movimiento_tiempo->mes_generacion] += $datos_movimiento_tiempo->valor_hora_recargo;
                }
            }
        }
        
        return $datos_tiempo_laborados_mes;
    }
    ////////Determino la cantidad de dias dentro de un rango/////////////
    function determinoRangoDias($fecha_liquidacion,$fecha_ingreso_empleado)
    {
        $informacion_fecha  = getdate(strtotime($fecha_liquidacion));
        $mes_numero       = (int)$informacion_fecha['mon'];
        $dia_inicio       = 0;
        $total_dias_trabajados = 0;

        $fecha_liquidacion_tiempo = strtotime($fecha_liquidacion);
        $areglo_liquidacion = explode('-',$fecha_liquidacion);
        $anio_liquidacion = $areglo_liquidacion[0];
        
        $fecha_primero_enero      = strtotime($anio_liquidacion.'-01-01');


        if(strtotime($fecha_ingreso_empleado) <=  $fecha_primero_enero){

            for($i=1;$i<$mes_numero;$i++){
               $dias_trabajados_mes[$i] = 30;
            }

            $dias_trabajados_mes[$mes_numero] = $informacion_fecha['mday'];
            if($informacion_fecha['mday']=='31'){
                $dias_trabajados_mes[$mes_numero] -=1;
            }
            $dia_inicio = 1;
            $numero_entradas = count($dias_trabajados_mes);
        }else{
            $informacion_fecha_inicio  = getdate(strtotime($fecha_ingreso_empleado));
            $mes_numero_fecha_inicio   = (int)$informacion_fecha_inicio['mon'];
            $dia_inicio = $mes_numero_fecha_inicio;

            for($i=$mes_numero_fecha_inicio;$i<$mes_numero;$i++){
               $dias_trabajados_mes[$i] = 30;
            }
            $dias_trabajados_mes[$mes_numero] = $informacion_fecha['mday'];

            if($informacion_fecha_inicio['mday']!=31 || $informacion_fecha_inicio['mday']!=30){
                $dias_trabajados_mes[$mes_numero_fecha_inicio] -= $informacion_fecha_inicio['mday'];
            }else{
                $dias_trabajados_mes[$mes_numero_fecha_inicio] = 1;
            }

            $numero_entradas = (count($dias_trabajados_mes)+$dia_inicio-1);
        }

        for($i=$dia_inicio; $i<=$numero_entradas;$i++){

            $total_dias_trabajados += $dias_trabajados_mes[$i];
        }

        return $total_dias_trabajados;
    }

?>

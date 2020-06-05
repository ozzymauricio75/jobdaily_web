<?php

function determinarDatosVacacion($forma_fecha_inicial,$forma_dias_no_laborados){
    $contador        = 1;
    $dias_no_habiles = 0;
    $fecha_generada = $forma_fecha_inicial;
    /////Determino los dias no habiles/////////
    while($contador<(int)$forma_dias_no_laborados){
        $existe_dominical =  SQL::existeItem("domingos_festivos","fecha",$fecha_generada);
         if(!$existe_dominical){
              $contador++;
         }else{$dias_no_habiles++;}
         $date_actual    = getdate(strtotime($fecha_generada));
         $fecha_generada = date("Y-m-d", mktime(($date_actual["hours"]), ($date_actual["minutes"]), ($date_actual["seconds"]), ($date_actual["mon"]), ($date_actual["mday"]+1), ($date_actual["year"])));

    }
    $respuesta = array();
    $respuesta[] = $fecha_generada;
    $respuesta[] = $dias_no_habiles+(int)$forma_dias_no_laborados;
    //echo var_dump($respuesta);
    return $respuesta;
}

function determinarEstaRangoFecha($codigo_sucursal,$forma_fecha_inicial,$forma_dias_no_laborados){
    $contador        = 1;
    $respuesta       =  false;
    $fecha_generada = $forma_fecha_inicial;
    /////Determino los dias no habiles/////////
    while($contador<=(int)$forma_dias_no_laborados){
        $condicion = "('$fecha_generada' BETWEEN fecha_inicio_tiempo AND fecha_final_tiempo)";
        $existe    =  SQL::existeItem("movimiento_liquidacion_vacaciones","codigo_sucursal",$codigo_sucursal,$condicion);
        //echo var_dump($fecha_generada);
        if($existe){
              $respuesta = true;
              break;
         }
         $date_actual    = getdate(strtotime($fecha_generada));
         $fecha_generada = date("Y-m-d", mktime(($date_actual["hours"]), ($date_actual["minutes"]), ($date_actual["seconds"]), ($date_actual["mon"]), ($date_actual["mday"]+1), ($date_actual["year"])));
         $contador++;

    }
  
    return $respuesta;
}

function calculoVacaciones($codigo_empresa,$documento_empleado,$fecha_ingreso_empleado,$codigo_sucursal,$url_fecha_liquidacion,$salario_base,$manejo_auxilio_transporte){
    $dias_año           = 360;
    ////////////////////////////////
    $dias_año = 360;
       ///Determino periodo de pago : primer semetres o segundo semestre///
    $fecha_liquidacion = $url_fecha_liquidacion;
    $fecha_liquidacion = explode("-",$fecha_liquidacion);
    $año_liquidacion   = $fecha_liquidacion[0];

    $fecha_liquidacion_tiempo = strtotime($url_fecha_liquidacion);
    $fecha_primero_enero      = strtotime($año_liquidacion.'-01-01');
    //////Datos Contrato Empleado///////
    $fecha_ingreso_empleado = $fecha_ingreso_empleado;
    $salario_base           = $salario_base;

    $informacion_fecha  = getdate(strtotime($url_fecha_liquidacion));
    $mes_numero         = (int)$informacion_fecha['mon'];
    $dia_inicio         = 0;
    $fecha_inicio_calculo     = $fecha_ingreso_empleado;
    ////////////Verifico que movimientos acumulan para prima de servicio/////////////
    $datos_movimientos        = determinarTransaccionAcumulan("acumula_vacaciones",$codigo_empresa,$codigo_sucursal,$documento_empleado,$url_fecha_liquidacion,$fecha_inicio_calculo,$salario_base,$manejo_auxilio_transporte);
    $total_dias_trabajados    = $datos_movimientos[0];
    $auxilio_transporte_total = $datos_movimientos[1];
    $promedio_movimientos     = $datos_movimientos[2];
    /////////////////////////////////////////////////////////////////////////////////
    $dias_tomados     = 0;
    $salario_base     = $salario_base + $auxilio_transporte_total + $promedio_movimientos;
    $dias_liquidar    = (($total_dias_trabajados * 15)/ 360) - $dias_tomados;
    $varlor_dia       = $salario_base/30;
    $valor_vacaciones = $varlor_dia * $dias_liquidar;

    $respuesta = array();

    $respuesta[] = $promedio_movimientos;
    $respuesta[] = $auxilio_transporte_total;
    $respuesta[] = $fecha_inicio_calculo;
    $respuesta[] = $total_dias_trabajados;
    $respuesta[] = $salario_base;
    $respuesta[] = round($valor_vacaciones);
    $respuesta[] = $documento_empleado;
    $respuesta[] = "";
    $respuesta[] = "2";

    return $respuesta;
}

function generarInformacionEmpleado($url_fecha_liquidacion,$url_documento_empleado){

    $fecha_arreglo     = explode('-',$url_fecha_liquidacion);
    $anio              = $fecha_arreglo[0];
    $fecha_comparacion = $anio.'-01-01';
    $condicion_ingreso                    = "documento_identidad_empleado='$url_documento_empleado' AND estado='1'"; //codigo_empresa= AND

    $consulta_ingreso_contrato            = SQL::seleccionar(array("ingreso_empleados"),array("*"),$condicion_ingreso);
    $datos_ingreso_contrato               = SQL::filaEnObjeto($consulta_ingreso_contrato);
    $ultmos_tres_salario_fijos            = array();
    $salario_fijo                         = true;
    $valor_salario_fijo                   = 0;

    $condicion_sucursal_contrato          = "documento_identidad_empleado='$url_documento_empleado' AND codigo_empresa='$datos_ingreso_contrato->codigo_empresa'";
    $consulta_sucursal_contrato_empleados = SQL::seleccionar(array("sucursal_contrato_empleados"),array("*"),$condicion_sucursal_contrato, "", "fecha_ingreso_sucursal DESC",0, 1);
    $datos_sucursal_contrato_empleados    = SQL::filaEnObjeto($consulta_sucursal_contrato_empleados);
    /////////Determino si ha habido variacion de salario/////////
    $meses_anterior  = 3;
    $fecha_tiempo    = getdate(strtotime($url_fecha_liquidacion));
    $fecha_resultado = date("Y-m-d", mktime(($fecha_tiempo["hours"]),($fecha_tiempo["minutes"]),($fecha_tiempo["seconds"]),($fecha_tiempo["mon"]-$meses_anterior),($fecha_tiempo["mday"]),($fecha_tiempo["year"])));

    $condicion_extra = " AND fecha_salario>='$fecha_resultado' AND fecha_salario<='$url_fecha_liquidacion' AND fecha_salario>='$fecha_comparacion'";

    $consulta_sucursal_variacion_salarial = SQL::seleccionar(array("consulta_contrato_empleado"),array("salario"),$condicion_sucursal_contrato.$condicion_extra,"", "fecha_ingreso_sucursal,fecha_salario DESC",0, 3);

    while($datos_sucursal_variacion_salarial = SQL::filaEnObjeto($consulta_sucursal_variacion_salarial))
    {
        $ultmos_tres_salario_fijos[] = $datos_sucursal_variacion_salarial->salario;
    }
    $tamanio_arreglo = count($ultmos_tres_salario_fijos);

    if($tamanio_arreglo >= 1 && $tamanio_arreglo <= 3){
       for($i=1;$i<$tamanio_arreglo;$i++){
            if($ultmos_tres_salario_fijos[0]!=$ultmos_tres_salario_fijos[$i]){
                $salario_fijo = false;
                break;
            }
        }
    }
    ///////////////////////////////////////////////////
    if(!$salario_fijo){
        $consulta_sucursal_variacion_salarial = SQL::seleccionar(array("consulta_contrato_empleado"),array("salario"),$condicion_sucursal_contrato." AND fecha_salario>='$fecha_comparacion'");
        $sumatoria_salarial_anio_actual = 0;
        $contador_salarios              = 0;

        while($datos_sucursal_variacion_salarial = SQL::filaEnObjeto($consulta_sucursal_variacion_salarial))
        {
            $sumatoria_salarial_anio_actual += $datos_sucursal_variacion_salarial->salario;
            $contador_salarios++;
        }

        $valor_salario_fijo = round($sumatoria_salarial_anio_actual/$contador_salarios);

    }else{
        $valor_salario_fijo =  SQL::obtenerValor("consulta_contrato_empleado","salario",$condicion_sucursal_contrato." AND fecha_salario <='$url_fecha_liquidacion' ORDER BY fecha_ingreso_sucursal,fecha_salario DESC LIMIT 0,1");
        $valor_salario_fijo = round($valor_salario_fijo);
    }

    $codigo_empresa    = SQL::obtenerValor("sucursales","codigo_empresa","codigo='$datos_sucursal_contrato_empleados->codigo_sucursal'");

    $consulta_contrato_empleado = SQL::seleccionar(array("consulta_contrato_empleado"), array("*"),"codigo_empresa='$codigo_empresa' AND documento_identidad_empleado='$url_documento_empleado'  AND fecha_ingreso_sucursal <= '$url_fecha_liquidacion' AND estado= '1' ","","fecha_ingreso_sucursal,fecha_inicia_departamento_seccion DESC",0,1); //AND codigo_sucursal='$datos_sucursal_contrato_empleados->codigo_sucursal'
    $datos_contrato_empleado    = SQL::filaEnObjeto($consulta_contrato_empleado);
  
    $tipo_contratacion = SQL::obtenerValor("tipos_contrato","tipo_contratacion","codigo='$datos_contrato_empleado->codigo_tipo_contrato'");

    /////////////////////////////////////////////////////////////
    $respuesta = array();
    $respuesta[] = $datos_sucursal_contrato_empleados->fecha_ingreso;
    $respuesta[] = $valor_salario_fijo;
    $respuesta[] = $datos_ingreso_contrato->manejo_auxilio_transporte ;
    $respuesta[] = $tipo_contratacion;
    $respuesta[] = $url_documento_empleado;

    return $respuesta;
}

/////////////Funcion que permite calcular que transacciones acumula para la prestaciones sociales
function determinarTransaccionAcumulan($tipo_acumula,$codigo_empresa,$codigo_sucursal,$documento_empleado,$fecha_liquidacion,$fecha_inicio_calculo,$salario_base,$manejo_auxilio_transporte){

    $total_dias_trabajados      = rangoDias($fecha_liquidacion,$fecha_inicio_calculo);
    $total_dias_calculo_auxilio = $total_dias_trabajados;
    $total_dias_descontar       = 0;

    $condicion_movimientos = "codigo_empresa='$codigo_empresa' AND codigo_sucursal='$codigo_sucursal' AND documento_identidad_empleado='$documento_empleado'";// AND contabilizado = '0'

    $valor_total_movimientos = 0;
    $consulta_movimientos_rango   = SQL::seleccionar(array("consulta_datos_planilla"),array("*"),$condicion_movimientos." AND (fecha_pago_planilla BETWEEN '$fecha_inicio_calculo' AND '$fecha_liquidacion')");
    $consulta_movimientos_periodo = SQL::seleccionar(array("consulta_datos_planilla"),array("*"),$condicion_movimientos." AND (fecha_pago_planilla BETWEEN '$fecha_inicio_calculo' AND '$fecha_liquidacion')");

    $tablas_movimientos = array(
        "job_movimiento_tiempos_laborados",
        "job_reporte_incapacidades",
        "job_movimiento_control_prestamos_empleados",
        "job_movimiento_tiempos_no_laborados_dias",
        "job_movimiento_tiempos_no_laborados_horas",
        "job_movimiento_novedades_manuales"
    );

    $tablas_movimientos_restan_dias = array(
        "job_reporte_incapacidades",
        "job_movimiento_tiempos_no_laborados_dias",
    );

    while($datos_movimientos = SQL::filaEnObjeto($consulta_movimientos_rango)){
        if(in_array($datos_movimientos->nombre_tabla,$tablas_movimientos)){
            //echo var_dump($datos_movimientos->nombre_tabla);
            $codigo_transaccion_contable = $datos_movimientos->codigo_transaccion_contable;
            $acumula = SQL::obtenerValor("transacciones_contables_empleado",$tipo_acumula,"codigo='$codigo_transaccion_contable'");
            if($acumula=='1'){
                //echo var_dump((int)$datos_movimientos->valor_movimiento);
                $valor_total_movimientos += $datos_movimientos->valor_movimiento;
            }
        }
    }
    while($datos_movimientos = SQL::filaEnObjeto($consulta_movimientos_periodo)){
        if(in_array($datos_movimientos->nombre_tabla,$tablas_movimientos_restan_dias)){
            $total_dias_descontar ++;
        }
    }

    if(($manejo_auxilio_transporte=='1' || $manejo_auxilio_transporte=='3')){
       $total_dias_calculo_auxilio -= $total_dias_descontar;
    }

    $auxilio_transporte_total = determinarAuxilioTransporte($fecha_liquidacion,$salario_base,$manejo_auxilio_transporte,$total_dias_calculo_auxilio);

    $respuesta   = array();
    $respuesta[] = ($total_dias_trabajados-$total_dias_descontar);
    $respuesta[] = $auxilio_transporte_total;
    $respuesta[] = ($valor_total_movimientos*30/$total_dias_trabajados);

    return $respuesta;
}

////////////////////Calculo del auxilio de transporte//////////////
function determinarAuxilioTransporte($fecha_liquidacion,$salario_base,$manejo_auxilio_transporte,$total_dias_trabajados){
    $auxilio_transporte_total =0;
    $valor_auxilio = SQL::obtenerValor("auxilio_transporte","valor","fecha <= '$fecha_liquidacion' ORDER BY fecha DESC LIMIT 0,1");
    $salario_minimo_actual  = SQL::obtenerValor("salario_minimo", "valor", "codigo!=0 ORDER BY fecha DESC LIMIT 1");
    if(($salario_minimo_actual*2) >= $salario_base){
        if($manejo_auxilio_transporte !='5'){
           //$auxilio_transporte_total = ($valor_auxilio * $total_dias_trabajados)/30;
           $auxilio_transporte_total = $valor_auxilio;
        }
    }
    return $auxilio_transporte_total;
}

//////////////////Determino el rango de dias dependiendo
function rangoDias($fecha_liquidacion,$fecha_ingreso_empleado){

    $total_dias_trabajados = 0;
    $dias_finales_meses = array("31","30","29","28");
    ///////Parametro AÑO CALNEDARIO//////////
    $dias_ano = 0;
    if($dias_ano){
        $total_dias_trabajados = (strtotime($fecha_liquidacion) - strtotime($fecha_ingreso_empleado))/(60*60*24);
    }else{

        //$fecha_liquidacion = str_replace("30","31",$fecha_liquidacion);

        $datos_mes = 0;
        $fecha_generada = $fecha_ingreso_empleado;

        $estado       = true;
        while($estado){
            $informacion_liquidacion = getdate(strtotime($fecha_generada));
            $fecha_generada   = date("Y-m-d", mktime($informacion_liquidacion["hours"],$informacion_liquidacion["minutes"],$informacion_liquidacion["seconds"],($informacion_liquidacion["mon"]+1),($informacion_liquidacion["mday"]),($informacion_liquidacion["year"])));
            $fecha_comparacion = date("Y-m-",strtotime($fecha_generada))."30";
            //echo var_dump($fecha_comparacion);
            if(strtotime($fecha_comparacion) >= strtotime($fecha_liquidacion)){
                //echo var_dump("ENTRO-1");
                $estado = false;
            }else{
                //echo var_dump("ENTRO-2");
                $datos_mes +=30;
            }
        }
        if(!(date("Y-m",strtotime($fecha_ingreso_empleado))==date("Y-m",strtotime($fecha_liquidacion)))){

            if(date("t",strtotime($fecha_ingreso_empleado))==date("d",strtotime($fecha_ingreso_empleado))){
                $datos_mes +=30;
            }else{
                $datos_mes +=30-(int)date("d",strtotime($fecha_ingreso_empleado));
            }

            if(date("d",strtotime($fecha_liquidacion))!=31){
                if(date("t",strtotime($fecha_liquidacion))==date("d",strtotime($fecha_liquidacion))){

                    $datos_mes +=30;
                }else{

                    $datos_mes +=(int)date("d",strtotime($fecha_liquidacion));
                }
            }

        }else{

            $datos_mes = ((strtotime($fecha_liquidacion) - strtotime($fecha_ingreso_empleado))/(60*60*24));
        }
    }

   $datos_mes++;
   return $datos_mes;
}

function calculoLiquidacionVacaciones($url_fecha_liquidacion,$url_documento_empleado,$url_codigo_sucursal,$url_dias_tomados,$url_forma_liquidacion,$textos,$cargar_tabla){
    
    $valor_descontar_cuotas_pagadas = 0;
    $valor_prestamo                 = 0;
    $dias_totales                   = 0;
    $salario_mensual                = 0;
    $auxilio_transporte_pendiente   = 0;
    $valor_salud_pendiente          = 0;
    $valor_pension_pendiente        = 0;
    $valor_horas_extras_pendiente   = 0;
    $horas_tiempo_aportan_pension   = 0;
    $horas_tiempo_aportan_salud     = 0;

    $dias_totales_inacapacidad      = 0;


    $informacion_empleado = generarInformacionEmpleado($url_fecha_liquidacion,$url_documento_empleado);

    $fecha_ingreso_empleado     = $informacion_empleado[0];
    $url_fecha_ingreso_empleado = $fecha_ingreso_empleado;
    $salario_base               = $informacion_empleado[1];
    $manejo_auxilio_transporte  = $informacion_empleado[2];
    //////////////////////////////////////////////////////////////////////////////
    $condicion =  "documento_identidad_empleado='$url_documento_empleado' ORDER BY fecha_pago_planilla DESC LIMIT 0,1"; // AND contabilizado='1'
    /////////////////////////////////////////////////////////////////////
    $ultima_fecha_pago = SQL::obtenerValor("movimientos_salarios","fecha_pago_planilla", $condicion);

    ///////Calculo de dias con incapacidad////////////
    $codigo_empresa = SQL::obtenerValor("sucursales","codigo_empresa","codigo='$url_codigo_sucursal'");
    $condicion_incapacidad = "codigo_empresa='$codigo_empresa' AND codigo_sucursal='$url_codigo_sucursal' AND documento_identidad_empleado='$url_documento_empleado'";// AND contabilizado = '0'
    $consulta_movimiento_incapacidades =  SQL::seleccionar(array("reporte_incapacidades"),array("*"),$condicion_incapacidad);
    $dias_totales_inacapacidad = SQL::filasDevueltas($consulta_movimiento_incapacidades);
    //echo var_dump($ultima_fecha_pago);
    if($ultima_fecha_pago){
       if(strtotime($ultima_fecha_pago)>=strtotime($url_fecha_liquidacion)){
           $url_fecha_liquidacion = $ultima_fecha_pago;
       }
       $fecha_diferencia = strtotime($url_fecha_liquidacion)-strtotime($ultima_fecha_pago); //Hallo la diferencia de las fechas en segundos
       $dias_totales = $fecha_diferencia / (60 * 60 * 24); //Convierto la diferencia en dias
       $fecha_calcular = $ultima_fecha_pago;
    }else{
       $fecha_diferencia = strtotime($url_fecha_liquidacion)-strtotime($url_fecha_ingreso_empleado); //Hallo la diferencia de las fechas en segundos
       $dias_totales = $fecha_diferencia / (60 * 60 * 24); //Convierto la diferencia en dias
       $fecha_calcular = $url_fecha_ingreso_empleado;
    }

    $valor_auxilio = SQL::obtenerValor("auxilio_transporte","valor","fecha <= '$url_fecha_liquidacion' ORDER BY fecha DESC LIMIT 0,1");
    $valor_salario_minimo = SQL::obtenerValor("salario_minimo","valor","fecha <= '$url_fecha_liquidacion' ORDER BY fecha DESC LIMIT 0,1");

    //echo var_dump($dias_totales_inacapacidad);
    $salario_pendiente = ($salario_base * $dias_totales)/30;
    //echo var_dump($manejo_auxilio_transporte);
    if ($manejo_auxilio_transporte != '5') {
            if ($manejo_auxilio_transporte == '3' || $manejo_auxilio_transporte == '1') {
                $auxilio_transporte_pendiente = ($valor_auxilio * ($dias_totales - $dias_totales_inacapacidad)) / 30;
            } else {
                $auxilio_transporte_pendiente = ($valor_auxilio * $dias_totales) / 30;
            }
    }

   //////////Moviminetos tiempo laborados//////////
    
   $consulta_tiempo_laborados = SQL::seleccionar(array("movimiento_tiempos_laborados"),array("*"),"documento_identidad_empleado='$url_documento_empleado' AND contabilizado !='2' AND fecha_inicio<='$url_fecha_liquidacion'");
    
   //echo var_dump(SQL::filaEnObjeto($consulta_tiempo_laborados));
   while($datos_tiempos = SQL::filaEnObjeto($consulta_tiempo_laborados)){
       // echo var_dump($datos_tiempos);
       $codigo_concepto_transaccion_tiempo = SQL::obtenerValor("transacciones_tiempo","codigo_concepto_transaccion_tiempo","codigo='$datos_tiempos->codigo_transaccion_tiempo'");

       $ibc_pension = SQL::obtenerValor("transacciones_contables_empleado","ibc_pension","codigo ='$datos_tiempos->codigo_transaccion_contable'");
       $ibc_salud = SQL::obtenerValor("transacciones_contables_empleado","ibc_salud","codigo ='$datos_tiempos->codigo_transaccion_contable'");

       if($codigo_concepto_transaccion_tiempo!='1'){ ///Que su concepto sea diferente de horas normales
           if($ibc_pension == '1'){
               $horas_tiempo_aportan_pension = $datos_tiempos->valor_hora_recargo;
           }
           if($ibc_salud == '1'){
               $horas_tiempo_aportan_salud   = $datos_tiempos->valor_hora_recargo;
           }

           $valor_horas_extras_pendiente += $datos_tiempos->valor_movimiento ;
       }
   }
   ////////////////////////////////////////////////
    $valor_ibc_salud = $salario_pendiente+$auxilio_transporte_pendiente+$horas_tiempo_aportan_salud;
    $valor_ibc_pension = $salario_pendiente+$auxilio_transporte_pendiente+$horas_tiempo_aportan_pension;
    //////////Pension///////////////
    $pension  = SQL::obtenerValor("aspirantes", "pensionado", "documento_identidad = '".$url_documento_empleado."'");
    if($pension == '0'){
       $codigo_tasa_pension     = SQL::obtenerValor("preferencias","valor","variable='tasa_pension' AND tipo_preferencia=1");
       $tasa_pension            = SQL::obtenerValor("vigencia_tasas","porcentaje","codigo_tasa = '".$codigo_tasa_pension."'","","fecha DESC",0,1);
       $valor_pension_pendiente = ($valor_ibc_pension*$tasa_pension)/100;
    }
    ////////////Salud///////////////
    $codigo_tasa_salud = SQL::obtenerValor("preferencias","valor","variable='tasa_salud' AND tipo_preferencia=1");
    $tasa_salud = SQL::obtenerValor("vigencia_tasas","porcentaje","codigo_tasa= '".$codigo_tasa_salud."'","","fecha DESC",0,1);
    $valor_salud_pendiente = ($valor_ibc_salud*$tasa_salud)/100;


    $codigo_empresa     = SQL::obtenerValor("sucursales","codigo_empresa","codigo='$url_codigo_sucursal'");
    $calculo_vacaciones = calculoVacaciones($codigo_empresa,$url_documento_empleado,$fecha_ingreso_empleado,$url_codigo_sucursal,$url_fecha_liquidacion,$salario_base,$manejo_auxilio_transporte);
    $contenido          = determinarDatosVacacion($url_fecha_liquidacion,$url_dias_tomados);
    $valor_vacaciones   = ($calculo_vacaciones[4]/30) * (int)$contenido[1] ;

    $respuesta = array();
    $sumatoria_devengos    =  $valor_vacaciones+$salario_pendiente+$auxilio_transporte_pendiente+$valor_horas_extras_pendiente;
    $sumatoria_deducciones =  $valor_salud_pendiente+$valor_pension_pendiente;

    $total_liquidacion = $sumatoria_devengos - $sumatoria_deducciones;
    if($cargar_tabla){
        if($url_forma_liquidacion=="2"){
            $respuesta[] = $textos["TITULO_PAGOS"]."| |".$textos["VACACIONES"]."|".round($valor_vacaciones)."|".$textos["SALARIO"]."|".round($salario_pendiente)."|".$textos["AUXILIO_TRANSPORTE"]."|".round($auxilio_transporte_pendiente)."|".$textos["HORAS_EXTRAS"]."|".round($valor_horas_extras_pendiente)."|".$textos["TITULO_DEVENGO"]."|".round($sumatoria_devengos);
            $respuesta[] = $textos["TITULO_DESCUENTOS"]."| |".$textos["SALUD"]."|".round($valor_salud_pendiente)."|".$textos["PENSION"]."|".round($valor_pension_pendiente)."|".$textos["TITULO_DEDUCCIONES"]."|".round($sumatoria_deducciones);
            $respuesta[] = $textos["TITULO_TOTAL_LIQUIDACION"]."|".round($total_liquidacion);
        }else{
            $respuesta[] = $textos["TITULO_PAGOS"]."| |".$textos["VACACIONES"]."|".round($valor_vacaciones);
            $respuesta[] = $textos["TITULO_TOTAL_LIQUIDACION"]."|".round($valor_vacaciones);
        }
    }else{
            $respuesta[] = "1|codigo_transaccion_salario|".round($salario_pendiente)."|".$fecha_calcular."|".$dias_totales."|".$dias_totales_inacapacidad."|0|0";
            $respuesta[] = "2|codigo_transaccion_auxilio_transporte|".round($auxilio_transporte_pendiente)."|".$fecha_calcular."|".$dias_totales."|".$dias_totales_inacapacidad."|0|0";
            $respuesta[] = "2|codigo_transaccion_salud|".round($valor_salud_pendiente)."|".$fecha_calcular."|".$dias_totales."|".$dias_totales_inacapacidad."|".$valor_ibc_salud."|".$tasa_salud;
            if($pension=="0"){
                $respuesta[] = "4|codigo_transaccion_pension|".round($valor_pension_pendiente)."|".$fecha_calcular."|".$dias_totales."|".$dias_totales_inacapacidad."|".$valor_ibc_salud."|".$tasa_salud;
            }
    }

    return $respuesta;
}

//determinar los dias tomados en el anio de acuerdo a la fecha de inicio o inicio del anio de trabajo
function diasTomadosAnio($fecha_ingreso,$fecha_liquidacion,$condicion){

    //$tipo_calendario    = 365;
    $fecha_inicia_anio = $fecha_ingreso;
    $continuar = true;

    while($continuar){
        $fecha_anterior    = $fecha_inicia_anio;
     
        //////////////////////////////////////////
        $fecha_inicia_anio = date("Y-m-d",strtotime("+1 year",strtotime($fecha_anterior)));
        if(strtotime($fecha_inicia_anio) > strtotime($fecha_liquidacion)){
            $fecha_inicio_anio = $fecha_anterior;
            $continuar = false;
        }
    }

    $fecha_final_anio = date("Y-m-d",strtotime("+1 year -1 day",strtotime($fecha_inicio_anio)));

    $condicion    .= " AND (fecha_inicio_tiempo BETWEEN '$fecha_inicio_anio' AND '$fecha_final_anio')";//
    $dias_tomados = SQL::obtenerValor("movimiento_liquidacion_vacaciones","sum(dias_tomados)",$condicion);
    //echo var_dump($dias_tomados);
    return $dias_tomados;

}


?>

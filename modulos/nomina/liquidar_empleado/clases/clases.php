<?php
function movimientos_reporte_incapacidades($documento_empleado,$codigo_sucursal,$codigo_empresa,$tipo_acumula,$fecha_inicial_transaccion,$fecha_final_transaccion){
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

////////////////Funcion que permite cargar la informacion del empleado//////////////////
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

    $consulta_contrato_empleado = SQL::seleccionar(array("consulta_contrato_empleado"), array("*"),"codigo_empresa='$codigo_empresa' AND documento_identidad_empleado='$url_documento_empleado' AND codigo_sucursal='$datos_sucursal_contrato_empleados->codigo_sucursal' AND fecha_ingreso_sucursal <= '$url_fecha_liquidacion' AND estado= '1' ","","fecha_ingreso_sucursal,fecha_inicia_departamento_seccion DESC",0,1);
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

////////////////////////CALCULO DEPRESTACIONES SOCIALES/////////////////////////////////////////////////
////////////////////////funcion que permite calcular la prima de servicio de un empleado////////////////
function calculoPrimaSevicio($codigo_empresa,$documento_empleado,$url_fecha_ingreso_empleado,$codigo_sucursal,$url_fecha_liquidacion,$salario_base,$manejo_auxilio_transporte){
   $dias_año = 360;
   ///Determino periodo de pago : primer semetres o segundo semestre///
   $fecha_liquidacion = $url_fecha_liquidacion;
   $fecha_liquidacion = explode("-",$fecha_liquidacion);
   $año_liquidacion   = $fecha_liquidacion[0];
   $valor_auxilio     = 0;
   $auxilio_transporte_pendiente = 0;

   $fecha_liquidacion_tiempo = strtotime($url_fecha_liquidacion);
   $fecha_primero_enero      = strtotime($año_liquidacion.'-01-01');
   $fecha_treinta_junio      = strtotime($año_liquidacion.'-06-30');

   $fecha_ingreso_empleado   = $url_fecha_ingreso_empleado;
   $dias_trabajados_mes = array();

    ///////////Determino fecha de inicio del periodo//////////////////
    if($fecha_liquidacion_tiempo>=$fecha_primero_enero && $fecha_liquidacion_tiempo<=$fecha_treinta_junio){
      $condicion = "documento_identidad_empleado='$documento_empleado' AND (mes_generacion BETWEEN 1 AND 6)";
      $fecha_primero = $fecha_primero_enero;
      $fecha_inicio_calculo = $año_liquidacion.'-01-01';

    }else{
      $condicion                    = "documento_identidad_empleado='$documento_empleado' AND (mes_generacion BETWEEN 7 AND 12)";
      $fecha_primero                = strtotime($año_liquidacion.'-07-01');
      $fecha_inicio_calculo         = $año_liquidacion.'-07-01';
    }
    if(strtotime($fecha_inicio_calculo) < strtotime($url_fecha_ingreso_empleado)){
        $fecha_inicio_calculo = $url_fecha_ingreso_empleado;
    }
    ////////////Verifico que movimientos acumulan para prima de servicio/////////////
    $datos_movimientos        = determinarTransaccionAcumulan("acumula_prima",$codigo_empresa,$codigo_sucursal,$documento_empleado,$url_fecha_liquidacion,$fecha_inicio_calculo,$salario_base,$manejo_auxilio_transporte);
    $total_dias_trabajados    = $datos_movimientos[0];
    $auxilio_transporte_total = $datos_movimientos[1];
    $promedio_movimientos     = $datos_movimientos[2];

    /////////////////////////////////////////////////////////////////////////////////
    //$promedio_movimientos = (($sumatoria_incapacidades_acumulan_primas+$sumatoria_tiempo_acumulan_prima)*30)/$total_dias_trabajados;
    $base_prima   = $salario_base + $auxilio_transporte_total + $promedio_movimientos;
    //$base_prima     = $salario_base;
    $prima_servicio = $base_prima * $total_dias_trabajados / $dias_año;

    $respuesta[] = $promedio_movimientos;
    $respuesta[] = $auxilio_transporte_total;
    $respuesta[] = $fecha_inicio_calculo; //fecha_inicio_primas
    $respuesta[] = $total_dias_trabajados;
    $respuesta[] = $base_prima;
    $respuesta[] = round($prima_servicio);
    $respuesta[] = $documento_empleado; //periodo
    $respuesta[] = "";
    $respuesta[] = "1";

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

//////////Determino las cesantias por trabajador//////////////
function calculoCesantias($codigo_empresa,$url_documento_empleado,$url_fecha_ingreso_empleado,$url_codigo_sucursal,$url_fecha_liquidacion,$url_salario_base,$manejo_auxilio_transporte,$tipo){

    $valor_total_cesantias  = 0;
    ////////Variables de movimientos//////
    $total_interes_cesantias = 0;
    $total_retiros_cesantias = 0;
    /////////////////////////////////
    ///////Variable Auxilio/////////
    $auxilio_transporte_total = 0;
      ////////////////////////////////
    $dias_año = 360;
       ///Determino periodo de pago : primer semetres o segundo semestre///
    $fecha_liquidacion = $url_fecha_liquidacion;
    $fecha_liquidacion = explode("-",$fecha_liquidacion);
    $año_liquidacion   = $fecha_liquidacion[0];

    $fecha_liquidacion_tiempo = strtotime($url_fecha_liquidacion);
    $fecha_primero_enero      = strtotime($año_liquidacion.'-01-01');

    $consulta_movimientos_salarios = SQL::seleccionar(array("consulta_datos_planilla"), array("*"), "documento_identidad_empleado='$url_documento_empleado' AND (tabla='1' OR tabla='6' OR tabla='4')");
    ////////consulto la fecha de pago de la planilla///////
    $total_dias_trabajados   = 0;
    $total_descontar_pago_cesantias = 0;
    //////Datos Contrato Empleado///////
    $fecha_ingreso_empleado = $url_fecha_ingreso_empleado;
    $salario_base           = $url_salario_base;

    $codigo_empresa = SQL::obtenerValor("sucursales","codigo_empresa","codigo='$url_codigo_sucursal'");

    $informacion_fecha  = getdate(strtotime($url_fecha_liquidacion));
    $mes_numero       = (int)$informacion_fecha['mon'];
    $dia_inicio       = 0;
    $fecha_inicio_calculo = $año_liquidacion.'-01-01';

    if(strtotime($fecha_inicio_calculo) < strtotime($url_fecha_ingreso_empleado)){
        $fecha_inicio_calculo = $url_fecha_ingreso_empleado;
    }
    ////////////Verifico que movimientos acumulan para prima de servicio/////////////
    $datos_movimientos        = determinarTransaccionAcumulan("acumula_cesantias",$codigo_empresa,$url_codigo_sucursal,$url_documento_empleado,$url_fecha_liquidacion,$fecha_inicio_calculo,$salario_base,$manejo_auxilio_transporte);
    $total_dias_trabajados    = $datos_movimientos[0];
    $auxilio_transporte_total = $datos_movimientos[1];
    $promedio_movimientos     = $datos_movimientos[2];
    /////////////////////////////////////////////////////////////////////////////////
    ///////////////////Retiro de cesantias////////////////////
    $consulta_retiro = SQL::seleccionar(array("retiro_cesantias"), array("*"),"documento_identidad_empleado='$url_documento_empleado'");
    while($datos_retiro = SQL::filaEnObjeto($consulta_retiro)){
        $total_retiros_cesantias += $datos_retiro->valor_retiro;
        //////Calculo de los dias de retiro del uttimo retiro///////////////
        $fecha_ultimo_retiro = $datos_retiro->fecha_generacion;
    }

    ////////////Determino de donde empeezo a tomar los retiros de cesantias/////////////
    if(strtotime($url_fecha_ingreso_empleado) <= $fecha_primero_enero){
        $fecha_comparacion = $año_liquidacion.'-01-01';
    }else{
        $fecha_comparacion = $url_fecha_ingreso_empleado;
    }

    $fecha_final_anio = $año_liquidacion.'-12-31';

    $valor_total_retiros = 0;
    $consulta_retiro = SQL::seleccionar(array("retiro_cesantias"), array("*")," codigo_empresa='$codigo_empresa' AND documento_identidad_empleado='$url_documento_empleado' AND fecha_liquidacion >= '$fecha_comparacion' AND fecha_liquidacion <= '$fecha_final_anio'");
    while($datos_retiro = SQL::filaEnObjeto($consulta_retiro)){
        $valor_total_retiros += $datos_retiro->valor_retiro;
    }

    $salario_base = $salario_base + $auxilio_transporte_total + $promedio_movimientos;
    $valor_total_cesantias = (($salario_base)*($total_dias_trabajados)/360)-$valor_total_retiros;
    $valor_total_interese_cesantia = ($valor_total_cesantias * $total_dias_trabajados * 0.12)/360;

    /////////////////////////////////////////////////////////
    if($valor_total_cesantias < 0 ){
        $valor_total_cesantias = 0;
        $valor_total_interese_cesantia = 0;
    }
    //////////////////////////////////////////////////////////
    $respuesta   = array();
    $respuesta[] = $promedio_movimientos;
    $respuesta[] = $auxilio_transporte_total;
    $respuesta[] = $fecha_inicio_calculo;
    $respuesta[] = $total_dias_trabajados;
    $respuesta[] = $salario_base;

    if($tipo=="3"){
        $respuesta[] = round($valor_total_cesantias);
        $respuesta[] = round($valor_total_interese_cesantia);
        $respuesta[] = $url_documento_empleado;
        $respuesta[] = "3";
    }else{
        $respuesta[] = round($valor_total_interese_cesantia);
        $respuesta[] = round($valor_total_interese_cesantia);
        $respuesta[] = $url_documento_empleado;
        $respuesta[] = "4";
    }

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

?>

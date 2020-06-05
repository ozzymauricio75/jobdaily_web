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
* modificarlo  bajo los terminos de la Licencia Publica General GNU
* publicada por la Fundacion para el Software Libre, ya sea la version 3
* de la Licencia, o (a su eleccion) cualquier version posterior.
*
* Este programa se distribuye con la esperanza de que sea util, pero
* SIN GARANTIA ALGUNA; ni siquiera la garantia implicita MERCANTIL o
* de APTITUD PARA UN PROPOSITO DETERMINADO. Consulte los detalles de
* la Licencia Publica General GNU para obtener una informacion mas
* detallada.
*
* Deberia haber recibido una copia de la Licencia Publica General GNU
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
**/

require("clases/clases.php");

$datos_movimiento           = array();
$datos_movimiento_valor     = array();
$datos_enviar               = array();
$valor_total_cesantias      = array();
$acumulado_transacciones    = 0;
$contador_tiempos_laborados = 0;

if(isset($url_informacion_empleado)){
    
    $respuesta = generarInformacionEmpleado($url_fecha_liquidacion,$url_documento_empleado);
    HTTP::enviarJSON($respuesta);
    exit;
}
////////////////Determino de sueldos por pagar//////////////////
if(isset($url_determino_salarios_pendientes)){
    
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

    $fecha_ingreso_empleado    = $url_fecha_ingreso_empleado;
    $salario_base              = $url_salario_base;
    $manejo_auxilio_transporte = $url_manejo_auxilio_transporte;
    //////////////////////////////////////////////////////////////////////////////
    $condicion =  "documento_identidad_empleado='$url_documento_empleado' ORDER BY fecha_pago_planilla DESC LIMIT 0,1"; // AND contabilizado='1'
    /////////////////////////////////////////////////////////////////////
    $ultima_fecha_pago = SQL::obtenerValor("movimientos_salarios","fecha_pago_planilla", $condicion);
    //echo var_dump($ultima_fecha_pago);
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

           $valor_horas_extras_pendiente += $datos_tiempos->valor_movimiento;
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

    $respuesta = array();
    $respuesta[] = round($salario_pendiente);
    $respuesta[] = round($auxilio_transporte_pendiente);
    $respuesta[] = round($valor_salud_pendiente);
    $respuesta[] = round($valor_pension_pendiente);
    $respuesta[] = round($valor_horas_extras_pendiente);

    $respuesta[] = $fecha_calcular; //fecha_inicio_pago
    $respuesta[] = $dias_totales; //dias_trabajados

    $respuesta[] = $dias_totales - $dias_totales_inacapacidad; //dias_auxilio

    $respuesta[] = $valor_ibc_salud;//ibc_salud
    $respuesta[] = $tasa_salud;//porcentaje_tasa_salud

    $respuesta[] = $pension;// parametro que indica si el empleado es pensionado
    $respuesta[] = $valor_ibc_salud;//ibc_pension
    $respuesta[] = $tasa_salud;//porcentaje_tasa_pension

    HTTP::enviarJSON($respuesta);
    exit;
}

if(isset($url_prestamos_empleado)){
   $valor_descontar_cuotas_pagadas = 0;
   $valor_prestamo                 = 0;
   $condicion =  "documento_identidad_empleado='$url_documento_empleado'"; // AND contabilizado='1'
   /////////////////////////////////////////////////////////////////////
   $consulta_movimientos_prestamos = SQL::seleccionar(array("vista_control_contrato"), array("*"),$condicion);
   while($datos_movimientos_prestamos = SQL::filaEnObjeto($consulta_movimientos_prestamos)){
        ////Armo una llave unica para el arreglo////
        //$llave_movimientos = $datos_movimientos_salarios->ano_generacion.'|'.$datos_movimientos_salarios->mes_generacion.'|'.$datos_movimientos_salarios->codigo_planilla.'|'.$datos_movimientos_salarios->periodo_pago;
        $valor_descontar_cuotas_pagadas += $datos_movimientos_prestamos->valor_pago;
        $valor_prestamo = $datos_movimientos_prestamos->valor_total;
    }

    $respuesta = array();
    $respuesta[] = round($valor_prestamo-$valor_descontar_cuotas_pagadas);
    $respuesta[] = round($valor_prestamo).' - '.round($valor_descontar_cuotas_pagadas);

    HTTP::enviarJSON($respuesta);
    exit;
}
////////////////Determino el calculo de las pretaciones sociales//////////////////
if(isset($url_determinar_prestaciones)){

    $respuesta = array();
    $codigo_empresa   = SQL::obtenerValor("sucursales","codigo_empresa","codigo='$url_codigo_sucursal'");

    $datos_vacaciones = calculoVacaciones($codigo_empresa,$url_documento_empleado,$url_fecha_ingreso_empleado,$url_codigo_sucursal,$url_fecha_liquidacion,$url_salario_base,$url_manejo_auxilio_transporte);
    $datos_vacaciones = $datos_vacaciones[5]."|". $datos_vacaciones[4]."|".$datos_vacaciones[2]."|".$datos_vacaciones[3]."| ";
    $respuesta []     = $datos_vacaciones;

    if($url_tipo_contratacion!="1"){
        $datos_prima  = calculoPrimaSevicio($codigo_empresa,$url_documento_empleado,$url_fecha_ingreso_empleado,$url_codigo_sucursal,$url_fecha_liquidacion,$url_salario_base,$url_manejo_auxilio_transporte);
        $datos_prima  = $datos_prima[5]."| |". $datos_prima[2]."|".$datos_prima[3]."|".$datos_prima[4]."| ";
        $respuesta [] = $datos_prima;

        $datos_cesantias = calculoCesantias($codigo_empresa,$url_documento_empleado,$url_fecha_ingreso_empleado,$url_codigo_sucursal,$url_fecha_liquidacion,$url_salario_base,$url_manejo_auxilio_transporte,"3");
        $datos_cesantias = $datos_cesantias[5]."|".$datos_cesantias[6]."|".$datos_cesantias[2]."|".$datos_cesantias [3]."|".$datos_cesantias[4]."| |".$datos_cesantias[2]."|".$datos_cesantias[3]."|".$datos_cesantias [5]."| ";
        $respuesta [] = $datos_cesantias;
    }else{
        $datos_prima  = "0| |0000-00-00|0|0| ";
        $respuesta [] = $datos_prima;

        $datos_cesantias = "0|0|0000-00-00|0|0| |0000-00-00|0|| ";
        $respuesta [] = $datos_cesantias;
    }

    HTTP::enviarJSON($respuesta);
    exit;
}

//////////Determino las cesantias por trabajador//////////////
if (isset($url_determino_cesantias)){

    $codigo_empresa  = SQL::obtenerValor("sucursales","codigo_empresa","codigo='$url_codigo_sucursal'");
    $datos_cesantias = calculoCesantias($codigo_empresa,$url_documento_empleado,$url_fecha_ingreso_empleado,$url_codigo_sucursal,$url_fecha_liquidacion,$url_salario_base,$url_manejo_auxilio_transporte,"3");

    $respuesta   = array();
    $respuesta[] = $datos_cesantias [5];
    $respuesta[] = $datos_cesantias [6];
    $respuesta[] = $datos_cesantias [2]; //fecha_inicio_cesantias
    $respuesta[] = $datos_cesantias [3]; //dias_liquidados_cesantias
    $respuesta[] = $datos_cesantias [4]; //salario_base_cesantias
    $respuesta[] = ""; //periodo_pago_cesantias

    $respuesta[] = $datos_cesantias [2]; //fecha_inicio_interes_cesantias
    $respuesta[] = $datos_cesantias [3]; //dias_liquidados_interes_cesantias
    $respuesta[] = $datos_cesantias [5]; //salario_base_interes_cesantias
    $respuesta[] = ""; //periodo_pago_interes_cesantias

   HTTP::enviarJSON($respuesta);
   exit;
}
//////////Determino las Retiros de cesantias trabajador//////////////
if(isset($url_datos_cesantias)){

    $datos_movimiento       = array();
    $datos_movimiento_valor = array();
    $datos_enviar           = array();
    $valor_total_cesantias  = 0;

    ////////Variables de movimientos//////
    $sumatoria_incapacidades_acumulan_censatias = 0;
    $sumatoria_tiempo_acumulan_censatias        = 0;
    $promedio_movimientos_tiempos               = 0;
    $total_interes_cesantias                    = 0;
    $total_retiros_cesantias                    = 0;
    /////////////////////////////////
    ///////Variable Auxilio/////////
    $auxilio_transporte_total = 0;
    ///////////////////////////////
    $dias_trabajados_mes = array();
    $datos_incapacidades_mes = array();
    $datos_tiempo_laborados_mes = array();
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

    $fecha_ingreso_empleado = $url_fecha_ingreso_empleado;
    $salario_base           = $url_salario_base;

    $codigo_empresa = SQL::obtenerValor("sucursales","codigo_empresa","codigo='$url_codigo_sucursal'");

    $informacion_fecha  = getdate(strtotime($url_fecha_liquidacion));
    $mes_numero       = (int)$informacion_fecha['mon'];
    $dia_inicio       = 0;
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
    $fecha_inicial_transaccion = "2011-03-11";
    $fecha_final_transaccion   = "2011-03-11";
    ///////Sumatoria de incapacidades que acumulen para primas///////////////
    $datos_incapacidades_mes = movimientos_reporte_incapacidades($url_documento_empleado,$url_codigo_sucursal,$codigo_empresa,"acumula_cesantias",$fecha_inicial_transaccion,$fecha_final_transaccion);
    foreach($datos_incapacidades_mes AS $valor_movimiento){
        $sumatoria_incapacidades_acumulan_censatias += $datos_incapacidades_mes[$i];
    }
    /////// Sumatoria tiempos laborados que acumulan para primas ////////////
    $datos_tiempo_laborados_mes = movimientos_tiempos_laborados($url_documento_empleado,$url_codigo_sucursal,$codigo_empresa,"acumula_cesantias",$fecha_inicial_transaccion,$fecha_final_transaccion);
    foreach($datos_tiempo_laborados_mes AS $valor_movimiento){
        $sumatoria_tiempo_acumulan_censatias += $valor_movimiento;
    }

    ////////////Calculo de promedio tiempos///////////////////
    $promedio_movimientos_tiempos = (($sumatoria_tiempo_acumulan_censatias+$sumatoria_incapacidades_acumulan_censatias)*30)/$total_dias_trabajados;
    ///////////Calculo de auxilio de transporte///////////////
    $valor_auxilio = SQL::obtenerValor("auxilio_transporte","valor","fecha <= '$url_fecha_liquidacion' ORDER BY fecha DESC LIMIT 0,1");
    //$salario_pendiente = ($datos_sucursal_contrato_empleados->salario_mensual * $dias_totales)/30;
    $salario_minimo_actual  = SQL::obtenerValor("salario_minimo", "valor", "codigo!=0 ORDER BY fecha DESC LIMIT 1");
    if(($salario_minimo_actual*2) >= $salario_base){
        if($url_manejo_auxilio_transporte !='5'){
           $auxilio_transporte_total= ($valor_auxilio * $total_dias_trabajados)/30;
        }
    }
    //$salario_base = $salario_base;
    //$salario_base = $salario_base+$auxilio_transporte_total+$promedio_movimientos_tiempos-$total_retiros_cesantias;
    //echo var_dump($total_dias_trabajados);
    $valor_total_cesantias = ($salario_base)*($total_dias_trabajados)/360;
    $valor_total_interese_cesantia = ($valor_total_cesantias * $total_dias_trabajados * 0.12)/360;

    ///////////////////Retiro de cesantias///////////////////////////////////
    $arreglo_retiros     = array();
    $valor_total_retiros = 0;

    ////////////Determino de donde empeezo a tomar los retiros de cesantias/////////////
    if(strtotime($url_fecha_ingreso_empleado) <= $fecha_primero_enero){
        $fecha_comparacion = $año_liquidacion.'-01-01';
    }else{
        $fecha_comparacion = $url_fecha_ingreso_empleado;
    }

    $fecha_final_anio = $año_liquidacion.'-12-31';
    ////////////////////////////////////////////////////////////////////////////////////
    $consulta_retiro = SQL::seleccionar(array("retiro_cesantias"), array("*")," codigo_empresa='$codigo_empresa' AND documento_identidad_empleado='$url_documento_empleado' AND fecha_liquidacion >= '$fecha_comparacion' AND fecha_liquidacion <= '$fecha_final_anio'");
    while($datos_retiro = SQL::filaEnObjeto($consulta_retiro)){
        $total_retiros_cesantias += $datos_retiro->valor_retiro;
        //////Calculo de los dias de retiro del uttimo retiro///////////////
        $fecha_ultimo_retiro = $datos_retiro->fecha_generacion;
        //////////Determino el valor de la cesantias hasta fecha en que fue liquidado//////
        $dias_calculo = determinoRangoDias($datos_retiro->fecha_liquidacion,$url_fecha_ingreso_empleado);
        $valor_cesantias = ($salario_base * $dias_calculo / 360)-$valor_total_retiros;
        $valor_retiro    = $datos_retiro->valor_retiro;
        $valor_total_retiros += $valor_retiro;
        $valor_interes   = $valor_cesantias * $dias_calculo * 0.12 / 360;
        $valor_interes_retiro = $valor_retiro  * $dias_calculo * 0.12 /360;
        $saldo_actual_censantias = $valor_cesantias - $valor_retiro;
        ////////////////////////////////////////////////////////////////////
        $arreglo_retiros[]   = $datos_retiro->fecha_liquidacion.','.round($valor_cesantias).','.round($valor_interes).','.round($valor_retiro).','.round($valor_interes_retiro).','.round($saldo_actual_censantias);
    }

    $valor_total_cesantias -= $valor_total_retiros;
    $arreglo_retiros[] = round($valor_total_cesantias);

    HTTP::enviarJSON($arreglo_retiros);
    exit;
}
/////////////////////////////////////////////////////
// Verificar si el modulo del componente actual esta habilitado en el periodo contable actual
$sentidos = array(
    "1" => $textos["DEBITO"],
    "2" => $textos["CREDITO"]
);

if(isset($url_recargar_consecutivo_cheque)){
    $llave_cuenta   = explode('|',$url_cuenta);
    $consecutivo_cheque = SQL::obtenerValor("consecutivo_cheques", "MAX(consecutivo)", "codigo_tipo_documento = '".$llave_cuenta[1]."' AND codigo_banco = '".$llave_cuenta[6]."' AND numero = '".$llave_cuenta[7]."'");
    if (!$consecutivo_cheque) {
        $consecutivo_cheque = 1;
    }else{
        $consecutivo_cheque++;
    }
    $cuenta = SQL::obtenerValor("seleccion_plan_contable_transacciones","SUBSTRING_INDEX(cuenta,'|',1)","id = '".$llave_cuenta[8]."'");

    unset($llave_cuenta[8]);

    $llave = implode('|',$llave_cuenta);
    $auxiliar = SQL::obtenerValor("buscador_cuentas_bancarias","id_auxiliar","id = '".$llave."'");
    $descripcion = SQL::obtenerValor("seleccion_auxiliares_contables","descripcion","id = '".$auxiliar."'");
    $datos = array($consecutivo_cheque,$cuenta,$auxiliar,$descripcion,$llave);
    HTTP::enviarJSON($datos);
    exit;
}
// Devolver datos para autocompletar la búsqueda
if (isset($url_completar)) {//Validado
    if ($url_item == "selector2") {
        echo SQL::datosAutoCompletar("seleccion_plan_contable_debito", $url_q);
    }
    exit;
}
// Devolver datos para recargar informacion requerida
if (isset($url_recargarDatosDocumento)) {

    $datos = array();
    // Obtener consecutivo de documento si tiene manejo automatico
    $manejo         = SQL::obtenerValor("tipos_documentos", "manejo_automatico", "codigo = '".$url_documento."'");
    if ($manejo == '2') {
        $consecutivo_documento  = SQL::obtenerValor("consecutivo_documentos", "MAX(consecutivo)", "codigo_sucursal = '".$url_sucursal."' AND codigo_tipo_documento = '".$url_documento."'");
        if (!$consecutivo_documento){
            $consecutivo_documento = 1;
        } else {
            $consecutivo_documento++;
        }
        $datos["consecutivo_documento"]   = $consecutivo_documento;
    }else {
        $datos["consecutivo_documento"]   = 0;
    }

    // Obtener cuentas bancarias si genera cheques
    $cheques    = SQL::obtenerValor("tipos_documentos", "genera_cheque", "codigo = '".$url_documento."'");
    $datos["genera_cheque"] = $cheques;
    if ($cheques == '1') {
        $primer_cuenta  = false;
        $consulta       = SQL::seleccionar(array("buscador_cuentas_bancarias"), array("*"),"id_sucursal = '".$url_sucursal."' AND id_documento = '".$url_documento."'");
        if (SQL::filasDevueltas($consulta)) {
            while ($datos_cuenta = SQL::filaEnObjeto($consulta)){
                if ($primer_cuenta == false) {
                    $primer_cuenta = $datos_cuenta->id;
                }
                $llave_cuenta   = explode('|',$datos_cuenta->id);
                $id_plan_cuenta = SQL::obtenerValor("cuentas_bancarias", "codigo_plan_contable", "codigo_sucursal = '".$llave_cuenta[0]."' AND codigo_tipo_documento = '".$llave_cuenta[1]."' AND codigo_banco = '".$llave_cuenta[6]."' AND numero = '".$llave_cuenta[7]."' AND codigo_sucursal_banco = '".$llave_cuenta[2]."'");
                $datos[$datos_cuenta->id."|".$id_plan_cuenta] = $datos_cuenta->BANCO." - No. ".$datos_cuenta->NUMERO;
            }
            $llave_cuenta   = explode('|',$primer_cuenta);
            $consecutivo_cheque = SQL::obtenerValor("consecutivo_cheques", "MAX(consecutivo)", "codigo_tipo_documento = '".$llave_cuenta[1]."' AND codigo_banco = '".$llave_cuenta[6]."' AND numero = '".$llave_cuenta[7]."'");
            if (!$consecutivo_cheque) {
                $consecutivo_cheque = 1;
            } else {
                $consecutivo_cheque++;
            }
            $datos["consecutivo_cheque"] = $consecutivo_cheque;
        }
    }
    HTTP::enviarJSON($datos);
    exit;
}

if(isset($url_verificar)){
$condicion_extra = "id_sucursal='$url_codigo_sucursal'";
echo SQL::datosAutoCompletar("seleccion_empleados", $url_q, $condicion_extra);
exit;
}

    ///Generar el formulario para la captura de datos

    $mensaje   = $textos["MENSAJE"];
    $continuar = true;

    $consulta_sucursales          = SQL::seleccionar(array("sucursales"),array("*"),"codigo !='0'");
    $consulta_empleados           = SQL::seleccionar(array("ingreso_empleados"),array("*"),"documento_identidad_empleado!='0' AND estado='1'");
    $consulta_tipos_documentos    = SQL::seleccionar(array("tipos_documentos"),array("*"),"codigo !='0'");
    $consulta_plan_contable       = SQL::seleccionar(array("plan_contable"),array("*"));
    $consulta_conceptos_prestamos = SQL::seleccionar(array("conceptos_prestamos"),array("*"),"codigo !='0'");

    if(SQL::filasDevueltas($consulta_sucursales)== 0 ){
    $mensaje   .= $textos["SUCURSALES"];
    $continuar = false;
    }
    if(SQL::filasDevueltas($consulta_empleados)== 0 ){
    $mensaje   .= $textos["EMPLEADOS"];
    $continuar = false;

    }
    if(SQL::filasDevueltas($consulta_tipos_documentos)== 0 ){
    $mensaje   .= $textos["TIPOS_DOCUMENTOS"];
    $continuar = false;
    }
    if(SQL::filasDevueltas($consulta_plan_contable)== 0 ){
    $mensaje   .= $textos["PLAN_CONTABLE"];
    $continuar = false;
    }

    if(SQL::filasDevueltas($consulta_conceptos_prestamos)== 0 ){
    $mensaje  .= $textos["CONCEPTO_PRESTAMOS"];
    $continuar = false;
    }

    if(!$continuar){

    $respuesta    = array();
    $respuesta[0] = $mensaje;
    $respuesta[1] = "";
    $respuesta[2] = "";
    HTTP::enviarJSON($respuesta);

}elseif(!empty($url_generar)) {

    $tasa_salud   = (int)SQL::obtenerValor("preferencias","valor","variable='tasa_salud' AND tipo_preferencia=1");
    $tasa_pension = (int)SQL::obtenerValor("preferencias","valor","variable='tasa_pension' AND tipo_preferencia=1");

    if(!$tasa_salud || !$tasa_pension){
        $listaMensajes = array();
        $mensaje       = $textos["ERROR_PREFERENCIAS_TASAS"];

        if(!$tasa_salud){
            $listaMensajes[] = $textos["ERROR_TASA_SALUD"];
        }
        if(!$tasa_pension){
            $listaMensajes[] = $textos["ERROR_TASA_PENSION"];
        }
        $mensaje  .= implode("\n",$listaMensajes);
        $error     = $mensaje;
        $titulo    = "";
        $contenido = "";
    }else{

        $error  = "";
        $titulo = $componente->nombre;

        $documento             = SQL::obtenerValor("tipos_documentos", "codigo", "codigo != '0' ORDER BY descripcion LIMIT 1");
        $modulo                = SQL::obtenerValor("componentes", "id_modulo", "id = '".$componente->id."'");
        $consecutivo_documento = "";
        $read                  = "";
        ///////////////////////////////////////////////////
        ///transacciones contables donde su concepto es prestamos a empleados  009////
        $listado_transacciones_contables = HTML::generarDatosLista("transacciones_contables_empleado","codigo","descripcion","codigo_concepto_transaccion_contable='9' AND sentido='D'");
        ////////////////////////////////////////////////////
        $cuentas_bancarias  = array();
        $cheques            = SQL::obtenerValor("tipos_documentos", "genera_cheque", "codigo = '".$documento."'");
        if ($cheques == 1) {
            $primer_cuenta = false;
            $consulta   = SQL::seleccionar(array("buscador_cuentas_bancarias"), array("*"));
            if (SQL::filasDevueltas($consulta)) {
                while ($datos_cuenta = SQL::filaEnObjeto($consulta)) {
                    if ($primer_cuenta == false) {
                        $primer_cuenta = $datos_cuenta->id;
                    }
                     $llave_cuenta   = explode('|',$datos_cuenta->id);
                    $id_plan_cuenta = SQL::obtenerValor("cuentas_bancarias", "codigo_plan_contable", "codigo_sucursal = '".$llave_cuenta[0]."' AND codigo_tipo_documento = '".$llave_cuenta[1]."' AND codigo_banco = '".$llave_cuenta[6]."' AND numero = '".$llave_cuenta[7]."' AND codigo_sucursal_banco = '".$llave_cuenta[2]."'");
                    $cuentas_bancarias[$datos_cuenta->id."|".$id_plan_cuenta] = $datos_cuenta->BANCO." - No. ".$datos_cuenta->NUMERO;
                }
                $llave_cuenta   = explode('|',$primer_cuenta);
                $consecutivo_cheque = SQL::obtenerValor("consecutivo_cheques", "MAX(consecutivo)", "codigo_tipo_documento = '".$llave_cuenta[1]."' AND codigo_banco = '".$llave_cuenta[6]."' AND numero = '".$llave_cuenta[7]."'");
                if (!$consecutivo_cheque) {
                    $consecutivo_cheque = 1;
                } else {
                    $consecutivo_cheque++;
                }
            }else{
                $consecutivo_cheque     = "";
            }
            $oculto = "";
            $banco_disabled         = "";
        } else {
            $cuentas_bancarias[0]   = "";
            $consecutivo_cheque     = "";
            $oculto                 = "oculto";
            $banco_disabled         = "disabled";
        }

        /////Obtengo los datos de motivos de retiros////
        $motivos_retiro = HTML::generarDatosLista("motivos_retiro","codigo","descripcion","codigo!='0'");
        ///////////////////////////////////////////////////
        ///Obtener lista de sucursales para selección///
        $codigo_empresa = SQL::obtenerValor("sucursales", "codigo_empresa", "codigo = '$sesion_sucursal'");
        ///Obtener lista de sucursales para selección dependiendo a los permisos///
        $consulta = SQL::seleccionar(array("sucursales"),array("*"),"codigo > 0","","nombre");
        if ($sesion_usuario == $datosGlobales["usuarioMaestro"]){
            while ($datos = SQL::filaEnObjeto($consulta)){
                $sucursales[$datos->codigo] = $datos->nombre;
            }
        }else{
            /*** Obtener lista de sucursales para selección ***/
            $tablas     = array(
            "a" => "perfiles_usuario",
            "b" => "componentes_usuario",
            "c" => "sucursales"
            );
            $columnas = array(
            "codigo" => "c.codigo",
            "nombre" => "c.nombre"
            );
            $condicion = "c.codigo = a.codigo_sucursal AND a.id = b.id_perfil
            AND a.codigo_usuario = '".$sesion_codigo_usuario."'
            AND b.id_componente = '".$componente->id."'";

            $consulta = SQL::seleccionar($tablas, $columnas, $condicion);

            if (SQL::filasDevueltas($consulta)) {
                while ($datos = SQL::filaEnObjeto($consulta)) {
                    $sucursales[$datos->codigo] = $datos->nombre;
                }
            }
        }
        ///Obtengo preferencia del valor de la cuotaminima de pago///
        //$preferencia_cuota_minima=SQL::obtenerValor("preferencias","valor","variable = 'valor_cuota_minima_pago' AND codigo_empresa='$codigo_empresa' AND tipo_preferencia='2'");
        /*** Definición de pestaña Basica ***/
        $formularios["PESTANA_BASICA"] = array(
            array(
                HTML::campoTextoCorto("*fecha_liquidacion", $textos["FECHA_LIQUIDACION"], 10, 10,date("Y-m-d"), array("title" => $textos["AYUDA_FECHA_LIQUIDACION"], "class" => "selectorFechaBloquear","onblur" => "informacionEmpleado();")),
                HTML::campoTextoCorto("*fecha_contabilizacion", $textos["FECHA_CONTABILIZACION"], 10, 10,date("Y-m-d"), array("title" => $textos["AYUDA_FECHA_CONTABILIZACION"], "class" => "selectorFecha")),
            ),
            array(
                 HTML::listaSeleccionSimple("*codigo_sucursal", $textos["SUCURSAL_LABORA"],$sucursales,$sesion_sucursal, array("title" => $textos["AYUDA_SUCURSAL_LABORA"],"onchange" => "limpiarCampo();recargarDatosDocumento();")),
                 HTML::campoTextoCorto("*selector1", $textos["EMPLEADO"], 40, 255, "", array("title" => $textos["AYUDA_EMPLEADOS"],"onfocus" => "acLocalEmpleados(this);","onKeyUp" => "limpiar_oculto_Autocompletable(this,documento_empleado)"))
                .HTML::campoOculto("documento_empleado", "")
                .HTML::campoOculto("fecha_inicio",Date("Y-m-d"))
                .HTML::campoOculto("codigo_planilla","")
                .HTML::campoOculto("codigo_empresa",$codigo_empresa)
                .HTML::campoOculto("proceso","A")
                //.HTML::campoOculto("cuota_minima",$preferencia_cuota_minima)
                .HTML::campoOculto("mensaje_valor_prestamo",$textos["ERROR_VALOR_PRESTAMO"])
                .HTML::campoOculto("mensaje_valor_cuota",$textos["ERROR_VALOR_CUOTA"])
                .HTML::campoOculto("modulo", $modulo)
                .HTML::campoOculto("maneja_cheque", '0')
                .HTML::campoOculto("error_no_cuentas_bancarias", $textos["CUENTAS_BANCARIAS_VACIAS"])
                .HTML::campoOculto("utima_fecha","")
                .HTML::campoOculto("total_cesantias","")
                .HTML::campoOculto("total_intereses_cesantias","")
                .HTML::campoOculto("total_primas","")
                .HTML::campoOculto("total_vacaciones","")
                .HTML::campoOculto("total_prestamos","")
                .HTML::campoOculto("total_horas_extras_pendientes","")

                .HTML::campoOculto("titulo_cesantias",$textos["TITULO_CESANTIAS"])
                .HTML::campoOculto("titulo_intereses_cesantias",$textos["TITULO_INTERES"])
                .HTML::campoOculto("titulo_primas",$textos["TITULO_PRIMAS"])
                .HTML::campoOculto("titulo_vacaciones",$textos["TITULO_VACACIONES"])
                .HTML::campoOculto("titulo_total",$textos["TITULO_TOTAL"])
                .HTML::campoOculto("titulo_resumen_pagos",$textos["RESUMEN_LIQUIDACION_PAGOS"])

                .HTML::campoOculto("titulo_sueldo_pendiente",$textos["SUELDO_PENDIENTE"])
                .HTML::campoOculto("titulo_auxilio_pendiente",$textos["AUXILIO_TRANSPORTE_PENDIENTE"])
                .HTML::campoOculto("titulo_extras_pendiente",$textos["HORAS_EXTRAS_PENDIENTE"])

                .HTML::campoOculto("formula_cesantias","")
                .HTML::campoOculto("formula_intereses_cesantias","")
                .HTML::campoOculto("formula_primas","")
                .HTML::campoOculto("formula_vacaciones","")
                .HTML::campoOculto("formula_prestamos","")

                .HTML::campoOculto("formula_sueldo_pendiente","")
                .HTML::campoOculto("formula_auxilio_pendiente","")

                .HTML::campoOculto("total_sueldo_pendiente","")
                .HTML::campoOculto("total_auxilio_pendiente","")

                .HTML::campoOculto("formula_salud_pendiente","")
                .HTML::campoOculto("formula_pension_pendiente","")

                .HTML::campoOculto("total_salud_pendiente","")
                .HTML::campoOculto("total_pension_pendiente","")

                .HTML::campoOculto("titulo_resumen_descuento",$textos["RESUMEN_DESCUENTOS_LIQUIDACION"])
                .HTML::campoOculto("titulo_total_deducciones",$textos["TOTAL_DEDUCCIONES"])

                .HTML::campoOculto("titulo_salud",$textos["SALUD"])
                .HTML::campoOculto("titulo_pension",$textos["PENSION"])
                .HTML::campoOculto("titulo_prestamos",$textos["PRESTAMOS_O_ANTICIPOS"])
                .HTML::campoOculto("valor_liquidacion",$textos["VALOR_LIQUIDACION"])

                .HTML::campoOculto("total_prestamos","")
                .HTML::campoOculto("formula_prestamos","")
                .HTML::campoOculto("minDate",date("Y-m-d",strtotime("-6 days",strtotime(date("Y-m-d")))))
                .HTML::campoOculto("mensaje_vacios_campos",$textos["ERROR_VACIO_EMPLEADO"])

                .HTML::campoOculto("titulo_fecha_liquidacion",$textos["FECHA_LIQUIDACION"])
                .HTML::campoOculto("titulo_valor_cesantias",$textos["VALOR_CESANTIAS"])
                .HTML::campoOculto("titulo_valor_retiro",$textos["VALOR_RETIRO"])
                .HTML::campoOculto("titulo_saldo_cesantias",$textos["SALDO_CESANTIAS"])
                .HTML::campoOculto("titulo_valor",$textos["TITULO_VALOR"])
                .HTML::campoOculto("titulo_intereses",$textos["INTERECES_CESANTIAS"])
                /////////////////Datos de ingreso //////////////////////
                ////////Cesantias///////////////////////////////////////
                .HTML::campoOculto("fecha_inicio_cesantias","")
                .HTML::campoOculto("dias_liquidados_cesantias","")
                .HTML::campoOculto("salario_base_cesantias","")
                .HTML::campoOculto("periodo_pago_cesantias","")
                 ////////Intereses/Cesantias///////////////////////////
                .HTML::campoOculto("fecha_inicio_interes_cesantias","")
                .HTML::campoOculto("dias_liquidados_interes_cesantias","")
                .HTML::campoOculto("salario_base_interes_cesantias","")
                .HTML::campoOculto("periodo_pago_interes_cesantias","")
                ////////Primas Servicio////////////////////////////////
                .HTML::campoOculto("fecha_inicio_primas","")
                .HTML::campoOculto("dias_liquidados_primas","")
                .HTML::campoOculto("salario_base_primas","")
                .HTML::campoOculto("periodo_pago_primas","")
                ////////Vacaciones/////////////////////////////////////
                .HTML::campoOculto("fecha_inicio_vacaciones","")
                .HTML::campoOculto("dias_liquidados_vacaciones","")
                .HTML::campoOculto("salario_base_vacaciones","")
                .HTML::campoOculto("periodo_pago_vacaciones","")
                ////////Salarios Pendiente/////////////////////////////
                .HTML::campoOculto("fecha_inicio_pago_salario","")
                .HTML::campoOculto("dias_trabajados_salario","")
                .HTML::campoOculto("dias_auxilio","")
               ///////Salud////////////////////////////////////////////
                .HTML::campoOculto("ibc_salud","")
                .HTML::campoOculto("porcentaje_tasa_salud","")
               ///////Pension//////////////////////////////////////////
                .HTML::campoOculto("pensionado","")
                .HTML::campoOculto("ibc_pension","")
                .HTML::campoOculto("porcentaje_tasa_pension","")
               //////Tipo de contratacion/////////////////////////////
                .HTML::campoOculto("tipo_contratacion","")
            ),
            array(
                HTML::listaSeleccionSimple("*tipo_documento", $textos["TIPO_DOCUMENTO"], HTML::generarDatosLista("tipos_documentos", "codigo", "descripcion"),"", array("title" => $textos["AYUDA_TIPO_DOCUMENTO"],"onchange" => "recargarDatosDocumento()")),
                HTML::listaSeleccionSimple("cuenta_bancaria", $textos["CUENTA_BANCARIA"], $cuentas_bancarias, "", array("class" => $oculto,$banco_disabled => $banco_disabled, "onChange" => "consecutivoCheque();")),
                HTML::campoTextoCorto("*consecutivo_cheque", $textos["CONSECUTIVO_CHEQUE"], 10, 10, $consecutivo_cheque, array("title" => $textos["AYUDA_CONSECUTIVO_CHEQUE"], "class" => $oculto, "readonly" => "readonly",$banco_disabled => $banco_disabled)),
            ),
            array(
                HTML::campoTextoCorto("*consecutivo_documento", $textos["CONSECUTIVO_DOCUMENTO"], 10, 10, $consecutivo_documento, array("title" => $textos["AYUDA_CONSECUTIVO_DOCUMENTO"],$read => $read)),
                HTML::campoTextoCorto("*selector2", $textos["CUENTA"], 40, 255, "", array("title" => $textos["AYUDA_CUENTA"], "class" => "autocompletable"))
               .HTML::campoOculto("codigo_contable", ""),
                //HTML::listaSeleccionSimple("sentido", $textos["SENTIDO"], $sentidos, "", array("title" => $textos["AYUDA_SENTIDO"],"onChange" => "recargarDatosCuenta();"))
            ),
            array(
                HTML::listaSeleccionSimple("*movito_retiro", $textos["MOTIVO_RETIRO"],$motivos_retiro, "", array("title" => $textos["AYUDA_MOTIVO_RETIRO"]))
            ),
            /*array(

                HTML::campoTextoCorto("*valor_cesantias", $textos["VALOR_CESANTIAS"], 10, 20, "", array("disabled" => "disabled" ,"title" => $textos["AYUDA_VALOR_CESANTIAS"],"onKeyPress" => "return campoEntero(event)")),
                HTML::campoTextoCorto("*valor_retiro", $textos["VALOR_RETIRO"], 10, 20, "", array("title" => $textos["AYUDA_VALOR_RETIRO"],"onKeyPress" => "return campoEntero(event)","onkeyup" => "determinarRetiro();")),//,"onkeyup" => "return determinarNumeroCuotas(event)",
                HTML::campoOculto("oculto_valor_cesantias","")
            ),*/
            array(
                HTML::campoTextoLargo("observaciones", $textos["OBSERVACIONES"], 4, 50, "", array("title" => $textos["AYUDA_OBSERVACIONES"]))
            )
        );

        $funciones["CALCULO_LIQUIZACION"] = "calculoTotal();validarCamposObligatorios('1');";
        $funciones["DATOS_CONTROL"]       =  "validarCamposObligatorios('2');";

        $formularios["DATOS_CONTROL"] = array(
             array( HTML::campoTextoCorto("*salario_base", $textos["SALARIO_BASE"], 10, 10,""),
                HTML::campoTextoCorto("*fecha_ingreso_empleado", $textos["FECHA_INGRESO_EMPLEADO"], 10, 10,"",array("class" => "selectorFecha")),
                HTML::campoOculto("manejo_auxilio_transporte",""))
          );

        $formularios["CALCULO_LIQUIZACION"] = array(
                    array(HTML::contenedor(HTML::generarTabla(
                            array("id","","","VALOR_PRESTACIONES"),"",
                            array("I","I","I"),"calculo_prestaciones",false))
                    )
                );
        $funciones["REPORTE_RETIROS"] = "datosDeCesantias();validarCamposObligatorios('3');";

        $formularios["REPORTE_RETIROS"] = array(
            array(HTML::contenedor(HTML::generarTabla(
                                        array("id","FECHA_PLANILLA","VALOR_DEVENGADO","VALOR_CESANTIAS"),
                                        "",array("I","I", "I"),"listaCesantias",false))
                )
        );

        /*$formularios["REPORTE_CENSANTIAS"] = array(

            array(
                HTML::contenedor(HTML::generarTabla(
                                        array("id","FECHA_PLANILLA","VALOR_DEVENGADO","VALOR_CESANTIAS"),
                                        "",
                                        array("I","I", "I"),
                                        "listaCesantias",
                                        false
                                )
                    )
            )

        );*/

        // Definicion de botones
        $botones   = array(HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar"));
        $contenido = HTML::generarPestanas($formularios, $botones,"",$funciones);
    }

    /// Enviar datos para la generación del formulario al script que originó la petición
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);
}

//// Adicionar los datos provenientes del formulario
elseif (!empty($forma_procesar)) {
    /// Asumir por defecto que no hubo error ///
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];
    $continuar = true;

    ///////Validar que el tipo de documento genera cheque tenga cuenta//////
    $genera_cheques = SQL::obtenerValor("tipos_documentos", "genera_cheque", "codigo = '".$forma_tipo_documento."'");
    if($genera_cheques=='1'){
         $consulta = SQL::seleccionar(array("buscador_cuentas_bancarias"), array("*"),"id_sucursal = '".$forma_codigo_sucursal."' AND id_documento = '".$forma_tipo_documento."'");
         if(SQL::filasDevueltas($consulta)){
            $continuar = false;
         }
    }else{
         $continuar = false;
    }

    $existe_concecutivo = false;

   // Guardar datos del documento que genera el movimiento contable
    $id_tabla   = SQL::obtenerValor("tablas", "id", "nombre_tabla = 'movimiento_liquidaciones_empleado'");
    $manejo     = SQL::obtenerValor("tipos_documentos", "manejo_automatico", "codigo = '".$forma_tipo_documento."'");
    if ($manejo == 2) {
        $consecutivo_documento = SQL::obtenerValor("consecutivo_documentos", "MAX(consecutivo)", "codigo_sucursal = '".$forma_codigo_sucursal."' AND codigo_tipo_documento = '".$forma_tipo_documento."'");
        if(!$consecutivo_documento){
            $consecutivo_documento = 1;
        }else{
            $consecutivo_documento++;
            $existe_concecutivo = false;
        }
    } else {
        $consecutivo_documento = $forma_consecutivo_documento;
        $existe_concecutivo    = SQL::existeItem("consecutivo_documentos","consecutivo", $consecutivo_documento, "codigo_sucursal = '".$forma_codigo_sucursal."' AND codigo_tipo_documento = '".$forma_tipo_documento."'");
    }

    if($continuar){
        $error   = true;
        $mensaje = $textos["CUENTAS_BANCARIAS_VACIAS"];
    }elseif($existe_concecutivo){
        $error   = true;
        $mensaje = $textos["CONSECUTIVO_DOCUMENTO_EXISTE"];
    }elseif(empty($forma_documento_empleado)){
        $error   = true;
        $mensaje = $textos["ERROR_VACIO_EMPLEADO"];
    }elseif(empty($forma_codigo_contable)){
        $error   = true;
        $mensaje = $textos["ERROR_VACIO_CODIGO_CONTABLE"];
    }elseif(empty($forma_documento_empleado)){
        $error   = true;
        $mensaje = $textos["ERROR_VACIO_EMPLEADO"];
    }elseif (empty($forma_consecutivo_documento)){
        $error   = true;
        $mensaje = $textos["CONSECUTIVO_DOCUMENTO_VACIO"];
    }elseif(strtotime($forma_fecha_liquidacion)>strtotime($forma_fecha_contabilizacion)){
        $error   = true;
        $mensaje = $textos["ERROR_FECHA_LIQUIDACION"];
    }else{

        $fecha_registro             = date("Y-m-d H:i:s");
        $fecha_registro_consecutivo = date("Y-m-d");
        //////////////Generó la llave de la tabla////////////////
        $tipo_comprobante = SQL::obtenerValor("tipos_documentos", "codigo_comprobante", "codigo = '".$forma_tipo_documento."'");
        $codigo_empresa   = SQL::obtenerValor("sucursales","codigo_empresa","codigo='".$forma_codigo_sucursal."'");

        $llave_tabla = $codigo_empresa.'|'.$forma_documento_empleado.'|'.$tipo_comprobante.'|0|'.$forma_tipo_documento.'|'.str_pad($consecutivo_documento,9,"0", STR_PAD_LEFT).'|'.$fecha_registro;

        if(SQL::existeItem("consecutivo_documentos","llave_tabla",$llave_tabla)){
            $error   = true;
            $mensaje = $textos["EXISTE_CONSECUTIVO_DOCUMENTO"];
        }else{
            $datos = array(
                "codigo_sucursal"             => $forma_codigo_sucursal,
                "codigo_tipo_documento"       => $forma_tipo_documento,
                "fecha_registro"              => $fecha_registro_consecutivo,
                "documento_identidad_tercero" => $forma_documento_empleado,
                "consecutivo"                 => $consecutivo_documento,
                "id_tabla"                    => $id_tabla,
                "llave_tabla"                 => $llave_tabla,
                "codigo_sucursal_archivo"     => '0',
                "consecutivo_archivo"         => '0'
            );

            $insertar = SQL::insertar("consecutivo_documentos", $datos);
            if (!$insertar) {
                $error   = true;
                $mensaje = $textos["EXISTE_CONSECUTIVO_DOCUMENTO"];
            }else{
                $estado = true;
                ///////////Datos de la cuenta afectada////////////
                $tipo_documento_genera_cheque = SQL::obtenerValor("tipos_documentos", "genera_cheque", "codigo = '".$forma_tipo_documento."'");
                $flujo_efectivo               = SQL::obtenerValor("plan_contable","flujo_efectivo","codigo_contable='".$forma_codigo_contable."'");

                $consulta_sucursal_contrato_empleados = SQL::seleccionar(array("sucursal_contrato_empleados"),array("*"), "codigo_empresa='".$codigo_empresa."' AND documento_identidad_empleado='$forma_documento_empleado'", "", "fecha_ingreso_sucursal DESC", 0, 1); ////////////
                $datos_sucursal_contrato_empleados    = SQL::filaEnObjeto($consulta_sucursal_contrato_empleados);

                $datos = array(
                    /////////fechas de control/////////
                    "fecha_generacion"                  => $fecha_registro,
                    "fecha_liquidacion"                 => $forma_fecha_liquidacion,
                    "fecha_contabilizacion"             => $forma_fecha_contabilizacion,
                    ////contrato_sucursal_empleado////
                    "codigo_empresa"                    => $codigo_empresa,
                    "documento_identidad_empleado"      => $forma_documento_empleado,
                    "fecha_ingreso"                     => $datos_sucursal_contrato_empleados->fecha_ingreso,
                    "codigo_sucursal"                   => $datos_sucursal_contrato_empleados->codigo_sucursal,
                    "fecha_ingreso_sucursal"            => $datos_sucursal_contrato_empleados->fecha_ingreso_sucursal,
                    ///Datos consecutivo documento///
                    "fecha_generacion_consecutivo"      => $fecha_registro_consecutivo,
                    "codigo_tipo_documento"             => $forma_tipo_documento,
                    "consecutivo_documento"             => $consecutivo_documento,
                    /////Datos Auxiliar contable///////
                    "codigo_empresa_auxiliar"           => $datos_sucursal_contrato_empleados->codigo_empresa_auxiliar,
                    "codigo_anexo_contable"             => $datos_sucursal_contrato_empleados->codigo_anexo_contable,
                    "codigo_auxiliar_contable"          => $datos_sucursal_contrato_empleados->codigo_auxiliar,
                    ///informacion de transacciones////
                    ///////////Cesantias//////////////
                    "fecha_inicio_cesantias"            => $forma_fecha_inicio_cesantias,
                    "fecha_final_cesantias"             => $forma_fecha_liquidacion,
                    "dias_liquidados_cesantias"         => $forma_dias_liquidados_cesantias,
                    "salario_base_cesantias"            => $forma_salario_base_cesantias,
                    "periodo_pago_cesantias"            => $forma_periodo_pago_cesantias,
                    //////Intereses/Cesantias//////////
                    "fecha_inicio_interes_cesantias"    => $forma_fecha_inicio_interes_cesantias,
                    "fecha_final_interes_cesantias"     => $forma_fecha_liquidacion,
                    "dias_liquidados_interes_cesantias" => $forma_dias_liquidados_interes_cesantias,
                    "salario_base_interes_cesantias"    => $forma_salario_base_interes_cesantias,
                    "periodo_pago_interes_cesantias"    => $forma_periodo_pago_interes_cesantias,
                    ////////Primas Servicio////////////
                    "fecha_inicio_primas"               => $forma_fecha_inicio_primas,
                    "fecha_final_primas"                => $forma_fecha_liquidacion,
                    "dias_liquidados_primas"            => $forma_dias_liquidados_primas,
                    "salario_base_primas"               => $forma_salario_base_primas,
                    "periodo_pago_primas"               => $forma_periodo_pago_primas,
                     ////////Vacaciones////////////////
                    "fecha_inicio_vacaciones"           => $forma_fecha_inicio_vacaciones,
                    "fecha_final_vacaciones"            => $forma_fecha_liquidacion,
                    "dias_liquidados_vacaciones"        => $forma_dias_liquidados_vacaciones,
                    "salario_base_vacaciones"           => $forma_salario_base_vacaciones,
                    "periodo_pago_vacaciones"           => $forma_periodo_pago_vacaciones,
                    //////Informacion Adicional///////
                    "motivo_retiro"                     => $forma_movito_retiro,
                    "observaciones"                     => $forma_observaciones,
                    "autorizado"                        => "1",
                    "pagado"                            => "0",
                    "codigo_usuario_registra"           => $sesion_codigo_usuario,
                 );

                $insertar = SQL::insertar("liquidaciones_empleado", $datos);

                if (!$insertar) {
                    ////////Elimino los valores registrados anteriores///////
                    //////// Tablas consecutivo_documentos //////////////////
                    $llave_consecutivo_documento  = " codigo_sucursal='$forma_codigo_sucursal' AND codigo_tipo_documento='$forma_tipo_documento'";
                    $llave_consecutivo_documento .= " AND documento_identidad_tercero='$forma_documento_empleado' AND fecha_registro='$fecha_registro_consecutivo' AND  consecutivo='$consecutivo_documento'";

                    $eliminar = SQL::eliminar("consecutivo_documentos",$llave_consecutivo_documento);
                    $error   = true;
                    $mensaje = $textos["ERROR_INSERTAR_LIQUIDACION_EMPLEADO"];
                }else{

                    $codigo_sucursal_pertence       = '0';
                    $tipo_documento_cuenta_bancaria = '0';
                    $codigo_sucursal_banco          = '0';
                    $codigo_iso                     = '';
                    $codigo_dane_departamento       = '';
                    $codigo_dane_municipio          = '';
                    $consecutivo_cheque             = '0';
                    $codigo_banco                   = '0';
                    $numero                         = '';

                    $flujo_efectivo = SQL::obtenerValor("plan_contable","flujo_efectivo","codigo_contable='$forma_codigo_contable'");

                    $datos = array(
                        ///////////////////////////////////
                        "codigo_empresa"                 => $codigo_empresa,
                        "documento_identidad_empleado"   => $forma_documento_empleado,
                        "fecha_generacion"               => $fecha_registro,
                        "motivo_retiro"                  => $forma_movito_retiro,
                        //////DATOS CUENTA AFECTA/////////
                        "flujo_efectivo"                 => $flujo_efectivo,
                        "codigo_plan_contable"           => $forma_codigo_contable,
                        "sentido"                        => "D",
                        /////////CUENTA BANCARIA//////////
                        "codigo_sucursal_pertence"       => $codigo_sucursal_pertence,
                        "tipo_documento_cuenta_bancaria" => $tipo_documento_cuenta_bancaria,
                        "codigo_sucursal_banco"          => $codigo_sucursal_banco,
                        "codigo_iso"                     => $codigo_iso,
                        "codigo_dane_departamento"       => $codigo_dane_departamento,
                        "codigo_dane_municipio"          => $codigo_dane_municipio,
                        "codigo_banco"                   => $codigo_banco,
                        "consecutivo_cheque"             => $consecutivo_cheque,
                        "numero"                         => $numero
                    );

                    $insertar = SQL::insertar("movimiento_liquidaciones_empleado", $datos);
                    if (!$insertar) {
                        ////////Elimino los valores registrados anteriores///////
                        //////// Tablas consecutivo_documentos //////////////////
                        /////////////////////////////////////////////////////////
                        $llave_movimiento  = " codigo_empresa='$codigo_empresa' AND documento_identidad_empleado='$forma_documento_empleado'";
                        $llave_movimiento .= " AND fecha_generacion='$fecha_registro' AND motivo_retiro='$forma_movito_retiro'";
                        //////// Tablas liquidaciones_empleado ///////////////////
                        $eliminar = SQL::eliminar("liquidaciones_empleado",$llave_movimiento);
                        //////////////////////////////////////////////////////////
                        $llave_consecutivo_documento  = " codigo_sucursal='$forma_codigo_sucursal' AND codigo_tipo_documento='$forma_tipo_documento'";
                        $llave_consecutivo_documento .= " AND documento_identidad_tercero='$forma_documento_empleado' AND fecha_registro='$fecha_registro_consecutivo' AND  consecutivo='$consecutivo_documento'";
                        $eliminar = SQL::eliminar("consecutivo_documentos",$llave_consecutivo_documento);

                        $error   = true;
                        $mensaje = $textos["ERROR_MOVIMIENTO_LIQUIDACION"];
                    }else{
                        $continuar                      = true;
                        $consulta_departamento_empleado = SQL::seleccionar(array("departamento_seccion_contrato_empleado"),array("*"), "codigo_empresa='$codigo_empresa' AND documento_identidad_empleado='$forma_documento_empleado'", "", "fecha_ingreso_sucursal DESC", 0, 1); ////////////
                        $datos_departamento_empleado    = SQL::filaEnObjeto($consulta_departamento_empleado);

                        $codigo_gasto = SQL::obtenerValor("departamentos_empresa","codigo_gasto","codigo ='$datos_departamento_empleado->codigo_departamento_empresa'");
                        $consulta_gastos_prestaciones_sociales = SQL::seleccionar(array("gastos_prestaciones_sociales"),array("*"),"codigo='$codigo_gasto'");
                        $datos_gastos_prestaciones_sociales    = SQL::filaEnObjeto($consulta_gastos_prestaciones_sociales);

                        $arreglo_transacciones = array(
                            $datos_gastos_prestaciones_sociales->cesantia_pago_gasto,
                            $datos_gastos_prestaciones_sociales->intereses_pago_gasto,
                            $datos_gastos_prestaciones_sociales->prima_pago_gasto,
                            $datos_gastos_prestaciones_sociales->vacacion_pago_gasto_liquidacion
                        );
                        $arreglo_valor_prestaciones = array(
                            $forma_total_cesantias,
                            $forma_total_intereses_cesantias,
                            $forma_total_primas,
                            $forma_total_vacaciones
                        );

                         for($i=0;$i<count($arreglo_transacciones);$i++){

                            $transaccion_contable = $arreglo_transacciones[$i];
                            $sentido              = SQL::obtenerValor("transacciones_contables_empleado","sentido","codigo='$transaccion_contable'");
                            $codigo_plan_contable = SQL::obtenerValor("transacciones_contables_empleado","codigo_contable","codigo='$transaccion_contable'");

                            if($arreglo_valor_prestaciones[$i]!=0){
                                $datos = array(
                                     ///////////////////////////////////
                                    "codigo_empresa"                 => $codigo_empresa,
                                    "documento_identidad_empleado"   => $forma_documento_empleado,
                                    "fecha_generacion"               => $fecha_registro,
                                    "motivo_retiro"                  => $forma_movito_retiro,
                                    "codigo_empresa_auxiliar"        => $datos_sucursal_contrato_empleados->codigo_empresa_auxiliar,
                                    "codigo_anexo_contable"          => $datos_sucursal_contrato_empleados->codigo_anexo_contable,
                                    "codigo_auxiliar_contable"       => $datos_sucursal_contrato_empleados->codigo_auxiliar,
                                    //////////////////////////////////
                                    "condigo_transaccion"            => $transaccion_contable,
                                    "codigo_plan_contable"           => $codigo_plan_contable,
                                    "sentido"                        => $sentido,
                                    "valor"                          => $arreglo_valor_prestaciones[$i]
                                 );

                                 $insertar = SQL::insertar("datos_liquidaciones_empleado", $datos);
                            }else{
                                $insertar = true;
                            }
                             if(!$insertar) {

                                $continuar=false;
                                ////////Elimino los valores registrados anteriores///////
                                /////////////////////////////////////////////////////////
                                $llave_movimiento  = " codigo_empresa='$codigo_empresa' AND documento_identidad_empleado='$forma_documento_empleado'";
                                $llave_movimiento .= " AND fecha_generacion='$fecha_registro' AND motivo_retiro='$forma_movito_retiro'";
                                ////////Elimino todos los datos generados en datosprestaciones sociales///////
                                $eliminar = SQL::eliminar("datos_liquidaciones_empleado",$llave_movimiento);
                                //////// Tablas movimiento_liquidaciones_empleado ////////
                                $eliminar = SQL::eliminar("movimiento_liquidaciones_empleado",$llave_movimiento);
                                //////// Tablas liquidaciones_empleado ///////////////////
                                $eliminar = SQL::eliminar("liquidaciones_empleado",$llave_movimiento);
                                //////////////////////////////////////////////////////////
                                $llave_consecutivo_documento  = " codigo_sucursal='$forma_codigo_sucursal' AND codigo_tipo_documento='$forma_tipo_documento'";
                                $llave_consecutivo_documento .= " AND documento_identidad_tercero='$forma_documento_empleado' AND fecha_registro='$fecha_registro_consecutivo' AND  consecutivo='$consecutivo_documento'";
                                $eliminar = SQL::eliminar("consecutivo_documentos",$llave_consecutivo_documento);

                                $error   = true;
                                $mensaje = $textos["ERROR_PRESTACIONES_SOCIALES"];
                                break;
                             }
                         }

                         if($continuar){

                             $transaccion_contable_salario = $datos_sucursal_contrato_empleados->codigo_transaccion_salario;
                             $sentido                      = SQL::obtenerValor("transacciones_contables_empleado","sentido","codigo='$transaccion_contable_salario'");
                             $codigo_plan_contable         = SQL::obtenerValor("transacciones_contables_empleado","codigo_contable","codigo='$transaccion_contable_salario'");

                             $datos = array(
                                ///////////////////////////////////
                                "codigo_empresa"                => $codigo_empresa,
                                "documento_identidad_empleado"  => $forma_documento_empleado,
                                "fecha_generacion"              => $fecha_registro,
                                "motivo_retiro"                 => $forma_movito_retiro,
                                "codigo_transaccion_contable"   => $transaccion_contable_salario,
                                ///////////////////////////////
                                "fecha_inicio_pago"             => $forma_fecha_inicio_pago_salario,
                                "fecha_hasta_pago"              => $forma_fecha_liquidacion,
                                ///////////////////////////////
                                "codigo_empresa_auxiliar"       => $datos_sucursal_contrato_empleados->codigo_empresa_auxiliar,
                                "codigo_anexo_contable"         => $datos_sucursal_contrato_empleados->codigo_anexo_contable,
                                ///tabla_auxiliares_contables
                                "codigo_auxiliar_contable"      => $datos_sucursal_contrato_empleados->codigo_auxiliar,
                                ///////////////////////////////
                                "codigo_contable"               => $codigo_plan_contable,
                                "sentido"                       => $sentido,
                                "dias_trabajados"               => $forma_dias_trabajados_salario,
                                "salario_mensual"               => $datos_sucursal_contrato_empleados->salario_mensual,
                                "valor_movimiento"              => $forma_total_sueldo_pendiente
                             );

                             $insertar = SQL::insertar("liquidaciones_movimientos_salarios", $datos);

                             if(!$insertar) {

                                ////////Elimino los valores registrados anteriores///////
                                /////////////////////////////////////////////////////////
                                $llave_movimiento  = " codigo_empresa='$codigo_empresa' AND documento_identidad_empleado='$forma_documento_empleado'";
                                $llave_movimiento .= " AND fecha_generacion='$fecha_registro' AND motivo_retiro='$forma_movito_retiro'";
                                ////////Elimino todos los datos generados en datosprestaciones sociales///////
                                $eliminar = SQL::eliminar("datos_liquidaciones_empleado",$llave_movimiento);
                                //////// Tablas movimiento_liquidaciones_empleado ////////
                                $eliminar = SQL::eliminar("movimiento_liquidaciones_empleado",$llave_movimiento);
                                //////// Tablas liquidaciones_empleado ///////////////////
                                $eliminar = SQL::eliminar("liquidaciones_empleado",$llave_movimiento);
                                //////////////////////////////////////////////////////////
                                $llave_consecutivo_documento  = " codigo_sucursal='$forma_codigo_sucursal' AND codigo_tipo_documento='$forma_tipo_documento'";
                                $llave_consecutivo_documento .= " AND documento_identidad_tercero='$forma_documento_empleado' AND fecha_registro='$fecha_registro_consecutivo' AND  consecutivo='$consecutivo_documento'";
                                $eliminar = SQL::eliminar("consecutivo_documentos",$llave_consecutivo_documento);

                                $continuar = false;
                                $error     = true;
                                $mensaje   = $textos["ERROR_LIQUIDACION_SALARIO"];

                             }else{
                                 $transaccion_contable_auxilio = $datos_sucursal_contrato_empleados->codigo_transaccion_pension;
                                 $sentido                      = SQL::obtenerValor("transacciones_contables_empleado","sentido","codigo='$transaccion_contable_auxilio'");
                                 $codigo_plan_contable         = SQL::obtenerValor("transacciones_contables_empleado","codigo_contable","codigo='$transaccion_contable_auxilio'");

                                 $datos = array(
                                    ///////////////////////////////////
                                    "codigo_empresa"                => $codigo_empresa,
                                    "documento_identidad_empleado"  => $forma_documento_empleado,
                                    "fecha_generacion"              => $fecha_registro,
                                    "motivo_retiro"                 => $forma_movito_retiro,
                                    "codigo_transaccion_contable"   => $transaccion_contable_auxilio,
                                    ///////////////////////////////
                                    "fecha_inicio_pago"             => $forma_fecha_inicio_pago_salario,
                                    "fecha_hasta_pago"              => $forma_fecha_liquidacion,
                                    ///////////////////////////////
                                    "codigo_empresa_auxiliar"       => $datos_sucursal_contrato_empleados->codigo_empresa_auxiliar,
                                    "codigo_anexo_contable"         => $datos_sucursal_contrato_empleados->codigo_anexo_contable,
                                    ///tabla_auxiliares_contables
                                    "codigo_auxiliar_contable"      => $datos_sucursal_contrato_empleados->codigo_auxiliar,
                                    ///////////////////////////////
                                    "codigo_contable"               => $codigo_plan_contable,
                                    "sentido"                       => $sentido,
                                    "dias_trabajados"               => $forma_dias_trabajados_salario,
                                    "dias_auxilio"                  => $forma_dias_auxilio,
                                    "salario_mensual"               => $datos_sucursal_contrato_empleados->salario_mensual,
                                    "valor_movimiento"              => $forma_total_auxilio_pendiente
                                 );

                                 $insertar = SQL::insertar("liquidaciones_movimientos_auxilio_transporte", $datos);

                                 if(!$insertar) {
                                    ////////Elimino los valores registrados anteriores///////
                                    /////////////////////////////////////////////////////////
                                    $llave_movimiento  = " codigo_empresa='$codigo_empresa' AND documento_identidad_empleado='$forma_documento_empleado'";
                                    $llave_movimiento .= " AND fecha_generacion='$fecha_registro' AND motivo_retiro='$forma_movito_retiro'";
                                    /////Elimino todos los datos generados para saldos pendientes por pagar///////
                                    $eliminar = SQL::eliminar("liquidaciones_movimientos_salarios",$llave_movimiento);
                                    ////////Elimino todos los datos generados en datosprestaciones sociales///////
                                    $eliminar = SQL::eliminar("datos_liquidaciones_empleado",$llave_movimiento);
                                    //////// Tablas movimiento_liquidaciones_empleado ////////
                                    $eliminar = SQL::eliminar("movimiento_liquidaciones_empleado",$llave_movimiento);
                                    //////// Tablas liquidaciones_empleado ///////////////////
                                    $eliminar = SQL::eliminar("liquidaciones_empleado",$llave_movimiento);
                                    //////////////////////////////////////////////////////////
                                    $llave_consecutivo_documento  = " codigo_sucursal='$forma_codigo_sucursal' AND codigo_tipo_documento='$forma_tipo_documento'";
                                    $llave_consecutivo_documento .= " AND documento_identidad_tercero='$forma_documento_empleado' AND fecha_registro='$fecha_registro_consecutivo' AND  consecutivo='$consecutivo_documento'";
                                    $eliminar = SQL::eliminar("consecutivo_documentos",$llave_consecutivo_documento);

                                    $continuar = false;
                                    $error     = true;
                                    $mensaje   = $textos["ERROR_LIQUIDACION_AUXILO"];

                                 }else{

                                     $transaccion_contable_salud = $datos_sucursal_contrato_empleados->codigo_transaccion_salud;
                                     $sentido                      = SQL::obtenerValor("transacciones_contables_empleado","sentido","codigo='$transaccion_contable_salud'");
                                     $codigo_plan_contable         = SQL::obtenerValor("transacciones_contables_empleado","codigo_contable","codigo='$transaccion_contable_salud'");

                                     $codicion_entidad_salud  = " documento_identidad_empleado='$forma_documento_empleado' AND codigo_empresa='$codigo_empresa' AND fecha_ingreso='$datos_sucursal_contrato_empleados->fecha_ingreso' AND fecha_inicio_salud <= '$fecha_registro_consecutivo'";
                                     $codicion_entidad_salud .= " ORDER BY fecha_inicio_salud DESC LIMIT 0,1";
                                     $codigo_entidad_salud    = SQL::obtenerValor("entidades_salud_empleados","codigo_entidad_salud",$codicion_entidad_salud);

                                     $datos = array(
                                        ///////////////////////////////////
                                        "codigo_empresa"                => $codigo_empresa,
                                        "documento_identidad_empleado"  => $forma_documento_empleado,
                                        "fecha_generacion"              => $fecha_registro,
                                        "motivo_retiro"                 => $forma_movito_retiro,
                                        "codigo_transaccion_contable"   => $transaccion_contable_salud,
                                        ///////////////////////////////
                                        "fecha_inicio_pago"             => $forma_fecha_inicio_pago_salario,
                                        "fecha_hasta_pago"              => $forma_fecha_liquidacion,
                                        ///////////////////////////////
                                        "codigo_empresa_auxiliar"       => $datos_sucursal_contrato_empleados->codigo_empresa_auxiliar,
                                        "codigo_anexo_contable"         => $datos_sucursal_contrato_empleados->codigo_anexo_contable,
                                        "codigo_auxiliar_contable"      => $datos_sucursal_contrato_empleados->codigo_auxiliar,
                                        ///////////////////////////////
                                        "codigo_entidad_salud"          => $codigo_entidad_salud,
                                        ///////////////////////////////
                                        "codigo_contable"               => $codigo_plan_contable,
                                        "sentido"                       => $sentido,
                                        "dias_trabajados"               => $forma_dias_trabajados_salario,
                                        "salario_mensual"               => $datos_sucursal_contrato_empleados->salario_mensual,
                                        "valor_movimiento"              => $forma_total_salud_pendiente,
                                        "ibc_salud"                     => $forma_ibc_salud,
                                        "porcentaje_tasa_salud"         => $forma_porcentaje_tasa_salud,
                                       );

                                     $insertar = SQL::insertar("liquidaciones_movimientos_salud",$datos);

                                     if(!$insertar) {
                                        ////////Elimino los valores registrados anteriores///////
                                        /////////////////////////////////////////////////////////
                                        $llave_movimiento  = " codigo_empresa='$codigo_empresa' AND documento_identidad_empleado='$forma_documento_empleado'";
                                        $llave_movimiento .= " AND fecha_generacion='$fecha_registro' AND motivo_retiro='$forma_movito_retiro'";
                                        /////Elimino todos los datos generados para saldos pendientes por pagar///////
                                        $eliminar = SQL::eliminar("liquidaciones_movimientos_salarios",$llave_movimiento);
                                        $eliminar = SQL::eliminar("liquidaciones_movimientos_auxilio_transporte",$llave_movimiento);
                                        ////////Elimino todos los datos generados en datosprestaciones sociales///////
                                        $eliminar = SQL::eliminar("datos_liquidaciones_empleado",$llave_movimiento);
                                        //////// Tablas movimiento_liquidaciones_empleado ////////
                                        $eliminar = SQL::eliminar("movimiento_liquidaciones_empleado",$llave_movimiento);
                                        //////// Tablas liquidaciones_empleado ///////////////////
                                        $eliminar = SQL::eliminar("liquidaciones_empleado",$llave_movimiento);
                                        //////////////////////////////////////////////////////////
                                        $llave_consecutivo_documento  = " codigo_sucursal='$forma_codigo_sucursal' AND codigo_tipo_documento='$forma_tipo_documento'";
                                        $llave_consecutivo_documento .= " AND documento_identidad_tercero='$forma_documento_empleado' AND fecha_registro='$fecha_registro_consecutivo' AND  consecutivo='$consecutivo_documento'";
                                        $eliminar = SQL::eliminar("consecutivo_documentos",$llave_consecutivo_documento);

                                        $continuar = false;
                                        $error     = true;
                                        $mensaje   = $textos["ERROR_LIQUIDACION_SALUD"];
                                      }else{

                                         if($forma_pensionado == '0'){
                                             $transaccion_contable_pension = $datos_sucursal_contrato_empleados->codigo_transaccion_pension;
                                             $sentido                      = SQL::obtenerValor("transacciones_contables_empleado","sentido","codigo='$transaccion_contable_pension'");
                                             $codigo_plan_contable         = SQL::obtenerValor("transacciones_contables_empleado","codigo_contable","codigo='$transaccion_contable_pension'");

                                             $codicion_entidad_pension  = " documento_identidad_empleado='$forma_documento_empleado' AND codigo_empresa='$codigo_empresa' AND fecha_ingreso='$datos_sucursal_contrato_empleados->fecha_ingreso' AND fecha_inicio_pension <= '$fecha_registro_consecutivo'";
                                             $codicion_entidad_pension .= " ORDER BY fecha_inicio_pension DESC LIMIT 0,1";
                                             $codigo_entidad_pension    = SQL::obtenerValor("entidades_pension_empleados","codigo_entidad_pension",$codicion_entidad_pension);

                                             $datos = array(
                                                ///////////////////////////////////
                                                "codigo_empresa"                => $codigo_empresa,
                                                "documento_identidad_empleado"  => $forma_documento_empleado,
                                                "fecha_generacion"              => $fecha_registro,
                                                "motivo_retiro"                 => $forma_movito_retiro,
                                                "codigo_transaccion_contable"   => $transaccion_contable_pension,
                                                ///////////////////////////////
                                                "fecha_inicio_pago"             => $forma_fecha_inicio_pago_salario,
                                                "fecha_hasta_pago"              => $forma_fecha_liquidacion,
                                                ///////////////////////////////
                                                "codigo_empresa_auxiliar"       => $datos_sucursal_contrato_empleados->codigo_empresa_auxiliar,
                                                "codigo_anexo_contable"         => $datos_sucursal_contrato_empleados->codigo_anexo_contable,
                                                "codigo_auxiliar_contable"      => $datos_sucursal_contrato_empleados->codigo_auxiliar,
                                                ///////////////////////////////
                                                "codigo_entidad_pension"        => $codigo_entidad_pension,
                                                ///////////////////////////////
                                                "codigo_contable"               => $codigo_plan_contable,
                                                "sentido"                       => $sentido,
                                                "dias_trabajados"               => $forma_dias_trabajados_salario,
                                                "salario_mensual"               => $datos_sucursal_contrato_empleados->salario_mensual,
                                                "valor_movimiento"              => $forma_total_pension_pendiente,
                                                "ibc_pension"                   => $forma_ibc_pension,
                                                "porcentaje_tasa_pension"       => $forma_porcentaje_tasa_pension
                                               );

                                             $insertar = SQL::insertar("liquidaciones_movimientos_pension",$datos);
                                         } else {
                                             $insertar = true;
                                         }
                                         if(!$insertar) {
                                            ////////Elimino los valores registrados anteriores///////
                                            /////////////////////////////////////////////////////////
                                            $llave_movimiento  = " codigo_empresa='$codigo_empresa' AND documento_identidad_empleado='$forma_documento_empleado'";
                                            $llave_movimiento .= " AND fecha_generacion='$fecha_registro' AND motivo_retiro='$forma_movito_retiro'";
                                            /////Elimino todos los datos generados para saldos pendientes por pagar///////
                                            $eliminar = SQL::eliminar("liquidaciones_movimientos_salud",$llave_movimiento);
                                            $eliminar = SQL::eliminar("liquidaciones_movimientos_salarios",$llave_movimiento);
                                            $eliminar = SQL::eliminar("liquidaciones_movimientos_auxilio_transporte",$llave_movimiento);
                                            ////////Elimino todos los datos generados en datosprestaciones sociales///////
                                            $eliminar = SQL::eliminar("datos_liquidaciones_empleado",$llave_movimiento);
                                            //////// Tablas movimiento_liquidaciones_empleado ////////
                                            $eliminar = SQL::eliminar("movimiento_liquidaciones_empleado",$llave_movimiento);
                                            //////// Tablas liquidaciones_empleado ///////////////////
                                            $eliminar = SQL::eliminar("liquidaciones_empleado",$llave_movimiento);
                                            //////////////////////////////////////////////////////////
                                            $llave_consecutivo_documento  = " codigo_sucursal='$forma_codigo_sucursal' AND codigo_tipo_documento='$forma_tipo_documento'";
                                            $llave_consecutivo_documento .= " AND documento_identidad_tercero='$forma_documento_empleado' AND fecha_registro='$fecha_registro_consecutivo' AND  consecutivo='$consecutivo_documento'";
                                            $eliminar = SQL::eliminar("consecutivo_documentos",$llave_consecutivo_documento);

                                            $continuar = false;
                                            $error     = true;
                                            $mensaje   = $textos["ERROR_LIQUIDACION_SALUD"];
                                          }
                                      }
                                  }
                              }
                         }

                         if($tipo_documento_genera_cheque=='1' && $continuar){

                            $llave_tabla        = " codigo_empresa=\'$codigo_empresa\' AND documento_identidad_empleado=\'$forma_documento_empleado\'";
                            $llave_tabla       .= " AND fecha_generacion=\'$fecha_registro\' AND motivo_retiro=\'$forma_movito_retiro\'";

                            $llave_cuenta_bancaria          = explode("|",$forma_cuenta_bancaria);
                            $codigo_sucursal_pertence       = $llave_cuenta_bancaria[0];
                            $tipo_documento_cuenta_bancaria = $llave_cuenta_bancaria[1];
                            $codigo_sucursal_banco          = $llave_cuenta_bancaria[2];
                            $codigo_iso                     = $llave_cuenta_bancaria[3];
                            $codigo_dane_departamento       = $llave_cuenta_bancaria[4];
                            $codigo_dane_municipio          = $llave_cuenta_bancaria[5];
                            $codigo_banco                   = $llave_cuenta_bancaria[6];
                            $numero                         = $llave_cuenta_bancaria[7];

                            $consecutivo_cheque = (int) SQL::obtenerValor("consecutivo_cheques", "MAX(consecutivo)", "codigo_sucursal='$codigo_sucursal_pertence' AND codigo_tipo_documento = '$tipo_documento_cuenta_bancaria' AND codigo_banco = '$codigo_banco' AND numero = '$numero'");
                            //echo var_dump("codigo_sucursal='$codigo_sucursal_pertence' AND codigo_tipo_documento = '$tipo_documento_cuenta_bancaria' AND codigo_banco = '$codigo_banco' AND numero = '$numero'");
                            if(!$consecutivo_cheque){
                                $consecutivo_cheque = 1;
                            }else{
                                $consecutivo_cheque++;
                            }

                            $datos = array (
                                "codigo_sucursal"                 => $forma_codigo_sucursal,
                                "codigo_tipo_documento"           => $tipo_documento_cuenta_bancaria,
                                "codigo_banco"                    => $codigo_banco,
                                "numero"                          => $numero,
                                "consecutivo"                     => $consecutivo_cheque,
                                /////////////LLAVE DE CUENTAS BANCARIAS//////////
                                "codigo_sucursal_cuenta"          => $forma_codigo_sucursal,
                                "codigo_tipo_documento_cuenta"    => $tipo_documento_cuenta_bancaria,
                                "codigo_sucursal_banco"           => $codigo_sucursal_banco,
                                "codigo_iso_cuenta"               => $codigo_iso,
                                "codigo_dane_departamento_cuenta" => $codigo_dane_departamento,
                                "codigo_dane_municipio_cuenta"    => $codigo_dane_municipio,
                                "codigo_banco_cuenta"             => $codigo_banco,
                                "numero_cuenta"                   => $numero,
                                ////////////////////////////////////////////////
                                "id_tabla"                        => $id_tabla,
                                "llave_tabla"                     => $llave_tabla
                            );

                            $insertar = SQL::insertar("consecutivo_cheques", $datos);
                            if (!$insertar) {
                                ////////Elimino los valores registrados anteriores///////
                                /////////////////////////////////////////////////////////
                                $llave_movimiento  = " codigo_empresa='$codigo_empresa' AND documento_identidad_empleado='$forma_documento_empleado'";
                                $llave_movimiento .= " AND fecha_generacion='$fecha_registro' AND motivo_retiro='$forma_movito_retiro'";
                                /////Elimino todos los datos generados para saldos pendientes por pagar///////
                                $eliminar = SQL::eliminar("liquidaciones_movimientos_salud",$llave_movimiento);
                                $eliminar = SQL::eliminar("liquidaciones_movimientos_salarios",$llave_movimiento);
                                $eliminar = SQL::eliminar("liquidaciones_movimientos_auxilio_transporte",$llave_movimiento);
                                ////////Elimino todos los datos generados en datosprestaciones sociales///////
                                $eliminar = SQL::eliminar("datos_liquidaciones_empleado",$llave_movimiento);
                                //////// Tablas movimiento_liquidaciones_empleado ////////
                                $eliminar = SQL::eliminar("movimiento_liquidaciones_empleado",$llave_movimiento);
                                //////// Tablas liquidaciones_empleado ///////////////////
                                $eliminar = SQL::eliminar("liquidaciones_empleado",$llave_movimiento);
                                //////////////////////////////////////////////////////////
                                $llave_consecutivo_documento  = " codigo_sucursal='$forma_codigo_sucursal' AND codigo_tipo_documento='$forma_tipo_documento'";
                                $llave_consecutivo_documento .= " AND documento_identidad_tercero='$forma_documento_empleado' AND fecha_registro='$fecha_registro_consecutivo' AND  consecutivo='$consecutivo_documento'";
                                $eliminar = SQL::eliminar("consecutivo_documentos",$llave_consecutivo_documento);

                                $error   = true;
                                $mensaje = $textos["ERROR_ADICIONAR_CONSECUTIVO_CHEQUE"];
                            }else{

                                $datos = array(
                                     /////////CUENTA BANCARIA//////////
                                    "codigo_sucursal_pertence"       => $codigo_sucursal_pertence,
                                    "tipo_documento_cuenta_bancaria" => $tipo_documento_cuenta_bancaria,
                                    "codigo_sucursal_banco"          => $codigo_sucursal_banco,
                                    "codigo_iso"                     => $codigo_iso,
                                    "codigo_dane_departamento"       => $codigo_dane_departamento,
                                    "codigo_dane_municipio"          => $codigo_dane_municipio,
                                    "codigo_banco"                   => $codigo_banco,
                                    "consecutivo_cheque"             => $consecutivo_cheque,
                                    "numero"                         => $numero
                                );

                                $llave_movimiento  = " codigo_empresa='$codigo_empresa' AND documento_identidad_empleado='$forma_documento_empleado'";
                                $llave_movimiento .= " AND fecha_generacion='$fecha_registro' AND motivo_retiro='$forma_movito_retiro'";
                                SQL::modificar("movimiento_liquidaciones_empleado", $datos,$llave_movimiento);

                                if (!$insertar) {
                                    $error   = true;
                                    $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
                                }
                            }
                        }
                    }
                }
             }
         }
    }
    /// Enviar datos con la respuesta del proceso al script que originó la petición
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

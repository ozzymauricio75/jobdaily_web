<?php

/**
*
* Copyright (C) 2020 Jobdaily
* Raul Mauricio Oidor Lozano <ozzymauricio75@gmail.com>
*
* Este archivo es parte de:
* SEM :: Plataforma para la Administración del Nexo Cliente-Empresa
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

require("clases/clases.php");

$datos_movimiento           = array();
$datos_movimiento_valor     = array();
$datos_enviar               = array();
$valor_total_cesantias      = array();
$acumulado_transacciones    = 0;
$contador_tiempos_laborados = 0;
///////////////Informacion-Contrato-Empleado/////////////////////
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
   $consulta_tiempo_laborados = SQL::seleccionar(array("movimiento_tiempos_laborados"),array("*"),"documento_identidad_empleado='$url_documento_empleado' AND contabilizado!='2' ");
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

           $valor_horas_extras_pendiente += $datos_tiempos->valor_hora_recargo;
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
////////////////Determino de total de la deuda//////////////////
if(isset($url_prestamos_empleado))
{
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


if(!empty($url_generar)) {

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
    }else{

        if(empty($url_id)){
            $error     = $textos["ERROR_CONSULTAR_VACIO"];
            $titulo    = "";
            $contenido = "";
        }else{
            $error      = "";
            $titulo     = $componente->nombre;
            $modulo     = SQL::obtenerValor("componentes", "id_modulo", "id = '".$componente->id."'");
            $oculto     = "oculto";
            /////Llave primaria de liquidacion empleado////
            $llave_primaria               = explode("|",$url_id);
            $codigo_empresa               = $llave_primaria[0];
            $documento_identidad_empleado = $llave_primaria[1];
            $fecha_generacion             = $llave_primaria[2];
            $motivo_retiro                = $llave_primaria[3];
            ///////////////////////////////////////////////////
            ///transacciones contables donde su concepto es prestamos a empleados  009////
            $listado_transacciones_contables = HTML::generarDatosLista("transacciones_contables_empleado","codigo","descripcion","codigo_concepto_transaccion_contable='9' AND sentido='D'");
            ///////////////////////////////////////////////////
            ///Obtener lista de sucursales para selección///
            $codigo_empresa = SQL::obtenerValor("sucursales", "codigo_empresa", "codigo = '$sesion_sucursal'");
            $preferencia_cuota_minima=SQL::obtenerValor("preferencias","valor","variable = 'valor_cuota_minima_pago' AND codigo_empresa='$codigo_empresa' AND tipo_preferencia='2'");
            /*** Definición de pestaña Basica ***/
            ///Obtengo los datos de la liquidacion
            $condicion  = " codigo_empresa='$codigo_empresa' AND documento_identidad_empleado='$documento_identidad_empleado'";
            $condicion .= " AND fecha_generacion='$fecha_generacion' AND motivo_retiro='$motivo_retiro'";
            
            $consulta_liquidacion_empleado = SQL::seleccionar(array("liquidaciones_empleado"),array("*"),$condicion);
            $datos_liquidacion_empleado    = SQL::filaEnObjeto($consulta_liquidacion_empleado);

            $codigo_sucursal = $datos_liquidacion_empleado->codigo_sucursal;
            $nombre_sucursal = SQL::obtenerValor("sucursales","nombre","codigo='$codigo_sucursal'");

            $nombre_empleado = SQL::obtenerValor("seleccion_empleados","SUBSTRING_INDEX(nombre_completo,'|',1)","id='$documento_identidad_empleado'");
 
            ////////Informacion del documento generado///////
            $condicion_consetivo_documento  = " codigo_sucursal='$codigo_sucursal' AND codigo_tipo_documento='$datos_liquidacion_empleado->codigo_tipo_documento'";
            $condicion_consetivo_documento .= " AND documento_identidad_tercero='$datos_liquidacion_empleado->documento_identidad_empleado' AND fecha_registro='$datos_liquidacion_empleado->fecha_generacion_consecutivo' AND consecutivo='$datos_liquidacion_empleado->consecutivo_documento'";
            ////////////////////////////////////////////////
            $numero_documento      = $datos_liquidacion_empleado->consecutivo_documento;
           
            $codigo_tipo_documento = $datos_liquidacion_empleado->codigo_tipo_documento;
            $nombre_tipo_documento = SQL::obtenerValor("tipos_documentos","descripcion","codigo='$codigo_tipo_documento'");
            $genera_cheques        = SQL::obtenerValor("tipos_documentos","genera_cheque","codigo = '$codigo_tipo_documento'");
            ////////////Movimiento generado por la liquidacion///////////
            $consulta_movimiento_liquidacion = SQL::seleccionar(array("movimiento_liquidaciones_empleado"),array("*"),$condicion);
            $datos_movimiento_liquidacion    = SQL::filaEnObjeto($consulta_movimiento_liquidacion);
            $nombre_cuenta                   = SQL::obtenerValor("seleccion_plan_contable","SUBSTRING_INDEX(codigo_contable,'|',1)","id='$datos_movimiento_liquidacion->codigo_plan_contable'");
            ////////////Motivo de retiro/////////////
            $nombre_motivo_retiro = SQL::obtenerValor("motivos_retiro","descripcion","codigo='$datos_liquidacion_empleado->motivo_retiro'");

            if($genera_cheques=="1"){
                $oculto = "";
                $consecutivo_cheque = round($datos_movimiento_liquidacion->consecutivo_cheque);
                $condicion_cuenta_bancaria  = $datos_movimiento_liquidacion->codigo_sucursal_pertence.'|'.$datos_movimiento_liquidacion->tipo_documento_cuenta_bancaria.'|'.$datos_movimiento_liquidacion->codigo_banco;
                $condicion_cuenta_bancaria .= '|'.$datos_movimiento_liquidacion->numero.'|'.$consecutivo_cheque;
                $consulta_cuenta_bancaria   = SQL::seleccionar(array("buscador_cuentas_bancarias"),array("*"),$condicion_cuenta_bancaria);
                $datos_cuenta_bancaria      = SQL::filaEnObjeto($consulta_cuenta_bancaria);
                $cuenta_bancaria            = $datos_cuenta_bancaria->BANCO." - No. ".$datos_cuenta_bancaria->NUMERO;
            }else{
                $consecutivo_cheque = "";
                $cuenta_bancaria    = "";
            }

            $formularios["PESTANA_BASICA"] = array(
               array(
                    HTML::mostrarDato("mostrar_fecha_liquidacion", $textos["FECHA_LIQUIDACION"],$datos_liquidacion_empleado->fecha_liquidacion),
                    HTML::mostrarDato("fecha_contabilizacion", $textos["FECHA_CONTABILIZACION"],$datos_liquidacion_empleado->fecha_contabilizacion),
                    HTML::campoOculto("fecha_liquidacion",$datos_liquidacion_empleado->fecha_liquidacion)
               ),
               array(
                     HTML::mostrarDato("nombre_sucursal", $textos["SUCURSAL_LABORA"],$nombre_sucursal),
                     HTML::campoOculto("codigo_sucursal",$codigo_sucursal),
                     HTML::mostrarDato("nombre_empleado", $textos["EMPLEADO"],$nombre_empleado)
                    .HTML::campoOculto("documento_empleado",$documento_identidad_empleado)
                    .HTML::campoOculto("codigo_planilla","")
                    .HTML::campoOculto("codigo_empresa",$codigo_empresa)
                    .HTML::campoOculto("cuota_minima",$preferencia_cuota_minima)
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
                    .HTML::campoOculto("minDate","7")
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
                    .HTML::campoOculto("tipo_contratacion","")

                   ),
                   array(
                        HTML::mostrarDato("tipo_documento", $textos["TIPO_DOCUMENTO"],$nombre_tipo_documento),
                        HTML::contenedor(HTML::mostrarDato("cuenta_bancaria", $textos["CUENTA_BANCARIA"],$cuenta_bancaria), array("class" => $oculto)),
                        HTML::contenedor(HTML::mostrarDato("consecutivo_cheque", $textos["CONSECUTIVO_CHEQUE"],$consecutivo_cheque), array("class" => $oculto)),
                    ),
                   array(
                        HTML::mostrarDato("consecutivo_documento", $textos["CONSECUTIVO_DOCUMENTO"],(int)$numero_documento),
                        HTML::mostrarDato("*cuenta_plan_contable", $textos["CUENTA"],$nombre_cuenta)
                    ),
                   array(
                        HTML::mostrarDato("movito_retiro",$textos["MOTIVO_RETIRO"],$nombre_motivo_retiro)
                    ),
                   array(
                        HTML::campoTextoLargo("observaciones", $textos["OBSERVACIONES"], 4, 50,$datos_liquidacion_empleado->observaciones, array("title" => $textos["AYUDA_OBSERVACIONES"],"readonly" => "readonly"))
                    ),
                   array(
                        HTML::campoOculto("salario_base", $textos["SALARIO_BASE"],""),
                        HTML::campoOculto("fecha_ingreso_empleado",""),
                        HTML::campoOculto("manejo_auxilio_transporte","")
                   )
                );

                $funciones["CALCULO_LIQUIZACION"]   = "informacionEmpleado();informacionEmpleado();";
   
                $formularios["CALCULO_LIQUIZACION"] = array(
                    array(
                        HTML::contenedor(HTML::generarTabla(
                            array("id","","","VALOR_PRESTACIONES"),
                            "",
                            array("I","I","I"),
                            "calculo_prestaciones",
                            false
                            )
                        )
                    )
                );

                $funciones["REPORTE_RETIROS"]   = "datosDeCesantias();validarCamposObligatorios('3');";
                $formularios["REPORTE_RETIROS"] = array(
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
                );
                //// Definición de botones
                $botones = array(
                                HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar")
                            );
                $contenido = HTML::generarPestanas($formularios, $botones,"",$funciones);
            }
            /// Enviar datos para la generación del formulario al script que originó la petición
    }
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);
}

?>

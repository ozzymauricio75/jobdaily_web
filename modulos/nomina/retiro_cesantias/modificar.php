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
     * */

 require("clases/clases.php");
    $concepto_retiro_cesantias = array(
        "1" => $textos["VIVIENDA"],
        "2" => $textos["EDUCACION"]
    );

///////////////Informacion-Contrato-Empleado/////////////////////
if(isset($url_informacion_empleado)){

    $condicion_ingreso                    = "documento_identidad_empleado='$url_documento_empleado' AND codigo_sucursal_activo ='$url_codigo_sucursal' AND estado='1'";
    $consulta_ingreso_contrato            = SQL::seleccionar(array("ingreso_empleados"),array("*"),$condicion_ingreso);
    $datos_ingreso_contrato               = SQL::filaEnObjeto($consulta_ingreso_contrato);

    $condicion_sucursal_contrato          = "documento_identidad_empleado='$url_documento_empleado' AND codigo_empresa='$datos_ingreso_contrato->codigo_empresa'";
    $consulta_sucursal_contrato_empleados = SQL::seleccionar(array("sucursal_contrato_empleados"),array("*"),$condicion_sucursal_contrato, "", "fecha_ingreso_sucursal DESC",0, 1);
    $datos_sucursal_contrato_empleados    = SQL::filaEnObjeto($consulta_sucursal_contrato_empleados);

    $respuesta = array();
    $respuesta[] = $datos_sucursal_contrato_empleados->fecha_ingreso;
    $respuesta[] = $datos_sucursal_contrato_empleados->salario_mensual;
    $respuesta[] = $datos_ingreso_contrato->manejo_auxilio_transporte ;
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
    $valor_retiro        = 0;

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
    $arreglo_retiros[] = round($valor_total_cesantias+$valor_retiro);
    HTTP::enviarJSON($arreglo_retiros);
    exit;
}
    /////////////////////////////////////////////////////
    // Verificar si el modulo del componente actual esta habilitado en el periodo contable actual
    $sentidos = array(
        "1" => $textos["DEBITO"],
        "2" => $textos["CREDITO"]
    );

    if (isset($url_recargar_consecutivo_cheque)) {
        $llave_cuenta = explode('|', $url_cuenta);
        $consecutivo_cheque = SQL::obtenerValor("consecutivo_cheques", "MAX(consecutivo)", "codigo_tipo_documento = '" . $llave_cuenta[1] . "' AND codigo_banco = '" . $llave_cuenta[6] . "' AND numero = '" . $llave_cuenta[7] . "'");
        if (!$consecutivo_cheque) {
            $consecutivo_cheque = 1;
        } else {
            $consecutivo_cheque++;
        }
        $cuenta = SQL::obtenerValor("seleccion_plan_contable_transacciones", "SUBSTRING_INDEX(cuenta,'|',1)", "id = '" . $llave_cuenta[8] . "'");

        unset($llave_cuenta[8]);

        $llave = implode('|', $llave_cuenta);

        $auxiliar = SQL::obtenerValor("buscador_cuentas_bancarias", "id_auxiliar", "id = '" . $llave . "'");

        $descripcion = SQL::obtenerValor("seleccion_auxiliares_contables", "descripcion", "id = '" . $auxiliar . "'");

        $datos = array($consecutivo_cheque, $cuenta, $auxiliar, $descripcion, $llave);
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
// Devolver datos para recargar informacion requerida
    if (isset($url_recargarDatosDocumento)) {

        $datos = array();
        // Obtener consecutivo de documento si tiene manejo automatico
        $manejo = SQL::obtenerValor("tipos_documentos", "manejo_automatico", "codigo = '" . $url_documento . "'");
        if ($manejo == '2') {
            $consecutivo_documento = SQL::obtenerValor("consecutivo_documentos", "MAX(consecutivo)", "codigo_sucursal = '" . $url_sucursal . "' AND codigo_tipo_documento = '" . $url_documento . "'");
            if (!$consecutivo_documento) {
                $consecutivo_documento = 1;
            } else {
                $consecutivo_documento++;
            }
            $datos["consecutivo_documento"] = $consecutivo_documento;
        } else {
            $datos["consecutivo_documento"] = 0;
        }

        // Obtener cuentas bancarias si genera cheques
        $cheques = SQL::obtenerValor("tipos_documentos", "genera_cheque", "codigo = '" . $url_documento . "'");
        $datos["genera_cheque"] = $cheques;
        if ($cheques == '1') {
            $primer_cuenta = false;
            $consulta = SQL::seleccionar(array("buscador_cuentas_bancarias"), array("*"), "id_sucursal = '" . $url_sucursal . "' AND id_documento = '" . $url_documento . "'");
            if (SQL::filasDevueltas($consulta)) {
                while ($datos_cuenta = SQL::filaEnObjeto($consulta)) {
                    if ($primer_cuenta == false) {
                        $primer_cuenta = $datos_cuenta->id;
                    }
                    $llave_cuenta = explode('|', $datos_cuenta->id);
                    $id_plan_cuenta = SQL::obtenerValor("cuentas_bancarias", "codigo_plan_contable", "codigo_sucursal = '" . $llave_cuenta[0] . "' AND codigo_tipo_documento = '" . $llave_cuenta[1] . "' AND codigo_banco = '" . $llave_cuenta[6] . "' AND numero = '" . $llave_cuenta[7] . "' AND codigo_sucursal_banco = '" . $llave_cuenta[2] . "'");
                    $datos[$datos_cuenta->id . "|" . $id_plan_cuenta] = $datos_cuenta->BANCO . " - No. " . $datos_cuenta->NUMERO;
                }
                $llave_cuenta = explode('|', $primer_cuenta);
                $consecutivo_cheque = SQL::obtenerValor("consecutivo_cheques", "MAX(consecutivo)", "codigo_tipo_documento = '" . $llave_cuenta[1] . "' AND codigo_banco = '" . $llave_cuenta[6] . "' AND numero = '" . $llave_cuenta[7] . "'");
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

    if (isset($url_verificar)) {
        $condicion_extra = "id_sucursal='$url_codigo_sucursal'";
        echo SQL::datosAutoCompletar("seleccion_empleados", $url_q, $condicion_extra);
        exit;
    }

    ///Generar el formulario para la captura de datos///
    if (!empty($url_generar)) {

        //Verificar que se haya enviado el ID del elemento a modificar///
        if (empty($url_id)) {
            $error = $textos["ERROR_MODIFICAR_VACIO"];
            $titulo = "";
            $contenido = "";
        }else{

            $llave_principal              = explode("|",$url_id);
            $documento_identidad_empleado = $llave_principal[0];
            $consecutivo                  = $llave_principal[1];
            $fecha_generacion             = $llave_principal[2];
            $concepto_retiro              = $llave_principal[3];

            $condicion = "documento_identidad_empleado='$documento_identidad_empleado' ORDER BY fecha_generacion DESC LIMIT 0,1 ";
            $fecha_ultimo_retiro = SQL::obtenerValor("retiro_cesantias","fecha_generacion",$condicion);
            /*echo var_dump($fecha_ultimo_retiro);
            echo var_dump($fecha_generacion);*/
            if($fecha_ultimo_retiro==$fecha_generacion){
                $error         = "";
                $titulo        = $componente->nombre;
                $consecutivo_documento = "";
                 ///transacciones contables donde su concepto es prestamos a empleados  009////
                $listado_transacciones_contables = HTML::generarDatosLista("transacciones_contables_empleado","codigo","descripcion","codigo_concepto_transaccion_contable='9' AND sentido='D'");
                ////////////////////////////////////////////////////
                ///Obtener lista de sucursales para selección///
                $codigo_empresa = SQL::obtenerValor("sucursales", "codigo_empresa", "codigo = '$sesion_sucursal'");
                ///Cargo las sucursales dependiendo del codigo de la empresa con que se incia seccion///
                $sucursales = HTML::generarDatosLista("sucursales", "codigo", "nombre","codigo_empresa='$codigo_empresa'");
                $documento  = SQL::obtenerValor("tipos_documentos", "codigo", "codigo != '0' ORDER BY descripcion LIMIT 1");

                $condicion                     = "documento_identidad_empleado= '$documento_identidad_empleado' AND consecutivo='$consecutivo' AND fecha_generacion='$fecha_generacion' AND concepto_retiro='$concepto_retiro'";
                $consulta_retiro_cesantias     = SQL::seleccionar(array("retiro_cesantias"),array("*"),$condicion);
                $datos_retiro_cesantias        = SQL::filaEnObjeto($consulta_retiro_cesantias);
                ////Obtego datos/////////////////////
                $nombre_sucursal    = SQL::obtenerValor("sucursales","nombre","codigo='$datos_retiro_cesantias->codigo_sucursal'");
                $nombre_empleado    = SQL::obtenerValor("menu_retiro_cesantias","NOMBRE_EMPLEADO","id='$url_id'");
                $nombre_transaccion = SQL::obtenerValor("transacciones_contables_empleado","descripcion","codigo='$datos_retiro_cesantias->codigo_transaccion_contable'");
                $nombre_concepto    = $concepto_retiro_cesantias[(int)$datos_retiro_cesantias->concepto_retiro];
                ////Definición de pestaña basica////
                ////////////////////////////////////////////////////
                $cuentas_bancarias  = array();
                $cheques            = SQL::obtenerValor("tipos_documentos", "genera_cheque", "codigo = '".$documento."'");
                if ($cheques == 1) {
                    $primer_cuenta = false;
                    $consulta   = SQL::seleccionar(array("buscador_cuentas_bancarias"), array("*"),"id_sucursal='$datos_retiro_cesantias->codigo_sucursal'");
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

                ///////////////////////////////////////////////////
                $tipo_documento_genera_cheque   = SQL::obtenerValor("tipos_documentos", "genera_cheque", "codigo = '$datos_retiro_cesantias->codigo_tipo_documento'");
                if($tipo_documento_genera_cheque=='1')
                {
                    $oculto   = '';
                    $disabled = "disabled";
                }else
                {
                    $oculto   ='oculto';
                    $disabled = '';
                }
                //////////////////////////////////////////////////
                ////////Consulta del consecutivo generado/////////
                $consecutivo_documento = number_format($datos_retiro_cesantias->consecutivo_documento);
                $tipo_comprobante      = SQL::obtenerValor("tipos_documentos", "codigo_comprobante", "codigo = '$datos_retiro_cesantias->codigo_tipo_documento'");
                $llave_inicial_tabla = $datos_retiro_cesantias->documento_identidad_empleado.'|'.$datos_retiro_cesantias->consecutivo.'|'.$datos_retiro_cesantias->fecha_generacion.'|'.$datos_retiro_cesantias->concepto_retiro;
                /////////////////////////////////////////////////
                $codicion_movimiento  = $condicion;
                $consulta_movimientos_retiro_cesantias = SQL::seleccionar(array("movimiento_retiro_cesantias"),array("*"),$codicion_movimiento);
                $datos_movimiento_cesantias            = SQL::filaEnObjeto($consulta_movimientos_retiro_cesantias);
                /////////////////////////////////////////////////
                $codigo_contable = $datos_movimiento_cesantias->codigo_plan_contable;
                $cuenta = SQL::obtenerValor("seleccion_plan_contable_transacciones","SUBSTRING_INDEX(cuenta,'|',1)","id = '$codigo_contable'");
                $read   = "";
                $arreglo = array();

                $arreglo[] = array(
                HTML::campoTextoCorto("*fecha_liquidacion", $textos["FECHA_LIQUIDACION"], 10, 10,date("Y-m-d"), array("title" => $textos["AYUDA_FECHA_LIQUIDACION"], "class" => "selectorFecha")),
                HTML::campoTextoCorto("*fecha_contabilizacion", $textos["FECHA_CONTABILIZACION"], 10, 10,date("Y-m-d"), array("title" => $textos["AYUDA_FECHA_CONTABILIZACION"], "class" => "selectorFecha")),
                );
                $arreglo[] = array(
                    HTML::mostrarDato("codigo_sucursal_muestra",  $textos["SUCURSAL_LABORA"],$nombre_sucursal),
                    HTML::mostrarDato("nombre_empleado", $textos["EMPLEADO"],$nombre_empleado).
                    HTML::campoOculto("documento_empleado",$documento_identidad_empleado).
                    HTML::campoOculto("codigo_sucursal",$datos_retiro_cesantias->codigo_sucursal)
                   .HTML::campoOculto("fecha_ingreso_empleado","")
                   .HTML::campoOculto("salario_base","")
                   .HTML::campoOculto("manejo_auxilio_transporte","")
                   .HTML::campoOculto("oculto_valor_cesantias","")
                   .HTML::campoOculto("fecha_generacion",$datos_retiro_cesantias->fecha_generacion)
                   .HTML::campoOculto("llave_consecutivo_documento",$llave_inicial_tabla)
                );
                $arreglo[] = array(
                     HTML::listaSeleccionSimple("*tipo_documento", $textos["TIPO_DOCUMENTO"], HTML::generarDatosLista("tipos_documentos", "codigo", "descripcion", "codigo != 0"),$datos_retiro_cesantias->codigo_tipo_documento, array("title" => $textos["AYUDA_TIPO_DOCUMENTO"],"onchange" => "recargarDatosDocumento()")),
                     HTML::listaSeleccionSimple("cuenta_bancaria", $textos["CUENTA_BANCARIA"], $cuentas_bancarias, "", array("class" => $oculto,$banco_disabled => $banco_disabled, "onChange" => "consecutivoCheque();")), //,$disabled => $disabled
                     HTML::campoTextoCorto("*consecutivo_cheque", $textos["CONSECUTIVO_CHEQUE"], 10, 10, $consecutivo_cheque, array("title" => $textos["AYUDA_CONSECUTIVO_CHEQUE"], "class" => $oculto, "readonly" => "readonly",$banco_disabled => $banco_disabled)),

                    );
                $arreglo[] = array(
                     HTML::campoTextoCorto("*consecutivo_documento", $textos["CONSECUTIVO_DOCUMENTO"], 10, 10,$consecutivo_documento, array("title" => $textos["AYUDA_CONSECUTIVO_DOCUMENTO"],$read => $read)),
                     HTML::campoTextoCorto("*selector2", $textos["CUENTA"], 40, 255,$cuenta,array("title" => $textos["AYUDA_CUENTA"], "class" => "autocompletable",$disabled => $disabled,"onclick" => "informacionEmpleado();"))
                    .HTML::campoOculto("codigo_contable",$codigo_contable),
                    //HTML::listaSeleccionSimple("sentido", $textos["SENTIDO"], $sentidos, "", array("title" => $textos["AYUDA_SENTIDO"],"onChange" => "recargarDatosCuenta();"))
                );
                $arreglo[] =array(
                    HTML::listaSeleccionSimple("*codigo_transaccion_contable", $textos["TRANSACCION_CONTABLE"],$listado_transacciones_contables,$datos_retiro_cesantias->codigo_transaccion_contable,array("title" => $textos["AYUDA_TRANSACCION_CONTABLE"])),
                );
                $arreglo[] = array(
                    HTML::campoOculto("codigo_tipo_documento",$datos_retiro_cesantias->codigo_tipo_documento)
                    );

                $arreglo[] = array(
                    HTML::listaSeleccionSimple("*concepto_retiro", $textos["CONCEPTO_PRESTAMO"],$concepto_retiro_cesantias,$datos_retiro_cesantias->concepto_retiro, array("title" => $textos["AYUDA_CONCEPTO_RETIRO"])),
                    HTML::campoTextoCorto("*valor_cesantias", $textos["VALOR_CESANTIAS"], 10, 20,"", array("title" => $textos["AYUDA_VALOR_CESANTIAS"],"onKeyPress" => "return campoEntero(event)","disabled" => "disabled")),
                    HTML::campoTextoCorto("*valor_retiro", $textos["VALOR_RETIRO"], 10, 20,round($datos_retiro_cesantias->valor_retiro), array("title" => $textos["AYUDA_VALOR_RETIRO"],"onKeyPress" => "return campoEntero(event)","onkeyup" => "determinarRetiro();" ))
                    //HTML::campoOculto("valor_descuento",$datos_control_prestamo->valor_pago)
                    );

                 $arreglo[] = array(
                    HTML::campoTextoLargo("observaciones", $textos["OBSERVACIONES"], 4, 50,$datos_retiro_cesantias->observaciones, array("title" => $textos["AYUDA_OBSERVACIONES"]))
                    );

                 $formularios["PESTANA_BASICA"] = $arreglo;

                    /// Definición de botones
                  $botones = array(
                      HTML::boton("botonAceptar", $textos["ACEPTAR"], "modificarItem('$url_id');", "aceptar")
                  );
                $contenido = HTML::generarPestanas($formularios,$botones);
            }else{
                $error = $textos["NO_PERMITIR_MODIFICAR"];
                $titulo = "";
                $contenido = "";
            }
        }
    /// Enviar datos para la generación del formulario al script que originó la petición
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);
     ///Validar los datos provenientes del formulario
    } elseif (!empty($forma_procesar)) {
        /*** Asumir por defecto que no hubo error ***/
        $error   = false;
        $mensaje = $textos["ITEM_ADICIONADO"];
        $continuar = true;
        ///////Validar que el tipo de documento genera cheque tenga cuenta//////
        $genera_cheques = SQL::obtenerValor("tipos_documentos", "genera_cheque", "codigo = '$forma_tipo_documento'");
        if($genera_cheques=='1')
        {
         $consulta       = SQL::seleccionar(array("buscador_cuentas_bancarias"), array("*"),"id_sucursal = '$forma_codigo_sucursal' AND id_documento = '$forma_tipo_documento'");
             if(SQL::filasDevueltas($consulta))
             {
                $continuar = false;
             }
        }else{
                $continuar = false;
        }

            ///////////////////////////////////////////////////////////////////////
           // Guardar datos del documento que genera el movimiento contable
            $id_tabla   = SQL::obtenerValor("tablas", "id", "nombre_tabla = 'movimiento_retiro_cesantias'");
            $manejo     = SQL::obtenerValor("tipos_documentos", "manejo_automatico", "codigo = '".$forma_tipo_documento."'");
            if ($manejo == 2) {
                $consecutivo_documento = SQL::obtenerValor("consecutivo_documentos", "MAX(consecutivo)", "codigo_sucursal = '".$forma_codigo_sucursal."' AND codigo_tipo_documento = '".$forma_tipo_documento."'");
                if(!$consecutivo_documento){
                    $consecutivo_documento = 1;
                }else{
                    $consecutivo_documento++;
                    $existe_concecutivo=false;
                }
            } else {
                $consecutivo_documento = $forma_consecutivo_documento;
                $existe_concecutivo = SQL::existeItem("consecutivo_documentos","consecutivo", $consecutivo_documento, "codigo_sucursal = '".$forma_codigo_sucursal."' AND codigo_tipo_documento = '".$forma_tipo_documento."'");
            }

          ///////////////////////////////////////////////////////////////////////
            if($continuar){
            $error = true;
            $mensaje = $textos["CUENTAS_BANCARIAS_VACIAS"];
            }elseif($existe_concecutivo){
            $error = true;
            $mensaje = $textos["CONSECUTIVO_DOCUMENTO_EXISTE"];
            }elseif (empty($forma_documento_empleado)){
            $error = true;
            $mensaje = $textos["ERROR_VACIO_EMPLEADO"];
        }elseif (empty($forma_consecutivo_documento)){
            $error   = true;
            $mensaje = $textos["CONSECUTIVO_DOCUMENTO_VACIO"];

        }elseif(empty($forma_codigo_transaccion_contable)){

            $error = true;
            $mensaje = $textos["ERROR_VACIO_TRANSACCION"];
        }elseif(empty($forma_valor_retiro)){

            $error = true;
            $mensaje = $textos["ERROR_VACIO_VALOR_RETIRO"];
        }else {
            ///////////////////////////////////////

            $vistaConsulta                        = "sucursal_contrato_empleados";
            $columnas                             = SQL::obtenerColumnas($vistaConsulta);
            $consulta_sucursal_contrato_empleados = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo_empresa='$forma_codigo_empresa' AND documento_identidad_empleado='$forma_documento_empleado'", "", "fecha_ingreso_sucursal DESC", 0, 1); ////////////
            $datos_sucursal_contrato_empleados    = SQL::filaEnObjeto($consulta_sucursal_contrato_empleados);
            //$tipo_documento=SQL::obtenerValor("preferencias","valor","variable = 'codigo_prestamo_empleados' AND codigo_empresa='$forma_codigo_empresa' AND tipo_preferencia='2'");
            $tipo_documento=$forma_tipo_documento;
            $sentido = SQL::obtenerValor("transacciones_contables_empleado","sentido","codigo = '$forma_codigo_transaccion_contable'");
            $codigo_contable = SQL::obtenerValor("transacciones_contables_empleado","codigo_contable","codigo = '$forma_codigo_transaccion_contable'");
            //////////////Generó la llave de la tabla////////////////

            $consecutivo = (int)SQL::obtenerValor("retiro_cesantias","max(consecutivo)","");
                if($consecutivo){
                    $consecutivo++;
                }else{
                    $consecutivo=1;
             }


            $tipo_comprobante  = SQL::obtenerValor("tipos_documentos", "codigo_comprobante", "codigo = '$tipo_documento'");
            $llave_tabla = $forma_documento_empleado.'|'.$consecutivo.'|'.$forma_fecha_generacion.'|'.$forma_concepto_retiro;

             if(SQL::existeItem("menu_movimientos_contables","id",$llave_tabla)){
                $error   = true;
                $mensaje = $textos["ERROR_EXISTE_CUENTA"];
            }else{

                  $datos = array (
                    "codigo_sucursal"             => $datos_sucursal_contrato_empleados->codigo_sucursal,
                    "codigo_tipo_documento"       => $forma_tipo_documento,
                    "fecha_registro"              => $forma_fecha_generacion,
                    "documento_identidad_tercero" => '0',
                    "consecutivo"                 => $consecutivo_documento,
                    "id_tabla"                    => $id_tabla,
                    "llave_tabla"                 => $llave_tabla,
                    "codigo_sucursal_archivo"     => '0',
                    "consecutivo_archivo"         => '0'
                );

                $insertar = SQL::modificar("consecutivo_documentos", $datos,"llave_tabla='$forma_llave_consecutivo_documento'");

                if (!$insertar) {
                    $error   = true;
                    $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
                     //$mensaje = mysql_error();
                }else{

            ///////////Datos de la cuenta afectada////////////
            $tipo_documento_genera_cheque   = SQL::obtenerValor("tipos_documentos", "genera_cheque", "codigo = '$tipo_documento'");
            $flujo_efectivo = SQL::obtenerValor("plan_contable","flujo_efectivo","codigo_contable='$forma_codigo_contable'");

            if($tipo_documento_genera_cheque=='1'){

                $llave_cuenta_bancaria          = explode("|",$forma_cuenta_bancaria);
                $codigo_sucursal_pertence       = $llave_cuenta_bancaria[0];
                $tipo_documento_cuenta_bancaria = $llave_cuenta_bancaria[1];
                $codigo_sucursal_banco          = $llave_cuenta_bancaria[2];
                $codigo_iso                     = $llave_cuenta_bancaria[3];
                $codigo_dane_departamento       = $llave_cuenta_bancaria[4];
                $codigo_dane_municipio          = $llave_cuenta_bancaria[5];
                $codigo_banco                   = $llave_cuenta_bancaria[6];
                $numero                         = $llave_cuenta_bancaria[7];

            }else{
                $codigo_sucursal_pertence       = '0';
                $tipo_documento_cuenta_bancaria = '0';
                $codigo_sucursal_banco          = '0';
                $codigo_iso                     = '';
                $codigo_dane_departamento       = '';
                $codigo_dane_municipio          = '';
                $codigo_banco                   = '0';
                $numero                         = '';
            }

              
               $datos = array (
                    "fecha_generacion"               => $forma_fecha_generacion,
                    "fecha_liquidacion"              => $forma_fecha_liquidacion,
                    "fecha_contabilizacion"          => $forma_fecha_contabilizacion,
                    //contrato_sucursal_empleado//
                    "codigo_empresa"                 => $forma_codigo_empresa,
                    "documento_identidad_empleado"   => $forma_documento_empleado,
                    "fecha_ingreso"                  => $datos_sucursal_contrato_empleados->fecha_ingreso,
                    "codigo_sucursal"                => $datos_sucursal_contrato_empleados->codigo_sucursal,
                    "fecha_ingreso_sucursal"         => $datos_sucursal_contrato_empleados->fecha_ingreso_sucursal,
                    //////////////////////////////
                    "consecutivo"                    => $consecutivo,
                    "codigo_tipo_documento"          => $tipo_documento,
                    "consecutivo_documento"          => $consecutivo_documento,
                    /////////////////////////////
                    "codigo_transaccion_contable"    => $forma_codigo_transaccion_contable,
                    ////////////////////////////
                    "codigo_contable"                => $codigo_contable,
                    "concepto_retiro"                => $forma_concepto_retiro,
                    "sentido"                        => $sentido,
                    "fecha_ultima_planilla"          => $forma_utima_fecha,
                    "valor_retiro"                   => $forma_valor_retiro,
                    "autorizado"                     => "1",
                    "pagado"                         => "0",
                    "observaciones"                  => $forma_observaciones,
                    "codigo_usuario_modifica"        => $sesion_codigo_usuario

              );

             $insertar = SQL::modificar("retiro_cesantias", $datos);

               if (!$insertar) {
                $error   = true;
                $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
                $mensaje = mysql_error();
                }else{
                     $datos= array(
                            ///////////////////////////////////
                            "documento_identidad_empleado"   => $forma_documento_empleado,
                            "fecha_generacion"               => $forma_fecha_generacion,
                            "consecutivo"                    => $consecutivo,
                            "concepto_retiro"                => $forma_concepto_retiro,
                             //////DATOS CUENTA AFECTA/////////
                            "flujo_efectivo"                 => $flujo_efectivo,
                            "codigo_plan_contable"           => $forma_codigo_contable,
                            /////////CUENTA BANCARIA/////////
                            "codigo_sucursal_pertence"       => $codigo_sucursal_pertence,
                            "tipo_documento_cuenta_bancaria" => $tipo_documento_cuenta_bancaria,
                            "codigo_sucursal_banco"          => $codigo_sucursal_banco,
                            "codigo_iso"                     => $codigo_iso,
                            "codigo_dane_departamento"       => $codigo_dane_departamento,
                            "codigo_dane_municipio"          => $codigo_dane_municipio,
                            "codigo_banco"                   => $codigo_banco,
                            "numero"                         => $numero
                        );

                      $insertar = SQL::modificar("movimiento_retiro_cesantias", $datos);

                            if (!$insertar) {
                                $error = true;
                                $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
                                $mensaje = mysql_error();
                            }
                        }
                    }
                }
            }

        ///Enviar datos con la respuesta del proceso al script que originó la petición
        $respuesta = array();
        $respuesta[0] = $error;
        $respuesta[1] = $mensaje;
        HTTP::enviarJSON($respuesta);
    }
?>


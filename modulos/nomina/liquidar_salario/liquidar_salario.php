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
* de APTITUD PARA UN PROPÓSITO DETERMINADO. Consulte los detalles de
* la Licencia Pública General GNU para obtener una información más
* detallada.
*
* Debería haber recibido una copia de la Licencia Pública General GNU
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
**/

/*** Generar el formulario para la captura de datos ***/
if(isset($url_recargarTipoPlanilla)){
    $tipo_planilla = SQL::obtenerValor("planillas","periodo_pago","codigo='$url_codigo_planilla'");
    HTTP::enviarJSON($tipo_planilla);
}

if (!empty($url_recargar) && !empty($url_codigo_planilla) && !empty($url_ano_generacion) && !empty($url_mes_generacion) ) {


    $tipo_planilla = SQL::obtenerValor("planillas","periodo_pago","codigo='$url_codigo_planilla'");

    $periodo = "";
    if ($tipo_planilla == '1'){
        $periodo = array(
            "1" => $textos["MENSUAL"],
        );
    } else if($tipo_planilla == '2') {
        $periodo = array(
            "2" => $textos["PRIMERA_QUINCENA"],
            "3" => $textos["SEGUNDA_QUINCENA"],
        );
    } else if($tipo_planilla == '3')  {
        $periodo = array(
            "4" => $textos["PRIMERA_SEMANA"],
            "5" => $textos["SEGUNDA_SEMANA"],
            "6" => $textos["TERCERA_SEMANA"],
            "7" => $textos["CUARTA_SEMANA"],
            "8" => $textos["QUINTA_SEMANA"]
        );
    } else {
        $periodo = array(
            "9" => $textos["FECHA_UNICA"]
        );
    }

    if ($url_mes_generacion == 2){
        if (($url_ano_generacion % 4 ==0) && ($url_ano_generacion % 100 !=0 || $url_ano_generacion % 400 == 0)){
            $dia_fin = 29;
        } else {
            $dia_fin = 28;
        }
    } else {
            $dia_fin = 31;
    }

    $fecha_inicio = $url_ano_generacion."-".$url_mes_generacion."-01";
    $fecha_fin    = $url_ano_generacion."-".$url_mes_generacion."-".$dia_fin;

    $respuesta = HTML::generarDatosLista("fechas_planillas", "fecha", "fecha", "codigo_planilla='$url_codigo_planilla' AND (fecha BETWEEN '$fecha_inicio' AND '$fecha_fin')");

    $fechas = SQL::seleccionar(array("fechas_planillas"),array("fecha"),"codigo_planilla='$url_codigo_planilla' AND (fecha BETWEEN '$fecha_inicio' AND '$fecha_fin')");

    if (isset($url_periodo) && SQL::filasDevueltas($fechas)){
        $respuesta = $periodo;
    }

    HTTP::enviarJSON($respuesta);

}

if (!empty($url_generar)) {

    $error           = "";
    $titulo          = $componente->nombre;
    $error_continuar = false;

    $id_modulo = SQL::obtenerValor("componentes","id_modulo","id = '".$componente->id."'");
    $empresa   = SQL::obtenerValor("sucursales","codigo_empresa","codigo='".$sesion_sucursal."' AND codigo>0");

    if ($sesion_usuario == $datosGlobales["usuarioMaestro"]){
        $consulta_sucursales = SQL::seleccionar(array("sucursales"), array("codigo, nombre"), "codigo_empresa = '".$empresa."'");
    }else{
        // Obtener lista de sucursales para seleccion
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
        AND b.id_componente = '".$componente->id."' AND c.codigo_empresa = '".$empresa."'";

        $consulta_sucursales = SQL::seleccionar($tablas, $columnas, $condicion);
    }

    $error_sucursales = false;
    if (SQL::filasDevueltas($consulta_sucursales)){

        $pestana_sucursales   = array();
        $pestana_sucursales[] = array(HTML::boton("botonSeleccionarTodos", $textos["SELECCIONAR_TODOS"], "seleccionar_todas_sucursales();", "", array()));

        while ($datos_sucursales = SQL::filaEnObjeto($consulta_sucursales)){

            $codigo_sucursal = $datos_sucursales->codigo;
            $nombreSucursal  = $datos_sucursales->nombre;

            $pestana_sucursales[]   = array(
                HTML::marcaChequeo("sucursales[$datos_sucursales->codigo]", $datos_sucursales->nombre, $datos_sucursales->codigo, false, array("title" => $textos["AYUDA_SUCURSAL"], "id" => "sucursales_$datos_sucursales->codigo", "class" => "sucursales_electrodomesticos"))
            );
        }
    } else {
        $error_sucursales = true;
    }

    $consulta_planillas = SQL::seleccionar(array("planillas"),array("*"),"codigo>0");
    $error_planillas = false;
    if (SQL::filasDevueltas($consulta_planillas)){

        $planillas[0] = '';

        while ($datos_planilla = SQL::filaEnObjeto($consulta_planillas)){
            $planillas[$datos_planilla->codigo] = $datos_planilla->descripcion;
        }

    } else {
        $error_planillas = true;
    }

    $consulta_fechas_planillas = SQL::seleccionar(array("fechas_planillas"),array("*"),"codigo_planilla>0");
    $error_fechas_planilla = false;
    if (SQL::filasDevueltas($consulta_fechas_planillas)){

        while ($datos_fechas_planillas = SQL::filaEnObjeto($consulta_fechas_planillas)){
            $fechas_planillas[$datos_fechas_planillas->codigo_planilla."|".$datos_fechas_planillas->fecha] = $datos_fechas_planillas->fecha;
        }
    } else {
        $error_fechas_planilla = false;
    }

    $consulta_empleados = SQL::seleccionar(array("ingreso_empleados"),array("*"),"documento_identidad_empleado!=''");
    $error_empleados = false;
    if (!SQL::filasDevueltas($consulta_empleados)){
        $error_empleados = true;
    }

    $consulta_salario_minimo = SQL::seleccionar(array("salario_minimo"),array("*"),"codigo>0");
    $error_salario_minimo = false;
    if (!SQL::filasDevueltas($consulta_salario_minimo)){
        $error_salario_minimo = true;
    }

    $consulta_auxilio_trasnporte = SQL::seleccionar(array("auxilio_transporte"),array("*"),"codigo>0");
    $error_auxilio_transporte = false;
    if (!SQL::filasDevueltas($consulta_auxilio_trasnporte)){
        $error_auxilio_transporte = true;
    }

    if (!$error_sucursales && !$error_planillas && !$error_fechas_planilla && !$error_salario_minimo && !$error_auxilio_transporte){


        $ano = date("Y");
        $ano_planilla = array();
        for ($i=0;$i<=1;$i++){
            $ano_planilla[$ano] = $ano;
            $ano++;
        }
        $ano = date("Y");
        $mes = date("m");

        $meses = array(
            "01" => $textos["ENERO"],
            "02" => $textos["FEBRERO"],
            "03" => $textos["MARZO"],
            "04" => $textos["ABRIL"],
            "05" => $textos["MAYO"],
            "06" => $textos["JUNIO"],
            "07" => $textos["JULIO"],
            "08" => $textos["AGOSTO"],
            "09" => $textos["SEPTIEMBRE"],
            "10" => $textos["OCTUBRE"],
            "11" => $textos["NOVIEMBRE"],
            "12" => $textos["DICIEMBRE"]
        );

        $formularios["PESTANA_SUCURSALES"] = $pestana_sucursales;

        $formularios["PESTANA_BASICA"] = array(
            array(
                HTML::listaSeleccionSimple("*ano_generacion", $textos["ANO_PLANILLA"],$ano_planilla,$ano, array("title" => $textos["AYUDA_ANO_PLANILLA"], "class" => "selectorFecha", "onchange"=>"cargarFechaPago2();")),
                HTML::listaSeleccionSimple("*mes_generacion", $textos["MES_PLANILLA"],$meses,$mes, array("title" => $textos["AYUDA_MES_PLANILLA"], "class" => "selectorFecha", "onchange"=>"cargarFechaPago2();"))
            ),
            array(
                HTML::mostrarDato("datos_planilla",$textos["DATOS_PLANILLA"],"")
               .HTML::campoOculto("id_modulo", $id_modulo)
            ),
            array(
                HTML::listaSeleccionSimple("codigo_planilla",$textos["PLANILLA"],$planillas,"",array("title"=>$textos["AYUDA_PLANILLA"], "onchange"=>"cargarFechaPago2();"))
            ),
            array(
                HTML::listaSeleccionSimple("fecha_pago",$textos["FECHA_PAGO"], "","",array("title"=>$textos["AYUDA_FECHA_PAGO"],"class"=>"fecha_pago","onclick" => "determinarPeriodo();")),
                HTML::mostrarDato("nombre_periodo",$textos["PERIODO"],"")
            ),
            array(
                HTML::campoOculto("periodo","").
                HTML::campoOculto("mensual",$textos["MENSUAL"]).
                HTML::campoOculto("primera_quincena",$textos["PRIMERA_QUINCENA"]).
                HTML::campoOculto("segunda_quincena",$textos["SEGUNDA_QUINCENA"]).
                HTML::campoOculto("fecha_unica",$textos["FECHA_UNICA"])

            )
        );
        // Definicion de botones
        $botones = array(HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar"));

        $contenido = HTML::generarPestanas($formularios, $botones);
    } else {

        $error = $textos["VERIFICAR_DATOS"];
        if ($error_sucursales){
            $error .= $textos["ERROR_SUCURSALES"];
        }
        if($error_planillas){
            $error .= $textos["ERROR_PLANILLAS"];
        }
        if($error_fechas_planillas){
            $error .= $textos["ERROR_FECHAS_PLANILLAS"];
        }
        if($error_empleados){
            $error .= $textos["ERROR_EMPLEADOS"];
        }
        if($error_salario_minimo){
            $error .= $textos["ERROR_SALARIO_MINIMO"];
        }
        if($error_auxilio_transporte){
            $error .= $textos["ERROR_AUXILIO_TRANSPORTE"];
        }
        $contenido = "";
    }


    // Enviar datos para la generacion del formulario al script que origino la peticion
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

} elseif (!empty($forma_procesar)) {
    /*** Asumir por defecto que no hubo error ***/
    $error        = false;
    $mensaje      = $textos["SALARIO_GENERADO"];

    if ($forma_mes_generacion == 2){
        if (($forma_ano_generacion % 4 ==0) && ($forma_ano_generacion % 100 !=0 || $forma_ano_generacion % 400 == 0)){
            $dia_fin = 29;
        } else {
            $dia_fin = 28;
        }
    } else {
            $dia_fin = 30;
    }

    $fecha_inicio  = $forma_ano_generacion."-".$forma_mes_generacion."-01";
    $fecha_fin     = $forma_ano_generacion."-".$forma_mes_generacion."-".$dia_fin;
    $fecha         = strtotime($fecha_inicio) - strtotime($fecha_fin); //Hallo la diferencia de las fechas en segundos
    $dias_totales  = $fecha / (60 * 60 * 24); //Convierto la diferencia en dias
    $tipo_planilla = SQL::obtenerValor("planillas","periodo_pago","codigo='$forma_codigo_planilla'");

    if (!isset($forma_sucursales)){
        $error   = true;
        $mensaje = $textos["ERROR_SUCURSAL_VACIA"];

    } else if (empty($forma_fecha_pago)){
        $error   = true;
        $mensaje = $textos["ERROR_FECHA_PAGO"];

    } else if (empty($forma_periodo)){
        $error   = true;
        $mensaje = $textos["ERROR_PERIODO"];

    } else {
        $contadorNO = 0;
        $mensajeS   = $textos["ERROR_PERIODO1"];
        $mensajeS   .= $textos["ERROR_PERIODO2"];

        foreach($forma_sucursales as $codigo_sucursal){
            $consulta = SQL::obtenerValor("periodos_contables","estado","codigo_sucursal='$codigo_sucursal' AND '$forma_fecha_pago' BETWEEN fecha_inicio AND fecha_fin AND id_modulo='$forma_id_modulo' AND estado='1'");

            if(!$consulta){
                $nombreSucursal = SQL::obtenerValor("sucursales","nombre","codigo='$codigo_sucursal'");
                $mensajeS.="- ".$nombreSucursal."\n";
                $contadorNO+=1;
            }
        }

        if($contadorNO>0){
            $error        = true;
            $mensaje      = $mensajeS;

        }else{
            $datos_movimientos = array();
            $datos_movimientos_prestamos_terceros = array();

            $salario_minimo     = SQL::obtenerValor("salario_minimo","valor","fecha<='$forma_fecha_pago' ORDER BY fecha DESC LIMIT 0,1");
            $auxilio_transporte = SQL::obtenerValor("auxilio_transporte","valor","fecha<='$forma_fecha_pago' ORDER BY fecha DESC LIMIT 0,1");
            if ($salario_minimo && $auxilio_transporte){

                foreach ($forma_sucursales as $codigo_sucursal) {
                    $empleado = array();

                    $autoriza_liquidar = true;

                    $condicion_pago  = "ano_generacion = '".$forma_ano_generacion."'";
                    $condicion_pago .= " AND mes_generacion = '".$forma_mes_generacion."'";
                    $condicion_pago .= " AND codigo_planilla = '".$forma_codigo_planilla."'";
                    $condicion_pago .= " AND periodo_pago = '".$forma_periodo."'";
                    $condicion_pago .= " AND fecha_pago_planilla = '".$forma_fecha_pago."'";
                    $condicion_pago .= " AND codigo_sucursal = '".$codigo_sucursal."'";


                    $consulta_pago  = SQL::seleccionar(array("forma_pago_planillas_nomina"),array("pagada"),$condicion_pago);
                    if(SQL::filasDevueltas($consulta_pago)){
                        $datos_pago = SQL::filaEnObjeto($consulta_pago);
                        if($datos_pago->pagada == '1'){
                            $autoriza_liquidar = false;
                        }
                    }

                    if($autoriza_liquidar){

                        $datos_descontabilizar = array("contabilizado" => "0");

                        $condicion_movimientos = "ano_generacion='".$forma_ano_generacion."' AND mes_generacion='".$forma_mes_generacion."' AND codigo_planilla='".$forma_codigo_planilla."' AND periodo_pago='".$forma_periodo."' AND codigo_sucursal='".$codigo_sucursal."' AND fecha_pago_planilla='".$forma_fecha_pago."'";

                        $modificar = SQL::modificar("reporte_incapacidades",$datos_descontabilizar,$condicion_movimientos);
                        ///////////////////////////////////////////////////////////////
                        $eliminar  = SQL::modificar("movimiento_tiempos_no_laborados_dias",$datos_descontabilizar,$condicion_movimientos);
                        ///////////////////////////////////////////////////////////////
                        $eliminar  = SQL::modificar("movimiento_tiempos_no_laborados_horas",$datos_descontabilizar,$condicion_movimientos);
                        //echo "modificar reporte_incapacidades ".$modificar."\n";
                        $modificar = SQL::modificar("movimiento_tiempos_laborados",$datos_descontabilizar,$condicion_movimientos);
                        //echo "modificar movimiento_tiempos_laborados ".$modificar."\n";
                        $eliminar  = SQL::eliminar("movimientos_salud",$condicion_movimientos);
                        //echo "eliminar movimientos_salud ".$eliminar."\n";
                        $eliminar  = SQL::eliminar("movimientos_pension",$condicion_movimientos);
                        //echo "eliminar movimientos_pension ".$eliminar."\n";
                        $eliminar  = SQL::eliminar("movimientos_salarios",$condicion_movimientos);
                        //echo "eliminar movimientos_salarios ".$eliminar."\n";
                        $eliminar  = SQL::eliminar("movimientos_prima",$condicion_movimientos);
                        //echo "eliminar movimientos_salarios ".$eliminar."\n";
                        $eliminar  = SQL::eliminar("movimientos_auxilio_transporte",$condicion_movimientos);
                        //echo "eliminar movimientos_auxilio_transporte ".$eliminar."\n";
                        $eliminar  = SQL::eliminar("movimiento_cuenta_por_cobrar_descuento",$condicion_movimientos);
                        //////////// Elimino las forma de pago y todos los documentos que se generaron /////////
                        $consulta_prestamos = SQL::seleccionar(array("movimiento_control_prestamos_empleados"),array("*"),$condicion_movimientos);
                        if (SQL::filasDevueltas($consulta_prestamos)){

                            while ($datos_eliminar = SQL::filaEnObjeto($consulta_prestamos)){

                                $condicion_fecha = "documento_identidad_empleado='$datos_eliminar->documento_identidad_empleado'";
                                $condicion_fecha .= " AND consecutivo='$datos_eliminar->consecutivo_fecha_pago'";
                                $condicion_fecha .= " AND fecha_generacion='$datos_eliminar->fecha_generacion_control'";
                                $condicion_fecha .= " AND concepto_prestamo='$datos_eliminar->concepto_prestamo'";
                                $condicion_fecha .= " AND fecha_pago='$datos_eliminar->fecha_pago'";

                                $datos = array(
                                    "pagada" => "0"
                                );
                                $modificar = SQL::modificar("fechas_prestamos_empleados",$datos,$condicion_fecha);

                            }
                            $eliminar  = SQL::eliminar("movimiento_control_prestamos_empleados",$condicion_movimientos);
                        }
                        //////////// Elimino las forma de pago y todos los documentos que se generaron /////////
                        $eliminar  = SQL::eliminar("movimientos_salario_retroactivo",$condicion_movimientos);

                        $condicion_forma_pago = "ano_generacion='".$forma_ano_generacion."' AND mes_generacion='".$forma_mes_generacion."' AND codigo_planilla='".$forma_codigo_planilla."' AND periodo_pago='".$forma_periodo."' AND codigo_sucursal='".$codigo_sucursal."' AND fecha_pago_planilla='".$forma_fecha_pago."'";
                        $consulta_forma_pago  = SQL::seleccionar(array("forma_pago_planillas_nomina"),array("*"),$condicion_forma_pago);

                        if(SQL::filasDevueltas($consulta_forma_pago)){

                            $datos_forma_pago     = SQL::filaEnObjeto($consulta_forma_pago);
                            $forma_pago_utilizada = $datos_forma_pago->forma_pago;

                            if($forma_pago_utilizada == '1'){
                                $nombre_tabla= "forma_pago_planillas_nomina";
                            }else{
                                $nombre_tabla= "forma_pago_planillas_nomina";
                            }

                            $consulta_forma_pago_nomina = SQL::seleccionar(array($nombre_tabla),array("*"),$condicion_movimientos);
                            $eliminar_forma_pago_nomina = SQL::eliminar($nombre_tabla,$condicion_movimientos);
                            if($eliminar_forma_pago_nomina){
                                while($datos_forma_pago_nomina = SQL::filaEnObjeto($consulta_forma_pago_nomina)){
                                    $condicion_consecutivo_documento  = "codigo_sucursal='".$datos_forma_pago_nomina->codigo_sucursal_consecutivo_documento."' AND codigo_tipo_documento='".$datos_forma_pago_nomina->codigo_tipo_documento_consecutivo_documento."'";
                                    $condicion_consecutivo_documento .= " AND documento_identidad_tercero='".$datos_forma_pago_nomina->documento_identidad_tercero_consecutivo_documento."' AND fecha_registro='".$datos_forma_pago_nomina->fecha_registro_consecutivo_documento."' AND consecutivo='".$datos_forma_pago_nomina->consecutivo_documento."'";
                                    $eliminar = SQL::eliminar("consecutivo_documentos",$condicion_consecutivo_documento);
                                }
                            }
                            $eliminar = SQL::eliminar("forma_pago_planillas_nomina",$condicion_forma_pago);
                        }

                        $fecha_pago     = explode("-",$forma_fecha_pago);
                        $ano_generacion = $fecha_pago[0];
                        $mes_generacion = $fecha_pago[1];

                        $condicion = "codigo_sucursal ='$codigo_sucursal' AND codigo_planilla='$forma_codigo_planilla'";
                        $condicion .=" AND (fecha_ingreso_sucursal<='$forma_fecha_pago'";
                        $condicion .=" AND ((fecha_retiro_sucursal !='0000-00-00' AND fecha_retiro_sucursal>='$forma_fecha_pago')";
                        $condicion .= " OR fecha_retiro_sucursal='0000-00-00')";
                        $condicion .=" ) AND fecha_salario <='$forma_fecha_pago' AND estado='1' ORDER BY fecha_ingreso_sucursal DESC, fecha_salario DESC";
                        $consulta_sucursal_contrato = SQL::seleccionar(array("consulta_contrato_empleado"),array("*"),$condicion);

                        if (SQL::filasDevueltas($consulta_sucursal_contrato)){

                            while($datos_ingreso = SQL::filaEnObjeto($consulta_sucursal_contrato)){

                                if (!isset($empleado[$datos_ingreso->documento_identidad_empleado][$datos_ingreso->codigo_sucursal][$datos_ingreso->codigo_planilla])){
                                    $empleado[$datos_ingreso->documento_identidad_empleado][$datos_ingreso->codigo_sucursal][$datos_ingreso->codigo_planilla] = 1;
                                } else {
                                    continue;
                                }

                                $fecha_ingreso_sucursal = $datos_ingreso->fecha_ingreso_sucursal;
                                $codigo_planilla        = $datos_ingreso->codigo_planilla;

                                if ($codigo_planilla == $forma_codigo_planilla){

                                    $salario_mensual        = $datos_ingreso->salario;
                                    $valor_dia              = $salario_mensual / 30;
                                    $forma_pago_auxilio     = $datos_ingreso->forma_pago_auxilio;
                                    /////////////////Datos de icapacidades/////////////////
                                    $condicion_incapacidad  = "codigo_planilla='$forma_codigo_planilla' AND fecha_pago_planilla='$forma_fecha_pago'";
                                    $condicion_incapacidad .= " AND ano_generacion='$forma_ano_generacion' AND mes_generacion='$forma_mes_generacion'";
                                    $condicion_incapacidad .= " AND periodo_pago='$forma_periodo' AND documento_identidad_empleado='$datos_ingreso->documento_identidad_empleado'";
                                    $consulta_incapacidades = SQL::seleccionar(array("reporte_incapacidades"),array("*"),$condicion_incapacidad);
                                    $dias_incapacitado         = 0;
                                    $dias_incapacitado_auxilio = 0;
                                    if (SQL::filasDevueltas($consulta_incapacidades)){

                                        while($datos_incapacidad = SQL::filaEnObjeto($consulta_incapacidades)){

                                            $codigo_concepto_transaccion_tiempo = SQL::obtenerValor("transacciones_tiempo","codigo_concepto_transaccion_tiempo","codigo='$datos_incapacidad->codigo_transaccion_tiempo'");
                                            $tipo_concepto_tiempo               = SQL::obtenerValor("conceptos_transacciones_tiempo","tipo","codigo='$codigo_concepto_transaccion_tiempo'");
                                            $resta_salario                      = SQL::obtenerValor("transacciones_tiempo","resta_salario","codigo='$datos_incapacidad->codigo_transaccion_tiempo'");
                                            $resta_auxilio_transporte           = SQL::obtenerValor("transacciones_tiempo","resta_auxilio_transporte","codigo='$datos_incapacidad->codigo_transaccion_tiempo'");

                                            if (($resta_salario == '1' || $resta_auxilio_transporte == '1') && ($tipo_concepto_tiempo=='3' || $tipo_concepto_tiempo=='4')){

                                                if ($resta_salario == '1'){
                                                    $dias_incapacitado++;
                                                }
                                                if ($resta_auxilio_transporte == '1'){
                                                    $dias_incapacitado_auxilio++;
                                                }

                                                $datos = array(
                                                    "ano_generacion"      => $forma_ano_generacion,
                                                    "mes_generacion"      => $forma_mes_generacion,
                                                    "codigo_planilla"     => $forma_codigo_planilla,
                                                    "fecha_pago_planilla" => $forma_fecha_pago,
                                                    "periodo_pago"        => $forma_periodo,
                                                    "contabilizado"       => "2"
                                                );

                                                $condicion = "documento_identidad_empleado ='$datos_incapacidad->documento_identidad_empleado'";
                                                $condicion .= " AND fecha_incapacidad ='$datos_incapacidad->fecha_incapacidad'";
                                                $modificar_incapacidad = SQL::modificar("reporte_incapacidades",$datos,$condicion);
                                            }
                                        }
                                    }

                                    $condicion_incapacidad  = "fecha_pago_planilla<='$forma_fecha_pago'";
                                    $condicion_incapacidad .= " AND documento_identidad_empleado='$datos_ingreso->documento_identidad_empleado' AND contabilizado='0'";
                                    $consulta_incapacidades = SQL::seleccionar(array("reporte_incapacidades"),array("*"),$condicion_incapacidad);

                                    $dias_descontar  = 0;
                                    $dias_descontar_auxilio = 0;

                                    if (SQL::filasDevueltas($consulta_incapacidades)){

                                        while($datos_incapacidad = SQL::filaEnObjeto($consulta_incapacidades)){

                                            $codigo_concepto_transaccion_tiempo = SQL::obtenerValor("transacciones_tiempo","codigo_concepto_transaccion_tiempo","codigo='$datos_incapacidad->codigo_transaccion_tiempo'");
                                            $tipo_concepto_tiempo               = SQL::obtenerValor("conceptos_transacciones_tiempo","tipo","codigo='$codigo_concepto_transaccion_tiempo'");
                                            $resta_salario                      = SQL::obtenerValor("transacciones_tiempo","resta_salario","codigo='$datos_incapacidad->codigo_transaccion_tiempo'");
                                            $resta_auxilio_transporte           = SQL::obtenerValor("transacciones_tiempo","resta_auxilio_transporte","codigo='$datos_incapacidad->codigo_transaccion_tiempo'");

                                            if (($resta_salario == '1' || $resta_auxilio_transporte=='1') && ($tipo_concepto_tiempo=='3' || $tipo_concepto_tiempo=='4')){

                                                if ($resta_salario == '1'){
                                                    $dias_descontar++;
                                                }
                                                if ($resta_auxilio_transporte == '1'){
                                                    $dias_descontar_auxilio++;
                                                }

                                                $datos = array(
                                                    "ano_generacion"      => $forma_ano_generacion,
                                                    "mes_generacion"      => $forma_mes_generacion,
                                                    "codigo_planilla"     => $forma_codigo_planilla,
                                                    "fecha_pago_planilla" => $forma_fecha_pago,
                                                    "periodo_pago"        => $forma_periodo,
                                                    "contabilizado"       => "2"
                                                );

                                                $condicion = "documento_identidad_empleado ='$datos_incapacidad->documento_identidad_empleado'";
                                                $condicion .= " AND fecha_incapacidad ='$datos_incapacidad->fecha_incapacidad'";
                                                $modificar_incapacidad = SQL::modificar("reporte_incapacidades",$datos,$condicion);
                                            }
                                        }
                                    }

                                    ////////////////////////Datos de tiempos no laborados dias//////////////////
                                    $condicion_no_laborados  = "codigo_planilla='$forma_codigo_planilla' AND fecha_pago_planilla='$forma_fecha_pago'";
                                    $condicion_no_laborados .= " AND ano_generacion='$forma_ano_generacion' AND mes_generacion='$forma_mes_generacion'";
                                    $condicion_no_laborados .= " AND periodo_pago='$forma_periodo' AND documento_identidad_empleado='$datos_ingreso->documento_identidad_empleado'";

                                    $consulta_no_laborados = SQL::seleccionar(array("movimiento_tiempos_no_laborados_dias"),array("*"),$condicion_no_laborados);

                                    if (SQL::filasDevueltas($consulta_no_laborados)){

                                        while($datos_no_laborados = SQL::filaEnObjeto($consulta_no_laborados)){

                                            $codigo_concepto_transaccion_tiempo = SQL::obtenerValor("transacciones_tiempo","codigo_concepto_transaccion_tiempo","codigo='$datos_no_laborados->codigo_transaccion_tiempo'");
                                            $tipo_concepto_tiempo               = SQL::obtenerValor("conceptos_transacciones_tiempo","tipo","codigo='$codigo_concepto_transaccion_tiempo'");
                                            $resta_salario                      = SQL::obtenerValor("transacciones_tiempo","resta_salario","codigo='$datos_no_laborados->codigo_transaccion_tiempo'");
                                            $resta_auxilio_transporte            = SQL::obtenerValor("transacciones_tiempo","resta_auxilio_transporte","codigo='$datos_no_laborados->codigo_transaccion_tiempo'");

                                            if (($resta_salario == '1' || $resta_auxilio_transporte == '1') && $tipo_concepto_tiempo=='2'){

                                                if ($resta_salario == '1'){
                                                    $dias_incapacitado++;
                                                }
                                                if ($resta_auxilio_transporte == '1'){
                                                    $dias_incapacitado_auxilio++;
                                                }
                                                $datos = array(
                                                    "ano_generacion"      => $forma_ano_generacion,
                                                    "mes_generacion"      => $forma_mes_generacion,
                                                    "codigo_planilla"     => $forma_codigo_planilla,
                                                    "fecha_pago_planilla" => $forma_fecha_pago,
                                                    "periodo_pago"        => $forma_periodo,
                                                    "contabilizado"       => "2"
                                                );

                                                $condicion = "documento_identidad_empleado ='$datos_no_laborados->documento_identidad_empleado'";
                                                $condicion .= " AND fecha_tiempo ='$datos_no_laborados->fecha_tiempo'";
                                                $modificar_no_laborados = SQL::modificar("movimiento_tiempos_no_laborados_dias",$datos,$condicion);
                                            }
                                        }
                                    }

                                    $condicion_no_laborados  = "fecha_pago_planilla<='$forma_fecha_pago'";
                                    $condicion_no_laborados .= " AND documento_identidad_empleado='$datos_ingreso->documento_identidad_empleado' AND contabilizado='0'";
                                    $consulta_no_laborados   = SQL::seleccionar(array("movimiento_tiempos_no_laborados_dias"),array("*"),$condicion_no_laborados);

                                    $valor_tiempos_no_laborados = 0;

                                    if (SQL::filasDevueltas($consulta_no_laborados)){

                                        while($datos_no_laborados = SQL::filaEnObjeto($consulta_no_laborados)){

                                            $codigo_concepto_transaccion_tiempo = SQL::obtenerValor("transacciones_tiempo","codigo_concepto_transaccion_tiempo","codigo='$datos_no_laborados->codigo_transaccion_tiempo'");
                                            $tipo_concepto_tiempo               = SQL::obtenerValor("conceptos_transacciones_tiempo","tipo","codigo='$codigo_concepto_transaccion_tiempo'");
                                            $resta_salario                      = SQL::obtenerValor("transacciones_tiempo","resta_salario","codigo='$datos_no_laborados->codigo_transaccion_tiempo'");
                                            $resta_auxilio_transporte           = SQL::obtenerValor("transacciones_tiempo","resta_auxilio_transporte","codigo='$datos_no_laborados->codigo_transaccion_tiempo'");

                                            if (($resta_salario == '1' && $resta_auxilio_transporte == '1') && $tipo_concepto_tiempo=='2'){

                                                if ($resta_salario=='1'){
                                                    $dias_descontar++;
                                                }

                                                if ($resta_auxilio_transporte=='1'){
                                                    $dias_descontar_auxilio++;
                                                }

                                                $datos = array(
                                                    "ano_generacion"      => $forma_ano_generacion,
                                                    "mes_generacion"      => $forma_mes_generacion,
                                                    "codigo_planilla"     => $forma_codigo_planilla,
                                                    "fecha_pago_planilla" => $forma_fecha_pago,
                                                    "periodo_pago"        => $forma_periodo,
                                                    "contabilizado"       => "2"
                                                );

                                                $condicion = "documento_identidad_empleado ='$datos_incapacidad->documento_identidad_empleado'";
                                                $condicion .= " AND fecha_tiempo ='$datos_incapacidad->fecha_tiempo'";
                                                $modificar_no_laborados = SQL::modificar("movimiento_tiempos_no_laborados_dias",$datos,$condicion);
                                            }
                                        }
                                    }
                                    //////////////////////////////////////////////////////////////////////
                                    ////////////////////////Datos de tiempos no laborados horas///////////

                                    $condicion_no_laborados  = "codigo_planilla='$forma_codigo_planilla' AND fecha_pago_planilla='$forma_fecha_pago'";
                                    $condicion_no_laborados .= " AND ano_generacion='$forma_ano_generacion' AND mes_generacion='$forma_mes_generacion'";
                                    $condicion_no_laborados .= " AND periodo_pago='$forma_periodo' AND documento_identidad_empleado='$datos_ingreso->documento_identidad_empleado'";
                                    $consulta_no_laborados = SQL::seleccionar(array("movimiento_tiempos_no_laborados_horas"),array("*"),$condicion_no_laborados);

                                    if (SQL::filasDevueltas($consulta_no_laborados)){

                                        while($datos_no_laborados = SQL::filaEnObjeto($consulta_no_laborados)){

                                            $codigo_concepto_transaccion_tiempo = SQL::obtenerValor("transacciones_tiempo","codigo_concepto_transaccion_tiempo","codigo='$datos_no_laborados->codigo_transaccion_tiempo'");
                                            $tipo_concepto_tiempo               = SQL::obtenerValor("conceptos_transacciones_tiempo","tipo","codigo='$codigo_concepto_transaccion_tiempo'");
                                            $resta_salario                      = SQL::obtenerValor("transacciones_tiempo","resta_salario","codigo='$datos_no_laborados->codigo_transaccion_tiempo'");

                                            if ($resta_salario == '1' && ($tipo_concepto_tiempo=='2')){

                                                $datos = array(
                                                    "ano_generacion"      => $forma_ano_generacion,
                                                    "mes_generacion"      => $forma_mes_generacion,
                                                    "codigo_planilla"     => $forma_codigo_planilla,
                                                    "fecha_pago_planilla" => $forma_fecha_pago,
                                                    "periodo_pago"        => $forma_periodo,
                                                    "contabilizado"       => "2"
                                                );
                                                $valor_tiempos_no_laborados += $datos_no_laborados->valor_movimiento;

                                                $condicion = "documento_identidad_empleado ='$datos_no_laborados->documento_identidad_empleado'";
                                                $condicion .= " AND fecha_registro ='$datos_no_laborados->fecha_registro' AND hora_inicio='$datos_no_laborados->hora_inicio' AND hora_fin='$datos_no_laborados->hora_fin' AND codigo_sucursal='$datos_no_laborados->codigo_sucursal'";
                                                $modificar_no_laborados = SQL::modificar("movimiento_tiempos_no_laborados_horas",$datos,$condicion);
                                            }
                                        }
                                    }

                                    $condicion_no_laborados  = " fecha_pago_planilla<='$forma_fecha_pago'";
                                    $condicion_no_laborados .= " AND documento_identidad_empleado='$datos_ingreso->documento_identidad_empleado' AND contabilizado='0'";
                                    $consulta_no_laborados   = SQL::seleccionar(array("movimiento_tiempos_no_laborados_horas"),array("*"),$condicion_no_laborados);

                                    if (SQL::filasDevueltas($consulta_no_laborados)){

                                        while($datos_no_laborados = SQL::filaEnObjeto($consulta_no_laborados)){

                                            $codigo_concepto_transaccion_tiempo = SQL::obtenerValor("transacciones_tiempo","codigo_concepto_transaccion_tiempo","codigo='$datos_no_laborados->codigo_transaccion_tiempo'");
                                            $tipo_concepto_tiempo               = SQL::obtenerValor("conceptos_transacciones_tiempo","tipo","codigo='$codigo_concepto_transaccion_tiempo'");
                                            $resta_salario                      = SQL::obtenerValor("transacciones_tiempo","resta_salario","codigo='$datos_no_laborados->codigo_transaccion_tiempo'");

                                            if ($resta_salario == '1' && ($tipo_concepto_tiempo=='2')){

                                                $datos = array(
                                                    "ano_generacion"      => $forma_ano_generacion,
                                                    "mes_generacion"      => $forma_mes_generacion,
                                                    "codigo_planilla"     => $forma_codigo_planilla,
                                                    "fecha_pago_planilla" => $forma_fecha_pago,
                                                    "periodo_pago"        => $forma_periodo,
                                                    "contabilizado"       => "2"
                                                );

                                                $valor_tiempos_no_laborados += $datos_no_laborados->valor_movimiento;

                                                $condicion = "documento_identidad_empleado ='$datos_no_laborados->documento_identidad_empleado'";
                                                $condicion .= " AND fecha_registro ='$datos_no_laborados->fecha_registro' AND hora_inicio='$datos_no_laborados->hora_inicio' AND hora_fin='$datos_no_laborados->hora_fin' AND codigo_sucursal='$datos_no_laborados->codigo_sucursal'";
                                                $modificar_no_laborados = SQL::modificar("movimiento_tiempos_no_laborados_horas",$datos,$condicion);
                                            }
                                        }
                                    }
                                    //////////////////////////////////////////////////////////////////////

                                    $total_dias_descontar = (int)$dias_descontar + (int)$dias_incapacitado;
                                    $total_dias_auxilio   = (int)$dias_descontar_auxilio + (int)$dias_incapacitado_auxilio;

                                    if ($forma_periodo =='1'){
                                        $dias_trabajados = 30 - (int)$total_dias_descontar;
                                        $dias_auxilio    = 30 - (int)$total_dias_auxilio;
                                        $dias_periodo    = 30;

                                    } else if ($forma_periodo =='2' || $forma_periodo =='3'){
                                        $dias_trabajados = 15 - (int)$total_dias_descontar;
                                        $dias_auxilio    = 15 - (int)$total_dias_auxilio;
                                        $dias_periodo    = 15;

                                    } else {
                                        $dias_trabajados = 7 - (int)$total_dias_descontar;
                                        $dias_auxilio    = 7 - (int)$total_dias_auxilio;
                                        $dias_periodo    = 7;
                                    }

                                    $fecha_inicio_mes = $forma_ano_generacion."-".$forma_mes_generacion."-01";
                                    if ($tipo_planilla == '1'){
                                        $fecha_inicio_pago = $forma_ano_generacion."-".$forma_mes_generacion."-01";
                                        $fecha_fin_pago    = $forma_ano_generacion."-".$forma_mes_generacion."-".$dia_fin;
                                    } else if ($tipo_planilla == '2'){
                                        if ($forma_periodo == '2'){
                                            $fecha_inicio_pago = $forma_ano_generacion."-".$forma_mes_generacion."-01";
                                            $fecha_fin_pago    = $forma_ano_generacion."-".$forma_mes_generacion."-15";
                                        } else {
                                            $fecha_inicio_pago = $forma_ano_generacion."-".$forma_mes_generacion."-16";
                                            $fecha_fin_pago    = $forma_ano_generacion."-".$forma_mes_generacion."-".$dia_fin;
                                        }
                                    }

                                    $ingreso_empleado = false;
                                    $dias_trabajados_primera_quincena = 0;
                                    if ($datos_ingreso->fecha_ingreso > $fecha_inicio_pago){

                                        $diferencia       = strtotime($datos_ingreso->fecha_ingreso) - strtotime($fecha_inicio_pago); //Hallo la diferencia de las fechas en segundos
                                        $dias_diferencia  = $diferencia / (60 * 60 * 24); //Convierto la diferencia en dias
                                        $dias_trabajados  = (int)$dias_trabajados - (int)$dias_diferencia;
                                        $ingreso_empleado = true;
                                    } else {
                                        if ($tipo_planilla=='2' && $forma_periodo =='3' && $datos_ingreso->fecha_ingreso > $fecha_inicio_mes && $datos_ingreso->forma_pago_auxilio=='2'){
                                            $ingreso_empleado = true;
                                        }
                                    }

                                    if ($dias_trabajados > 0){

                                        $condicion_tiempos  = "codigo_planilla='$forma_codigo_planilla' AND fecha_pago_planilla='$forma_fecha_pago'";
                                        $condicion_tiempos .= " AND ano_generacion='$forma_ano_generacion' AND mes_generacion='$forma_mes_generacion'";
                                        $condicion_tiempos .= " AND periodo_pago='$forma_periodo' AND documento_identidad_empleado='$datos_ingreso->documento_identidad_empleado'";
                                        $consulta_tiempos   = SQL::seleccionar(array("movimiento_tiempos_laborados"),array("*"),$condicion_tiempos);

                                        $valor_tiempos = 0;

                                        if (SQL::filasDevueltas($consulta_tiempos)){

                                            while($datos_tiempos = SQL::filaEnObjeto($consulta_tiempos)){

                                                $codigo_concepto_transaccion_tiempo = SQL::obtenerValor("transacciones_tiempo","codigo_concepto_transaccion_tiempo","codigo='$datos_tiempos->codigo_transaccion_tiempo'");
                                                $resta_salario                      = SQL::obtenerValor("transacciones_tiempo","resta_salario","codigo='$datos_tiempos->codigo_transaccion_tiempo'");
                                                $tipo_concepto_tiempo               = SQL::obtenerValor("conceptos_transacciones_tiempo","tipo","codigo='$codigo_concepto_transaccion_tiempo'");

                                                if(($tipo_concepto_tiempo==1) ||
                                                    ($forma_ano_generacion=="2011" && $forma_mes_generacion=="04" && $forma_periodo=="2" && $tipo_concepto_tiempo==1)){

                                                    //$valor_tiempos = $valor_tiempos + ((int)$datos_tiempos->cantidad * $datos_tiempos->valor_hora_recargo);
                                                    if ($resta_salario=='1' ||
                                                        ($forma_ano_generacion=="2011" && $forma_mes_generacion=="04" && $forma_periodo=="2" && $tipo_concepto_tiempo==1)){
                                                        $valor_tiempos = $valor_tiempos + (int)$datos_tiempos->valor_movimiento;
                                                    }

                                                    $datos = array(
                                                        "contabilizado" => '2'
                                                    );

                                                    $condicion = "codigo_empresa='$datos_tiempos->codigo_empresa' AND documento_identidad_empleado='$datos_tiempos->documento_identidad_empleado'";
                                                    $condicion .=" AND fecha_ingreso='$datos_tiempos->fecha_ingreso' AND codigo_sucursal='$datos_tiempos->codigo_sucursal'";
                                                    $condicion .=" AND fecha_ingreso_sucursal='$datos_tiempos->fecha_ingreso_sucursal' AND consecutivo='$datos_tiempos->consecutivo'";

                                                    $modificar_tiempos = SQL::modificar("movimiento_tiempos_laborados",$datos,$condicion);
                                                }
                                            }
                                        }

                                        $condicion_tiempos  = "fecha_pago_planilla<'$forma_fecha_pago'";
                                        $condicion_tiempos .= " AND documento_identidad_empleado='$datos_ingreso->documento_identidad_empleado' AND contabilizado = '0'";
                                        $consulta_tiempos   = SQL::seleccionar(array("movimiento_tiempos_laborados"),array("*"),$condicion_tiempos);
                                        if (SQL::filasDevueltas($consulta_tiempos)){

                                            while($datos_tiempos = SQL::filaEnObjeto($consulta_tiempos)){
                                                if (!isset($valor_tiempos)){
                                                    $valor_tiempos = 0;
                                                }

                                                $codigo_concepto_transaccion_tiempo = SQL::obtenerValor("transacciones_tiempo","codigo_concepto_transaccion_tiempo","codigo='$datos_tiempos->codigo_transaccion_tiempo'");
                                                $resta_salario                      = SQL::obtenerValor("transacciones_tiempo","resta_salario","codigo='$datos_tiempos->codigo_transaccion_tiempo'");
                                                $tipo_concepto_tiempo               = SQL::obtenerValor("conceptos_transacciones_tiempo","tipo","codigo='$codigo_concepto_transaccion_tiempo'");

                                                if(($tipo_concepto_tiempo==1) ||
                                                    ($forma_ano_generacion=="2011" && $forma_mes_generacion=="04" && $forma_periodo=="2" && $tipo_concepto_tiempo==1)){

                                                    //$valor_tiempos = $valor_tiempos + ((int)$datos_tiempos->cantidad * $datos_tiempos->valor_hora_recargo);
                                                    if ($resta_salario=='1' ||
                                                        ($forma_ano_generacion=="2011" && $forma_mes_generacion=="04" && $forma_periodo=="2" && $tipo_concepto_tiempo==1)){
                                                        $valor_tiempos = $valor_tiempos + ((int)$datos_tiempos->valor_movimiento);
                                                    }

                                                    $datos = array(
                                                        "ano_generacion"      => $forma_ano_generacion,
                                                        "mes_generacion"      => $forma_mes_generacion,
                                                        "codigo_planilla"     => $forma_codigo_planilla,
                                                        "fecha_pago_planilla" => $forma_fecha_pago,
                                                        "periodo_pago"        => $forma_periodo,
                                                        "contabilizado"       => "2"
                                                    );

                                                    $condicion = "codigo_empresa='$datos_tiempos->codigo_empresa' AND documento_identidad_empleado='$datos_tiempos->documento_identidad_empleado'";
                                                    $condicion .=" AND fecha_ingreso='$datos_tiempos->fecha_ingreso' AND codigo_sucursal='$datos_tiempos->codigo_sucursal'";
                                                    $condicion .=" AND fecha_ingreso_sucursal='$datos_tiempos->fecha_ingreso_sucursal' AND consecutivo='$datos_tiempos->consecutivo'";

                                                    $modificar_tiempos = SQL::modificar("movimiento_tiempos_laborados",$datos,$condicion);
                                                }
                                            }
                                        }

                                        //////////////movimientos_novedades_manuales////////////
                                        $condicion_novedades  = "codigo_planilla='$forma_codigo_planilla' AND fecha_pago_planilla='$forma_fecha_pago'";
                                        $condicion_novedades .= " AND ano_generacion='$forma_ano_generacion' AND mes_generacion='$forma_mes_generacion'";
                                        $condicion_novedades .= " AND periodo_pago='$forma_periodo' AND documento_identidad_empleado='$datos_ingreso->documento_identidad_empleado'";
                                        $consulta_novedades   = SQL::seleccionar(array("movimiento_novedades_manuales"),array("*"),$condicion_novedades);
                                        $valor_novedad        = 0;

                                        if (SQL::filasDevueltas($consulta_novedades)){
                                            while($datos_tiempos = SQL::filaEnObjeto($consulta_novedades)){

                                                $codigo_concepto_novedad_manual = SQL::obtenerValor("transacciones_contables_empleado","codigo_concepto_transaccion_contable","codigo='$datos_tiempos->codigo_transaccion_contable'");
                                                $sentido_transaccion            = SQL::obtenerValor("transacciones_contables_empleado","sentido","codigo='$datos_tiempos->codigo_transaccion_contable'");
                                                $datos               = array("contabilizado"       => "2");
                                                $modificar_novedades = SQL::modificar("movimiento_novedades_manuales",$datos,$condicion_novedades);
                                            }
                                        }

                                        ////////////////////////////////////////////////////////

                                        $valor_movimiento = (int)($valor_dia * $dias_trabajados);
                                        if (($valor_tiempos + $valor_novedad) < $valor_movimiento){

                                            $valor_movimiento = $valor_movimiento - $valor_tiempos - $valor_novedad;

                                            $codigo_transaccion_contable = $datos_ingreso->codigo_transaccion_salario;
                                            $codigo_contable             = SQL::obtenerValor("transacciones_contables_empleado","codigo_contable","codigo='$codigo_transaccion_contable'");
                                            $sentido                     = SQL::obtenerValor("transacciones_contables_empleado","sentido","codigo='$codigo_transaccion_contable'");

                                            $condicion    = "ano_generacion=' $ano_generacion' AND mes_generacion='$mes_generacion' AND codigo_planilla='$forma_codigo_planilla' AND periodo_pago='$forma_periodo' AND codigo_transaccion_contable='$codigo_transaccion_contable' AND codigo_sucursal='$datos_ingreso->codigo_sucursal'";
                                            $consecutivo  = SQL::obtenerValor("movimientos_salarios","MAX(consecutivo)",$condicion);
                                            if (!$consecutivo){
                                                $consecutivo = 1;
                                            } else {
                                                $consecutivo ++;
                                            }

                                            if ($tipo_planilla == '1'){
                                                $fecha_inicio_pago = $forma_ano_generacion."-".$forma_mes_generacion."-01";
                                                $fecha_fin_pago    = $forma_ano_generacion."-".$forma_mes_generacion."-".$dia_fin;
                                            } else if ($tipo_planilla == '2'){
                                                if ($forma_periodo == '2'){
                                                    $fecha_inicio_pago = $forma_ano_generacion."-".$forma_mes_generacion."-01";
                                                    $fecha_fin_pago    = $forma_ano_generacion."-".$forma_mes_generacion."-15";
                                                } else {
                                                    $fecha_inicio_pago = $forma_ano_generacion."-".$forma_mes_generacion."-16";
                                                    $fecha_fin_pago    = $forma_ano_generacion."-".$forma_mes_generacion."-".$dia_fin;
                                                }
                                            }

                                            if ($dias_trabajados == $dias_periodo && $valor_tiempos==0 && $valor_novedad==0){
                                                if ($tipo_planilla == '1'){
                                                    $salario_pagar = $salario_mensual;

                                                } else if ($tipo_planilla == '2'){
                                                    $salario_pagar = (int)($salario_mensual / 2);
                                                    if (($salario_pagar *2) < $salario_mensual  && $valor_tiempos==0 && $valor_novedad==0 && $forma_periodo == '3'){
                                                        $salario_pagar = $salario_pagar + ($salario_mensual - ($salario_pagar * 2));
                                                    }

                                                } else {
                                                    $salario_pagar = $valor_dia * 7;
                                                    if (($salario_pagar * 7) < $salario_mensual){
                                                        if ($forma_periodo == '7'){
                                                            $salario_pagar = $salario_pagar + ($salario_mensual + $salario_pagar);
                                                        }
                                                    }
                                                }
                                                $valor_movimiento = $salario_pagar;
                                            }

                                            $datos = array(
                                                "ano_generacion"                => $ano_generacion,
                                                "mes_generacion"                => $mes_generacion,
                                                "codigo_planilla"               => $forma_codigo_planilla,
                                                "periodo_pago"                  => $forma_periodo,
                                                "codigo_transaccion_contable"   => $codigo_transaccion_contable,
                                                "consecutivo"                   => $consecutivo,
                                                ///////////////////////////////
                                                "codigo_empresa"                => $datos_ingreso->codigo_empresa,
                                                "documento_identidad_empleado"  => $datos_ingreso->documento_identidad_empleado,
                                                "fecha_ingreso_empresa"         => $datos_ingreso->fecha_ingreso,
                                                "codigo_sucursal"               => $datos_ingreso->codigo_sucursal,
                                                "fecha_ingreso_sucursal"        => $fecha_ingreso_sucursal,
                                                ///////////////////////////////
                                                "fecha_pago_planilla"           => $forma_fecha_pago,
                                                "fecha_ingreso_planilla"        => date("Y-m-d"),
                                                "fecha_inicio_pago"             => $fecha_inicio_pago,
                                                "fecha_hasta_pago"              => $fecha_fin_pago,
                                                ///////////////////////////////
                                                "codigo_empresa_auxiliar"       => $datos_ingreso->codigo_empresa_auxiliar,
                                                "codigo_anexo_contable"         => $datos_ingreso->codigo_anexo_contable,
                                                ///tabla_auxiliares_contables
                                                "codigo_auxiliar_contable"      => $datos_ingreso->codigo_auxiliar,
                                                ///////////////////////////////
                                                "codigo_contable"               => $codigo_contable,
                                                "sentido"                       => $sentido,
                                                "codigo_transaccion_tiempo"     => 0,
                                                "dias_trabajados"               => $dias_trabajados,
                                                "salario_mensual"               => $salario_mensual,
                                                "valor_movimiento"              => round($valor_movimiento)
                                            );
                                            $insertar = SQL::insertar("movimientos_salarios",$datos);
                                            if($insertar){
                                                $grabo_registro = true;
                                            }
                                        }
                                    }
                                    //Liquidacion auxilio de transporte
                                    if (
                                        $datos_ingreso->manejo_auxilio_transporte !='5' &&
                                        (
                                         (
                                          $tipo_planilla == '2' &&
                                          (
                                           ($forma_pago_auxilio == '2' && $forma_periodo =='3') ||
                                           ($forma_pago_auxilio == '1' && $dias_auxilio>0)
                                          )
                                         ) ||
                                         (
                                          $tipo_planilla != '2' &&
                                          $dias_auxilio > 0
                                         )
                                        )
                                       ){

                                        $fecha_consulta = explode("-",$forma_fecha_pago);
                                        $fecha_consulta = $fecha_consulta[0]."-00-00";
                                        //$consulta_salario_minimo = SQL::seleccionar(array("salario_minimo"),array("*"),"fecha >= '$fecha_consulta'","","fecha ASC");
                                        $salario_minimo = SQL::obtenerValor("salario_minimo","valor","fecha <= '$forma_fecha_pago' ORDER BY fecha DESC LIMIT 0,1");

                                        $base_auxilio = $salario_minimo * 2;
                                        if (
                                            (($datos_ingreso->manejo_auxilio_transporte == '1' || $datos_ingreso->manejo_auxilio_transporte == '2') && $salario_mensual < $base_auxilio) ||
                                            $datos_ingreso->manejo_auxilio_transporte == '3' ||
                                            $datos_ingreso->manejo_auxilio_transporte == '4'
                                           ){


                                            $dias_incapacidad_primera_quincena = 0;
                                            $dias_trabajados_primera_quincena  = 0;
                                            $dias_tiempos_primera_quincena     = 0;
                                            if ($tipo_planilla == '2' && $forma_pago_auxilio == '2' && $forma_periodo == '3'){

                                                if ($ingreso_empleado){
                                                    $diferencia       = strtotime($datos_ingreso->fecha_ingreso) - strtotime($fecha_inicio_mes); //Hallo la diferencia de las fechas en segundos
                                                    $dias_diferencia  = $diferencia / (60 * 60 * 24); //Convierto la diferencia en dias
                                                    $dias_trabajados_primera_quincena  = (int)$dias_auxilio - (int)$dias_diferencia;
                                                } else {
                                                    $dias_trabajados_primera_quincena = 15;
                                                }

                                                $condicion_incapacidad = "ano_generacion = '$forma_ano_generacion' AND mes_generacion = '$forma_mes_generacion'";
                                                $condicion_incapacidad .= " AND periodo_pago = '2' AND codigo_planilla = '$forma_codigo_planilla'";
                                                $condicion_incapacidad .= " AND fecha_pago_planilla != '$forma_fecha_pago' AND documento_identidad_empleado = '$datos_ingreso->documento_identidad_empleado'";
                                                $consulta_incapacidad  = SQL::seleccionar(array("reporte_incapacidades"),array("*"), $condicion_incapacidad);

                                                if (SQL::filasDevueltas($consulta_incapacidad)){
                                                    while ($datos_incapacidad_anterior = SQL::filaEnObjeto($consulta_incapacidad)){
                                                        $dias_incapacidad_primera_quincena++;
                                                    }
                                                    $dias_trabajados_primera_quincena = $dias_trabajados_primera_quincena - $dias_incapacidad_primera_quincena;
                                                }

                                                $consulta_tiempos_dias  = SQL::seleccionar(array("movimiento_tiempos_no_laborados_dias"),array("*"), $condicion_incapacidad);
                                                if (SQL::filasDevueltas($consulta_tiempos_dias)){
                                                    while ($datos_tiempos_anterior = SQL::filaEnObjeto($consulta_tiempos_dias)){
                                                        $dias_tiempos_primera_quincena++;
                                                    }
                                                    $dias_trabajados_primera_quincena = $dias_trabajados_primera_quincena - $dias_tiempos_primera_quincena;
                                                }
                                            }

                                            $dias_auxilio = $dias_auxilio + $dias_trabajados_primera_quincena;
                                            if ($dias_auxilio > 0){
                                                if (
                                                    $datos_ingreso->manejo_auxilio_transporte == '2' ||
                                                    $datos_ingreso->manejo_auxilio_transporte == '4'
                                                   ){
                                                    if ($tipo_planilla == '1' && !$ingreso_empleado){
                                                        $dias_auxilio = 30;
                                                    } else if($tipo_planilla == '2' && !$ingreso_empleado){
                                                        $dias_auxilio = 15;
                                                    } else if($tipo_planilla == '3' && !$ingreso_empleado){
                                                        $dias_auxilio = 7;
                                                    }
                                                }

                                                $valor_dia_auxilio = $auxilio_transporte / 30;
                                                $valor_movimiento  = (int)($valor_dia_auxilio * $dias_auxilio);

                                                $codigo_transaccion_contable = $datos_ingreso->codigo_transaccion_auxilio_transporte;
                                                $codigo_contable             = SQL::obtenerValor("transacciones_contables_empleado","codigo_contable","codigo='$codigo_transaccion_contable'");
                                                $sentido                     = SQL::obtenerValor("transacciones_contables_empleado","sentido","codigo='$codigo_transaccion_contable'");

                                                $condicion    = "ano_generacion=' $ano_generacion' AND mes_generacion='$mes_generacion' AND codigo_planilla='$forma_codigo_planilla' AND periodo_pago='$forma_periodo' AND codigo_transaccion_contable='$codigo_transaccion_contable' AND codigo_sucursal='$datos_ingreso->codigo_sucursal'";
                                                $consecutivo  = SQL::obtenerValor("movimientos_auxilio_transporte","MAX(consecutivo)",$condicion);
                                                if (!$consecutivo){
                                                    $consecutivo = 1;
                                                } else {
                                                    $consecutivo ++;
                                                }

                                                if ($tipo_planilla == '1'){
                                                    $fecha_inicio_pago = $forma_ano_generacion."-".$forma_mes_generacion."-01";
                                                    $fecha_fin_pago    = $forma_ano_generacion."-".$forma_mes_generacion."-".$dia_fin;
                                                } else if ($tipo_planilla == '2'){
                                                    if ($forma_periodo == '2'){
                                                        $fecha_inicio_pago = $forma_ano_generacion."-".$forma_mes_generacion."-01";
                                                        $fecha_fin_pago    = $forma_ano_generacion."-".$forma_mes_generacion."-15";
                                                    } else {
                                                        $fecha_inicio_pago = $forma_ano_generacion."-".$forma_mes_generacion."-16";
                                                        $fecha_fin_pago    = $forma_ano_generacion."-".$forma_mes_generacion."-".$dia_fin;
                                                    }
                                                }

                                                if ($tipo_planilla == '1'){
                                                    if (
                                                        $datos_ingreso->manejo_auxilio_transporte == '2' ||
                                                        $datos_ingreso->manejo_auxilio_transporte == '4'
                                                       ){
                                                        if ($valor_movimiento != $auxilio_transporte){
                                                            $valor_movimiento = $auxilio_transporte;
                                                        }
                                                    } else {
                                                        if ($dias_auxilio == 30 && $valor_movimiento != $auxilio_transporte){
                                                            $valor_movimiento = $auxilio_transporte;
                                                        }
                                                    }
                                                } else {

                                                    if ($tipo_planilla == '2' && $forma_pago_auxilio=='1' && $forma_periodo=='3' && $dias_auxilio==15){
                                                        $valor_movimiento = (int)($auxilio_transporte / 2);
                                                    } else if($tipo_planilla == '2' && $forma_pago_auxilio=='2' && $forma_periodo=='3' && $dias_auxilio==30){
                                                        $valor_movimiento = (int)($auxilio_transporte);
                                                    }
                                                }

                                                $datos = array(
                                                    "ano_generacion"                => $ano_generacion,
                                                    "mes_generacion"                => $mes_generacion,
                                                    "codigo_planilla"               => $forma_codigo_planilla,
                                                    "periodo_pago"                  => $forma_periodo,
                                                    "codigo_transaccion_contable"   => $codigo_transaccion_contable,
                                                    "consecutivo"                   => $consecutivo,
                                                    ///////////////////////////////
                                                    "codigo_empresa"                => $datos_ingreso->codigo_empresa,
                                                    "documento_identidad_empleado"  => $datos_ingreso->documento_identidad_empleado,
                                                    "fecha_ingreso_empresa"         => $datos_ingreso->fecha_ingreso,
                                                    "codigo_sucursal"               => $datos_ingreso->codigo_sucursal,
                                                    "fecha_ingreso_sucursal"        => $fecha_ingreso_sucursal,
                                                    ///////////////////////////////
                                                    "fecha_pago_planilla"           => $forma_fecha_pago,
                                                    "fecha_ingreso_planilla"        => date("Y-m-d"),
                                                    "fecha_inicio_pago"             => $fecha_inicio_pago,
                                                    "fecha_hasta_pago"              => $fecha_fin_pago,
                                                    ///////////////////////////////
                                                    "codigo_empresa_auxiliar"       => $datos_ingreso->codigo_empresa_auxiliar,
                                                    "codigo_anexo_contable"         => $datos_ingreso->codigo_anexo_contable,
                                                    ///tabla_auxiliares_contables
                                                    "codigo_auxiliar_contable"      => $datos_ingreso->codigo_auxiliar,
                                                    ///////////////////////////////
                                                    "codigo_contable"               => $codigo_contable,
                                                    "sentido"                       => $sentido,
                                                    "codigo_transaccion_tiempo"     => 0,
                                                    "dias_trabajados"               => $dias_trabajados,
                                                    "dias_auxilio"                  => $dias_auxilio,
                                                    "salario_mensual"               => $salario_mensual,
                                                    "valor_movimiento"              => $valor_movimiento
                                                );
                                                $insertar = SQL::insertar("movimientos_auxilio_transporte",$datos);
                                                if($insertar){
                                                    $grabo_registro = true;
                                                }
                                            }
                                        }
                                    }
                                }

                                /////Prestamos empleados
                                $codigo_empresa = SQL::obtenerValor("sucursales","codigo_empresa","codigo='$codigo_sucursal'");
                                $condicion = "codigo_empresa='$codigo_empresa' AND documento_identidad_empleado='$datos_ingreso->documento_identidad_empleado'";
                                $condicion .= " AND fecha_ingreso='$datos_ingreso->fecha_ingreso'";
                                $consulta_prestamos_empleados = SQL::seleccionar(array("vista_control_contrato"),array("*"),$condicion);

                                if (SQL::filasDevueltas($consulta_prestamos_empleados)){

                                    while($datos_prestamos = SQL::filaEnObjeto($consulta_prestamos_empleados)){

                                        $documento_identidad         = $datos_prestamos->documento_identidad_empleado;
                                        $consecutivo_prestamo        = $datos_prestamos->consecutivo;
                                        $fecha_generacion            = $datos_prestamos->fecha_generacion;
                                        $concepto_prestamo           = $datos_prestamos->concepto_prestamo;
                                        $periodo_pago                = $datos_prestamos->forma_pago;
                                        $codigo_sucursal             = $datos_prestamos->codigo_sucursal;
                                        $codigo_empresa              = $datos_prestamos->codigo_empresa;
                                        $fecha_ingreso               = $datos_prestamos->fecha_ingreso;
                                        $fecha_ingreso_sucursal      = $datos_prestamos->fecha_ingreso_sucursal;
                                        $codigo_transaccion_contable = $datos_prestamos->codigo_transaccion_contable_descontar;
                                        $sentido                     = SQL::obtenerValor("transacciones_contables_empleado","sentido","codigo='$codigo_transaccion_contable'");
                                        $codigo_contable             = SQL::obtenerValor("transacciones_contables_empleado","codigo_contable","codigo='$codigo_transaccion_contable'");
                                        $codigo_empresa_auxiliar     = 0;
                                        $codigo_anexo_contable       = "";
                                        $codigo_auxiliar_contable    = 0;

                                        $condicion = "documento_identidad_empleado='$documento_identidad' AND consecutivo='$consecutivo_prestamo' ";
                                        $condicion .= "AND fecha_generacion='$fecha_generacion' AND concepto_prestamo='$concepto_prestamo' ";
                                        $condicion .= " AND pagada='0' AND fecha_pago <='$forma_fecha_pago'";

                                        $consulta_fecha_cercana   = SQL::seleccionar(array("fechas_prestamos_empleados"),array("*"),$condicion,"","",0,1);

                                        if (SQL::filasDevueltas($consulta_fecha_cercana)){

                                            while($datos_fechas = SQL::filaEnObjeto($consulta_fecha_cercana)){

                                                if ($datos_fechas->descuento=='1'){

                                                    $valor_descuento = $datos_fechas->valor_descuento;
                                                    $condicion  = " ano_generacion='$ano_generacion' AND mes_generacion='$mes_generacion' AND codigo_planilla='$forma_codigo_planilla'";
                                                    $condicion .= " AND periodo_pago='$forma_periodo' AND fecha_Pago_planilla='$forma_fecha_pago' ";
                                                    $condicion .= " AND documento_identidad_empleado='$documento_identidad' AND codigo_sucursal='$codigo_sucursal'";
                                                    $consecutivo = SQL::obtenerValor("movimiento_control_prestamos_empleados","MAX(consecutivo)",$documento_identidad,$condicion);

                                                    if (!$consecutivo){
                                                        $consecutivo = 1;
                                                    } else {
                                                        $consecutivo ++;
                                                    }
                                                    $datos = array(
                                                        /////////llave primaria///////////
                                                        "ano_generacion"               => $ano_generacion,
                                                        "mes_generacion"               => $mes_generacion,
                                                        "codigo_planilla"              => $forma_codigo_planilla,
                                                        "periodo_pago"                 => $forma_periodo,
                                                        "fecha_pago_planilla"          => $forma_fecha_pago,
                                                        "documento_identidad_empleado" => $documento_identidad,
                                                        "codigo_sucursal"              => $codigo_sucursal,
                                                        "consecutivo"                  => $consecutivo,
                                                        /////////////////////////////////
                                                        "fecha_generacion"             => $fecha_generacion,
                                                        "codigo_empresa"               => $codigo_empresa,
                                                        "fecha_ingreso"                => $fecha_ingreso,
                                                        "fecha_ingreso_sucursal"       => $fecha_ingreso_sucursal,
                                                        "consecutivo"                  => $consecutivo,
                                                        ///////////////////////////////
                                                        "codigo_transaccion_contable"  => $codigo_transaccion_contable,
                                                        "codigo_empresa_auxiliar"      => $codigo_empresa_auxiliar,
                                                        "codigo_anexo_contable"        => $codigo_anexo_contable,
                                                        "codigo_auxiliar_contable"     => $codigo_auxiliar_contable,
                                                        //////////////////////////////
                                                        "codigo_contable"              => $codigo_contable,
                                                        "sentido"                      => $sentido,
                                                        ///tabla fechas de pago////
                                                        "fecha_generacion_control"     => $fecha_generacion,
                                                        "consecutivo_fecha_pago"       => $consecutivo_prestamo,
                                                        "fecha_pago"                   => $datos_fechas->fecha_pago,
                                                        "concepto_prestamo"            => $concepto_prestamo,
                                                        "valor_descuento"              => $valor_descuento,
                                                        //////Datos de control////////
                                                        "contabilizado"                => "0",
                                                        "codigo_usuario_registra"      => $sesion_codigo_usuario
                                                    );

                                                    $insertar = SQL::insertar("movimiento_control_prestamos_empleados",$datos);
                                                    if($insertar){
                                                        $datos = array(
                                                            "pagada" => "1"
                                                        );

                                                        $condicion = "documento_identidad_empleado='$documento_identidad' AND consecutivo='$datos_fechas->consecutivo'";
                                                        $condicion .= " AND fecha_generacion='$datos_fechas->fecha_generacion' AND fecha_pago='$datos_fechas->fecha_pago'";
                                                        $condicion .= " AND concepto_prestamo='$datos_fechas->concepto_prestamo'";

                                                        $modificar_fecha = SQL::modificar("fechas_prestamos_empleados",$datos,$condicion);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                //////////Determinino que empleado tienen deudas con un tercero para descontar por nomina/////////
                                ///////Obtengo todos los prestamos que no se hayan terminado de pagar///////
                                $condicion_descuento_terceros = "'$forma_fecha_pago' >= fecha_inicio_descuento AND estado='0' AND codigo_empresa='$codigo_empresa' AND documento_identidad_empleado='$datos_ingreso->documento_identidad_empleado'";
                                $consulta_descuento_terceros = SQL::seleccionar(array("control_prestamos_terceros"),array("*"),$condicion_descuento_terceros);

                                if(SQL::filasDevueltas($consulta_descuento_terceros)){

                                    while($datos_descuento_terceros = SQL::filaEnObjeto($consulta_descuento_terceros)){

                                        $limite_descuento            = $datos_descuento_terceros->limite_descuento;
                                        $empresa                     = $datos_descuento_terceros->codigo_empresa;
                                        $documento_empleado          = $datos_descuento_terceros->documento_identidad_empleado;
                                        $documento_identidad_tercero = $datos_descuento_terceros->documento_identidad_tercero;
                                        $obligacion                  = $datos_descuento_terceros->obligacion;

                                        $codigo_planilla      = $datos_ingreso->codigo_planilla;
                                        $periodo_descuento    = $datos_descuento_terceros->periodo_pago;
                                        $descontar            = false;

                                        if (
                                            $forma_periodo=='1' ||
                                            ($forma_periodo=='2' && ($periodo_descuento=='2' || $periodo_descuento=='9')) ||
                                            ($forma_periodo=='3' && ($periodo_descuento=='3' || $periodo_descuento=='9'))
                                            ){
                                            $descontar = true;
                                            if($limite_descuento=='0'){
                                                $descontar = true;
                                            } else if($limite_descuento=='1' && $forma_fecha_pago <= $datos_descuento_terceros->fecha_limite_descuento){
                                                $descontar = true;
                                            } else {

                                                $llave_prestamos_terceros = "codigo_empresa='$empresa' AND documento_identidad_empleado='$documento_empleado' AND obligacion='$obligacion'";
                                                $valor_total_descontado  = SQL::obtenerValor("movimiento_cuenta_por_cobrar_descuento","SUM(valor_movimiento)",$llave_prestamos_terceros);
                                                $valor_tope  = (int)$datos_descuento_terceros->valor_tope_descuento;

                                                if($valor_tope > (int)$valor_total_descontado){
                                                    $descontar = true;
                                                }
                                            }
                                        }

                                        if ($descontar){

                                            $valor_descuento = 0;
                                            if($forma_periodo=="1"){
                                                $valor_descuento = $datos_descuento_terceros->valor_descontar_mensual;
                                            }elseif($forma_periodo=="2" || $forma_periodo=="3"){

                                                if($periodo_descuento=='2' && $forma_periodo=='2'){
                                                    $valor_descuento = $datos_descuento_terceros->valor_descontar_primera_quincena;

                                                }elseif($periodo_descuento=='3' && $forma_periodo=='3'){
                                                    $valor_descuento = $datos_descuento_terceros->valor_descontar_segunda_quincena;

                                                }else if($periodo_descuento=='9'){

                                                    if($forma_periodo=='2'){
                                                        $valor_descuento =(int) $datos_descuento_terceros->valor_descontar_primera_quincena;
                                                    }else{
                                                        $valor_descuento =(int) $datos_descuento_terceros->valor_descontar_segunda_quincena;
                                                    }
                                                }
                                            }

                                            if($limite_descuento=='3'){

                                                $saldo_prestamo = (int)$valor_tope - (int)$valor_total_descontado;

                                                if ($valor_descuento > $saldo_prestamo){
                                                    $valor_descuento = (int)$saldo_prestamo;
                                                }
                                            }

                                            $transaccion_contable_descuento = $datos_descuento_terceros->transaccion_contable_descuento;
                                            $codigo_contable = SQL::obtenerValor("transacciones_contables_empleado","codigo_contable","codigo='$transaccion_contable_descuento'");
                                            $sentido         = SQL::obtenerValor("transacciones_contables_empleado","sentido","codigo='$transaccion_contable_descuento'");

                                            $codigo_empresa_auxiliar     = 0;
                                            $codigo_anexo_contable       = "";
                                            $codigo_auxiliar_contable    = 0;

                                            $datos = array(
                                                /////////llave primaria////////
                                                "ano_generacion"               => $ano_generacion,
                                                "mes_generacion"               => $mes_generacion,
                                                "codigo_planilla"              => $forma_codigo_planilla,
                                                "periodo_pago"                 => $forma_periodo,
                                                "fecha_pago_planilla"          => $forma_fecha_pago,
                                                "codigo_sucursal"              => $codigo_sucursal,
                                                ///////////////////////////////
                                                "codigo_empresa_auxiliar"      => $codigo_empresa_auxiliar,
                                                "codigo_anexo_contable"        => $codigo_anexo_contable,
                                                "codigo_auxiliar_contable"     => $codigo_auxiliar_contable,
                                                ///////////////////////////////
                                                "fecha_generacion"             => date("Y-m-d"),
                                                //////////////////////////////
                                                "codigo_empresa"               => $datos_descuento_terceros->codigo_empresa,
                                                "documento_identidad_empleado" => $datos_descuento_terceros->documento_identidad_empleado,
                                                "codigo_transaccion_contable"  => $transaccion_contable_descuento,
                                                "obligacion"                   => $datos_descuento_terceros->obligacion,
                                                "valor_movimiento"             => $valor_descuento,
                                                ///////////////////////////////
                                                "codigo_contable"              => $codigo_contable,
                                                "sentido"                      => $sentido,
                                                ///////Datos de control////////
                                                "contabilizado"                => "2",
                                                "codigo_usuario_registra"      => $sesion_codigo_usuario
                                            );

                                            $condicion = "ano_generacion='$ano_generacion' AND mes_generacion='$mes_generacion'";
                                            $condicion .= " AND codigo_planilla='$forma_codigo_planilla' AND periodo_pago='$forma_periodo'";
                                            $condicion .= " AND codigo_empresa='$codigo_empresa'";
                                            $condicion .= " AND documento_identidad_empleado='$datos_descuento_terceros->documento_identidad_empleado'";
                                            $condicion .= " AND obligacion='$datos_descuento_terceros->obligacion'";
                                            $eliminar  = SQL::eliminar("movimiento_cuenta_por_cobrar_descuento",$condicion);
                                            $insertar  = SQL::insertar("movimiento_cuenta_por_cobrar_descuento",$datos);

                                            if(!$insertar){
                                                $error   = true;
                                                $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
                                            }else{

                                                $condicion = "documento_identidad_empleado='$datos_descuento_terceros->documento_identidad_empleado' AND codigo_sucursal='$datos_descuento_terceros->codigo_sucursal' AND obligacion='$datos_descuento_terceros->obligacion' AND fecha_pago_planilla='$forma_fecha_pago'";
                                                ///////Elimino los movimiento que se han generado///////////
                                                $eliminar = SQL::eliminar("movimiento_cuenta_por_cobrar_empleado",$condicion);
                                                $eliminar = SQL::eliminar("movimiento_cuenta_por_pagar_tercero",$condicion);

                                                $fecha_generacion = date("Y-m-d H:i:s");

                                                $transaccion_contable_empleado = $datos_descuento_terceros->transaccion_contable_empleado;
                                                $codigo_contable = SQL::obtenerValor("transacciones_contables_empleado","codigo_contable","codigo='$transaccion_contable_empleado'");
                                                $sentido         = SQL::obtenerValor("transacciones_contables_empleado","sentido","codigo='$transaccion_contable_empleado'");

                                                $datos = array(
                                                    "fecha_generacion"             => $fecha_generacion,
                                                    "codigo_sucursal"              => $codigo_sucursal,
                                                    "fecha_pago_planilla"          => $forma_fecha_pago,
                                                    ///////////////////////////////
                                                    "codigo_empresa_auxiliar"      => $codigo_empresa_auxiliar,
                                                    "codigo_anexo_contable"        => $codigo_anexo_contable,
                                                    "codigo_auxiliar_contable"     => $codigo_auxiliar_contable,
                                                    ///////////////////////////////
                                                    "codigo_empresa"               => $datos_descuento_terceros->codigo_empresa,
                                                    "documento_identidad_empleado" => $datos_descuento_terceros->documento_identidad_empleado,
                                                    "codigo_transaccion_contable"  => $transaccion_contable_empleado,
                                                    "obligacion"                   => $datos_descuento_terceros->obligacion,
                                                    "valor_movimiento"             => $valor_descuento,
                                                    ///////////////////////////////
                                                    "codigo_contable"              => $codigo_contable,
                                                    "sentido"                      => $sentido,
                                                    ///////Datos de control////////
                                                    "contabilizado"                => "2",
                                                    "codigo_usuario_registra"      => $sesion_codigo_usuario,
                                                );

                                                $insertar = SQL::insertar("movimiento_cuenta_por_cobrar_empleado",$datos);

                                                if(!$insertar){
                                                    $error   = true;
                                                    $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
                                                }else{

                                                    $transaccion_contable_pagar_tercero = $datos_descuento_terceros->transaccion_contable_pagar_tercero;
                                                    $codigo_contable = SQL::obtenerValor("transacciones_contables_empleado","codigo_contable","codigo='$transaccion_contable_pagar_tercero'");
                                                    $sentido         = SQL::obtenerValor("transacciones_contables_empleado","sentido","codigo='$transaccion_contable_pagar_tercero'");

                                                    $datos = array(
                                                        "documento_identidad_tercero"  => $datos_descuento_terceros->documento_identidad_tercero,
                                                        "fecha_generacion"             => $fecha_generacion,
                                                        "codigo_sucursal"              => $datos_descuento_terceros->codigo_sucursal,
                                                        "fecha_pago_planilla"          => $forma_fecha_pago,
                                                        ///////////////////////////////
                                                        "codigo_empresa_auxiliar"      => $codigo_empresa_auxiliar,
                                                        "codigo_anexo_contable"        => $codigo_anexo_contable,
                                                        "codigo_auxiliar_contable"     => $codigo_auxiliar_contable,
                                                        //////////////////////////////
                                                        "codigo_empresa"               => $datos_descuento_terceros->codigo_empresa,
                                                        "documento_identidad_empleado" => $datos_descuento_terceros->documento_identidad_empleado,
                                                        "codigo_transaccion_contable"  => $transaccion_contable_pagar_tercero,
                                                        "obligacion"                   => $datos_descuento_terceros->obligacion,
                                                        "valor_movimiento"             => $valor_descuento,
                                                        ///////////////////////////////
                                                        "codigo_contable"              => $codigo_contable,
                                                        "sentido"                      => $sentido,
                                                        ///////Datos de control////////
                                                        "contabilizado"                => "2",
                                                        "codigo_usuario_registra"      => $sesion_codigo_usuario,
                                                    );

                                                    $insertar = SQL::insertar("movimiento_cuenta_por_pagar_tercero",$datos);

                                                    if(!$insertar){
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
                    }
                }
            } else {
                $error   = true;
                $mensaje = $textos["VERIFICAR_DATOS"];
                if (!$salario_minimo){
                    $mensaje .= $textos["ERROR_FECHA_SALARIO_MINIMO"];
                }
                if (!$auxilio_transporte){
                    $mensaje .= $textos["ERROR_FECHA_AUXILIO_TRANSPORTE"];
                }
            }
        }
    }

    // Enviar datos con la respuesta del proceso al script que origino la peticion
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

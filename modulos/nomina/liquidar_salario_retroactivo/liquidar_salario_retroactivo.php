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

if (!empty($url_generar)){

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
    $mensaje      = $textos["RETROACTIVO_GENERADO"];

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
    if ($forma_periodo=='3'){
        $fecha_inicio  = $forma_ano_generacion."-".$forma_mes_generacion."-16";
    }
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
        $regsitros = false;
        $mensajeS  = $textos["ERROR_PERIODO1"];
        $mensajeS  .= $textos["ERROR_PERIODO2"];

        foreach($forma_sucursales as $codigo_sucursal){

            $consulta = SQL::obtenerValor("periodos_contables","estado","codigo_sucursal='$codigo_sucursal' AND ('$forma_fecha_pago' BETWEEN fecha_inicio AND fecha_fin) AND id_modulo='$forma_id_modulo' AND estado='1'");

            if(!$consulta){
                $nombreSucursal = SQL::obtenerValor("sucursales","nombre","codigo='$codigo_sucursal'");
                $mensajeS .="- ".$nombreSucursal."\n";
                $contadorNO+=1;
            }
        }

        if($contadorNO>0){
            $error        = true;
            $mensaje      = $mensajeS;

        }else{

            foreach ($forma_sucursales as $codigo_sucursal) {

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

                    $fecha_pago     = explode("-",$forma_fecha_pago);
                    $ano_generacion = $fecha_pago[0];
                    $mes_generacion = $fecha_pago[1];

                    $condicion = "codigo_sucursal ='$codigo_sucursal' AND codigo_planilla='$forma_codigo_planilla'";
                    $condicion .= " AND (fecha_ingreso_sucursal<='$forma_fecha_pago'";
                    $condicion .= " AND ((fecha_retiro_sucursal !='0000-00-00' AND fecha_retiro_sucursal>='$forma_fecha_pago')";
                    $condicion .= " OR fecha_retiro_sucursal='0000-00-00')";
                    $condicion .= " ) AND fecha_salario <='$forma_fecha_pago' AND fecha_retroactivo !='0000-00-00' AND fecha_retroactivo<fecha_salario";
                    $condicion .= " AND estado='1' ORDER BY fecha_ingreso_sucursal DESC, fecha_salario DESC";
                    $consulta_sucursal_contrato = SQL::seleccionar(array("consulta_contrato_empleado"),array("*"),$condicion);

                    if (SQL::filasDevueltas($consulta_sucursal_contrato)){

                        while($datos_ingreso = SQL::filaEnObjeto($consulta_sucursal_contrato)){

                            if (!isset($empleado[$datos_ingreso->documento_identidad_empleado][$datos_ingreso->codigo_sucursal][$datos_ingreso->codigo_planilla])){
                                $empleado[$datos_ingreso->documento_identidad_empleado][$datos_ingreso->codigo_sucursal][$datos_ingreso->codigo_planilla] = 1;
                            } else {
                                continue;
                            }

                            $condicion = "codigo_empresa='$datos_ingreso->codigo_empresa' AND fecha_ingreso='$datos_ingreso->fecha_ingreso'";
                            $condicion .= " AND documento_identidad_empleado='$datos_ingreso->documento_identidad_empleado' AND fecha_salario<'$datos_ingreso->fecha_salario'";
                            $condicion .= " AND salario<'$datos_ingreso->salario'";
                            //$condicion .= " AND fecha_salario>'$fecha_ano_retroactivo'";

                            $consulta_salario_anterior    = SQL::seleccionar(array("salario_sucursal_contrato"),array("*"),$condicion,"","","0","1");

                            $condicion_retroactivo = "codigo_empresa='$datos_ingreso->codigo_empresa' AND fecha_ingreso='$datos_ingreso->fecha_ingreso'";
                            $condicion_retroactivo = "codigo_sucursal='$datos_ingreso->codigo_sucursal' AND fecha_ingreso_sucursal='$datos_ingreso->fecha_ingreso_sucursal'";
                            $condicion_retroactivo .= " AND documento_identidad_empleado='$datos_ingreso->documento_identidad_empleado'";
                            $condicion_retroactivo .= " AND fecha_salario='$datos_ingreso->fecha_salario'";
                            $consulta_salario_retoractivo = SQL::seleccionar(array("movimientos_salario_retroactivo"),array("*"),$condicion_retroactivo,"","","0","1");

                            if (SQL::filasDevueltas($consulta_salario_anterior) && !SQL::filasDevueltas($consulta_salario_retoractivo)){



                                $datos_salario      = SQL::filaEnObjeto($consulta_salario_anterior);
                                $condicion = "codigo_empresa='$datos_salario->codigo_empresa' AND fecha_ingreso_empresa='$datos_salario->fecha_ingreso'";
                                $condicion .= " AND codigo_sucursal='$datos_salario->codigo_sucursal' AND fecha_ingreso_sucursal='$datos_salario->fecha_ingreso_sucursal'";
                                $condicion .= " AND fecha_salario='$datos_salario->fecha_salario' AND documento_identidad_empleado='$datos_salario->documento_identidad_empleado'";

                                $fecha_ingreso_empresa = $datos_ingreso->fecha_ingreso;
                                if ($fecha_ingreso_empresa > $datos_ingreso->fecha_retroactivo){
                                    $fecha_liquidacion = $fecha_ingreso_empresa;
                                } else {
                                    $fecha_liquidacion = $datos_ingreso->fecha_retroactivo;
                                }
                                $salario_actual        = $datos_ingreso->salario;
                                $valor_dia             = $datos_ingreso->valor_dia;
                                $fecha_ano_retroactivo = $forma_ano_generacion."-00-00";

                                $fecha_retroactivo  = explode("-",$fecha_liquidacion);
                                $ano_retroactivo    = (int)$fecha_retroactivo[0];
                                $mes_retroactivo    = (int)$fecha_retroactivo[1];
                                $dia_retroactivo    = (int)$fecha_retroactivo[2];
                                $ano_planilla       = (int)$forma_ano_generacion;
                                $mes_planilla       = (int)$forma_mes_generacion;

                                $diferencia_salario = ($datos_ingreso->salario - $datos_salario->salario) /30;
                                $fecha_salario      = $datos_salario->fecha_salario;
                                $fecha_registro     = date("Y-m-d H:i:s");

                                if ($forma_periodo == '2'){
                                    $fecha_fin = $forma_ano_generacion."-".$forma_mes_generacion."-15";
                                    $dia_inicio = "01";
                                } else {
                                    $dia_inicio = "16";
                                }

                                $dias_movimiento = 0;

                                for ($ano=$ano_retroactivo;$ano<=$ano_planilla;$ano++){

                                    for($mes=$mes_retroactivo;$mes<=$mes_planilla;$mes++){

                                        if ($mes==$mes_retroactivo && $ano==$ano_retroactivo && $mes<$forma_mes_generacion){

                                            if($dia_retroactivo>1){
                                                $dias_movimiento = $dias_movimiento + (31 - $dia_retroactivo);
                                            } else {
                                                $dias_movimiento = $dias_movimiento + 30;
                                            }
                                        }
                                        if ($mes==$mes_planilla && $ano==$ano_planilla){

                                            if($forma_periodo=='3' && $fecha_liquidacion < $forma_ano_generacion."-".$forma_mes_generacion."-16"){
                                                if ($ano_retroactivo == $ano_planilla && $mes_retroactivo == $mes_planilla){
                                                    $dias_movimiento = $dias_movimiento + (16 - $dia_retroactivo);
                                                } else {
                                                    $dias_movimiento = $dias_movimiento + 15;
                                                }
                                            }
                                        }
                                        if ($mes<$mes_planilla && $mes>$mes_retroactivo){
                                            $dias_movimiento = $dias_movimiento + 30;
                                        }
                                    }
                                }

                                $codigo_contable = SQL::obtenerValor("transacciones_contables_empleado","codigo_contable","codigo='$datos_ingreso->codigo_transaccion_salario'");
                                $sentido         = SQL::obtenerValor("transacciones_contables_empleado","sentido","codigo='$datos_ingreso->codigo_transaccion_salario'");
                                $valor_movimiento  = (int)($dias_movimiento * $diferencia_salario);

                                $datos = array(
                                    "ano_generacion"                => $forma_ano_generacion,
                                    "mes_generacion"                => $forma_mes_generacion,
                                    "codigo_planilla"               => $forma_codigo_planilla,
                                    "periodo_pago"                  => $forma_periodo,
                                    "documento_identidad_empleado"  => $datos_ingreso->documento_identidad_empleado,
                                    "codigo_transaccion_contable"   => $datos_ingreso->codigo_transaccion_salario,
                                    ///////////////////////////////
                                    "codigo_empresa"                => $datos_ingreso->codigo_empresa,
                                    "fecha_ingreso_empresa"         => $datos_ingreso->fecha_ingreso,
                                    "codigo_sucursal"               => $codigo_sucursal,
                                    "fecha_ingreso_sucursal"        => $datos_ingreso->fecha_ingreso_sucursal,
                                    "fecha_salario"                 => $datos_ingreso->fecha_salario,
                                    ///////////////////////////////
                                    "fecha_pago_planilla"           => $forma_fecha_pago,
                                    "fecha_inicio_pago"             => $fecha_inicio,
                                    "fecha_hasta_pago"              => $fecha_fin,
                                    ///////////////////////////////
                                    "codigo_empresa_auxiliar"       => 0,
                                    "codigo_anexo_contable"         => "",
                                    ///tabla_auxiliares_contables
                                    "codigo_auxiliar_contable"      => 0,
                                    ///////////////////////////////
                                    "codigo_contable"               => $codigo_contable,
                                    "sentido"                       => $sentido,
                                    "fecha_inicio_retroactivo"      => $fecha_liquidacion,
                                    "dias_retroactivo"              => $dias_movimiento,
                                    "valor_movimiento"              => $valor_movimiento,
                                    "contabilizado"                 => "0",
                                    "codigo_usuario_genera"         => $sesion_codigo_usuario,
                                    "fecha_registro"                => $fecha_registro
                                );
                                $insertar = SQL::reemplazar("movimientos_salario_retroactivo",$datos);
                                if ($insertar){
                                    $regsitros = true;
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    // Enviar datos con la respuesta del proceso al script que origino la peticion
    $respuesta    = array();
    $respuesta[0] = $error;
    if (!$regsitros && $contadorNO==0){
        $mensaje = $textos["SIN_INFORMACION"];
    }
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

<?php
/**
*
* Copyright (C) 2020 Jobdaily
* Raul Mauricio Oidor Lozano <ozzymauricio75@gmail.com>
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

if(isset($url_verificar_planillas) && isset($url_codigo_planilla)){

    $tipo_planilla = SQL::obtenerValor("planillas","periodo_pago","codigo = '$url_codigo_planilla'");

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
    } else if($tipo_planilla == '4') {
        $periodo = array(
            "9" => $textos["FECHA_UNICA"]
        );
    }

    HTTP::enviarJSON($periodo);
    exit;
}

if (isset($url_completar)) {
    if (($url_item) == "selector2") {
        echo SQL::datosAutoCompletar("seleccion_transacciones_contables_empleado", $url_q);
    }
    exit;
}
if(isset($url_verificar_empleado)){
    $condicion_extra = "id_sucursal ='$url_codigo_sucursal'";
    echo SQL::datosAutoCompletar("seleccion_empleados", $url_q, $condicion_extra);
    exit;
}

// Generar el formulario para la captura de datos
if (!empty($url_generar)) {
    $error  = "";
    $titulo = $componente->nombre;

    $anos = array();
    $anos[((int)date("Y"))-2] = date("Y");
    $ano_actual = date("Y");
    for($i=1;$i<8;$i++){
        $anos[(int)date("Y")+$i]=date("Y")+$i;
    }

    $planillas  = HTML::generarDatosLista("planillas", "codigo", "descripcion","codigo>=0");
    $sucursales = HTML::generarDatosLista("sucursales", "codigo", "nombre","codigo>=0");

    if ($planillas && $sucursales){

        // Definicion de pestana personal
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::listaSeleccionSimple("codigo_sucursal", $textos["SUCURSAL"],$sucursales,"",array("onchange"=>"inicializarEmpleado()"))
            ),
            array(
                HTML::campoTextoCorto("*selector1", $textos["EMPLEADO"], 50, 255, "", array("title" => $textos["AYUDA_EMPLEADO"], "onfocus" => "autocompletableEmpleado(this);", "onKeyUp" => "limpiar_oculto_Autocompletable(this, documento_identidad)")).
                HTML::campoOculto("documento_identidad","")
            ),
            array(
                HTML::campoTextoCorto("*selector2",$textos["TRANSACCION_CONTABLE"], 50, 50,"",array("title"=>$textos["AYUDA_TRANSACCION_CONTABLE"],"class"=>"autocompletable")).
                HTML::campoOculto("codigo_transaccion_contable","")
            ),
            array(
                HTML::campoTextoCorto("*valor_movimiento",$textos["VALOR_MOVIMIENTO"], 20, 20,"",array("title"=>$textos["AYUDA_VALOR_MOVIMIENTO"],"onKeyPress" => "return campoEntero(event);"))
            ),
            array(
                HTML::listaSeleccionSimple("codigo_planilla", $textos["PLANILLA"],$planillas,"",array("onchange"=>"cargarPeriodo('codigo_planilla','periodo')"))
            ),
            array(
                HTML::listaSeleccionSimple("periodo", $textos["PERIODO"],"","")
            ),
            array(
                HTML::campoTextoCorto("*fecha_pago_planilla", $textos["FECHA_PAGO"], 10, 10, date("Y-m-d"),array("class"=>"selectorFecha","title"=>$textos["AYUDA_FECHA_PAGO"]))
            )
        );
        // Definicion de botones
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar")
        );
        $contenido = HTML::generarPestanas($formularios, $botones);
        // Enviar datos para la generacion del formulario al script que origino la peticion
    } else {
        $continuar = "";
        $error = $textos["NO_EXISTEN_DATOS"];
        if (!$sucursales){
            $error .= $textos["CREAR_SUCURSAL"];
        }
        if (!$planillas){
            $error .= $textos["CREAR_PLANILLAS"];
        }
        $error = $textos["CREAR_DATOS"];
    }
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

// Adicionar los datos provenientes del formulario
}elseif (!empty($forma_procesar)) {
    // Asumir por defecto que no hubo error
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];

    if ($forma_codigo_sucursal==0){
        $error   = true;
        $mensaje = $textos["ERROR_SUCURSAL_VACIO"];
    } else if(empty($forma_documento_identidad_empleado) && empty($forma_selector1)){
        $error   = true;
        $mensaje = $textos["ERROR_EMPLEADO_VACIO"];
    } else if (empty($forma_codigo_transaccion_contable) && empty($forma_selector2)){
        $error   = true;
        $mensaje = $textos["ERROR_TRANSACCION_CONTABLE"];
    } else if (empty($forma_valor_movimiento)){
        $error   = true;
        $mensaje = $textos["ERROR_VALOR_MOVIMIENTO"];
    } else if($forma_codigo_planilla==0){
        $error   = true;
        $mensaje = $textos["ERROR_PLANILLA_VACIO"];
    } else if (empty($forma_periodo)){
        $error   = true;
        $mensaje = $textos["ERROR_PERIODO_VACIO"];
    } else if(empty($forma_fecha_pago_planilla)){
        $error   = true;
        $mensaje = $textos["ERROR_FECHA_PAGO_VACIO"];
    } else {

        $consulta_empleado = SQL::seleccionar(array("consulta_contrato_empleado"),array("*"),"documento_identidad_empleado='$forma_documento_identidad' AND codigo_sucursal='$forma_codigo_sucursal' AND estado='1' AND fecha_ingreso_sucursal<='$forma_fecha_pago_planilla'","","fecha_ingreso_sucursal DESC",0,1);

        if (!SQL::filasDevueltas($consulta_empleado)){
            $error   = true;
            $mensaje = $textos["ERROR_CONTRATO"];
        } else {

            $fecha_generacion = explode("-",$forma_fecha_pago_planilla);
            $ano_generacion   = $fecha_generacion[0];
            $mes_generacion   = $fecha_generacion[1];

            $datos_empleado  = SQL::filaEnObjeto($consulta_empleado);
            $codigo_contable = SQL::obtenerValor("transacciones_contables_empleado","codigo_contable","codigo='$forma_codigo_transaccion_contable'");
            $sentido         = SQL::obtenerValor("transacciones_contables_empleado","sentido","codigo='$forma_codigo_transaccion_contable'");
            $condicion       = "ano_generacion='$ano_generacion' AND mes_generacion='$mes_generacion'";
            $condicion       .= " AND codigo_planilla='$forma_codigo_planilla' AND periodo_pago='$forma_periodo'";
            $condicion       .= " AND codigo_transaccion_contable='$forma_codigo_transaccion_contable'";
            $consecutivo     = SQL::obtenerValor("movimientos_nomina_migracion","MAX(consecutivo)",$condicion);
            if ($consecutivo){
                $consecutivo++;
            } else {
                $consecutivo = 1;
            }

            $datos = array(
                "ano_generacion"               => $ano_generacion,
                "mes_generacion"               => $mes_generacion,
                "codigo_planilla"              => $forma_codigo_planilla,
                "periodo_pago"                 => $forma_periodo,
                "codigo_transaccion_contable"  => $forma_codigo_transaccion_contable,
                "consecutivo"                  => $consecutivo,
                "codigo_empresa"               => $datos_empleado->codigo_empresa,
                "documento_identidad_empleado" => $forma_documento_identidad,
                "fecha_ingreso_empresa"        => $datos_empleado->fecha_ingreso,
                "codigo_sucursal"              => $forma_codigo_sucursal,
                "fecha_ingreso_sucursal"       => $datos_empleado->fecha_ingreso_sucursal,
                "fecha_pago_planilla"          => $forma_fecha_pago_planilla,
                "codigo_empresa_auxiliar"      => 0,
                "codigo_anexo_contable"        => "",
                "codigo_auxiliar_contable"     => 0,
                "codigo_contable"              => $codigo_contable,
                "sentido"                      => $sentido,
                "valor_movimiento"             => $forma_valor_movimiento,
                "contabilizado"                => 0,
                "codigo_usuario_genera"        => $sesion_codigo_usuario,
                "fecha_registro"               => date("Y-m-d H:i:s"),
                "codigo_usuario_modifica"      => 0,
                "fecha_modificacion"           => "0000-00-00"
            );

            $insertar = SQL::insertar("movimientos_nomina_migracion",$datos);

            if (!$insertar){
                $error = true;
                $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
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

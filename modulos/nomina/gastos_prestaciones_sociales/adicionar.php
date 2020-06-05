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

if (!empty($url_generar)) {

    $consulta    = SQL::seleccionar(array("transacciones_contables_empleado"),array("*"),"codigo != 0 AND codigo_concepto_transaccion_contable = '12'");
    $concepto_12 = SQL::filasDevueltas($consulta);

    $consulta    = SQL::seleccionar(array("transacciones_contables_empleado"),array("*"),"codigo != 0 AND codigo_concepto_transaccion_contable = '13'");
    $concepto_13 = SQL::filasDevueltas($consulta);

    $consulta    = SQL::seleccionar(array("transacciones_contables_empleado"),array("*"),"codigo != 0 AND codigo_concepto_transaccion_contable = '14'");
    $concepto_14 = SQL::filasDevueltas($consulta);

    $consulta    = SQL::seleccionar(array("transacciones_contables_empleado"),array("*"),"codigo != 0 AND codigo_concepto_transaccion_contable = '15'");
    $concepto_15 = SQL::filasDevueltas($consulta);

    $consulta    = SQL::seleccionar(array("transacciones_contables_empleado"),array("*"),"codigo != 0 AND codigo_concepto_transaccion_contable = '16'");
    $concepto_16 = SQL::filasDevueltas($consulta);

    $consulta    = SQL::seleccionar(array("transacciones_contables_empleado"),array("*"),"codigo != 0 AND codigo_concepto_transaccion_contable = '17'");
    $concepto_17 = SQL::filasDevueltas($consulta);

    $consulta    = SQL::seleccionar(array("transacciones_contables_empleado"),array("*"),"codigo != 0 AND codigo_concepto_transaccion_contable = '18'");
    $concepto_18 = SQL::filasDevueltas($consulta);

    $consulta    = SQL::seleccionar(array("transacciones_contables_empleado"),array("*"),"codigo != 0 AND codigo_concepto_transaccion_contable = '19'");
    $concepto_19 = SQL::filasDevueltas($consulta);

    $consulta    = SQL::seleccionar(array("transacciones_contables_empleado"),array("*"),"codigo != 0 AND codigo_concepto_transaccion_contable = '20'");
    $concepto_20 = SQL::filasDevueltas($consulta);

    $consulta    = SQL::seleccionar(array("transacciones_contables_empleado"),array("*"),"codigo != 0 AND codigo_concepto_transaccion_contable = '21'");
    $concepto_21 = SQL::filasDevueltas($consulta);

    $consulta    = SQL::seleccionar(array("transacciones_contables_empleado"),array("*"),"codigo != 0 AND codigo_concepto_transaccion_contable = '22'");
    $concepto_22 = SQL::filasDevueltas($consulta);

    $consulta    = SQL::seleccionar(array("transacciones_contables_empleado"),array("*"),"codigo != 0 AND codigo_concepto_transaccion_contable = '23'");
    $concepto_23 = SQL::filasDevueltas($consulta);

    $consulta    = SQL::seleccionar(array("transacciones_contables_empleado"),array("*"),"codigo != 0 AND codigo_concepto_transaccion_contable = '24'");
    $concepto_24 = SQL::filasDevueltas($consulta);

    $consulta    = SQL::seleccionar(array("transacciones_contables_empleado"),array("*"),"codigo != 0 AND codigo_concepto_transaccion_contable = '25'");
    $concepto_25 = SQL::filasDevueltas($consulta);

    $consulta    = SQL::seleccionar(array("transacciones_contables_empleado"),array("*"),"codigo != 0 AND codigo_concepto_transaccion_contable = '26'");
    $concepto_26 = SQL::filasDevueltas($consulta);

    $consulta    = SQL::seleccionar(array("transacciones_contables_empleado"),array("*"),"codigo != 0 AND codigo_concepto_transaccion_contable = '27'");
    $concepto_27 = SQL::filasDevueltas($consulta);

    $consulta    = SQL::seleccionar(array("transacciones_contables_empleado"),array("*"),"codigo != 0 AND codigo_concepto_transaccion_contable = '28'");
    $concepto_28 = SQL::filasDevueltas($consulta);

    if($concepto_12 == 0 || $concepto_13 == 0 || $concepto_14 == 0 || $concepto_15 == 0 || $concepto_16 == 0 || $concepto_17 == 0 || $concepto_18 == 0 || $concepto_19 == 0 || $concepto_20 == 0 || $concepto_21 == 0 || $concepto_22 == 0 || $concepto_23 == 0 || $concepto_24 == 0 || $concepto_25 == 0 || $concepto_26 == 0 || $concepto_27 == 0 || $concepto_28 == 0){

        $mensaje = $textos["ERROR_TABLAS_VACIAS"];
        $conceptos = array();

        if($concepto_12 == 0){
            $conceptos[] = "- ".$textos["CESANTIA_PAGO_PRESTACION"];
        }

        if($concepto_13 == 0){
            $conceptos[] = "- ".$textos["CESANTIA_PAGO_GASTO"];
        }

        if($concepto_14 == 0){
            $conceptos[] = "- ".$textos["CESANTIA_TRASLADO_FONDO"];
        }

        if($concepto_15 == 0){
            $conceptos[] = "- ".$textos["CESANTIA_CAUSACION_PRESTACION"];
        }

        if($concepto_16 == 0){
            $conceptos[] = "- ".$textos["CESANTIA_CAUSACION_GASTO"];
        }

        if($concepto_17 == 0){
            $conceptos[] = "- ".$textos["INTERESES_PAGO_PRESTACION"];
        }

        if($concepto_18 == 0){
            $conceptos[] = "- ".$textos["INTERESES_PAGO_GASTO"];
        }

        if($concepto_19 == 0){
            $conceptos[] = "- ".$textos["INTERESES_CAUSACION_PRESTACION"];
        }

        if($concepto_20 == 0){
            $conceptos[] = "- ".$textos["INTERESES_CAUSACION_GASTO"];
        }

        if($concepto_21 == 0){
            $conceptos[] = "- ".$textos["PRIMA_PAGO_PRESTACION"];
        }

        if($concepto_22 == 0){
            $conceptos[] = "- ".$textos["PRIMA_PAGO_GASTO"];
        }

        if($concepto_23 == 0){
            $conceptos[] = "- ".$textos["PRIMA_CAUSACION_PRESTACION"];
        }

        if($concepto_24 == 0){
            $conceptos[] = "- ".$textos["PRIMA_CAUSACION_GASTO"];
        }

        if($concepto_25 == 0){
            $conceptos[] = "- ".$textos["VACACION_PAGO_PRESTACION"];
        }

        if($concepto_26 == 0){
            $conceptos[] = "- ".$textos["VACACION_PAGO_GASTO"];
        }

        if($concepto_27 == 0){
            $conceptos[] = "- ".$textos["VACACION_CAUSACION_PRESTACION"];
        }

        if($concepto_28 == 0){
            $conceptos[] = "- ".$textos["VACACION_CAUSACION_GASTO"];
        }

        $conceptosTexto = implode("\n",$conceptos);
        $mensaje       .= $conceptosTexto;
        $error          = $mensaje;
        $titulo         = "";
        $contenido      = "";
    }else{
        $error  = "";
        $titulo = $componente->nombre;

        $consecutivo = (int)SQL::obtenerValor("gastos_prestaciones_sociales","max(codigo)","");
        if($consecutivo){
            $consecutivo++;
        }else{
            $consecutivo=1;
        }

        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::campoTextoCorto("*codigo", $textos["CODIGO"], 7, 5, $consecutivo, array("title" => $textos["AYUDA_CODIGO"], "onblur" => "validarItem(this);","onKeyPress" => "return campoEntero(event)"))
            ),
            array(
                HTML::campoTextoCorto("*descripcion", $textos["DESCRIPCION"], 30, 255, "", array("title" => $textos["AYUDA_DESCRIPCION"], "onblur" => "validarItem(this);"))
            )
        );

        $formularios["PESTANA_CESANTIAS"] = array(
            array(
                HTML::listaSeleccionSimple("*cesantia_pago_prestacion", $textos["CESANTIA_PAGO_PRESTACION"], HTML::generarDatosLista("transacciones_contables_empleado", "codigo", "descripcion","codigo != 0 AND codigo_concepto_transaccion_contable = '12'"),"",array("title" => $textos["AYUDA_TRANSACCION"]))
            ),
            array(
                HTML::listaSeleccionSimple("*cesantia_pago_gasto", $textos["CESANTIA_PAGO_GASTO"], HTML::generarDatosLista("transacciones_contables_empleado", "codigo", "descripcion","codigo != 0 AND codigo_concepto_transaccion_contable = '13'"),"",array("title" => $textos["AYUDA_TRANSACCION"]))
            ),
            array(
                HTML::listaSeleccionSimple("*cesantia_traslado_fondo", $textos["CESANTIA_TRASLADO_FONDO"], HTML::generarDatosLista("transacciones_contables_empleado", "codigo", "descripcion","codigo != 0 AND codigo_concepto_transaccion_contable = '14'"),"",array("title" => $textos["AYUDA_TRANSACCION"]))
            ),
            array(
                HTML::listaSeleccionSimple("*cesantia_causacion_prestacion", $textos["CESANTIA_CAUSACION_PRESTACION"], HTML::generarDatosLista("transacciones_contables_empleado", "codigo", "descripcion","codigo != 0 AND codigo_concepto_transaccion_contable = '15'"),"",array("title" => $textos["AYUDA_TRANSACCION"]))
            ),
            array(
                HTML::listaSeleccionSimple("*cesantia_causacion_gasto", $textos["CESANTIA_CAUSACION_GASTO"], HTML::generarDatosLista("transacciones_contables_empleado", "codigo", "descripcion","codigo != 0 AND codigo_concepto_transaccion_contable = '16'"),"",array("title" => $textos["AYUDA_TRANSACCION"]))
            )
        );

        $formularios["PESTANA_INTERESES"] = array(
            array(
                HTML::listaSeleccionSimple("*intereses_pago_prestacion", $textos["INTERESES_PAGO_PRESTACION"], HTML::generarDatosLista("transacciones_contables_empleado", "codigo", "descripcion","codigo != 0 AND codigo_concepto_transaccion_contable = '17'"),"",array("title" => $textos["AYUDA_TRANSACCION"]))
            ),
            array(
                HTML::listaSeleccionSimple("*intereses_pago_gasto", $textos["INTERESES_PAGO_GASTO"], HTML::generarDatosLista("transacciones_contables_empleado", "codigo", "descripcion","codigo != 0 AND codigo_concepto_transaccion_contable = '18'"),"",array("title" => $textos["AYUDA_TRANSACCION"]))
            ),
            array(
                HTML::listaSeleccionSimple("*intereses_causacion_prestacion", $textos["INTERESES_CAUSACION_PRESTACION"], HTML::generarDatosLista("transacciones_contables_empleado", "codigo", "descripcion","codigo != 0 AND codigo_concepto_transaccion_contable = '19'"),"",array("title" => $textos["AYUDA_TRANSACCION"]))
            ),
            array(
                HTML::listaSeleccionSimple("*intereses_causacion_gasto", $textos["INTERESES_CAUSACION_GASTO"], HTML::generarDatosLista("transacciones_contables_empleado", "codigo", "descripcion","codigo != 0 AND codigo_concepto_transaccion_contable = '20'"),"",array("title" => $textos["AYUDA_TRANSACCION"]))
            )
        );

        $formularios["PESTANA_PRIMAS"] = array(
            array(
                HTML::listaSeleccionSimple("*prima_pago_prestacion", $textos["PRIMA_PAGO_PRESTACION"], HTML::generarDatosLista("transacciones_contables_empleado", "codigo", "descripcion","codigo != 0 AND codigo_concepto_transaccion_contable = '21'"),"",array("title" => $textos["AYUDA_TRANSACCION"]))
            ),
            array(
                HTML::listaSeleccionSimple("*prima_pago_gasto", $textos["PRIMA_PAGO_GASTO"], HTML::generarDatosLista("transacciones_contables_empleado", "codigo", "descripcion","codigo != 0 AND codigo_concepto_transaccion_contable = '22'"),"",array("title" => $textos["AYUDA_TRANSACCION"]))
            ),
            array(
                HTML::listaSeleccionSimple("*prima_causacion_prestacion", $textos["PRIMA_CAUSACION_PRESTACION"], HTML::generarDatosLista("transacciones_contables_empleado", "codigo", "descripcion","codigo != 0 AND codigo_concepto_transaccion_contable = '23'"),"",array("title" => $textos["AYUDA_TRANSACCION"]))
            ),
            array(
                HTML::listaSeleccionSimple("*prima_causacion_gasto", $textos["PRIMA_CAUSACION_GASTO"], HTML::generarDatosLista("transacciones_contables_empleado", "codigo", "descripcion","codigo != 0 AND codigo_concepto_transaccion_contable = '24'"),"",array("title" => $textos["AYUDA_TRANSACCION"]))
            )
        );

        $formularios["PESTANA_VACACIONES"] = array(
            array(
                HTML::listaSeleccionSimple("*vacacion_pago_prestacion_disfrute", $textos["VACACION_PAGO_PRESTACION_DISFRUTE"], HTML::generarDatosLista("transacciones_contables_empleado", "codigo", "descripcion","codigo != 0 AND codigo_concepto_transaccion_contable = '25'"),"",array("title" => $textos["AYUDA_TRANSACCION"]))
            ),
            array(
                HTML::listaSeleccionSimple("*vacacion_pago_gasto_disfrute", $textos["VACACION_PAGO_GASTO_DISFRUTE"], HTML::generarDatosLista("transacciones_contables_empleado", "codigo", "descripcion","codigo != 0 AND codigo_concepto_transaccion_contable = '26'"),"",array("title" => $textos["AYUDA_TRANSACCION"]))
            ),
            array(
                HTML::listaSeleccionSimple("*vacacion_pago_prestacion_liquidacion", $textos["VACACION_PAGO_PRESTACION_LIQUIDACION"], HTML::generarDatosLista("transacciones_contables_empleado", "codigo", "descripcion","codigo != 0 AND codigo_concepto_transaccion_contable = '25'"),"",array("title" => $textos["AYUDA_TRANSACCION"]))
            ),
            array(
                HTML::listaSeleccionSimple("*vacacion_pago_gasto_liquidacion", $textos["VACACION_PAGO_GASTO_LIQUIDACION"], HTML::generarDatosLista("transacciones_contables_empleado", "codigo", "descripcion","codigo != 0 AND codigo_concepto_transaccion_contable = '26'"),"",array("title" => $textos["AYUDA_TRANSACCION"]))
            ),
            array(
                HTML::listaSeleccionSimple("*vacacion_causacion_prestacion", $textos["VACACION_CAUSACION_PRESTACION"], HTML::generarDatosLista("transacciones_contables_empleado", "codigo", "descripcion","codigo != 0 AND codigo_concepto_transaccion_contable = '27'"),"",array("title" => $textos["AYUDA_TRANSACCION"])),
            ),
            array(
                HTML::listaSeleccionSimple("*vacacion_causacion_gasto", $textos["VACACION_CAUSACION_GASTO"], HTML::generarDatosLista("transacciones_contables_empleado", "codigo", "descripcion","codigo != 0 AND codigo_concepto_transaccion_contable = '28'"),"",array("title" => $textos["AYUDA_TRANSACCION"]))
            )
        );

        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones);
    }

    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

} elseif (!empty($url_validar)) {

    if ($url_item == "codigo") {
        $existe = SQL::existeItem("gastos_prestaciones_sociales", "codigo", $url_valor,"codigo != 0");
        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_CODIGO"]);
        }
    }else if($url_item == "descripcion"){
        $existe_nombre = SQL::existeItem("gastos_prestaciones_sociales", "descripcion", $url_valor,"codigo != 0");
        if ($existe_nombre) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_DESCRIPCION"]);
        }
    }

} elseif (!empty($forma_procesar)) {

    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];

    $codigo = (int)$forma_codigo;

    if(empty($forma_codigo) || $codigo == 0){
        $error = true;
        $mensaje = $textos["ERROR_CODIGO_VACIO"];
    }elseif(empty($forma_descripcion) || $codigo == 0){
        $error = true;
        $mensaje = $textos["ERROR_DESCRIPCION_VACIO"];
    }elseif(SQL::existeItem("gastos_prestaciones_sociales", "codigo", $forma_codigo,"codigo != 0")){
        $error = true;
        $mensaje = $textos["ERROR_EXISTE_CODIGO"];
    }elseif(SQL::existeItem("gastos_prestaciones_sociales", "descripcion", $forma_descripcion,"codigo != 0")){
        $error = true;
        $mensaje = $textos["ERROR_EXISTE_DESCRIPCION"];
    }else{

        $datos = array (
            "codigo"                               => $forma_codigo,
            "descripcion"                          => $forma_descripcion,
            "cesantia_pago_prestacion"             => $forma_cesantia_pago_prestacion,
            "cesantia_pago_gasto"                  => $forma_cesantia_pago_gasto,
            "cesantia_traslado_fondo"              => $forma_cesantia_traslado_fondo,
            "cesantia_causacion_prestacion"        => $forma_cesantia_causacion_prestacion,
            "cesantia_causacion_gasto"             => $forma_cesantia_causacion_gasto,
            "intereses_pago_prestacion"            => $forma_intereses_pago_prestacion,
            "intereses_pago_gasto"                 => $forma_intereses_pago_gasto,
            "intereses_causacion_prestacion"       => $forma_intereses_causacion_prestacion,
            "intereses_causacion_gasto"            => $forma_intereses_causacion_gasto,
            "prima_pago_prestacion"                => $forma_prima_pago_prestacion,
            "prima_pago_gasto"                     => $forma_prima_pago_gasto,
            "prima_causacion_prestacion"           => $forma_prima_causacion_prestacion,
            "prima_causacion_gasto"                => $forma_prima_causacion_gasto,
            "vacacion_pago_prestacion_disfrute"    => $forma_vacacion_pago_prestacion_disfrute,
            "vacacion_pago_prestacion_liquidacion" => $forma_vacacion_pago_prestacion_liquidacion,
            "vacacion_pago_gasto_disfrute"         => $forma_vacacion_pago_gasto_disfrute,
            "vacacion_pago_gasto_liquidacion"      => $forma_vacacion_pago_gasto_liquidacion,
            "vacacion_causacion_prestacion"        => $forma_vacacion_causacion_prestacion,
            "vacacion_causacion_gasto"             => $forma_vacacion_causacion_gasto
        );
        $insertar = SQL::insertar("gastos_prestaciones_sociales", $datos);

        if (!$insertar) {
            $error   = true;
            $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
        }
    }
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

<?php

/**
*
* Copyright (C) 2020 Jobdaily
* Walter Andrés Márquez Gutiérrez <walteramg@gmail.com>
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
* de APTITUD PARA UN PROPÓITO DETERMINADO. Consulte los detalles de
* la Licencia Pública General GNU para obtener una información más
* detallada.
*
* Debería haber recibido una copia de la Licencia Pública General GNU
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
**/


/*** Devolver datos para autocompletar la búsqueda ***/
if (isset($url_completar)) {
     if (($url_item) == "selector1") {
        echo SQL::datosAutoCompletar("seleccion_transacciones_contables_empleado", $url_q);
    }
    exit;
}

if(isset($url_verificarConceptos) && isset($url_tipo_concepto)){

    $lista = HTML::generarDatosLista("conceptos_transacciones_tiempo","codigo","descripcion", "tipo = '$url_tipo_concepto'");

    HTTP::enviarJSON($lista);
    exit;
}

/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {

    $error  = "";
    $titulo = $componente->nombre;

     /*** Verificar que se haya enviado el ID del elemento a modificar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_MODIFICAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {
        $vistaConsulta = "transacciones_tiempo";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo = '$url_id'");
        $datos         = SQL::filaEnObjeto($consulta);
        $error         = "";
        $titulo        = $componente->nombre;

        $transaccion_contable = SQL::obtenerValor("seleccion_transacciones_contables_empleado", "SUBSTRING_INDEX(nombre,'|',1)", "id = '$datos->codigo_transaccion_contable'");
        $concepto_tiempo      = SQL::obtenerValor("conceptos_transacciones_tiempo", "descripcion", "codigo = '$datos->codigo_concepto_transaccion_tiempo'");

        $tipo_concepto = array(
            "1" => $textos["HORAS_LABORALES"],
            "2" => $textos["LICENCIAS_REMUNERADAS"],
            "3" => $textos["INCAPACIDADES"],
            "4" => $textos["LICENCIAS_NO_REMUNERADAS"],
            "5" => $textos["VACACIONES"]
        );

        $valor_tipo_concepto = SQL::obtenerValor("conceptos_transacciones_tiempo","tipo","codigo='$datos->codigo_concepto_transaccion_tiempo'");

        if($datos->extras_empleado == 1){
            $extras_si = true;
            $extras_no = false;
        }
        else{
            $extras_si = false;
            $extras_no = true;
        }

        $tasa_oculta = "";
        $dividendo   = "";
        $divisor     = "";
        if ($valor_tipo_concepto==2 || $valor_tipo_concepto==4 || $valor_tipo_concepto==5){
            $tasa_oculta = "oculto";
            $dividendo   = "oculto";
            $divisor     = "oculto";
        } else if($valor_tipo_concepto==3){
            $tasa_oculta = "oculto";
            $dividendo   = "";
            $divisor     = "";
        } else {
            $tasa_oculta = "";
            $dividendo   = "oculto";
            $divisor     = "oculto";
        }

        /*** Definición de pestaña personal ***/
        $formularios["PESTANA_BASICA"] = array(
            array(
                HTML::campoTextoCorto("*codigo", $textos["CODIGO"], 8, 8, $datos->codigo, array("title" => $textos["AYUDA_CODIGO"],"onkeypress"=>"return campoEntero(event)", "onBlur" => "validarItem(this);")),
                HTML::campoTextoCorto("*nombre", $textos["NOMBRE"], 25, 25, $datos->nombre, array("title" => $textos["AYUDA_NOMBRE"]))
            ),
            array(
                HTML::campoTextoCorto("*descripcion", $textos["DESCRIPCION"], 50, 255, $datos->descripcion, array("title" => $textos["AYUDA_DESCRIPCION"], "onBlur" => "validarItem(this);"))
            ),
            array(
                HTML::campoTextoCorto("*selector1", $textos["TRANSACCION_CONTABLE"], 30, 255, $transaccion_contable, array("title" => $textos["AYUDA_TRANSACCION_CONTABLE"], "class" => "autocompletable", "onKeyUp" => "limpiar_oculto_Autocompletable(this, codigo_transaccion_contable)"))
                .HTML::campoOculto("codigo_transaccion_contable", $datos->codigo_transaccion_contable)
            ),
            array(
                HTML::listaSeleccionSimple("*tipo_concepto", $textos["TIPO_CONCEPTO"], $tipo_concepto, $valor_tipo_concepto, array("title" => $textos["AYUDA_TIPO_CONCEPTO"], "onChange" => "cargarConceptos();")),
                HTML::campoTextoCorto("tasa", $textos["TASA"], 10, 10, $datos->tasa, array("title" => $textos["AYUDA_TASA"], "onKeyPress" => "return campoDecimal(event)","class" => $tasa_oculta))
            ),
            array(
                HTML::contenedor(
                    HTML::mostrarDato("mensaje",$textos["AYUDA_DIVIDENDO"],""),
                    array("id"=>"contenedor_mensaje_ayuda","class"=>$divisor)
                ),
                HTML::campoTextoCorto("dividendo", $textos["DIVIDENDO"], 2, 2, $datos->dividendo, array("title" => $textos["AYUDA_DIVIDENDO"], "onKeyPress" => "return campoDecimal(event)", "class" => $dividendo)),
                HTML::campoTextoCorto("divisor", $textos["DIVISOR"], 2, 2, $datos->divisor, array("title" => $textos["AYUDA_DIVISOR"], "onKeyPress" => "return campoDecimal(event)", "class" => $divisor))
            ),
            array(
                HTML::listaSeleccionSimple("*codigo_concepto_transaccion_tiempo", $textos["CONCEPTO_TIEMPO"], HTML::generarDatosLista("conceptos_transacciones_tiempo", "codigo", "descripcion"), $datos->codigo_concepto_transaccion_tiempo, array("title" => $textos["AYUDA_CONCEPTO_TIEMPO"]))
            ),
             array(
                HTML::mostrarDato("extras", $textos["PAGA_EXTRAS"], "")
            ),
            array(
                HTML::marcaSeleccion("extras", $textos["SI"], 1, $extras_si, array("title" => $textos["AYUDA_EXTRAS"], "id" => "extras_si")),
                HTML::marcaSeleccion("extras", $textos["NO"], 0, $extras_no, array("title" => $textos["AYUDA_EXTRAS"], "id" => "extras_no"))
            )
        );

        if($datos->resta_salario == 1){
            $salario_si  = true;
            $salario_no  = false;
        } else{
            $salario_si  = false;
            $salario_no  = true;
        }

        if($datos->resta_auxilio_transporte == 1){
            $auxilio_si  = true;
            $auxilio_no  = false;
        } else{
            $auxilio_si  = false;
            $auxilio_no  = true;
        }

        if($datos->resta_cesantias == 1){
            $cesantias_si  = true;
            $cesantias_no  = false;
        } else{
            $cesantias_si  = false;
            $cesantias_no  = true;
        }

        if($datos->resta_prima == 1){
            $prima_si  = true;
            $prima_no  = false;
        } else{
            $prima_si  = false;
            $prima_no  = true;
        }

        if($datos->resta_vacaciones == 1){
            $vacaciones_si  = true;
            $vacaciones_no  = false;
        } else{
            $vacaciones_si  = false;
            $vacaciones_no  = true;
        }


     /*** Definición de pestaña de ubicación del empleado ***/
    $formularios["PESTANA_CONTABLE"] = array(
        array(
            HTML::mostrarDato("resta_salario", $textos["SALARIO"], ""),
            HTML::marcaSeleccion("salario", $textos["SI"], 1, $salario_si, array("id" => "salario_si")),
            HTML::marcaSeleccion("salario", $textos["NO"], 0, $salario_no, array("id" => "salario_no"))
        ),
        array(
            HTML::mostrarDato("resta_auxilio_transporte", $textos["AUXILIO_TRANSPORTE"], ""),
            HTML::marcaSeleccion("auxilio", $textos["SI"], 1, $auxilio_si, array("id" => "auxilio_transporte_si")),
            HTML::marcaSeleccion("auxilio", $textos["NO"], 0, $auxilio_no, array("id" => "auxilio_transporte_no"))
        ),
        array(
            HTML::mostrarDato("resta_cesantias", $textos["CESANTIAS"], ""),
            HTML::marcaSeleccion("cesantias", $textos["SI"], 1, $cesantias_si, array("id" => "cesantias_si")),
            HTML::marcaSeleccion("cesantias", $textos["NO"], 0, $cesantias_no, array("id" => "cesantias_no"))
        ),
        array(
            HTML::mostrarDato("acumula_prima", $textos["PRIMA"], ""),
            HTML::marcaSeleccion("prima", $textos["SI"], 1, $prima_si, array("id" => "prima_si")),
            HTML::marcaSeleccion("prima", $textos["NO"], 0, $prima_no, array("id" => "prima_no"))
        ),
        array(
            HTML::mostrarDato("acumula_vaciones", $textos["RESTA_VACACIONES"], ""),
            HTML::marcaSeleccion("vacaciones", $textos["SI"], 1, $vacaciones_si, array("id" => "vacaciones_si")),
            HTML::marcaSeleccion("vacaciones", $textos["NO"], 0, $vacaciones_no, array("id" => "vacaciones_no"))
        ),
    );


    /*** Definición de botones ***/
    $botones = array(
        HTML::boton("botonAceptar", $textos["ACEPTAR"], "modificarItem('$url_id');", "aceptar")
    );

    $contenido = HTML::generarPestanas($formularios, $botones);
}


    /*** Enviar datos para la generación del formulario al script que originó la petición ***/
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

/*** Validar los datos provenientes del formulario ***/
} elseif (!empty($url_validar)) {

    /*** Validar codigo ***/
    if ($url_item == "codigo") {
        $existe = SQL::existeItem("transacciones_tiempo", "codigo", $url_valor,"codigo!='$url_id' AND codigo != 0");
        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_CODIGO"]);
        }
    }
    /*** Validar descripcion ***/
    else if ($url_item=="descripcion") {
        $existe = SQL::existeItem("transacciones_tiempo", "descripcion", $url_valor,"descripcion!='$url_id' AND codigo != 0");
        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_TRANSACCION"]);
        }
    }

/*** Adicionar los datos provenientes del formulario ***/
} elseif (!empty($forma_procesar)) {
    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];

    if (empty($forma_codigo)){
        $error   = true;
        $mensaje = $textos["ERROR_CODIGO"];

    } else if($forma_codigo == 0){
        $error   = true;
        $mensaje = $textos["ERROR_CODIGO_MAYOR"];

    } else if(empty($forma_nombre)){
        $error   = true;
        $mensaje = $textos["ERROR_NOMBRE"];

    } else if(empty($forma_descripcion)){
        $error   = true;
        $mensaje = $textos["ERROR_DESCRIPCION"];

    } else if(SQL::existeItem("transacciones_tiempo","codigo",$forma_codigo,"codigo !='$forma_id'")) {
        $error   = true;
        $mensaje = $textos["ERROR_EXISTE_CODIGO"];

    } else if(SQL::existeItem("transacciones_tiempo","descripcion",$forma_descripcion,"codigo !='$forma_id'")) {
        $error   = true;
        $mensaje = $textos["ERROR_EXISTE_TRANSACCION"];

    } else {

        if (empty($forma_salario)){
            $forma_salario = '0';
        }
        if (empty($forma_auxilio)){
            $forma_auxilio = '0';
        }
        if (empty($forma_cesantias)){
            $forma_cesantias = '0';
        }
        if (empty($forma_prima)){
            $forma_prima = '0';
        }
        if (empty($forma_vacaciones)){
            $forma_vacaciones = '0';
        }
        if (empty($forma_tasa)){
            $forma_tasa = '0';
        }
        if (empty($forma_codigo_transaccion_contable)){
            $forma_codigo_transaccion_contable = '0';
        }
        if (empty($forma_extras)){
            $forma_extras = '0';
        }
        if (empty($forma_dividendo)){
            $forma_dividendo = '0';
        }
        if (empty($forma_divisor)){
            $forma_divisor = '0';
        }

        $datos = array(
            "codigo"                             => $forma_codigo,
            "nombre"                             => $forma_nombre,
            "descripcion"                        => $forma_descripcion,
            "codigo_concepto_transaccion_tiempo" => $forma_codigo_concepto_transaccion_tiempo,
            "resta_salario"                      => $forma_salario,
            "resta_auxilio_transporte"           => $forma_auxilio,
            "resta_cesantias"                    => $forma_cesantias,
            "resta_prima"                        => $forma_prima,
            "resta_vacaciones"                   => $forma_vacaciones,
            "tasa"                               => $forma_tasa,
            "codigo_transaccion_contable"        => $forma_codigo_transaccion_contable,
            "extras_empleado"                    => $forma_extras,
            "dividendo"                          => $forma_dividendo,
            "divisor"                            => $forma_divisor
        );

        $consulta = SQL::modificar("transacciones_tiempo", $datos, "codigo = '$forma_id'");

        if(!$consulta){
            $error   = true;
            $mensaje = $textos["ERROR_MODIFICAR_ITEM"];

        }
    }
    /*** Enviar datos con la respuesta del proceso al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>


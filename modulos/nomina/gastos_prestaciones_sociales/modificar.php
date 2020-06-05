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

// Generar el formulario para la captura de datos
if (!empty($url_generar)) {

     // Verificar que se haya enviado el ID del elemento a modificar
    if (empty($url_id)) {
        $error     = $textos["ERROR_MODIFICAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {
        $error  = "";
        $titulo = $componente->nombre;

        $vistaConsulta = "gastos_prestaciones_sociales";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo = '$url_id'");
        $datos         = SQL::filaEnObjeto($consulta);

        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::campoTextoCorto("*codigo", $textos["CODIGO"], 7, 5, $datos->codigo, array("title" => $textos["AYUDA_CODIGO"], "onblur" => "validarItem(this);","onKeyPress" => "return campoEntero(event)"))
            ),
            array(
                HTML::campoTextoCorto("*descripcion", $textos["DESCRIPCION"], 30, 255, $datos->descripcion, array("title" => $textos["AYUDA_DESCRIPCION"], "onblur" => "validarItem(this);"))
            )
        );
        $formularios["PESTANA_CESANTIAS"] = array(
            array(
                HTML::listaSeleccionSimple("*cesantia_pago_prestacion", $textos["CESANTIA_PAGO_PRESTACION"], HTML::generarDatosLista("transacciones_contables_empleado", "codigo", "descripcion","codigo != 0 AND codigo_concepto_transaccion_contable = '12'"),$datos->cesantia_pago_prestacion,array("title" => $textos["AYUDA_TRANSACCION"]))
            ),
            array(
                HTML::listaSeleccionSimple("*cesantia_pago_gasto", $textos["CESANTIA_PAGO_GASTO"], HTML::generarDatosLista("transacciones_contables_empleado", "codigo", "descripcion","codigo != 0 AND codigo_concepto_transaccion_contable = '13'"),$datos->cesantia_pago_gasto,array("title" => $textos["AYUDA_TRANSACCION"]))
            ),
            array(
                HTML::listaSeleccionSimple("*cesantia_traslado_fondo", $textos["CESANTIA_TRASLADO_FONDO"], HTML::generarDatosLista("transacciones_contables_empleado", "codigo", "descripcion","codigo != 0 AND codigo_concepto_transaccion_contable = '14'"),$datos->cesantia_traslado_fondo,array("title" => $textos["AYUDA_TRANSACCION"]))
            ),
            array(
                HTML::listaSeleccionSimple("*cesantia_causacion_prestacion", $textos["CESANTIA_CAUSACION_PRESTACION"], HTML::generarDatosLista("transacciones_contables_empleado", "codigo", "descripcion","codigo != 0 AND codigo_concepto_transaccion_contable = '15'"),$datos->cesantia_causacion_prestacion,array("title" => $textos["AYUDA_TRANSACCION"]))
            ),
            array(
                HTML::listaSeleccionSimple("*cesantia_causacion_gasto", $textos["CESANTIA_CAUSACION_GASTO"], HTML::generarDatosLista("transacciones_contables_empleado", "codigo", "descripcion","codigo != 0 AND codigo_concepto_transaccion_contable = '16'"),$datos->cesantia_causacion_gasto,array("title" => $textos["AYUDA_TRANSACCION"]))
            )
        );
        $formularios["PESTANA_INTERESES"] = array(
            array(
                HTML::listaSeleccionSimple("*intereses_pago_prestacion", $textos["INTERESES_PAGO_PRESTACION"], HTML::generarDatosLista("transacciones_contables_empleado", "codigo", "descripcion","codigo != 0 AND codigo_concepto_transaccion_contable = '17'"),$datos->intereses_pago_prestacion,array("title" => $textos["AYUDA_TRANSACCION"]))
            ),
            array(
                HTML::listaSeleccionSimple("*intereses_pago_gasto", $textos["INTERESES_PAGO_GASTO"], HTML::generarDatosLista("transacciones_contables_empleado", "codigo", "descripcion","codigo != 0 AND codigo_concepto_transaccion_contable = '18'"),$datos->intereses_pago_gasto,array("title" => $textos["AYUDA_TRANSACCION"]))
            ),
            array(
                HTML::listaSeleccionSimple("*intereses_causacion_prestacion", $textos["INTERESES_CAUSACION_PRESTACION"], HTML::generarDatosLista("transacciones_contables_empleado", "codigo", "descripcion","codigo != 0 AND codigo_concepto_transaccion_contable = '19'"),$datos->intereses_causacion_prestacion,array("title" => $textos["AYUDA_TRANSACCION"]))
            ),
            array(
                HTML::listaSeleccionSimple("*intereses_causacion_gasto", $textos["INTERESES_CAUSACION_GASTO"], HTML::generarDatosLista("transacciones_contables_empleado", "codigo", "descripcion","codigo != 0 AND codigo_concepto_transaccion_contable = '20'"),$datos->intereses_causacion_gasto,array("title" => $textos["AYUDA_TRANSACCION"]))
            )
        );
        $formularios["PESTANA_PRIMAS"] = array(
            array(
                HTML::listaSeleccionSimple("*prima_pago_prestacion", $textos["PRIMA_PAGO_PRESTACION"], HTML::generarDatosLista("transacciones_contables_empleado", "codigo", "descripcion","codigo != 0 AND codigo_concepto_transaccion_contable = '21'"),$datos->prima_pago_prestacion,array("title" => $textos["AYUDA_TRANSACCION"]))
            ),
            array(
                HTML::listaSeleccionSimple("*prima_pago_gasto", $textos["PRIMA_PAGO_GASTO"], HTML::generarDatosLista("transacciones_contables_empleado", "codigo", "descripcion","codigo != 0 AND codigo_concepto_transaccion_contable = '22'"),$datos->prima_pago_gasto,array("title" => $textos["AYUDA_TRANSACCION"]))
            ),
            array(
                HTML::listaSeleccionSimple("*prima_causacion_prestacion", $textos["PRIMA_CAUSACION_PRESTACION"], HTML::generarDatosLista("transacciones_contables_empleado", "codigo", "descripcion","codigo != 0 AND codigo_concepto_transaccion_contable = '23'"),$datos->prima_causacion_prestacion,array("title" => $textos["AYUDA_TRANSACCION"]))
            ),
            array(
                HTML::listaSeleccionSimple("*prima_causacion_gasto", $textos["PRIMA_CAUSACION_GASTO"], HTML::generarDatosLista("transacciones_contables_empleado", "codigo", "descripcion","codigo != 0 AND codigo_concepto_transaccion_contable = '24'"),$datos->prima_causacion_gasto,array("title" => $textos["AYUDA_TRANSACCION"]))
            )
        );
        $formularios["PESTANA_VACACIONES"] = array(
            array(
                HTML::listaSeleccionSimple("*vacacion_pago_prestacion_disfrute", $textos["VACACION_PAGO_PRESTACION_DISFRUTE"], HTML::generarDatosLista("transacciones_contables_empleado", "codigo", "descripcion","codigo != 0 AND codigo_concepto_transaccion_contable = '25'"),$datos->vacacion_pago_prestacion_disfrute,array("title" => $textos["AYUDA_TRANSACCION"]))
            ),
            array(
                HTML::listaSeleccionSimple("*vacacion_pago_gasto_disfrute", $textos["VACACION_PAGO_GASTO_DISFRUTE"], HTML::generarDatosLista("transacciones_contables_empleado", "codigo", "descripcion","codigo != 0 AND codigo_concepto_transaccion_contable = '26'"),$datos->vacacion_pago_gasto_disfrute,array("title" => $textos["AYUDA_TRANSACCION"]))
            ),
            array(
                HTML::listaSeleccionSimple("*vacacion_pago_prestacion_liquidacion", $textos["VACACION_PAGO_PRESTACION_LIQUIDACION"], HTML::generarDatosLista("transacciones_contables_empleado", "codigo", "descripcion","codigo != 0 AND codigo_concepto_transaccion_contable = '25'"),$datos->vacacion_pago_prestacion_liquidacion,array("title" => $textos["AYUDA_TRANSACCION"]))
            ),
            array(
                HTML::listaSeleccionSimple("*vacacion_pago_gasto_liquidacion", $textos["VACACION_PAGO_GASTO_LIQUIDACION"], HTML::generarDatosLista("transacciones_contables_empleado", "codigo", "descripcion","codigo != 0 AND codigo_concepto_transaccion_contable = '26'"),$datos->vacacion_pago_gasto_liquidacion,array("title" => $textos["AYUDA_TRANSACCION"]))
            ),
            array(
                HTML::listaSeleccionSimple("*vacacion_causacion_prestacion", $textos["VACACION_CAUSACION_PRESTACION"], HTML::generarDatosLista("transacciones_contables_empleado", "codigo", "descripcion","codigo != 0 AND codigo_concepto_transaccion_contable = '27'"),$datos->vacacion_causacion_prestacion,array("title" => $textos["AYUDA_TRANSACCION"]))
            ),
            array(
                HTML::listaSeleccionSimple("*vacacion_causacion_gasto", $textos["VACACION_CAUSACION_GASTO"], HTML::generarDatosLista("transacciones_contables_empleado", "codigo", "descripcion","codigo != 0 AND codigo_concepto_transaccion_contable = '28'"),$datos->vacacion_causacion_gasto,array("title" => $textos["AYUDA_TRANSACCION"]))
            )
        );

        // Definicion de botones
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "modificarItem('$url_id');", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones);

    }

    // Enviar datos para la generacion del formulario al script que origino la peticion
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

// Validar los datos provenientes del formulario
} elseif (!empty($url_validar)) {

    if ($url_item == "codigo") {
        $existe = SQL::existeItem("gastos_prestaciones_sociales", "codigo", $url_valor,"codigo != 0 AND codigo != '".$url_id."'");
        if ($existe) {
        HTTP::enviarJSON($textos["ERROR_EXISTE_CODIGO"]);
        }
    }else if($url_item == "descripcion"){
        $existe_nombre = SQL::existeItem("gastos_prestaciones_sociales", "descripcion", $url_valor,"codigo != 0 AND codigo != '".$url_id."'");
        if ($existe_nombre) {
        HTTP::enviarJSON($textos["ERROR_EXISTE_DESCRIPCION"]);
        }
    }

// Modificar el elemento seleccionado
} elseif (!empty($forma_procesar)) {
    // Asumir por defecto que no hubo error
    $error   = false;
    $mensaje = $textos["ITEM_MODIFICADO"];

    $codigo = (int)$forma_codigo;

    if(empty($forma_codigo) || $codigo == 0){
        $error = true;
        $mensaje = $textos["ERROR_CODIGO_VACIO"];
    }elseif(empty($forma_descripcion) || $codigo == 0){
        $error = true;
        $mensaje = $textos["ERROR_DESCRIPCION_VACIO"];
    }elseif(SQL::existeItem("gastos_prestaciones_sociales", "codigo", $forma_codigo,"codigo != 0 AND codigo != '".$forma_id."'")){
        $error = true;
        $mensaje = $textos["ERROR_EXISTE_CODIGO"];
    }elseif(SQL::existeItem("gastos_prestaciones_sociales", "descripcion", $forma_descripcion,"codigo != 0 AND codigo != '".$forma_id."'")){
        $error = true;
        $mensaje = $textos["ERROR_EXISTE_DESCRIPCION"];
    }else {

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
        $modificar = SQL::modificar("gastos_prestaciones_sociales", $datos, "codigo = '".$forma_id."'");

        // Error de modificacion
        if (!$modificar) {
            $error   = true;
            $mensaje = $textos["ERROR_MODIFICAR_ITEM"];
        }
    }
    // Enviar datos con la respuesta del proceso al script que origino la peticion
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>


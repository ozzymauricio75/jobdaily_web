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

    // Verificar que se haya enviado el ID del elemento a consultar
    if (empty($url_id)) {
        $error     = $textos["ERROR_CONSULTAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {

        $vistaConsulta = "auxilio_transporte";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo = '".$url_id."'");
        $datos         = SQL::filaEnObjeto($consulta);
        $error         = "";
        $titulo        = $componente->nombre;

        // Definicion de pestana personal
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::campoTextoCorto("*codigo", $textos["FECHA"], 5, 3, $datos->codigo, array("title" => $textos["AYUDA_CODIGO"], "onKeyPress" => "return campoEntero(event)"))
            ),
            array(
                HTML::campoTextoCorto("*fecha", $textos["FECHA"], 10, 10, $datos->fecha, array("title" => $textos["AYUDA_FECHA"], "class" => "selectorFecha"))
            ),
            array(
                HTML::campoTextoCorto("*valor", $textos["VALOR"], 10, 10, $datos->valor, array("title" => $textos["AYUDA_VALOR"], "onKeypress" => "return campoDecimal(event)"))
            )
        );

        // Definicion de botones
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "modificarItem('".$url_id."');", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones);
    }

    // Enviar datos para la generacion del formulario al script que origino la peticion
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

// Adicionar los datos provenientes del formulario
} elseif (!empty($forma_procesar)){
    // Asumir por defecto que no hubo error
    $error   = false;
    $mensaje = $textos["ITEM_MODIFICADO"];

    $codigo = (int)$forma_codigo;
    $valor  = (int)$forma_valor;

    if(empty($forma_codigo) || $codigo == 0){
        $error   = true;
        $mensaje = $textos["ERROR_CODIGO"];
    }else if (empty($forma_fecha)){
        $error   = true;
        $mensaje = $textos["ERROR_FECHA"];
    }else if(empty($forma_valor) || $valor == 0){
        $error   = true;
        $mensaje = $textos["ERROR_VALOR"];
    } else if(SQL::obtenerValor("auxilio_transporte","codigo","codigo='".$forma_codigo."' AND codigo != '".$forma_id."'")){
        $error   = true;
        $mensaje = $textos["ERROR_EXISTE_CODIGO"];
    }else if (SQL::obtenerValor("auxilio_transporte","fecha","fecha='".$forma_fecha."' AND codigo != '".$forma_id."'")){
        $error   = true;
        $mensaje = $textos["ERROR_EXISTE_FECHA"];
    } else {

        $datos = array (
            "codigo" => $forma_codigo,
            "fecha"  => $forma_fecha,
            "valor"  => $forma_valor
        );
        $modificar = SQL::modificar("auxilio_transporte", $datos, "codigo='".$forma_id."'");

        // Error de insercón
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

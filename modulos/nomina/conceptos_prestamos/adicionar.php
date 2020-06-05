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
    $error  = "";
    $titulo = $componente->nombre;

    $consecutivo = (int)SQL::obtenerValor("conceptos_prestamos","max(codigo)","");
    if($consecutivo){
        $consecutivo++;
    }else{
        $consecutivo = 1;
    }

    // Definicion de pestana personal
    $formularios["PESTANA_GENERAL"] = array(
        array(
            HTML::campoTextoCorto("*codigo", $textos["CODIGO_CONCEPTO"], 5, 4, $consecutivo, array("title" => $textos["AYUDA_CONCEPTO"], "onblur" => "validarItem(this);","onKeyPress" => "return campoEntero(event)"))
        ),
        array(
            HTML::campoTextoCorto("*descripcion", $textos["DESCRIPCION_CONCEPTO"], 25, 255, "", array("title" => $textos["AYUDA_NOMBRE_CONCEPTO"], "onblur" => "validarItem(this);"))
        )
    );

    // Definicion de botones
    $botones = array(
        HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar")
    );

    $contenido = HTML::generarPestanas($formularios, $botones);

    // Enviar datos para la generacion del formulario al script que origino la peticion
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

// Validar los datos provenientes del formulario
} elseif (!empty($url_validar)) {
    // Validar codigo y nombre del concepto del prestamo
    if($url_item == "codigo"){
        $existe = SQL::existeItem("conceptos_prestamos", "codigo", $url_valor,"codigo != '0'");

        if ($existe) {
        HTTP::enviarJSON($textos["ERROR_EXISTE_CODIGO"]);
        }
    }
    if ($url_item == "descripcion") {
        $existe = SQL::existeItem("conceptos_prestamos", "descripcion", $url_valor,"codigo != '0'");

        if ($existe) {
        HTTP::enviarJSON($textos["ERROR_EXISTE_NOMBRE_CONCEPTO"]);
        }
    }
// Adicionar los datos provenientes del formulario
} elseif (!empty($forma_procesar)) {
    // Asumir por defecto que no hubo error
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];

    if (empty($forma_codigo) || (empty($forma_descripcion))){
        $error = true;
        $mensaje = $textos["ERROR_DATOS_INCOMPLETOS"];
    }elseif($existe = SQL::existeItem("conceptos_prestamos", "codigo", $forma_codigo,"codigo != '0'")){
        $error = true;
        $mensaje = $textos["ERROR_EXISTE_CODIGO"];
    }elseif($existe = SQL::existeItem("conceptos_prestamos", "descripcion", $forma_descripcion,"codigo != '0'")){
        $error = true;
        $mensaje = $textos["ERROR_EXISTE_NOMBRE_CONCEPTO"];
    }else{
        // Insertar datos
        $datos = array (
            "codigo"      => $forma_codigo,
            "descripcion" => $forma_descripcion
        );
        $insertar = SQL::insertar("conceptos_prestamos", $datos);

        // Error de insercion
        if (!$insertar) {
            $error   = true;
            $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
        }
    }
    // Enviar datos con la respuesta del proceso al script que origino la peticion
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

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

        $vistaConsulta = "conceptos_prestamos";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo = '".$url_id."'");
        $datos         = SQL::filaEnObjeto($consulta);

        // Definicion de pestana personal
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::campoTextoCorto("*codigo", $textos["CODIGO_CONCEPTO"], 5, 4, $datos->codigo, array("title" => $textos["AYUDA_CONCEPTO"], "onblur" => "validarItem(this);"))
            ),
            array(
                HTML::campoTextoCorto("*descripcion", $textos["DESCRIPCION_CONCEPTO"], 25, 255, $datos->descripcion, array("title" => $textos["AYUDA_NOMBRE_CONCEPTO"], "onblur" => "validarItem(this);"))
            )
        );
        // Definicion de botones
        $botones = array(HTML::boton("botonAceptar", $textos["ACEPTAR"], "modificarItem('".$url_id."');", "aceptar"));

        $contenido = HTML::generarPestanas($formularios, $botones);
    }

    // Enviar datos para la generacion del formulario al script que origino la peticion
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

// Validar los datos provenientes del formulario
} elseif (!empty($url_validar)) {
    // Validar numero de documento
    if($url_item == "codigo"){
        $existe = SQL::existeItem("conceptos_prestamos", "codigo", $url_valor, "codigo != '".$url_id."' AND codigo != '0' ");
        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_CODIGO"]);
        }
    }
    if ($url_item == "descripcion") {
        $existe = SQL::existeItem("conceptos_prestamos", "descripcion", $url_valor, "codigo != '".$url_id."' AND codigo!='0'");
        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_NOMBRE_CONCEPTO"]);
        }
    }
// Modificar el elemento seleccionado
} elseif (!empty($forma_procesar)) {
    // Asumir por defecto que no hubo error
    $error   = false;
    $mensaje = $textos["ITEM_MODIFICADO"];

    if(empty($forma_codigo) || (empty($forma_descripcion))){
        $error = true;
        $mensaje = $textos["ERROR_DATOS_INCOMPLETOS"];
    }elseif($existe = SQL::existeItem("conceptos_prestamos", "codigo", $forma_codigo,"codigo != '".$forma_id."' AND codigo != '0'")){
        $error = true;
        $mensaje = $textos["ERROR_EXISTE_CODIGO"];
    }elseif($existe = SQL::existeItem("conceptos_prestamos", "descripcion", $forma_descripcion,"codigo != '".$forma_id."' AND codigo != '0'")){
        $error = true;
        $mensaje = $textos["ERROR_EXISTE_NOMBRE_CONCEPTO"];
    } else {
        // Insertar datos
        $datos = array (
             "codigo"      => $forma_codigo,
             "descripcion" => $forma_descripcion
        );
        $modificar = SQL::modificar("conceptos_prestamos", $datos, "codigo = '".$forma_id."'");

        // Error de modificacion
        if (!$modificar) {
            $error   = true;
            $mensaje = $textos["ERROR_MODIFICAR_ITEM"];
        }
    }
    // Enviar datos con la respuesta del proceso al script que origino la peticion ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>


<?php

/**
*
* Copyright (C) 2020 Jobdaily
*
* Este archivo es parte de:
* Jobdaily :: Software empresarial a la medida
*
* Este programa es software libre: usted puede redistribuirlo y/o
* modificarlo  bajo los tÃ©rminos de la Licencia Pública General GNU
* publicada por la Fundación para el Software Libre, ya sea la versión 3
* de la Licencia, o (a su elección) cualquier versión posterior.
*
* Este programa se distribuye con la esperanza de que sea útil, pero
* SIN GARANTIA ALGUNA; ni siquiera la garantía implícita MERCANTIL o
* de APTITUD PARA UN PROPOSITO DETERMINADO. Consulte los detalles de
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
        echo SQL::datosAutoCompletar("seleccion_proveedores", $url_q);
    }
    if (($url_item) == "selector2") {
        echo SQL::datosAutoCompletar("seleccion_municipios", $url_q);
    }
    exit;
}


/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {

    /*** Verificar que se haya enviado el ID del elemento a modificar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_MODIFICAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {
        $vistaConsulta = "agenda";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "id = '$url_id'");
        $datos         = SQL::filaEnObjeto($consulta);
        $error         = "";
        $titulo        = $componente->nombre;

        $tiempo = explode(":", $datos->hora_inicio);
        $hora = $tiempo[0];
        $minuto = $tiempo[1];

        $horas     = Arreglo::generarListaNumerica(8, 18, 1, true);
        $minutos   = Arreglo::generarListaNumerica(0, 55, 5, true);
        $duracionH = Arreglo::generarListaNumerica(0, 4);
        $duracionM = Arreglo::generarListaNumerica(0, 50, 10, true);
        

        /*** Definición de pestañas general ***/
         $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::listaSeleccionSimple("hora", $textos["HORA"], $horas, $hora, array("title" => $textos["AYUDA_HORA_INICIO"])).
                HTML::listaSeleccionSimple("*minutos", "", $minutos, $minuto, array("title" => $textos["AYUDA_HORA_INICIO"])),
                 HTML::listaSeleccionSimple("duracionH", $textos["DURACION"], $duracionH, "", array("title" => $textos["AYUDA_DURACION"], "onchange" => "validarDuracion(this)")).":".
                HTML::listaSeleccionSimple("*duracionM", "", $duracionM, "10", array("title" => $textos["AYUDA_DURACION"], "onchange" => "validarDuracion(this)"))
            
             ),
            array(
                HTML::campoTextoCorto("*titulo", $textos["TITULO"], 30, 50, $datos->titulo, array("title" => $textos["AYUDA_TITULO"], "onblur" => "validarEvento(this);"))
            ),
            array(
                HTML::campoTextoCorto("descripcion", $textos["DESCRIPCION"], 30, 50, $datos->descripcion, array("title" => $textos["AYUDA_DESCRIPCION"], "onblur" => "validarEvento(this);"))
            )
        );

        /*** Definición de botones ***/
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "modificarEvento('$url_id');", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones);
    }

    /*** Enviar datos para la generación del formulario al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

/*** Validar los datos provenientes del formulario ***/
} elseif (!empty($forma_procesar)) {


	$hora_inicio = $forma_hora.":".$forma_minutos.":00";
	$duracion = ($forma_duracionH * 60) + $forma_duracionM;

    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_MODIFICADO"];
    
    if (empty($forma_titulo)) {
        $error = true;
        $mensaje = $textos["ERROR_DATOS_INCOMPLETOS"];
    } else {

        $datos = array(
            "codigo_usuario" => $sesion_id_usuario,
            "fecha"			 => $sesion_fechaActual,//date("Y-m-d"),
            "duracion"		 => $duracion,
	        "hora_inicio"	 => $hora_inicio,
	        "titulo"		 => $forma_titulo,
	        "descripcion"	 => $forma_descripcion,
        );

        $consulta = SQL::modificar("agenda", $datos, "id = '$forma_id'");

        if ($consulta) {
            $error   = false;
            $mensaje = $textos["ITEM_MODIFICADO"];
        } else {
            $error   = true;
            $mensaje = $textos["ERROR_MODIFICAR_ITEM"];
        }
    }

    /*** Enviar datos con la respuesta del proceso al script que originï¿½ la peticiï¿½n ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

<?php

/**
*
* Copyright (C) 2020 Jobdaily
*
* Este archivo es parte de:
* Jobdaily :: Software empresarial a la medida
*
* Este programa es software libre: usted puede redistribuirlo y/o
* modificarlo  bajo los términos de la Licencia Pública General GNU
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

/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {
    $error     = "";
    $titulo    = $componente->nombre;
    
    $horas     = Arreglo::generarListaNumerica(8, 18, 1, true);
    $minutos   = Arreglo::generarListaNumerica(0, 55, 5, true);
    $duracionH = Arreglo::generarListaNumerica(0, 4);
    $duracionM = Arreglo::generarListaNumerica(0, 50, 10, true);
    
    /*** Definición de pestañas general ***/
    $formularios["PESTANA_GENERAL"] = array(
        array(
            HTML::listaSeleccionSimple("hora", $textos["HORA"], $horas, "", array("title" => $textos["AYUDA_HORA_INICIO"])).":".
            HTML::listaSeleccionSimple("*minutos", "", $minutos, "", array("title" => $textos["AYUDA_HORA_INICIO"])),
            HTML::listaSeleccionSimple("duracionH", $textos["DURACION"], $duracionH, "", array("title" => $textos["AYUDA_DURACION"], "onchange" => "validarDuracion(this)")).":".
            HTML::listaSeleccionSimple("*duracionM", "", $duracionM, "10", array("title" => $textos["AYUDA_DURACION"], "onchange" => "validarDuracion(this)"))
            
        ),
        array(
            HTML::campoTextoCorto("*titulo", $textos["TITULO"], 30, 50, "", array("title" => $textos["AYUDA_TITULO"], "onblur" => "validarEvento(this);"))
        ),
        array(
            HTML::campoTextoCorto("descripcion", $textos["DESCRIPCION"], 30, 50, "", array("title" => $textos["AYUDA_DESCRIPCION"], "onblur" => "validarEvento(this);"))
        )
    );

    /*** Definición de botones ***/
    $botones = array(
        HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarEvento();", "aceptar")
    );

    $contenido = HTML::generarPestanas($formularios, $botones);

    /*** Enviar datos para la generación del formulario al script que originó la petición ***/
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
    $mensaje = $textos["ITEM_ADICIONADO"];
    
    if (empty($forma_titulo)) {
        $error = true;
        $mensaje = $textos["ERROR_DATOS_INCOMPLETOS"];
    } else {

        $datos = array(
            "codigo_usuario" => $sesion_id_usuario,
            "fecha"			 => $forma_fecha,//*/$sesion_fechaActual,//date("Y-m-d"),
            "duracion"		 => $duracion,
	        "hora_inicio"	 => $hora_inicio,
	        "titulo"		 => $forma_titulo,
	        "descripcion"	 => $forma_descripcion,
        );

        $insertar = SQL::insertar("agenda", $datos);
        
        /*** Error de inserción ***/
        if (!$insertar) {
            $error   = true;
            $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
        }
    }

    /*** Enviar datos con la respuesta del proceso al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    //$respuesta[2] = $sesion_fechaActual;
    HTTP::enviarJSON($respuesta);
}
?>

<?php

/**
*
* Copyright (C) 2020 Jobdaily
*
* Este archivo es parte de:
* Jobdaily :: Software empresarial a la medida
*
* Este programa es software libre: usted puede redistribuirlo y/o
* modificarlo  bajo los t�rminos de la Licencia P�blica General GNU
* publicada por la Fundaci�n para el Software Libre, ya sea la versi�n 3
* de la Licencia, o (a su elecci�n) cualquier versi�n posterior.
*
* Este programa se distribuye con la esperanza de que sea �til, pero
* SIN GARANTIA ALGUNA; ni siquiera la garant�a impl�cita MERCANTIL o
* de APTITUD PARA UN PROPOSITO DETERMINADO. Consulte los detalles de
* la Licencia P�blica General GNU para obtener una informaci�n m�s
* detallada.
*
* Deber�a haber recibido una copia de la Licencia P�blica General GNU
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
    
    /*** Definici�n de pesta�as general ***/
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

    /*** Definici�n de botones ***/
    $botones = array(
        HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarEvento();", "aceptar")
    );

    $contenido = HTML::generarPestanas($formularios, $botones);

    /*** Enviar datos para la generaci�n del formulario al script que origin� la petici�n ***/
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
        
        /*** Error de inserci�n ***/
        if (!$insertar) {
            $error   = true;
            $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
        }
    }

    /*** Enviar datos con la respuesta del proceso al script que origin� la petici�n ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    //$respuesta[2] = $sesion_fechaActual;
    HTTP::enviarJSON($respuesta);
}
?>

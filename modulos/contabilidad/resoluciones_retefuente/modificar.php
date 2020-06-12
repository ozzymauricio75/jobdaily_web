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
* modificarlo  bajo los t�rminos de la Licencia P�blica General GNU
* publicada por la Fundaci�n para el Software Libre, ya sea la versi�n 3
* de la Licencia, o (a su elecci�n) cualquier versi�n posterior.
*
* Este programa se distribuye con la esperanza de que sea �til, pero
* SIN GARANT�A ALGUNA; ni siquiera la garant�a impl�cita MERCANTIL o
* de APTITUD PARA UN PROP�SITO DETERMINADO. Consulte los detalles de
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

    /*** Verificar que se haya enviado el ID del elemento a modificar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_MODIFICAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {
        $error         = "";
        $titulo        = $componente->nombre;
        
        $vistaConsulta = "resoluciones_retefuente";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "numero_retefuente = '$url_id'");
        $datos         = SQL::filaEnObjeto($consulta);

        /*** Definici�n de pesta�as general ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::campoTextoCorto("*numero_retefuente", $textos["NUMERO"], 15, 20, $datos->numero_retefuente, array("title" => $textos["AYUDA_NUMERO"],"onBlur" => "validarItem(this);"))
            ),
            array(
                HTML::campoTextoCorto("*fecha", $textos["FECHA"], 10, 10, $datos->fecha, array("class" => "fechaNuevas", "title" => $textos["AYUDA_FECHA"], "onBlur" => "validarItem(this);"))
            ),
            array(
                HTML::campoTextoCorto("*descripcion", $textos["DESCRIPCION"], 30, 255, $datos->descripcion, array("title" => $textos["AYUDA_DESCRIPCION"]))
            )
        );

        /*** Definici�n de botones ***/
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "modificarItem('$url_id');", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones);
    }

    /*** Enviar datos para la generaci�n del formulario al script que origin� la petici�n ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

} elseif (!empty($url_validar)) {

    /*** Validar numero ***/
     if ($url_item == "numero_retefuente") {
        $existe = SQL::existeItem("resoluciones_retefuente", "numero_retefuente", $url_valor,"numero_retefuente !='' AND numero_retefuente !='$url_id'");

        if ($existe) {
           HTTP::enviarJSON($textos["ERROR_EXISTE_NUMERO"]);
        }
    }

/*** Modificar el elemento seleccionado ***/
} elseif (!empty($forma_procesar)) {
    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_MODIFICADO"];
    
	if(empty($forma_descripcion)){
		$error   = true;
		$mensaje = $textos["DESCRIPCION_VACIO"];
	}elseif(empty($forma_numero_retefuente)){
		$error   = true;
		$mensaje = $textos["NUMERO_VACIO"];
	}elseif(empty($forma_fecha)){
        $error   = true;
        $mensaje = $textos["FECHA_VACIO"];
    }elseif(SQL::existeItem("resoluciones_retefuente", "numero_retefuente", $forma_numero_retefuente,"numero_retefuente !='' AND numero_retefuente !='$forma_id'")){
	    $error   = true;
        $mensaje = $textos["ERROR_EXISTE_NUMERO"];
    } else {
		/*** Insertar datos ***/
        $datos = array(
            "numero_retefuente"      => $forma_numero_retefuente,
            "fecha"       => $forma_fecha,
            "descripcion" => $forma_descripcion
        );
        $consulta = SQL::modificar("resoluciones_retefuente", $datos, "numero_retefuente = '$forma_id'");
		
		/*** Error inserci�n ***/
        if ($consulta) {
            $error   = false;
            $mensaje = $textos["ITEM_MODIFICADO"];
        } else {
            $error   = true;
            $mensaje = $textos["ERROR_MODIFICAR_ITEM"];
        }
    }
    /*** Enviar datos con la respuesta del proceso al script que origin� la petici�n ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

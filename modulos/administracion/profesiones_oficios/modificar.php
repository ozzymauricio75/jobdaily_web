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
* de APTITUD PARA UN PROP�ITO DETERMINADO. Consulte los detalles de
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
        $vistaConsulta = "profesiones_oficios";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo_dane = '$url_id'");
        $datos         = SQL::filaEnObjeto($consulta);
        
        $error         = "";
        $titulo        = $componente->nombre;
        
        /*** Definición de pestañas general ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::campoTextoCorto("*codigo_dane", $textos["CODIGO_DANE"], 4, 4, $datos->codigo_dane, array("title" => $textos["AYUDA_CODIGO_DANE"], "onblur" => "validarItem(this);", "onKeyPress" => "return campoEntero(event)"))
            ),
            array(
                HTML::campoTextoCorto("*descripcion", $textos["DESCRIPCION"], 30, 255, $datos->descripcion, array("title" => $textos["AYUDA_DESCRIPCION"], "onblur" => "validarItem(this);"))
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

/*** Validar los datos provenientes del formulario ***/
} elseif (!empty($url_validar)) {

    /*** Validar codigo_DANE ***/
    if ($url_item == "codigo_dane") {
        $existe = SQL::existeItem("profesiones_oficios", "codigo_dane", $url_valor, "codigo_dane != '$url_id' AND codigo_dane !='0'");

        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_CODIGO_DANE"]);
        }
    }

    /*** Validar nombre ***/
    if ($url_item == "descripcion") {
        $existe = SQL::existeItem("profesiones_oficios", "descripcion", $url_valor, "codigo_dane != '$url_id' AND descripcion !=''");

        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_DESCRIPCION"]);
        }
    }

/*** Procesar los datos del formulario ***/
}  elseif (!empty($forma_procesar)) {
    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];

	if(empty($forma_codigo_dane)){
		$error = true;
        $mensaje = $textos["CODIGO_VACIO"];
	}elseif(empty($forma_descripcion)){
        $error = true;
        $mensaje = $textos["DESCRIPCION_VACIO"];
	}elseif($existe = SQL::existeItem("profesiones_oficios", "codigo_dane", $forma_codigo_dane,"codigo_dane !='$forma_id'")){
	    $error   = true;
        $mensaje = $textos["ERROR_EXISTE_CODIGO_DANE"]; 
    }elseif($existe = SQL::existeItem("profesiones_oficios", "descripcion", $forma_descripcion,"codigo_dane !='$forma_id'")){
	    $error   = true;
        $mensaje = $textos["ERROR_EXISTE_DESCRIPCION"]; 
    } else {
		/*** Insertar datos ***/
        $datos = array(
            "codigo_dane "=> $forma_codigo_dane,
            "descripcion" => $forma_descripcion
        );
        $consulta = SQL::modificar("profesiones_oficios", $datos, "codigo_dane = '$forma_id'");
		
		/*** Error insercion ***/
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

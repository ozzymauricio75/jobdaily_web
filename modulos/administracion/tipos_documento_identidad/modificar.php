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
        
        $vistaConsulta = "tipos_documento_identidad";
        $condicion     = "codigo = '$url_id'";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, $condicion);
        $datos         = SQL::filaEnObjeto($consulta);
        
        $tipo_persona = array(
            "1" => $textos["PERSONA_NATURAL"],
            "2" => $textos["PERSONA_JURIDICA"],
            "3" => $textos["CODIGO_INTERNO"],
            "4" => $textos["NATURAL_COMERCIANTE"]
        );
        
        /*** Definici�n de pesta�as ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::campoTextoCorto("codigo_dian", $textos["CODIGO_DIAN"], 3, 3, $datos->codigo_dian, array("title" => $textos["AYUDA_CODIGO_DIAN"],"onBlur" => "validarItem(this);"))
            ),
            array(
                HTML::campoTextoCorto("codigo_interno", $textos["CODIGO_INTERNO"], 3, 3, $datos->codigo, array("title" => $textos["AYUDA_CODIGO_INTERNO"],"onBlur" => "validarItem(this);")),
            ),
            array(
                HTML::campoTextoCorto("descripcion", $textos["DESCRIPCION"], 30, 255, $datos->descripcion, array("title" => $textos["AYUDA_DESCRIPCION"],"onBlur" => "validarItem(this);"))
            ),
            array(
                HTML::listaSeleccionSimple("*tipo_persona", $textos["TIPO_PERSONA"], $tipo_persona, $datos->tipo_persona, array("title" => $textos["AYUDA_TIPO_PERSONA"]))
            ),
        );

        /*** Definici�n de botones ***/
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "modificarItem('$url_id');", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones);
    }

    /*** Enviar datos para la generaci�n del formulario al script que origino la petici�n ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

/*** Validar los datos provenientes del formulario ***/
} elseif (!empty($url_validar)) {

    /*** Validar codigo_dian ***/
    if ($url_item == "codigo_dian") {
        $existe = SQL::existeItem("tipos_documento_identidad", "codigo_dian", $url_valor, "codigo != '$url_id'  AND codigo_dian != '0'");

        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_CODIGO_DIAN"]);
        }
    }else if ($url_item == "codigo_interno") {
        $existe = SQL::existeItem("tipos_documento_identidad", "codigo", $url_valor, "codigo != '$url_id'  AND codigo != '0'");

        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_CODIGO_INTERNO"]);
        }
    }else if($url_item == "descripcion") {
        $existe = SQL::existeItem("tipos_documento_identidad", "descripcion", $url_valor, "codigo_dian != '$url_id' AND descripcion !=''");

        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_DESCRIPCION"]);
        }
    }

/*** Modificar el elemento seleccionado ***/
} elseif (!empty($forma_procesar)) {
    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_MODIFICADO"];
    
	/*** Verificar campos requeridos ***/
    if(empty($forma_codigo_dian)){
		$error   = true;
        $mensaje = $textos["CODIGO_DIAN_VACIO"];
        
	}elseif(empty($forma_codigo_interno)){
		$error   = true;
        $mensaje = $textos["CODIGO_VACIO"];
        
	}elseif(empty($forma_tipo_persona)){
		$error   = true;
        $mensaje = $textos["TIPO_PERSONA_VACIO"];
        
	}elseif(empty($forma_descripcion)){
		$error   = true;
        $mensaje = $textos["DESCRIPCION_VACIO"];
        
    }elseif(SQL::existeItem("tipos_documento_identidad", "codigo_dian", $forma_codigo_dian,"codigo != $forma_id")){
	    $error   = true;
        $mensaje = $textos["ERROR_EXISTE_CODIGO_DIAN"];
        
    }elseif(SQL::existeItem("tipos_documento_identidad", "codigo", $forma_codigo_interno,"codigo != $forma_id")){
	    $error   = true;
        $mensaje = $textos["ERROR_EXISTE_CODIGO_INTERNO"];
        
    }elseif(SQL::existeItem("tipos_documento_identidad", "descripcion", $forma_descripcion,"codigo_dian != $forma_id")){
	    $error   = true;
        $mensaje = $textos["ERROR_EXISTE_DESCRIPCION"]; 
        
    }else {
		/*** Insertar datos ***/
        $datos = array(
            "codigo_dian"    => $forma_codigo_dian,
            "codigo"         => $forma_codigo_interno,
            "descripcion"    => $forma_descripcion,
            "tipo_persona"   => $forma_tipo_persona
        );
        $consulta = SQL::modificar("tipos_documento_identidad", $datos, "codigo = '$forma_id'");
		
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

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
* modificarlo  bajo los términos de la Licencia Pública General GNU
* publicada por la Fundación para el Software Libre, ya sea la versión 3
* de la Licencia, o (a su elección) cualquier versión posterior.
*
* Este programa se distribuye con la esperanza de que sea útil, pero
* SIN GARANTÍA ALGUNA; ni siquiera la garantía implícita MERCANTIL o
* de APTITUD PARA UN PROPÓSITO DETERMINADO. Consulte los detalles de
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
    $error  = "";
    $titulo = $componente->nombre;
    
    $tipo_persona = array(
        "1" => $textos["PERSONA_NATURAL"],
        "2" => $textos["PERSONA_JURIDICA"],
        "3" => $textos["CODIGO_INTERNO"],
        "4" => $textos["NATURAL_COMERCIANTE"]
    );
    
    /*** Definición de pestañas general ***/
    $formularios["PESTANA_GENERAL"] = array(
        array(
            HTML::campoTextoCorto("*codigo_dian", $textos["CODIGO_DIAN"], 3, 3, "", array("title" => $textos["AYUDA_CODIGO_DIAN"],"onBlur" => "validarItem(this);", "alt" => "Prueba", "onKeyPress" => "return campoEntero(event)"))
        ),
        array(
            HTML::campoTextoCorto("*codigo_interno", $textos["CODIGO_INTERNO"], 3, 3, "", array("title" => $textos["AYUDA_CODIGO_INTERNO"],"onBlur" => "validarItem(this);", "onKeyPress" => "return campoEntero(event)"))
        ),
        array(
            HTML::campoTextoCorto("*descripcion", $textos["DESCRIPCION"], 30, 255, "", array("title" => $textos["AYUDA_DESCRIPCION"],"onBlur" => "validarItem(this);"))
        ),
        array(
            HTML::listaSeleccionSimple("*tipo_persona", $textos["TIPO_PERSONA"], $tipo_persona, "",array("title" => $textos["AYUDA_TIPO_PERSONA"]))
        ),
    );

    /*** Definición de botones ***/
    $botones = array(
        HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar")
    );

    $contenido = HTML::generarPestanas($formularios, $botones);

    /*** Enviar datos para la generación del formulario al script que originó la petición ***/
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

/*** Validar los datos provenientes del formulario ***/
} elseif (!empty($url_validar)) {

    /*** Validar codigo DIAN***/
    if ($url_item == "codigo_dian") {
        $existe = SQL::existeItem("tipos_documento_identidad", "codigo_dian", $url_valor,"codigo_dian != '0'");

        if ($existe) {
           HTTP::enviarJSON($textos["ERROR_EXISTE_CODIGO_DIAN"]);
        }
    }

    /*** Validar codigo interno ***/
    if ($url_item == "codigo_interno") {
        $existe = SQL::existeItem("tipos_documento_identidad", "codigo", $url_valor,"codigo != '0'");

        if ($existe) {
           HTTP::enviarJSON($textos["ERROR_EXISTE_CODIGO_INTERNO"]);
        }
    }

    /*** Validar Descripcion ***/
    if ($url_item == "descripcion") {
        $existe = SQL::existeItem("tipos_documento_identidad", "descripcion", $url_valor,"descripcion !=''");

        if ($existe) {
           HTTP::enviarJSON($textos["ERROR_EXISTE_DESCRIPCION"]);
        }
    }

/*** Adicionar los datos provenientes del formulario ***/
} elseif (!empty($forma_procesar)) {
    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];
	
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
        
    }elseif(SQL::existeItem("tipos_documento_identidad", "codigo_dian", $forma_codigo_dian)){
	    $error   = true;
        $mensaje = $textos["ERROR_EXISTE_CODIGO_DIAN"];
        
    }elseif(SQL::existeItem("tipos_documento_identidad", "codigo", $forma_codigo_interno)){
	    $error   = true;
        $mensaje = $textos["ERROR_EXISTE_CODIGO_INTERNO"];
        
    }elseif(SQL::existeItem("tipos_documento_identidad", "descripcion", $forma_descripcion)){
	    $error   = true;
        $mensaje = $textos["ERROR_EXISTE_DESCRIPCION"];
        
    } else {
		/*** Insertar datos ***/
        $datos = array(
            "codigo_dian"    => $forma_codigo_dian,
            "codigo"         => $forma_codigo_interno,
            "descripcion"    => $forma_descripcion,
            "tipo_persona"   => $forma_tipo_persona
        );
        $insertar = SQL::insertar("tipos_documento_identidad", $datos);

        /*** Error de inserción ***/
        if (!$insertar) {
            $error   = true;
            $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
        }
    }
    /*** Enviar datos con la respuesta del proceso al script que originá la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

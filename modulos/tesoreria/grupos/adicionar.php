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

    $codigo = SQL::obtenerValor("grupos_tesoreria","MAX(codigo)","codigo>0");
    if ($codigo){
        $codigo++;
    } else {
        $codigo = 1;
    }
    /*** Definición de pestañas general ***/
    $formularios["PESTANA_GENERAL"] = array(
        array(
            HTML::campoTextoCorto("*codigo", $textos["CODIGO"], 3, 3, $codigo, array("title" => $textos["AYUDA_CODIGO"], "onblur" => "validarItem(this);", "onKeyPress" => "return campoEntero(event)"))
        ),
        array(
            HTML::campoTextoCorto("*nombre", $textos["NOMBRE"], 30, 50, "", array("title" => $textos["AYUDA_NOMBRE"], "onblur" => "validarItem(this);"))
        )
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

    /*** Validar nombre ***/
    if ($url_item == "nombre") {
        $existe = SQL::existeItem("grupos_tesoreria", "nombre", $url_valor,"nombre !=''");

        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_NOMBRE"]);
        }
    }

/*** Procesar los datos del formulario ***/
}  elseif (!empty($forma_procesar)) {
    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];
    
    /*** Validar campos requeridos ***/
    if(empty($forma_codigo)){
		$error = true;
        $mensaje = $textos["CODIGO_VACIO"];
        
	}elseif(empty($forma_nombre)){
        $error = true;
        $mensaje = $textos["NOMBRE_VACIO"];
    
    }elseif(SQL::existeItem("grupos_tesoreria", "codigo", $forma_codigo)){
	$error   = true;
        $mensaje = $textos["ERROR_EXISTE_CODIGO"];
        
    }elseif(SQL::existeItem("grupos_tesoreria", "nombre", $forma_nombre)){
	    $error   = true;
        $mensaje = $textos["ERROR_EXISTE_NOMBRE"];    
    
    }else {

        if (!isset($forma_interno)) {
            $forma_interno = "0";
        }
        /*** Insertar datos ***/
        $datos = array(
            "codigo"       => $forma_codigo,
            "nombre_grupo" => $forma_nombre
        );
        $insertar = SQL::insertar("grupos_tesoreria", $datos);
        
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
    HTTP::enviarJSON($respuesta);
}
?>

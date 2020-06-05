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
    $error  = "";
    $titulo = $componente->nombre;
    
    $codigo = SQL::obtenerValor("tipos_moneda","MAX(codigo)","codigo>0");
    if ($codigo){
        $codigo++;
    } else {
        $codigo = 1;
    }

    /*** Definici�n de pesta�as general ***/
    $formularios["PESTANA_GENERAL"] = array(
        array(
            HTML::campoTextoCorto("codigo", $textos["CODIGO"], 3, 3, $codigo, array("title" => $textos["AYUDA_CODIGO"],"onBlur" => "validarItem(this);", "onKeyPress" => "return campoEntero(event)"))
        ),
        array(
            HTML::campoTextoCorto("*codigo_dian", $textos["CODIGO_DIAN"], 3, 3, "", array("title" => $textos["AYUDA_CODIGO_DIAN"],"onBlur" => "validarItem(this);", "onKeyPress" => "return campoEntero(event)"))
        ),
        array(
            HTML::campoTextoCorto("*nombre", $textos["NOMBRE"], 30, 30, "", array("title" => $textos["AYUDA_NOMBRE"],"onBlur" => "validarItem(this);"))
        )
    );

    /*** Definici�n de botones ***/
    $botones = array(
        HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar")
    );

    $contenido = HTML::generarPestanas($formularios, $botones);

    /*** Enviar datos para la generación del formulario al script que origin� la petici�n ***/
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

/*** Validar los datos provenientes del formulario ***/
} elseif (!empty($url_validar)) {

     /*** Validar codigo_dian ***/
    if ($url_item == "codigo_dian") {
        $existe = SQL::existeItem("tipos_moneda", "codigo_dian", $url_valor,"codigo_dian !=''");

        if ($existe) {
           HTTP::enviarJSON($textos["ERROR_EXISTE_CODIGO_dian"]);
        }
    }
    /*** Validar codigo_interno ***/
    if ($url_item == "codigo") {
        $existe = SQL::existeItem("tipos_moneda", "codigo", $url_valor,"codigo >0");

        if ($existe) {
           HTTP::enviarJSON($textos["ERROR_EXISTE_CODIGO_INTERNO"]);
        }
    }
    /*** Validar descripci�n ***/
    if ($url_item == "nombre") {
        $existe = SQL::existeItem("tipos_moneda", "nombre", $url_valor,"nombre !=''");

        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_NOMBRE"]);
        }
    }

/*** Adicionar los datos provenientes del formulario ***/
} elseif (!empty($forma_procesar)) {
    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];

    if(empty($forma_codigo_dian)){
		$error   = true;
		$mensaje = $textos["CODIGO_VACIO"];
	}elseif(empty($forma_nombre)){
        $error   = true;
        $mensaje = $textos["NOMBRE_VACIO"];
     }elseif($existe = SQL::existeItem("tipos_moneda", "codigo_dian", $forma_codigo_dian,"codigo_dian >0")){
	    $error   = true;
        $mensaje = $textos["ERROR_EXISTE_CODIGO_DIAN"];
     }elseif($existe = SQL::existeItem("tipos_moneda", "codigo", $forma_codigo,"codigo >0")){
	    $error   = true;
        $mensaje = $textos["ERROR_EXISTE_CODIGO_INTERNO"];
    }elseif($existe = SQL::existeItem("tipos_moneda", "nombre", $forma_nombre,"nombre !=''")){
	    $error   = true;
        $mensaje = $textos["ERROR_EXISTE_NOMBRE"];
    }else {
		/*** Insertar datos ***/
        $datos = array(
            "codigo"      => $forma_codigo,
            "codigo_dian" => $forma_codigo_dian,
            "nombre"      => $forma_nombre
        );
        $insertar = SQL::insertar("tipos_moneda", $datos);

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
    HTTP::enviarJSON($respuesta);
}
?>

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

    /*** Definici�n de pesta�as general ***/
    $formularios["PESTANA_GENERAL"] = array(
        array(
            HTML::campoTextoCorto("*codigo", $textos["CODIGO_ANEXO"], 2, 2, "", array("title" => $textos["AYUDA_CODIGO_ANEXO"],"onBlur" => "validarItem(this);"))
        ),
        array(
            HTML::campoTextoCorto("*descripcion", $textos["DESCRIPCION"], 20, 255, "", array("title" => $textos["AYUDA_DESCRIPCION"],"onBlur" => "validarItem(this);"))
        )
    );

    /*** Definici�n de botones ***/
    $botones = array(
        HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar")
    );

    $contenido = HTML::generarPestanas($formularios, $botones);

    /*** Enviar datos para la generaci�n del formulario al script que origin� la petici�n ***/
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

/*** Validar los datos provenientes del formulario ***/
} elseif (!empty($url_validar)) {
    
    if(Cadena::contieneUTF8($url_valor)){
        $url_valor = utf8_decode($url_valor);
    }
    
    /*** Validar codigo_anexo ***/
    if ($url_item == "codigo_anexo") {
        $existe = SQL::existeItem("anexos_contables", "codigo_anexo", $url_valor,"codigo_anexo != ''");

        if ($existe) {
           HTTP::enviarJSON($textos["ERROR_EXISTE_CODIGO_ANEXO"]);
        }
    }

    /*** Validar codigo_interno ***/
    if ($url_item == "descripcion") {
        $existe = SQL::existeItem("anexos_contables", "descripcion", $url_valor,"descripcion != ''");

        if ($existe) {
           HTTP::enviarJSON($textos["ERROR_EXISTE_DESCRIPCION"]);
        }
    }

/*** Adicionar los datos provenientes del formulario ***/
} elseif (!empty($forma_procesar)) {
    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];

    /*** Validar campos requeridos ***/
    if(empty($forma_codigo)){
		$error   = true;
        $mensaje = $textos["CODIGO_VACIO"];
        
	}elseif(empty($forma_descripcion)){
        $error   = true;
        $mensaje = $textos["DESCRIPCION_VACIO"];
    
    }elseif($existe = SQL::existeItem("anexos_contables", "codigo", $forma_codigo, "codigo != ''")){
	    $error   = true;
        $mensaje = $textos["ERROR_EXISTE_CODIGO_ANEXO"];
        
    }elseif($existe = SQL::existeItem("anexos_contables", "descripcion", $forma_descripcion, "descripcion != ''")){
	    $error   = true;
        $mensaje = $textos["ERROR_EXISTE_DESCRIPCION"];
    
    } else {
		/*** Insertar datos ***/
        $datos = array(
            "codigo"      => $forma_codigo,
            "descripcion" => $forma_descripcion
        );
        $insertar = SQL::insertar("anexos_contables", $datos);

        /*** Error de inserci�n ***/
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

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
    $error  = "";
    $titulo = $componente->nombre;
    
    $codigo = SQL::obtenervalor("estado_mercancia","MAX(codigo)","codigo>0");
    if ($codigo){
        $codigo++;
    } else {
        $codigo=1;
    }

    /*** Definici�n de pesta�as general ***/
    $formularios["PESTANA_GENERAL"] = array(
        array(
            HTML::campoTextoCorto("*codigo", $textos["CODIGO"], 3, 3, $codigo, array("title" => $textos["AYUDA_CODIGO"],"onBlur" => "validarItem(this);","onkeypress"=>"return campoEntero(event);"))
        ),
        array(
            HTML::campoTextoCorto("*descripcion", $textos["DESCRIPCION"], 30, 255, "", array("title" => $textos["AYUDA_DESCRIPCION"],"onBlur" => "validarItem(this);"))
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

    if ($url_item == "codigo") {
        $existe = SQL::existeItem("estado_mercancia", "codigo", $url_valor);

        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_CODIGO"]);
        }
    }

    if ($url_item == "descripcion") {
        $existe = SQL::existeItem("estado_mercancia", "descripcion", $url_valor);

        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_DESCRIPCION"]);
        }
    }

/*** Adicionar los datos provenientes del formulario ***/
} elseif (!empty($forma_procesar)) {
    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];
    
    if (empty($forma_codigo)){
        $error   = true;
        $mensaje = $textos["CODIGO_VACIO"];
    
    } else if (empty($forma_descripcion)){
        $error   = true;
        $mensaje = $textos["DESCRIPCION_VACIO"];
    
    }elseif($existe = SQL::existeItem("estado_mercancia", "codigo", $forma_codigo)){
	    $error   = true;
        $mensaje = $textos["ERROR_EXISTE_CODIGO"]; 
    
    }elseif($existe = SQL::existeItem("estado_mercancia", "descripcion", $forma_descripcion)){
	    $error   = true;
        $mensaje = $textos["ERROR_EXISTE_DESCRIPCION"]; 
    
    }else {
        $datos = array(
            "codigo"      => $forma_codigo,
            "descripcion" => $forma_descripcion
        );

        $insertar = SQL::insertar("estado_mercancia", $datos);

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

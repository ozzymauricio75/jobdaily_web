<?php

/**
*
* Copyright (C) 2020 Jobdaily
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
* SIN GARANTIA ALGUNA; ni siquiera la garantía implícita MERCANTIL o
* de APTITUD PARA UN PROP�SITO DETERMINADO. Consulte los detalles de
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

    /*** Definición de pestañas general ***/
    $formularios["PESTANA_GENERAL"] = array(
        array(
            HTML::campoTextoCorto("*codigo_dian", $textos["CODIGO_dian"], 3, 3, "", array("title" => $textos["AYUDA_CODIGO_dian"],"onBlur" => "validarItem(this);")),
            HTML::campoTextoCorto("codigo_interno", $textos["CODIGO_INTERNO"], 3, 3, "", array("title" => $textos["AYUDA_CODIGO_INTERNO"],"onBlur" => "validarItem(this);"))
        ),
        array(
            HTML::campoTextoCorto("*nombre", $textos["NOMBRE"], 30, 30, "", array("title" => $textos["AYUDA_NOMBRE"],"onBlur" => "validarItem(this);"))
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

    /*** Validar codigo_dian ***/
    if ($url_item == "codigo_dian") {
        $existe = SQL::existeItem("tipos_moneda", "codigo_dian", $url_valor);

        if ($existe) {
           HTTP::enviarJSON($textos["ERROR_EXISTE_CODIGO_dian"]);
        }
    }
    
    /*** Validar codigo_interno ***/
    if ($url_item == "codigo_interno") {
        $existe = SQL::existeItem("tipos_moneda", "codigo_interno", $url_valor);

        if ($existe) {
           HTTP::enviarJSON($textos["ERROR_EXISTE_CODIGO_INTERNO"]);
        }
    }
    
    /*** Validar descripción ***/
    if ($url_item == "nombre") {
        $existe = SQL::existeItem("tipos_moneda", "nombre", $url_valor);

        if ($existe) {
           HTTP::enviarJSON($textos["ERROR_EXISTE_DESCRIPCION"]);
        }
    }


/*** Adicionar los datos provenientes del formulario ***/
} elseif (!empty($forma_procesar)) {
    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];

    if (empty($forma_codigo_dian)||
        empty($forma_nombre)){
        $error   = true;
        $mensaje = $textos["ERROR_DATOS_INCOMPLETOS"];
    }else {
        $datos = array(
            "codigo_dian"    => $forma_codigo_dian,
            "codigo_interno" => $forma_codigo_interno,
            "nombre"         => $forma_nombre
        );

        $insertar = SQL::insertar("tipos_moneda", $datos);

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

<?php

/**
*
* Copyright (C) 2020 Jobdaily
* Leonardo Silva Medina <flownormal.hotmail.com>
*
* Este archivo es parte de:
* Jobdaily:: Sofware empresarial a la medida
*
* Este programa es software libre: usted puede redistribuirlo y/o
* modificarlo  bajo los terminos de la Licencia Publica General GNU
* publicada por la Fundacion para el Software Libre, ya sea la version 3
* de la Licencia, o (a su eleccion) cualquier version posterior.
*
* Este programa se distribuye con la esperanza de que sea util, pero
* SIN GARANTIA ALGUNA; ni siquiera la garantia implicita MERCANTIL o
* de APTITUD PARA UN PROPOSITO DETERMINADO. Consulte los detalles de
* la Licencia Publica General GNU para obtener una informacion mas
* detallada.
*
* Deberia haber recibido una copia de la Licencia Publica General GNU
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
**/

/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {
    $error  = "";
    $titulo = $componente->nombre;
    $consecutivo = (int)SQL::obtenerValor("idiomas","max(codigo)","");
    if($consecutivo){
        $consecutivo++;
    }else{
        $consecutivo=1;
    }
    /*** Definición de pestaña personal ***/
    $formularios["PESTANA_GENERAL"] = array(
        array(
            HTML::campoTextoCorto("*codigo", $textos["CODIGO_IDIOMA"], 5, 4, $consecutivo, array("title" => $textos["AYUDA_IDIOMA"], "onblur" => "validarItem(this);","onKeyPress" => "return campoEntero(event)"))
        ),
        array(
            HTML::campoTextoCorto("*descripcion", $textos["DESCRIPCION_IDIOMA"], 25, 255, "", array("title" => $textos["AYUDA_NOMBRE_IDIOMA"], "onblur" => "validarItem(this);"))
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
    /*** Validar codigo y nombre del idioma ***/
    if (isset($url_valor)) {
        $existe = SQL::existeItem("idiomas", "codigo", $url_valor,"codigo != ''");
        $existe_nombre = SQL::existeItem("idiomas", "descripcion", $url_valor,"descripcion != ''");

        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_IDIOMA"]);
        }
        if ($existe_nombre) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_NOMBRE_IDIOMA"]);
        }
    }
 /*** Adicionar los datos provenientes del formulario ***/
} elseif (!empty($forma_procesar)) {
    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];

    if (empty($forma_codigo) || (empty($forma_descripcion))){
        $error = true;
        $mensaje = $textos["ERROR_DATOS_INCOMPLETOS"];

    }elseif(SQL::existeItem("idiomas", "codigo", $forma_codigo,"codigo != ''")){
        $error = true;
        $mensaje = $textos["ERROR_EXISTE_IDIOMA"];

    }elseif(SQL::existeItem("idiomas", "descripcion", $forma_descripcion,"descripcion != ''")){
        $error = true;
        $mensaje = $textos["ERROR_EXISTE_NOMBRE_IDIOMA"];

    }else{
        /*** Insertar datos ***/
        $datos = array (
            "codigo"  	  => $forma_codigo,
            "descripcion" => $forma_descripcion
        );
        $insertar = SQL::insertar("idiomas", $datos);

        /*** Error de insercón ***/
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

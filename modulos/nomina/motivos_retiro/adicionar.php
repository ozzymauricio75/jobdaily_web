<?php

/**
*
* Copyright (C) 2008  Sistemas de Apoyo Empresarial Ltda
* 
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

    $consecutivo = (int)SQL::obtenerValor("motivos_retiro","max(codigo)","");
    if($consecutivo){
        $consecutivo++;
    }else{
        $consecutivo=1;
    }

    /*** Definici�n de pesta�a personal ***/
    $formularios["PESTANA_GENERAL"] = array(
        array(
            HTML::campoTextoCorto("*codigo", $textos["CODIGO_MOTIVO_RETIRO"], 5, 4, $consecutivo, array("title" => $textos["AYUDA_MOTIVO_RETIRO"], "onblur" => "validarItem(this);","onKeypress" => "return campoEntero(event)"))
        ),
        array(
            HTML::campoTextoCorto("*descripcion", $textos["NOMBRE_MOTIVO_RETIRO"], 25, 50, "", array("title" => $textos["AYUDA_NOMBRE_MOTIVO_RETIRO"], "onblur" => "validarItem(this);"))
        ),
        array(
            HTML::mostrarDato("genera_indemnizacion", $textos["INDEMNIZACION"], "")
        ),
        array(
            HTML::marcaSeleccion("indemniza", $textos["INDEMNIZACION_NO"], 0,true),
            HTML::marcaSeleccion("indemniza", $textos["INDEMNIZACION_SI"], 1,false)
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

    /*** Validar numero de documento ***/   
    if ($url_item == "codigo" && $url_valor) {
        $existe_codigo = SQL::existeItem("motivos_retiro", "codigo", $url_valor,"codigo != 0");
	
        if ($existe_codigo) {
	    HTTP::enviarJSON($textos["ERROR_EXISTE_MOTIVO_RETIRO"]);
        } 
    }
    else if ($url_item == "descripcion" && $url_valor) {
        $existe_descripcion = SQL::existeItem("motivos_retiro", "descripcion", $url_valor,"codigo != 0");
	
        if ($existe_descripcion) {
	    HTTP::enviarJSON($textos["ERROR_EXISTE_DESCRIPCION"]);
        } 
    }

/*** Adicionar los datos provenientes del formulario ***/
} elseif (!empty($forma_procesar)) {
    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];

    $codigo = (int)$forma_codigo;
    
    if (empty($forma_codigo) || empty($forma_descripcion) || $codigo == 0){
        $error = true;
        $mensaje = $textos["ERROR_DATOS_INCOMPLETOS"];
    } elseif (SQL::existeItem("motivos_retiro", "codigo", $forma_codigo,"codigo != 0")) {
        $error   = true;
        $mensaje =  $textos["ERROR_EXISTE_MOTIVO_RETIRO"];            
    } elseif (SQL::existeItem("motivos_retiro", "descripcion", $forma_descripcion,"codigo != 0")) {
        $error   = true;
        $mensaje =  $textos["ERROR_EXISTE_DESCRIPCION"];            
    } else {
        /*** Insertar datos ***/
        $datos = array (
            "codigo"	  => $forma_codigo,
            "descripcion" => $forma_descripcion,
            "indemniza"	  => $forma_indemniza
        );
        $insertar = SQL::insertar("motivos_retiro", $datos);

        /*** Error de inserc�n ***/
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

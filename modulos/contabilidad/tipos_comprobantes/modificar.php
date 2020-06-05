<?php

/**
*
* Copyright (C) 2008 Felinux Ltda
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
        $vistaConsulta = "tipos_comprobantes";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo = '$url_id'");
        $datos         = SQL::filaEnObjeto($consulta);
        
        $error         = "";
        $titulo        = $componente->nombre;

        /*** Definici�n de pesta�as general ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::campoTextoCorto("*codigo", $textos["CODIGO_INTERNO"], 2, 2, $datos->codigo, array("title" => $textos["AYUDA_CODIGO_INTERNO"],"onBlur" => "validarItem(this);")),
            ),
            array(  
                HTML::campoTextoCorto("*descripcion", $textos["DESCRIPCION"], 20, 255, $datos->descripcion, array("title" => $textos["AYUDA_DESCRIPCION"],"onBlur" => "validarItem(this);"))
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

    /*** Validar codigo_anexo ***/
    if ($url_item == "codigo") {
        $existe = SQL::existeItem("tipos_comprobantes", "codigo", $url_valor, "codigo != '$url_id' AND codigo != '0'");

        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_CODIGO_INTERNO"]);
        }
    }

    /*** Validar descripcion ***/
    if ($url_item == "descripcion") {
        $existe = SQL::existeItem("tipos_comprobantes", "descripcion", $url_valor, "codigo != '$url_id' AND descripcion != ''");

        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_DESCRIPCION"]);
        }
    }

/*** Modificar el elemento seleccionado ***/
} elseif (!empty($forma_procesar)) {
    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_MODIFICADO"];

    if(empty($forma_codigo)){
		$error   = true;
        $mensaje = $textos["CODIGO_VACIO"];
        
	}elseif(empty($forma_descripcion)){
        $error   = true;
        $mensaje = $textos["DESCRIPCION_VACIO"];
    
    }elseif($existe = SQL::existeItem("tipos_comprobantes", "codigo", $forma_codigo,"codigo != '$forma_id'")){
	    $error   = true;
        $mensaje = $textos["ERROR_EXISTE_CODIGO_INTERNO"];
        
    }elseif($existe = SQL::existeItem("tipos_comprobantes", "descripcion", $forma_descripcion, "codigo != '$forma_id'")){
	    $error   = true;
        $mensaje = $textos["ERROR_EXISTE_DESCRIPCION"];
    
    }else {
		/*** Insertar datos ***/
        $datos = array(
            "codigo"      => $forma_codigo,
            "descripcion" => $forma_descripcion
        );
        $consulta = SQL::modificar("tipos_comprobantes", $datos, "codigo = '$forma_id'");
		
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

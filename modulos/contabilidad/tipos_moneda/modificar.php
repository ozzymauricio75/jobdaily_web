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
* de APTITUD PARA UN PROPÓITO DETERMINADO. Consulte los detalles de
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

    /*** Verificar que se haya enviado el ID del elemento a modificar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_MODIFICAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {
        $vistaConsulta = "tipos_moneda";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo = '$url_id'");
        $datos         = SQL::filaEnObjeto($consulta);
        $error         = "";
        $titulo        = $componente->nombre;
        

        /*** Definición de pestañas general ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::campoTextoCorto("codigo", $textos["CODIGO"], 3, 3, $datos->codigo, array("title" => $textos["AYUDA_CODIGO"],"onBlur" => "validarItem(this);", "onKeyPress" => "return campoEntero(event)"))
            ),
            array(
                HTML::campoTextoCorto("*codigo_dian", $textos["CODIGO_DIAN"], 3, 3, $datos->codigo_dian, array("title" => $textos["AYUDA_CODIGO_DIAN"],"onBlur" => "validarItem(this);", "onKeyPress" => "return campoEntero(event)"))
            ),
            array(
                HTML::campoTextoCorto("*nombre", $textos["NOMBRE"], 30, 30, $datos->nombre, array("title" => $textos["AYUDA_NOMBRE"],"onBlur" => "validarItem(this);"))
            )
        );

        /*** Definición de botones ***/
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "modificarItem('$url_id');", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones);
    }

    /*** Enviar datos para la generación del formulario al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

/*** Validar los datos provenientes del formulario ***/
} elseif (!empty($url_validar)) {

   /*** Validar código_dian ***/
    if ($url_item == "codigo_dian") {
        $existe = SQL::existeItem("tipos_moneda", "codigo_dian", $url_valor, "codigo != $url_id AND codigo_dian !=''");

        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_CODIGO_dian"]);
        }
    }
    /*** Validar codigo_interno ***/
    if ($url_item == "codigo") {
        $existe = SQL::existeItem("tipos_moneda", "codigo", $url_valor, "codigo != $url_id AND codigo >0");

        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_CODIGO_INTERNO"]);
        }
    }
    /*** Validar descripción ***/
    if ($url_item == "nombre") {
        $existe = SQL::existeItem("tipos_moneda", "nombre", $url_valor, "codigo != $url_id AND nombre !=''");

        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_NOMBRE"]);
        }
    }

/*** Modificar el elemento seleccionado ***/
} elseif (!empty($forma_procesar)) {    
    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_MODIFICADO"];
    
    if(empty($forma_codigo_dian)){
		$error   = true;
		$mensaje = $textos["CODIGO_VACIO"];
	}elseif(empty($forma_nombre)){
        $error   = true;
        $mensaje = $textos["NOMBRE_VACIO"];
     }elseif($existe = SQL::existeItem("tipos_moneda", "codigo_dian", $forma_codigo_dian,"codigo !='$forma_id' AND codigo_dian !=''")){
	    $error   = true;
        $mensaje = $textos["ERROR_EXISTE_CODIGO_dian"];
     }elseif($existe = SQL::existeItem("tipos_moneda", "codigo", $forma_codigo,"codigo !='$forma_id' AND codigo >0")){
	    $error   = true;
        $mensaje = $textos["ERROR_EXISTE_CODIGO_INTERNO"];
    }elseif($existe = SQL::existeItem("tipos_moneda", "nombre", $forma_nombre,"codigo !='$forma_id' AND nombre !=''")){
	    $error   = true;
        $mensaje = $textos["ERROR_EXISTE_NOMBRE"];
    }else {
		/*** Insertar datos ***/
        $datos = array(
            "codigo"      => $forma_codigo,
            "codigo_dian" => $forma_codigo_dian,
            "nombre"      => $forma_nombre
        );
        $consulta = SQL::modificar("tipos_moneda", $datos, "codigo = '$forma_id'");
		
		/*** Error inserción ***/
        if ($consulta) {
            $error   = false;
            $mensaje = $textos["ITEM_MODIFICADO"];
        } else {
            $error   = true;
            $mensaje = $textos["ERROR_MODIFICAR_ITEM"];
        }
    }
    /*** Enviar datos con la respuesta del proceso al script que originï¿½ la peticiï¿½n ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

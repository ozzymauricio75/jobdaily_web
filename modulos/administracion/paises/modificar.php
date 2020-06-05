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

    /*** Verificar que se haya enviado el ID del elemento a modificar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_MODIFICAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {
        $vistaConsulta = "paises";
        $condicion     = "codigo_iso = '$url_id'";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, $condicion);
        $datos         = SQL::filaEnObjeto($consulta);
        
        $error         = "";
        $titulo        = $componente->nombre;

        /*** Definici�n de pesta�as ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::campoTextoCorto("codigo_iso", $textos["CODIGO_ISO"], 2, 2, $datos->codigo_iso, array("title" => $textos["AYUDA_CODIGO_ISO"], "onBlur" => "validarItem(this);"))
            ),
            array(
                HTML::campoTextoCorto("nombre", $textos["NOMBRE"], 30, 255, $datos->nombre, array("title" => $textos["AYUDA_NOMBRE"], "onBlur" => "validarItem(this);"))
            ),
            array(
                HTML::campoTextoCorto("codigo_interno", $textos["CODIGO_INTERNO"], 4, 4, $datos->codigo_interno, array("title" => $textos["AYUDA_CODIGO_INTERNO"]))
            ),
            array(
                HTML::campoOculto("llave",$datos->codigo_iso)
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

    $respuesta = "";

    if ($url_item == "nombre" && $url_valor) {
        $existe = SQL::existeItem("paises", "nombre", $url_valor, "id != '$url_id' AND nombre !=''");

        if ($existe) {
            $respuesta =  $textos["ERROR_EXISTE_NOMBRE"];
        }
    }

    if ($url_item == "codigo_iso" && $url_valor) {
        $existe = SQL::existeItem("paises", "codigo_iso", $url_valor, "id != '$url_id' AND codigo_iso !=''");

        if ($existe) {
            $respuesta =  $textos["ERROR_EXISTE_CODIGO_ISO"];

        } elseif (!Cadena::validarMayusculas($url_valor, 2, 2)) {
            $respuesta =  $textos["ERROR_FORMATO_CODIGO_ISO"];
        }
    }

    if ($url_item == "codigo_interno" && $url_valor) {
        $existe = SQL::existeItem("paises", "codigo_interno", $url_valor, "id != '$url_id' AND codigo_interno !='0'");

        if ($existe) {
            $respuesta =  $textos["ERROR_EXISTE_CODIGO_INTERNO"];

        } elseif (!Cadena::validarNumeros($url_valor)) {
            $respuesta =  $textos["ERROR_FORMATO_CODIGO_INTERNO"];
        }
    }

    HTTP::enviarJSON($respuesta);

/*** Modificar el elemento seleccionado ***/
} elseif (!empty($forma_procesar)) {

    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_MODIFICADO"];

   if(empty($forma_nombre)){
		$error   = true;
        $mensaje = $textos["NOMBRE_VACIO"];
	}elseif(empty($forma_codigo_iso)){
        $error   = true;
        $mensaje = $textos["CODIGO_VACIO"];
    } elseif (SQL::existeItem("paises", "nombre", $forma_nombre, "codigo_iso != '$forma_llave'")) {
        $error   = true;
        $mensaje =  $textos["ERROR_EXISTE_NOMBRE"];
    } elseif (SQL::existeItem("paises", "codigo_iso", $forma_codigo_iso, "codigo_iso != '$forma_llave'")) {
		$error   = true;
		$mensaje =  $textos["ERROR_EXISTE_CODIGO_ISO"];
    } elseif (!Cadena::validarMayusculas($forma_codigo_iso, 2, 2)) {
        $error   = true;
        $mensaje =  $textos["ERROR_FORMATO_CODIGO_ISO"];
    } elseif (!empty($forma_codigo_interno) && SQL::existeItem("paises", "codigo_interno", $forma_codigo_interno, "codigo_iso != '$forma_llave'")) {
        $error   = true;
        $mensaje =  $textos["ERROR_EXISTE_CODIGO_INTERNO"];
    } elseif (!empty($forma_codigo_interno) && !Cadena::validarNumeros($forma_codigo_interno)) {
        $error   = true;
        $mensaje =  $textos["ERROR_FORMATO_CODIGO_INTERNO"];
    } else {
		/*** Insertar datos ***/ 
        $datos = array(
            "nombre"         => $forma_nombre,
            "codigo_iso"     => $forma_codigo_iso,
            "codigo_interno" => $forma_codigo_interno
        );
        $consulta = SQL::modificar("paises", $datos, "codigo_iso = '$forma_llave'");
		
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

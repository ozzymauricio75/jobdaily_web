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
    $paises = HTML::generarDatosLista("paises","codigo_iso", "nombre","codigo_iso !=''");

    /*** Definición de pestañas ***/
    $formularios["PESTANA_GENERAL"] = array(
        array(
            HTML::listaSeleccionSimple("*pais", $textos["PAIS"], $paises, "", array("title" => $textos["AYUDA_PAIS"]))
        ),
        array(
            HTML::campoTextoCorto("*nombre", $textos["NOMBRE"], 30, 255, "", array("title" => $textos["AYUDA_NOMBRE"], "onBlur" => "validarItem(this);"))
        ),
        array(
            HTML::campoTextoCorto("*codigo_dane_departamento", $textos["CODIGO_DANE"], 2, 2, "", array("title" => $textos["AYUDA_CODIGO_DANE"], "onBlur" => "validarItem(this);"))
        ),
        array(
            HTML::campoTextoCorto("codigo_interno", $textos["CODIGO_INTERNO"], 3, 3, "", array("title" => $textos["AYUDA_CODIGO_INTERNO"], "onBlur" => "validarItem(this);"))
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

/*** Validación en línea de los datos provenientes del formulario ***/
} elseif (!empty($url_validar)) {

    $respuesta = "";

    if ($url_item == "nombre" && $url_valor) {
        $existe = SQL::existeItem("departamentos", "nombre", $url_valor,"nombre !=''");

        if ($existe) {
            $respuesta =  $textos["ERROR_EXISTE_NOMBRE"];
        }
    }

    if ($url_item == "codigo_dane_departamento" && $url_valor) {
        $existe = SQL::existeItem("departamentos", "codigo_dane_departamento", $url_valor,"codigo_dane_departamento !=''");

        if ($existe) {
            $respuesta =  $textos["ERROR_EXISTE_CODIGO_DANE"];

        } elseif (!Cadena::validarNumeros($url_valor, 2, 2)) {
            $respuesta =  $textos["ERROR_FORMATO_CODIGO_DANE"];
        }
    }

    HTTP::enviarJSON($respuesta);

/*** Adicionar los datos provenientes del formulario ***/
} elseif (!empty($forma_procesar)) {

    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];

    /*** Validar el ingreso de los datos requeridos ***/
    if(empty($forma_pais)){
	    $error   = true;
            $mensaje = $textos["PAIS_VACIO"];
	}elseif(empty($forma_nombre)){
        $error   = true;
        $mensaje = $textos["NOMBRE_VACIO"];
	}elseif(empty($forma_codigo_dane_departamento)){
        $error   = true;
        $mensaje = $textos["CODIGO_VACIO"];
    } elseif (SQL::existeItem("departamentos", "nombre", $forma_nombre, "codigo_iso = '$forma_pais'")) {
        $error   = true;
        $mensaje =  $textos["ERROR_EXISTE_NOMBRE"];
    } elseif (SQL::existeItem("departamentos", "codigo_dane_departamento", $forma_codigo_dane_departamento, "codigo_iso = '$forma_pais'")) {
        $error   = true;
        $mensaje =  $textos["ERROR_EXISTE_CODIGO_DANE"];
    } elseif (!Cadena::validarNumeros($forma_codigo_dane_departamento, 2, 2)) {
        $error   = true;
        $mensaje =  $textos["ERROR_FORMATO_CODIGO_DANE"];
    } elseif (!empty($forma_codigo_interno) && !Cadena::validarNumeros($forma_codigo_interno)) {
        $error   = true;
        $mensaje =  $textos["ERROR_FORMATO_CODIGO_INTERNO"];
    } else {
		/*** Insertar datos ***/
        $datos = array(
            "codigo_iso"                => $forma_pais,
            "nombre"                    => $forma_nombre,
            "codigo_dane_departamento"  => $forma_codigo_dane_departamento,
            "codigo_interno"            => $forma_codigo_interno
        );
        $insertar = SQL::insertar("departamentos", $datos);

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

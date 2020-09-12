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

if (isset($url_completar)) {
    if (($url_item) == "selector1") {
        echo SQL::datosAutoCompletar("seleccion_municipios", $url_q);
    }
    exit;
}

/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {

    /*** Verificar que se haya enviado el ID del elemento a modificar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_MODIFICAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {
        $vistaConsulta = "conceptos_tesoreria";
        $condicion     = "codigo = '$url_id'";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, $condicion);
        $datos         = SQL::filaEnObjeto($consulta);

        $error         = "";
        $titulo        = $componente->nombre;

        /*** Obtener valores ***/
        $grupo_tesoreria = SQL::obtenerValor("grupos_tesoreria","nombre_grupo","codigo = '$datos->codigo_grupo_tesoreria'");

        /*** Definición de pestañas general ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::campoTextoCorto("*codigo", $textos["CODIGO"], 4, 4, $datos->codigo, array("title" => $textos["AYUDA_CODIGO"],"onBlur" => "validarItem(this);", "onKeyPress" => "return campoEntero(event)"))
            ),
            array(
                HTML::listaSeleccionSimple("*codigo_grupo", $textos["EMPRESA"], HTML::generarDatosLista("grupos_tesoreria", "codigo", "nombre_grupo","codigo != 0"), $datos->grupo_tesoreria, array("title" => $textos["AYUDA_GRUPO_TESORERIA"],"onBlur" => "validarItem(this);"))
            ),
            array(
                HTML::campoTextoCorto("*nombre", $textos["NOMBRE"], 40, 60, $datos->nombre_concepto, array("title" => $textos["AYUDA_NOMBRE"],"onBlur" => "validarItem(this);"))
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

    $respuesta = "";

    /*** Validar codigo ***/
    if ($url_item == "codigo" && $url_valor) {
        $existe = SQL::existeItem("conceptos_tesoreria", "codigo", $url_valor, "codigo != '$url_id' AND codigo !=''");

        if ($existe) {
            $respuesta = $textos["ERROR_EXISTE_CODIGO"];
        }
    }

    /*** Validar nombre ***/ 
    if ($url_item == "nombre" && $url_valor) {
        $existe = SQL::existeItem("conceptos_tesoreria", "nombre_concepto", $url_valor, "codigo != '$url_id' AND nombre_concepto !=''");

        if ($existe) {
            $respuesta = $textos["ERROR_EXISTE_NOMBRE"];
        }
    }
    
    HTTP::enviarJSON($respuesta);

/*** Modificar el elemento seleccionado ***/
} elseif (!empty($forma_procesar)) {

    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_MODIFICADO"];

    /*** Validar el ingreso de los datos requeridos ***/
    if(empty($forma_codigo)){
		$error   = true;
        $mensaje = $textos["CODIGO_VACIO"];
            
	}elseif(empty($forma_nombre)){
		$error   = true;
        $mensaje = $textos["NOMBRE_VACIO"];
            
	}elseif(SQL::existeItem("conceptos_tesoreria", "codigo", $forma_codigo,"codigo !='' AND codigo !='$forma_id'")){
	    $error   = true;
        $mensaje = $textos["ERROR_EXISTE_CODIGO"];

     }elseif(SQL::existeItem("conceptos_tesoreria", "nombre_concepto", $forma_nombre,"nombre_concepto !='' AND codigo !='$forma_id'")){
	    $error   = true;
        $mensaje = $textos["ERROR_EXISTE_NOMBRE"];

    } else {

        /*** Insertar datos ***/
        $datos = array(
            "codigo"                       => $forma_codigo,
            "codigo_grupo_tesoreria"       => $forma_codigo_grupo,
            "nombre_concepto"              => $forma_nombre
        );

        $consulta = SQL::modificar("conceptos_tesoreria", $datos, "codigo = '$forma_id'");
		
		/*** Error inserción ***/
        if ($consulta) {
            $error   = false;
            $mensaje = $textos["ITEM_MODIFICADO"];
        } else {
            $error   = true;
            $mensaje = $textos["ERROR_MODIFICAR_ITEM"];
        }
    }
    /*** Enviar datos con la respuesta del proceso al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

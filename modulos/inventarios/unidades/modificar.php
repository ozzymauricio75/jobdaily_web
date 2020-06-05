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

/*** Devolver datos para autocompletar la búsqueda ***/
if (isset($url_completar)) {

    if (($url_item) == "selector1") {
        echo SQL::datosAutoCompletar("seleccion_unidades", $url_q);
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
        $vistaConsulta = "unidades";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo = '$url_id'");
        $datos         = SQL::filaEnObjeto($consulta);
        
        $error         = "";
        $titulo        = $componente->nombre;

        /*** Obtener valores de la tabla ***/
        $factor_conversion       = SQL::obtenerValor("unidades","factor_conversion","codigo = '$url_id'");
        $codigo_unidad_principal = SQL::obtenerValor("unidades","codigo_unidad_principal","codigo = '$url_id'");
        $unidad_base_texto       = SQL::obtenerValor("unidades","nombre","codigo = '$codigo_unidad_principal'");
        $tipo_unidad             = SQL::obtenerValor("unidades","codigo_tipo_unidad","codigo = '$url_id'");

        $chequea = false;
        $oculta  = "";
        if ($codigo_unidad_principal == 0) {
            $chequea = true;
            $oculta  = "oculto";
        }

        /*** Definición de pestañas ***/
        $formularios["PESTANA_GENERAL"] = array(
        array(
            HTML::listaSeleccionSimple("tipo_unidad", $textos["TIPO_UNIDAD"], HTML::generarDatosLista("tipos_unidades", "codigo", "nombre"), $tipo_unidad)
            ),
            array(
                HTML::campoTextoCorto("*codigo", $textos["CODIGO"], 10,6, $datos->codigo, array("title" => $textos["AYUDA_CODIGO"],"onBlur" => "validarItem(this);", "onKeyPress" => "return campoEntero(event)"))
            ),
            array(
                HTML::campoTextoCorto("*nombre", $textos["NOMBRE"], 30, 255, $datos->nombre, array("title" => $textos["AYUDA_NOMBRE"],"onBlur" => "validarItem(this);")),
            ),
            array(
               HTML::marcaChequeo("principal", $textos["PRINCIPAL"], 1, $chequea, array("onChange" => "unidadPrincipal()"))
            ),
            array(
                HTML::campoTextoCorto("*selector1", $textos["UNIDAD_BASE"], 20, 60, $unidad_base_texto, array("title" => $textos["AYUDA_UNIDAD_BASE"], "class" => "autocompletable $oculta"))
                .HTML::campoOculto("codigo_unidad_principal", $codigo_unidad_principal),
                HTML::campoTextoCorto("*factor_conversion", $textos["FACTOR_CONVERSION"], 9, 9, $factor_conversion, array("title" => $textos["AYUDA_FACTOR"], "class" => $oculta, "onKeyPress" => "return campoDecimal(event)"))
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

/*** Validar codigo ***/
    if ($url_item == "codigo") {
        $existe = SQL::existeItem("unidades", "codigo", $url_valor, "codigo != $url_id AND codigo !=''");

        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_CODIGO"]);
        }
    }

    /*** Validar nombre ***/
    if ($url_item == "nombre") {
        $existe = SQL::existeItem("unidades", "nombre", $url_valor, "codigo != $url_id AND nombre !=''");

        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_NOMBRE"]);
        }
    }

/*** Modificar el elemento seleccionado ***/
} elseif (!empty($forma_procesar)) {
    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_MODIFICADO"];

	/*** Validar campos requeridos ***/
    if(empty($forma_tipo_unidad) || $forma_tipo_unidad == "00"){
		$error   = true;
        $mensaje = $textos["TIPO_UNIDAD_VACIO"];
        
	}elseif(empty($forma_codigo)){
		$error   = true;
        $mensaje = $textos["CODIGO_VACIO"];
        
	}elseif(empty($forma_nombre)){
		$error   = true;
        $mensaje = $textos["NOMBRE_VACIO"];
	
	}elseif(!isset($forma_principal) && empty($forma_factor_conversion)){
		$error   = true;
        $mensaje = $textos["CONVERSION_VACIO"];
		
	}elseif(!isset($forma_principal) && empty($forma_codigo_unidad_principal)){
        $error   = true;
        $mensaje = $textos["UNIDAD_BASE_VACIO"];

    } elseif (!empty($forma_codigo) && SQL::existeItem("unidades", "codigo", $forma_codigo, "codigo != '$forma_id'")) {
            $error   = true;
            $mensaje =  $textos["ERROR_EXISTE_CODIGO"];

    } elseif (!empty($forma_nombre) && SQL::existeItem("unidades", "nombre", $forma_nombre, "codigo != '$forma_id'")) {
            $error   = true;
            $mensaje =  $textos["ERROR_EXISTE_NOMBRE"];

    } else {
		if(isset($forma_principal))
            $forma_id_unidad_base = 0;
		
		/*** Insertar datos ***/
        $datos = array(
            "codigo_tipo_unidad"      => $forma_tipo_unidad,
            "codigo"                  => $forma_codigo,
            "nombre"                  => $forma_nombre,
            "factor_conversion"       => $forma_factor_conversion,
            "codigo_unidad_principal" => $forma_codigo_unidad_principal
        );
        $consulta = SQL::modificar("unidades", $datos, "codigo = '$forma_id'");
		
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

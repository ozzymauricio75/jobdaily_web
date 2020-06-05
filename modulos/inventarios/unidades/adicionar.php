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
    $error  = "";
    $titulo = $componente->nombre;
    
    $unidades = SQL::seleccionar(array("tipos_unidades"),array("*"),"codigo>0");

    if (SQL::filasDevueltas($unidades)){

        $consecutivo = (int)SQL::obtenerValor("unidades","max(codigo)","");
        if($consecutivo){
            $consecutivo++;
        }else{
            $consecutivo=1;
        }

        /*** Definición de pestañas general ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::listaSeleccionSimple("*tipo_unidad", $textos["TIPO_UNIDAD"], HTML::generarDatosLista("tipos_unidades", "codigo", "nombre","codigo != 0"))
            ),
            array(
                HTML::campoTextoCorto("*codigo", $textos["CODIGO"], 10, 6, $consecutivo, array("title" => $textos["AYUDA_CODIGO"],"onBlur" => "validarItem(this);", "onKeyPress" => "return campoEntero(event)"))
            ),
            array(
                HTML::campoTextoCorto("*nombre", $textos["NOMBRE"], 30, 255, "", array("title" => $textos["AYUDA_NOMBRE"],"onBlur" => "validarItem(this);"))
            ),
            array(
                HTML::marcaChequeo("principal", $textos["PRINCIPAL"], 1, false, array("onChange" => "unidadPrincipal()"))
            ),
            array(
                HTML::campoTextoCorto("*selector1", $textos["UNIDAD_BASE"], 20, 60, "", array("title" => $textos["AYUDA_UNIDAD_BASE"], "class" => "autocompletable"))
                .HTML::campoOculto("codigo_unidad_principal", ""),
                HTML::campoTextoCorto("*factor_conversion", $textos["FACTOR_CONVERSION"], 9, 9, "", array("title" => $textos["AYUDA_FACTOR"], "onKeyPress" => "return campoDecimal(event)"))
            )
        );

        /*** Definición de botones ***/
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones);
    } else {
        $error     = $textos["CREAR_TIPOS_UNIDADES"];
        $contenido = "";
    }

    /*** Enviar datos para la generación del formulario al script que originó la petición ***/
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

/*** Validar los datos provenientes del formulario ***/
} elseif (!empty($url_validar)) {

    /*** Validar codigo ***/
    if ($url_item == "codigo") {
        $existe = SQL::existeItem("unidades", "codigo", $url_valor,"codigo !=''");

        if ($existe) {
           HTTP::enviarJSON($textos["ERROR_EXISTE_CODIGO"]);
        }
    }

    /*** Validar nombre ***/
    if ($url_item == "nombre") {
        $existe = SQL::existeItem("unidades", "nombre", $url_valor,"nombre !=''");

        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_NOMBRE"]);
        }
    }

/*** Adicionar los datos provenientes del formulario ***/
} elseif (!empty($forma_procesar)) {
    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];
	
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

    } elseif (!empty($forma_codigo) && SQL::existeItem("unidades", "codigo", $forma_codigo)) {
            $error   = true;
            $mensaje =  $textos["ERROR_EXISTE_CODIGO"];

    } elseif (!empty($forma_nombre) && SQL::existeItem("unidades", "nombre", $forma_nombre)) {
            $error   = true;
            $mensaje =  $textos["ERROR_EXISTE_NOMBRE"];

    } else {
		if(isset($forma_principal))
            $forma_id_unidad_base = 0;
            
		/*** Insertar datos ***/
        $datos = array(
            "codigo"                  => $forma_codigo,
            "codigo_tipo_unidad"      => $forma_tipo_unidad,           
            "nombre"                  => $forma_nombre,
            "factor_conversion"       => $forma_factor_conversion,
            "codigo_unidad_principal" => $forma_codigo_unidad_principal
        );
        $insertar = SQL::insertar("unidades", $datos);

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

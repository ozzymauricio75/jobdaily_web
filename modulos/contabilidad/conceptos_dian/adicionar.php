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
    $error  = "";
    $titulo = $componente->nombre;
    
    $consulta_formatos = SQL::seleccionar(array("formatos_dian"),array("*"),"codigo>0");
    if (SQL::filasDevueltas($consulta_formatos)){

        $valor_a_informar = array(
            "1" => $textos["SALDO"],
            "2" => $textos["ACUMULADO"],
            "3" => $textos["ACUMULADO_DB_CR"]
        );
        $codigo = SQL::obtenerValor("conceptos_dian","MAX(codigo)","codigo>0");
        if ($codigo){
            $codigo++;
        } else {
            $codigo = 1;
        }

        /*** Definición de pestañas general ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::campoTextoCorto("*codigo", $textos["CODIGO_DIAN"], 4, 4, $codigo, array("title" => $textos["AYUDA_CODIGO_DIAN"],"onBlur" => "validarItem(this);","onKeyPress" => "return campoEntero(event)"))
            ),
            array(
                HTML::listaSeleccionSimple("*codigo_formato_dian", $textos["FORMATO_DIAN"], HTML::generarDatosLista("formatos_dian", "codigo", "descripcion"), "",array("title" => $textos["AYUDA_FORMATO_DIAN"]))
            ),
            array(            
                HTML::campoTextoCorto("*descripcion", $textos["DESCRIPCION"], 30, 255, "", array("title" => $textos["AYUDA_DESCRIPCION"])),
                HTML::campoTextoCorto("*valor_base", $textos["VALOR_BASE"], 20, 20, "", array("title" => $textos["AYUDA_VALOR_BASE"],"onKeyPress" => "return campoDecimal(event)"))
            ),
            array(
                HTML::listaSeleccionSimple("*valor_a_informar", $textos["VALOR_INFORMAR"], $valor_a_informar, "", array("title" => $textos["AYUDA_VALOR_INFORMAR"]))
            ),
            array(
                HTML::campoTextoCorto("*identificacion_valores_mayores", $textos["IDENTIFICAION_VALORES"], 15, 15, "", array("title" => $textos["AYUDA_IDENTIFICACION_VALORES"],"onKeyPress" => "return campoEntero(event)"))
            ),
            array(
                HTML::campoTextoCorto("*concepto_razon_social", $textos["CONCEPTO_RAZON_SOCIAL"], 30, 255, "", array("title" => $textos["AYUDA_CONCEPTO_RAZON_SOCIAL"])),
                HTML::campoTextoCorto("*tipo_documento", $textos["TIPO_DOCUMENTO"], 4, 4, "", array("title" => $textos["AYUDA_TIPO_DOCUMENTO"],"onKeyPress" => "return campoEntero(event)"))
            )   
        );

        /*** Definición de botones ***/
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones);
    } else {
        $error = $textos["CREAR_FORMATOS_DIAN"];
        $contenido = "";
    }

    /*** Enviar datos para la generación del formulario al script que originó la petición ***/
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);
    
/*** validación de los datos ***/
} elseif (!empty($url_validar)) {

    /*** Validar numero ***/
    if ($url_item == "codigo") {
        $existe = SQL::existeItem("conceptos_dian", "codigo", $url_valor,"codigo !=''");

        if ($existe) {
           HTTP::enviarJSON($textos["ERROR_EXISTE_CODIGO_DIAN"]);
        }
    }

/*** Validar los datos provenientes del formulario ***/
}  elseif (!empty($forma_procesar)) {
    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];

    if(empty($forma_codigo_formato_dian)){
		$error   = true;
		$mensaje = $textos["FORMATO_DIAN_VACIO"];
	}elseif(empty($forma_codigo)){
		$error   = true;
		$mensaje = $textos["CODIGO_DIAN_VACIO"];
	}elseif(empty($forma_descripcion)){
		$error   = true;
		$mensaje = $textos["DESCRIPCION_VACIO"];
	}elseif(empty($forma_valor_base)){
		$error   = true;
		$mensaje = $textos["VALOR_BASE_VACIO"];
	}elseif(empty($forma_identificacion_valores_mayores)){
		$error   = true;
		$mensaje = $textos["VALORES_MAYORES_VACIO"];
	}elseif(empty($forma_valor_a_informar)){
		$error   = true;
		$mensaje = $textos["VALOR_INFORMAR_VACIO"];
	}elseif(empty($forma_concepto_razon_social)){
		$error   = true;
		$mensaje = $textos["RAZON_SOCIAL_VACIO"];
	}elseif(empty($forma_tipo_documento)){
        $error   = true;
        $mensaje = $textos["TIPO_DOCUMENTO_VACIO"];
    }elseif($existe = SQL::existeItem("conceptos_dian", "codigo", $forma_codigo,"codigo !=0")){
	    $error   = true;
        $mensaje = $textos["ERROR_EXISTE_CODIGO_DIAN"];
    
    } else {
		/*** Isertar datos ***/
        $datos = array(
            "codigo"                         => $forma_codigo,
            "codigo_formato_dian"            => $forma_codigo_formato_dian,                
            "descripcion"                    => $forma_descripcion,
            "valor_base"                     => $forma_valor_base,
            "valor_a_informar"               => $forma_valor_a_informar,
            "identificacion_valores_mayores" => $forma_identificacion_valores_mayores,
            "concepto_razon_social"          => $forma_concepto_razon_social,
            "tipo_documento"                 => $forma_tipo_documento
        );
        $insertar = SQL::insertar("conceptos_dian", $datos);

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

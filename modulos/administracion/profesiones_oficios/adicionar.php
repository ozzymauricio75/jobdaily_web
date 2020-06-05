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
    
    $codigo = SQL::obtenerValor("profesiones_oficios","MAX(codigo_dane)","codigo_dane>0");
    if ($codigo){
        $codigo++;
    } else {
        $codigo = 1;
    }

    /*** Definición de pestañas general ***/
    $formularios["PESTANA_GENERAL"] = array(
        array(
            HTML::campoTextoCorto("*codigo_dane", $textos["CODIGO_DANE"], 4, 4, $codigo, array("title" => $textos["AYUDA_CODIGO_DANE"], "onblur" => "validarItem(this);", "onKeyPress" => "return campoEntero(event)"))
        ),
        array(
            HTML::campoTextoCorto("*descripcion", $textos["DESCRIPCION"], 30, 255, "", array("title" => $textos["AYUDA_DESCRIPCION"], "onblur" => "validarItem(this);"))
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
    
    /*** Validar codigo_DANE ***/
    if ($url_item == "codigo_dane") {
        $existe = SQL::existeItem("profesiones_oficios", "codigo_DANE", $url_valor,"codigo_dane !='0'");

        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_CODIGO_DANE"]);
        }
    }

    /*** Validar nombre ***/
    if ($url_item == "descripcion") {
        $existe = SQL::existeItem("profesiones_oficios", "descripcion", $url_valor,"descripcion !=''");

        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_DESCRIPCION"]);
        }
    }

/*** Procesar los datos del formulario ***/
}  elseif (!empty($forma_procesar)) {
    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];
    
    if(empty($forma_codigo_dane)){
		$error = true;
        $mensaje = $textos["CODIGO_VACIO"];
	}elseif(empty($forma_descripcion)){
        $error = true;
        $mensaje = $textos["DESCRIPCION_VACIO"];
    }elseif($existe = SQL::existeItem("profesiones_oficios", "codigo_dane", $forma_codigo_dane)){
	    $error   = true;
        $mensaje = $textos["ERROR_EXISTE_CODIGO_DANE"]; 
    }elseif($existe = SQL::existeItem("profesiones_oficios", "descripcion", $forma_descripcion)){
	    $error   = true;
        $mensaje = $textos["ERROR_EXISTE_DESCRIPCION"]; 
    }else {
		/*** Insertar datos ***/
        $datos = array(
            "codigo_dane"    => $forma_codigo_dane,
            "descripcion"    => $forma_descripcion
        );
        $insertar = SQL::insertar("profesiones_oficios", $datos);
        
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

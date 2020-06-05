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

    /*** Definición de pestañas general ***/
    $formularios["PESTANA_GENERAL"] = array(
        array(
            HTML::campoTextoCorto("*numero", $textos["NUMERO"], 15, 20, "", array("title" => $textos["AYUDA_NUMERO"],"onBlur" => "validarItem(this);"))
        ),
        array(
            HTML::campoTextoCorto("*fecha", $textos["FECHA"], 10, 10, date("Y-m-d"), array("class" => "fechaNuevas", "title" => $textos["AYUDA_FECHA"]))
        ),
        array(
            HTML::campoTextoCorto("*descripcion", $textos["DESCRIPCION"], 30, 255, "", array("title" => $textos["AYUDA_DESCRIPCION"]))
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
    
/*** validacion de los datos ***/
} elseif (!empty($url_validar)) {

    /*** Validar numero ***/
    if ($url_item == "numero") {
        $existe = SQL::existeItem("resoluciones_gran_contribuyente", "numero_resolucion", $url_valor,"numero_resolucion !=''");

        if ($existe) {
           HTTP::enviarJSON($textos["ERROR_EXISTE_NUMERO"]);
        }
    }

/*** Adicionar los datos provenientes del formulario ***/
} elseif (!empty($forma_procesar)) {
    
    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];
    
    if(empty($forma_descripcion)){
		$error   = true;
		$mensaje = $textos["DESCRIPCION_VACIO"];
	}elseif(empty($forma_numero)){
		$error   = true;
		$mensaje = $textos["NUMERO_VACIO"];
	}elseif(empty($forma_fecha)){
        $error   = true;
        $mensaje = $textos["FECHA_VACIO"];
    }elseif(SQL::existeItem("resoluciones_gran_contribuyente", "numero_resolucion", $forma_numero,"numero_resolucion !=''")){
	    $error   = true;
        $mensaje = $textos["ERROR_EXISTE_NUMERO"];
    }else{
		/*** Insertar datos ***/
        $datos = array(
            "numero_resolucion" => $forma_numero,
            "fecha"             => $forma_fecha,
            "descripcion"       => $forma_descripcion
        );
        $insertar = SQL::insertar("resoluciones_gran_contribuyente", $datos);

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

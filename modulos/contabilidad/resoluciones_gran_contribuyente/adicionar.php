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
    $error  = "";
    $titulo = $componente->nombre;

    /*** Definici�n de pesta�as general ***/
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

    /*** Definici�n de botones ***/
    $botones = array(
        HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar")
    );

    $contenido = HTML::generarPestanas($formularios, $botones);

    /*** Enviar datos para la generaci�n del formulario al script que origin� la petici�n ***/
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

        /*** Error de inserci�n ***/
        if (!$insertar) {
            $error   = true;
            $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
        }
    }
    /*** Enviar datos con la respuesta del proceso al script que origin� la petici�n ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

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
        $vistaConsulta = "cargos";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo = '$url_id'");
        $datos         = SQL::filaEnObjeto($consulta);
        
        $error         = "";
        $titulo        = $componente->nombre;
        
        /*** Obtener valor ***/
       

        /*** Definici�n de pesta�as general ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::campoTextoCorto("*codigo_interno", $textos["CODIGO"], 3, 3, $datos->codigo, array("title" => $textos["AYUDA_CODIGO"], "onblur" => "validarItem(this);", "onKeyPress" => "return campoEntero(event)"))
            ),
            array(
                HTML::campoTextoCorto("*nombre", $textos["NOMBRE"], 30, 50, $datos->nombre, array("title" => $textos["AYUDA_NOMBRE"], "onblur" => "validarItem(this);"))
            ),
            array(
                HTML::marcaChequeo("interno", $textos["INTERNO"], 1,$datos->interno)
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

    /*** Validar codigo_interno ***/
    if ($url_item == "codigo_interno") {
        $existe = SQL::existeItem("cargos", "codigo", $url_valor, "codigo !='$url_id' AND codigo !='0'");
        
        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_CODIGO"]);
        }
    }

    /*** Validar nombre ***/
    if ($url_item == "nombre") {
        $existe = SQL::existeItem("cargos", "nombre", $url_valor, "codigo !='$url_id' AND nombre !=''");

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
    if(empty($forma_codigo_interno)){
		$error = true;
        $mensaje = $textos["CODIGO_VACIO"];
        
	}elseif(empty($forma_nombre)){
        $error = true;
        $mensaje = $textos["NOMBRE_VACIO"];
        
    }elseif(SQL::existeItem("cargos", "codigo", $forma_codigo_interno,"codigo !=$forma_id")){
	    $error   = true;
        $mensaje = $textos["ERROR_EXISTE_CODIGO"];
     
    }elseif(SQL::existeItem("cargos", "nombre", $forma_nombre, "codigo !=$forma_id")){
	    $error   = true;
        $mensaje = $textos["ERROR_EXISTE_NOMBRE"];    
     
    }else {

        if (!isset($forma_interno)) {
            $forma_interno = "0";
        }
		/*** Insertar datos ***/
        $datos = array(
            "codigo"         => $forma_codigo_interno,
            "nombre"         => $forma_nombre,
	    "interno"        => $forma_interno
        );
        $consulta = SQL::modificar("cargos", $datos, "codigo = '$forma_id'");
		
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

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

    /*** Verificar que se haya enviado el ID del elemento a modificar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_MODIFICAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {
        $vistaConsulta = "grupos_tesoreria";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo = '$url_id'");
        $datos         = SQL::filaEnObjeto($consulta);
        
        $error         = "";
        $titulo        = $componente->nombre;
        
        /*** Obtener valor ***/
       

        /*** Definición de pestañas general ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::campoTextoCorto("*codigo_interno", $textos["CODIGO"], 3, 3, $datos->codigo, array("title" => $textos["AYUDA_CODIGO"], "onblur" => "validarItem(this);", "onKeyPress" => "return campoEntero(event)"))
            ),
            array(
                HTML::campoTextoCorto("*nombre", $textos["NOMBRE"], 30, 50, $datos->nombre_grupo, array("title" => $textos["AYUDA_NOMBRE"], "onblur" => "validarItem(this);"))
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

    /*** Validar nombre ***/
    if ($url_item == "nombre") {
        $existe = SQL::existeItem("grupos_tesoreria", "nombre", $url_valor, "codigo !='$url_id' AND nombre !=''");

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
    if(empty($forma_nombre)){
        $error = true;
        $mensaje = $textos["NOMBRE_VACIO"];
        
    }elseif(SQL::existeItem("grupos_tesoreria", "codigo", $forma_codigo_interno,"codigo !=$forma_id")){
	    $error   = true;
        $mensaje = $textos["ERROR_EXISTE_CODIGO"];
     
    }elseif(SQL::existeItem("grupos_tesoreria", "nombre", $forma_nombre, "codigo !=$forma_id")){
	    $error   = true;
        $mensaje = $textos["ERROR_EXISTE_NOMBRE"];    
     
    }else {

		/*** Insertar datos ***/
        $datos = array(
            "codigo"       => $forma_codigo_interno,
            "nombre_grupo" => $forma_nombre
        );
        $consulta = SQL::modificar("grupos_tesoreria", $datos, "codigo = '$forma_id'");
		
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

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

    /*** Verificar que se haya enviado el ID del elemento a modificar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_MODIFICAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {
        $error         = "";
        $titulo        = $componente->nombre;
        
        $vistaConsulta = "actividades_economicas_dian";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo_dian = '$url_id'");
        $datos         = SQL::filaEnObjeto($consulta);

        /*** Definición de pestañas general ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::campoTextoCorto("*codigo_dian", $textos["CODIGO_DIAN"], 4, 4, $datos->codigo_dian, array("title" => $textos["AYUDA_CODIGO_DIAN"],"onBlur" => "validarItem(this);","onKeyPress" => "return campoEntero(event)"))            
            ),
            array(            
                HTML::campoTextoCorto("*descripcion", $textos["DESCRIPCION"], 30, 255, $datos->descripcion, array("title" => $textos["AYUDA_DESCRIPCION"])),
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

/*** validación de los datos ***/
} elseif (!empty($url_validar)) {

    /*** Validar numero ***/
    if ($url_item == "codigo_dian") {
        $existe = SQL::existeItem("actividades_economicas_dian", "codigo_dian", $url_valor,"codigo_dian !='' AND codigo_dian !='$url_id'");

        if ($existe) {
           HTTP::enviarJSON($textos["ERROR_EXISTE_CODIGO_DIAN"]);
        }
    }

/*** Modificar el elemento seleccionado ***/
} elseif (!empty($forma_procesar)) {
    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_MODIFICADO"];
    
    if(empty($forma_codigo_dian)){
		$error   = true;
		$mensaje = $textos["CODIGO_VACIO"];
	}elseif(empty($forma_descripcion)){
        $error   = true;
        $mensaje = $textos["DESCRIPCION_VACIO"];
    }elseif(SQL::existeItem("actividades_economicas_dian", "codigo_dian", $forma_codigo_dian,"codigo_dian !='' AND codigo_dian !='$forma_id'")){
	    $error   = true;
        $mensaje = $textos["ERROR_EXISTE_CODIGO_DIAN"];
    } else {
		/*** Insertar datos ***/
        $datos = array(
            "codigo_dian" => $forma_codigo_dian,
            "descripcion" => $forma_descripcion
        );
        $consulta = SQL::modificar("actividades_economicas_dian", $datos, "codigo_dian = '$forma_id'");
		
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

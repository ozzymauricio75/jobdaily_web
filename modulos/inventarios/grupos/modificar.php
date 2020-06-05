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

    /*** Verificar que se haya enviado el ID del elemento a modificar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_MODIFICAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {
        $vistaConsulta = "grupos";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo = '$url_id'");
        $datos         = SQL::filaEnObjeto($consulta);
        $error         = "";
        $titulo        = $componente->nombre;

        /*** Definición de pestañas ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::campoTextoCorto("*codigo", $textos["CODIGO"], 4, 4, $datos->codigo, array("title" => $textos["AYUDA_CODIGO"],"onBlur" => "validarItem(this);","onKeyPress" => "return campoEntero(event)"))
            ),
            array(
                HTML::campoTextoCorto("*descripcion", $textos["DESCRIPCION"], 30, 255, $datos->descripcion, array("title" => $textos["AYUDA_DESCRIPCION"],"onBlur" => "validarItem(this);"))
            ),       
	    array(
		HTML::campoTextoCorto("*orden", $textos["ORDEN"], 6, 4,  $datos->orden, array("title" => $textos["AYUDA_ORDEN"],"onKeyPress" => "return campoEntero(event)"))
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

    /*** Validar nmbre ***/
    if ($url_item == "codigo") {
        $existe = SQL::existeItem("grupos", "codigo", $url_valor, "codigo != $url_id AND codigo !='0'");

        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_CODIGO"]);
        }
    }

    if ($url_item == "descripcion") {
        $existe = SQL::existeItem("grupos", "descripcion", $url_valor, "codigo != $url_id AND descripcion !=''");

        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_DESCRIPCION"]);
        }
    }

/*** Modificar el elemento seleccionado ***/
} elseif (!empty($forma_procesar)) {

/*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];
    
   if(empty($forma_codigo)){
		$error   = true;
        $mensaje = $textos["CODIGO_VACIO"];
        
	}elseif(empty($forma_descripcion)){
        $error   = true;
        $mensaje = $textos["DESCRIPCION_VACIO"];
        
    }elseif(empty($forma_orden)){
        $error   = true;
        $mensaje = $textos["ORDEN_VACIO"];
    
    }elseif($existe = SQL::existeItem("grupos", "codigo", $forma_codigo, "codigo != $forma_id")){
	    $error   = true;
        $mensaje = $textos["ERROR_EXISTE_CODIGO"];
        
    }elseif($existe = SQL::existeItem("grupos", "descripcion", $forma_descripcion, "codigo != $forma_id")){
	    $error   = true;
        $mensaje = $textos["ERROR_EXISTE_DESCRIPCION"];
        
    }else{
		/*** Insertar datos ***/
		$datos = array(
			"codigo"      => $forma_codigo,
			"descripcion" => $forma_descripcion,
			"orden"	      => $forma_orden
		);
		$consulta = SQL::modificar("grupos", $datos, "codigo = '$forma_id'");

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

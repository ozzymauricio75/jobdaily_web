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
* modificarlo  bajo los terminos de la Licencia Publica General GNU
* publicada por la Fundacion para el Software Libre, ya sea la version 3
* de la Licencia, o (a su eleccion) cualquier version posterior.
*
* Este programa se distribuye con la esperanza de que sea util, pero
* SIN GARANTIA ALGUNA; ni siquiera la garantia implicita MERCANTIL o
* de APTITUD PARA UN PROPOSITO DETERMINADO. Consulte los detalles de
* la Licencia Publica General GNU para obtener una informacion mas
* detallada.
*
* Deberia haber recibido una copia de la Licencia Publica General GNU
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
**/

/*** Devolver datos para autocompletar la búsqueda ***/
if (isset($url_completar)) {
    if (($url_item) == "selector1") {
		echo SQL::datosAutoCompletar("aficiones", $url_q);
    }
    if (($url_item) == "selector2") {
		echo SQL::datosAutoCompletar("aficiones", $url_q);
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
        $vistaConsulta = "motivos_incapacidad";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo = '$url_id'");
        $datos         = SQL::filaEnObjeto($consulta);
        $error         = "";
        $titulo        = $componente->nombre;
		
	      /*** Definición de pestaña personal ***/
	      $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::campoTextoCorto("codigo", $textos["CODIGO"], 5, 3, $datos->codigo, array("title" => $textos["AYUDA_MOTIVO"], "onblur" => "validarItem(this);")),
                HTML::campoTextoCorto("descripcion", $textos["DESCRIPCION"], 25, 255, $datos->descripcion, array("title" => $textos["AYUDA_NOMBRE_MOTIVO"], "onblur" => "validarItem(this);"))
	          )
	
        );

	      /*** Definición de botones ***/
	      $botones = array(
	          HTML::boton("botonAceptar", $textos["ACEPTAR"], "modificarItem('$url_id');", "aceptar")
	      );

	      $contenido = HTML::generarPestanas($formularios, $botones);

    }

    /*** Enviar datos para la generación del formulario al script que originó la petición ***/
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

/*** Validar los datos provenientes del formulario ***/
} elseif (!empty($url_validar)) {

    if($url_item == "codigo"){
        $existe = SQL::existeItem("motivos_incapacidad","codigo",$url_valor,"codigo != 0 AND codigo != '".$url_id."'");
        if($existe){
            HTTP::enviarJSON($textos["ERROR_EXISTE_MOTIVO"]);
        }
    }else if($url_item == "descripcion"){
        $existe = SQL::existeItem("motivos_incapacidad","descripcion",$url_valor,"codigo != 0 AND codigo != '".$url_id."'");
        if($existe){
            HTTP::enviarJSON($textos["ERROR_EXISTE_NOMBRE_MOTIVO"]);
        }
    }
    
} elseif (!empty($forma_procesar)) {

    $error   = false;
    $mensaje = $textos["ITEM_MODIFICADO"];
    
    $codigo  = (int)$forma_codigo;
    
    if(empty($forma_codigo) || $codigo == 0){
        $error = true;
        $mensaje = $textos["ERROR_CODIGO_VACIO"];
    } else if(empty($forma_descripcion)){
        $error = true;
        $mensaje = $textos["ERROR_DESCRIPCION_VACIO"];
    } else if(SQL::existeItem("motivos_incapacidad","codigo",$forma_codigo,"codigo != 0 AND codigo != '".$forma_id."'")){
        $error = true;
        $mensaje = $textos["ERROR_EXISTE_MOTIVO"];
    } else if(SQL::existeItem("motivos_incapacidad","descripcion",$forma_descripcion,"codigo != 0 AND codigo != '".$forma_id."'")){
        $error = true;
        $mensaje = $textos["ERROR_EXISTE_NOMBRE_MOTIVO"];
    } else {
        
	      $datos = array (
            "codigo"	     => $forma_codigo,
            "descripcion" => $forma_descripcion
	      );
	  
        $modificar = SQL::modificar("motivos_incapacidad", $datos, "codigo = '".$forma_id."'");

        if (!$modificar) {
            $error   = true;
            $mensaje = $textos["ERROR_MODIFICAR_ITEM"];
            $mensaje = mysql_error();
        }
    }
       
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

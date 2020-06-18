<?php

/**
*
* Copyright (C) 2020 Jobdaily
* Raul Mauricio Oidor Lozano <ozzymauricio75@gmail.com>
*
* Este archivo es parte de:
* Jobdaily :: Software empresarial a la medida
*
* Este programa es software libre: usted puede redistribuirlo y/o
* modificarlo  bajo los terminos de la Licencia Publica General GNU
* publicada por la Fundacion para el Software Libre, ya sea la versiï¿½n 3
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

/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {

     /*** Verificar que se haya enviado el ID del elemento a modificar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_MODIFICAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {
        $error  = "";
        $titulo = $componente->nombre;
        
        $vistaConsulta = "planillas";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo = '$url_id'");
        $datos         = SQL::filaEnObjeto($consulta);
    
    if($datos->periodo_pago == 1){
        $pagos_mensual  = true;
        $pagos_quincenal= false;
        $pagos_semanal  = false;
    }
    
    elseif($datos->periodo_pago == 2){
        $pagos_mensual  = false;
        $pagos_quincenal= true;
        $pagos_semanal  = false;
    }
    
    else{
        $pagos_mensual  = false;
        $pagos_quincenal= false;
        $pagos_semanal  = true;
    }
    	
	/*** Definición de pestaña personal ***/
	$formularios["PESTANA_GENERAL"] = array(
        array(
           HTML::campoTextoCorto("*codigo", $textos["CODIGO"], 5, 3, $datos->codigo, array("title" => $textos["AYUDA_PLANILLA"], "onblur" => "validarItem(this);"))
           .HTML::campoOculto("llave_principal",$datos->codigo)
        ),     
        array( 
            HTML::campoTextoCorto("*descripcion", $textos["DESCRIPCION"], 25, 255, $datos->descripcion, array("title" => $textos["AYUDA_DESCRIPCION"], "onblur" => "validarItem(this);")),
         ),
        array(
            HTML::mostrarDato("periodo_pago", $textos["PERIODO_PAGO"], "")
        ),
        array(
            HTML::marcaSeleccion("pagos", $textos["MENSUAL"], 1, $pagos_mensual, array("id" => "mensual")),
            HTML::marcaSeleccion("pagos", $textos["QUINCENAL"], 2, $pagos_quincenal, array("id" => "quincenal")),
            HTML::marcaSeleccion("pagos", $textos["SEMANAL"], 3, $pagos_semanal, array("id" => "semanal"))
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

    /*** Validar numero de documento ***/
    if (isset($url_valor)) {
        $existe = SQL::existeItem("planillas", "descripcion", $url_valor, "codigo != '$url_id'");
        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_PLANILLA"]);
        } 
    }
    
/*** Modificar el elemento seleccionado ***/
} elseif (!empty($forma_procesar)) {
    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_MODIFICADO"];
    
    if (empty($forma_descripcion) || (empty($forma_pagos)) || (empty($forma_codigo))){
        $error = true;
        $mensaje = $textos["ERROR_DATOS_INCOMPLETOS"];
    }elseif($existe = SQL::existeItem("planillas", "codigo", $forma_codigo,"codigo != '$forma_llave_principal' AND codigo != ''")){
        $error = true;
        $mensaje = $textos["ERROR_EXISTE_PLANILLA"];
    
    }elseif($existe = SQL::existeItem("planillas", "descripcion", $forma_descripcion,"codigo != '$forma_llave_principal' AND descripcion != ''")){
        $error = true;
        $mensaje = $textos["ERROR_EXISTE_DESCRIPCION"];

    } else {
        
        $datos = array (
            "codigo"     	 => $forma_codigo,           
            "descripcion"	 => $forma_descripcion,
            "periodo_pago" => $forma_pagos	     
        );
	  
        $modificar = SQL::modificar("planillas", $datos, "codigo = '$forma_llave_principal'");

	      /*** Error de modificacion ***/
	      if (!$modificar) {
	          $error   = true;
	          $mensaje = $textos["ERROR_MODIFICAR_ITEM"];
	          $mensaje = mysql_error();
	      }
    }
       
    /*** Enviar datos con la respuesta del proceso al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>


<?php

/**
*
* Copyright (C) 2008 Sistemas de Apoyo Empresarial Ltda
* 
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
    $error         = "";
		$titulo        = $componente->nombre;
        
		$vistaConsulta = "motivos_retiro";
    $columnas      = SQL::obtenerColumnas($vistaConsulta);
    $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo = '$url_id'");
    $datos         = SQL::filaEnObjeto($consulta);
        
    if($datos->indemniza == 0){
        $indemniza_1 = true;
        $indemniza_2 = false;
    }else{
        $indemniza_1 = false;
        $indemniza_2 = true;
    }
		/*** Definición de pestaña personal ***/
		$formularios["PESTANA_GENERAL"] = array(
        array(
            HTML::campoTextoCorto("*codigo", $textos["CODIGO_MOTIVO_RETIRO"], 5, 4, $datos->codigo, array("title" => $textos["AYUDA_MOTIVO_RETIRO"], "onblur" => "validarItem(this);","onKeypress" => "return campoDecimal(event)"))
        ),
        array(
            HTML::campoTextoCorto("*descripcion", $textos["NOMBRE_MOTIVO_RETIRO"], 25, 50, $datos->descripcion, array("title" => $textos["AYUDA_NOMBRE_MOTIVO_RETIRO"], "onblur" => "validarItem(this);"))
        ),
        array(
            HTML::mostrarDato("genera_indemnizacion", $textos["INDEMNIZACION"], "")
        ),
        array(
            HTML::marcaSeleccion("indemniza", $textos["INDEMNIZACION_NO"], 0,$indemniza_1),
            HTML::marcaSeleccion("indemniza", $textos["INDEMNIZACION_SI"], 1,$indemniza_2)
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

} elseif (!empty($url_validar)) {

    if ($url_item == "codigo" && $url_valor) {
        $existe_codigo = SQL::existeItem("motivos_retiro", "codigo", $url_valor,"codigo != 0 AND codigo !='".$url_id."'");
        if ($existe_codigo) {
	    HTTP::enviarJSON($textos["ERROR_EXISTE_MOTIVO_RETIRO"]);
        } 
    }
    else if ($url_item == "descripcion" && $url_valor) {
        $existe_descripcion = SQL::existeItem("motivos_retiro", "descripcion", $url_valor,"codigo != 0 AND codigo !='".$url_id."'");
        if ($existe_descripcion) {
	    HTTP::enviarJSON($textos["ERROR_EXISTE_DESCRIPCION"]);
        } 
    }
} elseif (!empty($forma_procesar)) {

    $error   = false;
    $mensaje = $textos["ITEM_MODIFICADO"];
    
    $codigo = (int)$forma_codigo;
    
    if (empty($forma_codigo) || empty($forma_descripcion) || $codigo == 0){
        $error = true;
        $mensaje = $textos["ERROR_DATOS_INCOMPLETOS"];
    } elseif (SQL::existeItem("motivos_retiro", "codigo", $forma_codigo,"codigo != 0 AND codigo !='".$forma_id."'")) {
        $error   = true;
        $mensaje =  $textos["ERROR_EXISTE_MOTIVO_RETIRO"];            
    } elseif (SQL::existeItem("motivos_retiro", "descripcion", $forma_descripcion,"codigo != 0 AND codigo !='".$forma_id."'")) {
        $error   = true;
        $mensaje =  $textos["ERROR_EXISTE_DESCRIPCION"];            
    } else {

        $datos = array (
            "codigo"	  	=> $forma_codigo,
            "descripcion"	=> $forma_descripcion,
            "indemniza"	  => $forma_indemniza
        );
        $modifica = SQL::modificar("motivos_retiro", $datos, "codigo = '".$forma_id."'");

        if (!$modifica) {
            $error   = true;
            $mensaje = $textos["ERROR_MODIFICAR_ITEM"];
        }
    }
    
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>


<?php

/**
*
* Copyright (C) 2008 Sistemas de Apoyo Empresarial Ltda
* 
*
* Este archivo es parte de:
* PANCE :: Plataforma para la Administraci�n del Nexo Cliente-Empresa
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
		/*** Definici�n de pesta�a personal ***/
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


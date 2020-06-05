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
    $error  = "";
    $titulo = $componente->nombre;

    $consecutivo = (int)SQL::obtenerValor("aficiones","max(codigo)","");
    if($consecutivo){
        $consecutivo++;
    }else{
        $consecutivo=1;
    }

    /*** Definici�n de pesta�a personal ***/
    $formularios["PESTANA_GENERAL"] = array(
        array(
            HTML::campoTextoCorto("*codigo", $textos["CODIGO_AFICION"], 5, 4, $consecutivo, array("title" => $textos["AYUDA_AFICION"], "onblur" => "validarItem(this);","onKeyPress" => "return campoEntero(event)"))
        ),
        array(
            HTML::campoTextoCorto("*descripcion", $textos["DESCRIPCION_AFICION"], 25, 255, "", array("title" => $textos["AYUDA_NOMBRE_AFICION"], "onblur" => "validarItem(this);"))
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

/*** Validar los datos provenientes del formulario ***/
} elseif (!empty($url_validar)) {

    /*** Validar numero de documento ***/
    if ($url_item == "codigo" && $url_valor) {
        $existe = SQL::existeItem("aficiones", "codigo", $url_valor);
        if($existe){
            HTTP::enviarJSON($textos["ERROR_EXISTE_AFICION"]);
        }
    } 
    if ($url_item == "descripcion" && $url_valor) {            
        $existe_nombre = SQL::existeItem("aficiones", "descripcion", $url_valor);         
        if($existe_nombre){
            HTTP::enviarJSON($textos["ERROR_EXISTE_NOMBRE_AFICION"]);
        } 
    }

/*** Adicionar los datos provenientes del formulario ***/
} elseif (!empty($forma_procesar)) {
    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];
    
    if (empty($forma_codigo) || (empty($forma_descripcion))){
        $error = true;
        $mensaje = $textos["ERROR_DATOS_INCOMPLETOS"];
    
    }elseif($existe = SQL::existeItem("aficiones", "descripcion", $forma_descripcion)){
        $error = true;
        $mensaje = $textos["ERROR_EXISTE_NOMBRE_AFICION"];
    
    } else {
		
    	$datos = array (
    	    "codigo"      => $forma_codigo,
    	    "descripcion" => $forma_descripcion
    	);
    	  
    	$insertar = SQL::insertar("aficiones", $datos);
    
    	/*** Error de inserc�n ***/
    	if (!$insertar) {
    	    $error   = true;
    	    $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
    	    $mensaje = mysql_error();
    	}
    }
       
    /*** Enviar datos con la respuesta del proceso al script que origin� la petici�n ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

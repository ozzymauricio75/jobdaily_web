<?php

/***
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
***/

/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {
    $error  = "";
    $titulo = $componente->nombre;
    
    /*** Definicion de pestañas general ***/
    $formularios["PESTANA_GENERAL"] = array(
        array(            
            HTML::campoTextoCorto("*nombre_tabla", $textos["NOMBRE_TABLA"], 40, 255, "", array("title" => $textos["AYUDA_NOMBRE_TABLA"], "onSubmit" => "adicionarItem();"))
        )
    );

    /*** Definicion de botones ***/
    $botones = array(
        HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar")
    );

    $contenido = HTML::generarPestanas($formularios, $botones);

    /*** Enviar datos para la generacion del formulario al script que origino la peticion ***/
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

} elseif (!empty($forma_procesar)) {

    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];

    if(empty($forma_nombre_tabla)){
	    $error   = true;
		$mensaje = $textos["ERROR_NOMBRE_VACIO"];
		
	}elseif($existe = SQL::existeItem("tablas", "nombre_tabla", $forma_nombre_tabla)){
	     $error   = true;
         $mensaje = $textos["ERROR_EXISTE_NOMBRE_TABLA"];   
         
    }else{
		$datos = array(
			"nombre_tabla" => $forma_nombre_tabla
		);

		$insertar = SQL::insertar("tablas", $datos);

		/*** Error de insercion ***/
		if (!$insertar) {
				 $error   = true;
				 $mensaje = $textos["ERROR_ADICIONAR_ITEM"];		
		}	
	}
    
    /*** Enviar datos con la respuesta del proceso al script que origin? la petici?n ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

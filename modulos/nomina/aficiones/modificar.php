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
* de APTITUD PARA UN PROP�ITO DETERMINADO. Consulte los detalles de
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
        $error  = "";
        $titulo = $componente->nombre;
        
        $vistaConsulta = "aficiones";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo = '$url_id'");
        $datos         = SQL::filaEnObjeto($consulta);
            
	      /*** Definici�n de pesta�a personal ***/
	      $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::campoTextoCorto("codigo", $textos["CODIGO_AFICION"], 5, 4, $datos->codigo, array("title" => $textos["AYUDA_AFICION"], "onblur" => "validarItem(this);","onKeyPress" => "return campoEntero(event)")),
                HTML::campoOculto("llave_principal", $datos->codigo)
            ),
            array(
                HTML::campoTextoCorto("descripcion", $textos["DESCRIPCION_AFICION"], 25, 255, $datos->descripcion, array("title" => $textos["AYUDA_NOMBRE_AFICION"], "onblur" => "validarItem(this);"))
            )	
        );

	      /*** Definici�n de botones ***/
	      $botones = array(
	          HTML::boton("botonAceptar", $textos["ACEPTAR"], "modificarItem('$url_id');", "aceptar")
	      );

	      $contenido = HTML::generarPestanas($formularios, $botones);

    }

    /*** Enviar datos para la generaci�n del formulario al script que origin� la petici�n ***/
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

/*** Validar los datos provenientes del formulario ***/
} elseif (!empty($url_validar)) {

    /*** Validar numero de documento ***/
    if (isset($url_valor)) {
        $existe            = SQL::existeItem("aficiones", "codigo", $url_valor, "codigo != '$url_id'");     
        $existeDescripcion = SQL::existeItem("aficiones", "descripcion", $url_valor, "codigo != '$url_id'");
        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_AFICION"]);
        } 
        if ($existeDescripcion) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_NOMBRE_AFICION"]);
        } 
    }
    
/*** Modificar el elemento seleccionado ***/
} elseif (!empty($forma_procesar)) {
    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_MODIFICADO"];
    
    if(empty($forma_codigo) || (empty($forma_descripcion))){
        $error = true;
        $mensaje = $textos["ERROR_DATOS_INCOMPLETOS"];       
    } elseif (SQL::existeItem("aficiones", "descripcion", $forma_descripcion)) {
        $error   = true;
        $mensaje =  $textos["ERROR_EXISTE_NOMBRE_AFICION"];            
    } else {
        /*** Insertar datos ***/	
        $datos = array (
            "codigo"	  => $forma_codigo,
            "descripcion" => $forma_descripcion
        );
        $modificar = SQL::modificar("aficiones", $datos, "codigo = '$forma_llave_principal'");

        /*** Error de modificacion ***/
        if (!$modificar) {
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


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


if(isset($url_validarItemsllaves))
{
   if($url_item=="numero_ica" && !empty($url_valor)){


      // echo var_dump($url_valor);
       $existe = SQL::existeItem("seleccion_resoluciones_reteica","id",$url_valor);
        if ($existe) {
            $mensaje = $textos["ERROR_EXISTE_NUMERO"];
            HTTP::enviarJSON($mensaje);
        }
    }
}




$codigo_items_llave_primaria="numero_ica|id_sucursal"; //tene en cuenta que el orden se arma de acuerdo a la vista

/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {
    $error  = "";
    $titulo = $componente->nombre;
    
    $consulta_sucursales = SQL::seleccionar(array("sucursales"),array("*"),"codigo>0");
    if (SQL::filasDevueltas($consulta_sucursales)){

        /*** Definici�n de pesta�as general ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::listaSeleccionSimple("*id_sucursal", $textos["SUCURSAL"], HTML::generarDatosLista("sucursales", "codigo", "nombre","codigo!=0"), "", array("title" => $textos["AYUDA_SUCURSAL"]))
            ),
            array(
                HTML::campoTextoCorto("*numero_ica", $textos["NUMERO"], 20, 20, "", array("title" => $textos["AYUDA_NUMERO"], "onKeyPress" => "return campoEntero(event)","onBlur" => "validarItemsllaves(this,'$codigo_items_llave_primaria');"))
             ),
            array(
                HTML::campoTextoCorto("*fecha", $textos["FECHA"], 10, 10, date("Y-m-d"), array("class" => "fechaNuevas", "title" => $textos["AYUDA_FECHA"]))
            )
        );

        /*** Definici�n de botones ***/
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones);
    } else {
        $error = $textos["CREAR_SUCURSALES"];
        $contenido = "";
    }

    /*** Enviar datos para la generaci�n del formulario al script que origin� la petici�n ***/
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);
    
/*** validacion de los datos ***/
} elseif (!empty($forma_procesar)) {
    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];

    $condicion = "numero_resolucion=$forma_numero_ica AND codigo_sucursal=$forma_id_sucursal AND numero_resolucion !=''";

    

    if(empty($forma_id_sucursal)){
		$error   = true;
		$mensaje = $textos["SUCURSAL_VACIO"];
	}elseif(empty($forma_numero_ica)){
		$error   = true;
		$mensaje = $textos["NUMERO_VACIO"];
	}elseif(empty($forma_fecha)){
		$error   = true;
		$mensaje = $textos["FECHA_VACIO"];
    }elseif(SQL::existeItem("resoluciones_ica","numero_resolucion",$forma_numero_ica,$condicion)){
	    $error   = true;
        $mensaje = $textos["ERROR_EXISTE_NUMERO"];
    } else {
		/*** Insertar datos ***/
        $datos = array(
            "codigo_sucursal"   => $forma_id_sucursal,
            "numero_resolucion" => $forma_numero_ica,
            "fecha"             => $forma_fecha
        );
        $insertar = SQL::insertar("resoluciones_ica", $datos);

        /*** Error de inserci�n ***/
        if (!$insertar) {
            $error   = true;
            $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
        }
    }
    /*** Enviar datos con la respuesta del proceso al script que origin� la petici�n ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

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

        /*** Definición de pestañas general ***/
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

        /*** Definición de botones ***/
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones);
    } else {
        $error = $textos["CREAR_SUCURSALES"];
        $contenido = "";
    }

    /*** Enviar datos para la generación del formulario al script que originó la petición ***/
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

        /*** Error de inserción ***/
        if (!$insertar) {
            $error   = true;
            $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
        }
    }
    /*** Enviar datos con la respuesta del proceso al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

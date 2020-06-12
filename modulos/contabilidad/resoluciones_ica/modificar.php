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
* de APTITUD PARA UN PROPÓSITO DETERMINADO. Consulte los detalles de
* la Licencia Pública General GNU para obtener una información más
* detallada.
*
* Debería haber recibido una copia de la Licencia Pública General GNU
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
**/

/*** Generar el formulario para la captura de datos ***/



if(isset($url_validarItemsllaves))
{
   if($url_item=="numero" && !empty($url_valor)){

       $existe = SQL::existeItem("seleccion_resoluciones_reteica","id",$url_valor,"id !='$url_id'");
        if ($existe) {
            $mensaje = $textos["ERROR_EXISTE_NUMERO"];
            HTTP::enviarJSON($mensaje);
        }
    }
}


if (!empty($url_generar)) {

    /*** Verificar que se haya enviado el ID del elemento a modificar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_MODIFICAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {
        $vistaConsulta = "buscador_resoluciones_ica";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "id = '$url_id'");
        $datos         = SQL::filaEnObjeto($consulta);
        $error         = "";
        $titulo        = $componente->nombre;

        $codigo_items_llave_primaria="numero|id_sucursal"; //tene en cuenta que el orden se arma de acuerdo a la vista

        /*** Definición de pestañas general ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::listaSeleccionSimple("*id_sucursal", $textos["SUCURSAL"], HTML::generarDatosLista("sucursales", "codigo", "nombre","codigo!=0"), $datos->codigo_sucursal, array("title" => $textos["AYUDA_SUCURSAL"]))
            ),
            array(
                HTML::campoTextoCorto("*numero", $textos["NUMERO"], 8, 8, $datos->numero, array("title" => $textos["AYUDA_NUMERO"],"onBlur" => "validarItemsllaves(this,'$codigo_items_llave_primaria','$url_id');"))
            ),
            array(
                HTML::campoTextoCorto("*fecha", $textos["FECHA"], 10, 10, $datos->fecha, array("class" => "fechaNuevas", "title" => $textos["AYUDA_FECHA"], "onBlur" => "validarItem(this);"))
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

}  elseif (!empty($forma_procesar)) {
    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_MODIFICADO"];

     $llave_principal_ica = explode("|",$forma_id);
     $numero_resolucion   = $llave_principal_ica[0];
     $codigo_sucursal     = $llave_principal_ica[1];

     $condicion = "numero_resolucion=$numero_resolucion AND codigo_sucursal=$codigo_sucursal";
     $llave_primaria =$numero_resolucion."|".$codigo_sucursal;
     $llave_primaria_dada = $forma_numero."|".$forma_id_sucursal;

    
    // echo var_dump($llave_primaria."  == ".$llave_primaria_dada);

    if(empty($forma_id_sucursal)){
		$error   = true;
		$mensaje = $textos["SUCURSAL_VACIO"];
	}elseif(empty($forma_numero)){
		$error   = true;
		$mensaje = $textos["NUMERO_VACIO"];
	}elseif(empty($forma_fecha)){
		$error   = true;
		$mensaje = $textos["FECHA_VACIO"];
    }elseif(SQL::existeItem("seleccion_resoluciones_reteica","id",$llave_primaria_dada,"id !='$llave_primaria'")){
	    $error   = true;
        $mensaje = $textos["ERROR_EXISTE_NUMERO"];
    } else {
		/*** Insertar datos ***/
        $datos = array(
            "codigo_sucursal"    => $forma_id_sucursal,
            "numero_resolucion"  => $forma_numero,
            "fecha"              => $forma_fecha
        );

        
        
        $insertar = SQL::modificar("resoluciones_ica", $datos,$condicion);
		
		/*** Error inserción ***/
        if ($insertar) {
            $error   = false;
            $mensaje = $textos["ITEM_MODIFICADO"];
        } else {
            $error   = true;
            $mensaje = $textos["ERROR_MODIFICAR_ITEM"];
        }
    }
    /*** Enviar datos con la respuesta del proceso al script que originï¿½ la peticiï¿½n ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

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
* de APTITUD PARA UN PROPÓITO DETERMINADO. Consulte los detalles de
* la Licencia Pública General GNU para obtener una información más
* detallada.
*
* Debería haber recibido una copia de la Licencia Pública General GNU
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
**/

/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {

    /*** Verificar que se haya enviado el ID del elemento a eliminar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_ELIMINAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {
        $vistaConsulta = "unidades";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo = '$url_id'");
        $datos         = SQL::filaEnObjeto($consulta);
        
        $error         = "";
        $titulo        = $componente->nombre;

        /*** Obtener valores de la tabla ***/
        $factor_conversion       = SQL::obtenerValor("unidades","factor_conversion","codigo = '$url_id'");
        $codigo_unidad_principal = SQL::obtenerValor("unidades","codigo_unidad_principal","codigo = '$url_id'");
        $tipo_unidad             = SQL::obtenerValor("tipos_unidades","nombre","codigo='".$datos->codigo_tipo_unidad."'");

        if ($codigo_unidad_principal == 0) {
            $texto = array( 
                HTML::mostrarDato("codigo_unidad_base", $textos["PRINCIPAL"], $textos["SI_NO_1"])
            );
        } else {
            $texto = array ( 
                HTML::mostrarDato("codigo_unidad_base", $textos["UNIDAD_BASE"], SQL::obtenerValor("unidades","nombre","codigo = '$codigo_unidad_principal'")),
                HTML::mostrarDato("factor_conversion", $textos["FACTOR_CONVERSION"], $factor_conversion)
            );
        }

        /*** Definición de pestañas ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::mostrarDato("tipo_unidad", $textos["TIPO_UNIDAD"], $tipo_unidad)
            ),
            array(
                HTML::mostrarDato("codigo", $textos["CODIGO"], $datos->codigo)
            ),
            array(
                HTML::mostrarDato("nombre", $textos["NOMBRE"], $datos->nombre)
            ),
            $texto
        );

        /*** Definición de botones ***/
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "eliminarItem('$url_id');", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones);
    }

    /*** Enviar datos para la generaciï¿½n del formulario al script que originï¿½ la peticiï¿½n ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

/*** Eliminar el elemento seleccionado ***/
} elseif (!empty($forma_procesar)) {

    $consulta = SQL::seleccionar(array("unidades"),array("*"),"codigo_unidad_principal='$forma_id'","","",0,1);
    
    if (SQL::filasDevueltas($consulta)){
        $datos   = SQL::filaEnObjeto($consulta);
        $nombre  = $datos->nombre;
        $error   = true;
        $mensaje = $textos["ERROR_ELIMINAR"]." ".$nombre;
    } else {

        $consulta = SQL::eliminar("unidades", "codigo = '$forma_id'");

        if ($consulta) {
            $error   = false;
            $mensaje = $textos["ITEM_ELIMINADO"];
        } else {
            $error   = true;
            $mensaje = $textos["ERROR_ELIMINAR_ITEM"];
        }
    }

    /*** Enviar datos con la respuesta del proceso al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

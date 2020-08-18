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
if (!empty($url_generar)) {

    /*** Verificar que se haya enviado el ID del elemento a consultar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_CONSULTAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {
        $vistaConsulta  = "correspondencia";
        $columnas       = SQL::obtenerColumnas($vistaConsulta);
        $consulta       = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo = '$url_id'");
        $datos          = SQL::filaEnObjeto($consulta);
        $valor_proyecto = number_format($datos->valor_proyecto,0);
        $estado         = $datos->estado;

        if (($estado!=1 && $estado!=3)) {
            /*Obtener Valores*/
            $codigo_proyecto               = $datos->codigo_proyecto;
            $documento_identidad_proveedor = $datos->documento_identidad_proveedor;
            $codigo_tipo_documento         = $datos->codigo_tipo_documento;
            $numero_documento_proveedor    = $datos->numero_documento_proveedor;
            $valor_documento               = number_format($datos->valor_documento,0);
            $fecha_recepcion               = $datos->fecha_recepcion;
            $fecha_vencimiento             = $datos->fecha_vencimiento;
            $fecha_envio                   = $datos->fecha_envio; 
            $observaciones                 = $datos->observaciones;
            $estado                        = $datos->estado;
            $numero_orden_compra           = $datos->numero_orden_compra;

            $nombre_tipo_documento = SQL::obtenerValor("tipos_documentos","descripcion", "codigo = '$codigo_tipo_documento'");
            $nombre_proyecto       = SQL::obtenerValor("proyectos","nombre", "codigo = '$codigo_proyecto'");
            
            $tipo_persona          = SQL::obtenerValor("terceros", "tipo_persona", "documento_identidad = '".$documento_identidad_proveedor."'");
                if(($tipo_persona==1)||($tipo_persona==3)){
                    $primer_nombre    = SQL::obtenerValor("terceros", "primer_nombre", "documento_identidad = '".$documento_identidad_proveedor."'");
                    $segundo_nombre   = SQL::obtenerValor("terceros", "segundo_nombre", "documento_identidad = '".$documento_identidad_proveedor."'");
                    $primer_apellido  = SQL::obtenerValor("terceros", "primer_apellido", "documento_identidad = '".$documento_identidad_proveedor."'");
                    $segundo_apellido = SQL::obtenerValor("terceros", "segundo_apellido", "documento_identidad = '".$documento_identidad_proveedor."'");
                    $razon_social     = $primer_nombre." ".$segundo_nombre." ".$primer_apellido." ".$segundo_apellido;
                }else{
                    $razon_social     = SQL::obtenerValor("terceros","razon_social", "documento_identidad = '$documento_identidad_proveedor'");
                }

            $error  = "";
            $titulo = $componente->nombre;

            /*** Definición de pestañas general ***/
            $formularios["PESTANA_GENERAL"] = array(
                array(
                    HTML::mostrarDato("proyecto", $textos["PROYECTO"], $nombre_proyecto),
                    HTML::mostrarDato("orden_compra", $textos["ORDEN_COMPRA"], $numero_orden_compra)
                ),
                array(
                    HTML::mostrarDato("nit_proveedor", $textos["NIT_PROVEEDOR"], $documento_identidad_proveedor),
                    HTML::mostrarDato("razon_social", $textos["RAZON_SOCIAL"], $razon_social),
                ),
                array(
                    HTML::mostrarDato("tipo_documento", $textos["TIPO_DOCUMENTO"], $nombre_tipo_documento),
                    HTML::mostrarDato("numero_documento", $textos["DOCUMENTO_SOPORTE"], $numero_documento_proveedor),
                    HTML::mostrarDato("valor_documento", $textos["VALOR_DOCUMENTO"], "$".$valor_documento),
                ),
                array(
                    HTML::mostrarDato("estado", $textos["ESTADO"], $textos["ESTADO_".$estado])
                ),
                array(
                    HTML::mostrarDato("fecha_recepcion", $textos["FECHA_RECEPCION"], $fecha_recepcion),
                    HTML::mostrarDato("fecha_vencimiento", $textos["FECHA_VENCIMIENTO"], $fecha_vencimiento),
                    HTML::mostrarDato("fecha_envio", $textos["FECHA_ENVIO"], $fecha_envio)
                ),
                array(
                    HTML::mostrarDato("observaciones", $textos["OBSERVACIONES"], $observaciones)
                )
            );

             /*** Definición de botones ***/
            $botones = array(
                HTML::boton("botonAceptar", $textos["ACEPTAR"], "eliminarItem('$url_id');", "aceptar")
            );
            
            $contenido = HTML::generarPestanas($formularios, $botones);
        
        } else {
            if ($estado) {
                $error = $textos["ERROR_ORDEN_ESTADO"];
            } 
            $titulo    = "";
            $contenido = "";
        }
    }

    /*** Enviar datos para la generación del formulario al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

} elseif (!empty($forma_procesar)) {
    $consulta = SQL::eliminar("correspondencia", "codigo = '$forma_id'");

    if ($consulta) {
        $error   = false;
        $mensaje = $textos["ITEM_ELIMINADO"];
    } else {
        $error   = true;
        $mensaje = $textos["ERROR_ELIMINAR_ITEM"];
    }

    /*** Enviar datos con la respuesta del proceso al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

<?php

/**
*
* Copyright (C) 2020 Jobdaily
*
* Este archivo es parte de:
* Jobdaily:: Sofware empresarial a la medida
*
* Este programa es software libre: usted puede redistribuirlo y/o
* modificarlo  bajo los terminos de la Licencia Publica General GNU
* publicada por la Fundacion para el Software Libre, ya sea la version 3
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

    /*** Verificar que se haya enviado el ID del elemento a consultar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_CONSULTAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {
        $vistaConsulta  = "aprobaciones";
        $columnas       = SQL::obtenerColumnas($vistaConsulta);
        $consulta       = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo = '$url_id'");
        $datos          = SQL::filaEnObjeto($consulta);
        
        $vistaConsultaCorrespondencia  = "correspondencia";
        $condicionCorrespondencia      = "codigo_aprobaciones = '$url_id'";
        $columnasCorrespondencia       = SQL::obtenerColumnas($vistaConsultaCorrespondencia);
        $consultaCorrespondencia       = SQL::seleccionar(array($vistaConsultaCorrespondencia), $columnasCorrespondencia, $condicionCorrespondencia);
        $datosCorrespondencia          = SQL::filaEnObjeto($consultaCorrespondencia);

        $fecha_recepcion               = $datosCorrespondencia->fecha_recepcion;
        $fecha_vencimiento             = $datosCorrespondencia->fecha_vencimiento;
        $fecha_envio                   = $datosCorrespondencia->fecha_envio;
        $estado                        = $datosCorrespondencia->estado;

        /*Obtener Valores*/
        $codigo_proyecto               = $datos->codigo_proyecto;
        $documento_identidad_proveedor = $datos->documento_identidad_proveedor;
        $codigo_tipo_documento         = $datos->codigo_tipo_documento;
        $numero_documento_proveedor    = $datos->numero_documento_proveedor;
        $valor_documento               = number_format($datos->valor_documento,0);
        $fecha_registro_residente      = $datos->fecha_registro_residente;
        $fecha_registro_director       = $datos->fecha_registro_director; 
        $observaciones                 = $datos->observaciones;
        $estado_residente              = $datos->estado_residente;
        $estado_director               = $datos->estado_director;
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

        if ($estado_director==0 && $estado=='0' && $estado_residente==0) {
        
            /*** Definición de pestañas general ***/
            $formularios["PESTANA_GENERAL"] = array(
                array(
                    HTML::mostrarDato("proyecto", $textos["PROYECTO"], $nombre_proyecto),
                    HTML::mostrarDato("orden_compra", $textos["ORDEN_COMPRA"], $numero_orden_compra),
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
                    HTML::mostrarDato("estado_residente", $textos["ESTADO_RESIDENTE"], $textos["ESTADO_RESIDENTE_".$estado_residente]),
                    HTML::mostrarDato("estado_director", $textos["ESTADO_DIRECTOR"], $textos["ESTADO_DIRECTOR_".$estado_director])
                ),
                array(
                    HTML::mostrarDato("fecha_registro_residente", $textos["FECHA_REGISTRO_RESIDENTE"], $fecha_registro_residente),
                    HTML::mostrarDato("fecha_registro_director", $textos["FECHA_REGISTRO_DIRECTOR"], $fecha_registro_director)
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
            if ($estado_director==1) {
                $error = $textos["ERROR_ORDEN_ESTADO"];
            }elseif($datos_correspondencia->estado!='0') {
                $error = $textos["ERROR_ORDEN_ESTADOS"];
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

/*** Adicionar los datos provenientes del formulario ***/
} elseif (!empty($forma_procesar)) {
    /*** Asumir por defecto que no hubo error ***/
    $datos = array(
        "estado_residente" => "2"
    );
    
    $consulta_aprobaciones = SQL::modificar("aprobaciones", $datos, "codigo = '$forma_id'");

    if ($consulta_aprobaciones) {
        $datos_correspondencia = array(
            "estado" => "2"
        );
        
        $consulta_correspondencia = SQL::modificar("correspondencia", $datos_correspondencia, "codigo_aprobaciones = '$forma_id'");
        
        $error    = false;
        $mensaje  = $textos["ITEM_ANULADO"];
    } else {
        $error   = true;
        $mensaje = $textos["ERROR_ANULAR_ITEM"];
    }

    /*** Enviar datos con la respuesta del proceso al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

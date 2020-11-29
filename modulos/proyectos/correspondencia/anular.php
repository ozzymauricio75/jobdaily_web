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
        $vistaConsulta  = "correspondencia";
        $columnas       = SQL::obtenerColumnas($vistaConsulta);
        $consulta       = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo = '$url_id'");
        $datos          = SQL::filaEnObjeto($consulta);
        
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

        $error         = "";
        $titulo        = $componente->nombre;

        if (($estado==0) && ($estado_residente==0) && ($estado_director==0)) {
            /*** Definición de pestañas general ***/
            $formularios["PESTANA_GENERAL"] = array(
                array(
                    HTML::mostrarDato("proyecto", $textos["PROYECTO"], $nombre_proyecto),
                    HTML::mostrarDato("orden_compra", $textos["ORDEN_COMPRA"], $numero_orden_compra),
                ),
                array(
                    HTML::mostrarDato("nit_proveedor", $textos["NIT_PROVEEDOR"], $documento_identidad_proveedor)
                    .HTML::campoOculto("documento_identidad_proveedor", $documento_identidad_proveedor),
                    HTML::mostrarDato("razon_social", $textos["RAZON_SOCIAL"], $razon_social),
                ),
                array(
                    HTML::mostrarDato("tipo_documento", $textos["TIPO_DOCUMENTO"], $nombre_tipo_documento),
                    HTML::mostrarDato("numero_documento", $textos["DOCUMENTO_SOPORTE"], $numero_documento_proveedor),
                    HTML::mostrarDato("valor_documento", $textos["VALOR_DOCUMENTO"], "$".$valor_documento)
                    .HTML::campoOculto("numero_documento_proveedor", $numero_documento_proveedor)
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
            /*if ($estado) {
                $error = $textos["ERROR_ORDEN_ESTADO"];
            } elseif(($estado==0) && ($codigo_tipo_documento!=3) || ($codigo_tipo_documento!=4) || ($codigo_tipo_documento!=5)) {
                $error = $textos["ERROR_TIPO_DOCUMENTOS"];
            }*/
            $error     = $textos["ERROR_ORDEN_ESTADO_APROBADOS"];
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
    $consulta_documentos_cruzados = SQL::seleccionar(array("correspondencia"),array("codigo"),"documento_cruzado_por_factura = '$forma_numero_documento_proveedor' AND documento_identidad_proveedor='$forma_documento_identidad_proveedor'");

    if (SQL::filasDevueltas($consulta_documentos_cruzados)) {
        while ($documentos_cruzados = SQL::filaEnObjeto($consulta_documentos_cruzados)) {
            $codigo_documento = $documentos_cruzados->codigo;
            $datos_cruce = array(
                "documento_cruzado_por_factura" => ""
            );
            $modificar = SQL::modificar("correspondencia", $datos_cruce, "codigo = '$codigo_documento'");
        }
    }

    $datos = array(
        "estado" => "2"
    );
    
    $consulta_correspondencia = SQL::modificar("correspondencia", $datos, "codigo = '$forma_id'");

    if ($consulta_correspondencia) {
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

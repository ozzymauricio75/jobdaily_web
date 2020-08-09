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

        $error         = "";
        $titulo        = $componente->nombre;

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

        $contenido = HTML::generarPestanas($formularios);
    }

    /*** Enviar datos para la generación del formulario al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);
}
?>

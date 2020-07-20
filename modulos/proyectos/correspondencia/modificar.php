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

if (isset($url_completar)) {
    if (($url_item) == "selector1") {
        echo SQL::datosAutoCompletar("seleccion_municipios", $url_q);
    }
    exit;

}elseif (!empty($url_recargar)) {

    if ($url_elemento == "empresa") {
       $respuesta = HTML::generarDatosLista("empresas", "codigo", "nombre", "codigo = '$url_origen'");
    }

    if ($url_elemento == "sucursal") {
       $respuesta = HTML::generarDatosLista("sucursales", "codigo", "nombre", "codigo_empresa = '".$url_codigo."'");
    }

    HTTP::enviarJSON($respuesta);
}

/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {

    /*** Verificar que se haya enviado el ID del elemento a modificar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_MODIFICAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {
        $vistaConsulta  = "correspondencia";
        $condicion      = "codigo = '$url_id'";
        $columnas       = SQL::obtenerColumnas($vistaConsulta);
        $consulta       = SQL::seleccionar(array($vistaConsulta), $columnas, $condicion);
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

        $razon_social          = SQL::obtenerValor("terceros","razon_social", "documento_identidad = '$documento_identidad_proveedor'");
        $nombre_tipo_documento = SQL::obtenerValor("tipos_documentos","descripcion", "codigo = '$codigo_tipo_documento'");
        $nombre_proyecto       = SQL::obtenerValor("proyectos","nombre", "codigo = '$codigo_proyecto'");

        $error  = "";
        $titulo = $componente->nombre;

        /*** Consulta tipos de documentos ***/
        $consulta_tipos_documentos = SQL::seleccionar(array("tipos_documentos"),array("codigo","descripcion"),"codigo > 0");

        if (SQL::filasDevueltas($consulta_tipos_documentos)) {

            while ($datos_tipos_documentos = SQL::filaEnObjeto($consulta_tipos_documentos)) {
                $tipos_documentos[$datos_tipos_documentos->codigo] = $datos_tipos_documentos->descripcion;
            }
        }

        /*** Definición de pestañas general ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::mostrarDato("proyecto", $textos["PROYECTO"], $nombre_proyecto),
                HTML::mostrarDato("estado", $textos["ESTADO"], $textos["ESTADO_".$estado])
            ),
            array(
                HTML::mostrarDato("nit_proveedor", $textos["NIT_PROVEEDOR"], $documento_identidad_proveedor),
                HTML::mostrarDato("razon_social", $textos["RAZON_SOCIAL"], $razon_social),
            ),
            array(
                HTML::listaSeleccionSimple("*tipo_documento", $textos["TIPO_DOCUMENTO"], $tipos_documentos, $datos_tipos_documentos->codigo,array("title" => $textos["AYUDA_TIPO_DOCUMENTO"])),

                HTML::campoTextoCorto("*documento_soporte",$textos["DOCUMENTO_SOPORTE"], 15, 15, $numero_documento_proveedor, array("title"=>$textos["AYUDA_DOCUMENTO_SOPORTE"], "onBlur" => "validarItem(this)")),

                HTML::campoTextoCorto("*valor_documento",$textos["VALOR_DOCUMENTO"], 15, 15, $valor_documento, array("title"=>$textos["AYUDA_VALOR_DOCUMENTO"], "onkeyup"=>"formatoMiles(this)", "onchange"=>"formatoMiles(this)"))
            ),
            array(
                HTML::campoTextoCorto("fecha_recepcion", $textos["FECHA_RECEPCION"], 10, 10, $fecha_recepcion, array("class" => "selectorFecha"), array("title" => $textos["AYUDA_FECHA_RECEPCION"], "onBlur" => "validarItem(this);")),

                HTML::campoTextoCorto("fecha_vencimiento", $textos["FECHA_VENCIMIENTO"], 10, 10, $fecha_vencimiento, array("class" => "selectorFecha"), array("title" => $textos["AYUDA_FECHA_VENCIMIENTO"], "onBlur" => "validarItem(this);")),

                //HTML::campoTextoCorto("fecha_envio", $textos["FECHA_ENVIO"], 10, 10, date("Y-m-d"), array("class" => "selectorFecha"), array("title" => $textos["AYUDA_FECHA_ENVIO"], "onBlur" => "validarItem(this);"))
            ),
            array(    
                HTML::campoTextoCorto("observaciones",$textos["OBSERVACIONES"], 50, 234, $observaciones,array("title"=>$textos["AYUDA_OBSERVACIONES"]))
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

/*** Modificar el elemento seleccionado ***/
} elseif (!empty($forma_procesar)) {

    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_MODIFICADO"];

    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];

    /*** Validar el ingreso de los datos requeridos ***/
    if(empty($forma_tipo_documento)){
        $error   = true;
        $mensaje = $textos["TIPO_DOCUMENTO_VACIO"];

    }elseif(empty($forma_documento_soporte)){
        $error   = true;
        $mensaje = $textos["DOCUMENTO_SOPORTE_VACIO"];

    }elseif(empty($forma_valor_documento)){
        $error   = true;
        $mensaje = $textos["VALOR_VACIO"];

    }elseif(empty($forma_fecha_recepcion)){
        $error   = true;
        $mensaje = $textos["FECHA_RECEPCION_VACIO"];

    }elseif(empty($forma_fecha_vencimiento)){
        $error   = true;
        $mensaje = $textos["FECHA_VENCIMIENTO_VACIO"];
    /*}

    elseif(empty($forma_fecha_envio)){
        $error   = true;
        $mensaje = $textos["FECHA_ENVIO_VACIO"];*/
    } else {

       /*** Quitar separador de miles a un numero ***/
        function quitarMiles($cadena){
            $valor = array();
            for ($i = 0; $i < strlen($cadena); $i++) {
                if (substr($cadena, $i, 1) != ".") {
                    $valor[$i] = substr($cadena, $i, 1);
                }
            }
            $valor = implode($valor);
            return $valor;
        }

        $forma_valor_documento = quitarMiles($forma_valor_documento);

        /*** Insertar datos ***/
        $datos = array(
            "codigo_tipo_documento"         => $forma_tipo_documento,
            "numero_documento_proveedor"    => $forma_documento_soporte,
            "valor_documento"               => $forma_valor_documento,
            "estado"                        => '0',
            "fecha_recepcion"               => $forma_fecha_recepcion,
            "fecha_vencimiento"             => $forma_fecha_vencimiento,
            "fecha_envio"                   => " ",
            "observaciones"                 => $forma_observaciones
        );

        $consulta = SQL::modificar("correspondencia", $datos, "codigo = '$forma_id'");
		
		/*** Error inserción ***/
        if ($consulta) {
            $error   = false;
            $mensaje = $textos["ITEM_MODIFICADO"];
        } else {
            $error   = true;
            $mensaje = $textos["ERROR_MODIFICAR_ITEM"];
        }
    }
    /*** Enviar datos con la respuesta del proceso al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

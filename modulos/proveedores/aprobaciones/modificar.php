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
        $vistaConsulta  = "aprobaciones";
        $condicion      = "codigo = '$url_id'";
        $columnas       = SQL::obtenerColumnas($vistaConsulta);
        $consulta       = SQL::seleccionar(array($vistaConsulta), $columnas, $condicion);
        $datos          = SQL::filaEnObjeto($consulta);
        $codigo         = $url_id; 

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

        /*** Consulta ordenes de compra ***/
        $consulta_orden = SQL::seleccionar(array("ordenes_compra"),array("numero_consecutivo"),"prefijo_codigo_proyecto='$codigo_proyecto' AND documento_identidad_proveedor='$documento_identidad_proveedor'");

        if (SQL::filasDevueltas($consulta_orden)) {

            while ($datos_orden = SQL::filaEnObjeto($consulta_orden)) {
                $ordenes[$datos_orden->numero_consecutivo] = $datos_orden->numero_consecutivo;
            }
        }

        if (($estado=='0')) {
            /*** Definición de pestañas general ***/
            $formularios["PESTANA_GENERAL"] = array(
                array(
                    HTML::mostrarDato("proyecto", $textos["PROYECTO"], $nombre_proyecto),
                    HTML::mostrarDato("estado_residente", $textos["ESTADO_RESIDENTE"], $textos["ESTADO_RESIDENTE_".$estado_residente]),
                    HTML::mostrarDato("estado_director", $textos["ESTADO_DIRECTOR"], $textos["ESTADO_DIRECTOR_".$estado_director])
                ),
                array(
                    HTML::mostrarDato("nit_proveedor", $textos["NIT_PROVEEDOR"], $documento_identidad_proveedor),
                    HTML::mostrarDato("razon_social", $textos["RAZON_SOCIAL"], $razon_social),

                     HTML::listaSeleccionSimple("*orden_compra", $textos["ORDEN_COMPRA"], HTML::generarDatosLista("ordenes_compra", "numero_consecutivo","codigo", "prefijo_codigo_proyecto='$codigo_proyecto' AND documento_identidad_proveedor='$documento_identidad_proveedor'"), $numero_orden_compra, array("title" => $textos["AYUDA_ORDEN_COMPRA"])),
                ),
                array(
                    HTML::listaSeleccionSimple("*tipo_documento", $textos["TIPO_DOCUMENTO"], HTML::generarDatosLista("tipos_documentos", "codigo", "descripcion", "codigo=4 OR codigo=5"), $codigo_tipo_documento, array("title" => $textos["AYUDA_TIPO_DOCUMENTO"])),

                    HTML::campoTextoCorto("*documento_soporte",$textos["DOCUMENTO_SOPORTE"], 15, 15, $numero_documento_proveedor, array("title"=>$textos["AYUDA_DOCUMENTO_SOPORTE"], "onBlur" => "validarItem(this)")),

                    HTML::campoTextoCorto("*valor_documento",$textos["VALOR_DOCUMENTO"], 15, 15, $valor_documento, array("title"=>$textos["AYUDA_VALOR_DOCUMENTO"]))
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

            /*** Documentos soportes ***/
            $documentos_cotizaciones = SQL::seleccionar(array("documentos"),array("*"),"codigo_registro_tabla = '$url_id'");
            $documentos_cotizaciones = SQL::filaEnObjeto($documentos_cotizaciones);
            $nombre_archivo          = $documentos_cotizaciones->ruta;

            $formularios["PESTANA_DOCUMENTO"] = array(
                array(
                    HTML::selectorArchivo("archivo", $textos["ARCHIVO_DOCUMENTO"], array("title" => $textos["AYUDA_ARCHIVO_DOCUMENTO"])),
                    HTML::campoTextoCorto("nombre_documento", $textos["NOMBRE_DOCUMENTO"], 15, 255, "", array("title" => $textos["AYUDA_NOMBRE_DOCUMENTO"])),
                    HTML::campoOculto("codigo", $codigo)
                )
            );

            /*** Definición de botones ***/
            $botones = array(
                HTML::boton("botonAceptar", $textos["ACEPTAR"], "modificarItem('$url_id');", "aceptar")
            );

            $contenido = HTML::generarPestanas($formularios, $botones);
        }else {

            $error = $textos["ERROR_ORDEN_ESTADOS"];
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
}
/*** Modificar el elemento seleccionado ***/
//} elseif (!empty($forma_procesar)) {

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

    }elseif(empty($forma_orden_compra)){
        $error   = true;
        $mensaje = $textos["ORDEN_VACIO"];
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
            "numero_orden_compra"           => $forma_orden_compra,  
            "valor_documento"               => $forma_valor_documento,
            "fecha_registro_residente"      => date("Y-m-d H:i:s"),
            "observaciones"                 => $forma_observaciones
        );

        $consulta = SQL::modificar("aprobaciones", $datos, "codigo = '$forma_id'");
		
        //Modificar correspondencia
        $datos_correspondencia = array(
            "codigo_tipo_documento"         => $forma_tipo_documento,
            "numero_documento_proveedor"    => $forma_documento_soporte,
            "numero_orden_compra"           => $forma_orden_compra,  
            "valor_documento"               => $forma_valor_documento,
            "estado"                        => '0',
            "fecha_recepcion"               => $forma_fecha_recepcion,
            "fecha_vencimiento"             => $forma_fecha_vencimiento,
            "fecha_envio"                   => " ",
            "observaciones"                 => $forma_observaciones
        );

        $consulta = SQL::modificar("correspondencia", $datos_correspondencia, "codigo_aprobaciones = '$forma_id'");

		/*** Error inserción ***/
        if (!$consulta) {
            $error   = false;
            $mensaje = $textos["ERROR_MODIFICAR_ITEM"];
        } else {
            if (!empty($_FILES["archivo"]["name"])) {
                $original  = $_FILES["archivo"]["name"];
                $temporal  = $_FILES["archivo"]["tmp_name"];
                $extension = strtolower(substr($original, (strrpos($original, ".") - strlen($original)) + 1));

                $nombre    = substr(md5(uniqid(rand(), true)), 0, 8);
                $ruta      = $rutasGlobales["archivos"]."/"."soportes/".$nombre.".".$extension;

                while (file_exists($ruta)) {
                    $nombre    = substr(md5(uniqid(rand(), true)), 0, 8);
                    $ruta      = $rutasGlobales["archivos"]."/".$nombre.".".$extension;
                }

                $copiar = move_uploaded_file($temporal, $ruta);

                $datos_documento = array(
                    "titulo"                => $forma_nombre_documento,
                    "ruta"                  => $ruta,
                    "nombre_tabla"          => "aprobaciones"
                );
                $modificar_documento = SQL::modificar("documentos",$datos_documento,"codigo_registro_tabla = '$forma_codigo'");
            }
        }
//    }
    /*** Enviar datos con la respuesta del proceso al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

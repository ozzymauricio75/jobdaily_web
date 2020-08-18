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
/*** Devolver datos para autocompletar la búsqueda ***/
if (isset($url_completar)) {
    if (($url_item) == "selector1") {
        echo SQL::datosAutoCompletar("seleccion_municipios", $url_q);
    }

    if (($url_item) == "selector3") {
        echo SQL::datosAutoCompletar("seleccion_proveedores", $url_q);
    }
    
    if (($url_item) == "selector4") {
        echo SQL::datosAutoCompletar("menu_proveedores", $url_q);
    }

    if (($url_item) == "selector5") {
        echo SQL::datosAutoCompletar("seleccion_proyectos", $url_q);
    }
    exit;

}elseif (isset($url_recibirDocumento)) {
    $codigo = $url_id_tabla;

    $datos_correspondencia = array(
        "estado"           => '3', 
        "fecha_autorizado" => date("Y-m-d H:i:s"),
    );

    $recibir_documento = SQL::modificar("correspondencia",$datos_correspondencia,"codigo='$codigo'");
    $respuesta[0]      = true;

    if (!$recibir_documento){
        $respuesta[0] = false;
        $respuesta[1] = $textos["ITEM_APROBADO"];
    }else if($recibir_documento) {
        $error   = true;
        $mensaje = $textos["ERROR_APROBAR_ITEM"];
    }
    HTTP::enviarJSON($respuesta);
    exit;

}elseif (isset($url_cargarDocumentosCorrespondencia)) {
    $codigo_proyecto               = $url_codigo_proyecto;
    $documento_identidad_proveedor = $url_documento_identidad_proveedor;

    $llave                         = explode("-", $url_documento_identidad_proveedor);
    $documento_identidad_proveedor = $llave[0];

    $llave_proyecto                = explode("-", $codigo_proyecto);
    $codigo_proyecto               = $llave_proyecto[0];

    $vistaConsulta  = "correspondencia";
    $columnas       = SQL::obtenerColumnas($vistaConsulta);
    $consulta       = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo_proyecto = '$codigo_proyecto' AND documento_identidad_proveedor='$documento_identidad_proveedor'");
    $numero_filas   = mysql_affected_rows();
    $contador       = 0;
    $tabla          = array(); 

    //Inicio la construccion de la tabla
    if (SQL::filasDevueltas($consulta)) {
        while ($fila = mysql_fetch_array($consulta)) {
            $tipo_documento        = $fila['tipo_documento'];
            $nombre_tipo_documento = SQL::obtenerValor("tipos_documentos","descripcion", "codigo = '$tipo_documento'");
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

            $id_tabla              = $fila['codigo'];   
            $fecha_recepcion       = $fila['fecha_recepcion'];
            $documento_identidad   = $fila['documento_identidad_proveedor'];
            //$razon_social          = $fila['razon_social'];
            //$nombre_tipo_documento = $fila['nombre_tipo_documento'];
            $valor_documento       = $fila['valor_documento'];
            $fecha_vencimiento     = $fila['fecha_vencimiento'];
            $fecha_envio           = $fila['fecha_envio'];

            $tabla[] = array('codigo'=> $id_tabla, 'fecha_recepcion'=> $fecha_recepcion, 'documento_identidad_proveedor'=> $documento_identidad, 'valor_documento'=> $valor_documento, 'fecha_vencimiento'=> $fecha_vencimiento, 'fecha_envio'=> $fecha_envio);
        } 
         
    } else {
        $tabla[] = "";
    }
    $json_string = json_encode($tabla);
    //echo $json_string;
    HTTP::enviarJSON($json_string);
    exit;  
}
/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {
    
    /*** Verificar que se haya enviado el ID del elemento a consultar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_CONSULTAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {
        $error          = "";
        $titulo         = $componente->nombre;
        $contenido      = "";

        $vistaConsulta         = "correspondencia";
        $columnas              = SQL::obtenerColumnas($vistaConsulta);
        $consulta              = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo = '$url_id'");
        $datos_correspondencia = SQL::filaEnObjeto($consulta);

        //Inicio la construccion de la tabla
        if (SQL::filasDevueltas($consulta)) {
            /*** Consulta todas las ordenes y documentos ***/
            $consulta_documentos = SQL::seleccionar(array("correspondencia"),array("*"),"codigo_proyecto='$datos_correspondencia->codigo_proyecto' AND documento_identidad_proveedor='$datos_correspondencia->documento_identidad_proveedor' AND estado='0'");
            while ($datos = SQL::filaEnObjeto($consulta_documentos)) {
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

                $tipo_persona = SQL::obtenerValor("terceros", "tipo_persona", "documento_identidad = '".$documento_identidad_proveedor."'");
                if(($tipo_persona==1)||($tipo_persona==3)){
                    $primer_nombre    = SQL::obtenerValor("terceros", "primer_nombre", "documento_identidad = '".$documento_identidad_proveedor."'");
                    $segundo_nombre   = SQL::obtenerValor("terceros", "segundo_nombre", "documento_identidad = '".$documento_identidad_proveedor."'");
                    $primer_apellido  = SQL::obtenerValor("terceros", "primer_apellido", "documento_identidad = '".$documento_identidad_proveedor."'");
                    $segundo_apellido = SQL::obtenerValor("terceros", "segundo_apellido", "documento_identidad = '".$documento_identidad_proveedor."'");
                    $razon_social = $primer_nombre." ".$segundo_nombre." ".$primer_apellido." ".$segundo_apellido;
                }else{
                    $razon_social = SQL::obtenerValor("terceros","razon_social", "documento_identidad = '$documento_identidad_proveedor'");
                }

                if ($estado =='0'){
                    $modificar = "";
                    $cumplido  = HTML::boton("botonCumplido", "", "cumplirItem(this);", "aceptar", array("title" => $textos["CUMPLIR"]));
                }

                $ocultos = HTML::campoOculto("estado_documento_tabla[]", $datos->codigo, array("class" => "estado_documento_tabla"));
                $celda   = $ocultos.$cumplido;

                /*** Documentos soportes ***/
                $documentos_cotizaciones = SQL::seleccionar(array("documentos"),array("*"),"codigo_registro_tabla = '$datos->codigo'");
                $documentos_cotizaciones = SQL::filaEnObjeto($documentos_cotizaciones);
                $nombre_archivo          = $documentos_cotizaciones->ruta;

                $items[] = array(
                    //$cumplido     = HTML::boton("botonCumplido", "", "cumplirItem(this);", "aceptar", array("title" => $textos["CUMPLIR"])),
                    $id_tabla = $datos->codigo,
                    $celda,
                    $fecha_recepcion,
                    $documento_identidad_proveedor,
                    $razon_social,
                    $nombre_tipo_documento,
                    $numero_documento_proveedor,
                    $valor = $valor_documento,
                    $fecha_vencimiento,
                    $fecha_envio,
                    HTML::enlazarPagina($textos["DESCARGAR"]." ".$documentos_cotizaciones->titulo, $nombre_archivo, array("target" => "_new"))
                ); 
            }
        } 

        if (($estado=='0')) {
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
                    HTML::campoOculto("indicador", "")
                ),
                array(
                    HTML::contenedor(HTML::boton("botonCumplir", "", "cumplirItem(this);", "aceptar"), array("id" => "cumplir", "style" => "display: none"))
                ),
                array(
                    HTML::generarTabla(
                        array("id","BOTON_RECIBIR","FECHA_RECEPCION","NIT_PROVEEDOR","RAZON_SOCIAL","TIPO_DOCUMENTO","DOCUMENTO_SOPORTE","VALOR_DOCUMENTO","FECHA_VENCIMIENTO","FECHA_ENVIO","ARCHIVO"), 
                            $items, 
                            array("I","C","I","I","C","C","D","C","C","C"), 
                            "listaItems", 
                            false
                        )
                )
            );
            /*** Definición de botones ***/
            $botones = array(
                HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem('$url_id');", "aceptar")
            );
            $contenido = HTML::generarPestanas($formularios, $botones);
        }else {

            $error = $textos["ERROR_ORDEN_ESTADO_AUTORIZADO"];
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
    
    if ($forma_indicador==1) {
        $error    = false;
        $mensaje  = $textos["ITEM_APROBADO"];
    } else {
        $error   = true;
        $mensaje = $textos["ERROR_APROBAR_ITEM"];
    }

    /*** Enviar datos con la respuesta del proceso al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

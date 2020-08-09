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

    $datos_aprobaciones = array(
        "estado_residente"         => '1', 
        "fecha_registro_residente" => date("Y-m-d H:i:s")
    );

    $aprobar_documento = SQL::modificar("aprobaciones",$datos_aprobaciones,"codigo='$codigo'");
    $respuesta[0]      = true;

    if (!$aprobar_documento){
        $respuesta[0] = false;
        $respuesta[1] = $textos["ITEM_RECIBIDO"];
    }else if($aprobar_documento) {
        $error   = true;
        $mensaje = $textos["ERROR_RECIBIR_ITEM"];
    }
    HTTP::enviarJSON($respuesta);
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

        $vistaConsulta         = "aprobaciones";
        $columnas              = SQL::obtenerColumnas($vistaConsulta);
        $consulta              = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo = '$url_id'");
        $datos_aprobaciones    = SQL::filaEnObjeto($consulta);

        //Inicio la construccion de la tabla
        if (SQL::filasDevueltas($consulta)) {
            /*** Consulta todas las ordenes y documentos ***/
            $consulta_documentos = SQL::seleccionar(array("aprobaciones"),array("*"),"codigo_proyecto='$datos_aprobaciones->codigo_proyecto' AND documento_identidad_proveedor='$datos_aprobaciones->documento_identidad_proveedor' AND estado_residente='0'");
            while ($datos = SQL::filaEnObjeto($consulta_documentos)) {
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

                if ($estado_residente =='0'){
                    $modificar = "";
                    $cumplido  = HTML::boton("botonCumplido", "", "cumplirItem(this);", "aceptar", array("title" => $textos["CUMPLIR"]));
                }

                $ocultos = HTML::campoOculto("estado_documento_tabla[]", $datos->codigo, array("class" => "estado_documento_tabla"));
                $celda   = $ocultos.$cumplido;

                $items[] = array(
                    $id_tabla = $datos->codigo,
                    $celda,
                    $fecha_registro_residente,
                    $documento_identidad_proveedor,
                    $razon_social,
                    $nombre_tipo_documento,
                    $numero_documento_proveedor,
                    $valor = $valor_documento
                ); 
            }
        } 

        if (($datos_aprobaciones->estado_residente==0)) {
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
                            array("id","BOTON_RECIBIR","FECHA_REGISTRO_RESIDENTE","NIT_PROVEEDOR","RAZON_SOCIAL","TIPO_DOCUMENTO","DOCUMENTO_SOPORTE","VALOR_DOCUMENTO"), 
                                $items, 
                                array("I","C","I","I","C","C","D"), 
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

        $error = $textos["ERROR_ORDEN_ESTADO"];
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
        $mensaje  = $textos["ITEM_RECIBIDO"];
    } else {
        $error   = true;
        $mensaje = $textos["ERROR_RECIBIR_ITEM"];
    }

    /*** Enviar datos con la respuesta del proceso al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

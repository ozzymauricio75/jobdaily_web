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

}elseif (!empty($url_cargarOrdenes)) {
    $codigo_proyecto               = $url_codigo_proyecto;
    $documento_identidad_proveedor = $url_documento_identidad_proveedor;

    $llave                         = explode("-", $url_documento_identidad_proveedor);
    $documento_identidad_proveedor = $llave[0];

    $llave_proyecto                = explode("-", $codigo_proyecto);
    $codigo_proyecto               = $llave_proyecto[0];

    $consulta_orden  = SQL::seleccionar(array("ordenes_compra"), array("*"), "prefijo_codigo_proyecto = '$codigo_proyecto' AND documento_identidad_proveedor='$documento_identidad_proveedor' AND estado!='3'");

    if (SQL::filasDevueltas($consulta_orden)) {
        while($datos = SQL::filaEnObjeto($consulta_orden)){
            $codigo.= $datos->codigo."-";
            $numero_consecutivo.= $datos->numero_consecutivo."-";
        }   
    }else {
        $error   = true;
        $mensaje = $textos["ERROR_EXISTE_ORDEN"];
    }
    $codigo             = trim($codigo,"-");
    $numero_consecutivo = trim($numero_consecutivo,"-");
    /*******************************************************/
    $elementos[0] = $codigo;
    $elementos[1] = $numero_consecutivo;
    $elementos[2] = $mensaje;
    HTTP::enviarJSON($elementos);
    exit;    

}elseif (!empty($url_valoresOrden)) {
    $codigo_proyecto               = $url_codigo_proyecto;
    $documento_identidad_proveedor = $url_documento_identidad_proveedor;
    $orden_compra                  = $url_orden_compra;

    $llave                         = explode("-", $url_documento_identidad_proveedor);
    $documento_identidad_proveedor = $llave[0];

    $llave_proyecto                = explode("-", $codigo_proyecto);
    $codigo_proyecto               = $llave_proyecto[0];

    $codigo_orden              = SQL::obtenerValor("ordenes_compra","codigo","numero_consecutivo='$orden_compra'");
    $total_orden               = SQL::obtenerValor("movimiento_ordenes_compra","SUM(neto_pagar)","codigo_orden_compra='$codigo_orden'");
    $valor_documentos_cruzados = SQL::obtenerValor("aprobaciones","SUM(valor_documento)","numero_orden_compra='$orden_compra'");
    $tolerancia                = SQL::obtenerValor("tolerancia","porcentaje","codigo>'0'");
    $total_orden               = number_format($total_orden,2);
    $valor_documentos_cruzados = number_format($valor_documentos_cruzados,2);
    $indicador  = true;
    /*******************************************************/
    $datos = array(
        $indicador,
        $total_orden,
        $valor_documentos_cruzados,
        $tolerancia
    );
    HTTP::enviarJSON($datos);
    exit;    

}elseif (!empty($url_validaTotalOrden)) {
    $codigo_proyecto               = $url_codigo_proyecto;
    $documento_identidad_proveedor = $url_documento_identidad_proveedor;
    $orden_compra                  = $url_orden_compra;

    $llave                         = explode("-", $url_documento_identidad_proveedor);
    $documento_identidad_proveedor = $llave[0];

    $llave_proyecto                = explode("-", $codigo_proyecto);
    $codigo_proyecto               = $llave_proyecto[0];

    $codigo_orden              = SQL::obtenerValor("ordenes_compra","codigo","numero_consecutivo='$orden_compra'");
    $total_orden               = SQL::obtenerValor("movimiento_ordenes_compra","SUM(neto_pagar)","codigo_orden_compra='$codigo_orden'");
    $valor_documentos_cruzados = SQL::obtenerValor("aprobaciones","SUM(valor_documento)","numero_orden_compra='$orden_compra'");
    $tolerancia                = SQL::obtenerValor("tolerancia","porcentaje","codigo>'0'");
    
    $indicador  = true;
    /*******************************************************/
    $datos = array(
        $indicador,
        $total_orden,
        $valor_documentos_cruzados,
        $tolerancia
    );
    HTTP::enviarJSON($datos);
    exit; 

}elseif (!empty($url_recargar)) {

    if ($url_elemento == "empresa") {
       $respuesta = HTML::generarDatosLista("empresas", "codigo", "nombre", "codigo = '$url_origen'");
    }

    if ($url_elemento == "sucursal") {
       $respuesta = HTML::generarDatosLista("sucursales", "codigo", "nombre", "codigo_empresa = '".$url_codigo."' AND codigo !='0'");
    }

    HTTP::enviarJSON($respuesta);
    exit; 
}

/*** Generar el formulario para la captura de datos ***/
elseif (!empty($url_generar)) {
    $error    = "";
    $titulo   = $componente->nombre;
    
    $consulta_proyectos = SQL::seleccionar(array("proyectos"),array("*"),"codigo>0");
    if (SQL::filasDevueltas($consulta_proyectos)){

        //Asignar codigo siguiente de la tabla 
        $codigo = SQL::obtenerValor("correspondencia","MAX(codigo)","codigo>0");

        if ($codigo){
            $codigo++;
        } else {
            $codigo = 1;
        }

         /*** Definición de pestañas general ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::campoTextoCorto("*selector5", $textos["PROYECTO"], 40, 255, "", array("title" => $textos["AYUDA_PROYECTO"], "class" => "autocompletable"))
                .HTML::campoOculto("codigo_proyecto", "")
            ),
            array(  
                HTML::campoTextoCorto("*selector3", $textos["NIT_PROVEEDOR"], 40, 255, "", array("title" => $textos["AYUDA_NIT_PROVEEDOR"], "class" => "autocompletable","onBlur"=>"cargarOrdenes()","onKeyPress"=>"return campoEntero(event)"))
                .HTML::campoOculto("documento_identidad_proveedor", ""),

                HTML::listaSeleccionSimple("*tipo_documento", $textos["TIPO_DOCUMENTO"], HTML::generarDatosLista("tipos_documentos", "codigo", "descripcion","codigo=4 OR codigo=5"), "", array("title" => $textos["AYUDA_TIPO_DOCUMENTO"]))
            ),
            array(
                HTML::listaSeleccionSimple("*orden_compra", $textos["ORDEN_COMPRA"], "", "", array("title",$textos["AYUDA_ORDEN_COMPRA"],"onBlur"=>"valoresOrden()"))
            ),
            array(   
                HTML::campoTextoCorto("*documento_soporte",$textos["DOCUMENTO_SOPORTE"], 15, 15, "", array("title"=>$textos["AYUDA_DOCUMENTO_SOPORTE"])),

                HTML::mostrarDato("total_orden", $textos["TOTAL_ORDEN"], $total_orden),

                HTML::mostrarDato("documentos_cruzados", $textos["TOTAL_CRUZADO"], $total_cruzado),

                HTML::campoTextoCorto("*valor_documento",$textos["VALOR_DOCUMENTO"], 15, 15, "", array("title"=>$textos["AYUDA_VALOR_DOCUMENTO"], "onkeyup"=>"formatoMiles(this)", "onchange"=>"formatoMiles(this)", "onBlur"=>"validaTotalOrden()"))
            ),
            array(
                HTML::campoTextoCorto("fecha_recepcion", $textos["FECHA_RECEPCION"], 10, 10, date("Y-m-d"), array("class" => "selectorFecha"), array("title" => $textos["AYUDA_FECHA_RECEPCION"])),

                HTML::campoTextoCorto("fecha_vencimiento", $textos["FECHA_VENCIMIENTO"], 10, 10, date("Y-m-d"), array("class" => "selectorFecha"), array("title" => $textos["AYUDA_FECHA_VENCIMIENTO"]))
            ),
            array(    
                HTML::campoTextoCorto("observaciones",$textos["OBSERVACIONES"], 50, 234, "",array("title"=>$textos["AYUDA_OBSERVACIONES"])),
            ),
            array(
                HTML::selectorArchivo("archivo", $textos["ARCHIVO_DOCUMENTO"], array("title" => $textos["AYUDA_ARCHIVO_DOCUMENTO"])),
                HTML::campoTextoCorto("nombre_documento", $textos["NOMBRE_DOCUMENTO"], 15, 255, "", array("title" => $textos["AYUDA_NOMBRE_DOCUMENTO"]))
            )
        );

        /*** Definicion de botones ***/
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones);
    } else {
        $contenido = "";
        $error     = $textos["CREAR_PROYECTOS"];
    }

    /*** Enviar datos para la generacion del formulario al script que origino la peticion ***/
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);
    exit();
}    
/*** Adicionar los datos provenientes del formulario ***/
//} elseif (!empty($forma_procesar)) {

    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];

    $llave                         = explode("-", $forma_selector3);
    $documento_identidad_proveedor = $llave[0];

    $llave_proyecto                = explode("-", $forma_selector5);
    $codigo_proyecto               = $llave_proyecto[0];

    /*** Validar el ingreso de los datos requeridos ***/
    if(empty($forma_selector5)){
        $error   = true;
        $mensaje = $textos["PROYECTO_VACIO"];

    }elseif(empty($forma_selector3)){
        $error   = true;
        $mensaje = $textos["PROVEEDOR_VACIO"];

    }elseif(empty($forma_tipo_documento)){
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
            "codigo_proyecto"               => $codigo_proyecto,
            "documento_identidad_proveedor" => $documento_identidad_proveedor,
            "codigo_tipo_documento"         => $forma_tipo_documento,
            "numero_documento_proveedor"    => $forma_documento_soporte,
            "numero_orden_compra"           => $forma_orden_compra,
            "valor_documento"               => $forma_valor_documento,
            "estado_residente"              => '0',
            "estado_director"               => '0',
            "fecha_registro_residente"      => date("Y-m-d H:i:s"),
            "fecha_registro_director"       => "",
            "observaciones"                 => $forma_observaciones
        );
        
        $insertar = SQL::insertar("aprobaciones", $datos);
        
        //Asignar codigo siguiente de la tabla 
        $codigo_aprobaciones = SQL::obtenerValor("aprobaciones","MAX(codigo)","codigo>0");

        if($insertar){
            /*** Insertar datos en correspondencia ***/
            $datos_correspondencia = array(
                "codigo_aprobaciones"           => $codigo_aprobaciones,
                "codigo_proyecto"               => $codigo_proyecto,
                "documento_identidad_proveedor" => $documento_identidad_proveedor,
                "codigo_tipo_documento"         => $forma_tipo_documento,
                "numero_documento_proveedor"    => $forma_documento_soporte,
                "numero_orden_compra"           => $forma_orden_compra,
                "valor_documento"               => $forma_valor_documento,
                "estado"                        => '0',
                "fecha_recepcion"               => $forma_fecha_recepcion,
                "fecha_vencimiento"             => $forma_fecha_vencimiento, 
                "fecha_envio"                   => "",
                "observaciones"                 => $forma_observaciones
            );

            $insertar_correspondencia = SQL::insertar("correspondencia", $datos_correspondencia);

            //Asignar codigo siguiente de la tabla 
            $codigo = SQL::obtenerValor("aprobaciones","MAX(codigo)","codigo>0");

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

                $copiar   = move_uploaded_file($temporal, $ruta);

                $datos_documento = array(
                    "titulo"                => $forma_nombre_documento,
                    "ruta"                  => $ruta,
                    "nombre_tabla"          => "aprobaciones",
                    "codigo_registro_tabla" => $codigo,
                    "tipo_archivo"          => '1'
                );

                $insertar_documento = SQL::insertar("documentos",$datos_documento);
            }

        }else{
            $error   = true;
            $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
        }
    }
    /*** Enviar datos con la respuesta del proceso al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
//}
?>

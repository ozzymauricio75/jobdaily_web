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

    if (($url_item) == "documento_soporte") {
        echo SQL::datosAutoCompletar("correspondencia", $url_q, "estado_residente='1' AND estado_director='1' AND estado_factura='0'");
    }
    exit;

}elseif (!empty($url_cargarOrdenes)) {
    $codigo_proyecto               = $url_codigo_proyecto;
    $documento_identidad_proveedor = $url_documento_identidad_proveedor;
    $tipo_documento                = $url_tipo_documento;

    $llave                         = explode("-", $url_documento_identidad_proveedor);
    $documento_identidad_proveedor = $llave[0];

    $llave_proyecto                = explode("-", $codigo_proyecto);
    $codigo_proyecto               = $llave_proyecto[0];

    $consulta_orden  = SQL::seleccionar(array("ordenes_compra"), array("*"), "prefijo_codigo_proyecto = '$codigo_proyecto' AND documento_identidad_proveedor='$documento_identidad_proveedor'");

    if (SQL::filasDevueltas($consulta_orden)) {
        while($datos = SQL::filaEnObjeto($consulta_orden)){
            $codigo.= $datos->codigo."-";
            $numero_consecutivo.= $datos->numero_consecutivo."-";
        }   
    }/*else {
        $error   = true;
        $mensaje = $textos["ERROR_EXISTE_ORDEN"];
    }*/
    $codigo             = trim($codigo,"-");
    $numero_consecutivo = trim($numero_consecutivo,"-");
    /*******************************************************/
    $elementos[0] = $codigo;
    $elementos[1] = $numero_consecutivo;
    $elementos[2] = $mensaje;
    HTTP::enviarJSON($elementos);
    exit;    

}elseif (!empty($url_cargaValor)) {
    $documento_soporte             = $url_documento_soporte;
    $documento_identidad_proveedor = $url_documento_identidad_proveedor;

    $llave                         = explode("-", $url_documento_identidad_proveedor);
    $documento_identidad_proveedor = $llave[0];

    $datos = SQL::obtenerValor("correspondencia", "valor_documento", "numero_documento_proveedor = '$documento_soporte' AND documento_identidad_proveedor='$documento_identidad_proveedor'");

    HTTP::enviarJSON($datos);
    exit; 

}elseif (!empty($url_ocultarValor)) {
    $tipo_documento = $url_tipo_documento;

    if($tipo_documento==10){
        $datos=true;
    }else{
        $datos=false;
    }

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
    $valor_documentos_cruzados = SQL::obtenerValor("correspondencia","SUM(valor_documento)","numero_orden_compra='$orden_compra' AND codigo_tipo_documento=5");
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
    $valor_documentos_cruzados = SQL::obtenerValor("correspondencia","SUM(valor_documento)","numero_orden_compra='$orden_compra'");
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

}elseif (!empty($url_existeDocumento)) {
    $documento_soporte             = $url_documento_soporte;
    $documento_identidad_proveedor = $url_documento_identidad_proveedor;

    $llave                         = explode("-", $url_documento_identidad_proveedor);
    $documento_identidad_proveedor = $llave[0];

    $datos = SQL::obtenerValor("correspondencia", "valor_documento", "numero_documento_proveedor = '$documento_soporte' AND documento_identidad_proveedor='$documento_identidad_proveedor'");
    if($datos){
        $respuesta=true;
    }else{
        $respuesta=false;
    }

    HTTP::enviarJSON($respuesta);
    exit;       
}
/*** Mostrar los tipos de documentos a cruzar con la factura ***/
elseif(isset($url_cruzarDocumentos)){
    $tipo_documento = $url_tipo_documento;

    if($tipo_documento==5){
        $lista = HTML::generarDatosLista("tipos_documentos","codigo","descripcion","codigo = '0' OR codigo ='7' OR codigo='8' OR codigo='11' OR codigo= '12'");
    }
    
    if(empty($lista)){
        $lista = array("0" => $textos["NO_ES_FACTURA"]);
    }

    HTTP::enviarJSON($lista);
    exit;
}

/*** Mostrar los numeros de documentos a cruzar con la factura ***/
elseif(isset($url_tipo_documento_cruce)){

    $tipo_documento_cruce          = $url_tipo_documento_cruce;
    $codigo_proyecto               = $url_codigo_proyecto;
    $documento_identidad_proveedor = $url_documento_identidad_proveedor;
    $orden_compra                  = $url_orden_compra;

    $llave                         = explode("-", $url_documento_identidad_proveedor);
    $documento_identidad_proveedor = $llave[0];

    $llave_proyecto                = explode("-", $codigo_proyecto);
    $codigo_proyecto               = $llave_proyecto[0];

    $consulta = SQL::seleccionar(array("correspondencia"), array("*"), "codigo_tipo_documento='$tipo_documento_cruce' AND documento_identidad_proveedor='$documento_identidad_proveedor' AND numero_orden_compra='$orden_compra' AND codigo_proyecto='$codigo_proyecto'");

    if (SQL::filasDevueltas($consulta)) {
        while($datos = SQL::filaEnObjeto($consulta)){  
            $codigo.= $datos->codigo."-";
            $numero_documento_proveedor.= $datos->numero_documento_proveedor."-";
            $valor_documento.= $datos->valor_documento."-";
        }   
    }
    $codigo                     = trim($codigo,"-");
    $numero_documento_proveedor = trim($numero_documento_proveedor,"-");
    $valor_documento            = trim($valor_documento,"-");
    /*******************************************************/
    $elementos_documento[0] = $codigo;
    $elementos_documento[1] = $numero_documento_proveedor;
    $elementos_documento[2] = $valor_documento;

    HTTP::enviarJSON($elementos_documento);
    exit;

}elseif (isset($url_cruzarConFactura)) {
    $codigo            = $url_id_tabla;
    $documento_soporte = $url_documento_soporte;

    $datos_documento_cruzado_por_factura = array(
        "documento_cruzado_por_factura" => $documento_soporte, 
    );

    $documento_cruzado = SQL::modificar("correspondencia",$datos_documento_cruzado_por_factura,"codigo='$codigo'");
    $respuesta[0]      = true;

    if (!$documento_cruzado){
        $respuesta[0] = false;
        $respuesta[1] = $textos["ITEM_CRUZADO"];
    }else if($documento_cruzado) {
        $error   = true;
        $mensaje = $textos["ERROR_CRUZAR_ITEM"];
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

        //$tipos_documentos  = HTML::generarDatosLista("tipos_documentos", "codigo", "descripcion","");
        //$documentos_cruce  = HTML::generarDatosLista("correspondencia", "codigo", "numero_documento_proveedor","");

         /*** Definición de pestañas general ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::listaSeleccionSimple("*tipo_documento", $textos["TIPO_DOCUMENTO"], HTML::generarDatosLista("tipos_documentos", "codigo", "descripcion",""), "", array("title" => $textos["AYUDA_TIPO_DOCUMENTO"],"onClick"=>"ocultarValor(this)","onChange"=>"cruzarDocumentos()")),

                HTML::listaSeleccionSimple("*tipo_documento_cruce", $textos["TIPO_DOCUMENTO_CRUCE"], $tipos_documentos, "", array("title" => $textos["AYUDA_TIPO_DOCUMENTO"],"class" => "extracto","disabled" => "true")),

                //HTML::listaSeleccionSimple("*documento_cruce", $textos["DOCUMENTO_CRUCE"], $documentos_cruce, array("title" => $textos["AYUDA_DOCUMENTO_CRUCE"], "disabled" => "true"))
            ),
            array(
                HTML::campoTextoCorto("selector5", $textos["PROYECTO"], 40, 255, "", array("title" => $textos["AYUDA_PROYECTO"], "class" => "autocompletable extracto" ))
                .HTML::campoOculto("codigo_proyecto", "")
            ),
            array(  
                HTML::campoTextoCorto("*selector3", $textos["NIT_PROVEEDOR"], 40, 255, "", array("title" => $textos["AYUDA_NIT_PROVEEDOR"], "class" => "autocompletable extracto","onBlur"=>"cargarOrdenes()"))
                .HTML::campoOculto("documento_identidad_proveedor", "")
            ),
            array(
                HTML::listaSeleccionSimple("orden_compra", $textos["ORDEN_COMPRA"], "", "", array("title",$textos["AYUDA_ORDEN"],"class" => " extracto", "onBlur"=>"documentosCruce()"))
                .HTML::campoOculto("orden_compra_seleccionada", ""),

                HTML::campoTextoCorto("*documento_soporte",$textos["DOCUMENTO_SOPORTE"], 15, 15, "", array("title"=>$textos["AYUDA_DOCUMENTO_SOPORTE"], "class" => "autocompletable extracto", "onBlur" => "cargaValor(),existeDocumento(),valoresOrden()")),

                HTML::mostrarDato("total_orden", $textos["TOTAL_ORDEN"], $total_orden),

                HTML::mostrarDato("documentos_cruzados", $textos["TOTAL_CRUZADO"], $total_cruzado),

                HTML::campoTextoCorto("*valor_documento",$textos["VALOR_DOCUMENTO"], 15, 15, "", array("title"=>$textos["AYUDA_VALOR_DOCUMENTO"],"class"=>"extracto","onkeyup"=>"formatoMiles(this)","onChange"=>"validaTotalOrden()"))
            ),
            array(
                /*HTML::contenedor(
                    HTML::marcaChequeo("seleccion",$textos[""], 1, false, array("title"=>$textos["AYUDA_APLICA"], "class" => "extracto")),
                    array("id" => "seleccion", "style" => "display: none")
                ),*/
                HTML::contenedor(HTML::boton("botonCruzar", "", "cruzarItem(this);", "aceptar"), array("id" => "botonCruzar", "style" => "display: none")),
                
                HTML::contenedor(
                    HTML::generarTabla(
                        array("id","ITEM","NUMERO","VALOR"),"",array("C","I","I"),"listaDocumentos",false
                    ),
                    array("id"=>"documentos","class"=>"extracto")
                )
            ),
            array(
                HTML::campoTextoCorto("fecha_recepcion", $textos["FECHA_RECEPCION"], 10, 10, date("Y-m-d"), array("class" => "selectorFecha"),array("title" => $textos["AYUDA_FECHA_RECEPCION"])),

                HTML::campoTextoCorto("fecha_vencimiento", $textos["FECHA_VENCIMIENTO"], 10, 10, date("Y-m-d"), array("class" => "selectorFecha extracto"), array("title" => $textos["AYUDA_FECHA_VENCIMIENTO"])),

                //HTML::campoTextoCorto("fecha_envio", $textos["FECHA_ENVIO"], 10, 10, date("Y-m-d"), array("class" => "selectorFecha"), array("title" => $textos["AYUDA_FECHA_ENVIO"], "onBlur" => "validarItem(this);"))
            ),
            array(    
                HTML::campoTextoCorto("observaciones",$textos["OBSERVACIONES"], 50, 234, "",array("title"=>$textos["AYUDA_OBSERVACIONES"]))
            ),
            array(
                HTML::selectorArchivo("archivo", $textos["ARCHIVO_DOCUMENTO"], array("title" => $textos["AYUDA_ARCHIVO_DOCUMENTO"])),
                HTML::campoTextoCorto("*nombre_documento", $textos["NOMBRE_DOCUMENTO"], 15, 255, "", array("title" => $textos["AYUDA_NOMBRE_DOCUMENTO"]))
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
    $error     = false;
    $mensaje   = $textos["ITEM_ADICIONADO"];
    $indicador = 0;

    $llave                         = explode("-", $forma_selector3);
    $documento_identidad_proveedor = $llave[0];

    $llave_proyecto                = explode("-", $forma_selector5);
    $codigo_proyecto               = $llave_proyecto[0];

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

    /*if($forma_aplica==true){
        $forma_orden_compra = "";
    }
    if(!$forma_documento_soporte){
        $forma_documento_soporte = "";
    }*/
  
    /*** Insertar datos ***/
    if($forma_tipo_documento==10){
        $datos = array(
            "codigo_proyecto"               => 1,
            "documento_identidad_proveedor" => 0,
            "codigo_tipo_documento"         => $forma_tipo_documento,
            "numero_documento_proveedor"    => 0,
            "numero_orden_compra"           => 0,
            "valor_documento"               => 0,
            "estado"                        => '0',
            "fecha_recepcion"               => $forma_fecha_recepcion,
            "fecha_vencimiento"             => "", 
            "fecha_envio"                   => "",
            "observaciones"                 => $forma_observaciones,
            "estado_residente"              => '0',
            "estado_director"               => '0',
            "fecha_registro_residente"      => "",
            "fecha_registro_director"       => "",
            "documento_cruzado_por_factura" => ""
        ); 

    }else{
        /*** Validar el ingreso de los datos requeridos ***/
        if(empty($forma_selector5)){
            $error     = true;
            $mensaje   = $textos["PROYECTO_VACIO"];
            $indicador = 1;

        }elseif(empty($forma_selector3)){
            $error     = true;
            $mensaje   = $textos["PROVEEDOR_VACIO"];
            $indicador = 1;

        }elseif(empty($forma_tipo_documento)){
            $error     = true;
            $mensaje   = $textos["TIPO_DOCUMENTO_VACIO"];
            $indicador = 1;

        }elseif(empty($forma_nombre_documento)){
            $error     = true;
            $mensaje   = $textos["NOMBRE_DOCUMENTO_VACIO"];  
            $indicador = 1;  

        }elseif(empty($forma_documento_soporte)){
            $error     = true;
            $mensaje   = $textos["DOCUMENTO_SOPORTE_VACIO"];
            $indicador = 1;

        }elseif(empty($forma_fecha_recepcion)){
            $error     = true;
            $mensaje   = $textos["FECHA_RECEPCION_VACIO"];
            $indicador = 1;

        }elseif(empty($forma_fecha_vencimiento)){
            $error     = true;
            $mensaje   = $textos["FECHA_VENCIMIENTO_VACIO"];
            $indicador = 1;
        }

        $datos = array(
            "codigo_proyecto"               => $codigo_proyecto,
            "documento_identidad_proveedor" => $documento_identidad_proveedor,
            "codigo_tipo_documento"         => $forma_tipo_documento,
            "numero_documento_proveedor"    => $forma_documento_soporte,
            "numero_orden_compra"           => $forma_orden_compra_seleccionada,
            "valor_documento"               => $forma_valor_documento,
            "estado"                        => '0',
            "fecha_recepcion"               => $forma_fecha_recepcion,
            "fecha_vencimiento"             => $forma_fecha_vencimiento, 
            "fecha_envio"                   => "",
            "observaciones"                 => $forma_observaciones,
            "estado_residente"              => '0',
            "estado_director"               => '0',
            "fecha_registro_residente"      => "",
            "fecha_registro_director"       => "",
            "documento_cruzado_por_factura" => ""
        );
    }   

    if($indicador=0){
        $insertar = SQL::insertar("correspondencia", $datos);
    }

    /*** Error de insercion ***/
    if (!$insertar) {
        $error   = true;
        $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
    } else {

        //Asignar codigo siguiente de la tabla 
        $codigo = SQL::obtenerValor("correspondencia","MAX(codigo)","codigo>0");

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
                "nombre_tabla"          => "correspondencia",
                "codigo_registro_tabla" => $codigo,
                "tipo_archivo"          => '1'
            );

            $insertar_documento = SQL::insertar("documentos",$datos_documento);
        }
    }

    /*** Enviar datos con la respuesta del proceso al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
?>

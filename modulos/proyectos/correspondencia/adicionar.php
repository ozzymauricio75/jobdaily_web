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

}elseif (!empty($url_recargar)) {

    if ($url_elemento == "empresa") {
       $respuesta = HTML::generarDatosLista("empresas", "codigo", "nombre", "codigo = '$url_origen'");
    }

    if ($url_elemento == "sucursal") {
       $respuesta = HTML::generarDatosLista("sucursales", "codigo", "nombre", "codigo_empresa = '".$url_codigo."' AND codigo !='0'");
    }

    HTTP::enviarJSON($respuesta);
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
                //HTML::listaSeleccionSimple("*selector5", $textos["PROYECTO"], HTML::generarDatosLista("proyectos", "codigo", "nombre","codigo != 0"), "", array("title" => $textos["AYUDA_PROYECTO"])),
                HTML::campoTextoCorto("*selector5", $textos["PROYECTO"], 40, 255, "", array("title" => $textos["AYUDA_PROYECTO"], "class" => "autocompletable"))
                .HTML::campoOculto("codigo_proyecto", ""),

                //HTML::campoTextoCorto("nombre", $textos["NOMBRE"], 40, 60, "", array("readonly" => "true"), array("title" => $textos["AYUDA_NOMBRE"],"onBlur" => "validarItem(this);"))
            ),
            array(  
                HTML::campoTextoCorto("*selector3", $textos["NIT_PROVEEDOR"], 40, 255, "", array("title" => $textos["AYUDA_NIT_PROVEEDOR"], "class" => "autocompletable"))
                .HTML::campoOculto("documento_identidad_proveedor", "")
            ),
            array(
                HTML::listaSeleccionSimple("*tipo_documento", $textos["TIPO_DOCUMENTO"], HTML::generarDatosLista("tipos_documentos", "codigo", "descripcion","codigo != 0"), "", array("title" => $textos["AYUDA_TIPO_DOCUMENTO"])),

                HTML::campoTextoCorto("*documento_soporte",$textos["DOCUMENTO_SOPORTE"], 15, 15, "", array("title"=>$textos["AYUDA_DOCUMENTO_SOPORTE"], "onBlur" => "validarItem(this)")),

                HTML::campoTextoCorto("*valor_documento",$textos["VALOR_DOCUMENTO"], 15, 15, "", array("title"=>$textos["AYUDA_VALOR_DOCUMENTO"], "onkeyup"=>"formatoMiles(this)", "onchange"=>"formatoMiles(this)"))
            ),
            array(
                HTML::campoTextoCorto("fecha_recepcion", $textos["FECHA_RECEPCION"], 10, 10, date("Y-m-d"), array("class" => "selectorFecha"), array("title" => $textos["AYUDA_FECHA_RECEPCION"], "onBlur" => "validarItem(this);")),

                HTML::campoTextoCorto("fecha_vencimiento", $textos["FECHA_VENCIMIENTO"], 10, 10, date("Y-m-d"), array("class" => "selectorFecha"), array("title" => $textos["AYUDA_FECHA_VENCIMIENTO"], "onBlur" => "validarItem(this);")),

                //HTML::campoTextoCorto("fecha_envio", $textos["FECHA_ENVIO"], 10, 10, date("Y-m-d"), array("class" => "selectorFecha"), array("title" => $textos["AYUDA_FECHA_ENVIO"], "onBlur" => "validarItem(this);"))
            ),
            array(    
                HTML::campoTextoCorto("observaciones",$textos["OBSERVACIONES"], 50, 234, "",array("title"=>$textos["AYUDA_OBSERVACIONES"])),
            ),
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

/*** Adicionar los datos provenientes del formulario ***/
} elseif (!empty($forma_procesar)) {

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
            "codigo_proyecto"               => $codigo_proyecto,
            "documento_identidad_proveedor" => $documento_identidad_proveedor,
            "codigo_tipo_documento"         => $forma_tipo_documento,
            "numero_documento_proveedor"    => $forma_documento_soporte,
            "valor_documento"               => $forma_valor_documento,
            "estado"                        => '0',
            "fecha_recepcion"               => $forma_fecha_recepcion,
            "fecha_vencimiento"             => $forma_fecha_vencimiento,
            "fecha_envio"                   => " ",
            "observaciones"                 => $forma_observaciones
        );
        $insertar = SQL::insertar("correspondencia", $datos);

        /*** Error de insercion ***/
        if (!$insertar) {
            $error   = true;
            $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
        }
    }
    /*** Enviar datos con la respuesta del proceso al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

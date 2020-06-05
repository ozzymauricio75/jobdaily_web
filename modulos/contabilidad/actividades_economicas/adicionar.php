<?php

/**
*
* Copyright (C) 2008 Felinux Ltda
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

if(isset($url_completar)){
    if (($url_item) == "selector1") {
        echo SQL::datosAutoCompletar("seleccion_municipios", $url_q);
    }
    if (($url_item) == "selector2") {
        echo SQL::datosAutoCompletar("seleccion_actividades_economicas_dian", $url_q);
    }
    exit;
}

if(isset($url_verificarCodigo) && isset($url_codigo) && isset($url_municipio) && isset($url_codigo_dian)){
        $existe =SQL::existeItem("actividades_economicas","codigo_actividad_municipio",$url_codigo, "codigo_dane_municipio =$url_municipio AND codigo_dian = $url_codigo_dian "); // AND codigo_dane_municipio = $codigo_dane_municipio

        if($existe){
            $mensaje = $textos["ERROR_EXISTE_CODIGO_ACTIVIDAD"];
            HTTP::enviarJSON($mensaje);
        }
}

/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)){
    $error  = "";
    $titulo = $componente->nombre;
    
    $completar = false;
    $consulta_actividades = SQL::seleccionar(array("actividades_economicas_dian"),array("*"),"codigo_dian>0");
    if (!SQL::filasDevueltas($consulta_actividades)){
        $completar = true;
    }

    if (!$completar){
        /*** Definición de pestañas general ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::campoTextoCorto("*selector1", $textos["MUNICIPIOS"], 40, 255,"", array("title" => $textos["AYUDA_MUNICIPIOS"], "class" => "autocompletable"))
               .HTML::campoOculto("id_municipio","")
            ),
            array(
                HTML::campoTextoCorto("*selector2", $textos["CODIGO_DIAN"], 30, 255, "", array("title" => $textos["AYUDA_CODIGO_DIAN"],"Class" => "autocompletable"))
                .HTML::campoOculto("codigo_dian", "")
            ),
            array(
                HTML::campoTextoCorto("*codigo_actividad_municipio", $textos["ACTIVIDAD_MUNICIPIO"], 5, 5, "", array("title" => $textos["AYUDA_ACTIVIDAD_MUNICIPIO"],"onKeyPress" => "return campoEntero(event)", "onBlur" => "validarCodigo(this);"))
            ),
            array(
                HTML::campoTextoCorto("codigo_interno", $textos["CODIGO_INTERNO"], 4, 4, "", array("title" => $textos["AYUDA_CODIGO_INTERNO"],"onKeyPress" => "return campoEntero(event)"))
            ),
            array(
                HTML::campoTextoCorto("*descripcion", $textos["DESCRIPCION"], 30, 255, "", array("title" => $textos["AYUDA_DESCRIPCION"],"onBlur" => "validarItem(this);"))
            )
        );

        /*** Definición de botones ***/
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones);
    } else {
        $error     = $textos["ACTIVIDADES_DIAN"];
        $contenido = "";
    }

    /*** Enviar datos para la generación del formulario al script que originá la petición ***/
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

/*** Validación en línea de los datos provenientes del formulario ***/

}
/*** Adicionar los datos provenientes del formulario ***/
elseif (!empty($forma_procesar)){

     $forma_id_municipio       = explode(",",$forma_id_municipio);
     $codigo_iso               = $forma_id_municipio[0];
     $codigo_dane_departamento = $forma_id_municipio[1];
     $codigo_dane_municipio    = $forma_id_municipio[2];

     /*** Asumir por defecto que no hubo error ***/
     $error   = false;
     $mensaje = $textos["ITEM_ADICIONADO"];

    if(empty($forma_id_municipio) || empty($forma_selector1)){
        $error  = true;
        $mensaje = $textos["MUNICIPO_VACIO"];
    }elseif(empty($forma_codigo_actividad_municipio)){
        $error  = true;
        $mensaje = $textos["ACTIVIDAD_VACIO"];
    }elseif(empty($forma_codigo_dian) || empty($forma_selector2)){
        $error   = true;
        $mensaje = $textos["CODIGO_DIAN_VACIO"];
    }elseif(!empty($forma_codigo_actividad_municipio) && SQL::existeItem("actividades_economicas","codigo_actividad_municipio",$forma_codigo_actividad_municipio," codigo_dian=$forma_codigo_dian AND codigo_dane_municipio = $codigo_dane_municipio")){

        $error   = true;
        $mensaje = $textos["ERROR_EXISTE_CODIGO_ACTIVIDAD"];
    }elseif(empty($forma_descripcion)){
        $error  = true;
        $mensaje = $textos["DESCRIPCION_VACIO"];
    }else{
        /*** Insertar datos ***/
       
        if(empty($forma_codigo_interno)){
            
            $forma_codigo_interno=$forma_codigo_actividad_municipio;
            
        }

        $datos = array(
            "codigo_iso"                 => $codigo_iso,
            "codigo_dane_departamento"   => $codigo_dane_departamento,
            "codigo_dane_municipio"      => $codigo_dane_municipio,
            "codigo_actividad_municipio" => $forma_codigo_actividad_municipio,
            "codigo_dian"                => $forma_codigo_dian,
            "codigo_interno"             => $forma_codigo_interno,
            "descripcion"                => $forma_descripcion
        );
        $insertar = SQL::insertar("actividades_economicas", $datos);

        /*** Error de inserción ***/
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

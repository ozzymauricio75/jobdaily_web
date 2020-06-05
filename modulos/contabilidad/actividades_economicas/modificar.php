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
        $existe =SQL::existeItem("actividades_economicas","codigo_actividad_municipio",$url_codigo, "codigo_dane_municipio = $url_municipio AND codigo_dian = $url_codigo_dian "); // AND codigo_dane_municipio = $codigo_dane_municipio

        if($existe){
            $mensaje = $textos["ERROR_EXISTE_CODIGO_ACTIVIDAD"];
            HTTP::enviarJSON($mensaje);
        }
}

/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {

    /*** Verificar que se haya enviado el ID del elemento a modificar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_MODIFICAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {
        $error         = "";
        $titulo        = $componente->nombre;

        $forma_id_url               = explode("|",$url_id);
        $codigo_iso                 = $forma_id_url[0];
        $codigo_dane_departamento   = $forma_id_url[1];
        $codigo_dane_municipio      = $forma_id_url[2];
        $codigo_dian                = $forma_id_url[3];
        $codigo_actividad_municipio = $forma_id_url[4];

        $condicion = "codigo_iso='$codigo_iso' AND codigo_dane_departamento ='$codigo_dane_departamento' AND codigo_dane_municipio='$codigo_dane_municipio'";
        $condicion .= " AND codigo_dian='$codigo_dian' AND codigo_actividad_municipio ='$codigo_actividad_municipio'";

        $vistaConsulta = "actividades_economicas";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, $condicion);
        $datos         = SQL::filaEnObjeto($consulta);
        
        $condicion = $codigo_iso.'|'.$codigo_dane_departamento.'|'.$codigo_dane_municipio;
        $nombre_municipio  = SQL::obtenerValor("seleccion_municipios","nombre", "id = '$condicion'");
        $nombre_municipio  = explode("|", $nombre_municipio);
        $nombre_municipio_ = $nombre_municipio[0];
        $codigo_           = $nombre_municipio[1];

        $nombre_actividad_dian  = SQL::obtenerValor("actividades_economicas_dian","descripcion", "codigo_dian = '$codigo_dian'");

        /*** Definición de pestañas ***/
        $formularios["PESTANA_GENERAL"] = array(
             array(
                HTML::campoTextoCorto("*selector1", $textos["MUNICIPIOS"], 40, 255,$nombre_municipio_, array("title" => $textos["AYUDA_MUNICIPIOS"], "class" => "autocompletable"))
                .HTML::campoOculto("id_municipio",$codigo_)

            ),
             array(
                HTML::campoTextoCorto("*selector2", $textos["CODIGO_DIAN"], 30, 255,$nombre_actividad_dian, array("title" => $textos["AYUDA_CODIGO_DIAN"],"Class" => "autocompletable"))
                .HTML::campoOculto("codigo_dian",$codigo_dian)
                .HTML::campoOculto("codigo_dian_oculto",$codigo_dian)
            ),
            array(
                HTML::campoTextoCorto("*codigo_actividad_municipio", $textos["ACTIVIDAD_MUNICIPIO"], 4, 4, $datos->codigo_actividad_municipio, array("title" => $textos["AYUDA_ACTIVIDAD_MUNICIPIO"],"onKeyPress" => "return campoEntero(event)"))
            ),
            array(
                HTML::campoTextoCorto("codigo_interno", $textos["CODIGO_INTERNO"], 4, 4, $datos->codigo_interno, array("title" => $textos["AYUDA_CODIGO_INTERNO"],"onKeyPress" => "return campoEntero(event)"))
            ),
            array(
                HTML::campoTextoCorto("descripcion", $textos["DESCRIPCION"], 30, 255,$datos->descripcion, array("title" => $textos["AYUDA_DESCRIPCION"]))
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


     /*Datos enviados de la vista*/
     $forma_id_url               = explode("|",$forma_id);
     $codigo_iso                 = $forma_id_url[0];
     $codigo_dane_departamento   = $forma_id_url[1];
     $codigo_dane_municipio      = $forma_id_url[2]; 
     $codigo_dian                = $forma_id_url[3];
     $codigo_actividad_municipio = $forma_id_url[4];

     /*Datos que cambia dependiendo del selector*/
     $forma_id_municipio                = explode(",",$forma_id_municipio);
     $codigo_iso_selector               = $forma_id_municipio[0];
     $codigo_dane_departamento_selector = $forma_id_municipio[1];
     $codigo_dane_municipio_selector    = $forma_id_municipio[2];

     //var_dump($forma_id_municipio);

    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_MODIFICADO"];

    if(empty($forma_id_municipio) || empty($forma_selector1)){
		$error  = true;
		$mensaje = $textos["MUNICIPO_VACIO"];
	}elseif(empty($forma_codigo_actividad_municipio)){
		$error  = true;
		$mensaje = $textos["ACTIVIDAD_VACIO"];
	}elseif(!empty($forma_codigo_actividad_municipio) && SQL::existeItem("actividades_economicas","codigo_actividad_municipio",$forma_codigo_actividad_municipio,"codigo_actividad_municipio != $codigo_actividad_municipio AND codigo_dian=$forma_codigo_dian AND codigo_dane_municipio = $codigo_dane_municipio")){

        $error   = true;
        $mensaje = $textos["ERROR_EXISTE_CODIGO_ACTIVIDAD"];
         }elseif(empty($forma_descripcion)){
        $error  = true;
        $mensaje = $textos["DESCRIPCION_VACIO"];
        }elseif(empty($forma_codigo_dian) || empty($forma_selector2)){
        $error   = true;
        $mensaje = $textos["CODIGO_DIAN_VACIO"];
        }else{
        /*** Insertar datos ***/
        $datos = array(
            "codigo_iso"                 => $codigo_iso_selector,
            "codigo_dane_departamento"   => $codigo_dane_departamento_selector,
            "codigo_dane_municipio"      => $codigo_dane_municipio_selector,
            "codigo_actividad_municipio" => $forma_codigo_actividad_municipio,
            "codigo_dian"                => $forma_codigo_dian,
            "codigo_interno"             => $forma_codigo_interno,
            "descripcion"                => $forma_descripcion
        );

       

        $condicion     = "codigo_iso ='$codigo_iso' AND codigo_dane_departamento='$codigo_dane_departamento' AND codigo_dane_municipio ='$codigo_dane_municipio'";
        $condicion     .= " AND codigo_dian ='$codigo_dian' AND codigo_actividad_municipio='$codigo_actividad_municipio'";
        $consulta = SQL::modificar("actividades_economicas", $datos, $condicion);
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

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



if (!empty($url_generar)) {
    $error  = "";
    $titulo = $componente->nombre;

    $consecutivo = (int)SQL::obtenerValor("motivos_tiempo_no_laborado","max(codigo)","");
    if($consecutivo){
        $consecutivo++;
    }else{
        $consecutivo=1;
    }

    $formularios["PESTANA_GENERAL"] = array(
        array(
            HTML::campoTextoCorto("*codigo", $textos["CODIGO"], 5, 3, $consecutivo, array("title" => $textos["AYUDA_MOTIVO"], "onblur" => "validarItem(this);", "onKeyPress" => "return campoEntero(event)")),
            HTML::campoTextoCorto("*descripcion", $textos["DESCRIPCION"], 25, 255, "", array("title" => $textos["AYUDA_NOMBRE_MOTIVO"], "onblur" => "validarItem(this);"))
        )
    );

    $botones = array(
        HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar")
    );

    $contenido = HTML::generarPestanas($formularios, $botones);

    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

} elseif (!empty($url_validar)) {

    if($url_item == "codigo"){
        $existe = SQL::existeItem("motivos_tiempo_no_laborado","codigo",$url_valor,"codigo != 0");
        if($existe){
            HTTP::enviarJSON($textos["ERROR_EXISTE_MOTIVO"]);
        }
    }else if($url_item == "descripcion"){
        $existe = SQL::existeItem("motivos_tiempo_no_laborado","descripcion",$url_valor,"codigo != 0");
        if($existe){
            HTTP::enviarJSON($textos["ERROR_EXISTE_NOMBRE_MOTIVO"]);
        }
    }

} elseif (!empty($forma_procesar)) {

    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];
    
    $codigo  = (int)$forma_codigo;
    
    if(empty($forma_codigo) || $codigo == 0){
        $error = true;
        $mensaje = $textos["ERROR_CODIGO_VACIO"];
    } else if(empty($forma_descripcion)){
        $error = true;
        $mensaje = $textos["ERROR_DESCRIPCION_VACIO"];
    } else if(SQL::existeItem("motivos_tiempo_no_laborado","codigo",$forma_codigo,"codigo != 0")){
        $error = true;
        $mensaje = $textos["ERROR_EXISTE_MOTIVO"];
    } else if(SQL::existeItem("motivos_tiempo_no_laborado","descripcion",$forma_descripcion,"codigo != 0")){
        $error = true;
        $mensaje = $textos["ERROR_EXISTE_NOMBRE_MOTIVO"];
    } else {
	
        $datos = array (
            "codigo"      => $forma_codigo,
            "descripcion" => $forma_descripcion
        );
	  
        $insertar = SQL::insertar("motivos_tiempo_no_laborado", $datos);

        if (!$insertar) {
            $error   = true;
            $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
        }
    }
       
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

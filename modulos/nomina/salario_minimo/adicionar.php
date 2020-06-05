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

// Generar el formulario para la captura de datos
if (!empty($url_generar)) {
    $error  = "";
    $titulo = $componente->nombre;
    
        
    // Definicion de pestana personal
    $formularios["PESTANA_GENERAL"] = array(
        array(    
            HTML::campoTextoCorto("*codigo", $textos["CODIGO"], 5, 3, "", array("title" => $textos["AYUDA_CODIGO"], "onblur" => "validarItem(this);","onKeyPress" => "return campoEntero(event)"))
        ),
        array(    
            HTML::campoTextoCorto("*fecha_inicia", $textos["FECHA_INICIA"], 10, 10, "", array("title" => $textos["AYUDA_FECHA_INICIA"], "class" => "selectorFecha"))
        ), 
        array(
            HTML::campoTextoCorto("*valor", $textos["VALOR"], 10, 18, "", array("title" => $textos["AYUDA_VALOR"], "onKeypress" => "return campoDecimal(event)")),
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

} 
   
// Adicionar los datos provenientes del formulario
 elseif (!empty($forma_procesar)) {

    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];

    $codigo = (int)$forma_codigo;
    $valor  = (int)$forma_valor;
        
    if(empty($forma_codigo) || $codigo == 0){
        $error = true;
        $mensaje = $textos["ERROR_CODIGO_VACIO"];
    }elseif(empty($forma_fecha_inicia)){
        $error = true;
        $mensaje = $textos["ERROR_FECHA_VACIA"];
    }elseif(empty($forma_valor) || $valor==0){
        $error = true;
        $mensaje = $textos["ERROR_VALOR_VACIO"];
    }elseif(SQL::existeItem("salario_minimo","fecha",$forma_fecha_inicia)){
        $error = true;
        $mensaje = $textos["ERROR_EXISTE_FECHA"];
    }elseif(SQL::existeItem("salario_minimo","codigo",$forma_codigo)){
        $error = true;
        $mensaje = $textos["ERROR_EXISTE_CODIGO"];
    }else{
        $datos = array (
            "codigo" => $forma_codigo,
            "fecha"  => $forma_fecha_inicia,
            "valor"	 => $forma_valor
        );
        $insertar = SQL::insertar("salario_minimo", $datos);

        // Error de insercion
        if (!$insertar) {
            $error   = true;
            $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
        }
    }
    // Enviar datos con la respuesta del proceso al script que origino la peticion
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
    
}
?>

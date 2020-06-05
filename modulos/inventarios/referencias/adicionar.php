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
* de APTITUD PARA UN PROPÓITO DETERMINADO. Consulte los detalles de
* la Licencia Pública General GNU para obtener una información más
* detallada.
*
* Debería haber recibido una copia de la Licencia Pública General GNU
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
**/

/*** Devolver datos para autocompletar la búsqueda ***/
if (isset($url_completar)){
    if (($url_item) == "selector1") {
        echo SQL::datosAutoCompletar("seleccion_articulos", $url_q);
    }
    if (($url_item) == "selector2") {
        echo SQL::datosAutoCompletar("seleccion_proveedores", $url_q);
    }
    exit;
}/*** Generar el formulario para la captura de datos ***/
else if (!empty($url_generar)) {
   $unidades1 = SQL::seleccionar(array("seleccion_articulos"),array("id"),"");
   $unidades2 = SQL::seleccionar(array("seleccion_proveedorer"),array("id"),"");

    if((!SQL::filasDevueltas($unidades1))||(!SQL::filasDevueltas($unidades2))){
    $error     = $textos["ERROR_FALTA_ARTICULO_PROVEEDOR"];
    $titulo    = "";
    $contenido = "";
    }else{

        $error         = "";
        $titulo        = $componente->nombre;

        /*** Definición de pestañas general ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::campoTextoCorto("*selector1", $textos["CODIGO_ARTICULO"], 30, 30, "", array("title" => $textos["AYUDA_ARTICULO"], "class" => "autocompletable")).HTML::campoOculto("codigo_interno_articulo", "")
            ),
            array(
                HTML::campoTextoCorto("*selector2", $textos["PROVEEDOR"], 30, 255, "", array("title" => $textos["AYUDA_PROVEEDOR"], "class" => "autocompletable")).HTML::campoOculto("documento_identidad_proveedor", "")
            ),
            array(
                HTML::campoTextoCorto("*referencia", $textos["REFERENCIA"], 30, 30, "", array("title"=>$textos["AYUDA_REFERENCIA"]))
            ),

            array(
                HTML::campoTextoCorto("codigo_barras", $textos["CODIGO_BARRAS"], 13, 13, "", array("title"=>$textos["AYUDA_BARRAS"],"onBlur" => "validarItem(this);","onKeyPress" => "return campoEntero(event)"))
            ),
            array(
                HTML::marcaChequeo("principal", $textos["PRINCIPAL"])
            )
        );

        /*** Definición de botones ***/
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones);
    }
    /*** Enviar datos para la generación del formulario al script que originó la petición ***/
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

/*** Adicionar los datos provenientes del formulario ***/
} elseif (!empty($forma_procesar)) {
    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];

    $descripcion_articulo = $forma_selector1."|".$forma_codigo_interno_articulo;
    $nombre_proveedor     = $forma_selector2."|".$forma_documento_identidad_proveedor;

    if (empty($forma_selector1)||empty($forma_selector2)||empty($forma_referencia)){
        $error   = true;
        $mensaje = $textos["ERROR_DATOS_INCOMPLETOS"];
    }elseif(!SQL::existeItem("seleccion_articulos", "descripcion", $descripcion_articulo,"")){
        $error   = true;
        $mensaje = $textos["ERROR_NO_EXISTE_ARTICULO"];
    }elseif(!SQL::existeItem("seleccion_proveedores", "nombre", $nombre_proveedor,"")){
        $error   = true;
        $mensaje = $textos["ERROR_NO_EXISTE_PROVEEDOR"];
    }else{
        if (!isset($forma_principal)){
            $forma_principal = "0";
        }
        $datos = array(
            "codigo_interno_articulo"       => $forma_codigo_interno_articulo,
            "documento_identidad_proveedor" => $forma_documento_identidad_proveedor,
            "referencia"                    => $forma_referencia,
            "codigo_barras"                 => $forma_codigo_barras,
            "principal"                     => $forma_principal
        );
        
        $insertar = SQL::insertar("referencias_por_proveedor", $datos);

        /*** Error de inserción ***/
        if (!$insertar) {
            $error   = true;
            $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
        }
    }

    /*** Enviar datos con la respuesta del proceso al script que originá la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

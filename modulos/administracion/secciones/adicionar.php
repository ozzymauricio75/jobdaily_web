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

/*** Generar el formulario para la captura de datos ***/

if(isset($url_validarItemsllaves))
{
    if($url_item=="codigo" && !empty($url_valor))
    {

        $existe = SQL::existeItem("secciones", "codigo",$url_valor,$url_condicion." AND codigo!=0");
        if ($existe) {
            $mensaje = $textos["ERROR_EXISTE_CODIGO"];
            HTTP::enviarJSON($mensaje);
        }
    }
}

if (!empty($url_generar)) {
    $error     = "";
    $titulo    = $componente->nombre;
    $contenido = "";

    $vistaConsulta     = "buscador_bodegas";

    $columnas          = SQL::obtenerColumnas($vistaConsulta);
    $consulta          = SQL::seleccionar(array($vistaConsulta), $columnas,"");

    $sucursales        = HTML::generarDatosLista("sucursales", "codigo", "nombre","codigo != 0");
    $bodegas           = HTML::generarDatosLista("bodegas", "codigo", "nombre", "codigo>0 AND codigo_sucursal = '".array_shift(array_keys($sucursales))."'");

    if (!empty($bodegas)){

        $codigo_items_llave_primaria="codigo:codigo,codigo_sucursal:sucursal,codigo_bodega:bodega";

        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::listaSeleccionSimple("*sucursal", $textos["ALMACEN"],$sucursales , "", array("onChange" => "recargarLista('sucursal','bodega');","title" => $textos["AYUDA_ALMACEN"]))
            ),
            array(
                HTML::listaSeleccionSimple("*bodega", $textos["BODEGA"],$bodegas, "", array("title" => $textos["AYUDA_BODEGA"]))
            ),
            array(
                 HTML::campoTextoCorto("*codigo", $textos["CODIGO"], 4, 4, "",  array("title" => $textos["AYUDA_CODIGO"], "onKeyPress" => "return campoEntero(event)","onBlur" => "validarItemsllaves(this,'$codigo_items_llave_primaria');"))
            ),
            array(
                HTML::campoTextoCorto("*nombre", $textos["NOMBRE"], 40, 60, "", array("title" => $textos["AYUDA_NOMBRE"],"onBlur" => "validarItem(this);"))
            ),
            array(
                HTML::campoTextoCorto("*descripcion", $textos["DESCRIPCION"], 40, 60, "", array("title" => $textos["AYUDA_DESCRIPCION"],"onBlur" => "validarItem(this);"))
            )
        );

        /*** Definicion de botones ***/
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones);
    } else {
        $error = $textos["CREAR_BODEGAS"];
    }

    /*** Enviar datos para la generacion del formulario al script que origino la peticion ***/
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

/*** Validar los datos provenientes del formulario ***/
}elseif (!empty($url_recargar)) {


    if ($url_elemento == "bodega") {

        $respuesta = HTML::generarDatosLista("bodegas", "codigo", "nombre","codigo_sucursal = '$url_origen'");
    }


    HTTP::enviarJSON($respuesta);

/*** Validación en línea de los datos provenientes del formulario ***/
} elseif (!empty($forma_procesar)) {

    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];


    $condicion = "codigo=$forma_codigo AND codigo_sucursal=$forma_sucursal AND codigo_bodega=$forma_bodega AND codigo!=0";

    /*** Validar el ingreso de los datos requeridos ***/
    if (empty($forma_codigo)){
        $error   = true;
        $mensaje = $textos["ERROR_CODIGO_VACIO"];

    } else if(empty($forma_nombre)){
        $error   = true;
        $mensaje = $textos["ERROR_NOMBRE"];

    } else if(empty($forma_descripcion)){
        $error   = true;
        $mensaje = $textos["ERROR_DESCRIPCION"];

    } else if(empty($forma_bodega)){
        $error   = true;
        $mensaje = $textos["ERROR_BODEGA"];

    } elseif (SQL::existeItem("secciones","codigo",$forma_codigo,$condicion)) {
        $error   = true;
        $mensaje =  $textos["ERROR_EXISTE_CODIGO"];

    } elseif (!empty($forma_codigo) && !Cadena::validarNumeros($forma_codigo)) {
        $error   = true;
        $mensaje =  $textos["ERROR_FORMATO_CODIGO"];

    } elseif (!empty($forma_nombre) && SQL::existeItem("secciones", "nombre", $forma_nombre, "codigo = '$forma_codigo'")) {
            $error   = true;
            $mensaje =  $textos["ERROR_EXISTE_NOMBRE"];

    } elseif (!empty($forma_descripcion) && SQL::existeItem("secciones", "descripcion", $forma_descripcion, "codigo = '$forma_codigo'")) {
        $error   = true;
        $mensaje =  $textos["ERROR_EXISTE_DESCRIPCION"];

    } else {
        $datos = array(
            "codigo_sucursal" => $forma_sucursal,
            "codigo_bodega"   => $forma_bodega,
            "codigo"          => $forma_codigo,
            "nombre"          => $forma_nombre,
            "descripcion"     => $forma_descripcion
        );

        $insertar = SQL::insertar("secciones", $datos);

        /*** Error de insercion ***/
        if (!$insertar) {
            $error   = true;
            $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
            }
      }

    /*** Enviar datos con la respuesta del proceso al script que origin? la petici?n ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

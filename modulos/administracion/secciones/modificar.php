<?php

/**
*
* Copyright (C) 2020 Jobdaily
* Raul Mauricio Oidor Lozano <ozzymauricio75@gmail.com>
* 
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

/*
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
}*/


/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {

    /*** Verificar que se haya enviado el ID del elemento a modificar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_MODIFICAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {
        $vistaConsulta = "buscador_secciones";
        $condicion     = "id = '$url_id'";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, $condicion);
        $datos         = SQL::filaEnObjeto($consulta);

        $sucursales        =  HTML::generarDatosLista("sucursales", "codigo", "nombre","codigo != 0");
        $bodegas =  HTML::generarDatosLista("bodegas", "codigo", "nombre","codigo !=0 AND codigo_sucursal=$datos->codigo_sucursal");


        
        
        $error         = "";
        $titulo        = $componente->nombre;

        $codigo_items_llave_primaria="codigo:codigo,codigo_sucursal:sucursal,codigo_bodega:bodega";

        /*** Definición de pestañas ***/
        $formularios["PESTANA_GENERAL"] = array(
             array(
            HTML::listaSeleccionSimple("*sucursal", $textos["ALMACEN"],$sucursales,$datos->codigo_sucursal, array("onChange" => "recargarLista('sucursal','bodega');","title" => $textos["AYUDA_ALMACEN"]))
            ),
            array(
                HTML::mostrarDato("codigo", $textos["CODIGO"],$datos->codigo ,array("title" => $textos["AYUDA_CODIGO"], "onBlur" => "validarItem(this);")) //,"onBlur" => "validarItemsllaves(this,'$codigo_items_llave_primaria');"
                .HTML::campoOculto("codigo_seccion",$datos->codigo ),
            ),
            array(
                HTML::listaSeleccionSimple("*bodega", $textos["BODEGA"],$bodegas, $datos->codigo_bodega, array("title" => $textos["AYUDA_BODEGA"]))
                .HTML::campoOculto("id_bodega",$datos->codigo_bodega),
            ),
            array(
                HTML::campoTextoCorto("*nombre", $textos["NOMBRE"], 40, 60, $datos->nombre, array("title" => $textos["AYUDA_NOMBRE"],"onBlur" => "validarItem(this);"))
            ),
            array(
                 HTML::campoTextoCorto("*descripcion", $textos["DESCRIPCION"], 40, 60, $datos->descripcion, array("title" => $textos["AYUDA_DESCRIPCION"]))
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

/*** Validar los datos provenientes del formulario ***/
}elseif (!empty($url_recargar)) {

   
    if ($url_elemento == "bodega") {

        $respuesta = HTML::generarDatosLista("bodegas", "codigo", "nombre","codigo_sucursal = '$url_origen'");


    }

   
    HTTP::enviarJSON($respuesta);

/*** Validación en línea de los datos provenientes del formulario ***/
}elseif (!empty($forma_procesar)) {

    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_MODIFICADO"];


    $llave_primaria_seccion = explode("|",$forma_id);
    $codigo_sucursal        = $llave_primaria_seccion[0];
    $codigo_bodega          = $llave_primaria_seccion[1];
    $codigo_seccion         = $llave_primaria_seccion[2];


    $condicion = "codigo!=$codigo_seccion AND codigo_sucursal!=$codigo_sucursal AND codigo_bodega!=$codigo_bodega";
    $condicion2 = "codigo=$codigo_seccion AND codigo_sucursal=$codigo_sucursal AND codigo_bodega=$codigo_bodega";

    /*** Validar el ingreso de los datos requeridos ***/
    if (empty($forma_nombre)||
        empty($forma_bodega)||
        empty($forma_descripcion)
    ){
        $error   = true;
        $mensaje = $textos["ERROR_DATOS_INCOMPLETOS"];

    } elseif (SQL::existeItem("secciones","codigo",$forma_codigo_seccion, $condicion)) {
        $error   = true;
        $mensaje =  $textos["ERROR_EXISTE_CODIGO"];
        
    } elseif (SQL::existeItem("secciones", "nombre", $forma_nombre, $condicion)) {
        $error   = true;
        $mensaje =  $textos["ERROR_EXISTE_NOMBRE"];
        
    } else {

        $datos = array(
            "codigo_sucursal" => $forma_sucursal,
            "codigo_bodega"   => $forma_bodega,
            "codigo"          => $forma_codigo_seccion,
            "nombre"          => $forma_nombre,
            "descripcion"     => $forma_descripcion
        );

        $consulta = SQL::modificar("secciones", $datos,$condicion2);

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

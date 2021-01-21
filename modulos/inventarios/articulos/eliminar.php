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
if (!empty($url_generar)) {

    /*** Verificar que se haya enviado el ID del elemento a consultar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_CONSULTAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {
        $llave                         = explode("|",$url_id);
        $url_id                        = $llave[0];
        $documento_identidad_proveedor = $llave[1];

        $vistaConsulta = "articulos";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo = '$url_id'");
        $datos         = SQL::filaEnObjeto($consulta);
        $error         = "";
        $titulo        = $componente->nombre;

        $consulta_imagen = SQL::seleccionar(array("imagenes"), array("id_asociado","categoria","ancho","alto"), "id_asociado = '$url_id' AND categoria ='2'");
        $imagen        = SQL::filaEnObjeto($consulta_imagen);

        if ($imagen){
            $muestra_imagen = HTML::imagen(HTTP::generarURL("VISUIMAG")."&id=".$imagen->id_asociado, array("width" => $imagen->ancho, "height" => $imagen->alto));
        } else {
            $muestra_imagen = "";
        }
        

        /***Obtener datos de la tabla de articulos ***/
        $impuesto_compra        = SQL::obtenerValor("tasas", "descripcion", "codigo = '$datos->codigo_impuesto_compra'");
        $impuesto_venta         = SQL::obtenerValor("tasas", "descripcion", "codigo = '$datos->codigo_impuesto_venta'");
        $marca                  = SQL::obtenerValor("marcas", "descripcion", "codigo = '$datos->codigo_marca'");
        $unidad_venta           = SQL::obtenerValor("unidades", "nombre", "codigo = '$datos->codigo_unidad_venta'");
        $unidad_compra          = SQL::obtenerValor("unidades", "nombre", "codigo = '$datos->codigo_unidad_compra'");
        $unidad_presentacion    = SQL::obtenerValor("unidades", "nombre", "codigo = '$datos->codigo_unidad_presentacion'");
        $pais                   = SQL::obtenerValor("paises", "nombre", "codigo_iso = '$datos->codigo_iso'");        
        $nombre_proveedor       = SQL::obtenerValor("seleccion_proveedores", "nombre", "id = '$documento_identidad_proveedor'");
        $nombre_proveedor       = explode("|",$nombre_proveedor);
        $nombre_proveedor       = $nombre_proveedor[0];
        $costo                  = SQL::obtenerValor("lista_precio_articulos", "costo", "codigo_articulo = '$url_id'");     
        $costo                  = number_format($costo,2);  
        $referencia             = SQL::obtenerValor("referencias_proveedor", "referencia", "codigo_articulo = '$url_id' AND principal = '1' AND documento_identidad_proveedor = '$documento_identidad_proveedor'");
        $codigo_barras          = SQL::obtenerValor("referencias_proveedor", "codigo_barras", "codigo_articulo = '$url_id' AND principal = '1' AND documento_identidad_proveedor = '$documento_identidad_proveedor'");

        $tipo_articulo= array(
            "1" => $textos["MATERIA_PRIMA"],
            "2" => $textos["PRODUCTO_TERMINADO"]
        );

        $manejo_inventario = array(
            "1" => $textos["INVENTARIO_VALORIZADO"],
            "2" => $textos["INVENTARIO_SOLO_KARDEX"]
        );

        $activo = array(
            "0" => $textos["INACTIVO"],
            "1" => $textos["ACTIVO"]
        );	

        $imprimir = $datos->imprime_listas;
        switch($imprimir){
            case 1: $texto_imprime = $textos["SIEMPRE_IMPRIME"];
                break;
            case 2: $texto_imprime = $textos["OCASIONALMENTE_IMPRIME"];
                break;
            case 3: $texto_imprime = $textos["NUNCA_IMPRIME"];
                break;
        }

        /*** Obtener referencias alternas ***/
        $consulta_referencias = SQL::seleccionar(array("referencias_proveedor"), array("*"), "codigo_articulo = '$url_id' AND principal='0'");

        if (SQL::filasDevueltas($consulta_referencias)) {
            $item_referencias = array();
            while ($datos_referencias = SQL::filaEnObjeto($consulta_referencias)) {

                $item_referencias[] = array(
                    $url_id,
                    $datos_referencias->referencia,
                    $datos_referencias->codigo_barras
                );
            }
        }

        /*** Definición de pestaña general ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::mostrarDato("codigo   ", $textos["CODIGO"], $datos->codigo),
                HTML::mostrarDato("tipo_articulo", $textos["TIPO_ARTICULO"], $tipo_articulo[$datos->tipo_articulo])
            ),
            array(
                HTML::mostrarDato("id_proveedor", $textos["PROVEEDOR"], $nombre_proveedor)
            ),
            array(
                HTML::mostrarDato("codigo_alfanumerico", $textos["REFERENCIA_PROVEEDOR"], $referencia),
                HTML::mostrarDato("codigo_barras", $textos["CODIGO_BARRAS"], $codigo_barras)
            ),
            array(
                HTML::mostrarDato("descripcion", $textos["DESCRIPCION"], $datos->descripcion),
                HTML::mostrarDato("costo", $textos["COSTO"], $costo)
            ),
            array(
                HTML::mostrarDato("imprime", $textos["ESTADO_IMPRESION"], $texto_imprime)
            ),
            array(
                HTML::mostrarDato("ficha_tecnica", $textos["FICHA_TECNICA"], $datos->ficha_tecnica)
            ),
            array(
                HTML::mostrarDato("alto", $textos["ALTO"], $datos->alto),
                HTML::mostrarDato("ancho", $textos["ANCHO"], $datos->ancho),
                HTML::mostrarDato("profundidad", $textos["PROFUNDIDAD"], $datos->profundidad),
                HTML::mostrarDato("peso", $textos["PESO"], $datos->peso)
            )
        );

        /*** Definición de pestaña estructura de grupo***/
        $formularios["PESTANA_GRUPO"] = array(
            array(
                HTML::contenedor(HTML::arbolGrupos("arbolGrupos",$datos->codigo_estructura_grupo, $datos->codigo_estructura_grupo))
            )
        );

        /*** Definición de pestaña de datos operativos de articulos***/
        $formularios["PESTANA_DATOS"] = array(
            array(
                HTML::mostrarDato("impuesto_compra", $textos["IMPUESTO_COMPRA"], $impuesto_compra),
                HTML::mostrarDato("impuesto_venta", $textos["IMPUESTO_VENTA"], $impuesto_venta)
            ),
            array(
                HTML::mostrarDato("marca", $textos["MARCA"], $marca)
            ),
            array(
                HTML::mostrarDato("manejo_inventario", $textos["MANEJO_INVENTARIO"], $manejo_inventario[$datos->manejo_inventario])
            ),
            array(
                HTML::mostrarDato("unidad_compra", $textos["UNIDAD_COMPRA"], $unidad_compra)
            ),
            array(
                HTML::mostrarDato("pais", $textos["PAIS"], $pais),
                HTML::mostrarDato("activo", $textos["ESTADO"], $activo[$datos->activo])
            )
        );

        if ($imagen) {
            $id_imagen = $imagen->id_asociado."|".$imagen->categoria;

            $formularios["PESTANA_IMAGEN"] = array(
                array(
                    HTML::imagen(HTTP::generarURL("VISUIMAG")."&id=".$id_imagen, array("width" => $imagen->ancho, "height" => $imagen->alto))
                )
            );
        }

        if(isset($item_referencias)){
            $formularios["PESTANA_REFERENCIA"] = array(
                array(
                    HTML::generarTabla(
                        array("id","REFERENCIA","CODIGO_BARRAS"),
                        $item_referencias,
                        array("I","D"),
                        "lista_items",
                        false)
                )
            );
        }

        /*** Definición de botones ***/
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "eliminarItem('$url_id');", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones);
    }

    /*** Enviar datos para la generación del formulario al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

/*** Eliminar el elemento seleccionado ***/
} elseif (!empty($forma_procesar)) {
    $consulta   = SQL::eliminar("articulos_proveedor", "codigo_articulo = '$forma_id'");
    $consulta   = SQL::eliminar("referencias_proveedor", "codigo_articulo = '$forma_id'");
    $consulta   = SQL::eliminar("listado_precio_articulos", "codigo_articulo = '$forma_id'");
    $consulta   = SQL::eliminar("imagenes", "id_asociado = '$forma_id' AND categoria = '2'");
    $consulta   = SQL::eliminar("articulos", "codigo = '$forma_id'");
    $datos = array(
                "activo" => 0
            );
                        
    $condicion  = "codigo ='$forma_id'";
    $modificar  = SQL::modificar("articulos", $datos, $condicion);

    if ($modificar) {
        $error   = false;
        $mensaje = $textos["ITEM_ELIMINADO"];
    } else {
        $error   = true;
        $mensaje = $textos["ERROR_ELIMINAR_ITEM"];
    }

    /*** Enviar datos con la respuesta del proceso al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

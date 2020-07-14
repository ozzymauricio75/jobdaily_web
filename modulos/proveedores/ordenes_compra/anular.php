<?php

/**
*
* Copyright (C) 2020 Jobdaily
* Raul Mauricio Oidor Lozano <ozzymauricio75@gmail.com>
*
* Este archivo es parte de:
* Jobdaily :: Software empresarial a la medida
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

if (!empty($url_generar)) {
    /*** Verificar que se haya enviado el ID del elemento a consultar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_CONSULTAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {

        $error          = "";
        $titulo         = $componente->nombre;
        $contenido      = "";

        $vistaConsulta  = "ordenes_compra";
        $columnas       = SQL::obtenerColumnas($vistaConsulta);
        $consulta       = SQL::seleccionar(array($vistaConsulta), $columnas, "id = '$url_id'");
        $datos          = SQL::filaEnObjeto($consulta);

        /*** Verificar si la orden de compra puede ser anulada ***/
        $estado             = $datos->estado;
        $tablas             = array ("a" => "ordenes_compra", "b" => "movimiento_ordenes_compra");
        $columnas           = array ("a.codigo");
        $condicion          = "a.codigo = b.codigo_orden_compra AND a.codigo = '$url_id'";
        $consulta_entradas  = SQL::seleccionar($tablas, $columnas, $condicion);
        $entradas           = SQL::filasDevueltas($consulta_entradas);

        if (($estado==0) && ($estado==1)) {

            $codigo_orden_compra           = $url_id;
            $numero_consecutivo            = $datos->numero_consecutivo;
            $codigo_usuario_orden_compra   = $datos->codigo_usuario_orden_compra;
            $codigo_comprador              = $datos->codigo_comprador;
            $codigo_sucursal               = $datos->codigo_sucursal;
            $documento_identidad_proveedor = $datos->documento_identidad_proveedor;
            $fecha_documento               = $datos->fecha_documento;
            $estado                        = $datos->estado;
            $observaciones                 = $datos->observaciones;
            $descuento_global1             = $datos->descuento_global1;

            $documento_comprador   = SQL::obtenerValor("compradores", "documento_identidad", "codigo = '".$codigo_comprador."'");
            $primer_nombre         = SQL::obtenerValor("terceros", "primer_nombre", "documento_identidad = '".$documento_comprador."'");
           //aca voy
            $almacen            = SQL::obtenerValor("sucursales","nombre","id = '$idSucursal'");
            $tercero            = SQL::obtenerValor("proveedores","id_tercero","id = '$idProveedor'");
            $proveedor          = SQL::obtenerValor("seleccion_proveedores","nombre","id = '$idProveedor'");

            $vector_proveedor   = explode("|", $proveedor);
            $nombre_proveedor   = $vector_proveedor[0];
            $tipo_compra        = SQL::obtenerValor("tipos_compra","nombre","id = '$tipoCompra'");

            $consulta1          = SQL::seleccionar(array("articulos_ordenes_compra"), array("*"), "id_orden = '$url_id'");
            $total_credito      = 0;
            $total_contado      = 0;
            $items              = array();
            if (SQL::filasDevueltas($consulta1)) {
                while ($datos_item = SQL::filaEnObjeto($consulta1)) {
                    $id             = $datos_item->id;
                    $sucursal       = SQL::obtenerValor("sucursales","nombre","id = '$datos_item->id_sucursal'");
                    $articulo       = SQL::obtenerValor("articulos","codigo_interno","id='".$datos_item->id_articulo."'");
                    $plazo          = SQL::obtenerValor("plazos_pago_proveedores","nombre","id ='".$datos_item->id_plazo_pago."'");
                    $forma_pago     = $datos_item->forma_pago;
                    $valor_compra   = $datos_item->valor_compra;
                    $precio_publico = $datos_item->precio_publico;
                    $unidades       = $datos_item->unidades;
                    $descuento1     = $datos_item->descuento1;
                    $descuento2     = $datos_item->descuento2;

                    $valor_compra   = $valor_compra-(($valor_compra*$descuento1)/100);
                    $valor_compra   = $valor_compra-(($valor_compra*$descuento2)/100);

                    /*** Obtener porcentaje vigente de la tasa de compra del articulo ***/
                    $consulta2  = SQL::seleccionar(array("vigencia_tasas"), array("porcentaje"), "id_tasa='".$datos_item->id_tasa."'", "", "fecha DESC", 1);
                    if (SQL::filasDevueltas($consulta2)) {
                        $datos      = SQL::filaEnObjeto($consulta2);
                        $porcentaje = $datos->porcentaje;
                    } else {
                        $porcentaje = 0;
                    }
                    $valor_mas_iva = $valor_compra+(($valor_compra*$porcentaje)/100);

                    if ($forma_pago == 1) {
                        $pago   = $textos["CONTADO"];
                        $total_contado = $total_contado+($valor_compra*$unidades);
                    }
                    if ($forma_pago == 2) {
                        $pago   = $textos["CREDITO"];
                        $total_credito = $total_credito+($valor_compra*$unidades);
                    }

                    if ($datos_item->obsequio) {
                        $pago   = $textos["OBSEQUIO"];
                        $plazo  = "-";
                    }

                    $margen = (($precio_publico-$valor_mas_iva)*100)/$precio_publico;
                    $margen = number_format($margen,2);

                    $items[] = array(   $id,
                                        $articulo,
                                        $pago,
                                        $plazo,
                                        number_format($valor_compra),
                                        number_format($valor_mas_iva),
                                        number_format($precio_publico),
                                        $margen,
                                        $unidades,
                                        number_format($valor_mas_iva*$unidades),
                                        $sucursal
                                    );
                }
            }

            /*** Calcular total con descuentos globales aplicados ***/
            $total = $total_contado+$total_credito;
            $total_descuento = $total-(($total*$global1)/100);
            $total_descuento = $total_descuento-(($total_descuento*$global2)/100);

            $tipo_iva = array(
                "1" => $textos["DISTRIBUIDO"],
                "2" => $textos["PRIMERA_CUOTA"],
                "3" => $textos["SEPARADO"]
            );

            /*** Definición de pestañas general ***/
            $formularios["PESTANA_GENERAL"] = array(
                array(
                    HTML::mostrarDato("idorden", $textos["IDORDEN"], $consecutivo)
                ),
                array(
                    HTML::mostrarDato("estado", $textos["ESTADO"], $textos["ESTADO_".$estado])
                ),
                array(
                    HTML::mostrarDato("usuario", $textos["USUARIO"], $usuario),
                    HTML::mostrarDato("sucursal", $textos["SUCURSAL"], $almacen)
                    .HTML::campoOculto("id_sucursal", $idSucursal)
                ),
                array(
                    HTML::mostrarDato("proveedor", $textos["PROVEEDOR"], $proveedor)
                ),
                array(
                    HTML::mostrarDato("tipo_compra", $textos["TIPO_COMPRA"], $tipo_compra),
                    HTML::mostrarDato("forma_iva", $textos["TIPO_IVA"], $tipo_iva[$forma_iva])
                ),
                array(
                    HTML::mostrarDato("descuento_global1", $textos["DESCUENTO_GLOBAL1"], $global1),
                    HTML::mostrarDato("descuento_global2", $textos["DESCUENTO_GLOBAL2"], $global2)
                ),
                array(
                    HTML::mostrarDato("descuento_financiero", $textos["DESCUENTO_FINANCIERO"], $financiero)
                ),
                array(
                    HTML::mostrarDato("observaciones", $textos["OBSERVACIONES"], $observaciones)
                )
            );

            $formularios["PESTANA_ARTICULOS"] = array(
                array(
                    HTML::mostrarDato("total_contado", $textos["TOTAL_CONTADO"], number_format($total_contado)),
                    HTML::mostrarDato("total_credito", $textos["TOTAL_CREDITO"], number_format($total_credito)),
                    HTML::mostrarDato("total", $textos["TOTAL"], number_format($total)),
                    HTML::mostrarDato("total_descuento", $textos["TOTAL_DESCUENTO"], number_format($total_descuento))
                ),
                array(
                    HTML::generarTabla( array("id","ARTICULO","FORMA_PAGO","PLAZO","PRECIO_COMPRA","PRECIO_MAS_IVA","PRECIO_PUBLICO","MARGEN","CANTIDAD","TOTAL","SUCURSAL"),
                                        $items,
                                        array("I","C","C","D","D","D","D","C","D","C"),
                                        "listaItems",
                                        false)
                )
            );

            /*** Definición de botones ***/
            $botones = array(
                HTML::boton("botonAceptar", $textos["ACEPTAR"], "eliminarItem('$url_id');", "aceptar")
            );

            $contenido = HTML::generarPestanas($formularios, $botones);

        } else {
            if ($entradas) {
                $error = $textos["ERROR_ORDEN_ENTRADAS"];
            } else {
                $error = $textos["ERROR_ORDEN_ESTADO"];
            }

            $titulo    = "";
            $contenido = "";
        }
    }

    /*** Enviar datos para la generación del formulario al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

/*** Adicionar los datos provenientes del formulario ***/
} elseif (!empty($forma_procesar)) {
    /*** Asumir por defecto que no hubo error ***/
    $datos = array(
        "estado" => "4"
    );
    $consulta = SQL::modificar("ordenes_compras", $datos, "id = '$forma_id'");

    if ($consulta) {
        $error   = false;
        $mensaje = $textos["ITEM_ANULADO"];

        Sesion::registrar("indice_imprimir", $forma_id);
        include("clases/imprimir.php");
        $dato_respuesta = HTML::enlazarPagina($textos["IMPRIMIR_PDF"], $pance["url"]."/".$nombreArchivo, array("class" => "pdf", "onClick" => "$('a.pdf').media({width:500, height:400});"));


    } else {
        $error   = true;
        $mensaje = $textos["ERROR_ANULAR_ITEM"];
    }

    /*** Enviar datos con la respuesta del proceso al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>
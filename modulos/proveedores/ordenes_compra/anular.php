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
        $consulta       = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo = '$url_id'");
        $datos          = SQL::filaEnObjeto($consulta);

        /*** Verificar si la orden de compra puede ser anulada ***/
        $estado             = $datos->estado;
        $tablas             = array ("a" => "ordenes_compra", "b" => "movimiento_ordenes_compra");
        $columnas           = array ("a.codigo");
        $condicion          = "a.codigo = b.codigo_orden_compra AND a.codigo = '$url_id'";
        $consulta_entradas  = SQL::seleccionar($tablas, $columnas, $condicion);
        $entradas           = SQL::filasDevueltas($consulta_entradas);

        if (($estado==0) || ($estado==1)) {

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

            $documento_comprador     = SQL::obtenerValor("compradores", "documento_identidad", "codigo = '".$codigo_comprador."'");
            $primer_nombre           = SQL::obtenerValor("terceros", "primer_nombre", "documento_identidad = '".$documento_comprador."'");
            $segundo_nombre          = SQL::obtenerValor("terceros", "segundo_nombre", "documento_identidad = '".$documento_comprador."'");
            $primer_apellido         = SQL::obtenerValor("terceros", "primer_apellido", "documento_identidad = '".$documento_comprador."'");
            $segundo_apellido        = SQL::obtenerValor("terceros", "segundo_apellido", "documento_identidad = '".$documento_comprador."'");
            $nombre_comprador        = $primer_nombre." ".$segundo_nombre." ".$primer_apellido." ".$segundo_apellido;
            $nombre_numero_dias_pago = SQL::obtenerValor("plazos_pago_proveedores", "nombre", "codigo = '".$datos->codigo_numero_dias_pago."'");

            $nombre_moneda           = SQL::obtenerValor("tipos_moneda", "nombre", "codigo = '".$datos->codigo_moneda."'");
            $tipo_documento          = SQL::obtenerValor("tipos_documentos", "descripcion", "codigo = '".$datos->codigo_tipo_documento."'");
            $codigo_empresa          = SQL::obtenerValor("sucursales", "codigo_empresa", "codigo = '".$codigo_sucursal."'");
            $empresa                 = SQL::obtenerValor("empresas", "razon_social", "codigo = '".$codigo_empresa."'");
            $nit_empresa             = SQL::obtenerValor("empresas", "documento_identidad_tercero", "codigo = '".$codigo_empresa."'");   
            $nombre_proyecto         = SQL::obtenerValor("proyectos", "nombre", "codigo = '".$datos->prefijo_codigo_proyecto."'");
            $razon_social_proveedor  = SQL::obtenerValor("terceros", "razon_social", "documento_identidad = '".$documento_identidad_proveedor."'");
            $direccion_proveedor     = SQL::obtenerValor("terceros", "direccion_principal", "documento_identidad = '".$documento_identidad_proveedor."'");
          
            $nombre_sucursal         = SQL::obtenerValor("sucursales","nombre","codigo = '$codigo_sucursal'");
            $proveedor               = SQL::obtenerValor("seleccion_proveedores","nombre","id = '$documento_identidad_proveedor'");

            $vector_proveedor        = explode("|", $proveedor);
            $nombre_proveedor        = $vector_proveedor[0];

            //Inicia lectura del movimiento
            $consulta_movimiento   = SQL::seleccionar(array("movimiento_ordenes_compra"), array("*"), "codigo_orden_compra = '$url_id' AND codigo_sucursal_destino='$codigo_sucursal' ORDER BY consecutivo ASC");
            $total_credito         = 0;
            $total_contado         = 0;
            $items                 = array();

            if (SQL::filasDevueltas($consulta_movimiento)) {
                while ($datos_item           = SQL::filaEnObjeto($consulta_movimiento)) {
                    $id                      = $datos_item->codigo;
                    $codigo_articulo         = $datos_item->codigo_articulo;
                    $nombre_sucursal_destino = SQL::obtenerValor("sucursales","nombre","codigo = '$datos_item->codigo_sucursal'");
                    $descripcion             = SQL::obtenerValor("articulos","descripcion","codigo='".$datos_item->codigo_articulo."'");
                    $referencia_articulo     = $datos_item->referencia_articulo;
                    $nombre_unidad_medida    = SQL::obtenerValor("unidades","nombre","codigo='".$datos_item->codigo_unidad_medida."'");
                    $fecha_entrega           = $datos_item->fecha_entrega;
                    $primer_nombre           = SQL::obtenerValor("vendedores_proveedor", "primer_nombre", "codigo = '".$datos_item->codigo_vendedor."'");
                    $segundo_nombre          = SQL::obtenerValor("vendedores_proveedor", "segundo_nombre", "codigo = '".$datos_item->codigo_vendedor."'");
                    $primer_apellido         = SQL::obtenerValor("vendedores_proveedor", "primer_apellido", "codigo = '".$datos_item->codigo_vendedor."'");
                    $segundo_apellido        = SQL::obtenerValor("vendedores_proveedor", "segundo_apellido", "codigo = '".$datos_item->codigo_vendedor."'");
                    $nombre_vendedor         = $primer_nombre." ".$segundo_nombre." ".$primer_apellido." ".$segundo_apellido;
                    $correo_vendedor         = SQL::obtenerValor("vendedores_proveedor", "correo", "codigo = '".$datos_item->codigo_vendedor."'");
                    $celular_vendedor        = SQL::obtenerValor("vendedores_proveedor", "celular", "codigo = '".$datos_item->codigo_vendedor."'");

                    $observaciones           = $datos_item->observaciones;
                    $valor_unitario          = $datos_item->valor_unitario;
                    $cantidad_total          = $datos_item->cantidad_total;
                    $valor_total             = $datos_item->valor_total;
                    $descuento_global1       = $datos_item->descuento_global1;
                    $valor_descuento_global1 = $datos_item->valor_descuento_global1;
                    $neto_pagar              = $datos_item->neto_pagar;
                    $valor_iva               = $datos_item->valor_iva;

                    $items[] = array(   
                                    $id,
                                    $referencia_articulo,
                                    $descripcion,
                                    number_format($cantidad_total,0),
                                    $nombre_unidad_medida,
                                    number_format($valor_unitario,2),
                                    number_format($valor_total,2),
                                    number_format($valor_descuento_global1,2),
                                    number_format($valor_iva,2),
                                    $observaciones,
                                );
                }
            }
            $total_orden = SQL::obtenerValor("movimiento_ordenes_compra","SUM(neto_pagar)","codigo_orden_compra='$codigo_orden_compra'");
            $total_orden = $total_orden;
            $total_items = SQL::obtenerValor("movimiento_ordenes_compra","COUNT(codigo_articulo)","codigo_orden_compra='$codigo_orden_compra'");

            /*** Definición de pestañas general ***/
            $formularios["PESTANA_GENERAL"] = array(
                array(
                    HTML::agrupador(
                        array(
                            array(
                                HTML::mostrarDato("prefijo_orden", $textos["PREFIJO_ORDEN_COMPRA"], $datos->prefijo_codigo_proyecto),
                                HTML::mostrarDato("numero_orden", $textos["DOCUMENTO"], $numero_consecutivo),
                                HTML::mostrarDato("fecha_documento", $textos["FECHA_DOCUMENTO"], $datos->fecha_documento),
                                HTML::mostrarDato("fecha_entrega", $textos["FECHA_ENTREGA"], $fecha_entrega)
                            ),
                            array(
                                HTML::mostrarDato("numero_dias_pago", $textos["NUMERO_DIAS_PAGO"], $nombre_numero_dias_pago),
                                HTML::mostrarDato("moneda", $textos["MONEDA"], $nombre_moneda),
                                HTML::mostrarDato("tipo_documento", $textos["TIPO_DOCUMENTO"], $tipo_documento),
                                HTML::mostrarDato("estado", $textos["ESTADO"], $textos["ESTADO_".$estado])
                            ),
                            array(
                                HTML::mostrarDato("empresa", $textos["EMPRESA"], $empresa),
                                HTML::mostrarDato("nit", $textos["NIT"], $nit_empresa)
                            ),
                            array(
                                HTML::mostrarDato("consorcio", $textos["CONSORCIO"], $nombre_sucursal),
                                HTML::mostrarDato("comprador", $textos["COMPRADOR"], $nombre_comprador)
                            ),
                            array(
                                HTML::mostrarDato("proyecto", $textos["PROYECTO"], $nombre_proyecto),
                                HTML::mostrarDato("solicitante", $textos["SOLICITANTE"], $datos->solicitante)
                            ),
                            array(
                                HTML::mostrarDato("descuento_global1", $textos["DESCUENTO_GLOBAL1"], $descuento_global1)
                            ),
                        ),
                        $textos["DATOS_FACTURACION"]
                    )
                ),
                array(
                    HTML::agrupador(
                        array(
                            array(
                                HTML::mostrarDato("nit_proveedor", $textos["NIT_PROVEEDOR"], $documento_identidad_proveedor),
                                HTML::mostrarDato("razon_social_proveedor", $textos["RAZON_SOCIAL_PROVEEDOR"], $razon_social_proveedor),
                                HTML::mostrarDato("nombre_vendedor", $textos["VENDEDOR"], $nombre_vendedor) 
                            ),
                            array(
                                HTML::mostrarDato("direccion", $textos["DIRECCION"], $documento_identidad_proveedor),
                                HTML::mostrarDato("email", $textos["CORREO_ELECTRONICO"], $correo_vendedor),
                                HTML::mostrarDato("celular", $textos["CELULAR"], $celular_vendedor),
                            )
                        ),
                        $textos["DATOS_PROVEEDOR"]
                    )
                ),    
            );

            $formularios["PESTANA_ARTICULOS"] = array(
                array(
                    HTML::mostrarDato("total_unidades", $textos["TOTAL_UNIDADES"], number_format($total_items)),
                    HTML::mostrarDato("subtotal", $textos["SUBTOTAL"], number_format($valor_total)),
                    HTML::mostrarDato("descuento_global1", $textos["DESCUENTO_GLOBAL1"], number_format($valor_descuento_global1)),
                    HTML::mostrarDato("total_iva", $textos["VALOR_IVA"], number_format($valor_iva)),
                    HTML::mostrarDato("total_pedido", $textos["TOTAL_PEDIDO"], number_format($total_orden))
                ),
                array(
                    HTML::generarTabla(
                        array("id","REFERENCIA","DESCRIPCION","CANTIDAD","UNIDAD_MEDIDA","VALOR_UNITARIO","SUBTOTAL","DESCUENTO","IVA","OBSERVACIONES"), $items, array("I","I","D","D","C","D","D","D","I"), "listaItems", false
                        )
                )
            );

            /*** Definición de botones ***/
            $botones = array(
                HTML::boton("botonAceptar", $textos["ACEPTAR"], "eliminarItem('$url_id');", "aceptar")
            );

            $contenido = HTML::generarPestanas($formularios, $botones);

        } else {
            if ($estado) {
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
        "estado" => "3"
    );
    $consulta = SQL::modificar("ordenes_compras", $datos, "codigo = '$forma_id'");

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
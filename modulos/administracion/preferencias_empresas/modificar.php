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

// Devolver datos para autocompletar la busqueda
if (isset($url_completar)) {

    if (($url_item) == "selector1") {
        echo SQL::datosAutoCompletar("seleccion_plan_contable_transacciones", $url_q);
    }if (($url_item) == "selector2") {
        echo SQL::datosAutoCompletar("seleccion_plan_contable_transacciones", $url_q);
    }
    exit;
}

// Generar el formulario para la captura de datos
if (!empty($url_generar)) {

    $count_1 = SQL::filasDevueltas(SQL::seleccionar(array("sucursales"),array("codigo"),"codigo != 0"));
    $count_2 = SQL::filasDevueltas(SQL::seleccionar(array("tipos_documentos"),array("codigo"),"codigo != 0"));
    $count_3 = SQL::filasDevueltas(SQL::seleccionar(array("tipos_comprobantes"),array("codigo"),"codigo != 0"));
    $count_4 = SQL::filasDevueltas(SQL::seleccionar(array("plan_contable"),array("codigo_contable"),"codigo_contable != '' AND clase_cuenta = '1'"));

    if($count_1 == 0 || $count_2 == 0 || $count_3 == 0 || $count_4 == 0){
        $mensaje   = $textos["ERROR_TABLAS"];
        $listaMensajes = array();

        if($count_1 == 0){
            $listaMensajes[] = $textos["TABLA_VACIA_SUCURSALES"];
        }
        if($count_2 == 0){
            $listaMensajes[] = $textos["TABLA_VACIA_TIPOS_DOCUMENTOS"];
        }
        if($count_3 == 0){
            $listaMensajes[] = $textos["TABLA_VACIA_TIPOS_COMPROBANTES"];
        }
        if($count_4 == 0){
            $listaMensajes[] = $textos["TABLA_VACIA_PLAN_CONTABLE"];
        }

        $tablas    = implode("\n",$listaMensajes);
        $mensaje  .= $tablas;
        $error     = $mensaje;
        $titulo    = "";
        $contenido = "";
    }else{

        // Verificar que se haya enviado el ID del elemento a modificar
        if (empty($url_id)) {
            $error     = $textos["ERROR_MODIFICAR_VACIO"];
            $titulo    = "";
            $contenido = "";
        } else {
            $error    = "";
            $titulo   = $componente->nombre;

            $sucursales                                  = HTML::generarDatosLista("sucursales", "codigo", "nombre");
            $tipo_documento                              = HTML::generarDatosLista("tipos_documentos", "codigo", "descripcion");
            $tipo_documento_remision                     = HTML::generarDatosLista("tipos_documentos", "codigo", "descripcion");
            $tipo_documento_entrada_traslado             = HTML::generarDatosLista("tipos_documentos", "codigo", "descripcion");
            $tipo_documento_salida_traslado              = HTML::generarDatosLista("tipos_documentos", "codigo", "descripcion");
            $tipo_documento_facturacion                  = HTML::generarDatosLista("tipos_documentos", "codigo", "descripcion");
            $tipo_documento_devoluciones_compras         = HTML::generarDatosLista("tipos_documentos", "codigo", "descripcion", "codigo != 0");
            $tipo_documento_contabilizacion_devoluciones = HTML::generarDatosLista("tipos_documentos", "codigo", "descripcion", "codigo != 0");
            $tipo_documento_recibo_provisional           = HTML::generarDatosLista("tipos_documentos", "codigo", "descripcion", "codigo != 0");
            $tipo_documento_servicio_publicidad          = HTML::generarDatosLista("tipos_documentos", "codigo", "descripcion", "codigo != 0");
            $tipo_documento_nota_varia_1                 = HTML::generarDatosLista("tipos_documentos", "codigo", "descripcion", "codigo != 0");
            $tipo_documento_nota_varia_2                 = HTML::generarDatosLista("tipos_documentos", "codigo", "descripcion", "codigo != 0");
            $tipo_comprobante_facturacion_proveedores    = HTML::generarDatosLista("tipos_comprobantes", "codigo", "descripcion", "codigo != 0");
            $tipo_documento_facturacion_proveedores      = HTML::generarDatosLista("tipos_documentos", "codigo", "descripcion", "codigo != 0");
            $tipo_comprobante_devoluciones_compra        = HTML::generarDatosLista("tipos_comprobantes", "codigo", "descripcion", "codigo != 0");
            $tipo_documento_devoluciones_compra          = HTML::generarDatosLista("tipos_documentos", "codigo", "descripcion", "codigo != 0");
            $tipo_documento_provision                    = HTML::generarDatosLista("tipos_documentos", "codigo", "descripcion", "codigo != 0");
            $tipo_documento_cruce_devolucion             = HTML::generarDatosLista("tipos_documentos", "codigo", "descripcion", "codigo != 0");

            $preferencias = array();
            $preferencias["sucursal_ordenes_compra"]                     = 0;
            $preferencias["sucursal_lista_detallista_orden"]             = 0;
            $preferencias["tipo_documento_entrada"]                      = 0;
            $preferencias["incentivo_credito_detallista"]                = 0;
            $preferencias["incentivo_jefes"]                             = 0;
            $preferencias["tipo_documento_remision"]                     = 0;
            $preferencias["tipo_documento_entrada_traslado"]             = 0;
            $preferencias["tipo_documento_salida_traslado"]              = 0;
            $preferencias["tipo_documento_facturacion"]                  = 0;
            $preferencias["tipo_documento_devoluciones_compras"]         = 0;
            $preferencias["retencion_anos_anteriores"]                   = 0;
            $preferencias["tipo_documento_contabilizacion_devoluciones"] = 0;
            $preferencias["porcentaje_reteiva"]                          = 0;
            $preferencias["porcentaje_iva"]                              = 0;
            $preferencias["tipo_documento_recibo_provisional"]           = 0;
            $preferencias["id_plan_contable_compra"]                     = 0;
            $preferencias["id_plan_contable_iva_compra"]                 = 0;
            $preferencias["tipo_documento_servicio_publicidad"]          = 0;
            $preferencias["tipo_documento_nota_varia_1"]                 = 0;
            $preferencias["tipo_documento_nota_varia_2"]                 = 0;
            $preferencias["tipo_comprobante_facturacion_proveedores"]    = 0;
            $preferencias["tipo_documento_facturacion_proveedores"]      = 0;
            $preferencias["tipo_comprobante_devoluciones_compra"]        = 0;
            $preferencias["tipo_documento_devoluciones_compra"]          = 0;
            $preferencias["tipo_documento_provision"]                    = 0;
            $preferencias["tipo_documento_cruce_devolucion"]             = 0;
            $preferencias["valor_cuota_minima_pago"]                     = "";

            $preferencias_sucursal = SQL::seleccionar(array("preferencias"),array("valor", "variable"),"tipo_preferencia='2' AND codigo_empresa='".$url_id."'","","variable");
            if(SQL::filasDevueltas($preferencias_sucursal)){
              while ($datos = SQL::filaEnObjeto($preferencias_sucursal)) {
                $preferencias[$datos->variable] = $datos->valor;
              }
            }

            $retencion_anos_anteriores = false;
            if ($preferencias["retencion_anos_anteriores"] == 1) {
                $retencion_anos_anteriores = true;
            }

            $cuenta_compra = $preferencias["id_plan_contable_compra"];
            if(isset($cuenta_compra) || $cuenta_compra > 0){
                $id_plan_contable_compra = SQL::obtenerValor("seleccion_plan_contable_transacciones","cuenta","id = '".$cuenta_compra."'");
                $id_plan_contable_compra = explode("|",$id_plan_contable_compra);
                $id_plan_contable_compra = $id_plan_contable_compra[0];
            }else{
                $id_plan_contable_compra = "";
            }

            $cuenta_iva_compra = $preferencias["id_plan_contable_iva_compra"];
            if(isset($cuenta_iva_compra) || $cuenta_iva_compra > 0){
                $id_plan_contable_iva_compra = SQL::obtenerValor("seleccion_plan_contable_transacciones","cuenta","id = '".$cuenta_iva_compra."'");
                $id_plan_contable_iva_compra = explode("|",$id_plan_contable_iva_compra);
                $id_plan_contable_iva_compra = $id_plan_contable_iva_compra[0];
            }else{
                $id_plan_contable_iva_compra = "";
            }

            $formularios["PESTANA_PROVEEDORES"] = array(
                array(
                    HTML::mostrarDato("listas_precios", $textos["PESTANA_LISTAS_PRECIOS"],"")
                ),
                array(
                    HTML::campoTextoCorto("incentivo_credito_detallista", $textos["INCENTIVO_CREDITO_DETALLISTA"], 4, 3, $preferencias["incentivo_credito_detallista"], array("title" => $textos["AYUDA_INCENTIVO_CREDITO_DETALLISTA"])),
                    HTML::campoTextoCorto("incentivo_jefes", $textos["INCENTIVO_JEFES"], 4, 3, $preferencias["incentivo_jefes"], array("title" => $textos["AYUDA_FINCENTIVO_JEFES"]))
                ),
                array(
                    HTML::mostrarDato("ordenes_compras", $textos["PESTANA_ORDENES_COMPRAS"],"")
                ),
                array(
                    HTML::listaSeleccionSimple("sucursales", $textos["SUCURSAL_ORDEN_COMPRA"], $sucursales, $preferencias["sucursal_ordenes_compra"]),
                    HTML::listaSeleccionSimple("sucursal_lista_detallista_orden", $textos["SUCURSAL_DETALLISTA_ORDEN_COMPRA"], $sucursales, $preferencias["sucursal_lista_detallista_orden"]),
                    HTML::listaSeleccionSimple("tipo_documento_devoluciones_compras", $textos["TIPO_DOCUMENTO_DEVOLUCIONES_COMPRAS"], $tipo_documento_devoluciones_compras, $preferencias["tipo_documento_devoluciones_compras"]),
                    HTML::listaSeleccionSimple("tipo_documento_contabilizacion_devoluciones", $textos["TIPO_DOCUMENTO_CONTABILIZACION_DEVOLUCIONES"], $tipo_documento_devoluciones_compras, $preferencias["tipo_documento_contabilizacion_devoluciones"])
                ),
                array(
                    HTML::marcaChequeo("retencion_anos_anteriores", $textos["RETENCION_ANOS_ANTERIORES"], 1, $retencion_anos_anteriores)
                ),
                array(
                    HTML::mostrarDato("entradas_mercancia", $textos["PESTANA_ENTRADAS_MERCANCIA"],"")
                ),
                array(
                    HTML::listaSeleccionSimple("tipo_documento_entrada", $textos["TIPO_DOCUMENTO"], $tipo_documento, $preferencias["tipo_documento_entrada"])
                ),
                array(
                    HTML::mostrarDato("mercancia_transito", $textos["PESTANA_MERCANCIA_TRANSITO"],"")
                ),
                array(
                    HTML::campoTextoCorto("selector1", $textos["PLAN_CONTABLE_COMPRA"], 30, 30, $id_plan_contable_compra, array("title" => $textos["AYUDA_PLAN_CONTABLE_COMPRA"],"Class" => "autocompletable"))
                    .HTML::campoOculto("id_plan_contable_compra", $preferencias["id_plan_contable_compra"]),
                    HTML::campoTextoCorto("selector2", $textos["PLAN_CONTABLE_IVA_COMPRA"], 30, 30, $id_plan_contable_iva_compra, array("title" => $textos["AYUDA_PLAN_CONTABLE_IVA_COMPRA"],"Class" => "autocompletable"))
                    .HTML::campoOculto("id_plan_contable_iva_compra", $preferencias["id_plan_contable_iva_compra"])
                )
            );

            $formularios["PESTANA_PROVEEDORES_2"] = array(
                array(
                    HTML::mostrarDato("ordenes_publicidad", $textos["PESTANA_ORDENES_PUBLICIDAD"],"")
                ),
                array(
                    HTML::listaSeleccionSimple("tipo_documento_servicio_publicidad", $textos["TIPO_DOCUMENTO"], $tipo_documento_servicio_publicidad, $preferencias["tipo_documento_servicio_publicidad"])
                ),
                array(
                    HTML::mostrarDato("notas_varias", $textos["NOTAS_VARIAS"],"")
                ),
                array(
                    HTML::listaSeleccionSimple("tipo_documento_nota_varia_1", $textos["TIPO_DOCUMENTO_RECIBO"], $tipo_documento_nota_varia_1, $preferencias["tipo_documento_nota_varia_1"]),
                    HTML::listaSeleccionSimple("tipo_documento_nota_varia_2", $textos["TIPO_DOCUMENTO_RECIBO"], $tipo_documento_nota_varia_2, $preferencias["tipo_documento_nota_varia_2"])
                ),
                array(
                    HTML::mostrarDato("facturacion_proveedores", $textos["FACTURACION_PROVEEDORES"],"")
                ),
                array(
                    HTML::listaSeleccionSimple("tipo_comprobante_facturacion_proveedores", $textos["TIPO_COMPROBANTE_FACTURACION_PROVEEDORES"], $tipo_comprobante_facturacion_proveedores, $preferencias["tipo_comprobante_facturacion_proveedores"]),
                    HTML::listaSeleccionSimple("tipo_documento_facturacion_proveedores", $textos["TIPO_DOCUMENTO_FACTURACION_PROVEEDORES"], $tipo_documento_facturacion_proveedores, $preferencias["tipo_documento_facturacion_proveedores"])
                ),
                array(
                    HTML::mostrarDato("devoluciones_compras", $textos["DEVOLUCIONES_COMPRA"],"")
                ),
                array(
                    HTML::listaSeleccionSimple("tipo_comprobante_devoluciones_compra", $textos["TIPO_COMPROBANTE_DEVOLUCIONES_COMPRA"], $tipo_comprobante_devoluciones_compra, $preferencias["tipo_comprobante_devoluciones_compra"]),
                    HTML::listaSeleccionSimple("tipo_documento_devoluciones_compra", $textos["TIPO_DOCUMENTO_DEVOLUCIONES_COMPRA"], $tipo_documento_devoluciones_compra, $preferencias["tipo_comprobante_devoluciones_compra"])
                ),
                array(
                    HTML::mostrarDato("cruce_devoluciones_compras", $textos["CRUCE_DEVOLUCIONES_COMPRA"],"")
                ),
                array(
                    HTML::listaSeleccionSimple("tipo_documento_cruce_devolucion", $textos["TIPO_DOCUMENTO_CRUCE_DEVOLUCION"], $tipo_documento_cruce_devolucion, $preferencias["tipo_documento_cruce_devolucion"])
                )
            );


            $formularios["PESTANA_CLIENTES"] = array(
                array(
                    HTML::mostrarDato("ventas_despachos", $textos["VENTAS_DESPACHOS"],"")
                ),
                array(
                    HTML::listaSeleccionSimple("tipo_documento_remision", $textos["TIPO_DOCUMENTO"], $tipo_documento_remision, $preferencias["tipo_documento_remision"]),
                    HTML::listaSeleccionSimple("tipo_documento_facturacion", $textos["TIPO_DOCUMENTO_FACTURA"], $tipo_documento_facturacion, $preferencias["tipo_documento_facturacion"], array("title" => $textos["AYUDA_TIPO_DOCUMENTO_FACTURA"])),
                    HTML::listaSeleccionSimple("tipo_documento_entrada_traslado", $textos["ENTRADA_TRASLADO"], $tipo_documento_entrada_traslado, $preferencias["tipo_documento_entrada_traslado"], array("title" => $textos["AYUDA_ENTRADA_TRASLADOS"])),
                    HTML::listaSeleccionSimple("tipo_documento_salida_traslado", $textos["SALIDA_TRASLADO"], $tipo_documento_salida_traslado, $preferencias["tipo_documento_salida_traslado"], array("title" => $textos["AYUDA_SALIDA_TRASLADOS"])),
                    HTML::listaSeleccionSimple("bodega_recepcion_traslados", $textos["BODEGA_RECEPCION_TRASLADOS"], $tipo_documento_facturacion, $preferencias["tipo_documento_facturacion"], array("title" => $textos["AYUDA_BODEGA_RECEPCION_TRASLADOS"]))
                ),
                array(
                    HTML::mostrarDato("tipos_tarjetas", $textos["TIPOS_TARJETAS"],"")
                ),
                array(
                    HTML::campoTextoCorto("porcentaje_reteiva", $textos["PORCENTAJE_RETEIVA"], 5, 5, $preferencias["porcentaje_reteiva"], array("title" => $textos["AYUDA_PORCENTAJE_RETEIVA"],"onKeyPress" => "return campoDecimal(event)")),
                    HTML::campoTextoCorto("porcentaje_iva", $textos["PORCENTAJE_IVA"], 5, 5, $preferencias["porcentaje_iva"], array("title" => $textos["AYUDA_PORCENTAJE_IVA"],"onKeyPress" => "return campoDecimal(event)"))
                ),
                array(
                    HTML::mostrarDato("recibo_provisional", $textos["RECIBO_PROVISIONAL"],"")
                ),
                array(
                    HTML::listaSeleccionSimple("tipo_documento_recibo_provisional", $textos["TIPO_DOCUMENTO_RECIBO"], $tipo_documento_recibo_provisional, $preferencias["tipo_documento_recibo_provisional"])
                )
            );

            $formularios["PESTANA_INVENTARIOS"] = array(
                array(
                    HTML::listaSeleccionSimple("tipo_documento_provision", $textos["DOCUMENTO_PROVISION"], $tipo_documento_provision, $preferencias["tipo_documento_provision"])
                )
            );

            $formularios["PESTANA_NOMINA"] = array(
                array(
                    HTML::campoTextoCorto("valor_cuota_minima_pago", $textos["VALOR_CUOTA_MINIMA_PAGO"], 15,11, $preferencias["valor_cuota_minima_pago"],array("onKeyPress" => "return campoEntero(event);", "title" => $textos["AYUDA_VALOR_CUOTA_MINIMA_PAGO"]))
                )
            );

            // Definicion de botones
            $botones = array(HTML::boton("botonAceptar", $textos["ACEPTAR"], "modificarItem('".$url_id."');", "aceptar"));

            $contenido  = HTML::generarPestanas($formularios, $botones);
        }
    }

    // Enviar datos para la generacion del formulario al script que origino la peticion
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

// Modificar el elemento seleccionado
} elseif (!empty($forma_procesar)) {

    // Asumir por defecto que no hubo error
    $error   = false;
    $mensaje = $textos["ITEM_MODIFICADO"];

    $elementos_preferencias = array();
    $elementos_preferencias["sucursal_ordenes_compra"]                      = $forma_sucursales;
    $elementos_preferencias["sucursal_lista_detallista_orden"]              = $forma_sucursal_lista_detallista_orden;
    $elementos_preferencias["tipo_documento_entrada"]                       = $forma_tipo_documento_entrada;
    $elementos_preferencias["incentivo_credito_detallista"]                 = $forma_incentivo_credito_detallista;
    $elementos_preferencias["incentivo_jefes"]                              = $forma_incentivo_jefes;
    $elementos_preferencias["tipo_documento_remision"]                      = $forma_tipo_documento_remision;
    $elementos_preferencias["tipo_documento_entrada_traslado"]              = $forma_tipo_documento_entrada_traslado;
    $elementos_preferencias["tipo_documento_salida_traslado"]               = $forma_tipo_documento_salida_traslado;
    $elementos_preferencias["tipo_documento_facturacion"]                   = $forma_tipo_documento_facturacion;
    $elementos_preferencias["tipo_documento_devoluciones_compras"]          = $forma_tipo_documento_devoluciones_compras;
    $elementos_preferencias["tipo_documento_contabilizacion_devoluciones"]  = $forma_tipo_documento_contabilizacion_devoluciones;
    $elementos_preferencias["porcentaje_reteiva"]                           = $forma_porcentaje_reteiva;
    $elementos_preferencias["porcentaje_iva"]                               = $forma_porcentaje_iva;
    $elementos_preferencias["tipo_documento_recibo_provisional"]            = $forma_tipo_documento_recibo_provisional;
    $elementos_preferencias["id_plan_contable_compra"]                      = $forma_id_plan_contable_compra;
    $elementos_preferencias["id_plan_contable_iva_compra"]                  = $forma_id_plan_contable_iva_compra;
    $elementos_preferencias["tipo_documento_servicio_publicidad"]           = $forma_tipo_documento_servicio_publicidad;
    $elementos_preferencias["tipo_documento_nota_varia_1"]                  = $forma_tipo_documento_nota_varia_1;
    $elementos_preferencias["tipo_documento_nota_varia_2"]                  = $forma_tipo_documento_nota_varia_2;
    $elementos_preferencias["tipo_documento_facturacion_proveedores"]       = $forma_tipo_documento_facturacion_proveedores;
    $elementos_preferencias["tipo_comprobante_facturacion_proveedores"]     = $forma_tipo_comprobante_facturacion_proveedores;
    $elementos_preferencias["tipo_documento_devoluciones_compra"]           = $forma_tipo_documento_devoluciones_compra;
    $elementos_preferencias["tipo_comprobante_devoluciones_compra"]         = $forma_tipo_comprobante_devoluciones_compra;
    $elementos_preferencias["tipo_documento_provision"]                     = $forma_tipo_documento_provision;
    $elementos_preferencias["tipo_documento_cruce_devolucion"]              = $forma_tipo_documento_cruce_devolucion;
    $elementos_preferencias["valor_cuota_minima_pago"]                      = $forma_valor_cuota_minima_pago;

    if (!isset($forma_retencion_anos_anteriores)) {
        $forma_retencion_anos_anteriores = 0;
    }
    $elementos_preferencias["retencion_anos_anteriores"]  = $forma_retencion_anos_anteriores;

    foreach($elementos_preferencias AS $id_vector => $valor_vector){
        $datos = array(
            "tipo_preferencia" => "2",
            "variable"         => $id_vector,
            "valor"            => $valor_vector,
            "codigo_sucursal"  => 0,
            "codigo_empresa"   => $forma_id,
            "codigo_usuario"   => 0
        );
        $modificar = SQL::reemplazar("preferencias", $datos);
    }
    // Enviar datos con la respuesta del proceso al script que origino la peticion
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

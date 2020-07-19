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
$indicador = 0;
$tabla                      = "usuarios";
$columnas                   = SQL::obtenerColumnas($tabla);
$consulta                   = SQL::seleccionar(array($tabla), $columnas, "usuario = '$sesion_usuario'");
$datos                      = SQL::filaEnObjeto($consulta);
$sesion_id_usuario_ingreso  = $datos->codigo;

if (isset($url_insertaCantidades)) {
    $codigo_orden_compra     = $url_codigo_orden_compra;  
    $codigo_articulo         = $url_codigo_articulo;  
    $tipo_documento          = $url_tipo_documento; 
    $documento_soporte_orden = $url_documento_soporte_orden;
    $cantidad_total_articulo = $url_cantidad_articulo;

    $consulta_cruce_orden   = SQL::seleccionar(array("cruce_orden_compra"), array("*"), "codigo_orden_compra='$codigo_orden_compra'");
    $consulta_ordenes_orden = SQL::seleccionar(array("ordenes_compra"), array("*"), "codigo='$codigo_orden_compra'");
    $datos_cruce_orden      = SQL::filaEnObjeto($consulta_ordenes_orden);

    $unidades_cruce = SQL::obtenerValor("movimiento_cruce_orden_compra","SUM(cantidad_total)","codigo_cruce_orden_compra='$codigo_cruce_orden' AND codigo_articulo='$codigo_articulo'");
    $unidades_orden = SQL::obtenerValor("movimiento_ordenes_compra","SUM(cantidad_total)","codigo_orden_compra='$codigo_orden_compra' AND codigo_articulo='$datos_item->codigo_articulo' AND codigo='$datos_item->codigo'");

    $unidades_cruce      = intval($unidades_cruce);
    $unidades_orden      = intval($unidades_orden);
    $unidades_pendientes = $unidades_orden - $unidades_cruce;  

} elseif(isset($url_tipoCruce)){
    $codigo_orden   = SQL::obtenerValor("ordenes_compra","codigo","numero_consecutivo='$url_numero_orden'");
    $cantidad_items = SQL::obtenerValor("movimiento_ordenes_compra","COUNT(codigo_articulo)","codigo_orden_compra='$codigo_orden'");
    $respuesta      = array();
    $respuesta      = $cantidad_items;var_dump("expression",$respuesta);
    HTTP::enviarJSON($respuesta);
    exit();

} elseif (!empty($url_generar)) {
    /*** Verificar que se haya enviado el ID del elemento a consultar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_CRUZAR_VACIO"];
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

        if (($estado==0)||($estado==1)) {

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

            //Obtengo cantidades por codigo en el movimiento y en el cruce
            /*$codigo_cruce_orden_compra = SQL::obtenerValor("cruce_orden_compra","codigo","codigo_orden_compra='$url_id'");
            $unidades_orden            = SQL::obtenerValor("movimiento_ordenes_compra","SUM(cantidad_total)","codigo_orden_compra='$url_id'");
            $unidades_cruce            = SQL::obtenerValor("movimiento_cruce_orden_compra","SUM(cantidad_total)","codigo_cruce_orden_compra='$codigo_cruce_orden_compra'");
            */
            //Inicia lectura del movimiento
            $consulta_movimiento   = SQL::seleccionar(array("movimiento_ordenes_compra"), array("*"), "codigo_orden_compra = '$url_id' AND codigo_sucursal_destino='$codigo_sucursal' ORDER BY consecutivo ASC");
            $total_credito         = 0;
            $total_contado         = 0;
            $nombre_campo          = 0;
            $items                 = array();
            $unidades_mayores      = 0;

            if($consulta_movimiento){

                if (SQL::filasDevueltas($consulta_movimiento)) {
                    while ($datos_item       = SQL::filaEnObjeto($consulta_movimiento)) {
                        $codigo_articulo     = $datos_item->codigo_articulo;
                        $codigo_cruce_orden  = SQL::obtenerValor("cruce_orden_compra","codigo","codigo_orden_compra='$codigo_orden_compra' LIMIT 0,1");
                        $unidades_cruce      = SQL::obtenerValor("movimiento_cruce_orden_compra","SUM(cantidad_total)","codigo_cruce_orden_compra='$codigo_cruce_orden' AND codigo_articulo='$codigo_articulo'");
                        $unidades_orden      = SQL::obtenerValor("movimiento_ordenes_compra","SUM(cantidad_total)","codigo_orden_compra='$codigo_orden_compra' AND codigo_articulo='$datos_item->codigo_articulo' AND codigo='$datos_item->codigo'");

                        $unidades_cruce      = intval($unidades_cruce);
                        $unidades_orden      = intval($unidades_orden);

                        if($unidades_mayores>0){
                            $unidades_cruce = $unidades_mayores;
                        }
                    
                        if($unidades_cruce<$unidades_orden){
                            $unidades_pendientes = $unidades_orden - $unidades_cruce;
                            $nombre_campo++;
                            $id_tabla                = $datos_item->codigo;
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
                            $descuento_global1       = number_format($datos_item->descuento_global1,2)."%";
                            $valor_descuento_global1 = $datos_item->valor_descuento_global1;
                            $neto_pagar              = $datos_item->neto_pagar;
                            $valor_iva               = $datos_item->valor_iva;

                            $items[] = array(   
                                    $id_tabla,
                                    $referencia_articulo,
                                    $descripcion,
                                    number_format($cantidad_total,0),
                                    $nombre_unidad_medida,
                                    number_format($valor_unitario,2),
                                    $observaciones,
                                    HTML::campoTextoCorto("cantidades[".$nombre_campo."]", "", 8, 8, $unidades_pendientes, array("title"=>$textos["AYUDA_CANTIDAD_TOTAL"], "onkeyup"=>"formatoMiles(this),insertaCantidades()"))
                                    .HTML::campoOculto("codigo_orden_compra", $codigo_orden_compra)
                                    .HTML::campoOculto("codigo_articulo", $codigo_articulo)
                                    .HTML::campoOculto("valor_unitario", $valor_unitario)
                                    .HTML::campoOculto("porcentaje_iva", $datos_item->porentaje_impuesto)
                                    .HTML::campoOculto("id", $id_tabla)
                                    .HTML::campoOculto("codigos[".$nombre_campo."]", $codigo_articulo)
                                    .HTML::campoOculto("porcentaje_descuento", $descuento_global1)
                                    .HTML::campoOculto("unidades_cruce", $unidades_cruce)
                                    .HTML::campoOculto("unidades_orden", $unidades_orden)
                                    .HTML::campoOculto("unidades_pendientes", $unidades_pendientes)
                                );
                                $unidades_mayores = 0;
                        }
                        $unidades_mayores = $unidades_cruce - $unidades_orden;
                    }
                }  
            
            }else{
                $error = $textos["ERROR_ESTADO_CUMPLIDA"];
                $titulo    = "";
                $contenido = "";    
            }

            $subtotal    = SQL::obtenerValor("movimiento_ordenes_compra","SUM(valor_total)","codigo_orden_compra='$codigo_orden_compra'");
            $subtotal    = number_format($subtotal, 0);
            $subtotal    = str_replace(',', '.', $subtotal);

            $unidades    = SQL::obtenerValor("movimiento_ordenes_compra","SUM(cantidad_total)","codigo_orden_compra='$codigo_orden_compra'");
            $unidades    = number_format($unidades, 0);
            
            $descuento   = SQL::obtenerValor("movimiento_ordenes_compra","SUM(valor_descuento_global1)","codigo_orden_compra='$codigo_orden_compra'");
            $descuento   = number_format($descuento, 0);
            $descuento   = str_replace(',', '.', $descuento);

            $total_iva   = SQL::obtenerValor("movimiento_ordenes_compra","SUM(valor_iva)","codigo_orden_compra='$codigo_orden_compra'");
            $total_iva   = number_format($total_iva, 0);
            $total_iva   = str_replace(',', '.', $total_iva);

            $total_orden = SQL::obtenerValor("movimiento_ordenes_compra","SUM(neto_pagar)","codigo_orden_compra='$codigo_orden_compra'");
            $total_orden = number_format($total_orden, 0);
            $total_orden = str_replace(',', '.', $total_orden);

            $total_items = SQL::obtenerValor("movimiento_ordenes_compra","COUNT(codigo_articulo)","codigo_orden_compra='$codigo_orden_compra'");

            /*** Definición de pestañas general ***/
            $formularios["PESTANA_GENERAL"] = array(
                array(
                    HTML::listaSeleccionSimple("*tipo_documento",$textos["TIPO_DOCUMENTO"], HTML::generarDatosLista("tipos_documentos", "codigo", "descripcion","codigo>0"), "", array("title",$textos["AYUDA_TIPO_DOCUMENTO"])),

                    HTML::campoTextoCorto("*documento_soporte_orden",$textos["DOCUMENTO_CRUCE"], 15, 15, "", array("title"=>$textos["AYUDA_DOCUMENTO_CRUCE"], "onBlur" => "validarItem(this)"))
                ),
                array(
                    HTML::agrupador(
                        array(
                            array(
                                HTML::marcaSeleccion("tipo", $textos["POR_ARTICULOS"], 1, true, array("title"=>$textos["AYUDA_POR_TOTALES"],"class"=>"por_totales","onChange"=>"activaCamposArticulos(this)")),
                                HTML::marcaSeleccion("tipo", $textos["POR_TOTALES"], 0, false, array("title"=>$textos["AYUDA_POR_TOTALES"],"class"=>"por_totales","onChange"=>"activaCamposTotales(this)"))
                            )
                        ),
                        $textos["DATOS_CRUCE"]
                    ),
                ),
                /*array(
                    HTML::contenedor(
                        HTML::marcaChequeo("por_articulos", $textos["POR_ARTICULOS"], 1, false, array("title"=>$textos["AYUDA_POR_ARTICULOS"],"class"=>"por_articulos","onClick"=>"activaCamposArticulos(this)")),
                        array("id"=>"contenedor_por_articulos","class"=>"movimiento")
                    ),
                    HTML::contenedor(
                        HTML::marcaChequeo("por_totales", $textos["POR_TOTALES"], 1, false, array("title"=>$textos["AYUDA_POR_TOTALES"],"class"=>"por_totales","onClick"=>"activaCamposTotales(this)")),
                        array("id"=>"contenedor_por_totales","class"=>"movimiento")
                    )
                ),*/ 
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
                                HTML::mostrarDato("nit", $textos["NIT"], $nit_empresa),
                                HTML::mostrarDato("consorcio", $textos["CONSORCIO"], $nombre_sucursal),
                                HTML::mostrarDato("comprador", $textos["COMPRADOR"], $nombre_comprador)
                            ),
                            array(
                                HTML::mostrarDato("proyecto", $textos["PROYECTO"], $nombre_proyecto),
                                HTML::mostrarDato("solicitante", $textos["SOLICITANTE"], $datos->solicitante),
                                HTML::mostrarDato("descuento_global1", $textos["DESCUENTO_GLOBAL1"], $descuento_global1)
                            ),
                            array(
                                HTML::campoOculto("numero_orden",$numero_consecutivo)
                            ),
                        ),
                        $textos["DATOS_FACTURACION"]
                    ),
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
                array(
                    HTML::contenedor(
                        HTML::agrupador(
                            array(
                                array(
                                    HTML::campoTextoCorto("total_unidades",$textos["TOTAL_UNIDADES"], 10, 10, $unidades, array("title"=>$textos["AYUDA_TOTAL_UNIDADES"], array("readOnly"=>"true"),"class"=>"oculto")),
                            
                                    HTML::campoTextoCorto("subtotal_pedido",$textos["SUBTOTAL"], 15, 15, "$".$subtotal, array("title"=>$textos["AYUDA_SUBTOTAL"], array("readOnly"=>"true"),"class"=>"oculto")),

                                    HTML::campoTextoCorto("descuento_pedido",$textos["DESCUENTO"], 15, 15, "$".$descuento, array("title"=>$textos["AYUDA_DESCUENTO"], array("readOnly"=>"true"),"class"=>"oculto")),

                                    HTML::campoTextoCorto("total_iva_pedido",$textos["TOTAL_IVA"], 15, 15, "$".$total_iva, array("title"=>$textos["AYUDA_IVA"], array("readOnly"=>"true"),"class"=>"oculto")),

                                    HTML::campoTextoCorto("total_pedido",$textos["TOTAL_PEDIDO"], 15, 15, "$".$total_orden, array("title"=>$textos["AYUDA_TOTAL_PEDIDO"], array("readOnly"=>"true"),"class"=>"oculto"))
                                )
                            ),$textos["TOTALES_ORDEN"]
                        )
                    )
                )
            );
            
            $formularios["PESTANA_ARTICULOS"] = array(
                array(
                    HTML::mostrarDato("total_unidades", $textos["TOTAL_UNIDADES"], $unidades),
                    HTML::mostrarDato("subtotal", $textos["SUBTOTAL"], "$".($subtotal)),
                    HTML::mostrarDato("descuento_global1", $textos["DESCUENTO"], "$".($descuento)),
                    HTML::mostrarDato("total_iva", $textos["VALOR_IVA"], "$".($total_iva)),
                    HTML::mostrarDato("total_pedido", $textos["TOTAL_PEDIDO"], "$".($total_orden))
                ),
                array(
                    HTML::generarTabla(
                        //array("id","REFERENCIA","DESCRIPCION","CANTIDAD","UNIDAD_MEDIDA","VALOR_UNITARIO","OBSERVACIONES","UND_FACT","SUBTOTAL","IVA",), 
                        array("id","REFERENCIA","DESCRIPCION","CANTIDAD","UNIDAD_MEDIDA","VALOR_UNITARIO","OBSERVACIONES","UND_FACT"), 
                            $items, 
                            array("I","I","I","C","D","I","D"), 
                            "listaItems", 
                            false
                        )
                ),
                array(
                    HTML::campoOculto("codigo_prefijo_proyecto", $datos->prefijo_codigo_proyecto),
                    HTML::campoOculto("codigo_orden_compra", $codigo_orden_compra),
                    HTML::campoOculto("codigo_sucursal", $codigo_sucursal),
                    HTML::campoOculto("documento_identidad_proveedor", $documento_identidad_proveedor),
                    HTML::campoOculto("id",""),
                    HTML::campoOculto("listaItems",$items),
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
    $error   = false;
    $mensaje = $textos["ORDEN_CRUZADA"];
 
    /*** Validar el ingreso de los datos requeridos ***/
    if(empty($forma_documento_soporte_orden)){
        $error   = true;
        $mensaje = $textos["DOCUMENTO_PROVEEDOR_VACIO"];
    }

    $consulta_cruce_orden     = SQL::seleccionar(array("cruce_orden_compra"), array("*"), "codigo_orden_compra='$forma_codigo_orden_compra'");
    $consulta_movimiento_cruce = SQL::seleccionar(array("movimiento_cruce_orden_compra"), array("*"), "codigo_cruce_orden_compra >0 '");
    
    $datos_cruce_orden = array(
        "codigo_prefijo_proyecto"       => $forma_codigo_prefijo_proyecto,
        "codigo_orden_compra"           => $forma_codigo_orden_compra,
        "codigo_sucursal"               => $forma_codigo_sucursal,
        "fecha_registro"                => date("Y-m-d H:i:s"),
        "codigo_usuario_registra"       => $sesion_id_usuario_ingreso,
        "documento_identidad_proveedor" => $forma_documento_identidad_proveedor
    );

    if($forma_tipo_documento==4){
        $numero_remision_proveedor = $forma_documento_soporte_orden;
        $numero_factura_proveedor  = "";
    }elseif($forma_tipo_documento ==5){
        $numero_factura_proveedor  = $forma_documento_soporte_orden;
        $numero_remision_proveedor = "";
    }

    //Si se cruza por totales
    if(($forma_tipo==0) && (!$consulta_movimiento_cruce) && ($forma_documento_soporte_orden)){
                
        if(isset($consulta_cruce_orden)){
            $insertar_cruce_orden  = SQL::insertar("cruce_orden_compra", $datos_cruce_orden);
            $numero_codigos        = count($forma_codigos);
        $numero_cantidades         = count($forma_cantidades);
        //Inicia lectura del movimiento
        $codigo_cruce_orden_compra = SQL::obtenerValor("cruce_orden_compra", "codigo", "codigo_orden_compra = '$forma_codigo_orden_compra' LIMIT 0,1");
        $consulta_movimiento       = SQL::seleccionar(array("movimiento_ordenes_compra"), array("*"), "codigo_orden_compra = '$forma_codigo_orden_compra' AND codigo_sucursal_destino='$forma_codigo_sucursal'");
        $datos_item_movimiento     = SQL::filaEnObjeto($consulta_movimiento);

        for ($i=1; $i<=$numero_codigos; $i++) { 
            $codigo                   = $forma_codigos[$i];

            $valor_unitario           = SQL::obtenerValor("lista_precio_articulos","costo","codigo_articulo='$codigo'"); 
            $codigo_tasa_impuesto     = SQL::obtenerValor("articulos","codigo_impuesto_compra","codigo='$codigo'");
            $codigo_tasa              = SQL::obtenerValor("tasas ","codigo","codigo='$codigo_tasa_impuesto'"); 
            $porcentaje_tasa_impuesto = SQL::obtenerValor("vigencia_tasas","porcentaje","codigo_tasa='$codigo_tasa'");

            $valor_total              = $valor_unitario * $forma_cantidades[$i];
            $valor_descuento_global1  = ($valor_total * $datos_item_movimiento->descuento_global1)/100;
            $valor_iva                = (($valor_total - $valor_descuento_global1) * $porcentaje_tasa_impuesto) /100;             
            $neto_pagar               = $valor_total - $valor_descuento_global1 + $valor_iva;
            $datos_movimiento_cruce_orden_compra  = array(
                "codigo_cruce_orden_compra" => $codigo_cruce_orden_compra,
                "codigo_articulo"           => $forma_codigos[$i],
                "cantidad_total"            => $forma_cantidades[$i],
                "valor_total"               => $valor_total,
                "valor_descuento_global1"   => $valor_descuento_global1,
                "neto_pagar"                => $neto_pagar,
                "valor_iva"                 => $valor_iva,
                "codigo_tipo_documento"     => $forma_tipo_documento,
                "numero_factura_proveedor"  => $numero_factura_proveedor,
                "numero_remision_proveedor" => $numero_remision_proveedor,
                "observaciones"             => "",
                "fecha_registro"            => date("Y-m-d H:i:s"),
                "codigo_usuario_registra"   => $sesion_id_usuario_ingreso
            );
            $insertar_movimiento_cruce_orden = SQL::insertar("movimiento_cruce_orden_compra", $datos_movimiento_cruce_orden_compra);
            //Obtengo cantidades por codigo en el movimiento y en el cruce
            $unidades_orden           = SQL::obtenerValor("movimiento_ordenes_compra","SUM(cantidad_total)","codigo_orden_compra='$forma_codigo_orden_compra'");
            $unidades_cruce           = SQL::obtenerValor("movimiento_cruce_orden_compra","SUM(cantidad_total)","codigo_cruce_orden_compra='$codigo_cruce_orden_compra'");
            $unidades_cruce = intval($unidades_cruce);
            $unidades_orden = intval($unidades_orden);
            
            if($unidades_cruce<$unidades_orden){
                $datos = array(
                    "estado" => "1"
                ); 
                $modificar_encabezado = SQL::modificar("ordenes_compra", $datos, "codigo = '$forma_codigo_orden_compra'");
                $modificar_movimiento = SQL::modificar("movimiento_ordenes_compra", $datos, "codigo_orden_compra = '$forma_codigo_orden_compra'");
            
                if (($modificar_encabezado) && ($modificar_movimiento)) {
                    $error    = false;
                    $mensaje  = $textos["ORDEN_CUMPLIDA_PARCIALMENTE"]; 
                }
                $error    = false;
                $mensaje  = $textos["ORDEN_CUMPLIDA_PARCIALMENTE"]; 
            }elseif($unidades_cruce==$unidades_orden){
                $datos = array(
                    "estado" => "3"
                );
                $modificar_encabezado = SQL::modificar("ordenes_compra", $datos, "codigo = '$forma_codigo_orden_compra'");
                $modificar_movimiento = SQL::modificar("movimiento_ordenes_compra", $datos, "codigo_orden_compra = '$forma_codigo_orden_compra'");
            
                if (($modificar_encabezado) && ($modificar_movimiento)) {
                    $error    = false;
                    $mensaje  = $textos["ORDEN_CUMPLIDA"];
                }
            }    
        }
            //Inicia lectura del movimiento
            /*$codigo_cruce_orden_compra = SQL::obtenerValor("cruce_orden_compra", "codigo", "codigo_orden_compra = '$forma_codigo_orden_compra' LIMIT 0,1");

            $consulta_movimiento       = SQL::seleccionar(array("movimiento_ordenes_compra"), array("*"), "codigo_orden_compra = '$forma_codigo_orden_compra' AND codigo_sucursal_destino='$forma_codigo_sucursal'");
            
            $datos = array(
                "estado" => "3"
            );

            if($consulta_movimiento){
                if (SQL::filasDevueltas($consulta_movimiento)) {
                    while ($datos_item  = SQL::filaEnObjeto($consulta_movimiento)) {
                        $datos_movimiento_cruce_orden_compra  = array(
                            "codigo_cruce_orden_compra" => $codigo_cruce_orden_compra,
                            "codigo_articulo"           => $datos_item->codigo_articulo,
                            "cantidad_total"            => $datos_item->cantidad_total,
                            "valor_total"               => $datos_item->valor_total,
                            "valor_descuento_global1"   => $datos_item->valor_descuento_global1,
                            "neto_pagar"                => $datos_item->neto_pagar,
                            "valor_iva"                 => $datos_item->valor_iva,
                            "codigo_tipo_documento"     => $forma_tipo_documento,
                            "numero_factura_proveedor"  => $numero_factura_proveedor,
                            "numero_remision_proveedor" => $numero_remision_proveedor,
                            "observaciones"             => "",
                            "fecha_registro"            => date("Y-m-d H:i:s"),
                            "codigo_usuario_registra"   => $sesion_id_usuario_ingreso
                        );
                        $insertar_movimiento_cruce_orden = SQL::insertar("movimiento_cruce_orden_compra", $datos_movimiento_cruce_orden_compra);
                    }
                }
            }
            $modificar_encabezado = SQL::modificar("ordenes_compra", $datos, "codigo = '$forma_codigo_orden_compra'");
            $modificar_movimiento = SQL::modificar("movimiento_ordenes_compra", $datos, "codigo_orden_compra = '$forma_codigo_orden_compra'");
            
            if (($modificar_encabezado) && ($modificar_movimiento)) {
                $error    = false;
                $mensaje  = $textos["ORDEN_CUMPLIDA"];
            }*/  
        }
    }elseif(($forma_tipo==1) && ($forma_documento_soporte_orden)){
        if(isset($consulta_cruce_orden)){
            $insertar_cruce_orden = SQL::insertar("cruce_orden_compra", $datos_cruce_orden);
        }    
        $numero_codigos    = count($forma_codigos);
        $numero_cantidades = count($forma_cantidades);
        //Inicia lectura del movimiento
        $codigo_cruce_orden_compra = SQL::obtenerValor("cruce_orden_compra", "codigo", "codigo_orden_compra = '$forma_codigo_orden_compra' LIMIT 0,1");
        $consulta_movimiento       = SQL::seleccionar(array("movimiento_ordenes_compra"), array("*"), "codigo_orden_compra = '$forma_codigo_orden_compra' AND codigo_sucursal_destino='$forma_codigo_sucursal'");
        $datos_item_movimiento     = SQL::filaEnObjeto($consulta_movimiento);

        for ($i=1; $i<=$numero_codigos; $i++) { 
            $codigo                   = $forma_codigos[$i];

            $valor_unitario           = SQL::obtenerValor("lista_precio_articulos","costo","codigo_articulo='$codigo'"); 
            $codigo_tasa_impuesto     = SQL::obtenerValor("articulos","codigo_impuesto_compra","codigo='$codigo'");
            $codigo_tasa              = SQL::obtenerValor("tasas ","codigo","codigo='$codigo_tasa_impuesto'"); 
            $porcentaje_tasa_impuesto = SQL::obtenerValor("vigencia_tasas","porcentaje","codigo_tasa='$codigo_tasa'");

            $valor_total              = $valor_unitario * $forma_cantidades[$i];
            $valor_descuento_global1  = ($valor_total * $datos_item_movimiento->descuento_global1)/100;
            $valor_iva                = (($valor_total - $valor_descuento_global1) * $porcentaje_tasa_impuesto) /100;             
            $neto_pagar               = $valor_total - $valor_descuento_global1 + $valor_iva;
            $datos_movimiento_cruce_orden_compra  = array(
                "codigo_cruce_orden_compra" => $codigo_cruce_orden_compra,
                "codigo_articulo"           => $forma_codigos[$i],
                "cantidad_total"            => $forma_cantidades[$i],
                "valor_total"               => $valor_total,
                "valor_descuento_global1"   => $valor_descuento_global1,
                "neto_pagar"                => $neto_pagar,
                "valor_iva"                 => $valor_iva,
                "codigo_tipo_documento"     => $forma_tipo_documento,
                "numero_factura_proveedor"  => $numero_factura_proveedor,
                "numero_remision_proveedor" => $numero_remision_proveedor,
                "observaciones"             => "",
                "fecha_registro"            => date("Y-m-d H:i:s"),
                "codigo_usuario_registra"   => $sesion_id_usuario_ingreso
            );
            $insertar_movimiento_cruce_orden = SQL::insertar("movimiento_cruce_orden_compra", $datos_movimiento_cruce_orden_compra);
            //Obtengo cantidades por codigo en el movimiento y en el cruce
            $unidades_orden           = SQL::obtenerValor("movimiento_ordenes_compra","SUM(cantidad_total)","codigo_orden_compra='$forma_codigo_orden_compra'");
            $unidades_cruce           = SQL::obtenerValor("movimiento_cruce_orden_compra","SUM(cantidad_total)","codigo_cruce_orden_compra='$codigo_cruce_orden_compra'");
            $unidades_cruce = intval($unidades_cruce);
            $unidades_orden = intval($unidades_orden);
            
            if($unidades_cruce<$unidades_orden){
                $datos = array(
                    "estado" => "1"
                ); 
                $modificar_encabezado = SQL::modificar("ordenes_compra", $datos, "codigo = '$forma_codigo_orden_compra'");
                $modificar_movimiento = SQL::modificar("movimiento_ordenes_compra", $datos, "codigo_orden_compra = '$forma_codigo_orden_compra'");
            
                if (($modificar_encabezado) && ($modificar_movimiento)) {
                    $error    = false;
                    $mensaje  = $textos["ORDEN_CUMPLIDA_PARCIALMENTE"]; 
                }
                $error    = false;
                $mensaje  = $textos["ORDEN_CUMPLIDA_PARCIALMENTE"]; 
            }elseif($unidades_cruce==$unidades_orden){
                $datos = array(
                    "estado" => "3"
                );
                $modificar_encabezado = SQL::modificar("ordenes_compra", $datos, "codigo = '$forma_codigo_orden_compra'");
                $modificar_movimiento = SQL::modificar("movimiento_ordenes_compra", $datos, "codigo_orden_compra = '$forma_codigo_orden_compra'");
            
                if (($modificar_encabezado) && ($modificar_movimiento)) {
                    $error    = false;
                    $mensaje  = $textos["ORDEN_CUMPLIDA"];
                }
            }    
        }
        
    }else {
        $respuesta    = array();
        $error        = true;
        $mensaje      = $textos["ERROR_CRUZAR"];
    }
    /*** Enviar datos con la respuesta del proceso al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>
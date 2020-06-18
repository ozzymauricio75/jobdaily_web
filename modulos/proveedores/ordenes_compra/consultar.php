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

    // Verificar que se haya enviado el ID del elemento a consultar
    if (empty($url_id)) {
        $error     = $textos["ERROR_CONSULTAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {
        $vistaConsulta = "ordenes_compra";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "id = '".$url_id."'");
        $datos         = SQL::filaEnObjeto($consulta);
        $error         = "";
        $titulo        = $componente->nombre;

        $consulta_proveedor = SQL::seleccionar(array("terceros"),array("*"),"id='$datos->id_proveedor'");
        if (SQL::filasDevueltas($consulta_proveedor)){
            $datos_proveedor = SQL::filaEnObjeto($consulta_proveedor);
        }

        if (isset($datos_proveedor)){

            $vistaConsulta = "movimiento_ordenes_compra";
            $columnas      = SQL::obtenerColumnas($vistaConsulta);
            $consulta_movimiento = SQL::seleccionar(array($vistaConsulta), $columnas, "id_orden_compra='".$datos->id."'","id_articulo DESC, id ASC, color ASC, id_concepto_criterio_subnivel_articulo ASC");

            $nombre_proveedor = SQL::obtenerValor("menu_terceros","NOMBRE_COMPLETO","id='$datos_proveedor->id'");
            // Definicion de pestanas
            $encabezado["PESTANA_GENERAL"] = array(
                array(
                    HTML::mostrarDato("nir_proveedor", $textos["NIT_PROVEEDOR"], $datos_proveedor->documento_identidad),
                    HTML::mostrarDato("proveedor", $textos["PROVEEDOR"], $nombre_proveedor)
                )
            );


            $regimen = array(
                "1" => $textos["COMUN"],
                "2" => $textos["SIMPLIFICADO"],
            );
            $id_municipio = SQL::obtenerValor("localidades","id_municipio","id='$datos_proveedor->id_localidad'");
            $municipio = SQL::obtenerValor("seleccion_municipios","nombre","id='$id_municipio'");
            $municipio = explode("|",$municipio);
            $municipio = $municipio[0];
            $sucursal_pedido = SQL::obtenerValor("sucursales","nombre","id='$datos->id_sucursal'");
            $tipo_documento  = SQL::obtenerValor("tipos_documentos","descripcion","id='$datos->id_tipo_documento'");
            $tipo_entrada_salida = SQL::obtenerValor("tipos_entrada_salida","descripcion","id='$datos->id_tipo_entrada_salida'");
            if ($datos->observaciones !=""){
                $titulo_observaciones = $textos["OBSERVACIONES"];
                $observaciones = $datos->observaciones;
            } else {
                $titulo_observaciones = "";
                $observaciones = "";
            }
            $numero_pedido = SQL::obtenerValor("propuesta_pedidos","numero_consecutivo","id='$datos->id_propuesta_pedido'");

            $formularios["PESTANA_DATOS_PEDIDO"] = array(
                array(
                    HTML::agrupador(
                        array(
                            array(
                                HTML::mostrarDato("sucursal",$textos["SUCURSAL"], $sucursal_pedido)
                            ),
                            array(
                                HTML::mostrarDato("fecha_documento_mostrar",$textos["FECHA_DOCUMENTO"], $datos->fecha_documento),
                            ),
                            array(
                                HTML::mostrarDato("tipo_documento",$textos["TIPO_DOCUMENTO"], $tipo_documento)
                            ),
                            array(
                                HTML::mostrarDato("tipo_entrada_salida",$textos["TIPO_ENTRADA_SALIDA"], $tipo_entrada_salida)
                            ),
                            array(
                                HTML::mostrarDato("orden_compra",$textos["ORDEN_COMPRA"], (int)$datos->numero_consecutivo)
                            ),
                            array(
                                HTML::mostrarDato("numero_pedido",$textos["NUMERO_PEDIDO"], $numero_pedido)
                            )
                        ),
                        $textos["DATOS_DOCUMENTO"]
                    )
                ),
                array(
                    HTML::agrupador(
                        array(
                            array(
                                HTML::mostrarDato("regimen",$textos["REGIMEN"], $regimen[$datos_proveedor->regimen])
                            ),
                            array(
                                HTML::mostrarDato("municipio",$textos["MUNICIPIO"], $municipio)
                            ),
                            array(
                                HTML::mostrarDato("direccion",$textos["DIRECCION"], $datos_proveedor->direccion_principal)
                            ),
                            array(
                                HTML::mostrarDato("telefono",$textos["TELEFONO"], $datos_proveedor->telefono_principal),
                                HTML::mostrarDato("celular",$textos["CELULAR"], $datos_proveedor->celular)
                            ),
                            array(
                                HTML::mostrarDato("correo_electronico",$textos["CORREO_ELECTRONICO"], $datos_proveedor->correo)
                            )
                        ),
                        $textos["DATOS_PROVEEDOR"]
                    )
                ),
                array(
                    HTML::mostrarDato("observaciones",$titulo_observaciones,$observaciones)
                )
            );

            $id_tercero_comprador = SQL::obtenerValor("compradores","id_tercero","id='$datos->id_comprador'");
            $comprador = SQL::obtenerValor("menu_terceros","NOMBRE_COMPLETO","id='$id_tercero_comprador'");
            $moneda = SQL::obtenerValor("monedas","descripcion","id='$datos->id_moneda'");
            if ($datos->participacion > 0){
                $titulo_participacion = $textos["PARTICIPACION"];
                $participacion = $datos->participacion;
            } else {
                $titulo_participacion = "";
                $participacion = "";
            }
            $descuentos = false;
            if ($datos->descuento_financiero_fijo > 0){
                $titulo_financiero_fijo = $textos["DESCUENTO_FINANCIERO_FIJO"];
                $decimales = $sesion_proveedores_numero_decimales_valores;
                if ($decimales <= 0){
                    $decimales_fijo = explode(".",$datos->descuento_financiero_fijo);
                    if (isset($decimales_fijo[1])){
                        if ($decimales_fijo[1] > 0){
                            $decimales = 2;
                        }
                    }
                }
                $financiero_fijo = number_format($datos->descuento_financiero_fijo,$decimales,".",",");
                $descuentos = true;
            } else {
                $titulo_financiero_fijo = "";
                $financiero_fijo = "";
            }
            if ($datos->descuento_financiero_pronto_pago > 0){
                $titulo_financiero_pronto_pago = $textos["DESCUENTO_FINANCIERO_PRONTO"];
                $decimales = $sesion_proveedores_numero_decimales_valores;
                if ($decimales <= 0){
                    $decimales_pronto = explode(".",$datos->descuento_financiero_fijo);
                    if (isset($decimales_pronto[1])){
                        if ($decimales_pronto[1] > 0){
                            $decimales = 2;
                        }
                    }
                }
                $financiero_pronto_pago = number_format($datos->descuento_financiero_pronto_pago,$decimales,".",",");
                if ($datos->descuento_financiero_pronto_pago > 0){
                    $titulo_numero_dias_pronto_pago = $textos["NUMERO_DIAS_PRONTO_PAGO"];
                    $numero_dias_pronto_pago = number_format($datos->numero_dias_pronto_pago,0,".",",");
                }
                $descuentos = true;
            } else {
                $titulo_financiero_pronto_pago = "";
                $financiero_pronto_pago = "";
                $titulo_numero_dias_pronto_pago = "";
                $numero_dias_pronto_pago = "";
            }
            if ($datos->descuento_global1 > 0){
                $titulo_descuento_global1 = $textos["DESCUENTO_GLOBAL1"];
                $decimales = $sesion_proveedores_numero_decimales_valores;
                if ($decimales <= 0){
                    $decimales_global = explode(".",$datos->descuento_global1);
                    if (isset($decimales_global[1])){
                        if ($decimales_global[1] > 0){
                            $decimales = 2;
                        }
                    }
                }
                $descuento_global1 = number_format($datos->descuento_global1,$decimales,".",",");
                $descuentos = true;
            } else {
                $titulo_descuento_global1 = "";
                $descuento_global1 = "";
            }
            if ($datos->descuento_global2 > 0){
                $titulo_descuento_global2 = $textos["DESCUENTO_GLOBAL2"];
                $decimales = $sesion_proveedores_numero_decimales_valores;
                if ($decimales <= 0){
                    $decimales_global = explode(".",$datos->descuento_global2);
                    if (isset($decimales_global[1])){
                        if ($decimales_global[1] > 0){
                            $decimales = 2;
                        }
                    }
                }
                $descuento_global1 = number_format($datos->descuento_global2,$decimales,".",",");
                $descuentos = true;
            } else {
                $titulo_descuento_global2 = "";
                $descuento_global2 = "";
            }
            if ($datos->descuento_global3 > 0){
                $titulo_descuento_global3 = $textos["DESCUENTO_GLOBAL3"];
                $decimales = $sesion_proveedores_numero_decimales_valores;
                if ($decimales <= 0){
                    $decimales_global = explode(".",$datos->descuento_global3);
                    if (isset($decimales_global[1])){
                        if ($decimales_global[1] > 0){
                            $decimales = 2;
                        }
                    }
                }
                $descuento_global1 = number_format($datos->descuento_global3,$decimales,".",",");
                $descuentos = true;
            } else {
                $titulo_descuento_global3 = "";
                $descuento_global3 = "";
            }
            if ($datos->numero_entregas > 0){
                $titulo_numero_entregas = $textos["NUMERO_ENTREGAS"];
                $numero_entregas = $datos->numero_entregas;
                $titulo_fecha_ultima_entrega = $textos["FECHA_FINAL_ENTREGAS"];
                $fecha_ultima_entrega = $datos->fecha_final_entregas;
            } else {
                $titulo_numero_entregas = "";
                $numero_entregas = "";
                $titulo_fecha_ultima_entrega = "";
                $fecha_ultima_entrega = "";
            }
            if ($descuentos){
                $arreglo_descuentos =  array(
                    HTML::agrupador(
                        array(
                            array(
                                HTML::mostrarDato("descuento_financiero_fijo",$titulo_financiero_fijo, $financiero_fijo),
                            ),
                            array(
                                HTML::mostrarDato("descuento_financiero_pronto_pago",$titulo_financiero_pronto_pago, $financiero_pronto_pago),
                                HTML::mostrarDato("numero_dias_pronto_pago",$titulo_numero_dias_pronto_pago, $numero_dias_pronto_pago),
                            ),
                            array(
                                HTML::mostrarDato("descuento_global1",$titulo_descuento_global1, $descuento_global1),
                                HTML::mostrarDato("descuento_global2",$titulo_descuento_global2, $descuento_global2),
                                HTML::mostrarDato("descuento_global3",$titulo_descuento_global3, $descuento_global3),
                            )
                        ),
                        $textos["DESCUENTOS"]
                    )
                );
            } else {
                $arreglo_descuentos = array(
                    HTML::mostrarDato("descuentos","","")
                );
            }
            if ((int)$datos->id_sucursal == 999){
                $datos->iva_incluido = 0;
            }
            $formularios["PESTANA_NEGOCIACION"] = array(
                array(
                    HTML::mostrarDato("comprador",$textos["COMPRADOR"], $comprador)
                ),
                array(
                    HTML::mostrarDato("participacion",$titulo_participacion, $participacion)
                ),
                array(
                    HTML::mostrarDato("moneda",$textos["MONEDA"], $moneda)
                ),
                $arreglo_descuentos,
                array(
                    HTML::mostrarDato("iva_incluido",$textos["IVA_INCLUIDO"], $textos["SI_NO_".$datos->iva_incluido])
                ),
                array(
                    HTML::mostrarDato("numero_dias_pago",$textos["NUMERO_DIAS_PAGO"], $datos->numero_dias_pago)
                ),
                array(
                    HTML::mostrarDato("numero_entregas",$titulo_numero_entregas, $numero_entregas),
                    HTML::mostrarDato("fecha_final_entregas",$titulo_fecha_ultima_entrega, $fecha_ultima_entrega),
                ),
                array(
                    HTML::campoOculto("id_orden_compra",$datos->id)
                )
            );

            $items = array();
            while ($datos_movimiento = SQL::filaEnObjeto($consulta_movimiento)){

                $consulta_articulo = SQL::seleccionar(array("articulos"),array("*"),"id='$datos_movimiento->id_articulo'");
                $datos_articulo = SQL::filaEnObjeto($consulta_articulo);
                $ruta_foto = trim($datos_articulo->ruta_foto_principal);
                $nombre_foto = trim($datos_articulo->nombre_archivo_foto_principal);
                $foto = "";
                if ($ruta_foto!="" && $nombre_foto!=""){
                    $nombreArchivo = $ruta_foto."/".$nombre_foto;
                    if (file_exists($nombreArchivo)){
                        if (($archivo = fopen($nombreArchivo, "r")) !== FALSE){
                            $nombreArchivoMuestra  = $rutasGlobales["temp"]."/".$nombre_foto;
                            copy($nombreArchivo, $nombreArchivoMuestra);
                            $foto = HTML::enlazarPagina($textos["DESCARGAR"],$nombreArchivoMuestra);
                        }
                    }
                }
                $estructura = SQL::obtenerValor("estructura_grupos","descripcion","id='$datos_articulo->id_estructura_grupo'");
                $consulta_caracteristicas = SQL::seleccionar(array("caracteristicas_articulos"),array("id_caracteristica"),"id_articulo='$datos_movimiento->id_articulo'");
                $caracteristicas = "";
                if (SQL::filasDevueltas($consulta_caracteristicas)){
                    $caracteristicas .=", ";
                    while ($datos_caracteristicas = SQL::filaEnObjeto($consulta_caracteristicas)){
                        $caracteristicas .= SQL::obtenerValor("caracteristicas","descripcion","id='$datos_caracteristicas->id_caracteristica'").", ";
                    }
                }
                $detalle = $estructura.$caracteristicas.$datos_articulo->detalle;
                $referencia = $datos_movimiento->referencia;
                $codigo_articulo = $datos_articulo->codigo;
                $sucursal_destino = SQL::obtenerValor("sucursales","nombre_corto","id='$datos_movimiento->id_sucursal_destino'");
                $concepto = "";
                if ($datos_movimiento->id_concepto_criterio_subnivel_articulo > 0){
                    $concepto = SQL::obtenerValor("conceptos_criterio_subnivel_articulos","descripcion","id='$datos_movimiento->id_concepto_criterio_subnivel_articulo'");
                }
                /*$color = "";
                if ($datos_movimiento->id_color > 0){
                    $color = SQL::obtenerValor("colores","descripcion","id='$datos_movimiento->id_color'");
                }*/
                $color = $datos_movimiento->color;
                $descuento = $datos_movimiento->valor_descuento_global1 + $datos_movimiento->valor_descuento_global2 + $datos_movimiento->valor_descuento_global3 + $datos_movimiento->valor_descuento_linea;
                if ($descuento > 0){
                    $descuento = number_format($descuento,$sesion_proveedores_numero_decimales_valores,".",",");
                } else {
                    $descuento = "";
                }
                if ($datos->iva_incluido == "1" && (int)$datos->id_sucursal != 999){
                    $factor_impuesto = str_pad(str_replace(".","",$datos_movimiento->porcentaje_impuesto),6,"0");
                    $factor_unitario_incluido  = "1.".($factor_impuesto);
                } else {
                    $factor_unitario_incluido = 1;
                }
                if (!isset($factor_conversion[$datos_movimiento->id_unidad_medida])){
                    $unidad[$datos_movimiento->id_unidad_medida] = SQL::obtenerValor("unidades","descripcion","id='".$datos_movimiento->id_unidad_medida."'");
                    if (!$unidad[$datos_movimiento->id_unidad_medida]){
                        $unidad[$datos_movimiento->id_unidad_medida] = "";
                    }
                    $factor_conversion[$datos_movimiento->id_unidad_medida] = SQL::obtenerValor("unidades","factor_conversion","id='".$datos_movimiento->id_unidad_medida."'");
                    if (!$factor_conversion[$datos_movimiento->id_unidad_medida]){
                        $factor_conversion[$datos_movimiento->id_unidad_medida] = 1;
                    }
                }
                $valor_unitario = ($datos_movimiento->valor_unitario * $factor_conversion[$datos_movimiento->id_unidad_medida]) * $factor_unitario_incluido;
                $subtotal = $valor_unitario * $datos_movimiento->cantidad;
                $items[] = array(
                    $datos_movimiento->id,
                    $sucursal_destino,
                    $codigo_articulo,
                    $referencia,
                    $detalle,
                    $concepto,
                    $color,
                    number_format($datos_movimiento->cantidad,$sesion_proveedores_numero_decimales_cantidad,".",","),
                    $unidad[$datos_movimiento->id_unidad_medida],
                    number_format($valor_unitario,$sesion_proveedores_numero_decimales_valores,".",","),
                    number_format($subtotal,$sesion_proveedores_numero_decimales_valores,".",","),
                    number_format($datos_movimiento->porcentaje_impuesto,$sesion_proveedores_numero_decimales_valores,".",","),
                    number_format($datos_movimiento->descuento_linea,$sesion_proveedores_numero_decimales_valores,".",","),
                    $datos_movimiento->fecha_entrega,
                    $foto,
                    $datos_movimiento->observaciones
                );
            }
            $formularios["PESTANA_ARTICULOS"] = array(
                array(
                    HTML::generarTabla(
                        array("id","SUCURSAL_DESTINO","ARTICULO","REFERENCIA","DETALLE","CONCEPTO","COLOR","CANTIDAD","UNIDAD_MEDIDA","VALOR_UNITARIO","SUBTOTAL","TASA","DESCUENTO","FECHA_ENTREGA","FOTO","OBSERVACIONES"),
                        $items,
                        array("I","I","I","I","I","I","I","I","D","D","C","D","C","C","I"),
                        "listaArticulos",
                        false
                    )
                )
            );

            $unidades = SQL::obtenerValor("movimiento_ordenes_compra","SUM(cantidad_total)","id_orden_compra='$datos->id'");
            $columnas = array(
                "valor_descuento_global1",
                "valor_descuento_global2",
                "valor_descuento_global3",
                "valor_descuento_linea",
                "valor_total",
                "valor_iva",
                "neto_pagar"
            );
            $consulta_movimiento = SQL::seleccionar(array("movimiento_ordenes_compra"),$columnas,"id_orden_compra='$datos->id'");
            $subtotal = 0;
            $linea = 0;
            $global1 = 0;
            $global2 = 0;
            $global3 = 0;
            $iva = 0;
            $total = 0;
            if (SQL::filasDevueltas($consulta_movimiento)){
                $subtotal = 0;
                $linea = 0;
                $global1 = 0;
                $global2 = 0;
                $global3 = 0;
                $iva = 0;
                $total = 0;
                while($datos_total = SQL::filaEnObjeto($consulta_movimiento)){
                    $subtotal += $datos_total->valor_total;
                    $linea += $datos_total->valor_descuento_linea;
                    $global1 += $datos_total->valor_descuento_global1;
                    $global2 += $datos_total->valor_descuento_global2;
                    $global3 += $datos_total->valor_descuento_global3;
                    $iva += $datos_total->valor_iva;
                    $total = $datos_total->neto_pagar;
                }
            }
            $globales = $global1 + $global2 + $global3;
            $clase_descuentos = "";
            if ($globales <= 0){
                $clase_descuentos = "oculto";
            }
            $clase_linea = "";
            if ($linea <= 0){
                $clase_linea = "oculto";
            }
            $clase_iva = "";
            if ($iva <= 0){
                $clase_iva = "oculto";
            }

            $formularios["PESTANA_TOTAL_PEDIDO"] = array(
                array(
                    HTML::mostrarDato("total_unidades",$textos["TOTAL_UNIDADES"], number_format($unidades,$sesion_proveedores_numero_decimales_cantidad,".",","))
                ),
                array(
                    HTML::mostrarDato("subtotal_pedido",$textos["SUBTOTAL"], number_format($subtotal,$sesion_proveedores_numero_decimales_valores,".",","))
                ),
                array(
                    HTML::mostrarDato("total_descuentos_linea_pedido",$textos["TOTAL_DESCUENTOS_LINEA"], number_format($linea,$sesion_proveedores_numero_decimales_valores,".",","), "", array("class"=>$clase_linea))
                ),
                array(
                    HTML::mostrarDato("total_descuentos_globales_pedido",$textos["TOTAL_DESCUENTOS_GLOBALES"], number_format($globales,$sesion_proveedores_numero_decimales_valores,".",","), "", array("class"=>$clase_descuentos))
                ),
                array(
                    HTML::mostrarDato("total_iva_pedido",$textos["TOTAL_IVA"], number_format($iva,$sesion_proveedores_numero_decimales_valores,".",","), "", array("class"=>$clase_iva))
                ),
                array(
                    HTML::mostrarDato("total_pedido",$textos["TOTAL_PEDIDO"], number_format($total,$sesion_proveedores_numero_decimales_valores,".",","))
                )
            );

            $botones = array(HTML::boton("botonAceptar", $textos["IMPRIMIR"], "imprimirItem('0');", "exportar"));
            $contenido = HTML::generarPestanas($formularios, $botones, "", "", $encabezado);
        } else {
            $contenido = "";
            $error     = $textos["CREAR_PROVEEDOR"];
        }
    }

    // Enviar datos para la generacion del formulario al script que origino la peticion
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

} elseif (!empty($forma_procesar)) {
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];

    // Validar el ingreso de los datos requeridos
    if(!isset($forma_id_orden_compra) || $forma_id_orden_compra <=0){
        $error   = true;
        $mensaje = $textos["ENCABEZADO_VACIO"];

    } else {
        include("clases/ordenes.php");
        $ruta_archivo = imprimir_ordenes($forma_id_orden_compra,$textos,$sem,$imagenesGlobales,$rutasGlobales, $sesion_proveedores_numero_decimales_cantidad, $sesion_proveedores_numero_decimales_valores);

    }
    // Enviar datos con la respuesta del proceso al script que origino la peticion
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    if (isset($ruta_archivo)){
        $respuesta[2] = $ruta_archivo;
    }
    HTTP::enviarJSON($respuesta);
}
?>

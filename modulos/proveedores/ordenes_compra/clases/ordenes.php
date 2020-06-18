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

    function imprimir_ordenes($id_orden_compra, $textos,$sem,$imagenesGlobales,$rutasGlobales,$decimales_cantidad, $decimales_valores){
        $consulta_encabezado = SQL::seleccionar(array("ordenes_compra"),array("*"),"id='$id_orden_compra'");
        $datos_encabezado = SQL::filaEnObjeto($consulta_encabezado);
        $descuento_global1_iva_incluido = SQL::obtenerValor("propuesta_pedidos","descuento_global1_iva_incluido","id='".$datos_encabezado->id_propuesta_pedido."'");

        $estado_orden = $datos_encabezado->estado;
        $cantidad_registros  = SQL::obtenerValor("movimiento_ordenes_compra","COUNT(id)","id_orden_compra='$id_orden_compra'");
        $consulta_subtotal_encabezado = SQL::seleccionar(array("movimiento_ordenes_compra"),array("valor_total","valor_unitario","cantidad","iva_incluido","porcentaje_impuesto"),"id_orden_compra='$id_orden_compra'");
        if (SQL::filasDevueltas($consulta_subtotal_encabezado)){
            $subtotal_encabezado = 0;
            while ($datos_consulta_encabezado = SQL::filaEnObjeto($consulta_subtotal_encabezado)){
                if ($datos_consulta_encabezado->iva_incluido == "1" && (int)$datos_encabezado->id_sucursal != 999){
                    $factor_impuesto = str_pad(str_replace(".","",$datos_consulta_encabezado->porcentaje_impuesto),6,"0");
                    $factor = "1.".($factor_impuesto);
                    $valor_unitario = round($datos_consulta_encabezado->valor_unitario * $factor);
                    $subtotal_encabezado += ($valor_unitario * $datos_consulta_encabezado->cantidad);
                } else {
                    $subtotal_encabezado += $datos_consulta_encabezado->valor_total;
                }
            }
        }
        $unidades_encabezado = SQL::obtenerValor("movimiento_ordenes_compra","SUM(cantidad)","id_orden_compra='$id_orden_compra'");
        $porcentaje_global1_encabezado  = $datos_encabezado->descuento_global1;
        $porcentaje_global2_encabezado  = $datos_encabezado->descuento_global2;
        $porcentaje_global3_encabezado  = $datos_encabezado->descuento_global3;
        $global1_encabezado  = SQL::obtenerValor("movimiento_ordenes_compra","SUM(valor_descuento_global1)","id_orden_compra='$id_orden_compra'");
        $global2_encabezado  = SQL::obtenerValor("movimiento_ordenes_compra","SUM(valor_descuento_global2)","id_orden_compra='$id_orden_compra'");
        $global3_encabezado  = SQL::obtenerValor("movimiento_ordenes_compra","SUM(valor_descuento_global3)","id_orden_compra='$id_orden_compra'");
        $linea_encabezado    = SQL::obtenerValor("movimiento_ordenes_compra","SUM(valor_descuento_linea)","id_orden_compra='$id_orden_compra'");
        $iva_encabezado      = SQL::obtenerValor("movimiento_ordenes_compra","SUM(valor_iva)","id_orden_compra='$id_orden_compra'");
        $total_encabezado    = SQL::obtenerValor("movimiento_ordenes_compra","SUM(neto_pagar)","id_orden_compra='$id_orden_compra'");
        $observaciones_encabezado = $datos_encabezado->observaciones;
        $participacion_encabezado = $datos_encabezado->participacion;
        $descuento_financiero_pronto_pago = $datos_encabezado->descuento_financiero_pronto_pago;
        $numero_dias_pronto_pago = $datos_encabezado->numero_dias_pronto_pago;
        $descuento_financiero_pronto_pago2 = $datos_encabezado->descuento_financiero_pronto_pago2;
        $numero_dias_pronto_pago2 = $datos_encabezado->numero_dias_pronto_pago2;
        $descuento_financiero_fijo = $datos_encabezado->descuento_financiero_fijo;
        $numero_dias_pago_encabezado = $datos_encabezado->numero_dias_pago;
        $numero_entregas_encabezado = $datos_encabezado->numero_entregas;
        $fecha_final_entregas_encabezado = $datos_encabezado->fecha_final_entregas;
        $fecha_pedido = $datos_encabezado->fecha_documento;
        $ano_pedido = substr($fecha_pedido,0,4);
        $mes_pedido = substr($fecha_pedido,5,2);
        $dia_pedido = substr($fecha_pedido,8,2);

        $sucursal = SQL::obtenerValor("sucursales","nombre","id='$datos_encabezado->id_sucursal'");
        $sucursal_pedido = SQL::obtenerValor("sucursales","nombre_corto","id='$datos_encabezado->id_sucursal'");
        $numero_pedido = SQL::obtenerValor("propuesta_pedidos","numero_consecutivo","id='$datos_encabezado->id_propuesta_pedido'");

        $columnas = array(
            "direccion_principal",
            "telefono_principal",
            "celular",
            "correo"
        );
        $logo_cliente = $imagenesGlobales["logoClienteReportes"];
        $borrador = "";
        if ($datos_encabezado->estado == "0"){
            $borrador = "";
        } else if ($datos_encabezado->estado == "1"){
            $borrador = $imagenesGlobales["borrador"];
        } else if ($datos_encabezado->estado == "2"){
            $borrador = $imagenesGlobales["anulado"];
        }

        $nombre_proveedor = trim(SQL::obtenerValor("menu_terceros","NOMBRE_COMPLETO","id='$datos_encabezado->id_proveedor'"));
        $nombre_proveedor = str_replace(" ","_",$nombre_proveedor);
        $nombre_proveedor = str_replace("&","",$nombre_proveedor);
        $nombre_proveedor = str_replace("/","-",$nombre_proveedor);
        $nombre         = $nombre_proveedor."-OC-".(int)$datos_encabezado->numero_consecutivo."-".$sucursal_pedido.".pdf";
        $nombreArchivo  = $rutasGlobales["temp"]."/".$nombre;
        $archivo                 = new PDF("P","mm","legal");

        $archivo->textoPiePagina = $textos["ELABORADO_POR"]." ".SQL::obtenerValor("usuarios","nombre","id='$datos_encabezado->id_usuario_registra'");
        encabezadoOrden($archivo,$sem,$datos_encabezado,$textos,$logo_cliente,$borrador,$numero_pedido);

        $consulta_movimiento = SQL::seleccionar(array("movimiento_ordenes_compra"),array("*"),"id_orden_compra='$id_orden_compra' AND cantidad > 0","","id_articulo DESC, id ASC, color ASC, id_concepto_criterio_subnivel_articulo ASC");
        if (SQL::filasDevueltas($consulta_movimiento)){

            $foto_articulo = array();
            $referencia_articulo = array();
            $cantidad_articulo = array();
            $precio_unitario_articulo = array();
            $detalle_articulo = array();
            $codigo_articulo = array();
            $total_articulo = array();
            $iva_articulo = array();
            $linea_articulo = array();
            $global_articulo = array();
            $neto_articulo = array();
            $observaciones_articulo = array();
            $total_unidades_articulo = array();
            $total_concepto_articulo = array();
            $total_color_articulo = array();
            $foto_detalle = array();
            $referencia_detalle = array();
            $cantidad_detalle = array();
            $precio_unitario_detalle = array();
            $detalle_referencia = array();
            $total_detalle = array();
            $iva_detalle = array();
            $linea_detalle = array();
            $global_detalle = array();
            $neto_detalle = array();
            $observaciones_detalle = array();
            $total_unidades_detalle = array();
            $total_concepto_detalle = array();
            $total_color_detalle = array();
            $ancho_color_mayor = 0;
            $ancho_total_color_mayor = 0;
            $ancho_concepto_mayor = 0;
            $ancho_total_concepto_mayor = 0;
            $descripcion_color_mayor = "";
            $descripcion_concepto_mayor = "";

            while($datos_movimiento = SQL::filaEnObjeto($consulta_movimiento)){

                $id_articulo = (int)$datos_movimiento->id_articulo;
                $id_sucursal_destino = $datos_movimiento->id_sucursal_destino;
                $referencia = $datos_movimiento->referencia;
                //$id_color = (int)$datos_movimiento->id_color;
                $id_color = $datos_movimiento->color;
                $id_concepto_criterio_subnivel_articulo = (int)$datos_movimiento->id_concepto_criterio_subnivel_articulo;
                $fecha_entrega = (int)str_replace("-","",$datos_movimiento->fecha_entrega);
                $valor_unitario = str_replace(".","",$datos_movimiento->valor_unitario);
                $unidad_pedido = $datos_movimiento->id_unidad_medida;
                if (!isset($factor_conversion[$unidad_pedido])){
                    $factor_conversion[$unidad_pedido] = SQL::obtenerValor("unidades","factor_conversion","id='".$unidad_pedido."'");
                    if (!$factor_conversion[$unidad_pedido]){
                        $factor_conversion[$unidad_pedido] = 1;
                    }
                }
                if ($id_concepto_criterio_subnivel_articulo > 0){
                    if (!isset($id_criterio[$id_concepto_criterio_subnivel_articulo])){
                        $id_criterio[$id_concepto_criterio_subnivel_articulo] = SQL::obtenerValor("conceptos_criterio_subnivel_articulos","id_criterio_subnivel_articulo","id='$id_concepto_criterio_subnivel_articulo'");
                        $id_subnivel[$id_concepto_criterio_subnivel_articulo] = SQL::obtenerValor("criterio_subnivel_articulos","id_subnivel_articulo","id='".$id_criterio[$id_concepto_criterio_subnivel_articulo]."'");
                        $criterio[$id_concepto_criterio_subnivel_articulo] = SQL::obtenerValor("criterio_subnivel_articulos","criterio","id='".$id_criterio[$id_concepto_criterio_subnivel_articulo]."'");
                        $subnivel[$id_concepto_criterio_subnivel_articulo] = SQL::obtenerValor("subnivel_articulos","subnivel","id='".$id_subnivel[$id_concepto_criterio_subnivel_articulo]."'");
                    }
                }

                $consulta_articulo = SQL::seleccionar(array("articulos"),array("*"),"id='$id_articulo'");
                $datos_articulo = SQL::filaEnObjeto($consulta_articulo);
                $id_sucursal_articulo = $datos_articulo->id_sucursal;
                /*$ruta_foto = trim($datos_articulo->ruta_foto_principal);
                $nombre_foto = trim($datos_articulo->nombre_archivo_foto_principal);*/
                $categoria_referencia[$datos_articulo->id_estructura_grupo] = SQL::obtenerValor("estructura_grupos","id_categoria","id='$datos_articulo->id_estructura_grupo'");
                $grupo1_referencia[$datos_articulo->id_estructura_grupo] = SQL::obtenerValor("estructura_grupos","id_grupo1","id='$datos_articulo->id_estructura_grupo'");
                $grupo2_referencia[$datos_articulo->id_estructura_grupo] = SQL::obtenerValor("estructura_grupos","id_grupo2","id='$datos_articulo->id_estructura_grupo'");
                $condicion_referencia = "id_articulo='$datos_articulo->id' AND id_tercero='$datos_articulo->id_tercero' AND ";
                $condicion_referencia .= "referencia='$datos_movimiento->referencia' AND id_categoria='".$categoria_referencia[$datos_articulo->id_estructura_grupo]."' AND ";
                $condicion_referencia .= "id_grupo1='".$grupo1_referencia[$datos_articulo->id_estructura_grupo]."' AND id_grupo2='".$grupo2_referencia[$datos_articulo->id_estructura_grupo]."' AND ";
                $condicion_referencia .= "id_marca='$datos_articulo->id_marca'";
                $consulta_referencia = SQL::seleccionar(array("referencias_articulos"),array("id","ruta_foto","nombre_archivo"),$condicion_referencia);
                $ruta_foto = "";
                $nombre_foto = "";
                if (SQL::filasDevueltas($consulta_referencia)){
                    $datos_referencia = SQL::filaEnObjeto($consulta_referencia);
                    $ruta_foto = trim($datos_referencia->ruta_foto);
                    $nombre_foto = trim($datos_referencia->nombre_archivo);
                }
                if ($ruta_foto!="" && $nombre_foto!=""){
                    if (!isset($foto_articulo[$id_articulo][$referencia])){
                        $foto_articulo[$id_articulo][$referencia] = $ruta_foto."/".$nombre_foto;
                    }
                }
                if (!isset($codigo_articulo[$id_articulo])){
                    $codigo_articulo[$id_articulo] = $datos_articulo->codigo;
                }
                if (!isset($referencia_articulo[$id_articulo])){
                    $referencia_articulo[$id_articulo] = $datos_movimiento->referencia;
                }
                $id_estructura_grupo = $datos_articulo->id_estructura_grupo;
                if (!isset($concepto[$id_concepto_criterio_subnivel_articulo])){
                    $concepto[$id_concepto_criterio_subnivel_articulo] = trim(SQL::obtenerValor("conceptos_criterio_subnivel_articulos","descripcion","id='$id_concepto_criterio_subnivel_articulo'"));
                }
                if (!isset($sucursal_destino[$id_sucursal_destino])){
                    $sucursal_destino[$id_sucursal_destino] = trim(SQL::obtenerValor("sucursales","nombre_corto","id='$id_sucursal_destino'"));
                }
                if (!isset($color[$id_color])){
                    //$color[$id_color] = SQL::obtenerValor("colores","descripcion","id='$id_color'");
                    $color[$id_color] = $id_color;
                }
                if (!isset($ancho_color[$id_color])){
                    $ancho_color[$id_color] = strlen($color[$id_color]);
                }
                if ($ancho_color[$id_color] > $ancho_color_mayor){
                    $ancho_color_mayor = $ancho_color[$id_color];
                    $descripcion_color_mayor = $color[$id_color];
                }

                if (!isset($ancho_concepto[$id_concepto_criterio_subnivel_articulo])){
                    $ancho_concepto[$id_concepto_criterio_subnivel_articulo] = strlen($concepto[$id_concepto_criterio_subnivel_articulo]);
                }
                if ($ancho_concepto[$id_concepto_criterio_subnivel_articulo] > $ancho_concepto_mayor){
                    $ancho_concepto_mayor = $ancho_concepto[$id_concepto_criterio_subnivel_articulo];
                    $descripcion_concepto_mayor = trim($concepto[$id_concepto_criterio_subnivel_articulo]);
                }
                if (strlen(number_format($datos_movimiento->cantidad,$decimales_cantidad,".",",")) > $ancho_concepto_mayor){
                    $ancho_concepto_mayor = strlen(number_format($datos_movimiento->cantidad,$decimales_cantidad,".",","));
                    $descripcion_concepto_mayor = number_format($datos_movimiento->cantidad,$decimales_cantidad,".",",");
                }
                if (!isset($cantidad_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario][$fecha_entrega][$id_color][$id_concepto_criterio_subnivel_articulo])){
                    $cantidad_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario][$fecha_entrega][$id_color][$id_concepto_criterio_subnivel_articulo] = $datos_movimiento->cantidad;
                } else {
                    $cantidad_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario][$fecha_entrega][$id_color][$id_concepto_criterio_subnivel_articulo] += $datos_movimiento->cantidad;
                }

                if ($datos_movimiento->iva_incluido == "1" && (int)$datos_encabezado->id_sucursal != 999){
                    $factor_impuesto = str_pad(str_replace(".","",$datos_movimiento->porcentaje_impuesto),6,"0");
                    $factor_unitario_incluido  = "1.".($factor_impuesto);
                } else {
                    $factor_unitario_incluido = 1;
                }
                if (!isset($precio_unitario_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario][$fecha_entrega])){
                    if ($datos_movimiento->iva_incluido == "1" && (int)$datos_encabezado->id_sucursal != 999){
                        $precio_unitario_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario][$fecha_entrega] = round(($datos_movimiento->valor_unitario * $factor_conversion[$unidad_pedido]) * $factor_unitario_incluido);
                    } else {
                        $precio_unitario_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario][$fecha_entrega] = $datos_movimiento->valor_unitario * $factor_conversion[$unidad_pedido];
                    }
                }

                if (!isset($detalle_articulo[$id_articulo])){
                    $estructura = trim(SQL::obtenerValor("estructura_grupos","descripcion","id='$id_estructura_grupo'"));
                    $consulta_caracteristicas = SQL::seleccionar(array("caracteristicas_articulos"),array("id_caracteristica"),"id_articulo='$id_articulo'");
                    $caracteristica = " ";
                    if (SQL::filasDevueltas($consulta_caracteristicas)){
                        $caracteristica = "";
                        while($datos_caracteristicas = SQL::filaEnObjeto($consulta_caracteristicas)){
                            $caracteristica .= ", ".SQL::obtenerValor("caracteristicas","descripcion","id='".$datos_caracteristicas->id_caracteristica."'");
                        }
                        $caracteristica .= ", ";
                    }
                    $detalle = $estructura.$caracteristica.trim($datos_articulo->detalle);
                    $detalle_articulo[$id_articulo] = $detalle;
                }

                if (!isset($descripcion_unidad_pedido[$unidad_pedido])){
                    $descripcion_unidad_pedido[$unidad_pedido] = SQL::obtenerValor("unidades","descripcion_corta","id='$unidad_pedido'");
                }
                if (!isset($total_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario][$fecha_entrega])){
                    if ($datos_movimiento->iva_incluido == "1" && (int)$datos_encabezado->id_sucursal != 999){
                        $total_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario][$fecha_entrega] = round($datos_movimiento->valor_total * $factor);
                    } else {
                        $total_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario][$fecha_entrega] = $datos_movimiento->valor_total;
                    }
                } else {
                    if ($datos_movimiento->iva_incluido == "1" && (int)$datos_encabezado->id_sucursal != 999){
                        $total_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario][$fecha_entrega] += round($datos_movimiento->valor_total * $factor);
                    } else {
                        $total_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario][$fecha_entrega] += $datos_movimiento->valor_total;
                    }
                }

                if (!isset($iva_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario][$fecha_entrega])){
                    $iva_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario][$fecha_entrega] = $datos_movimiento->valor_iva;
                } else {
                    $iva_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario][$fecha_entrega] += $datos_movimiento->valor_iva;
                }

                if (!isset($linea_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario][$fecha_entrega])){
                    $linea_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario][$fecha_entrega] = $datos_movimiento->valor_descuento_linea;
                    $porcentaje_linea_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario][$fecha_entrega] = $datos_movimiento->descuento_linea;
                } else {
                    $linea_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario][$fecha_entrega] += $datos_movimiento->valor_descuento_linea;
                }

                if (!isset($global1_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario][$fecha_entrega])){
                    $global1_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario][$fecha_entrega] = $datos_movimiento->valor_descuento_global1;
                    $porcentaje_global1_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario][$fecha_entrega] = $datos_movimiento->descuento_global1;
                } else {
                    $global1_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario][$fecha_entrega] += $datos_movimiento->valor_descuento_global1;
                }
                if (!isset($global2_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario][$fecha_entrega])){
                    $global2_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario][$fecha_entrega] = $datos_movimiento->valor_descuento_global2;
                    $porcentaje_global2_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario][$fecha_entrega] = $datos_movimiento->descuento_global2;
                } else {
                    $global2_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario][$fecha_entrega] += $datos_movimiento->valor_descuento_global2;
                }
                if (!isset($global3_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario][$fecha_entrega])){
                    $global3_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario][$fecha_entrega] = $datos_movimiento->valor_descuento_global3;
                    $porcentaje_global3_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario][$fecha_entrega] = $datos_movimiento->descuento_global3;
                } else {
                    $global3_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario][$fecha_entrega] += $datos_movimiento->valor_descuento_global3;
                }


                if (!isset($neto_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario][$fecha_entrega])){
                    $neto_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario][$fecha_entrega] = $datos_movimiento->neto_pagar;
                } else {
                    $neto_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario][$fecha_entrega] += $datos_movimiento->neto_pagar;
                }

                if ($datos_movimiento->observaciones != ""){
                    if (!isset($observaciones_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario][$fecha_entrega])){
                        $observaciones_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario][$fecha_entrega] = $datos_movimiento->observaciones;
                    }// else {
                        //$observaciones_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario][$fecha_entrega] .= ", ".$datos_movimiento->observaciones;
                    //}
                }

                if (!isset($total_unidades_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario][$fecha_entrega])){
                    $total_unidades_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario][$fecha_entrega] = $datos_movimiento->cantidad;
                } else {
                    $total_unidades_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario][$fecha_entrega] += $datos_movimiento->cantidad;
                }

                if (!isset($total_concepto_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario][$fecha_entrega][$id_concepto_criterio_subnivel_articulo])){
                    $total_concepto_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario][$fecha_entrega][$id_concepto_criterio_subnivel_articulo] = $datos_movimiento->cantidad;
                } else {
                    $total_concepto_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario][$fecha_entrega][$id_concepto_criterio_subnivel_articulo] += $datos_movimiento->cantidad;
                }
                $tamano_total_concepto_articulo = strlen(number_format($total_concepto_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario][$fecha_entrega][$id_concepto_criterio_subnivel_articulo],$decimales_cantidad,".",","));
                if ($tamano_total_concepto_articulo > $ancho_concepto_mayor){
                    $ancho_concepto_mayor = $tamano_total_concepto_articulo;
                    $descripcion_concepto_mayor = number_format($total_concepto_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario][$fecha_entrega][$id_concepto_criterio_subnivel_articulo],$decimales_cantidad,".",",");
                }
                if (!isset($total_color_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario][$fecha_entrega][$id_color])){
                    $total_color_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario][$fecha_entrega][$id_color] = $datos_movimiento->cantidad;
                } else {
                    $total_color_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario][$fecha_entrega][$id_color] += $datos_movimiento->cantidad;
                }
                $tamano_cantidad_articulo = strlen(number_format($cantidad_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario][$fecha_entrega][$id_color][$id_concepto_criterio_subnivel_articulo],$decimales_cantidad,".",","));
                if ($tamano_cantidad_articulo > $ancho_concepto_mayor){
                    $ancho_concepto_mayor = $tamano_cantidad_articulo;
                    $descripcion_concepto_mayor = number_format($cantidad_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario][$fecha_entrega][$id_color][$id_concepto_criterio_subnivel_articulo],$decimales_cantidad,".",",");
                }
                if ($descripcion_concepto_mayor == ""){
                    $descripcion_concepto_mayor = number_format($total_color_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario][$fecha_entrega][$id_color],$decimales_cantidad,".",",");
                }
            }
            $x_detalle = 5;
            $y_detalle = 40;
            $x_color = 36;
            $y_color = 48;
            $y_inicial = 48;
            $x_concepto = 0;
            $y_concepto = 44;
            $posicion_cantidad = array();
            if (isset($cantidad_articulo)){
                foreach($cantidad_articulo AS $id_articulo => $arreglo_sucursal){
                    foreach($arreglo_sucursal AS $id_sucursal_destino => $arreglo_referencia){
                        foreach($arreglo_referencia AS $referencia => $arreglo_unidad){
                            foreach($arreglo_unidad AS $unidad_pedido => $arreglo_precio){
                                foreach($arreglo_precio AS $valor_unitario_articulo => $arreglo_fecha){
                                    foreach($arreglo_fecha AS $fecha_entrega_articulo => $arreglo_color){

                                        $ancho_inicial = $archivo->GetStringWidth($descripcion_color_mayor) + 2.5;
                                        $ancho_celdas = $archivo->GetStringWidth($descripcion_concepto_mayor) + 2;
                                        $ancho_inicial = $archivo->GetStringWidth($descripcion_color_mayor);
                                        $ancho_celdas = $archivo->GetStringWidth($descripcion_concepto_mayor) + 2;
                                        if ($ancho_celdas > $ancho_total_concepto_mayor){
                                            $ancho_total_concepto_mayor = $ancho_celdas;
                                        } else {
                                            $ancho_celdas = $ancho_total_concepto_mayor;
                                        }
                                        $ancho_colores = false;
                                        $cantidad_colores = 0;
                                        $cantidad_conceptos = 0;
                                        $x_ultimo_concepto = 0;
                                        $primera_vez = true;
                                        $posicion_color = array();
                                        $posicion_concepto = array();
                                        $posicion_cantidad = array();
                                        $salto_pagina = false;
                                        $total_colores = false;
                                        $total_conceptos = false;

                                        $archivo->SetY($archivo->GetY() + 4);
                                        $altura_detalle = 0;
                                        if (isset($foto_articulo[$id_articulo][$referencia])){
                                            if (isset($observaciones_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario_articulo][$fecha_entrega_articulo])){
                                                $dato_detalle = $observaciones_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario_articulo][$fecha_entrega_articulo];
                                                $ancho_detalle = 67;
                                            } else {
                                                $dato_detalle = $detalle_articulo[$id_articulo];
                                                $ancho_detalle = 134;
                                            }
                                            $x_color = 41;

                                        } else {
                                            if (isset($observaciones_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario_articulo][$fecha_entrega_articulo])){
                                                $dato_detalle = $observaciones_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario_articulo][$fecha_entrega_articulo];
                                                $ancho_detalle = 85;
                                            } else {
                                                $dato_detalle = $detalle_articulo[$id_articulo];
                                                $ancho_detalle = 170;
                                            }
                                            $x_color = 5;
                                        }

                                        if ($archivo->breakCellRow(array($dato_detalle),4,$ancho_detalle)){
                                            $salto_pagina = true;
                                        } else {
                                            $archivo->SetY($archivo->GetY() - 4);
                                        }

                                        if ($salto_pagina){
                                            encabezadoOrden($archivo,$sem,$datos_encabezado,$textos,$logo_cliente,$borrador,$numero_pedido);
                                            $x_detalle = 5;
                                            $y_detalle = 40;
                                        }
                                        $y_respuesta_detalle = detalleOrden($archivo,$textos,$unidad_pedido,$id_sucursal_destino,$referencia,$detalle_articulo,$foto_articulo,$observaciones_articulo,$id_articulo,$valor_unitario_articulo,$fecha_entrega_articulo,$x_detalle,$y_detalle);
                                        $y_detalle = $y_respuesta_detalle[0];
                                        $y_foto = $y_respuesta_detalle[1];
                                        $y_color = $y_detalle + 4;
                                        $y_concepto = $y_detalle;
                                        $y_inicial = $y_detalle;

                                        foreach($arreglo_color AS $color_articulo => $arreglo_concepto){

                                            $imprimio_color = false;
                                            $cantidad_colores++;

                                            foreach($arreglo_concepto AS $concepto_pedido => $cantidad){

                                                if ($archivo->breakCell(12) && !$salto_pagina){
                                                    $salto_pagina = true;
                                                    $imprimio_color = false;
                                                    $cantidad_colores = 1;
                                                    $salto_pagina = true;

                                                    $total_colores = false;
                                                    $total_conceptos = false;

                                                    $posicion_cantidad = array();
                                                    $cantidad_conceptos = 0;
                                                    $x_ultimo_concepto = 0;
                                                    $primera_vez = true;
                                                    encabezadoOrden($archivo,$sem,$datos_encabezado,$textos,$logo_cliente,$borrador,$numero_pedido);
                                                    $x_detalle = 5;
                                                    $y_detalle = 40;
                                                    $y_respuesta_detalle = detalleOrden($archivo,$textos,$unidad_pedido,$id_sucursal_destino,$referencia,$detalle_articulo,$foto_articulo,$observaciones_articulo,$id_articulo,$valor_unitario_articulo,$fecha_entrega_articulo,$x_detalle,$y_detalle);
                                                    $y_detalle = $y_respuesta_detalle[0];
                                                    $y_foto = $y_respuesta_detalle[1];
                                                    $y_color = $y_detalle + 4;
                                                    $y_concepto = $y_detalle;
                                                    $y_inicial = $y_detalle;
                                                }

                                                if ($cantidad_colores == 1 && !$ancho_colores){
                                                    $precio_unitario_ancho = $precio_unitario_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario_articulo][$fecha_entrega_articulo];
                                                    $ano_ancho = substr($fecha_entrega_articulo,0,4);
                                                    $mes_ancho = substr($fecha_entrega_articulo,4,2);
                                                    $dia_ancho = substr($fecha_entrega_articulo,6,2);
                                                    $fecha_ancho = $ano_ancho."-".$mes_ancho."-".$dia_ancho;

                                                    $condicion_ancho = "id_orden_compra='$id_orden_compra'";
                                                    $condicion_ancho .= " AND id_articulo='$id_articulo'";
                                                    $condicion_ancho .= " AND id_sucursal_destino='$id_sucursal_destino' AND referencia='$referencia'";
                                                    $condicion_ancho .= " AND id_unidad_medida='$unidad_pedido' AND valor_unitario='$precio_unitario_ancho'";
                                                    $condicion_ancho .= " AND fecha_entrega='$fecha_ancho' AND color='$color_articulo'";
                                                    $cantidad_colores_concepto = SQL::obtenerValor("movimiento_ordenes_compra",("COUNT(id)"),$condicion_ancho);
                                                    $ancho_pagina_concepto = $archivo->GetStringWidth($descripcion_concepto_mayor) * $cantidad_colores_concepto;
                                                    if (($x_color + $ancho_pagina_concepto) > 150){
                                                        $ancho_colores = true;
                                                        if (isset($foto_articulo[$id_articulo][$referencia])){
                                                            $x_color = 5;
                                                            $y_color = $y_foto + 42;
                                                            $x_inicial = 5;
                                                            $y_inicial = $y_foto + 38;
                                                            $y_detalle = $y_foto + 38;
                                                            $x_concepto = 5;
                                                            $y_concepto = $y_foto + 38;
                                                            $x_celdas = 5;
                                                            $y_celdas = $y_foto + 42;
                                                        }
                                                    }
                                                }

                                                if ($salto_pagina){
                                                    if ($concepto_pedido <= 0){
                                                        $y_color -= 4;
                                                    } else {
                                                        unset($posicion_cantidad);
                                                    }
                                                    if ($color_articulo == ""){
                                                        $ancho_celda = 0;
                                                        $ancho_inicial = 0;
                                                    }
                                                    $salto_pagina = false;
                                                    $cantidad_colores=1;
                                                    $cantidad_conceptos=0;
                                                } else {
                                                    if ($concepto_pedido <= 0 && $primera_vez){
                                                        $y_color -= 4;
                                                    }
                                                }
                                                if (!$imprimio_color){
                                                    if ($concepto_pedido <= 0 && $primera_vez){
                                                        $primera_vez = false;
                                                    }
                                                    if ($color_articulo != ""){
                                                        $total_colores = true;
                                                        $ancho_inicial = $archivo->GetStringWidth($descripcion_color_mayor) + 2.5;
                                                        $archivo->SetXY($x_color,$y_color);
                                                        $archivo->cell($ancho_inicial, 4, $color[$color_articulo], 1, 0, "L");
                                                    } else {
                                                        $ancho_inicial = 0;
                                                    }
                                                    $x_concepto = $x_color + $ancho_inicial;
                                                    $imprimio_color = true;
                                                }
                                                $x_cantidad = $x_concepto;
                                                $y_cantidad = $y_color;
                                                if($x_concepto > $x_ultimo_concepto){
                                                    $x_ultimo_concepto = $x_concepto;
                                                }
                                                $ancho_celda = $archivo->GetStringWidth($descripcion_concepto_mayor) + 2;
                                                $ancho_celda = $archivo->GetStringWidth($descripcion_concepto_mayor);
                                                if ($concepto_pedido > 0){
                                                    $total_conceptos = true;
                                                    if (!isset($posicion_cantidad[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario_articulo][$fecha_entrega_articulo][$concepto_pedido])){
                                                        $posicion_cantidad[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario_articulo][$fecha_entrega_articulo][$concepto_pedido] = $x_ultimo_concepto."|".$y_color;
                                                        $archivo->SetXY($x_ultimo_concepto,$y_concepto);
                                                        $archivo->cell($ancho_celdas, 4, $concepto[$concepto_pedido], 1, 0, "C");
                                                        $x_cantidad = $x_ultimo_concepto;
                                                        $x_concepto+=$ancho_celdas;
                                                        $x_ultimo_concepto+=$ancho_celdas;
                                                        $cantidad_conceptos++;
                                                    } else {
                                                        $valores = explode("|",$posicion_cantidad[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario_articulo][$fecha_entrega_articulo][$concepto_pedido]);
                                                        $x_cantidad = $valores[0];
                                                    }
                                                } else {
                                                    $cantidad_conceptos = 1;
                                                }
                                                if (!$total_colores && !$total_conceptos){
                                                    $archivo->SetXY($x_cantidad,$y_cantidad);
                                                    $ancho_celda1 = $archivo->GetStringWidth($textos["CANTIDAD_PDF"]." ".$descripcion_unidad_pedido[$unidad_pedido]) + 2;
                                                    $ancho_celda2 = $archivo->GetStringWidth(number_format($cantidad,$decimales_cantidad,".",",")) + 2;
                                                    if ($ancho_celda1 > $ancho_celda2){
                                                        $ancho_celda_unidad = $ancho_celda1;
                                                    } else {
                                                        $ancho_celda_unidad = $ancho_celda2;
                                                    }
                                                    $archivo->SetFont('Arial','B',8);
                                                    $archivo->cell($ancho_celda_unidad, 4, $textos["CANTIDAD_PDF"]." ".$descripcion_unidad_pedido[$unidad_pedido], 0, 0, "R");
                                                    $y_inicial = $y_cantidad;
                                                    $y_cantidad+=4;
                                                    $archivo->SetFont('Arial','',8);
                                                    $archivo->SetXY($x_cantidad,$y_cantidad);
                                                    $archivo->cell($ancho_celda_unidad, 4, number_format($cantidad,$decimales_cantidad,".",","), 0, 0, "R");
                                                } else {
                                                    $archivo->SetFont('Arial','',8);
                                                    $archivo->SetXY($x_cantidad,$y_cantidad);
                                                    $archivo->cell($ancho_celdas, 4, number_format($cantidad,$decimales_cantidad,".",","), 0, 0, "R");
                                                }
                                                $posicion_color[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario_articulo][$fecha_entrega_articulo][$color_articulo] = $y_cantidad;
                                                $posicion_concepto[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario_articulo][$fecha_entrega_articulo][$concepto_pedido] = $x_cantidad;
                                            }
                                            $y_color+=4;
                                        }

                                        if (!$total_colores && !$total_conceptos){
                                            $ancho_celdas_conceptos = $ancho_celda_unidad;
                                            $y_celdas = $y_inicial + 4;
                                        } else {
                                            $ancho_celdas_conceptos = $ancho_celdas;
                                            if ($total_conceptos){
                                                $y_celdas = $y_inicial + 4;
                                            } else {
                                                $y_celdas = $y_inicial;
                                            }
                                        }
                                        $x_celdas = $x_color + $ancho_inicial;
                                        //$y_celdas = $y_inicial + 4;
                                        $x_primera_celda = $x_celdas;
                                        $y_primera_celda = $y_celdas;
                                        $x_ultima_celda = $x_celdas;
                                        $y_ultima_celda = $y_celdas + 4;
                                        for($ccolor=1;$ccolor<=$cantidad_colores;$ccolor++){
                                            for($cconcepto=1;$cconcepto<=$cantidad_conceptos;$cconcepto++){
                                                $archivo->SetXY($x_celdas,$y_celdas);
                                                $archivo->cell($ancho_celdas_conceptos,4,"",1);
                                                $x_celdas+=$ancho_celdas;
                                                if ($x_celdas > $x_ultima_celda){
                                                    $x_ultima_celda = $x_celdas;
                                                }
                                            }
                                            if ($y_celdas > $y_ultima_celda){
                                                $y_ultima_celda = $y_celdas;
                                            }
                                            $y_celdas+=4;
                                            $x_celdas = $x_color + $ancho_inicial;
                                            if ($x_celdas > $x_ultima_celda){
                                                $x_ultima_celda = $x_celdas;
                                            }
                                            if ($y_celdas > $y_ultima_celda){
                                                $y_ultima_celda = $y_celdas;
                                            }
                                        }
                                        /*$ancho_celdas_color = $archivo->GetStringWidth($descripcion_color_mayor) + 2.5;
                                        $ancho_celdas_concepto = $archivo->GetStringWidth($descripcion_concepto_mayor) + 2;*/
                                        if ($total_colores && $total_conceptos){
                                            $archivo->SetXY($x_ultima_celda,$y_ultima_celda);
                                            $archivo->SetFont('Arial','B',8);
                                            $archivo->cell($ancho_celdas,4,number_format($total_unidades_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario_articulo][$fecha_entrega_articulo],$decimales_cantidad,".",","),1,0,"R");
                                        }
                                        $x_actual = $archivo->GetX();
                                        $y_actual = $archivo->GetY();

                                        if ($total_colores || $total_conceptos){
                                            if (isset($foto_articulo[$id_articulo][$referencia])){
                                                $x_fecha = 36;
                                            } else {
                                                $x_fecha = 5;
                                            }
                                            $ancho_celda_fecha = 0;
                                            if ($total_colores){
                                                //$ancho_celda_fecha += $ancho_celdas_color;
                                                $ancho_celda_fecha += $ancho_inicial;
                                            }
                                            if ($total_conceptos){
                                                //$ancho_celda_fecha += ($ancho_celdas_concepto * ($cantidad_conceptos + 1));
                                                $ancho_celda_fecha += ($ancho_celdas * ($cantidad_conceptos + 1));
                                            } else {
                                                //$ancho_celda_fecha += $ancho_celdas_concepto;
                                                $ancho_celda_fecha += $ancho_celdas;
                                            }
                                            $archivo->SetXY($x_fecha, $y_detalle - 4);
                                            $archivo->SetFont('Arial','B',8);
                                            $archivo->cell($ancho_celda_fecha,4,$textos["CANTIDAD_PDF"]." ".$descripcion_unidad_pedido[$unidad_pedido],0,0,"C");
                                        }

                                        if ($total_colores){
                                            $archivo->SetXY($x_primera_celda - $ancho_inicial,$y_ultima_celda);
                                            $archivo->SetFont('Arial','B',8);
                                            $archivo->cell($ancho_inicial, 4, $textos["TOTAL"], 1, 0, "C");
                                            $x_posicion_concepto = $x_primera_celda - $ancho_inicial;
                                            $y_posicion_concepto = $y_ultima_celda;
                                            $archivo->SetFont('Arial','',8);
                                            foreach($posicion_concepto AS $id_articulo_posicion_concepto  => $arreglo_sucursal_posicion_concepto){
                                                foreach($arreglo_sucursal_posicion_concepto AS $id_sucursal_destino_posicion_concepto  => $arreglo_referencia_posicion_concepto){
                                                    foreach($arreglo_referencia_posicion_concepto AS $referencia_posicion_concepto  => $arreglo_unidad_pedido_posicion_concepto){
                                                        foreach($arreglo_unidad_pedido_posicion_concepto AS $unidad_pedido_posicion_concepto => $arreglo_unitario_posicion_concepto){
                                                            foreach($arreglo_unitario_posicion_concepto AS $unitario_posicion_concepto => $arreglo_fecha_posicion_concepto){
                                                                foreach($arreglo_fecha_posicion_concepto AS $fecha_posicion_concepto => $arreglo_color_posicion_concepto){
                                                                    foreach($arreglo_color_posicion_concepto AS $id_color_posicion_concepto => $x_posicion_concepto){
                                                                        $total_unidades_concepto = number_format($total_concepto_articulo[$id_articulo_posicion_concepto][$id_sucursal_destino_posicion_concepto][$referencia_posicion_concepto][$unidad_pedido_posicion_concepto][$unitario_posicion_concepto][$fecha_posicion_concepto][$id_color_posicion_concepto],$decimales_cantidad,".",",");
                                                                        $archivo->SetXY($x_posicion_concepto,$y_posicion_concepto);
                                                                        $archivo->cell($ancho_celdas, 4, $total_unidades_concepto, 1, 0, "R");
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                        if ($total_conceptos){
                                            $archivo->SetXY($x_ultima_celda,$y_primera_celda - 4);
                                            $archivo->SetFont('Arial','B',8);
                                            $archivo->cell($ancho_celdas, 4, $textos["TOTAL"], 1, 0, "C");
                                            $archivo->SetXY($x_actual,$y_actual);
                                            $x_posicion_color = $x_ultima_celda;
                                            $y_posicion_color = $y_ultima_celda;
                                            $archivo->SetFont('Arial','',8);
                                            foreach($posicion_color AS $id_articulo_posicion_color => $arreglo_sucursal_posicion_color){
                                                foreach($arreglo_sucursal_posicion_color AS $id_sucursal_destino_posicion_color => $arreglo_referencia_posicion_color){
                                                    foreach($arreglo_referencia_posicion_color AS $referencia_posicion_color => $arreglo_unidad_pedido_posicion_color){
                                                        foreach($arreglo_unidad_pedido_posicion_color AS $unidad_pedido_posicion_color => $arreglo_unitario_posicion_color){
                                                            foreach($arreglo_unitario_posicion_color AS $unitario_posicion_color => $arreglo_fecha_posicion_color){
                                                                foreach($arreglo_fecha_posicion_color AS $fecha_posicion_color => $arreglo_color_posicion_color){
                                                                    foreach($arreglo_color_posicion_color AS $id_color_posicion_color => $y_posicion_color){
                                                                        $total_unidades_color = number_format($total_color_articulo[$id_articulo_posicion_color][$id_sucursal_destino_posicion_color][$referencia_posicion_color][$unidad_pedido_posicion_color][$unitario_posicion_color][$fecha_posicion_color][$id_color_posicion_color],$decimales_cantidad,".",",");
                                                                        $archivo->SetXY($x_posicion_color,$y_posicion_color);
                                                                        $archivo->cell($ancho_celdas, 4, $total_unidades_color, 1, 0, "R");
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }

                                        $y_totales_articulo = $y_detalle;
                                        $x_totales_articulo = $x_ultima_celda + $ancho_celdas + 2;
                                        if (!$total_conceptos){
                                            if (!$total_colores){
                                                $x_totales_articulo = $x_cantidad + $ancho_celda_unidad + 2;
                                            } else {
                                                $x_totales_articulo -= $ancho_celdas;
                                            }
                                        }
                                        //echo var_dump($x_totales_articulo);
                                        if ($x_totales_articulo > 150){
                                            $x_totales_articulo = 5;
                                            if (isset($foto_articulo[$id_articulo][$referencia])){
                                                if ($y_ultima_celda < ($y_foto + 38)){
                                                    $y_totales_articulo = $y_foto + 42;
                                                } else {
                                                    $y_totales_articulo = $y_ultima_celda + 4;
                                                }
                                            } else {
                                                $y_totales_articulo = $y_ultima_celda + 4;
                                            }
                                        }

                                        if ($ancho_colores){
                                            $y_totales_articulo += 4;
                                        }

                                        $archivo->SetXY($x_totales_articulo,$y_totales_articulo);
                                        $archivo->SetFont('Arial','B',8);
                                        $archivo->cell(25,4,$textos["CODIGO_INTERNO"],1,0,"L");
                                        $archivo->SetFont('Arial','',8);
                                        $archivo->cell(27,4,$codigo_articulo[$id_articulo],1,0,"R");
                                        $y_totales_articulo+=4;
                                        $archivo->SetXY($x_totales_articulo,$y_totales_articulo);
                                        $archivo->SetFont('Arial','B',8);
                                        $archivo->cell(25,4,$textos["SUCURSAL_DESTINO"],1,0,"L");
                                        $archivo->SetFont('Arial','',8);
                                        $archivo->cell(27,4,$sucursal_destino[$id_sucursal_destino],1,0,"L");
                                        $y_totales_articulo+=4;
                                        $archivo->SetXY($x_totales_articulo,$y_totales_articulo);
                                        $archivo->SetFont('Arial','B',8);
                                        $archivo->cell(25,4,$textos["PRECIO_ARTICULO"],1,0,"L");
                                        $archivo->SetFont('Arial','',8);
                                        $archivo->cell(27,4,number_format($precio_unitario_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario_articulo][$fecha_entrega_articulo],$decimales_valores,".",","),1,0,"R");
                                        $y_totales_articulo+=4;
                                        $archivo->SetXY($x_totales_articulo,$y_totales_articulo);
                                        $archivo->SetFont('Arial','B',8);
                                        $archivo->cell(25,4,$textos["CANTIDAD_PDF"]." ".$descripcion_unidad_pedido[$unidad_pedido],1,0,"L");
                                        $archivo->SetFont('Arial','',8);
                                        $archivo->cell(27,4,number_format($total_unidades_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario_articulo][$fecha_entrega_articulo],$decimales_cantidad,".",","),1,0,"R");
                                        $y_totales_articulo+=4;
                                        $archivo->SetXY($x_totales_articulo,$y_totales_articulo);
                                        $archivo->SetFont('Arial','B',8);
                                        $archivo->cell(25,4,$textos["SUBTOTAL_ARTICULO"],1,0,"L");
                                        $archivo->SetFont('Arial','',8);
                                        $archivo->cell(27,4,number_format($total_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario_articulo][$fecha_entrega_articulo],$decimales_valores,".",","),1,0,"R");
                                        if ($linea_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario_articulo][$fecha_entrega_articulo] > 0){
                                            $y_totales_articulo+=4;;
                                            $archivo->SetXY($x_totales_articulo,$y_totales_articulo);
                                            $valor_descuento = number_format($linea_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario_articulo][$fecha_entrega_articulo],$decimales_valores,".",",");
                                            $decimales = explode(".",$porcentaje_linea_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario_articulo][$fecha_entrega_articulo]);
                                            if (isset($decimales[1])){
                                                if ($decimales[1] > 0){
                                                    $decimales_linea = 2;
                                                } else {
                                                    $decimales_linea = $decimales_valores;
                                                }
                                            }
                                            $porcentaje_descuento = number_format($porcentaje_linea_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario_articulo][$fecha_entrega_articulo],$decimales_linea,".",",")."%";
                                            $archivo->SetFont('Arial','B',8);
                                            $archivo->cell(25,4,$textos["LINEA_ARTICULO"],1,0,"L");
                                            $archivo->SetFont('Arial','',8);
                                            $archivo->cell(27,4,$porcentaje_descuento,1,0,"R");
                                        }
                                        $y_totales_articulo+=4;;
                                        $archivo->SetXY($x_totales_articulo,$y_totales_articulo);
                                        $archivo->SetFont('Arial','B',8);
                                        $archivo->cell(25,4,$textos["FECHA_ENTREGA_ARTICULO"],1,0,"L");
                                        $archivo->SetFont('Arial','',8);
                                        $ano_entrega = substr($fecha_entrega_articulo,0,4);
                                        $mes_entrega = substr($fecha_entrega_articulo,4,2);
                                        $dia_entrega = substr($fecha_entrega_articulo,6,2);
                                        $archivo->cell(27,4,$ano_entrega."-".$mes_entrega."-".$dia_entrega,1,0,"R");


                                        if ($y_totales_articulo > $y_ultima_celda){
                                            $y_ultima_celda = $y_totales_articulo;
                                        }

                                        if (isset($foto_articulo[$id_articulo][$referencia])){
                                            if (($y_foto + 38) > $y_ultima_celda){
                                                $y_detalle = $y_foto + 42;
                                            } else {
                                                $y_detalle = $y_ultima_celda + 4;
                                            }
                                        } else {
                                            $y_detalle = $y_ultima_celda + 4;
                                        }
                                        $archivo->SetY($y_detalle);
                                    }
                                }
                            }
                        }
                    }
                }
            }

            if ($archivo->breakCell(45)){
                encabezadoOrden($archivo,$sem,$datos_encabezado,$textos,$logo_cliente,$borrador,$numero_pedido);
                $archivo->Ln(8);
                $y_detalle = 40;
            }
            $archivo->SetY($y_detalle);
            $y_linea = $archivo->GetY() + 4;
            $archivo->SetLineWidth(0.2);
            $archivo->Line(5,$y_linea,205,$y_linea);
            $y_linea+=4;
            $archivo->SetXY(5,$y_linea);

            /*$archivo->SetFont('Arial','B',8);
            $archivo->cell(60,4,$textos["TOTALES"],1,0,"C");
            $archivo->Ln(4);*/

            $archivo->SetX(5);
            $unidades = number_format($unidades_encabezado,$decimales_cantidad,".",",");
            $archivo->SetFont('Arial','B',8);
            $archivo->cell(30,4,$textos["UNIDADES"],1,0,"L");
            $archivo->SetFont('Arial','',8);
            $archivo->cell(30,4,$unidades,1,0,"R");
            $archivo->Ln(4);

            $subtotal = number_format($subtotal_encabezado,$decimales_valores,".",",");
            $archivo->SetX(5);
            $archivo->SetFont('Arial','B',8);
            $archivo->cell(30,4,$textos["SUBTOTAL"],1,0,"L");
            $archivo->SetFont('Arial','',8);
            $archivo->cell(30,4,$subtotal,1,0,"R");
            $archivo->Ln(4);

            if ($archivo->breakCell(45)){
                encabezadoOrden($archivo,$sem,$datos_encabezado,$textos,$logo_cliente,$borrador,$numero_pedido);
                $archivo->Ln(8);
                $y_detalle = 40;
            }
            if ($porcentaje_global1_encabezado > 0 && $descuento_global1_iva_incluido == "0"){
                $archivo->SetX(5);
                $decimales = explode(".",$porcentaje_global1_encabezado);
                if (isset($decimales[1])){
                    if ($decimales[1] > 0){
                        $decimales_global = 2;
                    } else {
                        $decimales_global = $decimales_valores;
                    }
                }
                $global1 = number_format($porcentaje_global1_encabezado,$decimales_global,".",",");
                $archivo->SetFont('Arial','B',8);
                $archivo->cell(30,4,$textos["DESCUENTO_GLOBAL1"],1,0,"L");
                $archivo->SetFont('Arial','',8);
                $archivo->cell(30,4,$global1."%",1,0,"R");
                $archivo->Ln(4);
            }
            if ($porcentaje_global2_encabezado > 0){
                $archivo->SetX(5);
                $decimales = explode(".",$porcentaje_global2_encabezado);
                if (isset($decimales[1])){
                    if ($decimales[1] > 0){
                        $decimales_global = 2;
                    } else {
                        $decimales_global = $decimales_valores;
                    }
                }
                $global2 = number_format($porcentaje_global2_encabezado,$decimales_global,".",",");
                $archivo->SetFont('Arial','B',8);
                if ($descuento_global1_iva_incluido == "0" && $porcentaje_global1_encabezado > 0){
                    $archivo->cell(30,4,$textos["DESCUENTO_GLOBAL2"],1,0,"L");
                } else {
                    $archivo->cell(30,4,$textos["DESCUENTO_GLOBAL1"],1,0,"L");
                }
                $archivo->SetFont('Arial','',8);
                $archivo->cell(30,4,$global2."%",1,0,"R");
                $archivo->Ln(4);
            }
            if ($porcentaje_global3_encabezado > 0){
                $archivo->SetX(5);
                $decimales = explode(".",$porcentaje_global3_encabezado);
                if (isset($decimales[1])){
                    if ($decimales[1] > 0){
                        $decimales_global = 2;
                    } else {
                        $decimales_global = $decimales_valores;
                    }
                }
                $global3 = number_format($porcentaje_global3_encabezado,$decimales_global,".",",");
                $archivo->SetFont('Arial','B',8);
                if ($descuento_global1_iva_incluido == "0" && $porcentaje_global1_encabezado > 0 && $porcentaje_global2_encabezado > 0){
                    $archivo->cell(30,4,$textos["DESCUENTO_GLOBAL3"],1,0,"L");
                } else {
                    if ($descuento_global1_iva_incluido == "0" && $porcentaje_global1_encabezado > 0 && $porcentaje_global2_encabezado <= 0){
                        $archivo->cell(30,4,$textos["DESCUENTO_GLOBAL2"],1,0,"L");
                    } else if ($descuento_global1_iva_incluido == "1" && $porcentaje_global2_encabezado <= 0){
                        $archivo->cell(30,4,$textos["DESCUENTO_GLOBAL1"],1,0,"L");
                    } else if ($descuento_global1_iva_incluido == "1" && $porcentaje_global2_encabezado > 0){
                        $archivo->cell(30,4,$textos["DESCUENTO_GLOBAL2"],1,0,"L");
                    } else if ($porcentaje_global1_encabezado > 0 && $porcentaje_global2_encabezado <= 0){
                        $archivo->cell(30,4,$textos["DESCUENTO_GLOBAL2"],1,0,"L");
                    } else {
                        $archivo->cell(30,4,$textos["DESCUENTO_GLOBAL1"],1,0,"L");
                    }
                }
                $archivo->SetFont('Arial','',8);
                $archivo->cell(30,4,$global3."%",1,0,"R");
                $archivo->Ln(4);
            }
            /*if ($linea_encabezado > 0){
                $archivo->SetX(5);
                $linea = number_format($linea_encabezado,$decimales_valores,".",",");
                $archivo->SetFont('Arial','B',8);
                $archivo->cell(30,4,$textos["DESCUENTO_LINEA"],1,0,"L");
                $archivo->SetFont('Arial','',8);
                $archivo->cell(30,4,$linea,1,0,"R");
                $archivo->Ln(4);
            }*/
            $archivo->Ln(8);

            if ($archivo->breakCell(45)){
                encabezadoOrden($archivo,$sem,$datos_encabezado,$textos,$logo_cliente,$borrador,$numero_pedido);
                $archivo->Ln(8);
                $y_detalle = 40;
            }
            if ($observaciones_encabezado != ""){
                $archivo->SetY($archivo->GetY() + 4);
                if ($archivo->breakCellRow(array($observaciones_encabezado),4,200)){
                    encabezadoOrden($archivo,$sem,$datos_encabezado,$textos,$logo_cliente,$borrador,$numero_pedido);
                    $archivo->SetY(40);
                } else {
                    $archivo->SetY($archivo->GetY() - 4);
                }
                $archivo->SetFont('Arial','B',8);
                $archivo->SetX(5);
                $archivo->cell(200,4,$textos["OBSERVACIONES"],1,0,"L");
                $archivo->Ln(4);
                $archivo->SetFont('Arial','',8);
                $texto_columna = array($observaciones_encabezado);
                $archivo->SetAligns(array("L"));
                $archivo->SetWidths(array(200));
                $archivo->SetX(5);
                $archivo->Row($texto_columna,false,"","",4);
                $archivo->Ln(8);
            }
            if ($archivo->breakCell(35)){
                encabezadoOrden($archivo,$sem,$datos_encabezado,$textos,$logo_cliente,$borrador,$numero_pedido);
                $archivo->Ln(8);
            }

            if ($archivo->breakCell(65)){
                encabezadoOrden($archivo,$sem,$datos_encabezado,$textos,$logo_cliente,$borrador,$numero_pedido);
                $archivo->Ln(8);
                $y_detalle = 40;
            }
            $archivo->SetX(5);
            $archivo->SetFont('Arial','B',10);
            $archivo->cell(100,5,$textos["CONDICIONES"],0,0,"L");
            $archivo->Ln(6);
            $archivo->SetFont('Arial','',10);
            $regimen_proveedor = SQL::obtenerValor("terceros","regimen","id='$datos_encabezado->id_proveedor'");
            if ($regimen_proveedor == "1" && $datos_encabezado->id_sucursal != 999){
                if ($datos_encabezado->iva_incluido == "1" || ($datos_encabezado->descuento_global1 > 0 && $descuento_global1_iva_incluido == "1")){
                    $archivo->SetX(5);
                    $archivo->cell(60,4,$textos["IVA_INCLUIDO"],0,0,"L");
                    $archivo->Ln(5);
                } else {
                    $archivo->SetX(5);
                    $archivo->cell(60,4,$textos["IVA_NO_INCULIDO"],0,0,"L");
                    $archivo->Ln(5);
                }
            }
            if ($descuento_financiero_fijo > 0){
                $archivo->SetX(5);
                $archivo->cell(42,5,$textos["DESCUENTO_FINANCIERO_FIJO"].": ",0,0,"L");
                $decimales = explode(".",$descuento_financiero_fijo);
                if (isset($decimales[1])){
                    if ($decimales[1] > 0){
                        $decimales_fijo = 2;
                    } else {
                        $decimales_fijo = $decimales_valores;
                    }
                }
                $archivo->cell(10,5,number_format($descuento_financiero_fijo,$decimales_fijo,".",","),0,0,"L");
                $archivo->Ln(5);
            }
            if ($descuento_financiero_pronto_pago > 0){
                $archivo->SetX(5);
                $archivo->cell(56,5,$textos["DESCUENTO_FINANCIERO_PRONTO"].": ",0,0,"L");
                $decimales = explode(".",$descuento_financiero_pronto_pago);
                if (isset($decimales[1])){
                    if ($decimales[1] > 0){
                        $decimales_pronto = 2;
                    } else {
                        $decimales_pronto = $decimales_valores;
                    }
                }
                $archivo->cell(10,5,number_format($descuento_financiero_pronto_pago,$decimales_pronto,".",","),0,0,"L");
                if ($numero_dias_pronto_pago > 0){
                    $archivo->cell(43,5,$textos["NUMERO_DIAS_PRONTO_PAGO"].": ",0,0,"L");
                    $archivo->cell(10,5,number_format($numero_dias_pronto_pago,0,".",","),0,0,"L");
                }
                $archivo->Ln(5);
                if ($descuento_financiero_pronto_pago2 > 0){
                    $archivo->SetX(5);
                    $archivo->cell(56,5,$textos["DESCUENTO_FINANCIERO_PRONTO2"].": ",0,0,"L");
                    $decimales2 = explode(".",$descuento_financiero_pronto_pago2);
                    if (isset($decimales2[1])){
                        if ($decimales2[1] > 0){
                            $decimales_pronto2 = 2;
                        } else {
                            $decimales_pronto2 = $decimales_valores2;
                        }
                    }
                    $archivo->cell(10,5,number_format($descuento_financiero_pronto_pago2,$decimales_pronto2,".",","),0,0,"L");
                    if ($numero_dias_pronto_pago2 > 0){
                        $archivo->cell(43,5,$textos["NUMERO_DIAS_PRONTO_PAGO2"].": ",0,0,"L");
                        $archivo->cell(10,5,number_format($numero_dias_pronto_pago2,0,".",","),0,0,"L");
                    }
                    $archivo->Ln(5);
                }
            }
            $archivo->SetX(5);
            $archivo->cell(50,5,$textos["NUMERO_DIAS_PAGO"].": ",0,0,"L");
            $archivo->cell(10,5,(int)$numero_dias_pago_encabezado,0,0,"L");
            $archivo->Ln(5);

            if ($numero_entregas_encabezado > 0){
                $archivo->SetX(5);
                $archivo->cell(50,5,$textos["NUMERO_ENTREGAS"].": ",0,0,"L");
                $archivo->cell(18,5,(int)$numero_entregas_encabezado,0,0,"L");
                $archivo->cell(34,5,$textos["FECHA_FINAL_ENTREGAS"].": ",0,0,"L");
                $archivo->cell(10,5,$fecha_final_entregas_encabezado,0,0,"L");
                $archivo->Ln(5);
            }

            if ($archivo->breakCell(30)){
                encabezadoOrden($archivo,$sem,$datos_encabezado,$textos,$logo_cliente,$borrador,$numero_pedido);
                $archivo->Ln(8);
            }

            if ($archivo->breakCell(65)){
                encabezadoOrden($archivo,$sem,$datos_encabezado,$textos,$logo_cliente,$borrador,$numero_pedido);
                $archivo->Ln(8);
                $y_detalle = 40;
            }
            $archivo->Ln(8);
            $archivo->SetX(5);
            $archivo->SetFont('Arial','BI',10);
            $archivo->cell(200,5,$textos["NOTA"],0,0,"L");
            $archivo->Ln(5);
            $archivo->SetX(5);
            $archivo->SetFont('Arial','I',10);
            $archivo->cell(200,5,$textos["NOTA1"],0,0);
            $archivo->Ln(5);
            $archivo->SetX(5);
            $archivo->cell(200,5,$textos["NOTA2"],0,0);
            $archivo->Ln(5);
            $archivo->SetX(5);
            $archivo->cell(200,5,$textos["NOTA3"],0,0);
            $archivo->Ln(5);
            $archivo->SetX(5);
            $archivo->cell(200,5,$textos["NOTA4"],0,0);
            $archivo->Ln(5);
            $archivo->SetX(5);
            $archivo->cell(200,5,$textos["NOTA5"],0,0);
            $archivo->Ln(5);
            $archivo->SetX(5);
            $archivo->cell(200,5,$textos["NOTA6"],0,0);

            $archivo->Ln(18);
            if ($archivo->breakCell(15)){
                encabezadoOrden($archivo,$sem,$datos_encabezado,$textos,$logo_cliente,$borrador,$numero_pedido);
                $archivo->Ln(8);
            }

            $archivo->SetFont('Arial','',10);
            $id_tercero = SQL::obtenerValor("compradores","id_tercero","id='$datos_encabezado->id_comprador'");
            $comprador =  SQL::obtenerValor("menu_terceros","NOMBRE_COMPLETO","id='$id_tercero'");
            $archivo->SetX(5);
            $archivo->cell(40,5,$textos["COMPRADOR"],0,0,"L");
            $archivo->Ln(5);
            $archivo->SetX(5);
            $archivo->SetFont('Arial','B',10);
            $archivo->cell(40,5,$comprador,0,0,"L");
        }

        $archivo->Output($nombreArchivo, "F");
        $ordenTemporal  = $rutasGlobales["temp"]."/".$nombre;
        $nombreArchivoGuardar = $rutasGlobales["ordenesCompra"]."/".$nombre;
        copy($nombreArchivo, $nombreArchivoGuardar);
        copy($nombreArchivo, $ordenTemporal);
        //return $ordenTemporal;

        $ruta_archivo = HTTP::generarURL("DESCARCH")."&temporal=1&nombre_archivo=".$nombre;
        return($ruta_archivo);
    }
    function encabezadoOrden($archivo,$sem,$datos_encabezado,$textos,$logo_cliente,$borrador,$numero_pedido){
        $archivo->AddPage("","",false,true);
        if ($borrador != ""){
            $archivo->Image($borrador, 8, 1, 200,300);
        }
        $tercero_sucursal = SQL::obtenerValor("sucursales","id_tercero","id='$datos_encabezado->id_sucursal'");
        if ($tercero_sucursal > 0){
            $consulta_tercero_sucursal = SQL::seleccionar(array("terceros"),array("*"),"id='$tercero_sucursal'");
            $datos_tercero_sucursal = SQL::filaEnObjeto($consulta_tercero_sucursal);
            $nombre_tercero_sucursal = SQL::obtenerValor("menu_terceros","NOMBRE_COMPLETO","id='$datos_tercero_sucursal->id'");
            $tipo_documento_identidad = SQL::obtenerValor("tipos_documento_identidad","descripcion","id='$datos_tercero_sucursal->id_tipo_documento_identidad'");
            $regimen = array(
                "1" => $textos["COMUN"],
                "2" => $textos["SIMPLIFICADO"]
            );
            /*$archivo->Image($logo_cliente, 8, 10, 35);
            $archivo->SetFont('Arial','B',10);
            $archivo->Ln(8);*/
            $archivo->SetFont('Arial','B',10);
            $archivo->SetXY(5,10);
            $archivo->cell(100, 4, $nombre_tercero_sucursal, 0, 0, "L");
            $archivo->cell(70, 4, $textos["DOCUOCPR"].": ", 0, 0, "R");
            $archivo->cell(30, 4, (int)$datos_encabezado->numero_consecutivo, 0, 0, "L");
            $archivo->Ln(4);
            $archivo->SetX(5);
            $archivo->cell(100, 4, $tipo_documento_identidad.": ".$datos_tercero_sucursal->documento_identidad, 0, 0, "L");
            $archivo->cell(70, 4, $textos["FECHA"].": ", 0, 0, "R");
            $archivo->cell(30, 4, $datos_encabezado->fecha_documento, 0, 0, "L");
            $archivo->Ln(4);
            $archivo->SetX(5);
            $archivo->cell(100, 4, $textos["REGIMEN_EMPRESA"].": ".$regimen[$datos_tercero_sucursal->regimen], 0 ,0, "L");
            $archivo->cell(70, 4, $textos["PEDIDO"].": ", 0, 0, "R");
            $archivo->cell(30, 4, $numero_pedido, 0, 0, "L");

            if ($datos_tercero_sucursal->direccion_principal != "" && $datos_tercero_sucursal->telefono_principal != ""){
                $ubicacion = $textos["DIRECCION"].": ".$datos_tercero_sucursal->direccion_principal;
                $ubicacion .= " ".$textos["TELEFONO"].": ".$datos_tercero_sucursal->telefono_principal;
            } else if($datos_tercero_sucursal->direccion_principal != ""){
                $ubicacion = $textos["DIRECCION"].": ".$datos_tercero_sucursal->direccion_principal;
            } else if($datos_tercero_sucursal->telefono_principal != ""){
                $ubicacion = $textos["TELEFONO"].": ".$datos_tercero_sucursal->telefono_principal;
            }
            if (isset($ubicacion)){
                $archivo->Ln(4);
                $archivo->SetX(5);
                $archivo->cell(100, 4, $ubicacion, 0, 0, "L");
            }
        } else {
            $archivo->SetFont('Arial','B',10);
            $archivo->SetXY(5,10);
            $archivo->cell(100, 4, "GRUPO MAYOR", 0, 0, "L");
            $archivo->cell(70, 4, "Propuesta compra: ", 0, 0, "R");
            $archivo->cell(30, 4, (int)$datos_encabezado->numero_consecutivo."-P".$numero_pedido, 0, 0, "L");
            $archivo->Ln(4);
            $archivo->SetX(5);
            $archivo->cell(100, 4, "", 0, 0, "L");
            $archivo->cell(70, 4, $textos["FECHA"].": ", 0, 0, "R");
            $archivo->cell(30, 4, $datos_encabezado->fecha_documento, 0, 0, "L");
        }

        $consulta_proveedor = SQL::seleccionar(array("terceros"),array("*"),"id='$datos_encabezado->id_proveedor'");
        $datos_proveedor = SQL::filaEnObjeto($consulta_proveedor);
        $nit_proveedor = $datos_proveedor->documento_identidad;
        $nombre_proveedor = SQL::obtenerValor("menu_terceros","NOMBRE_COMPLETO","id='$datos_encabezado->id_proveedor'");
        $id_municipio_proveedor = SQL::obtenerValor("localidades","id_municipio","id='$datos_proveedor->id_localidad'");
        $municipio_proveedor = SQL::obtenerValor("municipios","nombre","id='$id_municipio_proveedor'");
        $id_departamento_proveedor = SQL::obtenerValor("municipios","id_departamento","id='$id_municipio_proveedor'");
        $departamento_proveedor = SQL::obtenerValor("departamentos","nombre","id='$id_departamento_proveedor'");
        $archivo->Ln(8);
        $archivo->SetX(5);
        $archivo->SetFont('Arial','B',8);
        $archivo->cell(20, 4, $textos["PROVEEDOR"].": ", 0, 0, "L");
        $archivo->SetFont('Arial','',8);
        $archivo->cell(80, 4, $nombre_proveedor, 0, 0, "L");
        $archivo->SetFont('Arial','B',8);
        $archivo->cell(8, 4, $textos["NIT"].": ", 0, 0, "L");
        $archivo->SetFont('Arial','',8);
        $archivo->cell(22, 4, $nit_proveedor, 0, 0, "L");
        $archivo->Ln(4);
        $archivo->SetX(5);
        $archivo->SetFont('Arial','B',8);
        $archivo->cell(17, 4, $textos["DIRECCION"].": ", 0, 0, "L");
        $archivo->SetFont('Arial','',8);
        $archivo->cell(60, 4, $datos_proveedor->direccion_principal."(".$municipio_proveedor.", ".$departamento_proveedor.")", 0, 0, "L");
        $archivo->Ln(4);
        $archivo->SetX(5);
        $archivo->SetFont('Arial','B',8);
        $archivo->cell(15, 4, $textos["TELEFONO"].": ", 0, 0, "L");
        $archivo->SetFont('Arial','',8);
        $archivo->cell(25, 4, $datos_proveedor->telefono_principal, 0, 0, "L");
        $archivo->SetFont('Arial','B',8);
        $archivo->cell(12, 4, $textos["CELULAR"].": ", 0, 0, "L");
        $archivo->SetFont('Arial','',8);
        $archivo->cell(18, 4, $datos_proveedor->celular, 0, 0, "L");
        $archivo->Ln(4);
        $archivo->SetX(5);
        $archivo->SetFont('Arial','B',8);
        $archivo->cell(30, 4, $textos["CORREO_ELECTRONICO"].": ", 0, 0, "L");
        $archivo->SetFont('Arial','',8);
        $archivo->cell(60, 4, $datos_proveedor->correo, 0, 0, "L");
        $archivo->Ln(4);
    }
    function detalleOrden($archivo,$textos,$unidad_pedido,$id_sucursal_destino,$referencia,$detalle_articulo,$foto_articulo,$observaciones_articulo,$id_articulo,$valor_unitario_articulo,$fecha_entrega_articulo,$x_detalle,$y_detalle){

        $y_detalle+=4;
        $archivo->SetXY($x_detalle,$y_detalle);
        $archivo->SetLineWidth(0.2);
        $archivo->Line(5,$y_detalle +2 ,205,$y_detalle +2);

        $y_detalle+=4;
        $y_foto = $y_detalle;
        $archivo->SetXY($x_detalle,$y_detalle);

        if (isset($foto_articulo[$id_articulo][$referencia])){
            $archivo->SetXY(5,$y_detalle);
            $extension  = strtolower(substr($foto_articulo[$id_articulo][$referencia], (strrpos($foto_articulo[$id_articulo][$referencia], ".") - strlen($foto_articulo[$id_articulo][$referencia])) + 1));
            if ($extension == "png"){
                $imagen = imagecreatefrompng($foto_articulo[$id_articulo][$referencia]);
                $imagen_jpg = str_replace(".png",".jpg",$foto_articulo[$id_articulo][$referencia]);
                imagejpeg($imagen, $imagen_jpg, 100);
            } else if ($extension=="gif"){
                $imagen = imagecreatefromgif($foto_articulo[$id_articulo][$referencia]);
                $imagen_jpg = str_replace(".gif",".jpg",$foto_articulo[$id_articulo][$referencia]);
                imagejpeg($imagen, $imagen_jpg, 100);
            } else {
                $imagen_jpg = $foto_articulo[$id_articulo][$referencia];
            }
            $archivo->Image($imagen_jpg, 5, $y_detalle, 35, 38);
            $x_detalle = $x_detalle + 36;
            $archivo->SetX($x_detalle);
            if (isset($observaciones_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario_articulo][$fecha_entrega_articulo])){
                $ancho_columnas= array(30,67,67);
                $ancho_detalle = 67;
            } else {
                $ancho_columnas= array(30,134);
                $ancho_detalle = 134;
            }
            $ancho_referencia = 30;
        } else {
            if (isset($observaciones_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario_articulo][$fecha_entrega_articulo])){
                $ancho_columnas= array(30,85,85);
                $ancho_detalle = 85;
            } else {
                $ancho_columnas= array(30,170);
                $ancho_detalle = 170;
            }
            $x_detalle = 5;
            $archivo->SetX($x_detalle);
        }
        $archivo->SetFont('Arial','B',8);
        $archivo->cell(30,4,$textos["REFERENCIA"],1,0,"C");
        $archivo->cell($ancho_detalle,4,$textos["DESCRIPCION"],1,0,"C");
        if (isset($observaciones_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario_articulo][$fecha_entrega_articulo])){
            $archivo->cell($ancho_detalle,4,$textos["OBSERVACIONES"],1,0,"C");
            $archivo->SetAligns("L","L","L");
            $texto_columnas = array(
                $referencia,
                $detalle_articulo[$id_articulo],
                $observaciones_articulo[$id_articulo][$id_sucursal_destino][$referencia][$unidad_pedido][$valor_unitario_articulo][$fecha_entrega_articulo]
            );
        } else {
            $archivo->SetAligns("L","L");
            $texto_columnas = array(
                $referencia,
                $detalle_articulo[$id_articulo]
            );
        }
        $y_detalle = $archivo->GetY() + 4;
        $archivo->SetXY($x_detalle,$y_detalle);
        $archivo->SetWidths($ancho_columnas);
        $archivo->SetFont('Arial','',8);
        $archivo->Row($texto_columnas,true,"","",4);
        $y_detalle = $archivo->GetY() + 4;
        $y[0] = $y_detalle;
        $y[1] = $y_foto;
        return($y) ;
    }
?>

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

// Generar el formulario para la captura de datos

include("clases/saldos.php");

if (!empty($url_generar)) {

    $consulta       = SQL::seleccionar(array("sucursales"), array("*"), "codigo != '0'");
    $sucursales_ver = SQL::filasDevueltas($consulta);

    if($sucursales_ver==0){
        $error     = $textos["ERROR_SUCURSALES_VACIAS"];
        $titulo    = "";
        $contenido = "";        
    }else{

        $error  = "";
        $titulo = $componente->nombre;

        $nivelDetalle = array(
            "1" => $textos['CUENTA_MAYOR'],
            "2" => $textos['GRUPO'],
            "3" => $textos['SUBGRUPO'],
            "4" => $textos['CUENTA'],
            "5" => $textos['CUENTA_AUXILIAR']
        );

        $consulta = SQL::seleccionar(array("sucursales"), array("codigo, nombre"), "codigo!='0'");

        if ($sesion_usuario == $datosGlobales["usuarioMaestro"]){
            while ($datos = SQL::filaEnObjeto($consulta)){
                $sucursales[$datos->codigo] = $datos->nombre;
            }
        } else {
            // Obtener lista de sucursales para seleccion
            $tablas     = array(
                "a" => "perfiles_usuario",
                "b" => "componentes_usuario",
                "c" => "sucursales"
            );
            $columnas = array(
                "codigo" => "c.codigo",
                "nombre" => "c.nombre"
            );
            $condicion = "c.codigo = a.codigo_sucursal AND a.id = b.id_perfil
                        AND a.codigo_usuario = '".$sesion_codigo_usuario."'
                        AND b.id_componente = '".$componente->id."'";

            $consulta = SQL::seleccionar($tablas, $columnas, $condicion);

            if (SQL::filasDevueltas($consulta)) {
                while ($datos = SQL::filaEnObjeto($consulta)) {
                    $sucursales[$datos->codigo] = $datos->nombre;
                }
            }
        }

        $pestana_sucursales   = array();
        $pestana_sucursales[] = array(HTML::campoTextoCorto("*fecha_saldo", $textos["FECHA_SALDO"], 10, 10, date("Y-m-d"), array("title" => $textos["AYUDA_FECHA_SALDO"], "class" => "selectorFecha")));
        $pestana_sucursales[] = array(HTML::boton("botonSeleccionarTodos", $textos["SELECCIONAR_TODOS"], "seleccionar_todas_sucursales();", "", array()));

        foreach($sucursales AS $llave => $valor){
            
            $idSucursal = $llave;
            
            $pestana_sucursales[]   = array(
                HTML::marcaChequeo("sucursales[".$llave."]", $valor, $llave, false, array("title" => $textos["AYUDA_SUCURSAL_PRINCIPAL"], "id" => "sucursales_".$llave, "class" => "sucursales_electrodomesticos")),
                HTML::listaSeleccionSimple("consolidar[".$llave."]", $textos["CONSOLIDAR_EN"], HTML::generarDatosLista("sucursales", "codigo", "nombre","codigo!='0'"), $llave, array("title" => $textos["AYUDA_SUCURSAL_CONSOLIDA"]))
            );
        }
    
        $formularios["PESTANA_GENERAL"] = $pestana_sucursales;
                        
        $botones = array (HTML::boton("botonAceptar", $textos["ACEPTAR"], "imprimirItem(1);", "aceptar"));
            
        $contenido = HTML::generarPestanas($formularios, $botones);
    }
    
    // Enviar datos para la generacion del formulario al script que origino la peticion
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

// Exportar los datos
} elseif (!empty($forma_procesar)) {

    $error          = false;
    $mensaje        = $textos["ARCHIVO_GENERADO"];
    $ruta_archivo   = "";
    
    if (empty($forma_fecha_saldo) || !isset($forma_sucursales)) {
        $error   = true;
        $mensaje = $textos["ERROR_DATOS_INCOMPLETOS"];

    } else {
        
        $nombre         = "";
        $nombreArchivo  = "";
        do {
            $cadena         = Cadena::generarCadenaAleatoria(8);
            $nombre         = $sesion_sucursal.$cadena.".pdf";
            $nombreArchivo  = $rutasGlobales["archivos"]."/".$nombre;
        } while (is_file($nombreArchivo));
        
        $archivo = new PDF("P","mm","Letter");

        $id_tabla = SQL::obtenerValor("tablas","id","nombre_tabla = 'movimientos_contables'");

        $sucursales_reporte = array();
        foreach ($forma_sucursales AS $sucursal) {

            if (!isset($sucursales_reporte[$forma_consolidar[$sucursal]])){
                $sucursales_reporte[$forma_consolidar[$sucursal]] = $sucursal.",";
            } else {
                $sucursales_reporte[$forma_consolidar[$sucursal]] .= $sucursal.",";
            }
        }
        $contador_items=0;
        foreach ($sucursales_reporte AS $sucursal => $condicion_sucursal) {

            marcarTitulosCXP($archivo,$textos,$sucursal);

            $condicion_sucursal = trim($condicion_sucursal,",");
            $consolidadas       = explode(",", $condicion_sucursal);

            $nombre_sucursal = SQL::obtenerValor("sucursales", "nombre", "codigo = ".$sucursal);

            $tabla     = array("plan_contable");
            $columnas  = array("*");
            $condicion = "maneja_saldos = '1' AND maneja_tercero = '1' AND naturaleza_cuenta = 'C'";
            
            $consulta = SQL::seleccionar($tabla, $columnas, $condicion);
            $plan     = array();
            if(SQL::filasDevueltas($consulta)) {
                while($datos = SQL::filaEnObjeto($consulta)){
                    $plan[] = $datos->codigo_contable;
                }
            }
            $plan = implode(',',$plan);

            if($plan==""){
                $plan = "''";
            }
            
            $consulta_movimientos_contables = SQL::seleccionar(array("menu_movimientos_contables"), array("*"), "id_sucursal IN (".$condicion_sucursal.")");
            $movimiento                     = array();
            $sucursal_genera_movimiento     = "";
            if(SQL::filasDevueltas($consulta_movimientos_contables)){
                while($datos_movimientos = SQL::filaEnObjeto($consulta_movimientos_contables)){
                    $movimiento[] = "'".$datos_movimientos->id."'";
                }
            }

            $movimiento = implode(',',$movimiento);
            
            if($movimiento!=""){
                $movimiento_contable = "AND id_movimiento IN (".$movimiento.")";
            }else{
                $movimiento_contable = "AND id_movimiento IN ('')";
            }
                        
            $consulta_item = SQL::seleccionar(array("seleccion_items_movimientos_contables"), array("*"), "codigo_plan_contable IN (".$plan.") ".$movimiento_contable);
            
            $items = array();

            if(SQL::filasDevueltas($consulta_item)){
                $contador_items++;
                adicionarPaginaCXP($textos,$forma_fecha_saldo,$nombre_sucursal,$consolidadas,$sucursal,$archivo);
                while($datos_items = SQL::filaEnObjeto($consulta_item)){
                    $items[] = "'".$datos_items->id."'";
                }
                
                $items = implode(',',$items);
            
                $consulta_saldos = SQL::seleccionar(array("buscador_saldos_movimientos_contables"),array("*","SUM(saldo) AS valor_total"), "id_item_movimiento IN (".$items.")","id_item_movimiento, id_saldo WITH ROLLUP");
                
                $i = 1;

                $cuenta_actual             = '';
                
                $total_por_vencer_mayor_30 = 0;
                $total_por_vencer_1_30     = 0;
                
                $total_vencido_0_30        = 0;
                $total_vencido_31_60       = 0;
                $total_vencido_61_90       = 0;
                $total_vencido_mayor_90    = 0;

                while($datos_saldos = SQL::filaEnObjeto($consulta_saldos)){

                    $item        = SQL::obtenerValor("seleccion_items_movimientos_contables", "id_movimiento", "id = '".$datos_saldos->id_item_movimiento."'");
                    $abono       = SQL::obtenerValor("buscador_abonos_movimientos_contables", "id_item_movimiento", "id_saldo = '".$datos_saldos->id_saldo."'");
                    $item_abono  = SQL::obtenerValor("seleccion_items_movimientos_contables", "id_movimiento", "id = '".$abono."'");

                    $tercero_contable = $datos_saldos->id_tercero;
                    
                    $tercero  = SQL::obtenerValor("seleccion_terceros", "SUBSTRING_INDEX(nombre,'|',1)", "id = '".$tercero_contable."'");
                                                   
                    $tipo_documento        = SQL::obtenerValor("consecutivo_documentos","codigo_tipo_documento","llave_tabla = '".$item."' AND id_tabla = '".$id_tabla."'");
                    $consecutivo_documento = SQL::obtenerValor("consecutivo_documentos","consecutivo","llave_tabla = '".$item."' AND id_tabla = '".$id_tabla."' AND codigo_tipo_documento = '".$tipo_documento."'");
                    $descripcion_documento = SQL::obtenerValor("tipos_documentos","descripcion","codigo = '".$tipo_documento."'");
                    
                    $total_factura  = SQL::obtenerValor("buscador_saldos_movimientos_contables","SUM(saldo)","id_item_movimiento = '".$datos_saldos->id_item_movimiento."' GROUP BY id_item_movimiento");
        
                    $consulta_tercero  = SQL::seleccionar(array("terceros"), array("*"), "documento_identidad = '".$tercero_contable."' ");
                    $datos_tercero     = SQL::filaEnObjeto($consulta_tercero);
                    $totalizador       = SQL::seleccionar(array("totalizador_saldos_movimientos_contables"),array("*","SUM(abono) AS abono"), "id_saldo = '".$datos_saldos->id_saldo."'","id_saldo");
                    $datos_totalizador = SQL::filaEnObjeto($totalizador);
                                                
                    if($datos_saldos->id_saldo != NULL){

                        if( $cuenta_actual != $datos_saldos->id_item_movimiento ){                            
                            if(!imprimirCabeceraCXP($archivo,$textos,$tercero,$descripcion_documento,$consecutivo_documento,$total_factura,10)){
                                imprimirTercero($tercero,$textos,$descripcion_documento,$consecutivo_documento,$total_factura,$archivo);
                            }
                        }

                        list($ano_ven,$mes_ven,$dia_ven) = split("-",$datos_saldos->fecha_vencimiento);
                        $fecha_vencimiento = mktime(0,0,0, $mes_ven, $dia_ven, $ano_ven);

                        $diferencia = $datos_totalizador->saldo - $datos_totalizador->abono;

                        list($ano_ini,$mes_ini,$dia_ini) = split("-",$forma_fecha_saldo);
                        
                        $fecha_corte         = mktime(0,0,0, $mes_ini, $dia_ini, $ano_ini);
                        $fecha_por_vencer    = mktime(0,0,0, $mes_ini, $dia_ini-30, $ano_ini);                        
                        $fecha_vencido_0_30  = mktime(0,0,0, $mes_ini, $dia_ini+30, $ano_ini);
                        $fecha_vencido_31_60 = mktime(0,0,0, $mes_ini, $dia_ini+60, $ano_ini);
                        $fecha_vencido_61_90 = mktime(0,0,0, $mes_ini, $dia_ini+90, $ano_ini);

                        //Fecha Vencida > 30 dias
                        if($fecha_vencimiento < $fecha_por_vencer && $diferencia>0){
                            imprimirCabeceraCXP($archivo,$textos,$tercero,$descripcion_documento,$consecutivo_documento,$total_factura,5);
                            colorCeldas($archivo);
                            $archivo->Cell(20, 4, $i, 0, 0, "C", true);//Numero cuota
                            $archivo->Cell(20, 4, number_format($datos_totalizador->saldo), 0, 0, "C", true);//Valor Cuota
                            $archivo->Cell(30, 4, $datos_saldos->fecha_vencimiento, 0, 0, "C", true);//Fecha vencimiento
                            $archivo->Cell(20, 4, number_format($diferencia), 0, 0, "R", true);//Saldo Por vencer > 30
                            $archivo->Cell(20, 4, "", 0, 0, "R", true);//Saldo Por vencer 1-30
                            $archivo->Cell(20, 4, "", 0, 0, "R", true);//Saldo Vencido 0-30
                            $archivo->Cell(20, 4, "", 0, 0, "R", true);//Saldo Vencido 31-60
                            $archivo->Cell(20, 4, "", 0, 0, "R", true);//Saldo Vencido 61-90
                            $archivo->Cell(20, 4, "", 0, 0, "R", true);//Saldo Vencido > 90
                            $archivo->ln(4);

                            $total_por_vencer_mayor_30 += ($diferencia);
                            $i++;                           
                        }
                        //Fecha Vencida de 0 a 30 dias
                        else if($fecha_vencimiento <= $fecha_corte && $fecha_vencimiento >= $fecha_por_vencer && $diferencia>0){
                            imprimirCabeceraCXP($archivo,$textos,$tercero,$descripcion_documento,$consecutivo_documento,$total_factura,5);
                            colorCeldas($archivo);
                            $archivo->Cell(20, 4, $i, 0, 0, "C", true);//Numero cuota
                            $archivo->Cell(20, 4, number_format($datos_totalizador->saldo), 0, 0, "C", true);//Valor Cuota
                            $archivo->Cell(30, 4, $datos_saldos->fecha_vencimiento, 0, 0, "C", true);//Fecha vencimiento
                            $archivo->Cell(20, 4, "", 0, 0, "R", true);//Saldo Por vencer > 30
                            $archivo->Cell(20, 4, number_format($diferencia), 0, 0, "R", true);//Saldo Por vencer 1-30
                            $archivo->Cell(20, 4, "", 0, 0, "R", true);//Saldo Vencido 0-30
                            $archivo->Cell(20, 4, "", 0, 0, "R", true);//Saldo Vencido 31-60
                            $archivo->Cell(20, 4, "", 0, 0, "R", true);//Saldo Vencido 61-90
                            $archivo->Cell(20, 4, "", 0, 0, "R", true);//Saldo Vencido > 90
                            $archivo->ln(4);

                            $total_por_vencer_1_30 += ($diferencia);
                            $i++;                           
                        }
                        //Fecha Por vencer de 1 a 30 dias
                        else if($fecha_vencimiento > $fecha_corte && $fecha_vencimiento <= $fecha_vencido_0_30 && $diferencia>0){
                            imprimirCabeceraCXP($archivo,$textos,$tercero,$descripcion_documento,$consecutivo_documento,$total_factura,5);
                            colorCeldas($archivo);
                            $archivo->Cell(20, 4, $i, 0, 0, "C", true);//Numero cuota
                            $archivo->Cell(20, 4, number_format($datos_totalizador->saldo), 0, 0, "C", true);//Valor Cuota
                            $archivo->Cell(30, 4, $datos_saldos->fecha_vencimiento, 0, 0, "C", true);//Fecha vencimiento
                            $archivo->Cell(20, 4, "", 0, 0, "R", true);//Saldo Por vencer > 30
                            $archivo->Cell(20, 4, "", 0, 0, "R", true);//Saldo Por vencer 1-30
                            $archivo->Cell(20, 4, number_format($diferencia), 0, 0, "R", true);//Saldo Vencido 0-30
                            $archivo->Cell(20, 4, "", 0, 0, "R", true);//Saldo Vencido 31-60
                            $archivo->Cell(20, 4, "", 0, 0, "R", true);//Saldo Vencido 61-90
                            $archivo->Cell(20, 4, "", 0, 0, "R", true);//Saldo Vencido > 90
                            $archivo->ln(4);

                            $total_vencido_0_30 += ($diferencia);
                            $i++;                           
                        }
                        //Fecha Por vencer de 31 a 60 dias
                        else if($fecha_vencimiento > $fecha_vencido_0_30 && $fecha_vencimiento <= $fecha_vencido_31_60 && $diferencia>0){
                            imprimirCabeceraCXP($archivo,$textos,$tercero,$descripcion_documento,$consecutivo_documento,$total_factura,5);
                            colorCeldas($archivo);
                            $archivo->Cell(20, 4, $i, 0, 0, "C", true);//Numero cuota
                            $archivo->Cell(20, 4, number_format($datos_totalizador->saldo), 0, 0, "C", true);//Valor Cuota
                            $archivo->Cell(30, 4, $datos_saldos->fecha_vencimiento, 0, 0, "C", true);//Fecha vencimiento
                            $archivo->Cell(20, 4, "", 0, 0, "R", true);//Saldo Por vencer > 30
                            $archivo->Cell(20, 4, "", 0, 0, "R", true);//Saldo Por vencer 1-30
                            $archivo->Cell(20, 4, "", 0, 0, "R", true);//Saldo Vencido 0-30
                            $archivo->Cell(20, 4, number_format($diferencia), 0, 0, "R", true);//Saldo Vencido 31-60
                            $archivo->Cell(20, 4, "", 0, 0, "R", true);//Saldo Vencido 61-90
                            $archivo->Cell(20, 4, "", 0, 0, "R", true);//Saldo Vencido > 90
                            $archivo->ln(4);

                            $total_vencido_31_60 += ($diferencia);
                            $i++;                           
                        }
                        //Fecha Por vencer de 61 a 90 dias
                        else if($fecha_vencimiento > $fecha_vencido_31_60 && $fecha_vencimiento <= $fecha_vencido_61_90 && $diferencia>0){
                            imprimirCabeceraCXP($archivo,$textos,$tercero,$descripcion_documento,$consecutivo_documento,$total_factura,5);
                            colorCeldas($archivo);
                            $archivo->Cell(20, 4, $i, 0, 0, "C", true);//Numero cuota
                            $archivo->Cell(20, 4, number_format($datos_totalizador->saldo), 0, 0, "C", true);//Valor Cuota
                            $archivo->Cell(30, 4, $datos_saldos->fecha_vencimiento, 0, 0, "C", true);//Fecha vencimiento
                            $archivo->Cell(20, 4, "", 0, 0, "R", true);//Saldo Por vencer > 30
                            $archivo->Cell(20, 4, "", 0, 0, "R", true);//Saldo Por vencer 1-30
                            $archivo->Cell(20, 4, "", 0, 0, "R", true);//Saldo Vencido 0-30
                            $archivo->Cell(20, 4, "", 0, 0, "R", true);//Saldo Vencido 31-60
                            $archivo->Cell(20, 4, number_format($diferencia), 0, 0, "R", true);//Saldo Vencido 61-90
                            $archivo->Cell(20, 4, "", 0, 0, "R", true);//Saldo Vencido > 90
                            $archivo->ln(4);

                            $total_vencido_61_90 += ($diferencia);
                            $i++;                           
                        }
                        //Fecha Por vencer > 90 dias
                        else if($fecha_vencimiento > $fecha_vencido_61_90 && $diferencia>0){
                            imprimirCabeceraCXP($archivo,$textos,$tercero,$descripcion_documento,$consecutivo_documento,$total_factura,5);
                            colorCeldas($archivo);
                            $archivo->Cell(20, 4, $i, 0, 0, "C", true);//Numero cuota
                            $archivo->Cell(20, 4, number_format($datos_totalizador->saldo), 0, 0, "C", true);//Valor Cuota
                            $archivo->Cell(30, 4, $datos_saldos->fecha_vencimiento, 0, 0, "C", true);//Fecha vencimiento
                            $archivo->Cell(20, 4, "", 0, 0, "R", true);//Saldo Por vencer > 30
                            $archivo->Cell(20, 4, "", 0, 0, "R", true);//Saldo Por vencer 1-30
                            $archivo->Cell(20, 4, "", 0, 0, "R", true);//Saldo Vencido 0-30
                            $archivo->Cell(20, 4, "", 0, 0, "R", true);//Saldo Vencido 31-60
                            $archivo->Cell(20, 4, "", 0, 0, "R", true);//Saldo Vencido 61-90
                            $archivo->Cell(20, 4, number_format($diferencia), 0, 0, "R", true);//Saldo Vencido > 90
                            $archivo->ln(4);

                            $total_vencido_mayor_90 += ($diferencia);
                            $i++;                           
                        }
                        
                        $cuenta_actual  = $datos_saldos->id_item_movimiento;
                        
                    }else if($datos_saldos->id_saldo == NULL && $datos_saldos->id_item_movimiento != NULL){
                        imprimirCabeceraCXP($archivo,$textos,$tercero,$descripcion_documento,$consecutivo_documento,$total_factura,5);
                    
                        if($total_por_vencer_mayor_30==0){$total_por_vencer_mayor_30 = "";}
                        else{$total_por_vencer_mayor_30 = number_format($total_por_vencer_mayor_30);}
                        
                        if($total_por_vencer_1_30==0){$total_por_vencer_1_30 = "";}
                        else{$total_por_vencer_1_30 = number_format($total_por_vencer_1_30);}
                        
                        if($total_vencido_0_30==0){$total_vencido_0_30 = "";}
                        else{$total_vencido_0_30 = number_format($total_vencido_0_30);}
                        
                        if($total_vencido_31_60==0){$total_vencido_31_60 = "";}
                        else{$total_vencido_31_60 = number_format($total_vencido_31_60);}
                        
                        if($total_vencido_61_90==0){$total_vencido_61_90 = "";}
                        else{$total_vencido_61_90 = number_format($total_vencido_61_90);}

                        if($total_vencido_mayor_90==0){$total_vencido_mayor_90 = "";}
                        else{$total_vencido_mayor_90 = number_format($total_vencido_mayor_90);}

                        generarTotalesSaldo($archivo,$textos,$total_por_vencer_mayor_30,$total_por_vencer_1_30,$total_vencido_0_30,$total_vencido_31_60,$total_vencido_61_90,$total_vencido_mayor_90);
                        
                        $total_por_vencer_mayor_30 = 0;                        
                        $total_por_vencer_1_30     = 0;
                        $total_vencido_0_30        = 0;
                        $total_vencido_31_60       = 0;
                        $total_vencido_61_90       = 0;
                        $total_vencido_mayor_90    = 0;
                        $i = 1;
                    }
                }
            }
        
            
        }
        // Si existen sucursales se genera el pdf
        if($contador_items==0){
            $error   = true;
            $mensaje = $textos["ERROR_SIN_DATOS"];
        }else{
            $archivo->Output($nombreArchivo, "F");

            $consecutivo_arc = SQL::obtenerValor("archivos","MAX(consecutivo)","codigo_sucursal='".$sesion_sucursal."'");
            if ($consecutivo_arc){
                $consecutivo_arc++;
            } else {
                $consecutivo_arc = 1;
            }
            $consecutivo_arc = (int)$consecutivo_arc;
            
            $datos_archivo = array(
                "codigo_sucursal" => $sesion_sucursal,
                "consecutivo"     => $consecutivo_arc,
                "nombre"          => $nombre
            );
            SQL::insertar("archivos", $datos_archivo);
            $id_archivo   = $sesion_sucursal."|".$consecutivo_arc;
            $ruta_archivo = HTTP::generarURL("DESCARCH")."&id=".$id_archivo."&temporal=1";
        }
    }

    // Enviar datos con la respuesta del proceso al script que origino la peticion
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    $respuesta[2] = $ruta_archivo;            
    HTTP::enviarJSON($respuesta);
}
?>

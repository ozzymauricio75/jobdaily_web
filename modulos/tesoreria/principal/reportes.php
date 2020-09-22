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

//include("clases/diario.php");

// Generar el formulario para la captura de datos
if (!empty($url_generar)) {

    $consulta   = SQL::seleccionar(array("bancos"), array("*"), "codigo != '0'");
    $bancos_ver = SQL::filasDevueltas($consulta);

    if($bancos_ver==0){
        $error     = $textos["ERROR_BANCOS_VACIOS"];
        $titulo    = "";
        $contenido = "";
    }else{

        $error  = "";
        $titulo = $componente->nombre;

        $consulta = SQL::seleccionar(array("bancos"), array("codigo, documento_identidad_tercero"), "codigo!='0'");

        // Obtener lista de sucursales para seleccion
        if (SQL::filasDevueltas($consulta)) {
            while ($datos = SQL::filaEnObjeto($consulta)) {
                $bancos[$datos->codigo] = $datos->descripcion;
            }
        }

        $pestana_bancos   = array();
        $pestana_bancos[] = array(HTML::boton("botonSeleccionarTodos", $textos["SELECCIONAR_TODOS"], "seleccionar_todos_bancos();", "", array()));

        foreach($sucursales AS $llave => $valor){

            $idSucursal = $llave;

            $pestana_bancos[]   = array(
                HTML::marcaChequeo("bancos[".$llave."]", $valor, $llave, false, array("title" => $textos["AYUDA_SUCURSAL_PRINCIPAL"], "id" => "sucursales_".$llave, "class" => "sucursales_electrodomesticos"))
            );
        }

        $formularios["PESTANA_SUCURSALES"] = $pestana_sucursales;

        // Definicion de pestanas

        $fecha_inicial = date("Y/m/d")." - ".date("Y/m/d");

        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::campoTextoCorto("*fechas", $textos["FECHA_DESDE"].'  -  '.$textos["FECHA_HASTA"], 25, 25,$fecha_inicial, array("title" => $textos["RANGO_FECHAS"], "class" => "fechaRango"))
            ),
            array(
                HTML::listaSeleccionSimple("*tipo_comprobante", $textos["TIPO_COMPROBANTE"], HTML::generarDatosLista("tipos_comprobantes", "codigo", "descripcion", "codigo > 0"))
            ),
            array(
                HTML::marcaChequeo("mostrar_detalle", $textos["MOSTRAR_DETALLE"])
            )
        );

        // Definicion de botones
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "imprimirItem('0');", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones);
    }

    // Enviar datos para la generacion del formulario al script que origino la peticion
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

// Adicionar los datos provenientes del formulario
} elseif (!empty($forma_procesar)) {
    // Asumir por defecto que no hubo error
    $error          = false;
    $mensaje        = $textos["ITEM_ADICIONADO"];
    $ruta_archivo   = "";

    $fechas            = explode('-',$forma_fechas);
    $forma_fecha_desde = trim($fechas[0]);
    $forma_fecha_hasta = trim($fechas[1]);

    if (!isset($forma_sucursales)) {
        $error   = true;
        $mensaje = $textos["SUCURSAL_VACIO"];

    } else {

        $nombre         = "";
        $nombreArchivo  = "";
        do {
            $cadena         = Cadena::generarCadenaAleatoria(8);
            $nombre         = $sesion_sucursal.$cadena.".pdf";
            $nombreArchivo  = $rutasGlobales["archivos"]."/".$nombre;
        } while (is_file($nombreArchivo));

        $detalles = false;
        if (isset($forma_mostrar_detalle)) {
            $detalles = true;
        }

        $archivo                 = new PDF("L","mm","Legal");
        $archivo->textoCabecera  = $textos["FECHA"].": ".date("Y-m-d");
        $archivo->textoPiePagina = "";

        $condicion_comprobante = "AND codigo_tipo_comprobante = '".$forma_tipo_comprobante."'";
        if ($forma_tipo_comprobante == 0) {
            $condicion_comprobante = "";
        }

        $sucursales_reporte = array();
        foreach ($forma_sucursales AS $sucursal) {
            if (!isset($sucursales_reporte[$forma_consolidar[$sucursal]])){
                $sucursales_reporte[$forma_consolidar[$sucursal]] = "'".$sucursal."',";
            } else {
                $sucursales_reporte[$forma_consolidar[$sucursal]] .= "'".$sucursal."',";
            }
        }

        $total_debito_empresa  = 0;
        $total_credito_empresa = 0;

        $contador_registros = 0;

        foreach ($sucursales_reporte AS $sucursal => $condicion_sucursal) {

            $condicion_sucursal = trim($condicion_sucursal, ",");
            $consolidadas       = explode(",", $condicion_sucursal);

            $total_debito_sucursal  = 0;
            $total_credito_sucursal = 0;

            $total_debito_documento  = 0;
            $total_credito_documento = 0;

            $documento_actual   = '';
            $consecutivo_actual = '';

            $primera = true;

            $consulta   = SQL::seleccionar(array("movimientos_contables_consolidados"),array("*"),"(fecha_contabilizacion BETWEEN '".$forma_fecha_desde."' AND '".$forma_fecha_hasta."')AND codigo_sucursal_genera IN (".$condicion_sucursal.") ".$condicion_comprobante,"","codigo_tipo_documento ASC, numero_consecutivo ASC, codigo_tipo_comprobante ASC, numero_comprobante ASC");

            if (SQL::filasDevueltas($consulta)) {

                cabeceraComprobante($archivo,$textos,$sucursal,$forma_fecha_desde,$forma_fecha_hasta,$consolidadas,"REPODIMD");
                titulosDMD($archivo,$textos);

                while ($datos = SQL::filaEnObjeto($consulta)) {

                    if($archivo->breakCell(10)){
                        cabeceraComprobante($archivo,$textos,$sucursal,$forma_fecha_desde,$forma_fecha_hasta,$consolidadas,"REPODIMD");
                        titulosDMD($archivo,$textos);
                    }

                    if(!$primera && ($documento_actual != $datos->codigo_tipo_documento || $consecutivo_actual != $datos->numero_consecutivo)){
                        $archivo->SetFont('Arial','B',7);
                        $archivo->Cell(225,5,"",0,0,'L');
                        $archivo->Cell(40,5,$textos["TOTAL"].":",0,0,'R');
                        $archivo->Cell(35,5,"$".number_format($total_debito_documento,0),0,0,'R');
                        $archivo->Cell(35,5,"$".number_format($total_credito_documento,0),0,0,'R');
                        $archivo->Ln(5);
                        $total_debito_documento  = 0;
                        $total_credito_documento = 0;
                    }
                    $archivo->SetFont('Arial','',7);
                    $maneja_saldo    = SQL::obtenerValor("plan_contable","maneja_saldos","codigo_contable = '".$datos->codigo_contable."'");
                    $documento_cruce = "";
                    if($maneja_saldo == '1'){
                        $tabla = SQL::obtenerValor("tablas","nombre_tabla","id = '".$datos->id_tabla."'");
                        if($tabla == "movimientos_contables"){
                            if($datos->sentido_cuenta != $datos->sentido_movimiento){
                                $llave_item = $datos->llave_registro."|".str_pad($datos->consecutivo_item,9,"0", STR_PAD_LEFT);
                                $llave_saldo = SQL::obtenerValor("buscador_abonos_movimientos_contables","id_saldo","id_item_movimiento = '".$llave_item."'","","",0,1);
                                if($llave_saldo){
                                    $llave_saldo = explode('|',$llave_saldo);
                                    $documento_cruce = $llave_saldo[4]." - ".$llave_saldo[5];
                                }
                            }
                        }
                    }
                    $tercero = SQL::obtenerValor("seleccion_terceros","SUBSTRING_INDEX(nombre,'|',1)","id = '".$datos->documento_identidad_tercero."'");
                    $archivo->Cell(30,4,$datos->codigo_tipo_documento." - ".$datos->numero_consecutivo,0,0,'L');
                    $archivo->Cell(30,4,$documento_cruce,0,0,'L');
                    $archivo->Cell(35,4,$datos->codigo_tipo_comprobante." - ".$datos->numero_comprobante,0,0,'L');
                    $archivo->Cell(30,4,$datos->fecha_contabilizacion,0,0,'C');
                    $archivo->Cell(50,4,$tercero,0,0,'L',false,"",true);
                    $archivo->Cell(50,4,$datos->codigo_contable." - ".$datos->descripcion_cuenta,0,0,'L',false,"",true);
                    $archivo->Cell(20,4,$datos->codigo_anexo_contable,0,0,'L');
                    $archivo->Cell(20,4,$datos->codigo_auxiliar_contable,0,0,'L');

                    if($datos->sentido_movimiento == "D"){
                        $debito  = "$".number_format($datos->valor,0);
                        $credito = "$0";
                        $total_debito_sucursal  += $datos->valor;
                        $total_debito_empresa   += $datos->valor;
                        $total_debito_documento += $datos->valor;
                    }else{
                        $credito = "$".number_format($datos->valor,0);
                        $debito  = "$0";
                        $total_credito_sucursal  += $datos->valor;
                        $total_credito_empresa   += $datos->valor;
                        $total_credito_documento += $datos->valor;
                    }

                    $archivo->Cell(35,4,$debito,0,0,'R');
                    $archivo->Cell(35,4,$credito,0,0,'R');
                    $archivo->Ln(4);

                    if($detalles && !empty($datos->detalle)){
                        $archivo->SetFillColor(240,240,240);
                        $archivo->Cell(30,4,$textos["OBSERVACIONES"].":",0,0,'L',true);
                        $archivo->Cell(305,4,$datos->detalle,0,0,'L',true,"",true);
                        $archivo->Ln(4);
                    }

                    $documento_actual   = $datos->codigo_tipo_documento;
                    $consecutivo_actual = $datos->numero_consecutivo;
                    $primera            = false;
                    $contador_registros++;
                }
                $archivo->SetFont('Arial','B',7);
                $archivo->Cell(225,5,"",0,0,'L');
                $archivo->Cell(40,5,$textos["TOTAL"].":",0,0,'R');
                $archivo->Cell(35,5,"$".number_format($total_debito_documento,0),0,0,'R');
                $archivo->Cell(35,5,"$".number_format($total_credito_documento,0),0,0,'R');
                $archivo->Ln(5);
                totalSucursalDMD($archivo,$textos,$total_debito_sucursal,$total_credito_sucursal);
            }
        }

        if($total_credito_empresa > 0 || $total_debito_empresa > 0){
            $archivo->SetFont('Arial','B',7);
            $archivo->Cell(225,5,"",0,0,'L');
            $archivo->Cell(40,5,$textos["TOTAL_EMPRESA"].":",0,0,'R');
            $archivo->Cell(35,5,"$".number_format($total_debito_empresa,0),0,0,'R');
            $archivo->Cell(35,5,"$".number_format($total_credito_empresa,0),0,0,'R');
            $archivo->Ln(5);
            $archivo->Cell(225,5,"",0,0,'L');
            $archivo->Cell(40,5,$textos["DIFERENCIA"].":",0,0,'R');
            $archivo->Cell(70,5,"$".number_format($total_debito_empresa-$total_credito_empresa,0),0,0,'C');
            $archivo->Ln(5);
        }

//////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////

        if($contador_registros > 0){
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
        }else{
            $error   = true;
            $mensaje = $textos["ERROR_GENERAR_ARCHIVO"];
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

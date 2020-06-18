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
* publicada por la Fundacion para el Software Libre, ya sea la versiï¿½n 3
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

/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {
    $error  = "";
    $titulo = $componente->nombre;

    $nivelDetalle = array(
                    "1" => $textos['GRUPO'],
                    "2" => $textos['SUBGRUPO'],
                    "3" => $textos['CUENTA'],
                    "4" => $textos['CUENTA_AUXILIAR']                    
                );
                    
    $rango_dias = array(
                    "1" => $textos['30_DIAS'],
                    "2" => $textos['60_DIAS'],
                    "3" => $textos['90_DIAS']
                );
                
    /*** Definición de pestaña general ***/
    $formularios["PESTANA_BALANCE_GENERAL"] = array(
        array(
            HTML::campoTextoCorto("*fecha_balance_general", $textos["FECHA_BALANCE_GENERAL"], 10, 10, date("Y-m-d"), array("title" => $textos["AYUDA_FECHA_BALANCE_GENERAL"], "class" => "selectorFecha")),
            HTML::listaSeleccionSimple("nivel_detalle", $textos["NIVEL_DETALLE"], $nivelDetalle, 4, array("title" => $textos["AYUDA_NIVEL_DETALLE"]))
        ), array(
            HTML::marcaChequeo("detalla_auxiliares", $textos["DETALLA_AUXILIARES"], "1", false, array("title" => $textos["AYUDA_DETALLA_AUXILIARES"])),
            HTML::marcaChequeo("detalla_terceros", $textos["DETALLA_TERCEROS"], "1", false, array("title" => $textos["AYUDA_DETALLA_TERCEROS"])),
        ), array(
            HTML::boton("boton_balance_general", $textos["GENERAR"], "exportarDatosIndice('1');", "aceptar")
        )
    );

    /*** Definición de pestaña movimientos ***/
    $formularios["PESTANA_PYG"] = array(
        array(
            HTML::campoTextoCorto("*fecha_inicial_pyg", $textos["FECHA_INICIAL_PYG"], 10, 10, date("Y-m-d"), array("title" => $textos["AYUDA_FECHA_INICIAL_PYG"], "class" => "selectorFecha")),
            HTML::campoTextoCorto("*fecha_final_pyg", $textos["FECHA_FINAL_PYG"], 10, 10, date("Y-m-d"), array("title" => $textos["AYUDA_FECHA_FINAL_PYG"], "class" => "selectorFecha")),
            HTML::contenedor(HTML::boton("botonRemover", "", "removerItemFecha(this);", "eliminar"), array("id" => "removedor", "style" => "display: none")),
            HTML::boton("botonAgregar", $textos["AGREGAR"], "agregarItemPeriodosContables();", "adicionar"),
            HTML::campoOculto("cantidad_filas","0")
        ), array(
            HTML::marcaChequeo("detalla_auxiliares_pyg", $textos["DETALLA_AUXILIARES"], "1", false, array("title" => $textos["AYUDA_DETALLA_AUXILIARES"])),
            HTML::marcaChequeo("detalla_terceros_pyg", $textos["DETALLA_TERCEROS"], "1", false, array("title" => $textos["AYUDA_DETALLA_TERCEROS"])),
        ), array(
            HTML::mostrarDato("titulo_periodos_contables",$textos["PERIODOS_CONTABLES"],"")
        ), array(
            HTML::generarTabla(array("id","","FECHA_INICIAL_PYG","FECHA_FINAL_PYG"),"",array("C","I","I"),"listaItemsPeriodoContable",false)
        ), array(
            HTML::boton("boton_pyg", $textos["GENERAR"], "exportarDatosIndice('2');", "aceptar")
        )  
    );

    /*** Definición de pestaña general ***/
    $formularios["PESTANA_BALANCE_COMPROBACION"] = array(
        array(
            HTML::campoTextoCorto("*fecha_balance_general", $textos["FECHA_BALANCE_GENERAL"], 10, 10, date("Y-m-d"), array("title" => $textos["AYUDA_FECHA_BALANCE_GENERAL"], "class" => "selectorFecha"))
        ), array(
            HTML::boton("boton_balance_comprobacion", $textos["GENERAR"], "exportarDatosIndice('3');", "aceptar")
        )
    );

    $contenido = HTML::generarPestanas($formularios);

    /*** Enviar datos para la generación del formulario al script que originï¿½ la peticiï¿½n ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

/*** Exportar los datos***/
} elseif (!empty($forma_procesar)) {

        if($forma_id==1){
                /*** 
                    BALANCE GENERAL:
                
                
                ***/
            
                $detallar_terceros = false;
                if(isset($forma_detalla_terceros))
                    $detallar_terceros = true;

                $detallar_auxiliares = false;
                if(isset($forma_detalla_auxiliares))
                    $detallar_auxiliares = true;

                $nombreArchivo = $rutasGlobales["archivos"]."/".$componente->id."-".Sesion::$id."3.pdf";

                $cargaPdf = 0;
                $mensaje = $textos["ERROR_GENERAR_ARCHIVO"];

                $fechaReporte = date("Y-m-d");
                $archivo = new PDF("P","mm","Letter");
                $archivo->textoCabecera = $textos["FECHA"].": $fechaReporte";

                $tituloColumnas = array($textos["CODIGO"],$textos["NOMBRE_CUENTA"],$textos["DEBE"],$textos["HABER"]);
                $anchoColumnas = array(20,100,30,30);

                $archivo->AddPage();
                $archivo->SetFont('Arial','B',6);
                $archivo->Cell(200, 4, $textos["BALANCE_GENERAL"], 0, 0, "C", false);
                $archivo->Ln(4);

                $archivo->SetFont('Arial','',6);
                $archivo->Cell(200, 4, $textos["FECHA_DESDE"].": ".$forma_fecha_balance_general, 0, 0, "L", false);
                $archivo->Ln(8);

                $tablas = array("plan_contable AS ppc",
                                "movimientos_contables AS mc",
                                "items_movimientos_contables AS imc",
                                "tablas AS tt",
                                "consecutivo_documentos AS cd");

                $condicion = "  tt.nombre_tabla  = 'movimientos_contables' AND
                                cd.id_tabla = tt.id AND
                                cd.id_registro_tabla = mc.id AND
                                imc.id_movimiento = mc.id AND
                                mc.estado = 1 AND
                                imc.id_plan_contable = ppc.id AND
                                mc.fecha_contabilizacion <= '$forma_fecha_balance_general' AND
                                ( SUBSTRING(ppc.codigo_contable,1,1)='1' OR 
                                  SUBSTRING(ppc.codigo_contable,1,1)='2' OR 
                                  SUBSTRING(ppc.codigo_contable,1,1)='3')";
                        
                $datos = array( "imc.id_plan_contable AS id_cuenta",
                                "ppc.codigo_contable AS codigo_cuenta_contable",
                                "ppc.descripcion AS descripcion",
                                "imc.id_auxiliar_contable AS auxiliar_contable",
                                "imc.sentido AS sentido",
                                "mc.id_tercero AS tercero",
                                "mc.fecha_contabilizacion AS fechaContabilizacion",
                                "cd.id_tipo_documento AS tipoDocumento",
                                "cd.numero_consecutivo AS consecutivo",
                                "imc.valor AS valor",
                                "imc.valor_base1 AS valorBase1",
                                "imc.valor_base2 AS valorBase2");
          
                switch($forma_nivel_detalle){
                    case 1: $datos[] = $longitudCuenta=2;
                            break;

                    case 2: $datos[] = $longitudCuenta=4;
                            break;

                    case 3: $datos[] = $longitudCuenta=6;
                            break;

                    case 4: $datos[] = $longitudCuenta=8;
                            break;
                }

                $cuentas_balance        = array();
                $nombre_cuentas         = array();
                $valor_debito_cuenta    = array();
                $valor_credito_cuenta   = array();

                $cuentas_auxiliares     = array();
                $terceros               = array();
                $y=0;

                $consulta2  = SQL::seleccionar($tablas, $datos, $condicion, "", "");
                
                if(SQL::filasDevueltas($consulta2)) {
                        while ($datos2 = SQL::filaEnObjeto($consulta2) ) {

                            $valorDebito = $datos2->valor;
                            $valorCredito = 0;
                            if($datos2->sentido==2){
                                $valorDebito = 0;
                                $valorCredito = $datos2->valor;
                            }

                            if(isset($cuentas_balance[$datos2->codigo_cuenta_contable])){
                                $valor_debito_cuenta[$datos2->codigo_cuenta_contable]+=$valorDebito;
                                $valor_credito_cuenta[$datos2->codigo_cuenta_contable]+=$valorCredito;
                            }else{
                                $cuentas_balance[$datos2->codigo_cuenta_contable]       =$datos2->codigo_cuenta_contable;
                                $nombre_cuentas[$datos2->codigo_cuenta_contable]        =$datos2->descripcion;
                                $valor_debito_cuenta[$datos2->codigo_cuenta_contable]   =$valorDebito;
                                $valor_credito_cuenta[$datos2->codigo_cuenta_contable]  =$valorCredito;
                            }


                            if($datos2->auxiliar_contable){
                                if(isset($cuentas_auxiliares[$datos2->codigo_cuenta_contable]))
                                    $cuentas_auxiliares[$datos2->codigo_cuenta_contable].= $datos2->auxiliar_contable."-".$valorDebito."-".$valorCredito."|";
                                else
                                    $cuentas_auxiliares[$datos2->codigo_cuenta_contable] = $datos2->auxiliar_contable."-".$valorDebito."-".$valorCredito."|";
                            }    

                            if($datos2->tercero){
                                if(isset($terceros[$datos2->codigo_cuenta_contable]))
                                    $terceros[$datos2->codigo_cuenta_contable].= $datos2->tercero."-".$valorDebito."-".$valorCredito."|";
                                else
                                    $terceros[$datos2->codigo_cuenta_contable] = $datos2->tercero."-".$valorDebito."-".$valorCredito."|";
                            }                
                        }
                    
                        $listado_cuentas        = array();
                        $listado_descripciones  = array();
                        $listado_debitos        = array();
                        $listado_creditos       = array();

                        $ultimoPadre1           = array();
                        $ultimoPadre2           = array();

                        foreach($cuentas_balance AS $numeroCuenta) {

                            $id_cuenta_padre    = $numeroCuenta;
                            $hallarPadre        = true;

                            $codigo_cuenta_padre        = array();
                            $descripcion_cuenta_padre   = array();
                            $debito_cuenta_padre        = array();
                            $credito_cuenta_padre       = array();

                            while($hallarPadre) {
                                $id_cuenta_padre               = SQL::obtenerValor("plan_contable","id_cuenta_padre","codigo_contable='$id_cuenta_padre'");

                                if($id_cuenta_padre) {
                                    $codigo_cuentapadre        = SQL::obtenerValor("plan_contable","codigo_contable","id='$id_cuenta_padre'");
                                    $descripcion_cuentapadre   = SQL::obtenerValor("plan_contable","descripcion","id='$id_cuenta_padre'");
                                    $id_cuenta_padre           = $codigo_cuentapadre;

                                    $codigo_cuenta_padre[]                           = $codigo_cuentapadre;
                                    $descripcion_cuenta_padre[$codigo_cuentapadre]   = $descripcion_cuentapadre;
                                    $debito_cuenta_padre[$codigo_cuentapadre]        = $valor_debito_cuenta[$numeroCuenta];
                                    $credito_cuenta_padre[$codigo_cuentapadre]       = $valor_credito_cuenta[$numeroCuenta];

                                    $hallarPadre=true;                        
                                }else {
                                    $hallarPadre=false;
                                    $ultimo_padre = $codigo_cuentapadre;
                                }
                            }

                            asort($codigo_cuenta_padre);
                            foreach($codigo_cuenta_padre AS $cuenta_padre) {

                                $listado_cuentas[]                      = "'".($cuenta_padre)."'";
                                $listado_descripciones[$cuenta_padre]   = $descripcion_cuenta_padre[$cuenta_padre];
                                $listado_debitos[$cuenta_padre]         += $debito_cuenta_padre[$cuenta_padre];
                                $listado_creditos[$cuenta_padre]        += $credito_cuenta_padre[$cuenta_padre];

                                $ultimoPadre1[$cuenta_padre]             = $ultimo_padre;
                                $ultimoPadre2[]                          = $ultimo_padre;
                            }

                            $listado_cuentas[]                                          = "'".($cuentas_balance[$numeroCuenta])."'";
                            $listado_descripciones[ $cuentas_balance[$numeroCuenta] ]   = $nombre_cuentas[$numeroCuenta];
                            $listado_debitos[$cuentas_balance[$numeroCuenta]]           += $valor_debito_cuenta[$numeroCuenta];
                            $listado_creditos[$cuentas_balance[$numeroCuenta]]          += $valor_credito_cuenta[$numeroCuenta];

                            $ultimoPadre1[$cuentas_balance[$numeroCuenta]]              = $ultimo_padre;
                            $ultimoPadre2[]                                             = $ultimo_padre;
                        }

                        
                        sort($listado_cuentas);
                        $vectorTemporal = array();
                        foreach($listado_cuentas AS $idCuenta) {
                            if(!isset($vectorTemporal[$idCuenta])) {
                                $vectorTemporal[$idCuenta] = $idCuenta;
                            }
                        }

                        $listado_cuentas = $vectorTemporal;
                        $vector_totalizador = $listado_cuentas;
                        sort($listado_cuentas);
                        $i=0;

                        foreach($listado_cuentas AS $idCuenta) {

                            if($i%2==0){
                                $rojo = 255;
                                $azul = 255;
                                $verde = 255;
                            }else{
                                $rojo = 226;
                                $azul = 236;
                                $verde = 237;
                            }

                            $idCuenta = ltrim($idCuenta,"'");
                            $idCuenta = rtrim($idCuenta,"'");

                            if($idCuenta>10){
                                /*** Imprime la lista de cuentas ***/
                                if(strlen($idCuenta)<=$longitudCuenta){

                                    $indicador='';
                                    if($idCuenta<100 && $idCuenta>10)
                                        $indicador='B';

                                    $archivo->SetFont('Arial',$indicador,6);

                                    $archivo->SetFillColor($rojo,$verde,$azul);
                                    $archivo->Cell(20, 4, $idCuenta, 1, 0, "L", true);
                                    $archivo->Cell(100, 4, $listado_descripciones[$idCuenta], 1, 0, "L", true);

                                    if(strlen($idCuenta)==$longitudCuenta){
                                        $archivo->Cell(30, 4, "$ ".number_format($listado_debitos[$idCuenta]), 1, 0, "R", true);
                                        $archivo->Cell(30, 4, "$ ".number_format($listado_creditos[$idCuenta]), 1, 0, "R", true);
                                    } else {
                                        $archivo->Cell(30, 4, "", 1, 0, "R", true);
                                        $archivo->Cell(30, 4, "", 1, 0, "R", true);
                                    }

                                    $archivo->Ln(4);
                                    $i++;
                                }
                                /*** ***/

                                /*** Imprimir lista de terceros **/
                                if((isset($terceros[$idCuenta])) && $detallar_terceros) {

                                    $terceros[$idCuenta] = rtrim($terceros[$idCuenta],"|");
                                    $cuentas_tercero = explode("|",$terceros[$idCuenta]);

                                    foreach($cuentas_tercero AS $datos_tercero){

                                        $info_datos = explode("-",$datos_tercero);
                                        $id_tercero     =  $info_datos[0];
                                        $valor_debito   =  $info_datos[1];
                                        $valor_credito  =  $info_datos[2];

                                        if($i%2==0) {
                                            $rojo = 255;
                                            $azul = 255;
                                            $verde = 255;
                                        } else{
                                            $rojo = 226;
                                            $azul = 236;
                                            $verde = 237;
                                        }

                                        $nombre_tercero = SQL::obtenerValor("seleccion_terceros","SUBSTRING_INDEX(nombre,'|',1)","id='$id_tercero'");
                                        $archivo->SetFont('Arial','',6);

                                        $archivo->SetFillColor($rojo,$verde,$azul);
                                        $archivo->Cell(120, 4, $nombre_tercero, 1, 0, "R", true);
                                        $archivo->Cell(30, 4, "$ ".number_format($valor_debito), 1, 0, "R", true);
                                        $archivo->Cell(30, 4, "$ ".number_format($valor_credito), 1, 0, "R", true);
                                        $archivo->Ln(4);
                                        $i++;
                                    }

                                    /*** Totalizar ****
                                    if($i%2==0) {
                                        $rojo = 255;
                                        $azul = 255;
                                        $verde = 255;
                                    } else{
                                        $rojo = 226;
                                        $azul = 236;
                                        $verde = 237;
                                    }

                                    $archivo->SetFont('Arial','I',6);
                                    $archivo->SetFillColor($rojo,$verde,$azul);
                                    $archivo->Cell(120, 4, $textos["TOTAL"]." ".$listado_descripciones[$idCuenta], 1, 0, "R", true);
                                    $archivo->Cell(30, 4, "$ ".number_format($listado_debitos[$idCuenta]), 1, 0, "R", true);
                                    $archivo->Cell(30, 4, "$ ".number_format($listado_creditos[$idCuenta]), 1, 0, "R", true);
                                    $archivo->Ln(4);
                                    $i++;
                                    *** ***/
                                }
                                /*** ***/
                            } else {

                                if($idCuenta>1) {
                                    $archivo->SetFont('Arial','B',6);

                                    $archivo->SetFillColor($rojo,$verde,$azul);
                                    $archivo->Cell(120, 4, $textos["TOTAL"]." ".$listado_descripciones[$idCuenta-1], 1, 0, "R", true);
                                    $archivo->Cell(30, 4, "$ ".number_format($listado_debitos[$idCuenta-1]), 1, 0, "R", true);
                                    $archivo->Cell(30, 4, "$ ".number_format($listado_creditos[$idCuenta-1]), 1, 0, "R", true);
                                    $archivo->Ln(4);
                                    $i++;
                                }

                                $archivo->SetFont('Arial','',6);
                                $archivo->SetFillColor(255,255,255);
                                $archivo->Cell(180, 4, $listado_descripciones[$idCuenta], 1, 0, "C", true);
                                $archivo->Ln(4);

                                $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
                                $archivo->Ln(4);
                            }
                        }
                        /*** ***/

                        /*** Totalizar el último listado de cuentas ***/
                        if($i%2==0){
                            $rojo = 255;
                            $azul = 255;
                            $verde = 255;
                        }else{
                            $rojo = 226;
                            $azul = 236;
                            $verde = 237;
                        }

                        $archivo->SetFont('Arial','B',6);
                        $archivo->SetFillColor($rojo,$verde,$azul);
                        $archivo->Cell(120, 4, $textos["TOTAL"]." ".$listado_descripciones[substr($idCuenta,0,1)], 1, 0, "R", true);
                        $archivo->Cell(30, 4, "$ ".number_format($listado_debitos[substr($idCuenta,0,1)]), 1, 0, "R", true);
                        $archivo->Cell(30, 4, "$ ".number_format($listado_creditos[substr($idCuenta,0,1)]), 1, 0, "R", true);
                        $archivo->Ln(4);
                        /*** ***/

                        $archivo->Output($nombreArchivo, "F");
                        $cargaPdf = 1;
                }

                /*** Enviar datos con la respuesta del proceso al script que originó la petición ***/
                $respuesta    = array();
                $respuesta[0] = false;
                if ($cargaPdf == 1){
                    $respuesta[1] = HTML::enlazarPagina($textos["IMRPIMIR_PDF"], $nombreArchivo, array("target" => "_new"));
                } else{
                    $respuesta[0] = true;
                    $respuesta[1] = $mensaje;
                }
                HTTP::enviarJSON($respuesta);
                
                
        } if($forma_id==2){
               /*** 
                        BALANCE DE PERDIDAS Y GANANCIAS
                
                
                ***/
            
                $detallar_terceros = false;
                if(isset($forma_detalla_terceros_pyg))
                    $detallar_terceros = true;

                $detallar_auxiliares = false;
                if(isset($forma_detalla_auxiliares_pyg))
                    $detallar_auxiliares = true;
            
                $nombreArchivo = $rutasGlobales["archivos"]."/".$componente->id."-".Sesion::$id."3.pdf";
                $cargaPdf = 0;
                $mensaje = $textos["ERROR_GENERAR_ARCHIVO"];
                
                $fechaReporte = date("Y-m-d");
                $archivo = new PDF("P","mm","Letter");
                $archivo->textoCabecera = $textos["FECHA"].": $fechaReporte";
                
                $archivo->AddPage();
                $archivo->SetFont('Arial','B',6);
                $archivo->Cell(200, 4, $textos["BALANCE_PERDIDAS_GANANCIAS"], 0, 0, "C", false);
                $archivo->Ln(4);

                $tituloColumnas = array($textos["CODIGO"],$textos["NOMBRE_CUENTA"]);
                $anchoColumnas  = array(20,80);                
                                
                $cantidadFechas = count($forma_idTablaPeriodos);

                $archivo->SetFont('Arial','',6);
                $archivo->Cell(20, 4, "", 0, 0, "L", false);
                $archivo->Cell(80, 4, "", 0, 0, "L", false);
                
                $valorAnchoColumnas = 80/($cantidadFechas*2);                 
                for($i=0; $i<$cantidadFechas; $i++) {
                    $tituloColumnas[] = $textos["DEBE"];
                    $tituloColumnas[] = $textos["HABER"];    
                    
                    $anchoColumnas[]  = $valorAnchoColumnas;
                    $anchoColumnas[]  = $valorAnchoColumnas;

                    $archivo->Cell(($valorAnchoColumnas*2), 4, $forma_fechaInicialPeriodo[$i], 0, 0, "C", false);                    
                }                
                $archivo->Ln(4);
                
                $archivo->Cell(20, 4, "", 0, 0, "L", false);
                $archivo->Cell(80, 4, "", 0, 0, "L", false);
                for($i=0; $i<$cantidadFechas; $i++) {
                    $archivo->Cell(($valorAnchoColumnas*2), 4, $forma_fechaFinalPeriodo[$i], 0, 0, "C", false);                    
                }    
                $archivo->Ln(4);
                
                
                //Listado de fechas por el cúal se harán los resultados
                $cadenaFechas = "";
                for($i=0; $i<$cantidadFechas; $i++) {
                    $fechasInicialesConsulta = $forma_fechaInicialPeriodo[$i];
                    $fechasFinalesConsulta   = $forma_fechaFinalPeriodo[$i];
                    
                    $cadenaFechas.= " fecha_contabilizacion BETWEEN '$fechasInicialesConsulta' AND '$fechasFinalesConsulta' OR";
                }   
                $cadenaFechas = "(".rtrim($cadenaFechas, " OR").")";
                
                //Consulta de la base de datos de las cuentas PYG
                $tablas = array("plan_contable AS ppc",
                                "movimientos_contables AS mc",
                                "items_movimientos_contables AS imc",
                                "tablas AS tt",
                                "consecutivo_documentos AS cd");

                $condicion = "  tt.nombre_tabla  = 'movimientos_contables' AND
                                cd.id_tabla = tt.id AND
                                cd.id_registro_tabla = mc.id AND
                                imc.id_movimiento = mc.id AND
                                mc.estado = 1 AND
                                imc.id_plan_contable = ppc.id AND
                                ( SUBSTRING(ppc.codigo_contable,1,1)='4' OR 
                                  SUBSTRING(ppc.codigo_contable,1,1)='5' OR 
                                  SUBSTRING(ppc.codigo_contable,1,1)='6' OR 
                                  SUBSTRING(ppc.codigo_contable,1,1)='7' ) AND $cadenaFechas";
                        
                $datos = array( "imc.id_plan_contable AS id_cuenta",
                                "ppc.codigo_contable AS codigo_cuenta_contable",
                                "ppc.descripcion AS descripcion",
                                "imc.id_auxiliar_contable AS auxiliar_contable",
                                "imc.sentido AS sentido",
                                "mc.id_tercero AS tercero",
                                "mc.fecha_contabilizacion AS fechaContabilizacion",
                                "cd.id_tipo_documento AS tipoDocumento",
                                "cd.numero_consecutivo AS consecutivo",
                                "imc.valor AS valor",
                                "imc.valor_base1 AS valorBase1",
                                "imc.valor_base2 AS valorBase2");
                                
  
                $longitudCuenta=10;
                $consulta2  = SQL::seleccionar($tablas, $datos, $condicion, "", "");
                
                $cuentasInforme = array();
                $cuentasValidas = array();                
                $cuentasPadre     = array();                
                $cuentasImpresion = array();
                $nombreCuentaImpresion = array();
                $cuentasPadreAlterno = array();
                $valorCuentaInicial = array();
                $cuentasAgrupamiento = array();
                
                if(SQL::filasDevueltas($consulta2)) {
                    
                        //Extrae las cuentas que arroja la consulta 
                        while ($datos2 = SQL::filaEnObjeto($consulta2) ) {                             
                            $cuentasInforme[] = " ".$datos2->codigo_cuenta_contable." ";    
                            $valorCuentaInicial[$datos2->codigo_cuenta_contable] = $datos2->valor; 
                            $sentidoCuentaInicial[$datos2->codigo_cuenta_contable] = $datos2->sentido;                            
                        } 
                        
                        //Ordena las cuentas ascendentemente
                        sort($cuentasInforme);
                        
                        //Extraé el nivel de detalle que solicite el usuario de la aplicación
                        foreach ($cuentasInforme AS $cuentaContable ) {                             
                            if(strlen($cuentaContable)<=$longitudCuenta) {
                                $cuentasValidas[] = $cuentaContable;                                
                            }
                        } 
                        
                        //Buscar padre de la cuenta
                        foreach ($cuentasValidas AS $cuentaContable ) { 
                             
                            $id_cuenta_padre            = (int)$cuentaContable;
                            $id_cuenta_padre            = SQL::obtenerValor("plan_contable","id","codigo_contable='$id_cuenta_padre'");
                            $id_cuenta_padre_inicial    = $id_cuenta_padre;
                            
                            $cuentaPadreCuenta = array();
                            
                            $cuentaPadreCuenta[] = $id_cuenta_padre_inicial; 
                            while ($id_cuenta_padre!=0) {                                    
                                   /*$archivo->Cell($valorAnchoColumnas, 4, $id_cuenta_padre, 1, 0, "R", false);
                                   $archivo->Ln(4);*/                                                                           
                                   $id_cuenta_padre = SQL::obtenerValor("plan_contable","id_cuenta_padre","id='$id_cuenta_padre'");                                      
                                   $cuentaPadreCuenta[] = $id_cuenta_padre;
                            }

                            foreach($cuentaPadreCuenta AS $idCuentaPadre) { 
                                    $cuentasPadreAlterno[] = $idCuentaPadre;
                                    //Vector en su orden descendente para calcular los consolidados
                            }
                                
                            sort($cuentaPadreCuenta);
                            foreach($cuentaPadreCuenta AS $idCuentaPadre) { 
                                    $cuentasPadre[] = $idCuentaPadre;
                            }
                        } 
                                                
                        
                        //Códigos contables de las cuentas                        
                        foreach ($cuentasPadre AS $cuentaContable ) {                              
                             $codigoCuentas = SQL::obtenerValor("plan_contable","codigo_contable","id='$cuentaContable'");
                             $nombreCuentaImpresion[$codigoCuentas] = SQL::obtenerValor("plan_contable","descripcion","id='$cuentaContable'");
                             
                             //$cuentasAgrupamiento[] = (int)$codigoCuentas;
                             $cuentasImpresion[] = $codigoCuentas;
                        } 
                        
                                
                        //Agrupar por número de cuenta          
                        /*foreach ($cuentasAgrupamiento AS $cuentaContable ) {   
                            if( !isset($cuentasImpresion[$cuentaContable]) ) {                      
                                $cuentasImpresion[$cuentaContable] = $cuentaContable;
                            } 
                        }*/
                        
    
                                            
                        //Imprime en panatalla
                        $i=0;
                        foreach ($cuentasImpresion AS $cuentaContable ) {   
                           
                            if($cuentaContable!=0){
                                
                                /*** ***/if($i%2==0){
                                    $rojo = 255;
                                    $azul = 255;
                                    $verde = 255;
                                }else{
                                    $rojo = 226;
                                    $azul = 236;
                                    $verde = 237;
                                }/*** ***/
                                             
                                    $archivo->SetFont('Arial',$indicador,6);
                                    $archivo->SetFillColor($rojo,$verde,$azul);
                         
                                    $archivo->Cell(20, 4, $cuentaContable, 1, 0, "L", true);
                                    $archivo->Cell(80, 4, $nombreCuentaImpresion[$cuentaContable], 1, 0, "L", true);
                                    
                                    if($sentidoCuentaInicial[$cuentaContable]==1) {
                                        $archivo->Cell($valorAnchoColumnas, 4, "$".number_format($valorCuentaInicial[$cuentaContable]), 1, 0, "R", true);
                                        $archivo->Cell($valorAnchoColumnas, 4, " ", 1, 0, "R", true);
                                    } if($sentidoCuentaInicial[$cuentaContable]==2) {
                                        $archivo->Cell($valorAnchoColumnas, 4, " ", 1, 0, "R", true);
                                        $archivo->Cell($valorAnchoColumnas, 4, "$".number_format($valorCuentaInicial[$cuentaContable]), 1, 0, "R", true);                                
                                    } 
                                    
                                    
                                    if(!isset($valorCuentaInicial[$cuentaContable])) {
                                        $archivo->Cell($valorAnchoColumnas, 4, " ", 1, 0, "R", true);
                                        $archivo->Cell($valorAnchoColumnas, 4, " ", 1, 0, "R", true);
                                    }                                                        
                                                                                                          
                                    $archivo->Ln(4);
                                    $i++;
                                    
                                    if(isset($valorCuentaInicial[$cuentaContable])) {
                                                                                
                                        /*** ***/if($i%2==0){
                                            $rojo = 255;
                                            $azul = 255;
                                            $verde = 255;
                                        }else{
                                            $rojo = 226;
                                            $azul = 236;
                                            $verde = 237;
                                        }/*** ***/ 
                                        
                                        $archivo->SetFillColor($rojo,$verde,$azul);
                                        $archivo->Cell(100, 4, $textos["TOTAL"]." ".$nombreCuentaImpresion[$cuentaContable], 1, 0, "R", true);
                                        
                                        $id_cuenta = SQL::obtenerValor("plan_contable","id","codigo_contable='$cuentaContable'");
                                        $totalDebitoCuenta  = SQL::obtenerValor("movimientos_contables_consolidados","SUM(valor)","id_plan_contable='$id_cuenta' AND sentido='1' AND $cadenaFechas ");
                                        $archivo->Cell($valorAnchoColumnas, 4, "$".number_format($totalDebitoCuenta), 1, 0, "R", true);
                                        
                                        $totalCreditoCuenta  = SQL::obtenerValor("movimientos_contables_consolidados","SUM(valor)","id_plan_contable='$id_cuenta' AND sentido='2' AND $cadenaFechas");
                                        $archivo->Cell($valorAnchoColumnas, 4, "$".number_format($totalCreditoCuenta), 1, 0, "R", true);
                                        
                                        $archivo->Ln(4);     
                                        
                                        $i++;                                   
                                    }
                             }
                        } 
                        
                        
                        $archivo->Output($nombreArchivo, "F");
                        $cargaPdf = 1;
                }

                /*** Enviar datos con la respuesta del proceso al script que originó la petición ***/
                $respuesta    = array();
                $respuesta[0] = false;
                if ($cargaPdf == 1){
                    $respuesta[1] = HTML::enlazarPagina($textos["IMRPIMIR_PDF"], $nombreArchivo, array("target" => "_new"));
                } else{
                    $respuesta[0] = true;
                    $respuesta[1] = $mensaje;
                }
                HTTP::enviarJSON($respuesta);
        }
}
?>

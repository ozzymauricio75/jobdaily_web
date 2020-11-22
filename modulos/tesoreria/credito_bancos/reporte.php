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

/*** Devolver datos para autocompletar la búsqueda ***/
if (isset($url_completar)) {
    if (($url_item) == "selector1") {
        echo SQL::datosAutoCompletar("seleccion_bancos", $url_q);
    }
    
    if (($url_item) == "selector2") {
        echo SQL::datosAutoCompletar("seleccion_proyectos", $url_q);
    }

    if (($url_item) == "selector3") {
        echo SQL::datosAutoCompletar("menu_cuentas_bancarias", $url_q);
    }

    if (($url_item) == "selector4") {
        echo SQL::datosAutoCompletar("seleccion_proveedores", $url_q);
    }

    exit;

/*** Valida que exista el credito  ***/
} elseif (isset($url_existeCredito)){
    $numero_credito = $url_numero_credito;
    $estado_credito = SQL::obtenerValor("creditos_bancos","estado_credito","numero_credito='$numero_credito'");
    
    if($estado_credito){
        $codigo_credito = SQL::obtenerValor("creditos_bancos","codigo","numero_credito='$numero_credito'");
    } else{
        $codigo_credito = "";
    }

    HTTP::enviarJSON($codigo_credito);
    exit;
}

/*** Valida que el valor del movimiento no supere el saldo de la cuenta ***/
if(isset($url_cargaValorCredito)){
    $numero_credito = $url_numero_credito;

    $valor_credito = SQL::obtenerValor("creditos_bancos","valor_credito","numero_credito='$numero_credito'");
    $codigo_banco  = SQL::obtenerValor("creditos_bancos","codigo_banco","numero_credito='$numero_credito'");
    $banco         = SQL::obtenerValor("bancos","descripcion","codigo='$codigo_banco'");
    $valor_credito = number_format($valor_credito,0);

    $datos = array(
        $valor_credito,
        $banco
    );

    HTTP::enviarJSON($datos);
    exit;

/*** Generar el formulario para la captura de datos ***/
} if (!empty($url_generar)) {

    $consulta   = SQL::seleccionar(array("bancos"), array("*"), "codigo != '0'");
    $bancos_ver = SQL::filasDevueltas($consulta);

    if($bancos_ver==0){
        $error     = $textos["ERROR_BANCOS_VACIOS"];
        $titulo    = "";
        $contenido = "";
    }else{

        $error  = "";
        $titulo = $componente->nombre;

        $consulta = SQL::seleccionar(array("bancos"), array("codigo, descripcion"), "codigo!='0'");

        // Obtener lista de sucursales para seleccion
        if (SQL::filasDevueltas($consulta)) {
            while ($datos = SQL::filaEnObjeto($consulta)) {
                $bancos[$datos->codigo] = $datos->descripcion;
            }
        }

        $pestana_bancos   = array();
        $pestana_bancos[] = array(HTML::boton("botonSeleccionarTodos", $textos["SELECCIONAR_TODOS"], "seleccionar_todos_bancos();", "", array()));

        foreach($bancos AS $llave => $valor){

            $idBancos = $llave;

            $pestana_bancos[] = array(
                HTML::marcaChequeo("bancos[".$llave."]", $valor, $llave, false, array("title" => $textos["AYUDA_SUCURSAL_PRINCIPAL"], "codigo" => "bancos_".$llave))
            );
        }
        
        $grupos    = HTML::generarDatosLista("grupos_tesoreria", "codigo", "nombre_grupo","codigo>0");
        $conceptos = HTML::generarDatosLista("conceptos_tesoreria", "codigo_grupo_tesoreria", "nombre_concepto","codigo_grupo_tesoreria = '".array_shift(array_keys($grupos))."'");

        $tipo_listado = array(
            "1" => "PDF",
            "2" => "EXCEL"
        );
        $estado = array(
            "0" => "Pagado",
            "1" => "Por Pagar"
        );

        // Definicion de pestanas
        $fecha_inicial = date("Y/m/d")." - ".date("Y/m/d");
        $sucursales    = array();

        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::listaSeleccionSimple("tipo_listado",$textos["TIPO_LISTADO"], $tipo_listado,1,array("title"=>$textos["AYUDA_TIPO_LISTADO"]))
            ),
            array(
                HTML::agrupador(
                    array(
                        array(
                            HTML::marcaChequeo("por_credito",$textos["POR_CREDITO"], 1, false, array("title"=>$textos["AYUDA_POR_CREDITO"], "class"=>"por_credito","onClick"=>"activaCamposCreditos(this)"))
                            .HTML::campoOculto("por_credito_activo", "")
                        ),
                        array(
                            HTML::campoTextoCorto("selector5",$textos["NUMERO_CREDITO"], 15, 15, "", array("title"=>$textos["AYUDA_NUMERO_CREDITO"],"class" => "autocompletable por_banco oculto", "onChange"=>"existeCredito(), cargaValorCredito()")),

                            HTML::mostrarDato("banco", $textos["BANCO"], ""),

                            //HTML::campoTextoCorto("banco", $textos["BANCO"], 37, 40, "", array("title" => $textos["AYUDA_BANCO"],"class" => "por_banco oculto", "onBlur" => "validarItem(this);")),
    
                            HTML::mostrarDato("valor_credito", $textos["VALOR_CREDITO"], ""),
                            //HTML::campoTextoCorto("valor_credito", $textos["VALOR_CREDITO"], 37, 40, "", array("title" => $textos["AYUDA_VALOR_CREDITO"],"class" => "por_banco oculto", "onBlur" => "validarItem(this);"))
                        ),
                        array(
                            HTML::marcaChequeo("por_estado",$textos["POR_ESTADO"], 1, false, array("title"=>$textos["AYUDA_POR_ESTADO"], "class"=>"por_estado","onClick"=>"activaCamposEstado(this)"))
                            .HTML::campoOculto("por_estado_activo", "")
                        ),
                        array(
                           HTML::listaSeleccionSimple("estado_credito",$textos["ESTADO"], $estado, 1, array("title"=>$textos["AYUDA_POR_ESTADO"], "class" => "por_estado_credito oculto"))
                        )
                    ),
                    $textos["CREDITOS"]
                )
            ),
            array(
                HTML::agrupador(
                    array(
                        array(
                            HTML::marcaChequeo("por_banco",$textos["POR_BANCO"], 1, false, array("title"=>$textos["AYUDA_POR_BANCO"], "class"=>"por_banco_credito","onClick"=>"activaCamposBancos(this)"))
                            .HTML::campoOculto("por_banco_activo", "")
                        ),
                        array(    
                            HTML::campoTextoCorto("selector1", $textos["BANCO"], 45, 255, "", array("title" => $textos["AYUDA_BANCO"], "class" => "autocompletable por_banco_seleccionado oculto" ))
                        )
                    ),
                    $textos["BANCOS"]
                )
            )
        );
    
        $formularios["PESTANA_GENERAL"] = array_merge($formularios["PESTANA_GENERAL"],$sucursales);

        // Definicion de botones
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "exportarDatosIndice(1);", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones);
    }

    // Enviar datos para la generacion del formulario al script que origino la peticion
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

// Adicionar los datos provenientes del formulario
} elseif (!empty($forma_procesar)) {
    // Asumir por defecto que no hubo error
    $error        = false;
    $cargaPdf     = 0;
    $mensaje      = $textos["PLANO_GENERADO"];

    $fechas            = explode('-',$forma_fechas);
    $forma_fecha_desde = trim($fechas[0]);
    $forma_fecha_desde = str_replace("/","-",$forma_fecha_desde);
    $forma_fecha_hasta = trim($fechas[1]);
    $forma_fecha_hasta = str_replace("/","-",$forma_fecha_hasta);

    $llave_banco       = explode(":", $forma_selector1);
    $codigo_banco      = $llave_banco[0];
    $codigo_banco      = trim($codigo_banco);
    $numero_credito    = $forma_selector5;

    $nombre         = "";
    $nombreArchivo  = "";

    do {
        $cadena = Cadena::generarCadenaAleatoria(8);
        if ($forma_tipo_listado=="1"){
            $nombre = $cadena.".pdf";

        } else{
            $nombre = $cadena.".csv";
        }
        $nombreArchivo = $rutasGlobales["bancos"]."/bancos".$nombre;
    } while (is_file($nombreArchivo));
        
    if (file_exists($nombreArchivo)){
        unlink($nombreArchivo);
        $archivo = fopen($nombreArchivo,"a+");
    } else {
        $archivo = fopen($nombreArchivo,"a+");
    } 

    if($forma_por_credito_activo==2){
        $condicion_credito = "numero_credito='$numero_credito'";
    } else{
        $condicion_credito = "numero_credito!='0'";
    }

    if($forma_por_banco_activo==2){
        $condicion_banco = " AND codigo_banco='$codigo_banco'";
    } else{
        $condicion_banco = " AND codigo_banco!='0'";
    }

    if($forma_por_estado_activo==2){
        $condicion_estado = " AND estado_credito='$forma_estado_credito'";
    } else{
        $condicion_estado = " AND estado_credito!=''";
    }

    //Titulos segun tipo listado
    if ($forma_tipo_listado=="1"){
        //////////////////////////////ENCABEZADO DEL DOCUMENTO PDF REPORTE DE MOVIMIENTO/////////////////////////////
        $anchoColumnas           = array(20,50);
        $alineacionColumnas      = array("I","I");
        $saldo_actual            = 0;

        $archivo                 = new PDF("L","mm","Letter");
        $archivo->textoCabecera  = $textos["FECHA"].": ".date("Y-m-d");
        $archivo->textoTitulo    = $textos["REPOCRBA"];
        $archivo->textoPiePagina = $textos["PIE_PAGINA_EMPRESA"];

        $archivo->AddPage();
        $archivo->SetFont('Arial','B',8);
  
        $archivo->SetFillColor(225,225,225);

        $archivo->Ln(6);
        $archivo->Cell(40,4,$textos["BANCOS"],1,0,'C',true);
        $archivo->Cell(25,4,$textos["TIPO_CREDITO"],1,0,'C',true);
        $archivo->Cell(30,4,$textos["NRO_CREDITO"],1,0,'C',true);
        $archivo->Cell(22,4,$textos["CUPO"],1,0,'C',true);
        $archivo->Cell(10,4,$textos["TASA"],1,0,'C',true);
        $archivo->Cell(22,4,$textos["SALDO"],1,0,'C',true);
        $archivo->Cell(22,4,$textos["FECHA_CREDITO"],1,0,'C',true);
        $archivo->Cell(22,4,$textos["FECHA_TERMINA"],1,0,'C',true);
        $archivo->Cell(10,4,$textos["PLAZO"],1,0,'C',true);
        $archivo->Cell(14,4,$textos["DIA_PAGO"],1,0,'C',true);
        $archivo->Cell(22,4,$textos["ULTIMO_PAGADO"],1,0,'C',true);
        $archivo->Cell(22,4,$textos["PROXIMO_PAGO"],1,0,'C',true);
        //////////////////////////////FIN ENCABEZADO DEL DOCUMENTO PDF ORDEN DE COMPRA/////////////////////////////
        
        /*** Obtener los datos de la tabla creditos bancos ***/
        $condiciones     = $condicion_credito.$condicion_banco.$condicion_estado;
        $creditos_bancos = SQL::seleccionar(array("creditos_bancos"),array("*"),"$condiciones");

        if (SQL::filasDevueltas($creditos_bancos)){
            while($datos_creditos_bancos = SQL::filaEnObjeto($creditos_bancos)){
                if($archivo->FillColor != sprintf('%.3F %.3F %.3F rg',1,1,1)){
                    $archivo->SetFillColor(255,255,255);
                } else{
                    $archivo->SetFillColor(240,240,240);
                }
                //Se lee el movimiento de la tabla creditos bancos
                $codigo_credito = SQL::obtenerValor("creditos_bancos","codigo","numero_credito='$datos_creditos_bancos->numero_credito'");
                $codigo_banco   = SQL::obtenerValor("creditos_bancos","codigo_banco","numero_credito='$datos_creditos_bancos->numero_credito'");
                $nombre_banco   = SQL::obtenerValor("bancos","descripcion","codigo='$codigo_banco'");
                $fecha_actual   = date("Y-m-d");

                $ultima_fecha_paga   =  SQL::obtenerValor("cuotas_creditos_bancos","fecha_cuota","codigo_credito='$codigo_credito' AND saldo_capital_pagado!='' AND estado_cuota='0'");
              
                if($ultima_fecha_paga){
                    $saldo_fecha     = SQL::obtenerValor("cuotas_creditos_bancos","saldo_capital_pagado","codigo_credito='$codigo_credito' AND fecha_cuota='$ultima_fecha_paga'");    
                } else{
                    $primera_cuota = SQL::obtenerValor("cuotas_creditos_bancos","abono_capital","codigo_credito='$codigo_credito' AND estado_cuota='1' AND numero_cuota='1'");
                    $saldo_fecha   = SQL::obtenerValor("cuotas_creditos_bancos","saldo_capital","codigo_credito='$codigo_credito' AND numero_cuota='1'");
                    $saldo_fecha   = $saldo_fecha+$primera_cuota;
                }
                
                $fecha_fin_credito   = SQL::obtenerValor("cuotas_creditos_bancos","MAX(fecha_cuota)","codigo_credito='$codigo_credito'");
                $ultimo_pagado       = SQL::obtenerValor("cuotas_creditos_bancos","abono_capital_pagado","codigo_credito='$codigo_credito' AND fecha_cuota='$ultima_fecha_paga'");
                $ultima_cuota_pagada = SQL::obtenerValor("cuotas_creditos_bancos","numero_cuota","codigo_credito='$codigo_credito' AND fecha_cuota='$ultima_fecha_paga'");
                $siguiente_cuota     = $ultima_cuota_pagada+1;
                $proximo_pago        = SQL::obtenerValor("cuotas_creditos_bancos","abono_capital","codigo_credito='$codigo_credito' AND numero_cuota='$siguiente_cuota'");

                if ($datos_creditos_bancos->tipo_credito == '1'){
                    $tipo_credito = $textos["CREDITO"];
                }
                if ($datos_creditos_bancos->tipo_credito == '2'){
                    $tipo_credito = $textos["CREDIPAGO"];
                }

                /////////////////////////////////////////////////////////////////////////////////////////////////
                $archivo->Ln(4);
                $archivo->SetFont('Arial','',6);
                $archivo->Cell(40,4,$nombre_banco,1,0,'L',true);
                $archivo->Cell(25,4,$tipo_credito,1,0,'L',true);
                $archivo->Cell(30,4,$datos_creditos_bancos->numero_credito,1,0,'D',true);
                $archivo->Cell(22,4,""."$ ".number_format($datos_creditos_bancos->valor_credito,0),1,0,'R',true);
                $archivo->Cell(10,4,$datos_creditos_bancos->tasa_dtf,1,0,'C',true);
                $archivo->Cell(22,4,""."$ ".number_format($saldo_fecha,0),1,0,'R',true);
                $archivo->Cell(22,4,$datos_creditos_bancos->fecha_credito,1,0,'C',true);
                $archivo->Cell(22,4,$fecha_fin_credito,1,0,'C',true);
                $archivo->Cell(10,4,$datos_creditos_bancos->periodos,1,0,'L',true);
                $archivo->Cell(14,4,$datos_creditos_bancos->fecha_pago_cuota,1,0,'L',true);
                $archivo->Cell(22,4,""."$ ".number_format($ultimo_pagado,0),1,0,'R',true);
                $archivo->Cell(22,4,""."$ ".number_format($proximo_pago,0),1,0,'R',true);

                /////////////////////////////////////////////////////////////////////////////////////////////////
                $imprime_cabecera = $archivo->breakCell(8);

                if($imprime_cabecera){
                    $archivo->Ln(4);
                    $archivo->SetFont('Arial','B',8);
                    $archivo->Ln(0);
  
                    $archivo->SetFont('Arial','B',6);
                    $archivo->SetFillColor(225,225,225);

                    $archivo->Ln(6);
                    $archivo->Cell(40,4,$textos["BANCOS"],1,0,'C',true);
                    $archivo->Cell(25,4,$textos["TIPO_CREDITO"],1,0,'C',true);
                    $archivo->Cell(30,4,$textos["NRO_CREDITO"],1,0,'C',true);
                    $archivo->Cell(22,4,$textos["CUPO"],1,0,'C',true);
                    $archivo->Cell(10,4,$textos["TASA"],1,0,'C',true);
                    $archivo->Cell(22,4,$textos["SALDO"],1,0,'C',true);
                    $archivo->Cell(22,4,$textos["FECHA_CREDITO"],1,0,'C',true);
                    $archivo->Cell(22,4,$textos["FECHA_TERMINA"],1,0,'C',true);
                    $archivo->Cell(10,4,$textos["PLAZO"],1,0,'C',true);
                    $archivo->Cell(14,4,$textos["DIA_PAGO"],1,0,'C',true);
                    $archivo->Cell(22,4,$textos["ULTIMO_PAGADO"],1,0,'C',true);
                    $archivo->Cell(22,4,$textos["PROXIMO_PAGO"],1,0,'C',true);
                }
                $i++;
                $item++;
            }
        }

        if($archivo->FillColor != sprintf('%.3F %.3F %.3F rg',1,1,1)){
            $archivo->SetFillColor(255,255,255);
        } else{
            $archivo->SetFillColor(240,240,240);
        }

        $archivo->Output($nombreArchivo, "F");
        $consecutivo = SQL::obtenerValor("archivos","MAX(consecutivo)","codigo_sucursal='".$codigo_sucursal."'");

        if ($consecutivo){
            $consecutivo++;
        } else {
            $consecutivo = 1;
        }

        $datos_archivo = array(
            "codigo_sucursal" => $codigo_sucursal,
            "consecutivo"     => $consecutivo,
            "nombre"          => $nombre
        );
        SQL::insertar("archivos", $datos_archivo);
        $mensaje = HTML::enlazarPagina($textos["GENERAR_PLANO"], $nombreArchivo, array("target" => "_new"));
        
    } else{
        //Se crean los titulos del archivo excel
        $titulos_plano = "BANCOS;TIPO_CREDITO;NRO_CREDITO;CUPO;TASA;SALDO;FECHA_CREDITO;FECHA_TERMINA;PLAZO;DIA_PAGO;ULTIMO_PAGADO;PROXIMO_PAGO\n";
            fwrite($archivo, $titulos_plano);

        /*** Obtener los datos de la tabla creditos bancos ***/
        $condiciones     = $condicion_credito.$condicion_banco.$condicion_estado;
        $creditos_bancos = SQL::seleccionar(array("creditos_bancos"),array("*"),"$condiciones");

        if (SQL::filasDevueltas($creditos_bancos)){
            while($datos_creditos_bancos = SQL::filaEnObjeto($creditos_bancos)){
                //Se lee el movimiento de la tabla creditos bancos
                $codigo_credito = SQL::obtenerValor("creditos_bancos","codigo","numero_credito='$datos_creditos_bancos->numero_credito'");
                $codigo_banco   = SQL::obtenerValor("creditos_bancos","codigo_banco","numero_credito='$datos_creditos_bancos->numero_credito'");
                $nombre_banco   = SQL::obtenerValor("bancos","descripcion","codigo='$codigo_banco'");
                $fecha_actual   = date("Y-m-d");

                $ultima_fecha_paga   =  SQL::obtenerValor("cuotas_creditos_bancos","fecha_cuota","codigo_credito='$codigo_credito' AND saldo_capital_pagado!='' AND estado_cuota='0'");
              
                if($ultima_fecha_paga){
                    $saldo_fecha     = SQL::obtenerValor("cuotas_creditos_bancos","saldo_capital_pagado","codigo_credito='$codigo_credito' AND fecha_cuota='$ultima_fecha_paga'");    
                } else{
                    $primera_cuota = SQL::obtenerValor("cuotas_creditos_bancos","abono_capital","codigo_credito='$codigo_credito' AND estado_cuota='1' AND numero_cuota='1'");
                    $saldo_fecha   = SQL::obtenerValor("cuotas_creditos_bancos","saldo_capital","codigo_credito='$codigo_credito' AND numero_cuota='1'");
                    $saldo_fecha   = $saldo_fecha+$primera_cuota;
                }
                
                $fecha_fin_credito   = SQL::obtenerValor("cuotas_creditos_bancos","MAX(fecha_cuota)","codigo_credito='$codigo_credito'");
                $ultimo_pagado       = SQL::obtenerValor("cuotas_creditos_bancos","abono_capital_pagado","codigo_credito='$codigo_credito' AND fecha_cuota='$ultima_fecha_paga'");
                $ultima_cuota_pagada = SQL::obtenerValor("cuotas_creditos_bancos","numero_cuota","codigo_credito='$codigo_credito' AND fecha_cuota='$ultima_fecha_paga'");
                $siguiente_cuota     = $ultima_cuota_pagada+1;
                $proximo_pago        = SQL::obtenerValor("cuotas_creditos_bancos","abono_capital","codigo_credito='$codigo_credito' AND numero_cuota='$siguiente_cuota'");

                if ($datos_creditos_bancos->tipo_credito == '1'){
                    $tipo_credito = $textos["CREDITO"];
                }
                if ($datos_creditos_bancos->tipo_credito == '2'){
                    $tipo_credito = $textos["CREDIPAGO"];
                }
                /////////////////////////////////////////////////////////////////////////////////////////////////
                $valor_credito      = number_format($datos_creditos_bancos->valor_credito,0);
                $saldo_fecha        = number_format($saldo_fecha,0);
                $ultimo_pagado      = number_format($ultimo_pagado, 0);
                $proximo_pago       = number_format($proximo_pago,0);

                $valor_credito      = str_replace(',', '.', $valor_credito);
                $saldo_fecha        = str_replace(',', '.', $saldo_fecha);
                $ultimo_pagado      = str_replace(',', '.', $ultimo_pagado);
                $proximo_pago       = str_replace(',', '.', $proximo_pago);

                //Contenido del archivo
                $contenido = "$nombre_banco;$tipo_credito;$datos_creditos_bancos->numero_credito;$valor_credito;$datos_creditos_bancos->tasa_dtf;$saldo_fecha;$datos_creditos_bancos->fecha_credito;$fecha_fin_credito;$datos_creditos_bancos->periodos;$datos_creditos_bancos->fecha_pago_cuota;$ultimo_pagado;$proximo_pago\n";
                $guardarArchivo = fwrite($archivo,$contenido);
            }
        }
        $consecutivo = SQL::obtenerValor("archivos","MAX(consecutivo)","codigo_sucursal='".$codigo_sucursal."'");

        if ($consecutivo){
            $consecutivo++;
        } else {
            $consecutivo = 1;
        }

        $datos_archivo = array(
            "codigo_sucursal" => $codigo_sucursal,
            "consecutivo"     => $consecutivo,
            "nombre"          => $nombre
        );
        fclose($archivo);
        $mensaje = HTML::enlazarPagina($textos["GENERAR_PLANO"], $nombreArchivo, array("target" => "_new"));  
    }
    // Enviar datos con la respuesta del proceso al script que origino la peticion
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    $respuesta[2] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

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

// Devolver datos para autocompletar la búsqueda
if (isset($url_completar)) {
    if ($url_item == "selector1" || $url_item == "selector2") {
       echo SQL::datosAutoCompletar("seleccion_todo_plan_contable", $url_q);
    }
    exit;
}
// Generar el formulario para la captura de datos
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
        $pestana_sucursales[] = array(HTML::boton("botonSeleccionarTodos", $textos["SELECCIONAR_TODOS"], "seleccionar_todas_sucursales();", "", array()));

        foreach($sucursales AS $llave => $valor){

            $idSucursal = $llave;

            $pestana_sucursales[]   = array(
                HTML::marcaChequeo("sucursales[".$llave."]", $valor, $llave, false, array("title" => $textos["AYUDA_SUCURSAL_PRINCIPAL"], "id" => "sucursales_".$llave, "class" => "sucursales_electrodomesticos")),
                HTML::listaSeleccionSimple("consolidar[".$llave."]", $textos["CONSOLIDAR_EN"], HTML::generarDatosLista("sucursales", "codigo", "nombre","codigo!='0'"), $llave, array("title" => $textos["AYUDA_SUCURSAL_CONSOLIDA"]))
            );
        }

        $formularios["PESTANA_SUCURSALES"] = $pestana_sucursales;

        // Definición de pestaña general
        $fecha_inicial = date("Y/m/d")." - ".date("Y/m/d");

        $formularios["PESTANA_ESTADO_CUENTA"] = array(
            array(
                HTML::campoTextoCorto("*fechas", $textos["FECHA_INICIAL_PYG"]."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$textos["FECHA_FINAL_PYG"], 25, 25,$fecha_inicial, array("title" => $textos["FECHAS"], "class" => "fechaRango"))
            ),
            array(
                HTML::campoTextoCorto("selector1", $textos["CUENTA_INICIAL"], 30, 100, "", array("title" => $textos["AYUDA_CUENTA_INICIAL"], "class" => "autocompletable_1"))
                .HTML::campoOculto("id_cuenta_inicial", "")
            ),
            array(
                HTML::campoTextoCorto("selector2", $textos["CUENTA_FINAL"], 30, 100, "", array("title" => $textos["AYUDA_CUENTA_FINAL"], "class" => "autocompletable_1"))
                .HTML::campoOculto("id_cuenta_final", "")
            ),
            array (
                HTML::contenedor(
                    (
                        HTML::marcaChequeo("detalla_auxiliares", $textos["DETALLA_AUXILIARES"], "1", false, array("title" => $textos["AYUDA_DETALLA_AUXILIARES"]))
                        .HTML::marcaChequeo("detalla_terceros", $textos["DETALLA_TERCEROS"], "1", false, array("title" => $textos["AYUDA_DETALLA_TERCEROS"]))
                    ),
                    array("id" => "detalles_balance")
                )
            ),
            array (
                HTML::boton("boton_balance_general", $textos["GENERAR"], "imprimirItem('0');", "aceptar")
            )
        );
        $contenido = HTML::generarPestanas($formularios);
    }
    // Enviar datos para la generación del formulario al script que originï¿½ la peticiï¿½n
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

// Exportar los datos
} elseif (!empty($forma_procesar)) {

    $datos_incompletos= false;

    $fechas              = explode('-',$forma_fechas);
    $forma_fecha_inicial = trim($fechas[0]);
    $forma_fecha_final   = trim($fechas[1]);

    if (!empty($forma_id_cuenta_inicial)){
        $cuenta_inicial         = explode(",",$forma_selector1);
        $cuenta_inicial         = $cuenta_inicial[0];
        $cuenta_final           = explode(",",$forma_selector2);
        $cuenta_final           = $cuenta_final[0];
        $condicion_cuenta       = " AND codigo_contable >='".$cuenta_inicial."' AND codigo_contable<='".$cuenta_final."'";
        $cuenta_inicial_compara = "'".$cuenta_inicial."'";
        $cuenta_final_compara   = "'".$cuenta_final."'";
    } else if (!empty($forma_id_cuenta_final)){
        $cuenta_inicial         = "";
        $cuenta_final           = explode(",",$forma_selector2);
        $cuenta_final           = $cuenta_final[0];
        $condicion_cuenta       = " AND codigo_contable<='".$cuenta_final."'";
        $cuenta_inicial_compara = "'".$cuenta_inicial."'";
        $cuenta_final_compara   = "'".$cuenta_final."'";
    } else {
        $condicion_cuenta = "";
    }

    if (!isset($forma_sucursales)){
        $error             = true;
        $mensaje           = $textos["ERROR_SUCURSALES"];
        $datos_incompletos = true;
    } else if ($forma_fecha_inicial > $forma_fecha_final){
        $error             = true;
        $mensaje           = $textos["ERROR_FECHAS"];
        $datos_incompletos = true;
    } else if (!empty($forma_id_cuenta_inicial) && empty($forma_id_cuenta_final)){
        $error             = true;
        $mensaje           = $textos["ERROR_CUENTA_FINAL"];
        $datos_incompletos = true;
    } else if(isset($cuenta_inicial_compara) && isset($cuenta_final_compara) && $cuenta_inicial_compara>$cuenta_final_compara){
        $error             = true;
        $mensaje           = $textos["ERROR_CUENTA_INICIAL"];
        $datos_incompletos = true;
    } else {

        $detalla_auxiliar      = false;
        $ordenamiento_auxiliar = "";
        if(isset($forma_detalla_auxiliares)){
            $detalla_auxiliar      = true;
            $ordenamiento_auxiliar = ", codigo_empresa_auxiliar, codigo_anexo_contable, codigo_auxiliar_contable";
        }

        $detalla_tercero      = false;
        $ordenamiento_tercero = "";
        if(isset($forma_detalla_terceros)){
            $detalla_tercero      = true;
            $ordenamiento_tercero = ", documento_identidad_tercero";
        }

        $nombre         = "";
        $nombreArchivo  = "";
        do {
            $cadena         = Cadena::generarCadenaAleatoria(8);
            $nombre         = $sesion_sucursal.$cadena.".pdf";
            $nombreArchivo  = $rutasGlobales["archivos"]."/".$nombre;
        } while (is_file($nombreArchivo));

        $cargaPdf = 0;
        $mensaje  = $textos["ERROR_GENERAR_ARCHIVO"];

        $fechaReporte = date("Y-m-d");
        $archivo      = new PDF("P","mm","Letter");

        $sucursales_reporte = array();

        foreach ($forma_sucursales AS $sucursal) {

            if (!isset($sucursales_reporte[$forma_consolidar[$sucursal]])){
                $sucursales_reporte[$forma_consolidar[$sucursal]] = "$sucursal,";
            } else {
                $sucursales_reporte[$forma_consolidar[$sucursal]] .= "$sucursal,";
            }
        }

        $imprime_pdf = false;
        include("clases/balances.php");

        $total_saldo_empresa   = 0;
        $total_debito_empresa  = 0;
        $total_credito_empresa = 0;

        foreach ($sucursales_reporte AS $sucursal => $condicion_sucursal) {

            $total_saldo_sucursal   = 0;
            $total_debito_sucursal  = 0;
            $total_credito_sucursal = 0;
            $saldo_cuenta           = 0;
            $total_debito_cuenta    = 0;
            $total_credito_cuenta   = 0;
            $saldo_auxiliar         = 0;
            $total_debito_auxiliar  = 0;
            $total_credito_auxiliar = 0;
            $saldo_tercero          = 0;
            $total_debito_tercero   = 0;
            $total_credito_tercero  = 0;
            $sucursal_positivo      = 0;
            $sucursal_negativo      = 0;

            $condicion_sucursal = trim($condicion_sucursal, ",");
            $consolidadas       = explode(",", $condicion_sucursal);

            $fecha_inicial = $forma_fecha_inicial." 00:00:00";
            $fecha_final   = $forma_fecha_final." 23:59:59";
            $tablas        = array("movimientos_contables_consolidados");
            $condicion     = "estado = 1 AND fecha_contabilizacion >= '".$fecha_inicial."' AND fecha_contabilizacion <= '".$fecha_final."'AND codigo_sucursal_genera IN (".$condicion_sucursal.")".$condicion_cuenta;
            $datos         = array(
                "codigo_contable",
                "descripcion_cuenta",
                "codigo_empresa_auxiliar",
                "codigo_anexo_contable",
                "codigo_auxiliar_contable",
                "sentido_cuenta",
                "sentido_movimiento",
                "documento_identidad_tercero",
                "codigo_sucursal_genera",
                "fecha_contabilizacion",
                "valor",
                "codigo_tipo_documento",
                "numero_consecutivo",
                "codigo_tipo_comprobante",
                "numero_comprobante"
            );

            $ordenamiento = "codigo_contable ASC ".$ordenamiento_auxiliar." ".$ordenamiento_tercero.", fecha_contabilizacion ASC, codigo_tipo_comprobante, codigo_tipo_documento";

            $consulta_movimiento  = SQL::seleccionar($tablas, $datos, $condicion, "", $ordenamiento);

            if (SQL::filasDevueltas($consulta_movimiento)){

                $codigo_contable_anterior = "";
                $auxiliar_anterior        = 0;
                $tercero_anterior         = "";
                $maneja_tercero_anterior  = '0';
                $imprime_titulo_tabla     = false;
                $indicador_colores        = 0;
                // Generar PDF
                $condicion_sucursal = trim($condicion_sucursal, ",");
                $consolidadas       = explode(",", $condicion_sucursal);

                $archivo->Ln(4);
                $nombreSucursal         = SQL::obtenerValor("sucursales","nombre","codigo='$sucursal'");
                $archivo->textoTitulo   = $textos["ESTADO_CUENTA"]." ".$nombreSucursal;
                $archivo->textoCabecera = $textos["FECHA_INICIAL_PYG"].": ".$forma_fecha_inicial. " ".$textos["FECHA_FINAL_PYG"].": ".$forma_fecha_final;
                $archivo->SetFont('Arial','B',6);

                if ((count($consolidadas) > 1) || (count($consolidadas) == 1 && $consolidadas[0] == $sucursal)){
                    $nombres_consolidadas = "";
                    foreach ($consolidadas AS $consolidada) {
                        $nombres_consolidadas .= SQL::obtenerValor("sucursales", "nombre", "codigo = ".$consolidada).", ";
                    }
                    $nombres_consolidadas = trim($nombres_consolidadas, ", ");
                }// Final: if ((count($consolidadas) > 1) || (count($consolidadas) == 1 && $consolidadas[0] == $sucursal))
                $archivo->AddPage();
                $archivo->textoPiePagina = $textos["CONSOLIDADAS"].": ".$nombres_consolidadas;

                $archivo->Ln(4);
                $archivo->SetFont('Arial','B',6);
                $tituloColumnas = array(
                    $textos["FECHA"], $textos["TIPO_COMPROBANTE"], $textos["NUMERO_COMPROBANTE"], $textos["TIPO_DOCUMENTO"], $textos["NUMERO_DOCUMENTO"],$textos["DEBITO"],$textos["CREDITO"],$textos["SALDO"]
                );
                $anchoColumnas = array(15,30,25,35,20,25,25,25);
                $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
                $indicador_colores=1;

                while ($datos_movimiento = SQL::filaEnObjeto($consulta_movimiento)) {

                    $codigo_contable    = $datos_movimiento->codigo_contable;
                    $descripcion        = $datos_movimiento->descripcion_cuenta;
                    $sentido_cuenta     = $datos_movimiento->sentido_cuenta;
                    $sentido_movimiento = $datos_movimiento->sentido_movimiento;
                    if ($detalla_auxiliar && $datos_movimiento->codigo_auxiliar_contable > 0){
                        $auxiliar = $datos_movimiento->codigo_empresa_auxiliar.'|'.$datos_movimiento->codigo_anexo_contable.'|'.str_pad($datos_movimiento->codigo_auxiliar_contable,8,"0", STR_PAD_LEFT);
                    } else {
                        $auxiliar = 0;
                    }
                    if ($detalla_tercero){
                        $maneja_tercero = SQL::obtenerValor("plan_contable","maneja_tercero","codigo_contable='".$codigo_contable."'");
                        $tercero = $datos_movimiento->documento_identidad_tercero;
                    } else {
                        $maneja_tercero = 0;
                        $tercero = 0;
                    }

                    $fecha_movimiento   = $datos_movimiento->fecha_contabilizacion;
                    $tipo_documento     = SQL::obtenerValor("tipos_documentos","descripcion","codigo='".$datos_movimiento->codigo_tipo_documento."'");
                    $numero_documento   = $datos_movimiento->numero_consecutivo;
                    $tipo_comprobante   = SQL::obtenerValor("tipos_comprobantes","descripcion","codigo='".$datos_movimiento->codigo_tipo_comprobante."'");
                    $numero_comprobante = $datos_movimiento->numero_comprobante;

                    if ($codigo_contable != $codigo_contable_anterior && $codigo_contable_anterior!=""){
                        $tercero_anterior  = -1;
                        $auxiliar_positivo = $auxiliar;
                        $auxiliar = -1;
                    }

                    if ($detalla_tercero && $maneja_tercero_anterior == '1' && $tercero != $tercero_anterior && $tercero_anterior != ""){

                        if ($saldo_tercero < 0){
                            $saldo_tercero = $saldo_tercero * (-1);
                            $saldo_tercero = "$ (".number_format($saldo_tercero).")";
                        } else if ($saldo_tercero > 0){
                            $saldo_tercero = "$ ".number_format($saldo_tercero);
                        } else {
                            $saldo_tercero = "$ 0";
                        }

                        if ($total_debito_tercero < 0){
                            $total_debito_tercero = $total_debito_tercero * (-1);
                            $total_debito_tercero = "$ (".number_format($total_debito_tercero).")";
                        } else  if ($total_debito_tercero > 0){
                            $total_debito_tercero = "$ ".number_format($total_debito_tercero);
                        } else {
                            $total_debito_tercero = "$ 0";
                        }

                        if ($total_credito_tercero < 0){
                            $total_credito_tercero = $total_credito_tercero * (-1);
                            $total_credito_tercero = "$ (".number_format($total_credito_tercero).")";
                        } else  if ($total_credito_tercero > 0){
                            $total_credito_tercero = "$ ".number_format($total_credito_tercero);
                        } else {
                            $total_credito_tercero = "$ 0";
                        }
                        $archivo->SetFont('Arial','',6);
                        //$archivo->SetFillColor(255,160,22);
                        //$archivo->SetFillColor(226,226,240);
                        $archivo->SetFillColor(240,240,240);
                        $archivo->Ln(4);
                        $archivo->Cell(125, 4, $textos["TOTAL_TERCERO"], 1, 0, "L", true);
                        $archivo->Cell(25, 4, $total_debito_tercero, 1, 0, "R", true);
                        $archivo->Cell(25, 4, $total_credito_tercero, 1, 0, "R", true);
                        $archivo->Cell(25, 4, $saldo_tercero, 1, 0, "R", true);
                        $saldo_tercero         = 0;
                        $total_debito_tercero  = 0;
                        $total_credito_tercero = 0;
                    }

                    if ($detalla_auxiliar && $auxiliar_anterior > 0 && $auxiliar != $auxiliar_anterior){

                        if ($saldo_auxiliar < 0){
                            $saldo_auxiliar = $saldo_auxiliar * (-1);
                            $saldo_auxiliar = "$ (".number_format($saldo_auxiliar).")";
                        } else if ($saldo_auxiliar > 0){
                            $saldo_auxiliar = "$ ".number_format($saldo_auxiliar);
                        } else {
                            $saldo_auxiliar = "$ 0";
                        }

                        if ($total_debito_auxiliar < 0){
                            $total_debito_auxiliar = $total_debito_auxiliar * (-1);
                            $total_debito_auxiliar = "$ (".number_format($total_debito_auxiliar).")";
                        } else  if ($total_debito_auxiliar > 0){
                            $total_debito_auxiliar = "$ ".number_format($total_debito_auxiliar);
                        } else {
                            $total_debito_auxiliar = "$ 0";
                        }

                        if ($total_credito_auxiliar < 0){
                            $total_credito_auxiliar = $total_credito_auxiliar * (-1);
                            $total_credito_auxiliar = "$ (".number_format($total_credito_auxiliar).")";
                        } else  if ($total_credito_auxiliar > 0){
                            $total_credito_auxiliar = "$ ".number_format($total_credito_auxiliar);
                        } else {
                            $total_credito_auxiliar = "$ 0";
                        }
                        $archivo->SetFont('Arial','',6);
                        //$archivo->SetFillColor(255,180,42);
                        //$archivo->SetFillColor(226,226,240);
                        $archivo->SetFillColor(240,240,240);
                        $archivo->Ln(4);
                        $archivo->Cell(125, 4, $textos["TOTAL_AUXILIAR"], 1, 0, "L", true);
                        $archivo->Cell(25, 4, $total_debito_auxiliar, 1, 0, "R", true);
                        $archivo->Cell(25, 4, $total_credito_auxiliar, 1, 0, "R", true);
                        $archivo->Cell(25, 4, $saldo_auxiliar, 1, 0, "R", true);
                        $saldo_auxiliar         = 0;
                        $total_debito_auxiliar  = 0;
                        $total_credito_auxiliar = 0;
                    }

                    if ($codigo_contable != $codigo_contable_anterior){

                        if ($codigo_contable_anterior!=""){

                            if ($saldo_cuenta < 0){
                                $saldo_cuenta = $saldo_cuenta * (-1);
                                $saldo_cuenta = "$ (".number_format($saldo_cuenta).")";
                            } else  if ($saldo_cuenta > 0){
                                $saldo_cuenta = "$ ".number_format($saldo_cuenta);
                            } else {
                                $saldo_cuenta = "$ 0";
                            }

                            if ($total_debito_cuenta < 0){
                                $total_debito_cuenta = $total_debito_cuenta * (-1);
                                $total_debito_cuenta = "$ (".number_format($total_debito_cuenta).")";
                            } else  if ($total_debito_cuenta > 0){
                                $total_debito_cuenta = "$ ".number_format($total_debito_cuenta);
                            } else {
                                $total_debito_cuenta = "$ 0";
                            }

                            if ($total_credito_cuenta < 0){
                                $total_credito_cuenta = $total_credito_cuenta * (-1);
                                $total_credito_cuenta = "$ (".number_format($total_credito_cuenta).")";
                            } else  if ($total_credito_cuenta > 0){
                                $total_credito_cuenta = "$ ".number_format($total_credito_cuenta);
                            } else {
                                $total_credito_cuenta = "$ 0";
                            }
                            $archivo->SetFont('Arial','',6);
                            //$archivo->SetFillColor(255,140,93);
                            //$archivo->SetFillColor(226,226,240);
                            $archivo->SetFillColor(240,240,240);
                            $archivo->Ln(4);
                            $archivo->Cell(125, 4, $textos["TOTAL_CODIGO"].$codigo_contable_anterior, 1, 0, "L", true);
                            $archivo->Cell(25, 4, $total_debito_cuenta, 1, 0, "R", true);
                            $archivo->Cell(25, 4, $total_credito_cuenta, 1, 0, "R", true);
                            $archivo->Cell(25, 4, $saldo_cuenta, 1, 0, "R", true);
                            $archivo->Ln(4);
                            $saldo_cuenta         = 0;
                            $total_debito_cuenta  = 0;
                            $total_credito_cuenta = 0;
                        }
                    }

                    if ($codigo_contable != $codigo_contable_anterior){

                        $imprime_cabecera = $archivo->breakCell(12);
                        if ($imprime_cabecera){
                            $archivo->AddPage();
                            $archivo->Ln(10);
                            $archivo->SetFont('Arial','B',6);
                            $tituloColumnas = array(
                                $textos["FECHA"], $textos["TIPO_COMPROBANTE"], $textos["NUMERO_COMPROBANTE"], $textos["TIPO_DOCUMENTO"], $textos["NUMERO_DOCUMENTO"],$textos["DEBITO"],$textos["CREDITO"],$textos["SALDO"]
                            );
                            $anchoColumnas = array(15,30,25,35,20,25,25,25);
                            $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
                        }

                        $tituloColumna = $textos["CODIGO"].": ".$codigo_contable." ".$descripcion;
                        $archivo->SetFont('Arial','B',9);
                        //$archivo->SetFillColor(255,140,93);
                        //$archivo->SetFillColor(226,226,240);
                        $archivo->SetFillColor(240,240,240);
                        $archivo->Ln(4);
                        $archivo->Cell(200, 4, $tituloColumna, 1, 0, "L", true);
                    }

                    if ($auxiliar < 0){
                        $auxiliar = $auxiliar_positivo;
                        $auxiliar_anterior = "";
                    }
                    if ($detalla_auxiliar && $auxiliar>0 && $auxiliar != $auxiliar_anterior){

                        $indicador_colores = 1;
                        $archivo->Ln(4);
                        $archivo->SetFont('Arial','B',8);
                        //$archivo->SetFillColor(255,180,42);
                        //$archivo->SetFillColor(226,226,240);
                        $archivo->SetFillColor(240,240,240);
                        //echo $auxiliar;
                        $nombre_auxiliar = SQL::obtenerValor("buscador_auxiliares_contables","descripcion","id='".$auxiliar."'");
                        $archivo->Cell(200, 4, $textos["AUXILIAR_CONTABLE"].": ".$nombre_auxiliar, 1, 0, "L", true);
                    }

                    if ($detalla_tercero && $maneja_tercero == '1' && $tercero != $tercero_anterior){

                        $indicador_colores = 1;
                        $archivo->SetFont('Arial','B',7);
                        $archivo->Ln(4);
                        //$archivo->SetFillColor(255,160,22);
                        $archivo->SetFillColor(240,240,240);
                        //$archivo->SetFillColor(226,226,240);
                        $nombre_tercero  = $tercero;
                        $nombre_tercero .= " ";
                        $nombre_tercero .= SQL::obtenerValor("menu_terceros","NOMBRE_COMPLETO","id='$tercero'");
                        $archivo->Cell(200, 4, $textos["TERCERO"].": ".$nombre_tercero, 1, 0, "L", true);
                    }

                    if ($codigo_contable != $codigo_contable_anterior){

                        $saldo_debito    = SQL::obtenerValor("movimientos_contables_consolidados","SUM(valor)","estado=1 AND codigo_contable='".$codigo_contable."' AND fecha_contabilizacion<'".$fecha_inicial."' AND sentido_movimiento='D' AND codigo_sucursal_genera IN (".$condicion_sucursal.")");
                        if (!$saldo_debito){
                            $saldo_debito = 0;
                        }
                        $saldo_credito   = SQL::obtenerValor("movimientos_contables_consolidados","SUM(valor)","estado=1 AND codigo_contable='".$codigo_contable."' AND fecha_contabilizacion<'".$fecha_inicial."' AND sentido_movimiento='C' AND codigo_sucursal_genera IN (".$condicion_sucursal.")");
                        if (!$saldo_credito){
                            $saldo_credito = 0;
                        }
                        if ($sentido_cuenta == 'D'){

                            $saldo_inicial = $saldo_debito - $saldo_credito;
                            $saldo_cuenta = $saldo_inicial;

                            if ($saldo_inicial < 0){
                                $saldo_inicial = "$ (".number_format($saldo_inicial).")";
                            } else  if ($saldo_inicial > 0){
                                $saldo_inicial = "$ ".number_format($saldo_inicial);
                            } else {
                                $saldo_inicial = "$ 0";
                            }
                        } else {
                            $saldo_inicial = $saldo_credito - $saldo_debito;
                            $saldo_cuenta = $saldo_inicial;
                            if ($saldo_inicial < 0){
                                $saldo_inicial = "$ (".number_format($saldo_inicial).")";
                            } else  if ($saldo_inicial > 0){
                                $saldo_inicial = "$ ".number_format($saldo_inicial);
                            } else {
                                $saldo_inicial = "$ 0";
                            }
                        }
                        //$indicador_colores = 1;
                        //if($indicador_colores%2==0){
                            $rojo  = 240;
                            $azul  = 240;
                            $verde = 240;
                        /*} else {
                            $rojo = 226;
                            $azul = 236;
                            $verde = 237;
                        }*/
                        $archivo->SetFont('Arial','',6);
                        $archivo->SetFillColor($rojo,$verde,$azul);
                        $archivo->Ln(4);
                        $archivo->Cell(175, 4, $textos["SALDO_INICIAL"], 1, 0, "L", true);
                        $archivo->Cell(25, 4, $saldo_inicial, 1, 0, "R", true);
                    }

                    $archivo->Ln(4);
                    $imprime_cabecera = $archivo->breakCell(10);
                    if ($imprime_cabecera){
                        $archivo->AddPage();
                        $archivo->SetFont('Arial','B',6);
                        $tituloColumnas = array(
                            $textos["FECHA"], $textos["TIPO_COMPROBANTE"], $textos["NUMERO_COMPROBANTE"], $textos["TIPO_DOCUMENTO"], $textos["NUMERO_DOCUMENTO"],$textos["DEBITO"],$textos["CREDITO"],$textos["SALDO"]
                        );
                        $anchoColumnas = array(15,30,25,35,20,25,25,25);
                        $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
                        $archivo->Ln(4);
                    }

                    //$indicador_colores++;
                    //if($indicador_colores%2==0){
                        $rojo = 255;
                        $azul = 255;
                        $verde = 255;
                    /*} else {
                        $rojo = 226;
                        $azul = 236;
                        $verde = 237;
                    }*/

                    if ($sentido_cuenta != $sentido_movimiento){
                        $saldo_cuenta         -= $datos_movimiento->valor;
                        if ($detalla_auxiliar && $auxiliar>0){
                            $saldo_auxiliar -= $datos_movimiento->valor;
                        }
                        if ($detalla_tercero && $maneja_tercero== '1'){
                            $saldo_tercero -= $datos_movimiento->valor;
                        }
                    } else {
                        $saldo_cuenta         += $datos_movimiento->valor;
                        if ($detalla_auxiliar && $auxiliar>0){
                            $saldo_auxiliar += $datos_movimiento->valor;
                        }
                        if ($detalla_tercero && $maneja_tercero== '1'){
                            $saldo_tercero += $datos_movimiento->valor;
                        }
                    }

                    if ($saldo_cuenta < 0){
                        $saldo = $saldo_cuenta * (-1);
                        $saldo = "$ (".number_format($saldo).")";
                    } else if ($saldo_cuenta > 0){
                        $saldo = "$ ".number_format($saldo_cuenta);
                    } else {
                        $saldo = "$ 0";
                    }

                    if ($sentido_movimiento == 'D'){
                        $valor_debito          = $datos_movimiento->valor;
                        if ($detalla_auxiliar && $auxiliar>0){
                            $total_debito_auxiliar += $valor_debito;
                        }
                        if ($detalla_tercero && $maneja_tercero== '1'){
                            $total_debito_tercero += $valor_debito;
                        }
                        $total_debito_cuenta   += $valor_debito;
                        $total_debito_sucursal += $valor_debito;
                        $total_debito_empresa  += $valor_debito;
                        $valor_debito          = "$ ".number_format($valor_debito);
                        $valor_credito         = "$ 0";
                    } else {
                        $valor_credito          = $datos_movimiento->valor;
                        if ($detalla_auxiliar && $auxiliar>0){
                            $total_credito_auxiliar += $valor_credito;
                        }
                        if ($detalla_tercero && $maneja_tercero== '1'){
                            $total_credito_tercero  += $valor_credito;
                        }
                        $total_credito_cuenta   += $valor_credito;
                        $total_credito_sucursal += $valor_credito;
                        $total_credito_empresa  += $valor_credito;
                        $valor_credito          = "$ ".number_format($valor_credito);
                        $valor_debito           = "$ 0";
                    }

                    $archivo->SetFont('Arial','',6);
                    $archivo->SetFillColor($rojo,$verde,$azul);
                    $fecha_movimiento = explode(" ",$fecha_movimiento);
                    $fecha_movimiento = $fecha_movimiento[0];
                    $archivo->Cell(15, 4, $fecha_movimiento, 1, 0, "C", true);
                    $archivo->Cell(30, 4, $tipo_comprobante, 1, 0, "L", true);
                    $archivo->Cell(25, 4, $numero_comprobante, 1, 0, "L", true);
                    $archivo->Cell(35, 4, $tipo_documento, 1, 0, "L", true);
                    $archivo->Cell(20, 4, $numero_documento, 1, 0, "L", true);
                    $archivo->Cell(25, 4, $valor_debito, 1, 0, "R", true);
                    $archivo->Cell(25, 4, $valor_credito, 1, 0, "R", true);
                    $archivo->Cell(25, 4, $saldo, 1, 0, "R", true);

                    $codigo_contable_anterior = $codigo_contable;
                    $auxiliar_anterior        = $auxiliar;
                    $tercero_anterior         = $tercero;
                    $maneja_tercero_anterior  = $maneja_tercero;
                    $imprime_pdf              = true;
                }// while ($datos_movimiento = SQL::filaEnObjeto($consulta_movimiento))

                if ($detalla_tercero && $maneja_tercero_anterior == '1'){

                    if ($saldo_tercero < 0){
                        $saldo_tercero = $saldo_tercero * (-1);
                        $saldo_tercero = "$ (".number_format($saldo_tercero).")";
                    } else if ($saldo_tercero > 0){
                        $saldo_tercero = "$ ".number_format($saldo_tercero);
                    } else {
                        $saldo_tercero = "$ 0";
                    }

                    if ($total_debito_tercero < 0){
                        $total_debito_tercero = $total_debito_tercero * (-1);
                        $total_debito_tercero = "$ (".number_format($total_debito_tercero).")";
                    } else  if ($total_debito_auxiliar > 0){
                        $total_debito_tercero = "$ ".number_format($total_debito_tercero);
                    } else {
                        $total_debito_tercero = "$ 0";
                    }

                    if ($total_credito_tercero < 0){
                        $total_credito_tercero = $total_credito_tercero * (-1);
                        $total_credito_tercero = "$ (".number_format($total_credito_tercero).")";
                    } else  if ($total_credito_tercero > 0){
                        $total_credito_tercero = "$ ".number_format($total_credito_tercero);
                    } else {
                        $total_credito_tercero = "$ 0";
                    }
                    $archivo->SetFont('Arial','',6);
                    //$archivo->SetFillColor(255,160,22);
                    //$archivo->SetFillColor(226,226,240);
                    $archivo->SetFillColor(240,240,240);
                    $archivo->Ln(4);
                    $archivo->Cell(125, 4, $textos["TOTAL_TERCERO"], 1, 0, "L", true);
                    $archivo->Cell(25, 4, $total_debito_tercero, 1, 0, "R", true);
                    $archivo->Cell(25, 4, $total_credito_tercero, 1, 0, "R", true);
                    $archivo->Cell(25, 4, $saldo_tercero, 1, 0, "R", true);
                    $saldo_tercero         = 0;
                    $total_debito_tercero  = 0;
                    $total_credito_tercero = 0;
                }

                if ($detalla_auxiliar && $auxiliar_anterior > 0){

                    if ($saldo_auxiliar < 0){
                        $saldo_auxiliar = $saldo_auxiliar * (-1);
                        $saldo_auxiliar = "$ (".number_format($saldo_auxiliar).")";
                    } else if ($saldo_auxiliar > 0){
                        $saldo_auxiliar = "$ ".number_format($saldo_auxiliar);
                    } else {
                        $saldo_auxiliar = "$ 0";
                    }

                    if ($total_debito_auxiliar < 0){
                        $total_debito_auxiliar = $total_debito_auxiliar * (-1);
                        $total_debito_auxiliar = "$ (".number_format($total_debito_auxiliar).")";
                    } else  if ($total_debito_auxiliar > 0){
                        $total_debito_auxiliar = "$ ".number_format($total_debito_auxiliar);
                    } else {
                        $total_debito_auxiliar = "$ 0";
                    }

                    if ($total_credito_auxiliar < 0){
                        $total_credito_auxiliar = $total_credito_auxiliar * (-1);
                        $total_credito_auxiliar = "$ (".number_format($total_credito_auxiliar).")";
                    } else  if ($total_credito_auxiliar > 0){
                        $total_credito_auxiliar = "$ ".number_format($total_credito_auxiliar);
                    } else {
                        $total_credito_auxiliar = "$ 0";
                    }
                    $archivo->SetFont('Arial','',6);
                    //$archivo->SetFillColor(255,180,42);
                    //$archivo->SetFillColor(226,226,240);
                    $archivo->SetFillColor(240,240,240);
                    $archivo->Ln(4);
                    $archivo->Cell(125, 4, $textos["TOTAL_AUXILIAR"], 1, 0, "L", true);
                    $archivo->Cell(25, 4, $total_debito_auxiliar, 1, 0, "R", true);
                    $archivo->Cell(25, 4, $total_credito_auxiliar, 1, 0, "R", true);
                    $archivo->Cell(25, 4, $saldo_auxiliar, 1, 0, "R", true);
                }

                if ($saldo_cuenta < 0){
                    $saldo_cuenta = $saldo_cuenta * (-1);
                    $saldo_cuenta = "$ (".number_format($saldo_cuenta).")";
                } else  if ($saldo_cuenta > 0){
                    $saldo_cuenta = "$ ".number_format($saldo_cuenta);
                } else {
                    $saldo_cuenta = "$ 0";
                }

                if ($total_debito_cuenta < 0){
                    $total_debito_cuenta = $total_debito_cuenta * (-1);
                    $total_debito_cuenta = "$ (".number_format($total_debito_cuenta).")";
                } else  if ($total_debito_cuenta > 0){
                    $total_debito_cuenta = "$ ".number_format($total_debito_cuenta);
                } else {
                    $total_debito_cuenta = "$ 0";
                }

                if ($total_credito_cuenta < 0){
                    $total_credito_cuenta = $total_credito_cuenta * (-1);
                    $total_credito_cuenta = "$ (".number_format($total_credito_cuenta).")";
                } else  if ($total_credito_cuenta > 0){
                    $total_credito_cuenta = "$ ".number_format($total_credito_cuenta);
                } else {
                    $total_credito_cuenta = "$ 0";
                }
                $archivo->SetFont('Arial','',6);
                //$archivo->SetFillColor(255,140,93);
                //$archivo->SetFillColor(226,226,240);
                $archivo->SetFillColor(240,240,240);
                $archivo->Ln(4);
                $archivo->Cell(125, 4, $textos["TOTAL_CODIGO"].$codigo_contable_anterior, 1, 0, "L", true);
                $archivo->Cell(25, 4, $total_debito_cuenta, 1, 0, "R", true);
                $archivo->Cell(25, 4, $total_credito_cuenta, 1, 0, "R", true);
                $archivo->Cell(25, 4, $saldo_cuenta, 1, 0, "R", true);
                $archivo->Ln(4);
                $saldo_cuenta         = 0;
                $total_debito_cuenta  = 0;
                $total_credito_cuenta = 0;

                $archivo->Ln(8);
                $archivo->SetFont('Arial','B',6);
                $tituloColumnas = array(
                    $textos["TOTAL_SUCURSAL"], $textos["DEBITO"],$textos["CREDITO"],$textos["SALDO"]
                );
                $anchoColumnas = array(50,25,25,25);
                $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);

                $total_saldo_sucursal = $total_debito_sucursal - $total_credito_sucursal;

                if ($total_saldo_sucursal < 0){
                    $total_saldo_sucursal = $total_saldo_sucursal * (-1);
                    $total_saldo_sucursal = "$ (".number_format($total_saldo_sucursal).")";
                } else  if ($total_saldo_sucursal > 0){
                    $total_saldo_sucursal = "$ ".number_format($total_saldo_sucursal);
                } else {
                    $total_saldo_sucursal = "$ 0";
                }

                if ($total_debito_sucursal < 0){
                    $total_debito_sucursal = $total_debito_sucursal * (-1);
                    $total_debito_sucursal = "$ (".number_format($total_debito_sucursal).")";
                } else  if ($total_debito_sucursal > 0){
                    $total_debito_sucursal = "$ ".number_format($total_debito_sucursal);
                } else {
                    $total_debito_sucursal = "$ 0";
                }

                if ($total_credito_sucursal < 0){
                    $total_credito_sucursal = $total_credito_sucursal * (-1);
                    $total_credito_sucursal = "$ (".number_format($total_credito_sucursal).")";
                } else if ($total_credito_sucursal > 0){
                    $total_credito_sucursal = "$ ".number_format($total_credito_sucursal);
                } else {
                    $total_credito_sucursal = "$ 0";
                }
                $archivo->SetFont('Arial','',6);
                //$archivo->SetFillColor(255,140,93);
                $archivo->SetFillColor(250,250,250);
                $archivo->Ln(4);
                $archivo->Cell(50, 4, $nombreSucursal, 1, 0, "L", true);
                $archivo->Cell(25, 4, $total_debito_sucursal, 1, 0, "R", true);
                $archivo->Cell(25, 4, $total_credito_sucursal, 1, 0, "R", true);
                $archivo->Cell(25, 4, $total_saldo_sucursal, 1, 0, "R", true);
                $archivo->Ln(4);
                $total_saldo_sucursal   = 0;
                $total_debito_sucursal  = 0;
                $total_credito_sucursal = 0;
            } // Final: if ($separa_movimiento)
        } //Final: foreach ($sucursales_reporte AS $sucursal => $condicion_sucursal)

        $archivo->Ln(8);
        $archivo->SetFont('Arial','B',8);
        $tituloColumnas = array($textos["TOTAL_EMPRESA"]);
        $anchoColumnas = array(75);
        $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
        $archivo->Ln(4);
        $archivo->SetFont('Arial','B',6);
        $tituloColumnas = array($textos["DEBITO"],$textos["CREDITO"],$textos["SALDO"]);
        $anchoColumnas = array(25,25,25);
        $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);

        $total_saldo_empresa = $total_debito_empresa - $total_credito_empresa;

        if ($total_saldo_empresa < 0){
            $total_saldo_empresa = $total_saldo_empresa * (-1);
            $total_saldo_empresa = "$ (".number_format($total_saldo_empresa).")";
        } else  if ($total_saldo_empresa > 0){
            $total_saldo_empresa = "$ ".number_format($total_saldo_empresa);
        } else {
            $total_saldo_empresa = "$ 0";
        }

        if ($total_debito_empresa < 0){
            $total_debito_empresa = $total_debito_empresa * (-1);
            $total_debito_empresa = "$ (".number_format($total_debito_empresa).")";
        } else  if ($total_debito_empresa > 0){
            $total_debito_empresa = "$ ".number_format($total_debito_empresa);
        } else {
            $total_debito_empresa = "$ 0";
        }

        if ($total_credito_empresa < 0){
            $total_credito_empresa = $total_credito_empresa * (-1);
            $total_credito_empresa = "$ (".number_format($total_credito_empresa).")";
        } else if ($total_credito_empresa > 0){
            $total_credito_empresa = "$ ".number_format($total_credito_empresa);
        } else {
            $total_credito_empresa = "$ 0";
        }
        $archivo->SetFont('Arial','',6);
        //$archivo->SetFillColor(255,140,93);
        $archivo->SetFillColor(250,250,250);
        $archivo->Ln(4);
        $archivo->Cell(25, 4, $total_debito_empresa, 1, 0, "R", true);
        $archivo->Cell(25, 4, $total_credito_empresa, 1, 0, "R", true);
        $archivo->Cell(25, 4, $total_saldo_empresa, 1, 0, "R", true);
    }// Final: if (!isset($forma_sucursales))

    // Enviar datos con la respuesta del proceso al script que originó la petición
    $respuesta    = array();
    if ($datos_incompletos){
        $respuesta[0] = $error;
        $respuesta[1] = $mensaje;
    } else if(count($forma_sucursales)!=0 && $imprime_pdf) {
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

        $error        = false;
        $mensaje      = $textos["MENSAJE_EXITO"];
        $respuesta[0] = $error;
        $respuesta[1] = $mensaje;
        $respuesta[2] = $ruta_archivo;
    } else {
        $error = true;
        $mensaje = $textos["MENSAJE_NO_GENERA_PDF"];
        $respuesta[0] = $error;
        $respuesta[1] = $mensaje;
    }
    HTTP::enviarJSON($respuesta);
}
?>

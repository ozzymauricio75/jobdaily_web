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

    function imprime_detalle_cuenta($codigo_actual, $archivo, $nombre_cuenta, $indicador_colores, $fecha, $textos){

        $archivo->Ln(4);
        $imprime_cabecera = $archivo->breakCell(5);
        if ($imprime_cabecera){
            $archivo->SetFont('Arial','B',6);
            if (isset($fecha[0]) && isset($fecha[1]) && isset($fecha[2])){
                $tituloColumnas = array($textos["CODIGO"],$textos["NOMBRE_CUENTA"],$textos["SALDO"]." ".$fecha[0], $textos["SALDO"]." ".$fecha[1], $textos["SALDO"]." ".$fecha[2]);
                $anchoColumnas = array(20,80,30,30,30);
            } else if(isset($fecha[0]) && isset($fecha[1])) {
                $tituloColumnas = array($textos["CODIGO"],$textos["NOMBRE_CUENTA"],$textos["SALDO"]." ".$fecha[0], $textos["SALDO"]." ".$fecha[1]);
                $anchoColumnas = array(20,80,30,30);
            } else {
                $tituloColumnas = array($textos["CODIGO"],$textos["NOMBRE_CUENTA"],$textos["SALDO"]." ".$fecha[0]);
                $anchoColumnas = array(20,80,30);
            }
            $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
            $archivo->Ln(4);
        }

        $indicador_colores++;
        if($indicador_colores%2==0){
            $rojo = 255;
            $azul = 255;
            $verde = 255;
        } else {
            $rojo = 240;
            $azul = 240;
            $verde = 240;
        }
        $longitud_cuenta = strlen($codigo_actual);

        $CodigoCuenta = ltrim($codigo_actual,"'");
        $CodigoCuenta = rtrim($CodigoCuenta,"'");

        if ($longitud_cuenta==3){
            $archivo->SetFont('Arial','B',8);
            $archivo->SetFillColor($rojo,$verde,$azul);
            if (isset($fecha[1])){
                if (isset($fecha[2])){
                    $archivo->Cell(190, 4, $CodigoCuenta." ".$nombre_cuenta, 1, 0, "L", true);
                } else {
                    $archivo->Cell(160, 4, $CodigoCuenta." ".$nombre_cuenta, 1, 0, "L", true);
                }
            } else {
                $archivo->Cell(130, 4, $CodigoCuenta." ".$nombre_cuenta, 1, 0, "L", true);
            }
        } else {
            $archivo->SetFont('Arial','',6);
            $archivo->SetFillColor($rojo,$verde,$azul);
            $archivo->Cell(20, 4, $CodigoCuenta, 1, 0, "L", true);
            $archivo->Cell(80, 4, $nombre_cuenta, 1, 0, "L", true);
            $archivo->Cell(30, 4,"", 1, 0, "R", true);
            if (isset($fecha[1])){
                $archivo->Cell(30, 4,"", 1, 0, "R", true);
                if (isset($fecha[2])){
                    $archivo->Cell(30, 4,"", 1, 0, "R", true);
                }
            }
        }
        return $indicador_colores;
    }

    function imprime_total_cuenta($saldo_cuenta, $codigo_actual, $archivo, $nombre_cuenta, $indicador_colores, $fecha, $textos, $imprime_codigo_contable){

        // $imprime_codigo_contable 0->No imprime 1->Si imprime
        $archivo->Ln(4);
        $imprime_cabecera = $archivo->breakCell(5);
        if ($imprime_cabecera){
            $archivo->SetFont('Arial','B',6);
            if (isset($fecha[0]) && isset($fecha[1]) && isset($fecha[2])){
                $tituloColumnas = array($textos["CODIGO"],$textos["NOMBRE_CUENTA"],$textos["SALDO"]." ".$fecha[0], $textos["SALDO"]." ".$fecha[1], $textos["SALDO"]." ".$fecha[2]);
                $anchoColumnas = array(20,80,30,30,30);
            } else if(isset($fecha[0]) && isset($fecha[1])) {
                $tituloColumnas = array($textos["CODIGO"],$textos["NOMBRE_CUENTA"],$textos["SALDO"]." ".$fecha[0], $textos["SALDO"]." ".$fecha[1]);
                $anchoColumnas = array(20,80,30,30);
            } else {
                $tituloColumnas = array($textos["CODIGO"],$textos["NOMBRE_CUENTA"],$textos["SALDO"]." ".$fecha[0]);
                $anchoColumnas = array(20,80,30);
            }
            $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
            $archivo->Ln(4);
        }

        $indicador_colores++;
        if($indicador_colores%2==0){
            $rojo = 255;
            $azul = 255;
            $verde = 255;
        } else {
            $rojo = 240;
            $azul = 240;
            $verde = 240;
        }
        $longitud_cuenta = strlen($codigo_actual);

        $CodigoCuenta = ltrim($codigo_actual,"'");
        $CodigoCuenta = rtrim($CodigoCuenta,"'");


        $archivo->SetFont('Arial','B',6);
        $archivo->SetFillColor($rojo,$verde,$azul);
        if ($imprime_codigo_contable==0){
            if ($longitud_cuenta==3){
                $archivo->SetFont('Arial','B',8);
            }
            $archivo->Cell(100, 4,"TOTAL ".$nombre_cuenta, 1, 0, "L", true);
        } else {
            $archivo->Cell(20, 4,$CodigoCuenta, 1, 0, "L", true);
            $archivo->Cell(80, 4,$nombre_cuenta, 1, 0, "L", true);
        }
        if (isset($saldo_cuenta[$fecha[0]])){
            $saldo1 = $saldo_cuenta[$fecha[0]];
            if ($saldo1 < 0){
                //$archivo->SetTextColor(255,0,0);
                $saldo1 = $saldo1*(-1);
                $saldo1 = "$ (".number_format($saldo1).")";
            } else {
                //$archivo->SetTextColor(0,128,0);
                $saldo1 = "$ ".number_format($saldo1);
            }
        } else {
            $saldo1 = "$ 0";
        }
        $archivo->Cell(30, 4,$saldo1, 1, 0, "R", true);

        if (isset($fecha[1])){

            if (isset($saldo_cuenta[$fecha[1]])){
                $saldo2 = $saldo_cuenta[$fecha[1]];
                if ($saldo2 < 0){
                    //$archivo->SetTextColor(255,0,0);
                    $saldo2 = $saldo2*(-1);
                    $saldo2 = "$ (".number_format($saldo2).")";
                } else {
                    //$archivo->SetTextColor(0,128,0);
                    $saldo2 = "$ ".number_format($saldo2);
                }
            } else {
                //$archivo->SetTextColor(0,0,0);
                $saldo2 = "$ 0";
            }

            $archivo->Cell(30, 4,$saldo2, 1, 0, "R", true);
            if (isset($fecha[2])){
                if (isset($fecha[2]) && isset($saldo_cuenta[$fecha[2]])){
                    $saldo3 = $saldo_cuenta[$fecha[2]];
                    if ($saldo3 < 0){
                        //$archivo->SetTextColor(255,0,0);
                        $saldo3 = $saldo3*(-1);
                        $saldo3 = "$ (".number_format($saldo3).")";
                    } else {
                        //$archivo->SetTextColor(0,128,0);
                        $saldo3 = "$ ".number_format($saldo3);
                    }
                } else {
                    //$archivo->SetTextColor(0,0,0);
                    $saldo3 = "$ 0";
                }
                $archivo->Cell(30, 4,$saldo3, 1, 0, "R", true);
            }
        }
        //$archivo->SetTextColor(0,0,0);
        return $indicador_colores;
    }

    function imprime_detalle_auxiliar_tercero($descripcion_cuenta, $descripcion_auxiliar_tercero, $archivo, $indicador_colores, $fecha, $textos){

        $archivo->Ln(4);
        $imprime_cabecera = $archivo->breakCell(5);
        if ($imprime_cabecera){
            $archivo->SetFont('Arial','B',6);
            if (isset($fecha[0]) && isset($fecha[1]) && isset($fecha[2])){
                $tituloColumnas = array($textos["CODIGO"],$textos["NOMBRE_CUENTA"],$textos["SALDO"]." ".$fecha[0], $textos["SALDO"]." ".$fecha[1], $textos["SALDO"]." ".$fecha[2]);
                $anchoColumnas = array(20,80,30,30,30);
            } else if(isset($fecha[0]) && isset($fecha[1])) {
                $tituloColumnas = array($textos["CODIGO"],$textos["NOMBRE_CUENTA"],$textos["SALDO"]." ".$fecha[0], $textos["SALDO"]." ".$fecha[1]);
                $anchoColumnas = array(20,80,30,30);
            } else {
                $tituloColumnas = array($textos["CODIGO"],$textos["NOMBRE_CUENTA"],$textos["SALDO"]." ".$fecha[0]);
                $anchoColumnas = array(20,80,30);
            }
            $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
            $archivo->Ln(4);
        }

        $indicador_colores++;
        if($indicador_colores%2==0){
            $rojo = 255;
            $azul = 255;
            $verde = 255;
        } else {
            $rojo = 240;
            $azul = 240;
            $verde = 240;
        }

        $archivo->SetFont('Arial','B',6);
        $archivo->SetFillColor($rojo,$verde,$azul);
        $archivo->Cell(20, 4,$descripcion_cuenta, 1, 0, "L", true);
        $archivo->Cell(80, 4,$descripcion_auxiliar_tercero, 1, 0, "L", true);
        if (isset($fecha[0])){
            $archivo->Cell(30, 4,"", 1, 0, "R", true);
            if (isset($fecha[1])){
                $archivo->Cell(30, 4,"", 1, 0, "R", true);
                if (isset($fecha[2])){
                    $archivo->Cell(30, 4,"", 1, 0, "R", true);
                }
            }
        }
    }

    function imprime_total_auxiliar_tercero($descripcion_cuenta, $descripcion_auxiliar_tercero, $archivo, $indicador_colores, $fecha, $fechas_detalle, $textos){

        $archivo->Ln(4);
        $imprime_cabecera = $archivo->breakCell(5);
        if ($imprime_cabecera){
            $archivo->SetFont('Arial','B',6);
            if (isset($fecha[0]) && isset($fecha[1]) && isset($fecha[2])){
                $tituloColumnas = array($textos["CODIGO"],$textos["NOMBRE_CUENTA"],$textos["SALDO"]." ".$fecha[0], $textos["SALDO"]." ".$fecha[1], $textos["SALDO"]." ".$fecha[2]);
                $anchoColumnas = array(20,80,30,30,30);
            } else if(isset($fecha[0]) && isset($fecha[1])) {
                $tituloColumnas = array($textos["CODIGO"],$textos["NOMBRE_CUENTA"],$textos["SALDO"]." ".$fecha[0], $textos["SALDO"]." ".$fecha[1]);
                $anchoColumnas = array(20,80,30,30);
            } else {
                $tituloColumnas = array($textos["CODIGO"],$textos["NOMBRE_CUENTA"],$textos["SALDO"]." ".$fecha[0]);
                $anchoColumnas = array(20,80,30);
            }
            $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
            $archivo->Ln(4);
        }

        $indicador_colores++;
        if($indicador_colores%2==0){
            $rojo = 255;
            $azul = 255;
            $verde = 255;
        } else {
            $rojo = 240;
            $azul = 240;
            $verde = 240;
        }

        $archivo->SetFont('Arial','B',6);
        $archivo->SetFillColor($rojo,$verde,$azul);
        $archivo->Cell(20, 4,$descripcion_cuenta, 1, 0, "L", true);
        $archivo->Cell(80, 4,$descripcion_auxiliar_tercero, 1, 0, "L", true);
        if (isset($fechas_detalle[$fecha[0]])){
            $saldo1 = $fechas_detalle[$fecha[0]];
            if ($saldo1 < 0){
                //$archivo->SetTextColor(255,0,0);
                $saldo1 = $saldo1*(-1);
                $saldo1 = "$ (".number_format($saldo1).")";
            } else {
                //$archivo->SetTextColor(0,128,0);
                $saldo1 = "$ ".number_format($saldo1);
            }
        } else {
            $saldo1 = "$ 0";
        }
        $archivo->Cell(30, 4,$saldo1, 1, 0, "R", true);

        if (isset($fecha[1])){

            if (isset($fechas_detalle[$fecha[1]])){
                $saldo2 = $fechas_detalle[$fecha[1]];
                if ($saldo2 < 0){
                    //$archivo->SetTextColor(255,0,0);
                    $saldo2 = $saldo2*(-1);
                    $saldo2 = "$ (".number_format($saldo2).")";
                } else {
                    //$archivo->SetTextColor(0,128,0);
                    $saldo2 = "$ ".number_format($saldo2);
                }
            } else {
                //$archivo->SetTextColor(0,0,0);
                $saldo2 = "$ 0";
            }

            $archivo->Cell(30, 4,$saldo2, 1, 0, "R", true);
            if (isset($fecha[2])){
                if (isset($fecha[2]) && isset($fechas_detalle[$fecha[2]])){
                    $saldo3 = $fechas_detalle[$fecha[2]];
                    if ($saldo3 < 0){
                        //$archivo->SetTextColor(255,0,0);
                        $saldo3 = $saldo3*(-1);
                        $saldo3 = "$ (".number_format($saldo3).")";
                    } else {
                        //$archivo->SetTextColor(0,128,0);
                        $saldo3 = "$ ".number_format($saldo3);
                    }
                } else {
                    //$archivo->SetTextColor(0,0,0);
                    $saldo3 = "$ 0";
                }
                $archivo->Cell(30, 4,$saldo3, 1, 0, "R", true);
            }
        }
        //$archivo->SetTextColor(0,0,0);
        return $indicador_colores;
    }

    function imprime_detalle_comprobacion($codigo_actual, $archivo, $nombre_cuenta, $indicador_colores, $textos){

        $archivo->Ln(4);
        $imprime_cabecera = $archivo->breakCell(5);
        if ($imprime_cabecera){
            $archivo->SetFont('Arial','B',6);
            $tituloColumnas = array($textos["CODIGO"],$textos["NOMBRE_CUENTA"],$textos["SALDO_INICIAL"],$textos["DEBE"],$textos["HABER"],$textos["SALDO_FINAL"]);
            $anchoColumnas = array(20,80,25,25,25,25);
            $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
            $archivo->Ln(4);
        }

        $indicador_colores++;
        if($indicador_colores%2==0){
            $rojo = 255;
            $azul = 255;
            $verde = 255;
        } else {
            $rojo = 240;
            $azul = 240;
            $verde = 240;
        }
        $longitud_cuenta = strlen($codigo_actual);

        $CodigoCuenta = ltrim($codigo_actual,"'");
        $CodigoCuenta = rtrim($CodigoCuenta,"'");

        if ($longitud_cuenta==3){
            $archivo->SetFont('Arial','B',8);
            $archivo->SetFillColor($rojo,$verde,$azul);
            $archivo->Cell(200, 4, $CodigoCuenta." ".$nombre_cuenta, 1, 0, "L", true);
        } else {
            $archivo->SetFont('Arial','',6);
            $archivo->SetFillColor($rojo,$verde,$azul);
            $archivo->Cell(20, 4, $CodigoCuenta, 1, 0, "L", true);
            $archivo->Cell(80, 4, $nombre_cuenta, 1, 0, "L", true);
            $archivo->Cell(25, 4,"", 1, 0, "R", true);
            $archivo->Cell(25, 4,"", 1, 0, "R", true);
            $archivo->Cell(25, 4,"", 1, 0, "R", true);
            $archivo->Cell(25, 4,"", 1, 0, "R", true);
        }
        return $indicador_colores;
    }

    function imprime_total_comprobacion($indicador_colores, $archivo, $codigo_actual, $nombre_cuenta, $saldo_inicial, $saldo_debe, $saldo_haber, $saldo_final, $textos, $imprime_codigo_contable){

        // $imprime_codigo_contable 0->No imprime 1->Si imprime
        $archivo->Ln(4);
        $imprime_cabecera = $archivo->breakCell(5);
        if ($imprime_cabecera){
            $archivo->SetFont('Arial','B',6);
            $tituloColumnas = array($textos["CODIGO"],$textos["NOMBRE_CUENTA"],$textos["SALDO_INICIAL"],$textos["DEBE"],$textos["HABER"],$textos["SALDO_FINAL"]);
            $anchoColumnas = array(20,80,25,25,25,25);
            $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
            $archivo->Ln(4);
        }

        $indicador_colores++;
        if($indicador_colores%2==0){
            $rojo = 255;
            $azul = 255;
            $verde = 255;
        } else {
            $rojo = 240;
            $azul = 240;
            $verde = 240;
        }
        $longitud_cuenta = strlen($codigo_actual);

        $CodigoCuenta = ltrim($codigo_actual,"'");
        $CodigoCuenta = rtrim($CodigoCuenta,"'");


        $archivo->SetFont('Arial','B',6);
        $archivo->SetFillColor($rojo,$verde,$azul);
        if ($imprime_codigo_contable==0){
            if ($longitud_cuenta==3){
                $archivo->SetFont('Arial','B',8);
            }
            $archivo->Cell(100, 4,$textos["TOTAL"]." ".$nombre_cuenta, 1, 0, "L", true);
        } else {
            $archivo->Cell(20, 4,$CodigoCuenta, 1, 0, "L", true);
            $archivo->Cell(80, 4,$nombre_cuenta, 1, 0, "L", true);
        }
        if ($saldo_inicial < 0){
                //$archivo->SetTextColor(255,0,0);
                $saldo1 = $saldo_inicial*(-1);
                $saldo1 = "$ (".number_format($saldo1).")";
        } else if ($saldo_inicial > 0){
                //$archivo->SetTextColor(0,128,0);
                $saldo1 = "$ ".number_format($saldo_inicial);
        } else {
            $saldo1 = "$ 0";
        }
        $archivo->Cell(25, 4,$saldo1, 1, 0, "R", true);

        if ($saldo_debe < 0){
            //$archivo->SetTextColor(255,0,0);
            $saldo2 = $saldo_debe * (-1);
            $saldo2 = "$ (".number_format($saldo2).")";
        } else if ($saldo_debe > 0){
            //$archivo->SetTextColor(0,128,0);
            $saldo2 = "$ ".number_format($saldo_debe);
        } else {
            //$archivo->SetTextColor(0,0,0);
            $saldo2 = "$ 0";
        }
        $archivo->Cell(25, 4,$saldo2, 1, 0, "R", true);

        if ($saldo_haber < 0){
            //$archivo->SetTextColor(255,0,0);
            $saldo3 = $saldo_haber * (-1);
            $saldo3 = "$ (".number_format($saldo3).")";
        } else if ($saldo_haber > 0){
            //$archivo->SetTextColor(0,128,0);
            $saldo3 = "$ ".number_format($saldo_haber);
        } else {
            //$archivo->SetTextColor(0,0,0);
            $saldo3 = "$ 0";
        }
        $archivo->Cell(25, 4,$saldo3, 1, 0, "R", true);

        if ($saldo_final < 0){
            //$archivo->SetTextColor(255,0,0);
            $saldo4 = $saldo_final * (-1);
            $saldo4 = "$ (".number_format($saldo4).")";
        } else if ($saldo_final > 0){
            //$archivo->SetTextColor(0,128,0);
            $saldo4 = "$ ".number_format($saldo_final);
        } else {
            //$archivo->SetTextColor(0,0,0);
            $saldo4 = "$ 0";
        }
        $archivo->Cell(25, 4,$saldo4, 1, 0, "R", true);

        //$archivo->SetTextColor(0,0,0);
        return $indicador_colores;
    }
?>

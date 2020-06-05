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

    //FUNCIONES GENERICAS

    function colorCeldas($archivo){
        if($archivo->FillColor != sprintf('%.3F %.3F %.3F rg',1,1,1)){
            $archivo->SetFillColor(255,255,255);
        }else{
            $archivo->SetFillColor(240,240,240);
        }
    }

    function imprimirTercero($tercero,$textos,$descripcion_documento,$consecutivo_documento,$total_factura,$archivo){
        colorCeldas($archivo);
        $archivo->Cell(190, 4, $tercero."  --  ".$textos["NUMERO_FACTURA"]." : ".$descripcion_documento." - ".$consecutivo_documento."  --  ".$textos["VALOR_FACTURA"]." : ".number_format($total_factura), 0, 0, "L", true);
        $archivo->ln(4);
    }

    function generarTotalesSaldo($archivo,$textos,$total_por_vencer_mayor_30,$total_por_vencer_1_30,$total_vencido_0_30,$total_vencido_31_60,$total_vencido_61_90,$total_vencido_mayor_90){
        colorCeldas($archivo);
        $archivo->SetFont('Arial','B',7);
        $archivo->Cell(70, 4, $textos["TOTAL_SALDOS"].":", 0, 0, "C", true);
        $archivo->Cell(20, 4, $total_por_vencer_mayor_30,  0, 0, "R", true);
        $archivo->Cell(20, 4, $total_por_vencer_1_30,      0, 0, "R", true);
        $archivo->Cell(20, 4, $total_vencido_0_30,         0, 0, "R", true);
        $archivo->Cell(20, 4, $total_vencido_31_60,        0, 0, "R", true);
        $archivo->Cell(20, 4, $total_vencido_61_90,        0, 0, "R", true);
        $archivo->Cell(20, 4, $total_vencido_mayor_90,     0, 0, "R", true);
        $archivo->ln(4);
        $archivo->SetFont('Arial','B',6);
    }

    //////////////////////////////////////FUNCIONES DE CUENTAS X COBRAR///////////////////////////////////////////////

    function imprimirCabeceraCXC($archivo,$textos,$tercero,$descripcion_documento,$consecutivo_documento,$total_factura,$salto){
        $imprime_cabecera = $archivo->breakCell($salto);
        $estado = false;//Indica si se imprime o no la cabecera
        if ($imprime_cabecera){
            $archivo->AddPage();
            $archivo->ln(8);
            $archivo->SetFillColor(230,230,230);

            $archivo->cell(70, 4, $textos["DATOS_SALDO"], 1, 0, "C", true);
            $archivo->cell(40, 4, $textos["FECHA_POR_VENCER"], 1, 0, "C", true);
            $archivo->cell(80, 4, $textos["FECHA_VENCIDA"], 1, 0, "C", true);
            $archivo->ln(4);


            $archivo->cell(20, 4, $textos["NUMERO_CUOTA"], 1, 0, "C", true);
            $archivo->cell(20, 4, $textos["VALOR_CUOTA"], 1, 0, "C", true);
            $archivo->cell(30, 4, $textos["FECHA_VENCIMIENTO"], 1, 0, "C", true);

            $archivo->cell(20, 4, $textos["FECHA_MAYOR_30"], 1, 0, "C", true);
            $archivo->cell(20, 4, $textos["FECHA_1_30"], 1, 0, "C", true);

            $archivo->cell(20, 4, $textos["FECHA_0_30"], 1, 0, "C", true);
            $archivo->Cell(20, 4, $textos["FECHA_31_60"], 1, 0, "C", true);
            $archivo->Cell(20, 4, $textos["FECHA_61_90"], 1, 0, "C", true);
            $archivo->Cell(20, 4, $textos["FECHA_MAYOR_90"], 1, 0, "C", true);
            $archivo->ln(4);

            imprimirTercero($tercero,$textos,$descripcion_documento,$consecutivo_documento,$total_factura,$archivo);

            $estado = true;
        }
        return $estado;
    }

    function adicionarPaginaCXC($textos,$forma_fecha_saldo,$nombre_sucursal,$consolidadas,$sucursal,$archivo){

        $archivo->AddPage();

        $archivo->Ln(5);
        $archivo->SetFont('Arial','B',8);
        $archivo->Cell(140,5,"",0,0,'R');
        $archivo->Cell(20,5,$textos["FECHA_SALDO"],0,0,'R');
        $archivo->SetFont('Arial','',8);
        $archivo->Cell(30,5,$forma_fecha_saldo,0,0,'L');

        $archivo->Ln(6);
        $archivo->SetFont('Arial','B',8);
        $archivo->Cell(50,6,$textos["SUCURSAL"]." :",0,0,'L');
        $archivo->SetFont('Arial','',8);
        $archivo->Cell(140,6,$nombre_sucursal,0,0,'L');


        if (!( (count($consolidadas) == 1) && ($consolidadas[0] == $sucursal) )) {
            $archivo->Ln(4);
            $archivo->SetFont('Arial','B',8);
            $archivo->Cell(50,6,$textos["CONSOLIDADAS"]." :",0,0,'L');
            $nombres_consolidadas = array();
            foreach ($consolidadas AS $consolidada) {
                $nombres_consolidadas[] = SQL::obtenerValor("sucursales", "nombre", "codigo = ".$consolidada);
            }
            $nombres_consolidadas = implode(",",$nombres_consolidadas);
            $archivo->SetFont('Arial','',8);
            $archivo->Cell(140,6,$nombres_consolidadas,0,0,'L');
        }

        $archivo->SetFont('Arial','B',6);
        $archivo->SetFillColor(230,230,230);
        $archivo->ln(8);

        $archivo->cell(70, 4, $textos["DATOS_SALDO"], 1, 0, "C", true);
        $archivo->cell(40, 4, $textos["FECHA_POR_VENCER"], 1, 0, "C", true);
        $archivo->cell(80, 4, $textos["FECHA_VENCIDA"], 1, 0, "C", true);
        $archivo->ln(4);


        $archivo->cell(20, 4, $textos["NUMERO_CUOTA"], 1, 0, "C", true);
        $archivo->cell(20, 4, $textos["VALOR_CUOTA"], 1, 0, "C", true);
        $archivo->cell(30, 4, $textos["FECHA_VENCIMIENTO"], 1, 0, "C", true);

        $archivo->cell(20, 4, $textos["FECHA_MAYOR_30"], 1, 0, "C", true);
        $archivo->cell(20, 4, $textos["FECHA_1_30"], 1, 0, "C", true);

        $archivo->cell(20, 4, $textos["FECHA_0_30"], 1, 0, "C", true);
        $archivo->Cell(20, 4, $textos["FECHA_31_60"], 1, 0, "C", true);
        $archivo->Cell(20, 4, $textos["FECHA_61_90"], 1, 0, "C", true);
        $archivo->Cell(20, 4, $textos["FECHA_MAYOR_90"], 1, 0, "C", true);
        $archivo->ln(4);
    }

    function marcarTitulosCXC($archivo,$textos,$sucursal){
        $nombreSucursal          = SQL::obtenerValor("sucursales","nombre","codigo='".$sucursal."'");
        $archivo->textoTitulo    = $textos["REPOSCXC"]." ".$nombreSucursal;
        $archivo->textoCabecera  = $textos["FECHA"].": ".date("Y-m-d");
        $archivo->textoPiePagina = "";
    }

    //////////////////////////////////////FUNCIONES DE CUENTAS X PAGAR///////////////////////////////////////////////

    function imprimirCabeceraCXP($archivo,$textos,$tercero,$descripcion_documento,$consecutivo_documento,$total_factura,$salto){
        $imprime_cabecera = $archivo->breakCell($salto);
        $estado = false;//Indica si se imprime o no la cabecera
        if ($imprime_cabecera){
            $archivo->AddPage();
            $archivo->ln(8);
            $archivo->SetFillColor(230,230,230);

            $archivo->cell(70, 4, $textos["DATOS_SALDO"], 1, 0, "C", true);
            $archivo->cell(40, 4, $textos["FECHA_VENCIDA"], 1, 0, "C", true);
            $archivo->cell(80, 4, $textos["FECHA_POR_VENCER"], 1, 0, "C", true);
            $archivo->ln(4);


            $archivo->cell(20, 4, $textos["NUMERO_CUOTA"], 1, 0, "C", true);
            $archivo->cell(20, 4, $textos["VALOR_CUOTA"], 1, 0, "C", true);
            $archivo->cell(30, 4, $textos["FECHA_VENCIMIENTO"], 1, 0, "C", true);

            $archivo->cell(20, 4, $textos["FECHA_MAYOR_30"], 1, 0, "C", true);
            $archivo->cell(20, 4, $textos["FECHA_0_30"], 1, 0, "C", true);

            $archivo->cell(20, 4, $textos["FECHA_1_30"], 1, 0, "C", true);
            $archivo->Cell(20, 4, $textos["FECHA_31_60"], 1, 0, "C", true);
            $archivo->Cell(20, 4, $textos["FECHA_61_90"], 1, 0, "C", true);
            $archivo->Cell(20, 4, $textos["FECHA_MAYOR_90"], 1, 0, "C", true);
            $archivo->ln(4);

            imprimirTercero($tercero,$textos,$descripcion_documento,$consecutivo_documento,$total_factura,$archivo);

            $estado = true;
        }
        return $estado;
    }

    function adicionarPaginaCXP($textos,$forma_fecha_saldo,$nombre_sucursal,$consolidadas,$sucursal,$archivo){

        $archivo->AddPage();

        $archivo->Ln(5);
        $archivo->SetFont('Arial','B',8);
        $archivo->Cell(140,5,"",0,0,'R');
        $archivo->Cell(20,5,$textos["FECHA_SALDO"],0,0,'R');
        $archivo->SetFont('Arial','',8);
        $archivo->Cell(30,5,$forma_fecha_saldo,0,0,'L');

        $archivo->Ln(6);
        $archivo->SetFont('Arial','B',8);
        $archivo->Cell(50,6,$textos["SUCURSAL"]." :",0,0,'L');
        $archivo->SetFont('Arial','',8);
        $archivo->Cell(140,6,$nombre_sucursal,0,0,'L');


        if (!( (count($consolidadas) == 1) && ($consolidadas[0] == $sucursal) )) {
            $archivo->Ln(4);
            $archivo->SetFont('Arial','B',8);
            $archivo->Cell(50,6,$textos["CONSOLIDADAS"]." :",0,0,'L');
            $nombres_consolidadas = array();
            foreach ($consolidadas AS $consolidada) {
                $nombres_consolidadas[] = SQL::obtenerValor("sucursales", "nombre", "codigo = ".$consolidada);
            }
            $nombres_consolidadas = implode(",",$nombres_consolidadas);
            $archivo->SetFont('Arial','',8);
            $archivo->Cell(140,6,$nombres_consolidadas,0,0,'L');
        }

        $archivo->SetFont('Arial','B',6);
        $archivo->SetFillColor(230,230,230);
        $archivo->ln(8);

        $archivo->cell(70, 4, $textos["DATOS_SALDO"], 1, 0, "C", true);
        $archivo->cell(40, 4, $textos["FECHA_VENCIDA"], 1, 0, "C", true);
        $archivo->cell(80, 4, $textos["FECHA_POR_VENCER"], 1, 0, "C", true);
        $archivo->ln(4);


        $archivo->cell(20, 4, $textos["NUMERO_CUOTA"], 1, 0, "C", true);
        $archivo->cell(20, 4, $textos["VALOR_CUOTA"], 1, 0, "C", true);
        $archivo->cell(30, 4, $textos["FECHA_VENCIMIENTO"], 1, 0, "C", true);

        $archivo->cell(20, 4, $textos["FECHA_MAYOR_30"], 1, 0, "C", true);
        $archivo->cell(20, 4, $textos["FECHA_0_30"], 1, 0, "C", true);

        $archivo->cell(20, 4, $textos["FECHA_1_30"], 1, 0, "C", true);
        $archivo->Cell(20, 4, $textos["FECHA_31_60"], 1, 0, "C", true);
        $archivo->Cell(20, 4, $textos["FECHA_61_90"], 1, 0, "C", true);
        $archivo->Cell(20, 4, $textos["FECHA_MAYOR_90"], 1, 0, "C", true);
        $archivo->ln(4);
    }

    function marcarTitulosCXP($archivo,$textos,$sucursal){
        $nombreSucursal          = SQL::obtenerValor("sucursales","nombre","codigo='".$sucursal."'");
        $archivo->textoTitulo    = $textos["REPOSCXP"]." ".$nombreSucursal;
        $archivo->textoCabecera  = $textos["FECHA"].": ".date("Y-m-d");
        $archivo->textoPiePagina = "";
    }

    function imprimirCabeceraSMD($textos,$archivo,$forma_fecha_desde,$forma_fecha_hasta,$lista_consolidadas,$sucursal,$forma_cuenta_desde,$forma_cuenta_hasta){
        $nombreSucursal     = SQL::obtenerValor("sucursales","nombre","codigo='".$sucursal."'");

        //CABECERA
        $archivo->AddPage();

        $archivo->SetFont('Arial','B',8);
        $archivo->SetFillColor(255,255,255);

        $archivo->SetFont('Arial','B',8);
        $archivo->Cell(200,4,"",0,0,'R');
        $archivo->Cell(20,4,$textos["FECHA_DESDE"].": ",0,0,'L');
        $archivo->SetFont('Arial','',8);
        $archivo->Cell(25,4,$forma_fecha_desde,0,0,'L');
        $archivo->Ln(5);

        $archivo->SetFont('Arial','B',8);
        $archivo->Cell(200,4,"",0,0,'R');
        $archivo->Cell(20,4,$textos["FECHA_HASTA"].": ",0,0,'L');
        $archivo->SetFont('Arial','',8);
        $archivo->Cell(25,4,$forma_fecha_hasta,0,0,'L');
        $archivo->Ln(5);

        $archivo->SetFont('Arial','B',8);
        $archivo->Cell(30,6,$textos["SUCURSAL"].": ",0,0,'L');
        $archivo->SetFont('Arial','',8);
        $archivo->Cell(130,6,$nombreSucursal,0,0,'L');
        $archivo->Ln(6);

        if (!( (count($lista_consolidadas) == 1) && ($lista_consolidadas[0] == "'".$sucursal."'") )) {
            $archivo->SetFont('Arial','B',8);
            $archivo->Cell(30,6,$textos["CONSOLIDADAS"]." :",0,0,'L');
            $nombres_consolidadas = array();
            foreach ($lista_consolidadas AS $consolidada) {
                $nombres_consolidadas[] = SQL::obtenerValor("sucursales", "nombre", "codigo = ".$consolidada);
            }
            $nombres_consolidadas = implode(",",$nombres_consolidadas);
            $archivo->SetFont('Arial','',8);
            $archivo->Cell(140,6,$nombres_consolidadas,0,0,'L');
            $archivo->Ln(6);
        }
        $decripcion_desde = SQL::obtenerValor("plan_contable","descripcion","codigo_contable = '".$forma_cuenta_desde."'");
        $decripcion_hasta = SQL::obtenerValor("plan_contable","descripcion","codigo_contable = '".$forma_cuenta_hasta."'");

        $archivo->SetFont('Arial','B',8);
        $archivo->Cell(30,6,$textos["CUENTAS_REPORTE"].": ",0,0,'L');
        $archivo->SetFont('Arial','',8);
        $archivo->Cell(130,6,$decripcion_desde." / ".$decripcion_hasta,0,0,'L');
        $archivo->Ln(15);

        //FIN CABECERA
    }
?>

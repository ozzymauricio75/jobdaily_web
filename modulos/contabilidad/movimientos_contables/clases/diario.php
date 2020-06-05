<?php /**
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

    function cabeceraComprobante($archivo,$textos,$sucursal,$forma_fecha_desde,$forma_fecha_hasta,$consolidadas,$tipo){

        $nombre_sucursal = SQL::obtenerValor("sucursales", "nombre", "codigo = '".$sucursal."'");
        $archivo->textoTitulo   = $textos[$tipo]." ".$nombre_sucursal;

        $archivo->AddPage();

        $archivo->SetFont('Arial','B',6);
        $archivo->Cell(140,5,"",0,0,'R');
        $archivo->Cell(20,5,$textos["FECHA_DESDE"],0,0,'L');
        $archivo->SetFont('Arial','',6);
        $archivo->Cell(30,5,$forma_fecha_desde,0,0,'L');
        $archivo->Ln(4);

        $archivo->SetFont('Arial','B',6);
        $archivo->Cell(140,4,"",0,0,'R');
        $archivo->Cell(20,5,$textos["FECHA_HASTA"],0,0,'L');
        $archivo->SetFont('Arial','',6);
        $archivo->Cell(30,4,$forma_fecha_hasta,0,0,'L');
        $archivo->Ln(6);

        $archivo->SetFont('Arial','B',8);
        $archivo->Cell(30,5,$textos["SUCURSAL"]." :",0,0,'L');
        $archivo->SetFont('Arial','',8);
        $archivo->Cell(140,5,$nombre_sucursal,0,0,'L');
        $archivo->Ln(6);

        if (!( (count($consolidadas) == 1) && ($consolidadas[0] == "'".$sucursal."'") )) {

            $archivo->SetFont('Arial','B',8);
            $archivo->Cell(30,6,$textos["CONSOLIDADAS"]." :",0,0,'L');
            $nombres_consolidadas = "";
            foreach ($consolidadas AS $consolidada) {
                $nombres_consolidadas .= SQL::obtenerValor("sucursales", "nombre", "codigo = ".$consolidada).", ";
            }
            $nombres_consolidadas = trim($nombres_consolidadas, ", ");
            $archivo->SetFont('Arial','',8);
            $archivo->Cell(140,5,$nombres_consolidadas,0,0,'L');
            $archivo->Ln(8);
        }
    }

    function cabeceraTabla($archivo,$textos,$datos){

        $descripcion = SQL::obtenerValor("tipos_comprobantes","descripcion","codigo = '".$datos->codigo_tipo_comprobante."'");
        $archivo->SetFont('Arial','B',8);
        $archivo->Cell(30,7,$textos["TIPO_COMPROBANTE"].":",0,0,'L');
        $archivo->Cell(170,7,$descripcion,0,0,'L');
        $archivo->Ln(10);

        $archivo->SetFillColor(230,230,230);
        $archivo->SetFont('Arial','B',6);
        $archivo->Cell(30,4,$textos["NUMERO_COMPROBANTE"],1,0,'C',true);
        $archivo->Cell(30,4,$textos["FECHA_CONTABILIZACION"],1,0,'C',true);
        $archivo->Cell(80,4,$textos["NOMBRE_CUENTA"],1,0,'C',true);
        $archivo->Cell(30,4,$textos["DEBITO"],1,0,'C',true);
        $archivo->Cell(30,4,$textos["CREDITO"],1,0,'C',true);
        $archivo->Ln(4);
    }

    function totalSucursal($archivo,$textos,$total_debito_sucursal,$total_credito_sucursal){

        $archivo->SetFont('Arial','B',6);
        $archivo->Cell(110,4,"",0,0,'L');
        $archivo->Cell(30,4,$textos["TOTAL_SUCURSAL"].":",0,0,'R');
        $archivo->Cell(30,4,"$".number_format($total_debito_sucursal,0),0,0,'R');
        $archivo->Cell(30,4,"$".number_format($total_credito_sucursal,0),0,0,'R');
        $archivo->Ln(4);
        $archivo->Cell(110,4,"",0,0,'L');
        $archivo->Cell(30,4,$textos["DIFERENCIA"].":",0,0,'R');
        $archivo->Cell(60,4,"$".number_format($total_debito_sucursal-$total_credito_sucursal,0),0,0,'C');
        $archivo->Ln(4);
    }

    function totalEmpresa($archivo,$textos,$total_debito_empresa,$total_credito_empresa){

        if($total_debito_empresa > 0 || $total_credito_empresa > 0){
            $archivo->textoTitulo   = $textos["REPODIMC"]." - "."Total Empresa";
            $archivo->AddPage();
            $archivo->Ln(10);
            $archivo->SetFont('Arial','B',8);
            $archivo->Cell(30,6,$textos["TOTAL"].":",0,0,'R');
            $archivo->Cell(30,6,"$".number_format($total_debito_empresa,0),0,0,'R');
            $archivo->Cell(30,6,"$".number_format($total_credito_empresa,0),0,0,'R');
            $archivo->Ln(6);
            $archivo->Cell(30,6,$textos["DIFERENCIA"].":",0,0,'R');
            $archivo->Cell(60,6,"$".number_format($total_debito_empresa-$total_credito_empresa,0),0,0,'C');
            $archivo->Ln(6);
        }
    }

    function colorCeldas($archivo){
        if($archivo->FillColor != sprintf('%.3F %.3F %.3F rg',1,1,1)){
            $archivo->SetFillColor(255,255,255);
        }else{
            $archivo->SetFillColor(240,240,240);
        }
    }

    function totalSucursalDMD($archivo,$textos,$total_debito_sucursal,$total_credito_sucursal){

        $archivo->SetFont('Arial','B',7);
        $archivo->Cell(225,5,"",0,0,'L');
        $archivo->Cell(40,5,$textos["TOTAL_SUCURSAL"].":",0,0,'R');
        $archivo->Cell(35,5,"$".number_format($total_debito_sucursal,0),0,0,'R');
        $archivo->Cell(35,5,"$".number_format($total_credito_sucursal,0),0,0,'R');
        $archivo->Ln(5);
        $archivo->Cell(225,5,"",0,0,'L');
        $archivo->Cell(40,5,$textos["DIFERENCIA"].":",0,0,'R');
        $archivo->Cell(70,5,"$".number_format($total_debito_sucursal-$total_credito_sucursal,0),0,0,'C');
        $archivo->Ln(4);
    }

    function titulosDMD($archivo,$textos){
        $archivo->Ln(5);
        $archivo->SetFont('Arial','B',7);
        $archivo->SetFillColor(230,230,230);
        $archivo->Cell(30,4,$textos["DOCUMENTO"],1,0,'C',true);
        $archivo->Cell(30,4,$textos["TIPO_DOCUMENTO_CRUCE"],1,0,'C',true);
        $archivo->Cell(35,4,$textos["COMPROBANTE"],1,0,'C',true);
        $archivo->Cell(30,4,$textos["FECHA"],1,0,'C',true);
        $archivo->Cell(50,4,$textos["TERCERO"],1,0,'C',true);
        $archivo->Cell(50,4,$textos["NOMBRE_CUENTA"],1,0,'C',true);
        $archivo->Cell(20,4,$textos["ANEXO"],1,0,'C',true);
        $archivo->Cell(20,4,$textos["AUXILIAR"],1,0,'C',true);
        $archivo->Cell(35,4,$textos["DEBITO"],1,0,'C',true);
        $archivo->Cell(35,4,$textos["CREDITO"],1,0,'C',true);
        $archivo->Ln(4);
    }
?>

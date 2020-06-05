<?php

/**
*
* Copyright (C) 2008 Felinux Ltda
* Raul Mauricio Oidor Lozano <ozzymauricio75@gmail.com>
*
* Este archivo es parte de:
* Jobdaily:: Sofware empresarial a la medida
*
* Este programa es software libre: usted puede redistribuirlo y/o
* modificarlo  bajo los términos de la Licencia Pública General GNU
* publicada por la Fundación para el Software Libre, ya sea la versión 3
* de la Licencia, o (a su elección) cualquier versión posterior.
*
* Este programa se distribuye con la esperanza de que sea útil, pero
* SIN GARANTÍA ALGUNA; ni siquiera la garantía implícita MERCANTIL o
* de APTITUD PARA UN PROPÓSITO DETERMINADO. Consulte los detalles de
* la Licencia Pública General GNU para obtener una información más
* detallada.
*
* Debería haber recibido una copia de la Licencia Pública General GNU
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
**/

    $consulta_tercero = SQL::seleccionar(array("terceros"), array("*"), "documento_identidad = '".$datos->documento_identidad_tercero."'");
    $datos_tercero    = SQL::filaEnObjeto($consulta_tercero);
    $llave_localidad  = $datos_tercero->codigo_iso_localidad.'|'.$datos_tercero->codigo_dane_departamento_localidad.'|'.$datos_tercero->codigo_dane_municipio_localidad.'|'.$datos_tercero->tipo_localidad.'|'.$datos_tercero->codigo_dane_localidad;
    $localidad        = explode('|',SQL::obtenerValor("seleccion_localidades","nombre","id = '".$llave_localidad."'"));

    $nombreArchivo          = $rutasGlobales["archivos"]."/".$nombre_arc;
    $archivo                = new PDF("P","mm","Letter");

    $archivo->AddPage();
    $archivo->textoPiePagina = $textos["PAGINA"].' '.$archivo->PageNo();
    $archivo->SetFont('Arial','B',8);

    $archivo->Cell(180,8,$documento.": ".$consecutivo_documento,0,1,'R');

    $archivo->Ln(3);
    $archivo->Cell(40,8,$textos["FECHA_DOCUMENTO"]." :",0,0,'L');
    $archivo->SetFont('Arial','',8);
    $archivo->Cell(120,8,$datos->fecha_documento,0);

    $archivo->Ln(4);
    $archivo->SetFont('Arial','B',8);
    $archivo->Cell(40,8,$textos["SUCURSAL_GENERA"]." :",0,0,'L');
    $archivo->SetFont('Arial','',8);
    $archivo->Cell(120,8,$sucursal_genera,0);

    $archivo->Ln(4);
    $archivo->SetFont('Arial','B',8);
    $archivo->Cell(40,8,$textos["TERCERO"]." :",0,0,'L');
    $archivo->SetFont('Arial','',8);
    $archivo->Cell(120,8,$tercero,0);

    $archivo->Ln(4);
    $archivo->SetFont('Arial','B',8);
    $archivo->Cell(40,8,$textos["TERCERO_UBICACION"]." :",0,0,'L');
    $archivo->SetFont('Arial','',8);
    $archivo->Cell(120,8,$localidad[0],0);

    $archivo->Ln(4);
    $archivo->SetFont('Arial','B',8);
    $archivo->Cell(40,8,$textos["TERCERO_DIRECCION"]." :",0,0,'L');
    $archivo->SetFont('Arial','',8);
    $archivo->Cell(120,8,$datos_tercero->direccion_principal,0);

    $archivo->Ln(4);
    $archivo->SetFont('Arial','B',8);
    $archivo->Cell(40,8,$textos["TERCERO_TELEFONO"]." :",0,0,'L');
    $archivo->SetFont('Arial','',8);
    $archivo->Cell(120,8,$datos_tercero->telefono_principal,0);

    $archivo->Ln(12);

    $archivo->generarCabeceraTabla( array(  $textos["SUCURSAL"],
                                            $textos["CUENTA"],
                                            $textos["TERCERO_CUENTA"],
                                            $textos["DEBITOS"],
                                            $textos["CREDITOS"],
                                            $textos["OPCIONES"] ),
                                    array(20, 40, 40, 20, 20, 50));

    $archivo->Ln(4);
    $archivo->SetFont('Arial','',7);
    $archivo->SetFillColor(224,235,255);
    $ancho_columnas = array(20, 40, 40, 20, 20, 50);
    $alineacion     = array("L", "L", "L", "R", "R", "L");
    $altos          = 0;
    $rellenar       = true;
    foreach ($lista_PDF as $fila) {
        $imprime_cabecera = $archivo->breakCell($alto_celdas_PDF[$altos]);
        if($imprime_cabecera){
            $archivo->textoPiePagina = $textos["PAGINA"].' '.$archivo->PageNo();
            $archivo->Ln(12);
            $archivo->generarCabeceraTabla(array($textos["SUCURSAL"],$textos["CUENTA"],$textos["TERCERO_CUENTA"],$textos["DEBITOS"],
                                                $textos["CREDITOS"],$textos["OPCIONES"] ),array(20, 40, 40, 20, 20, 50));
            $archivo->Ln(4);
            $archivo->SetFont('Arial','',7);
            $archivo->SetFillColor(224,235,255);
        }
        $celdas = 0;
        foreach ($fila as $celda) {
            if ($celdas != 5) {
                $archivo->Cell($ancho_columnas[$celdas], $alto_celdas_PDF[$altos], htmlspecialchars_decode($celda), "LRT", 0, $alineacion[$celdas], $rellenar);
            } else {
                $archivo->MultiCell($ancho_columnas[$celdas], 3, $celda, "LRT", $alineacion[$celdas], $rellenar);
            }
            $celdas++;
        }
        $rellenar = !$rellenar;
        $altos++;
    }

    $archivo->Cell(array_sum($ancho_columnas), 0, "", "T");

    $archivo->Ln(0);
    $archivo->SetFillColor(230, 230, 230);
    $archivo->Cell(100,3,"",0,0,'C');
    $archivo->Cell(20,3,"$ ".number_format($total_debitos),"LRBT",0,'R', true);
    $archivo->Cell(20,3,"$ ".number_format($total_creditos),"LRBT",0,'R', true);

    $archivo->Output($nombreArchivo, "F");
?>

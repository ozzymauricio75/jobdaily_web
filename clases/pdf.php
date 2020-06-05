<?php
/**
*
* Copyright (C) 2020 Jobdaily
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

// Requiere libreria de terceros (FPDF - www.fpdf.org)
require "fpdf/fpdf.php";

class PDF extends FPDF {

    var $textoCabecera;
    var $textoTitulo;
    var $textoResolucion;

    // Generar tabla
    function generarCabeceraTabla($columnas, $anchoColumnas,$borde=1,$alineacion="C",$rellenar=true) {
        $this->SetFillColor(230, 230, 230);
        $this->SetTextColor(0);
        $this->SetDrawColor(0);
        $this->SetLineWidth(.1);
        $this->SetFont("", "", "");

        for($i = 0 ; $i < count($columnas); $i++) {
            $this->Cell($anchoColumnas[$i], 4, $columnas[$i], $borde, 0, $alineacion, $rellenar);
        }
    }

    // Generar tabla
    function generarContenidoTabla($filas, $anchoColumnas, $alineacionColumnas = "", $formatoColumnas = "") {
        $this->Ln(0);
        $this->SetFillColor(224,235,255);
        $this->SetTextColor(0);
        $this->SetFont("");

        $rellenar = true;

        foreach($filas as $fila) {
            $celdas = 0;

            foreach ($fila as $celda) {
                switch (strtoupper($alineacionColumnas[$celdas])) {
                    case "I" :
                        $alineacion = "L";
                        break;
                    case "D" :
                        $alineacion = "R";
                        break;
                    case "C" :
                        $alineacion = "C";
                        break;
                    default :
                        $alineacion = "L";
                        break;
                }

                $this->Cell($anchoColumnas[$celdas], 3, htmlspecialchars_decode($celda), "LRT", 0, $alineacion, $rellenar);
                $celdas++;
            }

            $this->Ln();
            $rellenar = !$rellenar;
        }

        $this->Cell(array_sum($anchoColumnas), 0, "", "T");
    }

    // Encabezado
    function Header() {
        global $sem, $imagenesGlobales;

        $this->SetLineWidth(0.2);
        $this->SetFont("Arial", "B", 7);
        $this->SetXY(0,12);
        $this->MultiCell(0, 2.5, $this->textoCabecera, 0, "R");

        $this->SetFont("Arial", "B", 10);
        $this->SetXY(5,20);
        $this->MultiCell(0, 2.5,$this->textoTitulo, 0, "C");

        $this->SetFont("Arial", "B", 7);
        $this->SetXY(155,12);
        $this->MultiCell(0, 2.5,$this->textoResolucion, 0, "L");

        $this->SetFont("Arial", "", 7);
        $this->Image($imagenesGlobales["logoClienteReportes"], 10, 10, 20);
        $this->SetXY(10, 22);
        $this->Cell(0, 3, $sem["nitCliente"], 0);
        $this->Ln(3);
        $this->Cell(0, 3, $sem["direccionCliente"], 0);
        $this->Ln(3);
        $this->Cell(0, 3, $sem["telefonoCliente"], "B");
        $this->Ln(6);
        $this->SetLineWidth(0.1);
    }

    function Cabecera($Y,$empresa,$nit,$direccion,$titulo_listado) {//Solo para reportes sin imagen

        $this->SetFont("Arial", "", 7);
        $this->SetXY(10, $Y);
        if (!empty($empresa)){
            $this->Cell(0, 3, $empresa, 0);
            $this->Ln(3);
        }
        if (!empty($nit)){
            $this->Cell(0, 3, $nit, 0);
            $this->Ln(3);
        }
        if (!empty($direccion)){
            $this->Cell(0, 3, $direccion, 0);
            $this->Ln(3);
        }
        if (!empty($titulo_listado)){
            $this->Cell(10, 3, $titulo_listado, 0);
        }
        $this->Ln(5);
    }

    function Footer(){
        //Posicion desde la parte inferior de la pagina (1,5 cm)
        $this->SetY(-15);

        //Intento 1, pie de pagina
        $this->Cell(0, 0, $this->textoPiePagina, 0, "L");

        //Seleccion del tipo de letra
        $this->SetFont('Arial','I',8);
        $this->SetTextColor(128);

        //Numero de pagina
        $this->Cell(0,10,''.$this->PageNo(),0,0,'C');
    }

    function PieDePagina($y,$texto){
        $this->SetXY(10,$y);
        $this->SetFont('Arial','I',8);
        $this->SetTextColor(128);
        $this->Cell(0,5,''.$texto,0,0,'R');
    }

    var $widths;
    var $aligns;

    function SetWidths($w){
        //Set the array of column widths
        $this->widths=$w;
    }

    function SetAligns($a){
        //Set the array of column alignments
        $this->aligns=$a;
    }

    function Row($data,$generaCabecera=false, $titulos="",$anchos=""){
        //Calculate the height of the row
        $nb=0;

        for($i=0;$i<count($data);$i++)
            $nb=max($nb, $this->NbLines($this->widths[$i], $data[$i]) );

        $h=5*$nb;
        $salto_linea=5*$nb;

        //Issue a page break first if needed
        $this->CheckPageBreak($h,$generaCabecera,$titulos,$anchos);

        //Draw the cells of the row
        for($i=0;$i<count($data);$i++){
            $w=$this->widths[$i];
            $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
            //Save the current position
            $x=$this->GetX();
            $y=$this->GetY();
            //Draw the border
            $this->Rect($x,$y,$w,$h);
            //Print the text
            $this->MultiCell($w,5,$data[$i],0,$a,false);
            //Put the position to the right of the cell
            $this->SetXY($x+$w,$y);
        }

        //Go to the next line
        $this->Ln($salto_linea);
    }

    function NewPage($data){
        //Calculate the height of the row
        $nb=0;

        for($i=0;$i<count($data);$i++)
            $nb=max($nb, $this->NbLines($this->widths[$i], $data[$i]) );

        $h=5*$nb;
        $salto_linea=5*$nb;

        if($this->GetY()+$h>$this->PageBreakTrigger)
            $salto_pagina = true;
        else
            $salto_pagina = false;

        return($salto_pagina);
    }

    function CheckPageBreak($h,$generaCabecera=false, $titulos="",$anchos=""){
        //If the height h would cause an overflow, add a new page immediately
        if($this->GetY()+$h>$this->PageBreakTrigger){
            $this->AddPage($this->CurOrientation);
            if($generaCabecera){
                $this->SetFont('Arial','B',6);
                $this->generarCabeceraTabla($titulos, $anchos);
                $this->Ln(4);
            }
        }
    }

    function NbLines($w,$txt){
        //Computes the number of lines a MultiCell of width w will take
        $cw=&$this->CurrentFont['cw'];

        if($w==0)
            $w=$this->w-$this->rMargin-$this->x;

        $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
        $s=str_replace("\r",'',$txt);
        $nb=strlen($s);

        if($nb>0 and $s[$nb-1]=="\n")
            $nb--;

        $sep=-1;
        $i=0;
        $j=0;
        $l=0;
        $nl=1;

        while($i<$nb) {
            $c=$s[$i];
            if($c=="\n") {
                $i++;
                $sep=-1;
                $j=$i;
                $l=0;
                $nl++;
                continue;
            }if($c==' ')
                $sep=$i;

            $l+=$cw[$c];
            if($l>$wmax)
            {
                if($sep==-1)
                {
                    if($i==$j)
                        $i++;
                }
                else
                    $i=$sep+1;
                $sep=-1;
                $j=$i;
                $l=0;
                $nl++;
            }
            else
                $i++;
        }
        return $nl;
    }

    function breakCell($h=0){
        if($this->y+$h>$this->PageBreakTrigger && !$this->InHeader && !$this->InFooter && $this->AcceptPageBreak()){
            $salto_pagina = true;
        } else {
            $salto_pagina = false;
        }
        return($salto_pagina);
    }

    /*function breakCell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link=''){
        //Output a cell
        $k=$this->k;
        if($this->y+$h+4>$this->PageBreakTrigger && !$this->InHeader && !$this->InFooter && $this->AcceptPageBreak())
        {
            $salto_pagina = true;
        } else {
            $salto_pagina = false;
        }
        return($salto_pagina);
    }*/
}
?>

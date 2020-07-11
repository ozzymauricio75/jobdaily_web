<?php
/**
*
* Copyright (C) 2020 Jobdaily
* Raul Mauricio Oidor Lozano <ozzymauricio75@gmail.com>
*
* Este archivo es parte de:
* JOBDAILY :: Software empresarial a la medida
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
    $vistaConsulta = "ordenes_compra";
    $columnas      = SQL::obtenerColumnas($vistaConsulta);
    $llaveOrden    = explode("|",$forma_id);
    $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo_sucursal = '".$llaveOrden[0]."' AND fecha_documento = '".$llaveOrden[1]."' AND numero_consecutivo = '".$llaveOrden[2]."'");
    
    $datos         = SQL::filaEnObjeto($consulta);
    $nombre        = "";
    $nombreArchivo = "";

    do{
        $cadena        = Cadena::generarCadenaAleatoria(8);
        $nombre        = $datos->codigo_sucursal.$cadena.".pdf";
        $nombreArchivo = $rutasGlobales["archivos"]."/".$nombre;
    } while (is_file($nombreArchivo));

    $consecutivo                = $datos->numero_consecutivo;
    $fechaInicial               = $datos->fecha_documento;
    $fechaRegistra              = $datos->fecha_registra;
    $estado                     = $datos->estado;
    $observaciones              = $datos->observaciones;
    $descuento_global_1         = $datos->descuento_global1;
    $descuento_global_2         = $datos->descuento_global2;
    $descuento_financiero       = $datos->descuento_financiero;

    $usuario                    = SQL::obtenerValor("usuarios", "nombre", "codigo = '".$datos->codigo_usuario_orden_compra."'");
    $almacen                    = SQL::obtenerValor("sucursales","nombre","codigo = '".$datos->codigo_sucursal."'");
    
    // Datos de proveedor
    $tipo_persona = SQL::obtenerValor("terceros", "tipo_persona", "documento_identidad = '$datos->documento_identidad_proveedor' AND activo = '1'");
        
    if ($tipo_persona == '2' || $tipo_persona == '4') {

        //Genera digito de verificacion en nit
        $nit     = $datos->documento_identidad_proveedor;
        $array   = array(1 => 3, 4 => 17, 7 => 29, 10 => 43, 13 => 59, 2 => 7, 5 => 19, 8 => 37, 11 => 47, 14 => 67, 3 => 13,
                    6 => 23, 9 => 41, 12 => 53, 15 => 71);
        $x       = 0;
        $y       = 0;
        $z       = strlen($nit);
        $digitoV = '';
    
        for ($i = 0; $i < $z; $i++) {
            $y  = substr($nit, $i, 1);
            $x += ($y*$array[$z-$i]);
        }
    
        $y = $x%11;
    
        if ($y > 1) {
            $digitoV = 11-$y;
        } else {
            $digitoV = $y;
        }
        $nombre_proveedor = SQL::obtenerValor("terceros", "razon_social", "documento_identidad = '$nit' AND activo = '1'");
   
    } else{
        $primer_nombre    = SQL::obtenerValor("terceros", "primer_nombre", "documento_identidad ='$nit' AND activo ='1'");
        $segundo_nombre   = SQL::obtenerValor("terceros", "segundo_nombre", "documento_identidad ='$nit' AND activo ='1'");
        $primer_apellido  = SQL::obtenerValor("terceros", "primer_apellido", "documento_identidad ='$nit' AND activo ='1'");
        $segundo_apellido = SQL::obtenerValor("terceros", "segundo_apellido", "documento_identidad ='$nit' AND activo='1'");
        $nombre_proveedor = $primer_nombre." ".$segundo_nombre." ".$primer_apellido." ".$segundo_apellido;
    }

    $direccion_proveedor  = SQL::obtenerValor("terceros", "direccion_principal", "documento_identidad ='$nit' AND activo ='1'");
    $codigo_municipio     = SQL::obtenerValor("terceros", "codigo_dane_municipio_localidad", "documento_identidad ='$nit' AND activo ='1'");
    $ciudad_proveedor     = SQL::obtenerValor("municipios", "nombre", "codigo_dane_municipio ='$codigo_municipio'");
    //Datos vendedor
    $telefono_vendedor    = SQL::obtenerValor("vendedores_proveedor", "celular", "documento_proveedor ='$nit' AND activo ='1'");
    $correo_vendedor      = SQL::obtenerValor("vendedores_proveedor", "correo", "documento_proveedor ='$nit' AND activo ='1'");
    $primer_nombre        = SQL::obtenerValor("vendedores_proveedor", "primer_nombre", "documento_proveedor ='$nit' AND activo ='1'");
    $segundo_nombre       = SQL::obtenerValor("vendedores_proveedor", "segundo_nombre", "documento_proveedor ='$nit' AND activo ='1'");
    $primer_apellido      = SQL::obtenerValor("vendedores_proveedor", "primer_apellido", "documento_proveedor ='$nit' AND activo ='1'");
    $segundo_apellido     = SQL::obtenerValor("vendedores_proveedor", "segundo_apellido", "documento_proveedor ='$nit' AND activo ='1'");
    $nombre_vendedor      = $primer_nombre." ".$segundo_nombre." ".$primer_apellido." ".$segundo_apellido;

    //$proveedor            = SQL::obtenerValor("seleccion_proveedores","SUBSTRING_INDEX(nombre,'|',1)","id = '".$datos->documento_identidad_proveedor."'");
    //$forma_liquida_linea        = SQL::obtenerValor("proveedores","forma_liquidacion_descuento_en_linea","documento_identidad = '".$datos->documento_identidad_proveedor."'");
    //$forma_liquida_global       = SQL::obtenerValor("proveedores","forma_liquidacion_descuento_global","documento_identidad = '".$datos->documento_identidad_proveedor."'");
    //$forma_liquida_tasa_credito = SQL::obtenerValor("proveedores","forma_liquidacion_tasa_credito","documento_identidad = '".$datos->documento_identidad_proveedor."'");

    $anchoColumnas      = array(20,50);
    $alineacionColumnas = array("I","I");

    $archivo = new PDF("L","mm","Letter");
    $archivo->textoCabecera  = $textos["FECHA"].": ".date("Y-m-d");
    $archivo->textoTitulo    = $textos["DOCUOCPR"];
    $archivo->textoPiePagina = "";

    $archivo->AddPage();
    $archivo->SetFont('Arial','B',8);

    $archivo->Ln(0);
    $archivo->Cell(60,4,"",0);
    $archivo->Cell(60,4,"",0);
    $archivo->Cell(130,4,$textos["ORDEN_COMPRA_PDF"].$consecutivo,0,1,'R');

    $archivo->Ln(1);
    $archivo->Cell(30,4,$textos["PROVEEDOR"]." :",0,0,'L');
    $archivo->SetFont('Arial','',8);
    $archivo->Cell(130,4,"".$nombre_proveedor,0,0,'L');
    $archivo->Cell(40,4,"",0,1,'R');

    $archivo->Ln(1);
    $archivo->Cell(30,4,$textos["NIT"]." :",0,0,'L');
    $archivo->SetFont('Arial','',8);
    $archivo->Cell(130,4,"".$nit."-".$digitoV,0,0,'L');
    $archivo->Cell(40,4,"",0,1,'R');

    $archivo->SetFont('Arial','B',8);
    $archivo->Ln(0);
    $archivo->Cell(30,4,$textos["COMPRADOR"]." :",0,0,'L');
    $archivo->SetFont('Arial','',8);
    $archivo->Cell(90,4,"".$usuario."",0);
    $archivo->Cell(40,4,"",0);
    $archivo->Cell(40,4,"",0,1,'R');

    $archivo->SetFont('Arial','B',8);
    $archivo->Ln(0);
    $archivo->Cell(30,4,$textos["SUCURSAL"]." :",0,0,'L');
    $archivo->SetFont('Arial','',8);
    $archivo->Cell(90,4,"".$almacen."",0);
    $archivo->Cell(40,4,"",0);

    if ($descuento_global_1 != 0) {
        $archivo->SetFont('Arial','B',8);
        $archivo->Cell(35,4,$textos["DESCUENTO_GLOBAL1_PDF"]." :",0,0,'L');
        $archivo->SetFont('Arial','',8);
        $archivo->Cell(120,4,$descuento_global_1." %",0);
        $archivo->Cell(40,4,"",0,1,'R');
    } else {
        $archivo->Cell(40,4,"",0,1,'R');
    }

    $archivo->SetFont('Arial','B',8);

    // Calculo del dia en el que se hizo la orden
    $vector_fecha = explode("-",$fechaInicial);
    $anno         = (int)$vector_fecha[0];
    $mes          = $vector_fecha[1];
    $dia          = $vector_fecha[2];

    $fecha     = mktime(0,0,0,$mes,$dia,$anno);
    $diasemana = date("w",$fecha);

    $archivo->Ln(0);
    $archivo->Cell(30,4,$textos["FECHA"].":",0,0,'L');
    $archivo->SetFont('Arial','',8);
    $archivo->Cell(90,4,$textos["DIA_".$diasemana.""]." ".$dia." de ".$textos["MES_".$mes.""]." de ".$anno,0);
    $archivo->Cell(40,4,"",0,0,'R');

    if ($descuento_global_2 != 0) {
        $archivo->SetFont('Arial','B',8);
        $archivo->Cell(35,4,$textos["DESCUENTO_GLOBAL2_PDF"]." :",0,0,'L');
        $archivo->SetFont('Arial','',8);
        $archivo->Cell(120,4,"".$descuento_global_2." %",0);
        $archivo->Cell(40,4,"",0,1,'R');
    } else {
        $archivo->Cell(40,4,"",0,1,'R');
    }

    $archivo->SetFont('Arial','B',8);
    $archivo->Ln(0);
    $archivo->Cell(30,4,$textos["OBSERVACIONES"]." :",0,0,'L');
    $archivo->SetFont('Arial','',8);
    $archivo->Cell(130,4,"".$observaciones."",0);

    if ($descuento_financiero != 0) {
        $archivo->SetFont('Arial','B',8);
        $archivo->Cell(35,4,$textos["FINANCIERO"]." :",0,0,'L');
        $archivo->SetFont('Arial','',8);
        $archivo->Cell(120,4,"".$descuento_financiero." %",0);
        $archivo->Cell(40,4,"",0,1,'R');
    } else {
        $archivo->Cell(40,4,"",0,1,'R');
    }

    $archivo->SetFont('Arial','B',6);
    $archivo->SetFillColor(225,225,225);

    $archivo->Ln(6);
    $archivo->Cell(15,4,$textos["ARTICULO"],1,0,'C',true);
    $archivo->Cell(25,4,$textos["REFERENCIA"],1,0,'C',true);
    $archivo->Cell(20,4,$textos["SUCURSAL_RECIBE"],1,0,'C',true);
    $archivo->Cell(18,4,$textos["CANTIDAD"],1,0,'C',true);
    $archivo->Cell(17,4,$textos["REQUERIDO"],1,0,'C',true);
    $archivo->Cell(25,4,$textos["VALOR_UNITARIO"],1,0,'C',true);
    $archivo->Cell(15,4,$textos["DESCUENTO_1_PDF"],1,0,'C',true);
    $archivo->Cell(15,4,$textos["DESCUENTO_2_PDF"],1,0,'C',true);
    $archivo->Cell(25,4,$textos["CON_DESCUENTO"],1,0,'C',true);
    $archivo->Cell(20,4,$textos["IVA_ARTICULO"],1,0,'C',true);
    $archivo->Cell(25,4,$textos["FORMA_PAGO_PDF"],1,0,'C',true);
    $archivo->Cell(15,4,$textos["FINANCIACION_PDF"],1,0,'C',true);
    $archivo->Cell(25,4,$textos["TOTAL_ITEM"],1,0,'C',true);

    $vistaConsulta          = "movimiento_ordenes_compra";
    $columnas               = SQL::obtenerColumnas($vistaConsulta);
    $consulta               = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo_sucursal_destino = '".$llaveOrden[0]."' AND fecha_registra = '".$llaveOrden[1]."' AND codigo_orden_compra = '".$datos->codigo."'");
    $total                  = 0;
    $total_iva              = 0;
    $sucursales             = array();
    $i                      = 0;
    $valor_descuento        = 0;
    $valor_total            = 0;
    $total_todos_descuentos = 0;
    $total_entradas         = 0;
    $total_descuentos_linea = 0;
    $descuento_financiacion = 0;
    $subtotal_factura       = 0;
    $valor_con_iva          = 0;

    while ($datos_articulo = SQL::filaEnObjeto($consulta)) {

        if($archivo->FillColor != sprintf('%.3F %.3F %.3F rg',1,1,1)){
            $archivo->SetFillColor(255,255,255);
        }else{
            $archivo->SetFillColor(240,240,240);
        }

        $financiacion       = "-";
        $plazo              = SQL::obtenerValor("plazos_pago_proveedores","nombre","codigo ='".$datos->codigo_numero_dias_pago."'");
        $mostrar_descuento1 = number_format($datos->descuento_global1, 2,'.' ,',');
        $mostrar_descuento1 = $mostrar_descuento1." %";
        //$mostrar_descuento2 = number_format($datos_articulo->descuento_global2, 2,'.' ,',');
        //$mostrar_descuento2 = $mostrar_descuento2." %";
        $descuento_global1  = $datos->descuento_global1;
        //$descuento2         = $datos_articulo->descuento_global2;

        $pago           = $textos["FORMA_PAGO_PDF"]." : ".$plazo;
        $financiacion   = "-";    
        /*if ($datos_articulo->forma_pago == 2) {
            $pago           = $textos["CREDITO"]." : ".$plazo;
            $financiacion   = number_format($datos_articulo->tasa_credito,2,'.',',');
            $financiacion   = $financiacion." %";
        } else {
            $pago           = $textos["CONTADO"]." : ".$plazo;
            $financiacion   = "-";
        }*/

        /*if ($datos_articulo->tipo_inventario == '2') {
              $pago               = $textos["OBSEQUIO"];
              $financiacion       = "-";
              $mostrar_descuento1 = "-";
              $mostrar_descuento2 = "-";
        } else if ($datos_articulo->tipo_inventario == '3'){
              $pago = $textos["CONSIGNACION"];
        }*/

        // Obtener porcentaje vigente de la tasa de compra del articulo
        $consulta_impuesto  = SQL::seleccionar(array("vigencia_tasas"), array("porcentaje"), "codigo_tasa='".$datos_articulo->codigo_tasa_impuesto."'", "", "fecha DESC", 1);
        if (SQL::filasDevueltas($consulta_impuesto)) {
            $datos_impuesto = SQL::filaEnObjeto($consulta_impuesto);
            $valor_impuesto = $datos_impuesto->porcentaje;
        } else {
            $valor_impuesto = 0;
        }

        $valor_compra           = $datos_articulo->valor_total;
        $cantidad               = $datos_articulo->cantidad_total;

        //$tasa_pago_credito      = $datos_articulo->tasa_credito;
        // Valores descuento en linea
        /*if($forma_liquida_linea == 1){
            // se calcula sobre el valor unitario
            $valor_descuento_1        = (($valor_compra * $descuento1)/100);
            $valor_porcentaje_d1      = $valor_descuento_1;
            $valor_descuento_1        = $valor_compra - $valor_descuento_1;
            $subtotal2                = $valor_descuento_1 * $cantidad;

            $valor_descuento_2        = (($valor_descuento_1 * $descuento2)/100);
            $valor_descuento          = $valor_descuento_1 - $valor_descuento_2;
            $subtotal                 = $valor_descuento * $cantidad;
            $subtotal2                = $valor_descuento * $cantidad;

            if ($forma_liquida_tasa_credito == '1' && $tasa_pago_credito>0){
                $descuento_financiacion += (($subtotal * $tasa_pago_credito) / 100);
            }

            // Valores descuento en linea
            if($forma_liquida_global == 1){
                // se calcula sobre el valor unitario
                $valor_descuento_global1 = (($valor_descuento) * $descuento_global_1)/100;
                $valor_porcentaje_dg1    = $valor_descuento_global1;
                $valor_descuento_global1 = $valor_descuento - $valor_descuento_global1;

                $valor_descuento_global2 = (($valor_descuento_global1) * $descuento_global_2)/100;
                $valor_descuento         = $valor_descuento_global1 - $valor_descuento_global2;
                $costo                   = (int)$valor_descuento;
                $subtotal                = $valor_descuento * $cantidad;
                $subtotal2               = $valor_descuento * $cantidad;
                if ($forma_liquida_tasa_credito == '2' && $tasa_pago_credito>0){
                    $descuento_financiacion += (($subtotal * $tasa_pago_credito) / 100);
                }

                $total_todos_descuentos  += ($valor_porcentaje_dg1+$valor_descuento_global2)*$cantidad;
            } else if ($forma_liquida_global == 2){
                // se calcula sobre el valor total
                $valor_descuento_global1 = (($subtotal) * $descuento_global_1)/100;
                $valor_porcentaje_dg1    = $valor_descuento_global1;
                $valor_descuento_global1 = $subtotal - $valor_descuento_global1;

                $valor_descuento_global2 = (($valor_descuento_global1) * $descuento_global_2)/100;
                $valor_descuento         = $valor_descuento_global1 - $valor_descuento_global2;
                $subtotal2               = $valor_descuento * $cantidad;
                $costo                   = (int)($valor_descuento/$cantidad);
                if ($forma_liquida_tasa_credito == '2' && $tasa_pago_credito>0){
                    $descuento_financiacion += (($subtotal2 * $tasa_pago_credito) / 100);
                }

                $total_todos_descuentos += ($valor_porcentaje_dg1+$valor_descuento_global2)*$cantidad;
            } else {
                // se calcula sobre el total de la factura
                $costo                   = (int)($subtotal/$cantidad);
                $total_entradas          += $subtotal;
                $valor_descuento_global1 = (($total_entradas) * $descuento_global_1)/100;
                $valor_porcentaje_dg1    = $valor_descuento_global1;
                $valor_descuento_global1 = $total_entradas - $valor_descuento_global1;
                $valor_descuento_global2 = (($valor_descuento_global1) * $descuento_global_2)/100;
                $valor_total             = $valor_descuento_global1 -$valor_descuento_global2;

                $subtotal = (int)($subtotal - (($subtotal * $descuento_global_1)/100));
                $subtotal = (int)($subtotal - (($subtotal * $descuento_global_2)/100));
                if ($forma_liquida_tasa_credito == '2' && $tasa_pago_credito>0){
                    $descuento_financiacion += (($subtotal * $tasa_pago_credito) / 100);
                }

                $total_descuentos_linea  +=($valor_porcentaje_d1+$valor_descuento_2)*$cantidad;
                $total_todos_descuentos += $valor_porcentaje_dg1+$valor_descuento_global2;
            }
        } else {
            // Valores descuento en linea
            if($forma_liquida_global == 2){
                // se calcula sobre el valor total
                $subtotal              = $valor_compra * $cantidad;

                $valor_descuento_1     = (($subtotal * $descuento1)/100);
                $valor_porcentaje_d1   = $valor_descuento_1;
                $valor_descuento_1     = $subtotal - $valor_descuento_1;

                $valor_descuento_2     = (($valor_descuento_1 * $descuento2)/100);
                $valor_descuento       = $valor_descuento_1 - $valor_descuento_2;
                if ($forma_liquida_tasa_credito == '1' && $tasa_pago_credito>0){
                    $descuento_financiacion += (($valor_descuento * $tasa_pago_credito) / 100);
                }

                // se calcula sobre el valor total
                $valor_descuento_global1 = (($valor_descuento) * $descuento_global_1)/100;
                $valor_porcentaje_dg1    = $valor_descuento_global1;
                $valor_descuento_global1 = $valor_descuento - $valor_descuento_global1;

                $valor_descuento_global2 = (($valor_descuento_global1) * $descuento_global_2)/100;
                $valor_descuento         = $valor_descuento_global1 - $valor_descuento_global2;
                $subtotal2               = $valor_descuento;
                $costo                   = (int)($valor_descuento/$cantidad);

                $total_todos_descuentos += ($valor_porcentaje_dg1+$valor_descuento_global2)*$cantidad;
            } else {
                // se calcula sobre el valor total
                $subtotal              = $valor_compra * $cantidad;

                $valor_descuento_1     = (($subtotal * $descuento1)/100);
                $valor_porcentaje_d1   = $valor_descuento_1;
                $valor_descuento_1     = $subtotal - $valor_descuento_1;

                $valor_descuento_2     = (($valor_descuento_1 * $descuento2)/100);
                $valor_descuento       = $valor_descuento_1 - $valor_descuento_2;
                $costo                 = (int)($valor_descuento/$cantidad);
                $subtotal2             = $valor_descuento;

                // se calcula sobre el total de la factura
                $total_entradas          += $subtotal;
                $valor_descuento_global1 = (($total_entradas) * $descuento_global_1)/100;
                $valor_porcentaje_dg1    = $valor_descuento_global1;
                $valor_descuento_global1 = $total_entradas - $valor_descuento_global1;

                $valor_descuento_global2 = (($valor_descuento_global1) * $descuento_global_2)/100;
                $valor_total             = $valor_descuento_global1 -$valor_descuento_global2;

                $total_descuentos_linea  +=($valor_porcentaje_d1+$valor_descuento_2)*$cantidad;
                $total_todos_descuentos  = $valor_porcentaje_dg1+$valor_descuento_global2;
            }
            if ($forma_liquida_tasa_credito == '2' && $tasa_pago_credito>0){
                $descuento_financiacion += (($total_todos_descuentos * $tasa_pago_credito) / 100);
            }
        }*/

        $fecha_hoy          = date("Y-m-d");
        $id_tasa_articulo   = SQL::obtenerValor("articulos","codigo_impuesto_compra","codigo = '".$datos_articulo->codigo_articulo."'");
        $fecha_tasa         = SQL::obtenerValor("vigencia_tasas","MAX(fecha)","codigo_tasa='".$id_tasa_articulo."' AND fecha<='".$fecha_hoy."'");
        $valorbase_articulo = SQL::obtenerValor("vigencia_tasas","valor_base","codigo_tasa='".$id_tasa_articulo."'AND fecha LIKE '".$fecha_tasa."'");

        //$valor_articulo = ($datos_articulo->valor_compra - (($datos_articulo->valor_compra * $descuento1) /100));
        //$valor_articulo = ($valor_articulo - (($valor_articulo * $descuento2) /100));

        /*if ($forma_liquida_tasa_credito == '1'){
            $valor_articulo = ($valor_articulo + (($valor_articulo * $tasa_pago_credito) /100));
        }
        $valor_articulo = ($valor_articulo - (($valor_articulo * $descuento_global_1) /100));
        $valor_articulo = ($valor_articulo - (($valor_articulo * $descuento_global_2) /100));
        if ($forma_liquida_tasa_credito == '2'){
            $valor_articulo = ($valor_articulo + (($valor_articulo * $tasa_pago_credito) /100));
        }
        if($valor_articulo >= $valorbase_articulo){
            $valor_articulo = ($valor_articulo * $cantidad);
            $valor_con_iva += ($valor_articulo + (($valor_articulo*$valor_impuesto)/100));
            $total_iva     += (($valor_articulo*$valor_impuesto)/100);
        }*/

        if($valor_total > 0){
            $total = $valor_total;
        }

        $subtotal_factura   = $subtotal_factura + $total;
        $sucursales[]       = $datos_articulo->codigo_sucursal;

        $sucursal_entrega   = SQL::obtenerValor("sucursales","nombre","codigo = '".$datos_articulo->codigo_sucursal."'");
        $articulo           = SQL::obtenerValor("articulos","codigo","codigo = '".$datos_articulo->codigo_articulo."'");

        $archivo->SetFont('Arial','',6);

        $id_unidad     = $datos_articulo->codigo_unidad_medida;
        $nombre_unidad = SQL::obtenerValor("unidades","nombre","codigo='".$id_unidad."'");
        $cantidad      = number_format($datos_articulo->cantidad_total)." ".$nombre_unidad;
/////////////////////////////////////////////////////////////////////////////////////////////////
        $archivo->Ln(4);
        $archivo->Cell(15,4,$articulo,1,0,'C',true);
        $archivo->Cell(25,4,$datos_articulo->referencia_articulo,1,0,'L',true);
        $archivo->Cell(20,4,$sucursal_entrega,1,0,'L',true);
        $archivo->Cell(18,4,$cantidad,1,0,'C',true);
        $archivo->Cell(17,4,$datos_articulo->fecha_entrega,1,0,'C',true);
        $archivo->Cell(25,4,"$ ".number_format($valor_compra),1,0,'R',true);
        $archivo->Cell(15,4,$mostrar_descuento1,1,0,'C',true);
        $archivo->Cell(15,4,$mostrar_descuento2,1,0,'C',true);
        $archivo->Cell(25,4,"$ ".number_format($costo),1,0,'R',true);
        $archivo->Cell(20,4,$valor_impuesto."%",1,0,'R',true);
        $archivo->Cell(25,4,$pago,1,0,'L',true,"",true);
        $archivo->Cell(15,4,$financiacion,1,0,'C',true);
        $archivo->Cell(25,4,"$ ".number_format($subtotal2),1,0,'R',true);
/////////////////////////////////////////////////////////////////////////////////////////////////
        $imprime_cabecera = $archivo->breakCell(8);

        if($imprime_cabecera){
            $archivo->Ln(4);
            $archivo->SetFont('Arial','B',8);
            $archivo->Cell(60,4,"",0);
            $archivo->Cell(60,4,"",0);
            $archivo->Cell(130,4,$textos["ORDEN_COMPRA"].$consecutivo,0,1,'R');

            $archivo->SetFont('Arial','B',8);
            $archivo->Ln(4);
            $archivo->Cell(30,4,$textos["SUCURSAL"]." :",0,0,'L');
            $archivo->SetFont('Arial','',8);
            $archivo->Cell(90,4,"".$almacen."",0);
            $archivo->Cell(40,4,"",0);

            $archivo->SetFont('Arial','B',6);
            $archivo->SetFillColor(225,225,225);

            $archivo->Ln(6);
            $archivo->Cell(15,4,$textos["ARTICULO"],1,0,'C',true);
            $archivo->Cell(25,4,$textos["REFERENCIA"],1,0,'C',true);
            $archivo->Cell(20,4,$textos["SUCURSAL_RECIBE"],1,0,'C',true);
            $archivo->Cell(18,4,$textos["CANTIDAD"],1,0,'C',true);
            $archivo->Cell(17,4,$textos["REQUERIDO"],1,0,'C',true);
            $archivo->Cell(25,4,$textos["VALOR_BASE"],1,0,'C',true);
            $archivo->Cell(15,4,$textos["DESCUENTO_1_PDF"],1,0,'C',true);
            $archivo->Cell(15,4,$textos["DESCUENTO_2_PDF"],1,0,'C',true);
            $archivo->Cell(25,4,$textos["CON_DESCUENTO"],1,0,'C',true);
            $archivo->Cell(20,4,$textos["PORCENTAJE_IVA"],1,0,'C',true);
            $archivo->Cell(25,4,$textos["FORMA_PAGO_PDF"],1,0,'C',true);
            $archivo->Cell(15,4,$textos["FINANCIACION_PDF"],1,0,'C',true);
            $archivo->Cell(25,4,$textos["TOTAL_ITEM"],1,0,'C',true);
        }

        $i++;
    }

    if($archivo->FillColor != sprintf('%.3F %.3F %.3F rg',1,1,1)){
        $archivo->SetFillColor(255,255,255);
    }else{
        $archivo->SetFillColor(240,240,240);
    }
    $archivo->Ln(4);
    $archivo->SetFont('Arial','B',6);
    $archivo->Cell(235,4,$textos["SUBTOTAL"].": ",0,0,'R');
    $archivo->SetFont('Arial','',6);
    $archivo->Cell(25,4,"$ ".number_format($subtotal_factura),1,0,'R',true);

    /*if ($forma_liquida_tasa_credito=='1' && $descuento_financiacion>0){
        if($archivo->FillColor != sprintf('%.3F %.3F %.3F rg',1,1,1)){
            $archivo->SetFillColor(255,255,255);
        }else{
            $archivo->SetFillColor(240,240,240);
        }
        $archivo->Ln(4);
        $archivo->SetFont('Arial','B',6);
        $archivo->Cell(235,4,$textos["FINANCIACION"].": ",0,0,'R');
        $archivo->SetFont('Arial','',6);
        $archivo->Cell(25,4,"$ ".number_format($descuento_financiacion),1,0,'R',true);
    }*/

    $total_todos_descuentos = 0;
    /*if ($forma_liquida_global=='3'){

        $total_todos_descuentos = (($subtotal_factura + $descuento_financiacion) * $descuento_global_1) / 100;
        $total_todos_descuentos = ($total_todos_descuentos + (($subtotal_factura - $total_todos_descuentos) * $descuento_global_2) / 100);
        if ($total_todos_descuentos > 0){
            if($archivo->FillColor != sprintf('%.3F %.3F %.3F rg',1,1,1)){
                $archivo->SetFillColor(255,255,255);
            }else{
                $archivo->SetFillColor(240,240,240);
            }
            $archivo->Ln(4);
            $archivo->SetFont('Arial','B',6);
            $archivo->Cell(235,4,$textos["TOTAL_DESCUENTOS"].": ",0,0,'R');
            $archivo->SetFont('Arial','',6);
            $archivo->Cell(25,4,"$ ".number_format($total_todos_descuentos),1,0,'R',true);
        }

        $subtotal_pdf = $subtotal_factura + $descuento_financiacion - $total_todos_descuentos;

        if ($subtotal_pdf > 0){
            if($archivo->FillColor != sprintf('%.3F %.3F %.3F rg',1,1,1)){
                $archivo->SetFillColor(255,255,255);
            }else{
                $archivo->SetFillColor(240,240,240);
            }
            $archivo->Ln(4);
            $archivo->SetFont('Arial','B',6);
            $archivo->Cell(235,4,$textos["SUBTOTAL"].": ",0,0,'R');
            $archivo->SetFont('Arial','',6);
            $archivo->Cell(25,4,"$ ".number_format($subtotal_pdf),1,0,'R',true);
        }
    } else {
        $subtotal_pdf = $subtotal_factura + $descuento_financiacion;
    }*/

    if($archivo->FillColor != sprintf('%.3F %.3F %.3F rg',1,1,1)){
        $archivo->SetFillColor(255,255,255);
    }else{
        $archivo->SetFillColor(240,240,240);
    }
    $archivo->Ln(4);
    $archivo->SetFont('Arial','B',6);
    $archivo->Cell(235,4,$textos["IVA"],0,0,'R');
    $archivo->SetFont('Arial','',6);
    $archivo->Cell(25,4,"$ ".number_format($total_iva),1,0,'R',true);

    if($archivo->FillColor != sprintf('%.3F %.3F %.3F rg',1,1,1)){
        $archivo->SetFillColor(255,255,255);
    }else{
        $archivo->SetFillColor(240,240,240);
    }
    $archivo->Ln(4);
    $archivo->SetFont('Arial','B',6);
    $archivo->Cell(235,4,$textos["TOTAL_ORDEN"],0,0,'R');
    $archivo->SetFont('Arial','',6);
    $total  = round($subtotal_pdf)+round($total_iva);
    $archivo->Cell(25,4,"$ ".number_format($total),1,0,'R',true);

    $archivo->Ln(10);
    $archivo->Ln(4);
    if($archivo->FillColor != sprintf('%.3F %.3F %.3F rg',1,1,1)){
        $archivo->SetFillColor(255,255,255);
    }else{
        $archivo->SetFillColor(240,240,240);
    }
    $archivo->SetFont('Arial','B',8);
    $archivo->Cell(260,4,$textos["NOTAS"],0,0,'L');

    $sucursales = array_unique($sucursales);
    foreach ($sucursales as $sucursal) {
        $consulta      = SQL::seleccionar(array("sucursales"), array("*"), "codigo = '".$sucursal."'");
        $datosSucursal = SQL::filaEnObjeto($consulta);
        $municipio     = SQL::obtenerValor("municipios","nombre","codigo_iso = '".$datosSucursal->codigo_iso."' AND codigo_dane_departamento = '".$datosSucursal->codigo_dane_departamento."' AND codigo_dane_municipio = '".$datosSucursal->codigo_dane_municipio."'");

        $archivo->Ln(4);
        $archivo->SetFont('Arial','',8);
        $archivo->Cell(260,4,$datosSucursal->nombre.": ".$municipio.", ".$datosSucursal->direccion_residencia.", Tel. ".$datosSucursal->telefono_1.".",0,0,'L');
    }
    $archivo->Output($nombreArchivo, "F");

    $consecutivo = SQL::obtenerValor("archivos","MAX(consecutivo)","codigo_sucursal='".$datos->codigo_sucursal."'");
    if ($consecutivo){
        $consecutivo++;
    } else {
        $consecutivo = 1;
    }

    $datos_archivo = array(
        "codigo_sucursal" => $datos->codigo_sucursal,
        "consecutivo"     => $consecutivo,
        "nombre"          => $nombre
    );
    SQL::insertar("archivos", $datos_archivo);
    $id_archivo = $datos->codigo_sucursal."|".$consecutivo;
?>

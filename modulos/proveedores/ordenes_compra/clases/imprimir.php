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
    //Datos del encabezado de la orden
    $vistaConsulta = "ordenes_compra";
    $columnas      = SQL::obtenerColumnas($vistaConsulta);
    $llaveOrden    = explode("|",$forma_id);
    $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo_sucursal = '".$llaveOrden[0]."' AND fecha_documento = '".$llaveOrden[1]."' AND numero_consecutivo = '".$llaveOrden[2]."'");
    
    $datos_ordenes = SQL::filaEnObjeto($consulta);
    $nombre        = "";
    $nombreArchivo = "";
    $texto_orden   = "Orden ";

    do{
        //$cadena      = Cadena::generarCadenaAleatoria(8);
        $nombre        = $texto_orden.$llaveOrden[2].".pdf";
        $nombreArchivo = $rutasGlobales["archivos"]."/".$nombre;
    } while (is_file($nombreArchivo));

    $consecutivo                = $datos_ordenes->numero_consecutivo;
    $fechaRegistra              = $datos_ordenes->fecha_registra;
    $estado                     = $datos_ordenes->estado;
    $descuento_global_1         = $datos_ordenes->descuento_global1;
    $descuento_global_2         = $datos_ordenes->descuento_global2;
    $descuento_financiero       = $datos_ordenes->descuento_financiero;
    $solicitante                = $datos_ordenes->solicitante;
    $prefijo_codigo_proyecto    = $datos_ordenes->prefijo_codigo_proyecto;
    $observaciones_orden        = $datos_ordenes->observaciones;

    $documento_comprador        = SQL::obtenerValor("compradores", "documento_identidad", "codigo = '".$datos_ordenes->codigo_comprador."'");
    $comprador                  = SQL::obtenerValor("menu_compradores", "NOMBRE_COMPLETO", "DOCUMENTO = '".$documento_comprador."'");
    $codigo_empresa             = SQL::obtenerValor("sucursales","codigo_empresa","codigo = '".$datos_ordenes->codigo_sucursal."'");
    $empresa                    = SQL::obtenerValor("empresas","razon_social","codigo = '".$codigo_empresa."'");
    $consorcio                  = SQL::obtenerValor("sucursales","nombre","codigo = '".$datos_ordenes->codigo_sucursal."'");
    $forma_pago                 = SQL::obtenerValor("plazos_pago_proveedores","nombre","codigo = '".$datos_ordenes->codigo_numero_dias_pago."'"); 
    $nit_empresa                = SQL::obtenerValor("empresas","documento_identidad_tercero","codigo = '".$codigo_empresa."'");
    $direccion_empresa          = SQL::obtenerValor("terceros","direccion_principal","documento_identidad = '".$nit_empresa."'");
    $celular_empresa            = SQL::obtenerValor("terceros","celular","documento_identidad = '".$nit_empresa."'");
    $nombre_proyecto            = SQL::obtenerValor("proyectos","nombre","codigo = '".$prefijo_codigo_proyecto."'");
   
    //Genera digito de verificacion en nit empresa
    $array = array(1 => 3, 4 => 17, 7 => 29, 10 => 43, 13 => 59, 2 => 7, 5 => 19, 8 => 37, 11 => 47, 14 => 67, 3 => 13,
               6 => 23, 9 => 41, 12 => 53, 15 => 71);
    $x       = 0;
    $y       = 0;
    $z       = strlen($nit_empresa);
    $digitoV_Empresa = '';
    
    for ($i = 0; $i < $z; $i++) {
        $y  = substr($nit_empresa, $i, 1);
        $x += ($y*$array[$z-$i]);
    }
    $y = $x%11;
    if ($y > 1) {
        $digitoV_Empresa = 11-$y;
    } else {
            $digitoV_Empresa = $y;
    }

    // Datos del movimiento de la orden
    $vistaConsultaMovimiento = "movimiento_ordenes_compra";
    $columnas                = SQL::obtenerColumnas($vistaConsultaMovimiento);
    $consulta                = SQL::seleccionar(array($vistaConsultaMovimiento), $columnas, "codigo_orden_compra = '".$datos_ordenes->codigo."'");
    $datos_movimiento_orden  = SQL::filaEnObjeto($consulta);
    $fechaEntrega            = $datos_movimiento_orden->fecha_entrega;

    // Datos de Proveedor
    $tipo_persona = SQL::obtenerValor("terceros", "tipo_persona", "documento_identidad = '$datos_ordenes->documento_identidad_proveedor' AND activo = '1'");
        
    if ($tipo_persona == '2' || $tipo_persona == '4') {

        //Genera digito de verificacion en nit
        $nit     = $datos_ordenes->documento_identidad_proveedor;
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
    $codigo_departamento  = SQL::obtenerValor("terceros", "codigo_dane_departamento_localidad", "documento_identidad ='$nit' AND activo ='1'");
    $codigo_municipio     = SQL::obtenerValor("terceros", "codigo_dane_municipio_localidad", "documento_identidad ='$nit' AND activo ='1'");
    $ciudad_proveedor     = SQL::obtenerValor("municipios", "nombre", "codigo_dane_departamento = '$codigo_departamento' AND codigo_dane_municipio ='$codigo_municipio'");

    //Datos Vendedor
    $telefono_vendedor    = SQL::obtenerValor("vendedores_proveedor", "celular", "documento_proveedor ='$nit' AND activo ='1' AND codigo = '".$datos_movimiento_orden->codigo_vendedor."'");
    $correo_vendedor      = SQL::obtenerValor("vendedores_proveedor", "correo", "documento_proveedor ='$nit' AND activo ='1' AND codigo = '".$datos_movimiento_orden->codigo_vendedor."'");
    $nombre_vendedor      = SQL::obtenerValor("menu_vendedores_proveedor", "NOMBRE_COMPLETO", "id = '".$datos_movimiento_orden->codigo_vendedor."'");

    // Calculo del dia en que se entrega la orden
    $dias         = array("Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado");
    $vector_fecha = explode("-", $fechaEntrega);
    $anno         = (int)$vector_fecha[0];
    $mes          = $vector_fecha[1];
    $dia          = $vector_fecha[2];

    $fecha        = mktime(0,0,0, $mes, $dia, $anno);
    $diasemana    = date("w", $fecha);
    //////////////////////////////ENCABEZADO DEL DOCUMENTO PDF ORDEN DE COMPRA/////////////////////////////
    $anchoColumnas      = array(20,50);
    $alineacionColumnas = array("I","I");

    $archivo = new PDF("L","mm","Letter");
    $archivo->textoCabecera  = $textos["FECHA"].": ".date("Y-m-d");
    $archivo->textoTitulo    = $textos["DOCUOCPR"];
    $archivo->textoPiePagina = $textos["PIE_PAGINA_EMPRESA"];

    $archivo->AddPage();
    $archivo->SetFont('Arial','B',8);

    $archivo->Ln(0);
    $archivo->Cell(60,4,"",0);
    $archivo->Cell(60,4,"",0);
    $archivo->Cell(130,4,$textos["ORDEN_COMPRA_PDF"].$consecutivo,0,1,'R');

    $archivo->SetFont('Arial','B',8);
    $archivo->Ln(0);
    $archivo->Cell(35,4,$textos["PROVEEDOR"]." :",0,0,'L');
    $archivo->SetFont('Arial','',8);
    $archivo->Cell(70,4,"".$nombre_proveedor,0,0,'L');
    $archivo->SetFont('Arial','B',8);
    $archivo->Cell(35,4,$textos["FECHA_ENTREGA"]." :",0,0,'L');
    $archivo->SetFont('Arial','',8);
    $archivo->Cell(70,4,"".$fechaEntrega,0,0,'L');
    $archivo->Cell(40,4,"",0,1,'R');

    $archivo->SetFont('Arial','B',8);
    $archivo->Ln(0);
    $archivo->Cell(35,4,$textos["NIT"]." :",0,0,'L');
    $archivo->SetFont('Arial','',8);
    $archivo->Cell(70,4,"".$nit."-".$digitoV,0,0,'L');
    $archivo->SetFont('Arial','B',8);
    $archivo->Cell(35,4,$textos["FACTURAR_A"]." :",0,0,'L');
    $archivo->SetFont('Arial','',8);
    $archivo->Cell(70,4,"".$empresa." - ".$consorcio,0,0,'L');
    $archivo->Cell(40,4,"",0,1,'R');

    $archivo->SetFont('Arial','B',8);
    $archivo->Ln(0);
    $archivo->Cell(35,4,$textos["DIRECCION"]." :",0,0,'L');
    $archivo->SetFont('Arial','',8);
    $archivo->Cell(70,4,"".$direccion_proveedor,0,0,'L');
    $archivo->SetFont('Arial','B',8);
    $archivo->Cell(35,4,$textos["NIT"]." :",0,0,'L');
    $archivo->SetFont('Arial','',8);
    $archivo->Cell(70,4,"".$nit_empresa."-".$digitoV_Empresa,0,0,'L');
    $archivo->Cell(40,4,"",0,1,'R');

    $archivo->SetFont('Arial','B',8);
    $archivo->Ln(0);
    $archivo->Cell(35,4,$textos["CIUDAD"]." :",0,0,'L');
    $archivo->SetFont('Arial','',8);
    $archivo->Cell(70,4,"".$ciudad_proveedor,0,0,'L');
    $archivo->SetFont('Arial','B',8);
    $archivo->Cell(35,4,$textos["FORMA_PAGO_PDF"]." :",0,0,'L');
    $archivo->SetFont('Arial','',8);
    $archivo->Cell(70,4,"".$forma_pago,0,0,'L');
    $archivo->Cell(40,4,"",0,1,'R');

    $archivo->SetFont('Arial','B',8);
    $archivo->Ln(0);
    $archivo->Cell(35,4,$textos["VENDEDOR"]." :",0,0,'L');
    $archivo->SetFont('Arial','',8);
    $archivo->Cell(70,4,"".$nombre_vendedor."",0);
    $archivo->SetFont('Arial','B',8);
    $archivo->Cell(35,4,$textos["COMPRADOR"]." :",0,0,'L');
    $archivo->SetFont('Arial','',8);
    $archivo->Cell(70,4,"".$comprador,0,0,'L');
    $archivo->Cell(40,4,"",0,1,'R');

    $archivo->SetFont('Arial','B',8);
    $archivo->Ln(0);
    $archivo->Cell(35,4,$textos["TELEFONO"]." :",0,0,'L');
    $archivo->SetFont('Arial','',8);
    $archivo->Cell(70,4,"".$telefono_vendedor,0,0,'L');
    $archivo->SetFont('Arial','B',8);
    $archivo->Cell(35,4,$textos["SOLICITANTE"]." :",0,0,'L');
    $archivo->SetFont('Arial','',8);
    $archivo->Cell(70,4,"".$solicitante,0,0,'L');
    $archivo->Cell(40,4,"",0,1,'R');

    if ($descuento_global_1 != 0) {
        $descuento_global_1 = number_format($descuento_global_1,2);
        
    } 

    $archivo->SetFont('Arial','B',8);
    $archivo->Ln(0);
    $archivo->Cell(35,4,$textos["PROYECTO"]." :",0,0,'L');
    $archivo->SetFont('Arial','',8);
    $archivo->Cell(70,4,"".$prefijo_codigo_proyecto." - ".$nombre_proyecto."",0);
    $archivo->SetFont('Arial','B',8);
    $archivo->Cell(35,4,$textos["DESCUENTO_GLOBAL1_PDF"]." :",0,0,'L');
    $archivo->SetFont('Arial','',8);
    $archivo->Cell(70,4,"".$descuento_global_1."%",0,0,'L');
    $archivo->Cell(40,4,"",0,1,'R');

    $archivo->SetFont('Arial','B',8);
    $archivo->Ln(0);
    $archivo->Cell(35,4,$textos["OBSERVACIONES"]." :",0,0,'L');
    $archivo->SetFont('Arial','',8);
    $archivo->Cell(150,4,"".$observaciones_orden."",0);
    $archivo->SetFont('Arial','B',8);

    if ($descuento_financiero != 0) {
        $archivo->SetFont('Arial','B',8);
        $archivo->Cell(35,4,$textos["FINANCIERO"]." :",0,0,'L');
        $archivo->SetFont('Arial','',8);
        $archivo->Cell(70,4,"".$descuento_financiero." %",0);
        $archivo->Cell(40,4,"",0,1,'R');
    } else {
        $archivo->Cell(40,4,"",0,1,'R');
    }
    //////////////////////////////FIN ENCABEZADO DEL DOCUMENTO PDF ORDEN DE COMPRA/////////////////////////////
    $archivo->SetFont('Arial','B',6);
    $archivo->SetFillColor(225,225,225);

    $archivo->Ln(6);
    $archivo->Cell(8,4,$textos["ITEMS"],1,0,'C',true);
    $archivo->Cell(24,4,$textos["REFERENCIA"],1,0,'C',true);
    $archivo->Cell(89,4,$textos["DESCRIPCION"],1,0,'C',true);
    $archivo->Cell(61,4,$textos["OBSERVACIONES_ARTICULO"],1,0,'C',true);
    $archivo->Cell(20,4,$textos["UNIDAD_MEDIDA"],1,0,'C',true);
    $archivo->Cell(12,4,$textos["CANTIDAD_PDF"],1,0,'C',true);
    $archivo->Cell(23,4,$textos["VALOR_UNITARIO"],1,0,'C',true);
    $archivo->Cell(23,4,$textos["VALOR_TOTAL"],1,0,'C',true);
    
    //Se lee el movimiento de la tabla movimientos
    $vistaConsulta          = "movimiento_ordenes_compra";
    $columnas               = SQL::obtenerColumnas($vistaConsulta);
    $consulta_movimiento    = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo_sucursal_destino = '".$datos_ordenes->codigo_sucursal."' AND codigo_orden_compra = '".$datos_ordenes->codigo."'");
    //Inicia valores en cero de totales
    $total                  = 0;
    $subtotal_orden         = 0;
    $total_descuento        = 0;
    $total_iva              = 0;
    $i                      = 0;
    $item                   = 1;
    $valor_descuento        = 0;
    $valor_total            = 0;
    $total_todos_descuentos = 0;
    $valor_con_iva          = 0;

    //Inicio la lectura del movimiento
    if (SQL::filasDevueltas($consulta_movimiento)) {

        while ($datos_movimiento = SQL::filaEnObjeto($consulta_movimiento)) {

            if($archivo->FillColor != sprintf('%.3F %.3F %.3F rg',1,1,1)){
                $archivo->SetFillColor(255,255,255);
            }else{
                $archivo->SetFillColor(240,240,240);
            }

            // Obtener porcentaje vigente de la tasa de compra del articulo
            $consulta_impuesto  = SQL::seleccionar(array("vigencia_tasas"), array("porcentaje"), "codigo_tasa='".$datos_movimiento->codigo_tasa_impuesto."'", "", "fecha DESC", 1);

            if (SQL::filasDevueltas($consulta_impuesto)) {
                $datos_impuesto = SQL::filaEnObjeto($consulta_impuesto);
                $valor_impuesto = $datos_impuesto->porcentaje;
            } else {
                $valor_impuesto = 0;
            }

            // Valores de cada articulo
            $cantidad_total          = $datos_movimiento->cantidad_total;
            $valor_unitario          = $datos_movimiento->valor_unitario;
            $valor_total             = $datos_movimiento->valor_total;
            $descuento_global1       = $datos_movimiento->descuento_global1;
            $valor_descuento_global1 = $datos_movimiento->valor_descuento_global1;
            $observaciones_articulo  = $datos_movimiento_orden->observaciones;

            //Hago la sumatoria
            $subtotal             = SQL::obtenerValor("movimiento_ordenes_compra","SUM(valor_total)","codigo_orden_compra='$codigo_orden_compra'");
            $porcentaje_descuento = SQL::obtenerValor("movimiento_ordenes_compra","descuento_global1","codigo_orden_compra='$codigo_orden_compra'");
            $valor_descuento      = SQL::obtenerValor("movimiento_ordenes_compra","SUM(valor_descuento_global1)","codigo_orden_compra='$codigo_orden_compra'");
            $total_iva            = SQL::obtenerValor("movimiento_ordenes_compra","SUM(valor_iva)","codigo_orden_compra='$codigo_orden_compra'");
            $total_orden          = SQL::obtenerValor("movimiento_ordenes_compra","SUM(neto_pagar)","codigo_orden_compra='$codigo_orden_compra'");
            $total_items          = SQL::obtenerValor("movimiento_ordenes_compra","COUNT(codigo_articulo)","codigo_orden_compra='$codigo_orden_compra'");

            $fecha_hoy          = date("Y-m-d");
            $id_tasa_articulo   = SQL::obtenerValor("articulos","codigo_impuesto_compra","codigo = '".$datos_movimiento->codigo_articulo."'");
            $fecha_tasa         = SQL::obtenerValor("vigencia_tasas","MAX(fecha)","codigo_tasa='".$id_tasa_articulo."' AND fecha<='".$fecha_hoy."'");
            $valorbase_articulo = SQL::obtenerValor("vigencia_tasas","valor_base","codigo_tasa='".$id_tasa_articulo."'AND fecha LIKE '".$fecha_tasa."'");

            $descripcion            = SQL::obtenerValor("articulos","descripcion","codigo = '".$datos_movimiento->codigo_articulo."'");
            $observaciones_articulo = SQL::obtenerValor("movimiento_ordenes_compra","observaciones","codigo_articulo = '$datos_movimiento->codigo_articulo' AND codigo_orden_compra = '$codigo_orden_compra'");
            $archivo->SetFont('Arial','',6);

            $id_unidad     = $datos_movimiento->codigo_unidad_medida;
            $nombre_unidad = SQL::obtenerValor("unidades","nombre","codigo='".$id_unidad."'");

    /////////////////////////////////////////////////////////////////////////////////////////////////
            $archivo->Ln(4);
            $archivo->Cell(8,4,$item,1,0,'C',true);
            $archivo->Cell(24,4,$datos_movimiento->referencia_articulo,1,0,'C',true);
            $archivo->Cell(89,4,$descripcion,1,0,'L',true);
            $archivo->Cell(61,4,$observaciones_articulo,1,0,'L',true);
            $archivo->Cell(20,4,$nombre_unidad,1,0,'C',true);
            $archivo->Cell(12,4,number_format($cantidad_total),1,0,'R',true);
            $archivo->Cell(23,4,"$ ".number_format($valor_unitario,2),1,0,'R',true);
            $archivo->Cell(23,4,"$ ".number_format($valor_total,2),1,0,'R',true);
    /////////////////////////////////////////////////////////////////////////////////////////////////
            $imprime_cabecera = $archivo->breakCell(8);

            if($imprime_cabecera){
                $archivo->Ln(4);
                $archivo->SetFont('Arial','B',8);
                $archivo->Cell(60,4,"",0);
                $archivo->Cell(60,4,"",0);
                $archivo->Cell(130,4,$textos["ORDEN_COMPRA_PDF"].$consecutivo,0,1,'R');

                $archivo->SetFont('Arial','B',8);
                $archivo->Ln(0);
                $archivo->Cell(35,4,$textos["PROVEEDOR"]." :",0,0,'L');
                $archivo->SetFont('Arial','',8);
                $archivo->Cell(70,4,"".$nombre_proveedor,0,0,'L');
                $archivo->SetFont('Arial','B',8);
                $archivo->Cell(35,4,$textos["FECHA_ENTREGA"]." :",0,0,'L');
                $archivo->SetFont('Arial','',8);
                $archivo->Cell(70,4,"".$fechaEntrega,0,0,'L');
                $archivo->Cell(40,4,"",0,1,'R');

                $archivo->SetFont('Arial','B',8);
                $archivo->Ln(0);
                $archivo->Cell(35,4,$textos["NIT"]." :",0,0,'L');
                $archivo->SetFont('Arial','',8);
                $archivo->Cell(70,4,"".$nit."-".$digitoV,0,0,'L');
                $archivo->SetFont('Arial','B',8);
                $archivo->Cell(35,4,$textos["FACTURAR_A"]." :",0,0,'L');
                $archivo->SetFont('Arial','',8);
                $archivo->Cell(70,4,"".$empresa." - ".$consorcio,0,0,'L');
                $archivo->Cell(40,4,"",0,1,'R');

                $archivo->SetFont('Arial','B',8);
                $archivo->Ln(0);
                $archivo->Cell(35,4,$textos["DIRECCION"]." :",0,0,'L');
                $archivo->SetFont('Arial','',8);
                $archivo->Cell(70,4,"".$direccion_proveedor,0,0,'L');
                $archivo->SetFont('Arial','B',8);
                $archivo->Cell(35,4,$textos["NIT"]." :",0,0,'L');
                $archivo->SetFont('Arial','',8);
                $archivo->Cell(70,4,"".$nit_empresa."-".$digitoV_Empresa,0,0,'L');
                $archivo->Cell(40,4,"",0,1,'R');

                $archivo->SetFont('Arial','B',8);
                $archivo->Ln(0);
                $archivo->Cell(35,4,$textos["CIUDAD"]." :",0,0,'L');
                $archivo->SetFont('Arial','',8);
                $archivo->Cell(70,4,"".$ciudad_proveedor,0,0,'L');
                $archivo->SetFont('Arial','B',8);
                $archivo->Cell(35,4,$textos["FORMA_PAGO_PDF"]." :",0,0,'L');
                $archivo->SetFont('Arial','',8);
                $archivo->Cell(70,4,"".$forma_pago,0,0,'L');
                $archivo->Cell(40,4,"",0,1,'R');

                $archivo->SetFont('Arial','B',8);
                $archivo->Ln(0);
                $archivo->Cell(35,4,$textos["VENDEDOR"]." :",0,0,'L');
                $archivo->SetFont('Arial','',8);
                $archivo->Cell(70,4,"".$nombre_vendedor."",0);
                $archivo->SetFont('Arial','B',8);
                $archivo->Cell(35,4,$textos["COMPRADOR"]." :",0,0,'L');
                $archivo->SetFont('Arial','',8);
                $archivo->Cell(70,4,"".$comprador,0,0,'L');
                $archivo->Cell(40,4,"",0,1,'R');

                $archivo->SetFont('Arial','B',8);
                $archivo->Ln(0);
                $archivo->Cell(35,4,$textos["TELEFONO"]." :",0,0,'L');
                $archivo->SetFont('Arial','',8);
                $archivo->Cell(70,4,"".$telefono_vendedor,0,0,'L');
                $archivo->SetFont('Arial','B',8);
                $archivo->Cell(35,4,$textos["SOLICITANTE"]." :",0,0,'L');
                $archivo->SetFont('Arial','',8);
                $archivo->Cell(70,4,"".$solicitante,0,0,'L');
                $archivo->Cell(40,4,"",0,1,'R');

                if ($descuento_global_1 != 0) {
                    $descuento_global_1 = number_format($descuento_global_1,2);
                    
                } 

                $archivo->SetFont('Arial','B',8);
                $archivo->Ln(0);
                $archivo->Cell(35,4,$textos["PROYECTO"]." :",0,0,'L');
                $archivo->SetFont('Arial','',8);
                $archivo->Cell(70,4,"".$prefijo_codigo_proyecto." - ".$nombre_proyecto."",0);
                $archivo->SetFont('Arial','B',8);
                $archivo->Cell(35,4,$textos["DESCUENTO_GLOBAL1_PDF"]." :",0,0,'L');
                $archivo->SetFont('Arial','',8);
                $archivo->Cell(70,4,"".$descuento_global_1."%",0,0,'L');
                $archivo->Cell(40,4,"",0,1,'R');

                $archivo->SetFont('Arial','B',8);
                $archivo->Ln(0);
                $archivo->Cell(35,4,$textos["OBSERVACIONES"]." :",0,0,'L');
                $archivo->SetFont('Arial','',8);
                $archivo->Cell(150,4,"".$observaciones_orden."",0);
                $archivo->SetFont('Arial','B',8);

                if ($descuento_financiero != 0) {
                    $archivo->SetFont('Arial','B',8);
                    $archivo->Cell(35,4,$textos["FINANCIERO"]." :",0,0,'L');
                    $archivo->SetFont('Arial','',8);
                    $archivo->Cell(70,4,"".$descuento_financiero." %",0);
                    $archivo->Cell(40,4,"",0,1,'R');
                } else {
                    $archivo->Cell(40,4,"",0,1,'R');
                }
                //////////////////////////////FIN ENCABEZADO DEL DOCUMENTO PDF ORDEN DE COMPRA/////////////////////////////
                $archivo->SetFont('Arial','B',6);
                $archivo->SetFillColor(225,225,225);

                $archivo->Ln(6);
                $archivo->Cell(8,4,$textos["ITEMS"],1,0,'C',true);
                $archivo->Cell(24,4,$textos["REFERENCIA"],1,0,'C',true);
                $archivo->Cell(89,4,$textos["DESCRIPCION"],1,0,'C',true);
                $archivo->Cell(61,4,$textos["OBSERVACIONES_ARTICULO"],1,0,'C',true);
                $archivo->Cell(20,4,$textos["UNIDAD_MEDIDA"],1,0,'C',true);
                $archivo->Cell(12,4,$textos["CANTIDAD_PDF"],1,0,'C',true);
                $archivo->Cell(23,4,$textos["VALOR_UNITARIO"],1,0,'C',true);
                $archivo->Cell(23,4,$textos["VALOR_TOTAL"],1,0,'C',true);
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

    $archivo->Ln(4);
    $archivo->SetFont('Arial','B',6);
    $archivo->Cell(237,4,$textos["SUBTOTAL"].": ",0,0,'R');
    $archivo->SetFont('Arial','',6);
    $archivo->Cell(23,4,"$ ".number_format($subtotal,2),1,0,'R',true);

    if($archivo->FillColor != sprintf('%.3F %.3F %.3F rg',1,1,1)){
        $archivo->SetFillColor(255,255,255);
    } else{
        $archivo->SetFillColor(240,240,240);
    }

    $archivo->Ln(4);
    $archivo->SetFont('Arial','B',6);
    $archivo->Cell(237,4,$textos["DESCUENTO_GLOBAL1_PDF"].": ",0,0,'R');
    $archivo->SetFont('Arial','',6);
    $archivo->Cell(23,4,"$ ".number_format($valor_descuento,2),1,0,'R',true);

    if($archivo->FillColor != sprintf('%.3F %.3F %.3F rg',1,1,1)){
        $archivo->SetFillColor(255,255,255);
    } else{
        $archivo->SetFillColor(240,240,240);
    }

    $archivo->Ln(4);
    $archivo->SetFont('Arial','B',6);
    $archivo->Cell(237,4,$textos["IVA"],0,0,'R');
    $archivo->SetFont('Arial','',6);
    $archivo->Cell(23,4,"$ ".number_format($total_iva,2),1,0,'R',true);

    if($archivo->FillColor != sprintf('%.3F %.3F %.3F rg',1,1,1)){
        $archivo->SetFillColor(255,255,255);
    }else{
        $archivo->SetFillColor(240,240,240);
    }

    $archivo->Ln(4);
    $archivo->SetFont('Arial','B',6);
    $archivo->Cell(237,4,$textos["TOTAL_ORDEN"],0,0,'R');
    $archivo->SetFont('Arial','',6);
    $archivo->Cell(23,4,"$ ".number_format($total_orden,2),1,0,'R',true);

    $archivo->Ln(8);
    $archivo->Ln(2);
    
    if($archivo->FillColor != sprintf('%.3F %.3F %.3F rg',1,1,1)){
        $archivo->SetFillColor(255,255,255);
    }else{
        $archivo->SetFillColor(240,240,240);
    }
    
    $archivo->SetFont('Arial','B',8);
    $archivo->Cell(260,4,$textos["OBSERVACIONES"],0,0,'L');

    $codigo_iso               = SQL::obtenerValor("sucursales","codigo_iso","codigo = '".$datos_ordenes->codigo_sucursal."'");
    $codigo_dane_departamento = SQL::obtenerValor("sucursales","codigo_dane_departamento","codigo = '".$datos_ordenes->codigo_sucursal."'");
    $codigo_dane_municipio    = SQL::obtenerValor("sucursales","codigo_dane_municipio","codigo = '".$datos_ordenes->codigo_sucursal."'");
    $municipio                = SQL::obtenerValor("municipios","nombre","codigo_iso = '".$codigo_iso."' AND codigo_dane_departamento = '".$codigo_dane_departamento."' AND codigo_dane_municipio = '".$codigo_dane_municipio."'");

    $archivo->Ln(4);
    $archivo->SetFont('Arial','',8);
    $archivo->Cell(260,4,"".$observaciones_orden."",0);

    //Texto de pie de pagina
    $archivo->SetFont('Arial','B',8);
    $archivo->Ln(6);
    $archivo->SetFont('Arial','',8);
    $archivo->Cell(260,4,$textos["PIE_PAGINA"],0,0,'L');
    $archivo->Ln(4);
    $archivo->Cell(260,4,$textos["PIE_PAGINA2"],0,0,'L');
    $archivo->Ln(4);
    $archivo->Cell(260,4,$textos["PIE_PAGINA3"],0,0,'L');

    $archivo->Ln(6);
    $archivo->SetFont('Arial','B',8);
    $archivo->Cell(260,4,$textos["IMPORTANTE"],0,0,'L');
    $archivo->Ln(4);
    $archivo->SetFont('Arial','',8);
    $archivo->Cell(260,4,$textos["IMPORTANTE2"],0,0,'L');

    $archivo->Output($nombreArchivo, "F");

    $consecutivo = SQL::obtenerValor("archivos","MAX(consecutivo)","codigo_sucursal='".$datos_ordenes->codigo_sucursal."'");
    if ($consecutivo){
        $consecutivo++;
    } else {
        $consecutivo = 1;
    }

    $datos_archivo = array(
        "codigo_sucursal" => $datos_ordenes->codigo_sucursal,
        "consecutivo"     => $consecutivo,
        "nombre"          => $nombre
    );
    SQL::insertar("archivos", $datos_archivo);
    $id_archivo   = $datos_ordenes->codigo_sucursal."|".$consecutivo;
?>

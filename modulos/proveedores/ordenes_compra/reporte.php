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
require_once ('jpgraph/src/jpgraph.php');
require_once ('jpgraph/src/jpgraph_pie.php');

$tabla                      = "usuarios";
$columnas                   = SQL::obtenerColumnas($tabla);
$consulta                   = SQL::seleccionar(array($tabla), $columnas, "usuario = '$sesion_usuario'");
$datos                      = SQL::filaEnObjeto($consulta);
$sesion_id_usuario_ingreso  = $datos->codigo;

/*** Devolver datos para autocompletar la búsqueda ***/
if (isset($url_completar)) {
    if (($url_item) == "selector5") {
        echo SQL::datosAutoCompletar("seleccion_proyectos", $url_q);
    }
    exit;
}
/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {
    $error    = "";
    $titulo   = $componente->nombre;

    $tipo_orden = array(
        "0" => $textos["ORDEN_GRABADAS"],
        "1" => $textos["ORDEN_PARCIALES"],
        "2" => $textos["ORDEN_ANULADAS"],
        "3" => $textos["ORDEN_CUMPLIDAS"],
        "4" => $textos["TODAS"]
    );

    /*** Obtener lista de sucursales para selección ***/
    $tablas = array(
        "a" => "perfiles_usuario",
        "c" => "sucursales"
    );
    $columnas = array(
        "codigo" => "a.codigo_sucursal",
        "nombre" => "c.nombre"
    );

    $tipo_listado = array(
        "1" => "PDF",
        "2" => "EXCEL"
    );

    $condicion = "c.codigo = a.codigo_sucursal AND a.codigo_usuario = '$sesion_id_usuario_ingreso'";
    $consulta_privilegios = SQL::seleccionar($tablas, $columnas, $condicion, "", "");
    $sucursales = array();

    /*** Definición de pestañas para datos del tercero***/
    $formularios["PESTANA_REPORTE"] = array(
        array(   
            HTML::listaSeleccionSimple("*estado", $textos["TIPO_ORDEN"], $tipo_orden, "", array("title" => $textos["AYUDA_TIPO_ORDEN"],"onBlur" => "validarItem(this);")),
            HTML::campoTextoCorto("*selector5", $textos["PROYECTO"], 40, 255, "", array("title" => $textos["AYUDA_PROYECTO"], "class" => "autocompletable"))
        ),
        array(
            HTML::listaSeleccionSimple("tipo_listado",$textos["TIPO_LISTADO"], $tipo_listado,1,array("title"=>$textos["AYUDA_TIPO_LISTADO"]))
        )
    );
    
    $formularios["PESTANA_REPORTE"] = array_merge($formularios["PESTANA_REPORTE"],$sucursales);

    /*** Definicion de botones ***/
    $botones = array(
        HTML::boton("botonAceptar", $textos["GENERAR_PLANO"],"exportarDatosIndice(1);", "reporte")
    );
    $contenido = HTML::generarPestanas($formularios, $botones);

    /*** Enviar datos para la generacion del formulario al script que origino la peticion ***/
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

/*** Validar los datos provenientes del formulario ***/
} elseif (!empty($forma_procesar)) {

    /*** Asumir por defecto que no hubo error ***/
    $ruta_archivo = "";
    $cargaPdf     = 0;
    $ruta         = "";
    $error        = false;
    $mensaje      = $textos["PLANO_GENERADO"];
    
    $llave_proyecto  = explode("-", $forma_selector5);
    $codigo_proyecto = $llave_proyecto[0];
    
    /*** Validar ingreso de campo fecha al formulario ***/
    if (empty($forma_selector5)){
        $error   = true;
        $mensaje = $textos["ERROR_DATOS_INCOMPLETOS"];
    } else {
        /*** Generar archivo plano ***/
        if ($forma_estado == 4){
            $condicion_estado = "estado !='4' AND";
        } elseif ($forma_estado == 0) {
            $condicion_estado = "estado = '$forma_estado' AND ";    
        } elseif ($forma_estado == 1) {
            $condicion_estado = "estado = '$forma_estado' AND ";
        } elseif($forma_estado == 2){
            $condicion_estado = "estado = '$forma_estado' AND ";
        } elseif($forma_estado == 3){
            $condicion_estado = "estado = '$forma_estado' AND ";
        }

        $nombre         = "";
        $nombreArchivo  = "";

        do {
            $cadena = Cadena::generarCadenaAleatoria(8);
            if ($forma_tipo_listado=="1"){
                $nombre = $codigo_proyecto.$cadena.".pdf";
            } else {
                $nombre = $codigo_proyecto.$cadena.".csv";
            }
            $nombreArchivo = $rutasGlobales["archivos"]."/orden".$nombre;
        } while (is_file($nombreArchivo));
        
        //$nombreArchivo = $rutasGlobales["archivos"]."/plano".$forma_selector5.".csv";
        if (file_exists($nombreArchivo)){
            unlink($nombreArchivo);
            $archivo = fopen($nombreArchivo,"a+");
        } else {
            $archivo = fopen($nombreArchivo,"a+");
        }   
        /*** Obtener los datos de la tabla ***/
        $ordenes_compra   = SQL::seleccionar(array("ordenes_compra"),array("*"),"$condicion_estado prefijo_codigo_proyecto='$codigo_proyecto'");
        $datos_encabezado = SQL::filaEnObjeto($ordenes_compra);
        //Calculo los porcentajes de cumplimiento
        $numero_ordenes_proyecto  = SQL::obtenerValor("ordenes_compra","COUNT(numero_consecutivo)","prefijo_codigo_proyecto='$codigo_proyecto'");
        $numero_ordenes_grabadas = SQL::obtenerValor("ordenes_compra","COUNT(numero_consecutivo)","prefijo_codigo_proyecto='$codigo_proyecto' AND estado='0'");
        $numero_ordenes_cumplidas = SQL::obtenerValor("ordenes_compra","COUNT(numero_consecutivo)","prefijo_codigo_proyecto='$codigo_proyecto' AND estado='3'");
        $numero_ordenes_parciales = SQL::obtenerValor("ordenes_compra","COUNT(numero_consecutivo)","prefijo_codigo_proyecto='$codigo_proyecto' AND estado='1'");
        $numero_ordenes_anuladas  = SQL::obtenerValor("ordenes_compra","COUNT(numero_consecutivo)","prefijo_codigo_proyecto='$codigo_proyecto' AND estado='2'");
        $porcentaje_cumplidas     = ($numero_ordenes_cumplidas/$numero_ordenes_proyecto)*100;
        $porcentaje_parciales     = ($numero_ordenes_parciales/$numero_ordenes_proyecto)*100;
        $porcentaje_anuladas      = ($numero_ordenes_anuladas/$numero_ordenes_proyecto)*100;
        $porcentaje_grabadas      = ($numero_ordenes_grabadas/$numero_ordenes_proyecto)*100;
 
        $data    = array($porcentaje_grabadas, $porcentaje_cumplidas, $porcentaje_parciales, $porcentaje_anuladas);
        $estados = array("Grab","Cumpl","Parc","Anul");

        // Create the Pie Graph. 
        $graph = new PieGraph(301,217);

        $theme_class="DefaultTheme";

        // Set A title for the plot
        $graph->title->Set("Analisis estados O.C");
        $graph->title->SetFont(FF_FONT2,FS_BOLD);
        $graph->SetBox(true);

        // Create
        $p1 = new PiePlot($data);
        $p1->SetLegends($estados);
        $graph->Add($p1);
        
        $p1->ShowBorder();
        $p1->SetColor('black');
        $p1->SetSliceColors(array("#0088ff","#61db5c","#ffdf00","#DC143C"));
    
        $cadena       = Cadena::generarCadenaAleatoria(8);
        $nombreImagen = '../archivos'.$cadena.".png";
 
        // Display the graph
        $graph->Stroke($nombreImagen);


        //Titulos segun tipo listado
        if ($forma_tipo_listado=="1"){
            //////////////////////////////ENCABEZADO DEL DOCUMENTO PDF ORDEN DE COMPRA/////////////////////////////
            $anchoColumnas       = array(20,50);
            $alineacionColumnas  = array("I","I");
            $acumulado_subtotal  = 0;
            $acumulado_descuento = 0;
            $acumulado_iva       = 0;
            $acumulado_unidades  = 0;
            $acumulado_total     = 0;

            $archivo                 = new PDF("L","mm","Letter");
            $archivo->textoCabecera  = $textos["FECHA"].": ".date("Y-m-d");
            $archivo->textoTitulo    = $textos["REPOORCO"];
            $archivo->textoPiePagina = $textos["PIE_PAGINA_EMPRESA"];

            $archivo->AddPage();
            $archivo->SetFont('Arial','B',8);

            $nombre_proyecto  = SQL::obtenerValor("proyectos","nombre","codigo='$datos_encabezado->prefijo_codigo_proyecto'");
            $codigo_empresa   = SQL::obtenerValor("sucursales","codigo_empresa","codigo='$datos_encabezado->codigo_sucursal'");
            $nombre_sucursal  = SQL::obtenerValor("sucursales","nombre","codigo='$datos_encabezado->codigo_sucursal'");
            $nombre_empresa   = SQL::obtenerValor("empresas","razon_social","codigo='$codigo_empresa'");
            $valor_proyecto   = SQL::obtenerValor("proyectos","valor_proyecto","codigo='$datos_encabezado->prefijo_codigo_proyecto'");

            $archivo->SetFont('Arial','B',8);
            $archivo->Ln(0);
            $archivo->Cell(35,4,$textos["PROYECTO"]." :",0,0,'L');
            $archivo->SetFont('Arial','',8);
            $archivo->Cell(70,4,"".$nombre_proyecto,0,0,'L');
            $archivo->Cell(40,4,"",0,1,'R');

            $archivo->SetFont('Arial','B',8);
            $archivo->Ln(0);
            $archivo->Cell(35,4,$textos["EMPRESA"]." :",0,0,'L');
            $archivo->SetFont('Arial','',8);
            $archivo->Cell(70,4,"".$nombre_empresa." - ".$nombre_sucursal,0,0,'L');
            $archivo->Cell(40,4,"",0,1,'R');

            $archivo->SetFont('Arial','B',8);
            $archivo->Ln(0);
            $archivo->Cell(35,4,$textos["VALOR_PROYECTO"]." :",0,0,'L');
            $archivo->SetFont('Arial','',8);
            $archivo->Cell(70,4,""."$ ".number_format($valor_proyecto,0),0,0,'L');
            $archivo->Cell(40,4,"",0,1,'R');
            //Imprime Grafica
            $archivo->Ln(2);
            //Aqui agrego la imagen que acabo de crear con jpgraph
            $archivo->Image($nombreImagen, 222, 16, 52);
            //$this->Image($graph,10, 10, 20);  
            //////////////////////////////FIN ENCABEZADO DEL DOCUMENTO PDF ORDEN DE COMPRA/////////////////////////////
            $archivo->SetFont('Arial','B',6);
            $archivo->SetFillColor(225,225,225);

            $archivo->Ln(6);
            $archivo->Cell(50,4,$textos["EMPRESA"],1,0,'C',true);
            $archivo->Cell(20,4,$textos["CONSORCIO_PDF"],1,0,'C',true);
            $archivo->Cell(20,4,$textos["FECHA_DOCUMENTO"],1,0,'C',true);
            $archivo->Cell(20,4,$textos["ORDEN_COMPRA"],1,0,'C',true);
            $archivo->Cell(19,4,$textos["SUBTOTAL"],1,0,'C',true);
            $archivo->Cell(19,4,$textos["DESCUENTO"],1,0,'C',true);
            $archivo->Cell(19,4,$textos["IVA"],1,0,'C',true);
            $archivo->Cell(19,4,$textos["VALOR_TOTAL"],1,0,'C',true);
            $archivo->Cell(18,4,$textos["ESTADO_PDF"],1,0,'C',true);
            $archivo->Cell(15,4,$textos["NIT"],1,0,'C',true);
            $archivo->Cell(40,4,$textos["PROVEEDOR"],1,0,'C',true);
            //////////////////////////////FIN ENCABEZADO DEL DOCUMENTO PDF ORDEN DE COMPRA/////////////////////////////
            /*** Obtener los datos de la tabla ***/
            $ordenes_compra = SQL::seleccionar(array("ordenes_compra"),array("*"),"$condicion_estado prefijo_codigo_proyecto='$codigo_proyecto'");
            //Se lee el movimiento de la tabla movimientos
            if (SQL::filasDevueltas($ordenes_compra)){
                while($datos_encabezado=SQL::filaEnObjeto($ordenes_compra)){
                    if($archivo->FillColor != sprintf('%.3F %.3F %.3F rg',1,1,1)){
                        $archivo->SetFillColor(255,255,255);
                    } else{
                        $archivo->SetFillColor(240,240,240);
                    }
                    
                    $movimiento_ordenes  = SQL::seleccionar(array("movimiento_ordenes_compra"),array("*"),"codigo_orden_compra='$datos_encabezado->codigo'");    
                    
                    $id_sucursal     = SQL::obtenerValor("ordenes_compra","codigo_sucursal","prefijo_codigo_proyecto='$codigo_proyecto' LIMIT 0,1");
                    $codigo_empresa  = SQL::obtenerValor("sucursales","codigo_empresa","codigo='$id_sucursal'");
                    $nombre_empresa  = SQL::obtenerValor("empresas","razon_social","codigo='$codigo_empresa'");
                    $numero_orden    = $datos_encabezado->numero_consecutivo;
                    $nit             = SQL::obtenerValor("ordenes_compra","documento_identidad_proveedor","numero_consecutivo='$numero_orden'");
                    $proveedor       = SQL::obtenerValor("menu_proveedores","PROVEEDOR","DOCUMENTO_PROVEEDOR ='$nit'");
                    $grabar_registro = 1;
                    
                    if ($grabar_registro==1){

                        $consorciado = SQL::obtenerValor("sucursales","nombre","codigo='$datos_encabezado->codigo_sucursal'");
                        
                        if ($datos_encabezado->estado == '0'){
                           $estado_orden = $textos["ESTADO_0"];
                        }
                        if ($datos_encabezado->estado == '1'){
                           $estado_orden = $textos["ESTADO_1"];
                        }
                        if ($datos_encabezado->estado == '2'){
                           $estado_orden = $textos["ESTADO_2"];
                        }
                        if ($datos_encabezado->estado == '3'){
                           $estado_orden = $textos["ESTADO_3"];
                        }
                        if ($datos_encabezado->estado == '4'){
                           $estado_orden = $textos["ESTADO_4"];
                        }
                        
                        $subtotal = SQL::obtenerValor("movimiento_ordenes_compra","SUM(valor_total)","codigo_orden_compra='$datos_encabezado->codigo'");
                        $acumulado_subtotal = $subtotal + $acumulado_subtotal;

                        $unidades  = SQL::obtenerValor("movimiento_ordenes_compra","SUM(cantidad_total)","codigo_orden_compra='$datos_encabezado->codigo'");
                        $unidades  = number_format($unidades, 0);
                        $acumulado_unidades  = $unidades + $acumulado_unidades;
                        
                        $descuento = SQL::obtenerValor("movimiento_ordenes_compra","SUM(valor_descuento_global1)","codigo_orden_compra='$datos_encabezado->codigo'");
                        $acumulado_descuento = $descuento + $acumulado_descuento;

                        $total_iva = SQL::obtenerValor("movimiento_ordenes_compra","SUM(valor_iva)","codigo_orden_compra='$datos_encabezado->codigo'");
                        $acumulado_iva = $total_iva + $acumulado_iva;

                        $total_orden = SQL::obtenerValor("movimiento_ordenes_compra","SUM(neto_pagar)","codigo_orden_compra='$datos_encabezado->codigo'");
                        $acumulado_total = $total_orden + $acumulado_total;

                        /*** Ordenes ***/
                        $fecha_orden  = $datos_encabezado->fecha_documento;
                        $estado       = $datos_encabezado->estado;
                        $subtotal     = number_format($subtotal, 0);
                        $subtotal     = str_replace(',', '.', $subtotal);  
                        $descuento    = number_format($descuento, 0);
                        $descuento    = str_replace(',', '.', $descuento); 
                        $total_iva    = number_format($total_iva, 0);
                        $total_iva    = str_replace(',', '.', $total_iva);
                        $total_orden  = number_format($total_orden, 0);
                        $total_orden  = str_replace(',', '.', $total_orden);
                        
                        /////////////////////////////////////////////////////////////////////////////////////////////////
                        $archivo->Ln(4);
                        $archivo->Cell(50,4,$nombre_empresa,1,0,'L',true);
                        $archivo->Cell(20,4,$consorciado,1,0,'L',true);
                        $archivo->Cell(20,4,$fecha_orden,1,0,'C',true);
                        $archivo->Cell(20,4,$numero_orden,1,0,'R',true);
                        $archivo->Cell(19,4,"$ ".$subtotal,1,0,'R',true);
                        $archivo->Cell(19,4,"$ ".$descuento,1,0,'R',true);
                        $archivo->Cell(19,4,"$ ".$total_iva,1,0,'R',true);
                        $archivo->Cell(19,4,"$ ".$total_orden,1,0,'R',true);
                        $archivo->Cell(18,4,$estado_orden,1,0,'L',true);
                        $archivo->Cell(15,4,$nit,1,0,'R',true);
                        $archivo->Cell(40,4,$proveedor,1,0,'C',true);

                        /////////////////////////////////////////////////////////////////////////////////////////////////
                        $imprime_cabecera = $archivo->breakCell(8);

                        if($imprime_cabecera){
                            $archivo->Ln(4);
                            $archivo->SetFont('Arial','B',8);
                            $archivo->Ln(0);
                            $archivo->Cell(35,4,$textos["PROYECTO"]." :",0,0,'L');
                            $archivo->SetFont('Arial','',8);
                            $archivo->Cell(70,4,"".$nombre_proyecto,0,0,'L');
                            $archivo->Cell(40,4,"",0,1,'R');

                            $archivo->SetFont('Arial','B',8);
                            $archivo->Ln(0);
                            $archivo->Cell(35,4,$textos["EMPRESA"]." :",0,0,'L');
                            $archivo->SetFont('Arial','',8);
                            $archivo->Cell(70,4,"".$nombre_empresa." - ".$nombre_sucursal,0,0,'L');

                            $archivo->SetFont('Arial','B',8);
                            $archivo->Ln(0);
                            $archivo->Cell(35,4,$textos["VALOR_PROYECTO"]." :",0,0,'L');
                            $archivo->SetFont('Arial','',8);
                            $archivo->Cell(70,4,"".$valor_proyecto,0,0,'L');
                            $archivo->Cell(40,4,"",0,1,'R');
                        //////////////////////////////FIN ENCABEZADO DEL DOCUMENTO PDF ORDEN DE COMPRA/////////////////////////////    
                            $archivo->SetFont('Arial','B',6);
                            $archivo->SetFillColor(225,225,225);

                            $archivo->Ln(6);
                            $archivo->Cell(50,4,$textos["EMPRESA"],1,0,'C',true);
                            $archivo->Cell(20,4,$textos["CONSORCIO_PDF"],1,0,'C',true);
                            $archivo->Cell(20,4,$textos["FECHA_DOCUMENTO"],1,0,'C',true);
                            $archivo->Cell(20,4,$textos["ORDEN_COMPRA"],1,0,'C',true);
                            $archivo->Cell(19,4,$textos["SUBTOTAL"],1,0,'C',true);
                            $archivo->Cell(19,4,$textos["DESCUENTO"],1,0,'C',true);
                            $archivo->Cell(19,4,$textos["IVA"],1,0,'C',true);
                            $archivo->Cell(19,4,$textos["VALOR_TOTAL"],1,0,'C',true);
                            $archivo->Cell(18,4,$textos["ESTADO_PDF"],1,0,'C',true);
                            $archivo->Cell(15,4,$textos["NIT"],1,0,'C',true);
                            $archivo->Cell(40,4,$textos["PROVEEDOR"],1,0,'C',true);
                        }
                        $i++;
                        $item++;
                    }
                }
            }
            
            if($archivo->FillColor != sprintf('%.3F %.3F %.3F rg',1,1,1)){
                $archivo->SetFillColor(255,255,255);
            } else{
                $archivo->SetFillColor(240,240,240);
            }

            $acumulado_subtotal    = number_format($acumulado_subtotal, 0);
            $acumulado_subtotal    = str_replace(',', '.', $acumulado_subtotal);  
            $acumulado_descuento   = number_format($acumulado_descuento, 0);
            $acumulado_descuento   = str_replace(',', '.', $acumulado_descuento); 
            $acumulado_iva         = number_format($acumulado_iva, 0);
            $acumulado_iva         = str_replace(',', '.', $acumulado_iva);
            $acumulado_total       = number_format($acumulado_total, 0);
            $acumulado_total       = str_replace(',', '.', $acumulado_total);

            $archivo->Ln(4);
            $archivo->SetFont('Arial','B',6);
            $archivo->Cell(50,4,"",1,0,'R',true);
            $archivo->Cell(20,4,"",1,0,'R',true);
            $archivo->Cell(20,4,"",1,0,'R',true);
            $archivo->Cell(20,4,"",1,0,'R',true);
            $archivo->Cell(19,4,"$ ".$acumulado_subtotal,1,0,'R',true);
            $archivo->Cell(19,4,"$ ".$acumulado_descuento,1,0,'R',true);
            $archivo->Cell(19,4,"$ ".$acumulado_iva,1,0,'R',true);
            $archivo->Cell(19,4,"$ ".$acumulado_total,1,0,'R',true);

            if($archivo->FillColor != sprintf('%.3F %.3F %.3F rg',1,1,1)){
                $archivo->SetFillColor(255,255,255);
            } else{
                $archivo->SetFillColor(240,240,240);
            }

            $archivo->Output($nombreArchivo, "F");

            $consecutivo = SQL::obtenerValor("archivos","MAX(consecutivo)","codigo_sucursal='".$datos_encabezado->codigo_sucursal."'");
            if ($consecutivo){
                $consecutivo++;
            } else {
                $consecutivo = 1;
            }

            $datos_archivo = array(
                "codigo_sucursal" => $datos_encabezado->codigo_sucursal,
                "consecutivo"     => $consecutivo,
                "nombre"          => $nombre
            );
            SQL::insertar("archivos", $datos_archivo);
            $mensaje = HTML::enlazarPagina($textos["GENERAR_PLANO"], $nombreArchivo, array("target" => "_new"));
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
        } else{
            //Se crean los titulos del archivo excel
            $titulos_plano = "EMPRESA;CONSORCIO;FECHA ORDEN;ORDEN COMPRA;SUBTOTAL;DESCUENTO;IVA;VALOR TOTAL;ESTADO;NIT;PROVEEDOR\n";
            fwrite($archivo, $titulos_plano);
            
            /*** Obtener los datos de la tabla ***/
            $ordenes_compra = SQL::seleccionar(array("ordenes_compra"),array("*"),"$condicion_estado prefijo_codigo_proyecto='$codigo_proyecto'");
            if (SQL::filasDevueltas($ordenes_compra)){
                while($datos_encabezado=SQL::filaEnObjeto($ordenes_compra)){
                    $movimiento_ordenes  = SQL::seleccionar(array("movimiento_ordenes_compra"),array("*"),"codigo_orden_compra='$datos_encabezado->codigo'");    
                    
                    $id_sucursal      = SQL::obtenerValor("ordenes_compra","codigo_sucursal","prefijo_codigo_proyecto='$codigo_proyecto' LIMIT 0,1");
                    $codigo_empresa   = SQL::obtenerValor("sucursales","codigo_empresa","codigo='$id_sucursal'");
                    $nombre_empresa   = SQL::obtenerValor("empresas","razon_social","codigo='$codigo_empresa'");
                    $numero_orden     = $datos_encabezado->numero_consecutivo;
                    $nit              = SQL::obtenerValor("ordenes_compra","documento_identidad_proveedor","numero_consecutivo='$numero_orden'");
                    $proveedor        = SQL::obtenerValor("menu_proveedores","PROVEEDOR","DOCUMENTO_PROVEEDOR ='$nit'");
                    $grabar_registro  = 1;

                    if ($grabar_registro==1){

                        $consorciado = SQL::obtenerValor("sucursales","nombre","codigo='$datos_encabezado->codigo_sucursal'");
                        
                        if ($datos_encabezado->estado == '0'){
                           $estado_orden = $textos["ESTADO_0"];
                        }
                        if ($datos_encabezado->estado == '1'){
                           $estado_orden = $textos["ESTADO_1"];
                        }
                        if ($datos_encabezado->estado == '2'){
                           $estado_orden = $textos["ESTADO_2"];
                        }
                        if ($datos_encabezado->estado == '3'){
                           $estado_orden = $textos["ESTADO_3"];
                        }
                        if ($datos_encabezado->estado == '4'){
                           $estado_orden = $textos["ESTADO_4"];
                        }
                        
                        $subtotal = SQL::obtenerValor("movimiento_ordenes_compra","SUM(valor_total)","codigo_orden_compra='$datos_encabezado->codigo'");
                        $subtotal  = number_format($subtotal, 0);
                        $subtotal  = str_replace(',', '.', $subtotal);   

                        $unidades  = SQL::obtenerValor("movimiento_ordenes_compra","SUM(cantidad_total)","codigo_orden_compra='$datos_encabezado->codigo'");
                        $unidades  = number_format($unidades, 0);
                        
                        $descuento = SQL::obtenerValor("movimiento_ordenes_compra","SUM(valor_descuento_global1)","codigo_orden_compra='$datos_encabezado->codigo'");
                        $descuento = number_format($descuento, 0);
                        $descuento = str_replace(',', '.', $descuento);

                        $total_iva = SQL::obtenerValor("movimiento_ordenes_compra","SUM(valor_iva)","codigo_orden_compra='$datos_encabezado->codigo'");
                        $total_iva = number_format($total_iva, 0);
                        $total_iva = str_replace(',', '.', $total_iva);

                        $total_orden = SQL::obtenerValor("movimiento_ordenes_compra","SUM(neto_pagar)","codigo_orden_compra='$datos_encabezado->codigo'");
                        $total_orden = number_format($total_orden, 0);
                        $total_orden = str_replace(',', '.', $total_orden);

                        /*** Ordenes ***/
                        $fecha_orden  = $datos_encabezado->fecha_documento;
                        $estado       = $datos_encabezado->estado;
                                                                                               
                        //Contenido del archivo
                        $contenido = "$nombre_empresa;$consorciado;$fecha_orden;$numero_orden;$subtotal;$descuento;$total_iva;$total_orden;$estado_orden;$nit;$proveedor\n";
                        $guardarArchivo = fwrite($archivo,$contenido);
                    }
                }
            }
            fclose($archivo);
            $mensaje = HTML::enlazarPagina($textos["GENERAR_PLANO"], $nombreArchivo, array("target" => "_new"));
        }
    }    
    /*** Enviar datos con la respuesta del proceso al script que origino la peticion ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    $respuesta[2] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

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

    $estado_correspondencia = array(
        "0" => $textos["RECEPCIONADO"],
        "1" => $textos["ENTREGADO"],
        "2" => $textos["ANULADO"],
        "3" => $textos["TODOS"],
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
            HTML::listaSeleccionSimple("*estado", $textos["ESTADO_CORRESPONDENCIA"], $estado_correspondencia, "", array("title" => $textos["AYUDA_ESTADO_CORRESPONDENCIA"],"onBlur" => "validarItem(this);")),
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
        if ($forma_estado == 0) {
            $condicion_estado = "estado = '$forma_estado' AND ";    
        } elseif ($forma_estado == 1) {
            $condicion_estado = "estado = '$forma_estado' AND ";
        } elseif($forma_estado == 2){
            $condicion_estado = "estado = '$forma_estado' AND ";
        } elseif($forma_estado == 3){
            $condicion_estado = "estado !='' AND ";
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
            $nombreArchivo = $rutasGlobales["archivos"]."/Corresp".$nombre;
        } while (is_file($nombreArchivo));
        
        //$nombreArchivo = $rutasGlobales["archivos"]."/plano".$forma_selector5.".csv";
        if (file_exists($nombreArchivo)){
            unlink($nombreArchivo);
            $archivo = fopen($nombreArchivo,"a+");
        } else {
            $archivo = fopen($nombreArchivo,"a+");
        }  
        /*** Obtener los datos de la tabla ***/
        $correspondencia       = SQL::seleccionar(array("correspondencia"),array("*"),"$condicion_estado codigo_proyecto='$codigo_proyecto'");
        $datos_correspondencia = SQL::filaEnObjeto($correspondencia);
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
            $archivo->textoTitulo    = $textos["REPOCORR"];
            $archivo->textoPiePagina = $textos["PIE_PAGINA_EMPRESA"];

            $archivo->AddPage();
            $archivo->SetFont('Arial','B',8);

            //$datos_encabezado = SQL::filaEnObjeto($ordenes_compra);

            $nombre_proyecto  = SQL::obtenerValor("proyectos","nombre","codigo='$datos_correspondencia->codigo_proyecto'");
            $codigo_sucursal  = SQL::obtenerValor("ordenes_compra","codigo_sucursal","numero_consecutivo='$datos_correspondencia->numero_orden_compra'");
            $nombre_sucursal  = SQL::obtenerValor("sucursales","nombre","codigo='$codigo_sucursal'");
            $codigo_empresa   = SQL::obtenerValor("sucursales","codigo_empresa","codigo='$codigo_sucursal'");
            $nombre_empresa   = SQL::obtenerValor("empresas","razon_social","codigo='$codigo_empresa'");
            $valor_proyecto   = SQL::obtenerValor("proyectos","valor_proyecto","codigo='$datos_correspondencia->codigo_proyecto'");

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
            //////////////////////////////FIN ENCABEZADO DEL DOCUMENTO PDF ORDEN DE COMPRA/////////////////////////////
            $archivo->SetFont('Arial','B',6);
            $archivo->SetFillColor(225,225,225);

            $archivo->Ln(6);
            $archivo->Cell(20,4,$textos["FECHA_DOCUMENTO"],1,0,'C',true);
            $archivo->Cell(25,4,$textos["TIPO_DOCUMENTO"],1,0,'L',true);
            $archivo->Cell(20,4,$textos["ORDEN_COMPRA"],1,0,'C',true);
            $archivo->Cell(20,4,$textos["VALOR_ORDEN_COMPRA"],1,0,'C',true);
            $archivo->Cell(15,4,$textos["NIT_PROVEEDOR"],1,0,'C',true);
            $archivo->Cell(43,4,$textos["RAZON_SOCIAL"],1,0,'C',true);
            $archivo->Cell(20,4,$textos["FACTURA"],1,0,'C',true);
            $archivo->Cell(16,4,$textos["CRUZADO"],1,0,'C',true);
            $archivo->Cell(18,4,$textos["VALOR_TOTAL"],1,0,'C',true);
            $archivo->Cell(18,4,$textos["ESTADO_PDF"],1,0,'L',true);
            $archivo->Cell(21,4,$textos["FECHA_VENCIMIENTO"],1,0,'C',true);
            $archivo->Cell(21,4,$textos["FECHA_ENVIO"],1,0,'C',true);
            //////////////////////////////FIN ENCABEZADO DEL DOCUMENTO PDF ORDEN DE COMPRA/////////////////////////////
            /*** Obtener los datos de la tabla ***/
            $consulta_correspondencia = SQL::seleccionar(array("correspondencia"),array("*"),"$condicion_estado codigo_proyecto='$codigo_proyecto' ORDER BY codigo DESC");
            //Se lee el movimiento de la tabla movimientos
            if (SQL::filasDevueltas($consulta_correspondencia)){
                while($datos_correspondencia=SQL::filaEnObjeto($consulta_correspondencia)){
                    if($archivo->FillColor != sprintf('%.3F %.3F %.3F rg',1,1,1)){
                        $archivo->SetFillColor(255,255,255);
                    } else{
                        $archivo->SetFillColor(240,240,240);
                    }
                    $codigo_orden_compra = SQL::obtenerValor("ordenes_compra","codigo","numero_consecutivo='$datos_correspondencia->numero_orden_compra'");
                    $movimiento_consulta_correspondencia = SQL::seleccionar(array("movimiento_ordenes_compra"),array("*"),"codigo_orden_compra='$codigo_orden_compra'");    
                    
                    $numero_orden = SQL::obtenerValor("ordenes_compra","numero_consecutivo","codigo='$codigo_orden_compra'");
                    $nit          = SQL::obtenerValor("ordenes_compra","documento_identidad_proveedor","numero_consecutivo='$numero_orden'");
                    $proveedor    = SQL::obtenerValor("menu_proveedores","PROVEEDOR","DOCUMENTO_PROVEEDOR ='$nit'");
                    $grabar_registro = 1;
                    
                    if ($grabar_registro==1){
                        
                        if ($datos_correspondencia->estado == '0'){
                           $estado_correspondencia = $textos["ESTADO_0"];
                        }
                        if ($datos_correspondencia->estado == '1'){
                           $estado_correspondencia = $textos["ESTADO_1"];
                        }
                        if ($datos_correspondencia->estado == '2'){
                           $estado_correspondencia = $textos["ESTADO_2"];
                        }

                        $tipo_documento = SQL::obtenerValor("tipos_documentos","descripcion","codigo='$datos_correspondencia->codigo_tipo_documento'"); 
                        $total_orden    = SQL::obtenerValor("movimiento_ordenes_compra","SUM(neto_pagar)","codigo_orden_compra='$codigo_orden_compra'");

                        /*** Ordenes ***/
                        $fecha_orden  = SQL::obtenerValor("ordenes_compra","fecha_documento","codigo='$codigo_orden_compra'");
                        $total_orden  = number_format($total_orden, 0);
                        $total_orden  = str_replace(',', '.', $total_orden);
                        
                        /////////////////////////////////////////////////////////////////////////////////////////////////
                        $archivo->Ln(4);
                        $archivo->Cell(20,4,$fecha_orden,1,0,'C',true);
                        $archivo->Cell(25,4,$tipo_documento,1,0,'L',true);
                        $archivo->Cell(20,4,$numero_orden,1,0,'R',true);
                        $archivo->Cell(20,4,$total_orden,1,0,'R',true);
                        $archivo->Cell(15,4,$nit,1,0,'C',true);
                        $archivo->Cell(43,4,$proveedor,1,0,'L',true);
                        $archivo->Cell(20,4,$datos_correspondencia->numero_documento_proveedor,1,0,'R',true);
                        $archivo->Cell(16,4,$datos_correspondencia->documento_cruzado_por_factura,1,0,'R',true);
                        $archivo->Cell(18,4,number_format($datos_correspondencia->valor_documento,0),1,0,'R',true);
                        $archivo->Cell(18,4,$estado_correspondencia,1,0,'L',true);
                        $archivo->Cell(21,4,$datos_correspondencia->fecha_vencimiento,1,0,'C',true);
                        $archivo->Cell(21,4,$datos_correspondencia->fecha_envio,1,0,'C',true);
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
                            $archivo->Cell(20,4,$textos["FECHA_DOCUMENTO"],1,0,'C',true);
                            $archivo->Cell(25,4,$textos["TIPO_DOCUMENTO"],1,0,'L',true);
                            $archivo->Cell(20,4,$textos["ORDEN_COMPRA"],1,0,'C',true);
                            $archivo->Cell(20,4,$textos["VALOR_ORDEN_COMPRA"],1,0,'C',true);
                            $archivo->Cell(15,4,$textos["NIT_PROVEEDOR"],1,0,'C',true);
                            $archivo->Cell(43,4,$textos["RAZON_SOCIAL"],1,0,'C',true);
                            $archivo->Cell(20,4,$textos["FACTURA"],1,0,'C',true);
                            $archivo->Cell(16,4,$textos["CRUZADO"],1,0,'C',true);
                            $archivo->Cell(18,4,$textos["VALOR_TOTAL"],1,0,'C',true);
                            $archivo->Cell(18,4,$textos["ESTADO_PDF"],1,0,'L',true);
                            $archivo->Cell(21,4,$textos["FECHA_VENCIMIENTO"],1,0,'C',true);
                            $archivo->Cell(21,4,$textos["FECHA_ENVIO"],1,0,'C',true);
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
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
        } else{
            //Se crean los titulos del archivo excel
            $titulos_plano = "FECHA_DOCUMENTO;TIPO_DOCUMENTO;ORDEN_COMPRA;VALOR ORDEN;NIT;PROVEEDOR;FACTURA;CRUZADO_CON;VALOR TOTAL;ESTADO;FECHA_VENCIMIENTO;FECHA_ENVIO\n";
            fwrite($archivo, $titulos_plano);
            
            /*** Obtener los datos de la tabla ***/
            $consulta_correspondencia = SQL::seleccionar(array("correspondencia"),array("*"),"$condicion_estado codigo_proyecto='$codigo_proyecto' ORDER BY codigo DESC");
            //Se lee el movimiento de la tabla movimientos
            if (SQL::filasDevueltas($consulta_correspondencia)){
                while($datos_correspondencia=SQL::filaEnObjeto($consulta_correspondencia)){
                    $codigo_orden_compra = SQL::obtenerValor("ordenes_compra","codigo","numero_consecutivo='$datos_correspondencia->numero_orden_compra'");
                    $movimiento_consulta_correspondencia = SQL::seleccionar(array("movimiento_ordenes_compra"),array("*"),"codigo_orden_compra='$codigo_orden_compra'");    
                    
                    $numero_orden = SQL::obtenerValor("ordenes_compra","numero_consecutivo","codigo='$codigo_orden_compra'");
                    $nit          = SQL::obtenerValor("ordenes_compra","documento_identidad_proveedor","numero_consecutivo='$numero_orden'");
                    $proveedor    = SQL::obtenerValor("menu_proveedores","PROVEEDOR","DOCUMENTO_PROVEEDOR ='$nit'");
                    $grabar_registro = 1;
                    
                    if ($grabar_registro==1){
                        
                        if ($datos_correspondencia->estado == '0'){
                           $estado_correspondencia = $textos["ESTADO_0"];
                        }
                        if ($datos_correspondencia->estado == '1'){
                           $estado_correspondencia = $textos["ESTADO_1"];
                        }
                        if ($datos_correspondencia->estado == '2'){
                           $estado_correspondencia = $textos["ESTADO_2"];
                        }

                        $total_orden = SQL::obtenerValor("movimiento_ordenes_compra","SUM(neto_pagar)","codigo_orden_compra='$codigo_orden_compra'");

                        /*** Ordenes ***/
                        $fecha_orden     = SQL::obtenerValor("ordenes_compra","fecha_documento","codigo='$codigo_orden_compra'");
                        $total_orden     = number_format($total_orden, 0);
                        $total_orden     = str_replace(',', '.', $total_orden);
                        $valor_documento = $datos_correspondencia->valor_documento;
                        $valor_documento = number_format($valor_documento, 0);
                        $valor_documento = str_replace(',', '.', $valor_documento);
                        $tipo_documento  = SQL::obtenerValor("tipos_documentos","descripcion","codigo='$datos_correspondencia->codigo_tipo_documento'"); 
                        $cruzado         = $datos_correspondencia->documento_cruzado_por_factura;
                                                                                               
                        //Contenido del archivo
                        $contenido = "$fecha_orden;$tipo_documento;$numero_orden;$total_orden;$nit;$proveedor;$datos_correspondencia->numero_documento_proveedor;$cruzado;$valor_documento;$estado_correspondencia;$datos_correspondencia->fecha_vencimiento;$datos_correspondencia->fecha_envio\n";
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

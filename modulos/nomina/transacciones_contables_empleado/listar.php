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

    $certificado_ingresos = array(
        "1" => "",
        "2" => $textos["SALARIOS_OTROS_INGRESOS"],
        "3" => $textos["RETENCIONES_PRACTICADAS"],
        "4" => $textos["DESCUENTOS_SALUD_PENSION"]
    );

if (isset($url_completar)) {

    if (($url_item) == "selector1") {
        echo SQL::datosAutoCompletar("seleccion_plan_contable", $url_q);
    }
    exit;
}
// Generar el formulario para la captura de datos
if (!empty($url_generar)) {
    $error  = "";
    $titulo = $componente->nombre;

    $tipo_listado = array(
        "1" => $textos["ARCHIVO_PDF"],
        "2" => $textos["ARCHIVO_PLANO"]
    );

    $sentido= array(
        ""  => "",
        "C" => $textos["CREDITO"],
        "D" => $textos["DEBITO"]
    );

    $columnas = array(
        "" => ""
    );
    $consultaColumnas = SQL::seleccionar(array("titulos_planillas"),array("*"),"");
    while($datosColumnas = SQL::filaEnObjeto($consultaColumnas)){
        $columnas[$datosColumnas->columna] = $datosColumnas->nombre;
    }

    $conceptos = array(
        "" => ""
    );
    $consultaColumnas = SQL::seleccionar(array("conceptos_transacciones_contables_empleado"),array("*"),"");
    while($datosColumnas = SQL::filaEnObjeto($consultaColumnas)){
        $conceptos[$datosColumnas->codigo] = $datosColumnas->descripcion;
    }

    // Definicion de pestana general
    $formularios["PESTANA_GENERAL"] = array(
        array(
            HTML::listaSeleccionSimple("sentido", $textos["SENTIDO"], $sentido, 0, array("title" => $textos["AYUDA_SENTIDO"]))
        ),
        array(
            HTML::listaSeleccionSimple("codigo_concepto_transaccion_contable", $textos["CONCEPTO_CONTABLE"], $conceptos, "", array("title" => $textos["AYUDA_CONCEPTO_CONTABLE"], "onchange" => "activarTipoRetencion()"))
        ),
        array(
            HTML::listaSeleccionSimple("certificado_ingresos_retenciones", $textos["CERTIFICADO_INGRESOS"], $certificado_ingresos, 0, array("title" => $textos["AYUDA_CERTIFICADO_INGRESO"]))
        ),
        array(
            HTML::campoTextoCorto("selector1", $textos["CODIGO_CONTABLE"], 30, 255, "", array("title" => $textos["AYUDA_CODIGO_CONTABLE"], "class" => "autocompletable", "onKeyUp" => "limpiar_oculto_Autocompletable(this, codigo_contable);"))
           .HTML::campoOculto("codigo_contable", "")
        ),
        array(
            HTML::listaSeleccionSimple("tipo_listado",$textos["TIPO_LISTADO"],$tipo_listado,"",array("title"=>$textos["AYUDA_TIPO_LISTADO"]))
        )
    );

    $botones = array(
        HTML::boton("botonAceptar", $textos["EXPORTAR"], "imprimirItem(1);", "aceptar")
    );

    $contenido = HTML::generarPestanas($formularios,$botones);
    // Enviar datos para la generacion del formulario al script que origino la peticion
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

// Exportar los datos
} elseif (!empty($forma_procesar)) {
    // Asumir por defecto que no hubo error
    $error        = false;
    $mensaje      = $textos["ARCHIVO_GENERADO"];
    $ruta_archivo = "";

    $condicion = "codigo != 0";

    $tipo_listado = (int)$forma_tipo_listado;

    //================== INICIO DE CONDICION ==================//
    if($forma_sentido != ""){
        $condicion .= " AND sentido = '".$forma_sentido."'";
    }
    if($forma_codigo_concepto_transaccion_contable != ""){
        $condicion .= " AND codigo_concepto_transaccion_contable = '".$forma_codigo_concepto_transaccion_contable."'";
    }
    if($forma_certificado_ingresos_retenciones != "1"){
        $condicion .= " AND certificado_ingresos_retenciones = '".$forma_certificado_ingresos_retenciones."'";
    }
    if($forma_codigo_contable != ""){
        $condicion .= " AND codigo_contable = '".$forma_codigo_contable."'";
    }
    //===================== FIN CONDICION =====================//

    $nombre         = "";
    $nombreArchivo  = "";
    do {
        $cadena         = Cadena::generarCadenaAleatoria(8);
        if ($tipo_listado == 1){
            $nombre = $sesion_sucursal.$cadena.".pdf";
        } else {
            $nombre = $sesion_sucursal.$cadena.".csv";
        }
        $nombreArchivo  = $rutasGlobales["archivos"]."/".$nombre;
    } while (is_file($nombreArchivo));

    if ($tipo_listado == 1){

        $fechaReporte = date("Y-m-d");
        $imprime_pdf  = true;

        $archivo                 = new PDF("L","mm","Letter");
        $archivo->textoCabecera  = $textos["FECHA"].": ".$fechaReporte;
        $archivo->textoTitulo    = $textos["REPORTE_TRANSACCIONES"];
        $archivo->AddPage();
        $archivo->textoPiePagina = "";

        $archivo->SetFont('Arial','B',6);
        $tituloColumnas = array($textos["CODIGO"],$textos["NOMBRE"],$textos["DESCRIPCION"],$textos["CODIGO_CONTABLE"],$textos["SENTIDO"],$textos["CONCEPTO_CONTABLE"],$textos["CERTIFICADO_INGRESOS"],$textos["COLUMNA_PLANILLA"]);
        $anchoColumnas = array(15,40,50,20,10,40,35,20);
        $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
        $archivo->Ln(5);
    } else {
        $archivo = fopen($nombreArchivo,"a+");
        $titulos_plano = "\"".$textos["CODIGO"]."\";\"".$textos["NOMBRE"]."\";\"".$textos["DESCRIPCION"]."\";\"".$textos["CODIGO_CONTABLE"]."\";\"".$textos["SENTIDO"]."\";\"".$textos["CONCEPTO_CONTABLE"]."\";\"".$textos["CERTIFICADO_INGRESOS"]."\";\"".$textos["COLUMNA_PLANILLA"]."\"\n";
        fwrite($archivo, $titulos_plano);
    }

    $consulta = SQL::seleccionar(array("transacciones_contables_empleado"),array("*"),$condicion);

    $genero_archivo = false;

    if(SQL::filasDevueltas($consulta)){

        while($datos = SQL::filaEnObjeto($consulta)){

            $codigo            = $datos->codigo;
            $nombreTransaccion = $datos->nombre;
            $descripcion       = $datos->descripcion;
            $cuenta            = $datos->codigo_contable;
            $sentido           = $datos->sentido;
            $concepto          = SQL::obtenerValor("conceptos_transacciones_contables_empleado","descripcion","codigo = '".$datos->codigo_concepto_transaccion_contable."'");
            $certificado       = $certificado_ingresos[$datos->certificado_ingresos_retenciones];
            $columna           = $datos->columna_planilla;

            if($tipo_listado == 1){
                if($archivo->breakCell(5)){
                    $archivo->AddPage();
                    $archivo->SetFont('Arial','B',6);
                    $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
                    $archivo->Ln(5);
                }
                if($archivo->FillColor != sprintf('%.3F %.3F %.3F rg',1,1,1)){
                    $archivo->SetFillColor(255,255,255);
                }else{
                    $archivo->SetFillColor(240,240,240);
                }
                $archivo->SetFont('Arial','',6);
                $archivo->Cell(15, 4, $codigo, 0, 0, "L",true,"",true);
                $archivo->Cell(40, 4, $nombreTransaccion, 0, 0, "L",true,"",true);
                $archivo->Cell(50, 4, $descripcion, 0, 0, "L",true,"",true);
                $archivo->Cell(20, 4, $cuenta, 0, 0, "L",true,"",true);
                $archivo->Cell(10, 4, $sentido, 0, 0, "C",true,"",true);
                $archivo->Cell(40, 4, $concepto, 0, 0, "L",true,"",true);
                $archivo->Cell(35, 4, $certificado, 0, 0, "L",true,"",true);
                $archivo->Cell(20, 4, $columna, 0, 0, "C",true,"",true);
                $archivo->Ln(4);
            }else{
                $contenido = "\"".$codigo."\";\"".$nombreTransaccion."\";\"".$descripcion."\";\"".$cuenta."\";\"".$sentido."\";\"".$concepto."\";\"".$certificado."\";\"".$columna."\"\n";
                $guardarArchivo = fwrite($archivo,$contenido);
            }

        }
        $genero_archivo = true;
    }

    if($genero_archivo){
        if($tipo_listado == 1){
            $archivo->Output($nombreArchivo, "F");
        }else{
            fclose($archivo);
        }

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
    }else {
        $error = true;
        $mensaje = $textos["ERROR_GENERAR_ARCHIVO"];
        $respuesta[0] = $error;
        $respuesta[1] = $mensaje;
    }

    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    $respuesta[2] = $ruta_archivo;

    HTTP::enviarJSON($respuesta);
}
?>

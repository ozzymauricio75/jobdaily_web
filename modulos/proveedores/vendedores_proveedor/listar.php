<?php
/**
*
* Copyright (C) 2020 Raul Mauricio Oidor Lozano
* Raul Mauricio Oidor Lozano <ozzymauricio75@gmail.com>
*
* Este archivo es parte de:
* PANCE :: Software empresarial a la medida
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
$tipos_personas = array(
        "0" => $textos[""],
        "1" => $textos["PERSONA_NATURAL"],
        "2" => $textos["PERSONA_JURIDICA"],
        "3" => $textos["CODIGO_INTERNO"],
        "4" => $textos["NATURAL_COMERCIANTE"]
);

// Devolver datos para autocompletar la busqueda
if (isset($url_completar)) {
    if($url_item == "selector1"){
        echo SQL::datosAutoCompletar("seleccion_localidades", $url_q);
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

    $tipos_documentos = HTML::generarDatosLista("tipos_documento_identidad", "id", "descripcion","");

    // Definicion de pestana general
    $formularios["PESTANA_GENERAL"] = array(
        array(
            HTML::listaSeleccionSimple("id_tipo_documento_identidad", $textos["TIPO_DOCUMENTO_IDENTIDAD"], $tipos_documentos, "")
        ),
        array(
            HTML::campoTextoCorto("selector1", $textos["LOCALIDAD"], 45, 255, "", array("title" => $textos["AYUDA_LOCALIDAD"], "class" => "autocompletable", "onKeyUp" => "limpiar_oculto_Autocompletable(this,id_localidad);"))
            .HTML::campoOculto("id_localidad", ""),
        ),
        array(
            HTML::campoTextoCorto("documento_identidad_tercero1", $textos["TERCERO_INICIAL"], 20, 12, "", array("title" => $textos["AYUDA_DOCUMENTO_IDENTIDAD"], "onKeyPress" => "return campoEntero(event);")),
            HTML::campoTextoCorto("documento_identidad_tercero2", $textos["TERCERO_FINAL"], 20, 12, "", array("title" => $textos["AYUDA_DOCUMENTO_IDENTIDAD"], "onKeyPress" => "return campoEntero(event);"))
        ),
        array(
            HTML::campoTextoCorto("fechas", $textos["FECHA_DESDE"], 20, 20,"", array("class" => "fechaRango"))
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

    $condicion_tipo_documento       = "";
    $condicion_localidad_residencia = "";
    $condicion_documentos           = "";
    $condicion_fechas               = "";
    $id_tercero_comprador           = "0";

    $consulta_terceros = SQL::seleccionar(array("compradores"),array("id_tercero"),"id>0","id_tercero");
    if (SQL::filasDevueltas($consulta_terceros)){
        $id_tercero_comprador = array();
        while($datos_comprador = SQL::filaEnObjeto($consulta_terceros)){
            $id_tercero_comprador[] = $datos_comprador->id_tercero;
        }
        $id_tercero_comprador = implode(",",$id_tercero_comprador);
    }

    $tipo_listado = (int)$forma_tipo_listado;

    if($forma_id_tipo_documento_identidad != "0"){
        $condicion_tipo_documento = " AND id_tipo_documento_identidad = '$forma_id_tipo_documento_identidad'";
    }
    if($forma_id_localidad != ""){
        $condicion_localidad_residencia = " AND id_localidad = '$forma_id_localidad'";
    }
    if($forma_fechas != ""){
        $fechas        = explode("-",$forma_fechas);
        $fecha_inicial = trim($fechas[0]);
        $fecha_final   = trim($fechas[1]);
        $condicion_fechas = " AND (fecha_registra BETWEEN '".$fecha_inicial."' AND '".$fecha_final."')";
    }
    if($forma_documento_identidad_tercero1 != ""){
        $condicion_documentos = " AND documento_identidad >= ".$forma_documento_identidad_tercero1;
    }
    if($forma_documento_identidad_tercero2 != "" && $forma_documento_identidad_tercero1 == ""){
        $error   = true;
        $mensaje = $textos["ERROR_DOCUMENTO_IDENTIDAD_2"];

    }else if($forma_documento_identidad_tercero2 != "" && $forma_documento_identidad_tercero1 != ""){
        $condicion_documentos .= " AND documento_identidad <= ".$forma_documento_identidad_tercero2;

    }
    $condicion = "id>0 AND id IN($id_tercero_comprador)".$condicion_tipo_documento.$condicion_localidad_residencia.$condicion_documentos.$condicion_fechas;

    $nombre         = "";
    $nombreArchivo  = "";

    $consulta = SQL::seleccionar(array("terceros"),array("*"), $condicion,"","id_tipo_documento_identidad,documento_identidad,primer_nombre,razon_social");
    $genero_archivo = false;

    if(SQL::filasDevueltas($consulta)){

        do {
            $cadena = Cadena::generarCadenaAleatoria(8);
            if ($tipo_listado == 1){
                $nombre = (int)$sesion_id_usuario_ingreso.$cadena.".pdf";
            } else {
                $nombre = (int)$sesion_id_usuario_ingreso.$cadena.".csv";
            }
            $nombreArchivo  = $rutasGlobales["temp"]."/".$nombre;
        } while (is_file($nombreArchivo));

        if ($tipo_listado == 1){

            $fechaReporte = date("Y-m-d");
            $imprime_pdf  = true;

            $archivo                 = new PDF("L","mm","Legal");
            $archivo->textoCabecera  = $textos["FECHA"].": ".$fechaReporte;
            $archivo->textoTitulo    = $textos["REPORTE_COMPRADORES"];
            $archivo->AddPage("","",false,true);
            $archivo->textoPiePagina = $textos["ELABORADO_POR"].": ".SQL::obtenerValor("usuarios","nombre","id = '".$sesion_id_usuario_ingreso."'")." ".date("Y-m-d H:i:s");

            $archivo->SetFont('Arial','B',6);
            $archivo->Cell(100, 4, $textos["REPORTE_COMPRADORES"], 0, 0);
            $archivo->Ln(4);
            $tituloColumnas = array(
                $textos["TIPO_DOCUMENTO"], $textos["DOCUMENTO_IDENTIDAD"], $textos["NOMBRE_COMPLETO"], $textos["LUGAR_RESIDENCIA"], $textos["TELEFONO"], $textos["CELULAR"], $textos["CORREO"]
            );
            $anchoColumnas = array(30,30,60,60,30,20,35);
            $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
            $archivo->Ln(4);
        } else {
            $archivo = fopen($nombreArchivo,"a+");
            $titulos_plano = "\"".$textos["TIPO_DOCUMENTO"]."\";\"".$textos["DOCUMENTO_IDENTIDAD"]."\";\"".$textos["NOMBRE_COMPLETO"]."\";\"".$textos["LUGAR_RESIDENCIA"]."\";\"".$textos["TELEFONO"]."\";\"".$textos["CELULAR"]."\";\"".$textos["CORREO"]."\"\n";
            fwrite($archivo, $titulos_plano);
        }
        while($datos = SQL::filaEnObjeto($consulta)){

            if (!isset($tipo_documento[$datos->id_tipo_documento_identidad])){
                $tipo_documento[$datos->id_tipo_documento_identidad] = SQL::obtenerValor("tipos_documento_identidad","descripcion","id = '$datos->id_tipo_documento_identidad'");
            }
            if (!isset($localidad[$datos->id_localidad])){
                $descripcion_localidad = SQL::obtenerValor("seleccion_localidades","nombre","id='$datos->id_localidad'");
                $descripcion_localidad = explode("|",$descripcion_localidad);
                $descripcion_localidad = $descripcion_localidad[0];
                $localidad[$datos->id_localidad] = $descripcion_localidad;
            }
            $tercero   = SQL::obtenerValor("menu_terceros","NOMBRE_COMPLETO","id = '$datos->id'");
            if($tipo_listado == 1){
                if($archivo->breakCell(5)){
                    $archivo->AddPage("","",false,true);
                    $archivo->SetFont('Arial','B',6);
                    $archivo->Cell(100, 4, $textos["REPORTE_COMPRADORES"], 0, 0);
                    $archivo->Ln(4);
                    $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
                    $archivo->Ln(4);
                }
                $archivo->SetFont('Arial','',6);
                $alineamiento = array("L","L","L","L","L","L","L");
                $archivo->SetAligns($alineamiento);
                $archivo->SetWidths($anchoColumnas);
                $texto  = array(
                    $tipo_documento[$datos->id_tipo_documento_identidad],
                    $datos->documento_identidad,
                    $tercero,
                    $localidad[$datos->id_localidad],
                    $datos->telefono_principal,
                    $datos->celular,
                    $datos->correo
                );
                $archivo->Row($texto,true,$tituloColumnas, $anchoColumnas);
            }else{
                $contenido = "\"".$tipo_documento[$datos->id_tipo_documento_identidad]."\";\"$datos->documento_identidad\";\"$tercero\";\"".$localidad[$datos->id_localidad]."\";\"$datos->telefono_principal\";\"$datos->celular\";\"$datos->correo\"\n";
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

        $ruta_archivo = HTTP::generarURL("DESCARCH")."&temporal=1&nombre_archivo=".$nombre;
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

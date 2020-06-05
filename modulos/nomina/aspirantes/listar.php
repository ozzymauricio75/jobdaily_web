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



    $tipos_documentos = HTML::generarDatosLista("tipos_documento_identidad", "codigo", "descripcion","");


    // Definicion de pestana general
    $formularios["PESTANA_GENERAL"] = array(
        array(
            HTML::listaSeleccionSimple("tipo_documento", $textos["TIPO_DOCUMENTO"], $tipos_documentos, "")
        ),
        array(
            HTML::campoTextoCorto("selector1", $textos["LOCALIDAD"], 45, 255, "", array("title" => $textos["AYUDA_LOCALIDAD"], "class" => "autocompletable", "onKeyUp" => "limpiar_oculto_Autocompletable(this,codigo_localidad_residencia);"))
            .HTML::campoOculto("codigo_localidad_residencia", ""),
        ),
        array(
            HTML::campoTextoCorto("documento_identidad_tercero1", $textos["TERCERO_INICIAL"], 20, 12, "", array("title" => $textos["AYUDA_DOCUMENTO_IDENTIDAD"], "onKeyPress" => "return campoEntero(event);")),
            HTML::campoTextoCorto("documento_identidad_tercero2", $textos["TERCERO_FINAL"], 20, 12, "", array("title" => $textos["AYUDA_DOCUMENTO_IDENTIDAD"], "onKeyPress" => "return campoEntero(event);"))
        ),
        array(
            HTML::campoTextoCorto("fechas", $textos["FECHA_DESDE"].'  -  '.$textos["FECHA_HASTA"], 25, 25,"", array("class" => "fechaRango"))
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

    $condicion_tipo_persona         = "a.documento_identidad = t.documento_identidad";
    $condicion_tipo_documento       = "";
    $condicion_localidad_residencia = "";
    $condicion_documentos           = "";
    $condicion_fechas               = "";

    $tipo_listado = (int)$forma_tipo_listado;

    if($forma_tipo_documento != "0"){
        $condicion_tipo_documento = " AND t.codigo_tipo_documento = '".$forma_tipo_documento."'";
    }
    if($forma_tipo_documento != "0"){
        $condicion_tipo_documento = " AND t.codigo_tipo_documento = '".$forma_tipo_documento."'";
    }
    if($forma_codigo_localidad_residencia != ""){
        $localidad_residencia = explode(',',$forma_codigo_localidad_residencia);
        $condicion_localidad_residencia = " AND t.codigo_iso_localidad = '".$localidad_residencia[0]."'
                                            AND t.codigo_dane_departamento_localidad = '".$localidad_residencia[1]."'
                                            AND t.codigo_dane_municipio_localidad = '".$localidad_residencia[2]."'
                                            AND t.tipo_localidad = '".$localidad_residencia[3]."'
                                            AND t.codigo_dane_localidad = '".$localidad_residencia[4]."'";
    }
    if($forma_fechas != ""){
        $fechas        = explode("-",$forma_fechas);
        $fecha_inicial = trim($fechas[0]);
        $fecha_final   = trim($fechas[1]);
        $condicion_fechas = " AND (t.fecha_nacimiento BETWEEN '".$fecha_inicial."' AND '".$fecha_final."')";
    }
    if($forma_documento_identidad_tercero1 != ""){
        $condicion_documentos = " AND t.documento_identidad >= ".$forma_documento_identidad_tercero1;
    }
    if($forma_documento_identidad_tercero2 != "" && $forma_documento_identidad_tercero1 == ""){
        $error   = true;
        $mensaje = $textos["ERROR_DOCUMENTO_IDENTIDAD_2"];
    }else if($forma_documento_identidad_tercero2 != "" && $forma_documento_identidad_tercero1 != ""){
        $condicion_documentos .= " AND t.documento_identidad <= ".$forma_documento_identidad_tercero2;
    }
    $condicion = $condicion_tipo_persona.$condicion_tipo_documento.$condicion_localidad_residencia.$condicion_documentos.$condicion_fechas;

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

        $archivo                 = new PDF("L","mm","Legal");
        $archivo->textoCabecera  = $textos["FECHA"].": ".$fechaReporte;
        $archivo->textoTitulo    = $textos["REPORTE_ASPIRANTES"];
        $archivo->AddPage();
        $archivo->textoPiePagina = "";

        $archivo->SetFont('Arial','B',6);
        $tituloColumnas = array(
            $textos["TIPO_DOCUMENTO"], $textos["DOCUMENTO_IDENTIDAD"], $textos["NOMBRE_COMPLETO"], $textos["LUGAR_RESIDENCIA"], $textos["TELEFONO"], $textos["CELULAR"], $textos["CORREO_ELECTRONICO"], $textos["GENERO"], $textos["FECHA_NACIMIENTO"]
        );
        $anchoColumnas = array(30,30,60,60,30,20,35,15,25);
        $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
        $archivo->Ln(4);
    } else {
        $archivo = fopen($nombreArchivo,"a+");
        $titulos_plano = "\"".$textos["TIPO_DOCUMENTO"]."\";\"".$textos["DOCUMENTO_IDENTIDAD"]."\";\"".$textos["NOMBRE_COMPLETO"]."\";\"".$textos["LUGAR_RESIDENCIA"]."\";\"".$textos["TELEFONO"]."\";\"".$textos["CELULAR"]."\";\"".$textos["CORREO_ELECTRONICO"]."\";\"".$textos["GENERO"]."\";\"".$textos["FECHA_NACIMIENTO"]."\"\n";
        fwrite($archivo, $titulos_plano);
    }

    $consulta = SQL::seleccionar(array("aspirantes a","terceros t"),array("*"),$condicion." AND t.documento_identidad != 0","","t.codigo_tipo_documento,t.documento_identidad,t.tipo_persona,t.primer_nombre,t.razon_social");

    $genero_archivo = false;

    if(SQL::filasDevueltas($consulta)){

        while($datos = SQL::filaEnObjeto($consulta)){

            $tipo_documento  = SQL::obtenerValor("tipos_documento_identidad","descripcion","codigo = '".$datos->codigo_tipo_documento."'");
            $llave_localidad = $datos->codigo_iso_localidad.'|'.$datos->codigo_dane_departamento_localidad.'|'.$datos->codigo_dane_municipio_localidad.'|'.$datos->tipo_localidad.'|'.$datos->codigo_dane_localidad;
            $localidad       = SQL::obtenerValor("seleccion_localidades","SUBSTRING_INDEX(nombre,'|',1)","id = '".$llave_localidad."'");
            $tercero         = SQL::obtenerValor("seleccion_terceros","SUBSTRING_INDEX(nombre,'|',1)","id = '".$datos->documento_identidad."'");
            $tercero         = explode(',',$tercero);
            $tercero         = $tercero[1];

            if($tipo_listado == 1){
                if($archivo->breakCell(5)){
                    $archivo->AddPage();
                    $archivo->SetFont('Arial','B',6);
                    $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
                    $archivo->Ln(4);
                }
                $archivo->SetFont('Arial','',6);
                $archivo->Cell(30, 4, $tipo_documento, 0, 0, "L");
                $archivo->Cell(30, 4, $datos->documento_identidad, 0, 0, "L");
                $archivo->Cell(60, 4, $tercero, 0, 0, "L",false,"",true);
                $archivo->Cell(60, 4, $localidad, 0, 0, "L",false,"",true);
                $archivo->Cell(30, 4, $datos->telefono_principal, 0, 0, "L");
                $archivo->Cell(20, 4, $datos->celular, 0, 0, "L");
                $archivo->Cell(35, 4, $datos->correo, 0, 0, "L");
                $archivo->Cell(15, 4, $datos->genero, 0, 0, "C");
                $archivo->Cell(25, 4, $datos->fecha_nacimiento, 0, 0, "C");
                $archivo->Ln(4);
            }else{
                $contenido = "\"$tipo_documento\";\"$datos->documento_identidad\";\"$tercero\";\"$localidad\";\"$datos->telefono_principal\";\"$datos->celular\";\"$datos->correo\";\"$datos->genero\";\"$datos->fecha_nacimiento\"\n";
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

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

// Generar el formulario para la captura de datos
if (!empty($url_generar)) {

    $error  = "";
    $titulo = $componente->nombre;

    $tipo_listado = array(
        "1" => $textos["ARCHIVO_PDF"],
        "2" => $textos["ARCHIVO_PLANO"]
    );

    // Definicion de pestana general
    $formularios["PESTANA_GENERAL"] = array(
        array(
            HTML::listaSeleccionSimple("tipo_listado",$textos["TIPO_LISTADO"],$tipo_listado,"",array("title"=>$textos["AYUDA_TIPO_LISTADO"]))
        )
    );

    $botones = array (
        HTML::boton("botonAceptar", $textos["ACEPTAR"], "imprimirItem('1');", "aceptar")
    );

    $contenido = HTML::generarPestanas($formularios, $botones);

    // Enviar datos para la generacion del formulario al script que origino la peticion
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

// Exportar los datos
} elseif (!empty($forma_procesar)) {

    $ruta_archivo = "";
    $condicion    = "codigo != 0";
    $error        = false;
    $mensaje      = $textos["ARCHIVO_GENERADO"];

    $tipo_listado = (int)$forma_tipo_listado;

    // ====================== Inicio de condiciones ====================== //

    // ======================= Fin  de condiciones ======================= //

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

    $archivo                 = new PDF("L","mm","a4");
    $archivo->textoTitulo    = $textos["REPORTE_TURNOS"];
    $archivo->textoCabecera  = $textos["FECHA"].": ".date("Y-m-d");
    $archivo->textoPiePagina = "";
    $archivo->AddPage();

    $listaSemana = array($textos["LUNES_M"],$textos["MARTES_M"],$textos["MIERCOLES_M"],$textos["JUEVES_M"],$textos["VIERNES_M"],$textos["SABADO_M"],$textos["DOMINGO_M"]);

    if ($tipo_listado == 1){
        $tituloColumnas = array($textos["CODIGO"],$textos["DESCRIPCION"],$textos["LUNES"],$textos["MARTES"],$textos["MIERCOLES"],$textos["JUEVES"],$textos["VIERNES"],$textos["SABADO"],$textos["DOMINGO"]);
        $anchoColumnas  = array(20,47,30,30,30,30,30,30,30);
        $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
        $archivo->Ln(4);
    }else{
        $archivo = fopen($nombreArchivo,"a+");
        $titulos_plano = "\"".$textos["CODIGO"]."\";\"".$textos["DESCRIPCION"]."\";\"".$textos["LUNES"]."\";\"".$textos["MARTES"]."\";\"".$textos["MIERCOLES"]."\";\"".$textos["JUEVES"]."\";\"".$textos["VIERNES"]."\";\"".$textos["SABADO"]."\";\"".$textos["DOMINGO"]."\"\n";
        fwrite($archivo, $titulos_plano);
    }

    $i = 0;

    $consulta = SQL::seleccionar(array("turnos_laborales"), array("*"),$condicion);

    if (SQL::filasDevueltas($consulta)) {
        while($datos = SQL::filaEnObjeto($consulta)) {

            if($tipo_listado == 1){

                if($archivo->breakCell(5)){
                    $archivo->AddPage();
                    $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
                    $archivo->Ln(4);
                }

                if($archivo->FillColor != sprintf('%.3F %.3F %.3F rg',1,1,1)){
                    $archivo->SetFillColor(255,255,255);
                }else{
                    $archivo->SetFillColor(240,240,240);
                }

                $archivo->SetFont('Arial',"",8);
                $archivo->Cell(20, 4, $datos->codigo, 1, 0, "C", true,"",true);
                $archivo->Cell(47, 4, $datos->descripcion, 1, 0, "L", true,"",true);
                $archivo->SetFont('Arial',"I",6);
                for($i = 0; $i<7; $i++){
                    $tipo        = "tipo_turno_".$listaSemana[$i];
                    $horaInicio1 = "hora_inicial_turno1_".$listaSemana[$i];
                    $horaFinal1  = "hora_final_turno1_".$listaSemana[$i];
                    $horaInicio2 = "hora_inicial_turno2_".$listaSemana[$i];
                    $horaFinal2  = "hora_final_turno2_".$listaSemana[$i];
                    $descanso    = "dia_descanso_".$listaSemana[$i];

                    $diaDescanso = $datos->$descanso;
                    $tipoTurno   = $datos->$tipo;

                    $hora1 = substr($datos->$horaInicio1,0,5).'-'.substr($datos->$horaFinal1,0,5);
                    $hora2 = substr($datos->$horaInicio2,0,5).'-'.substr($datos->$horaFinal2,0,5);

                    if($diaDescanso == '0'){
                        if($tipoTurno == '1'){
                            $archivo->Cell(30, 4, $hora1, 1, 0, "C", true,"",true);
                        }else{
                            $archivo->Cell(30, 4, $hora1." / ".$hora2, 1, 0, "C", true,"",true);
                        }
                    }else{
                        $archivo->SetFont('Arial',"B",6);
                        $archivo->Cell(30, 4, $textos["DIA_DESCANSO"], 1, 0, "C", true,"",true);
                    }
                }
                $archivo->Ln(4);
            }else{
                $contenido = "\"$datos->codigo\";\"$datos->descripcion\";";

                for($i = 0; $i<7; $i++){
                    $tipo        = "tipo_turno_".$listaSemana[$i];
                    $horaInicio1 = "hora_inicial_turno1_".$listaSemana[$i];
                    $horaFinal1  = "hora_final_turno1_".$listaSemana[$i];
                    $horaInicio2 = "hora_inicial_turno2_".$listaSemana[$i];
                    $horaFinal2  = "hora_final_turno2_".$listaSemana[$i];
                    $descanso    = "dia_descanso_".$listaSemana[$i];

                    $diaDescanso = $datos->$descanso;
                    $tipoTurno   = $datos->$tipo;

                    $hora1 = substr($datos->$horaInicio1,0,5).'-'.substr($datos->$horaFinal1,0,5);
                    $hora2 = substr($datos->$horaInicio2,0,5).'-'.substr($datos->$horaFinal2,0,5);

                    if($i != 6){
                        if($diaDescanso == '0'){
                            if($tipoTurno == '1'){
                                $contenido .= "\"".$hora1."\";";
                            }else{
                                $contenido .= "\"".$hora1." / ".$hora2."\";";
                            }
                        }else{
                            $contenido .= "\"".$textos["DIA_DESCANSO"]."\";";
                        }
                    }else{
                        if($diaDescanso == '0'){
                            if($tipoTurno == '1'){
                                $contenido .= "\"".$hora1."\"\n";
                            }else{
                                $contenido .= "\"".$hora1." / ".$hora2."\"\n";
                            }
                        }else{
                            $contenido .= "\"".$textos["DIA_DESCANSO"]."\"\n";
                        }
                    }
                }
                $guardarArchivo = fwrite($archivo,$contenido);
            }

            $i++;
        }
    }

    $cargaPdf = 0;

    if($i>0 && !$error) {
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
        $cargaPdf = 1;
    }

    // Enviar datos con la respuesta del proceso al script que origino la peticion

    if ($cargaPdf == 1) {
        $ruta    = $ruta_archivo;
    } else if($cargaPdf == 0 && !$error){
        $error = true;
        $mensaje = $textos["ERROR_GENERAR_ARCHIVO"];
        $ruta = "";
    }
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    $respuesta[2] = $ruta;
    HTTP::enviarJSON($respuesta);
}
?>

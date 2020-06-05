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

    $sucursales = HTML::generarDatosLista("sucursales", "codigo", "nombre", "");

    // Definicion de pestana general
    $formularios["PESTANA_GENERAL"] = array(
        array(
            HTML::listaSeleccionSimple("codigo_sucursal", $textos["SUCURSAL"], $sucursales, "")
        ),
        array(
            HTML::campoTextoCorto("fechas", $textos["DESDE_HASTA"], 19, 25,"", array("class" => "fechaRango"))
        ),
        array(
            HTML::campoTextoCorto("documento_identidad_tercero1", $textos["DOCUMENTO_DESDE"], 20, 12, "", array("onKeyPress" => "return campoEntero(event);"))
        ),
        array(
            HTML::campoTextoCorto("documento_identidad_tercero2", $textos["DOCUMENTO_HASTA"], 20, 12, "", array("onKeyPress" => "return campoEntero(event);"))
        )
    );

    $botones = array (
        HTML::boton("botonAceptar", $textos["ACEPTAR"], "imprimirItem('0');", "aceptar")
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
    $condicion    = "1";
    $error        = false;
    $mensaje      = $textos["ARCHIVO_GENERADO"];

    // ====================== Inicio de condiciones ====================== //

    if((int)$forma_codigo_sucursal != 0){
        $condicion .= " AND codigo_sucursal = '".$forma_codigo_sucursal."'";
    }

    if($forma_fechas != ""){
        $fechas = explode("-",$forma_fechas);
        $fecha1 = trim($fechas[0]);
        $fecha2 = trim($fechas[1]);
        $fecha1 = str_replace("/","-",$fecha1);
        $fecha2 = str_replace("/","-",$fecha2);
        $condicion .= " AND (fecha_inicial BETWEEN '".$fecha1."' AND '".$fecha2."')";
    }

    if($forma_documento_identidad_tercero2 != ""){
        $condicion .= " AND documento_identidad_empleado <= ".$forma_documento_identidad_tercero2;
    }
    if($forma_documento_identidad_tercero1 != "" && $forma_documento_identidad_tercero2 == ""){
        $error   = true;
        $mensaje = $textos["ERROR_DOCUMENTO_IDENTIDAD_1"];
        $ruta    = "";
    }else if($forma_documento_identidad_tercero2 != "" && $forma_documento_identidad_tercero1 != ""){
        $condicion .= " AND documento_identidad_empleado >= ".$forma_documento_identidad_tercero1;
    }

    // ======================= Fin  de condiciones ======================= //

    $nombre         = "";
    $nombreArchivo  = "";
    do {
        $cadena         = Cadena::generarCadenaAleatoria(8);
        $nombre = $sesion_sucursal.$cadena.".pdf";
        $nombreArchivo  = $rutasGlobales["archivos"]."/".$nombre;
    } while (is_file($nombreArchivo));

    $archivo                 = new PDF("L","mm","Legal");
    $archivo->textoTitulo    = $textos["LISTASTU"];
    $archivo->textoCabecera  = $textos["FECHA"].": ".date("Y-m-d");
    $archivo->textoPiePagina = "";
    $archivo->AddPage();

    $listaSemana = array(strtolower($textos["LU"]),strtolower($textos["MA"]),strtolower($textos["MI"]),strtolower($textos["JU"]),strtolower($textos["VI"]),strtolower($textos["SA"]),strtolower($textos["DO"]));

    $tituloColumnas = array($textos["SUCURSAL"],$textos["EMPLEADO"],$textos["TURNO_LABORAL"],$textos["DESDE"],$textos["HASTA"],$textos["LU"],$textos["MA"],$textos["MI"],$textos["JU"],$textos["VI"],$textos["SA"],$textos["DO"]);
    $anchoColumnas  = array(30,51,30,18,18,27,27,27,27,27,27,27);

    $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
    $archivo->Ln(4);
    $i = 0;

    $consulta = SQL::seleccionar(array("asignacion_turnos"), array("*"),$condicion,"","fecha_inicial");

    if (SQL::filasDevueltas($consulta)) {
        while($datos = SQL::filaEnObjeto($consulta)) {
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

            $sucursal = SQL::obtenerValor("sucursales","nombre","codigo = '".$datos->codigo_sucursal."'");
            $empleado = SQL::obtenerValor("seleccion_terceros","SUBSTRING_INDEX(nombre,'|',1)","id = '".$datos->documento_identidad_empleado."'");

            $consultaTurno = SQL::seleccionar(array("turnos_laborales"),array("*"),"codigo = '".$datos->codigo_turno."'");
            $datosTurno    = SQL::filaEnObjeto($consultaTurno);

            $archivo->SetFont('Arial',"",6);
            $archivo->Cell(30, 4, $sucursal, 1, 0, "L", true,"",true);
            $archivo->Cell(51, 4, $empleado, 1, 0, "L", true,"",true);
            $archivo->Cell(30, 4, $datosTurno->descripcion, 1, 0, "L", true,"",true);
            $archivo->Cell(18, 4, $datos->fecha_inicial, 1, 0, "C", true,"",true);
            $archivo->Cell(18, 4, $datos->fecha_final, 1, 0, "C", true,"",true);
            for($i = 0; $i<7; $i++){
                $tipo        = "tipo_turno_".$listaSemana[$i];
                $horaInicio1 = "hora_inicial_turno1_".$listaSemana[$i];
                $horaFinal1  = "hora_final_turno1_".$listaSemana[$i];
                $horaInicio2 = "hora_inicial_turno2_".$listaSemana[$i];
                $horaFinal2  = "hora_final_turno2_".$listaSemana[$i];
                $descanso    = "dia_descanso_".$listaSemana[$i];

                $diaDescanso = $datosTurno->$descanso;
                $tipoTurno   = $datosTurno->$tipo;

                $hora1 = substr($datosTurno->$horaInicio1,0,5).'-'.substr($datosTurno->$horaFinal1,0,5);
                $hora2 = substr($datosTurno->$horaInicio2,0,5).'-'.substr($datosTurno->$horaFinal2,0,5);

                if($diaDescanso == '0'){
                    if($tipoTurno == '1'){
                        $archivo->Cell(27, 4, $hora1, 1, 0, "C", true,"",true);
                    }else{
                        $archivo->Cell(27, 4, $hora1." / ".$hora2, 1, 0, "C", true,"",true);
                    }
                }else{
                    $archivo->Cell(27, 4, $textos["DIA_DESCANSO"], 1, 0, "C", true,"",true);
                }
            }
            $archivo->Ln(4);

            $i++;
        }
    }

    $cargaPdf = 0;

    if($i>0 && !$error) {
        $archivo->Output($nombreArchivo, "F");
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

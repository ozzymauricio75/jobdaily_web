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

// Devolver datos para autocompletar la búsqueda
if (isset($url_completar)) {
    if (($url_item) == "selector1") {
       echo SQL::datosAutoCompletar("seleccion_todo_plan_contable", $url_q);
    }
    if (($url_item) == "selector2") {
       echo SQL::datosAutoCompletar("seleccion_todo_plan_contable", $url_q);
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

    // Definición de pestaña general
    $formularios["PESTANA_GENERAL"] = array(
        array(
            HTML::campoTextoCorto("selector1", $textos["CUENTA_INICIAL"], 60, 100, "", array("title" => $textos["AYUDA_CUENTA_INICIAL"], "class" => "autocompletable_1"))
            .HTML::campoOculto("codigo_contable_inicial", "")
        ),
        array(
            HTML::campoTextoCorto("selector2", $textos["CUENTA_FINAL"], 60, 100, "", array("title" => $textos["AYUDA_CUENTA_FINAL"], "class" => "autocompletable_1"))
            .HTML::campoOculto("codigo_contable_final", "")
        ),
        array(
            HTML::listaSeleccionSimple("tipo_listado",$textos["TIPO_LISTADO"],$tipo_listado,"",array("title"=>$textos["AYUDA_TIPO_LISTADO"]))
        )
    );

    $botones = array(
        HTML::boton("botonAceptar", $textos["EXPORTAR"], "imprimirItem(1);", "aceptar")
    );

    $contenido = HTML::generarPestanas($formularios,$botones);
    // Enviar datos para la generación del formulario al script que originï¿½ la peticiï¿½n
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

// Exportar los datos
} elseif (!empty($forma_procesar)){


    if (!empty($forma_codigo_contable_inicial)){
        $cuenta_inicial         = explode(",",$forma_selector1);
        $cuenta_inicial         = $cuenta_inicial[0];

        if (empty($forma_codigo_contable_final)){
            $cuenta_final = "";
        } else {
            $cuenta_final           = explode(",",$forma_selector2);
            $cuenta_final           = $cuenta_final[0];
        }
        $condicion_cuenta       = "codigo_contable !='' AND codigo_contable >='$cuenta_inicial' AND codigo_contable<='$cuenta_final'";
        $cuenta_inicial_compara = "'".$cuenta_inicial."'";
        $cuenta_final_compara   = "'".$cuenta_final."'";
    } else if (!empty($forma_codigo_contable_final)){
        if (empty($forma_codigo_contable_inicial)){
            $condicion_cuenta_inicial         = "";
        } else {
            $cuenta_inicial           = explode(",",$forma_selector1);
            $cuenta_inicial           = $cuenta_inicial[0];
            $condicion_cuenta_inicial = " AND codigo_contable>='$cuenta_inicial' AND ";
        }
        $cuenta_final           = explode(",",$forma_selector2);
        $cuenta_final           = $cuenta_final[0];
        $condicion_cuenta       = "codigo_contable !='' $condicion_cuenta_inicial AND codigo_contable<='$cuenta_final'";
        $cuenta_inicial_compara = "'".$cuenta_inicial."'";
        $cuenta_final_compara   = "'".$cuenta_final."'";
    } else {
        $condicion_cuenta = "codigo_contable !=''";
    }

    $datos_incompletos = false;
    if (!empty($forma_codigo_contable_inicial) && empty($forma_codigo_contable_final)){
        $error             = true;
        $mensaje           = $textos["ERROR_CUENTA_FINAL"];
        $datos_incompletos = true;
    } else if(isset($cuenta_inicial_compara) && isset($cuenta_final_compara) && $cuenta_inicial_compara>$cuenta_final_compara){
        $error             = true;
        $mensaje           = $textos["ERROR_CUENTA_INICIAL"];
        $datos_incompletos = true;
    } else {

        $imprime_pdf = false;
        $nombre         = "";
        $nombreArchivo  = "";
        do {
            $cadena         = Cadena::generarCadenaAleatoria(8);
            if ($forma_tipo_listado == 1){
                $nombre = $sesion_sucursal.$cadena.".pdf";
            } else {
                $nombre = $sesion_sucursal.$cadena.".csv";
            }
            $nombreArchivo  = $rutasGlobales["archivos"]."/".$nombre;
        } while (is_file($nombreArchivo));

        $genero_archivo = false;

        $tipo_cuenta = array(
            "1" => $textos["BALANCE"],
            "2" => $textos["GANANCIAS_Y_PERDIDAS"],
            "3" => $textos["CUENTA_ORDEN"]
        );

        $clase_cuenta = array(
            "1" => $textos["CUENTA_MOVIMIENTO"],
            "2" => $textos["CUENTA_MAYOR"]
        );

        $consulta = SQL::seleccionar(array("plan_contable"),array("*"), $condicion_cuenta, "", "codigo_contable ASC");

        if (SQL::filasDevueltas($consulta)){
            $genero_archivo = true;
            $imprime_pdf    = false;
            $imprime_plano  = false;

            if ($forma_tipo_listado == 1){

                $fechaReporte = date("Y-m-d");
                $imprime_pdf  = true;

                $archivo                 = new PDF("P","mm","Letter");
                $archivo->textoCabecera  = $textos["FECHA"].": ".$fechaReporte;
                $archivo->textoTitulo    = $textos["PLAN_CONTABLE"];
                $archivo->AddPage();
                $archivo->textoPiePagina = "";

                $archivo->SetFont('Arial','B',6);
                $tituloColumnas = array(
                    $textos["CODIGO"], $textos["DESCRIPCION"], $textos["PADRE"], $textos["DB_CR"], $textos["ANEXO"], $textos["BENEFICIARIO"], $textos["TASA_1_LISTADO"], $textos["TASA_2_LISTADO"], $textos["TIPO_CUENTA"], $textos["CLASE_CUENTA"]
                );
                $anchoColumnas = array(15,60,15,8,8,6,10,10,25,25);
                $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
                $indicador_colores=1;
            } else {
                $archivo = fopen($nombreArchivo,"a+");
                $titulos_plano = $textos["CODIGO"].";".$textos["DESCRIPCION"].";".$textos["PADRE"].";".$textos["DB_CR"].";".$textos["ANEXO"].";".$textos["BENEFICIARIO"].";".$textos["TASA_1_LISTADO"].";".$textos["TASA_2_LISTADO"].";".$textos["TIPO_CUENTA"].";".$textos["CLASE_CUENTA"]."\n";
                fwrite($archivo, $titulos_plano);
            }

            while ($datos = SQL::filaEnObjeto($consulta)) {

                if ($datos->naturaleza_cuenta == 'D'){
                    $sentido = $textos["DB"];
                } else {
                    $sentido = $textos["CR"];
                }

                $tasa1 = SQL::obtenerValor("vigencia_tasas","porcentaje","codigo_tasa='$datos->codigo_tasa_aplicar_1'","FECHA DESC","",0,1);
                if (!$tasa1 || $tasa1==0){
                    $tasa1 = (int)$tasa1;
                }
                $tasa2 = SQL::obtenerValor("vigencia_tasas","porcentaje","codigo_tasa='$datos->codigo_tasa_aplicar_2'","FECHA DESC","",0,1);
                if (!$tasa2 || $tasa2==0){
                    $tasa2 = (int)$tasa2;
                }

                if ($forma_tipo_listado == 1){
                    $archivo->Ln(4);
                    $imprime_cabecera = $archivo->breakCell(4);
                    if ($imprime_cabecera){
                        $archivo->SetFont('Arial','B',6);
                        $tituloColumnas = array(
                            $textos["CODIGO"], $textos["DESCRIPCION"], $textos["PADRE"], $textos["DB_CR"], $textos["ANEXO"], $textos["BENEFICIARIO"], $textos["TASA_1_LISTADO"], $textos["TASA_2_LISTADO"], $textos["TIPO_CUENTA"], $textos["CLASE_CUENTA"]
                        );
                        $anchoColumnas = array(15,60,15,8,8,6,10,10,25,25);
                        $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
                        $archivo->Ln(4);
                    }

                    $indicador_colores++;
                    if($indicador_colores%2==0){
                        $rojo = 255;
                        $azul = 255;
                        $verde = 255;
                    } else {
                        $rojo  = 230;
                        $azul  = 230;
                        $verde = 230;
                    }

                    $archivo->SetFont('Arial','',6);
                    $archivo->SetFillColor($rojo,$verde,$azul);
                    $archivo->Cell(15, 3, $datos->codigo_contable, 0, 0, "L", true);
                    $archivo->Cell(60, 3, $datos->descripcion, 0, 0, "L", true);
                    $archivo->Cell(15, 3, $datos->codigo_contable_padre, 0, 0, "L", true);
                    $archivo->Cell(8, 3, $sentido, 0, 0, "C", true);
                    $archivo->Cell(8, 3, $datos->codigo_anexo_contable, 0, 0, "L", true);
                    $archivo->Cell(6, 3, $datos->maneja_tercero, 0, 0, "R", true);
                    $archivo->Cell(10, 3, $tasa1, 0, 0, "R", true);
                    $archivo->Cell(10, 3, $tasa2, 0, 0, "R", true);
                    $archivo->Cell(25, 3, $tipo_cuenta[$datos->tipo_cuenta], 0, 0, "L", true);
                    $archivo->Cell(25, 3, $clase_cuenta[$datos->clase_cuenta], 0, 0, "L", true);
                } else {

                    $tipo                  = $tipo_cuenta[$datos->tipo_cuenta];
                    $clase                 = $clase_cuenta[$datos->clase_cuenta];
                    $codigo_contable       = str_replace(";","",$datos->codigo_contable);
                    $descripcion           = str_replace(";","",$datos->descripcion);
                    $codigo_contable_padre = str_replace(";","",$datos->codigo_contable_padre);
                    $anexo                 = str_replace(";","",$datos->codigo_anexo_contable);
                    $contenido      = "\"$codigo_contable\";\"$descripcion\";\"$codigo_contable_padre\";$sentido;\"$anexo\";$datos->maneja_tercero;$tasa1;$tasa2;\"$tipo\";\"$clase\"\n";
                    $guardarArchivo = fwrite($archivo,$contenido);
                }
            }
        }
    }
    // Enviar datos con la respuesta del proceso al script que originó la petición

    $respuesta    = array();
    if ($datos_incompletos){
        $respuesta[0] = $error;
        $respuesta[1] = $mensaje;
    } else if($genero_archivo) {
        if ($imprime_pdf){
            $archivo->Output($nombreArchivo, "F");
        } else {
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

        $error        = false;
        $mensaje      = $textos["MENSAJE_EXITO"];
        $respuesta[0] = $error;
        $respuesta[1] = $mensaje;
        $respuesta[2] = $ruta_archivo;
    } else {
        $error = true;
        $mensaje = $textos["MENSAJE_NO_GENERA_PDF"];
        $respuesta[0] = $error;
        $respuesta[1] = $mensaje;
    }
    HTTP::enviarJSON($respuesta);
}
?>

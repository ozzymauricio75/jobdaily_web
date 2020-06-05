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

$tipo_contratacion = array(
    ""  => "",
    "1" => $textos["INTEGRAL"],
    "2" => $textos["DESTAJO"],
    "3" => $textos["PRACTICANTE"],
    "4" => $textos["PASANTIAS"],
    "5" => $textos["PRESTACION_SERVICIOS"],
    "6" => $textos["COOPERATIVA_TRABAJO_ASOCIADO"],
    "7"  => $textos["BASICO_MENOR_MINIMO"],
    "8"  => $textos["BASICO_MAYOR_MINIMO"],
    "9"  => $textos["COMISION_CON_BASICO"],
    "10" => $textos["COMISION_SIN_BASICO"],
);

$tipo_planta = array(
    ""   => "",
    "1"  => $textos["INTEGRAL"],
    "7"  => $textos["BASICO_MENOR_MINIMO"],
    "8"  => $textos["BASICO_MAYOR_MINIMO"],
    "9"  => $textos["COMISION_CON_BASICO"],
    "10" => $textos["COMISION_SIN_BASICO"],
);

if(isset($url_verificarTipo))
{
    if($url_tipo== 3 || $url_tipo== ""){
    $tipo_salario = $tipo_contratacion;
    }else{
        $tipo_salario = $tipo_planta;
    }

    HTTP::enviarJSON($tipo_salario);
    exit;
}

// Generar el formulario para la captura de datos
else if (!empty($url_generar)) {
    $error  = "";
    $titulo = $componente->nombre;

    $tipo_listado = array(
        "1" => $textos["ARCHIVO_PDF"],
        "2" => $textos["ARCHIVO_PLANO"]
    );

    $termino_contrato = array(
        ""  => "",
        "1" => $textos["TERMINO_FIJO"],
        "2" => $textos["TERMINO_INDEFINIDO"],
        "3" => $textos["SIN_RELACION_LABORAL"],
        "4" => $textos["OBRA_LABOR"]
    );

    // Definicion de pestana general
    $formularios["PESTANA_GENERAL"] = array(
        array(

            HTML::listaSeleccionSimple("termino_contrato", $textos["TERMINO_CONTRATO"],$termino_contrato,0, array("title" => $textos["AYUDA_TERMINO_CONTRATO"],"onchange" => "cargarTipos(this);"))
        ),
        array(
            HTML::listaSeleccionSimple("tipo_contratacion", $textos["TIPO_CONTRATACION"], $tipo_contratacion, 0, array("title" => $textos["AYUDA_TIPO_CONTRATACION"]))
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

    //=============== INICIO CONDICION ===============//
    if($forma_termino_contrato != ""){
        $condicion .= " AND termino_contrato = '".$forma_termino_contrato."'";
    }
    if($forma_tipo_contratacion != ""){
        $condicion .= " AND tipo_contratacion = '".$forma_tipo_contratacion."'";
    }
    //================ FIN  CONDICION ================//

    $tipo_listado = (int)$forma_tipo_listado;

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

        $archivo                 = new PDF("P","mm","Letter");
        $archivo->textoCabecera  = $textos["FECHA"].": ".date("Y-m-d");
        $archivo->textoTitulo    = $textos["REPORTE_CONTRATOS"];
        $archivo->textoPiePagina = "";
        $archivo->AddPage();

        $archivo->SetFont('Arial','B',6);
        $tituloColumnas = array($textos["CODIGO"],$textos["DESCRIPCION_TIPO_CONTRATO"],$textos["TERMINO_CONTRATO"],$textos["TIPO_CONTRATACION"],$textos["AJUSTA_MINIMO"]);
        $anchoColumnas  = array(20,40,40,50,25);
        $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
        $archivo->Ln(4);
    } else {
        $archivo       = fopen($nombreArchivo,"a+");
        $titulos_plano = "\"".$textos["CODIGO"]."\";\"".$textos["DESCRIPCION_TIPO_CONTRATO"]."\";\"".$textos["TERMINO_CONTRATO"]."\";\"".$textos["TIPO_CONTRATACION"]."\";\"".$textos["AJUSTA_MINIMO"]."\"\n";//."\";\"".
        fwrite($archivo, $titulos_plano);
    }

    $consulta = SQL::seleccionar(array("tipos_contrato"),array("*"),$condicion);

    $i = 0;

    if(SQL::filasDevueltas($consulta)){

        while($datos = SQL::filaEnObjeto($consulta)){

        if($datos->termino_contrato == 1){
        $termino_contrato = $textos["TERMINO_FIJO"];
        }else if($datos->termino_contrato == 2){
            $termino_contrato = $textos["TERMINO_INDEFINIDO"];
        }else if($datos->termino_contrato == 3){
            $termino_contrato = $textos["SIN_RELACION_LABORAL"];
        }else if($datos->termino_contrato == 4){
            $termino_contrato = $textos["OBRA_LABOR"];
        }

        if($datos->sueldo_ajusta_minimo == 1){
            $ajusta_minimo = $textos["AJUSTA_MINIMO_SI"];
        }else{
            $ajusta_minimo = $textos["AJUSTA_MINIMO_NO"];
        }

            if($tipo_listado == 1){

                if($archivo->breakCell(5)){
                    $archivo->AddPage();
                    $archivo->SetFont('Arial','B',6);
                    $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
                    $archivo->Ln(4);
                }

                if($archivo->FillColor != sprintf('%.3F %.3F %.3F rg',1,1,1)){
                    $archivo->SetFillColor(255,255,255);
                }else{
                    $archivo->SetFillColor(240,240,240);
                }

                $archivo->SetFont('Arial','',6);
                $archivo->Cell(20, 4, $datos->codigo, 0, 0, "C",true,"",true);
                $archivo->Cell(40, 4, $datos->descripcion, 0, 0, "L",true,"",true);
                $archivo->Cell(40, 4, $termino_contrato, 0, 0, "L",true,"",true);
                $archivo->Cell(50, 4, $tipo_contratacion[$datos->tipo_contratacion], 0, 0, "L",true,"",true);
                $archivo->Cell(25, 4, $ajusta_minimo, 0, 0, "C",true,"",true);
                $archivo->Ln(4);
            }else{
                $contenido = "\"".$datos->codigo."\";\"".$datos->descripcion."\";\"".$termino_contrato."\";\"".$tipo_contratacion[$datos->tipo_contratacion]."\";\"".$ajusta_minimo."\"\n";
                $guardarArchivo = fwrite($archivo,$contenido);
            }
            $i++;
        }
    }

    if($i > 0 && !$error){
        if($tipo_listado == 1){
            $archivo->Output($nombreArchivo, "F");
        }else{
            fclose($archivo);
        }

        $consecutivo = SQL::obtenerValor("archivos","MAX(consecutivo)","codigo_sucursal='".$sesion_sucursal."'");
        if ($consecutivo){
            $consecutivo++;
        } else {
            $consecutivo = 1;
        }
        $consecutivo = (int)$consecutivo;

        $datos_archivo = array(
            "codigo_sucursal" => $sesion_sucursal,
            "consecutivo"     => $consecutivo,
            "nombre"          => $nombre
        );
        SQL::insertar("archivos", $datos_archivo);
        $id_archivo   = $sesion_sucursal."|".$consecutivo;
        $ruta_archivo = HTTP::generarURL("DESCARCH")."&id=".$id_archivo."&temporal=1";
    }else if(!$error){
        $error        = true;
        $mensaje      = $textos["ERROR_GENERAR_ARCHIVO"];
        $ruta_archivo = "";
    }

    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    $respuesta[2] = $ruta_archivo;

    HTTP::enviarJSON($respuesta);
}
?>


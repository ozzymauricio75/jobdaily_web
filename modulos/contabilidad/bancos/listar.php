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

if(isset($url_completar)){
    if (($url_item) == "selector1") {
        echo SQL::datosAutoCompletar("seleccion_municipios", $url_q);
    }
    exit();

// Generar el formulario para la captura de datos
}else if (!empty($url_generar)) {
    $error  = "";
    $titulo = $componente->nombre;

    $tipo_listado = array(
        "1" => $textos["ARCHIVO_PDF"],
        "2" => $textos["ARCHIVO_PLANO"]
    );

    $bancos = array();

    $consultaColumnas = SQL::seleccionar(array("bancos"),array("*"),"");
    while($datosColumnas = SQL::filaEnObjeto($consultaColumnas)){
        $bancos[$datosColumnas->codigo] = $datosColumnas->descripcion;
    }

    // Definicion de pestana general
    $formularios["PESTANA_GENERAL"] = array(
        array(
            HTML::listaSeleccionSimple("codigo_banco",$textos["BANCO"],$bancos,"")
        ),
        array(
            HTML::campoTextoCorto("selector1", $textos["MUNICIPIO_SUCURSAL"], 35, 255,"", array("class" => "autocompletable", "onKeyUp" => "limpiar_oculto_Autocompletable(this,codigo_municipio)"))
           .HTML::campoOculto("codigo_municipio","")
        ),
        array(
            HTML::listaSeleccionSimple("tipo_listado",$textos["TIPO_LISTADO"],$tipo_listado,"",array("title"=>$textos["AYUDA_TIPO_LISTADO"]))
        )
    );

    $botones = array(
        HTML::boton("botonAceptar", $textos["EXPORTAR"], "imprimirItem(0);", "aceptar")
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

    $condicion = "1";

    //=============== INICIO CONDICION ===============//
    if($forma_codigo_municipio != ""){
        $llave_municipio = explode(",",$forma_codigo_municipio);
        $condicion .= " AND codigo_iso = '".$llave_municipio[0]."'";
        $condicion .= " AND codigo_dane_departamento = '".$llave_municipio[1]."'";
        $condicion .= " AND codigo_dane_municipio = '".$llave_municipio[2]."'";
    }
    if((int)$forma_codigo_banco != 0){
        $condicion .= " AND codigo_banco = '".$forma_codigo_banco."'";
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

        $archivo                 = new PDF("L","mm","Legal");
        $archivo->textoCabecera  = $textos["FECHA"].": ".date("Y-m-d");
        $archivo->textoTitulo    = $textos["REPORTE_BANCOS"];
        $archivo->textoPiePagina = "";
        $archivo->AddPage();

        $archivo->SetFont('Arial','B',6);
        $tituloColumnas = array($textos["CODIGO_BANCO"],$textos["NOMBRE_BANCO"],$textos["TERCERO"],$textos["CODIGO_SUCURSAL"],$textos["NOMBRE_SUCURSAL"],$textos["MUNICIPIO_SUCURSAL"],$textos["DIRECCION_SUCURSAL"],$textos["TELEFONO_SUCURSAL"],$textos["CONTACTO"]);
        $anchoColumnas  = array(20,45,50,25,45,55,30,30,36);
        $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
        $archivo->Ln(4);
    } else {
        $archivo       = fopen($nombreArchivo,"a+");
        $titulos_plano = "\"".$textos["CODIGO_BANCO"]."\";\"".$textos["NOMBRE_BANCO"]."\";\"".$textos["TERCERO"]."\";\"".$textos["CODIGO_SUCURSAL"]."\";\"".$textos["NOMBRE_SUCURSAL"]."\";\"".$textos["MUNICIPIO_SUCURSAL"]."\";\"".$textos["DIRECCION_SUCURSAL"]."\";\"".$textos["TELEFONO_SUCURSAL"]."\";\"".$textos["CONTACTO"]."\"\n";
        fwrite($archivo, $titulos_plano);
    }

    $consulta = SQL::seleccionar(array("seleccion_bancos_sucursales"),array("*"),$condicion);

    $i = 0;

    if(SQL::filasDevueltas($consulta)){

        while($datos = SQL::filaEnObjeto($consulta)){

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
                $archivo->Cell(20, 4, $datos->codigo_banco, 0, 0, "C",true,"",true);
                $archivo->Cell(45, 4, $datos->nombre_banco, 0, 0, "L",true,"",true);
                $archivo->Cell(50, 4, $datos->nombre_tercero, 0, 0, "L",true,"",true);
                $archivo->Cell(25, 4, $datos->codigo_sucursal, 0, 0, "C",true,"",true);
                $archivo->Cell(45, 4, $datos->nombre_sucursal, 0, 0, "L",true,"",true);
                $archivo->Cell(55, 4, $datos->nombre_municipio, 0, 0, "L",true,"",true);
                $archivo->Cell(30, 4, $datos->direccion, 0, 0, "L",true,"",true);
                $archivo->Cell(30, 4, $datos->telefono, 0, 0, "L",true,"",true);
                $archivo->Cell(36, 4, $datos->contacto, 0, 0, "L",true,"",true);
                $archivo->Ln(4);
            }else{

                $datos->nombre_banco     = str_replace(";","",$datos->nombre_banco);
                $datos->nombre_tercero   = str_replace(";","",$datos->nombre_tercero);
                $datos->nombre_sucursal  = str_replace(";","",$datos->nombre_sucursal);
                $datos->nombre_municipio = str_replace(";","",$datos->nombre_municipio);
                $datos->direccion        = str_replace(";","",$datos->direccion);
                $datos->telefono         = str_replace(";","",$datos->telefono);
                $datos->contacto         = str_replace(";","",$datos->contacto);

                $contenido = "\"".$datos->codigo_banco."\";\"".$datos->nombre_banco."\";\"".$datos->nombre_tercero."\";\"".$datos->codigo_sucursal."\";\"".$datos->nombre_sucursal."\";\"".$datos->nombre_municipio."\";\"".$datos->direccion."\";\"".$datos->telefono."\";\"".$datos->contacto."\"\n";
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


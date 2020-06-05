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
            HTML::boton("botonSeleccionarTodos", $textos["SELECCIONAR_TODOS"], "seleccionarTodo();", "", array())
        ),
        array(
            HTML::marcaChequeo("cesantias", $textos["CESANTIAS"],1,false,array("class" => "parafiscales"))
        ),
        array(
            HTML::marcaChequeo("caja", $textos["CAJA"],1,false,array("class" => "parafiscales"))
        ),
        array(
            HTML::marcaChequeo("pension", $textos["PENSION"],1,false,array("class" => "parafiscales"))
        ),
        array(
            HTML::marcaChequeo("salud", $textos["SALUD"],1,false,array("class" => "parafiscales"))
        ),
        array(
            HTML::marcaChequeo("sena", $textos["SENA"],1,false,array("class" => "parafiscales"))
        ),
        array(
            HTML::marcaChequeo("icbf", $textos["ICBF"],1,false,array("class" => "parafiscales"))
        ),
        array(
            HTML::marcaChequeo("riesgos_profesionales", $textos["RIESGOS_PROFESIONALES"],1,false,array("class" => "parafiscales"))
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

    $primeraCondicion = true;

    if(isset($forma_cesantias)){
        $condicion .= " AND (cesantias = '1'";
        $primeraCondicion = false;
    }
    if(isset($forma_caja)){
        if($primeraCondicion){
            $condicion .= " AND (caja = '1'";
        }else{
            $condicion .= " OR caja = '1'";
        }
        $primeraCondicion = false;
    }
    if(isset($forma_pension)){
        if($primeraCondicion){
            $condicion .= " AND (pension = '1'";
        }else{
            $condicion .= " OR pension = '1'";
        }
        $primeraCondicion = false;
    }
    if(isset($forma_salud)){
        if($primeraCondicion){
            $condicion .= " AND (salud = '1'";
        }else{
            $condicion .= " OR salud = '1'";
        }
        $primeraCondicion = false;
    }
    if(isset($forma_sena)){
        if($primeraCondicion){
            $condicion .= " AND (sena = '1'";
        }else{
            $condicion .= " OR sena = '1'";
        }
        $primeraCondicion = false;
    }
    if(isset($forma_icbf)){
        if($primeraCondicion){
            $condicion .= " AND (icbf = '1'";
        }else{
            $condicion .= " OR icbf = '1'";
        }
        $primeraCondicion = false;
    }
    if(isset($forma_riesgos_profesionales)){
        if($primeraCondicion){
            $condicion .= " AND (riesgos_profesionales = '1'";
        }else{
            $condicion .= " OR riesgos_profesionales = '1'";
        }
        $primeraCondicion = false;
    }
    //================ FIN  CONDICION ================//

    if($primeraCondicion){
        $error   = true;
        $mensaje = $textos["ERROR_NO_SELECCIONO"];
    }else{

        $condicion .= ")";

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
            $archivo->textoTitulo    = $textos[""];
            $archivo->textoPiePagina = "";
            $archivo->AddPage();

            $archivo->SetFont('Arial','B',6);
            $tituloColumnas = array($textos["CODIGO"],$textos["CODIGO_RUAF"],$textos["NOMBRE"],$textos["NOMBRE_TERCERO"],$textos["SALUD"],$textos["PENSION"],$textos["ARP"],$textos["CESANTIAS"],$textos["CAJA_COMP"],$textos["SENA"],$textos["ICBF"]);
            $anchoColumnas  = array(25,25,125,55,15,15,15,15,15,15,15);
            $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
            $archivo->Ln(4);
        } else {
            $archivo       = fopen($nombreArchivo,"a+");
            $titulos_plano = "\"".$textos["CODIGO"]."\";\"".$textos["CODIGO_RUAF"]."\";\"".$textos["NOMBRE"]."\";\"".$textos["NOMBRE_TERCERO"]."\";\"".$textos["SALUD"]."\";\"".$textos["PENSION"]."\";\"".$textos["ARP"]."\";\"".$textos["CESANTIAS"]."\";\"".$textos["CAJA_COMP"]."\";\"".$textos["SENA"]."\";\"".$textos["ICBF"]."\"\n";
            fwrite($archivo, $titulos_plano);
        }

        $consulta = SQL::seleccionar(array("entidades_parafiscales"),array("*"),$condicion);

        $i = 0;

        if(SQL::filasDevueltas($consulta)){

            while($datos = SQL::filaEnObjeto($consulta)){

                $tercero = SQL::obtenerValor("seleccion_terceros","SUBSTRING_INDEX(nombre,'|',1)","id = '".$datos->documento_identidad_tercero."'");

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
                    $archivo->Cell(25, 4, $datos->codigo, 0, 0, "C",true,"",true);
                    $archivo->Cell(25, 4, $datos->codigo_ruaf, 0, 0, "L",true,"",true);
                    $archivo->Cell(125, 4, $datos->nombre, 0, 0, "L",true,"",true);
                    $archivo->Cell(55, 4, $tercero, 0, 0, "L",true,"",true);
                    $archivo->Cell(15, 4, $textos["SI_NO_".$datos->salud], 0, 0, "C",true,"",true);
                    $archivo->Cell(15, 4, $textos["SI_NO_".$datos->pension], 0, 0, "C",true,"",true);
                    $archivo->Cell(15, 4, $textos["SI_NO_".$datos->riesgos_profesionales], 0, 0, "C",true,"",true);
                    $archivo->Cell(15, 4, $textos["SI_NO_".$datos->cesantias], 0, 0, "C",true,"",true);
                    $archivo->Cell(15, 4, $textos["SI_NO_".$datos->caja], 0, 0, "C",true,"",true);
                    $archivo->Cell(15, 4, $textos["SI_NO_".$datos->sena], 0, 0, "C",true,"",true);
                    $archivo->Cell(15, 4, $textos["SI_NO_".$datos->icbf], 0, 0, "C",true,"",true);
                    $archivo->Ln(4);
                }else{

                    $datos->nombre = str_replace(";","",$datos->nombre);
                    $tercero       = str_replace(";","",$tercero);

                    $contenido = "\"".$datos->codigo."\";\"".$datos->codigo_ruaf."\";\"".$datos->nombre."\";\"". $tercero."\";\"".$textos["SI_NO_".$datos->salud]."\";\"".$textos["SI_NO_".$datos->pension]."\";\"".$textos["SI_NO_".$datos->riesgos_profesionales]."\";\"".$textos["SI_NO_".$datos->cesantias]."\";\"".$textos["SI_NO_".$datos->caja]."\";\"".$textos["SI_NO_".$datos->sena]."\";\"".$textos["SI_NO_".$datos->icbf]."\"\n";
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
    }

    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    $respuesta[2] = $ruta_archivo;

    HTTP::enviarJSON($respuesta);
}
?>


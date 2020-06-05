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

if (!empty($url_recargar)) {
    $datos    = array();
    $consulta = SQL::seleccionar(array("departamentos"), array("codigo_dane_departamento","nombre"), "codigo_iso = '".$url_origen."' OR codigo_dane_departamento = ''", "", "codigo_dane_departamento");

    while ($fila = SQL::filaEnObjeto($consulta)) {
        $datos[$fila->codigo_dane_departamento] = $fila->nombre;
    }

    HTTP::enviarJSON($datos);
}

// Generar el formulario para la captura de datos
if (!empty($url_generar)) {
    $error  = "";
    $titulo = $componente->nombre;

    $tipo_listado = array(
        "1" => $textos["ARCHIVO_PDF"],
        "2" => $textos["ARCHIVO_PLANO"]
    );

    $paises        = HTML::generarDatosLista("paises", "codigo_iso", "nombre","");
    $departamentos = HTML::generarDatosLista("departamentos", "codigo_dane_departamento", "nombre", "codigo_dane_departamento = ''");

    // Definicion de pestana general
    $formularios["PESTANA_GENERAL"] = array(
        array(
            HTML::listaSeleccionSimple("tipo_listado",$textos["TIPO_LISTADO"],$tipo_listado,"",array("title"=>$textos["AYUDA_TIPO_LISTADO"]))
        ),
        array(
            HTML::listaSeleccionSimple("pais", $textos["PAIS"], $paises,"", array("onChange" => "recargarLista('pais','departamento','');"))
        ),
        array(
            HTML::listaSeleccionSimple("departamento", $textos["DEPARTAMENTO"], $departamentos,"")
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

    $condicion = "codigo_dane_municipio != ''";

    //=============== INICIO CONDICION ===============//
    if($forma_pais != ""){
        $condicion .= " AND codigo_iso = '".$forma_pais."'";
    }
    if($forma_departamento != ""){
        $condicion .= " AND codigo_dane_departamento = '".$forma_departamento."'";
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

        $archivo                 = new PDF("L","mm","Letter");
        $archivo->textoCabecera  = $textos["FECHA"].": ".date("Y-m-d");
        $archivo->textoTitulo    = $textos[""];
        $archivo->textoPiePagina = $textos["ELABORADO_POR"].": ".SQL::obtenerValor("usuarios","nombre","codigo = '".$sesion_codigo_usuario."'");
        $archivo->AddPage();

        $archivo->SetFont('Arial','B',6);
        $tituloColumnas = array($textos["CODIGO_DANE"],$textos["CODIGO_INTERNO"],$textos["NOMBRE"],$textos["PAIS"],$textos["DEPARTAMENTO"],$textos["COMUNAS"],$textos["CAPITAL"]);
        $anchoColumnas  = array(25,25,60,50,40,30,30);
        $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
        $archivo->Ln(4);
    } else {
        $archivo       = fopen($nombreArchivo,"a+");
        $titulos_plano = "\"".$textos["CODIGO_DANE"]."\";\"".$textos["CODIGO_INTERNO"]."\";\"".$textos["NOMBRE"]."\";\"".$textos["PAIS"]."\";\"".$textos["DEPARTAMENTO"]."\";\"".$textos["COMUNAS"]."\";\"".$textos["CAPITAL"]."\"\n";
        fwrite($archivo, $titulos_plano);
    }

    $consulta = SQL::seleccionar(array("municipios"),array("*"),$condicion);

    $i = 0;

    if(SQL::filasDevueltas($consulta)){

        while($datos = SQL::filaEnObjeto($consulta)){

            $pais         = SQL::obtenerValor("paises","nombre","codigo_iso = '".$datos->codigo_iso."'");
            $departamento = SQL::obtenerValor("departamentos","nombre","codigo_dane_departamento = '".$datos->codigo_dane_departamento."' AND codigo_iso = '".$datos->codigo_iso."'");
            $capital      = $textos["SI_NO_".$datos->capital];

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
                $archivo->Cell(25, 4, $datos->codigo_dane_municipio, 0, 0, "L",true,"",true);
                $archivo->Cell(25, 4, $datos->codigo_interno, 0, 0, "C",true,"",true);
                $archivo->Cell(60, 4, $datos->nombre, 0, 0, "L",true,"",true);
                $archivo->Cell(50, 4, $departamento, 0, 0, "L",true,"",true);
                $archivo->Cell(40, 4, $pais, 0, 0, "L",true,"",true);
                $archivo->Cell(30, 4, $datos->comunas, 0, 0, "R",true,"",true);
                $archivo->Cell(30, 4, $capital, 0, 0, "C",true,"",true);
                $archivo->Ln(4);
            }else{

                $pais         = str_replace(";","",$pais);
                $departamento = str_replace(";","",$departamento);
                $municipio    = str_replace(";","",$datos->nombre);

                $contenido = "\"".$datos->codigo_dane_municipio."\";\"".$datos->codigo_interno."\";\"".$municipio."\";\"".$departamento."\";\"".$pais."\";\"".$datos->comunas."\";\"".$capital."\"\n";
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


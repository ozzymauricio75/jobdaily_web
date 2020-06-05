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

    $lista_usuarios = HTML::generarDatosLista("usuarios","codigo","nombre");

    $fecha_completa = date("Y/m/d")." - ".date("Y/m/d");

    // Definicion de pestana general
    $formularios["PESTANA_GENERAL"] = array(
        array(
            HTML::listaSeleccionSimple("tipo_listado",$textos["TIPO_LISTADO"],$tipo_listado,"",array("title"=>$textos["AYUDA_TIPO_LISTADO"]))
        ),
        array(
            HTML::listaSeleccionSimple("codigo_usuario",$textos["TIPO_LISTADO"],$lista_usuarios,"",array("title"=>$textos["AYUDA_TIPO_LISTADO"]))
        ),
        array(
            HTML::campoTextoCorto("*fechas", $textos["FECHAS"], 25, 25, $fecha_completa, array("title" => $textos["AYUDA_FECHAS"],"class" => "fechaRango"))
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

    $fechas  = explode("-",$forma_fechas);
    $fecha_1 = trim($fechas[0]);
    $fecha_2 = trim($fechas[1]);
    $fecha_1 = str_replace("/","-",$fecha_1)." 00:00:00";
    $fecha_2 = str_replace("/","-",$fecha_2)." 23:59:59";

    //=============== INICIO CONDICION ===============//
    $condicion .= " AND codigo_usuario_conexion = '".$forma_codigo_usuario."'";
    $condicion .= " AND (fecha_conexion BETWEEN '".$fecha_1."' AND '".$fecha_2."')";
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

        $archivo                 = new PDF("L","mm","legal");
        $archivo->textoCabecera  = $textos["FECHA"].": ".date("Y-m-d");
        $archivo->textoTitulo    = $textos["LISTBITA"];
        $archivo->textoPiePagina = "";
        $archivo->AddPage();

        $archivo->SetFont('Arial','B',6);
        $tituloColumnas = array($textos["SUCURSAL"],$textos["USUARIO"],$textos["FECHA"],$textos["HORA"],$textos["COMPONENTE"],$textos["CONSULTA"],$textos["MENSAJE"]);
        $anchoColumnas  = array(25,25,20,15,25,140,85);
        $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
        $archivo->Ln(4);
    } else {
        $archivo       = fopen($nombreArchivo,"a+");
        $titulos_plano = "\"".$textos["SUCURSAL"]."\";\"".$textos["USUARIO"]."\";\"".$textos["FECHA"]."\";\"".$textos["HORA"]."\";\"".$textos["COMPONENTE"]."\";\"".$textos["CONSULTA"]."\";\"".$textos["MENSAJE"]."\"\n";
        fwrite($archivo, $titulos_plano);
    }

    $consulta = SQL::seleccionar(array("bitacora"),array("*"),$condicion,"","fecha_conexion");

    $i = 0;

    if(SQL::filasDevueltas($consulta)){
        while($datos = SQL::filaEnObjeto($consulta)){
            $tiempo = explode(" ",$datos->fecha_operacion);
            $fecha  = trim($tiempo[0]);
            $hora   = trim($tiempo[1]);

            $sucursal = SQL::obtenerValor("sucursales","nombre","codigo = '".$datos->codigo_sucursal_conexion."'");
            $usuario  = SQL::obtenerValor("usuarios","nombre","codigo = '".$datos->codigo_usuario_conexion."'");

            if($tipo_listado == 1){
                if($archivo->FillColor != sprintf('%.3F %.3F %.3F rg',1,1,1)){
                    $archivo->SetFillColor(255,255,255);
                }else{
                    $archivo->SetFillColor(240,240,240);
                }
                $ancho  = array(25,25,20,15,25,140,85);
                $texto  = array($sucursal,$usuario,$fecha,$hora,$datos->componente,$datos->consulta,$datos->mensaje);
                $aligns = array("L","L","C","C","L","L","L");

                $archivo->SetFont('Arial','',6);
                $archivo->SetAligns($aligns);
                $archivo->SetWidths($ancho);
                $archivo->Row($texto,true,$tituloColumnas, $anchoColumnas);
            }else{
                $sucursal      = str_replace(";","",$sucursal);
                $usuario       = str_replace(";","",$usuario);
                $componentePDF = str_replace(";","",$datos->componente);
                $sentencia     = str_replace(";","",$datos->consulta);
                $mensajeError  = str_replace(";","",$datos->mensaje);

                $contenido = "\"".$sucursal."\";\"".$usuario."\";\"".$fecha."\";\"".$hora."\";\"".$componentePDF."\";\"".$sentencia."\";\"".$mensajeError."\"\n";
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

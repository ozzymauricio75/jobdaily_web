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
if(isset($url_recargar_sucursales) && isset($url_id_banco)){

    $lista = HTML::generarDatosLista("seleccion_sucursales_bancos","id","nombre_sucursal","codigo_banco = '".$url_id_banco."'");

    HTTP::enviarJSON($lista);
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

    $bancos            = HTML::generarDatosLista("bancos", "codigo", "descripcion");
    $listar_sucursales = array("0" => "");

    // Definicion de pestana general
    $formularios["PESTANA_GENERAL"] = array(
        array(
            HTML::listaSeleccionSimple("codigo_sucursal", $textos["SUCURSAL"], HTML::generarDatosLista("sucursales", "codigo", "nombre"), "")
        ),
        array(
            HTML::listaSeleccionSimple("codigo_banco", $textos["BANCO"], $bancos, "",array("onChange" => "verificarSucursalesListado();"))
        ),
        array(
            HTML::listaSeleccionSimple("codigo_sucursal_banco", $textos["SUCURSALES_BANCOS"], $listar_sucursales, "")
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
    if((int)$forma_codigo_sucursal != 0){
        $condicion .= " AND codigo_almacen = '".$forma_codigo_sucursal."'";
    }
    if((int)$forma_codigo_banco != 0){
        $condicion .= " AND codigo_banco = '".$forma_codigo_banco."'";
    }
    if((int)$forma_codigo_sucursal_banco != 0){
        $llave = explode("|",$forma_codigo_sucursal_banco);
        $condicion .= " AND codigo_sucursal = '".$llave[0]."'";
        $condicion .= " AND codigo_banco = '".$llave[1]."'";
        $condicion .= " AND codigo_iso = '".$llave[2]."'";
        $condicion .= " AND codigo_dane_departamento = '".$llave[3]."'";
        $condicion .= " AND codigo_dane_municipio = '".$llave[4]."'";
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
        $archivo->textoTitulo    = $textos["REPORTE_CUBA"];
        $archivo->textoPiePagina = "";
        $archivo->AddPage();

        $archivo->SetFont('Arial','B',6);
        $tituloColumnas = array($textos["SUCURSAL"],$textos["BANCO"],$textos["SUCURSALES_BANCOS"],$textos["NUMERO"],$textos["TIPO_DOCUMENTO"],$textos["PLAN_CONTABLE"],$textos["AUXILIAR_CONTABLE"],$textos["ESTADO"]);
        $anchoColumnas  = array(30,35,40,30,30,45,30,20);
        $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
        $archivo->Ln(4);
    } else {
        $archivo       = fopen($nombreArchivo,"a+");
        $titulos_plano = "\"".$textos["SUCURSAL"]."\";\"".$textos["BANCO"]."\";\"".$textos["SUCURSALES_BANCOS"]."\";\"".$textos["NUMERO"]."\";\"".$textos["TIPO_DOCUMENTO"]."\";\"".$textos["PLAN_CONTABLE"]."\";\"".$textos["AUXILIAR_CONTABLE"]."\";\"".$textos["ESTADO"]."\"\n";
        fwrite($archivo, $titulos_plano);
    }

    $consulta = SQL::seleccionar(array("listado_cuentas_bancarias"),array("*"),$condicion);

    $i = 0;

    if(SQL::filasDevueltas($consulta)){

        while($datos = SQL::filaEnObjeto($consulta)){

            $estado = $datos->estado == '1' ? $textos["ACTIVA"] : $textos["INACTIVA"];

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
                $archivo->Cell(30, 4, $datos->nombre_almacen, 0, 0, "L",true,"",true);
                $archivo->Cell(35, 4, $datos->nombre_banco, 0, 0, "L",true,"",true);
                $archivo->Cell(40, 4, $datos->nombre_sucursal, 0, 0, "L",true,"",true);
                $archivo->Cell(30, 4, $datos->numero, 0, 0, "L",true,"",true);
                $archivo->Cell(30, 4, $datos->tipo_documento, 0, 0, "L",true,"",true);
                $archivo->Cell(45, 4, $datos->cuenta, 0, 0, "L",true,"",true);
                $archivo->Cell(30, 4, $datos->auxiliar, 0, 0, "L",true,"",true);
                $archivo->Cell(20, 4, $estado, 0, 0, "L",true,"",true);
                $archivo->Ln(4);
            }else{
                $datos->nombre_almacen  = str_replace(";","",$datos->nombre_almacen);
                $datos->nombre_banco    = str_replace(";","",$datos->nombre_banco);
                $datos->nombre_sucursal = str_replace(";","",$datos->nombre_sucursal);
                $datos->numero          = str_replace(";","",$datos->numero);
                $datos->tipo_documento  = str_replace(";","",$datos->tipo_documento);
                $datos->cuenta          = str_replace(";","",$datos->cuenta);
                $datos->auxiliar        = str_replace(";","",$datos->auxiliar);

                $contenido = "\"".$datos->nombre_almacen."\";\"".$datos->nombre_banco."\";\"".$datos->nombre_sucursal."\";\"".$datos->numero."\";\"".$datos->tipo_documento."\";\"".$datos->cuenta."\";\"".$datos->auxiliar."\";\"".$estado."\"\n";
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


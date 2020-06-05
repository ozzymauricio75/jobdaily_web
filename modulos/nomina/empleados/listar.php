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
if(!empty($url_recargar)){
    $respuesta = HTML::generarDatosLista("sucursales", "codigo", "nombre", "codigo_empresa = '".$url_origen."' OR codigo = 0");
    HTTP::enviarJSON($respuesta);
}
else if (!empty($url_generar)) {

    $error  = "";
    $titulo = $componente->nombre;

    $tipo_listado = array(
        "1" => $textos["ARCHIVO_PDF"],
        "2" => $textos["ARCHIVO_PLANO"]
    );

    $empresas       = HTML::generarDatosLista("empresas", "codigo", "razon_social", "");
    $sucursales     = HTML::generarDatosLista("sucursales", "codigo", "nombre_corto", "");
    $departamentos  = HTML::generarDatosLista("departamentos_empresa", "codigo", "nombre","");
    $tipos_contrato = HTML::generarDatosLista("tipos_contrato", "codigo", "descripcion", "");

    // Definicion de pestana general
    $formularios["PESTANA_GENERAL"] = array(
        array(
            HTML::listaSeleccionSimple("codigo_empresa", $textos["EMPRESA"], $empresas, "",array("onChange" => "recargarLista('codigo_empresa','codigo_sucursal');")),
        ),
        array(
            HTML::listaSeleccionSimple("codigo_sucursal", $textos["SUCURSAL_LABORA"], $sucursales, "")
        ),
        array(
            HTML::listaSeleccionSimple("codigo_departamento", $textos["DEPARTAMENTOS"], $departamentos, ""),
        ),
        array(
            HTML::listaSeleccionSimple("codigo_tipo_contrato", $textos["TIPO_CONTRATO"], $tipos_contrato, "")
        ),
        array(
            HTML::mostrarDato("fechas_ingreso",$textos["FECHA_INGRESO"],HTML::campoTextoCorto("fechas_ingreso", $textos["DESDE_HASTA"], 19, 25,"", array("class" => "fechaRango"))),
            HTML::mostrarDato("fechas_nacimiento",$textos["FECHA_NACIMIENTO"],HTML::campoTextoCorto("fechas_nacimiento", $textos["DESDE_HASTA"], 19, 25,"", array("class" => "fechaRango")))
        ),
        array(
            HTML::listaSeleccionSimple("tipo_listado",$textos["TIPO_LISTADO"],$tipo_listado,"",array("title"=>$textos["AYUDA_TIPO_LISTADO"])),
            HTML::marcaChequeo("muestra_salario", $textos["MOSTRAR_SALARIO"], 1, false)
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
    $tipo_listado = (int)$forma_tipo_listado;

    // ====================== Inicio de condiciones ====================== //

    if((int)$forma_codigo_empresa != 0){
        $condicion .= " AND codigo_empresa = '".$forma_codigo_empresa."'";
    }

    if((int)$forma_codigo_sucursal != 0){
        $condicion .= " AND codigo_sucursal_activo = '".$forma_codigo_sucursal."'";
    }

    if((int)$forma_codigo_departamento != 0){
        $condicion .= " AND codigo_departamento_empresa = '".$forma_codigo_departamento."'";
    }

    if((int)$forma_codigo_tipo_contrato != 0){
        $condicion .= " AND codigo_tipo_contrato = '".$forma_codigo_tipo_contrato."'";
    }

    if($forma_fechas_ingreso != ""){
        $fechas = explode("-",$forma_fechas_ingreso);
        $fecha1 = trim($fechas[0]);
        $fecha2 = trim($fechas[1]);
        $fecha1 = str_replace("/","-",$fecha1);
        $fecha2 = str_replace("/","-",$fecha2);
        $condicion .= " AND (fecha_ingreso BETWEEN '".$fecha1."' AND '".$fecha2."')";
    }

    if($forma_fechas_nacimiento != ""){
        $fechas = explode("-",$forma_fechas_nacimiento);
        $fecha1 = trim($fechas[0]);
        $fecha2 = trim($fechas[1]);
        $fecha1 = str_replace("/","-",$fecha1);
        $fecha2 = str_replace("/","-",$fecha2);
        $condicion .= " AND (fecha_nacimiento BETWEEN '".$fecha1."' AND '".$fecha2."')";
    }

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

    if ($tipo_listado == 1){
        $archivo                 = new PDF("L","mm","Legal");
        $archivo->textoTitulo    = $textos["LISTINEM"];
        $archivo->textoCabecera  = $textos["FECHA"].": ".date("Y-m-d");
        $archivo->textoPiePagina = "";
        $archivo->AddPage();

        if(isset($forma_muestra_salario)){
            $tituloColumnas = array($textos["FECHA_INICIA"],$textos["EMPRESA"], $textos["FECHA_INICIA_SUCURSAL"], $textos["SUCURSAL_LABORA"], $textos["NUMERO_DOCUMENTO"], $textos["NOMBRE_COMPLETO"], $textos["TIPO_CONTRATO"], $textos["DEPARTAMENTOS"], $textos["SECCION"], $textos["FECHA_NACIMIENTO"],$textos["SALARIOS"]);
            $anchoColumnas  = array(20,35,20,30,30,55,30,35,35,22,24);
        }else{
            $tituloColumnas = array($textos["FECHA_INICIA"],$textos["EMPRESA"], $textos["FECHA_INICIA_SUCURSAL"], $textos["SUCURSAL_LABORA"], $textos["NUMERO_DOCUMENTO"], $textos["NOMBRE_COMPLETO"], $textos["TIPO_CONTRATO"], $textos["DEPARTAMENTOS"], $textos["SECCION"], $textos["FECHA_NACIMIENTO"]);
            $anchoColumnas  = array(20,35,20,30,30,55,30,35,35,22);
        }

        $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
        $archivo->Ln(4);
    } else {
        $archivo       = fopen($nombreArchivo,"a+");
        if(isset($forma_muestra_salario)){
            $titulos_plano = "\"".$textos["FECHA_INICIA"]."\";\"".$textos["EMPRESA"]."\";\"".$textos["FECHA_INICIA_SUCURSAL"]."\";\"".$textos["SUCURSAL_LABORA"]."\";\"".$textos["NUMERO_DOCUMENTO"]."\";\"".$textos["NOMBRE_COMPLETO"]."\";\"".$textos["TIPO_CONTRATO"]."\";\"".$textos["DEPARTAMENTOS"]."\";\"".$textos["SECCION"]."\";\"".$textos["FECHA_NACIMIENTO"]."\";\"".$textos["SALARIOS"]."\"\n";
        }else{
            $titulos_plano = "\"".$textos["FECHA_INICIA"]."\";\"".$textos["EMPRESA"]."\";\"".$textos["FECHA_INICIA_SUCURSAL"]."\";\"".$textos["SUCURSAL_LABORA"]."\";\"".$textos["NUMERO_DOCUMENTO"]."\";\"".$textos["NOMBRE_COMPLETO"]."\";\"".$textos["TIPO_CONTRATO"]."\";\"".$textos["DEPARTAMENTOS"]."\";\"".$textos["SECCION"]."\";\"".$textos["FECHA_NACIMIENTO"]."\"\n";
        }
        fwrite($archivo, $titulos_plano);
    }

    $i=0;

    $consulta = SQL::seleccionar(array("listado_empleados"), array("*"),$condicion,"","nombre_completo");

    if (SQL::filasDevueltas($consulta)) {
        while($datos = SQL::filaEnObjeto($consulta)) {

            if($tipo_listado == 1){
                if($archivo->breakCell(6)){
                    $archivo->AddPage();
                    $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
                    $archivo->Ln(4);
                }

                if($archivo->FillColor != sprintf('%.3F %.3F %.3F rg',1,1,1)){
                    $archivo->SetFillColor(255,255,255);
                }else{
                    $archivo->SetFillColor(240,240,240);
                }

                $archivo->SetFont('Arial',"",7);
                $archivo->Cell(20, 5, $datos->fecha_ingreso, 1, 0, "C", true,"",true);
                $archivo->Cell(35, 5, $datos->razon_social, 1, 0, "L", true,"",true);
                $archivo->Cell(20, 5, $datos->fecha_ingreso_sucursal, 1, 0, "C", true,"",true);
                $archivo->Cell(30, 5, $datos->nombre_sucursal, 1, 0, "L", true,"",true);
                $archivo->Cell(30, 5, $datos->documento_identidad_empleado, 1, 0, "L", true,"",true);
                $archivo->Cell(55, 5, $datos->nombre_completo, 1, 0, "L", true,"",true);
                $archivo->Cell(30, 5, $datos->tipo_contrato, 1, 0, "L", true,"",true);
                $archivo->Cell(35, 5, $datos->departamento_empresa, 1, 0, "L", true,"",true);
                $archivo->Cell(35, 5, $datos->seccion_empresa, 1, 0, "L", true,"",true);
                $archivo->Cell(22, 5, $datos->fecha_nacimiento, 1, 0, "C", true,"",true);
                if(isset($forma_muestra_salario)){
                    $archivo->Cell(24, 5, "$ ".number_format($datos->salario,0), 1, 0, "R", true,"",true);
                }

                $archivo->Ln(5);
            }else{
                $razon_social         = str_replace(";","",$datos->razon_social);
                $nombre_sucursal      = str_replace(";","",$datos->nombre_sucursal);
                $nombre_completo      = str_replace(";","",$datos->nombre_completo);
                $tipo_contrato        = str_replace(";","",$datos->tipo_contrato);
                $departamento_empresa = str_replace(";","",$datos->departamento_empresa);
                $seccion_empresa      = str_replace(";","",$datos->seccion_empresa);

                if(isset($forma_muestra_salario)){
                    $contenido = "\"".$datos->fecha_ingreso."\";\"".$razon_social."\";\"".$datos->fecha_ingreso_sucursal."\";\"".$nombre_sucursal."\";\"".$datos->documento_identidad_empleado."\";\"".$nombre_completo."\";\"".$tipo_contrato."\";\"".$departamento_empresa."\";\"".$seccion_empresa."\";\"".$datos->fecha_nacimiento."\";".$datos->salario."\n";
                }else{
                    $contenido = "\"".$datos->fecha_ingreso."\";\"".$razon_social."\";\"".$datos->fecha_ingreso_sucursal."\";\"".$nombre_sucursal."\";\"".$datos->documento_identidad_empleado."\";\"".$nombre_completo."\";\"".$tipo_contrato."\";\"".$departamento_empresa."\";\"".$seccion_empresa."\";\"".$datos->fecha_nacimiento."\"\n";
                }
                $guardarArchivo = fwrite($archivo,$contenido);
            }

            $i++;
        }
    }

    $cargaPdf = 0;

    if($i>0) {
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
    $respuesta    = array();
    if ($cargaPdf == 1) {
        $respuesta[0] = false;
        $respuesta[1] = $textos["ARCHIVO_GENERADO"];
        $respuesta[2] = $ruta_archivo;
    } else{
        $respuesta[0] = true;
        $respuesta[1] = $textos["ERROR_GENERAR_ARCHIVO"];
        $respuesta[2] = "";
    }
    HTTP::enviarJSON($respuesta);
}
?>

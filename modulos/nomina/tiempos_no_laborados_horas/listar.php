<?php
/**
*
* Copyright (C) 2020 Jobdaily
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
require("clases/clases.php");

if(isset($url_verificar)){
    $condicion_extra = "id_sucursal IN (".$url_sucursales.")";
    echo SQL::datosAutoCompletar("seleccion_empleados", $url_q, $condicion_extra);
    exit;
}

// Generar el formulario para la captura de datos
if (!empty($url_generar)) {

    $error  = "";
    $titulo = $componente->nombre;
    $error_empleados  = false;
    $error_sucursales = false;
    $continuar        = true;

    $empresa   = SQL::obtenerValor("sucursales","codigo_empresa","codigo='".$sesion_sucursal."' AND codigo>0");

    if ($sesion_usuario == $datosGlobales["usuarioMaestro"]){
        $consulta_sucursales = SQL::seleccionar(array("sucursales"), array("codigo, nombre"), "codigo_empresa = '".$empresa."'");
    }else{
        // Obtener lista de sucursales para seleccion
        $tablas     = array(
        "a" => "perfiles_usuario",
        "b" => "componentes_usuario",
        "c" => "sucursales"
        );
        $columnas = array(
        "codigo" => "c.codigo",
        "nombre" => "c.nombre"
        );
        $condicion = "c.codigo = a.codigo_sucursal AND a.id = b.id_perfil
        AND a.codigo_usuario = '".$sesion_codigo_usuario."'
        AND b.id_componente = '".$componente->id."' AND c.codigo_empresa = '".$empresa."'";

        $consulta_sucursales = SQL::seleccionar($tablas, $columnas, $condicion);
    }

    $listaSucursales = array();
    if (SQL::filasDevueltas($consulta_sucursales)){

        $pestana_sucursales   = array();
        if (SQL::filasDevueltas($consulta_sucursales) == 1){
            $chequeo = true;
        } else {
            $pestana_sucursales[] = array(HTML::boton("botonSeleccionarTodos", $textos["SELECCIONAR_TODOS"], "seleccionar_todas_sucursales();", "", array()));
            $chequeo = false;
        }

        while ($datos_sucursales = SQL::filaEnObjeto($consulta_sucursales)){

            $codigo_sucursal = $datos_sucursales->codigo;
            $nombreSucursal  = $datos_sucursales->nombre;

            $listaSucursales[] = $codigo_sucursal;

            $pestana_sucursales[]   = array(
                HTML::marcaChequeo("sucursales[".$datos_sucursales->codigo."]", $datos_sucursales->nombre, $datos_sucursales->codigo, $chequeo, array("title" => $textos["AYUDA_SUCURSAL"], "id" => "sucursales_".$datos_sucursales->codigo, "class" => "sucursales_electrodomesticos"))
            );
        }
        $listaSucursales = implode(",",$listaSucursales);
    } else {
        $error_sucursales = true;
        $continuar = false;
    }

    $consulta_empleados = SQL::seleccionar(array("ingreso_empleados"),array("*"),"documento_identidad_empleado!=''");
    if (!SQL::filasDevueltas($consulta_empleados)){
        $error_empleados = true;
        $continuar = false;
    }

    if ($continuar){

        $orden_empleado =array(
            "1" => $textos["APELLIDO_NOMBRE"],
            "2" => $textos["NOMBRE_APELLIDO"],
            "3" => $textos["CEDULA"]
        );

        $tipo_listado = array(
            "1" => $textos["ARCHIVO_PDF"],
            "2" => $textos["ARCHIVO_PLANO"]
        );
        $formularios["PESTANA_SUCURSALES"] = $pestana_sucursales;

        // Definicion de pestana general
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::campoTextoCorto("*fechas", $textos["DESDE_HASTA"], 19, 25,"", array("class" => "fechaRango"))
            ),
            array(
                HTML::campoTextoCorto("selector1", $textos["EMPLEADO"], 40, 255, "", array("title" => $textos["AYUDA_EMPLEADO"], "onfocus" => "autocompletableListaPlanilla(this);", "onKeyUp" => "limpiar_oculto_Autocompletable(this, documento_identidad)")).
                HTML::campoOculto("documento_identidad",""),
                HTML::campoOculto("listaSucursales",$listaSucursales)
            ),
            array(
                HTML::listaSeleccionSimple("orden_empleado",$textos["ORDEN_EMPLEADO"],$orden_empleado,"",array("title"=>$textos["AYUDA_ORDEN_EMPLEADO"]))
            ),
            array(
                HTML::listaSeleccionSimple("tipo_listado",$textos["TIPO_LISTADO"],$tipo_listado,"",array("title"=>$textos["AYUDA_TIPO_LISTADO"]))
            )
        );

        $botones = array (
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "imprimirItem(1);", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones);

    } else {
        $error = $textos["VERIFICAR_DATOS"];
        if ($error_sucursales){
            $error .= $textos["ERROR_SUCURSALES"];
        }
        if($error_empleados){
            $error = $textos["ERROR_EMPLEADOS"];
        }
        $error = $textos["CREAR_DATOS"];
        $contenido = "";
    }

    // Enviar datos para la generacion del formulario al script que origino la peticion
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

// Exportar los datos
} elseif (!empty($forma_procesar)) {

    $cargaPdf = false;
    $ruta     = "";

    if (!isset($forma_sucursales)){
        $error   = true;
        $mensaje = $textos["ERROR_SUCURSAL_VACIA"];
    } else if (empty($forma_fechas)){
        $error   = true;
        $mensaje = $textos["ERROR_FECHA_VACIA"];
    } else {


        $fechas = explode(" - ",$forma_fechas);
        $fecha1 = trim($fechas[0]);
        $fecha2 = trim($fechas[1]);
        $fecha1 = str_replace("/","-",$fecha1);
        $fecha2 = str_replace("/","-",$fecha2);

        $nombre         = "";
        $nombreArchivo  = "";
        do {
            $cadena         = Cadena::generarCadenaAleatoria(8);
            if ($forma_tipo_listado=="1"){
                $nombre = $sesion_sucursal.$cadena.".pdf";
            } else {
                $nombre = $sesion_sucursal.$cadena.".csv";
            }
            $nombreArchivo  = $rutasGlobales["archivos"]."/".$nombre;
        } while (is_file($nombreArchivo));

        if ($forma_tipo_listado=="1"){
            $archivo                 = new PDF("L","mm","Letter");
            $archivo->textoTitulo    = $textos["REPORTE_HORAS"]." ".$textos["FECHA"]." ".$forma_fechas;
            $archivo->textoCabecera  = $textos["FECHA"].": ".date("Y-m-d H:i:s");
            $archivo->textoPiePagina = "";
        } else {
            $archivo = fopen($nombreArchivo,"a+");
        }

        $i = 0;
        foreach($forma_sucursales AS $codigo_sucursal){

            $condicion = "codigo_sucursal ='$codigo_sucursal' AND fecha_pago_planilla BETWEEN '$fecha1' AND '$fecha2'";
            if($forma_documento_identidad != ""){
                $condicion .= " AND documento_identidad_empleado = '$forma_documento_identidad_tercero'";
            }

            if ($forma_orden_empleado == "1"){
                $orden_empleado = "apellido_empleado";
            } else if ($forma_orden_empleado =="2"){
                $orden_empleado = "nombre_empleado";
            } else {
                $orden_empleado = "documento_identidad_empleado";
            }

            if ($forma_tipo_listado==1){
                $archivo->AddPage();
                $tituloColumnas = array($textos["CEDULA"],$textos["EMPLEADO"],$textos["FECHA_INICIO"],$textos["HORA_INICIO"],$textos["HORA_FIN"],$textos["FECHA_PAGO"],$textos["TRANSACCION"],$textos["CANTIDAD"],$textos["VALOR_MOVIMIENTO"]);
                $anchoColumnas  = array(15,40,20,20,20,20,40,30,30);

                $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
                $archivo->Ln(4);
            } else {
                $tituloColumnas = $textos["CEDULA"].";".$textos["EMPLEADO"].";".$textos["FECHA_INICIO"].";".$textos["HORA_INICIO"].";".$textos["HORA_FIN"].";".$textos["FECHA_PAGO"].";".$textos["TRANSACCION"].";".$textos["CANTIDAD"].";".$textos["VALOR_MOVIMIENTO"]."\n";
                fwrite($archivo,$tituloColumnas);
            }

            $consulta = SQL::seleccionar(array("consulta_tiempos_no_laborados_horas"), array("*"),$condicion,"",$orden_empleado.",fecha_pago_planilla");

            if (SQL::filasDevueltas($consulta)){

                $total_empleado   = 0;
                $total_minutos    = 0;
                $total_sucursal   = 0;
                $sucursal_minutos = 0;
                $documento_identidad_anterior = "";

                while($datos = SQL::filaEnObjeto($consulta)) {

                    $sucursal    = SQL::obtenerValor("sucursales","nombre","codigo = '".$datos->codigo_sucursal."'");
                    if ($forma_orden_empleado==1){
                        $empleado = $datos->apellido_empleado;
                    } else {
                        $empleado = $datos->nombre_empleado;
                    }
                    $transaccion = SQL::obtenerValor("transacciones_tiempo","descripcion"," codigo = '".$datos->codigo_transaccion_tiempo."'");

                    if ($forma_tipo_listado==1){

                        if ($documento_identidad_anterior !="" && $documento_identidad_anterior!=$datos->documento_identidad_empleado){
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
                            $archivo->SetFont('Arial',"B",6);
                            $archivo->Cell(170, 4, $textos["TOTAL_EMPLEADO"], 1, 0, "L", true,"",true);
                            $archivo->Cell(30, 4, conversor_segundos(($total_minutos),$textos), 1, 0, "C", true,"",true);
                            $archivo->Cell(30, 4, "$ ".number_format($total_empleado,0), 1, 0, "C", true,"",true);
                            $archivo->Ln(4);
                            $total_minutos  = 0;
                            $total_empleado = 0;
                        }
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
                        $archivo->SetFont('Arial',"",6);
                        $archivo->Cell(15, 4, $datos->documento_identidad_empleado, 1, 0, "L", true,"",true);
                        $archivo->Cell(40, 4, $empleado, 1, 0, "L", true,"",true);
                        $archivo->Cell(20, 4, $datos->fecha_registro, 1, 0, "C", true,"",true);
                        $archivo->Cell(20, 4, $datos->hora_inicio, 1, 0, "C", true,"",true);
                        $archivo->Cell(20, 4, $datos->hora_fin, 1, 0, "C", true,"",true);
                        $archivo->Cell(20, 4, $datos->fecha_pago_planilla, 1, 0, "C", true,"",true);
                        $archivo->Cell(40, 4, $transaccion, 1, 0, "C", true,"",true);
                        $archivo->Cell(30, 4, conversor_segundos(($datos->cantidad_minutos*60),$textos), 1, 0, "C", true,"",true);
                        $archivo->Cell(30, 4, "$ ".number_format($datos->valor_movimiento,0), 1, 0, "C", true,"",true);
                        $archivo->Ln(4);
                    } else {
                        $tituloColumnas = $datos->documento_identidad_empleado.";".$empleado.";".$datos->fecha_registro.";".$datos->hora_inicio.";".$datos->hora_fin.";".$datos->fecha_pago_planilla.";".$transaccion.";".conversor_segundos_plano(($datos->cantidad_minutos*60),$textos).";". (int)$datos->valor_movimiento."\n";
                        fwrite($archivo,$tituloColumnas);
                    }


                    $i++;
                    $total_minutos    += $datos->cantidad_minutos*60;
                    $total_empleado   += $datos->valor_movimiento;
                    $sucursal_minutos += $datos->cantidad_minutos*60;
                    $total_sucursal   += $datos->valor_movimiento;
                    $documento_identidad_anterior = $datos->documento_identidad_empleado;
                }
            }
        }
        $cargaPdf = 0;

        if($i>0) {

            if ($forma_tipo_listado==1){
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
                $archivo->SetFont('Arial',"B",6);
                $archivo->Cell(175, 4, $textos["TOTAL_EMPLEADO"], 1, 0, "R", false,"",false);
                $archivo->Cell(30, 4, conversor_segundos(($total_minutos),$textos), 1, 0, "C", true,"",true);
                $archivo->Cell(30, 4, "$ ".number_format($total_empleado,0), 1, 0, "C", true,"",true);
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
                $archivo->Ln(4);
                $archivo->SetFont('Arial',"B",6);
                $archivo->Cell(175, 4, $textos["TOTAL_SUCURSAL"], 1, 0, "R", false,"",false);
                $archivo->Cell(30, 4, conversor_segundos(($sucursal_minutos),$textos), 1, 0, "C", true,"",true);
                $archivo->Cell(30, 4, "$ ".number_format($total_sucursal,0), 1, 0, "C", true,"",true);
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
            $cargaPdf = 1;
        }

        // Enviar datos con la respuesta del proceso al script que origino la peticion

        if ($cargaPdf == 1) {
            $ruta    = $ruta_archivo;
            $error   = false;
            $mensaje = $textos["ARCHIVO_GENERADO"];
        } else if($cargaPdf == 0){
            $error   = true;
            $mensaje = $textos["ERROR_GENERAR_ARCHIVO"];
            $ruta = "";
        }

    }
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    $respuesta[2] = $ruta;
    HTTP::enviarJSON($respuesta);
}
?>

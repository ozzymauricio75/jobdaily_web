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
                HTML::campoTextoCorto("*fechas", $textos["DESDE_HASTA"], 19, 25,"", array("class" => "fechaRango","title"=>$textos["AYUDA_FECHA"]))
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

    if (!isset($forma_sucursales)){
        $error   = true;
        $mensaje = $textos["ERROR_SUCURSAL_VACIA"];
    } else if (empty($forma_fechas)){
        $error   = true;
        $mensaje = $textos["ERROR_FECHA_VACIA"];
    } else {

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

        if ($forma_orden_empleado == "1"){
            $orden_empleado = "apellido_empleado";
        } else if ($forma_orden_empleado =="2"){
            $orden_empleado = "nombre_empleado";
        } else {
            $orden_empleado = "documento_identidad_empleado";
        }

        $i=0;
        $total_empleado = 0;
        $documento_identidad_anterior = "";
        $fechas = explode(" - ",$forma_fechas);
        $fecha_inicio = str_replace("/","-",$fechas[0]);
        $fecha_fin    = str_replace("/","-",$fechas[1]);

        foreach($forma_sucursales AS $codigo_sucursal){

            if ($forma_tipo_listado == 1){
                $archivo                 = new PDF("P","mm","Letter");
                $archivo->textoTitulo    = $textos["REPORTE_NOVEDADES"];
                $archivo->textoCabecera  = $textos["FECHAS"].": ".date("Y-m-d H:i:s");
                $archivo->textoPiePagina = "";
                $archivo->AddPage();

                $tituloColumnas = array($textos["SUCURSAL"],$textos["DOCUMENTO_IDENTIDAD"], $textos["EMPLEADO"], $textos["FECHA_PAGO"], $textos["TRANSACCION_CONTABLE"], $textos["VALOR"]);
                $anchoColumnas  = array(30,30,50,20,45,21);

                $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
                $archivo->Ln(4);
            } else {
                $archivo       = fopen($nombreArchivo,"a+");
                $titulos_plano = "\"".$textos["SUCURSAL"]."\";\"".$textos["DOCUMENTO_IDENTIDAD"]."\";\"".$textos["EMPLEADO"]."\";\"".$textos["FECHA_PAGO"]."\";\"".$textos["TRANSACCION_CONTABLE"]."\";\"".$textos["VALOR"]."\"\n";
                fwrite($archivo, $titulos_plano);
            }

            $condicion = "codigo_sucursal = '$codigo_sucursal' AND fecha_pago_planilla BETWEEN '$fecha_inicio' AND '$fecha_fin'";
            if (!empty($forma_documento_identidad)){
                $condicion .= " AND documento_identidad_empleado = '$forma_documento_identidad'";
            }

            $consulta  = SQL::seleccionar(array("consulta_movimientos_novedades_manuales"), array("*"),$condicion,"",$orden_empleado.",fecha_pago_planilla");

            if (SQL::filasDevueltas($consulta)) {
                while($datos = SQL::filaEnObjeto($consulta)) {

                    $transaccion = SQL::obtenerValor("transacciones_contables_empleado","nombre","codigo='".$datos->codigo_transaccion_contable."'");
                    $sucursal    = SQL::obtenerValor("sucursales","nombre","codigo='".$datos->codigo_sucursal."'");
                    if ($forma_orden_empleado==1){
                        $empleado = $datos->apellido_empleado;
                    } else {
                        $empleado = $datos->nombre_empleado;
                    }

                    if($forma_tipo_listado == 1){
                        if($archivo->breakCell(6)){
                            $archivo->AddPage();
                            $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
                            $archivo->Ln(4);
                        }

                        if ($documento_identidad_anterior !="" && $documento_identidad_anterior != $datos->documento_identidad_empleado){

                            if($archivo->FillColor != sprintf('%.3F %.3F %.3F rg',1,1,1)){
                                $archivo->SetFillColor(255,255,255);
                            }else{
                                $archivo->SetFillColor(240,240,240);
                            }
                            $archivo->SetFont('Arial',"B",7);
                            $archivo->Cell(175, 4, $textos["TOTAL_EMPLEADO"], 0, 0, "R", true,"",true);
                            $archivo->Cell(21, 4, "$ ".number_format($total_empleado,0), 0, 0, "R", true,"",true);
                            $archivo->Ln(4);
                            $total_empleado = 0;
                        }

                        if($archivo->FillColor != sprintf('%.3F %.3F %.3F rg',1,1,1)){
                            $archivo->SetFillColor(255,255,255);
                        }else{
                            $archivo->SetFillColor(240,240,240);
                        }

                        $archivo->SetFont('Arial',"",7);
                        $archivo->Cell(30, 4, $sucursal, 0, 0, "L", true,"",true);
                        $archivo->Cell(30, 4, $datos->documento_identidad_empleado, 0, 0, "L", true,"",true);
                        $archivo->Cell(50, 4, $empleado, 0, 0, "L", true,"",true);
                        $archivo->Cell(20, 4, $datos->fecha_pago_planilla, 0, 0, "C", true,"",true);
                        $archivo->Cell(45, 4, $transaccion, 0, 0, "L", true,"",true);
                        $archivo->Cell(21, 4, "$ ".number_format($datos->valor_movimiento,0), 0, 0, "R", true,"",true);
                        $archivo->Ln(4);
                        $documento_identidad_anterior = $datos->documento_identidad_empleado;
                        $total_empleado += $datos->valor_movimiento;
                    }else{
                        $transaccion = str_replace(";","",$transaccion);
                        $sucursal    = str_replace(";","",$sucursal);
                        $empleado    = str_replace(";","",$empleado);

                        $contenido      = "\"".$sucursal."\";\"".$datos->documento_identidad_empleado."\";\"".$empleado."\";\"".$datos->fecha_pago_planilla."\";\"".$transaccion."\";".(int)$datos->valor_movimiento."\n";
                        $guardarArchivo = fwrite($archivo,$contenido);
                    }

                    $i++;
                }

                if($forma_tipo_listado == 1){
                    if($archivo->FillColor != sprintf('%.3F %.3F %.3F rg',1,1,1)){
                        $archivo->SetFillColor(255,255,255);
                    }else{
                        $archivo->SetFillColor(240,240,240);
                    }
                    $archivo->SetFont('Arial',"B",7);
                    $archivo->Cell(175, 4, $textos["TOTAL_EMPLEADO"], 0, 0, "R", true,"",true);
                    $archivo->Cell(21, 4, "$ ".number_format($total_empleado,0), 0, 0, "R", true,"",true);
                    $archivo->Ln(4);
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
            } else {
                $mensaje = $textos["ERROR_GENERAR_ARCHIVO"];
            }
        }
    }

    // Enviar datos con la respuesta del proceso al script que origino la peticion
    $respuesta    = array();
    if ($cargaPdf == 1) {
        $respuesta[0] = false;
        $respuesta[1] = $textos["ARCHIVO_GENERADO"];
        $respuesta[2] = $ruta_archivo;
    } else{
        $respuesta[0] = true;
        $respuesta[1] = $mensaje;
        $respuesta[2] = "";
    }
    HTTP::enviarJSON($respuesta);
}
?>

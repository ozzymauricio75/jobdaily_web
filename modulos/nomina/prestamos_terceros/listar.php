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
if(isset($url_verificar)){
    $condicion_extra = "id_sucursal IN (".$url_sucursales.")";
    echo SQL::datosAutoCompletar("seleccion_empleados", $url_q, $condicion_extra);
    exit;
}

if (isset($url_completar)) {//Validado
    if ($url_item == "selector2") {
        echo SQL::datosAutoCompletar("seleccion_terceros", $url_q);
    }
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

        $formularios["PESTANA_SUCURSALES"] = $pestana_sucursales;

        // Definicion de pestana general
        $orden_empleado =array(
            "1" => $textos["APELLIDO_NOMBRE"],
            "2" => $textos["NOMBRE_APELLIDO"],
            "3" => $textos["CEDULA"]
        );

        $tipo_listado = array(
            "1" => "PDF",
            "2" => "PLANO"
        );
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
                HTML::campoTextoCorto("*selector2", $textos["NOMBRE_TERCERO"], 40, 255, "", array("title" => $textos["AYUDA_TERCERO"],"class" => "autocompletable","onKeyUp" => "limpiar_oculto_Autocompletable(this, documento_tercero);"))
               .HTML::campoOculto("documento_tercero", "")
            ),
            array(
                HTML::listaSeleccionSimple("orden_empleado", $textos["ORDEN_EMPLEADO"],$orden_empleado,1, array("title" => $textos["AYUDA_ORDEN_EMPLEADO"]))
            ),
            array(
                HTML::listaSeleccionSimple("tipo_listado",$textos["TIPO_LISTADO"],$tipo_listado,1,array("title"=>$textos["AYUDA_TIPO_LISTADO"]))
            )
        );

        $botones = array(
            HTML::boton("botonAceptar", $textos["EXPORTAR"], "imprimirItem(1);", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios,$botones);
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

    $ruta_archivo = "";
    $error        = false;
    $mensaje      = $textos["ARCHIVO_GENERADO"];
    $cargaPdf     = 0;
    $ruta         = "";

    if (!isset($forma_sucursales)){
        $error   = true;
        $mensaje = $textos["ERROR_SUCURSAL_VACIA"];
    } else if (empty($forma_fechas)){
        $error   = true;
        $mensaje = $textos["ERROR_FECHA_VACIA"];
    } else {
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
            $archivo->textoTitulo    = $textos["LISTPTTE"]." ".$textos["FECHAS"]." ".$forma_fechas;
            $archivo->textoCabecera  = $textos["FECHA"].": ".date("Y-m-d H:i:s");
            $archivo->textoPiePagina = "";
        } else {
            $archivo = fopen($nombreArchivo,"a+");
        }

        if ($forma_orden_empleado=="1"){
            $orden_empleado = "apellido_empleado";
        } else if ($forma_orden_empleado=="2"){
            $orden_empleado = "nombre_empleado";
        } else {
            $orden_empleado = "documento_identidad_empleado";
        }

        $fechas = explode("-",$forma_fechas);
        $fecha1 = trim($fechas[0]);
        $fecha2 = trim($fechas[1]);
        $fecha1 = str_replace("/","-",$fecha1);
        $fecha2 = str_replace("/","-",$fecha2);

        $limite = array(
            "0" => $textos["DESCUENTO_ILIMITADO"],
            "1" => $textos["DESCUENTO_FECHA"],
            "2" => $textos["DESCUENTO_TOPE"]
        );
        $forma_pago = array(
            "1" => $textos["MENSUAL"],
            "4" => $textos["PRIMERA_SEMANA"],
            "5" => $textos["SEGUNDA_SEMANA"],
            "6" => $textos["TERCERA_SEMANA"],
            "7" => $textos["CUARTA_SEMANA"],
            "8" => $textos["QUINTA_SEMANA"],
            "2" => $textos["PRIMERA_QUINCENA"],
            "3" => $textos["SEGUNDA_QUINCENA"],
            "9" => $textos["PROPOCIONAL"]
         );

        foreach ($forma_sucursales AS $codigo_sucursal){

            $total_empleado = 0;
            $total_sucursal = 0;
            $documento_identidad_anterior = "";

            if ($forma_tipo_listado == 1){

                $archivo->AddPage();

                $archivo->SetFont('Arial','B',6);
                $tituloColumnas = array($textos["CEDULA"],$textos["EMPLEADO"],$textos["NOMBRE_TERCERO"],$textos["FECHA_INICIO_DESCUENTO"],$textos["OBLIGACION"],$textos["LIMITE_DESCUENTO"],$textos["DESCUENTO_TOPE"],$textos["DESCUENTO_FECHA"],$textos["ESTADO"],$textos["PERIODO_PAGO"],$textos["VALOR_DESCUENTO"]);
                $anchoColumnas  = array(15,40,40,15,15,20,20,15,18,20,20);
                $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
                $archivo->Ln(4);
            } else {
                $tituloColumnas = $textos["CEDULA"].";".$textos["EMPLEADO"].";".$textos["NOMBRE_TERCERO"].";".$textos["FECHA_INICIO_DESCUENTO"].";".$textos["OBLIGACION"].";".$textos["LIMITE_DESCUENTO"].";".$textos["DESCUENTO_TOPE"].";".$textos["DESCUENTO_FECHA"].";".$textos["ESTADO"].";".$textos["PERIODO_PAGO"].";".$textos["VALOR_DESCUENTO"]."\n";
                fwrite($archivo,$tituloColumnas);
            }
            $condicion = "codigo_sucursal = '$codigo_sucursal'";
            $condicion .= " AND (fecha_inicio_descuento BETWEEN '$fecha1' AND '$fecha2')";
            if($forma_documento_identidad != ""){
                $condicion .= " AND documento_identidad_empleado = '$forma_documento_identidad'";
            }
            if($forma_documento_tercero != ""){
                $condicion .= " AND documento_identidad_tercero = '$forma_documento_tercero'";
            }

            $i = 0;
            $consulta = SQL::seleccionar(array("consulta_prestamos_terceros"),array("*"),$condicion,"","codigo_sucursal,$orden_empleado,fecha_inicio_descuento");

            if(SQL::filasDevueltas($consulta)){

                $total_empleado_primera = 0;
                $total_empleado_segunda = 0;
                $total_empleado_mensual = 0;
                $total_sucursal = 0;
                $documento_identidad_anterior = "";

                while($datos = SQL::filaEnObjeto($consulta)){

                    $tercero  = SQL::obtenerValor("seleccion_terceros","SUBSTRING_INDEX(nombre,'|',1)","id = '".$datos->documento_identidad_tercero."'");
                    $sucursal = SQL::obtenerValor("sucursales","nombre","codigo = '".$datos->codigo_sucursal."'");

                    if($datos->limite_descuento == '0'){
                        $valor_tope   = "";
                        $fecha_limite = "";
                    }else if($datos->limite_descuento == '1'){
                        $valor_tope   = "";
                        $fecha_limite = $datos->fecha_limite_descuento;
                    }else{
                        $valor_tope   = $datos->valor_tope_descuento;
                        $fecha_limite = "";
                    }

                    if ($forma_orden_empleado=="1"){
                        $empleado = $datos->apellido_empleado;
                    } else {
                        $empleado = $datos->nombre_empleado;
                    }

                    $valor_mensual          = "";
                    $valor_primera_quincena = "";
                    $valor_segundo_quincena = "";

                    if($forma_tipo_listado == 1){

                        if($datos->periodo_pago == '1'){
                            $valor_mensual          = "$ ".number_format($datos->valor_descontar_mensual,0);
                        }else if($datos->periodo_pago == '2'){
                            $valor_primera_quincena = "$ ".number_format($datos->valor_descontar_primera_quincena,0);
                        }else if($datos->periodo_pago == '3'){
                            $valor_segundo_quincena = "$ ".number_format($datos->valor_descontar_segunda_quincena,0);
                        }else if($datos->periodo_pago == '9'){
                            $valor_primera_quincena = "$ ".number_format($datos->valor_descontar_primera_quincena,0);
                            $valor_segundo_quincena = "$ ".number_format($datos->valor_descontar_segunda_quincena,0);
                        }

                        if ($documento_identidad_anterior!="" && $documento_identidad_anterior!=$datos->documento_identidad_empleado){
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

                            $archivo->SetFont('Arial','B',6);
                            //$archivo->Cell(15, 4, $sucursal, 0, 0, "L",true,"",true);
                            $archivo->Cell(218, 4, $textos["TOTAL_EMPLEADO"], 0, 0, "R",true,"",true);
                            $archivo->Cell(20, 4, "$ ".number_format($total_empleado), 0, 0, "R",true,"",true);
                            $archivo->Ln(4);
                            $total_empleado = 0;
                        }

                        $total_prestamo = "$ ".number_format((int)($datos->valor_descontar_mensual + $datos->valor_descontar_primera_quincena + $datos->valor_descontar_segunda_quincena));

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
                        //$archivo->Cell(15, 4, $sucursal, 0, 0, "L",true,"",true);
                        $archivo->Cell(15, 4, $datos->documento_identidad_empleado, 0, 0, "L",true,"",true);
                        $archivo->Cell(40, 4, $empleado, 0, 0, "L",true,"",true);
                        $archivo->Cell(40, 4, $tercero, 0, 0, "L",true,"",true);
                        $archivo->Cell(15, 4, $datos->fecha_inicio_descuento, 0, 0, "C",true,"",true);
                        $archivo->Cell(15, 4, $datos->obligacion, 0, 0, "L",true,"",true);
                        $archivo->Cell(20, 4, $limite[$datos->limite_descuento], 0, 0, "L",true,"",true);
                        $archivo->Cell(20, 4, $valor_tope, 0, 0, "R",true,"",true);
                        $archivo->Cell(15, 4, $fecha_limite, 0, 0, "C",true,"",true);
                        $archivo->Cell(18, 4, $textos["ESTADO_".$datos->estado], 0, 0, "L",true,"",true);
                        $archivo->Cell(20, 4, $forma_pago[$datos->periodo_pago], 0, 0, "R",true,"",true);
                        $archivo->Cell(20, 4, $total_prestamo, 0, 0, "R",true,"",true);
                        $archivo->Ln(4);
                    }else{

                        $valor_mensual          = (int)$datos->valor_descontar_mensual;
                        $valor_primera_quincena = (int)$datos->valor_descontar_primera_quincena;
                        $valor_segundo_quincena = (int)$datos->valor_descontar_segunda_quincena;
                        $total_prestamo = (int)($datos->valor_descontar_mensual + $datos->valor_descontar_primera_quincena + $datos->valor_descontar_segunda_quincena);

                        $tituloColumnas = $datos->documento_identidad_empleado.";".$empleado.";".$tercero.";".$datos->fecha_inicio_descuento.";".$datos->obligacion.";".$limite[$datos->limite_descuento].";".$valor_tope.";".$fecha_limite.";".$textos["ESTADO_".$datos->estado].";".$forma_pago[$datos->periodo_pago].";".$total_prestamo."\n";
                        fwrite($archivo,$tituloColumnas);
                    }

                    $total_empleado += (int)($datos->valor_descontar_mensual + $datos->valor_descontar_primera_quincena + $datos->valor_descontar_segunda_quincena);
                    $total_sucursal += (int)($datos->valor_descontar_mensual + $datos->valor_descontar_primera_quincena + $datos->valor_descontar_segunda_quincena);
                    $documento_identidad_anterior = $datos->documento_identidad_empleado;
                    $i++;
                }
            }
            if ($forma_tipo_listado==1){
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

                $archivo->SetFont('Arial','B',6);
                //$archivo->Cell(15, 4, $sucursal, 0, 0, "L",true,"",true);
                $archivo->Cell(218, 4, $textos["TOTAL_EMPLEADO"], 0, 0, "R",true,"",true);
                $archivo->Cell(20, 4, "$ ".number_format($total_empleado), 0, 0, "R",true,"",true);
                $archivo->Ln(4);
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

                $archivo->SetFont('Arial','B',6);
                //$archivo->Cell(15, 4, $sucursal, 0, 0, "L",true,"",true);
                $archivo->Cell(218, 4, $textos["TOTAL_SUCURSAL"], 0, 0, "R",true,"",true);
                $archivo->Cell(20, 4, "$ ".number_format($total_sucursal), 0, 0, "R",true,"",true);
                $archivo->Ln(4);
            }

        }

        if($i > 0 && !$error){
            if($forma_tipo_listado == 1){
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


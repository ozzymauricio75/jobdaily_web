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
* modificarlo  bajo los términos de la Licencia Pública General GNU
* publicada por la Fundación para el Software Libre, ya sea la versión 3
* de la Licencia, o (a su elección) cualquier versión posterior.
*
* Este programa se distribuye con la esperanza de que sea útil, pero
* SIN GARANTÍA ALGUNA; ni siquiera la garantía implícita MERCANTIL o
* de APTITUD PARA UN PROPÓITO DETERMINADO. Consulte los detalles de
* la Licencia Pública General GNU para obtener una información más
* detallada.
*
* Debería haber recibido una copia de la Licencia Pública General GNU
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
**/

// Generar el formulario para la captura de datos

if(isset($url_verificar)){
    $condicion_extra = "id_sucursal IN (".$url_sucursales.")";
    echo SQL::datosAutoCompletar("seleccion_empleados", $url_q, $condicion_extra);
    exit;
}

if (!empty($url_generar)) {

    $error           = "";
    $titulo          = $componente->nombre;
    $error_continuar = false;

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
        $error_continuar = 0;
    }

    $consulta_empleados = SQL::seleccionar(array("ingreso_empleados"),array("*"),"documento_identidad_empleado!=''");
    if (!SQL::filasDevueltas($consulta_empleados)){
        $error_continuar = 1;
    }

    if (!$error_continuar){


        $ano = date("Y");
        $ano_planilla = array();
        for ($i=0;$i<=1;$i++){
            $ano_planilla[$ano] = $ano;
            $ano++;
        }
        $ano = date("Y");
        $mes = date("m");

        if ($mes < 7){
            $fecha_inicio = $ano."/01/01";
            $fecha_fin    = $ano."/06/30";
        } else {
            $fecha_inicio = $ano."/07/01";
            $fecha_fin    = $ano."/12/30";
        }

        $orden_planilla =array(
            "1" => $textos["APELLIDO_NOMBRE"],
            "2" => $textos["NOMBRE_APELLIDO"],
            "3" => $textos["CEDULA"]
        );

        $tipo_listado = array(
            "1" => "PDF",
            "2" => "PLANO"
        );

        $formularios["PESTANA_SUCURSALES"] = $pestana_sucursales;

        $formularios["PESTANA_BASICA"] = array(
            array(
                HTML::listaSeleccionSimple("*orden_planilla", $textos["ORDEN_PLANILLA"],$orden_planilla,1, array("title" => $textos["AYUDA_ORDEN_PLANILLA"])),
            ),
            array(
                HTML::campoTextoCorto("selector1", $textos["EMPLEADO"], 40, 255, "", array("title" => $textos["AYUDA_EMPLEADO"], "onfocus" => "autocompletableListaPlanilla(this);", "onKeyUp" => "limpiar_oculto_Autocompletable(this, documento_identidad)")).
                HTML::campoOculto("documento_identidad","").
                HTML::campoOculto("listaSucursales",$listaSucursales)
            ),
            array(
                HTML::campoTextoCorto("*fecha_promedio", $textos["FECHA_PROMEDIO"], 20, 20, $fecha_inicio." - ".$fecha_fin, array("title" => $textos["AYUDA_FECHA_PROMEDIO"], "class" => "fechaRango")),
            ),
            array(
                HTML::listaSeleccionSimple("tipo_listado",$textos["TIPO_LISTADO"],$tipo_listado,1,array("title"=>$textos["AYUDA_TIPO_LISTADO"]))
            )
        );

        // Definicion de botones
        $botones = array(HTML::boton("botonAceptar", $textos["ACEPTAR"], "imprimirItem(1);", "aceptar"));

        $contenido = HTML::generarPestanas($formularios, $botones);
    } else {
        if ($error_continuar == 0){
            $error = $textos["ERROR_SUCURSALES"];
        } else if($error_continuar == 1){
            $error = $textos["ERROR_EMPLEADOS"];
        }
        $contenido = "";
    }


    // Enviar datos para la generacion del formulario al script que origino la peticion
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

} elseif (!empty($forma_procesar)) {
    // Asumir por defecto que no hubo error
    $error        = false;
    $mensaje      = $textos["LISTADO_GENREADO"];
    $genero_pdf   = false;

    if (empty($forma_documento_identidad) && !isset($forma_sucursales)){
        $error   = true;
        $mensaje = $textos["ERROR_SUCURSAL_VACIA"];

    } else if (empty($forma_fecha_promedio)){
        $error   = true;
        $mensaje = $textos["ERROR_FECHAS"];

    } else {

        $nombre         = "";
        $nombreArchivo  = "";

        $consecutivo = SQL::obtenerValor("archivos","MAX(consecutivo)","codigo_sucursal='$sesion_sucursal'");
        if ($consecutivo){
            $consecutivo++;
        } else {
            $consecutivo = 1;
        }
        $consecutivo = (int)$consecutivo;
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
            $fechaReporte = date("Y-m-d");
            $archivo = new PDF("L","mm","Legal");
            $archivo->textoPiePagina = "";
            $genero_pdf = false;
        } else {
            $archivo = fopen($nombreArchivo,"a+");
        }

        $genero_pdf = false;

        if ($forma_orden_planilla=="1"){
            $orden_planilla = "apellido_empleado";
        } else if ($forma_orden_planilla=="2"){
            $orden_planilla = "nombre_empleado";
        } else {
            $orden_planilla = "documento_identidad_empleado ASC";
        }

        $fecha_movimiento = explode(" - ",$forma_fecha_promedio);
        $fecha_inicio     = $fecha_movimiento[0];
        $fecha_inicio     = str_replace("/","-",$fecha_inicio);
        $fecha_fin        = $fecha_movimiento[1];
        $fecha_fin        = str_replace("/","-",$fecha_fin);

        $arreglo    = explode("-",$fecha_inicio);
        $ano_inicio = (int)$arreglo[0];
        $mes_inicio = (int)$arreglo[1];
        $dia_inicio = (int)$arreglo[2];
        $arreglo    = explode("-",$fecha_fin);
        $ano_fin    = (int)$arreglo[0];
        $mes_fin    = (int)$arreglo[1];
        $dia_fin    = (int)$arreglo[2];

        $dias_promedio = (((($ano_fin * 360)+($mes_fin*30)+$dia_fin) - (($ano_inicio * 360)+($mes_inicio*30)+$dia_inicio)) + 1);

        foreach ($forma_sucursales AS $codigo_sucursal){

            $condicion_planilla  = "codigo_sucursal = '$codigo_sucursal' AND (acumula_prima = '1' OR resta_prima='1')";
            $condicion_planilla .= " AND fecha_pago_planilla BETWEEN '$fecha_inicio' AND '$fecha_fin'";
            if(!empty($forma_documento_identidad)){
                $condicion_planilla .= " AND documento_identidad_empleado = '$forma_documento_identidad'";
            }

            $consulta_planilla = SQL::seleccionar(array("consulta_datos_planilla"),array("*"),$condicion_planilla,"","departamento_empresa,$orden_planilla,fecha_pago_planilla ASC");
            $contabilizado = false;


            if (SQL::filasDevueltas($consulta_planilla)) {

                if ($forma_tipo_listado==1){
                    $nombreSucursal          = SQL::obtenerValor("sucursales", "nombre", "codigo='".$codigo_sucursal."'");
                    $archivo->textoTitulo    = $textos["LISTMOPR"] . " ". $textos["FECHA"] . ": " . $forma_fecha_promedio;
                    $archivo->textoCabecera  = $textos["FECHA_LISTADO"]." ".date("Y-m-d H:i:s");
                    $usuario                 = SQL::obtenerValor("usuarios", "nombre", "usuario='".$sesion_usuario."'");
                    $archivo->textoPiePagina = $textos["USUARIO"] . " " . $usuario;
                    $archivo->AddPage();
                    $archivo->SetFont('Arial', 'B', 7);
                }

                if ($forma_tipo_listado==1){
                    $tituloColumnas = array($textos["DOCUMENTO"],$textos["NOMBRE_EMPLEADO"],$textos["TRANSACCION_CONTABLE"],$textos["CUENTA_CONTABLE"],$textos["SENTIDO"],$textos["VALOR_MOVIMIENTO"],$textos["FECHA_REGISTRO"],$textos["FECHA_PAGO"],$textos["ANEXO"],$textos["AUXILIAR"]);
                    $anchoColumnas = array(20,40,35,45,15,20,25,25,30,30);
                    $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
                    $archivo->Ln(3);
                } else {
                    $tituloColumnas = $textos["DEPARTAMENTO"].";".$textos["DOCUMENTO"].";".$textos["NOMBRE_EMPLEADO"].";".$textos["TRANSACCION_CONTABLE"].";".$textos["CUENTA_CONTABLE"].";".$textos["SENTIDO"].";".$textos["VALOR_MOVIMIENTO"].";".$textos["FECHA_REGISTRO"].";".$textos["FECHA_PAGO"].";".$textos["ANEXO"].";".$textos["AUXILIAR"]."\n";
                    fwrite($archivo,$tituloColumnas);
                }

                $codigoEmpresa     = "";
                $documentoAnterior = "";
                $sumaEmpresaD   = 0;
                $sumaEmpresaC   = 0;
                $sumaSucursalD  = 0;
                $sumaSucursalC  = 0;
                $sumaEmpleadoD  = 0;
                $sumaEmpleadoC  = 0;

                while ($datos = SQL::filaEnObjeto($consulta_planilla)) {

                    if ($forma_tipo_listado==1){
                        if($archivo->breakCell(12)){
                            $archivo->AddPage();
                            $archivo->SetFont('Arial', 'B', 7);
                            $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
                            $archivo->Ln(3);
                        }

                        if(!($documentoAnterior === $datos->documento_identidad_empleado)){
                            $archivo->SetFont('Arial', 'B', 8);
                            if($sumaEmpleadoD > 0 || $sumaEmpleadoC > 0){
                                $archivo->Ln(3);
                                $archivo->Cell(155,5,$textos["TOTAL_EMPLEADO"].":",0,0,"R",false,"",true);
                                $archivo->Cell(20,5,"$ ".number_format($sumaEmpleadoD-$sumaEmpleadoC,0),0,0,"R",false,"",true);
                                $promedio = ($sumaEmpleadoD-$sumaEmpleadoC) / ($dias_promedio / 30);
                                $archivo->Cell(20,5,$textos["PROMEDIO"].":",0,0,"R",false,"",true);
                                $archivo->Cell(20,5,"$ ".number_format($promedio,0),0,0,"R",false,"",true);
                                $archivo->Ln(6);
                                $sumaEmpleadoD = 0;
                                $sumaEmpleadoC = 0;
                            }
                        }
                        if(!($codigoEmpresa === $datos->departamento_empresa)){
                            $archivo->SetFont('Arial', 'B', 8);
                            if($sumaEmpresaD > 0 || $sumaEmpresaC > 0){
                                $archivo->Cell(155,5,$textos["TOTAL_DEPARTAMENTO"].":",0,0,"R",false,"",true);
                                $archivo->Cell(20,5,"$ ".number_format($sumaEmpresaD-$sumaEmpresaC,0),0,0,"R",false,"",true);
                                $archivo->Ln(3);
                                $sumaEmpresaD   = 0;
                                $sumaEmpresaC   = 0;
                            }
                            $archivo->Ln(2);
                            $archivo->Cell(50,5,$datos->departamento_empresa,0,0,"L",false,"",true);
                            $archivo->Ln(4);
                        }
                    }

                    $transaccion = SQL::obtenerValor("transacciones_contables_empleado","descripcion","codigo = '".$datos->codigo_transaccion_contable."'");
                    $cuenta      = SQL::obtenerValor("plan_contable","descripcion","codigo_contable = '".$datos->codigo_contable."'");
                    $auxiliar    = SQL::obtenerValor("auxiliares_contables","descripcion","codigo_empresa = '".$datos->codigo_empresa_auxiliar."' AND codigo_anexo_contable = '".$datos->codigo_anexo_contable."' AND codigo = '".$datos->codigo_auxiliar_contable."'");
                    $anexo       = SQL::obtenerValor("anexos_contables","descripcion","codigo = '".$datos->codigo_anexo_contable."'");

                    if ($forma_orden_planilla==1){
                        $nombre_empleado_listado = $datos->apellido_empleado;
                    } else {
                        $nombre_empleado_listado = $datos->nombre_empleado;
                    }
                    if ($forma_tipo_listado==1){
                        $archivo->SetFont('Arial', '', 8);

                        $archivo->Cell(20, 5, $datos->documento_identidad_empleado, 0, 0, "L", false,"",true);
                        $archivo->Cell(40, 5, $nombre_empleado_listado, 0, 0, "L", false,"",true);
                        $archivo->Cell(35, 5, $datos->codigo_transaccion_contable." - ".$transaccion, 0, 0, "L", false,"",true);
                        $archivo->Cell(45, 5, $datos->codigo_contable." - ".$cuenta, 0, 0, "L", false,"",true);
                        $archivo->Cell(15, 5, $datos->sentido, 0, 0, "C", false,"",true);
                        $archivo->Cell(20, 5, "$ ".number_format($datos->valor_movimiento,0), 0, 0, "R", false,"",true);
                        $archivo->Cell(25, 5, $datos->fecha_registro, 0, 0, "C", false,"",true);
                        $archivo->Cell(25, 5, $datos->fecha_pago_planilla, 0, 0, "C", false,"",true);
                        $archivo->Cell(30, 5, $anexo, 0, 0, "L", false,"",true);
                        $archivo->Cell(30, 5, $auxiliar, 0, 0, "L", false,"",true);
                        $archivo->Ln(4);
                        $codigoEmpresa     = $datos->departamento_empresa;
                        $documentoAnterior = $datos->documento_identidad_empleado;
                    } else {
                        $tituloColumnas = "$datos->departamento_empresa;".(int)$datos->documento_identidad_empleado.";$nombre_empleado_listado;$datos->codigo_transaccion_contable - $transaccion;$datos->codigo_contable - $cuenta;$datos->sentido;";
                        $tituloColumnas .= (int)$datos->valor_movimiento.";$datos->fecha_registro;$datos->fecha_pago_planilla;$anexo;$auxiliar\n";
                        fwrite($archivo,$tituloColumnas);
                    }
                    if($datos->sentido == 'D'){
                        $sumaEmpresaD  += $datos->valor_movimiento;
                        $sumaSucursalD += $datos->valor_movimiento;
                        $sumaEmpleadoD += $datos->valor_movimiento;
                    }else{
                        $sumaEmpresaC  += $datos->valor_movimiento;
                        $sumaSucursalC += $datos->valor_movimiento;
                        $sumaEmpleadoC += $datos->valor_movimiento;
                    }
                }
                if ($forma_tipo_listado==1){
                    $archivo->SetFont('Arial', 'B', 8);
                    $archivo->Ln(3);
                    $archivo->Cell(155,5,$textos["TOTAL_EMPLEADO"].":",0,0,"R",false,"",true);
                    $archivo->Cell(20,5,"$ ".number_format($sumaEmpleadoD-$sumaEmpleadoC,0),0,0,"R",false,"",true);
                    $promedio = ($sumaEmpleadoD-$sumaEmpleadoC) / ($dias_promedio / 30);
                    $archivo->Cell(20,5,$textos["PROMEDIO"].":",0,0,"R",false,"",true);
                    $archivo->Cell(20,5,"$ ".number_format($promedio,0),0,0,"R",false,"",true);
                    $archivo->Ln(3);
                    $archivo->Cell(155,5,$textos["TOTAL_DEPARTAMENTO"].":",0,0,"R",false,"",true);
                    $archivo->Cell(20,5,"$ ".number_format($sumaEmpresaD-$sumaEmpresaC,0),0,0,"R",false,"",true);
                    $archivo->Ln(3);
                    $archivo->Cell(155,5,$textos["TOTAL_SUCURSAL"].":",0,0,"R",false,"",true);
                    $archivo->Cell(20,5,"$ ".number_format($sumaSucursalD-$sumaSucursalC,0),0,0,"R",false,"",true);
                }
                $genero_pdf = true;
            }
        }

        if ($forma_tipo_listado=="1"){
            $archivo->Output($nombreArchivo, "F");
        } else {
            fclose($archivo);
        }
        $datos_archivo = array(
            "codigo_sucursal" => $sesion_sucursal,
            "consecutivo"     => $consecutivo,
            "nombre"          => $nombre
        );
        SQL::insertar("archivos", $datos_archivo);
        $id_archivo = $sesion_sucursal."|".$consecutivo;
        $ruta_archivo = HTTP::generarURL("DESCARCH")."&id=".$id_archivo."&temporal=1";
    }
    // Enviar datos con la respuesta del proceso al script que origino la peticion
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;

    if ($genero_pdf && !$error) {
        $respuesta    = array();
        $respuesta[0] = $error;
        $respuesta[1] = $mensaje;
        $respuesta[2] = $ruta_archivo;
    } else if (!$genero_pdf && !$error){
        $error        = true;
        $mensaje      = $textos["SIN_INFORMACION"];

        $respuesta    = array();
        $respuesta[0] = $error;
        $respuesta[1] = $mensaje;
    }
    HTTP::enviarJSON($respuesta);
}
?>

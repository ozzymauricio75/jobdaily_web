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

        $formularios["PESTANA_SUCURSALES"] = $pestana_sucursales;

        $orden_empleado =array(
            "1" => $textos["APELLIDO_NOMBRE"],
            "2" => $textos["NOMBRE_APELLIDO"],
            "3" => $textos["CEDULA"]
        );

        $orden_listado =array(
            "1" => $textos["CONCEPTO_EMPLEADO"],
            "2" => $textos["EMPLEADO_CONCEPTO"]
        );

        $orden_listado_terceros =array(
            "1" => $textos["TERCERO_EMPLEADO"],
            "2" => $textos["EMPLEADO_TERCERO"]
        );

        $tipo_listado = array(
            "1" => "PDF",
            "2" => "PLANO"
        );

        $formularios["PESTANA_BASICA"] = array(
            array(
                HTML::campoTextoCorto("*fecha_listado", $textos["FECHA"], 20, 20, date("Y/m/d")." - ".date("Y/m/d"), array("title" => $textos["AYUDA_FECHA"], "class" => "fechaRango")),
            ),
            array(
                HTML::marcaChequeo("descuentos_empleados",$textos["DESCUENTOS_EMPLEADOS"], 1, true, array("title"=>$textos["AYUDA_DESCUENTOS_EMPLEADOS"])),
                HTML::listaSeleccionSimple("*orden_listado_empleado", $textos["ORDEN_LISTADO_EMPLEADO"],$orden_listado,1, array("title" => $textos["AYUDA_ORDEN_LISTADO"]))
            ),
            array(
                HTML::marcaChequeo("descuentos_terceros",$textos["DESCUENTOS_TERCEROS"], 1, true, array("title"=>$textos["AYUDA_DESCUENTOS_TERCEROS"])),
                HTML::listaSeleccionSimple("*orden_listado_tercero", $textos["ORDEN_LISTADO_TERCEROS"],$orden_listado_terceros,1, array("title" => $textos["AYUDA_ORDEN_LISTADO"]))
            ),
            array(
                HTML::campoTextoCorto("selector1", $textos["EMPLEADO"], 40, 255, "", array("title" => $textos["AYUDA_EMPLEADO"], "onfocus" => "autocompletableListaPlanilla(this);", "onKeyUp" => "limpiar_oculto_Autocompletable(this, documento_identidad)")).
                HTML::campoOculto("documento_identidad","").
                HTML::campoOculto("listaSucursales",$listaSucursales)
            ),
            array(
                HTML::listaSeleccionSimple("*orden_empleado", $textos["ORDEN_PLANILLA"],$orden_empleado,1, array("title" => $textos["AYUDA_ORDEN_PLANILLA"]))
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

} elseif (!empty($forma_procesar)){
    // Asumir por defecto que no hubo error
    $error        = false;
    $mensaje      = $textos["LISTADO_GENERADO"];
    $genero_pdf   = false;
    $genero_pdf_tercero = false;

    if (!isset($forma_sucursales)){
        $error   = true;
        $mensaje = $textos["ERROR_SUCURSALES"];

    } else if (!isset($forma_descuentos_empleados) && !isset($forma_descuentos_terceros)){
        $error   = true;
        $mensaje = $textos["ERROR_DESCUENTOS"];

    } else if (empty($forma_fecha_listado)){
        $error   = true;
        $mensaje = $textos["ERROR_FECHA"];

    } else {

        $meses = array(
            "1" => $textos["ENERO"],
            "2" => $textos["FEBRERO"],
            "3" => $textos["MARZO"],
            "4" => $textos["ABRIL"],
            "5" => $textos["MAYO"],
            "6" => $textos["JUNIO"],
            "7" => $textos["JULIO"],
            "8" => $textos["AGOSTO"],
            "9" => $textos["SEPTIEMBRE"],
            "10" => $textos["OCTUBRE"],
            "11" => $textos["NOVIEMBRE"],
            "12" => $textos["DICIEMBRE"]
        );

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
            $archivo = new PDF("P","mm","Letter");
            $archivo->textoPiePagina = "";
        } else {
            $archivo = fopen($nombreArchivo,"a+");
        }
        $genero_pdf              = false;

        $fecha_listado = explode(" - ",$forma_fecha_listado);
        $fecha_desde   = str_replace("/","-",$fecha_listado[0]);
        $fecha_hasta   = str_replace("/","-",$fecha_listado[1]);

        $codigo_sucursal_anterior = "";
        $total_sucursal           = 0;
        foreach($forma_sucursales AS $codigo_sucursal){

            $condicion  = "fecha_pago_planilla >='$fecha_desde' AND fecha_pago_planilla <='$fecha_hasta' ";
            $condicion .= " AND codigo_sucursal_pago = '".$codigo_sucursal."'";

            if (!empty($forma_documento_identidad)){
                $condicion .= " AND documento_identidad_empleado = '$forma_documento_identidad'";
            }

            if ($forma_orden_empleado=="1"){
                $orden_empleado = "apellido_empleado";
            } else if ($forma_orden_empleado=="2"){
                $orden_empleado = "nombre_empleado";
            } else {
                $orden_empleado = "documento_identidad_empleado";
            }

            if (isset($forma_descuentos_empleados)){

                if ($forma_orden_listado_empleado==1){
                    $orden = "codigo_concepto_prestamo,".$orden_empleado;
                } else {
                    $orden = $orden_empleado.",codigo_concepto_prestamo";
                }

                $consulta = SQL::seleccionar(array("prestamos_empleados_pagados"),array("*"),$condicion,"","$orden,fecha_pago_planilla");

                if (SQL::filasDevueltas($consulta)){

                    $empleado         = array();
                    $nombre_empleado  = array();

                    while($datos = SQL::filaEnObjeto($consulta)){

                        $concepto_prestamo       = $datos->codigo_concepto_prestamo;
                        $descripcion_concepto    = $datos->descripcion_concepto_prestamo;
                        $codigo_transaccion      = $datos->codigo_transaccion_contable_pago;
                        $descripcion_transaccion = $datos->descripcion_transaccion_contable_pago;
                        $codigo_contable         = $datos->codigo_contable;
                        $documento_identidad     = $datos->documento_identidad_empleado;
                        if ($forma_orden_empleado == "1"){
                            $nombre_empleado = $datos->apellido_empleado;
                        } else {
                            $nombre_empleado = $datos->nombre_empleado;
                        }
                        $apellido_empleado       = $datos->apellido_empleado;
                        $valor_descuento         = $datos->valor_descuento;
                        $sentido                 = $datos->sentido;
                        $fecha_pago              = $datos->fecha_pago_planilla;
                        $genero_pdf = true;

                        if ($forma_orden_empleado=="1"){
                            $orden_indice = $apellido_empleado;
                        } else if ($forma_orden_empleado=="2"){
                            $orden_indice = $nombre_empleado;
                        } else {
                            $orden_indice = $documento_identidad;
                        }
                        if ($forma_orden_listado_empleado==1){
                            $indice_arreglo = $orden_indice."|".$concepto_prestamo."|".$codigo_transaccion."|".$codigo_contable."|".$sentido."|".$fecha_pago;
                        } else {
                            $indice_arreglo = $concepto_prestamo."|".$orden_indice."|".$codigo_transaccion."|".$codigo_contable."|".$sentido."|".$fecha_pago;
                        }


                        if (isset($valor_consulta[$indice_arreglo])){
                            if ($sentido=="C"){
                                $valor_consulta[$indice_arreglo] += $valor_descuento;
                            } else {
                                $valor_consulta[$indice_arreglo] -= $valor_descuento;
                            }
                        } else {
                            if ($sentido=="C"){
                                $valor_consulta[$indice_arreglo] = $valor_descuento;
                            } else {
                                $valor_consulta[$indice_arreglo] = $valor_descuento * (-1);
                            }
                            $documento_empleado_consulta[$orden_indice]            = $documento_identidad;
                            $nombre_empleado_consulta[$orden_indice]               = $nombre_empleado;
                            $descripcion_concepto_consulta[$concepto_prestamo]     = $descripcion_concepto;
                            $descripcion_transaccion_consulta[$codigo_transaccion] = $descripcion_transaccion;
                        }
                    }
                }

                $total_sucursal = 0;
                if ($genero_pdf){

                    if ($forma_tipo_listado==1){
                        $archivo->SetFont('Arial', 'B', 6);
                        $nombreSucursal = SQL::obtenerValor("sucursales", "nombre", "codigo='$codigo_sucursal'");
                        $archivo->textoTitulo    = $textos["LISTPGPR"];
                        $archivo->textoCabecera  = $textos["FECHA"]." ".date("Y-m-d H:i:s");
                        $usuario                 = SQL::obtenerValor("usuarios", "nombre", "usuario='".$sesion_usuario."'");
                        $archivo->textoPiePagina = $textos["USUARIO"] . " " . $usuario;
                        $archivo->AddPage();
                        $archivo->SetFont('Arial', 'B', 6);
                    }

                    if ($forma_tipo_listado==1){
                        if ($forma_orden_listado_empleado==1){
                            $tituloColumnas = array($textos["EMPLEADO"],$textos["TRANSACCION"],$textos["CODIGO_CONTABLE"],$textos["SENTIDO"],$textos["FECHA_PAGO"],$textos["TOTAL"]);
                        } else {
                            $tituloColumnas = array($textos["CONCEPTO"],$textos["TRANSACCION"],$textos["CODIGO_CONTABLE"],$textos["SENTIDO"],$textos["FECHA_PAGO"],$textos["TOTAL"]);
                        }
                    } else {

                        $tituloColumnas = $textos["DESCUENTOS_EMPLEADOS"]."\n";
                        fwrite($archivo,$tituloColumnas);

                        if ($forma_orden_listado_empleado==1){
                            $tituloColumnas = $textos["CONCEPTO"].";".$textos["EMPLEADO"].";".$textos["TRANSACCION"].";".$textos["CODIGO_CONTABLE"].";".$textos["SENTIDO"].";".$textos["FECHA_PAGO"].";".$textos["TOTAL"]."\n";
                        } else {
                            $tituloColumnas = $textos["EMPLEADO"].";".$textos["CONCEPTO"].";".$textos["TRANSACCION"].";".$textos["CODIGO_CONTABLE"].";".$textos["SENTIDO"].";".$textos["FECHA_PAGO"].";".$textos["TOTAL"]."\n";
                        }
                        fwrite($archivo,$tituloColumnas);
                    }

                    if ($forma_tipo_listado==1){
                        $anchoColumnas = array(40,40,20,10,20,30);
                        $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
                        $archivo->Ln(6);
                        $archivo->Cell(155,5,$textos["DESCUENTOS_EMPLEADOS"],0,0,"L",false,"",true);
                        $archivo->Ln(3);
                    }

                    $total_descuentos = 0;
                    $total_empleado   = 0;
                    $documento_identidad_anterior = "";
                    $concepto_prestamo_anterior = "";

                    foreach($valor_consulta AS $datos => $valor_descuento){

                        $datos_consulta       = explode("|",$datos);
                        if ($forma_orden_listado_empleado==1){
                            $documento_identidad  = $datos_consulta[0];
                            $concepto_prestamo    = $datos_consulta[1];
                        } else {
                            $documento_identidad  = $datos_consulta[1];
                            $concepto_prestamo    = $datos_consulta[0];
                        }
                        $transaccion_contable = $datos_consulta[2];
                        $codigo_contable      = $datos_consulta[3];
                        $sentido              = $datos_consulta[4];
                        $fecha_pago           = $datos_consulta[5];

                        if ($forma_tipo_listado==1){
                            if($archivo->breakCell(12)){
                                $archivo->AddPage();
                                $archivo->SetFont('Arial', 'B', 6);
                                $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
                                $archivo->Ln(3);
                                $archivo->Cell(155,5,$textos["DESCUENTOS_EMPLEADOS"],0,0,"L",false,"",true);
                                $archivo->Ln(3);
                            }
                        }

                        if ($forma_orden_listado_empleado==1){
                            if ($concepto_prestamo_anterior != $concepto_prestamo){
                                $continuar = true;
                                if ($concepto_prestamo_anterior==""){
                                    $continuar_vacio = false;
                                } else {
                                    $continuar_vacio = true;
                                }
                            } else {
                                $continuar = false;
                            }
                        } else {
                            if ($documento_identidad_anterior != $documento_identidad){
                                $continuar = true;
                                if ($documento_identidad_anterior==""){
                                    $continuar_vacio = false;
                                } else {
                                    $continuar_vacio = true;
                                }
                            } else {
                                $continuar = false;
                            }
                        }

                        if ($continuar && $forma_tipo_listado==1){

                            $archivo->SetFont('Arial', 'B', 5);

                            if ($continuar_vacio){

                                if ($forma_orden_listado_empleado==1){
                                    $archivo->Cell(130, 5, $textos["TOTAL_CONCEPTO"], 0, 0, "R", false,"",true);
                                } else {
                                    $archivo->Cell(130, 5, $textos["TOTAL_EMPLEADO"], 0, 0, "R", false,"",true);
                                }
                                $archivo->Cell(30, 5,number_format($total_empleado), 0, 0, "R", false,"",true);
                                $archivo->Ln(3);
                                $total_empleado = 0;
                                if($archivo->breakCell(12)){
                                    $archivo->AddPage();
                                    $archivo->SetFont('Arial', 'B', 6);
                                    $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
                                    $archivo->Ln(6);
                                    $archivo->Cell(155,5,$textos["DESCUENTOS_EMPLEADOS"],0,0,"L",false,"",true);
                                    $archivo->Ln(3);
                                }
                            }

                            if ($forma_orden_listado_empleado==1){
                                $archivo->Cell(100, 5, $descripcion_concepto_consulta[$concepto_prestamo], 0, 0, "L", false,"",true);
                            } else {
                                $archivo->Cell(100, 5, $documento_empleado_consulta[$documento_identidad]." ".$nombre_empleado_consulta[$documento_identidad], 0, 0, "L", false,"",true);
                            }
                            $archivo->Ln(3);
                        }


                        if ($forma_tipo_listado==1){
                            $archivo->SetFont('Arial', '', 5);
                            if ($forma_orden_listado_empleado==1){
                                $archivo->Cell(40, 5, $documento_empleado_consulta[$documento_identidad]." ".$nombre_empleado_consulta[$documento_identidad], 0, 0, "L", false,"",true);
                            } else {
                                $archivo->Cell(40, 5, $descripcion_concepto_consulta[$concepto_prestamo], 0, 0, "L", false,"",true);
                            }
                            $archivo->Cell(40, 5, $descripcion_transaccion_consulta[$transaccion_contable], 0, 0, "L", false,"",true);
                            $archivo->Cell(20, 5, $codigo_contable, 0, 0, "L", false,"",true);
                            $archivo->Cell(10, 5, $sentido, 0, 0, "C", false,"",true);
                            $archivo->Cell(20, 5, $fecha_pago, 0, 0, "C", false,"",true);
                            $archivo->Cell(30, 5, number_format($valor_descuento), 0, 0, "R", false,"",true);
                            $archivo->Ln(3);
                        } else {
                            if ($forma_orden_listado_empleado==1){
                                $tituloColumnas = $descripcion_concepto_consulta[$concepto_prestamo].";";
                                $tituloColumnas .= $documento_empleado_consulta[$documento_identidad]." ".$nombre_empleado_consulta[$documento_identidad].";";
                            } else {
                                $tituloColumnas = $documento_empleado_consulta[$documento_identidad]." ".$nombre_empleado_consulta[$documento_identidad].";";
                                $tituloColumnas .= $descripcion_concepto_consulta[$concepto_prestamo].";";
                            }
                            $tituloColumnas .= $descripcion_transaccion_consulta[$transaccion_contable].";";
                            $tituloColumnas .= $codigo_contable.";";
                            $tituloColumnas .= $sentido.";";
                            $tituloColumnas .= $fecha_pago.";";
                            $tituloColumnas .= (int)$valor_descuento."\n";
                            fwrite($archivo,$tituloColumnas);
                        }

                        if ($sentido=="C"){
                            $total_descuentos += $valor_descuento;
                            $total_empleado   += $valor_descuento;
                            $total_sucursal   += $valor_descuento;
                        } else {
                            $total_descuentos -= $valor_descuento;
                            $total_empleado   -= $valor_descuento;
                            $total_sucursal   -= $valor_descuento;
                        }

                        $documento_identidad_anterior = $documento_identidad;
                        $concepto_prestamo_anterior   = $concepto_prestamo;
                    }

                    if ($forma_tipo_listado==1){
                        if($archivo->breakCell(12)){
                            $archivo->AddPage();
                            $archivo->SetFont('Arial', 'B', 6);
                            $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
                            $archivo->Ln(3);
                            $archivo->Cell(155,5,$textos["DESCUENTOS_EMPLEADOS"],0,0,"L",false,"",true);
                            $archivo->Ln(3);
                        }
                        $archivo->SetFont('Arial', 'B', 5);
                        if ($forma_orden_listado_empleado==1){
                            $archivo->Cell(130, 5, $textos["TOTAL_CONCEPTO"], 0, 0, "R", false,"",true);
                        } else {
                            $archivo->Cell(130, 5, $textos["TOTAL_EMPLEADO"], 0, 0, "R", false,"",true);
                        }
                        $archivo->Cell(30, 5, number_format($total_empleado), 0, 0, "R", false,"",true);
                        $archivo->Ln(3);
                        $archivo->Cell(130, 5, $textos["TOTAL"]." ".$textos["DESCUENTOS_EMPLEADOS"], 0, 0, "R", false,"",true);
                        $archivo->Cell(30, 5, number_format($total_descuentos), 0, 0, "R", false,"",true);
                        $archivo->Ln(3);
                    }
                }
            }

            if (isset($forma_descuentos_terceros)){

                if ($forma_orden_listado_tercero==1){
                    $orden = "documento_identidad_tercero,".$orden_empleado;
                } else {
                    $orden = $orden_empleado.",documento_identidad_tercero";
                }

                $consulta = SQL::seleccionar(array("prestamos_terceros_pagados"),array("*"),$condicion,"","$orden,transaccion_contable_descuento,fecha_pago_planilla");

                if (SQL::filasDevueltas($consulta)){

                    $empleado                         = array();
                    $nombre_empleado                  = array();
                    $valor_consulta                   = array();
                    $documento_empleado_consulta      = array();
                    $nombre_empleado_consulta         = array();
                    $documento_tercero_consulta       = array();
                    $nombre_tercero_consulta          = array();
                    $descripcion_transaccion_consulta = array();

                    while($datos = SQL::filaEnObjeto($consulta)){

                        $codigo_transaccion          = $datos->transaccion_contable_descuento;
                        $descripcion_transaccion     = $datos->descripcion_transaccion_contable_empleado;
                        $codigo_contable             = $datos->codigo_contable_descuento_empleado;
                        $documento_identidad         = $datos->documento_identidad_empleado;
                        $nombre_empleado             = $datos->nombre_empleado;
                        $documento_identidad_tercero = $datos->documento_identidad_tercero;
                        $tercero                     = $datos->tercero;
                        $apellido_empleado           = $datos->apellido_empleado;
                        $valor_descuento             = $datos->valor_descuento;
                        $sentido                     = $datos->sentido_cuenta_cobrar_descuento;
                        $fecha_pago                  = $datos->fecha_pago_planilla;
                        $genero_pdf_tercero          = true;

                        if ($forma_orden_empleado=="1"){
                            $orden_indice_tabla = $apellido_empleado;
                        } else if ($forma_orden_empleado=="2"){
                            $orden_indice_tabla = $nombre_empleado;
                        } else {
                            $orden_indice_tabla = $documento_identidad;
                        }
                        if ($forma_orden_listado_tercero==1){
                            $orden_indice = $documento_identidad_tercero."-".$orden_indice_tabla;
                        } else {
                            $orden_indice = $orden_indice_tabla."-".$documento_identidad_tercero;
                        }

                        $indice_arreglo = $orden_indice."|".$codigo_transaccion."|".$codigo_contable."|".$sentido."|".$fecha_pago;

                        if (isset($valor_consulta[$indice_arreglo])){
                            if ($sentido=="C"){
                                $valor_consulta[$indice_arreglo] += $valor_descuento;
                            } else {
                                $valor_consulta[$indice_arreglo] -= $valor_descuento;
                            }
                        } else {
                            if ($sentido=="C"){
                                $valor_consulta[$indice_arreglo] = $valor_descuento;
                            } else {
                                $valor_consulta[$indice_arreglo] = $valor_descuento * (-1);
                            }

                            $documento_empleado_consulta[$orden_indice_tabla]         = $documento_identidad;
                            $nombre_empleado_consulta[$orden_indice_tabla]            = $nombre_empleado;
                            $documento_tercero_consulta[$documento_identidad_tercero] = $documento_identidad_tercero;
                            $nombre_tercero_consulta[$documento_identidad_tercero]    = $tercero;
                            $descripcion_transaccion_consulta[$codigo_transaccion]    = $descripcion_transaccion;
                        }
                    }

                    if ($genero_pdf_tercero){

                        if ($forma_tipo_listado==1){
                            if (!$genero_pdf){
                                $archivo->SetFont('Arial', 'B', 6);
                                $nombreSucursal = SQL::obtenerValor("sucursales", "nombre", "codigo='$codigo_sucursal'");
                                $archivo->textoTitulo    = $textos["LISTDEEM"];
                                $archivo->textoCabecera  = $textos["FECHA"]." ".$forma_fecha_listado;
                                $usuario                 = SQL::obtenerValor("usuarios", "nombre", "usuario='".$sesion_usuario."'");
                                $archivo->SetFont('Arial', '', 4);
                                $archivo->textoPiePagina = $textos["USUARIO"] . " " . $usuario;
                                $archivo->AddPage();
                                $archivo->SetFont('Arial', 'B', 6);
                            }

                            $archivo->Ln(6);
                        }
                        $documento_identidad_anterior = "";
                        $codigo_transaccion_anterior  = "";

                        if ($forma_tipo_listado==1){
                            if ($forma_orden_listado_tercero==1){
                                $tituloColumnas = array($textos["EMPLEADO"],$textos["TRANSACCION"],$textos["CODIGO_CONTABLE"],$textos["SENTIDO"],$textos["FECHA_PAGO"],$textos["TOTAL"]);
                            } else {
                                $tituloColumnas = array($textos["TERCERO"],$textos["TRANSACCION"],$textos["CODIGO_CONTABLE"],$textos["SENTIDO"],$textos["FECHA_PAGO"],$textos["TOTAL"]);
                            }
                        } else {

                            $tituloColumnas = "\n";
                            fwrite($archivo,$tituloColumnas);

                            $tituloColumnas = $textos["DESCUENTOS_TERCEROS"]."\n";
                            fwrite($archivo,$tituloColumnas);

                            if ($forma_orden_listado_tercero==1){
                                $tituloColumnas = $textos["TERCERO"].";".$textos["EMPLEADO"].";".$textos["TRANSACCION"].";".$textos["CODIGO_CONTABLE"].";".$textos["SENTIDO"].";".$textos["FECHA_PAGO"]."\n";
                            } else {
                                $tituloColumnas = $textos["EMPLEADO"].";".$textos["TERCERO"].";".$textos["TRANSACCION"].";".$textos["CODIGO_CONTABLE"].";".$textos["SENTIDO"].";".$textos["FECHA_PAGO"]."\n";
                            }
                            fwrite($archivo,$tituloColumnas);
                        }

                        if ($forma_tipo_listado==1){
                            $anchoColumnas = array(40,40,20,10,20,30);
                            $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
                            $archivo->Ln(6);
                            $archivo->SetFont('Arial', 'B', 6);
                            $archivo->Cell(155,5,$textos["DESCUENTOS_TERCEROS"],0,0,"L",false,"",true);
                            $archivo->Ln(3);
                        }

                        $total_descuentos = 0;
                        $total_empleado   = 0;
                        foreach($valor_consulta AS $datos => $valor_descuento){

                            $datos_consulta            = explode("|",$datos);
                            $documento_identidad       = $datos_consulta[0];
                            $documentos                = explode("-",$documento_identidad);
                            $documento_identidad       = $documentos[0];
                            $documento_identidad_tabla = $documentos[1];
                            $transaccion_contable      = $datos_consulta[1];
                            $codigo_contable           = $datos_consulta[2];
                            $sentido                   = $datos_consulta[3];
                            $fecha_pago                = $datos_consulta[4];

                            if ($forma_tipo_listado==1){
                                if($archivo->breakCell(12)){
                                    $archivo->AddPage();
                                    $archivo->SetFont('Arial', 'B', 6);
                                    $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
                                    $archivo->Ln(3);
                                    $archivo->Cell(155,5,$textos["DESCUENTOS_TERCEROS"],0,0,"L",false,"",true);
                                    $archivo->Ln(3);
                                }
                            }

                            if ($forma_tipo_listado==1){
                                if ($documento_identidad_anterior != $documento_identidad){

                                    $archivo->SetFont('Arial', 'B', 5);

                                    if ($documento_identidad_anterior!=""){

                                        if ($forma_orden_listado_tercero==1){
                                            $archivo->Cell(130, 5, $textos["TOTAL_TERCERO"], 0, 0, "R", false,"",true);
                                        } else {
                                            $archivo->Cell(130, 5, $textos["TOTAL_EMPLEADO"], 0, 0, "R", false,"",true);
                                        }
                                        $archivo->Cell(30, 5,number_format($total_empleado), 0, 0, "R", false,"",true);
                                        $archivo->Ln(3);
                                        $total_empleado = 0;
                                        if($archivo->breakCell(12)){
                                            $archivo->AddPage();
                                            $archivo->SetFont('Arial', 'B', 6);
                                            $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
                                            $archivo->Ln(6);
                                            $archivo->Cell(155,5,$textos["DESCUENTOS_TERCEROS"],0,0,"L",false,"",true);
                                            $archivo->Ln(3);
                                        }
                                    }

                                    if ($forma_orden_listado_tercero==1){
                                        $archivo->Cell(100, 5, $documento_tercero_consulta[$documento_identidad]." ".$nombre_tercero_consulta[$documento_identidad], 0, 0, "L", false,"",true);
                                    } else {
                                        $archivo->Cell(100, 5, $documento_empleado_consulta[$documento_identidad]." ".$nombre_empleado_consulta[$documento_identidad], 0, 0, "L", false,"",true);
                                    }
                                    $archivo->Ln(3);
                                }
                            }

                            if ($forma_tipo_listado==1){
                                $archivo->SetFont('Arial', '', 5);
                                if ($forma_orden_listado_tercero==1){
                                    $archivo->Cell(40, 5, $documento_empleado_consulta[$documento_identidad_tabla]." ".$nombre_empleado_consulta[$documento_identidad_tabla], 0, 0, "L", false,"",true);
                                } else {
                                    $archivo->Cell(40, 5, $documento_tercero_consulta[$documento_identidad_tabla]." ".$nombre_tercero_consulta[$documento_identidad_tabla], 0, 0, "L", false,"",true);
                                }
                                $archivo->Cell(40, 5, $descripcion_transaccion_consulta[$transaccion_contable], 0, 0, "L", false,"",true);
                                $archivo->Cell(20, 5, $codigo_contable, 0, 0, "L", false,"",true);
                                $archivo->Cell(10, 5, $sentido, 0, 0, "C", false,"",true);
                                $archivo->Cell(20, 5, $fecha_pago, 0, 0, "C", false,"",true);
                                $archivo->Cell(30, 5, number_format($valor_descuento), 0, 0, "R", false,"",true);
                                $archivo->Ln(3);
                            } else {

                                if ($forma_orden_listado_tercero==1){
                                    $tituloColumnas = $documento_tercero_consulta[$documento_identidad]." ".$nombre_tercero_consulta[$documento_identidad].";";
                                    $tituloColumnas .= $documento_empleado_consulta[$documento_identidad_tabla]." ".$nombre_empleado_consulta[$documento_identidad_tabla].";";
                                } else {
                                    $tituloColumnas = $documento_empleado_consulta[$documento_identidad]." ".$nombre_empleado_consulta[$documento_identidad].";";
                                    $tituloColumnas .= $documento_tercero_consulta[$documento_identidad_tabla]." ".$nombre_tercero_consulta[$documento_identidad_tabla].";";
                                }
                                $tituloColumnas .= $descripcion_transaccion_consulta[$transaccion_contable].";";
                                $tituloColumnas .= $codigo_contable.";";
                                $tituloColumnas .= $sentido.";";
                                $tituloColumnas .= $fecha_pago.";";
                                $tituloColumnas .= (int)$valor_descuento."\n";
                                fwrite($archivo,$tituloColumnas);
                            }

                            if ($sentido=="C"){
                                $total_descuentos += $valor_descuento;
                                $total_empleado   += $valor_descuento;
                                $total_sucursal   += $valor_descuento;
                            } else {
                                $total_descuentos -= $valor_descuento;
                                $total_empleado   -= $valor_descuento;
                                $total_sucursal   -= $valor_descuento;
                            }

                            $documento_identidad_anterior = $documento_identidad;
                        }

                        if ($forma_tipo_listado==1){
                            if($archivo->breakCell(12)){
                                $archivo->AddPage();
                                $archivo->SetFont('Arial', 'B', 6);
                                $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
                                $archivo->Ln(3);
                                $archivo->Cell(155,5,$textos["DESCUENTOS_TERCEROS"],0,0,"L",false,"",true);
                                $archivo->Ln(3);
                            }
                            $archivo->SetFont('Arial', 'B', 5);
                            if ($forma_orden_listado_tercero==1){
                                $archivo->Cell(130, 5, $textos["TOTAL_TERCERO"], 0, 0, "R", false,"",true);
                            } else {
                                $archivo->Cell(130, 5, $textos["TOTAL_EMPLEADO"], 0, 0, "R", false,"",true);
                            }
                            $archivo->Cell(30, 5, number_format($total_empleado), 0, 0, "R", false,"",true);
                            $archivo->Ln(3);
                            $archivo->Cell(130, 5, $textos["TOTAL"]." ".$textos["DESCUENTOS_TERCEROS"], 0, 0, "R", false,"",true);
                            $archivo->Cell(30, 5, number_format($total_descuentos), 0, 0, "R", false,"",true);
                            $archivo->Ln(3);
                        }
                    }
                }
            }

            if (($genero_pdf || $genero_pdf_tercero) && $forma_tipo_listado==1){
                $archivo->SetFont('Arial', 'B', 5);
                $archivo->Cell(130, 5, $textos["TOTAL_SUCURSAL"], 0, 0, "R", false,"",true);
                $archivo->Cell(30, 5, number_format($total_sucursal), 0, 0, "R", false,"",true);
                $archivo->Ln(3);
            }
        }

        if ($forma_tipo_listado==1 && ($genero_pdf || $genero_pdf_tercero)){
            $archivo->Output($nombreArchivo, "F");

        } else if ($forma_tipo_listado==2 && ($genero_pdf || $genero_pdf_tercero)){
            fclose($archivo);
        }
        if ($genero_pdf || $genero_pdf_tercero){
            $datos_archivo = array(
                "codigo_sucursal" => $sesion_sucursal,
                "consecutivo"     => $consecutivo,
                "nombre"          => $nombre
            );
            SQL::insertar("archivos", $datos_archivo);
            $id_archivo = $sesion_sucursal."|".$consecutivo;
            $ruta_archivo = HTTP::generarURL("DESCARCH")."&id=".$id_archivo."&temporal=1";
        }
    }
    // Enviar datos con la respuesta del proceso al script que origino la peticion
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;

    if (($genero_pdf || $genero_pdf_tercero) && !$error) {
        $respuesta    = array();
        $respuesta[0] = $error;
        $respuesta[1] = $mensaje;
        $respuesta[2] = $ruta_archivo;
    } else if (!$genero_pdf && !$genero_pdf_tercero && !$error){
        $error        = true;
        $mensaje      = $textos["SIN_INFORMACION"];

        $respuesta    = array();
        $respuesta[0] = $error;
        $respuesta[1] = $mensaje;
    }
    HTTP::enviarJSON($respuesta);
}
?>

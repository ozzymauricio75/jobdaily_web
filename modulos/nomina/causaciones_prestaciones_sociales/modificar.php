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

$concepto_retiro_cesantias = array(
    "1" => $textos["VIVIENDA"],
    "2" => $textos["EDUCACION"]
);

//////////Determino las cesantias por trabajador//////////////
if (isset($url_determino_cesantias)) {

    $datos_movimiento = array();
    $datos_movimiento_valor = array();
    $datos_enviar = array();
    $valor_total_cesantias = array();


    $consulta_movimientos_salarios = SQL::seleccionar(array("consulta_datos_planilla"), array("*"), "documento_identidad_empleado='$url_documento_empleado'");
    while ($datos_movimientos_salarios = SQL::filaEnObjeto($consulta_movimientos_salarios)) {
        ////Armo una llave unica para el arreglo////
        $llave_movimientos = $datos_movimientos_salarios->ano_generacion . '|' . $datos_movimientos_salarios->mes_generacion . '|' . $datos_movimientos_salarios->codigo_planilla . '|' . $datos_movimientos_salarios->periodo_pago;
        /////calculo de cesantias////
        $dias_trabajados = 0;
        $valor_salario = 0;
        $valor_auxilio = 0;
        $valor_transaccion = 0;

        if ($datos_movimientos_salarios->tabla == 1) {
            $valor_salario = $datos_movimientos_salarios->valor_movimiento;
            $dias_trabajados = $datos_movimientos_salarios->dias_trabajados;
        } elseif ($datos_movimientos_salarios->tabla == 6) {
            $valor_auxilio = $datos_movimientos_salarios->valor_movimiento;
        } elseif ($datos_movimientos_salarios->tabla == 4) {
            $acumula_censantias = SQL::obtenerValor("transacciones_contables_empleado", "acumula_cesantias", "codigo='$datos_movimientos_salarios->codigo_transaccion_contable'");
            if ($acumula_censantias == '1') {
                $valor_transaccion = $datos_movimientos_salarios->valor_movimiento;
            }
        }

        if (!isset($datos_movimiento_valor[$llave_movimientos])) {
            $datos_movimiento_valor[$llave_movimientos][1] = $dias_trabajados;
            $datos_movimiento_valor[$llave_movimientos][0] = $valor_salario + $valor_auxilio + $valor_transaccion;
        } else {
            $datos_movimiento_valor[$llave_movimientos][0] += $valor_salario + $valor_auxilio + $valor_transaccion;
        }
    }
    ////////consulto la fecha de pago de la planilla///////
    $valor_total_movimiento = 0;
    $valor_total_retiro = 0;
    $valor_retiro = 0;
    $llaves_movimiento = array_keys($datos_movimiento_valor);
    for ($i = 0; $i < count($datos_movimiento_valor); $i++) {
        $continuar = false;
        $llave_planilla = explode("|", $llaves_movimiento[$i]);
        $condicion = " ano_generacion='$llave_planilla[0]' AND mes_generacion='$llave_planilla[1]'";
        $condicion .= " AND codigo_planilla='$llave_planilla[2]' AND periodo_pago='$llave_planilla[3]'";
        $consulta_planilla = SQL::seleccionar(array("movimientos_salarios"), array("fecha_pago_planilla"), $condicion, "", "", 0, 1);
        $datos_planilla = SQL::filaEnObjeto($consulta_planilla);
        $salario_devengado = $datos_movimiento_valor[$llaves_movimiento[$i]][0];
        $dias_trabajado = $datos_movimiento_valor[$llaves_movimiento[$i]][1];
        $valor_cesantias = ($salario_devengado * $dias_trabajado) / 360;  //definir como parametro

        $consulta_retiro = SQL::seleccionar(array("retiro_cesantias"), array("*"), "documento_identidad_empleado='$url_documento_empleado' AND fecha_ultima_planilla='$datos_planilla->fecha_pago_planilla'");
        $valor_total_movimiento += $valor_cesantias;

        while ($datos_retiro = SQL::filaEnObjeto($consulta_retiro)) {

            $valor_retiro += $datos_retiro->valor_retiro;
            $valor_total_retiro += $datos_retiro->valor_retiro;
            $continuar = true;
        }

        if ($continuar) {

            $datos_enviar[] = '1|' . $datos_planilla->fecha_pago_planilla . '|' . round($salario_devengado) . '|' . round($valor_cesantias) . '|' . round($valor_total_movimiento) . '|' . round($valor_retiro);
        } else {

            $datos_enviar[] = '2|' . $datos_planilla->fecha_pago_planilla . '|' . round($salario_devengado) . '|' . round($valor_cesantias);
        }

        $ultima_fecha = $datos_planilla->fecha_pago_planilla;
        $datos_enviar[] = $ultima_fecha . '|' . round($valor_total_movimiento - $valor_total_retiro);
    }

    HTTP::enviarJSON($datos_enviar);
    exit;
}
/////////////////////////////////////////////////////
// Verificar si el modulo del componente actual esta habilitado en el periodo contable actual
$sentidos = array(
    "1" => $textos["DEBITO"],
    "2" => $textos["CREDITO"]
);

if (isset($url_recargar_consecutivo_cheque)) {
    $llave_cuenta = explode('|', $url_cuenta);
    $consecutivo_cheque = SQL::obtenerValor("consecutivo_cheques", "MAX(consecutivo)", "codigo_tipo_documento = '" . $llave_cuenta[1] . "' AND codigo_banco = '" . $llave_cuenta[6] . "' AND numero = '" . $llave_cuenta[7] . "'");
    if (!$consecutivo_cheque) {
        $consecutivo_cheque = 1;
    } else {
        $consecutivo_cheque++;
    }
    $cuenta = SQL::obtenerValor("seleccion_plan_contable_transacciones", "SUBSTRING_INDEX(cuenta,'|',1)", "id = '" . $llave_cuenta[8] . "'");

    unset($llave_cuenta[8]);

    $llave = implode('|', $llave_cuenta);

    $auxiliar = SQL::obtenerValor("buscador_cuentas_bancarias", "id_auxiliar", "id = '" . $llave . "'");

    $descripcion = SQL::obtenerValor("seleccion_auxiliares_contables", "descripcion", "id = '" . $auxiliar . "'");

    $datos = array($consecutivo_cheque, $cuenta, $auxiliar, $descripcion, $llave);
    HTTP::enviarJSON($datos);
    exit;
}

// Devolver datos para autocompletar la búsqueda
if (isset($url_completar)) {//Validado
    if ($url_item == "selector2") {
        echo SQL::datosAutoCompletar("seleccion_plan_contable_debito", $url_q);
    }
    exit;
}

// Devolver datos para recargar informacion requerida
// Devolver datos para recargar informacion requerida
if (isset($url_recargarDatosDocumento)) {

    $datos = array();
    // Obtener consecutivo de documento si tiene manejo automatico
    $manejo = SQL::obtenerValor("tipos_documentos", "manejo_automatico", "codigo = '" . $url_documento . "'");
    if ($manejo == '2') {
        $consecutivo_documento = SQL::obtenerValor("consecutivo_documentos", "MAX(consecutivo)", "codigo_sucursal = '" . $url_sucursal . "' AND codigo_tipo_documento = '" . $url_documento . "'");
        if (!$consecutivo_documento) {
            $consecutivo_documento = 1;
        } else {
            $consecutivo_documento++;
        }
        $datos["consecutivo_documento"] = $consecutivo_documento;
    } else {
        $datos["consecutivo_documento"] = 0;
    }

    // Obtener cuentas bancarias si genera cheques
    $cheques = SQL::obtenerValor("tipos_documentos", "genera_cheque", "codigo = '" . $url_documento . "'");
    $datos["genera_cheque"] = $cheques;
    if ($cheques == '1') {
        $primer_cuenta = false;
        $consulta = SQL::seleccionar(array("buscador_cuentas_bancarias"), array("*"), "id_sucursal = '" . $url_sucursal . "' AND id_documento = '" . $url_documento . "'");
        if (SQL::filasDevueltas($consulta)) {
            while ($datos_cuenta = SQL::filaEnObjeto($consulta)) {
                if ($primer_cuenta == false) {
                    $primer_cuenta = $datos_cuenta->id;
                }
                $llave_cuenta = explode('|', $datos_cuenta->id);
                $id_plan_cuenta = SQL::obtenerValor("cuentas_bancarias", "codigo_plan_contable", "codigo_sucursal = '" . $llave_cuenta[0] . "' AND codigo_tipo_documento = '" . $llave_cuenta[1] . "' AND codigo_banco = '" . $llave_cuenta[6] . "' AND numero = '" . $llave_cuenta[7] . "' AND codigo_sucursal_banco = '" . $llave_cuenta[2] . "'");
                $datos[$datos_cuenta->id . "|" . $id_plan_cuenta] = $datos_cuenta->BANCO . " - No. " . $datos_cuenta->NUMERO;
            }
            $llave_cuenta = explode('|', $primer_cuenta);
            $consecutivo_cheque = SQL::obtenerValor("consecutivo_cheques", "MAX(consecutivo)", "codigo_tipo_documento = '" . $llave_cuenta[1] . "' AND codigo_banco = '" . $llave_cuenta[6] . "' AND numero = '" . $llave_cuenta[7] . "'");
            if (!$consecutivo_cheque) {
                $consecutivo_cheque = 1;
            } else {
                $consecutivo_cheque++;
            }
            $datos["consecutivo_cheque"] = $consecutivo_cheque;
        }
    }
    HTTP::enviarJSON($datos);
    exit;
}




if (isset($url_verificar)) {
    $condicion_extra = "id_sucursal='$url_codigo_sucursal'";
    echo SQL::datosAutoCompletar("seleccion_empleados", $url_q, $condicion_extra);
    exit;
}

// Generar el formulario para la captura de datos
if (!empty($url_generar)) {
    // Verificar que se haya enviado el ID del elemento a modificar
    if (empty($url_id)) {
        $error     = $textos["ERROR_MODIFICAR_VACIO"];
        $titulo    = "";
        $contenido = "";
    } else {
        $error                 = "";
        $titulo                = $componente->nombre;
        $consecutivo_documento = "";
        ///transacciones contables donde su concepto es prestamos a empleados  009////
        $listado_transacciones_contables = HTML::generarDatosLista("transacciones_contables_empleado","codigo","descripcion","codigo_concepto_transaccion_contable='9' AND sentido='D'");
        ////////////////////////////////////////////////////
        ///Obtener lista de sucursales para selección///
        $codigo_empresa = SQL::obtenerValor("sucursales", "codigo_empresa", "codigo = '$sesion_sucursal'");
        ///Cargo las sucursales dependiendo del codigo de la empresa con que se incia seccion///
        $sucursales = HTML::generarDatosLista("sucursales", "codigo", "nombre","codigo_empresa='$codigo_empresa'");
        $documento  = SQL::obtenerValor("tipos_documentos", "codigo", "codigo != '0' ORDER BY descripcion LIMIT 1");

        $llave_principal              = explode("|",$url_id);
        $documento_identidad_empleado = $llave_principal[0];
        $consecutivo                  = $llave_principal[1];
        $fecha_generacion             = $llave_principal[2];
        $concepto_retiro              = $llave_principal[3];

        $condicion                     = "documento_identidad_empleado= '$documento_identidad_empleado' AND consecutivo='$consecutivo' AND fecha_generacion='$fecha_generacion' AND concepto_retiro='$concepto_retiro'";
        $consulta_retiro_cesantias     = SQL::seleccionar(array("retiro_cesantias"),array("*"),$condicion);
        $datos_retiro_cesantias        = SQL::filaEnObjeto($consulta_retiro_cesantias);
        ////Obtego datos////
        $nombre_sucursal    = SQL::obtenerValor("sucursales","nombre","codigo='$datos_retiro_cesantias->codigo_sucursal'");
        $nombre_empleado    = SQL::obtenerValor("menu_retiro_cesantias","NOMBRE_EMPLEADO","id='$url_id'");
        $nombre_transaccion = SQL::obtenerValor("transacciones_contables_empleado","descripcion","codigo='$datos_retiro_cesantias->codigo_transaccion_contable'");
        $nombre_concepto    = $concepto_retiro_cesantias[(int)$datos_retiro_cesantias->concepto_retiro];
        ////Definición de pestaña basica////
        ////////////////////////////////////////////////////
        $cuentas_bancarias  = array();
        $cheques            = SQL::obtenerValor("tipos_documentos", "genera_cheque", "codigo = '".$documento."'");
        if ($cheques == 1) {
            $primer_cuenta = false;
            $consulta   = SQL::seleccionar(array("buscador_cuentas_bancarias"), array("*"),"id_sucursal='$datos_retiro_cesantias->codigo_sucursal'");
            if (SQL::filasDevueltas($consulta)) {
                while ($datos_cuenta = SQL::filaEnObjeto($consulta)) {
                    if ($primer_cuenta == false) {
                        $primer_cuenta = $datos_cuenta->id;
                    }
                     $llave_cuenta   = explode('|',$datos_cuenta->id);
                    $id_plan_cuenta = SQL::obtenerValor("cuentas_bancarias", "codigo_plan_contable", "codigo_sucursal = '".$llave_cuenta[0]."' AND codigo_tipo_documento = '".$llave_cuenta[1]."' AND codigo_banco = '".$llave_cuenta[6]."' AND numero = '".$llave_cuenta[7]."' AND codigo_sucursal_banco = '".$llave_cuenta[2]."'");
                    $cuentas_bancarias[$datos_cuenta->id."|".$id_plan_cuenta] = $datos_cuenta->BANCO." - No. ".$datos_cuenta->NUMERO;
                }
                $llave_cuenta   = explode('|',$primer_cuenta);
                $consecutivo_cheque = SQL::obtenerValor("consecutivo_cheques", "MAX(consecutivo)", "codigo_tipo_documento = '".$llave_cuenta[1]."' AND codigo_banco = '".$llave_cuenta[6]."' AND numero = '".$llave_cuenta[7]."'");
                if (!$consecutivo_cheque) {
                    $consecutivo_cheque = 1;
                } else {
                    $consecutivo_cheque++;
                }
            }else{
                $consecutivo_cheque     = "";
            }
            $oculto = "";
            $banco_disabled         = "";
        } else {
            $cuentas_bancarias[0]   = "";
            $consecutivo_cheque     = "";
            $oculto                 = "oculto";
            $banco_disabled         = "disabled";
        }

        ///////////////////////////////////////////////////
        $tipo_documento_genera_cheque   = SQL::obtenerValor("tipos_documentos", "genera_cheque", "codigo = '$datos_retiro_cesantias->codigo_tipo_documento'");
        if($tipo_documento_genera_cheque=='1')
        {
            $oculto   = '';
            $disabled = "disabled";
        }else
        {
            $oculto   ='oculto';
            $disabled = '';
        }
        //////////////////////////////////////////////////
        ////////Consulta del consecutivo generado/////////
        $consecutivo_documento = number_format($datos_retiro_cesantias->consecutivo_documento);
        $tipo_comprobante      = SQL::obtenerValor("tipos_documentos", "codigo_comprobante", "codigo = '$datos_retiro_cesantias->codigo_tipo_documento'");
        $llave_inicial_tabla = $datos_retiro_cesantias->documento_identidad_empleado.'|'.$datos_retiro_cesantias->consecutivo.'|'.$datos_retiro_cesantias->fecha_generacion.'|'.$datos_retiro_cesantias->concepto_retiro;
        /////////////////////////////////////////////////
        $codicion_movimiento  = $condicion;
        $consulta_movimientos_retiro_cesantias = SQL::seleccionar(array("movimiento_retiro_cesantias"),array("*"),$codicion_movimiento);
        $datos_movimiento_cesantias            = SQL::filaEnObjeto($consulta_movimientos_retiro_cesantias);
        /////////////////////////////////////////////////
        $codigo_contable = $datos_movimiento_cesantias->codigo_plan_contable;
        $cuenta = SQL::obtenerValor("seleccion_plan_contable_transacciones","SUBSTRING_INDEX(cuenta,'|',1)","id = '$codigo_contable'");
        $read   = "";
        $arreglo = array();

        $arreglo[] = array(
            HTML::mostrarDato("codigo_sucursal_muestra",  $textos["SUCURSAL_LABORA"],$nombre_sucursal),
            HTML::mostrarDato("nombre_empleado", $textos["EMPLEADO"],$nombre_empleado).
            HTML::campoOculto("documento_empleado",$documento_identidad_empleado)
        );
        $arreglo[] = array(
             HTML::listaSeleccionSimple("*tipo_documento", $textos["TIPO_DOCUMENTO"], HTML::generarDatosLista("tipos_documentos", "codigo", "descripcion", "codigo != 0"),$datos_retiro_cesantias->codigo_tipo_documento, array("title" => $textos["AYUDA_TIPO_DOCUMENTO"],"onchange" => "recargarDatosDocumento()")),
             HTML::listaSeleccionSimple("cuenta_bancaria", $textos["CUENTA_BANCARIA"], $cuentas_bancarias, "", array("class" => $oculto,$banco_disabled => $banco_disabled, "onChange" => "consecutivoCheque();")), //,$disabled => $disabled
             HTML::campoTextoCorto("*consecutivo_cheque", $textos["CONSECUTIVO_CHEQUE"], 10, 10, $consecutivo_cheque, array("title" => $textos["AYUDA_CONSECUTIVO_CHEQUE"], "class" => $oculto, "readonly" => "readonly",$banco_disabled => $banco_disabled)),

            );
        $arreglo[] = array(
             HTML::campoTextoCorto("*consecutivo_documento", $textos["CONSECUTIVO_DOCUMENTO"], 10, 10,$consecutivo_documento, array("title" => $textos["AYUDA_CONSECUTIVO_DOCUMENTO"],$read => $read)),
             HTML::campoTextoCorto("*selector2", $textos["CUENTA"], 40, 255,$cuenta,array("title" => $textos["AYUDA_CUENTA"], "class" => "autocompletable",$disabled => $disabled,"onclick" => "determinarCesantias();"))
            .HTML::campoOculto("codigo_contable",$codigo_contable),
            //HTML::listaSeleccionSimple("sentido", $textos["SENTIDO"], $sentidos, "", array("title" => $textos["AYUDA_SENTIDO"],"onChange" => "recargarDatosCuenta();"))
        );
        $arreglo[] =array(
            HTML::listaSeleccionSimple("*codigo_transaccion_contable", $textos["TRANSACCION_CONTABLE"],$listado_transacciones_contables,$datos_retiro_cesantias->codigo_transaccion_contable,array("title" => $textos["AYUDA_TRANSACCION_CONTABLE"])),
        );
        $arreglo[] = array(
            HTML::campoOculto("codigo_tipo_documento",$datos_retiro_cesantias->codigo_tipo_documento)
            );

        $arreglo[] = array(
            HTML::listaSeleccionSimple("*concepto_retiro", $textos["CONCEPTO_PRESTAMO"],$concepto_retiro_cesantias,$datos_retiro_cesantias->concepto_retiro, array("title" => $textos["AYUDA_CONCEPTO_RETIRO"])),
            HTML::campoTextoCorto("*valor_cesantias", $textos["VALOR_CESANTIAS"], 10, 20,"", array("title" => $textos["AYUDA_VALOR_CESANTIAS"],"onKeyPress" => "return campoEntero(event)","onblur" => "generarTablaPagos();","disabled" => "disabled")),
            HTML::campoTextoCorto("*valor_retiro", $textos["VALOR_RETIRO"], 10, 20,$datos_retiro_cesantias->valor_retiro, array("title" => $textos["AYUDA_VALOR_RETIRO"],"onKeyPress" => "return campoEntero(event)"))
            //HTML::campoOculto("valor_descuento",$datos_control_prestamo->valor_pago)
            );


     $arreglo[] = array(
        HTML::campoTextoLargo("observaciones", $textos["OBSERVACIONES"], 4, 50,$datos_retiro_cesantias->observaciones, array("title" => $textos["AYUDA_OBSERVACIONES"]))
        );

        $formularios["PESTANA_BASICA"] = $arreglo;
        $formularios["REPORTE_CENSANTIAS"] = array(
            array(
                HTML::contenedor(HTML::generarTabla(
                                array("id", "FECHA_PLANILLA", "VALOR_DEVENGADO", "VALOR_CESANTIAS"),
                                "",
                                array("I", "I", "I"),
                                "listaCesantias",
                                false
                        )
                )
            )
        );

        // Definicion de botones
        $botones   = array(HTML::boton("botonAceptar", $textos["ACEPTAR"], "modificarItem('".$url_id."');", "aceptar"));
        $contenido = HTML::generarPestanas($formularios,$botones);
        // Enviar datos para la generacion del formulario al script que origino la peticion
        $respuesta[0] = $error;
        $respuesta[1] = $titulo;
        $respuesta[2] = $contenido;
        HTTP::enviarJSON($respuesta);
    }
// Validar los datos provenientes del formulario
} elseif (!empty($forma_procesar)) {

    // Enviar datos con la respuesta del proceso al script que origino la peticion
    $respuesta = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

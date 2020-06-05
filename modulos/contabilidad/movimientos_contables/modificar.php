<?php

/**
*
* Copyright (C) 2008 Felinux Ltda
* Raul Mauricio Oidor Lozano <ozzymauricio75@gmail.com>
*
* Este archivo es parte de:
* PANCE :: Plataforma para la Administraciï¿½n del Nexo Cliente-Empresa
*
* Este programa es software libre: usted puede redistribuirlo y/o
* modificarlo  bajo los tï¿½rminos de la Licencia Pï¿½blica General GNU
* publicada por la Fundaciï¿½n para el Software Libre, ya sea la versiï¿½n 3
* de la Licencia, o (a su elecciï¿½n) cualquier versiï¿½n posterior.
*
* Este programa se distribuye con la esperanza de que sea ï¿½til, pero
* SIN GARANTï¿½A ALGUNA; ni siquiera la garantï¿½a implï¿½cita MERCANTIL o
* de APTITUD PARA UN PROPï¿½SITO DETERMINADO. Consulte los detalles de
* la Licencia Pï¿½blica General GNU para obtener una informaciï¿½n mï¿½s
* detallada.
*
* Deberï¿½a haber recibido una copia de la Licencia Pï¿½blica General GNU
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
**/

/*** Devolver datos para autocompletar la búsqueda ***/
if (isset($url_completar)) {
    if ($url_item == "selector1" || $url_item == "selector3" || $url_item == "selector4" || $url_item == "selector5") {
    echo SQL::datosAutoCompletar("seleccion_terceros", $url_q);
    }
    if ($url_item == "selector2") {
        echo SQL::datosAutoCompletar("seleccion_plan_contable_transacciones", $url_q);
    }
    exit;
}

/*** Devolver datos para recargar informacion requerida ***/
if (isset($url_recargarDatosDocumento)) {
    $datos = array();
    /*** Obtener consecutivo de documento si tiene manejo automatico ***/
    $manejo     = SQL::obtenerValor("tipos_documentos", "manejo_automatico", "id = '$url_documento'");
    if ($manejo == 2) {
        $consecutivo_documento  = SQL::obtenerValor("consecutivo_documentos", "MAX(numero_consecutivo)", "id_sucursal = '$url_sucursal' AND id_tipo_documento = '$url_documento'");
        if ($consecutivo_documento == '') {
            $consecutivo_documento = 1;
        } else {
            $consecutivo_documento++;
        }
        $datos["consecutivo_documento"]   = $consecutivo_documento;
    } else {
        $datos["consecutivo_documento"]   = 0;
    }

    /*** Obtener cuentas bancarias si genera cheques ***/
    $cheques    = SQL::obtenerValor("tipos_documentos", "genera_cheque", "id = '$url_documento'");
    if ($cheques == 1) {
        $primer_cuenta  = false;
        $consulta       = SQL::seleccionar(array("buscador_cuentas_bancarias"), array("*"), "id != '0'");
        if (SQL::filasDevueltas($consulta)) {
            while ($datos_cuenta = SQL::filaEnObjeto($consulta)) {
                if ($primer_cuenta == false) {
                    $primer_cuenta = $datos_cuenta->id;
                }

                $id_plan_cuenta = SQL::obtenerValor("cuentas_bancarias", "id_plan_contable", "id = '$datos_cuenta->id'");
                $datos[$datos_cuenta->id."|".$id_plan_cuenta] = $datos_cuenta->BANCO." - No. ".$datos_cuenta->NUMERO;
            }

            $consecutivo_cheque = SQL::obtenerValor("consecutivo_cheques", "MAX(consecutivo)", "id_cuenta = '$primer_cuenta'");
            if ($consecutivo_cheque == '') {
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

if (isset($url_recargarDatosCuenta)) {
    $datos = array();
    /*** Obtener datos de la cuenta ***/
    $consulta       = SQL::seleccionar( array("plan_contable"),
                                        array("maneja_saldos", "naturaleza_cuenta", "flujo_efectivo", "id_tasa_aplicar_1", "id_tasa_aplicar_2", "maneja_tercero", "id_anexo_contable"),
                                        "id = '$url_id'");
    $datos_cuenta   = SQL::filaEnObjeto($consulta);

    $datos["saldo"]               = $datos_cuenta->maneja_saldos;
    $datos["sentido"]             = $datos_cuenta->naturaleza_cuenta;
    $datos["flujo"]               = $datos_cuenta->flujo_efectivo;
    $datos["tercero"]             = $datos_cuenta->maneja_tercero;
    $datos["anexo_contable"]      = $datos_cuenta->id_anexo_contable;

    $tasa1              = SQL::obtenerValor( "vigencia_tasas", "porcentaje", "id_tasa = '$datos_cuenta->id_tasa_aplicar_1' AND fecha <= '$url_fecha' ORDER BY fecha DESC LIMIT 1");
    if (!$tasa1) {
        $datos["tasa1"] = 0;
    } else {
        $datos["tasa1"] = $tasa1;
    }

    $tasa2              = SQL::obtenerValor( "vigencia_tasas", "porcentaje", "id_tasa = '$datos_cuenta->id_tasa_aplicar_2' AND fecha <= '$url_fecha' ORDER BY fecha DESC LIMIT 1");
    if (!$tasa2) {
        $datos["tasa2"] = 0;
    } else {
        $datos["tasa2"] = $tasa2;
    }

    $anexo      = SQL::obtenerValor("plan_contable", "id_anexo_contable", "id = '$url_id'");
    $consulta   = SQL::seleccionar(array("auxiliares_contables"), array("id","descripcion"), "id_anexo_contable = '$anexo' AND id != '0'");
    if (SQL::filasDevueltas($consulta)) {
        while ($datos_auxiliar = SQL::filaEnObjeto($consulta)) {
            $datos[$datos_auxiliar->id] = $datos_auxiliar->descripcion;

        }
    }

    HTTP::enviarJSON($datos);
    exit;
}

if (isset($url_recargarDatosSaldo)) {
    $datos      = array();
    /*** Consultar documentos del proveedor con saldos pendientes ***/
    $consulta = SQL::seleccionar(array("totalizador_saldos_movimientos_contables"), array('*, SUM(abono) AS total_abono'), "id_tercero = '$url_id_tercero' AND id_cuenta = '$url_id_cuenta'", "id_saldo");
    if (SQL::filasDevueltas($consulta)) {
        $i          = 0;
        $documento  = 0;
        while ($datos_saldo = SQL::filaEnObjeto($consulta)) {

            if ($documento == $datos_saldo->id_consecutivo) {
                $i++;
            } else {
                $i = 1;
            }

            if ($datos_saldo->saldo > $datos_saldo->total_abono) {
                $documento  = SQL::obtenerValor("tipos_documentos", "descripcion", "id = '$datos_saldo->id_documento'");
                $diferencia = $datos_saldo->saldo - $datos_saldo->total_abono;
                $datos[$datos_saldo->id_consecutivo."-".$datos_saldo->id_saldo."-".$datos_saldo->numero_consecutivo."-".$diferencia."-".$documento] = "No. ".$datos_saldo->numero_consecutivo.": Saldo $i $".number_format($diferencia);
            }
            $documento  = $datos_saldo->id_consecutivo;
        }
    }

    HTTP::enviarJSON($datos);
    exit;
}


/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {

     /*** Verificar que se haya enviado el ID del elemento a modificar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_MODIFICAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {

        $vistaConsulta          = "consecutivo_documentos";
        $columnas               = SQL::obtenerColumnas($vistaConsulta);
        $consulta               = SQL::seleccionar(array($vistaConsulta), $columnas, "id = '$url_id'");
        $datos_documento        = SQL::filaEnObjeto($consulta);
        $error                  = "";
        $titulo                 = $componente->nombre;

        /*** Verificar si el modulo del componente actual esta habilitado en el periodo contable actual ***/
        $hoy        = date("Y-m-d");
        $modulo     = SQL::obtenerValor("componentes", "id_modulo", "id = '$componente->id'");
        $consulta   = SQL::seleccionar( array("a" => "periodos_contables", "b" => "periodos_contables_modulos"),
                                                array("b.id"),
                                                "b.id_modulo = '$modulo' AND
                                                b.id_periodo = a.id AND
                                                a.id_sucursal = '$sesion_sucursal' AND
                                                '$hoy' BETWEEN a.fecha_inicio AND a.fecha_fin"
                                            );
        if (!SQL::filasDevueltas($consulta)) {
            $error      = $textos["ERROR_PERIODO_CONTABLE"];
            $titulo     = "";
            $contenido  = "";
        } else {

            /*** Obtener movimiento contable correspondiente al documento seleccionado ***/
            $consulta_movimiento    = SQL::seleccionar( array("movimientos_contables"),
                                                        array("*"),
                                                        "id = '$datos_documento->id_registro_tabla'");
            $datos                  = SQL::filaEnObjeto($consulta_movimiento);

            /*** Validar si el estado del movimiento contable permite la operación ***/
            if ($datos->estado == 2) {
                $error     = $textos["ERROR_ITEM_ANULADO"];
                $titulo    = "";
                $contenido = "";

            } else {

                /*** Validar si el documento posee cheque relacionado, y mostrar datos ***/
                $fila_cuenta_bancaria   = array("");
                $fila_cheque            = array("");
                $cuenta_contable_cheque = 0;
                $consecutivo_cheque     = "";
                $cheque                 = SQL::seleccionar( array(  "a" => "consecutivo_cheques",
                                                                    "b" => "movimientos_contables",
                                                                    "c" => "items_movimientos_contables"),
                                                            array(   "a.numero_consecutivo AS consecutivo"),
                                                            "c.id_movimiento = b.id AND
                                                            b.id = '$url_id'"
                                                        );
                if (SQL::filasDevueltas($cheque)) {
                    $datos_cheque           = SQL::filaEnObjeto($cheque);
                    $cuenta_cheque          = SQL::seleccionar(array("cuentas_bancarias"), array("*"), "id_cuenta = '$datos_cheque->id_cuenta'");
                    $cuenta_cheque          = SQL::filaEnObjeto($cuenta_cheque);

                    $banco_cheque           = SQL::obtenerValor("bancos", "descripcion", "id = '$cuenta_cheque->id_banco'");
                    $consecutivo_cheque     = $datos_cheque->consecutivo;

                    $fila_cuenta_bancaria   =
                        array(
                            HTML::mostrarDato("cuenta_bancaria_texto", $textos["CUENTA_BANCARIA"], $banco_cheque." - ".$textos["NUM"]." ".$cuenta_cheque->numero)
                            .HTML::campoOculto("cuenta_bancaria", $cuenta_cheque->id."|")
                        );
                    $fila_cheque            =
                        array(
                            HTML::mostrarDato("consecutivo_cheque_texto", $textos["CONSECUTIVO_CHEQUE"], $consecutivo_cheque)
                            .HTML::campoOculto("consecutivo_cheque", $consecutivo_cheque)
                        );
                } else {
                    $fila_cuenta_bancaria   = array(HTML::campoOculto("cuenta_bancaria", "0"));
                }


                /* Obtener items relacionados con el movimiento contable */
                $fila               = 0;
                $lista_items        = "";
                $items_no_modificar = "0";
                $total_debito       = 0;
                $total_credito      = 0;
                $consulta_items     = SQL::seleccionar(array("items_movimientos_contables"), array("*"), "id_movimiento = '$datos->id'");
                if (SQL::filasDevueltas($consulta_items)) {

                    $removedor  = HTML::boton("botonRemover", "", "removerItem(this);", "eliminar", array("title" => $textos["ELIMINAR_ITEM"]));
                    $editor     = HTML::boton("botonEditar", "", "editarItem(this);", "modificar", array("title" => $textos["MODIFICAR_ITEM"]));

                    while ($datos_item = SQL::filaEnObjeto($consulta_items)) {

                        $fila       = $datos_item->id;
                        $opciones   = "";

                        /*** Validar si el item es un abono a un saldo por documento ***/
                        $saldo_abono        = 0;
                        $abono = SQL::obtenerValor( "abonos_items_movimientos_contables", "id", "id_item_movimiento = '$fila'");
                        if ($abono) {
                            $consulta_abono     = SQL::seleccionar(array("totalizador_saldos_movimientos_contables"), array("*"), "id_abono = '$abono'");
                            $abono_item         = SQL::filaEnObjeto($consulta_abono);
                            $documento_abono    = SQL::obtenerValor("tipos_documentos", "descripcion", "id = $abono_item->id_documento");
                            $opciones           .=  '<span class="etiqueta">'.$textos["ABONO_DOCUMENTO"]."</span>".$documento_abono." ".$textos["NUM"]
                                                    ." ".$abono_item->numero_consecutivo." ".$textos["SALDO_TOTAL"].number_format($abono_item->saldo)
                                                    ."<br/><br/>";
                            $saldo_abono        = $abono_item->id_saldo;
                        }

                        /*** Definir campos ocultos con datos requeridos ***/
                        $co1  = HTML::campoOculto("movimientos[$fila]", $fila, array("class"=>"movimientos"));
                        $co2  = HTML::campoOculto("sucursales[$fila]", $datos_item->id_sucursal_contabiliza, array("class"=>"sucursales"));
                        $co3  = HTML::campoOculto("cuentas[$fila]", $datos_item->id_plan_contable, array("class"=>"cuentas"));
                        $co4  = HTML::campoOculto("sentidos[$fila]", $datos_item->sentido, array("class"=>"sentidos"));
                        $co5  = HTML::campoOculto("abonos[$fila]", $saldo_abono, array("class"=>"abonos"));
                        $co6  = HTML::campoOculto("terceros[$fila]", $datos_item->id_tercero_cuenta, array("class"=>"terceros"));
                        $co7  = HTML::campoOculto("auxiliares[$fila]", $datos_item->id_auxiliar_contable, array("class"=>"auxiliares"));
                        $co8  = HTML::campoOculto("documentos_soportes[$fila]", $datos_item->id_tipo_documento_soporte, array("class"=>"documentos_soportes"));
                        $co9  = HTML::campoOculto("numeros_documentos_soportes[$fila]", $datos_item->numero_documento_soporte, array("class"=>"numeros_documentos_soportes"));
                        $co10 = HTML::campoOculto("documentos_bancarios[$fila]", $datos_item->id_tipo_documento_bancario, array("class"=>"documentos_bancarios"));
                        $co11 = HTML::campoOculto("numeros_documentos_bancarios[$fila]", $datos_item->numero_documento_bancario, array("class"=>"numeros_documentos_bancarios"));
                        $co12 = HTML::campoOculto("terceros1[$fila]", $datos_item->id_tercero_fiador1, array("class"=>"terceros1"));
                        $co13 = HTML::campoOculto("terceros2[$fila]", $datos_item->id_tercero_fiador2, array("class"=>"terceros2"));
                        $co14 = HTML::campoOculto("valores[$fila]", $datos_item->valor, array("class"=>"valores"));
                        $co15 = HTML::campoOculto("numero_comprobantes[$fila]", $datos->numero_comprobante, array("class"=>"numero_comprobantes"));

                        /*** Definir datos visibles del item en la tabla ***/
                        $sucursal               = SQL::obtenerValor("sucursales","nombre","id= '$datos_item->id_sucursal_contabiliza'");
                        $cuenta                 = SQL::obtenerValor("plan_contable","codigo_contable","id= '$datos_item->id_plan_contable'");
                        $tercero                = SQL::obtenerValor("seleccion_terceros", "nombre", "id = '$datos_item->id_tercero_cuenta'");
                        $tercero                = explode("|",$tercero);
                        $tercero                = $tercero[0];

                        /*** Validar si el item posee auxiliar contable definido ***/
                        if ($datos_item->id_auxiliar_contable != 0) {
                            $auxiliar   = SQL::obtenerValor("auxiliares_contables","descripcion","id= '$datos_item->id_auxiliar_contable'");
                            $opciones   .= '<span class="etiqueta">'.$textos["AUXILIAR_CONTABLE"].": </span>".$auxiliar."<br/><br/>";
                        }
                        /*** Validar si el item posee documento de soporte definido ***/
                        if ($datos_item->id_tipo_documento_soporte != 0) {
                            $documento_soporte  = SQL::obtenerValor("tipos_documentos", "descripcion", "id = '$datos_item->id_tipo_documento_soporte'");
                            $opciones           .= '<span class="etiqueta">'.$textos["TIPO_DOCUMENTO_SOPORTE"].": </span>".$documento_soporte." - ".$textos["NUM"]." ".$datos_item->numero_documento_soporte."<br/><br/>";
                        }
                        /*** Validar si el item posee documento banacario definido ***/
                        if ($datos_item->id_tipo_documento_bancario != 0) {
                            $documento_bancario = SQL::obtenerValor("tipos_documentos_bancarios", "descripcion", "id = '$datos_item->id_tipo_documento_bancario'");
                            $opciones           .= '<span class="etiqueta">'.$textos["TIPO_DOCUMENTO_BANCARIO"].": </span>".$documento_bancario." - ".$textos["NUM"]." ".$datos_item->numero_documento_bancario."<br/><br/>";
                        }
                        /*** Validar si el item posee tercero fiador 1 definido ***/
                        if ($datos_item->id_tercero_fiador1 != 0) {
                            $tercero_fiador1    = SQL::obtenerValor("seleccion_terceros", "nombre", "id = '$datos_item->id_tercero_fiador1'");
                            $tercero_fiador1    = explode("|",$tercero_fiador1);
                            $tercero_fiador1    = $tercero_fiador1[0];
                            $opciones           .= "<span class=\"etiqueta\">".$textos["TERCERO_FIADOR1"].": </span><span class=\"tercero1\">".$tercero_fiador1."</span><br/><br/>";
                        }
                        /*** Validar si el item posee tercero fiador 2 definido ***/
                        if ($datos_item->id_tercero_fiador2 != 0) {
                            $tercero_fiador2    = SQL::obtenerValor("seleccion_terceros", "nombre", "id = '$datos_item->id_tercero_fiador2'");
                            $tercero_fiador2    = explode("|",$tercero_fiador2);
                            $tercero_fiador2    = $tercero_fiador2[0];
                            $opciones           .= "<span class=\"etiqueta\">".$textos["TERCERO_FIADOR2"].": </span><span class=\"tercero2\">".$tercero_fiador2."</span><br/><br/>";
                        }
                        /*** Validar si el documento posee cheque generado y se esta mostrando la cuenta correspondiente a la cuenta bancaria ***/
                        if ($cuenta_contable_cheque == $datos_item->id_plan_contable) {
                            $opciones           .= '<span class="etiqueta">'.$textos["CHEQUE"]." ".$textos["NUM"].": </span>".$consecutivo_cheque."<br/><br/>";
                        }

                        /*** Validar si el item posee saldos por documento ***/
                        $saldos         = SQL::seleccionar( array("saldos_items_movimientos_contables"), array("*"), "id_item_movimiento = '$fila'");
                        $celda_valor    = "";
                        $botones        = $removedor.$editor;
                        $existen_abonos = 0;

                        if (SQL::filasDevueltas($saldos)) {
                            $orden          = 1;
                            /*** Validar si alguno de los saldos del item poseen abonos asignados ***/
                            $abono_asignado = SQL::seleccionar( array(  "a" => "saldos_items_movimientos_contables",
                                                                        "b" => "abonos_items_movimientos_contables"),
                                                                array(  "b.id"),
                                                                "b.id_saldo = a.id AND a.id_item_movimiento = $fila");
                            $existen_abonos = SQL::filasDevueltas($abono_asignado);
                            if ($existen_abonos) {
                                $botones    = "";
                                $opciones   .= "<span class=\"etiqueta\">".$textos["OTROS"].": </span>".$textos["ABONOS"]."<br/><br/>";
                            }
                            /*** Mostrar los saldos dependiendo de si alguno posee abonos asignados ***/

                            while ($datos_saldo = SQL::filaEnObjeto($saldos)) {
                                if ($existen_abonos) {
                                    $items_no_modificar .= "|".$datos_saldo->id_item_movimiento;
                                    $celda_valor        .=  "<span class=\"etiqueta\">".$textos["SALDO"]." $orden:</span>"
                                                            ."<span>".$datos_saldo->fecha_vencimiento." - $ ".number_format($datos_saldo->valor)."</span><br/><br/>";
                                } else {
                                    $celda_valor    .=  "<span class=\"etiqueta\">".$textos["SALDO"]." $orden:</span>"
                                                        ."<input id=\"fechas_".$fila."[$orden]\" class=\"selectorFecha\" type=\"text\" value=\"$datos_saldo->fecha_vencimiento\" maxlength=\"10\" size=\"8\" name=\"fechas_".$fila."[$orden]\" alt=\"\"/>"
                                                        ."<input id=\"saldos_".$fila."[$orden]\" class=\"saldos\" type=\"text\" value=\"$datos_saldo->valor\" maxlength=\"10\" size=\"8\" name=\"saldos_".$fila."[$orden]\" onblur=\"validarSaldo(this)\" alt=\"\"/><br/><br/>";
                                }

                                $orden++;
                            }
                            $opciones .= "<span class=\"etiqueta\">".$textos["TOTAL_SALDO"].": </span>".number_format($datos_item->valor_base1)."<br/><br/>";
                        } else {
                            $celda_valor = number_format($datos_item->valor);
                        }

                        /*** Ubicar valores debitos y creditos en la columna respectiva, y calcular totales del documento ***/
                        $valor_debito           = "";
                        $valor_credito          = "";
                        if ($datos_item->sentido == 1) {
                            $valor_debito       = $celda_valor;
                            $total_debito       = $total_debito + $datos_item->valor_base1;
                        } else {
                            $valor_credito      = $celda_valor;
                            $total_credito      = $total_credito + $datos_item->valor_base1;
                        }

                        /*** Agregar fila de datos a la tabla de items ***/
                        $lista_items[]          = array(    $fila,
                                                            $co1.$co2.$co3.$co4.$co5.$co6.$co7.$co8.$co9.$co10
                                                            .$co11.$co12.$co13.$co14.$botones,
                                                            $sucursal,
                                                            $cuenta,
                                                            $tercero,
                                                            $opciones,
                                                            $valor_debito,
                                                            $valor_credito
                                                    );
                    }
                }

                $comprobante            = SQL::obtenerValor("tipos_comprobantes", "descripcion", "id = '$datos->id_tipo_comprobante'");
                $numero_comprobante     = $datos->numero_comprobante;
                $tercero_movimiento     = SQL::obtenerValor("seleccion_terceros", "nombre", "id = '$datos->id_tercero'");
                $tercero_movimiento     = explode("|",$tercero_movimiento);
                $tercero_movimiento     = $tercero_movimiento[0];

                $consecutivo_documento  = $datos_documento->numero_consecutivo;
                $documento_movimiento   = SQL::obtenerValor("tipos_documentos", "descripcion", "id = '$datos_documento->id_tipo_documento'");

                $fila++;

                $sentidos = array(
                    "1" => $textos["DEBITO"],
                    "2" => $textos["CREDITO"]
                );

                /*** Definición de pestañas general ***/
                $formularios["PESTANA_GENERAL"] = array(
                    array(
                        HTML::listaSeleccionSimple("sucursal_genera", $textos["SUCURSAL_GENERA"], HTML::generarDatosLista("sucursales", "id", "nombre","id != 0"), $datos->id_sucursal_genera, array("title" => $textos["AYUDA_SUCURSAL_GENERA"],"onChange"=>"verificarPeriodoContable(this.value, fecha_contabilizacion.value, '$modulo')"))
                       .HTML::campoOculto("id_movimiento", $datos->id)
                    ), array(
                        HTML::campoTextoCorto("*fecha_contabilizacion", $textos["FECHA_CONTABILIZACION"], 10, 10, $datos->fecha_contabilizacion, array("title" => $textos["AYUDA_FECHA_CONTABILIZACION"], "class" => "selectorFecha", "onChange"=>"verificarPeriodoContable(sucursal_genera.value, this.value, '$modulo')"))
                    ), array(
                        HTML::campoTextoCorto("selector1", $textos["TERCERO"], 40, 255, $tercero_movimiento, array("title" => $textos["AYUDA_TERCERO"], "class" => "autocompletable"))
                        .HTML::campoOculto("id_tercero", "")
                    ), array(
                        HTML::listaSeleccionSimple("*tipo_comprobante", $textos["TIPO_COMPROBANTE"], HTML::generarDatosLista("tipos_comprobantes", "id", "descripcion", "id != 0"), $datos->id_tipo_comprobante, array("title" => $textos["AYUDA_TIPO_COMPROBANTE"])),
                        HTML::campoTextoCorto("*numero_comprobante", $textos["NUMERO_COMPROBANTE"], 10, 10, $numero_comprobante, array("title" => $textos["AYUDA_NUMERO_COMPROBANTE"],"onchange" => "activarPestana(this)")),
                        HTML::campoOculto($numero_comprobante, "")
                    ), array(
                        HTML::listaSeleccionSimple("*tipo_documento", $textos["TIPO_DOCUMENTO"], HTML::generarDatosLista("tipos_documentos", "id", "descripcion", "id != 0"), $datos_documento->id_tipo_documento, array("title" => $textos["AYUDA_TIPO_DOCUMENTO"],"onchange" => "recargarDatosDocumento()")),
                        HTML::campoTextoCorto("*consecutivo_documento", $textos["CONSECUTIVO_DOCUMENTO"], 10, 10, $consecutivo_documento, array("title" => $textos["AYUDA_CONSECUTIVO_DOCUMENTO"])),
                        HTML::campoTextoCorto("*fecha_documento", $textos["FECHA_DOCUMENTO"], 10, 10, $datos->fecha_documento, array("title" => $textos["AYUDA_FECHA_DOCUMENTO"], "class" => "selectorFecha"))
                    ), array(
                        HTML::campoTextoLargo("observaciones", $textos["OBSERVACIONES"], 4, 60, $datos->observaciones, array("title" => $textos["AYUDA_OBSERVACIONES"]))
                    ),
                    $fila_cuenta_bancaria,
                    $fila_cheque,
                );
                /*** Definición de pestaña movimientos ***/
                $formularios["PESTANA_MOVIMIENTOS"] = array(
                    array(
                        HTML::listaSeleccionSimple("sucursal_contabiliza", $textos["SUCURSAL_CONTABILIZA"], HTML::generarDatosLista("sucursales", "id", "nombre", "id != 0"), $sucursal, array("onchange" => "recargarDatosDocumento()", "title" => $textos["AYUDA_SUCURSAL_CONTABILIZA"]))
                    ), array(
                        HTML::campoTextoCorto("*selector2", $textos["CUENTA"], 40, 255, "", array("title" => $textos["AYUDA_CUENTA"], "class" => "autocompletable"))
                        .HTML::campoOculto("id_cuenta", ""),
                        HTML::campoOculto("descripcion_selector2", ""),
                        HTML::listaSeleccionSimple("sentido", $textos["SENTIDO"], $sentidos, "", array("onChange" => "recargarDatos( \$('#id_cuenta').val(), 'selector2')")),
                        HTML::listaSeleccionSimple("documento_saldo", $textos["DOCUMENTO_SALDO"], array("0" => " "), "", array("class" => "oculto", "onchange" => "cambiarValorAbono()"))
                    ), array(
                        HTML::campoTextoCorto("*selector3", $textos["TERCERO_CUENTA"], 40, 255, "", array("title" => $textos["AYUDA_TERCERO_CUENTA"], "class" => "autocompletable oculto"))
                        .HTML::campoOculto("id_tercero_cuenta", "")
                    ), array(
                        HTML::listaSeleccionSimple("auxiliar_contable", $textos["AUXILIAR_CONTABLE"], array("0" => " "), "", array("class" => "oculto"))
                    ), array(
                        HTML::listaSeleccionSimple("tipo_documento_soporte",  $textos["TIPO_DOCUMENTO_SOPORTE"], HTML::generarDatosLista("tipos_documentos", "id", "descripcion","id != 0"),"",array("title" => $textos["AYUDA_TIPO_DOCUMENTO_SOPORTE"],"class" => "oculto")),
                        HTML::campoTextoCorto("numero_documento_soporte", $textos["NUMERO_DOCUMENTO_SOPORTE"], 10, 10, "", array("title" => $textos["AYUDA_NUMERO_DOCUMENTO_SOPORTE"],"class" => "oculto"))
                    ), array(
                        HTML::listaSeleccionSimple("tipo_documento_bancario", $textos["TIPO_DOCUMENTO_BANCARIO"], HTML::generarDatosLista("tipos_documentos_bancarios", "id", "descripcion"), "", array("class" => "oculto")),
                        HTML::campoTextoCorto("numero_documento_bancario", $textos["NUMERO_DOCUMENTO_BANCARIO"], 10, 10, "", array("title" => $textos["AYUDA_NUMERO_DOCUMENTO_BANCARIO"], "class" => "oculto"))
                    ), array(
                        HTML::campoTextoCorto("selector4", $textos["TERCERO_FIADOR1"], 40, 255, "", array("title" => $textos["AYUDA_TERCERO_FIADOR1"], "class" => "autocompletable oculto"))
                        .HTML::campoOculto("id_tercero_fiador1", ""),
                        HTML::campoTextoCorto("selector5", $textos["TERCERO_FIADOR2"], 40, 255, "", array("title" => $textos["AYUDA_TERCERO_FIADOR2"], "class" => "autocompletable oculto"))
                        .HTML::campoOculto("id_tercero_fiador2", "")
                    ), array(
                        HTML::campoTextoCorto("valor", $textos["VALOR"], 10, 10, "0", array("title" => $textos["AYUDA_VALOR"], "onblur" => "validarValor()"))
                        .HTML::campoOculto("valor_maximo", "0"),
                        HTML::campoTextoCorto("cantidad_vencimientos", $textos["CANTIDAD_VENCIMIENTOS"], 2, 10, "0", array("title" => $textos["AYUDA_VALOR"], "class" => "oculto")),
                        HTML::campoTextoCorto("intervalo", $textos["INTERVALO"], 3, 10, "1", array("title" => $textos["AYUDA_INTERVALO"], "class" => "oculto")),
                        HTML::boton("botonAgregar", $textos["AGREGAR"], "agregarItem();", "adicionar"),
                        HTML::contenedor(HTML::boton("botonRemover", "", "removerItem(this);", "eliminar", array("title" => $textos["ELIMINAR_ITEM"])), array("id" => "removedor", "style" => "display: none")),
                        HTML::contenedor(HTML::boton("botonEditar", "", "editarItem(this);", "modificar", array("title" => $textos["MODIFICAR_ITEM"])), array("id" => "editor", "style" => "display: none"))
                    ), array(
                        HTML::mostrarDato("total_debito", $textos["TOTAL_DEBITO"], number_format($total_debito)),
                        HTML::mostrarDato("total_credito", $textos["TOTAL_CREDITO"], number_format($total_credito))
                        .HTML::campoOculto("consecutivo_fila", $fila)
                        .HTML::campoOculto("items_no_modificar", $items_no_modificar)
                    ), array(
                            HTML::generarTabla( array("id","","SUCURSAL","CUENTA","TERCERO_CUENTA","OPCIONES","DEBITOS","CREDITOS"),
                                            $lista_items,
                                            array("C","I","I","I","I","D","D"),
                                            "listaItems",
                                            false,false)
                    ), array(
                        HTML::campoOculto("ingresar_tercero",$textos["TERCERO_VACIO"])
                    ), array(
                        HTML::campoOculto("ingresar_numero_comprobante",$textos["NUMERO_COMPROBANTE_VACIO"])
                    ), array(
                        HTML::campoOculto("ingresar_documento_soporte",$textos["DOCUMENTO_SOPORTE_VACIO"])
                    ), array(
                        HTML::campoOculto("error_cuenta",$textos["ERROR_CUENTA"])
                    ), array(
                        HTML::campoOculto("ingresar_cuenta",$textos["INGRESAR_CUENTA"])
                    ), array(
                        HTML::campoOculto("ingresar_valor",$textos["VALOR_VACIO"]),
                    ), array(
                        HTML::campoOculto("maneja_saldos", "0"),
                    ), array(
                        HTML::campoOculto("maneja_tercero", "0"),
                    )
                );
                /*** Definición de botones ***/
                $botones = array(
                    HTML::boton("botonAceptar", $textos["ACEPTAR"], "modificarItem('$datos_documento->id_registro_tabla');", "aceptar")
                );

                $contenido = HTML::generarPestanas($formularios, $botones);

            }
        }
    }

    /*** Enviar datos para la generación del formulario al script que originó la petición ***/
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

/*** Validar los datos provenientes del formulario ***/
} elseif (!empty($url_validar)) {
    /*** Validar articulo a agregar no exista en la lista ***
    HTTP::enviarJSON($textos["ERROR_ARTICULO_DOBLE"]);*/

/*** Modificar el elemento seleccionado ***/
} elseif (!empty($forma_procesar)) {
    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_MODIFICADO"];

    $cuenta_cheque = explode("|", $forma_cuenta_bancaria);
    $cuenta_cheque = $cuenta_cheque[1];

    if (($cuenta_cheque != 0) && (!in_array($cuenta_cheque, $forma_cuentas))) {
        $error = true;
        $mensaje = $textos["ERROR_CUENTA_CHEQUE"];

    } elseif (!isset($forma_movimientos)) {
        $error = true;
        $mensaje = $textos["ERROR_DATOS_INCOMPLETOS"];

    } else {

        $datos = array (
            "fecha_transaccion"     => date("Y-m-d"),
            "fecha_contabilizacion" => $forma_fecha_contabilizacion,
            "observaciones"         => $forma_observaciones
        );

        $id_movimiento_contable = $forma_id;
        $modificar = SQL::modificar("movimientos_contables", $datos, "id='$id_movimiento_contable'");

        /*** Error de modificación ***/
        if (!$modificar) {
            $error   = true;
            $mensaje = $textos["ERROR_MODIFICAR_ITEM"];
        } else {

            /*** Eliminar items que pudieron ser modificados en el formulario ***/
            $forma_items_no_modificar   = explode("|", $forma_items_no_modificar);
            $items_no_modificar         = implode(",", $forma_items_no_modificar);

            $consulta_items             = SQL::seleccionar( array("items_movimientos_contables"), array("id"), "id_movimiento='$id_movimiento_contable' AND id NOT IN ($items_no_modificar)");
            if (SQL::filasDevueltas($consulta_items)) {
                while ($item_borrar = SQL::filaEnObjeto($consulta_items)) {
                    $eliminar_abonos    = SQL::eliminar("abonos_items_movimientos_contables", "id_item_movimiento='$item_borrar->id'");
                    $eliminar_saldos    = SQL::eliminar("saldos_items_movimientos_contables", "id_item_movimiento='$item_borrar->id'");
                    $eliminar_items     = SQL::eliminar("items_movimientos_contables", "id='$item_borrar->id'");
                }
            }

            /*** Guardar los items del movimientos contable ***/
            foreach ($forma_movimientos as $id_movimiento) {
                if (!in_array($id_movimiento, $forma_items_no_modificar)) {

                    $datos = array(
                        "id_movimiento"                 => $forma_id,
                        "id_sucursal_contabiliza"       => $forma_sucursales[$id_movimiento],
                        "id_plan_contable"              => $forma_cuentas[$id_movimiento],
                        "id_auxiliar_contable"          => $forma_auxiliares[$id_movimiento],
                        "id_tercero_cuenta"             => $forma_terceros[$id_movimiento],
                        "sentido"                       => $forma_sentidos[$id_movimiento],
                        "valor"                         => $forma_valores[$id_movimiento],
                        "valor_base1"                   => $forma_valores[$id_movimiento],
                        "valor_base2"                   => "0",
                        "id_tipo_documento_soporte"     => $forma_documentos_soportes[$id_movimiento],
                        "numero_documento_soporte"      => $forma_numeros_documentos_soportes[$id_movimiento],
                        "id_tipo_documento_bancario"    => $forma_documentos_bancarios[$id_movimiento],
                        "numero_documento_bancario"     => $forma_numeros_documentos_bancarios[$id_movimiento],
                        "id_tercero_fiador1"            => $forma_terceros1[$id_movimiento],
                        "id_tercero_fiador2"            => $forma_terceros2[$id_movimiento]
                    );

                    $insertar   = SQL::insertar("items_movimientos_contables", $datos);
                    $id_item    = SQL::$ultimoId;

                    /*** Verificar si el item corresponde a un abono para un saldo por documento ***/
                    if ($forma_abonos[$id_movimiento] != 0 ) {
                        $datos = array(
                            "id_item_movimiento"    => $id_item,
                            "id_saldo"              => $forma_abonos[$id_movimiento],
                            "valor"                 => $forma_valores[$id_movimiento]
                        );
                        $insertar = SQL::insertar("abonos_items_movimientos_contables", $datos);
                    }

                    /*** Verificar si el item generó saldos por documento ***/
                    $saldos = "forma_saldos_".$id_movimiento;
                    $fechas = "forma_fechas_".$id_movimiento;
                    if (isset($$saldos) && isset($$fechas)) {
                        foreach ($$saldos as $id => $vencimiento) {

                            $datos = array(
                                "id_item_movimiento"    => $id_item,
                                "fecha_vencimiento"     => ${$fechas}[$id],
                                "valor"                 => ${$saldos}[$id]
                            );
                            $insertar = SQL::insertar("saldos_items_movimientos_contables", $datos);
                        }
                    }

                    /*** Verificar si el item corresponde al cheque generado ***/
                    if ($forma_cuenta_bancaria != 0) {
                        if ($forma_cuentas[$id_movimiento] == $forma_cuenta_bancaria[1]) {

                            $consecutivo_cheque = SQL::obtenerValor("consecutivo_cheques", "MAX(consecutivo)", "id_cuenta = '$forma_cuenta_bancaria[0]'");
                            if ($consecutivo_cheque == '') {
                                $consecutivo_cheque = 1;
                            } else {
                                $consecutivo_cheque++;
                            }
                            $datos = array(
                                    "id_cuenta_bancaria"    => $forma_cuenta_bancaria[0],
                                    "id_registro_tabla"     => $id_item,
                                    "id_tabla"              => "00116",
                                    "numero_consecutivo"    => $consecutivo_cheque
                                );
                            $insertar = SQL::insertar("consecutivo_cheques", $datos);
                        }
                    }

                    /*** Error de insercón ***/
                    if (!$insertar) {
                        $error   = true;
                        $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
                        $mensaje = mysql_error();
                        exit;
                    }
                }
            }
        }
    }

    /*** Enviar datos con la respuesta del proceso al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>


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
* de APTITUD PARA UN PROPÓSITO DETERMINADO. Consulte los detalles de
* la Licencia Pública General GNU para obtener una información más
* detallada.
*
* Debería haber recibido una copia de la Licencia Pública General GNU
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
**/

// Devolver datos para autocompletar la busqueda
if (isset($url_completar)) {
    if ($url_item == "selector1" || $url_item == "selector3" || $url_item == "selector4" || $url_item == "selector5") {
    echo SQL::datosAutoCompletar("seleccion_terceros", $url_q);
    } if ($url_item == "selector2") {
        echo SQL::datosAutoCompletar("seleccion_plan_contable_transacciones", $url_q);
    }
    exit;
}

// Devolver datos para recargar informacion requerida
if (isset($url_recargarDatosDocumento)) {

    $datos = array();
    // Obtener consecutivo de documento si tiene manejo automatico
    $manejo         = SQL::obtenerValor("tipos_documentos", "manejo_automatico", "codigo = '".$url_documento."'");
    $datos["manejo"]   = $manejo;
    if ($manejo == '2') {//Manejo autimatico
        $consecutivo_documento = SQL::obtenerValor("consecutivo_documentos", "MAX(consecutivo)", "codigo_sucursal = '".$url_sucursal."' AND codigo_tipo_documento = '".$url_documento."' AND fecha_registro IN (SELECT MAX(fecha_registro) FROM job_consecutivo_documentos WHERE codigo_sucursal = '".$url_sucursal."' AND codigo_tipo_documento = '".$url_documento."')");
        if (!$consecutivo_documento) {
            $consecutivo_documento = 1;
        } else {
            $consecutivo_documento++;
        }
        $datos["consecutivo_documento"]   = $consecutivo_documento;
    }else if($manejo == '3') {//Consecutivo por mes
        $consecutivo_documento = SQL::obtenerValor("consecutivo_documentos", "MAX(consecutivo)", "codigo_sucursal = '".$url_sucursal."' AND codigo_tipo_documento = '".$url_documento."' AND fecha_registro IN (SELECT MAX(fecha_registro) FROM job_consecutivo_documentos WHERE codigo_sucursal = '".$url_sucursal."' AND codigo_tipo_documento = '".$url_documento."')");
        if (!$consecutivo_documento) {
            $consecutivo_documento = 1;
        } else {
            $consecutivo_documento++;
        }
        $datos["consecutivo_documento"]   = $consecutivo_documento;
    } else {
        $datos["consecutivo_documento"]   = 0;
    }

    // Obtener cuentas bancarias si genera cheques
    $cheques    = SQL::obtenerValor("tipos_documentos", "genera_cheque", "codigo = '".$url_documento."'");
    $datos["genera_cheque"] = $cheques;
    if ($cheques == '1') {
        $primer_cuenta  = false;
        $consulta       = SQL::seleccionar(array("buscador_cuentas_bancarias"), array("*"),"id_sucursal = '".$url_sucursal."' AND id_documento = '".$url_documento."'");
        if (SQL::filasDevueltas($consulta)) {
            while ($datos_cuenta = SQL::filaEnObjeto($consulta)) {
                if ($primer_cuenta == false) {
                    $primer_cuenta = $datos_cuenta->id;
                }
                $llave_cuenta   = explode('|',$datos_cuenta->id);
                $id_plan_cuenta = SQL::obtenerValor("cuentas_bancarias", "codigo_plan_contable", "codigo_sucursal = '".$llave_cuenta[0]."' AND codigo_tipo_documento = '".$llave_cuenta[1]."' AND codigo_banco = '".$llave_cuenta[6]."' AND numero = '".$llave_cuenta[7]."' AND codigo_sucursal_banco = '".$llave_cuenta[2]."'");
                $datos[$datos_cuenta->id."|".$id_plan_cuenta] = $datos_cuenta->BANCO." - No. ".$datos_cuenta->NUMERO;
            }
            $llave_cuenta   = explode('|',$primer_cuenta);
            $consecutivo_cheque = SQL::obtenerValor("consecutivo_cheques", "MAX(consecutivo)", "codigo_tipo_documento = '".$llave_cuenta[1]."' AND codigo_banco = '".$llave_cuenta[6]."' AND numero = '".$llave_cuenta[7]."'");
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

if (isset($url_recargarDatosCuenta)) {

    $datos = array();
    // Obtener datos de la cuenta
    $consulta       = SQL::seleccionar(array("plan_contable"),array("*"),"codigo_contable = '".$url_id."'");
    $datos_cuenta   = SQL::filaEnObjeto($consulta);

    $datos["saldo"]               = $datos_cuenta->maneja_saldos;
    $datos["sentido"]             = $datos_cuenta->naturaleza_cuenta;
    $datos["flujo"]               = $datos_cuenta->flujo_efectivo;
    $datos["tercero"]             = $datos_cuenta->maneja_tercero;
    $datos["anexo_contable"]      = $datos_cuenta->codigo_anexo_contable;

    $tasa1 = SQL::obtenerValor( "vigencia_tasas", "porcentaje", "codigo_tasa = '".$datos_cuenta->codigo_tasa_aplicar_1."' AND fecha <= '".$url_fecha."' AND codigo_tasa != '000' ORDER BY fecha DESC LIMIT 0,1");
    if (!$tasa1) {
        $datos["tasa1"] = 0;
    } else {
        $datos["tasa1"] = $tasa1;
    }

    $tasa2 = SQL::obtenerValor( "vigencia_tasas", "porcentaje", "codigo_tasa = '".$datos_cuenta->codigo_tasa_aplicar_2."' AND fecha <= '".$url_fecha."' AND codigo_tasa != '000' ORDER BY fecha DESC LIMIT 0,1");
    if (!$tasa2) {
        $datos["tasa2"] = 0;
    } else {
        $datos["tasa2"] = $tasa2;
    }

    $consulta = SQL::seleccionar(array("seleccion_auxiliares_contables"), array("id","descripcion"), "anexo_contable = '".$datos_cuenta->codigo_anexo_contable."'");
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
    // Consultar documentos del proveedor con saldos pendientes
    $consulta = SQL::seleccionar(array("totalizador_saldos_movimientos_contables"), array("*, SUM(abono) AS total_abono"), "id_tercero = '".$url_id_tercero."' AND id_cuenta = '".$url_id_cuenta."'", "id_saldo");
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
                $documento  = SQL::obtenerValor("tipos_documentos", "descripcion", "codigo = '".$datos_saldo->id_documento."'");
                $diferencia = $datos_saldo->saldo - $datos_saldo->total_abono;
                $datos[$datos_saldo->id_consecutivo."/".$datos_saldo->id_saldo."/".$datos_saldo->consecutivo."/".$diferencia."/".$documento] = "No. ".$datos_saldo->consecutivo.": Saldo $i $".number_format($diferencia);
            }
            $documento  = $datos_saldo->id_consecutivo;
        }
    }
    HTTP::enviarJSON($datos);
    exit;
}

if(isset($url_calcular_periodo_contable)) {
    $datos    = array();
    $estado   = 0;
    $consulta = SQL::seleccionar(array("periodos_contables_modulos"),array("*"),"codigo_sucursal='".$url_sucursal."' AND ('".$url_fecha."' BETWEEN fecha_inicio AND fecha_fin) AND id_modulo='".$url_modulo."'");

    if(SQL::filasDevueltas($consulta)){
        $estado = 1;
    }

    $datos[] = $estado;
    $datos[] = $textos["MENSAJE_PERIODO_CONTABLE"];

    HTTP::enviarJSON($datos);
    exit;
}

if(isset($url_recargar_consecutivo_cheque)){
    $llave_cuenta   = explode('|',$url_cuenta);
    $consecutivo_cheque = SQL::obtenerValor("consecutivo_cheques", "MAX(consecutivo)", "codigo_tipo_documento = '".$llave_cuenta[1]."' AND codigo_banco = '".$llave_cuenta[6]."' AND numero = '".$llave_cuenta[7]."'");
    if (!$consecutivo_cheque) {
        $consecutivo_cheque = 1;
    } else {
        $consecutivo_cheque++;
    }
    $cuenta  = SQL::obtenerValor("seleccion_plan_contable_transacciones","SUBSTRING_INDEX(cuenta,'|',1)","id = '".$llave_cuenta[8]."'");
    $sentido = SQL::obtenerValor("plan_contable","naturaleza_cuenta","codigo_contable = '".$llave_cuenta[8]."'");
    /*if($sentido=='D'){
        $sentido = '1';
    }else{
        $sentido = '2';
    }*/

    unset($llave_cuenta[8]);

    $llave = implode('|',$llave_cuenta);

    $auxiliar = SQL::obtenerValor("buscador_cuentas_bancarias","id_auxiliar","id = '".$llave."'");

    $descripcion = SQL::obtenerValor("seleccion_auxiliares_contables","descripcion","id = '".$auxiliar."'");

    $datos = array($consecutivo_cheque,$cuenta,$auxiliar,$descripcion,$llave,$sentido);
    HTTP::enviarJSON($datos);
    exit;
}

if(isset($url_traer_sentido)){
    $sentido = SQL::obtenerValor("plan_contable","naturaleza_cuenta","codigo_contable =".$url_cuenta);
    HTTP::enviarJSON($sentido);
    exit;
}


// Generar el formulario para la captura de datos
if (!empty($url_generar)) {

    $consulta         = SQL::seleccionar(array("sucursales"), array("*"), "codigo != '0'");
    $sucursales_ver   = SQL::filasDevueltas($consulta);

    $consulta         = SQL::seleccionar(array("tipos_comprobantes"), array("*"), "codigo != '0'");
    $comprobantes_ver = SQL::filasDevueltas($consulta);

    $consulta         = SQL::seleccionar(array("tipos_documentos"), array("*"), "codigo != '0'");
    $documentos_ver   = SQL::filasDevueltas($consulta);

    $consulta         = SQL::seleccionar(array("plan_contable"), array("*"), "clase_cuenta = '1' AND codigo_contable != ''");
    $cuentas_ver      = SQL::filasDevueltas($consulta);

    $consulta         = SQL::seleccionar(array("tablas"), array("*"), "nombre_tabla = 'movimientos_contables'");
    $tablas_ver       = SQL::filasDevueltas($consulta);

    if($sucursales_ver == 0 || $comprobantes_ver == 0 || $documentos_ver == 0 || $cuentas_ver == 0 || $tablas_ver == 0){

        $mensaje=$textos["ERROR_TABLAS_VACIAS"];
        $listaTablas = array();

        if($sucursales_ver==0){
            $listaTablas[] = $textos["ERROR_TABLA_VACIA_SUCURSALES"];
        }

        if($comprobantes_ver==0){
            $listaTablas[] = $textos["ERROR_TABLA_VACIA_TIPOS_COMPROBANTES"];
        }

        if($documentos_ver==0){
            $listaTablas[] = $textos["ERROR_TABLA_VACIA_TIPOS_DOCUMENTOS"];
        }

        if($cuentas_ver==0){
            $listaTablas[] = $textos["ERROR_TABLA_VACIA_TIPOS_CUENTAS"];
        }

        if($tablas_ver==0){
            $listaTablas[] = $textos["ERROR_TABLA_VACIA_TABLAS"];
        }

        $tablas    = implode("",$listaTablas);
        $mensaje  .= $tablas;
        $error     = $mensaje;
        $titulo    = "";
        $contenido = "";

    }else{

        $error  = "";
        $titulo = $componente->nombre;

        $modulo = SQL::obtenerValor("componentes", "id_modulo", "id = '".$componente->id."'");

        // Verificar si el modulo del componente actual esta habilitado en el periodo contable actual
        $sentidos = array(
            "D" => $textos["DEBITO"],
            "C" => $textos["CREDITO"]
        );

        // Obtener consecutivo para documento seleccionado por defecto
        $consecutivo    = "";
        $documento      = SQL::obtenerValor("tipos_documentos", "codigo", "1 ORDER BY descripcion LIMIT 1");

        $consulta = SQL::seleccionar(array("sucursales"),array("*"),"codigo > 0","","nombre");

        if ($sesion_usuario == $datosGlobales["usuarioMaestro"]){
            while ($datos = SQL::filaEnObjeto($consulta)){
                $sucursales[$datos->codigo] = $datos->nombre;
            }
        } else {
            /*** Obtener lista de sucursales para selección ***/
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
                        AND b.id_componente = '".$componente->id."'";

            $consulta = SQL::seleccionar($tablas, $columnas, $condicion);

            if (SQL::filasDevueltas($consulta)) {
                while ($datos = SQL::filaEnObjeto($consulta)) {
                    $sucursales[$datos->codigo] = $datos->nombre;
                }
            }
        }

        $sucursal = array_shift(array_keys($sucursales));

        $consecutivo_documento = "";
        $read                  = "";
        $comprobante           = SQL::obtenerValor("tipos_documentos", "codigo_comprobante", "codigo = '".$documento."'");

        $cuentas_bancarias[0]   = "";
        $consecutivo_cheque     = "";
        $oculto                 = "oculto";
        $banco_disabled         = "disabled";

        $periodo   = '0';
        $fecha_con = date("Y-m-d");
        $consulta  = SQL::seleccionar(array("periodos_contables_modulos"),array("*"),"codigo_sucursal='".$sucursal."' AND ('".$fecha_con."' BETWEEN fecha_inicio AND fecha_fin) AND id_modulo='".$modulo."'");

        if(SQL::filasDevueltas($consulta)){
            $periodo = '1';
        }


        // Definición de pestañas general
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::listaSeleccionSimple("sucursal_genera", $textos["SUCURSAL_GENERA"], $sucursales, $sucursal, array("title" => $textos["AYUDA_SUCURSAL_GENERA"], "onChange" => "verificarPeriodoContable()"))
               .HTML::campoOculto("sucursal_genera2", $sucursal)
            ), array(
                HTML::campoTextoCorto("*fecha_contabilizacion", $textos["FECHA_CONTABILIZACION"], 10, 10, date("Y-m-d"), array("title" => $textos["AYUDA_FECHA_CONTABILIZACION"], "class" => "selectorFecha", "onChange"=>"verificarPeriodoContable()"))
            ), array(
                HTML::campoTextoCorto("selector1", $textos["TERCERO"], 40, 255, "", array("title" => $textos["AYUDA_TERCERO"], "class" => "autocompletable","onKeyUp" => "limpiarTercero()"))
               .HTML::campoOculto("id_tercero", "0")
            ), array(
                HTML::listaSeleccionSimple("*tipo_comprobante", $textos["TIPO_COMPROBANTE"], HTML::generarDatosLista("tipos_comprobantes", "codigo", "descripcion", "codigo != 0"), $comprobante,array("title" => $textos["AYUDA_TIPO_COMPROBANTE"])),
                HTML::campoTextoCorto("*numero_comprobante", $textos["NUMERO_COMPROBANTE"], 10, 10, "", array("title" => $textos["AYUDA_NUMERO_COMPROBANTE"]))
            ), array(
                HTML::listaSeleccionSimple("*tipo_documento", $textos["TIPO_DOCUMENTO"], HTML::generarDatosLista("tipos_documentos", "codigo", "descripcion"),$documento, array("title" => $textos["AYUDA_TIPO_DOCUMENTO"],"onchange" => "recargarDatosDocumento()"))
               .HTML::campoOculto("tipo_documento2", $documento),
                HTML::campoTextoCorto("*consecutivo_documento", $textos["CONSECUTIVO_DOCUMENTO"], 10, 10, $consecutivo_documento, array("title" => $textos["AYUDA_CONSECUTIVO_DOCUMENTO"],$read => $read)),
                HTML::campoTextoCorto("*fecha_documento", $textos["FECHA_DOCUMENTO"], 10, 10, date("Y-m-d"), array("title" => $textos["AYUDA_FECHA_DOCUMENTO"], "class" => "selectorFecha"))
            ), array(
                HTML::listaSeleccionSimple("cuenta_bancaria", $textos["CUENTA_BANCARIA"], $cuentas_bancarias, "", array("class" => $oculto,$banco_disabled => $banco_disabled, "onChange" => "consecutivoCheque();"))
               .HTML::campoOculto("cuenta_bancaria2", ""),
                HTML::campoTextoCorto("*consecutivo_cheque", $textos["CONSECUTIVO_CHEQUE"], 10, 10, $consecutivo_cheque, array("title" => $textos["AYUDA_CONSECUTIVO_CHEQUE"], "class" => $oculto, "readonly" => "readonly",$banco_disabled => $banco_disabled)),
            ), array(
                HTML::campoTextoLargo("observaciones", $textos["OBSERVACIONES"], 4, 60, "", array("title" => $textos["AYUDA_OBSERVACIONES"]))
            )
        );

        // Definición de pestaña movimientos
        $funciones["PESTANA_MOVIMIENTOS"]   = "activarPestana()";
        $formularios["PESTANA_MOVIMIENTOS"] = array(
            array(
                HTML::listaSeleccionSimple("sucursal_contabiliza", $textos["SUCURSAL_CONTABILIZA"], $sucursales, $sucursal, array("onchange" => "recargarDatosDocumento()", "title" => $textos["AYUDA_SUCURSAL_CONTABILIZA"]))
            ), array(
                HTML::campoTextoCorto("*selector2", $textos["CUENTA"], 40, 255, "", array("title" => $textos["AYUDA_CUENTA"], "class" => "autocompletable","onKeyUp" => "limpiarCuenta()"))
               .HTML::campoOculto("id_cuenta", ""),
                HTML::campoOculto("sentido_cuenta", ""),
                HTML::listaSeleccionSimple("sentido", $textos["SENTIDO"], $sentidos, "", array("title" => $textos["AYUDA_SENTIDO"],"onChange" => "recargarDatosCuenta()")),
                HTML::listaSeleccionSimple("documento_saldo", $textos["DOCUMENTO_SALDO"], array("0" => " "), "", array("title" => $textos["AYUDA_DOCUMENTO_SALDO"],"class" => "oculto", "onchange" => "cambiarValorAbono()", "disabled" => "disabled"))
            ), array(
                HTML::campoTextoCorto("*selector3", $textos["TERCERO_CUENTA"], 40, 255, "", array("title" => $textos["AYUDA_TERCERO_CUENTA"], "class" => "autocompletable oculto", "disabled" => "disabled","onKeyUp" => "limpiarTerceroCuenta()"))
               .HTML::campoOculto("id_tercero_cuenta", ""),
                HTML::campoOculto("descripcion_selector3", "")
            ), array(
                HTML::campoTextoCorto("selector4", $textos["TERCERO_FIADOR1"], 40, 255, "", array("title" => $textos["AYUDA_TERCERO_FIADOR1"], "class" => "autocompletable oculto"))
               .HTML::campoOculto("id_tercero_fiador1", ""),
                HTML::campoTextoCorto("selector5", $textos["TERCERO_FIADOR2"], 40, 255, "", array("title" => $textos["AYUDA_TERCERO_FIADOR2"], "class" => "autocompletable oculto"))
                .HTML::campoOculto("id_tercero_fiador2", "")
            ), array(
                HTML::listaSeleccionSimple("*tipo_documento_soporte", $textos["TIPO_DOCUMENTO_SOPORTE"], HTML::generarDatosLista("tipos_documentos", "codigo", "descripcion","codigo != 0"),"",array("title" => $textos["AYUDA_TIPO_DOCUMENTO_SOPORTE"],"class" => "oculto", "disabled" => "disabled")),
                HTML::campoTextoCorto("*numero_documento_soporte", $textos["NUMERO_DOCUMENTO_SOPORTE"], 10, 10, "", array("title" => $textos["AYUDA_NUMERO_DOCUMENTO_SOPORTE"],"class" => "oculto", "disabled" => "disabled"))
            ), array(
                HTML::listaSeleccionSimple("*auxiliar_contable", $textos["AUXILIAR_CONTABLE"], array("0||0" => " "), "", array("class" => "oculto", "disabled" => "disabled"))
            ), array(
                HTML::listaSeleccionSimple("tipo_documento_bancario", $textos["TIPO_DOCUMENTO_BANCARIO"], HTML::generarDatosLista("tipos_documentos_bancarios", "codigo", "descripcion","codigo != 0"), "", array("class" => "oculto", "disabled" => "disabled")),
                HTML::campoTextoCorto("numero_documento_bancario", $textos["NUMERO_DOCUMENTO_BANCARIO"], 10, 10, "", array("title" => $textos["AYUDA_NUMERO_DOCUMENTO_BANCARIO"], "class" => "oculto", "disabled" => "disabled"))
            ), array(
                HTML::campoTextoCorto("*valor", $textos["VALOR"], 10, 10, "0", array("title" => $textos["AYUDA_VALOR"], "onKeyUp" => "validarValor();", "onKeyPress" => "return campoDecimal(event)", "onFocus"=>"inicialize(this)")),
                HTML::campoTextoCorto("valor_base1", $textos["VALOR_BASE1"], 10, 10, "0", array("title" => $textos["AYUDA_VALOR_BASE1"], "class" => "oculto", "onfocus"=>"inicialize(this)", "disabled" => "disabled")),
                HTML::campoTextoCorto("valor_base2", $textos["VALOR_BASE2"], 10, 10, "0", array("title" => $textos["AYUDA_VALOR_BASE2"], "class" => "oculto", "disabled" => "disabled")),
                HTML::campoTextoCorto("cantidad_vencimientos", $textos["CANTIDAD_VENCIMIENTOS"], 3, 2, "0", array("title" => $textos["AYUDA_VALOR"], "class" => "oculto","onKeyPress" => "return campoEntero(event)", "disabled" => "disabled")),
                HTML::campoTextoCorto("intervalo", $textos["INTERVALO"], 3, 3, "1", array("title" => $textos["AYUDA_INTERVALO"], "class" => "oculto","onKeyPress" => "return campoEntero(event)", "disabled" => "disabled"))
               .HTML::campoOculto("valor_maximo", "0")
               .HTML::campoOculto("tasa1", "0")
               .HTML::campoOculto("tasa2", "0"),
                HTML::boton("botonAgregar", $textos["AGREGAR"], "agregarItem();", "adicionar"),
                HTML::contenedor(HTML::boton("botonRemover", "", "removerItem(this);", "eliminar", array("title" => $textos["ELIMINAR_ITEM"])), array("id" => "removedor", "style" => "display: none"))
            ), array(
                HTML::mostrarDato("total_debito", $textos["TOTAL_DEBITO"], "0"),
                HTML::mostrarDato("total_credito", $textos["TOTAL_CREDITO"], "0")
               .HTML::campoOculto("consecutivo_fila", "0")

            ), array(
                HTML::generarTabla( array("id","","SUCURSAL","CUENTA","TERCERO_CUENTA","OPCIONES","TIPO_DOCUMENTO_CRUCE","NUMERO_DOCUMENTO_CRUCE","DEBITOS","CREDITOS"),
                "",
                array("C","I","I","I","I","I","C","C","C"),
                "listaItems",
                false,
                false)

            ), array(//contiene los textos y variables de control leidos por el menu.js
                HTML::campoOculto("ingresar_tercero",$textos["TERCERO_VACIO"]),
                HTML::campoOculto("ingresar_numero_comprobante",$textos["NUMERO_COMPROBANTE_VACIO"]),
                HTML::campoOculto("ingresar_documento_soporte",$textos["DOCUMENTO_SOPORTE_VACIO"]),
                HTML::campoOculto("error_cuenta",$textos["ERROR_CUENTA"]),
                HTML::campoOculto("ingresar_cuenta",$textos["INGRESAR_CUENTA"]),
                HTML::campoOculto("ingresar_valor",$textos["VALOR_VACIO"]),
                HTML::campoOculto("ingresar_valor_correcto",$textos["VALOR_INCORRECTO"]),
                HTML::campoOculto("error_no_saldos", $textos["ERROR_NO_SALDOS"]),
                HTML::campoOculto("cuotas_vacia", $textos["CUOTAS_VACIA"]),
                HTML::campoOculto("intervalos_vacio", $textos["INTERVALOS_VACIO"]),
                HTML::campoOculto("intervalos_invalidos", $textos["INTERVALOS_INVALIDOS"]),
                HTML::campoOculto("maneja_tercero", '0'),
                HTML::campoOculto("maneja_saldos", "0"),
                HTML::campoOculto("fecha_soporte", ""),
                HTML::campoOculto("maneja_cheque", '0'),
                HTML::campoOculto("error_no_cuentas_bancarias", $textos["CUENTAS_BANCARIAS_VACIAS"]),
                HTML::campoOculto("cuenta_vacia", $textos["CUENTA_VACIO"]),
                HTML::campoOculto("modulo", $modulo),
                HTML::campoOculto("seleccione_documento", $textos["TIPO_DOCUMENTO_VACIO"]),
                HTML::campoOculto("periodo_activo", $periodo),
                HTML::campoOculto("periodo_activo_error", $textos["MENSAJE_PERIODO_CONTABLE"]),
                HTML::campoOculto("existe_fecha", $textos["ERROR_EXISTE_FECHA"]),
                HTML::campoOculto("denegado_registro_cuenta", $textos["DENEGADO_REGISTRO_CUENTA"]),
                HTML::campoOculto("denegado_registro_cuenta2", $textos["DENEGADO_REGISTRO_CUENTA2"]),
                HTML::campoOculto("estado_insertar", "S"),
                HTML::campoOculto("cual_mensaje_error_cuentas", ""),
                HTML::campoOculto("manejo_automatico", ""),
            )
        );

        // Definicion de botones
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones, "", $funciones);
    }

    // Enviar datos para la generacion del formulario al script que origino la peticion
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

// Adicionar los datos provenientes del formulario
} elseif (!empty($forma_procesar)) {
    // Asumir por defecto que no hubo error
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];

    $forma_cuenta_bancaria  = $forma_cuenta_bancaria2;
    $forma_tipo_documento   = $forma_tipo_documento2;
    $forma_sucursal_genera  = $forma_sucursal_genera2;

    if(isset($forma_cuenta_bancaria) && $forma_cuenta_bancaria > 0){
        $cuenta_cheque = explode("|", $forma_cuenta_bancaria);
        $cuenta_cheque = $cuenta_cheque[8];
    }else{
        $cuenta_cheque = 0;
    }

    if($forma_periodo_activo!='1'){
        $error   = true;
        $mensaje = $textos["MENSAJE_PERIODO_CONTABLE"];
    }elseif($forma_maneja_cheque == '1' && empty($forma_cuenta_bancaria)){
        $error   = true;
        $mensaje = $textos["CUENTAS_BANCARIAS_VACIAS"];
    }elseif (($cuenta_cheque != 0) && (!in_array($cuenta_cheque, $forma_cuentas))) {
        $error   = true;
        $mensaje = $textos["ERROR_CUENTA_CHEQUE"];

    } elseif (empty($forma_consecutivo_documento)){
        $error   = true;
        $mensaje = $textos["CONSECUTIVO_DOCUMENTO_VACIO"];

    }elseif(empty($forma_numero_comprobante)) {
        $error   = true;
        $mensaje = $textos["NUMERO_COMPROBANTE_VACIO"];

    } elseif (!isset($forma_movimientos)){
        $error   = true;
        $mensaje = $textos["ERROR_DATOS_MOVIMIENTO_INCOMPLETOS"];

    } elseif (empty($forma_cuentas)){
        $error   = true;
        $mensaje = $textos["CUENTA_VACIO"];

    } else {

        // Guardar datos del documento que genera el movimiento contable
        $id_tabla   = SQL::obtenerValor("tablas", "id", "nombre_tabla = 'movimientos_contables'");

        if($forma_id_tercero=="0"){
            $empresa           = SQL::obtenerValor("sucursales","codigo_empresa","codigo='".$forma_sucursal_genera."'");
            $forma_id_tercero  = SQL::obtenerValor("empresas","documento_identidad_tercero","codigo='".$empresa."'");
        }

        $manejo     = SQL::obtenerValor("tipos_documentos", "manejo_automatico", "codigo = '".$forma_tipo_documento."'");
        if ($manejo == '2') {
            $consecutivo_documento = SQL::obtenerValor("consecutivo_documentos", "MAX(consecutivo)", "codigo_sucursal = '".$forma_sucursal_genera."' AND codigo_tipo_documento = '".$forma_tipo_documento."' AND fecha_registro IN (SELECT MAX(fecha_registro) FROM job_consecutivo_documentos WHERE codigo_sucursal = '".$forma_sucursal_genera."' AND codigo_tipo_documento = '".$forma_tipo_documento."')");
            if(!$consecutivo_documento){
                $consecutivo_documento = 1;
            }else{
                $consecutivo_documento++;
            }
            $existeConsecutivo = false;
        } else {
            if(SQL::existeItem("consecutivo_documentos","codigo_sucursal",$forma_sucursal_genera,"codigo_tipo_documento = '".$forma_tipo_documento."' AND fecha_registro = '".$forma_fecha_contabilizacion."' AND consecutivo = '".$forma_consecutivo_documento."' AND documento_identidad_tercero = '".$forma_id_tercero."'")){
                $existeConsecutivo = true;
            }else{
                $existeConsecutivo = false;
            }
            $consecutivo_documento = $forma_consecutivo_documento;
        }

        $llave_tabla = $forma_sucursal_genera.'|'.$forma_id_tercero.'|'.$forma_tipo_comprobante.'|'.$forma_numero_comprobante.'|'.$forma_tipo_documento.'|'.str_pad($consecutivo_documento,8,"0", STR_PAD_LEFT).'|'.$forma_fecha_contabilizacion;

        if($existeConsecutivo){
            $error   = true;
            $mensaje = $textos["ERROR_EXISTE_CONSECUTIVO"];
        }else{

            if(SQL::existeItem("menu_movimientos_contables","id",$llave_tabla)){
                $error   = true;
                $mensaje = $textos["ERROR_EXISTE_CUENTA"];
            }else{

                $datos = array (
                    "codigo_sucursal"             => $forma_sucursal_genera,
                    "codigo_tipo_documento"       => $forma_tipo_documento,
                    "documento_identidad_tercero" => $forma_id_tercero,
                    "fecha_registro"              => $forma_fecha_contabilizacion,
                    "consecutivo"                 => $consecutivo_documento,
                    "id_tabla"                    => $id_tabla,
                    "llave_tabla"                 => $llave_tabla,
                    "codigo_sucursal_archivo"     => '0',
                    "consecutivo_archivo"         => '0'
                );

                $insertar = SQL::insertar("consecutivo_documentos", $datos);

                if (!$insertar) {
                    $error   = true;
                    $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
                }else{

                    // Guardar datos del encabezado del movimiento contable
                    $datos = array (
                        "codigo_sucursal"             => $forma_sucursal_genera,
                        "documento_identidad_tercero" => $forma_id_tercero,
                        "codigo_tipo_comprobante"     => $forma_tipo_comprobante,
                        "numero_comprobante"          => $forma_numero_comprobante,
                        "codigo_tipo_documento"       => $forma_tipo_documento,
                        "consecutivo_documento"       => $consecutivo_documento,
                        "fecha_contabilizacion"       => $forma_fecha_contabilizacion,
                        "fecha_transaccion"           => date("Y-m-d"),
                        "fecha_documento"             => $forma_fecha_documento,
                        "estado"                      => '1',
                        "observaciones"               => $forma_observaciones,
                        "codigo_usuario_genera"       => $sesion_codigo_usuario
                    );

                    $insertar       = SQL::insertar("movimientos_contables", $datos);

                    if (!$insertar) {
                        $error   = true;
                        $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
                        SQL::eliminar("consecutivo_documentos","codigo_sucursal='".$forma_sucursal_genera."' AND codigo_tipo_documento='".$forma_tipo_documento."' AND fecha_registro='".$forma_fecha_contabilizacion."' AND consecutivo='".$consecutivo_documento."'");
                    }else{

                        // Validar si el documento generó un cheque
                        if (isset($forma_cuenta_bancaria) && $forma_cuenta_bancaria > 0) {
                            $forma_cuenta_bancaria = explode("|", $forma_cuenta_bancaria);
                        }else{
                            $forma_cuenta_bancaria = 0;
                        }

                        // Guardar los items del movimientos contable
                        foreach ($forma_movimientos as $id_movimiento) {

                            if($forma_terceros[$id_movimiento] <= 0 || empty($forma_terceros[$id_movimiento])){
                                $cuenta_terceros = 0;
                            }else{
                                $cuenta_terceros = $forma_terceros[$id_movimiento];
                            }

                            $aux = explode('|',$forma_auxiliares[$id_movimiento]);

                            $consecutivo_item = SQL::obtenerValor("items_movimientos_contables", "MAX(consecutivo)", "codigo_sucursal='".$forma_sucursal_genera."' AND documento_identidad_tercero='".$forma_id_tercero."' AND codigo_tipo_comprobante='".$forma_tipo_comprobante."' AND numero_comprobante='".$forma_numero_comprobante."' AND codigo_tipo_documento='".$forma_tipo_documento."' AND consecutivo_documento='".$consecutivo_documento."' AND fecha_contabilizacion='".$forma_fecha_contabilizacion."'");
                            if(!$consecutivo_item){
                                $consecutivo_item = 1;
                            }else{
                                $consecutivo_item++;
                            }

                            $datos = array(
                                "codigo_sucursal"                     => $forma_sucursal_genera,
                                "documento_identidad_tercero"         => $forma_id_tercero,
                                "codigo_tipo_comprobante"             => $forma_tipo_comprobante,
                                "numero_comprobante"                  => $forma_numero_comprobante,
                                "codigo_tipo_documento"               => $forma_tipo_documento,
                                "consecutivo_documento"               => $consecutivo_documento,
                                "fecha_contabilizacion"               => $forma_fecha_contabilizacion,
                                "consecutivo"                         => $consecutivo_item,
                                "codigo_sucursal_contabiliza"         => $forma_sucursales[$id_movimiento],
                                "codigo_plan_contable"                => $forma_cuentas[$id_movimiento],
                                "codigo_empresa_auxiliar"             => $aux[0],
                                "codigo_anexo_contable"               => $aux[1],
                                "codigo_auxiliar_contable"            => $aux[2],
                                "documento_identidad_tercero_saldo"   => $cuenta_terceros,
                                "sentido"                             => $forma_sentidos[$id_movimiento],
                                "valor"                               => $forma_valores[$id_movimiento],
                                "valor_base1"                         => '0',
                                "valor_base2"                         => '0',
                                "codigo_tipo_documento_soporte"       => $forma_documentos_soportes[$id_movimiento],
                                "numero_documento_soporte"            => $forma_numeros_documentos_soportes[$id_movimiento],
                                "codigo_tipo_documento_bancario"      => $forma_documentos_bancarios[$id_movimiento],
                                "numero_documento_bancario"           => $forma_numeros_documentos_bancarios[$id_movimiento],
                                "documento_identidad_tercero_fiador1" => $forma_terceros1[$id_movimiento],
                                "documento_identidad_tercero_fiador2" => $forma_terceros2[$id_movimiento],
                                "codigo_sucursal_cheque"              => '0',
                                "codigo_tipo_documento_cheque"        => '0',
                                "codigo_banco_cheque"                 => '0',
                                "numero_cheque"                       => '',
                                "consecutivo_cheque"                  => '0'
                            );

                            $insertar   = SQL::insertar("items_movimientos_contables", $datos);

                            if (!$insertar) {
                                $error   = true;
                                $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
                                SQL::eliminar("consecutivo_cheques","codigo_sucursal='".$forma_sucursal_genera."' AND documento_identidad_tercero='".$forma_id_tercero."' AND codigo_tipo_comprobante='".$forma_tipo_comprobante."' AND numero_comprobante='".$forma_numero_comprobante."' AND codigo_tipo_documento_item='".$forma_tipo_documento."' AND consecutivo_documento='".$consecutivo_documento."' AND fecha_contabilizacion='".$forma_fecha_contabilizacion."'");
                                SQL::eliminar("abonos_items_movimientos_contables","codigo_sucursal='".$forma_sucursal_genera."' AND documento_identidad_tercero='".$forma_id_tercero."' AND codigo_tipo_comprobante='".$forma_tipo_comprobante."' AND numero_comprobante='".$forma_numero_comprobante."' AND codigo_tipo_documento='".$forma_tipo_documento."' AND consecutivo_documento='".$consecutivo_documento."' AND fecha_contabilizacion='".$forma_fecha_contabilizacion."'");
                                SQL::eliminar("saldos_items_movimientos_contables","codigo_sucursal='".$forma_sucursal_genera."' AND documento_identidad_tercero='".$forma_id_tercero."' AND codigo_tipo_comprobante='".$forma_tipo_comprobante."' AND numero_comprobante='".$forma_numero_comprobante."' AND codigo_tipo_documento='".$forma_tipo_documento."' AND consecutivo_documento='".$consecutivo_documento."' AND fecha_contabilizacion='".$forma_fecha_contabilizacion."'");
                                SQL::eliminar("items_movimientos_contables","codigo_sucursal='".$forma_sucursal_genera."' AND documento_identidad_tercero='".$forma_id_tercero."' AND codigo_tipo_comprobante='".$forma_tipo_comprobante."' AND numero_comprobante='".$forma_numero_comprobante."' AND codigo_tipo_documento='".$forma_tipo_documento."' AND consecutivo_documento='".$consecutivo_documento."' AND fecha_contabilizacion='".$forma_fecha_contabilizacion."'");
                                SQL::eliminar("movimientos_contables","codigo_sucursal='".$forma_sucursal_genera."' AND documento_identidad_tercero='".$forma_id_tercero."' AND codigo_tipo_comprobante='".$forma_tipo_comprobante."' AND numero_comprobante='".$forma_numero_comprobante."' AND codigo_tipo_documento='".$forma_tipo_documento."' AND consecutivo_documento='".$consecutivo_documento."' AND fecha_contabilizacion='".$forma_fecha_contabilizacion."'");
                                SQL::eliminar("consecutivo_documentos","codigo_sucursal='".$forma_sucursal_genera."' AND codigo_tipo_documento='".$forma_tipo_documento."' AND fecha_registro='".$forma_fecha_contabilizacion."' AND consecutivo='".$consecutivo_documento."'");
                                break;
                            }

                            // Verificar si el item corresponde a un abono para un saldo por documento
                            if ($forma_abonos[$id_movimiento] != 0 ) {

                                $llave_saldo = split('[|/]',$forma_abonos[$id_movimiento]);

                                $consecutivo_abono = SQL::obtenerValor("abonos_items_movimientos_contables", "MAX(consecutivo)", "codigo_sucursal_saldo = '".$llave_saldo[4]."' AND documento_identidad_tercero_saldo = '".$llave_saldo[5]."' AND codigo_tipo_comprobante_saldo = '".$llave_saldo[6]."' AND numero_comprobante_saldo = '".$llave_saldo[7]."' AND codigo_tipo_documento_saldo = '".$llave_saldo[8]."' AND consecutivo_documento_saldo = '".$llave_saldo[9]."' AND fecha_contabilizacion_saldo = '".$llave_saldo[10]."' AND consecutivo_saldo = '".$llave_saldo[11]."' AND fecha_vencimiento_saldo = '".$llave_saldo[12]."'");
                                if(!$consecutivo_abono){
                                    $consecutivo_abono = 1;
                                }else{
                                    $consecutivo_abono++;
                                }
                                $datos = array(
                                    "codigo_sucursal"                   => $forma_sucursal_genera,
                                    "documento_identidad_tercero"       => $forma_id_tercero,
                                    "codigo_tipo_comprobante"           => $forma_tipo_comprobante,
                                    "numero_comprobante"                => $forma_numero_comprobante,
                                    "codigo_tipo_documento"             => $forma_tipo_documento,
                                    "consecutivo_documento"             => $consecutivo_documento,
                                    "fecha_contabilizacion"             => $forma_fecha_contabilizacion,
                                    "consecutivo_item"                  => $consecutivo_item,
                                    "codigo_sucursal_saldo"             => $llave_saldo[4],
                                    "documento_identidad_tercero_saldo" => $llave_saldo[5],
                                    "codigo_tipo_comprobante_saldo"     => $llave_saldo[6],
                                    "numero_comprobante_saldo"          => $llave_saldo[7],
                                    "codigo_tipo_documento_saldo"       => $llave_saldo[8],
                                    "consecutivo_documento_saldo"       => $llave_saldo[9],
                                    "fecha_contabilizacion_saldo"       => $llave_saldo[10],
                                    "consecutivo_saldo"                 => $llave_saldo[11],
                                    "fecha_vencimiento_saldo"           => $llave_saldo[12],
                                    "consecutivo"                       => $consecutivo_abono,
                                    "fecha_pago_abono"                  => $forma_fecha_contabilizacion,
                                    "valor"                             => $forma_valores[$id_movimiento]
                                );
                                $insertar = SQL::insertar("abonos_items_movimientos_contables", $datos);
                            }

                            if (!$insertar) {
                                $error   = true;
                                $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
                                SQL::eliminar("consecutivo_cheques","codigo_sucursal='".$forma_sucursal_genera."' AND documento_identidad_tercero='".$forma_id_tercero."' AND codigo_tipo_comprobante='".$forma_tipo_comprobante."' AND numero_comprobante='".$forma_numero_comprobante."' AND codigo_tipo_documento_item='".$forma_tipo_documento."' AND consecutivo_documento='".$consecutivo_documento."' AND fecha_contabilizacion='".$forma_fecha_contabilizacion."'");
                                SQL::eliminar("abonos_items_movimientos_contables","codigo_sucursal='".$forma_sucursal_genera."' AND documento_identidad_tercero='".$forma_id_tercero."' AND codigo_tipo_comprobante='".$forma_tipo_comprobante."' AND numero_comprobante='".$forma_numero_comprobante."' AND codigo_tipo_documento='".$forma_tipo_documento."' AND consecutivo_documento='".$consecutivo_documento."' AND fecha_contabilizacion='".$forma_fecha_contabilizacion."'");
                                SQL::eliminar("saldos_items_movimientos_contables","codigo_sucursal='".$forma_sucursal_genera."' AND documento_identidad_tercero='".$forma_id_tercero."' AND codigo_tipo_comprobante='".$forma_tipo_comprobante."' AND numero_comprobante='".$forma_numero_comprobante."' AND codigo_tipo_documento='".$forma_tipo_documento."' AND consecutivo_documento='".$consecutivo_documento."' AND fecha_contabilizacion='".$forma_fecha_contabilizacion."'");
                                SQL::eliminar("items_movimientos_contables","codigo_sucursal='".$forma_sucursal_genera."' AND documento_identidad_tercero='".$forma_id_tercero."' AND codigo_tipo_comprobante='".$forma_tipo_comprobante."' AND numero_comprobante='".$forma_numero_comprobante."' AND codigo_tipo_documento='".$forma_tipo_documento."' AND consecutivo_documento='".$consecutivo_documento."' AND fecha_contabilizacion='".$forma_fecha_contabilizacion."'");
                                SQL::eliminar("movimientos_contables","codigo_sucursal='".$forma_sucursal_genera."' AND documento_identidad_tercero='".$forma_id_tercero."' AND codigo_tipo_comprobante='".$forma_tipo_comprobante."' AND numero_comprobante='".$forma_numero_comprobante."' AND codigo_tipo_documento='".$forma_tipo_documento."' AND consecutivo_documento='".$consecutivo_documento."' AND fecha_contabilizacion='".$forma_fecha_contabilizacion."'");
                                SQL::eliminar("consecutivo_documentos","codigo_sucursal='".$forma_sucursal_genera."' AND codigo_tipo_documento='".$forma_tipo_documento."' AND fecha_registro='".$forma_fecha_contabilizacion."' AND consecutivo='".$consecutivo_documento."'");
                                break;
                            }

                            // Verificar si el item generó saldos por documento
                            $saldos = "forma_saldos_".$id_movimiento;
                            $fechas = "forma_fechas_".$id_movimiento;
                            if (isset($$saldos) && isset($$fechas)) {
                                foreach ($$saldos as $id => $vencimiento) {

                                    $datos = array(
                                        "codigo_sucursal"             => $forma_sucursal_genera,
                                        "documento_identidad_tercero" => $forma_id_tercero,
                                        "codigo_tipo_comprobante"     => $forma_tipo_comprobante,
                                        "numero_comprobante"          => $forma_numero_comprobante,
                                        "codigo_tipo_documento"       => $forma_tipo_documento,
                                        "consecutivo_documento"       => $consecutivo_documento,
                                        "fecha_contabilizacion"       => $forma_fecha_contabilizacion,
                                        "consecutivo"                 => $consecutivo_item,
                                        "fecha_vencimiento"           => ${$fechas}[$id],
                                        "valor"                       => ${$saldos}[$id]
                                    );
                                    $insertar = SQL::insertar("saldos_items_movimientos_contables", $datos);
                                    if (!$insertar) {
                                        $error   = true;
                                        $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
                                        break;
                                    }
                                }
                            }

                            if ($error) {
                                SQL::eliminar("consecutivo_cheques","codigo_sucursal='".$forma_sucursal_genera."' AND documento_identidad_tercero='".$forma_id_tercero."' AND codigo_tipo_comprobante='".$forma_tipo_comprobante."' AND numero_comprobante='".$forma_numero_comprobante."' AND codigo_tipo_documento_item='".$forma_tipo_documento."' AND consecutivo_documento='".$consecutivo_documento."' AND fecha_contabilizacion='".$forma_fecha_contabilizacion."'");
                                SQL::eliminar("abonos_items_movimientos_contables","codigo_sucursal='".$forma_sucursal_genera."' AND documento_identidad_tercero='".$forma_id_tercero."' AND codigo_tipo_comprobante='".$forma_tipo_comprobante."' AND numero_comprobante='".$forma_numero_comprobante."' AND codigo_tipo_documento='".$forma_tipo_documento."' AND consecutivo_documento='".$consecutivo_documento."' AND fecha_contabilizacion='".$forma_fecha_contabilizacion."'");
                                SQL::eliminar("saldos_items_movimientos_contables","codigo_sucursal='".$forma_sucursal_genera."' AND documento_identidad_tercero='".$forma_id_tercero."' AND codigo_tipo_comprobante='".$forma_tipo_comprobante."' AND numero_comprobante='".$forma_numero_comprobante."' AND codigo_tipo_documento='".$forma_tipo_documento."' AND consecutivo_documento='".$consecutivo_documento."' AND fecha_contabilizacion='".$forma_fecha_contabilizacion."'");
                                SQL::eliminar("items_movimientos_contables","codigo_sucursal='".$forma_sucursal_genera."' AND documento_identidad_tercero='".$forma_id_tercero."' AND codigo_tipo_comprobante='".$forma_tipo_comprobante."' AND numero_comprobante='".$forma_numero_comprobante."' AND codigo_tipo_documento='".$forma_tipo_documento."' AND consecutivo_documento='".$consecutivo_documento."' AND fecha_contabilizacion='".$forma_fecha_contabilizacion."'");
                                SQL::eliminar("movimientos_contables","codigo_sucursal='".$forma_sucursal_genera."' AND documento_identidad_tercero='".$forma_id_tercero."' AND codigo_tipo_comprobante='".$forma_tipo_comprobante."' AND numero_comprobante='".$forma_numero_comprobante."' AND codigo_tipo_documento='".$forma_tipo_documento."' AND consecutivo_documento='".$consecutivo_documento."' AND fecha_contabilizacion='".$forma_fecha_contabilizacion."'");
                                SQL::eliminar("consecutivo_documentos","codigo_sucursal='".$forma_sucursal_genera."' AND codigo_tipo_documento='".$forma_tipo_documento."' AND fecha_registro='".$forma_fecha_contabilizacion."' AND consecutivo='".$consecutivo_documento."'");
                                break;
                            }
                            // Verificar si el item corresponde al cheque generado
                            $consecutivos_cheques_generados = array();

                            if ($forma_cuenta_bancaria != 0) {
                                if ($forma_cuentas[$id_movimiento] == $forma_cuenta_bancaria[8]) {

                                    $consecutivo_cheque = SQL::obtenerValor("consecutivo_cheques", "MAX(consecutivo)", "codigo_tipo_documento = '".$forma_cuenta_bancaria[1]."' AND codigo_banco = '".$forma_cuenta_bancaria[6]."' AND numero = '".$forma_cuenta_bancaria[7]."'");
                                    if ($consecutivo_cheque == '') {
                                        $consecutivo_cheque = 1;
                                    } else {
                                        $consecutivo_cheque++;
                                    }

                                    $tabla   = SQL::obtenerValor("tablas", "id", "nombre_tabla = 'movimientos_contables'");

                                    $conse_con_ceros      = str_pad($consecutivo_documento,8,"0", STR_PAD_LEFT);
                                    $conse_item_con_ceros = str_pad($consecutivo_item,9,"0", STR_PAD_LEFT);

                                    $llave = "codigo_sucursal = \'".$forma_sucursal_genera."\' AND documento_identidad_tercero = \'".$forma_id_tercero."\' AND codigo_tipo_comprobante = \'".$forma_tipo_comprobante."\' AND numero_comprobante = \'".$forma_numero_comprobante."\' AND codigo_tipo_documento = \'".$forma_tipo_documento."\' AND consecutivo_documento = \'".$conse_con_ceros."\' AND fecha_contabilizacion = \'".$forma_fecha_contabilizacion."\' AND consecutivo = \'".$consecutivo_item."\'";

                                    $datos = array(
                                        "codigo_sucursal"                 => $forma_cuenta_bancaria[0],
                                        "codigo_tipo_documento"           => $forma_cuenta_bancaria[1],
                                        "codigo_banco"                    => $forma_cuenta_bancaria[6],
                                        "numero"                          => $forma_cuenta_bancaria[7],
                                        "consecutivo"                     => $consecutivo_cheque,
                                        "codigo_sucursal_cuenta"          => $forma_cuenta_bancaria[0],
                                        "codigo_tipo_documento_cuenta"    => $forma_cuenta_bancaria[1],
                                        "codigo_sucursal_banco"           => $forma_cuenta_bancaria[2],
                                        "codigo_iso_cuenta"               => $forma_cuenta_bancaria[3],
                                        "codigo_dane_departamento_cuenta" => $forma_cuenta_bancaria[4],
                                        "codigo_dane_municipio_cuenta"    => $forma_cuenta_bancaria[5],
                                        "codigo_banco_cuenta"             => $forma_cuenta_bancaria[6],
                                        "numero_cuenta"                   => $forma_cuenta_bancaria[7],
                                        "id_tabla"                        => $tabla,
                                        "llave_tabla"                     => $llave
                                    );

                                    $insertar = SQL::insertar("consecutivo_cheques", $datos);

                                    $consecutivos_cheques_generados[] = $consecutivo_cheque;

                                    $datos = array(
                                        "codigo_sucursal_cheque"       => $forma_cuenta_bancaria[0],
                                        "codigo_tipo_documento_cheque" => $forma_cuenta_bancaria[1],
                                        "codigo_banco_cheque"          => $forma_cuenta_bancaria[6],
                                        "numero_cheque"                => $forma_cuenta_bancaria[7],
                                        "consecutivo_cheque"           => $consecutivo_cheque
                                    );
                                    $modificar = SQL::modificar("items_movimientos_contables", $datos, "codigo_sucursal='".$forma_sucursal_genera."' AND documento_identidad_tercero='".$forma_id_tercero."' AND codigo_tipo_comprobante='".$forma_tipo_comprobante."' AND numero_comprobante='".$forma_numero_comprobante."' AND codigo_tipo_documento='".$forma_tipo_documento."' AND consecutivo_documento='".$consecutivo_documento."' AND fecha_contabilizacion='".$forma_fecha_contabilizacion."' AND consecutivo = '".$consecutivo_item."'");
                                }
                            }

                            // Error de insercón
                            if (!$insertar) {
                                $consecutivos_cheques_generados = implode(',',$consecutivos_cheques_generados);
                                $error   = true;
                                $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
                                SQL::eliminar("consecutivo_cheques","codigo_sucursal = '".$forma_cuenta_bancaria[0]."' AND codigo_tipo_documento = '".$forma_cuenta_bancaria[1]."' AND codigo_banco = '".$forma_cuenta_bancaria[6]."' AND numero IN(".$consecutivos_cheques_generados.")");
                                SQL::eliminar("abonos_items_movimientos_contables","codigo_sucursal='".$forma_sucursal_genera."' AND documento_identidad_tercero='".$forma_id_tercero."' AND codigo_tipo_comprobante='".$forma_tipo_comprobante."' AND numero_comprobante='".$forma_numero_comprobante."' AND codigo_tipo_documento='".$forma_tipo_documento."' AND consecutivo_documento='".$consecutivo_documento."' AND fecha_contabilizacion='".$forma_fecha_contabilizacion."'");
                                SQL::eliminar("saldos_items_movimientos_contables","codigo_sucursal='".$forma_sucursal_genera."' AND documento_identidad_tercero='".$forma_id_tercero."' AND codigo_tipo_comprobante='".$forma_tipo_comprobante."' AND numero_comprobante='".$forma_numero_comprobante."' AND codigo_tipo_documento='".$forma_tipo_documento."' AND consecutivo_documento='".$consecutivo_documento."' AND fecha_contabilizacion='".$forma_fecha_contabilizacion."'");
                                SQL::eliminar("items_movimientos_contables","codigo_sucursal='".$forma_sucursal_genera."' AND documento_identidad_tercero='".$forma_id_tercero."' AND codigo_tipo_comprobante='".$forma_tipo_comprobante."' AND numero_comprobante='".$forma_numero_comprobante."' AND codigo_tipo_documento='".$forma_tipo_documento."' AND consecutivo_documento='".$consecutivo_documento."' AND fecha_contabilizacion='".$forma_fecha_contabilizacion."'");
                                SQL::eliminar("movimientos_contables","codigo_sucursal='".$forma_sucursal_genera."' AND documento_identidad_tercero='".$forma_id_tercero."' AND codigo_tipo_comprobante='".$forma_tipo_comprobante."' AND numero_comprobante='".$forma_numero_comprobante."' AND codigo_tipo_documento='".$forma_tipo_documento."' AND consecutivo_documento='".$consecutivo_documento."' AND fecha_contabilizacion='".$forma_fecha_contabilizacion."'");
                                SQL::eliminar("consecutivo_documentos","codigo_sucursal='".$forma_sucursal_genera."' AND codigo_tipo_documento='".$forma_tipo_documento."' AND fecha_registro='".$forma_fecha_contabilizacion."' AND consecutivo='".$consecutivo_documento."'");
                                break;
                            }
                        }
                    }
                }
            }
        }
    }

    // Enviar datos con la respuesta del proceso al script que originó la petición
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

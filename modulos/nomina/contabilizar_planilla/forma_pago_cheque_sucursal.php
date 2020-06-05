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

/*** Generar el formulario para la captura de datos ***/
///obtengo las sucursales que no se le ha generado una forma de pago//
if(isset($url_cargaCuentasBancarias)){
    $codigo_contable = SQL::obtenerValor("buscador_cuentas_bancarias", "codigo_plan_contable", "id='$url_codigo_cuenta'");
    $cuenta          = SQL::obtenerValor("seleccion_plan_contable_transacciones", "SUBSTRING_INDEX(cuenta,'|',1)", "id = '$codigo_contable'");

    $datos_envio   = array();
    $dato_envio[0] = $cuenta;
    $dato_envio[1] = $codigo_contable;
    HTTP::enviarJSON($dato_envio);
}

if(!empty($url_recargar)){
    if($url_elemento == "cuentas_bancarias"){
        $respuesta = HTML::generarDatosLista("buscador_cuentas_bancarias", "id", "CONCAT(BANCO,' - No. ',NUMERO)", "id_sucursal='$url_origen'");
    }
    HTTP::enviarJSON($respuesta);
}
if (isset($url_determinarSucursales)) {
    $condicion  = " ano_generacion='$url_ano_generacion' AND mes_generacion='$url_mes_generacion' AND ";
    $condicion .= " codigo_planilla='$url_codigo_planilla' AND fecha_pago_planilla='$url_fecha_pago_planilla' AND ";
    $condicion .= " periodo_pago='$url_periodo_pago'";
    $dato_envio = 0;

    $filas_cheque_empleado               = SQL::filasDevueltas(SQL::seleccionar(array("forma_pago_planillas_empleado"), array("*"), $condicion));
    $filas_forma_pago_planillas_efectivo = SQL::filasDevueltas(SQL::seleccionar(array("forma_pago_planillas_efectivo"), array("*"), $condicion));
    $filas_forma_pago_planillas_sucursal = SQL::filasDevueltas(SQL::seleccionar(array("forma_pago_planillas_sucursal"), array("*"), $condicion));
    ////Si almenos la suma de las filas me da mayo de cero(0) Quiere decir que existe un registro///
    ///////////////////////////////////////que cumple la condicion/////////////////////////////////
    $respuesta = $filas_cheque_empleado + $filas_forma_pago_planillas_efectivo + $filas_forma_pago_planillas_sucursal;
    HTTP::enviarJSON($respuesta);
}

if (isset($url_recargarTipoPlanilla)) {
    $tipo_planilla = SQL::obtenerValor("planillas", "periodo_pago", "codigo='$url_codigo_planilla'");
    HTTP::enviarJSON($tipo_planilla);
}

if(!empty($url_recargar_datos) && !empty($url_codigo_planilla) && !empty($url_ano_generacion) && !empty($url_mes_generacion)){
    $tipo_planilla = SQL::obtenerValor("planillas", "periodo_pago", "codigo='$url_codigo_planilla'");
    $periodo = "";
    if($tipo_planilla == '1') {
        $periodo = array(
            "1" => $textos["MENSUAL"],
        );
    }elseif($tipo_planilla == '2'){
        $periodo = array(
            "2" => $textos["PRIMERA_QUINCENA"],
            "3" => $textos["SEGUNDA_QUINCENA"],
        );
    }elseif($tipo_planilla == '3'){
        $periodo = array(
            "4" => $textos["PRIMERA_SEMANA"],
            "5" => $textos["SEGUNDA_SEMANA"],
            "6" => $textos["TERCERA_SEMANA"],
            "7" => $textos["CUARTA_SEMANA"],
            "8" => $textos["QUINTA_SEMANA"]
        );
    }  else if($tipo_planilla == '4'){
        $periodo = array(
            "9" => $textos["FECHA_UNICA"]
        );
    }
    if($url_mes_generacion == 2){
        if(($url_ano_generacion % 4 == 0) && ($url_ano_generacion % 100 != 0 || $url_ano_generacion % 400 == 0)){
            $dia_fin = 29;
        }else{
            $dia_fin = 28;
        }
    }else{
        $dia_fin = 31;
    }
    $fecha_inicio = $url_ano_generacion . "-" . $url_mes_generacion . "-01";
    $fecha_fin    = $url_ano_generacion . "-" . $url_mes_generacion . "-" . $dia_fin;
    $respuesta    = HTML::generarDatosLista("fechas_planillas", "fecha", "fecha", "codigo_planilla='$url_codigo_planilla' AND (fecha BETWEEN '$fecha_inicio' AND '$fecha_fin')");
    $fechas       = SQL::seleccionar(array("fechas_planillas"), array("fecha"), "codigo_planilla='$url_codigo_planilla' AND (fecha BETWEEN '$fecha_inicio' AND '$fecha_fin')");
    if(isset($url_periodo) && SQL::filasDevueltas($fechas)){
       $respuesta = $periodo;
    }
    HTTP::enviarJSON($respuesta);
}

if(!empty($url_generar)){
    $error           = "";
    $titulo          = $componente->nombre;
    $error_continuar = false;

    $empresa             = SQL::obtenerValor("sucursales","codigo_empresa","codigo='$sesion_sucursal' AND codigo>0");
    $consulta_sucursales = SQL::seleccionar(array("sucursales"), array("codigo, nombre"), "codigo_empresa = '$empresa'");//SQL::seleccionar(array("sucursales"), array("codigo, nombre"), "codigo_empresa = '$empresa' AND codigo in (select id_sucursal from job_buscador_cuentas_bancarias)");
    $listado_sucursales  = HTML::generarDatosLista("sucursales","codigo","nombre","codigo_empresa = '$empresa' AND codigo in (select id_sucursal from job_buscador_cuentas_bancarias)");

    if(SQL::filasDevueltas($consulta_sucursales)){
        $pestana_sucursales = array();
        $cuentas_bancarias  = HTML::generarDatosLista("buscador_cuentas_bancarias","id","CONCAT(BANCO,' - No. ',NUMERO)","id_sucursal='".array_shift(array_keys($listado_sucursales))."'");
        $codigo_contable    = SQL::obtenerValor("buscador_cuentas_bancarias","codigo_plan_contable","id='".array_shift(array_keys($cuentas_bancarias))."'");
        $cuenta             = SQL::obtenerValor("seleccion_plan_contable_transacciones","SUBSTRING_INDEX(cuenta,'|',1)","id = '$codigo_contable'");
        $listado_documentos = HTML::generarDatosLista("tipos_documentos", "codigo", "descripcion","genera_cheque='1' AND manejo_automatico='2'");

        $pestana_sucursales[] = array(HTML::listaSeleccionSimple("*tipo_documento", $textos["TIPO_DOCUMENTO"],$listado_documentos,"", array("title" => $textos["AYUDA_TIPO_DOCUMENTO"],"onchange" => "recargarDatosDocumento()")));
        $pestana_sucursales[] = array(HTML::listaSeleccionSimple("sucursal_genera",$textos["SUCURSAL_GENERA"],$listado_sucursales,"",array("title"=>$textos["AYUDA_PLANILLA"],"onChange" => "recargarLista('sucursal_genera','cuentas_bancarias'); setTimeout('cargarCuentasBancarias()',200);")));
        $pestana_sucursales[] = array(HTML::listaSeleccionSimple("cuentas_bancarias", $textos["CUENTA_BANCARIA"],$cuentas_bancarias,"",array("onChange" => "cargarCuentasBancarias();")));
        $pestana_sucursales[] = array(HTML::mostrarDato("cuenta",$textos["CUENTA"],$cuenta),HTML::campoOculto("codigo_contable_pago", $codigo_contable));
        $sucursales           = "";

        while($datos_sucursales = SQL::filaEnObjeto($consulta_sucursales)){
            $sucursales         .= $datos_sucursales->codigo.",";
            $codigo_sucursal     = $datos_sucursales->codigo;
            $nombreSucursal      = $datos_sucursales->nombre;
            $pestana_sucursales[] = array(
                HTML::campoOculto("sucursales[$datos_sucursales->codigo]",$datos_sucursales->codigo)
            );
        }
    }else{
        $error_continuar = 4;
    }

    $consulta_documentos = SQL::seleccionar(array("tipos_documentos"), array("codigo"), "genera_cheque='1' AND manejo_automatico='2'");
    if(!SQL::filasDevueltas($consulta_documentos)){
        $error_continuar = 5;
    }

    $consulta_planillas = SQL::seleccionar(array("planillas"), array("*"), "codigo>0");
    if (SQL::filasDevueltas($consulta_planillas)) {
       $planillas[0] = '';
       while($datos_planilla = SQL::filaEnObjeto($consulta_planillas)){
           $planillas[$datos_planilla->codigo] = $datos_planilla->descripcion;
        }
    }else{
        $error_continuar = 1;
    }
    $consulta_fechas_planillas = SQL::seleccionar(array("fechas_planillas"), array("*"), "codigo_planilla>0");
    if (SQL::filasDevueltas($consulta_fechas_planillas)){
        while ($datos_fechas_planillas = SQL::filaEnObjeto($consulta_fechas_planillas)) {
            $fechas_planillas[$datos_fechas_planillas->codigo_planilla . "|" . $datos_fechas_planillas->fecha] = $datos_fechas_planillas->fecha;
        }
    }else{
        $error_continuar = 2;
    }
    $consulta_empleados = SQL::seleccionar(array("ingreso_empleados"), array("*"), "documento_identidad_empleado!=''");
    if(!SQL::filasDevueltas($consulta_empleados)){
        $error_continuar = 3;
    }
    if(!$error_continuar){
        $ano = date("Y");
        $ano_planilla = array();
        for ($i = 0; $i <= 1; $i++) {
            $ano_planilla[$ano] = $ano;
            $ano++;
        }
        $ano   = date("Y");
        $mes   = date("m");
        $meses = array(
            "01" => $textos["ENERO"],
            "02" => $textos["FEBRERO"],
            "03" => $textos["MARZO"],
            "04" => $textos["ABRIL"],
            "05" => $textos["MAYO"],
            "06" => $textos["JUNIO"],
            "07" => $textos["JULIO"],
            "08" => $textos["AGOSTO"],
            "09" => $textos["SEPTIEMBRE"],
            "10" => $textos["OCTUBRE"],
            "11" => $textos["NOVIEMBRE"],
            "12" => $textos["DICIEMBRE"]
        );
       $formularios["PESTANA_BASICA"] = array(
            array(
                HTML::listaSeleccionSimple("*ano_generacion", $textos["ANO_PLANILLA"],$ano_planilla,$ano, array("title" => $textos["AYUDA_ANO_PLANILLA"], "class" => "selectorFecha", "onchange"=>"cargarFechaPagoFormaPago();")),
                HTML::listaSeleccionSimple("*mes_generacion", $textos["MES_PLANILLA"],$meses,$mes, array("title" => $textos["AYUDA_MES_PLANILLA"], "class" => "selectorFecha", "onchange"=>"cargarFechaPagoFormaPago();"))
            ),
            array(
                HTML::mostrarDato("datos_planilla",$textos["DATOS_PLANILLA"],"")
            ),
            array(
                HTML::listaSeleccionSimple("codigo_planilla",$textos["PLANILLA"],$planillas,"",array("title"=>$textos["AYUDA_PLANILLA"], "onchange"=>"cargarFechaPagoFormaPago();"))
            ),
            array(
                HTML::listaSeleccionSimple("fecha_pago",$textos["FECHA_PAGO"], "","",array("title"=>$textos["AYUDA_FECHA_PAGO"],"class"=>"fecha_pago","onclick" => "determinarPeriodo();")),
                HTML::mostrarDato("nombre_periodo",$textos["PERIODO"],"")
            ),
            array(
                HTML::campoOculto("periodo","").
                HTML::campoOculto("mensual",$textos["MENSUAL"]).
                HTML::campoOculto("primera_quincena",$textos["PRIMERA_QUINCENA"]).
                HTML::campoOculto("segunda_quincena",$textos["SEGUNDA_QUINCENA"]).
                HTML::campoOculto("sucursales_habilitar",$sucursales).
                HTML::campoOculto("codigo_contable",$codigo_contable).
                HTML::campoOculto("mensaje_forma_pago",$textos["EXISTE_FORMA_PAGO"]).
                HTML::campoOculto("periodo_pago_activo","1")
            )
        );

        $formularios["PESTANA_SUCURSALES"] = $pestana_sucursales;
        $botones = array(HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar"));
        $contenido = HTML::generarPestanas($formularios, $botones);
    }else{
        if($error_continuar == 4){
            $error = $textos["ERROR_SUCURSALES"];
        }elseif($error_continuar == 1){
            $error = $textos["ERROR_PLANILLAS"];
        }elseif($error_continuar == 2){
            $error = $textos["ERROR_FECHAS_PLANILLAS"];
        }elseif($error_continuar == 3){
            $error = $textos["ERROR_EMPLEADOS"];
        }elseif($error_continuar == 5){
            $error = $textos["ERROR_TIPOS_DOCUMENTOS_CHEQUE"];
        }
        $contenido = "";
    }

    /*** Enviar datos para la generación del formulario al script que originó la petición ***/
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

}elseif(!empty($forma_procesar)){
    // Asumir por defecto que no hubo error
    $error                          = false;
    $mensaje                        = $textos["EXITO_PLANILLA_CONTABILIZADA"];
    $llave_cuenta_bancaria          = explode("|", $forma_cuentas_bancarias);
    $codigo_sucursal_pertence       = $llave_cuenta_bancaria[0];
    $tipo_documento_cuenta_bancaria = $llave_cuenta_bancaria[1];
    $codigo_sucursal_banco          = $llave_cuenta_bancaria[2];
    $codigo_iso                     = $llave_cuenta_bancaria[3];
    $codigo_dane_departamento       = $llave_cuenta_bancaria[4];
    $codigo_dane_municipio          = $llave_cuenta_bancaria[5];
    $codigo_banco                   = $llave_cuenta_bancaria[6];
    $numero                         = $llave_cuenta_bancaria[7];

    $condicion  = " ano_generacion='$forma_ano_generacion' AND mes_generacion='$forma_mes_generacion' AND ";
    $condicion .= " codigo_planilla='$forma_codigo_planilla' AND fecha_pago_planilla='$forma_fecha_pago' AND ";
    $condicion .= " periodo_pago='$forma_periodo'";
     ///Determino que sucursales no seles ha generado//
    $surcusales_faltantes_pago = array();
    foreach($forma_sucursales as $codigo_sucursal){
        $dato_sucursal = SQL::obtenerValor("forma_pago_planillas","codigo_sucursal_recibe",$condicion." AND codigo_sucursal_recibe='$codigo_sucursal'");//
        if($dato_sucursal==false){
            $surcusales_faltantes_pago[$codigo_sucursal] = $codigo_sucursal;
        }
    }
    if($forma_periodo_pago_activo == "0"){
        $error   = true;
        $mensaje = $textos["EXISTE_FORMA_PAGO"];
    }elseif(count($surcusales_faltantes_pago)== 0){
        $error   = true;
        $mensaje = $textos["ERROR_SUCURSAL_GENERO_FORMA"];
    }elseif(empty($forma_codigo_planilla)){
        $error   = true;
        $mensaje = $textos["ERROR_CODIGO_PLANILLA"];
    }elseif(empty($forma_fecha_pago)){
        $error   = true;
        $mensaje = $textos["ERROR_FECHA_PAGO"];
    }elseif(empty($forma_periodo)){
        $error   = true;
        $mensaje = $textos["ERROR_PERIODO"];
    }else{
        foreach ($surcusales_faltantes_pago as $codigo_sucursal) {

            $datos = array("contabilizado" => "1");

            //Para las incapacidades
            $modificar1 = SQL::modificar("reporte_incapacidades", $datos, "ano_generacion='" . $forma_ano_generacion . "' AND mes_generacion='" . $forma_mes_generacion . "' AND codigo_planilla='" . $forma_codigo_planilla . "' AND periodo_pago='" . $forma_periodo . "' AND codigo_sucursal='" . $codigo_sucursal . "' AND fecha_pago_planilla='" . $forma_fecha_pago . "' AND contabilizado='2'");
            //Para las horas Normales, Extras y Recargos
            $modificar2 = SQL::modificar("movimiento_tiempos_laborados", $datos, "ano_generacion='" . $forma_ano_generacion . "' AND mes_generacion='" . $forma_mes_generacion . "' AND codigo_planilla='" . $forma_codigo_planilla . "' AND periodo_pago='" . $forma_periodo . "' AND codigo_sucursal='" . $codigo_sucursal . "' AND fecha_pago_planilla='" . $forma_fecha_pago . "' AND contabilizado='2'");
            //Para la liquidacion de salud
            $modificar3 = SQL::modificar("movimientos_salud", $datos, "ano_generacion='" . $forma_ano_generacion . "' AND mes_generacion='" . $forma_mes_generacion . "' AND codigo_planilla='" . $forma_codigo_planilla . "' AND periodo_pago='" . $forma_periodo . "' AND codigo_sucursal='" . $codigo_sucursal . "' AND fecha_pago_planilla='" . $forma_fecha_pago . "' AND contabilizado='0'");
            //Para la liquidacion de pension
            $modificar4 = SQL::modificar("movimientos_pension", $datos, "ano_generacion='" . $forma_ano_generacion . "' AND mes_generacion='" . $forma_mes_generacion . "' AND codigo_planilla='" . $forma_codigo_planilla . "' AND periodo_pago='" . $forma_periodo . "' AND codigo_sucursal='" . $codigo_sucursal . "' AND fecha_pago_planilla='" . $forma_fecha_pago . "' AND contabilizado='0'");
            //Para la liquidacion de salario
            $modificar5 = SQL::modificar("movimientos_salarios", $datos, "ano_generacion='" . $forma_ano_generacion . "' AND mes_generacion='" . $forma_mes_generacion . "' AND codigo_planilla='" . $forma_codigo_planilla . "' AND periodo_pago='" . $forma_periodo . "' AND codigo_sucursal='" . $codigo_sucursal . "' AND fecha_pago_planilla='" . $forma_fecha_pago . "' AND contabilizado='0'");
            //Para la liquidacion de auxilio de trasnporte
            $modificar6 = SQL::modificar("movimientos_auxilio_transporte", $datos, "ano_generacion='" . $forma_ano_generacion . "' AND mes_generacion='" . $forma_mes_generacion . "' AND codigo_planilla='" . $forma_codigo_planilla . "' AND periodo_pago='" . $forma_periodo . "' AND codigo_sucursal='" . $codigo_sucursal . "' AND fecha_pago_planilla='" . $forma_fecha_pago . "' AND contabilizado='0'");
            //Para las novedades manuales
            $modificar7 = SQL::modificar("movimiento_novedades_manuales",$datos,"ano_generacion='".$forma_ano_generacion."' AND mes_generacion='".$forma_mes_generacion."' AND codigo_planilla='".$forma_codigo_planilla."' AND periodo_pago='".$forma_periodo."' AND codigo_sucursal='".$codigo_sucursal."' AND fecha_pago_planilla='".$forma_fecha_pago."' AND contabilizado='2'");
            //Para los descuentos por prestamos a empleados
            $modificar8 = SQL::modificar("movimiento_control_prestamos_empleados",$datos,"ano_generacion='".$forma_ano_generacion."' AND mes_generacion='".$forma_mes_generacion."' AND codigo_planilla='".$forma_codigo_planilla."' AND periodo_pago='".$forma_periodo."' AND codigo_sucursal='".$codigo_sucursal."' AND fecha_pago_planilla='".$forma_fecha_pago."' AND contabilizado='0'");

            if (!$modificar1) {
                $error = false;
                $mensaje = $textos["ERROR_PLANILLA_CONTABILIZADA_INCAPACIDADES"];
            } else if (!$modificar2) {
                $error = false;
                $mensaje = $textos["ERROR_PLANILLA_CONTABILIZADA_TIEMPOS"];
            } else if (!$modificar3) {
                $error = false;
                $mensaje = $textos["ERROR_PLANILLA_CONTABILIZADA_SALUD"];
            } else if (!$modificar4) {
                $error = false;
                $mensaje = $textos["ERROR_PLANILLA_CONTABILIZADA_PENSION"];
            } else if (!$modificar5) {
                $error = false;
                $mensaje = $textos["ERROR_PLANILLA_CONTABILIZADA_SALARIOS"];
            } else if (!$modificar6) {
                $error = false;
                $mensaje = $textos["ERROR_PLANILLA_CONTABILIZADA_AUXILIO"];
            } else if(!$modificar7){
                $error        = false;
                $mensaje      = $textos["ERROR_PLANILLA_CONTABILIZADA_NOVEDAD"];
            }else if(!$modificar8){
                $error   = false;
                $mensaje = $textos["ERROR_PLANILLA_CONTABILIZADA_PRESTAMOS"];
            }

            $datos = array(
                "forma_pago"             => "3",
                "ano_generacion"         => $forma_ano_generacion,
                "mes_generacion"         => $forma_mes_generacion,
                "codigo_planilla"        => $forma_codigo_planilla,
                "periodo_pago"           => $forma_periodo,
                "fecha_pago_planilla"    => $forma_fecha_pago,
                "codigo_sucursal_genera" => $forma_sucursal_genera,
                "codigo_sucursal_recibe" => $codigo_sucursal,
                "autorizado"             => "1",
                "pagado"                 => "0"
            );
            $insertar = SQL::insertar("forma_pago_planillas",$datos);
        }
       $id_tabla = SQL::obtenerValor("tablas", "id", "nombre_tabla = 'forma_pago_planilla'");
       $consecutivo_documento = SQL::obtenerValor("consecutivo_documentos", "MAX(consecutivo)", "codigo_sucursal = '" . $forma_sucursal_genera . "' AND codigo_tipo_documento = '" . $tipo_documento_cuenta_bancaria . "'");
        if (!$consecutivo_documento) {
            $consecutivo_documento = 1;
        } else {
            $consecutivo_documento++;
            $existe_concecutivo = false;
        }
        $tipo_comprobante = SQL::obtenerValor("tipos_documentos", "codigo_comprobante", "codigo = '$forma_tipo_documento'");
        $llave_tabla = $forma_ano_generacion . '|' . $forma_mes_generacion . '|' . $forma_periodo . '|' . $forma_fecha_pago . '|' . $forma_sucursal_genera;

        if(SQL::existeItem("menu_movimientos_contables","id",$llave_tabla)){
            $error   = true;
            $mensaje = $textos["ERROR_EXISTE_CUENTA"];
        }else{
            $fecha_registro =  Date("Y-m-d");
            $datos = array (
                "codigo_sucursal"              => $forma_sucursal_genera,
                "codigo_tipo_documento"        => $forma_tipo_documento,
                "fecha_registro"               => $fecha_registro,
                "consecutivo"                  => $consecutivo_documento,
                "id_tabla"                     => $id_tabla,
                "llave_tabla"                  => $llave_tabla,
                "codigo_sucursal_archivo"      => '0',
                "consecutivo_archivo"          => '0',
                "documento_identidad_tercero"  => '0'
            );

            $condicion_consecutivo_documentos  = " codigo_sucursal='$forma_sucursal_genera' AND codigo_tipo_documento='$forma_tipo_documento'";
            $condicion_consecutivo_documentos .= " AND documento_identidad_tercero='0' AND fecha_registro='$fecha_registro' AND consecutivo='$consecutivo_documento'";
            $insertar                          = SQL::insertar("consecutivo_documentos", $datos);

            if (!$insertar) {
                $error   = true;
                $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
            }else{
                $llave_tabla  = "ano_generacion=\'$forma_ano_generacion\' AND mes_generacion=\'$forma_mes_generacion\'";
                $llave_tabla .= " AND periodo_pago=\'$forma_periodo\' AND fecha_pago_planilla=\'$forma_fecha_pago\' AND codigo_sucursal=\'$forma_sucursal_genera\'";

                $consecutivo_cheque = SQL::obtenerValor("consecutivo_cheques", "MAX(consecutivo)", "codigo_tipo_documento = '$tipo_documento_cuenta_bancaria' AND codigo_banco = '$codigo_banco' AND numero = '$numero'");
                if (!$consecutivo_cheque) {
                    $consecutivo_cheque = 1;
                } else {
                    $consecutivo_cheque++;
                }

                $datos = array (
                    "codigo_sucursal"                 => $forma_sucursal_genera,
                    "codigo_tipo_documento"           => $tipo_documento_cuenta_bancaria,
                    "codigo_banco"                    => $codigo_banco,
                    "numero"                          => $numero,
                    "consecutivo"                     => $consecutivo_cheque,
                    /////////////LLAVE DE CUENTAS BANCARIAS//////////
                    "codigo_sucursal_cuenta"          => $forma_sucursal_genera,
                    "codigo_tipo_documento_cuenta"    => $tipo_documento_cuenta_bancaria,
                    "codigo_sucursal_banco"           => $codigo_sucursal_banco,
                    "codigo_iso_cuenta"               => $codigo_iso,
                    "codigo_dane_departamento_cuenta" => $codigo_dane_departamento,
                    "codigo_dane_municipio_cuenta"    => $codigo_dane_municipio,
                    "codigo_banco_cuenta"             => $codigo_banco,
                    "numero_cuenta"                   => $numero,
                    ////////////////////////////////////////////////
                    "id_tabla"                        => $id_tabla,
                    "llave_tabla"                     => $llave_tabla
                );
                $condicion_consecutivo_cheques  = "codigo_sucursal='$forma_sucursal_genera' AND codigo_tipo_documento='$tipo_documento_cuenta_bancaria'";
                $condicion_consecutivo_cheques .= "AND codigo_banco='$codigo_banco' AND  numero='$numero' AND consecutivo='$consecutivo_cheque'";
                $insertar                       = SQL::insertar("consecutivo_cheques", $datos);

               if (!$insertar) {
                    $error    = true;
                    $mensaje  = $textos["ERROR_ADICIONAR_ITEM"];
                    $eliminar = SQL::eliminar("consecutivo_documentos", $condicion_consecutivo_documentos);
                }else{

                    $surcusales_faltantes_pago_cadena = implode(",",$surcusales_faltantes_pago);

                    $condicion   = " ano_generacion='$forma_ano_generacion' AND mes_generacion='$forma_mes_generacion'";
                    $condicion  .= " AND codigo_planilla='$forma_codigo_planilla' AND periodo_pago='$forma_periodo'";
                    $condicion  .= " AND fecha_pago_planilla='$forma_fecha_pago' AND codigo_sucursal in ($surcusales_faltantes_pago_cadena)";

                    $valor_movimiento_debitos  = SQL::obtenerValor("consulta_datos_planilla","sum(valor_movimiento)",$condicion." AND sentido='D' ");
                    $valor_movimiento_creditos = SQL::obtenerValor("consulta_datos_planilla","sum(valor_movimiento)",$condicion." AND sentido='C' ");

                    if($valor_movimiento_debitos){
                        $valor_movimiento = round($valor_movimiento_debitos-$valor_movimiento_creditos);
                    }else{
                        $valor_movimiento = 0;
                    }

                    $datos  = array(
                        "ano_generacion"                                    => $forma_ano_generacion,
                        "mes_generacion"                                    => $forma_mes_generacion,
                        "periodo_pago"                                      => $forma_periodo,
                        "fecha_pago_planilla"                               => $forma_fecha_pago,
                        "codigo_planilla"                                   => $forma_codigo_planilla,
                        "codigo_sucursal"                                   => $forma_sucursal_genera,
                        "codigo_plan_contable"                              => $forma_codigo_plan_contable,
                        "sentido"                                           => 'C',
                        "codigo_empresa_auxiliar"                           => '',
                        "codigo_anexo_contable"                             => '',
                        "codigo_auxiliar_contable"                          => '0',
                        "codigo_sucursal_pertence"                          => $codigo_sucursal_pertence,
                        "tipo_documento_cuenta_bancaria"                    => $tipo_documento_cuenta_bancaria,
                        "codigo_sucursal_banco"                             => $codigo_sucursal_banco,
                        "codigo_iso"                                        => $codigo_iso,
                        "codigo_dane_departamento"                          => $codigo_dane_departamento,
                        "codigo_dane_municipio"                             => $codigo_dane_municipio,
                        "codigo_banco"                                      => $codigo_banco,
                        "numero"                                            => $numero,
                        "codigo_tipo_documento"                             => $forma_tipo_documento,
                        ///llave_consecutivo docuemento/////
                        "consecutivo_documento"                             => $consecutivo_documento,
                        "codigo_tipo_documento_consecutivo_documento"       => $forma_tipo_documento,
                        "codigo_sucursal_consecutivo_documento"             => $forma_sucursal_genera,
                        "fecha_registro_consecutivo_documento"              => date("Y-m-d"),
                        "documento_identidad_tercero_consecutivo_documento" => '0',
                        ////llave de consecutivo cheque//////
                        "codigo_sucursal_consecutivo_cheque"                => $forma_sucursal_genera,
                        "codigo_tipo_documento_consecutivo_cheque"          => $tipo_documento_cuenta_bancaria,
                        "codigo_banco_consecutivo_cheque"                   => $codigo_banco,
                        "numero_consecutivo_cheque"                         => $numero,
                        "consecutivo_cheque"                                => $consecutivo_cheque,
                        "valor_movimiento"                                  => $valor_movimiento
                    );
                    $insertar = SQL::insertar("forma_pago_planillas_sucursal", $datos);
                    if (!$insertar) {
                        $error    = true;
                        $mensaje  = $textos["ERROR_ADICIONAR_ITEM"];
                        $eliminar = SQL::eliminar("consecutivo_documentos", $condicion_consecutivo_documentos);
                        $eliminar = SQL::eliminar("consecutivo_cheques", $condicion_consecutivo_cheques);
                    }
                }
            }
        }
    }

    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

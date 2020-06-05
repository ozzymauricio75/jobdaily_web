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

if(isset($url_determinarSucursales_forma_nomina))
{
    $condicion  = " ano_generacion='$url_ano_generacion' AND mes_generacion='$url_mes_generacion' AND ";
    $condicion .= " codigo_planilla='$url_codigo_planilla' AND fecha_pago_planilla='$url_fecha_pago_planilla' AND ";
    $condicion .= " periodo_pago='$url_periodo_pago'";
    $datos_sucursales = HTML::generarDatosLista("sucursales","codigo","codigo"," codigo IN (select codigo_sucursal_recibe from job_forma_pago_planillas where ".$condicion.")");
    $dato_envio       = array();
    foreach($datos_sucursales AS $sucursal){
        $dato_envio[] = $sucursal;
    }
    HTTP::enviarJSON($dato_envio);
}
if(isset($url_determinarSucursales)){
    $condicion  = " ano_generacion='$url_ano_generacion' AND mes_generacion='$url_mes_generacion' AND ";
    $condicion .= " codigo_planilla='$url_codigo_planilla' AND fecha_pago_planilla='$url_fecha_pago_planilla' AND ";
    $condicion .= " periodo_pago='$url_periodo_pago'";
    $dato_envio = 0;

    $filas_cheque_empleado               = SQL::filasDevueltas(SQL::seleccionar(array("forma_pago_planillas_empleado"), array("*"), $condicion));
    $filas_forma_pago_planillas_efectivo = SQL::filasDevueltas(SQL::seleccionar(array("forma_pago_planillas_efectivo"), array("*"), $condicion));
    $filas_forma_pago_planillas_sucursal = SQL::filasDevueltas(SQL::seleccionar(array("forma_pago_planillas_sucursal"), array("*"), $condicion));
    ////Si almenos la suma de las filas me da mayor de cero(0) Quiere decir que existe un registro///
    ///////////////////////////////////////que cumple la condicion/////////////////////////////////
    $respuesta = $filas_cheque_empleado + $filas_forma_pago_planillas_efectivo + $filas_forma_pago_planillas_sucursal;
    HTTP::enviarJSON($respuesta);
}
if(isset($url_recargarTipoPlanilla)){
    $tipo_planilla = SQL::obtenerValor("planillas", "periodo_pago", "codigo='$url_codigo_planilla'");
    HTTP::enviarJSON($tipo_planilla);
}
if(!empty($url_recargar_datos) && !empty($url_codigo_planilla) && !empty($url_ano_generacion) && !empty($url_mes_generacion)) {
    $tipo_planilla = SQL::obtenerValor("planillas", "periodo_pago", "codigo='$url_codigo_planilla'");
    $periodo = "";
    if($tipo_planilla == '1'){
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
    } else if($tipo_planilla == '4'){
        $periodo = array(
            "9" => $textos["FECHA_UNICA"]
        );
    }
    if($url_mes_generacion == 2){
        if(($url_ano_generacion % 4 ==0) && ($url_ano_generacion % 100 !=0 || $url_ano_generacion % 400 == 0)){
            $dia_fin = 29;
        }else{
            $dia_fin = 28;
        }
    }else{
        $dia_fin = 31;
    }
    $fecha_inicio = $url_ano_generacion."-".$url_mes_generacion."-01";
    $fecha_fin    = $url_ano_generacion."-".$url_mes_generacion."-".$dia_fin;
    $respuesta    = HTML::generarDatosLista("fechas_planillas", "fecha", "fecha", "codigo_planilla='$url_codigo_planilla' AND (fecha BETWEEN '$fecha_inicio' AND '$fecha_fin')");
    $fechas       = SQL::seleccionar(array("fechas_planillas"),array("fecha"),"codigo_planilla='$url_codigo_planilla' AND (fecha BETWEEN '$fecha_inicio' AND '$fecha_fin')");
    if(isset($url_periodo) && SQL::filasDevueltas($fechas)){
        $respuesta = $periodo;
    }
    HTTP::enviarJSON($respuesta);
}

if(!empty($url_generar)){

    $error               = "";
    $titulo              = $componente->nombre;
    $error_continuar     = false;
    $empresa             = SQL::obtenerValor("sucursales", "codigo_empresa", "codigo='$sesion_sucursal' AND codigo>0");
    $consulta_sucursales = SQL::seleccionar(array("sucursales"), array("codigo, nombre"), "codigo_empresa = '$empresa'");

    $continuar               = true;
    $error_planillas         = false;
    $error_fechas_planillas  = false;
    $error_empleados         = false;
    $error_preferencia       = false;
    $error_tipos_docummentos = false;
    $error_sucursales        = false;
    $error_tabla             = false;

    $consulta_planillas = SQL::seleccionar(array("planillas"), array("*"),"codigo>0");
    if(SQL::filasDevueltas($consulta_planillas)){
        $planillas[0] = '';
        while($datos_planilla = SQL::filaEnObjeto($consulta_planillas)){
            $planillas[$datos_planilla->codigo] = $datos_planilla->descripcion;
        }
    }else{
        $error_planillas = true;
        $continuar       = false;
    }

    $consulta_fechas_planillas = SQL::seleccionar(array("fechas_planillas"), array("*"), "codigo_planilla>0");
    if(SQL::filasDevueltas($consulta_fechas_planillas)){
        while($datos_fechas_planillas = SQL::filaEnObjeto($consulta_fechas_planillas)){
            $fechas_planillas[$datos_fechas_planillas->codigo_planilla . "|" . $datos_fechas_planillas->fecha] = $datos_fechas_planillas->fecha;
        }
    }else{
        $error_fechas_planillas = true;
        $continuar              = false;
    }

    $consulta_empleados = SQL::seleccionar(array("ingreso_empleados"), array("*"), "documento_identidad_empleado!=''");
    if(!SQL::filasDevueltas($consulta_empleados)){
        $error_empleados = true;
        $continuar       = false;
    }

    $codigo_contable = SQL::obtenerValor("preferencias", "valor", "variable='nomina_por_pagar' AND tipo_preferencia='1'");
    if(!$codigo_contable || $codigo_contable == ""){
        $error_preferencia = true;
        $continuar         = false;
    }

    $consulta_documentos = SQL::seleccionar(array("tipos_documentos"), array("codigo"), "manejo_automatico='2'");
    if(!SQL::filasDevueltas($consulta_documentos)){
        $error_tipos_docummentos = true;
        $continuar               = false;
    }

    if(SQL::filasDevueltas($consulta_sucursales)){
        $pestana_sucursales = array();
        $listado_documentos = HTML::generarDatosLista("tipos_documentos", "codigo", "descripcion", "manejo_automatico='2'");
        $listado_sucursales = HTML::generarDatosLista("sucursales", "codigo", "nombre", "codigo_empresa = '$empresa'");

        $pestana_sucursales[] = array(HTML::listaSeleccionSimple("*codigo_tipo_documento", $textos["TIPO_DOCUMENTO"], $listado_documentos, "", array("title" => $textos["AYUDA_TIPO_DOCUMENTO"], "onchange" => "recargarDatosDocumento()")));
        if(SQL::filasDevueltas($consulta_sucursales) > 1){
            $pestana_sucursales[] = array(HTML::listaSeleccionSimple("sucursal_genera", $textos["SUCURSAL_GENERA"], $listado_sucursales, "", array("title" => $textos["AYUDA_PLANILLA"])));
            $chequeo = false;
        }
        $sucursales           = "";

        while($datos_sucursales = SQL::filaEnObjeto($consulta_sucursales)){

            if(SQL::filasDevueltas($consulta_sucursales) == 1){
                $pestana_sucursales[] = array(HTML::campoOculto("sucursal_genera", $datos_sucursales->codigo));
                $chequeo = true;
            }
            $sucursales      .= $datos_sucursales->codigo.",";
            $codigo_sucursal = $datos_sucursales->codigo;
            $nombreSucursal  = $datos_sucursales->nombre;
            $sucursales     .= $datos_sucursales->codigo.",";
            $codigo_sucursal = $datos_sucursales->codigo;
            $nombreSucursal  = $datos_sucursales->nombre;

            $pestana_sucursales[] = array(
                HTML::marcaChequeo("sucursales[".$datos_sucursales->codigo."]", $datos_sucursales->nombre, $datos_sucursales->codigo, $chequeo, array("title" => $textos["AYUDA_SUCURSAL"], "id" => "sucursales_$datos_sucursales->codigo", "class" => "sucursales_electrodomesticos")),
            );
        }
    }else{
        $error_sucursales = true;
        $continuar        = false;
    }

    $id_tabla = SQL::obtenerValor("tablas", "id", "nombre_tabla='forma_pago_planillas_nomina'");
    if(!$id_tabla){
        $error_tabla = true;
        $continuar   = false;
    }

    if($continuar){

        $ano          = date("Y");
        $ano_planilla = array();

        for ($i=0;$i<=1;$i++){
            $ano_planilla[$ano] = $ano;
            $ano++;
        }
        $ano = date("Y");
        $mes = date("m");

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
                HTML::campoOculto("periodo",""),
                HTML::campoOculto("mensual",$textos["MENSUAL"]),
                HTML::campoOculto("primera_quincena",$textos["PRIMERA_QUINCENA"]),
                HTML::campoOculto("segunda_quincena",$textos["SEGUNDA_QUINCENA"]),
                HTML::campoOculto("fecha_unica",$textos["FECHA_UNICA"]),
                HTML::campoOculto("sucursales_habilitar",$sucursales),
                HTML::campoOculto("codigo_contable",$codigo_contable),
                HTML::campoOculto("id_tabla",$id_tabla),
                HTML::campoOculto("mensaje_forma_pago",$textos["EXISTE_FORMA_PAGO"]),
                HTML::campoOculto("periodo_pago_activo","1")
            )
        );
        $formularios["PESTANA_SUCURSALES"] = $pestana_sucursales;
        $botones = array(HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar"));
        $contenido = HTML::generarPestanas($formularios, $botones);
    }else{
        $error = $textos["VERIFICAR_DATOS"];
        if($error_planillas){
            $error .= $textos["ERROR_PLANILLAS"]."\n";
        }
        if($error_fechas_planillas){
            $error .= $textos["ERROR_FECHAS_PLANILLAS"]."\n";
        }
        if($error_empleados){
            $error .= $textos["ERROR_EMPLEADOS"]."\n";
        }
        if($error_preferencia){
            $error .= $textos["ERROR_PREFERENCIA_CUENTA"]."\n";
        }
        if($error_tipos_docummentos){
            $error .= $textos["ERROR_TIPOS_DOCUMENTOS"]."\n";
        }
        if($error_sucursales){
            $error .= $textos["ERROR_SUCURSALES"]."\n";
        }
        if($error_tabla){
            $error .= $textos["ERROR_TABLA"]."\n";
        }
        $error     .= $textos["CREAR_DATOS"];
        $contenido = "";
    }
// Enviar datos para la generacion del formulario al script que origino la peticion
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

}elseif(!empty($forma_procesar)){

    $error   = false;
    $mensaje = $textos["EXITO_PLANILLA_CONTABILIZADA"];

    if($forma_periodo_pago_activo == "0"){
        $error   = true;
        $mensaje = $textos["EXISTE_FORMA_PAGO"];
    }elseif(!isset($forma_sucursales)){
        $error   = true;
        $mensaje = $textos["ERROR_SUCURSAL_VACIA"];
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
        foreach ($forma_sucursales as $codigo_sucursal) {

            $condicion_extra = "mes_generacion='$forma_mes_generacion' AND codigo_planilla='$forma_codigo_planilla' AND periodo_pago='$forma_periodo'";
            $condicion_extra .= " AND fecha_pago_planilla='$forma_fecha_pago' AND codigo_sucursal='$codigo_sucursal'";

            if(!SQL::existeItem("forma_pago_planillas_nomina", "ano_generacion", $forma_ano_generacion,$condicion_extra)){

                $datos = array("contabilizado" => "1");

                $condicion = "ano_generacion='$forma_ano_generacion' AND mes_generacion='$forma_mes_generacion'";
                $condicion .= " AND codigo_planilla='$forma_codigo_planilla' AND periodo_pago='$forma_periodo' AND codigo_sucursal='$codigo_sucursal'";
                $condicion .= " AND fecha_pago_planilla='$forma_fecha_pago'";

                $modificar1  = SQL::modificar("reporte_incapacidades", $datos, $condicion." AND contabilizado='2'");
                $modificar2  = SQL::modificar("movimiento_tiempos_laborados", $datos, $condicion." AND contabilizado='2'");
                $modificar3  = SQL::modificar("movimientos_salud", $datos, $condicion." AND contabilizado='0'");
                $modificar4  = SQL::modificar("movimientos_pension", $datos, $condicion." AND contabilizado='0'");
                $modificar5  = SQL::modificar("movimientos_salarios", $datos, $condicion." AND contabilizado='0'");
                $modificar6  = SQL::modificar("movimientos_auxilio_transporte", $datos, $condicion." AND contabilizado='0'");
                $modificar7  = SQL::modificar("movimiento_novedades_manuales",$datos,$condicion." AND contabilizado='2'");
                $modificar8  = SQL::modificar("movimiento_control_prestamos_empleados",$datos,$condicion." AND contabilizado='0'");
                $modificar9  = SQL::modificar("movimientos_salario_retroactivo", $datos, $condicion." AND contabilizado='0'");
                $modificar10 = SQL::modificar("movimiento_tiempos_no_laborados_horas", $datos, $condicion." AND contabilizado='2'");
                $modificar11 = SQL::modificar("movimiento_tiempos_no_laborados_dias", $datos, $condicion." AND contabilizado='2'");
                $modificar12 = SQL::modificar("movimientos_prima", $datos, $condicion." AND contabilizado='0'");
                $continuar   = true;

                $mensaje = "";
                if(!$modificar1){
                    $error     = true;
                    $mensaje   .= $textos["ERROR_PLANILLA_CONTABILIZADA_INCAPACIDADES"]."\n";
                    $continuar = false;
                }
                if(!$modificar2){
                    $error     = true;
                    $mensaje   .= $textos["ERROR_PLANILLA_CONTABILIZADA_TIEMPOS"]."\n";
                    $continuar = false;
                }
                if(!$modificar3){
                    $error     = true;
                    $mensaje   .= $textos["ERROR_PLANILLA_CONTABILIZADA_SALUD"]."\n";
                    $continuar = false;
                }
                if(!$modificar4){
                    $error     = true;
                    $mensaje   .= $textos["ERROR_PLANILLA_CONTABILIZADA_PENSION"]."\n";
                    $continuar = false;
                }
                if(!$modificar5){
                    $error     = true;
                    $mensaje   .= $textos["ERROR_PLANILLA_CONTABILIZADA_SALARIOS"]."\n";
                    $continuar = false;
                }
                if(!$modificar6){
                    $error     = true;
                    $mensaje   .= $textos["ERROR_PLANILLA_CONTABILIZADA_AUXILIO"]."\n";
                    $continuar = false;
                }
                if(!$modificar7){
                    $error     = true;
                    $mensaje   .= $textos["ERROR_PLANILLA_CONTABILIZADA_NOVEDAD"]."\n";
                    $continuar = false;
                }
                if(!$modificar8){
                    $error     = true;
                    $mensaje   .= $textos["ERROR_PLANILLA_CONTABILIZADA_PRESTAMOS"]."\n";
                    $continuar = false;
                }
                if(!$modificar9){
                    $error     = true;
                    $mensaje   .= $textos["ERROR_MOVIMIENTOS_SALARIO_RETROACTIVO"]."\n";
                    $continuar = false;
                }
                if(!$modificar10){
                    $error     = true;
                    $mensaje   .= $textos["ERROR_TIEMPOS_HORAS"]."\n";
                    $continuar = false;
                }
                if(!$modificar11){
                    $error     = true;
                    $mensaje   .= $textos["ERROR_TIEMPOS_DIAS"]."\n";
                    $continuar = false;
                }
                if(!$modificar12){
                    $error     = true;
                    $mensaje   .= $textos["ERROR_PRIMA"]."\n";
                    $continuar = false;
                }

                if ($continuar){

                    $tipo_comprobante      = SQL::obtenerValor("tipos_documentos", "codigo_comprobante", "codigo = '$forma_codigo_tipo_documento'");
                    $consecutivo_documento = SQL::obtenerValor("consecutivo_documentos", "MAX(consecutivo)", "codigo_sucursal='$codigo_sucursal' AND codigo_tipo_documento='$forma_codigo_tipo_documento'");
                    $llave_tabla            = $codigo_sucursal."|".$forma_ano_generacion."|".$forma_mes_generacion."|".$forma_codigo_planilla."|".$forma_periodo;

                   if(!$consecutivo_documento){
                        $consecutivo_documento = 1;
                    }else{
                        $consecutivo_documento++;
                    }

                    $fecha_registro = Date("Y-m-d");

                    $datos = array(
                        "codigo_sucursal"             => $codigo_sucursal,
                        "codigo_tipo_documento"       => $forma_codigo_tipo_documento,
                        "fecha_registro"              => $fecha_registro,
                        "consecutivo"                 => $consecutivo_documento,
                        "id_tabla"                    => $forma_id_tabla,
                        "llave_tabla"                 => $llave_tabla,
                        "codigo_sucursal_archivo"     => '0',
                        "consecutivo_archivo"         => '0',
                        "documento_identidad_tercero" => '0'
                    );

                    $insertar = SQL::insertar("consecutivo_documentos", $datos);

                    if(!$insertar){
                        $error           = true;
                        $continuar       = false;
                        $mensaje         = $textos["ERROR_ADICIONAR_ITEM"];
                    }else{

                        $condicion   = " ano_generacion='$forma_ano_generacion' AND mes_generacion='$forma_mes_generacion'";
                        $condicion  .= " AND codigo_planilla='$forma_codigo_planilla' AND periodo_pago='$forma_periodo'";
                        $condicion  .= " AND fecha_pago_planilla='$forma_fecha_pago' AND codigo_sucursal='$codigo_sucursal'";

                        $valor_movimiento_debitos  = SQL::obtenerValor("consulta_datos_planilla","sum(valor_movimiento)",$condicion." AND sentido='D'");
                        $valor_movimiento_creditos = SQL::obtenerValor("consulta_datos_planilla","sum(valor_movimiento)",$condicion." AND sentido='C'");

                        if($valor_movimiento_debitos){
                            $valor_movimiento = $valor_movimiento_debitos-$valor_movimiento_creditos;
                        }else{
                            $valor_movimiento = 0;
                        }

                        if ($valor_movimiento>0){

                            $datos = array(
                                "codigo_sucursal"             => $codigo_sucursal,
                                "ano_generacion"              => $forma_ano_generacion,
                                "mes_generacion"              => $forma_mes_generacion,
                                "codigo_planilla"             => $forma_codigo_planilla,
                                "periodo_pago"                => $forma_periodo,
                                "fecha_pago_planilla"         => $forma_fecha_pago,
                                "valor_movimiento"            => $valor_movimiento,
                                "codigo_contable"             => $forma_codigo_contable,
                                "sentido"                     => "C",
                                "codigo_empresa_auxiliar"     => 0,
                                "codigo_anexo_contable"       => "",
                                "codigo_auxiliar_contable"    => 0,
                                "codigo_tipo_documento"       => $forma_codigo_tipo_documento,
                                "documento_identidad_tercero" => "0",
                                "fecha_consecutivo"           => $fecha_registro,
                                "consecutivo"                 => $consecutivo_documento,
                                "fecha_registro"              => date("Y-m-d H:i:s"),
                                "codigo_usuario_registra"     => $sesion_codigo_usuario
                            );
                            $insertar = SQL::insertar("forma_pago_planillas_nomina", $datos);

                            if (!$insertar) {
                                $error     = true;
                                $continuar = false;
                                $mensaje   = $textos["ERROR_ADICIONAR_ITEM"];
                                $condicion = "codigo_sucursal='$codigo_sucursal' AND codigo_tipo_documento='$forma_tipo_documento'";
                                $condicion .= " AND fecha_registro='$fecha_registro' AND consecutivo='$consecutivo_documento'";
                                $condicion .= " AND documento_identidad_tercero='0'";
                                $eliminar  = SQL::eliminar("consecutivo_documentos",$condicion);
                            }
                        }
                    }
                }

                if (!$continuar){
                    //Para las incapacidades
                    $condicion = "ano_generacion='$forma_ano_generacion' AND mes_generacion='$forma_mes_generacion'";
                    $condicion .= " AND codigo_planilla='$forma_codigo_planilla' AND periodo_pago='$forma_periodo' AND codigo_sucursal='$codigo_sucursal'";
                    $condicion .= " AND fecha_pago_planilla='$forma_fecha_pago' AND contabilizado='1'";
                    $datos     = array("contabilizado" => "0");
                    $datos2    = array("contabilizado" => "2");

                    $modificar = SQL::modificar("reporte_incapacidades", $datos2, $condicion);
                    $modificar = SQL::modificar("movimiento_tiempos_laborados", $datos2, $condicion);
                    $modificar = SQL::modificar("movimiento_tiempos_no_laborados_horas", $datos2, $condicion);
                    $modificar = SQL::modificar("movimiento_tiempos_no_laborados_dias", $datos2, $condicion);
                    $modificar = SQL::modificar("movimientos_salud", $datos, $condicion);
                    $modificar = SQL::modificar("movimientos_pension", $datos, $condicion);
                    $modificar = SQL::modificar("movimientos_salarios", $datos, $condicion);
                    $modificar = SQL::modificar("movimientos_auxilio_transporte", $datos, $condicion);
                    $modificar = SQL::modificar("movimiento_novedades_manuales", $datos2, $condicion);
                    $modificar = SQL::modificar("movimiento_control_prestamos_empleados", $datos,$condicion);
                    $modificar = SQL::modificar("movimientos_salario_retroactivo", $datos, $condicion);
                    $modificar = SQL::modificar("movimientos_prima", $datos, $condicion);

                }
            } else {
                $error   = true;
                $mensaje = $textos["EXISTE_FORMA_PAGO"];
            }
        }
    }

    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

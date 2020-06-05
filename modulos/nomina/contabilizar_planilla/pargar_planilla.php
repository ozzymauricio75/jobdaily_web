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

// Generar el formulario para la captura de datos
if(isset($url_cargaInformacionPlanilla))
{
    $datos_enviar = array();
    $condicion  = " ano_generacion ='$url_ano_generacion' AND mes_generacion='$url_mes_generacion' AND codigo_planilla='$url_codigo_planilla'";
    $condicion .= " AND periodo_pago='$url_periodo_pago' AND fecha_pago_planilla='$url_fecha_pago_planilla'";
    $forma_pago_sucursal = SQL::seleccionar(array("forma_pago_planillas"), array("*"), $condicion);
    while($forma_pago_general = SQL::filaEnObjeto($forma_pago_sucursal)){
        $forma_pago      = $forma_pago_general->forma_pago;
        $datos_envio = array();
        if(!$forma_pago){
            $datos_envio[] = "0";
        }else{
            if($forma_pago == "1"){
                $tabla = "forma_pago_planillas_nomina";
                $condicionExtra = $condicion." AND codigo_sucursal='$forma_pago_general->codigo_sucursal_recibe'";
            }elseif ($forma_pago == "2"){
                $tabla = "forma_pago_planillas_efectivo";
                $condicionExtra = $condicion." AND codigo_sucursal='$forma_pago_general->codigo_sucursal_recibe'";
            }elseif ($forma_pago == "3"){
                $tabla = "forma_pago_planillas_sucursal";
                $condicionExtra = $condicion;
            }else{
                $tabla = "forma_pago_planillas_empleado";
                $condicionExtra = $condicion." AND codigo_sucursal='$forma_pago_general->codigo_sucursal_recibe'";
            }
            $datos_envio[] = $forma_pago;
            $consulta = SQL::seleccionar(array($tabla), array("*"),$condicionExtra);

            if(SQL::filasDevueltas($consulta)){
                $datos_consulta = SQL::filaEnObjeto($consulta);

                $nombre_sucursal_recibe = SQL::obtenerValor("sucursales", "nombre", "codigo='$datos_consulta->codigo_sucursal'");
                $datos_envio[] = $nombre_sucursal_recibe;

                $nombre_sucursal_genera = SQL::obtenerValor("sucursales", "nombre", "codigo='$datos_consulta->codigo_sucursal_consecutivo_documento'");
                $datos_envio[] = $nombre_sucursal_genera;

                $tipo_documento = SQL::obtenerValor("tipos_documentos", "descripcion", "codigo='$datos_consulta->codigo_tipo_documento_consecutivo_documento'");
                $datos_envio[] = $tipo_documento;

                $cuenta = SQL::obtenerValor("seleccion_plan_contable_transacciones", "SUBSTRING_INDEX(cuenta,'|',1)", "id = '$datos_consulta->codigo_plan_contable'");
                $datos_envio[] = $cuenta;
                if($forma_pago == "3" || $forma_pago == "4"){
                    $datos_envio[] = $datos_consulta->numero;
                }else
                {
                    $datos_envio[] = "";
                }
                $datos_enviar[] = implode("|",$datos_envio);
            }else{
                $datos_envio[] = "0";
            }
        }
    }
    HTTP::enviarJSON($datos_enviar);
}
if(isset($url_recargarTipoPlanilla))
{
    $tipo_planilla = SQL::obtenerValor("planillas","periodo_pago","codigo='$url_codigo_planilla'");
    HTTP::enviarJSON($tipo_planilla);
}
if(!empty($url_recargar_datos) && !empty($url_codigo_planilla) && !empty($url_ano_generacion) && !empty($url_mes_generacion) ){
    $tipo_planilla = SQL::obtenerValor("planillas","periodo_pago","codigo='$url_codigo_planilla'");
    $periodo = "";
    if($tipo_planilla == '1'){
        $periodo = array(
        "1" => $textos["MENSUAL"],
        );
    }else if($tipo_planilla == '2'){
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
    }elseif($tipo_planilla == '4'){
        $periodo = array(
            "9" => $textos["FECHA_UNICA"]
        );
    }
    if($url_mes_generacion == 2){
        if (($url_ano_generacion % 4 ==0) && ($url_ano_generacion % 100 !=0 || $url_ano_generacion % 400 == 0)){
            $dia_fin = 29;
        }else{
            $dia_fin = 28;
        }
    }else{
        $dia_fin = 31;
    }
    $fecha_inicio = $url_ano_generacion."-".$url_mes_generacion."-01";
    $fecha_fin    = $url_ano_generacion."-".$url_mes_generacion."-".$dia_fin;
    $respuesta = HTML::generarDatosLista("fechas_planillas", "fecha", "fecha", "codigo_planilla='$url_codigo_planilla' AND (fecha BETWEEN '$fecha_inicio' AND '$fecha_fin')");
    $fechas = SQL::seleccionar(array("fechas_planillas"),array("fecha"),"codigo_planilla='$url_codigo_planilla' AND (fecha BETWEEN '$fecha_inicio' AND '$fecha_fin')");
    if(isset($url_periodo) && SQL::filasDevueltas($fechas)){
        $respuesta = $periodo;
    }
    HTTP::enviarJSON($respuesta);
}

if(!empty($url_generar)){

    $error  = "";
    $titulo = $componente->nombre;

    $id_modulo           = SQL::obtenerValor("componentes","id_modulo","id='$componente->id'");
    $empresa             = SQL::obtenerValor("sucursales", "codigo_empresa", "codigo='$sesion_sucursal' AND codigo>0");
    $consulta_sucursales = SQL::seleccionar(array("sucursales"), array("codigo, nombre"), "codigo_empresa = '$empresa'");
    $error_sucursales    = false;
    if(SQL::filasDevueltas($consulta_sucursales)){

        $pestana_sucursales   = array();
        $pestana_sucursales[] = array(HTML::boton("botonSeleccionarTodos", $textos["SELECCIONAR_TODOS"], "seleccionar_todas_sucursales();", "", array()));
        while($datos_sucursales = SQL::filaEnObjeto($consulta_sucursales)){
            $codigo_sucursal      = $datos_sucursales->codigo;
            $nombreSucursal       = $datos_sucursales->nombre;
            $pestana_sucursales[] = array(
                HTML::marcaChequeo("sucursales[$datos_sucursales->codigo]", $datos_sucursales->nombre, $datos_sucursales->codigo, false, array("title" => $textos["AYUDA_SUCURSAL"], "id" => "sucursales_$datos_sucursales->codigo", "class" => "sucursales_electrodomesticos"))
            );
        }
    }else{
        $error_sucursales = true;
    }

    $error_planillas = false;
    $consulta_planillas = SQL::seleccionar(array("planillas"), array("*"), "codigo>0");
    if (SQL::filasDevueltas($consulta_planillas)) {
        $planillas[0] = '';
        while($datos_planilla = SQL::filaEnObjeto($consulta_planillas)){
            $planillas[$datos_planilla->codigo] = $datos_planilla->descripcion;
        }
    }else{
        $error_planillas = true;
    }

    $consulta_fechas_planillas = SQL::seleccionar(array("fechas_planillas"), array("*"), "codigo_planilla>0");
    $error_fechas_planilla = false;
    if(SQL::filasDevueltas($consulta_fechas_planillas)) {
        while($datos_fechas_planillas = SQL::filaEnObjeto($consulta_fechas_planillas)){
            $fechas_planillas[$datos_fechas_planillas->codigo_planilla . "|" . $datos_fechas_planillas->fecha] = $datos_fechas_planillas->fecha;
        }
    }else{
        $error_fechas_planilla = true;
    }

    $consulta_empleados = SQL::seleccionar(array("ingreso_empleados"), array("*"), "documento_identidad_empleado!=''");
    $error_empleados = false;
    if(!SQL::filasDevueltas($consulta_empleados)){
        $error_empleados = true;
    }

    $codigo_transaccion_cuenta_pagar_salud = SQL::obtenerValor("preferencias","valor","variable ='codigo_transaccion_cuenta_pagar_salud' AND tipo_preferencia='1'");
    $error_cuenta_pagar_salud = false;
    if (!$codigo_transaccion_cuenta_pagar_salud || $codigo_transaccion_cuenta_pagar_salud==0){
        $error_cuenta_pagar_salud = true;
    }
    $codigo_transaccion_cuenta_pagar_pension = SQL::obtenerValor("preferencias","valor","variable ='codigo_transaccion_cuenta_pagar_pension' AND tipo_preferencia='1'");
    $error_cuenta_pagar_pension = false;
    if (!$codigo_transaccion_cuenta_pagar_pension || $codigo_transaccion_cuenta_pagar_pension==0){
        $error_cuenta_pagar_pension = true;
    }
    $transaccion_cancelacion_nomina_pagar_salud   = SQL::obtenerValor("preferencias","valor","variable ='transaccion_cancelacion_nomina_pagar_salud' AND tipo_preferencia='1'");
    $error_cancelacion_nomina_pagar_salud = false;
    if (!$transaccion_cancelacion_nomina_pagar_salud || $transaccion_cancelacion_nomina_pagar_salud==0){
        $error_cancelacion_nomina_pagar_salud = true;
    }
    $transaccion_cancelacion_nomina_pagar_pension = SQL::obtenerValor("preferencias","valor","variable ='transaccion_cancelacion_nomina_pagar_pension' AND tipo_preferencia='1'");
    $error_cancelacion_nomina_pagar_pension = false;
    if (!$transaccion_cancelacion_nomina_pagar_pension || $transaccion_cancelacion_nomina_pagar_pension==0){
        $error_cancelacion_nomina_pagar_pension = true;
    }

    if(!$error_planillas && !$error_fechas_planilla && !$error_empleados && !$error_sucursales &&
       !$error_cuenta_pagar_salud && !$error_cuenta_pagar_pension && !$error_cancelacion_nomina_pagar_salud && !$error_cancelacion_nomina_pagar_pension
      ){
        $ano = date("Y");
        $ano_planilla = array();
        for($i = 0; $i <= 1; $i++){
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
                HTML::listaSeleccionSimple("*ano_generacion", $textos["ANO_PLANILLA"], $ano_planilla, $ano, array("title" => $textos["AYUDA_ANO_PLANILLA"], "class" => "selectorFecha", "onchange" => "cargarFechaPagoFormaPago('P');")),
                HTML::listaSeleccionSimple("*mes_generacion", $textos["MES_PLANILLA"], $meses, $mes, array("title" => $textos["AYUDA_MES_PLANILLA"], "class" => "selectorFecha", "onchange" => "cargarFechaPagoFormaPago('P');"))
            ),
            array(
                HTML::mostrarDato("datos_planilla", $textos["DATOS_PLANILLA"], "")
               .HTML::campoOculto("id_modulo", $id_modulo)
               .HTML::campoOculto("mensaje_pagar_planilla",$textos["MENSAJE_PAGAR_PLANILLA"])
            ),
            array(
                HTML::listaSeleccionSimple("codigo_planilla", $textos["PLANILLA"], $planillas, "", array("title" => $textos["AYUDA_PLANILLA"], "onchange" => "cargarFechaPagoFormaPago('P');")),
                HTML::listaSeleccionSimple("fecha_pago", $textos["FECHA_PAGO"], "", "", array("title" => $textos["AYUDA_FECHA_PAGO"], "class" => "fecha_pago", "onclick" => "determinarPeriodoFormaPago('P');")),
                HTML::mostrarDato("nombre_periodo", $textos["PERIODO"], "")
            ),
            array(
                HTML::contenedor(HTML::mostrarDato("tipo_documento",  $textos["TIPO_DOCUMENTO"], ""), array("class" => "oculto datos_comun")),
                HTML::contenedor(HTML::mostrarDato("sucursal_genera", $textos["SUCURSAL_GENERA"], ""), array("class" => "oculto datos_comun")),
                HTML::contenedor(HTML::mostrarDato("cuenta", $textos["CUENTA"], ""), array("class" => "oculto datos_comun")),
                HTML::contenedor(HTML::mostrarDato("cuenta_bancaria", $textos["CUENTA_BANCARIA"], ""), array("id" => "cuenta_banco" , "class" => "oculto")),
            ),
            array(
                HTML::generarTabla(
                    array("id","SUCURSAL","SUCURSAL_GENERA","TIPO_DOCUMENTO","CUENTA","CUENTA_BANCARIA"),
                        "",
                    array("C","I","I","I","I"),
                        "listaPagos",
                    false
                )
            ),
            array(
                HTML::campoOculto("periodo", ""),
                HTML::campoOculto("mensual", $textos["MENSUAL"]),
                HTML::campoOculto("primera_quincena", $textos["PRIMERA_QUINCENA"]),
                HTML::campoOculto("segunda_quincena", $textos["SEGUNDA_QUINCENA"]),
                HTML::campoOculto("mensaje_forma_pago", $textos["ERROR_NO_GENERO_FORMA_PAGO"]),
                HTML::campoOculto("codigo_transaccion_cuenta_pagar_salud", $codigo_transaccion_cuenta_pagar_salud),
                HTML::campoOculto("codigo_transaccion_cuenta_pagar_pension", $codigo_transaccion_cuenta_pagar_pension),
                HTML::campoOculto("transaccion_cancelacion_nomina_pagar_salud", $transaccion_cancelacion_nomina_pagar_salud),
                HTML::campoOculto("transaccion_cancelacion_nomina_pagar_pension", $transaccion_cancelacion_nomina_pagar_pension)
            )
        );
        $botones = array(HTML::boton("botonAceptar", $textos["ACEPTAR"], "verificarPagoPlanilla();", "aceptar"));
        $contenido = HTML::generarPestanas($formularios, $botones);
    }else{

        $error = $textos["VERIFICAR_DATOS"];
        if ($error_sucursales){
            $error .= $textos["ERROR_SUCURSALES"];
        }
        if ($error_planillas){
            $error .= $textos["ERROR_PLANILLAS"];
        }
        if ($error_fechas_planilla){
            $error .= $textos["ERROR_FECHAS_PLANILLAS"];
        }
        if ($error_empleados){
            $error .= $textos["ERROR_EMPLEADOS"];
        }
        if ($error_cuenta_pagar_salud){
            $error .= $textos["ERROR_CUENTA_PAGAR_SALUD"];
        }
        if ($error_cuenta_pagar_pension){
            $error .= $textos["ERROR_CUENTA_PAGAR_PENSION"];
        }
        if($error_cancelacion_nomina_pagar_salud){
            $error .= $textos["ERROR_CANCELACION_PAGAR_SALUD"];
        }
        if($error_cancelacion_nomina_pagar_pension){
            $error .= $textos["ERROR_CANCELACION_PAGAR_PENSION"];
        }
        $contenido = "";
    }

    // Enviar datos para la generacion del formulario al script que origino la peticion
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);
}elseif(!empty($forma_procesar)){
    $error = false;
    $mensaje = $textos["ITEM_ADICIONADO"];

    $condicion  = "ano_generacion = '".$forma_ano_generacion."' AND mes_generacion = '".$forma_mes_generacion."' AND ";
    $condicion .= "codigo_planilla = '".$forma_codigo_planilla."' AND fecha_pago_planilla = '".$forma_fecha_pago."' AND ";
    $condicion .= "periodo_pago = '".$forma_periodo."'";
    ///Determino que sucursales que se genero datos//
    //////////////////////////////////////////
    $forma_pago_sucursal = SQL::seleccionar(array("forma_pago_planillas"), array("*"), $condicion);
    while($forma_sucursales = SQL::filaEnObjeto($forma_pago_sucursal)){
        $surcusales_pago[$forma_sucursales->codigo_sucursal_recibe] = $forma_sucursales->codigo_sucursal_recibe;
    }
    // echo var_dump($surcusales_faltantes_pago);
    /////////////////////////////////////////
    if(!isset($forma_sucursales)){
        $error = true;
        $mensaje = $textos["ERROR_SUCURSAL_VACIA"];
    }elseif(empty($forma_fecha_pago)){
        $error = true;
        $mensaje = $textos["ERROR_FECHA_PAGO"];
    }elseif(empty($forma_periodo)){
        $error = true;
        $mensaje = $textos["ERROR_PERIODO"];
    }else{

        if($forma_mes_generacion == 2){
            if(($forma_ano_generacion % 4 == 0) && ($forma_ano_generacion % 100 != 0 || $forma_ano_generacion % 400 == 0)) {
                $dia_fin = 29;
            }else{
                $dia_fin = 28;
            }
        }else{
            $dia_fin = 30;
        }

        $fecha_inicio = $forma_ano_generacion . "-" . $forma_mes_generacion . "-01";
        $fecha_fin = $forma_ano_generacion . "-" . $forma_mes_generacion . "-" . $dia_fin;
        $fecha = strtotime($fecha_inicio) - strtotime($fecha_fin); //Hallo la diferencia de las fechas en segundos
        $dias_totales = $fecha / (60 * 60 * 24); //Convierto la diferencia en dias
        $tipo_planilla = SQL::obtenerValor("planillas", "periodo_pago", "codigo='".$forma_codigo_planilla."'");


        foreach($surcusales_pago as $codigo_sucursal){

            $datos         = array("pagado" => "1");
            $modificar     = SQL::modificar("forma_pago_planillas",$datos,$condicion." AND codigo_sucursal_recibe='".$codigo_sucursal."'");
            if(!$modificar){
                $error = true;
                $mensaje = $textos["ERROR_PAGAR_PLANILLA"];
            }else{

                $tablas_movimientos = array(
                    "movimientos_salarios",
                    "movimientos_salud",
                    "movimientos_pension",
                    "movimientos_auxilio_transporte",
                    "movimiento_control_prestamos_empleados",
                    "movimiento_novedades_manuales",
                    "movimiento_tiempos_laborados",
                    "reporte_incapacidades",
                    "movimiento_tiempos_no_laborados_dias"
                );

                foreach($tablas_movimientos AS $tabla){

                    $datos_tabla_movimiento = array(
                        "contabilizado" => "1"
                    );
                    $modificar = SQL::modificar($tabla,$datos_tabla_movimiento,$condicion." AND codigo_sucursal='".$codigo_sucursal."'");

                    if ($modificar && ($tabla=="movimientos_salud" || $tabla=="movimientos_pension")){

                        $consulta_movimiento = SQL::seleccionar(array($tabla),array("*"),$condicion." AND codigo_sucursal='".$codigo_sucursal."'");

                        if(SQL::filasDevueltas($consulta_movimiento)){

                            while($datos_movimiento = SQL::filaEnObjeto($consulta_movimiento)){

                                if ($tabla == "movimientos_salud"){
                                    $codigo_transaccion_contable = $forma_transaccion_cancelacion_nomina_pagar_salud;
                                    $codigo_contable = SQL::obtenerValor("transacciones_contables_empleado","codigo_contable","codigo='$codigo_transaccion_contable'");
                                    $sentido = SQL::obtenerValor("transacciones_contables_empleado","sentido","codigo='$codigo_transaccion_contable'");
                                } else {
                                    $codigo_transaccion_contable = $forma_transaccion_cancelacion_nomina_pagar_pension;
                                    $codigo_contable = SQL::obtenerValor("transacciones_contables_empleado","codigo_contable","codigo='$codigo_transaccion_contable'");
                                    $sentido = SQL::obtenerValor("transacciones_contables_empleado","sentido","codigo='$codigo_transaccion_contable'");
                                }

                                $fecha_generacion = date("Y-m-d H:i:s");

                                $datos_cancelacion = array(
                                    "ano_generacion"                => $forma_ano_generacion,
                                    "mes_generacion"                => $forma_mes_generacion,
                                    "codigo_planilla"               => $forma_codigo_planilla,
                                    "periodo_pago"                  => $forma_periodo,
                                    "fecha_pago_planilla"           => $forma_fecha_pago,
                                    "documento_identidad_empleado"  => $datos_movimiento->documento_identidad_empleado,
                                    "codigo_sucursal"               => $datos_movimiento->codigo_sucursal,

                                    "codigo_empresa"                => $datos_movimiento->codigo_empresa,
                                    "fecha_ingreso_empresa"         => $datos_movimiento->fecha_ingreso_empresa,
                                    "fecha_ingreso_sucursal"        => $datos_movimiento->fecha_ingreso_sucursal,

                                    "codigo_transaccion_contable"   => $codigo_transaccion_contable,
                                    "codigo_contable"               => $codigo_contable,
                                    "sentido"                       => $sentido,
                                    "codigo_empresa_auxiliar"       => $datos_movimiento->codigo_empresa_auxiliar,
                                    "codigo_anexo_contable"         => $datos_movimiento->codigo_anexo_contable,
                                    "codigo_auxiliar_contable"      => $datos_movimiento->codigo_auxiliar_contable,
                                    "valor_movimiento"              => $datos_movimiento->valor_movimiento,
                                    "fecha_generacion"              => $fecha_generacion,
                                    "codigo_usuario_genera"         => $sesion_codigo_usuario
                                );
                                if ($tabla == "movimientos_salud"){
                                    $tabla_insertar = "cancelacion_nomina_por_pagar_salud_empleado";
                                } else {
                                    $tabla_insertar = "cancelacion_nomina_por_pagar_pension_empleado";
                                }

                                $insertar_cancelacion = SQL::insertar($tabla_insertar,$datos_cancelacion);

                                if ($insertar_cancelacion){

                                    if ($tabla == "movimientos_salud"){
                                        $codigo_transaccion_contable = $forma_codigo_transaccion_cuenta_pagar_salud;
                                        $codigo_contable             = SQL::obtenerValor("transacciones_contables_empleado","codigo_contable","codigo='$codigo_transaccion_contable'");
                                        $sentido                     = SQL::obtenerValor("transacciones_contables_empleado","sentido","codigo='$codigo_transaccion_contable'");
                                        $codigo_entidad              = $datos_movimiento->codigo_entidad_salud;
                                        $documento_identidad_entidad = SQL::obtenerValor("entidades_parafiscales","documento_identidad_tercero","codigo='$codigo_entidad'");
                                    } else {
                                        $codigo_transaccion_contable = $forma_codigo_transaccion_cuenta_pagar_pension;
                                        $codigo_contable             = SQL::obtenerValor("transacciones_contables_empleado","codigo_contable","codigo='$codigo_transaccion_contable'");
                                        $sentido                     = SQL::obtenerValor("transacciones_contables_empleado","sentido","codigo='$codigo_transaccion_contable'");
                                        $codigo_entidad              = $datos_movimiento->codigo_entidad_pension;
                                        $documento_identidad_entidad = SQL::obtenerValor("entidades_parafiscales","documento_identidad_tercero","codigo='$codigo_entidad'");
                                    }

                                    $fecha_generacion = date("Y-m-d H:i:s");

                                    $datos_cancelacion = array(
                                        "ano_generacion"                => $forma_ano_generacion,
                                        "mes_generacion"                => $forma_mes_generacion,
                                        "codigo_planilla"               => $forma_codigo_planilla,
                                        "periodo_pago"                  => $forma_periodo,
                                        "fecha_pago_planilla"           => $forma_fecha_pago,
                                        "documento_identidad_empleado"  => $datos_movimiento->documento_identidad_empleado,
                                        "documento_identidad_entidad"   => $documento_identidad_entidad,
                                        "codigo_sucursal"               => $datos_movimiento->codigo_sucursal,

                                        "codigo_empresa"                => $datos_movimiento->codigo_empresa,
                                        "fecha_ingreso_empresa"         => $datos_movimiento->fecha_ingreso_empresa,
                                        "fecha_ingreso_sucursal"        => $datos_movimiento->fecha_ingreso_sucursal,

                                        "codigo_entidad_parafiscal"     => $codigo_entidad,
                                        "codigo_transaccion_contable"   => $codigo_transaccion_contable,
                                        "codigo_contable"               => $codigo_contable,
                                        "sentido"                       => $sentido,
                                        "codigo_empresa_auxiliar"       => $datos_movimiento->codigo_empresa_auxiliar,
                                        "codigo_anexo_contable"         => $datos_movimiento->codigo_anexo_contable,
                                        "codigo_auxiliar_contable"      => $datos_movimiento->codigo_auxiliar_contable,
                                        "valor_movimiento"              => $datos_movimiento->valor_movimiento,
                                        "fecha_generacion"              => $fecha_generacion,
                                        "codigo_usuario_genera"         => $sesion_codigo_usuario
                                    );
                                    if ($tabla == "movimientos_salud"){
                                        $tabla_insertar = "cuenta_por_pagar_salud_entidad";
                                    } else {
                                        $tabla_insertar = "cuenta_por_pagar_pension_entidad";
                                    }
                                    $insertar_cancelacion = SQL::insertar($tabla_insertar,$datos_cancelacion);
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    // Enviar datos con la respuesta del proceso al script que origino la peticion
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

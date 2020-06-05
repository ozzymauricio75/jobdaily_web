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


$forma_pago_mensual = array(
        "1" => $textos["MENSUAL"]
);

$forma_pago_semanal = array(
        "4" => $textos["PRIMERA_SEMANA"],
        "5" => $textos["SEGUNDA_SEMANA"],
        "6" => $textos["TERCERA_SEMANA"],
        "7" => $textos["CUARTA_SEMANA"],
        "8" => $textos["QUINTA_SEMANA"]
);

$forma_pago_quincenal = array(
        "9" => $textos["PROPOCIONAL"],
        "2" => $textos["PRIMERA_QUINCENA"],
        "3" => $textos["SEGUNDA_QUINCENA"]
);

// Verificar si el modulo del componente actual esta habilitado en el periodo contable actual
$sentidos = array(
        "1" => $textos["DEBITO"],
        "2" => $textos["CREDITO"]
);

if(isset($url_recargar_consecutivo_cheque)){
    $llave_cuenta   = explode('|',$url_cuenta);
    $consecutivo_cheque = SQL::obtenerValor("consecutivo_cheques", "MAX(consecutivo)", "codigo_tipo_documento = '".$llave_cuenta[1]."' AND codigo_banco = '".$llave_cuenta[6]."' AND numero = '".$llave_cuenta[7]."'");
    if (!$consecutivo_cheque) {
        $consecutivo_cheque = 1;
    } else {
        $consecutivo_cheque++;
    }
    $cuenta = SQL::obtenerValor("seleccion_plan_contable_transacciones","SUBSTRING_INDEX(cuenta,'|',1)","id = '".$llave_cuenta[8]."'");
    unset($llave_cuenta[8]);
    $llave       = implode('|',$llave_cuenta);
    $auxiliar    = SQL::obtenerValor("buscador_cuentas_bancarias","id_auxiliar","id = '".$llave."'");
    $descripcion = SQL::obtenerValor("seleccion_auxiliares_contables","descripcion","id = '".$auxiliar."'");
    $datos       = array($consecutivo_cheque,$cuenta,$auxiliar,$descripcion,$llave);
    HTTP::enviarJSON($datos);
    exit;
}

// Devolver datos para autocompletar la busqueda
if (isset($url_completar)) {//Validado
    if ($url_item == "selector2") {
        echo SQL::datosAutoCompletar("seleccion_plan_contable", $url_q);
    }
    exit;
}
// Devolver datos para recargar informacion requerida
if (isset($url_recargarDatosDocumento)) {
    $datos = array();
    // Obtener consecutivo de documento si tiene manejo automatico
    $manejo         = SQL::obtenerValor("tipos_documentos", "manejo_automatico", "codigo = '".$url_documento."'");
    if ($manejo == '2') {
        $consecutivo_documento  = SQL::obtenerValor("consecutivo_documentos", "MAX(consecutivo)", "codigo_sucursal = '".$url_sucursal."' AND codigo_tipo_documento = '".$url_documento."'");
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
            $llave_cuenta       = explode('|',$primer_cuenta);
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
//////////////////////////////////////////
if(isset($url_generarDatosTabla))
{
    // Calculo de numero de fechas a adicionar para completar el pago
    $saldo_actual        = $url_valorPrestamo;
    $numero_fechas_abono = ceil($url_valorPrestamo/$url_valorCuota);
    $condicionif         = true;
    $dia_fecha           = "";

    if($url_formaPago=="2" || $url_formaPago=="3"){
        $numero_fechas_abono *=2;
    }
    $consulta     = SQL::seleccionar(array("fechas_planillas"),array("*"),"codigo_planilla='$url_codigoPlanilla' AND fecha >= '$url_fechaInicio'","","",0,$numero_fechas_abono);
    $numero_filas = SQL::filasDevueltas($consulta);

    $datos_envio = array();

    if($numero_filas){
        $utilmo_saldo = "";
        $utilma_fecha = "";
        $dato_inicio  = (int)($numero_filas)+1;
    }else
    {
        $utilmo_saldo = $url_valorPrestamo;
        $utilma_fecha = $url_fechaInicio;
        $dato_inicio  = 0;
    }

    while($datosfechas = SQL::filaEnObjeto($consulta)){

        $fechas_descuento = explode("-",$datosfechas->fecha);
        $dia_fecha        = (int)$fechas_descuento[2];
        // Determino la fechas por el periodo de pago
        if($url_formaPago=="2")
        {
            $condicionif = $dia_fecha<=15;
        }elseif($url_formaPago=="3"){
            $condicionif = $dia_fecha > 15;
        }

        if($condicionif){

            $saldo_anterior  = $saldo_actual;
            $saldo_actual   -= $url_valorCuota;
            $valor_descuento = $url_valorCuota;

            if($saldo_actual< 0)
            {
                $saldo_actual   = 0;
                $valor_descuento = $saldo_anterior;
            }
            $datos_envio[] = $datosfechas->fecha.','.$saldo_actual.','.$valor_descuento;
            $utilma_fecha  = $datosfechas->fecha;
            $utilmo_saldo  = $saldo_actual;

        }
    }
    // Completo el el registro del acuerdo de pago

    for($i=$dato_inicio;$i<=$numero_fechas_abono;$i++)
    {
        $saldo_anterior  = $utilmo_saldo;
        $utilmo_saldo   -= $url_valorCuota;
        $valor_descuento = $url_valorCuota;
        $fecha           = getdate(strtotime($utilma_fecha));
        $utilma_fecha    = date("Y-m-d", mktime(($fecha["hours"]),($fecha["minutes"]),($fecha["seconds"]),($fecha["mon"]+1),($fecha["mday"]),($fecha["year"])));

        if($utilmo_saldo< 0){
            $utilmo_saldo    = 0;
            $valor_descuento = $saldo_anterior;
            $i               = $numero_fechas_abono+1;
        }
        if($utilmo_saldo!=0 && $valor_descuento!=0 ){
            $datos_envio[] = $utilma_fecha.','.$utilmo_saldo.','.$valor_descuento;
        }
    }
    HTTP::enviarJSON($datos_envio);
    exit;
}

if(isset($url_obtenerDatosContrato) && isset($url_documento_empleado)){

    $consulta_contrato_sucursal_empleado = SQL::seleccionar(array("sucursal_contrato_empleados"),array("codigo_planilla"),"documento_identidad_empleado='$url_documento_empleado'","","fecha_ingreso_sucursal DESC",0,1);
    $datos_contrato_sucursal_empleado    = SQL::filaEnObjeto($consulta_contrato_sucursal_empleado);

    $codigo_planilla  = $datos_contrato_sucursal_empleado->codigo_planilla;
    $periodo_pago     = SQL::obtenerValor("planillas","periodo_pago","codigo = $codigo_planilla");
    $datos_envio      = array();

    if($periodo_pago == '1')
    {
        $datos_envio = $forma_pago_mensual;
    }elseif($periodo_pago == '2')
    {
        $datos_envio = $forma_pago_quincenal;
    }

    $datos_envio["codigo_planilla"] = $codigo_planilla;
    HTTP::enviarJSON($datos_envio);
    exit;
}

if(isset($url_verificar)){
    $condicion_extra = "id_sucursal='".$url_codigo_sucursal."'";
    echo SQL::datosAutoCompletar("seleccion_empleados", $url_q, $condicion_extra);
    exit;
}

if (isset($url_verificarPlanillaGenerada)){

    $mensaje            = array();
    $fechas_liquidacion = SQL::seleccionar(array("movimiento_horas_normales"), array("fecha_inicio","fecha_fin"), "id_tipo_planilla = '$url_id_planilla'");
    $a                  = 0;
    while($datosfechas = SQL::filaEnObjeto($fechas_liquidacion)){

        list($ano_ini,$mes_ini,$dia_ini) = split("-", $url_fecha_inicial);
        $fecha_nueva_ini    = mktime(0,0,0, $mes_ini, $dia_ini, $ano_ini);

        list($ano_fin,$mes_fin,$dia_fin) = split("-", $url_fecha_final);
        $fecha_nueva_fin  = mktime(0,0,0, $mes_fin, $dia_fin, $ano_fin);

        $fecha_liq_ini        = explode("-", $datosfechas->fecha_inicio);
        $fecha_liquida_inicia = mktime(0,0,0, $fecha_liq_ini[1], $fecha_liq_ini[2], $fecha_liq_ini[0]);

        $fecha_liq_fin     = explode("-", $datosfechas->fecha_fin);
        $fecha_liquida_fin = mktime(0,0,0, $fecha_liq_fin[1], $fecha_liq_fin[2], $fecha_liq_fin[0]);

        $fecha_in[] .= $fecha_liquida_inicia;
        $fecha_fn[] .= $fecha_liquida_fin;

        if($fecha_nueva_ini >= $fecha_in[$a] && $fecha_nueva_fin <= $fecha_fn[$a]){
            $mensaje = $textos["PLANILLA_GENERADA"];
        }
        else{
            $mensaje = "";
        }
        $a++;
    }
    HTTP::enviarJSON($mensaje);
    exit;
}

if(isset($url_cargarTipoPlanilla)){

    $planilla      = SQL::obtenerValor("ingreso_empleados","id_planilla","id = '".$url_id_empleado."'");
    $tipo_planilla = SQL::obtenerValor("planillas","periodo_pago","id = '".$planilla."'");

    switch($tipo_planilla) {
        case 1 : //Se debe de buscar la fecha habil mas cercana al dia elegido para el pago
            $tipo_pla = 1;
            break;
        case 2 : $tipo_pla = 2;
            break;
        case 3 : $tipo_pla = 3;
            break;
    }

    HTTP::enviarJSON($tipo_pla);

    exit;
}
// Generar el formulario para la captura de datos
elseif(!empty($url_generar)) {

    $mensaje    = $textos["MENSAJE"];
    $respuesta  = array();
    $continuar  = true;

    $consulta_sucursales          = SQL::seleccionar(array("sucursales"),array("*"),"codigo !='0'");
    $consulta_empleados           = SQL::seleccionar(array("ingreso_empleados"),array("*"),"documento_identidad_empleado!='0' AND estado='1'");
    $consulta_tipos_documentos    = SQL::seleccionar(array("tipos_documentos"),array("*"),"codigo !='0'");
    $consulta_plan_contable       = SQL::seleccionar(array("plan_contable"),array("*"));
    $consulta_conceptos_prestamos = SQL::seleccionar(array("conceptos_prestamos"),array("*"),"codigo !='0'");
    $consulta_tablas_del_sistema  = SQL::seleccionar(array("tablas"),array("*"),"nombre_tabla = 'movimientos_prestamos_generados_empleados'");

    if(SQL::filasDevueltas($consulta_tablas_del_sistema) == 0 ){
        $mensaje     = $textos["NO_EXISTE_TABLA"];
        $continuar   = false;
    }else{
        if(SQL::filasDevueltas($consulta_sucursales) == 0 ){
            $mensaje   .= $textos["SUCURSALES"];
            $continuar = false;
        }
        if(SQL::filasDevueltas($consulta_empleados)== 0 ){
            $mensaje   .= $textos["EMPLEADOS"];
            $continuar = false;
        }
        if(SQL::filasDevueltas($consulta_tipos_documentos)== 0 ){
            $mensaje   .= $textos["TIPOS_DOCUMENTOS"];
            $continuar = false;
        }
        if(SQL::filasDevueltas($consulta_plan_contable)== 0 ){
            $mensaje   .= $textos["PLAN_CONTABLE"];
            $continuar = false;
        }
        if(SQL::filasDevueltas($consulta_conceptos_prestamos)== 0 ){
            $mensaje  .= $textos["CONCEPTO_PRESTAMOS"];
            $continuar = false;
        }
    }

    $respuesta[0] = $mensaje;
    $respuesta[1] = "";
    $respuesta[2] = "";

    if($continuar){

        $codigo_empresa           = SQL::obtenerValor("sucursales", "codigo_empresa", "codigo = '".$sesion_sucursal."'");
        $preferencia_cuota_minima = SQL::obtenerValor("preferencias","valor","variable = 'valor_cuota_minima_pago' AND codigo_empresa='".$codigo_empresa."' AND tipo_preferencia='2'");

        if(!$preferencia_cuota_minima){
            $respuesta[0] = $textos["ERROR_PREFERENCIA_CUOTA_MINIMA"];
            $respuesta[1] = "";
            $respuesta[2] = "";
        }else{

            $error                 = "";
            $titulo                = $componente->nombre;
            $documento             = SQL::obtenerValor("tipos_documentos", "codigo", "codigo != '0' ORDER BY descripcion LIMIT 1");
            $modulo                = SQL::obtenerValor("componentes", "id_modulo", "id = '".$componente->id."'");
            $consecutivo_documento = "";
            $read                  = "";

            // Transacciones contables donde su concepto es prestamos a empleados  009
            $listado_transacciones_contables_descontar = HTML::generarDatosLista("transacciones_contables_empleado","codigo","descripcion","codigo_concepto_transaccion_contable='9' AND sentido='C'"); //codigo_concepto_transaccion_contable='9'
            $listado_transacciones_contables_cobrar    = HTML::generarDatosLista("transacciones_contables_empleado","codigo","descripcion","codigo_concepto_transaccion_contable='9' AND sentido='D'");

            $cuentas_bancarias  = array();
            $cheques            = SQL::obtenerValor("tipos_documentos", "genera_cheque", "codigo = '".$documento."'");
            if ($cheques == 1) {
                $primer_cuenta = false;
                $consulta   = SQL::seleccionar(array("buscador_cuentas_bancarias"), array("*"));
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
                    $consecutivo_cheque = "";
                }
                $oculto         = "";
                $banco_disabled = "";
            } else {
                $cuentas_bancarias[0] = "";
                $consecutivo_cheque   = "";
                $oculto               = "oculto";
                $banco_disabled       = "disabled";
            }

            $consulta = SQL::seleccionar(array("sucursales"),array("*"),"codigo != 0 AND codigo_empresa = '".$codigo_empresa."'","","nombre");
            if ($sesion_usuario == $datosGlobales["usuarioMaestro"]){
                 while ($datos = SQL::filaEnObjeto($consulta)){
                    $sucursales[$datos->codigo] = $datos->nombre;
                }
            } else {
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
                AND b.id_componente = '".$componente->id."'";

                $consulta = SQL::seleccionar($tablas, $columnas, $condicion);

                if (SQL::filasDevueltas($consulta)) {
                    while ($datos = SQL::filaEnObjeto($consulta)) {
                        $sucursales[$datos->codigo] = $datos->nombre;
                    }
                }
            }
            // Obtengo preferencia del valor de la cuotaminima de pago

            // Definicion de pestana Basica
            $formularios["PESTANA_BASICA"] = array(
                array(
                    HTML::campoTextoCorto("*fecha_prestamo", $textos["FECHA_PRESTAMO"], 10, 10,date("Y-m-d"), array("title" => $textos["AYUDA_PRESTAMO"], "class" => "selectorFecha")),
                ),
                array(
                    HTML::listaSeleccionSimple("*codigo_sucursal", $textos["SUCURSAL_LABORA"],$sucursales,$sesion_sucursal, array("title" => $textos["AYUDA_SUCURSAL_LABORA"],"onchange" => "limpiarCampo();recargarDatosDocumento();")),

                    HTML::campoTextoCorto("*selector1", $textos["EMPLEADO"], 40, 255, "", array("title" => $textos["AYUDA_EMPLEADOS"],"onfocus" => "acLocalEmpleados(this);"))
                    .HTML::campoOculto("documento_empleado", "")
                    .HTML::campoOculto("fecha_inicio",date("Y-m-d"))
                    .HTML::campoOculto("codigo_planilla","")
                    .HTML::campoOculto("codigo_empresa",$codigo_empresa)
                    .HTML::campoOculto("proceso","A")
                    .HTML::campoOculto("cuota_minima",$preferencia_cuota_minima)
                    .HTML::campoOculto("mensaje_valor_prestamo",$textos["ERROR_VALOR_PRESTAMO"])
                    .HTML::campoOculto("mensaje_valor_cuota",$textos["ERROR_VALOR_CUOTA"])
                    .HTML::campoOculto("modulo", $modulo)
                    .HTML::campoOculto("valor_cuota_minima",$preferencia_cuota_minima)
                    .HTML::campoOculto("maneja_cheque", '0')
                    .HTML::campoOculto("error_no_cuentas_bancarias", $textos["CUENTAS_BANCARIAS_VACIAS"])
                    .HTML::campoOculto("valor_cuota_mayor",$textos["VALOR_CUOTA_MAYOR"])
                    .HTML::campoOculto("mensaje_vacios_campos",$textos["MENSAJE_VACIO_CAMPOS"])
                    .HTML::campoOculto("mensaje_vacio_valor_prestamo",$textos["VACIO_VALOR_PRESTAMO"])
                    .HTML::campoOculto("mensaje_vacio_valor_cuota",$textos["VACIO_VALOR_DESCONTAR"])
                    .HTML::campoOculto("nombre_empleado_vacio",$textos["VACIO_NOMBRE_EMPLEADO"])
                    .HTML::campoOculto("mensaje_actualizar_cuota",$textos["NO_ACTUALIZADO_CUOTAS"])
                    .HTML::campoOculto("boton_actualizar","0")
                    .HTML::campoOculto("actualizar_cuota","0")

                ),array(
                    HTML::listaSeleccionSimple("*tipo_documento", $textos["TIPO_DOCUMENTO"], HTML::generarDatosLista("tipos_documentos", "codigo", "descripcion"),"", array("title" => $textos["AYUDA_TIPO_DOCUMENTO"],"onchange" => "recargarDatosDocumento()")),
                    HTML::listaSeleccionSimple("cuenta_bancaria", $textos["CUENTA_BANCARIA"], $cuentas_bancarias, "", array("class" => $oculto,$banco_disabled => $banco_disabled, "onChange" => "consecutivoCheque();")),
                    HTML::campoTextoCorto("*consecutivo_cheque", $textos["CONSECUTIVO_CHEQUE"], 10, 10, $consecutivo_cheque, array("title" => $textos["AYUDA_CONSECUTIVO_CHEQUE"], "class" => $oculto, "readonly" => "readonly",$banco_disabled => $banco_disabled)),
                ),
                array(
                    HTML::campoTextoCorto("*consecutivo_documento", $textos["CONSECUTIVO_DOCUMENTO"], 10, 10, $consecutivo_documento, array("title" => $textos["AYUDA_CONSECUTIVO_DOCUMENTO"],$read => $read)),
                    HTML::campoTextoCorto("*selector2", $textos["CUENTA"], 40, 255, "", array("title" => $textos["AYUDA_CUENTA"], "class" => "autocompletable"))
                   .HTML::campoOculto("codigo_contable", ""),
                ),
                array(
                    HTML::listaSeleccionSimple("*codigo_transaccion_descontar", $textos["TRANSACCION_CONTABLE_DESCONTAR"],$listado_transacciones_contables_descontar,"", array("title" => $textos["AYUDA_TRANSACCION_CONTABLE_DESCONTAR"])),
                    HTML::listaSeleccionSimple("*codigo_transaccion_cobrar", $textos["TRANSACCION_CONTABLE_COBRAR"],$listado_transacciones_contables_cobrar,"", array("title" => $textos["AYUDA_TRANSACCION_CONTABLE_COBRAR"])),
                ),
                array(
                    HTML::listaSeleccionSimple("*concepto_prestamo", $textos["CONCEPTO_PRESTAMO"], HTML::generarDatosLista("conceptos_prestamos", "codigo", "descripcion"), "", array("title" => $textos["AYUDA_CONCEPTO_PRESTAMO"])),
                    HTML::campoTextoCorto("*valor_prestamo", $textos["VALOR_PRESTAMO"], 10, 20, $preferencia_cuota_minima, array("title" => $textos["AYUDA_VALOR_PRESTAMO"],"onKeyPress" => "return campoEntero(event)", "onKeyUp" => "valorPrestamo();")),
                    HTML::campoTextoCorto("*valor_descuento", $textos["VALOR_DESCUENTO"], 10, 20, $preferencia_cuota_minima, array("title" => $textos["AYUDA_VALOR_DESCUENTO"],"onKeyPress" => "return campoEntero(event)","onKeyUp" => "actualizarCuotas();"))
                ),
                array(
                    HTML::listaSeleccionSimple("*forma_pago_prestamo", $textos["FORMA_PAGO"],"", "", array("title" => $textos["AYUDA_FORMA_PAGO"],"onchange" => "removerTabla();")),
                ),
                array(
                    HTML::campoTextoLargo("observaciones", $textos["OBSERVACIONES"], 4, 50, "", array("title" => $textos["AYUDA_OBSERVACIONES"]))
                )
            );

            $funciones["FECHAS_GENERADAS"]   = "validarCamposObligatorios();";
            $formularios["FECHAS_GENERADAS"] = array(

                array(
                    HTML::boton("generar",$textos["GENERAR_FECHAS"],"generarTablaPagos();","adicionar"),
                    HTML::contenedor(HTML::generarTabla(
                            array("id","","FECHA_DESCUENTO","SALDO_ACTUAL","PERMITE_DESCUENTO"),
                                            "",
                            array("I","C","I", "I"),
                                            "listaItemsPagos",
                            false
                        )
                    )
                ),

            );
            // Definicion de botones
            $botones = array(HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar"));
            $contenido = HTML::generarPestanas($formularios, $botones,"",$funciones);
            // Enviar datos para la generacion del formulario al script que origino la peticion
            $respuesta[0] = $error;
            $respuesta[1] = $titulo;
            $respuesta[2] = $contenido;
        }
    }

    HTTP::enviarJSON($respuesta);
}

// Adicionar los datos provenientes del formulario
elseif (!empty($forma_procesar)) {
    // Asumir por defecto que no hubo error
    $error     = false;
    $mensaje   = $textos["ITEM_ADICIONADO"];
    $continuar = true;

    // Validar que el tipo de documento genera cheque tenga cuenta
    if (!empty($forma_tipo_documento)){
        $genera_cheques = SQL::obtenerValor("tipos_documentos", "genera_cheque", "codigo = '".$forma_tipo_documento."'");
        if($genera_cheques=='1')
        {
            $consulta = SQL::seleccionar(array("buscador_cuentas_bancarias"), array("*"),"id_sucursal = '".$forma_codigo_sucursal."' AND id_documento = '".$forma_tipo_documento."'");
            if(SQL::filasDevueltas($consulta)){
                $continuar = false;
            }
        }else{
            $continuar = false;
        }
        $existe_concecutivo = false;

        // Guardar datos del documento que genera el movimiento contable
        $id_tabla   = SQL::obtenerValor("tablas", "id", "nombre_tabla = 'movimientos_prestamos_generados_empleados'");
        $manejo     = SQL::obtenerValor("tipos_documentos", "manejo_automatico", "codigo = '".$forma_tipo_documento."'");
        if ($manejo == 2) {
            $consecutivo_documento = SQL::obtenerValor("consecutivo_documentos", "MAX(consecutivo)", "codigo_sucursal = '".$forma_codigo_sucursal."' AND codigo_tipo_documento = '".$forma_tipo_documento."'");
            if(!$consecutivo_documento){
                $consecutivo_documento = 1;
            }else{
                $consecutivo_documento++;
                $existe_concecutivo=false;
            }
        } else {
            $consecutivo_documento = $forma_consecutivo_documento;
            $existe_concecutivo = SQL::existeItem("consecutivo_documentos","consecutivo", $consecutivo_documento, "codigo_sucursal = '".$forma_codigo_sucursal."' AND codigo_tipo_documento = '".$forma_tipo_documento."'");
        }
    }
    // Validar que se hayan generado fechas
    if($forma_boton_actualizar == '1'){
        $error   = true;
        $mensaje = $textos["NO_ACTUALIZADO_TABLA"];
    }elseif(!isset($forma_fechas_pago[0])){
        $error   = true;
        $mensaje = $textos["ERROR_GENERAR_FECHAS"];
    }elseif($forma_actualizar_cuota == '1'){
        $error   = true;
        $mensaje = $textos["NO_ACTUALIZADO_CUOTAS"];
    }elseif($forma_tipo_documento==0){
        $error   = true;
        $mensaje = $textos["ERROR_TIPO_DOCUMENTO"];
    }elseif(empty($forma_codigo_contable)){
        $error   = true;
        $mensaje = $textos["ERROR_CODIGO_CONTABLE"];
    }elseif($continuar){
        $error   = true;
        $mensaje = $textos["CUENTAS_BANCARIAS_VACIAS"];
    }elseif($existe_concecutivo){
        $error   = true;
        $mensaje = $textos["CONSECUTIVO_DOCUMENTO_EXISTE"];
    }elseif (empty($forma_documento_empleado)){
        $error   = true;
        $mensaje = $textos["ERROR_VACIO_EMPLEADO"];
    }elseif (empty($forma_consecutivo_documento)){
        $error   = true;
        $mensaje = $textos["CONSECUTIVO_DOCUMENTO_VACIO"];
    }elseif(empty($forma_codigo_transaccion_descontar)){
        $error   = true;
        $mensaje = $textos["ERROR_VACIO_TRANSACCION"];
    }elseif(empty($forma_valor_prestamo)){
        $error   = true;
        $mensaje = $textos["ERROR_VACIO_VALOR_PRESTAMO"];
    }elseif(empty($forma_valor_descuento)){
        $error   = true;
        $mensaje = $textos["ERROR_VACIO_VALOR_ABONO"];
    }else{
        ///////////////////////////////////////
        $vistaConsulta                        = "sucursal_contrato_empleados";
        $columnas                             = SQL::obtenerColumnas($vistaConsulta);
        $consulta_sucursal_contrato_empleados = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo_empresa='".$forma_codigo_empresa."' AND documento_identidad_empleado='".$forma_documento_empleado."'", "", "fecha_ingreso_sucursal DESC", 0, 1); ////////////
        $datos_sucursal_contrato_empleados    = SQL::filaEnObjeto($consulta_sucursal_contrato_empleados);
        $tipo_documento                       = $forma_tipo_documento;
        /*$sentido                              = SQL::obtenerValor("transacciones_contables_empleado","sentido","codigo = '".$forma_codigo_transaccion_contable."'");
        $codigo_contable                      = SQL::obtenerValor("transacciones_contables_empleado","codigo_contable","codigo = '".$forma_codigo_transaccion_contable."'");*/

        // Genero la llave de la tabla
        $tipo_comprobante = SQL::obtenerValor("tipos_documentos", "codigo_comprobante", "codigo = '".$tipo_documento."'");
        $llave_tabla      = $datos_sucursal_contrato_empleados->codigo_sucursal.'|'.$forma_documento_empleado.'|'.$tipo_comprobante.'|0|'.$forma_tipo_documento.'|'.str_pad($consecutivo_documento,8,"0", STR_PAD_LEFT).'|'.Date("Y-m-d");

        if(SQL::existeItem("consecutivo_documentos","llave_tabla",$llave_tabla)){
            $error   = true;
            $mensaje = $textos["ERROR_EXISTE_CUENTA"];
        }else{
            $datos = array (
                "codigo_sucursal"             => $datos_sucursal_contrato_empleados->codigo_sucursal,
                "codigo_tipo_documento"       => $forma_tipo_documento,
                "fecha_registro"              => $forma_fecha_prestamo,
                "documento_identidad_tercero" => $forma_documento_empleado,
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
            } else {
                // Datos de la cuenta afectada
                $tipo_documento_genera_cheque = SQL::obtenerValor("tipos_documentos", "genera_cheque", "codigo = '".$tipo_documento."'");
                $flujo_efectivo               = SQL::obtenerValor("plan_contable","flujo_efectivo","codigo_contable='".$forma_codigo_contable."'");

                if($tipo_documento_genera_cheque=='1'){
                    $llave_cuenta_bancaria          = explode("|",$forma_cuenta_bancaria);
                    $codigo_sucursal_pertence       = $llave_cuenta_bancaria[0];
                    $tipo_documento_cuenta_bancaria = $llave_cuenta_bancaria[1];
                    $codigo_sucursal_banco          = $llave_cuenta_bancaria[2];
                    $codigo_iso                     = $llave_cuenta_bancaria[3];
                    $codigo_dane_departamento       = $llave_cuenta_bancaria[4];
                    $codigo_dane_municipio          = $llave_cuenta_bancaria[5];
                    $codigo_banco                   = $llave_cuenta_bancaria[6];
                    $numero                         = $llave_cuenta_bancaria[7];

                }else{
                    $codigo_sucursal_pertence       = '0';
                    $tipo_documento_cuenta_bancaria = '0';
                    $codigo_sucursal_banco          = '0';
                    $codigo_iso                     = '';
                    $codigo_dane_departamento       = '';
                    $codigo_dane_municipio          = '';
                    $codigo_banco                   = '0';
                    $numero                         = '';
                }
                $consecutivo = (int)SQL::obtenerValor("control_prestamos_empleados","max(consecutivo)","");
                if($consecutivo){
                    $consecutivo++;
                }else{
                    $consecutivo=1;
                }

                $datos = array (
                    "fecha_generacion"                      => $forma_fecha_prestamo,
                    ///contrato_sucursal_empleado///
                    "codigo_empresa"                        => $forma_codigo_empresa,
                    "documento_identidad_empleado"          => $forma_documento_empleado,
                    "fecha_ingreso"                         => $datos_sucursal_contrato_empleados->fecha_ingreso,
                    "codigo_sucursal"                       => $datos_sucursal_contrato_empleados->codigo_sucursal,
                    "fecha_ingreso_sucursal"                => $datos_sucursal_contrato_empleados->fecha_ingreso_sucursal,
                    //////////////////////////////
                    "consecutivo"                           => $consecutivo,
                    "codigo_tipo_documento"                 => $tipo_documento,
                    "consecutivo_documento"                 => $consecutivo_documento,
                    /////////////////////////////
                    "codigo_transaccion_contable_descontar" => $forma_codigo_transaccion_descontar,
                    "codigo_transaccion_contable_cobrar"    => $forma_codigo_transaccion_cobrar,
                    ////////////////////////////
                    "concepto_prestamo"                     => $forma_concepto_prestamo,
                    "observaciones"                         => $forma_observaciones,
                    "valor_total"                           => $forma_valor_prestamo,
                    "valor_pago"                            => $forma_valor_descuento,
                    "forma_pago"                            => $forma_forma_pago_prestamo,
                    "fecha_registro"                        => date("Y-m-d H:i:s"),
                    "codigo_usuario_registra"               => $sesion_codigo_usuario,
                    "codigo_usuario_modifica"               => $sesion_codigo_usuario,
                    "valor_saldo"                           => $forma_valor_prestamo
                );

                $insertar = SQL::insertar("control_prestamos_empleados", $datos);
                if (!$insertar) {
                    // Elimino el consecutivo de documento
                    $llave_consecutivo_documento  = " codigo_sucursal='".$datos_sucursal_contrato_empleados->codigo_sucursal."' AND codigo_tipo_documento='".$forma_tipo_documento."'";
                    $llave_consecutivo_documento .= " AND documento_identidad_tercero='".$forma_documento_empleado."' AND fecha_registro='".$forma_fecha_prestamo."' AND consecutivo='".$consecutivo_documento."'";
                    $eliminar                     = SQL::eliminar("consecutivo_documentos",$llave_consecutivo_documento);

                    $error   = true;
                    $mensaje = $textos["ERROR_ADICIONAR_CONTRO_PRESTAMO"];
                }else{

                    $codigo_empresa_auxiliar     = "";
                    $codigo_anexo_contable       = "";
                    $codigo_auxiliar_contable    = "0";

                    $datos= array(
                        ///////////////////////////////////
                        "documento_identidad_empleado"   => $forma_documento_empleado,
                        "fecha_generacion"               => $forma_fecha_prestamo,
                        "consecutivo"                    => $consecutivo,
                        "concepto_prestamo"              => $forma_concepto_prestamo,
                        "codigo_empresa_auxiliar"        => $codigo_empresa_auxiliar,
                        "codigo_anexo_contable"          => $codigo_anexo_contable,
                        "codigo_auxiliar_contable"       => $codigo_auxiliar_contable,
                        //////DATOS CUENTA AFECTA/////////
                        "flujo_efectivo"                 => $flujo_efectivo,
                        "codigo_plan_contable"           => $forma_codigo_contable,
                        "sentido"                        => "D",
                        "valor_movimiento"               => $forma_valor_prestamo,
                        /////////CUENTA BANCARIA///////////
                        "codigo_sucursal_pertence"       => $codigo_sucursal_pertence,
                        "tipo_documento_cuenta_bancaria" => $tipo_documento_cuenta_bancaria,
                        "codigo_sucursal_banco"          => $codigo_sucursal_banco,
                        "codigo_iso"                     => $codigo_iso,
                        "codigo_dane_departamento"       => $codigo_dane_departamento,
                        "codigo_dane_municipio"          => $codigo_dane_municipio,
                        "codigo_banco"                   => $codigo_banco,
                        "numero"                         => $numero
                    );

                    $insertar = SQL::insertar("movimientos_prestamos_empleados", $datos);

                    if (!$insertar) {
                        // Control prestamos empleados
                        $condicion_llave_prestamo  = " documento_identidad_empleado='".$forma_documento_empleado."' AND consecutivo='".$consecutivo."'";
                        $condicion_llave_prestamo .= " AND fecha_generacion='".$forma_fecha_prestamo."' AND concepto_prestamo='".$forma_concepto_prestamo."'";
                        $eliminar = SQL::eliminar("control_prestamos_empleados",$condicion_llave_prestamo);
                        // Elimino el consecutivo de documento
                        $llave_consecutivo_documento  = " codigo_sucursal='".$datos_sucursal_contrato_empleados->codigo_sucursal."' AND codigo_tipo_documento='".$forma_tipo_documento."'";
                        $llave_consecutivo_documento .= " AND documento_identidad_tercero='".$forma_documento_empleado."' AND fecha_registro='".$forma_fecha_prestamo."' AND consecutivo='".$consecutivo_documento."'";
                        $eliminar = SQL::eliminar("consecutivo_documentos",$llave_consecutivo_documento);

                        $error   = true;
                        $mensaje = $textos["ERROR_ADICIONAR_MOVIMIENTO_PRESTAMO"];
                    }else{
                        for($id = 0;!empty($forma_fechas_pago[$id]); $id++){
                            $datos = array (
                                /////////////////////////////
                                "documento_identidad_empleado"   => $forma_documento_empleado,
                                "fecha_generacion"               => $forma_fecha_prestamo,
                                "consecutivo"                    => $consecutivo,
                                "concepto_prestamo"              => $forma_concepto_prestamo,
                                ////////////////////////////
                                "fecha_pago"                     => $forma_fechas_pago[$id],
                                "valor_saldo"                    => $forma_valor_saldo[$id],
                                "descuento"                      => "1",
                                "pagada"                         => "0",
                                "valor_descuento"                => $forma_valor_descuentos[$id]
                            );

                            $insertar = SQL::insertar("fechas_prestamos_empleados", $datos);
                            if (!$insertar) {
                                // Elimino todos los valores anteriores
                                // Control prestamos empleados
                                $condicion_llave_prestamo  = " documento_identidad_empleado='".$forma_documento_empleado."' AND consecutivo='".$consecutivo."'";
                                $condicion_llave_prestamo .= " AND fecha_generacion='".$forma_fecha_prestamo."' AND concepto_prestamo='".$forma_concepto_prestamo."'";
                                $eliminar                  = SQL::eliminar("fechas_prestamos_empleados",$condicion_llave_prestamo);
                                $eliminar                  = SQL::eliminar("movimientos_prestamos_empleados",$condicion_llave_prestamo);
                                $eliminar                  = SQL::eliminar("control_prestamos_empleados",$condicion_llave_prestamo);
                                // Elimino el consecutivo de documento
                                $llave_consecutivo_documento  = " codigo_sucursal='".$datos_sucursal_contrato_empleados->codigo_sucursal."' AND codigo_tipo_documento='".$forma_tipo_documento."'";
                                $llave_consecutivo_documento .= " AND documento_identidad_tercero='".$forma_documento_empleado."' AND fecha_registro='".$forma_fecha_prestamo."' AND consecutivo='".$consecutivo_documento."'";
                                $eliminar                     = SQL::eliminar("consecutivo_documentos",$llave_consecutivo_documento);

                                $error   = true;
                                $mensaje = $textos["ERROR_ADICIONAR_FECHAS"];
                                break;
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

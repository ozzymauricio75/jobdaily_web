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

   // Devolver datos para autocompletar la búsqueda
if(isset($url_completar)){//Validado
    if ($url_item == "selector2") {
        echo SQL::datosAutoCompletar("seleccion_plan_contable", $url_q);
    }
    exit;
}

   // Devolver datos para recargar informacion requerida
// Devolver datos para recargar informacion requerida
if(isset($url_recargarDatosDocumento)){
    $datos = array();
    // Obtener consecutivo de documento si tiene manejo automatico
    $manejo         = SQL::obtenerValor("tipos_documentos", "manejo_automatico", "codigo = '".$url_documento."'");
    if ($manejo == '2') {
        $consecutivo_documento  = SQL::obtenerValor("consecutivo_documentos", "MAX(consecutivo)", "codigo_sucursal = '".$url_sucursal."' AND codigo_tipo_documento = '".$url_documento."'");
        if(!$consecutivo_documento){
            $consecutivo_documento = 1;
        }else{
            $consecutivo_documento++;
        }
        $datos["consecutivo_documento"]   = $consecutivo_documento;
    } else {
        $datos["consecutivo_documento"]   = 0;
    }

    // Obtener cuentas bancarias si genera cheques
    $cheques   = SQL::obtenerValor("tipos_documentos", "genera_cheque", "codigo = '$url_documento'");
    $datos["genera_cheque"] = $cheques;
    if ($cheques == '1') {
        $primer_cuenta  = false;
        $consulta       = SQL::seleccionar(array("buscador_cuentas_bancarias"), array("*"),"id_sucursal = '$url_sucursal' AND id_documento = '$url_documento'");
        if(SQL::filasDevueltas($consulta)){
            while($datos_cuenta = SQL::filaEnObjeto($consulta)){
                if($primer_cuenta == false){
                    $primer_cuenta = $datos_cuenta->id;
                }
                $llave_cuenta   = explode('|',$datos_cuenta->id);
                $id_plan_cuenta = SQL::obtenerValor("cuentas_bancarias", "codigo_plan_contable", "codigo_sucursal = '".$llave_cuenta[0]."' AND codigo_tipo_documento = '".$llave_cuenta[1]."' AND codigo_banco = '".$llave_cuenta[6]."' AND numero = '".$llave_cuenta[7]."' AND codigo_sucursal_banco = '".$llave_cuenta[2]."'");
                $datos[$datos_cuenta->id."|".$id_plan_cuenta] = $datos_cuenta->BANCO." - No. ".$datos_cuenta->NUMERO;
            }
            $llave_cuenta   = explode('|',$primer_cuenta);
            $consecutivo_cheque = SQL::obtenerValor("consecutivo_cheques", "MAX(consecutivo)", "codigo_tipo_documento = '".$llave_cuenta[1]."' AND codigo_banco = '".$llave_cuenta[6]."' AND numero = '".$llave_cuenta[7]."'");
            if(!$consecutivo_cheque){
                $consecutivo_cheque = 1;
            }else{
                $consecutivo_cheque++;
            }
            $datos["consecutivo_cheque"] = $consecutivo_cheque;
        }
    }
    HTTP::enviarJSON($datos);
    exit;
}

 ////////////////////////////////////
  if(isset($url_recargar_consecutivo_cheque)){
    $llave_cuenta   = explode('|',$url_cuenta);
    $consecutivo_cheque = SQL::obtenerValor("consecutivo_cheques", "MAX(consecutivo)", "codigo_tipo_documento = '".$llave_cuenta[1]."' AND codigo_banco = '".$llave_cuenta[6]."' AND numero = '".$llave_cuenta[7]."'");
    if(!$consecutivo_cheque){
        $consecutivo_cheque = 1;
    }else{
        $consecutivo_cheque++;
    }

    $cuenta = SQL::obtenerValor("seleccion_plan_contable_transacciones","SUBSTRING_INDEX(cuenta,'|',1)","id = '".$llave_cuenta[8]."'");

    unset($llave_cuenta[8]);

    $llave = implode('|',$llave_cuenta);

    $auxiliar = SQL::obtenerValor("buscador_cuentas_bancarias","id_auxiliar","id = '".$llave."'");

    $descripcion = SQL::obtenerValor("seleccion_auxiliares_contables","descripcion","id = '".$auxiliar."'");

    $datos = array($consecutivo_cheque,$cuenta,$auxiliar,$descripcion,$llave);
    HTTP::enviarJSON($datos);
    exit;
}

 ///////////////////////////////////
 if(isset($url_generarDatosTabla)){
   ///Calculo de numero de fechas a adicionar para completar el pago///
   $saldo_actual        = $url_valorPrestamo;
   $numero_fechas_abono = ceil($url_valorPrestamo/$url_valorCuota);
   $condicionif         =true;
   $dia_fecha           ="";

   if($url_formaPago=="2" || $url_formaPago=="3"){
    $numero_fechas_abono *=2;
   }

   $consulta = SQL::seleccionar(array("fechas_planillas"),array("*"),"codigo_planilla='$url_codigoPlanilla' AND fecha >= '$url_fechaInicio'","","",0,$numero_fechas_abono);
   $numero_filas = SQL::filasDevueltas($consulta);
   $datos_envio = array();

   if($numero_filas){
   $utilmo_saldo = "";
   $utilma_fecha="";
   $dato_inicio = (int)($numero_filas)+1;
   }else{
   $utilmo_saldo = $url_valorPrestamo;
   $utilma_fecha=$url_fechaInicio;
   $dato_inicio = 0;
   }

   while($datosfechas = SQL::filaEnObjeto($consulta)){
       $fechas_descuento = explode("-",$datosfechas->fecha);
       $dia_fecha        = (int)$fechas_descuento[2];
       ////Determino la fechas por el periodo de pago/////
        if($url_formaPago=="2"){
            $condicionif= $dia_fecha<=15;
        }elseif($url_formaPago == "3"){
            $condicionif= $dia_fecha > 15;
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

               $datos_envio[]   = $datosfechas->fecha.','.$saldo_actual.','.$valor_descuento;
               $utilma_fecha    = $datosfechas->fecha;
               $utilmo_saldo    = $saldo_actual;

       }
   }
   ////////completo el el registro del acuerdo de pago//////
   for($i=$dato_inicio;$i<=$numero_fechas_abono;$i++)
   {
       $saldo_anterior  = $utilmo_saldo;
       $utilmo_saldo   -= $url_valorCuota;
       $valor_descuento = $url_valorCuota;

       $fecha = getdate(strtotime($utilma_fecha));
       $utilma_fecha = date("Y-m-d", mktime(($fecha["hours"]),($fecha["minutes"]),($fecha["seconds"]),($fecha["mon"]+1),($fecha["mday"]),($fecha["year"])));

       if($utilmo_saldo< 0){
           $utilmo_saldo   = 0;
           $valor_descuento = $saldo_anterior;
           $i=$numero_fechas_abono+1;
       }
        if($utilmo_saldo!=0 && $valor_descuento!=0 ){
            $datos_envio[]   = $utilma_fecha.','.$utilmo_saldo.','.$valor_descuento;
        }
   }
     HTTP::enviarJSON($datos_envio);
     exit;
 }

/// Generar el formulario para la captura de datos
if (!empty($url_generar)){

    //// Verificar que se haya enviado el ID del elemento a consultar
    if(empty($url_id)){
        $error     = $textos["ERROR_CONSULTAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    }else{

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
            $error  = "";
            $titulo = $componente->nombre;


            ///Obtener lista de sucursales para selección///
            $codigo_empresa            = SQL::obtenerValor("sucursales", "codigo_empresa", "codigo = '$sesion_sucursal'");
            $preferencia_cuota_minima  = SQL::obtenerValor("preferencias","valor","variable = 'valor_cuota_minima_pago' AND codigo_empresa='".$codigo_empresa."' AND tipo_preferencia='2'");

            $llave_principal               = explode("|",$url_id);
            $documento_identidad_empleado  = $llave_principal[0];
            $fecha_generacion              = $llave_principal[1];
            $consecutivo                   = $llave_principal[2];
            $concepto_prestamo             = $llave_principal[3];

            $condicion                     = "documento_identidad_empleado= '$documento_identidad_empleado' AND consecutivo='$consecutivo' AND fecha_generacion='$fecha_generacion' AND concepto_prestamo='$concepto_prestamo'";
            $consulta_fechas_prestamo      = SQL::seleccionar(array("fechas_prestamos_empleados"),array("*"),$condicion);
            $consulta_control_prestamo     = SQL::seleccionar(array("control_prestamos_empleados"),array("*"),$condicion);
            $datos_control_prestamo        = SQL::filaEnObjeto($consulta_control_prestamo);
            $forma_pago                    = $datos_control_prestamo->forma_pago;
            $estado_fecha_pago             = true;

            $vistaConsulta                        = "sucursal_contrato_empleados";
            $columnas                             = SQL::obtenerColumnas($vistaConsulta);
            $consulta_sucursal_contrato_empleados = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo_empresa='$codigo_empresa' AND documento_identidad_empleado='$documento_identidad_empleado'", "", "fecha_ingreso_sucursal DESC", 0, 1); ////////////
            $datos_sucursal_contrato_empleados    = SQL::filaEnObjeto($consulta_sucursal_contrato_empleados);

            /////Armar datos de tabla////
            $items_tabla          = array();
            $items_tabla_pagados  = array();
            $items_llaves         = array();
            $items_tabla_consulta = array();
            $contador             = 1;
            $genero_movimiento    = false;

            while($dato_prestamo = SQL::filaEnObjeto($consulta_fechas_prestamo)){

                $transaccion_contable_descontar = SQL::obtenerValor("transacciones_contables_empleado","descripcion","codigo='$datos_control_prestamo->codigo_transaccion_contable_descontar'");
                $transaccion_contable_cobrar    = SQL::obtenerValor("transacciones_contables_empleado","descripcion","codigo='$datos_control_prestamo->codigo_transaccion_contable_cobrar'");
                $concepto_prestamo              = SQL::obtenerValor("conceptos_prestamos","descripcion","codigo='$datos_control_prestamo->concepto_prestamo'");

                $condicion           = "documento_identidad_empleado='$documento_identidad_empleado' AND consecutivo_fecha_pago='$dato_prestamo->consecutivo' ";
                $condicion          .= " AND fecha_generacion_control ='$dato_prestamo->fecha_generacion' AND concepto_prestamo='$dato_prestamo->concepto_prestamo' ";
                $condicion          .= " AND fecha_pago='$dato_prestamo->fecha_pago'";

                $descuenta       = $dato_prestamo->descuento;
                $valor_saldo     = (int)$dato_prestamo->valor_saldo+ (int)$dato_prestamo->valor_descuento;
                $fecha_descuento = $dato_prestamo->fecha_pago;

                if ($dato_prestamo->pagada=='1'){

                    $items_tabla_pagados[] = array(
                        "1",
                        $dato_prestamo->fecha_pago,
                        number_format($dato_prestamo->valor_saldo,0,".",""),
                        number_format($dato_prestamo->valor_descuento,0,".","")
                    );
                } else {
                    $celda  = HTML::campoOculto("fechas_pago[]",$dato_prestamo->fecha_pago, array("class" => "fechas_pago"));
                    $celda .= HTML::campoOculto("valor_saldo[]",$dato_prestamo->valor_saldo, array("class" => "valor_saldo"));
                    $celda .= HTML::campoOculto("valor_descuentos[]",$dato_prestamo->valor_descuento, array("class" => "valor_descuentos"));
                    $celda .= HTML::campoOculto("descuenta[]",$dato_prestamo->descuento, array("class" => "descuenta"));

                    $consulta_movimiento = SQL::existeItem("movimiento_control_prestamos_empleados","fecha_pago",$dato_prestamo->fecha_pago,$condicion);
                    if($consulta_movimiento){
                        $disabled_campos   = "disabled";
                        $genero_movimiento = true;
                    }else{
                        $disabled_campos = "";
                    }

                    $valor_cuota =  HTML::campoTextoCorto("texto_".$contador,"",10,30,round($dato_prestamo->valor_descuento),array("title" => $textos["AYUDA_VALOR_DESCUENTO"],$disabled_campos => $disabled_campos,"onKeyUp" =>"mostrarBotonActualizar(this);", "onKeyPress" => "return campoEntero(event);"));
                    if(!$consulta_movimiento && $estado_fecha_pago){

                        if($descuenta=='0'){
                            $estado_check = true;
                        } else{
                            $estado_check = false;
                        }

                        $celda_check            = HTML::marcaChequeo("DesautorizaDescuento","",1,$estado_check,array("align" => "center" ,"onclick" => "cambiarEstadocheck(this);"));
                        $estado_fecha_pago      = false;
                        $ultima_fecha_pendiente = $contador;

                    }else{
                        $celda_check   = "";
                    }

                    $items_tabla[] = array(
                        $contador,
                        $celda.$celda_check,
                        $dato_prestamo->fecha_pago,
                        number_format($dato_prestamo->valor_saldo,0,".",""),
                        $valor_cuota,
                    );

                    if(!$consulta_movimiento){
                        $items_tabla_consulta[] = array(
                            "",
                            $dato_prestamo->fecha_pago,
                            $dato_prestamo->valor_saldo,
                            $dato_prestamo->valor_descuento,
                        );

                        $items_llaves[]=$dato_prestamo->fecha_pago;

                    }
                    $contador++;
                }
            }

            $items_llaves = implode("|",$items_llaves);

            if($forma_pago >='4' && $forma_pago <='8'){
                $forma_pago_usuario =  $forma_pago_semanal;
            }elseif($forma_pago == '1'){
                $forma_pago_usuario =  $forma_pago_mensual;
            }else{
                $forma_pago_usuario =  $forma_pago_quincenal;
            }
            ////Obtengo datos////
            $nombre_sucursal    = SQL::obtenerValor("sucursales","nombre","codigo='$datos_control_prestamo->codigo_sucursal'");
            $nombre_empleado    = SQL::obtenerValor("seleccion_empleados","SUBSTRING_INDEX(nombre_completo,'|',1)","id = '$documento_identidad_empleado'");

            $nombre_transaccion_descontar = SQL::obtenerValor("transacciones_contables_empleado","descripcion","codigo='$datos_control_prestamo->codigo_transaccion_contable_descontar'");
            $nombre_concepto    = SQL::obtenerValor("conceptos_prestamos","descripcion","codigo='$datos_control_prestamo->concepto_prestamo'");
            ////Definición de pestaña basica////
            $cuentas_bancarias  = array();
            $cheques            = SQL::obtenerValor("tipos_documentos", "genera_cheque", "codigo = '".$datos_control_prestamo->codigo_tipo_documento."'");

            if ($cheques == "1") {
                $primer_cuenta = false;
                $condicion_cuenta = "codigo_sucursal='' AND codigo_tipo_documento,codigo_sucursal_banco,codigo_iso,codigo_dane_departamento,codigo_dane_municipio,codigo_banco,numero";
                //$consulta_cuenta =;
                $consulta   = SQL::seleccionar(array("buscador_cuentas_bancarias"), array("*"),"id_sucursal='$datos_control_prestamo->codigo_sucursal'");

                if(SQL::filasDevueltas($consulta)){
                    while($datos_cuenta = SQL::filaEnObjeto($consulta)){

                        if($primer_cuenta == false){
                            $primer_cuenta = $datos_cuenta->id;
                        }

                        $llave_cuenta   = explode('|',$datos_cuenta->id);
                        $id_plan_cuenta = SQL::obtenerValor("cuentas_bancarias", "codigo_plan_contable", "codigo_sucursal = '".$llave_cuenta[0]."' AND codigo_tipo_documento = '".$llave_cuenta[1]."' AND codigo_banco = '".$llave_cuenta[6]."' AND numero = '".$llave_cuenta[7]."' AND codigo_sucursal_banco = '".$llave_cuenta[2]."'");
                        $cuentas_bancarias[$datos_cuenta->id."|".$id_plan_cuenta] = $datos_cuenta->BANCO." - No. ".$datos_cuenta->NUMERO;
                    }

                    $llave_cuenta   = explode('|',$primer_cuenta);
                    $consecutivo_cheque = SQL::obtenerValor("consecutivo_cheques", "MAX(consecutivo)", "codigo_tipo_documento = '".$llave_cuenta[1]."' AND codigo_banco = '".$llave_cuenta[6]."' AND numero = '".$llave_cuenta[7]."'");
                    if(!$consecutivo_cheque){
                        $consecutivo_cheque = 1;
                    }else{
                        $consecutivo_cheque++;
                    }
                }else{
                    $consecutivo_cheque = "";
                }
                $oculto = "";
                $banco_disabled         = "";
            }else{
                $cuentas_bancarias[0]   = "";
                $consecutivo_cheque     = "";
                $oculto                 = "oculto";
                $banco_disabled         = "disabled";
            }

            ///////////////////////////////////////////////////
            $tipo_documento_genera_cheque  = SQL::obtenerValor("tipos_documentos", "genera_cheque", "codigo = '$datos_control_prestamo->codigo_tipo_documento'");

            if($tipo_documento_genera_cheque=='1'){
                $oculto   = '';
                $disabled = "disabled";
            }else{
                $oculto   ='oculto';
                $disabled = '';
            }

            //////////////////////////////////////////////////
            ////////Consulta del consecutivo generado/////////
            $consecutivo_documento = $datos_control_prestamo->consecutivo_documento;
            /////////////////////////////////////////////////
            $codicion_movimiento  = "documento_identidad_empleado='$datos_control_prestamo->documento_identidad_empleado' AND consecutivo='$datos_control_prestamo->consecutivo'";
            $codicion_movimiento .= "AND fecha_generacion='$datos_control_prestamo->fecha_generacion'AND concepto_prestamo='$datos_control_prestamo->concepto_prestamo'";

            $consulta_movimientos_prestamos_empleados = SQL::seleccionar(array("movimientos_prestamos_empleados"),array("*"),$codicion_movimiento);
            $datos_movimiento                         = SQL::filaEnObjeto($consulta_movimientos_prestamos_empleados);
            /////////////////////////////////////////////////
            $codigo_contable = $datos_movimiento->codigo_plan_contable;
            $cuenta = SQL::obtenerValor("seleccion_plan_contable","SUBSTRING_INDEX(codigo_contable,'|',1)","id = '$codigo_contable'");
            $read   = "";
            $arreglo = array();

            $arreglo[] =  array(
                HTML::mostrarDato("mostra_fecha_prestamo", $textos["FECHA_PRESTAMO"],$datos_control_prestamo->fecha_generacion),
                HTML::campoOculto("fecha_prestamo",$datos_control_prestamo->fecha_generacion)
            );
            $cargar_funcion =  "cargarNuevosDatos();";
            $genero = $ultima_fecha_pendiente;

            $arreglo[] = array(
                HTML::mostrarDato("codigo_sucursal_muestra",  $textos["SUCURSAL_LABORA"],$nombre_sucursal),
                HTML::mostrarDato("nombre_empleado", $textos["EMPLEADO"],$nombre_empleado)
                .HTML::campoOculto("documento_empleado",$documento_identidad_empleado)
                .HTML::campoOculto("fecha_inicio",$datos_control_prestamo->fecha_generacion)
                .HTML::campoOculto("codigo_empresa",$datos_control_prestamo->codigo_empresa)
                .HTML::campoOculto("permite_descuento",$descuenta)
                .HTML::campoOculto("valor_saldo_llegada",$valor_saldo)
                .HTML::campoOculto("identificador_check",$ultima_fecha_pendiente)
                .HTML::campoOculto("genero_movimiento",$genero)
                .HTML::campoOculto("proceso","M")
                .HTML::campoOculto("items_llaves",$items_llaves)
                .HTML::campoOculto("fecha_marcada",$fecha_descuento)
                .HTML::campoOculto("valor_cuota_estable",$datos_control_prestamo->valor_pago)
                .HTML::campoOculto("tipo_documento",$datos_control_prestamo->codigo_tipo_documento)
                .HTML::campoOculto("concepto_prestamo",$datos_control_prestamo->concepto_prestamo)
                .HTML::campoOculto("codigo_transaccion_descontar",$datos_control_prestamo->codigo_transaccion_contable_descontar)
                .HTML::campoOculto("codigo_transaccion_cobrar",$datos_control_prestamo->codigo_transaccion_contable_cobrar)
                .HTML::campoOculto("concepto_prestamo",$datos_control_prestamo->concepto_prestamo)
                .HTML::campoOculto("valor_cuota_minima",$preferencia_cuota_minima)
                .HTML::campoOculto("codigo_sucursal",$datos_control_prestamo->codigo_sucursal)
                .HTML::campoOculto("error_no_cuentas_bancarias", $textos["CUENTAS_BANCARIAS_VACIAS"])
                .HTML::campoOculto("genero_movimiento",$genero_movimiento)
                .HTML::campoOculto("oculto_codigo_transaccion_contable_descontar",$datos_control_prestamo->codigo_transaccion_contable_descontar)
                .HTML::campoOculto("oculto_codigo_transaccion_contable_cobrar",$datos_control_prestamo->codigo_transaccion_contable_cobrar)
                .HTML::campoOculto("oculto_concepto_prestamo",$datos_control_prestamo->concepto_prestamo)
                .HTML::campoOculto("oculto_tipo_documento",$datos_control_prestamo->codigo_tipo_documento)
                .HTML::campoOculto("consecutivo_documento",$consecutivo_documento)
                .HTML::campoOculto("valor_cuota_mayor",$textos["VALOR_CUOTA_MAYOR"])
                .HTML::campoOculto("mensaje_actualizar_cuota",$textos["NO_ACTUALIZADO_CUOTAS"])
                .HTML::campoOculto("boton_actualizar","0")
                .HTML::campoOculto("actualizar_cuota","0")
                .HTML::campoOculto("cuota_minima",$preferencia_cuota_minima)
                .HTML::campoOculto("codigo_contable",$codigo_contable)
            );
            $tipo_documento = SQL::obtenerValor("tipos_documentos","descripcion","codigo='$datos_control_prestamo->codigo_tipo_documento'");
            $arreglo[] = array(
                 HTML::mostrarDato("*tipo_documento", $textos["TIPO_DOCUMENTO"], $tipo_documento)/*,
                 HTML::listaSeleccionSimple("cuenta_bancaria", $textos["CUENTA_BANCARIA"], $cuentas_bancarias, "", array("class" => $oculto,$banco_disabled => $banco_disabled, "onChange" => "consecutivoCheque();")), //,$disabled => $disabled
                 HTML::campoTextoCorto("*consecutivo_cheque", $textos["CONSECUTIVO_CHEQUE"], 10, 10, $consecutivo_cheque, array("title" => $textos["AYUDA_CONSECUTIVO_CHEQUE"], "class" => $oculto, "readonly" => "readonly",$banco_disabled => $banco_disabled)),*/
            );
            $arreglo[] = array(
                 HTML::mostrarDato("*consecutivo_documento", $textos["CONSECUTIVO_DOCUMENTO"], round($consecutivo_documento)),
                 HTML::mostrarDato("codigo_contable", $textos["CUENTA"], $cuenta)
            );
            $arreglo[] =array(
                HTML::mostrarDato("*codigo_transaccion_descontar", $textos["TRANSACCION_CONTABLE_DESCONTAR"],$transaccion_contable_descontar),
                HTML::mostrarDato("*codigo_transaccion_cobrar", $textos["TRANSACCION_CONTABLE_COBRAR"],$transaccion_contable_cobrar),
            );
            $arreglo[] = array(
                HTML::campoOculto("codigo_tipo_documento",$datos_control_prestamo->codigo_tipo_documento)
            );

            $arreglo[] = array(
                HTML::mostrarDato("*concepto_prestamo", $textos["CONCEPTO_PRESTAMO"], $concepto_prestamo),
                HTML::mostrarDato("*valor_prestamo", $textos["VALOR_PRESTAMO"], number_format($datos_control_prestamo->valor_total,0,".","")),
                HTML::campoTextoCorto("*valor_descuento", $textos["VALOR_DESCUENTO"], 10, 20,number_format($datos_control_prestamo->valor_pago,0,".",""), array("title" => $textos["AYUDA_VALOR_DESCUENTO"],"onKeyPress" => "return campoEntero(event)","onKeyUp" => "actualizarCuotas();"))
                //HTML::campoOculto("valor_descuento",$datos_control_prestamo->valor_pago)
            );

             $arreglo[] = array(
                HTML::listaSeleccionSimple("*forma_pago_prestamo", $textos["FORMA_PAGO"],$forma_pago_usuario,$forma_pago, array("title" => $textos["AYUDA_FORMA_PAGO"],"onchange" => "cargarNuevosDatos();"))
            );

             $arreglo[] = array(
                HTML::campoTextoLargo("observaciones", $textos["OBSERVACIONES"], 4, 50,$datos_control_prestamo->observaciones, array("title" => $textos["AYUDA_OBSERVACIONES"]))
            );

           $formularios["PESTANA_BASICA"] = $arreglo;

            $funciones["FECHAS_GENERADAS"]   = "validarCamposObligatorios();";
            if ( $items_tabla_pagados){
                $titulo_pagadas = array(HTML::mostrarDato("titulo_pagadas",$textos["DEUDA_PAGADA"],""));
                $pagadas = array(
                    HTML::contenedor(HTML::generarTabla(
                            array("id","FECHA_DESCUENTO", "SALDO_ACTUAL", "VALOR_CUOTA"),
                            $items_tabla_pagados,
                            array("C","I", "I"),
                            "listaItemsPagados",
                            false
                        )
                    )
                );
            } else {
                $titulo_pagadas = array(HTML::campoOculto("titulo_pagadas",""));
                $pagadas = array(HTML::campoOculto("no_pago",""));
            }
            $formularios["FECHAS_GENERADAS"] = array(
                $titulo_pagadas,
                $pagadas,
                array(
                    HTML::mostrarDato("titulo_debe",$textos["DEUDA_PENDIENTE"],"")
                ),
                array(
                    HTML::boton("generar",$textos["GENERAR_FECHAS"],$cargar_funcion,"adicionar"),
                    HTML::contenedor(HTML::generarTabla(
                            array("id","PERMITE_DESCUENTO","FECHA_DESCUENTO", "SALDO_ACTUAL", "VALOR_CUOTA"),
                            $items_tabla,
                            array("C","C","I", "I"),
                            "listaItemsPagos",
                            false
                        )
                    )
                )
          );

        $formularios["FECHAS_INICIALES"] = array(
                array(
                   HTML::contenedor(HTML::generarTabla(
                                    array("id","FECHA_DESCUENTO", "SALDO_ACTUAL", "VALOR_CUOTA"),
                                    $items_tabla_consulta,
                                    array("I","I", "I"),
                                    "listaItemsPagosIniciales",
                                    false
                            )
                        )
                )
        );

            //Definición de botones
            $botones = array(
              HTML::boton("botonAceptar", $textos["ACEPTAR"], "modificarItem('$url_id');", "aceptar")
            );
            $contenido = HTML::generarPestanas($formularios,$botones,"",$funciones);

            /// Enviar datos para la generación del formulario al script que origino la petición
            $respuesta    = array();
            $respuesta[0] = $error;
            $respuesta[1] = $titulo;
            $respuesta[2] = $contenido;
        }
    }
    HTTP::enviarJSON($respuesta);
}elseif(!empty($forma_procesar)){

    /// Asumir por defecto que no hubo error
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];
    $continuar = true;
    ///////Validar que el tipo de documento genera cheque tenga cuenta//////
    if(isset($forma_tipo_documento)){

        $genera_cheques = SQL::obtenerValor("tipos_documentos", "genera_cheque", "codigo = '$forma_tipo_documento'");
        if($genera_cheques=='1')
        {
         $consulta       = SQL::seleccionar(array("buscador_cuentas_bancarias"), array("*"),"id_sucursal = '$forma_codigo_sucursal' AND id_documento = '$forma_tipo_documento'");
            if(SQL::filasDevueltas($consulta)){
                $continuar = false;
            }
        } else {
            $continuar = false;
        }
    }else{
        $continuar = false;
    }
    ///////////////////////////////////////////////////////////////////////
    $llave_principal              = explode("|",$forma_id);
    $documento_identidad_empleado = $llave_principal[0];
    $fecha_generacion             = $llave_principal[1];
    $consecutivo                  = $llave_principal[2];
    $concepto_prestamo            = $llave_principal[3];

    $condicion  = "documento_identidad_empleado= '$documento_identidad_empleado' AND consecutivo='$consecutivo' AND fecha_generacion='$fecha_generacion'";
    //////////////////////////////////////////////////
    $vistaConsulta                        = "sucursal_contrato_empleados";
    $columnas                             = SQL::obtenerColumnas($vistaConsulta);
    $consulta_sucursal_contrato_empleados = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo_empresa='$forma_codigo_empresa' AND documento_identidad_empleado='$forma_documento_empleado'", "", "fecha_ingreso_sucursal DESC", 0, 1); ////////////
    $datos_sucursal_contrato_empleados    = SQL::filaEnObjeto($consulta_sucursal_contrato_empleados);
    //$tipo_documento=SQL::obtenerValor("preferencias","valor","variable = 'codigo_prestamo_empleados' AND codigo_empresa='$forma_codigo_empresa' AND tipo_preferencia='2'");
    $tipo_documento=$forma_tipo_documento;
    ////////////////////////////////////////////////////////////////////
    //////////////Generó la llave de la tabla///////////////////////////
    $tipo_comprobante  = SQL::obtenerValor("tipos_documentos", "codigo_comprobante", "codigo = '$tipo_documento'");
    $llave_tabla = $datos_sucursal_contrato_empleados->codigo_sucursal.'|'.$forma_documento_empleado.'|'.$tipo_comprobante.'|0|'.$forma_tipo_documento.'|'.str_pad($forma_consecutivo_documento,8,"0", STR_PAD_LEFT).'|'.$fecha_generacion;
    ////////////////////////////////////////////////////////////////////
    /// Guardar datos del documento que genera el movimiento contable///
    if($llave_tabla!=$forma_llave_inicial_tabla){

        $id_tabla   = SQL::obtenerValor("tablas", "id", "nombre_tabla = 'movimientos_prestamos_generados_empleados'");
        $manejo     = SQL::obtenerValor("tipos_documentos", "manejo_automatico", "codigo = '".$forma_tipo_documento."'");
        if($manejo == 2){
            $consecutivo_documento = SQL::obtenerValor("consecutivo_documentos", "MAX(consecutivo)", "codigo_sucursal = '".$forma_codigo_sucursal."' AND codigo_tipo_documento = '".$forma_tipo_documento."'");
            if(!$consecutivo_documento){
                $consecutivo_documento = 1;
            }else{
                $consecutivo_documento++;
                $existe_concecutivo=false;
            }
        }else{
            $consecutivo_documento = $forma_consecutivo_documento;
            $existe_concecutivo = SQL::existeItem("consecutivo_documentos","consecutivo", $consecutivo_documento, "codigo_sucursal = '".$forma_codigo_sucursal."' AND codigo_tipo_documento = '".$forma_tipo_documento."'");
        }

    }else{
        $existe_concecutivo    = false;
        $consecutivo_documento = $forma_consecutivo_documento;
    }
     /////////////////////////////////////////////////////////////////////
    if($forma_boton_actualizar == '1'){
        $error = true;
        $mensaje = $textos["NO_ACTUALIZADO_TABLA"];
    }elseif(!isset($forma_fechas_pago[0])){
        $error = true;
        $mensaje = $textos["ERROR_GENERAR_FECHAS"];
    }elseif($forma_actualizar_cuota == '1'){
        $error = true;
        $mensaje = $textos["NO_ACTUALIZADO_CUOTAS"];
    }elseif(empty($forma_documento_empleado)){
        $error = true;
        $mensaje = $textos["ERROR_VACIO_EMPLEADO"];
    }elseif(empty($forma_valor_descuento)){
        $error = true;
        $mensaje = $textos["ERROR_VACIO_VALOR_ABONO"];
    }else {

        $fecha_registro = Date("Y-m-d");
        ///valido que no se haya henerado un movimiento///
        if($forma_genero_movimiento=='1'){
            $forma_codigo_transaccion_descontar = $forma_oculto_codigo_transaccion_contable_descontar;
            $forma_codigo_transaccion_cobrar    = $forma_oculto_codigo_transaccion_contable_cobrar;

            $forma_concepto_prestamo            = $forma_oculto_concepto_prestamo;
            $forma_tipo_documento               = $forma_oculto_tipo_documento;
        }

        $llave_tabla = $datos_sucursal_contrato_empleados->codigo_sucursal.'|'.$forma_documento_empleado.'|'.$tipo_comprobante.'|0|'.$forma_tipo_documento.'|'.str_pad($consecutivo_documento,8,"0", STR_PAD_LEFT).'|'.$fecha_generacion;

        $datos = array (
            "consecutivo"             => $consecutivo_documento,
            "llave_tabla"             => $llave_tabla,
        );

        $modificar = SQL::modificar("consecutivo_documentos",$datos,"llave_tabla='$forma_llave_inicial_tabla'");
        if(!$modificar){
            $error   = true;
            $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
        }else{
            ///////////Datos de la cuenta afectada////////////
            $tipo_documento_genera_cheque   = SQL::obtenerValor("tipos_documentos", "genera_cheque", "codigo = '$tipo_documento'");
            $flujo_efectivo = SQL::obtenerValor("plan_contable","flujo_efectivo","codigo_contable='$forma_codigo_contable'");

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

            $datos = array (
                "fecha_generacion"                      => $forma_fecha_prestamo,
                "codigo_tipo_documento"                 => $tipo_documento,
                /////////////////////////////
                "codigo_transaccion_contable_descontar" => $forma_codigo_transaccion_descontar,
                "codigo_transaccion_contable_cobrar"    => $forma_codigo_transaccion_cobrar,
                ////////////////////////////
                "concepto_prestamo"                     => $forma_concepto_prestamo,
                "observaciones"                         => $forma_observaciones,
                "valor_pago"                            => $forma_valor_descuento,
                "forma_pago"                            => $forma_forma_pago_prestamo,
                "codigo_usuario_modifica"               => $sesion_codigo_usuario
            );

            $insertar = SQL::modificar("control_prestamos_empleados", $datos,$condicion." AND concepto_prestamo='$concepto_prestamo'");

            if (!$insertar) {
                $error   = true;
                $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
            }else{
                $codigo_empresa_auxiliar     = "";
                $codigo_anexo_contable       = "";
                $codigo_auxiliar_contable    = "0";

                $datos = array(
                    "fecha_generacion"               => $forma_fecha_prestamo,
                    ///////////////////////////////////
                    "documento_identidad_empleado"   => $forma_documento_empleado,
                    "consecutivo"                    => $consecutivo,
                    "concepto_prestamo"              => $forma_concepto_prestamo,
                    "codigo_empresa_auxiliar"        => $codigo_empresa_auxiliar,
                    "codigo_anexo_contable"          => $codigo_anexo_contable,
                    "codigo_auxiliar_contable"       => $codigo_auxiliar_contable,
                    //////DATOS CUENTA AFECTA/////////
                    "flujo_efectivo"                 => $flujo_efectivo,
                    "codigo_plan_contable"           => $forma_codigo_contable,
                    /////////CUENTA BANCARIA/////////
                    "codigo_sucursal_pertence"       => $codigo_sucursal_pertence,
                    "tipo_documento_cuenta_bancaria" => $tipo_documento_cuenta_bancaria,
                    "codigo_sucursal_banco"          => $codigo_sucursal_banco,
                    "codigo_iso"                     => $codigo_iso,
                    "codigo_dane_departamento"       => $codigo_dane_departamento,
                    "codigo_dane_municipio"          => $codigo_dane_municipio,
                    "codigo_banco"                   => $codigo_banco,
                    "numero"                         => $numero
                );
                $insertar = SQL::modificar("movimientos_prestamos_empleados", $datos,$condicion." AND concepto_prestamo='$forma_concepto_prestamo'");
                //echo var_dump($insertar);
                if(!$insertar){
                    $error   = true;
                    $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
                }else{
                    $items_llaves = explode("|",$forma_items_llaves);

                    for($id = 0;$id<count($items_llaves); $id++){
                        $eliminar = SQL::eliminar("fechas_prestamos_empleados",$condicion." AND concepto_prestamo='$forma_concepto_prestamo' AND fecha_pago='$items_llaves[$id]'");
                    }
                    //////////////////////////////////////////
                    for($id =((int)$forma_identificador_check-1);!empty($forma_fechas_pago[$id]); $id++){

                        if($forma_fecha_marcada==$forma_fechas_pago[$id]){
                            $descuenta = $forma_permite_descuento;
                        }else{
                            $descuenta = $forma_descuenta[$id];
                        }

                        $datos = array (
                           /////////////////////////////
                            "documento_identidad_empleado"   => $forma_documento_empleado,
                            "fecha_generacion"               => $fecha_generacion,
                            "concepto_prestamo"              => $forma_concepto_prestamo,
                            "consecutivo"                    => $consecutivo,
                           ////////////////////////////
                            "fecha_pago"                     => $forma_fechas_pago[$id],
                            "valor_saldo"                    => $forma_valor_saldo[$id],
                            "descuento"                      => $descuenta,
                            "valor_descuento"                => $forma_valor_descuentos[$id]
                        );
                        $insertar = SQL::insertar("fechas_prestamos_empleados", $datos);

                         if (!$insertar) {
                            $error   = true;
                            $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
                            $mensaje = mysql_error();
                        }
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

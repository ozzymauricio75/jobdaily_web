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

// Devolver datos para autocompletar la busqueda
if (isset($url_completar)) {//Validado
    if ($url_item == "selector2") {
        echo SQL::datosAutoCompletar("seleccion_terceros", $url_q);
    }
    exit;
}

if(isset($url_obtenerDatosContrato) && isset($url_documento_empleado)){

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
         "2" => $textos["PRIMERA_QUINCENA"],
         "3" => $textos["SEGUNDA_QUINCENA"],
         "9" => $textos["PROPOCIONAL"],
     );

    $consulta_contrato_sucursal_empleado = SQL::seleccionar(array("sucursal_contrato_empleados"),array("codigo_planilla"),"documento_identidad_empleado='$url_documento_empleado'","","fecha_ingreso_sucursal DESC",0,1);
    $datos_contrato_sucursal_empleado    = SQL::filaEnObjeto($consulta_contrato_sucursal_empleado);

    $codigo_planilla  = $datos_contrato_sucursal_empleado->codigo_planilla;
    $periodo_pago     = SQL::obtenerValor("planillas","periodo_pago","codigo = '".$codigo_planilla."'");
    $datos_envio      = array();

    if($periodo_pago=='1'){
        $datos_envio = $forma_pago_mensual;
    }else if($periodo_pago=='2'){
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

// Generar el formulario para la captura de datos

if(!empty($url_generar)) {

    $mensaje   = $textos["MENSAJE"];
    $continuar = true;

    $consulta_sucursales              = SQL::seleccionar(array("sucursales"),array("*"),"codigo !='0'");
    $consulta_empleados               = SQL::seleccionar(array("ingreso_empleados"),array("*"),"documento_identidad_empleado!='0' AND estado='1'");
    $consulta_tipos_documentos        = SQL::seleccionar(array("tipos_documentos"),array("*"),"codigo !='0'");
    $consulta_plan_contable           = SQL::seleccionar(array("plan_contable"),array("*"));
    $consulta_conceptos_prestamos     = SQL::seleccionar(array("conceptos_prestamos"),array("*"),"codigo !='0'");

    $transacciones_contable_descuento = SQL::seleccionar(array("transacciones_contables_empleado"),array("*"),"codigo_concepto_transaccion_contable='11' AND sentido='C'");
    $transacciones_contable_empleado  = SQL::seleccionar(array("transacciones_contables_empleado"),array("*"),"codigo_concepto_transaccion_contable='11' AND sentido='D'");
    $transacciones_contable_pagar     = SQL::seleccionar(array("transacciones_contables_empleado"),array("*"),"codigo_concepto_transaccion_contable='40' AND sentido='C'");
    $transacciones_contable_pago      = SQL::seleccionar(array("transacciones_contables_empleado"),array("*"),"codigo_concepto_transaccion_contable='40' AND sentido='D'");

    if(SQL::filasDevueltas($transacciones_contable_descuento)== 0 ){
        $mensaje   .= $textos["MENSAJE_TRANSACION_DESCUENTO"];
        $continuar = false;
    }
    if(SQL::filasDevueltas($transacciones_contable_empleado)== 0 ){
        $mensaje   .= $textos["MENSAJE_TRANSACION_EMPLEADO"];
        $continuar = false;
    }
    if(SQL::filasDevueltas($transacciones_contable_pagar)== 0 ){
        $mensaje   .= $textos["MENSAJE_TRANSACION_PAGAR"];
        $continuar = false;
    }
    if(SQL::filasDevueltas($transacciones_contable_pago)== 0 ){
        $mensaje   .= $textos["MENSAJE_TRANSACION_PAGO"];
        $continuar = false;
    }
    if(SQL::filasDevueltas($consulta_sucursales)== 0 ){
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

    if(!$continuar){

        $respuesta    = array();
        $respuesta[0] = $mensaje;
        $respuesta[1] = "";
        $respuesta[2] = "";
    }else{
        //Determinar periodos de descutos
        $periodos_descuento = array(
          "0" => $textos["DESCUENTO_ILIMITADO"],
          "1" => $textos["DESCUENTO_FECHA"],
          "2" => $textos["DESCUENTO_TOPE"]
        );

        $error                 = "";
        $titulo                = $componente->nombre;
        $documento             = SQL::obtenerValor("tipos_documentos", "codigo", "codigo != '0' ORDER BY descripcion LIMIT 1");
        $modulo                = SQL::obtenerValor("componentes", "id_modulo", "id = '".$componente->id."'");
        $consecutivo_documento = "";
        $read                  = "";
        ////////////////////////////////////////////////////
        $transacciones_contable_descuento = HTML::generarDatosLista("transacciones_contables_empleado","codigo","descripcion","codigo_concepto_transaccion_contable='11' AND sentido='C'");
        $transacciones_contable_empleado  = HTML::generarDatosLista("transacciones_contables_empleado","codigo","descripcion","codigo_concepto_transaccion_contable='11' AND sentido='D'");
        $transacciones_contable_pagar     = HTML::generarDatosLista("transacciones_contables_empleado","codigo","descripcion","codigo_concepto_transaccion_contable='40' AND sentido='C'");
        $transacciones_contable_pago      = HTML::generarDatosLista("transacciones_contables_empleado","codigo","descripcion","codigo_concepto_transaccion_contable='40' AND sentido='D'");
        ////////////////////////////////////////////////////
        $cuentas_bancarias = array();
        $cheques       = SQL::obtenerValor("tipos_documentos", "genera_cheque", "codigo = '".$documento."'");
        if ($cheques == 1) {
            $primer_cuenta = false;
            $consulta = SQL::seleccionar(array("buscador_cuentas_bancarias"), array("*"));
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

        // Cargo las sucursales dependiendo del codigo de la empresa con que se incia seccion
        $codigo_empresa = SQL::obtenerValor("sucursales", "codigo_empresa", "codigo = '".$sesion_sucursal."'");
        // Obtener lista de sucursales para selección dependiendo a los permisos
        $consulta = SQL::seleccionar(array("sucursales"),array("*"),"codigo != 0 AND codigo_empresa = '".$codigo_empresa."'","","nombre");
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
            AND b.id_componente = '".$componente->id."' AND c.codigo_empresa = '".$codigo_empresa."'";

            $consulta = SQL::seleccionar($tablas, $columnas, $condicion);

            if (SQL::filasDevueltas($consulta)) {
                while ($datos = SQL::filaEnObjeto($consulta)) {
                    $sucursales[$datos->codigo] = $datos->nombre;
                }
            }
        }

        // Definicion de pestana Basica
        $formularios["PESTANA_BASICA"] = array(
            array(
                HTML::listaSeleccionSimple("*codigo_sucursal", $textos["SUCURSAL_LABORA"],$sucursales,$sesion_sucursal, array("title" => $textos["AYUDA_SUCURSAL_LABORA"],"onchange" => "limpiarCampo();recargarDatosDocumento();")),
                HTML::campoTextoCorto("*selector1", $textos["EMPLEADO"], 40, 255, "", array("title" => $textos["AYUDA_EMPLEADOS"],"onfocus" => "acLocalEmpleados(this);"))
               .HTML::campoOculto("documento_empleado", "")
               .HTML::campoOculto("valor_descuento_primera_semana", $textos["VALOR_DESCUENTO_PRIMERA_QUINCENA"])
               .HTML::campoOculto("valor_descuento_segunda_semana", $textos["VALOR_DESCUENTO_SEGUNDA_QUINCENA"])
               .HTML::campoOculto("valor_descuento_todo", $textos["VALOR_DESCUENTO_TODO"])
            ),
            array(
                HTML::campoTextoCorto("*fecha_inicio_descuento", $textos["FECHA_INICIO_DESCUENTO"], 10, 10,date("Y-m-d"), array("title" => $textos["AYUDA_PRESTAMO"], "class" => "selectorFecha")),
                HTML::campoTextoCorto("*selector2", $textos["NOMBRE_TERCERO"], 40, 255, "", array("title" => $textos["AYUDA_TERCERO"],"class" => "autocompletable"))
               .HTML::campoOculto("documento_tercero", "")
            ),
            array(
                HTML::campoTextoCorto("*obligacion", $textos["OBLIGACION"],15,10, "", array("title" => $textos["AYUDA_OBLIGACION"])),
            ),
            array(
                HTML::listaSeleccionSimple("*limite_descuento", $textos["LIMITE_DESCUENTO"],$periodos_descuento, "", array("title" => $textos["AYUDA_LIMITE_DESCUENTO"],"onchange" => "limiteDescuento(this)")),
                HTML::contenedor( HTML::campoTextoCorto("*fecha_limite_pago", $textos["FECHA_HASTA_DESCONTAR"], 10, 10,"0000-00-00", array("title" => $textos["AYUDA_PRESTAMO"], "class" => "selectorFecha")),array("id" => "fecha_limite_pago_contenedor","class" => "oculto","disabled" =>"disabled"  )),
                HTML::campoTextoCorto("*valor_tope_descuento", $textos["VALOR_TOPE_DESCUENTO"],13,10, "", array("title" => $textos["AYUDA_VALOR_TOPE_DESCUENTO"],"onKeyPress" => "return campoEntero(event)","class" => "oculto","disabled" =>"disabled"))
            ),
            array(
                HTML::listaSeleccionSimple("*periodo_pago", $textos["PERIODO_PAGO"],"", "", array("title" => $textos["AYUDA_PERIODO"],"onchange" => "tipoFormadePago();")),
                HTML::campoTextoCorto("*valor_descuento_1",$textos["VALOR_DESCUENTO"],13,10, "", array("onKeyPress" => "return campoEntero(event)", "class" => "oculto")),
                HTML::campoTextoCorto("*valor_descuento_2",$textos["VALOR_DESCUENTO"],13,10, "", array("onKeyPress" => "return campoEntero(event)", "class" => "oculto"))
            )
            );


        $formularios["TRANSACCION_CONTABLE"] = array(
            array(
                HTML::listaSeleccionSimple("*transaccion_contable_descuento", $textos["TRANSACCION_CONTABLE_DESCUENTO"],$transacciones_contable_descuento,"", array("title" => $textos["AYUDA_TRANSACCION_CONTABLE_DESCUENTO"]))
            ),
             array(
                HTML::listaSeleccionSimple("*transaccion_contable_empleado", $textos["TRANSACCION_CONTABLE_EMPLEADO"],$transacciones_contable_empleado,"", array("title" => $textos["AYUDA_TRANSACCION_CONTABLE_EMPLEADO"]))

            ),
            array(
                HTML::listaSeleccionSimple("*transaccion_contable_pagar", $textos["TRANSACCION_CONTABLE_PAGAR_TERCERO"],$transacciones_contable_pagar,"", array("title" => $textos["AYUDA_TRANSACCION_CONTABLE_PAGAR_TERCERO"]))
            ),
            array(
                HTML::listaSeleccionSimple("*transaccion_contable_pago", $textos["TRANSACCION_CONTABLE_PAGO_TERCERO"],$transacciones_contable_pago,"", array("title" => $textos["AYUDA_TRANSACCION_CONTABLE_PAGO_TERCERO"]))
            )

        );

        // Definicion de botones
        $botones = array(HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar"));
        $contenido = HTML::generarPestanas($formularios, $botones);
        // Enviar datos para la generacion del formulario al script que origino la peticion

        $respuesta[0] = $error;
        $respuesta[1] = $titulo;
        $respuesta[2] = $contenido;
    }


    HTTP::enviarJSON($respuesta);
}
// Adicionar los datos provenientes del formulario
elseif (!empty($forma_procesar)){

    $condicion = "codigo_empresa,documento_identidad_empleado,obligacion";

    if(empty($forma_documento_empleado)){
        $error   = true;
        $mensaje = $textos["ERROR_VACIO_EMPLEADO"];
    }elseif(empty($forma_documento_tercero)){
        $error   = true;
        $mensaje = $textos["ERROR_VACIO_TERCERO"];
    }elseif(empty($forma_obligacion)){
        $error   = true;
        $mensaje = $textos["ERROR_VACIO_OBLIGACION"];
    }elseif(isset($forma_valor_tope_descuento) && empty($forma_valor_tope_descuento)){
        $error   = true;
        $mensaje = $textos["ERROR_VACIO_VALOR_TOPE"];
    }elseif((empty($forma_valor_descuento_1) || empty($forma_valor_descuento_2)) && $forma_periodo_pago=="9"){
        $error    = true;
         $mensaje = $textos["ERROR_VACIO_VALOR_DESCUENTOS"];
        if(empty($forma_valor_descuento_1)){
            $mensaje .= $textos["ERROR_VACIO_VALOR_DESCUENTO_1"];
        }
        if(empty($forma_valor_descuento_2)){
            $mensaje .= $textos["ERROR_VACIO_VALOR_DESCUENTO_2"];
        }
    }elseif(empty($forma_valor_descuento_1)){
        $error   = true;
        $mensaje = $textos["ERROR_VACIO_VALOR_DESCUENTO"];
    }else{
        $error   = false;
        $mensaje = $textos["ITEM_ADICIONADO"];


        $empresa = SQL::obtenerValor("sucursales","codigo_empresa","codigo='".$forma_codigo_sucursal."'");

        $valor_descontar_mensual          = 0;
        $valor_descontar_primera_quincena = 0;
        $valor_descontar_segunda_quincena = 0;
        $valor_descontar_primera_semana   = 0;
        $valor_descontar_segunda_semana   = 0;
        $valor_descontar_tercera_semana   = 0;
        $valor_descontar_cuarta_semana    = 0;

        if($forma_periodo_pago=="9"){
            $valor_descontar_primera_quincena = $forma_valor_descuento_1;
            $valor_descontar_segunda_quincena = $forma_valor_descuento_2;
        }elseif($forma_periodo_pago=="1"){
             $valor_descontar_mensual = $forma_valor_descuento_1;
        }elseif($forma_periodo_pago=="2"){
             $valor_descontar_primera_quincena = $forma_valor_descuento_1;
        }elseif($forma_periodo_pago=="3"){
             $valor_descontar_segunda_quincena = $forma_valor_descuento_1;
        }

        if(!isset($forma_fecha_limite_pago)){
            $forma_fecha_limite_pago = "0000-00-00";
        }

        if(!isset($forma_valor_tope_descuento)){
            $forma_valor_tope_descuento = 0;
        }

        if(!isset($forma_descuento_ilimitado)){
            $forma_descuento_ilimitado = 0;
        }

        $fecha_registro = date("Y-m-d H:i:s");

        $datos = array(
            "limite_descuento"                   => $forma_limite_descuento,
            "fecha_generacion"                   => $fecha_registro,
            "fecha_inicio_descuento"             => $forma_fecha_inicio_descuento,
            //contrato_sucursal_empleado//
            "codigo_sucursal"                    => $forma_codigo_sucursal,
            "codigo_empresa"                     =>  $empresa,
            "documento_identidad_empleado"       =>  $forma_documento_empleado,
            //////////////////////////////
            "documento_identidad_tercero"        => $forma_documento_tercero,
            "autorizacion_descuento_nomina"      => '1',
            "obligacion"                         => $forma_obligacion,
            "valor_tope_descuento"               => $forma_valor_tope_descuento,
            "periodo_pago"                       => $forma_periodo_pago,
            //////////////////////////////
            "valor_descontar_mensual"            => $valor_descontar_mensual,
            "valor_descontar_primera_quincena"   => $valor_descontar_primera_quincena,
            "valor_descontar_segunda_quincena"   => $valor_descontar_segunda_quincena,
            "valor_descontar_primera_semana"     => $valor_descontar_primera_semana,
            "valor_descontar_segunda_semana"     => $valor_descontar_segunda_semana,
            "valor_descontar_tercera_semana"     => $valor_descontar_tercera_semana,
            "valor_descontar_cuarta_semana"      => $valor_descontar_cuarta_semana,
            ///////////////////////////////
            "descuento_ilimitado"                => $forma_descuento_ilimitado,
            "fecha_limite_descuento"             => $forma_fecha_limite_pago,
            //////////////////////////////
            "estado"                             => '0',
            "transaccion_contable_descuento"     => $forma_transaccion_contable_descuento,
            "transaccion_contable_empleado"      => $forma_transaccion_contable_empleado,
            "transaccion_contable_pagar_tercero" => $forma_transaccion_contable_pagar,
            "transaccion_contable_pago_tercero"  => $forma_transaccion_contable_pago,
            "codigo_usuario_registra"            => $sesion_codigo_usuario,
        );

        $insertar = SQL::insertar("control_prestamos_terceros",$datos);
        /// Error de insercón
        if (!$insertar) {
            $error   = true;
            $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
            $mensaje = mysql_error();
        }
    }
    /// Enviar datos con la respuesta del proceso al script que originó la petición
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

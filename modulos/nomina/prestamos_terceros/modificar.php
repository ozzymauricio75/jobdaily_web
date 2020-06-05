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
if (isset($url_completar)) {
    if ($url_item == "selector2") {
        echo SQL::datosAutoCompletar("seleccion_terceros", $url_q);
    }
    exit;
}

 if(isset($url_obtenerDatosContrato) && isset($url_documento_empleado)){

     $consulta_contrato_sucursal_empleado = SQL::seleccionar(array("sucursal_contrato_empleados"),array("codigo_planilla"),"documento_identidad_empleado='".$url_documento_empleado."'","","fecha_ingreso_sucursal DESC",0,1);
     $datos_contrato_sucursal_empleado    = SQL::filaEnObjeto($consulta_contrato_sucursal_empleado);

     $codigo_planilla  = $datos_contrato_sucursal_empleado->codigo_planilla;
     $periodo_pago     = SQL::obtenerValor("planillas","periodo_pago","codigo = '".$codigo_planilla."'");
     $datos_envio      = array();

     if($periodo_pago=='1'){
         $datos_envio = $forma_pago_mensual;
     }elseif($periodo_pago=='2'){
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
    $respuesta = array();
    $continuar = true;

    $consulta_sucursales          = SQL::seleccionar(array("sucursales"),array("*"),"codigo !='0'");
    $consulta_empleados           = SQL::seleccionar(array("ingreso_empleados"),array("*"),"documento_identidad_empleado!='0' AND estado='1'");
    $consulta_tipos_documentos    = SQL::seleccionar(array("tipos_documentos"),array("*"),"codigo !='0'");
    $consulta_plan_contable       = SQL::seleccionar(array("plan_contable"),array("*"));
    $consulta_conceptos_prestamos = SQL::seleccionar(array("conceptos_prestamos"),array("*"),"codigo !='0'");
    $consulta_tablas_del_sistema  = SQL::seleccionar(array("tablas"),array("*"),"nombre_tabla = 'movimientos_prestamos_generados_empleados'");

    if(SQL::filasDevueltas($consulta_tablas_del_sistema) == 0 ){
        $mensaje    = $textos["NO_EXISTE_TABLA"];
        $continuar  = false;
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

        $estado = array(
            "0" => $textos["ESTADO_0"],
            "1" => $textos["ESTADO_1"],
            "2" => $textos["ESTADO_2"]
        );

        //Determinar periodos de descutos
        $periodos_descuento = array(
            "0" => $textos["DESCUENTO_ILIMITADO"],
            "1" => $textos["DESCUENTO_FECHA"],
            "2" => $textos["DESCUENTO_TOPE"]
        );

        $error     = "";
        $titulo    = $componente->nombre;
        $documento = SQL::obtenerValor("tipos_documentos", "codigo", "codigo != '0' ORDER BY descripcion LIMIT 1");

        $llave_principal              = explode("|",$url_id);
        $codigo_empresa               = $llave_principal[0];
        $documento_identidad_empleado = $llave_principal[1];
        $obligacion                   = $llave_principal[2];
        ///////////////////////////////////////////////////
        $condicion                 = "codigo_empresa='".$codigo_empresa."' AND documento_identidad_empleado='".$documento_identidad_empleado."' AND obligacion='".$obligacion."'";
        $consulta_control_prestamo = SQL::seleccionar(array("control_prestamos_terceros"),array("*"),$condicion);
        $datos_control_prestamo    = SQL::filaEnObjeto($consulta_control_prestamo);

        if($datos_control_prestamo->estado != '2'){
            ///////////////////////////////////////////////////
            ////Obtego datos////
            $nombre_sucursal = SQL::obtenerValor("sucursales","nombre","codigo='".$datos_control_prestamo->codigo_sucursal."'");
            $nombre_empleado = SQL::obtenerValor("seleccion_empleados","SUBSTRING_INDEX(nombre_completo,'|',1)","id = '".$documento_identidad_empleado."'");;
            $tercero         = SQL::obtenerValor("seleccion_terceros","SUBSTRING_INDEX(nombre,'|',1)","id = '".$datos_control_prestamo->documento_identidad_tercero."'");
            ///Obtener lista de sucursales para selección///
            $codigo_empresa   = SQL::obtenerValor("sucursales", "codigo_empresa", "codigo = '".$sesion_sucursal."'");
            $codigo_planilla  = SQL::obtenerValor("sucursal_contrato_empleados","codigo_planilla","documento_identidad_empleado='".$documento_identidad_empleado."' ORDER BY fecha_ingreso_sucursal DESC LIMIT 0,1");
            $periodo_empleado = SQL::obtenerValor("planillas","periodo_pago","codigo='".$codigo_planilla."'");
            $limite_descuento = $datos_control_prestamo->limite_descuento;
            ////////////////////////////////////////////////
            // Transacciones contables donde su concepto es prestamos a empleados  009
            $transacciones_contable_descuento = HTML::generarDatosLista("transacciones_contables_empleado","codigo","descripcion","codigo_concepto_transaccion_contable='11' AND sentido='C'");
            $transacciones_contable_empleado  = HTML::generarDatosLista("transacciones_contables_empleado","codigo","descripcion","codigo_concepto_transaccion_contable='11' AND sentido='D'");
            $transacciones_contable_pagar     = HTML::generarDatosLista("transacciones_contables_empleado","codigo","descripcion","codigo_concepto_transaccion_contable='40' AND sentido='C'");
            $transacciones_contable_pago      = HTML::generarDatosLista("transacciones_contables_empleado","codigo","descripcion","codigo_concepto_transaccion_contable='40' AND sentido='D'");
            ////////////////////////////////////////////////////
                $valor_tope = "";
            if($limite_descuento=='0'){
               $propiedades_fecha = array("id" => "fecha_limite_pago_contenedor","class" => "oculto","disabled" =>"disabled"  );
               $propiedades_tope  = array("title" => $textos["AYUDA_VALOR_TOPE_DESCUENTO"],"onKeyPress" => "return campoEntero(event)","class" => "oculto","disabled" =>"disabled");

            }elseif($limite_descuento=='1'){
               $propiedades_fecha = array("id" => "fecha_limite_pago_contenedor");
               $propiedades_tope  = array("title" => $textos["AYUDA_VALOR_TOPE_DESCUENTO"],"onKeyPress" => "return campoEntero(event)","class" => "oculto","disabled" =>"disabled");
            }else{
               $propiedades_fecha = array("id" => "fecha_limite_pago_contenedor","class" => "oculto","disabled" =>"disabled"  );
               $propiedades_tope  = array("title" => $textos["AYUDA_VALOR_TOPE_DESCUENTO"],"onKeyPress" => "return campoEntero(event)");
               $valor_tope        = round($datos_control_prestamo->valor_tope_descuento);
            }

            $titulo_1 = $textos["VALOR_DESCUENTO"];
            $titulo_2 = $textos["VALOR_DESCUENTO_SEGUNDA_QUINCENA"];

            if($periodo_empleado=='1'){
                $forma_pago        = $forma_pago_mensual;
                $valor_descuento_1 = round($datos_control_prestamo->valor_descontar_mensual);
                $valor_descuento_2 = "";
                $valor_oculto_1    = "";
                $valor_oculto_2    = "oculto";
            }else{
                $forma_pago   = $forma_pago_quincenal;
                $periodo_pago = round($datos_control_prestamo->periodo_pago);
                if($periodo_pago=='9'){
                    $valor_descuento_1 = round($datos_control_prestamo->valor_descontar_primera_quincena);
                    $valor_descuento_2 = round($datos_control_prestamo->valor_descontar_segunda_quincena);
                    $titulo_1          = $textos["VALOR_DESCUENTO_PRIMERA_QUINCENA"];
                    $titulo_2          = $textos["VALOR_DESCUENTO_SEGUNDA_QUINCENA"];
                    $valor_oculto_1    = "";
                    $valor_oculto_2    = "";
                }elseif($periodo_pago=='2'){
                    $valor_descuento_1 = round($datos_control_prestamo->valor_descontar_primera_quincena);
                    $valor_descuento_2 = "";
                    $valor_oculto_1    = "";
                    $valor_oculto_2    = "oculto";
                }else{
                    $valor_descuento_1 = "";
                    $valor_descuento_2 = round($datos_control_prestamo->valor_descontar_segunda_quincena);
                    $valor_oculto_1    = "oculto";
                    $valor_oculto_2    = "";
                }
            }
            // Definición de pestana Basica
            $formularios["PESTANA_BASICA"] = array(
                array(
                     HTML::mostrarDato("fecha_inicio_descuento", $textos["FECHA_INICIO_DESCUENTO"],$datos_control_prestamo->fecha_inicio_descuento),
                     HTML::listaSeleccionSimple("estado", $textos["ESTADO"],$estado,$datos_control_prestamo->estado, array("title" => $textos["AYUDA_ESTADO"]))
                ),
                 array(
                     HTML::mostrarDato("codigo_sucursal",  $textos["SUCURSAL_LABORA"],$nombre_sucursal),
                     HTML::mostrarDato("documento_empleado", $textos["EMPLEADO"],$nombre_empleado),
                     HTML::mostrarDato("documento_tercero", $textos["NOMBRE_TERCERO"],$tercero)
                    .HTML::campoOculto("valor_descuento_primera_semana", $textos["VALOR_DESCUENTO_PRIMERA_QUINCENA"])
                    .HTML::campoOculto("valor_descuento_segunda_semana", $textos["VALOR_DESCUENTO_SEGUNDA_QUINCENA"])
                    .HTML::campoOculto("valor_descuento_todo", $textos["VALOR_DESCUENTO_TODO"])
                ),
                array(
                    HTML::mostrarDato("obligacion",  $textos["OBLIGACION"],$obligacion),
                ),
                array(
                    HTML::listaSeleccionSimple("*limite_descuento", $textos["LIMITE_DESCUENTO"],$periodos_descuento,$datos_control_prestamo->limite_descuento, array("title" => $textos["AYUDA_LIMITE_DESCUENTO"],"onchange" => "limiteDescuento(this)")),
                    HTML::contenedor( HTML::campoTextoCorto("*fecha_limite_pago", $textos["FECHA_HASTA_DESCONTAR"], 10, 10,date("Y-m-d"), array("title" => $textos["AYUDA_PRESTAMO"], "class" => "selectorFecha")),$propiedades_fecha),
                    HTML::campoTextoCorto("*valor_tope_descuento", $textos["VALOR_TOPE_DESCUENTO"],13,10,$valor_tope,$propiedades_tope)
                ),
                array(
                    HTML::listaSeleccionSimple("*periodo_pago", $textos["PERIODO_PAGO"],$forma_pago,$datos_control_prestamo->periodo_pago, array("title" => $textos["AYUDA_PERIODO"],"onchange" => "tipoFormadePago();")),
                    HTML::campoTextoCorto("*valor_descuento_1",$titulo_1,13,10,$valor_descuento_1, array("onKeyPress" => "return campoEntero(event)", "class" => $valor_oculto_1)),
                    HTML::campoTextoCorto("*valor_descuento_2",$titulo_2,13,10,$valor_descuento_2, array("onKeyPress" => "return campoEntero(event)", "class" => $valor_oculto_2))
                )
            );
            $formularios["TRANSACCION_CONTABLE"] = array(
                array(
                    HTML::listaSeleccionSimple("*transaccion_contable_descuento", $textos["TRANSACCION_CONTABLE_DESCUENTO"],$transacciones_contable_descuento,$datos_control_prestamo->transaccion_contable_descuento, array("title" => $textos["AYUDA_TRANSACCION_CONTABLE_DESCUENTO"]))
                ),
                 array(
                    HTML::listaSeleccionSimple("*transaccion_contable_empleado", $textos["TRANSACCION_CONTABLE_EMPLEADO"],$transacciones_contable_empleado,$datos_control_prestamo->transaccion_contable_empleado, array("title" => $textos["AYUDA_TRANSACCION_CONTABLE_EMPLEADO"]))

                ),
                array(
                    HTML::listaSeleccionSimple("*transaccion_contable_pagar", $textos["TRANSACCION_CONTABLE_PAGAR_TERCERO"],$transacciones_contable_pagar,$datos_control_prestamo->transaccion_contable_pagar_tercero, array("title" => $textos["AYUDA_TRANSACCION_CONTABLE_PAGAR_TERCERO"]))
                ),
                array(
                    HTML::listaSeleccionSimple("*transaccion_contable_pago", $textos["TRANSACCION_CONTABLE_PAGO_TERCERO"],$transacciones_contable_pago,$datos_control_prestamo->transaccion_contable_pago_tercero, array("title" => $textos["AYUDA_TRANSACCION_CONTABLE_PAGO_TERCERO"]))
                 )
            );
            /// Definicion de botones
            $botones = array(
                            HTML::boton("botonAceptar", $textos["ACEPTAR"],"modificarItem('".$url_id."');", "aceptar")
                        );

            $contenido = HTML::generarPestanas($formularios, $botones);
            $respuesta[0] = $error;
            $respuesta[1] = $titulo;
            $respuesta[2] = $contenido;
        }else{
            $respuesta[0] = $textos["PRESTAMO_CANCELADO"];
            $respuesta[1] = "";
            $respuesta[2] = "";
        }
    }
    // Enviar datos para la generacion del formulario al script que origino la peticion
    HTTP::enviarJSON($respuesta);
}

// Adicionar los datos provenientes del formulario
elseif (!empty($forma_procesar)) {

    $llave_principal              = explode("|",$forma_id);
    $codigo_empresa               = $llave_principal[0];
    $documento_identidad_empleado = $llave_principal[1];
    $obligacion                   = $llave_principal[2];
    ///////////////////////////////////////////////////
    $condicion = "codigo_empresa='".$codigo_empresa."' AND documento_identidad_empleado='".$documento_identidad_empleado."' AND obligacion='".$obligacion."'";

    if(isset($forma_valor_tope_descuento) && empty($forma_valor_tope_descuento)){
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
    }elseif(empty($forma_valor_descuento_1) && $forma_periodo_pago=="2"){
        $error   = true;
        $mensaje = $textos["ERROR_VACIO_VALOR_DESCUENTO"];
    }else{
        $error   = false;
        $mensaje = $textos["ITEM_MODIFICADO"];

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
             $valor_descontar_segunda_quincena = $forma_valor_descuento_2;
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

        $datos = array(
            "limite_descuento"                   => $forma_limite_descuento,
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
            "estado"                             => $forma_estado,
            "transaccion_contable_descuento"     => $forma_transaccion_contable_descuento,
            "transaccion_contable_empleado"      => $forma_transaccion_contable_empleado,
            "transaccion_contable_pagar_tercero" => $forma_transaccion_contable_pagar,
            "transaccion_contable_pago_tercero"  => $forma_transaccion_contable_pago,
            "codigo_usuario_modifica"            => $sesion_codigo_usuario
        );
        $insertar = SQL::modificar("control_prestamos_terceros",$datos,$condicion);
        if (!$insertar) {
            $error   = true;
            $mensaje = $textos["ERROR_MODIFICAR_ITEM"];
            $mensaje = mysql_error();
        }
    }
    // Enviar datos con la respuesta del proceso al script que origino la peticion
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);

}
?>

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
* modificarlo  bajo los t√©rminos de la Licencia P√∫blica General GNU
* publicada por la Fundaci√≥n para el Software Libre, ya sea la versi√≥n 3
* de la Licencia, o (a su elecci√≥n) cualquier versi√≥n posterior.
*
* Este programa se distribuye con la esperanza de que sea √∫til, pero
* SIN GARANT√çA ALGUNA; ni siquiera la garant√≠a impl√≠cita MERCANTIL o
* de APTITUD PARA UN PROP√ìITO DETERMINADO. Consulte los detalles de
* la Licencia P√∫blica General GNU para obtener una informaci√≥n m√°s
* detallada.
*
* Deber√≠a haber recibido una copia de la Licencia P√∫blica General GNU
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
**/

require("clases/clases.php");


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

// Devolver datos para recargar informacion requerida
if (isset($url_recargarDatosDocumento) && !empty($url_sucursal) ) {
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
if (isset($url_completar)) {//Validado
    if ($url_item == "selector2") {
        echo SQL::datosAutoCompletar("seleccion_plan_contable", $url_q);
    }
    exit;
}
/// Generar el formulario para la captura de datos
if (!empty($url_generar)){
    /*** Verificar que se haya enviado el ID del elemento a consultar ***/
    $respuesta  = array();
    if(empty($url_id)){
        $respuesta[0] = $textos["ERROR_PAGAR_VACIO"];
        $respuesta[1] = "";
        $respuesta[2] = "";
    }else{
        $llave_primaria               = explode("|",$url_id);
        $codigo_sucursal              = $llave_primaria[0];
        $documento_identidad_empleado = $llave_primaria[1];
        $fecha_inicio_tiempo          = $llave_primaria[2];
        $estado                       = $llave_primaria[3];
        if($estado=="3"){
            $consulta = SQL::seleccionar(array("sucursales"),array("*"),"codigo > 0","","nombre");
            if ($sesion_usuario == $datosGlobales["usuarioMaestro"]){
                $sucursales[""]= "";
                while ($datos = SQL::filaEnObjeto($consulta)){
                    $sucursales[$datos->codigo] = $datos->nombre;
                }
            }else{
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
                    $sucursales[""]= "";
                    while ($datos = SQL::filaEnObjeto($consulta)) {
                        $sucursales[$datos->codigo] = $datos->nombre;
                    }
                }
            }
            $mensaje    = $textos["MENSAJE_FALTA_DATOS"];
            $continuar  = true;
            $anio_actual = date("Y");
            $consulta_empleados          = SQL::seleccionar(array("ingreso_empleados"),array("*"),"documento_identidad_empleado!='0' AND estado='1'");
            $consulta_domingos_festivos  = SQL::seleccionar(array("domingos_festivos"),array("*"),"anio='$anio_actual'");
            $consulta_tablas_del_sistema = SQL::seleccionar(array("tablas"),array("*"),"nombre_tabla = 'movimiento_liquidacion_vacaciones'");

            if(SQL::filasDevueltas($consulta_tablas_del_sistema)== 0 ){
                $mensaje  .= $textos["NO_EXISTE_TABLA"];
                $continuar = false;
            }if(SQL::filasDevueltas($consulta_empleados) == 0){
                $mensaje   .= $textos["NO_EXISTEN_EMPLEADOS"];
                $continuar  = false;
            }
            if(count($sucursales) == 0){
                $mensaje   .= $textos["NO_EXISTEN_SUCURSALES"];
                $continuar  = false;
            }
            if(SQL::filasDevueltas($consulta_domingos_festivos) == 0){
                $mensaje   .= $textos["NO_EXISTEN_DOMINGOS_FESTIVOS"];
                $continuar  = false;
            }

            $respuesta[0] = $mensaje;
            $respuesta[1] = "";
            $respuesta[2] = "";

            if($continuar){

                $error  = "";
                $titulo = $componente->nombre;
                $id_modulo = SQL::obtenerValor("componentes","id_modulo","id = '$componente->id'");

                $forma_liquidacion = array(
                    "1" => $textos["AFECTA_PLANILLA"],
                    "2" => $textos["LIQUIDACION_TOTAL"]
                );

                $condicion = "codigo_sucursal='$codigo_sucursal' AND documento_identidad_empleado='$documento_identidad_empleado' AND fecha_inicio_tiempo='$fecha_inicio_tiempo'";
                $consulta_liquidacion = SQL::seleccionar(array("movimiento_liquidacion_vacaciones"),array("*"),$condicion);
                $datos_liquidacion    = SQL::filaEnObjeto($consulta_liquidacion);

                $nombre_sucursal = SQL::obtenerValor("sucursales","nombre","codigo='$datos_liquidacion->codigo_sucursal'");
                $empleado        = SQL::obtenerValor("seleccion_terceros","SUBSTRING_INDEX(nombre,'|',1)","id = '$datos_liquidacion->documento_identidad_empleado'");

                $cargar_datos = calculoLiquidacionVacaciones($fecha_inicio_tiempo,$documento_identidad_empleado,$codigo_sucursal,$datos_liquidacion->dias_tomados,$datos_liquidacion->forma_liquidacion,$textos,true);
                $datos_total_liquidacion = explode("|",end($cargar_datos));
                $valor_total_liquidacion = $datos_total_liquidacion[1];
                $cargar_datos = implode("¨", $cargar_datos);

                ////////////////////////////////////////

                /// DefiniciÛn de pestaÒa Basica
                $formularios["PESTANA_BASICA"] = array(
                    array(
                        HTML::mostrarDato("forma_liquidacion",$textos["FORMA_LIQUIDACION"],$forma_liquidacion[$datos_liquidacion->forma_liquidacion])
                    ),
                    array(
                        HTML::mostrarDato("sucursal", $textos["SUCURSAL_LABORA"],$nombre_sucursal),
                        HTML::mostrarDato("nombre_empleado",$textos["EMPLEADO"],$empleado),
                    ),
                    array(
                        HTML::mostrarDato("fecha_inicio", $textos["FECHA_INICIAL"],$datos_liquidacion->fecha_inicio_tiempo),
                        HTML::mostrarDato("dia_a_tomar",$textos["CANTIDAD_DIAS"],$datos_liquidacion->dias_tomados),
                        HTML::mostrarDato("fecha_final", $textos["FECHA_FINAL_VACACIONES"],$datos_liquidacion->fecha_final_tiempo),
                        HTML::mostrarDato("dias_dusfrutar", $textos["DIAS_DISFRUTAR"],$datos_liquidacion->dias_disfrutado),
                    ),
                    array(
                        HTML::listaSeleccionSimple("*codigo_sucursal", $textos["SUCURSAL_PAGA"],$sucursales,"", array("title" => $textos["AYUDA_SUCURSAL_PAGA"],"onchange" => "recargarDatosDocumento();")),
                        HTML::listaSeleccionSimple("*tipo_documento", $textos["TIPO_DOCUMENTO"], HTML::generarDatosLista("tipos_documentos", "codigo", "descripcion"),"", array("title" => $textos["AYUDA_TIPO_DOCUMENTO"],"onchange" => "recargarDatosDocumento()")),
                        HTML::listaSeleccionSimple("cuenta_bancaria", $textos["CUENTA_BANCARIA"],"", "", array("onChange" => "consecutivoCheque();","class" => "oculto")),
                        HTML::campoTextoCorto("*consecutivo_cheque", $textos["CONSECUTIVO_CHEQUE"], 10, 10,"", array("title" => $textos["AYUDA_CONSECUTIVO_CHEQUE"],"class" => "oculto","readonly" => "readonly")),
                    ),
                    array(
                        HTML::campoTextoCorto("*consecutivo_documento", $textos["CONSECUTIVO_DOCUMENTO"], 10, 10,"", array("title" => $textos["AYUDA_CONSECUTIVO_DOCUMENTO"])),
                        HTML::campoTextoCorto("*selector2", $textos["CUENTA"], 40, 255, "", array("title" => $textos["AYUDA_CUENTA"], "class" => "autocompletable"))
                       .HTML::campoOculto("codigo_contable", "")
                       .HTML::campoOculto("valor_total_liquidacion",$valor_total_liquidacion)
                    ),
                    array(
                    HTML::generarTabla(
                            array("id","CONCEPTO","VALOR_MOVIENTO"),
                            "",
                            array("C","C"),
                            "listaItemsVacaciones",
                            false
                        )
                    ),
                    array(
                        //////////Permitir que se ejecute una funcion al iniciar el formulario//////////
                        HTML::contenedor("<script language='javascript'>generarTablaLiquidacion('$cargar_datos');</script>")
                       .HTML::campoOculto("error_no_cuentas_bancarias", $textos["CUENTAS_BANCARIAS_VACIAS"])
                       
                    )
                );
                /*** DefiniciÛn de botones ***/
                $botones = array(
                                HTML::boton("botonAceptar", $textos["ACEPTAR"],"modificarItem('$url_id');", "aceptar")
                            );

                $contenido = HTML::generarPestanas($formularios, $botones);
                /// Enviar datos para la generaciÛn del formulario al script que originÛ la peticiÛn
                $respuesta[0] = $error;
                $respuesta[1] = $titulo;
                $respuesta[2] = $contenido;
            }
        }else{
            $respuesta[0] = $textos["ERROR_ESTADO_AUTORIZADO"];
            $respuesta[1] = "";
            $respuesta[2] = "";
        }
    }
    HTTP::enviarJSON($respuesta);

//// Validar los datos provenientes del formulario
}elseif(!empty($forma_procesar)){
    $error   = false;
    $mensaje = $textos["ITEM_MODIFICADO"];

    $continuar = true;

    ///////Validar que el tipo de documento genera cheque tenga cuenta//////
    $genera_cheques = SQL::obtenerValor("tipos_documentos", "genera_cheque", "codigo = '$forma_tipo_documento'");
    if($genera_cheques=='1'){
         $consulta = SQL::seleccionar(array("buscador_cuentas_bancarias"), array("*"),"id_sucursal = '$forma_codigo_sucursal' AND id_documento = '$forma_tipo_documento'");
         if(SQL::filasDevueltas($consulta)){
            $continuar = false;
         }
    }else{
         $continuar = false;
    }

    $existe_concecutivo = false;
    //Guardar datos del documento que genera el movimiento contable
    $id_tabla   = SQL::obtenerValor("tablas", "id", "nombre_tabla = 'movimiento_liquidacion_vacaciones'");
    $manejo     = SQL::obtenerValor("tipos_documentos", "manejo_automatico", "codigo = '$forma_tipo_documento'");
    if ($manejo == 2) {
        $consecutivo_documento = SQL::obtenerValor("consecutivo_documentos", "MAX(consecutivo)", "codigo_sucursal = '$forma_codigo_sucursal' AND codigo_tipo_documento = '$forma_tipo_documento'");
        if(!$consecutivo_documento){
            $consecutivo_documento = 1;
        }else{
            $consecutivo_documento++;
            $existe_concecutivo = false;
        }
    } else {
        $consecutivo_documento = $forma_consecutivo_documento;
        $existe_concecutivo    = SQL::existeItem("consecutivo_documentos","consecutivo", $consecutivo_documento, "codigo_sucursal = '".$forma_codigo_sucursal."' AND codigo_tipo_documento = '".$forma_tipo_documento."'");
    }

    if(empty($forma_codigo_sucursal)){
        $error   = true;
        $mensaje = $textos["ERROR_SUCURSAL_GENERA"];
    }elseif($existe_concecutivo){
        $error   = true;
        $mensaje = $textos["CONSECUTIVO_DOCUMENTO_EXISTE"];
    }elseif($continuar){
        $error   = true;
        $mensaje = $textos["CUENTAS_BANCARIAS_VACIAS"];
    }elseif(empty($forma_codigo_contable)){
        $error   = true;
        $mensaje = $textos["ERROR_CUENTA_VACIA"];
    }elseif(empty($forma_consecutivo_documento)){
        $error   = true;
        $mensaje = $textos["ERROR_CONSECUTIVO_DOCUMENTO"];
    }elseif(empty($forma_tipo_documento) || $forma_tipo_documento==0){
        $error   = true;
        $mensaje = $textos["ERROR_TIPO_DOCUMENTO"];   
    }else{
        $fecha_registro             = date("Y-m-d H:i:s");
        $fecha_registro_consecutivo = date("Y-m-d");

        $llave_primaria               = explode("|",$forma_id);
        $codigo_sucursal              = $llave_primaria[0];
        $documento_identidad_empleado = $llave_primaria[1];
        $fecha_inicio_tiempo          = $llave_primaria[2];

        //////////////GenerÛ la llave de la tabla////////////////
        $tipo_comprobante = SQL::obtenerValor("tipos_documentos", "codigo_comprobante", "codigo = '$forma_tipo_documento'");
        $codigo_empresa   = SQL::obtenerValor("sucursales","codigo_empresa","codigo='$forma_codigo_sucursal'");

        $llave_tabla = $codigo_empresa.'|'.$documento_identidad_empleado.'|'.$tipo_comprobante.'|0|'.$forma_tipo_documento.'|'.str_pad($consecutivo_documento,9,"0",STR_PAD_LEFT).'|'.$fecha_registro;

        if(SQL::existeItem("consecutivo_documentos","llave_tabla",$llave_tabla)){
            $error   = true;
            $mensaje = $textos["EXISTE_CONSECUTIVO_DOCUMENTO"];
        }else{
            
            $datos = array(
                "codigo_sucursal"             => $forma_codigo_sucursal,
                "codigo_tipo_documento"       => $forma_tipo_documento,
                "fecha_registro"              => $fecha_registro_consecutivo,
                "documento_identidad_tercero" => $documento_identidad_empleado,
                "consecutivo"                 => $consecutivo_documento,
                "id_tabla"                    => $id_tabla,
                "llave_tabla"                 => $llave_tabla,
                "codigo_sucursal_archivo"     => '0',
                "consecutivo_archivo"         => '0'
            );
            
            $insertar = SQL::insertar("consecutivo_documentos", $datos);
            if (!$insertar) {
                $error   = true;
                $mensaje = $textos["EXISTE_CONSECUTIVO_DOCUMENTO"];
            }else{
                
                $condicion = "codigo_sucursal='$codigo_sucursal' AND documento_identidad_empleado='$documento_identidad_empleado' AND fecha_inicio_tiempo='$fecha_inicio_tiempo'";

                $consulta_liquidacion = SQL::seleccionar(array("movimiento_liquidacion_vacaciones"),array("*"),$condicion);
                $datos_liquidacion    = SQL::filaEnObjeto($consulta_liquidacion);

                $flujo_efectivo = SQL::obtenerValor("plan_contable","flujo_efectivo","codigo_contable='$forma_codigo_contable'");

                $datos = array(
                    "codigo_sucursal"                                   => $codigo_sucursal,
                    "documento_identidad_empleado"                      => $documento_identidad_empleado,
                    "fecha_inicio_tiempo"                               => $fecha_inicio_tiempo,
                    /////LLAVE_EN_TABLA_SUCURSAL_CONTRATO/////
                    "codigo_empresa"                                    => $datos_liquidacion->codigo_empresa,
                    "fecha_ingreso_sucursal"                            => $datos_liquidacion->fecha_ingreso_sucursal,
                    "fecha_ingreso"                                     => $datos_liquidacion->fecha_ingreso,
                    ///////////////////////////////
                    "codigo_empresa_auxiliar"                           => $datos_liquidacion->codigo_empresa_auxiliar,
                    "codigo_anexo_contable"                             => $datos_liquidacion->codigo_anexo_contable,
                    "codigo_auxiliar_contable"                          => $datos_liquidacion->codigo_auxiliar_contable,
                    //////DATOS CUENTA AFECTA/////////
                    "flujo_efectivo"                                    => $flujo_efectivo,
                    "codigo_contable"                                   => $forma_codigo_contable,
                    "sentido"                                           => "C",
                    /////////CUENTA BANCARIA//////////
                    "codigo_sucursal_pertence"                          => "0",
                    "tipo_documento_cuenta_bancaria"                    => "0",
                    "codigo_sucursal_banco"                             => "0",
                    "codigo_iso"                                        => "",
                    "codigo_dane_departamento"                          => "",
                    "codigo_dane_municipio"                             => "",
                    "codigo_banco"                                      => "0",
                    "consecutivo_cheque"                                => "0",
                    "numero"                                            => "",
                    ////////////////////////////////////
                    "codigo_tipo_documento"                             => $forma_tipo_documento,
                    ///llave_consecutivo docuemento/////
                    "consecutivo_documento"                             => $consecutivo_documento,
                    "codigo_tipo_documento_consecutivo_documento"       => $forma_tipo_documento,
                    "codigo_sucursal_consecutivo_documento"             => $forma_codigo_sucursal,
                    "fecha_registro_consecutivo_documento"              => $fecha_registro_consecutivo,
                    "documento_identidad_tercero_consecutivo_documento" => $documento_identidad_empleado,
                    /////////////////////////////////
                    "valor_movimiento"                                  => $forma_valor_total_liquidacion,
                    "codigo_usuario_registra"                           => $sesion_usuario,
                );

                $insertar = SQL::insertar("pago_liquidaciones_vacaciones", $datos);
                if (!$insertar) {
                    $llave_consecutivo_documento  = " codigo_sucursal='$forma_codigo_sucursal' AND codigo_tipo_documento='$forma_tipo_documento'";
                    $llave_consecutivo_documento .= " AND documento_identidad_tercero='$documento_identidad_empleado' AND fecha_registro='$fecha_registro_consecutivo' AND  consecutivo='$consecutivo_documento'";
                    $eliminar = SQL::eliminar("consecutivo_documentos",$llave_consecutivo_documento);
                    $error   = true;
                    $mensaje = $textos["ERROR_PAGO_LIQUIDACION"];
                }else{
                    if($genera_cheques=="1" && $insertar){

                        $llave_tabla        = " codigo_sucursal=\'$forma_codigo_sucursal\' AND documento_identidad_empleado=\'$documento_identidad_empleado\'";
                        $llave_tabla       .= " AND fecha_inicio_tiempo=\'$fecha_inicio_tiempo\'";

                        $llave_cuenta_bancaria          = explode("|",$forma_cuenta_bancaria);
                        $codigo_sucursal_pertence       = $llave_cuenta_bancaria[0];
                        $tipo_documento_cuenta_bancaria = $llave_cuenta_bancaria[1];
                        $codigo_sucursal_banco          = $llave_cuenta_bancaria[2];
                        $codigo_iso                     = $llave_cuenta_bancaria[3];
                        $codigo_dane_departamento       = $llave_cuenta_bancaria[4];
                        $codigo_dane_municipio          = $llave_cuenta_bancaria[5];
                        $codigo_banco                   = $llave_cuenta_bancaria[6];
                        $numero                         = $llave_cuenta_bancaria[7];

                        $consecutivo_cheque = (int) SQL::obtenerValor("consecutivo_cheques", "MAX(consecutivo)", "codigo_sucursal='$codigo_sucursal_pertence' AND codigo_tipo_documento = '$tipo_documento_cuenta_bancaria' AND codigo_banco = '$codigo_banco' AND numero = '$numero'");
                        //echo var_dump("codigo_sucursal='$codigo_sucursal_pertence' AND codigo_tipo_documento = '$tipo_documento_cuenta_bancaria' AND codigo_banco = '$codigo_banco' AND numero = '$numero'");
                        if(!$consecutivo_cheque){
                            $consecutivo_cheque = 1;
                        }else{
                            $consecutivo_cheque++;
                        }

                           $consecutivo_cheque = (int) SQL::obtenerValor("consecutivo_cheques", "MAX(consecutivo)", "codigo_sucursal='$codigo_sucursal_pertence' AND codigo_tipo_documento = '$tipo_documento_cuenta_bancaria' AND codigo_banco = '$codigo_banco' AND numero = '$numero'");
                        //echo var_dump("codigo_sucursal='$codigo_sucursal_pertence' AND codigo_tipo_documento = '$tipo_documento_cuenta_bancaria' AND codigo_banco = '$codigo_banco' AND numero = '$numero'");
                        if(!$consecutivo_cheque){
                            $consecutivo_cheque = 1;
                        }else{
                            $consecutivo_cheque++;
                        }

                        $datos = array (
                            "codigo_sucursal"                 => $forma_codigo_sucursal,
                            "codigo_tipo_documento"           => $tipo_documento_cuenta_bancaria,
                            "codigo_banco"                    => $codigo_banco,
                            "numero"                          => $numero,
                            "consecutivo"                     => $consecutivo_cheque,
                            /////////////LLAVE DE CUENTAS BANCARIAS//////////
                            "codigo_sucursal_cuenta"          => $forma_codigo_sucursal,
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

                        $insertar = SQL::insertar("consecutivo_cheques", $datos);
                        
                        if (!$insertar) {
                            ///////////Elimino la forma de pago generada///////////////////
                            $condicion = "codigo_sucursal='$codigo_sucursal' AND documento_identidad_empleado='$documento_identidad_empleado' AND fecha_inicio_tiempo='$fecha_inicio_tiempo'";
                            $eliminar = SQL::eliminar("pago_liquidaciones_vacaciones",$condicion);
                            //////////Elimino el consecutivo del documento/////////////////
                            $llave_consecutivo_documento  = " codigo_sucursal='$forma_codigo_sucursal' AND codigo_tipo_documento='$forma_tipo_documento'";
                            $llave_consecutivo_documento .= " AND documento_identidad_tercero='$documento_identidad_empleado' AND fecha_registro='$fecha_registro_consecutivo' AND  consecutivo='$consecutivo_documento'";
                            $eliminar = SQL::eliminar("consecutivo_documentos",$llave_consecutivo_documento);
                            //////////////////////////////////////////////////////////////
                            $error   = true;
                            $mensaje = $textos["ERROR_ADICIONAR_CONSECUTIVO_CHEQUE"];
                        }else{
                            $datos = array(
                                "codigo_sucursal_pertence"       => $forma_codigo_sucursal,
                                "tipo_documento_cuenta_bancaria" => $tipo_documento_cuenta_bancaria,
                                "codigo_sucursal_banco"          => $codigo_sucursal_banco,
                                "codigo_iso"                     => $codigo_iso,
                                "codigo_dane_departamento"       => $codigo_dane_departamento,
                                "codigo_dane_municipio"          => $codigo_dane_municipio,
                                "codigo_banco"                   => $codigo_banco,
                                "consecutivo_cheque"             => $consecutivo_cheque,
                                "numero"                         => $numero
                            );

                            $condicion = "codigo_sucursal='$codigo_sucursal' AND documento_identidad_empleado='$documento_identidad_empleado' AND fecha_inicio_tiempo='$fecha_inicio_tiempo'";
                            $modificar = SQL::modificar("pago_liquidaciones_vacaciones", $datos,$condicion);
                            $datos = array("estado_liquidacion" => "4");
                            $modificar = SQL::modificar("movimiento_liquidacion_vacaciones",$datos,$condicion);
                            $modificar = SQL::modificar("pago_liquidaciones_vacaciones",$datos,$condicion);
                        }
                    }else{
                        $datos = array("estado_liquidacion" => "4");
                        $modificar = SQL::modificar("movimiento_liquidacion_vacaciones",$datos,$condicion);
                        $modificar = SQL::modificar("pago_liquidaciones_vacaciones",$datos,$condicion);
                    }
                }
            }
        }
    }
    //// Enviar datos con la respuesta del proceso al script que originÛ la peticiÛn
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

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
        $respuesta[0] = $textos["ERROR_AUTORIZAR_VACIO"];
        $respuesta[1] = "";
        $respuesta[2] = "";
    }else{
        $llave_primaria               = explode("|",$url_id);
        $codigo_sucursal              = $llave_primaria[0];
        $documento_identidad_empleado = $llave_primaria[1];
        $fecha_inicio_tiempo          = $llave_primaria[2];
        $estado                       = $llave_primaria[3];
        if($estado=="1"){
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

            if(SQL::filasDevueltas($consulta_empleados) == 0){
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
                $cargar_datos = implode("¨", $cargar_datos);
                /// DefiniciÛn de pestaÒa Basica
                $formularios["PESTANA_BASICA"] = array(
                    array(
                        HTML::mostrarDato("forma_liquidacion",$textos["FORMA_LIQUIDACION"],$forma_liquidacion[$datos_liquidacion->forma_liquidacion]),
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
            $respuesta[0] = $textos["ERROR_ESTADO"];
            $respuesta[1] = "";
            $respuesta[2] = "";
        }
    }
    HTTP::enviarJSON($respuesta);

//// Validar los datos provenientes del formulario
}elseif(!empty($forma_procesar)){
    //// Enviar datos con la respuesta del proceso al script que originÛ la peticiÛn
    $error   = false;
    $mensaje = $textos["ITEM_MODIFICADO"];
    $llave_primaria               = explode("|",$forma_id);
    $codigo_sucursal              = $llave_primaria[0];
    $documento_identidad_empleado = $llave_primaria[1];
    $fecha_inicio_tiempo          = $llave_primaria[2];
    
    $condicion = "codigo_sucursal='$codigo_sucursal' AND documento_identidad_empleado='$documento_identidad_empleado' AND fecha_inicio_tiempo='$fecha_inicio_tiempo'";
    $datos = array("estado_liquidacion" => "3");
    $modificar = SQL::modificar("movimiento_liquidacion_vacaciones",$datos,$condicion);

    if(!$modificar){
         $error   = true;
         $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
    }else{
         $modificar = SQL::modificar("liquidaciones_movimientos_conceptos_vacaciones",$datos,$condicion);
         if(!$modificar){
             $datos = array("estado_liquidacion" => "1");
             $modificar = SQL::modificar("movimiento_liquidacion_vacaciones",$datos,$condicion);
             $error   = true;
             $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
         }
    }

    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

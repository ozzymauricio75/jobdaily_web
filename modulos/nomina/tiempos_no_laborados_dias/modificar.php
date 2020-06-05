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

if(isset($url_verificar_periodo_contable)){
     $consulta = SQL::seleccionar(array("periodos_contables"),array("*"),"codigo_sucursal='$url_codigo_sucursal' AND ('$url_fecha_inicio' BETWEEN fecha_inicio AND fecha_fin) AND id_modulo='$url_id_modulo' AND estado='1'");
     if(SQL::filasDevueltas($consulta)){
        $estado = "1";
        $mensaje = "";
     }else{
        $estado= "0";
        $mensaje = $textos["MENSAJE_PERIODO_CONTABLE"];
     }

    $respuesta = array();
    $respuesta[] = $estado;
    $respuesta[] = $mensaje;

    HTTP::enviarJSON($respuesta);
    exit;
}

if(isset($url_eliminar_base_datos)){

    $mensaje = "";

    $condicion = "documento_identidad_empleado = '$url_documento_identidad' AND fecha_inicio_tiempo = '$url_fecha_inicial' AND codigo_sucursal='$url_codigo_sucursal'";
    if($url_opcion=="1"){
        $consulta    = SQL::existeItem("movimiento_tiempos_no_laborados_dias","fecha_inicio_tiempo",$url_fecha_inicial,$condicion);
    }else{
        $consulta  = SQL::eliminar("movimiento_tiempos_no_laborados_dias",$condicion);
        if($consulta){
            $mensaje = $textos["MENSAJE_EXITO_ELIMINO"];
        }else{
            $mensaje = $textos["MENSAJE_ERROR_ELIMINO"];
        }
    }
    $respuesta   = array();
    $respuesta[] = $consulta;
    $respuesta[] = $mensaje;
    HTTP::enviarJSON($respuesta);
    exit;
}

if(isset($url_verificar)){
    $condicion_extra = "id_sucursal='".$url_codigo_sucursal."'";
    echo SQL::datosAutoCompletar("seleccion_empleados", $url_q, $condicion_extra);
    exit;
}

if (isset($url_generarTiempos)){

    $fecha_enviar = array();
    $fecha_enviar[0] = true;
    $fecha_enviar[1] = "0";
    $fecha_enviar[2] = "";
    $mensaje = "";

    for($i=0;$i<$url_fecha_dias;$i++)
    {

        $fecha_inicial   = getdate(strtotime($url_fecha_inicio));
        $fecha_resultado = date("Y-m-d", mktime(($fecha_inicial["hours"]),($fecha_inicial["minutes"]),($fecha_inicial["seconds"]),($fecha_inicial["mon"]),($fecha_inicial["mday"]+$i),($fecha_inicial["year"])));

        $consulta = SQL::seleccionar(array("periodos_contables"),array("*"),"codigo_sucursal='$url_codigo_sucursal' AND ('$fecha_resultado' BETWEEN fecha_inicio AND fecha_fin) AND id_modulo='$url_id_modulo' AND estado='1'");
        if(SQL::filasDevueltas($consulta)){
            $fecha_enviar[] = $fecha_resultado;
        }else{
            $fecha_enviar[1]  = "1";
            $fecha_enviar[2]  = $textos["MENSAJE_CERRADO_PERIODO"];
            $mensaje         .= ' - '.$fecha_resultado."\n";
        }

        $fecha_enviar[2] .=$mensaje;
        $consulta_existe_fecha_reportada = SQL::seleccionar(array("movimiento_tiempos_no_laborados_dias"),array("*"),"fecha_tiempo='$fecha_resultado' AND documento_identidad_empleado='$url_documento_identidad' AND codigo_sucursal='$url_codigo_sucursal'");
        $consulta_existe_incapacidades   = SQL::seleccionar(array("reporte_incapacidades"),array("*"),"fecha_incapacidad='$fecha_resultado' AND documento_identidad_empleado='$url_documento_identidad' AND codigo_sucursal='$url_codigo_sucursal'");

        if(SQL::filasDevueltas($consulta_existe_fecha_reportada) != 0 || SQL::filasDevueltas($consulta_existe_incapacidades) != 0){
            $fecha_enviar[0] = false;
            break;
        }
    }

    HTTP::enviarJSON($fecha_enviar);
    exit;
}

if(isset($url_recargar_auxiliares)){
    $empresa  = SQL::obtenerValor("sucursales","codigo_empresa","codigo='".$sesion_sucursal."'");
    $lista=HTML::generarDatosLista("auxiliares_contables", "codigo", "descripcion", "codigo_anexo_contable = '".$url_origen."'AND codigo_empresa='".$empresa."'");
    HTTP::enviarJSON($lista);
    exit;
}

if(isset($url_verificaAnexos)){
    $anexo    = SQL::obtenerValor("sucursal_contrato_empleados","codigo_anexo_contable","documento_identidad_empleado='".$url_empleado."' AND codigo_sucursal='".$url_sucursal."' ORDER BY fecha_ingreso_sucursal DESC LIMIT 0,1");
    $consulta = SQL::seleccionar(array("plan_contable PC,job_transacciones_contables_empleado TCE,job_transacciones_tiempo TT"),array("PC.codigo_anexo_contable"),"TT.codigo_transaccion_contable=TCE.codigo AND TCE.codigo_contable=PC.codigo_contable AND TT.codigo=$url_transaccion");
    $datos    = SQL::filaEnObjeto($consulta);
    $anexoTr  = $datos->codigo_anexo_contable;

    if($anexoTr=="0" || $anexoTr==""){
        $respuesta = 1;//No tiene anexo contable
    }elseif($anexoTr==$anexo){
        $respuesta = 2;//Es el mismo del empleado entonces se lleva el auxiliar de este
    }elseif($anexoTr!=$anexo){
        $respuesta = 3;//No es el mismo del empleado entonces se pide por pantalla
    }

    HTTP::enviarJSON($respuesta);
    exit;
}
/// Generar el formulario para la captura de datos
if (!empty($url_generar)) {
    //echo var_dump($url_id);
    $respuesta = array();
    if (empty($url_id)) {
        //echo var_dump("SII");
        $respuesta[0] = $textos["ERROR_MODIFICAR_VACIO"];
        $respuesta[1] = "";
        $respuesta[2] = "";
    }else{

        $datos               = explode('|',$url_id);
        $documento_identidad = $datos[0];
        $fecha_inicial       = $datos[1];
        $codigo_sucursal     = $datos[2];

        $condicion = "documento_identidad_empleado = '$documento_identidad' AND fecha_inicio_tiempo = '$fecha_inicial' AND codigo_sucursal='$codigo_sucursal'";

        $vistaConsulta       = "movimiento_tiempos_no_laborados_dias";
        $columnas            = SQL::obtenerColumnas($vistaConsulta);
        $consulta_reporte_tiempo_no_laborado  = SQL::seleccionar(array($vistaConsulta), $columnas,$condicion);
        $items = array();
        $contabilizado = false;
        if (SQL::filasDevueltas($consulta_reporte_tiempo_no_laborado)) {
            $dias = 0;
            //////Verificar que no se haya generado ningun evento////////
            while ($datos_item = SQL::filaEnObjeto($consulta_reporte_tiempo_no_laborado)){
                $contabilizar = $datos_item->contabilizado;
                if($contabilizar=='2' || $contabilizar=='1'){
                    $respuesta[0] = $textos["MENSAJE_LEIDO_SALARIO"];
                    $respuesta[1] = "";
                    $respuesta[2] = "";
                    $contabilizado = true ;
                    break;
                }else{
                    $ocultos   = HTML::campoOculto("fechas_tiempo[]",$datos_item->fecha_tiempo, array("class" => "fechas_tiempo"));
                    $ocultos  .= HTML::campoOculto("codigo_transaccion[]",$datos_item->codigo_transaccion_tiempo, array("class" => "codigo_transaccion"));
                    $ocultos  .= HTML::campoOculto("motivo_no_laborado[]",$datos_item->codigo_motivo_no_laboral, array("class" => "motivo_no_laborado"));
                    $ocultos  .= HTML::campoOculto("codigo_anexo_contable_oculto[]",$datos_item->codigo_anexo_contable, array("class" => "codigo_anexo_contable_oculto"));
                    $ocultos  .= HTML::campoOculto("codigo_auxiliar_contable_oculto[]",$datos_item->codigo_auxiliar_contable, array("class" => "codigo_auxiliar_contable_oculto"));
                    $ocultos  .= HTML::campoOculto("fecha_inicio[]",$datos_item->fecha_inicio_tiempo, array("class" => "fecha_inicio"));

                    $nombre_transaccion       = SQL::obtenerValor("transacciones_tiempo","nombre","codigo='$datos_item->codigo_transaccion_tiempo'");
                    $nombre_motivo_no_laboral = SQL::obtenerValor("motivos_tiempo_no_laborado","descripcion","codigo='$datos_item->codigo_motivo_no_laboral'");

                    $items[] = array(
                        $dias,
                        $ocultos,
                        $datos_item->fecha_tiempo,
                        $nombre_transaccion,
                        $nombre_motivo_no_laboral
                    );
                    $dias++;
                }
            }
        }

        if(!$contabilizado){

            ///Obtener lista de sucursales para selecciÛn dependiendo a los permisos///
            $consulta = SQL::seleccionar(array("sucursales"),array("*"),"codigo > 0","","nombre");
            if ($sesion_usuario == $datosGlobales["usuarioMaestro"]){
                 while ($datos = SQL::filaEnObjeto($consulta)){
                    $sucursales[$datos->codigo] = $datos->nombre;
                }
            } else {
                /*** Obtener lista de sucursales para selecciÛn ***/
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

            $mensaje    = $textos["MENSAJE_FALTA_DATOS"];
            $continuar  = true;
            $respuesta  = array();

            $consulta_empleados          = SQL::seleccionar(array("ingreso_empleados"),array("*"),"documento_identidad_empleado!='0' AND estado='1'");
            $consulta_transaccion_tiempo = SQL::seleccionar(array("transacciones_tiempo"), array("codigo","nombre"), "codigo='0' OR codigo_concepto_transaccion_tiempo IN (select codigo from job_conceptos_transacciones_tiempo where tipo='2')", "", "nombre");
            $consulta_motivos            = SQL::seleccionar(array("motivos_tiempo_no_laborado"),array("*"),"codigo!='0'");

            //echo var_dump(SQL::filasDevueltas($consulta_transaccion_tiempo)) ;
            if(SQL::filasDevueltas($consulta_empleados) == 0){
                $mensaje   .= $textos["NO_EXISTEN_EMPLEADOS"];
                $continuar  = false;
            }
            if(SQL::filasDevueltas($consulta_motivos) == 0){
                $mensaje   .= $textos["NO_EXISTEN_MOTIVOS"];
                $continuar  = false;
            }
            if(count($sucursales) == 0){
                $mensaje   .= $textos["NO_EXISTEN_SUCURSALES"];
                $continuar  = false;
            }
            if(SQL::filasDevueltas($consulta_transaccion_tiempo) == 1 ){
                $mensaje   .= $textos["NO_EXISTEN_TRANSACCION"];
                $continuar  = false;
            }

            $respuesta[0] = $mensaje;
            $respuesta[1] = "";
            $respuesta[2] = "";

            if($continuar){

                $error  = "";
                $titulo = $componente->nombre;
                $transacciones    = array();

                while ($fila = SQL::filaEnArreglo($consulta_transaccion_tiempo)) {
                    $transacciones[$fila[0]] = $fila[1];
                }
                $empresa  = SQL::obtenerValor("sucursales","codigo_empresa","codigo='".$sesion_sucursal."'");
                $anexos  = array();
                $consulta = SQL::seleccionar(array("auxiliares_contables,job_anexos_contables"), array("codigo_anexo_contable","job_anexos_contables.descripcion"), "codigo_empresa='".$empresa."' AND codigo_anexo_contable=job_anexos_contables.codigo", "codigo_anexo_contable","job_anexos_contables.descripcion");

                while ($fila = SQL::filaEnArreglo($consulta)) {
                    $anexos[$fila[0]] = $fila[1];
                }

                $error               = "";
                $titulo              = $componente->nombre;
                $valor_movimiento    = 0;
                $id_modulo = SQL::obtenerValor("componentes","id_modulo","id = '$componente->id'");

                $empleado    = SQL::obtenerValor("seleccion_empleados","SUBSTRING_INDEX(nombre_completo,'|',1)"," id = '$documento_identidad'");
                $sucursal    = SQL::obtenerValor("sucursales","nombre"," codigo = '$codigo_sucursal'");
                $fecha_inicial = explode(" ",$fecha_inicial);

                /*** DefiniciÛn de pestaÒa Basica ***/
                $formularios["PESTANA_BASICA"] = array(
                        array(
                             HTML::mostrarDato("codigo_sucursal", $textos["SUCURSAL_LABORA"],$sucursal)
                            .HTML::campoOculto("codigo_sucursal2",$codigo_sucursal),
                             HTML::mostrarDato("nombre_empleado", $textos["EMPLEADO"],$empleado)
                            .HTML::campoOculto("documento_aspirante",$documento_identidad)
                            .HTML::campoOculto("fecha_incial_oculto",$fecha_inicial[0])
                            .HTML::campoOculto("mensaje_fechas_repetidas",$textos["MENSAJE_FECHAS_EXISTE"])
                            .HTML::campoOculto("mensaje_campos_vacios",$textos["MENSAJE_CAMPOS_VACIOS"])
                            .HTML::campoOculto("mensaje_vacio_documento_empleado",$textos["MENSAJE_VACIO_EMPLEADO"])
                            .HTML::campoOculto("mensaje_vacio_dias",$textos["CANTIDAD_DIAS"])
                            .HTML::campoOculto("mensaje_tipo_transaccion",$textos["TIPO_TRANSACCION"])
                            .HTML::campoOculto("mensaje_confimacion",$textos["MENSAJE_CONFIRMACION"])
                            .HTML::campoOculto("id_modulo",$id_modulo)
                        ),
                        array(
                            HTML::listaSeleccionSimple("*codigo_transaccion_tiempo", $textos["TIPO_TRANSACCION"], $transacciones, 0, array("title" => $textos["AYUDA_TIPO_TRANSACCION"],"onChange" => "verificarAnexosEnTransacciones();"))
                           .HTML::campoOculto("estado_anexo",""),
                            HTML::listaSeleccionSimple("*codigo_motivo_tiempo", $textos["MOTIVO_TIEMPO_NO_LABORADO"], HTML::generarDatosLista("motivos_tiempo_no_laborado ","codigo", "descripcion"), "", array("title" => $textos["AYUDA_MOTIVO_TIEMPO_NO_LABORADO"]))
                        ),
                        array(
                            HTML::listaSeleccionSimple("*codigo_anexo_contable", $textos["ANEXO_CONTABLE"], $anexos,0, array("title" => $textos["AYUDA_ANEXO_CONTABLE"], "onChange" => "recargarListaAuxiliares('codigo_anexo_contable','codigo_auxiliar_contable')","style" => "display: none;")),
                            HTML::listaSeleccionSimple("*codigo_auxiliar_contable", $textos["AUXILIAR_CONTABLE"], HTML::generarDatosLista("auxiliares_contables", "codigo", "descripcion", "codigo_anexo_contable = '" . array_shift(array_keys($anexos)) . "'AND codigo_empresa='".$empresa."'"), 0, array("title" => $textos["AYUDA_AUXILIAR_CONTABLE"],"style" => "display: none;"))
                        ),
                        array(

                            HTML::campoTextoCorto("*fecha_inicial", $textos["FECHA_INICIAL"], 10, 10,$fecha_inicial[0], array("title" => $textos["AYUDA_FECHA_INICIAL"], "class" => "selectorFecha")),
                            HTML::campoTextoCorto("*dias_no_laborados", $textos["CANTIDAD_DIAS"], 2, 3,$dias, array("title" => $textos["AYUDA_CANTIDAD_DIAS"], "onKeyPress" => "return campoEntero(event)")),
                            HTML::campoOculto("mensaje_fechas", $textos["ERROR_FECHA_MENOR"])

                           .HTML::contenedor(HTML::boton("botonRemoverIncapacidad", "", "removerItems(this);", "eliminar"), array("id" => "botonRemoverIncapacidad", "style" => "display: none;"))
                        ),
                        array(
                            HTML::boton("botonAgregar", $textos["AGREGAR"], "adicionarTiempos();", "adicionar"),
                            HTML::boton("botonEliminar",$textos["ELIMINAR_TODOS"],"removerItemsModificar(this);", "eliminar")
                        ),
                        array(
                            HTML::generarTabla(
                                array("id","","FECHA_TIEMPO","TIPO_TRANSACCION","MOTIVO_TIEMPO_NO_LABORADO"),
                                $items,
                                array("C","C","I","C"),
                                "listaItemsTiempo",
                                false
                            )
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
        }
    }

    HTTP::enviarJSON($respuesta);

//// Validar los datos provenientes del formulario
}

//// Adicionar los datos provenientes del formulario
elseif (!empty($forma_procesar)) {
    //// Asumir por defecto que no hubo error
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];

    if (!isset($forma_fechas_tiempo)){
        $error = true;
        $mensaje = $textos["ERROR_TABLA_VACIA"];
    } else {
        $fecha_registro = date("Y-m-d H:i:s");

         $datos               = explode('|',$forma_id);
         $documento_identidad = $datos[0];
         $fecha_inicial       = $datos[1];
         $codigo_sucursal     = $datos[2];
         $condicion = "documento_identidad_empleado = '$documento_identidad' AND fecha_inicio_tiempo = '$fecha_inicial' AND codigo_sucursal='$codigo_sucursal'";

         $eliminar = SQL::eliminar("movimiento_tiempos_no_laborados_dias",$condicion);

         for($id = 0;!empty($forma_fechas_tiempo[$id]); $id++){
            ////////////////////////////////////////////////////////////////////////////////////////////////
            $fecha_tiempo = $forma_fechas_tiempo[$id];
            $fecha_pago     = explode("-",$fecha_tiempo);
            $ano_generacion = $fecha_pago[0];
            $mes_generacion = $fecha_pago[1];

            $consulta_sucursal_contrato = SQL::seleccionar(array("consulta_contrato_empleado"), array("*"), "documento_identidad_empleado='$forma_documento_aspirante' AND codigo_sucursal='$forma_codigo_sucursal2' AND fecha_salario <= '$fecha_tiempo' AND estado='1' ","","fecha_ingreso_sucursal,fecha_salario DESC",0,1);
            $datos_sucursal_contrato    = SQL::filaEnObjeto($consulta_sucursal_contrato);

            $varlor_dia = round($datos_sucursal_contrato->valor_dia);

            $transaccion_contable   = SQL::obtenerValor("transacciones_tiempo", "codigo_transaccion_contable", "codigo = '$forma_codigo_transaccion[$id]'");
            $codigo_contable        = SQL::obtenerValor("transacciones_contables_empleado", "codigo_contable","codigo = '$transaccion_contable'");
            $sentido                = SQL::obtenerValor("transacciones_contables_empleado", "sentido","codigo = '$transaccion_contable'");
            $entidad                = SQL::obtenerValor("entidades_salud_empleados", "codigo_entidad_salud","documento_identidad_empleado = '".$forma_documento_aspirante."'");
            $fecha_ingreso_sucursal = SQL::obtenerValor("sucursal_contrato_empleados","max(fecha_ingreso_sucursal)","documento_identidad_empleado='".$forma_documento_aspirante."' and codigo_sucursal='".$forma_codigo_sucursal2."'");
            $empresa                = SQL::obtenerValor("sucursal_contrato_empleados","codigo_empresa","documento_identidad_empleado='".$forma_documento_aspirante."' and fecha_ingreso_sucursal='".$fecha_ingreso_sucursal."' and codigo_sucursal='".$forma_codigo_sucursal2."'");
            $fecha_ingreso          = SQL::obtenerValor("sucursal_contrato_empleados","fecha_ingreso","documento_identidad_empleado='".$forma_documento_aspirante."' and fecha_ingreso_sucursal='".$fecha_ingreso_sucursal."' and codigo_sucursal='".$forma_codigo_sucursal2."'");
            $planilla               = SQL::obtenerValor("sucursal_contrato_empleados","codigo_planilla","documento_identidad_empleado='".$forma_documento_aspirante."' and fecha_ingreso_sucursal='".$fecha_ingreso_sucursal."' and codigo_sucursal='".$forma_codigo_sucursal2."'");
            $tipo_planilla          = SQL::obtenerValor("planillas","periodo_pago","codigo='".$planilla."'");

            $anexo    = SQL::obtenerValor("sucursal_contrato_empleados","codigo_anexo_contable","documento_identidad_empleado='$forma_documento_aspirante' AND codigo_sucursal='$forma_codigo_sucursal2' ORDER BY fecha_ingreso_sucursal DESC LIMIT 0,1");
            $consulta = SQL::seleccionar(array("plan_contable PC,job_transacciones_contables_empleado TCE,job_transacciones_tiempo TT"),array("PC.codigo_anexo_contable"),"TT.codigo_transaccion_contable=TCE.codigo AND TCE.codigo_contable=PC.codigo_contable AND TT.codigo='$forma_codigo_transaccion[$id]'");
            $datos    = SQL::filaEnObjeto($consulta);
            $anexoTr  = $datos->codigo_anexo_contable;

            if($anexoTr=="0" || $anexoTr==""){
                $codigo_empresa_auxiliar  = '0';
                $codigo_anexo_contable    = '';
                $codigo_auxiliar_contable = '0';
            }elseif($anexoTr==$anexo){
                $codigo_empresa_auxiliar  = $datos_sucursal_contrato->codigo_empresa_auxiliar;
                $codigo_anexo_contable    = $datos_sucursal_contrato->codigo_anexo_contable;
                $codigo_auxiliar_contable = $datos_sucursal_contrato->codigo_auxiliar;
            }elseif($anexoTr!=$anexo){
                $codigo_empresa_auxiliar  = SQL::obtenerValor("auxiliares_contables","codigo_empresa","codigo='$forma_codigo_auxiliar_contable_oculto[$id]'");
                $codigo_anexo_contable    = $forma_codigo_anexo_contable_oculto[$id];
                $codigo_auxiliar_contable = $forma_codigo_auxiliar_contable_oculto[$id];
            }

            if ((int)$mes_generacion == 2){
                if (((int)$ano_generacion % 4 ==0) && ((int)$ano_generacion % 100 !=0 || (int)$ano_generacion % 400 == 0)){
                    $dia_fin = 29;
                } else {
                    $dia_fin = 28;
                }
            } else {
                    $dia_fin = 30;
            }

            $fecha_inicio = $ano_generacion."-".$mes_generacion."-01";
            $fecha_fin    = $ano_generacion."-".$mes_generacion."-".$dia_fin;

            $periodo      = '1';

            if($tipo_planilla=='1'){//Mensual
                $fecha_pago_planilla  = SQL::obtenerValor("fechas_planillas","fecha","codigo_planilla='".$planilla."' AND fecha BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."'");
                $periodo = '1';
            }elseif($tipo_planilla=='2'){//Quincenal
                $fechas_pago_planilla = SQL::seleccionar(array("fechas_planillas"),array("fecha"),"codigo_planilla='".$planilla."' AND fecha >= '".$fecha_inicio."'");
                $datosPQ = SQL::filaEnObjeto($fechas_pago_planilla);
                $fecha_pago_planilla = $datosPQ->fecha;
                $fpag = strtotime($fecha_pago_planilla);
                $finc = strtotime($fecha_tiempo);
                if($finc <= $fpag){
                    $periodo = '2';
                }else{
                    $datosSQ = SQL::filaEnObjeto($fechas_pago_planilla);
                    $fecha_pago_planilla = $datosSQ->fecha;
                    $periodo = '3';
                }
            } else if ($tipo_planilla=='4'){
                $fecha_pago_planilla  = SQL::obtenerValor("fechas_planillas","fecha","codigo_planilla='".$planilla."' AND fecha BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."'");
                $periodo = '9';
            }

            $datos = array(
                "fecha_generacion"              => $fecha_registro,
                "codigo_empresa"                => $empresa,
                "documento_identidad_empleado"  => $forma_documento_aspirante,
                "fecha_ingreso"                 => $fecha_ingreso,
                "codigo_sucursal"               => $forma_codigo_sucursal2,
                "fecha_ingreso_sucursal"        => $fecha_ingreso_sucursal,
                ///////////////////////////////////
                "ano_generacion"                => $ano_generacion,
                "mes_generacion"                => $mes_generacion,
                "codigo_planilla"               => $planilla,
                "fecha_pago_planilla"           => $fecha_pago_planilla,
                "codigo_empresa_auxiliar"       => $codigo_empresa_auxiliar,
                "codigo_anexo_contable"         => $codigo_anexo_contable,
                "codigo_auxiliar_contable"      => $codigo_auxiliar_contable,
                "periodo_pago"                  => $periodo,
                "contabilizado"                 => '0',
                ///////////////////////////////
                "fecha_inicio_tiempo"           => $forma_fecha_inicio[$id],
                "fecha_tiempo"                  => $fecha_tiempo,
                "codigo_transaccion_tiempo"     => $forma_codigo_transaccion[$id],
                "codigo_transaccion_contable"   => $transaccion_contable,
                "codigo_contable"               => $codigo_contable,
                "sentido"                       => $sentido,
                "dias_no_laborados"             => '1',
                "valor_dia"                     => $varlor_dia,
                "codigo_motivo_no_laboral"      => $forma_motivo_no_laborado[$id],
                "codigo_usuario_modifica"       => $sesion_codigo_usuario
             );

            $insertar = SQL::insertar("movimiento_tiempos_no_laborados_dias", $datos);
            /// Error de insercÛn
            if (!$insertar) {
                $error   = true;
                $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
            }else{
                $eliminar = SQL::eliminar("movimiento_tiempos_laborados","fecha_inicio='$fecha_tiempo' AND documento_identidad_empleado='$forma_documento_aspirante' AND codigo_sucursal='$forma_codigo_sucursal2'");
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

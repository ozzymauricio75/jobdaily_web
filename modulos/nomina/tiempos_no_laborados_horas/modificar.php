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

require("clases/clases.php");

$dias = array(
    '0' => 'domingo',
    '1' => 'lunes',
    '2' => 'martes',
    '3' => 'miercoles',
    '4' => 'jueves',
    '5' => 'viernes',
    '6' => 'sabado'
);

if(isset($url_tipo_transaccion)){
    $codigo_concepto_transaccion_tiempo = SQL::obtenerValor("transacciones_tiempo","codigo_concepto_transaccion_tiempo", "codigo='url_transaccion_tiempo'");
    $tipo = SQL::obtenerValor("conceptos_transacciones_tiempo","tipo","codigo='$codigo_concepto_transaccion_tiempo'");
    HTTP::enviarJSON($tipo);
    exit;
}

if(isset($url_obtenerFechaRango) && isset($url_documento_identidad))
{
    $consulta_fecha_inicio = SQL::seleccionar(array("sucursal_contrato_empleados"),array("fecha_ingreso"),"documento_identidad_empleado='$url_documento_identidad'", "", "fecha_ingreso ASC", 0, 1);
    $datos_fecha_inicio = SQL::filaEnObjeto($consulta_fecha_inicio);

    $rango_dias   = (int)(strtotime(date("Y-m-d")) - strtotime($datos_fecha_inicio->fecha_ingreso)) / (60 * 60 * 24);
    HTTP::enviarJSON($rango_dias);
    exit;
}

if(isset($url_verificarHoraDentroTurno)){
    $respuesta = verificarHoraDentroTurno($url_documento_identidad,$url_hora_inicio,$url_hora_fin,$url_fecha_inicio,$url_sucursal,$dias,$textos);
    HTTP::enviarJSON($respuesta);
}

if(isset($url_verificar)){
    $condicion_extra = "id_sucursal='$url_codigo_sucursal'";
    echo SQL::datosAutoCompletar("seleccion_empleados", $url_q, $condicion_extra);
    exit;
}
if (!empty($url_recargar)) {
    if($url_elemento == "auxiliares_contables"){
        // Listado de los anexos contables que pertenecen a la empresa en la que pertenece la sucursal con que se inicio sesion
        $empresa   = SQL::obtenerValor("sucursales","codigo_empresa","codigo='".$sesion_sucursal."'");
        $respuesta = HTML::generarDatosLista("auxiliares_contables","codigo","descripcion", "codigo_empresa='$empresa' AND codigo_anexo_contable = '$url_origen'");
    }
    HTTP::enviarJSON($respuesta);
}

//////////////////////////////////////////
if(isset($url_verificaAnexos)){

    $anexoEmpleado = SQL::obtenerValor("sucursal_contrato_empleados","codigo_anexo_contable","documento_identidad_empleado='".$url_empleado."' AND codigo_sucursal='".$url_sucursal."' ORDER BY fecha_ingreso_sucursal DESC LIMIT 0,1");
    $auxilEmpleado = SQL::obtenerValor("sucursal_contrato_empleados","codigo_auxiliar","documento_identidad_empleado='".$url_empleado."' AND codigo_sucursal='".$url_sucursal."' ORDER BY fecha_ingreso_sucursal DESC LIMIT 0,1");

    if($anexoEmpleado== "0" || $anexoEmpleado== ""){
        $respuesta[0] = 1;//No tiene anexo contable
    }else{
        $respuesta[0] = 2;
        $respuesta[1] = $anexoEmpleado;
        $respuesta[2] = $auxilEmpleado;
    }

    HTTP::enviarJSON($respuesta);
    exit;
}

if(!empty($url_generar)){
    $error  = "";
    $titulo = $componente->nombre;


    ///Obtener lista de sucursales para selecci�n dependiendo a los permisos///
    $consulta = SQL::seleccionar(array("sucursales"),array("*"),"codigo > 0","","nombre");
    if ($sesion_usuario == $datosGlobales["usuarioMaestro"]){
         while ($datos = SQL::filaEnObjeto($consulta)){
            $sucursales[$datos->codigo] = $datos->nombre;
        }
    } else {
        /*** Obtener lista de sucursales para selecci�n ***/
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
    $consulta_transaccion_tiempo = SQL::seleccionar(array("transacciones_tiempo"), array("codigo","nombre"), "codigo='0' OR codigo_concepto_transaccion_tiempo IN (select codigo from job_conceptos_transacciones_tiempo where tipo='2' OR tipo='4')", "", "nombre");
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
        $id_modulo = SQL::obtenerValor("componentes","id_modulo","id = '$componente->id'");

        //////////////Cargar Datos//////////////////

        $llave_primaria = explode("|", $url_id);
        $codigo_sucursal = $llave_primaria[0];
        $hora_fin        = $llave_primaria[1];
        $hora_inicio     = $llave_primaria[2];
        $fecha_reporte   = $llave_primaria[3];
        $documento_identidad_empleado = $llave_primaria[4];

        $condicion = "codigo_sucursal='$codigo_sucursal' AND hora_inicio='$hora_inicio' AND hora_fin='$hora_fin' AND fecha_registro='$fecha_reporte' AND documento_identidad_empleado='$documento_identidad_empleado'";
        $consulta_movimiento_tiempos_no_laborados_horas = SQL::seleccionar(array("movimiento_tiempos_no_laborados_horas"),array("*"),$condicion);
        $datos_movimiento_tiempos_no_laborados_horas = SQL::filaEnObjeto($consulta_movimiento_tiempos_no_laborados_horas);

        $nombre_sucursal   = SQL::obtenerValor("sucursales","nombre"," codigo = '$codigo_sucursal'");
        $nombre_empleado   = SQL::obtenerValor("seleccion_empleados","SUBSTRING_INDEX(nombre_completo,'|',1)"," id = '$documento_identidad_empleado'");

        $informacion_turno = verificarHoraDentroTurno($documento_identidad_empleado,$datos_movimiento_tiempos_no_laborados_horas->hora_inicio,$datos_movimiento_tiempos_no_laborados_horas->hora_fin,$datos_movimiento_tiempos_no_laborados_horas->fecha_registro,$codigo_sucursal,$dias,$textos);

        ///////////////////////////////////////////
        $transacciones    = array();

        while ($fila = SQL::filaEnArreglo($consulta_transaccion_tiempo)) {
            $transacciones[$fila[0]] = $fila[1];
        }


        $empresa           = SQL::obtenerValor("sucursales","codigo_empresa","codigo='".$sesion_sucursal."'");
        $anexos_contables  = array();
        $consulta          = SQL::seleccionar(array("auxiliares_contables,job_anexos_contables"), array("codigo_anexo_contable","job_anexos_contables.descripcion"), "codigo_empresa='$empresa' AND codigo_anexo_contable=job_anexos_contables.codigo", "codigo_anexo_contable");

        $anexos_contables[0] = "";
        while($fila = SQL::filaEnArreglo($consulta)){
            $anexos_contables[$fila[0]] = $fila[1];
        }

         ///Obtengo preferencia del valor de la cuota minima de pago///
        $listado_auxiliares = HTML::generarDatosLista("auxiliares_contables","codigo","descripcion", "codigo_empresa='$empresa' AND codigo_anexo_contable = '$datos_movimiento_tiempos_no_laborados_horas->codigo_anexo_contable'");
        // Definicion de pestana Basica
        $formularios["PESTANA_BASICA"] = array(
            array(
                HTML::mostrarDato("nombre_codigo_sucursal", $textos["SUCURSAL"],$nombre_sucursal),
                HTML::mostrarDato("nombre_empleado", $textos["EMPLEADO"],$nombre_empleado),
                HTML::campoOculto("codigo_sucursal",$codigo_sucursal),
                HTML::campoOculto("documento_identidad_empleado",$documento_identidad_empleado),
                HTML::campoOculto("consecutivo",$datos_movimiento_tiempos_no_laborados_horas->consecutivo),
                HTML::listaSeleccionSimple("*anexos_contables", $textos["ANEXO_CONTABLE"],$anexos_contables,$datos_movimiento_tiempos_no_laborados_horas->codigo_anexo_contable, array("title" => $textos["AYUDA_SUCURSAL"],"onmouseover" => "verificarAnexosEnTransacciones()", "onfocus" => "verificarAnexosEnTransacciones()", "onchange" => "recargarLista('anexos_contables','auxiliares_contables');")),
                HTML::listaSeleccionSimple("*auxiliares_contables", $textos["AUXILIAR_CONTABLE"],$listado_auxiliares,$datos_movimiento_tiempos_no_laborados_horas->codigo_auxiliar_contable, array("title" => $textos["AYUDA_SUCURSAL"])),
                HTML::listaSeleccionSimple("*codigo_transaccion_tiempo", $textos["TIPO_TRANSACCION"], $transacciones,$datos_movimiento_tiempos_no_laborados_horas->codigo_transaccion_tiempo, array("title" => $textos["AYUDA_TIPO_TRANSACCION"])),
                HTML::listaSeleccionSimple("*codigo_motivo_tiempo", $textos["MOTIVO_TIEMPO_NO_LABORADO"], HTML::generarDatosLista("motivos_tiempo_no_laborado ","codigo", "descripcion"),$datos_movimiento_tiempos_no_laborados_horas->codigo_auxiliar_contable, array("title" => $textos["AYUDA_MOTIVO_TIEMPO_NO_LABORADO"]))
            ),
            array(
                HTML::campoTextoCorto("*fecha_reporte", $textos["FECHA_RFEPORTE"],15, 25,$datos_movimiento_tiempos_no_laborados_horas->fecha_registro, array("title" => $textos["AYUDA_FECHAS"],"class" => "selectorFecha", "onfocus" => "obtenerRango();", "onclick" => "obtenerRango();")),
                HTML::mostrarDato("turno_laborar",$textos["TURNO_LABORAL"],$informacion_turno[0])

            ),
            array(
                HTML::campoTextoCorto("*hora_inicio", $textos["HORA_INICIO"], 5, 5,$datos_movimiento_tiempos_no_laborados_horas->hora_inicio, array("title" => $textos["AYUDA_HORA_INICIO"], "class" => "hora")),
                HTML::campoTextoCorto("*hora_fin", $textos["HORA_FIN"], 5, 5,$datos_movimiento_tiempos_no_laborados_horas->hora_fin, array("title" => $textos["AYUDA_HORA_FIN"], "class" => "hora"))
            ),
            array(
                HTML::campoOculto("tipo_turno",$informacion_turno[1])
               .HTML::campoOculto("dia_descanso",$informacion_turno[2])
               .HTML::campoOculto("hora_inicial_turno1",$informacion_turno[3])
               .HTML::campoOculto("hora_final_turno1",$informacion_turno[4])
               .HTML::campoOculto("hora_inicial_turno2",$informacion_turno[5])
               .HTML::campoOculto("hora_final_turno2",$informacion_turno[6])
            )
        );
        // Definicion de botones
        $botones   = array(HTML::boton("botonAceptar", $textos["ACEPTAR"],"modificarItem('$url_id');", "aceptar"));
        $contenido = HTML::generarPestanas($formularios, $botones);
        // Enviar datos para la generacion del formulario al script que origino la peticion
        $respuesta[0] = $error;
        $respuesta[1] = $titulo;
        $respuesta[2] = $contenido;
    }

    HTTP::enviarJSON($respuesta);

} // Adicionar los datos provenientes del formulario
elseif(!empty($forma_procesar)){
    // Asumir por defecto que no hubo error
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];
    $fecha_actual= date("Y-m-d H:i:s");
    $continuar_descanso = true;
    $hora_dentro_turno  = false;

    $condicion = "";

    if($forma_dia_descanso=='0'){

        $forma_hora_inicio = $forma_hora_inicio.":00";
        $forma_hora_fin    = $forma_hora_fin.":00";
        $condicion = "";

        $tiempo_hora_inicio = strtotime($forma_hora_inicio);
        $tiempo_hora_fin    =  strtotime($forma_hora_fin);

        if($tiempo_hora_inicio < $tiempo_hora_fin ){
            if($forma_tipo_turno=='1'){
                $condicion = "(('$forma_hora_inicio' BETWEEN '$forma_hora_inicial_turno1' AND '$forma_hora_final_turno1') AND ('$forma_hora_fin' BETWEEN '$forma_hora_inicial_turno1' AND '$forma_hora_final_turno1'))";
            }else{
                $condicion  = " (('$forma_hora_inicio' BETWEEN '$forma_hora_inicial_turno1' AND '$forma_hora_final_turno1') AND ('$forma_hora_fin' BETWEEN '$forma_hora_inicial_turno1' AND '$forma_hora_final_turno1'))";
                $condicion .= " || (('$forma_hora_inicio' BETWEEN '$forma_hora_inicial_turno2' AND '$forma_hora_final_turno2') AND ('$forma_hora_fin' BETWEEN '$forma_hora_inicial_turno2' AND '$forma_hora_final_turno2'))";
            }
            //echo var_dump($condicion);
            $consulta_dentro_rango = SQL::seleccionar(array("turnos_laborales"),array("*"),$condicion);
            if(SQL::filasDevueltas($consulta_dentro_rango)){
                $hora_dentro_turno = true;
            }
        }

    }else{
        $continuar_descanso = false;
    }

    if(empty($forma_documento_identidad_empleado)){
        $error = true;
        $mensaje = $textos["ERROR_VACIO_EMPLEADO"];
    }elseif(empty($forma_codigo_transaccion_tiempo) || $forma_codigo_transaccion_tiempo == 0){
        $error = true;
        $mensaje = $textos["ERROR_VACIO_TRANSACCION"];
    }elseif(empty($forma_fecha_reporte)){
        $error = true;
        $mensaje = $textos["ERROR_VACIO_FECHA_REPORTE"];
    }elseif(!$continuar_descanso){
        $error = true;
        $mensaje = $textos["MESAJE_DIA_DESCASO"];
    }elseif(!$hora_dentro_turno){
        $error = true;
        $mensaje = $textos["ERROR_RANGO_HORA"];
    }else{

        $HoraInicioDB = $forma_hora_inicio;
        $HoraFinalDB  = $forma_hora_fin;

        $tabla     = array("movimiento_tiempos_no_laborados_horas");
        $campos    = array("documento_identidad_empleado");
        $condicion = "documento_identidad_empleado = '".$forma_documento_identidad_empleado."' AND fecha_registro = '".$forma_fecha_reporte."'";
        $condicion.= " AND ((('".$HoraInicioDB."' >= hora_inicio) AND ('".$HoraFinalDB."' >= hora_fin) AND ('".$HoraInicioDB."' < hora_fin))";
        $condicion.= " OR (('".$HoraInicioDB."' <= hora_inicio) AND ('".$HoraFinalDB."' <= hora_fin) AND ('".$HoraFinalDB."' > hora_inicio))";
        $condicion.= " OR (('".$HoraInicioDB."' <= hora_inicio) AND ('".$HoraFinalDB."' >= hora_fin))";
        $condicion.= " OR (('".$HoraInicioDB."' >= hora_inicio) AND ('".$HoraFinalDB."' <= hora_fin))) AND consecutivo != ".$forma_consecutivo;

        $consultaCruce = SQL::filasDevueltas(SQL::seleccionar($tabla,$campos,$condicion));

        if($consultaCruce > 0){
            $error   = true;
            $mensaje = $textos["ERROR_EXISTE_CRUCE"];
        }else{


            $llave_primaria = explode("|", $forma_id);

            $codigo_sucursal = $llave_primaria[0];
            $hora_fin        = $llave_primaria[1];
            $hora_inicio     = $llave_primaria[2];
            $fecha_reporte   = $llave_primaria[3];
            $documento_identidad_empleado = $llave_primaria[4];

            $condicion = "codigo_sucursal='$codigo_sucursal' AND hora_inicio='$hora_inicio' AND hora_fin='$hora_fin' AND fecha_registro='$fecha_reporte' AND documento_identidad_empleado='$documento_identidad_empleado'";

            $consulta                = SQL::seleccionar(array("consulta_contrato_empleado"), array("*"), "documento_identidad_empleado='$forma_documento_identidad_empleado' AND codigo_sucursal='$forma_codigo_sucursal' AND fecha_salario <= '$forma_fecha_reporte' AND estado= '1' ","","fecha_ingreso_sucursal,fecha_salario DESC",0,1);
            $datos                   = SQL::filaEnObjeto($consulta);

            $empresa                 = $datos->codigo_empresa;
            $fecha_ingreso           = $datos->fecha_ingreso;
            $fecha_ingeso_sucursal   = $datos->fecha_ingreso_sucursal;
            $codigo_planilla         = $datos->codigo_planilla;
            $valor_hora              = $datos->valor_hora;

            $valor_minuto            = $valor_hora/60;
            $cantidad_minutos        = (($tiempo_hora_fin-$tiempo_hora_inicio)/60);

            $continuar = true;

            $fecha_arreglo      = explode("-",$forma_fecha_reporte);
            $ano_generacion     = $fecha_arreglo[0];
            $mes_generacion     = $fecha_arreglo[1];
            $fecha_incapacidad  = $forma_fecha_reporte;
            $fecha_pago         = explode("-",$fecha_incapacidad);
            $ano_generacion     = $fecha_pago[0];
            $mes_generacion     = $fecha_pago[1];
            if ($mes_generacion == 2){
                if (($ano_generacion % 4 ==0) && ($ano_generacion % 100 !=0 || $ano_generacion % 400 == 0)){
                    $dia_fin = 29;
                } else {
                    $dia_fin = 28;
                }
            } else {
                $dia_fin = 30;
            }
            $fecha_inicio = $ano_generacion."-".$mes_generacion."-01";
            $fecha_fin    = $ano_generacion."-".$mes_generacion."-".$dia_fin;
            $periodo      = 1;

            $planilla      = $datos->codigo_planilla;
            $tipo_planilla = SQL::obtenerValor("planillas","periodo_pago","codigo='".$planilla."'");

            /////////////////////// Calculo de la fecha de pago en planilla y del periodo ///////////////////////
            /////////////////////////////////////////////////////////////////////////////////////////////////////
            if($tipo_planilla==1){//Mensual
                $fecha_pago_planilla  = SQL::obtenerValor("fechas_planillas","fecha","codigo_planilla='".$planilla."' AND fecha BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."'");
                $periodo=1;
            }elseif($tipo_planilla==2){//Quincenal
                $fechas_pago_planilla = SQL::seleccionar(array("fechas_planillas"),array("fecha"),"codigo_planilla='".$planilla."' AND fecha BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."'");
                $datosPQ              = SQL::filaEnObjeto($fechas_pago_planilla);
                $fecha_pago_planilla  = $datosPQ->fecha;
                $fpag                 = strtotime($fecha_pago_planilla);
                $finc                 = strtotime($fecha_incapacidad);
                if($finc<=$fpag){
                    $periodo=2;
                }else{
                    $datosSQ             = SQL::filaEnObjeto($fechas_pago_planilla);
                    $fecha_pago_planilla = $datosSQ->fecha;
                    $periodo             = 3;
                }
            } else if($tipo_planilla==4){
                $fecha_pago_planilla  = SQL::obtenerValor("fechas_planillas","fecha","codigo_planilla='".$planilla."' AND fecha BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."'");
                $periodo=9;
            }

            $tasa                         = SQL::obtenerValor("transacciones_tiempo", "tasa", "codigo = '$forma_codigo_transaccion_tiempo'");
            $codigo_transaccion_contable  = SQL::obtenerValor("transacciones_tiempo", "codigo_transaccion_contable", "codigo = '$forma_codigo_transaccion_tiempo'");
            $codigo_contable              = SQL::obtenerValor("transacciones_contables_empleado", "codigo_contable","codigo = '".$codigo_transaccion_contable."'");
            $sentido                      = SQL::obtenerValor("transacciones_contables_empleado", "sentido","codigo = '".$codigo_transaccion_contable."'");


            $total_valor_minutos          = $cantidad_minutos * $valor_minuto;
            $valor_movimiento             = $total_valor_minutos+(($total_valor_minutos*$tasa)/100);
            $valor_movimiento             = round($valor_movimiento);
            $valor_hora                   = round($valor_hora);

            $datos_registro = array (
                "fecha_generacion"             => $fecha_actual,
                "codigo_empresa"               => $empresa,
                "fecha_ingreso"                => $fecha_ingreso,
                 /////////////////////////////////
                "codigo_planilla"              => $codigo_planilla,
                /////////////////////////////////
                "fecha_registro"               => $forma_fecha_reporte,
                "hora_inicio"                  => $forma_hora_inicio,
                "hora_fin"                     => $forma_hora_fin,
                /////////////////////////////////
                "codigo_transaccion_tiempo"    => $forma_codigo_transaccion_tiempo,
                "codigo_transaccion_contable"  => $codigo_transaccion_contable,
                "codigo_contable"              => $codigo_contable,
                "sentido"                      => $sentido,
                "tasa"                         => $tasa,
                /////////////////////////////////
                "ano_generacion"               => $ano_generacion ,
                "mes_generacion"               => $mes_generacion ,
                "fecha_pago_planilla"          => $fecha_pago_planilla,
                "codigo_empresa_auxiliar"      => $empresa,
                "codigo_anexo_contable"        => $forma_anexos_contables,
                "codigo_auxiliar_contable"     => $forma_auxiliares_contables,
                "periodo_pago"                 => $periodo,
                /////////////////////////////////
                "cantidad_minutos"             => $cantidad_minutos,
                "valor_hora_salario"           => $valor_hora,
                "valor_movimiento"             => $valor_movimiento,
                "codigo_motivo_no_laboral"     => $forma_codigo_motivo_tiempo,
                "codigo_usuario_modifica"      => $sesion_codigo_usuario,
            );
            $insertar = SQL::modificar("movimiento_tiempos_no_laborados_horas",$datos_registro,$condicion);

            if(!$insertar){
                $error   = true;
                $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
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

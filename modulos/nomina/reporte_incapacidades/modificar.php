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

if (isset($url_generarIncapacidades)){

    $fechasTabla  = explode('|',$url_fechasTabla);
    $motivosTabla = explode('|',$url_motivosTabla);

    HTTP::enviarJSON(generar_Incapacidades_Modificar($url_empleado,$url_fecha_reporte,$url_fecha_inicio,$url_dias_incapacidad,$url_id_Motivo,$url_id_transaccion,$fechasTabla,$motivosTabla,$url_codigo_sucursal,$url_estado_anexo,$url_anexo,$url_auxiliar,$textos));
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
    $consulta = SQL::seleccionar(array("plan_contable PC,job_transacciones_contables_empleado TCE,job_transacciones_tiempo TT"),array("PC.codigo_anexo_contable"),"TT.codigo_transaccion_contable=TCE.codigo AND TCE.codigo_contable=PC.codigo_contable AND TT.codigo=".$url_transaccion);
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
// Generar el formulario para la captura de datos
if (!empty($url_generar)) {

    if (empty($url_id)) {
        $error     = $textos["ERROR_MODIFICAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    }else{
        $datos               = explode('|',$url_id);
        $documento_identidad = $datos[0];
        $fecha_inicial       = $datos[1];
        $vistaConsulta       = "reporte_incapacidades";
        $columnas            = SQL::obtenerColumnas($vistaConsulta);
        $contabilizado       = SQL::obtenerValor("reporte_incapacidades", "contabilizado", "documento_identidad_empleado = '".$documento_identidad."' AND fecha_inicial_incapacidad = '".$fecha_inicial."' LIMIT 0,1");
        if($contabilizado=='0'){
            $consulta            = SQL::seleccionar(array($vistaConsulta), $columnas, "documento_identidad_empleado = '".$documento_identidad."' AND fecha_inicial_incapacidad = '".$fecha_inicial."'");
            $error               = "";
            $titulo              = $componente->nombre;
            $items               = array();

            if (SQL::filasDevueltas($consulta)) {
                $consecutivo=0;
                while ($datos = SQL::filaEnObjeto($consulta)){

                    $ocultos = HTML::campoOculto("posicionTabla[".$consecutivo."]", $consecutivo, array("class" => "posicionTabla"));
                    $ocultos .= HTML::campoOculto("fechaIncapacidadTabla[".$consecutivo."]", $datos->fecha_incapacidad, array("class" => "fechaIncapacidadTabla"));

                    $codigo_transaccion         = $datos->codigo_transaccion_tiempo;
                    $codigo_motivo              = $datos->codigo_motivo_incapacidad;
                    $fecha_reporte              = $datos->fecha_reporte_incapacidad;
                    $fecha_inicial              = $datos->fecha_inicial_incapacidad;
                    $dias                       = $datos->dias_incapacidad;
                    $numero                     = $datos->numero_incapacidad;
                    $codigo_usuario             = $datos->codigo_usuario_registra;
                    $codigo_sucursal            = $datos->codigo_sucursal;
                    $codigo_anexo               = $datos->codigo_anexo_contable;
                    $codigo_auxiliar            = $datos->codigo_auxiliar_contable;
                    $codigo_empresa_auxiliar    = $datos->codigo_empresa_auxiliar;

                    $transaccion = SQL::obtenerValor("transacciones_tiempo","nombre"," codigo = '".$codigo_transaccion."'");
                    $motivo      = SQL::obtenerValor("motivos_incapacidad","descripcion"," codigo = '".$codigo_motivo."'");

                    $items[] = array(
                        $consecutivo,
                        $ocultos,
                        $datos->fecha_incapacidad,
                        $transaccion,
                        $motivo,
                        $numero
                    );
                    $consecutivo++;
                }
            }

            $anexo    = SQL::obtenerValor("sucursal_contrato_empleados","codigo_anexo_contable","documento_identidad_empleado='".$documento_identidad."' AND codigo_sucursal='".$codigo_sucursal."' ORDER BY fecha_ingreso_sucursal DESC LIMIT 0,1");

            if($codigo_anexo=="" || $codigo_anexo=="0"){
                $mostrar_anexo="none";
                $estado_anexo=1;
            }elseif($codigo_anexo==$anexo){
                $mostrar_anexo="none";
                $estado_anexo=2;
            }else{
                $mostrar_anexo="block";
                $estado_anexo=3;
            }

            $empleado    = SQL::obtenerValor("seleccion_empleados","SUBSTRING_INDEX(nombre_completo,'|',1)"," id = '".$documento_identidad."'");

            $transacciones    = array();
            $consulta = SQL::seleccionar(array("transacciones_tiempo"), array("codigo","nombre"), "codigo_concepto_transaccion_tiempo in (select codigo from job_conceptos_transacciones_tiempo where tipo=3)", "", "nombre");

            while ($fila = SQL::filaEnArreglo($consulta)) {
                $transacciones[$fila[0]] = $fila[1];
            }

            $empresa  = SQL::obtenerValor("sucursales","codigo_empresa","codigo='".$codigo_sucursal."'");

            $anexos  = array();
            $consulta = SQL::seleccionar(array("auxiliares_contables,job_anexos_contables"), array("codigo_anexo_contable","job_anexos_contables.descripcion"), "codigo_empresa='".$empresa."' AND codigo_anexo_contable=job_anexos_contables.codigo", "codigo_anexo_contable","job_anexos_contables.descripcion");

            while ($fila = SQL::filaEnArreglo($consulta)) {
                $anexos[$fila[0]] = $fila[1];
            }

            // Definicion de pestana Basica
            $formularios["PESTANA_BASICA"] = array(
                array(
                    HTML::listaSeleccionSimple("*codigo_sucursal", $textos["SUCURSAL_LABORA"], HTML::generarDatosLista("sucursales", "codigo", "nombre"), $codigo_sucursal, array("title" => $textos["AYUDA_SUCURSAL_LABORA"],"onChange" => "pegarValor(this)","disabled" => "disabled"))
                    .HTML::campoOculto("codigo_sucursal2",$codigo_sucursal)
                    .HTML::campoOculto("codigo_usuario",$codigo_usuario),
                    HTML::campoTextoCorto("*selector1", $textos["EMPLEADO"], 45, 255, $empleado, array("title" => $textos["AYUDA_EMPLEADO"], "class" => "autocompletable","disabled" => "disabled"))
                    .HTML::campoOculto("documento_aspirante",$documento_identidad)
                ),
                array(
                    HTML::listaSeleccionSimple("*codigo_transaccion_tiempo", $textos["TIPO_TRANSACCION"], $transacciones, $codigo_transaccion, array("title" => $textos["AYUDA_TIPO_TRANSACCION"],"disabled" => "disabled","onChange" => "verificarAnexosEnTransacciones();"))
                    .HTML::campoOculto("estado_anexo",$estado_anexo),
                    HTML::listaSeleccionSimple("*codigo_motivo_incapacidad", $textos["MOTIVO_INCAPACIDAD"], HTML::generarDatosLista("motivos_incapacidad ", "codigo", "descripcion"), $codigo_motivo, array("title" => $textos["AYUDA_MOTIVO_INCAPACIDAD"],"disabled" => "disabled"))
                ),
                array(
                    HTML::listaSeleccionSimple("*codigo_anexo_contable", $textos["ANEXO_CONTABLE"], $anexos,$codigo_anexo, array("title" => $textos["AYUDA_ANEXO_CONTABLE"], "onChange" => "recargarListaAuxiliares('codigo_anexo_contable','codigo_auxiliar_contable')","style" => "display: ".$mostrar_anexo.";","disabled" => "disabled")),
                    HTML::listaSeleccionSimple("*codigo_auxiliar_contable", $textos["AUXILIAR_CONTABLE"], HTML::generarDatosLista("auxiliares_contables", "codigo", "descripcion", "codigo_anexo_contable = '" . array_shift(array_keys($anexos)) . "'AND codigo_empresa='".$empresa."'"), $codigo_auxiliar, array("title" => $textos["AYUDA_AUXILIAR_CONTABLE"],"style" => "display: ".$mostrar_anexo.";","disabled" => "disabled"))
                ),
                array(
                    HTML::campoTextoCorto("*fecha_reporte_incapacidad", $textos["FECHA_REPORTE"], 10, 10, $fecha_reporte, array("title" => $textos["AYUDA_FECHA_REPORTE"], "class" => "selectorFecha","disabled" => "disabled")),
                    HTML::campoTextoCorto("*fecha_inicial_incapacidad", $textos["FECHA_INICIAL"], 10, 10, $fecha_inicial, array("title" => $textos["AYUDA_FECHA_INICIAL"], "class" => "selectorFecha","disabled" => "disabled")),
                    HTML::campoTextoCorto("*dias_incapacidad", $textos["CANTIDAD_DIAS"], 2, 3, $dias, array("title" => $textos["AYUDA_CANTIDAD_DIAS"], "onKeyPress" => "return campoEntero(event)","disabled" => "disabled")),
                    HTML::campoTextoCorto("numero_incapacidad", $textos["NUMERO_INCAPACIDAD"], 10, 10, $numero, array("title" => $textos["AYUDA_NUMERO_INCAPACIDAD"], "style" => "text-transform: uppercase;","disabled" => "disabled")),
                    HTML::campoOculto("fecha_sistema", $fecha_inicial),
                    HTML::campoOculto("mensaje_fechas", $textos["ERROR_FECHA_MENOR"]),
                    HTML::campoOculto("lista_incapacidad", "0")
                    .HTML::contenedor(HTML::boton("botonRemoverIncapacidad", "", "removerItems(this);", "eliminar"), array("id" => "botonRemoverIncapacidad", "style" => "display: none;"))
                ),
                array(
                    HTML::boton("botonAgregar", $textos["GENERAR_FECHAS"], "cargarIncapacidadesModificar('".$textos["ERROR_DATOS_VACIOS_JS2"]."');", "adicionar",array("style" => "display: none;")),
                    HTML::boton("botonEliminar",$textos["ELIMINAR_TODOS"],"removerTableModificar(this);", "eliminar")
                ),
                array(
                    HTML::generarTabla(
                        array("id","","FECHA_INCAPACIDAD","TIPO_TRANSACCION","MOTIVO_INCAPACIDAD","NUMERO_INCAPACIDAD"),
                        $items,
                        array("C","C","I","I","C"),
                        "listaItemsIncapacidad",
                        false
                    )
                )
            );

            // Definicion de botones
            $botones = array(
                            HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar")
                        );

            $contenido = HTML::generarPestanas($formularios, $botones);
        }else{
            $error     = $textos["NO_MODIFICAR_NO_ELIMINAR"];
            $titulo    = "";
            $contenido = "";
        }
    }
    // Enviar datos para la generacion del formulario al script que origino la peticion
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

// Validar los datos provenientes del formulario
}

// Adicionar los datos provenientes del formulario
elseif (!empty($forma_procesar)) {
    // Asumir por defecto que no hubo error
    $error   = false;
    $mensaje = $textos["ITEM_MODIFICADO"];

    if(!isset($forma_posicionTabla)){
        $error = true;
        $mensaje = $textos["ERROR_TABLA_VACIA"];
    }else{

        $consulta = SQL::eliminar("reporte_incapacidades", "documento_identidad_empleado = '".$forma_documento_aspirante."' AND fecha_inicial_incapacidad = '".$forma_fecha_sistema."'");

        foreach($forma_posicionTabla AS $idTablaIncapacidad){

            $fecha_incapacidad = $forma_fechaIncapacidadTabla[$idTablaIncapacidad];

            $transaccion_contable   = SQL::obtenerValor("transacciones_tiempo", "codigo_transaccion_contable", "codigo = '".$forma_transaccionTabla[$idTablaIncapacidad]."'");
            $codigo_contable        = SQL::obtenerValor("transacciones_contables_empleado", "codigo_contable","codigo = '".$transaccion_contable."'");
            $sentido                = SQL::obtenerValor("transacciones_contables_empleado", "sentido","codigo = '".$transaccion_contable."'");
            $entidad                = SQL::obtenerValor("entidades_salud_empleados", "codigo_entidad_salud","documento_identidad_empleado = '".$forma_documento_aspirante."'");
            $fecha_ingreso_sucursal = SQL::obtenerValor("sucursal_contrato_empleados","max(fecha_ingreso_sucursal)","documento_identidad_empleado='".$forma_documento_aspirante."' and codigo_sucursal='".$forma_codigo_sucursal2."' and fecha_ingreso_sucursal <= '".$fecha_incapacidad."'");
            $empresa                = SQL::obtenerValor("sucursal_contrato_empleados","codigo_empresa","documento_identidad_empleado='".$forma_documento_aspirante."' and fecha_ingreso_sucursal='".$fecha_ingreso_sucursal."' and codigo_sucursal='".$forma_codigo_sucursal2."'");
            $fecha_ingreso          = SQL::obtenerValor("sucursal_contrato_empleados","fecha_ingreso","documento_identidad_empleado='".$forma_documento_aspirante."' and fecha_ingreso_sucursal='".$fecha_ingreso_sucursal."' and codigo_sucursal='".$forma_codigo_sucursal2."'");
            $planilla               = SQL::obtenerValor("sucursal_contrato_empleados","codigo_planilla","documento_identidad_empleado='".$forma_documento_aspirante."' and fecha_ingreso_sucursal='".$fecha_ingreso_sucursal."' and codigo_sucursal='".$forma_codigo_sucursal2."'");
            $tipo_planilla          = SQL::obtenerValor("planillas","periodo_pago","codigo='".$planilla."'");

            $empresaF   = SQL::obtenerValor("sucursales","codigo_empresa","codigo='".$forma_codigo_sucursal2."'");
            $auxiliarDB = SQL::obtenerValor("sucursal_contrato_empleados","codigo_auxiliar","documento_identidad_empleado='".$forma_documento_aspirante."' AND codigo_sucursal='".$forma_codigo_sucursal2."' and fecha_ingreso_sucursal='".$fecha_ingreso_sucursal."' ORDER BY fecha_ingreso_sucursal DESC lIMIT 0,1");
            $anexoDB    = SQL::obtenerValor("sucursal_contrato_empleados","codigo_anexo_contable","documento_identidad_empleado='".$forma_documento_aspirante."' AND codigo_sucursal='".$forma_codigo_sucursal2."' and fecha_ingreso_sucursal='".$fecha_ingreso_sucursal."' ORDER BY fecha_ingreso_sucursal DESC lIMIT 0,1");

            if($forma_estado_anexo==1){
                $anexo    = "";
                $auxiliar = 0;
                $empresa  = 0;
            }elseif($forma_estado_anexo==2){
                $anexo    = $anexoDB;
                $auxiliar = $auxiliarDB;
                $empresa  = $empresaF;
            }elseif($forma_estado_anexo==3){
                $anexo    = $forma_codigo_anexo_contable;
                $auxiliar = $forma_codigo_auxiliar_contable;
                $empresa  = $empresaF;
            }

            ////////////////////////////////////////////////////////////////////////////////////////////////
            $fecha_pago     = explode("-",$fecha_incapacidad);
            $ano_generacion = $fecha_pago[0];
            $mes_generacion = $fecha_pago[1];

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
            ////////////////////////////////////////////////////////////////////////////////////////////////

            if($tipo_planilla=='1'){//Mensual
                $fecha_pago_planilla  = SQL::obtenerValor("fechas_planillas","fecha","codigo_planilla='".$planilla."' AND fecha BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."'");
                $periodo='1';
            }elseif($tipo_planilla=='2'){//Quincenal
                $fechas_pago_planilla = SQL::seleccionar(array("fechas_planillas"),array("fecha"),"codigo_planilla='".$planilla."' AND fecha >= '".$fecha_inicio."'");
                $datosPQ = SQL::filaEnObjeto($fechas_pago_planilla);
                $fecha_pago_planilla = $datosPQ->fecha;
                $fpag = strtotime($fecha_pago_planilla);
                $finc = strtotime($fecha_incapacidad);
                if($finc<=$fpag){
                    $periodo='2';
                }else{
                    $datosSQ = SQL::filaEnObjeto($fechas_pago_planilla);
                    $fecha_pago_planilla = $datosSQ->fecha;
                    $periodo='3';
                }
            } else if($tipo_planilla=='4'){
                $fecha_pago_planilla  = SQL::obtenerValor("fechas_planillas","fecha","codigo_planilla='".$planilla."' AND fecha BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."'");
                $periodo = '9';
            }

            $datos = array(
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
                "codigo_empresa_auxiliar"       => $forma_empresaTabla[$idTablaIncapacidad],
                "codigo_anexo_contable"         => $forma_anexoTabla[$idTablaIncapacidad],
                "codigo_auxiliar_contable"      => $forma_auxiliarTabla[$idTablaIncapacidad],
                "periodo_pago"                  => $periodo,
                "contabilizado"                 => '0',
                ///////////////////////////////////
                "fecha_incapacidad"             => $fecha_incapacidad,
                "fecha_reporte_incapacidad"     => $forma_fechaReporteTabla[$idTablaIncapacidad],
                "fecha_inicial_incapacidad"     => $forma_fechaInicialTabla[$idTablaIncapacidad],
                "codigo_transaccion_tiempo"     => $forma_transaccionTabla[$idTablaIncapacidad],
                "codigo_transaccion_contable"   => $transaccion_contable,
                "codigo_contable"               => $codigo_contable,
                "sentido"                       => $sentido,
                "dias_incapacidad"              => $forma_diasTabla[$idTablaIncapacidad],
                "valor_dia"                     => $forma_valorDiaTabla[$idTablaIncapacidad],
                "dividendo"                     => $forma_dividendoTabla[$idTablaIncapacidad],
                "divisor"                       => $forma_divisorTabla[$idTablaIncapacidad],
                "valor_movimiento"              => round($forma_valorMovimientoTabla[$idTablaIncapacidad]),
                "codigo_motivo_incapacidad"     => $forma_motivoTabla[$idTablaIncapacidad],
                "codigo_entidad_parafiscal"     => $entidad,
                "numero_incapacidad"            => $forma_numeroTabla[$idTablaIncapacidad],
                "fecha_registro"                => date("Y-m-d H:i:s"),
                "codigo_usuario_registra"       => $forma_codigo_usuario,
                "codigo_usuario_modifica"       => $sesion_codigo_usuario
            );

            $insertar = SQL::insertar("reporte_incapacidades", $datos);
            if($insertar){
                $eliminar = SQL::eliminar("movimiento_tiempos_laborados","fecha_inicio='".$fecha_incapacidad."' AND documento_identidad_empleado='".$forma_documento_aspirante."'");
                $eliminar = SQL::eliminar("movimiento_tiempos_no_laborados_horas","fecha_registro='".$fecha_incapacidad."' AND documento_identidad_empleado='".$forma_documento_aspirante."'");
            }
        }
        // Error de insercion
        if (!$insertar) {
            $error   = true;
            $mensaje = $textos["ERROR_MODIFICAR_ITEM"];
        }
    }
    // Enviar datos con la respuesta del proceso al script que origino la peticion
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

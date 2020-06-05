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

if(isset($url_obtenerFechaRango) && isset($url_documento_identidad))
{
    $consulta_fecha_inicio = SQL::seleccionar(array("sucursal_contrato_empleados"),array("fecha_ingreso"),"documento_identidad_empleado='$url_documento_identidad'", "", "fecha_ingreso ASC", 0, 1);
    $datos_fecha_inicio = SQL::filaEnObjeto($consulta_fecha_inicio);

    $rango_dias   = (int)(strtotime(date("Y-m-d")) - strtotime($datos_fecha_inicio->fecha_ingreso)) / (60 * 60 * 24);
    HTTP::enviarJSON($rango_dias);
    exit;
}

if(isset($url_determinarTurno))
{
    // Determino mediante el turno a tormar, si el de la asignacion o el del contrato dependiendo la fecha actual
    $fecha_actual= date("Y-m-d");
    $horas_generadas = array();
    $consulta_turno_empleado = SQL::seleccionar(array("asignacion_turnos"),array("*")," ('$fecha_actual' BETWEEN fecha_inicial AND fecha_final) AND documento_identidad_empleado='$url_documento_identidad' ","","fecha_inicial DESC ",0,1);
    $datos_empleado          = SQL::filaEnObjeto($consulta_turno_empleado);
    if($datos_empleado){
        $consulta_turno   = SQL::seleccionar(array("turnos_laborales"),array("*"),"codigo='$datos_empleado->codigo_turno'");
        $datos_turno      = SQL::filaEnObjeto($consulta_turno);
        $descripcion      = $datos_turno->descripcion;
        $permite_festivos = $datos_turno->permite_festivos;
        $paga_domingo     = $datos_turno->paga_dominical;
        $paga_festivos    = $datos_turno->paga_festivo;
    }else{
        $consulta_turno_empleado = SQL::seleccionar(array("sucursal_contrato_empleados"),array("*"),"documento_identidad_empleado='$url_documento_identidad' AND codigo_sucursal = '$url_sucursal' AND fecha_ingreso_sucursal <= '$url_fecha_inicio'","","fecha_ingreso_sucursal DESC ",0,1);
        $datos_empleado          = SQL::filaEnObjeto($consulta_turno_empleado);
        $consulta_turno          = SQL::seleccionar(array("turnos_laborales"),array("*"),"codigo='$datos_empleado->codigo_turno_laboral'");
        $datos_turno             = SQL::filaEnObjeto($consulta_turno);
        $descripcion             = $datos_turno->descripcion;
        $permite_festivos        = $datos_turno->permite_festivos;
        $paga_domingo            = $datos_turno->paga_dominical;
        $paga_festivos           = $datos_turno->paga_festivo;
    }
    $dato_turnos    = array();
    $dato_turnos[0] = $textos["NO"];
    $dato_turnos[1] = $textos["SI"];
    $dato_turnos[2] = $descripcion;
    $dato_turnos[3] = $permite_festivos;
    $dato_turnos[4] = $paga_domingo;
    $dato_turnos[5] = $paga_festivos;
    HTTP::enviarJSON($dato_turnos);
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

if(isset($url_validarTipoHora))
{
    // Determino mediante el turno a tormar, si el de la asignacion o el del contrato dependiendo ala fecha actual
    $url_fecha_inicio = str_replace("/","-",$url_fecha_inicio);
    $url_fecha_fin = str_replace("/","-",$url_fecha_fin);
    $consulta_sucursal_contrato = SQL::seleccionar(array("consulta_contrato_empleado"),array("*"),"documento_identidad_empleado = '$url_documento_identidad' AND codigo_sucursal = '$url_sucursal' AND fecha_ingreso_sucursal <= '$url_fecha_inicio' AND estado = '1'","","fecha_ingreso_sucursal DESC",0,1);
    if(SQL::filasDevueltas($consulta_sucursal_contrato)){
        $fecha_actual            = date("Y-m-d");
        $horas_generadas         = array();
        $consulta_turno_empleado = SQL::seleccionar(array("asignacion_turnos"),array("*")," ('$fecha_actual' BETWEEN fecha_inicial AND fecha_final) AND documento_identidad_empleado='$url_documento_identidad' ","","fecha_inicial DESC ",0,1);
        $datos_empleado          = SQL::filaEnObjeto($consulta_turno_empleado);

        if($datos_empleado){
            $codigo_turno_laboral    = $datos_empleado->codigo_turno;
        }
        if(SQL::filasDevueltas($consulta_turno_empleado)== 0)
        {
            $consulta_turno_empleado = SQL::seleccionar(array("sucursal_contrato_empleados"),array("*"),"documento_identidad_empleado='$url_documento_identidad' AND codigo_sucursal = '$url_sucursal' AND fecha_ingreso_sucursal <= '$url_fecha_inicio'","","fecha_ingreso_sucursal DESC ",0,1);
            $datos_empleado          = SQL::filaEnObjeto($consulta_turno_empleado);
            $codigo_turno_laboral    = $datos_empleado -> codigo_turno_laboral;
        }
        // Verificar que los tipos de horas que se ha generado
        if(SQL::filasDevueltas($consulta_turno_empleado))
        {
            $consulta_tipo_turno = SQL::seleccionar(array("turnos_laborales"),array("*"),"codigo='$codigo_turno_laboral'");
            $datos_tipo_turno    = SQL::filaEnObjeto($consulta_tipo_turno);

            $fecha_completa = getdate(strtotime($url_fecha_inicio));
            $dia            = $dias[$fecha_completa['wday']];
            $campo_turno    = "tipo_turno_".$dia;
            $prueba         = SQL::seleccionar(array("turnos_laborales"), array("*"), "codigo='$codigo_turno_laboral' AND $campo_turno='1'");

            if($datos_tipo_turno->$campo_turno==1)
            {
                $hora_inicial                 = $url_hora_inicio;
                $hora_final                   = $url_hora_fin;
                $fecha_inicio                 = $url_fecha_inicio;
                $fecha_fin                    = $url_fecha_fin;
                $codigo_turno                 = $codigo_turno_laboral;
                $documento_identidad_empleado = $url_documento_identidad;
                $horas_generadas              = generar_Fechas($hora_inicial,$hora_final,$fecha_inicio,$fecha_fin,$codigo_turno,$documento_identidad_empleado,1,$url_sucursal,$dia,$textos);
            }else{
                $hora_inicial                 = $url_hora_inicio;
                $hora_final                   = $url_hora_fin;
                $fecha_inicio                 = $url_fecha_inicio;
                $fecha_fin                    = $url_fecha_fin;
                $codigo_turno                 = $codigo_turno_laboral;
                $documento_identidad_empleado = $url_documento_identidad;
                $horas_generadas              = generar_Fechas($hora_inicial,$hora_final,$fecha_inicio,$fecha_fin,$codigo_turno,$documento_identidad_empleado,0,$url_sucursal,$dia,$textos);
            }
        }
        $horas_generadas_enviar = array();
        $indicador              = true;
        foreach($horas_generadas AS $datos)
        {
            $respuesta=validarCruces($url_documento_identidad,$fecha_inicio,$fecha_fin,$datos[1],$datos[2]);
            if(!empty ($respuesta))
            {
                $respuest[0]= $respuesta;
                HTTP::enviarJSON($respuest);
                $indicador = false;
                exit;
                break;
            }
            $segundos                 = (int) (strtotime($datos[2]) - strtotime($datos[1]));
            $minutos                  = $segundos/60;
            $formato_hora             = conversor_segundos($segundos,$textos);
            $datos[6]                 = $formato_hora;
            $datos[7]                 = $minutos;
            $rango_hora               = (int) (strtotime($datos[2]) - strtotime($datos[1])) / 3600;
            $datos[8]                 = ceil($rango_hora); // ceil - Redondea un float al alza
            $horas_generadas_enviar[] = implode($datos,"!");
        }
        ///////////////////////////////////////////////////////
        if($indicador){
            $horas_generadas_enviar[] = "1";
            HTTP::enviarJSON($horas_generadas_enviar);
            exit;
        }
    }else{
        $consulta_sucursal_contrato = SQL::seleccionar(array("consulta_contrato_empleado"),array("*"),"documento_identidad_empleado = '$url_documento_identidad' AND fecha_ingreso_sucursal <= '$url_fecha_inicio' AND estado = 1","","fecha_ingreso_sucursal DESC",0,1);
        if(SQL::filasDevueltas($consulta_sucursal_contrato)){
            $datosCCE = SQL::filaEnObjeto($consulta_sucursal_contrato);
            $sucursal = SQL::obtenerValor("sucursales","nombre","codigo = '".$datosCCE->codigo_sucursal."'");
            $respuesta = array();
            $respuesta[0] = $textos["ERROR_SUCURSAL_EQUIVOCADA"]." (".$sucursal.")".$textos["ERROR_SUCURSAL_EQUIVOCADA2"];
            HTTP::enviarJSON($respuesta);
            exit;
        }else{
            $respuesta = array();
            $respuesta[0] = $textos["ERROR_EMPLEADO_INACTIVO"];
            HTTP::enviarJSON($respuesta);
            exit;
        }
    }
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


    $empresa           = SQL::obtenerValor("sucursales","codigo_empresa","codigo='".$sesion_sucursal."'");
    $anexos_contables  = array();
    $consulta          = SQL::seleccionar(array("auxiliares_contables,job_anexos_contables"), array("codigo_anexo_contable","job_anexos_contables.descripcion"), "codigo_empresa='$empresa' AND codigo_anexo_contable=job_anexos_contables.codigo", "codigo_anexo_contable");

    $anexos_contables[0] = "";
    while($fila = SQL::filaEnArreglo($consulta)){
        $anexos_contables[$fila[0]] = $fila[1];
    }
    // Obtener lista de sucursales para seleccion dependiendo a los permisos
    $consulta = SQL::seleccionar(array("sucursales"),array("*"),"codigo > 0","","nombre");
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
    ///Obtengo preferencia del valor de la cuota minima de pago///
    $listado_auxiliares = HTML::generarDatosLista("auxiliares_contables","codigo","descripcion", "codigo_empresa='".$empresa."' AND codigo_anexo_contable = '" . array_shift(array_keys($anexos_contables)) . "'");
    // Definicion de pestana Basica
    $formularios["PESTANA_BASICA"] = array(
        array(
            HTML::listaSeleccionSimple("*codigo_sucursal", $textos["SUCURSAL"],$sucursales,$sesion_sucursal, array("title" => $textos["AYUDA_SUCURSAL"],"onchange" => " limpiarCampos();")),
            HTML::campoTextoCorto("*selector1", $textos["EMPLEADO"], 40, 255, "", array("title" => $textos["AYUDA_EMPLEADO"], "onfocus" => "acLocalEmpleados(this);"))
           .HTML::campoOculto("documento_identidad_empleado",""),
            HTML::listaSeleccionSimple("*anexos_contables", $textos["ANEXO_CONTABLE"],$anexos_contables, "", array("title" => $textos["AYUDA_SUCURSAL"],"onmouseover" => "verificarAnexosEnTransacciones()", "onfocus" => "verificarAnexosEnTransacciones()", "onchange" => "recargarLista('anexos_contables','auxiliares_contables');")),
            HTML::listaSeleccionSimple("*auxiliares_contables", $textos["AUXILIAR_CONTABLE"],$listado_auxiliares, "", array("title" => $textos["AYUDA_SUCURSAL"])),
        ),
        array(
            HTML::campoTextoCorto("*fechas", $textos["FECHA_INICIO"].'  -  '.$textos["FECHA_FIN"], 25, 25, "", array("title" => $textos["AYUDA_FECHAS"],"class" => "fechaRango", "onfocus" => "obtenerRango();", "onclick" => "obtenerRango();", "disabled" => "disabled")),
            HTML::contenedor(HTML::campoTextoCorto("*turno_laboral_1", $textos["TURNO_LABORAL"],40, 25, "", array("title" => $textos["AYUDA_FECHAS"], "class" => "turno_laboral","disabled" => "disabled"))."<br/><br/>".HTML::contenedor(HTML::mostrarDato("permite_festivos_1",$textos["PEMITE_FESTIVOS"],"")),array("id" => "contenedor_turno_laboral_1","class" => "oculto")),
            HTML::contenedor(HTML::campoTextoCorto("*turno_laboral_2", $textos["TURNO_LABORAL"],40, 25, "", array("title" => $textos["AYUDA_FECHAS"], "class" => "turno_laboral","disabled" => "disabled"))."<br/><br/>".HTML::mostrarDato("permite_festivos_2",$textos["PEMITE_FESTIVOS"],"").HTML::mostrarDato("poga_dominical",$textos["PAGO_DOMINICAL"],"").HTML::mostrarDato("poga_festivo",$textos["PAGO_FESTIVOS"],""),array("id" => "contenedor_turno_laboral_2","class" => "oculto")),
            HTML::campoOculto("rango_fecha",""),
        ),
        array(
            HTML::mostrarDato("mostrar",$textos["AUTORIZA_EXTRAS"],""),
            HTML::marcaChequeo("autorizarExtra","",1,true,array("class" => "autorizarExtra","onclick" => "horasAutorizadasExtras();")),
        ),
        array(
            HTML::campoTextoCorto("*hora_inicio", $textos["HORA_INICIO"], 5, 5, "__:__", array("title" => $textos["AYUDA_HORA_INICIO"], "class" => "hora")),
            HTML::campoTextoCorto("*hora_fin", $textos["HORA_FIN"], 5, 5, "__:__", array("title" => $textos["AYUDA_HORA_FIN"], "class" => "hora"))
           .HTML::campoOculto("contador", "0")
           .HTML::contenedor(HTML::boton("botonRemoverextras", "", "removerItems(this);", "eliminar"), array("id" => "botonRemoverextras", "style" => "display: none"))

        ),
        array(
            HTML::boton("botonAgregar", $textos["AGREGAR"], "CalcularTipoHoras();determinarTurnos();", "adicionar"),
            HTML::boton("botonEliminar",$textos["ELIMINAR_TODOS"],"removerTable();", "eliminar"),
            HTML::campoOculto("autorizaHoraExtra","")
        ),
        array(
            HTML::generarTabla(
                array("id","","FECHA_INICIO","HORA_INICIO","HORA_FIN","TRANSACCION","CANTIDAD","AUTORIZA_EXTRAS"),
                "",
                array("C","C","C","C","I","I","I"),
                "listaItemsExtras",
                false
            )
        )
    );

    // Definicion de botones
    $botones   = array(HTML::boton("botonAceptar", $textos["ACEPTAR"], "horasAutorizadasExtras();adicionarItem();", "aceptar"));
    $contenido = HTML::generarPestanas($formularios, $botones);
    // Enviar datos para la generacion del formulario al script que origino la peticion
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

} // Adicionar los datos provenientes del formulario
elseif(!empty($forma_procesar)){
    // Asumir por defecto que no hubo error
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];
    $fecha_actual= date("Y-m-d H:i:s");
    if(empty($forma_documento_identidad_empleado) || empty($forma_codigo_sucursal)){
        $error = true;
        $mensaje = $textos["ERROR_DATOS_INCOMPLETOS"];
    }else{

        for($posicion = 0;!empty($forma_fecha_inicio[$posicion]); $posicion++){

            $consulta                = SQL::seleccionar(array("consulta_contrato_empleado"), array("*"), "documento_identidad_empleado='$forma_documento_identidad_empleado' AND codigo_sucursal='$forma_codigo_sucursal' AND fecha_salario <= '$forma_fecha_inicio[$posicion]' AND estado= '1' ","","fecha_ingreso_sucursal,fecha_salario DESC",0,1);
            $datos                   = SQL::filaEnObjeto($consulta);
            //echo var_dump($forma_fecha_inicio[$posicion]);
            $empresa                 = $datos->codigo_empresa;
            $fecha_ingreso           = $datos->fecha_ingreso;
            $fecha_ingeso_sucursal   = $datos->fecha_ingreso_sucursal;
            $codigo_planilla         = $datos->codigo_planilla;
            $valor_hora              = $datos->valor_hora;

            $valor_minuto            = $valor_hora/60;

            $continuar = true;
            $consecutivo = (int)SQL::obtenerValor("movimiento_tiempos_laborados","max(consecutivo)","");
            if($consecutivo){
                $consecutivo++;
            }else{
                $consecutivo=1;
            }
            $fecha_arreglo      = explode("-",$forma_fecha_inicio[$posicion]);
            $ano_generacion     = $fecha_arreglo[0];
            $mes_generacion     = $fecha_arreglo[1];
            $fecha_incapacidad  = $forma_fecha_inicio[$posicion];
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
            } else if($tipo_planilla==4){//Fecha unica
                $fecha_pago_planilla  = SQL::obtenerValor("fechas_planillas","fecha","codigo_planilla='".$planilla."' AND fecha BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."'");
                $periodo=9;
            }
            ///////////////////////// Fin del calculo de la fecha de pago y del periodo /////////////////////////
            /////////////////////////////////////////////////////////////////////////////////////////////////////

            if(!empty($forma_autorizaHoraExtra)){
                $filasQueAutorizaExtra = explode(",",$forma_autorizaHoraExtra);
                // Observo si la fila tiene la marca de que recibe extra
                for($i=0;$i < count($filasQueAutorizaExtra);$i++){
                    if((int)$filasQueAutorizaExtra[$i] === (int) $posicion && $filasQueAutorizaExtra[$i]!=""){
                        $codigo_transaccion = $forma_codigo_transaccion_tiempo[$posicion];
                        break;
                    }else{
                        // Si no esta marcada como extra se va con la transaccion de hora normal del empleado
                        $codigo_transaccion = $datos->codigo_transaccion_normales;
                    }
                    // Si esta marcada como autorizada pero la transaccion es 0 no debe permitir grabar
                    if($codigo_transaccion=='0'){
                        $continuar = false;
                    }
                }
            }else{
                // Si no esta marcada como extra se va con la transaccion de hora normal del empleado
                $codigo_transaccion = $forma_codigo_transaccion_tiempo[$posicion];
                if($codigo_transaccion != 0){
                    $codigo_transaccion = $datos->codigo_transaccion_normales;
                }else{
                    $continuar = false;
                }
            }

            if($codigo_transaccion == 0){
                $continuar = false;
            }

            if($continuar){

                $tasa                         = SQL::obtenerValor("transacciones_tiempo", "tasa", "codigo = '".$codigo_transaccion."'");
                $codigo_transaccion_contable  = SQL::obtenerValor("transacciones_tiempo", "codigo_transaccion_contable", "codigo = '".$codigo_transaccion."'");
                $codigo_contable              = SQL::obtenerValor("transacciones_contables_empleado", "codigo_contable","codigo = '".$codigo_transaccion_contable."'");
                $sentido                      = SQL::obtenerValor("transacciones_contables_empleado", "sentido","codigo = '".$codigo_transaccion_contable."'");

                $total_valor_minutos          = ((int) $forma_minutos[$posicion]) * $valor_minuto;
                $valor_movimiento             = $total_valor_minutos+(($total_valor_minutos*$tasa)/100);
                $valor_movimiento             = round($valor_movimiento);
                $valor_hora                   = round($valor_hora);

                $datos_registro = array (
                    "fecha_generacion"             => $fecha_actual,
                    "codigo_empresa"               => $empresa,
                    "documento_identidad_empleado" => $forma_documento_identidad_empleado,
                    "fecha_ingreso"                => $fecha_ingreso,
                    "codigo_sucursal"              => $forma_codigo_sucursal,
                    "fecha_ingreso_sucursal"       => $fecha_ingeso_sucursal,
                    "consecutivo"                  => $consecutivo,
                    "codigo_planilla"              => $codigo_planilla,
                    "fecha_inicio"                 => $forma_fecha_inicio[$posicion],
                    "hora_inicio"                  => $forma_hora_inicio[$posicion],
                    "fecha_fin"                    => $forma_fecha_fin[$posicion],
                    "hora_fin"                     => $forma_hora_fin[$posicion],
                    "codigo_transaccion_tiempo"    => $codigo_transaccion,
                    "codigo_transaccion_contable"  => $codigo_transaccion_contable,
                    "codigo_contable"              => $codigo_contable,
                    "tasa"                         => $tasa,
                    "sentido"                      => $sentido,
                    "ano_generacion"               => $ano_generacion ,
                    "mes_generacion"               => $mes_generacion ,
                    "fecha_pago_planilla"          => $fecha_pago_planilla,
                    "codigo_empresa_auxiliar"      => $empresa,
                    "codigo_anexo_contable"        => $forma_anexos_contables,
                    "codigo_auxiliar_contable"     => $forma_auxiliares_contables,
                    "periodo_pago"                 => $periodo,
                    "cantidad_horas"               => $forma_horas[$posicion],
                    "cantidad_minutos"             => $forma_minutos[$posicion],
                    "valor_hora_salario"           => $valor_hora,
                    "valor_hora_recargo"           => $valor_hora,
                    "valor_movimiento"             => $valor_movimiento,
                    "contabilizado"                => 0,
                    "codigo_usuario_registra"      => $sesion_codigo_usuario,
                );
                $insertar = SQL::insertar("movimiento_tiempos_laborados", $datos_registro);

                if(!$insertar){
                    $error   = true;
                    $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
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

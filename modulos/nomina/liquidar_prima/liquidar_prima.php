<?php
/**
*
* Copyright (C) 2020 Jobdaily
* Walter Andrés Márquez Gutiérrez <walteramg@gmail.com>
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

// Generar el formulario para la captura de datos
 if(isset($url_recargarTipoPlanilla)){
    $tipo_planilla = SQL::obtenerValor("planillas","periodo_pago","codigo='".$url_codigo_planilla."'");
    HTTP::enviarJSON($tipo_planilla);
}

if (!empty($url_recargar) && !empty($url_codigo_planilla) && !empty($url_ano_generacion) && !empty($url_mes_generacion) ) {

    $tipo_planilla = SQL::obtenerValor("planillas","periodo_pago","codigo='".$url_codigo_planilla."'");

    $periodo = "";
    if ($tipo_planilla == '1'){
        $periodo = array(
            "1" => $textos["MENSUAL"],
        );
    } else if($tipo_planilla == '2') {
        $periodo = array(
            "2" => $textos["PRIMERA_QUINCENA"],
            "3" => $textos["SEGUNDA_QUINCENA"],
        );
    } else if($tipo_planilla == '3'){
        $periodo = array(
            "4" => $textos["PRIMERA_SEMANA"],
            "5" => $textos["SEGUNDA_SEMANA"],
            "6" => $textos["TERCERA_SEMANA"],
            "7" => $textos["CUARTA_SEMANA"],
            "8" => $textos["QUINTA_SEMANA"]
        );
    } else {
        $periodo = array(
            "9" => $textos["FECHA_UNICA"]
        );
    }

    if ($url_mes_generacion == 2){
        if (($url_ano_generacion % 4 ==0) && ($url_ano_generacion % 100 !=0 || $url_ano_generacion % 400 == 0)){
            $dia_fin = 29;
        } else {
            $dia_fin = 28;
        }
    } else {
        $dia_fin = 31;
    }

    $fecha_inicio = $url_ano_generacion."-".$url_mes_generacion."-01";
    $fecha_fin    = $url_ano_generacion."-".$url_mes_generacion."-".$dia_fin;

    $respuesta = HTML::generarDatosLista("fechas_planillas", "fecha", "fecha", "codigo_planilla='".$url_codigo_planilla."' AND (fecha BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."')");
    $fechas    = SQL::seleccionar(array("fechas_planillas"),array("fecha"),"codigo_planilla='".$url_codigo_planilla."' AND (fecha BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."')");

    if (isset($url_periodo) && SQL::filasDevueltas($fechas)){
        $respuesta = $periodo;
    }

    HTTP::enviarJSON($respuesta);

}

if (!empty($url_generar)) {

    $error     = "";
    $titulo    = $componente->nombre;
    $id_modulo = SQL::obtenerValor("componentes","id_modulo","id='".$componente->id."'");

    $empresa = SQL::obtenerValor("sucursales","codigo_empresa","codigo='".$sesion_sucursal."' AND codigo != 0");

    if ($sesion_usuario == $datosGlobales["usuarioMaestro"]){
        $consulta_sucursales = SQL::seleccionar(array("sucursales"), array("codigo, nombre"), "codigo_empresa = '".$empresa."'");
    } else {
        // Obtener lista de sucursales para seleccion
        $tablas    = array("a" => "perfiles_usuario","b" => "componentes_usuario","c" => "sucursales");
        $columnas  = array("codigo" => "c.codigo","nombre" => "c.nombre");
        $condicion = "c.codigo = a.codigo_sucursal AND a.id = b.id_perfil AND a.codigo_usuario = '".$sesion_codigo_usuario."'
        AND b.id_componente = '".$componente->id."' AND c.codigo_empresa = '".$empresa."'";

        $consulta_sucursales = SQL::seleccionar($tablas, $columnas, $condicion);
    }

    $error_sucursales = false;
    if (SQL::filasDevueltas($consulta_sucursales)){

        $pestana_sucursales   = array();
        $pestana_sucursales[] = array(HTML::boton("botonSeleccionarTodos", $textos["SELECCIONAR_TODOS"], "seleccionar_todas_sucursales();", "", array()));

        while ($datos_sucursales = SQL::filaEnObjeto($consulta_sucursales)){

            $codigo_sucursal = $datos_sucursales->codigo;
            $nombreSucursal  = $datos_sucursales->nombre;

            $pestana_sucursales[]   = array(
                HTML::marcaChequeo("sucursales[".$datos_sucursales->codigo."]", $datos_sucursales->nombre, $datos_sucursales->codigo, false, array("title" => $textos["AYUDA_SUCURSAL"], "id" => "sucursales_".$datos_sucursales->codigo, "class" => "sucursales_electrodomesticos"))
            );
        }
    } else {
        $error_sucursales = true;
    }

    $consulta_planillas = SQL::seleccionar(array("planillas"),array("*"),"codigo>0");
    $error_planillas = false;
    if (SQL::filasDevueltas($consulta_planillas)){

        $planillas[0] = '';

        while ($datos_planilla = SQL::filaEnObjeto($consulta_planillas)){
            $planillas[$datos_planilla->codigo] = $datos_planilla->descripcion;
        }

    } else {
        $error_planillas = true;
    }

    $consulta_fechas_planillas = SQL::seleccionar(array("fechas_planillas"),array("*"),"codigo_planilla>0");
    $error_fechas_planilla = false;
    if (SQL::filasDevueltas($consulta_fechas_planillas)){

        while ($datos_fechas_planillas = SQL::filaEnObjeto($consulta_fechas_planillas)){
            $fechas_planillas[$datos_fechas_planillas->codigo_planilla."|".$datos_fechas_planillas->fecha] = $datos_fechas_planillas->fecha;
        }
    } else {
        $error_fechas_planilla = true;
    }

    $consulta_empleados = SQL::seleccionar(array("ingreso_empleados"),array("*"),"documento_identidad_empleado!=''");
    $error_empleados = false;
    if (!SQL::filasDevueltas($consulta_empleados)){
        $error_empleados = true;
    }

    if (!$error_sucursales && !$error_planillas && !$error_fechas_planilla && !$error_empleados){

        $ano          = date("Y");
        $ano_planilla = array();
        for ($i=0;$i<=1;$i++){
            $ano_planilla[$ano] = $ano;
            $ano++;
        }
        $ano = date("Y");
        $mes = date("m");

        $meses = array(
            "01" => $textos["ENERO"],
            "02" => $textos["FEBRERO"],
            "03" => $textos["MARZO"],
            "04" => $textos["ABRIL"],
            "05" => $textos["MAYO"],
            "06" => $textos["JUNIO"],
            "07" => $textos["JULIO"],
            "08" => $textos["AGOSTO"],
            "09" => $textos["SEPTIEMBRE"],
            "10" => $textos["OCTUBRE"],
            "11" => $textos["NOVIEMBRE"],
            "12" => $textos["DICIEMBRE"]
        );

        $formularios["PESTANA_SUCURSALES"] = $pestana_sucursales;

        $fecha_actual = date("Y-m");
        $fecha_actual = explode("-",$fecha_actual);
        $ano_actual   = $fecha_actual[0];
        $mes_actual   = (int)$fecha_actual[1];
        if ($mes_actual < 7){
            $fecha_inicial = $ano_actual."/01/01";
            $fecha_final   = $ano_actual."/06/30";
        } else {
            $fecha_inicial = $ano_actual."/07/01";
            $fecha_final   = $ano_actual."/12/30";
        }

        $formularios["PESTANA_BASICA"] = array(
            array(
                HTML::listaSeleccionSimple("*ano_generacion", $textos["ANO_PLANILLA"],$ano_planilla,$ano, array("title" => $textos["AYUDA_ANO_PLANILLA"], "class" => "selectorFecha", "onchange"=>"cargarFechaPago2();")),
                HTML::listaSeleccionSimple("*mes_generacion", $textos["MES_PLANILLA"],$meses,$mes, array("title" => $textos["AYUDA_MES_PLANILLA"], "class" => "selectorFecha", "onchange"=>"cargarFechaPago2();"))
            ),
            array(
                HTML::mostrarDato("datos_planilla",$textos["DATOS_PLANILLA"],"")
               .HTML::campoOculto("id_modulo", $id_modulo)
            ),
            array(
                HTML::listaSeleccionSimple("codigo_planilla",$textos["PLANILLA"],$planillas,"",array("title"=>$textos["AYUDA_PLANILLA"], "onchange"=>"cargarFechaPago2();"))
            ),
            array(
                HTML::listaSeleccionSimple("fecha_pago",$textos["FECHA_PAGO"], "","",array("title"=>$textos["AYUDA_FECHA_PAGO"],"class"=>"fecha_pago","onclick" => "determinarPeriodo();")),
                HTML::mostrarDato("nombre_periodo",$textos["PERIODO"],"")
            ),
            array(
                HTML::campoTextoCorto("*fecha_liquidacion", $textos["FECHA_LIQUIDACION"], 20, 20, $fecha_inicial." - ".$fecha_final, array("title" => $textos["AYUDA_FECHA_LIQUIDACION"], "class" => "fechaRango")),
            ),
            array(
                HTML::campoTextoCorto("*fecha_promedios", $textos["FECHA_PROMEDIOS"], 20, 20, $fecha_inicial." - ".$fecha_final, array("title" => $textos["AYUDA_FECHA_PROMEDIOS"], "class" => "fechaRango")),
            ),
            array(
                HTML::marcaChequeo("ultimo_salario", $textos["ULTIMO_SALARIO"], 1, true, array("title" => $textos["AYUDA_ULTIMO_SALARIO"]))
            ),
            array(
                HTML::contenedor(
                    HTML::marcaChequeo("todas_planillas", $textos["TODOS_EMPLEADOS"], 1, false, array("title" => $textos["AYUDA_TODOS_EMPLEADOS"], "class"=>"todas_planillas")),
                    array("id"=>"planillas_fecha_unica","class"=>"oculto")
                )
            ),
            array(
                HTML::campoOculto("periodo","").
                HTML::campoOculto("mensual",$textos["MENSUAL"]).
                HTML::campoOculto("primera_quincena",$textos["PRIMERA_QUINCENA"]).
                HTML::campoOculto("segunda_quincena",$textos["SEGUNDA_QUINCENA"]).
                HTML::campoOculto("fecha_unica",$textos["FECHA_UNICA"])
            )
        );

        // Definicion de botones
        $botones = array(HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar"));

        $contenido = HTML::generarPestanas($formularios, $botones);
    } else {

        $contenido = "";
        $error     = $textos["VERIFICAR_DATOS"];
        if ($error_sucursales){
            $error .= $textos["ERROR_SUCURSALES"];
        }
        if($error_planillas){
            $error .= $textos["ERROR_PLANILLAS"];
        }
        if($error_fechas_planilla){
            $error .= $textos["ERROR_FECHAS_PLANILLAS"];
        }
        if($error_empleados){
            $error .= $textos["ERROR_EMPLEADOS"];
        }
    }

    // Enviar datos para la generacion del formulario al script que origino la peticion
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

} elseif (!empty($forma_procesar)) {

    $periodo = array(
        "1" => $textos["MENSUAL"],
        "2" => $textos["PRIMERA_QUINCENA"],
        "3" => $textos["SEGUNDA_QUINCENA"],
        "4" => $textos["PRIMER_SEMANA"],
        "5" => $textos["SEGUNDA_SEMANA"],
        "6" => $textos["TERCERA_SEMANA"],
        "7" => $textos["CUARTA_SEMANA"],
        "8" => $textos["QUINTA_SEMANA"],
        "9" => $textos["FECHA_UNICA"]
    );

    // Asumir por defecto que no hubo error
    $error = false;

    if (!isset($forma_sucursales)){
        $error   = true;
        $mensaje = $textos["ERROR_SUCURSAL_VACIA"];

    } else if (empty($forma_codigo_planilla)){
        $error   = true;
        $mensaje = $textos["ERROR_CODIGO_PLANILLA"];

    } else if (empty($forma_fecha_pago)){
        $error   = true;
        $mensaje = $textos["ERROR_FECHA_PAGO"];

    } else if (empty($forma_periodo)){
        $error   = true;
        $mensaje = $textos["ERROR_PERIODO"];

    } else if (empty($forma_fecha_liquidacion)){
        $error   = true;
        $mensaje = $textos["ERROR_FECHA_LIQUIDACION"];

    } else if (empty($forma_fecha_promedios)){
        $error   = true;
        $mensaje = $textos["ERROR_FECHA_PROMEDIOS"];

    } else {

        $mensaje = $textos["EXITO_LIQUIDACION"].$periodo[$forma_periodo].$textos["EXITO_LIQUIDACION2"].$forma_fecha_pago;

        if ($forma_mes_generacion == 2){
            if (($forma_ano_generacion % 4 ==0) && ($forma_ano_generacion % 100 !=0 || $forma_ano_generacion % 400 == 0)){
                $dia_fin = 29;
            } else {
                $dia_fin = 28;
            }
        } else {
                $dia_fin = 30;
        }

        $tipo_planilla = SQL::obtenerValor("planillas","periodo_pago","codigo='$forma_codigo_planilla'");

        $ano_generacion = $forma_ano_generacion;
        $mes_generacion = $forma_mes_generacion;

        $contadorNO = 0;
        $mensajeS   = $textos["ERROR_PERIODO1"];
        $mensajeS  .= $textos["ERROR_PERIODO2"];

        foreach($forma_sucursales as $codigo_sucursal){
            $consulta = SQL::obtenerValor("periodos_contables","estado","codigo_sucursal='$codigo_sucursal' AND ('$forma_fecha_pago' BETWEEN fecha_inicio AND fecha_fin) AND id_modulo='$forma_id_modulo' AND estado='1'");

            if(!$consulta){
                $nombreSucursal = SQL::obtenerValor("sucursales","nombre","codigo='".$codigo_sucursal."'");
                $mensajeS.="- ".$nombreSucursal."\n";
                $contadorNO+=1;
            }
        }

        if($contadorNO>0){
            $error   = true;
            $mensaje = $mensajeS;
        }else{

            $salario_minimo = SQL::obtenerValor("salario_minimo","valor","fecha<='$forma_fecha_pago' ORDER BY fecha DESC LIMIT 0,1");
            if (!$salario_minimo){
                $salario_minimo=0;
            }
            $valor_auxilio  = SQL::obtenerValor("auxilio_transporte","valor","fecha<='$forma_fecha_pago' ORDER BY fecha DESC LIMIT 0,1");
            if (!$valor_auxilio){
                $valor_auxilio=0;
            }

            $id_empleados = array();//Array que lleva los ids de los empleados
            $fecha_pago     = explode("-",$forma_fecha_pago);
            $ano_generacion = $fecha_pago[0];
            $mes_generacion = $fecha_pago[1];

            $fecha_compuesta_liquidacion = explode(" - ",$forma_fecha_liquidacion);
            $fecha_inicio_liquidacion = $fecha_compuesta_liquidacion[0];
            $fecha_inicio_liquidacion = str_replace("/","-",$fecha_inicio_liquidacion);
            $arreglo                  = explode("-",$fecha_inicio_liquidacion);
            $ano_inicio               = (int)$arreglo[0];
            $mes_inicio               = (int)$arreglo[1];
            $dia_inicio               = (int)$arreglo[2];
            $fecha_fin_liquidacion    = $fecha_compuesta_liquidacion[1];
            $fecha_fin_liquidacion    = str_replace("/","-",$fecha_fin_liquidacion);
            $arreglo                  = explode("-",$fecha_fin_liquidacion);
            $ano_fin                  = (int)$arreglo[0];
            $mes_fin                  = (int)$arreglo[1];
            $dia_fin                  = (int)$arreglo[2];

            $dias_trabajados = (((($ano_fin * 360)+($mes_fin*30)+$dia_fin) - (($ano_inicio * 360)+($mes_inicio*30)+$dia_inicio)) + 1);

            $fecha_compuesta_promedios = explode(" - ",$forma_fecha_promedios);
            $fecha_inicio_promedio = $fecha_compuesta_promedios[0];
            $fecha_inicio_promedio = str_replace("/","-",$fecha_inicio_promedio);
            $arreglo                  = explode("-",$fecha_inicio_promedio);
            $ano_inicio_promedio      = (int)$arreglo[0];
            $mes_inicio_promedio      = (int)$arreglo[1];
            $dia_inicio_promedio      = (int)$arreglo[2];
            $fecha_fin_promedio       = $fecha_compuesta_promedios[1];
            $fecha_fin_promedio       = str_replace("/","-",$fecha_fin_promedio);
            $arreglo                  = explode("-",$fecha_fin_promedio);
            $ano_fin_promedio         = (int)$arreglo[0];
            $mes_fin_promedio         = (int)$arreglo[1];
            $dia_fin_promedio         = (int)$arreglo[2];

            $dias_liquidacion_promedio = (((($ano_fin_promedio * 360)+($mes_fin_promedio*30)+$dia_fin_promedio) - (($ano_inicio_promedio * 360)+($mes_inicio_promedio*30)+$dia_inicio_promedio)) + 1);

            foreach ($forma_sucursales AS $codigo_sucursal) {

                //RECORRER LOS MOVIMIENTOS DE SALARIOS PARA SUMAR EN EL IBC DE SALUD

                $condicion = "codigo_sucursal ='$codigo_sucursal' AND tipo_salario!='2' AND tipo_salario!='4'";
                $condicion .=" AND (fecha_ingreso_sucursal<='$forma_fecha_pago'";
                $condicion .=" AND ((fecha_retiro_sucursal !='0000-00-00' AND fecha_retiro_sucursal>='$forma_fecha_pago')";
                $condicion .= " OR fecha_retiro_sucursal='0000-00-00')";
                $condicion .=" ) AND fecha_salario <='$forma_fecha_pago' AND estado='1'";
                if (!isset($forma_todas_planillas)){
                    $condicion .=" AND codigo_planilla ='$forma_codigo_planilla'";
                }
                $consulta_sucursal_contrato = SQL::seleccionar(array("consulta_contrato_empleado"),array("*"),$condicion,"","fecha_ingreso_sucursal DESC, fecha_salario DESC");

                if (SQL::filasDevueltas($consulta_sucursal_contrato)){

                    while($datos_ingreso = SQL::filaEnObjeto($consulta_sucursal_contrato)){

                        if (!isset($empleado[$datos_ingreso->documento_identidad_empleado])){

                            $empleado[$datos_ingreso->documento_identidad_empleado] = $datos_ingreso->documento_identidad_empleado;
                            $salario[$datos_ingreso->documento_identidad_empleado] = $datos_ingreso->salario;
                            $fecha_ingreso[$datos_ingreso->documento_identidad_empleado] = $datos_ingreso->fecha_ingreso;
                            $fecha_ingreso_sucursal[$datos_ingreso->documento_identidad_empleado] = $datos_ingreso->fecha_ingreso_sucursal;
                            $codigo_empresa[$datos_ingreso->documento_identidad_empleado] = $datos_ingreso->codigo_empresa;
                            $fecha_salario[$datos_ingreso->documento_identidad_empleado] = $datos_ingreso->fecha_salario;
                            $departamento[$datos_ingreso->documento_identidad_empleado] = $datos_ingreso->codigo_departamento_empresa;

                            if ($datos_ingreso->manejo_auxilio_transporte != '5'){
                                $auxilio[$datos_ingreso->documento_identidad_empleado] = $valor_auxilio;
                            } else {
                                $auxilio[$datos_ingreso->documento_identidad_empleado] = 0;
                            }

                            if (!isset($codigo_gasto[$datos_ingreso->codigo_departamento_empresa])){

                                $codigo_gasto[$datos_ingreso->codigo_departamento_empresa] = SQL::obtenerValor("departamentos_empresa","codigo_gasto","codigo='$datos_ingreso->codigo_departamento_empresa'");
                                $codigo_gasto_consulta = $codigo_gasto[$datos_ingreso->codigo_departamento_empresa];
                                $codigo_transaccion_contable[$datos_ingreso->codigo_departamento_empresa] = SQL::obtenerValor("gastos_prestaciones_sociales","prima_pago_gasto","codigo='$codigo_gasto_consulta '");
                                $codigo_transaccion_contable_consulta = $codigo_transaccion_contable[$datos_ingreso->codigo_departamento_empresa];
                                $codigo_contable[$datos_ingreso->codigo_departamento_empresa] = SQL::obtenerValor("transacciones_contables_empleado","codigo_contable","codigo='$codigo_transaccion_contable_consulta'");
                                $sentido[$datos_ingreso->codigo_departamento_empresa] = SQL::obtenerValor("transacciones_contables_empleado","sentido","codigo='$codigo_transaccion_contable_consulta'");
                            }

                        }
                    }
                }

                if (isset($empleado)){

                    $condicion = "ano_generacion = '$ano_generacion' AND mes_generacion='$mes_generacion' AND codigo_planilla='$forma_codigo_planilla' AND periodo_pago='$forma_periodo' AND fecha_pago_planilla='$forma_fecha_pago'";

                    $eliminar = SQL::eliminar("movimientos_prima",$condicion);

                    $condicion_empleado = " AND documento_identidad_empleado IN (".implode(",", $empleado).")";
                    $condicion    = "fecha_pago_planilla BETWEEN '$fecha_inicio_promedio' AND '$fecha_fin_promedio' AND contabilizado != '1' $condicion_empleado";
                    $condicion    .= " AND (acumula_prima='1' OR resta_prima='1')";
                    $columnas     = array("documento_identidad_empleado,SUM(valor_movimiento) AS valor_movimiento","sentido");
                    $ordenamiento = "documento_identidad_empleado,sentido";
                    $agrupamiento = "documento_identidad_empleado,sentido";

                    $consulta = SQL::seleccionar(array("consulta_datos_planilla"),$columnas,$condicion,$agrupamiento,$ordenamiento);

                    if (SQL::filasdevueltas($consulta)){

                        $documento_identidad_anterior = "";
                        $valor_promedio               = 0;
                        while($datos=SQL::filaEnObjeto($consulta)){

                            if ($documento_identidad_anterior!=$datos->documento_identidad_empleado && $documento_identidad_anterior!=""){

                                $condicion      = "fecha_pago_planilla BETWEEN '$fecha_inicio_promedio' AND '$fecha_fin_promedio' AND contabilizado != '1'";
                                $condicion      .= " AND documento_identidad_empleado='$documento_identidad_anterior' AND resta_prima='1'";
                                $columnas       = array("COUNT(resta_salario) AS dias_descontar");
                                $dias_descontar = 0;

                                $consulta_resta_salario = SQL::seleccionar(array("consulta_datos_planilla"),$columnas,$condicion);

                                if(SQL::filasDevueltas($consulta_resta_salario)){
                                    while($datos_resta_salario=SQL::filaEnObjeto($consulta_resta_salario)){
                                        $dias_descontar = $datos_resta_salario->dias_descontar;
                                    }
                                }

                                $dias_trabajados = (((($ano_fin * 360)+($mes_fin*30)+$dia_fin) - (($ano_inicio * 360)+($mes_inicio*30)+$dia_inicio)) + 1);
                                if ($fecha_ingreso[$documento_identidad_anterior] > $fecha_inicio_liquidacion){

                                    $arreglo            = explode("-",$fecha_ingreso[$documento_identidad_anterior]);
                                    $ano_inicio_ingreso = (int)$arreglo[0];
                                    $mes_inicio_ingreso = (int)$arreglo[1];
                                    $dia_inicio_ingreso = (int)$arreglo[2];

                                    $dias_trabajados = (((($ano_fin * 360)+($mes_fin*30)+$dia_fin) - (($ano_inicio_ingreso * 360)+($mes_inicio_ingreso*30)+$dia_inicio_ingreso)) + 1);

                                }

                                if (
                                    $fecha_salario[$documento_identidad_anterior] > $fecha_ingreso[$documento_identidad_anterior] &&
                                    $fecha_salario[$documento_identidad_anterior] > $fecha_inicio_liquidacion &&
                                    !isset($forma_ultimo_salario)
                                    ){

                                    $arreglo            = explode("-",$fecha_salario[$documento_identidad_anterior]);
                                    $ano_inicio_salario = (int)$arreglo[0];
                                    $mes_inicio_salario = (int)$arreglo[1];
                                    $dia_inicio_salario = (int)$arreglo[2];

                                    $dias_salario = (((($ano_fin * 360)+($mes_fin*30)+$dia_fin) - (($ano_inicio_salario * 360)+($mes_inicio_salario*30)+$dia_inicio_salario)) + 1);

                                    if ($dias_salario > 0 && $dias_salario < 90){

                                        $condicion_salario = "codigo_empresa ='$codigo_empresa[$documento_identidad_anterior]' AND documento_identidad_empleado='$documento_identidad_anterior'";
                                        $condicion_salario .=" AND fecha_ingreso<='$fecha_ingreso[$documento_identidad_anterior]' AND fecha_ingreso_sucursal<='$forma_fecha_pago'";
                                        $condicion_salario .=" AND fecha_salario BETWEEN '$fecha_inicio_liquidacion' AND '$fecha_fin_liquidacion'";
                                        $consulta_salario  = SQL::seleccionar(array("consulta_contrato_empleado"),array("SUM(salario) AS salario","COUNT(salario) AS cantidad","fecha_salario"),$condicion_salario);

                                        if (SQL::filasDevueltas($consulta_salario)){

                                            $datos_salario    = SQL::filaEnObjeto($consulta_salario);
                                            $cantidad         = $datos_salario->cantidad;
                                            $salario_consulta = $datos_salario->salario;

                                            if ($datos_salario->fecha_salario > $fecha_inicio_liquidacion){

                                                $condicion_salario = "codigo_empresa ='$codigo_empresa[$documento_identidad_anterior]' AND documento_identidad_empleado='$documento_identidad_anterior'";
                                                $condicion_salario .=" AND fecha_ingreso<='$fecha_ingreso[$documento_identidad_anterior]' AND fecha_ingreso_sucursal<='$forma_fecha_pago'";
                                                $condicion_salario .=" AND fecha_salario < '$fecha_inicio_liquidacion'";
                                                $consulta_salario  = SQL::seleccionar(array("consulta_contrato_empleado"),array("salario","fecha_salario"),$condicion_salario,"","",0,1);
                                                if (SQL::filasdevueltas($consulta_salario)){
                                                    $datos_salario_anterior = SQL::filaEnObjeto($consulta_salario);
                                                    $salario_anterior       = $datos_salario_anterior->salario;
                                                    $cantidad++;
                                                } else {
                                                    $salario_anterior = 0;
                                                }
                                            }

                                            $salario_liquidacion = (int)(($salario_consulta + $salario_anterior) / $cantidad);
                                        } else {
                                            $salario_liquidacion = $salario[$documento_identidad_anterior];
                                        }
                                    } else {
                                        $salario_liquidacion = $salario[$documento_identidad_anterior];
                                    }
                                } else {
                                    $salario_liquidacion = $salario[$documento_identidad_anterior];
                                }

                                $dias_liquidacion[$documento_identidad_anterior]  = $dias_trabajados - $dias_descontar;
                                if ($dias_trabajados < 30){
                                    $meses_promedio = 1;
                                } else {
                                    $meses_promedio = ($dias_liquidacion_promedio) / 30;
                                }
                                $valor_movimientos[$documento_identidad_anterior] = $valor_promedio/$meses_promedio;
                                $salario_promedio[$documento_identidad_anterior]  = $salario_liquidacion;
                                $valor_base                                       = $salario_liquidacion + $auxilio[$documento_identidad_anterior]+($valor_promedio/$meses_promedio);
                                $valor_prima[$documento_identidad_anterior]       = round(($valor_base * ($dias_trabajados - $dias_descontar))/360);

                                $valor_promedio = 0;
                            }
                            $documento_identidad_anterior = $datos->documento_identidad_empleado;

                            if ($datos->sentido == "D"){
                                $valor_promedio += $datos->valor_movimiento;
                            } else {
                                $valor_promedio -= $datos->valor_movimiento;
                            }
                        }

                        $condicion      = "fecha_pago_planilla BETWEEN '$fecha_inicio_promedio' AND '$fecha_fin_promedio' AND contabilizado != '1'";
                        $condicion      .= " AND documento_identidad_empleado='$documento_identidad_anterior' AND resta_prima='1'";
                        $columnas       = array("COUNT(resta_salario) AS dias_descontar");
                        $dias_descontar = 0;

                        $consulta_resta_salario = SQL::seleccionar(array("consulta_datos_planilla"),$columnas,$condicion);
                        if(SQL::filasDevueltas($consulta_resta_salario)){
                            while($datos_resta_salario=SQL::filaEnObjeto($consulta_resta_salario)){
                                $dias_descontar = $datos_resta_salario->dias_descontar;
                            }
                        }

                        $dias_trabajados = (((($ano_fin * 360)+($mes_fin*30)+$dia_fin) - (($ano_inicio * 360)+($mes_inicio*30)+$dia_inicio)) + 1);
                        if ($fecha_ingreso[$documento_identidad_anterior] > $fecha_inicio_liquidacion){

                            $arreglo            = explode("-",$fecha_ingreso[$documento_identidad_anterior]);
                            $ano_inicio_ingreso = (int)$arreglo[0];
                            $mes_inicio_ingreso = (int)$arreglo[1];
                            $dia_inicio_ingreso = (int)$arreglo[2];

                            $dias_trabajados = (((($ano_fin * 360)+($mes_fin*30)+$dia_fin) - (($ano_inicio_ingreso * 360)+($mes_inicio_ingreso*30)+$dia_inicio_ingreso)) + 1);
                        }

                        if (
                            $fecha_salario[$documento_identidad_anterior] > $fecha_ingreso[$documento_identidad_anterior] &&
                            $fecha_salario[$documento_identidad_anterior] > $fecha_inicio_liquidacion &&
                            !isset($forma_ultimo_salario)
                            ){

                            $arreglo            = explode("-",$fecha_salario[$documento_identidad_anterior]);
                            $ano_inicio_salario = (int)$arreglo[0];
                            $mes_inicio_salario = (int)$arreglo[1];
                            $dia_inicio_salario = (int)$arreglo[2];

                            $dias_salario = (((($ano_fin * 360)+($mes_fin*30)+$dia_fin) - (($ano_inicio_salario * 360)+($mes_inicio_salario*30)+$dia_inicio_salario)) + 1);

                            if ($dias_salario > 0 && $dias_salario < 90){

                                $condicion_salario = "codigo_empresa ='$codigo_empresa[$documento_identidad_anterior]' AND documento_identidad_empleado='$documento_identidad_anterior'";
                                $condicion_salario .=" AND fecha_ingreso<='$fecha_ingreso[$documento_identidad_anterior]' AND fecha_ingreso_sucursal<='$forma_fecha_pago'";
                                $condicion_salario .=" AND fecha_salario BETWEEN '$fecha_inicio_liquidacion' AND '$fecha_fin_liquidacion'";
                                $consulta_salario  = SQL::seleccionar(array("consulta_contrato_empleado"),array("SUM(salario) AS salario","COUNT(salario) AS cantidad","fecha_salario"),$condicion_salario);

                                if (SQL::filasDevueltas($consulta_salario)){

                                    $datos_salario    = SQL::filaEnObjeto($consulta_salario);
                                    $cantidad         = $datos_salario->cantidad;
                                    $salario_consulta = $datos_salario->salario;

                                    if ($datos_salario->fecha_salario > $fecha_inicio_liquidacion){

                                        $condicion_salario = "codigo_empresa ='$codigo_empresa[$documento_identidad_anterior]' AND documento_identidad_empleado='$documento_identidad_anterior'";
                                        $condicion_salario .=" AND fecha_ingreso<='$fecha_ingreso[$documento_identidad_anterior]' AND fecha_ingreso_sucursal<='$forma_fecha_pago'";
                                        $condicion_salario .=" AND fecha_salario < '$fecha_inicio_liquidacion'";
                                        $consulta_salario  = SQL::seleccionar(array("consulta_contrato_empleado"),array("salario","fecha_salario"),$condicion_salario,"","",0,1);
                                        if (SQL::filasdevueltas($consulta_salario)){
                                            $datos_salario_anterior = SQL::filaEnObjeto($consulta_salario);
                                            $salario_anterior       = $datos_salario_anterior->salario;
                                            $cantidad++;
                                        } else {
                                            $salario_anterior = 0;
                                        }
                                    }

                                    $salario_liquidacion = (int)(($salario_consulta + $salario_anterior) / $cantidad);
                                } else {
                                    $salario_liquidacion = $salario[$documento_identidad_anterior];
                                }
                            } else {
                                $salario_liquidacion = $salario[$documento_identidad_anterior];
                            }
                        } else {
                            $salario_liquidacion = $salario[$documento_identidad_anterior];
                        }

                        $dias_liquidacion[$documento_identidad_anterior]  = $dias_trabajados - $dias_descontar;
                        if ($dias_trabajados < 30){
                            $meses_promedio = 1;
                        } else {
                            $meses_promedio = ($dias_liquidacion_promedio) / 30;
                        }
                        $meses_promedio                                   = ($dias_liquidacion_promedio) / 30;
                        $valor_movimientos[$documento_identidad_anterior] = $valor_promedio/$meses_promedio;
                        $salario_promedio[$documento_identidad_anterior]  = $salario_liquidacion;
                        $valor_base                                       = $salario_liquidacion + $auxilio[$documento_identidad_anterior]+($valor_promedio/$meses_promedio);
                        $valor_prima[$documento_identidad_anterior]       = round((($valor_base * ($dias_trabajados - $dias_descontar))/360));
                    }

                    foreach($empleado AS $documento_identidad_empleado){

                        if (isset($valor_prima[$documento_identidad_empleado])){

                            $salario_tabla           = (int)$salario_promedio[$documento_identidad_empleado];
                            $valor_movimientos_tabla = (int)$valor_movimientos[$documento_identidad_empleado];
                            $valor_prima_tabla       = $valor_prima[$documento_identidad_empleado];
                            $dias_liquidacion_tabla  = $dias_liquidacion[$documento_identidad_empleado];

                        } else {

                            $dias_liquidacion_tabla = (((($ano_fin * 360)+($mes_fin*30)+$dia_fin) - (($ano_inicio * 360)+($mes_inicio*30)+$dia_inicio)) + 1);

                            $dias_liquidacion_tabla = 180;
                            if ($fecha_ingreso[$documento_identidad_empleado] > $fecha_inicio_liquidacion){

                                $arreglo            = explode("-",$fecha_ingreso[$documento_identidad_empleado]);
                                $ano_inicio_ingreso = (int)$arreglo[0];
                                $mes_inicio_ingreso = (int)$arreglo[1];
                                $dia_inicio_ingreso = (int)$arreglo[2];

                                $dias_liquidacion_tabla = (((($ano_fin * 360)+($mes_fin*30)+$dia_fin) - (($ano_inicio_ingreso * 360)+($mes_inicio_ingreso*30)+$dia_inicio_ingreso)) + 1);
                            }

                            if (
                                $fecha_salario[$documento_identidad_empleado] > $fecha_ingreso[$documento_identidad_empleado] &&
                                $fecha_salario[$documento_identidad_empleado] > $fecha_inicio_liquidacion &&
                                !isset($forma_ultimo_salario)
                                ){

                                $arreglo            = explode("-",$fecha_salario[$documento_identidad_empleado]);
                                $ano_inicio_salario = (int)$arreglo[0];
                                $mes_inicio_salario = (int)$arreglo[1];
                                $dia_inicio_salario = (int)$arreglo[2];

                                $dias_salario = (((($ano_fin * 360)+($mes_fin*30)+$dia_fin) - (($ano_inicio_salario * 360)+($mes_inicio_salario*30)+$dia_inicio_salario)) + 1);


                                if ($dias_salario > 0 && $dias_salario < 90){

                                    $condicion_salario = "codigo_empresa ='$codigo_empresa[$documento_identidad_empleado]' AND documento_identidad_empleado='$documento_identidad_empleado'";
                                    $condicion_salario .=" AND fecha_ingreso<='$fecha_ingreso[$documento_identidad_empleado]' AND fecha_ingreso_sucursal<='$forma_fecha_pago'";
                                    $condicion_salario .=" AND fecha_salario BETWEEN '$fecha_inicio_liquidacion' AND '$fecha_fin_liquidacion'";

                                    $consulta_salario  = SQL::seleccionar(array("consulta_contrato_empleado"),array("SUM(salario) AS salario","COUNT(salario) AS cantidad", "fecha_salario"),$condicion_salario);

                                    if (SQL::filasDevueltas($consulta_salario)){

                                        $datos_salario    = SQL::filaEnObjeto($consulta_salario);
                                        $cantidad         = $datos_salario->cantidad;
                                        $salario_consulta = $datos_salario->salario;

                                        if ($datos_salario->fecha_salario > $fecha_inicio_liquidacion){

                                            $condicion_salario = "codigo_empresa ='$codigo_empresa[$documento_identidad_empleado]' AND documento_identidad_empleado='$documento_identidad_empleado'";
                                            $condicion_salario .=" AND fecha_ingreso<='$fecha_ingreso[$documento_identidad_empleado]' AND fecha_ingreso_sucursal<='$forma_fecha_pago'";
                                            $condicion_salario .=" AND fecha_salario < '$fecha_inicio_liquidacion'";
                                            $consulta_salario  = SQL::seleccionar(array("consulta_contrato_empleado"),array("salario","fecha_salario"),$condicion_salario,"","",0,1);
                                            if (SQL::filasdevueltas($consulta_salario)){
                                                $datos_salario_anterior = SQL::filaEnObjeto($consulta_salario);
                                                $salario_anterior       = $datos_salario_anterior->salario;
                                                $cantidad++;
                                            } else {
                                                $salario_anterior = 0;
                                            }
                                        }

                                        $datos_salario = SQL::filaEnObjeto($consulta_sucursal_contrato);
                                        $salario_tabla = (int)(($salario_consulta + $salario_anterior)/ $datos_salario->cantidad);
                                    } else {
                                        $salario_tabla = $salario[$documento_identidad_empleado];
                                    }
                                } else {
                                    $salario_tabla = $salario[$documento_identidad_empleado];
                                }
                            } else {
                                $salario_tabla = $salario[$documento_identidad_empleado];
                            }

                            $valor_prima_tabla       = round((($salario_tabla + $auxilio[$documento_identidad_empleado]) * $dias_liquidacion_tabla) / 360);
                            $valor_movimientos_tabla = 0;
                        }

                        $departamento_empleado = $departamento[$documento_identidad_empleado];

                        $datos = array(
                            "ano_generacion"                => $ano_generacion,
                            "mes_generacion"                => $mes_generacion,
                            "codigo_planilla"               => $forma_codigo_planilla,
                            "periodo_pago"                  => $forma_periodo,
                            "codigo_transaccion_contable"   => $codigo_transaccion_contable[$departamento_empleado],
                            "codigo_empresa"                => $codigo_empresa[$documento_identidad_empleado],
                            "documento_identidad_empleado"  => $documento_identidad_empleado,
                            "fecha_ingreso_empresa"         => $fecha_ingreso[$documento_identidad_empleado],
                            "codigo_sucursal"               => $codigo_sucursal,
                            "fecha_ingreso_sucursal"        => $fecha_ingreso_sucursal[$documento_identidad_empleado],
                            "fecha_pago_planilla"           => $forma_fecha_pago,
                            "fecha_inicio_pago"             => $fecha_inicio_liquidacion,
                            "fecha_hasta_pago"              => $fecha_fin_liquidacion,
                            "fecha_inicio_promedio"         => $fecha_inicio_promedio,
                            "fecha_hasta_promedio"          => $fecha_fin_promedio,
                            "codigo_empresa_auxiliar"       => 0,
                            "codigo_anexo_contable"         => "",
                            "codigo_auxiliar_contable"      => 0,
                            "codigo_contable"               => $codigo_contable[$departamento_empleado],
                            "sentido"                       => $sentido[$departamento_empleado],
                            "dias_liquidados"               => $dias_liquidacion_tabla,
                            "dias_promedio"                 => $dias_liquidacion_promedio,
                            "salario"                       => $salario[$documento_identidad_empleado],
                            "salario_promedio"              => $salario_tabla,
                            "auxilio_transporte"            => $auxilio[$documento_identidad_empleado],
                            "valor_ingresos_promedio"       => $valor_movimientos_tabla,
                            "valor_movimiento"              => $valor_prima_tabla,
                            "contabilizado"                 => "0",
                            "codigo_usuario_genera"         => $sesion_codigo_usuario,
                            "fecha_registro"                => date("Y-m-d H:i:S")
                        );

                        $insertar = SQL::insertar("movimientos_prima",$datos);
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

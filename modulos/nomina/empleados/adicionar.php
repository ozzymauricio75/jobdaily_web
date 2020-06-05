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

$MANEJO_SALARIO_MINIMO_MAYOR_2 = array(
    "3" => $textos["PAGELE_MAYOR_2_SALARIO_CON_DESCUENTO"],
    "4" => $textos["PAGELE_MAYOR_2_SALARIO_SIN_DESCUENTO"],
);

$MANEJO_SALARIO_MINIMO_MENOR_2 = array(
    "1" => $textos["PAGUELO_POR_LEY_CON_DESCUENTO"],
    "2" => $textos["PAGUELO_POR_LEY_SIN_DESCUENTO"],
);

$QUINCENA = array(
    "1" => $textos["PROPORCIONAL_QUINCENA"],
    "2" => $textos["SEGUNDA_QUINCENA"],
);
$conceptos_contables= array(
    "salario"            => 1,
    "pension"            => 6,
    "auxilio_transporte" => 3,
    "salud"              => 5
);

if(isset($url_RecargarManejo)){
    $respuesta = array();
    if ($url_id == 0) {
        $respuesta = $MANEJO_SALARIO_MINIMO_MENOR_2;
    } else {
        $respuesta = $MANEJO_SALARIO_MINIMO_MAYOR_2;
    }
    HTTP::enviarJSON($respuesta);
    exit;
}

if(isset($url_completar)){
    if(($url_item) == "selector1"){
        echo SQL::datosAutoCompletar("seleccion_aspirantes", $url_q);
    }if(($url_item) == "selector7"){
        echo SQL::datosAutoCompletar("seleccion_terceros", $url_q);
    }if(($url_item) == "selector8"){
        echo SQL::datosAutoCompletar("seleccion_anexos_contables", $url_q);
    }if(($url_item) == "selector10"){
        echo SQL::datosAutoCompletar("seleccion_entidad_salud", $url_q);
    }if(($url_item) == "selector11"){
        echo SQL::datosAutoCompletar("seleccion_entidad_pension", $url_q);
    }if(($url_item) == "selectorSalud"){
        echo SQL::datosAutoCompletar("seleccion_entidades_parafiscales_salud", $url_q);
    }if(($url_item) == "selectorPension"){
        echo SQL::datosAutoCompletar("seleccion_entidades_parafiscales_pension", $url_q);
    }
    exit;
}

////////Generacion de listado del transacciones contables de acuerdo al anexo del empleado////////
if(isset($url_verificarTransaccion)){
    $condicionIf = $url_item == "selector3" || $url_item == "selector4" || $url_item == "selector5";
    if($condicionIf){

       if($url_anexo!=""){
           //$condicion_extra = " id_codigo_contable in (select codigo_contable from job_plan_contable where codigo_anexo_contable ='$url_anexo')  AND '$url_concepto' in (select codigo_concepto_transaccion_contable from job_transacciones_contables_empleado)";
            $condicion_extra = "";
        }else{
           $condicion_extra = "";
       }
       echo SQL::datosAutoCompletar("seleccion_transacciones_contables_empleado",$url_q,$condicion_extra);
    }
    if($url_item == "selector6"){
        if($url_anexo!=""){
           // $condicion_extra = " id_codigo_contable in (select codigo_contable from job_plan_contable where codigo_anexo_contable ='$url_anexo')";
            $condicion_extra = "";

        }else{
           $condicion_extra = "";
        }
        echo SQL::datosAutoCompletar("seleccion_transacciones_contables_empleado",$url_q,$condicion_extra);
    }
    if(($url_item) == "selector2"){
        if($url_anexo!=""){
           // $condicion_extra = " id_codigo_contable in (select codigo_contable from job_plan_contable where codigo_anexo_contable ='$url_anexo' AND codigo_contable LIKE '5%')"; /// se le agrega como condicion que solo cargue los egresos(gastos)
            $condicion_extra = "";

        }else{
           $condicion_extra = "";
        }

        $condicion_extra = " id_codigo_contable in (select codigo_contable from job_plan_contable where codigo_contable LIKE '5%')";
        echo SQL::datosAutoCompletar("seleccion_transacciones_contables_empleado",$url_q,$condicion_extra);
    }
    exit;
}


if(isset($url_tipo_planilla)){
    $tipo_planilla           = SQL::obtenerValor("planillas", "periodo_pago", "codigo='".$url_id."'");
    $descripcion_transaccion = SQL::obtenerValor("transacciones_contables_empleado", "descripcion", "codigo='".$url_idconceptoContable."'");
    $datos                   = array();
    $datos[0]                = $tipo_planilla;
    $datos[1]                = $descripcion_transaccion;
    HTTP::enviarJSON($datos);
    exit;
}

if(isset($url_termino)){
    $terminio_contrato = SQL::obtenerValor("tipos_contrato", "termino_contrato", "codigo='".$url_id."'");
    $tipo_salario      = SQL::obtenerValor("tipos_contrato", "tipo_contratacion", "codigo='".$url_id."'");
    $tipo_contrato     = array();
    $tipo_contrato[0]  = $terminio_contrato;
    $tipo_contrato[1]  = $tipo_salario;
    HTTP::enviarJSON($tipo_contrato);
    exit;
}
if(isset($url_verificarPlanilla) && isset($url_id_planilla)){
    $periodoPago = SQL::obtenerValor("planillas", "periodo_pago", "codigo = '".$url_id_planilla."'");
    HTTP::enviarJSON($periodoPago);
    exit;
}

if(isset($url_verificarPension)){
    if(isset($url_id_empleado)){
        $existe = SQL::obtenerValor("aspirantes", "pensionado", "documento_identidad = '".$url_id_empleado."'");
        HTTP::enviarJSON($existe);
    }
    exit;
}


if(isset($url_verificarEmpleado) && isset($url_id_empleado)){
    $existe = SQL::existeItem("ingreso_empleados", "documento_identidad_empleado", $url_id_empleado,"estado='1'");
    if ($existe) {
        $mensaje = $textos["ERROR_EXISTE_EMPLEADO"];
        HTTP::enviarJSON($mensaje);
    }
    exit;
}

if(isset($url_verificarAnexos) && isset($url_id_anexos_contable)){
    $lista = "";
    if($url_id_anexos_contable){
        $lista = HTML::generarDatosLista("buscador_auxiliares_contables", "codigo", "descripcion", "codigo_id = '".$url_id_anexos_contable."' AND codigo > '0'");
    }
    HTTP::enviarJSON($lista);
    exit;
}

if(isset($url_verificarRiesgos)){
    $riesgo = SQL::obtenerValor("departamentos_empresa", "riesgos_profesionales", "codigo = '".$url_id_riesgo."'");
    HTTP::enviarJSON($riesgo);
    exit;
}
if(isset($url_verificarParafiscales)){
    if(isset($url_id_empleado)){
        $entidades      = array();
        $entidad_salud  = SQL::obtenerValor("aspirantes", "codigo_entidad_salud", "documento_identidad = '".$url_id_empleado."'");
        $entidad_pesion = SQL::obtenerValor("aspirantes", "codigo_entidad_pension", "documento_identidad = '".$url_id_empleado."'");
        $pensionado     = SQL::obtenerValor("aspirantes", "pensionado", "documento_identidad = '".$url_id_empleado."'");
        $entidades[0]   = $entidad_salud;
        $entidades[1]   = $entidad_pesion;
        $entidades[2]   = $entidad_pesion;
        HTTP::enviarJSON($entidades);
        exit;
    }
}

if(!empty($url_recargar)){
    if($url_elemento == "sucursal_labora"){
        $respuesta = HTML::generarDatosLista("sucursales", "codigo", "nombre", "codigo_empresa = '".$url_origen."'");
    }
    if($url_elemento == "id_seccion"){
        $respuesta = HTML::generarDatosLista("secciones_departamentos", "codigo", "nombre", "codigo_departamento_empresa = '".$url_origen."'");
    }

    if($url_elemento == "id_anexos_contables"){
        $respuesta  = array();
        $consulta = SQL::seleccionar(array("auxiliares_contables,job_anexos_contables"), array("codigo_anexo_contable","job_anexos_contables.descripcion"), "codigo_empresa='".$url_origen."'", "codigo_anexo_contable");
        while($fila = SQL::filaEnArreglo($consulta)){
            $respuesta[$fila[0]] = $fila[1];
        }
    }
    // Contruir toda la condicion apartir del elemento de llegada
    $condicionIf  = $url_elemento == "codigo_transaccion_normales" || $url_elemento == "codigo_transaccion_extras" || $url_elemento == "codigo_transaccion_recargo_nocturno";
    $condicionIf .= $url_elemento == "codigo_transaccion_extras_nocturnas" || $url_elemento == "codigo_transaccion_dominicales" || $url_elemento == "codigo_transaccion_extras_dominicales";
    $condicionIf .= $url_elemento == "codigo_transaccion_recargo_noche_dominicales" || $url_elemento == "codigo_transaccion_extras_noche_dominicales";
    if($condicionIf){
        if($url_origen!=""){
            $condicion = "";
            $condicion = "codigo_concepto_transaccion_tiempo in (select codigo from job_conceptos_transacciones_tiempo where tipo=1)";
        }else{
            $condicion = "";
            $condicion = "codigo_concepto_transaccion_tiempo in (select codigo from job_conceptos_transacciones_tiempo where tipo=1)";
        }

        if($url_elemento == "codigo_transaccion_normales"){
            $condicion = "codigo!='0' AND codigo_concepto_transaccion_tiempo in (select codigo from job_conceptos_transacciones_tiempo where tipo=1)";
        }

        $consulta  = SQL::seleccionar(array("transacciones_tiempo"), array("codigo", "nombre"),$condicion);
        $respuesta = HTML::generarDatosLista("transacciones_tiempo", "codigo", "nombre",$condicion);
    }
    HTTP::enviarJSON($respuesta);
}


if(!empty($url_generar)){ ///Generar el formulario para la captura de datos

    $existe_salario_integral     = SQL::existeItem("preferencias","variable","equivale_salario_integral","tipo_preferencia = '1'");
    $existe_minimo_ingreso_valor = SQL::existeItem("preferencias","variable","valor_minimo_ingresos_varios","tipo_preferencia = '1'");

    if(!$existe_salario_integral || !$existe_minimo_ingreso_valor){
        $listaMensajes = array();
        $mensaje       = $textos["ERROR_PREFERENCIAS_VACIAS"];
        if(!$existe_salario_integral){
            $listaMensajes[] = $textos["PREFERENCIA_INTEGRAL_VACIA"];
        }
        if(!$existe_minimo_ingreso_valor){
            $listaMensajes[] = $textos["PREFERENCIA_MINIMO_INGVAR_VACIA"];
        }

        $mensaje  .= implode("\n",$listaMensajes);
        $error     = $mensaje;
        $titulo    = "";
        $contenido = "";
    }else{

        ///Validacion de datos de llegada///
        $datos_llegada        = SQL::seleccionar(array("turnos_laborales"), array("*"), "codigo > '0'");
        $totalRegistrosTurnos = SQL::filasDevueltas($datos_llegada);

        $datos_llegada           = SQL::seleccionar(array("planillas"), array("*"), "codigo > '0'");
        $totalRegistrosPlanillas = SQL::filasDevueltas($datos_llegada);

        $datos_llegada            = SQL::seleccionar(array("sucursales"), array("*"), "codigo > '0'");
        $totalRegistrosSucursales = SQL::filasDevueltas($datos_llegada);

        $datos_llegada              = SQL::seleccionar(array("tipos_contrato"), array("*"), "codigo > '0'");
        $totalRegistrosTipoContrato = SQL::filasDevueltas($datos_llegada);

        $datos_llegada        = SQL::seleccionar(array("cargos"), array("*"), "codigo > '0'");
        $totalRegistrosCargos = SQL::filasDevueltas($datos_llegada);

        $datos_llegada               = SQL::seleccionar(array("departamentos_empresa"), array("*"), "codigo > '0'");
        $totalRegistrosDepartamentos = SQL::filasDevueltas($datos_llegada);

        $datos_llegada           = SQL::seleccionar(array("secciones_departamentos"), array("*"), "codigo > '0'");
        $totalRegistrosSecciones = SQL::filasDevueltas($datos_llegada);

        $datos_llegada                 = SQL::seleccionar(array("anexos_contables"), array("*"), "codigo > '0'");
        $totalRegistrosAnexosContables = SQL::filasDevueltas($datos_llegada);

        $datos_llegada                     = SQL::seleccionar(array("transacciones_tiempo"), array("*"), "codigo > '0'");
        $totalRegistrosTransaccionesTiempo = SQL::filasDevueltas($datos_llegada);

        $titulo    = "";
        $contenido = "";
        if($totalRegistrosTurnos == 0){
            $error = $textos["ERROR_VACIO_TURNO"];
        }elseif($totalRegistrosPlanillas == 0){
            $error = $textos["ERROR_VACIO_PLANILLA"];
        }elseif($totalRegistrosSucursales == 0){
            $error = $textos["ERROR_VACIO_SUCURSALES"];
        }elseif($totalRegistrosTipoContrato == 0){
            $error = $textos["ERROR_VACIO_TIPOS_COTRATO"];
        }elseif($totalRegistrosCargos == 0){
            $error = $textos["ERROR_VACIO_CARGOS"];
        }elseif($totalRegistrosDepartamentos == 0){
            $error = $textos["ERROR_VACIO_DEPARTAMENTOS"];
        }elseif($totalRegistrosSecciones == 0){
            $error = $textos["ERROR_VACIO_SECCIONES"];
        }elseif($totalRegistrosAnexosContables == 0){
            $error = $textos["ERROR_VACIO_ANEXOS"];
        }elseif ($totalRegistrosTransaccionesTiempo == 0){
            $error = $textos["ERROR_VACIO_TRANSACCIONES"];
        }else{
            $error  = "";
            $titulo = $componente->nombre;

            $departamentos_empresa   = HTML::generarDatosLista("departamentos_empresa", "codigo", "nombre");
            $riesgo                  = SQL::obtenerValor("departamentos_empresa", "riesgos_profesionales", "codigo = '" . array_shift(array_keys($departamentos_empresa)) . "'");
            $secciones_departamentos = HTML::generarDatosLista("secciones_departamentos", "codigo", "nombre", "codigo_departamento_empresa = '" . array_shift(array_keys($departamentos_empresa)) . "'");

            $empresas   = HTML::generarDatosLista("empresas", "codigo", "razon_social", "codigo != 0");
            $sucursales = HTML::generarDatosLista("sucursales", "codigo", "nombre", "codigo_empresa = '" . array_shift(array_keys($empresas)) . "'AND codigo > '0'");

            $preferencia_equivale_salario_integral = SQL::obtenerValor("preferencias", "valor", "variable='equivale_salario_integral' AND tipo_preferencia = 1 AND codigo_sucursal = '0'");
            $salario_minimo_actual                 = SQL::obtenerValor("salario_minimo", "valor", "codigo!=0 ORDER BY fecha DESC LIMIT 1");

            $tipo_contrato      = HTML::generarDatosLista("tipos_contrato", "codigo", "descripcion");
            $terminio_contrato  = SQL::obtenerValor("tipos_contrato", "termino_contrato", "codigo = '" . array_shift(array_keys($tipo_contrato)) . "'");
            $tipo_salario       = SQL::obtenerValor("tipos_contrato", "tipo_contratacion", "codigo = '" . array_shift(array_keys($tipo_contrato)) . "'");
            // Cargar los anexos relacionados con la empresa de la sucursal iniciada
            $anexos_contables      = array();
            $consulta              = SQL::seleccionar(array("auxiliares_contables,job_anexos_contables"), array("codigo_anexo_contable","job_anexos_contables.descripcion"), "codigo_empresa='" . array_shift(array_keys($empresas)) . "' AND codigo_anexo_contable=job_anexos_contables.codigo", "codigo_anexo_contable");
            $anexos_contables [''] = '';
            while($fila = SQL::filaEnArreglo($consulta)){
                $anexos_contables[$fila[0]] = $fila[1];
            }
            // Genero el listado de los auxiliares contables de acuerdo al anexo cargado
            $listado_auxiliares   = HTML::generarDatosLista("auxiliares_contables","codigo","descripcion", "codigo_anexo_contable = '" . array_shift(array_keys($anexos_contables)) . "'");

            $condicion            = "codigo_concepto_transaccion_tiempo in (select codigo from job_conceptos_transacciones_tiempo where tipo=1)";
            $consulta             = SQL::seleccionar(array("transacciones_tiempo"), array("codigo", "descripcion"),$condicion);
            $condicion_normal     = " codigo>0 OR codigo_concepto_transaccion_tiempo in (select codigo from job_conceptos_transacciones_tiempo where tipo=1)";
            $consulta_normal      = SQL::seleccionar(array("transacciones_tiempo"), array("codigo", "descripcion"),$condicion_normal);
            $transacciones        = array();
            $transacciones_normal = array();

            while($fila_normal = SQL::filaEnArreglo($consulta_normal)){
                $transacciones_normal[$fila_normal[0]] = $fila_normal[1];
            }
            while($fila = SQL::filaEnArreglo($consulta)){
                $transacciones[$fila[0]] = $fila[1];
            }

            $condicion = "codigo !='0' AND codigo_concepto_transaccion_tiempo='1' AND codigo_concepto_transaccion_tiempo in (select codigo from job_conceptos_transacciones_tiempo where tipo=1) LIMIT 0,1";
            $seleccionar_hora_normal = SQL::obtenerValor("transacciones_tiempo","codigo",$condicion);

            //////////////////////////////////////////////////////////////////////////////////////
            //////Permitir Recargar transaccion de tiempo de pendiendo al anexo seleccionado////
            $recargar_transacciones_tiempo  ="recargarLista('id_anexos_contables','codigo_transaccion_normales','".$seleccionar_hora_normal."'); recargarLista('id_anexos_contables','codigo_transaccion_extras'); recargarLista('id_anexos_contables','codigo_transaccion_recargo_nocturno');";
            $recargar_transacciones_tiempo .="recargarLista('id_anexos_contables','codigo_transaccion_extras_nocturnas'); recargarLista('id_anexos_contables','codigo_transaccion_dominicales'); recargarLista('id_anexos_contables','codigo_transaccion_extras_dominicales');";
            $recargar_transacciones_tiempo .="recargarLista('id_anexos_contables','codigo_transaccion_recargo_noche_dominicales'); recargarLista('id_anexos_contables','codigo_transaccion_extras_noche_dominicales');";
            ////////////////////////////////////////////////////////////////////////////////////
            $valor_minimo_ingreso_vario = SQL::obtenerValor("preferencias","valor","variable = 'valor_minimo_ingresos_varios' AND tipo_preferencia='1'");

            $formularios["PESTANA_BASICA"] = array(
                array(
                    HTML::listaSeleccionSimple("*codigo_empresa", $textos["EMPRESA"], $empresas, "", array("title" => $textos["AYUDA_EMPRESAS"], "onChange" => "recargarLista('codigo_empresa','sucursal_labora'); recargarLista('codigo_empresa','id_anexos_contables');", "onBlur" => "cargarPagoTransaccion('id_planilla'); tiposTerminosDecontrato(); cargarAnexos();".$recargar_transacciones_tiempo, "onFocus" => "cargarAnexos();")),
                    HTML::listaSeleccionSimple("*sucursal_labora", $textos["SUCURSAL_LABORA"],$sucursales, "", array("title" => $textos["AYUDA_SUCURSAL_LABORA"],"")),
                    HTML::campoTextoCorto("*selector1", $textos["NOMBRE_COMPLETO"], 40, 255, "", array("title" => $textos["AYUDA_NOMBRE_COMPLETO"], "class" => "autocompletable", "onBlur" => "CargarParafiscales(); validarEmpleado(); CargarRiesgoProfesional();"))
                   .HTML::campoOculto("documento_identidad_empleado", "")
                ),
                array(
                    HTML::campoTextoCorto("*fecha_inicio", $textos["FECHA_INICIAL"], 10, 10, date("Y-m-d"), array("title" => $textos["AYUDA_FECHA_INICIAL"], "class" => "selectorFecha")),
                    HTML::listaSeleccionSimple("*id_turno_laboral", $textos["TURNO_LABORAL"], HTML::generarDatosLista("turnos_laborales", "codigo", "descripcion", "codigo > '0'"), "", array("title" => $textos["AYUDA_TURNO_LABORAL"])),
                ),
                array(
                    HTML::listaSeleccionSimple("*id_planilla", $textos["PLANILLA"], HTML::generarDatosLista("planillas", "codigo", "descripcion", "codigo > '0' "), "", array("title" => $textos["AYUDA_PLANILLA"], "onChange" => "cargarPagoTransaccion('id_planilla');")),
                )
            );

            $formularios["PESTANA_CONTRATO"] = array(
                array(
                    HTML::campoOculto("termino_contrato", $terminio_contrato)
                   .HTML::campoOculto("tipo_salario", $tipo_salario)
                   .HTML::campoOculto("oculto_estado","A")
                   .HTML::campoOculto("valor_minimo_ingreso_vario",$valor_minimo_ingreso_vario)
                   .HTML::campoOculto("salario_minimo", $salario_minimo_actual)
                   .HTML::campoOculto("mensaje_minimo_vario",$textos["MINIMO_VALOR_INGRESO_VARIOS"])
                   .HTML::campoOculto("mensaje_valor_ingreso_varios",$textos["VACIO_VALOR_VARIO"])
                   .HTML::campoOculto("preferencia_equivale_salario_integral", $preferencia_equivale_salario_integral)
                   .HTML::listaSeleccionSimple("*id_tipo_contrato", $textos["TIPO_CONTRATO"], $tipo_contrato, "", array("title" => $textos["AYUDA_TIPO_CONTRATO"], "onChange" => "tiposTerminosDecontrato();")),
                    HTML::listaSeleccionSimple("*id_cargo", $textos["CARGO"], HTML::generarDatosLista("cargos", "codigo", "nombre", "codigo > '0'"), "", array("title" => $textos["AYUDA_CARGO"])),
                    HTML::contenedor(HTML::campoTextoCorto("*fecha_vencimiento_contrato", $textos["FECHA_VENCIMIENTO"], 10, 10, date("Y-m-d"), array("title" => $textos["AYUDA_FECHA_VENCIMIENTO"], "class" => "selectorFecha")), array("id" => "fecha_vencimiento_contrato", "class" => "oculto"))
                ),
                array(
                    HTML::listaSeleccionSimple("*id_departamento", $textos["DEPARTAMENTOS"], $departamentos_empresa, "", array("title" => $textos["AYUDA_DEPARTAMENTOS"], "onChange" => "recargarLista('id_departamento','id_seccion'); CargarRiesgoProfesional();")),
                    HTML::listaSeleccionSimple("*id_seccion", $textos["SECCION"], $secciones_departamentos, "", array("title" => $textos["AYUDA_SECCION"], "onFocus" => "cargarAnexos();")),
                    HTML::campoTextoCorto("riesgo_profesional", $textos["RIESGO_PROFESIONAL"], 10, 255, $riesgo, array("title" => $textos["AYUDA_RIESGO_PROFESIONAL"], "class" => ""))
                ),
                array(
                    HTML::campoTextoCorto("selector7", $textos["NOMBRE_JEFE"], 40, 255, "", array("title" => $textos["AYUDA_NOMBRE_COMPLETO_DEL_JEFE"], "class" => "autocompletable"))
                   .HTML::campoOculto("documento_identidad_jefe", ""),
                    HTML::listaSeleccionSimple("*id_anexos_contables", $textos["ANEXO_CONTABLE"],$anexos_contables, "", array("title" => $textos["AYUDA_ANEXO_CONTABLE"], "onChange" => "cargarAnexos();limpiarCampoTrasaccion();".$recargar_transacciones_tiempo)),
                    HTML::listaSeleccionSimple("id_auxiliar_contable", $textos["AUXILIAR_CONTABLE"],$listado_auxiliares, "", array("title" => $textos["AYUDA_AUXILIAR_CONTABLE"], "class" => "oculto"))
                ),
                array(
                    HTML::campoTextoCorto("salario_mensual", $textos["SALARIOS_MENSUAL"], 10, 255, "", array("title" => $textos["AYUDA_SALARIO"], "onkeyup" => "CalcularSalarioDiario();", "onKeyPress" => "return campoEntero(event)", "onblur" => "equivaleIntegral('" . $textos["ERROR_SALARIO_INTEGRAL"] . "');")),
                    HTML::campoTextoCorto("salario_diario", $textos["SALARIO_DIARIO"], 10, 255, "", array("title" => $textos["AYUDA_SALARIO"], "disabled" => "disabled")),
                    HTML::campoTextoCorto("dias_mes", $textos["DIAS_MES"], 3, 3, "", array("title" => $textos["AYUDA_DIAS_MES"], "onKeyPress" => "return campoEntero(event)","onKeyUp" => "habilitarHorasMes(this);")),
                    HTML::campoTextoCorto("horas_mes", $textos["HORAS_MES"], 3, 3, "", array("title" => $textos["AYUDA_HORAS_MES"], "onKeyPress" => "return campoEntero(event)", "disabled" => "disabled","onKeyUp" => "validarDiasHoras(this);"))
                   .HTML::campoOculto("mensaje_dias_horas",$textos["ERROR_HORAS_NO_CONCUERDAN_DIAS"])
                ),
                array(
                    HTML::mostrarDato("auxilio_trans", $textos["AUXILIO_TRANSPORTE"], ""),
                    HTML::marcaSeleccion("auxilio_transporte", $textos["NO"], 1, true, array("id" => "auxilio_no", "onclick" => "auxilocamposCheck('auxilio_no')")) .
                    HTML::marcaSeleccion("auxilio_transporte", $textos["SI"], 2, false, array("id" => "auxilio_si", "onclick" => "auxilocamposCheck('auxilio_si')")) . '<br/><br/>' ,
                    HTML::listaSeleccionSimple("manejo_auxilio_trasnporte", "", $MANEJO_SALARIO_MINIMO_MENOR_2, "", array("title" => $textos["AUXILIO_TRANSPORTE"]))
                )
            );

            $formularios["PESTANA_PARAFISCALES"] = array(
                array(
                    HTML::campoTextoCorto("*selectorSalud", $textos["ENTIDAD_SALUD"],60,255, "",array("title" => $textos["AYUDA_ENTIDAD_SALUD"],"class" => "autocompletable")).
                    HTML::campoOculto("codigo_entidad_salud",""),
                    HTML::campoTextoCorto("*fecha_inicial_salud", $textos["FECHA_INICIA"], 10, 255, "", array("title" => $textos["AYUDA_FECHA_SALUD"], "class" => "selectorFecha")),
                ),
                array(
                    HTML::campoTextoCorto("direccion_atienden", $textos["DIRECCION_ATIENDEN"], 25, 255, "", array("title" => $textos["AYUDA_DIRECCION_ATIENDEN"]))
                ),
                array(
                    HTML::campoTextoCorto("direccion_urgencia", $textos["DIRECCION_URGENCIA"], 25, 255, "", array("title" => $textos["AYUDA_DIRECCION_URGENCIA"]))
                ),
                array(
                    HTML::campoTextoCorto("*selectorPension", $textos["ENTIDAD_PENSION"],60,255, "",array("title" => $textos["AYUDA_ENTIDAD_PENSION"],"class" => "autocompletable")).
                    HTML::campoOculto("codigo_entidad_pension",""),
                    HTML::contenedor(HTML::campoTextoCorto("fecha_inicial_pension", $textos["FECHA_INICIA"], 10, 255, "", array("title" => $textos["AYUDA_FECHA_PENSION"], "class" => "selectorFecha")),
                        array(
                            "id" => "pensiones",
                        )
                    )
                ),
                array(
                    HTML::campoOculto("pago_mensual", $textos["MENSUAL"])
                   .HTML::campoOculto("2_quincena", $textos["PROPORCIONAL_QUINCENA"])
                   .HTML::campoOculto("3_quincena", $textos["SEGUNDA_QUINCENA"])
                   .HTML::campoOculto("semanal", $textos["SEMANAL"])
                   .HTML::campoOculto("4_semana", $textos["PRIMERA_SEMANA"])
                   .HTML::campoOculto("5_semana", $textos["SEGUNDA_SEMANA"])
                   .HTML::campoOculto("6_semana", $textos["TERCERA_SEMANA"])
                   .HTML::campoOculto("7_semana", $textos["CUARTA_SEMANA"])
                   .HTML::campoOculto("termino_contrato", $terminio_contrato)
                )
                ,
                array(
                    HTML::campoOculto("lista_pagos", "0")
                   .HTML::contenedor(HTML::boton("botonRemoverextras", "", "removerItems(this);", "eliminar"), array("title" => $textos["AYUDA_MODIFICAR_TRASACCION"],"id" => "botonRemoverextras", "style" => "display: none"))
                   .HTML::contenedor(HTML::boton("botonModificar","", "removerModificarTransaccion(this);","modificar"), array("title" => $textos["AYUDA_REMOVER_TRASACCION"],"id" => "botonModificar", "style" => "display: none"))
               )
            );

            $formularios["TRANSACCION_FIJAS"] = array(
                array(
                    HTML::campoTextoCorto("selector3", $textos["TRANSACCION_CONTABLE_SALARIO"], 25, 255, "", array("title" => $textos["AYUDA_TRANSACCION_CONTABLE"], "onFocus" => "autoCompletableLocal(this,'codigo_transaccion_salario',".$conceptos_contables['salario'].");"))
                   .HTML::campoOculto("codigo_transaccion_salario", "")),
                array(
                    HTML::campoTextoCorto("selector4", $textos["TRANSACCION_CONTABLE_AUXILIO"], 25, 255, "", array("title" => $textos["AYUDA_TRANSACCION_CONTABLE"],"onFocus" => "autoCompletableLocal(this,'codigo_transaccion_auxilio_transporte',".$conceptos_contables['auxilio_transporte'].");", "disabled" => "disabled"))
                   .HTML::campoOculto("codigo_transaccion_auxilio_transporte", "")
                    , "&nbsp;&nbsp;&nbsp;" . HTML::listaSeleccionSimple("forma_pago_auxilio", $textos["PERIODO_PAGO"], $QUINCENA, "", array("title" => $textos["AYUDA_ENTIDAD_PENSION"],"class" => "forma_pago","id" => "forma_pago_auxilio", "disabled" => "disabled"))),
                array(HTML::campoTextoCorto("selector5", $textos["TRANSACCION_CONTABLE_SALUD"], 25, 255, "", array("title" => $textos["AYUDA_TRANSACCION_CONTABLE"], "onFocus" => "autoCompletableLocal(this,'codigo_transaccion_salud',".$conceptos_contables['salud'].");"))
                    .HTML::campoOculto("codigo_transaccion_salud", "")
                    ,"&nbsp;&nbsp;&nbsp;" . "&nbsp;&nbsp;&nbsp;" . HTML::listaSeleccionSimple("forma_descuento_salud", $textos["PERIODO_PAGO"], $QUINCENA, "", array("title" => $textos["AYUDA_ENTIDAD_PENSION"],"class" => "forma_pago"))),
                array(HTML::campoTextoCorto("selector6", $textos["TRANSACCION_CONTABLE_PENSION"], 25, 255, "", array("title" => $textos["AYUDA_TRANSACCION_CONTABLE"], "onFocus" => "autoCompletableLocal(this,'codigo_transaccion_pension',".$conceptos_contables['pension'].");"))
                    .HTML::campoOculto("codigo_transaccion_pension", "")
                    ,"&nbsp;&nbsp;&nbsp;" . "&nbsp;&nbsp;&nbsp;" . HTML::listaSeleccionSimple("forma_descuento_pension", $textos["PERIODO_PAGO"], $QUINCENA, "", array("title" => $textos["AYUDA_ENTIDAD_PENSION"],"id" => "forma_descuento_pension","class" => "forma_pago")))
            );

            $formularios["PESTANA_TRANSACCIONES_CONTABLES"] = array(
                array(
                    HTML::campoTextoCorto("selector2", $textos["TRANSACCION_CONTABLE"], 25, 255, "", array("title" => $textos["AYUDA_TRANSACCION_CONTABLE"], "onFocus" => "autoCompletableLocal(this,'codigo_transaccion',".$conceptos_contables['salario'].");", "onblur" => "estadosChecks('0');"))
                   .HTML::campoOculto("codigo_transaccion", "")
                   ,HTML::campoTextoCorto("*valor_ingreso_vario", $textos["VALOR_INGRESO"], 10, 255, "", array("title" => $textos["AYUDA_FECHA_SALUD"],"onKeyPress" => "return campoEntero(event)"))
                ),
                array(
                    HTML::contenedor(
                        HTML::listaSeleccionSimple("forma_descuento_ingresos_varios", $textos["PERIODO_PAGO"], $QUINCENA, "", array("title" => $textos["AYUDA_ENTIDAD_PENSION"],"id" => "forma_descuento_ingresos_varios","class" => "forma_pago")),
                        array("id" => "quincenal", "class" => "oculto")
                    )),
                array(
                    HTML::contenedor(
                        HTML::marcaChequeo("semana_uno", $textos["PRIMERA_SEMANA"], 1, false, array("class" => "4_semana", "disabled" => "disabled")) .
                        HTML::marcaChequeo("semana_dos", $textos["SEGUNDA_SEMANA"], 1, false, array("class" => "5_semana", "disabled" => "disabled")) .
                        HTML::marcaChequeo("semana_tres", $textos["TERCERA_SEMANA"], 1, false, array("class" => "6_semana", "disabled" => "disabled")) .
                        HTML::marcaChequeo("semana_cuatro", $textos["CUARTA_SEMANA"], 1, false, array("class" => "7_semana", "disabled" => "disabled")),
                        array("id" => "semanal", "class" => "oculto")
                    )),
                array(
                    HTML::contenedor(HTML::boton("botonAgregar", $textos["AGREGAR"], "adicionarTransaccionTabla('" . $textos["ERROR_EXISTE_TRANSACCION"] . "','id_planilla');", "adicionar", array("onfocus" => "VerificarHorasExtras();"))),
                    HTML::contenedor(HTML::generarTabla(
                            array("id", "", "ID_TRANSACCION_CONTABLE","DESCRIPCION","PERIODO_PAGO","VALOR_INGRESO"),
                            "",
                            array("I", "I", "I","I","I"),
                            "listaItemsPagos",
                            false
                        )
                    ))
            );

            $formularios["PESTANA_TRANSACCIONES_TIEMPO"] = array(
                array(
                    HTML::listaSeleccionSimple("*codigo_transaccion_normales", $textos["HORAS_NORMALES"], $transacciones_normal, "", array("title" => $textos["AYUDA_EXTRAS_NORMALES"])),
                    HTML::listaSeleccionSimple("codigo_transaccion_extras", $textos["HORAS_EXTRAS"], $transacciones, "", array("title" => $textos["AYUDA_EXTRAS_NOCTURNAS"]))
                ),
                array(
                    HTML::listaSeleccionSimple("codigo_transaccion_recargo_nocturno", $textos["HORAS_RECARGO_NOCTURNA"], $transacciones, "", array("title" => $textos["AYUDA_HORAS_RECARGO_NOCTURNA"])),
                    HTML::listaSeleccionSimple("codigo_transaccion_extras_nocturnas", $textos["HORAS_EXTRAS_NOCTURNAS"], $transacciones, "", array("title" => $textos["AYUDA_EXTRAS_NOCTURNAS"]))
                ),
                array(
                    HTML::listaSeleccionSimple("codigo_transaccion_dominicales", $textos["HORAS_DOMINICALES"], $transacciones, "", array("title" => $textos["AYUDA_HORAS_DOMINICALES"])),
                ),
                array(
                    HTML::listaSeleccionSimple("codigo_transaccion_extras_dominicales", $textos["HORAS_EXTRAS_DOMINGOS_FESTIVOS"], $transacciones, "", array("title" => $textos["AYUDA_EXTRAS_DOMINICALES"])),
                ),
                array(
                    HTML::listaSeleccionSimple("codigo_transaccion_recargo_noche_dominicales", $textos["HORAS_RECARGO_NOCHE_DOMINICAL"], $transacciones, "", array("title" => $textos["AYUDA_HORAS_RECARGO_NOCHE_DOMINICAL"])),
                ),
                array(
                    HTML::listaSeleccionSimple("codigo_transaccion_extras_noche_dominicales", $textos["HORAS_EXTRAS_DOMINGOS_FESTIVOS_NOCHE"], $transacciones, "", array("title" => $textos["AYUDA_EXTRAS_DOMINICALES_NOCTURNAS"])),
                )
            );

            // Definicion de botones
            $botones = array(
                HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar")
            );
            $titulos = array("", "", "", $textos["UBICACION"], "", "", "", "", "", "", "", $textos["TRANSACCION_FIJAS"], $textos["OTRAS_TRANSACCION"], "", "", "", "", "", "", "", "", "");
            $isfail = array("", "", "", 1, "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "");

            $contenido = HTML::generarPestanas($formularios, $botones);
        }
    }
    // Enviar datos para la generacion del formulario al script que origino la peticion
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);
}elseif(!empty($forma_procesar)){

    $error     = false;
    $mensaje   = $textos["ITEM_ADICIONADO"];
    $continuar = false;

    $terminio_contrato = SQL::obtenerValor("tipos_contrato", "termino_contrato", "codigo='".$forma_id_tipo_contrato."'");
    $es_pensionado = SQL::obtenerValor("aspirantes", "pensionado", "documento_identidad = '".$forma_documento_identidad_empleado."'");
    ////////////////validar que el numero de hora ingresada concuerde con el numero de dias//////////////////
    $numero_dias_horas = (int)$forma_horas_mes/24;
    if($numero_dias_horas > (int)$forma_dias_mes){
        $continuar = true;
    }
    /////////////////////////////////////////////////////////////////////////////////////////////////////////

    if(SQL::existeItem("ingreso_empleados", "documento_identidad_empleado", $forma_documento_identidad_empleado)){
        $error = true;
        $mensaje = $textos["ERROR_EXISTE_EMPLEADO"];
    }elseif(empty($forma_documento_identidad_empleado)){
        $error = true;
        $mensaje = $textos["ERROR_VACIO_EMPLEADO"];  //$forma_id_auxiliar_contable
    }elseif(empty($forma_sucursal_labora)){
        $error = true;
        $mensaje = $textos["ERROR_VACIO_SUCURSALES_CODIGO"];
    }elseif(empty($forma_dias_mes)){
        $error = true;
        $mensaje = $textos["ERROR_VACIO_DIAS_MES"];
    }elseif(empty($forma_horas_mes)){
        $error = true;
        $mensaje = $textos["ERROR_VACIO_HORAS_MES"];
    }elseif($continuar){
        $error = true;
        $mensaje = $textos["ERROR_HORAS_NO_CONCUERDAN_DIAS"];
    }elseif(!isset($forma_id_auxiliar_contable) && $forma_id_anexos_contables!=""){
        $error = true;
        $mensaje = $textos["ERROR_VACIO_AUXILIAR"];
    }elseif(empty($forma_id_seccion)){
        $error = true;
        $mensaje = $textos["ERROR_VACIO_SECCIONES_CODIGO"];
    }elseif(empty($forma_salario_mensual)){
        $error = true;
        $mensaje = $textos["ERROR_VACIO_SALARIO_CODIGO"];
    }elseif(empty($forma_fecha_inicial_salud) && ($terminio_contrato == "1" || $terminio_contrato == "2")){
        $error = true;
        $mensaje = $textos["ERROR_VACIO_FECHA_INICIAL_SALUD"];
    }elseif ($es_pensionado==0 && empty($forma_fecha_inicial_pension) && ($terminio_contrato == "1" || $terminio_contrato == "2")){
        $error = true;
        $mensaje = $textos["ERROR_VACIO_FECHA_INICIAL_PENSION"];
    }elseif(empty($forma_codigo_transaccion_salario)){
        $error = true;
        $mensaje = $textos["ERROR_VACIO_TRANSACCION_SALARIO"];
    }else{

        if(!isset($forma_id_auxiliar_contable)){
            $forma_id_auxiliar_contable = 0;
            $empresa_auxiliar = 0;
        }else{
            $empresa_auxiliar = $forma_codigo_empresa;
        }
        if(!isset($forma_auxilio_transporte)){
            $auxilio_transporte = $forma_manejo_auxilio_trasnporte;
        }elseif($forma_auxilio_transporte == "1"){
            $auxilio_transporte = 5;
        }else{
            $auxilio_transporte = $forma_manejo_auxilio_trasnporte;
        }
        $terminio_contrato = SQL::obtenerValor("tipos_contrato", "termino_contrato", "codigo='$forma_id_tipo_contrato'");
        if($terminio_contrato == "1"){
            $fecha_vencimiento = $forma_fecha_vencimiento_contrato;
        }else{
            $fecha_vencimiento = "0000-00-00";
        }
        $datos = array(
            "codigo_empresa"               => $forma_codigo_empresa,
            "documento_identidad_empleado" => $forma_documento_identidad_empleado,
            "fecha_ingreso"                => $forma_fecha_inicio,
            "fecha_vencimiento_contrato"   => "0000-00-00",
            "fecha_retiro"                 => "0000-00-00",
            "codigo_motivo_retiro"         => '0',
            "riesgo_profesional"           => $forma_riesgo_profesional,
            "manejo_auxilio_transporte"    => $auxilio_transporte,
            "codigo_sucursal_activo"       => $forma_sucursal_labora
        );

        $insertar = SQL::insertar("ingreso_empleados", $datos);

        if($terminio_contrato == "1" || $terminio_contrato == "3"){
            $fecha_cambio = $forma_fecha_vencimiento_contrato;
        }elseif($terminio_contrato == "2" || $terminio_contrato == "4"){
            $fecha_cambio = "";
        }else{
            $fecha_cambio = "";
        }

        $datos = array(
            "codigo_empresa"                => $forma_codigo_empresa,
            "documento_identidad_empleado"  => $forma_documento_identidad_empleado,
            "fecha_ingreso"                 => $forma_fecha_inicio,
            "fecha_contrato"                => $forma_fecha_inicio,
            "codigo_tipo_contrato"          => $forma_id_tipo_contrato,
            "fecha_cambio_contrato"         => $fecha_cambio,
        );

        $insertar = SQL::insertar("contrato_empleados", $datos);

        ///////////calculo de proporcion///////////////
        $salario_diario    = ($forma_salario_mensual / $forma_dias_mes);
        $numero_horas_dias =  $forma_horas_mes / $forma_dias_mes;
        $valor_hora        =  $salario_diario / $numero_horas_dias;
        ///////////////////////////////////////////////
        $periodoPago = SQL::obtenerValor("planillas", "periodo_pago", "codigo = '".$forma_id_planilla."'");

        // Validacion de campos transacciones auxilo de transporte y pension
        if(!isset($forma_codigo_transaccion_auxilio_transporte))
        {
            $forma_codigo_transaccion_auxilio_transporte="0";
        }
        if(!isset($forma_codigo_transaccion_pension))
        {
            $forma_codigo_transaccion_pension="0";
        }
        if(empty ($forma_forma_pago_auxilio))
        {
            $forma_forma_pago_auxilio="1";
        }
        if(empty ($forma_forma_descuento_pension))
        {
            $forma_forma_descuento_pension="1";
        }
        //////////////////////////////////////////////////////////////////////////////////////////

        $datos = array(
            "codigo_empresa"                               => $forma_codigo_empresa,
            "documento_identidad_empleado"                 => $forma_documento_identidad_empleado,
            "fecha_ingreso"                                => $forma_fecha_inicio,
            "codigo_sucursal"                              => $forma_sucursal_labora,
            "fecha_ingreso_sucursal"                       => $forma_fecha_inicio,
            /////////////////////////////
            "codigo_anexo_contable"                        => $forma_id_anexos_contables,
            ///tabla_auxiliares_contables
            "codigo_empresa_auxiliar"                      => $empresa_auxiliar,
            "codigo_auxiliar"                              => $forma_id_auxiliar_contable,
            /////////////////////////////
            "codigo_planilla"                              => $forma_id_planilla,
            "salario_mensual"                              => $forma_salario_mensual,
            "valor_hora"                                   => $salario_diario,
            "dias_mes"                                     => $forma_dias_mes,
            "horas_mes"                                    => $forma_horas_mes,
            "codigo_turno_laboral"                         => $forma_id_turno_laboral,
            "codigo_motivo_retiro"                         => '0',
            "fecha_retiro"                                 => "0000-00-00",
            "codigo_transaccion_salario"                   => $forma_codigo_transaccion_salario,
            "codigo_transaccion_auxilio_transporte"        => $forma_codigo_transaccion_auxilio_transporte,
            "forma_pago_auxilio"                           => $forma_forma_pago_auxilio,
            "codigo_transaccion_salud"                     => $forma_codigo_transaccion_salud,
            "forma_descuento_salud"                        => $forma_forma_descuento_salud,
            "codigo_transaccion_pension"                   => $forma_codigo_transaccion_pension,
            "forma_descuento_pension"                      => $forma_forma_descuento_pension,
            "codigo_transaccion_normales"                  => $forma_codigo_transaccion_normales,
            "codigo_transaccion_extras"                    => $forma_codigo_transaccion_extras,
            "codigo_transaccion_recargo_nocturno"          => $forma_codigo_transaccion_recargo_nocturno,
            "codigo_transaccion_extras_nocturnas"          => $forma_codigo_transaccion_extras_nocturnas,
            "codigo_transaccion_dominicales"               => $forma_codigo_transaccion_dominicales,
            "codigo_transaccion_extras_dominicales"        => $forma_codigo_transaccion_extras_dominicales,
            "codigo_transaccion_recargo_noche_dominicales" => $forma_codigo_transaccion_recargo_noche_dominicales,
            "codigo_transaccion_extras_noche_dominicales"  => $forma_codigo_transaccion_extras_noche_dominicales,
        );


       $insertar = SQL::insertar("sucursal_contrato_empleados", $datos);

       $datos = array(
            "codigo_empresa"               => $forma_codigo_empresa,
            "documento_identidad_empleado" => $forma_documento_identidad_empleado,
            "fecha_ingreso"                => $forma_fecha_inicio,
            "codigo_sucursal"              => $forma_sucursal_labora,
            "fecha_ingreso_sucursal"       => $forma_fecha_inicio,
            "fecha_salario"                => $forma_fecha_inicio,
            "fecha_cambio_salario"         => $forma_fecha_inicio,
            "salario"                      => $forma_salario_mensual,
            "valor_dia"                    => $salario_diario,
            "valor_hora"                   => $valor_hora

        );

        $consulta=  SQL::insertar("salario_sucursal_contrato",$datos);

        $datos = array(
            "codigo_empresa"               => $forma_codigo_empresa,
            "documento_identidad_empleado" => $forma_documento_identidad_empleado,
            "fecha_ingreso"                => $forma_fecha_inicio,
            "fecha_inicio_salud"           => $forma_fecha_inicial_salud,
            "codigo_entidad_salud"         => $forma_codigo_entidad_salud,
            "direccion_atencion"           => $forma_direccion_atienden,
            "direccion_urgencia"           => $forma_direccion_urgencia
        );
        $insertar = SQL::insertar("entidades_salud_empleados", $datos);

        $pension  = SQL::obtenerValor("aspirantes", "pensionado", "documento_identidad = '$forma_documento_identidad_empleado'");
        if ($pension == 0) {
            $datos = array(
                "codigo_empresa"                => $forma_codigo_empresa,
                "documento_identidad_empleado"  => $forma_documento_identidad_empleado,
                "fecha_ingreso"                 => $forma_fecha_inicio,
                "fecha_inicio_pension"          => $forma_fecha_inicial_pension,
                "codigo_entidad_pension"        => $forma_codigo_entidad_pension
            );
            $insertar = SQL::insertar("entidades_pension_empleados", $datos);
        }
        if (!isset($forma_idPosicionTablaPago)) {
            $forma_idPosicionTablaPago = array();
        }

        for ($id = 0; !empty($forma_ConceptoContableTabla[$id]); $id++) {
            $datos = array(
                "codigo_empresa"                 => $forma_codigo_empresa,
                "documento_identidad_empleado"   => $forma_documento_identidad_empleado,
                "fecha_ingreso"                  => $forma_fecha_inicio,
                "fecha_inicio_transacion_tiempo" => $forma_fecha_inicio,
                "fecha_final_transacion_tiempo"  => $forma_fecha_inicio,
                "estado"                         => 1,
                "codigo_transaccion_tiempo"      => $forma_IdConceptoContable[$id],
                "periodo_pago"                   => $forma_PeriodoPagoTabla[$id],
                "valor"                          => $forma_valorIngreso[$id]
            );
            $insertar = SQL::insertar("ingresos_varios_empleados", $datos);
            if(!$insertar){
                $error = true;
                $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
                $mensaje = mysql_error();
            }
        }
        if(empty($forma_documento_identidad_jefe)){
            $forma_documento_identidad_jefe = "0";
        }

        $datos = array(
            "codigo_empresa"                     => $forma_codigo_empresa,
            "documento_identidad_empleado"       => $forma_documento_identidad_empleado,
            "fecha_ingreso"                      => $forma_fecha_inicio,
            "codigo_sucursal"                    => $forma_sucursal_labora,
            "fecha_ingreso_sucursal"             => $forma_fecha_inicio,
            "fecha_inicia_cargo"                 => $forma_fecha_inicio,
            "codigo_cargo"                       => $forma_id_cargo,
            "fecha_termina"                      => $forma_fecha_vencimiento_contrato,
            "documento_identidad_jefe_inmediato" => $forma_documento_identidad_jefe
        );

        $insertar = SQL::insertar("cargo_contrato_empleados", $datos);

        $datos = array(
            "codigo_empresa"                    => $forma_codigo_empresa,
            "documento_identidad_empleado"      => $forma_documento_identidad_empleado,
            "fecha_ingreso"                     => $forma_fecha_inicio,
            "codigo_sucursal"                   => $forma_sucursal_labora,
            "fecha_ingreso_sucursal"            => $forma_fecha_inicio,
            "fecha_inicia_departamento_seccion" => $forma_fecha_inicio,
            "codigo_departamento_empresa"       => $forma_id_departamento,
            "codigo_seccion_empresa"            => $forma_id_seccion,
            "fecha_termina"                     => $forma_fecha_inicio,
        );

        $insertar = SQL::insertar("departamento_seccion_contrato_empleado",$datos);
        if (!$insertar) {
            $error = true;
            $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
            $mensaje = mysql_error();
        }
    }

    // Enviar datos con la respuesta del proceso al script que origino la peticion
    $respuesta = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

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
$conceptos_contables = array(
    "salario" => 1,
    "pension" => 6,
    "auxilio_transporte" => 3,
    "salud" => 5
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
    }
    if(($url_item) == "selectorPension"){
        echo SQL::datosAutoCompletar("seleccion_entidades_parafiscales_pension", $url_q);
    }
    exit;
}
////////Generacion de listado del transacciones contables de acuerdo al anexo del empleado////////
if(isset($url_verificarTransaccion)){
    $condicionIf = $url_item == "selector3" || $url_item == "selector4" || $url_item == "selector5";

    if($condicionIf){
        //$condicion_extra = " codigo_contable in (select codigo_contable from job_plan_contable where codigo_anexo_contable ='$url_anexo')  AND '$url_concepto' in (select codigo_concepto_transaccion_contable from job_transacciones_contables_empleado where codigo_concepto_transaccion_contable='$url_concepto')";
         $condicion_extra = "";
        echo SQL::datosAutoCompletar("seleccion_transacciones_contables_empleado", $url_q, $condicion_extra);
    }
    if($url_item == "selector6"){
        //$condicion_extra = " codigo_contable in (select codigo_contable from job_plan_contable where codigo_anexo_contable ='$url_anexo')";
         $condicion_extra = "";
        echo SQL::datosAutoCompletar("seleccion_transacciones_contables_empleado", $url_q, $condicion_extra);
    }if(($url_item) == "selector2"){
        //$condicion_extra = " codigo_contable in (select codigo_contable from job_plan_contable where codigo_anexo_contable ='$url_anexo' AND codigo_contable LIKE '5%')"; /// se le agrega como condicion que solo cargue los egresos(gastos)
        $condicion_extra = "";
        echo SQL::datosAutoCompletar("seleccion_transacciones_contables_empleado", $url_q, $condicion_extra);
    }
    exit;
}
if(isset($url_tipo_planilla) && isset($url_id) && isset($url_idconceptoContable)){
    $tipo_planilla           = SQL::obtenerValor("planillas", "periodo_pago", "codigo='$url_id'");
    $descripcion_transaccion = SQL::obtenerValor("transacciones_contables_empleado", "descripcion", "codigo='$url_idconceptoContable'");
    $datos    = array();
    $datos[0] = $tipo_planilla;
    $datos[1] = $descripcion_transaccion;
    $datos[2] = date("Y-m-d");
    HTTP::enviarJSON($datos);
    exit;
}
if(isset($url_termino)){
    $terminio_contrato = SQL::obtenerValor("tipos_contrato", "termino_contrato", "codigo='$url_id'");
    $tipo_salario = SQL::obtenerValor("tipos_contrato", "tipo_contratacion", "codigo='$url_id'");
    $tipo_contrato = array();
    $tipo_contrato[0] = $terminio_contrato;
    $tipo_contrato[1] = $tipo_salario;
    HTTP::enviarJSON($tipo_contrato);
    exit;
}
if(isset($url_verificarPlanilla) && isset($url_id_planilla)){
    $periodoPago = SQL::obtenerValor("planillas", "periodo_pago", "codigo = '$url_id_planilla'");
    HTTP::enviarJSON($periodoPago);
    exit;
}
/// Validar pension ///
if(isset($url_verificarPension)){
    if(isset($url_id_empleado)){
        $existe = SQL::obtenerValor("aspirantes", "pensionado", "id = '$url_id_empleado'");
    }
    HTTP::enviarJSON($existe);
    exit;
}
if(isset($url_verificarEmpleado) && isset($url_id_empleado)){
    $existe = SQL::existeItem("ingreso_empleados", "documento_identidad_empleado", $url_id_empleado, "documento_identidad_empleado!='$url_id'");
    if($existe){
        $mensaje = $textos["ERROR_EXISTE_EMPLEADO"];
        HTTP::enviarJSON($mensaje);
    }
    exit;
}
if(isset($url_verificarAnexos) && isset($url_id_anexos_contable)){
    $lista = "";
    if($url_id_anexos_contable){
        $lista = HTML::generarDatosLista("buscador_auxiliares_contables", "codigo", "descripcion", "codigo_id = '$url_id_anexos_contable' AND codigo > '0'");
    }
    HTTP::enviarJSON($lista);
    exit;
}
if(isset($url_verificarRiesgos)){
    $riesgo = SQL::obtenerValor("departamentos_empresa", "riesgos_profesionales", "codigo = '$url_id_riesgo'");
    HTTP::enviarJSON($riesgo);
    exit;
}
///// Carga las entidades parafiscales con la que se encuentra relaciona el ////
if(isset($url_verificarParafiscales)){
    if (isset($url_id_empleado)) {
        $entidades      = array();
        $entidad_salud  = SQL::obtenerValor("aspirantes", "codigo_entidad_salud", "documento_identidad = '$url_id_empleado'");
        $entidad_pesion = SQL::obtenerValor("aspirantes", "codigo_entidad_pension", "documento_identidad = '$url_id_empleado'");
        $pensionado     = SQL::obtenerValor("aspirantes", "pensionado", "documento_identidad = '$url_id_empleado'");
        $entidades[0]   = $entidad_salud;
        $entidades[1]   = $entidad_pesion;
        $entidades[2]   = $entidad_pesion;
        HTTP::enviarJSON($entidades);
        exit;
    }
}

if(!empty($url_recargar)){
    if($url_elemento == "sucursal_labora"){
        $respuesta = HTML::generarDatosLista("sucursales", "codigo", "nombre", "codigo_empresa = '$url_origen'");
    }
    if($url_elemento == "id_seccion"){
        $respuesta = HTML::generarDatosLista("secciones_departamentos", "codigo", "nombre", "codigo_departamento_empresa = '$url_origen'");
    }
    if($url_elemento == "id_anexos_contables"){
        $respuesta = array();
        $consulta = SQL::seleccionar(array("auxiliares_contables,job_anexos_contables"), array("codigo_anexo_contable", "job_anexos_contables.descripcion"), "codigo_empresa='$url_origen' AND codigo_anexo_contable=job_anexos_contables.codigo", "codigo_anexo_contable");
        while($fila = SQL::filaEnArreglo($consulta)){
            $respuesta[$fila[0]] = $fila[1];
        }
    }
    /////Contruir toda la condicion apartir del elemento de llegada/////
    $condicionIf  = $url_elemento == "codigo_transaccion_normales" || $url_elemento == "codigo_transaccion_extras" || $url_elemento == "codigo_transaccion_recargo_nocturno";
    $condicionIf .= $url_elemento == "codigo_transaccion_extras_nocturnas" || $url_elemento == "codigo_transaccion_dominicales" || $url_elemento == "codigo_transaccion_extras_dominicales";
    $condicionIf .= $url_elemento == "codigo_transaccion_recargo_noche_dominicales" || $url_elemento == "codigo_transaccion_extras_noche_dominicales";
    if($condicionIf){
        //$condicion = "codigo='0' OR codigo_concepto_transaccion_tiempo in (select codigo from job_conceptos_transacciones_tiempo where tipo=1) AND codigo_transaccion_contable IN (SELECT codigo FROM job_transacciones_contables_empleado WHERE codigo_contable IN (SELECT codigo_contable FROM job_plan_contable WHERE codigo_anexo_contable='$url_origen'))";
          $condicion="";
        $consulta = SQL::seleccionar(array("transacciones_tiempo"), array("codigo", "descripcion"), $condicion);

        $respuesta = array();
        while($fila = SQL::filaEnArreglo($consulta)){
            $respuesta[$fila[0]] = $fila[1];
        }
    }
    HTTP::enviarJSON($respuesta);
}

////Validacion de datos de llegada/////
$datos_llegada                     = SQL::seleccionar(array("turnos_laborales"), array("*"), "codigo > '0'");
$totalRegistrosTurnos              = SQL::filasDevueltas($datos_llegada);
$datos_llegada                     = SQL::seleccionar(array("planillas"), array("*"), "codigo > '0'");
$totalRegistrosPlanillas           = SQL::filasDevueltas($datos_llegada);
$datos_llegada                     = SQL::seleccionar(array("sucursales"), array("*"), "codigo > '0'");
$totalRegistrosSucursales          = SQL::filasDevueltas($datos_llegada);
$datos_llegada                     = SQL::seleccionar(array("tipos_contrato"), array("*"), "codigo > '0'");
$totalRegistrosTipoContrato        = SQL::filasDevueltas($datos_llegada);
$datos_llegada                     = SQL::seleccionar(array("cargos"), array("*"), "codigo > '0'");
$totalRegistrosCargos              = SQL::filasDevueltas($datos_llegada);
$datos_llegada                     = SQL::seleccionar(array("departamentos_empresa"), array("*"), "codigo > '0'");
$totalRegistrosDepartamentos       = SQL::filasDevueltas($datos_llegada);
$datos_llegada                     = SQL::seleccionar(array("secciones_departamentos"), array("*"), "codigo > '0'");
$totalRegistrosSecciones           = SQL::filasDevueltas($datos_llegada);
$datos_llegada                     = SQL::seleccionar(array("anexos_contables"), array("*"), "codigo > '0'");
$totalRegistrosAnexosContables     = SQL::filasDevueltas($datos_llegada);
$datos_llegada                     = SQL::seleccionar(array("transacciones_tiempo"), array("*"), "codigo > '0'");
$totalRegistrosTransaccionesTiempo = SQL::filasDevueltas($datos_llegada);

if(!empty($url_generar)){
    /// Verificar que se haya enviado el ID del elemento a consultar ////
    if(empty($url_id)){
        $error = $textos["ERROR_MODIFICAR_VACIO"];
        $titulo = "";
        $contenido = "";
    }else{
        $llave_pricipal               = explode("|", $url_id);
        $codigo_empresa               = $llave_pricipal[0];
        $documento_identidad_empleado = $llave_pricipal[1];
        $fecha_inicio                 = $llave_pricipal[2];
        //Derminos si el empleado ha sido contabilizado en planilla, de no ser asi se puede realizar todos los cambios posible
        $consulta_datos_planilla = SQL::seleccionar(array("consulta_datos_planilla"), array("*"), "documento_identidad_empleado='$documento_identidad_empleado'");//AND (contabilizado='1' || contabilizado='2')
        $vistaConsulta           = "ingreso_empleados";
        $columnas                = SQL::obtenerColumnas($vistaConsulta);
        // echo var_dump( "codigo_empresa='$codigo_empresa' AND documento_identidad_empleado='$documento_identidad_empleado' AND fecha_ingreso='$fecha_ingreso'");
        $consulta_ingreso_empleados = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo_empresa='$codigo_empresa' AND documento_identidad_empleado='$documento_identidad_empleado' AND fecha_ingreso='$fecha_inicio'");
        $datos_ingreso_empleados    = SQL::filaEnObjeto($consulta_ingreso_empleados);
        $nombre_empleado = SQL::obtenerValor("seleccion_aspirantes","SUBSTRING_INDEX(nombre_completo,'|',1)", "id ='$documento_identidad_empleado'");
        $fecha_inicio    = $datos_ingreso_empleados->fecha_ingreso;
        $codigo_sucursal = $datos_ingreso_empleados->codigo_sucursal_activo;

        $vistaConsulta                     = "cargo_contrato_empleados";
        $columnas                          = SQL::obtenerColumnas($vistaConsulta);
        $consulta_cargo_contrato_empleados = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo_empresa='$codigo_empresa' AND documento_identidad_empleado='$documento_identidad_empleado' AND fecha_ingreso='$fecha_inicio' AND codigo_sucursal='$codigo_sucursal'", "", "fecha_inicia_cargo  DESC", 0, 1);
        $datos_cargo_contrato_empleados    = SQL::filaEnObjeto($consulta_cargo_contrato_empleados);
        $fecha_ingreso_cargo               = $datos_cargo_contrato_empleados->fecha_inicia_cargo;

        $vistaConsulta                      = "entidades_salud_empleados";
        $columnas                           = SQL::obtenerColumnas($vistaConsulta);
        $consulta_entidades_salud_empleados = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo_empresa='$codigo_empresa' AND documento_identidad_empleado='$documento_identidad_empleado' AND fecha_ingreso='$fecha_inicio'", "", "fecha_inicio_salud DESC", 0, 1);
        $datos_entidades_salud_empleados    = SQL::filaEnObjeto($consulta_entidades_salud_empleados);

        $vistaConsulta              = "entidades_pension_empleados";
        $columnas                   = SQL::obtenerColumnas($vistaConsulta);
        $consulta_pension_empleados = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo_empresa='$codigo_empresa' AND documento_identidad_empleado='$documento_identidad_empleado' AND fecha_ingreso='$fecha_inicio'", "", "fecha_inicio_pension DESC", 0, 1);
        $datos_pension_empleados    = SQL::filaEnObjeto($consulta_pension_empleados);
        /////////////////////////////////////////////////////////////
        if($datos_pension_empleados){
            $entidad_pension       = $datos_pension_empleados->codigo_entidad_pension;
            $fecha_entidad_pension = $datos_pension_empleados->fecha_inicio_pension;

            if((strtotime(Date("Y-m-d")) >= strtotime($fecha_entidad_pension))){
                $rango_dias_pension = (int) ((strtotime(Date("Y-m-d")) - strtotime($fecha_entidad_pension)) / (60 * 60 * 24) - 1);
            }else{
                $rango_dias_pension = (int) (strtotime($fecha_entidad_pension) - (strtotime(Date("Y-m-d"))) / (60 * 60 * 24) - 1);
            }
        }else{
            $entidad_pension = "";
            $fecha_entidad_pension = "";
            $rango_dias_pension = "";
        }
        /////////////////////////////////////////////////////////////
        if($datos_entidades_salud_empleados->fecha_inicio_salud == "0000-00-00"){
            $fecha_entidad_salud = "";
            $rango_dias_salud    = "";
        }else{
            $fecha_entidad_salud = $datos_entidades_salud_empleados->fecha_inicio_salud;
            $rango_dias_salud = (int) ((strtotime(Date("Y-m-d")) - strtotime($fecha_entidad_salud)) / (60 * 60 * 24) - 1);
        }
        /////////////////////////////////////////////////////////////
        if($datos_ingreso_empleados->manejo_auxilio_transporte != '5'){
            //echo var_dump("SIII");
            $recibe_si                  = true;
            $recibe_no                  = false;
            $manejo_auxilio             = $datos_ingreso_empleados->manejo_auxilio_transporte;
            $estado_transaccion_auxilio = "";
            $oculto_listado             = "";
        }else{
            $recibe_si                  = false;
            $recibe_no                  = true;
            $manejo_auxilio             = 5;
            $estado_transaccion_auxilio = "disabled";
            $oculto_listado             = "oculto";
        }

        $vistaConsulta               = "contrato_empleados";
        $columnas                    = SQL::obtenerColumnas($vistaConsulta);
        $consulta_contrato_empleados = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo_empresa='$codigo_empresa' AND documento_identidad_empleado='$documento_identidad_empleado' AND fecha_ingreso='$fecha_inicio'", "", "fecha_contrato DESC", 0, 1);
        $datos_contrato_empleados    = SQL::filaEnObjeto($consulta_contrato_empleados);

        $codigo_tipo_contrato = SQL::obtenerValor("tipos_contrato", "descripcion", "codigo='$datos_contrato_empleados->codigo_tipo_contrato'");
        $terminio_contrato    = SQL::obtenerValor("tipos_contrato", "termino_contrato", "codigo='$datos_contrato_empleados->codigo_tipo_contrato'");

        if($terminio_contrato == 1 || $terminio_contrato == 3){
            $fecha_cambio_contrato = $datos_contrato_empleados->fecha_cambio_contrato;
        }else{
            $fecha_cambio_contrato = "Indefinida";
        }

        $vistaConsulta                        = "sucursal_contrato_empleados";
        $columnas                             = SQL::obtenerColumnas($vistaConsulta);
        $consulta_sucursal_contrato_empleados = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo_empresa='$codigo_empresa' AND documento_identidad_empleado='$documento_identidad_empleado' AND fecha_ingreso='$fecha_inicio'", "", "fecha_ingreso_sucursal DESC", 0, 1); ////////////
        $datos_sucursal_contrato_empleados    = SQL::filaEnObjeto($consulta_sucursal_contrato_empleados);
        $fecha_ingreso_sucursal               = $datos_sucursal_contrato_empleados->fecha_ingreso_sucursal;

        $condicion = "codigo_empresa='$datos_sucursal_contrato_empleados->codigo_empresa' AND documento_identidad_empleado='$datos_sucursal_contrato_empleados->documento_identidad_empleado' AND fecha_ingreso='$datos_sucursal_contrato_empleados->fecha_ingreso'  ORDER BY fecha_salario DESC LIMIT 0,1"; //AND fecha_ingreso_sucursal='$datos_sucursal_contrato_empleados->fecha_ingreso'
        $salario_actual_empleado = SQL::obtenerValor("salario_sucursal_contrato","salario",$condicion);
        $valor_hora_empleado      = SQL::obtenerValor("salario_sucursal_contrato","valor_hora",$condicion);
        $fecha_salario           = SQL::obtenerValor("salario_sucursal_contrato","fecha_salario",$condicion);

        if(SQL::filasDevueltas($consulta_datos_planilla) == 0){
            $url_cargarmenu                  = "1";
            $url_opcion                      = 1;
            $url_opcion_basica               = 0;
            $url_opcion_contrato             = 0;
            $url_opcion_parafiscales         = 0;
            $url_opcion_transacciones_fijas  = 0;
            $url_opcion_contables            = 0;
            $url_opcion_transacciones_tiempo = 0;
            $url_opcion_modificar_salario    = 0;
            $url_opciones                    = '1|0|0|0|0|0|0|0';
        }
        //$url_cargarmenu = "si";

        if(empty($url_cargarmenu)){

            $error     = "";
            $titulo    = $componente->nombre;
            $contenido = "";
            $formularios["OPCIONES"] = array(
                array(
                    HTML::mostrarDato("mensaje", "", $textos["MENSAJE"]),
                ),
                /*array(
                    HTML::campoTextoCorto("*fecha_inicio_cambios", $textos["FECHA_INICIAL_CAMBIOS"], 10, 10, "", array("title" => $textos["AYUDA_FECHA_INICIAL"], "class" => "selectorFecha")),
                ),*/
                array(
                    HTML::marcaChequeo("translado_sucursal", $textos["TRANSLADO_CONTRATO_SUCURSAL"],1,false,array("id" => "translado_sucursal")),
                ),
                /*array(
                    HTML::marcaChequeo("renovar_contrato", $textos["CAMBIO_CONTRATO"], 1, false, array("onclick" => "mostrarCalendario(this,'#fecha_renovar_contrato_contenedor');","id" => "renovar_contrato")),
                ),*/
                array(
                    HTML::contenedor(HTML::campoTextoCorto("*fecha_renovar_contrato", $textos["FECHA_RENOVACION_CONTRATO"], 10, 10, date("Y-m-d"), array("onclick" => "mostrarCalendario(this,'#fecha_renovar_contrato_contenedor');","title" => $textos["AYUDA_FECHA_INICIAL"], "class" => "selectorFecha")), array("class" => "oculto", "id" => "fecha_renovar_contrato_contenedor"))
                ),
                array(
                    HTML::marcaChequeo("entidades_parafiscales", $textos["CAMBIAR_PARAFISCALES"], 1, false, array("id" => "entidades_parafiscales")),
                )
                ,
                array(
                    HTML::marcaChequeo("ingresos_varios", $textos["CAMBIAR_INGRESO_VARIOS_EMPLEADO"], 1, false, array("onclick" => "mostrarCalendario(this,'#fecha_ingresos_varios');","id" => "ingresos_varios")),
                ),
                array(
                    HTML::contenedor(HTML::campoTextoCorto("*fecha_ingresos_varios_calendario", $textos["FECHA_INICIAL_CAMBIOS"], 10, 10, date("Y-m-d"), array("title" => $textos["AYUDA_FECHA_INICIAL"], "class" => "selectorFecha")), array("class" => "oculto", "id" => "fecha_ingresos_varios"))
                ),
                array(
                    HTML::marcaChequeo("modificar_transacciones", $textos["MODIFICAR_TRANSACCIONES"], 1, false, array("id" => "modificar_transacciones")),
                ),
                array(
                    HTML::marcaChequeo("modificar_salario", $textos["MODIFICAR_SALARIO"], 1, false, array("id" => "modificar_salario")),
                ),
            );

            $datos_envio = '0|1|0|0|0|0|0'; // '0|0|1|0|1|0|1'
            ////Calculos los diferentes rangos para bloquear los dias/////
            $date_r = getdate(strtotime($fecha_salario));
            $fecha_termina = date("Y-m-d", mktime(($date_r["hours"]), ($date_r["minutes"]), ($date_r["seconds"]), ($date_r["mon"]), ($date_r["mday"] - 1), ($date_r["year"])));

            $rango_dias = (int) ((strtotime(Date("Y-m-d")) - strtotime($fecha_ingreso_sucursal)) / (60 * 60 * 24) - 1);
            $rango_dias_salario =  (int) ((strtotime(Date("Y-m-d")) - strtotime($fecha_termina)) / (60 * 60 * 24) - 1);
            //echo var_dump($rango_dias_salario);
            // echo var_dump($fecha_salario);
            $botones    = array(
                HTML::boton("botonAceptar", $textos["MODIFICAR"], "cargarOpcion('$datos_envio','$url_id','$rango_dias','$rango_dias_salud','$rango_dias_pension','$rango_dias_salario');", "modificar")
            );
            $contenido = HTML::generarPestanas($formularios, $botones);
        }else{

            $titulo    = $componente->nombre;
            $contenido = "";
            if($totalRegistrosTurnos == 0){
                $error = $textos["ERROR_VACIO_TURNO"];
            }elseif($totalRegistrosPlanillas == 0){
                $error = $textos["ERROR_VACIO_PLANILLA"];
            }elseif($totalRegistrosSucursales == 0){
                $error = $textos["ERROR_VACIO_SUCURSALES"];
            }elseif($totalRegistrosTipoContrato == 0){
                $error = $textos["ERROR_VACIO_TIPOS_COTRATO"];
            }elseif ($totalRegistrosCargos == 0){
                $error = $textos["ERROR_VACIO_CARGOS"];
            }elseif($totalRegistrosDepartamentos == 0){
                $error = $textos["ERROR_VACIO_DEPARTAMENTOS"];
            }elseif($totalRegistrosSecciones == 0){
                $error = $textos["ERROR_VACIO_SECCIONES"];
            }elseif($totalRegistrosAnexosContables == 0){
                $error = $textos["ERROR_VACIO_ANEXOS"];
            }elseif($totalRegistrosTransaccionesTiempo == 0){
                $error = $textos["ERROR_VACIO_TRANSACCIONES"];
            }else{
                $titulo       = $componente->nombre;
                $contenido    = "";
                $error        = "";
                $formas_pago  = array("1" => $textos["PROPORCIONAL_QUINCENA"], "2" => $textos["SEGUNDA_QUINCENA"]);
                $formas_pago_todo = array("1" => $textos["PROPORCIONAL_QUINCENA"], "2" => $textos["SEGUNDA_QUINCENA"], "3" => $textos["MENSUAL"]);
                $MANEJO_AUXILIO_TRANAPORTE = array(
                    "1" => $textos["PAGUELO_POR_LEY_CON_DESCUENTO"],
                    "2" => $textos["PAGUELO_POR_LEY_SIN_DESCUENTO"],
                    "3" => $textos["PAGELE_MAYOR_2_SALARIO_CON_DESCUENTO"],
                    "4" => $textos["PAGELE_MAYOR_2_SALARIO_SIN_DESCUENTO"]
                );
                $sucursal             = SQL::obtenerValor("sucursales", "nombre", "codigo='$datos_sucursal_contrato_empleados->codigo_sucursal'");
                $codigo_planilla      = $datos_sucursal_contrato_empleados->codigo_planilla;
                $codigo_turno_laboral = $datos_sucursal_contrato_empleados->codigo_turno_laboral;
                $turno_laboral        = SQL::obtenerValor("turnos_laborales", "descripcion", "codigo='$codigo_turno_laboral'");

                // $empresas = HTML::generarDatosLista("empresas", "codigo", "razon_social", "codigo != 0");
                $sucursales                            = HTML::generarDatosLista("sucursales", "codigo", "nombre", "codigo_empresa ='$codigo_empresa' AND codigo > '0'");
                $preferencia_equivale_salario_integral = SQL::obtenerValor("preferencias", "valor", "variable='equivale_salario_integral' AND tipo_preferencia = 1 AND codigo_sucursal = '0'");
                $salario_minimo_actual                 = SQL::obtenerValor("salario_minimo", "valor", "codigo!=0 ORDER BY fecha DESC LIMIT 1");
                $tipo_contrato                         = HTML::generarDatosLista("tipos_contrato", "codigo", "descripcion");
                $terminio_contrato                     = SQL::obtenerValor("tipos_contrato", "termino_contrato", "codigo = '" . array_shift(array_keys($tipo_contrato)) . "'");
                $tipo_salario                          = SQL::obtenerValor("tipos_contrato", "tipo_contratacion", "codigo = '" . array_shift(array_keys($tipo_contrato)) . "'");
                //$tipo_planilla=SQL::obtenerValor("planillas","periodo_pago","codigo='$url_id'");
                $nombre_jefe                           =  SQL::obtenerValor("seleccion_aspirantes","SUBSTRING_INDEX(nombre_completo,'|',1)", "id ='$datos_cargo_contrato_empleados->documento_identidad_jefe_inmediato'");
                /////////////////////////////////////////////////////
                $vistaConsulta = "departamento_seccion_contrato_empleado";
                $columnas      = SQL::obtenerColumnas($vistaConsulta);

                $consulta_departamento_seccion_contrato_empleado = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo_empresa='$codigo_empresa' AND documento_identidad_empleado='$documento_identidad_empleado' AND fecha_ingreso='$fecha_inicio' AND fecha_ingreso_sucursal='$fecha_ingreso_sucursal'", "", "fecha_inicia_departamento_seccion DESC", 0, 1);
                $datos_departamento_seccion_contrato_empleado    = SQL::filaEnObjeto($consulta_departamento_seccion_contrato_empleado);

                $codigo_departamento_empresa = $datos_departamento_seccion_contrato_empleado->codigo_departamento_empresa;
                $departamentos_empresa = HTML::generarDatosLista("departamentos_empresa","codigo","nombre");
                $riesgo = SQL::obtenerValor("departamentos_empresa", "riesgos_profesionales", "codigo = '$codigo_departamento_empresa'");


                $departamento_empresa = SQL::obtenerValor("departamentos_empresa", "nombre", "codigo='$datos_departamento_seccion_contrato_empleado->codigo_departamento_empresa'");
                $seccion_departamento = SQL::obtenerValor("secciones_departamentos", "nombre", "codigo='$datos_departamento_seccion_contrato_empleado->codigo_seccion_empresa'");

                $secciones_departamentos = HTML::generarDatosLista("secciones_departamentos", "codigo", "nombre", "codigo_departamento_empresa = ' $datos_departamento_seccion_contrato_empleado->codigo_departamento_empresa'");

                $llave_auxliares = $codigo_empresa . "|" . $datos_sucursal_contrato_empleados->codigo_anexo_contable;
                $auxiliares_contable = $lista = HTML::generarDatosLista("buscador_auxiliares_contables", "codigo", "descripcion", "codigo_id = '$llave_auxliares' AND codigo > '0'");
                /////////////////////////////////////////////////
                $transaccion_salario = SQL::obtenerValor("transacciones_contables_empleado", "descripcion", "codigo='$datos_sucursal_contrato_empleados->codigo_transaccion_salario'");
                $transaccion_auxilio_transporte = SQL::obtenerValor("transacciones_contables_empleado", "descripcion", "codigo='$datos_sucursal_contrato_empleados->codigo_transaccion_auxilio_transporte'");
                $transaccion_salud = SQL::obtenerValor("transacciones_contables_empleado", "descripcion", "codigo='$datos_sucursal_contrato_empleados->codigo_transaccion_salud'");
                $transaccion_pension = SQL::obtenerValor("transacciones_contables_empleado", "descripcion", "codigo='$datos_sucursal_contrato_empleados->codigo_transaccion_pension'");
                $periodo_pago = SQL::obtenerValor("planillas", "periodo_pago", "codigo='$datos_sucursal_contrato_empleados->codigo_planilla'");
                if($periodo_pago == 2 || $url_opcion == 1){
                    $forma_pago_auxilio = $datos_sucursal_contrato_empleados->forma_pago_auxilio;
                    $forma_descuento_salud = $datos_sucursal_contrato_empleados->forma_descuento_salud;
                    $forma_descuento_pension = $datos_sucursal_contrato_empleados->forma_descuento_pension;
                }else{
                    $forma_pago_auxilio = $textos["MENSUAL"];
                    $forma_descuento_salud = $textos["MENSUAL"];
                    $forma_descuento_pension = $textos["MENSUAL"];
                    $estado = "oculto";
                }
                //////obtengo el listado de los anexos de acuerdo a la empresa /////
                $listado_anexo = array();
                $consulta = SQL::seleccionar(array("auxiliares_contables,job_anexos_contables"), array("codigo_anexo_contable", "job_anexos_contables.descripcion"), "codigo_empresa='$codigo_empresa' AND codigo_anexo_contable=job_anexos_contables.codigo", "codigo_anexo_contable");
                $listado_anexo [''] = '';
                while($fila = SQL::filaEnArreglo($consulta)){
                    $listado_anexo[$fila[0]] = $fila[1];
                }
                //////Genero el listado de las transacciones de tiempo dependiendo de los anexos contable y auxiliares del plan contable/////
                //$condicion = "codigo='0' OR codigo_concepto_transaccion_tiempo in (select codigo from job_conceptos_transacciones_tiempo where tipo=1) AND  codigo_transaccion_contable IN (SELECT codigo FROM job_transacciones_contables_empleado WHERE codigo_contable IN (SELECT codigo_contable FROM job_plan_contable WHERE codigo_anexo_contable='$datos_sucursal_contrato_empleados->codigo_anexo_contable'))";
                $condicion ="codigo='0' OR codigo_concepto_transaccion_tiempo in (select codigo from job_conceptos_transacciones_tiempo where tipo=1)";
                $consulta = SQL::seleccionar(array("transacciones_tiempo"), array("codigo", "descripcion"), $condicion);
                $transacciones = array();
                $condicion_normal = "codigo>0 OR codigo_concepto_transaccion_tiempo in (select codigo from job_conceptos_transacciones_tiempo where tipo=1)";
                $consulta_normal = SQL::seleccionar(array("transacciones_tiempo"), array("codigo", "descripcion"), $condicion_normal);

                while($fila_normal = SQL::filaEnArreglo($consulta_normal)){
                    $transacciones_normal[$fila_normal[0]] = $fila_normal[1];
                }

                while($fila = SQL::filaEnArreglo($consulta)){
                    $transacciones[$fila[0]] = $fila[1];
                }
                //////Permitir Recargar transaccion de tiempo de pendiendo al anexo seleccionado////
                $recargar_transacciones_tiempo  = "recargarLista('id_anexos_contables','codigo_transaccion_normales'); recargarLista('id_anexos_contables','codigo_transaccion_extras'); recargarLista('id_anexos_contables','codigo_transaccion_recargo_nocturno');";
                $recargar_transacciones_tiempo .= "recargarLista('id_anexos_contables','codigo_transaccion_extras_nocturnas'); recargarLista('id_anexos_contables','codigo_transaccion_dominicales'); recargarLista('id_anexos_contables','codigo_transaccion_extras_dominicales');";
                $recargar_transacciones_tiempo .= "recargarLista('id_anexos_contables','codigo_transaccion_recargo_noche_dominicales'); recargarLista('id_anexos_contables','codigo_transaccion_extras_noche_dominicales');";
                ////////////////////////////////////////////////////////////////////////////////////

                $valor_minimo_ingreso_vario = SQL::obtenerValor("preferencias","valor","variable = 'valor_minimo_ingresos_varios' AND tipo_preferencia='1'");

                $campos_ocultos = ////Campos que se debetener en cuenta para armmar las condiciones a la hora de modificar////
                array(
                    HTML::campoOculto("oculto_sucursal_labora", $codigo_sucursal),
                    HTML::campoOculto("oculto_codigo_empresa", $codigo_empresa),
                    HTML::campoOculto("codigo_empresa", $codigo_empresa),
                    HTML::campoOculto("oculto_documento_identidad_empleado", $documento_identidad_empleado),
                    HTML::campoOculto("oculto_fecha_inicio", $fecha_inicio),
                    HTML::campoOculto("oculto_fecha_ingreso_surcursal", $fecha_ingreso_sucursal),
                    HTML::campoOculto("oculto_fecha_inicio_cargo", $fecha_ingreso_cargo),
                    HTML::campoOculto("oculto_id_turno_laboral", $codigo_turno_laboral),
                    HTML::campoOculto("oculto_id_planilla", $codigo_planilla),
                    HTML::campoOculto("termino_contrato", $terminio_contrato),
                    HTML::campoOculto("tipo_salario", $tipo_salario),
                    HTML::campoOculto("salario_minimo", $salario_minimo_actual),
                    HTML::campoOculto("preferencia_equivale_salario_integral", $preferencia_equivale_salario_integral),
                    HTML::campoOculto("oculto_id_tipo_contrato", $datos_contrato_empleados->codigo_tipo_contrato),
                    HTML::campoOculto("oculto_id_departamento", $datos_departamento_seccion_contrato_empleado->codigo_departamento_empresa),
                    HTML::campoOculto("oculto_id_cargo", $datos_cargo_contrato_empleados->codigo_cargo),
                    HTML::campoOculto("oculto_id_seccion", $datos_departamento_seccion_contrato_empleado->codigo_seccion_empresa),
                    HTML::campoOculto("oculto_riesgo_profesional", $riesgo),
                    HTML::campoOculto("oculto_fecha_vencimiento_contrato", ""),
                    HTML::campoOculto("oculto_documento_identidad_jefe", $datos_cargo_contrato_empleados->documento_identidad_jefe_inmediato),
                    HTML::campoOculto("oculto_id_anexos_contables", $datos_sucursal_contrato_empleados->codigo_anexo_contable),
                    HTML::campoOculto("oculto_id_auxiliar_contable", $datos_sucursal_contrato_empleados->codigo_auxiliar),
                    HTML::campoOculto("oculto_salario_mensual", $datos_sucursal_contrato_empleados->salario_mensual),
                    HTML::campoOculto("oculto_salario_diario", $datos_sucursal_contrato_empleados->valor_hora),
                    HTML::campoOculto("oculto_manejo_auxilio_transporte", $manejo_auxilio),
                    HTML::campoOculto("pago_mensual", $textos["MENSUAL"]),
                    HTML::campoOculto("2_quincena", $textos["PROPORCIONAL_QUINCENA"]),
                    HTML::campoOculto("3_quincena", $textos["SEGUNDA_QUINCENA"]),
                    HTML::campoOculto("semanal", $textos["SEMANAL"]),
                    HTML::campoOculto("4_semana", $textos["PRIMERA_SEMANA"]),
                    HTML::campoOculto("5_semana", $textos["SEGUNDA_SEMANA"]),
                    HTML::campoOculto("6_semana", $textos["TERCERA_SEMANA"]),
                    HTML::campoOculto("7_semana", $textos["CUARTA_SEMANA"]),
                    HTML::campoOculto("termino_contrato", $terminio_contrato),
                    HTML::campoOculto("lista_pagos", "0"),
                    HTML::campoOculto("oculto_codigo_entidad_salud", $datos_entidades_salud_empleados->codigo_entidad_salud),
                    HTML::campoOculto("oculto_direccion_atienden", $datos_entidades_salud_empleados->direccion_atencion),
                    HTML::campoOculto("oculto_direccion_urgencia", $datos_entidades_salud_empleados->direccion_urgencia),
                    HTML::campoOculto("oculto_fecha_inicial_salud", $fecha_entidad_salud),
                    HTML::campoOculto("oculto_fecha_inicial_pension", $fecha_entidad_pension),
                    HTML::campoOculto("oculto_codigo_entidad_pension", $entidad_pension),
                    HTML::campoOculto("oculto_codigo_entidad_salud", $datos_entidades_salud_empleados->codigo_entidad_salud),
                    HTML::campoOculto("oculto_direccion_atienden", $datos_entidades_salud_empleados->direccion_atencion),
                    HTML::campoOculto("oculto_direccion_urgencia", $datos_entidades_salud_empleados->direccion_urgencia),
                    HTML::campoOculto("oculto_fecha_inicial_salud", $fecha_entidad_salud),
                    HTML::campoOculto("oculto_fecha_inicial_pension", $fecha_entidad_pension),
                    HTML::campoOculto("oculto_codigo_entidad_pension", $entidad_pension),
                    HTML::campoOculto("oculto_codigo_transaccion_normales", $datos_sucursal_contrato_empleados->codigo_transaccion_normales),
                    HTML::campoOculto("oculto_codigo_transaccion_extras", $datos_sucursal_contrato_empleados->codigo_transaccion_extras),
                    HTML::campoOculto("oculto_codigo_transaccion_recargo_nocturno", $datos_sucursal_contrato_empleados->codigo_transaccion_recargo_nocturno),
                    HTML::campoOculto("oculto_codigo_transaccion_extras_nocturnas", $datos_sucursal_contrato_empleados->codigo_transaccion_extras_nocturnas),
                    HTML::campoOculto("oculto_codigo_transaccion_dominicales", $datos_sucursal_contrato_empleados->codigo_transaccion_dominicales),
                    HTML::campoOculto("oculto_codigo_transaccion_extras_dominicales", $datos_sucursal_contrato_empleados->codigo_transaccion_extras_dominicales),
                    HTML::campoOculto("oculto_codigo_transaccion_recargo_noche_dominicales", $datos_sucursal_contrato_empleados->codigo_transaccion_recargo_noche_dominicales),
                    HTML::campoOculto("oculto_codigo_transaccion_extras_noche_dominicales", $datos_sucursal_contrato_empleados->codigo_transaccion_extras_noche_dominicales),
                    HTML::campoOculto("oculto_codigo_transaccion_salario", $datos_sucursal_contrato_empleados->codigo_transaccion_salario),
                    HTML::campoOculto("oculto_codigo_transaccion_auxilio_transporte", $datos_sucursal_contrato_empleados->codigo_transaccion_auxilio_transporte),
                    HTML::campoOculto("oculto_forma_pago_auxilio", $datos_sucursal_contrato_empleados->forma_pago_auxilio),
                    HTML::campoOculto("oculto_codigo_transaccion_salud", $datos_sucursal_contrato_empleados->codigo_transaccion_salud),
                    HTML::campoOculto("oculto_forma_descuento_salud", $datos_sucursal_contrato_empleados->forma_descuento_salud),
                    HTML::campoOculto("oculto_codigo_transaccion_pension", $datos_sucursal_contrato_empleados->codigo_transaccion_pension),
                    HTML::campoOculto("oculto_forma_descuento_pension", $datos_sucursal_contrato_empleados->forma_descuento_pension),
                    HTML::campoOculto("oculto_fecha_inician_ingreso_varios", ""),
                    HTML::campoOculto("oculto_estado","M"),
                    HTML::campoOculto("mensaje_minimo_vario",$textos["MINIMO_VALOR_INGRESO_VARIOS"]),
                    HTML::campoOculto("valor_minimo_ingreso_vario",$valor_minimo_ingreso_vario),
                    HTML::campoOculto("mensaje_valor_ingreso_varios",$textos["VACIO_VALOR_VARIO"]),
                    HTML::campoOculto("fecha_salario",$fecha_salario),
                    HTML::campoOculto("oculto_dias_mes",$datos_sucursal_contrato_empleados->dias_mes),
                    HTML::campoOculto("oculto_horas_mes",$datos_sucursal_contrato_empleados->horas_mes)
                );
                if($url_opcion == 1){
                    $definicion_autocompletar = "autoCompletableLocal(this,'codigo_transaccion'," . $conceptos_contables['salario'] . ");";
                } else {
                    $definicion_autocompletar = "autoCompletableLocalModificar(this,'codigo_transaccion'," . $conceptos_contables['salario'] . ");";
                }
                ////////////////////////////////////////////////
                if($url_opcion_basica == 1 || $url_opcion == 1){
                    if($url_opcion == 1){
                        $fecha_inicio_sucursal = $fecha_ingreso_sucursal;
                    }else{
                        $sucursales = HTML::generarDatosLista("sucursales", "codigo", "nombre", "codigo_empresa ='$codigo_empresa' AND codigo > '0' AND codigo!='$codigo_sucursal'");
                        $date_r = getdate(strtotime($fecha_ingreso_sucursal));
                        $fecha_inicio_sucursal = date("Y-m-d", mktime(($date_r["hours"]), ($date_r["minutes"]), ($date_r["seconds"]), ($date_r["mon"]), ($date_r["mday"] + 1), ($date_r["year"])));
                    }
                    $formularios["PESTANA_BASICA"] = array(
                        array(
                            HTML::listaSeleccionSimple("*sucursal_labora", $textos["SUCURSAL_LABORA"],$sucursales,"", array("title" => $textos["AYUDA_SUCURSAL_LABORA"])),
                            HTML::campoTextoCorto("*selector1", $textos["NOMBRE_COMPLETO"], 40, 255, $nombre_empleado, array("title" => $textos["AYUDA_NOMBRE_COMPLETO"], "class" => "autocompletable", "onBlur" => "CargarParafiscales(); validarEmpleado('$documento_identidad_empleado'); CargarRiesgoProfesional();"))
                           .HTML::campoOculto("documento_identidad_empleado", $documento_identidad_empleado)
                        ),
                        array(
                            HTML::campoTextoCorto("*fecha_inicio", $textos["FECHA_INICIAL"], 10, 10, $fecha_inicio_sucursal, array("title" => $textos["AYUDA_FECHA_INICIAL"], "class" => "selectorFecha")),
                            HTML::listaSeleccionSimple("*id_turno_laboral", $textos["TURNO_LABORAL"], HTML::generarDatosLista("turnos_laborales", "codigo", "descripcion", "codigo > '0'"), $codigo_turno_laboral, array("title" => $textos["AYUDA_TURNO_LABORAL"])),
                        ),
                        array(
                            HTML::listaSeleccionSimple("*id_departamento", $textos["DEPARTAMENTOS"], $departamentos_empresa,$codigo_departamento_empresa, array("title" => $textos["AYUDA_DEPARTAMENTOS"], "onChange" => "recargarLista('id_departamento','id_seccion'); CargarRiesgoProfesional();")),
                            HTML::listaSeleccionSimple("*id_seccion", $textos["SECCION"], $secciones_departamentos, $datos_departamento_seccion_contrato_empleado->codigo_seccion_empresa, array("title" => $textos["AYUDA_SECCION"], "onFocus" => "cargarAnexos();")),
                            HTML::campoTextoCorto("riesgo_profesional", $textos["RIESGO_PROFESIONAL"], 10, 255,$riesgo, array("title" => $textos["AYUDA_RIESGO_PROFESIONAL"], "class" => "")),
                        ), $campos_ocultos
                    );
                }

                $codigo_entidad_salud = $datos_entidades_salud_empleados->codigo_entidad_salud;
                $nombre_entidad_salud =  SQL::obtenerValor("seleccion_entidades_parafiscales_salud","SUBSTRING_INDEX(descripcion,'|',1)","id = '$codigo_entidad_salud'");

                $codigo_entidad_pension = $entidad_pension;
                $nombre_entidad_pension = SQL::obtenerValor("seleccion_entidades_parafiscales_pension","SUBSTRING_INDEX(descripcion,'|',1)","id = '$codigo_entidad_pension'");

                if($url_opcion_contrato == 1 || $url_opcion == 1){

                    if($url_opcion_basica == 1 ){
                        $campo_oculto = 'oculto';
                    }else{
                        $campo_oculto = '';
                    }

                    $formularios["PESTANA_CONTRATO"] = array(
                        array(
                            HTML::listaSeleccionSimple("*id_tipo_contrato", $textos["TIPO_CONTRATO"], $tipo_contrato, $datos_contrato_empleados->codigo_tipo_contrato, array("title" => $textos["AYUDA_TIPO_CONTRATO"], "onChange" => "tiposTerminosDecontrato();","class" => $campo_oculto )),
                            HTML::listaSeleccionSimple("*id_cargo", $textos["CARGO"], HTML::generarDatosLista("cargos", "codigo", "nombre", "codigo > '0'"),$datos_cargo_contrato_empleados->codigo_cargo, array("title" => $textos["AYUDA_CARGO"])) . "<br/><br/>",
                            HTML::contenedor(HTML::campoTextoCorto("*fecha_vencimiento_contrato", $textos["FECHA_VENCIMIENTO"], 10, 10, date("Y-m-d"), array("title" => $textos["AYUDA_FECHA_VENCIMIENTO"], "class" => "selectorFecha")), array("id" => "fecha_vencimiento_contrato", "class" => "oculto"))
                        ),
                        array(
                            HTML::campoTextoCorto("selector7", $textos["NOMBRE_JEFE"], 40, 255, $nombre_jefe, array("title" => $textos["AYUDA_NOMBRE_COMPLETO_DEL_JEFE"], "class" => "autocompletable", "onBlur" => "CargarParafiscales(); validarEmpleado(); CargarRiesgoProfesional();"))
                           .HTML::campoOculto("documento_identidad_jefe", $datos_cargo_contrato_empleados->documento_identidad_jefe_inmediato),
                            HTML::listaSeleccionSimple("*id_anexos_contables", $textos["ANEXO_CONTABLE"], $listado_anexo, $datos_sucursal_contrato_empleados->codigo_anexo_contable, array("title" => $textos["AYUDA_ANEXO_CONTABLE"], "onChange" => "cargarAnexos();limpiarCampoTrasaccion();" . $recargar_transacciones_tiempo)),
                        ), array(
                            HTML::listaSeleccionSimple("id_auxiliar_contable", $textos["AUXILIAR_CONTABLE"], $auxiliares_contable, $datos_sucursal_contrato_empleados->codigo_auxiliar, array("title" => $textos["AYUDA_AUXILIAR_CONTABLE"])),
                            HTML::listaSeleccionSimple("*id_planilla", $textos["PLANILLA"], HTML::generarDatosLista("planillas", "codigo", "descripcion", "codigo > '0' "), $codigo_planilla, array("title" => $textos["AYUDA_PLANILLA"], "onChange" => "cargarPagoTransaccion('id_planilla');")),
                            HTML::campoOculto("fecha_salario",$fecha_salario)
                       ),
                        array(
                            HTML::campoTextoCorto("salario_mensual", $textos["SALARIOS_MENSUAL"], 10, 255,round($salario_actual_empleado), array("title" => $textos["AYUDA_SALARIO"], "onkeyup" => "CalcularSalarioDiario();", "onKeyPress" => "return campoEntero(event)", "onblur" => "equivaleIntegral('" . $textos["ERROR_SALARIO_INTEGRAL"] . "');")),
                            HTML::campoTextoCorto("dias_mes", $textos["DIAS_MES"], 3, 3,$datos_sucursal_contrato_empleados->dias_mes, array("title" => $textos["AYUDA_DIAS_MES"], "onKeyPress" => "return campoEntero(event)","onKeyUp" => "habilitarHorasMes(this);")),
                            HTML::campoTextoCorto("horas_mes", $textos["HORAS_MES"], 3, 3,$datos_sucursal_contrato_empleados->horas_mes, array("title" => $textos["AYUDA_HORAS_MES"], "onKeyPress" => "return campoEntero(event)","onKeyUp" => "validarDiasHoras(this);")),
                            HTML::campoTextoCorto("salario_diario", $textos["SALARIO_DIARIO"], 10, 255,round($valor_hora_empleado), array("title" => $textos["AYUDA_SALARIO"], "disabled" => "disabled"))
                           .HTML::campoOculto("mensaje_dias_horas",$textos["ERROR_HORAS_NO_CONCUERDAN_DIAS"])
                        ),
                        array(
                            HTML::mostrarDato("auxilio_trans", $textos["AUXILIO_TRANSPORTE"], ""),
                            HTML::marcaSeleccion("auxilio_transporte", $textos["NO"], 1, $recibe_no, array("id" => "auxilio_no", "onclick" => "auxilocamposCheck('auxilio_no')")) .
                            HTML::marcaSeleccion("auxilio_transporte", $textos["SI"], 2, $recibe_si, array("id" => "auxilio_si", "onclick" => "auxilocamposCheck('auxilio_si')")) . '<br/><br/>' ,
                            HTML::listaSeleccionSimple("manejo_auxilio_transporte", "", $MANEJO_SALARIO_MINIMO_MENOR_2, $manejo_auxilio, array("title" => $textos["AUXILIO_TRANSPORTE"]))
                        ), $campos_ocultos
                    );
                }

                if(($url_opcion_modificar_salario == 1)){

                    $fecha_hoy     = date("Y-m-d");

                    if ($fecha_salario > $fecha_hoy){
                        $fecha_inicial_salario = $fecha_salario;
                    } else {
                        $fecha_inicial_salario = $fecha_hoy;
                    }
                    $formularios["PESTANA_MODIFICAR_SALARIO"] = array(
                        array(
                            HTML::campoTextoCorto("*fecha_inicial_salario", $textos["FECHA_INICIA_SALARIO"], 10, 10,$fecha_inicial_salario, array("title" => $textos["AYUDA_FECHA_INICIA_SALARIO"], "class" => "selectorFechaBloquear", "onchange"=>"fechaRetroactivo()")),
                            HTML::campoOculto("minDate",$fecha_salario),
                            HTML::campoTextoCorto("salario_mensual", $textos["SALARIOS_MENSUAL"], 10, 255,round($salario_actual_empleado), array("title" => $textos["AYUDA_SALARIO"],"onkeyup" => "CalcularSalarioDiarioModificar();","onKeyPress" => "return campoEntero(event)")),
                            HTML::campoTextoCorto("salario_diario", $textos["SALARIO_DIARIO"], 10, 255,round($valor_hora_empleado), array("title" => $textos["AYUDA_SALARIO"], "disabled" => "disabled")),
                            HTML::campoOculto("fecha_salario",$fecha_salario)
                        ),
                        array(
                            HTML::campoTextoCorto("*fecha_retroactivo", $textos["FECHA_RETROACTIVO"], 10, 10,$fecha_inicial_salario, array("title" => $textos["AYUDA_FECHA_RETROACTIVO"], "class"=>"selectorFecha", "onchange"=>"validarFechaRetroactivo()"))
                        ),
                        array(
                            HTML::mostrarDato("auxilio_trans", $textos["AUXILIO_TRANSPORTE"], ""),
                            HTML::marcaSeleccion("auxilio_transporte", $textos["NO"], 1, $recibe_no, array("id" => "auxilio_no", "onclick" => "auxilocamposCheck('auxilio_no')")) .
                            HTML::marcaSeleccion("auxilio_transporte", $textos["SI"], 2, $recibe_si, array("id" => "auxilio_si", "onclick" => "auxilocamposCheck('auxilio_si')")) . '<br/><br/>' ,
                            HTML::listaSeleccionSimple("manejo_auxilio_transporte", "", $MANEJO_SALARIO_MINIMO_MENOR_2, $manejo_auxilio, array("title" => $textos["AUXILIO_TRANSPORTE"],"class" => $oculto_listado))
                        ),
                        array(
                            HTML::campoTextoCorto("selector4", $textos["TRANSACCION_CONTABLE_AUXILIO"], 40, 255, $transaccion_auxilio_transporte, array("title" => $textos["AYUDA_TRANSACCION_CONTABLE"],"onFocus" => "autoCompletableLocal(this,'codigo_transaccion_auxilio_transporte',".$conceptos_contables['auxilio_transporte'].");", $estado_transaccion_auxilio => $estado_transaccion_auxilio))
                           .HTML::campoOculto("codigo_transaccion_auxilio_transporte", $datos_sucursal_contrato_empleados->codigo_transaccion_auxilio_transporte)
                        ),
                        array(
                           HTML::listaSeleccionSimple("forma_pago_auxilio", $textos["PERIODO_PAGO"], $QUINCENA,$datos_sucursal_contrato_empleados->forma_pago_auxilio, array("title" => $textos["AYUDA_ENTIDAD_PENSION"], "class" => "forma_pago", "id" => "forma_pago_auxilio", $estado_transaccion_auxilio => $estado_transaccion_auxilio))
                        ),
                        $campos_ocultos
                    );
                }

                if($url_opcion == 1){
                    $clase_calendario_salud   = "selectorFecha";
                    $clase_calendario_pension = "selectorFecha";
                }else{
                    $clase_calendario_salud  = "selectorFechaSalud";
                    $clase_calendario_pension = "selectorFechaPension";
                }

                if($url_opcion_parafiscales == 1 || $url_opcion == 1){
                    $formularios["PESTANA_PARAFISCALES"] = array(
                        array(
                            HTML::campoTextoCorto("*selectorSalud", $textos["ENTIDAD_SALUD"],60,255,$nombre_entidad_salud,array("title" => $textos["AYUDA_ENTIDAD_SALUD"],"class" => "autocompletable")).
                            HTML::campoOculto("codigo_entidad_salud",$codigo_entidad_salud),
                            //HTML::listaSeleccionSimple("*codigo_entidad_salud", $textos["ENTIDAD_SALUD"], HTML::generarDatosLista("entidades_parafiscales", "codigo", "nombre", "codigo =0 OR salud = '1'"), $datos_entidades_salud_empleados->codigo_entidad_salud, array("title" => $textos["AYUDA_ENTIDAD_SALUD"])),
                            HTML::campoTextoCorto("*fecha_inicial_salud", $textos["FECHA_INICIA"], 10, 255, $fecha_entidad_salud, array("title" => $textos["AYUDA_FECHA_SALUD"], "class" => $clase_calendario_salud)),
                        ),
                        array(
                            HTML::campoTextoCorto("direccion_atienden", $textos["DIRECCION_ATIENDEN"], 25, 255, $datos_entidades_salud_empleados->direccion_atencion, array("title" => $textos["AYUDA_DIRECCION_ATIENDEN"]))
                        ),
                        array(
                            HTML::campoTextoCorto("direccion_urgencia", $textos["DIRECCION_URGENCIA"], 25, 255, $datos_entidades_salud_empleados->direccion_urgencia, array("title" => $textos["AYUDA_DIRECCION_URGENCIA"]))
                        ),
                        array(
                            HTML::campoTextoCorto("*selectorPension", $textos["ENTIDAD_PENSION"],60,255, $nombre_entidad_pension,array("title" => $textos["AYUDA_ENTIDAD_PENSION"],"class" => "autocompletable")).
                            HTML::campoOculto("codigo_entidad_pension",$codigo_entidad_pension),
                            //HTML::listaSeleccionSimple("codigo_entidad_pension", $textos["ENTIDAD_PENSION"], HTML::generarDatosLista("entidades_parafiscales", "codigo", "nombre", "codigo =0 OR pension = '1'"), $entidad_pension, array("title" => $textos["AYUDA_ENTIDAD_PENSION"])),
                            HTML::contenedor(HTML::campoTextoCorto("fecha_inicial_pension", $textos["FECHA_INICIA"], 10, 255, $fecha_entidad_pension, array("title" => $textos["AYUDA_FECHA_PENSION"], "class" => $clase_calendario_pension)),
                                array(
                                    "id" => "pensiones",
                                )
                            )
                        ), $campos_ocultos
                    );
                }

                if ($url_opcion_transacciones_fijas == 1 || $url_opcion == 1) {

                    $formularios["TRANSACCION_FIJAS"] = array(
                        array(
                            HTML::campoTextoCorto("selector3", $textos["TRANSACCION_CONTABLE_SALARIO"], 25, 255, $transaccion_salario, array("title" => $textos["AYUDA_TRANSACCION_CONTABLE"], "onFocus" => "autoCompletableLocal(this,'codigo_transaccion_salario',".$conceptos_contables['salario'].");"))
                           .HTML::campoOculto("codigo_transaccion_salario", $datos_sucursal_contrato_empleados->codigo_transaccion_salario)),
                        array(
                            HTML::campoTextoCorto("selector4", $textos["TRANSACCION_CONTABLE_AUXILIO"], 25, 255, $transaccion_auxilio_transporte, array("title" => $textos["AYUDA_TRANSACCION_CONTABLE"],"onFocus" => "autoCompletableLocal(this,'codigo_transaccion_auxilio_transporte',".$conceptos_contables['auxilio_transporte'].");", $estado_transaccion_auxilio => $estado_transaccion_auxilio))
                           .HTML::campoOculto("codigo_transaccion_auxilio_transporte", $datos_sucursal_contrato_empleados->codigo_transaccion_auxilio_transporte)
                           ,"&nbsp;&nbsp;&nbsp;" . HTML::listaSeleccionSimple("forma_pago_auxilio", $textos["PERIODO_PAGO"], $QUINCENA,$datos_sucursal_contrato_empleados->forma_pago_auxilio, array("title" => $textos["AYUDA_ENTIDAD_PENSION"], "class" => "forma_pago", "id" => "forma_pago_auxilio", $estado_transaccion_auxilio => $estado_transaccion_auxilio))),
                        array(HTML::campoTextoCorto("selector5", $textos["TRANSACCION_CONTABLE_SALUD"], 25, 255, $transaccion_salud, array("title" => $textos["AYUDA_TRANSACCION_CONTABLE"], "onFocus" => "autoCompletableLocal(this,'codigo_transaccion_salud',".$conceptos_contables['salud'].");"))
                            . HTML::campoOculto("codigo_transaccion_salud", $datos_sucursal_contrato_empleados->codigo_transaccion_salud)
                            , "&nbsp;&nbsp;&nbsp;" . "&nbsp;&nbsp;&nbsp;" . HTML::listaSeleccionSimple("forma_descuento_salud", $textos["PERIODO_PAGO"], $QUINCENA,$datos_sucursal_contrato_empleados->forma_descuento_salud, array("title" => $textos["AYUDA_ENTIDAD_PENSION"], "class" => "forma_pago"))),
                        array(HTML::campoTextoCorto("selector6", $textos["TRANSACCION_CONTABLE_PENSION"], 25, 255, $transaccion_pension, array("title" => $textos["AYUDA_TRANSACCION_CONTABLE"],"onFocus" => "autoCompletableLocal(this,'codigo_transaccion_pension',".$conceptos_contables['pension'].");"))
                            .HTML::campoOculto("codigo_transaccion_pension", $datos_sucursal_contrato_empleados->codigo_transaccion_pension)
                            ,"&nbsp;&nbsp;&nbsp;" . "&nbsp;&nbsp;&nbsp;" . HTML::listaSeleccionSimple("forma_descuento_pension", $textos["PERIODO_PAGO"], $QUINCENA,$datos_sucursal_contrato_empleados->forma_descuento_pension, array("title" => $textos["AYUDA_ENTIDAD_PENSION"], "class" => "forma_pago"))
                        ), $campos_ocultos
                    );
                }

                $vistaConsulta             = "ingresos_varios_empleados";
                $consulta_varios_empleados = SQL::seleccionar(array($vistaConsulta), array("*"), "codigo_empresa='$codigo_empresa' AND documento_identidad_empleado='$documento_identidad_empleado' AND fecha_ingreso='$fecha_inicio' AND estado ='1'");

                $varios_empleados       = array();
                $llaves_ingresos_varios = array();

                if (SQL::filasDevueltas($consulta_varios_empleados)) {
                    while($transaccion_tiempo = SQL::filaEnObjeto($consulta_varios_empleados)){
                        $otras_transaccion = SQL::obtenerValor("transacciones_contables_empleado", "descripcion", "codigo='$transaccion_tiempo->codigo_transaccion_tiempo'");
                        $nombreconceptoContable = SQL::obtenerValor("seleccion_transacciones_contables_empleado","SUBSTRING_INDEX(nombre,'|',1)","id='$transaccion_tiempo->codigo_transaccion_tiempo'");

                        $celda  = HTML::campoOculto("idValidarDatos[" . $transaccion_tiempo->codigo_transaccion_tiempo . "]", $transaccion_tiempo->codigo_transaccion_tiempo, array("class" => "idValidarDatos"));
                        $celda .= HTML::campoOculto("IdConceptoContable[]", $transaccion_tiempo->codigo_transaccion_tiempo, array("class" => "IdConceptoContable"));

                        $celda .= HTML::campoOculto("valorIngreso[]",round($transaccion_tiempo->valor), array("class" => "valorIngreso"));
                        $celda .= HTML::campoOculto("nombreconceptoContable[]",$nombreconceptoContable, array("class" => "nombreconceptoContable"));
                        $celda .= HTML::campoOculto("PeriodoPagoTabla[]", $transaccion_tiempo->periodo_pago, array("class" => "PeriodoPagoTabla"));
                        $celda .= HTML::campoOculto("ConceptoContableTabla[]", $transaccion_tiempo->codigo_transaccion_tiempo, array("class" => "ConceptoContableTabla"));
                        $celda .= HTML::campoOculto("estado[]",1, array("class" => "estado"));
                        $celda .= HTML::boton("botonRemoverextras", "", "removerItems(this);", "eliminar");
                        $celda .= "&nbsp;";
                        $celda .= HTML::boton("botonModificar", "","removerModificarTransaccion(this);", "modificar");
                        $llaves_ingresos_varios[] = $transaccion_tiempo->codigo_transaccion_tiempo . ',' . $transaccion_tiempo->fecha_inicio_transacion_tiempo;
                        $descripcion_transaccion = SQL::obtenerValor("transacciones_contables_empleado", "descripcion", "codigo='$transaccion_tiempo->codigo_transaccion_tiempo'");
                        $varios_empleados[] = array(
                            "id",
                            $celda,
                            $otras_transaccion,
                            $descripcion_transaccion,
                            $formas_pago_todo[$transaccion_tiempo->periodo_pago],
                            round($transaccion_tiempo->valor),
                            $transaccion_tiempo->fecha_inicio_transacion_tiempo
                        );
                    }
                }
                if($url_opcion_contables == 1 || $url_opcion == 1){
                    $formularios["PESTANA_TRANSACCIONES_CONTABLES"] = array(
                        array(
                            HTML::campoTextoCorto("selector2", $textos["TRANSACCION_CONTABLE"], 25, 255, "", array("title" => $textos["AYUDA_TRANSACCION_CONTABLE"], "onFocus" => $definicion_autocompletar . " cargarPagoTransaccion('oculto_id_planilla');", "onblur" => "estadosChecks('0');"))
                            .HTML::campoOculto("codigo_transaccion", "")
                            ,HTML::campoTextoCorto("*valor_ingreso_vario", $textos["VALOR_INGRESO"], 10, 255, "", array("title" => $textos["AYUDA_FECHA_SALUD"],"onKeyPress" => "return campoEntero(event)"))
                        ),
                        array(
                            HTML::contenedor(
                                HTML::listaSeleccionSimple("forma_descuento_ingresos_varios", $textos["PERIODO_PAGO"], $QUINCENA, "", array("title" => $textos["AYUDA_ENTIDAD_PENSION"], "id" => "forma_descuento_ingresos_varios", "class" => "forma_pago")),
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
                            HTML::contenedor(HTML::boton("botonRemoverextras", "", "removerItems(this);", "eliminar"), array("id" => "botonRemoverextras", "style" => "display: none")),
                            HTML::contenedor(HTML::boton("botonAgregar", $textos["AGREGAR"], "adicionarTransaccionTabla('" . $textos["ERROR_EXISTE_TRANSACCION"] . "','oculto_id_planilla');", "adicionar", array("onfocus" => "VerificarHorasExtras();"))),
                            HTML::contenedor(HTML::boton("botonModificar","", "removerModificarTransaccion(this);","modificar"), array("title" => $textos["AYUDA_REMOVER_TRASACCION"],"id" => "botonModificar", "style" => "display: none")),
                            HTML::contenedor(HTML::generarTabla(
                                    array("id","","ID_TRANSACCION_CONTABLE","DESCRIPCION","PERIODO_PAGO","VALOR_INGRESO","FECHA_INICIO_TRANSACCION"),
                                    $varios_empleados,
                                    array("I", "I", "I","I","I","I"),
                                    "listaItemsPagos",
                                    false
                                )
                            )), $campos_ocultos
                    );
                }
                if($url_opcion_transacciones_tiempo == 1 || $url_opcion == 1){
                    $formularios["PESTANA_TRANSACCIONES_TIEMPO"] = array(
                        array(
                            HTML::listaSeleccionSimple("*codigo_transaccion_normales", $textos["HORAS_NORMALES"], $transacciones_normal, $datos_sucursal_contrato_empleados->codigo_transaccion_normales, array("title" => $textos["AYUDA_EXTRAS_NORMALES"])),
                            HTML::listaSeleccionSimple("codigo_transaccion_extras", $textos["HORAS_EXTRAS"], $transacciones, $datos_sucursal_contrato_empleados->codigo_transaccion_extras, array("title" => $textos["AYUDA_EXTRAS_NOCTURNAS"]))
                        ),
                        array(
                            HTML::listaSeleccionSimple("codigo_transaccion_recargo_nocturno", $textos["HORAS_RECARGO_NOCTURNA"], $transacciones, $datos_sucursal_contrato_empleados->codigo_transaccion_recargo_nocturno, array("title" => $textos["AYUDA_HORAS_RECARGO_NOCTURNA"])),
                            HTML::listaSeleccionSimple("codigo_transaccion_extras_nocturnas", $textos["HORAS_EXTRAS_NOCTURNAS"], $transacciones, $datos_sucursal_contrato_empleados->codigo_transaccion_extras_nocturnas, array("title" => $textos["AYUDA_EXTRAS_NOCTURNAS"]))
                        ),
                        array(
                            HTML::listaSeleccionSimple("codigo_transaccion_dominicales", $textos["HORAS_DOMINICALES"], $transacciones, $datos_sucursal_contrato_empleados->codigo_transaccion_dominicales, array("title" => $textos["AYUDA_HORAS_DOMINICALES"])),
                        ),
                        array(
                            HTML::listaSeleccionSimple("codigo_transaccion_extras_dominicales", $textos["HORAS_EXTRAS_DOMINGOS_FESTIVOS"], $transacciones, $datos_sucursal_contrato_empleados->codigo_transaccion_extras_dominicales, array("title" => $textos["AYUDA_EXTRAS_DOMINICALES"])),
                        ),
                        array(
                            HTML::listaSeleccionSimple("codigo_transaccion_recargo_noche_dominicales", $textos["HORAS_RECARGO_NOCHE_DOMINICAL"], $transacciones, $datos_sucursal_contrato_empleados->codigo_transaccion_recargo_noche_dominicales, array("title" => $textos["AYUDA_HORAS_RECARGO_NOCHE_DOMINICAL"])),
                        ),
                        array(
                            HTML::listaSeleccionSimple("codigo_transaccion_extras_noche_dominicales", $textos["HORAS_EXTRAS_DOMINGOS_FESTIVOS_NOCHE"], $transacciones, $datos_sucursal_contrato_empleados->codigo_transaccion_extras_noche_dominicales, array("title" => $textos["AYUDA_EXTRAS_DOMINICALES_NOCTURNAS"])),
                        ), $campos_ocultos
                    );
                }
                if(count($llaves_ingresos_varios)==0){
                    $datos_envio            = $url_id . "|" . $url_opciones;
                }else
                {
                    $llaves_ingresos_varios = implode("|", $llaves_ingresos_varios);
                    $datos_envio            = $url_id . "|" . $url_opciones . "#" . $llaves_ingresos_varios;
                }
                $botones = array(
                    HTML::boton("botonAceptar", $textos["ACEPTAR"], "modificarItem('$datos_envio');", "aceptar")
                );

                $contenido = HTML::generarPestanas($formularios, $botones);
            }
        }
    }
    /////Enviar datos para la generación del formulario al script que originó la petición/////
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

}elseif(!empty($forma_procesar)){
    $error     = false;
    $mensaje   = $textos["ITEM_MODIFICADO"];
    $insertar  = true;
    $continuar = false;

    $datos_llegada = explode("#", $forma_id);
    $datos_llave = $datos_llegada[0];
    ////llaves_de_los_anteriores_ingresos_varios////
    $datos_ingresos_varios =array();
    if(isset($datos_llegada[1])){
        $datos_ingresos_varios = $datos_llegada[1];
        $datos_ingresos_varios = explode("|", $datos_ingresos_varios);
    }
    ///////////////////////////////////////////////
    $llave_pricipal               = explode("|", $datos_llave);
    $codigo_empresa               = $llave_pricipal[0];
    $documento_identidad_empleado = $llave_pricipal[1];
    $fecha_ingreso                = $llave_pricipal[2];

    $opcion                       = $llave_pricipal[3];
    $pestana_basica               = $llave_pricipal[4];
    $petana_contrato              = $llave_pricipal[5];
    $pestana_parafiscales         = $llave_pricipal[6];
    $pestana_transacciones_fijas  = $llave_pricipal[7];
    $pestana_contables            = $llave_pricipal[8];
    $pestana_transacciones_tiempo = $llave_pricipal[9];
    $pestana_modificar_salario    = $llave_pricipal[10];

    $condicion = "codigo_empresa='$codigo_empresa' AND documento_identidad_empleado='$documento_identidad_empleado' AND fecha_ingreso='$fecha_ingreso' ";
    $prefijo   = "forma_oculto_";

    $terminio_contrato = SQL::obtenerValor("tipos_contrato", "termino_contrato", "codigo='${$prefijo . "id_tipo_contrato"}'");
    $es_pensionado = SQL::obtenerValor("aspirantes", "pensionado", "documento_identidad = '${$prefijo . "documento_identidad_empleado"}'");


    if($opcion == 1){

        ////////////////validar que el numero de hora ingresada concuerde con el numero de dias//////////////////
        $numero_dias_horas = (int)$forma_horas_mes/24;
        if($numero_dias_horas > (int)$forma_dias_mes){
            $continuar = true;
        }
        /////////////////////////////////////////////////////////////////////////////////////////////////////////
        if (SQL::existeItem("ingreso_empleados", "documento_identidad_empleado", ${$prefijo . "documento_identidad_empleado"}, "documento_identidad_empleado!=$documento_identidad_empleado")) {
            $error = true;
            $mensaje = $textos["ERROR_EXISTE_EMPLEADO"];
        } elseif (empty($forma_documento_identidad_empleado)) {
            $error = true;
            $mensaje = $textos["ERROR_VACIO_EMPLEADO"];  //$forma_id_auxiliar_contable
        } elseif (empty($forma_sucursal_labora)) {
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
        }elseif (!isset($forma_id_auxiliar_contable) && $forma_id_anexos_contables!="") {
            $error = true;
            $mensaje = $textos["ERROR_VACIO_AUXILIAR"];
        } elseif (empty($forma_id_seccion)) {
            $error = true;
            $mensaje = $textos["ERROR_VACIO_SECCIONES_CODIGO"];
        } elseif (empty($forma_salario_mensual)) {
            $error = true;
            $mensaje = $textos["ERROR_VACIO_SALARIO_CODIGO"];
        } elseif (empty($forma_fecha_inicial_salud) && ($terminio_contrato == "1" || $terminio_contrato == "2")) {
            $error = true;
            $mensaje = $textos["ERROR_VACIO_FECHA_INICIAL_SALUD"];
        } elseif ($es_pensionado == 0 && empty($forma_fecha_inicial_pension) && ($terminio_contrato == "1" || $terminio_contrato == "2")) {
            $error = true;
            $mensaje = $textos["ERROR_VACIO_FECHA_INICIAL_PENSION"];
        } elseif (empty($forma_codigo_transaccion_salario)) {
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
                $auxilio_transporte = $forma_manejo_auxilio_transporte;
            }elseif ($forma_auxilio_transporte == "1"){
                $auxilio_transporte = 5;
            }else{
                $auxilio_transporte = $forma_manejo_auxilio_transporte;
            }
            $terminio_contrato = SQL::obtenerValor("tipos_contrato", "termino_contrato", "codigo='$forma_id_tipo_contrato'");

            if($terminio_contrato == "1"){
                $fecha_vencimiento = $forma_fecha_vencimiento_contrato;
            }else{
                $fecha_vencimiento = "";
            }

            $datos = array(
                "codigo_empresa"               => $forma_codigo_empresa,
                "documento_identidad_empleado" => $forma_documento_identidad_empleado,
                "fecha_ingreso"                => $forma_fecha_inicio,
                "fecha_vencimiento_contrato"   => $fecha_vencimiento,
                "fecha_retiro"                 => "0000-00-00",
                "codigo_motivo_retiro"         => 0,
                "riesgo_profesional"           => $forma_riesgo_profesional,
                "manejo_auxilio_transporte"    => $auxilio_transporte,
                "codigo_sucursal_activo"       => $forma_sucursal_labora
            );
            $insertar = SQL::modificar("ingreso_empleados", $datos, $condicion);
            if($terminio_contrato == "1" || $terminio_contrato == "3"){
                $fecha_cambio = $forma_fecha_vencimiento_contrato;
            }elseif($terminio_contrato == "2" || $terminio_contrato == "4"){
                $fecha_cambio = "";
            }else{
                $fecha_cambio = "";
            }

            $datos = array(
                "codigo_empresa"               => $forma_codigo_empresa,
                "documento_identidad_empleado" => $forma_documento_identidad_empleado,
                "fecha_ingreso"                => $forma_fecha_inicio,
                "fecha_contrato"               => "",
                "codigo_tipo_contrato"         => $forma_id_tipo_contrato,
                "fecha_cambio_contrato"        => $fecha_cambio,
            );
            $insertar = SQL::modificar("contrato_empleados", $datos, $condicion . " AND fecha_contrato=''");

            $periodoPago = SQL::obtenerValor("planillas", "periodo_pago", "codigo = '$forma_id_planilla'");

            ////Si no viaja los datos por defecto siempre seran 1///
            if(!isset($forma_forma_pago_auxilio)){
                $forma_forma_pago_auxilio = '1';
            }
            if(!isset($forma_forma_descuento_salud)){
                $forma_forma_descuento_salud = '1';
            }
            if(!isset($forma_forma_descuento_salud)){
                $forma_forma_descuento_pension = '1';
            }
            //////// Validacion de campos transacciones auxilo de transporte y pension /////////////
            if(!isset($forma_codigo_transaccion_auxilio_transporte)){
                $forma_codigo_transaccion_auxilio_transporte = "0";
            }
            if(!isset($forma_codigo_transaccion_pension)){
                $forma_codigo_transaccion_pension = "0";
            }
            if(!isset($forma_forma_pago_auxilio)){
                $forma_forma_pago_auxilio = "1";
            }
            if(!isset($forma_forma_descuento_pension)){
                $forma_forma_descuento_pension = "1";
            }
            ///////////calculo de proporcion///////////////
            $salario_diario   = ($forma_salario_mensual / $forma_dias_mes);
            $numero_horas_dias =  $forma_horas_mes / $forma_dias_mes;
            $valor_hora     = (int) $salario_diario / $numero_horas_dias;
            ///////////////////////////////////////////////
            //////////////////////////////////////////////////////////////////////////////////////////
            $datos = array(
                "codigo_empresa"                               => $forma_codigo_empresa,
                "documento_identidad_empleado"                 => $forma_documento_identidad_empleado,
                "fecha_ingreso"                                => $forma_fecha_inicio,
                "codigo_sucursal"                              => $forma_sucursal_labora,
                "fecha_ingreso_sucursal"                       => $forma_fecha_inicio,
                "codigo_anexo_contable"                        => $forma_id_anexos_contables,
                "codigo_empresa_auxiliar"                      => $empresa_auxiliar,
                "codigo_auxiliar"                              => $forma_id_auxiliar_contable,
                "codigo_planilla"                              => $forma_id_planilla,
                "salario_mensual"                              => $forma_salario_mensual,
                "valor_hora"                                   => $salario_diario,
                "dias_mes"                                     => $forma_dias_mes,
                "horas_mes"                                    => $forma_horas_mes,
                "codigo_turno_laboral"                         => $forma_id_turno_laboral,
                "codigo_motivo_retiro"                         => 0,
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

            $insertar = SQL::modificar("sucursal_contrato_empleados", $datos, $condicion . " AND codigo_sucursal='$forma_oculto_sucursal_labora' AND fecha_ingreso_sucursal='$forma_oculto_fecha_inicio'");

            $modificar = false;
            $condicion_salario_contrato = "codigo_empresa='$codigo_empresa' AND documento_identidad_empleado='$forma_documento_identidad_empleado' AND fecha_ingreso='$forma_fecha_inicio' AND fecha_ingreso_sucursal='$forma_fecha_inicio'";

            $datos = array(
                "codigo_empresa"               => $forma_codigo_empresa,
                "documento_identidad_empleado" => $forma_documento_identidad_empleado,
                "fecha_ingreso"                => $forma_fecha_inicio,
                "codigo_sucursal"              => $forma_sucursal_labora,
                "fecha_ingreso_sucursal"       => $forma_fecha_inicio,
                "fecha_salario"                => $forma_fecha_inicio,
                "fecha_registro"               => $fecha_registro,
                "fecha_retroactivo"            => $forma_fecha_retroactivo,
                "salario"                      => $forma_salario_mensual,
                "valor_dia"                    => $salario_diario,
                "valor_hora"                   => $valor_hora
            );

            $modificar = SQL::modificar("salario_sucursal_contrato",$datos,$condicion_salario_contrato." AND fecha_salario='$forma_fecha_salario'");

            $datos = array(
                "codigo_empresa"               => $forma_codigo_empresa,
                "documento_identidad_empleado" => $forma_documento_identidad_empleado,
                "fecha_ingreso"                => $forma_fecha_inicio,
                "fecha_inicio_salud"           => $forma_fecha_inicial_salud,
                "codigo_entidad_salud"         => $forma_codigo_entidad_salud,
                "direccion_atencion"           => $forma_direccion_atienden,
                "direccion_urgencia"           => $forma_direccion_urgencia
            );
            ////compruebo que halla algun registro en salud ///
            $consulta_salud_empleados = SQL::seleccionar(array("entidades_salud_empleados"), array("*"), $condicion);
            if(SQL::filasDevueltas($consulta_salud_empleados)){
                $insertar = SQL::modificar("entidades_salud_empleados", $datos, $condicion . " AND fecha_inicio_salud='$forma_oculto_fecha_inicial_salud'");
            }else{
                $insertar = SQL::insertar("entidades_salud_empleados", $datos);
            }
            $pension = SQL::obtenerValor("aspirantes", "pensionado", "documento_identidad = '$forma_documento_identidad_empleado'");
            if($pension == 0){
                $datos = array(
                    "codigo_empresa"               => $forma_codigo_empresa,
                    "documento_identidad_empleado" => $forma_documento_identidad_empleado,
                    "fecha_ingreso"                => $forma_fecha_inicio,
                    "fecha_inicio_pension"         => $forma_fecha_inicial_pension,
                    "codigo_entidad_pension"       => $forma_codigo_entidad_pension
                );
                $consulta_pension_empleados = SQL::seleccionar(array("entidades_pension_empleados"), array("*"), $condicion);
                if(SQL::filasDevueltas($consulta_pension_empleados)){
                    $insertar = SQL::modificar("entidades_pension_empleados", $datos, $condicion . " AND fecha_inicio_pension='$forma_oculto_fecha_inicial_pension'");
                } else {
                    $insertar = SQL::insertar("entidades_pension_empleados", $datos);
                }
            }
            if(!isset($forma_idPosicionTablaPago)){
                $forma_idPosicionTablaPago = array();
            }
            SQL::eliminar("ingresos_varios_empleados", $condicion);
            for($id = 0; !empty($forma_ConceptoContableTabla[$id]); $id++){
                $datos = array(
                    "codigo_empresa"                 => $forma_codigo_empresa,
                    "documento_identidad_empleado"   => $forma_documento_identidad_empleado,
                    "fecha_ingreso"                  => $forma_fecha_inicio,
                    "codigo_transaccion_tiempo"      => $forma_fecha_inicio,
                    "fecha_inicio_transacion_tiempo" => $forma_fecha_inicio,
                    "fecha_final_transacion_tiempo"  => "",
                    "estado"                         => 1,
                    "codigo_transaccion_tiempo"      => $forma_IdConceptoContable[$id],
                    "periodo_pago"                   => $forma_PeriodoPagoTabla[$id],
                    "valor"                          => $forma_valorIngreso[$id]
                );
                $insertar = SQL::insertar("ingresos_varios_empleados", $datos);
                if (!$insertar) {
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
            $insertar = SQL::modificar("cargo_contrato_empleados", $datos, $condicion . " AND codigo_sucursal='$forma_oculto_sucursal_labora' AND fecha_ingreso_sucursal='$forma_oculto_fecha_inicio' AND fecha_inicia_cargo='$forma_oculto_fecha_inicio_cargo'");

            $datos = array(
                "codigo_empresa"                    => $forma_codigo_empresa,
                "documento_identidad_empleado"      => $forma_documento_identidad_empleado,
                "fecha_ingreso"                     => $forma_fecha_inicio,
                "codigo_sucursal"                   => $forma_sucursal_labora,
                "fecha_ingreso_sucursal"            => $forma_fecha_inicio,
                "fecha_inicia_departamento_seccion" => $forma_fecha_inicio,
                "codigo_departamento_empresa"       => $forma_id_departamento,
                "codigo_seccion_empresa"            => $forma_id_seccion,
                "fecha_termina"                     => $forma_documento_identidad_jefe,
            );
            $insertar = SQL::modificar("departamento_seccion_contrato_empleado", $datos, $condicion . " AND codigo_sucursal='$forma_oculto_sucursal_labora' AND fecha_ingreso_sucursal='$forma_oculto_fecha_inicio'");
            if (!$insertar) {
                $error = true;
                $mensaje = $textos["ERROR_MODIFICAR_ITEM"];
                $mensaje = mysql_error();
            }
        }
    }else{

        if(!isset($forma_id_auxiliar_contable)){
            $forma_id_auxiliar_contable = 0;
            $empresa_auxiliar = 0;
        }else{
           $empresa_auxiliar = $forma_oculto_codigo_empresa;
        }

        if($pestana_basica == 1){
            $prefijo = "forma_oculto_";
        }
        if($petana_contrato == 1){
            $prefijo = "forma_";
        }
        if(!isset(${$prefijo . "id_auxiliar_contable"})){
            ${$prefijo . "id_auxiliar_contable"} = 0;
        }
        if(!isset(${$prefijo . "auxilio_transporte"})){
            $auxilio_transporte = ${$prefijo . "manejo_auxilio_transporte"};
        }elseif(${$prefijo . "auxilio_transporte"} == "1" || empty(${$prefijo . "auxilio_transporte"})){
            $auxilio_transporte = 5;
        }else{
            $auxilio_transporte = ${$prefijo . "manejo_auxilio_transporte"};
        }
        $terminio_contrato = SQL::obtenerValor("tipos_contrato", "termino_contrato", "codigo='${$prefijo . "id_tipo_contrato"}'");
        if($terminio_contrato == "1"){
        $fecha_vencimiento = ${$prefijo . "fecha_vencimiento_contrato"};
        }else{
        $fecha_vencimiento = "";
        }
        if($petana_contrato == 1){
        $prefijo = "forma_oculto_";
        }
        if($pestana_basica == 1 || $petana_contrato == 1){
        if($pestana_basica==1)
        {
            $prefijo = "forma_";
        }
        $datos = array(
            "riesgo_profesional"        => ${$prefijo . "riesgo_profesional"},
            "manejo_auxilio_transporte" => $auxilio_transporte,
            "codigo_sucursal_activo"    => ${$prefijo . "sucursal_labora"}
        );
        $insertar = SQL::modificar("ingreso_empleados", $datos, $condicion);
        }
                    /*
                      if($petana_contrato==1)
                      {
                      $prefijo= "forma_";
                      $fecha_prologa_contrato ="2011-03-06";
                      }

                      if ($terminio_contrato == "1" || $terminio_contrato == "3") {
                      $fecha_cambio = ${$prefijo."fecha_vencimiento_contrato"};
                      } elseif ($terminio_contrato == "2" || $terminio_contrato == "4") {
                      $fecha_cambio = "";
                      }else{
                      $fecha_cambio = "";
                      }
                      //$fecha_cambio = $forma_fecha_vencimiento_contrato;
                      $datos = array(
                      "codigo_empresa"               => $codigo_empresa,
                      "documento_identidad_empleado" => $documento_identidad_empleado,
                      "fecha_ingreso"                => $fecha_ingreso,
                      "fecha_contrato"               => $fecha_prologa_contrato,
                      "codigo_tipo_contrato"         => ${$prefijo."id_tipo_contrato"},
                      "fecha_cambio_contrato"        => $fecha_cambio,
                      );

                      $insertar = SQL::insertar("contrato_empleados", $datos);

                     */
        // echo var_dump($forma_id_auxiliar_contable);
        //////////////////////////////////////////////////////////////////////////////////////////
        //echo var_dump($forma_id_anexos_contables);
        ////////////////////////////////////////////////////////
        if($pestana_basica == 1){
            $codigo_sucursal = $forma_sucursal_labora;
            $fecha_inicio = $forma_fecha_inicio;
            //$codigo_turno_laboral = ${$prefijo . "id_turno_laboral"};
            $codigo_turno_laboral = $forma_id_turno_laboral;
        }else{
            $codigo_sucursal = $forma_oculto_sucursal_labora;
            $fecha_inicio = $forma_oculto_fecha_inicio;
        }
        if($pestana_basica == 1){
            $prefijo = "forma_oculto_";//cambio
        }
        if($petana_contrato == 1){
            $prefijo = "forma_";
            $codigo_turno_laboral = $forma_oculto_forma_pago_auxilio;
            $fecha_inicio = $forma_oculto_fecha_ingreso_surcursal;

            ///////////calculo de proporcion///////////////
            $salario_diario    = (${$prefijo . "salario_mensual"}  / ${$prefijo . "dias_mes"});
            $numero_horas_dias =  ${$prefijo . "horas_mes"} / ${$prefijo . "dias_mes"};
            $valor_hora        =  $salario_diario / $numero_horas_dias;
            ///////////////////////////////////////////////
        }


        $periodoPago = SQL::obtenerValor("planillas", "periodo_pago", "codigo = '${$prefijo . "id_planilla"}'");
        ///Si no viaja los datos por defecto siempre seran 1///
        if(!isset(${$prefijo . "forma_pago_auxilio"}) || !isset(${$prefijo . "forma_descuento_salud"}) || !isset(${$prefijo . "forma_descuento_salud"})) {
            ${$prefijo . "pago_auxilio"} = 1;
            ${$prefijo . "descuento_salud"} = 1;
            ${$prefijo . "descuento_pension"} = 1;
        }
        //////// Validacion de campos transacciones auxilo de transporte y pension /////////////
        if(!isset(${$prefijo . "codigo_transaccion_auxilio_transporte"})){
            ${$prefijo . "codigo_transaccion_auxilio_transporte"} = "0";
        }
        if(!isset(${$prefijo . "codigo_transaccion_pension"})){
            ${$prefijo . "codigo_transaccion_pension"} = "0";
        }
        if(empty(${$prefijo . "forma_pago_auxilio"})){
            ${$prefijo . "forma_pago_auxilio"} = "1";
        }
        if(empty(${$prefijo . "forma_descuento_pension"})){
            ${$prefijo . "forma_descuento_pension"} = "1";
        }
        if($pestana_basica == 1 || $petana_contrato == 1){
            if($pestana_basica==1)
            {
                $prefijo = "forma_";
            }


            $date_r = getdate(strtotime($fecha_inicio));
            $fecha_termina = date("Y-m-d", mktime(($date_r["hours"]), ($date_r["minutes"]), ($date_r["seconds"]), ($date_r["mon"]), ($date_r["mday"] - 1), ($date_r["year"])));
            $modificar = SQL::modificar("sucursal_contrato_empleados",array("fecha_retiro"=>$fecha_termina), $condicion . " AND codigo_sucursal='$forma_oculto_sucursal_labora' AND fecha_ingreso_sucursal='$forma_oculto_fecha_ingreso_surcursal'");

            $datos = array(
                "codigo_empresa"                                => $codigo_empresa,
                "documento_identidad_empleado"                  => $documento_identidad_empleado,
                "fecha_ingreso"                                 => $fecha_ingreso,
                "codigo_sucursal"                               => $codigo_sucursal,
                "fecha_ingreso_sucursal"                        => $fecha_inicio,
                "codigo_anexo_contable"                         => ${$prefijo . "id_anexos_contables"},
                "codigo_empresa_auxiliar"                       => $codigo_empresa,
                "codigo_auxiliar"                               => ${$prefijo . "id_auxiliar_contable"},
                "codigo_planilla"                               => ${$prefijo . "id_planilla"},
                "salario_mensual"                               => ${$prefijo . "salario_mensual"},
                "valor_hora"                                    => $salario_diario,
                "dias_mes"                                      => $forma_dias_mes,
                "horas_mes"                                     => $forma_horas_mes,
                "codigo_turno_laboral"                          => $codigo_turno_laboral,
                "codigo_motivo_retiro"                          => '0',
                "fecha_retiro"                                  => "0000-00-00",
                "codigo_transaccion_salario"                    => ${$prefijo . "codigo_transaccion_salario"},
                "codigo_transaccion_auxilio_transporte"         => ${$prefijo . "codigo_transaccion_auxilio_transporte"},
                "forma_pago_auxilio"                            => ${$prefijo . "forma_pago_auxilio"},
                "codigo_transaccion_salud"                      => ${$prefijo . "codigo_transaccion_salud"},
                "forma_descuento_salud"                         => ${$prefijo . "forma_descuento_salud"},
                "codigo_transaccion_pension"                    => ${$prefijo . "codigo_transaccion_pension"},
                "forma_descuento_pension"                       => ${$prefijo . "forma_descuento_pension"},
                "codigo_transaccion_normales"                   => ${$prefijo . "codigo_transaccion_normales"},
                "codigo_transaccion_extras"                     => ${$prefijo . "codigo_transaccion_extras"},
                "codigo_transaccion_recargo_nocturno"           => ${$prefijo . "codigo_transaccion_recargo_nocturno"},
                "codigo_transaccion_extras_nocturnas"           => ${$prefijo . "codigo_transaccion_extras_nocturnas"},
                "codigo_transaccion_dominicales"                => ${$prefijo . "codigo_transaccion_dominicales"},
                "codigo_transaccion_extras_dominicales"         => ${$prefijo . "codigo_transaccion_extras_dominicales"},
                "codigo_transaccion_recargo_noche_dominicales"  => ${$prefijo . "codigo_transaccion_recargo_noche_dominicales"},
                "codigo_transaccion_extras_noche_dominicales"   => ${$prefijo . "codigo_transaccion_extras_noche_dominicales"},
            );

           $condcionIf = ($codigo_sucursal==$forma_oculto_sucursal_labora AND $forma_oculto_fecha_ingreso_surcursal==$fecha_inicio);

           if($pestana_basica == 0 || $petana_contrato == 0 || $condcionIf){
                // echo var_dump($condicion." AND codigo_sucursal='$forma_oculto_sucursal_labora' AND fecha_ingreso_sucursal='$forma_oculto_fecha_ingreso_surcursal'");
                $insertar = SQL::modificar("sucursal_contrato_empleados", $datos, $condicion . " AND codigo_sucursal='$forma_oculto_sucursal_labora' AND fecha_ingreso_sucursal='$forma_oculto_fecha_ingreso_surcursal'");
                //echo var_dump( $condicion . " AND codigo_sucursal='$forma_oculto_sucursal_labora' AND fecha_ingreso_sucursal='$forma_oculto_fecha_ingreso_surcursal'");
            }elseif($pestana_basica == 1){
                $insertar = SQL::insertar("sucursal_contrato_empleados", $datos);
                /*echo var_dump($insertar);
                echo var_dump($datos);*/
            }
        }

        if($pestana_parafiscales == 1){
            $prefijo = "forma_";
            $datos = array(
                "codigo_empresa"                => $codigo_empresa,
                "documento_identidad_empleado"  => $documento_identidad_empleado,
                "fecha_ingreso"                 => $fecha_ingreso,
                "fecha_inicio_salud"            => ${$prefijo . "fecha_inicial_salud"},
                "codigo_entidad_salud"          => ${$prefijo . "codigo_entidad_salud"},
                "direccion_atencion"            => ${$prefijo . "direccion_atienden"},
                "direccion_urgencia"            => ${$prefijo . "direccion_urgencia"}
            );
            ////compruebo que halla algun registro en salud ///
            if($forma_oculto_fecha_inicial_salud != ${$prefijo . "fecha_inicial_salud"}){
                $insertar = SQL::insertar("entidades_salud_empleados", $datos);
            }
            $pension = SQL::obtenerValor("aspirantes", "pensionado", "documento_identidad = '$documento_identidad_empleado'");
            if ($pension == 0){
                $datos = array(
                    "codigo_empresa"               => $codigo_empresa,
                    "documento_identidad_empleado" => $documento_identidad_empleado,
                    "fecha_ingreso"                => $fecha_ingreso,
                    "fecha_inicio_pension"         => ${$prefijo . "fecha_inicial_pension"},
                    "codigo_entidad_pension"       => ${$prefijo . "codigo_entidad_pension"}
                );
                if($forma_oculto_fecha_inicial_pension != ${$prefijo . "fecha_inicial_pension"}){
                    $insertar = SQL::insertar("entidades_pension_empleados", $datos);
                }
            }
        }
        /*
        if(!isset($forma_idPosicionTablaPago)){
            $forma_idPosicionTablaPago = array();
        }
        ///Determino el dia anterior de acuerdo a la fecha selecciona///
        $date_r = getdate(strtotime($forma_oculto_fecha_inician_ingreso_varios));
        $fecha_ingreso_varios = date("Y-m-d", mktime(($date_r["hours"]), ($date_r["minutes"]), ($date_r["seconds"]), ($date_r["mon"]), ($date_r["mday"] - 1), ($date_r["year"])));

        foreach($datos_ingresos_varios AS $completar_llave_ingresos_varios){
            $completar_llave_ingresos_varios = explode(",", $completar_llave_ingresos_varios);
            $llave_primaria_varios           = $condicion . " AND codigo_transaccion_tiempo='$completar_llave_ingresos_varios[0]' AND fecha_inicio_transacion_tiempo='$completar_llave_ingresos_varios[1]'";

            $obtener_fecha_inicia = SQL::obtenerValor("ingresos_varios_empleados","fecha_inicio_transacion_tiempo",$llave_primaria_varios);
            if(strftime($obtener_fecha_inicia)== strftime($completar_llave_ingresos_varios[1]))
            {
                $fecha_ingreso_varios = $completar_llave_ingresos_varios[1];
            }
            $datos = array(
                "fecha_final_transacion_tiempo" => $fecha_ingreso_varios,
                "estado"                        => '0',
            );
            SQL::modificar("ingresos_varios_empleados", $datos, $llave_primaria_varios);
        }

        for($id = 0; !empty($forma_ConceptoContableTabla[$id]); $id++) {
            $datos = array(
                "codigo_empresa"                 => $codigo_empresa,
                "documento_identidad_empleado"   => $documento_identidad_empleado,
                "fecha_ingreso"                  => $fecha_ingreso,
                "fecha_inicio_transacion_tiempo" => $forma_oculto_fecha_inician_ingreso_varios,
                "fecha_final_transacion_tiempo"  => "",
                "estado"                         => 1,
                "codigo_transaccion_tiempo"      => $forma_IdConceptoContable[$id],
                "periodo_pago"                   => $forma_PeriodoPagoTabla[$id],
                "valor"                          => $forma_valorIngreso[$id]
            );
            ////validar por si en un mismmo dia se ingresa la misma transaccion contable///
            $llave_primaria_varios           = $condicion . " AND codigo_transaccion_tiempo='$forma_IdConceptoContable[$id]' AND fecha_inicio_transacion_tiempo='$forma_oculto_fecha_inician_ingreso_varios'";
            $consulta_datos_ingreso_varios   = SQL::seleccionar(array("ingresos_varios_empleados"),array("*"),$llave_primaria_varios);
            if(!SQL::filasDevueltas($consulta_datos_ingreso_varios) && !isset($forma_estado[$id])){
                $insertar = SQL::insertar("ingresos_varios_empleados", $datos);
            }
            else{
                $datos = array(
                    "fecha_final_transacion_tiempo" => "",
                    "estado"                        => '1',
                    "valor"                         => $forma_valorIngreso[$id]
                );
                SQL::modificar("ingresos_varios_empleados",$datos, $llave_primaria_varios);
           }
            if(!$insertar){
                $error   = true;
                $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
                $mensaje = mysql_error();
            }
        }
        */
        if ($pestana_basica == 1) {

            $prefijo = "forma_";
            $date_r = getdate(strtotime(${$prefijo . "fecha_inicio"}));
            $fecha_termina = date("Y-m-d", mktime(($date_r["hours"]), ($date_r["minutes"]), ($date_r["seconds"]), ($date_r["mon"]), ($date_r["mday"] - 1), ($date_r["year"])));
            $modificar = SQL::modificar("cargo_contrato_empleados",array("fecha_termina" => $fecha_termina),$condicion." AND codigo_sucursal='$forma_oculto_sucursal_labora'");

            /*
            $datos = array(
                "codigo_empresa"                     => $codigo_empresa,
                "documento_identidad_empleado"       => $documento_identidad_empleado,
                "fecha_ingreso"                      => $fecha_ingreso,
                "codigo_sucursal"                    => $codigo_sucursal,
                "fecha_ingreso_sucursal"             => $fecha_inicio,
                "fecha_inicia_cargo"                 => $fecha_inicio,
                "codigo_cargo"                       => $forma_id_cargo,
                "fecha_termina"                      => '0000-00-00',
                "documento_identidad_jefe_inmediato" => $forma_documento_identidad_jefe
            );

            $insertar = SQL::insertar("cargo_contrato_empleados", $datos);
            ////////////////Insertar el nuevo departamento////////////////////

            ///Determino el dia anterior de acuerdo a la fecha selecciona///

            $datos = array(
            "fecha_termina" => $fecha_termina,
            );
            $insertar = SQL::modificar("departamento_seccion_contrato_empleado", $datos, $condicion . "  AND fecha_ingreso_sucursal='$forma_oculto_fecha_ingreso_surcursal'"); //AND codigo_sucursal='$forma_oculto_sucursal_labora'
            $datos = array(
                "codigo_empresa"                    => $codigo_empresa,
                "documento_identidad_empleado"      => $documento_identidad_empleado,
                "fecha_ingreso"                     => $fecha_ingreso,
                "codigo_sucursal"                   => $codigo_sucursal,
                "fecha_ingreso_sucursal"            => $fecha_inicio,
                "fecha_inicia_departamento_seccion" => $fecha_inicio,
                "codigo_departamento_empresa"       => ${$prefijo . "id_departamento"},
                "codigo_seccion_empresa"            => ${$prefijo . "id_seccion"},
                "fecha_termina"                     => '0000-00-00'
            );
            $insertar = SQL::insertar("departamento_seccion_contrato_empleado", $datos);
            */

            $condicion_salario_contrato =  $condicion . " AND codigo_sucursal='$forma_oculto_sucursal_labora' AND fecha_ingreso_sucursal='$forma_oculto_fecha_inicio' AND fecha_salario='$forma_fecha_salario'";

            $consulta = SQL::modificar("salario_sucursal_contrato",array("fecha_cambio_salario"=>$fecha_termina),$condicion_salario_contrato);

            $fecha_registro = date("Y-m-d H:i:s");
            $datos = array(
                "codigo_empresa"               => $codigo_empresa,
                "documento_identidad_empleado" => $documento_identidad_empleado,
                "fecha_ingreso"                => $fecha_ingreso,
                "codigo_sucursal"              => $codigo_sucursal,
                "fecha_ingreso_sucursal"       => $fecha_inicio,
                "fecha_salario"                => $fecha_inicio,
                "fecha_registro"               => $fecha_registro,
                "fecha_retroactivo"            => $forma_fecha_retroactivo,
                "salario"                      => $forma_salario_mensual,
                "valor_dia"                    => $salario_diario,
                "valor_hora"                   => $valor_hora
            );
            $insertar=  SQL::insertar("salario_sucursal_contrato",$datos);


        }

        if(!$insertar){
            $error = true;
            $mensaje = $textos["ERROR_MODIFICAR_ITEM"];
            $mensaje = mysql_error();
        }

       if($pestana_transacciones_fijas == 1 && $pestana_transacciones_tiempo == 1)
       {
            if (!isset($forma_forma_pago_auxilio)){
                $forma_forma_pago_auxilio = "1";
            }

            $datos = array(
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

            $insertar = SQL::modificar("sucursal_contrato_empleados",$datos,$condicion . " AND codigo_sucursal='$forma_oculto_sucursal_labora' AND fecha_ingreso_sucursal='$forma_oculto_fecha_inicio'");

            if(!$insertar){
                $error = true;
                $mensaje = $textos["ERROR_MODIFICAR_ITEM"];
                $mensaje = mysql_error();
            }
       }

       ////////////////////////////////////////////////////////////////////////////////////////
       if($pestana_modificar_salario == 1){
            $requiere_transaccion = true;
            if(!isset($forma_auxilio_transporte)){
                $auxilio_transporte = $forma_manejo_auxilio_transporte;
            }elseif($forma_auxilio_transporte == "1"){
                $auxilio_transporte = 5;
                $requiere_transaccion = false;
            }else{
                $auxilio_transporte = $forma_manejo_auxilio_transporte;
            }

            if($requiere_transaccion){
                if(empty ($forma_selector4)){
                    $error = true;
                    $mensaje = $textos["ERROR_VACIO_TRANSACCION_AUXILIO"];
                }
            }

            if(!$error){
                $fecha_cambio_salario = $forma_fecha_inicial_salario;
                $modificar = false;
                $condicion_salario_contrato = "codigo_empresa='$codigo_empresa' AND documento_identidad_empleado='$documento_identidad_empleado' AND fecha_ingreso='$fecha_ingreso' AND fecha_ingreso_sucursal='$forma_oculto_fecha_ingreso_surcursal'";

                $fecha_termina = date("Y-m-d",strtotime("-1 days",strtotime($fecha_cambio_salario)));

                if($forma_fecha_salario==$fecha_cambio_salario){
                    $modificar = true;
                }
                //echo var_dump($fecha_termina);
                $salario_diario = ($forma_salario_mensual / 30);
                ///////////calculo de proporcion///////////////
                $salario_diario    = ($forma_salario_mensual / $forma_oculto_dias_mes);
                $numero_horas_dias =  $forma_oculto_horas_mes / $forma_oculto_dias_mes;
                $valor_hora        =  $salario_diario / $numero_horas_dias;
                ///////////////////////////////////////////////

                $fecha_registro = date("Y-m-d H:i:s");
                $datos = array(
                    "codigo_empresa"               => $codigo_empresa,
                    "documento_identidad_empleado" => $documento_identidad_empleado,
                    "fecha_ingreso"                => $fecha_ingreso,
                    "codigo_sucursal"              => $forma_oculto_sucursal_labora,
                    "fecha_ingreso_sucursal"       => $forma_oculto_fecha_ingreso_surcursal,
                    "fecha_salario"                => $fecha_cambio_salario,
                    "fecha_registro"               => $fecha_registro,
                    "fecha_retroactivo"            => $forma_fecha_retroactivo,
                    "salario"                      => $forma_salario_mensual,
                    "valor_dia"                    => $salario_diario,
                    "valor_hora"                   => $valor_hora
                );
                //echo var_dump($condicion_salario_contrato." AND codigo_sucursal='$forma_oculto_sucursal_labora' AND fecha_salario='$forma_fecha_salario'");
               if($modificar){
                    $consulta = SQL::modificar("salario_sucursal_contrato",$datos,$condicion_salario_contrato." AND fecha_salario='$forma_fecha_salario'");
                }else{
                    $consulta=  SQL::insertar("salario_sucursal_contrato",$datos);
                }

                if(!$consulta){
                    $error = true;
                    $mensaje = $textos["ERROR_MODIFICAR_SALARIO_EMPLEADO"];
                    $mensaje = mysql_error();
                }else{

                    if(!isset($forma_codigo_transaccion_auxilio_transporte) || empty($forma_codigo_transaccion_auxilio_transporte)){
                        $forma_codigo_transaccion_auxilio_transporte = 0;
                    }
                    if(!isset($forma_forma_pago_auxilio)){
                        $forma_forma_pago_auxilio = "1";
                    }

                    $datos = array(
                        "codigo_transaccion_auxilio_transporte" => $forma_codigo_transaccion_auxilio_transporte,
                        "forma_pago_auxilio"                    => $forma_forma_pago_auxilio
                    );

                    $modificar = SQL::modificar("sucursal_contrato_empleados",$datos,$condicion_salario_contrato);

                    if(!$consulta){
                        $error = true;
                        $mensaje = $textos["ERROR_MODIFICAR_ITEM"];
                    }else{
                        $condicion_ingreso_empleados = "codigo_empresa='$codigo_empresa' AND documento_identidad_empleado='$documento_identidad_empleado' AND fecha_ingreso='$fecha_ingreso'";
                        $modificar = SQL::modificar("ingreso_empleados",array("manejo_auxilio_transporte"=>$auxilio_transporte),$condicion_ingreso_empleados);

                    }
                }
            }
       }
}

$respuesta = array();
$respuesta[0] = $error;
$respuesta[1] = $mensaje;
HTTP::enviarJSON($respuesta);
}
?>

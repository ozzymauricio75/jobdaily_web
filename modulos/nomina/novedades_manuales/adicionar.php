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
* modificarlo  bajo los términos de la Licencia Pública General GNU
* publicada por la Fundación para el Software Libre, ya sea la versión 3
* de la Licencia, o (a su elección) cualquier versión posterior.
*
* Este programa se distribuye con la esperanza de que sea útil, pero
* SIN GARANTÍA ALGUNA; ni siquiera la garantía implícita MERCANTIL o
* de APTITUD PARA UN PROPÓSITO DETERMINADO. Consulte los detalles de
* la Licencia Pública General GNU para obtener una información más
* detallada.
*
* Debería haber recibido una copia de la Licencia Pública General GNU
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
//////////Verifico que la planilla no halla sido pagada/////
 if(isset($url_recargar_auxiliares)){
    $empresa  = SQL::obtenerValor("sucursales","codigo_empresa","codigo='".$sesion_sucursal."'");
    $lista=HTML::generarDatosLista("auxiliares_contables", "codigo", "descripcion", "codigo_anexo_contable = '".$url_origen."'AND codigo_empresa='".$empresa."'");
    HTTP::enviarJSON($lista);
    exit;
}

 if(isset ($url_verificaPagoPlanilla))
 {
     $condicion  = " ano_generacion= '$url_anio' AND mes_generacion='$url_mes' AND codigo_planilla='$url_codigo_planilla'";
     $condicion .= " AND periodo_pago='$url_periodo' AND fecha_pago_planilla='$url_fecha_pago_planilla' AND codigo_sucursal_recibe='$url_codigo_sucursal'";
     $respuesta = '2';

     $pago_planilla = SQL::obtenerValor("forma_pago_planillas","pagado",$condicion);
     if($pago_planilla=='1')
     {
         $respuesta = '1';
     }/*else
     {
         $respuesta = '2';
     }  */
     HTTP::enviarJSON($respuesta);
     exit();
 }
 //////////////Cargar el tipo de planilla del empleado///////
 if(isset($url_cargar_planilla))
 {
     $planilla = SQL::obtenerValor("sucursal_contrato_empleados","codigo_planilla","documento_identidad_empleado='$url_documento_identidad' ORDER BY fecha_ingreso_sucursal DESC LIMIT 0,1");
     HTTP::enviarJSON($planilla);
 }

 if(isset($url_recargarTipoPlanilla))
 {
     $tipo_planilla = SQL::obtenerValor("planillas","periodo_pago","codigo='$url_codigo_planilla'");
     HTTP::enviarJSON($tipo_planilla);
 }

 if (!empty($url_recargar) && !empty($url_codigo_planilla) && !empty($url_ano_generacion) && !empty($url_mes_generacion) ) {
    $tipo_planilla = SQL::obtenerValor("planillas","periodo_pago","codigo='$url_codigo_planilla'");
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
    } else if($tipo_planilla == '3')  {
        $periodo = array(
            "4" => $textos["PRIMERA_SEMANA"],
            "5" => $textos["SEGUNDA_SEMANA"],
            "6" => $textos["TERCERA_SEMANA"],
            "7" => $textos["CUARTA_SEMANA"],
            "8" => $textos["QUINTA_SEMANA"]
        );
    } else {
        $periodo = array(
            "9" => $textos["FECHA_UINCA"]
        );
    }

    if ($url_mes_generacion == 2){
        if (($url_ano_generacion % 4 ==0) && ($url_ano_generacion % 100 !=0 || $url_ano_generacion % 400 == 0)){
            $dia_fin = 29;
        } else {
            $dia_fin = 28;
        }
    } else {
            $dia_fin = 30;
    }
    $fecha_inicio = $url_ano_generacion."-".$url_mes_generacion."-01";
    $fecha_fin    = $url_ano_generacion."-".$url_mes_generacion."-".$dia_fin;
    $respuesta = HTML::generarDatosLista("fechas_planillas", "fecha", "fecha", "codigo_planilla='$url_codigo_planilla' AND (fecha BETWEEN '$fecha_inicio' AND '$fecha_fin')");
    $fechas = SQL::seleccionar(array("fechas_planillas"),array("fecha"),"codigo_planilla='$url_codigo_planilla' AND (fecha BETWEEN '$fecha_inicio' AND '$fecha_fin')");
    if (isset($url_periodo) && SQL::filasDevueltas($fechas)){
        $respuesta = $periodo;
    }
    HTTP::enviarJSON($respuesta);
}
//////////////////////////////////////////
if(isset($url_verificaAnexos)){

    if(!empty($url_transaccion) && !empty($url_selector)){
        $anexoEmpleado = SQL::obtenerValor("sucursal_contrato_empleados","codigo_anexo_contable","documento_identidad_empleado='".$url_empleado."' AND codigo_sucursal='".$url_sucursal."' ORDER BY fecha_ingreso_sucursal DESC LIMIT 0,1");
        $auxilEmpleado = SQL::obtenerValor("sucursal_contrato_empleados","codigo_auxiliar","documento_identidad_empleado='".$url_empleado."' AND codigo_sucursal='".$url_sucursal."' ORDER BY fecha_ingreso_sucursal DESC LIMIT 0,1");
        $consulta      = SQL::seleccionar(array("plan_contable PC,job_transacciones_contables_empleado TCE"),array("PC.codigo_anexo_contable"),"TCE.codigo_contable=PC.codigo_contable AND TCE.codigo=$url_transaccion");
        $datos         = SQL::filaEnObjeto($consulta);
        $anexo         = $datos->codigo_anexo_contable;

        if($anexo== "0" || $anexo== ""){
            $respuesta[0] = 1;//No tiene anexo contable
        }else{
            $respuesta[0] = 2;
            $respuesta[1] = $anexoEmpleado;
            $respuesta[2] = "";
            if($anexo == $anexoEmpleado){
                $respuesta[2] = $auxilEmpleado;
            }
        }
    }else
    {
        $respuesta[0] = 3;
    }

    HTTP::enviarJSON($respuesta);
    exit;
}

if(isset ($url_obtenerFechaRango) && isset ($url_documento_identidad) )
{
    $fecha_inicio = SQL::obtenerValor("sucursal_contrato_empleados","fecha_ingreso_sucursal","documento_identidad_empleado='$url_documento_identidad'", "", "fecha_ingreso_sucursal DESC", 0, 1);
    $fechas[0] =  $fecha_inicio;
    $rango_dias = (int) (strtotime(Date("Y-m-d")) - strtotime($fecha_inicio)) / (60 * 60 * 24);
    HTTP::enviarJSON($rango_dias);
    exit;
}

if(isset($url_verificar)){

    if($url_item == 'selector1'){
        $condicion_extra = "id_sucursal='$url_codigo_sucursal'";
        echo SQL::datosAutoCompletar("seleccion_empleados", $url_q, $condicion_extra);
        exit;
    }elseif (($url_item) == "selector2") {
            echo SQL::datosAutoCompletar("seleccion_transacciones_contables_empleado", $url_q);
            exit;
    }

}


if(!empty($url_generar)){

    $error  = "";
    $titulo = $componente->nombre;
    $id_modulo = SQL::obtenerValor("componentes","id_modulo","id = '$componente->id'");

    $empresa  = SQL::obtenerValor("sucursales","codigo_empresa","codigo='".$sesion_sucursal."'");
    $anexos_contables  = array();
    $consulta = SQL::seleccionar(array("auxiliares_contables,job_anexos_contables"), array("codigo_anexo_contable","job_anexos_contables.descripcion"), "codigo_empresa='$empresa' AND codigo_anexo_contable=job_anexos_contables.codigo", "codigo_anexo_contable");

    while ($fila = SQL::filaEnArreglo($consulta)) {
        $anexos_contables[$fila[0]] = $fila[1];
    }

    $listado_auxiliares = HTML::generarDatosLista("auxiliares_contables","codigo","descripcion", "codigo_empresa='$empresa' AND codigo_anexo_contable = '" . array_shift(array_keys($anexos_contables)) . "'");
    ////////////////////////////////////////////////////////////////////////////
    $consulta_planillas = SQL::seleccionar(array("planillas"),array("*"),"codigo>0");
    if (SQL::filasDevueltas($consulta_planillas)){

        $planillas[0] = '';

        while ($datos_planilla = SQL::filaEnObjeto($consulta_planillas)){
            $planillas[$datos_planilla->codigo] = $datos_planilla->descripcion;
        }

    } else {
        $error_continuar = 1;
    }

    $consulta_fechas_planillas = SQL::seleccionar(array("fechas_planillas"),array("*"),"codigo_planilla>0");
    if (SQL::filasDevueltas($consulta_fechas_planillas)){

        while ($datos_fechas_planillas = SQL::filaEnObjeto($consulta_fechas_planillas)){
            $fechas_planillas[$datos_fechas_planillas->codigo_planilla."|".$datos_fechas_planillas->fecha] = $datos_fechas_planillas->fecha;
        }
    } else {
        $error_continuar = 2;
    }

    $consulta_empleados = SQL::seleccionar(array("ingreso_empleados"),array("*"),"documento_identidad_empleado!=''");
    if (!SQL::filasDevueltas($consulta_empleados)){
        $error_continuar = 3;
    }
    ////////////////////////////////////////////////////////////////////////////
    $ano = date("Y");
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

        ///Obtener lista de sucursales para selección dependiendo a los permisos///
    $consulta = SQL::seleccionar(array("sucursales"),array("*"),"codigo > 0","","nombre");
    if ($sesion_usuario == $datosGlobales["usuarioMaestro"]){
         while ($datos = SQL::filaEnObjeto($consulta)){
            $sucursales[$datos->codigo] = $datos->nombre;
        }
    } else {
        /*** Obtener lista de sucursales para selección ***/
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

    //// Definición de pestaña Basica ////
    $formularios["PESTANA_BASICA"] = array(
        array(
            HTML::listaSeleccionSimple("*codigo_sucursal", $textos["SUCURSAL"],$sucursales,$sesion_sucursal, array("title" => $textos["AYUDA_SUCURSAL"],"onchange" => " limpiarCampos();")),
            HTML::campoTextoCorto("*selector1", $textos["EMPLEADO"], 40, 255, "", array("title" => $textos["AYUDA_EMPLEADO"], "onfocus" => "acLocalEmpleados(this);"))//,"onblur" => "obtenerRango();","onclick" => "obtenerRango();"
           .HTML::campoOculto("documento_identidad_empleado","")
           .HTML::campoOculto("id_modulo",$id_modulo)
        ),
       array(
            HTML::campoTextoCorto("*selector2", $textos["TRANSACCION_CONTABLE"], 40, 255, "", array("title" => $textos["AYUDA_TRANSACCION"],"onfocus" => "acLocalEmpleados(this);","disabled" => "disabled"))
           .HTML::campoOculto("transaccion_contable",""),
            HTML::campoTextoCorto("*valor_noveda", $textos["VALOR"],15,15, "", array("title" => $textos["AYUDA_VALOR_NOVEDAD"],"onclick" =>  "verificarAnexosEnTransacciones();","onKeyPress" => "return campoEntero(event)")),
            HTML::contenedor(HTML::boton("botonRemoverextras", "", "removerItems(this);", "eliminar"), array("id" => "botonRemoverextras", "style" => "display: none"))
       ),
       array(
            HTML::listaSeleccionSimple("*codigo_anexo_contable", $textos["ANEXO_CONTABLE"], $anexos_contables,0, array("title" => $textos["AYUDA_ANEXO_CONTABLE"], "onChange" => "recargarListaAuxiliares('codigo_anexo_contable','codigo_auxiliar_contable')","style" => "display: none;")),
            HTML::listaSeleccionSimple("*codigo_auxiliar_contable", $textos["AUXILIAR_CONTABLE"], HTML::generarDatosLista("auxiliares_contables", "codigo", "descripcion", "codigo_anexo_contable = '" . array_shift(array_keys($anexos_contables)) . "'AND codigo_empresa='".$empresa."'"), 0, array("title" => $textos["AYUDA_AUXILIAR_CONTABLE"],"style" => "display: none;"))
        ),
        array(
            HTML::listaSeleccionSimple("*ano_generacion", $textos["ANO_PLANILLA"],$ano_planilla,$ano, array("title" => $textos["AYUDA_ANO_PLANILLA"], "class" => "selectorFecha", "onchange"=>"cargarFechaPago2();")),
            HTML::listaSeleccionSimple("*mes_generacion", $textos["MES_PLANILLA"],$meses,$mes, array("title" => $textos["AYUDA_MES_PLANILLA"], "class" => "selectorFecha", "onchange"=>"cargarFechaPago2();")),
            HTML::listaSeleccionSimple("codigo_planilla",$textos["PLANILLA"],$planillas,"",array("title"=>$textos["AYUDA_PLANILLA"], "onchange"=>"cargarFechaPago2();","disabled" => "disabled")),
            HTML::listaSeleccionSimple("fecha_pago",$textos["FECHA_PAGO"], "","",array("title"=>$textos["AYUDA_FECHA_PAGO"],"class"=>"fecha_pago","onclick" => "determinarPeriodo();")),
            HTML::mostrarDato("nombre_periodo",$textos["PERIODO"],"")
        ),
        array(
            HTML::campoOculto("periodo","").
            HTML::campoOculto("mensual",$textos["MENSUAL"]).
            HTML::campoOculto("primera_quincena",$textos["PRIMERA_QUINCENA"]).
            HTML::campoOculto("segunda_quincena",$textos["SEGUNDA_QUINCENA"]).
            HTML::campoOculto("fecha_unica",$textos["FECHA_UNICA"]).
            HTML::campoOculto("mensaje_obligatorios",$textos["CAMPOS_OBLIGATORIOS"]).
            HTML::campoOculto("mensaje_transaccion",$textos["VACION_TRANSACCION"]).
            HTML::campoOculto("mensaje_valor_novedad",$textos["VACIO_VALOR_NOVEDAD"]).
            HTML::campoOculto("codigo_empresa",$empresa).
            HTML::campoOculto("genero_pago","").
            HTML::campoOculto("mensaje_genero_pago",$textos["PAGO_PLANILLA"])

        ),
        array(
            HTML::boton("botonAgregar", $textos["AGREGAR"],"verificarPeriodoContable();", "adicionar"),
            HTML::contenedor(HTML::boton("botonRemover", "", "removerItem(this);", "eliminar"), array("id" => "removedor", "style" => "display: none")),
            HTML::boton("botonEliminar",$textos["ELIMINAR_TODOS"],"removerTable();", "eliminar"),
            HTML::campoOculto("autorizaHoraExtra","")
        ),
        array(
            HTML::generarTabla(
                array("id","","TRANSACCION_CONTABLE","VALOR","ANEXO_CONTABLE","AUXILIAR_CONTABLE"),
                "",
                array("C","C","C","C","I"),
                "novedades_manuales",
                false
            )
        )
    );
    /*** Definición de botones ***/
    $botones = array(HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar"));
    $contenido = HTML::generarPestanas($formularios, $botones);
    /*** Enviar datos para la generación del formulario al script que originó la petición ***/
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

} /*** Adicionar los datos provenientes del formulario ***/
 elseif (!empty($forma_procesar)) {
    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];
    $fecha_actual= date("Y-m-d");
    if(empty($forma_transaccion[0])){
        $error = true;
        $mensaje = $textos["ERROR_NO_GENERO_NOVEDADES"];
    }elseif (false){
        $error = true;
        $mensaje = $textos["ERROR_DATOS_INCOMPLETOS"];
    } else {
            $consulta_sucursal_contrato = SQL::seleccionar(array("sucursal_contrato_empleados"),array("*"),"documento_identidad_empleado='$forma_documento_identidad_empleado'","","fecha_ingreso_sucursal DESC",0,1);
            $datos_sucursal_contrato    = SQL::filaEnObjeto($consulta_sucursal_contrato);

            for($id = 0;!empty($forma_transaccion[$id]); $id++){

                $consecutivo = (int)SQL::obtenerValor("movimiento_novedades_manuales","max(consecutivo)","");
                if($consecutivo){
                    $consecutivo++;
                }else{
                    $consecutivo=1;
                }

                $codigo_contable = SQL::obtenerValor("transacciones_contables_empleado", "codigo_contable","codigo = '$forma_transaccion[$id]'");
                $sentido         = SQL::obtenerValor("transacciones_contables_empleado", "sentido","codigo = '$forma_transaccion[$id]'");

                $fecha_pago_planilla_principal = $forma_fecha_pago[$id];
                $codigo_planilla               = $forma_codigo_planilla[$id];
                $codigo_sucursal               = $datos_sucursal_contrato->codigo_sucursal;

                $fecha_pago_planilla = explode("-",$forma_fecha_pago[$id]);
                $ano = (int)$fecha_pago_planilla[0];
                $mes = (int)$fecha_pago_planilla[1];

                $datos = array (
                    "fecha_generacion"             => $fecha_actual,
                    "codigo_empresa"               => $forma_codigo_empresa,
                    "documento_identidad_empleado" => $datos_sucursal_contrato->documento_identidad_empleado,
                    "fecha_ingreso"                => $datos_sucursal_contrato->fecha_ingreso,
                    "codigo_sucursal"              => $codigo_sucursal,
                    "fecha_ingreso_sucursal"       => $datos_sucursal_contrato->fecha_ingreso_sucursal,
                    "consecutivo"                  => $consecutivo,
                    ///////////////////////////////
                    "codigo_planilla"              => $codigo_planilla,
                    ///////////////////////////////
                    "codigo_transaccion_contable"  => $forma_transaccion[$id],
                    "codigo_contable"              => $codigo_contable,
                    "sentido"                      => $sentido,
                    ///////////////////////////////
                    "ano_generacion"               => $ano,
                    "mes_generacion"               => $mes,
                    "fecha_pago_planilla"          => $fecha_pago_planilla_principal,
                    "codigo_empresa_auxiliar"      => $forma_codigo_empresa,
                    "codigo_anexo_contable"        => $forma_codigo_anexo_contable[$id],
                    "codigo_auxiliar_contable"     => $forma_codigo_auxiliar_contable[$id],
                    "periodo_pago"                 => $forma_periodo,
                    ///////////////////////////////
                    "contabilizado"                => "0",
                    "valor_movimiento"             => $forma_valor_novedad_oculto[$id],
                    "codigo_usuario_registra"      => $sesion_codigo_usuario
                );
                //echo var_dump($datos);
                $insertar = SQL::insertar("movimiento_novedades_manuales", $datos);
                if(!$insertar){
                    $error   = true;
                    $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
                }
            }
            if($insertar){
                $condicion  = " ano_generacion= '$ano' AND mes_generacion='$mes' AND codigo_planilla='$codigo_planilla'";
                $condicion .= " AND periodo_pago='$forma_periodo' AND fecha_pago_planilla='$fecha_pago_planilla_principal' AND codigo_sucursal='$codigo_sucursal'";
                // echo var_dump($condicion);
                if(SQL::existeItem("movimientos_salarios","codigo_planilla",$codigo_planilla,$condicion)){
                    $error   = false;
                    $mensaje = $textos["ITEM_ADICIONADO_CORRECTO"];
                }

                SQL::eliminar("movimientos_salud",$condicion);
                SQL::eliminar("movimientos_salarios",$condicion);
                SQL::eliminar("movimientos_pension",$condicion);
                SQL::eliminar("movimientos_auxilio_transporte",$condicion);

            }

    }
    /*** Enviar datos con la respuesta del proceso al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

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

/*** Generar el formulario para la captura de datos ***/
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

if (!empty($url_generar)) {

    $error           = "";
    $titulo          = $componente->nombre;
    $id_modulo       = SQL::obtenerValor("componentes","id_modulo","id='$componente->id'");
    $error_continuar = false;

    $empresa             = SQL::obtenerValor("sucursales","codigo_empresa","codigo='$sesion_sucursal' AND codigo>0");
    $consulta_sucursales = SQL::seleccionar(array("sucursales"), array("codigo, nombre"), "codigo_empresa = '$empresa'");
    if (SQL::filasDevueltas($consulta_sucursales)){

        $pestana_sucursales   = array();
        $pestana_sucursales[] = array(HTML::boton("botonSeleccionarTodos", $textos["SELECCIONAR_TODOS"], "seleccionar_todas_sucursales();", "", array()));

        while ($datos_sucursales = SQL::filaEnObjeto($consulta_sucursales)){

            $codigo_sucursal = $datos_sucursales->codigo;
            $nombreSucursal  = $datos_sucursales->nombre;

            $pestana_sucursales[]   = array(
                HTML::marcaChequeo("sucursales[$datos_sucursales->codigo]", $datos_sucursales->nombre, $datos_sucursales->codigo, false, array("title" => $textos["AYUDA_SUCURSAL"], "id" => "sucursales_$datos_sucursales->codigo", "class" => "sucursales_electrodomesticos"))
            );
        }
    } else {
        $error_continuar = 0;
    }

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

    if (!$error_continuar){


        $ano = date("Y");
        $ano_planilla = array();
        for ($i=0;$i<=1;$i++){
            $ano_planilla[$ano] = $ano;
            $ano++;
        }
        $ano = date("Y");
        $mes = date("d");

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
                HTML::campoOculto("periodo","").
                HTML::campoOculto("mensual",$textos["MENSUAL"]).
                HTML::campoOculto("primera_quincena",$textos["PRIMERA_QUINCENA"]).
                HTML::campoOculto("segunda_quincena",$textos["SEGUNDA_QUINCENA"])

            )
        );


        /*** Definición de botones ***/
        $botones = array(HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar"));

        $contenido = HTML::generarPestanas($formularios, $botones);
    } else {
        if ($error_continuar == 0){
            $error = $textos["ERROR_SUCURSALES"];
        } else if($error_continuar == 1){
            $error = $textos["ERROR_PLANILLAS"];
        } else if($error_continuar == 2){
            $error = $textos["ERROR_FECHAS_PLANILLAS"];
        } else if($error_continuar == 3){
            $error = $textos["ERROR_EMPLEADOS"];
        }
        $contenido = "";
    }


    /*** Enviar datos para la generación del formulario al script que originó la petición ***/
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

} elseif (!empty($forma_procesar)) {
    /*** Asumir por defecto que no hubo error ***/

    $periodo = array(
            "1" => $textos["MENSUAL"],
            "2" => $textos["PRIMERA_QUINCENA"],
            "3" => $textos["SEGUNDA_QUINCENA"],
            "4" => $textos["PRIMER_SEMANA"],
            "5" => $textos["SEGUNDA_SEMANA"],
            "6" => $textos["TERCERA_SEMANA"],
            "7" => $textos["CUARTA_SEMANA"],
            "8" => $textos["QUINTA_SEMANA"]
        );

    $error        = false;
    $mensaje      = $textos["EXITO_LIQUIDACION"].$periodo[$forma_periodo].$textos["EXITO_LIQUIDACION2"].$forma_fecha_pago;

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

    if (!isset($forma_sucursales)){
        $error   = true;
        $mensaje = $textos["ERROR_SUCURSAL_VACIA"];

    }else if (empty($forma_codigo_planilla)){
        $error   = true;
        $mensaje = $textos["ERROR_CODIGO_PLANILLA"];

    }else if (empty($forma_fecha_pago)){
        $error   = true;
        $mensaje = $textos["ERROR_FECHA_PAGO"];

    }else if (empty($forma_periodo)){
        $error   = true;
        $mensaje = $textos["ERROR_PERIODO"];

    }else {

        $empleados      = array();
        $id_empleados   = array();
        $ano_generacion = $forma_ano_generacion;
        $mes_generacion = $forma_mes_generacion;

        $contadorNO     = 0;
        $mensajeS        = $textos["ERROR_PERIODO1"];
        $mensajeS       .= $textos["ERROR_PERIODO2"];

        foreach($forma_sucursales as $codigo_sucursal){
            $consulta = SQL::seleccionar(array("periodos_contables_modulos"),array("*"),"codigo_sucursal='".$codigo_sucursal."' AND ('".$forma_fecha_pago."' BETWEEN fecha_inicio AND fecha_fin) AND id_modulo='".$forma_id_modulo."'");

            if(SQL::filasDevueltas($consulta)){
            }else{
                $nombreSucursal = SQL::obtenerValor("sucursales","nombre","codigo='".$codigo_sucursal."'");
                $mensajeS.="- ".$nombreSucursal."\n";
                $contadorNO+=1;
            }
        }

        if($contadorNO>0){
            $error        = true;
            $mensaje      = $mensajeS;
        }else{

            $datos_movimientos = array();

            foreach ($forma_sucursales as $codigo_sucursal) {
                 ///////Obtengo todos los prestamos que no se hayan terminado de pagar///////
                 $consulta_cotrol_prestamos = SQL::seleccionar(array("vista_control_contrato"),array("*"),"codigo_sucursal='$codigo_sucursal' AND  codigo_planilla='$forma_codigo_planilla'");

                 if(SQL::filasDevueltas($consulta_cotrol_prestamos))
                 {     // fecha_pago
                      ////Determino los datos base para los movimientos////

                     while($datosCP = SQL::filaEnObjeto($consulta_cotrol_prestamos)){

                        $documento_identidad = $datosCP->documento_identidad_empleado;
                        $consecutivo         = $datosCP->consecutivo;
                        $fecha_generacion    = $datosCP->fecha_generacion;
                        $concepto_prestamo   = $datosCP->concepto_prestamo;
                        $periodo_pago        = $datosCP->forma_pago;
                        
                        $condicion           = "documento_identidad_empleado='$documento_identidad' AND consecutivo='$consecutivo' ";
                        $condicion          .= "AND fecha_generacion='$fecha_generacion' AND concepto_prestamo='$concepto_prestamo' ";
                        $condicion          .= "AND fecha_pago >= '$forma_fecha_pago'";

                        $consulta_fecha_cercana   = SQL::seleccionar(array("fechas_prestamos_empleados"),array("*"),$condicion,"","",0,1);
                        $datos_fecha_cercana      = SQL::filaEnObjeto($consulta_fecha_cercana);
                        $fecha_cercana_pago       = $datos_fecha_cercana->fecha_pago;
                        $permite_descuento        = $datos_fecha_cercana->descuento;

                        $datosCP                   = get_object_vars($datosCP);
                        $datosCP["fecha_cercana"]  = $fecha_cercana_pago;

                        if($permite_descuento=='1'){

                            $tipo_planilla = SQL::obtenerValor("planillas","periodo_pago","codigo='$forma_codigo_planilla'");

                            if($tipo_planilla=='2'){ ///quincenal

                                if($periodo_pago=='9'){//proporcional{
                                     $datos_movimientos [] = (object)$datosCP;
                                }elseif($periodo_pago==$forma_periodo){
                                     $datos_movimientos [] = (object)$datosCP;
                                    }

                            }elseif($tipo_planilla=='1'){
                                     $datos_movimientos [] = (object)$datosCP;
                                    }
                            }
                      }
                 }
            }
            
            foreach($datos_movimientos as $movimiento_prestamo){

                    $consecutivo = (int)SQL::obtenerValor("movimiento_control_prestamos_empleados","max(consecutivo)","");

                    if($consecutivo){
                         $consecutivo++;
                    }else{
                         $consecutivo=1;
                    }

                    $documento_identidad    = $movimiento_prestamo->documento_identidad_empleado;
                    $consecutivo_prestamo   = $movimiento_prestamo->consecutivo;
                    $fecha_generacion       = $movimiento_prestamo->fecha_generacion;
                    $concepto_prestamo      = $movimiento_prestamo->concepto_prestamo;
                    $periodo_pago           = $movimiento_prestamo->forma_pago;
                    $codigo_sucursal        = $movimiento_prestamo->codigo_sucursal;
                    $codigo_empresa         = $movimiento_prestamo->codigo_empresa;
                    $fecha_ingreso          = $movimiento_prestamo->fecha_ingreso;
                    $fecha_ingreso_sucursal = $movimiento_prestamo->fecha_ingreso_sucursal;
                    $codigo_contable        = $movimiento_prestamo->codigo_contable;
                    $sentido                = $movimiento_prestamo->sentido;
                    $fecha_cercana          = $movimiento_prestamo->fecha_cercana;

                    $condicion           = "documento_identidad_empleado='$documento_identidad' AND consecutivo='$consecutivo_prestamo' ";
                    $condicion          .= "AND fecha_generacion='$fecha_generacion' AND concepto_prestamo='$concepto_prestamo' ";
                    $condicion          .= " AND fecha_pago='$fecha_cercana'";

                    $consulta_fecha_cercana   = SQL::seleccionar(array("fechas_prestamos_empleados"),array("*"),$condicion,"","",0,1);
                    $datos_fecha_cercana      = SQL::filaEnObjeto($consulta_fecha_cercana);
                    $valor_descuento          = $datos_fecha_cercana->valor_descuento;

                    $datos = array(
                            /////////llave primaria///////////
                            "ano_generacion"               => $ano_generacion,
                            "mes_generacion"               => $mes_generacion,
                            "codigo_planilla"              => $forma_codigo_planilla,
                            "periodo_pago"                 => $forma_periodo,
                            "fecha_pago_planilla"          => $forma_fecha_pago,
                            "documento_identidad_empleado" => $documento_identidad,
                            "codigo_sucursal"              => $codigo_sucursal,
                            /////////////////////////////////
                            "fecha_generacion"             => $fecha_generacion,
                            "codigo_empresa"               => $codigo_empresa,
                            "fecha_ingreso"                => $fecha_ingreso,
                            "fecha_ingreso_sucursal"       => $fecha_ingreso_sucursal,
                            "consecutivo"                  => $consecutivo,
                            ///////////////////////////////
                            "codigo_contable"              => $codigo_contable,
                            "sentido"                      => $sentido,
                            ///tabla fechas de pago////
                            "fecha_generacion_control"     => $fecha_generacion,
                            "consecutivo_fecha_pago"       => $consecutivo_prestamo,
                            "fecha_pago"                   => $fecha_cercana,
                            "concepto_prestamo"            => $concepto_prestamo,
                            "valor_descuento"              => $valor_descuento,
                            //////Datos de control////////
                            "contabilizado"                => "0",
                            "codigo_usuario_registra"      => $sesion_codigo_usuario

                  );

                    $condicion  = " ano_generacion='$ano_generacion' AND mes_generacion='$mes_generacion' AND codigo_planilla='$forma_codigo_planilla'";
                    $condicion .= " AND periodo_pago='$forma_periodo' AND fecha_Pago_planilla='$forma_fecha_pago' ";
                    $condicion .= " AND documento_identidad_empleado='$documento_identidad' AND codigo_sucursal='$codigo_sucursal'";

                    if(!SQL::existeItem("movimiento_control_prestamos_empleados","documento_identidad_empleado",$documento_identidad,$condicion)){

                    $insertar = SQL::insertar("movimiento_control_prestamos_empleados",$datos);
                    if(!$insertar){
                            $error   = true;
                            $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
                    }

                    }
            }




        }
    }

    /*** Enviar datos con la respuesta del proceso al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

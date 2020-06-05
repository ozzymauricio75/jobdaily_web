<?php
/**
*
* Copyright (C) 2020 Jobdaily
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
 if(isset($url_recargarTipoPlanilla))
        {
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

    $error           = "";
    $titulo          = $componente->nombre;
    $error_continuar = false;

    $empresa = SQL::obtenerValor("sucursales","codigo_empresa","codigo='".$sesion_sucursal."' AND codigo>0");

    if ($sesion_usuario == $datosGlobales["usuarioMaestro"]){
        $consulta_sucursales = SQL::seleccionar(array("sucursales"), array("codigo, nombre"), "codigo_empresa = '".$empresa."'");
    }else{
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
        AND b.id_componente = '".$componente->id."' AND c.codigo_empresa = '".$empresa."'";

        $consulta_sucursales = SQL::seleccionar($tablas, $columnas, $condicion);
    }

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

        $forma_listar = array(
            "1"  => $textos["AUTOMATICOS"],
            "2"  => $textos["MANUALES"],
            "3"  => $textos["TODAS"],
        );

        $formularios["PESTANA_SUCURSALES"] = $pestana_sucursales;

        $formularios["PESTANA_BASICA"] = array(
            array(
                HTML::listaSeleccionSimple("*ano_generacion", $textos["ANO_PLANILLA"],$ano_planilla,$ano, array("title" => $textos["AYUDA_ANO_PLANILLA"], "class" => "selectorFecha", "onchange"=>"cargarFechaPago2();")),
                HTML::listaSeleccionSimple("*mes_generacion", $textos["MES_PLANILLA"],$meses,$mes, array("title" => $textos["AYUDA_MES_PLANILLA"], "class" => "selectorFecha", "onchange"=>"cargarFechaPago2();")),
                HTML::listaSeleccionSimple("*opcion_movimientos", $textos["MOVIMIENTOS"],$forma_listar,1, array("title" => $textos["AYUDA_MOVIMIENTOS"])),
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

        // Definicion de botones
        $botones = array(HTML::boton("botonAceptar", $textos["ACEPTAR"],"adicionarItem();", "aceptar"));

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


    // Enviar datos para la generacion del formulario al script que origino la peticion
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

} elseif (!empty($forma_procesar)) {

    $error   = false;
    $mensaje = $textos["EXITO_ELIMINAR_MOVIMINETOS"];

    $tablas_movimientos_automaticos = array(
        "movimientos_salarios",
        "movimientos_salud",
        "movimientos_pension",
        "movimientos_auxilio_transporte",
        "movimiento_control_prestamos_empleados",
        "movimiento_cuenta_por_cobrar_descuento",
        "movimientos_salario_retroactivo"
    );

    $tablas_movimientos_manuales = array(
        "movimiento_novedades_manuales"
    );

    $tablas_movimientos_manuales_tiempo = array(
        "movimiento_tiempos_laborados",
        "reporte_incapacidades",
        "movimiento_control_prestamos_empleados",
        "movimiento_tiempos_no_laborados_dias",
        "movimiento_tiempos_no_laborados_horas",
        "movimiento_novedades_manuales"
    );

    if(!isset($forma_sucursales)){
        $error   = true;
        $mensaje = $textos["ERROR_SUCURSAL_VACIA"];
    }elseif(empty($forma_codigo_planilla)){
        $error   = true;
        $mensaje = $textos["ERROR_CODIGO_PLANILLA"];
    }elseif(empty($forma_fecha_pago)){
        $error   = true;
        $mensaje = $textos["ERROR_FECHA_PAGO"];
    }elseif(empty($forma_periodo)){
        $error   = true;
        $mensaje = $textos["ERROR_PERIODO"];
    }else{

        $mensaje_general = $textos["ERROR_ELIMINAR_MOVIMIENTOS"];
        $nombres_sucusales = "";

        foreach ($forma_sucursales as $codigo_sucursal){

            $condicion_movimientos = "ano_generacion='$forma_ano_generacion' AND mes_generacion='$forma_mes_generacion' AND codigo_planilla='$forma_codigo_planilla' AND periodo_pago='$forma_periodo' AND fecha_pago_planilla='$forma_fecha_pago'";
            $consulta_forma_pago  = SQL::seleccionar(array("forma_pago_planillas"),array("*"),$condicion_movimientos." AND codigo_sucursal_recibe='$codigo_sucursal'");
            $pago_planilla         = SQL::obtenerValor("forma_pago_planillas","pagado",$condicion_movimientos." AND codigo_sucursal_recibe='$codigo_sucursal'");

            if($pago_planilla=='0' || !$pago_planilla){

                if($forma_opcion_movimientos=="1" || $forma_opcion_movimientos=="3"){
                    foreach($tablas_movimientos_automaticos AS $tabla){
                        if ($tabla=='movimiento_control_prestamos_empleados'){
                            $consulta_movimientos = SQL::seleccionar(array("movimiento_control_prestamos_empleados"),array("*"),$condicion_movimientos." AND codigo_sucursal='$codigo_sucursal'");

                            if (SQL::filasDevueltas($consulta_movimientos)){

                                while($datos_prestamo = SQL::filaEnObjeto($consulta_movimientos)){
                                    $condicion = "documento_identidad_empleado='$datos_prestamo->documento_identidad_empleado'";
                                    $condicion .= " AND consecutivo='$datos_prestamo->consecutivo_fecha_pago'";
                                    $condicion .= " AND fecha_generacion='$datos_prestamo->fecha_generacion_control'";
                                    $condicion .= " AND concepto_prestamo='$datos_prestamo->concepto_prestamo'";
                                    $condicion .= " AND fecha_pago='$datos_prestamo->fecha_pago'";
                                    $modificar_prestamo = SQL::modificar("fechas_prestamos_empleados",array("pagada"=>"0"),$condicion);
                                }
                            }
                        }
                        $eliminar =SQL::eliminar($tabla, $condicion_movimientos." AND codigo_sucursal='$codigo_sucursal'");
                    }
                }

                if($forma_opcion_movimientos=="2" || $forma_opcion_movimientos=="3"){
                    foreach($tablas_movimientos_manuales AS $tabla){
                        $eliminar =SQL::eliminar($tabla, $condicion_movimientos." AND codigo_sucursal='$codigo_sucursal'");
                    }
                }

                foreach($tablas_movimientos_manuales_tiempo AS $tabla){
                    if ($tabla=='movimiento_control_prestamos_empleados'){
                        $consulta_movimientos = SQL::seleccionar(array("movimiento_control_prestamos_empleados"),array("*"),$condicion_movimientos." AND codigo_sucursal='$codigo_sucursal'");

                        if (SQL::filasDevueltas($consulta_movimientos)){

                            while($datos_prestamo = SQL::filaEnObjeto($consulta_movimientos)){
                                $condicion = "documento_identidad_empleado='$datos_prestamo->documento_identidad_empleado'";
                                $condicion .= " AND consecutivo='$datos_prestamo->consecutivo_fecha_pago'";
                                $condicion .= " AND fecha_generacion='$datos_prestamo->fecha_generacion_control'";
                                $condicion .= " AND concepto_prestamo='$datos_prestamo->concepto_prestamo'";
                                $condicion .= " AND fecha_pago='$datos_prestamo->fecha_pago'";
                                $modificar_prestamo = SQL::modificar("fechas_prestamos_empleados",array("pagada"=>"0"),$condicion);
                            }
                        }
                    }
                    $modificar =SQL::modificar($tabla,array("contabilizado" => "0"),$condicion_movimientos." AND codigo_sucursal='$codigo_sucursal'");
                }

                ////////////////////////////////////////////////////////////////////////////////////////
                //////////// Elimino las forma de pago y todos los documentos que se generaron /////////

                if(SQL::filasDevueltas($consulta_forma_pago)){
                    $datos_forma_pago     = SQL::filaEnObjeto($consulta_forma_pago);
                    $forma_pago_utilizada = $datos_forma_pago->forma_pago;
                    if($forma_pago_utilizada == '1' || $forma_pago_utilizada == '2'){

                        $nombre_tabla = "";
                        if($forma_pago_utilizada == '1'){
                            $nombre_tabla= "forma_pago_planillas_nomina";
                        }else{
                            $nombre_tabla= "forma_pago_planillas_efectivo";
                        }
                        $consulta_forma_pago_nomina = SQL::seleccionar(array($nombre_tabla),array("*"), $condicion_movimientos." AND codigo_sucursal='$codigo_sucursal'");
                        $eliminar_forma_pago_nomina = SQL::eliminar($nombre_tabla, $condicion_movimientos." AND codigo_sucursal='$codigo_sucursal'");
                        if(!$eliminar_forma_pago_nomina){
                            $error        = false;
                            $mensaje      = $textos["ERROR_FORMA_PAGO_NOMINA"];
                        }else{
                            while($datos_forma_pago_nomina = SQL::filaEnObjeto($consulta_forma_pago_nomina)){
                                $condicion_consecutivo_documento  = " codigo_sucursal='$datos_forma_pago_nomina->codigo_sucursal_consecutivo_documento' AND codigo_tipo_documento='$datos_forma_pago_nomina->codigo_tipo_documento_consecutivo_documento'";
                                $condicion_consecutivo_documento .= " AND documento_identidad_tercero='$datos_forma_pago_nomina->documento_identidad_tercero_consecutivo_documento' AND fecha_registro='$datos_forma_pago_nomina->fecha_registro_consecutivo_documento' AND consecutivo='$datos_forma_pago_nomina->consecutivo_documento'";
                                $eliminar = SQL::eliminar("consecutivo_documentos",$condicion_consecutivo_documento);
                                if(!$eliminar){
                                    $error        = false;
                                    $mensaje      = $textos["ERROR_ELIMINANDO_DOCUMENTO"];
                                }

                            }
                        }
                        $eliminar = SQL::eliminar("forma_pago_planillas",$condicion_movimientos." AND codigo_sucursal_recibe='$codigo_sucursal'");

                    }elseif($forma_pago_utilizada == '3' || $forma_pago_utilizada == '4'){
                        $nombre_tabla = "";
                        if($forma_pago_utilizada == '3'){
                            $nombre_tabla= "forma_pago_planillas_sucursal";
                        }else{
                            $nombre_tabla= "forma_pago_planillas_empleado";
                        }

                        $consulta_forma_pago = SQL::seleccionar(array($nombre_tabla),array("*"), $condicion_movimientos." AND codigo_sucursal='$codigo_sucursal'");
                        $eliminar_forma_pago= SQL::eliminar("$nombre_tabla", $condicion_movimientos." AND codigo_sucursal='$codigo_sucursal'");

                        if(!$eliminar_forma_pago){
                            $error        = false;
                            $mensaje      = $textos["ERROR_FORMA_PAGO_NOMINA"];
                        }else{
                            ///////////eliminar consecutivo documento y/o cheque generado//////////////
                            while($datos_forma_pago = SQL::filaEnObjeto($consulta_forma_pago)){
                                $condicion_consecutivo_documento  = " codigo_sucursal='$datos_forma_pago->codigo_sucursal_consecutivo_documento' AND codigo_tipo_documento='$datos_forma_pago->codigo_tipo_documento_consecutivo_documento'";
                                $condicion_consecutivo_documento .= " AND documento_identidad_tercero='$datos_forma_pago->documento_identidad_tercero_consecutivo_documento' AND fecha_registro='$datos_forma_pago->fecha_registro_consecutivo_documento' AND consecutivo='$datos_forma_pago->consecutivo_documento'";
                                $eliminar = SQL::eliminar("consecutivo_documentos",$condicion_consecutivo_documento);
                                if(!$eliminar){
                                    $error        = false;
                                    $mensaje      = $textos["ERROR_ELIMINANDO_DOCUMENTO"];
                                }else{
                                    $condicion_consecutivo_cheque  = " codigo_sucursal='$datos_forma_pago->codigo_sucursal_consecutivo_cheque' AND codigo_tipo_documento='$datos_forma_pago->codigo_tipo_documento_consecutivo_cheque'";
                                    $condicion_consecutivo_cheque .= " AND codigo_banco='$datos_forma_pago->codigo_banco_consecutivo_cheque' AND numero='$datos_forma_pago->numero_consecutivo_cheque' AND consecutivo='$datos_forma_pago->consecutivo_cheque'";
                                    $eliminar = SQL::eliminar("consecutivo_cheques",$condicion_consecutivo_cheque);
                                }
                            }
                        }
                        $eliminar = SQL::eliminar("forma_pago_planillas",$condicion_movimientos." AND codigo_sucursal_recibe='$codigo_sucursal'");
                    }
                }

            }else{
                $nombre_sucursal    = SQL::obtenerValor("sucursales","nombre","codigo='$codigo_sucursal'");
                $nombres_sucusales .= " - ".$nombre_sucursal."\n";
            }
        }


        ///////////////////////////////////////////////////////
        if(!empty($nombres_sucusales)){
            $mensaje = $textos["PLANILLA_PAGADA"].$nombres_sucusales;
        }
    }

    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

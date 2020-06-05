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
* de APTITUD PARA UN PROPÓITO DETERMINADO. Consulte los detalles de
* la Licencia Pública General GNU para obtener una información más
* detallada.
*
* Debería haber recibido una copia de la Licencia Pública General GNU
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
**/

if (isset($url_completar)) {
    if (($url_item) == "selector1") {
        echo SQL::datosAutoCompletar("seleccion_empleados", $url_q);
    }
exit;
}

/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {

     /*** Verificar que se haya enviado el ID del elemento a modificar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_MODIFICAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {
        $vistaConsulta  = "asignacion_turnos";
        $columnas       = SQL::obtenerColumnas($vistaConsulta);
        $consulta       = SQL::seleccionar(array($vistaConsulta), $columnas, "consecutivo = $url_id");
        $datos          = SQL::filaEnObjeto($consulta);
        $error          = "";
        $titulo         = $componente->nombre;

        $empleado       = SQL::obtenerValor("seleccion_empleados", "SUBSTRING_INDEX(nombre_completo,'|',1)", "id = '".$datos->documento_identidad_empleado."'");

        if($datos->dominicales==0){
            $si_dominical=false;
            $no_dominical=true;
        }

        if($datos->festivos==0){
            $si_festivos=false;
            $no_festivos=true;
        }

        $fecha = str_replace('-','/',$datos->fecha_inicial).' - '.str_replace('-','/',$datos->fecha_final);


     /*** Definición de pestaña Basica ***/
    $formularios["PESTANA_BASICA"] = array(
           array(
                HTML::campoTextoCorto("*selector1", $textos["EMPLEADO"], 40, 255, $empleado, array("title" => $textos["EMPLEADO"],"class" => "autocompletable", "onblur" => "validarItem(this);" ))
                .HTML::campoOculto("documento_empleado",$datos->documento_identidad_empleado)
            ),
            array(
                HTML::listaSeleccionSimple("*codigo_turno", $textos["TURNO_LABORAL"], HTML::generarDatosLista("turnos_laborales", "codigo", "descripcion","codigo > '0'"), $datos->codigo_turno, array("title" => $textos["AYUDA_TURNO_LABORAL"]))
                .HTML::campoOculto("codigo_turno_actual",$datos->codigo_turno),
                HTML::listaSeleccionSimple("*codigo_sucursal", $textos["SUCURSAL"], HTML::generarDatosLista("sucursales", "codigo", "nombre","codigo > '0'"), $datos->codigo_sucursal, array("title" => $textos["SUCURSAL"]))
            ),
            array(

                HTML::campoTextoCorto("*fechas", $textos["FECHA_INICIAL"].'  -  '.$textos["FECHA_FINAL"], 25, 25, $fecha, array("title" => $textos["FECHA_INICIAL"], "class" => "fechaRango"))
            ),
            array(
                HTML::mostrarDato("nombre_dominicales", $textos["PERMITE_FESTIVOS"], ""),
                HTML::marcaSeleccion("dominicales", $textos["FESTIVOS_SI"], 1, $si_dominical, array("id" => "si_dominical")),
                HTML::marcaSeleccion("dominicales", $textos["FESTIVOS_NO"], 0, $no_dominical, array("id" => "no_dominical"))
            ),
            array(
                HTML::mostrarDato("nombre_festivos", $textos["PAGA_DOMINICAL"], ""),
                HTML::marcaSeleccion("festivos", $textos["PAGA_DOMINICAL_SI"], 1, $si_festivos, array("id" => "si_festivos")),
                HTML::marcaSeleccion("festivos", $textos["PAGA_DOMINICAL_NO"], 0, $no_festivos, array("id" => "no_festivos"))
            )
        );

            /*** Definición de botones ***/
            $botones = array(
                HTML::boton("botonAceptar", $textos["ACEPTAR"], "modificarItem('$url_id');", "aceptar")
            );

            $contenido = HTML::generarPestanas($formularios, $botones);

    }

    /*** Enviar datos para la generación del formulario al script que originó la petición ***/
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

/*** Modificar el elemento seleccionado ***/
} elseif (!empty($forma_procesar)) {
    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_MODIFICADO"];

    $dias = array('_domingo','_lunes','_martes','_miercoles','_jueves','_viernes','_sabado');

    $fechas            = explode('-',$forma_fechas);
    $fecha_inicial     = trim($fechas[0]);
    $fecha_final       = trim($fechas[1]);

    $consulta          = SQL::seleccionar(array("asignacion_turnos"), array("codigo_turno"), "(((fecha_inicial BETWEEN '".$fecha_inicial."' AND '".$fecha_final."') OR (fecha_final BETWEEN '".$fecha_inicial."' AND '".$fecha_final."')) OR (('".$fecha_inicial."' BETWEEN fecha_inicial AND fecha_final) OR ('".$fecha_final."' BETWEEN fecha_inicial AND fecha_final))) AND documento_identidad_empleado = '".$forma_documento_empleado."' AND codigo_turno != '".$forma_codigo_turno_actual."'");
    $filasRangos       = SQL::filasDevueltas($consulta);

    $consulta2         = SQL::seleccionar(array("asignacion_turnos"), array("*"), "(((fecha_inicial BETWEEN '".$fecha_inicial."' AND '".$fecha_final."') OR (fecha_final BETWEEN '".$fecha_inicial."' AND '".$fecha_final."')) OR (('".$fecha_inicial."' BETWEEN fecha_inicial AND fecha_final) OR ('".$fecha_final."' BETWEEN fecha_inicial AND fecha_final))) AND documento_identidad_empleado = '".$forma_documento_empleado."' AND codigo_turno = '".$forma_codigo_turno."' AND codigo_turno != '".$forma_codigo_turno_actual."'");
    $filasRangosyTurno = SQL::filasDevueltas($consulta2);

    $cruze = 1;

    if($filasRangos>0 && $filasRangosyTurno==0){
        while ($datos = SQL::filaEnObjeto($consulta)){

            for($i=0;$i<7;$i++){
            /////////////////////////////////DATOS DEL TURNO ESCOGIDO POR PANTALLA/////////////////////////////////
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
                $tipoTurno      = (int)SQL::obtenerValor("turnos_laborales","tipo_turno".$dias[$i],"codigo='".$forma_codigo_turno."'");

                $horaInicioT1   = SQL::obtenerValor("turnos_laborales","hora_inicial_turno1".$dias[$i],"codigo='".$forma_codigo_turno."'");
                $horaFinT1      = SQL::obtenerValor("turnos_laborales","hora_final_turno1".$dias[$i],"codigo='".$forma_codigo_turno."'");

                $horaInicioT2   = SQL::obtenerValor("turnos_laborales","hora_inicial_turno2".$dias[$i],"codigo='".$forma_codigo_turno."'");
                $horaFinT2      = SQL::obtenerValor("turnos_laborales","hora_final_turno2".$dias[$i],"codigo='".$forma_codigo_turno."'");

                $horaInicioT1   = explode(':',$horaInicioT1);
                $horaFinT1      = explode(':',$horaFinT1);

                $horaInicioT2   = explode(':',$horaInicioT2);
                $horaFinT2      = explode(':',$horaFinT2);

                $horaInicioT1   = ((int)$horaInicioT1[0]*60)+((int)$horaInicioT1[1]);
                $horaFinT1      = ((int)$horaFinT1[0]*60)+((int)$horaFinT1[1]);

                if($tipoTurno==0){
                    $horaInicioT2   = ((int)$horaInicioT2[0]*60)+((int)$horaInicioT2[1]);
                    $horaFinT2      = ((int)$horaFinT2[0]*60)+((int)$horaFinT2[1]);
                }
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
            ///////////////////////////////////////////////////////////////////////////////////////////////////////

                $codigo_turno = $datos->codigo_turno;

                $comp1             = 1;
                $comp2             = 1;
                $comp3             = 1;
                $comp4             = 1;

                $tipoTurno2     = (int)SQL::obtenerValor("turnos_laborales","tipo_turno".$dias[$i],"codigo='".$codigo_turno."'");

                $horaInicioTa   = SQL::obtenerValor("turnos_laborales","hora_inicial_turno1".$dias[$i],"codigo='".$codigo_turno."'");
                $horaFinTa      = SQL::obtenerValor("turnos_laborales","hora_final_turno1".$dias[$i],"codigo='".$codigo_turno."'");

                $horaInicioTb   = SQL::obtenerValor("turnos_laborales","hora_inicial_turno2".$dias[$i],"codigo='".$codigo_turno."'");
                $horaFinTb      = SQL::obtenerValor("turnos_laborales","hora_final_turno2".$dias[$i],"codigo='".$codigo_turno."'");

                $horaInicioTa   = explode(':',$horaInicioTa);
                $horaFinTa      = explode(':',$horaFinTa);

                $horaInicioTb   = explode(':',$horaInicioTb);
                $horaFinTb      = explode(':',$horaFinTb);

                $horaInicioTa   = ((int)$horaInicioTa[0]*60)+((int)$horaInicioTa[1]);
                $horaFinTa      = ((int)$horaFinTa[0]*60)+((int)$horaFinTa[1]);

                if($tipoTurno2==0){
                    $horaInicioTb   = ((int)$horaInicioTb[0]*60)+((int)$horaInicioTb[1]);
                    $horaFinTb      = ((int)$horaFinTb[0]*60)+((int)$horaFinTb[1]);
                }

                if($tipoTurno==0 && $tipoTurno2==0){

                    if(($horaInicioT1>=$horaInicioTa && $horaFinT1<=$horaFinTa)||($horaInicioT1<=$horaInicioTa && $horaFinT1>=$horaFinTa)||($horaInicioT1>=$horaInicioTa && $horaFinT1>=$horaFinTa && $horaInicioT1<$horaFinTa)||($horaInicioT1<=$horaInicioTa && $horaFinT1<=$horaFinTa && $horaFinT1>$horaInicioTa)){//t1 con ta
                        $comp1=0;
                    }

                    if(($horaInicioT1>=$horaInicioTb && $horaFinT1<=$horaFinTb)||($horaInicioT1<=$horaInicioTb && $horaFinT1>=$horaFinTb)||($horaInicioT1>=$horaInicioTb && $horaFinT1>=$horaFinTb && $horaInicioT1<$horaFinTb)||($horaInicioT1<=$horaInicioTb && $horaFinT1<=$horaFinTb && $horaFinT1>$horaInicioTb)){//t1 con tb
                        $comp2=0;
                    }

                    if(($horaInicioT2>=$horaInicioTa && $horaFinT2<=$horaFinTa)||($horaInicioT2<=$horaInicioTa && $horaFinT2>=$horaFinTa)||($horaInicioT2>=$horaInicioTa && $horaFinT2>=$horaFinTa && $horaInicioT2<$horaFinTa)||($horaInicioT2<=$horaInicioTa && $horaFinT2<=$horaFinTa && $horaFinT2>$horaInicioTa)){//t2 con ta
                        $comp3=0;
                    }

                    if(($horaInicioT2>=$horaInicioTb && $horaFinT2<=$horaFinTb)||($horaInicioT2<=$horaInicioTb && $horaFinT2>=$horaFinTb)||($horaInicioT2>=$horaInicioTb && $horaFinT2>=$horaFinTb && $horaInicioT2<$horaFinTb)||($horaInicioT2<=$horaInicioTb && $horaFinT2<=$horaFinTb && $horaFinT2>$horaInicioTb)){//t2 con tb
                        $comp4=0;
                    }
                }elseif($tipoTurno==1 && $tipoTurno2==0){

                    if(($horaInicioT1>=$horaInicioTa && $horaFinT1<=$horaFinTa)||($horaInicioT1<=$horaInicioTa && $horaFinT1>=$horaFinTa)||($horaInicioT1>=$horaInicioTa && $horaFinT1>=$horaFinTa && $horaInicioT1<$horaFinTa)||($horaInicioT1<=$horaInicioTa && $horaFinT1<=$horaFinTa && $horaFinT1>$horaInicioTa)){//t1 con ta
                        $comp1=0;
                    }

                    if(($horaInicioT1>=$horaInicioTb && $horaFinT1<=$horaFinTb)||($horaInicioT1<=$horaInicioTb && $horaFinT1>=$horaFinTb)||($horaInicioT1>=$horaInicioTb && $horaFinT1>=$horaFinTb && $horaInicioT1<$horaFinTb)||($horaInicioT1<=$horaInicioTb && $horaFinT1<=$horaFinTb && $horaFinT1>$horaInicioTb)){//t1 con tb
                        $comp2=0;
                    }
                }elseif($tipoTurno==0 && $tipoTurno2==1){

                    if(($horaInicioT1>=$horaInicioTa && $horaFinT1<=$horaFinTa)||($horaInicioT1<=$horaInicioTa && $horaFinT1>=$horaFinTa)||($horaInicioT1>=$horaInicioTa && $horaFinT1>=$horaFinTa && $horaInicioT1<$horaFinTa)||($horaInicioT1<=$horaInicioTa && $horaFinT1<=$horaFinTa && $horaFinT1>$horaInicioTa)){//t1 con ta
                        $comp1=0;
                    }

                    if(($horaInicioT2>=$horaInicioTa && $horaFinT2<=$horaFinTa)||($horaInicioT2<=$horaInicioTa && $horaFinT2>=$horaFinTa)||($horaInicioT2>=$horaInicioTa && $horaFinT2>=$horaFinTa && $horaInicioT2<$horaFinTa)||($horaInicioT2<=$horaInicioTa && $horaFinT2<=$horaFinTa && $horaFinT2>$horaInicioTa)){//t2 con ta
                        $comp3=0;
                    }
                }elseif($tipoTurno==1 && $tipoTurno2==1){

                    if(($horaInicioT1>=$horaInicioTa && $horaFinT1<=$horaFinTa)||($horaInicioT1<=$horaInicioTa && $horaFinT1>=$horaFinTa)||($horaInicioT1>=$horaInicioTa && $horaFinT1>=$horaFinTa && $horaInicioT1<$horaFinTa)||($horaInicioT1<=$horaInicioTa && $horaFinT1<=$horaFinTa && $horaFinT1>$horaInicioTa)){//t1 con ta
                        $comp1=0;
                    }
                }
                $cruze = $comp1*$comp2*$comp3*$comp4;

                if($cruze==0){
                    break;
                }
            }

            if($cruze==0){
                break;
            }
        }
    }

    if (empty($forma_selector1)){
        $error = true;
        $mensaje = $textos["ERROR_EMPLEADO_VACIO"];
    }elseif(!SQL::existeItem("seleccion_empleados", "id", $forma_documento_empleado)){
        $error = true;
        $mensaje = $textos["ERROR_EMPLEADO_NO_EXISTE"];
    }elseif($filasRangosyTurno>0){
        $error = true;
        $mensaje = $textos["ERROR_CRUCE_TURNOS"];
    }elseif($cruze==0){
        $error = true;
        $mensaje = $textos["ERROR_CRUCE_TURNOS"];
    }else{

        $fecha_ingreso          = SQL::obtenerValor("sucursal_contrato_empleados","max(fecha_ingreso)","documento_identidad_empleado='".$forma_documento_empleado."' and codigo_sucursal='".$forma_codigo_sucursal."'");
        $empresa                = SQL::obtenerValor("sucursal_contrato_empleados","codigo_empresa","documento_identidad_empleado='".$forma_documento_empleado."' and fecha_ingreso='".$fecha_ingreso."' and codigo_sucursal='".$forma_codigo_sucursal."'");
        $fecha_ingreso_sucursal = SQL::obtenerValor("sucursal_contrato_empleados","fecha_ingreso_sucursal","documento_identidad_empleado='".$forma_documento_empleado."' and fecha_ingreso='".$fecha_ingreso."' and codigo_sucursal='".$forma_codigo_sucursal."'");

        $datos = array (
            "codigo_empresa"                => $empresa,
            "documento_identidad_empleado"  => $forma_documento_empleado,
            "fecha_ingreso"                 => $fecha_ingreso,
            "codigo_sucursal"  	            => $forma_codigo_sucursal,
            "fecha_ingreso_sucursal"  	    => $fecha_ingreso_sucursal,
            "consecutivo"                   => $forma_id,
            "codigo_turno"  	            => $forma_codigo_turno,
            "fecha_inicial"                 => $fecha_inicial,
            "fecha_final"  	                => $fecha_final,
            "dominicales"                   => $forma_dominicales,
            "festivos"                      => $forma_festivos,
            "codigo_usuario_modifica"       => $sesion_codigo_usuario
        );

        $insertar = SQL::modificar("asignacion_turnos", $datos,"consecutivo = $forma_id");

        /*** Error de insercón ***/
        if (!$insertar) {
            $error   = true;
            $mensaje = $textos["ERROR_MODIFICAR_ITEM"];
            $mensaje = mysql_error();
        }
    }

    /*** Enviar datos con la respuesta del proceso al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>


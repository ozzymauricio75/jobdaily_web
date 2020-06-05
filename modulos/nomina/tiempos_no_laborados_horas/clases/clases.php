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
function conversor_segundos($seg_ini,$textos) {

    $horas = floor($seg_ini/3600);
    $minutos = floor(($seg_ini-($horas*3600))/60);
    $segundos = $seg_ini-($horas*3600)-($minutos*60);
    return $horas.$textos['FOMARTO_HORA'].':'.$minutos.$textos['FOMARTO_MINUTO'];

}

function conversor_segundos_plano($seg_ini,$textos) {

    $horas = floor($seg_ini/3600);
    $minutos = floor(($seg_ini-($horas*3600))/60);
    $segundos = $seg_ini-($horas*3600)-($minutos*60);
    if ($horas < 10){
        $horas = "0".$horas;
    }
    if ($minutos < 10){
        $minutos = "0".$minutos;
    }
    return $horas.":".$minutos.":00";

}

function verificarHoraDentroTurno($url_documento_identidad,$url_hora_inicio,$url_hora_fin,$url_fecha_inicio,$url_sucursal,$dias,$textos){
    // Determino mediante el turno a tormar, si el de la asignacion o el del contrato dependiendo la fecha actual
    $fecha_actual= date("Y-m-d");
    $horas_generadas = array();
    $consulta_turno_empleado = SQL::seleccionar(array("asignacion_turnos"),array("*")," ('$fecha_actual' BETWEEN fecha_inicial AND fecha_final) AND documento_identidad_empleado='$url_documento_identidad' ","","fecha_inicial DESC ",0,1);
    $datos_empleado          = SQL::filaEnObjeto($consulta_turno_empleado);

    if($datos_empleado){
        $consulta_turno   = SQL::seleccionar(array("turnos_laborales"),array("*"),"codigo='$datos_empleado->codigo_turno'");
        $datos_turno      = SQL::filaEnObjeto($consulta_turno);

    }else{
        $consulta_turno_empleado = SQL::seleccionar(array("sucursal_contrato_empleados"),array("*"),"documento_identidad_empleado='$url_documento_identidad' AND codigo_sucursal = '$url_sucursal' AND fecha_ingreso_sucursal <= '$url_fecha_inicio'","","fecha_ingreso_sucursal DESC ",0,1);
        $datos_empleado          = SQL::filaEnObjeto($consulta_turno_empleado);
        $consulta_turno          = SQL::seleccionar(array("turnos_laborales"),array("*"),"codigo='$datos_empleado->codigo_turno_laboral'");
        $datos_turno             = SQL::filaEnObjeto($consulta_turno);
    }

    ///////////Armo el horario del turno de acuerdo al dia y al tipo (si es continuo o discontinuo)/////////////
    $fecha_completa = getdate(strtotime($url_fecha_inicio));
    $dia            = $dias[$fecha_completa['wday']];
    $campo_turno    = "tipo_turno_".$dia;

    $nombre_hora_inicial_turno1 = "hora_inicial_turno1_".$dia;
    $nombre_hora_final_turno1   = "hora_final_turno1_".$dia;
    $nombre_hora_inicial_turno2 = "hora_inicial_turno2_".$dia;
    $nombre_hora_final_turno2   = "hora_final_turno2_".$dia;
    $dia_descanso               = "dia_descanso_".$dia;
    $descripcion_turno = "";
    if($datos_turno->$dia_descanso == "0"){
        if($datos_turno->$campo_turno=="1"){
            $descripcion_turno = $textos["TIPO_TURNO_CONTINUO"].$datos_turno->$nombre_hora_inicial_turno1.$textos["A"].$datos_turno->$nombre_hora_final_turno1;
        }else{
             $descripcion_turno  = $textos["TIPO_TURNO_CONTINUO"].$datos_turno->$nombre_hora_inicial_turno1.$textos["A"].$datos_turno->$nombre_hora_final_turno1;
             $descripcion_turno .= $textos["Y"].$datos_turno->$nombre_hora_inicial_turno2.$textos["A"].$datos_turno->$nombre_hora_final_turno2;

        }
    }else{
        $descripcion_turno = $textos["MESAJE_DIA_DESCASO"];
    }
    //echo var_dump($descripcion_turno);

    $respuesta   = array();
    $respuesta[] = $descripcion_turno;
    $respuesta[] = $datos_turno->$campo_turno;
    $respuesta[] = $datos_turno->$dia_descanso;

    $respuesta[] = $datos_turno->$nombre_hora_inicial_turno1;
    $respuesta[] = $datos_turno->$nombre_hora_final_turno1;
    $respuesta[] = $datos_turno->$nombre_hora_inicial_turno2;
    $respuesta[] = $datos_turno->$nombre_hora_final_turno2;

    return $respuesta;
}
?>

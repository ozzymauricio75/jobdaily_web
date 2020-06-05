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

function separar_Horas($hora_inicial,$hora_final){
    $horas = array();
    $horas[] = $hora_inicial;
    //$horas[] = $hora_inicial;
    $rango_hora = ceil((int) (strtotime($hora_final) - strtotime($hora_inicial)) / 3600);
    $arreglo_hora_inicial = explode(":",$hora_inicial);
    $arreglo_hora_final = explode(":",$hora_final);

    $hora_inicial_contar  = (int)$arreglo_hora_inicial[0];
    $hora_final_contar    = (int)$arreglo_hora_final[0];

    for($i=1;$i<=$rango_hora;$i++){
        $horas[] = str_pad($hora_inicial_contar+$i.":00",5, "0", STR_PAD_LEFT);
        if(($hora_inicial_contar+$i)==$hora_final_contar){
            break;
        }
    }
    $ultimo_elemento = end($horas);

    if($ultimo_elemento!=$hora_final){
        $horas[] = $hora_final;
    }

    //echo var_dump($horas);
    return $horas;
}

function generar_Fechas($hora_inicial, $hora_final, $fecha_inicio, $fecha_fin, $codigo_turno, $documento_identidad_empleado, $tipo_turno,$codigo_sucursal,$dia_semana,$textos) {//Genera un listado de fechas a partir de la fecha dada hasta tantos dias despues dados
    $horas = separar_Horas($hora_inicial,$hora_final);
    $hora_generada = $horas[0];
    $rango_hora = count($horas);
    $rango_dias = (int) (strtotime($fecha_fin) - strtotime($fecha_inicio)) / (60 * 60 * 24);
    $anexo_contable_de_transaccion_tiempo = "";
    $cantidad = 0;
    $horas[0].=":00";

    ///////////////////////
    $nombre_hora_inicial_turno1 = "hora_inicial_turno1_".$dia_semana;
    $nombre_hora_final_turno1   = "hora_final_turno1_".$dia_semana;
    $nombre_hora_inicial_turno2 = "hora_inicial_turno2_".$dia_semana;
    $nombre_hora_final_turno2   = "hora_final_turno2_".$dia_semana;

    //////////////////////
    //echo var_dump($rango_hora);
    //$consulta = SQL::seleccionar(array("plan_contable PC,job_transacciones_contables_empleado TCE,job_transacciones_tiempo TT"),array("PC.codigo_anexo_contable"),"TT.codigo_transaccion_contable=TCE.codigo AND TCE.codigo_contable=PC.codigo_contable AND TT.codigo=$url_transaccion");
    $horas_laborales = array();
    for ($i = 1; $i < $rango_hora; $i++) {

        $hora_anterior = $hora_generada;
        $hora_generada = $horas[$i];
        //$hora_generada = explode(":", $hora_generada);
        //$hora_generada = ((int) $hora_generada[0] + 1) . ":" . $hora_generada[1];
       // $hora_generada = str_pad($hora_generada, 5, "0", STR_PAD_LEFT);
        $hora_generada .=":00";
        $hora_anterior .=":00";
        ///Condicion si el turno es continuo
        $consulta_turno = SQL::seleccionar(array("turnos_laborales"), array("*"), "codigo='$codigo_turno'");
        $datos_turno    = SQL::filaEnObjeto($consulta_turno);
        if($tipo_turno == 1){

            $consulta_rango_hora = SQL::seleccionar(array("turnos_laborales"), array("*"), "codigo='$codigo_turno' AND ('$hora_generada' BETWEEN $nombre_hora_inicial_turno1 AND $nombre_hora_final_turno1) AND ('$hora_anterior' BETWEEN $nombre_hora_inicial_turno1 AND $nombre_hora_final_turno1)");
            $datos_rango_hora = SQL::filaEnObjeto($consulta_rango_hora);
            if(SQL::filasDevueltas($consulta_rango_hora) == 1){
                $hora_inicio_turno = $datos_rango_hora->$nombre_hora_final_turno1;
                $consulta_mayor   = SQL::seleccionar(array("turnos_laborales"), array("*"), "codigo='$codigo_turno' AND '$hora_inicial' >= $nombre_hora_inicial_turno1");
                if(SQL::filasDevueltas($consulta_mayor)== 1)
                {
                    $hora_inicio_turno=$hora_inicial;

                }else{
                    $hora_inicio_turno = $datos_rango_hora->$nombre_hora_inicial_turno1;
                }
                $opcion = 1;


            }
        }else{
            $consulta_rango_hora = SQL::seleccionar(array("turnos_laborales"), array("*"), "codigo='$codigo_turno' AND ('$hora_generada' BETWEEN $nombre_hora_inicial_turno1 AND $nombre_hora_final_turno1) AND ('$hora_anterior' BETWEEN $nombre_hora_inicial_turno1 AND $nombre_hora_final_turno1)");
            $datos_rango_hora = SQL::filaEnObjeto($consulta_rango_hora);
            if(SQL::filasDevueltas($consulta_rango_hora) == 1){
                $consulta_mayor   = SQL::seleccionar(array("turnos_laborales"), array("*"), "codigo='$codigo_turno' AND '$hora_inicial' > $nombre_hora_inicial_turno1");
                if(SQL::filasDevueltas($consulta_mayor) !=0){
                    $hora_inicio_turno=$hora_inicial;
                }else{
                    $hora_inicio_turno = $datos_rango_hora->$nombre_hora_inicial_turno1;
                }
                $opcion = 1;
            }else{
                $consulta_rango_hora = SQL::seleccionar(array("turnos_laborales"), array("*"), "codigo='$codigo_turno' AND (('$hora_generada' BETWEEN $nombre_hora_inicial_turno2 AND $nombre_hora_final_turno2)) AND ('$hora_anterior' BETWEEN $nombre_hora_inicial_turno2 AND $nombre_hora_final_turno2) ");
                $datos_rango_hora = SQL::filaEnObjeto($consulta_rango_hora);
                if (SQL::filasDevueltas($consulta_rango_hora) == 1) {
                    $consulta_mayor   = SQL::seleccionar(array("turnos_laborales"), array("*"), "codigo='$codigo_turno' AND (('$hora_inicial' BETWEEN $nombre_hora_inicial_turno2 AND $nombre_hora_final_turno2))");
                    if(SQL::filasDevueltas($consulta_mayor) ==1){
                        $hora_inicio_turno=$hora_inicial;
                    }else{
                        $hora_inicio_turno = $datos_rango_hora->$nombre_hora_inicial_turno2;
                    }
                    $opcion = 2;
                }
            }
        }
        //$hora_generada = explode(":", $hora_generada);
        //$hora_generada = $hora_generada[0].":".$hora_generada[1];
        $fecha_generada = $fecha_inicio;
        /////////Permite pago de domingos y festivos///////////
        $permite_festivo = $datos_turno->permite_festivos;
        $paga_dominical  = $datos_turno->paga_dominical;
        $paga_festivo    = $datos_turno->paga_festivo;
        /////Si son Horas dentro del turno el numero de filas devueltas seria 1////
        if(SQL::filasDevueltas($consulta_rango_hora)){
            if($opcion == 1){
                $hora_inicio = $datos_rango_hora->$nombre_hora_inicial_turno1;
                $hora_final= $datos_rango_hora->$nombre_hora_final_turno1;
            }else{
                $hora_inicio = $datos_rango_hora->$nombre_hora_inicial_turno2;
                $hora_final = $datos_rango_hora->$nombre_hora_final_turno2;
            }
            for($c = 0; $c <= $rango_dias; $c++){
                $consulta_domingos_festivos = SQL::seleccionar(array("domingos_festivos"), array("*"), "fecha='$fecha_generada'");
                $datos_domingos_festivos = SQL::filaEnObjeto($consulta_domingos_festivos);
                //echo var_dump(SQL::filasDevueltas($consulta_domingos_festivos)."=>".$fecha_generada);
                $consulta_contrato_sucursal = SQL::seleccionar(array("sucursal_contrato_empleados"), array("*"), "documento_identidad_empleado='$documento_identidad_empleado' and codigo_sucursal='$codigo_sucursal' AND fecha_ingreso_sucursal <='$fecha_generada'", "", "fecha_ingreso_sucursal DESC", 0, 1);
                $datos_contrato_sucursal = SQL::filaEnObjeto($consulta_contrato_sucursal);
                /////Si existe alguna fecha igual a la generada el dia sera un domingo o un festivo////
                if((SQL::filasDevueltas($consulta_domingos_festivos) == 1) && ($permite_festivo) && ($paga_dominical) && ($datos_domingos_festivos->tipo==1)) {
                    //////Determino como que tipo de hora le pago////// WHERE ((Hora Between #00:00# And #06:01#));
                    $consulta_rango = SQL::seleccionar(array("turnos_laborales"), array("*"), "codigo='$codigo_turno' AND ('$hora_generada' BETWEEN '06:00' AND '22:00') OR (CONCAT('$hora_generada',':00') BETWEEN '$hora_inicio' AND '$hora_final') ");
                    if(SQL::filasDevueltas($consulta_rango) != 0){
                        //echo var_dump($hora_generada." Horas Normales");
                        $codigo_transaccion = $datos_contrato_sucursal->codigo_transaccion_dominicales;
                        $anexo_contable_de_transaccion_tiempo = determinarAnexo($codigo_transaccion, $documento_identidad_empleado,$codigo_sucursal);
                        $descripcion = "Horas Normales en Domingos y Festivos (dominical): Horas de domingos o festivos dentro del turno de 6:00 AM hasta 10:00 PM";
                        $nombre = SQL::obtenerValor("transacciones_tiempo","nombre","codigo='$codigo_transaccion'");//"Horas domingos y festivos";
                        if($codigo_transaccion=='0'){
                            $nombre = $textos["NO_EXISTEN_HORAS_DOMINICALES"];
                        }
                        if($opcion == 1){
                            $tipo = "A";
                        }else{
                            //echo var_dump($hora_inicio_turno);
                            $tipo = "AA";
                        }
                    }
                    else{
                        $consulta_rango = SQL::seleccionar(array("turnos_laborales"), array("*"), "codigo='$codigo_turno' AND (('$hora_generada' BETWEEN '22:01' AND '24:59') OR ('$hora_generada' BETWEEN '01:00' AND '06:00'))");
                        if(SQL::filasDevueltas($consulta_rango) != 0){
                            // echo var_dump($hora_generada." Horas recargo nocturno");
                            $codigo_transaccion = $datos_contrato_sucursal->codigo_transaccion_recargo_noche_dominicales;
                            $anexo_contable_de_transaccion_tiempo = determinarAnexo($codigo_transaccion, $documento_identidad_empleado,$codigo_sucursal);
                            $nombre = SQL::obtenerValor("transacciones_tiempo","nombre","codigo='$codigo_transaccion'");//"Horas recargo nocturno domingos y festivos";
                            if($codigo_transaccion=='0'){
                                $nombre = $textos["NO_EXISTEN_HORAS_NOCTURNAS_DOMINICALES"];
                            }
                            $descripcion = "Horas Recargo Nocturna en Domingos y Festivos : Horas de domingos o festivos dentro del turno de 10:01 PM hasta 00:00 PM  o de las 00:00 hasta las 6:00 AM";
                            if ($opcion == 1) {
                                $tipo = "B";
                            } else {
                                $tipo = "BB";
                            }
                            //echo var_dump($hora_anterior);
                            $hora_anterior = explode(":", $hora_anterior);
                            $hora_inicio_turno = ((int) $hora_anterior[0] - 1) . ":" . $hora_anterior[1];
                            //$hora_inicio_turno = str_pad($hora_inicio_turno, 5, "0", STR_PAD_LEFT);
                            //echo var_dump($hora_inicio_turno);
                        }
                    }
                } elseif((SQL::filasDevueltas($consulta_domingos_festivos) == 1) && ($permite_festivo) && ($paga_festivo) && ($datos_domingos_festivos->tipo==2)) {
                    //////Determino como que tipo de hora le pago////// WHERE ((Hora Between #00:00# And #06:01#));
                    $consulta_rango = SQL::seleccionar(array("turnos_laborales"), array("*"), "codigo='$codigo_turno' AND ('$hora_generada' BETWEEN '06:00' AND '22:00') OR (CONCAT('$hora_generada',':00') BETWEEN '$hora_inicio' AND '$hora_final') ");
                    if(SQL::filasDevueltas($consulta_rango) != 0){
                        //echo var_dump($hora_generada." Horas Normales");
                        $codigo_transaccion = $datos_contrato_sucursal->codigo_transaccion_dominicales;
                        $anexo_contable_de_transaccion_tiempo = determinarAnexo($codigo_transaccion, $documento_identidad_empleado,$codigo_sucursal);
                        $descripcion = "Horas Normales en Domingos y Festivos (dominical): Horas de domingos o festivos dentro del turno de 6:00 AM hasta 10:00 PM";
                        $nombre = SQL::obtenerValor("transacciones_tiempo","nombre","codigo='$codigo_transaccion'");//"Horas domingos y festivos";
                        if($codigo_transaccion=='0'){
                            $nombre = $textos["NO_EXISTEN_HORAS_DOMINICALES"];
                        }
                        if($opcion == 1){
                            $tipo = "A";
                        } else{
                            //echo var_dump($hora_inicio_turno);
                            $tipo = "AA";
                        }
                    } else{
                        $consulta_rango = SQL::seleccionar(array("turnos_laborales"), array("*"), "codigo='$codigo_turno' AND (('$hora_generada' BETWEEN '22:01' AND '24:59') OR ('$hora_generada' BETWEEN '01:00' AND '06:00'))");
                        if(SQL::filasDevueltas($consulta_rango) != 0){
                            // echo var_dump($hora_generada." Horas recargo nocturno");
                            $codigo_transaccion = $datos_contrato_sucursal->codigo_transaccion_recargo_noche_dominicales;
                            $anexo_contable_de_transaccion_tiempo = determinarAnexo($codigo_transaccion, $documento_identidad_empleado,$codigo_sucursal);
                            $nombre = SQL::obtenerValor("transacciones_tiempo","nombre","codigo='$codigo_transaccion'");//"Horas recargo nocturno domingos y festivos";
                            if($codigo_transaccion=='0'){
                                $nombre = $textos["NO_EXISTEN_HORAS_RECARGO_NOCTURNO_DOMINGO"];
                            }
                            $descripcion = "Horas Recargo Nocturna en Domingos y Festivos : Horas de domingos o festivos dentro del turno de 10:01 PM hasta 00:00 PM  o de las 00:00 hasta las 6:00 AM";
                            if ($opcion == 1) {
                                $tipo = "B";
                            } else {
                                $tipo = "BB";
                            }
                            //echo var_dump($hora_anterior);
                            $hora_anterior = explode(":", $hora_anterior);
                            $hora_inicio_turno = ((int) $hora_anterior[0] - 1) . ":" . $hora_anterior[1];
                            //$hora_inicio_turno = str_pad($hora_inicio_turno, 5, "0", STR_PAD_LEFT);
                            //echo var_dump($hora_inicio_turno);
                        }
                    }
                } else{
                    $consulta_rango = SQL::seleccionar(array("turnos_laborales"), array("*"), "codigo='$codigo_turno' AND ('$hora_generada' BETWEEN '06:00' AND '22:00') OR (CONCAT('$hora_generada',':00') BETWEEN '$hora_inicio' AND '$hora_final')  ");
                    if(SQL::filasDevueltas($consulta_rango) != 0){
                        $codigo_transaccion = $datos_contrato_sucursal->codigo_transaccion_normales;
                        $anexo_contable_de_transaccion_tiempo = determinarAnexo($codigo_transaccion, $documento_identidad_empleado,$codigo_sucursal);

                        $nombre = SQL::obtenerValor("transacciones_tiempo","nombre","codigo='$codigo_transaccion'"); //"Horas normales"
                        if($codigo_transaccion=='0'){
                            $nombre = $textos["NO_EXISTEN_HORAS_NORMALES"];
                        }
                        $descripcion = "Horas normales de lunes a sabado : Horas dentro del turno de 6:00 AM a 6:00 PM";

                        if($opcion == 1){
                            $tipo = "C";
                        }else{
                            $tipo = "CC";
                        }
                    }else{
                        $consulta_rango = SQL::seleccionar(array("turnos_laborales"), array("*"), "codigo='$codigo_turno' AND (('$hora_generada' BETWEEN '22:01' AND '24:59') OR ('$hora_generada' BETWEEN '01:00' AND '06:00'))");
                        if(SQL::filasDevueltas($consulta_rango) != 0){
                            $codigo_transaccion = $datos_contrato_sucursal->codigo_transaccion_recargo_nocturno;
                            $anexo_contable_de_transaccion_tiempo = determinarAnexo($codigo_transaccion, $documento_identidad_empleado,$codigo_sucursal);
                            $nombre = SQL::obtenerValor("transacciones_tiempo","nombre","codigo='$codigo_transaccion'"); //"Horas recargo nocturno";
                            if($codigo_transaccion=='0'){
                                $nombre = $textos["NO_EXISTEN_HORAS_RECARGO_NOCTURNO"];
                            }

                            $descripcion = "Horas Recargo Nocturna lunes a sabado : Horas de lunes a sabado dentro del turno de 10:01 PM hasta 06:00 PM";
                            if($opcion == 1){
                                $tipo = "D";
                            }else{
                                $tipo = "DD";
                            }
                            $hora_anterior = explode(":", $hora_anterior);
                            $hora_inicio_turno = ((int) $hora_anterior[0] - 1) . ":" . $hora_anterior[1];
                        }
                    }
                }

                $formato_hora_minuto = explode(":", $hora_inicio_turno);
                $hora_inicio_turno = $formato_hora_minuto[0] . ":" . $formato_hora_minuto[1];
                // echo var_dump($hora_generada." => ".$hora_anterior);
                //echo var_dump($hora_inicio_turno);
                //echo var_dump($tipo);
                $hora_generada = explode(":", $hora_generada);
                $hora_generada = $hora_generada[0] . ":" . $hora_generada[1];
                $horas_laborales [$tipo] = array(
                    $fecha_generada, //Fecha de la transaccion
                    $hora_inicio_turno, //Hora inicial
                    $hora_generada, //Hora final
                    $codigo_transaccion, //codigo de la transaccion de tiempo de acuerdo al empleado
                    $anexo_contable_de_transaccion_tiempo, //codigo del anexo dependiendo de la transaccion de tiempo
                    $nombre,
                    $cantidad,
                    0
                );
                //echo var_dump($horas_laborales);
                $fecha_entrada = getdate(strtotime($fecha_generada));
                $fecha_generada = date("Y-m-d", mktime(($fecha_entrada["hours"]), ($fecha_entrada["minutes"]), ($fecha_entrada["seconds"]), ($fecha_entrada["mon"]), ($fecha_entrada["mday"] + 1), ($fecha_entrada["year"])));
            }
        }else{
            //////Horas Extras por fuera del turno laboral//////
            for($c = 0; $c <= $rango_dias; $c++){
                $consulta_domingos_festivos = SQL::seleccionar(array("domingos_festivos"), array("*"), "fecha='$fecha_generada'");
                $datos_domingos_festivos    = SQL::filaEnObjeto($consulta_domingos_festivos);
                //echo var_dump(SQL::filasDevueltas($consulta_domingos_festivos)."=>".$fecha_generada);
                $consulta_contrato_sucursal = SQL::seleccionar(array("sucursal_contrato_empleados"), array("*"), "documento_identidad_empleado='$documento_identidad_empleado' and codigo_sucursal='$codigo_sucursal' AND fecha_ingreso_sucursal <='$fecha_generada'", "", "fecha_ingreso_sucursal DESC", 0, 1);
                $datos_contrato_sucursal = SQL::filaEnObjeto($consulta_contrato_sucursal);
                /////Si existe alguna fecha igual a la generada el dia sera un domingo o un festivo////
                if((SQL::filasDevueltas($consulta_domingos_festivos) == 1)  && ($permite_festivo)  && ($paga_dominical) && ($datos_domingos_festivos->tipo==1)) {
                    //////Determino como que tipo de hora le pago////// WHERE ((Hora Between #00:00# And #06:01#));
                    $consulta_rango = SQL::seleccionar(array("turnos_laborales"), array("*"), "codigo='$codigo_turno' AND ('$hora_generada' BETWEEN '06:00' AND '22:00')");

                    if(SQL::filasDevueltas($consulta_rango) != 0){
                        $codigo_transaccion = $datos_contrato_sucursal->codigo_transaccion_extras_dominicales;
                        $anexo_contable_de_transaccion_tiempo = determinarAnexo($codigo_transaccion, $documento_identidad_empleado,$codigo_sucursal);
                        $nombre = SQL::obtenerValor("transacciones_tiempo","nombre","codigo='$codigo_transaccion'"); //"Hora extra dominingo o festivo";
                        if($codigo_transaccion=='0'){
                            $nombre = $textos["NO_EXISTEN_EXTRAS_HORAS_DOMINICALES"];
                        }
                        $descripcion = "Horas Extras Dominicales y festivas : Horas de domingos o festivos fuera del turno de 6:00 AM hasta 10:00 PM";
                        $tipo = "5";
                    }else{
                        $consulta_rango = SQL::seleccionar(array("turnos_laborales"), array("*"), "codigo='$codigo_turno' AND (('$hora_generada' BETWEEN '22:01' AND '24:59') OR ('$hora_generada' BETWEEN '01:00' AND '06:00'))");
                        if (SQL::filasDevueltas($consulta_rango) != 0) {
                            $codigo_transaccion = $datos_contrato_sucursal->codigo_transaccion_extras_noche_dominicales;
                            $anexo_contable_de_transaccion_tiempo = determinarAnexo($codigo_transaccion, $documento_identidad_empleado,$codigo_sucursal);
                            $nombre = SQL::obtenerValor("transacciones_tiempo","nombre","codigo='$codigo_transaccion'");//"Hora extra nocturna domingo y festivo";
                            if($codigo_transaccion=='0'){
                                $nombre = $textos["NO_EXISTEN_EXTRAS_NOCTURNA_HORAS_DOMINICALES"];
                            }
                            $descripcion = "Horas Extras Nocturna en Domingos y Festivos : Horas de domingos o festivos fuera del turno de 10:01 PM hasta 00:00 PM  o de las 00:00 hasta las 6:00 AM";
                            $tipo = "6";
                        }
                    }
                }elseif((SQL::filasDevueltas($consulta_domingos_festivos) == 1)  && ($permite_festivo)  && ($paga_festivo) && ($datos_domingos_festivos->tipo==2)) {
                    //////Determino como que tipo de hora le pago////// WHERE ((Hora Between #00:00# And #06:01#));
                    $consulta_rango = SQL::seleccionar(array("turnos_laborales"), array("*"), "codigo='$codigo_turno' AND ('$hora_generada' BETWEEN '06:00' AND '22:00')");

                    if(SQL::filasDevueltas($consulta_rango) != 0){
                        $codigo_transaccion = $datos_contrato_sucursal->codigo_transaccion_extras_dominicales;
                        $anexo_contable_de_transaccion_tiempo = determinarAnexo($codigo_transaccion, $documento_identidad_empleado,$codigo_sucursal);
                        $nombre = SQL::obtenerValor("transacciones_tiempo","nombre","codigo='$codigo_transaccion'");//"Hora extra dominingo o festivo";
                        if($codigo_transaccion=='0'){
                            $nombre = $textos["NO_EXISTEN_EXTRAS_HORAS_DOMINICALES"];
                        }
                        $descripcion = "Horas Extras Dominicales y festivas : Horas de domingos o festivos fuera del turno de 6:00 AM hasta 10:00 PM";
                        $tipo = "5";
                    }else{
                        $consulta_rango = SQL::seleccionar(array("turnos_laborales"), array("*"), "codigo='$codigo_turno' AND (('$hora_generada' BETWEEN '22:01' AND '24:59') OR ('$hora_generada' BETWEEN '01:00' AND '06:00'))");
                        if(SQL::filasDevueltas($consulta_rango) != 0){
                            $codigo_transaccion = $datos_contrato_sucursal->codigo_transaccion_extras_noche_dominicales;
                            $anexo_contable_de_transaccion_tiempo = determinarAnexo($codigo_transaccion, $documento_identidad_empleado,$codigo_sucursal);
                            $nombre = SQL::obtenerValor("transacciones_tiempo","nombre","codigo='$codigo_transaccion'");//"Hora extra nocturna domingo y festivo";
                            if($codigo_transaccion=='0'){
                                $nombre = $textos["NO_EXISTEN_EXTRAS_NOCTURNA_HORAS_DOMINICALES"];
                            }
                            $descripcion = "Horas Extras Nocturna en Domingos y Festivos : Horas de domingos o festivos fuera del turno de 10:01 PM hasta 00:00 PM  o de las 00:00 hasta las 6:00 AM";
                            $tipo = "6";
                        }
                    }
                }else{
                    $consulta_rango = SQL::seleccionar(array("turnos_laborales"), array("*"), "codigo='$codigo_turno' AND ('$hora_generada' BETWEEN '06:00' AND '22:00')");
                    if(SQL::filasDevueltas($consulta_rango) != 0){
                        $codigo_transaccion = $datos_contrato_sucursal->codigo_transaccion_extras;
                        $anexo_contable_de_transaccion_tiempo = determinarAnexo($codigo_transaccion, $documento_identidad_empleado,$codigo_sucursal);
                        $nombre = SQL::obtenerValor("transacciones_tiempo","nombre","codigo='$codigo_transaccion'");//"Horas extras";
                        if($codigo_transaccion=='0'){
                            $nombre = $textos["NO_EXISTEN_EXTRAS"];
                        }
                        $descripcion = "Horas Extras de lunes a sabado : Horas fuera del turno de 6:00 AM a 6:00 PM";
                        $tipo = "7";
                    }else{
                        $consulta_rango = SQL::seleccionar(array("turnos_laborales"), array("*"), "codigo='$codigo_turno' AND (('$hora_generada' BETWEEN '22:01' AND '24:59') OR ('$hora_generada' BETWEEN '01:00' AND '06:00'))");

                        if(SQL::filasDevueltas($consulta_rango) != 0){
                            $codigo_transaccion = $datos_contrato_sucursal->codigo_transaccion_extras_nocturnas;
                            $anexo_contable_de_transaccion_tiempo = determinarAnexo($codigo_transaccion, $documento_identidad_empleado,$codigo_sucursal);
                            $nombre = SQL::obtenerValor("transacciones_tiempo","nombre","codigo='$codigo_transaccion'");//"Horas extras nocturna";
                            if($codigo_transaccion=='0'){
                                $nombre = $textos["NO_EXISTEN_EXTRAS_NOCTURNAS"];
                            }
                            $descripcion = "Horas Extras Nocturna lunes a sabado : Horas de lunes a sabado fuera del turno de 10:01 PM hasta 06:00 PM";
                            $tipo = "8";
                        }
                    }
                }
                //echo var_dump($hora_generada." => ".$fecha_generada);
                $hora_anterior = explode(":", $hora_anterior);
                $hora_anterior = $hora_anterior[0] . ":" . $hora_anterior[1];
                $hora_generada = explode(":", $hora_generada);
                $hora_generada = $hora_generada[0] . ":" . $hora_generada[1];
                $horas_laborales [] = array(
                    $fecha_generada, //Fecha de la transaccion
                    $hora_anterior, //Hora inicial
                    $hora_generada, //Hora final
                    $codigo_transaccion, //codigo de la transaccion de tiempo de acuerdo al empleado
                    $anexo_contable_de_transaccion_tiempo, //codigo del anexo dependiendo de la transaccion de tiempo
                    $nombre,
                    $cantidad,
                    1
                );
                $fecha_entrada = getdate(strtotime($fecha_generada));
                $fecha_generada = date("Y-m-d", mktime(($fecha_entrada["hours"]), ($fecha_entrada["minutes"]), ($fecha_entrada["seconds"]), ($fecha_entrada["mon"]), ($fecha_entrada["mday"] + 1), ($fecha_entrada["year"])));
            }
        }
    }
     //echo var_dump($horas_laborales);
    return $horas_laborales;
}

function determinarAnexo($transaccion_tiempo, $documento_identidad_empleado,$codigo_sucursal) {
    $anexo    = SQL::obtenerValor("sucursal_contrato_empleados", "codigo_anexo_contable", "documento_identidad_empleado='$documento_identidad_empleado' AND codigo_sucursal='$codigo_sucursal' ORDER BY fecha_ingreso_sucursal DESC LIMIT 0,1");
    $consulta = SQL::seleccionar(array("plan_contable PC,job_transacciones_contables_empleado TCE,job_transacciones_tiempo TT"), array("PC.codigo_anexo_contable"), "TT.codigo_transaccion_contable=TCE.codigo AND TCE.codigo_contable=PC.codigo_contable AND TT.codigo=$transaccion_tiempo");
    $datos    = SQL::filaEnObjeto($consulta);
    $anexoTr  = $datos->codigo_anexo_contable;
    return $anexoTr;
}

function  validarCruces($documento_identidad,$fecha_inicio,$fecha_fin,$hora_inicio,$hora_fin){
    $respuesta    = "";
    $fecha_inicio = str_replace("/","-", $fecha_inicio);
    $consulta     = SQL::seleccionar(array("reporte_incapacidades"), array("fecha_incapacidad"), "fecha_incapacidad = '".$fecha_inicio."' AND documento_identidad_empleado='$documento_identidad'");
    if(SQL::filasDevueltas($consulta)){
        $respuesta = "Error existe incapacidad en el rango de dias seleccionados para este empleado";
    }else{
        $consulta  = SQL::seleccionar(array("movimiento_tiempos_laborados"), array("hora_inicio,hora_fin"), "(((fecha_inicio BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."') OR (fecha_fin BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."')) OR (('".$fecha_inicio."' BETWEEN fecha_inicio AND fecha_fin) OR ('".$fecha_fin."' BETWEEN fecha_inicio AND fecha_fin))) AND documento_identidad_empleado = '".$documento_identidad."'");
        if(SQL::filasDevueltas($consulta)){

            $horaInicioT1   = $hora_inicio;
            $horaFinT1      = $hora_fin;

            $horaInicioT1   = explode(':',$horaInicioT1);
            $horaFinT1      = explode(':',$horaFinT1);

            $horaInicioT1   = (int)$horaInicioT1[0];
            if((int)$horaInicioT1[1]>0){
                $horaInicioT1+=1;
            }
            $horaFinT1      = (int)$horaFinT1[0];
            if((int)$horaFinT1[1]>0){
                $horaFinT1+=1;
            }

            $horaFinT11     = $horaFinT1;

            if($horaFinT1<$horaInicioT1){
                $horaFinT1+=12;
            }
            if($horaFinT1==12 && $horaFinT11!=12){
                $horaFinT1+=12;
            }

            while($datos = SQL::filaEnObjeto($consulta)){
                $horaInicioTa   = $datos->hora_inicio;
                $horaFinTa      = $datos->hora_fin;

                $horaInicioTa   = explode(':',$horaInicioTa);
                $horaFinTa      = explode(':',$horaFinTa);

                $horaFinTa      = (int)$horaFinTa[0];
                $horaInicioTa   = (int)$horaInicioTa[0];

                if((int)$horaFinTa[1]>0){
                    $horaFinTa+=1;
                }

                $horaFinTa1     = $horaFinTa;

                if($horaFinTa<$horaInicioTa){
                    $horaFinTa+=12;
                }
                if($horaFinTa==12 && $horaFinTa1!=12){
                    $horaFinTa+=12;
                }

                if(($horaInicioT1>=$horaInicioTa && $horaFinT1<=$horaFinTa)||($horaInicioT1<=$horaInicioTa && $horaFinT1>=$horaFinTa)||($horaInicioT1>=$horaInicioTa && $horaFinT1>=$horaFinTa && $horaInicioT1<$horaFinTa)||($horaInicioT1<=$horaInicioTa && $horaFinT1<=$horaFinTa && $horaFinT1>$horaInicioTa)){//t1 con ta
                    //$respuesta[0] = false;
                    $respuesta = "Error existe un cruce con las horas reportadas en la base";
                    break;
                }
            }
        }
    }
    return $respuesta;
}

?>

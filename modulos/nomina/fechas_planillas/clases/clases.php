<?php
/**
*
* Copyright (C) 2020 Jobdaily
* Raul Mauricio Oidor Lozano <ozzymauricio75@gmail.com>
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

    function determinarPeriodoFechas($codigo_planilla,$fecha_pago){//Determina la condicion de consulta para ver si una fecha de planillas tiene movimientos

        $datosFecha  = explode("-",$fecha_pago);
        $ano        = (int)$datosFecha[0];
        $mes         = (int)$datosFecha[1];
        $dia         = (int)$datosFecha[2];
        $condicion   = "ano_generacion='".$ano."' AND mes_generacion='".$mes."' AND codigo_planilla='".$codigo_planilla."' AND periodo_pago=";

        $tipo_planilla = SQL::obtenerValor("planillas","periodo_pago","codigo='".$codigo_planilla."'");

        if($tipo_planilla == '2'){
            if($dia <= 15){
                $condicion .= "'2'";
            }else{
                $condicion .= "'3'";
            }
        }else if($tipo_planilla == '4'){
            $condicion .= "'4'";
        } else {
            $condicion .= "'1'";
        }

        return $condicion;
    }

    function puedeIngresar($arregloFechas,$fecha,$planilla){//Me verifica las condiciones de si una fecha generada puede adicionarse a la tabla del formulario

        $partesFecha = split("[-/]",$fecha);
        $ano        = $partesFecha[0];
        $mes         = $partesFecha[1];
        $puedeFecha  = true;

        $consulta   = SQL::seleccionar(array("seleccion_fechas_planillas"),array("*"),"ano = '".$ano."' AND mes = '".$mes."' AND codigo_planilla = '".$planilla."'");
        $cantidad   = SQL::filasDevueltas($consulta);
        $periodo    = SQL::obtenerValor("planillas","periodo_pago","codigo = '".$planilla."'");
        $existeEnBD = SQL::existeItem("seleccion_fechas_planillas","codigo_planilla",$planilla,"fecha = '".$fecha."'");

        if($periodo == '1'){//Mensual
            if($cantidad > 0 || seRepiteFecha($arregloFechas,$fecha) || $existeEnBD || !verificarTabla($arregloFechas,$fecha,$planilla)){
                $puedeFecha = false;
            }
        } else if($periodo == '4'){//fecha unica
            if($cantidad > 0 || seRepiteFecha($arregloFechas,$fecha) || $existeEnBD || !verificarTabla($arregloFechas,$fecha,$planilla)){
                $puedeFecha = false;
            }
        }else if($periodo == '2'){//Quincenal
            if($cantidad > 1 || seRepiteFecha($arregloFechas,$fecha) || $existeEnBD || !verificarTabla($arregloFechas,$fecha,$planilla)){
                $puedeFecha = false;
            }
        }else if($periodo == '3'){//Semanal
            if(seRepiteFecha($arregloFechas,$fecha) || $existeEnBD || !verificarTabla($arregloFechas,$fecha,$planilla)){
                $puedeFecha = false;
            }
        }
        return $puedeFecha;
    }

    function seRepiteFecha($arregloFechas,$fecha){//Verificar que la fecha no se repita en el array de fechas existentes
        $cantidad = count($arregloFechas);
        $esta     = false;
        for($i=0; $i<$cantidad; $i++){
            $fechaCompara = explode(",",$arregloFechas[$i]);
            $fechaCompara = $fechaCompara[0];

            if($fecha == $fechaCompara){
                $esta = true;
                break;
            }
        }
        return $esta;
    }

    function generarFechasMes($fechaInicio,$anoFin,$planilla,$textos,$meses,$dias,$listaTabla){

        $partesFecha = explode("-",$fechaInicio);
        $ano         = (int)$partesFecha[0];
        $mes         = (int)$partesFecha[1];
        $dia         = (int)$partesFecha[2];
        $anoFin      = (int)$anoFin;

        $listaFechas = surtirListadoDeDb($planilla,$ano,$meses,$dias);
        $listaFechas = surtirListadoDeTabla($listaTabla,$listaFechas,$meses,$dias);
        $primeraVez  = true;

        for($i = $ano; $i<=$anoFin; $i++){
            for($j = 1; $j<=12; $j++){
                if($i == $ano && $primeraVez){
                    $j          = $mes;
                    $primeraVez = false;
                }
                $mesNuevaFecha = $j;
                if($j<10){
                   $mesNuevaFecha = "0".$j;
                }
                $diaNuevaFecha = $dia;
                if($diaNuevaFecha>29 && $j == 2 && (($i % 4 == 0) && (($i % 100 != 0) || ($i % 400 == 0)))){
                    $diaNuevaFecha = 29;
                }else if($diaNuevaFecha>28 && $j == 2){
                    $diaNuevaFecha = 28;
                }else if($diaNuevaFecha < 10){
                    $diaNuevaFecha = "0".$dia;
                }else if($diaNuevaFecha == 31 && ($j==4 || $j==6 || $j==9 || $j==11)){
                    $diaNuevaFecha = 30;
                }
                $fechaNueva = $i."-".$mesNuevaFecha."-".$diaNuevaFecha;
                if(puedeIngresar($listaFechas,$fechaNueva,$planilla)){
                    $date_r        = getdate(strtotime($fechaNueva));
                    $listaFechas[] = $fechaNueva.",".$meses[$date_r["mon"]].",".$dias[$date_r["weekday"]].",1";
                }
            }
        }

        return $listaFechas;
    }

    function generarFechasSemana($fechaInicio,$anoFin,$planilla,$textos,$meses,$dias,$listaTabla){
        $partesFecha = explode("-",$fechaInicio);
        $ano        = (int)$partesFecha[0];
        $mes         = (int)$partesFecha[1];
        $dia         = (int)$partesFecha[2];
        $anoFin     = (int)$anoFin;

        $listaFechas = surtirListadoDeDb($planilla,$ano,$meses,$dias);
        $listaFechas = surtirListadoDeTabla($listaTabla,$listaFechas,$meses,$dias);
        $primeraVez  = true;

        $fechaFin    = $anoFin."-12-31";
        $fechaFin    = strtotime($fechaFin);
        $fechaFin    = mktime(0, 0, 0, date("m", $fechaFin), date("d", $fechaFin), date("Y", $fechaFin));
        $fechaInicio = strtotime($fechaInicio);
        $fechaNueva  = mktime(0, 0, 0, date("m", $fechaInicio), date("d", $fechaInicio), date("Y", $fechaInicio));

        $i = 0;
        while($fechaNueva <= $fechaFin){
            $fechaNueva = date('Y-m-d', $fechaNueva);
            if(puedeIngresar($listaFechas,$fechaNueva,$planilla)){
                $date_r        = getdate(strtotime($fechaNueva));
                $listaFechas[] = $fechaNueva.",".$meses[$date_r["mon"]].",".$dias[$date_r["weekday"]].",1";
            }
            $i += 7;
            $fechaNueva  = mktime(0, 0, 0, date("m", $fechaInicio), date("d", $fechaInicio)+$i, date("Y", $fechaInicio));
        }
        return $listaFechas;
    }

    function generarFechasQuincena($fechas,$anoFin,$planilla,$textos,$meses,$dias,$listaTabla){

        $partesFecha = explode("-",$fechas);
        $primeraFecha = str_replace("/","-",trim($partesFecha[0]));
        $segundaFecha = str_replace("/","-",trim($partesFecha[1]));

        $partesFecha = explode("-",$primeraFecha);

        $ano         = (int)$partesFecha[0];
        $mes         = (int)$partesFecha[1];
        $primerDia   = (int)$partesFecha[2];
        $partesFecha = explode("-",$segundaFecha);
        $segundoDia  = (int)$partesFecha[2];
        $anoFin     = (int)$anoFin;

        $listaFechas = surtirListadoDeDb($planilla,$ano,$meses,$dias);
        $listaFechas = surtirListadoDeTabla($listaTabla,$listaFechas,$meses,$dias);
        $primeraVez  = true;

        for($i = $ano; $i<=$anoFin; $i++){
            for($j = 1; $j<=12; $j++){
                if($i == $ano && $primeraVez){
                    $j          = $mes;
                    $primeraVez = false;
                }
                $mesNuevaFecha = $j;
                if($j<10){
                   $mesNuevaFecha = "0".$j;
                }
                $primerDiaNuevaFecha  = $primerDia;
                $segundoDiaNuevaFecha = $segundoDia;

                ///////////////////////////////////////////////////////////////////////////////////////////
                if($primerDiaNuevaFecha>29 && $j == 2 && (($i % 4 == 0) && (($i % 100 != 0) || ($i % 400 == 0)))){
                    $primerDiaNuevaFecha = 29;
                }else if($primerDiaNuevaFecha>28 && $j == 2){
                    $primerDiaNuevaFecha = 28;
                }else if($primerDiaNuevaFecha < 10){
                    $primerDiaNuevaFecha = "0".$primerDia;
                }else if($primerDiaNuevaFecha == 31 && ($j==4 || $j==6 || $j==9 || $j==11)){
                    $primerDiaNuevaFecha = 30;
                }
                ///////////////////////////////////////////////////////////////////////////////////////////
                if($segundoDiaNuevaFecha>29 && $j == 2 && (($i % 4 == 0) && (($i % 100 != 0) || ($i % 400 == 0)))){
                    $segundoDiaNuevaFecha = 29;
                }else if($segundoDiaNuevaFecha>28 && $j == 2){
                    $segundoDiaNuevaFecha = 28;
                }else if($segundoDiaNuevaFecha < 10){
                    $segundoDiaNuevaFecha = "0".$segundoDia;
                }else if($segundoDiaNuevaFecha == 31 && ($j==4 || $j==6 || $j==9 || $j==11)){
                    $segundoDiaNuevaFecha = 30;
                }
                ///////////////////////////////////////////////////////////////////////////////////////////

                $fechaNueva1 = $i."-".$mesNuevaFecha."-".$primerDiaNuevaFecha;
                $fechaNueva2 = $i."-".$mesNuevaFecha."-".$segundoDiaNuevaFecha;

                if(puedeIngresar($listaFechas,$fechaNueva1,$planilla)){
                    $date_r        = getdate(strtotime($fechaNueva1));
                    $listaFechas[] = $fechaNueva1.",".$meses[$date_r["mon"]].",".$dias[$date_r["weekday"]].",1";
                }
                if(puedeIngresar($listaFechas,$fechaNueva2,$planilla)){
                    $date_r        = getdate(strtotime($fechaNueva2));
                    $listaFechas[] = $fechaNueva2.",".$meses[$date_r["mon"]].",".$dias[$date_r["weekday"]].",1";
                }
            }
        }

        return $listaFechas;
    }

    function ordenarFechas($listaFechas){//Ordenamiento de un array por medio del metodo burbuja
        $cantidad = count($listaFechas);
        if($cantidad > 1){
            for($i=0;$i<$cantidad;$i++){
                for($j=$i+1;$j<$cantidad;$j++){
                    $fechaActual = explode(",",$listaFechas[$j]);
                    $fechaActual = $fechaActual[0];
                    $fechaAtras  = explode(",",$listaFechas[$i]);
                    $fechaAtras  = $fechaAtras[0];
                    $fechaActual = strtotime($fechaActual);
                    $fechaAtras  = strtotime($fechaAtras);
                    $fechaActual = mktime(0, 0, 0, date("m", $fechaActual), date("d", $fechaActual), date("Y", $fechaActual));
                    $fechaAtras  = mktime(0, 0, 0, date("m", $fechaAtras), date("d", $fechaAtras), date("Y", $fechaAtras));
                    if($fechaActual < $fechaAtras){
                        $aux             = $listaFechas[$j];
                        $listaFechas[$j] = $listaFechas[$i];
                        $listaFechas[$i] = $aux;
                    }
                }
            }
        }
        return $listaFechas;
    }

    function surtirListadoDeDb($planilla,$ano,$meses,$dias){//Cargar todas las fechas de esa planilla existebtes en la DB
        $listaFechas = array();
        $consulta    = SQL::seleccionar(array("seleccion_fechas_planillas"),array("*"),"codigo_planilla = '".$planilla."' AND ano='$ano'");
        if(SQL::filasDevueltas($consulta)){
            while($datos = SQL::filaEnObjeto($consulta)){
                $condicion = determinarPeriodoFechas($planilla,$datos->fecha);
                $consulta2 = SQL::seleccionar(array("consulta_datos_planilla"),array("*"),$condicion);
                if(SQL::filasDevueltas($consulta2)){
                    $estado = "0";
                }else{
                    $estado = "1";
                }
                $date_r        = getdate(strtotime($datos->fecha));
                $listaFechas[] = $datos->fecha.",".$meses[$date_r["mon"]].",".$dias[$date_r["weekday"]].",".$estado;
            }
        }
        return $listaFechas;
    }

    function surtirListadoDeTabla($listaTabla,$listaFechas,$meses,$dias){//Cargar las fechas que estan actualmente en la tabla del formulario
        $cantidad = count($listaTabla);
        for($i=0;$i<$cantidad;$i++){
            $datos         = explode(",",$listaTabla[$i]);
            if(!seRepiteFecha($listaFechas,$datos[0])){
                $date_r        = getdate(strtotime($datos[0]));
                $listaFechas[] = $datos[0].",".$meses[$date_r["mon"]].",".$dias[$date_r["weekday"]].",".$datos[1];
            }
        }
        return $listaFechas;
    }

    function verificarTabla($listaFechas,$fecha,$planilla){//Controla si en el array de fechas existe para mes 1 o para quincena 2 fechas
        $cantidad = count($listaFechas);
        $puede    = true;

        $datosFecha = explode("-",$fecha);
        $ano       = (int)$datosFecha[0];
        $mes        = (int)$datosFecha[1];
        $dia        = (int)$datosFecha[2];

        $tipo_planilla = SQL::obtenerValor("planillas","periodo_pago","codigo='".$planilla."'");
        if($tipo_planilla == '1'){//Mensual
            for($i=0;$i<$cantidad;$i++){
                $datosFechas = explode(",",$listaFechas[$i]);
                $datosFecha  = explode("-",$datosFechas[0]);
                $ano2       = (int)$datosFecha[0];
                $mes2        = (int)$datosFecha[1];
                if($ano == $ano2 && $mes == $mes2){
                    $puede = false;
                    break;
                }
            }
        }else if($tipo_planilla == '2'){//Quincenal par la primera y segunda quincena son diferentes analizar
            for($i=0;$i<$cantidad;$i++){
                $datosFechas = explode(",",$listaFechas[$i]);
                $datosFecha  = explode("-",$datosFechas[0]);
                $ano2       = (int)$datosFecha[0];
                $mes2        = (int)$datosFecha[1];
                $dia2        = (int)$datosFecha[2];
                if($ano == $ano2 && $mes == $mes2 && $dia <= 15 && $dia2 <= 15){
                    $puede = false;
                    break;
                }else if($ano == $ano2 && $mes == $mes2 && $dia > 15 && $dia2 > 15){
                    $puede = false;
                    break;
                }
            }
        }else if($tipo_planilla == '3'){//Semanal
            $puede = true;
        }
        return $puede;
    }

    function cargarFechasModificar($codigo_planilla,$meses,$dias,$ano){
        $listaFechasPago = array();

        $fechas   = surtirListadoDeDb($codigo_planilla,$ano,$meses,$dias);
        $cantidad = count($fechas);

        for($i=0;$i<$cantidad;$i++){
            $datos = explode(",",$fechas[$i]);

            $ocultos  = HTML::campoOculto("fecha_tabla[]",$datos[0],array("class" => "fecha_tabla"));
            $ocultos .= HTML::campoOculto("estado[]",$datos[3],array("class" => "estado"));
            if($datos[3] == '1'){
                $ocultos    .=  HTML::boton("botonRemover","","removerItem(this);", "eliminar", array("id" => "removedor"));
                $calendario  = HTML::campoTextoCorto("otro","", 10, 10,$datos[0], array("class" => "selectorFechaTabla", "onClick" => "pegarFecha(this)", "onChange" => "validarFechas(this)"));
                $calendario .= HTML::imagen("imagenes/calendario.png",array("class" => "ui-datepicker-trigger"));
            }else{
                $calendario = HTML::campoTextoCorto("otro","", 10, 10,$datos[0], array("disabled" => "disabled"));
            }

            $listaFechasPago[] = array(
                $i,
                $ocultos,
                $datos[1],
                $datos[2],
                $calendario
            );
        }
        return $listaFechasPago;
    }
?>

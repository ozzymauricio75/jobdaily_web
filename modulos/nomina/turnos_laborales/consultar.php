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

/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {

    /*** Verificar que se haya enviado el ID del elemento a consultar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_CONSULTAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {
        /*** Obtener los datos de la tabla de terceros ***/
        $vistaConsulta = "turnos_laborales";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo = '$url_id'");
        $datos         = SQL::filaEnObjeto($consulta);
        $error         = "";
        $titulo        = $componente->nombre;

        if($datos->permite_festivos == 1){
            $permite_festivos = $textos["SI"];
        }
        else{
            $permite_festivos = $textos["NO"];
        }
        
        if($datos->paga_dominical == 1){
            $dominical = $textos["SI"];
        }
        else{
            $dominical = $textos["NO"];
        }
        
        if($datos->paga_festivo == 1){
            $festivos = $textos["SI"];
        }
        else{
            $festivos = $textos["NO"];
        }

        $dias   = array($textos["LUNES"],$textos["MARTES"],$textos["MIERCOLES"],$textos["JUEVES"],$textos["VIERNES"],$textos["SABADO"],$textos["DOMINGO"]);
        $dias_m = array($textos["LUNES_M"],$textos["MARTES_M"],$textos["MIERCOLES_M"],$textos["JUEVES_M"],$textos["VIERNES_M"],$textos["SABADO_M"],$textos["DOMINGO_M"]);

        $lista_items = array();
        for($i=0;$i<7;$i++){
            
            $tipo        = "tipo_turno_".$dias_m[$i];
            $horaInicio1 = "hora_inicial_turno1_".$dias_m[$i];
            $horaFinal1  = "hora_final_turno1_".$dias_m[$i];
            $horaInicio2 = "hora_inicial_turno2_".$dias_m[$i];
            $horaFinal2  = "hora_final_turno2_".$dias_m[$i];
            $descanso    = "dia_descanso_".$dias_m[$i];

            $diaDescanso = $datos->$descanso == '1' ? $textos["SI"] : $textos["NO"];
            $tipoTurno   = $datos->$tipo == '1' ? $textos["SI"] : $textos["NO"];

            if($diaDescanso == $textos["NO"]){            
                $lista_items[]=array(
                    $i,
                    $dias[$i],
                    $tipoTurno,
                    substr($datos->$horaInicio1,0,5).' - '.substr($datos->$horaFinal1,0,5),
                    $tipoTurno == $textos["NO"] ? substr($datos->$horaInicio2,0,5).' - '.substr($datos->$horaFinal2,0,5) : "--",
                    $diaDescanso
                );
            }else{
                $lista_items[]=array(
                    $i,
                    $dias[$i],
                    "--",
                    "--",
                    "--",
                    $diaDescanso
                );
            }
        }
        
        /*** Definición de pestaña personal ***/
         $formularios["PESTANA_BASICA"] = array(
            array(
                HTML::mostrarDato("codigo", $textos["CODIGO"], $datos->codigo),
                HTML::mostrarDato("descripcion", $textos["DESCRIPCION"], $datos->descripcion)
            ),
            array(
                HTML::mostrarDato("festivos", $textos["PERMITE_FESTIVOS"], $permite_festivos),
                HTML::mostrarDato("paga_domingos", $textos["PAGA_DOMINICAL"], $dominical),
                HTML::mostrarDato("paga_festivos", $textos["PAGA_FESTIVOS"], $festivos)
            ),
            array(
                HTML::generarTabla(
                    array("id","DIA_SEMANA","TRABAJA_SEGUIDO","PRIMER_TURNO","SEGUNDO_TURNO","DIA_DESCANSO"),
                    $lista_items,array("I","C","C","C","C"),"listaTurnos",false
                )
            )
        );

        $contenido = HTML::generarPestanas($formularios);
    }

    /*** Enviar datos para la generación del formulario al script que originï¿½ la peticiï¿½n ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);
}
?>

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

// Generar el formulario para la captura de datos
if (!empty($url_generar)) {

     // Verificar que se haya enviado el ID del elemento a modificar
    if (empty($url_id)) {
        $error     = $textos["ERROR_MODIFICAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {
        $vistaConsulta = "turnos_laborales";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo = '$url_id'");
        $datos         = SQL::filaEnObjeto($consulta);
        $error  = "";
        $titulo = $componente->nombre;

        if($datos->permite_festivos == 1){
            $permite_festivos_no = false;
            $permite_festivos_si = true;
            $clase = "";
        }
        else{
            $permite_festivos_no = true;
            $permite_festivos_si = false;
            $clase = "oculto";
        }

        if($datos->paga_dominical == 1){
            $dominical_no = false;
            $dominical_si = true;
        }
        else{
            $dominical_no = true;
            $dominical_si = false;
        }

        if($datos->paga_festivo == 1){
            $festivos_no = false;
            $festivos_si = true;
        }
        else{
            $festivos_no = true;
            $festivos_si = false;
        }

        // Definicion de pestana Basica
        $formularios["PESTANA_BASICA"] = array(
            array(
                HTML::campoTextoCorto("*codigo", $textos["CODIGO"], 6, 4, $datos->codigo, array("title" => $textos["AYUDA_TURNO_LABORAL"], "onblur" => "validarItem(this);","onKeyPress" => "return campoEntero(event)"))
                .HTML::campoOculto("llave_turno", $datos->codigo),
                HTML::campoTextoCorto("*descripcion", $textos["DESCRIPCION"], 30, 255, $datos->descripcion, array("title" => $textos["AYUDA_TURNO_LABORAL"], "onblur" => "validarItem(this);"))
            ),
            array(
                HTML::mostrarDato("permite_festivos", $textos["PERMITE_FESTIVOS"], ""),
                HTML::marcaSeleccion("festivos", $textos["NO"], 0, $permite_festivos_no, array("id" => "festivo_no", "onChange" => "activarPagos(1)")),
                HTML::marcaSeleccion("festivos", $textos["SI"], 1, $permite_festivos_si, array("id" => "festivo_si", "onChange" => "activarPagos(2)"))
            ),
            array(
                HTML::contenedor(HTML::mostrarDato("paga_dominical", $textos["PAGA_DOMINICAL"]."&nbsp;", ""),array("id" => "pagos_dominicales", "class" => $clase)),
                HTML::marcaSeleccion("paga_dominical", $textos["NO"], 0, $dominical_no, array("id" => "paga_dominical_no", "class" => $clase)),
                HTML::marcaSeleccion("paga_dominical", $textos["SI"], 1, $dominical_si, array("id" => "paga_dominical_si", "class" => $clase))
            ),
            array(
                HTML::contenedor(HTML::mostrarDato("paga_festivos", $textos["PAGA_FESTIVOS"]."&nbsp;&nbsp;&nbsp;&nbsp;", ""),array("id" => "pagos_festivos", "class" => $clase)),
                HTML::marcaSeleccion("paga_festivo", $textos["NO"], 0, $festivos_no, array("id" => "paga_festivo_no", "class" => $clase)),
                HTML::marcaSeleccion("paga_festivo", $textos["SI"], 1, $festivos_si, array("id" => "paga_festivo_si", "class" => $clase))
            )
        );

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

            $continuoTextoSi = $tipoTurno == $textos["SI"] ? 'checked' : '';
            $continuoTextoNo = $tipoTurno == $textos["NO"] ? 'checked' : '';

            $col1 = HTML::campoOculto("diaSemana[".$dias[$i]."]", $dias[$i], array("class"=>"diaSemana")).$dias[$i];

            $col4_I_disabled = '<input type="text" class="horaTabla" name="horaInicio2['.$dias[$i].']" value="" size="5" maxlength="5" disabled="" />';
            $col4_F_disabled = '<input type="text" class="horaTabla" name="horaFinal2['.$dias[$i].']"  value="" size="5" maxlength="5" disabled="" />';

            if($diaDescanso == $textos["NO"]){

                $col2 = '<label class="dato"><input type="radio" class="turnoContinuoSi" name="turnoContinuo['.$dias[$i].']" value="1" onChange="validarTurnos(this,1);" '.$continuoTextoSi.'>Si</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.
                        '<label class="dato"><input type="radio" class="turnoContinuoNo" name="turnoContinuo['.$dias[$i].']" value="0" onChange="validarTurnos(this,2);" '.$continuoTextoNo.'>No</label>';

                $col3_I = '<input type="text" class="horaTabla" name="horaInicio1['.$dias[$i].']" value="'.substr($datos->$horaInicio1,0,5).'" size="5" maxlength="5"/>';
                $col3_F = '<input type="text" class="horaTabla" name="horaFinal1['.$dias[$i].']"  value="'.substr($datos->$horaFinal1,0,5).'" size="5" maxlength="5"/>';

                $col4_I_habilitado = '<input type="text" class="horaTabla" name="horaInicio2['.$dias[$i].']" value="'.substr($datos->$horaInicio2,0,5).'" size="5" maxlength="5"/>';
                $col4_F_habilitado = '<input type="text" class="horaTabla" name="horaFinal2['.$dias[$i].']"  value="'.substr($datos->$horaFinal2,0,5).'" size="5" maxlength="5"/>';

                $col5 = '<input type="checkbox" name="diaDescanso['.$dias[$i].']" onChange="habilitarHoras(this);" value="'.$dias[$i].'" title="'.$textos["AYUDA_DIA_DESCANSO"].'"/>';

                $lista_items[]=array(
                    $dias[$i],
                    $col1,
                    $col2,
                    $col3_I.$col3_F,
                    $tipoTurno == $textos["NO"] ? $col4_I_habilitado.$col4_F_habilitado : $col4_I_disabled.$col4_F_disabled,
                    $col5
                );
            }else{
                $col2   = '<label class="dato"><input type="radio" class="turnoContinuoSi" name="turnoContinuo['.$dias[$i].']" value="1" onChange="validarTurnos(this,1);" disabled="" >Si</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.
                          '<label class="dato"><input type="radio" class="turnoContinuoNo" name="turnoContinuo['.$dias[$i].']" value="0" onChange="validarTurnos(this,2);" checked="" disabled="" >No</label>';

                $col3_I = '<input type="text" class="horaTabla" name="horaInicio1['.$dias[$i].']" value="" size="5" maxlength="5" disabled="" />';
                $col3_F = '<input type="text" class="horaTabla" name="horaFinal1['.$dias[$i].']"  value="" size="5" maxlength="5" disabled="" />';

                $lista_items[]=array(
                    $dias[$i],
                    $col1,
                    $col2,
                    $col3_I.$col3_F,
                    $col4_I_disabled.$col4_F_disabled,
                    '<input type="checkbox" name="diaDescanso['.$dias[$i].']" onChange="habilitarHoras(this);" value="'.$dias[$i].'" checked="" title="'.$textos["AYUDA_DIA_DESCANSO"].'"/>'
                );
            }
        }

        $funciones["PESTANA_TURNOS"]   = "eventoPestana()";
        $formularios["PESTANA_TURNOS"] = array(
            array(
                HTML::mostrarDato("trabaja_turno", $textos["TRABAJA_SEGUIDO"], ""),
                HTML::mostrarDato("primer_turno",$textos["PRIMER_TURNO"], ""),
                HTML::contenedor(HTML::mostrarDato("segundo_turno","&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$textos["SEGUNDO_TURNO"], ""),array("id" => "turno2", "class" => "oculto"))
            ),
            array(
                HTML::marcaSeleccion("turno_laboral", $textos["SI"], 1, true, array("id" => "seguido_si", "onChange" => "activarHoras(2)", "disabled" => "disabled")),
                HTML::marcaSeleccion("turno_laboral", $textos["NO"], 0, false, array("id" => "seguido_no", "onChange" => "activarHoras(1)", "disabled" => "disabled")),
                HTML::campoTextoCorto("*hora_inicia1", $textos["HORA_INICIAL"], 5, 5, "__:__", array("title" => $textos["AYUDA_HORA_INICIAL"], "class" => "hora", "disabled" => "disabled")),
                HTML::campoTextoCorto("*hora_finaliza1", $textos["HORA_FINAL"], 5, 5, "__:__", array("title" => $textos["AYUDA_HORA_FINAL"], "class" => "hora", "disabled" => "disabled")),
                HTML::campoTextoCorto("*hora_inicia2", $textos["HORA_INICIAL"], 5, 5, "__:__", array("title" => $textos["AYUDA_HORA_INICIAL2"], "class" => "hora oculto")),
                HTML::campoTextoCorto("*hora_finaliza2", $textos["HORA_FINAL"], 5, 5, "__:__", array("title" => $textos["AYUDA_HORA_FINAL2"], "class" => "hora oculto"))
            ),
            array(
                HTML::boton("botonAgregar", $textos["GENERAR"], "adicionarDia();", "adicionar"),
                HTML::boton("botonEliminar",$textos["ELIMINAR_TODOS"],"removerTable();", "eliminar")
            ),
            array(
                HTML::generarTabla(
                    array("id","DIA_SEMANA","TRABAJA_SEGUIDO","PRIMER_TURNO","SEGUNDO_TURNO","DIA_DESCANSO"),
                    $lista_items,array("I","C","C","C","C"),"listaTurnos",false
                )
            ),
            array(//Campos de control
                HTML::campoOculto("lunes",$textos["LUNES"]),
                HTML::campoOculto("martes",$textos["MARTES"]),
                HTML::campoOculto("miercoles",$textos["MIERCOLES"]),
                HTML::campoOculto("jueves",$textos["JUEVES"]),
                HTML::campoOculto("viernes",$textos["VIERNES"]),
                HTML::campoOculto("sabado",$textos["SABADO"]),
                HTML::campoOculto("domingo",$textos["DOMINGO"]),

                HTML::campoOculto("hora_inicia1_vacio",$textos["ERROR_DATOS_INICIA1"]),
                HTML::campoOculto("hora_finaliza1_vacio",$textos["ERROR_DATOS_FINALIZA1"]),
                HTML::campoOculto("horas_turno1_iguales",$textos["ERROR_HORA_INICIA1_FINAL1"]),
                HTML::campoOculto("hora_inicia1_mal_formato",$textos["ERROR_HORA_INICIA1"]),
                HTML::campoOculto("hora_finaliza1_mal_formato",$textos["ERROR_HORA_FINALIZA1"]),
                HTML::campoOculto("hora_inicia2_vacio",$textos["ERROR_DATOS_INICIA2"]),
                HTML::campoOculto("hora_finaliza2_vacio",$textos["ERROR_DATOS_FINALIZA2"]),
                HTML::campoOculto("horas_turno2_iguales",$textos["ERROR_HORA_INICIA2_FINAL2"]),
                HTML::campoOculto("turnos_cruzados",$textos["ERROR_HORA_INICIA2_FINAL1"]),
                HTML::campoOculto("hora_inicia2_mal_formato",$textos["ERROR_HORA_INICIA2"]),
                HTML::campoOculto("hora_finaliza2_mal_formato",$textos["ERROR_HORA_FINALIZA2"]),
                HTML::campoOculto("aplica_mascara",false),
                HTML::campoOculto("ayuda_dia_descanso",$textos["AYUDA_DIA_DESCANSO"])
            )
        );

            // Definicion de botones
            $botones = array(
                HTML::boton("botonAceptar", $textos["ACEPTAR"], "validarTablaM();", "aceptar")
            );

            $contenido = HTML::generarPestanas($formularios, $botones, "", $funciones);

    }

    // Enviar datos para la generacion del formulario al script que origino la peticion
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

// Validar los datos provenientes del formulario
} elseif (!empty($url_validar)) {

    // Validar descripcion
    if ($url_item=="descripcion") {
        $existe = SQL::existeItem("turnos_laborales", "descripcion", $url_valor,"codigo != '".$url_id."' AND codigo != '0'");

        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_DESCRICION"]);
        }
    }
    // Validar codigo
    elseif ($url_item=="codigo") {
        $existeCodigo = SQL::existeItem("turnos_laborales", "codigo", $url_valor,"codigo != '".$url_id."' AND codigo != '0'");

        if ($existeCodigo) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_CODIGO"]);
        }
    }

// Modificar el elemento seleccionado
} elseif (!empty($forma_procesar)) {
    // Asumir por defecto que no hubo error
    $error   = false;
    $mensaje = $textos["ITEM_MODIFICADO"];

    $codigo = (int)$forma_codigo;

    if(empty($forma_codigo) || $codigo == 0){
        $error = true;
        $mensaje = $textos["ERROR_CODIGO_VACIO"];
    } elseif (SQL::existeItem("turnos_laborales", "codigo", $forma_codigo,"codigo != '0' AND codigo != '".$forma_id."'")){
        $error   = true;
        $mensaje = $textos["ERROR_EXISTE_CODIGO"];
    } elseif (SQL::existeItem("turnos_laborales", "descripcion", $forma_descripcion,"codigo != '0' AND codigo != '".$forma_id."'")){
        $error   = true;
        $mensaje = $textos["ERROR_EXISTE_DESCRICION"];
    } elseif (empty($forma_descripcion)){
        $error   = true;
        $mensaje = $textos["ERROR_DESCRIPCION"];

    } elseif(!isset($forma_horaInicio1)){
        $error   = true;
        $mensaje = $textos["ERROR_DIAS_NO_GENERADOS"];
    } else {

        foreach($forma_diaSemana AS $diaSemana){
            if(isset($forma_diaDescanso[$diaSemana])){
                $forma_horaInicio1[$diaSemana]   = "00:00";
                $forma_horaFinal1[$diaSemana]    = "00:00";
                $forma_horaInicio2[$diaSemana]   = "00:00";
                $forma_horaFinal2[$diaSemana]    = "00:00";
                $forma_turnoContinuo[$diaSemana] = '0';
                $forma_diaDescanso[$diaSemana]   = '1';
            }else if($forma_turnoContinuo[$diaSemana]==1){
                $forma_horaInicio2[$diaSemana] = "00:00";
                $forma_horaFinal2[$diaSemana]  = "00:00";
                $forma_diaDescanso[$diaSemana] = '0';
            }else{
                $forma_diaDescanso[$diaSemana] = '0';
            }
        }

        if(!isset($forma_paga_dominical)){
            $forma_paga_dominical = '0';
        }

        if(!isset($forma_paga_festivo)){
            $forma_paga_festivo = '0';
        }

        $datos = array (
            "codigo"                        => $forma_codigo,
            "descripcion"                   => $forma_descripcion,
            "permite_festivos"              => $forma_festivos,
            "paga_dominical"                => $forma_paga_dominical,
            "paga_festivo"                  => $forma_paga_festivo,
            //////////////////// LUNES ///////////////////////////////
            "tipo_turno_lunes"              => $forma_turnoContinuo[$textos["LUNES"]],
            "dia_descanso_lunes"            => $forma_diaDescanso[$textos["LUNES"]],
            "hora_inicial_turno1_lunes"     => $forma_horaInicio1[$textos["LUNES"]],
            "hora_final_turno1_lunes"       => $forma_horaFinal1[$textos["LUNES"]],
            "hora_inicial_turno2_lunes"     => $forma_horaInicio2[$textos["LUNES"]],
            "hora_final_turno2_lunes"       => $forma_horaFinal2[$textos["LUNES"]],
            //////////////////// MARTES //////////////////////////////
            "tipo_turno_martes"             => $forma_turnoContinuo[$textos["MARTES"]],
            "dia_descanso_martes"           => $forma_diaDescanso[$textos["MARTES"]],
            "hora_inicial_turno1_martes"    => $forma_horaInicio1[$textos["MARTES"]],
            "hora_final_turno1_martes"      => $forma_horaFinal1[$textos["MARTES"]],
            "hora_inicial_turno2_martes"    => $forma_horaInicio2[$textos["MARTES"]],
            "hora_final_turno2_martes"      => $forma_horaFinal2[$textos["MARTES"]],
            //////////////////// MIERCOLES ///////////////////////////
            "tipo_turno_miercoles"          => $forma_turnoContinuo[$textos["MIERCOLES"]],
            "dia_descanso_miercoles"        => $forma_diaDescanso[$textos["MIERCOLES"]],
            "hora_inicial_turno1_miercoles" => $forma_horaInicio1[$textos["MIERCOLES"]],
            "hora_final_turno1_miercoles"   => $forma_horaFinal1[$textos["MIERCOLES"]],
            "hora_inicial_turno2_miercoles" => $forma_horaInicio2[$textos["MIERCOLES"]],
            "hora_final_turno2_miercoles"   => $forma_horaFinal2[$textos["MIERCOLES"]],
            //////////////////// JUEVES //////////////////////////////
            "tipo_turno_jueves"             => $forma_turnoContinuo[$textos["JUEVES"]],
            "dia_descanso_jueves"           => $forma_diaDescanso[$textos["JUEVES"]],
            "hora_inicial_turno1_jueves"    => $forma_horaInicio1[$textos["JUEVES"]],
            "hora_final_turno1_jueves"      => $forma_horaFinal1[$textos["JUEVES"]],
            "hora_inicial_turno2_jueves"    => $forma_horaInicio2[$textos["JUEVES"]],
            "hora_final_turno2_jueves"      => $forma_horaFinal2[$textos["JUEVES"]],
            //////////////////// VIERNES /////////////////////////////
            "tipo_turno_viernes"            => $forma_turnoContinuo[$textos["VIERNES"]],
            "dia_descanso_viernes"          => $forma_diaDescanso[$textos["VIERNES"]],
            "hora_inicial_turno1_viernes"   => $forma_horaInicio1[$textos["VIERNES"]],
            "hora_final_turno1_viernes"     => $forma_horaFinal1[$textos["VIERNES"]],
            "hora_inicial_turno2_viernes"   => $forma_horaInicio2[$textos["VIERNES"]],
            "hora_final_turno2_viernes"     => $forma_horaFinal2[$textos["VIERNES"]],
            //////////////////// SABADO //////////////////////////////
            "tipo_turno_sabado"             => $forma_turnoContinuo[$textos["SABADO"]],
            "dia_descanso_sabado"           => $forma_diaDescanso[$textos["SABADO"]],
            "hora_inicial_turno1_sabado"    => $forma_horaInicio1[$textos["SABADO"]],
            "hora_final_turno1_sabado"      => $forma_horaFinal1[$textos["SABADO"]],
            "hora_inicial_turno2_sabado"    => $forma_horaInicio2[$textos["SABADO"]],
            "hora_final_turno2_sabado"      => $forma_horaFinal2[$textos["SABADO"]],
            //////////////////// DOMINGO /////////////////////////////
            "tipo_turno_domingo"            => $forma_turnoContinuo[$textos["DOMINGO"]],
            "dia_descanso_domingo"          => $forma_diaDescanso[$textos["DOMINGO"]],
            "hora_inicial_turno1_domingo"   => $forma_horaInicio1[$textos["DOMINGO"]],
            "hora_final_turno1_domingo"     => $forma_horaFinal1[$textos["DOMINGO"]],
            "hora_inicial_turno2_domingo"   => $forma_horaInicio2[$textos["DOMINGO"]],
            "hora_final_turno2_domingo"     => $forma_horaFinal2[$textos["DOMINGO"]]
        );

        $modificar = SQL::modificar("turnos_laborales", $datos, "codigo = '$forma_id'");

        // Error de modificacion
        if (!$modificar) {
            $error   = true;
            $mensaje = $textos["ERROR_MODIFICAR_ITEM"];
            //$mensaje = mysql_error();
        }
    }

    // Enviar datos con la respuesta del proceso al script que origino la peticion
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

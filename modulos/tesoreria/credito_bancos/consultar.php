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
$tabla                      = "usuarios";
$columnas                   = SQL::obtenerColumnas($tabla);
$consulta                   = SQL::seleccionar(array($tabla), $columnas, "usuario = '$sesion_usuario'");
$datos                      = SQL::filaEnObjeto($consulta);
$sesion_id_usuario_ingreso  = $datos->codigo;

/*** Mostrar los datos de la cuenta ***/
if (!empty($url_insertarCuota)) {
    $codigo_credito = $url_id;
    $numero_cuota   = $url_cuota;
    $abono_capital  = $url_abono_capital;
    $abono_capital  = str_replace(".", "", $abono_capital);

    $datos = array(
        "abono_capital" => $abono_capital      
    );
    
    $modificar = SQL::modificar("cuotas_creditos_bancos", $datos, "numero_cuota = '$numero_cuota' AND codigo_credito = '$codigo_credito'");

    exit;

/*** Generar el formulario para la captura de datos ***/
} elseif (!empty($url_generar)) {

    /*** Verificar que se haya enviado el ID del elemento a consultar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_CONSULTAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {
        $vistaConsulta  = "creditos_bancos";
        $columnas       = SQL::obtenerColumnas($vistaConsulta);
        $consulta       = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo = '$url_id'");
        $datos          = SQL::filaEnObjeto($consulta);
        $estado_credito = $datos->estado_credito;
        $tipo_credito   = $datos->tipo_credito;
        $fecha_pago     = $datos->fecha_pago_cuota;
        $tasa_DTF       = $datos->tasa_dtf;
        
        $error  = "";
        $titulo = $componente->nombre;

        /*** Obtener valores ***/
        $vistaConsultaCuotas  = "cuotas_creditos_bancos";
        $columnas_cuota       = SQL::obtenerColumnas($vistaConsultaCuotas);
        $consulta_cuota       = SQL::seleccionar(array($vistaConsultaCuotas), $columnas_cuota, "codigo_credito = '$url_id'");
        $datos_cuota_credito  = SQL::filaEnObjeto($consulta_cuota);
        $estado_cuota_credito = $datos_cuota_credito->estado_cuota;
 
        $proyecto          = SQL::obtenerValor("proyectos","nombre","codigo=$datos->codigo_proyecto");
        $banco             = SQL::obtenerValor("bancos","descripcion","codigo=$datos->codigo_banco");
        $interes           = SQL::obtenerValor("cuotas_creditos_bancos","SUM(interes)","codigo_credito='$url_id'");
        $interes_pagado    = SQL::obtenerValor("cuotas_creditos_bancos","SUM(interes_pagado)","codigo_credito='$url_id'");
        $interes_por_pagar = $interes-$interes_pagado;
        $capital           = SQL::obtenerValor("cuotas_creditos_bancos","SUM(abono_capital)","codigo_credito='$url_id'");
        $capital_pagado    = SQL::obtenerValor("cuotas_creditos_bancos","SUM(abono_capital_pagado)","codigo_credito='$url_id'");
        $capital_por_pagar = $capital-$capital_pagado;
        $por_pagar         = $interes_por_pagar+$capital_por_pagar;

        /* Obtener cuotas relacionadas con el credito */
        $consulta_cuotas = SQL::seleccionar(array("cuotas_creditos_bancos"), array("*"), "codigo_credito = '$url_id'");
        if (SQL::filasDevueltas($consulta_cuotas)) {

            $estados_cuota = array(
                "0" => $textos["ESTADO_0"],
                "1" => $textos["ESTADO_1"],
                "2" => $textos["ESTADO_2"]
            );
            
            $cuota = 1;

            while ($datos_cuotas = SQL::filaEnObjeto($consulta_cuotas)) {
    
                $id_cuota             = $datos_cuotas->codigo;
                $numero_cuota         = $datos_cuotas->numero_cuota;
                $fecha_cuota          = $datos_cuotas->fecha_cuota;
                $interes              = $datos_cuotas->interes;
                $interes_pagado       = $datos_cuotas->interes_pagado;
                $abono_capital        = $datos_cuotas->abono_capital;
                $abono_capital_pagado = $datos_cuotas->abono_capital_pagado;
                $saldo_capital_pagado = $datos_cuotas->saldo_capital_pagado;
                $estado_cuota         = $estados_cuota[$datos_cuotas->estado_cuota];

                $item_cuota[]  = array( $id_cuota,
                                        $numero_cuota,
                                        $fecha_cuota,
                                        HTML::campoTextoCorto("cuota[".$cuota."]", "", 15, 15, $abono_capital, array("title"=>$textos["AYUDA_VALOR_CUOTA"], "onkeyup"=>"formatoMiles(this)", "OnChange"=>"insertarCuota(".$cuota.",".$url_id.")"))
                                        .HTML::campoOculto("listaItems",$item_cuota),
                                        $estado_cuota
                );
                $cuota++;
                /*$item_cuota[]  = array( $id_cuota,
                                        $numero_cuota,
                                        $interes,
                                        $interes_pagado,
                                        $abono_capital,
                                        $abono_capital_pagado,
                                        $saldo_capital_pagado,
                                        $estado_cuota
                );*/
            }
        }

        if($tipo_credito=="1"){
            $tipo_credito = "Credito";
        } else{
            $tipo_credito = "Credipago";
        }

        /*** Definici�n de pesta�as general ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::mostrarDato("codigo", $textos["CODIGO"], $datos->codigo),
                HTML::mostrarDato("proyecto", $textos["PROYECTO"], $proyecto),
                HTML::mostrarDato("tipo_credito", $textos["TIPO_CREDITO"], $tipo_credito),
                HTML::mostrarDato("dia_pago", $textos["FECHA_PAGO_CUOTA"], $fecha_pago),
                HTML::mostrarDato("tasa_dtf", $textos["TASA_DTF"], $tasa_DTF),
            ),
            array(
                HTML::agrupador(
                    array(
                        array(   
                            HTML::mostrarDato("banco", $textos["BANCO"], $banco),
                            HTML::mostrarDato("numero_credito", $textos["NUMERO_CREDITO"], $datos->numero_credito),
                            HTML::mostrarDato("valor_credito", $textos["VALOR_CREDITO"], "$".number_format($datos->valor_credito,0)),
                            HTML::mostrarDato("estado_cuota", $textos["ESTADO"], $textos["ESTADO_".$estado_credito])
                        )
                    ),
                    $textos["DATOS_BANCO"]
                )
            ),
            array(
                HTML::agrupador(
                    array(
                        array(
                            HTML::mostrarDato("tasa_mensual", $textos["TASA_MENSUAL"], number_format($datos->tasa_mensual,2)),
                            HTML::mostrarDato("numero_cuotas", $textos["NUMERO_CUOTAS"], $datos->periodos),
                            HTML::mostrarDato("valor_cuota", $textos["VALOR_CUOTA"], "$".number_format($datos->valor_cuota,0)),
                            HTML::mostrarDato("fecha_credito", $textos["FECHA_CREDITO"], $datos->fecha_credito)
                        )
                    ),
                    $textos["DATOS_CREDITO"]
                )
            ),
            array(
                HTML::agrupador(
                    array(
                        array(   
                            HTML::mostrarDato("capital", $textos["CAPITAL_POR_PAGAR"], "$".number_format($capital_por_pagar,0)),
                            HTML::mostrarDato("intereses", $textos["INTERES_POR_PAGAR"], "$".number_format($interes_por_pagar,0)),
                            HTML::mostrarDato("valor_por pagar", $textos["POR_PAGAR"], "$".number_format($por_pagar,0))
                        )
                    ),
                    $textos["SALDOS_APROXIMADOS"]
                )
            ),
            array(
                HTML::mostrarDato("observaciones", $textos["OBSERVACIONES"], $datos->observaciones)    
            )
        );
        /*** Definici�n de pesta�a de cuentas bancarias relacionadas ***/
        if (isset($item_cuota)) {

            $formularios["PESTANA_CUOTAS"] = array(
                array(
                    HTML::generarTabla(
                        //array("id","NRO_CUOTA","INTERES","INTERES_PAGADO","ABONO_CAPITAL","ABONO_CAPITAL_PAGADO","SALDO_CAPITAL_PAGADO","ESTADO_CUOTA"),
                        array("id","NRO_CUOTA","FECHA_PAGO","ABONO_CAPITAL","ESTADO_CUOTA"),
                        $item_cuota,
                        array("I","C","D","D"),
                        "lista_items_cuotas",
                        false)
                )
            );
        } 

        /*** Definici�n de botones ***/
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "modificarItem('$url_id');", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones);
    }

    /*** Enviar datos para la generaci�n del formulario al script que origin� la petici�n ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

/*** Validar los datos provenientes del formulario ***/
} elseif (!empty($forma_procesar)) {
    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_MODIFICADO"];

    /*** Enviar datos con la respuesta del proceso al script que origin� la petici�n ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}

?>

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
* modificarlo  bajo los t�rminos de la Licencia P�blica General GNU
* publicada por la Fundaci�n para el Software Libre, ya sea la versi�n 3
* de la Licencia, o (a su elecci�n) cualquier versi�n posterior.
*
* Este programa se distribuye con la esperanza de que sea �til, pero
* SIN GARANT�A ALGUNA; ni siquiera la garant�a impl�cita MERCANTIL o
* de APTITUD PARA UN PROP�SITO DETERMINADO. Consulte los detalles de
* la Licencia P�blica General GNU para obtener una informaci�n m�s
* detallada.
*
* Deber�a haber recibido una copia de la Licencia P�blica General GNU
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
        $vistaConsulta  = "creditos_bancos";
        $columnas       = SQL::obtenerColumnas($vistaConsulta);
        $consulta       = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo = '$url_id'");
        $datos          = SQL::filaEnObjeto($consulta);
        $estado_credito = $datos->estado_credito;
        
        $error  = "";
        $titulo = $componente->nombre;

        /*** Obtener valores ***/
        $vistaConsultaCuotas  = "cuotas_creditos_bancos";
        $columnas_cuota       = SQL::obtenerColumnas($vistaConsultaCuotas);
        $consulta_cuota       = SQL::seleccionar(array($vistaConsultaCuotas), $columnas_cuota, "codigo_credito = '$url_id'");
        $datos_cuota_credito  = SQL::filaEnObjeto($consulta_cuota);
        $estado_cuota_credito = $datos_cuota_credito->estado_cuota;

        $proyecto = SQL::obtenerValor("proyectos","nombre","codigo=$datos->codigo_proyecto");
        $banco    = SQL::obtenerValor("bancos","descripcion","codigo=$datos->codigo_banco");

        /* Obtener cuentas bancarias relacionadas con el proveedor */
        $consulta_cuotas = SQL::seleccionar(array("cuotas_creditos_bancos"), array("*"), "codigo_credito = '$url_id'");
        if (SQL::filasDevueltas($consulta_cuotas)) {

            $estados_cuota = array(
                "0" => $textos["ESTADO_0"],
                "1" => $textos["ESTADO_1"],
                "2" => $textos["ESTADO_2"]
            );
            while ($datos_cuotas = SQL::filaEnObjeto($consulta_cuotas)) {

                $id_cuota             = $datos_cuotas->codigo;
                $numero_cuota         = $datos_cuotas->numero_cuota;
                $interes              = $datos_cuotas->interes;
                $interes_pagado       = $datos_cuotas->interes_pagado;
                $abono_capital        = $datos_cuotas->abono_capital;
                $abono_capital_pagado = $datos_cuotas->abono_capital_pagado;
                $saldo_capital_pagado = $datos_cuotas->saldo_capital_pagado;
                $estado_cuota         = $estados_cuota[$datos_cuotas->estado_cuota];

                $item_cuota[]  = array( $id_cuota,
                                        $numero_cuota,
                                        $interes,
                                        $interes_pagado,
                                        $abono_capital,
                                        $abono_capital_pagado,
                                        $saldo_capital_pagado,
                                        $estado_cuota
                );
            }
        }

        if($datos_cuotas->abono_capital_pagado!=""){
            $error     = $textos["ERROR_ESTADO_ELIMINADO"];
            $titulo    = "";
            $contenido = "";

        }else{  
            /*** Definici�n de pesta�as general ***/
            $formularios["PESTANA_GENERAL"] = array(
                array(
                    HTML::mostrarDato("codigo", $textos["CODIGO"], $datos->codigo)
                    .HTML::campoOculto("codigo_credito", $datos->codigo),
                    HTML::mostrarDato("proyecto", $textos["PROYECTO"], $proyecto)
                ),
                array(
                    HTML::agrupador(
                        array(
                            array(   
                                HTML::mostrarDato("banco", $textos["BANCO"], $banco),
                                HTML::mostrarDato("numero_credito", $textos["NUMERO_CREDITO"], $datos->numero_credito),
                                HTML::mostrarDato("valor_credito", $textos["VALOR_CREDITO"], "$".number_format($datos->valor_credito,0)),
                                HTML::mostrarDato("estado_cuota", $textos["ESTADO_CUOTA"], $textos["ESTADO_".$estado_cuota_credito])
                            )
                        ),
                        $textos["DATOS_BANCO"]
                    )
                ),
                array(
                    HTML::agrupador(
                        array(
                            array(
                                HTML::mostrarDato("tasa_mensual", $textos["TASA_MENSUAL"], $datos->tasa_mensual),
                                HTML::mostrarDato("numero_cuotas", $textos["NUMERO_CUOTAS"], $datos->periodos),
                                HTML::mostrarDato("valor_cuota", $textos["VALOR_CUOTA"], "$".number_format($datos->valor_cuota,0)),
                                HTML::mostrarDato("fecha_credito", $textos["FECHA_CREDITO"], $datos->fecha_credito)
                            )
                        ),
                        $textos["DATOS_CREDITO"]
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
                            array("id","NRO_CUOTA","INTERES","INTERES_PAGADO","ABONO_CAPITAL","ABONO_CAPITAL_PAGADO","SALDO_CAPITAL_PAGADO","ESTADO_CUOTA"),
                            $item_cuota,
                            array("I","D","D","D","D","D","I"),
                            "lista_items_cuotas",
                            false)
                    )
                );
            }

            /*** Definici�n de botones ***/
            $botones = array(
                HTML::boton("botonAceptar", $textos["ACEPTAR"], "eliminarItem('$url_id');", "aceptar")
            );

            $contenido = HTML::generarPestanas($formularios, $botones);
        }
    }

    /*** Enviar datos para la generaci�n del formulario al script que origin� la petici�n ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

} elseif (!empty($forma_procesar)) {
    
    $existe_movimiento_credito = SQL::obtenerValor("cuotas_creditos_bancos","interes_pagado","codigo_credito=$forma_codigo_credito");
    
    if(!$existe_movimiento_credito){
        $elimina_cuota   = SQL::eliminar("cuotas_creditos_bancos", "codigo_credito='$forma_codigo_credito'");
        $elimina_credito = SQL::eliminar("creditos_bancos", "codigo='$forma_codigo_credito'");
    }

    if ($elimina_credito) {
        $error   = false;
        $mensaje = $textos["ITEM_ELIMINADO"];
    } else {
        $error   = true;
        $mensaje = $textos["ERROR_ELIMINAR_ITEM"];
    }

    /*** Enviar datos con la respuesta del proceso al script que origin� la petici�n ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

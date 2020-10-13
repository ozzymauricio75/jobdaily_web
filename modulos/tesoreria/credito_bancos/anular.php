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

/*** Devolver datos para autocompletar la búsqueda ***/
if (isset($url_completar)) {
    if (($url_item) == "selector1") {
        echo SQL::datosAutoCompletar("seleccion_municipios", $url_q);
    }
    
    if (($url_item) == "selector2") {
        echo SQL::datosAutoCompletar("seleccion_proyectos", $url_q);
    }

    if (($url_item) == "selector3") {
        echo SQL::datosAutoCompletar("menu_cuentas_bancarias", $url_q);
    }

    if (($url_item) == "selector4") {
        echo SQL::datosAutoCompletar("seleccion_proveedores", $url_q);
    }

    exit;
}
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

        /*** Definición de pestañas general ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::mostrarDato("codigo", $textos["CODIGO"], $datos->codigo),
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
        /*** Definición de pestaña de cuentas bancarias relacionadas ***/
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

        /*** Definición de botones ***/
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "eliminarItem('$url_id');", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones);
    }

    /*** Enviar datos para la generación del formulario al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

/*** Adicionar los datos provenientes del formulario ***/
} elseif (!empty($forma_procesar)) {
    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_ANULADO"];

    $datos = array(
        "estado"         => "1",
        "fecha_registra" => date("Y-m-d H:i:s")
    );
    
    $consulta = SQL::modificar("movimientos_tesoreria", $datos, "codigo = '$forma_id'");

    if ($consulta) {
        $saldo       = SQL::obtenerValor("saldos_movimientos","saldo","codigo_movimiento='$forma_id'");
        $nuevo_saldo = $saldo + $forma_valor_movimiento;

        $datos_saldos_movimientos = array(
            "saldo"                   => $nuevo_saldo,
            "fecha_saldo"             => date("Y-m-d H:i:s"),
            "codigo_usuario_registra" => $forma_sesion_id_usuario_ingreso
        );
        $modificar_saldo = SQL::modificar("saldos_movimientos", $datos_saldos_movimientos, "codigo_movimiento='$forma_id'"); 
        
        $error       = false;
        $mensaje     = $textos["ITEM_ANULADO"];
    } else {
        $error   = true;
        $mensaje = $textos["ERROR_ANULAR_ITEM"];
    }

    /*** Enviar datos con la respuesta del proceso al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

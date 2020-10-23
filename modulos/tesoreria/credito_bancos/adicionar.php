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
        echo SQL::datosAutoCompletar("seleccion_bancos", $url_q);
    }

    if (($url_item) == "selector4") {
        echo SQL::datosAutoCompletar("seleccion_proveedores", $url_q);
    }

    if (($url_item) == "selector5") {
        echo SQL::datosAutoCompletar("menu_cuentas_bancarias", $url_q);
    }

    exit;

/*** Mostrar los datos de la cuenta ***/
} elseif (!empty($url_cargarCuenta)) {
    $cuenta       = $url_cuenta;
    $consulta     = SQL::seleccionar(array("cuentas_bancarias"), array("*"), "numero='$cuenta'");
    $datos_cuenta = SQL::filaEnObjeto($consulta);
    $banco        = $datos_cuenta->codigo_banco;
    //$sucursal     = $datos_cuenta->codigo_sucursal;

    $nombre_banco = SQL::obtenerValor("bancos","descripcion","codigo='$banco'");
    //$tercero      = SQL::obtenerValor("sucursales","nombre","codigo='$sucursal'");

    if($nombre_banco){
        $datos = array(
            $nombre_banco
            //$tercero
        );
    }else{
        $datos = "";
    }
    
    HTTP::enviarJSON($datos);
    exit;

/*** Calcular datos del credito ***/
} elseif (!empty($url_calcularCredito)) {
    $valor_credito = $url_valor_credito;
    $tasa_mensual  = $url_tasa_mensual;
    $numero_cuotas = $url_numero_cuotas;
    $valor_credito   = str_replace(".", "", $valor_credito);

    $calculo_tasa  = (100 + ($tasa_mensual))/100;
    $valor_cuota   = pow(($calculo_tasa), $numero_cuotas);
    $valor_cuota   = ($valor_cuota*$tasa_mensual)/100;
    $valor_cuota   = $valor_cuota*$valor_credito;
    $calculo       = (pow($calculo_tasa, $numero_cuotas)-1);
    $valor_cuota   = $valor_cuota/$calculo;
    $valor_cuota   = number_format($valor_cuota,0);
    $valor_cuota   = str_replace(",", ".", $valor_cuota);

    HTTP::enviarJSON($valor_cuota);
    exit; 
}
/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {
    $error    = "";
    $titulo   = $componente->nombre;
    
    $consulta_creditos_bancos = SQL::seleccionar(array("bancos"),array("*"),"codigo>0");
    if (SQL::filasDevueltas($consulta_creditos_bancos)){

        //Asignar codigo siguiente de la tabla 
        $codigo = SQL::obtenerValor("creditos_bancos","MAX(codigo)","codigo>0");

        if ($codigo){
            $codigo++;
        } else {
            $codigo = 1;
        }
    
         /*** Definición de pestañas general ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::campoTextoCorto("*codigo", $textos["CODIGO"], 4, 4, $codigo, array("readonly" => "true"), array("title" => $textos["AYUDA_CODIGO"],"onBlur" => "validarItem(this);", "onKeyPress" => "return campoEntero(event)")),
  
                HTML::campoTextoCorto("selector2", $textos["PROYECTO"], 40, 255, "", array("title" => $textos["AYUDA_PROYECTO"], "class" => "autocompletable extracto" ))
            ),
            array(    
                HTML::agrupador(
                    array(
                        array(
                            HTML::campoTextoCorto("*selector5",$textos["NUMERO_CUENTA"], 15, 15, "", array("title"=>$textos["AYUDA_NUMERO_CUENTA"],"class" => "autocompletable", "onChange"=>"cargarCuenta(), saldoCuenta()")),

                            HTML::campoTextoCorto("banco", $textos["BANCO"], 20, 20, "", array("readonly" => "true"), array("title" => $textos["AYUDA_BANCO"],"onBlur" => "validarItem(this);"))
                        )
                    ),
                    $textos["CUENTA_DESTINO"]
                )
            ),
            array(
                HTML::agrupador(
                    array(
                        array(    
                            HTML::campoTextoCorto("*selector3",$textos["BANCO"], 15, 15, "", array("title"=>$textos["AYUDA_BANCO"],"class" => "autocompletable")),

                            HTML::campoTextoCorto("*numero_credito", $textos["NUMERO_CREDITO"], 20, 20, "", array("title" => $textos["AYUDA_NUMERO_CREDITO"],"onBlur" => "validarItem(this)")),

                            HTML::campoTextoCorto("*valor_credito", $textos["VALOR_CREDITO"], 20, 20, "", array("title" => $textos["AYUDA_VALOR_CREDITO"],"onBlur" => "validarItem(this)","onkeyup"=>"formatoMiles(this)"))
                        )
                    ),
                    $textos["DATOS_BANCO"]
                )
            ),
            array(
                HTML::agrupador(
                    array(
                        array(
                            HTML::campoTextoCorto("*tasa_mensual", $textos["TASA_MENSUAL"], 4, 4, "", array("title" => $textos["AYUDA_TASA_MENSUAL"],"onBlur" => "validarItem(this);")), 

                            HTML::campoTextoCorto("*numero_cuotas", $textos["NUMERO_CUOTAS"], 3, 3, "", array("title" => $textos["AYUDA_NUMERO_CUOTAS"],"onBlur" => "validarItem(this),calcularCredito()")), 

                            HTML::campoTextoCorto("valor_cuota", $textos["VALOR_CUOTA"], 20, 20, "", array("title" => $textos["AYUDA_VALOR_CUOTA"],"onBlur" => "validarItem(this);")),

                            HTML::campoTextoCorto("fecha_credito", $textos["FECHA_CREDITO"], 10, 10, date("Y-m-d"), array("class" => "selectorFecha"),array("title" => $textos["AYUDA_FECHA_CREDITO"])),
                        )
                    ),
                    $textos["DATOS_CREDITO"]
                )
            ),
            array(
                HTML::campoTextoCorto("observaciones", $textos["OBSERVACIONES"], 86, 254, "", array("title" => $textos["AYUDA_OBSERVACIONES"]))
            )
        );

        /*** Definicion de botones ***/
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones);
    } else {
        $contenido = "";
        $error     = $textos["CREAR_BANCOS"];
    }

    /*** Enviar datos para la generacion del formulario al script que origino la peticion ***/
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

/*** Validar los datos provenientes del formulario ***/
} elseif (!empty($url_validar)) {

    $respuesta = "";

    /*** Validar codigo ***/
    if ($url_item == "numero_credito") {
        $existe = SQL::existeItem("creditos_bancos", "numero_credito", $url_valor, "codigo != '0'");

        if ($existe) {
            $respuesta = $textos["ERROR_EXISTE_NUMERO_CREDITO"];
        }
    }

    if ($url_item == "numero_credito") {
        $existe = SQL::existeItem("creditos_bancos", "numero_credito", $url_valor, "numero_credito != '0'");

        if ($existe) {
            $respuesta = $textos["ERROR_EXISTE_NUMERO_CREDITO"];
        }
    }

    HTTP::enviarJSON($respuesta);

/*** Validar los datos provenientes del formulario ***/
} elseif (!empty($url_validar)) {

    $respuesta = "";

    /*** Validar codigo ***/
    if ($url_item == "codigo") {
        $existe = SQL::existeItem("proyectos", "codigo", $url_valor, "codigo != '0'");

        if ($existe) {
            $respuesta = $textos["ERROR_EXISTE_CODIGO"];
        }
    }    

    /*** Validar nombre ***/
    if ($url_item == "nombre") {
        $existe = SQL::existeItem("proyectos", "nombre", $url_valor, "nombre !=''");

        if ($existe) {
            $respuesta = $textos["ERROR_EXISTE_NOMBRE"];
        }
    }

    HTTP::enviarJSON($respuesta);

/*** Adicionar los datos provenientes del formulario ***/
} elseif (!empty($forma_procesar)) {

    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];

    /*** Validar el ingreso de los datos requeridos ***/
    if(empty($forma_codigo)){
        $error   = true;
        $mensaje = $textos["CODIGO_VACIO"];

    } elseif(empty($forma_selector3)){
        $error   = true;
        $mensaje = $textos["BANCO_VACIO"];

    } elseif(empty($forma_numero_credito)){
        $error   = true;
        $mensaje = $textos["NUMERO_CREDITO_VACIO"];

    } elseif(empty($forma_valor_credito)){
        $error   = true;
        $mensaje = $textos["VALOR_VACIO"];

    } elseif(empty($forma_tasa_mensual)){
        $error   = true;
        $mensaje = $textos["TASA_MENSUAL_VACIO"];

    } elseif(empty($forma_numero_cuotas)){
        $error   = true;
        $mensaje = $textos["NUMERO_CUOTAS_VACIO"];        

    } else {

        /*** Quitar separador de miles a un numero ***/
        function quitarMiles($cadena){
            $valor = array();
            for ($i = 0; $i < strlen($cadena); $i++) {
                if (substr($cadena, $i, 1) != ".") {
                    $valor[$i] = substr($cadena, $i, 1);
                }
            }
            $valor = implode($valor);
            return $valor;
        }
        $i = 1;
        $forma_valor_credito = quitarMiles($forma_valor_credito);
        $forma_valor_cuota   = quitarMiles($forma_valor_cuota);

        $llave               = explode(":", $forma_selector3);
        $codigo_banco        = $llave[0];

        $llave               = explode("-", $forma_selector2);
        $codigo_proyecto     = $llave[0];
        
        /*** Insertar datos ***/
        $datos = array(
            "codigo"           => $forma_codigo,
            "codigo_banco"     => $codigo_banco,
            "codigo_proyecto"  => $codigo_proyecto,
            "numero_credito"   => $forma_numero_credito,
            "tasa_mensual"     => $forma_tasa_mensual,
            "valor_credito"    => $forma_valor_credito,
            "fecha_credito"    => $forma_fecha_credito,
            "periodos"         => $forma_numero_cuotas,
            "valor_cuota"      => $forma_valor_cuota,
            "estado_credito"   => 1,
            "observaciones"    => $forma_observaciones
        );
        $insertar = SQL::insertar("creditos_bancos", $datos);

        /*** Error de insercion ***/
        if (!$insertar) {
            $error   = true;
            $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
        } else{
            /*** Grabar las cuotas del credito en cuotas_creditos_bancos ****/
            while($i<=$forma_numero_cuotas){
                if($i==1){
                    $interes             = ($forma_tasa_mensual*$forma_valor_credito)/100;
                    $abono_capital       = $forma_valor_cuota - $interes;
                    $nuevo_saldo_credito = $forma_valor_credito - $abono_capital;    
                }else{
                    if($nuevo_saldo_credito>=$forma_valor_cuota){
                        $interes             = ($forma_tasa_mensual*$nuevo_saldo_credito)/100;
                        $abono_capital       = $forma_valor_cuota - $interes;
                        $nuevo_saldo_credito = $nuevo_saldo_credito - $abono_capital;
                    }else{
                        $interes             = ($forma_tasa_mensual*$nuevo_saldo_credito)/100;
                        $abono_capital       = $nuevo_saldo_credito - $interes;
                        $nuevo_saldo_credito = 0;
                    }
                }

                $datos_cuotas_credito = array(
                    "codigo_credito"       => $forma_codigo,
                    "numero_cuota"         => $i,
                    "interes"              => $interes,
                    "interes_pagado"       => 0,
                    "abono_capital"        => $abono_capital,
                    "abono_capital_pagado" => 0,
                    "saldo_capital"        => $nuevo_saldo_credito,
                    "saldo_capital_pagado" => 0,
                    "observaciones"        => "",
                    "estado_cuota"         => 1
                );
                $insertar_cuotas = SQL::insertar("cuotas_creditos_bancos", $datos_cuotas_credito);  
                $i++; 
            }
            /*** Insertar datos movimientos tesoreria ***/
            $forma_codigo_movimiento = SQL::obtenerValor("movimientos_tesoreria","MAX(codigo)","codigo>0");
            
            // Asignar codigo siguiente de la tabla 
            if ($forma_codigo_movimiento){
                $forma_codigo_movimiento++;
            } else {
                $forma_codigo_movimiento = 1;
            }

            $datos = array(
                "codigo"                       => $forma_codigo_movimiento,
                "sentido"                      => "C",
                "codigo_proyecto"              => $codigo_proyecto,
                "numero_credito"               => $forma_numero_credito,
                "codigo_grupo_tesoreria"       => 3,
                "codigo_concepto_tesoreria"    => 4,
                "cuenta_origen"                => $forma_selector5,
                "valor_movimiento"             => $forma_valor_credito,
                "fecha_registra"               => $forma_fecha_credito,
                "codigo_usuario_registra"      => $forma_sesion_id_usuario_ingreso,
                "observaciones"                => $forma_observaciones,
                "estado"                       => 0
            );
            $insertar = SQL::insertar("movimientos_tesoreria", $datos);

            /*** Grabar nuevo saldo en la tabla saldos_movimientos ****/

            /*** Verificar saldos iniciales ****/
            $codigo_saldos_movimientos = SQL::obtenerValor("saldos_movimientos","MAX(codigo)","cuenta_origen='$forma_selector5'");

            if(!$codigo_saldos_movimientos){
                $saldo_inicial  = SQL::obtenerValor("saldo_inicial_cuentas","saldo","cuenta_origen='$forma_selector5'");
                $saldo_anterior = $saldo_inicial; 
            } else{
                $saldo_anterior = SQL::obtenerValor("saldos_movimientos","saldo","codigo='$codigo_saldos_movimientos'");
            }

            $nuevo_saldo = $saldo_anterior + $forma_valor_credito;

            $datos_saldos_movimientos = array(
                "codigo_movimiento"       => $forma_codigo_movimiento,
                "cuenta_origen"           => $forma_selector5,
                "saldo"                   => $nuevo_saldo,
                "fecha_saldo"             => $forma_fecha_credito,
                "codigo_usuario_registra" => $forma_sesion_id_usuario_ingreso,
                "observaciones"           => $forma_observaciones
            );
            $insertar_saldo = SQL::insertar("saldos_movimientos", $datos_saldos_movimientos);  
        }
    }
    /*** Enviar datos con la respuesta del proceso al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

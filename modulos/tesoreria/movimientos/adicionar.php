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
        echo SQL::datosAutoCompletar("menu_cuentas_bancarias", $url_q);
    }

    if (($url_item) == "selector4") {
        echo SQL::datosAutoCompletar("seleccion_proveedores", $url_q);
    }

    if (($url_item) == "selector5") {
        echo SQL::datosAutoCompletar("seleccion_creditos_bancos", $url_q);
    }
    exit;

/*** Mostrar los datos de la cuenta ***/
} elseif (!empty($url_cargarCuenta)) {
    $cuenta       = $url_cuenta;
    $consulta     = SQL::seleccionar(array("cuentas_bancarias"), array("*"), "numero='$cuenta'");
    $datos_cuenta = SQL::filaEnObjeto($consulta);
    $banco        = $datos_cuenta->codigo_banco;
    $sucursal     = $datos_cuenta->codigo_sucursal;

    $nombre_banco = SQL::obtenerValor("bancos","descripcion","codigo='$banco'");
    $tercero      = SQL::obtenerValor("sucursales","nombre","codigo='$sucursal'");

    if($nombre_banco){
        $datos = array(
            $nombre_banco,
            $tercero
        );
    }else{
        $datos = "";
    }
    
    HTTP::enviarJSON($datos);
    exit; 

    /*** Verificar si existe saldo inicial ***/
} elseif (!empty($url_saldoCuenta)) {
    $cuenta       = $url_cuenta;
    $consulta     = SQL::obtenerValor("saldo_inicial_cuentas","saldo","cuenta_origen='$cuenta'");

    if($consulta){
        $indicador = 1;
    }else{
        $indicador = 0;
    }
    
    HTTP::enviarJSON($indicador);
    exit; 

    /*** Verificar si el saldo es mayor que el movimiento a ingresar ***/
} elseif (!empty($url_valorSaldo)) {
    $cuenta       = $url_cuenta;
    $valor        = $url_valor;

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

    $valor = quitarMiles($valor);
    $valor = quitarMiles($valor);

    /*** Verificar saldos iniciales ****/
    $codigo_saldos_movimientos = SQL::obtenerValor("saldos_movimientos","MAX(codigo)","cuenta_origen='$cuenta'");

    if(!$codigo_saldos_movimientos){
        $saldo_inicial  = SQL::obtenerValor("saldo_inicial_cuentas","saldo","cuenta_origen='$cuenta'");
        $saldo_anterior = $saldo_inicial; 
    } else{
        $saldo_anterior = SQL::obtenerValor("saldos_movimientos","saldo","codigo='$codigo_saldos_movimientos'");
    }

    if($saldo_anterior>=$valor){
        $indicador = 1;
    } else{
        $indicador = 0;
    }
    
    HTTP::enviarJSON($indicador);
    exit;     

/*** Mostrar los datos de la cuenta ***/
} elseif (!empty($url_cargarCuentaProveedor)) {
    $llave             = explode("-", $url_nit_proveedor);
    $url_nit_proveedor = $llave[0];

    $lista = HTML::generarDatosLista("cuentas_bancarias_proveedores","cuenta","cuenta","documento_identidad_proveedor = '".$url_nit_proveedor."'");
    
    if(empty($lista)){
        $lista = array("0" => $textos["PROVEEDOR_SIN_CUENTA"]);
    }

    HTTP::enviarJSON($lista);
    exit;
}
/*** Mostrar los conceptos de tesoreria ***/
if(isset($url_recargar_conceptos)){
    
    $lista = HTML::generarDatosLista("conceptos_tesoreria","codigo","nombre_concepto","codigo_grupo_tesoreria = '".$url_codigo_grupo."'");
    
    if(empty($lista)){
        $lista = array("0" => $textos["CONCEPTO_SIN_GRUPO"]);
    }

    HTTP::enviarJSON($lista);
    exit;
}

/*** Mostrar los creditos activos ***/
if(isset($url_cargarCuotasCreditos)){
    $llave          = explode(":", $url_numero_credito);
    $numero_credito = $llave[0];
    $codigo_credito = SQL::obtenerValor("creditos_bancos","codigo","numero_credito='$numero_credito'");
    
    if($numero_credito!=0){
        $lista_cuotas = HTML::generarDatosLista("cuotas_creditos_bancos","numero_cuota","numero_cuota","estado_cuota='1' AND codigo_credito='".$codigo_credito."'");
    }

    HTTP::enviarJSON($lista_cuotas);
    exit;
}

/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {
    $error    = "";
    $titulo   = $componente->nombre;
    
    $consulta_conceptos_tesoreria = SQL::seleccionar(array("conceptos_tesoreria"),array("*"),"codigo>0");
    if (SQL::filasDevueltas($consulta_conceptos_tesoreria)){

        //Asignar codigo siguiente de la tabla 
        $codigo = SQL::obtenerValor("movimientos_tesoreria","MAX(codigo)","codigo>0");

        if ($codigo){
            $codigo++;
        } else {
            $codigo = 1;
        }
        $grupos    = HTML::generarDatosLista("grupos_tesoreria", "codigo", "nombre_grupo","codigo>0");
        $conceptos = HTML::generarDatosLista("conceptos_tesoreria", "codigo_grupo_tesoreria", "nombre_concepto","codigo_grupo_tesoreria = '".array_shift(array_keys($grupos))."'");
    
         /*** Definición de pestañas general ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::agrupador(
                    array(
                        array(
                            HTML::campoTextoCorto("*codigo", $textos["CODIGO"], 4, 4, $codigo, array("readonly" => "true"), array("title" => $textos["AYUDA_CODIGO"],"onBlur" => "validarItem(this);", "onKeyPress" => "return campoEntero(event)")),
                            
                            HTML::listaSeleccionSimple("*codigo_grupo", $textos["GRUPO_TESORERIA"], HTML::generarDatosLista("grupos_tesoreria", "codigo", "nombre_grupo"), "", array("title" => $textos["AYUDA_GRUPO_TESORERIA"],"onChange" => "verificarConceptos();")),

                            HTML::listaSeleccionSimple("*codigo_concepto", $textos["CONCEPTO_TESORERIA"], HTML::generarDatosLista("conceptos_tesoreria", "codigo", "nombre_concepto"), "", array("title" => $textos["AYUDA_CONCEPTO_TESORERIA"],"")),

                            //HTML::listaSeleccionSimple("*numero_credito", $textos["NUMERO_CREDITO"], $numero_credito, "",array("title" => $textos["AYUDA_NUMERO_CREDITO"],"class" => "oculto"))
                        ),
                    array(
                        HTML::campoTextoCorto("selector5", $textos["NUMERO_CREDITO"], 30, 30, "", array("title" => $textos["AYUDA_NUMERO_CREDITO"],"class" => "autocompletable","onChange" => "cargarCuotasCreditos()")),

                        HTML::listaSeleccionSimple("cuotas_credito", $textos["CUOTAS_CREDITO"], "", "", array("title" => $textos["AYUDA_VALOR_CREDITO"],""))
                        ),    
                    ),
                    $textos["BASICOS"]
                )    
            ),
            array(
                HTML::agrupador(
                    array(
                        array(
                            HTML::campoTextoCorto("*selector3",$textos["NUMERO_CUENTA"], 15, 15, "", array("title"=>$textos["AYUDA_NUMERO_CUENTA"],"class" => "autocompletable", "onChange"=>"cargarCuenta(), saldoCuenta()")),

                            HTML::campoTextoCorto("banco", $textos["BANCO"], 20, 20, "", array("readonly" => "true"), array("title" => $textos["AYUDA_BANCO"],"onBlur" => "validarItem(this);")),

                            HTML::campoTextoCorto("tercero", $textos["TERCERO"], 20, 20, "", array("readonly" => "true"), array("title" => $textos["AYUDA_TERCERO"],"onBlur" => "validarItem(this);"))
                        )
                    ),
                    $textos["CUENTA_ORIGEN"]
                )
            ),
            array(
                HTML::agrupador(
                    array(
                        array(    
                            HTML::campoTextoCorto("selector2", $textos["PROYECTO"], 40, 255, "", array("title" => $textos["AYUDA_PROYECTO"], "class" => "autocompletable extracto" ))
                        ),
                        array(
                            HTML::campoTextoCorto("fecha_movimiento", $textos["FECHA_MOVIMIENTO"], 10, 10, date("Y-m-d"), array("class" => "selectorFecha"),array("title" => $textos["AYUDA_FECHA_MOVIMIENTO"])),

                            HTML::campoTextoCorto("*valor", $textos["VALOR_MOVIMIENTO"], 20, 20, "", array("title" => $textos["AYUDA_VALOR_MOVIMIENTO"],"onBlur" => "validarItem(this)", "onkeyup"=>"formatoMiles(this),valorSaldo(this)"))
                        ),
                        array(
                            HTML::campoTextoCorto("*observaciones", $textos["OBSERVACIONES"], 75, 254, "", array("title" => $textos["AYUDA_OBSERVACIONES"]))
                        )
                    ),
                    $textos["DATOS_MOVIMIENTO"]
                )
            ),
            array(
                HTML::agrupador(
                    array(
                        array(
                            HTML::campoTextoCorto("selector4",$textos["PROVEEDOR"], 45, 45, "", array("title"=>$textos["AYUDA_PROVEEDOR"],"class" => "autocompletable", "onBlur"=>"cargarCuentaProveedor()")),

                            HTML::listaSeleccionSimple("cuenta_destino", $textos["CUENTA_DESTINO"], HTML::generarDatosLista("cuentas_bancarias_proveedores", "cuenta", "documento_identidad_proveedor","documento_identidad_proveedor = 0"), "", array("title" => $textos["AYUDA_CUENTA_DESTINO"])),
                        )
                    ),
                    $textos["CUENTA_DESTINO"]
                )
            )
        );

        /*** Definicion de botones ***/
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones);
    } else {
        $contenido = "";
        $error     = $textos["CREAR_CONCEPTOS_TESORERIA"];
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
    if ($url_item == "codigo") {
        $existe = SQL::existeItem("movimientos_tesoreria", "codigo", $url_valor, "codigo != '0'");

        if ($existe) {
            $respuesta = $textos["ERROR_EXISTE_CODIGO"];
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
        $mensaje = $textos["CUENTA_VACIO"];

    } elseif(empty($forma_fecha_movimiento)){
        $error   = true;
        $mensaje = $textos["FECHA_VACIO"];

    } elseif(empty($forma_valor)){
        $error   = true;
        $mensaje = $textos["VALOR_VACIO"];

    } else {
        $sentido                       = SQL::obtenerValor("conceptos_tesoreria","sentido","codigo='$forma_codigo_concepto'");
        
        $llave                         = explode("-", $forma_selector4);
        $documento_identidad_proveedor = $llave[0];

        $llave                         = explode(":", $forma_selector5);
        $numero_credito                = $llave[0];

        if(!$forma_selector2){
            $forma_selector2 = "";
        
        } elseif(!$forma_cuenta_destino){
            $forma_cuenta_destino = "";

        } elseif(!$forma_selector4){
            $documento_identidad_proveedor = "";
        }

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

        $forma_valor = quitarMiles($forma_valor);
        
        /*** Verificar saldos iniciales ****/
        $codigo_saldos_movimientos = SQL::obtenerValor("saldos_movimientos","MAX(codigo)","cuenta_origen='$forma_selector3'");

        if(!$codigo_saldos_movimientos){
            $saldo_inicial  = SQL::obtenerValor("saldo_inicial_cuentas","saldo","cuenta_origen='$forma_selector3'");
            $saldo_anterior = $saldo_inicial; 
        } else{
            $saldo_anterior = SQL::obtenerValor("saldos_movimientos","saldo","codigo='$codigo_saldos_movimientos'");
        }

        /*** Insertar datos ***/
        $datos = array(
            "codigo"                       => $forma_codigo,
            "sentido"                      => $sentido,
            "codigo_proyecto"              => $forma_selector2,
            "numero_credito"               => $numero_credito,
            "codigo_grupo_tesoreria"       => $forma_codigo_grupo,
            "codigo_concepto_tesoreria"    => $forma_codigo_concepto,
            "cuenta_proveedor"             => $forma_cuenta_destino,
            "cuenta_origen"                => $forma_selector3,
            "valor_movimiento"             => $forma_valor,
            "documento_identidad_tercero"  => $documento_identidad_proveedor,
            "fecha_registra"               => $forma_fecha_movimiento,
            "codigo_usuario_registra"      => $forma_sesion_id_usuario_ingreso,
            "observaciones"                => $forma_observaciones,
            "estado"                       => 0
        );
        $insertar = SQL::insertar("movimientos_tesoreria", $datos);

        /*** Error de insercion ***/
        if (!$insertar) {
            $error   = true;
            $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
        }else{
            /*** Grabar nuevo saldo en la tabla saldos_movimientos ****/
            if($sentido=="D"){
                $nuevo_saldo = $saldo_anterior - $forma_valor;
            } else{
                $nuevo_saldo = $saldo_anterior + $forma_valor;
            }

            $datos_saldos_movimientos = array(
                "codigo_movimiento"       => $forma_codigo,
                "cuenta_origen"           => $forma_selector3,
                "saldo"                   => $nuevo_saldo,
                "fecha_saldo"             => $forma_fecha_movimiento,
                "codigo_usuario_registra" => $forma_sesion_id_usuario_ingreso,
                "observaciones"           => $forma_observaciones
            );
            $insertar_saldo = SQL::insertar("saldos_movimientos", $datos_saldos_movimientos);

            /*** Obtener valores del credito ***/
            $llave          = explode(":", $forma_selector5);
            $numero_credito = $llave[0];

            $codigo_credito      = SQL::obtenerValor("creditos_bancos","codigo","numero_credito='$numero_credito'");
            $cuota_del_credito   = SQL::obtenerValor("creditos_bancos","valor_cuota","numero_credito='$numero_credito'");
            $cuota_del_credito   = (int)$cuota_del_credito;
            $valor_credito       = SQL::obtenerValor("creditos_bancos","valor_credito","numero_credito='$numero_credito'");
            $nuevo_saldo_credito = $valor_credito;

            /* Obtener cuotas relacionadas con el credito */
            $consulta_cuotas = SQL::seleccionar(array("cuotas_creditos_bancos"), array("*"), "codigo_credito = '$codigo_credito' AND numero_cuota>='$forma_cuotas_credito'");
         
            if (SQL::filasDevueltas($consulta_cuotas)) {
                $valor_movimiento_cuota = (int)$forma_valor;
 
                while ($datos_cuotas = SQL::filaEnObjeto($consulta_cuotas)) {
                    
                    if($valor_movimiento_cuota>=$cuota_del_credito){
                        $interes_pagado       = $datos_cuotas->interes;
                        $abono_capital_pagado = $datos_cuotas->abono_capital;
                        $nuevo_saldo_credito  = $nuevo_saldo_credito - $abono_capital_pagado;aqui voy

                        $datos_cuotas_credito = array(
                            "codigo_credito"       => $codigo_credito,
                            "numero_cuota"         => $forma_cuotas_credito,
                            "interes_pagado"       => $interes_pagado,
                            "abono_capital_pagado" => $abono_capital_pagado,
                            "saldo_capital_pagado" => $nuevo_saldo_credito,
                            "observaciones"        => "",
                            "estado_cuota"         => 0
                        );
                        $insertar_cuotas        = SQL::insertar("cuotas_creditos_bancos", $datos_cuotas_credito);
                        $valor_movimiento_cuota = $valor_movimiento_cuota-$cuota_del_credito;

                    } elseif($valor_movimiento_cuota < $cuota_del_credito){
                        
                        if($valor_movimiento_cuota>=$datos_cuotas->interes){
                            $interes_pagado         = $datos_cuotas->interes;
                            $abono_capital_pagado   = $valor_movimiento_cuota - $datos_cuotas->interes;
                            $valor_movimiento_cuota = $valor_movimiento_cuota - $interes_pagado - $abono_capital_pagado; 
                            $nuevo_saldo_credito    = $nuevo_saldo_credito - $abono_capital_pagado;
                        } else{
                            $interes_pagado         = $valor_movimiento_cuota;
                            $abono_capital_pagado   = 0;
                            $valor_movimiento_cuota = 0;
                        }
                        
                        $datos_cuotas_credito = array(
                            "codigo_credito"       => $codigo_credito,
                            "numero_cuota"         => $forma_cuotas_credito,
                            "interes_pagado"       => $interes_pagado,
                            "abono_capital_pagado" => $abono_capital_pagado,
                            "saldo_capital_pagado" => $nuevo_saldo_credito,
                            "observaciones"        => "",
                            "estado_cuota"         => 0
                        );
                        $insertar_cuotas = SQL::insertar("cuotas_creditos_bancos", $datos_cuotas_credito);
                    }
                }    
            }
        }
    }
    /*** Enviar datos con la respuesta del proceso al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

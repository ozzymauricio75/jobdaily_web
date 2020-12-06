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

    if (($url_item) == "selector6") {
        echo SQL::datosAutoCompletar("menu_cuentas_bancarias", $url_q);
    }

    /*if (($url_item) == "selector5") {
        echo SQL::datosAutoCompletar("seleccion_creditos_bancos", $url_q);
    }*/
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

/*** Mostrar los datos de la cuenta destino***/
} elseif (!empty($url_cargarCuentas)) {
    $cuenta_destino = $url_cuenta_destino;
    $consulta       = SQL::seleccionar(array("cuentas_bancarias"), array("*"), "numero='$cuenta_destino'");
    $datos_cuenta   = SQL::filaEnObjeto($consulta);
    $banco          = $datos_cuenta->codigo_banco;
    $sucursal       = $datos_cuenta->codigo_sucursal;

    $nombre_banco   = SQL::obtenerValor("bancos","descripcion","codigo='$banco'");
    $tercero        = SQL::obtenerValor("sucursales","nombre","codigo='$sucursal'");

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
   
   /*** Valida que la cuenta origen sea diferente a la de destino***/
} elseif (!empty($url_cuentasDiferentes)) {
    $cuenta_origen  = $url_cuenta_origen;
    $cuenta_destino = $url_cuenta_destino;

    if($cuenta_origen!=$cuenta_destino){
        $datos = 1;
    } else{
        $datos = 0;
    }
    
    HTTP::enviarJSON($datos);
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
    $codigo_saldos_movimientos = SQL::obtenerValor("saldos_movimientos","MAX(codigo)","cuenta_origen='$cuenta' AND estado='0'");

    if(!$codigo_saldos_movimientos){
        $saldo_inicial  = SQL::obtenerValor("saldo_inicial_cuentas","saldo","cuenta_origen='$cuenta'");
        $saldo_anterior = $saldo_inicial; 
    } else{
        $saldo_anterior = SQL::obtenerValor("saldos_movimientos","saldo","codigo='$codigo_saldos_movimientos' AND estado='0'");
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

/*** Mostrar los datos de la cuenta del proveedor***/
} elseif (!empty($url_cargarBancoProveedor)) {
    $cuenta_destino = $url_cuenta_destino;

    $codigo_banco_proveedor = SQL::obtenerValor("cuentas_bancarias_proveedores","codigo_banco","cuenta='$cuenta_destino'");
    $banco_proveedor        = SQL::obtenerValor("bancos","descripcion","codigo='$codigo_banco_proveedor'");

    HTTP::enviarJSON($banco_proveedor);
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

/*** Mostrar el valor del credito ***/
if(isset($url_validarMonto)){
    $codigo_concepto  = $url_codigo_concepto;
    $valor_movimiento = $url_valor_movimiento;
    $numero_cuenta    = $url_numero_cuenta;
    
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

    $valor_movimiento = quitarMiles($valor_movimiento);
    $valor_movimiento = (int)$valor_movimiento;

    $sentido = SQL::obtenerValor("conceptos_tesoreria","sentido","codigo='$codigo_concepto'");
    
    if($sentido=="D"){
        $codigo_movimiento = SQL::obtenerValor("saldos_movimientos","MAX(codigo)","cuenta_origen='$numero_cuenta' AND estado='0'");
        
        if($codigo_movimiento){
            $saldo_cuenta     = SQL::obtenerValor("saldos_movimientos","saldo","codigo='$codigo_movimiento'");
            $saldo_cuenta     = (int)$saldo_cuenta;

            if($saldo_cuenta>=$valor_movimiento){
                $datos = "1";
            } else{
                $datos = "0";
            } 
        } else {
            $saldo_inicial = SQL::obtenerValor("saldo_inicial_cuentas","saldo","cuenta_origen='$numero_cuenta'"); 
            $saldo_cuenta     = (int)$saldo_cuenta;

            if($saldo_inicial>=$valor_movimiento){
                $datos = "1";
            } else{
                $datos = "0";
            }
        } 
    }
    HTTP::enviarJSON($datos);
    exit;
}

/*** Mostrar los creditos activos ***/
if(isset($url_cargarCuotasCreditos)){
    /*$llave        = explode(":", $url_numero_credito);
    $numero_credito = $llave[0];*/
    $numero_credito = $url_numero_credito;
    $codigo_credito = SQL::obtenerValor("creditos_bancos","codigo","numero_credito='$numero_credito'");
    
    if($numero_credito!=0){
        $lista_cuotas = HTML::generarDatosLista("cuotas_creditos_bancos","numero_cuota","numero_cuota","estado_cuota='1' OR estado_cuota='2' AND codigo_credito='".$codigo_credito."'");
    }

    HTTP::enviarJSON($lista_cuotas);
    exit;
}

/*** Mostrar el valor de la cuota ***/
if(isset($url_valorCuota)){
    $numero_credito = $url_numero_credito;
    $numero_cuota   = $url_numero_cuota;
    $codigo_credito = SQL::obtenerValor("creditos_bancos","codigo","numero_credito='$numero_credito'");
    $valor_cuota    = SQL::obtenerValor("cuotas_creditos_bancos","abono_capital","codigo_credito='$codigo_credito' AND numero_cuota='$numero_cuota'");
    $valor_cuota = "$".number_format($valor_cuota,0);

    HTTP::enviarJSON($valor_cuota);
    exit;
}

/*** Valida que exista el credito  ***/
if(isset($url_existeCredito)){
    $numero_credito = $url_numero_credito;
    $estado_credito = SQL::obtenerValor("creditos_bancos","estado_credito","numero_credito='$numero_credito'");
    
    if($estado_credito){
        $codigo_credito = SQL::obtenerValor("creditos_bancos","codigo","numero_credito='$numero_credito'");
    } else{
        $codigo_credito = "";
    }

    HTTP::enviarJSON($codigo_credito);
    exit;
}

/*** Valida que el valor del movimiento no supere el saldo de la cuenta ***/
if(isset($url_cargaValorCredito)){
    $numero_credito = $url_numero_credito;

    $valor_credito = SQL::obtenerValor("creditos_bancos","valor_credito","numero_credito='$numero_credito'");
    $valor_credito = number_format($valor_credito,0);

    HTTP::enviarJSON($valor_credito);
    exit;
}

/*** Activa los campos si es un credito***/
if(isset($url_activaCampos)){
    $codigo_concepto = $url_codigo_concepto;

    if($codigo_concepto==5){
        $datos = 1;
    } else{
        $datos = 0;
    }
        
    HTTP::enviarJSON($datos);
    exit;   
}

/*** Activa los campos si es una transaccion proveedor***/
if(isset($url_activaCamposProveedorMovimiento)){
    $llave             = explode(":", $url_codigo_grupo);
    $url_codigo_grupo  = $llave[0];
    $codigo_grupo      = $url_codigo_grupo;

    if($codigo_grupo=="001"){
        $datos = 1;
    } else{
        $datos = 0;
    }
        
    HTTP::enviarJSON($datos);
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
                            
                            HTML::listaSeleccionSimple("*codigo_grupo", $textos["GRUPO_TESORERIA"], HTML::generarDatosLista("grupos_tesoreria", "codigo", "nombre_grupo"), "", array("title" => $textos["AYUDA_GRUPO_TESORERIA"],"onChange" => "verificarConceptos(), activaCamposProveedorMovimiento()")),

                            HTML::listaSeleccionSimple("*codigo_concepto", $textos["CONCEPTO_TESORERIA"], HTML::generarDatosLista("conceptos_tesoreria", "codigo", "nombre_concepto"), "", array("title" => $textos["AYUDA_CONCEPTO_TESORERIA"],"onChange" => "activaCampos()")),

                            //HTML::listaSeleccionSimple("*numero_credito", $textos["NUMERO_CREDITO"], $numero_credito, "",array("title" => $textos["AYUDA_NUMERO_CREDITO"],"class" => "oculto"))
                        ),
                    array(
                        HTML::campoTextoCorto("selector5", $textos["NUMERO_CREDITO"], 30, 30, "", array("title" => $textos["AYUDA_NUMERO_CREDITO"], "class" => " creditos oculto", "onChange" => "existeCredito(), cargarCuotasCreditos(), cargaValorCredito()")),

                        HTML::campoTextoCorto("valor_credito", $textos["VALOR_CREDITO"], 20, 20, "", array("title" => $textos["AYUDA_VALOR_CREDITO"], array("readonly" => "true"), "class" => "creditos oculto", "onBlur" => "validarItem(this)", "onkeyup"=>"formatoMiles(this)")),
                        //HTML::campoTextoCorto("selector5", $textos["NUMERO_CREDITO"], 30, 30, "", array("title" => $textos["AYUDA_NUMERO_CREDITO"],"class" => "autocompletable","onChange" => "cargarCuotasCreditos()")),

                        HTML::listaSeleccionSimple("cuotas_credito", $textos["CUOTAS_CREDITO"], "", "", array("title" => $textos["AYUDA_VALOR_CREDITO"],"class" => "creditos oculto"))
                        ),    
                    ),
                    $textos["BASICOS"]
                )    
            ),
            array(
                HTML::agrupador(
                    array(
                        array(
                            HTML::campoTextoCorto("*selector3",$textos["NUMERO_CUENTA"], 15, 15, "", array("title"=>$textos["AYUDA_NUMERO_CUENTA"],"class" => "autocompletable", "onChange"=>"cargarCuenta(), saldoCuenta(), valorCuota()")),

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
                            HTML::campoTextoCorto("*selector6",$textos["NUMERO_CUENTA"], 15, 15, "", array("title"=>$textos["AYUDA_NUMERO_CUENTA"],"class" => "autocompletable", "onChange"=>"cargarCuentas(), cuentasDiferentes()")),

                            HTML::campoTextoCorto("banco_destino", $textos["BANCO"], 20, 20, "", array("readonly" => "true"), array("title" => $textos["AYUDA_BANCO"],"onBlur" => "validarItem(this);")),

                            HTML::campoTextoCorto("tercero_destino", $textos["TERCERO"], 20, 20, "", array("readonly" => "true"), array("title" => $textos["AYUDA_TERCERO"],"onBlur" => "validarItem(this);"))
                        )
                    ),
                    $textos["CUENTA_DESTINO_PROPIA"]
                )
            ),
            array(
                HTML::agrupador(
                    array(
                        array(    
                            HTML::campoTextoCorto("*selector2", $textos["PROYECTO"], 40, 255, "", array("title" => $textos["AYUDA_PROYECTO"], "class" => "autocompletable extracto" ))
                        ),
                        array(
                            HTML::campoTextoCorto("fecha_movimiento", $textos["FECHA_MOVIMIENTO"], 10, 10, date("Y-m-d"), array("class" => "selectorFecha"),array("title" => $textos["AYUDA_FECHA_MOVIMIENTO"])),

                            HTML::campoTextoCorto("*valor", $textos["VALOR_MOVIMIENTO"], 20, 20, "", array("title" => $textos["AYUDA_VALOR_MOVIMIENTO"],"onBlur" => "validarItem(this)", "onkeyup"=>"formatoMiles(this), validarMonto()")),

                            HTML::mostrarDato("valor_de_cuota", $textos["VALOR_CUOTA"], ""),
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
                            HTML::campoTextoCorto("selector4",$textos["PROVEEDOR"], 45, 45, "", array("title"=>$textos["AYUDA_PROVEEDOR"],"class" => "oculto autocompletable", "onBlur"=>"cargarCuentaProveedor()")),

                            HTML::listaSeleccionSimple("cuenta_destino", $textos["CUENTA_DESTINO"], HTML::generarDatosLista("cuentas_bancarias_proveedores", "cuenta", "documento_identidad_proveedor","documento_identidad_proveedor = 0"), "", array("title" => $textos["AYUDA_CUENTA_DESTINO"],"class" => "oculto", "onChange" => "cargarBancoProveedor()")),

                            HTML::campoTextoCorto("banco_proveedor", $textos["BANCO"], 20, 20, "", array("title" => $textos["AYUDA_BANCO"], array("readonly" => "true"), "class" => "oculto", "onBlur" => "validarItem(this)")),
                        )
                    ),
                    $textos["CUENTA_DESTINO_PROVEEDOR"]
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

    } elseif(empty($forma_selector2)){
        $error   = true;
        $mensaje = $textos["PROYECTO_VACIO"];

    } elseif(empty($forma_valor)){
        $error   = true;
        $mensaje = $textos["VALOR_VACIO"];

    } else {
        $sentido = SQL::obtenerValor("conceptos_tesoreria","sentido","codigo='$forma_codigo_concepto'");
        
        $llave                         = explode("-", $forma_selector4);
        $documento_identidad_proveedor = $llave[0];

        /*$llave                         = explode(":", $forma_selector5);
        $numero_credito                = $llave[0];*/
        $numero_credito = $forma_selector5;

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
        $codigo_saldos_movimientos = SQL::obtenerValor("saldos_movimientos","MAX(codigo)","cuenta_origen='$forma_selector3' AND estado='0'");

        if(!$codigo_saldos_movimientos){
            $saldo_inicial  = SQL::obtenerValor("saldo_inicial_cuentas","saldo","cuenta_origen='$forma_selector3'");
            $saldo_anterior = $saldo_inicial; 
        } else{
            $saldo_anterior = SQL::obtenerValor("saldos_movimientos","saldo","codigo='$codigo_saldos_movimientos' AND estado='0'");
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
            "cuenta_destino"               => "",
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

            if($forma_selector6){
                $forma_codigo                      = $forma_codigo + 1;
                $codigo_saldos_movimientos_destino = SQL::obtenerValor("saldos_movimientos","MAX(codigo)","cuenta_origen='$forma_selector6'");
                $saldo_anterior                    = SQL::obtenerValor("saldos_movimientos","saldo","codigo='$codigo_saldos_movimientos_destino'");
                $nuevo_saldo                       = $saldo_anterior + $forma_valor;
                
                /*** Insertar datos ***/
                $datos_tesoreria = array(
                    "codigo"                       => $forma_codigo,
                    "sentido"                      => "C",
                    "codigo_proyecto"              => $forma_selector2,
                    "numero_credito"               => $numero_credito,
                    "codigo_grupo_tesoreria"       => 3,
                    "codigo_concepto_tesoreria"    => 20,
                    "cuenta_proveedor"             => $forma_cuenta_destino,
                    "cuenta_origen"                => $forma_selector3,
                    "cuenta_destino"               => $forma_selector6,
                    "valor_movimiento"             => $forma_valor,
                    "documento_identidad_tercero"  => "",
                    "fecha_registra"               => $forma_fecha_movimiento,
                    "codigo_usuario_registra"      => $forma_sesion_id_usuario_ingreso,
                    "observaciones"                => $forma_observaciones,
                    "estado"                       => 0
                );
                $insertar = SQL::insertar("movimientos_tesoreria", $datos_tesoreria);

                /*** Grabar nuevo saldo en la tabla saldos_movimientos ****/
                $datos_saldos_movimientos_destino = array(
                    "codigo_movimiento"       => $forma_codigo,
                    "cuenta_origen"           => $forma_selector6,
                    "cuenta_destino"          => "",
                    "saldo"                   => $nuevo_saldo,
                    "fecha_saldo"             => $forma_fecha_movimiento,
                    "codigo_usuario_registra" => $forma_sesion_id_usuario_ingreso,
                    "observaciones"           => $forma_observaciones
                ); 
                $insertar_saldo_destino = SQL::insertar("saldos_movimientos", $datos_saldos_movimientos_destino); 
            } 
            /*** Obtener valores del credito ***/
            /*$llave          = explode(":", $forma_selector5);
            $numero_credito = $llave[0];*/
            $numero_credito      = $forma_selector5;
            $codigo_credito      = SQL::obtenerValor("creditos_bancos","codigo","numero_credito='$numero_credito'");
            $cuota_del_credito   = SQL::obtenerValor("creditos_bancos","valor_cuota","numero_credito='$numero_credito'");
            $numero_de_cuotas    = SQL::obtenerValor("creditos_bancos","periodos","numero_credito='$numero_credito'");
            $valor_credito       = $cuota_del_credito*$numero_de_cuotas;
            $cuota_del_credito   = (int)$cuota_del_credito;
            //$valor_credito       = SQL::obtenerValor("creditos_bancos","valor_credito","numero_credito='$numero_credito'");
            $diferencia_abono_capital_pagado = 0;

            /* Obtener cuotas relacionadas con el credito */
            $consulta_cuotas = SQL::seleccionar(array("cuotas_creditos_bancos"), array("*"), "codigo_credito = '$codigo_credito' AND numero_cuota>='$forma_cuotas_credito'");
         
            if (SQL::filasDevueltas($consulta_cuotas)) {
                $valor_movimiento_cuota = (int)$forma_valor;
 
                while ($datos_cuotas = SQL::filaEnObjeto($consulta_cuotas)) {
                    
                    $existe_abono   = SQL::obtenerValor("cuotas_creditos_bancos","abono_capital_pagado","codigo_credito='$codigo_credito' AND numero_cuota='$datos_cuotas->numero_cuota'");

                    if (($forma_cuotas_credito==1) && ($existe_abono==0)){
                        $nuevo_saldo_credito = $valor_credito;

                    } else{
                        if($datos_cuotas->numero_cuota==1){
                            $cuota_anterior  = $datos_cuotas->numero_cuota;
                        } else{
                            $cuota_anterior  = $datos_cuotas->numero_cuota-1;  
                        }

                        $nuevo_saldo_credito  = SQL::obtenerValor("cuotas_creditos_bancos","saldo_capital_pagado","codigo_credito='$codigo_credito' AND numero_cuota='$cuota_anterior'");
                    }

                    if($valor_movimiento_cuota>=$datos_cuotas->abono_capital){
                        //$existe_abono = SQL::obtenerValor("cuotas_creditos_bancos","abono_capital_pagado","codigo_credito='$codigo_credito' AND numero_cuota='$datos_cuotas->numero_cuota'");

                        if($existe_abono){
                            $diferencia_abono_capital_pagado = $datos_cuotas->abono_capital-$datos_cuotas->abono_capital_pagado; 
                            
                            if($valor_movimiento_cuota>=$diferencia_abono_capital_pagado){
                                //$valor_movimiento_cuota = $diferencia_abono_capital_pagado;
                                $abono_capital_pagado   = $diferencia_abono_capital_pagado+$datos_cuotas->abono_capital_pagado; 
                                $valor_movimiento_cuota = $valor_movimiento_cuota-$diferencia_abono_capital_pagado;
                                $nuevo_saldo_credito    = $nuevo_saldo_credito-$diferencia_abono_capital_pagado-$datos_cuotas->abono_capital_pagado;
                            } else{
                                $abono_capital_pagado   = $valor_movimiento_cuota+$datos_cuotas->abono_capital_pagado;
                                $valor_movimiento_cuota = 0; 
                                $nuevo_saldo_credito    = $nuevo_saldo_credito-$valor_movimiento_cuota-$datos_cuotas->abono_capital_pagado;
                            } 
                        } else{
                            $abono_capital_pagado   = $datos_cuotas->abono_capital;
                            $valor_movimiento_cuota = $valor_movimiento_cuota-$abono_capital_pagado;
                            $nuevo_saldo_credito    = $nuevo_saldo_credito-$abono_capital_pagado;
                        }
                        //$abono_capital_pagado  = $datos_cuotas->abono_capital;
                        //$nuevo_saldo_credito = $nuevo_saldo_credito - $abono_capital_pagado;
                        $estado_cuota = "0";

                        $datos_cuotas_credito = array(
                            "codigo_credito"       => $codigo_credito,
                            "numero_cuota"         => $datos_cuotas->numero_cuota,
                            "interes_pagado"       => 0,
                            "abono_capital_pagado" => $abono_capital_pagado,
                            "saldo_capital_pagado" => $nuevo_saldo_credito,
                            "observaciones"        => "",
                            "estado_cuota"         => $estado_cuota
                        );
                        $modificar_cuotas = SQL::modificar("cuotas_creditos_bancos", $datos_cuotas_credito, "codigo_credito='$codigo_credito' AND numero_cuota='$datos_cuotas->numero_cuota'"); 
                        //$valor_movimiento_cuota = $valor_movimiento_cuota-$abono_capital_pagado;

                    } elseif(($valor_movimiento_cuota<$datos_cuotas->abono_capital) && ($valor_movimiento_cuota>0)){
                        //$existe_abono = SQL::obtenerValor("cuotas_creditos_bancos","abono_capital_pagado","codigo_credito='$codigo_credito' AND numero_cuota='$datos_cuotas->numero_cuota'");

                        if($existe_abono){
                            $diferencia_abono_capital_pagado = $datos_cuotas->abono_capital-$datos_cuotas->abono_capital_pagado; 
                            
                            if($valor_movimiento_cuota>=$diferencia_abono_capital_pagado){
                                //$valor_movimiento_cuota = $diferencia_abono_capital_pagado;
                                $abono_capital_pagado   = $diferencia_abono_capital_pagado+$datos_cuotas->abono_capital_pagado;
                                $nuevo_saldo_credito    = $nuevo_saldo_credito-$abono_capital_pagado-$datos_cuotas->abono_capital_pagado;
                                $valor_movimiento_cuota = $valor_movimiento_cuota-$diferencia_abono_capital_pagado;
                                $estado_cuota = "0";
                            } else{
                                $abono_capital_pagado = $valor_movimiento_cuota+$datos_cuotas->abono_capital_pagado;
                                $nuevo_saldo_credito  = $nuevo_saldo_credito-$abono_capital_pagado-$datos_cuotas->abono_capital_pagado;
                                $valor_movimiento_cuota = $valor_movimiento_cuota-$valor_movimiento_cuota;
                                $estado_cuota = "2";
                            }   
                        } else{
                            $abono_capital_pagado   = $valor_movimiento_cuota;
                            $nuevo_saldo_credito    = $nuevo_saldo_credito-$abono_capital_pagado;
                            $valor_movimiento_cuota = $valor_movimiento_cuota-$abono_capital_pagado;
                            $estado_cuota = "2";
                        }
                        //$nuevo_saldo_credito = $nuevo_saldo_credito - $abono_capital_pagado;
                        $datos_cuotas_credito = array(
                            "codigo_credito"       => $codigo_credito,
                            "numero_cuota"         => $datos_cuotas->numero_cuota,
                            "interes_pagado"       => 0,
                            "abono_capital_pagado" => $abono_capital_pagado,
                            "saldo_capital_pagado" => $nuevo_saldo_credito,
                            "observaciones"        => "",
                            "estado_cuota"         => $estado_cuota
                        );
                        $modificar_cuotas = SQL::modificar("cuotas_creditos_bancos", $datos_cuotas_credito, "codigo_credito='$codigo_credito' AND numero_cuota='$datos_cuotas->numero_cuota'"); 
                        //$valor_movimiento_cuota = $valor_movimiento_cuota-$abono_capital_pagado;  
                    } 
                    $forma_cuotas_credito++;
                    //$valor_movimiento_cuota = $valor_movimiento_cuota-$abono_capital_pagado;
                }    
            }
            $estado_pagado = SQL::obtenerValor("cuotas_creditos_bancos","MAX(numero_cuota)","estado_cuota!='0' AND codigo_credito='$codigo_credito'");
            
            if(!$estado_pagado){

                $datos_credito = array(
                    "estado_credito" => '0'
                );
                $modificar_credito = SQL::modificar("creditos_bancos", $datos_credito, "numero_credito='$numero_credito'");
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

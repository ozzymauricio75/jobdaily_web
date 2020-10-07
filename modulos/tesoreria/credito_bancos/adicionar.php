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
$tabla                      = "usuarios";
$columnas                   = SQL::obtenerColumnas($tabla);
$consulta                   = SQL::seleccionar(array($tabla), $columnas, "usuario = '$sesion_usuario'");
$datos                      = SQL::filaEnObjeto($consulta);
$sesion_id_usuario_ingreso  = $datos->codigo;

/*** Devolver datos para autocompletar la b�squeda ***/
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
    
         /*** Definici�n de pesta�as general ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::campoTextoCorto("*codigo", $textos["CODIGO"], 4, 4, $codigo, array("readonly" => "true"), array("title" => $textos["AYUDA_CODIGO"],"onBlur" => "validarItem(this);", "onKeyPress" => "return campoEntero(event)")),
                
                HTML::listaSeleccionSimple("*codigo_grupo", $textos["GRUPO_TESORERIA"], HTML::generarDatosLista("grupos_tesoreria", "codigo", "nombre_grupo"), "", array("title" => $textos["AYUDA_GRUPO_TESORERIA"],"onChange" => "verificarConceptos();")),

                HTML::listaSeleccionSimple("*codigo_concepto", $textos["CONCEPTO_TESORERIA"], $concepto, "")
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
        }
    }
    /*** Enviar datos con la respuesta del proceso al script que origin� la petici�n ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

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
}
/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {

    /*** Verificar que se haya enviado el ID del elemento a modificar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_MODIFICAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {
        $vistaConsulta = "movimientos_tesoreria";
        $condicion     = "codigo = '$url_id'";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, $condicion);
        $datos         = SQL::filaEnObjeto($consulta);
        $codigo        = $url_id;

        $error         = "";
        $titulo        = $componente->nombre;

        /*** Obtener valores ***/
        $grupo_tesoreria    = SQL::obtenerValor("grupos_tesoreria","nombre_grupo","codigo='$datos->codigo_grupo_tesoreria'");
        $concepto_tesoreria = SQL::obtenerValor("conceptos_tesoreria","nombre_concepto","codigo='$datos->codigo_concepto_tesoreria'");
        $sucursal           = SQL::obtenerValor("cuentas_bancarias","codigo_sucursal","numero='$datos->cuenta_origen'");
        $sucursal           = SQL::obtenerValor("sucursales","nombre","codigo='$sucursal'");
        $tipo_persona       = SQL::obtenerValor("terceros","tipo_persona","documento_identidad='$datos->documento_identidad_tercero'");
        $valor_movimiento   = number_format($datos->valor_movimiento,0);
        $proyecto           = SQL::obtenerValor("proyectos","nombre","codigo='$datos->codigo_proyecto'");
        $codigo_banco       = SQL::obtenerValor("cuentas_bancarias","codigo_banco","numero='$datos->cuenta_origen'");
        $banco              = SQL::obtenerValor("bancos","descripcion","codigo='$codigo_banco'");
        $listar_cuentas     = HTML::generarDatosLista("cuentas_bancarias_proveedores", "cuenta", "cuenta","cuenta='$datos->cuenta_proveedor'");

        if($tipo_persona==1){
            $primer_nombre    = SQL::obtenerValor("terceros", "primer_nombre", "documento_identidad = '".$datos->documento_identidad_tercero."'");
            $segundo_nombre   = SQL::obtenerValor("terceros", "segundo_nombre", "documento_identidad = '".$datos->documento_identidad_tercero."'");
            $primer_apellido  = SQL::obtenerValor("terceros", "primer_apellido", "documento_identidad = '".$datos->documento_identidad_tercero."'");
            $segundo_apellido = SQL::obtenerValor("terceros", "segundo_apellido", "documento_identidad = '".$datos->documento_identidad_tercero."'");
            $nombre_proveedor = $primer_nombre." ".$segundo_nombre." ".$primer_apellido." ".$segundo_apellido;
        } else{
            $nombre_proveedor  = SQL::obtenerValor("terceros", "razon_social", "documento_identidad = '".$datos->documento_identidad_tercero."'"); 
        }

        /*** Definici�n de pesta�as general ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::campoTextoCorto("*codigo", $textos["CODIGO"], 9, 9, $codigo, array("readonly" => "true"), array("title" => $textos["AYUDA_CODIGO"],"onBlur" => "validarItem(this);", "onKeyPress" => "return campoEntero(event)")),

                HTML::mostrarDato("nombre_grupo", $textos["GRUPO_TESORERIA"], $grupo_tesoreria),

                HTML::mostrarDato("nombre_concepto", $textos["CONCEPTO_TESORERIA"], $concepto_tesoreria)
            ),
            array(
                HTML::agrupador(
                    array(
                        array(
                            HTML::campoTextoCorto("*selector3",$textos["NUMERO_CUENTA"], 15, 15, $datos->cuenta_origen, array("title"=>$textos["AYUDA_NUMERO_CUENTA"],"class" => "autocompletable", "onChange"=>"cargarCuenta()")),

                            HTML::campoTextoCorto("banco", $textos["BANCO"], 20, 20, $banco, array("readonly" => "true"), array("title" => $textos["AYUDA_BANCO"],"onBlur" => "validarItem(this);")),

                            HTML::campoTextoCorto("tercero", $textos["TERCERO"], 20, 20, $sucursal, array("readonly" => "true"), array("title" => $textos["AYUDA_TERCERO"],"onBlur" => "validarItem(this);"))
                        )
                    ),
                    $textos["CUENTA_ORIGEN"]
                )
            ),
            array(
                HTML::agrupador(
                    array(
                        array(    
                            HTML::campoTextoCorto("selector2", $textos["PROYECTO"], 40, 255, $proyecto, array("title" => $textos["AYUDA_PROYECTO"], "class" => "autocompletable extracto" ))
                        ),
                        array(
                            HTML::campoTextoCorto("fecha_movimiento", $textos["FECHA_MOVIMIENTO"], 10, 10, $datos->fecha_registra, array("class" => "selectorFecha"),array("title" => $textos["AYUDA_FECHA_MOVIMIENTO"])),

                            HTML::campoTextoCorto("*valor", $textos["VALOR_MOVIMIENTO"], 20, 20, number_format($datos->valor_movimiento,0,',','.'), array("title" => $textos["AYUDA_VALOR_MOVIMIENTO"],"onBlur" => "validarItem(this)", "onkeyup"=>"formatoMiles(this), valorSaldo(this)"))
                            .HTML::campoOculto("valor_movimiento_anterior", $datos->valor_movimiento)
                        ),
                        array(
                            HTML::campoTextoCorto("observaciones", $textos["OBSERVACIONES"], 75, 254, $datos->observaciones, array("title" => $textos["AYUDA_OBSERVACIONES"]))
                        )
                    ),
                    $textos["DATOS_MOVIMIENTO"]
                )
            ),
            array(
                HTML::agrupador(
                    array(
                        array(
                            HTML::campoTextoCorto("selector4",$textos["PROVEEDOR"], 45, 45, $nombre_proveedor, array("title"=>$textos["AYUDA_PROVEEDOR"],"class" => "autocompletable", "onBlur"=>"cargarCuentaProveedor()")),

                             HTML::listaSeleccionSimple("cuenta_destino", $textos["CUENTA_DESTINO"], $listar_cuentas, $datos->cuenta_proveedor, array("title" => $textos["AYUDA_CUENTA_DESTINO"]))
                        )
                    ),
                    $textos["CUENTA_DESTINO"]
                )
            )
        );

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

/*** Modificar el elemento seleccionado ***/
} elseif (!empty($forma_procesar)) {

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

        $forma_valor      = quitarMiles($forma_valor);
        $cuenta_proveedor = SQL::obtenerValor("cuentas_bancarias_proveedores","documento_identidad_proveedor","cuenta='$forma_cuenta_destino'");
        $proyecto         = SQL::obtenerValor("proyectos","codigo","nombre='$forma_selector2'");
        
        if($proyecto){
            $forma_selector2 = $proyecto;
        }
        if($cuenta_proveedor){
            $documento_identidad_proveedor = $cuenta_proveedor;
        }

        /*** Verificar saldos movimiento ****/
        $valor_movimiento_anterior = SQL::obtenerValor("movimientos_tesoreria","valor_movimiento","codigo='$forma_codigo'");
        $saldos_movimientos        = SQL::obtenerValor("saldos_movimientos","saldo","codigo_movimiento='$forma_codigo'");

        if($valor_movimiento_anterior>$forma_valor){
            $diferencia  = $valor_movimiento_anterior - $forma_valor;
            $nuevo_saldo = $saldos_movimientos + $diferencia;
        } else{
            $diferencia  = $forma_valor - $valor_movimiento_anterior;
            $nuevo_saldo = $saldos_movimientos - $diferencia;
        }

        /*** Insertar datos ***/
        $datos = array(
            "codigo_proyecto"              => $forma_selector2,
            "cuenta_proveedor"             => $forma_cuenta_destino,
            "cuenta_origen"                => $forma_selector3,
            "valor_movimiento"             => $forma_valor,
            "documento_identidad_tercero"  => $documento_identidad_proveedor,
            "fecha_registra"               => $forma_fecha_movimiento,
            "codigo_usuario_registra"      => $forma_sesion_id_usuario_ingreso,
            "observaciones"                => $forma_observaciones
        );

        $consulta = SQL::modificar("movimientos_tesoreria", $datos, "codigo='$forma_codigo'");
		
		/*** Error inserci�n ***/
        if ($consulta) {
            /*** Grabar nuevo saldo en la tabla saldos_movimientos ****/
            $datos_saldos_movimientos = array(
                "codigo_movimiento"       => $forma_codigo,
                "cuenta_origen"           => $forma_selector3,
                "saldo"                   => $nuevo_saldo,
                "fecha_saldo"             => $forma_fecha_movimiento,
                "codigo_usuario_registra" => $forma_sesion_id_usuario_ingreso,
                "observaciones"           => $forma_observaciones
            );
            $modificar_saldo = SQL::modificar("saldos_movimientos", $datos_saldos_movimientos, "codigo_movimiento='$forma_codigo'"); 
            
            $error   = false;
            $mensaje = $textos["ITEM_MODIFICADO"];
        } else{
            $error   = true;
            $mensaje = $textos["ERROR_MODIFICAR_ITEM"];    
        }
    }
    /*** Enviar datos con la respuesta del proceso al script que origin� la petici�n ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

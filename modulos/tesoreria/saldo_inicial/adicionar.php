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
        echo SQL::datosAutoCompletar("menu_cuentas_bancarias", $url_q);
    }
    exit;

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
}
/*** Generar el formulario para la captura de datos ***/
elseif (!empty($url_generar)) {
    $error    = "";
    $titulo   = $componente->nombre;
    
    $consulta_cuentas_bancarias = SQL::seleccionar(array("cuentas_bancarias"),array("*"),"codigo_banco>0");
    if (SQL::filasDevueltas($consulta_cuentas_bancarias)){

        //Asignar codigo siguiente de la tabla 
        $codigo = SQL::obtenerValor("saldo_inicial_cuentas","MAX(codigo)","codigo>0");

        if ($codigo){
            $codigo++;
        } else {
            $codigo = 1;
        }
         /*** Definición de pestañas general ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::campoTextoCorto("*codigo", $textos["CODIGO"], 4, 4, $codigo, array("readonly" => "true"), array("title" => $textos["AYUDA_CODIGO"],"onBlur" => "validarItem(this);", "onKeyPress" => "return campoEntero(event)"))
            ),
            array(
                HTML::campoTextoCorto("*selector1",$textos["NUMERO_CUENTA"], 15, 15, "", array("title"=>$textos["AYUDA_NUMERO_CUENTA"],"class" => "autocompletable", "onBlur"=>"cargarCuenta()")),

                HTML::campoTextoCorto("banco", $textos["BANCO"], 20, 20, "", array("readonly" => "true"), array("title" => $textos["AYUDA_BANCO"],"onBlur" => "validarItem(this);")),

                HTML::campoTextoCorto("tercero", $textos["TERCERO"], 20, 20, "", array("readonly" => "true"), array("title" => $textos["AYUDA_TERCERO"],"onBlur" => "validarItem(this);"))
            ),
            array(    
                HTML::campoTextoCorto("*saldo", $textos["SALDO_INICIAL"], 20, 20, "", array("title" => $textos["AYUDA_SALDO_INICIAL"],"onBlur" => "validarItem(this)", "onkeyup"=>"formatoMiles(this)")),

                HTML::campoTextoCorto("observaciones", $textos["OBSERVACIONES"], 20, 20, "", array("title" => $textos["AYUDA_OBSERVACIONES"]))
            ),
            array(
                HTML::campoTextoCorto("fecha_saldo", $textos["FECHA_SALDO"], 10, 10, date("Y-m-d"), array("class" => "selectorFecha"),array("title" => $textos["AYUDA_FECHA_SALDO"]))
            )
        );

        /*** Definicion de botones ***/
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones);
    } else {
        $contenido = "";
        $error     = $textos["CREAR_CUENTAS_BANCO"];
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
        $existe = SQL::existeItem("saldo_inicial_cuentas", "codigo", $url_valor, "codigo != '0'");

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

    }elseif(empty($forma_selector1)){
        $error   = true;
        $mensaje = $textos["NUMERO_VACIO"];

    }elseif(SQL::existeItem("saldo_inicial_cuentas", "codigo", $forma_codigo,"codigo !=''")){
        $error   = true;
        $mensaje = $textos["ERROR_EXISTE_CODIGO"];

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

        $forma_saldo     = quitarMiles($forma_saldo);
        $forma_selector1 = quitarMiles($forma_selector1);
        
        /*** Insertar datos ***/
        $datos = array(
            "codigo"                  => $forma_codigo,
            "cuenta_origen"           => $forma_selector1,
            "saldo"                   => $forma_saldo,
            "fecha_saldo"             => $forma_fecha_saldo,
            "fecha_registra"          => date("Y-m-d H:i:s"),
            "codigo_usuario_registra" => $sesion_id_usuario_ingreso,
            "observaciones"           => $forma_observaciones
        );
        $insertar = SQL::insertar("saldo_inicial_cuentas", $datos);

        /*** Error de insercion ***/
        if (!$insertar) {
            $error   = true;
            $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
        }
    }
    /*** Enviar datos con la respuesta del proceso al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

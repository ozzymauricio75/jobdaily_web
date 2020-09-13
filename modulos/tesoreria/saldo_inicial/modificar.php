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

if (isset($url_completar)) {
    if (($url_item) == "selector1") {
        echo SQL::datosAutoCompletar("menu_cuentas_bancarias", $url_q);
    }
    exit;

/*** Generar el formulario para la captura de datos ***/
}elseif (!empty($url_generar)) {

    /*** Verificar que se haya enviado el ID del elemento a modificar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_MODIFICAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {
        $vistaConsulta = "saldo_inicial_cuentas";
        $condicion     = "codigo = '$url_id'";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, $condicion);
        $datos         = SQL::filaEnObjeto($consulta);
        $saldo         = number_format($datos->saldo,0);

        $error         = "";
        $titulo        = $componente->nombre;

        /*** Obtener valores ***/
        $consulta_cuentas_bancarias = SQL::seleccionar(array("cuentas_bancarias"), array("*"), "numero='$datos->cuenta_origen'");
        $datos_cuenta               = SQL::filaEnObjeto($consulta_cuentas_bancarias);

        $nombre_banco = SQL::obtenerValor("bancos","descripcion","codigo='$datos_cuenta->codigo_banco'");
        $tercero      = SQL::obtenerValor("sucursales","nombre","codigo='$datos_cuenta->codigo_sucursal'");

        /*** Definición de pestañas general ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::mostrarDato("codigo", $textos["CODIGO"], $datos->codigo)
            ),
            array(
                HTML::mostrarDato("numero", $textos["NUMERO_CUENTA"], $datos->cuenta_origen)
            ),
            array(
                HTML::mostrarDato("banco", $textos["BANCO"], $nombre_banco)
            ),
            array(
                HTML::mostrarDato("tercero", $textos["TERCERO"], $tercero)  
            ),
            array(    
                HTML::campoTextoCorto("*saldo", $textos["SALDO_INICIAL"], 20, 20, $saldo, array("title" => $textos["AYUDA_SALDO_INICIAL"],"onBlur" => "validarItem(this)", "onkeyup"=>"formatoMiles(this)")),

                HTML::campoTextoCorto("observaciones", $textos["OBSERVACIONES"], 20, 20, $datos->observaciones, array("title" => $textos["AYUDA_OBSERVACIONES"]))
            ),
            array(
                HTML::campoTextoCorto("fecha_saldo", $textos["FECHA_SALDO"], 10, 10, $datos->fecha_saldo, array("class" => "selectorFecha"),array("title" => $textos["AYUDA_FECHA_SALDO"]))
            )
        );

        /*** Definición de botones ***/
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "modificarItem('$url_id');", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones);
    }

    /*** Enviar datos para la generación del formulario al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

/*** Validar los datos provenientes del formulario ***/
} elseif (!empty($url_validar)) {

    $respuesta = "";

    /*** Validar codigo ***/
    if ($url_item == "codigo" && $url_valor) {
        $existe = SQL::existeItem("conceptos_tesoreria", "codigo", $url_valor, "codigo != '$url_id' AND codigo !=''");

        if ($existe) {
            $respuesta = $textos["ERROR_EXISTE_CODIGO"];
        }
    }
    
    HTTP::enviarJSON($respuesta);

/*** Modificar el elemento seleccionado ***/
} elseif (!empty($forma_procesar)) {

    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_MODIFICADO"];

    /*** Validar el ingreso de los datos requeridos ***/
    if(empty($forma_saldo)){
        $error   = true;
        $mensaje = $textos["SALDO_VACIO"];

    }elseif(empty($forma_fecha_saldo)){
        $error   = true;
        $mensaje = $textos["FECHA_VACIO"];

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
        
        $forma_saldo = quitarMiles($forma_saldo);
        $forma_saldo = str_replace(",", "", $forma_saldo);

        /*** Insertar datos ***/
        $datos = array(
            "saldo"                   => $forma_saldo,
            "fecha_saldo"             => $forma_fecha_saldo,
            "fecha_registra"          => date("Y-m-d H:i:s"),
            "codigo_usuario_registra" => $sesion_id_usuario_ingreso,
            "observaciones"           => $forma_observaciones
        );

        $consulta = SQL::modificar("saldo_inicial_cuentas", $datos, "codigo = '$forma_id'");
		
		/*** Error inserción ***/
        if ($consulta) {
            $error   = false;
            $mensaje = $textos["ITEM_MODIFICADO"];
        } else {
            $error   = true;
            $mensaje = $textos["ERROR_MODIFICAR_ITEM"];
        }
    }
    /*** Enviar datos con la respuesta del proceso al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

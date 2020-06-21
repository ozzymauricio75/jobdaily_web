<?php

/**
*
* Copyright (C) 2020 Raul Mauricio Oidor Lozano
* Raul Mauricio Oidor Lozano <ozzymauricio75@gmail.com>
*
* Este archivo es parte de:
* PANCE :: Software empresarial a la medida
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
        echo SQL::datosAutoCompletar("seleccion_localidades", $url_q);
    }
    if (($url_item) == "selector3") {
        echo SQL::datosAutoCompletar("seleccion_proveedores", $url_q);
    }
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
        $vistaConsulta = "vendedores_proveedor";
        $condicion     = "codigo = '$url_id'";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);

        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo = '$url_id'");
        $datos         = SQL::filaEnObjeto($consulta);
        $error         = "";
        $titulo        = $componente->nombre;

        $razon_social  = SQL::obtenerValor("terceros", "razon_social", "documento_identidad = '$datos->documento_proveedor'");
        $codigo        = $url_id;
 
        if ($datos->tipo_persona  == 1){
            $valor_persona_natural    = true;
            $valor_oculto_natural     = "";
            $valor_oculto_juridica    = "oculto";
            $valor_persona_juridica   = false;
            $valor_codigo_interno     = false;

        }elseif($datos->tipo_persona == 2){
            $valor_persona_natural    = false;
            $valor_persona_juridica   = true;
            $valor_oculto_natural     = "oculto";
            $valor_oculto_juridica    = "";
            $valor_codigo_interno     = false;

        }else{
            $valor_persona_natural    = false;
            $valor_persona_juridica   = false;
            $valor_codigo_interno     = true;
            $valor_oculto_natural     = "oculto";
            $valor_oculto_juridica    = "";
        }

        $regimen = array(
            "1" => $textos["REGIMEN_COMUN"],
            "2" => $textos["REGIMEN_SIMPLIFICADO"]
        );

        $tipo_persona = array(
            "1" => $textos["NATURAL"],
            "2" => $textos["JURIDICA"],
            "3" => $textos["INTERNO"]
        );

        $genero = array(
            "M" => $textos["MASCULINO"],
            "F" => $textos["FEMENINO"],
            "N" => $textos["NO_APLICA"],
        );

        $activo = array(
            "0" => $textos["INACTIVO"],
            "1" => $textos["ACTIVO"]
        );

        /*** Definición de pestañas para datos del tercero***/
        $formularios["PESTANA_VENDEDOR"] = array(
            array(
                HTML::mostrarDato("nit_proveedor", $textos["PROVEEDOR"], $datos->documento_proveedor),
                HTML::mostrarDato("razon_social", $textos["PROVEEDOR"], $razon_social)
                .HTML::campoOculto("codigo", $codigo)
            ),
            array(
                HTML::campoTextoCorto("*primer_nombre", $textos["PRIMER_NOMBRE"], 15, 15, $datos->primer_nombre, array("title" => $textos["AYUDA_PRIMER_NOMBRE"], "onblur" => "validarItem(this)", "")),

                HTML::campoTextoCorto("segundo_nombre", $textos["SEGUNDO_NOMBRE"], 15, 15, $datos->segundo_nombre, array("title" => $textos["AYUDA_SEGUNDO_NOMBRE"], "onblur" => "validarItem(this)", ""))
            ),
            array(
                HTML::campoTextoCorto("*primer_apellido", $textos["PRIMER_APELLIDO"], 15, 15, $datos->primer_apellido, array("title" => $textos["AYUDA_PRIMER_APELLIDO"], "onblur" => "validarItem(this)", "")),

                HTML::campoTextoCorto("segundo_apellido", $textos["SEGUNDO_APELLIDO"], 15, 15, $datos->segundo_apellido, array("title" => $textos["AYUDA_SEGUNDO_APELLIDO"], "onblur" => "validarItem(this)", ""))
            ),
            array(
                HTML::campoTextoCorto("correo", $textos["CORREO"], 50, 255, $datos->correo, array("title" => $textos["AYUDA_CORREO"]))
            ),
            array(
                HTML::campoTextoCorto("celular", $textos["CELULAR"], 20, 20, $datos->celular, array("title" => $textos["AYUDA_CELULAR"]))
            ),
            array(
                HTML::listaSeleccionSimple("*activo", $textos["ESTADO"], $activo, $datos->activo, array("title" => $textos["AYUDA_ESTADO"]))
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
//} elseif (!empty($url_validar)) {

/*** Modificar el elemento seleccionado ***/
} elseif (!empty($forma_procesar)) {
    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_MODIFICADO"];

    
    if (empty($forma_primer_nombre) && empty($forma_primer_apellido) || 
        (!empty($forma_primer_nombre) && empty($forma_primer_apellido))) {
        
        $error   = true;
        $mensaje = $textos["ERROR_DATOS_INCOMPLETOS"];

    } else {
        
        $datos = array(
            "primer_nombre"        => $forma_primer_nombre,
            "segundo_nombre"       => $forma_segundo_nombre,
            "primer_apellido"      => $forma_primer_apellido,
            "segundo_apellido"     => $forma_segundo_apellido,
            "celular"              => $forma_celular,
            "correo"               => $forma_correo,
            "activo"               => $forma_activo,
            "id_usuario_registra"  => $sesion_id_usuario_ingreso,
            "fecha_modificacion"   => date("Y-m-d H:i:s")
        );

        $consulta = SQL::modificar("vendedores_proveedor", $datos, "codigo = '$forma_codigo'");

        if (!$consulta) {
            $error   = false;
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


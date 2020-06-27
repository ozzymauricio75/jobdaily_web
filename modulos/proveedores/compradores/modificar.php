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
        $vistaConsulta = "terceros";
        $condicion     = "documento_identidad = '$url_id'";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);

        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "documento_identidad = '$url_id'");
        $datos         = SQL::filaEnObjeto($consulta);
        $error         = "";
        $titulo        = $componente->nombre;

        $codigo_empresa = SQL::obtenerValor("compradores", "codigo_empresa", "documento_identidad = '$url_id'");
        $razon_social   = SQL::obtenerValor("empresas", "razon_social", "codigo = '$codigo_empresa'");
        
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
    

        /*** Definición de pestañas para datos del tercero***/
        $formularios["PESTANA_COMPRADOR"] = array(
            array(
                HTML::listaSeleccionSimple("*empresa", $textos["EMPRESA"], HTML::generarDatosLista("empresas", "codigo", "razon_social","codigo != 0 AND razon_social='$razon_social'"), "", array("readonly" => "true"), array("title" => $textos["AYUDA_EMPRESAS"],""))
            ),
            array(
                HTML::campoTextoCorto("*documento_identidad", $textos["DOCUMENTO_COMPRADOR"], 15, 15, $datos->documento_identidad,array("title" => $textos["AYUDA_DOCUMENTO_PROVEEDOR"],"onblur" => "validarItem(this);"))
            ),
            array(
                HTML::campoTextoCorto("*primer_nombre", $textos["PRIMER_NOMBRE"], 15, 15, $datos->primer_nombre, array("title" => $textos["AYUDA_PRIMER_NOMBRE"], "onblur" => "validarItem(this)", "class" => "$valor_oculto_natural")),
                HTML::campoTextoCorto("segundo_nombre", $textos["SEGUNDO_NOMBRE"], 15, 15, $datos->segundo_nombre, array("title" => $textos["AYUDA_SEGUNDO_NOMBRE"], "onblur" => "validarItem(this)", "class" => "$valor_oculto_natural"))
            ),
            array(
                HTML::campoTextoCorto("*primer_apellido", $textos["PRIMER_APELLIDO"], 15, 15, $datos->primer_apellido, array("title" => $textos["AYUDA_PRIMER_APELLIDO"], "onblur" => "validarItem(this)", "class" => "$valor_oculto_natural")),
                HTML::campoTextoCorto("segundo_apellido", $textos["SEGUNDO_APELLIDO"], 15, 15, $datos->segundo_apellido, array("title" => $textos["AYUDA_SEGUNDO_APELLIDO"], "onblur" => "validarItem(this)", "class" => "$valor_oculto_natural"))
            ),
            array(
                HTML::campoTextoCorto("correo", $textos["CORREO"], 50, 255, $datos->correo, array("title" => $textos["AYUDA_CORREO"]))
            ),
            array(
                HTML::campoTextoCorto("celular", $textos["CELULAR"], 20, 20, $datos->celular, array("title" => $textos["AYUDA_CELULAR"]))
            ),
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

    
    if (empty($forma_documento_identidad) || (
        empty($forma_primer_nombre) && 
        empty($forma_primer_apellido) || 
        (!empty($forma_primer_nombre) && empty($forma_primer_apellido)))) {
        $error   = true;
        $mensaje = $textos["ERROR_DATOS_INCOMPLETOS"];

    } else {
        
        $datos = array(
            "documento_identidad"                => $forma_documento_identidad,
            
            "codigo_tipo_documento"              => '1',
            "codigo_iso_municipio_documento"     => "CO",
            "codigo_dane_departamento_documento" => '76',
            "codigo_dane_municipio_documento"    => '001',
            "tipo_persona"                       => '1',
            "primer_nombre"                      => $forma_primer_nombre,
            "segundo_nombre"                     => $forma_segundo_nombre,
            "primer_apellido"                    => $forma_primer_apellido,
            "segundo_apellido"                   => $forma_segundo_apellido,
            "codigo_iso_localidad"               => "CO",
            "codigo_dane_departamento_localidad" => '76',
            "codigo_dane_municipio_localidad"    => '001',
            "codigo_dane_localidad"              => 'EL',
            "tipo_localidad"                     => 'B',
            "fecha_ingreso"                      => date("Y-m-d H:i:s"),
            "direccion_principal"                => "",
            "telefono_principal"                 => "",
            "fax"                                => "",
            "celular"                            => $forma_celular,
            "genero"                             => 'N',
            "sitio_web"                          => "",
            "correo"                             => $forma_correo,
            "comprador"                          => '1'
        );

        $consulta = SQL::modificar("terceros", $datos, "documento_identidad = '$forma_documento_identidad'");

        if ($consulta) {
            $error   = false;
            $mensaje = $textos["ITEM_MODIFICADO"];
            
            $tabla      = "terceros";
            $columnas   = SQL::obtenerColumnas($tabla);
            $consulta   = SQL::seleccionar(array($tabla), $columnas, "documento_identidad = '$forma_documento_identidad'");

            $datos      = SQL::filaEnObjeto($consulta);
            $id_tercero = $datos->documento_identidad;

            $datos_compradores = array(
                "documento_identidad" => $documento_tercero,
                "activo"              => "1",
                "id_usuario_registra" => $sesion_id_usuario_ingreso,
                "fecha_registra"      => date("Y-m-d H:i:s"),
                "fecha_modificacion"  => date("Y-m-d H:i:s")
            );

            $consulta_comprador = SQL::modificar("compradores", $datos_compradores, "documento_identidad = '$id_tercero'");

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


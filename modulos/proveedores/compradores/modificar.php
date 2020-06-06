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
$tabla = "usuarios";
$columnas                   = SQL::obtenerColumnas($tabla);
$consulta                   = SQL::seleccionar(array($tabla), $columnas, "usuario = '$sesion_usuario'");
$datos                      = SQL::filaEnObjeto($consulta);
$sesion_id_usuario_ingreso  = $datos->id;

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
/*** Devolver datos para cargar los elementos del formulario relacionados con el documento del cliente digitado***/
if (isset($url_recargar)) {
    if (!empty($url_origen)){
        $respuesta = HTML::generarDatosLista("localidades","id","nombre","id_municipio = '$url_origen'");    
        HTTP::enviarJSON($respuesta);
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
        $condicion     = "id = '$url_id'";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "id = '$url_id'");
        $datos         = SQL::filaEnObjeto($consulta);
        $error         = "";
        $titulo        = $componente->nombre;
        
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
        
        $tipo_documento_identidad       = SQL::obtenerValor("tipos_documento_identidad", "descripcion", "id = '$datos->id_tipo_documento'");
        $nombre_municipio_documento     = SQL::obtenerValor("municipios", "nombre", "id = '$datos->id_municipio_documento'");
        $departamento_documento         = SQL::obtenerValor("municipios", "id_departamento", "id = '$datos->id_municipio_documento'");
        $nombre_departamento_documento  = SQL::obtenerValor("departamentos", "nombre", "id = '$departamento_documento'");
        $pais_documento                 = SQL::obtenerValor("departamentos", "id_pais", "id = '$departamento_documento'");
        $nombre_pais_documento          = SQL::obtenerValor("paises", "nombre", "id = '$pais_documento'");
        $nombre_localidad_residencia    = SQL::obtenerValor("localidades", "nombre", "id = '$datos->id_municipio_residencia'");
        $municipio_residencia           = SQL::obtenerValor("localidades", "id_municipio", "id = '$datos->id_municipio_residencia'");
        $nombre_municipio_residencia    = SQL::obtenerValor("municipios", "nombre", "id = '$municipio_residencia'");
        $departamento_residencia        = SQL::obtenerValor("municipios", "id_departamento", "id = '$municipio_residencia'");
        $nombre_departamento_residencia = SQL::obtenerValor("departamentos", "nombre", "id = '$departamento_residencia'");
        $pais_residencia                = SQL::obtenerValor("departamentos", "id_pais", "id = '$departamento_residencia'");
        $nombre_pais_residencia         = SQL::obtenerValor("paises", "nombre", "id = '$pais_residencia'");
        
        $nombre_municipio_documento     = SQL::obtenerValor("seleccion_municipios", "nombre", "id = '$datos->id_municipio_documento'");
        $nombre_municipio_documento     = explode("|", $nombre_municipio_documento);
        $nombre_municipio_documento     = $nombre_municipio_documento[0];
        $barrios_localidades = HTML::generarDatosLista("localidades", "id", "nombre");

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
                HTML::campoTextoCorto("*documento_identidad", $textos["DOCUMENTO_PROVEEDOR"], 15, 15, $datos->documento_identidad,array("title" => $textos["AYUDA_DOCUMENTO_PROVEEDOR"],"onblur" => "validarItem(this);"))
            ),
            array(                
                HTML::listaSeleccionSimple("*id_tipo_documento", $textos["TIPO_DOCUMENTO_IDENTIDAD"], HTML::generarDatosLista("tipos_documento_identidad", "id", "descripcion"), $datos->id_tipo_documento)
            ),
            array(
                HTML::marcaSeleccion("tipo_persona", $textos["PERSONA_NATURAL"], 1, $valor_persona_natural, array("id" => "persona_natural", "onChange" => "activarNombres(1)")),
                HTML::marcaSeleccion("tipo_persona", $textos["PERSONA_JURIDICA"], 2, $valor_persona_juridica, array("id" => "persona_juridica", "onChange" => "activarNombres(2)")),
                HTML::marcaSeleccion("tipo_persona", $textos["CODIGO_INTERNO"], 3, $valor_codigo_interno, array("id" => "codigo_interno", "onChange" => "activarNombres(3)"))
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
                HTML::campoTextoCorto("*fecha_ingreso", $textos["FECHA_INGRESO"], 10, 10, substr($datos->fecha_ingreso, 0, 10), array("class" => "selectorFecha"), array("title" => $textos["AYUDA_FECHA_INGRESO"],"onBlur" => "validarItem(this);"))
            )
        );

        /*** Definición de pestañas para la ubicación del tercero***/
        $formularios["PESTANA_UBICACION_COMPRADOR"] = array(
            array(
                HTML::campoTextoCorto("*selector1", $textos["MUNICIPIO"], 40, 255, $nombre_municipio_documento, array("title" => $textos["AYUDA_DOCUMENTO_MUNICIPIO"], "class" => "autocompletable", "onblur" => "recargarLista('id_municipio_documento','id_municipio_residencia');")).HTML::campoOculto("id_municipio_documento", $datos->id_municipio_documento)
            ),
            array(
                HTML::listaSeleccionSimple("*id_municipio_residencia", $textos["AYUDA_DOCUMENTO_MUNICIPIO"], $barrios_localidades, $datos->id_municipio_residencia, array("title" => $textos["AYUDA_DEPARTAMENTO"], "onChange" => "recargarLista('departamento','municipio');"))
            ),
            array(
                HTML::campoTextoCorto("*direccion_principal", $textos["DIRECCION"], 50, 50, $datos->direccion_principal, array("title" => $textos["AYUDA_DIRECCION"]))
            ),
            array(
                HTML::campoTextoCorto("telefono_principal", $textos["TELEFONO_PRINCIPAL"], 15, 15, $datos->telefono_principal, array("title" => $textos["AYUDA_TELEFONO_PRINCIPAL"])),
                HTML::campoTextoCorto("fax", $textos["FAX"], 15, 15, $datos->fax, array("title" => $textos["AYUDA_FAX"])),
                HTML::campoTextoCorto("celular", $textos["CELULAR"], 20, 20, $datos->celular, array("title" => $textos["AYUDA_CELULAR"]))
            ),
            array(
                HTML::campoTextoCorto("correo", $textos["CORREO"], 50, 255, $datos->correo, array("title" => $textos["AYUDA_CORREO"]))
            ),
            array(
                HTML::campoTextoCorto("sitio_web", $textos["SITIO_WEB"], 50, 50, $datos->sitio_web, array("title" => $textos["AYUDA_SITIO_WEB"]))
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

    
    if (empty($forma_documento_identidad) ||
        empty($forma_tipo_persona) ||
        empty($forma_id_municipio_documento) ||
        empty($forma_id_municipio_residencia) || 
        empty($forma_direccion_principal) || 
        empty($forma_id_tipo_documento) || (
        empty($forma_primer_nombre) && 
        empty($forma_primer_apellido) || 
        (!empty($forma_primer_nombre) && empty($forma_primer_apellido)))) {
        $error   = true;
        $mensaje = $textos["ERROR_DATOS_INCOMPLETOS"];

    } else {

        
        if ($forma_tipo_persona == 1){
            $forma_razon_social = "";
        }elseif($forma_tipo_persona == 2 || $forma_tipo_persona == 3){
            $forma_primer_nombre    = "";
            $forma_segundo_nombre   = "";
            $forma_primer_apellido  = "";
            $forma_segundo_apellido = "";
        } 
        
        $datos = array(
            "documento_identidad"     => $forma_documento_identidad,
            "tipo_persona"            => $forma_tipo_persona,
            "id_tipo_documento"       => $forma_id_tipo_documento,
            "primer_nombre"           => $forma_primer_nombre,
            "segundo_nombre"          => $forma_segundo_nombre,
            "primer_apellido"         => $forma_primer_apellido,
            "segundo_apellido"        => $forma_segundo_apellido,
            "fecha_ingreso"           => $forma_fecha_ingreso,
            "id_municipio_documento"  => $forma_id_municipio_documento,            
            "id_municipio_residencia" => $forma_id_municipio_residencia,
            "direccion_principal"     => $forma_direccion_principal,
            "telefono_principal"      => $forma_telefono_principal,
            "fax"                     => $forma_fax,
            "celular"                 => $forma_celular,
            "correo"                  => $forma_correo,
            "sitio_web"               => $forma_sitio_web
        );

        $consulta = SQL::modificar("terceros", $datos, "id = '$forma_id'");
        
        if ($consulta) {
            $error   = false;
            $mensaje = $textos["ITEM_MODIFICADO"];
            
            $tabla = "terceros";
            $columnas                 = SQL::obtenerColumnas($tabla);
            $consulta                 = SQL::seleccionar(array($tabla), $columnas, "documento_identidad = '$forma_documento_identidad'");
            $datos                    = SQL::filaEnObjeto($consulta);
            $id_tercero               = $datos->id;

            $datos_compradores = array(
                "activo"              => "1",
                "id_usuario_registra" => $sesion_id_usuario_ingreso,
                "fecha_registra"      => date("Y-m-d H:i:s"),
                "fecha_modificacion"  => date("Y-m-d H:i:s")
            );

            $consulta_comprador       = SQL::modificar("compradores", $datos_compradores, "id_tercero = '$id_tercero'");

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


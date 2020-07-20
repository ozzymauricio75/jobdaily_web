<?php

/**
*
* Copyright (C) 2020 Raul Mauricio Oidor Lozano
* Mauricio Oidor L. <ozzymauricio75@gmail.com>
*
* Este archivo es parte de:
* Jobdaily :: Software empresarial a la medida
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
/*** Devolver datos para cargar los elementos del formulario relacionados con el documento del cliente digitado***/
if (isset($url_recargar)) {


if (!empty($url_documento_identidad_carga)) {

        $consulta = SQL::seleccionar(array("terceros"), array("*"), "documento_identidad = '$url_documento_identidad_carga'", "", "documento_identidad", 1);
        $tabla = array();

        if (SQL::filasDevueltas($consulta)) {
            $datos = SQL::filaEnObjeto($consulta);
            $tabla = array(
                //$datos->documento_identidad,
                $datos->primer_nombre,
                $datos->segundo_nombre,
                $datos->primer_apellido,
                $datos->segundo_apellido,
                $datos->correo,
                $datos->celular

            );
        } else {
            $tabla[] = "";
        }
        HTTP::enviarJSON($tabla);
    }
    exit;
}

if (isset($url_recargarMunicipioDocumento)){

    if(!empty($url_municipio_documento)){

        $consulta = SQL::seleccionar(array("seleccion_municipios"), array("nombre"), "id = '$url_municipio_documento'", "", "nombre", 1);

        if (SQL::filasDevueltas($consulta)) {
            $datos = SQL::filaEnObjeto($consulta);
            $nombre_municipio_documento  = $datos->nombre;
            $nombre_municipio_documento  = explode("|", $nombre_municipio_documento);
            $nombre_municipio_documento  = $nombre_municipio_documento[0];
        }else {
            $nombre_municipio_documento = "";
        }
        HTTP::enviarJSON($nombre_municipio_documento);
    }
    exit;
}

if (isset($url_recargarMunicipioResidencia)){

    if(!empty($url_municipio_residencia)){

        $consulta = SQL::seleccionar(array("seleccion_localidades"), array("nombre"), "id = '$url_municipio_residencia'", "", "nombre", 1);

        if (SQL::filasDevueltas($consulta)) {
            $datos = SQL::filaEnObjeto($consulta);
            $nombre_municipio_residencia  = $datos->nombre;
            $nombre_municipio_residencia  = explode("|", $nombre_municipio_residencia);
            $nombre_municipio_residencia  = $nombre_municipio_residencia[0];
        }else {
            $nombre_municipio_residencia = "";
        }
        HTTP::enviarJSON($nombre_municipio_residencia);
    }
    exit;
}

/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {
    $error  = "";
    $titulo = $componente->nombre;

    $regimen = array(
        "1" => $textos["REGIMEN_COMUN"],
        "2" => $textos["REGIMEN_SIMPLIFICADO"]
    );

    $tipo_persona = array(
        "1" => $textos["PERSONA_NATURAL"],
        "2" => $textos["PERSONA_JURIDICA"],
        "3" => $textos["CODIGO_INTERNO"]
    );
    
    $barrios_localidades = HTML::generarDatosLista("seleccion_localidades", "id", "nombre");
    $id_tipo_documento   = SQL::obtenerValor("tipos_documento_identidad", "codigo", "descripcion='NIT' or descripcion='Nit'");

    /*** Definición de pestañas para datos del tercero***/
    $formularios["PESTANA_VENDEDOR"] = array(
        array(
                HTML::campoTextoCorto("*selector3", $textos["PROVEEDOR"], 40, 255, "", array("title" => $textos["AYUDA_PROVEEDOR"], "class" => "autocompletable"))
                .HTML::campoOculto("documento_identidad_proveedor", "")
            ),
        array(
            HTML::campoTextoCorto("*primer_nombre", $textos["PRIMER_NOMBRE"], 15, 15, "", array("title" => $textos["AYUDA_PRIMER_NOMBRE"], "onblur" => "validarItem(this)")),
            HTML::campoTextoCorto("segundo_nombre", $textos["SEGUNDO_NOMBRE"], 15, 15, "", array("title" => $textos["AYUDA_SEGUNDO_NOMBRE"], "onblur" => "validarItem(this)"))
        ),
        array(
            HTML::campoTextoCorto("*primer_apellido", $textos["PRIMER_APELLIDO"], 15, 15, "", array("title" => $textos["AYUDA_PRIMER_APELLIDO"], "onblur" => "validarItem(this)")),
            HTML::campoTextoCorto("segundo_apellido", $textos["SEGUNDO_APELLIDO"], 15, 15, "", array("title" => $textos["AYUDA_SEGUNDO_APELLIDO"], "onblur" => "validarItem(this)"))
        ),
        array(
            HTML::campoTextoCorto("*correo", $textos["CORREO"], 50, 255, "", array("title" => $textos["AYUDA_CORREO"], "onblur" => "validarItem(this)"))
        ),
        array(
             HTML::campoTextoCorto("*celular", $textos["CELULAR"], 20, 20, "", array("title" => $textos["AYUDA_CELULAR"], "onblur" => "validarItem(this)"))
        ),
    );

   /*** Definición de botones ***/
    $botones = array(
        HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar"),
    );

    $contenido = HTML::generarPestanas($formularios, $botones);

    /*** Enviar datos para la generación del formulario al script que originó la petición ***/
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

/*** Validar los datos provenientes del formulario ***/
} elseif (!empty($url_validar)) {
    $respuesta = "";

    /*** Validar documento de identidad ***/
    if ($url_item == "documento_identidad_proveedor") {
        $existe_primer_nombre = SQL::existeItem("vendedores_provedor", "primer_nombre", $url_valor,"primer_nombre !=0");
        if ($existe_primer_nombre) {
            $existe_primer_apellido = SQL::existeItem("vendedores_provedor", "primer_apellido", $url_valor,"primer_apellido !=0");
            HTTP::enviarJSON($textos["ERROR_VENDEDOR_EXISTE"]);
        }
    }

/*** Adicionar los datos provenientes del formulario ***/
} elseif (!empty($forma_procesar)) {

    /*** Asumir por defecto que no hubo error ***/
    $error        = false;
    $mensaje      = $textos["ITEM_ADICIONADO"];
    $valida_graba = false;

    $llave                         = explode("-", $forma_selector3);
    $documento_identidad_proveedor = $llave[0];
    
    /*** Validar el ingreso de campos requeridos ***/
    if (empty($forma_celular) || (empty($forma_correo) || (empty($forma_primer_nombre) && 
        empty($forma_primer_apellido) || (!empty($forma_primer_nombre) && empty($forma_primer_apellido))))) {

        $error   = true;
        $mensaje = $textos["ERROR_DATOS_INCOMPLETOS"];
    }else {

        $datos = array(
            "documento_proveedor"  => $documento_identidad_proveedor,
            "primer_nombre"        => $forma_primer_nombre,
            "segundo_nombre"       => $forma_segundo_nombre,
            "primer_apellido"      => $forma_primer_apellido,
            "segundo_apellido"     => $forma_segundo_apellido,
            "celular"              => $forma_celular,
            "correo"               => $forma_correo,
            "activo"               => '1',
            "id_usuario_registra"  => $sesion_id_usuario_ingreso,
            "fecha_registra"       => date("Y-m-d H:i:s"),
            "fecha_modificacion"   => ""
        );

        $insertar = SQL::insertar("vendedores_proveedor", $datos);
        /*** Error de inserción ***/
        if (!$insertar) {
            $error   = true;
            $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
        }   
    }

    /*** Enviar datos con la respuesta del proceso al script que originï¿½ la peticiï¿½n ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

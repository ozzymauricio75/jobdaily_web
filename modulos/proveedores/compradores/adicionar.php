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
$tabla = "usuarios";
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
    $inicio_cobro = array(
        "1" => $textos["FECHA_FACTURA"],
        "2" => $textos["FECHA_RECIBO"]
    );
    
    $genero = array(
        "M" => $textos["MASCULINO"],
        "F" => $textos["FEMENINO"],
        "N" => $textos["NO_APLICA"],
    );
    
    $barrios_localidades = HTML::generarDatosLista("seleccion_localidades", "id", "nombre");
    $id_tipo_documento   = SQL::obtenerValor("tipos_documento_identidad", "codigo", "descripcion='NIT' or descripcion='Nit'");

    /*** Definición de pestañas para datos del tercero***/
    $formularios["PESTANA_COMPRADOR"] = array(
        array(
            HTML::listaSeleccionSimple("*empresa", $textos["EMPRESA"], HTML::generarDatosLista("empresas", "codigo", "razon_social","codigo != 0"), "", array("title" => $textos["AYUDA_EMPRESAS"],""))
        ),
        array(
            HTML::campoTextoCorto("*documento_identidad", $textos["DOCUMENTO_COMPRADOR"], 15, 15, "", array("title" => $textos["AYUDA_DOCUMENTO_PROVEEDOR"],""))
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
            HTML::campoTextoCorto("correo", $textos["CORREO"], 50, 255, "", array("title" => $textos["AYUDA_CORREO"], "onblur" => "validarItem(this)"))
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
    if ($url_item == "documento_identidad") {
        $existe_tercero = SQL::existeItem("terceros", "documento_identidad", $url_valor,"documento_identidad !=0");
        if ($existe_tercero) {
            HTTP::enviarJSON($textos["ERROR_DOCUMENTO_IDENTIDAD_EXISTE"]);
        }
    }

/*** Adicionar los datos provenientes del formulario ***/
} elseif (!empty($forma_procesar)) {
    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];
    $valida_graba = false;
    
    /*** Validar el ingreso de campos requeridos ***/
    if (empty($forma_documento_identidad) ||
        empty($forma_celular) || (
        empty($forma_primer_nombre) && 
        empty($forma_primer_apellido) || 
        (!empty($forma_primer_nombre) && empty($forma_primer_apellido)))) {

        $error   = true;
        $mensaje = $textos["ERROR_DATOS_INCOMPLETOS"];
    
    }elseif($existe_tercero = SQL::existeItem("terceros", "documento_identidad", $forma_documento_identidad,"documento_identidad !=0")){
        $error   = true;
        $mensaje = $textos["ERROR_DOCUMENTO_IDENTIDAD_EXISTE"];
    }else {

        $existe_tercero = SQL::existeItem("terceros", "documento_identidad", $forma_documento_identidad);
        if ($existe_tercero) {
            $documento_tercero = SQL::obtenerValor("terceros", "documento_identidad", "documento_identidad = '$forma_documento_identidad'");
        }

        $datos = array(
            "documento_identidad"                => $forma_documento_identidad,
            "tipo_persona"                       => '1',
            "codigo_tipo_documento"              => '1',
            "primer_nombre"                      => $forma_primer_nombre,
            "segundo_nombre"                     => $forma_segundo_nombre,
            "primer_apellido"                    => $forma_primer_apellido,
            "segundo_apellido"                   => $forma_segundo_apellido,
            "fecha_ingreso"                      => date("Y-m-d H:i:s"),
            "direccion_principal"                => "",
            "telefono_principal"                 => "",
            "fax"                                => "",
            "celular"                            => $forma_celular,
            "genero"                             => 'N',
            "sitio_web"                          => "",
            "correo"                             => $forma_correo,
            "codigo_iso_municipio_documento"     => "CO",
            "codigo_dane_departamento_documento" => '76',
            "codigo_dane_municipio_documento"    => '001',
            "codigo_iso_localidad"               => "CO",
            "codigo_dane_departamento_localidad" => '76',
            "codigo_dane_municipio_localidad"    => '001',
            "tipo_localidad"                     => 'B',
            "codigo_dane_localidad"              => 'EL',
            "comprador"                          => '1'
        );

        if (!$existe_tercero) {
            $insertar = SQL::insertar("terceros", $datos);
            /*** Error de inserción ***/
            if (!$insertar) {
                $error   = true;
                $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
            }
            $idAsignado = SQL::$ultimoId;
        }else{
            $modificar = SQL::modificar("terceros", $datos, "id = '$idAsignado'");
            if ($modificar) {
                $error   = false;
                $mensaje = $textos["ITEM_MODIFICADO"];
            } else {
                $error   = true;
                $mensaje = $textos["ERROR_MODIFICAR_ITEM"];
            }
        }
        $tabla = "terceros";
            $columnas          = SQL::obtenerColumnas($tabla);
            $consulta          = SQL::seleccionar(array($tabla), $columnas, "documento_identidad = '$forma_documento_identidad'");
            $datos             = SQL::filaEnObjeto($consulta);
            $documento_tercero = $datos->documento_identidad;

            $datos_compradores = array(
                "codigo_empresa"      => $forma_empresa,
                "documento_identidad" => $forma_documento_identidad,
                "activo"              => "1",
                "id_usuario_registra" => $sesion_id_usuario_ingreso,
                "fecha_registra"      => date("Y-m-d H:i:s"),
                "fecha_modificacion"  => date("Y-m-d H:i:s")
            );
            
            $id_comprador = SQL::insertar("compradores",$datos_compradores, true);
            if (!$id_comprador){
                $error   = true;
                $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
                if ($crear_tercero){
                    $eliminar = SQL::eliminar("terceros","documento_identidad='$documento_tercero'");
                }
            }
    }

    /*** Enviar datos con la respuesta del proceso al script que originï¿½ la peticiï¿½n ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

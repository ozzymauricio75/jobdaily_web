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

/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {

    /*** Verificar que se haya enviado el ID del elemento a eliminar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_ELIMINAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {
        $vistaConsulta = "terceros";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "documento_identidad = '$url_id'");
        $datos         = SQL::filaEnObjeto($consulta);
        
        $error         = "";
        $titulo        = $componente->nombre;

        $llave_municipio = $datos->codigo_iso_municipio_documento.'|'.$datos->codigo_dane_departamento_documento.'|'.$datos->codigo_dane_municipio_documento;
        $llave_localidad = $datos->codigo_iso_localidad.'|'.$datos->codigo_dane_departamento_localidad.'|'.$datos->codigo_dane_municipio_localidad.'|'.$datos->tipo_localidad.'|'.$datos->codigo_dane_localidad;

        $municipio       = explode('|',SQL::obtenerValor("seleccion_municipios","nombre","id = '".$llave_municipio."'"));
        $localidad       = explode('|',SQL::obtenerValor("seleccion_localidades","nombre","id = '".$llave_localidad."'"));

        $persona = array(
            "1" => $textos["PERSONA_NATURAL"],
            "2" => $textos["PERSONA_JURIDICA"],
            "3" => $textos["CODIGO_INTERNO"],
            "4" => $textos["NATURAL_COMERCIANTE"]
        );

        $activo = array(
            "0" => $textos["INACTIVO"],
            "1" => $textos["ACTIVO"]
        );

        $genero = array(
            "M" => $textos["MASCULINO"],
            "F" => $textos["FEMENINO"],
            "N" => $textos["NO_APLICA"]
        );

        if ($datos->tipo_persona==1 || $datos->tipo_persona==4){
            $nombres = array(
                HTML::mostrarDato("primer_nombre", $textos["PRIMER_NOMBRE"], $datos->primer_nombre),
                HTML::mostrarDato("segundo_nombre", $textos["SEGUNDO_NOMBRE"], $datos->segundo_nombre)
            );
            $apellidos = array(
                HTML::mostrarDato("primer_apellido", $textos["PRIMER_APELLIDO"], $datos->primer_apellido),
                HTML::mostrarDato("segundo_apellido", $textos["SEGUNDO_APELLIDO"], $datos->segundo_apellido)
            );
            $razon_social = array(
                HTML::mostrarDato("razon_social","","")
            );
        } else {
            $nombres = array(
                HTML::mostrarDato("primer_nombre","","")
            );
            $apellidos = array(
                HTML::mostrarDato("primer_apellido","","")
            );
            $razon_social = array(
                HTML::mostrarDato("razon_social", $textos["RAZON_SOCIAL"], $datos->razon_social),
                HTML::mostrarDato("nombre_comercial", $textos["NOMBRE_COMERCIAL"], $datos->nombre_comercial)
            );
        }

        $tipo_documento = SQL::obtenerValor("tipos_documento_identidad","descripcion","codigo = '".$datos->codigo_tipo_documento."'");

        /*** Definición de pestañas ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::mostrarDato("tipo_documento", $textos["TIPO_DOCUMENTO_IDENTIDAD"], $tipo_documento),
                HTML::mostrarDato("municipio_documento", $textos["MUNICIPIO"], $municipio[0])
            ),
            array(
                HTML::mostrarDato("documento_identidad", $textos["DOCUMENTO_IDENTIDAD"], $datos->documento_identidad),
                HTML::mostrarDato("fecha_nacimiento", $textos["FECHA_NACIMIENTO"], $datos->fecha_nacimiento),
                HTML::mostrarDato("tipo_persona", $textos["TIPO_PERSONA"], $persona[$datos->tipo_persona])
            ),
            $nombres,
            $apellidos,
            $razon_social,
            array(
                HTML::mostrarDato("localidad_residencia", $textos["LOCALIDAD"], $localidad[0]),
                HTML::mostrarDato("direccion_principal", $textos["DIRECCION"], $datos->direccion_principal)
            ),
            array(
                HTML::mostrarDato("telefono_principal", $textos["TELEFONO"], $datos->telefono_principal),
                HTML::mostrarDato("fax", $textos["FAX"], $datos->fax),
                HTML::mostrarDato("celular", $textos["CELULAR"], $datos->celular)
            ),
            array(
                HTML::mostrarDato("correo", $textos["CORREO"], $datos->correo),
                HTML::mostrarDato("sitio_web", $textos["SITIO_WEB"], $datos->sitio_web)
            ),
            array(
                HTML::mostrarDato("correo2", $textos["CORREO2"], $datos->correo2),
                HTML::mostrarDato("celular2", $textos["CELULAR2"], $datos->celular2)
            ),
            array(
                HTML::mostrarDato("genero", $textos["GENERO"], $genero[$datos->genero]),
                HTML::mostrarDato("activo", $textos["ESTADO"], $activo[$datos->activo])
            )
        );

        /*** Definición de botones ***/
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "eliminarItem('$url_id');", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones);
    }

    /*** Enviar datos para la generación del formulario al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

/*** Eliminar el elemento seleccionado ***/
} elseif (!empty($forma_procesar)) {
    $consulta = SQL::eliminar("terceros", "documento_identidad = '$forma_id'");

    if ($consulta) {
        $error   = false;
        $mensaje = $textos["ITEM_ELIMINADO"];
    } else {
        $error   = true;
        $mensaje = $textos["ERROR_ELIMINAR_ITEM"];
    }

    /*** Enviar datos con la respuesta del proceso al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

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

/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {

    /*** Verificar que se haya enviado el ID del elemento a consultar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_CONSULTAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {
        $vistaConsulta                  = "terceros";
        $columnas                       = SQL::obtenerColumnas($vistaConsulta);
        $consulta                       = SQL::seleccionar(array($vistaConsulta), $columnas, "id = '$url_id'");
        $datos                          = SQL::filaEnObjeto($consulta);
        $error                          = "";
        $titulo                         = $componente->nombre;
        
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
        
        
        if(($datos->tipo_persona) == 1){
            $primer_nombre    = "PRIMER_NOMBRE";
            $segundo_nombre   = "SEGUNDO_NOMBRE";
            $primer_apellido  = "PRIMER_APELLIDO";
            $segundo_apellido = "SEGUNDO_APELLIDO";
            $razon_social     = "DATO_VACIO";
        }else{
            $razon_social     = "RAZON_SOCIAL";
            $primer_nombre    = "DATO_VACIO";
            $segundo_nombre   = "DATO_VACIO";
            $primer_apellido  = "DATO_VACIO";
            $segundo_apellido = "DATO_VACIO";
        }

        /*** Definición de pestañas ***/
        $formularios["PESTANA_COMPRADOR"] = array(
            array(
                HTML::mostrarDato("documento_identidad", $textos["DOCUMENTO_COMPRADOR"], $datos->documento_identidad)
            ),
            array(
                HTML::mostrarDato("tipo_persona", $textos["TIPO_PERSONA"], $tipo_persona[$datos->tipo_persona]),
                HTML::mostrarDato("id_tipo_documento", $textos["TIPO_DOCUMENTO_IDENTIDAD"], $tipo_documento_identidad)
            ),
            array(
                HTML::mostrarDato("primer_nombre", $textos["$primer_nombre"], $datos->primer_nombre),
                HTML::mostrarDato("segundo_nombre", $textos["$segundo_nombre"], $datos->segundo_nombre),
                HTML::mostrarDato("primer_apellido", $textos["$primer_apellido"], $datos->primer_apellido),
                HTML::mostrarDato("segundo_apellido", $textos["$segundo_apellido"], $datos->segundo_apellido)
            ),
            array(
                HTML::mostrarDato("razon_social", $textos["$razon_social"], $datos->razon_social)
            ),
            /*array(
                HTML::mostrarDato("nombre_comercial", $textos["NOMBRE_COMERCIAL"], $datos->nombre_comercial)
            ),*/
            array(
                HTML::mostrarDato("pais_documento", $textos["PAIS"], $nombre_pais_documento),
                HTML::mostrarDato("departamento_documento", $textos["DEPARTAMENTO"], $nombre_departamento_documento),
                HTML::mostrarDato("municipio_documento", $textos["MUNICIPIO"], $nombre_municipio_documento),
            )
        );

        /***Definición pestaña ubicacion***/
        $formularios["PESTANA_UBICACION_COMPRADOR"] = array(
            array(
                HTML::mostrarDato("pais_residencia", $textos["PAIS"], $nombre_pais_residencia),
                HTML::mostrarDato("departamento_residencia", $textos["DEPARTAMENTO"], $nombre_departamento_residencia)
            ),
            array(
                HTML::mostrarDato("municipio_residencia", $textos["MUNICIPIO"], $nombre_municipio_residencia),
                HTML::mostrarDato("localidad_residencia", $textos["LOCALIDAD"], $nombre_localidad_residencia)
            ),
            array(
                HTML::mostrarDato("direccion_principal", $textos["DIRECCION"], $datos->direccion_principal),
                HTML::mostrarDato("telefono_principal", $textos["TELEFONO_PRINCIPAL"], $datos->telefono_principal),
                HTML::mostrarDato("fax", $textos["FAX"], $datos->fax),
                HTML::mostrarDato("celular", $textos["CELULAR"], $datos->celular)
            ),
            array(
                HTML::mostrarDato("correo", $textos["CORREO"], $datos->correo),
                HTML::mostrarDato("sitio_web", $textos["SITIO_WEB"], $datos->sitio_web)
            )
        );

        $contenido = HTML::generarPestanas($formularios);
    }

    /*** Enviar datos para la generación del formulario al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);
}
?>

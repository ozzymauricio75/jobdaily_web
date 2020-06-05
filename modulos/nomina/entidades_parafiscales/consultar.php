<?php

/**
*
* Copyright (C) 2008 Sistemas de Apoyo Empresarial Ltda
* 
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
* de APTITUD PARA UN PROPÓITO DETERMINADO. Consulte los detalles de
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
        /*** Obtener los datos de la tabla de terceros ***/
        $vistaConsulta = "entidades_parafiscales";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo = '$url_id'");
        $datos         = SQL::filaEnObjeto($consulta);
        $error         = "";
        $titulo        = $componente->nombre;

        $vistaTercero    = "terceros";
        $columnasTercero = SQL::obtenerColumnas($vistaTercero);
        $consultaTercero = SQL::seleccionar(array($vistaTercero), $columnasTercero, "documento_identidad = '$datos->documento_identidad_tercero'");
        $datosTercero    = SQL::filaEnObjeto($consultaTercero);

        /*** Definición de pestaña basica ***/
        $pension   = $textos["SI_NO_".$datos->pension];
        $salud     = $textos["SI_NO_".$datos->salud];
        $cesantias = $textos["SI_NO_".$datos->cesantias];
        $caja      = $textos["SI_NO_".$datos->caja];
        $sena      = $textos["SI_NO_".$datos->sena];
        $icbf      = $textos["SI_NO_".$datos->icbf];
        $arp       = $textos["SI_NO_".$datos->riesgos_profesionales];

        /*** Definición de pestaña tercero***/
        $codigo_iso_documento               = $datosTercero->codigo_iso_municipio_documento;
        $codigo_dane_departamento_documento = $datosTercero->codigo_dane_departamento_documento;
        $codigo_dane_municipio_documento    = $datosTercero->codigo_dane_municipio_documento;
        $condicion                          = "codigo_iso='$codigo_iso_documento'";
        $pais_documento                     = SQL::obtenerValor("paises","nombre",$condicion);
        $condicion                          = "codigo_iso='$codigo_iso_documento' AND codigo_dane_departamento='$codigo_dane_departamento_documento'";
        $departamento_documento             = SQL::obtenerValor("departamentos","nombre",$condicion);
        $condicion                          = "codigo_iso='$codigo_iso_documento' AND codigo_dane_departamento='$codigo_dane_departamento_documento' AND codigo_dane_municipio='$codigo_dane_municipio_documento'";
        $municipio_documento                = SQL::obtenerValor("municipios","nombre",$condicion);

        $codigo_iso_localidad               = $datosTercero->codigo_iso_localidad;
        $codigo_dane_departamento_localidad = $datosTercero->codigo_dane_departamento_localidad;
        $codigo_dane_municipio_localidad    = $datosTercero->codigo_dane_municipio_localidad;
        $tipo_localidad                     = $datosTercero->tipo_localidad;
        $codigo_dane_localidad              = $datosTercero->codigo_dane_localidad;
        $condicion                          = "codigo_iso='$codigo_iso_localidad'";
        $pais_localidad                     = SQL::obtenerValor("paises","nombre",$condicion);
        $condicion                          = "codigo_iso='$codigo_iso_localidad' AND codigo_dane_departamento='$codigo_dane_departamento_localidad'";
        $departamento_localidad             = SQL::obtenerValor("departamentos","nombre",$condicion);
        $condicion                          = "codigo_iso='$codigo_iso_localidad' AND codigo_dane_departamento='$codigo_dane_departamento_localidad' AND codigo_dane_municipio='$codigo_dane_municipio_localidad'";
        $municipio_localidad                = SQL::obtenerValor("municipios","nombre",$condicion);
        $condicion                          = "codigo_iso='$codigo_iso_localidad' AND codigo_dane_departamento='$codigo_dane_departamento_localidad' AND codigo_dane_municipio='$codigo_dane_municipio_localidad' AND tipo='$tipo_localidad' AND codigo_dane_localidad='$codigo_dane_localidad'";
        $localidad                          = SQL::obtenerValor("localidades","nombre",$condicion);

        $formularios["PESTANA_BASICA"] = array(
            array(
                HTML::mostrarDato("codigo", $textos["CODIGO"], $datos->codigo)
            ),
            array(
                HTML::mostrarDato("codigo_ruaf", $textos["CODIGO_RUAF"], $datos->codigo_ruaf)
            ),
            array(
                HTML::mostrarDato("nombre", $textos["NOMBRE"], $datos->nombre)
            ),
            array(
                HTML::mostrarDato("pension", $textos["PENSION"], $pension),
                HTML::mostrarDato("salud", $textos["SALUD"], $salud),
                HTML::mostrarDato("cesantias", $textos["CESANTIAS"], $cesantias),
            ),
            array(
                HTML::mostrarDato("caja", $textos["CAJA"], $caja),
                HTML::mostrarDato("sena", $textos["SENA"], $sena),
                HTML::mostrarDato("icbf", $textos["ICBF"], $icbf)
            ),
            array(
                HTML::mostrarDato("riesgos_profesionales", $textos["RIESGOS_PROFESIONALES"], $arp)
            )
        );

        $formularios["PESTANA_TERCERO"] = array(
            array(
                HTML::mostrarDato("documento_identidad", $textos["DOCUMENTO_IDENTIDAD"], $datos->documento_identidad_tercero)
            ),
            array(
                HTML::mostrarDato("razon_social", $textos["NOMBRE_TERCERO"], $datosTercero->razon_social)
            ),
            array(
                HTML::mostrarDato("pais", $textos["PAIS"], $pais_documento)
            ),
            array(
                HTML::mostrarDato("departamento", $textos["DEPARTAMENTO"], $departamento_documento)
            ),
            array(
                HTML::mostrarDato("municipio", $textos["MUNICIPIO"], $municipio_documento)
            ),
        );

        $formularios["PESTANA_UBICACION"] = array(
            array(
                HTML::mostrarDato("pais_localidad", $textos["PAIS"], $pais_localidad),
                HTML::mostrarDato("departamento_localidad", $textos["DEPARTAMENTO"], $departamento_localidad)
            ),
            array(
                HTML::mostrarDato("municipio_localidad", $textos["MUNICIPIO"], $municipio_localidad),
                HTML::mostrarDato("localidad", $textos["LOCALIDAD"], $localidad)
            ),
            array(
                HTML::mostrarDato("direccion", $textos["DIRECCION"], $datosTercero->direccion_principal)
            ),
            array(
                HTML::mostrarDato("telefono", $textos["TELEFONO"], $datosTercero->telefono_principal),
                HTML::mostrarDato("celular", $textos["TELEFONO"], $datosTercero->celular),
                HTML::mostrarDato("fax", $textos["FAX"], $datosTercero->fax)
            ),
            array(
                HTML::mostrarDato("correo", $textos["CORREO"], $datosTercero->correo),
                HTML::mostrarDato("sitio_web", $textos["SITIO_WEB"], $datosTercero->sitio_web)
            )
        );

        $contenido = HTML::generarPestanas($formularios);
    }

    /*** Enviar datos para la generación del formulario al script que originï¿½ la peticiï¿½n ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);
}
?>

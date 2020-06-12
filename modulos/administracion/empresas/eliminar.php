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

    /*** Verificar que se haya enviado el ID del elemento a consultar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_CONSULTAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {
        $vistaConsulta = "empresas";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo= '$url_id'");
        $datos         = SQL::filaEnObjeto($consulta);
        $error         = "";
        $titulo        = $componente->nombre;

        $activo = array(
            "0" => $textos["ESTADO_ACTIVA"],
            "1" => $textos["ESTADO_INACTIVA"]
        );
        $regimen = array(
            "1" => $textos["REGIMEN_COMUN"],
            "2" => $textos["REGIMEN_SIMPLIFICADO"]
        );

        $tipo_persona = array(
            "1" => $textos["NATURAL"],
            "2" => $textos["JURIDICA"],
            "3" => $textos["INTERNO"],
            "4" => $textos["NATURAL_COMERCIANTE"]
        );

        /***Obtener los datos de la tabla de empresas***/

        $vista_empresa    = "empresas";
        $columnas_empresa = SQL::obtenerColumnas($vista_empresa);
        $consulta_empresa= SQL::seleccionar(array($vista_empresa),$columnas_empresa, "codigo = '$url_id'");
        $datos_empresa    = SQL::filaEnObjeto($consulta_empresa);







        $documento_tercero                   = $datos_empresa -> documento_identidad_tercero;
        $fecha_cierre                        = $datos_empresa -> fecha_cierre;
        $regimen_tabla                       = $datos_empresa -> regimen;
        $retiene_fuente                      = $datos_empresa -> retiene_fuente;
        $autoretenedor                       = $datos_empresa -> autoretenedor;
        $retiene_iva                         = $datos_empresa -> retiene_iva;
        $retiene_ica                         = $datos_empresa -> retiene_ica;
        $autoretenedor_ica                   = $datos_empresa -> autoretenedor_ica;
        $gran_contribuyente                  = $datos_empresa -> gran_contribuyente;

        /*Numero de resolucaion rentencion en la fuente*/



        /**Actividades Economicas Primaria**/
        $llave_primaria_actividad_principal  = $datos_empresa -> codigo_iso_primaria."|".$datos_empresa -> codigo_dane_departamento_primaria."|".$datos_empresa -> codigo_dane_municipio_primaria."|".$datos_empresa -> codigo_dian_primaria."|". $datos_empresa -> codigo_actividad_municipio_primaria;
        $actividad_principal                 = SQL::obtenerValor("buscador_actividades_economicas", "descripcion", "id = '$llave_primaria_actividad_principal'");

        /**Actividades Economicas Secundaria**/
        $llave_primaria_actividad_secundaria = $datos_empresa -> codigo_iso_secundaria."|".$datos_empresa -> codigo_dane_departamento_secundaria."|".$datos_empresa -> codigo_dane_municipio_secundaria."|".$datos_empresa -> codigo_dian_secundaria."|".$datos_empresa -> codigo_actividad_municipio_secundaria;
       // echo var_dump($llave_primaria_actividad_secundaria);
        $actividad_secundaria                 = SQL::obtenerValor("buscador_actividades_economicas", "descripcion", "id = '$llave_primaria_actividad_secundaria'");


        /*** Obtener los datos de la tabla de terceros ***/

        $vistaTercero    = "terceros";
        $columnasTercero = SQL::obtenerColumnas($vistaTercero);
        $consultaTercero = SQL::seleccionar(array($vistaTercero), $columnasTercero, "documento_identidad = '$documento_tercero'");
        $datosTercero    = SQL::filaEnObjeto($consultaTercero);

        if(($datosTercero->tipo_persona) == 1){
            $primer_nombre    = "PRIMER_NOMBRE";
            $segundo_nombre   = "SEGUNDO_NOMBRE";
            $primer_apellido  = "PRIMER_APELLIDO";
            $segundo_apellido = "SEGUNDO_APELLIDO";
            $razon_social     = "DATO_VACIO";
        }elseif(($datosTercero->tipo_persona) == 4){
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

        $descripcion_tipo_documento     = SQL::obtenerValor("tipos_documento_identidad", "descripcion", "codigo = '$datosTercero->codigo_tipo_documento'");
        /**Informacion de municipio de expedicion**/
        $nombre_municipio_documento     = SQL::obtenerValor("municipios", "nombre", "codigo_dane_municipio = '$datosTercero->codigo_dane_municipio_documento'");
        $nombre_departamento_documento  = SQL::obtenerValor("departamentos", "nombre", "codigo_dane_departamento = '$datosTercero->codigo_dane_departamento_documento'");
        $nombre_pais_documento          = SQL::obtenerValor("paises", "nombre", "codigo_iso = '$datosTercero->codigo_iso_municipio_documento'");
       /**Informacion de lugar de recidencia**/
        $nombre_localidad_residencia    = SQL::obtenerValor("localidades", "nombre", "codigo_dane_localidad = '$datosTercero->codigo_dane_localidad'");
        $nombre_municipio_residencia    = SQL::obtenerValor("municipios", "nombre", "codigo_dane_municipio = '$datosTercero->codigo_dane_municipio_localidad'");
        $nombre_departamento_residencia = SQL::obtenerValor("departamentos", "nombre", "codigo_dane_departamento = '$datosTercero->codigo_dane_departamento_localidad'");
        $nombre_pais_residencia         = SQL::obtenerValor("paises", "nombre", "codigo_iso = '$datosTercero->codigo_iso_localidad'");

         /*** Definición de pestañas ***/
        $formularios["PESTANA_TERCERO"] = array(
            array(
                HTML::mostrarDato("documento_identidad", $textos["DOCUMENTO_TERCERO"], $datosTercero->documento_identidad)
            ),
            array(
                HTML::mostrarDato("tipo_persona", $textos["TIPO_PERSONA"], $tipo_persona[$datosTercero->tipo_persona]),
                HTML::mostrarDato("descripcion_tipo_documento", $textos["TIPO_DOCUMENTO_IDENTIDAD"], $descripcion_tipo_documento)
            ),
            array(
                HTML::mostrarDato("primer_nombre", $textos["$primer_nombre"], $datosTercero->primer_nombre),
                HTML::mostrarDato("segundo_nombre", $textos["$segundo_nombre"], $datosTercero->segundo_nombre),
                HTML::mostrarDato("primer_apellido", $textos["$primer_apellido"], $datosTercero->primer_apellido),
                HTML::mostrarDato("segusndo_apellido", $textos["$segundo_apellido"], $datosTercero->segundo_apellido)
            ),
            array(
                HTML::mostrarDato("razon_social", $textos["$razon_social"], $datosTercero->razon_social)
            ),
            array(
                HTML::mostrarDato("nombre_comercial", $textos["NOMBRE_COMERCIAL"], $datosTercero->nombre_comercial)
            ),
            array(
                HTML::mostrarDato("pais_documento", $textos["PAIS"], $nombre_pais_documento),
                HTML::mostrarDato("departamento_documento", $textos["DEPARTAMENTO"], $nombre_departamento_documento),
                HTML::mostrarDato("municipio_documento", $textos["MUNICIPIO"], $nombre_municipio_documento),
            )
        );

        /***Definición pestaña ubicacion***/
        $formularios["PESTANA_UBICACION_TERCERO"] = array(
            array(
                HTML::mostrarDato("pais_residencia", $textos["PAIS"], $nombre_pais_residencia),
                HTML::mostrarDato("departamento_residencia", $textos["DEPARTAMENTO"], $nombre_departamento_residencia)
            ),
            array(
                HTML::mostrarDato("municipio_residencia", $textos["MUNICIPIO"], $nombre_municipio_residencia),
                HTML::mostrarDato("localidad_residencia", $textos["LOCALIDAD"], $nombre_localidad_residencia)
            ),
            array(
                HTML::mostrarDato("direccion_principal", $textos["DIRECCION"], $datosTercero->direccion_principal),
                HTML::mostrarDato("telefono_principal", $textos["TELEFONO_PRINCIPAL"], $datosTercero->telefono_principal),
                HTML::mostrarDato("fax", $textos["FAX"], $datosTercero->fax),
                HTML::mostrarDato("celular", $textos["CELULAR"], $datosTercero->celular)
            ),
            array(
                HTML::mostrarDato("correo", $textos["CORREO"], $datosTercero->correo),
                HTML::mostrarDato("sitio_web", $textos["SITIO_WEB"], $datosTercero->sitio_web)
            )
        );

        /*** Definición pestaña empresa ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::mostrarDato("codigo", $textos["CODIGO"],$datos_empresa->codigo)
            ),
            array(
                HTML::mostrarDato("razon_social", $textos["RAZON_SOCIAL"],$datos_empresa->razon_social)
            ),
            array(
                HTML::mostrarDato("nombre_corto", $textos["NOMBRE_CORTO"],$datos_empresa->nombre_corto)
            ),
            array(
                HTML::mostrarDato("actividad_principal", $textos["ACTIVIDAD_PRINCIPAL"], $actividad_principal)
            ),
            array(
                HTML::mostrarDato("actividad_secundaria", $textos["ACTIVIDAD_SECUNDARIA"], $actividad_secundaria)
            ),
            array(
                HTML::mostrarDato("fecha_cierre", $textos["FECHA_CIERRE"], $fecha_cierre),
                HTML::mostrarDato("activo", $textos["ACTIVO"], $textos["ACTIVO_".intval($activo)])
            )
        );



        if(!empty($datos_empresa->numero_retefuente)){
            $oculto4 = "";

            $id_resolucion_fuente = SQL::obtenerValor("seleccion_resoluciones_retefuente", "descripcion", "id = '$datos_empresa->numero_retefuente'");
            $id_resolucion_fuente = explode("|",$id_resolucion_fuente);
            $id_resolucion_fuente = $id_resolucion_fuente[0];
            $mostrar_resolucion_fuente = HTML::mostrarDato("selector6", $textos["RESOLUCION_RETEFUENTE"],$id_resolucion_fuente);
        }else{
            $mostrar_resolucion_fuente = "";
            $oculto1 = "oculto";
        }


        if(!empty($datos_empresa->numero_resolucion_contribuyente)){
            $id_resolucion_contribuyente = SQL::obtenerValor("seleccion_resoluciones_contribuyente", "descripcion", "id = '$datos_empresa->numero_resolucion_contribuyente'");
            $id_resolucion_contribuyente = explode("|",$id_resolucion_contribuyente);
            $id_resolucion_contribuyente = $id_resolucion_contribuyente[0];
            $mostrar_contribuyente = HTML::mostrarDato("selector5", $textos["RESOLUCION_CONTRIBUYENTE"],$id_resolucion_contribuyente);
        }else{
            $mostrar_contribuyente = "";
            $id_resolucion_contribuyente = "";
        }

        /*** Definición pestaña empresa ***/
        $formularios["PESTANA_TRIBUTARIA"] = array(
            array(
                HTML::mostrarDato("regimen", $textos["REGIMEN"], $regimen[$regimen_tabla])
            ),
            array(
                HTML::mostrarDato("retiene_fuente", $textos["RETIENE_FUENTE"], $textos["SI_NO_".intval($retiene_fuente)])
            ),
            array(
                HTML::mostrarDato("autoretenedor", $textos["AUTORETENEDOR"], $textos["SI_NO_".intval($autoretenedor)])
            ),
            array(
                $mostrar_resolucion_fuente
            ),
            array(
                HTML::mostrarDato("retiene_iva", $textos["RETIENE_IVA"], $textos["SI_NO_".intval($retiene_iva)])
            ),
            array(
                HTML::mostrarDato("retiene_ica", $textos["RETIENE_ICA"], $textos["SI_NO_".intval($retiene_ica)])
            ),
            array(
                HTML::mostrarDato("autoretenedor_ica", $textos["RETENEDOR_ICA"], $textos["SI_NO_".intval($autoretenedor_ica)])
            ),

            array(
                HTML::mostrarDato("gran_contribuyente", $textos["GRAN_CONTRIBUYENTE"], $textos["SI_NO_".intval($gran_contribuyente)])
            ),
            array(
                $mostrar_contribuyente
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
}elseif (!empty($forma_procesar)) {

    $id_tercero = SQL::obtenerValor("empresas", "documento_identidad_tercero", "codigo = '$forma_id'");
    $consulta   = SQL::eliminar("empresas", "codigo = '$forma_id'");

    if ($consulta) {
        $error   = false;
        $mensaje = $textos["ITEM_ELIMINADO"];
    } else {
        $error   = true;
        $mensaje = $textos["ERROR_ELIMINAR_ITEM"];
    }

    $consulta   = SQL::eliminar("terceros", "documento_identidad = '$id_tercero'");

    /*** Enviar datos con la respuesta del proceso al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

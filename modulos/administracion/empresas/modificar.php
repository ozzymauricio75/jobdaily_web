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
* modificarlo  bajo los terminos de la Licencia Publica General GNU
* publicada por la Fundacion para el Software Libre, ya sea la version 3
* de la Licencia, o (a su eleccion) cualquier version posterior.
*
* Este programa se distribuye con la esperanza de que sea util, pero
* SIN GARANTIA ALGUNA; ni siquiera la garantia implicita MERCANTIL o
* de APTITUD PARA UN PROPOSITO DETERMINADO. Consulte los detalles de
* la Licencia Publica General GNU para obtener una informacion mas
* detallada.
*
* Deberia haber recibido una copia de la Licencia Publica General GNU
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
**/

// Devolver datos para autocompletar la búsqueda
if (isset($url_completar)) {

    if (($url_item) == "selector1") {
        echo SQL::datosAutoCompletar("seleccion_municipios", $url_q);
    }
    if (($url_item) == "selector2") {
        echo SQL::datosAutoCompletar("seleccion_localidades", $url_q);
    }
    if (($url_item) == "selector4") {
        echo SQL::datosAutoCompletar("seleccion_resoluciones_reteica", $url_q);
    }
    if (($url_item) == "selector5") {
        echo SQL::datosAutoCompletar("seleccion_resoluciones_contribuyente", $url_q);
    }
    if (($url_item) == "selector6") {
        echo SQL::datosAutoCompletar("seleccion_resoluciones_retefuente", $url_q);
    }
    exit;
}

// Devolver datos para cargar los elementos del formulario relacionados con el documento del cliente digitado
if (isset($url_recargar)) {

    if (!empty($url_documento_identidad_carga)) {

        $consulta = SQL::seleccionar(array("terceros"), array("*"), "documento_identidad = '$url_documento_identidad_carga'", "", "documento_identidad", 1);

        $tabla = array();

        if (SQL::filasDevueltas($consulta)) {
            $datos = SQL::filaEnObjeto($consulta);

            $condicion                 = $datos->codigo_iso_localidad."|".$datos->codigo_dane_departamento_localidad."|".$datos->codigo_dane_municipio_localidad;
            $consulta2                 = SQL::seleccionar(array("seleccion_municipios"), array("*"), "id='$condicion'");
            $datos_muicipio            = SQL::filaEnObjeto($consulta2);
            $llave_primaria_municipios = explode("|", $datos_muicipio->nombre);

            $condicion                 .= "|".$datos->tipo_localidad."|".$datos->codigo_dane_localidad;
            $consulta3                  = SQL::seleccionar(array("seleccion_localidades"), array("*"), "id='$condicion'");
            $datos_localidad            = SQL::filaEnObjeto($consulta3);
            $llave_primaria_localidades = explode("|", $datos_localidad->nombre);

            $tabla = array(
                $datos->documento_identidad,
                $llave_primaria_municipios[1],
                $datos->tipo_persona,
                $datos->primer_nombre,
                $datos->segundo_nombre,
                $datos->primer_apellido,
                $datos->segundo_apellido,
                $datos->razon_social,
                $datos->nombre_comercial,
                $datos->fecha_nacimiento,
                $llave_primaria_localidades[1],
                $datos->direccion_principal,
                $datos->telefono_principal,
                $datos->celular,
                $datos->fax,
                $datos->correo,
                $datos->sitio_web
            );
        } else {
            $tabla[] = "";
        }
        HTTP::enviarJSON($tabla);
    }
    exit;
}

if (isset($url_recargarMunicipioDocumento)) {

    if (!empty($url_municipio_documento)) {

        $llave_primaria=explode(",",$url_municipio_documento);

        $consulta = SQL::seleccionar(array("seleccion_municipios"), array("nombre"), "llave_primaria = '$url_municipio_documento'", "", "nombre", 1);

        if (SQL::filasDevueltas($consulta)) {
            $datos = SQL::filaEnObjeto($consulta);
            $nombre_municipio_documento = $datos->nombre;
            $nombre_municipio_documento = explode("|", $nombre_municipio_documento);
            $nombre_municipio_documento = $nombre_municipio_documento[0];


        }else {
            $nombre_municipio_documento = "";
         }
        HTTP::enviarJSON($nombre_municipio_documento);
    }
    exit;
}

if (isset($url_recargarMunicipioResidencia)) {

    if (!empty($url_municipio_residencia)) {

        $consulta = SQL::seleccionar(array("seleccion_localidades"), array("nombre"), "llave_primaria = '$url_municipio_residencia'", "", "nombre", 1);

        if (SQL::filasDevueltas($consulta)) {
            $datos = SQL::filaEnObjeto($consulta);
            $nombre_municipio_residencia = $datos->nombre;
            $nombre_municipio_residencia = explode("|", $nombre_municipio_residencia);
            $nombre_municipio_residencia = $nombre_municipio_residencia[0];
        } else {
            $nombre_municipio_residencia = "";
        }
        HTTP::enviarJSON($nombre_municipio_residencia);
    }
    exit;
}

if (isset($url_recargarActividad)){

    $llave_municipio          = explode(",",$url_id_municipio_residencia);
    $codigo_iso               = $llave_municipio[0];
    $codigo_dane_departamento = $llave_municipio[1];
    $codigo_dane_municipio    = $llave_municipio[2];
    $condicion                = "codigo_iso='$codigo_iso' AND codigo_dane_departamento='$codigo_dane_departamento' AND codigo_dane_municipio='$codigo_dane_municipio'";
    $consulta = SQL::seleccionar(array("actividades_economicas"),array("*"),$condicion);

    if (SQL::filasDevueltas($consulta)){

        while ($datos = SQL::filaEnObjeto($consulta)){

            $llave  = $datos->codigo_iso."|".$datos->codigo_dane_departamento."|".$datos->codigo_dane_municipio."|";
            $llave .= $datos->codigo_dian."|".$datos->codigo_actividad_municipio;

            $actividad[$llave] = $datos->descripcion;
        }
    } else {
        $actividad = "";
    }
    HTTP::enviarJSON($actividad);
}
// Generar el formulario para la captura de datos

if (!empty($url_generar)) {

    // Verificar que se haya enviado el ID del elemento a consultar
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
            "0" => $textos["ESTADO_INACTIVA"],
            "1" => $textos["ESTADO_ACTIVA"]
        );

        $regimen = array(
            "1" => $textos["REGIMEN_COMUN"],
            "2" => $textos["REGIMEN_SIMPLIFICADO"]
        );

        $tipo_persona = array(
            "1" => $textos["NATURAL"],
            "2" => $textos["JURIDICA"],
            "3" => $textos["INTERNO"]
        );


        // Obtener los datos de la tabla de empresas

        $vista_empresa    = "empresas";
        $columnas_empresa = SQL::obtenerColumnas($vista_empresa);
        $consulta_empresa = SQL::seleccionar(array($vista_empresa),$columnas_empresa, "codigo = '$url_id'");
        $datos_empresa    = SQL::filaEnObjeto($consulta_empresa);

        $documento_tercero  = $datos_empresa->documento_identidad_tercero;
        $fecha_cierre       = $datos_empresa->fecha_cierre;
        $regimen_tabla      = $datos_empresa->regimen;
        $retiene_fuente     = $datos_empresa->retiene_fuente;
        $autoretenedor      = $datos_empresa->autoretenedor;
        $retiene_iva        = $datos_empresa->retiene_iva;
        $retiene_ica        = $datos_empresa->retiene_ica;
        $autoretenedor_ica  = $datos_empresa->autoretenedor_ica;
        $gran_contribuyente = $datos_empresa->gran_contribuyente;

        // Numero de resolucaion rentencion en la fuente

        // Actividades Economicas Primaria
        $llave_primaria_actividad_principal  = $datos_empresa->codigo_iso_primaria."|".$datos_empresa->codigo_dane_departamento_primaria."|".$datos_empresa->codigo_dane_municipio_primaria."|".$datos_empresa->codigo_dian_primaria."|". $datos_empresa->codigo_actividad_municipio_primaria;
        $actividad_principal                 = SQL::obtenerValor("buscador_actividades_economicas", "descripcion", "id = '$llave_primaria_actividad_principal'");

        // Actividades Economicas Secundaria
        $llave_primaria_actividad_secundaria = $datos_empresa->codigo_iso_secundaria."|".$datos_empresa->codigo_dane_departamento_secundaria."|".$datos_empresa->codigo_dane_municipio_secundaria."|".$datos_empresa->codigo_dian_secundaria."|".$datos_empresa->codigo_actividad_municipio_secundaria;
        $actividad_secundaria                = SQL::obtenerValor("buscador_actividades_economicas", "descripcion", "id = '$llave_primaria_actividad_secundaria'");

        // Obtener los datos de la tabla de terceros

        $vistaTercero    = "terceros";
        $columnasTercero = SQL::obtenerColumnas($vistaTercero);
        $consultaTercero = SQL::seleccionar(array($vistaTercero), $columnasTercero, "documento_identidad = '$documento_tercero'");
        $datosTercero    = SQL::filaEnObjeto($consultaTercero);

        if ($datosTercero->tipo_persona == 1){
            $valor_persona_natural     = true;
            $valor_oculto_natural      = "";
            $valor_oculto_juridica     = "oculto";
            $valor_persona_juridica    = false;
            $valor_codigo_interno      = false;
            $valor_oculto_comerciante  = "oculto";
            $valor_natural_comerciante = false;
        }elseif($datosTercero->tipo_persona == 2){
            $valor_persona_natural     = false;
            $valor_persona_juridica    = true;
            $valor_oculto_natural      = "oculto";
            $valor_oculto_juridica     = "";
            $valor_oculto_comerciante  = "";
            $valor_codigo_interno      = false;
            $valor_natural_comerciante = false;
        }elseif($datosTercero->tipo_persona == 4){
            $valor_persona_natural     = false;
            $valor_persona_juridica    = false;
            $valor_codigo_interno      = false;
            $valor_natural_comerciante = true;
            $valor_oculto_natural      = "";
            $valor_oculto_juridica     = "oculto";
            $valor_oculto_comerciante  = "";
        }else{
            $valor_persona_natural     = false;
            $valor_persona_juridica    = false;
            $valor_natural_comerciante = false;
            $valor_codigo_interno      = true;
            $valor_oculto_natural      = "oculto";
            $valor_oculto_juridica     = "";
            $valor_oculto_comerciante  = "";
        }

        $descripcion_tipo_documento     = SQL::obtenerValor("tipos_documento_identidad", "descripcion", "codigo = '$datosTercero->codigo_tipo_documento'");
        // Informacion de municipio de expedicion
        $condicion                      = "codigo_iso='$datosTercero->codigo_iso_municipio_documento' AND codigo_dane_departamento='$datosTercero->codigo_dane_departamento_documento'";
        $condicion                     .= " AND codigo_dane_municipio='$datosTercero->codigo_dane_municipio_documento'";
        $nombre_municipio_documento     = SQL::obtenerValor("municipios", "nombre", $condicion);

        $condicion                      = "codigo_iso='$datosTercero->codigo_iso_municipio_documento' AND codigo_dane_departamento='$datosTercero->codigo_dane_departamento_documento'";
        $nombre_departamento_documento  = SQL::obtenerValor("departamentos", "nombre", $condicion);

        $nombre_pais_documento          = SQL::obtenerValor("paises", "nombre", "codigo_iso = '$datosTercero->codigo_iso_municipio_documento'");
       // Informacion de lugar de recidencia
        $condicion                      = "codigo_iso='$datosTercero->codigo_iso_localidad' AND codigo_dane_departamento='$datosTercero->codigo_dane_departamento_localidad'";
        $condicion                     .= " AND codigo_dane_municipio='$datosTercero->codigo_dane_municipio_localidad' AND tipo='$datosTercero->tipo_localidad' AND codigo_dane_localidad='$datosTercero->codigo_dane_localidad'";
        $nombre_localidad_residencia    = SQL::obtenerValor("localidades", "nombre", $condicion);

        $condicion                      = "codigo_iso='$datosTercero->codigo_iso_localidad' AND codigo_dane_departamento='$datosTercero->codigo_dane_departamento_localidad'";
        $condicion                     .= " AND codigo_dane_municipio='$datosTercero->codigo_dane_municipio_localidad'";
        $nombre_municipio_residencia    = SQL::obtenerValor("municipios", "nombre", $condicion);

        $condicion                      = "codigo_iso='$datosTercero->codigo_iso_localidad' AND codigo_dane_departamento='$datosTercero->codigo_dane_departamento_localidad'";
        $nombre_departamento_residencia = SQL::obtenerValor("departamentos", "nombre", $condicion);

        $nombre_pais_residencia         = SQL::obtenerValor("paises", "nombre", "codigo_iso = '$datosTercero->codigo_iso_localidad'");


        // Datos de los selectores
        $llave_primaria_municipio = $datosTercero -> codigo_iso_municipio_documento.",". $datosTercero -> codigo_dane_departamento_documento.",".$datosTercero -> codigo_dane_municipio_documento;
        $descripcion_municipio    = SQL::obtenerValor("seleccion_municipios","nombre","llave_primaria ='$llave_primaria_municipio'");
        $descripcion_municipio    = explode("|",$descripcion_municipio);
        $descripcion_municipio    = $descripcion_municipio[0];

        $llave_primaria_localidad = $datosTercero -> codigo_iso_localidad.",". $datosTercero -> codigo_dane_departamento_localidad.",".$datosTercero -> codigo_dane_municipio_localidad.",".$datosTercero -> tipo_localidad.",".$datosTercero -> codigo_dane_localidad;
        $descripcion_localidad    = SQL::obtenerValor("seleccion_localidades","nombre","llave_primaria ='$llave_primaria_localidad'");
        $descripcion_localidad    = explode("|",$descripcion_localidad);
        $descripcion_localidad    = $descripcion_localidad[0];

        $llave_gran_contrinbuyente = $datos_empresa -> numero_resolucion_contribuyente;
        $descripcion_contribuyente = SQL::obtenerValor("seleccion_resoluciones_contribuyente","descripcion","id ='$llave_gran_contrinbuyente'");
        $descripcion_contribuyente = explode("|",$descripcion_contribuyente);
        $descripcion_contribuyente = $descripcion_contribuyente[0];

        $condicion = "codigo_iso ='$datosTercero->codigo_iso_localidad' AND codigo_dane_departamento = '$datosTercero->codigo_dane_departamento_localidad'";
        $condicion .= " AND codigo_dane_municipio='$datosTercero->codigo_dane_municipio_localidad'";
        $consulta  = SQL::seleccionar(array("actividades_economicas"),array("*"),$condicion);

        if (SQL::filasDevueltas($consulta)){

            while ($datos = SQL::filaEnObjeto($consulta)){

                $llave = $datos->codigo_iso."|".$datos->codigo_dane_departamento."|".$datos->codigo_dane_municipio."|";
                $llave.= $datos->codigo_dian."|".$datos->codigo_actividad_municipio;

                $actividad[$llave] = $datos->descripcion;
            }
        } else {
            $actividad = "";
        }

        // Definicion de pestanas para datos del tercero
        $formularios["PESTANA_TERCERO"] = array(
            array(
                HTML::listaSeleccionSimple("*codigo_tipo_documento", $textos["TIPO_DOCUMENTO_IDENTIDAD"], HTML::generarDatosLista("tipos_documento_identidad", "codigo", "descripcion", "codigo_dian != 0"), $datosTercero->codigo_tipo_documento, array("title" => $textos["AYUDA_DOCUMENTO"]))
            ),
            array(
                HTML::marcaSeleccion("tipo_persona", $textos["PERSONA_NATURAL"], 1, $valor_persona_natural, array("id" => "persona_natural", "onChange" => "activarNombres(1)")),
                HTML::marcaSeleccion("tipo_persona", $textos["PERSONA_JURIDICA"], 2, $valor_persona_juridica, array("id" => "persona_juridica", "onChange" => "activarNombres(2)")),
                HTML::marcaSeleccion("tipo_persona", $textos["CODIGO_INTERNO"], 3, $valor_codigo_interno, array("id" => "codigo_interno", "onChange" => "activarNombres(3)")),
                HTML::marcaSeleccion("tipo_persona", $textos["NATURAL_COMERCIANTE"], 4, $valor_natural_comerciante, array("id" => "natural_comerciante", "onChange" => "activarNombres(4)"))
            ),
            array(
                HTML::campoTextoCorto("*documento_identidad", $textos["DOCUMENTO_TERCERO"], 15, 15, $datosTercero->documento_identidad, array("title" => $textos["AYUDA_TERCERO"], "onchange" => "cargarDatos()", "onKeyPress" => "return campoEntero(event)")),
                HTML::campoTextoCorto("digito_verificacion", $textos["DIGITO_VERIFICACION"], 1, 1, "", array("readonly" => "true", "Class" => "oculto"))
            ),
            array(
                HTML::campoTextoCorto("*selector1", $textos["MUNICIPIO"], 40, 255, $descripcion_municipio, array("title" => $textos["AYUDA_DOCUMENTO_MUNICIPIO"], "class" => "autocompletable"))
                .HTML::campoOculto("codigo_municipio_documento",$llave_primaria_municipio)
            ),
            array(
                HTML::campoTextoCorto("*primer_nombre", $textos["PRIMER_NOMBRE"], 15, 15, $datosTercero->primer_nombre, array("title" => $textos["AYUDA_PRIMER_NOMBRE"], "onblur" => "validarItem(this)", "class" => "$valor_oculto_natural")),
                HTML::campoTextoCorto("segundo_nombre", $textos["SEGUNDO_NOMBRE"], 15, 15, $datosTercero->segundo_nombre, array("title" => $textos["AYUDA_SEGUNDO_NOMBRE"], "onblur" => "validarItem(this)", "class" => "$valor_oculto_natural"))
            ),
            array(
                HTML::campoTextoCorto("*primer_apellido", $textos["PRIMER_APELLIDO"], 15, 15, $datosTercero->primer_apellido, array("title" => $textos["AYUDA_PRIMER_APELLIDO"], "onblur" => "validarItem(this)", "class" => "$valor_oculto_natural")),
                HTML::campoTextoCorto("segundo_apellido", $textos["SEGUNDO_APELLIDO"], 15, 15, $datosTercero->segundo_apellido, array("title" => $textos["AYUDA_SEGUNDO_APELLIDO"], "onblur" => "validarItem(this)", "class" => "$valor_oculto_natural"))
            ),
            array(
                HTML::campoTextoCorto("*razon_social", $textos["RAZON_SOCIAL"], 40, 100, $datosTercero->razon_social, array("title" => $textos["AYUDA_RAZON_SOCIAL"], "onblur" => "validarItem(this)", "class" => "$valor_oculto_juridica"))
            ),
            array(
                HTML::campoTextoCorto("nombre_comercial", $textos["NOMBRE_COMERCIAL"], 40, 60, $datosTercero->nombre_comercial, array("title" => $textos["AYUDA_NOMBRE_COMERCIAL"], "onblur" => "validarItem(this)", "class" => "$valor_oculto_comerciante"))
            )
        );

        // Definicion de pestanas para la ubicacion del tercero
        $formularios["PESTANA_UBICACION_TERCERO"] = array(
            array(
                HTML::campoTextoCorto("*selector2", $textos["LOCALIDAD"], 50, 255, $descripcion_localidad, array("title" => $textos["AYUDA_LOCALIDAD"], "class" => "autocompletable"))
                .HTML::campoOculto("id_municipio_residencia", $llave_primaria_localidad),
                HTML::campoOculto("id_municipio_residencia_anterior", $llave_primaria_localidad)
            ),
            array(
                HTML::campoTextoCorto("*direccion_principal", $textos["DIRECCION"], 50, 50,$datosTercero->direccion_principal, array("title" => $textos["AYUDA_DIRECCION"]))
            ),
            array(
                HTML::campoTextoCorto("telefono_principal", $textos["TELEFONO_PRINCIPAL"], 15, 15,$datosTercero->telefono_principal, array("title" => $textos["AYUDA_TELEFONO_PRINCIPAL"])),
                HTML::campoTextoCorto("fax", $textos["FAX"], 15, 15,$datosTercero->fax , array("title" => $textos["AYUDA_FAX"])),
                HTML::campoTextoCorto("celular", $textos["CELULAR"], 20, 20, $datosTercero->celular, array("title" => $textos["AYUDA_CELULAR"]))
            ),
            array(
                HTML::campoTextoCorto("correo", $textos["CORREO"], 50, 255, $datosTercero->correo, array("title" => $textos["AYUDA_CORREO"]))
            ),
            array(
                HTML::campoTextoCorto("sitio_web", $textos["SITIO_WEB"], 50, 50, $datosTercero->sitio_web, array("title" => $textos["AYUDA_SITIO_WEB"]))
            )
        );

        // Definicion de pestanas general
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::campoTextoCorto("*codigo", $textos["CODIGO"], 4, 4, $datos_empresa->codigo, array("title" => $textos["AYUDA_CODIGO"], "onBlur" => "validarItem(this);", "onKeyPress" => "return campoEntero(event)")),
                HTML::listaSeleccionSimple("*activo", $textos["ACTIVO"], $activo,$datos_empresa->activo, array("title" => $textos["AYUDA_ACTIVO"], "onBlur" => "validarItem(this);"))
            ),
            array(
                HTML::campoTextoCorto("*razon_social_empresa", $textos["RAZON_SOCIAL"], 60, 60,$datos_empresa->razon_social, array("title" => $textos["AYUDA_RAZON_SOCIAL"], "onBlur" => "validarItem(this);"))
            ),
            array(
                HTML::campoTextoCorto("nombre_corto", $textos["NOMBRE_CORTO"], 10, 10, $datos_empresa->nombre_corto, array("title" => $textos["AYUDA_NOMBRE_CORTO"], "onBlur" => "validarItem(this);")),
                HTML::campoTextoCorto("fecha_cierre", $textos["FECHA_CIERRE"], 10, 10, $datos_empresa->fecha_cierre, array("class" => "selectorFecha"), array("title" => $textos["AYUDA_FECHA_CIERRE"], "onBlur" => "validarItem(this);"))
            ),
            array(
                HTML::listaSeleccionSimple("*id_actividad_principal", $textos["ACTIVIDAD_PRINCIPAL"], $actividad, $llave_primaria_actividad_principal, array("title" => $textos["AYUDA_ACTIVIDAD_PRINCIPAL"], "onBlur" => "validarItem(this);"))
            ),
            array(
                HTML::listaSeleccionSimple("*id_actividad_secundaria", $textos["ACTIVIDAD_SECUNDARIA"], $actividad, $llave_primaria_actividad_secundaria, array("title" => $textos["AYUDA_ACTIVIDAD_SECUNDARIA"], "onBlur" => "validarItem(this);"))
            )
        );


         if(!empty($datos_empresa->numero_retefuente)){
                $oculto4                   = "";
                $id_resolucion_fuente      = SQL::obtenerValor("seleccion_resoluciones_retefuente", "descripcion", "id = '$datos_empresa->numero_retefuente'");
                $id_resolucion_fuente      = explode("|",$id_resolucion_fuente);
                $id_resolucion_fuente      = $id_resolucion_fuente[0];
                $mostrar_resolucion_fuente = HTML::mostrarDato("selector6", $textos["RESOLUCION_RETEFUENTE"],$id_resolucion_fuente);
            }else{
                $mostrar_resolucion_fuente = "";
                $oculto1                   = "oculto";
            }


            if($datos_empresa->gran_contribuyente == 1 &&  !empty($datos_empresa->numero_resolucion_contribuyente)){
                $oculto3                     = "";
                $id_resolucion_contribuyente = SQL::obtenerValor("seleccion_resoluciones_contribuyente", "descripcion", "id = '$datos_empresa->numero_resolucion_contribuyente'");
                $id_resolucion_contribuyente = explode("|",$id_resolucion_contribuyente);
                $id_resolucion_contribuyente = $id_resolucion_contribuyente[0];
                $mostrar_contribuyente       = HTML::mostrarDato("selector5", $textos["RESOLUCION_CONTRIBUYENTE"],$id_resolucion_contribuyente);

            }else{

                $oculto3                     = "oculto";
                $mostrar_contribuyente       = "";
                $id_resolucion_contribuyente = "";
            }

        // Definicion pestana tributaria
        $formularios["PESTANA_TRIBUTARIA"] = array(
            array(
                HTML::listaSeleccionSimple("regimen", $textos["REGIMEN"], $regimen, $datos_empresa->regimen, array("title" => $textos["AYUDA_REGIMEN"]))
            ),
            array(
                HTML::marcaChequeo("retiene_fuente", $textos["RETIENE_FUENTE"], $datos_empresa->retiene_fuente, false)
            ),
            array(
                HTML::marcaChequeo("autoretenedor", $textos["AUTORETENEDOR"],$datos_empresa->autoretenedor, false, array("id" => "autoretenedor", "onclick" => "resolucionRetefuente();"))
            ),
            array(
                HTML::campoTextoCorto("selector6", $textos["RESOLUCION_RETEFUENTE"], 40, 255, "", array("title" => $textos["AYUDA_RESOLUCION_RETEFUENTE"], "Class" => "autocompletable oculto"))
                . HTML::campoOculto("resolucion_retefuente", "")
            ),
            array(
                HTML::marcaChequeo("retiene_iva", $textos["RETIENE_IVA"], 1, $datos_empresa->retiene_iva),
            ),
            array(
                HTML::marcaChequeo("retiene_ica", $textos["RETIENE_ICA"], 1, $datos_empresa->retiene_ica)
            ),
            array(
                HTML::marcaChequeo("autoretenedor_ica", $textos["RETENEDOR_ICA"],1,$datos_empresa->autoretenedor_ica, array("id" => "autoretenedor_ica"))
            ),
            array(
                HTML::marcaChequeo("gran_contribuyente", $textos["GRAN_CONTRIBUYENTE"],1, $datos_empresa->gran_contribuyente, array("id" => "gran_contribuyente", "onclick" => "resolucionContribuyente();"))
            ),
            array(
                HTML::campoTextoCorto("selector5", $textos["RESOLUCION_CONTRIBUYENTE"], 40, 255, $descripcion_contribuyente, array("title" => $textos["AYUDA_RESOLUCION_CONTRIBUYENTE"], "Class" => "autocompletable $oculto3"))
                . HTML::campoOculto("resolucion_gran_contribuyente", $llave_gran_contrinbuyente)
            )
        );

        // Definicion de botones
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"],"modificarItem('$url_id');", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones);
    }
    // Enviar datos para la generacion del formulario al script que origino la peticion
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

    // Validar los datos provenientes del formulario
} elseif (!empty($url_validar)) {

     $respuesta = "";

    // Validar codigo
    if ($url_item == "codigo") {
        $existe = SQL::existeItem("empresas", "codigo", $url_valor, "codigo != '$url_id'");
        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_CODIGO"]);
        }
    }

    // Validar razon social
    if ($url_item == "razon_social_empresa") {
        $existe = SQL::existeItem("empresas", "razon_social", $url_valor, "razon_social != '' AND codigo != '$url_id'");
        if ($existe) {
             HTTP::enviarJSON($textos["ERROR_EXISTE_RAZON_SOCIAL"]);
        }
    }

    // Validar nombre_corto
    if ($url_item == "nombre_corto") {
        $existe = SQL::existeItem("empresas", "nombre_corto", $url_valor, "nombre_corto != '' AND codigo != '$url_id'");
        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_NOMBRE_CORTO"]);
        }
    }


    // Adicionar los datos provenientes del formulario
} elseif (!empty($forma_procesar)) {

    // Asumir por defecto que no hubo error
    $error = false;
    $mensaje = $textos["ITEM_MODIFICADO"];

    // validar campos que vienen vacios
    if (!isset($forma_retiene_fuente)) {
        $forma_retiene_fuente = "0";
    }
    if (!isset($forma_autoretenedor)) {
        $forma_autoretenedor = "0";
    }
    if (!isset($forma_retiene_iva)) {
        $forma_retiene_iva = "0";
    }
    if (!isset($forma_retiene_ica)) {
        $forma_retiene_ica = "0";
    }
    if (!isset($forma_autoretenedor_ica)) {
        $forma_autoretenedor_ica = "0";
    }
    if (!isset($forma_gran_contribuyente)) {
        $forma_gran_contribuyente = "0";
    }
    if (empty($forma_resolucion_retefuente)) {
        $forma_resolucion_retefuente = "0";
    }

    if (empty($forma_resolucion_gran_contribuyente)) {
        $forma_resolucion_gran_contribuyente = "0";
    }

    if ($forma_tipo_persona == 1) {
        $forma_razon_social     = "";
        $forma_nombre_comercial = "";
    } elseif ($forma_tipo_persona == 4) {
        $forma_razon_social = "";
    } else {
        $forma_primer_nombre    = "";
        $forma_segundo_nombre   = "";
        $forma_primer_apellido  = "";
        $forma_segundo_apellido = "";
    }

    // Verificar la existencia del tercero

    if(empty($forma_documento_identidad)){
        $error   = true;
        $mensaje = $textos["DOCUMENTO_IDENTIDAD_VACIO"];

    }elseif(empty($forma_tipo_persona)){
        $error   = true;
        $mensaje = $textos["TIPO_PERSONA_VACIO"];

    }elseif(empty($forma_codigo_municipio_documento)){
        $error   = true;
        $mensaje = $textos["MUNICIPO_DOCUMENTO_VACIO"];

    }elseif(empty($forma_id_municipio_residencia)){
        $error   = true;
        $mensaje = $textos["LOCALIDAD_VACIO"];

    }elseif(empty($forma_direccion_principal)){
        $error   = true;
        $mensaje = $textos["DIRECCION_VACIO"];

    }elseif(empty($forma_codigo_tipo_documento)){
        $error   = true;
        $mensaje = $textos["TIPO_DOCUMENTO_VACIO"];

    }elseif(empty($forma_primer_nombre) &&  empty($forma_primer_apellido) && empty($forma_razon_social)){
        $error   = true;
        $mensaje = $textos["NOMBRE_RAZON_VACIO"];

    }elseif(empty($forma_primer_nombre) && !empty($forma_primer_apellido)){
        $error   = true;
        $mensaje = $textos["NOMBRE_VACIO"];

    }elseif(!empty($forma_primer_nombre) && empty($forma_primer_apellido)){
        $error   = true;
        $mensaje = $textos["APELLIDO_VACIO"];

    }elseif(empty($forma_codigo)){
        $error   = true;
        $mensaje = $textos["CODIGO_VACIO"];

    }elseif(empty($forma_razon_social_empresa)){
        $error   = true;
        $mensaje = $textos["RAZON_EMPRESA_VACIO"];

    }elseif(empty($forma_id_actividad_principal)){
        $error   = true;
        $mensaje = $textos["ACTIVIDAD_VACIO"];

    }elseif(empty($forma_id_actividad_secundaria)) {
        $error   = true;
        $mensaje = $textos["ACTIVIDAD_2_VACIO"];

    } elseif (SQL::existeItem("empresas", "codigo", $forma_codigo, "codigo != '' AND codigo!='$forma_id'")) {
        $error   = true;
        $mensaje =  $textos["ERROR_EXISTE_CODIGO"];

    } elseif (!empty($forma_razon_social_empresa) && SQL::existeItem("empresas", "razon_social", $forma_razon_social_empresa, "nombre_corto != '' AND codigo!='$forma_id'")) {
        $error   = true;
        $mensaje =  $textos["ERROR_EXISTE_RAZON_SOCIAL"];

    } elseif (!empty($forma_nombre_corto) && SQL::existeItem("empresas", "nombre_corto", $forma_nombre_corto, "nombre_corto != '' AND codigo!='$forma_id'")) {
        $error   = true;
        $mensaje =  $textos["ERROR_EXISTE_NOMBRE_CORTO"];

    } else {
        // Obtengo obtendo el codigo_municipio_documento
        $id_codigo_municipio_documento = explode(",", $forma_codigo_municipio_documento);
        $codigo_iso_documento          = $id_codigo_municipio_documento[0];
        $codigo_departamento_documento = $id_codigo_municipio_documento[1];
        $codigo_municipio_documento    = $id_codigo_municipio_documento[2];

        // Obtengo los datos de de acuerdo al municipio
        $vistaConsulta = "municipios";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $condicon      = "codigo_iso='$codigo_iso_documento' AND codigo_dane_departamento='$codigo_departamento_documento' AND codigo_dane_municipio = '$codigo_municipio_documento'";
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, $condicon);
        $datos_municipio_documento = SQL::filaEnObjeto($consulta);

        $id_codigo_dane_localidad = explode(",", $forma_id_municipio_residencia);

        $codigo_iso_localidad               = $id_codigo_dane_localidad[0];
        $codigo_dane_departamento_localidad = $id_codigo_dane_localidad[1];
        $codigo_dane_municipio_localidad    = $id_codigo_dane_localidad[2];
        $tipo_localidad                     = $id_codigo_dane_localidad[3];
        $codigo_dane_localidad              = $id_codigo_dane_localidad[4];

        $condicion  ="codigo_iso = '$codigo_iso_localidad' AND codigo_dane_departamento = '$codigo_dane_departamento_localidad'";
        $condicion .= " AND codigo_dane_municipio = '$codigo_dane_municipio_localidad' AND tipo ='$tipo_localidad'";
        $condicion .=" AND codigo_dane_localidad = '$codigo_dane_localidad'";

        // Obtengo los datos de de acuerdo al municipio
        $vistaConsulta = "localidades";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, $condicion);
        $datos_localidad_reside = SQL::filaEnObjeto($consulta);

        $forma_primer_nombre    = trim($forma_primer_nombre);
        $forma_segundo_nombre   = trim($forma_segundo_nombre);
        $forma_primer_apellido  = trim($forma_primer_apellido);
        $forma_segundo_apellido = trim($forma_segundo_apellido);
        $forma_razon_social     = trim($forma_razon_social);
        $forma_nombre_comercial = trim($forma_nombre_comercial);

        // Insertar datos tercero
        $datos = array(
            "documento_identidad"                   => $forma_documento_identidad,
            "codigo_tipo_documento"                 => $forma_codigo_tipo_documento,
            "codigo_iso_municipio_documento"        => $datos_municipio_documento->codigo_iso,
            "codigo_dane_departamento_documento"    => $datos_municipio_documento->codigo_dane_departamento,
            "codigo_dane_municipio_documento"       => $datos_municipio_documento->codigo_dane_municipio,
            "tipo_persona"                          => $forma_tipo_persona,
            "primer_nombre"                         => $forma_primer_nombre,
            "segundo_nombre"                        => $forma_segundo_nombre,
            "primer_apellido"                       => $forma_primer_apellido,
            "segundo_apellido"                      => $forma_segundo_apellido,
            "razon_social"                          => $forma_razon_social,
            "nombre_comercial"                      => $forma_nombre_comercial,
            "fecha_nacimiento"                      => "0000-00-00",
            "codigo_iso_localidad"                  => $datos_localidad_reside->codigo_iso,
            "codigo_dane_departamento_localidad"    => $datos_localidad_reside->codigo_dane_departamento,
            "codigo_dane_municipio_localidad"       => $datos_localidad_reside->codigo_dane_municipio,
            "tipo_localidad"                        => $datos_localidad_reside->tipo,
            "codigo_dane_localidad"                 => $datos_localidad_reside->codigo_dane_localidad,
            "direccion_principal"                   => $forma_direccion_principal,
            "telefono_principal"                    => $forma_telefono_principal,
            "celular"                               => $forma_celular,
            "fax"                                   => $forma_fax,
            "correo"                                => $forma_correo,
            "sitio_web"                             => $forma_sitio_web,
            "genero"                                => "N",
            "activo"                                => $forma_activo
        );

        // Verificar la existencia del tercero
        $existe_tercero = SQL::existeItem("terceros", "documento_identidad", $forma_documento_identidad);

        if(!$existe_tercero){
            $datos["fecha_ingreso"] = date("Y-m-d");
            $consulta = SQL::insertar("terceros", $datos);
        } else {
           $consulta = SQL::modificar("terceros", $datos,"documento_identidad = $forma_documento_identidad");
        }

        // Error de insercion
        if (!$consulta) {
            $error = true;
            $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
        } else {

            // Insertar datos empresa

            // Obtengo los datos de la actividad primaria
            $vistaConsulta = "buscador_actividades_economicas";
            $columnas      = SQL::obtenerColumnas($vistaConsulta);
            $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "id = '$forma_id_actividad_principal'");
            $actividad_primaria = SQL::filaEnObjeto($consulta);

            $codigo_iso_primaria                 = $actividad_primaria -> codigo_iso;
            $codigo_dane_departamento_primaria   = $actividad_primaria -> departamento;
            $codigo_dane_municipio_primaria      = $actividad_primaria -> municipio;
            $codigo_dian_primaria                = $actividad_primaria -> codigo_dian;
            $codigo_actividad_municipio_primaria = $actividad_primaria -> actividad_municipio;

            // Obtengo los datos de la actividad secundaria
            $vistaConsulta = "buscador_actividades_economicas";
            $columnas      = SQL::obtenerColumnas($vistaConsulta);
            $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "id = '$forma_id_actividad_secundaria'");
            $actividad_secundaria = SQL::filaEnObjeto($consulta);

            $codigo_iso_secundaria                 = $actividad_secundaria -> codigo_iso;
            $codigo_dane_departamento_secundaria   = $actividad_secundaria -> departamento;
            $codigo_dane_municipio_secundaria      = $actividad_secundaria -> municipio;
            $codigo_dian_secundaria                = $actividad_secundaria -> codigo_dian;
            $codigo_actividad_municipio_secundaria = $actividad_secundaria -> actividad_municipio;

            $datos = array(
                "codigo"                                => $forma_codigo,
                "razon_social"                          => $forma_razon_social_empresa,
                "nombre_corto"                          => $forma_nombre_corto,
                "fecha_cierre"                          => $forma_fecha_cierre,
                "activo"                                => $forma_activo,
                "documento_identidad_tercero"           => $forma_documento_identidad,
                "regimen"                               => $forma_regimen,
                "retiene_fuente"                        => $forma_retiene_fuente,
                "autoretenedor"                         => $forma_autoretenedor,
                "numero_retefuente"                     => $forma_resolucion_retefuente,
                "retiene_iva"                           => $forma_retiene_iva,
                "retiene_ica"                           => $forma_retiene_ica,
                "autoretenedor_ica"                     => $forma_autoretenedor_ica,
                "gran_contribuyente"                    => $forma_gran_contribuyente,
                "numero_resolucion_contribuyente"       => $forma_resolucion_gran_contribuyente,
                "codigo_iso_primaria"                   => $codigo_iso_primaria,
                "codigo_dane_departamento_primaria"     => $codigo_dane_departamento_primaria,
                "codigo_dane_municipio_primaria"        => $codigo_dane_municipio_primaria,
                "codigo_dian_primaria"                  => $codigo_dian_primaria,
                "codigo_actividad_municipio_primaria"   => $codigo_actividad_municipio_primaria,
                "codigo_iso_secundaria"                 => $codigo_iso_secundaria,
                "codigo_dane_departamento_secundaria"   => $codigo_dane_departamento_secundaria,
                "codigo_dane_municipio_secundaria"      => $codigo_dane_municipio_secundaria,
                "codigo_dian_secundaria"                => $codigo_dian_secundaria,
                "codigo_actividad_municipio_secundaria" => $codigo_actividad_municipio_secundaria
            );

            // Insertar datos de la empresa
            $insertar = SQL::modificar("empresas", $datos,"codigo='$forma_id'");

            // Error de insercion
            if (!$insertar) {
                $error = true;
                $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
            }
        }
    }

    // Enviar datos con la respuesta del proceso al script que origino la peticion
    $respuesta = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

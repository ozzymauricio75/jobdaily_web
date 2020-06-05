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

/*** Devolver datos para autocompletar la búsqueda ***/
if(isset($url_completar)){
    if(($url_item) == "selector1" || // Municipio Expedicion documento - Pestaña Identificacion
       ($url_item) == "selector2" || // Municipio de Nacimiento - Identificacion
       ($url_item) == "selector4" || // Municipio del arrendador - Pestaña Ubicacion
       ($url_item) == "selector5" || // Municipio de mayor estadia - Pestaña Ubicacion
       ($url_item) == "selector6" || // Municipio - Pestaña  Academica
       ($url_item) == "selector7") { // Municipio de Vivienda - Pestaña Vivienda
        echo SQL::datosAutoCompletar("seleccion_municipios", $url_q);
    }

    if(($url_item) == "selector8" ||  // Pestaña Personal
       ($url_item) == "selector9" ||  // Pestaña Conyugue
       ($url_item) == "selector10" || // Pestaña Familiar
       ($url_item) == "selector11") { // Pestaña Referencias
        echo SQL::datosAutoCompletar("seleccion_profesiones", $url_q);
    }
    if(($url_item) == "selector13") { // Pestaña Laboral
        echo SQL::datosAutoCompletar("seleccion_actividades_economicas", $url_q);
    }
    if(($url_item) == "selector14" || ($url_item) == "selector3") { // Pestaña Laboral(14) - Ubicacion(3)
        echo SQL::datosAutoCompletar("seleccion_localidades", $url_q);
    }
    if($url_item == "recomendacion_interna"){
        echo SQL::datosAutoCompletar("seleccion_empleados", $url_q);
    }
    exit;
}

if (isset($url_validarEnTablas)){
    $existe = '0';
    if(!empty($url_valor)){
        if (SQL::existeItem($url_tabla, $url_campo, $url_valor)) {
            $existe = '1';
        }
    }
    HTTP::enviarJSON($existe);
    exit;
}

/*** Generar el formularioo para la captura de datos ***/
if (!empty($url_generar)) {

    if (empty($url_id)) {
         $error     = $textos["ERROR_MODIFICAR_VACIO"];
         $titulo    = "";
         $contenido = "";
    }else{
        $error  = "";
        $titulo = $componente->nombre;

        $tipo_relacion_laboral= array(
            "1" => $textos["ASPIRANTE_LABORAR"],
            "2" => $textos["LABORA_DIRECTAMENTE"],
            "3" => $textos["LABORA_POR_CONTRATO"],
            "4" => $textos["PRESTACION_SERVICIOS"]
        );

        $clase_libreta_militar = array(
            "1" => $textos["NO_TIENE"],
            "2" => $textos["PRIMERA_CLASE"],
            "3" => $textos["SEGUNDA_CLASE"]
        );

        $categoria_pase = array(
            "1" => $textos["NO_TIENE"],
            "2" => $textos["PRIMERA_CATEGORIA"],
            "3" => $textos["SEGUNDA_CATEGORIA"],
            "4" => $textos["TERCERA_CATEGORIA"],
            "5" => $textos["CUARTA_CATEGORIA"],
            "6" => $textos["QUINTA_CATEGORIA"],
            "7" => $textos["SEXTA_CATEGORIA"]
        );

        $tipos_estado_civil = array(
            "1" => $textos["SOLTERO"],
            "2" => $textos["CASADO"],
            "3" => $textos["UNION_LIBRE"],
            "4" => $textos["DIVORCIADO"],
            "5" => $textos["VIUDO"]
        );

        $tipo_derecho_vivienda= array(
            "1" => $textos["ARRENDADA"],
            "2" => $textos["PROPIA"],
            "3" => $textos["FAMILIAR"],
            "4" => $textos["COMODATO"]
        );

        $horarios_trabajo = array(
            "1" => $textos["DIURNO"],
            "2" => $textos["NOCTURNO"],
            "3" => $textos["AMBAS"]
        );

        $horario_educacion= array(
            "1" => $textos["DIURNO"],
            "2" => $textos["NOCTURNO"],
            "3" => $textos["SABATINO"]
        );

        $parentesco = array(
            "1" => $textos["HIJO"],
            "2" => $textos["MADRE"],
            "3" => $textos["PADRE"],
            "4" => $textos["HERMANO"],
            "5" => $textos["ABUELO"],
            "6" => $textos["OTRO"]
        );

        $tipo_vivienda= array(
            "1" => $textos["CASA"],
            "2" => $textos["APARTAMENTO"],
            "3" => $textos["MEJORA"],
            "4" => $textos["LOTE"],
            "5" => $textos["EDIFICIO"]
        );

        $tipo_vehiculo = array(
            "1" => $textos["MOTOCICLETA"],
            "2" => $textos["VEHICULO_PARTICULAR"],
            "3" => $textos["VEHICULO_PUBLICO"],
            "4" => $textos["CAMION_PEQUENO"],
            "5" => $textos["CAMION_GRANDE"],
            "6" => $textos["BUS_COLECTIVO_BUSETA"]
        );

        $preferencia_documento = SQL::obtenerValor("preferencias","valor","variable='tipo_documento_identidad' AND tipo_preferencia='1'");

        $vistaConsulta = "terceros";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "documento_identidad = '$url_id'");
        $datosTerceros = SQL::filaEnObjeto($consulta);

        $vistaConsulta   = "aspirantes";
        $columnas        = SQL::obtenerColumnas($vistaConsulta);
        $consulta        = SQL::seleccionar(array($vistaConsulta), $columnas, "documento_identidad = '$url_id'");
        $datosAspirantes = SQL::filaEnObjeto($consulta);

        if($datosTerceros->genero=='M'){
            $masculino = true;
            $femenino  = false;
        }else{
            $masculino = false;
            $femenino  = true;
        }

        if($datosAspirantes->categoria_permiso_conducir==1){
            $permiso_disabled = "disabled";
        }else{
            $permiso_disabled = "";
        }
        if($datosAspirantes->clase_libreta_militar==1){
            $militar_disabled = "disabled";
        }else{
            $militar_disabled = "";
        }

        $codigo_dane_municipio_documento = $datosTerceros->codigo_iso_municipio_documento.'|'.$datosTerceros->codigo_dane_departamento_documento.'|'.$datosTerceros->codigo_dane_municipio_documento;
        $municipio_documento             = SQL::obtenerValor("seleccion_municipios","nombre","id='".$codigo_dane_municipio_documento."'");
        $municipio_documento             = explode('|',$municipio_documento);
        $municipio_documento             = $municipio_documento[0];

        $codigo_dane_municipio_nacimiento = $datosAspirantes->codigo_iso_nacimiento.'|'.$datosAspirantes->codigo_dane_departamento_nacimiento.'|'.$datosAspirantes->codigo_dane_municipio_nacimiento;
        $municipio_nacimiento             = SQL::obtenerValor("seleccion_municipios","nombre","id='".$codigo_dane_municipio_nacimiento."'");
        $municipio_nacimiento             = explode('|',$municipio_nacimiento);
        $municipio_nacimiento             = $municipio_nacimiento[0];

        // Definición de pestaña identificacion del aspirante

        $formularios["PESTANA_IDENTIFICACION"] = array(
            array(
                HTML::listaSeleccionSimple("*codigo_tipo_documento", $textos["TIPO_DOCUMENTO"], HTML::generarDatosLista("tipos_documento_identidad", "codigo", "descripcion","codigo != 0"), $datosTerceros->codigo_tipo_documento),
                HTML::campoTextoCorto("*documento_identidad", $textos["NUMERO_DOCUMENTO"], 15, 12, $datosAspirantes->documento_identidad, array("title" => $textos["AYUDA_NUMERO_DOCUMENTO"]))
                .HTML::campoOculto("documento_identidad2", $datosAspirantes->documento_identidad)
            ),
            array(
                HTML::campoTextoCorto("*selector1", $textos["MUNICIPIO_EXPEDICION_DOCUMENTO"], 37, 255, $municipio_documento, array("title" => $textos["AYUDA_MUNICIPIO_EXPEDICION_DOCUMENTO"], "class" => "autocompletable"))
                .HTML::campoOculto("codigo_dane_municipio_documento", $codigo_dane_municipio_documento)
            ),
            array(
                HTML::campoTextoCorto("*primer_nombre", $textos["PRIMER_NOMBRE"], 25, 15, $datosTerceros->primer_nombre, array("title" => $textos["AYUDA_PRIMER_NOMBRE"])),
                HTML::campoTextoCorto("segundo_nombre", $textos["SEGUNDO_NOMBRE"], 25, 15, $datosTerceros->segundo_nombre, array("title" => $textos["AYUDA_SEGUNDO_NOMBRE"]))
            ),
            array(
                HTML::campoTextoCorto("*primer_apellido", $textos["PRIMER_APELLIDO"], 25, 20, $datosTerceros->primer_apellido, array("title" => $textos["AYUDA_PRIMER_APELLIDO"])),
                HTML::campoTextoCorto("segundo_apellido", $textos["SEGUNDO_APELLIDO"], 25, 20, $datosTerceros->segundo_apellido, array("title" => $textos["AYUDA_SEGUNDO_APELLIDO"]))
            ),
            array(
                HTML::mostrarDato("nombre_genero", $textos["GENERO"], ""),
                HTML::marcaSeleccion("genero", $textos["GENERO_MASCULINO"], 'M', $masculino, array("id" => "genero_masculino")),
                HTML::marcaSeleccion("genero", $textos["GENERO_FEMENINO"], 'F', $femenino, array("id" => "genero_femenino")),
                HTML::campoTextoCorto("tipo_sangre", $textos["TIPO_SANGRE"], 5, 3, $datosAspirantes->tipo_sangre, array("title" => $textos["AYUDA_TIPO_SANGRE"]))
            ),
            array(
                HTML::campoTextoCorto("fecha_nacimiento", $textos["FECHA_NACIMIENTO"], 10, 10, $datosTerceros->fecha_nacimiento, array("title" => $textos["AYUDA_FECHA_NACIMIENTO"], "class" => "fechaAntigua")),
                HTML::campoTextoCorto("*selector2", $textos["MUNICIPIO_NACIMIENTO"], 40, 255, $municipio_nacimiento, array("title" => $textos["AYUDA_MUNICIPIO_NACIMIENTO"], "class" => "autocompletable"))
                .HTML::campoOculto("codigo_dane_municipio_nacimiento", $codigo_dane_municipio_nacimiento)
            ),
            array(
                HTML::listaSeleccionSimple("estado_civil", $textos["ESTADO_CIVIL"], $tipos_estado_civil, $datosAspirantes->estado_civil, array("title" => $textos["AYUDA_ESTADO_CIVIL"],"onChange" => "manejoEstadoCivil();"))
            ),
            array(
                HTML::listaSeleccionSimple("clase_libreta_militar", $textos["TIPO_LIBRETA"], $clase_libreta_militar, $datosAspirantes->clase_libreta_militar, array("title" => $textos["AYUDA_TIPO_LIBRETA"],"onChange" => "manejoLibreta();")),
                HTML::campoTextoCorto("libreta_militar", $textos["NUMERO_LIBRETA_MILITAR"], 15, 15, $datosAspirantes->libreta_militar, array("title" => $textos["AYUDA_NUMERO_LIBRETA_MILITAR"],"onKeyPress" => "return campoEntero(event);",$militar_disabled => $militar_disabled)),
                HTML::campoTextoCorto("distrito_militar", $textos["DISTRITO_MILITAR"], 6, 3, $datosAspirantes->distrito_militar, array("title" => $textos["AYUDA_DISTRITO_MILITAR"],"onKeyPress" => "return campoEntero(event);",$militar_disabled => $militar_disabled))
            ),
            array(
                HTML::listaSeleccionSimple("categoria_permiso_conducir", $textos["CATEGORIA_PERMISO_CONDUCCION"], $categoria_pase, $datosAspirantes->categoria_permiso_conducir, array("title" => $textos["AYUDA_CATEGORIA_PERMISO_CONDUCCION"],"onChange" => "manejoPase();")),
                HTML::campoTextoCorto("permiso_conducir", $textos["PERMISO_CONDUCCION"], 15, 15, $datosAspirantes->permiso_conducir, array("title" => $textos["AYUDA_PERMISO_CONDUCCION"],"onKeyPress" => "return campoEntero(event);",$permiso_disabled => $permiso_disabled))
            )
        );

        // Definición de pestaña de ubicación del aspirante

        $codigo_dane_municipio_residencia = $datosTerceros->codigo_iso_localidad.'|'.$datosTerceros->codigo_dane_departamento_localidad.'|'.$datosTerceros->codigo_dane_municipio_localidad.'|'.$datosTerceros->tipo_localidad.'|'.$datosTerceros->codigo_dane_localidad;
        $municipio_residencia             = SQL::obtenerValor("seleccion_localidades","nombre","id='".$codigo_dane_municipio_residencia."'");
        $municipio_residencia             = explode('|',$municipio_residencia);
        $municipio_residencia             = $municipio_residencia[0];

        $codigo_dane_municipio_arrendatario = $datosAspirantes->codigo_iso_arrendatario.'|'.$datosAspirantes->codigo_dane_departamento_arrendatario.'|'.$datosAspirantes->codigo_dane_municipio_arrendatario;
        $municipio_arrendatario             = SQL::obtenerValor("seleccion_municipios","nombre","id='".$codigo_dane_municipio_arrendatario."'");
        $municipio_arrendatario             = explode('|',$municipio_arrendatario);
        $municipio_arrendatario             = $municipio_arrendatario[0];

        $codigo_dane_municipio_mayor_estadia = $datosAspirantes->codigo_iso_mayor_estadia.'|'.$datosAspirantes->codigo_dane_departamento_mayor_estadia.'|'.$datosAspirantes->codigo_dane_municipio_mayor_estadia;
        $municipio_mayor_estadia             = SQL::obtenerValor("seleccion_municipios","nombre","id='".$codigo_dane_municipio_mayor_estadia."'");
        $municipio_mayor_estadia             = explode('|',$municipio_mayor_estadia);
        $municipio_mayor_estadia             = $municipio_mayor_estadia[0];

        if($datosAspirantes->derecho_sobre_vivienda!=1){
            $arrendatario_disabled = "disabled";
        }else{
            $arrendatario_disabled = "";
        }

        $formularios["PESTANA_UBICACION"] = array(
            array(
                HTML::campoTextoCorto("*selector3", $textos["BARRIO_RESIDENCIA"], 40, 255, $municipio_residencia, array("title" => $textos["AYUDA_BARRIO_RESIDENCIA"], "class" => "autocompletable"))
                .HTML::campoOculto("codigo_dane_municipio_residencia", $codigo_dane_municipio_residencia)
            ),
            array(
                HTML::campoTextoCorto("direccion", $textos["DIRECCION"], 40, 50, $datosTerceros->direccion_principal, array("title" => $textos["AYUDA_DIRECCION"]))
            ),
            array(
                HTML::campoTextoCorto("telefono", $textos["TELEFONO"], 18, 15, $datosTerceros->telefono_principal, array("title" => $textos["AYUDA_TELEFONO"])),
                HTML::campoTextoCorto("celular", $textos["CELULAR"], 18, 20, $datosTerceros->celular, array("title" => $textos["AYUDA_CELULAR"])),
                HTML::campoTextoCorto("celular2", $textos["CELULAR2"], 18, 20, $datosTerceros->celular2, array("title" => $textos["AYUDA_CELULAR"]))
            ),
            array(
                HTML::campoTextoCorto("fax", $textos["FAX"], 18, 20, $datosTerceros->fax, array("title" => $textos["AYUDA_FAX"])),
                HTML::campoTextoCorto("sitio_web", $textos["SITIO_WEB"], 18, 50, $datosTerceros->sitio_web, array("title" => $textos["AYUDA_SITIO_WEB"]))
            ),
            array(
                HTML::campoTextoCorto("correo_electronico", $textos["CORREO_ELECTRONICO"], 29, 255, $datosTerceros->correo, array("title" => $textos["AYUDA_CORREO_ELECTRONICO"])),
                HTML::campoTextoCorto("correo_electronico2", $textos["CORREO_ELECTRONICO2"], 30, 255, $datosTerceros->correo2, array("title" => $textos["AYUDA_CORREO_ELECTRONICO"]))
            ),
            array(
                HTML::listaSeleccionSimple("derecho_sobre_vivienda", $textos["DERECHO_VIVIENDA"], $tipo_derecho_vivienda, $datosAspirantes->derecho_sobre_vivienda, array("title" => $textos["AYUDA_DERECHO_VIVIENDA"],"onChange" => "manejoDerecho();")),
                HTML::campoTextoCorto("canon_arrendo", $textos["CANON_ARRENDAMIENTO"], 15, 255, $datosAspirantes->canon_arrendo, array("title" => $textos["AYUDA_CANON_ARRENDAMIENTO"],"onKeyPress" => "return campoDecimal(event);",$arrendatario_disabled => $arrendatario_disabled)),
                HTML::campoTextoCorto("fecha_inicio_vivienda", $textos["FECHA_INICIO_VIVIENDA"], 8, 10, $datosAspirantes->fecha_inicio_vivienda, array("title" => $textos["AYUDA_FECHA_INICIO_VIVIENDA"], "class" => "fechaAntigua",$arrendatario_disabled => $arrendatario_disabled))
            ),
            array(
                HTML::campoTextoCorto("nombre_arrendatario", $textos["NOMBRE_ARRENDADOR"], 49, 255, $datosAspirantes->nombre_arrendatario, array("title" => $textos["AYUDA_NOMBRE_ARRENDADOR"],$arrendatario_disabled => $arrendatario_disabled)),
            ),
            array(
                HTML::campoTextoCorto("selector4", $textos["MUNICIPIO_ARRENDADOR"], 29, 255, $municipio_arrendatario, array("title" => $textos["AYUDA_MUNICIPIO_ARRENDADOR"], "class" => "autocompletable",$arrendatario_disabled => $arrendatario_disabled))
                .HTML::campoOculto("codigo_dane_municipio_arrendatario", $codigo_dane_municipio_arrendatario),
                HTML::campoTextoCorto("telefono_arrendatario", $textos["TELEFONO_ARRENDADOR"], 15, 15, $datosAspirantes->telefono_arrendatario, array("title" => $textos["AYUDA_TELEFONO_ARRENDADOR"],$arrendatario_disabled => $arrendatario_disabled))
            ),
            array(
                HTML::campoTextoCorto("*selector5", $textos["MUNICIPIO_MAYOR_ESTADIA"], 40, 255, $municipio_mayor_estadia, array("title" => $textos["AYUDA_MUNICIPIO_MAYOR_ESTADIA"], "class" => "autocompletable"))
                .HTML::campoOculto("codigo_dane_municipio_mayor_estadia", $codigo_dane_municipio_mayor_estadia)
            )
        );

        // Definición de pestaña de información profesional del aspirante

        if($datosAspirantes->pensionado==0){
            $pensionado_no       = true;
            $pensionado_si       = false;
            $pensionado_disabled = "disabled";
        }else{
            $pensionado_no       = false;
            $pensionado_si       = true;
            $pensionado_disabled = "";
        }

        $codigo_dane_profesion = $datosAspirantes->codigo_dane_profesion;
        $profesion             = SQL::obtenerValor("seleccion_profesiones","descripcion","id='".$codigo_dane_profesion."'");
        $profesion             = explode('|',$profesion);
        $profesion             = $profesion[0];

        $formularios["PESTANA_PROFESIONAL"] = array(
            array(
                HTML::campoTextoCorto("*selector8", $textos["PROFESION_OFICIO"], 43, 255, $profesion, array("title" => $textos["AYUDA_PROFESION_OFICIO"], "class" => "autocompletable"))
                .HTML::campoOculto("codigo_dane_profesion", $codigo_dane_profesion),
            ),
            array(
                HTML::listaSeleccionSimple("codigo_cargo", $textos["CARGO"], HTML::generarDatosLista("cargos", "codigo", "nombre", "codigo > '0'"), $datosAspirantes->codigo_cargo, array("title" => $textos["AYUDA_CARGO"]))
            ),
            array(
                HTML::listaSeleccionSimple("relacion_laboral", $textos["TIPO_CONTRATACION"], $tipo_relacion_laboral, $datosAspirantes->relacion_laboral, array("title" => $textos["AYUDA_TIPO_CONTRATACION"])),
                HTML::campoTextoCorto("experiencia_laboral", $textos["EXPERIENCIA_LABORAL"], 15, 20, $datosAspirantes->experiencia_laboral, array("title" => $textos["AYUDA_EXPERIENCIA_LABORAL"])),
                HTML::campoTextoCorto("aspiracion_salarial", $textos["ASPIRACION_SALARIAL"], 10, 14, $datosAspirantes->aspiracion_salarial , array("title" => $textos["AYUDA_ASPIRACION_SALARIAL"],"onKeyPress" => "return campoDecimal(event);")),
                HTML::campoTextoCorto("fecha_ingreso", $textos["FECHA_INGRESO"], 8, 10, $datosAspirantes->fecha_ingreso, array("title" => $textos["AYUDA_FECHA_INGRESO"], "class" => "fechaAntigua"))
            ),
            array(
                HTML::campoTextoCorto("recomendacion_interna", $textos["RECOMENDACION_INTERNA"], 50, 100, $datosAspirantes->recomendacion_interna, array("title" => $textos["AYUDA_RECOMENDACION_INTERNA"],  "class" => "autocompletable"))
                .HTML::campoOculto("id_recomendacion_interna", "")
            ),
            array(
                HTML::mostrarDato("nombre_pensionado", $textos["PENSIONADO"], ""),
                HTML::marcaSeleccion("pensionado", $textos["SI_PENSIONADO"], 1, $pensionado_si, array("id" => "si_pensionado","onChange" => "manejoPension();")),
                HTML::marcaSeleccion("pensionado", $textos["NO_PENSIONADO"], 0, $pensionado_no, array("id" => "no_pensionado","onChange" => "manejoPension();")),
                HTML::campoTextoCorto("ingreso_pension", $textos["INGRESO_PENSION"], 21, 14, $datosAspirantes->ingreso_pension, array("title" => $textos["AYUDA_INGRESO_PENSION"],"onKeyPress" => "return campoDecimal(event);",$pensionado_disabled => $pensionado_disabled)),
            ),
            array(
                HTML::listaSeleccionSimple("codigo_entidad_salud", $textos["EPS_EMPRESA"], HTML::generarDatosLista("entidades_parafiscales", "codigo", "nombre", "salud = '1'"), $datosAspirantes->codigo_entidad_salud, array("title" => $textos["AYUDA_EPS_EMPRESA"])),
                HTML::listaSeleccionSimple("codigo_entidad_pension", $textos["PENSION_EMPRESA"], HTML::generarDatosLista("entidades_parafiscales", "codigo", "nombre", "pension = '1'"), $datosAspirantes->codigo_entidad_pension, array("title" => $textos["AYUDA_PENSION_EMPRESA"])),
                HTML::listaSeleccionSimple("codigo_entidad_cesantias", $textos["CESANTIAS_EMPRESA"], HTML::generarDatosLista("entidades_parafiscales", "codigo", "nombre", "cesantias = '1'"), $datosAspirantes->codigo_entidad_cesantias, array("title" => $textos["AYUDA_CESANTIAS_EMPRESA"]))
            )
        );

        // Definición de pestaña laboral del aspirante

        $consulta      = SQL::seleccionar(array("empresas_aspirante"), array("*"), "documento_identidad_aspirante = '".$datosAspirantes->documento_identidad."'","","consecutivo ASC");
        $items         = array();
        $consecutivo   = 0;

        if (SQL::filasDevueltas($consulta)) {

            while ($datos = SQL::filaEnObjeto($consulta)){

                $actividad = $datos->codigo_iso_actividad.','.$datos->codigo_dane_departamento_actividad.','.$datos->codigo_dane_municipio_actividad.','.$datos->codigo_dian_actividad.','.$datos->codigo_actividad_economica;

                $departamento = SQL::obtenerValor("departamentos_empresa", "nombre", "codigo=".$datos->codigo_departamento_empresa);
                $cargo        = SQL::obtenerValor("cargos", "nombre", "codigo=".$datos->codigo_cargo);
                $contrato     = SQL::obtenerValor("tipos_contrato", "descripcion", "codigo=".$datos->codigo_tipo_contrato);
                $retiro       = SQL::obtenerValor("motivos_retiro", "descripcion", "codigo=".$datos->codigo_motivo_retiro);

                $ocultos = HTML::campoOculto("idPosicionTablaEmpresa[".$datos->consecutivo."]", $datos->consecutivo, array("class" => "idPosicionTablaEmpresa"));
                $ocultos .= HTML::campoOculto("nombreTablaEmpresa[".$datos->consecutivo."]", $datos->nombre, array("class" => "nombreTablaEmpresa"));
                $ocultos .= HTML::campoOculto("tipoActividadEconomicaTablaEmpresa[".$datos->consecutivo."]", $actividad, array("class" => "tipoActividadEconomicaTablaEmpresa"));
                $ocultos .= HTML::campoOculto("direccionTablaEmpresa[".$datos->consecutivo."]", $datos->direccion, array("class" => "direccionTablaEmpresa"));
                $ocultos .= HTML::campoOculto("telefonoTablaEmpresa[".$datos->consecutivo."]", $datos->telefono, array("class" => "telefonoTablaEmpresa"));
                $ocultos .= HTML::campoOculto("departamentoTablaEmpresa[".$datos->consecutivo."]", $datos->codigo_departamento_empresa, array("class" => "departamentoTablaEmpresa"));
                $ocultos .= HTML::campoOculto("cargoTablaEmpresa[".$datos->consecutivo."]", $datos->codigo_cargo, array("class" => "cargoTablaEmpresa"));
                $ocultos .= HTML::campoOculto("jefeInmediatoTablaEmpresa[".$datos->consecutivo."]", $datos->jefe_inmediato, array("class" => "jefeInmediatoTablaEmpresa"));
                $ocultos .= HTML::campoOculto("fechaInicialTablaEmpresa[".$datos->consecutivo."]", $datos->fecha_inicial, array("class" => "fechaInicialTablaEmpresa"));
                $ocultos .= HTML::campoOculto("fechaFinalTablaEmpresa[".$datos->consecutivo."]", $datos->fecha_final, array("class" => "fechaFinalTablaEmpresa"));
                $ocultos .= HTML::campoOculto("horarioTablaEmpresa[".$datos->consecutivo."]", $datos->horario_laboral, array("class" => "horarioTablaEmpresa"));
                $ocultos .= HTML::campoOculto("contratoTablaEmpresa[".$datos->consecutivo."]", $datos->codigo_tipo_contrato, array("class" => "contratoTablaEmpresa"));
                $ocultos .= HTML::campoOculto("motivoRetiroTablaEmpresa[".$datos->consecutivo."]", $datos->codigo_motivo_retiro, array("class" => "motivoRetiroTablaEmpresa"));
                $ocultos .= HTML::campoOculto("logrosTablaEmpresa[".$datos->consecutivo."]", $datos->logros_obtenidos, array("class" => "logrosTablaEmpresa"));

                $remover   = HTML::boton("botonRemoverLaboral", "", "removerItems(this);", "eliminar", array("id" => "botonRemoverLaboral"));

                $celda    = $ocultos.$remover;

                $items[] = array(
                    $datos->consecutivo,
                    $celda,
                    $datos->nombre,
                    $departamento,
                    $cargo,
                    $contrato,
                    $retiro,
                    $datos->logros_obtenidos
                );
                $consecutivo=$datos->consecutivo;
            }
        }
        $consecutivo++;

        $formularios["PESTANA_LABORAL"] = array(
            array(
                HTML::campoTextoCorto("*nombre_empresa", $textos["NOMBRE_EMPRESA"], 50, 255, "", array("title" => $textos["AYUDA_NOMBRE_EMPRESA"])),
                HTML::campoTextoCorto("*selector13", $textos["ACTIVIDAD_ECONOMICA_EMPRESA"], 22, 255, "", array("title" => $textos["AYUDA_ACTIVIDAD_ECONOMICA_EMPRESA"], "class" => "autocompletable"))
                .HTML::campoOculto("codigo_actividad_economica", "")
            ),
            array(
                HTML::campoTextoCorto("direccion_empresa", $textos["DIRECCION_EMPRESA"], 30, 50, "", array("title" => $textos["AYUDA_DIRECCION_EMPRESA"])),
                HTML::campoTextoCorto("telefono_empresa", $textos["TELEFONO_EMPRESA"], 15, 15, "", array("title" => $textos["AYUDA_TELEFONO_EMPRESA"])),
                HTML::listaSeleccionSimple("codigo_departamento_empresa", $textos["DEPARTAMENTO_EMPRESA"], HTML::generarDatosLista("departamentos_empresa", "codigo", "nombre"), "", array("title" => $textos["AYUDA_DEPARTAMENTO_EMPRESA"])),
            ),
            array(
                HTML::listaSeleccionSimple("codigo_cargo_empresa", $textos["CARGO_EMPRESA"], HTML::generarDatosLista("cargos", "codigo", "nombre", "codigo > '0'"), "", array("title" => $textos["AYUDA_CARGO_EMPRESA"])),
                HTML::campoTextoCorto("jefe_inmediato", $textos["JEFE_INMEDAITO"], 30, 50, "", array("title" => $textos["AYUDA_JEFE_INMEDAITO"])),
                HTML::campoTextoCorto("*fecha_inicial_empresa", $textos["FECHA_INICIAL_EMPRESA"], 10, 10, date("Y-m-d"), array("title" => $textos["AYUDA_FECHA_INICIAL_EMPRESA"], "class" => "fechaAntigua")),
                HTML::campoTextoCorto("fecha_final_empresa", $textos["FECHA_FINAL_EMPRESA"], 10, 10, "", array("title" => $textos["AYUDA_FECHA_FINAL_EMPRESA"], "class" => "fechaAntigua"))
            ),
            array(
                HTML::listaSeleccionSimple("codigo_tipo_contrato", $textos["FORMA_CONTRATO"], HTML::generarDatosLista("tipos_contrato", "codigo", "descripcion"), "", array("title" => $textos["AYUDA_FORMA_CONTRATO"])),
                HTML::listaSeleccionSimple("codigo_motivo_retiro", $textos["MOTIVO_RETIRO"], HTML::generarDatosLista("motivos_retiro", "codigo", "descripcion","codigo > '0'"), "", array("title" => $textos["AYUDA_MOTIVO_RETIRO"])),
                HTML::listaSeleccionSimple("horario_laboral_empresa", $textos["HORARIO_EMPRESA"], $horarios_trabajo, 0, array("title" => $textos["AYUDA_HORARIO_EMPRESA"]))
            ),
            array(
                HTML::campoTextoLargo("logros_obtenidos", $textos["LOGROS_OBTENIDOS"], 3, 70, "", array("title" => $textos["AYUDA_LOGROS_OBTENIDOS"]))
                .HTML::campoOculto("lista_empresa", $consecutivo),
                HTML::contenedor(HTML::boton("botonRemoverLaboral", "", "removerItems(this);", "eliminar"), array("id" => "botonRemoverLaboral", "style" => "display: none")),
                HTML::boton("botonAgregar", $textos["AGREGAR"], "agregarItem_laboral('".$textos["ERROR_ACTIVIDAD_NO_EXISTE"]."','".$textos["ERROR_DATOS_VACIOS_LABORAL"]."');", "adicionar")
            ),
            array(
                HTML::generarTabla(
                    array("id","","EMPRESA","ID_DEPARTAMENTO","ID_CARGO","FORMA_CONTRATO","ID_MOTIVO_RETIRO","LOGROS_OBTENIDOS"),
                    $items,
                    array("I","I","I","I","I","I","I"),
                    "listaItemsLaboral",
                    false
                    )
               )
        );

        /*** Definición de pestaña academica del aspirante ***/

        $consulta      = SQL::seleccionar(array("estudios_aspirante"), array("*"), "documento_identidad_aspirante = '".$datosAspirantes->documento_identidad."'","","consecutivo ASC");
        $items         = array();
        $consecutivo   = 0;

        if (SQL::filasDevueltas($consulta)) {

            while ($datos = SQL::filaEnObjeto($consulta)) {

                $municipio = $datos->codigo_iso_estudios.'|'.$datos->codigo_dane_departamento_estudios.'|'.$datos->codigo_dane_municipio_estudios;
                $descripcionM = SQL::obtenerValor("seleccion_municipios", "nombre", "id='".$municipio."'");
                $descripcionM = explode('|',$descripcionM);
                $descripcionM = $descripcionM[0];
                $descripcionE = SQL::obtenerValor("escolaridad", "descripcion", "codigo='".$datos->codigo_escolaridad."'");
                $descripcionE = explode('|',$descripcionE);
                $descripcionE = $descripcionE[0];

                $ocultos = HTML::campoOculto("idPosicionTablaEducacion[".$datos->consecutivo."]", $datos->consecutivo, array("class" => "idPosicionTablaEducacion"));
                $ocultos .= HTML::campoOculto("gradoEducacionTabla[".$datos->consecutivo."]", $datos->codigo_escolaridad, array("class" => "gradoEducacionTabla"));
                $ocultos .= HTML::campoOculto("tituloEducacionTabla[".$datos->consecutivo."]", $datos->titulo, array("class" => "tituloEducacionTabla"));
                $ocultos .= HTML::campoOculto("fechaInicialEducacionTabla[".$datos->consecutivo."]", $datos->fecha_inicio, array("class" => "fechaInicialEducacionTabla"));
                $ocultos .= HTML::campoOculto("fechaFinalEducacionTabla[".$datos->consecutivo."]", $datos->fecha_fin, array("class" => "fechaFinalEducacionTabla"));
                $ocultos .= HTML::campoOculto("intensidadHorariaEducacionTabla[".$datos->consecutivo."]", $datos->intensidad_horaria, array("class" => "intensidadHorariaEducacionTabla"));
                $ocultos .= HTML::campoOculto("horarioEducacionTabla[".$datos->consecutivo."]", $datos->horario, array("class" => "horarioEducacionTabla"));
                $ocultos .= HTML::campoOculto("institutoEducacionTabla[".$datos->consecutivo."]", $datos->institucion, array("class" => "institutoEducacionTabla"));
                $ocultos .= HTML::campoOculto("municipioEducacionTabla[".$datos->consecutivo."]", $municipio, array("class" => "municipioEducacionTabla"));

                $remover   = HTML::boton("botonRemoverEducacion", "", "removerItems(this,'');", "eliminar", array("id" => "botonRemoverEducacion"));

                $celda    = $ocultos.$remover;

                $items[] = array(
                    $datos->consecutivo,
                    $celda,
                    $descripcionE,
                    $datos->titulo,
                    $datos->fecha_inicio,
                    $datos->fecha_fin,
                    $datos->intensidad_horaria,
                    $datos->horario,
                    $datos->institucion,
                    $descripcionM
                );
                $consecutivo=$datos->consecutivo;
            }
            $consecutivo++;
        }

        $formularios["PESTANA_ACADEMICA"] = array(
            array(
                HTML::listaSeleccionSimple("codigo_escolaridad", $textos["NOMBRE_ESCOLARIDAD"], HTML::generarDatosLista("escolaridad", "codigo", "descripcion", "codigo>0"), "", array("title" => $textos["AYUDA_NOMBRE_ESCOLARIDAD"])),
                HTML::campoTextoCorto("*titulo", $textos["NOMBRE_TITULO_EDUCACION"], 30, 255, "", array("title" => $textos["AYUDA_NOMBRE_TITULO_EDUCACION"]))
            ),
            array(
                HTML::campoTextoCorto("fecha_inicial_estudios", $textos["FECHA_INICIAL_EDUCACION"], 10, 10, "", array("title" => $textos["AYUDA_FECHA_INICIAL_EDUCACION"], "class" => "fechaAntigua")),
                HTML::campoTextoCorto("fecha_final_estudios", $textos["FECHA_FINAL_EDUCACION"], 10, 10, "", array("title" => $textos["AYUDA_FECHA_FINAL_EDUCACION"], "class" => "fechaAntigua")),
                HTML::campoTextoCorto("intensidad_horaria_estudios", $textos["INTENSIDAD_HORARIA"], 5, 2, "", array("title" => $textos["AYUDA_INTENSIDAD_HORARIA"],"onKeyPress" => "return campoEntero(event);")),
                HTML::listaSeleccionSimple("horario_estudios", $textos["HORARIO_EDUCACION"], $horario_educacion, 0, array("title" => $textos["AYUDA_HORARIO_EDUCACION"]))
            ),
            array(
                HTML::campoTextoCorto("*institucion", $textos["NOMBRE_INSTITUTO_EDUCACION"], 24, 255, "", array("title" => $textos["AYUDA_NOMBRE_INSTITUTO_EDUCACION"])),
                HTML::campoTextoCorto("*selector6", $textos["MUNICIPIO_INSTITUCION_EDUCACION"], 27, 255, "", array("title" => $textos["AYUDA_MUNICIPIO_INSTITUCION_EDUCACION"], "class" => "autocompletable"))
                .HTML::campoOculto("codigo_dane_municipio_estudios", ""),
                HTML::campoOculto("lista_educacion", $consecutivo),
                HTML::contenedor(HTML::boton("botonRemoverEducacion", "", "removerItems(this);", "eliminar"), array("id" => "botonRemoverEducacion", "style" => "display: none")),
                HTML::boton("botonAgregar", $textos["AGREGAR"], "agregarItem_educacion('".$textos["ERROR_ESCOLARIDAD_NO_EXISTE"]."','".$textos["ERROR_MUNICIPIO_NO_EXISTE"]."','".$textos["ERROR_DATOS_VACIOS_ACADEMICA"]."');", "adicionar")
            ),
            array(
                HTML::generarTabla(
                    array("id","","ID_ESCOLARIDAD","TITULO","FECHA_INICIO","FECHA_FIN","INTENSIDAD_HORARIA","HORARIO","INSTITUCION","ID_MUNICIPIO"),
                    $items,
                    array("I","I","I","I","I","I","I","I","I"),
                    "listaItemsEducacion",
                    false
                    )
               )
        );

        // Definición de pestana de idiomas del aspirante

        $consulta      = SQL::seleccionar(array("idiomas_aspirante"), array("*"), "documento_identidad_aspirante = '".$datosAspirantes->documento_identidad."'","","consecutivo ASC");
        $items         = array();
        $consecutivo   = 0;
        $idiomas = array("",$textos["NO_APLICA"],$textos["LO_HABLA_REGULAR"],$textos["LO_HABLA_BIEN"],$textos["LO_HABLA_EXCELENTE"]);

        if (SQL::filasDevueltas($consulta)) {

            while ($datos = SQL::filaEnObjeto($consulta)) {

                $descripcion = SQL::obtenerValor("idiomas", "descripcion", "codigo=".$datos->codigo_idioma);

                $ocultos = HTML::campoOculto("idPosicionTablaIdioma[".$datos->consecutivo."]", $datos->consecutivo, array("class" => "idPosicionTablaIdioma"));
                $ocultos .= HTML::campoOculto("idiomaTabla[".$datos->consecutivo."]", $datos->codigo_idioma, array("class" => "idiomaTabla"));
                $ocultos .= HTML::campoOculto("idiomaLoHablaTabla[".$datos->consecutivo."]", $datos->habla, array("class" => "idiomaLoHablaTabla"));
                $ocultos .= HTML::campoOculto("idiomaLoLeeTabla[".$datos->consecutivo."]", $datos->escritura, array("class" => "idiomaLoLeeTabla"));
                $ocultos .= HTML::campoOculto("idiomaLoEscribeTabla[".$datos->consecutivo."]", $datos->lectura, array("class" => "idiomaLoEscribeTabla"));

                $remover   = HTML::boton("botonRemoverIdiomas", "", "removerItems(this);", "eliminar", array("id" => "botonRemoverIdiomas"));

                $celda    = $ocultos.$remover;

                $items[] = array(
                    $datos->consecutivo,
                    $celda,
                    $descripcion,
                    $idiomas[$datos->habla],
                    $idiomas[$datos->escritura],
                    $idiomas[$datos->lectura],
                );
                $consecutivo=$datos->consecutivo;
            }
        }
        $consecutivo++;

        $formularios["PESTANA_IDIOMAS"] = array(
            array(
                HTML::listaSeleccionSimple("idiomas", $textos["IDIOMAS"], HTML::generarDatosLista("idiomas", "codigo", "descripcion", "codigo>0"), "", array("title" => $textos["AYUDA_IDIOMAS"]))
            ), array(
                HTML::mostrarDato("idioma_lo_habla_nombre", $textos["LO_HABLA"], ""),
                HTML::marcaSeleccion("idioma_lo_habla", $textos["LO_HABLA_EXCELENTE"], 4, false, array("id" => "habla_excelente"))
                .HTML::campoOculto("habla_excelente_texto", $textos["LO_HABLA_EXCELENTE"]),
                HTML::marcaSeleccion("idioma_lo_habla", $textos["LO_HABLA_BIEN"], 3, false, array("id" => "habla_bien"))
                .HTML::campoOculto("habla_bien_texto", $textos["LO_HABLA_BIEN"]),
                HTML::marcaSeleccion("idioma_lo_habla", $textos["LO_HABLA_REGULAR"], 2, false, array("id" => "habla_regular"))
                .HTML::campoOculto("habla_regular_texto", $textos["LO_HABLA_REGULAR"]),
                HTML::marcaSeleccion("idioma_lo_habla", $textos["NO_APLICA"], 1, true, array("id" => "no_habla"))
                .HTML::campoOculto("no_habla_texto", $textos["NO_APLICA"])
            ),
            array(
                HTML::mostrarDato("idioma_lo_lee_nombre", $textos["LO_LEE"], ""),
                HTML::marcaSeleccion("idioma_lo_lee", $textos["LO_LEE_EXCELENTE"], 4, false, array("id" => "lee_excelente"))
                .HTML::campoOculto("lee_excelente_texto", $textos["LO_LEE_EXCELENTE"]),
                HTML::marcaSeleccion("idioma_lo_lee", $textos["LO_LEE_BIEN"], 3, false, array("id" => "lee_bien"))
                .HTML::campoOculto("lee_bien_texto", $textos["LO_LEE_BIEN"]),
                HTML::marcaSeleccion("idioma_lo_lee", $textos["LO_LEE_REGULAR"], 2, false, array("id" => "lee_regular"))
                .HTML::campoOculto("lee_regular_texto", $textos["LO_LEE_REGULAR"]),
                HTML::marcaSeleccion("idioma_lo_lee", $textos["NO_APLICA"], 1, true, array("id" => "no_lee"))
                .HTML::campoOculto("no_lee_texto", $textos["NO_APLICA"])
            ),
            array(
                HTML::mostrarDato("idioma_lo_escribe_nombre", $textos["LO_ESCRIBE"], ""),
                HTML::marcaSeleccion("idioma_lo_escribe", $textos["LO_ESCRIBE_EXCELENTE"], 4, false, array("id" => "escribe_excelente"))
                .HTML::campoOculto("escribe_excelente_texto", $textos["LO_ESCRIBE_EXCELENTE"]),
                HTML::marcaSeleccion("idioma_lo_escribe", $textos["LO_ESCRIBE_BIEN"], 3, false, array("id" => "escribe_bien"))
                .HTML::campoOculto("escribe_bien_texto", $textos["LO_ESCRIBE_BIEN"]),
                HTML::marcaSeleccion("idioma_lo_escribe", $textos["LO_ESCRIBE_REGULAR"], 2, false, array("id" => "escribe_regular"))
                .HTML::campoOculto("escribe_regular_texto", $textos["LO_ESCRIBE_REGULAR"]),
                HTML::marcaSeleccion("idioma_lo_escribe", $textos["NO_APLICA"], 1, true, array("id" => "no_escribe"))
                .HTML::campoOculto("no_escribe_texto", $textos["NO_APLICA"])
                .HTML::campoOculto("lista_idiomas", $consecutivo),
                HTML::contenedor(HTML::boton("botonRemoverIdiomas", "", "removerItems(this,'idiomas');", "eliminar"), array("id" => "botonRemoverIdiomas", "style" => "display: none")),
            ),
            array (
                HTML::boton("botonAgregar", $textos["AGREGAR"], "agregarItem_idiomaExtranjero('".$textos["ERROR_IDIOMA_EXISTE"]."');", "adicionar")
            ),
            array(
                HTML::generarTabla(
                    array("id","","IDIOMAS","HABLA","LECTURA","ESCRITURA"),
                    $items,
                    array("I","I","I","I","I"),
                    "listaItemsIdiomas",
                    false
                )
            )
        );

        if($datosAspirantes->hojas_calculo==1){
            $hojas_calculo_muy_bien = false;
            $hojas_calculo_bien     = false;
            $hojas_calculo_regular  = false;
            $hojas_calculo_no_sabe  = true;
        }elseif($datosAspirantes->hojas_calculo==2){
            $hojas_calculo_muy_bien = false;
            $hojas_calculo_bien     = false;
            $hojas_calculo_regular  = true;
            $hojas_calculo_no_sabe  = false;
        }elseif($datosAspirantes->hojas_calculo==3){
            $hojas_calculo_muy_bien = false;
            $hojas_calculo_bien     = true;
            $hojas_calculo_regular  = false;
            $hojas_calculo_no_sabe  = false;
        }else{
            $hojas_calculo_muy_bien = true;
            $hojas_calculo_bien     = false;
            $hojas_calculo_regular  = false;
            $hojas_calculo_no_sabe  = false;
        }

        if($datosAspirantes->procesadores_texto==1){
            $procesadores_texto_muy_bien = false;
            $procesadores_texto_bien     = false;
            $procesadores_texto_regular  = false;
            $procesadores_texto_no_sabe  = true;
        }elseif($datosAspirantes->procesadores_texto==2){
            $procesadores_texto_muy_bien = false;
            $procesadores_texto_bien     = false;
            $procesadores_texto_regular  = true;
            $procesadores_texto_no_sabe  = false;
        }elseif($datosAspirantes->procesadores_texto==3){
            $procesadores_texto_muy_bien = false;
            $procesadores_texto_bien     = true;
            $procesadores_texto_regular  = false;
            $procesadores_texto_no_sabe  = false;
        }else{
            $procesadores_texto_muy_bien = true;
            $procesadores_texto_bien     = false;
            $procesadores_texto_regular  = false;
            $procesadores_texto_no_sabe  = false;
        }

        if($datosAspirantes->diseno_diapositivas==1){
            $diapositivas_muy_bien = false;
            $diapositivas_bien     = false;
            $diapositivas_regular  = false;
            $diapositivas_no_sabe  = true;
        }elseif($datosAspirantes->diseno_diapositivas==2){
            $diapositivas_muy_bien = false;
            $diapositivas_bien     = false;
            $diapositivas_regular  = true;
            $diapositivas_no_sabe  = false;
        }elseif($datosAspirantes->diseno_diapositivas==3){
            $diapositivas_muy_bien = false;
            $diapositivas_bien     = true;
            $diapositivas_regular  = false;
            $diapositivas_no_sabe  = false;
        }else{
            $diapositivas_muy_bien = true;
            $diapositivas_bien     = false;
            $diapositivas_regular  = false;
            $diapositivas_no_sabe  = false;
        }

        // Definicion de pestana de sistemas del aspirante
        $formularios["PESTANA_SISTEMAS"] = array(
            array(
                HTML::mostrarDato("hojas_calculo_nombre", $textos["HOJAS_CALCULO"], ""),
                HTML::marcaSeleccion("hojas_calculo", $textos["MUY_BIEN"], 4, $hojas_calculo_muy_bien, array("id" => "hojas_calculo_muy_bien")),
                HTML::marcaSeleccion("hojas_calculo", $textos["BIEN"], 3, $hojas_calculo_bien, array("id" => "hojas_calculo_bien")),
                HTML::marcaSeleccion("hojas_calculo", $textos["REGULAR"], 2, $hojas_calculo_regular, array("id" => "hojas_calculo_regular")),
                HTML::marcaSeleccion("hojas_calculo", $textos["NO_SABE"], 1, $hojas_calculo_no_sabe, array("id" => "hojas_calculo_no_sabe"))
            ),
            array(
                HTML::mostrarDato("procesador_texto_nombre", $textos["PROCESADOR_TEXTO"], ""),
                HTML::marcaSeleccion("procesadores_texto", $textos["MUY_BIEN"], 4, $procesadores_texto_muy_bien, array("id" => "procesadores_texto_muy_bien")),
                HTML::marcaSeleccion("procesadores_texto", $textos["BIEN"], 3, $procesadores_texto_bien, array("id" => "procesadores_texto_bien")),
                HTML::marcaSeleccion("procesadores_texto", $textos["REGULAR"], 2, $procesadores_texto_regular, array("id" => "procesadores_texto_regular")),
                HTML::marcaSeleccion("procesadores_texto", $textos["NO_SABE"], 1, $procesadores_texto_no_sabe, array("id" => "procesadores_texto_no_sabe"))
            ),
            array(
                HTML::mostrarDato("diapositivas_nombre", $textos["DIAPOSITIVAS"], ""),
                HTML::marcaSeleccion("diapositivas", $textos["MUY_BIEN"], 4, $diapositivas_muy_bien, array("id" => "diapositivas_muy_bien")),
                HTML::marcaSeleccion("diapositivas", $textos["BIEN"], 3, $diapositivas_bien, array("id" => "diapositivas_bien")),
                HTML::marcaSeleccion("diapositivas", $textos["REGULAR"], 2, $diapositivas_regular, array("id" => "diapositivas_regular")),
                HTML::marcaSeleccion("diapositivas", $textos["NO_SABE"], 1, $diapositivas_no_sabe, array("id" => "diapositivas_no_sabe"))
            ),
            array(
                HTML::campoTextoCorto("digitador", $textos["DIGITADOR_SISTEMAS"], 60, 255, $datosAspirantes->digitador, array("title" => $textos["AYUDA_CUALES_PROGRAMAS"]))
            ),
            array(
                HTML::campoTextoCorto("programacion", $textos["PROGRAMADOR_SISTEMAS"], 60, 255, $datosAspirantes->programacion, array("title" => $textos["AYUDA_CUALES_LENGUAJES"]))
            )
        );

        // Definicion de pestana personal del conyugue del aspirante

        if($datosAspirantes->estado_civil==1 || $datosAspirantes->estado_civil==4 || $datosAspirantes->estado_civil==5){

            $formularios["PESTANA_CONYUGUE"] = array(
                array(
                    HTML::listaSeleccionSimple("codigo_tipo_documento_conyugue", $textos["TIPO_DOCUMENTO"], HTML::generarDatosLista("tipos_documento_identidad", "codigo", "descripcion","codigo != 0"),$preferencia_documento,array("disabled" => "disabled")),
                    HTML::campoTextoCorto("documento_identidad_conyugue", $textos["NUMERO_DOCUMENTO"], 15, 12, "", array("title" => $textos["AYUDA_NUMERO_DOCUMENTO"], "onblur" => "validarItem(this);","disabled" => "disabled"))
                ),
                array(
                    HTML::campoTextoCorto("primer_nombre_conyugue", $textos["PRIMER_NOMBRE"], 25, 20, "", array("title" => $textos["AYUDA_PRIMER_NOMBRE"],"disabled" => "disabled")),
                    HTML::campoTextoCorto("segundo_nombre_conyugue", $textos["SEGUNDO_NOMBRE"], 25, 20, "", array("title" => $textos["AYUDA_SEGUNDO_NOMBRE"],"disabled" => "disabled"))
                ),
                array(
                    HTML::campoTextoCorto("primer_apellido_conyugue", $textos["PRIMER_APELLIDO"], 25, 20, "", array("title" => $textos["AYUDA_PRIMER_APELLIDO"],"disabled" => "disabled")),
                    HTML::campoTextoCorto("segundo_apellido_conyugue", $textos["SEGUNDO_APELLIDO"], 25, 20, "", array("title" => $textos["AYUDA_SEGUNDO_APELLIDO"],"disabled" => "disabled"))
                ),
                array(
                    HTML::campoTextoCorto("selector9", $textos["PROFESION_OFICIO"], 25, 255, "", array("title" => $textos["AYUDA_PROFESION_OFICIO"], "class" => "autocompletable","disabled" => "disabled"))
                    .HTML::campoOculto("codigo_dane_profesion_conyugue", "")
                ),
                array(
                    HTML::campoTextoCorto("empresa_conyugue", $textos["EMPRESA_CONYUGUE"], 25, 70, "", array("title" => $textos["AYUDA_EMPRESA_CONYUGUE"],"disabled" => "disabled")),
                    HTML::listaSeleccionSimple("codigo_cargo_conyugue", $textos["CARGO_EMPRESA_CONYUGUE"], HTML::generarDatosLista("cargos", "codigo", "nombre"),0,array("disabled" => "disabled")),
                ),
                array(
                    HTML::campoTextoCorto("telefono_conyugue", $textos["TELEFONO"], 25, 20, "", array("title" => $textos["AYUDA_TELEFONO_CONYUGUE"],"disabled" => "disabled")),
                    HTML::campoTextoCorto("celular_conyugue", $textos["CELULAR_CONYUGUE"], 25, 20, "", array("title" => $textos["AYUDA_CELULAR_CONYUGUE"],"disabled" => "disabled"))
                )
            );
        }
        else{

            $consulta    = SQL::seleccionar(array("conyugue_aspirante"), array("*"), "documento_identidad_aspirante = '".$datosAspirantes->documento_identidad."'");
            $datos       = SQL::filaEnObjeto($consulta);
            $descripcion = "";
            if($datos->codigo_dane_profesion != 0){
                $descripcion = SQL::obtenerValor("seleccion_profesiones", "descripcion", "id=".$datos->codigo_dane_profesion);
                $descripcion = explode('|',$descripcion);
                $descripcion = $descripcion[0];
            }else{
                $descripcion                  = "";
                $datos->codigo_dane_profesion = "";
            }

            $formularios["PESTANA_CONYUGUE"] = array(
                array(
                    HTML::listaSeleccionSimple("codigo_tipo_documento_conyugue", $textos["TIPO_DOCUMENTO"], HTML::generarDatosLista("tipos_documento_identidad", "codigo", "descripcion","codigo != 0"),$datos->codigo_tipo_documento),
                    HTML::campoTextoCorto("documento_identidad_conyugue", $textos["NUMERO_DOCUMENTO"], 15, 12, $datos->documento_identidad, array("title" => $textos["AYUDA_NUMERO_DOCUMENTO"], "onblur" => "validarItem(this);"))
                ),
                array(
                    HTML::campoTextoCorto("primer_nombre_conyugue", $textos["PRIMER_NOMBRE"], 25, 20, $datos->primer_nombre, array("title" => $textos["AYUDA_PRIMER_NOMBRE"])),
                    HTML::campoTextoCorto("segundo_nombre_conyugue", $textos["SEGUNDO_NOMBRE"], 25, 20, $datos->segundo_nombre, array("title" => $textos["AYUDA_SEGUNDO_NOMBRE"]))
                ),
                array(
                    HTML::campoTextoCorto("primer_apellido_conyugue", $textos["PRIMER_APELLIDO"], 25, 20, $datos->primer_apellido, array("title" => $textos["AYUDA_PRIMER_APELLIDO"])),
                    HTML::campoTextoCorto("segundo_apellido_conyugue", $textos["SEGUNDO_APELLIDO"], 25, 20, $datos->segundo_apellido, array("title" => $textos["AYUDA_SEGUNDO_APELLIDO"]))
                ),
                array(
                    HTML::campoTextoCorto("selector9", $textos["PROFESION_OFICIO"], 25, 255, $descripcion, array("title" => $textos["AYUDA_PROFESION_OFICIO"], "class" => "autocompletable"))
                    .HTML::campoOculto("codigo_dane_profesion_conyugue", $datos->codigo_dane_profesion)
                ),
                array(
                    HTML::campoTextoCorto("empresa_conyugue", $textos["EMPRESA_CONYUGUE"], 25, 70, $datos->empresa, array("title" => $textos["AYUDA_EMPRESA_CONYUGUE"])),
                    HTML::listaSeleccionSimple("codigo_cargo_conyugue", $textos["CARGO_EMPRESA_CONYUGUE"], HTML::generarDatosLista("cargos", "codigo", "nombre"),$datos->codigo_cargo),
                ),
                array(
                    HTML::campoTextoCorto("telefono_conyugue", $textos["TELEFONO"], 25, 20, $datos->telefono, array("title" => $textos["AYUDA_TELEFONO_CONYUGUE"])),
                    HTML::campoTextoCorto("celular_conyugue", $textos["CELULAR_CONYUGUE"], 25, 20, $datos->celular, array("title" => $textos["AYUDA_CELULAR_CONYUGUE"]))
                )
            );
        }

        // Definicion de pestana Familiar del aspirante

        $consulta      = SQL::seleccionar(array("familia_aspirante"), array("*"), "documento_identidad_aspirante = '".$datosAspirantes->documento_identidad."'","","consecutivo ASC");
        $items         = array();
        $consecutivo   = 0;
        $depende       = array($textos["DEPENDENCIA_ECONOMICA_NO"],$textos["DEPENDENCIA_ECONOMICA_SI"]);
        $genero        = array("M" => $textos["GENERO_MASCULINO"],"F" => $textos["GENERO_FEMENINO"]);


        if (SQL::filasDevueltas($consulta)) {

            while ($datos = SQL::filaEnObjeto($consulta)) {

                $descripcionD = SQL::obtenerValor("tipos_documento_identidad", "descripcion", "codigo=".$datos->codigo_tipo_documento);
                $descripcionP = SQL::obtenerValor("seleccion_profesiones", "descripcion", "id=".$datos->codigo_dane_profesion);
                $descripcionP = explode('|',$descripcionP);
                $descripcionP = $descripcionP[0];

                list($anio,$mes,$dia) = explode("-",$datos->fecha_nacimiento);
                $anio_dif = date("Y") - $anio;
                $mes_dif = date("m")  - $mes;
                $dia_dif = date("d")  - $dia;
                if ($dia_dif < 0 || $mes_dif < 0){
                $anio_dif--;
                }

                $ocultos = HTML::campoOculto("idPosicionTablaFamiliar[".$datos->consecutivo."]", $datos->consecutivo, array("class" => "idPosicionTablaFamiliar"));
                $ocultos.= HTML::campoOculto("id_tipo_documento_familiarTabla[".$datos->consecutivo."]", $datos->codigo_tipo_documento, array("class" => "id_tipo_documento_familiarTabla"));
                $ocultos.= HTML::campoOculto("numero_documento_familiarTabla[".$datos->consecutivo."]", $datos->documento_identidad, array("class" => "numero_documento_familiarTabla"));
                $ocultos.= HTML::campoOculto("nombre_familiarTabla[".$datos->consecutivo."]", $datos->nombre_completo, array("class" => "nombre_familiarTabla"));
                $ocultos.= HTML::campoOculto("id_profesion_familiarTabla[".$datos->consecutivo."]", $datos->codigo_dane_profesion, array("class" => "id_profesion_familiarTabla"));
                $ocultos.= HTML::campoOculto("relacion_familiarTabla[".$datos->consecutivo."]", $datos->parentesco, array("class" => "relacion_familiarTabla"));
                $ocultos.= HTML::campoOculto("fecha_nacimiento_familiarTabla[".$datos->consecutivo."]", $datos->fecha_nacimiento, array("class" => "fecha_nacimiento_familiarTabla"));
                $ocultos.= HTML::campoOculto("genero_familiarTabla[".$datos->consecutivo."]", $datos->genero, array("class" => "genero_familiarTabla"));
                $ocultos.= HTML::campoOculto("dependenciaFamiliarTabla[".$datos->consecutivo."]", $datos->depende_economicamente, array("class" => "dependenciaFamiliarTabla"));

                $remover   = HTML::boton("botonRemoverFamilia", "", "removerItems(this,'');", "eliminar", array("id" => "botonRemoverFamilia"));
                $celda    = $ocultos.$remover;

                $items[] = array(
                    $datos->consecutivo,
                    $celda,
                    $descripcionD,
                    $datos->documento_identidad,
                    $datos->nombre_completo,
                    $descripcionP,
                    $parentesco[$datos->parentesco],
                    $datos->fecha_nacimiento,
                    $anio_dif,
                    $genero[$datos->genero],
                    $depende[$datos->depende_economicamente]
                );
                $consecutivo=$datos->consecutivo;
            }
            $consecutivo++;
        }

        $formularios["PESTANA_FAMILIAR"] = array(
            array(
                HTML::listaSeleccionSimple("codigo_tipo_documento_familiar", $textos["TIPO_DOCUMENTO"], HTML::generarDatosLista("tipos_documento_identidad", "codigo", "descripcion"),$preferencia_documento),
                HTML::campoTextoCorto("documento_identidad_familiar", $textos["NUMERO_DOCUMENTO"], 15, 12, "", array("title" => $textos["AYUDA_NUMERO_DOCUMENTO"])),
                HTML::campoTextoCorto("*nombre_completo_familiar", $textos["NOMBRE_FAMILIAR"], 35, 255, "", array("title" => $textos["AYUDA_NOMBRE_FAMILIAR"]))
            ),
            array(
                HTML::campoTextoCorto("selector10", $textos["PROFESION_OFICIO"], 35, 255, "", array("title" => $textos["AYUDA_PROFESION_OFICIO"], "class" => "autocompletable"))
                .HTML::campoOculto("codigo_dane_profesion_familiar", ""),
                HTML::listaSeleccionSimple("parentesco_familiar", $textos["RELACION_FAMILIAR"], $parentesco, 0, array("title" => $textos["AYUDA_RELACION_FAMILIAR"])),
                HTML::campoTextoCorto("fecha_nacimiento_familiar", $textos["FECHA_NACIMIENTO"], 10, 10, "", array("title" => $textos["AYUDA_FECHA_NACIMIENTO"], "class" => "fechaAntigua"))
            ),
            array(
                HTML::mostrarDato("nombre_genero_familiar", $textos["GENERO"], ""),
                HTML::marcaSeleccion("genero_familiar", $textos["GENERO_MASCULINO"], 'M', false, array("id" => "genero_masculino_familia"))
                .HTML::campoOculto("genero_texto_masculino", $textos["GENERO_MASCULINO"]),
                HTML::marcaSeleccion("genero_familiar", $textos["GENERO_FEMENINO"], 'F', true, array("id" => "genero_femenino_familia"))
                .HTML::campoOculto("genero_texto_femenino", $textos["GENERO_FEMENINO"]),
                HTML::mostrarDato("nombre_dependencia", $textos["DEPENDENCIA_ECONOMICA"], ""),
                HTML::marcaSeleccion("dependencia", $textos["DEPENDENCIA_ECONOMICA_SI"], 1, true, array("id" => "dependencia_economica_si"))
                .HTML::campoOculto("depende_texto_si",$textos["DEPENDENCIA_ECONOMICA_SI"]),
                HTML::marcaSeleccion("dependencia", $textos["DEPENDENCIA_ECONOMICA_NO"], 0, false, array("id" => "dependencia_economica_no"))
                .HTML::campoOculto("depende_texto_no", $textos["DEPENDENCIA_ECONOMICA_NO"]),
                HTML::campoOculto("lista_familiar", $consecutivo),
                HTML::contenedor(HTML::boton("botonRemoverFamilia", "", "removerItems(this,'familiar');", "eliminar"), array("id" => "botonRemoverFamilia", "style" => "display: none")),
                HTML::boton("botonAgregar", $textos["AGREGAR"], "agregarItem_familiar('".$textos["ERROR_EXISTE_FAMILIAR"]."','".$textos["ERROR_DATOS_VACIOS_FAMILIAR"]."','".$textos["ERROR_PROFESION_NO_EXISTE"]."');", "adicionar")
            ),
            array(
                HTML::generarTabla(
                    array("id","","ID_TIPO_DOCUMENTO","DOCUMENTO_IDENTIDAD","NOMBRE_COMPLETO","ID_PROFESION","PARENTESCO","FECHA_NACIMIENTO","EDAD","GENERO","DEPENDE_ECONOMICAMENTE"),
                    $items,
                    array("I","I","I","I","I","I","I","I","I","C"),
                    "listaItemsFamiliar",
                    false
                    )
               )
        );

        if($datosAspirantes->anteojos==1){
            $anteojos_no = true;
            $anteojos_si = false;
        }else{
            $anteojos_no = true;
            $anteojos_si = false;
        }

        $consulta       = SQL::seleccionar(array("aficiones_aspirante"), array("*"), "documento_identidad_aspirante = '".$datosAspirantes->documento_identidad."'");
        $itemsAficiones = array();
        $consecutivo    = 0;

        if (SQL::filasDevueltas($consulta)) {

            while ($datos = SQL::filaEnObjeto($consulta)) {

                $descripcion = SQL::obtenerValor("aficiones", "descripcion", "codigo=".$datos->codigo_aficion);

                $ocultos = HTML::campoOculto("idPosicionTablaAficiones[".$consecutivo."]", $consecutivo, array("class" => "idPosicionTablaAficiones"));
                $ocultos.= HTML::campoOculto("codigoAficion[".$consecutivo."]", $datos->codigo_aficion, array("class" => "codigoAficion"));
                $ocultos.= HTML::campoOculto("descripcionAficion[".$consecutivo."]", $descripcion, array("class" => "descripcionAficion"));

                $remover = HTML::boton("botonRemoverAficion", "", "removerItems(this);", "eliminar", array("id" => "botonRemoverAficion"));
                $celda   = $ocultos.$remover;

                $itemsAficiones[] = array(
                    $consecutivo,
                    $celda,
                    $descripcion
                );
                $consecutivo++;
            }
        }
        $consecutivo++;

        $consulta      = SQL::seleccionar(array("deportes_aspirante"), array("*"), "documento_identidad_aspirante = '".$datosAspirantes->documento_identidad."'");
        $itemsDeportes = array();
        $consecutivo2  = 0;

        if (SQL::filasDevueltas($consulta)) {

            while ($datos = SQL::filaEnObjeto($consulta)) {

                $descripcion = SQL::obtenerValor("deportes", "descripcion", "codigo=".$datos->codigo_deporte);

                $ocultos = HTML::campoOculto("idPosicionTablaDeportes[".$consecutivo2."]", $consecutivo2, array("class" => "idPosicionTablaDeportes"));
                $ocultos.= HTML::campoOculto("codigoDeporte[".$consecutivo2."]", $datos->codigo_deporte, array("class" => "codigoDeporte"));
                $ocultos.= HTML::campoOculto("descripcionDeporte[".$consecutivo2."]", $descripcion, array("class" => "descripcionDeporte"));

                $remover = HTML::boton("botonRemoverDeporte", "", "removerItems(this);", "eliminar", array("id" => "botonRemoverDeporte"));
                $celda   = $ocultos.$remover;

                $itemsDeportes[] = array(
                    $consecutivo2,
                    $celda,
                    $descripcion
                );
                $consecutivo2++;
            }
        }
        $consecutivo2++;

        // Definicion de pestana personal del aspirante
        $formularios["PESTANA_PERSONAL"] = array(
            array(
                HTML::campoTextoCorto("estatura", $textos["ESTATURA"], 6, 3, $datosAspirantes->estatura, array("title" => $textos["AYUDA_ESTATURA"],"onKeyPress" => "return campoEntero(event);")),
                HTML::campoTextoCorto("peso", $textos["PESO"], 6, 3, $datosAspirantes->peso, array("title" => $textos["AYUDA_PESO"],"onKeyPress" => "return campoEntero(event);"))
            ),
            array(
                HTML::marcaSeleccion("anteojos", $textos["SI_ANTEOJOS"], 1, $anteojos_si, array("id" => "usa_anteojos")),
                HTML::marcaSeleccion("anteojos", $textos["NO_ANTEOJOS"], 0, $anteojos_no, array("id" => "no_usa_anteojos"))
            ),
            array(
                HTML::campoTextoCorto("talla_camisa", $textos["TALLA_CAMISA"], 6, 5, $datosAspirantes->talla_camisa, array("title" => $textos["AYUDA_TALLA_CAMISA"])),
                HTML::campoTextoCorto("talla_pantalon", $textos["TALLA_PANTALON"], 6, 5, $datosAspirantes->talla_pantalon, array("title" => $textos["AYUDA_TALLA_PANTALON"])),
                HTML::campoTextoCorto("talla_calzado", $textos["TALLA_CALZADO"], 6, 5, $datosAspirantes->talla_calzado, array("title" => $textos["AYUDA_TALLA_CALZADO"],"onKeyPress" => "return campoEntero(event);"))
            ),
            array(
                HTML::listaSeleccionSimple("codigo_aficion", $textos["LISTA_AFICIONES"], HTML::generarDatosLista("aficiones", "codigo", "descripcion","descripcion!=''"), "", array("title" => $textos["AYUDA_AFICION"]))
                .HTML::campoOculto("lista_aficiones", $consecutivo),
                HTML::listaSeleccionSimple("codigo_deporte", $textos["LISTA_DEPORTES"], HTML::generarDatosLista("deportes", "codigo", "descripcion","descripcion!=''"), "", array("title" => $textos["AYUDA_DEPORTE"]))
                .HTML::campoOculto("lista_deportes", $consecutivo2)
            ),
            array(
                HTML::contenedor(HTML::boton("botonRemoverAficion", "", "removerItems(this,'aficion');", "eliminar"), array("id" => "botonRemoverAficion", "style" => "display: none")),
                HTML::boton("botonAgregar", $textos["AGREGAR"], "agregarItem_aficion();", "adicionar"),
                HTML::contenedor(HTML::boton("botonRemoverDeporte", "", "removerItems(this,'deporte');", "eliminar"), array("id" => "botonRemoverDeporte", "style" => "display: none")),
                HTML::boton("botonAgregar", $textos["AGREGAR"], "agregarItem_deporte();", "adicionar")
            ),
            array(
                HTML::generarTabla(
                    array("id","","AFICION"),
                    $itemsAficiones,
                    array("I","I"),
                    "listaItemsAficion",
                    false
                ),
                HTML::generarTabla(
                    array("id","","DEPORTE"),
                    $itemsDeportes,
                    array("I","I"),
                    "listaItemsDeporte",
                    false
                )
            )
        );

        // Definicion de pestana viviendas del aspirante

        $consulta      = SQL::seleccionar(array("vivienda_aspirante"), array("*"), "documento_identidad_aspirante = '".$datosAspirantes->documento_identidad."'","","consecutivo ASC");
        $items         = array();
        $consecutivo   = 0;
        $hipo          = array($textos["NO_HIPOTECA"],$textos["SI_HIPOTECA"]);

        if (SQL::filasDevueltas($consulta)) {

            while ($datos = SQL::filaEnObjeto($consulta)) {

                $codigo_barrio = $datos->codigo_iso_barrio.'|'.$datos->codigo_dane_departamento_barrio.'|'.$datos->codigo_dane_municipio_barrio.'|'.$datos->tipo_barrio.'|'.$datos->codigo_dane_localidad_barrio;
                $barrio = SQL::obtenerValor("seleccion_localidades", "nombre", "id='".$codigo_barrio."'");
                $barrio = explode('|',$barrio);
                $barrio = $barrio[0];
                $codigo_barrio = str_replace('|',',',$codigo_barrio);

                $ocultos = HTML::campoOculto("idPosicionTablaVivienda[".$datos->consecutivo."]", $datos->consecutivo, array("class" => "idPosicionTablaVivienda"));
                $ocultos.= HTML::campoOculto("id_tipo_vivienda_Tabla[".$datos->consecutivo."]", $datos->tipo, array("class" => "id_tipo_vivienda_Tabla"));
                $ocultos.= HTML::campoOculto("hipoteca_vivienda_Tabla[".$datos->consecutivo."]", $datos->hipoteca, array("class" => "hipoteca_vivienda_Tabla"));
                $ocultos.= HTML::campoOculto("direccion_vivienda_Tabla[".$datos->consecutivo."]", $datos->direccion, array("class" => "direccion_vivienda_Tabla"));
                $ocultos.= HTML::campoOculto("barrio_vivienda_Tabla[".$datos->consecutivo."]", $codigo_barrio, array("class" => "barrio_vivienda_Tabla"));
                $ocultos.= HTML::campoOculto("telefono_vivienda_Tabla[".$datos->consecutivo."]", $datos->telefono, array("class" => "telefono_vivienda_Tabla"));

                $remover   = HTML::boton("botonRemoverVivienda", "", "removerItems(this,'');", "eliminar", array("id" => "botonRemoverVivienda"));
                $celda    = $ocultos.$remover;

                $items[] = array(
                    $datos->consecutivo,
                    $celda,
                    $tipo_vivienda[$datos->tipo],
                    $hipo[$datos->hipoteca],
                    $barrio,
                    $datos->direccion,
                    $datos->telefono
                );
                $consecutivo=$datos->consecutivo;
            }
            $consecutivo++;
        }

        $formularios["PESTANA_VIVIENDA"] = array(
            array(
                HTML::listaSeleccionSimple("tipo_vivienda", $textos["TIPO_VIVIENDA"], $tipo_vivienda, 0, array("title" => $textos["AYUDA_TIPO_VIVIENDA"])),
                HTML::mostrarDato("hipoteca_nombre_vivienda", $textos["HIPOTECA"], ""),
                HTML::marcaSeleccion("hipoteca_vivienda", $textos["SI_HIPOTECA"], 1, false, array("id" => "si_hipoteca"))
                .HTML::campoOculto("hipoteca_texto_si", $textos["SI_HIPOTECA"]),
                HTML::marcaSeleccion("hipoteca_vivienda", $textos["NO_HIPOTECA"], 0, true, array("id" => "no_hipoteca"))
                .HTML::campoOculto("hipoteca_texto_no", $textos["NO_HIPOTECA"])
            ),
            array(
                HTML::campoTextoCorto("telefono_vivienda", $textos["TELEFONO_VIVIENDA"], 20, 15, "", array("title" => $textos["AYUDA_TELEFONO_VIVIENDA"])),
                HTML::campoTextoCorto("direccion_vivienda", $textos["DIRECCION"], 20, 50, "", array("title" => $textos["AYUDA_DIRECCION"]))
            ),
            array(
                HTML::campoTextoCorto("*selector14", $textos["BARRIO_VIVIENDA"], 30, 255, "", array("title" => $textos["AYUDA_BARRIO_VIVIENDA"], "class" => "autocompletable"))
                .HTML::campoOculto("codigo_dane_municipio_localidad_vivienda", ""),
                HTML::contenedor(HTML::boton("botonRemoverVivienda", "", "removerItems(this);", "eliminar"), array("id" => "botonRemoverVivienda", "style" => "display: none"))
                .HTML::campoOculto("lista_vivienda", $consecutivo)
            ),
            array(
                HTML::boton("botonAgregar", $textos["AGREGAR"], "agregarItem_vivienda('".$textos["ERROR_DATOS_VACIOS_VIVIENDA"]."');", "adicionar")
            ),
            array(
                HTML::generarTabla(
                    array("id","","TIPO","HIPOTECA","DIRECCION","BARRIO","TELEFONO"),
                    $items,
                    array("I","I","I","I","I","I"),
                    "listaItemsVivienda",
                    false
                )
            )
        );

        // Definicion de pestana vehiculos del aspirantes

        $consulta      = SQL::seleccionar(array("vehiculo_aspirante"), array("*"), "documento_identidad_aspirante = '".$datosAspirantes->documento_identidad."'","","consecutivo ASC");
        $items         = array();
        $consecutivo   = 0;
        $pignorado     = array($textos["NO_PIGNORADO"],$textos["SI_PIGNORADO"]);

        if (SQL::filasDevueltas($consulta)) {

            while ($datos = SQL::filaEnObjeto($consulta)) {

                $ocultos = HTML::campoOculto("idPosicionTablaVehiculoTabla[".$datos->consecutivo."]", $datos->consecutivo, array("class" => "idPosicionTablaVehiculoTabla"));
                $ocultos.= HTML::campoOculto("id_tipo_vehiculo_Tabla[".$datos->consecutivo."]", $datos->tipo, array("class" => "id_tipo_vehiculo_Tabla"));
                $ocultos.= HTML::campoOculto("modelo_vehiculo_Tabla[".$datos->consecutivo."]", $datos->modelo, array("class" => "modelo_vehiculo_Tabla"));
                $ocultos.= HTML::campoOculto("marca_vehiculo_Tabla[".$datos->consecutivo."]", $datos->marca, array("class" => "marca_vehiculo_Tabla"));
                $ocultos.= HTML::campoOculto("placa_vehiculo_Tabla[".$datos->consecutivo."]", $datos->matricula, array("class" => "placa_vehiculo_Tabla"));
                $ocultos.= HTML::campoOculto("pignorado_vehiculo_Tabla[".$datos->consecutivo."]", $datos->pignorado, array("class" => "pignorado_vehiculo_Tabla"));

                $remover   = HTML::boton("botonRemoverVehiculo", "", "removerItems(this,'');", "eliminar", array("id" => "botonRemoverVehiculo"));
                $celda    = $ocultos.$remover;

                $items[] = array(
                    $datos->consecutivo,
                    $celda,
                    $tipo_vehiculo[$datos->tipo],
                    $datos->marca,
                    $datos->modelo,
                    $datos->matricula,
                    $pignorado[$datos->pignorado]
                );
                $consecutivo=$datos->consecutivo;
            }
            $consecutivo++;
        }
        $formularios["PESTANA_VEHICULO"] = array(
            array(
                HTML::listaSeleccionSimple("tipo_vehiculo", $textos["TIPO_VEHICULO"], $tipo_vehiculo, 0, array("title" => $textos["AYUDA_TIPO_VEHICULO"]))
            ),
            array(
                HTML::campoTextoCorto("modelo_vehiculo", $textos["MODELO_VEHICULO"], 20, 50, "", array("title" => $textos["AYUDA_MODELO_VEHICULO"])),
                HTML::campoTextoCorto("marca_vehiculo", $textos["MARCA_VEHICULO"], 20, 20, "", array("title" => $textos["AYUDA_MARCA_VEHICULO"]))
            ),
            array(
                HTML::campoTextoCorto("*matricula_vehiculo", $textos["PLACA_VEHICULO"], 20, 20, "", array("title" => $textos["AYUDA_PLACA_VEHICULO"])),
                HTML::mostrarDato("nombre_pignorado", $textos["PIGNORADO"], ""),
                HTML::marcaSeleccion("pignorado", $textos["SI_PIGNORADO"], 1, true, array("id" => "vehiculo_pignorado"))
                .HTML::campoOculto("pignorado_texto_si", $textos["SI_PIGNORADO"]),
                HTML::marcaSeleccion("pignorado", $textos["NO_PIGNORADO"], 0, false, array("id" => "vehiculo_no_pignorado"))
                .HTML::campoOculto("pignorado_texto_no", $textos["NO_PIGNORADO"])
                .HTML::campoOculto("lista_vehiculo", $consecutivo),
                HTML::contenedor(HTML::boton("botonRemoverVehiculo", "", "removerItems(this,'vehiculo');", "eliminar"), array("id" => "botonRemoverVehiculo", "style" => "display: none")),
                HTML::boton("botonAgregar", $textos["AGREGAR"], "agregarItem_vehiculo('".$textos["ERROR_DATOS_VACIOS_VEHICULO"]."');", "adicionar")
            ),
            array(
                HTML::generarTabla(
                    array("id","","TIPO_VEHICULO","MARCA_VEHICULO","MODELO_VEHICULO","PLACA_VEHICULO","PIGNORADO"),
                    $items,
                    array("I","I","I","I","I","C"),
                    "listaItemsVehiculo",
                    false
                )
            )
        );

        // Definicion de pestaña de referencia del aspirante

        $consulta      = SQL::seleccionar(array("referencias_aspirante"), array("*"), "documento_identidad_aspirante = '".$datosAspirantes->documento_identidad."'","","consecutivo ASC");
        $items         = array();
        $consecutivo   = 0;

        if (SQL::filasDevueltas($consulta)) {

            while ($datos = SQL::filaEnObjeto($consulta)) {

                $descripcion = SQL::obtenerValor("seleccion_profesiones", "descripcion", "id=".$datos->codigo_dane_profesion);
                $descripcion = explode('|',$descripcion);
                $descripcion = $descripcion[0];

                $ocultos = HTML::campoOculto("idPosicionTablaReferencia[".$datos->consecutivo."]", $datos->consecutivo, array("class" => "idPosicionTablaReferencia"));
                $ocultos.= HTML::campoOculto("nombre_referencia_Tabla[".$datos->consecutivo."]", $datos->nombre, array("class" => "nombre_referencia_Tabla"));
                $ocultos.= HTML::campoOculto("profesion_referencia_Tabla[".$datos->consecutivo."]", $datos->codigo_dane_profesion, array("class" => "profesion_referencia_Tabla"));
                $ocultos.= HTML::campoOculto("direccion_referencia_Tabla[".$datos->consecutivo."]", $datos->direccion, array("class" => "direccion_referencia_Tabla"));
                $ocultos.= HTML::campoOculto("telefono_referencia_Tabla[".$datos->consecutivo."]", $datos->telefono, array("class" => "telefono_referencia_Tabla"));

                $remover   = HTML::boton("botonRemoverReferencia", "", "removerItems(this,'');", "eliminar", array("id" => "botonRemoverReferencia"));
                $celda    = $ocultos.$remover;

                $items[] = array(
                    $datos->consecutivo,
                    $celda,
                    $datos->nombre,
                    $descripcion,
                    $datos->direccion,
                    $datos->telefono
                );
                $consecutivo=$datos->consecutivo;
            }
            $consecutivo++;
        }

        $formularios["PESTANA_REFERENCIAS"] = array(
            array(
                HTML::campoTextoCorto("*nombre_referencia", $textos["NOMBRE_REFERENCIA"], 50, 100, "", array("title" => $textos["AYUDA_NOMBRE_REFERENCIA"])),
                HTML::campoTextoCorto("*selector11", $textos["PROFESION_OFICIO"], 22, 255, "", array("title" => $textos["AYUDA_PROFESION_OFICIO"], "class" => "autocompletable"))
                .HTML::campoOculto("codigo_dane_profesion_referencia", "")
            ), array(
                HTML::campoTextoCorto("direccion_referencia", $textos["DIRECCION_REFERENCIA"], 30, 50, "", array("title" => $textos["AYUDA_DIRECCION_REFERENCIA"])),
                HTML::campoTextoCorto("telefono_referencia", $textos["TELEFONO_REFERENCIA"], 25, 20, "", array("title" => $textos["AYUDA_TELEFONO_REFERENCIA"]))
                .HTML::campoOculto("lista_referencia", $consecutivo),
                HTML::contenedor(HTML::boton("botonRemoverReferencia", "", "removerItems(this);", "eliminar"), array("id" => "botonRemoverReferencia", "style" => "display: none")),
                HTML::boton("botonAgregar", $textos["AGREGAR"], "agregarItem_referencia('".$textos["ERROR_DATOS_VACIOS_REFERENCIA"]."','".$textos["ERROR_PROFESION_NO_EXISTE"]."');", "adicionar")
            ),
            array(
                HTML::generarTabla(
                    array("id","","NOMBRE","ID_PROFESION","DIRECCION","TELEFONO"),
                    $items,
                    array("I","I","I","I","I"),
                    "listaItemsReferencia",
                    false
                )
            )
        );


        // Definicion de botones
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones);

    }

    // Enviar datos para la generacion del formulario al script que origino la peticion

    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

    // Adicionar los datos provenientes del formulario

}elseif (!empty($forma_procesar)) {
    // Asumir por defecto que no hubo error
    $error   = false;
    $mensaje = $textos["ITEM_MODIFICADO"];

    $municipio_documento     = str_replace(',','|',$forma_codigo_dane_municipio_documento);//seleccion_municipios
    $municipio_nacimiento    = str_replace(',','|',$forma_codigo_dane_municipio_nacimiento);//seleccion_municipios
    $municipio_residencia    = str_replace(',','|',$forma_codigo_dane_municipio_residencia);//seleccion_localidades
    $municipio_arrendatario  = "";
    if(isset($forma_selector4)){
        $municipio_arrendatario  = str_replace(',','|',$forma_codigo_dane_municipio_arrendatario);//seleccion_municipios
    }
    $municipio_mayor_estadia = str_replace(',','|',$forma_codigo_dane_municipio_mayor_estadia);//seleccion_municipios
    $profesion               = $forma_codigo_dane_profesion;//seleccion_profeciones
    if($forma_estado_civil==2 || $forma_estado_civil==3){
        $profesion_conyugue  = $forma_codigo_dane_profesion_conyugue;//seleccion_profeciones
    }else{
        $profesion_conyugue  = '0';
    }

    if(empty($profesion_conyugue)){
        $profesion_conyugue  = '0';
    }

    if(!isset($forma_ingreso_pension)){
        $forma_ingreso_pension = "";
    }

    if(!isset($forma_libreta_militar)){
        $forma_libreta_militar = "";
    }

    if(!isset($forma_distrito_militar)){
        $forma_distrito_militar = "";
    }

    if(!isset($forma_permiso_conducir)){
        $forma_permiso_conducir = "";
    }

    if(!isset($forma_fecha_inicio_vivienda)){
        $forma_fecha_inicio_vivienda = "";
    }

    if(!isset($forma_canon_arrendo)){
        $forma_canon_arrendo = "";
    }

    if(!isset($forma_telefono_arrendatario)){
        $forma_telefono_arrendatario = "";
    }

    if(!isset($forma_nombre_arrendatario)){
        $forma_nombre_arrendatario = "";
    }

    if(SQL::existeItem("aspirantes", "documento_identidad", $forma_documento_identidad, "documento_identidad!='".$forma_documento_identidad2."'")){
        $error   = true;
        $mensaje = $textos["ERROR_EXISTE_DOCUMENTO"];
    }elseif (empty($forma_documento_identidad)){
        $error   = true;
        $mensaje = $textos["ERROR_DOCUMENTO_IDENTIDAD_VACIO"];
    }elseif(empty($forma_selector1)){
        $error   = true;
        $mensaje = $textos["ERROR_MUNICIPIO_DOCUMENTO_VACIO"];
    }elseif(empty($forma_primer_nombre)){
        $error   = true;
        $mensaje = $textos["ERROR_PRIMERO_NOMBRE_VACIO"];
    }elseif(empty($forma_primer_apellido)){
        $error   = true;
        $mensaje = $textos["ERROR_PRIMER_APELLIDO_VACIO"];
    }elseif(empty($forma_selector2)){
        $error   = true;
        $mensaje = $textos["ERROR_MUNICIPIO_NACIMIENTO_VACIO"];
    }elseif(empty($forma_selector3)){
        $error   = true;
        $mensaje = $textos["ERROR_MUNICIPIO_RESIDENCIA_VACIO"];
    }elseif(empty($forma_selector5)){
        $error   = true;
        $mensaje = $textos["ERROR_MUNICIPIO_MAYOR_ESTADIA_VACIO"];
    }elseif(empty($forma_selector8)){
        $error   = true;
        $mensaje = $textos["ERROR_PROFESION_OFICIO_VACIO"];
    }elseif($forma_clase_libreta_militar>1 && (empty($forma_libreta_militar) || empty($forma_distrito_militar))){
        $error   = true;
        $mensaje = $textos["ERROR_LIBRETA_VACIO"];
    }elseif($forma_categoria_permiso_conducir>1 && empty($forma_permiso_conducir)){
        $error   = true;
        $mensaje = $textos["ERROR_PERMISO_VACIO"];
    }elseif($forma_pensionado==1 && empty($forma_ingreso_pension)){
        $error   = true;
        $mensaje = $textos["ERROR_PENSIONADO_VACIO"];
    }elseif(!SQL::existeItem("seleccion_municipios", "id", $municipio_documento)){
        $error   = true;
        $mensaje = $textos["ERROR_NO_EXISTE_MUNICIPIO_DOCUMENTO"];
    }elseif(!SQL::existeItem("seleccion_municipios", "id", $municipio_nacimiento)){
        $error   = true;
        $mensaje = $textos["ERROR_NO_EXISTE_MUNICIPIO_NACINIENTO"];
    }elseif(!SQL::existeItem("seleccion_localidades", "id",$municipio_residencia)){
        $error   = true;
        $mensaje = $textos["ERROR_NO_EXISTE_MUNICIPIO_RESIDENCIA"];
    }elseif(!SQL::existeItem("seleccion_municipios", "id", $municipio_arrendatario) && !empty($forma_selector4)){
        $error   = true;
        $mensaje = $textos["ERROR_NO_EXISTE_MUNICIPIO_ARRENDATARIO"];
    }elseif(!SQL::existeItem("seleccion_municipios", "id", $municipio_mayor_estadia)){
        $error   = true;
        $mensaje = $textos["ERROR_NO_EXISTE_MUNICIPIO_MAYOR_ESTADIA"];
    }elseif(!SQL::existeItem("seleccion_profesiones","id",$profesion)){
        $error   = true;
        $mensaje = $textos["ERROR_NO_EXISTE_PROFESION"];
    }elseif(!SQL::existeItem("seleccion_profesiones","id",$profesion_conyugue) && (($forma_estado_civil==2 || $forma_estado_civil==3) && $profesion_conyugue != '0')){
        $error   = true;
        $mensaje = $textos["ERROR_NO_EXISTE_PROFESION_CONYUGUE"];
    }else{

        $municipio_documento_tercero = split('[|,]',$forma_codigo_dane_municipio_documento);
        $municipio_residencia_tercero = split('[|,]',$forma_codigo_dane_municipio_residencia);

        $forma_primer_nombre    = trim($forma_primer_nombre);
        $forma_segundo_nombre   = trim($forma_segundo_nombre);
        $forma_primer_apellido  = trim($forma_primer_apellido);
        $forma_segundo_apellido = trim($forma_segundo_apellido);

        // Insertar los datos del tercero
        $datos = array(
            "documento_identidad"                => $forma_documento_identidad,
            "codigo_tipo_documento"              => $forma_codigo_tipo_documento,
            "codigo_iso_municipio_documento"     => $municipio_documento_tercero[0],
            "codigo_dane_departamento_documento" => $municipio_documento_tercero[1],
            "codigo_dane_municipio_documento"    => $municipio_documento_tercero[2],
            "primer_nombre"                      => $forma_primer_nombre,
            "segundo_nombre"                     => $forma_segundo_nombre,
            "primer_apellido"                    => $forma_primer_apellido,
            "segundo_apellido"                   => $forma_segundo_apellido,
            "fecha_nacimiento"                   => $forma_fecha_nacimiento,
            "codigo_iso_localidad"               => $municipio_residencia_tercero[0],
            "codigo_dane_departamento_localidad" => $municipio_residencia_tercero[1],
            "codigo_dane_municipio_localidad"    => $municipio_residencia_tercero[2],
            "tipo_localidad"                     => $municipio_residencia_tercero[3],
            "codigo_dane_localidad"              => $municipio_residencia_tercero[4],
            "direccion_principal"                => $forma_direccion,
            "telefono_principal"                 => $forma_telefono,
            "celular"                            => $forma_celular,
            "celular2"                           => $forma_celular2,
            "fax"                                => $forma_fax,
            "correo"                             => $forma_correo_electronico,
            "correo2"                            => $forma_correo_electronico2,
            "sitio_web"                          => $forma_sitio_web,
            "genero"                             => $forma_genero
        );
        $insertar_tercero = SQL::modificar("terceros", $datos, "documento_identidad='".$forma_documento_identidad2."'");

        //INSERTAR DATOS DEL ASPIRANTE
        if($forma_codigo_dane_municipio_arrendatario){
            $municipio_arrendatario = split('[|,]',$forma_codigo_dane_municipio_arrendatario);
        }else{
            $municipio_arrendatario = array("","","");
        }
        $municipio_mayor_estadia = split('[|,]',$forma_codigo_dane_municipio_mayor_estadia);
        $municipio_nacimiento    = split('[|,]',$forma_codigo_dane_municipio_nacimiento);

        $datos = array(
            "documento_identidad"                    => $forma_documento_identidad,
            "codigo_cargo"                           => $forma_codigo_cargo,
            "fecha_ingreso"                          => $forma_fecha_ingreso,
            "fecha_inicio_vivienda"                  => $forma_fecha_inicio_vivienda,
            "derecho_sobre_vivienda"                 => $forma_derecho_sobre_vivienda,
            "relacion_laboral"                       => $forma_relacion_laboral,
            "canon_arrendo"                          => $forma_canon_arrendo,
            "nombre_arrendatario"                    => $forma_nombre_arrendatario,
            "codigo_iso_arrendatario"                => $municipio_arrendatario[0],
            "codigo_dane_departamento_arrendatario"  => $municipio_arrendatario[1],
            "codigo_dane_municipio_arrendatario"     => $municipio_arrendatario[2],
            "telefono_arrendatario"                  => $forma_telefono_arrendatario,
            "codigo_iso_mayor_estadia"               => $municipio_mayor_estadia[0],
            "codigo_dane_departamento_mayor_estadia" => $municipio_mayor_estadia[1],
            "codigo_dane_municipio_mayor_estadia"    => $municipio_mayor_estadia[2],
            "codigo_dane_profesion"                  => $forma_codigo_dane_profesion,
            "aspiracion_salarial"                    => $forma_aspiracion_salarial,
            "pensionado"                             => $forma_pensionado,
            "ingreso_pension"                        => $forma_ingreso_pension,
            "experiencia_laboral"                    => $forma_experiencia_laboral,
            "recomendacion_interna"                  => $forma_recomendacion_interna,
            "estatura"                               => $forma_estatura,
            "peso"                                   => $forma_peso,
            "talla_camisa"                           => $forma_talla_camisa,
            "anteojos"                               => $forma_anteojos,
            "talla_pantalon"                         => $forma_talla_pantalon,
            "talla_calzado"                          => $forma_talla_calzado,
            "digitador"                              => $forma_digitador,
            "programacion"                           => $forma_programacion,
            "hojas_calculo"                          => $forma_hojas_calculo,
            "procesadores_texto"                     => $forma_procesadores_texto,
            "diseno_diapositivas"                    => $forma_diapositivas,
            "codigo_iso_nacimiento"                  => $municipio_nacimiento[0],
            "codigo_dane_departamento_nacimiento"    => $municipio_nacimiento[1],
            "codigo_dane_municipio_nacimiento"       => $municipio_nacimiento[2],
            "estado_civil"                           => $forma_estado_civil,
            "clase_libreta_militar"                  => $forma_clase_libreta_militar,
            "libreta_militar"                        => $forma_libreta_militar,
            "distrito_militar"                       => $forma_distrito_militar,
            "permiso_conducir"                       => $forma_permiso_conducir,
            "categoria_permiso_conducir"             => $forma_categoria_permiso_conducir,
            "codigo_entidad_salud"                   => $forma_codigo_entidad_salud,
            "codigo_entidad_pension"                 => $forma_codigo_entidad_pension,
            "codigo_entidad_cesantias"               => $forma_codigo_entidad_cesantias
        );
        $insertar_aspirante = SQL::modificar("aspirantes", $datos, "documento_identidad='".$forma_documento_identidad2."'");

        if(!isset($forma_idPosicionTablaEmpresa)){
            $forma_idPosicionTablaEmpresa = array();
        }

        //Insertar  datos de la tabla :: empresas_aspirante
        SQL::eliminar("empresas_aspirante","documento_identidad_aspirante='".$forma_documento_identidad2."'");
        foreach($forma_idPosicionTablaEmpresa AS $id){

            $consecutivo = (int)SQL::obtenerValor("empresas_aspirante","max(consecutivo)","documento_identidad_aspirante='".$forma_documento_identidad."'");
            if($consecutivo){
                $consecutivo++;
            }else{
                $consecutivo=1;
            }

            $actividad_economica = explode(',',$forma_tipoActividadEconomicaTablaEmpresa[$id]);

            $datos = array(
               "documento_identidad_aspirante"      => $forma_documento_identidad,
               "consecutivo"                        => $consecutivo,
               "nombre"                             => $forma_nombreTablaEmpresa[$id],
               "codigo_iso_actividad"               => $actividad_economica[0],
               "codigo_dane_departamento_actividad" => $actividad_economica[1],
               "codigo_dane_municipio_actividad"    => $actividad_economica[2],
               "codigo_dian_actividad"              => $actividad_economica[3],
               "codigo_actividad_economica"         => $actividad_economica[4],
               "direccion"                          => $forma_direccionTablaEmpresa[$id],
               "telefono"                           => $forma_telefonoTablaEmpresa[$id],
               "codigo_departamento_empresa"        => $forma_departamentoTablaEmpresa[$id],
               "codigo_cargo"                       => $forma_cargoTablaEmpresa[$id],
               "jefe_inmediato"                     => $forma_jefeInmediatoTablaEmpresa[$id],
               "fecha_inicial"                      => $forma_fechaInicialTablaEmpresa[$id],
               "fecha_final"                        => $forma_fechaFinalTablaEmpresa[$id],
               "horario_laboral"                    => $forma_horarioTablaEmpresa[$id],
               "codigo_tipo_contrato"               => $forma_contratoTablaEmpresa[$id],
               "codigo_motivo_retiro"               => $forma_motivoRetiroTablaEmpresa[$id],
               "logros_obtenidos"                   => $forma_logrosTablaEmpresa[$id]
            );
            $insertar = SQL::insertar("empresas_aspirante",$datos);
        }
        //Fin Insertar

        if(!isset($forma_idPosicionTablaEducacion)){
            $forma_idPosicionTablaEducacion = array();
        }

        //Insertar  datos de la tabla :: estudios_aspirante
        SQL::eliminar("estudios_aspirante","documento_identidad_aspirante='".$forma_documento_identidad2."'");
        foreach($forma_idPosicionTablaEducacion AS $id){

            $consecutivo = (int)SQL::obtenerValor("estudios_aspirante","max(consecutivo)","documento_identidad_aspirante='".$forma_documento_identidad."'");

            if($consecutivo){
                $consecutivo++;
            }else{
                $consecutivo=1;
            }

            $municipio_educacion = split('[|,]',$forma_municipioEducacionTabla[$id]);

            $datos = array(
                "documento_identidad_aspirante"     => $forma_documento_identidad,
                "consecutivo"                       => $consecutivo,
                "codigo_escolaridad"                => $forma_gradoEducacionTabla[$id],
                "titulo"                            => $forma_tituloEducacionTabla[$id],
                "fecha_inicio"                      => $forma_fechaInicialEducacionTabla[$id],
                "fecha_fin"                         => $forma_fechaFinalEducacionTabla[$id],
                "codigo_iso_estudios"               => $municipio_educacion[0],
                "codigo_dane_departamento_estudios" => $municipio_educacion[1],
                "codigo_dane_municipio_estudios"    => $municipio_educacion[2],
                "intensidad_horaria"                => $forma_intensidadHorariaEducacionTabla[$id],
                "horario"                           => $forma_horarioEducacionTabla[$id],
                "institucion"                       => $forma_institutoEducacionTabla[$id]
            );
            $insertar = SQL::insertar("estudios_aspirante",$datos);
        }
        //Fin Insertar

        if(!isset($forma_idPosicionTablaIdioma)){
            $forma_idPosicionTablaIdioma = array();
        }

        //Insertar  datos de la tabla :: idiomas_aspirante
        SQL::eliminar("idiomas_aspirante","documento_identidad_aspirante='".$forma_documento_identidad2."'");
        foreach($forma_idPosicionTablaIdioma AS $id){

            $consecutivo = (int)SQL::obtenerValor("idiomas_aspirante","max(consecutivo)","documento_identidad_aspirante='".$forma_documento_identidad."'");

            if($consecutivo){
                $consecutivo++;
            }else{
                $consecutivo=1;
            }

            $datos = array(
                "documento_identidad_aspirante" => $forma_documento_identidad,
                "consecutivo"                   => $consecutivo,
                "codigo_idioma"                 => $forma_idiomaTabla[$id],
                "habla"                         => $forma_idiomaLoHablaTabla[$id],
                "escritura"                     => $forma_idiomaLoEscribeTabla[$id],
                "lectura"                       => $forma_idiomaLoLeeTabla[$id]
            );
            $insertar = SQL::insertar("idiomas_aspirante",$datos);
        }
        //Fin Insertar

        //Insertar  datos de la tabla :: conyugue_aspirante
        SQL::eliminar("conyugue_aspirante","documento_identidad_aspirante='".$forma_documento_identidad2."'");
        if($forma_estado_civil==2 || $forma_estado_civil==3){
            if(!isset($forma_documento_identidad_conyugue)){
                $forma_documento_identidad_conyugue = "";
            }
            $datos = array(
                "documento_identidad_aspirante" => $forma_documento_identidad,
                "codigo_tipo_documento"         => $forma_codigo_tipo_documento_conyugue,
                "documento_identidad"           => $forma_documento_identidad_conyugue,
                "primer_nombre"                 => $forma_primer_nombre_conyugue,
                "segundo_nombre"                => $forma_segundo_nombre_conyugue,
                "primer_apellido"               => $forma_primer_apellido_conyugue,
                "segundo_apellido"              => $forma_segundo_apellido_conyugue,
                "telefono"                      => $forma_telefono_conyugue,
                "codigo_dane_profesion"         => $profesion_conyugue,
                "codigo_cargo"                  => $forma_codigo_cargo_conyugue,
                "empresa"                       => $forma_empresa_conyugue,
                "celular"                       => $forma_celular_conyugue
            );
            $insertar = SQL::insertar("conyugue_aspirante", $datos);
        }
        //Fin Insertar

        if(!isset($forma_idPosicionTablaFamiliar)){
            $forma_idPosicionTablaFamiliar = array();
        }

        //Insertar  datos de la tabla :: familia_aspirante
        SQL::eliminar("familia_aspirante","documento_identidad_aspirante='".$forma_documento_identidad2."'");
        foreach($forma_idPosicionTablaFamiliar AS $id){

            $consecutivo = (int)SQL::obtenerValor("familia_aspirante","max(consecutivo)","documento_identidad_aspirante='".$forma_documento_identidad."'");

            if($consecutivo){
                $consecutivo++;
            }else{
                $consecutivo=1;
            }

            $datos = array(
                "documento_identidad_aspirante" => $forma_documento_identidad,
                "consecutivo"                   => $consecutivo,
                "codigo_tipo_documento"         => $forma_id_tipo_documento_familiarTabla[$id],
                "documento_identidad"           => $forma_numero_documento_familiarTabla[$id],
                "nombre_completo"               => $forma_nombre_familiarTabla[$id],
                "codigo_dane_profesion"         => $forma_id_profesion_familiarTabla[$id],
                "parentesco"                    => $forma_relacion_familiarTabla[$id],
                "fecha_nacimiento"              => $forma_fecha_nacimiento_familiarTabla[$id],
                "genero"                        => $forma_genero_familiarTabla[$id],
                "depende_economicamente"        => $forma_dependenciaFamiliarTabla[$id]
            );
            $insertar = SQL::insertar("familia_aspirante",$datos);
        }
        //Fin Insertar

        if(!isset($forma_idPosicionTablaAficiones)){
            $forma_idPosicionTablaAficiones = array();
        }

        //Insertar  datos de la tabla :: aficiones_aspirante
        SQL::eliminar("aficiones_aspirante","documento_identidad_aspirante='".$forma_documento_identidad2."'");
        foreach($forma_idPosicionTablaAficiones AS $id){

            $datos = array(
                "documento_identidad_aspirante" => $forma_documento_identidad,
                "codigo_aficion"                => $forma_codigoAficion[$id]
            );
            $insertar = SQL::insertar("aficiones_aspirante",$datos);
        }
        //Fin Insertar

        if(!isset($forma_idPosicionTablaDeportes)){
            $forma_idPosicionTablaDeportes = array();
        }

        //Insertar  datos de la tabla :: deportes_aspirante
        SQL::eliminar("deportes_aspirante","documento_identidad_aspirante='".$forma_documento_identidad2."'");
        foreach($forma_idPosicionTablaDeportes AS $id){

            $datos = array(
                "documento_identidad_aspirante" => $forma_documento_identidad,
                "codigo_deporte"                => $forma_codigoDeporte[$id]
            );
            $insertar = SQL::insertar("deportes_aspirante",$datos);
        }
        //Fin Insertar

        if(!isset($forma_idPosicionTablaVivienda)){
            $forma_idPosicionTablaVivienda = array();
        }

        //Insertar  datos de la tabla :: vivienda_aspirante
        SQL::eliminar("vivienda_aspirante","documento_identidad_aspirante='".$forma_documento_identidad2."'");
        foreach($forma_idPosicionTablaVivienda AS $id){

            $consecutivo = (int)SQL::obtenerValor("vivienda_aspirante","max(consecutivo)","documento_identidad_aspirante='".$forma_documento_identidad."'");

            if($consecutivo){
                $consecutivo++;
            }else{
                $consecutivo=1;
            }

            $municipio_barrio = split('[|,]',$forma_barrio_vivienda_Tabla[$id]);

            $datos = array(
                "documento_identidad_aspirante"      => $forma_documento_identidad,
                "consecutivo"                        => $consecutivo,
                "tipo"                               => $forma_id_tipo_vivienda_Tabla[$id],
                "hipoteca"                           => $forma_hipoteca_vivienda_Tabla[$id],
                "direccion"                          => $forma_direccion_vivienda_Tabla[$id],
                "codigo_iso_barrio"                  => $municipio_barrio[0],
                "codigo_dane_departamento_barrio"    => $municipio_barrio[1],
                "codigo_dane_municipio_barrio"       => $municipio_barrio[2],
                "tipo_barrio"                        => $municipio_barrio[3],
                "codigo_dane_localidad_barrio"       => $municipio_barrio[4],
                "telefono"                           => $forma_telefono_vivienda_Tabla[$id]
            );
            $insertar = SQL::insertar("vivienda_aspirante",$datos);
        }
        //Fin Insertar

        if(!isset($forma_idPosicionTablaVehiculoTabla)){
            $forma_idPosicionTablaVehiculoTabla = array();
        }

        //Insertar  datos de la tabla :: vehiculo_aspirante
        SQL::eliminar("vehiculo_aspirante","documento_identidad_aspirante='".$forma_documento_identidad2."'");
        foreach($forma_idPosicionTablaVehiculoTabla AS $id){

            $consecutivo = (int)SQL::obtenerValor("vehiculo_aspirante","max(consecutivo)","documento_identidad_aspirante='".$forma_documento_identidad."'");

            if($consecutivo){
                $consecutivo++;
            }else{
                $consecutivo=1;
            }

            $datos = array(
                "documento_identidad_aspirante" => $forma_documento_identidad,
                "consecutivo"                   => $consecutivo,
                "tipo"                          => $forma_id_tipo_vehiculo_Tabla[$id],
                "matricula"                     => $forma_placa_vehiculo_Tabla[$id],
                "modelo"                        => $forma_modelo_vehiculo_Tabla[$id],
                "marca"                         => $forma_marca_vehiculo_Tabla[$id],
                "pignorado"                     => $forma_pignorado_vehiculo_Tabla[$id]
            );
            $insertar = SQL::insertar("vehiculo_aspirante",$datos);
        }
        //Fin Insertar

        if(!isset($forma_idPosicionTablaReferencia)){
            $forma_idPosicionTablaReferencia = array();
        }

        //Insertar  datos de la tabla :: referencias_aspirante
        SQL::eliminar("referencias_aspirante","documento_identidad_aspirante='".$forma_documento_identidad2."'");
        foreach($forma_idPosicionTablaReferencia AS $id){

            $consecutivo = (int)SQL::obtenerValor("referencias_aspirante","max(consecutivo)","documento_identidad_aspirante='".$forma_documento_identidad."'");

            if($consecutivo){
                $consecutivo++;
            }else{
                $consecutivo=1;
            }

            $datos = array(
                "documento_identidad_aspirante" => $forma_documento_identidad,
                "consecutivo"                   => $consecutivo,
                "nombre"                        => $forma_nombre_referencia_Tabla[$id],
                "codigo_dane_profesion"         => $forma_profesion_referencia_Tabla[$id],
                "direccion"                     => $forma_direccion_referencia_Tabla[$id],
                "telefono"                      => $forma_telefono_referencia_Tabla[$id]
            );
            $insertar = SQL::insertar("referencias_aspirante",$datos);
        }
        //Fin Insertar
    }
    // Enviar datos con la respuesta del proceso al script que origino la peticion
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

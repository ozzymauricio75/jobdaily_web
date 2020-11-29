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

/*** Devolver datos para autocompletar la búsqueda ***/
if (isset($url_completar)) {

    if (($url_item) == "selector1") {
        echo SQL::datosAutoCompletar("seleccion_municipios", $url_q);
    }
    if (($url_item) == "selector2") {
        echo SQL::datosAutoCompletar("seleccion_localidades", $url_q);
    }
    if ($url_item == "selector3") {
        echo SQL::datosAutoCompletar("seleccion_bancos", $url_q);
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

            $llave_municipio = $datos->codigo_iso_municipio_documento."|".$datos->codigo_dane_departamento_documento."|".$datos->codigo_dane_municipio_documento;
            $nombre_municipio_documento = SQL::obtenerValor("seleccion_municipios","nombre","id='$llave_municipio'");
            $nombre_municipio_documento = explode("|",$nombre_municipio_documento);
            $nombre_municipio_documento = $nombre_municipio_documento[0];
            $llave_municipio = str_replace("|",",",$llave_municipio);

            $llave_localidad  = $datos->codigo_iso_localidad."|".$datos->codigo_dane_departamento_localidad."|".$datos->codigo_dane_municipio_localidad."|".$datos->tipo_localidad."|".$datos->codigo_dane_localidad;
            $nombre_localidad = SQL::obtenerValor("seleccion_localidades","nombre","id='$llave_localidad'");
            $nombre_localidad = explode("|",$nombre_localidad);
            $nombre_localidad = $nombre_localidad[0];
            $llave_localidad = str_replace("|",",",$llave_localidad);

            $tabla = array(
                $datos->codigo_tipo_documento,
                $llave_municipio,
                /*$datos->codigo_iso_municipio_documento,
                $datos->codigo_dane_departamento_documento,
                $datos->codigo_dane_municipio_documento,*/
                $datos->tipo_persona,
                $datos->primer_nombre,
                $datos->segundo_nombre,
                $datos->primer_apellido,
                $datos->segundo_apellido,
                $datos->razon_social,
                $datos->nombre_comercial,
                $datos->fecha_nacimiento,
                $llave_localidad,/*
                $datos->codigo_iso_localidad,
                $datos->codigo_dane_departamento_localidad,
                $datos->codigo_dane_municipio_localidad,
                $datos->tipo_localidad,
                $datos->codigo_dane_localidad,*/
                $datos->direccion_principal,
                $datos->telefono_principal,
                $datos->celular,
                $datos->fax,
                $datos->correo,
                $datos->sitio_web,
                $nombre_municipio_documento,
                $nombre_localidad
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

if (isset($url_recargarActividad)){

    $llave_municipio          = explode(",",$url_id_localidad);
    $codigo_iso               = $llave_municipio[0];
    $codigo_dane_departamento = $llave_municipio[1];
    $codigo_dane_municipio    = $llave_municipio[2];
    $condicion                = "codigo_iso='$codigo_iso' AND codigo_dane_departamento='$codigo_dane_departamento' AND codigo_dane_municipio='$codigo_dane_municipio'";    
    $consulta = SQL::seleccionar(array("actividades_economicas"),array("*"),$condicion);

    if (SQL::filasDevueltas($consulta)){

        $actividad[0] = "";
        while ($datos = SQL::filaEnObjeto($consulta)){

            $llave = $datos->codigo_iso."|".$datos->codigo_dane_departamento."|".$datos->codigo_dane_municipio."|";
            $llave .= $datos->codigo_dian."|".$datos->codigo_actividad_municipio;

            $actividad[$llave] = $datos->descripcion;
        }
    } else {
        $actividad = "";
    }    
    HTTP::enviarJSON($actividad);
}

/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {

    $consulta_actividades = SQL::seleccionar(array("actividades_economicas"),array("*"));
    if (SQL::filasDevueltas($consulta_actividades)){
        while($datos_actividades = SQL::filaEnObjeto($consulta_actividades)){

            $codigo_iso_principal                 = $datos_actividades->codigo_iso;
            $codigo_dane_departamento_principal   = $datos_actividades->codigo_dane_departamento;
            $codigo_dane_municipio_principal      = $datos_actividades->codigo_dane_municipio;
            $codigo_dian_principal                = $datos_actividades->codigo_dian;
            $codigo_actividad_municipio_principal = $datos_actividades->codigo_actividad_municipio;

            $llave = $codigo_iso_principal."|".$codigo_dane_departamento_principal."|";
            $llave .= $codigo_dane_municipio_principal."|".$codigo_dian_principal."|".$codigo_actividad_municipio_principal;

            $actividades[$llave] = $datos_actividades->descripcion;
        }
    }

    $consulta_tipos_documentos = SQL::seleccionar(array("tipos_documento_identidad"), array("*"),"codigo != 0");
    if (SQL::filasDevueltas($consulta_tipos_documentos)){
        while($datos_tipos_documentos = SQL::filaEnObjeto($consulta_tipos_documentos)){
            $tipos_documentos[$datos_tipos_documentos->codigo] = $datos_tipos_documentos->descripcion;
        }
    }

    $consulta_servicios = SQL::seleccionar(array("servicios"), array("*"),"codigo != 0");
    if (SQL::filasDevueltas($consulta_servicios)){
        while($datos_servicios = SQL::filaEnObjeto($consulta_servicios)){
            $servicios[$datos_servicios->codigo] = $datos_servicios->descripcion;
        }
    }
    
    $consulta_municipios = SQL::seleccionar(array("municipios"), array("*"),"codigo_dane_municipio != ''");
    if (SQL::filasDevueltas($consulta_municipios)){
        $municipios = true;
    }

    $consulta_localidades = SQL::seleccionar(array("localidades"), array("*"),"codigo_dane_localidad != ''");
    if (SQL::filasDevueltas($consulta_localidades)){
        $localidades = true;
    }

    $consulta_plazos = SQL::seleccionar(array("plazos_pago_proveedores"), array("*"),"codigo != 0");
    if (SQL::filasDevueltas($consulta_plazos)){
        while($datos_plazos = SQL::filaEnObjeto($consulta_plazos)){
            $plazos_pagos[$datos_plazos->codigo] = $datos_plazos->nombre;
        }
    }

    if (isset($actividades) && isset($tipos_documentos) && isset($servicios) && isset($municipios) && isset($localidades) && isset($plazos_pagos)){

        $error  = "";
        $titulo = $componente->nombre;

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
        
        $inicio_cobro = array(
            "1" => $textos["FECHA_FACTURA"],
            "2" => $textos["FECHA_RECIBO"]
        );
        $forma_iva = array(
            "1" => $textos["TOTAL"],
            "2" => $textos["PRIMERA_CUOTA"],
            "3" => $textos["SEPARADO"],
            "4" => $textos["DISTRIBUIDO"]
        );

        $forma_liquidacion_tasa_credito = array(
            "1" => $textos["DESPUES_LINEA"],
            "2" => $textos["DESPUES_GLOBAL"]
        );

        $tipo_cuenta = array(
            "1" => $textos["AHORROS"],
            "2" => $textos["CORRIENTE"]
        );

        /*** Definición de pestañas para datos del tercero***/
        $formularios["PESTANA_TERCERO"] = array(
            array(
                HTML::listaSeleccionSimple("*codigo_tipo_documento", $textos["TIPO_DOCUMENTO_IDENTIDAD"], $tipos_documentos,"",array("title" => $textos["AYUDA_TIPO_DOCUMENTO"]))
            ),
            array(
                HTML::marcaSeleccion("tipo_persona", $textos["PERSONA_NATURAL"], 1, true, array("id" => "persona_natural", "onChange" => "activarNombres(1)")),
                HTML::marcaSeleccion("tipo_persona", $textos["PERSONA_JURIDICA"], 2, false, array("id" => "persona_juridica", "onChange" => "activarNombres(2)")),
                HTML::marcaSeleccion("tipo_persona", $textos["CODIGO_INTERNO"], 3, false, array("id" => "codigo_interno", "onChange" => "activarNombres(3)")),
                HTML::marcaSeleccion("tipo_persona", $textos["NATURAL_COMERCIANTE"], 4, false, array("id" => "natural_comerciante", "onChange" => "activarNombres(4)"))
            ),
            array(
                HTML::campoTextoCorto("*documento_identidad", $textos["DOCUMENTO_PROVEEDOR"], 15, 15, "",array("title" => $textos["AYUDA_PROVEEDOR"],"onblur" => "validarItem(this);","onchange" => "cargarDatos()","onKeyPress" => "return campoEntero(event)")),
                HTML::campoTextoCorto("digito_verificacion", $textos["DIGITO_VERIFICACION"], 1, 1, "", array("readonly" => "true","Class" => "oculto"))
            ),
            array(
                HTML::campoTextoCorto("*selector1", $textos["MUNICIPIO"], 38, 255, "", array("title" => $textos["AYUDA_DOCUMENTO_MUNICIPIO"], "class" => "autocompletable"))
                .HTML::campoOculto("id_municipio_documento", "")
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
                HTML::campoTextoCorto("*razon_social", $textos["RAZON_SOCIAL"], 40, 100, "", array("title" => $textos["AYUDA_RAZON_SOCIAL"], "onblur" => "validarItem(this)", "class" => "oculto"))
            ),array(
                HTML::campoTextoCorto("nombre_comercial", $textos["NOMBRE_COMERCIAL"], 40, 60, "", array("title" => $textos["AYUDA_NOMBRE_COMERCIAL"], "onblur" => "validarItem(this)", "class" => "oculto"))
            )
        );

        /*** Definición de pestañas para la ubicación del tercero***/
        $formularios["PESTANA_UBICACION_TERCERO"] = array(
            array(
                HTML::campoTextoCorto("*selector2", $textos["LOCALIDAD"], 50, 255, "", array("title" => $textos["AYUDA_LOCALIDAD"], "class" => "autocompletable"))
                .HTML::campoOculto("id_localidad", "")
            ),
            array(
                HTML::campoTextoCorto("*direccion_principal", $textos["DIRECCION"], 50, 50, "", array("title" => $textos["AYUDA_DIRECCION"]))
            ),
            array(
                HTML::campoTextoCorto("telefono_principal", $textos["TELEFONO_PRINCIPAL"], 13, 15, "", array("title" => $textos["AYUDA_TELEFONO_PRINCIPAL"])),
                HTML::campoTextoCorto("fax", $textos["FAX"], 13, 15, "", array("title" => $textos["AYUDA_FAX"])),
                HTML::campoTextoCorto("*celular", $textos["CELULAR"], 14, 20, "", array("title" => $textos["AYUDA_CELULAR"]))
            ),
            array(
                HTML::campoTextoCorto("correo", $textos["CORREO"], 50, 255, "", array("title" => $textos["AYUDA_CORREO"]))
            ),
            array(
                HTML::campoTextoCorto("sitio_web", $textos["SITIO_WEB"], 50, 50, "", array("title" => $textos["AYUDA_SITIO_WEB"]))
            )
        );

        /*** Definición de pestaña tributaria ***/
        $formularios["PESTANA_TRIBUTARIA"] = array(
            array(
                HTML::listaSeleccionSimple("regimen", $textos["REGIMEN"], $regimen, "", array("title" => $textos["AYUDA_REGIMEN"]))
            ),
            array(
                HTML::marcaChequeo("autoretenedor", $textos["AUTORETENEDOR"])
            ),
            array(
                HTML::marcaChequeo("retiene_fuente", $textos["RETIENE_FUENTE"])
            ),
            array(
                HTML::marcaChequeo("gran_contribuyente", $textos["GRAN_CONTRIBUYENTE"])
            ),
            array(
                HTML::marcaChequeo("retiene_iva", $textos["RETIENE_IVA"])
            ),
            array(
                HTML::marcaChequeo("autoretenedor_ica", $textos["AUTORETENEDOR_ICA"])
            ),
            array(
                HTML::marcaChequeo("retiene_ica", $textos["RETIENE_ICA"])
            ),
            /*array(
                HTML::listaSeleccionSimple("*forma_iva", $textos["FORMA_IVA"], $forma_iva, "1", array("title" => $textos["AYUDA_FORMA_IVA"]))
            )*/
        );
        
        /*** Definición de pestaña PROVEEDOR ***/
        //$funciones["PESTANA_PROVEEDOR"] = "recargarActividades()";
        $formularios["PESTANA_PROVEEDOR"] = array(
            /*array(
                HTML::listaSeleccionSimple("id_actividad_principal", $textos["ACTIVIDAD_PRINCIPAL"], $actividades, array("title" => $textos["AYUDA_ACTIVIDAD_PRINCIPAL"],"onBlur" => "validarItem(this);"))
            ),
            array(
                HTML::listaSeleccionSimple("id_actividad_secundaria", $textos["ACTIVIDAD_SECUNDARIA"], $actividades, "", array("title" => $textos["AYUDA_ACTIVIDAD_SECUNDARIA"],"onBlur" => "validarItem(this);"))
            ),
            array(
                HTML::marcaChequeo("fabricante", $textos["FABRICANTE"])
            ),
            array(
                HTML::marcaChequeo("ditribuidor", $textos["DISTRIBUIDOR"])
            ),
            array(
                HTML::marcaChequeo("servicios_tecnicos", $textos["SERVICIOS_TECNICOS"])
            ),
            array(
                HTML::marcaChequeo("transporte", $textos["TRANSPORTE"])
            ),
            array(
                HTML::marcaChequeo("publicidad", $textos["PUBLICIDAD"])
            ),
            array(
                HTML::marcaChequeo("servicios_especiales", $textos["SERVICIOS_ESPECIALES"])
            ),*/
            array(
                HTML::listaSeleccionSimple("codigo_servicio", $textos["TIPO_SERVICIO"], $servicios,"", array("title" => $textos["AYUDA_TIPO_SERVICIO"]))
            ),
            array(
                HTML::campoTextoCorto("tiempo_respuesta", $textos["TIEMPO_RESPUESTA"], 3, 3, "", array("title" => $textos["AYUDA_TIEMPO_RESPUESTA"],"onBlur" => "validarItem(this);", "onKeyPress" => "return campoEntero(event)")),
            ),
            array(
                HTML::campoTextoCorto("porcentaje_flete", $textos["PORCENTAJE_FLETE"], 9, 6, "", array("title" => $textos["AYUDA_PORCENTAJE_FLETE"],"onBlur" => "validarItem(this);","onKeyPress" => "return campoDecimal(event)")),
                HTML::campoTextoCorto("valor_flete", $textos["VALOR_FLETE"], 10, 10, "", array("title" => $textos["AYUDA_VALOR_FLETE"],"onBlur" => "validarItem(this);","onKeyPress" => "return campoEntero(event)"))
            ),
            array(
                HTML::campoTextoCorto("porcentaje_seguro", $textos["PORCENTAJE_SEGURO"], 9, 6, "", array("title" => $textos["AYUDA_PORCENTAJE_SEGURO"],"onBlur" => "validarItem(this);","onKeyPress" => "return campoDecimal(event)")),
                HTML::campoTextoCorto("valor_seguro", $textos["VALOR_SEGURO"], 10, 10, "", array("title" => $textos["AYUDA_VALOR_SEGURO"],"onBlur" => "validarItem(this);","onKeyPress" => "return campoEntero(event)"))
            )
        );

        /*** Definición de pestaña PAGOS ***/
        $formularios["PESTANA_PAGOS"] = array(
            array(
                HTML::listaSeleccionSimple("codigo_plazo_pago_contado", $textos["FORMA_PAGO_CONTADO"], $plazos_pagos,"",array("title" => $textos["AYUDA_PAGO_CONTADO"]))
            )
        );

        /*** Definición de pestaña cuentas bancarias ***/
        $formularios["PESTANA_CUENTAS"] = array(
            array(
                HTML::campoTextoCorto("selector3", $textos["BANCO"], 50, 255, "", array("title" => $textos["AYUDA_BANCO"], "class" => "autocompletable"))
                .HTML::campoOculto("codigo_banco", "")
            ),
            array(
                HTML::campoTextoCorto("cuenta", $textos["CUENTA"], 50, 50, "", array("title" => $textos["AYUDA_CUENTA"]))
            ),
            array(
                HTML::listaSeleccionSimple("tipo_cuenta", $textos["TIPO_CUENTA"], $tipo_cuenta)
                .HTML::campoOculto("orden_lista_cuentas", "0"),
                HTML::boton("botonAgregarCuenta", $textos["AGREGAR"], "agregarItemCuenta();", "adicionar"),
                HTML::contenedor(HTML::boton("botonRemoverCuenta", "", "removerItem(this);", "eliminar"), array("id" => "removedorCuenta", "style" => "display: none"))
            ),
            array(
                HTML::generarTabla( array("id","","BANCO","CUENTA","TIPO_CUENTA"),
                                    "",
                                    array("I","I","D","I"),
                                    "lista_items_cuentas",
                                    false)
            )
        );

        /*** Definición de botones ***/
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar"),
        );

        $contenido = HTML::generarPestanas($formularios, $botones, "", $funciones);
    } else {

        $error = $textos["NO_EXISTEN_REGISTROS"];
        if (!isset($actividades)){
            $error .= $textos["ERROR_CREAR_ACTIVIDADES"];
        }
        if (!isset($tipos_documentos)){
            $error .= $textos["ERROR_CREAR_TIPOS_DOCUMENTOS"];
        }
        if (!isset($servicios)){
            $error .= $textos["ERROR_CREAR_SERVICIOS"];
        }
        if (!isset($municipios)){
            $error .= $textos["ERROR_CREAR_MUNICIPIOS"];
        }
        if (!isset($localidades)){
            $error .= $textos["ERROR_CREAR_LOCALIDADES"];
        }
        if (!isset($plazos_pagos)){
            $error .= $textos["ERROR_PLAZOS_PAGOS"];
        }

        $error .= $textos["CREAR_REGISTROS"];        
        $titulo    = "";
        $contenido = "";
    }

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

    /*** Validar el ingreso de campos requeridos ***/
   if(empty($forma_documento_identidad)){
		$error   = true;
		$mensaje = $textos["DOCUMENTO_IDENTIDAD_VACIO"];
	}elseif(empty($forma_tipo_persona)){
		$error   = true;
		$mensaje = $textos["TIPO_PERSONA_VACIO"];
	}elseif(empty($forma_id_municipio_documento) || empty($forma_selector1)){
		$error   = true;
		$mensaje = $textos["MUNICIPO_DOCUMENTO_VACIO"];
	}elseif(empty($forma_id_localidad) || empty($forma_selector2)){
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
	}elseif(!empty($forma_correo) && !Cadena::validarCorreo($forma_correo)){
		$error   = true;
		$mensaje = $textos["ERROR_SINTAXIS_CORREO"];
    }elseif($existe_tercero = SQL::existeItem("terceros", "documento_identidad", $forma_documento_identidad,"documento_identidad !=0")){
        $error   = true;
        $mensaje = $textos["ERROR_DOCUMENTO_IDENTIDAD_EXISTE"];    
    } else {

        $existe_tercero = SQL::existeItem("terceros", "documento_identidad", $forma_documento_identidad);
        if ($existe_tercero) {
            $idAsignado = SQL::obtenerValor("terceros", "documento_identidad", "documento_identidad = '$forma_documento_identidad'");
        }

        if ($forma_tipo_persona == 1){
            $forma_razon_social     = "";
            $forma_nombre_comercial = "";
        }elseif($forma_tipo_persona == 4){
            $forma_razon_social = "";
        }else{
            $forma_primer_nombre    = "";
            $forma_segundo_nombre   = "";
            $forma_primer_apellido  = "";
            $forma_segundo_apellido = "";
        }

        $forma_id_municipio_documento       = explode(",",$forma_id_municipio_documento);
        $codigo_iso_documento               = $forma_id_municipio_documento[0];
        $codigo_dane_departamento_documento = $forma_id_municipio_documento[1];
        $codigo_dane_municipio_documento    = $forma_id_municipio_documento[2];

        $forma_id_localidad                 = explode(",",$forma_id_localidad);
        $codigo_iso_localidad               = $forma_id_localidad[0];
        $codigo_dane_departamento_localidad = $forma_id_localidad[1];
        $codigo_dane_municipio_localidad    = $forma_id_localidad[2];
        $tipo_localidad                     = $forma_id_localidad[3];
        $codigo_dane_localidad              = $forma_id_localidad[4];

		/*** Insertar datos ***/
        $datos = array(
            "documento_identidad"                => $forma_documento_identidad,
            "tipo_persona"                       => $forma_tipo_persona,
            "codigo_tipo_documento"              => $forma_codigo_tipo_documento,
            "primer_nombre"                      => $forma_primer_nombre,
            "segundo_nombre"                     => $forma_segundo_nombre,
            "primer_apellido"                    => $forma_primer_apellido,
            "segundo_apellido"                   => $forma_segundo_apellido,
            "razon_social"                       => $forma_razon_social,
            "nombre_comercial"                   => $forma_nombre_comercial,
            "codigo_iso_municipio_documento"     => $codigo_iso_documento,
            "codigo_dane_departamento_documento" => $codigo_dane_departamento_documento,
            "codigo_dane_municipio_documento"    => $codigo_dane_municipio_documento,
            "codigo_iso_localidad"               => $codigo_iso_localidad,
            "codigo_dane_departamento_localidad" => $codigo_dane_departamento_localidad,
            "codigo_dane_municipio_localidad"    => $codigo_dane_municipio_localidad,
            "tipo_localidad"                     => $tipo_localidad,
            "codigo_dane_localidad"              => $codigo_dane_localidad,
            "direccion_principal"                => $forma_direccion_principal,
            "telefono_principal"                 => $forma_telefono_principal,
            "fax"                                => $forma_fax,
            "celular"                            => $forma_celular,
            "correo"                             => $forma_correo,
            "sitio_web"                          => $forma_sitio_web,
            "fecha_ingreso"                      => date("Y-m-d H:i:s")
        );

        if (!$existe_tercero) {
            $insertar = SQL::insertar("terceros", $datos);
            /*** Error de inserción ***/
            if (!$insertar) {
                $error   = true;
                $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
            }
        }else{
            $modificar = SQL::modificar("terceros", $datos, "documento_identidad = '$forma_documento_identidad'");
            if ($modificar) {
                $error   = false;
                $mensaje = $textos["ITEM_MODIFICADO"];
            } else {
                $error   = true;
                $mensaje = $textos["ERROR_MODIFICAR_ITEM"];
            }
        }
        
        if (!$error){

            if (!isset($forma_retiene_fuente)) {
                $forma_retiene_fuente = "0";
            }
            if (!isset($forma_autoretenedor)) {
                $forma_autoretenedor = "0";
            }
            if (!isset($forma_autoretenedor_ica)) {
                $forma_autoretenedor_ica = "0";
            }
            if (!isset($forma_retiene_iva)) {
                $forma_retiene_iva = "0";
            }
            if (!isset($forma_retiene_ica)) {
                $forma_retiene_ica = "0";
            }
            if (!isset($forma_gran_contribuyente)) {
                $forma_gran_contribuyente = "0";
            }
            if (!isset($forma_fabricante)) {
                $forma_fabricante = "0";
            }
            if (!isset($forma_distribuidor)) {
                $forma_distribuidor = "0";
            }
            if (!isset($forma_servicios_tecnicos)) {
                $forma_servicios_tecnicos = "0";
            }
            if (!isset($forma_transporte)) {
                $forma_transporte = "0";
            }
            if (!isset($forma_publicidad)) {
                $forma_publicidad = "0";
            }
            if (!isset($forma_servicios_especiales)) {
                $forma_servicios_especiales = "0";
            }
            if (!isset($forma_pagos_anticipados)) {
                $forma_pagos_anticipados = "0";
            }
            if (!isset($forma_pagos_efectivo)) {
                $forma_pagos_efectivo = "0";
            }
            if (!isset($forma_transferencia_electronica)) {
                $forma_transferencia_electronica = "0";
            }
            if (!isset($forma_tarjeta_credito)) {
                $forma_tarjeta_credito = "0";
            }
            if (!isset($forma_triangulacion_bancaria)) {
                $forma_triangulacion_bancaria = "0";
            }

            if (!empty($forma_id_actividad_principal)){
                $forma_codigo_actividad_principal      = explode("|",$forma_id_actividad_principal);
                $codigo_iso_principal                  = $forma_codigo_actividad_principal[0];
                $codigo_dane_departamento_principal    = $forma_codigo_actividad_principal[1];
                $codigo_dane_municipio_principal       = $forma_codigo_actividad_principal[2];
                $codigo_dian_principal                 = $forma_codigo_actividad_principal[3];
                $codigo_actividad_municipio_principal  = $forma_codigo_actividad_principal[4];
            } else {
                $codigo_iso_principal                  = '';
                $codigo_dane_departamento_principal    = '';
                $codigo_dane_municipio_principal       = '';
                $codigo_dian_principal                 = 0;
                $codigo_actividad_municipio_principal  = 0;
            }
            if (!empty($forma_id_actividad_secundaria)){
                $forma_codigo_actividad_secundaria     = explode("|",$forma_id_actividad_secundaria);
                $codigo_iso_secundario                 = $forma_codigo_actividad_secundaria[0];
                $codigo_dane_departamento_secundario   = $forma_codigo_actividad_secundaria[1];
                $codigo_dane_municipio_secundario      = $forma_codigo_actividad_secundaria[2];
                $codigo_dian_secundario                = $forma_codigo_actividad_secundaria[3];
                $codigo_actividad_municipio_secundario = $forma_codigo_actividad_secundaria[4];
            } else {
                $codigo_iso_secundario                 = '';
                $codigo_dane_departamento_secundario   = '';
                $codigo_dane_municipio_secundario      = '';
                $codigo_dian_secundario                = 0;
                $codigo_actividad_municipio_secundario = 0;
            }

            $datos = array(
                "documento_identidad"                   => $forma_documento_identidad,
                "regimen"                               => $forma_regimen,
                "retiene_fuente"                        => $forma_retiene_fuente,
                "autoretenedor"                         => $forma_autoretenedor,
                "retiene_iva"                           => $forma_retiene_iva,
                "retiene_ica"                           => $forma_retiene_ica,
                "autoretenedor_ica"                     => $forma_autoretenedor_ica,
                "gran_contribuyente"                    => $forma_gran_contribuyente,
                "codigo_iso_principal"                  => $codigo_iso_principal,
                "codigo_dane_departamento_principal"    => $codigo_dane_departamento_principal,
                "codigo_dane_municipio_principal"       => $codigo_dane_municipio_principal,
                "codigo_dian_principal"                 => $codigo_dian_principal,
                "codigo_actividad_municipio_principal"  => $codigo_actividad_municipio_principal,
                "codigo_iso_secundario"                 => $codigo_iso_secundario,
                "codigo_dane_departamento_secundario"   => $codigo_dane_departamento_secundario,
                "codigo_dane_municipio_secundario"      => $codigo_dane_municipio_secundario,
                "codigo_dian_secundario"                => $codigo_dian_secundario,
                "codigo_actividad_municipio_secundario" => $codigo_actividad_municipio_secundario,
                "fabricante"                            => $forma_fabricante,
                "distribuidor"                          => $forma_distribuidor,
                "servicios_tecnicos"                    => $forma_servicios_tecnicos,
                "transporte"                            => $forma_transporte,
                "publicidad"                            => $forma_publicidad,
                "servicios_especiales"                  => $forma_servicios_especiales,
                "codigo_servicio"                       => $forma_codigo_servicio,
                "fecha_inicio_cobro"                    => '1',
                "codigo_plazo_pago_contado"             => $forma_codigo_plazo_pago_contado,
                "codigo_plazo_pago_credito"             => $forma_codigo_plazo_pago_credito,
                "tasa_pago_credito"                     => $forma_tasa_pago_credito,
                "porcentaje_primera_cuota"              => $forma_porcentaje_primera_cuota,
                "porcentaje_ultima_cuota"               => $forma_porcentaje_ultima_cuota,
                "pagos_anticipados"                     => $forma_pagos_anticipados,
                "pagos_efectivo"                        => $forma_pagos_efectivo,
                "transferencia_electronica"             => $forma_transferencia_electronica,
                "tarjeta_credito"                       => $forma_tarjeta_credito,
                "triangulacion_bancaria"                => $forma_triangulacion_bancaria,
                "tiempo_respuesta"                      => $forma_tiempo_respuesta,
                "porcentaje_flete"                      => $forma_porcentaje_flete,
                "valor_flete"                           => $forma_valor_flete,
                "porcentaje_seguro"                     => $forma_porcentaje_seguro,
                "valor_seguro"                          => $forma_valor_seguro,
                "forma_iva"                             => '1',
                "forma_liquidacion_descuento_en_linea"  => '1',
                "forma_liquidacion_descuento_global"    => '1',
                "forma_liquidacion_tasa_credito"        => '1'
            );


            $insertar = SQL::insertar("proveedores", $datos);

            /*** Error de inserción ***/
            if (!$insertar) {
                $error   = true;
                $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
            } else {

                /*** Insertar cuentas bancarias relacionadas con el proveedor ***/
                if (isset($forma_cuentas)) {

                    foreach ($forma_cuentas as $id_cuentas) {

                        $id_cuenta      = $id_cuentas;
                        $banco          = $forma_bancos[$id_cuenta];
                        $cuenta         = $forma_numeros_cuentas[$id_cuenta];
                        $tipo_cuenta    = $forma_tipos_cuentas[$id_cuenta];

                        $datos = array(
                            "documento_identidad_proveedor" => $forma_documento_identidad,
                            "codigo_banco"                  => $banco,
                            "cuenta"                        => $cuenta,
                            "tipo_cuenta"                   => $tipo_cuenta
                        );

                        $insertar = SQL::insertar("cuentas_bancarias_proveedores", $datos);

                        /*** Error de insercón ***/
                        if (!$insertar) {
                            $error   = true;
                            $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
                        }
                    }
                }
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

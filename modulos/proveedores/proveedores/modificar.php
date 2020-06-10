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
    $consulta = SQL::seleccionar(array("seleccion_actividades_economicas"),array("*"),$condicion);

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

if(isset($url_eliminarCuenta) && isset($url_id_cuenta_bancaria)){
    
    $llave = explode("|",$url_id_cuenta_bancaria);
    $documento_identidad_proveedor = $llave[0];
    $codigo_banco                  = $llave[1];
    $cuenta                        = $llave[2];
    $tipo_cuenta                   = $llave[3];

    $condicion = "documento_identidad_proveedor = '$documento_identidad_proveedor' AND codigo_banco='$codigo_banco'";
    $condicion .= " AND cuenta = '$cuenta' AND tipo_cuenta='$tipo_cuenta'";
    $eliminar_cuenta = SQL::eliminar("cuentas_bancarias_proveedores", $condicion);

    if($eliminar_cuenta){
        $error   = 1;
        $mensaje = $textos["CUENTA_ELIMINADA"];
    }else{
        $error   = 0;
        $mensaje = $textos["ERROR_ELIMINAR_CUENTA"];
    }
    
    $datos   = array();
    $datos[0]= $error;
    $datos[1]= $mensaje;
        
    HTTP::enviarJSON($datos);
    exit();
}

/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {

    /*** Verificar que se haya enviado el ID del elemento a modificar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_MODIFICAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {
        $vistaConsulta = "buscador_proveedores";
        $condicion     = "id = '$url_id'";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "id = '$url_id'");
        $datos         = SQL::filaEnObjeto($consulta);
        $error         = "";
        $titulo        = $componente->nombre;

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

        /***Obtener los datos de la tabla de proveedores***/
        $vistaProveedor       = "proveedores";
        $columnasProveedor    = SQL::obtenerColumnas($vistaProveedor);
        $consultaProveedor    = SQL::seleccionar(array($vistaProveedor), $columnasProveedor, "documento_identidad = '$url_id'");
        $datosProveedor       = SQL::filaEnObjeto($consultaProveedor);
        $descripcion_servicio = SQL::obtenerValor("servicios", "descripcion", "codigo = '$datosProveedor->codigo_servicio'");

        /*** Obtener los datos de la tabla de terceros ***/
        $vistaTercero    = "terceros";
        $columnasTercero = SQL::obtenerColumnas($vistaTercero);
        $consultaTercero = SQL::seleccionar(array($vistaTercero), $columnasTercero, "documento_identidad = '$datosProveedor->documento_identidad'");
        $datosTercero    = SQL::filaEnObjeto($consultaTercero);
        
        if ($datosTercero->tipo_persona == 1){
            $valor_persona_natural  = true;
            $valor_oculto_natural   = "";
            $valor_oculto_juridica  = "oculto";
            $valor_persona_juridica = false;
            $valor_codigo_interno   = false;
            $valor_oculto_comerciante = "oculto";
            $valor_natural_comerciante = false;
        }elseif($datosTercero->tipo_persona == 2){
            $valor_persona_natural  = false;
            $valor_persona_juridica = true;
            $valor_oculto_natural   = "oculto";
            $valor_oculto_juridica  = "";
            $valor_oculto_comerciante = "";
            $valor_codigo_interno   = false;
            $valor_natural_comerciante = false;
        }elseif($datosTercero->tipo_persona == 4){
            $valor_persona_natural  = false;
            $valor_persona_juridica = false;
            $valor_codigo_interno   = false;
            $valor_natural_comerciante = true;
            $valor_oculto_natural   = "";
            $valor_oculto_juridica  = "oculto";
            $valor_oculto_comerciante = "";
        }else{
            $valor_persona_natural  = false;
            $valor_persona_juridica = false;
            $valor_natural_comerciante = false;
            $valor_codigo_interno   = true;
            $valor_oculto_natural   = "oculto";
            $valor_oculto_juridica  = "";
            $valor_oculto_comerciante = "";
        }
        
        /**Datos de los selectores**/
        $llave_primaria_municipio = $datosTercero -> codigo_iso_municipio_documento.",". $datosTercero -> codigo_dane_departamento_documento.",".$datosTercero -> codigo_dane_municipio_documento;
        $descripcion_municipio    = SQL::obtenerValor("seleccion_municipios","nombre","llave_primaria ='$llave_primaria_municipio'");
        $descripcion_municipio    = explode("|",$descripcion_municipio);
        $descripcion_municipio    = $descripcion_municipio[0];

        $llave_primaria_localidad = $datosTercero -> codigo_iso_localidad.",". $datosTercero -> codigo_dane_departamento_localidad.",".$datosTercero -> codigo_dane_municipio_localidad.",".$datosTercero -> tipo_localidad.",".$datosTercero -> codigo_dane_localidad;
        $descripcion_localidad    = SQL::obtenerValor("seleccion_localidades","nombre","llave_primaria ='$llave_primaria_localidad'");
        $descripcion_localidad    = explode("|",$descripcion_localidad);
        $descripcion_localidad    = $descripcion_localidad[0];

        $condicion = "codigo_iso ='$datosTercero->codigo_iso_localidad' AND codigo_dane_departamento = '$datosTercero->codigo_dane_departamento_localidad'";
        $condicion .= " AND codigo_dane_municipio='$datosTercero->codigo_dane_municipio_localidad'";
        $consulta  = SQL::seleccionar(array("actividades_economicas"),array("*"),$condicion);


        $llave_actividad_primaria = $datosProveedor->codigo_iso_principal."|".$datosProveedor->codigo_dane_departamento_principal."|".$datosProveedor->codigo_dane_municipio_principal."|";
        $llave_actividad_primaria .= $datosProveedor->codigo_dian_principal."|".$datosProveedor->codigo_actividad_municipio_principal;
        if ($llave_actividad_primaria == '||0000|00000'){
            $llave_actividad_primaria = 0;
        }

        $llave_actividad_secundaria = $datosProveedor->codigo_iso_secundario."|".$datosProveedor->codigo_dane_departamento_secundario."|".$datosProveedor->codigo_dane_municipio_secundario."|";
        $llave_actividad_secundaria .= $datosProveedor->codigo_dian_secundario."|".$datosProveedor->codigo_actividad_municipio_secundario;
        if ($llave_actividad_secundaria == '||0000|00000'){
            $llave_actividad_primaria = 0;
        }

        if (SQL::filasDevueltas($consulta)){

            $actividad[0] = "";
            while ($datos_actividades = SQL::filaEnObjeto($consulta)){

                $llave = $datos_actividades->codigo_iso."|".$datos_actividades->codigo_dane_departamento."|".$datos_actividades->codigo_dane_municipio."|";
                $llave .= $datos_actividades->codigo_dian."|".$datos_actividades->codigo_actividad_municipio;

                $actividad[$llave] = $datos_actividades->descripcion;
            }
        }

        if ($datosTercero->tipo_persona == '2' || $datosTercero->tipo_persona == '4') {

            //Genera digito de verificacion en nit
            $nit     = $datosTercero->documento_identidad;
            $array   = array(1 => 3, 4 => 17, 7 => 29, 10 => 43, 13 => 59, 2 => 7, 5 => 19, 8 => 37, 11 => 47, 14 => 67, 3 => 13,
                             6 => 23, 9 => 41, 12 => 53, 15 => 71);
            $x       = 0;
            $y       = 0;
            $z       = strlen($nit);
            $digitoV = '';
    
            for ($i = 0; $i < $z; $i++) {
                $y  = substr($nit, $i, 1);
                $x += ($y*$array[$z-$i]);
            }
    
            $y = $x%11;
    
            if ($y > 1) {
                $digitoV = 11-$y;
                return $digitoV;
            } else {
                $digitoV = $y;
            }
        }
            
        /* Obtener cuentas bancarias relacionadas con el proveedor */
        $orden_lista_cuentas = 0;
        $item_cuenta = '';
        $consulta_cuentas = SQL::seleccionar(array("cuentas_bancarias_proveedores"), array("*"), "documento_identidad_proveedor = '$url_id'", "", "");
        if (SQL::filasDevueltas($consulta_cuentas)) {

            while ($datos_cuenta = SQL::filaEnObjeto($consulta_cuentas)) {

                $id_cuenta   = $datos_cuenta->documento_identidad_proveedor."|".$datos_cuenta->codigo_banco."|";
                $id_cuenta  .= $datos_cuenta->cuenta."|".$datos_cuenta->tipo_cuenta;
                $id_banco    = $datos_cuenta->codigo_banco;
                $banco       = SQL::obtenerValor("bancos","descripcion","codigo= '$id_banco'");
                $cuenta      = $datos_cuenta->cuenta;
                $tipo        = $tipo_cuenta[$datos_cuenta->tipo_cuenta];

                $co1 = HTML::campoOculto("cuentas[".$id_cuenta."]", $id_cuenta, array("class"=>"cuentas"));
                $co2 = HTML::campoOculto("bancos[".$id_cuenta."]", $id_banco, array("class"=>"bancos"));
                $co3 = HTML::campoOculto("numeros_cuentas[".$id_cuenta."]", $cuenta, array("class"=>"numeros_cuentas"));
                $co4 = HTML::campoOculto("tipos_cuentas[".$id_cuenta."]", $datos_cuenta->tipo_cuenta, array("class"=>"tipos_cuentas"));
                $co5 = HTML::campoOculto("estadoModificar[".$id_cuenta."]", '1', array("class"=>"estadoModificar"));

                $remover = HTML::boton("botonRemoverCuenta", "", "removerItem(this);", "eliminar");
                $celda   = $co1.$co2.$co3.$co4.$co5.$remover;

                $item_cuenta[] = array( $id_cuenta,
                                        $celda,
                                        $banco,
                                        $cuenta,
                                        $tipo
                );
            }
            $orden_lista_cuentas = $id_cuenta+1;
        }

        /*** Definición de pestañas para datos del tercero***/
        $formularios["PESTANA_TERCERO"] = array(
            array(
                HTML::listaSeleccionSimple("*codigo_tipo_documento", $textos["TIPO_DOCUMENTO_IDENTIDAD"], HTML::generarDatosLista("tipos_documento_identidad", "codigo", "descripcion"), $datosTercero->codigo_tipo_documento,array("title" => $textos["AYUDA_TIPO_DOCUMENTO"]))
            ),
            array(
                HTML::marcaSeleccion("tipo_persona", $textos["PERSONA_NATURAL"], 1, $valor_persona_natural, array("id" => "persona_natural", "onChange" => "activarNombres(1)")),
                HTML::marcaSeleccion("tipo_persona", $textos["PERSONA_JURIDICA"], 2, $valor_persona_juridica, array("id" => "persona_juridica", "onChange" => "activarNombres(2)")),
                HTML::marcaSeleccion("tipo_persona", $textos["CODIGO_INTERNO"], 3, $valor_codigo_interno, array("id" => "codigo_interno", "onChange" => "activarNombres(3)")),
                HTML::marcaSeleccion("tipo_persona", $textos["NATURAL_COMERCIANTE"], 4, $valor_natural_comerciante, array("id" => "natural_comerciante", "onChange" => "activarNombres(4)"))
            ),
            array(
                HTML::campoTextoCorto("*documento_identidad", $textos["DOCUMENTO_PROVEEDOR"], 15, 15, $datos->documento_identidad, array("title" => $textos["AYUDA_PROVEEDOR"],"onblur" => "validarItem(this);","onKeyPress" => "return campoEntero(event)")),
                HTML::campoTextoCorto("digito_verificacion", $textos["DIGITO_VERIFICACION"], 1, 1, $digitoV, array("readonly" => "true","Class" => "$valor_oculto_juridica"))
            ),
            array(
                HTML::campoTextoCorto("*selector1", $textos["MUNICIPIO"], 40, 255, $descripcion_municipio, array("title" => $textos["AYUDA_DOCUMENTO_MUNICIPIO"], "class" => "autocompletable"))
                .HTML::campoOculto("id_municipio_documento", $llave_primaria_municipio)
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
                HTML::campoTextoCorto("*razon_social", $textos["RAZON_SOCIAL"], 40, 100, $datos->razon_social, array("title" => $textos["AYUDA_RAZON_SOCIAL"], "onblur" => "validarItem(this)", "class" => "$valor_oculto_juridica"))
            ),
            array(
                HTML::campoTextoCorto("nombre_comercial", $textos["NOMBRE_COMERCIAL"], 40, 60, $datos->nombre_comercial, array("title" => $textos["AYUDA_NOMBRE_COMERCIAL"], "onblur" => "validarItem(this)", "class" => "$valor_oculto_comerciante"))
            )
        );

        /*** Definición de pestañas para la ubicación del tercero***/
        $formularios["PESTANA_UBICACION_TERCERO"] = array(
            array(
                HTML::campoTextoCorto("*selector2", $textos["LOCALIDAD"], 50, 255, $descripcion_localidad, array("title" => $textos["AYUDA_DOCUMENTO_MUNICIPIO"], "class" => "autocompletable"))
                .HTML::campoOculto("id_localidad", $llave_primaria_localidad),
                HTML::campoOculto("id_localidad_anterior", $llave_primaria_localidad)
            ),
            array(
                HTML::campoTextoCorto("*direccion_principal", $textos["DIRECCION"], 50, 50, $datosTercero->direccion_principal, array("title" => $textos["AYUDA_DIRECCION"]))
            ),
            array(
                HTML::campoTextoCorto("telefono_principal", $textos["TELEFONO_PRINCIPAL"], 13, 15, $datosTercero->telefono_principal, array("title" => $textos["AYUDA_TELEFONO_PRINCIPAL"])),
                HTML::campoTextoCorto("fax", $textos["FAX"], 13, 15, $datosTercero->fax, array("title" => $textos["AYUDA_FAX"])),
                HTML::campoTextoCorto("celular", $textos["CELULAR"], 14, 20, $datosTercero->celular, array("title" => $textos["AYUDA_CELULAR"]))
            ),
            array(
                HTML::campoTextoCorto("correo", $textos["CORREO"], 50, 255, $datosTercero->correo, array("title" => $textos["AYUDA_CORREO"]))
            ),
            array(
                HTML::campoTextoCorto("sitio_web", $textos["SITIO_WEB"], 50, 50, $datosTercero->sitio_web, array("title" => $textos["AYUDA_SITIO_WEB"]))
            )
        );

        /*** Definición de pestaña tributaria ***/
        $formularios["PESTANA_TRIBUTARIA"] = array(
            array(
                HTML::listaSeleccionSimple("regimen", $textos["REGIMEN"], $regimen, $datosProveedor->regimen, array("title" => $textos["AYUDA_REGIMEN"]))
            ),
            array(
                HTML::marcaChequeo("autoretenedor", $textos["AUTORETENEDOR"],1, $datosProveedor->autoretenedor)
            ),
            array(
                HTML::marcaChequeo("retiene_fuente", $textos["RETIENE_FUENTE"], 1, $datosProveedor->retiene_fuente)
            ),
            array(
                HTML::marcaChequeo("gran_contribuyente", $textos["GRAN_CONTRIBUYENTE"],1, $datosProveedor->gran_contribuyente)
            ),
            array(
                HTML::marcaChequeo("retiene_iva", $textos["RETIENE_IVA"], 1, $datosProveedor->retiene_iva)
            ),
            array(
                HTML::marcaChequeo("autoretenedor_ica", $textos["AUTORETENEDOR_ICA"],1, $datosProveedor->autoretenedor_ica)
            ),
            array(
                HTML::marcaChequeo("retiene_ica", $textos["RETIENE_ICA"],1, $datosProveedor->retiene_ica)
            ),
            /*array(
                HTML::listaSeleccionSimple("forma_iva", $textos["FORMA_IVA"], $forma_iva, $datosProveedor->forma_iva, array("title" => $textos["AYUDA_FORMA_IVA"]))
            )*/
        );

        /*** Definición de pestaña PROVEEDOR ***/
        $funciones["PESTANA_PROVEEDOR"] = "recargarActividades()";
        $formularios["PESTANA_PROVEEDOR"] = array(
            array(
                HTML::listaSeleccionSimple("id_actividad_principal", $textos["ACTIVIDAD_PRINCIPAL"], $actividad, $llave_actividad_primaria, array("title" => $textos["AYUDA_ACTIVIDAD_PRINCIPAL"],"onBlur" => "validarItem(this);"))
            ),
            array(
                HTML::listaSeleccionSimple("id_actividad_secundaria", $textos["ACTIVIDAD_SECUNDARIA"], $actividad, $llave_actividad_secundaria, array("title" => $textos["AYUDA_ACTIVIDAD_SECUNDARIA"],"onBlur" => "validarItem(this);"))
            ),
            array(
                HTML::marcaChequeo("fabricante", $textos["FABRICANTE"], 1, $datosProveedor->fabricante)
            ),
            array(
                HTML::marcaChequeo("distribuidor", $textos["DISTRIBUIDOR"], 1, $datosProveedor->distribuidor)
            ),
            array(
                HTML::marcaChequeo("servicios_tecnicos", $textos["SERVICIOS_TECNICOS"], 1, $datosProveedor->servicios_tecnicos)
            ),    
            array(
                HTML::marcaChequeo("transporte", $textos["TRANSPORTE"], 1, $datosProveedor->transporte)
            ),
            array(
                HTML::marcaChequeo("publicidad", $textos["PUBLICIDAD"], 1, $datosProveedor->publicidad)
            ),
            array(
                HTML::marcaChequeo("servicios_especiales", $textos["SERVICIOS_ESPECIALES"], 1, $datosProveedor->servicios_especiales)
            ),
            array(
                HTML::listaSeleccionSimple("codigo_servicio", $textos["TIPO_SERVICIO"], HTML::generarDatosLista("servicios", "codigo", "descripcion"), $datosProveedor->codigo_servicio, array("title" => $textos["AYUDA_TIPO_SERVICIO"])),
                HTML::listaSeleccionSimple("fecha_inicio_cobro", $textos["FECHA_INICIO_COBRO"], $inicio_cobro, $datosProveedor->fecha_inicio_cobro, array("title" => $textos["AYUDA_INICIO_COBRO"]))
            ),
            array(
                HTML::campoTextoCorto("tiempo_respuesta", $textos["TIEMPO_RESPUESTA"], 3, 3, $datosProveedor->tiempo_respuesta, array("title" => $textos["AYUDA_TIEMPO_RESPUESTA"],"onBlur" => "validarItem(this);","onKeyPress" => "return campoEntero(event)"))
            ),array(
                HTML::campoTextoCorto("porcentaje_flete", $textos["PORCENTAJE_FLETE"], 9, 6, $datosProveedor->porcentaje_flete, array("title" => $textos["AYUDA_PORCENTAJE_FLETE"],"onBlur" => "validarItem(this);","onKeyPress" => "return campoDecimal(event)")),
                HTML::campoTextoCorto("valor_flete", $textos["VALOR_FLETE"], 10, 10, $datosProveedor->valor_flete, array("title" => $textos["AYUDA_VALOR_FLETE"],"onBlur" => "validarItem(this);","onKeyPress" => "return campoEntero(event)"))
            ),array(
                HTML::campoTextoCorto("porcentaje_seguro", $textos["PORCENTAJE_SEGURO"], 9, 6, $datosProveedor->porcentaje_seguro, array("title" => $textos["AYUDA_PORCENTAJE_SEGURO"],"onBlur" => "validarItem(this);","onKeyPress" => "return campoDecimal(event)")),
                HTML::campoTextoCorto("valor_seguro", $textos["VALOR_SEGURO"], 10, 10, $datosProveedor->valor_seguro, array("title" => $textos["AYUDA_VALOR_SEGURO"],"onBlur" => "validarItem(this);","onKeyPress" => "return campoEntero(event)"))
            )
        );

        /*** Definición de pestaña PAGOS ***/
        if ($datosProveedor->forma_liquidacion_descuento_en_linea == '1'){
            $liquidacion_linea_inicio_articulo = true;
            $liquidacion_linea_final_articulo  = false;
        } else {
            $liquidacion_linea_inicio_articulo = false;
            $liquidacion_linea_final_articulo  = true;
        }

        if ($datosProveedor->forma_liquidacion_descuento_global == '1'){
            $liquidacion_global_inicio_articulo = true;
            $liquidacion_global_final_articulo  = false;
            $liquidacion_global_final_factura   = false;
        } else if($datosProveedor->forma_liquidacion_descuento_global == '2'){
            $liquidacion_global_inicio_articulo = false;
            $liquidacion_global_final_articulo  = true;
            $liquidacion_global_final_factura   = false;
        } else {
            $liquidacion_global_inicio_articulo = false;
            $liquidacion_global_final_articulo  = false;
            $liquidacion_global_final_factura   = true;
        }
        $formularios["PESTANA_PAGOS"] = array(
            array(
                HTML::listaSeleccionSimple("codigo_plazo_pago_contado", $textos["FORMA_PAGO_CONTADO"], HTML::generarDatosLista("plazos_pago_proveedores", "codigo", "nombre","codigo>0"), $datosProveedor->codigo_plazo_pago_contado, array("title" => $textos["AYUDA_PAGO_CONTADO"])),
            ),
            /*array(
                HTML::listaSeleccionSimple("codigo_plazo_pago_credito", $textos["FORMA_PAGO_CREDITO"], HTML::generarDatosLista("plazos_pago_proveedores", "codigo", "nombre","codigo>0"), $datosProveedor->codigo_plazo_pago_credito, array("title" => $textos["AYUDA_PAGO_CREDITO"])),
                HTML::campoTextoCorto("tasa_pago_credito", $textos["TASA_PAGO_CREDITO"], 6, 6, $datosProveedor->tasa_pago_credito, array("title" => $textos["AYUDA_TASA_CUOTAS_CREDITO"],"onBlur" => "validarItem(this);","onKeyPress" => "return campoDecimal(event)")),
                HTML::listaSeleccionSimple("liquidacion_tasa_credito", $textos["LIQUIDACION_TASA_CREDITO"], $forma_liquidacion_tasa_credito, $datosProveedor->forma_liquidacion_tasa_credito, array("title" => $textos["AYUDA_LIQUIDACION_TASA_CREDITO"]))
            ),
            array(
                HTML::campoTextoCorto("porcentaje_primera_cuota", $textos["PRIMERA_CUOTA"], 6, 6, $datosProveedor->porcentaje_primera_cuota, array("title" => $textos["AYUDA_PRIMERA_CUOTA"],"onKeyPress" => "return campoDecimal(event)")),
                HTML::campoTextoCorto("porcentaje_ultima_cuota", $textos["ULTIMA_CUOTA"], 6, 6, $datosProveedor->porcentaje_ultima_cuota, array("title" => $textos["AYUDA_ULTIMA_CUOTA"],"onKeyPress" => "return campoDecimal(event)"))
            ),
            array(
                HTML::marcaChequeo("pagos_anticipados", $textos["PAGOS_ANTICIPADOS"],1, $datosProveedor->pagos_anticipados)
            ),
            array(
                HTML::marcaChequeo("pagos_efectivo", $textos["PAGOS_EFECTIVOS"], 1, $datosProveedor->pagos_efectivo)
            ),
            array(
                HTML::marcaChequeo("transferencia_electronica", $textos["TRANSFERENCIA_ELECTRONICA"], 1, $datosProveedor->transferencia_electronica)
            ),
            array(
                HTML::marcaChequeo("tarjeta_credito", $textos["TARJETA_CREDITO"], 1, $datosProveedor->tarjeta_credito)
            ),
            array(
                HTML::marcaChequeo("triangulacion_bancaria", $textos["TRIANGULACION_BANCARIA"], 1, $datosProveedor->triangulacion_bancaria)
            ),
            array(
                HTML::mostrarDato("dato_vacio", $textos["TITULO_DESCUENTO_LINEA"], $textos["DATO_VACIO"])
            ),
            array(
                HTML::marcaSeleccion("liquidacion_descuento_en_linea", $textos["VALOR_NETO_UNITARIO"], 1, $liquidacion_linea_inicio_articulo, array("id" => "descuento_linea_neto_unitario",  "onChange" => "GlobalUnitario(0)")),
                HTML::marcaSeleccion("liquidacion_descuento_en_linea", $textos["VALOR_NETO_TOTAL"], 2, $liquidacion_linea_final_articulo, array("id" => "descuento_linea_neto_global", "onChange" => "GlobalUnitario(1)" ))
            ),
            array(
                HTML::mostrarDato("dato_vacio", $textos["TITULO_DESCUENTO_GLOBAL"], $textos["DATO_VACIO"])
            ),
            array(
                HTML::contenedor(
                    HTML::marcaSeleccion("liquidacion_descuento_global", $textos["NETO_DESDE_UNITARIO"], 1, $liquidacion_global_inicio_articulo, array("id" => "descuento_global_neto_unitario")),
                    array("id" => "descuentos_globales")
                ),
                HTML::marcaSeleccion("liquidacion_descuento_global", $textos["NETO_CON_TOTAL"], 2, $liquidacion_global_final_articulo, array("id" => "descuento_global_neto_total")),
                HTML::marcaSeleccion("liquidacion_descuento_global", $textos["NETO_FINAL_FACTURA"], 3, $liquidacion_global_final_factura, array("id" => "descuento_global_neto_valor_final"))
            )*/
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
                .HTML::campoOculto("orden_lista_cuentas", $orden_lista_cuentas),
                HTML::boton("botonAgregarCuenta", $textos["AGREGAR"], "agregarItemCuenta();", "adicionar"),
                HTML::contenedor(HTML::boton("botonRemoverCuenta", "", "removerItem(this);", "eliminar"), array("id" => "removedorCuenta", "style" => "display: none"))
            ),
            array(
                HTML::generarTabla( array("id","","BANCO","CUENTA","TIPO_CUENTA"),
                                    $item_cuenta,
                                    array("I","I","D","I"),
                                    "lista_items_cuentas",
                                    false)
            )
        );

        /*** Definición de botones ***/
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "modificarItem('$url_id');", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones,"",$funciones);
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

    } else {

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
            "sitio_web"                          => $forma_sitio_web
        );
        
        $consulta = SQL::modificar("terceros", $datos, "documento_identidad = '$forma_id'");

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
            "fecha_inicio_cobro"                    => $forma_fecha_inicio_cobro,
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
            "forma_iva"                             => $forma_forma_iva,
            "forma_liquidacion_descuento_en_linea"  => $forma_liquidacion_descuento_en_linea,
            "forma_liquidacion_descuento_global"    => $forma_liquidacion_descuento_global,
            "forma_liquidacion_tasa_credito"        => $forma_liquidacion_tasa_credito
        );


        $consulta = SQL::modificar("proveedores", $datos, "documento_identidad = '$forma_id'");

        if ($consulta) {
            $error   = false;
            $mensaje = $textos["ITEM_MODIFICADO"];

            /*** Modificar cuentas bancarias relacionadas con el proveedor ***/
            //$eliminar  = SQL::eliminar("cuentas_bancarias_proveedores", "documento_identidad_proveedor='$forma_id'");
            if (isset($forma_cuentas)) {

                foreach ($forma_cuentas as $id_cuentas) {

                    $id_cuenta      = $id_cuentas;
                    $banco          = $forma_bancos[$id_cuenta];
                    $cuenta         = $forma_numeros_cuentas[$id_cuenta];
                    $tipo_cuenta    = $forma_tipos_cuentas[$id_cuenta];
                    $estado         = $forma_estadoModificar[$id_cuenta];

                    $datos = array(
                        "documento_identidad_proveedor"  => $forma_documento_identidad,
                        "codigo_banco"                   => $banco,
                        "cuenta"                         => $cuenta,
                        "tipo_cuenta"                    => $tipo_cuenta
                    );

                    if ($estado != '1'){
                        $insertar = SQL::insertar("cuentas_bancarias_proveedores", $datos);
                    } else {

                        $llave = explode("|",$id_cuenta);
                        $documento_identidad_proveedor = $llave[0];
                        $codigo_banco                  = $llave[1];
                        $cuenta                        = $llave[2];
                        $tipo_cuenta                   = $llave[3];
                        $condicion = "documento_identidad_proveedor = '$documento_identidad_proveedor' AND codigo_banco='$codigo_banco'";
                        $condicion .= " AND cuenta = '$cuenta' AND tipo_cuenta='$tipo_cuenta'";
                        $insertar = SQL::modificar("cuentas_bancarias_proveedores", $datos, $condicion);
                    }

                    /*** Error de insercón ***/
                    if (!$insertar) {
                        $error     = true;
                        $mensaje   = $textos["ERROR_ADICIONAR_ITEM"];
                    }
                }
            }

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

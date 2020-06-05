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
        $vistaConsulta = "buscador_proveedores";
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
            "1" => $textos["DISTRIBUIDO"],
            "2" => $textos["PRIMERA_CUOTA"],
            "3" => $textos["SEPARADO"]
        );
        $forma_liquidacion_tasa_credito = array(
            "1" => $textos["DESPUES_LINEA"],
            "2" => $textos["DESPUES_GLOBAL"]
        );

        /***Obtener los datos de la tabla de proveedores***/
        $vistaProveedor       = "proveedores";
        $columnasProveedor    = SQL::obtenerColumnas($vistaProveedor);
        $consultaProveedor    = SQL::seleccionar(array($vistaProveedor), $columnasProveedor, "documento_identidad = '$url_id'");
        $datosProveedor       = SQL::filaEnObjeto($consultaProveedor);

        $condicion  = "codigo_iso ='$datosProveedor->codigo_iso_principal' AND codigo_dane_departamento = '$datosProveedor->codigo_dane_departamento_principal'";
        $condicion .= " AND codigo_dane_municipio='$datosProveedor->codigo_dane_municipio_principal' AND codigo_dian='$datosProveedor->codigo_dian_principal'";
        $condicion .= " AND codigo_actividad_municipio='$datosProveedor->codigo_actividad_municipio_principal'";
        $actividad_principal = SQL::obtenerValor("actividades_economicas","descripcion",$condicion);

        $condicion  = "codigo_iso ='$datosProveedor->codigo_iso_secundario' AND codigo_dane_departamento = '$datosProveedor->codigo_dane_departamento_secundario'";
        $condicion .= " AND codigo_dane_municipio='$datosProveedor->codigo_dane_municipio_secundario' AND codigo_dian='$datosProveedor->codigo_dian_secundario'";
        $condicion .= " AND codigo_actividad_municipio='$datosProveedor->codigo_actividad_municipio_secundario'";
        $actividad_secundaria = SQL::obtenerValor("actividades_economicas","descripcion",$condicion);

        $descripcion_servicio = SQL::obtenerValor("servicios", "descripcion", "codigo = '$datosProveedor->codigo_servicio'");
        $forma_pago_contado   = SQL::obtenerValor("plazos_pago_proveedores","nombre","codigo = '$datosProveedor->codigo_plazo_pago_contado'");
		$forma_pago_credito   = SQL::obtenerValor("plazos_pago_proveedores","nombre","codigo = '$datosProveedor->codigo_plazo_pago_credito'");

        /*** Obtener los datos de la tabla de terceros ***/
        $vistaTercero    = "terceros";
        $columnasTercero = SQL::obtenerColumnas($vistaTercero);
        $consultaTercero = SQL::seleccionar(array($vistaTercero), $columnasTercero, "documento_identidad = '$datosProveedor->documento_identidad'");
        $datosTercero    = SQL::filaEnObjeto($consultaTercero);
        if(($datosTercero->tipo_persona) == 1){
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

        $condicion = "codigo_iso = '$datosTercero->codigo_iso_municipio_documento' AND codigo_dane_departamento='$datosTercero->codigo_dane_departamento_documento'";
        $condicion .= " AND codigo_dane_municipio = '$datosTercero->codigo_dane_municipio_documento'";
        $nombre_municipio_documento     = SQL::obtenerValor("municipios", "nombre", $condicion);

        $condicion                     = "codigo_iso = '$datosTercero->codigo_iso_municipio_documento' AND codigo_dane_departamento='$datosTercero->codigo_dane_departamento_documento'";
        $nombre_departamento_documento = SQL::obtenerValor("departamentos", "nombre", $condicion);

        $nombre_pais_documento = SQL::obtenerValor("paises", "nombre", "codigo_iso = '$datosTercero->codigo_iso_municipio_documento'");

        $condicion = "codigo_iso = '$datosTercero->codigo_iso_localidad' AND codigo_dane_departamento='$datosTercero->codigo_dane_departamento_localidad'";
        $condicion .= " AND codigo_dane_municipio = '$datosTercero->codigo_dane_municipio_localidad' AND tipo='$datosTercero->tipo_localidad'";
        $condicion .= " AND codigo_dane_localidad = '$datosTercero->codigo_dane_localidad'";
        $nombre_localidad_residencia    = SQL::obtenerValor("localidades", "nombre", $condicion);

        $condicion = "codigo_iso = '$datosTercero->codigo_iso_localidad' AND codigo_dane_departamento='$datosTercero->codigo_dane_departamento_localidad'";
        $condicion .= " AND codigo_dane_municipio = '$datosTercero->codigo_dane_municipio_localidad'";
        $nombre_municipio_residencia = SQL::obtenerValor("municipios", "nombre", $condicion);

        $condicion = "codigo_iso = '$datosTercero->codigo_iso_localidad' AND codigo_dane_departamento='$datosTercero->codigo_dane_departamento_localidad'";
        $nombre_departamento_residencia = SQL::obtenerValor("departamentos", "nombre", $condicion);

        $nombre_pais_residencia = SQL::obtenerValor("paises", "nombre", "codigo_iso = '$datosTercero->codigo_iso_localidad'");

        /* Obtener cuentas bancarias relacionadas con el proveedor */
        $consulta_cuentas = SQL::seleccionar(array("cuentas_bancarias_proveedores"), array("*"), "documento_identidad_proveedor = '$url_id'");
        if (SQL::filasDevueltas($consulta_cuentas)) {

            $tipos_cuenta = array(
                "1" => $textos["AHORROS"],
                "2" => $textos["CORRIENTE"]
            );

            while ($datos_cuenta = SQL::filaEnObjeto($consulta_cuentas)) {

                $id_cuenta      = $datos_cuenta->documento_identidad_proveedor;
                $id_banco       = $datos_cuenta->codigo_banco;
                $banco          = SQL::obtenerValor("bancos","descripcion","codigo= '$id_banco'");
                $cuenta         = $datos_cuenta->cuenta;
                $tipo_cuenta    = $tipos_cuenta[$datos_cuenta->tipo_cuenta];

                $item_cuenta[] = array( $id_cuenta,
                                        $banco,
                                        $cuenta,
                                        $tipo_cuenta
                );
            }
        }

        /*** Definición de pestañas ***/
        $formularios["PESTANA_TERCERO"] = array(
            array(
                HTML::mostrarDato("documento_identidad", $textos["DOCUMENTO_PROVEEDOR"], $datos->documento_identidad)
            ),
            array(
                HTML::mostrarDato("tipo_persona", $textos["TIPO_PERSONA"], $tipo_persona[$datosTercero->tipo_persona]),
                HTML::mostrarDato("descripcion_tipo_documento", $textos["TIPO_DOCUMENTO_IDENTIDAD"], $descripcion_tipo_documento)
            ),
            array(
                HTML::mostrarDato("primer_nombre", $textos["$primer_nombre"], $datos->primer_nombre),
                HTML::mostrarDato("segundo_nombre", $textos["$segundo_nombre"], $datos->segundo_nombre),
                HTML::mostrarDato("primer_apellido", $textos["$primer_apellido"], $datos->primer_apellido),
                HTML::mostrarDato("segusndo_apellido", $textos["$segundo_apellido"], $datos->segundo_apellido)
            ),
            array(
                HTML::mostrarDato("razon_social", $textos["$razon_social"], $datos->razon_social)
            ),
            array(
                HTML::mostrarDato("nombre_comercial", $textos["NOMBRE_COMERCIAL"], $datos->nombre_comercial)
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
                HTML::mostrarDato("direccion_principal", $textos["DIRECCION"], $datosTercero->direccion_principal)
            ),array(
                HTML::mostrarDato("telefono_principal", $textos["TELEFONO_PRINCIPAL"], $datosTercero->telefono_principal),
                HTML::mostrarDato("fax", $textos["FAX"], $datosTercero->fax),
                HTML::mostrarDato("celular", $textos["CELULAR"], $datosTercero->celular)
            ),
            array(
                HTML::mostrarDato("correo", $textos["CORREO"], $datosTercero->correo),
                HTML::mostrarDato("sitio_web", $textos["SITIO_WEB"], $datosTercero->sitio_web)
            )
        );

        /*** Definición de pestaña tributaria ***/
        $formularios["PESTANA_TRIBUTARIA"] = array(
            array(
                HTML::mostrarDato("regimen", $textos["REGIMEN"], $regimen[$datosProveedor->regimen])
            ),
            array(
                HTML::mostrarDato("autoretenedor", $textos["AUTORETENEDOR"], $textos["SI_NO_".intval($datosProveedor->autoretenedor)]),
            ),
            array(
                HTML::mostrarDato("retiene_fuente", $textos["RETIENE_FUENTE"], $textos["SI_NO_".intval($datosProveedor->retiene_fuente)])
            ),
            array(
                HTML::mostrarDato("gran_contribuyente", $textos["GRAN_CONTRIBUYENTE"], $textos["SI_NO_".intval($datosProveedor->gran_contribuyente)])
            ),
            array(
                HTML::mostrarDato("retiene_iva", $textos["RETIENE_IVA"], $textos["SI_NO_".intval($datosProveedor->retiene_iva)])
            ),
            array(
                HTML::mostrarDato("autoretenedor_ica", $textos["AUTORETENEDOR_ICA"], $textos["SI_NO_".intval($datosProveedor->autoretenedor_ica)]),
            ),
            array(
                HTML::mostrarDato("retiene_ica", $textos["RETIENE_ICA"], $textos["SI_NO_".intval($datosProveedor->retiene_ica)])
            ),
            array(
                HTML::mostrarDato("forma_iva", $textos["FORMA_IVA"], $forma_iva[$datosProveedor->forma_iva])
            )
        );

        /*** Definición de pestaña PROVEEDOR ***/
        $formularios["PESTANA_PROVEEDOR"] = array(
            array(
                HTML::mostrarDato("actividad_principal", $textos["ACTIVIDAD_PRINCIPAL"], $actividad_principal)
            ),
            array(
                HTML::mostrarDato("actividad_secundaria", $textos["ACTIVIDAD_SECUNDARIA"], $actividad_secundaria)
            ),
            array(
                HTML::mostrarDato("fabricante", $textos["FABRICANTE"], $textos["SI_NO_".intval($datosProveedor->fabricante)])
            ),array(
                HTML::mostrarDato("distribuidor", $textos["DISTRIBUIDOR"], $textos["SI_NO_".intval($datosProveedor->distribuidor)])
            ),
            array(
                HTML::mostrarDato("servicios_tecnicos", $textos["SERVICIOS_TECNICOS"], $textos["SI_NO_".intval($datosProveedor->servicios_tecnicos)])
            ),array(
                HTML::mostrarDato("transporte", $textos["TRANSPORTE"], $textos["SI_NO_".intval($datosProveedor->transporte)])
            ),
            array(
                HTML::mostrarDato("publicidad", $textos["PUBLICIDAD"], $textos["SI_NO_".intval($datosProveedor->publicidad)])
            ),array(
                HTML::mostrarDato("servicios_especiales", $textos["SERVICIOS_ESPECIALES"], $textos["SI_NO_".intval($datosProveedor->servicios_especiales)]),
            ),
            array(
                HTML::mostrarDato("descripcion_servicio", $textos["TIPO_SERVICIO"], $descripcion_servicio),
                HTML::mostrarDato("fecha_inicio_cobro", $textos["FECHA_INICIO_COBRO"], $inicio_cobro[$datosProveedor->fecha_inicio_cobro])
            ),
            array(
                HTML::mostrarDato("tiempo_respuesta", $textos["TIEMPO_RESPUESTA"], $datosProveedor->tiempo_respuesta)
            ),array(
                HTML::mostrarDato("porcentaje_flete", $textos["PORCENTAJE_FLETE"], $datosProveedor->porcentaje_flete),
                HTML::mostrarDato("valor_flete", $textos["VALOR_FLETE"], $datosProveedor->valor_flete)
            ),array(
                HTML::mostrarDato("porcentaje_seguro", $textos["PORCENTAJE_SEGURO"], $datosProveedor->porcentaje_seguro),
                HTML::mostrarDato("valor_seguro", $textos["VALOR_SEGURO"], $datosProveedor->valor_seguro)
            )
        );


        $formularios["PESTANA_PAGOS"] = array(
            array(
                HTML::mostrarDato("id_forma_pago_contado", $textos["FORMA_PAGO_CONTADO"], $forma_pago_contado)
            ),
            array(
                HTML::mostrarDato("id_forma_pago_credito", $textos["FORMA_PAGO_CREDITO"], $forma_pago_credito),
                HTML::mostrarDato("tasa_pago_credito", $textos["TASA_PAGO_CREDITO"], $datosProveedor->tasa_pago_credito),
                HTML::mostrarDato("liquidacion_tasa_credito", $textos["LIQUIDACION_TASA_CREDITO"], $forma_liquidacion_tasa_credito[$datosProveedor->forma_liquidacion_tasa_credito])
            ),
            array(
                HTML::mostrarDato("porcentaje_primera_cuota", $textos["PRIMERA_CUOTA"], $datosProveedor->porcentaje_primera_cuota),
                HTML::mostrarDato("porcentaje_ultima_cuota", $textos["ULTIMA_CUOTA"], $datosProveedor->porcentaje_ultima_cuota)
            ),
            array(
                HTML::mostrarDato("pagos_anticipados", $textos["PAGOS_ANTICIPADOS"], $textos["SI_NO_".intval($datosProveedor->pagos_anticipados)]),
            ),
            array(
                HTML::mostrarDato("pagos_efectivo", $textos["PAGOS_EFECTIVOS"], $textos["SI_NO_".intval($datosProveedor->pagos_efectivo)])
            ),
            array(
                HTML::mostrarDato("transferencia_electronica", $textos["TRANSFERENCIA_ELECTRONICA"], $textos["SI_NO_".intval($datosProveedor->transferencia_electronica)])
            ),
            array(
                HTML::mostrarDato("tarjeta_credito", $textos["TARJETA_CREDITO"], $textos["SI_NO_".intval($datosProveedor->tarjeta_credito)])
            ),
            array(
                HTML::mostrarDato("triangulacion_bancaria", $textos["TRIANGULACION_BANCARIA"], $textos["SI_NO_".intval($datosProveedor->triangulacion_bancaria)])
            ),
            array(
                HTML::mostrarDato("forma_descuento_en_linea", $textos["TITULO_DESCUENTO_LINEA"], $textos["LINEA_".$datosProveedor->forma_liquidacion_descuento_en_linea])
            ),
            array(
                HTML::mostrarDato("forma_descuento_global", $textos["TITULO_DESCUENTO_GLOBAL"], $textos["GLOBAL_".$datosProveedor->forma_liquidacion_descuento_global])
            )
        );

        /*** Definición de pestaña de cuentas bancarias relacionadas ***/
        if (isset($item_cuenta)) {

            $formularios["PESTANA_CUENTAS"] = array(
                array(
                    HTML::generarTabla(
                        array("id","BANCO","CUENTA","TIPO_CUENTA"),
                        $item_cuenta,
                        array("I","D","I"),
                        "lista_items_cuentas",
                        false)
                )
            );
        } else {
            $formularios["PESTANA_CUENTAS"] = array(
                array(
                    HTML::mostrarDato("sin_cuentas", "", $textos["SIN_CUENTAS"])
                )
            );
        }

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

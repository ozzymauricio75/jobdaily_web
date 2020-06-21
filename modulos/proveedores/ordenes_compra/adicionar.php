<?php
/**
*
* Copyright (C) 2020 Jobdaily
* Raul Mauricio Oidor Lozano <ozzymauricio75@gmail.com>
*
* Este archivo es parte de:
* SEM :: Software empresarial a la medida
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
    if (($url_item) == "selector4") {
        echo SQL::datosAutoCompletar("seleccion_proveedores", $url_q);
    }
    if (($url_item) == "selector5") {
        echo SQL::datosAutoCompletar("seleccion_referencias_proveedor", $url_q);
    }
    exit;

}elseif (!empty($url_recargar)) {

    if ($url_elemento == "empresa") {
       $respuesta = HTML::generarDatosLista("empresas", "codigo", "nombre", "codigo = '$url_origen'");
    }

    if ($url_elemento == "sucursal") {
       $respuesta = HTML::generarDatosLista("sucursales", "codigo", "nombre", "codigo_empresa = '".$url_codigo."' AND codigo !='0' AND tipo != '0'");
    }

    HTTP::enviarJSON($respuesta);
}

if (!empty($url_generar)){

    $error  = "";
    $titulo = $componente->nombre;

    if ($sesion_usuario_maestro_ingreso){
        $sucursales  = SQL::obtenerValor("sucursales","codigo","codigo > '0' AND activo ='0' LIMIT 0,1");
        $compradores = SQL::obtenerValor("compradores","codigo","codigo > '0' AND activo ='1' LIMIT 0,1");
    } else {
        $tablas_sucursales = array(
            "s"   => "sucursales",
            "pus" => "perfiles_usuario"
        );
        $columnas_sucursales  = array(
            "codigo"          => "s.codigo",
            "nombre"          => "s.nombre_corto",
            "codigo_sucursal" => "pus.codigo_sucursal",
        );
        $condicion           = "s.codigo > '0' AND s.activo = '1' AND pus.codigo_sucursal = s.codigo";
        $consulta_sucursales = SQL::seleccionar($tablas_sucursales, $columnas_sucursales, $condicion, "", "", 0, 1);

        if (SQL::filasDevueltas($consulta_sucursales)){
            $sucursales = true;
        } else {
            $sucursales = false;
        }

        $tablas_compradores = array(
            "cpr"   => "compradores",
            "mt"    => "menu_terceros"
        );
        $columnas_compradores = array(
            "documento_tercero"  => "cpr.documento_tercero",
            "id"                 => "mt.id",
            "tercero"            => "mt.NOMBRE_COMPLETO"
        );

        $condicion_compradores  = "mt.id = cpr.documento_tercero";
        $consulta_compradores   = SQL::seleccionar($tablas_compradores, $columnas_compradores, $condicion_compradores, "", "", 0, 1);
 
        if (SQL::filasDevueltas($consulta_compradores)){
            $compradores = true;
        } else {
            $compradores = false;
        }
    }

    $tipos_documentos     = SQL::obtenerValor("tipos_documentos","codigo","codigo > '0' LIMIT 0,1");
    $municipios           = SQL::obtenerValor("municipios","codigo_iso","codigo_dane_departamento > '0' LIMIT 0,1");
    $unidades             = SQL::obtenerValor("unidades","codigo","codigo > '0' LIMIT 0,1");
    $estructuras          = SQL::obtenerValor("estructura_grupos", "codigo","codigo > '0' LIMIT 0,1");
    $tasas                = SQL::obtenerValor("tasas", "codigo", "codigo > '0' LIMIT 0,1");
    $monedas              = SQL::obtenerValor("tipos_moneda", "codigo", "codigo > '0' LIMIT 0,1");
    
    if ($sucursales && $compradores && $tipos_documentos && $municipios && $unidades && $estructuras && $tasas && $monedas){

        if ($sesion_usuario_maestro_ingreso){
            $sucursales  = HTML::generarDatosLista("sucursales","codigo","nombre_corto","codigo > '0' AND activo = '0'");
            $compradores = HTML::generarDatosLista("menu_compradores","id","NOMBRE_COMPLETO","id > '0' AND id_activo = '1'");
        } else {
            $tablas_sucursales = array(
                "pu"  => "perfiles_usuario",
                "s"   => "sucursales"
            );
            $columnas_sucursales  = array(
                "codigo_sucursal" => "pu.codigo_sucursal",
                "codigo_usuario"  => "pu.codigo_usuario",
                "nombre"          => "s.nombre_corto"
            );

            $condicion_sucursales = "pu.codigo_sucursal > '0' AND s.activo = '1' AND pu.codigo_sucursal = s.codigo";
            $consulta_sucursales  = SQL::seleccionar($tablas_sucursales, $columnas_sucursales, $condicion_sucursales,"s.codigo", "s.codigo ASC");
         
            if (SQL::filasDevueltas($consulta_sucursales)){
                $sucursales = array();
                while($datos_sucursales = SQL::filaEnObjeto($consulta_sucursales)){
                    $sucursales[(int)$datos_sucursales->codigo_sucursal] = $datos_sucursales->nombre;
                }
            }

            $consulta_compradores = SQL::seleccionar($tablas_compradores, $columnas_compradores, $condicion_compradores,"cpr.codigo","cpr.codigo ASC");
            $compradores = array();

            while ($datos_compradores = SQL::filaEnObjeto($consulta_compradores)){
                $compradores[(int)$datos_compradores->codigo] = $datos_compradores->tercero;
            }
        }

        $tipos_documentos      = HTML::generarDatosLista("tipos_documentos","codigo","descripcion","codigo > '0'");
        $unidades              = HTML::generarDatosLista("unidades","codigo","nombre","codigo > '0'");
        $monedas               = HTML::generarDatosLista("tipos_moneda","codigo","nombre","codigo > '0'");
        $tasas                 = HTML::generarDatosLista("tasas", "codigo", "descripcion","codigo > '0'");
        $tipo_comprobante      = HTML::generarDatosLista("tipos_comprobantes", "codigo", "descripcion","codigo > '0'");

        $tipos_documento_orden = SQL::obtenerValor("tipos_documentos","descripcion","codigo = '1' AND abreviaturas = 'OC'");
  
        $regimen = array(
            "1" => $textos["COMUN"],
            "2" => $textos["SIMPLIFICADO"]
        );

        $encabezado["PESTANA_GENERAL"] = array(
            array(
                HTML::mostrarDato("nit_proveedor_mostrar",$textos["NIT_PROVEEDOR"], "", "", array("class"=>"oculto")),
                HTML::mostrarDato("razon_social_proveedor_mostrar",$textos["RAZON_SOCIAL_PROVEEDOR"], "", "", array("class"=>"oculto")),
                HTML::mostrarDato("numero_propuesta",$textos["NUMERO_PROPUESTA"], "", "", array("class"=>"oculto"))
            ),
            array(
                HTML::campoTextoCorto("*nit_proveedor",$textos["NIT_PROVEEDOR"], 15, 15, "", array("title"=>$textos["AYUDA_NIT_PROVEEDOR"],"onKeyPress"=>"return campoEntero(event)", "onBlur"=>"cargarProveedor()")),

                HTML::campoTextoCorto("*razon_social_proveedor",$textos["RAZON_SOCIAL_PROVEEDOR"], 45, 255, "", array("title"=>$textos["AYUDA_RAZON_SOCIAL_PROVEEDOR"],"class"=>"autocompletable", "onFocus" => "acProveedor(this)"))
            )
        );
        
        $formularios["PESTANA_DATOS_PEDIDO"] = array(
            array(
                HTML::agrupador(
                    array(
                        array(
                            HTML::campoTextoCorto("*selector4", $textos["PROVEEDOR"], 40, 255, "", array("title" => $textos["AYUDA_PROVEEDOR"], "class" => "autocompletable"))
                            .HTML::campoOculto("documento_identidad_proveedor", "")
                        ),
                        array(
                            HTML::listaSeleccionSimple("*regimen",$textos["REGIMEN"], $regimen, "", array("title",$textos["AYUDA_REGIMEN"], "class"=>"regimen","onChange"=>"activaIva()"))
                        ),
                        array(
                            HTML::campoTextoCorto("*selector1",$textos["MUNICIPIO"], 40, 255, "", array("title",$textos["AYUDA_MUNICIPIO"], "class"=>"autocompletable")).
                            HTML::campoOculto("id_municipio","")
                        ),
                        array(
                            HTML::campoTextoCorto("*direccion",$textos["DIRECCION"], 40, 255, "", array("title",$textos["AYUDA_DIRECCION"]))
                        ),
                        array(
                            HTML::campoTextoCorto("*telefono",$textos["TELEFONO"], 15, 15, "", array("title",$textos["AYUDA_TELEFONO"])),
                            HTML::campoTextoCorto("celular",$textos["CELULAR"], 15, 15, "", array("title",$textos["AYUDA_CELULAR"]))
                        ),
                        array(
                            HTML::campoTextoCorto("correo_electronico",$textos["CORREO_ELECTRONICO"], 40, 255, "", array("title",$textos["AYUDA_CORREO_ELECTRONICO"]))
                        ),
                    ),
                    $textos["DATOS_PROVEEDOR"]
                )
            ),
            array(
                HTML::agrupador(
                    array(
                                             array(
                            //HTML::mostrarDato("fecha_documento_mostrar",$textos["FECHA_DOCUMENTO"], "", "", array("class"=>"oculto")),
                            HTML::contenedor(
                                HTML::campoTextoCorto("fecha_documento",$textos["FECHA_DOCUMENTO"], 8, 8, date("Y-m-d"), array("title"=>$textos["AYUDA_FECHA"],"onChange"=>"activaFechaFinal()", "readOnly"=>"true"))
                                .HTML::campoOculto("minDate", date("Y-m-d")),
                                
                                array("id"=>"fecha_pedido","class"=>"fecha_pedido")
                            ),
                            HTML::mostrarDato("tipo_documento_ordenes",$textos["TIPO_DOCUMENTO"], $tipos_documento_orden),
                            HTML::campoOculto("id_tipo_documento", $tipos_documento_orden),
                        ),
                        array(
                            HTML::listaSeleccionSimple("*empresa", $textos["EMPRESA"], HTML::generarDatosLista("empresas", "codigo", "razon_social","codigo != 0"), "", array("title" => $textos["AYUDA_EMPRESAS"],"onChange" => "recargarLista('codigo_empresa','codigo_sucursal');recargarListaEmpresas();")),

                            HTML::listaSeleccionSimple("*sucursal", $textos["CONSORCIO"], HTML::generarDatosLista("sucursales", "codigo", "nombre","codigo != 0 AND tipo != '0'"), "", array("title" => $textos["AYUDA_CONSORCIO"],"onChange" => "recargarListaEmpresas();")),
                            HTML::campoOculto("id_sucursal", "")
                        )
                    ),
                    $textos["DATOS_DOCUMENTO"]
                )
            )
        );
        $formularios["PESTANA_NEGOCIACION"] = array(
            array(
                HTML::listaSeleccionSimple("*codigo_comprador",$textos["COMPRADOR"], $compradores, "", array("title",$textos["AYUDA_COMPRADOR"]))
            ),
            array(
                HTML::marcaChequeo("iva_incluido",$textos["IVA_INCLUIDO"], 1, false, array("title"=>$textos["AYUDA_IVA_INCLUIDO"], "class"=>"iva_incluido"))
            ),
            array(
                HTML::listaSeleccionSimple("*id_moneda",$textos["MONEDA"], $monedas, "", array("title",$textos["AYUDA_MONEDA"]))
            ),
            array(
                HTML::agrupador(
                    array(
                        array(
                            HTML::marcaChequeo("aplica_descuento_financiero_fijo",$textos["DESCUENTO_FINANCIERO_FIJO"], 1, false, array("title"=>$textos["AYUDA_APLICA_DESCUENTO_FINANCIERO_FIJO"], "class"=>"descuento_financiero_fijo","onClick"=>"activaCampos(2,0)")),
                            HTML::campoTextoCorto("descuento_financiero_fijo","", 6, 8, "", array("title"=>$textos["AYUDA_DESCUENTO_FINANCIERO_FIJO"],"onKeyPress"=>"return campoDecimal(event)", "class"=>"fijo oculto")),
                            HTML::marcaChequeo("aplica_solo_factura_descuento_fijo",$textos["APLICA_FACTURA"], 1, false, array("title"=>$textos["AYUDA_APLICA_FACTURA"], "class"=>"fijo oculto")),
                        ),
                        array(
                            HTML::marcaChequeo("aplica_descuento_financiero_pronto_pago",$textos["DESCUENTO_FINANCIERO_PRONTO"], 1, false, array("title"=>$textos["AYUDA_APLICA_DESCUENTO_FINANCIERO_PRONTO"], "class"=>"descuento_financiero_pronto_pago","onClick"=>"activaCampos(3,0)")),
                            HTML::campoTextoCorto("descuento_financiero_pronto_pago","", 6, 8, "", array("title"=>$textos["AYUDA_DESCUENTO_FINANCIERO_PRONTO"],"onKeyPress"=>"return campoDecimal(event)", "class"=>"pronto_pago oculto")),
                            HTML::campoTextoCorto("numero_dias_pronto_pago",$textos["NUMERO_DIAS_PRONTO_PAGO"], 8, 3, "", array("title"=>$textos["AYUDA_NUMERO_DIAS_PRONTO_PAGO"], "onKeyPress"=>"return campoEntero(event)", "class"=>"pronto_pago oculto")),
                            HTML::marcaChequeo("aplica_solo_factura_descuento_pronto_pago",$textos["APLICA_FACTURA"], 1, false, array("title"=>$textos["AYUDA_APLICA_FACTURA"], "class"=>"pronto_pago oculto")),
                        ),
                        array(
                            HTML::marcaChequeo("aplica_descuento_global1",$textos["DESCUENTO_GLOBAL1"], 1, false, array("title"=>$textos["AYUDA_APLICA_DESCUENTO_GLOBAL1"], "class"=>"aplica_descuento_global1","onClick"=>"activaCampos(1,1)")),
                            HTML::campoTextoCorto("descuento_global1","", 6, 8, "", array("title"=>$textos["AYUDA_DESCUENTO_GLOBAL1"],"onKeyPress"=>"return campoDecimal(event)", "class"=>"global1 oculto")),
                            HTML::marcaChequeo("aplica_solo_factura_descuento_global1",$textos["APLICA_FACTURA"], 1, false, array("title"=>$textos["AYUDA_APLICA_FACTURA"], "class"=>"global1 oculto")),
                            HTML::marcaChequeo("descuento_global1_iva_incluido",$textos["GLOBAL1_IVA_INCLUIDO"], 1, false, array("title"=>$textos["AYUDA_GLOBAL1_IVA_INCLUIDO"], "class"=>"global1 oculto")),
                        ),
                        array(
                            HTML::marcaChequeo("aplica_descuento_global2",$textos["DESCUENTO_GLOBAL2"], 1, false, array("title"=>$textos["AYUDA_APLICA_DESCUENTO_GLOBAL2"], "class"=>"aplica_descuento_global2 oculto","onClick"=>"activaCampos(1,2)")),
                            HTML::campoTextoCorto("descuento_global2","", 6, 8, "", array("title"=>$textos["AYUDA_DESCUENTO_GLOBAL2"],"onKeyPress"=>"return campoDecimal(event)", "class"=>"global2 oculto")),
                            HTML::marcaChequeo("aplica_solo_factura_descuento_global2",$textos["APLICA_FACTURA"], 1, false, array("title"=>$textos["AYUDA_APLICA_FACTURA"], "class"=>"global2 oculto")),
                        ),
                        array(
                            HTML::marcaChequeo("aplica_descuento_global3",$textos["DESCUENTO_GLOBAL3"], 1, false, array("title"=>$textos["AYUDA_APLICA_DESCUENTO_GLOBAL3"], "class"=>"aplica_descuento_global3 oculto","onClick"=>"activaCampos(1,3)")),
                            HTML::campoTextoCorto("descuento_global3","", 6, 8, "", array("title"=>$textos["AYUDA_DESCUENTO_GLOBAL3"],"onKeyPress"=>"return campoDecimal(event)", "class"=>"global3 oculto")),
                            HTML::marcaChequeo("aplica_solo_factura_descuento_global3",$textos["APLICA_FACTURA"], 1, false, array("title"=>$textos["AYUDA_APLICA_FACTURA"], "class"=>"global3 oculto")),
                        )
                    ),
                    $textos["DESCUENTOS"]
                )
            ),
            array(
                HTML::campoTextoCorto("numero_dias_pago",$textos["NUMERO_DIAS_PAGO"], 8, 3, "", array("title"=>$textos["AYUDA_NUMERO_DIAS_PAGO"], "onKeyPress"=>"return campoEntero(event)"))
            ),
            array(
                HTML::campoTextoCorto("numero_entregas",$textos["NUMERO_ENTREGAS"], 8, 3, "", array("title"=>$textos["AYUDA_NUMERO_ENTREGAS"], "onKeyPress"=>"return campoEntero(event)", "onKeyUp"=>"activaFechaFinal()")),
                HTML::contenedor(
                    HTML::campoTextoCorto("fecha_final_entregas", $textos["FECHA_FINAL_ENTREGAS"], 8, 8, "", array("title"=>$textos["AYUDA_FECHA_FINAL_ENTREGAS"], "class"=>"fecha_final_entregas")),
                    array("id"=>"fechas_entregas","class"=>"fechas_entregas oculto")
                )
            ),
            array(
                HTML::campoTextoLargo("observaciones",$textos["OBSERVACIONES"], 2, 95, "", array("title"=>$textos["AYUDA_OBSERVACIONES"])),
                HTML::campoOculto("datos_incompletos_estructura",$textos["SELECCIONAR_ESTRUCTURA_COMPLETA"]),
                HTML::campoOculto("existe_caracteristica",$textos["EXISTE_CARACTERISTICA"]),
                HTML::campoOculto("seleccionar_caracteristica",$textos["SELECCIONAR_CARACTERISTICA"]),
                HTML::campoOculto("id_detalle",0),
                HTML::campoOculto("id_estructura_grupo",0),
                HTML::campoOculto("error_nit_proveedor",$textos["ERROR_NIT_PROVEEDOR"]),
                HTML::campoOculto("error_razon_social_proveedor",$textos["ERROR_RAZON_SOCIAL_PROVEEDOR"]),
                HTML::campoOculto("error_municipio",$textos["ERROR_MUNICIPIO"]),
                HTML::campoOculto("error_direccion",$textos["ERROR_DIRECCION"]),
                HTML::campoOculto("error_telefono",$textos["ERROR_TELEFONO"]),
                HTML::campoOculto("error_fecha_pedido",$textos["ERROR_FECHA_PEDIDO"]),
                HTML::campoOculto("error_fecha_final_entregas",$textos["ERROR_FECHA_FINAL_ENTREGAS"]),
                HTML::campoOculto("error_articulo",$textos["ERROR_ARTICULO"]),
                HTML::campoOculto("error_estructura_grupo",$textos["ERROR_ESTRUCTURA_GRUPO"]),
                HTML::campoOculto("error_referencia",$textos["ERROR_REFERENCIA"]),
                HTML::campoOculto("error_detalle",$textos["ERROR_DETALLE"]),
                HTML::campoOculto("error_tasa",$textos["ERROR_TASA"]),
                HTML::campoOculto("error_criterio",$textos["ERROR_CRITERIO"]),
                HTML::campoOculto("error_caracteristica",$textos["ERROR_CARACTERISTICA"]),
                HTML::campoOculto("error_cantidad_total",$textos["ERROR_CANTIDAD_TOTAL"]),
                HTML::campoOculto("error_valor_unitario",$textos["ERROR_VALOR_UNITARIO"]),
                HTML::campoOculto("error_porcentaje_descuento_linea",$textos["ERROR_PORCENTAJE_DESCUENTO_LINEA"]),
                HTML::campoOculto("error_cantidad_detalle",$textos["ERROR_CANTIDAD_DETALLE"]),
                HTML::campoOculto("error_concepto",$textos["ERROR_CONCEPTO"]),
                HTML::campoOculto("error_color",$textos["ERROR_COLOR"]),
                HTML::campoOculto("error_fecha_entrega_articulo",$textos["ERROR_FECHA_ENTREGA_ARTICULO"]),
                HTML::campoOculto("error_descuento_financiero_fijo",$textos["ERROR_DESCUENTO_FINANCIERO_FIJO"]),
                HTML::campoOculto("error_descuento_financiero_pronto_pago",$textos["ERROR_DESCUENTO_FINANCIERO_PRONTO_PAGO"]),
                HTML::campoOculto("error_descuento_global1",$textos["ERROR_DESCUENTO_GLOBAL1"]),
                HTML::campoOculto("error_descuento_global2",$textos["ERROR_DESCUENTO_GLOBAL2"]),
                HTML::campoOculto("error_descuento_global3",$textos["ERROR_DESCUENTO_GLOBAL3"]),
                HTML::campoOculto("error_cantidad_detalle_mayor",$textos["ERROR_CANTIDAD_DETALLE_MAYOR"]),
                HTML::campoOculto("id_unidad_medida_pedidos",$id_unidad_medida_pedidos),
                HTML::campoOculto("no_maneja_conceptos",$textos["NO_MANEJA_CONCEPTOS"])
            ),
            array(
                HTML::campoOculto("id_sucursal_pedidos",$id_sucursal_pedidos),
                HTML::campoOculto("id_unidad_actual",0),
                HTML::campoOculto("aplica_descuento_global1_actual",0),
                HTML::campoOculto("aplica_descuento_global2_actual",0),
                HTML::campoOculto("aplica_descuento_global3_actual",0),
                HTML::campoOculto("aplica_descuento_linea_actual",0),
                HTML::campoOculto("id_categoria_actual",0),
                HTML::campoOculto("id_grupo1_actual",0),
                HTML::campoOculto("id_grupo2_actual",0),
                HTML::campoOculto("id_grupo3_actual",0),
                HTML::campoOculto("id_grupo4_actual",0),
                HTML::campoOculto("id_grupo5_actual",0),
                HTML::campoOculto("id_grupo6_actual",0),
                HTML::campoOculto("referencia_actual",""),
                HTML::campoOculto("detalle_actual",""),
                HTML::campoOculto("id_tasa_actual",0),
                HTML::campoOculto("maneja_color_actual",0),
                HTML::campoOculto("id_criterio_subnivel_articulo_actual",0),
                HTML::campoOculto("maneja_caracteristica_actual",0),
                HTML::campoOculto("valor_unitario_actual",0),
                HTML::campoOculto("descuento_linea_actual",0),
                HTML::campoOculto("descuento_global1_actual",0),
                HTML::campoOculto("descuento_global2_actual",0),
                HTML::campoOculto("descuento_global3_actual",0),
                HTML::campoOculto("regimen_actual","1"),
                HTML::campoOculto("id_propuesta_pedido",0)
            )
        );

        $formularios["PESTANA_ARTICULOS"] = array(
            array(
                HTML::contenedor(
                    HTML::marcaChequeo("articulo_nuevo", $textos["ARTICULO_NUEVO"], 1, false, array("title"=>$textos["AYUDA_ARTICULO_NUEVO"],"class"=>"crear_articulo","onClick"=>"activaArticulos(this)")),
                    array("id"=>"contenedor_articulo_nuevo","class"=>"movimiento")
                )
            ),
            array(
                HTML::contenedor(
                    HTML::agrupador(
                        array(
                            array(
                                HTML::campoTextoCorto("+articulo",$textos["ARTICULO"], 60, 255, "", array("title"=>$textos["AYUDA_ARTICULO"],"class"=>"autocompletable articulo_existe modificar_articulo")).
                                HTML::campoOculto("id_articulo","")
                            ),
                            array(
                                HTML::contenedor(
                                    HTML::agrupador(
                                        array(
                                            array(
                                                HTML::listaSeleccionSimple("+id_categoria", $textos["CATEGORIA"], $categorias, "0", array("title" => $textos["AYUDA_CATEGORIA"], "onChange"=>"cargarEstructuraGrupos(1), removerDatosEstructura(), idEstructuraGrupo()")),
                                                HTML::listaSeleccionSimple("id_grupo1", $textos["GRUPO1"], "", "0", array("title" => $textos["AYUDA_GRUPO1"], "class"=>"oculto grupo1", "onChange"=>"cargarEstructuraGrupos(2), removerDatosEstructura(), idEstructuraGrupo()")),
                                                HTML::listaSeleccionSimple("id_grupo2", $textos["GRUPO2"], "", "0", array("title" => $textos["AYUDA_GRUPO2"], "class"=>"oculto grupo2", "onChange"=>"cargarEstructuraGrupos(3), removerDatosEstructura(), idEstructuraGrupo()")),
                                                HTML::listaSeleccionSimple("id_grupo3", $textos["GRUPO3"], "", "0", array("title" => $textos["AYUDA_GRUPO3"], "class"=>"oculto grupo3", "onChange"=>"cargarEstructuraGrupos(4), removerDatosEstructura(), idEstructuraGrupo()")),
                                                HTML::listaSeleccionSimple("id_grupo4", $textos["GRUPO4"], "", "0", array("title" => $textos["AYUDA_GRUPO4"], "class"=>"oculto grupo4", "onChange"=>"cargarEstructuraGrupos(5), removerDatosEstructura(), idEstructuraGrupo()")),
                                                HTML::listaSeleccionSimple("id_grupo5", $textos["GRUPO5"], "", "0", array("title" => $textos["AYUDA_GRUPO5"], "class"=>"oculto grupo5", "onChange"=>"cargarEstructuraGrupos(6), removerDatosEstructura(), idEstructuraGrupo()")),
                                                HTML::listaSeleccionSimple("id_grupo6", $textos["GRUPO6"], "", "0", array("title" => $textos["AYUDA_GRUPO6"], "class"=>"oculto grupo6")),
                                                HTML::campoOculto("nivel",1)
                                            )
                                        ),
                                        $textos["ESTRUCTURA_GRUPO"]
                                    ),
                                    array("id"=>"estructura_grupo","class"=>"articulo_nuevo modificar_detalle oculto")
                                )
                            ),
                            array(
                                HTML::campoTextoCorto("+referencia",$textos["REFERENCIA"], 5, 15, "", array("title"=>$textos["AYUDA_REFERENCIA"],"class"=>"oculto modificar articulo_nuevo")),

                                HTML::listaSeleccionSimple("+id_tasa", $textos["TASA"], $tasas, "", array("title" => $textos["AYUDA_TASA"], "class"=>"oculto modificar_detalle articulo_nuevo")),

                                HTML::marcaChequeo("maneja_color",$textos["MANEJA_COLOR"], 1, false, array("title"=>$textos["AYUDA_MANEJA_COLOR"],"onClick"=>"activaCampos(7,0)", "class"=>"movimiento maneja_color")),

                                HTML::marcaChequeo("maneja_criterio",$textos["MANEJA_CRITERIO"], 1, false, array("title"=>$textos["AYUDA_MANEJA_CRITERIO"],"onClick"=>"activaCampos(5,0)", "class"=>"oculto movimiento  maneja_criterio articulo_nuevo")),

                                HTML::marcaChequeo("maneja_criterio_articulo",$textos["MANEJA_CRITERIO"], 1, false, array("title"=>$textos["AYUDA_MANEJA_CRITERIO"],"onClick"=>"cargarSubnivelArticulo()", "class"=>"movimiento  maneja_criterio_articulo")),

                                HTML::listaSeleccionSimple("+id_subnivel_articulo", $textos["SUBNIVEL"], "", "", array("title" => $textos["AYUDA_SUBNIVEL"], "class"=>"oculto movimiento subnivel", "onChange"=>"cargaCriterio()")),

                                HTML::listaSeleccionSimple("+id_criterio_subnivel_articulo", $textos["CRITERIO"], "", "", array("title" => $textos["AYUDA_CRITERIO"], "class"=>"oculto movimiento criterio", "onChange"=>"activaConcepto()")),
                            ),
                            array(
                                HTML::campoTextoCorto("+detalle",$textos["DETALLE"], 50, 255, "", array("title"=>$textos["AYUDA_DETALLE"],"class"=>"oculto articulo_nuevo modificar_detalle")),
                            ),
                            array(
                                HTML::marcaChequeo("maneja_caracteristicas",$textos["MANEJA_CARACTERISTICAS"], 1, false, array("title"=>$textos["AYUDA_MANEJA_CARACTERISTICAS"],"onClick"=>"activaCampos(6,0)", "class"=>"oculto maneja_caracteristicas articulo_nuevo")),

                                HTML::listaSeleccionSimple("+id_caracteristica", $textos["CARACTERISTICA"], "", "", array("title" => $textos["AYUDA_CARACTRISTICA"],"class"=>"caracteristica oculto")),

                                HTML::boton("botonAgregarCaracteristica", $textos["AGREGAR_CARACTERISTICA"], "agregarCaracteristica();", "adicionar",array("class"=>"caracteristica item_caracteristica oculto"),"etiqueta"),

                                HTML::contenedor(
                                    HTML::boton("botonRemoverCaracteristica", "", "removerCaracteristica(this);", "eliminar",array("class"=>"item_caracteristica")),
                                    array("id" => "removerCaracteristica", "style" => "display: none")
                                ),
                                HTML::contenedor(
                                    HTML::generarTabla(
                                        array("id","","CARACTERISTICA"),
                                        "",
                                        array("I","I"),
                                        "listaCaracteristica",
                                        false
                                    ),
                                    array("id"=>"caracteristica_articulo","class"=>"caracteristica oculto")
                                )
                            ),
                            array(
                                HTML::listaSeleccionSimple("+id_unidad",$textos["UNIDAD_MEDIDA"], $unidades, $id_unidad_medida_pedidos, array("title"=>$textos["AYUDA_UNIDAD"])),

                                HTML::campoTextoCorto("+cantidad_total_articulo",$textos["CANTIDAD_TOTAL"], 5, 15, "", array("title"=>$textos["AYUDA_CANTIDAD_TOTAL"], "onKeyPress"=>"return campoDecimal(event)", "onKeyUp"=>"activaDetalle()", "onBlur"=>"cargarCantidad()", "class"=>"movimiento")),

                                HTML::campoTextoCorto("+valor_unitario",$textos["VALOR_UNITARIO"], 10, 15, "", array("title"=>$textos["AYUDA_VALOR_UNITARIO"], "onKeyPress"=>"return campoDecimal(event)")),
                                HTML::campoOculto("cantidad_total_control",0),
                            ),
                            array(
                                HTML::marcaChequeo("aplica_descuento_linea",$textos["DESCUENTO_LINEA"], 1, false, array("title"=>$textos["AYUDA_APLICA_DESCUENTO_LINEA"], "class"=>"descuento_linea modificar","onClick"=>"activaCampos(4,0)")),

                                HTML::campoTextoCorto("descuento_linea","", 2, 8, "", array("title"=>$textos["AYUDA_DESCUENTO_LINEA"],"onKeyPress"=>"return campoDecimal(event)", "class"=>"linea oculto modificar")),

                                HTML::campoTextoCorto("observaciones_articulo",$textos["OBSERVACIONES"], 50, 78, "", array("title"=>$textos["AYUDA_OBSERVACIONES"], "class"=>"movimiento")),
                            ),
                            array(
                                HTML::selectorArchivo("foto_articulo", $textos["FOTO"], array("title" => $textos["AYUDA_FOTO"])),
                                HTML::campoTextoCorto("+fecha_entrega_articulo",$textos["FECHA_ENTREGA"], 8, 8, date("Y-m-d"), array("title"=>$textos["AYUDA_FECHA_ENTREGA"],"class"=>"selectorFechaBloquear fecha_final_entrega_articulo")),
                            ),
                            array(
                                HTML::contenedor(
                                    HTML::agrupador(
                                        array(
                                            array(
                                                HTML::listaSeleccionSimple("+id_sucursal_destino",$textos["SUCURSAL_DESTINO"], $sucursales, "", array("title",$textos["AYUDA_SUCURSAL_DESTINO"])),

                                                HTML::listaSeleccionSimple("+id_concepto_criterio_subnivel_articulo",$textos["CONCEPTO"], "", "", array("title",$textos["AYUDA_CONCEPTO"], "class"=>"oculto concepto")),
                                                //HTML::listaSeleccionSimple("+id_color",$textos["COLOR"], $colores, "", array("title",$textos["AYUDA_COLOR"], "class"=>"oculto colores")),
                                                HTML::campoTextoCorto("+id_color",$textos["COLOR"], 15, 50, "", array("title",$textos["AYUDA_COLOR"], "class"=>"oculto colores")),

                                                HTML::campoTextoCorto("+cantidad_detalle",$textos["CANTIDAD_DETALLE"], 5, 15, "", array("title"=>$textos["AYUDA_CANTIDAD_DETALLE"], "onKeyPress"=>"return campoDecimal(event)")),
                                                HTML::mostrarDato("cantidad_pendiente",$textos["CANTIDAD_PENDIENTE"], ""),

                                                HTML::boton("botonAgregarArticulo", $textos["AGREGAR_ARTICULO"], "agregarArticulo();", "adicionar",array("class"=>"agregar_articulo"),"etiqueta"),

                                                HTML::contenedor(
                                                    "",
                                                    array("id"=>"indicadorEsperaFormulario")
                                                ),
                                                HTML::contenedor(
                                                    HTML::boton("botonRemoverArticulo", "", "removerArticulo(this);", "eliminar", array("class"=>"removerArticuloTabla")),
                                                    array("id" => "removerArticulo", "style" => "display: none")
                                                ),
                                                HTML::contenedor(
                                                    HTML::boton("botonModificarArticulo", "", "modificarArticulo(this);", "modificar", array("class"=>"modificarArticuloTabla")),
                                                    array("id" => "modificarArticulo", "style" => "display: none")
                                                )
                                            ),
                                        ),
                                        $textos["DETALLE_PEDIDO"]
                                    ),
                                    array("id"=>"detalle_pedido","class"=>"detalle_pedido oculto movimiento")
                                )
                            )
                        ),
                        $textos["DETALLE_ARTICULO"]
                    ),
                    array("id"=>"datos_totales_articulo")
                ),
                HTML::contenedor(
                    HTML::boton("botonActualizar", $textos["ACTUALIZAR_ARTICULO"], "actualizarArticulo();", "restaurar", array("class"=>"actualizarArticulo")),
                    array("id"=>"boton_actualizar","class"=>"actualizarArticulo oculto")
                ),
                HTML::generarTabla(
                    array("id","MODIFICAR","ELIMINAR","SUCURSAL_DESTINO","ARTICULO","REFERENCIA","DETALLE","CONCEPTO","COLOR","CANTIDAD","UNIDAD_MEDIDA","VALOR_UNITARIO","SUBTOTAL","TASA","DESCUENTO","FECHA_ENTREGA","FOTO","OBSERVACIONES"),
                    "",
                    array("I","I","I","I","I","I","I","I","I","I","D","D","C","D","C","C","I"),
                    "listaArticulos",
                    false
                )
            ),
            array(
                HTML::campoOculto("id_articulo_modificar",""),
                HTML::campoOculto("id_pedido_detalle_modificar",""),
                HTML::campoOculto("fecha_entrega_modificar",""),
                HTML::campoOculto("id_unidad_modificar",""),
            )
        );

        $funciones["PESTANA_TOTAL_PEDIDO"] = "totalPedido()";
        $opcionesLi["PESTANA_TOTAL_PEDIDO"] = array(
                "class" => "oculto total_pedido"
        );
        $formularios["PESTANA_TOTAL_PEDIDO"] = array(
            array(
                HTML::campoTextoCorto("total_unidades",$textos["TOTAL_UNIDADES"], 10, 10, "", array("readOnly"=>"true"))
            ),
            array(
                HTML::campoTextoCorto("subtotal_pedido",$textos["SUBTOTAL"], 10, 10, "", array("readOnly"=>"true"))
            ),
            array(
                HTML::campoTextoCorto("total_descuentos_linea_pedido",$textos["TOTAL_DESCUENTOS_LINEA"], 10, 10, "", array("readOnly"=>"true","class"=>"total_descuentos_linea oculto"))
            ),
            array(
                HTML::campoTextoCorto("total_descuentos_globales_pedido",$textos["TOTAL_DESCUENTOS_GLOBALES"], 10, 10, "", array("readOnly"=>"true","class"=>"total_descuentos_globales oculto"))
            ),
            array(
                HTML::campoTextoCorto("total_descuentos_financieros_pedido",$textos["TOTAL_DESCUENTOS_FINANCIEROS"], 10, 10, "", array("readOnly"=>"true","class"=>"total_descuentos_financieros oculto"))
            ),
            array(
                HTML::campoTextoCorto("total_iva_pedido",$textos["TOTAL_IVA"], 10, 10, "", array("readOnly"=>"true","class"=>"total_iva"))
            ),
            array(
                HTML::campoTextoCorto("total_pedido",$textos["TOTAL_PEDIDO"], 10, 10, "", array("readOnly"=>"true","class"=>"total_iva"))
            )
        );
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR_PEDIDO"], "imprimirItem('1');", "aceptar",array("class"=>"terminar_pedido oculto")),
        );

        $contenido = HTML::generarPestanas($formularios, $botones, "", $funciones, $encabezado, $opcionesLi);
    } else {
        $contenido = "";
        if (!$sucursales){
            $error .= $textos["ERROR_PERMISO_SUCURSALES"];
        }
        if (!$compradores){
            $error .= $textos["ERROR_COMPRADORES"];
        }
        if (!$tipos_documentos){
            $error .= $textos["ERROR_TIPOS_DOCUMENTOS"];
        }
        if (!$municipios){
            $error .= $textos["ERROR_MUNICIPIOS"];
        }

        if (!$unidades){
            $error .= $textos["ERROR_UNIDADES"];
        }
        if (!$estructuras){
            $error .= $textos["CREAR_ESTRUCTURA_GRUPOS"];
        }
        if (!$tasas){
            $error .= $textos["CREAR_TASAS"];
        }
        if (!$monedas){
            $error .= $textos["CREAR_MONEDAS"];
        }
    }

    // Enviar datos para la generacion del formulario al script que origino la peticion
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

//Adicionar los datos provenientes del formulario
} elseif (!empty($forma_procesar)) {
    // Asumir por defecto que no hubo error
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];

    /*// Validar el ingreso de los datos requeridos
    if(!isset($forma_id_propuesta_pedido) || $forma_id_propuesta_pedido <=0){
        $error   = true;
        $mensaje = $textos["ENCABEZADO_VACIO"];

    } else if(!isset($forma_id_movimiento_tabla)){
        $error   = true;
        $mensaje = $textos["MOVIMIENTO_VACIO"];

    } else {
        if (!isset($forma_iva_incluido) || empty($forma_iva_incluido)){
            $forma_iva_incluido = "0";
        }

        if (!isset($forma_numero_dias_pago) || empty($forma_numero_dias_pago)){
            $forma_numero_dias_pago = "1";
        }
        if (!isset($forma_numero_entregas)){
            $forma_numero_entregas = "0";
        }
        if (!isset($forma_fecha_final_entregas)){
            $forma_fecha_final_entregas = "0000-00-00";
        }
        if (!isset($forma_observaciones)){
            $forma_observaciones = "";
        }
        if (!isset($forma_descuento_financiero_fijo) || empty($forma_descuento_financiero_fijo)){
            $forma_descuento_financiero_fijo = 0;
            $forma_aplica_solo_factura_descuento_fijo = "0";
        }
        if (!isset($forma_aplica_solo_factura_descuento_fijo)){
            $forma_aplica_solo_factura_descuento_fijo = "0";
        }
        if (!isset($forma_descuento_financiero_pronto_pago) || empty($forma_descuento_financiero_pronto_pago)){
            $forma_descuento_financiero_pronto_pago = 0;
            $forma_numero_dias_pronto_pago = 0;
            $forma_aplica_solo_factura_descuento_pronto_pago = "0";
        }
        if (!isset($forma_aplica_solo_factura_descuento_pronto_pago)){
            $forma_aplica_solo_factura_descuento_pronto_pago = "0";
        }
        if (!isset($forma_numero_dias_pronto_pago) || empty($forma_numero_dias_pronto_pago)){
            $forma_numero_dias_pronto_pago = 0;
        }
        if (!isset($forma_descuento_global1)){
            $forma_descuento_global1 = 0;
            $forma_aplica_solo_factura_descuento_global1 = "0";
            $forma_descuento_global1_iva_incluido = "0";
        }
        if (!isset($forma_descuento_global2)){
            $forma_descuento_global2 = 0;
            $forma_aplica_solo_factura_descuento_global2 = "0";
        }
        if (!isset($forma_descuento_global3)){
            $forma_descuento_global3 = 0;
            $forma_aplica_solo_factura_descuento_global3 = "0";
        }
        if (!isset($forma_aplica_solo_factura_descuento_global1)){
            $forma_aplica_solo_factura_descuento_global1 = "0";
        }
        if (!isset($forma_descuento_global1_iva_incluido)){
            $forma_descuento_global1_iva_incluido = "0";
        }
        if (!isset($forma_aplica_solo_factura_descuento_global2)){
            $forma_aplica_solo_factura_descuento_global2 = "0";
        }
        if (!isset($forma_aplica_solo_factura_descuento_global3)){
            $forma_aplica_solo_factura_descuento_global3 = "0";
        }
        if (!isset($forma_participacion) || empty($forma_participacion)){
            $forma_participacion = 0;
        }
        $registros = SQL::obtenerValor("movimiento_propuesta_pedidos","COUNT(id)","id_propuesta_pedido='$forma_id_propuesta_pedido'");
        $datos = array(
            "id_municipio"         => $forma_id_municipio,
            "direccion"            => $forma_direccion,
            "telefono"             => $forma_telefono,
            "celular"              => $forma_celular,
            "correo_electronico"   => $forma_correo_electronico,
            "id_comprador"         => $forma_id_comprador,
            "id_moneda"            => $forma_id_moneda,
            "cantidad_registros"   => $registros,
            "estado"               => "0",
            "participacion"        => $forma_participacion,
            "descuento_global1" => $forma_descuento_global1,
            "aplica_solo_factura_descuento_global1" => $forma_aplica_solo_factura_descuento_global1,
            "descuento_global1_iva_incluido" => $forma_descuento_global1_iva_incluido,
            "descuento_global2" => $forma_descuento_global2,
            "aplica_solo_factura_descuento_global2" => $forma_aplica_solo_factura_descuento_global2,
            "descuento_global3" => $forma_descuento_global3,
            "aplica_solo_factura_descuento_global3" => $forma_aplica_solo_factura_descuento_global3,
            "descuento_financiero_fijo" => $forma_descuento_financiero_fijo,
            "aplica_solo_factura_descuento_fijo" => $forma_aplica_solo_factura_descuento_fijo,
            "descuento_financiero_pronto_pago" => $forma_descuento_financiero_pronto_pago,
            "aplica_solo_factura_descuento_pronto_pago" => $forma_aplica_solo_factura_descuento_pronto_pago,
            "numero_dias_pronto_pago" => $forma_numero_dias_pronto_pago,
            "iva_incluido"         => $forma_iva_incluido,
            "numero_dias_pago"     => $forma_numero_dias_pago,
            "numero_entregas"      => $forma_numero_entregas,
            "fecha_final_entregas" => $forma_fecha_final_entregas,
            "observaciones"        => $forma_observaciones,
            "fecha_modificacion"   => "0000-00-00"
        );
        $modificar = SQL::modificar("propuesta_pedidos",$datos,"id='$forma_id_propuesta_pedido'");

        // Error de insercion
        if (!$modificar) {
            $error   = true;
            $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
        } else {

            $consulta_modificar = SQL::seleccionar(array("movimiento_propuesta_pedidos"),array("*"),"id_propuesta_pedido='$forma_id_propuesta_pedido'");
            if (SQL::filasDevueltas($consulta_modificar)){
                while($datos_modificar = SQL::filaEnObjeto($consulta_modificar)){
                    $valor_unitario = $datos_modificar->valor_unitario;
                    if ($forma_iva_incluido == "1"){
                        $factor_impuesto = str_pad(str_replace(".","",$datos_modificar->porcentaje_impuesto),6,"0");
                        $factor = "1.".($factor_impuesto);
                        $iva = $valor_unitario * $datos_modificar->cantidad;
                        $valor_unitario = $valor_unitario / $factor;
                        $subtotal = $valor_unitario * $datos_modificar->cantidad;

                        $descuento_linea = ($subtotal * $datos_modificar->descuento_linea) / 100;
                        $descuento_global1 = ($subtotal - $descuento_linea) * $forma_descuento_global1 / 100;
                        $descuento_global2 = ($subtotal - $descuento_linea - $descuento_global1) * $forma_descuento_global2 / 100;
                        $descuento_global3 = ($subtotal - $descuento_linea - $descuento_global1 - $descuento_global2) * $forma_descuento_global3 / 100;

                        $descuento_global1_iva = ($iva - $subtotal) * $forma_descuento_global1 / 100;
                        $descuento_global2_iva = ($iva - $subtotal - $descuento_global1_iva) * $forma_descuento_global2 / 100;
                        $descuento_global3_iva = ($iva - $subtotal - $descuento_global1_iva - $descuento_global2_iva) * $forma_descuento_global3 / 100;
                        $descuento_linea_iva = ($iva - $subtotal - $descuento_global1_iva - $descuento_global2_iva - $descuento_global3_iva) * $datos_modificar->descuento_linea / 100;

                        if ($descuento_linea > 0 || $descuento_global1 > 0 || $descuento_global2 > 0 || $descuento_global3 > 0){
                            $iva = ($subtotal - $descuento_linea - $descuento_global1 - $descuento_global2 - $descuento_global3) * $datos_modificar->porcentaje_impuesto / 100;
                        } else {
                            $iva = $iva - $subtotal - $descuento_linea_iva - $descuento_global1_iva - $descuento_global2_iva - $descuento_global3_iva;
                        }
                        //$valor_unitario = $datos_modificar->valor_unitario;
                    } else {
                        $subtotal = $valor_unitario * $datos_modificar->cantidad;
                        $descuento_linea = ($subtotal * $datos_modificar->descuento_linea) / 100;
                        $descuento_global1 = ($subtotal - $descuento_linea) * $forma_descuento_global1 / 100;
                        $descuento_global2 = ($subtotal - $descuento_linea - $descuento_global1) * $forma_descuento_global2 / 100;
                        $descuento_global3 = ($subtotal - $descuento_linea - $descuento_global1 - $descuento_global2) * $forma_descuento_global3 / 100;
                        $iva = ($subtotal - $descuento_linea - $descuento_global1 - $descuento_global2 - $descuento_global3) * $datos_modificar->porcentaje_impuesto / 100;
                    }

                    $total = round($subtotal - $descuento_linea - $descuento_global1 - $descuento_global2 - $descuento_global3 + $iva);
                    $datos_totales = array(
                        "valor_unitario"       => $valor_unitario,
                        "valor_total"          => $subtotal,
                        "neto_pagar"           => $total,
                        "valor_iva"            => $iva,
                        "descuento_global1"       => $forma_descuento_global1,
                        "valor_descuento_global1" => $descuento_global1,
                        "descuento_global2"       => $forma_descuento_global2,
                        "valor_descuento_global2" => $descuento_global2,
                        "descuento_global3"       => $forma_descuento_global3,
                        "valor_descuento_global3" => $descuento_global3,
                        "valor_descuento_linea" => $descuento_linea,
                        "iva_incluido"         => $forma_iva_incluido,
                        "estado"               => "0"
                    );
                    $modificar_movimiento = SQL::modificar("movimiento_propuesta_pedidos",$datos_totales,"id='$datos_modificar->id'");
                }
            }

            include("clases/pedidos.php");
            $ruta_archivo = pedido($forma_id_propuesta_pedido,$textos,$sem,$imagenesGlobales,$rutasGlobales, $sesion_proveedores_numero_decimales_cantidad, $sesion_proveedores_numero_decimales_valores, "adicionar", $sesion_id_usuario_ingreso);
            $conexion = ssh2_connect($datosGlobales["servidorRemoto"], 22);
            ssh2_auth_password($conexion, $datosGlobales["usuarioRemoto"], $datosGlobales["claveUsuarioRemoto"]);
            //ssh2_scp_send($conexion, $nombreArchivoPlano, $nombreArchivoPlano, 0777);
            ssh2_exec($conexion, '/bin/sh /home/sfierp/bin/actpp');
        }
    }*/
    // Enviar datos con la respuesta del proceso al script que origino la peticion
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

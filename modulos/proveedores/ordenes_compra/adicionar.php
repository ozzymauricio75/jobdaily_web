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
        echo SQL::datosAutoCompletar("menu_proveedores", $url_q);
    }
    if (($url_item) == "selector5") {
        echo SQL::datosAutoCompletar("seleccion_referencias_proveedor", $url_q);
    }
    if (($url_item) == "selector6") {
        echo SQL::datosAutoCompletar("seleccion_proveedores", $url_q);
    }
    if (($url_item) == "selector7") {
        echo SQL::datosAutoCompletar("seleccion_referencias_proveedor", $url_q);
    }
    if (($url_item) == "selector8") {
        echo SQL::datosAutoCompletar("seleccion_referencias_proveedor", $url_q);
    }
    exit;

} elseif (isset($url_cargarProveedor)) {
    if (!empty($url_nit_proveedor)) {
      
        $tipo_persona = SQL::obtenerValor("terceros", "tipo_persona", "documento_identidad = '$url_nit_proveedor' AND activo = '1'");
           
        if ($tipo_persona == '2' || $tipo_persona == '4') {

            //Genera digito de verificacion en nit
            $nit     = $url_nit_proveedor;
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

            $nombre_proveedor = SQL::obtenerValor("terceros", "razon_social", "documento_identidad = '$url_nit_proveedor' AND activo = '1'");
        
        } else{
            $primer_nombre    = SQL::obtenerValor("terceros", "primer_nombre", "documento_identidad ='$url_nit_proveedor' AND activo ='1'");
            $segundo_nombre   = SQL::obtenerValor("terceros", "segundo_nombre", "documento_identidad ='$url_nit_proveedor' AND activo ='1'");
            $primer_apellido  = SQL::obtenerValor("terceros", "primer_apellido", "documento_identidad ='$url_nit_proveedor' AND activo ='1'");
            $segundo_apellido = SQL::obtenerValor("terceros", "segundo_apellido", "documento_identidad ='$url_nit_proveedor' AND activo='1'");
            $nombre_proveedor = $primer_nombre." ".$segundo_nombre." ".$primer_apellido." ".$segundo_apellido;
        }

        $codigo_vendedor       = SQL::obtenerValor("vendedores_proveedor", "codigo", "documento_proveedor ='$url_nit_proveedor' AND activo='1'");

        $nombre_vendedor       = SQL::obtenerValor("menu_vendedores_proveedor", "NOMBRE_COMPLETO", "DOCUMENTO ='$url_nit_proveedor'");

        $direccion             = SQL::obtenerValor("terceros", "direccion_principal", "documento_identidad ='$url_nit_proveedor' AND activo='1'");

        $correo_electronico    = SQL::obtenerValor("vendedores_proveedor", "correo", "documento_proveedor ='$url_nit_proveedor' AND activo='1'");
        $celular               = SQL::obtenerValor("vendedores_proveedor", "celular", "documento_proveedor ='$url_nit_proveedor' AND activo='1'");
        $codigo_dane_municipio = SQL::obtenerValor("terceros", "codigo_dane_municipio_localidad", "documento_identidad ='$url_nit_proveedor' AND activo='1'");
        $municipios            = SQL::obtenerValor("municipios","nombre","codigo_dane_municipio = '$codigo_dane_municipio' LIMIT 0,1");

        $tabla = array();
        $tabla = array(
          $nombre_proveedor,
          $digitoV,
          $vendedor_proveedor,
          $nombre_vendedor,
          $direccion,
          $correo_electronico,
          $celular,
          $municipios      
        );    

        HTTP::enviarJSON($tabla);
        exit();
    }

} elseif (!empty($url_recargar)) {

    if ($url_elemento == "empresa") {
       $respuesta = HTML::generarDatosLista("empresas", "codigo", "nombre", "codigo = '$url_origen'");
    }

    if ($url_elemento == "sucursal") {
       $respuesta = HTML::generarDatosLista("sucursales", "codigo", "nombre", "codigo_empresa = '".$url_codigo."' AND codigo !='0' AND tipo != '0'");
    }
    HTTP::enviarJSON($respuesta);

} elseif (isset($url_cargarDatosArticuloCreado)) {

    if (!empty($url_referencia_carga)) {

        $codigo_articulo               = SQL::obtenerValor("referencias_proveedor", "codigo_articulo", "referencia = '$url_referencia_carga' AND principal = '1'");

        $estructura_grupos             = SQL::obtenerValor("articulos", "codigo_estructura_grupo", "codigo = '$codigo_articulo'");
        $nodo_estructura_grupos        = SQL::obtenerValor("estructura_grupos", "codigo_padre", "codigo = '$estructura_grupos'");
        $grupo_estructura_grupos       = SQL::obtenerValor("estructura_grupos", "codigo_grupo", "codigo = '$estructura_grupos'");

        $codigo_barras                 = SQL::obtenerValor("referencias_proveedor", "codigo_barras", "referencia = '$url_referencia_carga' AND principal = '1'");
        $documento_identidad_proveedor = SQL::obtenerValor("referencias_proveedor", "documento_identidad_proveedor", "referencia = '$url_referencia_carga' AND principal = '1'");     

        $documento_identidad_proveedor = SQL::obtenerValor("seleccion_proveedores", "nombre", "id = '$documento_identidad_proveedor'");
        
        // Realiza consulta para mandar datos a java script y cargar si codigo existe - faltan algunos no esenciales
        $consulta                = SQL::seleccionar(array("articulos"), array("*"), "codigo = '$codigo_articulo'", "", "codigo", 1);
        $codigo_impuesto_compra  = SQL::obtenerValor("articulos", "codigo_impuesto_compra", "codigo = '$codigo_articulo'");
        $nombre_impuesto_compra  = SQL::obtenerValor("tasas", "descripcion", "codigo = '$codigo_impuesto_compra'");
        $codigo_impuesto_venta   = SQL::obtenerValor("articulos", "codigo_impuesto_venta", "codigo = '$codigo_articulo'");
        $nombre_impuesto_venta   = SQL::obtenerValor("tasas", "descripcion", "codigo = '$codigo_impuesto_venta'");
        $codigo_unidad_compra    = SQL::obtenerValor("articulos", "codigo_unidad_compra", "codigo = '$codigo_articulo'");
        $nombre_unidad_compra    = SQL::obtenerValor("tipos_unidades", "nombre", "codigo = '$codigo_unidad_compra'");
        $codigo_estructura_grupo = SQL::obtenerValor("articulos", "codigo_estructura_grupo", "codigo = '$codigo_articulo'");
        $codigo_padre            = SQL::obtenerValor("estructura_grupos", "codigo_padre", "codigo = '$codigo_estructura_grupo'");
        $codigo_grupo            = SQL::obtenerValor("estructura_grupos", "codigo_grupo", "codigo = '$codigo_estructura_grupo'");

        $tabla = array();

        if (SQL::filasDevueltas($consulta)) {

            $datos = SQL::filaEnObjeto($consulta);
            $codigo_marca = SQL::obtenerValor("marcas", "codigo", "codigo = '$datos->codigo'");
            $nombre_marca = SQL::obtenerValor("marcas", "descripcion", "codigo = '$datos->codigo'");

            $tabla = array(
                $datos->codigo,
                $datos->descripcion,
                $datos->tipo_articulo,
                $datos->ficha_tecnica,
                $datos->alto,
                $datos->ancho,
                $datos->profundidad,
                $datos->peso,
                $datos->codigo_impuesto_compra,
                $datos->codigo_impuesto_venta,
                $datos->codigo_marca,
                $datos->codigo_estructura_grupo,
                $datos->manejo_inventario,
                $datos->codigo_unidad_venta,
                $datos->codigo_unidad_compra,
                $datos->codigo_unidad_presentacion,
                $datos->codigo_iso,
                $datos->activo,
                $datos->imprime_listas,
                $datos->fecha_creacion,
                $documento_identidad_proveedor,
                $codigo_barras,
                $codigo_marca,
                $nombre_marca,
                $nombre_tipo_articulo,
                $nombre_impuesto_compra,
                $nombre_impuesto_venta,
                $nombre_unidad_compra,
                $codigo_estructura_grupo,
                $codigo_padre,
                $codigo_grupo
            );
        } else {
            $tabla[] = "";
        }
        HTTP::enviarJSON($tabla);
    }
    exit;
}

if (!empty($url_generar)){

    $error  = "";
    $titulo = $componente->nombre;

    if ($sesion_usuario_maestro_ingreso){
        $sucursales  = SQL::obtenerValor("sucursales","codigo","codigo > '0' AND activo ='0' LIMIT 0,1");
        $compradores = SQL::obtenerValor("compradores","codigo","codigo > '0' AND activo ='1' LIMIT 0,1");
        $vendedores  = SQL::obtenerValor("vendedores_proveedor","codigo","codigo > '0' AND activo ='1' LIMIT 0,1");
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
            $vendedores  = HTML::generarDatosLista("menu_vendedores_proveedor","id","NOMBRE_COMPLETO","id > '0' AND ACTIVO = Activo'");
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
        $marcas                = HTML::generarDatosLista("marcas", "codigo", "descripcion","codigo > '0'");
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
                //HTML::campoTextoCorto("*nit_proveedor",$textos["NIT_PROVEEDOR"], 15, 15, "", array("title"=>$textos["AYUDA_NIT_PROVEEDOR"],"onKeyPress"=>"return campoEntero(event)", "onBlur"=>"cargarProveedor()")),

                HTML::campoTextoCorto("*razon_social_proveedor",$textos["RAZON_SOCIAL_PROVEEDOR"], 45, 255, "", array("title"=>$textos["AYUDA_RAZON_SOCIAL_PROVEEDOR"],"class"=>"autocompletable", "onFocus" => "acProveedor(this)"))
            )
        );
        $funciones["PESTANA_DATOS_PEDIDO"]   = "cargarProveedor()";
        $formularios["PESTANA_DATOS_PEDIDO"] = array(
            array(
                HTML::agrupador(
                    array(
                        array(
                            HTML::campoTextoCorto("fecha_documento",$textos["FECHA_DOCUMENTO"], 8, 8, date("Y-m-d"), array("title"=>$textos["AYUDA_FECHA"],"", "readOnly"=>"true"))
                                .HTML::campoOculto("minDate", date("Y-m-d")),
                                //array("id"=>"fecha_pedido","class"=>"fecha_pedido"),

                            HTML::campoTextoCorto("fecha_entrega", $textos["FECHA_ENTREGA"], 10, 10, date("Y-m-d"), array("class"=>"selectorFechaBloquear"), array("title" => $textos["AYUDA_FECHA_ENTREGA"], "onBlur" => "validarItem(this);")),

                            HTML::mostrarDato("numero_orden",$textos["NUMERO_ORDEN_COMPRA"], "", "", array("class"=>"oculto"))
                        ),
                        array(    
                            HTML::listaSeleccionSimple("*codigo_comprador",$textos["COMPRADOR"], $compradores, "", array("title",$textos["AYUDA_COMPRADOR"])),

                             HTML::listaSeleccionSimple("*dias_pago", $textos["NUMERO_DIAS_PAGO"], HTML::generarDatosLista("plazos_pago_proveedores", "codigo", "descripcion", "codigo!='0'"), "", array("title",$textos["AYUDA_NUMERO_DIAS_PAGO"])),
                            
                             HTML::listaSeleccionSimple("*id_moneda",$textos["MONEDA"], $monedas, "", array("title",$textos["AYUDA_MONEDA"]))
                        ),
                        array(    
                            HTML::listaSeleccionSimple("*empresa", $textos["EMPRESA"], HTML::generarDatosLista("empresas", "codigo", "razon_social","codigo != 0"), "", array("title" => $textos["AYUDA_EMPRESAS"],"onChange" => "recargarLista('codigo_empresa','codigo_sucursal');recargarListaEmpresas();")),
                           
                            HTML::mostrarDato("nit_empresa",$textos["NIT"], "", "", array("class"=>"oculto")),
                        ),
                        array(
                            HTML::listaSeleccionSimple("*sucursal", $textos["CONSORCIO"], HTML::generarDatosLista("sucursales", "codigo", "nombre","codigo != 0 AND tipo != '0'"), "", array("title" => $textos["AYUDA_CONSORCIO"],"onChange" => "recargarListaEmpresas();")),
                            HTML::campoOculto("id_sucursal", "")
                        ),
                        array(
                           HTML::listaSeleccionSimple("*proyecto", $textos["PROYECTO"], HTML::generarDatosLista("proyectos", "codigo", "nombre","codigo != 0"), "", array("title" => $textos["AYUDA_EMPRESAS"], "")),

                           HTML::campoTextoCorto("*solicitante",$textos["SOLICITANTE"], 40, 255, "", array("title",$textos["AYUDA_SOLICITANTE"]))
                            //HTML::mostrarDato("fecha_documento_mostrar",$textos["FECHA_DOCUMENTO"], "", "", array("class"=>"oculto")),
                        ),
                    ),
                    $textos["DATOS_FACTURACION"]
                )
            ),
            array(
                HTML::agrupador(
                    array(
                        array(
                            HTML::campoTextoCorto("*selector4",$textos["NIT_PROVEEDOR"], 15, 15, "", array("title"=>$textos["AYUDA_NIT_PROVEEDOR"],"class" => "autocompletable", "onKeyPress"=>"return campoEntero(event)", "onBlur"=>"cargarProveedor()")),

                            HTML::campoTextoCorto("digito_verificacion", $textos["DIGITO_VERIFICACION"], 1, 1, "", array("readonly" => "true","Class" => "oculto"))
                            .HTML::campoOculto("documento_identidad_proveedor", ""),

                            HTML::campoTextoCorto("*razon_social_proveedor",$textos["RAZON_SOCIAL_PROVEEDOR"], 45, 255, "", array("title"=>$textos["AYUDA_RAZON_SOCIAL_PROVEEDOR"],"")),

                            HTML::listaSeleccionSimple("*vendedor_proveedor", $textos["VENDEDOR"], HTML::generarDatosLista("menu_vendedores_proveedor", "id", "NOMBRE_COMPLETO", "id >'0'"), "", array("title",$textos["AYUDA_VENDEDOR"]))
                        ),
                        array(
                            HTML::campoTextoCorto("*direccion",$textos["DIRECCION"], 40, 255, "", array("title",$textos["AYUDA_DIRECCION"])),
                            
                            HTML::campoTextoCorto("*selector1",$textos["MUNICIPIO"], 40, 255, "", array("title",$textos["AYUDA_MUNICIPIO"], "class"=>"autocompletable"))
                            .HTML::campoOculto("id_municipio","")
                        ),    
                        array(
                            HTML::campoTextoCorto("*correo_electronico",$textos["CORREO_ELECTRONICO"], 40, 255, "", array("title",$textos["AYUDA_CORREO_ELECTRONICO"])),

                            HTML::campoTextoCorto("*celular",$textos["CELULAR"], 15, 15, "", array("title",$textos["AYUDA_CELULAR"]))
                        )
                    ),
                    $textos["DATOS_PROVEEDOR"]
                )
            ),
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

        $funciones["PESTANA_ARTICULOS"]   = "cargarDatosArticulo()";
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
                                HTML::campoTextoCorto("+selector7",$textos["REFERENCIA"], 30, 30, "", array("title"=>$textos["AYUDA_REFERENCIA_PROVEEDOR"],"class"=>"autocompletable articulo_existe modificar","onblur" => "validarItem(this)","onblur" => "cargarDatosArticulo()","onKeyPress" => "return campoEntero(event)"))
                                .HTML::campoOculto("referencia","")
                            ),
                            array(
                                HTML::campoTextoCorto("+descripcion",$textos["DESCRIPCION"], 50, 255, "", array("title"=>$textos["AYUDA_DETALLE"],"class"=>"articulo_existe modificar_articulo")),
                            ),
                            array(
                                HTML::contenedor(
                                    HTML::agrupador(
                                        array(
                                            array(
                                                HTML::contenedor(HTML::arbolGrupos("arbolGrupos", "", "", "codigo_estructura_grupo"))
                                            )
                                        ),
                                        $textos["ESTRUCTURA_GRUPO"]
                                    ),
                                    array("id"=>"estructura_grupo","class"=>"articulo_nuevo modificar_detalle oculto")
                                ),
                            ),
                            /*array(
                                HTML::campoTextoCorto("*selector6", $textos["PROVEEDOR"], 40, 255, "", array("title" => $textos["AYUDA_PROVEEDOR"], "class" => "autocompletable articulo_nuevo oculto"))
                                .HTML::campoOculto("documento_identidad_proveedor", "")
                            ),*/
                            array(
                                HTML::campoTextoCorto("+selector8",$textos["REFERENCIA"], 30, 30, "", array("title"=>$textos["AYUDA_REFERENCIA_PROVEEDOR"],"class"=>"autocompletable oculto modificar articulo_nuevo")),

                                HTML::listaSeleccionSimple("+id_tasa", $textos["TASA"], $tasas, "", array("title" => $textos["AYUDA_TASA"], "class"=>"oculto modificar_detalle articulo_nuevo")),

                                HTML::listaSeleccionSimple("+id_marca", $textos["MARCA"], $marcas, "", array("title" => $textos["AYUDA_MARCA"], "class"=>"oculto modificar_detalle articulo_nuevo")),
                            ),
                            array(
                                HTML::campoTextoCorto("+descripcion",$textos["DESCRIPCION"], 50, 255, "", array("title"=>$textos["AYUDA_DETALLE"],"class"=>"oculto articulo_nuevo modificar_detalle")),
                            ),
                            array(
                                HTML::listaSeleccionSimple("+id_unidad_compra",$textos["UNIDAD_MEDIDA"], $unidades, $id_unidad_medida_pedidos, array("title"=>$textos["AYUDA_UNIDAD"])),

                                HTML::campoTextoCorto("+cantidad_total_articulo",$textos["CANTIDAD_TOTAL"], 5, 15, "", array("title"=>$textos["AYUDA_CANTIDAD_TOTAL"], "onKeyPress"=>"return campoDecimal(event)", "onKeyUp"=>"activaDetalle()", "onBlur"=>"cargarCantidad()", "class"=>"movimiento")),

                                HTML::campoTextoCorto("+valor_unitario",$textos["VALOR_UNITARIO"], 10, 15, "", array("title"=>$textos["AYUDA_VALOR_UNITARIO"], "onKeyPress"=>"return campoDecimal(event)")),
                                HTML::campoOculto("cantidad_total_control",0)
                            ),
                            array(
                                HTML::marcaChequeo("aplica_descuento",$textos["DESCUENTO"], 1, false, array("title"=>$textos["AYUDA_APLICA_DESCUENTO"], "class"=>"descuento_linea modificar","onClick"=>"activaCampos(4,0)")),

                                HTML::campoTextoCorto("descuento",$textos["PORCENTAJE"], 2, 8, "", array("title"=>$textos["AYUDA_APLICA_DESCUENTO"],"onKeyPress"=>"return campoDecimal(event)", "class"=>"linea oculto modificar")),
                            ),
                            array(    
                                HTML::campoTextoCorto("observaciones_articulo",$textos["OBSERVACIONES"], 50, 78, "", array("title"=>$textos["AYUDA_OBSERVACIONES"], "class"=>"movimiento")),
                                
                                HTML::boton("botonAgregarArticulo", $textos["AGREGAR_ARTICULO"], "agregarArticulo();", "adicionar",array("class"=>"agregar_articulo"),"etiqueta")
                            ),
                            array(
                                HTML::selectorArchivo("foto_articulo", $textos["FOTO"], array("title" => $textos["AYUDA_FOTO"], "class"=>" articulo_nuevo oculto"))
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
                    array("id","MODIFICAR","ELIMINAR","REFERENCIA","DESCRIPCION","CANTIDAD","UNIDAD_MEDIDA","VALOR_UNITARIO","SUBTOTAL","FOTO","OBSERVACIONES"),
                    "",
                    array("I","I","I","I","I","I","I","I","I","I"),
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
            ),
            array(
                HTML::campoTextoLargo("observaciones",$textos["OBSERVACIONES"], 2, 95, "", array("title"=>$textos["AYUDA_OBSERVACIONES"]))
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

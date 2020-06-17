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
        echo SQL::datosAutoCompletar("seleccion_proveedores", $url_q);
    }
    if (($url_item) == "selector2") {
        echo SQL::datosAutoCompletar("seleccion_referencias_proveedor", $url_q);
    }

    exit;
}

if (!empty($url_recargarPais) && !empty($url_documento_identidad_proveedor)){

    $codigo_iso   = SQL::obtenerValor("terceros","codigo_iso_localidad","documento_identidad = '$url_documento_identidad_proveedor'");

    $elementos[0] = $codigo_iso;
    /*******************************************************/
    $id_texto = "0-";
    $descripcion_texto = " -";

    $consulta = SQL::seleccionar(array("buscador_proveedores_marcas"),array("id_marca","marca"),"id_proveedor = '$url_documento_identidad_proveedor'");

    if (SQL::filasDevueltas($consulta)) {
        while($datos = SQL::filaEnObjeto($consulta)){
            $id_texto.= $datos->id_marca."-";
            $descripcion_texto.= $datos->marca."-";
        }
    }

    $codigo_marcas_proveedor      = trim($id_texto, "-");
    $descripcion_marcas_proveedor = trim($descripcion_texto, "-");
    /*******************************************************/

    $elementos[1] = $codigo_marcas_proveedor;
    $elementos[2] = $descripcion_marcas_proveedor;
    
    HTTP::enviarJSON($elementos);
    exit;
}

if (!empty($url_verificarReferencia) && !empty($url_documento_identidad) && !empty($url_codigo) && !empty($url_referencia)){

    $consulta = SQL::obtenerValor("referencias_proveedor","referencia","documento_identidad_proveedor='$url_documento_identidad' AND url_referencia='$url_referencia'");

    if ($consulta){
        $continuar = 0;
    } else {
        $continuar = 1;
    }
    HTTP::enviarJson($continuar);
    exit();
}

if (isset($url_recargar)) {

    if (!empty($url_referencia_carga)) {
        
        $referencia = $url_referencia_carga;

        $codigo_articulo               = SQL::obtenerValor("referencias_proveedor", "codigo_articulo", "referencia = '$referencia' AND principal = '1' LIMIT 1");

        $estructura_grupos             = SQL::obtenerValor("articulos", "codigo_estructura_grupo", "codigo = '$codigo_articulo'");
        $nodo_estructura_grupos        = SQL::obtenerValor("estructura_grupos", "codigo_padre", "codigo = '$estructura_grupos'");
        $grupo_estructura_grupos       = SQL::obtenerValor("estructura_grupos", "codigo_grupo", "codigo = '$estructura_grupos'");

        $codigo_barras                 = SQL::obtenerValor("referencias_proveedor", "codigo_barras", "referencia = '$url_referencia_carga' AND principal = '1' LIMIT 1");
        $documento_identidad_proveedor = SQL::obtenerValor("referencias_proveedor", "documento_identidad_proveedor", "referencia = '$url_referencia_carga' AND principal = '1'");     

        $documento_identidad_proveedor = SQL::obtenerValor("seleccion_proveedores", "nombre", "id = '$documento_identidad_proveedor'");

        // Realiza consulta para mandar datos a java script y cargar si codigo existe - faltan algunos no esenciales
        $codigo_impuesto_compra  = SQL::obtenerValor("articulos", "codigo_impuesto_compra", "codigo = '$codigo_articulo'");
        $nombre_impuesto_compra  = SQL::obtenerValor("tasas", "descripcion", "codigo = '$codigo_impuesto_compra'");
        $codigo_impuesto_venta   = SQL::obtenerValor("articulos", "codigo_impuesto_venta", "codigo = '$codigo_articulo'");
        $nombre_impuesto_venta   = SQL::obtenerValor("tasas", "descripcion", "codigo = '$codigo_impuesto_venta'");
        $codigo_unidad_compra    = SQL::obtenerValor("articulos", "codigo_unidad_compra", "codigo = '$codigo_articulo'");
        $nombre_unidad_compra    = SQL::obtenerValor("tipos_unidades", "nombre", "codigo = '$codigo_unidad_compra'");
        $codigo_estructura_grupo = SQL::obtenerValor("articulos", "codigo_estructura_grupo", "codigo = '$codigo_articulo'");
        $codigo_padre            = SQL::obtenerValor("estructura_grupos", "codigo_padre", "codigo = '$codigo_estructura_grupo'");
        $codigo_grupo            = SQL::obtenerValor("estructura_grupos", "codigo_grupo", "codigo = '$codigo_estructura_grupo'");

        $consulta_proveedores    = SQL::seleccionar(array("articulos_proveedor"), array("*"), "codigo_articulo = '$codigo_articulo'");
        $proveedores             = array();

        if (SQL::filasDevueltas($consulta_proveedores)) {
            $datos_proveedor = SQL::filaEnObjeto($consulta_proveedores);
            $proveedores = array(
                $datos_proveedor->documento_identidad_proveedor
            );
        }

        $consulta  = SQL::seleccionar(array("articulos"), array("*"), "codigo = '$codigo_articulo'", "", "codigo", 1);

        $tabla     = array();

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

/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {

    $error  = "";
    $titulo = $componente->nombre;
    
    $consulta_unidades = SQL::seleccionar(array("unidades"),array("*"),"codigo>0");
    if (SQL::filasDevueltas($consulta_unidades)){

        while($datos = SQL::filaEnObjeto($consulta_unidades)){
            $unidades[$datos->codigo] = $datos->nombre;
        }
    } else {
        $mensajes_error[] = 1;
    }
    
    $consulta_proveedores = SQL::seleccionar(array("proveedores"),array("*"),"documento_identidad !=''");
    if (!SQL::filasDevueltas($consulta_proveedores)){
        $mensajes_error[] = 2;
    }

    $consulta_paises = SQL::seleccionar(array("paises"),array("*"),"codigo_iso !=''");
    if (!SQL::filasDevueltas($consulta_paises)){
        $mensajes_error[] = 3;
    }
    
    $consulta_estructura = SQL::seleccionar(array("estructura_grupos"),array("*"),"codigo > 0");
    if (!SQL::filasDevueltas($consulta_estructura)){
        $mensajes_error[] = 4;
    }

    $consulta_tasas = SQL::seleccionar(array("tasas"),array("*"),"codigo > 0");
    if (!SQL::filasDevueltas($consulta_tasas)){
        $mensajes_error[] = 5;
    }

    if (!isset($mensajes_error)){

        $tipo_articulo= array(
            "1" => $textos["MATERIA_PRIMA"],
            "2" => $textos["PRODUCTO_TERMINADO"]
        );

        $manejo_inventario = array(
            "1" => $textos["INVENTARIO_VALORIZADO"],
            "2" => $textos["INVENTARIO_SOLO_KARDEX"]
        );

        $activo= array(
            "0" => $textos["INACTIVO"],
            "1" => $textos["ACTIVO"]
        );

        $preferencias = array();
        
        $preferencias["unidad_venta"]             = 0;    
        $preferencias["tipo_articulo"]            = 0;
        $preferencias["unidad_compra"]            = 0;
        $preferencias["unidad_presentacion"]      = 0;
        $preferencias_globales                    = array();
        $preferencias_globales["impuesto_compra"] = 0;    
        $preferencias_globales["impuesto_venta"]  = 0;
        
        //Asignar codigo siguiente de la tabla 
        $codigo = SQL::obtenerValor("articulos","MAX(codigo)","codigo>0");

        if ($codigo){
            intval($codigo++);
        } else {
            intval($codigo = 1);
        }
            
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::campoTextoCorto("*codigo", $textos["CODIGO"], 8, 8, $codigo, array("readonly" => "true"), array("title" => $textos["AYUDA_CODIGO"], "onblur" => "validarItem(this);"))
            ),
            array(
                HTML::campoTextoCorto("selector2", $textos["REFERENCIA_PROVEEDOR"], 30, 30, "", array("title" => $textos["AYUDA_REFERENCIA_PROVEEDOR"],"class" => "autocompletable", "onblur" => "validarItem(this)", "onchange" => "cargarDatosArticulo()"))
                .HTML::campoOculto("codigo_alfanumerico", ""),

                HTML::campoTextoCorto("codigo_barras", $textos["CODIGO_BARRAS"], 13, 13, "",array("title" => $textos["AYUDA_CODIGO_BARRAS"],"onKeyPress" => "return campoEntero(event)"))
            ),
            array(
                HTML::campoTextoCorto("*descripcion", $textos["DESCRIPCION"], 51, 255, "", array("title" => $textos["AYUDA_DESCRIPCION"],"onBlur" => "validarItem(this);"))
            ),
            array(
                HTML::campoTextoCorto("*selector1", $textos["PROVEEDOR"], 40, 255, "", array("title" => $textos["AYUDA_PROVEEDOR"], "class" => "autocompletable"))
                .HTML::campoOculto("documento_identidad_proveedor", "")
            ),
            array(
                HTML::listaSeleccionSimple("*tipo_articulo", $textos["TIPO_ARTICULO"], $tipo_articulo, $preferencias["tipo_articulo"], array("title" => $textos["AYUDA_TIPO_ARTICULO"]))
            ),
            array(
                HTML::campoTextoCorto("alto", $textos["ALTO"], 4, 4, "", array("title" => $textos["AYUDA_ALTO"] , "onBlur" => "validarItem(this);","onKeyPress" => "return campoEntero(event)")),

                HTML::campoTextoCorto("ancho", $textos["ANCHO"], 4, 4, "", array("title" => $textos["AYUDA_ANCHO"], "onBlur" => "validarItem(this);","onKeyPress" => "return campoEntero(event)")),

                HTML::campoTextoCorto("profundidad", $textos["PROFUNDIDAD"], 4, 4, "", array("title" => $textos["AYUDA_PROFUNDIDAD"], "onBlur" => "validarItem(this);","onKeyPress" => "return campoEntero(event)")),

                HTML::campoTextoCorto("peso", $textos["PESO"], 8, 8, "", array("title" => $textos["AYUDA_PESO"], "onBlur" => "validarItem(this);","onKeyPress" => "return campoEntero(event)"))
            ),
            array(
                HTML::selectorArchivo("imagen", $textos["FOTO"], array("title" => $textos["AYUDA_FOTO"]))
            ),
            array(
                HTML::campoTextoLargo("ficha_tecnica", $textos["FICHA_TECNICA"],5, 70, "", array("title" => $textos["AYUDA_FICHA_TECNICA"]))
            )
        );
 
        /*** Definición de pestaña datos operativos de articulo ***/
        $formularios["PESTANA_DATOS"] = array(
            array(
                HTML::listaSeleccionSimple("*codigo_impuesto_compra", $textos["IMPUESTO_COMPRA"], HTML::generarDatosLista("tasas", "codigo", "descripcion", "codigo!='0'"), $preferencias_globales["impuesto_compra"], array("title" => $textos["AYUDA_IMPUESTO_COMPRA"])),
                HTML::listaSeleccionSimple("*codigo_impuesto_venta", $textos["IMPUESTO_VENTA"], HTML::generarDatosLista("tasas", "codigo", "descripcion", "codigo!='0'"), $preferencias_globales["impuesto_venta"], array("title" => $textos["AYUDA_IMPUESTO_VENTA"]))
            ),
            array(
                HTML::listaSeleccionSimple("*codigo_marca", $textos["MARCA"], HTML::generarDatosLista("marcas", "codigo", "descripcion", "codigo!='0'"), "",array("title" => $textos["AYUDA_MARCA"]))
            ),
            array(
                HTML::listaSeleccionSimple("*codigo_unidad_compra", $textos["UNIDAD_COMPRA"], HTML::generarDatosLista("unidades", "codigo", "nombre", "codigo!='0'"), $preferencias["unidad_compra"], array("title" => $textos["AYUDA_UNIDAD_COMPRA"]))
            ),
            array(
                HTML::listaSeleccionSimple("*codigo_iso", $textos["PAIS"], HTML::generarDatosLista("paises", "codigo_iso", "nombre", "codigo_iso!=''"),"", array("title" => $textos["AYUDA_PAIS"]))
            ),
            array(
                HTML::campoOculto("digite_codigo_alfanumerico",$textos["CODIGO_ALFANUMERICO_VACIO"]),
            )
        );

        /*** Definición de botones ***/
        $botones = array(
          HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar")
        );
  
        $contenido = HTML::generarPestanas($formularios, $botones);
    } else {
        
        $error = $textos["SIN_REGISTROS_TABLAS"];
        foreach($mensajes_error AS $valor){
            if ($valor == 1){
                $error .= $textos["CREAR_UNIDAD"];
            }
            if ($valor == 2){
                $error .= $textos["CREAR_PROVEEDOR"];
            }
            if ($valor == 3){
                $error .= $textos["CREAR_PAIS"];
            }
            if ($valor == 4){
                $error .= $textos["CREAR_ESTRUCTURA"];
            }
            if ($valor == 5){
                $error .= $textos["CREAR_TASA"];
            }
        }
        $error      .= $textos["CREAR_REGISTROS"];
        $titulo     = "";
        $contenido  = "";
    }
    /*** Enviar datos para la generación del formulario al script que originó la petición ***/
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);
    exit();

/*** Validar los datos provenientes del formulario ***/
} elseif (!empty($url_validar)) {

    /*** Validar codigo ***/
    if ($url_item == "codigo_interno") {
        $existe = SQL::existeItem("articulos", "codigo_interno", $url_valor,"codigo_interno !=''");
        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_CODIGO"]);
        } 
    }

    /*** Validar altura ***/
    if ($url_item == "alto") {
        if ($url_valor < 0 || ctype_alpha($url_valor ) || ($url_valor <> NULL &&!ctype_digit($url_valor))) {
            HTTP::enviarJSON($textos["ERROR_DATO_ALTO"]);            
        }
    }
    
    /*** Validar ancho ***/
    if ($url_item == "ancho") {
        if ($url_valor < 0 || ctype_alpha($url_valor ) || ($url_valor <> NULL &&!ctype_digit($url_valor))) {
            HTTP::enviarJSON($textos["ERROR_DATO_ALTO"]);            
        }
    }
    
    /*** Validar profundidad ***/
    if ($url_item == "profundidad") {

        if ($url_valor < 0 || ctype_alpha($url_valor ) || ($url_valor <> NULL &&!ctype_digit($url_valor))) {
            HTTP::enviarJSON($textos["ERROR_DATO_ALTO"]);            
        }
    }
    
    /*** Validar peso ***/
    if ($url_item == "peso") {

        if ($url_valor < 0 || ctype_alpha($url_valor ) || ($url_valor <> NULL &&!ctype_digit($url_valor))) {
            HTTP::enviarJSON($textos["ERROR_DATO_ALTO"]);            
        }
    }
    
    exit();
}
    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];

    $forma_codigo        = $forma_selector2;
    $codigo_articulo     = SQL::obtenerValor("referencias_proveedor", "codigo_articulo", "referencia = '$forma_codigo' AND principal = '1' LIMIT 1");
    $forma_codigo_barras = SQL::obtenerValor("referencias_proveedor", "codigo_barras", "referencia = '$forma_codigo' AND principal = '1' LIMIT 1");

    /*** Validar campos requeridos ***/
    if(empty($forma_selector2)){
		$error   = true;
		$mensaje = $textos["REFERENCIA_VACIO"]; 

	}elseif(empty($forma_documento_identidad_proveedor)){
      $error   = true;
      $mensaje = $textos["PROVEEDOR_VACIO"];    
           
    }else {
        
        $datos_articulo = array(
            "codigo_articulo"               => $codigo_articulo,
            "documento_identidad_proveedor" => $forma_documento_identidad_proveedor,
            "fecha_modificacion"            => date("Y-m-d H:i:s")
        );

        $insertar_articulo = SQL::insertar("articulos_proveedor", $datos_articulo);

        /*** Error de inserción ***/
        if (!$insertar_articulo) {
            $error   = true;
            $mensaje = $textos["ERROR_EXISTE_PROVEEDOR"];
        } else {

            $datos = array(
                "codigo_articulo"               => $codigo_articulo,
                "referencia"                    => $forma_codigo,
                "documento_identidad_proveedor" => $forma_documento_identidad_proveedor,
                "codigo_barras"                 => $forma_codigo_barras,
                "principal"                     => "1"
            );

            $insertar_referencia = SQL::insertar("referencias_proveedor", $datos);

            /*** Error de insercón ***/
            if (!$insertar_referencia) {
                $error   = true;
                $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
            }
        }
    }
    /*** Enviar datos con la respuesta del proceso al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
?>

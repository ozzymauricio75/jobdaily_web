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
$indicador = 0;
$tabla                      = "usuarios";
$columnas                   = SQL::obtenerColumnas($tabla);
$consulta                   = SQL::seleccionar(array($tabla), $columnas, "usuario = '$sesion_usuario'");
$datos                      = SQL::filaEnObjeto($consulta);
$sesion_id_usuario_ingreso  = $datos->codigo;

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

if (!empty($url_varificarReferencia) && !empty($url_documento_identidad) && !empty($url_codigo) && !empty($url_referencia)){

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
        $codigo_marca            = SQL::obtenerValor("marcas", "codigo", "");
        $nombre_marca            = SQL::obtenerValor("marcas", "descripcion", "codigo !='0'");

        $tabla = array();

        if (SQL::filasDevueltas($consulta)) {

            $datos = SQL::filaEnObjeto($consulta);

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
                .HTML::campoOculto("codigo_maximo", $codigo),
            ),
            array(
                HTML::campoTextoCorto("codigo_alfanumerico", $textos["REFERENCIA_PROVEEDOR"], 30, 30, "", array("title" => $textos["AYUDA_REFERENCIA_PROVEEDOR"], "class" => "autocompletable", "onblur" => "validarItem(this)","onchange" => "cargarDatos()")),

                HTML::campoTextoCorto("codigo_barras", $textos["CODIGO_BARRAS"], 13, 13, "",array("title" => $textos["AYUDA_CODIGO_BARRAS"],"onKeyPress" => "return campoEntero(event)"))
            ),
            array(
                HTML::campoTextoCorto("*descripcion", $textos["DESCRIPCION"], 70, 255, "", array("title" => $textos["AYUDA_DESCRIPCION"],"onBlur" => "validarItem(this);")),
                HTML::campoTextoCorto("costo", $textos["COSTO"], 15, 15, "",array("title" => $textos["AYUDA_COSTO"],"onkeyup"=>"formatoMiles(this)", "onchange"=>"formatoMiles(this)"))
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
        
        /*** Definición pestaña estructura de grupo***/
        $formularios["PESTANA_GRUPO"] = array(
            array(
                HTML::contenedor(HTML::arbolGrupos("arbolGrupos", "", "", "codigo_estructura_grupo"))
            )
        );
 
        /*** Definición de pestaña datos operativos de articulo ***/
        $formularios["PESTANA_DATOS"] = array(
            array(
                HTML::listaSeleccionSimple("*codigo_impuesto_compra", $textos["IMPUESTO_COMPRA"], HTML::generarDatosLista("tasas", "codigo", "descripcion", "codigo!='0'"), $preferencias_globales["impuesto_compra"], array("title" => $textos["AYUDA_IMPUESTO_COMPRA"]))
            ),
            array(
                HTML::listaSeleccionSimple("*codigo_marca", $textos["MARCA"], HTML::generarDatosLista("marcas", "codigo", "descripcion", "codigo!='0'"), "",array("title" => $textos["AYUDA_MARCA"]))
            ),
            array(
                HTML::listaSeleccionSimple("*codigo_unidad_compra", $textos["UNIDAD_COMPRA"], HTML::generarDatosLista("unidades", "codigo", "nombre", "codigo!='0'"), $preferencias["unidad_compra"], array("title" => $textos["AYUDA_UNIDAD_COMPRA"]))
            ),
            array(
                HTML::listaSeleccionSimple("*codigo_iso", $textos["PAIS"], HTML::generarDatosLista("paises", "codigo_iso", "nombre", "codigo_iso!=''"),"", array("title" => $textos["AYUDA_PAIS"]))
            )
        );

        $formularios["PESTANA_REFERENCIA"] = array(
            array(
                HTML::campoTextoCorto("*referencia", $textos["REFERENCIA_ALTERNA"], 30, 30, "",array("title" => $textos["AYUDA_REFERENCIA"])),
                HTML::campoTextoCorto("codigo_barras_alterna", $textos["CODIGO_BARRAS"], 13, 13, "",array("title" => $textos["AYUDA_CODIGO_BARRAS"],"onKeyPress" => "return campoEntero(event)"))
            ),
            array(
                HTML::boton("botonAgregar", $textos["AGREGAR"], "agregarItemArticulo();", "adicionar"),
                HTML::contenedor(HTML::boton("botonRemover", "", "removerItem(this);", "eliminar"), array("id" => "removedor", "style" => "display: none"))
            ),
            array(
                HTML::generarTabla( array("id","","REFERENCIA","CODIGO_BARRAS"),
                                    "",
                                    array("I","I","D"),
                                    "lista_items",
                                    false)
            ),
            array(
                HTML::campoOculto("digite_codigo",$textos["CODIGO_VACIO"]),
                HTML::campoOculto("digite_codigo_alfanumerico",$textos["CODIGO_ALFANUMERICO_VACIO"]),
                HTML::campoOculto("existe_referencia_codigo",$textos["EXISTE_REFERENCIA_CODIGO"]),
                HTML::campoOculto("existe_referencia",$textos["EXISTE_REFERENCIA"]),
                HTML::campoOculto("existe_referencia_principal",$textos["EXISTE_REFERENCIA_PRINCIPAL"]),
                HTML::campoOculto("digite_referencia",$textos["DIGITE_REFRENCIA"]),
                HTML::campoOculto("principal_si",$textos["SI_NO_1"]),
                HTML::campoOculto("principal_no",$textos["SI_NO_0"]),
                HTML::campoOculto("referencia_diferente",$textos["REFERENCIA_DIFERENTE"]),
                HTML::campoOculto("indice","",0)
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

    /*** Validar referencia ***/
    if ($url_item == "codigo_alfanumerico") {
        $existe = SQL::existeItem("referencias_proveedor", "referencia", $url_valor,"principal != '0'");
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
    
    /*** Validar campos requeridos ***/
    if(empty($forma_codigo)){
		$error   = true;
		$mensaje = $textos["CODIGO_VACIO"];
		
	}elseif(empty($forma_descripcion)){
		$error   = true;
		$mensaje = $textos["DESCRIPCION_VACIO"]; 

    }elseif(empty($forma_costo)){
        $error   = true;
        $mensaje = $textos["COSTO_VACIO"]; 

	}elseif(empty($forma_codigo_estructura_grupo)){
		$error   = true;
		$mensaje = $textos["ESTRUCTURA_VACIO"]; 

	}elseif(empty($forma_codigo_unidad_compra)){
      $error = true;

      $mensaje = $textos["UNIDAD_COMPRA_VACIO"];   
	}elseif(empty($forma_documento_identidad_proveedor)){
      $error = true;
      $mensaje = $textos["PROVEEDOR_VACIO"];   
           
    }elseif($existe = SQL::existeItem("referencias_proveedor", "referencia", $forma_codigo_alfanumerico, "principal != '0'")){
	    $error   = true;
        $mensaje = $textos["ERROR_EXISTE_CODIGO"];   
    }else {

        /*** Quitar separador de miles a un numero ***/
        function quitarMiles($cadena){
            $valor = array();
            for ($i = 0; $i < strlen($cadena); $i++) {
                if (substr($cadena, $i, 1) != ".") {
                    $valor[$i] = substr($cadena, $i, 1);
                }
            }
            $valor = implode($valor);
            return $valor;
        }

        $forma_costo = quitarMiles($forma_costo);
        $forma_costo = str_replace(",", ".", $forma_costo);

        $datos = array(
            "codigo"                     => $forma_codigo,
            "descripcion"                => $forma_descripcion,
            "tipo_articulo"              => $forma_tipo_articulo,
            "ficha_tecnica"              => $forma_ficha_tecnica,
            "alto"                       => $forma_alto,
            "ancho"                      => $forma_ancho,
            "profundidad"                => $forma_profundidad,
            "peso"                       => $forma_peso,
            "garantia"                   => $forma_garantia,
            "garantia_partes"            => $forma_garantia_partes,
            "codigo_impuesto_compra"     => $forma_codigo_impuesto_compra,
            "codigo_impuesto_venta"      => $forma_codigo_impuesto_compra,
            "codigo_marca"               => $forma_codigo_marca,
            "codigo_estructura_grupo"    => $forma_codigo_estructura_grupo,
            "manejo_inventario"          => '1',
            "codigo_unidad_venta"        => '1',
            "codigo_unidad_compra"       => $forma_codigo_unidad_compra,
            "codigo_unidad_presentacion" => '1',
            "codigo_iso"                 => $forma_codigo_iso,
            "activo"                     => 1,
            "imprime_listas"             => 1,
            "fecha_creacion"             => date("Y-m-d H:i:s")
        );

        $insertar = SQL::insertar("articulos", $datos);

        $datos_articulo = array(
            "codigo_articulo"               => $forma_codigo,
            "documento_identidad_proveedor" => $forma_documento_identidad_proveedor,
            "fecha_modificacion"            => date("Y-m-d H:i:s")
        );

        $insertar_articulo = SQL::insertar("articulos_proveedor", $datos_articulo);

        $datos_listas = array(
            "codigo_articulo"            => $forma_codigo,
            "fecha"                      => date("Y-m-d H:i:s"),
            "costo"                      => $forma_costo,
            "codigo_usuario_registra"    => $sesion_id_usuario_ingreso,
            "fecha_registra"             => date("Y-m-d H:i:s"),
            "fecha_modificacion"         => ""
        );        

        $insertar_listas = SQL::insertar("lista_precio_articulos", $datos_listas);

        /*** Error de inserción ***/
        if (!$insertar) {
            $error   = true;
            $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
        } else {

            if (!empty($_FILES["imagen"])) {

                $original   = $_FILES["imagen"]["name"];
                $temporal   = $_FILES["imagen"]["tmp_name"];
                $extension  = strtolower(substr($original, (strrpos($original, ".") - strlen($original)) + 1));

                if (strtolower($extension) != "png" && strtolower($extension) != "jpg" && strtolower($extension) != "gif") {
                    $error   = true;
                    $mensaje = $textos["ERROR_FORMATO_IMAGEN"];

                } else {
                    list($ancho, $alto, $tipo) = getimagesize($temporal);

                    $datos   = array(
                        "categoria"   => "2",
                        "id_asociado" => $forma_codigo,
                        "contenido"   => file_get_contents($temporal),
                        "tipo"        => $tipo,
                        "extension"   => $extension,
                        "ancho"       => $ancho,
                        "alto"        => $alto
                    );

                    $insertar = SQL::insertarArchivo("imagenes", $datos);
                }
            }

            $datos = array(
                "codigo_articulo"               => $forma_codigo,
                "referencia"                    => $forma_codigo_alfanumerico,
                "documento_identidad_proveedor" => $forma_documento_identidad_proveedor,
                "codigo_barras"                 => $forma_codigo_barras,
                "principal"                     => "1"
            );

            $insertar_referencia = SQL::insertar("referencias_proveedor", $datos);

            if (isset($forma_referencia_tabla)){

                foreach($forma_referencia_tabla as $i => $valor){

                    if (empty($forma_principal_tabla[$i])){
                        $forma_principal_tabla[$i] = 0;
                    }
                    $datos = array(
                        "codigo_articulo"               => $forma_codigo,
                        "referencia"                    => $forma_referencia_tabla[$i],
                        "documento_identidad_proveedor" => $forma_documento_identidad_proveedor,
                        "codigo_barras"                 => $forma_codigo_barras_tabla[$i],
                        "principal"                     => 0
                    );
                    $insertar_referencia = SQL::insertar("referencias_proveedor", $datos);
                    
                }
            }

            /*** Error de insercón ***/
            if (!$insertar_referencia) {
                $error   = true;
                $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
                $eliminar = SQL::eliminar("articulos", "codigo='$forma_codigo'");

            }
        }
    }
    /*** Enviar datos con la respuesta del proceso al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
?>

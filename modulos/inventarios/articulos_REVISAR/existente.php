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
    echo SQL::datosAutoCompletar("seleccion_proveedores", $url_q);
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
        
        $preferencias["unidad_venta"] = 0;    
        $preferencias["tipo_articulo"] = 0;
        $preferencias["unidad_compra"] = 0;
        $preferencias["unidad_presentacion"] = 0;
        $preferencias_globales = array();
        $preferencias_globales["impuesto_compra"] = 0;    
        $preferencias_globales["impuesto_venta"] = 0;
        
        $consulta = SQL::obtenerValor("articulos","MAX(codigo)","codigo>0");
        if ($consulta){
            $codigo++;
        } else {
            $codigo=1;
        }

        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::campoTextoCorto("*codigo", $textos["CODIGO"], 8, 8, $codigo, array("title" => $textos["AYUDA_CODIGO"])),
                HTML::listaSeleccionSimple("*tipo_articulo", $textos["TIPO_ARTICULO"], $tipo_articulo, $preferencias["tipo_articulo"], array("title" => $textos["AYUDA_TIPO_ARTICULO"]))
            ),
            array(
                HTML::campoTextoCorto("*selector1", $textos["PROVEEDOR"], 40, 255, "", array("title" => $textos["AYUDA_PROVEEDOR"], "class" => "autocompletable"))
                .HTML::campoOculto("documento_identidad_proveedor", "")
            ),
            array(
                HTML::campoTextoCorto("codigo_alfanumerico", $textos["REFERENCIA_PROVEEDOR"], 20, 20, "", array("title" => $textos["AYUDA_REFERENCIA_PROVEEDOR"])),
                HTML::campoTextoCorto("codigo_barras", $textos["CODIGO_BARRAS"], 13, 13, "",array("title" => $textos["AYUDA_CODIGO_BARRAS"],"onKeyPress" => "return campoEntero(event)"))
            ),
            array(
                HTML::campoTextoCorto("*descripcion", $textos["DESCRIPCION"], 55, 255, "", array("title" => $textos["AYUDA_DESCRIPCION"],"onBlur" => "validarItem(this);"))
            ),
            array(
                HTML::campoTextoCorto("alto", $textos["ALTO"], 4, 4, "", array("title" => $textos["AYUDA_ALTO"] , "onBlur" => "validarItem(this);","onKeyPress" => "return campoEntero(event)")),
                HTML::campoTextoCorto("ancho", $textos["ANCHO"], 4, 4, "", array("title" => $textos["AYUDA_ANCHO"], "onBlur" => "validarItem(this);","onKeyPress" => "return campoEntero(event)")),
                HTML::campoTextoCorto("profundidad", $textos["PROFUNDIDAD"], 4, 4, "", array("title" => $textos["AYUDA_PROFUNDIDAD"], "onBlur" => "validarItem(this);","onKeyPress" => "return campoEntero(event)")),
                HTML::campoTextoCorto("peso", $textos["PESO"], 8, 8, "", array("title" => $textos["AYUDA_PESO"], "onBlur" => "validarItem(this);","onKeyPress" => "return campoEntero(event)"))
            ),
            array(
                HTML::marcaSeleccion("formato_imprime",$textos["SIEMPRE_IMPRIME"],1,true,array("id"=>"siempre_imprime")),
            //    HTML::marcaSeleccion("formato_imprime",$textos["OCASIONALMENTE_IMPRIME"],2,false,array("id"=>"ocasionalmente_imprime")),
            //    HTML::marcaSeleccion("formato_imprime",$textos["NUNCA_IMPRIME"],3,false,array("id"=>"nunca_imprime"))
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
                HTML::contenedor(HTML::arbolGrupos("arbolGrupos","", "","codigo_estructura_grupo"))
            )
        );
     
        /*** Definición de pestaña datos operativos de articulo ***/
        $formularios["PESTANA_DATOS"] = array(
            /*array(
                HTML::campoTextoCorto("garantia", $textos["GARANTIA"], 20, 255, "", array("title" => $textos["AYUDA_GARANTIA"],"onBlur" => "validarItem(this);")),
                HTML::campoTextoCorto("garantia_partes", $textos["GARANTIA_PARTES"], 20, 255, "", array("title" => $textos["AYUDA_GARANTIA_PARTES"],"onBlur" => "validarItem(this);"))
            ),*/
            array(
                HTML::listaSeleccionSimple("*codigo_impuesto_compra", $textos["IMPUESTO_COMPRA"], HTML::generarDatosLista("tasas", "codigo", "descripcion", "codigo!='0'"), $preferencias_globales["impuesto_compra"], array("title" => $textos["AYUDA_IMPUESTO_COMPRA"])),
                HTML::listaSeleccionSimple("*codigo_impuesto_venta", $textos["IMPUESTO_VENTA"], HTML::generarDatosLista("tasas", "codigo", "descripcion", "codigo!='0'"), $preferencias_globales["impuesto_venta"], array("title" => $textos["AYUDA_IMPUESTO_VENTA"]))
            ),
            array(
                HTML::listaSeleccionSimple("*codigo_marca", $textos["MARCA"], HTML::generarDatosLista("marcas", "codigo", "descripcion", "codigo!='0'"), "",array("title" => $textos["AYUDA_MARCA"]))
            ),
            /*array(
                HTML::listaSeleccionSimple("*manejo_inventario", $textos["MANEJO_INVENTARIO"], $manejo_inventario,"", array("title" => $textos["AYUDA_MANEJO_INVENTARIO"]))
            ),*/
            array(
                /*HTML::listaSeleccionSimple("*codigo_unidad_venta", $textos["UNIDAD_VENTA"], HTML::generarDatosLista("unidades", "codigo", "nombre", "codigo!='0'"), $preferencias["unidad_venta"], array("title" => $textos["AYUDA_UNIDAD_VENTA"])),*/
                HTML::listaSeleccionSimple("*codigo_unidad_compra", $textos["UNIDAD_COMPRA"], HTML::generarDatosLista("unidades", "codigo", "nombre", "codigo!='0'"), $preferencias["unidad_compra"], array("title" => $textos["AYUDA_UNIDAD_COMPRA"])),
                /*HTML::listaSeleccionSimple("*codigo_unidad_presentacion", $textos["UNIDAD_PRESENTACION"], HTML::generarDatosLista("unidades", "codigo", "nombre", "codigo!='0'"), $preferencias["unidad_presentacion"], array("title" => $textos["AYUDA_UNIDAD_PRESENTACION"]))*/
            ),
            array(
                HTML::listaSeleccionSimple("*codigo_iso", $textos["PAIS"], HTML::generarDatosLista("paises", "codigo_iso", "nombre", "codigo_iso!=''"),"", array("title" => $textos["AYUDA_PAIS"]))
            )
        );
        $formularios["PESTANA_REFERENCIA"] = array(
            array(
                HTML::campoTextoCorto("*referencia", $textos["REFERENCIA"], 30, 30, "",array("title" => $textos["AYUDA_REFERENCIA"])),
                HTML::campoTextoCorto("codigo_barras_alterna", $textos["CODIGO_BARRAS"], 13, 13, "",array("title" => $textos["AYUDA_CODIGO_BARRAS"],"onKeyPress" => "return campoEntero(event)"))
            ),
            array(
                HTML::boton("botonAgregar", $textos["AGREGAR"], "agregarItem();", "adicionar"),
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
    
    /*** Validar campos requeridos ***/
    if(empty($forma_codigo)){
		$error   = true;
		$mensaje = $textos["CODIGO_VACIO"];
		
	}elseif(empty($forma_descripcion)){
		$error   = true;
		$mensaje = $textos["DESCRIPCION_VACIO"]; 

	}elseif(empty($forma_codigo_estructura_grupo)){
		$error   = true;
		$mensaje = $textos["ESTRUCTURA_VACIO"]; 

	/*}elseif(empty($forma_codigo_unidad_venta)){
      $error = true;
      $mensaje = $textos["UNIDAD_VENTA_VACIO"];   
	*/
	}elseif(empty($forma_codigo_unidad_compra)){
      $error = true;
      $mensaje = $textos["UNIDAD_COMPRA_VACIO"];   
	
	/*}elseif(empty($forma_codigo_unidad_presentacion)){
      $error = true;
      $mensaje = $textos["UNIDAD_PRESENTACION_VACIO"];   
	*/
	}elseif(empty($forma_documento_identidad_proveedor)){
      $error = true;
      $mensaje = $textos["PROVEEDOR_VACIO"];   
           
    }elseif($existe = SQL::existeItem("articulos", "codigo", $forma_codigo)){
	    $error   = true;
        $mensaje = $textos["ERROR_EXISTE_CODIGO"]; 
    
    }elseif($existe = SQL::existeItem("referencias_proveedor", "referencia", $forma_referencia)){
	    $error   = true;
        $mensaje = $textos["ERROR_EXISTE_REFERENCIA"]; 
    
    }else {
        
        if (empty($forma_codigo_alfanumerico)){
            $forma_codigo_alfanumerico = $forma_codigo;            
        }

        $datos = array(
            "codigo"                     => $forma_codigo,
            "codigo_proveedor"           => $forma_documento_identidad_proveedor,
            "codigo_barras"              => $forma_codigo_barras,
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
            "codigo_impuesto_venta"      => $forma_codigo_impuesto_venta,
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

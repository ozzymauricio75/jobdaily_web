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
* de APTITUD PARA UN PROPÓITO DETERMINADO. Consulte los detalles de
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

    /*** Verificar que se haya enviado el ID del elemento a modificar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_MODIFICAR_VACIO"];
        $titulo    = "";
        $contenido = "";
        exit();
    } else {
        $vistaConsulta = "articulos";
        $condicion     = "codigo = '$url_id'";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, $condicion);
        $datos         = SQL::filaEnObjeto($consulta);
        $error         = "";
        $titulo        = $componente->nombre;

        $consulta      = SQL::seleccionar(array("imagenes"), array("id_asociado","ancho","alto"), "id_asociado = '$url_id' AND categoria = '2'");
        $imagen        = SQL::filaEnObjeto($consulta);
        if ($imagen){
            $muestra_imagen = HTML::imagen(HTTP::generarURL("VISUIMAG")."&id=".$imagen->id_asociado, array("width" => $imagen->ancho, "height" => $imagen->alto));
        } else {
            $muestra_imagen = "";
        }


        /***Obtener datos de la tabla de articulos ***/
        $documento_identidad_proveedor = SQL::obtenerValor("referencias_proveedor", "documento_identidad_proveedor", "codigo_articulo = '$url_id' LIMIT 1");
        $nombre_proveedor       = SQL::obtenerValor("seleccion_proveedores", "nombre", "id = '$documento_identidad_proveedor'");
        $nombre_proveedor       = explode("|",$nombre_proveedor);
        $nombre_proveedor       = $nombre_proveedor[0];

        /*******************************************************************/
        $consulta_proveedor_marca = SQL::seleccionar(array("proveedor_marca"),array("codigo_marca","descripcion"),"documento_identidad_proveedor = '$documento_identidad_proveedor'");

        $vector_marcas = array();
        $vector_marcas[0] = "";

        if (SQL::filasDevueltas($consulta_proveedor_marca)) {
                
            while($datos_proveedor_marca = SQL::filaEnObjeto($consulta_proveedor_marca)){
                
                $codigos_marcas = $datos_proveedor_marca->codigo_marca;
                $descripcion_marcas = $datos_proveedor_marca->descripcion;
                $vector_marcas[$codigos_marcas] = $descripcion_marcas;
            }
        }
        /*******************************************************************/

        $imprimir = $datos->imprime_listas;
        switch($imprimir){
            case 1: $imprime1 = true;
                $imprime2 = false;
                $imprime3 = false;
                break;
            case 2: $imprime1 = false;
                $imprime2 = true;
                $imprime3 = false;
                break;
            case 3: $imprime1 = false;
                $imprime2 = false;
                $imprime3 = true;
                break;
        }

        $tipo_articulo = array(
            "1" => $textos["PRODUCTO_TERMINADO"],
            "2" => $textos["OBSEQUIO"],
            "3" => $textos["ACTIVO_FIJO"],
            "4" => $textos["MATERIA_PRIMA"]
        );

        $manejo_inventario = array(
            "1" => $textos["INVENTARIO_VALORIZADO"],
            "2" => $textos["INVENTARIO_SOLO_KARDEX"]
        );

        $activo = array(
            "0" => $textos["INACTIVO"],
            "1" => $textos["ACTIVO"]
        );

        /*** Definición de pestaña general ***/
         $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::campoTextoCorto("*codigo", $textos["CODIGO"], 15, 15, $datos->codigo, array("title" => $textos["AYUDA_CODIGO"],"onBlur" => "validarItem(this);")),
                HTML::listaSeleccionSimple("tipo_articulo", $textos["TIPO_ARTICULO"], $tipo_articulo, $datos->tipo_articulo, array("title" => $textos["AYUDA_TIPO_ARTICULO"]))
            ),
            array(
                HTML::campoTextoCorto("*selector1", $textos["PROVEEDOR"], 40, 255, $nombre_proveedor, array("title" => $textos["AYUDA_PROVEEDOR"], "class" => "autocompletable"))
                .HTML::campoOculto("documento_identidad_proveedor", $documento_identidad_proveedor)
            ),
            array(
                HTML::campoTextoCorto("codigo_proveedor", $textos["REFERENCIA_PROVEEDOR"], 20, 20, $datos->codigo_proveedor, array("title" => $textos["AYUDA_REFERENCIA_PROVEEDOR"])),
                HTML::campoTextoCorto("codigo_barras", $textos["CODIGO_BARRAS"], 13, 13, $datos->codigo_barras,array("title" => $textos["AYUDA_CODIGO_BARRAS"],"onKeyPress" => "return campoEntero(event)"))
            ),
            array(
                HTML::campoTextoCorto("*descripcion", $textos["DESCRIPCION"], 55, 255, htmlentities(stripslashes($datos->descripcion)), array("title" => $textos["AYUDA_DESCRIPCION"]))
            ),
            array(
                HTML::marcaSeleccion("imprime",$textos["SIEMPRE_IMPRIME"],1,$imprime1,array("id"=>"siempre_imprime")),
                HTML::marcaSeleccion("imprime",$textos["OCASIONALMENTE_IMPRIME"],2,$imprime2,array("id"=>"ocasionalmente_imprime")),
                HTML::marcaSeleccion("imprime",$textos["NUNCA_IMPRIME"],3,$imprime3,array("id"=>"nunca_imprime")),
            ),
            array(
                HTML::campoTextoCorto("alto", $textos["ALTO"], 4, 4, $datos->alto, array("title" => $textos["AYUDA_ALTO"],"onKeyPress" => "return campoEntero(event)")),
                HTML::campoTextoCorto("ancho", $textos["ANCHO"], 4, 4, $datos->ancho, array("title" => $textos["AYUDA_ANCHO"],"onKeyPress" => "return campoEntero(event)")),
                HTML::campoTextoCorto("profundidad", $textos["PROFUNDIDAD"], 4, 4, $datos->profundidad, array("title" => $textos["AYUDA_PROFUNDIDAD"],"onKeyPress" => "return campoEntero(event)")),
                HTML::campoTextoCorto("peso", $textos["PESO"], 8, 8, $datos->peso, array("title" => $textos["AYUDA_PESO"],"onKeyPress" => "return campoEntero(event)"))
            ),
            array(
                HTML::campoTextoLargo("ficha_tecnica", $textos["FICHA_TECNICA"], 5, 30, $datos->ficha_tecnica, array("title" => $textos["AYUDA_FICHA_TECNICA"],"onBlur" => "validarItem(this);")),
                HTML::selectorArchivo("imagen", $textos["FOTO"], array("title" => $textos["AYUDA_FOTO"])),
                $muestra_imagen
            )
        );

        /*** Definición pestaña estructura de grupo***/
        $formularios["PESTANA_GRUPO"] = array(
            array(
                HTML::contenedor(HTML::arbolGrupos("arbolGrupos", $datos->codigo_estructura_grupo, $datos->codigo_estructura_grupo,"codigo_estructura_grupo"))
            )
        );

        /*** Definición pestaña de datos operativos***/
        $formularios["PESTANA_DATOS"] = array(
            array(
                HTML::campoTextoCorto("garantia", $textos["GARANTIA"], 20, 255, $datos->garantia, array("title" => $textos["AYUDA_GARANTIA"],"onBlur" => "validarItem(this);")),
                HTML::campoTextoCorto("garantia_partes", $textos["GARANTIA_PARTES"], 20, 255, $datos->garantia_partes, array("title" => $textos["AYUDA_GARANTIA_PARTES"],"onBlur" => "validarItem(this);"))
            ),
            array(
                HTML::listaSeleccionSimple("*codigo_impuesto_compra", $textos["IMPUESTO_COMPRA"], HTML::generarDatosLista("tasas", "codigo", "descripcion"), $datos->codigo_impuesto_compra, array("title" => $textos["AYUDA_IMPUESTO_COMPRA"])),
                HTML::listaSeleccionSimple("*codigo_impuesto_venta", $textos["IMPUESTO_VENTA"], HTML::generarDatosLista("tasas", "codigo", "descripcion"), $datos->codigo_impuesto_venta, array("title" => $textos["AYUDA_IMPUESTO_VENTA"]))
            ),
            array(
                HTML::listaSeleccionSimple("codigo_marca", $textos["MARCA"], $marcas, $datos->codigo_marca), array("title" => $textos["AYUDA_MARCA"]))
            ),
            array(
                HTML::listaSeleccionSimple("*manejo_inventario", $textos["MANEJO_INVENTARIO"], $manejo_inventario, $datos->manejo_inventario, array("title" => $textos["AYUDA_MANEJO_INVENTARIO"]))
            ),
            array(
                HTML::listaSeleccionSimple("*codigo_unidad_venta", $textos["UNIDAD_VENTA"], HTML::generarDatosLista("unidades", "codigo", "nombre"), $datos->codigo_unidad_venta, $condicion), array("title" => $textos["AYUDA_UNIDAD_VENTA"])),
                HTML::listaSeleccionSimple("*codigo_unidad_compra", $textos["UNIDAD_COMPRA"], HTML::generarDatosLista("unidades", "codigo", "nombre"), $datos->codigo_unidad_compra, array("title" => $textos["AYUDA_UNIDAD_COMPRA"])),
                HTML::listaSeleccionSimple("*codigo_unidad_presentacion", $textos["UNIDAD_PRESENTACION"], HTML::generarDatosLista("unidades", "id", "nombre"), $datos->codigo_unidad_presentacion, array("title" => $textos["AYUDA_UNIDAD_PRESENTACION"]))
            ),
            array(
                HTML::listaSeleccionSimple("*codigo_iso", $textos["PAIS"], HTML::generarDatosLista("paises", "codigo_iso", "nombre"), $datos->codigo_iso), array("title" => $textos["AYUDA_PAIS"])),
                HTML::listaSeleccionSimple("*activo", $textos["ESTADO"], $activo, $datos->activo, array("title" => $textos["AYUDA_ESTADO"]))
            ),
        );

        /*** Obtener sucursales relacionadas con las sucursales del banco ***/
        $item_referencia = '';
        $consulta_referencias = SQL::seleccionar(array("referencias_proveedor"), array("*"), "codigo_articulo = '$url_id' AND principal='0'");
        if (SQL::filasDevueltas($consulta_referencias)) {
            $contador = 0;
            while ($datos_referencias = SQL::filaEnObjeto($consulta_referencias)) {

                $referencia    = $datos_sucursal->referencia;
                $codigo_barras = $datos_sucursal->codigo_barras;

                $co1  = HTML::campoOculto("referencia_tabla[$contador]", $referencia, array("class"=>"referencia_tabla"));
                $co2  = HTML::campoOculto("codigo_barras_tabla[$contador]", $codigo_barras, array("class"=>"codigo_barras_tabla"));

                $remover = HTML::boton("botonRemover", "", "removerItem(this);", "eliminar");
                $celda = $co1.$co2.$remover;

                $item_sucursal[] = array( $consecutivo,
                                          $celda,
                                          $referencia,
                                          $codigo_barras
                );
                $consecutivo++;
            }
        }

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
                                    $item,
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
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "modificarItem('$url_id');", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones);
    }

    /*** Enviar datos para la generaciï¿½n del formulario al script que originï¿½ la peticiï¿½n ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);
    exit();
/*** Validar los datos provenientes del formulario ***/
} elseif (!empty($url_validar)) {

    /*** Validar codigo ***/
    if ($url_item == "codigo_interno") {
      $existe = SQL::existeItem("articulos", "codigo_interno", $url_valor,"id !='$url_id'");
      if ($existe) {
        HTTP::enviarJSON($textos["ERROR_EXISTE_CODIGO"]);
      } 
    }
    /*** Validar altura ***/
    if ($url_item == "alto") {

        if ($url_valor < 0 || ctype_alpha($url_valor ) || ($url_valor <> NULL &&!ctype_digit($url_valor))) {
            echo json_encode($textos["ERROR_DATO_ALTO"]);
        }
    }

    /*** Validar ancho ***/
    if ($url_item == "ancho") {

        if ($url_valor < 0 || ctype_alpha($url_valor ) || ($url_valor <> NULL &&!ctype_digit($url_valor))) {
            echo json_encode($textos["ERROR_DATO_ALTO"]);
        }
    }

    /*** Validar profundidad ***/
    if ($url_item == "profundidad") {

        if ($url_valor < 0 || ctype_alpha($url_valor ) || ($url_valor <> NULL &&!ctype_digit($url_valor))) {
            echo json_encode($textos["ERROR_DATO_ALTO"]);
        }
    }

    /*** Validar peso ***/
    if ($url_item == "peso") {

        if ($url_valor < 0 || ctype_alpha($url_valor ) || ($url_valor <> NULL &&!ctype_digit($url_valor))) {
            echo json_encode($textos["ERROR_DATO_ALTO"]);
        }
    }
    exit();
/*** Modificar el elemento seleccionado ***/
} 

    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_MODIFICADO"];

   /*** Validar campos requeridos ***/
   if(empty($forma_codigo_interno)){
		$error   = true;
		$mensaje = $textos["CODIGO_VACIO"];  
		
	}elseif(empty($forma_descripcion)){
		$error   = true;
		$mensaje = $textos["DESCRIPCION_VACIO"]; 
		
	}elseif(empty($forma_referencia)){
		$error   = true;
		$mensaje = $textos["REFERENCIA_VACIO"]; 
		
	}elseif(empty($forma_unidad_venta)){
      $error = true;
      $mensaje = $textos["UNIDAD_VENTA_VACIO"];   
	
	}elseif(empty($forma_unidad_compra)){
      $error = true;
      $mensaje = $textos["UNIDAD_COMPRA_VACIO"];   
	
	}elseif(empty($forma_unidad_presentacion)){
      $error = true;
      $mensaje = $textos["UNIDAD_PRESENTACION_VACIO"];   
	
	}elseif(empty($forma_id_proveedor)){
      $error = true;
      $mensaje = $textos["PREVEEDOR_VACIO"];   
    
    }elseif($existe = SQL::existeItem("articulos", "codigo_interno", $forma_codigo_interno,"id != '$forma_id'")){
	    $error   = true;
        $mensaje = $textos["ERROR_EXISTE_CODIGO"]; 

    } else {

        $datos = array(
            "codigo_interno"         => $forma_codigo_interno,
            "descripcion"            => $forma_descripcion,
            "tipo_articulo"          => $forma_tipo_articulo,
            "ficha_tecnica"          => $forma_ficha_tecnica,
            "alto"                   => $forma_alto,
            "ancho"                  => $forma_ancho,
            "profundidad"            => $forma_profundidad,
            "peso"                   => $forma_peso,
            "garantia"               => $forma_garantia,
            "garantia_partes"        => $forma_garantia_partes,
            "id_impuesto_compra"     => $forma_impuesto_compra,
            "id_impuesto_venta"      => $forma_impuesto_venta,
            "id_marca"               => $forma_marca,
            "id_estructura_grupo"    => $forma_id_estructura_grupo,
            "manejo_inventario"      => $forma_manejo_inventario,
            "id_unidad_venta"        => $forma_unidad_venta,
            "id_unidad_compra"       => $forma_unidad_compra,
            "id_unidad_presentacion" => $forma_unidad_presentacion,
            "id_pais"                => $forma_pais,
            "activo"                 => $forma_activo
        );

        $consulta = SQL::modificar("articulos", $datos, "id = '$forma_id'");
        if ($consulta) {
            $error   = false;
            $mensaje = $textos["ITEM_MODIFICADO"];

            if ($consulta) {
                $error   = false;
                $mensaje = $textos["ITEM_MODIFICADO"];
            } else {
                $error   = true;
                $mensaje = $textos["ERROR_MODIFICAR_ITEM"];
            }

            if (!empty($_FILES["imagen"])) {
                $id         = SQL::obtenerValor("imagenes","id","id_asociado = '$forma_id' AND categoria = '2'");
                if ($id){
                	$eliminar   = SQL::eliminar("imagenes", "id = '$id'");
                }
                $original   = $_FILES["imagen"]["name"];
                $temporal   = $_FILES["imagen"]["tmp_name"];
                $extension  = strtolower(substr($original, (strrpos($original, ".") - strlen($original)) + 1));

                if (strtolower($extension) != "png" && strtolower($extension) != "jpg" && strtolower($extension) != "gif") {
                    $error   = true;
                    $mensaje = $textos["ERROR_FORMATO_IMAGEN"];

                } else {
                    list($ancho, $alto, $tipo) = getimagesize($temporal);

                    $datos   = array(
                        "categoria"   => 2,
                        "id_asociado" => $forma_id,
                        "contenido"   => file_get_contents($temporal),
                        "tipo"        => $tipo,
                        "extension"   => $extension,
                        "ancho"       => $ancho,
                        "alto"        => $alto
                    );

                    $insertar = SQL::insertarArchivo("imagenes", $datos);
                }
			}
			
			/*** Obtener id de la tabla referencias_por_proveedor ***/
            $id_referencia  = SQL::obtenerValor("referencias_por_proveedor", "id", "id_articulo = '$forma_id'");

            $datos = array(
                "id_articulo"   => $forma_id,
                "referencia"    => $forma_referencia,
                "id_proveedor"  => $forma_id_proveedor,
                "codigo_barras" => $forma_codigo_barras,
                "principal"     => $forma_principal
            );

            $consulta = SQL::modificar("referencias_por_proveedor", $datos, "id = '$id_referencia'");

        } else {
            $error   = true;
            $mensaje = $textos["ERROR_MODIFICAR_ITEM"];
        }
    }

    /*** Enviar datos con la respuesta del proceso al script que originï¿½ la peticiï¿½n ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);

?>

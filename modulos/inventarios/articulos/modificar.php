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
$indicador = 0;
$tabla                      = "usuarios";
$columnas                   = SQL::obtenerColumnas($tabla);
$consulta                   = SQL::seleccionar(array($tabla), $columnas, "usuario = '$sesion_usuario'");
$datos                      = SQL::filaEnObjeto($consulta);
$sesion_id_usuario_ingreso  = $datos->codigo;

/*** Devolver datos para autocompletar la búsqueda ***/
if (isset($url_completar)) {
    echo SQL::datosAutoCompletar("seleccion_proveedores", $url_q);
    exit;
}

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

if(isset($url_eliminarReferencia) && isset($url_indice_tabla)){
   
    $llave                          = explode("|",$url_indice_tabla);
    $codigo_articulo                = $llave[0];
    $referencia                     = $llave[1];
    $documento_identidad_proveedor  = $llave[2];
    $codigo_barras                  = $llave[3];
    $principal                      = $llave[4];

    $condicion           = "documento_identidad_proveedor = '$documento_identidad_proveedor' AND codigo_articulo='$codigo_articulo'";
    $condicion          .= " AND codigo_barras = '$codigo_barras' AND principal = '0'";
    $eliminar_referencia = SQL::eliminar("referencias_proveedor", $condicion);

    if($eliminar_referencia){
        $error   = 1;
        $mensaje = $textos["REFERENCIA_ELIMINADA"];
    }else{
        $error   = 0;
        $mensaje = $textos["ERROR_REFERENCIA"];
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
        $llave                         = explode("|",$url_id);
        $url_id                        = $llave[0];
        $documento_identidad_proveedor = $llave[1];

        $vistaConsulta = "articulos";
        $condicion     = "codigo = '$url_id'";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, $condicion);

        $datos                  = SQL::filaEnObjeto($consulta);
        $codigo_impuesto_compra = $datos->codigo_impuesto_compra;
        $codigo_impuesto_venta  = $datos->codigo_impuesto_venta;
        $codigo_marca           = $datos->codigo_marca;
        $estado                 = $datos->activo;
        $codigo_iso             = $datos->codigo_iso;
 
        $error                  = "";
        $titulo                 = $componente->nombre;

        $consulta = SQL::seleccionar(array("imagenes"), array("id_asociado","categoria","ancho","alto"), "id_asociado = '$url_id' AND categoria = '2'");
        $imagen   = SQL::filaEnObjeto($consulta);

        /***Obtener datos de la tabla de articulos ***/
        $nombre_proveedor       = SQL::obtenerValor("seleccion_proveedores", "nombre", "id = '$documento_identidad_proveedor'");
        $nombre_proveedor       = explode("|",$nombre_proveedor);
        $nombre_proveedor       = $nombre_proveedor[0];

        /*******************************************************************/
        $consulta_proveedor_marca = SQL::seleccionar(array("proveedores_marcas"),array("codigo_marca"),"documento_identidad_proveedor = '$documento_identidad_proveedor'");

        $vector_marcas = array();
        $vector_marcas[0] = "";

        if (SQL::filasDevueltas($consulta_proveedor_marca)) {
                
            while($datos_proveedor_marca = SQL::filaEnObjeto($consulta_proveedor_marca)){
                
                $codigos_marcas                 = $datos_proveedor_marca->codigo_marca;
                $descripcion_marcas             = $datos_proveedor_marca->descripcion;
                $vector_marcas[$codigos_marcas] = $descripcion_marcas;
            }
        }
        /*******************************************************************/

        // Obtener datos de la referencias del codigo
        $referencia_principal = SQL::obtenerValor("referencias_proveedor", "referencia", "codigo_articulo = '$url_id' AND principal ='1' AND documento_identidad_proveedor = '$documento_identidad_proveedor'");
        $codigo_barras        = SQL::obtenerValor("referencias_proveedor", "codigo_barras", "codigo_articulo = '$url_id' AND principal ='1' AND documento_identidad_proveedor = '$documento_identidad_proveedor'");
        // Obtener la marca del articulo
        $codigo_marca = SQL::obtenerValor("articulos", "codigo_marca", "codigo = '$url_id'");

        // Obtener costo articulo
        $costo = SQL::obtenerValor("lista_precio_articulos", "costo", "codigo_articulo = '$url_id'");  
        //$costo = number_format($costo,2);      

        // Obtener referencias del proveedor y articulo
        $referencia_alterna = SQL::obtenerValor("referencias_proveedor", "referencia", "codigo_articulo = '$url_id' AND principal ='0' AND principal = '1' AND documento_identidad_proveedor = '$documento_identidad_proveedor'");

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

        $tipo_articulo= array(
            "1" => $textos["MATERIA_PRIMA"],
            "2" => $textos["PRODUCTO_TERMINADO"]
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
                HTML::campoTextoCorto("*codigo", $textos["CODIGO"], 15, 15, $datos->codigo, array("readonly" => "true"), array("title" => $textos["AYUDA_CODIGO"],"onBlur" => "validarItem(this);")),

                HTML::listaSeleccionSimple("tipo_articulo", $textos["TIPO_ARTICULO"], $tipo_articulo, $datos->tipo_articulo, array("title" => $textos["AYUDA_TIPO_ARTICULO"]))
            ),
            array(
                HTML::campoTextoCorto("*selector1", $textos["PROVEEDOR"], 40, 255, $nombre_proveedor, array("title" => $textos["AYUDA_PROVEEDOR"], "class" => "autocompletable"))
                .HTML::campoOculto("documento_identidad_proveedor", $documento_identidad_proveedor)
            ),
            array(
                HTML::campoTextoCorto("referencia_principal", $textos["REFERENCIA_PROVEEDOR"], 20, 20, $referencia_principal, array("title" => $textos["AYUDA_REFERENCIA_PROVEEDOR"])),

                HTML::campoTextoCorto("codigo_barras", $textos["CODIGO_BARRAS"], 13, 13, $codigo_barras,array("title" => $textos["AYUDA_CODIGO_BARRAS"],"onKeyPress" => "return campoEntero(event)")),

                HTML::campoTextoCorto("costo", $textos["COSTO"], 15, 15, $costo, array("title" => $textos["AYUDA_COSTO"]))
                //HTML::campoTextoCorto("costo", $textos["COSTO"], 15, 15, $costo, array("title" => $textos["AYUDA_COSTO"],"onkeyup"=>"formatoMiles(this)", "onchange"=>"formatoMiles(this)"))
            ),
            array(
                HTML::campoTextoCorto("*descripcion", $textos["DESCRIPCION"], 55, 255, htmlentities(stripslashes($datos->descripcion)), array("title" => $textos["AYUDA_DESCRIPCION"]))
            ),
            array(
                HTML::marcaSeleccion("imprime",$textos["SIEMPRE_IMPRIME"], 1, $imprime1 ,array("id"=>"siempre_imprime"))
            //    HTML::marcaSeleccion("imprime",$textos["OCASIONALMENTE_IMPRIME"],2,$imprime2,array("id"=>"ocasionalmente_imprime")),
            //    HTML::marcaSeleccion("imprime",$textos["NUNCA_IMPRIME"],3,$imprime3,array("id"=>"nunca_imprime")),
            ),
            array(
                HTML::campoTextoCorto("alto", $textos["ALTO"], 4, 4, $datos->alto, array("title" => $textos["AYUDA_ALTO"],"onKeyPress" => "return campoEntero(event)")),

                HTML::campoTextoCorto("ancho", $textos["ANCHO"], 4, 4, $datos->ancho, array("title" => $textos["AYUDA_ANCHO"],"onKeyPress" => "return campoEntero(event)")),

                HTML::campoTextoCorto("profundidad", $textos["PROFUNDIDAD"], 4, 4, $datos->profundidad, array("title" => $textos["AYUDA_PROFUNDIDAD"],"onKeyPress" => "return campoEntero(event)")),

                HTML::campoTextoCorto("peso", $textos["PESO"], 8, 8, $datos->peso, array("title" => $textos["AYUDA_PESO"],"onKeyPress" => "return campoEntero(event)"))
            ),
            array(
                HTML::campoTextoLargo("ficha_tecnica", $textos["FICHA_TECNICA"], 5, 30, $datos->ficha_tecnica, array("title" => $textos["AYUDA_FICHA_TECNICA"],"onBlur" => "validarItem(this);")),
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
                HTML::listaSeleccionSimple("*codigo_impuesto_compra", $textos["IMPUESTO_COMPRA"], HTML::generarDatosLista("tasas", "codigo", "descripcion"), $codigo_impuesto_compra, array("title" => $textos["AYUDA_IMPUESTO_COMPRA"])),

                //HTML::listaSeleccionSimple("*codigo_impuesto_venta", $textos["IMPUESTO_VENTA"], HTML::generarDatosLista("tasas", "codigo", "descripcion"), $codigo_impuesto_venta, array("title" => $textos["AYUDA_IMPUESTO_VENTA"]))
            ),
            array(
                HTML::listaSeleccionSimple("codigo_marca", $textos["MARCA"], HTML::generarDatosLista("marcas", "codigo", "descripcion","codigo>0"), $codigo_marca, array("title" => $textos["AYUDA_MARCA"]))
            ),
            array(
                HTML::listaSeleccionSimple("*codigo_unidad_compra", $textos["UNIDAD_COMPRA"], HTML::generarDatosLista("unidades", "codigo", "nombre"), $datos->codigo_unidad_compra, array("title" => $textos["AYUDA_UNIDAD_COMPRA"]))
            ),
            array(
                HTML::listaSeleccionSimple("*codigo_iso", $textos["PAIS"], HTML::generarDatosLista("paises", "codigo_iso", "nombre"),
                 $codigo_iso, array("title" => $textos["AYUDA_PAIS"]))
            ),
            array(
                HTML::listaSeleccionSimple("*activo", $textos["ESTADO"], $activo, $estado, array("title" => $textos["AYUDA_ESTADO"]))
            )
        );

        /*** Obtener sucursales relacionadas con las sucursales del banco ***/
        $orden_referencias    = 0;
        $lista_items          = '';

        $consulta_referencias = SQL::seleccionar(array("referencias_proveedor"), array("*"), "codigo_articulo = '$url_id' AND principal='0'");
        $consecutivo          = 0;

        if (SQL::filasDevueltas($consulta_referencias)) {
            $contador = 0;
            while ($datos_referencias = SQL::filaEnObjeto($consulta_referencias)) {

                $id_referencia   = $datos_referencias->codigo_articulo."|".$datos_referencias->referencia."|";
                $id_referencia  .= $datos_referencias->documento_identidad_proveedor."|".$datos_referencias->codigo_barras."|";
                $id_referencia  .= $datos_referencias->principal;

                $referencia      = $datos_referencias->referencia;
                $codigo_barras   = $datos_referencias->codigo_barras;

                $co1 = HTML::campoOculto("referencias[".$id_referencia."]", $id_referencia, array("class"=>"referencias"));
                $co2 = HTML::campoOculto("referencias_alternas[".$id_referencia."]", $id_referencia, array("class"=>"referencias_alternas"));
                $co3 = HTML::campoOculto("estadoModificar[".$id_referencia."]", '1', array("class"=>"estadoModificar"));
                $co4 = HTML::campoOculto("codigo_barras_referencia[".$id_referencia."]", $id_referencia, array("class"=>"codigo_barras_referencia"));

                $remover = HTML::boton("botonRemover", "", "removerItem(this);", "eliminar");
                $celda   = $co1.$co2.$co3.$co4.$remover;

                $lista_items[] = array($id_referencia,
                                       $celda,
                                       $referencia,
                                       $codigo_barras
                );
                //$consecutivo++;
                $orden_referencias = $id_referencia+1;
            }
        }

        if (($imagen) || (!$imagen)) {
            $id_imagen = $imagen->id_asociado."|".$imagen->categoria;

            $formularios["PESTANA_IMAGEN"] = array(
                array(  
                    HTML::selectorArchivo("imagen", $textos["FOTO"], array("title" => $textos["AYUDA_FOTO"])),
                    
                    HTML::imagen(HTTP::generarURL("VISUIMAG")."&id=".$id_imagen, array("width" => $imagen->ancho, "height" => $imagen->alto))
                )
            );
        }

        $formularios["PESTANA_REFERENCIA"] = array(
            array(
                HTML::campoTextoCorto("*referencia_alterna", $textos["REFERENCIA"], 30, 30, "",array("title" => $textos["AYUDA_REFERENCIA"])),

                HTML::campoTextoCorto("codigo_barras_alterna", $textos["CODIGO_BARRAS"], 13, 13, "",array("title" => $textos["AYUDA_CODIGO_BARRAS"],"onKeyPress" => "return campoEntero(event)"))
                .HTML::campoOculto("orden_referencias", $orden_referencias),
            ),
            array(
                HTML::boton("botonAgregar", $textos["AGREGAR"], "agregarItem();", "adicionar"),

                HTML::contenedor(HTML::boton("botonRemover", "", "removerItem(this);", "eliminar"), array("id" => "removedor", "style" => "display: none"))
            ),
            array(
                HTML::generarTabla( array("id","","REFERENCIA","CODIGO_BARRAS"),
                                    $lista_items,
                                    array("I","I","D"),
                                    "lista_items_referencias",
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
                HTML::campoOculto("indice","",0),
                HTML::campoOculto("estado","",0, array("class"=>"estado")),
            )
        );

        /*** Definición de botones ***/
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "modificarItem('$url_id');", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones);
        //$contenido = HTML::generarPestanas($formularios, $botones,"",$funciones);
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
    if ($url_item == "codigo") {
      $existe = SQL::existeItem("articulos", "codigo", $url_valor,"principal != '0' AND referencia!=''");
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
if(empty($forma_codigo)){
    $error   = true;
    $mensaje = $textos["CODIGO_VACIO"];  
		
}elseif(empty($forma_descripcion)){
	$error   = true;
	$mensaje = $textos["DESCRIPCION_VACIO"]; 
		
}/*elseif(empty($forma_referencia_principal)){
	$error   = true;
	$mensaje = $textos["REFERENCIA_VACIO"];   
}*/elseif(empty($forma_codigo_unidad_compra)){
    $error = true;
    $mensaje = $textos["UNIDAD_COMPRA_VACIO"];   
}elseif(empty($forma_documento_identidad_proveedor)){
    $error = true;
    $mensaje = $textos["PROVEEDOR_VACIO"];   
    
}elseif($existe = SQL::existeItem("articulos", "codigo", $forma_codigo,"codigo != '$forma_codigo'")){
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
    
    //$forma_costo = quitarMiles($forma_costo);
    $forma_costo = str_replace(",", ".", $forma_costo); 
    $forma_documento_identidad_proveedor = $forma_selector1;
    $forma_documento_identidad_proveedor = explode("-",$forma_documento_identidad_proveedor);
    $forma_documento_identidad_proveedor = $forma_documento_identidad_proveedor[0];
    $datos = array(
        "codigo"                     => $forma_codigo,
        "descripcion"                => $forma_descripcion,
        "tipo_articulo"              => $forma_tipo_articulo,
        "ficha_tecnica"              => $forma_ficha_tecnica,
        "alto"                       => $forma_alto,
        "ancho"                      => $forma_ancho,
        "profundidad"                => $forma_profundidad,
        "peso"                       => $forma_peso,
        "garantia"                   => "",
        "garantia_partes"            => "",
        "codigo_impuesto_compra"     => $forma_codigo_impuesto_compra,
        "codigo_impuesto_venta"      => $forma_codigo_impuesto_compra,
        "codigo_marca"               => $forma_codigo_marca,
        "codigo_estructura_grupo"    => $forma_codigo_estructura_grupo,
        "manejo_inventario"          => '1',
        "codigo_unidad_venta"        => '1',
        "codigo_unidad_compra"       => $forma_codigo_unidad_compra,
        "codigo_unidad_presentacion" => '1',
        "codigo_iso"                 => $forma_codigo_iso,
        "activo"                     => $forma_activo,
        "imprime_listas"             => '1'
    );

    $consulta = SQL::modificar("articulos", $datos, "codigo = '$forma_codigo'");

    $datos_articulo = array(
        "codigo_articulo"               => $forma_codigo,
        "documento_identidad_proveedor" => $forma_documento_identidad_proveedor,
        "fecha_modificacion"            => date("Y-m-d H:i:s")
    );

    $modificar_articulo = SQL::modificar("articulos_proveedor", $datos_articulo,"codigo_articulo = '$forma_codigo'");

    $datos_listas = array(
            "codigo_articulo"            => $forma_codigo,
            "fecha"                      => date("Y-m-d H:i:s"),
            "costo"                      => $forma_costo,
            "codigo_usuario_registra"    => $sesion_id_usuario_ingreso,
            "fecha_registra"             => date("Y-m-d H:i:s"),
            "fecha_modificacion"         => ""
        );        

    $modificar_listas = SQL::modificar("lista_precio_articulos", $datos_listas, "codigo_articulo = '$forma_codigo'");

    $datos = array(
        "codigo_articulo"               => $forma_codigo,
        "referencia"                    => $forma_referencia_principal,
        "documento_identidad_proveedor" => $forma_documento_identidad_proveedor,
        "codigo_barras"                 => $forma_codigo_barras,
        "principal"                     => 1
    );
                        
    $condicion = "codigo_articulo='$forma_codigo' AND principal = '1'";
    $insertar  = SQL::modificar("referencias_proveedor", $datos, $condicion);

    if ($insertar) {
        $error   = false;
        $mensaje = $textos["ITEM_MODIFICADO"];

        if (!empty($_FILES["imagen"]["name"])) {
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

                $consulta = SQL::eliminar("imagenes", "categoria = '2' AND id_asociado = '$forma_codigo'");
                $insertar = SQL::insertarArchivo("imagenes", $datos);
            }

        } if (isset($forma_referencias)) {
            foreach ($forma_referencias as $id_referencias_alternas) {
                    
                $id_referencia  = $id_referencias_alternas;
                $referencia     = $forma_referencias_alternas[$id_referencia];
                $codigo_barras  = $forma_codigo_barras_referencia[$id_referencia];
                $estado         = $forma_estadoModificar[$id_referencia];

                $datos = array(
                    "codigo_articulo"               => $forma_codigo,
                    "referencia"                    => $referencia,
                    "documento_identidad_proveedor" => $forma_documento_identidad_proveedor,
                    "codigo_barras"                 => $codigo_barras,
                    "principal"                     => 0
                );

                if ($estado != '1'){
                    $insertar = SQL::insertar("referencias_proveedor", $datos);
                } else {

                    $llave                          = explode("|",$forma_referencias);
                    $codigo_articulo                = $llave[0];
                    $referencia                     = $llave[1];
                    $documento_identidad_proveedor  = $llave[2];
                    $codigo_barras                  = $llave[3];
                    $principal                      = $llave[4];

                    $datos = array(
                        "codigo_articulo"               => $codigo_articulo,
                        "referencia"                    => $referencia,
                        "documento_identidad_proveedor" => $forma_documento_identidad_proveedor,
                        "codigo_barras"                 => $forma_codigo_barras,
                        "principal"                     => 0
                    );
                        
                    $condicion  = "documento_identidad_proveedor = '$forma_documento_identidad_proveedor' AND codigo_articulo='$codigo_articulo' AND principal = '0'";
    
                    $modificar  = SQL::modificar("referencias_proveedor", $datos, $condicion);
                    /*** Error de insercón ***/
                    if (!$modificar) {
                        $error     = true;
                        $mensaje   = $textos["ERROR_MODIFICAR_ITEM"];
                    }
                }
            }
        }
    }else {
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

<?php
    /*** Incluir archivo de configuración principal ***/
    require "../configuracion/global.php";

    /*** Incluir archivos de clases ***/
    require_once $rutasGlobales["clases"]."/sql.php";
    require_once $rutasGlobales["clases"]."/cadena.php";

    SQL::abrirConexion();

    $rutaModulos    = realpath($rutasGlobales["modulos"]);
    $archivoEsquema = $archivosGlobales["esquemaSQL"];
    $prefijoTabla   = SQL::$prefijoTabla;

    $fila = 0;
    $total_insertados = 0;
    $existen = 0;
    $total_referencias = 0;

    if (($archivo = fopen("planoarticulos.csv", "r")) !== FALSE) {

        while (($datos = fgetcsv($archivo, 15000, ";")) !== FALSE) {
            $campos_fila = count($datos);

            $codigo_interno = $datos[0];
            $codigo_interno = rtrim($codigo_interno,"'");
            $codigo_interno = ltrim($codigo_interno,"'");
            $existe_codigo = SQL::existeItem("articulos", "codigo_interno", $codigo_interno,"codigo_interno !=''");
            if (!$existe_codigo){

                $descripcion  = $datos[1];
                $descripcion  = rtrim($descripcion,"'");
                $descripcion  = ltrim($descripcion,"'");
                $codigo_marca = $datos[2];
                $codigo_marca = rtrim($codigo_marca,"'");
                $codigo_marca = ltrim($codigo_marca,"'");
                $existe_marca = SQL::existeItem("marcas", "codigo", $codigo_marca,"codigo !=''");
                if ($existe_marca){
                    $id_marca     = SQL::obtenerValor("marcas","id","codigo='$codigo_marca'");
                    $id_proveedor = SQL::obtenerValor("proveedores_marcas","id_proveedor","id_marca='$id_marca'");
                    if (!$id_proveedor){
                        $id_proveedor = 0;
                    }

                } else {
                    $id_marca     = 0;
                    $id_proveedor = 0;
                }

                $datos_articulos = array(
                    "codigo_interno"         => $codigo_interno,
                    "descripcion"            => $descripcion,
                    "tipo_articulo"          => 1,
                    "ficha_tecnica"          => $codigo_interno,
                    "alto"                   => 0,
                    "ancho"                  => 0,
                    "profundidad"            => 0,
                    "peso"                   => 0,
                    "id_impuesto_compra"     => 65,
                    "id_impuesto_venta"      => 65,
                    "id_marca"               => $id_marca,
                    "id_estructura_grupo"    => 0,
                    "manejo_inventario"      => 1,
                    "detalle_kardex"         => 0,
                    "id_unidad_venta"        => 1,
                    "id_unidad_compra"       => 1,
                    "id_unidad_presentacion" => 1,
                    "id_pais"                => 46,
                    "activo"                 => 1,
                    "imprime_listas"         => 1
                );

                $nombreTabla = "articulos";
                foreach($datos_articulos as $campo => $valor){
                    $listaCampos[]  = $campo;
                    $valor     = str_replace("&", "&amp;", $valor);
                    $valor     = str_replace("<", "&lt;", $valor);
                    $valor     = str_replace(">", "&gt;", $valor);
                    if (Cadena::contieneUTF8($valor)) {
                        $valor = utf8_decode($valor);
                    }
                    if (strtolower($valor) == "null"){
                        $listaValores[] = "$valor";
                    }else{
                        $listaValores[] = "'$valor'";
                    }
                }
                $indices           = implode(",", $listaCampos);
                $valores           = implode(",", $listaValores);
                $sentenciaInsertar = "INSERT INTO ".$prefijoTabla.$nombreTabla." ($indices)\n    VALUES($valores);\n";
                $insertar          = SQL::correrConsulta($sentenciaInsertar);
                if (mysql_error()) {echo "<span class='error'><b>Error: </b>".mysql_error().":</span><br>".$sentenciaInsertar."<br>";}
                unset($listaCampos, $listaValores);

                if ($insertar){
                    $consulta_articulo = SQL::seleccionar(array("articulos"),array("id"),"id > 0 ORDER BY id DESC LIMIT 1");
                    if (SQL::filasDevueltas($consulta_articulo)){
                        $dato_articulo = SQL::filaEnObjeto($consulta_articulo);
                        $id_articulo   = $dato_articulo->id;
                    } else {
                        $id_articulo   = 0;
                    }
                    echo "<p> Codigo $codigo_interno grabado satisfactoriamente. Id articulo: $id_articulo <br /></p>\n";

                    $referencias = array(
                        "id_articulo"   => $id_articulo,
                        "referencia"    => $codigo_interno,
                        "id_proveedor"  => $id_proveedor,
                        "codigo_barras" => 0,
                        "principal"     => "1"
                    );

                    $nombreTabla = "referencias_por_proveedor";
                    foreach($referencias as $campo => $valor){
                        $listaCampos[]  = $campo;
                        $valor     = str_replace("&", "&amp;", $valor);
                        $valor     = str_replace("<", "&lt;", $valor);
                        $valor     = str_replace(">", "&gt;", $valor);
                        if (Cadena::contieneUTF8($valor)) {
                            $valor = utf8_decode($valor);
                        }
                        if (strtolower($valor) == "null"){
                            $listaValores[] = "$valor";
                        }else{
                            $listaValores[] = "'$valor'";
                        }
                    }
                    $indices             = implode(",", $listaCampos);
                    $valores             = implode(",", $listaValores);
                    $sentenciaInsertar   = "INSERT INTO ".$prefijoTabla.$nombreTabla." ($indices)\n    VALUES($valores);\n";
                    $insertar_referencia = SQL::correrConsulta($sentenciaInsertar);
                    if (mysql_error()) {echo "<span class='error'><b>Error: </b>".mysql_error().":</span><br>".$sentenciaInsertar."<br>";}
                    unset($listaCampos, $listaValores);

                    if ($insertar_referencia){
                        $total_referencias ++;
                    }

                    $total_insertados ++;
                } else {
                    //$error = mysql_error();
                    echo "<p> Error, no inserto el codigo $codigo_interno<br /></p>\n";
                }
            } else {
                echo "<p> Existe codigo $codigo_interno<br /></p>\n";
                $existen ++;
            }
            $fila++;
        }
        echo "<p> Total articulos $fila <br /></p>\n";
        echo "<p> Total articulos que existen $existen <br /></p>\n";
        echo "<p> Total articulos insertados $total_insertados <br /></p>\n";
        echo "<p> Total referencias insertados $total_referencias <br /></p>\n";
        fclose($archivo);
    }
?>

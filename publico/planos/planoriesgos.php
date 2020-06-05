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

    if (($archivo = fopen("riesgos.csv", "r")) !== FALSE) {

        while (($datos = fgetcsv($archivo, 15000, ";")) !== FALSE) {
            $campos_fila = count($datos);

            $datos_entidad = array(
                "codigo"                      => $datos[0],
                "codigo_ruaf"                 => $datos[1],
                "documento_identidad_tercero" => $datos[2],
                "nombre"                      => $datos[3],
                "riesgos_profesionales"       => "1"
            );

            $nombreTabla = "entidades_parafiscales";
            foreach($datos_entidad as $campo => $valor){
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
            $sentenciaInsertar = "REPLACE INTO ".$prefijoTabla.$nombreTabla." ($indices)\n    VALUES($valores);\n";
            $insertar          = SQL::correrConsulta($sentenciaInsertar);
            if (mysql_error()) {
                echo "<span class='error'><b>Error: </b>".mysql_error().":</span><br>".$sentenciaInsertar."<br>";
            }
            unset($listaCampos, $listaValores);

            if ($insertar){
                echo "<p> Entidad grabada satisfactoriamente: $datos[3] <br /></p>\n";
            }
            $fila++;
        }
        echo "<p> Total entidades $fila <br /></p>\n";
        fclose($archivo);
    }
?>

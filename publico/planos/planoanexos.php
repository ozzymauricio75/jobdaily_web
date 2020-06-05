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

    /*** Incluir archivo de configuración principal ***/
    require "../configuracion/global.php";

    /*** Incluir archivos de clases ***/
    require_once $rutasGlobales["clases"]."/sql.php";
    require_once $rutasGlobales["clases"]."/cadena.php";

    SQL::abrirConexion();
    echo "<p> Carga archivo plano de anexos contables <br /></p>\n";

    $rutaModulos    = realpath($rutasGlobales["modulos"]);
    $archivoEsquema = $archivosGlobales["esquemaSQL"];
    $prefijoTabla   = SQL::$prefijoTabla;

    $total_insertados = 0;
    $nombreArchivo = $rutasGlobales["archivos"]."/anexos_contables.csv";

    if (($archivo = fopen($nombreArchivo, "r")) !== FALSE) {

        while (($datos = fgetcsv($archivo, 15000, ";")) !== FALSE) {

            $datos_anexos = array(
                "codigo"      => $datos[0],
                "descripcion" => $datos[1]
            );
            $nombreTabla = "anexos_contables";

            foreach($datos_anexos as $campo => $valor){
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
            unset($listaCampos, $listaValores);
            if ($insertar){
                $total_insertados++;
            }
        }
        echo "<p> Total anexos contables insertados $total_insertados <br /></p>\n";
        fclose($archivo);
    } else {
        echo "<p> No se pudo abrir el archivo $nombreArchivo <br /></p>\n";
    }
?>

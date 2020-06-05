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
    * modificarlo  bajo los t�rminos de la Licencia P�blica General GNU
    * publicada por la Fundaci�n para el Software Libre, ya sea la versi�n 3
    * de la Licencia, o (a su elecci�n) cualquier versi�n posterior.
    *
    * Este programa se distribuye con la esperanza de que sea �til, pero
    * SIN GARANT�A ALGUNA; ni siquiera la garant�a impl�cita MERCANTIL o
    * de APTITUD PARA UN PROP�ITO DETERMINADO. Consulte los detalles de
    * la Licencia P�blica General GNU para obtener una informaci�n m�s
    * detallada.
    *
    * Deber�a haber recibido una copia de la Licencia P�blica General GNU
    * junto a este programa. En caso contrario, consulte:
    * <http://www.gnu.org/licenses/>.
    *
    **/

    /*** Incluir archivo de configuraci�n principal ***/
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

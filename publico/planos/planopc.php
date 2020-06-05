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
    echo "<p> Carga archivo plano de movimientos de nomina <br /></p>\n";

    $rutaModulos    = realpath($rutasGlobales["modulos"]);
    $archivoEsquema = $archivosGlobales["esquemaSQL"];
    $prefijoTabla   = SQL::$prefijoTabla;

    $total_insertados = 0;

    if (($archivo = fopen("movimientosnomina.csv", "r")) !== FALSE) {

        while (($datos = fgetcsv($archivo, 15000, ";")) !== FALSE) {

            if ($datos[5] == 1){
                $clase_cuenta = "1";
            } else {
                $clase_cuenta = "2";
            }

            if ($datos[7] == '1'){
                $maneja_tercero = "1";
            } else {
                $maneja_tercero = "0";
            }

            $datos_plan_contable = array(
                "codigo_contable"           => $datos[0],
                "descripcion"               => $datos[1],
                "codigo_contable_padre"     => $datos[2],
                "naturaleza_cuenta"         => $datos[3],
                "clase_cuenta"              => $clase_cuenta,
                "tipo_cuenta"               => $datos[4],
                "maneja_tercero"            => $maneja_tercero,
                "maneja_saldos"             => "0",
                "maneja_subsistema"         => "0",
                "codigo_anexo_contable"     => $datos[6],
                "codigo_tasa_aplicar_1"     => 0,
                "codigo_tasa_aplicar_2"     => 0,
                "codigo_concepto_dian"      => 0,
                "tipo_certificado"          => "1",
                "causacion_automatica"      => "0",
                "flujo_efectivo"            => "1",
                "codigo_contable_consolida" => "",
                "codigo_sucursal"           => 0,
                "codigo_moneda_extranjera"  => 0
            );
            $nombreTabla = "plan_contable";

            foreach($datos_plan_contable as $campo => $valor){
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
            //echo "<p> Query ".$sentenciaInsertar."<br /></p>\n";
            $insertar          = SQL::correrConsulta($sentenciaInsertar);

            if ($insertar){
                $total_insertados++;
                echo "<p> Inserto el codigo ".$datos[0]."<br /></p>\n";
            } else {
                echo "<p> Error!!!!! no inserto el codigo ".$datos[0]." ".mysql_error()." ".mysql_errno()."<br /></p>\n";
            }
            unset($listaCampos, $listaValores);
        }
        echo "<p> Total codigos contables insertados $total_insertados <br /></p>\n";
        fclose($archivo);
    }
?>

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
    require "../../configuracion/global.php";

    /*** Incluir archivos de clases ***/
    require_once "../".$rutasGlobales["clases"]."/sql.php";
    require_once "../".$rutasGlobales["clases"]."/cadena.php";

    SQL::abrirConexion();
    echo "<p> Carga archivo plano de movimientos de nomina <br /></p>\n";

    $rutaModulos    = realpath($rutasGlobales["modulos"]);
    $archivoEsquema = $archivosGlobales["esquemaSQL"];
    $prefijoTabla   = SQL::$prefijoTabla;

    $total_insertados   = 0;
    $total_registros    = 0;
    $total_sin_contrato = 0;
    $total_lineas_sin_contrato = 0;
    $total_error        = 0;

    if (($archivo = fopen("nomina.csv", "r")) !== FALSE) {

        while (($datos = fgetcsv($archivo, 15000, ";")) !== FALSE) {

            $documento_identidad         = $datos[0];
            $codigo_transaccion_contable = $datos[1];
            $sentido                     = $datos[2];
            $valor_movimiento            = $datos[3];
            $fecha_movimiento            = $datos[4];
            $total_registros++;

            $consulta = SQL::seleccionar(array("consulta_contrato_empleado"),array("*"),"documento_identidad_empleado='$documento_identidad'","","",0,1);

            if (SQL::filasDevueltas($consulta)){

                $datos_consulta         = SQL::filaEnObjeto($consulta);
                $codigo_empresa         = $datos_consulta->codigo_empresa;
                $fecha_ingreso          = $datos_consulta->fecha_ingreso;
                $codigo_sucursal        = $datos_consulta->codigo_sucursal;
                $fecha_ingreso_sucursal = $datos_consulta->fecha_ingreso_sucursal;
                $codigo_planilla        = $datos_consulta->codigo_planilla;

                $ano_movimiento = substr($fecha_movimiento,0,4);
                $mes_movimiento = substr($fecha_movimiento,4,2);
                $dia_movimiento = substr($fecha_movimiento,6,2);

                if($dia_movimiento>15){
                    $periodo = "3";
                } else {
                    $periodo = "2";
                }

                if (!isset($codigo_contable[$codigo_transaccion_contable])){
                    $codigo_contable[$codigo_transaccion_contable] = SQL::ObtenerValor("transacciones_contables_empleado","codigo_contable","codigo='$codigo_transaccion_contable'");
                }

                $condicion   = "ano_generacion='$ano_movimiento' AND mes_generacion='$mes_movimiento' AND codigo_planilla='$codigo_planilla'";
                $condicion   .= " AND periodo_pago='$periodo' AND codigo_transaccion_contable='$codigo_transaccion_contable'";
                $consecutivo = SQL::obtenerValor("movimientos_nomina_migracion","MAX(consecutivo)",$condicion);

                echo "<p> Registro ".$datos[0].",".$datos[1].",".$datos[2].",".$datos[3].",".$datos[4]."<br /></p>\n";
                echo "<p> Condicion ".$condicion."<br /></p>\n";
                echo "<p> Consecutivo tabla".$consecutivo."<br /></p>\n";

                if ($consecutivo){
                    $consecutivo++;
                } else {
                    $consecutivo = 1;
                }
                echo "<p> Consecutivo condicion".$consecutivo."<br /></p>\n";
                echo "<p> <br /></p>\n";

                $datos_migracion = array(
                    "ano_generacion"               => $ano_movimiento,
                    "mes_generacion"               => $mes_movimiento,
                    "codigo_planilla"              => $codigo_planilla,
                    "periodo_pago"                 => $periodo,
                    "codigo_transaccion_contable"  => $codigo_transaccion_contable,
                    "consecutivo"                  => $consecutivo,
                    "codigo_empresa"               => $codigo_empresa,
                    "documento_identidad_empleado" => $documento_identidad,
                    "fecha_ingreso_empresa"        => $fecha_ingreso,
                    "codigo_sucursal"              => $codigo_sucursal,
                    "fecha_ingreso_sucursal"       => $fecha_ingreso_sucursal,
                    "fecha_pago_planilla"          => $ano_movimiento."-".$mes_movimiento."-".$dia_movimiento,
                    "codigo_empresa_auxiliar"      => 0,
                    "codigo_anexo_contable"        => "",
                    "codigo_auxiliar_contable"     => 0,
                    "codigo_contable"              => $codigo_contable[$codigo_transaccion_contable],
                    "sentido"                      => $sentido,
                    "valor_movimiento"             => $valor_movimiento,
                    "contabilizado"                => 0,
                    "codigo_usuario_genera"        => 0,
                    "fecha_registro"               => date("Y-m-d H:i:s")
                );

                $nombreTabla = "movimientos_nomina_migracion";

                foreach($datos_migracion as $campo => $valor){
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
                $sentenciaInsertar = "INSERT INTO ".$prefijoTabla.$nombreTabla." ($indices)\n VALUES($valores);\n";
                //echo "<p> Query ".$sentenciaInsertar."<br /></p>\n";
                $insertar          = SQL::correrConsulta($sentenciaInsertar);

                if ($insertar){
                    $total_insertados++;
                    echo "<p> Inserto el movimiento ".$datos[0].",".$datos[1].",".$datos[2].",".$datos[3].",".$datos[4]."<br /></p>\n";
                } else {
                    echo "<p> Error!!!!! no inserto ".$datos[0]." ".$datos[0].",".$datos[1].",".$datos[2].",".$datos[3].",".$datos[4]." ".$consecutivo." ".mysql_error()." ".mysql_errno()."<br /></p>\n";
                    $total_error++;
                }
                unset($listaCampos, $listaValores);
            } else {
                echo "<p> Error!!!!! no tiene contrato ".$documento_identidad."<br /></p>\n";
                if (!isset($documento_sin_contrato[$documento_identidad])){
                    $documento_sin_contrato[$documento_identidad] = $documento_identidad;
                    $total_sin_contrato++;
                }
                $total_lineas_sin_contrato++;
            }
        }
        echo "<p> Total registros $total_registros <br /></p>\n";
        echo "<p> Total movimientos insertados $total_insertados <br /></p>\n";
        echo "<p> Total no insertados $total_error <br /></p>\n";
        echo "<p> Total empleados sin contrato $total_sin_contrato <br /></p>\n";
        echo "<p> Total lineas sin contrato $total_lineas_sin_contrato <br /></p>\n";
        fclose($archivo);
    }
?>

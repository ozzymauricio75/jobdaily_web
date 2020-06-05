<?php
/**
*
* Copyright (C) 2011 Sae Ltda
* Walter Marquez <walteramg@saeltda.com.co>
*
* Este archivo es parte de:
* Jobdaily:: Sofware empresarial a la medida
*
* Este programa es software libre: usted puede redistribuirlo y/o
* modificarlo  bajo los terminos de la Licencia Publica General GNU
* publicada por la Fundacion para el Software Libre, ya sea la version 3
* de la Licencia, o (a su eleccion) cualquier version posterior.
*
* Este programa se distribuye con la esperanza de que sea util, pero
* SIN GARANTIA ALGUNA; ni siquiera la garantia implicita MERCANTIL o
* de APTITUD PARA UN PROPOSITO DETERMINADO. Consulte los detalles de
* la Licencia Publica General GNU para obtener una informacion mas
* detallada.
*
* Deberia haber recibido una copia de la Licencia Publica General GNU
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
**/

function escribir_plano($archivo,$textos,$numero_linea,$registro,$cedula,$nombre_empleado,$fechaReporte,$codigo_sucursal,$planilla){

    if ($numero_linea == 67 || $numero_linea == 34){

        $codigo_empresa = SQL::obtenerValor("sucursales","codigo_empresa","codigo='$codigo_sucursal'");
        $nombre_empresa = SQL::obtenerValor("empresas","razon_social","codigo='$codigo_empresa'");
        $nit_empresa    = SQL::obtenerValor("empresas","documento_identidad_tercero","codigo='$codigo_empresa'");
        $nombre_empresa = $nombre_empresa." ".$textos["NIT"]." ".$nit_empresa." ".$textos["FECHA_GENERACION"]." ".$fechaReporte;

        /*if ($numero_linea == 67){
            $texto_plano = chr(12);
            fwrite($archivo, $texto_plano);
        }*/

        $texto_plano = $nombre_empresa."\n";
        fwrite($archivo, $texto_plano);
        $numero_linea++;

        $texto_plano = $planilla."\n";
        fwrite($archivo, $texto_plano);
        $numero_linea++;

        $texto_plano = "\n";
        fwrite($archivo, $texto_plano);
        $numero_linea++;

        $texto_plano = $cedula." - ".$nombre_empleado."\n";
        fwrite($archivo, $texto_plano);
        $numero_linea++;

        $texto_plano = "\n";
        fwrite($archivo, $texto_plano);
        $numero_linea++;

        $texto_plano = $textos["NOMBRE_TRANSACCION"]."             ".$textos["TOTAL_DEVENGADO"]." ".$textos["TOTAL_DEDUCIDO"];
        fwrite($archivo, $texto_plano);
        $numero_linea++;

        if($numero_linea == 73){
            $numero_linea = 0;
        }
    }
    fwrite($archivo, $registro);
    $numero_linea++;
    return($numero_linea);
}
?>

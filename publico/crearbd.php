<style>
    .error {
        color: #990000;
    }
</style>
<pre>
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

/*** Incluir archivo de configuración principal ***/
require "../configuracion/global.php";

/*** Incluir archivos de clases ***/
require_once $rutasGlobales["clases"]."/sql.php";
require_once $rutasGlobales["clases"]."/cadena.php";

SQL::abrirConexion();

$rutaModulos    = realpath($rutasGlobales["modulos"]);
$archivoEsquema = $archivosGlobales["esquemaSQL"];
$prefijoTabla   = SQL::$prefijoTabla;

/*** Obtener el contenido de la carpeta de módulos ***/
if ($listaModulos = opendir($rutaModulos)) {

    /*** Obtener lista de carpetas de módulos ***/
    while (false !== ($modulo = readdir($listaModulos))) {

        /*** Procesar carpetas de módulos ***/
        if ($modulo != "." && $modulo != "..") {
            $modulo = "$rutaModulos/$modulo";

            /*** Obtener el contenido de la carpeta del módulo actual ***/
            if ($listaComponentes = opendir($modulo)) {

                /*** Obtener lista de carpetas de componentes del módulo actual ***/
                while (false !== ($componente = readdir($listaComponentes))) {
                    unset($tablas, $llaves, $registros, $vistas);

                    /*** Procesar carpetas de componentes ***/
                    if ($componente != "." && $componente != "..") {
                        $componente = "$modulo/$componente";

                        /*** Procesar sólo si se trata de un directorio ***/
                        if (is_dir($componente)) {
                            $esquema = "$componente/$archivoEsquema";
                            require_once "$componente/idiomas/".$datosGlobales["idioma"].".php";

                            /*** Buscar el archivo de definición del esquema SQL ***/
                            if (file_exists($esquema) && is_readable($esquema)) {
                                include $esquema;

                                $sentenciaCrear    = "";
                                $sentenciaInsertar = "";

                                /*** Parámetros iniciales ***/
                                $resultado = SQL::correrConsulta("/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */");
                                $resultado = SQL::correrConsulta("/*!40103 SET TIME_ZONE='+00:00' */");
                                $resultado = SQL::correrConsulta("/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */");
                                $resultado = SQL::correrConsulta("/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */");
                                $resultado = SQL::correrConsulta("/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */");
                                $resultado = SQL::correrConsulta("/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */");

                                /*** Crear nuevas tablas ***/
                                if (!empty($tablas)) {

                                    foreach ($tablas as $tabla => $columnas) {
                                        $sentenciaBorrar = "DROP TABLE IF EXISTS ".$prefijoTabla.$tabla."\n";
                                        $sentenciaCrear  = "CREATE TABLE IF NOT EXISTS ".$prefijoTabla.$tabla." (\n";

                                        $listaColumnas = array();

                                        foreach ($columnas as $columna => $tipoDatos) {
                                            $listaColumnas[] = "  $columna $tipoDatos";
                                            //$listaColumnas[] = "  $columna $tipoDatos CHARACTER SET latin1 COLLATE latin_spanish_ci";
                                        }

                                        /*** Adicionar código para definición llaves y campos únicos ***/
                                        if (!empty($llavesPrimarias[$tabla])) {
                                            $listaColumnas[]="  PRIMARY KEY ($llavesPrimarias[$tabla])";

                                        }

                                        if (!empty($llavesUnicas[$tabla])) {
                                            foreach ($llavesUnicas[$tabla] as $llave){
                                                $listaColumnas[]="  UNIQUE ($llave)";
                                            }
                                        }

                                        if (!empty($llavesForaneas[$tabla])) {
                                            foreach ($llavesForaneas[$tabla] as $elementoFK) {
                                            $listaColumnas[]="  CONSTRAINT ".$elementoFK[0]." FOREIGN KEY (".$elementoFK[1].") REFERENCES ".$prefijoTabla.$elementoFK[2]."(".$elementoFK[3].") ON UPDATE CASCADE ON DELETE RESTRICT";

                                            }
                                        }
                                        $sentenciaCrear .= implode(",\n", $listaColumnas);
                                        //$sentenciaCrear .= "\n) ENGINE=InnoDB DEFAULT CHARSET=latin1;\n";
                                        $sentenciaCrear .= "\n) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE latin1_spanish_ci;\n";
                                        $sentenciaAI     = "SET INSERT_ID = 0;\n";

                                        if ($borrarSiempre) {
                                            $resultado = SQL::correrConsulta($sentenciaBorrar);
                                            if (mysql_error()) {echo "<span class='error'><b>Error: </b>".mysql_error().":</span><br>".$sentenciaBorrar."<br>";}
                                            $borrarSiempre = false;
                                        }

                                        $resultado = SQL::correrConsulta($sentenciaCrear);
                                        if (mysql_error()) {echo "<span class='error'><b>Error: </b>".mysql_error().":</span><br>".$sentenciaCrear."<br>";}
                                        $resultado = SQL::correrConsulta($sentenciaAI);
                                    }
                                    unset($llavesForaneas, $llavesUnicas, $llavesPrimarias);
                                }

                                /*** Insertar datos iniciales ***/
                                if (!empty($registros)) {
                                    foreach($registros as $nombreTabla => $arregloA){
                                        $listaCampos = array();
                                        $listaValores = array();
                                        foreach($arregloA as $arregloB => $campos){
                                            foreach($campos as $campo => $valor){
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
                                            $resultado         = SQL::correrConsulta($sentenciaInsertar);
                                            if (mysql_error()) {echo "<span class='error'><b>Error: </b>".mysql_error().":</span><br>".$sentenciaInsertar."<br>";}
                                            unset($listaCampos, $listaValores);
                                            //$resultado = SQL::correrConsulta("/!40101 SET SQL_MODE=@OLD_SQL_MODE /");
                                        }
                                    }
                                }

                                $resultado = SQL::correrConsulta("/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */");
                                $resultado = SQL::correrConsulta("/*!40101 SET SQL_MODE=@OLD_SQL_MODE */");
                                $resultado = SQL::correrConsulta("/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */");
                                $resultado = SQL::correrConsulta("/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */");
                                $resultado = SQL::correrConsulta("/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */");

                            }
                        }
                    }
                }
            }
        }
    }
}

closedir($listaComponentes);
closedir($listaModulos);

SQL::cerrarConexion();

?>
</pre>

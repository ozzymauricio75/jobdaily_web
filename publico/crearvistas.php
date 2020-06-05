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
                    unset($vistas);

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

                                if (!empty($vistas)){
                                    foreach($vistas as $total_vistas){
                                        foreach($total_vistas as $query){

                                            $query = utf8_decode($query);
                                            $resultado = SQL::correrConsulta("$query");
                                            if (mysql_error()) {
                                                echo "<span class='error'><b>Error: </b>".mysql_error().":\n</span><br>".$query."<br>\n".$esquema."\n";
                                            }
                                        }
                                    }
                                }
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

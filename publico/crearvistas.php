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
* modificarlo  bajo los t�rminos de la Licencia P�blica General GNU
* publicada por la Fundaci�n para el Software Libre, ya sea la versi�n 3
* de la Licencia, o (a su elecci�n) cualquier versi�n posterior.
*
* Este programa se distribuye con la esperanza de que sea �til, pero
* SIN GARANT�A ALGUNA; ni siquiera la garant�a impl�cita MERCANTIL o
* de APTITUD PARA UN PROP�SITO DETERMINADO. Consulte los detalles de
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

$rutaModulos    = realpath($rutasGlobales["modulos"]);
$archivoEsquema = $archivosGlobales["esquemaSQL"];
$prefijoTabla   = SQL::$prefijoTabla;

/*** Obtener el contenido de la carpeta de m�dulos ***/
if ($listaModulos = opendir($rutaModulos)) {

    /*** Obtener lista de carpetas de m�dulos ***/
    while (false !== ($modulo = readdir($listaModulos))) {

        /*** Procesar carpetas de m�dulos ***/
        if ($modulo != "." && $modulo != "..") {
            $modulo = "$rutaModulos/$modulo";

            /*** Obtener el contenido de la carpeta del m�dulo actual ***/
            if ($listaComponentes = opendir($modulo)) {

                /*** Obtener lista de carpetas de componentes del m�dulo actual ***/
                while (false !== ($componente = readdir($listaComponentes))) {
                    unset($vistas);

                    /*** Procesar carpetas de componentes ***/
                    if ($componente != "." && $componente != "..") {
                        $componente = "$modulo/$componente";

                        /*** Procesar s�lo si se trata de un directorio ***/
                        if (is_dir($componente)) {
                            $esquema = "$componente/$archivoEsquema";
                            require_once "$componente/idiomas/".$datosGlobales["idioma"].".php";

                            /*** Buscar el archivo de definici�n del esquema SQL ***/
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

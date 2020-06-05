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

class Plantilla {
    public static $codificacion;
    public static $titulo;
    public static $autor;
    public static $generador;
    public static $descripcion;
    public static $contenido;
    public static $pie;

    /*** Inicializar el objeto ***/
    public static function iniciar($tituloComponente = true) {
        global $sem;
        global $plantillaGlobal;
        global $componente;
        global $textos;

        if (!empty($sem["nombre"]) && !empty($sem["version"])) {
            $generador = $sem["nombre"]." ".$sem["version"];
            $titulo    = $generador." :: ";
        } else {
            $generador = "";
            $titulo    = "";
        }

        if (!empty($sem["creador"]) && !empty($sem["correoCreador"])) {
            $autor = $sem["creador"]." :: ".$sem["correoCreador"];
        } else {
            $autor = "";
        }

        if (!empty($sem["descripcion"])) {
            $descripcion = $sem["descripcion"];
        } else {
            $descripcion = "";
        }

        if (!empty($sem["creador"]) && !empty($sem["urlCreador"])) {
            $pie  = $sem["nombre"]." :: ".$sem["descripcion"];
            $pie .= "<br>&copy; ".date("Y")." ".HTML::enlazarPagina($sem["creador"], $sem["urlCreador"]);
        } else {
            $pie = "";
        }

        self::$codificacion = $plantillaGlobal["codificacion"];
        self::$titulo       = $titulo.$componente->nombre;
        self::$generador    = $generador;
        self::$autor        = $autor;
        self::$descripcion  = $descripcion;
        self::$pie          = $pie;
        self::cargar($tituloComponente);
        self::insertarJavaScript();
    }

    /*** Cargar el archivo de plantilla HTML definido ***/
    private static function cargar($tituloComponente = true) {
        global $archivosGlobales;
        global $imagenesGlobales;
        global $plantillaGlobal;
        global $componente;
        global $textos;
        global $sem;
        global $sesion_usuario;

        if (file_exists($plantillaGlobal["ruta"]) && is_file($plantillaGlobal["ruta"]) && is_readable($plantillaGlobal["ruta"])) {
            $contenido = file_get_contents($plantillaGlobal["ruta"]);
            self::$contenido = $contenido;
        }

        /*** Reemplazar elementos básicos en la plantilla por sus valores correspondientes ***/
        self::$contenido = str_replace("{%codificacion}", self::$codificacion, self::$contenido);
        self::$contenido = str_replace("{%titulo}", self::$titulo, self::$contenido);
        self::$contenido = str_replace("{%autor}", self::$autor, self::$contenido);
        self::$contenido = str_replace("{%generador}", self::$generador, self::$contenido);
        self::$contenido = str_replace("{%cssGeneral}", $archivosGlobales["cssGeneral"], self::$contenido);
        self::$contenido = str_replace("{%cssExplorer6}", $archivosGlobales["cssExplorer6"], self::$contenido);
        self::$contenido = str_replace("{%cssExplorer7}", $archivosGlobales["cssExplorer7"], self::$contenido);
        self::$contenido = str_replace("{%descripcion}", self::$descripcion, self::$contenido);
        self::$contenido = str_replace("{%usuario_ingreso}", $sesion_usuario, self::$contenido);

        $URLBase = HTTP::generarURL($componente->id);
        $URLBase = HTML::campoOculto("URLBase", $URLBase);

        if ($tituloComponente) {
            self::$contenido = str_replace("{%componente}", $URLBase.$componente->nombre, self::$contenido);
        } else {
            self::$contenido = str_replace("{%componente}", $URLBase, self::$contenido);
        }

        self::$contenido = str_replace("{%pie}", self::$pie, self::$contenido);
        self::$contenido = str_replace("{%logoCliente}", HTML::imagen($imagenesGlobales["logoCliente"]), self::$contenido);
        self::$contenido = str_replace("{%logoAplicacion}", HTML::imagen($imagenesGlobales["logoAplicacion"]), self::$contenido);
    }

    /*** Insertar las etiquetas para la inclusión de archivos de JavaScript y el código JavaScript del componente actual ***/
    private static function insertarJavascript() {
        global $rutasJavaScript, $rutasComponente, $componente;

        if (is_array($rutasJavaScript) && count($rutasJavaScript) > 0) {
            reset($rutasJavaScript);
            $contenido = "";

            foreach ($rutasJavaScript as $archivo => $ruta) {

                $contenido .= "  <script type=\"text/javascript\" src=\"".$rutasJavaScript[$archivo]."\"></script>\n";
            }

        } else {
            $contenido = "";
        }

        $archivoJavaScript = $componente->carpeta."/".$rutasComponente["javascript"]."/".$componente->archivo.".js";

        /*** Incluir contenido del archivo JavaScript para el componente si existe ***/
        if (file_exists($archivoJavaScript) && is_file($archivoJavaScript) && is_readable($archivoJavaScript)) {
            $contenido .= "  <script type=\"text/javascript\">\n";
            $contenido .= file_get_contents($archivoJavaScript);
            $contenido .= "\n  </script>\n";
        }

        self::$contenido = str_replace("{%javascript}", $contenido, self::$contenido);
    }

    /*** Sustituir una etiqueta de la plantilla por su contenido correspondiente ***/
    public static function sustituir($etiqueta, $contenido = "") {
        $etiqueta        = "{%$etiqueta}";
        self::$contenido = str_replace($etiqueta, $contenido, self::$contenido);
    }

    /*** Enviar el código generado al cliente ***/
    public static function enviarCodigo() {
        if (!empty(self::$contenido)) {
            echo self::$contenido;
        }
    }
}
?>

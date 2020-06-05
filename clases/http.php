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

class HTTP {
    public static $cliente;
    public static $proxy;
    public static $componenteInicial;
    public static $variableComponente;

    /*** Inicializar la clase ***/
    public static function iniciar() {
        global $datosGlobales;

        date_default_timezone_set($datosGlobales["zonaHorario"]);
        self::$componenteInicial  = $datosGlobales["componenteInicioSesion"];
        self::$variableComponente = $datosGlobales["variableComponente"];

        if (empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            self::$cliente = $_SERVER["REMOTE_ADDR"];
            self::$proxy = "";
        } else {
            self::$cliente = $_SERVER["HTTP_X_FORWARDED_FOR"];
            self::$proxy   = $_SERVER["REMOTE_ADDR"];
        }
    }

    /* Enviar c�digo necesario para que la pagina no sea almacenada en cach� por el cliente o por un servidor proxy ***/
    public static function evitarCache() {
        header("Expires: ".date("D, d M Y H:i:s", 0)." GMT");
        header('Expires-Active: On');
        header("Last-Modified: ".date("D, d M Y H:i:s")." GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", FALSE);
        header("Pragma: no-cache");
    }

    /*** Codificar una cadena o arreglo de cadenas para enviar en formato JSON ***/
    public static function exportarVariables() {

        if (isset($_POST)) {

            foreach ($_POST as $variable => $valor) {

                if (!get_magic_quotes_gpc()) {
                    if (!is_array($valor)) {
                        $valor = addslashes($valor);
                    }
                }

                $nombre  = "forma_$variable";
                global $$nombre;
                $$nombre = $valor;
            }
        }

        if (isset($_GET)) {

            foreach ($_GET as $variable => $valor) {

                if (!get_magic_quotes_gpc()) {
                    if (!is_array($valor)) {
                        $valor = addslashes($valor);
                    }
                }

                $nombre  = "url_$variable";
                global $$nombre;
                $$nombre = $valor;
            }
        }

        if (isset($_COOKIES)) {

            foreach ($_COOKIES as $variable => $valor) {

                if (!get_magic_quotes_gpc()) {
                    if (!is_array($valor)) {
                        $valor = addslashes($valor);
                    }
                }

                $nombre  = "cookie_$variable";
                global $$nombre;
                $$nombre = $valor;
            }
        }

    }
    /*** Generar URL para abrir un componente ***/
    public static function generarURL($componente) {
        $URL = $_SERVER["PHP_SELF"]."?".self::$variableComponente."=".$componente;
        return $URL;
    }

    /*** Codificar una cadena o arreglo de cadenas para enviar en formato JSON ***/
    public static function enviarJSON($datos) {
        if (is_array($datos)) {
            $datos = array_map("utf8_encode",$datos);
        } else {
            $datos = utf8_encode($datos);
        }

        echo json_encode($datos);
    }
}
?>

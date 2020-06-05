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

class Sesion {
    public static $id;

    /*** Iniciar la sesi�n ***/
    public static function iniciar() {
        session_set_save_handler(
            array(__CLASS__,"abrir"),
            array(__CLASS__,"cerrar"),
            array(__CLASS__,"leer"),
            array(__CLASS__,"escribir"),
            array(__CLASS__,"terminar"),
            array(__CLASS__,"limpiar")
        );

        Sesion::limpiar();

        if (self::$id == "") {
            session_start();
        }

        self::$id = session_id();
        $id = 1234567899;

        foreach ($_SESSION as $variable => $valor) {
            $nombre  = "sesion_".$variable;
            global $$nombre;
            $$nombre = $valor;
        }
    }

    /*** Finalizar la sesi�n ***/
    public static function terminar() {
        self::destruir(self::$id);
    }

    /*** Abrir una sesi�n ***/
    public static function abrir() {
        return TRUE;
    }

    /*** Cerrar una sesi�n ***/
    public static function cerrar() {
        return TRUE;
    }

    /*** Registrar una variable en la sesi�n ***/
    public static function registrar($variable, $valor = "") {
        global $$variable;

        if (isset($valor)) {
            $$variable = $valor;
        }

        $nombre = "sesion_".$variable;

        if (isset($$variable)) {
            global $$nombre;

            $$nombre               = $$variable;
            $_SESSION["$variable"] = $$variable;
        } else {
            $_SESSION["$variable"] = NULL;
        }
    }

    /*** Eliminar una variable de sesi�n ***/
    public static function borrar($variable) {
        $nombre = "sesion_".$variable;

        global $$nombre;

        if (isset($$nombre)) {
            unset($$nombre);
            unset($_SESSION["$variable"]);
        }
    }

    /*** Leer los datos una sesi�n ***/
    public static function leer($id) {
        $fecha     = time();
        $columnas  = array("contenido");
        $tablas    = array("sesiones");
        $resultado = SQL::seleccionar($tablas, $columnas, "id = '$id' AND expiracion > '$fecha'");

        if (SQL::filasDevueltas($resultado)) {
            $datos = SQL::filaEnObjeto($resultado);
            return $datos->contenido;

        } else {
            return FALSE;
        }
    }

    /*** Escribir los datos de una sesi�n ***/
    public static function escribir($id, $contenido) {
        $contenido  = addslashes($contenido);
        $expiracion = time() + get_cfg_var("session.gc_maxlifetime");
        $datos      = array("id" => "$id", "expiracion" => "$expiracion", "contenido"  => "$contenido");
        $resultado  = SQL::reemplazar("sesiones", $datos);
        return $resultado;
    }

    /*** Destruir una sesi�n ***/
    public static function destruir($id) {
        $resultado  = SQL::eliminar("sesiones","id='$id'");
        session_unset();
        return $resultado;
    }

    /*** Eliminar las sesiones expiradas ***/
    public static function limpiar() {
/*        $fecha      = time();
        $resultado  = SQL::eliminar("sesiones","expiracion < '$fecha'");
        return $resultado;*/
    }
}

?>

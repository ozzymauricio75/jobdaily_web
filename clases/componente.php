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

class Componente {
    var $id;
    var $URL;
    var $padre;
    var $carpeta;
    var $archivo;
    var $global;
    var $visible;
    var $valido;
    var $existeArchivo;
    var $archivoLegible;
    var $ruta;
    var $nombre;

    /*** Inicializar el objeto ***/
    function __construct($componente) {
        global $rutasGlobales, $rutasComponente, $datosGlobales, $textos;

        $this->id  = $componente;
        $tablas    = array("c" => "componentes",
                           "m" => "modulos");
        $columnas  = array("modulo"     => "c.id_modulo",
                           "padre"      => "c.padre",
                           "carpeta"    => "m.carpeta",
                           "subcarpeta" => "c.carpeta",
                           "archivo"    => "c.archivo",
                           "global"     => "c.global",
                           "visible"    => "c.visible");
        $condicion = "m.id = c.id_modulo AND c.id = '$componente'";
        $resultado = SQL::seleccionar($tablas, $columnas, $condicion);

        if (SQL::filasDevueltas($resultado)) {
            $datos            = SQL::filaEnObjeto($resultado);
            $this->valido     = TRUE;
            $this->URL        = HTTP::generarURL($componente);
            $this->padre      = $datos->padre;
            $this->carpeta    = $rutasGlobales["modulos"]."/".$datos->carpeta."/".$datos->subcarpeta;
            $this->archivo    = $datos->archivo;
            $this->global     = $datos->global;
            $this->visible    = $datos->visible;
        } else {
            $this->valido  = FALSE;
        }

        /*** Verificar que el componente tenga definidos carpeta y archivo en la tabla ***/
        if (!empty($this->carpeta) && !empty($this->archivo)) {
            $this->ruta = $this->carpeta."/".$this->archivo.".php";

            /*** Verificar que el archivo asociado al componente exista ***/
            if (file_exists($this->ruta) && is_file($this->ruta)) {
                $this->existeArchivo = TRUE;

                /*** Verificar que el archivo asociado al componente tenga permisos de lectura ***/
                if (is_readable($this->ruta)) {
                    $this->archivoLegible = TRUE;
                }
            }
        }

        /*** Cargar los textos del componente actual para el idioma establecido y hacerlos globales ***/
        $textos = $this->cargarTextos();

        if (isset($textos[$this->id])) {
            $this->nombre = $textos[$this->id];
        } else {
            $this->nombre = $this->id;
        }

    }

    /*** Verificar que un usuario tenga acceso al componente ***/
    function usuarioPermitido($componente = "") {
        global $datosGlobales, $sesion_usuario, $sesion_perfil;

        if (empty($componente)) {
            $componente = $this;
        }
        //echo var_dump($sesion_perfil);
        if (($sesion_usuario == $datosGlobales["usuarioMaestro"]) || ($componente->global)) {
            return TRUE;
        } elseif (SQL::existeItem("componentes_usuario","id_perfil",$sesion_perfil,"id_componente = '".$componente->id."'")) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /*** Cargar los textos del archivo de idiomas correspondiente al componente ***/
    private function cargarTextos() {
        global $rutasGlobales, $rutasComponente, $datosGlobales;

        $textosGlobales   = array();
        $textosComponente = array();

        $archivoTextosGlobales = $rutasGlobales["idiomas"]."/".$datosGlobales["idioma"].".php";

        /*** Verificar que el archivo asociado al componente exista ***/
        if (file_exists($archivoTextosGlobales) && is_file($archivoTextosGlobales) && is_readable($archivoTextosGlobales)) {
            require $archivoTextosGlobales;

            /*** Verificar que se haya definido el arreglo con los textos ***/
            if (isset($textos)) {
                $textosGlobales = $textos;
            }
        }

        $archivoTextosComponente = $this->carpeta."/".$rutasComponente["idiomas"]."/".$datosGlobales["idioma"].".php";

        /*** Verificar que el archivo asociado al componente exista ***/
        if (file_exists($archivoTextosComponente) && is_file($archivoTextosComponente) && is_readable($archivoTextosComponente)) {
            require $archivoTextosComponente;

            /*** Verificar que se haya definido el arreglo con los textos ***/
            if (isset($textos)) {
                $textosComponente = $textos;
            }
        }

        $textos = $textosGlobales + $textosComponente;

        return $textos;
    }

}
?>

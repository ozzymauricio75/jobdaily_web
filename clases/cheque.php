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

class Cheque {
    var $variables;
    var $columnas;
    var $filas;

    /*** Inicializar el objeto ***/
    function __construct() {

        $this->columnas         = 78;
        $this->filas            = 34;
        $this->variables        = array(
            "ANO"               => "",
            "MES"               => "",
            "DIA"               => "",
            "VALOR"             => "",
            "BENEFICIARIO"      => "",
            "VALOR_LETRAS"      => "",
            "CODIGO"            => "",
            "DESCRIPCION"       => "",
            "VALOR_CONCEPTO"    => "",
            "FILAS_CONCEPTOS"   => "",
            "ELABORA"           => "",
            "AUTORIZA"          => ""
        );

    }

    public static function reemplazar($plantilla, $reemplazos) {

        preg_match_all("/([A-Z\_]{2,20}\=[0-9]{1,4})[^0-9]/", $plantilla, $claves);

        foreach ($claves[1] as $indice => $clave) {

            $datos = explode("=", $clave);
            if ( isset($reemplazos[$datos[0]]) ) {

                $plantilla = str_replace($clave, $reemplazos[$datos[0]], $plantilla);
            }
        }
    }

}
?>
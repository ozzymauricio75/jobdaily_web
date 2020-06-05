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
<?php

/**
*
* Copyright (C) 2020 Jobdaily
* Raul Mauricio Oidor Lozano <ozzymauricio75@gmail.com>
*
* Este archivo es parte de:
* Jobdaily :: Software empresarial a la medida
*
* Este programa es software libre: usted puede redistribuirlo y/o
* modificarlo  bajo los terminos de la Licencia Publica General GNU
* publicada por la Fundacion para el Software Libre, ya sea la versi�n 3
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

/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {

    /*** Verificar que se haya enviado el ID del elemento a consultar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_CONSULTAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {
        $vistaConsulta = "tipos_contrato";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo = '$url_id'");
        $datos         = SQL::filaEnObjeto($consulta);
        $error         = "";
        $titulo        = $componente->nombre;

        if($datos->termino_contrato == 1){
            $termino_contrato = $textos["TERMINO_FIJO"];
        }else if($datos->termino_contrato == 2){
            $termino_contrato = $textos["TERMINO_INDEFINIDO"];
        }else if($datos->termino_contrato == 3){
            $termino_contrato = $textos["SIN_RELACION_LABORAL"];
        }else if($datos->termino_contrato == 4){
            $termino_contrato = $textos["OBRA_LABOR"];
        }


        if($datos->tipo_contratacion == 1){
            $tipo_contratacion = $textos["INTEGRAL"];
        }else if($datos->tipo_contratacion == 2){
            $tipo_contratacion = $textos["DESTAJO"];
        }else if($datos->tipo_contratacion == 3){
            $tipo_contratacion = $textos["PRACTICANTE"];
        }else if($datos->tipo_contratacion == 4){
            $tipo_contratacion = $textos["PASANTIAS"];
        }else if($datos->tipo_contratacion == 5){
            $tipo_contratacion = $textos["PRESTACION_SERVICIOS"];
        }else if($datos->tipo_contratacion == 6){
            $tipo_contratacion = $textos["COOPERATIVA_TRABAJO_ASOCIADO"];
        }else if($datos->tipo_contratacion == 7){
            $tipo_contratacion = $textos["BASICO_MENOR_MINIMO"];
        }else if($datos->tipo_contratacion == 8){
            $tipo_contratacion = $textos["BASICO_MAYOR_MINIMO"];
        }else if($datos->tipo_contratacion == 9){
            $tipo_contratacion = $textos["COMISION_CON_BASICO"];
        }else if($datos->tipo_contratacion == 10){
            $tipo_contratacion = $textos["COMISION_SIN_BASICO"];
        }

        if($datos->sueldo_ajusta_minimo == 1){
            $ajusta_minimo = $textos["AJUSTA_MINIMO_SI"];
        }else{
            $ajusta_minimo = $textos["AJUSTA_MINIMO_NO"];
        }


        /*** Definici�n de pesta�a personal ***/
         $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::mostrarDato("descripcion", $textos["DESCRIPCION_TIPO_CONTRATO"], $datos->descripcion)
            ),
            array(
                HTML::mostrarDato("termino_contrato", $textos["TERMINO_CONTRATO"], $termino_contrato)
            ),
            array(
                HTML::mostrarDato("tipo_contratacion", $textos["TIPO_CONTRATACION"], $tipo_contratacion)
            ),
            array(
                HTML::mostrarDato("se_ajusta_minimo", $textos["AJUSTA_MINIMO"], $ajusta_minimo)
            ),
        );

        $contenido = HTML::generarPestanas($formularios);
    }

    /*** Enviar datos para la generaci�n del formulario al script que origin� la petici�n ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);
}
?>

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
* de APTITUD PARA UN PROPÓITO DETERMINADO. Consulte los detalles de
* la Licencia Pública General GNU para obtener una información más
* detallada.
*
* Debería haber recibido una copia de la Licencia Pública General GNU
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
        $vistaConsulta = "tipos_documentos";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo = '$url_id'");
        $datos         = SQL::filaEnObjeto($consulta);
        $error         = "";
        $titulo        = $componente->nombre;
        $manejo = array(
            "1" => $textos["NO_MANEJA"],
            "2" => $textos["MANEJO_AUTOMATICO"],
            "3" => $textos["CONSECUTIVO_MES"]
        );
        $control = array(
            "0" => $textos["NO_IMPRIME"],
            "1" => $textos["IMPRIME"],
        );

        $comprobante = SQL::obtenerValor("tipos_comprobantes","descripcion","codigo = '$datos->codigo_comprobante'");

        if($datos->sentido_contable == "0"){
            $texto_contable = $textos["NO_APLICA"];

        }else if($datos->sentido_contable == "1"){
            $texto_contable = $textos["DEBITO"];

        }else{
            $texto_contable = $textos["CREDITO"];
        }

        if($datos->sentido_inventario == "0"){
            $texto_inventario = $textos["NO_APLICA"];

        }else if($datos->sentido_inventario == "1"){
            $texto_inventario = $textos["ENTRADA"];

        }else{
            $texto_inventario = $textos["SALIDA"];
        }

        if($datos->aplica_notas == "1"){
            $texto_aplica_notas = $textos["APLICA"];
        }else{
            $texto_aplica_notas = $textos["NO_APLICA"];
        }

        /*** Definición de pestañas ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::mostrarDato("codigo_comprobante", $textos["COMPROBANTE"], $comprobante)
            ),
            array(
                HTML::mostrarDato("codigo", $textos["CODIGO"], $datos->codigo)
            ),
            array(
                HTML::mostrarDato("descripcion", $textos["DESCRIPCION"], $datos->descripcion)
            ),
            array(
                HTML::mostrarDato("observaciones", $textos["OBSERVACIONES"], $datos->observaciones)
            ),
            array(
                HTML::mostrarDato("abreviaturas", $textos["ABREVIATURAS"], $datos->abreviaturas),
                HTML::mostrarDato("tipo", $textos["TIPO"], $datos->tipo)
            ),
            array(
                HTML::mostrarDato("*sentido_contable", $textos["SENTIDO_CONTABLE"], $texto_contable),
                HTML::mostrarDato("*sentido_inventario", $textos["SENTIDO_INVENTARIO"], $texto_inventario),
                HTML::mostrarDato("*aplica_notas", $textos["APLICA_NOTAS"], $texto_aplica_notas)
            ),
            array(
                HTML::mostrarDato("manejo_automatico", $textos["MANEJO_AUTOMATICO"], $manejo[$datos->manejo_automatico]),
                HTML::mostrarDato("controL_titulo", $textos["CONTROL_TITULO"], $control[$datos->control_titulo])
            )
        );
        if ($datos->equivalencia != ""){
            $arreglo[] = array(
                HTML::mostrarDato("equivalecia", $textos["EQUIVALENCIA"], $datos->equivalencia)
            );

            $formularios["PESTANA_GENERAL"] = array_merge($formularios["PESTANA_GENERAL"],$arreglo);
        }
        $contenido = HTML::generarPestanas($formularios);
    }

    /*** Enviar datos para la generación del formulario al script que originá la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);
}
?>

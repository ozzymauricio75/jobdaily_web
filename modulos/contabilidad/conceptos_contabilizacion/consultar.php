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
* modificarlo  bajo los terminos de la Licencia Publica General GNU
* publicada por la Fundacion para el Software Libre, ya sea la version 3
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

// Generar el formulario para la captura de datos
if (!empty($url_generar)) {

    // Verificar que se haya enviado el ID del elemento a consultar
    if (empty($url_id)) {
        $error     = $textos["ERROR_CONSULTAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {

            // Obtener valores de las tablas
            $vistaConsulta = "conceptos_contabilizacion_compras";
            $columnas      = SQL::obtenerColumnas($vistaConsulta);
            $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo = '".$url_id."'");
            $datos         = SQL::filaEnObjeto($consulta);
            $error         = "";
            $titulo        = $componente->nombre;

            $nombre_regimen_empresa = $textos["REGIMEN_".$datos->regimen_ventas_empresa];

            $nombre_regimen_persona = $textos["REGIMEN_".$datos->regimen_persona];

            $nombre_tipo_compra     = SQL::obtenerValor("tipos_compra", "descripcion", "codigo = '".$datos->codigo_tipo_compra."'");

            $nombre_tipo_tasa_iva   = SQL::obtenerValor("tasas", "descripcion", "codigo = '".$datos-> codigo_tasa_iva."'");

            $cuenta_1 = SQL::obtenerValor("seleccion_plan_contable", "SUBSTRING_INDEX(codigo_contable,'|',1)", "id = '".$datos->codigo_contable_compras."'");
            $cuenta_2 = SQL::obtenerValor("seleccion_plan_contable", "SUBSTRING_INDEX(codigo_contable,'|',1)", "id = '".$datos->codigo_contable_iva."'");
            $cuenta_3 = SQL::obtenerValor("seleccion_plan_contable", "SUBSTRING_INDEX(codigo_contable,'|',1)", "id = '".$datos->codigo_contable_iva_debito."'");
            $cuenta_4 = SQL::obtenerValor("seleccion_plan_contable", "SUBSTRING_INDEX(codigo_contable,'|',1)", "id = '".$datos->codigo_contable_iva_credito."'");
            $cuenta_5 = SQL::obtenerValor("seleccion_plan_contable", "SUBSTRING_INDEX(codigo_contable,'|',1)", "id = '".$datos->codigo_contable_compras_uvt."'");
            $cuenta_6 = SQL::obtenerValor("seleccion_plan_contable", "SUBSTRING_INDEX(codigo_contable,'|',1)", "id = '".$datos->codigo_contable_iva_uvt."'");

            // Definicion de pestanas
            $pestana_general    = array();
            $pestana_general[]  = array( HTML::mostrarDato("descripcion", $textos["DESCRIPCION"], $datos->descripcion) );
            $pestana_general[]  = array( HTML::mostrarDato("regimen_empresa", $textos["REGIMEN_EMPRESA"], $nombre_regimen_empresa),
                                         HTML::mostrarDato("regimen_persona", $textos["REGIMEN_PERSONA"], $nombre_regimen_persona));
            $pestana_general[]  = array( HTML::mostrarDato("tipo_compra", $textos["TIPO_COMPRA"], $nombre_tipo_compra),
                                         HTML::mostrarDato("tipo_tasa_iva", $textos["TASA_IVA"], $nombre_tipo_tasa_iva));
            $pestana_general[]  = array( HTML::mostrarDato("selector1", $textos["CODIGO_COMPRAS"], $cuenta_1));

            if ($datos->regimen_persona == '1') {
                $pestana_general[]  = array( HTML::mostrarDato("selector2", $textos["CODIGO_IVA"], $cuenta_2));
                $pestana_general[]  = array( HTML::mostrarDato("selector3", $textos["CODIGO_IVA_DEBITO"], $cuenta_3));
                $pestana_general[]  = array( HTML::mostrarDato("selector4", $textos["CODIGO_IVA_CREDITO"], $cuenta_4));
            }

            $pestana_general[]  = array( HTML::mostrarDato("selector5", $textos["CODIGO_COMPRAS_UVT"], $cuenta_5));
            if ($datos->regimen_persona == '1') {
                $pestana_general[]  = array( HTML::mostrarDato("selector6", $textos["CODIGO_IVA_UVT"], $cuenta_6));
            }

            $formularios["PESTANA_GENERAL"] = $pestana_general;

            $contenido = HTML::generarPestanas($formularios);
    }
    // Enviar datos para la generacion del formulario al script que origino la peticion
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);
}
?>

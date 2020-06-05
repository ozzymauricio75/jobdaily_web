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
    if(empty($url_id)) {
        $error     = $textos["ERROR_ELIMINAR_VACIO"];
        $titulo    = "";
        $contenido = "";
    } else {
        // Obtener valores de las tablas
        $vistaConsulta = "conceptos_devolucion_compras";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo = '".$url_id."'");
        $datos         = SQL::filaEnObjeto($consulta);
        $error         = "";
        $titulo        = $componente->nombre;

        if($datos->regimen_ventas_empresa == '1'){
            $nombre_regimen_empresa = $textos["REGIMEN_COMUN"];
        }else{
            $nombre_regimen_empresa = $textos["REGIMEN_SIMPLIFICADO"];
        }

        if($datos->regimen_persona == '1'){
            $nombre_regimen_persona = $textos["REGIMEN_COMUN"];
        }else{
            $nombre_regimen_persona = $textos["REGIMEN_SIMPLIFICADO"];
        }

        $nombre_tipo_compra   = SQL::obtenerValor("tipos_devoluciones_compra", "descripcion", "codigo = '".$datos->codigo_tipo_devolucion."'");
        $nombre_tipo_tasa_iva = SQL::obtenerValor("tasas", "descripcion", "codigo = '".$datos->codigo_tasa_iva."'");
        $nombre_codigo_1      = SQL::obtenerValor("seleccion_plan_contable", "SUBSTRING_INDEX(codigo_contable,'|',1)", "id = '".$datos->codigo_contable_compras."'");

        // Definicion de pestanas
        $pestana = array();

        $pestana[] = array(
                        HTML::mostrarDato("codigo", $textos["CODIGO"], $datos->codigo),
                        HTML::mostrarDato("descripcion", $textos["DESCRIPCION"], $datos->descripcion)
                    );

        $pestana[] = array(
                        HTML::mostrarDato("regimen_empresa", $textos["REGIMEN_EMPRESA"], $nombre_regimen_empresa),
                        HTML::mostrarDato("regimen_persona", $textos["REGIMEN_PERSONA"], $nombre_regimen_persona)
                    );

        $pestana[] = array(
                        HTML::mostrarDato("tipo_compra", $textos["TIPO_DEVOLUCION"], $nombre_tipo_compra),
                        HTML::mostrarDato("tipo_tasa_iva", $textos["TASA_IVA"], $nombre_tipo_tasa_iva)
                    );

        $pestana[] = array(HTML::mostrarDato("selector1", $textos["CODIGO_COMPRAS"], $nombre_codigo_1));

        if($datos->regimen_persona == '1'){
            $nombre_codigo_2 = SQL::obtenerValor("seleccion_plan_contable", "SUBSTRING_INDEX(codigo_contable,'|',1)", "id = '".$datos->codigo_contable_iva."'");
            $pestana[]       = array(HTML::mostrarDato("selector2", $textos["CODIGO_IVA"], $nombre_codigo_2));
        }

        $formularios["PESTANA_GENERAL"] = $pestana;

        // Definicion de botones
        $botones   = array(HTML::boton("botonAceptar", $textos["ACEPTAR"], "eliminarItem('".$url_id."');", "aceptar"));
        $contenido = HTML::generarPestanas($formularios, $botones);
    }
    // Enviar datos para la generacion del formulario al script que origino la peticion
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

// Eliminar el elemento seleccionado
} elseif (!empty($forma_procesar)) {

    $consulta = SQL::eliminar("conceptos_devolucion_compras", "codigo = '".$forma_id."'");

    if ($consulta) {
        $error   = false;
        $mensaje = $textos["ITEM_ELIMINADO"];
    } else {
        $error   = true;
        $mensaje = $textos["ERROR_ELIMINAR_ITEM"];
    }

    // Enviar datos con la respuesta del proceso al script que origino la peticion
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

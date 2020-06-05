<?php

/**
*
* Copyright (C) 2020 Jobdaily
* Walter Andrés Márquez Gutiérrez <walteramg@gmail.com>
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

    /*** Verificar que se haya enviado el ID del elemento a modificar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_MODIFICAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {
        $vistaConsulta = "buscador_vigencia_tasas";
        $condicion     = "id = '$url_id'";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, $condicion);
        $datos         = SQL::filaEnObjeto($consulta);
        $error         = "";
        $titulo        = $componente->nombre;

        $url_id_buscador = explode("|",$url_id);
        $condicion       = "codigo = '$url_id_buscador[0]'";

        /*** Definición de pestañas ***/
        $formularios["PESTANA_GENERAL"] = array(
        	array(
        		HTML::mostrarDato("*tasa", $textos["TASA"], SQL::obtenerValor("tasas", "descripcion", $condicion)),
        		HTML::campoOculto("codigo_tasa", $url_id_buscador)
        	),
            array(
                HTML::campoTextoCorto("*fecha", $textos["FECHA"], 10, 10, $datos->fecha, array("class" => "fechaNuevas", "title" => $textos["AYUDA_FECHA"]))
            ),
            array(
                HTML::campoTextoCorto("porcentaje", $textos["PORCENTAJE"], 5, 5, $datos->porcentaje, array("title" => $textos["AYUDA_PORCENTAJE"], "onKeyPress" => "return campoDecimal(event)")),
                HTML::campoTextoCorto("valor_base", $textos["VALOR_BASE"], 10, 10, $datos->valor_base, array("title" => $textos["AYUDA_VALOR"], "onKeyPress" => "return campoDecimal(event)"))
            )
        );

        /*** Definición de botones ***/
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "modificarItem('$url_id');", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones);
    }

    /*** Enviar datos para la generación del formulario al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

/*** Validar los datos provenientes del formulario ***/
} elseif (!empty($forma_procesar)) {
    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_MODIFICADO"];

    if(empty($forma_fecha)){
        $error   = true;
        $mensaje = $textos["FECHA_VACIO"];
    }else {
		/*** Insertar datos ***/
        $llave_principal = explode("|",$forma_id);
        $codigo_tasa = $llave_principal[0];
        $fecha       = $llave_principal[1];
        
        if (SQL::obtenerValor("vigencia_tasas","fecha","codigo_tasa='$codigo_tasa' AND fecha='$fecha'")){
            $error   = true;
            $mensaje = $textos["EXISTE_VIGENCIA"];
        } else {
            $datos = array(
                "codigo_tasa"   => $codigo_tasa,
                "fecha"         => $forma_fecha,
                "porcentaje"    => $forma_porcentaje,
                "valor_base"    => $forma_valor_base
            );
            
            $consulta = SQL::modificar("vigencia_tasas", $datos, "codigo_tasa = '$codigo_tasa' AND fecha = '$fecha'");

            /*** Error inserción ***/
            if ($consulta) {
                $error   = false;
                $mensaje = $textos["ITEM_MODIFICADO"];
            } else {
                $error   = true;
                $mensaje = $textos["ERROR_MODIFICAR_ITEM"];
            }
        }
    }
    /*** Enviar datos con la respuesta del proceso al script que originï¿½ la peticiï¿½n ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

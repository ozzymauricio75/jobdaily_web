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

/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {

    /*** Verificar que se haya enviado el ID del elemento a consultar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_CONSULTAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {
        $vistaConsulta = "usuarios";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo = '$url_id'");
        $datos         = SQL::filaEnObjeto($consulta);

        $error         = "";
        $titulo        = $componente->nombre;

        $consulta_imagen = SQL::seleccionar(array("imagenes"), array("*"), "id_asociado = '$url_id' AND categoria = '1'");
        $imagen          = SQL::filaEnObjeto($consulta_imagen);

        $consulta_firma = SQL::seleccionar(array("imagenes"), array("*"), "id_asociado = '$url_id' AND categoria = '3'");
        $firma_digital = SQL::filaEnObjeto($consulta_firma);

        if (!$datos->fecha_expiracion) {
            $fecha_expiracion = $textos["NO_APLICA"];
        } else {
            $fecha_expiracion = $datos->fecha_expiracion;
        }

        if (!$datos->fecha_cambio_contrasena) {
            $fecha_cambio_contrasena = $textos["NO_APLICA"];
        } else {
            $fecha_cambio_contrasena = $datos->fecha_cambio_contrasena;
        }

        if (!$datos->cambio_contrasena_minimo) {
            $cambio_contrasena_minimo = $textos["NO_APLICA"];
        } else {
            $cambio_contrasena_minimo = $datos->cambio_contrasena_minimo." ".$textos["DIAS"];
        }

        if (!$datos->cambio_contrasena_maximo) {
            $cambio_contrasena_maximo = $textos["NO_APLICA"];
        } else {
            $cambio_contrasena_maximo = $datos->cambio_contrasena_maximo." ".$textos["DIAS"];
        }
        
        if ($datos->codigo > 0){
            $formularios["PESTANA_GENERAL"] = array(
                array(
                    HTML::mostrarDato("codigo", $textos["CODIGO"], $datos->codigo)
                )
            );
        } else {
            $formularios["PESTANA_GENERAL"] = array();
        }

        $contenido_pestaña[] = array(
            HTML::mostrarDato("nombre", $textos["NOMBRE"], $datos->nombre)
        );
        $contenido_pestaña[] = array(
            HTML::mostrarDato("correo", $textos["CORREO"], $datos->correo)
        );
        $contenido_pestaña[] = array(
            HTML::mostrarDato("usuario", $textos["USUARIO"], $datos->usuario)
        );
        /*** Definición de pestañas ***/
        $formularios["PESTANA_GENERAL"] = array_merge($formularios["PESTANA_GENERAL"], $contenido_pestaña);

        $formularios["PESTANA_ACCESO"] = array(
            array(
                HTML::mostrarDato("cambiar_contrasena", $textos["CAMBIAR_CONTRASENA"], $textos["SI_NO_".intval($datos->cambiar_contrasena)])
            ),
            array(
                HTML::mostrarDato("cambio_contrasena_minimo", $textos["CAMBIO_CONTRASENA_MINIMO"], $cambio_contrasena_minimo)
            ),
            array(
                HTML::mostrarDato("cambio_contrasena_maximo", $textos["CAMBIO_CONTRASENA_MAXIMO"], $cambio_contrasena_maximo)
            ),
            array(
                HTML::mostrarDato("fecha_cambio_contrasena", $textos["FECHA_CAMBIO_CONTRASENA"], $fecha_cambio_contrasena)
            ),
            array(
                HTML::mostrarDato("fecha_expiracion", $textos["FECHA_EXPIRACION"], $fecha_expiracion)
            )
        );

        if ($imagen) {
            $id_imagen = $imagen->id_asociado."|".$imagen->categoria;
            $formularios["PESTANA_IMAGEN"] = array(
                array(
                    HTML::imagen(HTTP::generarURL("VISUIMAG")."&id=".$id_imagen, array("width" => $imagen->ancho, "height" => $imagen->alto))
                )
            );
        }

        if ($firma_digital) {
            $id_imagen = $firma_digital->id_asociado."|".$firma_digital->categoria;
            $formularios["PESTANA_FIRMA_DIGITAL"] = array(
                array(
                    HTML::imagen(HTTP::generarURL("VISUIMAG")."&id=".$id_imagen, array("width" => $firma_digital->ancho, "height" => $firma_digital->alto))
                )
            );
        }

        $contenido = HTML::generarPestanas($formularios);
    }

    /*** Enviar datos para la generación del formulario al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);
}
?>

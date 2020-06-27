<?php

/**
*
* Copyright (C) 2020 Raul Mauricio Oidor Lozano
* Raul Mauricio Oidor Lozano <ozzymauricio75@gmail.com>
*
* Este archivo es parte de:
* PANCE :: Software empresarial a la medida
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

    /*** Verificar que se haya enviado el ID del elemento a eliminar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_ELIMINAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {
        $vistaConsulta                  = "terceros";
        $columnas                       = SQL::obtenerColumnas($vistaConsulta);
        $consulta                       = SQL::seleccionar(array($vistaConsulta), $columnas, "documento_identidad = '$url_id'");
        $datos                          = SQL::filaEnObjeto($consulta);
        $error                          = "";
        $titulo                         = $componente->nombre; 
        $documento_identidad            = $datos->documento_identidad;

        $codigo_empresa                 = SQL::obtenerValor("compradores", "codigo_empresa", "documento_identidad = '$url_id'");
        $razon_social                   = SQL::obtenerValor("empresas", "razon_social", "codigo = '$codigo_empresa'");
        
        $regimen = array(
            "1" => $textos["REGIMEN_COMUN"],
            "2" => $textos["REGIMEN_SIMPLIFICADO"]
        );

        $tipo_persona = array(
            "1" => $textos["PERSONA_NATURAL"],
            "2" => $textos["PERSONA_JURIDICA"],
            "3" => $textos["CODIGO_INTERNO"]
        );

        $inicio_cobro = array(
            "1" => $textos["FECHA_FACTURA"],
            "2" => $textos["FECHA_RECIBO"]
        );
        
        $genero = array(
            "M" => $textos["MASCULINO"],
            "F" => $textos["FEMENINO"],
            "N" => $textos["NO_APLICA"],
        );
    
        $activo = array(
            "0" => $textos["INACTIVO"],
            "1" => $textos["ACTIVO"]
        );

        /*** Definición de pestañas ***/
        $formularios["PESTANA_COMPRADOR"] = array(
            array(
                HTML::mostrarDato("empresa", $textos["RAZON_SOCIAL"], $razon_social)
            ),
            array(
                HTML::mostrarDato("documento_identidad", $textos["DOCUMENTO_COMPRADOR"], $datos->documento_identidad)
            ),
            array(
                HTML::mostrarDato("primer_nombre", $textos["PRIMER_NOMBRE"], $datos->primer_nombre),
                HTML::mostrarDato("segundo_nombre", $textos["SEGUNDO_NOMBRE"], $datos->segundo_nombre),
                HTML::mostrarDato("primer_apellido", $textos["PRIMER_APELLIDO"], $datos->primer_apellido),
                HTML::mostrarDato("segundo_apellido", $textos["SEGUNDO_APELLIDO"], $datos->segundo_apellido)
            ),
            array(
                HTML::mostrarDato("correo", $textos["CORREO"], $datos->correo)
            ),
            array(
                HTML::mostrarDato("celular", $textos["CELULAR"], $datos->celular)
            ),
        );

        /*** Definición de botones ***/
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "eliminarItem('$url_id');", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones);
    }

    /*** Enviar datos para la generación del formulario al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

/*** Eliminar el elemento seleccionado ***/
} elseif (!empty($forma_procesar)) {  
    $consulta_comprador = SQL::eliminar("compradores", "documento_identidad = '$forma_id'");
    $consulta           = SQL::eliminar("terceros", "documento_identidad = '$forma_id'");
    
    if ($consulta) {
        $error   = false;
        $mensaje = $textos["ITEM_ELIMINADO"];
    } else {
        $error   = true;
        $mensaje = $textos["ERROR_ELIMINAR_ITEM"];
    }

    /*** Enviar datos con la respuesta del proceso al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

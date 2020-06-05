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

    /*** Verificar que se haya enviado el ID del elemento a eliminar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_ELIMINAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    }else{
        $llave_cuenta   = explode('|',$url_id);
        $vistaConsulta  = "cuentas_bancarias";
        $columnas       = SQL::obtenerColumnas($vistaConsulta);
        $consulta       = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo_sucursal = '".$llave_cuenta[0]."' AND codigo_tipo_documento = '".$llave_cuenta[1]."' AND codigo_sucursal_banco = '".$llave_cuenta[2]."' AND codigo_banco = '".$llave_cuenta[6]."' AND numero = '".$llave_cuenta[7]."'");
        $datos          = SQL::filaEnObjeto($consulta);
        $error          = "";
        $titulo         = $componente->nombre;

        $sucursal       = SQL::obtenerValor("sucursales", "nombre", "codigo = '".$datos->codigo_sucursal."'");
        $tipo_documento = SQL::obtenerValor("tipos_documentos", "descripcion", "codigo = '".$datos->codigo_tipo_documento."'");
        $banco          = SQL::obtenerValor("bancos", "descripcion", "codigo = '".$datos->codigo_banco."'");
        $sucursal_banco = SQL::obtenerValor("sucursales_bancos", "nombre_sucursal", "codigo = '".$datos->codigo_sucursal_banco."' AND codigo_banco = '".$datos->codigo_banco."'");
        $plan_contable  = SQL::obtenerValor("plan_contable", "descripcion", "codigo_contable = '".$datos->codigo_plan_contable."'");

        $auxiliar       = array();
        if($datos->codigo_auxiliar_contable != 0){
            $auxiliar_contable  = SQL::obtenerValor("auxiliares_contables", "descripcion", "codigo_empresa = '".$datos->codigo_empresa_auxiliar."' AND codigo_anexo_contable = '".$datos->codigo_anexo_contable."' AND codigo = '".$datos->codigo_auxiliar_contable."'");
            $auxiliar           = array(
                                        HTML::mostrarDato("auxiliar_contable", $textos["AUXILIAR_CONTABLE"], $auxiliar_contable)
                                    );
        }else{
            $auxiliar = array(
                            HTML::campoOculto("auxiliar_contable", $datos->codigo_auxiliar_contable)
                        );
        }

        $estado = array(
            "0" => $textos["INACTIVA"],
            "1" => $textos["ACTIVA"]
        );

        
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::mostrarDato("sucursal", $textos["SUCURSAL"], $sucursal)
            ),
            array(
                HTML::mostrarDato("tipo_documento", $textos["TIPO_DOCUMENTO"], $tipo_documento)
            ),
            array(
                HTML::mostrarDato("banco", $textos["BANCO"], $banco)
            ),
            array(
                HTML::mostrarDato("sucursal_banco", $textos["SUCURSALES_BANCOS"], $sucursal_banco)
            ),
            array(
                HTML::mostrarDato("numero", $textos["NUMERO"], $datos->numero)
            ),
            array(
                HTML::mostrarDato("plan_contable", $textos["PLAN_CONTABLE"], $plan_contable)
            ),
            $auxiliar,
            array(
                HTML::mostrarDato("estado", $textos["ESTADO"], $estado[$datos->estado])
            )
        );

        $formularios["PESTANA_PLANTILLA"] = array(
            array(
                HTML::campoTextoLargo("plantilla", $textos["PLANTILLA"], 34, 76, $datos->plantilla, array("class" => "plantilla", "readonly" => "readonly"))
            )
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
    $llave_cuenta   = explode('|',$forma_id);
    $consulta       = SQL::eliminar("cuentas_bancarias", "codigo_sucursal = '".$llave_cuenta[0]."' AND codigo_tipo_documento = '".$llave_cuenta[1]."' AND codigo_sucursal_banco = '".$llave_cuenta[2]."' AND codigo_banco = '".$llave_cuenta[6]."' AND numero = '".$llave_cuenta[7]."'");

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

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

    } else {
        $error         = "";
        $titulo        = $componente->nombre;
        
        $vistaConsulta = "bancos";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo = '$url_id'");
        $datos         = SQL::filaEnObjeto($consulta);

        $tercero = explode('|',SQL::obtenerValor("seleccion_terceros","nombre","id = '".$datos->documento_identidad_tercero."'"));
        $tercero = $tercero[0];
        
        
                
        /*** Definición de pestañas ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::mostrarDato("codigo", $textos["CODIGO"],$datos->codigo),
                HTML::mostrarDato("descripcion", $textos["DESCRIPCION"],$datos->descripcion)
            ),
            array(
                HTML::mostrarDato("tercero", $textos["TERCERO"],$tercero)
            )
        );
        
        /*** Obtener sucursales relacionadas con el Banco ***/
        $consulta_sucursales = SQL::seleccionar(array("sucursales_bancos"), array("*"), "codigo_banco = '$url_id'");
        if (SQL::filasDevueltas($consulta_sucursales)) {
            $item_sucursal = array();
            while ($datos_sucursal = SQL::filaEnObjeto($consulta_sucursales)) {

                $codigo_sucursal    = $datos_sucursal->codigo;
                $nombre             = $datos_sucursal->nombre_sucursal;
                $llave_municipio    = $datos_sucursal->codigo_iso.'|'.$datos_sucursal->codigo_dane_departamento.'|'.$datos_sucursal->codigo_dane_municipio;
                $municipio          = explode('|',SQL::obtenerValor("seleccion_municipios","nombre","id= '".$llave_municipio."'"));
                $municipio          = $municipio[0];
                $direccion          = $datos_sucursal->direccion;
                $telefono           = $datos_sucursal->telefono;
                $contacto           = $datos_sucursal->contacto;
                $correo             = $datos_sucursal->correo;
                $celular            = $datos_sucursal->celular;

                $item_sucursal[] = array(
                    $codigo_sucursal,
                    $nombre,
                    $municipio,
                    $direccion,
                    $telefono,
                    $contacto,
                    $celular,
                    $correo
                );
            }
        }

        if(isset($item_sucursal)){
            $formularios["PESTANA_SUCURSALES"] = array(
                array(
                    HTML::generarTabla(
                        array("id","NOMBRE_SUCURSAL","MUNICIPIO_SUCURSAL","DIRECCION_SUCURSAL","TELEFONO_SUCURSAL","CONTACTO","CELULAR_SUCURSAL","CORREO_SUCURSAL"),
                        $item_sucursal,
                        array("I","I","I","D","I","C","I"),
                        "lista_items_sucursales",
                        false)
                )
            );
        }else{
            $formularios["PESTANA_SUCURSALES"] = array(
                array(
                    HTML::mostrarDato("sin_sucursales", "",$textos["BANCO_SUCURSALES_VACIAS"])
                )
            );
        }

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
    $eliminar_sucursal = SQL::eliminar("sucursales_bancos", "codigo_banco = '$forma_id'");
    
    if(!$eliminar_sucursal){
        $error   = true;
        $mensaje = $textos["ERROR_ELIMINAR_SUCURSAL"];
    }else{
        $eliminar = SQL::eliminar("bancos", "codigo = '$forma_id'");

        if($eliminar){
            $error   = false;
            $mensaje = $textos["ITEM_ELIMINADO"];
        }else{
            $error   = true;
            $mensaje = $textos["ERROR_ELIMINAR_ITEM"];
        }
    }

    /*** Enviar datos con la respuesta del proceso al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

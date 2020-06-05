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
    $error    = "";
    $idActual = $componente->id;
    $titulo   = $componente->nombre;
    
    $codigo = SQL::obtenerValor("perfiles","MAX(codigo)","codigo>0");
    if ($codigo){
        $codigo++;
    } else {
        $codigo = 1;
    }

    /*** Definición de pestañas ***/
    $formularios["PESTANA_GENERAL"] = array(
        array(
            HTML::campoTextoCorto("*codigo", $textos["CODIGO"], 4, 4, $codigo, array("title" => $textos["AYUDA_CODIGO"],"onBlur" => "validarItem(this);", "onKeyPress" => "return campoEntero(event)"))
        ),
        array(
            HTML::campoTextoCorto("*nombre", $textos["NOMBRE"], 30, 255, "", array("title" => $textos["AYUDA_NOMBRE"],"onBlur" => "validarItem(this);"))
        )
    );

    $formularios["PESTANA_COMPONENTES"] = array(
        array(
            HTML::arbolPerfiles("componentes_perfil")
        )
    );

    /*** Definición de botones ***/
    $botones = array(
        HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar")
    );

    $componente = new Componente($idActual);
    $contenido  = HTML::generarPestanas($formularios, $botones);

    /*** Enviar datos para la generación del formulario al script que originó la petición ***/
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

/*** Validación en línea de los datos provenientes del formulario ***/
} elseif (!empty($url_validar)) {

     /*** Validar codigo ***/
    if ($url_item == "codigo") {
        $existe = SQL::existeItem("perfiles", "codigo", $url_valor);

        if ($existe) {
           HTTP::enviarJSON($textos["ERROR_EXISTE_CODIGO"]);
        }
    }
    /*** Validar nombre ***/
    if ($url_item == "nombre") {
        $existe = SQL::existeItem("perfiles", "nombre", $url_valor);

        if ($existe) {
           HTTP::enviarJSON($textos["ERROR_EXISTE_NOMBRE"]);
        }
    }

/*** Adicionar los datos provenientes del formulario ***/
} elseif (!empty($forma_procesar)) {

    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];

    /*** Validar el ingreso de los datos requeridos ***/
    if (empty($forma_codigo)) {
        $error   = true;
        $mensaje = $textos["CODIGO_VACIO"];

    } else if (empty($forma_nombre)) {
        $error   = true;
        $mensaje = $textos["NOMBRE_VACIO"];

    } else if (!isset($forma_privilegios)) {
        $error   = true;
        $mensaje = $textos["PRIVILEGIOS_VACIO"];

    } elseif (SQL::existeItem("perfiles", "codigo", $forma_codigo)) {
        $error   = true;
        $mensaje =  $textos["ERROR_EXISTE_CODIGO"];
    
    } elseif (SQL::existeItem("perfiles", "nombre", $forma_nombre)) {
        $error   = true;
        $mensaje =  $textos["ERROR_EXISTE_NOMBRE"];

    } else {
		/*** Insertar datos ***/
        $datos = array(
            "codigo" => $forma_codigo,
            "nombre" => $forma_nombre
        );

        $insertar = SQL::insertar("perfiles", $datos);
        

        /*** Error de inserción ***/
        if (!$insertar) {
            $error   = true;
            $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
        } else {
            
            $idAsignado = SQL::$ultimoId;
            foreach ($forma_privilegios as $privilegio => $valor) {
                $datos = array(
                    "id_perfil"     => $idAsignado,
                    "id_componente" => $privilegio
                );

                $perfil = SQL::obtenerValor("componentes_perfil","id_componente","id_perfil='$idAsignado' AND id_componente='$privilegio'");
                if (!$perfil){
                    $insertar = SQL::insertar("componentes_perfil", $datos);
                    if (!$insertar) {
                        $error    = true;
                        $mensaje  = $textos["ERROR_ADICIONAR_ITEM"];
                    }
                }
                
                $padre = SQL::obtenerValor("componentes","padre","id='$privilegio'");
                if ($padre!=NULL){
                    while ($padre != NULL){
                        
                        $perfil_padre = SQL::obtenerValor("componentes_perfil","id_componente","id_perfil='$idAsignado' AND id_componente='$padre'");
                        if (!$perfil_padre){
                            $datos = array(
                                "id_perfil"     => $idAsignado,
                                "id_componente" => $padre
                            );
                            $insertar = SQL::insertar("componentes_perfil", $datos);
                            if (!$insertar) {
                                $error    = true;
                                $mensaje  = $textos["ERROR_ADICIONAR_ITEM"];
                            }
                        }
                        $padre = SQL::obtenerValor("componentes","padre","id='$padre'");
                    }
                }
            }
            if ($error) {
                $eliminar = SQL::eliminar("componentes_perfil","id_perfil='$idAsignado'");
                $eliminar = SQL::eliminar("perfiles","id='$idAsignado'");
            }
        }
    }

    /*** Enviar datos con la respuesta del proceso al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

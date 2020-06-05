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

	/*** Verificar que se haya enviado el ID del elemento a modificar ***/
	if (empty($url_id)) {
		$error     = $textos["ERROR_MODIFICAR_VACIO"];
		$titulo    = "";
		$contenido = "";

	} else {
		$vistaConsulta = "perfiles";
		$condicion     = "id = '$url_id'";
		$columnas      = SQL::obtenerColumnas($vistaConsulta);
		$consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, $condicion);
		$datos         = SQL::filaEnObjeto($consulta);
		$error         = "";
		$idActual      = $componente->id;
		$titulo        = $componente->nombre;

		/*** Definición de pestañas ***/
		$formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::campoTextoCorto("*codigo", $textos["CODIGO"], 4, 4, $datos->codigo, array("title" => $textos["AYUDA_CODIGO"],"onBlur" => "validarItem(this);", "onKeyPress" => "return campoEntero(event)"))
            ),
            array(
                HTML::campoTextoCorto("*nombre", $textos["NOMBRE"], 30, 255, $datos->nombre, array("title" => $textos["AYUDA_NOMBRE"],"onBlur" => "validarItem(this);"))
            ),
            array(
                HTML::marcaChequeo("cambiar", $textos["CAMBIAR"], 1, false)
            )
		);

		$formularios["PESTANA_COMPONENTES"] = array(
            array(
                HTML::arbolPerfiles("componentes_perfil", $url_id)
            )
		);

		/*** Definición de botones ***/
		$botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "modificarItem('$url_id');", "aceptar")
		);

		$componente = new Componente($idActual);
		$contenido  = HTML::generarPestanas($formularios, $botones);
	}

	/*** Enviar datos para la generación del formulario al script que originó la petición ***/
	$respuesta    = array();
	$respuesta[0] = $error;
	$respuesta[1] = $titulo;
	$respuesta[2] = $contenido;
	HTTP::enviarJSON($respuesta);

/*** Validar los datos provenientes del formulario ***/
} elseif (!empty($url_validar)) {

	 /*** Validar codigo ***/
    if ($url_item == "codigo") {
        $existe = SQL::existeItem("perfiles", "codigo", $url_valor,"id !='$url_id'");

        if ($existe) {
           HTTP::enviarJSON($textos["ERROR_EXISTE_CODIGO"]);
        }
    }
    /*** Validar nombre ***/
    if ($url_item == "nombre") {
        $existe = SQL::existeItem("perfiles", "nombre", $url_valor,"id !='$url_id'");

        if ($existe) {
           HTTP::enviarJSON($textos["ERROR_EXISTE_NOMBRE"]);
        }
    }

	/*** Modificar el elemento seleccionado ***/
} elseif (!empty($forma_procesar)) {

	/*** Asumir por defecto que no hubo error ***/
	$error   = false;
	$mensaje = $textos["ITEM_ADICIONADO"];

	/*** Validar el ingreso de los datos requeridos ***/
	if (empty($forma_nombre)) {
		$error   = true;
		$mensaje = $textos["ERROR_DATOS_INCOMPLETOS"];

	} elseif (SQL::existeItem("perfiles", "codigo", $forma_codigo, "id != $forma_id")) {
		$error   = true;
		$mensaje =  $textos["ERROR_EXISTE_CODIGO"];
    
    } elseif (SQL::existeItem("perfiles", "nombre", $forma_nombre, "id != $forma_id")) {
		$error   = true;
		$mensaje =  $textos["ERROR_EXISTE_NOMBRE"];

	} elseif (!isset($forma_privilegios)) {
		$error   = true;
		$mensaje =  $textos["ERROR_COMPONENTES"];

	} else {

		$datos = array(
            "codigo" => $forma_codigo,
            "nombre" => $forma_nombre
		);

		$consulta = SQL::modificar("perfiles", $datos, "id = '$forma_id'");
		$consulta = SQL::eliminar("componentes_perfil", "id_perfil = '$forma_id'");

		foreach ($forma_privilegios as $privilegio => $valor) {
			$datos = array(
                "id_perfil"     => $forma_id,
                "id_componente" => $privilegio
			);

            $perfil   = SQL::obtenerValor("componentes_perfil","id_componente","id_perfil='$forma_id' AND id_componente='$privilegio'");
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
                    $perfil_padre = SQL::obtenerValor("componentes_perfil","id_componente","id_perfil='$forma_id' AND id_componente='$privilegio'");
                    if (!$perfil_padre){
                        $datos = array(
                            "id_perfil"     => $forma_id,
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

		if(isset($forma_cambiar)){
			/* Consulta el id del perfil en la tabla perfiles_usuario */
			$consulta_usuario = SQL::seleccionar(array("perfiles_usuario"), array("id"), "id_perfil = '$forma_id'");
            if (SQL::filasDevueltas($consulta_usuario)){
                while($datos_usuario = SQL::filaEnObjeto($consulta_usuario)){
                    $id_perfil_usuario = $datos_usuario->id;

                    /* Elimina todos los componentes del perfil actual */
                    $eliminar = SQL::eliminar("componentes_usuario", "id_perfil = '$id_perfil_usuario'");
                    if ($eliminar){
                        /* Inserta nuevos componentes */				
                        foreach ($forma_privilegios as $privilegio_usuario => $valor) {
                            $datos = array(
                                "id_perfil"     => $id_perfil_usuario,
                                "id_componente" => $privilegio_usuario
                            );

                            $insertar = SQL::insertar("componentes_usuario", $datos);
                            /*** Error de inserción ***/
                            if (!$insertar) {
                                $error   = true;
                                $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
                            }
                        }
                    }
                }
            }
		}
		if ($consulta) {
			$error   = false;
			$mensaje = $textos["ITEM_MODIFICADO"];
		} else {
			$error   = true;
			$mensaje = $textos["ERROR_MODIFICAR_ITEM"];
		}
	}

	/*** Enviar datos con la respuesta del proceso al script que originó la petición ***/
	$respuesta    = array();
	$respuesta[0] = $error;
	$respuesta[1] = $mensaje;
	HTTP::enviarJSON($respuesta);
}
?>

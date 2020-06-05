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
* modificarlo  bajo los t�rminos de la Licencia P�blica General GNU
* publicada por la Fundaci�n para el Software Libre, ya sea la versi�n 3
* de la Licencia, o (a su elecci�n) cualquier versi�n posterior.
*
* Este programa se distribuye con la esperanza de que sea �til, pero
* SIN GARANT�A ALGUNA; ni siquiera la garant�a impl�cita MERCANTIL o
* de APTITUD PARA UN PROP�ITO DETERMINADO. Consulte los detalles de
* la Licencia P�blica General GNU para obtener una informaci�n m�s
* detallada.
*
* Deber�a haber recibido una copia de la Licencia P�blica General GNU
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
**/

/*** Validar descripcion ***/
if(isset($url_verificarDescripcion) && isset($url_descripcion) && isset($url_padre) && isset($url_id)){
    $existe_nombre = SQL::obtenerValor("estructura_grupos","descripcion","codigo_padre = '$url_padre' AND descripcion = '$url_descripcion' AND codigo != '$url_id'");
    if($existe_nombre){
        $mensaje = $textos["ERROR_EXISTE_DESCRIPCION"];
        HTTP::enviarJSON($mensaje);
    }
}

/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {
    $error       = "";
    $titulo      = $componente->nombre;

    /*** Verificar que se haya enviado el ID del elemento a modificar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_MODIFICAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {
        $vistaConsulta = "estructura_grupos";
        $condicion     = "codigo = '$url_id'";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, $condicion);
        $datos         = SQL::filaEnObjeto($consulta);
       
        $error         = "";
        $titulo        = $componente->nombre;
        $grupos        = HTML::generarDatosLista("grupos", "codigo", "descripcion","codigo != 0");

        /*** Marcar como grupo principal cuando id_padre en la tabla sea NULL ***/
        if (empty($datos->codigo_padre)) {
            $principal = true;
        } else {
            $principal = false;
        }

        /*** Definici�n de pesta�as general ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::campoTextoCorto("*codigo", $textos["CODIGO"], 4, 4, $datos->codigo, array("title" => $textos["AYUDA_CODIGO"],"onBlur" => "validarItem(this);","onKeyPress" => "return campoEntero(event)"))
                .HTML::campoOculto("id",$datos->codigo)
            ),
            array(
                HTML::campoTextoCorto("*descripcion", $textos["DESCRIPCION"], 30, 255, $datos->descripcion, array("title" => $textos["AYUDA_DESCRIPCION"], "onBlur" => "validarDescripcion();"))
            ),
            array(
                HTML::listaSeleccionSimple("*relacion", $textos["RELACION"], $grupos, $datos->codigo_grupo, array("title" => $textos["AYUDA_RELACION"]))
            ),
            array(
                HTML::campoTextoCorto("*orden", $textos["ORDEN"], 4, 4, $datos->orden, array("title" => $textos["AYUDA_ORDEN"],"onKeyPress" => "return campoEntero(event)"))
            )
        );

        $formularios["PESTANA_GRUPO"] = array(
            array(
                HTML::marcaChequeo("principal", $textos["GRUPO_PRINCIPAL"], 1, $principal, array("title" => $textos["AYUDA_PRINCIPAL"]))
            ),
            array(
                HTML::contenedor(HTML::arbolGrupos("arbolGrupos",$url_id, $datos->codigo_padre,"padre", true))
            )
        );

        /*** Definici�n de botones ***/
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "modificarItem('$url_id');", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones);
    }

    /*** Enviar datos para la generaci�n del formulario al script que origin� la petici�n ***/
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

/*** Validar los datos provenientes del formulario ***/
} elseif (!empty($url_validar)) {

    /*** Validar codigo ***/
    if ($url_item == "codigo") {
        $existe = SQL::existeItem("estructura_grupos", "codigo", $url_valor, "codigo != $url_id AND codigo !='0'");

        if ($existe) {
           HTTP::enviarJSON($textos["ERROR_EXISTE_CODIGO"]);
        }
    }

/*** Adicionar los datos provenientes del formulario ***/
} elseif (!empty($forma_procesar)) {
    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];

    /*** Validar el ingreso de los datos requeridos ***/
    if(empty($forma_descripcion)){
		$error   = true;
        $mensaje = $textos["DESCRIPCION_VACIO"];
        
	}elseif(empty($forma_codigo)){
        $error   = true;
        $mensaje = $textos["CODIGO_VACIO"];
        
   }elseif(empty($forma_relacion)){
        $error   = true;
        $mensaje = $textos["RELACION_VACIO"];

   }elseif(empty($forma_orden)){
        $error   = true;
        $mensaje = $textos["ORDEN_VACIO"];

    }elseif($existe = SQL::existeItem("estructura_grupos", "codigo", $forma_codigo, "codigo != $forma_id")){
	    $error   = true;
        $mensaje = $textos["ERROR_EXISTE_CODIGO"]; 

    }elseif($existe = SQL::existeItem("estructura_grupos", "descripcion", $forma_descripcion, "codigo_padre = '$forma_padre' AND codigo != $forma_id")){
	    $error   = true;
        $mensaje = $textos["ERROR_EXISTE_DESCRIPCION"]; 

    } else {

        if (isset($forma_principal)) {
            $forma_padre = "";
        }
		
		/*** Insertar datos ***/
        $datos = array(
            "codigo"       => $forma_codigo,
            "codigo_padre" => $forma_padre,
            "codigo_grupo" => $forma_relacion,
            "descripcion"  => $forma_descripcion,
            "orden"        => $forma_orden
        );
        $modificar = SQL::modificar("estructura_grupos", $datos, "codigo = '$forma_id'");
        
        /*** Error inserci�n ***/
        if(!$modificar){
			$error   = true;
			$mensaje = $textos["ERROR_MODIFICAR_ITEM"];
		}
    }
    /*** Enviar datos con la respuesta del proceso al script que origin� la petici�n ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

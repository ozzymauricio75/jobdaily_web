<?php

/**
*
* Copyright (C) 2010 Sistemas de Apoyo Empresarial Ltda
* Walter Andr�s M�rquez Guti�rrez <walteramg@gmail.com>
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

/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {

     /*** Verificar que se haya enviado el ID del elemento a modificar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_ELIMINAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {
        
        $vistaConsulta = "salario_minimo";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo = '$url_id'");
        $datos         = SQL::filaEnObjeto($consulta);
        $error         = "";
        $titulo        = $componente->nombre;
                    
		
        /*** Definici�n de pesta�a personal ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::mostrarDato("*fecha_inicia", $textos["FECHA_INICIA"], $datos->fecha)
            ),
            array(
                HTML::mostrarDato("*valor", $textos["VALOR"], (int)$datos->valor)
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
} else if (!empty($forma_procesar)){
    $error   = false;
    $mensaje = $textos["ITEM_ELIMINADO"];
    
    $eliminar = SQL::eliminar("salario_minimo","codigo='$forma_id'");
    if (!$eliminar){
        $error   = true;
        $mensaje = $textos["ERROR_ELIMINAR_ITEM"];
    }

    /*** Enviar datos con la respuesta del proceso al script que origin� la petici�n ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

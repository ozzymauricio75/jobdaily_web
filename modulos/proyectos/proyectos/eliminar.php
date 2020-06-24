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
* de APTITUD PARA UN PROP�SITO DETERMINADO. Consulte los detalles de
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

    /*** Verificar que se haya enviado el ID del elemento a consultar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_CONSULTAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {
        $vistaConsulta  = "proyectos";
        $columnas       = SQL::obtenerColumnas($vistaConsulta);
        $consulta       = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo = '$url_id'");
        $datos          = SQL::filaEnObjeto($consulta);
        $valor_proyecto = number_format($datos->valor_proyecto,0);

        /*Obtener Valores*/
        $empresa            = $datos->codigo_empresa_ejecuta;
        $sucursal           = $datos->codigo_sucursal_ejecuta;
        $direccion_proyecto = $datos->direccion_proyecto;
        $activo             = $datos->activo;

        $nombre_empresa  = SQL::obtenerValor("empresas","razon_social", "codigo = '$empresa'");
        $nombre_sucursal = SQL::obtenerValor("sucursales","nombre", "codigo = '$sucursal' AND codigo_empresa = '$empresa'");
        $tipo            = SQL::obtenerValor("sucursales","tipo", "codigo = '$sucursal' AND codigo_empresa = '$empresa'");

        $error         = "";
        $titulo        = $componente->nombre;

        $tipoEmpresa = array(
            "1" => $textos["EMPRESA_DISTRIBUIDORA_MAYORISTA"],
            "2" => $textos["EMPRESA_VENTAS_PUBLICO"],
            "3" => $textos["EMPRESA_AMBAS"],
            "4" => $textos["EMPRESA_SOPORTE"]
        );

        $indicador = array(
            "0" => $textos["INDICADOR_NO"],
            "1" => $textos["INDICADOR_SI"]
        );

        //Coloca texto del estado
        if($activo ==0){
            $estado = "Cerrado";
        }elseif ($activo ==1) {
            $estado = "Abierto";
        }

        //Coloca texto del tipo de sucursal
        if($tipo ==1){
            $tipo = "Consorcio";
        }elseif ($tipo ==2) {
            $tipo = "Uni�n temporal";
        }elseif ($tipo ==0) {
            $tipo = "Principal";
        }

        /*** Obtener valores ***/
        $llave_primaria_municipio = $datos -> codigo_iso.",".$datos->codigo_dane_departamento.",".$datos -> codigo_dane_municipio;

        $municipio = SQL::obtenerValor("seleccion_municipios","nombre","llave_primaria = '$llave_primaria_municipio'");
        $municipio = explode("|",$municipio);
        $municipio = $municipio[0];
              // echo var_dump($municipio);

        $empresa   = SQL::obtenerValor("empresas","razon_social","codigo = '$datos->codigo_empresa'");

        /*** Definici�n de pesta�as general ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::mostrarDato("codigo", $textos["CODIGO"], $datos->codigo)
            ),
            array(
                HTML::mostrarDato("empresa", $textos["EMPRESA"], $nombre_empresa),
            ),
            array(   
                HTML::mostrarDato("sucursal", $textos["CONSORCIO"], $nombre_sucursal),
                HTML::mostrarDato("tipo", $textos["TIPO"], $tipo)
            ),
            array(
                HTML::mostrarDato("nombre", $textos["NOMBRE"], $datos->nombre),

                HTML::mostrarDato("valor_proyecto", $textos["VALOR_PROYECTO"], $valor_proyecto),

                HTML::mostrarDato("estado", $textos["ESTADO"], $estado),
            )
        );

        $formularios["PESTANA_UBICACION"] = array(
            array(
                HTML::mostrarDato("municipio", $textos["MUNICIPIO"], $municipio)
            ),
            array(
                HTML::mostrarDato("*direccion_residencia", $textos["DIRECCION_PROYECTO"], $direccion_proyecto)
            )
        );

         /*** Definici�n de botones ***/
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "eliminarItem('$url_id');", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones);
    }

    /*** Enviar datos para la generaci�n del formulario al script que origin� la petici�n ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

} elseif (!empty($forma_procesar)) {
    $consulta = SQL::eliminar("proyectos", "codigo = '$forma_id'");

    if ($consulta) {
        $error   = false;
        $mensaje = $textos["ITEM_ELIMINADO"];
    } else {
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

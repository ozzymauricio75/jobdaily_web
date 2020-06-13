<?php

/**
*
* Copyright (C) 2020 Jobdaily
*
* Este archivo es parte de:
* Jobdaily:: Sofware empresarial a la medida
*
* Este programa es software libre: usted puede redistribuirlo y/o
* modificarlo  bajo los terminos de la Licencia Publica General GNU
* publicada por la Fundacion para el Software Libre, ya sea la version 3
* de la Licencia, o (a su eleccion) cualquier version posterior.
*
* Este programa se distribuye con la esperanza de que sea util, pero
* SIN GARANTIA ALGUNA; ni siquiera la garantia implicita MERCANTIL o
* de APTITUD PARA UN PROPOSITO DETERMINADO. Consulte los detalles de
* la Licencia Publica General GNU para obtener una informacion mas
* detallada.
*
* Deberia haber recibido una copia de la Licencia Publica General GNU
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
        
        /*Obtener Valores*/
        $empresa            = $datos->codigo_empresa_ejecuta;
        $sucursal           = $datos->codigo_sucursal_ejecuta;
        $direccion_proyecto = $datos->direccion_proyecto;
        $activo             = $datos->activo;
        $valor_proyecto     = number_format($datos->valor_proyecto,0);

        $nombre_empresa  = SQL::obtenerValor("empresas","razon_social", "codigo = '$empresa'");
        $nombre_sucursal = SQL::obtenerValor("sucursales","nombre", "codigo = '$sucursal' AND codigo_empresa = '$empresa'");
        $tipo            = SQL::obtenerValor("sucursales","tipo", "codigo = '$sucursal' AND codigo_empresa = '$empresa'");

        //Coloca texto del tipo de sucursal
        if($tipo ==1){
            $tipo = "Consorcio";
        }elseif ($tipo ==2) {
            $tipo = "Unión temporal";
        }elseif ($tipo ==0) {
            $tipo = "Principal";
        }

        //Coloca texto del estado
        if($activo ==0){
            $estado = "Cerrado";
        }elseif ($activo ==1) {
            $estado = "Abierto";
        }

        $error         = "";
        $titulo        = $componente->nombre;

        /*** Obtener valores ***/
        $llave_primaria_municipio = $datos -> codigo_iso.",".$datos->codigo_dane_departamento.",".$datos -> codigo_dane_municipio;

        $municipio = SQL::obtenerValor("seleccion_municipios","nombre","llave_primaria = '$llave_primaria_municipio'");
        $municipio = explode("|",$municipio);
        $municipio = $municipio[0];

        $activo = array(
            "0" => $textos["ESTADO_INACTIVA"],
            "1" => $textos["ESTADO_ACTIVA"]
        );

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

        /*** Definición de pestañas general ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::mostrarDato("codigo", $textos["CODIGO"], $datos->codigo)
            ),
            array(
                HTML::mostrarDato("empresa", $textos["EMPRESA"], $nombre_empresa)
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
                HTML::mostrarDato("*direccion_proyecto", $textos["DIRECCION_PROYECTO"], $direccion_proyecto)
            )
        );

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

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
    $error     = "";
    $titulo    = $componente->nombre;
    $contenido = $componente->nombre;

    $tablas     = array(
        "c" => "sucursales"
    );
    $columnas = array(
        "codigo" => "c.codigo",
        "nombre" => "c.nombre"
    );
    $condicion = "codigo != 0";
    $consulta = SQL::seleccionar($tablas, $columnas, $condicion);        

    $existen_sucursales = true;
    if (SQL::filasDevueltas($consulta)) {
        $sucursales = array();

        while ($datos_sucursal = SQL::filaEnObjeto($consulta)) {
            $idSucursal          = $datos_sucursal->codigo;
            $nombreSucursal      = $datos_sucursal->nombre;            

            $sucursales[]   = array(HTML::marcaChequeo("sucursales[]", $nombreSucursal, $idSucursal,false));

        }
    } else {
        $existen_sucursales = false;
    }

    $consulta = SQL::seleccionar(array("perfiles"), array("*"), "id>0");

    if (!$existen_sucursales){
        $error = $textos["CREAR_SUCURSALES"];

    } else if (!$sucursales){
        $error = $textos["PRIVILEGIO_SUCURSAL"];

    } else if (!SQL::filasDevueltas($consulta)){
        $error = $textos["CREAR_PERFILES"];

    } else {
        /*** Definici�n de pesta�as ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::listaSeleccionSimple("*usuario", $textos["USUARIO"], HTML::generarDatosLista("usuarios", "codigo", "nombre"), "", array("title" => $textos["AYUDA_USUARIO"]))
            ),
            array(
                HTML::listaSeleccionSimple("*perfil", $textos["PERFIL"], HTML::generarDatosLista("perfiles", "id", "nombre"), "", array("title" => $textos["AYUDA_PERFIL"]))
            )
        );
        $formularios["PESTANA_SUCURSALES"] = $sucursales;
        
        /*** Definici�n de botones ***/
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones);
    }

    /*** Enviar datos para la generaci�n del formulario al script que origin� la petici�n ***/
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

/*** Adicionar los datos provenientes del formulario ***/
} elseif (!empty($forma_procesar)) {

    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];

    /*** Validar el ingreso de los datos requeridos ***/
    if (empty($forma_usuario) || empty($forma_sucursales) || empty($forma_perfil)) {
        $error   = true;
        $mensaje = $textos["ERROR_DATOS_INCOMPLETOS"];

    }else {
        
        foreach($forma_sucursales AS $sucursal){

            if (!SQL::existeItem("perfiles_usuario", "codigo_usuario", $forma_usuario, "codigo_sucursal = '$sucursal' AND id_perfil = '$forma_perfil'")) {

                $datos = array(
                    "codigo_usuario"  => $forma_usuario,
                    "codigo_sucursal" => $sucursal,
                    "id_perfil"       => $forma_perfil
                );

                $insertar = SQL::insertar("perfiles_usuario", $datos);
                /*** Error de inserci�n ***/
                if (!$insertar) {
                  $error   = true;
                  $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
                } else {

                    $idAsignado = SQL::$ultimoId;
                    $consulta   = SQL::seleccionar(array("componentes_perfil"),array("id_componente"),"id_perfil = '$forma_perfil'");

                    while ($resultado = SQL::filaEnObjeto($consulta)) {
                        $privilegio = $resultado->id_componente;

                        $datos = array(
                            "id_perfil"     => $idAsignado,
                            "id_componente" => $privilegio
                        );
                        $insertar = SQL::insertar("componentes_usuario", $datos);

                        /*** Error de inserci�n ***/
                        if (!$insertar) {
                            $error   = true;
                            $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
                        }
                    }
                }
            }
        }
    }

    /*** Enviar datos con la respuesta del proceso al script que origin� la petici�n ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

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

/*** Procesar los datos provenientes del formulario ***/
if (isset($forma_botonAceptar)) {

    /*** Validar el ingreso de datos requeridos ***/
    if (empty($forma_usuario) && empty($forma_contrasena)) {
        echo $textos["ERROR_DATOS_INSUFICIENTES"];

    /*** Validar el nombre de usuario y la contraseña ingresados ***/
    } elseif (SQL::existeItem("usuarios","usuario",$forma_usuario, "contrasena = MD5('$forma_contrasena')")) {

        $columnas = array(
            "cambiar_contrasena",
            "fecha_cambio_contrasena",
            "cambio_contrasena_minimo",
            "cambio_contrasena_maximo",
            "fecha_expiracion",
            "activo"
        );

        /*** Obtener datos adicionales del usuario para validar su ingreso ***/
        $consulta = SQL::seleccionar(array("usuarios"), $columnas, "usuario = '$forma_usuario'");
        $datos    = SQL::filaEnObjeto($consulta);

        /*** Verificar si el usuario se encuentra activo ***/
        if (!$datos->activo) {
            echo $textos["ERROR_USUARIO_INACTIVO"];

        /*** Validar la fecha de expiración del usuario ***/
        } elseif ($datos->fecha_expiracion && ($datos->fecha_expiracion <= date("Y-m-d H:i:s"))) {
            echo $textos["ERROR_USUARIO_EXPIRADO"];

        /*** El usuario puede acceder ***/
        } else {
            //echo var_dump($sesion_usuario);
            //echo var_dump($forma_usuario);
            if (!isset($sesion_usuario) || (isset($sesion_usuario) && ($sesion_usuario == $forma_usuario))){

                Sesion::registrar("usuario", $forma_usuario);
                Sesion::registrar("contrasena", md5($forma_contrasena));
                Sesion::registrar("sucursal", $forma_sucursal);
                Sesion::registrar("cliente", HTTP::$cliente);

                $usuario = SQL::obtenerValor("usuarios", "codigo", "usuario = '$forma_usuario'");
                $perfil  = SQL::obtenerValor("perfiles_usuario", "id", "codigo_sucursal = '$forma_sucursal' AND codigo_usuario = '$usuario'");
                //$perfil  = SQL::obtenerValor("perfiles_usuario", "id", "id_sucursal = '$forma_sucursal' AND id_usuario = '$usuario'");

                $fecha_ingreso = date("Y-m-d H:i:s");

                Sesion::registrar("perfil", $perfil);
                Sesion::registrar("sucursal_conexion", $forma_sucursal);
                Sesion::registrar("codigo_usuario", $usuario);
                Sesion::registrar("fecha_conexion", $fecha_ingreso);

                $datos   = array(
                    "codigo_sucursal" => $forma_sucursal,
                    "codigo_usuario"  => $usuario,
                    "fecha"           => $fecha_ingreso,
                    "ip"              => HTTP::$cliente,
                    "proxy"           => HTTP::$proxy
                );
                $insertar = SQL::insertar("conexiones", $datos);

                if (!$insertar){
                    while(!$insertar){
                        $fecha_ingreso = date("Y-m-d H:i:s");
                        Sesion::registrar("fecha_conexion", $fecha_ingreso);
                        $datos   = array(
                            "codigo_sucursal" => $forma_sucursal,
                            "codigo_usuario"  => $usuario,
                            "fecha"           => $fecha_ingreso,
                            "ip"              => HTTP::$cliente,
                            "proxy"           => HTTP::$proxy
                        );
                        $insertar = SQL::insertar("conexiones", $datos);
                    }
                }
                Sesion::registrar("menu", HTML::arbolComponentes());

                $preferencias_individuales = array();
                $preferencias_usuario = SQL::seleccionar(array("preferencias"),array("valor", "variable"),"tipo_preferencia='2' AND codigo_usuario='$usuario'");

                if(SQL::filasDevueltas($preferencias_usuario)){

                    while ($datos = SQL::filaEnObjeto($preferencias_usuario)) {
                        $preferencias_individuales[$datos->variable] = $datos->valor;
                    }
                    Sesion::registrar("preferencias_individuales", $preferencias_individuales);
                }

                $preferencias_globales = array();
                $preferencias_sucursal = SQL::seleccionar(array("preferencias"),array("valor", "variable"),"tipo_preferencia='1' AND codigo_sucursal='$forma_sucursal'");
                if(SQL::filasDevueltas($preferencias_sucursal)){
                    while ($datos = SQL::filaEnObjeto($preferencias_sucursal)) {
                        $preferencias_globales[$datos->variable] = $datos->valor;
                    }
                    Sesion::registrar("preferencias_globales", $preferencias_globales);
                }

                setcookie("usuario_maquina", $forma_usuario, time() + 31536000);
                setcookie("sede_maquina", $forma_sucursal, time() + 31536000);
            } else {
                echo $textos["ERROR_USUARIO_INGRESO"];
            }
        }

    /*** El nombre de usuario y la contraseña ingresados son incorrectos ***/
    } else {
        echo $textos["ERROR_USUARIO_CONTRASENA"];
    }

/*** Generar el formulario ***/
} else {

    if (!isset($sesion_usuario)){
        //$sucursales  = HTML::generarDatosLista("sucursales", "id", "nombre","id>0");
        $sucursales  = HTML::generarDatosLista("sucursales", "codigo", "nombre","codigo!=0");
        $formulario  = HTML::campoOculto("URL1", HTTP::generarURL($datosGlobales["componenteInicioSesion"]));
        $formulario .= HTML::campoOculto("URL2", HTTP::generarURL($datosGlobales["componentePaginaInicio"]));

        if (isset($_COOKIE['usuario_maquina'])) {
            $usuario_anterior = $_COOKIE['usuario_maquina'];
        } else {
            $usuario_anterior = "";
        }

        if (isset($_COOKIE['sede_maquina'])) {
            $sede_anterior = $_COOKIE['sede_maquina'];
        } else {
            $sede_anterior = "";
        }

        $filas = array(
            array(
                HTML::campoTextoCorto("usuario", $textos["USUARIO"], 12, 12, $usuario_anterior),
            ), array(
                HTML::campoTextoClave("contrasena", $textos["CONTRASENA"], 12, 12)
            ), array(
                HTML::listaSeleccionSimple("sucursal", $textos["SUCURSAL"], $sucursales, $sede_anterior, array("title" => $textos["AYUDA_SUCURSAL"]))
            )
        );

        foreach ($filas as $fila) {
            $formulario .= HTML::filaFormulario($fila);
        }

        $botones  = HTML::boton("botonRestaurar", $textos["RESTAURAR"], "restaurarFormulario();", "restaurar");
        $botones .= HTML::boton("botonAceptar", $textos["ACEPTAR"], "procesarFormulario();", "aceptar");

        $formulario .= HTML::filaFormulario(array($botones), "C");
        $formulario  = HTML::contenedor($formulario, array("id" => "inicioSesion"));

        Plantilla::iniciar();
        Plantilla::sustituir("menu");
        Plantilla::sustituir("buscador");
        Plantilla::sustituir("botones");
        Plantilla::sustituir("paginador");
        Plantilla::sustituir("registros");
        Plantilla::sustituir("mensaje");
        Plantilla::sustituir("registros");
        Plantilla::sustituir("bloqueDerecho");
        Plantilla::sustituir("bloqueIzquierdo", $formulario);
        Plantilla::sustituir("cuadroDialogo");
        Plantilla::enviarCodigo();
    } else {
        Plantilla::iniciar();
        Plantilla::sustituir("menu", HTML::arbolComponentes());
        Plantilla::sustituir("buscador");
        Plantilla::sustituir("registros");
        Plantilla::sustituir("paginador");
        Plantilla::sustituir("botones");
        Plantilla::sustituir("mensaje");
        Plantilla::sustituir("registros");
        Plantilla::sustituir("bloqueDerecho");
        Plantilla::sustituir("bloqueIzquierdo");//, $contenido);
        Plantilla::sustituir("cuadroDialogo");
        Plantilla::enviarCodigo();
    }
}
?>

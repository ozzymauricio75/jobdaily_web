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
    $error     = "";
    $titulo    = $componente->nombre;
    $contenido = "";

    $consulta = SQL::seleccionar(array("sucursales"),array("*"),"codigo > 0");
    if (SQL::filasDevueltas($consulta)){

        if ($sesion_usuario == $datosGlobales["usuarioMaestro"]){
            while ($datos = SQL::filaEnObjeto($consulta)){
                $sucursales[$datos->codigo] = $datos->nombre_corto;
            }

        } else {
            /*** Obtener lista de sucursales para selección ***/
            $tablas     = array(
                "a" => "perfiles_usuario",
                "b" => "componentes_usuario",
                "c" => "sucursales"
            );
            $columnas = array(
                "codigo" => "c.codigo",
                "nombre" => "c.nombre_corto"
            );
            $condicion = "c.codigo = a.codigo_sucursal AND a.id = b.id_perfil
                        AND a.codigo_usuario = '$sesion_codigo_usuario'
                        AND b.id_componente = '".$componente->id."'";

            $consulta = SQL::seleccionar($tablas, $columnas, $condicion);

            if (SQL::filasDevueltas($consulta)) {
                while ($datos = SQL::filaEnObjeto($consulta)) {
                    $sucursales[$datos->codigo] = $datos->nombre;
                }
            }
        }

        if (isset($sucursales)){

            $manana         = mktime(0, 0, 0, date("m"), date("d")+1, date("Y"));
            $manana         = date("Y-m-d", $manana);

            $pestana    =   array();
            $pestana[]  =   array(
                                HTML::listaSeleccionSimple("*sucursal", $textos["SUCURSAL"], $sucursales, "", array("title" => $textos["AYUDA_SUCURSAL"]))
                            );
            $pestana[]  =   array(
                                HTML::campoTextoCorto("*fechas", $textos["FECHA_INICIO"].'  -  '.$textos["FECHA_FIN"], 25, 25, "", array("title" => $textos["FECHAS"], "class" => "fechaRango"))
                            );

            $pestana_modulos[] = array(HTML::boton("botonSeleccionarTodos", $textos["SELECCIONAR_TODOS"], "seleccionar_todos();", "", array()));
            $pestana_modulos[] =   array(
                HTML::mostrarDato("modulos", $textos["MODULOS"], "")
            );
            /*** Consultar los modulos ***/
            $consulta   = SQL::seleccionar(array("modulos"), array("id", "nombre"), "id != '0'");
            if (SQL::filasDevueltas($consulta)) {
                while($datos = SQL::filaEnObjeto($consulta)) {
                    $pestana_modulos[]  =   array(
                         HTML::marcaChequeo("modulos[".$datos->id."]", $datos->nombre, $datos->id, false, array("id" => "$datos->id","class" => "modulos"))
                    );
                }
            }

            /*** Definición de pestañas general ***/
            $formularios["PESTANA_GENERAL"] = $pestana;

            /*** Definición de pestañas general ***/
            $formularios["PESTANA_MODULOS"] = $pestana_modulos;

            /*** Definición de botones ***/
            $botones = array(
                HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar")
            );

            $contenido = HTML::generarPestanas($formularios, $botones);
        } else {
            $error = $textos["USUARIO_SIN_PRIVILEGIOS"];
        }
    } else {
        $error = $textos["NO_EXISTEN_SUCURSALES"];
    }

    /*** Enviar datos para la generación del formulario al script que originó la petición ***/
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

/*** Adicionar los datos provenientes del formulario ***/
}elseif (!empty($forma_procesar)){
    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];

    if (empty($forma_fechas)){
        $error = true;
        $mensaje = $textos["ERROR_FECHAS"];

    } else if(empty($forma_sucursal)){
        $error = true;
        $mensaje = $textos["ERROR_SUCURSAL"];

    } else if (!isset($forma_modulos)){
        $error = true;
        $mensaje = $textos["ERROR_MODULOS_VACIO"];
    } else {
        $fechas       = explode('-',$forma_fechas);
        $fecha_inicio = trim($fechas[0]);
        $fecha_fin    = trim($fechas[1]);
        $insertar     = false;

        foreach($forma_modulos as $modulo) {

            $consulta = SQL::seleccionar(array("periodos_contables"), array("codigo_sucursal"), "(((fecha_inicio BETWEEN '$fecha_inicio' AND '$fecha_fin') OR (fecha_fin BETWEEN '$fecha_inicio' AND '$fecha_fin')) OR (('$fecha_inicio' BETWEEN fecha_inicio AND fecha_fin) OR ('$fecha_fin' BETWEEN fecha_inicio AND fecha_fin))) AND codigo_sucursal = '$forma_sucursal' AND id_modulo='$modulo'","","",0,1);

            if (!SQL::filasDevueltas($consulta)){

                $datos = array(
                    "codigo_sucursal" => $forma_sucursal,
                    "id_modulo"       => $modulo,
                    "fecha_inicio"    => $fecha_inicio,
                    "fecha_fin"       => $fecha_fin,
                    "estado"          => "1"
                );
                $insertar = SQL::insertar("periodos_contables", $datos);
            }
        }

        /*** Error de insercón ***/
        if (!$insertar) {
            $error   = true;
            $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
        }
    }

    /*** Enviar datos con la respuesta del proceso al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

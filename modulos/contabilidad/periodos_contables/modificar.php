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
    $contenido = "";
    if (empty($url_id)) {
        $error     = $textos["ERROR_MODIFICAR_VACIO"];
        $titulo    = "";

    } else {

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

                $estados = array(
                    "0"=>$textos["ESTADO_0"],
                    "1"=>$textos["ESTADO_1"]
                );
                $llave           = explode('|',$url_id);
                $codigo_sucursal = $llave[0];
                $id_modulo      =  $llave[1];
                $fecha_inicio    = $llave[2];
                $fecha_fin       = $llave[3];
                $vistaConsulta   = "periodos_contables";
                $columnas        = SQL::obtenerColumnas($vistaConsulta);
                $consulta        = SQL::seleccionar(array($vistaConsulta), $columnas,"codigo_sucursal='$codigo_sucursal' AND id_modulo='$id_modulo' AND fecha_inicio='$fecha_inicio' AND fecha_fin='$fecha_fin'");
                $datos           = SQL::filaEnObjeto($consulta);
                $error           = "";
                $titulo          = $componente->nombre;

                $fecha    = str_replace('-','/',$datos->fecha_inicio).' - '.str_replace('-','/',$datos->fecha_fin);
                $sucursal = SQL::obtenerValor("sucursales","nombre","codigo='".$codigo_sucursal."'");

                /*** Definición de pestañas general ***/
                $formularios["PESTANA_GENERAL"] = array(
                    array(
                        HTML::mostrarDato("sucursal",$textos["SUCURSAL"],$sucursal)
                    ),
                    array(
                        HTML::mostrarDato("modulo",$textos["MODULO"],$id_modulo)
                    ),
                    array(
                        HTML::mostrarDato("fecha",$textos["FECHA"],$fecha)
                    ),
                    array(
                        HTML::listaSeleccionSimple("estado",$textos["ESTADO"],$estados,$datos->estado,array("title"=>$textos["AYUDA_ESTADO"]))
                    )
                );
                $botones = array(
                    HTML::boton("botonAceptar", $textos["ACEPTAR"], "modificarItem('$url_id');", "aceptar")
                );

                $contenido = HTML::generarPestanas($formularios, $botones);
            } else {
                $error = $textos["USUARIO_SIN_PRIVILEGIOS"];
            }
        } else {
            $error = $textos["NO_EXISTEN_SUCURSALES"];
        }
    }

    /*** Enviar datos para la generacion del formulario al script que origino la peticion ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);


/*** Modificar el elemento seleccionado ***/
} elseif (!empty($forma_procesar)) {

    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_MODIFICADO"];

    $llave           = explode('|',$forma_id);
    $codigo_sucursal = $llave[0];
    $id_modulo       = $llave[1];
    $fecha_inicio    = $llave[2];
    $fecha_fin       = $llave[3];

    $datos = array (
        "estado" => $forma_estado
    );
    $modificar = SQL::modificar("periodos_contables", $datos, "codigo_sucursal='$codigo_sucursal' AND id_modulo='$id_modulo' AND fecha_inicio='$fecha_inicio' AND fecha_fin='$fecha_fin'");

    if (!$modificar) {
        $error   = true;
        $mensaje = $textos["ERROR_MODIFICAR_ITEM"];
    }

    /*** Enviar datos con la respuesta del proceso al script que origino la peticion ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

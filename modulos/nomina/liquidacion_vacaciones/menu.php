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
* de APTITUD PARA UN PROPÓITO DETERMINADO. Consulte los detalles de
* la Licencia Pública General GNU para obtener una información más
* detallada.
*
* Debería haber recibido una copia de la Licencia Pública General GNU
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
**/

/*** Nombre de la vista a partir de la cual se genera la tabla ***/
$vistaMenu      = "menu_movimiento_liquidacion_vacaciones";
$vistaBuscador  = "buscador_movimiento_liquidacion_vacaciones";
$alineacion     = array("I","I","C","I");

/*** Devolver datos para autocompletar la b�squeda ***/
if (isset($url_completar)) {
    echo SQL::datosAutoCompletar($vistaBuscador, $url_q);
    exit;
}


// Mostrar  sucursales de las cuales tiene privilegios el usuario
if ($sesion_usuario == $datosGlobales["usuarioMaestro"]){
    $sucursales = "";
} else {

    $tablas     = array(
        "a" => "perfiles_usuario",
        "b" => "componentes_usuario",
        "c" => "sucursales"
    );
    $columnas = array(
        "codigo" => "c.codigo"
    );
    $condicion = "c.codigo = a.codigo_sucursal AND a.id = b.id_perfil
                AND a.codigo_usuario = '".$sesion_codigo_usuario."'
                AND b.id_componente = '".$componente->id."'";

    $consulta = SQL::seleccionar($tablas, $columnas, $condicion);

    if (SQL::filasDevueltas($consulta)) {
        while ($datos = SQL::filaEnObjeto($consulta)) {
            $codigo_sucursal[] = $datos->codigo;
        }
        $sucursales = " AND id_sucursal IN (".implode(",", $codigo_sucursal).")";
    } else {
        $sucursales = " AND id_sucursal IS NULL";
    }
}

/*** Generar botones de comandos ***/
$botones  = HTML::boton("ADICVATO",$textos["ADICIONAR"],"ejecutarComando(this,670,480);","adicionar");
$botones .= HTML::boton("CONSVATO",$textos["CONSULTAR"],"ejecutarComando(this,650,400);","consultar");
$botones .= HTML::boton("AUTOPAGO",$textos["AUTORIZAR"],"ejecutarComando(this,670,480);","exportar");
$botones .= HTML::boton("LIQUPAGA",$textos["PAGAR"],"ejecutarComando(this,670,480);","exportar");
/*
$botones .= HTML::boton("MODIRENL",$textos["MODIFICAR"],"ejecutarComando(this,670,460);","modificar");
$botones .= HTML::boton("ELIMRENL",$textos["ELIMINAR"],"ejecutarComando(this,650,400);","eliminar");
$botones .= HTML::boton("LISTRENL",$textos["EXPORTAR"],"ejecutarComando(this,650,400);","exportar");*/

/*** Obtener el n�mero de la p�gina actual ***/
if (empty($url_pagina)) {
    $paginaActual = 1;
} else {
    $paginaActual = intval($url_pagina);
}

/*** Datos por defecto para realizar la consulta ***/
$condicion      = SQL::evaluarBusqueda($vistaBuscador, $vistaMenu);
$agrupamiento   = "";
$ordenamiento   = SQL::ordenColumnas("");
$numeroFilas    = SQL::$filasPorConsulta;
$columnas       = SQL::obtenerColumnas($vistaMenu);
$totalRegistros = SQL::filasDevueltas(SQL::seleccionar(array($vistaMenu), $columnas, $condicion.$sucursales, $agrupamiento, $ordenamiento));
$paginador      = HTML::insertarPaginador($totalRegistros, $paginaActual, $numeroFilas);
$registros      = HTML::imprimirRegistros($totalRegistros, $paginaActual, $numeroFilas);


$estados["ESTADO_"] = array(
    "1" => "estadoVerde",
    "2" => "estadoRojo",
    "3" => "estadoAzul",
    "4" => "estadoNaranja",
);


/*** Ejecutar la consulta y generar tabla a partir de los resultados ***/
$consulta     = SQL::seleccionar(array($vistaMenu), $columnas, $condicion.$sucursales, $agrupamiento, $ordenamiento, $paginaActual, $numeroFilas);
$tabla        = HTML::generarTabla($columnas,SQL::recursoEnArreglo($consulta, $estados),$alineacion);

/*** Generar y enviar plantilla completa si la petici�n no se realiza v�a AJAX ***/
if (empty($url_origen) || ($url_origen != "ajax")) {
    Plantilla::iniciar();
    Plantilla::sustituir("menu", $sesion_menu);
    Plantilla::sustituir("buscador", HTML::insertarBuscador());
    Plantilla::sustituir("botones", $botones);
    Plantilla::sustituir("paginador", $paginador);
    Plantilla::sustituir("registros", $registros);
    Plantilla::sustituir("mensaje");
    Plantilla::sustituir("bloqueDerecho");
    Plantilla::sustituir("bloqueIzquierdo", $tabla);
    Plantilla::sustituir("cuadroDialogo");
    Plantilla::enviarCodigo();
} else {
    /*** Devolver s�lo datos en formato JSON para las consultas v�a AJAX ***/
    $datos[0] = $tabla;
    $datos[1] = $paginador;
    $datos[2] = $registros;
    $datos[3] = $botones;
    HTTP::enviarJSON($datos);
}
?>

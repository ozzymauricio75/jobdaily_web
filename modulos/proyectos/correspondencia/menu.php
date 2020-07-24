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

/*** Nombre de la vista a partir de la cual se genera la tabla ***/
$vistaMenu     = "menu_correspondencia";
$vistaBuscador = "buscador_correspondencia";
$alineacion    = array("D","C","D","D","D","D","C","I","C","C","C","C");

/*** Devolver datos para autocompletar la busqueda ***/
if (isset($url_completar)) {
    echo SQL::datosAutoCompletar($vistaBuscador, $url_q);
    exit;
}

/*** Generar botones de comandos ***/
$botones  = HTML::boton("ADICCORR",$textos["ADICIONAR"],"ejecutarComando(this, 600, 450);","adicionar");
$botones .= HTML::boton("CONSCORR",$textos["CONSULTAR"],"ejecutarComando(this, 600, 450);","consultar");
$botones .= HTML::boton("MODICORR",$textos["MODIFICAR"],"ejecutarComando(this, 600, 450);","modificar");
$botones .= HTML::boton("ANULCORR",$textos["ANULAR"],"ejecutarComando(this, 600, 450);","anular");
$botones .= HTML::boton("ELIMCORR",$textos["ELIMINAR"],"ejecutarComando(this, 600, 450);","eliminar");
$botones .= HTML::boton("RECICORR",$textos["RECIBIR"],"ejecutarComando(this, 800, 550);","recibir");
$botones .= HTML::boton("REPOCORR",$textos["REPORTE"],"ejecutarComando(this, 650,420);","reporte");

/*** Obtener el número de la página actual ***/
if (empty($url_pagina)) {
    $paginaActual = 1;
} else {
    $paginaActual = intval($url_pagina);
}

/*** Datos por defecto para realizar la consulta ***/
$condicion      = SQL::evaluarBusqueda($vistaBuscador, $vistaMenu);
$agrupamiento   = "";
$ordenamiento   = SQL::ordenColumnas("CODIGO DESC");
$numeroFilas    = SQL::$filasPorConsulta;
$columnas       = SQL::obtenerColumnas($vistaMenu);
$totalRegistros = SQL::filasDevueltas(SQL::seleccionar(array($vistaMenu), $columnas, $condicion, $agrupamiento, $ordenamiento));
$paginador      = HTML::insertarPaginador($totalRegistros, $paginaActual, $numeroFilas);
$registros      = HTML::imprimirRegistros($totalRegistros, $paginaActual, $numeroFilas);

// Definir colores para los estados //
$estados["ESTADO_"] = array(
    "0" => "estadoVerde",
    "1" => "estadoAzul",
    "2" => "estadoRojo"
);

/*** Ejecutar la consulta y generar tabla a partir de los resultados ***/
$consulta     = SQL::seleccionar(array($vistaMenu), $columnas, $condicion, $agrupamiento, $ordenamiento, $paginaActual, $numeroFilas);
$tabla        = HTML::generarTabla($columnas, SQL::recursoEnArreglo($consulta, $estados), $alineacion);

/*** Generar y enviar plantilla completa si la petición no se realiza vía AJAX ***/
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
    /*** Devolver sólo datos en formato JSON para las consultas vía AJAX ***/
    $datos[0] = $tabla;
    $datos[1] = $paginador;
    $datos[2] = $registros;
    $datos[3] = $botones;
    HTTP::enviarJSON($datos);
}
?>

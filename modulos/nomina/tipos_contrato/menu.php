<?php

/**
*
* Copyright (C) 2008 Sistemas de Apoyo Empresarial Ltda
* 
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
$vistaMenu     = "menu_tipo_contrato";
$vistaBuscador = "buscador_tipo_contrato";
$alineacion    = array("C","I","I");

/*** Devolver datos para autocompletar la b?squeda ***/
if (isset($url_completar)) {
    echo SQL::datosAutoCompletar($vistaBuscador, $url_q);
    exit;
}

/*** Generar botones de comandos ***/
$botones  = HTML::boton("ADICTICT",$textos["ADICIONAR"],"ejecutarComando(this,380,400);","adicionar");
$botones .= HTML::boton("CONSTICT",$textos["CONSULTAR"],"ejecutarComando(this,350,320);","consultar");
$botones .= HTML::boton("MODITICT",$textos["MODIFICAR"],"ejecutarComando(this,380,400);","modificar");
$botones .= HTML::boton("ELIMTICT",$textos["ELIMINAR"],"ejecutarComando(this,350,320);","eliminar");
$botones .= HTML::boton("LISTTICT",$textos["EXPORTAR"],"ejecutarComando(this,350,320);","exportar");

/*** Obtener el número de la página actual ***/
if (empty($url_pagina)) {
    $paginaActual = 1;
} else {
    $paginaActual = intval($url_pagina);
}

/*** Datos por defecto para realizar la consulta ***/
$condicion      = SQL::evaluarBusqueda($vistaBuscador, $vistaMenu);
$agrupamiento   = "";
$ordenamiento   = SQL::ordenColumnas();
$numeroFilas    = SQL::$filasPorConsulta;
$columnas       = SQL::obtenerColumnas($vistaMenu);
$totalRegistros = SQL::filasDevueltas(SQL::seleccionar(array($vistaMenu), $columnas, $condicion, $agrupamiento, $ordenamiento));
$paginador      = HTML::insertarPaginador($totalRegistros, $paginaActual, $numeroFilas);
$registros      = HTML::imprimirRegistros($totalRegistros, $paginaActual, $numeroFilas);

/*** Ejecutar la consulta y generar tabla a partir de los resultados ***/

$terminos["TERMINO_"] = array(
    "1" => "estadoNeutro",
    "2" => "estadoNeutro",
    "3" => "estadoNeutro",
    "4" => "estadoNeutro"
);

$consulta = SQL::seleccionar(array($vistaMenu), $columnas, $condicion, $agrupamiento, $ordenamiento, $paginaActual, $numeroFilas);
$tabla    = HTML::generarTabla($columnas, SQL::recursoEnArreglo($consulta, $terminos), $alineacion);

/*** Generar y enviar plantilla completa si la peticiï¿½n no se realiza vï¿½a AJAX ***/
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

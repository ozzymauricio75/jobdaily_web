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

/*** Obtener el n�mero de la p�gina actual ***/
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

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
$vistaMenu     = "menu_aprobaciones";
$vistaBuscador = "buscador_aprobaciones";
$alineacion    = array("D","C","D","D","D","D","C","C","I","C","C","C");

/*** Devolver datos para autocompletar la busqueda ***/
if (isset($url_completar)) {
    echo SQL::datosAutoCompletar($vistaBuscador, $url_q);
    exit;
}

/*** Generar botones de comandos ***/
$botones  = HTML::boton("ADICAPRO",$textos["ADICIONAR"],"ejecutarComando(this, 650, 500);","adicionar");
$botones .= HTML::boton("CONSAPRO",$textos["CONSULTAR"],"ejecutarComando(this, 600, 550);","consultar");
$botones .= HTML::boton("MODIAPRO",$textos["MODIFICAR"],"ejecutarComando(this, 600, 500);","modificar");
$botones .= HTML::boton("ANULAPRO",$textos["ANULAR"],"ejecutarComando(this, 600, 550);","anular");
$botones .= HTML::boton("ELIMAPRO",$textos["ELIMINAR"],"ejecutarComando(this, 600, 550);","eliminar");
$botones .= HTML::boton("APREAPRO",$textos["APROBAR_RESIDENTE"],"ejecutarComando(this, 800, 550);","aprobar_residente");
$botones .= HTML::boton("APDIAPRO",$textos["APROBAR_DIRECTOR"],"ejecutarComando(this, 800, 550);","aprobar_director");

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
$estados["ESTADO_RESIDENTE_"] = array(
    "0" => "estadoAzul",
    "1" => "estadoVerde",
    "2" => "estadoRojo"
);
$estados["ESTADO_DIRECTOR_"] = array(
    "0" => "estadoAzul",
    "1" => "estadoVerde"
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

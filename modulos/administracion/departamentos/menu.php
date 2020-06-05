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

// Nombre de la vista a partir de la cual se genera la tabla
$vistaMenu     = "menu_departamentos";
$vistaBuscador = "buscador_departamentos";
$alineacion    = array("C","I","I");

// Devolver datos para autocompletar la busqueda
if (isset($url_completar)) {
    echo SQL::datosAutoCompletar($vistaBuscador, $url_q);
    exit;
}

// Generar botones de comandos
$botones  = HTML::boton("ADICDEPA",$textos["ADICIONAR"],"ejecutarComando(this, 380, 360);","adicionar");
$botones .= HTML::boton("CONSDEPA",$textos["CONSULTAR"],"ejecutarComando(this, 350, 270);","consultar");
$botones .= HTML::boton("MODIDEPA",$textos["MODIFICAR"],"ejecutarComando(this, 380, 360);","modificar");
$botones .= HTML::boton("ELIMDEPA",$textos["ELIMINAR"],"ejecutarComando(this, 350, 270);","eliminar");
$botones .= HTML::boton("LISTDEPA",$textos["EXPORTAR"],"ejecutarComando(this, 350, 270);","exportar");

// Obtener el numero de la pagina actual
if (empty($url_pagina)) {
    $paginaActual = 1;
} else {
    $paginaActual = intval($url_pagina);
}

// Datos por defecto para realizar la consulta
$condicion      = SQL::evaluarBusqueda($vistaBuscador, $vistaMenu);
$agrupamiento   = "";
$ordenamiento   = SQL::ordenColumnas("NOMBRE ASC");
$numeroFilas    = SQL::$filasPorConsulta;
$columnas       = SQL::obtenerColumnas($vistaMenu);
$totalRegistros = SQL::filasDevueltas(SQL::seleccionar(array($vistaMenu), $columnas, $condicion, $agrupamiento, $ordenamiento));
$paginador      = HTML::insertarPaginador($totalRegistros, $paginaActual, $numeroFilas);
$registros      = HTML::imprimirRegistros($totalRegistros, $paginaActual, $numeroFilas);

// Ejecutar la consulta y generar tabla a partir de los resultados
$consulta     = SQL::seleccionar(array($vistaMenu), $columnas, $condicion, $agrupamiento, $ordenamiento, $paginaActual, $numeroFilas);
$tabla        = HTML::generarTabla($columnas, $consulta, $alineacion);

// Generar y enviar plantilla completa si la peticion no se realiza via AJAX
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
    // Devolver solo datos en formato JSON para las consultas via AJAX
    $datos[0] = $tabla;
    $datos[1] = $paginador;
    $datos[2] = $registros;
    $datos[3] = $botones;
    HTTP::enviarJSON($datos);
}
?>

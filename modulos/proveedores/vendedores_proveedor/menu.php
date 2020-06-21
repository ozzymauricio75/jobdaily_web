<?php
/***
*
* Copyright (C) 2020 Raul Mauricio Oidor Lozano
* Raul Mauricio Oidor Lozano <ozzymauricio75@gmail.com>
*
* Este archivo es parte de:
* PANCE :: Software empresarial a la medida
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
***/

// Nombre de la vista a partir de la cual se genera la tabla
$vistaMenu     = "menu_vendedores_proveedor";
$vistaBuscador = "buscador_vendedores_proveedor";
$alineacion    = array("I","I","I","I");

// Devolver datos para autocompletar la busqueda
if (isset($url_completar)) {
    echo SQL::datosAutoCompletar($vistaBuscador, $url_q);
    exit;
}

// Generar botones de comandos
$botones  = HTML::boton("ADICVDPR",$textos["ADICIONAR"],"ejecutarComando(this,660,540);","adicionar");
$botones .= HTML::boton("CONSVDPR",$textos["CONSULTAR"],"ejecutarComando(this,550,400);","consultar");
$botones .= HTML::boton("MODIVDPR",$textos["MODIFICAR"],"ejecutarComando(this,680,450);","modificar");
$botones .= HTML::boton("ELIMVDPR",$textos["ELIMINAR"],"ejecutarComando(this,550,400);","eliminar");
//$botones .= HTML::boton("LISTCMPR",$textos["EXPORTAR"],"ejecutarComando(this,520,380);","exportar");

// Obtener el numero de la pagina actual
if (empty($url_pagina)) {
    $paginaActual = 1;
} else {
    $paginaActual = intval($url_pagina);
}

// Datos por defecto para realizar la consulta
$condicion      = SQL::evaluarBusqueda($vistaBuscador, $vistaMenu);
$agrupamiento   = "";
$ordenamiento   = SQL::ordenColumnas("NOMBRE_COMPLETO");
$numeroFilas    = SQL::$filasPorConsulta;
$columnas       = array("id","NOMBRE_COMPLETO","PROVEEDOR","DOCUMENTO","ACTIVO");
$totalRegistros = SQL::filasDevueltas(SQL::seleccionar(array($vistaMenu), $columnas, $condicion, $agrupamiento, $ordenamiento));
$paginador      = HTML::insertarPaginador($totalRegistros, $paginaActual, $numeroFilas);
$registros      = HTML::imprimirRegistros($totalRegistros, $paginaActual, $numeroFilas);
/*
// Definir colores para los estados //
$estados["ESTADO_"] = array(
    "0" => "estadoRojo",
    "1" => "estadoVerde"
);*/

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

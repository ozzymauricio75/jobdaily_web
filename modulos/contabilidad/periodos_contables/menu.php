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

// Mostrar movimientos de sucursales de las cuales tiene privilegios el usuario
if ($sesion_usuario == $datosGlobales["usuarioMaestro"]){
    $sucursales = "";
} else {

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
            $codigo_sucursal[] = $datos->codigo;
        }
        $sucursales = " AND id_sucursal IN (".implode(",", $codigo_sucursal).")";
    } else {
        $sucursales = " AND id_sucursal IS NULL";
    }
}

/*** Nombre de la vista a partir de la cual se genera la tabla ***/
$vistaMenu     = "menu_periodos_contables";
$vistaBuscador = "buscador_periodos_contables";
$alineacion    = array("I","I","C","C","I");

/*** Devolver datos para autocompletar la busqueda ***/
if (isset($url_completar)) {
    echo SQL::datosAutoCompletar($vistaBuscador, $url_q);
    exit;
}

/*** Generar botones de comandos ***/
$botones  = HTML::boton("ADICPECO",$textos["ADICIONAR"],"ejecutarComando(this,400,520);","adicionar");
$botones .= HTML::boton("CONSPECO",$textos["CONSULTAR"],"ejecutarComando(this,350,300);","consultar");
$botones .= HTML::boton("MODIPECO",$textos["MODIFICAR"],"ejecutarComando(this,400,520);","modificar");
$botones .= HTML::boton("ELIMPECO",$textos["ELIMINAR"],"ejecutarComando(this,350,300);","eliminar");

/*** Obtener el numero de la pagina actual ***/
if (empty($url_pagina)) {
    $paginaActual = 1;
} else {
    $paginaActual = intval($url_pagina);
}

/*** Datos por defecto para realizar la consulta ***/
$condicion      = SQL::evaluarBusqueda($vistaBuscador, $vistaMenu);
$agrupamiento   = "";
$ordenamiento   = SQL::ordenColumnas("FECHA_INICIO DESC");
$numeroFilas    = SQL::$filasPorConsulta;
$columnas       = SQL::obtenerColumnas($vistaMenu);
$totalRegistros = SQL::filasDevueltas(SQL::seleccionar(array($vistaMenu), $columnas, $condicion.$sucursales, $agrupamiento, $ordenamiento));
$paginador      = HTML::insertarPaginador($totalRegistros, $paginaActual, $numeroFilas);
$registros      = HTML::imprimirRegistros($totalRegistros, $paginaActual, $numeroFilas);

$estados["ESTADO_"] = array(
    "0" => "estadoRojo",
    "1" => "estadoVerde",
    "2" => "estadoNaranja"
);



/*** Ejecutar la consulta y generar tabla a partir de los resultados ***/
$consulta     = SQL::seleccionar(array($vistaMenu), $columnas, $condicion.$sucursales, $agrupamiento, $ordenamiento, $paginaActual, $numeroFilas);
$tabla        = HTML::generarTabla($columnas, SQL::recursoEnArreglo($consulta, $estados), $alineacion);

/*** Generar y enviar plantilla completa si la peticion no se realiza via AJAX ***/
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
    /*** Devolver solo datos en formato JSON para las consultas via AJAX ***/
    $datos[0] = $tabla;
    $datos[1] = $paginador;
    $datos[2] = $registros;
    $datos[3] = $botones;
    HTTP::enviarJSON($datos);
}
?>

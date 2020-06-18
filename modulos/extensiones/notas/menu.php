<?php

/**
*
* Copyright (C) 2020 Jobdaily
* Raul Mauricio Oidor Lozano <ozzymauricio75@gmail.com>
*
* Este archivo es parte de:
* Jobdaily :: Software empresarial a la medida
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
$vistaMenuNotas     = "menu_notas";
//$vistaBuscador = "buscador_notas";
$alineacionNotas    = array("I", "I");

/*** Datos por defecto para realizar la consulta ***/
$condicionNotas      = "id_usuario = $sesion_id_usuario";
$agrupamientoNotas   = "";
$ordenamientoNotas   = SQL::ordenColumnas();
$numeroFilas    	 = SQL::$filasPorConsulta;
$columnasNotas       = SQL::obtenerColumnas($vistaMenuNotas);

/*** Ejecutar la consulta y generar tabla a partir de los resultados ***/
$consultaNotas  = SQL::seleccionar(array($vistaMenuNotas), $columnasNotas, $condicionNotas, $agrupamientoNotas, $ordenamientoNotas);
$tablaNotas	    = HTML::generarTabla($columnasNotas, $consultaNotas, $alineacionNotas);
				/*** Generar botones de comandos ***/
$botonesNotas   = HTML::boton("ADICNOTA",$textos["ADICIONAR"],"ejecutarAccion(this, 360, 280);","adicionar").
				  HTML::boton("CONSNOTA",$textos["CONSULTAR"],"ejecutarAccion(this, 350, 280);","consultar").
				  HTML::boton("MODINOTA",$textos["MODIFICAR"],"ejecutarAccion(this, 350, 235);","modificar").
				  HTML::boton("ELIMNOTA", $textos["ELIMINAR"],"ejecutarAccion(this, 350, 250);", "eliminar");
?>
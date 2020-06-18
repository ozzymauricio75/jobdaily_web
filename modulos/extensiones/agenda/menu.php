<?php

/**
*
* Copyright (C) 2020 Jobdaily
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
* SIN GARANÍA ALGUNA; ni siquiera la garantía implícita MERCANTIL o
* de APTITUD PARA UN PROPOSITO DETERMINADO. Consulte los detalles de
* la Licencia Pública General GNU para obtener una información más
* detallada.
*
* Debería haber recibido una copia de la Licencia Pública General GNU
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
**/

/*** Generar el arreglo que se visualiza en pantalla ***/
$plantillaAgenda = array();
$doceHoras = 12;

for ($contadorHora = 7; $contadorHora <= 20; $contadorHora++){
	if ($contadorHora < 12){
		$hora      = $contadorHora;
		$meridiano = " AM";
	} else {
		$doceHoras = 12;
		if ($contadorHora == 12) $doceHoras = 0;
		$hora      = $contadorHora - $doceHoras;
		$meridiano = " PM";
	}

	$plantillaAgenda[$contadorHora."00"] = array(NULL, $hora.":00 $meridiano", "");
}

/*** Obtener fecha de la consulta ***/
if (empty($url_fecha)) {
	$fechaActual = "CURDATE()";
	//echo $fechaActual;
    
} else {
	$fechaActual = $url_fecha;
	
}

Sesion::registrar("fechaActual", $fechaActual);

/*** Nombre de la vista a partir de la cual se genera la tabla *//**/
$vistaMenuAgenda     = "menu_agenda";
//$vistaBuscador = "buscador_agenda";
$alineacionAgenda    = array("D","I");

/*** Datos por defecto para realizar la consulta ***/
$condicionAgenda      = "codigo_usuario = '$sesion_id_usuario' AND id_fecha = '$sesion_fechaActual'";
$agrupamientoAgenda   = "";
$ordenamientoAgenda   = SQL::ordenColumnas();
$numeroFilas    	  = SQL::$filasPorConsulta;
$columnasAgenda       = SQL::obtenerColumnas($vistaMenuAgenda);

/*** Ejecutar la consulta y generar tabla a partir de los resultados ***/
$consultaAgenda     = SQL::seleccionar(array($vistaMenuAgenda), $columnasAgenda, $condicionAgenda, $agrupamientoAgenda, $ordenamientoAgenda);

/*** Combina Dos arrays para generar la tabla de la agenda ***/
$eventosUsuario = array();
while ($datos = SQL::filaEnArreglo($consultaAgenda)) {

	$indice		 = $datos[0];
	$horaNormal	 = $datos[1];
	$evento		 = $datos[2];
	$usuario	 = $datos[3];

	$meridiano 	 = substr($horaNormal, -2);
	$horaMilitar = substr($horaNormal, 0, -3);

	if($meridiano == "AM"){
		$horaMilitar = str_replace(":", "", $horaMilitar);
	} else {
		$hora	   = substr($horaMilitar, 0, strpos($horaMilitar, ":"));
		if ($hora < 12) $hora += 12;
		$horaMilitar = $hora . substr($horaMilitar, strpos($horaMilitar, ":") + 1, 2);
	}
	
	$eventosUsuario[$horaMilitar] = array($indice, $horaNormal, $evento);

}
$plantillaAgenda = $eventosUsuario + $plantillaAgenda;
ksort($plantillaAgenda);

/*** Generar la tabla ***/
$tablaAgenda  = HTML::generarTabla($columnasAgenda, $plantillaAgenda, $alineacionAgenda);

/*** Generar botones de comandos ***/
$botonesAgenda = HTML::boton("ADICAGEN",$textos["ADICIONAR"],"ejecutarAccion(this, 480, 330);","adicionar").
				 HTML::boton("CONSAGEN",$textos["CONSULTAR"],"ejecutarAccion(this, 350, 260);","consultar").
				 HTML::boton("MODIAGEN",$textos["MODIFICAR"],"ejecutarAccion(this, 480, 390);","modificar").
				 HTML::boton("ELIMAGEN",$textos["ELIMINAR"],"ejecutarAccion(this, 350, 330);","eliminar");

if (!empty($url_recargar)) {
	$respuesta    = array();
    //$respuesta[0] = $error;
    //$respuesta[1] = $mensaje;
    $respuesta[2] = $tablaAgenda;
    HTTP::enviarJSON($respuesta);
}

?>

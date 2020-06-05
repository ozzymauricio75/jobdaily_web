<?php

/**
*
* Copyright (C) 2020 Jobdaily
* Raul Mauricio Oidor Lozano <ozzymauricio75@gmail.com>
* Edier Andr�s Villaneda N. <eandres164@gmail.com>
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


/***********************************Codigo De la pesta�a Agenda *************************************/
//include($rutasGlobales["extensiones"]."/agenda/menu.php");
/****************************************************************************************************/
/******************************Codigo de la pesta�a notas********************************************/
//include($rutasGlobales["extensiones"]."/notas/menu.php");
/****************************************************************************************************/


	$idActual = $componente->id;
/*** Definici�n pesta�a de agenda ***/
//    $formularios["GESTAGEN"] = array(
//        array(
//        	HTML::contenedor("", array("id" => "mesAgenda")),
//       	HTML::contenedor($tablaAgenda, array("id" => "tablaAgenda", "align" => "center")).
//			HTML::contenedor($botonesAgenda, array("id" => "botonesAgenda", "align" => "center"))
//        ),
//        array(
//        	HTML::contenedor("", array("id" => "progressbar"))
//        )
//    );

	/*** Definici�n pesta�a de notas ***/
//      $formularios["GESTNOTA"] = array(
//      	array(
// 			HTML::contenedor($tablaNotas, array("id" => "nota", "align" => "center"))
//          ),
// 		 array(
// 		 	HTML::contenedor($botonesNotas, array("id" => "botonesNota", "align" => "center"))
// 		 )
//      );


//	$componente = new Componente($idActual);
//  $contenido = HTML::generarPestanas($formularios, "", array("id" => "pestanaMenuPrincipal"));


Plantilla::iniciar();
Plantilla::sustituir("menu", HTML::arbolComponentes());
Plantilla::sustituir("buscador");
Plantilla::sustituir("usuario_ingreso",$sesion_usuario);
Plantilla::sustituir("registros");
Plantilla::sustituir("paginador");
Plantilla::sustituir("botones");
Plantilla::sustituir("mensaje");
Plantilla::sustituir("registros");
Plantilla::sustituir("bloqueDerecho");
Plantilla::sustituir("bloqueIzquierdo");//, $contenido);
Plantilla::sustituir("cuadroDialogo");
Plantilla::enviarCodigo();
?>

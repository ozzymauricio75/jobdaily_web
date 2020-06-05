<?php

/**
*
* Copyright (C) 2020 Jobdaily
* Raul Mauricio Oidor Lozano <ozzymauricio75@gmail.com>
* Edier Andrés Villaneda N. <eandres164@gmail.com>
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


/***********************************Codigo De la pestaña Agenda *************************************/
//include($rutasGlobales["extensiones"]."/agenda/menu.php");
/****************************************************************************************************/
/******************************Codigo de la pestaña notas********************************************/
//include($rutasGlobales["extensiones"]."/notas/menu.php");
/****************************************************************************************************/


	$idActual = $componente->id;
/*** Definición pestaña de agenda ***/
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

	/*** Definición pestaña de notas ***/
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

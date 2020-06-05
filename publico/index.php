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

/*** Incluir archivo de configuraci�n principal ***/
require "../configuracion/global.php";
require "../configuracion/sincronizacion.php";

/*** Incluir archivos de clases globales ***/
require_once $rutasGlobales["clases"]."/sql.php";
require_once $rutasGlobales["clases"]."/http.php";
require_once $rutasGlobales["clases"]."/sesion.php";
require_once $rutasGlobales["clases"]."/componente.php";
require_once $rutasGlobales["clases"]."/plantilla.php";
require_once $rutasGlobales["clases"]."/codigohtml.php";
require_once $rutasGlobales["clases"]."/cadena.php";
require_once $rutasGlobales["clases"]."/archivo.php";
require_once $rutasGlobales["clases"]."/arreglo.php";
require_once $rutasGlobales["clases"]."/pdf.php";

//require_once $rutasGlobales["clases"]."/cheque.php";
HTTP::iniciar();
HTTP::evitarCache();
HTTP::exportarVariables();
SQL::abrirConexion();
Sesion::iniciar();

/*** Verificar que se haya solicitado un componente ***/
if (!empty($_GET[HTTP::$variableComponente])) {
    $componenteSolicitado = $_GET[HTTP::$variableComponente];
}

/*** Comprobar la existencia de las principales variables de sesion, si no existen, abrir m�dulo de inicio de sesi�n ***/
if (empty($sesion_usuario) || empty($sesion_contrasena) || empty($sesion_cliente) || empty($componenteSolicitado)) {
    $componenteSolicitado = HTTP::$componenteInicial;

/*** Confrontar la direcci�n IP registrada en la sesi�n con la direcci�n IP actual, si son diferentes enviar a una nueva sesi�n ***/
} elseif (HTTP::$cliente != $sesion_cliente) {
    $componenteSolicitado = HTTP::$componenteInicial;

/*** Verificar que exista el usuario y que su contrase�a sea correcta ***/
} elseif (!SQL::existeItem("usuarios", "usuario", $sesion_usuario, "contrasena='$sesion_contrasena'")) {
    $componenteSolicitado = HTTP::$componenteInicial;
    Sesion::terminar();
}

/*** Crear objeto componente ***/
$componente = new Componente($componenteSolicitado);

/*** Cargar sin verificaci�n si se trata de un componente global ***/
if ($componente->global) {
    if ($componente->archivoLegible) {
        include $componente->ruta;
    }

/*** Cargar si el usuario tiene acceso al componente ***/
} elseif ($componente->usuarioPermitido()) {
    if ($componente->archivoLegible) {
        include $componente->ruta;
    }
}

?>

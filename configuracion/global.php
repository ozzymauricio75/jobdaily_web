<?php
/**
*
* Copyright (C) 2020 Jobdaily
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

// Definicion de datos propios de la aplicacion
$sem["nombreCliente"]    = "HG INGENIERIA Y CONSTRUCCIÓN S.A.S.";
$sem["nitCliente"]       = "NIT 900.694.164-1";
$sem["direccionCliente"] = "CARRERA 64a # 5-30 Cali(V)";
$sem["telefonoCliente"]  = "Telefono 4003816";
$sem["nombre"]           = "Dailyjob";
$sem["descripcion"]      = "Software empresarial a la medida";
$sem["version"]          = "0.0.1";
$sem["url"]              = "http://localhost";
$sem["creador"]          = "Dailyjob";
$sem["urlCreador"]       = "http://www.Jobdaily.co";
$sem["correoCreador"]    = "desarrollo@Jobdaily.co";

// Definicion de datos para el acceso a la base datos
$accesoBaseDatos["servidor"]         = "localhost";
$accesoBaseDatos["nombre"]           = "jobdaily";
$accesoBaseDatos["usuario"]          = "jobdaily";
$accesoBaseDatos["contrasena"]       = "Jobdaily2020.+";
$accesoBaseDatos["prefijoTabla"]     = "job";
$accesoBaseDatos["filasPorConsulta"] = 20;

// Definicion de otros datos globales
$datosGlobales["servidorPrincipal"]      = true;
$datosGlobales["usuarioMaestro"]         = "admin";
$datosGlobales["componenteInicioSesion"] = "MENUINSE";
$datosGlobales["componentePaginaInicio"] = "MENUPRIN";
$datosGlobales["variableComponente"]     = "componente";
$datosGlobales["idioma"]                 = "es";
$datosGlobales["zonaHorario"]            = "America/Bogota";

// Definicion de rutas de los principales directorios
$rutasGlobales["modulos"]     = "../modulos";
$rutasGlobales["extensiones"] = $rutasGlobales["modulos"] ."/extensiones";
$rutasGlobales["idiomas"]     = "../idiomas";
$rutasGlobales["clases"]      = "../clases";
$rutasGlobales["plantillas"]  = "../plantillas";
$rutasGlobales["temporal"]    = "../temporal";
$rutasGlobales["javascript"]  = "javascript";
$rutasGlobales["imagenes"]    = "imagenes";
$rutasGlobales["estilos"]     = "css";
$rutasGlobales["archivos"]    = "../archivos";

// Definicion de directorios por modulo o componente
$rutasComponente["idiomas"]    = "idiomas";
$rutasComponente["clases"]     = "clases";
$rutasComponente["javascript"] = "javascript";
$rutasComponente["sql"]        = "sql";

// Archivos de JavaScript
$rutasJavaScript["global"]        = $rutasGlobales["javascript"]."/global.js";
$rutasJavaScript["formaPago"]     = $rutasGlobales["javascript"]."/formasPago.js";
$rutasJavaScript["principal"]     = $rutasGlobales["javascript"]."/jquery.js";
$rutasJavaScript["interfaz"]      = $rutasGlobales["javascript"]."/jquery.ui.js";
$rutasJavaScript["tablas"]        = $rutasGlobales["javascript"]."/jquery.tablesorter.js";
$rutasJavaScript["marcafila"]     = $rutasGlobales["javascript"]."/jquery.tablehover.js";
$rutasJavaScript["formularios"]   = $rutasGlobales["javascript"]."/jquery.form.js";
$rutasJavaScript["menu"]          = $rutasGlobales["javascript"]."/jquery.menu.js";
$rutasJavaScript["tips"]          = $rutasGlobales["javascript"]."/jquery.tooltip.js";
$rutasJavaScript["bloqueador"]    = $rutasGlobales["javascript"]."/jquery.blockui.js";
$rutasJavaScript["completar"]     = $rutasGlobales["javascript"]."/jquery.autocomplete.js";
$rutasJavaScript["dimension"]     = $rutasGlobales["javascript"]."/jquery.dimensions.js";
$rutasJavaScript["arbolSimple"]   = $rutasGlobales["javascript"]."/jquery.treeview.js";
$rutasJavaScript["arbolMultiple"] = $rutasGlobales["javascript"]."/jquery.checkboxtree.js";
$rutasJavaScript["media"]         = $rutasGlobales["javascript"]."/jquery.media.js";
$rutasJavaScript["metadata"]      = $rutasGlobales["javascript"]."/jquery.metadata.js";
$rutasJavaScript["hotkeys"]       = $rutasGlobales["javascript"]."/jquery.hotkeys.js";
$rutasJavaScript["png"]           = $rutasGlobales["javascript"]."/jquery.fixpng.js";
$rutasJavaScript["javascript"]    = $rutasGlobales["javascript"]."/jquery.maskedinput-1.2.2.min.js";
$rutasJavaScript["idiomaFecha"]   = $rutasGlobales["javascript"]."/i18n/ui.datepicker-".$datosGlobales["idioma"].".js";

// Definicion de archivos globales
$archivosGlobales["cssGeneral"]   = $rutasGlobales["estilos"]."/celeste/global.css";
$archivosGlobales["cssExplorer6"] = $rutasGlobales["estilos"]."/celeste/explorer6.css";
$archivosGlobales["cssExplorer7"] = $rutasGlobales["estilos"]."/celeste/explorer7.css";
$archivosGlobales["esquemaSQL"]   = $rutasComponente["sql"]."/esquema.php";

// Definicion de rutas de imagenes
$imagenesGlobales["logoAplicacion"]      = $rutasGlobales["imagenes"]."/logo-aplicacion.png";
$imagenesGlobales["logoCliente"]         = $rutasGlobales["imagenes"]."/logo-cliente.png";
$imagenesGlobales["logoClienteReportes"] = $rutasGlobales["imagenes"]."/logo-cliente-reportes.jpg";
$imagenesGlobales["inicioSesion"]        = $rutasGlobales["imagenes"]."/llaves.png";
$imagenesGlobales["cargando"]            = $rutasGlobales["imagenes"]."/cargando.png";
$imagenesGlobales["buscar"]              = $rutasGlobales["imagenes"]."/buscar.png";
$imagenesGlobales["restaurar"]           = $rutasGlobales["imagenes"]."/restaurar.png";
$imagenesGlobales["adicionar"]           = $rutasGlobales["imagenes"]."/adicionar.png";
$imagenesGlobales["existente"]           = $rutasGlobales["imagenes"]."/modificar.png";
$imagenesGlobales["consultar"]           = $rutasGlobales["imagenes"]."/consultar.png";
$imagenesGlobales["modificar"]           = $rutasGlobales["imagenes"]."/modificar.png";
$imagenesGlobales["eliminar"]            = $rutasGlobales["imagenes"]."/eliminar.png";
$imagenesGlobales["anular"]              = $rutasGlobales["imagenes"]."/anular.png";
$imagenesGlobales["enviar"]              = $rutasGlobales["imagenes"]."/enviar.png";
$imagenesGlobales["guardar"]             = $rutasGlobales["imagenes"]."/guardar.png";
$imagenesGlobales["aceptar"]             = $rutasGlobales["imagenes"]."/aceptar.png";
$imagenesGlobales["cancelar"]            = $rutasGlobales["imagenes"]."/cancelar.png";
$imagenesGlobales["regresar"]            = $rutasGlobales["imagenes"]."/regresar.png";
$imagenesGlobales["exportar"]            = $rutasGlobales["imagenes"]."/exportar.png";
$imagenesGlobales["cruzar"]              = $rutasGlobales["imagenes"]."/exportar.png";
$imagenesGlobales["anterior"]            = $rutasGlobales["imagenes"]."/anterior.png";
$imagenesGlobales["ultima"]              = $rutasGlobales["imagenes"]."/ultima.png";
$imagenesGlobales["siguiente"]           = $rutasGlobales["imagenes"]."/siguiente.png";
$imagenesGlobales["primera"]             = $rutasGlobales["imagenes"]."/primera.png";
$imagenesGlobales["requerido"]           = $rutasGlobales["imagenes"]."/requerido.png";
$imagenesGlobales["requerido_tabla"]     = $rutasGlobales["imagenes"]."/requerido_tabla.png";
$imagenesGlobales["imprimir"]            = $rutasGlobales["imagenes"]."/imprimir.png";

// Rutas
$plantillaGlobal["ruta"]         = $rutasGlobales["plantillas"]."/original.htm";
$plantillaGlobal["codificacion"] = "iso-8859-1";

// Definicion de parametros propios del lenguaje y/o del servidor web
ini_set("display_errors", "1");
ini_set("default_charset", $plantillaGlobal["codificacion"]);
ini_set("session.auto_start", "0");
ini_set("session.name", "ISP");
ini_set("session.save_path", $rutasGlobales["temporal"]."/sesiones");
ini_set("session.use_trans_sid", "0");
ini_set("session.gc_maxlifetime", "360000");
?>

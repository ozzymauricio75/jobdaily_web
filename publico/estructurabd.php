<?php

/**
*
* Copyright (C) 2020 Jobdaily
* Raul Mauricio Oidor Lozano <ozzymauricio75@gmail.com>
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

/*** Resaltar con colores los elementos de una cadena de SQL ***/
function colorearSQL ($cadena) {
    /*** Palabras clave ***/
    $palabras[] = "create";
    $palabras[] = "table";
    $palabras[] = "primary";
    $palabras[] = "unique";
    $palabras[] = "key";

    /*** Tipos de datos ***/
    $tipos[] = "int";
    $tipos[] = "smallint";
    $tipos[] = "bigint";
    $tipos[] = "tinyint";
    $tipos[] = "char";
    $tipos[] = "varchar";
    $tipos[] = "enum";

    $cadena = preg_replace("|\n  |","\n&nbsp;&nbsp;&nbsp;&nbsp;",$cadena);
    $cadena = preg_replace("|(?:`([a-zA-Z0-9_]{1,255}?)`)|","<span class=\"nombre\">\\0</span>",$cadena);
    $cadena = preg_replace("|(?:'([a-zA-Z0-9_]{1,255}?)')|","<span class=\"valor\">\\0</span>",$cadena);
    $cadena = preg_replace("|(?:\(([a-zA-Z0-9_]{1,255}?)\))|","<span class=\"longitud\">\\0</span>",$cadena);
    $cadena = preg_replace("|\(|","<b>\\0</b>",$cadena);
    $cadena = preg_replace("|\)|","<b>\\0</b>",$cadena);
    $cadena = nl2br($cadena);
    return $cadena.";";
}

/*** Variables para la conexión a la base de datos ***/
$baseDatos["servidor"]   = "localhost";
$baseDatos["nombre"]     = "enfriar";
$baseDatos["usuario"]    = "enfriar";
$baseDatos["contrasena"] = "3nfr14rS4e2011.";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es">
  <head>
    <meta name="Description" content="Análisis de la base de datos '<?php echo $baseDatos["nombre"]; ?>' en '<?php echo $baseDatos["servidor"]; ?>'" />
    <meta name="Keywords" content="software libre, software de código abierto, código abierto, open source, free software, linux, Cali, Santiago de Cali, Valle, Valle del Cauca, Colombia, MySQL, análisis" />
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <meta name="Distribution" content="Global" />
    <meta name="Author" content="Francisco J. Lozano B. :: fjlozano@felinux.com.co" />
    <meta name="Robots" content="index,follow" />

    <title>Análisis de la base de datos '<?php echo $baseDatos["nombre"]; ?>' en '<?php echo $baseDatos["servidor"]; ?>'</title>
    <style type="text/css">
      body {
        background: #ffffff;
        color: #000000;
        font-family: "Trebuchet MS", "Bitstream Vera Sans", Arial, Sans-serif;
        font-size: 9pt;
        text-align: center;
      }

      a {
        color: #5476C0;
        font-weight: bold;
        text-decoration: none;
      }

      a:hover {
        color: #5476C0;
        font-weight: bold;
        text-decoration: none;
      }

      .tabla {
        position: relative;
        margin: 5px;
        padding: 5px;
        width: 98%;
        border: solid 1px #c0c0c0;
        background: #f0f0f0;
        text-align: left;
        font-family: monospace;
      }

      .nombre {
        color: #990000;
      }

      .valor {
        color: #006600;
      }

      .longitud {
        color: #0000aa;
      }
    </style>
  </head>
  <body>
<?php

/*** Establecer conexión con el motor de bases de datos y abrir la base de datos requerida ***/
$conexion = mysql_connect($baseDatos["servidor"], $baseDatos["usuario"], $baseDatos["contrasena"])
            or die("Imposible conectar: ".mysql_error());
$apertura = mysql_select_db($baseDatos["nombre"])
            or die("Imposible abrir la base de datos ".$baseDatos["nombre"].".");

if (!isset($_REQUEST["tabla"])) {
    /*** Obtener listado de las tablas de la base de datos para ejecutar el análisis ***/
    $consulta = mysql_query("SHOW TABLES");
    echo "<div class='tabla'>\n";
    echo "<h3>Tablas de '".$baseDatos["nombre"]."' en '".$baseDatos["servidor"]."'</h3>";
    echo "<div><a href=\"".$_SERVER["PHP_SELF"]."?tabla=0\">Ver todas</a></div>";
    echo "<ol>\n";
    while ($datos = mysql_fetch_array($consulta)) {
        echo "<li><a href=\"".$_SERVER["PHP_SELF"]."?tabla=".$datos[0]."\">".$datos[0]."</a></li>";
    }
    echo "</ol>\n";
    echo "</div>\n";
} else {
    if (!get_magic_quotes_gpc()) {
        $tabla = addslashes($_REQUEST["tabla"]);
    } else {
        $tabla = $_REQUEST["tabla"];
    }

    /*** Mostrar una tabla específica ***/
    if ($tabla) {
        echo "<div class='tabla'>\n";
        $consulta = mysql_query("SHOW CREATE TABLE $tabla");
        while ($datos = mysql_fetch_array($consulta)) {
            echo colorearSQL($datos[1]);
        }
        echo "<br><br><div align=\"right\"><a href=\"".$_SERVER["PHP_SELF"]."\">[ Regresar ]</a></div>";
        echo "</div>\n";
    /*** Mostrar todas las tablas ***/
    } else {
        $consulta = mysql_query("SHOW TABLES");
        while ($datos = mysql_fetch_array($consulta)) {
            echo "<div class='tabla'>\n";
            $consulta2 = mysql_query("SHOW CREATE TABLE ".$datos[0]);
            while ($datos2 = mysql_fetch_array($consulta2)) {
                echo colorearSQL($datos2[1]);
            }
            echo "</div>\n";
        }
        echo "<br><br><div align=\"right\"><a href=\"".$_SERVER["PHP_SELF"]."\">[ Regresar ]</a></div>";
    }
}
?>
  </body>
</html>

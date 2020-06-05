<?php

/*
 *
 * Variables para la conexión a la base de datos
 *
 */
$archivoRespaldo   = "/tmp/datos-e2-".date("Ymdhis").".sql";
$servidorBaseDatos = "localhost";
$nombreBaseDatos   = "e2";
$usuarioBaseDatos  = "e2";
$claveBaseDatos    = "e2";
/*
 * Inicio
 *
 */
$conexion     = abrirConexionBD();
$baseDatos    = seleccionarBD($nombreBaseDatos);

$archivo = fopen($archivoRespaldo, "w");

$contenido    = "--\n";
$contenido   .= "-- Autor: Francisco J. Lozano B. <pacho@felinux.com.co>\n";
$contenido   .= "--\n";
$contenido   .= "-- MySQL ".mysql_get_server_info()."\n\n";
$contenido   .= "-- Inicio: ".date("Y-m-d G:i:s A", time())."\n\n";
$contenido   .= "/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;\n";
$contenido   .= "/*!40103 SET TIME_ZONE='+00:00' */;\n";
$contenido   .= "/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;\n";
$contenido   .= "/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;\n";
$contenido   .= "/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;\n";
$contenido   .= "/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;\n";
$contenido   .= "\n\n";

$escribe      = fputs($archivo, $contenido);

$consulta  = "SHOW TABLES";
$resultado = consultaBD($consulta);

while ($datos = filaEnArreglo($resultado)) {
    $tabla      = $datos[0];
    $contenido  = "DROP TABLE IF EXISTS $tabla;\n";
    $contenido .= "/*!40101 SET @saved_cs_client     = @@character_set_client */;\n";
    $contenido .= "/*!40101 SET character_set_client = utf8 */;\n";
    $consulta2  = "SHOW CREATE TABLE $tabla";
    $resultado2 = consultaBD($consulta2);
    $datos2     = filaEnArreglo($resultado2);
    $contenido .= $datos2[1].";\n";
    $contenido .= "/*!40101 SET character_set_client = @saved_cs_client */;\n\n";

    if (!preg_match("/CREATE ALGORITHM/", $datos2[1])) {
        $consulta3  = "SELECT * FROM $tabla";
        $resultado3 = consultaBD($consulta3);

        $contenido .= "LOCK TABLES $tabla WRITE;\n";
        $contenido .= "/*!40000 ALTER TABLE $tabla DISABLE KEYS */;\n";

        while ($datos3 = filaEnObjeto($resultado3)) {
            $objeto   = get_object_vars($datos3);
            $columnas = implode(",", array_keys($objeto));
            $valores  = array_values($objeto);

            foreach ($valores as $llave => $valor) {
                $valores[$llave] = "'".mysql_real_escape_string(stripslashes($valor))."'";
            }

            $valores   = implode(",", $valores);
            $contenido .= "INSERT INTO $tabla($columnas) VALUES($valores);\n";
        }

        $contenido .= "/*!40000 ALTER TABLE $tabla ENABLE KEYS */;\n";
        $contenido .= "UNLOCK TABLES;\n";
        $contenido .= "\n";
    }

    $escribe    = fputs($archivo, $contenido);
}

$contenido  = "/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;\n";
$contenido .= "/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;\n";
$contenido .= "/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;\n";
$contenido .= "/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;\n";
$contenido .= "/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;\n\n";
$contenido .= "-- Fin: ".date("Y-m-d G:i:s A", time())."\n";


$escribe = fputs($archivo, $contenido);
$cierra  = fclose($archivo);

echo "Ok";


/*
 * Funciones de MySQL
 *
 */

/*** Abrir una conexión a la base de datos ***/
function abrirConexionBD() {
    global $servidorBaseDatos, $usuarioBaseDatos, $claveBaseDatos;

    $conexion = mysql_connect($servidorBaseDatos, $usuarioBaseDatos, $claveBaseDatos);
    return $conexion;
}

/*** Cerrar una conexión establecida con la base de datos ***/
function cerrarConexionBD($conexion) {
    $cierre = mysql_close($conexion);
    return $cierre;
}

/*** Ejecutar una consulta en la base de datos ***/
function consultaBD($consulta) {
    $resultado = mysql_query($consulta);
    return $resultado;
}

/*** Ejecutar una consulta en la base de datos ***/
function ultimoId() {
    $id = mysql_insert_id();
    return $id;
}

/*** Seleccionar una base de datos ***/
function seleccionarBD($baseDatos) {
    $resultado = mysql_select_db($baseDatos);
    return $resultado;
}

/*** Retornar el numero de filas devueltas por la consulta dada ***/
function filasDevueltas($resultado) {
    $filas = mysql_num_rows($resultado);
    return $filas;
}

/*** Retornar el numero de filas afectadas por la consulta dada ***/
function filasAfectadas($conexion) {
    $filas = mysql_affected_rows($conexion);
    return $filas;
}

/*** Convertir los datos de una fila resultado de una consulta en objeto ***/
function filaEnObjeto($resultado) {
    $fila = mysql_fetch_object($resultado);
    return $fila;
}

/*** Convertir los datos de una fila resultado de una consulta en arreglo ***/
function filaEnArreglo($resultado) {
    $fila = mysql_fetch_array($resultado);
    return $fila;
}
?>

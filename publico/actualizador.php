<?php
    /*** Los registros de la tabla de servidores deben ser exactamente los mismos en todos los almacenes ***/

    /*** Definiir el ID del servidor principal en la tabla ***/
    $idServidorPrincipal = 1;

    /*** Definiir el ID del servidor del almacen local en la tabla ***/
    $idServidorLocal = 1;

    /*** En cada servidor se debe crear este usuario con los privilegios SELECT, INSERT, UPDATE y DELETE ***/
    $accesoRemoto["usuario"]    = "electro";
    $accesoRemoto["contrasena"] = "electro";

    /*** Incluir archivo de configuración principal ***/
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

    HTTP::iniciar();
    HTTP::evitarCache();
    HTTP::exportarVariables();
    SQL::abrirConexion();
    Sesion::iniciar();
    ?>
    <pre>
    <?

    /*** Solo el servidor principal se conecta a los otros servidores (almacenes) para traer los datos a sincronizar ***/
    if ($datosGlobales["servidorPrincipal"]) {

        /*** Obtener lista de equipos (servidores) a los que se debe conectar para realizar las actualizaciones ***/
        $listaServidores     = SQL::seleccionar(array("servidores"), array("id", "ip", "id_sucursal"), "id != '$idServidorPrincipal'");
        $ipServidorPrincipal = SQL::seleccionar(array("servidores"), array("ip"), "id = '$idServidorPrincipal'");

        while ($servidor = SQL::filaEnObjeto($listaServidores)){
            $ipServidor = $servidor->ip;
            $idServidor = $servidor->id;
            $idSucursal = $servidor->id_sucursal;

            $tabla_temporal            = array();
            $id_temporal               = array();
            $instruccion_temporal      = array();
            $columnas_temporal         = array();
            $valores_temporal          = array();
            $id_actualizacion_temporal = array();
            $condicion_temporal        = array();
            $valoresServidor           = array();
            $ejecutado_temporal        = array();
            /*** Conectarse a todos los servidores diferentes a sí mismo ***/
            if ($idServidor != $idServidorPrincipal) {

                /*** Conexión directa con la función mysql_connect() ya que la clase SQL es para la conexión local ***/
                $conexionServidor = mysql_connect($ipServidor, $accesoRemoto["usuario"], $accesoRemoto["contrasena"]);

                if (!$conexionServidor) {
                    echo "\nERROR1|".date("Y-m-d H:i:s")."|Error conectando a $ipServidor\n";

                /*** Conexión establecida correctamente ***/
                } else {
                    echo "\nOK|".date("Y-m-d H:i:s")."|Conexión establecida con $ipServidor\n";
                    $baseDatosRemota = mysql_select_db(SQL::$baseDatos, $conexionServidor);
                    $registrosNuevos = mysql_query("SELECT * FROM ".SQL::$prefijoTabla."actualizaciones_almacen WHERE leido = '0' ORDER BY FECHA ASC", $conexionServidor);

                    while ($registro = mysql_fetch_object($registrosNuevos)) {

                        $sentencia_registro_leido = "SELECT * FROM ".SQL::$prefijoTabla."actualizaciones_procesadas_cliente WHERE id_servidor='$idServidor' AND id_actualizacion='$registro->id' AND ejecutado='1' AND actualizado='1'";
                        $registro_leido           = mysql_query($sentencia_registro_leido,SQL::$conexion);

                        if (mysql_num_rows($registro_leido)){
                            $datos_procesada = SQL::filaEnObjeto($registro_leido);
                            $sentenciaCliente  = mysql_query("UPDATE ".SQL::$prefijoTabla."actualizaciones_almacen SET leido='1', id_real='$datos_procesada->id_real' WHERE id = '$registro->id'", $conexionServidor);
                        } else {

                            $sentencia_registro_leido = "SELECT * FROM ".SQL::$prefijoTabla."actualizaciones_procesadas_cliente WHERE id_servidor='$idServidor' AND id_actualizacion='$registro->id' AND ((ejecutado='0' AND actualizado='1') OR (ejecutado='1' AND actualizado='0'))";
                            $registro_leido           = mysql_query($sentencia_registro_leido,SQL::$conexion);
                            $ejecutado                = false;
                            if (mysql_num_rows($registro_leido)){
                                $datos_procesada = SQL::filaEnObjeto($registro_leido);
                                $ejecutado       = $datos_procesada->ejecutado;
                            }
                            $datos_procesada = SQL::filaEnObjeto($registro_leido);
                            $sentenciaCliente  = mysql_query("UPDATE ".SQL::$prefijoTabla."actualizaciones_almacen SET leido='1', id_real='$datos_procesada->id_real' WHERE id = '$registro->id'", $conexionServidor);
                            $idActualizacion = $registro->id;
                            $fechaOperacion  = $registro->fecha;
                            $instruccion     = $registro->instruccion;
                            $tablaAfectada   = $registro->tabla;
                            $columnas        = $registro->columnas;
                            $valores         = $registro->valores;
                            $idAfectado      = $registro->id_asignado;
                            $condicion       = $registro->condicion;
                            $columnas        = explode(",", $columnas);
                            // Ctrl+Shift+u+00EE para sacar î
                            $valores         = preg_split("/î(?!(?:[^\,\\\',]|[^\,\\\'],[^\,\\\'])+\\\')/", $valores);
                            $nuevosValores   = array();
                            $modificaciones  = array();
                            $modificaciones_servidor = array();
                            $valoresServidor = array();

                            for ($i = 0; $i < count($columnas); $i++) {
                                $modificaciones[$i]           = $columnas[$i]."=".trim(preg_replace("/^(\\\')(.*)(\\\')$/", "'$2'", $valores[$i]));
                                $nuevosValores[$i]            = trim(preg_replace("/^(\\\')(.*)(\\\')$/", "'$2'", $valores[$i]));
                                $valoresServidor[$i]          = "\\'".trim($nuevosValores[$i],"'")."\\'";
                                $modificaciones_servidor[$i]  = $columnas[$i]."=".$valoresServidor[$i];
                            }
                            $columnas_insert      = implode(",",$columnas);
                            $nuevosValores_insert = implode(",",$nuevosValores);
                            $valoresServidor      = implode(",",$valoresServidor);

                            switch ($instruccion) {

                                /*** Registro para insertar ***/
                                case "I" :
                                            $error_insercion = false;
                                            if ($ejecutado=='0'){
                                                if (in_array($tablaAfectada,$tablasLlavePrimariaCompuesta)){
                                                    $insertarRegistro = "INSERT INTO $tablaAfectada (id,$columnas_insert) VALUES($idAfectado,$nuevosValores_insert);";
                                                } else {
                                                    $insertarRegistro = "INSERT INTO $tablaAfectada ($columnas_insert) VALUES($nuevosValores_insert);";
                                                }
                                                $insercion = mysql_query($insertarRegistro,SQL::$conexion);
                                                if (mysql_error()) {
                                                    echo "\nERROR2|".mysql_errno().":".mysql_error()."\n";
                                                    $error_insercion = true;
                                                }
                                            }

                                            if (!$error_insercion){
                                                $tabla_temporal[]            = $tablaAfectada;
                                                $id_temporal[]               = $idAfectado;
                                                $instruccion_temporal[]      = $instruccion;
                                                $columnas_temporal[]         = $columnas_insert;
                                                $valores_temporal[]          = $valoresServidor;
                                                $id_actualizacion_temporal[] = $idActualizacion;
                                                $ejecutado_temporal[]        = $ejecutado;
                                                $tabla_actualizacion         = SQL::$prefijoTabla."actualizaciones_procesadas_cliente";
                                                $valores                     = "(id_servidor,id_actualizacion,ejecutado) VALUES($idServidor,$idActualizacion,'1')";
                                                $ejecutado                   = mysql_query("INSERT INTO $tabla_actualizacion $valores",SQL::$conexion);
                                            }
                                            unset($columnas_insert);
                                            unset($nuevosValores_insert);
                                            unset($valoresServidor);

                                            break;

                                /*** Registro para modificar ***/
                                case "U" :
                                            $instruccion1 = "\nUPDATE $tablaAfectada SET ".implode(",", $modificaciones)." WHERE $condicion";
                                            $modificacion = mysql_query($instruccion1,SQL::$conexion);

                                            if (mysql_errno()) {
                                                echo "\nERROR3|".mysql_error()."\n";

                                            } else {
                                                $tabla_temporal[]            = $tablaAfectada;
                                                $id_temporal[]               = $idAfectado;
                                                $instruccion_temporal[]      = $instruccion;
                                                $columnas_temporal[]         = $columnas_insert;
                                                $valores_temporal[]          = $valoresServidor;
                                                $id_actualizacion_temporal[] = $id_real;
                                                $condicion_temporal[]        = $condicion;
                                            }

                                            break;

                                /*** Registro para eliminar ***/
                                case "D" :  $instruccion1 = "\nDELETE FROM $tablaAfectada WHERE id = $idAfectado";
                                            $instruccion2 = "";
                                            $eliminacion  = SQL::correrConsulta($instruccion1);

                                            if (mysql_errno()) {
                                                echo "\nERROR4|".mysql_error()."\n";

                                            } else {
                                                $marcarRegistro  = mysql_query("UPDATE ".SQL::$prefijoTabla."actualizaciones_almacen SET leido='1' WHERE id = '$idActualizacion'", $conexionServidor);
                                                $insercionservidor = mysql_query("INSERT INTO ".SQL::$prefijoTabla."actualizaciones_procesadas_cliente (id_servidor,id_actualizacion,id_real) VALUES($idServidor,$idActualizacion,$idAfectado)",SQL::$conexion);
                                                $campos        = "id_servidor,fecha,instruccion1,instruccion2";
                                                $valores       = "$idServidor,'".date("Y-m-d G:i:s")."','$instruccion1',''";
                                                $actualizacion = mysql_query("INSERT INTO ".SQL::$prefijoTabla."actualizaciones_servidor ($campos) VALUES ($valores)", SQL::$conexion);
                                            }

                                            break;
                            }
                        }
                    }

                    if (isset($tabla_temporal)){
                        foreach($tabla_temporal as $id => $nombre_tabla){

                            $id_tabla             = $id_temporal[$id];
                            $instruccion_servidor = $instruccion_temporal[$id];
                            $columnas_servidor    = $columnas_temporal[$id];
                            $valores_servidor     = $valores_temporal[$id];
                            $id_actualizacion     = $id_actualizacion_temporal[$id];
                            $condicion            = $condicion_temporal[$id];
                            $ejecutado            = $ejecutado_temporal[$id];

                            $consulta_tabla = mysql_query("SELECT * FROM $nombre_tabla WHERE id ='$id_tabla'",SQL::$conexion);
                            if (mysql_num_rows($consulta_tabla)){

                                while ($datos_tabla = SQL::filaEnObjeto($consulta_tabla)){

                                    $consulta_tabla_consecutivo = true;
                                    $tabla_consecutivos         = false;
                                    $id_real                    = false;
                                    $id_real_archivo            = false;
                                    $consulta_columnas          = mysql_query("SHOW COLUMNS FROM $nombre_tabla",SQL::$conexion);
                                    $modificacion   = array();
                                    $nuevos_campos  = array();
                                    $nuevos_valores = array();
                                    $campo_id_tabla = false;


                                    while ($datos_columnas = SQL::filaEnArreglo($consulta_columnas)) {


                                        if ($datos_columnas[0]=="id"){
                                            $busqueda   = preg_match("/^[a-zA-Z]+\(([0-9]){0,2}\)/", $datos_columnas[1], $longitud);
                                            if (!empty($longitud)) {
                                                $idTemporal = (str_repeat("9", $longitud[1])+1) * 0.9;
                                            }
                                            $campo_id_tabla = true;
                                        } else {

                                            if ($nombre_tabla == SQL::$prefijoTabla."consecutivo_documentos" && $consulta_tabla_consecutivo){

                                                $consulta_consecutivo = mysql_query("SELECT * FROM $nombre_tabla WHERE id=$id_tabla",$conexionServidor);

                                                if (mysql_num_rows($consulta_consecutivo)){

                                                    $tabla_consecutivos = true;

                                                    $datos_consecutivo = SQL::filaEnObjeto($consulta_consecutivo);

                                                    $id_sucursal_consecutivo     = $datos_consecutivo->id_sucursal;
                                                    $id_tabla_modificar          = $datos_consecutivo->id_tabla;
                                                    $nombre_tabla_modificar      = SQL::obtenerValor("tablas","nombre_tabla","id='$id_tabla_modificar'");
                                                    $nombre_tabla_modificar      = SQL::$prefijoTabla.$nombre_tabla_modificar;
                                                    $id_registro_tabla_modificar = $datos_consecutivo->id_registro_tabla;
                                                    $id_archivo_tabla_modificar  = $datos_consecutivo->id_archivo;
                                                    $tabla_consulta              = SQL::$prefijoTabla."actualizaciones_almacen";
                                                    $sentencia_consulta          = "SELECT * FROM $tabla_consulta WHERE tabla='$nombre_tabla_modificar' AND id_asignado='$id_registro_tabla_modificar' AND instruccion='I' AND consecutivo_asignado='0' ORDER BY fecha LIMIT 1";
                                                    $consulta_actualizacion      = mysql_query($sentencia_consulta,$conexionServidor);

                                                    if (mysql_num_rows($consulta_actualizacion)){
                                                        $datos_actualizacion    = SQL::filaEnObjeto($consulta_actualizacion);
                                                        $id_real                = $datos_actualizacion->id_real;
                                                        $id                     = $datos_actualizacion->id;
                                                        $modifica_actualizacion = mysql_query("UPDATE $tabla_consulta SET consecutivo_asignado='1' WHERE id=$id",$conexionServidor);
                                                    }
                                                }
                                                $consulta_tabla_consecutivo = false;
                                            }

                                            $nombre_campo    = $datos_columnas[0]."=";
                                            $nuevos_campos[] = $datos_columnas[0];
                                            if ($tabla_consecutivos){
                                                if ($datos_columnas[0]=="id_registro_tabla" && $id_real>0){
                                                    $valor_campo    = "'".$id_real."'";
                                                    $nuevos_valores[] = $id_real;
                                                } else if ($datos_columnas[0]=="id_archivo" && isset($id_real_archivo)){
                                                    $valor_campo    = "'".$id_real_archivo."'";
                                                    $nuevos_valores[] = $id_real_archivo;
                                                } else {
                                                    $valor_campo    = "'".$datos_tabla->$datos_columnas[0]."'";
                                                    $nuevos_valores[] = "\'".$datos_tabla->$datos_columnas[0]."\'";
                                                }
                                            } else {
                                                $valor_campo    = "'".$datos_tabla->$datos_columnas[0]."'";
                                                $nuevos_valores[] = "\'".$datos_tabla->$datos_columnas[0]."\'";
                                            }
                                            $modificacion[] = $nombre_campo.$valor_campo;
                                        }
                                    }
                                }
                                $modificacion      = implode(",",$modificacion);
                                $nuevos_campos     = implode(",",$nuevos_campos);
                                $nuevos_valores    = implode(",",$nuevos_valores);
                                $tabla_sin_prefijo = explode(SQL::$prefijoTabla,$nombre_tabla);
                                $tabla_sin_prefijo = $tabla_sin_prefijo[0];

                                if (!in_array($tabla_sin_prefijo,$tablasLlavePrimariaCompuesta)){
                                    $sentencia_ultimo_id = mysql_query("SELECT id FROM $nombre_tabla WHERE id <$idTemporal ORDER BY id DESC LIMIT 1;",SQL::$conexion);
                                    if (mysql_num_rows($sentencia_ultimo_id)){
                                        $datos_ultimo_id = SQL::filaEnObjeto($sentencia_ultimo_id);
                                        $ultimo_id_tabla = $datos_ultimo_id->id;
                                        $ultimo_id_tabla++;
                                    }
                                    $modificacion = $modificacion.", id=$ultimo_id_tabla";
                                    $sentencia = "UPDATE $nombre_tabla SET ".$modificacion." WHERE $condicion";
                                } else {
                                    $sentencia = "UPDATE $nombre_tabla SET ".$modificacion." WHERE $condicion";
                                }
                                $insertar  = mysql_query($sentencia,SQL::$conexion);

                                if (mysql_error()) {
                                    echo "\nERROR5|".mysql_error()."\n";
                                } else {

                                    switch($instruccion_servidor){
                                        case "I":
                                            if (!in_array($tabla_sin_prefijo,$tablasLlavePrimariaCompuesta)){
                                                $instruccion1      = "INSERT INTO $nombre_tabla ($nuevos_campos,id) VALUES($nuevos_valores,$ultimo_id_tabla);";
                                                $instruccion2      = "UPDATE $nombre_tabla SET $modificacion WHERE id = $id_tabla";
                                            } else {
                                                $instruccion1      = "INSERT INTO $nombre_tabla ($nuevos_campos) VALUES($nuevos_valores);";
                                                $instruccion2      = "UPDATE $nombre_tabla SET $modificacion";
                                            }
                                            $actualiza          = mysql_query($instruccion2,$conexionServidor);
                                            $actualizo_registro = true;
                                            if (!mysql_error()){
                                                $actualizo_registro = false;
                                            }

                                            $columnasServidor  = "id_servidor, fecha, instruccion1, instruccion2";
                                            $fecha             = date("Y-m-d G:i:s");
                                            $valoresServidor   = $servidor->id.", '".$fecha."','".$instruccion1."','".$instruccion2."'";
                                            $sentenciaservidor = "INSERT INTO ".SQL::$prefijoTabla."actualizaciones_servidor (".$columnasServidor.") VALUES (".$valoresServidor.")";
                                            $insercionServidor = mysql_query($sentenciaservidor,SQL::$conexion);

                                            if (!in_array($tabla_sin_prefijo,$tablasLlavePrimariaCompuesta)){

                                                $insercionServidor = mysql_query("ALTER TABLE $nombre_tabla AUTO_INCREMENT=$ultimo_id_tabla",SQL::$conexion);

                                                if ($actualizo_registro){
                                                    $insercionCliente  = mysql_query("UPDATE ".SQL::$prefijoTabla."actualizaciones_almacen SET leido='1', id_real='$ultimo_id_tabla' WHERE id = '$id_actualizacion'", $conexionServidor);
                                                    $insercionservidor = mysql_query("UPDATE ".SQL::$prefijoTabla."actualizaciones_procesadas_cliente SET actualizado='1',id_real=$ultimo_id_tabla WHERE id_servidor=$idServidor AND id_actualizacion=$id_actualizacion",SQL::$conexion);
                                                }

                                            } else {
                                                if ($actualizo_registro){
                                                    $insercionCliente  = mysql_query("UPDATE ".SQL::$prefijoTabla."actualizaciones_almacen SET leido='1' WHERE id = '$id_actualizacion'", $conexionServidor);
                                                    $insercionservidor = mysql_query("UPDATE ".SQL::$prefijoTabla."actualizaciones_procesadas_cliente SET actualizado='1' WHERE id_servidor=$idServidor AND id_actualizacion=$id_actualizacion",SQL::$conexion);
                                                }
                                            }

                                            break;
                                        // Modificar con el id real
                                        /*case "U":
                                            $ejecutar_instruccion = true;
                                            if ($idAfectado >0){

                                                $consulta   = "SHOW FULL COLUMNS FROM $nombre_tabla LIKE 'id'";
                                                $resultado  = mysql_query($consulta,SQL::$conexion);
                                                $registros  = mysql_fetch_array($resultado);
                                                $busqueda   = preg_match("/^[a-zA-Z]+\(([0-9]){0,2}\)/", $registros[1], $longitud);
                                                if (!empty($longitud)) {
                                                    $idTemporal = (str_repeat("9", $longitud[1])+1) * 0.9;
                                                }
                                                if ($idAfectado <= $idTemporal){
                                                    $ejecutar_instruccion = mysql_query();
                                                }
                                            }
                                            $marcarRegistro    = mysql_query("UPDATE ".SQL::$prefijoTabla."actualizaciones_almacen SET leido='1' WHERE id = $idActualizacion", $conexionServidor);
                                            $insercionservidor = mysql_query("INSERT INTO ".SQL::$prefijoTabla."actualizaciones_procesadas_cliente (id_servidor,id_actualizacion,id_real) VALUES($idServidor,$idActualizacion,$idAfectado)",SQL::$conexion);
                                            $campos        = "id_servidor,fecha,instruccion1,instruccion2";
                                            $instruccion1  = "UPDATE $nombre_tabla SET $modificacion WHERE $condicion";
                                            if ($ejecutar_instruccion){
                                            }
                                            $valores       = "$idServidor,'".date("Y-m-d G:i:s")."','$instruccion1',''";
                                            $actualizacion = mysql_query("INSERT INTO ".SQL::$prefijoTabla."actualizaciones_servidor ($campos) VALUES ($valores)", SQL::$conexion);
                                            break;*/
                                    }
                                    unset($modificacion);
                                    unset($nuevos_campos);
                                    unset($nuevos_valores);
                                }
                            }
                        }
                    }
                }
            }
        }

    /*** Los almacenes se conectan al servidor principal para replicar los datos del mismo ***/
    } else {
        $servidor   = SQL::filaEnObjeto(SQL::seleccionar(array("servidores"), array("id", "ip"), "id = '$idServidorPrincipal'"));
        $ipServidor = $servidor->ip;
        $idServidor = $servidor->id;

        /*** Conexión directa con la función mysql_connect() ya que la clase SQL es para la conexión local ***/
        $conexionServidor = mysql_connect($ipServidor, $accesoRemoto["usuario"], $accesoRemoto["contrasena"]);
        /*** No hubo conexión ***/
        if (!$conexionServidor) {
            echo "\nERROR1|".date("Y-m-d H:i:s")."|Error conectando a $ipServidor\n";

        /*** Conexión establecida correctamente ***/
        } else {
            echo "\nOK|".date("Y-m-d H:i:s")."|Conexión establecida con $ipServidor\n";
            $baseDatosRemota        = mysql_select_db(SQL::$baseDatos, $conexionServidor);

            $consulta_id_procesados = mysql_query("SELECT id_actualizacion FROM ".SQL::$prefijoTabla."actualizaciones_procesadas_servidor WHERE id_servidor = '$idServidorLocal'",SQL::$conexion);
            if (mysql_num_rows($consulta_id_procesados)){
                $id_procesados = array();
                while($datos_id_procesados = mysql_fetch_object($consulta_id_procesados)){
                    $id_procesados[] = $datos_id_procesados->id_actualizacion;
                }
                $id_procesados = implode(",",$id_procesados);
            } else {
                $id_procesados = 0;
            }

            $actualizaciones = mysql_query("SELECT * FROM ".SQL::$prefijoTabla."actualizaciones_servidor WHERE id NOT IN ($id_procesados) ORDER BY FECHA ASC", $conexionServidor);

            while ($registro = mysql_fetch_object($actualizaciones)) {
                $idActualizacion = $registro->id;

                if ($registro->id_servidor != $idServidorLocal) {
                    $instruccion = $registro->instruccion1;

                } else {
                    $instruccion = $registro->instruccion2;
                }
                $ejecutar_instruccion   = mysql_query($instruccion,SQL::$conexion);
                $campos                 = "(id_servidor,id_actualizacion)";
                $valores                = "($idServidorLocal,$idActualizacion)";
                $insertar_actualizacion = mysql_query("INSERT INTO ".SQL::$prefijoTabla."actualizaciones_procesadas_servidor $campos VALUES $valores",SQL::$conexion);
            }
        }
    }
?>
</pre>

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
* de APTITUD PARA UN PROPÓITO DETERMINADO. Consulte los detalles de
* la Licencia Pública General GNU para obtener una información más
* detallada.
*
* Debería haber recibido una copia de la Licencia Pública General GNU
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
**/

// Generar el formulario para la captura de datos
if (!empty($url_generar)) {

    $error  = "";
    $titulo = $componente->nombre;

    ///Obtener lista de sucursales para selecci�n dependiendo a los permisos///
    $consulta = SQL::seleccionar(array("sucursales"),array("*"),"codigo > 0","","nombre");
    if ($sesion_usuario == $datosGlobales["usuarioMaestro"]){
         while ($datos = SQL::filaEnObjeto($consulta)){
            $sucursales[$datos->codigo] = $datos->nombre;
        }
    } else {
        /*** Obtener lista de sucursales para selecci�n ***/
        $tablas     = array(
        "a" => "perfiles_usuario",
        "b" => "componentes_usuario",
        "c" => "sucursales"
        );
        $columnas = array(
        "codigo" => "c.codigo",
        "nombre" => "c.nombre"
        );
        $condicion = "c.codigo = a.codigo_sucursal AND a.id = b.id_perfil
        AND a.codigo_usuario = '".$sesion_codigo_usuario."'
        AND b.id_componente = '".$componente->id."'";

        $consulta = SQL::seleccionar($tablas, $columnas, $condicion);

        if (SQL::filasDevueltas($consulta)) {
            while ($datos = SQL::filaEnObjeto($consulta)) {
                $sucursales[$datos->codigo] = $datos->nombre;
            }
        }
    }

    // Definicion de pestana general
    $formularios["PESTANA_GENERAL"] = array(
        array(
            HTML::listaSeleccionSimple("codigo_sucursal", $textos["SUCURSAL"], $sucursales, "")
        ),
        array(
            HTML::campoTextoCorto("fechas", $textos["DESDE_HASTA"], 19, 25,"", array("class" => "fechaRango"))
        ),
        array(
            HTML::campoTextoCorto("documento_identidad_tercero1", $textos["DOCUMENTO_DESDE"], 20, 12, "", array("onKeyPress" => "return campoEntero(event);"))
        ),
        array(
            HTML::campoTextoCorto("documento_identidad_tercero2", $textos["DOCUMENTO_HASTA"], 20, 12, "", array("onKeyPress" => "return campoEntero(event);"))
        )
    );

    $botones = array (
        HTML::boton("botonAceptar", $textos["ACEPTAR"], "imprimirItem('0');", "aceptar")
    );

    $contenido = HTML::generarPestanas($formularios, $botones);

    // Enviar datos para la generacion del formulario al script que origino la peticion
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

// Exportar los datos
} elseif (!empty($forma_procesar)) {

    $ruta_archivo = "";
    $condicion    = "1";
    $error        = false;
    $mensaje      = $textos["ARCHIVO_GENERADO"];

    // ====================== Inicio de condiciones ====================== //

    if((int)$forma_codigo_sucursal != 0){
        $condicion .= " AND codigo_sucursal = '".$forma_codigo_sucursal."'";
    }

    if($forma_fechas != ""){
        $fechas = explode("-",$forma_fechas);
        $fecha1 = trim($fechas[0]);
        $fecha2 = trim($fechas[1]);
        $fecha1 = str_replace("/","-",$fecha1);
        $fecha2 = str_replace("/","-",$fecha2);
        $condicion .= " AND (fecha_inicio_tiempo BETWEEN '".$fecha1."' AND '".$fecha2."')";
    }
    
    if($forma_documento_identidad_tercero2 != ""){
        $condicion .= " AND documento_identidad_empleado <= ".$forma_documento_identidad_tercero2;
    }
    if($forma_documento_identidad_tercero1 != "" && $forma_documento_identidad_tercero2 == ""){
        $error   = true;
        $mensaje = $textos["ERROR_DOCUMENTO_IDENTIDAD_1"];
        $ruta    = "";
    }else if($forma_documento_identidad_tercero2 != "" && $forma_documento_identidad_tercero1 != ""){
        $condicion .= " AND documento_identidad_empleado >= ".$forma_documento_identidad_tercero1;
    }

    // ======================= Fin  de condiciones ======================= //

    $nombre         = "";
    $nombreArchivo  = "";
    do {
        $cadena         = Cadena::generarCadenaAleatoria(8);
        $nombre = $sesion_sucursal.$cadena.".pdf";
        $nombreArchivo  = $rutasGlobales["archivos"]."/".$nombre;
    } while (is_file($nombreArchivo));

    $archivo                 = new PDF("P","mm","Letter");
    $archivo->textoTitulo    = $textos["REPORTE_INCAPACIDADES"];
    $archivo->textoCabecera  = $textos["FECHA"].": ".date("Y-m-d");
    $archivo->textoPiePagina = "";
    $archivo->AddPage();

    $tituloColumnas = array($textos["SUCURSAL"],$textos["EMPLEADO"],$textos["FECHA_INICIAL"],$textos["FECHA_FINAL"],$textos["NO_DIAS"],$textos["TIPO_TRANSACCION"],$textos["ID_MOTIVO_INCAPACIDAD"],$textos["VALOR_MOVIMIENTO"]);
    $anchoColumnas  = array(25,40,17,17,12,30,30,25);

    $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
    $archivo->Ln(4);
    $i = 0;

    $consulta = SQL::seleccionar(array("movimiento_tiempos_no_laborados_dias"), array("*"),$condicion,"documento_identidad_empleado,codigo_transaccion_tiempo,fecha_inicio_tiempo","codigo_sucursal,documento_identidad_empleado,fecha_tiempo");

    if (SQL::filasDevueltas($consulta)) {
        while($datos = SQL::filaEnObjeto($consulta)) {
            if($archivo->breakCell(5)){
                $archivo->AddPage();
                $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
                $archivo->Ln(4);
            }

            if($archivo->FillColor != sprintf('%.3F %.3F %.3F rg',1,1,1)){
                $archivo->SetFillColor(255,255,255);
            }else{
                $archivo->SetFillColor(240,240,240);
            }

            $condicion  = "documento_identidad_empleado = '".$datos->documento_identidad_empleado."'";
            $condicion .= " AND codigo_transaccion_tiempo = '".$datos->codigo_transaccion_tiempo."'";
            $condicion .= " AND fecha_inicio_tiempo = '".$datos->fecha_inicio_tiempo."'";

            $consulta2 = SQL::seleccionar(array("movimiento_tiempos_no_laborados_dias"), array("*"),$condicion);
            $cantidad = SQL::filasDevueltas($consulta2);

            $fecha_comienzo = strtotime($datos->fecha_tiempo);
            $fecha_generada = mktime(0, 0, 0, date("m", $fecha_comienzo), date("d", $fecha_comienzo)+($cantidad-1), date("Y", $fecha_comienzo));
            $fecha_fin      = date('Y-m-d', $fecha_generada);

            $sucursal    = SQL::obtenerValor("sucursales","nombre","codigo = '".$datos->codigo_sucursal."'");
            $empleado    = SQL::obtenerValor("seleccion_terceros","SUBSTRING_INDEX(nombre,'|',1)","id = '".$datos->documento_identidad_empleado."'");
            $transaccion = SQL::obtenerValor("transacciones_tiempo","nombre"," codigo = '".$datos->codigo_transaccion_tiempo."'");
            $motivo      = SQL::obtenerValor("motivos_tiempo_no_laborado","descripcion"," codigo = '".$datos->codigo_motivo_no_laboral."'");

            $archivo->SetFont('Arial',"",6);
            $archivo->Cell(25, 4, $sucursal, 1, 0, "L", true,"",true);
            $archivo->Cell(40, 4, $empleado, 1, 0, "L", true,"",true);
            $archivo->Cell(17, 4, $datos->fecha_tiempo, 1, 0, "C", true,"",true);
            $archivo->Cell(17, 4, $fecha_fin, 1, 0, "C", true,"",true);
            $archivo->Cell(12, 4, $cantidad, 1, 0, "R", true,"",true);
            $archivo->Cell(30, 4, $transaccion, 1, 0, "L", true,"",true);
            $archivo->Cell(30, 4, $motivo, 1, 0, "L", true,"",true);
            $archivo->Cell(25, 4, "$ ".number_format($datos->valor_dia,0), 1, 0, "R", true,"",true);
            $archivo->Ln(4);

            $i++;
        }
    }

    $cargaPdf = 0;

    if($i>0 && !$error) {
        $archivo->Output($nombreArchivo, "F");
        $consecutivo_arc = SQL::obtenerValor("archivos","MAX(consecutivo)","codigo_sucursal='".$sesion_sucursal."'");
        if ($consecutivo_arc){
            $consecutivo_arc++;
        } else {
            $consecutivo_arc = 1;
        }
        $consecutivo_arc = (int)$consecutivo_arc;

        $datos_archivo = array(
            "codigo_sucursal" => $sesion_sucursal,
            "consecutivo"     => $consecutivo_arc,
            "nombre"          => $nombre
        );
        SQL::insertar("archivos", $datos_archivo);
        $id_archivo   = $sesion_sucursal."|".$consecutivo_arc;
        $ruta_archivo = HTTP::generarURL("DESCARCH")."&id=".$id_archivo."&temporal=1";
        $cargaPdf = 1;
    }

    // Enviar datos con la respuesta del proceso al script que origino la peticion

    if ($cargaPdf == 1) {
        $ruta    = $ruta_archivo;
    } else if($cargaPdf == 0 && !$error){
        $error = true;
        $mensaje = $textos["ERROR_GENERAR_ARCHIVO"];
        $ruta = "";
    }
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    $respuesta[2] = $ruta;
    HTTP::enviarJSON($respuesta);
}
?>

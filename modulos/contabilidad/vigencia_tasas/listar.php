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
// Generar el formulario para la captura de datos
if (!empty($url_generar)) {

    $rango_dias = array(
        "a.fecha"        => $textos["FECHA"],
        "b.descripcion"   => $textos["DESCRIPCION"]
    );

    $error  = "";
    $titulo = $componente->nombre;

    // Definicion de pestana general
    $formularios["PESTANA_GENERAL"] = array(
        array(
            HTML::listaSeleccionSimple("ordenamientos", $textos["ORDENAMIENTO"], $rango_dias, 0),
            HTML::campoTextoCorto("fechas",$textos["FECHAS"],20,25,"",array("title" => $textos["AYUDA_FECHAS"], "class" => "fechaRango"))
        ),
        array(
            HTML::listaSeleccionSimple("codigo_tasa", $textos["TASA"], HTML::generarDatosLista("tasas","codigo","descripcion"), 0)
        )
    );

    $botones = array (HTML::boton("botonAceptar", $textos["ACEPTAR"], "imprimirItem('0');", "aceptar"));

    $contenido = HTML::generarPestanas($formularios, $botones);

    // Enviar datos para la generacion del formulario al script que origino la peticion ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

// Exportar los datos
} elseif (!empty($forma_procesar)) {

    $ruta_archivo   = "";
    $condicion      = "";

    if($forma_fechas != ""){
        $fechas = explode("-",$forma_fechas);
        $fecha1 = trim($fechas[0]);
        $fecha2 = trim($fechas[1]);
        $condicion .= " AND a.fecha BETWEEN '".$fecha1."' AND '".$fecha2."'";
    }
    if((int)$forma_codigo_tasa != 0){
        $condicion .= " AND b.codigo = '".$forma_codigo_tasa."'";
    }

    $nombre         = "";
    $nombreArchivo  = "";
    do {
        $cadena         = Cadena::generarCadenaAleatoria(8);
        $nombre = $sesion_sucursal.$cadena.".pdf";
        $nombreArchivo  = $rutasGlobales["archivos"]."/".$nombre;
    } while (is_file($nombreArchivo));

    $archivo                = new PDF("P","mm","Letter");
    $archivo->textoTitulo   = $textos["VIGENCIA_TASAS"];
    $archivo->textoCabecera = $textos["FECHA"].": ".date("Y-m-d");
    $archivo->textoPiePagina = "";
    $archivo->AddPage();

    $tituloColumnas = array($textos["DESCRIPCION"], $textos["FECHA"],$textos["PORCENTAJE"],$textos["VALOR_BASE"]);
    $anchoColumnas  = array(110,25,30,30);

    $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
    $archivo->Ln(4);

    $consulta = SQL::seleccionar(array("a" => "vigencia_tasas", "b" => "tasas"),array("descripcion" =>"b.descripcion",
                                "fecha" => "a.fecha","porcentaje" => "a.porcentaje","valor_base" => "a.valor_base"),
                                "a.codigo_tasa = b.codigo AND b.codigo != 0".$condicion,"","$forma_ordenamientos ASC");
    $i=0;
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

            $archivo->SetFont('Arial',"",6);
            $archivo->Cell(110, 4, $datos->descripcion, 1, 0, "L", true,"",true);
            $archivo->Cell(25, 4, $datos->fecha, 1, 0, "C", true);
            $archivo->Cell(30, 4, "%".$datos->porcentaje, 1, 0, "R", true);
            $archivo->Cell(30, 4, number_format($datos->valor_base,2), 1, 0, "R", true);
            $archivo->Ln(4);

            $i++;
        }
    }

    $cargaPdf = 0;

    if($i>0) {
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
    $respuesta    = array();
    if ($cargaPdf == 1) {
        $respuesta[0] = false;
        $respuesta[1] = $textos["ARCHIVO_GENERADO"];
        $respuesta[2] = $ruta_archivo;
    } else{
        $respuesta[0] = true;
        $respuesta[1] = $textos["ERROR_GENERAR_ARCHIVO"];
        $respuesta[2] = "";
    }
    HTTP::enviarJSON($respuesta);
}
?>

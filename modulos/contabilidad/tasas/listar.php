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

/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {

    $error  = "";
    $titulo = $componente->nombre;

    $rango_dias = array(
        "codigo"        => $textos['CODIGO'],
        "descripcion"   => $textos['DESCRIPCION']
    );

    /*** Definición de pestaña general ***/
    $formularios["PESTANA_GENERAL"] = array(
        array(
            HTML::listaSeleccionSimple("ordenamientos", $textos["ORDENAMIENTO"], $rango_dias, 0)
        )
    );

    $botones = array (
        HTML::boton("botonAceptar", $textos["ACEPTAR"], "imprimirItem('0');", "aceptar")
    );

    $contenido = HTML::generarPestanas($formularios, $botones);

    /*** Enviar datos para la generación del formulario al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

/*** Exportar los datos***/
} elseif (!empty($forma_procesar)) {

    $error          = false;
    $mensaje        = $textos["ITEM_ADICIONADO"];
    $ruta_archivo   = "";

    $nombre         = "";
    $nombreArchivo  = "";
    do {
        $cadena         = Cadena::generarCadenaAleatoria(8);
        $nombre = $sesion_sucursal.$cadena.".pdf";
        $nombreArchivo  = $rutasGlobales["archivos"]."/".$nombre;
    } while (is_file($nombreArchivo));

    $archivo                = new PDF("P","mm","Letter");
    $archivo->textoTitulo   = $textos["LISTADO_TASAS"];
    $archivo->textoCabecera = $textos["FECHA"].": ".date("Y-m-d");
    $archivo->textoPiePagina = "";
    $archivo->AddPage();

    $tituloColumnas = array($textos["CODIGO"], $textos["DESCRIPCION"]);
    $anchoColumnas  = array(20,150);

    $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
    $archivo->Ln(4);
    $i=0;
    $consulta = SQL::seleccionar(array("tasas"), array("codigo","descripcion"),"codigo != 0","","$forma_ordenamientos ASC");
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
            $archivo->Cell(20, 4, $datos->codigo, 1, 0, "L", true);
            $archivo->Cell(150, 4, $datos->descripcion, 1, 0, "L", true,"",true);
            $archivo->Ln(4);

            $i++;
        }
    }

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

    /*** Enviar datos con la respuesta del proceso al script que originó la petición ***/
    $respuesta    = array();
    if ($cargaPdf == 1) {
        $respuesta[0] = false;
        $respuesta[1] = $textos["MENSAJE_EXITO"];
        $respuesta[2] = $ruta_archivo;
    } else{
        $respuesta[0] = true;
        $respuesta[1] = $textos["MENSAJE_ERROR"];
        $respuesta[2] = "";
    }


    HTTP::enviarJSON($respuesta);
}
?>

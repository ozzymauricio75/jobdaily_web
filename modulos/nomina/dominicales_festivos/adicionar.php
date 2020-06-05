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
* modificarlo  bajo los t√©rminos de la Licencia P√∫blica General GNU
* publicada por la Fundaci√≥n para el Software Libre, ya sea la versi√≥n 3
* de la Licencia, o (a su elecci√≥n) cualquier versi√≥n posterior.
*
* Este programa se distribuye con la esperanza de que sea √∫til, pero
* SIN GARANT√çA ALGUNA; ni siquiera la garant√≠a impl√≠cita MERCANTIL o
* de APTITUD PARA UN PROP√ìSITO DETERMINADO. Consulte los detalles de
* la Licencia P√∫blica General GNU para obtener una informaci√≥n m√°s
* detallada.
*
* Deber√≠a haber recibido una copia de la Licencia P√∫blica General GNU
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
**/

/*** Generar el formulario para la captura de datos ***/

$meses = array(
    "1" => $textos["ENERO"],
    "2" => $textos["FEBRERO"],
    "3" => $textos["MARZO"],
    "4" => $textos["ABRIL"],
    "5" => $textos["MAYO"],
    "6" => $textos["JUNIO"],
    "7" => $textos["JULIO"],
    "8" => $textos["AGOSTO"],
    "9" => $textos["SEPTIEMBRE"],
    "10" => $textos["OCTUBRE"],
    "11" => $textos["NOVIEMBRE"],
    "12" => $textos["DICIEMBRE"]
);

$dias = array(
    "Sunday"    => $textos["DOMINGO"],
    "Monday"    => $textos["LUNES"],
    "Tuesday"   => $textos["MARTES"],
    "Wednesday" => $textos["MIERCOLES"],
    "Thursday"  => $textos["JUEVES"],
    "Friday"    => $textos["VIERNES"],
    "Saturday"  => $textos["SABADO"]
);

if(!empty($url_fechadescripcion)){
    $date_ = getdate(strtotime($url_fecha));
    $respuesta =$meses[$date_["mon"]]." ".$date_["mday"].$textos["DE"].$date_["year"];
    HTTP::enviarJSON($respuesta);
}

if(!empty($url_validarFechas)){
    $respuesta =true;
    $date = SQL::obtenerValor("domingos_festivos","fecha","fecha='".$url_fecha."'");
    if($date){
        $respuesta =false;
    }
    HTTP::enviarJSON($respuesta);
}

if(!empty($url_generar)){
    $error  = "";
    $titulo = $componente->nombre;
    /**********Genero listado de domingos*************/
    $date = date("Y-m-d");
    $anio = date("Y");
    $dateFinal = $anio."-12-31";
    $date_r = getdate(strtotime($anio."-01-01"));
    $domingos = array();
    $estado   = true;
    $dd       = 0;
    while($estado){
        $date_result = date("Y-m-d", mktime(($date_r["hours"]),($date_r["minutes"]),($date_r["seconds"]),($date_r["mon"]),($date_r["mday"]+$dd),($date_r["year"])));
        if($dateFinal==$date_result){
            $estado   = false;
        }

        $date_ = getdate(strtotime($date_result));
        $nombre = $date_["weekday"];
        $nombre_descripcion = $meses[$date_["mon"]]." ".$date_["mday"].$textos["DE"].$date_["year"];

        if($nombre=='Sunday'){
            $ocultos = HTML::campoOculto("fecha_domingo[]",$date_result, array("class" => "fecha_domingo"));
            $domingos[$dd]= array("",$ocultos.$nombre_descripcion);
        }

        $dd++;
    }
/*** DefiniciÛn de pestaÒa personal ***/
    $formularios["PESTANA_FESTIVOS"] = array(
        array(
            HTML::campoTextoCorto("*fecha", $textos["FECHA"], 10, 10,$date, array("title" => $textos["AYUDA_FECHA"], "class" => "selectorFecha")),
            HTML::campoTextoCorto("descripcion", $textos["DESCRIPCION"], 20, 50,"")
        ),
        array(
            HTML::boton("botonAgregar", $textos["AGREGAR"], "generarFestivo();", "adicionar"),
            HTML::boton("botonEliminar",$textos["ELIMINAR_TODOS"],"removerTable(this);", "eliminar"),
            HTML::contenedor(HTML::boton("botonRemover", "","removerItem(this);", "eliminar"), array("id" => "botonRemover", "style" => "display: none"))
        ),
        array(
            HTML::generarTabla(array("id","", "FECHA","DESCRIPCION"),"",array("I", "I","I"),"listaFestivos",false)
        ),
        array(
            HTML::campoOculto("mensaje",$textos["ERROR_EXISTE_FECHA"]).
            HTML::campoOculto("mensaje_domingo",$textos["ERROR_EXISTE_FECHA_DOMINGO"]).
            HTML::campoOculto("mensaje_base",$textos["ERROR_EXISTE_FECHA_BASE"]).
            HTML::campoOculto("anio",$anio)
        )

    );
    $formularios["PESTANA_DOMINGOS"] = array(
        array(HTML::generarTabla(array("id","FECHA"),$domingos,array("I"),"listaDomingos",false))
    );

/*** DefiniciÛn de botones ***/
    $botones = array(
        HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar")
    );
    $contenido = HTML::generarPestanas($formularios, $botones);
/*** Enviar datos para la generaciÛn del formulario al script que originÛ la peticiÛn ***/
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);
}elseif (!empty($forma_procesar)) {
/*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];

    for($id = 0;!empty($forma_fecha_festivo[$id]); $id++){

        $datos = array (
            "anio"  => $forma_anio,
            "fecha" => $forma_fecha_festivo[$id],
            "tipo"  => 2,
            "descripcion" => $forma_descripcionTabla[$id]
        );
        $insertar = SQL::insertar("domingos_festivos", $datos);
        if(!$insertar){
            $error   = true;
            $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
            $mensaje = mysql_error();
        }
    }

    for($id = 0;!empty($forma_fecha_domingo[$id]); $id++){
        $datos = array (
            "anio"        => $forma_anio,
            "fecha"       => $forma_fecha_domingo[$id],
            "tipo"        => 1,
            "descripcion" => "Domingo"
        );
        $insertar = SQL::reemplazar("domingos_festivos", $datos);
    /*** Error de insercÛn ***/
        if(!$insertar){
            $error   = true;
            $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
            $mensaje = mysql_error();
        }
    }

    /*** Enviar datos con la respuesta del proceso al script que originÛ la peticiÛn ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);

}
?>

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

$periodos = array(
    "1" => $textos["MENSUAL"],
    "2" => $textos["PRIMERA_QUINCENA"],
    "3" => $textos["SEGUNDA_QUINCENA"],
    "4" => $textos["PRIMERA_SEMANA"],
    "5" => $textos["SEGUNDA_SEMANA"],
    "6" => $textos["TERCERA_SEMANA"],
    "7" => $textos["CUARTA_SEMANA"],
    "8" => $textos["QUINTA_SEMANA"],
    "9" => $textos["FECHA_UNICA"]
);


if(!empty($url_generar)){

    if(empty($url_id)){
    $error     = $textos["ERROR_CONSULTAR_VACIO"];
    $titulo    = "";
    $contenido = "";
    }else{
        $error  = "";
        $titulo = $componente->nombre;

        $llave_movimiento = explode("|",$url_id);

        $ano = $llave_movimiento[0];
        $mes = $llave_movimiento[1];
        $codigo_planilla = $llave_movimiento[2];
        $periodo   = $llave_movimiento[3];
        $documento_identidad_empleado = $llave_movimiento[4];
        $consecutivo = $llave_movimiento[5];
        $sucursal    = $llave_movimiento[6];
        $fecha_pago_planilla = $llave_movimiento[7];

        $condicion  = " ano_generacion='$ano' AND mes_generacion='$mes' AND codigo_planilla='$codigo_planilla'";
        $condicion .= " AND periodo_pago='$periodo' AND documento_identidad_empleado='$documento_identidad_empleado'";

        $items_novedades_manuales = array();
        $consulta_movimiento = SQL::seleccionar(array("movimiento_novedades_manuales"),array("*"),$condicion);

        while($movimiento_novedad = SQL::filaEnObjeto($consulta_movimiento)){

            $nombre_transaccion = SQL::obtenerValor("transacciones_contables_empleado","nombre","codigo='$movimiento_novedad->codigo_transaccion_contable'");
            $nombre_anexo       = SQL::obtenerValor("anexos_contables","descripcion","codigo='$movimiento_novedad->codigo_anexo_contable'");
            $nombre_auxiliar    = SQL::obtenerValor("auxiliares_contables","descripcion","codigo='$movimiento_novedad->codigo_auxiliar_contable' AND codigo_empresa='$movimiento_novedad->codigo_empresa_auxiliar' AND codigo_anexo_contable='$movimiento_novedad->codigo_anexo_contable'");

            $items_novedades_manuales[] = array(
             "",
             $nombre_transaccion,
             number_format($movimiento_novedad->valor_movimiento),
             $nombre_anexo,
             $nombre_auxiliar
            );

        }
        //echo var_dump($sucursal);
        $nombre_susursal = SQL::obtenerValor("sucursales","nombre","codigo='$sucursal'");
        $nombre_empleado = SQL::obtenerValor("menu_movimiento_novedades_manuales","EMPLEADO","id='$url_id'");
        //// Definici�n de pesta�a Basica ////
        $formularios["PESTANA_BASICA"] = array(
            array(
                HTML::mostrarDato("nombre_sucursal", $textos["SUCURSAL"], $nombre_susursal),
                HTML::mostrarDato("nombre_empleado", $textos["EMPLEADO"], $nombre_empleado),
           ),
          array(
                HTML::mostrarDato("fecha_pago", $textos["FECHA_PAGO"],$fecha_pago_planilla),
                HTML::mostrarDato("nombre_periodo",$textos["PERIODO"],$periodos[$periodo])
           ),
            array(
                HTML::generarTabla(
                    array("id","TRANSACCION_CONTABLE","VALOR","ANEXO_CONTABLE","AUXILIAR_CONTABLE"),
                    $items_novedades_manuales,
                    array("C","C","C","C"),
                    "novedades_manuales",
                    false
                )
            )
        );
    /*** Definici�n de botones ***/
    $contenido = HTML::generarPestanas($formularios);
    }

    /*** Enviar datos para la generaci�n del formulario al script que origin� la petici�n ***/
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);
}
?>

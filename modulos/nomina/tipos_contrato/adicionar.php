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

    $tipo_contratacion = array(
        "1" => $textos["INTEGRAL"],
        "2" => $textos["DESTAJO"],
        "3" => $textos["PRACTICANTE"],
        "4" => $textos["PASANTIAS"],
        "5" => $textos["PRESTACION_SERVICIOS"],
        "6" => $textos["COOPERATIVA_TRABAJO_ASOCIADO"],
        "7"  => $textos["BASICO_MENOR_MINIMO"],
        "8"  => $textos["BASICO_MAYOR_MINIMO"],
        "9"  => $textos["COMISION_CON_BASICO"],
        "10" => $textos["COMISION_SIN_BASICO"],
    );

    $tipo_planta = array(
        "1"  => $textos["INTEGRAL"],
        "7"  => $textos["BASICO_MENOR_MINIMO"],
        "8"  => $textos["BASICO_MAYOR_MINIMO"],
        "9"  => $textos["COMISION_CON_BASICO"],
        "10" => $textos["COMISION_SIN_BASICO"],
    );


if(isset($url_verificarTipo))
{
    if($url_tipo== 3 )
    {
    $tipo_salario = $tipo_contratacion;
    }else{
       $tipo_salario = $tipo_planta;
    }

    HTTP::enviarJSON($tipo_salario);
    exit;
}


if (!empty($url_generar)) {
    $error  = "";
    $titulo = $componente->nombre;


    $termino_contrato = array(
        "1" => $textos["TERMINO_FIJO"],
        "2" => $textos["TERMINO_INDEFINIDO"],
        "3" => $textos["SIN_RELACION_LABORAL"],
        "4" => $textos["OBRA_LABOR"]
    );

   $consulta_terceros = SQL::seleccionar(array("terceros"), array("*"),"documento_identidad !=0");
    $codigo = SQL::obtenerValor("tipos_contrato","MAX(codigo)","codigo>0");
    if ($codigo){
        $codigo++;
    } else {
        $codigo=1;
    }

    //$codigo = str_pad($codigo,3,"0", STR_PAD_LEFT);

    $formularios["PESTANA_GENERAL"] = array(
        array(
            HTML::campoTextoCorto("*codigo", $textos["CODIGO"], 5, 3, $codigo, array("title" => $textos["AYUDA_CODIGO"], "onblur" => "validarItem(this);","onKeyPress" => "return campoEntero(event)")),
        ),
        array(
            HTML::campoTextoCorto("*descripcion", $textos["DESCRIPCION_TIPO_CONTRATO"], 30, 255, "", array("title" => $textos["AYUDA_NOMBRE_TIPO_CONTRATO"], "onblur" => "validarItem(this);")),
        ),
        array(
            HTML::mostrarDato("etiqueta_tipo", $textos["AJUSTA_TRABAJADOR"], ""),
        ),
        array(

            HTML::listaSeleccionSimple("*termino_contrato", $textos["TERMINO_CONTRATO"],$termino_contrato,0, array("title" => $textos["AYUDA_TERMINO_CONTRATO"],"onchange" => "cargarTipos(this);"))
        ),
        array(
            HTML::listaSeleccionSimple("*tipo_contratacion", $textos["TIPO_CONTRATACION"], $tipo_planta, 0, array("title" => $textos["AYUDA_TIPO_CONTRATACION"],"onchange" => "OcultarCampos(this);"))
        ),
        array(

            HTML::mostrarDato("se_ajusta_minimo", $textos["AJUSTA_MINIMO"], ""),
            HTML::marcaSeleccion("ajusta_minimo", $textos["AJUSTA_MINIMO_SI"], 1, true, array("id" => "ajusta_minimo_si")),
            HTML::marcaSeleccion("ajusta_minimo", $textos["AJUSTA_MINIMO_NO"], 0, false, array("id" => "ajusta_minimo_no"))

       )
    );

    $botones = array(
        HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar")
    );

    $contenido = HTML::generarPestanas($formularios, $botones);

    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

} elseif (!empty($url_validar)) {

    if ($url_item == "codigo" && $url_valor) {
        $existe_codigo = SQL::existeItem("tipos_contrato", "codigo", $url_valor,"codigo != 0");

        if ($existe_codigo) {
              HTTP::enviarJSON($textos["ERROR_EXISTE_TIPO_CONTRATO"]);
        }
    }else if ($url_item == "descripcion" && $url_valor) {
        $existe_descripcion = SQL::existeItem("tipos_contrato", "descripcion", $url_valor,"codigo != 0");

        if ($existe_descripcion) {
              HTTP::enviarJSON($textos["ERROR_EXISTE_DESCRIPCION_TIPO_CONTRATO"]);
        }
    }
} elseif (!empty($forma_procesar)) {

    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];

    if (empty($forma_descripcion)){
        $error = true;
        $mensaje = $textos["ERROR_DESCRPCION_VACIO"];
    } elseif (SQL::existeItem("tipos_contrato", "descripcion", $forma_descripcion,"codigo != 0")) {
        $error   = true;
        $mensaje =  $textos["ERROR_EXISTE_DESCRIPCION_TIPO_CONTRATO"];
    } elseif (SQL::existeItem("tipos_contrato", "codigo", $forma_codigo,"codigo != 0")) {
        $error   = true;
        $mensaje =  $textos["ERROR_EXISTE_TIPO_CONTRATO"];
    } else {

        $datos = array (
            "codigo"                   => $forma_codigo,
            "descripcion"              => $forma_descripcion,
            "termino_contrato"         => $forma_termino_contrato,
            "tipo_contratacion"        => $forma_tipo_contratacion,
            "sueldo_ajusta_minimo"     => $forma_ajusta_minimo
        );

        $insertar = SQL::insertar("tipos_contrato", $datos);

        if (!$insertar) {
            $error   = true;
            $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
        }
    }

    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

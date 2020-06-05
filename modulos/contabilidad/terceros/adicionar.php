<?php

/***
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
* de APTITUD PARA UN PROPÓSITO DETERMINADO. Consulte los detalles de
* la Licencia Pública General GNU para obtener una información más
* detallada.
*
* Debería haber recibido una copia de la Licencia Pública General GNU
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
***/

// Devolver datos para autocompletar la búsqueda
if(isset($url_completar)){
    if(($url_item) == "selector1"){
        echo SQL::datosAutoCompletar("seleccion_municipios", $url_q);
    }
    if($url_item == "selector2"){
        echo SQL::datosAutoCompletar("seleccion_localidades", $url_q);
    }
    exit;
}

// Generar el formulario para la captura de datos
if (!empty($url_generar)) {
    $error   = "";
    $titulo  = $componente->nombre;

    $validar             = false;
    $validar_documentos  = false;
    $validar_municipios  = false;
    $validar_localidades = false;

    $consulta = SQL::seleccionar(array("tipos_documento_identidad"),array("*"),"codigo >0");
    if (SQL::filasDevueltas($consulta)){
        while($datos = SQL::filaEnObjeto($consulta)){
            $tipos_documentos[$datos->codigo] = $datos->descripcion;
        }
    } else {
        $validar = true;
        $validar_documentos = true;

    }

    $consulta = SQL::seleccionar(array("municipios"),array("*"),"codigo_dane_municipio !=''");
    if (!SQL::filasDevueltas($consulta)){
        $validar = true;
        $validar_municipios = true;
    }

    $consulta = SQL::seleccionar(array("localidades"),array("*"),"codigo_dane_localidad !=''");
    if (!SQL::filasDevueltas($consulta)){
        $validar = true;
        $validar_localidades = true;
    }

    if (!$validar){

        //$preferencia_documento = SQL::obtenerValor("preferencias","valor","variable='tipo_documento_identidad' AND tipo_preferencia='1'");

        // Definicion de pestañas general
        $formularios["PESTANA_GENERAL"] = array(
            array(
                    HTML::listaSeleccionSimple("*codigo_tipo_documento", $textos["TIPO_DOCUMENTO_IDENTIDAD"], $tipos_documentos, "",array("title" => $textos["AYUDA_TIPO_DOCUMENTO"])),
                    HTML::campoTextoCorto("*selector1", $textos["MUNICIPIO"], 32, 255, "", array("title" => $textos["AYUDA_MUNICIPIO_DOCUMENTO"], "class" => "autocompletable"))
                    .HTML::campoOculto("codigo_municipio_documento", "")
            ),
            array(
                HTML::mostrarDato("nombre_genero", $textos["TIPO_PERSONA"], ""),
                HTML::marcaSeleccion("tipo_persona", $textos["PERSONA_NATURAL"], '1', false, array("id" => "persona_natural", "onClick" => "activarNombres(1)")),
                HTML::marcaSeleccion("tipo_persona", $textos["PERSONA_JURIDICA"], '2', true, array("id" => "persona_juridica", "onClick" => "activarNombres(2)")),
                HTML::marcaSeleccion("tipo_persona", $textos["CODIGO_INTERNO"], '3', false, array("id" => "codigo_interno", "onClick" => "activarNombres(3)")),
                HTML::marcaSeleccion("tipo_persona", $textos["NATURAL_COMERCIANTE"], '4', false, array("id" => "natural_comerciante", "onClick" => "activarNombres(4)"))
            ),
            array(
                HTML::campoTextoCorto("*documento_identidad", $textos["DOCUMENTO_IDENTIDAD"], 15, 12, "",array("title" => $textos["AYUDA_DOCUMENTO_IDENTIDAD"],"onblur" => "validarItem(this)")),
                HTML::campoTextoCorto("fecha_nacimiento", $textos["FECHA_NACIMIENTO"], 10, 10, "", array("title" => $textos["AYUDA_FECHA_NACIMIENTO"], "class" => "fechaAntigua"))
            ),
            array(
                HTML::campoTextoCorto("*primer_nombre", $textos["PRIMER_NOMBRE"], 20, 15, "", array("title" => $textos["AYUDA_PRIMER_NOMBRE"],"class" => "oculto")),
                HTML::campoTextoCorto("segundo_nombre", $textos["SEGUNDO_NOMBRE"], 20, 15, "", array("title" => $textos["AYUDA_SEGUNDO_NOMBRE"],"class" => "oculto"))
            ),
            array(
                HTML::campoTextoCorto("*primer_apellido", $textos["PRIMER_APELLIDO"], 20, 20, "", array("title" => $textos["AYUDA_PRIMER_APELLIDO"],"class" => "oculto")),
                HTML::campoTextoCorto("segundo_apellido", $textos["SEGUNDO_APELLIDO"], 20, 20, "", array("title" => $textos["AYUDA_SEGUNDO_APELLIDO"],"class" => "oculto"))
            ),
            array(
                HTML::campoTextoCorto("*razon_social", $textos["RAZON_SOCIAL"], 47, 255, "", array("title" => $textos["AYUDA_RAZON_SOCIAL"])),
                HTML::campoTextoCorto("nombre_comercial", $textos["NOMBRE_COMERCIAL"], 47, 255, "", array("title" => $textos["AYUDA_NOMBRE_COMERCIAL"]))
            ),
            array(
                HTML::mostrarDato("nombre_genero", $textos["GENERO"], ""),
                HTML::marcaSeleccion("genero", $textos["MASCULINO"], 'M', false, array("id" => "genero_masculino")),
                HTML::marcaSeleccion("genero", $textos["FEMENINO"], 'F', false, array("id" => "genero_femenino")),
                HTML::marcaSeleccion("genero", $textos["NO_APLICA"], 'N', true, array("id" => "no_aplica_genero"))
            )
        );
        $formularios["PESTANA_PERSONAL"] = array(
            array(
                HTML::campoTextoCorto("*selector2", $textos["LOCALIDAD"], 30, 255, "", array("title" => $textos["AYUDA_LOCALIDAD"], "class" => "autocompletable"))
                .HTML::campoOculto("codigo_localidad_residencia", ""),
                HTML::campoTextoCorto("direccion_principal", $textos["DIRECCION"], 30, 50, "", array("title" => $textos["AYUDA_DIRECCION"]))
            ),
            array(
                HTML::campoTextoCorto("telefono_principal", $textos["TELEFONO"], 19, 15, "", array("title" => $textos["AYUDA_TELEFONO"])),
                HTML::campoTextoCorto("fax", $textos["FAX"], 19, 20, "", array("title" => $textos["AYUDA_FAX"])),
                HTML::campoTextoCorto("celular", $textos["CELULAR"], 19, 20, "", array("title" => $textos["AYUDA_CELULAR"]))
            ),
            array(
                HTML::campoTextoCorto("correo", $textos["CORREO"], 31, 255, "", array("title" => $textos["AYUDA_CORREO"])),
                HTML::campoTextoCorto("sitio_web", $textos["SITIO_WEB"], 31, 50, "", array("title" => $textos["AYUDA_SITIO_WEB"]))
            ),
            array(
                HTML::campoTextoCorto("correo2", $textos["CORREO2"], 31, 255, "", array("title" => $textos["AYUDA_CORREO"])),
                HTML::campoTextoCorto("celular2", $textos["CELULAR2"], 20, 20, "", array("title" => $textos["AYUDA_CELULAR"]))
            )
        );

        // Definicion de botones
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones);
    } else {
        $contenido = "";
        $error     = $textos["NO_EXISTEN"]."\n";
        if ($validar_documentos){
            $error .= $textos["TIPOS_DOCUMENTO_IDENTIDAD"]."\n";
        }
        if ($validar_municipios){
            $error .= $textos["MUNICIPIOS"]."\n";
        }
        if ($validar_localidades){
            $error .= $textos["LOCALIDADES"]."\n";
        }
        $error .= "\n".$textos["CREAR"];
    }

    // Enviar datos para la generacion del formulario al script que origino la peticion
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);


}

if (!empty($url_validar)) {
    $respuesta = "";
    if ($url_item == "documento_identidad" && $url_valor) {
        $existe = SQL::existeItem("terceros", "documento_identidad", $url_valor);
        if ($existe) {
            $respuesta = $textos["ERROR_DOCUMENTO_IDENTIDAD_EXISTE"];
        }
    }
    HTTP::enviarJSON($respuesta);
}

if (!empty($forma_procesar)) {
    // Asumir por defecto que no hubo error
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];
    $forma_selector1 = utf8_decode($forma_selector1);
    $forma_selector2 = utf8_decode($forma_selector2);

    if(empty($forma_documento_identidad)){
        $error   = true;
        $mensaje = $textos["ERROR_DOCUMENTO_IDENTIDAD_VACIO"];

    }elseif(SQL::existeItem("terceros","documento_identidad",$forma_documento_identidad)){
        $error   = true;
        $mensaje = $textos["ERROR_DOCUMENTO_IDENTIDAD_EXISTE"];

    }elseif(empty($forma_selector1)){
        $error   = true;
        $mensaje = $textos["ERROR_MUNICIPIO_EXPEDICION_VACIO"];

    }elseif(!SQL::existeItem("seleccion_municipios","nombre",$forma_selector1.'|'.$forma_codigo_municipio_documento)){
        $error   = true;
        $mensaje = $textos["ERROR_MUNICIPIO_NO_EXISTE"];

    }elseif(empty($forma_selector2)){
        $error   = true;
        $mensaje = $textos["ERROR_BARRIO_CORREGIMIENTO_VACIO"];

    }elseif(!SQL::existeItem("seleccion_localidades","nombre",$forma_selector2.'|'.$forma_codigo_localidad_residencia)){
        $error   = true;
        $mensaje = $textos["ERROR_BARRIO_CORREGIMIENTO_NO_EXISTE"];

    }elseif(($forma_tipo_persona=='1' || $forma_tipo_persona=='4') && (empty($forma_primer_nombre) || empty($forma_primer_apellido))){
        $error   = true;
        $mensaje = $textos["ERROR_NOMBRE_APELLIDO_VACIO"];

    }elseif(!empty($forma_correo) && !Cadena::validarCorreo($forma_correo)) {
        $error   = true;
        $mensaje =  $textos["ERROR_SINTAXIS_CORREO"];

    }elseif(!empty($forma_correo2) && !Cadena::validarCorreo($forma_correo2)) {
        $error   = true;
        $mensaje =  $textos["ERROR_SINTAXIS_CORREO"];

    }elseif(($forma_tipo_persona=='2' || $forma_tipo_persona=='3') && empty($forma_razon_social)){
        $error   = true;
        $mensaje = $textos["ERROR_RAZON_SOCIAL_VACIO"];

    }else {
        // Insertar datos

        if(!isset($forma_nombre_comercial)){
            $forma_nombre_comercial = "";
        }

        if(!isset($forma_razon_social)){
            $forma_razon_social = "";
        }

        if(!isset($forma_primer_nombre)){
            $forma_primer_nombre = "";
        }

        if(!isset($forma_segundo_nombre)){
            $forma_segundo_nombre = "";
        }

        if(!isset($forma_primer_apellido)){
            $forma_primer_apellido = "";
        }

        if(!isset($forma_segundo_apellido)){
            $forma_segundo_apellido = "";
        }

        $forma_primer_nombre    = trim($forma_primer_nombre);
        $forma_segundo_nombre   = trim($forma_segundo_nombre);
        $forma_primer_apellido  = trim($forma_primer_apellido);
        $forma_segundo_apellido = trim($forma_segundo_apellido);
        $forma_razon_social     = trim($forma_razon_social);
        $forma_nombre_comercial = trim($forma_nombre_comercial);

        $municipio_documento  = explode(',',$forma_codigo_municipio_documento);
        $localidad_residencia = explode(',',$forma_codigo_localidad_residencia);

        $datos = array(
            "documento_identidad"                => $forma_documento_identidad,
            "codigo_tipo_documento"              => $forma_codigo_tipo_documento,
            "codigo_iso_municipio_documento"     => $municipio_documento[0],
            "codigo_dane_departamento_documento" => $municipio_documento[1],
            "codigo_dane_municipio_documento"    => $municipio_documento[2],
            "tipo_persona"                       => $forma_tipo_persona,
            "primer_nombre"                      => $forma_primer_nombre,
            "segundo_nombre"                     => $forma_segundo_nombre,
            "primer_apellido"                    => $forma_primer_apellido,
            "segundo_apellido"                   => $forma_segundo_apellido,
            "razon_social"                       => $forma_razon_social,
            "nombre_comercial"                   => $forma_nombre_comercial,
            "fecha_nacimiento"                   => $forma_fecha_nacimiento,
            "codigo_iso_localidad"               => $localidad_residencia[0],
            "codigo_dane_departamento_localidad" => $localidad_residencia[1],
            "codigo_dane_municipio_localidad"    => $localidad_residencia[2],
            "tipo_localidad"                     => $localidad_residencia[3],
            "codigo_dane_localidad"              => $localidad_residencia[4],
            "direccion_principal"                => $forma_direccion_principal,
            "telefono_principal"                 => $forma_telefono_principal,
            "celular"                            => $forma_celular,
            "celular2"                           => $forma_celular2,
            "fax"                                => $forma_fax,
            "correo"                             => $forma_correo,
            "correo2"                            => $forma_correo2,
            "sitio_web"                          => $forma_sitio_web,
            "genero"                             => $forma_genero,
            "activo"                             => '1',
            "fecha_ingreso"                      => date("Y-m-d")
        );
        $insertar = SQL::insertar("terceros", $datos);
        // Verificar si hubo error
        if (!$insertar){
            $error   = true;
            $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
        }
    }
    // Enviar datos con la respuesta del proceso al script que originó la petición
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

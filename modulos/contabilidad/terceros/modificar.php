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
* de APTITUD PARA UN PROPÓSITO DETERMINADO. Consulte los detalles de
* la Licencia Pública General GNU para obtener una información más
* detallada.
*
* Debería haber recibido una copia de la Licencia Pública General GNU
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
**/

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

/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {

    /*** Verificar que se haya enviado el ID del elemento a modificar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_MODIFICAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {
        $vistaConsulta = "terceros";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "documento_identidad = '$url_id'");
        $datos         = SQL::filaEnObjeto($consulta);

        $error         = "";
        $titulo        = $componente->nombre;

        $llave_municipio = $datos->codigo_iso_municipio_documento.'|'.$datos->codigo_dane_departamento_documento.'|'.$datos->codigo_dane_municipio_documento;
        $llave_localidad = $datos->codigo_iso_localidad.'|'.$datos->codigo_dane_departamento_localidad.'|'.$datos->codigo_dane_municipio_localidad.'|'.$datos->tipo_localidad.'|'.$datos->codigo_dane_localidad;

        $municipio       = explode('|',SQL::obtenerValor("seleccion_municipios","nombre","id = '".$llave_municipio."'"));
        $localidad       = explode('|',SQL::obtenerValor("seleccion_localidades","nombre","id = '".$llave_localidad."'"));

        if($datos->tipo_persona=='1'){
            $tp1       = true;
            $tp2       = false;
            $tp3       = false;
            $tp4       = false;
            $nombres   = "";
            $razon     = "oculto";
            $comercial = "oculto";
        }elseif($datos->tipo_persona=='2'){
            $tp1       = false;
            $tp2       = true;
            $tp3       = false;
            $tp4       = false;
            $nombres   = "oculto";
            $razon     = "";
            $comercial = "";
        }elseif($datos->tipo_persona=='3'){
            $tp1       = false;
            $tp2       = false;
            $tp3       = true;
            $tp4       = false;
            $nombres   = "oculto";
            $razon     = "";
            $comercial = "";
        }elseif($datos->tipo_persona=='4'){
            $tp1       = false;
            $tp2       = false;
            $tp3       = false;
            $tp4       = true;
            $nombres   = "";
            $razon     = "oculto";
            $comercial = "";
        }

        if($datos->genero=='M'){
            $masculino = true;
            $femenino  = false;
            $no_aplica = false;
        }elseif($datos->genero=='F'){
            $masculino = false;
            $femenino  = true;
            $no_aplica = false;
        }elseif($datos->genero=='N'){
            $masculino = false;
            $femenino  = false;
            $no_aplica = true;
        }

        $activo = array(
            "0" => $textos["INACTIVO"],
            "1" => $textos["ACTIVO"]
        );

        /*** Definición de pestañas ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::listaSeleccionSimple("*codigo_tipo_documento", $textos["TIPO_DOCUMENTO_IDENTIDAD"], HTML::generarDatosLista("tipos_documento_identidad", "codigo", "descripcion","codigo!='0'"),$datos->codigo_tipo_documento,array("title" => $textos["AYUDA_TIPO_DOCUMENTO"])),
                HTML::campoTextoCorto("*selector1", $textos["MUNICIPIO"], 32, 255, $municipio[0], array("title" => $textos["AYUDA_MUNICIPIO_DOCUMENTO"], "class" => "autocompletable"))
                .HTML::campoOculto("codigo_municipio_documento", $municipio[1])
            ),
            array(
                HTML::mostrarDato("nombre_persona", $textos["TIPO_PERSONA"], ""),
                HTML::marcaSeleccion("tipo_persona", $textos["PERSONA_NATURAL"], '1', $tp1, array("id" => "persona_natural", "onClick" => "activarNombres(1)")),
                HTML::marcaSeleccion("tipo_persona", $textos["PERSONA_JURIDICA"], '2', $tp2, array("id" => "persona_juridica", "onClick" => "activarNombres(2)")),
                HTML::marcaSeleccion("tipo_persona", $textos["CODIGO_INTERNO"], '3', $tp3, array("id" => "codigo_interno", "onClick" => "activarNombres(3)")),
                HTML::marcaSeleccion("tipo_persona", $textos["NATURAL_COMERCIANTE"], '4', $tp4, array("id" => "natural_comerciante", "onClick" => "activarNombres(4)"))
            ),
            array(
                HTML::campoTextoCorto("*documento_identidad", $textos["DOCUMENTO_IDENTIDAD"], 15, 12, $datos->documento_identidad,array("title" => $textos["AYUDA_DOCUMENTO_IDENTIDAD"],"onblur" => "validarItem(this)")),
                HTML::campoTextoCorto("fecha_nacimiento", $textos["FECHA_NACIMIENTO"], 10, 10, $datos->fecha_nacimiento, array("title" => $textos["AYUDA_FECHA_NACIMIENTO"], "class" => "fechaAntigua"))
            ),
            array(
                HTML::campoTextoCorto("*primer_nombre", $textos["PRIMER_NOMBRE"], 20, 15, $datos->primer_nombre, array("title" => $textos["AYUDA_PRIMER_NOMBRE"],"class" => $nombres)),
                HTML::campoTextoCorto("segundo_nombre", $textos["SEGUNDO_NOMBRE"], 20, 15, $datos->segundo_nombre, array("title" => $textos["AYUDA_SEGUNDO_NOMBRE"],"class" => $nombres))
            ),
            array(
                HTML::campoTextoCorto("*primer_apellido", $textos["PRIMER_APELLIDO"], 20, 20, $datos->primer_apellido, array("title" => $textos["AYUDA_PRIMER_APELLIDO"],"class" => $nombres)),
                HTML::campoTextoCorto("segundo_apellido", $textos["SEGUNDO_APELLIDO"], 20, 20, $datos->segundo_apellido, array("title" => $textos["AYUDA_SEGUNDO_APELLIDO"],"class" => $nombres))
            ),
            array(
                HTML::campoTextoCorto("*razon_social", $textos["RAZON_SOCIAL"], 47, 255, $datos->razon_social, array("title" => $textos["AYUDA_RAZON_SOCIAL"],"class" => $razon)),
                HTML::campoTextoCorto("nombre_comercial", $textos["NOMBRE_COMERCIAL"], 47,255,$datos->nombre_comercial, array("title" => $textos["AYUDA_NOMBRE_COMERCIAL"],"class" => $comercial))
            ),
            array(
                HTML::mostrarDato("nombre_genero", $textos["GENERO"], ""),
                HTML::marcaSeleccion("genero", $textos["MASCULINO"], 'M', $masculino, array("id" => "genero_masculino")),
                HTML::marcaSeleccion("genero", $textos["FEMENINO"], 'F', $femenino, array("id" => "genero_femenino")),
                HTML::marcaSeleccion("genero", $textos["NO_APLICA"], 'N', $no_aplica, array("id" => "no_aplica_genero"))
            ),
            array(
                HTML::listaSeleccionSimple("activo", $textos["ESTADO"], $activo,$datos->activo,array("title" => $textos["AYUDA_ESTADO"]))
            )
        );
        $formularios["PESTANA_PERSONAL"] = array(
            array(
                HTML::campoTextoCorto("*selector2", $textos["LOCALIDAD"], 30, 255, $localidad[0], array("title" => $textos["AYUDA_LOCALIDAD"], "class" => "autocompletable"))
                .HTML::campoOculto("codigo_localidad_residencia", $localidad[1]),
                HTML::campoTextoCorto("direccion_principal", $textos["DIRECCION"], 30, 50, $datos->direccion_principal, array("title" => $textos["AYUDA_DIRECCION"]))
            ),
            array(
                HTML::campoTextoCorto("telefono_principal", $textos["TELEFONO"], 19, 15, $datos->telefono_principal, array("title" => $textos["AYUDA_TELEFONO"])),
                HTML::campoTextoCorto("fax", $textos["FAX"], 19, 20, $datos->fax, array("title" => $textos["AYUDA_FAX"])),
                HTML::campoTextoCorto("celular", $textos["CELULAR"], 19, 20, $datos->celular, array("title" => $textos["AYUDA_CELULAR"]))
            ),
            array(
                HTML::campoTextoCorto("correo", $textos["CORREO"], 31, 255, $datos->correo, array("title" => $textos["AYUDA_CORREO"])),
                HTML::campoTextoCorto("sitio_web", $textos["SITIO_WEB"], 31, 50, $datos->sitio_web, array("title" => $textos["AYUDA_SITIO_WEB"]))
            ),
            array(
                HTML::campoTextoCorto("correo2", $textos["CORREO2"], 31, 255, $datos->correo2, array("title" => $textos["AYUDA_CORREO"])),
                HTML::campoTextoCorto("celular2", $textos["CELULAR2"], 20, 20, $datos->celular2, array("title" => $textos["AYUDA_CELULAR"]))
            )
        );

        /*** Definición de botones ***/
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "modificarItem('".$url_id."');", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones);
    }

    /*** Enviar datos para la generación del formulario al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);
}

if (!empty($url_validar)) {
    $respuesta = "";
    if ($url_item == "documento_identidad" && $url_valor) {
        $existe = SQL::existeItem("terceros", "documento_identidad", $url_valor,"documento_identidad != '".$url_id."'");
        if ($existe) {
            $respuesta = $textos["ERROR_DOCUMENTO_IDENTIDAD_EXISTE"];
        }
    }
    HTTP::enviarJSON($respuesta);
}

if (!empty($forma_procesar)) {
    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_MODIFICADO"];
    $forma_selector1 = utf8_decode($forma_selector1);
    $forma_selector2 = utf8_decode($forma_selector2);

    if(empty($forma_documento_identidad)){
        $error   = true;
        $mensaje = $textos["ERROR_DOCUMENTO_IDENTIDAD_VACIO"];

    }elseif(SQL::existeItem("terceros","documento_identidad",$forma_documento_identidad,"documento_identidad != '".$forma_id."'")){
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

    }elseif(!empty($forma_correo) && !Cadena::validarCorreo($forma_correo)) {
        $error   = true;
        $mensaje =  $textos["ERROR_SINTAXIS_CORREO"];

    }elseif(($forma_tipo_persona=='1' || $forma_tipo_persona=='4') && (empty($forma_primer_nombre) || empty($forma_primer_apellido))){
        $error   = true;
        $mensaje = $textos["ERROR_NOMBRE_APELLIDO_VACIO"];

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
            "activo"                             => $forma_activo
        );
        $insertar = SQL::modificar("terceros", $datos,"documento_identidad = '".$forma_id."'");
        // Verificar si hubo error
        if (!$insertar){
            $error   = true;
            $mensaje = $textos["ERROR_MODIFICAR_ITEM"];
        }
    }

    /*** Enviar datos con la respuesta del proceso al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

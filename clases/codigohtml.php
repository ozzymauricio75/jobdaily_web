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

class HTML {

    /*** Generar elemento de formulario para ingreso de texto de una sola línea ***/
    public static function mostrarDato($id, $etiqueta, $dato) {
        $elemento = "";

        if (!empty($etiqueta)) {
            $elemento = "<span class=\"etiqueta\">$etiqueta:</span>\n";
        }

        $elemento .= "<span id=\"$id\" class=\"dato\">$dato</span>\n";

        return $elemento;
    }

    /*** Generar elemento de formulario para ingreso de texto de una sola línea ***/


    /*** Crear código para insertar un formulario ***/
    public static function formulario($destino, $metodo, $contenido, $nombre) {
        $codigo  = HTML::contenedor("", array("id" => "indicadorEsperaComando"));
        $codigo .= "<form id=\"$nombre\" action=\"$destino\" ";
        if (!empty($opciones)) {
            $listaOpciones = array();

            foreach ($opciones as $atributo => $valor) {
                $listaOpciones[] = "$atributo=\"$valor\"";
            }

            $opciones = implode(" ", $listaOpciones);
            $codigo  .= " $opciones";
        }

        $codigo .= "method=\"".strtoupper($metodo)."\" enctype=\"multipart/form-data\">\n";
        $codigo .= "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"20000000\">\n$contenido";
        $codigo .= "</form>\n";
        return $codigo;
    }

    /*** Crear código para insertar una fila de campos en un formulario ***/
    public static function filaFormulario($datos, $alineacion = "I") {

        $celdas  = count($datos);
        $ancho   = floor(90 / $celdas);
        $codigo  = "";

        $alineacion = self::mapearAlineacion($alineacion);

        foreach ($datos as $celda) {
            $codigo .= self::contenedor($celda, array("class" => "celdaFormulario", "style" => "text-align: $alineacion"));
        }

        $codigo = self::contenedor($codigo, array("class" => "filaFormulario"));
        return $codigo;
    }

    /*** Crear código para insertar un formulario con pestañas ***/
    public static function generarPestanas($formularios, $botones = "", $opciones = "", $funciones = "") {
        global $textos, $componente;

        $lista     = "";
        $contenido = "";
        $inicio1   = true;
        $inicio2   = true;
        $codigo    = "";
        $nombre_funcion = "";

        foreach ($formularios as $pestana => $filas) {
            $celdas = "";
            if (!empty($funciones)) {
                if (isset($funciones[$pestana])){
                    $nombre_funcion = $funciones[$pestana];
                }
            }

            $lista .= "<li class=\"ui-tabs-nav-item\"><a onclick=\"$nombre_funcion\" href=\"#$pestana\">".$textos[$pestana]."</a></li>\n";

            foreach ($filas as $fila) {
                $celdas .= self::filaFormulario($fila);
            }
            $nombre_funcion = "";
            $contenido .= "<div id=\"$pestana\">$celdas</div>";
        }

        if (!empty($opciones)) {
            $listaOpciones = array();

            foreach ($opciones as $atributo => $valor) {
                $listaOpciones[0] = $atributo;
                $listaOpciones[1] = $valor;
            }
        } else {
            $listaOpciones[0] = "id";
            $listaOpciones[1] = "pestanas";
        }

        //$codigo .= self::contenedor("<ul class=\"ui-tabs-nav\">\n$lista</ul>\n$contenido\n", array("id" => "pestanas"));
        $codigo .= self::contenedor("<ul class=\"ui-tabs-nav\">\n$lista</ul>\n$contenido\n", array($listaOpciones[0] => $listaOpciones[1]));
        $codigo .= self::campoOculto("URLFormulario", HTTP::generarURL($componente->id));

        if (!empty($botones)) {
            $codigo .= HTML::contenedor(HTML::filaFormulario($botones, "C"), array("id" => "botonesDialogo"));
        }

        $codigo  = self::formulario(HTTP::generarURL($componente->id),"POST",$codigo,"formularioPrincipal");
        $codigo .= "<div id=\"errorCuadroDialogo\"><span id=\"errorDialogo\"></span></div>\n";

        return $codigo;

    }

    /*** Crear código para insertar una imagen ***/
    public static function imagen($ruta, $opciones = "") {
        $elemento = "<img src=\"$ruta\" alt=\"\"";

        if (!empty($opciones)) {
            $listaOpciones = array();

            foreach ($opciones as $atributo => $valor) {
                $listaOpciones[] = "$atributo=\"$valor\"";
            }

            $opciones = implode(" ", $listaOpciones);
            $elemento .= " $opciones";
        }

        $elemento .= ">\n";
        return $elemento;
    }

    /*** Crear código para insertar un botón de texto ***/
    public static function boton($id, $texto, $accion, $icono = "", $opciones = "") {
        global $imagenesGlobales;

        $clase        = "botonTexto";
        $esComponente = SQL::existeItem("componentes","id",$id);

        if ($esComponente) {
            $componenteBoton = new Componente($id);

            if (!$componenteBoton->usuarioPermitido()) {
                return NULL;
            }
        }

        $elemento = "<span id=\"$id\" onclick=\"$accion\"";

        if (!empty($opciones)) {
            $listaOpciones = array();

            foreach ($opciones as $atributo => $valor) {
                if (strtolower($atributo) == "class") {
                    $clase .= " $valor";
                } else {
                    $listaOpciones[] = "$atributo=\"$valor\"";
                }
            }

            $opciones = implode(" ", $listaOpciones);
            $elemento .= " $opciones";
        }

        $elemento .= "class=\"$clase\">";

        if (!empty($icono)) {
            $elemento .= self::imagen($imagenesGlobales[$icono]);
        }

        if (!empty($texto)) {
            $texto = "<span class=\"textoBoton\">$texto</span>";
        }

        $elemento .= "$texto</span>\n";

        return $elemento;
    }

    /*** Crear código para insertar un checkbox ***/
    public static function marcaChequeo($nombre, $texto, $valor = 1, $marcada = false, $opciones = "") {
        ($marcada) ? $marcada = "checked" : $marcada = "";

        $clase     = "campo";
        $elemento  = "<label for=\"$nombre\"class=\"dato\"><input type=\"checkbox\" $marcada name=\"$nombre\" value=\"$valor\"\n";

        if (!empty($opciones)) {
            $listaOpciones = array();

            foreach ($opciones as $atributo => $valor) {
                if (strtolower($atributo) == "class") {
                    $clase .= " $valor";
                } else {
                    $listaOpciones[] = "$atributo=\"$valor\"";
                }
            }

            $opciones = implode(" ", $listaOpciones);
            $elemento .= " $opciones";
        }

        $elemento .= "class=\"$clase\"> $texto</label>\n";
        return $elemento;
    }

    /*** Crear código para insertar un checkbox ***/
    public static function marcaSeleccion($nombre, $texto, $valor = 1, $marcada = false, $opciones = "") {
        ($marcada) ? $marcada = "checked" : $marcada = "";

        $clase     = "campo";
        $elemento  = "<label for=\"$nombre\" class=\"dato\"><input type=\"radio\" $marcada name=\"$nombre\" value=\"$valor\"\n";

        if (!empty($opciones)) {
            $listaOpciones = array();

            foreach ($opciones as $atributo => $valor) {
                if (strtolower($atributo) == "class") {
                    $clase .= " $valor";
                } else {
                    $listaOpciones[] = "$atributo=\"$valor\"";
                }
            }

            $opciones = implode(" ", $listaOpciones);
            $elemento .= " $opciones";
        }

        $elemento .= "class=\"$clase\"> $texto</label>\n";
        return $elemento;
    }

    /*** Crear código para insertar un rectángulo (div) ***/
    public static function contenedor($contenido, $opciones = "") {
        $elemento = "<div";

        if (!empty($opciones)) {
            $listaOpciones = array();

            foreach ($opciones as $atributo => $valor) {
                $listaOpciones[] = "$atributo=\"$valor\"";
            }

            $opciones = implode(" ", $listaOpciones);
            $elemento .= " $opciones";
        }

        $elemento .= ">$contenido</div>\n";
        return $elemento;
    }

      /*** Crear código para insertar un rectángulo (div) ***/
    public static function contenedorSpan($contenido, $opciones = "") {
        $elemento = "<span";

        if (!empty($opciones)) {
            $listaOpciones = array();

            foreach ($opciones as $atributo => $valor) {
                $listaOpciones[] = "$atributo=\"$valor\"";
            }

            $opciones = implode(" ", $listaOpciones);
            $elemento .= " $opciones";
        }

        $elemento .= ">$contenido</span>\n";
        return $elemento;
    }

    /*** Crear código para insertar un formulario ***/
    public static function campoOculto($id, $valorInicial, $opciones = "") {
        $elemento = "<input type=\"hidden\" id=\"$id\" name=\"$id\"";

        if (!empty($opciones)) {
            $listaOpciones = array();

            foreach ($opciones as $atributo => $valor) {
                $listaOpciones[] = "$atributo=\"$valor\"";
            }

            $opciones = implode(" ", $listaOpciones);
            $elemento .= " $opciones";
        }

        $elemento .= " value=\"$valorInicial\">\n";
        return $elemento;
    }

    /*** Generar elemento de formulario para ingreso de texto de una sola línea ***/
    public static function campoTextoCorto($id, $etiqueta, $longitud, $limite, $valor = "", $opciones = "") {
        global $textos, $imagenesGlobales;
        $elemento  = "";
        $clase     = "campo";
        $requerido = "";

        if (substr($id, 0, 1) == "*") {
            $id        = str_replace("*", "", $id);
            $clase     = "campo requerido";
            $requerido = self::imagen($imagenesGlobales["requerido"], array("title" => $textos["CAMPO_REQUERIDO"], "class" => "indicadorRequerido"));
        } else if(substr($id, 0, 1) == "+") {
            $id        = str_replace("+", "", $id);
            $clase     = "campo requerido";
            $requerido = self::imagen($imagenesGlobales["requerido_tabla"], array("title" => $textos["CAMPO_REQUERIDO_TABLA"], "class" => "indicadorRequerido"));
        }

        if (!empty($etiqueta)) {
            $elemento = "<span class=\"etiqueta\">$etiqueta:</span>\n";
        }

        $elemento .= "<input type=\"text\" id=\"$id\" name=\"$id\" size=\"$longitud\" maxlength=\"$limite\"";

        if (isset($valor)) {
            $elemento .= " value=\"$valor\"";
        }

        if (!empty($opciones)) {
            $listaOpciones = array();

            foreach ($opciones as $atributo => $valor) {
                if (strtolower($atributo) == "class") {
                    $clase .= " $valor";
                } else {
                    $listaOpciones[] = "$atributo=\"$valor\"";
                }
            }

            $opciones = implode(" ", $listaOpciones);
            $elemento .= " $opciones";
        }

        $elemento .= " class=\"$clase\">$requerido\n";
        return $elemento;
    }

    /*** Generar elemento de formulario para ingreso de texto de una sola línea ***/
    public static function campoTextoLargo($id, $etiqueta, $filas, $columnas, $contenido = "", $opciones = "") {
        global $textos, $imagenesGlobales;
        $elemento  = "";
        $clase     = "campo";
        $requerido = "";

        if (substr($id, 0, 1) == "*") {
            $id        = str_replace("*", "", $id);
            $clase     = "campo requerido";
            $requerido = self::imagen($imagenesGlobales["requerido"], array("title" => $textos["CAMPO_REQUERIDO"], "class" => "indicadorRequerido"));
        } else if(substr($id, 0, 1) == "+") {
            $id        = str_replace("+", "", $id);
            $clase     = "campo requerido";
            $requerido = self::imagen($imagenesGlobales["requerido_tabla"], array("title" => $textos["CAMPO_REQUERIDO"], "class" => "indicadorRequerido"));
        }

        if (!empty($etiqueta)) {
            $elemento = "<span class=\"etiqueta\">$etiqueta:</span>\n";
        }

        $elemento .= "<textarea id=\"$id\" name=\"$id\" rows=\"$filas\" cols=\"$columnas\"";

        if (!empty($opciones)) {
            $listaOpciones = array();

            foreach ($opciones as $atributo => $valor) {
                if (strtolower($atributo) == "class") {
                    $clase .= " $valor";
                } else {
                    $listaOpciones[] = "$atributo=\"$valor\"";
                }
            }

            $opciones = implode(" ", $listaOpciones);
            $elemento .= " $opciones";
        }

        $elemento .= " class=\"$clase\">$contenido</textarea>$requerido\n";
        return $elemento;
    }

    /*** Generar elemento de formulario para ingreso de texto de una sola línea ***/
    public static function campoTextoClave($id, $etiqueta, $longitud, $limite, $valor = "", $opciones = "") {
        global $textos, $imagenesGlobales;
        $elemento  = "";
        $clase     = "campo";
        $requerido = "";

        if (substr($id, 0, 1) == "*") {
            $id        = str_replace("*", "", $id);
            $clase     = "campo requerido";
            $requerido = self::imagen($imagenesGlobales["requerido"], array("title" => $textos["CAMPO_REQUERIDO"], "class" => "indicadorRequerido"));
        } else if(substr($id, 0, 1) == "+") {
            $id        = str_replace("+", "", $id);
            $clase     = "campo requerido";
            $requerido = self::imagen($imagenesGlobales["requerido_tabla"], array("title" => $textos["CAMPO_REQUERIDO"], "class" => "indicadorRequerido"));
        }

        if (!empty($etiqueta)) {
            $elemento = "<span class=\"etiqueta\">$etiqueta:</span>\n";
        }

        $elemento .= "<input type=\"password\" id=\"$id\" name=\"$id\" size=\"$longitud\" maxlength=\"$limite\"";

        if (isset($valor)) {
            $elemento .= " value=\"$valor\"";
        }

        if (!empty($opciones)) {
            $listaOpciones = array();

            foreach ($opciones as $atributo => $valor) {
                if (strtolower($atributo) == "class") {
                    $clase .= " $valor";
                } else {
                    $listaOpciones[] = "$atributo=\"$valor\"";
                }
            }

            $opciones = implode(" ", $listaOpciones);
            $elemento .= " $opciones";
        }

        $elemento .= " class=\"$clase\">$requerido\n";
        return $elemento;
    }

    /*** Crear código para insertar una lista de selección simple ***/
    public static function listaSeleccionSimple($id, $etiqueta, $datos, $seleccionado = "", $opciones = "") {
        global $textos, $imagenesGlobales;
        $elemento  = "";
        $clase     = "campo";
        $requerido = "";

        if (substr($id, 0, 1) == "*") {
            $id        = str_replace("*", "", $id);
            $clase     = "campo requerido";
            $requerido = self::imagen($imagenesGlobales["requerido"], array("title" => $textos["CAMPO_REQUERIDO"], "class" => "indicadorRequerido"));
        } else if(substr($id, 0, 1) == "+") {
            $id        = str_replace("+", "", $id);
            $clase     = "campo requerido";
            $requerido = self::imagen($imagenesGlobales["requerido_tabla"], array("title" => $textos["CAMPO_REQUERIDO"], "class" => "indicadorRequerido"));
        }

        if (!empty($etiqueta)) {
            $elemento = "<span class=\"etiqueta\">$etiqueta:</span>\n";
        }

        $elemento .= "<select id=\"$id\" name=\"$id\"";

        if (!empty($opciones)) {
            $listaOpciones = array();

            foreach ($opciones as $atributo => $valor) {
                if (strtolower($atributo) == "class") {
                    $clase .= " $valor";
                } else {
                    $listaOpciones[] = "$atributo=\"$valor\"";
                }
            }

            $opciones = implode(" ", $listaOpciones);
            $elemento .= " $opciones";
        }

        $elemento .= " class=\"$clase\">\n";

        if (is_array($datos) && count($datos)) {
            foreach ($datos as $opcion => $texto) {

                /*** Seleccionar opción por defecto ***/
                if (!empty($seleccionado) && ($opcion == $seleccionado)) {
                    $marca = " selected ";
                } else {
                    $marca = " ";
                }

                $elemento .= "<option".$marca."value=\"$opcion\">$texto</option>\n";
            }
        }

        $elemento .= "</select>$requerido\n";
        return $elemento;
    }

    /*** Generar elemento de formulario para ingreso de texto de una sola línea ***/
    public static function selectorArchivo($id, $etiqueta, $opciones) {
        global $textos, $imagenesGlobales;
        $elemento  = "";
        $clase     = "campo";
        $requerido = "";

        if (substr($id, 0, 1) == "*") {
            $id        = str_replace("*", "", $id);
            $clase     = "campo requerido";
            $requerido = self::imagen($imagenesGlobales["requerido"], array("title" => $textos["CAMPO_REQUERIDO"], "class" => "indicadorRequerido"));
        } else if(substr($id, 0, 1) == "+") {
            $id        = str_replace("+", "", $id);
            $clase     = "campo requerido";
            $requerido = self::imagen($imagenesGlobales["requerido_tabla"], array("title" => $textos["CAMPO_REQUERIDO"], "class" => "indicadorRequerido"));
        }

        if (!empty($etiqueta)) {
            $elemento = "<span class=\"etiqueta\">$etiqueta:</span>\n";
        }

        if (!empty($opciones)) {
            $listaOpciones = array();

            foreach ($opciones as $atributo => $valor) {
                if (strtolower($atributo) == "class") {
                    $clase .= " $valor";
                } else {
                    $listaOpciones[] = "$atributo=\"$valor\"";
                }
            }

            $opciones = implode(" ", $listaOpciones);
        }

        $elemento .= "<input type=\"file\" id=\"$id\" name=\"$id\" class=\"$clase\" size=\"20\" $opciones>$requerido\n";

        return $elemento;
    }

    /*** Generar tabla HTML a partir de una vista SQL ***/
    public static function generarTabla($columnas, $filas, $alineacion, $id = "tablaPrincipal", $ordenarColumnas = true, $variable=null, $opciones="") {
        global $componente, $textos;

        if ($ordenarColumnas) {
            $funcion = " onclick=\"ordenarResultados(this);\"";
        } else {
            $funcion = "";
        }

        $clase = "";
        if (!empty($opciones)) {
            $listaOpciones = array();

            foreach ($opciones as $atributo => $valor) {
                if (strtolower($atributo) == "class") {
                    $clase .= " $valor";
                } else {
                    $listaOpciones[] = "$atributo=\"$valor\"";
                }
            }

            $opciones = implode(" ", $listaOpciones);
        }

        $URL = HTTP::generarURL($componente->id)."&orden=";

        $tabla = "";

        if ($id == "tablaPrincipal") {
            $contenido  = "\n<!-- Inicio tabla principal -->\n<table";
            $contenido .= " id=\"$id\">\n";

            $tabla = "encabezadoTablaPrincipal";

        } else {
            $contenido  = "\n<!-- Inicio tabla interna -->\n<table";
            //$contenido .= " id=\"tablaInterna\">\n";
            $contenido .= " id=\"$id\" class=\"tablaInterna\">\n";

            $tabla = "encabezadoTablaInterna";
        }

        $contenido .= "<thead>\n <tr>\n";

        $contador   = 1;
        $excluidas  = array();

        foreach ($columnas as $columna) {
            if ($columna != "id") {

                /*** Excluir de la visualización de la tabla aquellas columnas cuyo nombre empiece por "id_" ***/
                if (preg_match("/^id_/", $columna)) {
                    $excluidas[] = $contador;

                } else {
                    $ancho_columna = count_chars($textos[$columna]);
                    if ($variable) {
                        $contenido .= "  <th align='center' width='15%' id=\"$columna\" class=\"tituloColumna $tabla\"$funcion>".$textos[$columna]."</th>\n";

                    } else {
                        $contenido .= "  <th id=\"$columna\" class=\"tituloColumna $tabla\"$funcion>".$textos[$columna]."</th>\n";
                    }
                }

                $contador++;
            }
        }


        /*** Comparar el número de columnas a mostrar con el número de columnas de la definición de alineación ***/
        if ((count($columnas) - count($excluidas)) != (count($alineacion)+1)) {
            return NULL;
        }

        $contenido .= " </tr>\n</thead>\n<tbody>\n";

        /*** Los datos para las filas de las tablas han sido pasados como arreglo ***/

        $parImpar = "even";

        if (is_array($filas)) {
            foreach ($filas as $datos) {

                /*** Obtener el id para la fila, si aplica ***/
                if ($datos[0]) {
                    $contenido .= " <tr id=\"fila_".$datos[0]."\" class=\"$parImpar $clase\" $opciones>";
                } else {
                    $contenido .= " <tr class=\"$parImpar $clase\" $opciones>";
                }

                for ($i = 1; $i < count($datos); $i++) {
                    /*** Excluir de la visualización de la tabla los datos de aquellas columnas cuyo nombre empiece por "id_" ***/
                    if (in_array($i, $excluidas)) {
                        continue;
                    }

                    if (empty($datos[$i])) {
                        $celda = "";
                    } else {
                        $celda = $datos[$i];
                    }

                    $contenido .= "  <td align=\"".self::mapearAlineacion($alineacion[$i-(count($excluidas)+1)])."\">$celda</td>\n";
                }

                $contenido .= " </tr>\n";

                if ($parImpar == "even") {
                  $parImpar = "odd";

                } else {
                  $parImpar = "even";
                }

            }

        /*** Los datos para las filas de las tablas han sido pasados como consulta de MySQL ***/
        } elseif (is_resource($filas)) {
            while ($datos = SQL::filaEnArreglo($filas)) {

                /*** Obtener el id para la fila, si aplica ***/
                if ($datos[0]) {
                    $contenido .= " <tr id=\"fila_".$datos[0]."\" class=\"$parImpar $clase\" $opciones>";
                } else {
                    $contenido .= " <tr>";
                }

                $columna = 0;

                for ($i = 1; $i < count($datos); $i++) {

                    /*** Excluir de la visualización de la tabla los datos de aquellas columnas cuyo nombre empiece por "id_" ***/
                    if (in_array($i, $excluidas)) {
                        continue;
                    }

                    if (empty($datos[$i])) {
                        $celda = "";
                    } else {
                        $celda = $datos[$i];
                    }

                    $contenido .= "  <td align=\"".self::mapearAlineacion($alineacion[$columna])."\">$celda</td>\n";
                    $columna++;
                }

                $contenido .= " </tr>\n";

                if ($parImpar == "even") {
                    $parImpar = "odd";

                } else {
                    $parImpar = "even";
                }

            }
        }

        $contenido .= "</tbody>\n</table>\n<!-- Fin tabla principal -->\n";
        return $contenido;
    }

    /*** Generar vínculo de correo electrónico ***/
    public static function enlazarCorreo($texto, $email, $opciones = "") {

        $enlace = "<a href=\"mailto:$email\"";

        if (!empty($opciones)) {
            $listaOpciones = array();

            foreach ($opciones as $atributo => $valor) {
                $listaOpciones[] = "$atributo=\"$valor\"";
            }

            $opciones = implode(" ", $listaOpciones);
            $enlace .= " $opciones";
        }

        $enlace .= ">$texto</a>";
        return $enlace;
    }

    /*** Generar vínculo de página web ***/
    public static function enlazarPagina($texto, $url, $opciones = "") {

        $enlace = "<a target='_blank' href=\"$url\"";

        if (!empty($opciones)) {
            $listaOpciones = array();

            foreach ($opciones as $atributo => $valor) {
                $listaOpciones[] = "$atributo=\"$valor\"";
            }

            $opciones = implode(" ", $listaOpciones);
            $enlace .= " $opciones";
        }

        $enlace .= ">$texto</a>";
        return $enlace;
    }

    /***  Genera lista HTML para crear el menú y otras opciones a partir de ella ***/
    public static function arbolComponentes(){
        $menuPrincipal  = "";
        $menuPrincipal .= "<ul id=\"menuGeneral\" class=\"menu\">\n";
        $menuPrincipal .= self::generarArbol();
        $menuPrincipal .= "</ul>\n";
        return $menuPrincipal;
    }

    /*** Requerida por self::arbolComponentes() ***/
    private static function generarArbol($elemento = "") {
        global $menuPrincipal, $sesion_usuario;

        $tablas       = array("componentes");
        $columnas     = array("id", "carpeta", "archivo","requiere_item","tipo_enlace");
        $ordenamiento = "orden ASC";

        if ($elemento == ""){
            $condicion = "padre IS NULL AND visible = '1'";
            $resultado = SQL::seleccionar($tablas, $columnas, $condicion, $agrupamiento = "", $ordenamiento);

            if (SQL::filasDevueltas($resultado)) {

                while($datos = SQL::filaEnObjeto($resultado)) {
                    $item           = $datos->id;
                    $carpeta        = $datos->carpeta;
                    $archivo        = $datos->archivo;
                    $elemento     = new Componente($item);

                    if (!$elemento->usuarioPermitido()) {
                        continue;
                    }

                    if (isset($carpeta) && isset($archivo)) {

                        if ($datos->tipo_enlace == 1) {
                            $texto = "<a href=\"".HTTP::generarURL($item)."\">".$elemento->nombre."</a>";

                        } elseif ($datos->tipo_enlace == 2) {
                            //$texto = "<a href=\"#\" onclick=\"ejecutarComando()\">".$elemento->nombre."</a>";
                            $texto = "<a href=\"#\">".$elemento->nombre."</a>";
                        }

                    } else {
                        $texto = $elemento->nombre;
                    }

                    $menuPrincipal .= "   <li id=\"$item\" class=\"menuPrincipal\">$texto\n";
                    $condicion      = "padre = '$item' AND visible = '1'";
                    $resultado2     = SQL::seleccionar($tablas, $columnas, $condicion, $agrupamiento = "", $ordenamiento);

                    if (SQL::filasDevueltas($resultado2)) {
                        $menuPrincipal .= "   <ul class=\"subMenu\">\n";
                        self::generarArbol($item);
                        $menuPrincipal .= "   </ul>\n";
                    }

                    $menuPrincipal .= "   </li>\n";
                }

            }
        } else {
            $condicion = "padre = '".$elemento."' AND visible = '1'";
            $resultado = SQL::seleccionar($tablas, $columnas, $condicion, $agrupamiento = "", $ordenamiento);

            if (SQL::filasDevueltas($resultado)) {

                while($datos = SQL::filaEnObjeto($resultado)){
                    $item           = $datos->id;
                    $carpeta        = $datos->carpeta;
                    $archivo        = $datos->archivo;
                    $elemento     = new Componente($item);

                    if (!$elemento->usuarioPermitido()) {
                        continue;
                    }

                    if (isset($carpeta) && isset($archivo)) {

                        if ($datos->tipo_enlace == 1) {
                            $texto = "<a href=\"".HTTP::generarURL($item)."\">".$elemento->nombre."</a>";

                        } elseif ($datos->tipo_enlace == 2) {
                            //$texto = "<a id=\"$item\" href=\"#\" onclick=\"ejecutarComando(this, 600, 500)\">".$elemento->nombre."</a>";
                            $texto = "<a id=\"$item\" href=\"#\">".$elemento->nombre."</a>";
                        }

                    } else {
                        $texto = $elemento->nombre;
                    }

                    $menuPrincipal .= "    <li id=\"$item\">$texto\n";
                    //$menuPrincipal .= "    <li>$texto\n";
                    $condicion      = "padre = '".$item."' AND visible = '1'";
                    $resultado2     = SQL::seleccionar($tablas, $columnas, $condicion, $agrupamiento = "", $ordenamiento);

                    if (SQL::filasDevueltas($resultado2)) {
                        $menuPrincipal .= "     <ul>\n";
                        self::generarArbol($item);
                        $menuPrincipal .= "     </ul>\n";
                    }

                    $menuPrincipal .= "    </li>\n";
                }
            }
        }

        return $menuPrincipal;
    }

    /***  Genera lista HTML para la gestión de privilegios de usuario ***/
    public static function arbolPerfiles($tabla, $perfil = "", $modificable = true) {
        $listaPrivilegios  = "";
        $listaPrivilegios .= "<ul class=\"arbolPerfiles\">\n";
        $listaPrivilegios .= self::generarArbolPerfiles($tabla, "", $perfil, $modificable);
        $listaPrivilegios .= "</ul>\n";
        return $listaPrivilegios;
    }

    /*** Requerida por self::arbolComponentes() ***/
    private static function generarArbolPerfiles($tabla, $privilegio, $perfil, $modificable) {
        global $listaPrivilegios;

        $tablas       = array("componentes");
        $columnas     = array("id", "global");
        $ordenamiento = "orden ASC";

        if ($privilegio == ""){
            $condicion = "padre IS NULL";
            $resultado = SQL::seleccionar($tablas, $columnas, $condicion, $agrupamiento = "", $ordenamiento);

            if (SQL::filasDevueltas($resultado)) {

                while($datos = SQL::filaEnObjeto($resultado)) {
                    $item       = $datos->id;
                    $privilegio = new Componente($item);
                    $nombre     = $privilegio->nombre;
                    $global     = $datos->global;
                    $marcado    = "";

                    if ($global) {
                        $marcado = "checked disabled";
                    } else {
                        if (!empty($perfil) && SQL::existeItem($tabla,"id_componente","$item","id_perfil = $perfil")) {
                            $marcado = "checked";
                        }
                    }

                    if (!$modificable) {
                        $marcado .= " disabled";
                    }

                    $listaPrivilegios .= "<li class=\"dato $item\">\n";
                    $listaPrivilegios .= "<input type=\"checkbox\" id=\"$item\" class=\"$item\" name=\"privilegios[$item]\" onchange = \"seleccionHijos(this)\" $marcado value=\"1\" />&nbsp;";
                    $listaPrivilegios .= "$nombre\n";
                    $condicion         = "padre = '$item' AND visible = '1'";
                    $resultado2        = SQL::seleccionar($tablas, $columnas, $condicion, $agrupamiento = "", $ordenamiento);

                    if (SQL::filasDevueltas($resultado2)) {
                        $listaPrivilegios .= "   <ul>\n";
                        self::generarArbolPerfiles($tabla,$item, $perfil, $modificable);
                        $listaPrivilegios .= "   </ul>\n";
                    }

                    $listaPrivilegios .= "   </li>\n";
                }

            }

        } else {
            $condicion = "padre = '".$privilegio."'";
            $resultado = SQL::seleccionar($tablas, $columnas, $condicion, $agrupamiento = "", $ordenamiento);

            if (SQL::filasDevueltas($resultado)) {

                while($datos = SQL::filaEnObjeto($resultado)){
                    $item       = $datos->id;
                    $privilegio = new Componente($item);
                    $nombre     = $privilegio->nombre;
                    $global     = $datos->global;
                    $marcado    = "";

                    if ($global) {
                        $marcado = "checked disabled";
                    } else {
                        if (!empty($perfil) && SQL::existeItem($tabla,"id_componente","$item","id_perfil = $perfil")) {
                            $marcado = "checked";
                        }
                    }

                    if (!$modificable) {
                        $marcado .= " disabled";
                    }

                    $listaPrivilegios .= "<li class=\"dato $item\">\n";
                    $listaPrivilegios .= "<input type=\"checkbox\" id=\"$item\" class=\"$item\" name=\"privilegios[$item]\" onchange = \"seleccionHijos(this)\" $marcado value=\"1\" />&nbsp;";
                    $listaPrivilegios .= "$nombre\n";
                    $condicion         = "padre = '".$item."'";
                    $resultado2        = SQL::seleccionar($tablas, $columnas, $condicion, $agrupamiento = "", $ordenamiento);

                    if (SQL::filasDevueltas($resultado2)) {
                        $listaPrivilegios .= "     <ul>\n";
                        self::generarArbolPerfiles($tabla, $item, $perfil, $modificable);
                        $listaPrivilegios .= "     </ul>\n";
                    }

                    $listaPrivilegios .= "    </li>\n";
                }
            }
        }

        return $listaPrivilegios;
    }

    /***  Genera lista HTML para de tallas para prendas de vestir***/
    public static function arbolTallas() {

        $listaPrivilegios = "<ul class=\"arbolTallas\">\n";

        $tablas       = array("tallas");
        $columnas     = array("codigo", "descripcion");
        $ordenamiento = "codigo ASC";
        $condicion    = "codigo > 0";
        $resultado    = SQL::seleccionar($tablas, $columnas, $condicion, $agrupamiento = "", $ordenamiento);

        if (SQL::filasDevueltas($resultado)) {

            while($datos = SQL::filaEnObjeto($resultado)) {

                $listaPrivilegios .= "  <li class=\"dato\">\n";
                $listaPrivilegios .= "   <ul>\n";
                $listaPrivilegios .= "     <input type=\"checkbox\" id=\"$datos->codigo\" class=\"tallas\" name=\"tallas[$datos->codigo]\" value=\"$datos->codigo\">&nbsp;";
                $listaPrivilegios .= "$datos->descripcion\n";
                $listaPrivilegios .= "   </ul>\n";
                $listaPrivilegios .= "  </li>\n";
            }
        }
        $listaPrivilegios .= "</ul>\n";
        return $listaPrivilegios;
    }

    /*** Generar código para la inserción del buscador en el bloque derecho ***/
    public static function insertarBuscador() {
        global $componente, $imagenesGlobales, $textos;

        $codigo  = self::campoTextoCorto("expresionBusqueda","",15,255);
        $codigo .= self::boton("botonBuscar",$textos["BUSCAR"],"realizarBusqueda();","buscar");
        $codigo .= self::boton("botonRestaurar","","deshacerBusqueda();","restaurar");

        return $codigo;
    }

    /*** Generar barra de paginación ***/
    public static function insertarPaginador($registros, $inicio, $filas) {
        global $imagenesGlobales, $componente, $paginaActual, $textos;

        $codigo = "";

        if (!$registros) {
            return $codigo;
        }

        $URL       = HTTP::generarURL($componente->id);
        $paginas   = ceil($registros / $filas);

        if ($paginaActual > $paginas) {
            $inicio = $paginas;
        } elseif ($paginaActual <= 0) {
            $inicio = 0;
        } else {
            $inicio = $filas * ($paginaActual - 1);
        }

        $paginaActual = $inicio;

        if ($paginaActual) {
            $paginaAnterior  = ($paginaActual/$filas);
            $paginaSiguiente = ($paginaActual/$filas)+2;
        } else {
            $paginaAnterior = 1;
            $paginaSiguiente = 2;
        }

        if ($paginaSiguiente > $paginas) {
            $paginaSiguiente = $paginas;
        }

        $codigo  = self::imagen($imagenesGlobales["primera"], array("id" => "pagina_1", "class" => "botonPaginador", "onclick"=>"cambiarPaginaDesdeBoton(this);return false;"));
        $codigo .= self::imagen($imagenesGlobales["anterior"], array("id" => "pagina_$paginaAnterior", "class" => "botonPaginador", "onclick"=>"cambiarPaginaDesdeBoton(this);return false;"));

        $lista   = array();

        for ($i=1; $i<=$paginas; $i++) {
            $lista[$i] = $i;
        }

        $lista   = self::listaSeleccionSimple("pagina", "", $lista, ($paginaActual/$filas)+1, array("onChange" => "cambiarPaginaDesdeLista(this.value);return false;"));
        $lista   = str_replace("%n", $lista, $textos["PAGINAS"]);
        $lista   = str_replace("%t", $paginas, $lista);
        $lista   = "<span>$lista</span>";

        $codigo .= $lista;

        $codigo .= self::imagen($imagenesGlobales["siguiente"], array("id" => "pagina_$paginaSiguiente", "class" => "botonPaginador", "onclick"=>"cambiarPaginaDesdeBoton(this);return false;"));
        $codigo .= self::imagen($imagenesGlobales["ultima"], array("id" => "pagina_$paginas", "class" => "botonPaginador", "onclick"=>"cambiarPaginaDesdeBoton(this);return false;"));

        return $codigo;
    }

    /*** Generar texto con el número de registros encontrados en una búsqueda ó en un menú ***/
    public static function imprimirRegistros($registros, $inicio, $filas) {
        global $textos;

        if (!$registros) {
            $datoRegistros = $textos["SIN_REGISTROS"];
        } elseif ($registros == 1) {
            $datoRegistros = $textos["REGISTRO"];
            $datoRegistros = str_replace("%n", "<b>".($inicio + 1)."</b>", $datoRegistros);
        } else {
            $datoRegistros = $textos["REGISTROS"];
            $datoRegistros = str_replace("%i", "<b>".($inicio + 1)."</b>", $datoRegistros);

            if ($registros < $filas) {
                $datoRegistros = str_replace("%f", "<b>".($inicio + $registros)."</b>", $datoRegistros);
            } else {
                (($registros - $inicio) >= $filas) ? $ultimo = $inicio + $filas : $ultimo = $registros;
                $datoRegistros = str_replace("%f", "<b>$ultimo</b>", $datoRegistros);
            }

            $datoRegistros = str_replace("%r", "<b>".$registros."</b>", $datoRegistros);
        }

        return $datoRegistros;
    }

    /*** Generar arreglo con datos para alimentar un lista de selección a partir de dos columnas de una tabla o vista ***/
    public static function generarDatosLista($tabla, $valor, $texto, $condicion = "") {
        $datos    = array();
        $consulta = SQL::seleccionar(array($tabla), array($valor,$texto), $condicion, "", $texto);

        while ($fila = SQL::filaEnArreglo($consulta)) {
            $datos[$fila[0]] = $fila[1];
        }

        return $datos;
    }

    /*** Elegir el texto adecuado en inglés para especificar la alineación de un elemento ***/
    private static function mapearAlineacion($caracter) {
        switch (strtoupper($caracter)) {
            case "I" :
                $alineacion = "left";
                break;
            case "D" :
                $alineacion = "right";
                break;
            case "C" :
                $alineacion = "center";
                break;
            case "J" :
                $alineacion = "justify";
                break;
            default :
                $alineacion = "left";
                break;
        }

        return $alineacion;
    }

    /***  Genera lista HTML para crear la estructura de grupos de artículos ***/
    public static function arbolGrupos($id, $nodo, $padre, $marcador = "", $principal = false) {
        $arbolGrupos     = "";
        $arbolGrupos    .= "<div id=\"bloqueArbol\"><ul id=\"$id\" class=\"arbol\">\n";
        $arbolGrupos    .= self::generarArbolGrupos($nodo, $padre, $marcador, "", $principal);
        $arbolGrupos    .= "</ul>\n</div>\n";
        return $arbolGrupos;
    }

    /*** Requerida por self::arbolGrupos() ***/
    private static function generarArbolGrupos($nodo, $padre, $marcador, $componente = "", $principal = false) {
        global $arbolGrupos, $textos;

        static $rutaArbolGrupos = array();

        if (empty($rutaArbolGrupos)) {
            $rutaArbolGrupos = self::rutaGrupos($nodo);
        }

        $tablas       = array("estructura_grupos");
        $columnas     = array("codigo", "codigo", "descripcion","orden");
        $ordenamiento = "codigo ASC";

        if ($componente == "") {
            $condicion = "codigo_padre IS NULL AND codigo != 0";
            $resultado = SQL::seleccionar($tablas, $columnas, $condicion, $agrupamiento = "", $ordenamiento);

            if (SQL::filasDevueltas($resultado)) {

                while ($datos = SQL::filaEnObjeto($resultado)) {
                    $item        = $datos->codigo;
                    $codigo      = $datos->codigo;
                    $descripcion = $datos->descripcion.$textos["ORDEN_CONSULTA"].$datos->orden;
                    $selector    = "<div id=\"marcaLista\"></div>";

                    if (!empty($nodo) && ($nodo == $item) && !empty($marcador) && $principal) {
                        continue;
                    }

                    if (in_array(abs($item), $rutaArbolGrupos)) {
                        $clase = "dato open";
                    } else {
                        $clase = "dato";
                    }

                    if (!empty($nodo) && ($nodo == $item)) {
                        $estilo = "class=\"$clase\" style=\"font-weight: bold;\"";
                    } else {
                        $estilo = "class=\"$clase\"";
                    }

                    if (!empty($padre) && $padre == $item) {
                        $activo = "checked";
                    } else {
                        $activo = "";
                    }

                    if (!empty($marcador)) {
                        $selector = "<input type=\"radio\" class=\"campo padre\" $activo name=\"$marcador\" value=\"$item\">";
                    }

                    $arbolGrupos .= "   <li $estilo id=\"grupo_$item\"><span class=\"itemLista\">$selector [$codigo] $descripcion</span>\n";
                    $condicion    = "codigo_padre = '$item'";
                    $resultado2   = SQL::seleccionar($tablas, $columnas, $condicion, $agrupamiento = "", $ordenamiento);

                    if (SQL::filasDevueltas($resultado2)) {
                        $arbolGrupos .= "   <ul>\n";
                        self::generarArbolGrupos($nodo, $padre, $marcador, $item, $principal);
                        $arbolGrupos .= "   </ul>\n";
                    }

                    $arbolGrupos .= "   </li>\n";
                }

            }

        } else {
            $condicion = "codigo_padre = '$componente' AND codigo != 0";
            $resultado = SQL::seleccionar($tablas, $columnas, $condicion, $agrupamiento = "", $ordenamiento);

            if (SQL::filasDevueltas($resultado)) {

                while ($datos = SQL::filaEnObjeto($resultado)) {
                    $item        = $datos->codigo;
                    $codigo      = $datos->codigo;
                    $descripcion = $datos->descripcion.$textos["ORDEN_CONSULTA"].$datos->orden;

                    if (!empty($nodo) && ($nodo == $item) && !empty($marcador) && $principal) {
                        continue;
                    }

                    if (in_array(abs($item), $rutaArbolGrupos)) {
                        $clase = "dato open";
                    } else {
                        $clase = "dato";
                    }

                    if (!empty($nodo) && ($nodo == $item)) {
                        $estilo = "class=\"$clase\" style=\"font-weight: bold;\"";
                    } else {
                        $estilo = "class=\"$clase\"";
                    }

                    if (!empty($padre) && $padre == $item) {
                        $activo = "checked";
                    } else {
                        $activo = "";
                    }

                    if (!empty($marcador)) {
                        $selector = "<input type=\"radio\" class=\"campo padre\" $activo name=\"$marcador\" value=\"$item\">";
                    } else {
                        $selector = $marcador;
                    }

                    $arbolGrupos .= "   <li $estilo id=\"grupo_$item\"><span class=\"itemLista\">$selector [$codigo] $descripcion</span>\n";
                    $condicion    = "codigo_padre = '$item'";
                    $resultado2   = SQL::seleccionar($tablas, $columnas, $condicion, $agrupamiento = "", $ordenamiento);

                    if (SQL::filasDevueltas($resultado2)) {
                        $arbolGrupos .= "     <ul>\n";
                        self::generarArbolGrupos($nodo, $padre, $marcador, $item, $principal);
                        $arbolGrupos .= "     </ul>\n";
                    }

                    $arbolGrupos .= "    </li>\n";
                }
            }
        }

        return $arbolGrupos;
    }

    /*** Obtener un arreglo con la ruta complete de grupos de hasta llegar a un nodo específico, requerida por generarArbolGrupos() ***/
    private static function rutaGrupos($nodo) {
        static $rutaGrupos = array();

        $consulta = SQL::seleccionar(array("estructura_grupos"),array("codigo_padre"), "codigo = '$nodo'");
        $padre    = "";

        if (SQL::filasDevueltas($consulta)) {
            $datos = SQL::filaEnObjeto($consulta);
            $padre = $datos->codigo_padre;
        }

        if ($padre) {
            $rutaGrupos[] = abs($padre);
            self::rutaGrupos($padre);
        }

        return $rutaGrupos;
    }

    /***  Genera lista HTML para crear la estructura de grupos de artículos ***/
    public static function arbolContable($id, $nodo, $padre, $marcador = "", $principal = false) {
        $arbolGrupos     = "";
        $arbolGrupos    .= "<div id=\"bloqueArbol\"><ul id=\"$id\" class=\"arbol\">\n";
        $arbolGrupos    .= self::generarArbolContable($nodo, $padre, $marcador, "", $principal);
        $arbolGrupos    .= "</ul>\n</div>\n";
        return $arbolGrupos;
    }

    /*** Requerida por self::arbolGrupos() ***/
    private static function generarArbolContable($nodo, $padre, $marcador, $componente = "", $principal = false) {
        global $arbolGrupos;

        static $rutaArbolGrupos = array();

        if (empty($rutaArbolGrupos)) {
            $rutaArbolGrupos = self::rutaContable($nodo);
        }

        $tablas       = array("plan_contable");
        $columnas     = array("codigo_contable", "descripcion", "clase_cuenta");
        $ordenamiento = "codigo_contable ASC";

        if ($componente == "") {
            $condicion = "(codigo_contable_padre ='' OR codigo_contable_padre IS NULL) AND codigo_contable != '' AND clase_cuenta!='1'";
            $resultado = SQL::seleccionar($tablas, $columnas, $condicion, $agrupamiento = "", $ordenamiento);

            if (SQL::filasDevueltas($resultado)) {

                while ($datos = SQL::filaEnObjeto($resultado)) {
                    $item         = $datos->codigo_contable;
                    $codigo       = $datos->codigo_contable;
                    $clase_cuenta = $datos->clase_cuenta;
                    $descripcion  = $datos->descripcion;
                    $selector     = "";

                    if (!empty($nodo) && ($nodo == $item) && !empty($marcador) && $principal) {
                        continue;
                    }

                    if (in_array(abs($item), $rutaArbolGrupos)) {
                        $clase = "dato open";
                    } else {
                        $clase = "dato";
                    }

                    if (!empty($nodo) && ($nodo == $item)) {
                        $estilo = "class=\"$clase\" style=\"font-weight: bold;\"";
                    } else {
                        $estilo = "class=\"$clase\"";
                    }

                    if (!empty($padre) && $padre == $item) {
                        $activo = "checked";
                    } else {
                        $activo = "";
                    }

                    if (!empty($marcador)) {
                        if ($clase_cuenta == '2'){
                            $selector = "<input type=\"radio\" $activo name=\"$marcador\" value=\"$item\">";
                        } else {
                            $selector = "&nbsp;&nbsp";
                        }
                    }

                    $arbolGrupos .= "   <li $estilo id=\"grupo_$item\">$selector [$codigo] $descripcion\n";
                    $condicion    = "codigo_contable_padre = '$item'";
                    $resultado2   = SQL::seleccionar($tablas, $columnas, $condicion, $agrupamiento = "", $ordenamiento);

                    if (SQL::filasDevueltas($resultado2)) {
                        $arbolGrupos .= "   <ul>\n";
                        self::generarArbolContable($nodo, $padre, $marcador, $item, $principal);
                        $arbolGrupos .= "   </ul>\n";
                    }

                    $arbolGrupos .= "   </li>\n";
                }

            }

        } else {
            $condicion = "codigo_contable_padre = '$componente' AND codigo_contable != ''";
            $resultado = SQL::seleccionar($tablas, $columnas, $condicion, $agrupamiento = "", $ordenamiento);

            if (SQL::filasDevueltas($resultado)) {

                while ($datos = SQL::filaEnObjeto($resultado)) {
                    $item         = $datos->codigo_contable;
                    $codigo       = $datos->codigo_contable;
                    $clase_cuenta = $datos->clase_cuenta;
                    $descripcion  = $datos->descripcion;

                    if (!empty($nodo) && ($nodo == $item) && !empty($marcador) && $principal) {
                        continue;
                    }

                    if (in_array(abs($item), $rutaArbolGrupos)) {
                        $clase = "dato open";
                    } else {
                        $clase = "dato";
                    }

                    if (!empty($nodo) && ($nodo == $item)) {
                        $estilo = "class=\"$clase\" style=\"font-weight: bold;\"";
                    } else {
                        $estilo = "class=\"$clase\"";
                    }

                    if (!empty($padre) && $padre == $item) {
                        $activo = "checked";
                    } else {
                        $activo = "";
                    }

                    if (!empty($marcador)) {
                        if ($clase_cuenta == '2'){
                            $selector = "<input type=\"radio\" $activo name=\"$marcador\" value=\"$item\">";
                        } else {
                            $selector = "&nbsp;&nbsp;";
                        }
                    } else {
                        $selector = $marcador;
                    }

                    $arbolGrupos .= "   <li $estilo id=\"grupo_$item\">$selector [$codigo] $descripcion\n";
                    $condicion    = "codigo_contable_padre = '$item'";
                    $resultado2   = SQL::seleccionar($tablas, $columnas, $condicion, $agrupamiento = "", $ordenamiento);

                    if (SQL::filasDevueltas($resultado2)) {
                        $arbolGrupos .= "     <ul>\n";
                        self::generarArbolContable($nodo, $padre, $marcador, $item, $principal);
                        $arbolGrupos .= "     </ul>\n";
                    }

                    $arbolGrupos .= "    </li>\n";
                }
            }
        }

        return $arbolGrupos;
    }

    /*** Obtener un arreglo con la ruta completa de grupos de hasta llegar a un nodo específico, requerida por generarArbolCuentas() ***/
    private static function rutaContable($nodo) {
        static $rutaCuentas = array();

        $consulta = SQL::seleccionar(array("plan_contable"),array("codigo_contable_padre"), "codigo_contable = '$nodo' AND codigo_contable != ''");

        if (SQL::filasDevueltas($consulta)) {
            $datos    = SQL::filaEnObjeto($consulta);
            $padre    = $datos->codigo_contable_padre;
            $rutaCuentas[] = abs($padre);
            self::rutaContable($padre);
        }

        return $rutaCuentas;
    }

    /*** Generar tabla HTML a partir de un arreglo de datos ***/
//     public static function arregloEnTabla($columnas, $filas, $alineacion, $ordenarColumnas = false) {
    public static function arregloEnTabla($datos) {
        global $componente, $textos;

        if ($ordenarColumnas) {
            $funcion = " onclick=\"ordenarResultados(this);\"";
        } else {
            $funcion = "";
        }


        $URL = HTTP::generarURL($componente->id)."&orden=";

        $contenido  = "\n<!-- Inicio tabla de datos -->\n<table id=\"tablaDatos\">\n";
        $contenido .= "<thead>\n <tr>\n";

        foreach ($columnas as $columna) {
            if ($columna != "id") {
                $contenido .= "  <th id=\"$columna\" class=\"tituloColumna\"$funcion>".$textos[$columna]."</th>\n";
            }
        }

        $contenido .= " </tr>\n</thead>\n<tbody>\n";

        while ($datos = SQL::filaEnArreglo($filas)) {
            $contenido .= " <tr id=\"fila_".$datos[0]."\">";

            for ($i = 1; $i < count($datos); $i++) {
                if (empty($datos[$i])) {
                    $celda = "";
                } else {
                    $celda = $datos[$i];
                }

                $contenido .= "  <td align=\"".self::mapearAlineacion($alineacion[$i-1])."\">$celda</td>\n";
            }

            $contenido .= " </tr>\n";
        }

        $contenido .= "</tbody>\n</table>\n<!-- Fin tabla de datos -->\n";
        return $contenido;
    }


}
?>

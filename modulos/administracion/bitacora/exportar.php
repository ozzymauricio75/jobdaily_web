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
if(!empty($url_recargarOrdenamiento)){

    $selector= array(
        "0" => $textos["NOAPLICA"],
        "1" => $textos["MARCA"],
        "2" => $textos["GRUPO"]
    );
    $ordenamiento= array(
        "0" => $textos["NOAPLICA"],
        "1" => $textos["ASCENDENTE"],
        "2" => $textos["DESCENDENTE"]
    );

    $texto_ids = "";
    $texto_descripcion = "";

    if($url_valor==1){
        if($url_id_item1){
            for($i=0; $i<count($selector); $i++){
                if($url_id_item1 != $i){
                    $texto_ids.= $i."-";
                    $texto_descripcion.= $selector[$i]."-";
                }
            }
        }
    }

    $textos_ids = trim($texto_ids, "-");
    $textos_descripcion = trim($texto_descripcion, "-");

    $elementos[0] = $textos_ids;
    $elementos[1] = $textos_descripcion;

    HTTP::enviarJSON($elementos);
    exit;
}


/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {
    $error  = "";
    $titulo = $componente->nombre;

    $selector= array(
        "0" => $textos["NOAPLICA"],
        "1" => $textos["MARCA"],
        "2" => $textos["GRUPO"]
    );

    $ordenamiento= array(
        "0" => $textos["NOAPLICA"],
        "1" => $textos["ASCENDENTE"],
        "2" => $textos["DESCENDENTE"]
    );



    /*** Obtener lista de sucursales para selección ***/
    $tablas     = array(
        "a" => "perfiles_usuario",
        "b" => "componentes_usuario",
        "c" => "sucursales"
    );
    $columnas = array(
        "id"     => "a.id_sucursal",
        "nombre" => "c.nombre_corto"
    );
    $condicion = "c.id = a.id_sucursal AND a.id = b.id_perfil AND (c.tipo_empresa = '1' OR c.tipo_empresa = '2' OR c.tipo_empresa = '3')
                  AND a.id_usuario = '$sesion_id_usuario' AND b.id_componente = '".$componente->id."' GROUP BY a.id_sucursal";

    $ordena_consulta = "orden ASC";

    $consulta = SQL::seleccionar($tablas, $columnas, $condicion, "", $ordena_consulta);
    if (SQL::filasDevueltas($consulta)) {
        $sucursales = array();

        $sucursales[] = array(HTML::boton("botonSeleccionarTodos", $textos["SELECCIONAR_TODOS"], "seleccionar_todas_sucursales();", "", array()));
        while ($datos = SQL::filaEnObjeto($consulta)) {
            $idSucursal     = $datos->id;
            $nombreSucursal = $datos->nombre;
            $sucursales[]   = array(HTML::marcaChequeo("sucursales[]", $nombreSucursal, $idSucursal, false, array("class"=>"sucursales_electrodomesticos", "id"=>"sucursales_".$idSucursal)));
        }
    }


    /*** Obtener lista de grupos para selección ***/
    $grupos = array();
    $grupos[] = array(HTML::boton("botonSeleccionarTodos", $textos["SELECCIONAR_TODOS"], "seleccionar_todos_grupos();", "", array()));
    $consulta = SQL::seleccionar(array("grupos"), array("id", "descripcion"), "", "", "orden ASC");
    if (SQL::filasDevueltas($consulta)) {

        while ($datos = SQL::filaEnObjeto($consulta)) {
            $grupos[] = array(HTML::marcaChequeo("grupos[]", $datos->descripcion, $datos->id, false, array("class"=>"grupos_electrodomesticos", "id"=>"grupos_".$datos->id)));
        }
    }

    /*** Obtener lista de marcas para selección ***/
    $consulta = SQL::seleccionar(array("marcas"), array("id", "descripcion"), "", "", "descripcion ASC");

    if (SQL::filasDevueltas($consulta)) {
        $marcas = array();

        $marcas[] = array(HTML::boton("botonSeleccionarTodos", $textos["SELECCIONAR_TODOS"], "seleccionar_todos_marcas();", "", array()));
        while ($datos = SQL::filaEnObjeto($consulta)) {
            $marcas[] = array(HTML::marcaChequeo("marcas[]", $datos->descripcion, $datos->id, false, array("class"=>"marcas_electrodomesticos", "id"=>"marcas_".$datos->id)));
        }
    }

    /*** Definición de pestañas ***/
    $formularios["PESTANA_SUCURSALES"] = $sucursales;
    $formularios["PESTANA_GRUPOS"]     = $grupos;
    $formularios["PESTANA_MARCAS"]     = $marcas;

    $formularios["PESTANA_SALTO_PAGINA"]    = array(
        array(
            HTML::mostrarDato("ordenamiento",$textos["ORDENAMIENTO"],"")
        ),
        array(
            HTML::listaSeleccionSimple("item1", $textos["ITEM1"], $selector, "", array("onchange"=>"funcion_ordenamiento_1()")),
            HTML::listaSeleccionSimple("orden1", $textos["ORDEN1"], $ordenamiento, "", array("class" => "oculto"))
        ),
        array(
            HTML::listaSeleccionSimple("item2", $textos["ITEM1"], $selector, "", array("class" => "oculto", "onchange"=>"funcion_ordenamiento_2()")),
            HTML::listaSeleccionSimple("orden2", $textos["ORDEN1"], $ordenamiento, "", array("class" => "oculto"))
        ),
        array(
            HTML::marcaChequeo("salto_sucursal", $textos["SALTO_SUCURSAL"], "1", true, array("title" => $textos["AYUDA_SALTO_SUCURSAL"]))
        ),
        array(
            HTML::marcaChequeo("salto_marca", $textos["SALTO_MARCA"], "1", true, array("title" => $textos["AYUDA_SALTO_MARCA"]))
        ),
        array(
            HTML::marcaChequeo("salto_grupo", $textos["SALTO_GRUPO"], "1", false, array("title" => $textos["AYUDA_SALTO_GRUPO"]))
        ),
        /*array(
            HTML::marcaChequeo("imprime_subtitulos", $textos["IMPRIME_SUBTITULOS"], "1", true, array("title" => $textos["AYUDA_IMPRIME_SUBTITULOS"]))
        ),*/
        array(
            HTML::marcaChequeo("imprime_contado", $textos["IMPRIME_CONTADO"], "1", false, array("title" => $textos["AYUDA_IMPRIME_CONTADO"]))
        ),
        array(
            HTML::marcaChequeo("imprime_fecha", $textos["IMPRIME_FECHA"], "1", false, array("title" => $textos["AYUDA_IMPRIME_FECHA"]))
        )
    );

    /*** Definición de botones ***/
    $cerrar_ventana = false;
    $botones = array(
        HTML::boton("botonAceptar", $textos["ACEPTAR"], "imprimirItem('$cerrar_ventana');", "aceptar", array("class" => "pdf"))
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

    $error = false;
    $mensaje = "ITEM_ADICIONADO";
    $ruta_archivo = "";

    $sin_datos_detallistas = true;
    $sin_datos_mayoristas = true;
    $datos_completos = false;

    if (empty($forma_sucursales) || !isset($forma_sucursales)){
        $error   = true;
        $mensaje = $textos["ERROR_SUCURSALES"];
    } else if (empty($forma_grupos) || !isset($forma_grupos)){
        $error   = true;
        $mensaje = $textos["ERROR_GRUPOS"];
    } else if (empty($forma_marcas) || !isset($forma_marcas)){
        $error   = true;
        $mensaje = $textos["ERROR_MARCAS"];
    } else {

        $datos_completos = true;
        $salto_pagina_sucursal = false;
        if(isset($forma_salto_sucursal))
            $salto_pagina_sucursal = true;

        $salto_pagina_marca = false;
        if(isset($forma_salto_marca))
            $salto_pagina_marca = true;

        $salto_pagina_grupo = false;
        if(isset($forma_salto_grupo))
            $salto_pagina_grupo = true;

        $imprimir_subtitulos = false;
        if(isset($forma_imprime_subtitulos))
            $imprimir_subtitulos = true;

        $imprimir_contado_lista = false;
        if(isset($forma_imprime_contado))
            $imprimir_contado_lista = true;

        $imprimir_fecha_lista = false;
        if(isset($forma_imprime_fecha))
            $imprimir_fecha_lista = true;

        /*** SELECCION INICIAL POR LA QUE SE QUIERE GENERAR EL ARCHIVO Y EL ORDEN DE LOS CAMPOS ***/
        $seleccion1 = $forma_item1;
        $seleccion2 = 0;
        if(isset($forma_item2))
            $seleccion2 = $forma_item2;

        if ($seleccion1==0 || $seleccion1==2){
            $primer_campo_seleccion  = "orden_grupo";
            $segundo_campo_seleccion = "orden_estructura_grupo";
            $tercer_campo_seleccion = "orden_marca";

            $ordenamiento1 = 0;
            $ordenamiento2 = 0;
            $ordenamiento3 = 0;
            if(isset($forma_orden1)){
                $ordenamiento1 = $forma_orden1;
                $ordenamiento2 = $forma_orden1;
            }
            if(isset($forma_orden2)){
                $ordenamiento3 = $forma_orden2;
            }

        } else {
            /*** GENERAR UN ARCHIVO POR MARCAS ***/
            $primer_campo_seleccion  = "orden_marca";
            //$segundo_campo_seleccion = "orden_estructura_grupo";
            $segundo_campo_seleccion = "orden_grupo";
            $tercer_campo_seleccion  = "orden_estructura_grupo";

            $ordenamiento1 = 0;
            $ordenamiento2 = 0;
            $ordenamiento3 = 0;
            if(isset($forma_orden1)){
                $ordenamiento1 = $forma_orden1;
            }
            if(isset($forma_orden2)){
                $ordenamiento2 = $forma_orden2;
                $ordenamiento3 = $forma_orden2;
            }

        }

        if($ordenamiento1==0 || $ordenamiento1==1)
            $primer_campo_ordenamiento = "ASC";
        else
            $primer_campo_ordenamiento = "DESC";

        if($ordenamiento2==0 || $ordenamiento2==1)
            $segundo_campo_ordenamiento = "ASC";
        else
            $segundo_campo_ordenamiento = "DESC";

        if($ordenamiento3==0 || $ordenamiento3==1)
            $tercer_campo_ordenamiento = "ASC";
        else
            $tercer_campo_ordenamiento = "DESC";

        $vector_grupos = array();
        foreach ($forma_grupos as $idGrupo) {
            $vector_grupos[] = $idGrupo;
        }
        $condicion_grupos = " AND id_grupo IN (".implode(",", $vector_grupos).")";

        $vector_marcas = array();
        foreach ($forma_marcas as $idmarca) {
            $vector_marcas[] = $idmarca;
        }
        $condicion_marca = " AND id_marca IN (".implode(",", $vector_marcas).")";

        $nombre         = "";
        $nombreArchivo  = "";
        do {
            $cadena         = Cadena::generarCadenaAleatoria(8);
            $nombre         = $cadena.".pdf";
            $nombreArchivo  = $rutasGlobales["archivos"]."/".$nombre;
        } while (is_file($nombreArchivo));

        $fechaReporte   = date("Y-m-d");
        $archivo        = new PDF("P","mm","Letter");

        $archivo->textoCabecera = $textos["LISTAS_PRECIOS"].". ".$textos["FECHA"].": $fechaReporte";
        $archivo->textoPiePagina = $textos["TEXTOS_INCENTIVOS"];
        if(!isset($forma_salto_sucursal))
            $archivo->AddPage();

        $sin_datos_detallistas = true;
        $sin_datos_mayoristas  = true;
        $texto = array();

        foreach ($forma_sucursales as $idsucursal) {

            $anchoColumnas      = array();
            $alineacionColumnas = array();
            $tituloColumnas     = array();
            $formatoColumnas    = array();
            $margenes           = array();

            $nombre_sucursal = SQL::obtenerValor("sucursales","nombre","id = '$idsucursal'");
            $tipo_sucursal   = SQL::obtenerValor("sucursales","tipo_empresa","id = '$idsucursal'");
            if ($tipo_sucursal =='1' || $tipo_sucursal =='3'){
                $imprimir_encabezado = true;
                $imprimir_encabezado_marca = false;
                $primer_grupo_mayorista = false;
                $primer_marca_mayorista = false;
                $nombre_grupo_anterior_mayorista = "";
                $descripcion_marca_anterior_mayorista = "";
                $id_grupo_anterior = "";

                $condicion      = "id_sucursal='$idsucursal' AND id_combo=0 $condicion_grupos $condicion_marca";
                $agrupamiento   = "";
                //$ordenamiento   = "$primer_campo_seleccion $primer_campo_ordenamiento, $segundo_campo_seleccion $segundo_campo_ordenamiento, fecha_inicial DESC, precio_compra ASC";
                $ordenamiento   = "$primer_campo_seleccion $primer_campo_ordenamiento, $segundo_campo_seleccion $segundo_campo_ordenamiento, $tercer_campo_seleccion $tercer_campo_ordenamiento,fecha_inicial DESC, precio_compra ASC";
                //$ordenamiento   = "$primer_campo_seleccion $primer_campo_ordenamiento, $segundo_campo_seleccion $segundo_campo_ordenamiento, orden_estructura_grupo ASC, fecha_inicial DESC, precio_compra ASC";
                //$ordenamiento   = "$primer_campo_seleccion $primer_campo_ordenamiento";
                $consultaInformacion = SQL::seleccionar(array("lista_mayorista"), array("*"), $condicion, $agrupamiento, $ordenamiento);

                $contador = 0;
                if (SQL::filasDevueltas($consultaInformacion)) {

                    $sin_datos_mayoristas = false;
                    if (!$primer_grupo_mayorista){
                        if ($salto_pagina_sucursal){
                            $archivo->AddPage();
                        }

                        $archivo->SetFont('Arial','B',8);
                        $archivo->Cell(200, 8, $textos["CLIENTE_MAYORISTA"], 0, 0, "I", false);
                        $archivo->Ln(8);
                        $archivo->SetFont('Arial','B',8);
                        $archivo->Cell(200, 8, $textos["TITULO_ALMACEN"].": ".$nombre_sucursal, 0, 0, "I", false);
                        $archivo->Ln(8);
                    }

                    $id_articulo_anterior = "";
                    $id_sucursal_anterior = "";

                    while ($datos = SQL::filaEnObjeto($consultaInformacion)) {

                        if ($datos->id_articulo != $id_articulo_anterior){
                            $separa_articulo = true;
                        } else {
                            $separa_articulo = false;
                        }

                        $id_articulo_anterior = $datos->id_articulo;

                        if ($separa_articulo){

                            $nombre_grupo   = SQL::obtenerValor("grupos","descripcion","id = '$datos->id_grupo'");

                            if($imprimir_subtitulos){
                                if ($nombre_grupo != $nombre_grupo_anterior_mayorista){
                                    $archivo->SetFont('Arial','B',8);
                                    $archivo->Cell(200, 8, $nombre_grupo, 0, 0, "I", false);
                                    $archivo->Ln(8);
                                    $imprimir_encabezado = true;
                                    $imprimir_encabezado_marca = false;
                                    $primer_marca_mayorista = false;
                                    $primer_grupo_mayorista = false;
                                }
                            }
                            $nombre_grupo_anterior_mayorista = $nombre_grupo;

                            if($imprimir_encabezado){
                                $anchoColumnas  = array("18","18","60");
                                $tituloColumnas = array($textos["CODIGO"],$textos["MARCA"],$textos["DESCRIPCION"]);
                                $consulta = SQL::seleccionar(array("margenes_precios_mayoristas"), array("nombre"), "", "", "columna ASC");
                                if (SQL::filasDevueltas($consulta)) {
                                    while ($datos_margenes = SQL::filaEnObjeto($consulta)) {
                                        $anchoColumnas[]  = "15";
                                        $tituloColumnas[] = $datos_margenes->nombre;
                                    }
                                }

                                $anchoColumnas[]  = "25";
                                $tituloColumnas[] = $textos["OBSERVACIONES"];

                                $anchoColumnas[]  = "5";
                                $tituloColumnas[] = $textos["TEXTO_INCENTIVO"];

                                if ($imprimir_fecha_lista){
                                    $anchoColumnas[]  = "15";
                                    $tituloColumnas[] = $textos["FECHA"];
                                }

                                $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
                                $archivo->Ln(4);

                                $imprimir_encabezado = false;
                            }

                            $numero_lineas = count($texto);
                            $salto_pagina = false;
                            if ($numero_lineas > 0){
                                $salto_pagina = $archivo->NewPage($texto);
                            }

                            if(
                                ($salto_pagina_grupo && $primer_grupo_mayorista && ($datos->id_grupo != $id_grupo_anterior)) ||
                                ($salto_pagina_marca && $primer_marca_mayorista && ($datos->marca != $descripcion_marca_anterior_mayorista)) ||
                                ($salto_pagina)
                              ){
                                $archivo->AddPage();
                                $archivo->SetFont('Arial','B',8);
                                $archivo->Cell(200, 8, $textos["CLIENTE_MAYORISTA"], 0, 0, "I", false);
                                $archivo->Ln(8);
                                $archivo->SetFont('Arial','B',8);
                                $archivo->Cell(200, 8, $textos["TITULO_ALMACEN"].": ".$nombre_sucursal, 0, 0, "I", false);
                                $archivo->Ln(8);
                                $imprimir_encabezado_marca = true;
                            }

                            if($imprimir_subtitulos){
                                if ($salto_pagina_marca && $primer_marca_mayorista && ($datos->marca != $descripcion_marca_anterior_mayorista)){
                                    $archivo->SetFont('Arial','B',8);
                                    $archivo->Cell(200, 8, $nombre_grupo, 0, 0, "I", false);
                                    $archivo->Ln(8);
                                    $imprimir_encabezado_marca = true;
                                }
                            }
                            $primer_marca_mayorista = true;
                            $primer_grupo_mayorista = true;
                            $descripcion_marca_anterior_mayorista = $datos->marca;
                            $id_grupo_anterior = $datos->id_grupo;
                            //$id_grupo_anterior = $datos->estructura_grupo;

                            if($imprimir_encabezado_marca){
                                $anchoColumnas  = array("18","18","60");
                                $tituloColumnas = array($textos["CODIGO"],$textos["MARCA"],$textos["DESCRIPCION"]);
                                $consulta_margenes_mayoristas = SQL::seleccionar(array("margenes_precios_mayoristas"), array("nombre"), "", "", "columna ASC");
                                if (SQL::filasDevueltas($consulta_margenes_mayoristas)) {
                                    while ($datos_margenes_mayoristas = SQL::filaEnObjeto($consulta_margenes_mayoristas)) {
                                        $anchoColumnas[]  = "15";
                                        $tituloColumnas[] = $datos_margenes_mayoristas->nombre;
                                    }
                                }

                                $anchoColumnas[]  = "25";
                                $tituloColumnas[] = $textos["OBSERVACIONES"];

                                $anchoColumnas[]  = "5";
                                $tituloColumnas[] = $textos["TEXTO_INCENTIVO"];

                                if ($imprimir_fecha_lista){
                                    $anchoColumnas[]  = "15";
                                    $tituloColumnas[] = $textos["FECHA"];
                                }

                                $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
                                $archivo->Ln(4);

                                $imprimir_encabezado_marca = false;
                            }

                            $ancho = array();
                            $texto = array();
                            $aligns= array();

                            $tamano_descripcion = strlen($datos->descripcion.$datos->descripcion);
                            $tamano_observacion = strlen($datos->observaciones);
                            if ($tamano_descripcion > $tamano_observacion){
                                $tamano_celda = $tamano_descripcion;
                            } else {
                                $tamano_celda = $tamano_observacion;
                            }
                            $filas = (int)$tamano_celda / 90;
                            $altura_celda = 5;
                            if ($filas>1){
                                $filas = ceil($filas);
                                //$filas++;
                                $altura_celda = $altura_celda * $filas;
                            }


                            $archivo->SetFont('Arial','B',8);
                            $archivo->Cell(18, $altura_celda, $datos->codigo_interno, 1, 0, "I", false);
                            /*$ancho[] = 18;
                            $texto[] = "";
                            $aligns[] = "L";*/

                            /*$ancho[] = 18;
                            $texto[] = $datos->marca;
                            $aligns[] = "L";*/
                            $archivo->SetFont('Arial','',6);
                            $archivo->Cell(18, $altura_celda, $datos->marca, 1, 0, "I", false);

                            /*$ancho[] = 60;
                            $texto[] = $datos->descripcion;
                            $aligns[] = "L";*/
                            $archivo->SetFont('Arial','',4);
                            $archivo->Cell(60, $altura_celda, $datos->descripcion.$datos->descripcion, 1, 0, "I", false);

                            $consulta1 = SQL::seleccionar(array("margenes_precios_mayoristas"), array("id", "nombre", "porcentaje"), "", "", "columna ASC");
                            if (SQL::filasDevueltas($consulta1)) {
                                $margenes     = array();
                                $primerMargen = false;
                                while ($datosMargen = SQL::filaEnObjeto($consulta1)) {
                                    $idMargen         = $datosMargen->id;
                                    $valorVentaMayorista    = SQL::obtenerValor("precios_mayoristas","precio_venta","id_sucursal='$idsucursal' AND id_articulo='$datos->id_articulo' AND id_margen='$idMargen' AND fecha_inicial='$datos->fecha_inicial' AND id_combo='0' GROUP BY id_articulo,id_sucursal,id_margen");

                                    /*$ancho[] = 15;
                                    $texto[] = "$".number_format($valorVentaMayorista);
                                    $aligns[] = "R";*/
                                    $archivo->SetFont('Arial','',8);
                                    $archivo->Cell(15, $altura_celda, "$".number_format($valorVentaMayorista), 1, 0, "D", false);
                                }
                            }

                            $ancho[] = 25;
                            $texto[] = $datos->observaciones;
                            $aligns[] = "L";

                            $incentivo = "";
                            if($datos->incentivo_mayorista>0)
                                $incentivo = $textos["INCENTIVOS"];

                            $ancho[] = 5;
                            $texto[] = $incentivo;
                            $aligns[] = "C";

                            if ($imprimir_fecha_lista){
                                $ancho[] = 15;
                                $texto[] = $datos->fecha_inicial;
                                $aligns[] = "R";
                            }

                            $archivo->SetFont('Arial','',6);
                            $archivo->SetAligns($aligns);
                            $archivo->SetWidths($ancho);
                            $archivo->Row($texto);

                            $consultaCombo = SQL::seleccionar(array("articulos_combos"),array("id_combo"),"id_articulo = '$datos->id_articulo' AND id_sucursal='$idsucursal' AND principal='1'");
                            $vector_combos = array();
                            if (SQL::filasDevueltas($consultaCombo)) {

                                while($datos_combos = SQL::filaEnObjeto($consultaCombo)){
                                    $combo_mayorista = SQL::obtenerValor("combos","tipo","id='$datos_combos->id_combo'");
                                    if ($datos_combos->id_combo !=0 && $combo_mayorista=='1')
                                        $vector_combos[] = $datos_combos->id_combo;
                                }

                                foreach($vector_combos as $id_combo){

                                    $id_articulos_combo = SQL::seleccionar(array("articulos_combos"),array("id_articulo"),"id_combo = '$id_combo'");
                                    $articulo = "";
                                    $marca    = "Marcas: ";

                                    while($datos_articulos_combo = SQL::filaEnObjeto($id_articulos_combo)){
                                        $codigo_interno = SQL::obtenerValor("articulos","codigo_interno","id='$datos_articulos_combo->id_articulo'");
                                        $id_marca = SQL::obtenerValor("articulos","id_marca","id='$datos_articulos_combo->id_articulo'");
                                        $descripcion_marca = SQL::obtenerValor("marcas","descripcion","id='$id_marca'");
                                        $articulo .= $codigo_interno." + ";
                                        $marca.= $descripcion_marca.", ";
                                    }

                                    $articulo = rtrim($articulo," + ");
                                    $marca    = rtrim($marca,", ");

                                    $numero_lineas = count($texto);
                                    $salto_pagina = false;
                                    if ($numero_lineas > 0){
                                        $salto_pagina = $archivo->NewPage($texto);
                                    }

                                    if ($salto_pagina){

                                        $archivo->AddPage();
                                        $archivo->SetFont('Arial','B',8);
                                        $archivo->Cell(200, 8, $textos["CLIENTE_MAYORISTA"], 0, 0, "I", false);
                                        $archivo->Ln(8);
                                        $archivo->SetFont('Arial','B',8);
                                        $archivo->Cell(200, 8, $textos["TITULO_ALMACEN"].": ".$nombre_sucursal, 0, 0, "I", false);
                                        $archivo->Ln(8);

                                        if($imprimir_subtitulos){
                                            if ($salto_pagina_marca && $primer_marca_mayorista && ($datos->marca != $descripcion_marca_anterior_mayorista)){
                                                $archivo->SetFont('Arial','B',8);
                                                $archivo->Cell(200, 8, $nombre_grupo, 0, 0, "I", false);
                                                $archivo->Ln(8);
                                                $imprimir_encabezado_marca = true;
                                            }
                                        }

                                        $anchoColumnas  = array("18","18","60");
                                        $tituloColumnas = array($textos["CODIGO"],$textos["MARCA"],$textos["DESCRIPCION"]);
                                        $consulta_margenes_mayoristas = SQL::seleccionar(array("margenes_precios_mayoristas"), array("nombre"), "", "", "columna ASC");
                                        if (SQL::filasDevueltas($consulta_margenes_mayoristas)) {
                                            while ($datos_margenes_mayoristas = SQL::filaEnObjeto($consulta_margenes_mayoristas)) {
                                                $anchoColumnas[]  = "15";
                                                $tituloColumnas[] = $datos_margenes_mayoristas->nombre;
                                            }
                                        }

                                        $anchoColumnas[]  = "25";
                                        $tituloColumnas[] = $textos["OBSERVACIONES"];

                                        $anchoColumnas[]  = "5";
                                        $tituloColumnas[] = $textos["TEXTO_INCENTIVO"];

                                        if ($imprimir_fecha_lista){
                                            $anchoColumnas[]  = "15";
                                            $tituloColumnas[] = $textos["FECHA"];
                                        }

                                        $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
                                        $archivo->Ln(4);

                                        $imprimir_encabezado_marca = false;
                                    }

                                    $ancho = array();
                                    $texto = array();
                                    $aligns= array();

                                    $ancho[] = 18;
                                    $texto[] = $articulo;
                                    $aligns[] = "L";

                                    $ancho[] = 18;
                                    $texto[] = $marca;
                                    $aligns[] = "L";

                                    $nombreCombo = SQL::obtenerValor("combos","descripcion","id='$id_combo'");
                                    $ancho[] = 60;
                                    $texto[] = "Combo: ".$nombreCombo;
                                    $aligns[] = "L";

                                    $consulta1 = SQL::seleccionar(array("margenes_precios_mayoristas"), array("id", "nombre", "porcentaje"), "", "", "columna ASC");
                                    if (SQL::filasDevueltas($consulta1)) {
                                        $margenes     = array();
                                        $primerMargen = false;

                                        while ($datosMargen = SQL::filaEnObjeto($consulta1)) {
                                            $idMargen           = $datosMargen->id;
                                            $fecha_inicial_combo = SQL::obtenerValor("precios_mayoristas","fecha_inicial","id_sucursal='$idsucursal' AND id_margen='$idMargen' AND id_combo='$id_combo' ORDER BY fecha_inicial DESC LIMIT 1");
                                            $valorVentaMayorista = SQL::obtenerValor("precios_mayoristas","SUM(precio_venta)","id_sucursal='$idsucursal' AND id_margen='$idMargen' AND id_combo='$id_combo' AND fecha_inicial='$fecha_inicial_combo'");
                                            //ORDER BY fecha_inicial DESC LIMIT 1

                                            $ancho[] = 15;
                                            $texto[] = "$".number_format($valorVentaMayorista);
                                            $aligns[] = "R";
                                        }
                                    }

                                    $observaciones = SQL::obtenerValor("precios_mayoristas","observaciones","id_sucursal='$idsucursal' AND id_combo='$id_combo' ORDER BY fecha_inicial DESC LIMIT 1");
                                    $ancho[] = 25;
                                    $texto[] = $observaciones;
                                    $aligns[] = "L";

                                    $incentivo = "";
                                    $ancho[] = 5;

                                    $texto[] = $incentivo;
                                    $aligns[] = "C";

                                    if ($imprimir_fecha_lista){
                                        $fecha_inicial    = SQL::obtenerValor("precios_mayoristas","fecha_inicial","id_sucursal='$idsucursal' AND id_combo='$id_combo' ORDER BY fecha_inicial DESC LIMIT 1");
                                        $ancho[] = 15;
                                        $texto[] = $fecha_inicial;
                                        $aligns[] = "R";
                                    }

                                    $archivo->SetFont('Arial','',6);
                                    $archivo->SetAligns($aligns);
                                    $archivo->SetWidths($ancho);
                                    $archivo->Row($texto);
                                }
                            }
                        }
                    }
                }
            }

            if ($tipo_sucursal =='2' || $tipo_sucursal =='3'){

                $anchoColumnas = array();
                $alineacionColumnas = array();
                $tituloColumnas = array();
                $formatoColumnas = array();
                $margenes = array();

                $imprimir_encabezado = true;
                $imprimir_encabezado_marca = false;
                $primer_grupo_detallista = false;
                $primer_marca_detallista = false;
                $nombre_grupo_anterior_detallista = "";
                $descripcion_marca_anterior_detallista = "";
                $id_grupo_anterior = "";

                $condicion      = "id_combo=0 AND id_sucursal='$idsucursal' $condicion_grupos $condicion_marca";
                $agrupamiento   = "";
                //$ordenamiento   = "$primer_campo_seleccion $primer_campo_ordenamiento, $segundo_campo_seleccion $segundo_campo_ordenamiento, fecha_inicial DESC, precio_compra ASC";
                $ordenamiento   = "$primer_campo_seleccion $primer_campo_ordenamiento, $segundo_campo_seleccion $segundo_campo_ordenamiento, $tercer_campo_seleccion $tercer_campo_ordenamiento,fecha_inicial DESC, precio_compra ASC";
                $consultaInformacion = SQL::seleccionar(array("lista_detallista"), array("*"), $condicion, $agrupamiento, $ordenamiento);

                if (SQL::filasDevueltas($consultaInformacion)) {

                    if (!$primer_grupo_detallista){
                        if ($salto_pagina_sucursal){
                            $archivo->AddPage();
                        }

                        $archivo->SetFont('Arial','B',8);
                        $archivo->Cell(200, 8, $textos["CLIENTE_DETALLISTA"], 0, 0, "I", false);
                        $archivo->Ln(8);
                        $archivo->SetFont('Arial','B',8);
                        $archivo->Cell(200, 8, $textos["TITULO_ALMACEN"].": ".$nombre_sucursal, 0, 0, "I", false);
                        $archivo->Ln(8);
                    }

                    $sin_datos_detallistas = false;

                    if($imprimir_encabezado){
                        $anchoColumnas  = array("18","18","70");
                        $tituloColumnas = array($textos["CODIGO"],$textos["MARCA"],$textos["DESCRIPCION"]);

                        $anchoColumnas[]  = "20";
                        $tituloColumnas[] = $textos["PRECIO_VENTA_PUBLICO"];

                        if($imprimir_contado_lista){
                            $anchoColumnas[]  = "25";
                            $tituloColumnas[] = $textos["PRECIO_CONTADO"];
                        }

                        $anchoColumnas[]  = "30";
                        $tituloColumnas[] = $textos["OBSERVACIONES"];

                        $anchoColumnas[]  = "5";
                        $tituloColumnas[] = $textos["TEXTO_INCENTIVO"];

                        if ($imprimir_fecha_lista){
                            $anchoColumnas[]  = "15";
                            $tituloColumnas[] = $textos["FECHA"];
                        }

                        $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
                        $archivo->Ln(4);

                        $imprimir_encabezado = false;
                    }

                    $id_articulo_anterior = "";
                    $id_sucursal_anterior = "";

                    while ($datos = SQL::filaEnObjeto($consultaInformacion)) {

                        $nombre_grupo   = SQL::obtenerValor("estructura_grupos","descripcion","id = '$datos->estructura_grupo'");
                        $nombre_grupo_anterior_detallista = $nombre_grupo;

                        if ($datos->id_articulo != $id_articulo_anterior){
                            $separa_articulo = true;
                        } else {
                            $separa_articulo = false;
                        }

                        $id_articulo_anterior = $datos->id_articulo;

                        if ($separa_articulo){

                            $numero_lineas = count($texto);
                            $salto_pagina = false;
                            if ($numero_lineas > 0){
                                $salto_pagina = $archivo->NewPage($texto);
                            }

                            if(
                               ($salto_pagina_grupo && $primer_grupo_detallista && ($datos->id_grupo != $id_grupo_anterior)) ||
                               ($salto_pagina_marca && $primer_marca_detallista && ($datos->marca != $descripcion_marca_anterior_detallista)) ||
                               $salto_pagina
                              ){
                                $archivo->AddPage();
                                $archivo->SetFont('Arial','B',8);
                                $archivo->Cell(200, 8, $textos["CLIENTE_DETALLISTA"], 0, 0, "I", false);
                                $archivo->Ln(8);
                                $archivo->SetFont('Arial','B',8);
                                $archivo->Cell(200, 8,$textos["TITULO_ALMACEN"].": ".$nombre_sucursal, 0, 0, "I", false);
                                $archivo->Ln(8);
                                $imprimir_encabezado_marca = true;
                            }

                            /*if($imprimir_subtitulos){
                                if ($salto_pagina_marca && $primer_marca_detallista && ($datos->marca != $descripcion_marca_anterior_detallista)){
                                    $archivo->SetFont('Arial','B',8);
                                    $archivo->Cell(200, 8, $nombre_grupo, 0, 0, "I", false);
                                    $archivo->Ln(8);
                                    $imprimir_encabezado_marca = true;
                                }
                            }*/
                            $primer_marca_detallista = true;
                            $primer_grupo_detallista = true;
                            $descripcion_marca_anterior_detallista = $datos->marca;
                            $id_grupo_anterior = $datos->id_grupo;

                            if($imprimir_encabezado_marca){
                                $anchoColumnas  = array("18","18","70");
                                $tituloColumnas = array($textos["CODIGO"],$textos["MARCA"],$textos["DESCRIPCION"]);

                                $anchoColumnas[]  = "20";
                                $tituloColumnas[] = $textos["PRECIO_VENTA_PUBLICO"];

                                if($imprimir_contado_lista){
                                    $anchoColumnas[]  = "25";
                                    $tituloColumnas[] = $textos["PRECIO_CONTADO"];
                                }

                                $anchoColumnas[]  = "30";
                                $tituloColumnas[] = $textos["OBSERVACIONES"];

                                $anchoColumnas[]  = "5";
                                $tituloColumnas[] = $textos["TEXTO_INCENTIVO"];

                                if ($imprimir_fecha_lista){
                                    $anchoColumnas[]  = "15";
                                    $tituloColumnas[] = $textos["FECHA"];
                                }

                                $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
                                $archivo->Ln(4);

                                $imprimir_encabezado_marca = false;
                            }

                            $ancho = array();
                            $texto = array();
                            $aligns= array();

                            $ancho[] = 18;
                            $texto[] = $datos->codigo_interno;
                            $aligns[] = "L";

                            $ancho[] = 18;
                            $texto[] = $datos->marca;
                            $aligns[] = "L";

                            $ancho[] = 70;
                            $texto[] = $datos->descripcion;
                            $aligns[] = "L";

                            $ancho[] = 20;
                            $texto[] = "$".number_format($datos->precio_venta_publico);
                            $aligns[] = "R";

                            if($imprimir_contado_lista){
                                $ancho[] = 25;
                                $texto[] = "$".number_format($datos->precio_contado);
                                $aligns[] = "R";
                            }

                            $ancho[] = 30;
                            $texto[] = $datos->observaciones;
                            $aligns[] = "L";

                            $incentivo = "";
                            if($datos->incentivo_detallista>0)
                                $incentivo = $textos["INCENTIVOS"];

                            $ancho[] = 5;
                            $texto[] = $incentivo;
                            $aligns[] = "C";

                            if ($imprimir_fecha_lista){
                                $ancho[] = 15;
                                $texto[] = $datos->fecha_inicial;
                                $aligns[] = "R";
                            }

                            $archivo->SetFont('Arial','',6);
                            $archivo->SetAligns($aligns);
                            $archivo->SetWidths($ancho);
                            $archivo->Row($texto);

                            $consultaCombo = SQL::seleccionar(array("articulos_combos"),array("id_combo"),"id_articulo = '$datos->id_articulo' AND id_sucursal='$idsucursal' AND principal='1'");
                            $vector_combos = array();
                            if (SQL::filasDevueltas($consultaCombo)) {

                                while($datos_combos = SQL::filaEnObjeto($consultaCombo)){
                                    $combo_mayorista = SQL::obtenerValor("combos","tipo","id='$datos_combos->id_combo'");
                                    if ($datos_combos->id_combo !=0 && $combo_mayorista=='2')
                                        $vector_combos[] = $datos_combos->id_combo;
                                }

                                foreach($vector_combos as $id_combo){

                                    $id_articulos_combo = SQL::seleccionar(array("articulos_combos"),array("id_articulo"),"id_combo = '$id_combo'");
                                    $articulo = "";
                                    $marca    = "Marcas: ";

                                    while($datos_articulos_combo = SQL::filaEnObjeto($id_articulos_combo)){
                                        $codigo_interno = SQL::obtenerValor("articulos","codigo_interno","id='$datos_articulos_combo->id_articulo'");
                                        $id_marca = SQL::obtenerValor("articulos","id_marca","id='$datos_articulos_combo->id_articulo'");
                                        $descripcion_marca = SQL::obtenerValor("marcas","descripcion","id='$id_marca'");
                                        $articulo .= $codigo_interno." + ";
                                        $marca.= $descripcion_marca.", ";
                                    }

                                    $articulo = rtrim($articulo," + ");
                                    $marca    = rtrim($marca,", ");

                                    $numero_lineas = count($texto);
                                    $salto_pagina = false;
                                    if ($numero_lineas > 0){
                                        $salto_pagina = $archivo->NewPage($texto);
                                    }

                                    if ($salto_pagina){

                                        $archivo->AddPage();
                                        $archivo->SetFont('Arial','B',8);
                                        $archivo->Cell(200, 8, $textos["CLIENTE_MAYORISTA"], 0, 0, "I", false);
                                        $archivo->Ln(8);
                                        $archivo->SetFont('Arial','B',8);
                                        $archivo->Cell(200, 8, $textos["TITULO_ALMACEN"].": ".$nombre_sucursal, 0, 0, "I", false);
                                        $archivo->Ln(8);

                                        /*if($imprimir_subtitulos){
                                            if ($salto_pagina_marca && $primer_marca_mayorista && ($datos->marca != $descripcion_marca_anterior_mayorista)){
                                                $archivo->SetFont('Arial','B',8);
                                                $archivo->Cell(200, 8, $nombre_grupo, 0, 0, "I", false);
                                                $archivo->Ln(8);
                                                $imprimir_encabezado_marca = true;
                                            }
                                        }*/

                                        $anchoColumnas  = array("18","18","60");
                                        $tituloColumnas = array($textos["CODIGO"],$textos["MARCA"],$textos["DESCRIPCION"]);

                                        $anchoColumnas[]  = "20";
                                        $tituloColumnas[] = $textos["PRECIO_VENTA_PUBLICO"];

                                        if($imprimir_contado_lista){
                                            $anchoColumnas[]  = "25";
                                            $tituloColumnas[] = $textos["PRECIO_CONTADO"];
                                        }

                                        $anchoColumnas[]  = "25";
                                        $tituloColumnas[] = $textos["OBSERVACIONES"];

                                        $anchoColumnas[]  = "5";
                                        $tituloColumnas[] = $textos["TEXTO_INCENTIVO"];

                                        if ($imprimir_fecha_lista){
                                            $anchoColumnas[]  = "15";
                                            $tituloColumnas[] = $textos["FECHA"];
                                        }

                                        $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
                                        $archivo->Ln(4);

                                        $imprimir_encabezado_marca = false;
                                    }

                                    $ancho = array();
                                    $texto = array();
                                    $aligns= array();

                                    $ancho[] = 18;
                                    $texto[] = $articulo;
                                    $aligns[] = "L";

                                    $ancho[] = 18;
                                    $texto[] = $marca;
                                    $aligns[] = "L";

                                    $nombreCombo = SQL::obtenerValor("combos","descripcion","id='$id_combo'");
                                    $ancho[] = 70;
                                    $texto[] = "Combo: ".$nombreCombo;
                                    $aligns[] = "L";

                                    $fecha_inicial_combo = SQL::obtenerValor("precios_detallistas","fecha_inicial","id_sucursal='$idsucursal' AND id_combo='$id_combo' ORDER BY fecha_inicial DESC LIMIT 1");

                                    $precio_venta_publico = SQL::obtenerValor("precios_detallistas","SUM(precio_venta_publico)","id_sucursal='$idsucursal' AND id_combo='$id_combo' ORDER BY fecha_inicial DESC LIMIT 1");
                                    $ancho[] = 20;
                                    $texto[] = "$".number_format($precio_venta_publico);
                                    $aligns[] = "R";

                                    if($imprimir_contado_lista){
                                        $precio_contado      = SQL::obtenerValor("precios_detallistas","SUM(precio_contado)","id_sucursal='$idsucursal' AND id_combo='$id_combo' AND fecha_inicial='$fecha_inicial_combo'");
                                        //ORDER BY fecha_inicial DESC LIMIT 1
                                        $ancho[] = 25;
                                        $texto[] = "$".number_format($precio_contado);
                                        $aligns[] = "R";
                                    }

                                    $ancho[] = 30;
                                    $texto[] = $datos->observaciones;
                                    $aligns[] = "L";

                                    $incentivo = "";
                                    $ancho[] = 5;
                                    $texto[] = $incentivo;
                                    $aligns[] = "C";

                                    if ($imprimir_fecha_lista){
                                        $ancho[] = 15;
                                        $texto[] = $fecha_inicial_combo;
                                        $aligns[] = "R";
                                    }

                                    $archivo->SetFont('Arial','',6);
                                    $archivo->SetAligns($aligns);
                                    $archivo->SetWidths($ancho);
                                    $archivo->Row($texto);
                                }
                            }
                        }
                    }
                }
            }
        }
        $archivo->Output($nombreArchivo, "F");
        SQL::insertar("archivos", array("nombre" => $nombre));
        $id_archivo     = SQL::$ultimoId;
        $ruta_archivo   = HTTP::generarURL("DESCARCH")."&id=".$id_archivo."&temporal=1";
        $cargaPdf = 1;
    }

    if ($sin_datos_detallistas && $sin_datos_mayoristas && $datos_completos){
        $error = true;
        $mensaje = $textos["NO_GENERO_INFORMACION"];
        $ruta_archivo = "";
    }

    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    $respuesta[2] = $ruta_archivo;
    HTTP::enviarJSON($respuesta);
}
?>

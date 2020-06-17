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
        "1" => $textos["PROVEEDOR"],
        "2" => $textos["MARCA"],
        "3" => $textos["ARTICULO"]        
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
    }else{
        if(isset($url_id_item2)){
            for($i=0; $i<count($selector); $i++){
                if($url_id_item1 != $i && $url_id_item2 != $i){
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
        "1" => $textos["PROVEEDOR"],
        "2" => $textos["MARCA"],
        "3" => $textos["ARTICULO"]        
    );
    $ordenamiento= array(
        "0" => $textos["NOAPLICA"],
        "1" => $textos["ASCENDENTE"],
        "2" => $textos["DESCENDENTE"]        
    );

    $consulta = SQL::seleccionar(array("grupos"), array("id", "descripcion"), "", "", "descripcion ASC");
    if (SQL::filasDevueltas($consulta)) {
        $grupos = array();
	$grupos[] = array(HTML::boton("botonSeleccionarTodos", $textos["SELECCIONAR_TODOS"], "seleccionar_todos_grupos();", "", array()));
	while ($datos = SQL::filaEnObjeto($consulta)) {
            $grupos[] = array(HTML::marcaChequeo("grupos[]", $datos->descripcion, $datos->id, false, array("class"=>"grupos_electrodomesticos")));
        }
    }

    /*** Obtener lista de marcas para selección ***/
    $consulta = SQL::seleccionar(array("marcas"), array("id", "descripcion"), "", "", "descripcion ASC");
    if (SQL::filasDevueltas($consulta)) {
        $marcas = array();

	$marcas[] = array(HTML::boton("botonSeleccionarTodos", $textos["SELECCIONAR_TODOS"], "seleccionar_todos_marcas();", "", array()));
        while ($datos = SQL::filaEnObjeto($consulta)) {
            $marcas[] = array(HTML::marcaChequeo("marcas[]", $datos->descripcion, $datos->id, false, array("class"=>"marcas_electrodomesticos")));
        }
    }

    $consulta = SQL::seleccionar(array("seleccion_proveedores"), array("id", "nombre"), "", "", "nombre ASC");
    if (SQL::filasDevueltas($consulta)) {
        $proveedores = array();

	$proveedores[] = array(HTML::boton("botonSeleccionarTodos", $textos["SELECCIONAR_TODOS"], "seleccionar_todos_proveedores();", "", array()));
        while ($datos = SQL::filaEnObjeto($consulta)) {
	    
            $texto_1 = $datos->nombre;
            $vector_texto= explode("|", $texto_1);
            $texto_2 = explode("-",$vector_texto[0]);
            $nombre_proveedor = $texto_2[1];

            $proveedores[] = array(HTML::marcaChequeo("proveedores[]", $nombre_proveedor, $datos->id, false, array("class"=>"proveedores_electrodomesticos")));
        }
    }

    /*** Definición de pestañas ***/
    $formularios["PESTANA_PROVEEDORES"] = $proveedores;
    $formularios["PESTANA_MARCAS"] 	= $marcas;
    $formularios["PESTANA_GRUPOS"]      = $grupos;
    

    $formularios["PESTANA_ORDEN"] = array(
        array(
            HTML::listaSeleccionSimple("item1", $textos["ITEM1"], $selector, "", array("onchange"=>"funcion_ordenamiento_1()")),
            HTML::listaSeleccionSimple("orden1", $textos["ORDEN1"], $ordenamiento, "", array("class" => "oculto"))
        ),array(
            HTML::listaSeleccionSimple("item2", $textos["ITEM1"], $selector, "", array("class" => "oculto", "onchange"=>"funcion_ordenamiento_2()")),
            HTML::listaSeleccionSimple("orden2", $textos["ORDEN1"], $ordenamiento, "", array("class" => "oculto"))
        ),array(
            HTML::listaSeleccionSimple("item3", $textos["ITEM1"], $selector, "", array("class" => "oculto", "onchange"=>"funcion_ordenamiento_3()")),
            HTML::listaSeleccionSimple("orden3", $textos["ORDEN1"], $ordenamiento, "", array("class" => "oculto"))
        )
    );

    /*** Definición de botones ***/
    $botones = array(
        HTML::boton("botonAceptar", $textos["ACEPTAR"], "exportarDatos();", "aceptar", array("class" => "pdf"))
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
      if(empty($forma_grupos) || empty($forma_proveedores)){
	      $cargaPdf = 0;  
	      $mensaje = $textos["ERROR_DATOS_INCOMPLETOS"];	
      }else{
 	      /******************************************************/ 
	      $nombreArchivo = $rutasGlobales["archivos"]."/".$componente->id."-".Sesion::$id.".pdf";
	      $anchoColumnas = array(20,50);	      
	      $cargaPdf = 0;
	      $mensaje = "";	  

	      $fechaReporte = date("Y-m-d");
	      $archivo = new PDF("P","mm","Letter");
	      $archivo->textoCabecera = $textos["FECHA"].": $fechaReporte";        
	      /******************************************************/

	      /*** Datos Seleccionados por el Usuario ***/
	      foreach ($forma_proveedores as $proveedor){
            $indice_proveedores[] = $proveedor;		
	      }foreach ($forma_grupos as $grupo) {
            $indice_grupos[] = $grupo;
	      }
	      /*******************************************/

	      /*** Datos Seleccionados por el Usuario, pero correspondientes al sistema ***/
	      $consulta2  = SQL::seleccionar(array("marcas"), array("id","descripcion"), "", "", "");
	      while ($datos2 = SQL::filaEnObjeto($consulta2) ) {
		    $indice_marcas[] = $datos2->id;
		    $descripcion_marca[] = $datos2->descripcion;
	      }
	      /*******************************************/

	      /*******************************************/
	      if($forma_item1!=0){

		    /******************************************************/	
		    $vector1 = array();
		    if($forma_item1!=0){
			$vector1[] = $forma_item1;
		    }
		    if($forma_item2!=0){
			$vector1[] = $forma_item2;
		    }
		    if(isset($forma_item3) && $forma_item3!=0){
			$vector1[] = $forma_item3;
		    }

		    $variable=array();
		    foreach($vector1 AS $id1){
                switch($id1){
                    case 1: $orden[] = $indice_proveedores;//Proveedor
                        $variable[] = "id_proveedor";
                        $tipo[] = 1;
                        break;

                    case 2: $orden[] = $indice_marcas;//Marca
                        $variable[] = "id_marca";
                        $tipo[] = 2;
                        break;

                    case 3: $orden[] = $indice_grupos;//Artículo
                        $variable[] = "indice_grupo";
                        $tipo[] = 3;
                        break;
                }
		    }


			/**************************************/
			$vector_vacio = array();
			if(count($orden)==2){
				foreach($orden[1] AS $id){
				      $vector_vacio[] = "";
				} 
				$orden[2] = $vector_vacio;
			} 
			$vector_vacio1 = array();
			if(count($orden)==1){
				foreach($orden[0] AS $id){
				      $vector_vacio1[] = "";
				      $vector_vacio[] = "";
				} 
				$orden[1] = $vector_vacio1;
				$orden[2] = $vector_vacio;
			} /*** borrar si no funciona **/
			/**************************************/



			foreach($orden[0] AS $id_1){

			      if($tipo[0]==1){
				    $consulta   = SQL::obtenerValor("seleccion_proveedores","nombre","id = '$id_1'");
				    $texto   	= explode("|",$consulta);
				    $nombre 	= $texto[0];	
				    $elemento   = "PROVEEDOR";
			      }if($tipo[0]==2){
				    $consulta   = SQL::obtenerValor("marcas","descripcion","id = '$id_1'");
				    $texto   	= explode("|",$consulta);
				    $nombre 	= $texto[0];
				    $elemento   = "MARCA";	
			      }if($tipo[0]==3){
				    $consulta    = SQL::obtenerValor("grupos","descripcion","id = '$id_1'");
				    $texto   	 = explode("|",$consulta);
				    $nombre	 = $texto[0];	
				    $elemento   = "ARTICULO";
			      }
				
			      $y=0;

			      foreach($orden[1] AS $id_2){
				    foreach($orden[2] AS $id_3){

					  $tablas = array("informes_articulos");
					  $datos = array("id", "CODIGO", "DESCRIPCION", "MARCA", "REFERENCIA", "ESTRUCTURA_GRUPO", "PROVEEDOR");
					  
					  if(count($orden)==1){
					      $condicion = "$variable[0] = '$id_1'";
					  }if(count($orden)==2){
					      $condicion = "$variable[0] = '$id_1' AND $variable[1] = '$id_2'";
					  }if(count($orden)==3){
					      $condicion = "$variable[0] = '$id_1' AND $variable[1] = '$id_2' AND $variable[2] = '$id_3'";
					  }
					  
					  $consulta2  = SQL::seleccionar($tablas, $datos, $condicion, "", "");
					  $i=0;
					  while ($datos2 = SQL::filaEnObjeto($consulta2) ) {

						if($y==0){
                            $archivo->AddPage();
                            $archivo->SetFont('Arial','B',8);
                            $archivo->Cell(70, 8, htmlspecialchars_decode($textos[$elemento].": ".$nombre), 0, "B", "L", false);
                            $archivo->Ln(8);

                            $archivo->SetFont('Arial','B',6);
                            $archivo->Cell(18, 8, $textos["CODIGO_ART"], 1, 0, "C", false);
                            $archivo->Cell(57, 8, $textos["DESCRIPCION"], 1, 0, "C", false);
                            $archivo->Cell(20, 8, $textos["MARCA"], 1, 0, "C", false);
                            $archivo->Cell(30, 8, $textos["REFERENCIA"], 1, 0, "C", false);
                            $archivo->Cell(25, 8, $textos["ESTRUCTURA_GRUPO"], 1, 0, "C", false);
                            $archivo->Cell(50, 8, $textos["PROVEEDOR"], 1, 0, "C", false);
                            $archivo->Ln(8);
						}$y++;

						if($i%2==0){
						    $rojo = 255;
						    $azul = 255;
						    $verde = 255;
						}else{
						    $rojo = 226;
						    $azul = 236;
						    $verde = 237;
						}

						$codigo 	 = $datos2->CODIGO;
						$descripcion 	 = $datos2->DESCRIPCION;
						$marca 	 	 = $datos2->MARCA;
						$referencia 	 = $datos2->REFERENCIA;
						$estructura	 = $datos2->ESTRUCTURA_GRUPO;
						$proveedor	 = $datos2->PROVEEDOR;

						$archivo->SetFont('Arial','',6);
						$archivo->SetFillColor($rojo,$verde,$azul);
						$archivo->Cell(18, 8, $codigo, 1, 0, "L", true);
						$archivo->Cell(57, 8, $descripcion, 1, 0, "L", true);
						$archivo->Cell(20, 8, $marca, 1, 0, "L", true);
						$archivo->Cell(30, 8, $referencia, 1, 0, "L", true);
						$archivo->Cell(25, 8, $estructura, 1, 0, "L", true);
						$archivo->Cell(50, 8, $proveedor, 1, 0, "L", true);
						$archivo->Ln(8);
						
						$i++;
					  }
                    }
				}
		      }
      	      }else{
                foreach($indice_proveedores AS $id_proveedor){

                $archivo->AddPage();

                $consulta     = SQL::obtenerValor("seleccion_proveedores","nombre","id = '$id_proveedor'");
                $texto   	  = explode("|",$consulta);
                $nombre_proveedor = $texto[0];
                $y=0;

                foreach($indice_grupos AS $id_grupo){

                    $tablas = array("informes_articulos");
                    $datos = array("id", "CODIGO", "DESCRIPCION", "MARCA", "REFERENCIA", "ESTRUCTURA_GRUPO", "PROVEEDOR");
                    $condicion = "id_proveedor = '$id_proveedor' AND indice_grupo = '$id_grupo'";

                    $consulta2  = SQL::seleccionar($tablas, $datos, $condicion, "", "");
                    $i=0;
                    while ($datos2 = SQL::filaEnObjeto($consulta2) ) {

                        if($y==0){
                            $archivo->SetFont('Arial','B',8);
                            $archivo->Cell(70, 8, htmlspecialchars_decode($textos["PROVEEDOR"].": ".$nombre_proveedor), 0, "B", "L", false);
                            $archivo->Ln(8);

                            $archivo->SetFont('Arial','B',6);
                            $archivo->Cell(18, 8, $textos["CODIGO_ART"], 1, 0, "C", false);
                            $archivo->Cell(57, 8, $textos["DESCRIPCION"], 1, 0, "C", false);
                            $archivo->Cell(20, 8, $textos["MARCA"], 1, 0, "C", false);
                            $archivo->Cell(30, 8, $textos["REFERENCIA"], 1, 0, "C", false);
                            $archivo->Cell(25, 8, $textos["ESTRUCTURA_GRUPO"], 1, 0, "C", false);
                            $archivo->Cell(50, 8, $textos["PROVEEDOR"], 1, 0, "C", false);
                            $archivo->Ln(8);
                        }$y++;

                        if($i%2==0){
                            $rojo = 255;
                            $azul = 255;
                            $verde = 255;
                        }else{
                            $rojo = 226;
                            $azul = 236;
                            $verde = 237;
                        }

                        $codigo 	 	 = $datos2->CODIGO;
                        $descripcion 	 = $datos2->DESCRIPCION;
                        $marca 	 	 = $datos2->MARCA;
                        $referencia 	 = $datos2->REFERENCIA;
                        $estructura	 = $datos2->ESTRUCTURA_GRUPO;
                        $proveedor	 = $datos2->PROVEEDOR;

                        $archivo->SetFont('Arial','',6);
                        $archivo->SetFillColor($rojo,$verde,$azul);
                        $archivo->Cell(18, 8, $codigo, 1, 0, "L", true);
                        $archivo->Cell(57, 8, $descripcion, 1, 0, "L", true);
                        $archivo->Cell(20, 8, $marca, 1, 0, "L", true);
                        $archivo->Cell(30, 8, $referencia, 1, 0, "L", true);
                        $archivo->Cell(25, 8, $estructura, 1, 0, "L", true);
                        $archivo->Cell(50, 8, $proveedor, 1, 0, "L", true);
                        $archivo->Ln(8);

                        $i++;
                    }
                }
            }
	      }

	      /***********************************************/
	      $archivo->Output($nombreArchivo, "F");
	      $cargaPdf = 1;    
    }


    /*** Enviar datos con la respuesta del proceso al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = false;
    if ($cargaPdf == 1){        
        $respuesta[1] = HTML::enlazarPagina($textos["IMRPIMIR_PDF"], $nombreArchivo, array("target" => "_new"));
    } else{        
        $respuesta[1] = $mensaje;
    }    
    HTTP::enviarJSON($respuesta);
}
?>
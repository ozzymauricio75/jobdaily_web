<?php
    /*** Incluir archivo de configuración principal ***/
    require "../configuracion/global.php";

    /*** Incluir archivos de clases ***/
    require_once $rutasGlobales["clases"]."/sql.php";
    require_once $rutasGlobales["clases"]."/cadena.php";

    SQL::abrirConexion();

    $rutaModulos    = realpath($rutasGlobales["modulos"]);
    $archivoEsquema = $archivosGlobales["esquemaSQL"];
    $prefijoTabla   = SQL::$prefijoTabla;

    $fila = 0;
    $total_insertados = 0;
    $existen = 0;
    $total_referencias = 0;

    if (($archivo = fopen("aspirantesenfriar.csv", "r")) !== FALSE) {

        while (($datos = fgetcsv($archivo, 15000, ";")) !== FALSE) {

            $campos_fila = count($datos);
            
            if ($datos[13] != ""){
                $fecha = explode("/",$datos[13]);
                $fecha_nacimiento = $fecha[2]."-".$fecha[1]."-".$fecha[0];
            } else {
                $fecha_nacimiento = "0000-00-00";
            }

            $datos_tercero = array(
                "documento_identidad"                => $datos[1],
                "codigo_tipo_documento"              => 1,
                //tabla municipios
                "codigo_iso_municipio_documento"     => $datos[2],
                "codigo_dane_departamento_documento" => $datos[3],
                "codigo_dane_municipio_documento"    => $datos[4],
                ////////////////////////////////////
                "tipo_persona"                       => "1",
                "primer_nombre"                      => $datos[7],
                "segundo_nombre"                     => $datos[8],
                "primer_apellido"                    => $datos[5],
                "segundo_apellido"                   => $datos[6],
                "fecha_nacimiento"                   => $fecha_nacimiento,
                //tabla localidades
                "codigo_iso_localidad"               => "CO",
                "codigo_dane_departamento_localidad" => "76",
                "codigo_dane_municipio_localidad"    => "001",
                "tipo_localidad"                     => "B",
                "codigo_dane_localidad"              => "0",
                ////////////////////////////////////
                "direccion_principal"                => "",
                "telefono_principal"                 => $datos[9],
                "celular"                            => $datos[10],
                "genero"                             => "N",
                "activo"                             => "1"
            );
            
            foreach($datos_tercero as $campo => $valor){
                $listaCampos[]  = $campo;

                $valor     = str_replace("&", "&amp;", $valor);
                $valor     = str_replace("<", "&lt;", $valor);
                $valor     = str_replace(">", "&gt;", $valor);
                if (Cadena::contieneUTF8($valor)) {
                    $valor = utf8_decode($valor);
                }

                if (strtolower($valor) == "null"){
                    $listaValores[] = "$valor";
                }else{
                    $listaValores[] = "'$valor'";
                }
            }

            $indices           = implode(",", $listaCampos);
            $valores           = implode(",", $listaValores);
            $nombreTabla       = "terceros";
            $sentenciaInsertar = "REPLACE INTO ".$prefijoTabla.$nombreTabla." ($indices)\n    VALUES($valores);\n";
            $insertar          = SQL::correrConsulta($sentenciaInsertar);
            if (mysql_error()) {
                echo "<span class='error'><b>Error: </b>".mysql_error().":</span><br>".$sentenciaInsertar."<br>";
            }
            unset($listaCampos, $listaValores);

            if ($insertar){

                if ($datos[1] != "Enfriar"){
                    $relacion_laboral= "4";
                } else {
                    $relacion_laboral= "1";
                }


                if ($datos[11] != ""){
                    $fecha = explode("/",$datos[11]);
                    $fecha_ingreso = $fecha[2]."-".$fecha[1]."-".$fecha[0];
                } else {
                    $fecha_ingreso = "0000-00-00";
                }

                if ($datos[12] != ""){
                    $fecha        = explode("/",$datos[12]);
                    $fecha_retiro = $fecha[2]."-".$fecha[1]."-".$fecha[0];
                } else {
                    $fecha_retiro = "0000-00-00";
                }

                $datos_aspirantes = array(
                    "documento_identidad"                    => $datos[1],
                    "tipo_sangre"                            => $datos[14],
                    "codigo_cargo"		    	             => 0,
                    "fecha_ingreso"		                     => $datos[11],
                    "fecha_inicio_vivienda"                  => "",
                    "derecho_sobre_vivienda"                 => "1",
                    "relacion_laboral"                       => $relacion_laboral,
                    "nombre_arrendatario"        		     => "",
                    // Llave municipio arrendatario
                    "codigo_iso_arrendatario"                => "",
                    "codigo_dane_departamento_arrendatario"  => "",
                    "codigo_dane_municipio_arrendatario"     => "",
                    // Fin llave
                    "telefono_arrendatario"			         => "",
                    // Llave municipio mayor estadia
                    "codigo_iso_mayor_estadia"               => "CO",
                    "codigo_dane_departamento_mayor_estadia" => "76",
                    "codigo_dane_municipio_mayor_estadia"    => "001",
                    // Fin llave
                    "codigo_dane_profesion"      	         => 0,
                    "aspiracion_salarial"	                 => 0,
                    "pensionado"                             => "0",
                    "ingreso_pension"    	                 => 0,
                    "experiencia_laboral"                    => "",
                    "recomendacion_interna"                  => "",
                    "estatura"                               => "",
                    "peso"                                   => "",
                    "talla_camisa"                           => $datos[15],
                    "anteojos"                               => "0",
                    "talla_pantalon"                         => $datos[16],
                    "talla_calzado"                          => $datos[17],
                    "digitador"        		                 => "",
                    "programacion"     		                 => "",
                    "hojas_calculo"                          => "1",
                    "procesadores_texto"                     => "1",
                    "diseno_diapositivas"                    => "1",
                    // Llave municipio nacimiento
                    "codigo_iso_nacimiento"                  => $datos[2],
                    "codigo_dane_departamento_nacimiento"    => $datos[3],
                    "codigo_dane_municipio_nacimiento"       => $datos[4],
                    // Fin llave
                    "estado_civil"                           => "1",
                    "clase_libreta_militar"                  => "1",
                    "categoria_permiso_conducir"             => "1",
                    "codigo_entidad_salud"		    	     => 0,
                    "codigo_entidad_pension"	    	     => 0,
                    "codigo_entidad_cesantias"	             => 0
                );

                foreach($datos_aspirantes as $campo => $valor){
                    $listaCampos[]  = $campo;

                    $valor     = str_replace("&", "&amp;", $valor);
                    $valor     = str_replace("<", "&lt;", $valor);
                    $valor     = str_replace(">", "&gt;", $valor);
                    if (Cadena::contieneUTF8($valor)) {
                        $valor = utf8_decode($valor);
                    }

                    if (strtolower($valor) == "null"){
                        $listaValores[] = "$valor";
                    }else{
                        $listaValores[] = "'$valor'";
                    }
                }

                $indices           = implode(",", $listaCampos);
                $valores           = implode(",", $listaValores);
                $nombreTabla  = "aspirantes";
                $sentenciaInsertar = "REPLACE INTO ".$prefijoTabla.$nombreTabla." ($indices)\n    VALUES($valores);\n";
                $insertar          = SQL::correrConsulta($sentenciaInsertar);
                if (mysql_error()) {
                    echo "<span class='error'><b>Error: </b>".mysql_error().":</span><br>".$sentenciaInsertar."<br>";
                }
                unset($listaCampos, $listaValores);

                if ($insertar){
                    echo "<p> Aspirante grabado satisfactoriamente: $datos[7]  $datos[8] $datos[5] $datos[6]<br /></p>\n";

                    if ($datos[12] !=""){
                        $datos_empresas = array(
                            "documento_identidad_aspirante"      => $datos[1],
                            "consecutivo"          		         => 1,
                            "nombre"        		             => "Enfriar",
                            "codigo_iso_actividad"               => "",
                            "codigo_dane_departamento_actividad" => "",
                            "codigo_dane_municipio_actividad"    => "",
                            "codigo_dian_actividad"              => 0,
                            "codigo_actividad_economica"         => 0,
                            "codigo_departamento_empresa"        => 0,
                            "codigo_cargo"		    	         => 0,
                            "fecha_inicial"  		             => $fecha_ingreso,
                            "fecha_final"		                 => $fecha_retiro,
                            "horario_laboral"	                 => "1",
                            "codigo_tipo_contrato"               => 0,
                            "codigo_motivo_retiro"               => 0
                        );

                        foreach($datos_empresas as $campo => $valor){
                            $listaCampos[]  = $campo;

                            $valor     = str_replace("&", "&amp;", $valor);
                            $valor     = str_replace("<", "&lt;", $valor);
                            $valor     = str_replace(">", "&gt;", $valor);
                            if (Cadena::contieneUTF8($valor)) {
                                $valor = utf8_decode($valor);
                            }
                            if (strtolower($valor) == "null"){
                                $listaValores[] = "$valor";
                            }else{
                                $listaValores[] = "'$valor'";
                            }
                        }

                        $indices      = implode(",", $listaCampos);
                        $valores      = implode(",", $listaValores);
                        $nombreTabla  = "empresas_aspirante";
                        $sentenciaInsertar = "REPLACE INTO ".$prefijoTabla.$nombreTabla." ($indices)\n    VALUES($valores);\n";
                        $insertar          = SQL::correrConsulta($sentenciaInsertar);
                        if (mysql_error()) {
                            echo "<span class='error'><b>Error: </b>".mysql_error().":</span><br>".$sentenciaInsertar."<br>";
                        }
                        unset($listaCampos, $listaValores);
                    }

                    $fila++;
                } else {
                    $eliminar = SQL::eliminar("terceros","documento_identidad='$datos[1]'");
                }
            }
        }
        echo "<p> Total aspirantes insertados $fila <br /></p>\n";
        fclose($archivo);
    }
?>

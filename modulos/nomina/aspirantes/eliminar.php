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
* publicada por la Fundacion para el Software Libre, ya sea la version 3
* de la Licencia, o (a su eleccion) cualquier version posterior.
*
* Este programa se distribuye con la esperanza de que sea útil, pero
* SIN GARANTÍA ALGUNA; ni siquiera la garantía implícita MERCANTIL o
* de APTITUD PARA UN PROPÓITO DETERMINADO. Consulte los detalles de
* la Licencia Pública General GNU para obtener una información más
* detallada.
*
* Debería haber recibido una copia de la Licencia Pública General GNU
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
**/

// Generar el formulario para la captura de datos
if (!empty($url_generar)) {

    // Verificar que se haya enviado el ID del elemento a consultar
    if (empty($url_id)) {
        $error     = $textos["ERROR_ELIMINAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    }else{

        $error           = "";
        $titulo          = $componente->nombre;

        $vistaConsulta   = "terceros";
        $columnas        = SQL::obtenerColumnas($vistaConsulta);
        $consulta        = SQL::seleccionar(array($vistaConsulta), $columnas, "documento_identidad = '".$url_id."'");
        $datos_terceros  = SQL::filaEnObjeto($consulta);

        $vistaConsulta   = "aspirantes";
        $columnas        = SQL::obtenerColumnas($vistaConsulta);
        $consulta        = SQL::seleccionar(array($vistaConsulta), $columnas, "documento_identidad = '".$url_id."'");
        $datos_aspirante = SQL::filaEnObjeto($consulta);

        $tipo_documento  = SQL::obtenerValor("tipos_documento_identidad","descripcion","codigo=$datos_terceros->codigo_tipo_documento");

        $llave_primaria_municipio_tercero = $datos_terceros->codigo_iso_municipio_documento."|".$datos_terceros->codigo_dane_departamento_documento."|".$datos_terceros->codigo_dane_municipio_documento;

        $municipio_expedicion = SQL::obtenerValor("seleccion_municipios","SUBSTRING_INDEX(nombre,'|',1)","id='".$llave_primaria_municipio_tercero."'");


        $llave_primaria_municipio_aspirante = $datos_aspirante->codigo_iso_nacimiento."|".$datos_aspirante->codigo_dane_departamento_nacimiento."|".$datos_aspirante->codigo_dane_municipio_nacimiento;

        $municipio_nacimiento = SQL::obtenerValor("seleccion_municipios","SUBSTRING_INDEX(nombre,'|',1)","id='".$llave_primaria_municipio_aspirante."'");


        $estado_civil          = $datos_aspirante->estado_civil;
        $clase_libreta_militar = $datos_aspirante->clase_libreta_militar;
        $categoria_pase        = $datos_aspirante->categoria_permiso_conducir;
        $derecho_vivienda      = $datos_aspirante->derecho_sobre_vivienda;

        if($datos_terceros->genero == "M"){
            $genero = $textos["GENERO_MASCULINO"];
        }elseif($datos_terceros->genero == "F"){
            $genero = $textos["GENERO_FEMENINO"];
        }else{
            $genero = $textos["NO_APLICA"];
        }

        if($estado_civil == 1){
            $estado_civil = $textos["SOLTERO"];
        }elseif($estado_civil == 2){
            $estado_civil = $textos["CASADO"];
        }elseif($estado_civil == 3){
            $estado_civil = $textos["UNION_LIBRE"];
        }elseif($estado_civil == 4){
            $estado_civil = $textos["DIVORCIADO"];
        }else{
            $estado_civil = $textos["VIUDO"];
        }

        if($clase_libreta_militar == 1){
            $tipo_libreta = $textos["DATO_VACIO"];
        }elseif($clase_libreta_militar == 2){
            $tipo_libreta = $textos["PRIMERA_CLASE"];
        }elseif($clase_libreta_militar == 3){
            $tipo_libreta = $textos["SEGUNDA_CLASE"];
        }

        if($categoria_pase == 1){
            $categoria_pase = $textos["NO_TIENE"];
        }elseif($categoria_pase == 2){
            $categoria_pase = $textos["PRIMERA_CATEGORIA"];
        }elseif($categoria_pase == 3){
            $categoria_pase = $textos["SEGUNDA_CATEGORIA"];
        }elseif($categoria_pase == 4){
            $categoria_pase = $textos["TERCERA_CATEGORIA"];
        }elseif($categoria_pase == 5){
            $categoria_pase = $textos["CUARTA_CATEGORIA"];
        }elseif($categoria_pase == 6){
            $categoria_pase = $textos["QUINTA_CATEGORIA"];
        }else{
            $categoria_pase = $textos["SEXTA_CATEGORIA"];
        }



         $formularios["PESTANA_IDENTIFICACION"] = array(
            array(
                HTML::mostrarDato("tipo_documento", $textos["TIPO_DOCUMENTO"], $tipo_documento),
                HTML::mostrarDato("numero_documento", $textos["NUMERO_DOCUMENTO"], $datos_terceros->documento_identidad),
                HTML::mostrarDato("municipio_expedicion", $textos["MUNICIPIO_EXPEDICION_DOCUMENTO"], $municipio_expedicion)
            ),
            array(
                HTML::mostrarDato("primer_nombre", $textos["PRIMER_NOMBRE"], $datos_terceros->primer_nombre),
                HTML::mostrarDato("segundo_nombre", $textos["SEGUNDO_NOMBRE"], $datos_terceros->segundo_nombre)
            ),
            array(
                HTML::mostrarDato("primer_apellido", $textos["PRIMER_APELLIDO"], $datos_terceros->primer_apellido),
                HTML::mostrarDato("segundo_apellido", $textos["SEGUNDO_APELLIDO"], $datos_terceros->segundo_apellido)
            ),
            array(
                HTML::mostrarDato("genero", $textos["GENERO"], $genero),
                HTML::mostrarDato("tipo_sangre", $textos["TIPO_SANGRE"], $datos_aspirante->tipo_sangre)
            ),
            array(
                HTML::mostrarDato("fecha_nacimiento", $textos["FECHA_NACIMIENTO"], $datos_terceros->fecha_nacimiento),
                HTML::mostrarDato("municipio_nacimiento", $textos["MUNICIPIO_NACIMIENTO"], $municipio_nacimiento)
            ),
            array(
                HTML::mostrarDato("estado_civil", $textos["ESTADO_CIVIL"], $estado_civil)
            ),
            array(
                HTML::mostrarDato("clase_libreta_militar", $textos["TIPO_LIBRETA"], $tipo_libreta),
                HTML::mostrarDato("libreta_militar", $textos["NUMERO_LIBRETA_MILITAR"], $datos_aspirante->libreta_militar),
                HTML::mostrarDato("distrito_militar", $textos["DISTRITO_MILITAR"], $datos_aspirante->distrito_militar)
            ),
            array(
                HTML::mostrarDato("categoria_permiso_conducir", $textos["CATEGORIA_PERMISO_CONDUCCION"], $categoria_pase),
                HTML::mostrarDato("permiso_conducir", $textos["PERMISO_CONDUCCION"], $datos_aspirante->permiso_conducir)
            )
        );

           //*Minicipio de Residencia
        $llave_primaria_municipio_aspirante_reside = $datos_terceros->codigo_iso_localidad."|".$datos_terceros->codigo_dane_departamento_localidad."|".$datos_terceros->codigo_dane_municipio_localidad."|".$datos_terceros->tipo_localidad."|".$datos_terceros->codigo_dane_localidad;

        $municipio_reside = SQL::obtenerValor("seleccion_localidades","SUBSTRING_INDEX(nombre,'|',1)","id='$llave_primaria_municipio_aspirante_reside'");


          //*Municipio del Arrendatario
        $llave_primaria_municipio_aspirante_arendatario = $datos_aspirante->codigo_iso_arrendatario."|".$datos_aspirante->codigo_dane_departamento_arrendatario."|".$datos_aspirante->codigo_dane_municipio_arrendatario;

        $municipio_arrendatario = SQL::obtenerValor("seleccion_municipios","SUBSTRING_INDEX(nombre,'|',1)","id='$llave_primaria_municipio_aspirante_arendatario'");


          //*Minicipio del Arrendatario
        $llave_primaria_municipio_aspirante_estadia = $datos_aspirante->codigo_iso_mayor_estadia."|".$datos_aspirante->codigo_dane_departamento_mayor_estadia."|".$datos_aspirante->codigo_dane_municipio_mayor_estadia;

        $municipio_estadia = SQL::obtenerValor("seleccion_municipios","SUBSTRING_INDEX(nombre,'|',1)","id='$llave_primaria_municipio_aspirante_estadia'");


        if($derecho_vivienda == 1){
            $derecho_vivienda = $textos["ARRENDADA"];
        }elseif($derecho_vivienda == 2){
            $derecho_vivienda = $textos["PROPIA"];
        }elseif($derecho_vivienda == 3){
            $derecho_vivienda = $textos["FAMILIAR"];
        }else{
            $derecho_vivienda = $textos["COMODATO"];
        }

        // Definicion de pestaña personal
        $formularios["PESTANA_UBICACION"] = array(
            array(
                HTML::mostrarDato("municipio_residencia", $textos["BARRIO_RESIDENCIA"], $municipio_reside)
            ),
            array(
                HTML::mostrarDato("direccion", $textos["DIRECCION"], $datos_terceros->direccion_principal),
                HTML::mostrarDato("telefono", $textos["TELEFONO"], $datos_terceros->telefono_principal),
                HTML::mostrarDato("celular", $textos["CELULAR"], $datos_terceros->celular),
                HTML::mostrarDato("celular2", $textos["CELULAR2"], $datos_terceros->celular2)
            ),
            array(
                HTML::mostrarDato("correo", $textos["CORREO_ELECTRONICO"], $datos_terceros->correo),
                HTML::mostrarDato("correo2", $textos["CORREO_ELECTRONICO2"], $datos_terceros->correo2)
            ),
            array(
                HTML::mostrarDato("fax", $textos["FAX"], $datos_terceros->fax),
                HTML::mostrarDato("sitio_web", $textos["SITIO_WEB"], $datos_terceros->sitio_web)
            ),
            array(
                HTML::mostrarDato("derecho_sobre_vivienda", $textos["DERECHO_VIVIENDA"], $derecho_vivienda),
                HTML::mostrarDato("canon_arrendo", $textos["CANON_ARRENDAMIENTO"], $datos_aspirante->canon_arrendo),
                HTML::mostrarDato("fecha_inicio_vivienda", $textos["FECHA_MUDANZA"], $datos_aspirante->fecha_inicio_vivienda)
            ),
            array(
                HTML::mostrarDato("nombre_arrendatario", $textos["NOMBRE_ARRENDADOR"], $datos_aspirante->nombre_arrendatario),
                HTML::mostrarDato("telefono_arrendatario", $textos["TELEFONO_ARRENDADOR"], $datos_aspirante->telefono_arrendatario),
                HTML::mostrarDato("municipio_arrendatario", $textos["MUNICIPIO_ARRENDADOR"], $municipio_arrendatario)
            ),
            array(
                HTML::mostrarDato("municipio_mayor_estadia", $textos["MUNICIPIO_MAYOR_ESTADIA"], $municipio_estadia)
            )
        );

        $profesion = SQL::obtenerValor("profesiones_oficios", "descripcion", "codigo_dane = '$datos_aspirante->codigo_dane_profesion'");
        $cargo     = SQL::obtenerValor("cargos", "nombre", "codigo = '$datos_aspirante->codigo_cargo'");
        $salud     = SQL::obtenerValor("entidades_parafiscales", "nombre", "codigo = '$datos_aspirante->codigo_entidad_salud'");
        $pension   = SQL::obtenerValor("entidades_parafiscales", "nombre", "codigo = '$datos_aspirante->codigo_entidad_pension'");
        $cesantias = SQL::obtenerValor("entidades_parafiscales", "nombre", "codigo = '$datos_aspirante->codigo_entidad_cesantias'");

        if($datos_aspirante->relacion_laboral == 1){
            $relacion_laboral = $textos["ASPIRANTE_LABORAR"];
        }elseif($datos_aspirante->relacion_laboral == 2){
            $relacion_laboral = $textos["LABORA_DIRECTAMENTE"];
        }elseif($datos_aspirante->relacion_laboral == 3){
            $relacion_laboral = $textos["LABORA_POR_CONTRATO"];
        }else{
            $relacion_laboral = $textos["PRESTACION_SERVICIOS"];
        }

         if($datos_aspirante->pensionado == 1){
            $pensionado = $textos["SI_PENSIONADO"];
        }else{
            $pensionado = $textos["NO_PENSIONADO"];
        }

            // Definicion de pestaña de la informacion profesional
         $formularios["PESTANA_PROFESIONAL"] = array(
            array(
                HTML::mostrarDato("profesion", $textos["PROFESION_OFICIO"], $profesion)
            ),
            array(
                HTML::mostrarDato("cargo", $textos["CARGO"],$cargo)
            ),
            array(
                HTML::mostrarDato("relacion_laboral", $textos["RELACION_LABORAL"], $relacion_laboral),
                HTML::mostrarDato("experiencia_laboral", $textos["EXPERIENCIA_LABORAL"], $datos_aspirante->experiencia_laboral),
                HTML::mostrarDato("aspiracion_salarial", $textos["ASPIRACION_SALARIAL"], $datos_aspirante->aspiracion_salarial),
                HTML::mostrarDato("fecha_ingreso", $textos["FECHA_INGRESO"], $datos_aspirante->fecha_ingreso)
            ),
            array(
                HTML::mostrarDato("pension", $textos["PENSIONADO"], $pensionado),
                HTML::mostrarDato("ingreso_pension", $textos["INGRESO_PENSION"], $datos_aspirante->ingreso_pension),
            ),
            array(
                HTML::mostrarDato("recomendacion_interna", $textos["RECOMENDACION_INTERNA"], $datos_aspirante->recomendacion_interna)
            ),
            array(
                HTML::mostrarDato("salud", $textos["EPS_EMPRESA"], $salud),
                HTML::mostrarDato("pension", $textos["PENSION_EMPRESA"], $pension),
                HTML::mostrarDato("cesantias", $textos["CESANTIAS_EMPRESA"], $cesantias)
            )
        );

        /**Obtener los datos de las tablas**/
        $consulta      = SQL::seleccionar(array("empresas_aspirante"),array("*"),"documento_identidad_aspirante = '$datos_aspirante->documento_identidad'");
        $alineacionem  = array("I","I","I","I","I","I","I","I","I","I","I","I","I");
        $itemEm        = array();

        if (SQL::filasDevueltas($consulta)) {
            while ($datosEm = SQL::filaEnObjeto($consulta)) {

               $actividad     = SQL::obtenerValor("actividades_economicas","descripcion","codigo_dian=$datosEm->codigo_actividad_economica");
               $departamento  = SQL::obtenerValor("departamentos_empresa", "nombre", "codigo = '$datosEm->codigo_departamento_empresa'");
               $cargo         = SQL::obtenerValor("cargos", "nombre", "codigo = '$datosEm->codigo_cargo'");
               $tipo_contrato = SQL::obtenerValor("tipos_contrato", "descripcion", "codigo = '$datosEm->codigo_tipo_contrato'");
               $motivo_retiro = SQL::obtenerValor("motivos_retiro","descripcion","codigo = '$datosEm->codigo_motivo_retiro'");

                if($datosEm->horario_laboral == 1){
                    $horario_laboral = $textos["DIURNO"];
                }elseif($datosEm->horario_laboral == 2){
                    $horario_laboral = $textos["NOCTURNO"];
                }else{
                    $horario_laboral = $textos["AMBAS"];
                }


                $itemEm[] = array(
                    0,
                    $datosEm->nombre,
                    $actividad,
                    $datosEm->direccion,
                    $datosEm->telefono,
                    $cargo,
                    $departamento,
                    $datosEm->jefe_inmediato,
                    $datosEm->fecha_inicial,
                    $datosEm->fecha_final,
                    $horario_laboral,
                    $tipo_contrato,
                    $motivo_retiro,
                    $datosEm->logros_obtenidos
                );
            }
        }

       $tablaem = HTML::generarTabla(
            array("id","EMPRESA","ACTIVIDAD_ECONOMICA_EMPRESA","DIRECCION_EMPRESA_TABLA","TELEFONO","CARGO","DEPARTAMENTO_EMPRESA","JEFE_INMEDIATO","FECHA_INICIAL_EMPRESA_TABLA","FECHA_FINAL_EMPRESA_TABLA","HORARIO_EMPRESA","FORMA_CONTRATO","ID_MOTIVO_RETIRO","LOGROS_OBTENIDOS"),
            $itemEm,
            $alineacionem,
            "tablaempresa",
            false
        );

        $formularios["PESTANA_LABORAL"] = array(array($tablaem));

        /**Informacion Academica**/

        $vistaConsultaAcademica = "estudios_aspirante";
        $alineacionAcademica    = array("I","I","I","I","I","I","I","I");

        $condicionAcademica     = "documento_identidad_aspirante = '$datos_aspirante->documento_identidad'";
        $consultaAcademica      = SQL::seleccionar(array($vistaConsultaAcademica),array("*"),$condicionAcademica);
        $i=0;
        $itemEstudios           = array();

        $columnasAcademica      = array("id","ID_ESCOLARIDAD","TITULO","FECHA_INICIO","FECHA_FIN","INTENSIDAD_HORARIA","HORARIO","INSTITUCION","ID_MUNICIPIO");

        while($datosEstudios   = SQL::filaEnObjeto($consultaAcademica)){

            if($datosEstudios->horario == 1){
                $horarioEstudios = $textos["DIURNO"];
            }elseif($datosEstudios->horario == 2){
                $horarioEstudios = $textos["NOCTURNO"];
            }else{
                $horarioEstudios = $textos["SABATINO"];
            }

            $escolaridad = SQL::obtenerValor("escolaridad","descripcion","codigo = '$datosEstudios->codigo_escolaridad'");

            $llave_primaria_municipio_escolaridad = $datosEstudios->codigo_iso_estudios."|".$datosEstudios->codigo_dane_departamento_estudios."|".$datosEstudios->codigo_dane_municipio_estudios;

            $municipio_escolaridad = SQL::obtenerValor("seleccion_municipios","SUBSTRING_INDEX(nombre,'|',1)","id ='$llave_primaria_municipio_escolaridad'");


            $itemEstudios[] = array(
                $i,
                $escolaridad,
                $datosEstudios->titulo,
                $datosEstudios->fecha_inicio,
                $datosEstudios->fecha_fin,
                $datosEstudios->intensidad_horaria,
                $horarioEstudios,
                $datosEstudios->institucion,
                $municipio_escolaridad
            );
            $i++;
        }

        $tablaAcademica = HTML::generarTabla($columnasAcademica, $itemEstudios, $alineacionAcademica, "tablaEstudios", false);

        $formularios["PESTANA_ACADEMICA"] = array(array($tablaAcademica));

         /**Informacion sobre los idiomas**/

        $vistaConsultaIdiomas = "idiomas_aspirante";
        $alineacionIdiomas    = array("I","I","I","I");
        $columnasIdiomas      = array("id","NOMBRE","LECTURA","ESCRITURA","HABLA");
        $condicionIdiomas     = "documento_identidad_aspirante = '".$datos_aspirante->documento_identidad."'";
        $consultaIdiomas      = SQL::seleccionar(array($vistaConsultaIdiomas),array("*"), $condicionIdiomas);
        $i                    = 0;
        $itemIdiomas          = array();



        while($datosIdiomas   = SQL::filaEnObjeto($consultaIdiomas)){

           $idioma      = SQL::obtenerValor("idiomas","descripcion","codigo = '$datosIdiomas->codigo_idioma'");
           $valorIdioma = array("1" => $textos["NO_APLICA"],"2" => $textos["REGULAR"],"3" => $textos["BIEN"],"4" => $textos["EXCELENTE"]);

           $habla   = $valorIdioma[$datosIdiomas->habla];
           $lee     = $valorIdioma[$datosIdiomas->lectura];
           $escribe = $valorIdioma[$datosIdiomas->escritura];

            $removerIdiomas = HTML::boton("botonRemoverIdiomas", "", "removerItems(this);", "eliminar");
            $itemIdiomas[]  = array(
                $i,
                $idioma,
                $lee,
                $escribe,
                $habla
            );
            $i++;
        }

        $tablaIdiomas = HTML::generarTabla($columnasIdiomas, $itemIdiomas, $alineacionIdiomas, "tablaIdiomas",false);

        $formularios["PESTANA_IDIOMAS"] = array(array($tablaIdiomas));

        /**Informacion de pestaña manejo de sistema  **/

        if($datos_aspirante -> hojas_calculo == 1){
            $hojas_calculo = $textos["NO_SABE"];
        }elseif($datos_aspirante -> hojas_calculo == 2){
            $hojas_calculo = $textos["MUY_BIEN"];
        }elseif($datos_aspirante -> hojas_calculo == 3){
            $hojas_calculo = $textos["BIEN"];
        }else{
            $hojas_calculo = $textos["REGULAR"];
        }

        if($datos_aspirante -> procesadores_texto == 1){
            $procesadores_texto = $textos["NO_SABE"];
        }elseif($datos_aspirante -> procesadores_texto == 2){
            $procesadores_texto = $textos["MUY_BIEN"];
        }elseif($datos_aspirante -> procesadores_texto == 3){
            $procesadores_texto = $textos["BIEN"];
        }else{
            $procesadores_texto = $textos["REGULAR"];
        }

        if($datos_aspirante -> diseno_diapositivas == 1){
            $diseno_diapositivas = $textos["NO_SABE"];
        }elseif($datos_aspirante -> diseno_diapositivas == 2){
            $diseno_diapositivas = $textos["MUY_BIEN"];
        }elseif($datos_aspirante -> diseno_diapositivas == 3){
            $diseno_diapositivas = $textos["BIEN"];
        }else{
            $diseno_diapositivas = $textos["REGULAR"];
        }

        $formularios["PESTANA_SISTEMAS"] = array(
            array(
                HTML::mostrarDato("hojas_calculo_nombre", $textos["HOJAS_CALCULO"], $hojas_calculo),
                HTML::mostrarDato("procesadores_texto", $textos["PROCESADOR_TEXTO"], $procesadores_texto),
                HTML::mostrarDato("diseno_diapositivas", $textos["DIAPOSITIVAS"], $diseno_diapositivas)
            ),
            array(
                HTML::mostrarDato("digitador", $textos["DIGITADOR_SISTEMAS"], $datos_aspirante -> digitador)
            ),
            array(
                HTML::mostrarDato("programacion", $textos["PROGRAMADOR_SISTEMAS"], $datos_aspirante -> programacion)
            ),
        );

        // Informacion de conyugue del aspirante

        $vistaConsulta   = "conyugue_aspirante";
        $columnas        = SQL::obtenerColumnas($vistaConsulta);
        $consulta        = SQL::seleccionar(array($vistaConsulta), $columnas, "documento_identidad_aspirante = '$url_id'");

        $empresa_conyugue        = "";
        $telefono_conyugue       = "";
        $celular_conyugue        = "";
        $documento_conyugue      = "";
        $nombre_completo         = "";
        $profesion_conyugue      = "";
        $cargo_conyugue          = "";
        $tipo_documento_conyugue = "";

        if(($datos_aspirante->estado_civil == '2' || $datos_aspirante->estado_civil == '3') && SQL::filasDevueltas($consulta)>0){
            $datos_conyugue  = SQL::filaEnObjeto($consulta);
            $tipo_documento_conyugue  = SQL::obtenerValor("tipos_documento_identidad","descripcion","codigo=$datos_conyugue->codigo_tipo_documento");
            $nombre_completo          = $datos_conyugue->primer_nombre." ".$datos_conyugue->segundo_nombre." ".$datos_conyugue->primer_apellido." ".$datos_conyugue->segundo_apellido;
            $profesion_conyugue       = SQL::obtenerValor("profesiones_oficios", "descripcion", "codigo_dane = '$datos_conyugue->codigo_dane_profesion'");
            $cargo_conyugue           = SQL::obtenerValor("cargos", "nombre", "codigo = '$datos_conyugue->codigo_cargo'");
            $empresa_conyugue         = $datos_conyugue->empresa;
            $telefono_conyugue        = $datos_conyugue->telefono;
            $celular_conyugue         = $datos_conyugue->celular;
            $documento_conyugue       =  $datos_conyugue->documento_identidad;
        }

        $formularios["PESTANA_CONYUGUE"] = array(
            array(
                HTML::mostrarDato("tipo_documento_conyugue", $textos["TIPO_DOCUMENTO"], $tipo_documento_conyugue),
                HTML::mostrarDato("numero_documento_conyugue", $textos["NUMERO_DOCUMENTO"],$documento_conyugue)
            ),
            array(
                HTML::mostrarDato("nombre_conyugue", $textos["NOMBRE"], $nombre_completo)
            ),
            array(
                HTML::mostrarDato("profesion_conyugue", $textos["PROFESION_OFICIO"],$profesion_conyugue),
                HTML::mostrarDato("empresa", $textos["EMPRESA_CONYUGUE"], $empresa_conyugue)
            ),
            array(
                HTML::mostrarDato("cargo", $textos["CARGO"], $cargo_conyugue)
            ),
            array(
                HTML::mostrarDato("telefono_conyugue", $textos["TELEFONO"], $telefono_conyugue),
                HTML::mostrarDato("celular_conyugue", $textos["CELULAR_CONYUGUE"], $celular_conyugue)
            )
        );

       /**Informacion de familiares**/

        $vistaConsultaFamilia = "familia_aspirante";
        $alineacionFamilia    = array("I","I","I","I","I","I","I","I","C");
        $columnasFamilia      = array("id","ID_TIPO_DOCUMENTO","DOCUMENTO_IDENTIDAD","NOMBRE_COMPLETO","ID_PROFESION","PARENTESCO","FECHA_NACIMIENTO","EDAD","GENERO","DEPENDE_ECONOMICAMENTE");
        $condicionFamilia     = "documento_identidad_aspirante = '$datos_aspirante->documento_identidad'";
        $consultaFamilia      = SQL::seleccionar(array($vistaConsultaFamilia),array("*"), $condicionFamilia);
        $i=0;
        $itemFamilia            = array();
        while($datosFamilia = SQL::filaEnObjeto($consultaFamilia)){
            if($datosFamilia->parentesco == 1){
                $pariente = $textos["HIJO"];
            }
            elseif($datosFamilia->parentesco == 2){
                $pariente = $textos["MADRE"];
            }
            elseif($datosFamilia->parentesco == 3){
                $pariente = $textos["PADRE"];
            }
            elseif($datosFamilia->parentesco == 4){
                $pariente = $textos["HERMANO"];
            }
            elseif($datosFamilia->parentesco == 5){
                $pariente = $textos["ABUELO"];
            }
            else{
                $pariente = $textos["OTRO"];
            }

            if($datosFamilia->genero == "M"){
                $generoFamilia = $textos["GENERO_MASCULINO"];
            }
            else{
                $generoFamilia = $textos["GENERO_FEMENINO"];
            }

            if($datosFamilia->depende_economicamente == 0){
                $dependenciaFamilia = $textos["DEPENDENCIA_ECONOMICA_NO"];
            }
            else{
                $dependenciaFamilia = $textos["DEPENDENCIA_ECONOMICA_SI"];
            }

            list($anio,$mes,$dia) = explode("-",$datosFamilia->fecha_nacimiento);
            $anio_dif = date("Y") - $anio;
            $mes_dif = date("m")  - $mes;
            $dia_dif = date("d")  - $dia;
            if ($dia_dif < 0 || $mes_dif < 0){
            $anio_dif--;
            }

            $profesionFamilia       = SQL::obtenerValor("profesiones_oficios","descripcion","codigo_dane = '$datosFamilia->codigo_dane_profesion'");
            $tipo_documentoFamilia  = SQL::obtenerValor("tipos_documento_identidad","descripcion","codigo = '$datosFamilia->codigo_tipo_documento'");

            $itemFamilia[]  = array(
                $i,
                $tipo_documentoFamilia,
                $datosFamilia->documento_identidad,
                $datosFamilia->nombre_completo,
                $profesionFamilia,
                $pariente,
                $datosFamilia->fecha_nacimiento,
                $anio_dif,
                $generoFamilia,
                $dependenciaFamilia
            );
            $i++;
        }

       $tablaFamilia = HTML::generarTabla($columnasFamilia, $itemFamilia, $alineacionFamilia, "tablaFamilia",false);

       $formularios["PESTANA_FAMILIAR"] = array(
           array(
                $tablaFamilia
            )
        );

         // Definicion de pestaña de ubicacion del aspirante

        if($datos_aspirante->anteojos == 0){
            $anteojos = $textos["ANTEOJOS_NO"];
        }else{
            $anteojos = $textos["ANTEOJOS_SI"];
        }


        /**Informacion de de aficiones**/

        $vistaConsultaAficiones = "aficiones_aspirante";
        $alineacionAficiones    = array("I");
        $columnasAficiones      = array("id","AFICION");
        $condicionAficiones     = "documento_identidad_aspirante = '$datos_aspirante->documento_identidad'";
        $consultaAficiones      = SQL::seleccionar(array($vistaConsultaAficiones ),array("*"), $condicionAficiones );
        $i                      = 0;
        $itemAficiones          = array();

        while($datosAficiones = SQL::filaEnObjeto($consultaAficiones )){
            $descripcion_aficiones = SQL::obtenerValor("aficiones","descripcion","codigo = '$datosAficiones->codigo_aficion '");

            $itemAficiones[]    = array(
                $i,
                $descripcion_aficiones
            );
            $i++;
        }

       $tablaAficiones  = HTML::generarTabla($columnasAficiones, $itemAficiones, $alineacionAficiones,"tablaAficiones",false);

       /**Informacion de de deportes**/

        $vistaConsultaDeporte = "deportes_aspirante";
        $alineacionDeporte    = array("I");
        $columnasDeporte      = array("id","DEPORTE");
        $condicionDeporte     = "documento_identidad_aspirante = '$datos_aspirante->documento_identidad'";
        $consultaDeporte      = SQL::seleccionar(array($vistaConsultaDeporte ),array("*"), $condicionDeporte );
        $i                    = 0;
        $itemDeporte          = array();

        while($datosDeporte = SQL::filaEnObjeto($consultaDeporte )){
            $descripcion_deporte = SQL::obtenerValor("deportes","descripcion","codigo = '$datosDeporte->codigo_deporte '");

            $itemDeporte[]  = array(
                $i,
                $descripcion_deporte

            );
            $i++;
        }

        $tablaDeporte  = HTML::generarTabla($columnasDeporte, $itemDeporte, $alineacionDeporte,"tablaDeporte",false);

        $formularios["PESTANA_PERSONAL"] = array(
            array(
                HTML::mostrardato("estatura", $textos["ESTATURA"],$datos_aspirante->estatura),
                HTML::mostrarDato("peso", $textos["PESO"], $datos_aspirante->peso)
            ),
            array(
                HTML::mostrarDato("anteojos", $textos["ANTEOJOS"], $anteojos)
            ),
            array(
                HTML::mostrarDato("talla_camisa", $textos["TALLA_CAMISA"], $datos_aspirante->talla_camisa),
                HTML::mostrarDato("talla_pantalon", $textos["TALLA_PANTALON"], $datos_aspirante->talla_pantalon),
                HTML::mostrarDato("talla_calzado", $textos["TALLA_CALZADO"], $datos_aspirante->talla_calzado)
            ),
            array(
                 $tablaAficiones,
                 $tablaDeporte
            )
        );

        // Informacion de pestaña laboral


        $vistaConsultaVehiculo = "vehiculo_aspirante";
        $alineacionVehiculo    = array("I","I","I","I","C");
        $columnasVehiculo      = array("id","TIPO","MODELO","MARCA","MATRICULA","PIGNORADO");
        $condicionVehiculo     = "documento_identidad_aspirante = '$datos_aspirante->documento_identidad'";
        $consultaVehiculo      = SQL::seleccionar(array($vistaConsultaVehiculo),array("*"), $condicionVehiculo);
        $i                     = 0;
        $itemVehiculo          = array();

        while($datosVehiculo = SQL::filaEnObjeto($consultaVehiculo)){

            if($datosVehiculo->tipo == 1){
                $tipoVehiculo = $textos["MOTOCICLETA"];
            }elseif($datosVehiculo->tipo == 2){
                $tipoVehiculo = $textos["VEHICULO_PARTICULAR"];
            }elseif($datosVehiculo->tipo == 3){
                $tipoVehiculo = $textos["VEHICULO_PUBLICO"];
            }elseif($datosVehiculo->tipo == 4){
                $tipoVehiculo = $textos["CAMION_PEQUENO"];
            }elseif($datosVehiculo->tipo == 5){
                $tipoVehiculo = $textos["CAMION_GRANDE"];
            }else{
                $tipoVehiculo = $textos["BUS_COLECTIVO_BUSETA"];
            }

            if($datosVehiculo->pignorado == 0){
                $pignoradoVehiculo = $textos["NO_PIGNORADO"];
            }else{
                $pignoradoVehiculo = $textos["SI_PIGNORADO"];
            }

            $removerVehiculo = HTML::boton("botonRemoverVehiculo", "", "removerItems(this)", "eliminar");
            $itemVehiculo[]  = array(
                $i,
                $tipoVehiculo,
                $datosVehiculo->modelo,
                $datosVehiculo->marca,
                $datosVehiculo->matricula,
                $pignoradoVehiculo
            );
            $i++;
        }

        $tablaVehiculo         = HTML::generarTabla($columnasVehiculo, $itemVehiculo, $alineacionVehiculo, "tablaVehiculo",false);
        $vistaConsultaVivienda = "vivienda_aspirante";
        $alineacionVivienda    = array("I","C","I","I","I");
        $columnasVivienda      = array("id","TIPO","HIPOTECA","DIRECCION","BARRIO","TELEFONO");
        $condicionVivienda     = "documento_identidad_aspirante = '$datos_aspirante->documento_identidad'";
        $consultaVivienda      = SQL::seleccionar(array($vistaConsultaVivienda),array("*"), $condicionVivienda);
        $i                     = 0;
        $itemVivienda          = array();

        while($datosVivienda = SQL::filaEnObjeto($consultaVivienda)){

            if($datosVivienda->tipo == 1){
                $tipoVivienda = $textos["CASA"];
            }elseif($datosVivienda->tipo ==2){
                $tipoVivienda = $textos["APARTAMENTO"];
            }elseif($datosVivienda->tipo == 3){
                $tipoVivienda = $textos["MEJORA"];
            }elseif($datosVivienda->tipo == 4){
                $tipoVivienda = $textos["LOTE"];
            }else{
                $tipoVivienda = $textos["EDIFICIO"];
            }

            if($datosVivienda->hipoteca == 0){
                $hipotecaVivienda = $textos["NO_HIPOTECA"];
            }else{
                $hipotecaVivienda = $textos["SI_HIPOTECA"];
            }

            $llave_primaria_municipio_barrio = $datosVivienda->codigo_iso_barrio."|".$datosVivienda->codigo_dane_departamento_barrio."|".$datosVivienda->codigo_dane_municipio_barrio."|".$datosVivienda->tipo_barrio."|".$datosVivienda->codigo_dane_localidad_barrio;
            $municipioBarrio = SQL::obtenerValor("seleccion_localidades","SUBSTRING_INDEX(nombre,'|',1)","id = '$llave_primaria_municipio_barrio'");

            $itemVivienda[]    = array(
                $i,
                $tipoVivienda,
                $hipotecaVivienda,
                $datosVivienda->direccion,
                $municipioBarrio,
                $datosVivienda->telefono
            );
            $i++;
        }

        $tablaVivienda = HTML::generarTabla($columnasVivienda, $itemVivienda, $alineacionVivienda, "tablaVivienda",false);

         // Definicion de pestaña laboral
        $formularios["PESTANA_VIVIENDA"] = array(
            array(
                 $tablaVivienda
            )
        );

        // Definicion de pestaña laboral
        $formularios["PESTANA_VEHICULO"] = array(
            array(
                 $tablaVehiculo
            )
        );

        //*************************//

        $vistaConsultaReferencia = "referencias_aspirante";
        $alineacionReferencia    = array("I","I","I","I");
        $columnasReferencia      = array("id","NOMBRE","ID_PROFESION","DIRECCION","TELEFONO");
        $condicionReferencia     = "documento_identidad_aspirante = '$datos_aspirante->documento_identidad'";
        $consultaReferencia      = SQL::seleccionar(array($vistaConsultaReferencia),array("*"), $condicionReferencia);
        $i                       = 0;
        $itemReferencia          = array();

        while($datosReferencia   = SQL::filaEnObjeto($consultaReferencia)){
            $profesionReferencia = SQL::obtenerValor("profesiones_oficios","descripcion","codigo_dane = '$datosReferencia->codigo_dane_profesion'");
            $removerReferencia   = HTML::boton("botonRemoverReferencia", "", "removerItems(this)", "eliminar");
            $itemReferencia[]    = array(
                $i,
                $datosReferencia->nombre,
                $profesionReferencia,
                $datosReferencia->direccion,
                $datosReferencia->telefono
            );
            $i++;
        }

        $tablaReferencia = HTML::generarTabla($columnasReferencia, $itemReferencia, $alineacionReferencia, "tablaReferencia",false);

         // Definicion de pestaña laboral
        $formularios["PESTANA_REFERENCIAS"] = array(
            array(
                $tablaReferencia
            )
        );

        // Definicion de botones
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "eliminarItem('$url_id');", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios,$botones);
    }
    // Enviar datos para la generacion del formulario al script que originï¿½ la peticiï¿½n
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);
}elseif(!empty($forma_procesar)) {

    // Eliminar servicios relacionados con el proveedor
    $consulta   = SQL::eliminar("conyugue_aspirante", "documento_identidad_aspirante = '".$forma_id."'");
    $consulta   = SQL::eliminar("empresas_aspirante", "documento_identidad_aspirante = '".$forma_id."'");
    $consulta   = SQL::eliminar("estudios_aspirante", "documento_identidad_aspirante = '".$forma_id."'");
    $consulta   = SQL::eliminar("idiomas_aspirante", "documento_identidad_aspirante = '".$forma_id."'");
    $consulta   = SQL::eliminar("familia_aspirante", "documento_identidad_aspirante = '".$forma_id."'");
    $consulta   = SQL::eliminar("vivienda_aspirante", "documento_identidad_aspirante = '".$forma_id."'");
    $consulta   = SQL::eliminar("vehiculo_aspirante", "documento_identidad_aspirante = '".$forma_id."'");
    $consulta   = SQL::eliminar("referencias_aspirante", "documento_identidad_aspirante = '".$forma_id."'");
    $consulta   = SQL::eliminar("deportes_aspirante", "documento_identidad_aspirante = '".$forma_id."'");
    $consulta   = SQL::eliminar("aficiones_aspirante", "documento_identidad_aspirante = '".$forma_id."'");

    if ($consulta) {
        $error   = false;
        $mensaje = $textos["ITEM_ELIMINADO"];
        // Eliminar el tercero relacionado con el aspirante
        $consulta = SQL::eliminar("aspirantes", "documento_identidad = '".$forma_id."'");
        $consulta = SQL::eliminar("terceros", "documento_identidad = '".$forma_id."'");
    } else {
        $error   = true;
        $mensaje = $textos["ERROR_ELIMINAR_ITEM"];
    }

    // Enviar datos con la respuesta del proceso al script que origino la peticion
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

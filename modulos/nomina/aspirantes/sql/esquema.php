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
* de APTITUD PARA UN PROPÓITO DETERMINADO. Consulte los detalles de
* la Licencia Pública General GNU para obtener una información más
* detallada.
*
* Debería haber recibido una copia de la Licencia Pública General GNU
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
**/

$borrarSiempre = false;

//Definición de tablas
$tablas["aspirantes"] = array(
    "documento_identidad"                    => "VARCHAR(12) NOT NULL COMMENT 'Codigo que identifica el tercero'",
    "tipo_sangre"                            => "CHAR(5) NULL COMMENT 'Tipo de sangre del aspirante ejemplo: B+'",
    "codigo_cargo"                           => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo interno para la base de datos del cargo que desempeña'",
    "fecha_ingreso"                          => "DATE NOT NULL COMMENT 'Fecha de inicio de labores en la empresa'",
    "fecha_inicio_vivienda"                  => "DATE NOT NULL COMMENT 'Fecha que se mudo a la casa'",
    "derecho_sobre_vivienda"                 => "ENUM('1','2','3','4') NOT NULL DEFAULT '1' COMMENT '1->arrendamiento 2->propiedad 3->familiar 4->comodato'",
    "relacion_laboral"                       => "ENUM('1','2','3','4') NOT NULL DEFAULT '1' COMMENT '1->Aspirante 2->Directamnete 3->Contrato 4->Prestacion de servicos'",
    "canon_arrendo"                          => "DOUBLE(11,2) COMMENT 'Valor del canon de arrendamiento'",
    "nombre_arrendatario"                    => "VARCHAR(255) NOT NULL COMMENT 'Nombre del arrendador de la vivienda'",
    // Llave municipio arrendatario
    "codigo_iso_arrendatario"                => "VARCHAR(2) NOT NULL COMMENT 'Codigo ISO en la tabla de municipios'",
    "codigo_dane_departamento_arrendatario"  => "VARCHAR(2) NOT NULL COMMENT 'Codigo dane departamento en la tabla de municipios'",
    "codigo_dane_municipio_arrendatario"     => "VARCHAR(3) NOT NULL COMMENT 'Codigo dane municipio en la tabla de municipios'",
    // Fin llave
    "telefono_arrendatario"                  => "VARCHAR(15) NULL COMMENT 'Numero de telefono de la empresa'",
    // Llave municipio mayor estadia
    "codigo_iso_mayor_estadia"               => "VARCHAR(2) NOT NULL COMMENT 'Codigo ISO en la tabla de municipios'",
    "codigo_dane_departamento_mayor_estadia" => "VARCHAR(2) NOT NULL COMMENT 'Codigo dane departamento en la tabla de municipios'",
    "codigo_dane_municipio_mayor_estadia"    => "VARCHAR(3) NOT NULL COMMENT 'Codigo dane municipio en la tabla de municipios'",
    // Fin llave
    "codigo_dane_profesion"                  => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo interno que identifica la profesion del aspirante'",
    "aspiracion_salarial"                    => "DOUBLE(11,2) NULL COMMENT 'Aspiracion salarial de la persona'",
    "pensionado"                             => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT '0->No 1->Si'",
    "ingreso_pension"                        => "DOUBLE(11,2) COMMENT 'Valor del ingeso por pension'",
    "experiencia_laboral"                    => "VARCHAR(20) NULL COMMENT 'Experiencia laboral que posee la persona'",
    "recomendacion_interna"                  => "VARCHAR(100) COMMENT 'Nombre de la persona que lo recomineda y trabaja en la empresa'",
    "estatura"                               => "INT(3) COMMENT 'estatura en centimetros de la persona'",
    "peso"                                   => "INT(3) COMMENT 'peso en kilogramos de la persona'",
    "talla_camisa"                           => "VARCHAR(5) COMMENT 'talla de la camisa'",
    "anteojos"                               => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT '0->No 1->Si'",
    "talla_pantalon"                         => "VARCHAR(5) COMMENT 'talla del pantalon'",
    "talla_calzado"                          => "VARCHAR(5) COMMENT 'talla del calzado'",
    "digitador"                              => "VARCHAR(255) COMMENT 'Descripcion de algunos programa que conoce'",
    "programacion"                           => "VARCHAR(255) COMMENT 'Descripcion de lenguajes de programacion que conoce'",
    "hojas_calculo"                          => "ENUM('1','2','3','4') NOT NULL DEFAULT '1' COMMENT '1->No sabe 2->Regular 3->Bien 4->Muy bien'",
    "procesadores_texto"                     => "ENUM('1','2','3','4') NOT NULL DEFAULT '1' COMMENT '1->No sabe 2->Regular 3->Bien 4->Muy bien'",
    "diseno_diapositivas"                    => "ENUM('1','2','3','4') NOT NULL DEFAULT '1' COMMENT '1->No sabe 2->Regular 3->Bien 4->Muy bien'",
    // Llave municipio nacimiento
    "codigo_iso_nacimiento"                  => "VARCHAR(2) NOT NULL COMMENT 'Codigo ISO en la tabla de municipios'",
    "codigo_dane_departamento_nacimiento"    => "VARCHAR(2) NOT NULL COMMENT 'Codigo dane departamento en la tabla de municipios'",
    "codigo_dane_municipio_nacimiento"       => "VARCHAR(3) NOT NULL COMMENT 'Codigo dane municipio en la tabla de municipios'",
    // Fin llave
    "estado_civil"                           => "ENUM('1','2','3','4','5') NOT NULL DEFAULT '1' COMMENT '1->soltero(a) 2->casado(a) 3->union libre 4->divorciado(a) 5->viudo(a)'",
    "clase_libreta_militar"                  => "ENUM('1','2','3') NOT NULL DEFAULT '1' COMMENT '1->No tiene 2->Primera 3->Segunda'",
    "libreta_militar"                        => "INT(15) NULL COMMENT 'Numero de la libreta militar'",
    "distrito_militar"                       => "INT(3) NULL COMMENT 'Numero del distrito militar'",
    "permiso_conducir"                       => "INT(15) NULL COMMENT 'Numero de la licencia de conduccion'",
    "categoria_permiso_conducir"             => "ENUM('1','2','3','4','5','6','7') NOT NULL DEFAULT '1' COMMENT '1->No tiene 2->primera- 3->segunda 4->tercera 5->cuarta 6->quinta 7->sexta'",
    "codigo_entidad_salud"                   => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo interno para la base de datos de entidades parafiscales'",
    "codigo_entidad_pension"                 => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo interno para la base de datos de entidades parafiscales'",
    "codigo_entidad_cesantias"               => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo interno para la base de datos de entidades parafiscales'"
);

$tablas["empresas_aspirante"] = array(
    "documento_identidad_aspirante"      => "VARCHAR(12) NOT NULL COMMENT 'Codigo que identifica el tercero'",
    "consecutivo"                        => "INT(8) NOT NULL COMMENT 'Consecutivo interno para la base de datos'",
    "nombre"                             => "VARCHAR(255) NOT NULL COMMENT 'Nombre de la empresa'",
    "codigo_iso_actividad"               => "VARCHAR(2) NOT NULL COMMENT 'Codigo ISO en la tabla de actividades econimicas'",
    "codigo_dane_departamento_actividad" => "VARCHAR(2) NOT NULL COMMENT 'Codigo dane departamento en la tabla de actividades econimicas'",
    "codigo_dane_municipio_actividad"    => "VARCHAR(3) NOT NULL COMMENT 'Codigo dane municipio en la tabla de actividades econimicas'",
    "codigo_dian_actividad"              => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo DIAN en la tabla de actividades econimicas'",
    "codigo_actividad_economica"         => "INT(5) UNSIGNED ZEROFILL NULL COMMENT'Codigo actividad municipio en la tabla de actividades econimicas'",
    "direccion"                          => "VARCHAR(50) NULL COMMENT 'Direccion ddonde esta ubicada la empresa'",
    "telefono"                           => "VARCHAR(15) NULL COMMENT 'Numero de telefono de la empresa'",
    "codigo_departamento_empresa"        => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo interno para la base de datos del departamento interno'",
    "codigo_cargo"                       => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo interno para la base de datos del cargo que desempeña'",
    "jefe_inmediato"                     => "VARCHAR(50) NULL COMMENT 'jefe inmediato en la empresa donde laboro'",
    "fecha_inicial"                      => "DATE NOT NULL COMMENT 'Fecha de inicio de labores en la empresa'",
    "fecha_final"                        => "DATE NULL COMMENT 'Fecha que termino labores en la empresa'",
    "horario_laboral"                    => "ENUM('1','2','3') NOT NULL DEFAULT '1' COMMENT ' 1->Diurno 2->Nocturno 3->Ambos'",
    "codigo_tipo_contrato"               => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id de la tabla de tipos de contrato'",
    "codigo_motivo_retiro"               => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo interno para la base de datos del motivo de retiro'",
    "logros_obtenidos"                   => "MEDIUMTEXT COMMENT 'Descripcion de los logros optenidos en la empresa'"
);

$tablas["conyugue_aspirante"] = array(
    "documento_identidad_aspirante" => "VARCHAR(12) NOT NULL COMMENT 'Codigo que identifica el tercero'",
    "codigo_tipo_documento"         => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo interno para la base de datos del tipo de documento de identificacion'",
    "documento_identidad"           => "VARCHAR(12) NULL COMMENT 'Numero del documento de identidad'",
    "primer_nombre"                 => "VARCHAR(20) NULL COMMENT 'Primer nombre de la persona'",
    "segundo_nombre"                => "VARCHAR(20) NULL COMMENT 'Segundo nombre de la persona'",
    "primer_apellido"               => "VARCHAR(20) NULL COMMENT 'Primer apellido de la persona'",
    "segundo_apellido"              => "VARCHAR(20) NULL COMMENT 'Segundo apellido de la persona'",
    "telefono"                      => "VARCHAR(20) NULL COMMENT 'Numero de telefono de la oficina de la persona'",
    "codigo_dane_profesion"         => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo interno para la base de datos del departamento interno'",
    "codigo_cargo"                  => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo interno para la base de datos del cargo que desempeña la persona'",
    "empresa"                       => "VARCHAR(70) NULL COMMENT 'Nombre de la empresa donde labora la persona'",
    "celular"                       => "VARCHAR(20) NULL COMMENT 'Numero del telefono celular de la persona'"

);

$tablas["familia_aspirante"] = array(
    "documento_identidad_aspirante" => "VARCHAR(12) NOT NULL COMMENT 'Codigo que identifica el tercero'",
    "consecutivo"                   => "INT(8) NOT NULL COMMENT 'Consecutivo interno para la base de datos'",
    "codigo_tipo_documento"         => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo interno para la base de datos del tipo de documento de identificacion'",
    "documento_identidad"           => "VARCHAR(12) NULL COMMENT 'Numero del documento de identidad'",
    "nombre_completo"               => "VARCHAR(255) NOT NULL COMMENT 'Nombre del familiar del aspirante'",
    "codigo_dane_profesion"         => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo interno que identifica la profesion del aspirante'",
    "parentesco"                    => "ENUM('1','2','3','4','5','6') NOT NULL DEFAULT '6' COMMENT '1->Hijo(a) 2->Madre 3->Padre 4->hermano(a) 5->abuelo(a) 6->otro'",
    "fecha_nacimiento"              => "DATE NOT NULL COMMENT 'Fecha de nacimiento'",
    "genero"                        => "ENUM('M','F') NOT NULL DEFAULT 'M' COMMENT 'M->Masculino F->Femenino'",
    "depende_economicamente"        => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT '0->No 1->Si'"
);

$tablas["referencias_aspirante"] = array(
    "documento_identidad_aspirante" => "VARCHAR(12) NOT NULL COMMENT 'Codigo que identifica el tercero'",
    "consecutivo"                   => "INT(8) NOT NULL COMMENT 'Consecutivo interno para la base de datos'",
    "nombre"                        => "VARCHAR(100) NOT NULL COMMENT 'Nombre de la presona que hace la recomendacion'",
    "codigo_dane_profesion"         => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo interno que identifica la profesion de la persona que hace la referrencia'",
    "direccion"                     => "VARCHAR(50) NULL COMMENT 'Direccion de la persona quien hace la referencia'",
    "telefono"                      => "VARCHAR(20) NULL COMMENT 'Numero de telefono de la persona quien hace la referencia'"

);

$tablas["vehiculo_aspirante"] = array(
    "documento_identidad_aspirante" => "VARCHAR(12) NOT NULL COMMENT 'Codigo que identifica el tercero'",
    "consecutivo"                   => "INT(8) NOT NULL COMMENT 'Consecutivo interno para la base de datos'",
    "tipo"                          => "ENUM('1','2','3','4','5','6') NOT NULL DEFAULT '1' COMMENT '1->Motocicleta 2->Particular 3->Servicio publico 4->carga pequeño 5->carga grande 6->Bus Buseta'",
    "matricula"                     => "VARCHAR(20) NOT NULL COMMENT 'Matricula del vehiculo'",
    "modelo"                        => "VARCHAR(50) NULL COMMENT 'Modelo del vehiculo'",
    "marca"                         => "VARCHAR(20) NULL COMMENT 'Marca del vehiculo'",
    "pignorado"                     => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT '0->Si 1->No'"

);

$tablas["vivienda_aspirante"] = array(
    "documento_identidad_aspirante"     => "VARCHAR(12) NOT NULL COMMENT 'Codigo que identifica el tercero'",
    "consecutivo"                       => "INT(8) NOT NULL COMMENT 'Consecutivo interno para la base de datos'",
    "tipo"                              => "ENUM('1','2','3','4','5') NOT NULL DEFAULT '1' COMMENT '1->Casa 2->Apto 3->Mejora 4->Lote 5->Edificio'",
    "hipoteca"                          => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT '0->No 1->Si'",
    "direccion"                         => "VARCHAR(50) NULL COMMENT 'Direccion de la propiedad '",
    "codigo_iso_barrio"                 => "VARCHAR(2) NOT NULL COMMENT 'Llave principal de la tabla paises'",
    "codigo_dane_departamento_barrio"   => "VARCHAR(2) NOT NULL COMMENT 'Código DANE'",
    "codigo_dane_municipio_barrio"      => "VARCHAR(3) NOT NULL COMMENT 'Código DANE'",
    "tipo_barrio"                       => "ENUM('B','C') NOT NULL DEFAULT 'B' COMMENT 'Tipo de localidad: B=Barrio, C=Corregimiento'",
    "codigo_dane_localidad_barrio"      => "VARCHAR(3) COMMENT 'Código DANE (sólo para corregimientos)'",
    "telefono"                          => "VARCHAR(15) NULL COMMENT 'Numero telefonico que tiene la vivienda'"
);

$tablas["estudios_aspirante"] = array(
    "documento_identidad_aspirante"     => "VARCHAR(12) NOT NULL COMMENT 'Codigo que identifica el tercero'",
    "consecutivo"                       => "INT(8) NOT NULL COMMENT 'Consecutivo interno para la base de datos'",
    "codigo_escolaridad"                => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo interno que identifica la escoloaridad del aspirante'",
    "titulo"                            => "VARCHAR(255) NULL COMMENT 'Titulo otorgado '",
    "fecha_inicio"                      => "DATE NULL COMMENT 'Fecha que inicio los estudios'",
    "fecha_fin"                         => "DATE NULL COMMENT 'Fecha que finalizo los estudios'",
    "codigo_iso_estudios"               => "VARCHAR(2) NOT NULL COMMENT 'Codigo ISO en la tabla de municipios'",
    "codigo_dane_departamento_estudios" => "VARCHAR(2) NOT NULL COMMENT 'Codigo dane departamento en la tabla de municipios'",
    "codigo_dane_municipio_estudios"    => "VARCHAR(3) NOT NULL COMMENT 'Codigo dane municipio en la tabla de municipios'",
    "intensidad_horaria"                => "INT(2) NULL COMMENT 'Horas de clase al dia'",
    "horario"                           => "ENUM('1','2','3') NOT NULL DEFAULT '1' COMMENT '1->Diurno 2->Nocturno 3->Sabatino'",
    "institucion"                       => "VARCHAR(255) NOT NULL COMMENT 'Nombre de la institucion donde realizo los estudios'"

);

$tablas["idiomas_aspirante"] = array(
    "documento_identidad_aspirante" => "VARCHAR(12) NOT NULL COMMENT 'Codigo que identifica el tercero'",
    "consecutivo"                   => "INT(8) NOT NULL COMMENT 'Consecutivo interno para la base de datos'",
    "codigo_idioma"                 => "INT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Descripcion que identifica el idioma'",
    "habla"                         => "ENUM('1','2','3','4') NULL COMMENT '1->No aplica 2->Regular 3->Bien 4->Excelente'",
    "escritura"                     => "ENUM('1','2','3','4') NULL COMMENT '1->No aplica 2->Regular 3->Bien 4->Excelente'",
    "lectura"                       => "ENUM('1','2','3','4') NULL COMMENT '1->No aplica 2->Regular 3->Bien 4->Excelente'",
);

$tablas["escolaridad"] = array(
    "codigo"        => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo interno para la base de datos del codigo de la escolaridad'",
    "descripcion"   => "VARCHAR(255)NOT NULL COMMENT 'Detalle que identifica la escolaridad'"
);

$tablas["aficiones_aspirante"] = array(
    "documento_identidad_aspirante" => "VARCHAR(12) NOT NULL COMMENT 'Codigo que identifica el tercero'",
    "codigo_aficion"                => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código interno que identifica la aficción'"
);

$tablas["deportes_aspirante"] = array(
    "documento_identidad_aspirante" => "VARCHAR(12) NOT NULL COMMENT 'Codigo que identifica el tercero'",
    "codigo_deporte"                => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código interno que identifica el deporte'"
);

//Definición de llaves primarias
$llavesPrimarias["aspirantes"] = "documento_identidad";

$llavesPrimarias["empresas_aspirante"] = "documento_identidad_aspirante,consecutivo";

$llavesPrimarias["conyugue_aspirante"] = "documento_identidad_aspirante";

$llavesPrimarias["familia_aspirante"] = "documento_identidad_aspirante,consecutivo";

$llavesPrimarias["referencias_aspirante"] = "documento_identidad_aspirante,consecutivo";

$llavesPrimarias["vehiculo_aspirante"] = "documento_identidad_aspirante,consecutivo";

$llavesPrimarias["vivienda_aspirante"] = "documento_identidad_aspirante,consecutivo";

$llavesPrimarias["estudios_aspirante"] = "documento_identidad_aspirante,consecutivo";

$llavesPrimarias["idiomas_aspirante"] = "documento_identidad_aspirante,consecutivo";

$llavesPrimarias["escolaridad"] = "codigo";

$llavesPrimarias["aficiones_aspirante"] = "documento_identidad_aspirante,codigo_aficion";

$llavesPrimarias["deportes_aspirante"] = "documento_identidad_aspirante,codigo_deporte";

//Definición de campos únicos

$llavesUnicas["empresas_aspirante"] = array(
    "documento_identidad_aspirante,nombre"
);

$llavesUnicas["conyugue_aspirante"] = array(
    "documento_identidad"
);

$llavesUnicas["familia_aspirante"] = array(
    "documento_identidad_aspirante,documento_identidad"
);

$llavesUnicas["idiomas_aspirante"] = array(
    "documento_identidad_aspirante,codigo_idioma"
);

$llavesUnicas["escolaridad"] = array(
    "descripcion"
);

//Definici{on de llaves Foraneas
$llavesForaneas["aspirantes"] = array(
    array(
        //Nombre de la llave foranea
        "aspirantes_municipios",
        //Nombre del campo en la tabla actual
        "codigo_iso_nacimiento,codigo_dane_departamento_nacimiento,codigo_dane_municipio_nacimiento",
        //Nombre de la tabla relacionada
        "municipios",
        //Nombre del campo de la tabla relacionada
        "codigo_iso,codigo_dane_departamento,codigo_dane_municipio"
    ),
    array(
        //Nombre de la llave foranea
        "aspirantes_cargos",
        //Nombre del campo en la tabla actual
        "codigo_cargo",
        //Nombre de la tabla relacionada
        "cargos",
        //Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        //Nombre de la llave foranea
        "aspirantes_municipio_arrendatario",
        //Nombre del campo en la tabla actual
        "codigo_iso_arrendatario,codigo_dane_departamento_arrendatario,codigo_dane_municipio_arrendatario",
        //Nombre de la tabla relacionada
        "municipios",
        //Nombre del campo de la tabla relacionada
        "codigo_iso,codigo_dane_departamento,codigo_dane_municipio"
    ),
    array(
        //Nombre de la llave foranea
        "aspirantes_municipio_mayor_estadia",
        //Nombre del campo en la tabla actual
        "codigo_iso_mayor_estadia,codigo_dane_departamento_mayor_estadia,codigo_dane_municipio_mayor_estadia",
        //Nombre de la tabla relacionada
        "municipios",
        //Nombre del campo de la tabla relacionada
        "codigo_iso,codigo_dane_departamento,codigo_dane_municipio"
    ),
    array(
        //Nombre de la llave foranea
        "aspirantes_profesion",
        //Nombre del campo en la tabla actual
        "codigo_dane_profesion",
        //Nombre de la tabla relacionada
        "profesiones_oficios",
        //Nombre del campo de la tabla relacionada
        "codigo_dane"
    ),
    array(
        //Nombre de la llave foranea
        "aspirantes_terceros",
        //Nombre del campo en la tabla actual
        "documento_identidad",
        //Nombre de la tabla relacionada
        "terceros",
        //Nombre del campo de la tabla relacionada
        "documento_identidad"
    ),
    array(
        //Nombre de la llave foranea
        "aspirantes_entidad_cesantias",
        //Nombre del campo en la tabla actual
        "codigo_entidad_cesantias",
        //Nombre de la tabla relacionada
        "entidades_parafiscales",
        //Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        //Nombre de la llave foranea
        "aspirantes_entidad_pension",
        //Nombre del campo en la tabla actual
        "codigo_entidad_pension",
        //Nombre de la tabla relacionada
        "entidades_parafiscales",
        //Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        //Nombre de la llave foranea
        "aspirantes_entidad_salud",
        //Nombre del campo en la tabla actual
        "codigo_entidad_salud",
        //Nombre de la tabla relacionada
        "entidades_parafiscales",
        //Nombre del campo de la tabla relacionada
        "codigo"
    )
);

$llavesForaneas["empresas_aspirante"] = array(
    array(
        //Nombre de la llave foranea
        "empresas_aspirante_aspirantes",
        //Nombre del campo en la tabla actual
        "documento_identidad_aspirante",
        //Nombre de la tabla relacionada
        "aspirantes",
        //Nombre del campo de la tabla relacionada
        "documento_identidad"
    ),
    array(
        //Nombre de la llave foranea
        "empresas_aspirante_motivo_retiro",
        //Nombre del campo en la tabla actual
        "codigo_motivo_retiro",
        //Nombre de la tabla relacionada
        "motivos_retiro",
        //Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        //Nombre de la llave foranea
        "empresas_aspirante_tipos_contrato",
        //Nombre del campo en la tabla actual
        "codigo_tipo_contrato",
        //Nombre de la tabla relacionada
        "tipos_contrato",
        //Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        //Nombre de la llave foranea
        "empresas_aspirante_codigo_departamento_empresa",
        //Nombre del campo en la tabla actual
        "codigo_departamento_empresa",
        //Nombre de la tabla relacionada
        "departamentos_empresa",
        //Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        //Nombre de la llave foranea
        "empresas_aspirante_actividad_economica",
        //Nombre del campo en la tabla actual
        "codigo_iso_actividad,codigo_dane_departamento_actividad,codigo_dane_municipio_actividad,codigo_dian_actividad,codigo_actividad_economica",
        //Nombre de la tabla relacionada
        "actividades_economicas",
        //Nombre del campo de la tabla relacionada
        "codigo_iso,codigo_dane_departamento,codigo_dane_municipio,codigo_dian,codigo_actividad_municipio"
    )
);

$llavesForaneas["conyugue_aspirante"] = array(
    array(
        //Nombre de la llave foranea
        "tipo_documento_conyugue",
        //Nombre del campo en la tabla actual
        "codigo_tipo_documento",
        //Nombre de la tabla relacionada
        "tipos_documento_identidad",
        //Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        //Nombre de la llave foranea
        "profesion_conyugue",
        //Nombre del campo en la tabla actual
        "codigo_dane_profesion",
        //Nombre de la tabla relacionada
        "profesiones_oficios",
        //Nombre del campo de la tabla relacionada
        "codigo_dane"
    ),
    array(
        //Nombre de la llave foranea
        "cargo_conyugue",
        //Nombre del campo en la tabla actual
        "codigo_cargo",
        //Nombre de la tabla relacionada
        "cargos",
        //Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        //Nombre de la llave foranea
        "aspirante_conyugue",
        //Nombre del campo en la tabla actual
        "documento_identidad_aspirante",
        //Nombre de la tabla relacionada
        "aspirantes",
        //Nombre del campo de la tabla relacionada
        "documento_identidad"
    )
);

$llavesForaneas["familia_aspirante"] = array(
    array(
        //Nombre de la llave foranea
        "familia_tipo_documento",
        //Nombre del campo en la tabla actual
        "codigo_tipo_documento",
        //Nombre de la tabla relacionada
        "tipos_documento_identidad",
        //Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        //Nombre de la llave foranea
        "familia_profesion",
        //Nombre del campo en la tabla actual
        "codigo_dane_profesion",
        //Nombre de la tabla relacionada
        "profesiones_oficios",
        //Nombre del campo de la tabla relacionada
        "codigo_dane"
    ),
    array(
        //Nombre de la llave foranea
        "familia_aspirante",
        //Nombre del campo en la tabla actual
        "documento_identidad_aspirante",
        //Nombre de la tabla relacionada
        "aspirantes",
        //Nombre del campo de la tabla relacionada
        "documento_identidad"
    )
);

$llavesForaneas["referencias_aspirante"] = array(
    array(
        //Nombre de la llave foranea
        "referencias_profesion",
        //Nombre del campo en la tabla actual
        "codigo_dane_profesion",
        //Nombre de la tabla relacionada
        "profesiones_oficios",
        //Nombre del campo de la tabla relacionada
        "codigo_dane"
    ),
    array(
        //Nombre de la llave foranea
        "referencias_aspirante",
        //Nombre del campo en la tabla actual
        "documento_identidad_aspirante",
        //Nombre de la tabla relacionada
        "aspirantes",
        //Nombre del campo de la tabla relacionada
        "documento_identidad"
    )
);

$llavesForaneas["vehiculo_aspirante"] = array(
    array(
        //Nombre de la llave foranea
        "vehiculo_aspirante",
        //Nombre del campo en la tabla actual
        "documento_identidad_aspirante",
        //Nombre de la tabla relacionada
        "aspirantes",
        //Nombre del campo de la tabla relacionada
        "documento_identidad"
    )
);

$llavesForaneas["vivienda_aspirante"] = array(
    array(
        //Nombre de la llave foranea
        "vivienda_documento_aspirante",
        //Nombre del campo en la tabla actual
        "documento_identidad_aspirante",
        //Nombre de la tabla relacionada
        "aspirantes",
        //Nombre del campo de la tabla relacionada
        "documento_identidad"
    ),
    array(
        //Nombre de la llave foranea
        "vivienda_barrio",
        //Nombre del campo en la tabla actual
        "codigo_iso_barrio,codigo_dane_departamento_barrio,codigo_dane_municipio_barrio,tipo_barrio,codigo_dane_localidad_barrio",
        //Nombre de la tabla relacionada
        "localidades",
        //Nombre del campo de la tabla relacionada
        "codigo_iso,codigo_dane_departamento,codigo_dane_municipio,tipo,codigo_dane_localidad"
    )
);

$llavesForaneas["estudios_aspirante"] = array(
    array(
        //Nombre de la llave foranea
        "escolaridad_aspirantes",
        //Nombre del campo en la tabla actual
        "codigo_escolaridad",
        //Nombre de la tabla relacionada
        "escolaridad",
        //Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        //Nombre de la llave foranea
        "aspirantes_municipio_escolaridad",
        //Nombre del campo en la tabla actual
        "codigo_iso_estudios,codigo_dane_departamento_estudios,codigo_dane_municipio_estudios",
        //Nombre de la tabla relacionada
        "municipios",
        //Nombre del campo de la tabla relacionada
        "codigo_iso,codigo_dane_departamento,codigo_dane_municipio"
    ),
    array(
        //Nombre de la llave foranea
        "estudios_aspirantes",
        //Nombre del campo en la tabla actual
        "documento_identidad_aspirante",
        //Nombre de la tabla relacionada
        "aspirantes",
        //Nombre del campo de la tabla relacionada
        "documento_identidad"
    )
);

$llavesForaneas["idiomas_aspirante"] = array(
    array(
        //Nombre de la llave foranea
        "idioma_aspirante",
        //Nombre del campo en la tabla actual
        "documento_identidad_aspirante",
        //Nombre de la tabla relacionada
        "aspirantes",
        //Nombre del campo de la tabla relacionada
        "documento_identidad"
    ),
    array(
        //Nombre de la llave foranea
        "idioma_aspirante_idiomas",
        //Nombre del campo en la tabla actual
        "codigo_idioma",
        //Nombre de la tabla relacionada
        "idiomas",
        //Nombre del campo de la tabla relacionada
        "codigo"
    )
);

$llavesForaneas["aficiones_aspirante"] = array(
    array(
        //Nombre de la llave foranea
        "aficiones_aspirante",
        //Nombre del campo en la tabla actual
        "documento_identidad_aspirante",
        //Nombre de la tabla relacionada
        "aspirantes",
        //Nombre del campo de la tabla relacionada
        "documento_identidad"
    ),
    array(
        //Nombre de la llave foranea
        "aficiones_aspirante_aficiones",
        //Nombre del campo en la tabla actual
        "codigo_aficion",
        //Nombre de la tabla relacionada
        "aficiones",
        //Nombre del campo de la tabla relacionada
        "codigo"
    )
);

$llavesForaneas["deportes_aspirante"] = array(
    array(
        //Nombre de la llave foranea
        "deportes_aspirante",
        //Nombre del campo en la tabla actual
        "documento_identidad_aspirante",
        //Nombre de la tabla relacionada
        "aspirantes",
        //Nombre del campo de la tabla relacionada
        "documento_identidad"
    ),
    array(
        //Nombre de la llave foranea
        "deportes_aspirante_deportes",
        //Nombre del campo en la tabla actual
        "codigo_deporte",
        //Nombre de la tabla relacionada
        "deportes",
        //Nombre del campo de la tabla relacionada
        "codigo"
    )
);

$registros["aspirantes"] = array(
    array(
        "documento_identidad"                    => "0",
        "tipo_sangre"                            => "",
        "codigo_cargo"                           => 0,
        "fecha_ingreso"                          => "",
        "fecha_inicio_vivienda"                  => "",
        "derecho_sobre_vivienda"                 => "1",
        "relacion_laboral"                       => "1",
        "canon_arrendo"                          => 0,
        "nombre_arrendatario"                    => "",
        // Llave municipio arrendatario
        "codigo_iso_arrendatario"                => "",
        "codigo_dane_departamento_arrendatario"  => "",
        "codigo_dane_municipio_arrendatario"     => "",
        // Fin llave
        "telefono_arrendatario"                  => "",
        // Llave municipio mayor estadia
        "codigo_iso_mayor_estadia"               => "",
        "codigo_dane_departamento_mayor_estadia" => "",
        "codigo_dane_municipio_mayor_estadia"    => "",
        // Fin llave
        "codigo_dane_profesion"                  => 0,
        "aspiracion_salarial"                    => 0,
        "pensionado"                             => "0",
        "ingreso_pension"                        => 0,
        "experiencia_laboral"                    => "",
        "recomendacion_interna"                  => "",
        "estatura"                               => 0,
        "peso"                                   => 0,
        "talla_camisa"                           => 0,
        "anteojos"                               => "0",
        "talla_pantalon"                         => "",
        "talla_calzado"                          => "",
        "digitador"                              => "",
        "programacion"                           => "",
        "hojas_calculo"                          => "1",
        "procesadores_texto"                     => "1",
        "diseno_diapositivas"                    => "1",
        // Llave municipio nacimiento
        "codigo_iso_nacimiento"                  => "",
        "codigo_dane_departamento_nacimiento"    => "",
        "codigo_dane_municipio_nacimiento"       => "",
        // Fin llave
        "estado_civil"                           => "1",
        "clase_libreta_militar"                  => "1",
        "libreta_militar"                        => 0,
        "distrito_militar"                       => 0,
        "permiso_conducir"                       => 0,
        "categoria_permiso_conducir"             => "1",
        "codigo_entidad_salud"                   => 0,
        "codigo_entidad_pension"                 => 0,
        "codigo_entidad_cesantias"               => 0
    )
);

$registros["componentes"] = array(
    array(
        "id"            => "GESTASPI",
        "padre"         => "SUBMDCRH",
        "id_modulo"     => "NOMINA",
        "orden"         => "10",
        "visible"       => "1",
        "carpeta"       => "aspirantes",
        "global"        => "0",
        "archivo"       => "menu",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "ADICASPI",
        "padre"         => "GESTASPI",
        "id_modulo"     => "NOMINA",
        "orden"         => "10",
        "visible"       => "0",
        "carpeta"       => "aspirantes",
        "global"        => "0",
        "archivo"       => "adicionar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "CONSASPI",
        "padre"         => "GESTASPI",
        "id_modulo"     => "NOMINA",
        "orden"         => "20",
        "visible"       => "0",
        "carpeta"       => "aspirantes",
        "global"        => "0",
        "archivo"       => "consultar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "MODIASPI",
        "padre"         => "GESTASPI",
        "id_modulo"     => "NOMINA",
        "orden"         => "30",
        "visible"       => "0",
        "carpeta"       => "aspirantes",
        "global"        => "0",
        "archivo"       => "modificar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "ELIMASPI",
        "padre"         => "GESTASPI",
        "id_modulo"     => "NOMINA",
        "orden"         => "40",
        "visible"       => "0",
        "carpeta"       => "aspirantes",
        "global"        => "0",
        "archivo"       => "eliminar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "LISTASPI",
        "padre"         => "GESTASPI",
        "id_modulo"     => "NOMINA",
        "orden"         => "40",
        "visible"       => "0",
        "carpeta"       => "aspirantes",
        "global"        => "0",
        "archivo"       => "listar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_aspirantes AS
        SELECT job_terceros.documento_identidad AS id,
        job_terceros.documento_identidad AS NUMERO_DOCUMENTO,
        IF(tipo_persona = '1' OR tipo_persona = '4',CONCAT(
                job_terceros.primer_nombre,' ',
                IF (job_terceros.segundo_nombre IS NOT NULL AND job_terceros.segundo_nombre != '',CONCAT(job_terceros.segundo_nombre,' '),''),
                job_terceros.primer_apellido,' ',
                IF (job_terceros.segundo_apellido IS NOT NULL AND job_terceros.segundo_apellido != '',job_terceros.segundo_apellido,'')
            ),
            job_terceros.razon_social) AS NOMBRE_COMPLETO
        FROM job_terceros, job_aspirantes
        WHERE job_aspirantes.documento_identidad = job_terceros.documento_identidad;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_aspirantes AS
        SELECT job_terceros.documento_identidad AS id,
        job_terceros.documento_identidad AS NUMERO_DOCUMENTO,
        job_terceros.primer_nombre,
        job_terceros.segundo_nombre,
        job_terceros.primer_apellido,
        job_terceros.segundo_apellido,
        job_terceros.razon_social,
        IF(tipo_persona = '1' OR tipo_persona = '4',CONCAT(
                job_terceros.primer_nombre,' ',
                IF (job_terceros.segundo_nombre IS NOT NULL AND job_terceros.segundo_nombre != '',CONCAT(job_terceros.segundo_nombre,' '),''),
                job_terceros.primer_apellido,' ',
                IF (job_terceros.segundo_apellido IS NOT NULL AND job_terceros.segundo_apellido != '',job_terceros.segundo_apellido,'')
            ),
            job_terceros.razon_social) AS NOMBRE_COMPLETO
        FROM job_terceros, job_aspirantes
        WHERE job_aspirantes.documento_identidad = job_terceros.documento_identidad;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_aspirantes AS
        SELECT job_aspirantes.documento_identidad AS id,
        CONCAT(job_terceros.documento_identidad,' - ',IF(job_terceros.primer_nombre IS NOT NULL,job_terceros.primer_nombre,' '),' ',
        IF(job_terceros.segundo_nombre IS NOT NULL,job_terceros.segundo_nombre,''),' ',
        IF(job_terceros.primer_apellido IS NOT NULL,job_terceros.primer_apellido,''),' ',
        IF(job_terceros.segundo_apellido IS NOT NULL,job_terceros.segundo_apellido,''),'|',job_aspirantes.documento_identidad) AS nombre_completo
        FROM job_terceros, job_aspirantes
        WHERE job_aspirantes.documento_identidad = job_terceros.documento_identidad
        AND job_aspirantes.documento_identidad > 0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_escolaridad AS
        SELECT codigo AS id,
        CONCAT(codigo,' - ',descripcion,'|',codigo) AS descripcion
        FROM job_escolaridad;"
    )
);

/***
    DROP TABLE IF EXISTS job_menu_aspirantes;
    DROP TABLE IF EXISTS job_buscador_aspirantes;
    DROP TABLE IF EXISTS job_seleccion_aspirantes;
    DROP TABLE IF EXISTS job_seleccion_escolaridad;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_aspirantes AS
    SELECT job_terceros.documento_identidad AS id,
    job_terceros.documento_identidad AS NUMERO_DOCUMENTO,
    CONCAT(IF(job_terceros.primer_nombre IS NOT NULL,job_terceros.primer_nombre,''),' ',
    IF(job_terceros.segundo_nombre IS NOT NULL,job_terceros.segundo_nombre,''),' ',
    IF(job_terceros.primer_apellido IS NOT NULL,job_terceros.primer_apellido,''),' ',
    IF(job_terceros.segundo_apellido IS NOT NULL,job_terceros.segundo_apellido,''),' ',
    IF(job_terceros.razon_social IS NOT NULL,job_terceros.razon_social,'')) AS NOMBRE_COMPLETO
    FROM job_terceros, job_aspirantes
    WHERE job_aspirantes.documento_identidad = job_terceros.documento_identidad;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_aspirantes AS
    SELECT job_terceros.documento_identidad AS id,
    job_terceros.documento_identidad AS NUMERO_DOCUMENTO,
    CONCAT(IF(job_terceros.primer_nombre IS NOT NULL,job_terceros.primer_nombre,''),' ',
    IF(job_terceros.segundo_nombre IS NOT NULL,job_terceros.segundo_nombre,''),' ',
    IF(job_terceros.primer_apellido IS NOT NULL,job_terceros.primer_apellido,''),' ',
    IF(job_terceros.segundo_apellido IS NOT NULL,job_terceros.segundo_apellido,''),' ',
    IF(job_terceros.razon_social IS NOT NULL,job_terceros.razon_social,'')) AS NOMBRE_COMPLETO
    FROM job_terceros, job_aspirantes
    WHERE job_aspirantes.documento_identidad = job_terceros.documento_identidad;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_aspirantes AS
    SELECT job_aspirantes.documento_identidad AS id,
    CONCAT(IF(job_terceros.primer_nombre IS NOT NULL,job_terceros.primer_nombre,' '),' ',
    IF(job_terceros.segundo_nombre IS NOT NULL,job_terceros.segundo_nombre,''),' ',
    IF(job_terceros.primer_apellido IS NOT NULL,job_terceros.primer_apellido,''),' ',
    IF(job_terceros.segundo_apellido IS NOT NULL,job_terceros.segundo_apellido,''),'|',job_aspirantes.documento_identidad) AS nombre_completo
    FROM job_terceros, job_aspirantes
    WHERE job_aspirantes.documento_identidad = job_terceros.documento_identidad
    AND job_aspirantes.documento_identidad > 0;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_escolaridad AS
    SELECT codigo AS id,
    CONCAT(codigo,' - ',descripcion,'|',codigo) AS descripcion
    FROM job_escolaridad;
***/
?>

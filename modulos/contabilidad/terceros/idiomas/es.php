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

$textos = array(
    "GESTTERC"                              => "Terceros",
    "ADICTERC"                              => "Adicionar tercero",
    "CONSTERC"                              => "Consultar tercero",
    "MODITERC"                              => "Modificar tercero",
    "ELIMTERC"                              => "Eliminar tercero",
    "LISTTERC"                              => "Listar terceros",
    "REPORTE_TERCEROS"                      => "Listado de terceros",
    "PESTANA_PERSONAL"                      => "Datos personales",
    "NOMBRE_COMPLETO"                       => "Nombre completo",
    //Campos
    "TIPO_DOCUMENTO_IDENTIDAD"              => "Tipo de documento de identidad",
    "TIPO_PERSONA"                          => "Tipo de persona",
    "PERSONA_NATURAL"                       => "Natural",
    "PERSONA_JURIDICA"                      => "Juridica",
    "CODIGO_INTERNO"                        => "Codigo interno",
    "NATURAL_COMERCIANTE"                   => "Natural Comerciante",
    "DOCUMENTO_IDENTIDAD"                   => "Documento identidad",
    "FECHA_NACIMIENTO"                      => "Fecha nacimiento",
    "MUNICIPIO"                             => "Municipio de expedición",
    "PRIMER_NOMBRE"                         => "Primer nombre",
    "SEGUNDO_NOMBRE"                        => "Segundo nombre",
    "PRIMER_APELLIDO"                       => "Primer apellido",
    "SEGUNDO_APELLIDO"                      => "Segundo apellido",
    "RAZON_SOCIAL"                          => "Razon social",
    "NOMBRE_COMERCIAL"                      => "Nombre comercial",
    "LOCALIDAD"                             => "Barrio o corregimiento de residencia",
    "DIRECCION"                             => "Direccion",
    "TELEFONO"                              => "Telefono Principal",
    "FAX"                                   => "Fax",
    "CELULAR"                               => "Celular",
    "CELULAR2"                              => "Celular 2",
    "CORREO"                                => "Corre electronico",
    "CORREO2"                               => "Corre electronico 2",
    "SITIO_WEB"                             => "Sitio Web",
    "GENERO"                                => "Genero",
    "MASCULINO"                             => "Masculino",
    "FEMENINO"                              => "Femenino",
    "NO_APLICA"                             => "No aplica",
    "ESTADO"                                => "Estado",
    "ACTIVO"                                => "Activo",
    "INACTIVO"                              => "Inactivo",
    //Ayudas
    "AYUDA_TIPO_DOCUMENTO"                  => "Seleccione un tipo de documento de identidad",
    "AYUDA_DOCUMENTO_IDENTIDAD"             => "Digite el numero de documento de identidad",
    "AYUDA_FECHA_NACIMIENTO"                => "Seleccione la fecha de nacimiento del tercero",
    "AYUDA_MUNICIPIO_DOCUMENTO"             => "Digite el municipio de expedicion del documento de identidad",
    "AYUDA_PRIMER_NOMBRE"                   => "Digite el primer nombre",
    "AYUDA_SEGUNDO_NOMBRE"                  => "Digite el segundo nombre",
    "AYUDA_PRIMER_APELLIDO"                 => "Digite el primer apellido",
    "AYUDA_SEGUNDO_APELLIDO"                => "Digite el segundo apellido",
    "AYUDA_RAZON_SOCIAL"                    => "Digite la razon social del tercero",
    "AYUDA_NOMBRE_COMERCIAL"                => "Digite el nombre comercial del tercero",
    "AYUDA_LOCALIDAD"                       => "Digite el barrio o corregimiento de residencia del tercero",
    "AYUDA_DIRECCION"                       => "Digite la direccion del tercero",
    "AYUDA_TELEFONO"                        => "Digite el telefono del tercero",
    "AYUDA_FAX"                             => "Digite el fax del tercero",
    "AYUDA_CELULAR"                         => "Digite el celular del tercero",
    "AYUDA_CORREO"                          => "Digite correo electronico del tercero",
    "AYUDA_SITIO_WEB"                       => "Digite el sitio web del tercero",
    "AYUDA_ESTADO"                          => "Seleecione el estado del tercero",
    //Errores
    "ERROR_DOCUMENTO_IDENTIDAD_VACIO"       => "Error, Documento de identidad no puede quedar vacio",
    "ERROR_DOCUMENTO_IDENTIDAD_EXISTE"      => "Error, Documento de identidad ya existe, verifique",
    "ERROR_NOMBRE_APELLIDO_VACIO"           => "Error, Primer nombre o Primer apellido no pueden quedar vacios",
    "ERROR_RAZON_SOCIAL_VACIO"              => "Error, Razon social no puede quedar vacio",
    "ERROR_MUNICIPIO_EXPEDICION_VACIO"      => "Error, Municipio de expedicion no puede quedar vacio",
    "ERROR_MUNICIPIO_NO_EXISTE"             => "Error, el Municipio de expedicion que digito no existe en la base de datos, verifique",
    "ERROR_BARRIO_CORREGIMIENTO_VACIO"      => "Error, Barrio o corregimiento de residencia no puede quedar vacio",
    "ERROR_BARRIO_CORREGIMIENTO_NO_EXISTE"  => "Error, Barrio o corregimiento de residencia que digito no existe en la base de datos, verifique",
    "NO_EXISTEN"                            => "No existen datos en las siguientes tablas:",
    "CREAR"                                 => "Es necesario crear al menos un registro",
    "TIPOS_DOCUMENTO_IDENTIDAD"             => "-Tipos de documentos de identidad",
    "MUNICIPIOS"                            => "-Municipios",
    "LOCALIDADES"                           => "-Localidades(Barrios y/o corregimientos)",
    "ARCHIVO_PLANO"                         => "Archivo plano para excel",
    "ARCHIVO_PDF"                           => "Pdf",
    "TERCERO_INICIAL"                       => "Documento desde",
    "TERCERO_FINAL"                         => "Documento hasta",
    "FECHA_DESDE"                           => "Fecha nacimiento desde",
    "FECHA_HASTA"                           => "Fecha nacimiento hasta",
    "ERROR_DOCUMENTO_IDENTIDAD_2"           => "Debe digitar el documento de identidad Inicial",
    "TIPO_LISTADO"                          => "Tipo de listado",
    "AYUDA_TIPO_LISTADO"                    => "Seleccione el tipo de listado a generar",
    "TIPO_DOCUMENTO"                        => "Tipo documento",
    "LUGAR_RESIDENCIA"                      => "Lugar de residencia"
);
?>

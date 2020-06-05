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

// Eliminar la tabla y crearla de nuevo cada vez que se ejecute el script de creación
$borrarSiempre = false;

// Definición de tablas
$tablas["sesiones"] = array(
    "id"         => "CHAR(32) NOT NULL COMMENT 'Identificador de la sesión'",
    "expiracion" => "INT(10) UNSIGNED NOT NULL COMMENT 'Fecha de expiración (en formato Unix Timestamp) de la sesión por inactividad'",
    "contenido"  => "TEXT NOT NULL COMMENT 'Variables definidas en la sesión con sus respectivos valores'"
);

$tablas["modulos"] = array(
    "id"          => "CHAR(32) NOT NULL COMMENT 'Identificador del módulo'",
    "nombre"      => "VARCHAR(32) NOT NULL COMMENT 'Nombre del módulo'",
    "descripcion" => "VARCHAR(255) NOT NULL COMMENT 'Descripción del módulo'",
    "carpeta"     => "VARCHAR(255) COMMENT 'Carpeta donde estarán almacenados los componentes del módulo'",
    "url"         => "VARCHAR(255) NULL COMMENT 'URL del módulo'",
    "version"     => "CHAR(10) NULL COMMENT 'Versión del módulo (Formato: AAAAMMDD+consecutivo. Ej: 2008031501)'"
);

$tablas["componentes"] = array(
    "id"              => "VARCHAR(8) NOT NULL COMMENT 'Identificador del componente'",
    "padre"           => "VARCHAR(8) COMMENT 'Identificador del padre del componente: NULL = Componente principal'",
    "id_modulo"       => "CHAR(32) NOT NULL COMMENT 'Identificador del modulo al cual pertenece'",
    "requiere_item"   => "ENUM('0','1') NOT NULL DEFAULT '1' COMMENT 'Si el componente requiere item: 0->No, 1->Si'",
    "global"          => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT 'Todos los usuarios lo pueden cargar sin verificar permisos: 0=No, 1=Si'",
    "visible"         => "ENUM('0','1') NOT NULL DEFAULT '1' COMMENT 'El componente debe aparecer en el menú: 0=No, 1=Si'",
    "orden"           => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL DEFAULT '0' COMMENT 'Orden en el que debe presentarse en el menú ó en los listados'",
    "tabla_principal" => "VARCHAR(255) DEFAULT NULL COMMENT 'Nombre de la tabla principal con la que se relaciona el componente'",
    "carpeta"         => "VARCHAR(255) COMMENT 'Carpeta donde está almacenado el archivo'",
    "archivo"         => "VARCHAR(255) COMMENT 'Archivo que se debe cargar al seleccionar el componente: NULL = No genera enlace o acción'",
    "tipo_enlace"     => "ENUM('1','2') NOT NULL DEFAULT '1' COMMENT '1->Muestra opciones de seleccion 2->Abre formulario directamente'"
);

$tablas["terceros"] = array(
    "documento_identidad"                => "VARCHAR(12) NOT NULL COMMENT 'Número del documento de identidad'",
    "codigo_tipo_documento"              => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo interno para la base de datos del tipo de documento de identidad'",
    //tabla municipios
    "codigo_iso_municipio_documento"     => "VARCHAR(2) NOT NULL COMMENT 'Llave principal de la tabla paises'",
    "codigo_dane_departamento_documento" => "VARCHAR(2) NOT NULL COMMENT 'Código DANE'",
    "codigo_dane_municipio_documento"    => "VARCHAR(3) NOT NULL COMMENT 'Código DANE'",
    ////////////////////////////////////
    "tipo_persona"                       => "ENUM('1','2','3','4') NOT NULL DEFAULT '1' COMMENT 'Tipo de persona: 1=Natural, 2=Juridica, 3=Código interno, 4 =Natural Comerciante'",
    "primer_nombre"                      => "VARCHAR(15) DEFAULT NULL COMMENT 'Primer nombre (persona natural)'",
    "segundo_nombre"                     => "VARCHAR(15) DEFAULT NULL COMMENT 'Segundo nombre (persona natural)'",
    "primer_apellido"                    => "VARCHAR(20) DEFAULT NULL COMMENT 'Primer apellido (persona natural)'",
    "segundo_apellido"                   => "VARCHAR(20) DEFAULT NULL COMMENT 'Segundo apellido (persona natural)'",
    "razon_social"                       => "VARCHAR(255) DEFAULT NULL COMMENT 'Razon social (persona jurídica)'",
    "nombre_comercial"                   => "VARCHAR(255) DEFAULT NULL COMMENT 'Nombre comercial (persona jurídica)'",
    "fecha_nacimiento"                   => "DATE NULL COMMENT 'Fecha de nacimiento de la persona ó constitución de la sociedad'",
    //tabla localidades
    "codigo_iso_localidad"               => "VARCHAR(2) NOT NULL COMMENT 'Llave principal de la tabla paises'",
    "codigo_dane_departamento_localidad" => "VARCHAR(2) NOT NULL COMMENT 'Código DANE'",
    "codigo_dane_municipio_localidad"    => "VARCHAR(3) NOT NULL COMMENT 'Código DANE'",
    "tipo_localidad"                     => "ENUM('B','C') NOT NULL DEFAULT 'B' COMMENT 'Tipo de localidad: B=Barrio, C=Corregimiento'",
    "codigo_dane_localidad"              => "VARCHAR(3) COMMENT 'Código DANE para el barrio o corregimiento'",
    ////////////////////////////////////
    "direccion_principal"                => "VARCHAR(50) DEFAULT NULL COMMENT 'Dirección de residencia'",
    "telefono_principal"                 => "VARCHAR(15) DEFAULT NULL COMMENT 'Número de teléfono'",
    "celular"                            => "VARCHAR(20) DEFAULT NULL COMMENT 'Número de celular'",
    "celular2"                           => "VARCHAR(20) DEFAULT NULL COMMENT 'Número de celular 2'",
    "fax"                                => "VARCHAR(20) DEFAULT NULL COMMENT 'Número de fax'",
    "correo"                             => "VARCHAR(255) DEFAULT NULL COMMENT 'Direccion de correo electronico'",
    "correo2"                            => "VARCHAR(255) DEFAULT NULL COMMENT 'Direccion de correo electronico 2'",
    "sitio_web"                          => "VARCHAR(50) DEFAULT NULL COMMENT 'Dirección del sitio web'",
    "genero"                             => "ENUM('M','F','N') NOT NULL DEFAULT 'N' COMMENT 'Género: M=Masculino, F=Femenino, N=No aplica'",
    "activo"                             => "ENUM('0','1') NOT NULL DEFAULT '1' COMMENT 'El tercero está activo 0=No, 1=Si'",
    "fecha_ingreso"                      => "DATE NOT NULL DEFAULT '0000-00-00' COMMENT 'Fecha ingreso al sistema'"
);

$tablas["imagenes"] = array(
    "id_asociado" => "INT(9) UNSIGNED ZEROFILL NOT NULL COMMENT 'Llave primaria de la tabla asociada según la categoría'",
    "categoria"   => "ENUM('1','2','3') NOT NULL COMMENT 'Clase de imagen: 1->Usuarios, 2->Artículos, 3->Firma digital'",
    "contenido"   => "MEDIUMBLOB NOT NULL COMMENT 'Lista de valores (datos) de las columnas'",
    "tipo"        => "VARCHAR(255) NOT NULL COMMENT 'TIpo de archivo (MIME)'",
    "extension"   => "ENUM('png','jpg','gif') NOT NULL COMMENT 'Extensión que determina el tipo de imagen'",
    "ancho"       => "SMALLINT(4) NOT NULL COMMENT 'Ancho de la imagen en pixeles'",
    "alto"        => "SMALLINT(4) NOT NULL COMMENT 'Alto de la imagen en pixeles'"
);

/*$tablas["actualizaciones_almacen"] = array(
    "id"          => "INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT NOT NULL COMMENT 'Consecutivo interno para la base de datos'",
    "fecha"       => "DATETIME NOT NULL COMMENT 'Fecha y hora en la que se ejecutó la instrucción'",
    "instruccion" => "ENUM('I','U','D') NOT NULL COMMENT 'Tipo de sentencia SQL originada en el almacén: I=INSERT, U=UPDATE, D=DELETE'",
    "tabla"       => "VARCHAR(255) NOT NULL COMMENT 'Nombre de la tabla en la que se debe ejecutar la instrucción'",
    "columnas"    => "TEXT NOT NULL COMMENT 'Lista de columnas'",
    "valores"     => "TEXT NOT NULL COMMENT 'Lista de valores (datos) de las columnas'",
    "id_asignado" => "INT(10) NOT NULL COMMENT 'Consecutivo interno asginado automáticamente para la instrucción actual'",
    "id_real"     => "INT(10) NOT NULL DEFAULT '0' COMMENT 'Consecutivo interno asginado en el proceso de sincronizacion id->servidor'",
    "leido"       => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT 'Registro leido 0->No 1->Si'",
    "consecutivo_asignado" => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT 'Modificacion del Consecutivo de documento asignado en el servidor 0->No 1->Si'",
    "condicion"            => "VARCHAR(255) NULL COMMENT 'Condicion de modificacion ó eliminación en caso de que la tabla no tenga campo id como llave principal'"
);

$tablas["actualizaciones_servidor"] = array(
    "id"           => "INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT NOT NULL COMMENT 'Consecutivo interno para la base de datos'",
    "id_servidor"  => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo interno para la base de datos del almacén que originó la instrucción'",
    "fecha"        => "DATETIME NOT NULL COMMENT 'Fecha y hora en la que se ejecutó la instrucción'",
    "instruccion1" => "TEXT NOT NULL COMMENT 'Sentencia SQL para el almacén que originó la actualización'",
    "instruccion2" => "TEXT NOT NULL COMMENT 'Sentencia SQL para el resto de almacenes'"
);


$tablas["actualizaciones_procesadas_servidor"] = array(
    "id_servidor"      => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Llave primaria de la tabla servidores'",
    "id_actualizacion" => "INT(10) UNSIGNED ZEROFILL NOT NULL COMMENT 'Llave primaria de la tabla actualizaciones_servidor'"
);

$tablas["actualizaciones_procesadas_cliente"] = array(
    "id_servidor"      => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Llave primaria de la tabla servidores'",
    "id_actualizacion" => "INT(10) UNSIGNED ZEROFILL NOT NULL COMMENT 'Llave primaria de la tabla actualizaciones_almacen'",
    "id_real"          => "INT(10) NOT NULL DEFAULT '0' COMMENT 'Consecutivo interno asginado en el proceso de sincronizacion id en la tabla modificada en el servidor'",
    "ejecutado"        => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT 'La instruccion fue ejecutada en el servidor principal 0->No 1->Si'",
    "actualizado"      => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT 'La actualizacion fue marcada como leida y con el id_real en el servidor de la sucursal'"
);*/

$tablas["archivos"] = array(
    "codigo_sucursal" => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código interno de la sucursal a la cual pertenece'",
    "consecutivo"     => "INT(9) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo interno para la base de datos'",
    "nombre"          => "CHAR(20) NOT NULL COMMENT 'Nombre completo del archivo'",
    "descripcion"     => "VARCHAR(255) DEFAULT NULL COMMENT 'Descripcion del archivo, opcional'",

);

// $tablas["actualizaciones_relaciones"] = array(
//     "id"            => "INT(10) NOT NULL COMMENT 'Consecutivo interno para la base de datos'",
//     "tabla_origen"  => "VARCHAR(255) DEFAULT NOT NULL COMMENT ''",
//     "campo_origen"  => "VARCHAR(255) DEFAULT NOT NULL COMMENT ''",
//     "tabla_destino" => "VARCHAR(255) DEFAULT NOT NULL COMMENT ''",
//     "campo_destino" => "VARCHAR(255) DEFAULT NOT NULL COMMENT ''"
// );


// Definición de llaves primarias

$llavesPrimarias["sesiones"]                   = "id";
$llavesPrimarias["modulos"]                    = "id";
$llavesPrimarias["componentes"]                = "id";
$llavesPrimarias["terceros"]                   = "documento_identidad";
$llavesPrimarias["imagenes"]                   = "id_asociado,categoria";
$llavesPrimarias["archivos"]                   = "codigo_sucursal,consecutivo";
/*$llavesPrimarias["actualizaciones_almacen"]    = "id";
$llavesPrimarias["actualizaciones_servidor"]   = "id";
$llavesPrimarias["actualizaciones_relaciones"] = "id";*/

// Definición de campos únicos
$llavesUnicas["modulos"]  = array(
    "nombre"
);

// Definición de llaves foráneas
$llavesForaneas["componentes"] = array(
    array(
        // Nombre de la llave
        "componente_modulo",
        // Nombre del campo clave de la tabla local
        "id_modulo",
        // Nombre de la tabla relacionada
        "modulos",
        // Nombre del campo clave en la tabla relacionada
        "id"
    )
);

$llavesForaneas["terceros"] = array(
    array(
        // Nombre de la llave
        "tercero_tipo_documento",
        // Nombre del campo clave de la tabla local
        "codigo_tipo_documento",
        // Nombre de la tabla relacionada
        "tipos_documento_identidad",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave
        "tercero_municipio_documento",
        // Nombre del campo clave de la tabla local
        "codigo_iso_municipio_documento,codigo_dane_departamento_documento,codigo_dane_municipio_documento",
        // Nombre de la tabla relacionada
        "municipios",
        // Nombre del campo clave en la tabla relacionada
        "codigo_iso,codigo_dane_departamento,codigo_dane_municipio"
    ),
    array(
        // Nombre de la llave
        "tercero_municipio_residencia",
        // Nombre del campo clave de la tabla local
        "codigo_iso_localidad,codigo_dane_departamento_localidad,codigo_dane_municipio_localidad,tipo_localidad,codigo_dane_localidad",
        // Nombre de la tabla relacionada
        "localidades",
        // Nombre del campo clave en la tabla relacionada
        "codigo_iso,codigo_dane_departamento,codigo_dane_municipio,tipo,codigo_dane_localidad"
    )
);

// Definición de llaves foráneas
/*$llavesForaneas["actualizaciones_servidor"] = array(
    array(
        // Nombre de la llave
        "actualizaciones_servidor_servidor",
        // Nombre del campo clave de la tabla local
        "id_servidor",
        // Nombre de la tabla relacionada
        "servidores",
        // Nombre del campo clave en la tabla relacionada
        "id"
    )
);
// Definición de llaves foráneas
$llavesForaneas["actualizaciones_procesadas_servidor"] = array(
    array(
        // Nombre de la llave
        "procesadas_servidor",
        // Nombre del campo clave de la tabla local
        "id_servidor",
        // Nombre de la tabla relacionada
        "servidores",
        // Nombre del campo clave en la tabla relacionada
        "id"
    )
);
// Definición de llaves foráneas
$llavesForaneas["actualizaciones_procesadas_cliente"] = array(
    array(
        // Nombre de la llave
        "actualizaciones_cliente",
        // Nombre del campo clave de la tabla local
        "id_servidor",
        // Nombre de la tabla relacionada
        "servidores",
        // Nombre del campo clave en la tabla relacionada
        "id"
    )
);*/

// Inserción de datos iniciales
$registros["modulos"] = array(
    array(
        "id"          => "ADMINISTRACION",
        "nombre"      => "Administracion",
        "descripcion" => "Operaciones y datos de control relacionados con el acceso a la aplicacion y la integracion de sus componentes",
        "carpeta"     => "administracion"
    ),
    array(
        "id"          => "INVENTARIO",
        "nombre"      => "Inventario",
        "descripcion" => "Operaciones y datos de control relacionados con el inventario",
        "carpeta"     => "inventarios"
    ),
    array(
        "id"          => "PROVEEDORES",
        "nombre"      => "Proveedores",
        "descripcion" => "Operaciones y datos de control relacionados con los proveedores",
        "carpeta"     => "proveedores"
    ),
    array(
        "id"          => "CLIENTES",
        "nombre"      => "Clientes",
        "descripcion" => "Operaciones y datos de control relacionados con los clientes",
        "carpeta"     => "clientes"
    ),
    array(
        "id"          => "CONTABILIDAD",
        "nombre"      => "Contabilidad",
        "descripcion" => "Menu de contabilidad",
        "carpeta"     => "contabilidad"
    ),
    array(
        "id"          => "EXTENSIONES",
        "nombre"      => "Extensiones",
        "descripcion" => "Extensiones de uso general de la aplicación",
        "carpeta"     => "extensiones"
    ),
    array(
       "id"           => "NOMINA",
       "nombre"       => "Nomina",
       "descripcion"  => "Operaciones y datos de control relacionados con la nomina",
       "carpeta"      => "nomina"
    ),
    array(
        "id"          => "LOGISTICA",
        "nombre"      => "Logistica",
        "descripcion" => "Operaciones y datos de control relacionados con la logistica",
        "carpeta"     => "logistica"
    ),
    array(
        "id"          => "FINANCIERA",
        "nombre"      => "Financiera",
        "descripcion" => "Operaciones y datos de control relacionados con lo financiero",
        "carpeta"     => "financiera"
    )
);

$registros["terceros"] = array(
    array(
        "documento_identidad"                => "0",
        "codigo_tipo_documento"              => "0",
        "codigo_iso_municipio_documento"     => "",
        "codigo_dane_departamento_documento" => "",
        "codigo_dane_municipio_documento"    => "",
        "tipo_persona"                       => "3",
        "primer_nombre"                      => "",
        "segundo_nombre"                     => "",
        "primer_apellido"                    => "",
        "segundo_apellido"                   => "",
        "razon_social"                       => "",
        "nombre_comercial"                   => "",
        "fecha_nacimiento"                   => "0000-00-00",
        "codigo_iso_localidad"               => "",
        "codigo_dane_departamento_localidad" => "",
        "codigo_dane_municipio_localidad"    => "",
        "tipo_localidad"                     => "B",
        "codigo_dane_localidad"              => "",
        "direccion_principal"                => "",
        "telefono_principal"                 => "",
        "celular"                            => "",
        "fax"                                => "",
        "correo"                             => "",
        "sitio_web"                          => "",
        "genero"                             => "M",
        "activo"                             => "0"
    )
);

$registros["archivos"] = array(
    array(
        "codigo_sucursal" => "0",
        "consecutivo"     => 0,
        "nombre"          => "",
        "descripcion"     => ""
    )
);

$registros["componentes"] = array(
    array(
        "id"        => "MENUINSE",
        "padre"     => "NULL",
        "id_modulo" => "ADMINISTRACION",
        "visible"   => "0",
        "orden"     => "0",
        "carpeta"   => "principal",
        "archivo"   => "iniciar",
        "global"    => "1"
    ),
    array(
        "id"        => "MENUPRIN",
        "padre"     => "NULL",
        "id_modulo" => "ADMINISTRACION",
        "visible"   => "1",
        "orden"     => "1",
        "carpeta"   => "principal",
        "archivo"   => "principal",
        "global"    => "1"
    ),
    array(
        "id"        => "MENUINVE",
        "padre"     => "NULL",
        "id_modulo" => "ADMINISTRACION",
        "visible"   => "1",
        "orden"     => "100",
        "carpeta"   => "principal",
        "archivo"   => "NULL",
        "global"    => "0"
    ),
    array(
        "id"        => "MENUPROV",
        "padre"     => "NULL",
        "id_modulo" => "ADMINISTRACION",
        "visible"   => "1",
        "orden"     => "200",
        "carpeta"   => "principal",
        "archivo"   => "NULL",
        "global"    => "0"
    ),/*
    array(
        "id"        => "MENUPROD",
        "padre"     => "NULL",
        "id_modulo" => "ADMINISTRACION",
        "visible"   => "1",
        "orden"     => "300",
        "carpeta"   => "principal",
        "archivo"   => "NULL",
        "global"    => "0"
    ),*/
    array(
        "id"        => "MENUCLIE",
        "padre"     => "NULL",
        "id_modulo" => "ADMINISTRACION",
        "visible"   => "1",
        "orden"     => "400",
        "carpeta"   => "principal",
        "archivo"   => "NULL",
        "global"    => "0"
    ),/*
    array(
        "id"        => "MENUFINA",
        "padre"     => "NULL",
        "id_modulo" => "ADMINISTRACION",
        "visible"   => "1",
        "orden"     => "500",
        "carpeta"   => "principal",
        "archivo"   => "NULL",
        "global"    => "0"
    ),
    array(
        "id"        => "MENUMERC",
        "padre"     => "NULL",
        "id_modulo" => "ADMINISTRACION",
        "visible"   => "1",
        "orden"     => "600",
        "carpeta"   => "principal",
        "archivo"   => "NULL",
        "global"    => "0"
    ),
    array(
        "id"        => "MENULOG",
        "padre"     => "NULL",
        "id_modulo" => "ADMINISTRACION",
        "visible"   => "1",
        "orden"     => "700",
        "carpeta"   => "principal",
        "archivo"   => "NULL",
        "global"    => "0"
    ),*/

    array(
        "id"        => "MENUNOMI",
        "padre"     => "NULL",
        "id_modulo" => "ADMINISTRACION",
        "visible"   => "1",
        "orden"     => "800",
        "carpeta"   => "principal",
        "archivo"   => "NULL",
        "global"    => "0"
    ),
    array(
        "id"        => "MENUCONT",
        "padre"     => "NULL",
        "id_modulo" => "ADMINISTRACION",
        "visible"   => "1",
        "orden"     => "900",
        "carpeta"   => "principal",
        "archivo"   => "NULL",
        "global"    => "0"
    ),
    array(
        "id"        => "MENUADMI",
        "padre"     => "NULL",
        "id_modulo" => "ADMINISTRACION",
        "visible"   => "1",
        "orden"     => "1000",
        "carpeta"   => "principal",
        "archivo"   => "NULL",
        "global"    => "0"
    ),
    array(
        "id"        => "MENUFINS",
        "padre"     => "NULL",
        "id_modulo" => "ADMINISTRACION",
        "visible"   => "1",
        "orden"     => "1100",
        "carpeta"   => "principal",
        "archivo"   => "finalizar",
        "global"    => "1"
    ),
    array(
        "id"        => "SUBMESTC",
        "padre"     => "MENUADMI",
        "id_modulo" => "ADMINISTRACION",
        "visible"   => "1",
        "orden"     => "100",
        "carpeta"   => "principal",
        "archivo"   => "NULL",
        "global"    => "0"
    ),
    array(
        "id"        => "SUBMACCE",
        "padre"     => "MENUADMI",
        "id_modulo" => "ADMINISTRACION",
        "visible"   => "1",
        "orden"     => "200",
        "carpeta"   => "principal",
        "archivo"   => "NULL",
        "global"    => "0"
    ),
    array(
        "id"        => "SUBMDISP",
        "padre"     => "MENUADMI",
        "id_modulo" => "ADMINISTRACION",
        "visible"   => "1",
        "orden"     => "300",
        "carpeta"   => "principal",
        "archivo"   => "NULL",
        "global"    => "0"
    ),
    array(
        "id"        => "SUBMSEGU",
        "padre"     => "MENUADMI",
        "id_modulo" => "ADMINISTRACION",
        "visible"   => "1",
        "orden"     => "400",
        "carpeta"   => "principal",
        "archivo"   => "NULL",
        "global"    => "0"
    ),
    array(
        "id"        => "SUBMDCAD",
        "padre"     => "MENUADMI",
        "id_modulo" => "ADMINISTRACION",
        "visible"   => "1",
        "orden"     => "500",
        "carpeta"   => "principal",
        "archivo"   => "NULL",
        "global"    => "0"
    ),
    array(
        "id"        => "SUBMUBIG",
        "padre"     => "SUBMDCAD",
        "id_modulo" => "ADMINISTRACION",
        "visible"   => "1",
        "orden"     => "600",
        "carpeta"   => "principal",
        "archivo"   => "NULL",
        "global"    => "0"
    ),
     // Componente especial para la visualización de imágenes desde una tabla
    array(
        "id"        => "VISUIMAG",
        "padre"     => "NULL",
        "id_modulo" => "ADMINISTRACION",
        "visible"   => "0",
        "orden"     => "0500",
        "carpeta"   => "principal",
        "archivo"   => "visualizar",
        "global"    => "1"
    ),
    // Componente especial para la descarga de archivos
    array(
        "id"        => "DESCARCH",
        "padre"     => "NULL",
        "id_modulo" => "ADMINISTRACION",
        "visible"   => "0",
        "orden"     => "0550",
        "carpeta"   => "principal",
        "archivo"   => "descargar_archivo",
        "global"    => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_terceros AS
        SELECT  documento_identidad AS id,
        documento_identidad AS DOCUMENTO_IDENTIDAD,
        primer_nombre AS PRIMER_NOMBRE,
        segundo_nombre AS SEGUNDO_NOMBRE,
        primer_apellido AS PRIMER_APELLIDO,
        segundo_apellido AS SEGUNDO_APELLIDO,
        razon_social AS RAZON_SOCIAL,
        IF(tipo_persona = '1' OR tipo_persona = '4',CONCAT(
            primer_nombre,' ',
            IF (segundo_nombre IS NOT NULL AND segundo_nombre != '',CONCAT(segundo_nombre,' '),''),
            primer_apellido,' ',
            IF (segundo_apellido IS NOT NULL AND segundo_apellido != '',segundo_apellido,'')
        ),
        razon_social) AS NOMBRE_COMPLETO
        FROM
            job_terceros
        WHERE
            documento_identidad != '0'
            ORDER BY primer_nombre, razon_social;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_terceros AS
        SELECT  documento_identidad AS id,
        documento_identidad AS DOCUMENTO_IDENTIDAD,
        primer_nombre AS PRIMER_NOMBRE,
        segundo_nombre AS SEGUNDO_NOMBRE,
        primer_apellido AS PRIMER_APELLIDO,
        segundo_apellido AS SEGUNDO_APELLIDO,
        razon_social AS RAZON_SOCIAL,
        IF(tipo_persona = '1' OR tipo_persona = '4',CONCAT(
            primer_nombre,' ',
            IF (segundo_nombre IS NOT NULL AND segundo_nombre != '',CONCAT(segundo_nombre,' '),''),
            primer_apellido,' ',
            IF (segundo_apellido IS NOT NULL AND segundo_apellido != '',segundo_apellido,'')
        ),
        razon_social) AS NOMBRE_COMPLETO
        FROM job_terceros
        WHERE documento_identidad != '0'
        ORDER BY primer_nombre, razon_social;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_terceros AS
        SELECT  job_terceros.documento_identidad AS id,
                CONCAT(
                job_terceros.documento_identidad, ', ',
                    if(job_terceros.primer_nombre is not null, CONCAT(job_terceros.primer_nombre, ' '), ''),
                    if(job_terceros.segundo_nombre is not null, CONCAT(job_terceros.segundo_nombre, ' '), ''),
                    if(job_terceros.primer_apellido is not null, CONCAT(job_terceros.primer_apellido, ' '), ''),
                    if(job_terceros.segundo_apellido is not null, CONCAT(job_terceros.segundo_apellido, ' '), ''),
                    if(job_terceros.razon_social is not null, job_terceros.razon_social, ''),
                    '|',job_terceros.documento_identidad
                ) AS nombre

        FROM    job_terceros

        WHERE   job_terceros.documento_identidad != '0'
                ORDER BY job_terceros.primer_nombre ASC;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_terceros_juridicos_codigo_interno AS
        SELECT job_terceros.documento_identidad AS id,
                CONCAT(
                    job_terceros.documento_identidad, ', ',
                    if(job_terceros.razon_social is not null, job_terceros.razon_social, ''), '|',
                    job_terceros.documento_identidad
                ) AS nombre

        FROM    job_terceros

        WHERE   job_terceros.documento_identidad != '0'
                AND job_terceros.tipo_persona != '1'
                ORDER BY job_terceros.razon_social ASC;"
    )
);

// Sentencia para la creación de la vista requerida
/***
    DROP TABLE IF EXISTS job_menu_terceros;
    DROP TABLE IF EXISTS job_buscador_terceros;
    DROP TABLE IF EXISTS job_seleccion_terceros;
    DROP TABLE IF EXISTS job_seleccion_terceros_juridicos_codigo_interno;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_terceros AS
    SELECT  job_terceros.documento_identidad AS id,
            job_terceros.documento_identidad AS DOCUMENTO_IDENTIDAD,
            job_terceros.primer_nombre AS PRIMER_NOMBRE,
            job_terceros.segundo_nombre AS SEGUNDO_NOMBRE,
            job_terceros.primer_apellido AS PRIMER_APELLIDO,
            job_terceros.segundo_apellido AS SEGUNDO_APELLIDO,
            IF (job_terceros.razon_social IS NOT NULL,
                job_terceros.razon_social,
                CONCAT(
                    job_terceros.primer_nombre,
                    ' ',
                    if(job_terceros.segundo_nombre is not null, CONCAT(job_terceros.segundo_nombre, ' '), ''),
                    job_terceros.primer_apellido,
                    ' ',
                    if(job_terceros.segundo_apellido is not null,job_terceros.segundo_apellido, '')
                )
            ) AS NOMBRE_COMPLETO

    FROM    job_terceros

    WHERE   job_terceros.documento_identidad != '0'
            ORDER BY job_terceros.primer_nombre ASC;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_terceros AS
    SELECT  job_terceros.documento_identidad AS id,
            job_terceros.documento_identidad AS DOCUMENTO_IDENTIDAD,
            job_terceros.primer_nombre AS PRIMER_NOMBRE,
            job_terceros.segundo_nombre AS SEGUNDO_NOMBRE,
            job_terceros.primer_apellido AS PRIMER_APELLIDO,
            job_terceros.segundo_apellido AS SEGUNDO_APELLIDO,
            IF (job_terceros.razon_social IS NOT NULL,
                job_terceros.razon_social,
                CONCAT(
                    job_terceros.primer_nombre,
                    ' ',
                    if(job_terceros.segundo_nombre is not null, CONCAT(job_terceros.segundo_nombre, ' '), ''),
                    job_terceros.primer_apellido,
                    ' ',
                    if(job_terceros.segundo_apellido is not null,job_terceros.segundo_apellido, '')
                )
            ) AS NOMBRE_COMPLETO

    FROM    job_terceros

    WHERE   job_terceros.documento_identidad != '0'
            ORDER BY job_terceros.primer_nombre ASC;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_terceros AS
    SELECT  job_terceros.documento_identidad AS id,
            CONCAT(
            job_terceros.documento_identidad, ', ',
                if(job_terceros.primer_nombre is not null, CONCAT(job_terceros.primer_nombre, ' '), ''),
                if(job_terceros.segundo_nombre is not null, CONCAT(job_terceros.segundo_nombre, ' '), ''),
                if(job_terceros.primer_apellido is not null, CONCAT(job_terceros.primer_apellido, ' '), ''),
                if(job_terceros.segundo_apellido is not null, CONCAT(job_terceros.segundo_apellido, ' '), ''),
                if(job_terceros.razon_social is not null, job_terceros.razon_social, ''),
                '|',job_terceros.documento_identidad
            ) AS nombre

    FROM    job_terceros

    WHERE   job_terceros.documento_identidad != '0'
            ORDER BY job_terceros.primer_nombre ASC;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_terceros_juridicos_codigo_interno AS
    SELECT job_terceros.documento_identidad AS id,
            CONCAT(
                job_terceros.documento_identidad, ', ',
                if(job_terceros.razon_social is not null, job_terceros.razon_social, ''), '|',
                job_terceros.documento_identidad
            ) AS nombre

    FROM    job_terceros

    WHERE   job_terceros.documento_identidad != '0'
            AND job_terceros.tipo_persona != '1'
            ORDER BY job_terceros.razon_social ASC;
***/
?>

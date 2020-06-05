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
$borrarSiempre = false;

// Definición de tablas
$tablas ["empresas"] = array(
    "codigo"                                => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código interno de la empresa'",
    "razon_social"                          => "VARCHAR(60) NOT NULL COMMENT 'Razon social que identifica la empresa'",
    "nombre_corto"                          => "CHAR(10) NOT NULL COMMENT 'Nombre corto que identifica la empresa en consultas'",
    "fecha_cierre"                          => "DATE DEFAULT NULL COMMENT 'Fecha que estuvo activa la empresa'",
    "activo"                                => "ENUM('0','1') NOT NULL DEFAULT '1' COMMENT 'Indicador de estado de la empresa: 0=Inactiva, 1=Activa'",
    //tabla terceros
    "documento_identidad_tercero"           => "VARCHAR(12) NOT NULL COMMENT 'Nit o docuemtno identidad de la empresa en la tabla terceros'",
    //**************
    "regimen"                               => "ENUM('1','2') DEFAULT '1' COMMENT '1->Regimen comun 2->Regimen simplificado'",
    "retiene_fuente"                        => "ENUM('0','1') DEFAULT '0' COMMENT 'Realiza retencion en la fuente 0->No 1->Si'",
    "autoretenedor"                         => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT 'Autoretenedor 0->No 1->Si'",
    //tabla resoluciones_retefuente
    "numero_retefuente"                     => "VARCHAR(255) NOT NULL COMMENT 'Numero de la resolución'",
    //*********************
    "retiene_iva"                           => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT 'Retiene IVA 0->No 1->Si'",
    "retiene_ica"                           => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT 'Retiene ICA 0->No 1->Si'",
    "autoretenedor_ica"                     => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT 'Autoretenedor ICA 0->No 1->Si'",
    //tabla resoluciones_ica
    //"numero_resolucion_reteica"           => "VARCHAR(255) NOT NULL COMMENT 'Numero de la resolución'",
    //*********************
    "gran_contribuyente"                    => "ENUM('0','1') NOT NULL COMMENT 'Empresa esta catalogada como gran contribuyente por la DIAN 0->No 1-Si'",
    //tabla resoluciones_gran_contribuyente
    "numero_resolucion_contribuyente"       => "VARCHAR(255) NOT NULL COMMENT 'Numero de la resolución del gran contribuyente'",
    //************************************
    //actividades_economicas
    "codigo_iso_primaria"                   => "VARCHAR(2) NOT NULL COMMENT 'Llave principal de la tabla paises'",
    "codigo_dane_departamento_primaria"     => "VARCHAR(2) NOT NULL COMMENT 'Código DANE'",
    "codigo_dane_municipio_primaria"        => "VARCHAR(3) NOT NULL COMMENT 'Código DANE'",
    "codigo_dian_primaria"                  => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'id de la tabla actividades economicas DIAN'",
    "codigo_actividad_municipio_primaria"   => "INT(5) UNSIGNED ZEROFILL NULL COMMENT'Codigo de la actividad economica del municipio'",
    //**********************
    //actividades_economicas
    "codigo_iso_secundaria"                 => "VARCHAR(2) NOT NULL COMMENT 'Llave principal de la tabla paises'",
    "codigo_dane_departamento_secundaria"   => "VARCHAR(2) NOT NULL COMMENT 'Código DANE'",
    "codigo_dane_municipio_secundaria"      => "VARCHAR(3) NOT NULL COMMENT 'Código DANE'",
    "codigo_dian_secundaria"                => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'id de la tabla actividades economicas DIAN'",
    "codigo_actividad_municipio_secundaria" => "INT(5) UNSIGNED ZEROFILL NULL COMMENT'Codigo de la actividad economica del municipio'"

);

// Definición de llaves primarias
$llavesPrimarias["empresas"] = "codigo";

//  Definición de llaves foráneas
$llavesForaneas["empresas"] = array(
    array(
        // Nombre de la llave
        "empresas_tercero",
        // Nombre del campo clave de la tabla local
        "documento_identidad_tercero",
        // Nombre de la tabla relacionada
        "terceros",
        // Nombre del campo clave en la tabla relacionada
        "documento_identidad"
    ),
    array(
        // Nombre de la llave
        "empresas_actividad_principal",
        // Nombre del campo clave de la tabla local
        "codigo_iso_primaria,codigo_dane_departamento_primaria,codigo_dane_municipio_primaria,codigo_dian_primaria,codigo_actividad_municipio_primaria",
        // Nombre de la tabla relacionada
        "actividades_economicas",
        // Nombre del campo clave en la tabla relacionada
        "codigo_iso,codigo_dane_departamento,codigo_dane_municipio,codigo_dian,codigo_actividad_municipio"
    ),
    array(
        // Nombre de la llave
        "empresas_actividad_secundaria",
        // Nombre del campo clave de la tabla local
        "codigo_iso_secundaria,codigo_dane_departamento_secundaria,codigo_dane_municipio_secundaria,codigo_dian_secundaria,codigo_actividad_municipio_secundaria",
        // Nombre de la tabla relacionada
        "actividades_economicas",
        // Nombre del campo clave en la tabla relacionada
        "codigo_iso,codigo_dane_departamento,codigo_dane_municipio,codigo_dian,codigo_actividad_municipio"
    ),
    array(
        // Nombre de la llave
        "empresas_resolucion_contribuyente",
        // Nombre del campo clave de la tabla local
        "numero_resolucion_contribuyente",
        // Nombre de la tabla relacionada
        "resoluciones_gran_contribuyente",
        // Nombre del campo clave en la tabla relacionada
        "numero_resolucion"
    ),
    array(
        // Nombre de la llave
        "empresas_resolucion_retefuente",
        // Nombre del campo clave de la tabla local
        "numero_retefuente",
        // Nombre de la tabla relacionada
        "resoluciones_retefuente",
        // Nombre del campo clave en la tabla relacionada
        "numero_retefuente"
    )
);

$registros["empresas"] = array(
    array(
        "codigo"                                => "0",
        "razon_social"                          => "",
        "nombre_corto"                          => "",
        "fecha_cierre"                          => "0000-00-00",
        "activo"                                => "1",
        "documento_identidad_tercero"           => "",
        "regimen"                               => "1",
        "retiene_fuente"                        => "0",
        "autoretenedor"                         => "0",
        "numero_retefuente"                     => "0",
        "retiene_iva"                           => "0",
        "retiene_ica"                           => "0",
        "autoretenedor_ica"                     => "0",
        "gran_contribuyente"                    => "0",
        "numero_resolucion_contribuyente"       => "0",
        "codigo_iso_primaria"                   => "",
        "codigo_dane_departamento_primaria"     => "",
        "codigo_dane_municipio_primaria"        => "",
        "codigo_dian_primaria"                  => "",
        "codigo_iso_secundaria"                 => "",
        "codigo_dane_departamento_secundaria"   => "",
        "codigo_dane_municipio_secundaria"      => "",
        "codigo_dian_secundaria"                => "",
        "codigo_actividad_municipio_primaria"   => "0",
        "codigo_actividad_municipio_secundaria" => "0"
    )
);

$registros["componentes"] = array(
    array(
        "id"           => "GESTEMPR",
        "padre"        => "SUBMESTC",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "1",
        "orden"        => "100",
        "carpeta"      => "empresas",
        "archivo"      => "menu",
        "global"       => "0",
        "requiere_item" => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "ADICEMPR",
        "padre"        => "GESTEMPR",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "0",
        "orden"        => "20",
        "carpeta"      => "empresas",
        "archivo"      => "adicionar",
        "global"       => "0",
        "requiere_item" => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "CONSEMPR",
        "padre"        => "GESTEMPR",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "0",
        "orden"        => "20",
        "carpeta"      => "empresas",
        "archivo"      => "consultar",
        "global"       => "0",
        "requiere_item" => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "MODIEMPR",
        "padre"        => "GESTEMPR",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "0",
        "orden"        => "20",
        "carpeta"      => "empresas",
        "archivo"      => "modificar",
        "global"       => "0",
        "requiere_item" => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "ELIMEMPR",
        "padre"        => "GESTEMPR",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "0",
        "orden"        => "20",
        "carpeta"      => "empresas",
        "archivo"      => "eliminar",
        "global"       => "0",
        "requiere_item" => "0",
        "tipo_enlace"  => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_empresas AS
        SELECT  job_empresas.codigo AS id,
                job_empresas.codigo AS CODIGO,
                job_empresas.razon_social AS RAZON_SOCIAL,
                if(job_empresas.regimen = 1, 'Comun', 'Simplificado') AS REGIMEN,
                job_terceros.documento_identidad AS TERCERO,
                CONCAT('ESTADO_',job_empresas.activo) AS ACTIVO

         FROM   job_empresas,
                job_terceros

         WHERE  job_empresas.documento_identidad_tercero = job_terceros.documento_identidad
                AND job_empresas.codigo != 0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_empresas AS
        SELECT  job_empresas.codigo AS id,
                job_empresas.codigo AS codigo, job_empresas.razon_social AS razon_social, job_empresas.nombre_corto AS nombre_corto,
                job_terceros.documento_identidad AS tercero

         FROM   job_empresas,
                job_terceros

         WHERE  job_empresas.documento_identidad_tercero = job_terceros.documento_identidad
                AND job_empresas.codigo != 0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_empresas AS
        SELECT job_empresas.codigo AS id,
            CONCAT(
                job_empresas.razon_social,
                '|', job_empresas.codigo
            ) AS nombre
        FROM job_empresas
        WHERE   job_empresas.codigo != 0 ORDER BY job_empresas.razon_social ASC;"
    )
);

/***
    DROP TABLE IF EXISTS job_menu_empresas;
    DROP TABLE IF EXISTS job_buscador_empresas;
    DROP TABLE IF EXISTS job_seleccion_empresas;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_empresas AS
    SELECT  job_empresas.codigo AS id,
            job_empresas.codigo AS CODIGO,
            job_empresas.razon_social AS RAZON_SOCIAL,
            if(job_empresas.activo = 0, 'Inactiva','Activa') AS ACTIVO,
            if(job_empresas.regimen = 1, 'Comun', 'Simplificado') AS REGIMEN,
            job_terceros.documento_identidad AS TERCERO

     FROM   job_empresas,
            job_terceros

     WHERE              job_empresas.documento_identidad_tercero = job_terceros.documento_identidad
            AND job_empresas.codigo != 0;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_empresas AS
    SELECT  job_empresas.codigo AS id,
            job_empresas.codigo AS codigo, job_empresas.razon_social AS razon_social, job_empresas.nombre_corto AS nombre_corto,
            CONCAT(
                IF(job_terceros.primer_nombre IS NOT NULL,job_terceros.primer_nombre,''),' ',
                IF(job_terceros.segundo_nombre IS NOT NULL,job_terceros.segundo_nombre,''),' ',
                IF(job_terceros.primer_apellido IS NOT NULL,job_terceros.primer_apellido,''),' ',
                IF(job_terceros.segundo_apellido IS NOT NULL,job_terceros.segundo_apellido,''),' ',
                IF(job_terceros.razon_social IS NOT NULL,job_terceros.razon_social,'')
            ) AS nombre_completo

     FROM   job_empresas,
            job_terceros

     WHERE  job_empresas.documento_identidad_tercero = job_terceros.documento_identidad
            AND job_empresas.codigo != 0;



     CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_empresas AS
     SELECT job_empresas.codigo AS id,
            CONCAT(
                job_empresas.razon_social,
                '|', job_empresas.codigo
            ) AS nombre

     FROM   job_empresas

     WHERE  job_empresas.codigo != 0 ORDER BY job_empresas.razon_social ASC;
**/
?>

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

/*** Eliminar la tabla y crearla de nuevo cada vez que se ejecute el script de creación ***/
$borrarSiempre = false;

/*** Definición de tablas ***/
$tablas ["cuentas_bancarias"] = array(//No se esta seguro de si la cuenta debiera llevar la sucursal el banco
    "codigo_sucursal"          => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la sucursal a la que pertenece la cuenta'",
    "codigo_tipo_documento"    => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo del tipo de documento'",
    ////////////////LLAVE DE SUCURSALES BANCOS//////////////
    "codigo_sucursal_banco"    => "SMALLINT(2) UNSIGNED ZEROFILL NOT NULL COMMENT 'codigo de la tabla bancos'",
    "codigo_iso"               => "VARCHAR(2) NOT NULL COMMENT 'Llave principal de la tabla paises'",
    "codigo_dane_departamento" => "VARCHAR(2) NOT NULL COMMENT 'Código DANE'",
    "codigo_dane_municipio"    => "VARCHAR(3) NOT NULL COMMENT 'Código DANE'",
    "codigo_banco"             => "SMALLINT(2) UNSIGNED ZEROFILL NOT NULL COMMENT 'id de la tabla bancos'",
    ////////////////////////////////////////////////////////
    "numero"                   => "VARCHAR(30) NOT NULL COMMENT 'Numero de la cuenta'",
    "codigo_plan_contable"     => "VARCHAR(15) NOT NULL COMMENT 'Código definido por el PUC(Plan unico de cuentas)'",
    /////////////LLAVE DEL AUXILIAR CONTABLE////////////////
    "codigo_empresa_auxiliar"  => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa para la llave de auxiliares'",
    "codigo_anexo_contable"    => "VARCHAR(3) NOT NULL COMMENT 'Código del anexo para la llave de auxiliares'",
    "codigo_auxiliar_contable" => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código del auxiliar si aplica'",
    ////////////////////////////////////////////////////////
    "estado"                   => "ENUM('0','1') NOT NULL DEFAULT '1' COMMENT 'Estado de la cuenta bancaria: 1->Activa, 2->Inactiva'",
    "plantilla"                => "TEXT NOT NULL COMMENT 'Plantilla para impresion de cheques'",
    "tipo_cuenta"              => "ENUM('1','2') NOT NULL COMMENT 'Tipo de cuenta: 1->Cuenta de ahorro, 2->Cuenta corriente'"
);

/*** Definición de llaves primarias ***/
$llavesPrimarias["cuentas_bancarias"] = "codigo_sucursal,codigo_tipo_documento,codigo_sucursal_banco,codigo_iso,codigo_dane_departamento,codigo_dane_municipio,codigo_banco,numero";

/*** Definici{on de llaves Foraneas ***/
$llavesForaneas["cuentas_bancarias"] = array(
    array(
        /*** Nombre de la llave foranea ***/
        "cuentas_bancarias_sucursal",
        /*** Nombre del campo en la tabla actual ***/
        "codigo_sucursal",
        /*** Nombre de la tabla relacionada ***/
        "sucursales",
        /*** Nombre del campo de la tabla relacionada ***/
        "codigo"
    ),
    array(
        /*** Nombre de la llave foranea ***/
        "cuentas_bancarias_tipo_documento",
        /*** Nombre del campo en la tabla actual ***/
        "codigo_tipo_documento",
        /*** Nombre de la tabla relacionada ***/
        "tipos_documentos",
        /*** Nombre del campo de la tabla relacionada ***/
        "codigo"
    ),
    array(
        /*** Nombre de la llave foranea ***/
        "cuentas_bancarias_banco",
        /*** Nombre del campo en la tabla actual ***/
        "codigo_banco",
        /*** Nombre de la tabla relacionada ***/
        "bancos",
        /*** Nombre del campo de la tabla relacionada ***/
        "codigo"
    ),
    array(
        /*** Nombre de la llave foranea ***/
        "cuentas_bancarias_sucursal_banco",
        /*** Nombre del campo en la tabla actual ***/
        "codigo_sucursal_banco,codigo_banco,codigo_iso,codigo_dane_departamento,codigo_dane_municipio",
        /*** Nombre de la tabla relacionada ***/
        "sucursales_bancos",
        /*** Nombre del campo de la tabla relacionada ***/
        "codigo,codigo_banco,codigo_iso,codigo_dane_departamento,codigo_dane_municipio"
    ),
    array(
        /*** Nombre de la llave foranea ***/
        "cuentas_bancarias_cuenta",
        /*** Nombre del campo en la tabla actual ***/
        "codigo_plan_contable",
        /*** Nombre de la tabla relacionada ***/
        "plan_contable",
        /*** Nombre del campo de la tabla relacionada ***/
        "codigo_contable"
    ),
    array(
        /*** Nombre de la llave foranea ***/
        "cuentas_bancarias_auxiliar_contable",
        /*** Nombre del campo en la tabla actual ***/
        "codigo_empresa_auxiliar,codigo_anexo_contable,codigo_auxiliar_contable",
        /*** Nombre de la tabla relacionada ***/
        "auxiliares_contables",
        /*** Nombre del campo de la tabla relacionada ***/
        "codigo_empresa,codigo_anexo_contable,codigo"
    )
);

/*** Insertar registros iniciales ***/
$registros["cuentas_bancarias"] = array(
    array(
        "codigo_sucursal"          => "0",
        "codigo_tipo_documento"    => "0",
        "codigo_sucursal_banco"    => "0",
        "codigo_iso"               => "",
        "codigo_dane_departamento" => "",
        "codigo_dane_municipio"    => "",
        "codigo_banco"             => "0",
        "numero"                   => "",
        "codigo_plan_contable"     => "",
        "codigo_empresa_auxiliar"  => "0",
        "codigo_anexo_contable"    => "",
        "codigo_auxiliar_contable" => "0",
        "estado"                   => "0",
        "plantilla"                => ""
    )
);

/*** Inserción de datos iniciales ***/
$registros["componentes"] = array(
    array(
        "id"              => "GESTCUBA",
        "padre"           => "SUBMFINA",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "1",
        "orden"           => "0020",
        "carpeta"         => "cuentas_bancarias",
        "archivo"         => "menu",
        "requiere_item"   => "1",
        "tabla_principal" => "cuentas_bancarias",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "ADICCUBA",
        "padre"           => "GESTCUBA",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "0",
        "orden"           => "10",
        "carpeta"         => "cuentas_bancarias",
        "archivo"         => "adicionar",
        "requiere_item"   => "0",
        "tabla_principal" => "cuentas_bancarias",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "CONSCUBA",
        "padre"           => "GESTCUBA",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "0",
        "orden"           => "20",
        "carpeta"         => "cuentas_bancarias",
        "archivo"         => "consultar",
        "requiere_item"   => "1",
        "tabla_principal" => "cuentas_bancarias",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "MODICUBA",
        "padre"           => "GESTCUBA",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "0",
        "orden"           => "30",
        "carpeta"         => "cuentas_bancarias",
        "archivo"         => "modificar",
        "requiere_item"   => "1",
        "tabla_principal" => "cuentas_bancarias",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "ELIMCUBA",
        "padre"           => "GESTCUBA",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "0",
        "orden"           => "40",
        "carpeta"         => "cuentas_bancarias",
        "archivo"         => "eliminar",
        "requiere_item"   => "1",
        "tabla_principal" => "cuentas_bancarias",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "LISTCUBA",
        "padre"           => "GESTCUBA",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "0",
        "orden"           => "50",
        "carpeta"         => "cuentas_bancarias",
        "archivo"         => "listar",
        "requiere_item"   => "1",
        "tabla_principal" => "cuentas_bancarias",
        "tipo_enlace"     => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_cuentas_bancarias AS
        SELECT CONCAT(cb.codigo_sucursal,'|',cb.codigo_tipo_documento,'|',cb.codigo_sucursal_banco,'|',cb.codigo_iso,'|',cb.codigo_dane_departamento,'|',cb.codigo_dane_municipio,'|',cb.codigo_banco,'|',cb.numero) AS id,
            s.nombre AS SUCURSAL,
            b.descripcion AS BANCO,
            sb.nombre_sucursal AS SUCURSALES_BANCOS,
            cb.numero AS NUMERO,
            td.descripcion AS TIPO_DOCUMENTO
        FROM  job_cuentas_bancarias AS cb,
            job_bancos AS b,
            job_sucursales_bancos AS sb,
            job_tipos_documentos AS td,
            job_sucursales AS s
        WHERE  cb.codigo_banco = b.codigo
            AND s.codigo = cb.codigo_sucursal
            AND cb.codigo_sucursal_banco = sb.codigo
            AND cb.codigo_banco = sb.codigo_banco
            AND td.codigo = cb.codigo_tipo_documento
            AND cb.numero != \"\";"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_cuentas_bancarias AS
        SELECT CONCAT(job_cuentas_bancarias.codigo_sucursal,'|',job_cuentas_bancarias.codigo_tipo_documento,'|',job_cuentas_bancarias.codigo_sucursal_banco,'|',job_cuentas_bancarias.codigo_iso,'|',job_cuentas_bancarias.codigo_dane_departamento,'|',job_cuentas_bancarias.codigo_dane_municipio,'|',job_cuentas_bancarias.codigo_banco,'|',job_cuentas_bancarias.numero) AS id,
            CONCAT(job_cuentas_bancarias.codigo_empresa_auxiliar,'|',job_cuentas_bancarias.codigo_anexo_contable,'|',job_cuentas_bancarias.codigo_auxiliar_contable) AS id_auxiliar,
            job_sucursales.codigo AS id_sucursal,
            job_tipos_documentos.codigo AS id_documento,
            job_sucursales.nombre AS SUCURSAL,
            job_bancos.descripcion AS BANCO,
            job_sucursales_bancos.nombre_sucursal AS SUCURSALES_BANCOS,
            job_cuentas_bancarias.numero AS NUMERO,
            job_tipos_documentos.descripcion AS TIPO_DOCUMENTO,
            job_cuentas_bancarias.codigo_plan_contable AS codigo_plan_contable
        FROM   job_cuentas_bancarias,
            job_bancos,
            job_sucursales_bancos,
            job_tipos_documentos,
            job_sucursales
        WHERE  job_cuentas_bancarias.codigo_banco = job_bancos.codigo
            AND job_sucursales.codigo = job_cuentas_bancarias.codigo_sucursal
            AND job_cuentas_bancarias.codigo_sucursal_banco = job_sucursales_bancos.codigo
            AND job_cuentas_bancarias.codigo_banco = job_sucursales_bancos.codigo_banco
            AND job_tipos_documentos.codigo = job_cuentas_bancarias.codigo_tipo_documento
            AND job_cuentas_bancarias.numero != \"\";"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_listado_cuentas_bancarias AS
            SELECT  s.nombre AS nombre_almacen,
                    s.codigo AS codigo_almacen,
                    b.descripcion AS nombre_banco,
                    sb.nombre_sucursal AS nombre_sucursal,
                    sb.codigo AS codigo_sucursal,
                    sb.codigo_banco AS codigo_banco,
                    sb.codigo_iso AS codigo_iso,
                    sb.codigo_dane_departamento AS codigo_dane_departamento,
                    sb.codigo_dane_municipio AS codigo_dane_municipio,
                    cb.numero AS numero,
                    td.descripcion AS tipo_documento,
                    CONCAT(pc.codigo_contable,'-',pc.descripcion) AS cuenta,
                    ac.descripcion AS auxiliar,
                    cb.estado AS estado
            FROM    job_cuentas_bancarias AS cb, job_sucursales AS s,
                    job_tipos_documentos AS td, job_bancos AS b,
                    job_sucursales_bancos AS sb, job_municipios AS m,
                    job_plan_contable AS pc, job_auxiliares_contables AS ac
            WHERE   cb.numero != \"\" AND cb.codigo_sucursal = s.codigo AND
                    cb.codigo_tipo_documento = td.codigo AND cb.codigo_banco = b.codigo AND
                    cb.codigo_sucursal_banco = sb.codigo AND cb.codigo_banco = sb.codigo_banco AND
                    cb.codigo_iso = sb.codigo_iso AND cb.codigo_dane_departamento = sb.codigo_dane_departamento AND
                    cb.codigo_dane_municipio = sb.codigo_dane_municipio AND cb.codigo_iso = m.codigo_iso AND
                    cb.codigo_dane_departamento = m.codigo_dane_departamento AND cb.codigo_dane_municipio = m.codigo_dane_municipio AND
                    cb.codigo_plan_contable = pc.codigo_contable AND cb.codigo_empresa_auxiliar = ac.codigo_empresa AND
                    cb.codigo_anexo_contable = ac.codigo_anexo_contable AND cb.codigo_auxiliar_contable = ac.codigo
            ORDER BY b.codigo, sb.codigo,pc.codigo_contable"
    )
);

/*** Sentencia para la creación de la vista requerida ***/
/***
    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_cuentas_bancarias AS
    SELECT CONCAT(cb.codigo_sucursal,'|',cb.codigo_tipo_documento,'|',cb.codigo_sucursal_banco,'|',cb.codigo_iso,'|',cb.codigo_dane_departamento,'|',cb.codigo_dane_municipio,'|',cb.codigo_banco,'|',cb.numero) AS id,
        s.nombre AS SUCURSAL,
        b.descripcion AS BANCO,
        sb.nombre_sucursal AS SUCURSALES_BANCOS,
        cb.numero AS NUMERO,
        td.descripcion AS TIPO_DOCUMENTO

    FROM  job_cuentas_bancarias AS cb,
        job_bancos AS b,
        job_sucursales_bancos AS sb,
        job_tipos_documentos AS td,
        job_sucursales AS s

    WHERE  cb.codigo_banco = b.codigo
        AND s.codigo = cb.codigo_sucursal
        AND cb.codigo_sucursal_banco = sb.codigo
        AND cb.codigo_banco = sb.codigo_banco
        AND td.codigo = cb.codigo_tipo_documento
        AND cb.numero != "";



    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_cuentas_bancarias AS
    SELECT CONCAT(job_cuentas_bancarias.codigo_sucursal,'|',job_cuentas_bancarias.codigo_tipo_documento,'|',job_cuentas_bancarias.codigo_sucursal_banco,'|',job_cuentas_bancarias.codigo_iso,'|',job_cuentas_bancarias.codigo_dane_departamento,'|',job_cuentas_bancarias.codigo_dane_municipio,'|',job_cuentas_bancarias.codigo_banco,'|',job_cuentas_bancarias.numero) AS id,
        CONCAT(job_cuentas_bancarias.codigo_empresa_auxiliar,'|',job_cuentas_bancarias.codigo_anexo_contable,'|',job_cuentas_bancarias.codigo_auxiliar_contable) AS id_auxiliar,
        job_sucursales.codigo AS id_sucursal,
        job_tipos_documentos.codigo AS id_documento,
        job_sucursales.nombre AS SUCURSAL,
        job_bancos.descripcion AS BANCO,
        job_sucursales_bancos.nombre_sucursal AS SUCURSALES_BANCOS,
        job_cuentas_bancarias.numero AS NUMERO,
        job_tipos_documentos.descripcion AS TIPO_DOCUMENTO,
        job_cuentas_bancarias.codigo_plan_contable AS codigo_plan_contable

    FROM   job_cuentas_bancarias,
        job_bancos,
        job_sucursales_bancos,
        job_tipos_documentos,
        job_sucursales

    WHERE  job_cuentas_bancarias.codigo_banco = job_bancos.codigo
        AND job_sucursales.codigo = job_cuentas_bancarias.codigo_sucursal
        AND job_cuentas_bancarias.codigo_sucursal_banco = job_sucursales_bancos.codigo
        AND job_cuentas_bancarias.codigo_banco = job_sucursales_bancos.codigo_banco
        AND job_tipos_documentos.codigo = job_cuentas_bancarias.codigo_tipo_documento
        AND job_cuentas_bancarias.numero != "";

***/

?>

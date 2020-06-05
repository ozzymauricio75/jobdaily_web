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
* modificarlo  bajo los t�rminos de la Licencia P�blica General GNU
* publicada por la Fundaci�n para el Software Libre, ya sea la versi�n 3
* de la Licencia, o (a su elecci�n) cualquier versi�n posterior.
*
* Este programa se distribuye con la esperanza de que sea �til, pero
* SIN GARANT�A ALGUNA; ni siquiera la garant�a impl�cita MERCANTIL o
* de APTITUD PARA UN PROP�ITO DETERMINADO. Consulte los detalles de
* la Licencia P�blica General GNU para obtener una informaci�n m�s
* detallada.
*
* Deber�a haber recibido una copia de la Licencia P�blica General GNU
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
**/

$borrarSiempre = false;

// Definici�n de tablas
$tablas["tipos_documentos"] = array(
    "codigo"             => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'C�digo asignado por el usuario'",
    "codigo_comprobante" => "SMALLINT(2) UNSIGNED ZEROFILL NOT NULL COMMENT'id de la tabla tipos comprobantes'",
    "descripcion"        => "VARCHAR(255) NOT NULL COMMENT 'Detalle que identifica el documento para su impresion'",
    "observaciones"      => "VARCHAR(255) COMMENT 'Observaciones para el tipo de documento (opcional)'",
    "abreviaturas"       => "CHAR(3) NOT NULL COMMENT 'Detalle corto que identifica el tipo de documento'",
    "tipo"               => "SMALLINT(2) NOT NULL COMMENT 'dependiendo de los m�dulos este dato permite realizar algunos controles'",
    "manejo_automatico"  => "ENUM('1','2','3','4')  DEFAULT '1' NOT NULL COMMENT '1->No tiene manejo automatico, 2->El consecutivo se maneja de manera autom�tica(se verifica en la tabla consecutivos de documento), 3-> Consecutivo por mes, 4-> Documento externo '",
    "control_titulo"     => "ENUM('0','1') DEFAULT '0' NOT NULL COMMENT '0->No imprime titulos 1->El documento imprime t�tulos'",
    "genera_cheque"      => "ENUM('0','1') DEFAULT '0' NOT NULL COMMENT '0->No genera cheques 1->El documento genera cheques'",
    "aplica_notas"       => "ENUM('0','1') DEFAULT '0' NOT NULL COMMENT 'Si el documento aplica para las notas: 0->No aplica, 1->Aplica'",
    "sentido_contable"   => "ENUM('0','1','2') DEFAULT '0' NOT NULL COMMENT 'Sentido contable del documento: 0->No aplica, 1->Debito, 2->Credito'",
    "sentido_inventario" => "ENUM('0','1','2') DEFAULT '0' NOT NULL COMMENT 'Sentido para el inventario del documento: 0->No aplica, 1->Entrada, 2->Salida'",
    "equivalencia"       => "VARCHAR(25) COMMENT 'Codigo o identificaci�n del tipo de documento un sistema anterior si se migrara la informaci�n'"
);

// Definici�n de llaves primarias
$llavesPrimarias["tipos_documentos"] = "codigo";

 // Definici�n de campos �nicos
$llavesUnicas["tipos_documentos"] = array(
    "descripcion",
    "abreviaturas"
);

// Definici�n de llaves for�neas
$llavesForaneas["tipos_documentos"] = array(
    array(
        // Nombre de la llave
        "tipos_documentos_id_comprobante",
        // Nombre del campo clave de la tabla local
        "codigo_comprobante",
        // Nombre de la tabla relacionada
        "tipos_comprobantes",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    )
);

// Inserci�n de datos iniciales
$registros["tipos_documentos"] = array(
    array(
        "codigo"             => "0",
        "codigo_comprobante" => "0",
        "descripcion"        => "",
        "observaciones"      => "",
        "abreviaturas"       => "0",
        "tipo"               => "0",
        "manejo_automatico"  => "1",
        "control_titulo"     => "0",
        "genera_cheque"      => "0"
    )
);

// Inserci�n de datos iniciales
$registros["componentes"] = array(
    array(
        "id"              => "GESTTIDO",
        "padre"           => "SUBMINCO",
        "id_modulo"       => "CONTABILIDAD",
        "orden"           => "0025",
        "visible"         => "1",
        "carpeta"         => "tipos_documentos",
        "archivo"         => "menu",
        "requiere_item"   => "1",
        "tabla_principal" => "tipos_documentos",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "ADICTIDO",
        "padre"           => "GESTTIDO",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "0",
        "orden"           => "0005",
        "carpeta"         => "tipos_documentos",
        "archivo"         => "adicionar",
        "requiere_item"   => "0",
        "tabla_principal" => "tipos_documentos",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "CONSTIDO",
        "padre"           => "GESTTIDO",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "0",
        "orden"           => "0010",
        "carpeta"         => "tipos_documentos",
        "archivo"         => "consultar",
        "requiere_item"   => "1",
        "tabla_principal" => "tipos_documentos",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "MODITIDO",
        "padre"           => "GESTTIDO",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "0",
        "orden"           => "0015",
        "carpeta"         => "tipos_documentos",
        "archivo"         => "modificar",
        "requiere_item"   => "1",
        "tabla_principal" => "tipos_documentos",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "ELIMTIDO",
        "padre"           => "GESTTIDO",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "0",
        "orden"           => "0020",
        "carpeta"         => "tipos_documentos",
        "archivo"         => "eliminar",
        "requiere_item"   => "1",
        "tabla_principal" => "tipos_documentos",
        "tipo_enlace"     => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_tipos_documentos AS
        SELECT  job_tipos_documentos.codigo AS id,
                job_tipos_comprobantes.descripcion AS COMPROBANTE,
                job_tipos_documentos.codigo AS CODIGO,
                job_tipos_documentos.descripcion AS DESCRIPCION,
                job_tipos_documentos.observaciones AS OBSERVACIONES,
                job_tipos_documentos.abreviaturas AS ABREVIATURAS
        FROM    job_tipos_documentos,job_tipos_comprobantes
        WHERE   job_tipos_documentos.codigo != '0' AND
                job_tipos_comprobantes.codigo = job_tipos_documentos.codigo_comprobante;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_tipos_documentos AS
        SELECT  job_tipos_documentos.codigo AS id,
                job_tipos_comprobantes.descripcion AS comprobante,
                job_tipos_documentos.codigo AS codigo,
                job_tipos_documentos.descripcion AS descripcion,
                job_tipos_documentos.observaciones AS observaciones,
                job_tipos_documentos.abreviaturas AS abreviaturas
        FROM    job_tipos_documentos,job_tipos_comprobantes
        WHERE   job_tipos_documentos.codigo != '0' AND
                job_tipos_documentos.codigo_comprobante = job_tipos_comprobantes.codigo;"
    )
);

/***
    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_tipos_documentos AS
    SELECT  job_tipos_documentos.codigo AS id,
            job_tipos_comprobantes.descripcion AS COMPROBANTE,
            job_tipos_documentos.codigo AS CODIGO,
            job_tipos_documentos.descripcion AS DESCRIPCION,
            job_tipos_documentos.observaciones AS OBSERVACIONES,
            job_tipos_documentos.abreviaturas AS ABREVIATURAS

    FROM    job_tipos_documentos,job_tipos_comprobantes

    WHERE   job_tipos_documentos.codigo != '0' AND
            job_tipos_comprobantes.codigo = job_tipos_documentos.codigo_comprobante;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_tipos_documentos AS
    SELECT  job_tipos_documentos.codigo AS id,
            job_tipos_comprobantes.descripcion AS comprobante,
            job_tipos_documentos.codigo AS codigo,
            job_tipos_documentos.descripcion AS descripcion,
            job_tipos_documentos.observaciones AS observaciones,
            job_tipos_documentos.abreviaturas AS abreviaturas

    FROM    job_tipos_documentos,job_tipos_comprobantes

    WHERE   job_tipos_documentos.codigo != '0' AND
            job_tipos_documentos.codigo_comprobante = job_tipos_comprobantes.codigo;
***/
?>

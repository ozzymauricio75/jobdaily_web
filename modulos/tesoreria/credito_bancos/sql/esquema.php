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
$tablas["creditos_bancos"]  = array(
    "codigo"                      => "INT(9) UNSIGNED ZEROFILL AUTO_INCREMENT NOT NULL COMMENT 'Codigo interno asignado por el usuario'",
    "codigo_banco"                => "SMALLINT(2) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la tabla movimientos de tesoreria'",
    "codigo_proyecto"             => "INT(9) UNSIGNED ZEROFILL NULL COMMENT 'Codigo interno del proyecto'",
    "numero_credito"              => "VARCHAR(30) NULL COMMENT 'Número del credito'",
    "valor_credito"               => "DECIMAL(15,2) UNSIGNED  NOT NULL COMMENT 'Valor del credito'",
    "tasa_mensual"                => "DECIMAL(7,2) UNSIGNED  NOT NULL COMMENT 'Porcentaje de tasa mensual'",
    "fecha_credito"               => "DATE NOT NULL COMMENT 'Fecha ingreso al sistema'",
    "periodos"                    => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'cantidad de cuotas del credito'",
    "valor_cuota"                 => "DECIMAL(15,2) UNSIGNED  NOT NULL COMMENT 'Valor de la cuota mensual'",
    "estado_credito"              => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT '0->Pagado 1->Por pagar'",
    "observaciones"               => "VARCHAR(255) COMMENT 'Observacion general del credito'"
);

$tablas["cuotas_creditos_bancos"] = array(
    "codigo"                      => "INT(9) UNSIGNED ZEROFILL AUTO_INCREMENT NOT NULL COMMENT 'Codigo interno asignado por el usuario'",
    "codigo_credito"              => "INT(9) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo del credito del banco'",
    "numero_cuota"                => "INT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'numero de la cuota credito'",
    "interes"                     => "DECIMAL(15,2) UNSIGNED  NOT NULL COMMENT 'interes del credito'",
    "interes_pagado"              => "DECIMAL(15,2) UNSIGNED  NOT NULL COMMENT 'interes pagado del credito'",
    "abono_capital"               => "DECIMAL(15,2) UNSIGNED  NOT NULL COMMENT 'abono capital del credito'",
    "abono_capital_pagado"        => "DECIMAL(15,2) UNSIGNED  NOT NULL COMMENT 'abono capital pagado del credito'",
    "saldo_capital"               => "DECIMAL(15,2) UNSIGNED  NOT NULL COMMENT 'saldo de capital del credito'",
    "saldo_capital_pagado"        => "DECIMAL(15,2) UNSIGNED  NOT NULL COMMENT 'saldo de capital pagado del credito'",
    "observaciones"               => "VARCHAR(255) COMMENT 'Observacion general del credito'",
    "estado_cuota"                => "ENUM('0','1','2') NOT NULL DEFAULT '0' COMMENT '0->Pagado 1->Por pagar 2->Abonada'"
);

// Definición de llaves primarias
$llavesPrimarias["creditos_bancos"]        = "codigo";
$llavesPrimarias["cuotas_creditos_bancos"] = "codigo,codigo_credito";

// Definición de las llaves unicas
$llavesUnicas["creditos_bancos"] = array(
    "codigo,codigo_banco,numero_credito"
);

$llavesUnicas["cuotas_creditos_bancos"] = array(
    "codigo,codigo_credito"
);

$registros["componentes"] = array(
    array(
        "id"              => "ADICCRBA",
        "padre"           => "SUBMCRED",
        "id_modulo"       => "TESORERIA",
        "visible"         => "0",
        "orden"           => "10",
        "carpeta"         => "credito_bancos",
        "archivo"         => "adicionar",
        "requiere_item"   => "0",
        "tabla_principal" => "creditos_bancos",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "CONSCRBA",
        "padre"           => "SUBMCRED",
        "id_modulo"       => "TESORERIA",
        "visible"         => "0",
        "orden"           => "20",
        "carpeta"         => "credito_bancos",
        "archivo"         => "consultar",
        "requiere_item"   => "1",
        "tabla_principal" => "creditos_bancos",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "MODICRBA",
        "padre"           => "SUBMCRED",
        "id_modulo"       => "TESORERIA",
        "visible"         => "0",
        "orden"           => "30",
        "carpeta"         => "credito_bancos",
        "archivo"         => "modificar",
        "requiere_item"   => "1",
        "tabla_principal" => "creditos_bancos",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "ELIMCRBA",
        "padre"           => "SUBMCRED",
        "id_modulo"       => "TESORERIA",
        "visible"         => "0",
        "orden"           => "40",
        "carpeta"         => "credito_bancos",
        "archivo"         => "eliminar",
        "requiere_item"   => "1",
        "tabla_principal" => "creditos_bancos",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "ANULCRBA",
        "padre"           => "SUBMCRED",
        "id_modulo"       => "TESORERIA",
        "visible"         => "0",
        "orden"           => "50",
        "carpeta"         => "credito_bancos",
        "archivo"         => "anular",
        "requiere_item"   => "1",
        "tabla_principal" => "creditos_bancos",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "REPOCRBA",
        "padre"           => "SUBMCRED",
        "id_modulo"       => "TESORERIA",
        "visible"         => "0",
        "orden"           => "60",
        "carpeta"         => "credito_bancos",
        "archivo"         => "reporte",
        "global"          => "0",
        "tipo_enlace"     => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_creditos_bancos AS
        SELECT job_creditos_bancos.codigo AS id,
            job_creditos_bancos.codigo AS CODIGO,
            job_creditos_bancos.numero_credito AS NUMERO_CREDITO,
            FORMAT(job_creditos_bancos.valor_credito,0) AS VALOR_CREDITO,
            job_creditos_bancos.fecha_credito AS FECHA_CREDITO,
            job_creditos_bancos.periodos AS PERIODOS,
            FORMAT(job_creditos_bancos.valor_cuota,0) AS VALOR_CUOTA,
            CONCAT('ESTADO_',job_creditos_bancos.estado_credito) AS ESTADO,
            job_creditos_bancos.observaciones AS OBSERVACIONES
        FROM job_creditos_bancos   
        WHERE job_creditos_bancos.codigo != 0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_creditos_bancos AS
        SELECT job_creditos_bancos.codigo AS id,
            job_creditos_bancos.codigo AS codigo,
            job_creditos_bancos.numero_credito AS numero_credito,
            FORMAT(job_creditos_bancos.valor_credito,0) AS valor_credito,
            job_creditos_bancos.fecha_credito AS fecha_credito,
            job_creditos_bancos.periodos AS periodos,
            FORMAT(job_creditos_bancos.valor_cuota,0) AS valor_cuota,
            CONCAT('ESTADO_',job_creditos_bancos.estado_credito) AS estado,
            job_creditos_bancos.observaciones AS observaciones
        FROM job_creditos_bancos   
        WHERE job_creditos_bancos.codigo != 0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_creditos_bancos AS
        SELECT  job_creditos_bancos.codigo AS id,
            CONCAT(job_creditos_bancos.numero_credito, ':',
                FORMAT(job_creditos_bancos.valor_credito,0), '|', job_creditos_bancos.codigo) AS descripcion
        FROM job_creditos_bancos
        WHERE   job_creditos_bancos.codigo > 0;"
    )
)
?>

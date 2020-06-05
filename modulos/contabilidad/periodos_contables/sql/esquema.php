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
$tablas["periodos_contables"] = array(
    "codigo_sucursal" => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la sucursal'",
    "id_modulo"       => "CHAR(32) NOT NULL COMMENT 'ID del modulo relacionado'",
    "fecha_inicio"    => "DATE NOT NULL COMMENT 'Fecha inicial del periodo contable'",
    "fecha_fin"       => "DATE NOT NULL COMMENT 'Fecha final del periodo contable'",
    "estado"          => "ENUM('0','1') NOT NULL COMMENT 'Estado del periodo contable 0->Inactivo 1->Activo'"
);

// Definición de llaves primarias
$llavesPrimarias["periodos_contables"] = "codigo_sucursal,fecha_inicio,fecha_fin,id_modulo";

// Definici{on de llaves Foraneas
$llavesForaneas["periodos_contables"] = array(
    array(
        // Nombre de la llave foranea
        "periodo_contable_sucursal",
        // Nombre del campo en la tabla actual
        "codigo_sucursal",
        // Nombre de la tabla relacionada
        "sucursales",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "periodo_contable_modulo",
        // Nombre del campo en la tabla actual
        "id_modulo",
        // Nombre de la tabla relacionada
        "modulos",
        // Nombre del campo de la tabla relacionada
        "id"
    )
);

// Inserción de datos iniciales***/
$registros["componentes"] = array(
    array(
        "id"        => "GESTPECO",
        "padre"     => "SUBMINCO",
        "id_modulo" => "CONTABILIDAD",
        "orden"     => "0003",
        "carpeta"   => "periodos_contables",
        "archivo"   => "menu"
    ),
    array(
        "id"        => "ADICPECO",
        "padre"     => "GESTPECO",
        "id_modulo" => "CONTABILIDAD",
        "visible"   => "0",
        "orden"     => "0005",
        "carpeta"   => "periodos_contables",
        "archivo"   => "adicionar"
    ),
    array(
        "id"        => "CONSPECO",
        "padre"     => "GESTPECO",
        "id_modulo" => "CONTABILIDAD",
        "visible"   => "0",
        "orden"     => "0010",
        "carpeta"   => "periodos_contables",
        "archivo"   => "consultar"
    ),
    array(
        "id"        => "MODIPECO",
        "padre"     => "GESTPECO",
        "id_modulo" => "CONTABILIDAD",
        "visible"   => "0",
        "orden"     => "0015",
        "carpeta"   => "periodos_contables",
        "archivo"   => "modificar"
    ),
    array(
        "id"        => "ELIMPECO",
        "padre"     => "GESTPECO",
        "id_modulo" => "CONTABILIDAD",
        "visible"   => "0",
        "orden"     => "0020",
        "carpeta"   => "periodos_contables",
        "archivo"   => "eliminar"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM=MERGE VIEW job_menu_periodos_contables AS
        SELECT            CONCAT(job_periodos_contables.codigo_sucursal,'|',job_periodos_contables.id_modulo,'|',job_periodos_contables.fecha_inicio,'|',job_periodos_contables.fecha_fin) AS id,
            job_sucursales.codigo AS id_sucursal,
            job_modulos.id AS id_modulo,
            job_sucursales.nombre AS SUCURSAL,
            job_modulos.id AS MODULO,
            job_periodos_contables.fecha_inicio AS FECHA_INICIO,
            job_periodos_contables.fecha_fin AS FECHA_FIN,
            CONCAT('ESTADO_',job_periodos_contables.estado) AS ESTADO
        FROM
            job_periodos_contables, job_sucursales, job_modulos
        WHERE
            job_periodos_contables.codigo_sucursal = job_sucursales.codigo AND
            job_periodos_contables.id_modulo = job_modulos.id;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM=MERGE VIEW job_buscador_periodos_contables AS
        SELECT
            CONCAT(job_periodos_contables.codigo_sucursal,'|',job_periodos_contables.fecha_inicio,'|',job_periodos_contables.fecha_fin) AS id,
            job_sucursales.codigo AS id_sucursal,
            job_modulos.id AS id_modulo,
            job_sucursales.nombre AS SUCURSAL,
            job_modulos.id AS MODULO,
            job_periodos_contables.fecha_inicio AS FECHA_INICIO,
            job_periodos_contables.fecha_fin AS FECHA_FIN,
            CONCAT('ESTADO_',job_periodos_contables.estado) AS ESTADO
        FROM
            job_periodos_contables, job_sucursales, job_modulos
        WHERE
            job_periodos_contables.codigo_sucursal = job_sucursales.codigo AND
            job_periodos_contables.id_modulo = job_modulos.id;"
    )
);
?>

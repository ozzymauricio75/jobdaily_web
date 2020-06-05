<?php
/**
*
* Copyright (C) 2020 Jobdaily
*
* Este archivo es parte de:
* Jobdaily:: Sofware empresarial a la medida
*
* Este programa es software libre: usted puede redistribuirlo y/o
* modificarlo  bajo los terminos de la Licencia Publica General GNU
* publicada por la Fundacion para el Software Libre, ya sea la version 3
* de la Licencia, o (a su eleccion) cualquier version posterior.
*
* Este programa se distribuye con la esperanza de que sea util, pero
* SIN GARANTIA ALGUNA; ni siquiera la garantia implicita MERCANTIL o
* de APTITUD PARA UN PROPOSITO DETERMINADO. Consulte los detalles de
* la Licencia Publica General GNU para obtener una informacion mas
* detallada.
*
* Deberia haber recibido una copia de la Licencia Publica General GNU
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
**/

// Eliminar la tabla y crearla de nuevo cada vez que se ejecute el script de creacion
$borrarSiempre = false;

// Definicion de tablas
$tablas["municipios"]   = array(
    "codigo_iso"               => "VARCHAR(2) NOT NULL COMMENT 'Llave principal de la tabla paises'",
    "codigo_dane_departamento" => "VARCHAR(2) NOT NULL COMMENT 'Código DANE'",
    "codigo_dane_municipio"    => "VARCHAR(3) NOT NULL COMMENT 'Código DANE'",
    "codigo_interno"           => "INT(4) UNSIGNED ZEROFILL COMMENT 'Código para uso interno de la empresa (opcional)'",
    "nombre"                   => "VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'Nombre completo'",
    "capital"                  => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT 'El municipio es la capital del departamento: 0=No, 1=Si'",
    "comunas"                  => "TINYINT(3) NOT NULL DEFAULT '0' COMMENT 'Número de comunas en las cuales se divide el municipio'"
);

// Definicion de llaves primarias
$llavesPrimarias["municipios"]   = "codigo_iso,codigo_dane_departamento,codigo_dane_municipio";

// Definicion de llaves foráneas
$llavesForaneas["municipios"]   = array(
    array(
        // Nombre de la llave
        "municipio_departamento",
        // Nombre del campo clave de la tabla local
        "codigo_iso,codigo_dane_departamento",
        // Nombre de la tabla relacionada
        "departamentos",
        // Nombre del campo clave en la tabla relacionada
        "codigo_iso,codigo_dane_departamento"
    )
);

// Insercion de datos iniciales
$registros["municipios"] = array(
    array(
        "codigo_iso"               => "",
        "codigo_dane_departamento" => "",
        "codigo_dane_municipio"    => "",
        "codigo_interno"           => "0",
        "nombre"                   => "",
        "capital"                  => "0",
        "comunas"                  => "0"
    )
);

$registros["componentes"] = array(
    array(
        "id"            => "GESTMUNI",
        "padre"         => "SUBMUBIG",
        "id_modulo"     => "ADMINISTRACION",
        "visible"       => "1",
        "orden"         => "300",
        "carpeta"       => "municipios",
        "archivo"       => "menu",
        "global"        => "0",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "ADICMUNI",
        "padre"         => "GESTMUNI",
        "id_modulo"     => "ADMINISTRACION",
        "visible"       => "0",
        "orden"         => "10",
        "carpeta"       => "municipios",
        "archivo"       => "adicionar",
        "global"        => "0",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "CONSMUNI",
        "padre"         => "GESTMUNI",
        "id_modulo"     => "ADMINISTRACION",
        "visible"       => "0",
        "orden"         => "20",
        "carpeta"       => "municipios",
        "archivo"       => "consultar",
        "global"        => "0",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "MODIMUNI",
        "padre"         => "GESTMUNI",
        "id_modulo"     => "ADMINISTRACION",
        "visible"       => "0",
        "orden"         => "30",
        "carpeta"       => "municipios",
        "archivo"       => "modificar",
        "global"        => "0",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "ELIMMUNI",
        "padre"         => "GESTMUNI",
        "id_modulo"     => "ADMINISTRACION",
        "visible"       => "0",
        "orden"         => "40",
        "carpeta"       => "municipios",
        "archivo"       => "eliminar",
        "global"        => "0",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "LISTMUNI",
        "padre"         => "GESTMUNI",
        "id_modulo"     => "ADMINISTRACION",
        "visible"       => "0",
        "orden"         => "50",
        "carpeta"       => "municipios",
        "archivo"       => "listar",
        "global"        => "0",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_municipios AS
        SELECT
                CONCAT(job_departamentos.codigo_iso, \"|\",job_municipios.codigo_dane_departamento,\"|\",job_municipios.codigo_dane_municipio) AS id,
                CONCAT(job_departamentos.codigo_dane_departamento, job_municipios.codigo_dane_municipio) AS CODIGO_DANE,
                job_municipios.nombre AS NOMBRE, job_departamentos.nombre AS DEPARTAMENTO,
                job_paises.nombre AS PAIS

        FROM            job_municipios,
                job_departamentos,
                job_paises

        WHERE   job_municipios.codigo_dane_departamento = job_departamentos.codigo_dane_departamento
                AND job_departamentos.codigo_iso = job_paises.codigo_iso
                AND job_municipios.codigo_dane_municipio != \"\"
                ORDER BY NOMBRE ASC;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_municipios AS
            SELECT
                    CONCAT(job_departamentos.codigo_iso, "|",job_municipios.codigo_dane_departamento,\"|\",job_municipios.codigo_dane_municipio) AS id,
                    CONCAT(job_departamentos.codigo_dane_departamento, job_municipios.codigo_dane_municipio) AS codigo_dane,
                    job_municipios.codigo_interno AS codigo_interno,
                    job_municipios.nombre AS nombre,
                    job_departamentos.nombre AS departamento,
                    job_paises.nombre AS pais
            FROM    job_municipios,
                    job_departamentos,
                    job_paises
            WHERE   job_municipios.codigo_dane_departamento = job_departamentos.codigo_dane_departamento
                    AND job_departamentos.codigo_iso = job_paises.codigo_iso
                    AND job_municipios.codigo_dane_municipio != \"\"
                    ORDER BY NOMBRE ASC;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_municipios AS
            SELECT
                CONCAT(job_departamentos.codigo_iso, "|",job_municipios.codigo_dane_departamento,\"|\",job_municipios.codigo_dane_municipio) AS id,
                CONCAT(
                    CONCAT(job_departamentos.codigo_dane_departamento, job_municipios.codigo_dane_municipio)
                    ,'-',job_municipios.nombre,
                    ', ', job_departamentos.nombre,
                    ', ',job_paises.nombre,
                    '|', CONCAT(job_departamentos.codigo_iso,\",\",job_municipios.codigo_dane_departamento,\",\",job_municipios.codigo_dane_municipio)
                ) AS nombre ,

                job_municipios.codigo_iso AS pais,
                job_municipios.codigo_dane_departamento AS departamento,
                job_municipios.codigo_dane_municipio AS codigo,
                CONCAT(job_departamentos.codigo_iso,\",\",job_municipios.codigo_dane_departamento,\",\",job_municipios.codigo_dane_municipio) AS llave_primaria
            FROM
                job_municipios,
                job_departamentos,
                job_paises
            WHERE
                job_municipios.codigo_dane_departamento = job_departamentos.codigo_dane_departamento
                AND job_departamentos.codigo_iso = job_paises.codigo_iso
                AND job_municipios.codigo_dane_municipio != \"\"
                ORDER BY NOMBRE ASC;"
    )
)

/* VISTAS REALES

CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_municipios AS
        SELECT
                CONCAT(job_departamentos.codigo_iso, "|",job_municipios.codigo_dane_departamento,"|",job_municipios.codigo_dane_municipio) AS id,
                CONCAT(job_departamentos.codigo_dane_departamento, job_municipios.codigo_dane_municipio) AS CODIGO_DANE,
                job_municipios.nombre AS NOMBRE, job_departamentos.nombre AS DEPARTAMENTO,
                job_paises.nombre AS PAIS

        FROM            job_municipios,
                job_departamentos,
                job_paises

        WHERE   job_municipios.codigo_dane_departamento = job_departamentos.codigo_dane_departamento
                AND job_departamentos.codigo_iso = job_paises.codigo_iso
                AND job_municipios.codigo_dane_municipio != ''
                ORDER BY NOMBRE ASC;"

CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_municipios AS
            SELECT
                    CONCAT(job_departamentos.codigo_iso, "|",job_municipios.codigo_dane_departamento,"|",job_municipios.codigo_dane_municipio) AS id,
                    CONCAT(job_departamentos.codigo_dane_departamento, job_municipios.codigo_dane_municipio) AS codigo_dane,
                    job_municipios.codigo_interno AS codigo_interno,
                    job_municipios.nombre AS nombre,
                    job_departamentos.nombre AS departamento,
                    job_paises.nombre AS pais
            FROM    job_municipios,
                    job_departamentos,
                    job_paises
            WHERE   job_municipios.codigo_dane_departamento = job_departamentos.codigo_dane_departamento
                    AND job_departamentos.codigo_iso = job_paises.codigo_iso
                    AND job_municipios.codigo_dane_municipio != ''
                    ORDER BY NOMBRE ASC;

CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_municipios AS
            SELECT
                CONCAT(job_departamentos.codigo_iso, "|",job_municipios.codigo_dane_departamento,"|",job_municipios.codigo_dane_municipio) AS id,
                CONCAT(
                    CONCAT(job_departamentos.codigo_dane_departamento, job_municipios.codigo_dane_municipio)
                    ,'-',job_municipios.nombre,
                    ', ', job_departamentos.nombre,
                    ', ',job_paises.nombre,
                    '|', CONCAT(job_departamentos.codigo_iso,",",job_municipios.codigo_dane_departamento,",",job_municipios.codigo_dane_municipio)
                ) AS nombre ,

                job_municipios.codigo_iso AS pais,
                job_municipios.codigo_dane_departamento AS departamento,
                job_municipios.codigo_dane_municipio AS codigo,
                CONCAT(job_departamentos.codigo_iso,",",job_municipios.codigo_dane_departamento,",",job_municipios.codigo_dane_municipio) AS llave_primaria
            FROM
                job_municipios,
                job_departamentos,
                job_paises
            WHERE
                job_municipios.codigo_dane_departamento = job_departamentos.codigo_dane_departamento
                AND job_departamentos.codigo_iso = job_paises.codigo_iso
                AND job_municipios.codigo_dane_municipio != ""
                ORDER BY NOMBRE ASC;
*/
?>

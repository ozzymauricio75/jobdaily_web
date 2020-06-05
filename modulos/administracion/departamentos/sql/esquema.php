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
$tablas["departamentos"] = array(
    "codigo_iso"               => "VARCHAR(2) NOT NULL COMMENT 'Llave principal de la tabla paises'",
    "codigo_dane_departamento" => "VARCHAR(2) NOT NULL COMMENT 'Código DANE'",
    "codigo_interno"           => "SMALLINT(3) UNSIGNED ZEROFILL COMMENT'Código para uso interno de la empresa (opcional)'",
    "nombre"                   => "VARCHAR(255) NOT NULL COMMENT'Nombre completo'"
);

// Definición de llaves primarias
$llavesPrimarias["departamentos"] = "codigo_iso,codigo_dane_departamento";


// Definición de llaves foráneas
$llavesForaneas["departamentos"] = array(
    array(
        // Nombre de la llave
        "departamento_pais",
        // Nombre del campo clave de la tabla local
        "codigo_iso",
        // Nombre de la tabla relacionada
        "paises",
        // Nombre del campo clave en la tabla relacionada
        "codigo_iso"
    )
);

// Inserción de datos iniciales
$registros["departamentos"] = array(
    array(
        "codigo_iso"               => "",
        "codigo_dane_departamento" => "",
        "codigo_interno"           => "0",
        "nombre"                   => ""
    )
);

$registros["componentes"] = array(
    array(
        "id"           => "GESTDEPA",
        "padre"        => "SUBMUBIG",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "1",
        "orden"        => "200",
        "carpeta"      => "departamentos",
        "archivo"      => "menu",
        "global"       => "0",
        "requiere_item" => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "ADICDEPA",
        "padre"        => "GESTDEPA",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "0",
        "orden"        => "10",
        "carpeta"      => "departamentos",
        "archivo"      => "adicionar",
        "global"       => "0",
        "requiere_item" => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "CONSDEPA",
        "padre"        => "GESTDEPA",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "0",
        "orden"        => "20",
        "carpeta"      => "departamentos",
        "archivo"      => "consultar",
        "global"       => "0",
        "requiere_item" => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "MODIDEPA",
        "padre"        => "GESTDEPA",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "0",
        "orden"        => "30",
        "carpeta"      => "departamentos",
        "archivo"      => "modificar",
        "global"       => "0",
        "requiere_item" => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "ELIMDEPA",
        "padre"        => "GESTDEPA",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "0",
        "orden"        => "40",
        "carpeta"      => "departamentos",
        "archivo"      => "eliminar",
        "global"       => "0",
        "requiere_item" => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "LISTDEPA",
        "padre"        => "GESTDEPA",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "0",
        "orden"        => "50",
        "carpeta"      => "departamentos",
        "archivo"      => "listar",
        "global"       => "0",
        "requiere_item" => "0",
        "tipo_enlace"  => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_departamentos AS
        SELECT
        CONCAT(job_departamentos.codigo_iso,\"|\",job_departamentos.codigo_dane_departamento) AS id,
        job_departamentos.codigo_iso AS id_codigo_iso,
        job_departamentos.codigo_dane_departamento AS CODIGO_DANE,
        job_departamentos.nombre AS NOMBRE,
        job_paises.nombre AS PAIS
        FROM job_departamentos, job_paises
        WHERE
        job_departamentos.codigo_iso = job_paises.codigo_iso
        AND job_departamentos.codigo_dane_departamento != '';"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_departamentos AS
        SELECT
        CONCAT(job_departamentos.codigo_iso,\"|\",job_departamentos.codigo_dane_departamento) AS id,
        job_departamentos.codigo_dane_departamento AS codigo_dane,
        job_departamentos.codigo_interno AS codigo_interno,
        job_departamentos.nombre AS nombre,
        job_paises.nombre AS pais
        FROM job_departamentos, job_paises
        WHERE
        job_departamentos.codigo_iso = job_paises.codigo_iso
        AND job_departamentos.codigo_dane_departamento != '';"
    )
);
/***
    DROP TABLE IF EXISTS job_menu_departamentos;
    DROP TABLE IF EXISTS job_buscador_departamentos;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_departamentos AS
    SELECT
    CONCAT(job_departamentos.codigo_iso,"|",job_departamentos.codigo_dane_departamento) AS id,
    job_departamentos.codigo_iso AS id_codigo_iso,
    job_departamentos.codigo_dane_departamento AS CODIGO_DANE,
    job_departamentos.nombre AS NOMBRE,
    job_paises.nombre AS PAIS
    FROM job_departamentos, job_paises
    WHERE
    job_departamentos.codigo_iso = job_paises.codigo_iso
    AND job_departamentos.codigo_dane_departamento != '';

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_departamentos AS
    SELECT
    CONCAT(job_departamentos.codigo_iso,"|",job_departamentos.codigo_dane_departamento) AS id,
    job_departamentos.codigo_dane_departamento AS codigo_dane,
    job_departamentos.codigo_interno AS codigo_interno,
    job_departamentos.nombre AS nombre,
    job_paises.nombre AS pais
    FROM job_departamentos, job_paises
    WHERE
    job_departamentos.codigo_iso = job_paises.codigo_iso
    AND job_departamentos.codigo_dane_departamento != '';

***/
?>

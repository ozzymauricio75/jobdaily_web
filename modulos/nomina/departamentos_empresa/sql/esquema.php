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

// Definición de tablas
$tablas["departamentos_empresa"] = array(
    "codigo"                => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código que identifica el departamento'",
    "nombre"                => "VARCHAR(50) NOT NULL DEFAULT '' COMMENT 'Nombre del departamento'",
    "riesgos_profesionales" => "DECIMAL(7,4) NULL COMMENT 'Porcentaje para la liquidacion de riesgos profesionales'",
    "codigo_gasto"          => "INT(5) UNSIGNED ZEROFILL NOT NULL DEFAULT 0 COMMENT 'Codigo de la tabla gastos prestaciones sociales'"
);

// Definición de llaves primarias
$llavesPrimarias["departamentos_empresa"] = "codigo";

// Definición de campos únicos
$llavesUnicas["departamentos_empresa"] = array(
    "nombre"
);

$llavesForaneas["departamentos_empresa"] = array(
    array(
        // Nombre de la llave foranea
        "gasto_departamento_empresa",
        // Campo en la tabla actual
        "codigo_gasto",
        // Nombre de la tabla relacionada
        "gastos_prestaciones_sociales",
        // Nombre del campo en la tabla relacionada
        "codigo"
    )
);

// Inserción de datos iniciales***/
$registros["departamentos_empresa"] = array(
    array(
        "codigo"       => "0",
        "nombre"       => "",
        "codigo_gasto" => "0"
    )
);

// Inserción de datos iniciales***/
$registros["componentes"] = array(
    array(
        "id"              => "GESTDEEM",
        "padre"           => "SUBMDCRH",
        "id_modulo"       => "NOMINA",
        "visible"         => "1",
        "orden"           => "80",
        "carpeta"         => "departamentos_empresa",
        "archivo"         => "menu",
        "global"          => "0",
        "requiere_item"   => "0",
        "tabla_principal" => "departamentos_empresa",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "ADICDEEM",
        "padre"           => "GESTDEEM",
        "id_modulo"       => "NOMINA",
        "visible"         => "0",
        "orden"           => "10",
        "carpeta"         => "departamentos_empresa",
        "archivo"         => "adicionar",
        "requiere_item"   => "0",
        "tabla_principal" => "departamentos_empresa",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "CONSDEEM",
        "padre"           => "GESTDEEM",
        "id_modulo"       => "NOMINA",
        "visible"         => "0",
        "orden"           => "20",
        "carpeta"         => "departamentos_empresa",
        "archivo"         => "consultar",
        "requiere_item"   => "1",
        "tabla_principal" => "departamentos_empresa",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "MODIDEEM",
        "padre"           => "GESTDEEM",
        "id_modulo"       => "NOMINA",
        "visible"         => "0",
        "orden"           => "30",
        "carpeta"         => "departamentos_empresa",
        "archivo"         => "modificar",
        "requiere_item"   => "1",
        "tabla_principal" => "departamentos_empresa",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "ELIMDEEMP",
        "padre"           => "GESTDEEM",
        "id_modulo"       => "NOMINA",
        "visible"         => "0",
        "orden"           => "40",
        "carpeta"         => "departamentos_empresa",
        "archivo"         => "eliminar",
        "requiere_item"   => "1",
        "tabla_principal" => "departamentos_empresa",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "LISTDEEM",
        "padre"           => "GESTDEEM",
        "id_modulo"       => "NOMINA",
        "visible"         => "0",
        "orden"           => "40",
        "carpeta"         => "departamentos_empresa",
        "archivo"         => "listar",
        "requiere_item"   => "1",
        "tabla_principal" => "departamentos_empresa",
        "tipo_enlace"     => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_departamentos_empresa AS
        SELECT
            job_departamentos_empresa.codigo AS id,
            job_departamentos_empresa.codigo AS CODIGO_DEPARTAMENTO,
            job_departamentos_empresa.nombre AS NOMBRE_DEPARTAMENTO,
            CONCAT(FORMAT(job_departamentos_empresa.riesgos_profesionales,2),'%') AS RIESGOS_PROFESIONALES
        FROM
            job_departamentos_empresa
        WHERE
           job_departamentos_empresa.codigo >0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_departamentos_empresa AS
        SELECT
            job_departamentos_empresa.codigo AS id,
            job_departamentos_empresa.codigo AS CODIGO_DEPARTAMENTO,
            job_departamentos_empresa.nombre AS NOMBRE_DEPARTAMENTO,
            CONCAT(FORMAT(job_departamentos_empresa.riesgos_profesionales,2),'%') AS RIESGOS_PROFESIONALES
        FROM
            job_departamentos_empresa
        WHERE
           job_departamentos_empresa.codigo > 0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_departamentos_empresa AS
        SELECT job_departamentos_empresa.codigo AS id,
        CONCAT(job_departamentos_empresa.codigo,' - ',job_departamentos_empresa.nombre,'|',job_departamentos_empresa.codigo) AS descripcion
        FROM job_departamentos_empresa
        WHERE job_departamentos_empresa.codigo > 0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_consulta_departamento_gastos_empleados AS
        SELECT
            job_departamentos_empresa.codigo AS codigo_empresa,
            job_departamentos_empresa.nombre AS nombre_departamento,
            job_departamentos_empresa.riesgos_profesionales AS riesgos_profesionales,
            job_gastos_prestaciones_sociales.codigo AS codigo_gasto_prestacion,
            job_gastos_prestaciones_sociales.descripcion AS descripcion_gasto,
            job_gastos_prestaciones_sociales.cesantia_pago_prestacion AS cesantia_pago_prestacion,
            job_gastos_prestaciones_sociales.cesantia_pago_gasto AS cesantia_pago_gasto,
            job_gastos_prestaciones_sociales.cesantia_traslado_fondo AS cesantia_traslado_fondo,
            job_gastos_prestaciones_sociales.cesantia_causacion_prestacion AS cesantia_causacion_prestacion,
            job_gastos_prestaciones_sociales.cesantia_causacion_gasto AS cesantia_causacion_gasto,
            job_gastos_prestaciones_sociales.intereses_pago_prestacion AS intereses_pago_prestacion,
            job_gastos_prestaciones_sociales.intereses_pago_gasto AS intereses_pago_gasto,
            job_gastos_prestaciones_sociales.intereses_causacion_prestacion AS intereses_causacion_prestacion,
            job_gastos_prestaciones_sociales.intereses_causacion_gasto AS intereses_causacion_gasto,
            job_gastos_prestaciones_sociales.prima_pago_prestacion AS prima_pago_prestacion,
            job_gastos_prestaciones_sociales.prima_pago_gasto AS prima_pago_gasto,
            job_gastos_prestaciones_sociales.prima_causacion_prestacion AS prima_causacion_prestacion,
            job_gastos_prestaciones_sociales.prima_causacion_gasto AS prima_causacion_gasto,
            job_gastos_prestaciones_sociales.vacacion_pago_prestacion_disfrute AS vacacion_pago_prestacion_disfrute,
            job_gastos_prestaciones_sociales.vacacion_pago_prestacion_liquidacion AS vacacion_pago_prestacion_liquidacion,
            job_gastos_prestaciones_sociales.vacacion_pago_gasto_disfrute AS vacacion_pago_gasto_disfrute,
            job_gastos_prestaciones_sociales.vacacion_pago_gasto_liquidacion AS vacacion_pago_gasto_liquidacion,
            job_gastos_prestaciones_sociales.vacacion_causacion_prestacion AS vacacion_causacion_prestacion,
            job_gastos_prestaciones_sociales.vacacion_causacion_gasto AS vacacion_causacion_gasto
        FROM
            job_departamentos_empresa,
            job_gastos_prestaciones_sociales
        WHERE
            job_departamentos_empresa.codigo_gasto = job_gastos_prestaciones_sociales.codigo
        "
    )
);
?>


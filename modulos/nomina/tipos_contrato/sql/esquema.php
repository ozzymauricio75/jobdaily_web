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

//Definición de tablas
$tablas["tipos_contrato"] = array(
    "codigo"               => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código interno que identifica el tipo de contrato'",
    "descripcion"          => "VARCHAR(255) NOT NULL COMMENT 'Detalle que describe el tipo de contrato'",
    "termino_contrato"     => "ENUM('1','2','3','4') NOT NULL COMMENT '1->Término fijo menor a un año 2->Término indefinido 3-> Sin relacion laboral 4-> Obra labor'",
    "tipo_contratacion"    => "ENUM('1','2','3','4','5','6','7','8','9','10') NOT NULL COMMENT '1->Integral 2->Al destajo 3->Practicante 4->Pasantías 5->Prestación de servicios 6->Cooperativa de trabajo asociado 7->Básico menor al minimo 8-> Básico mayor al minimo 9-> Comision con Básico 10-> Comision sin Básico'",
    "sueldo_ajusta_minimo" => "ENUM('0','1') NOT NULL COMMENT '0->No 1->Si'"
);

//Definición de llaves primarias
$llavesPrimarias["tipos_contrato"] = "codigo";

//Inserción de datos iniciales***/
$registros["tipos_contrato"] = array(
    array(
        "codigo"               => "0",
        "descripcion"          => "",
        "termino_contrato"     => "3",
        "tipo_contratacion"    => "1",
        "sueldo_ajusta_minimo" => "0"
    )
);

$registros["componentes"] = array(
    array(
        "id"            => "GESTTICT",
        "padre"         => "SUBMDCRH",
        "id_modulo"     => "NOMINA",
        "orden"         => "100",
        "visible"       => "1",
        "carpeta"       => "tipos_contrato",
        "global"        => "0",
        "archivo"       => "menu",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "ADICTICT",
        "padre"         => "GESTTICT",
        "id_modulo"     => "NOMINA",
        "orden"         => "10",
        "visible"       => "0",
        "carpeta"       => "tipos_contrato",
        "global"        => "0",
        "archivo"       => "adicionar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "CONSTICT",
        "padre"         => "GESTTICT",
        "id_modulo"     => "NOMINA",
        "orden"         => "20",
        "visible"       => "0",
        "carpeta"       => "tipos_contrato",
        "global"        => "0",
        "archivo"       => "consultar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "MODITICT",
        "padre"         => "GESTTICT",
        "id_modulo"     => "NOMINA",
        "orden"         => "30",
        "visible"       => "0",
        "carpeta"       => "tipos_contrato",
        "global"        => "0",
        "archivo"       => "modificar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "ELIMTICT",
        "padre"         => "GESTTICT",
        "id_modulo"     => "NOMINA",
        "orden"         => "40",
        "visible"       => "0",
        "carpeta"       => "tipos_contrato",
        "global"        => "0",
        "archivo"       => "eliminar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "LISTTICT",
        "padre"         => "GESTTICT",
        "id_modulo"     => "NOMINA",
        "orden"         => "50",
        "visible"       => "0",
        "carpeta"       => "tipos_contrato",
        "global"        => "0",
        "archivo"       => "listar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_tipo_contrato AS
        SELECT codigo AS id,
        codigo AS CODIGO,
        descripcion AS DESCRIPCION,
        CONCAT('TERMINO_',termino_contrato) AS TERMINO_CONTRATO
        FROM job_tipos_contrato
        WHERE job_tipos_contrato.codigo != 0;"
    ),
    array (
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_tipo_contrato AS
        SELECT codigo AS id,
        descripcion AS DESCRIPCION,
        CONCAT(
        IF(termino_contrato =1, 'Termino fijo','Termino indefinido')) AS TERMINO_CONTRATO
        FROM job_tipos_contrato
        WHERE job_tipos_contrato.codigo != 0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_tipo_contrato AS
        SELECT job_tipos_contrato.codigo AS id,
        CONCAT(job_tipos_contrato.descripcion, '|',
        job_tipos_contrato.codigo) AS descripcion
        FROM job_tipos_contrato
        WHERE job_tipos_contrato.codigo != 0;"
    )
);
//Sentencia para la creaciÓn de la vista requerida
/***
    DROP TABLE IF EXISTS job_menu_tipo_contrato;
    DROP TABLE IF EXISTS job_buscador_tipo_contrato;
    DROP TABLE IF EXISTS job_seleccion_tipo_contrato;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_tipo_contrato AS
    SELECT codigo AS id,
    codigo AS CODIGO,
    descripcion AS DESCRIPCION,
    CONCAT(
    IF(termino_contrato =1, 'Termino fijo','Termino indefinido')) AS TERMINO_CONTRATO
    FROM job_tipos_contrato
    WHERE job_tipos_contrato.codigo != 0;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_tipo_contrato AS
    SELECT codigo AS id,
    descripcion AS DESCRIPCION,
    CONCAT(
    IF(termino_contrato =1, 'Termino fijo','Termino indefinido')) AS TERMINO_CONTRATO
    FROM job_tipos_contrato
    WHERE job_tipos_contrato.codigo != 0;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_tipo_contrato AS
    SELECT job_tipos_contrato.codigo AS id,
    CONCAT(job_tipos_contrato.descripcion, '|',
    job_tipos_contrato.codigo) AS descripcion
    FROM job_tipos_contrato
    WHERE job_tipos_contrato.codigo != 0;
***/
?>

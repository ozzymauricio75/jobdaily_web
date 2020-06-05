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
$tablas["plan_contable"] = array(
    "codigo_contable"           => "VARCHAR(15) NOT NULL COMMENT 'Código definido por el PUC(Plan unico de cuentas)'",
    "descripcion"               => "VARCHAR(255) NOT NULL COMMENT 'Detalle que describe la cuenta contable'",
    "codigo_contable_padre"     => "VARCHAR(15) DEFAULT '' COMMENT 'Cuenta de nivel superior dentro de la estructura'",
    "naturaleza_cuenta"         => "ENUM('D','C') NOT NULL COMMENT 'Naturaleza cuenta: D->Debito C->Credito'",
    "clase_cuenta"              => "ENUM('1','2') NOT NULL COMMENT '1->Cuenta de movimiento la cual no podra ser padre y registra transacciones, 2->Cuenta mayor donde no se pueden registrar transacciones'",
    "tipo_cuenta"               => "ENUM('1','2','3') NOT NULL COMMENT '1->Cuenta de balance 2->Ganancias y perdidas 3->Orden'",
    "maneja_tercero"            => "ENUM('0','1') NOT NULL COMMENT 'La cuenta maneja saldos por tercero 0->No 1->Si'",
    "maneja_saldos"             => "ENUM('0','1') NOT NULL COMMENT 'La cuenta maneja saldos por documentos 0->No 1->Si'",
    "maneja_subsistema"         => "ENUM('0','1') NOT NULL COMMENT 'La cuenta maneja subsistema 0->No 1->Si'",
    "codigo_anexo_contable"     => "VARCHAR(3) NOT NULL COMMENT 'Id de la tabla de anexos contables(ver tabla de subcuentas), es opcional'",
    "codigo_tasa_aplicar_1"     => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Para las cuentas de impuestos y gravamenes que requieren un valor base a ser reportado, como el iva, la retencion en la fuente, ica y demas se debe colocar el codigo de tasa '",
    "codigo_tasa_aplicar_2"     => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Para las cuentas de impuestos y gravamenes que requieren un valor base a ser reportado, como el iva, la retencion en la fuente, ica y demas se debe colocar el codigo de tasa '",
    "codigo_concepto_dian"      => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código del concepto asignado por la DIAN para los informes de medios magneticos '",
    "tipo_certificado"          => "ENUM('1','2','3','4') NOT NULL COMMENT 'Con este parametro se identifican las cuentas de retenciones  para las cuales se requiere expedir el certificado a terceros, 1->No aplica, 2-> Retencion en la fuente 3-> industria y comercio (ica), 4-> Retencion de iva'",
    "causacion_automatica"      => "ENUM('0','1') NOT NULL COMMENT 'Para la cuenta se realiza un proceso de causacion 0->No 1->Si'",
    "flujo_efectivo"            => "ENUM('1','2','3') NOT NULL COMMENT '1->No afecta flujo 2->Caja 3->Bancos'",
    "codigo_contable_consolida" => "VARCHAR(15) DEFAULT '' COMMENT 'Cuenta donde consolida saldos'",
    "codigo_sucursal"           => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código de la sucursal si la cuenta requiere contabilización por una sucursal específica sin importar el origen del movimiento '",
    "codigo_moneda_extranjera"  => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código de la moneda extranjera en caso de que la cuenta requiera saldos por moneda extranjera'",
    "equivalencia"              => "VARCHAR(25) COMMENT 'Codigo contable un sistema anterior si se migrara la información'"
);

// Definición de llaves primarias
$llavesPrimarias["plan_contable"] = "codigo_contable";

$llavesForaneas["plan_contable"] = array(
    array(
        // Nombre de la llave
        "plan_anexo_contable",
        // Nombre del campo clave de la tabla local
        "codigo_anexo_contable",
        // Nombre de la tabla relacionada
        "anexos_contables",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave
        "plan_tasa_aplicar_1",
        // Nombre del campo clave de la tabla local
        "codigo_tasa_aplicar_1",
        // Nombre de la tabla relacionada
        "tasas",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave
        "plan_tasa_aplicar_2",
        // Nombre del campo clave de la tabla local
        "codigo_tasa_aplicar_2",
        // Nombre de la tabla relacionada
        "tasas",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave
        "plan_concepto_dian",
        // Nombre del campo clave de la tabla local
        "codigo_concepto_dian",
        // Nombre de la tabla relacionada
        "conceptos_dian",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave
        "plan_sucursal",
        // Nombre del campo clave de la tabla local
        "codigo_sucursal",
        // Nombre de la tabla relacionada
        "sucursales",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave
        "plan_moneda",
        // Nombre del campo clave de la tabla local
        "codigo_moneda_extranjera",
        // Nombre de la tabla relacionada
        "tipos_moneda",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    )
);

// Inserción de datos iniciales
$registros["plan_contable"] = array(
    array(
        "codigo_contable"           => "",
        "descripcion"               => "",
        "codigo_contable_padre"     => "",
        "naturaleza_cuenta"         => 'D',
        "clase_cuenta"              => '1',
        "tipo_cuenta"               => '1',
        "maneja_tercero"            => '0',
        "maneja_saldos"             => '0',
        "maneja_subsistema"         => '0',
        "codigo_anexo_contable"     => 0,
        "codigo_tasa_aplicar_1"     => 0,
        "codigo_tasa_aplicar_2"     => 0,
        "codigo_concepto_dian"      => 0,
        "tipo_certificado"          => 0,
        "causacion_automatica"      => '0',
        "flujo_efectivo"            => '1',
        "codigo_contable_consolida" => 0,
        "codigo_sucursal"           => 0,
        "codigo_moneda_extranjera"  => 0
    )
);
// Inserción de datos iniciales
$registros["componentes"] = array(
    array(
        "id"        => "GESTPLCO",
        "padre"     => "SUBMINCO",
        "id_modulo" => "CONTABILIDAD",
        "orden"     => "0005",
        "visible"   => "1",
        "carpeta"   => "plan_contable",
        "archivo"   => "menu"
    ),
    array(
        "id"        => "ADICPLCO",
        "padre"     => "GESTPLCO",
        "id_modulo" => "CONTABILIDAD",
        "visible"   => "0",
        "orden"     => "10",
        "carpeta"   => "plan_contable",
        "archivo"   => "adicionar"
    ),
    array(
        "id"        => "CONSPLCO",
        "padre"     => "GESTPLCO",
        "id_modulo" => "CONTABILIDAD",
        "visible"   => "0",
        "orden"     => "20",
        "carpeta"   => "plan_contable",
        "archivo"   => "consultar"
    ),
    array(
        "id"        => "MODIPLCO",
        "padre"     => "GESTPLCO",
        "id_modulo" => "CONTABILIDAD",
        "visible"   => "0",
        "orden"     => "30",
        "carpeta"   => "plan_contable",
        "archivo"   => "modificar"
    ),
    array(
        "id"        => "ELIMPLCO",
        "padre"     => "GESTPLCO",
        "id_modulo" => "CONTABILIDAD",
        "visible"   => "0",
        "orden"     => "40",
        "carpeta"   => "plan_contable",
        "archivo"   => "eliminar"
    ),
    array(
        "id"        => "LISTPLCO",
        "padre"     => "GESTPLCO",
        "id_modulo" => "CONTABILIDAD",
        "visible"   => "0",
        "orden"     => "50",
        "carpeta"   => "plan_contable",
        "archivo"   => "listar"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_plan_contable AS
        SELECT
            b.codigo_contable AS id,
            b.codigo_contable AS CODIGO_CONTABLE,
            b.descripcion AS DESCRIPCION,
            (SELECT a.codigo_contable FROM job_plan_contable a WHERE a.codigo_contable=b.codigo_contable_padre) AS CUENTA_PADRE,
            if(b.naturaleza_cuenta = 'D', 'Debito', 'Credito') AS NATURALEZA_CUENTA,
            c.codigo AS CODIGO_ANEXO
        FROM
            job_plan_contable b, job_anexos_contables c
        WHERE
            b.codigo_anexo_contable=c.codigo AND
            b.codigo_contable !='';"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_plan_contable AS
        SELECT
            codigo_contable AS id,
            codigo_contable AS codigo_contable,
            descripcion AS descripcion,
            codigo_contable_padre AS cuenta_padre
        FROM
            job_plan_contable
        WHERE
            codigo_contable != '';",
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_plan_contable AS
        SELECT
            codigo_contable AS id,
            CONCAT(codigo_contable, ', ', descripcion, '|', codigo_contable) AS codigo_contable
        FROM
            job_plan_contable
        WHERE
            clase_cuenta = 1 AND
            codigo_contable != '';"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_todo_plan_contable AS
        SELECT
            codigo_contable AS id,
            CONCAT(codigo_contable, ', ', descripcion, '|', codigo_contable) AS codigo_contable
        FROM
            job_plan_contable
        WHERE
            codigo_contable != '';"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_plan_contable_debito AS
        SELECT
            codigo_contable AS id,
            CONCAT(codigo_contable, ', ', descripcion, '|', codigo_contable) AS codigo_contable
        FROM
            job_plan_contable
        WHERE
            naturaleza_cuenta = 'D' AND
            codigo_contable !='';"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_plan_contable_credito AS
        SELECT
            codigo_contable AS id,
            CONCAT(codigo_contable, ', ', descripcion, '|', codigo_contable) AS codigo_contable
        FROM
            job_plan_contable
        WHERE
            naturaleza_cuenta = 'C' AND
            codigo_contable != 0;"
    ),
    // Seleccion de cuentas que registran transacciones, mostrando los padres a partir del tercer nivel
    array(
        "CREATE OR REPLACE ALGORITHM=MERGE VIEW job_seleccion_plan_contable_transacciones AS
        SELECT a.codigo_contable AS id,
        CONCAT(
            a.codigo_contable,
            ', ',
            IF(
                a.codigo_contable_padre = '', '',
                (
                    SELECT
                        CONCAT(
                            IF(b.descripcion IS NULL OR CHAR_LENGTH(b.codigo_contable) < 3, '', CONCAT(b.descripcion, '-')),
                            IF(c.descripcion IS NULL OR CHAR_LENGTH(c.codigo_contable) < 3, '', CONCAT(c.descripcion, '-')),
                            IF(d.descripcion IS NULL OR CHAR_LENGTH(d.codigo_contable) < 3, '', d.descripcion)
                        )
                        FROM job_plan_contable as b LEFT JOIN job_plan_contable as c ON b.codigo_contable = c.codigo_contable_padre
                        LEFT JOIN job_plan_contable as d ON c.codigo_contable = d.codigo_contable_padre
                        WHERE d.codigo_contable = a.codigo_contable_padre
                )
            ),
            '-',
            a.descripcion,
            '|',
            a.codigo_contable
        ) AS cuenta
        FROM
            job_plan_contable AS a
        WHERE
            a.clase_cuenta = 1 AND
            codigo_contable != '';"
    ),
    array(
        // Seleccion de cuentas que afectan el flujo de efectivo de bancos - genera cheque
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_plan_contable_flujo_bancos AS
        SELECT
            codigo_contable AS id,
            CONCAT(codigo_contable, ', ', descripcion, '|', codigo_contable) AS codigo_contable
        FROM
            job_plan_contable
        WHERE
            flujo_efectivo = '3' AND
            codigo_contable !='';"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_plan_contable_saldos_terceros AS
        SELECT
            codigo_contable AS id,
            CONCAT(codigo_contable,', ',descripcion,'|',codigo_contable) AS codigo_contable
        FROM
            job_plan_contable
        WHERE
            codigo_contable != '' AND
            maneja_saldos = '1' AND
            maneja_tercero = '1'
        ORDER BY codigo_contable;"
    )
);
// Sentencia para la creación de la vista requerida
/***
    DROP TABLE IF EXISTS job_menu_plan_contable;
    DROP TABLE IF EXISTS job_buscador_plan_contable;
    DROP TABLE IF EXISTS job_seleccion_plan_contable;
    DROP TABLE IF EXISTS job_seleccion_plan_contable_debito;
    DROP TABLE IF EXISTS job_seleccion_plan_contable_credito;
    DROP TABLE IF EXISTS job_seleccion_plan_contable_transacciones;
    DROP TABLE IF EXISTS job_seleccion_plan_contable_flujo_bancos;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_plan_contable AS
    SELECT
        b.codigo_contable AS id,
        b.codigo_contable AS CODIGO_CONTABLE,
        b.descripcion AS DESCRIPCION,
        (SELECT a.codigo_contable FROM job_plan_contable a WHERE a.codigo_contable=b.codigo_contable_padre) AS CUENTA_PADRE,
        if(b.naturaleza_cuenta = 'D', 'Debito', 'Credito') AS NATURALEZA_CUENTA,
        c.codigo AS CODIGO_ANEXO
    FROM
        job_plan_contable b, job_anexos_contables c
    WHERE
        b.codigo_anexo_contable=c.codigo AND
        b.codigo_contable !='';

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_plan_contable AS
    SELECT
        codigo_contable AS id,
        codigo_contable AS codigo_contable,
        descripcion AS descripcion,
        codigo_contable_padre AS cuenta_padre
    FROM
        job_plan_contable
    WHERE
        codigo_contable != '';

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_plan_contable AS
    SELECT
        codigo_contable AS id,
        CONCAT(codigo_contable, ', ', descripcion, '|', codigo_contable) AS codigo_contable
    FROM
        job_plan_contable
    WHERE
        clase_cuenta = 1 AND
        codigo_contable != '';

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_todo_plan_contable AS
    SELECT
        codigo_contable AS id,
        CONCAT(codigo_contable, ', ', descripcion, '|', codigo_contable) AS codigo_contable
    FROM
        job_plan_contable
    WHERE
        codigo_contable != '';

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_plan_contable_debito AS
    SELECT
        codigo_contable AS id,
        CONCAT(codigo_contable, ', ', descripcion, '|', codigo_contable) AS codigo_contable
    FROM
        job_plan_contable
    WHERE
        naturaleza_cuenta = 'D' AND
        codigo_contable !='';

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_plan_contable_credito AS
    SELECT
        codigo_contable AS id,
        CONCAT(codigo_contable, ', ', descripcion, '|', codigo_contable) AS codigo_contable
    FROM
        job_plan_contable
    WHERE
        naturaleza_cuenta = 'C' AND
        codigo_contable != 0;

    // Seleccion de cuentas que registran transacciones, mostrando los padres a partir del tercer nivel
    CREATE OR REPLACE ALGORITHM=MERGE VIEW job_seleccion_plan_contable_transacciones AS
    SELECT a.codigo_contable AS id,
    CONCAT(
        a.codigo_contable,
        ', ',
        IF(
            a.codigo_contable_padre = '', '',
            (
                SELECT
                    CONCAT(
                        IF(b.descripcion IS NULL OR CHAR_LENGTH(b.codigo_contable) < 3, '', CONCAT(b.descripcion, '-')),
                        IF(c.descripcion IS NULL OR CHAR_LENGTH(c.codigo_contable) < 3, '', CONCAT(c.descripcion, '-')),
                        IF(d.descripcion IS NULL OR CHAR_LENGTH(d.codigo_contable) < 3, '', d.descripcion)
                    )
                    FROM job_plan_contable as b LEFT JOIN job_plan_contable as c ON b.codigo_contable = c.codigo_contable_padre
                    LEFT JOIN job_plan_contable as d ON c.codigo_contable = d.codigo_contable_padre
                    WHERE d.codigo_contable = a.codigo_contable_padre
            )
        ),
        '-',
        a.descripcion,
        '|',
        a.codigo_contable
    ) AS cuenta
    FROM
        job_plan_contable AS a
    WHERE
        a.clase_cuenta = 1 AND
        codigo_contable != '';

    // Seleccion de cuentas que afectan el flujo de efectivo de bancos - genera cheque
    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_plan_contable_flujo_bancos AS
    SELECT
        codigo_contable AS id,
        CONCAT(codigo_contable, ', ', descripcion, '|', codigo_contable) AS codigo_contable
    FROM
        job_plan_contable
    WHERE
        flujo_efectivo = '3' AND
        codigo_contable !='';


***/

?>

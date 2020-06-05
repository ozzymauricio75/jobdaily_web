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
$tablas ["bancos"] = array(
    "codigo"                      => "SMALLINT(2) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código interno asignado al banco'",
    "documento_identidad_tercero" => "VARCHAR(12) NOT NULL COMMENT'Llave de la tabla terceros'",
    "descripcion"                 => "VARCHAR(30) NOT NULL COMMENT 'Nombre que describe el banco'"
);

// Definicion de llaves primarias
$llavesPrimarias["bancos"] = "codigo";

// Definicion de llaves foraneas
$llavesForaneas["bancos"]  = array(
    array(
        // Nombre de la llave
        "bancos_tercero",
        // Nombre del campo clave de la tabla local
        "documento_identidad_tercero",
        // Nombre de la tabla relacionada
        "terceros",
        // Nombre del campo clave en la tabla relacionada
        "documento_identidad"
    )
);

// Insercion de datos iniciales
$registros["bancos"] = array(
    array(
        "codigo"                      => "0",
        "documento_identidad_tercero" => "0",
        "descripcion"                 => ""
    )
);

$tablas["sucursales_bancos"] = array(
    "codigo"                   => "SMALLINT(2) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la sucursal bancaria'",
    "codigo_iso"               => "VARCHAR(2) NOT NULL COMMENT 'Llave principal de la tabla paises'",
    "codigo_dane_departamento" => "VARCHAR(2) NOT NULL COMMENT 'Codigo dane del departamento'",
    "codigo_dane_municipio"    => "VARCHAR(3) NOT NULL COMMENT 'Código dane del municipio'",
    "codigo_banco"             => "SMALLINT(2) UNSIGNED ZEROFILL NOT NULL COMMENT 'codigo de la tabla bancos'",
    "nombre_sucursal"          => "VARCHAR(50) NOT NULL COMMENT 'Nombre de la sucursal del banco'",
    "direccion"                => "VARCHAR(50) COMMENT 'Direccion donde se ubica la sucursal'",
    "telefono"                 => "VARCHAR(15) COMMENT 'Telefono de la sucursal'",
    "contacto"                 => "VARCHAR(255) COMMENT 'Contacto de la sucursal'",
    "correo"                   => "VARCHAR(255) COMMENT 'Correo del contacto de la sucursal'",
    "celular"                  => "VARCHAR(20) COMMENT 'Celular del contacto de la sucursal'"
);

// Definicion de llaves primarias
$llavesPrimarias["sucursales_bancos"] = "codigo,codigo_banco,codigo_iso,codigo_dane_departamento,codigo_dane_municipio";

// Definicion de llaves foraneas
$llavesForaneas["sucursales_bancos"] = array(
    array(
        // Nombre de la llave
        "sucursales_bancos_id_banco",
        // Nombre del campo clave de la tabla local
        "codigo_banco",
        // Nombre de la tabla relacionada
        "bancos",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave
        "sucursales_bancos_id_municipio",
        // Nombre del campo clave de la tabla local
        "codigo_iso,codigo_dane_departamento,codigo_dane_municipio",
        // Nombre de la tabla relacionada
        "municipios",
        // Nombre del campo clave en la tabla relacionada
        "codigo_iso,codigo_dane_departamento,codigo_dane_municipio"
    )
);

// Insercion de datos iniciales

$registros["sucursales_bancos"] = array(
    array(
        "codigo"                   => "0",
        "codigo_iso"               => "",
        "codigo_dane_departamento" => "",
        "codigo_dane_municipio"    => "",
        "codigo_banco"             => "0",
        "nombre_sucursal"          => "",
        "direccion"                => "",
        "telefono"                 => "",
        "contacto"                 => "",
        "correo"                   => "",
        "celular"                  => ""
    )
);

// Insercion de datos iniciales
$registros["componentes"] = array(
    array(
        "id"              => "GESTBANC",
        "padre"           => "SUBMFINA",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "1",
        "orden"           => "0010",
        "carpeta"         => "bancos",
        "archivo"         => "menu",
        "requiere_item"   => "1",
        "tabla_principal" => "bancos",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "ADICBANC",
        "padre"           => "GESTBANC",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "0",
        "orden"           => "10",
        "carpeta"         => "bancos",
        "archivo"         => "adicionar",
        "requiere_item"   => "0",
        "tabla_principal" => "bancos",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "CONSBANC",
        "padre"           => "GESTBANC",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "0",
        "orden"           => "20",
        "carpeta"         => "bancos",
        "archivo"         => "consultar",
        "requiere_item"   => "1",
        "tabla_principal" => "bancos",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "MODIBANC",
        "padre"           => "GESTBANC",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "0",
        "orden"           => "30",
        "carpeta"         => "bancos",
        "archivo"         => "modificar",
        "requiere_item"   => "1",
        "tabla_principal" => "bancos",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "ELIMBANC",
        "padre"           => "GESTBANC",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "0",
        "orden"           => "40",
        "carpeta"         => "bancos",
        "archivo"         => "eliminar",
        "requiere_item"   => "1",
        "tabla_principal" => "bancos",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "LISTBANC",
        "padre"           => "GESTBANC",
        "id_modulo"       => "CONTABILIDAD",
        "visible"         => "0",
        "orden"           => "50",
        "carpeta"         => "bancos",
        "archivo"         => "listar",
        "requiere_item"   => "1",
        "tabla_principal" => "bancos",
        "tipo_enlace"     => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_bancos AS
        SELECT  job_bancos.codigo AS id,
            job_bancos.codigo AS CODIGO,
            job_bancos.descripcion AS DESCRIPCION,
            IF(job_terceros.tipo_persona = '1' OR job_terceros.tipo_persona = '4',
                CONCAT(
                    job_terceros.primer_nombre,' ',
                    IF (job_terceros.segundo_nombre IS NOT NULL AND job_terceros.segundo_nombre != '',CONCAT(job_terceros.segundo_nombre,' '),''),
                    job_terceros.primer_apellido,' ',
                    IF (job_terceros.segundo_apellido IS NOT NULL AND job_terceros.segundo_apellido != '',job_terceros.segundo_apellido,'')
                ),
                job_terceros.razon_social
            ) AS TERCERO,
            job_terceros.documento_identidad AS DOCUMENTO_IDENTIDAD,
            CONCAT(job_localidades.nombre,\", \",job_municipios.nombre,\", \",job_departamentos.nombre,\", \",job_paises.nombre) AS CIUDAD_RESIDENCIA

        FROM job_bancos,
            job_terceros,
            job_localidades,
            job_municipios,
            job_departamentos,
            job_paises

        WHERE   job_bancos.codigo > 0
            AND job_bancos.documento_identidad_tercero = job_terceros.documento_identidad
            AND job_terceros.codigo_iso_localidad = job_paises.codigo_iso
            AND job_terceros.codigo_iso_localidad = job_departamentos.codigo_iso
            AND job_terceros.codigo_dane_departamento_localidad = job_departamentos.codigo_dane_departamento
            AND job_terceros.codigo_iso_localidad = job_municipios.codigo_iso
            AND job_terceros.codigo_dane_departamento_localidad = job_municipios.codigo_dane_departamento
            AND job_terceros.codigo_dane_municipio_localidad = job_municipios.codigo_dane_municipio
            AND job_terceros.codigo_iso_localidad = job_localidades.codigo_iso
            AND job_terceros.codigo_dane_departamento_localidad = job_localidades.codigo_dane_departamento
            AND job_terceros.codigo_dane_municipio_localidad = job_localidades.codigo_dane_municipio
            AND job_terceros.tipo_localidad = job_localidades.tipo
            AND job_terceros.codigo_dane_localidad = job_localidades.codigo_dane_localidad;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_bancos AS
        SELECT job_bancos.codigo AS id,
            IF(job_terceros.tipo_persona = '1' OR job_terceros.tipo_persona = '4',
                CONCAT(
                    job_terceros.primer_nombre,' ',
                    IF (job_terceros.segundo_nombre IS NOT NULL AND job_terceros.segundo_nombre != '',CONCAT(job_terceros.segundo_nombre,' '),''),
                    job_terceros.primer_apellido,' ',
                    IF (job_terceros.segundo_apellido IS NOT NULL AND job_terceros.segundo_apellido != '',job_terceros.segundo_apellido,'')
                ),
                job_terceros.razon_social
            ) AS tercero,
            job_terceros.documento_identidad AS documento_identidad,
            CONCAT(job_localidades.nombre,\", \",job_municipios.nombre,\", \",job_departamentos.nombre,\", \",job_paises.nombre) AS ciudad_residencia
        FROM    job_bancos,
            job_terceros,
            job_localidades,
            job_municipios,
            job_departamentos,
            job_paises
        WHERE   job_bancos.codigo > 0
            AND job_bancos.documento_identidad_tercero = job_terceros.documento_identidad
            AND job_terceros.codigo_iso_localidad = job_paises.codigo_iso
            AND job_terceros.codigo_iso_localidad = job_departamentos.codigo_iso
            AND job_terceros.codigo_dane_departamento_localidad = job_departamentos.codigo_dane_departamento
            AND job_terceros.codigo_iso_localidad = job_municipios.codigo_iso
            AND job_terceros.codigo_dane_departamento_localidad = job_municipios.codigo_dane_departamento
            AND job_terceros.codigo_dane_municipio_localidad = job_municipios.codigo_dane_municipio
            AND job_terceros.codigo_iso_localidad = job_localidades.codigo_iso
            AND job_terceros.codigo_dane_departamento_localidad = job_localidades.codigo_dane_departamento
            AND job_terceros.codigo_dane_municipio_localidad = job_localidades.codigo_dane_municipio
            AND job_terceros.tipo_localidad = job_localidades.tipo
            AND job_terceros.codigo_dane_localidad = job_localidades.codigo_dane_localidad;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_bancos AS
        SELECT  job_bancos.codigo AS id,
            CONCAT(job_bancos.codigo, ' : ',
            job_bancos.descripcion, '|', job_bancos.codigo) AS descripcion
        FROM job_bancos
        WHERE   job_bancos.codigo > 0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_sucursales_bancos AS
        SELECT  CONCAT(job_sucursales_bancos.codigo,'|',job_sucursales_bancos.codigo_banco,'|',job_sucursales_bancos.codigo_iso,'|',job_sucursales_bancos.codigo_dane_departamento,'|',job_sucursales_bancos.codigo_dane_municipio) AS id,
            job_sucursales_bancos.nombre_sucursal AS nombre_sucursal,
            job_sucursales_bancos.codigo_banco AS codigo_banco
        FROM    job_sucursales_bancos,job_bancos
        WHERE   job_sucursales_bancos.codigo_banco = job_bancos.codigo
            AND job_bancos.codigo != 0;"
    ),
    array("CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_bancos_sucursales AS(
            SELECT  b.codigo AS codigo_banco,
                    b.descripcion AS nombre_banco,
                    t.documento_identidad AS documento_identidad_tercero,
                    IF(t.tipo_persona = '1' OR t.tipo_persona = '4',
                        CONCAT(
                            t.primer_nombre,' ',
                            IF (t.segundo_nombre IS NOT NULL AND t.segundo_nombre != '',CONCAT(t.segundo_nombre,' '),''),
                            t.primer_apellido,' ',
                            IF (t.segundo_apellido IS NOT NULL AND t.segundo_apellido != '',t.segundo_apellido,'')
                        ),
                        t.razon_social
                    ) AS nombre_tercero,
                    s.codigo AS codigo_sucursal,
                    s.nombre_sucursal AS nombre_sucursal,
                    s.direccion AS direccion,
                    s.telefono AS telefono,
                    s.contacto AS contacto,
                    s.correo AS correo,
                    s.celular AS celular,
                    m.codigo_iso AS codigo_iso,
                    m.codigo_dane_departamento AS codigo_dane_departamento,
                    m.codigo_dane_municipio AS codigo_dane_municipio,
                    SUBSTRING_INDEX(sm.nombre,'|',1) AS nombre_municipio
            FROM    job_bancos AS b, job_terceros AS t, job_sucursales_bancos AS s,
                    job_municipios AS m, job_seleccion_municipios AS sm
            WHERE   b.documento_identidad_tercero = t.documento_identidad AND
                    b.codigo = s.codigo_banco AND s.codigo_iso = m.codigo_iso AND
                    s.codigo_dane_departamento = m.codigo_dane_departamento AND
                    s.codigo_dane_municipio = m.codigo_dane_municipio AND
                    sm.id = CONCAT(s.codigo_iso,'|',s.codigo_dane_departamento,'|',s.codigo_dane_municipio) AND
                    b.codigo != 0
            ORDER BY b.codigo, s.codigo
        );"
    )
);

/* VISTAS REALES
CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_bancos AS
        SELECT  job_bancos.codigo AS id,
            job_bancos.codigo AS CODIGO,
            job_bancos.descripcion AS DESCRIPCION,
            IF(job_terceros.tipo_persona = '1' OR job_terceros.tipo_persona = '4',
                CONCAT(
                    job_terceros.primer_nombre,' ',
                    IF (job_terceros.segundo_nombre IS NOT NULL AND job_terceros.segundo_nombre != '',CONCAT(job_terceros.segundo_nombre,' '),''),
                    job_terceros.primer_apellido,' ',
                    IF (job_terceros.segundo_apellido IS NOT NULL AND job_terceros.segundo_apellido != '',job_terceros.segundo_apellido,'')
                ),
                job_terceros.razon_social
            ) AS TERCERO,
            job_terceros.documento_identidad AS DOCUMENTO_IDENTIDAD,
            CONCAT(job_localidades.nombre,", ",job_municipios.nombre,", ",job_departamentos.nombre,", ",job_paises.nombre) AS CIUDAD_RESIDENCIA

        FROM job_bancos,
            job_terceros,
            job_localidades,
            job_municipios,
            job_departamentos,
            job_paises

        WHERE   job_bancos.codigo > 0
            AND job_bancos.documento_identidad_tercero = job_terceros.documento_identidad
            AND job_terceros.codigo_iso_localidad = job_paises.codigo_iso
            AND job_terceros.codigo_iso_localidad = job_departamentos.codigo_iso
            AND job_terceros.codigo_dane_departamento_localidad = job_departamentos.codigo_dane_departamento
            AND job_terceros.codigo_iso_localidad = job_municipios.codigo_iso
            AND job_terceros.codigo_dane_departamento_localidad = job_municipios.codigo_dane_departamento
            AND job_terceros.codigo_dane_municipio_localidad = job_municipios.codigo_dane_municipio
            AND job_terceros.codigo_iso_localidad = job_localidades.codigo_iso
            AND job_terceros.codigo_dane_departamento_localidad = job_localidades.codigo_dane_departamento
            AND job_terceros.codigo_dane_municipio_localidad = job_localidades.codigo_dane_municipio
            AND job_terceros.tipo_localidad = job_localidades.tipo
            AND job_terceros.codigo_dane_localidad = job_localidades.codigo_dane_localidad

CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_bancos AS
        SELECT job_bancos.codigo AS id,
            IF(job_terceros.tipo_persona = '1' OR job_terceros.tipo_persona = '4',
                CONCAT(
                    job_terceros.primer_nombre,' ',
                    IF (job_terceros.segundo_nombre IS NOT NULL AND job_terceros.segundo_nombre != '',CONCAT(job_terceros.segundo_nombre,' '),''),
                    job_terceros.primer_apellido,' ',
                    IF (job_terceros.segundo_apellido IS NOT NULL AND job_terceros.segundo_apellido != '',job_terceros.segundo_apellido,'')
                ),
                job_terceros.razon_social
            ) AS tercero,
            job_terceros.documento_identidad AS documento_identidad,
            CONCAT(job_localidades.nombre,", ",job_municipios.nombre,", ",job_departamentos.nombre,", ",job_paises.nombre) AS ciudad_residencia
        FROM    job_bancos,
            job_terceros,
            job_localidades,
            job_municipios,
            job_departamentos,
            job_paises
        WHERE   job_bancos.codigo > 0
            AND job_bancos.documento_identidad_tercero = job_terceros.documento_identidad
            AND job_terceros.codigo_iso_localidad = job_paises.codigo_iso
            AND job_terceros.codigo_iso_localidad = job_departamentos.codigo_iso
            AND job_terceros.codigo_dane_departamento_localidad = job_departamentos.codigo_dane_departamento
            AND job_terceros.codigo_iso_localidad = job_municipios.codigo_iso
            AND job_terceros.codigo_dane_departamento_localidad = job_municipios.codigo_dane_departamento
            AND job_terceros.codigo_dane_municipio_localidad = job_municipios.codigo_dane_municipio
            AND job_terceros.codigo_iso_localidad = job_localidades.codigo_iso
            AND job_terceros.codigo_dane_departamento_localidad = job_localidades.codigo_dane_departamento
            AND job_terceros.codigo_dane_municipio_localidad = job_localidades.codigo_dane_municipio
            AND job_terceros.tipo_localidad = job_localidades.tipo
            AND job_terceros.codigo_dane_localidad = job_localidades.codigo_dane_localidad

            


*/

?>

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
$tablas ["conceptos_tesoreria"] = array(//No se esta seguro de si la cuenta debiera llevar la sucursal el banco
    "codigo"                 => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo interno asignado por el usuario'",
    "codigo_grupo_tesoreria" => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo interno del grupo de tesoreria'",
    "nombre_concepto"        => "VARCHAR(50) NOT NULL COMMENT 'Nombre del concepto'"
);

/*** Definición de llaves primarias ***/
$llavesPrimarias["conceptos_tesoreria"] = "codigo_grupo_tesoreria,nombre_concepto";

/*** Definici{on de llaves Foraneas ***/
$llavesForaneas["conceptos_tesoreria"] = array(
    array(
        /*** Nombre de la llave foranea ***/
        "conceptos_tesoreria_grupos",
        /*** Nombre del campo en la tabla actual ***/
        "codigo_grupo_tesoreria",
        /*** Nombre de la tabla relacionada ***/
        "grupos_tesoreria",
        /*** Nombre del campo de la tabla relacionada ***/
        "codigo"
    )
);

/*** Insertar registros iniciales ***/
$registros["conceptos_tesoreria"] = array(
    array(
        "codigo"                 => "0",
        "codigo_grupo_tesoreria" => "0",
        "nombre_concepto"        => ""
    )
);

/*** Inserción de datos iniciales ***/
$registros["componentes"] = array(
    array(
        "id"              => "GESTCOTE",
        "padre"           => "SUBMDCTE",
        "id_modulo"       => "TESORERIA",
        "visible"         => "1",
        "orden"           => "400",
        "carpeta"         => "conceptos_tesoreria",
        "archivo"         => "menu",
        "requiere_item"   => "1",
        "tabla_principal" => "conceptos_tesoreria",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "ADICCOTE",
        "padre"           => "GESTCOTE",
        "id_modulo"       => "TESORERIA",
        "visible"         => "0",
        "orden"           => "10",
        "carpeta"         => "conceptos_tesoreria",
        "archivo"         => "adicionar",
        "requiere_item"   => "0",
        "tabla_principal" => "conceptos_tesoreria",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "CONSCOTE",
        "padre"           => "GESTCOTE",
        "id_modulo"       => "TESORERIA",
        "visible"         => "0",
        "orden"           => "20",
        "carpeta"         => "conceptos_tesoreria",
        "archivo"         => "consultar",
        "requiere_item"   => "1",
        "tabla_principal" => "conceptos_tesoreria",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "MODICOTE",
        "padre"           => "GESTCOTE",
        "id_modulo"       => "TESORERIA",
        "visible"         => "0",
        "orden"           => "30",
        "carpeta"         => "conceptos_tesoreria",
        "archivo"         => "modificar",
        "requiere_item"   => "1",
        "tabla_principal" => "conceptos_tesoreria",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "ELIMCOTE",
        "padre"           => "GESTCOTE",
        "id_modulo"       => "TESORERIA",
        "visible"         => "0",
        "orden"           => "40",
        "carpeta"         => "conceptos_tesoreria",
        "archivo"         => "eliminar",
        "requiere_item"   => "1",
        "tabla_principal" => "conceptos_tesoreria",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "LISTCOTE",
        "padre"           => "GESTCOTE",
        "id_modulo"       => "TESORERIA",
        "visible"         => "0",
        "orden"           => "50",
        "carpeta"         => "conceptos_tesoreria",
        "archivo"         => "listar",
        "requiere_item"   => "1",
        "tabla_principal" => "conceptos_tesoreria",
        "tipo_enlace"     => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_conceptos_tesoreria AS
        SELECT job_conceptos_tesoreria.codigo AS id,
            job_conceptos_tesoreria.codigo AS CODIGO,
            job_conceptos_tesoreria.nombre_concepto AS NOMBRE,
            job_grupos_tesoreria.nombre_grupo AS GRUPO_TESORERIA
        FROM job_conceptos_tesoreria, 
             job_grupos_tesoreria
        WHERE job_conceptos_tesoreria.codigo_grupo_tesoreria = job_grupos_tesoreria.codigo 
        AND job_conceptos_tesoreria.codigo != 0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_conceptos_tesoreria AS
        SELECT job_conceptos_tesoreria.codigo AS id,
            job_conceptos_tesoreria.codigo AS codigo,
            job_conceptos_tesoreria.nombre_concepto AS nombre,
            job_grupos_tesoreria.nombre_grupo AS grupo_tesoreria
        FROM job_conceptos_tesoreria, 
             job_grupos_tesoreria
        WHERE job_conceptos_tesoreria.codigo_grupo_tesoreria = job_grupos_tesoreria.codigo 
        AND job_conceptos_tesoreria.codigo != 0;"
    )
);

?>

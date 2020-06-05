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
$tablas ["tablas"] = array(
    "id"           => "SMALLINT(5) UNSIGNED ZEROFILL AUTO_INCREMENT NOT NULL COMMENT 'Id principal de tabla'",
    "nombre_tabla" => "VARCHAR(255) NOT NULL COMMENT 'Nombre de la tabla'"
);

// Definición de llaves primarias
$llavesPrimarias["tablas"] = "id";

// Definición de llaves unicas **/
$llavesUnicas["tablas"] = array(
    "nombre_tabla"
);

$registros["tablas"] = array(
    array(
        "id"           => "00000",
        "nombre_tabla" => ""
    )
);

$registros["componentes"] = array(
    array(
        "id"           => "GESTTABL",
        "padre"        => "SUBMDCAD",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "1",
        "orden"        => "600",
        "carpeta"      => "tablas",
        "archivo"      => "menu",
        "global"       => "0",
        "requiere_item" => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "ADICTABL",
        "padre"        => "GESTTABL",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "0",
        "orden"        => "20",
        "carpeta"      => "tablas",
        "archivo"      => "adicionar",
        "global"       => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "CONSTABL",
        "padre"        => "GESTTABL",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "0",
        "orden"        => "30",
        "carpeta"      => "tablas",
        "archivo"      => "consultar",
        "global"       => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "MODITABL",
        "padre"        => "GESTTABL",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "0",
        "orden"        => "40",
        "carpeta"      => "tablas",
        "archivo"      => "modificar",
        "global"       => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "ELIMTABL",
        "padre"        => "GESTTABL",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "0",
        "orden"        => "50",
        "carpeta"      => "tablas",
        "archivo"      => "eliminar",
        "global"       => "0",
        "tipo_enlace"  => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE VIEW job_menu_tablas AS
        SELECT id AS id,
        nombre_tabla AS NOMBRE_TABLA
        FROM job_tablas WHERE id != 0;"
    ),
    array(
        "CREATE OR REPLACE VIEW job_buscador_tablas AS
        SELECT id AS id,
        nombre_tabla AS tabla
        FROM job_tablas WHERE id != 0;"
    )
);
?>

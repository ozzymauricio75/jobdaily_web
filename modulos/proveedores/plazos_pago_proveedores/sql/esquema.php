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

$tablas["plazos_pago_proveedores"] = array(
    "codigo"	    => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo interno para la base de datos'",
    "nombre"	    => "VARCHAR(15) NOT NULL COMMENT 'Nombre de la forma de pago a credito asignado por el usuario'",
    "descripcion"   => "VARCHAR(255) NOT NULL COMMENT 'Descripcion de la forma de pago a credito'",
    "periodo"	    => "SMALLINT(2) UNSIGNED NOT NULL COMMENT 'Periodicidad de dias para los pagos dentro del intervalo inicial-final'",
    "inicial"	    => "ENUM('0','30','60','90','120','150','180','210','240','270') NOT NULL DEFAULT '0' COMMENT 'Plazo para pago inicial: 0,30,60,90,120,150,180,210,240,270'",
    "final"		    => "ENUM('0','30','60','90','120','150','180','210','240','270') NOT NULL DEFAULT '0' COMMENT 'Plazo para pago final: 0,30,60,90,120,150,180,210,240,270'",
	"numero_cuotas" => "SMALLINT(3) NOT NULL COMMENT 'Numero de cuotas para el plazo definido'",
    "orden"	        => "SMALLINT(3) UNSIGNED NOT NULL COMMENT 'Orden en el cual salen los datos'"
);

// Definición de llaves primarias
$llavesPrimarias["plazos_pago_proveedores"] = "codigo";

// Definición de llaves unicas
$llavesUnicas["plazos_pago_proveedores"] =  array(
    "nombre"
);

// Registro codigo=0
$registros["plazos_pago_proveedores"] = array(
    array(
        "codigo"        => 0,
        "nombre"        => "",
        "descripcion"   => "",
        "periodo"       => 0,
        "inicial"       => 0,
        "final"         => 0,
        "numero_cuotas" => 0,
        "orden"         => 0
    )
);

// Inserción de datos iniciales***/
$registros["componentes"] = array(
    array(
        "id"        	  => "GESTPPPR",
        "padre"     	  => "SUBMDCPV",
        "id_modulo" 	  => "PROVEEDORES",
        "visible"   	  => "1",
        "orden"     	  => "110",
        "carpeta"   	  => "plazos_pago_proveedores",
        "archivo"  		  => "menu",
		"requiere_item"   => "1",
        "tabla_principal" => "plazos_pago_proveedores",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "ADICPPPR",
        "padre"     	  => "GESTPPPR",
        "id_modulo" 	  => "PROVEEDORES",
        "visible"   	  => "0",
        "orden"     	  => "10",
        "carpeta"   	  => "plazos_pago_proveedores",
        "archivo"   	  => "adicionar",
        "requiere_item"   => "0",
        "tabla_principal" => "plazos_pago_proveedores",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "CONSPPPR",
        "padre"     	  => "GESTPPPR",
        "id_modulo" 	  => "PROVEEDORES",
        "visible"   	  => "0",
        "orden"     	  => "20",
        "carpeta"  	      => "plazos_pago_proveedores",
        "archivo"   	  => "consultar",
        "requiere_item"   => "1",
        "tabla_principal" => "plazos_pago_proveedores",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "MODIPPPR",
        "padre"     	  => "GESTPPPR",
        "id_modulo" 	  => "PROVEEDORES",
        "visible"   	  => "0",
        "orden"     	  => "30",
        "carpeta"   	  => "plazos_pago_proveedores",
        "archivo"   	  => "modificar",
        "requiere_item"   => "1",
        "tabla_principal" => "plazos_pago_proveedores",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"        	  => "ELIMPPPR",
        "padre"     	  => "GESTPPPR",
        "id_modulo" 	  => "PROVEEDORES",
        "visible"   	  => "0",
        "orden"     	  => "40",
        "carpeta"   	  => "plazos_pago_proveedores",
        "archivo"   	  => "eliminar",
        "requiere_item"   => "1",
        "tabla_principal" => "plazos_pago_proveedores",
        "tipo_enlace"     => "1"
    )    
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_plazos_pago_proveedores
        AS 
          SELECT 
            job_plazos_pago_proveedores.codigo AS id, 
            job_plazos_pago_proveedores.nombre AS NOMBRE,
            if (job_plazos_pago_proveedores.inicial = '0', 1, job_plazos_pago_proveedores.inicial) AS INICIAL,
            if (job_plazos_pago_proveedores.final = '0', 1, job_plazos_pago_proveedores.final) AS FINAL,
            job_plazos_pago_proveedores.periodo AS PERIODO,
            job_plazos_pago_proveedores.numero_cuotas AS CUOTAS
          FROM 
            job_plazos_pago_proveedores
          WHERE
            job_plazos_pago_proveedores.codigo != '0';"
    ),
    array(
       "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_plazos_pago_proveedores
        AS 
          SELECT 
            job_plazos_pago_proveedores.codigo AS id,
            job_plazos_pago_proveedores.nombre AS nombre,
            if (job_plazos_pago_proveedores.inicial = '0', 1, job_plazos_pago_proveedores.inicial) AS inicial,
            if (job_plazos_pago_proveedores.final = '0', 1, job_plazos_pago_proveedores.final) AS final
          FROM 
            job_plazos_pago_proveedores
          WHERE
            job_plazos_pago_proveedores.codigo != '0';"
    )
);
/***
    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_plazos_pago_proveedores
    AS 
      SELECT 
	    job_plazos_pago_proveedores.codigo AS id, 
	    job_plazos_pago_proveedores.nombre AS NOMBRE,
	    if (job_plazos_pago_proveedores.inicial = '0', 1, job_plazos_pago_proveedores.inicial) AS INICIAL,
	    if (job_plazos_pago_proveedores.final = '0', 1, job_plazos_pago_proveedores.final) AS FINAL,
	    job_plazos_pago_proveedores.periodo AS PERIODO,
		job_plazos_pago_proveedores.numero_cuotas AS CUOTAS
      FROM 
	    job_plazos_pago_proveedores
      WHERE
	    job_plazos_pago_proveedores.codigo != '0';
      
   CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_plazos_pago_proveedores
    AS 
      SELECT 
	    job_plazos_pago_proveedores.codigo AS id,
	    job_plazos_pago_proveedores.nombre AS nombre,
	    if (job_plazos_pago_proveedores.inicial = '0', 1, job_plazos_pago_proveedores.inicial) AS inicial,
	    if (job_plazos_pago_proveedores.final = '0', 1, job_plazos_pago_proveedores.final) AS final
      FROM 
	    job_plazos_pago_proveedores
      WHERE
	    job_plazos_pago_proveedores.codigo != '0';
*/
?>


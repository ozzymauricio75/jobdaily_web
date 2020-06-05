
    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_retiro_cesantias AS
    SELECT CONCAT(job_retiro_cesantias.documento_identidad_empleado,'|',job_retiro_cesantias.consecutivo,'|',job_retiro_cesantias.fecha_generacion,'|',job_retiro_cesantias.concepto_retiro) AS id,

           CONCAT( IF(job_terceros.primer_nombre IS NOT NULL,
                            CONCAT(
                                CONCAT(job_terceros.primer_nombre,' '),
                                IF(job_terceros.segundo_nombre IS NOT NULL,CONCAT(job_terceros.segundo_nombre,' '),''),
                                IF(job_terceros.primer_apellido IS NOT NULL,CONCAT(job_terceros.primer_apellido,' '),''),
                                IF(job_terceros.segundo_apellido IS NOT NULL,CONCAT(job_terceros.segundo_apellido,''),'')
                            ),
                            job_terceros.razon_social
                        )
                    ) AS NOMBRE_EMPLEADO,
           job_retiro_cesantias.fecha_generacion AS FECHA_GENERACION,
           job_retiro_cesantias.valor_retiro AS VALOR_RETIRO


    FROM job_retiro_cesantias,job_terceros

    WHERE
         job_retiro_cesantias.documento_identidad_empleado = job_terceros.documento_identidad;


    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_retiro_cesantias AS
    SELECT CONCAT(job_retiro_cesantias.documento_identidad_empleado,'|',job_retiro_cesantias.consecutivo,'|',job_retiro_cesantias.fecha_generacion,'|',job_retiro_cesantias.concepto_retiro) AS id,

           CONCAT( IF(job_terceros.primer_nombre IS NOT NULL,
                            CONCAT(
                                CONCAT(job_terceros.primer_nombre,' '),
                                IF(job_terceros.segundo_nombre IS NOT NULL,CONCAT(job_terceros.segundo_nombre,' '),''),
                                IF(job_terceros.primer_apellido IS NOT NULL,CONCAT(job_terceros.primer_apellido,' '),''),
                                IF(job_terceros.segundo_apellido IS NOT NULL,CONCAT(job_terceros.segundo_apellido,''),'')
                            ),
                            job_terceros.razon_social
                        )
                    ) AS nombre_empleado,
           job_retiro_cesantias.fecha_generacion AS fecha_generacion,
           job_retiro_cesantias.valor_retiro AS valor_retiro


    FROM job_retiro_cesantias,job_terceros

    WHERE
         job_retiro_cesantias.documento_identidad_empleado = job_terceros.documento_identidad;

       

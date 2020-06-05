
ALTER TABLE job_ingresos_varios_empleados ADD valor DECIMAL(11,2) NOT NULL DEFAULT 0 COMMENT 'Valor del ingreso vario que recibira el empleado'

INSERT INTO `desarrollo`.`job_preferencias` (
`codigo_empresa` ,
`codigo_sucursal` ,
`codigo_usuario` ,
`tipo_preferencia` ,
`variable` ,
`valor`
)
VALUES (
'123', '0', '0', '2', 'valor_minimo_ingresos_varios', '5000'

------------PARA PERMITIR CASLCULAR LAS PROPORCION DEL VALOR DE LA HORA DIARIA--------------
ALTER TABLE job_sucursal_contrato_empleados ADD dias_mes SMALLINT(3) NOT NULL DEFAULT 30 COMMENT 'Numero de dias que trabajara en el mes' AFTER valor_hora;

ALTER TABLE job_sucursal_contrato_empleados ADD horas_mes SMALLINT(3) NOT NULL DEFAULT 240 COMMENT 'Numero de horas que trabajara en el mes' AFTER dias_mes;

------------ADICIONAR VALOR DE HORA ------------------------

ALTER TABLE job_salario_sucursal_contrato ADD valor_hora DECIMAL(11,2) NOT NULL DEFAULT 0 COMMENT 'Valor de la hora' AFTER valor_dia;
UPDATE job_salario_sucursal_contrato  SET valor_hora = (valor_dia/8);

----2011-04-26 16:00-----
ALTER TABLE `job_salario_sucursal_contrato` CHANGE `fecha_cambio_salario` `fecha_registro` DATETIME NOT NULL COMMENT 'Fecha del sistema en la que se asigna el salario';
ALTER TABLE `job_salario_sucursal_contrato` ADD `fecha_retroactivo` DATE NOT NULL COMMENT 'Fecha a partir de la cual se hace retroactivo en salario' AFTER `fecha_registro`;

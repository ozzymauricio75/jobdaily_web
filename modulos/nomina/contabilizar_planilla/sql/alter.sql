ALTER TABLE `job_forma_pago_planillas` ADD `fecha_registro` DATETIME NOT NULL COMMENT 'Fecha en que se genera el registro',
ADD `codigo_usuario_registra` SMALLINT( 4 ) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo del usuario que genera el registro';

ALTER TABLE job_forma_pago_planillas ADD CONSTRAINT forma_pago_planillas_usuario_registra FOREIGN KEY(codigo_usuario_registra)
REFERENCES job_usuarios(codigo) ON UPDATE CASCADE ON DELETE RESTRICT;

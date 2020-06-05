ALTER TABLE job_movimientos_salud ADD CONSTRAINT movimiento_salud_usuario FOREIGN KEY(codigo_usuario_genera) REFERENCES job_usuarios(codigo) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE job_movimientos_pension ADD CONSTRAINT movimiento_pension_usuario FOREIGN KEY(codigo_usuario_genera) REFERENCES job_usuarios(codigo) ON UPDATE CASCADE ON DELETE RESTRICT;

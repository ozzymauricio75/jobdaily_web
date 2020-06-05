ALTER TABLE job_movimiento_tiempos_laborados CHANGE cantidad cantidad_horas SMALLINT(3) NOT NULL COMMENT 'Cantidad de horas trabajadas';

ALTER TABLE job_movimiento_tiempos_laborados ADD cantidad_minutos SMALLINT(4) NOT NULL DEFAULT 0 COMMENT 'Cantidad de minutos trabajadas' AFTER cantidad_horas;

ALTER TABLE job_movimiento_tiempos_laborados ADD valor_movimiento DECIMAL(15,2) NOT NULL DEFAULT 0 COMMENT 'Valor del movimiento que se genero' AFTER valor_hora_recargo;
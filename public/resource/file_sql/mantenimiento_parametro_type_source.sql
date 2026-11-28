-- =====================================================================
-- Tab "Parametros" en core_user/edit
-- Agrega tb_parameter.type y tb_parameter.source, y pasa
-- tb_company_parameter.customValue a utf8 para aceptar acentos.
-- Ejecutar en CADA base de datos de compania, un paso a la vez.
-- utf8 (no utf8mb4) por compatibilidad con MariaDB 5.5.
-- =====================================================================

-- Paso 1: tipo de control con el que se edita el parametro
-- Valores: bool, int, decimal, text, datetime, combobox. NULL = detectar por value.
ALTER TABLE `tb_parameter`
    ADD COLUMN `type` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;

-- Paso 2: opciones del combobox separadas por coma (ej. COST,PRECE)
ALTER TABLE `tb_parameter`
    ADD COLUMN `source` varchar(1000) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;

-- Paso 3: customValue a utf8 (la columna no tiene indice; MySQL convierte los datos existentes)
ALTER TABLE `tb_company_parameter`
    MODIFY COLUMN `customValue` varchar(300) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0';

-- Verificar
SHOW FULL COLUMNS FROM `tb_parameter`;
SHOW FULL COLUMNS FROM `tb_company_parameter`;

-- Ejemplo para configurar un parametro (cambiar el nombre):
-- UPDATE tb_parameter SET `type` = 'combobox', `source` = 'COST,PRECE' WHERE name = 'NOMBRE_DEL_PARAMETRO';

-- Revertir (solo si hace falta)
-- ALTER TABLE `tb_company_parameter` MODIFY COLUMN `customValue` varchar(300) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0';
-- ALTER TABLE `tb_parameter` DROP COLUMN `source`;
-- ALTER TABLE `tb_parameter` DROP COLUMN `type`;

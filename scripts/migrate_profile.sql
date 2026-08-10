-- Aggiunge colonne profilo esteso alla tabella profile.
-- Idempotente: usa INFORMATION_SCHEMA per verificare prima di aggiungere.

DROP PROCEDURE IF EXISTS _utc_add_col;
DELIMITER //
CREATE PROCEDURE _utc_add_col(IN tbl VARCHAR(64), IN col VARCHAR(64), IN def TEXT)
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = tbl AND COLUMN_NAME = col
    ) THEN
        SET @_sql = CONCAT('ALTER TABLE `', tbl, '` ADD COLUMN `', col, '` ', def);
        PREPARE _stmt FROM @_sql;
        EXECUTE _stmt;
        DEALLOCATE PREPARE _stmt;
    END IF;
END //
DELIMITER ;

CALL _utc_add_col('profile', 'nome',      'varchar(100) DEFAULT NULL AFTER fullname');
CALL _utc_add_col('profile', 'cognome',   'varchar(100) DEFAULT NULL AFTER nome');
CALL _utc_add_col('profile', 'telefono',  'varchar(30)  DEFAULT NULL AFTER user_id');
CALL _utc_add_col('profile', 'cellulare', 'varchar(30)  DEFAULT NULL AFTER telefono');
CALL _utc_add_col('profile', 'ruolo',     'varchar(100) DEFAULT NULL AFTER cellulare');
CALL _utc_add_col('profile', 'bio',       'text         DEFAULT NULL AFTER ruolo');
CALL _utc_add_col('profile', 'avatar',    'varchar(255) DEFAULT NULL AFTER bio');

DROP PROCEDURE IF EXISTS _utc_add_col;

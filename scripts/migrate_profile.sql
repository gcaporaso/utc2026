-- Aggiunge le colonne del profilo esteso alla tabella profile.
-- Sicuro: usa ALTER TABLE ... ADD COLUMN IF NOT EXISTS (MySQL 8.0+).

ALTER TABLE `profile`
  ADD COLUMN IF NOT EXISTS `nome`      varchar(100) DEFAULT NULL AFTER `fullname`,
  ADD COLUMN IF NOT EXISTS `cognome`   varchar(100) DEFAULT NULL AFTER `nome`,
  ADD COLUMN IF NOT EXISTS `telefono`  varchar(30)  DEFAULT NULL AFTER `user_id`,
  ADD COLUMN IF NOT EXISTS `cellulare` varchar(30)  DEFAULT NULL AFTER `telefono`,
  ADD COLUMN IF NOT EXISTS `ruolo`     varchar(100) DEFAULT NULL AFTER `cellulare`,
  ADD COLUMN IF NOT EXISTS `bio`       text         DEFAULT NULL AFTER `ruolo`,
  ADD COLUMN IF NOT EXISTS `avatar`    varchar(255) DEFAULT NULL AFTER `bio`;

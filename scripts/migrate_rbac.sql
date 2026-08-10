-- =============================================================================
-- migrate_rbac.sql
-- Registra le route /admin/* nel sistema RBAC e le assegna al permesso
-- "Gestione Utenti", in modo che solo gli utenti con tale permesso (o con
-- il ruolo Amministratore che lo eredita) possano accedere alle pagine
-- di gestione utenti/ruoli/permessi/assegnazioni.
--
-- Eseguire una sola volta (INSERT IGNORE evita duplicati).
-- =============================================================================

-- Route wildcard per tutte le azioni del modulo admin
INSERT IGNORE INTO auth_item (name, type, description, created_at, updated_at)
VALUES
  ('/admin/*',            2, 'Accesso area Configurazione utenti e RBAC', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
  ('/profilo/*',          2, 'Accesso area Profilo utente',               UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
  ('/db-backup/*',        2, 'Accesso area Backup database',              UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
  ('/progetti-gis/*',     2, 'Accesso Progetti GIS',                      UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

-- Assegna /admin/* al permesso "Gestione Utenti"
INSERT IGNORE INTO auth_item_child (parent, child)
VALUES ('Gestione Utenti', '/admin/*');

-- Profilo accessibile a tutti i ruoli principali
INSERT IGNORE INTO auth_item_child (parent, child)
VALUES
  ('Amministratore',         '/profilo/*'),
  ('Responsabile Edilizia',  '/profilo/*'),
  ('RUP Pratiche Edilizie',  '/profilo/*'),
  ('Tecnico Comunale',       '/profilo/*'),
  ('Consigliere Comunale',   '/profilo/*'),
  ('Cittadino',              '/profilo/*');

-- Backup accessibile solo a Amministratore e Responsabile Edilizia
INSERT IGNORE INTO auth_item_child (parent, child)
VALUES
  ('Amministratore',        '/db-backup/*'),
  ('Responsabile Edilizia', '/db-backup/*');

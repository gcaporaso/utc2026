-- Migrazione: tabella log interazioni chatbot AI
-- Eseguire con: mysql -u utcuser -p utcbim < scripts/migrate_ai_log.sql

CREATE TABLE IF NOT EXISTS `ai_chat_log` (
  `id`             int          NOT NULL AUTO_INCREMENT,
  `created_at`     datetime     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_query`     text         NOT NULL              COMMENT 'Domanda originale in linguaggio naturale',
  `action_type`    varchar(20)  NOT NULL DEFAULT ''   COMMENT 'sql | map | clarify | error',
  `sql_query`      text                               COMMENT 'Query SQL generata (se type=sql)',
  `map_action`     varchar(50)  DEFAULT NULL          COMMENT 'Azione Leaflet (se type=map)',
  `row_count`      int          DEFAULT NULL          COMMENT 'Righe restituite dalla query',
  `response_ok`    tinyint(1)   NOT NULL DEFAULT 1    COMMENT '1=risposta corretta, 0=errore',
  `feedback`       tinyint(1)   DEFAULT NULL          COMMENT '1=feedback positivo, 0=negativo, NULL=nessuno',
  `feedback_note`  varchar(500) DEFAULT NULL          COMMENT 'Note facoltative sul feedback negativo',
  `model_name`     varchar(100) DEFAULT NULL          COMMENT 'Modello Ollama usato',
  `response_ms`    int          DEFAULT NULL          COMMENT 'Tempo risposta in millisecondi',
  PRIMARY KEY (`id`),
  KEY `idx_created_at`  (`created_at`),
  KEY `idx_action_type` (`action_type`),
  KEY `idx_feedback`    (`feedback`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Log interazioni chatbot AI per analisi e fine-tuning';

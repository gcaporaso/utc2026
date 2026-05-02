-- Migrazione sicura: crea tabelle GIS solo se non esistono.
-- Non tocca dati esistenti.

CREATE TABLE IF NOT EXISTS `gis_progetti` (
  `id`          int          NOT NULL AUTO_INCREMENT,
  `nome`        varchar(255) NOT NULL,
  `descrizione` text,
  `tipo`        enum('opera_pubblica','rilievo_topografico') NOT NULL,
  `created_at`  timestamp    NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  timestamp    NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by`  int          DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `gis_layers` (
  `id`             int          NOT NULL AUTO_INCREMENT,
  `progetto_id`    int          NOT NULL,
  `nome`           varchar(255) NOT NULL,
  `tipo_geometria` varchar(50)  NOT NULL,
  `srid`           int          NOT NULL DEFAULT 4326,
  `stile`          json         DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_gl_progetto` (`progetto_id`),
  CONSTRAINT `fk_gl_progetto`
    FOREIGN KEY (`progetto_id`) REFERENCES `gis_progetti` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `gis_features` (
  `id`         int      NOT NULL AUTO_INCREMENT,
  `layer_id`   int      NOT NULL,
  `geometria`  geometry NOT NULL /*!80003 SRID 4326 */,
  `proprieta`  json     DEFAULT NULL,
  PRIMARY KEY (`id`),
  SPATIAL KEY `idx_geometria` (`geometria`),
  KEY `fk_gf_layer` (`layer_id`),
  CONSTRAINT `fk_gf_layer`
    FOREIGN KEY (`layer_id`) REFERENCES `gis_layers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

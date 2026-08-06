-- Migrazione sicura: crea le tabelle del modulo IMU solo se non esistono,
-- e inserisce le aliquote/coefficienti/aree edificabili già deliberate
-- (dati amministrativi pubblici, non dati personali di contribuenti).
-- Non tocca dati esistenti: le INSERT usano INSERT IGNORE, quindi rieseguire
-- questo script su un database già popolato non duplica né sovrascrive nulla.

CREATE TABLE IF NOT EXISTS `imu_aliquote` (
  `id` int NOT NULL AUTO_INCREMENT,
  `anno` smallint NOT NULL DEFAULT '2025',
  `tipo` varchar(60) NOT NULL COMMENT 'abitazione_principale|altri_immobili|immobili_produttivi_d|aree_fabbricabili|terreni_agricoli',
  `descrizione` varchar(255) NOT NULL,
  `aliquota` decimal(6,4) NOT NULL DEFAULT '0.0000' COMMENT 'es. 0.0076 = 7,6 per mille',
  `detrazione` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'detrazione annuale in euro',
  `attiva` tinyint(1) NOT NULL DEFAULT '1',
  `note` text,
  PRIMARY KEY (`id`),
  KEY `idx_anno_tipo` (`anno`,`tipo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Aliquote IMU deliberate dal Comune';

CREATE TABLE IF NOT EXISTS `imu_coefficienti` (
  `id` int NOT NULL AUTO_INCREMENT,
  `categoria` varchar(10) NOT NULL COMMENT 'Categoria catastale: A,A/10,B,C/1,C/2,C/3,D,D/5,...',
  `coefficiente` int NOT NULL DEFAULT '160' COMMENT 'Coefficiente moltiplicatore rendita (D.Lgs.201/2011)',
  `descrizione` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_categoria` (`categoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Coefficienti catastali per calcolo IMU (art.13 DL 201/2011)';

CREATE TABLE IF NOT EXISTS `imu_aree_edificabili` (
  `id` int NOT NULL AUTO_INCREMENT,
  `anno` int NOT NULL,
  `zona` varchar(50) NOT NULL,
  `descrizione` varchar(255) NOT NULL DEFAULT '',
  `valore_mq` decimal(12,2) NOT NULL DEFAULT '0.00',
  `attiva` tinyint(1) NOT NULL DEFAULT '1',
  `note` text,
  PRIMARY KEY (`id`),
  KEY `idx_anno` (`anno`),
  KEY `idx_zona` (`zona`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `ici_forniture` (
  `id`             INT AUTO_INCREMENT PRIMARY KEY,
  `nome_file`      VARCHAR(200) NOT NULL,
  `anno_mese`      VARCHAR(7)   DEFAULT NULL COMMENT 'YYYY-MM',
  `data_inizio`    DATE         DEFAULT NULL,
  `data_fine`      DATE         DEFAULT NULL,
  `num_variazioni` INT          DEFAULT '0',
  `num_soggetti`   INT          DEFAULT '0',
  `importato_il`   TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
  `note`           TEXT,
  KEY `idx_anno_mese` (`anno_mese`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Log forniture mensili ICI/IMU dal portale ANCI';

CREATE TABLE IF NOT EXISTS `ici_variazioni` (
  `id`                    INT AUTO_INCREMENT PRIMARY KEY,
  `fornitura_id`          INT           NOT NULL,
  `anno_mese`             VARCHAR(7)    NOT NULL,
  `numero_nota`           INT           DEFAULT NULL,
  `anno_nota`             SMALLINT      DEFAULT NULL,
  `data_presentazione`    DATE          DEFAULT NULL COMMENT 'DataPresentazioneAtto — data giuridicamente rilevante',
  `data_validita_atto`    DATE          DEFAULT NULL COMMENT 'DataValiditaAtto — data stipula',
  `esito_nota`            TINYINT       DEFAULT NULL COMMENT '0=registrata 1=parziale 2=non registrata',
  `codice_fiscale`        VARCHAR(16)   NOT NULL,
  `cognome`               VARCHAR(100)  DEFAULT NULL,
  `nome`                  VARCHAR(100)  DEFAULT NULL,
  `tipo_variazione`       CHAR(1)       NOT NULL COMMENT 'A=acquisizione C=cessione',
  `codice_diritto`        VARCHAR(10)   DEFAULT NULL,
  `quota_numeratore`      BIGINT        DEFAULT NULL,
  `quota_denominatore`    BIGINT        DEFAULT NULL,
  `tipologia_immobile`    CHAR(1)       DEFAULT NULL COMMENT 'F=fabbricato T=terreno',
  `foglio`                VARCHAR(10)   DEFAULT NULL,
  `numero`                VARCHAR(10)   DEFAULT NULL,
  `subalterno`            VARCHAR(10)   DEFAULT NULL,
  `id_catastale_immobile` BIGINT        DEFAULT NULL,
  `categoria`             VARCHAR(10)   DEFAULT NULL,
  `classe`                VARCHAR(5)    DEFAULT NULL,
  `superficie`            INT           DEFAULT NULL,
  `rendita`               DECIMAL(12,2) DEFAULT NULL COMMENT 'in euro (convertito da centesimi)',
  `indirizzo`             VARCHAR(150)  DEFAULT NULL,
  `dominicale`            DECIMAL(12,2) DEFAULT NULL,
  `agrario`               DECIMAL(12,2) DEFAULT NULL,
  UNIQUE KEY `uq_variazione` (`numero_nota`, `anno_nota`, `codice_fiscale`, `tipo_variazione`, `foglio`, `numero`, `subalterno`),
  KEY `idx_cf`         (`codice_fiscale`),
  KEY `idx_data`       (`data_presentazione`),
  KEY `idx_foglio`     (`foglio`, `numero`, `subalterno`),
  KEY `idx_anno_mese`  (`anno_mese`),
  KEY `idx_fornitura`  (`fornitura_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Variazioni catastali mensili da forniture ICI/IMU portale ANCI';

CREATE TABLE IF NOT EXISTS `imu_f24_pagamenti` (
  `id`                  INT AUTO_INCREMENT PRIMARY KEY,
  `anno_riferimento`    SMALLINT      NOT NULL,
  `codice_fiscale`      VARCHAR(16)   NOT NULL,
  `codice_fiscale_orig` VARCHAR(16)   DEFAULT NULL,
  `codice_tributo`      VARCHAR(4)    NOT NULL,
  `tipo_imposta`        CHAR(1)       NOT NULL DEFAULT 'I',
  `data_riscossione`    DATE          DEFAULT NULL,
  `data_fornitura`      DATE          DEFAULT NULL,
  `data_ripartizione`   DATE          DEFAULT NULL,
  `importo_debito`      DECIMAL(10,2) NOT NULL DEFAULT '0.00',
  `importo_credito`     DECIMAL(10,2) NOT NULL DEFAULT '0.00',
  `detrazione`          DECIMAL(10,2) NOT NULL DEFAULT '0.00',
  `acconto`             TINYINT(1)    NOT NULL DEFAULT '0',
  `saldo`               TINYINT(1)    NOT NULL DEFAULT '0',
  `ravvedimento`        TINYINT(1)    NOT NULL DEFAULT '0',
  `immobili_variati`    TINYINT(1)    NOT NULL DEFAULT '0',
  `num_fabbricati`      SMALLINT      NOT NULL DEFAULT '0',
  `progressivo_delega`  VARCHAR(6)    DEFAULT NULL,
  `progressivo_riga`    TINYINT       DEFAULT NULL,
  `codice_ente_comunale` VARCHAR(4)   DEFAULT NULL,
  `denominazione`       VARCHAR(39)   DEFAULT NULL,
  `nome_contribuente`   VARCHAR(20)   DEFAULT NULL,
  `sesso`               CHAR(1)       DEFAULT NULL,
  `data_nascita`        DATE          DEFAULT NULL,
  `comune_nascita`      VARCHAR(25)   DEFAULT NULL,
  `provincia_nascita`   VARCHAR(2)    DEFAULT NULL,
  `file_origine`        VARCHAR(100)  DEFAULT NULL,
  `importato_il`        TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `uq_g1_record` (`codice_fiscale`, `codice_tributo`, `anno_riferimento`, `data_riscossione`, `progressivo_delega`, `progressivo_riga`),
  KEY `idx_cf_anno`   (`codice_fiscale`, `anno_riferimento`),
  KEY `idx_anno`      (`anno_riferimento`),
  KEY `idx_file`      (`file_origine`(50))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Pagamenti IMU da forniture SOGEI F24';

CREATE TABLE IF NOT EXISTS `imu_f24_forniture` (
  `id`               INT AUTO_INCREMENT PRIMARY KEY,
  `nome_file`        VARCHAR(200) NOT NULL,
  `data_fornitura`   DATE         DEFAULT NULL,
  `anno_riferimento` SMALLINT     DEFAULT NULL,
  `num_record`       INT          DEFAULT '0',
  `num_imu`          INT          DEFAULT '0',
  `importato_il`     TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
  `note`             TEXT,
  KEY `idx_anno` (`anno_riferimento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Log forniture F24 SOGEI importate';

-- Dati già deliberati (esportati dal database di sviluppo il 2026-08-03)

INSERT IGNORE INTO `imu_aliquote` (`id`, `anno`, `tipo`, `descrizione`, `aliquota`, `detrazione`, `attiva`, `note`) VALUES
(1,2025,'abitazione_principale','Abitazione principale (cat. A2-A7)',0.0000,200.00,1,NULL),
(2,2025,'altri_immobili','Altri fabbricati (abitazioni in affitto, seconde case, ecc.)',0.0106,0.00,1,NULL),
(3,2025,'immobili_produttivi_d','Immobili categoria D (produttivi, capannoni, alberghi)',0.0106,0.00,1,NULL),
(4,2025,'aree_fabbricabili','Aree fabbricabili',0.0106,0.00,1,NULL),
(12,2026,'abpr_std','Abitazione principale',0.0000,0.00,1,''),
(13,2026,'pertinenza_std','Pertinenze dell\'abitazione principale',0.0000,0.00,1,''),
(14,2026,'assimilata_anziani','Abitazione assimilata a quella principale',0.0000,0.00,1,''),
(15,2026,'abpr_lusso','Abitazioni di pregio e/o di lusso',0.0040,0.00,1,''),
(16,2026,'pertinenza_lusso','Pertinenze delle abitazioni di pregio e/o di lusso',0.0040,0.00,1,''),
(17,2026,'altra_abitazione','Tutte le altre abitazioni',0.0106,0.00,1,''),
(18,2026,'a10_uffici','Uffici e studi',0.0106,0.00,1,''),
(19,2026,'c1_negozi','Negozi',0.0106,0.00,1,''),
(20,2026,'c2_magazzini','Magazzini e deposoti non pertinenza',0.0106,0.00,1,''),
(21,2026,'c3_laboratori','Laboratori',0.0106,0.00,1,''),
(22,2026,'b_c4_c5','Altri Fabbricati',0.0106,0.00,1,''),
(23,2026,'c6_c7_stalle','Stalle, scuderie',0.0106,0.00,1,''),
(24,2026,'cat_d','immobili industriali',0.0106,0.00,1,''),
(25,2026,'d5_credito','Istituti di credito',0.0106,0.00,1,''),
(26,2026,'d10_rurale','Fabbricati agricoli strumentali',0.0010,0.00,1,''),
(27,2026,'rurale_abc','Altri fabbricati agricoli',0.0010,0.00,1,''),
(28,2026,'area_fabbricabile','10,6',0.0000,0.00,1,''),
(29,2026,'comodato50','Abitazione in comodato',0.0106,0.00,1,''),
(30,2026,'comodato_noriduziome','abitazione in comodato',0.0106,0.00,1,''),
(31,2026,'iacp','Iacp',0.0106,0.00,1,''),
(32,2026,'terreno_agricolo','terreni agricoli',0.0000,0.00,1,'');

INSERT IGNORE INTO `imu_coefficienti` (`id`, `categoria`, `coefficiente`, `descrizione`) VALUES
(1,'A',160,'Abitazioni (A/1, A/2, A/3, A/4, A/5, A/6, A/7, A/8, A/9)'),
(2,'A/10',80,'Uffici e studi privati'),
(3,'B',140,'Collegi, ospizi, conventi, carceri, uffici pubblici'),
(4,'C/1',55,'Negozi e botteghe'),
(5,'C/2',160,'Magazzini e locali di deposito'),
(6,'C/3',140,'Laboratori per arti e mestieri'),
(7,'C/4',140,'Fabbricati e locali per esercizi sportivi (non A/P)'),
(8,'C/5',140,'Stabilimenti balneari e di acque curative'),
(9,'C/6',160,'Box e autorimesse'),
(10,'C/7',160,'Tettoie chiuse o aperte'),
(11,'D',65,'Opifici, alberghi, teatri, banche, pensioni'),
(12,'D/5',80,'Istituti di credito, cambio e assicurazione');

INSERT IGNORE INTO `imu_aree_edificabili` (`id`, `anno`, `zona`, `descrizione`, `valore_mq`, `attiva`, `note`) VALUES
(1,2026,'B','',20.00,1,'Delibera della Giunta Comunale n. 82 del 02/12/2025'),
(2,2026,'A','',22.50,1,''),
(3,2026,'C1','',17.50,1,''),
(4,2026,'C2','',17.50,1,''),
(5,2026,'C3','',17.50,1,''),
(6,2026,'C4','',17.50,1,''),
(7,2026,'Ct','Zona turistica',15.00,1,''),
(8,2026,'H','Zona Ospedaliera',15.00,1,''),
(9,2026,'D','Artigianale Pantanelle',15.00,1,''),
(10,2026,'D','Artigianale Provinciale e San Nicola Vecchio',12.50,1,''),
(11,2026,'C3I','Area Residenziale di sviluppo',17.50,1,''),
(12,2026,'C5','Zona residenziale di espansione',17.50,1,''),
(13,2026,'C6','Zona residenziale di espansione',17.50,1,''),
(14,2026,'C8','Zona residenziale di espansione',17.50,1,''),
(15,2026,'C11','Zona residenziale di espansione',17.50,1,''),
(16,2026,'C12','Zona Residenziale di espansione',17.50,1,'delibera della giunta comunale n. 82 del 02/12/2025'),
(17,2026,'C2I','Zona residenziale di espansione',17.50,1,'delibera della giunta comunale n. 82 del 02/12/2025'),
(18,2026,'C7','Zona residenziale di espansione',15.00,1,'delibera della giunta comunale n. 82 del 02/12/2025'),
(19,2026,'C9','Zona residenziale di espansione',15.00,1,'delibera della giunta comunale n. 82 del 02/12/2025'),
(20,2026,'C10','Zona residenziale di espansione',15.00,1,'delibera della giunta comunale n. 82 del 02/12/2025'),
(21,2026,'C1I','Zona residenziale di espansione',15.00,1,'delibera della giunta comunale n. 82 del 02/12/2025'),
(22,2026,'B1','Zona di completamento',20.00,1,'delibera della giunta comunale n. 82 del 02/12/2025');

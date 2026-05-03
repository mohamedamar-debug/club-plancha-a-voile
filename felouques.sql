-- ============================================================
-- Base de données : Club des Felouques de Kerkennah
-- Moteur : MySQL 8.0+ | Encodage : UTF8MB4
-- ============================================================

CREATE DATABASE IF NOT EXISTS felouques_kerkennah
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE felouques_kerkennah;

-- ─────────────────────────────────────────────────────────────
-- TABLE : membres
-- Stocke les demandes d'inscription au club
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS membres (
    id               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    prenom           VARCHAR(80)  NOT NULL,
    nom              VARCHAR(80)  NOT NULL,
    email            VARCHAR(180) NOT NULL UNIQUE,
    telephone        VARCHAR(20)  DEFAULT NULL,
    date_naissance   DATE         NOT NULL,
    nationalite      VARCHAR(60)  DEFAULT NULL,
    genre            ENUM('homme','femme','autre') NOT NULL,
    niveau_navigation ENUM('debutant','intermediaire','confirme','expert') NOT NULL,
    -- Stockage JSON des cases à cocher multiples
    activites        JSON         DEFAULT NULL,
    disponibilites   JSON         DEFAULT NULL,
    message          TEXT         DEFAULT NULL,
    source_info      VARCHAR(30)  DEFAULT NULL,
    newsletter       TINYINT(1)   NOT NULL DEFAULT 0,
    -- Gestion administrative
    statut           ENUM('en_attente','valide','refuse') NOT NULL DEFAULT 'en_attente',
    mot_de_passe     VARCHAR(255) DEFAULT NULL COMMENT 'Hash Argon2id — renseigné après validation',
    created_at       DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at       DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_email   (email),
    INDEX idx_statut  (statut),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ─────────────────────────────────────────────────────────────
-- TABLE : evenements
-- Calendrier des courses et régates
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS evenements (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom_epreuve     VARCHAR(200) NOT NULL,
    date_debut      DATE         NOT NULL,
    date_fin        DATE         DEFAULT NULL COMMENT 'Renseigné pour les épreuves multi-jours',
    heure_depart    TIME         NOT NULL,
    parcours        VARCHAR(300) NOT NULL,
    distance_mn     VARCHAR(20)  DEFAULT NULL COMMENT 'En milles nautiques',
    categorie       VARCHAR(100) NOT NULL,
    statut          ENUM('ouvert','complet','bientot','annule') NOT NULL DEFAULT 'bientot',
    description     TEXT         DEFAULT NULL,
    places_max      SMALLINT UNSIGNED DEFAULT NULL,
    created_at      DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_date_debut (date_debut),
    INDEX idx_statut     (statut)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ─────────────────────────────────────────────────────────────
-- TABLE : inscriptions_courses
-- Relation N:N entre membres et événements
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS inscriptions_courses (
    id               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    membre_id        INT UNSIGNED NOT NULL,
    evenement_id     INT UNSIGNED NOT NULL,
    date_inscription DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    statut           ENUM('confirme','liste_attente','annule') NOT NULL DEFAULT 'confirme',

    UNIQUE KEY uq_membre_event (membre_id, evenement_id),
    FOREIGN KEY (membre_id)    REFERENCES membres(id)    ON DELETE CASCADE,
    FOREIGN KEY (evenement_id) REFERENCES evenements(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ─────────────────────────────────────────────────────────────
-- DONNÉES INITIALES : Calendrier 2025
-- ─────────────────────────────────────────────────────────────
INSERT INTO evenements
    (nom_epreuve, date_debut, heure_depart, parcours, distance_mn, categorie, statut) VALUES
('Régate d\'Ouverture — Coupe du Printemps',     '2025-03-15', '08:00', 'Port Sidi Fredj → Pointe Sable → Retour', '12', 'Toutes catégories',        'complet'),
('Championnat Juniors — Manche 1',                '2025-04-05', '09:00', 'Lagune de Gharbi — Boucle',                '8',  'Juniors (–25 ans)',         'complet'),
('Régate Tradition — Felouques Classiques',       '2025-04-19', '07:30', 'Gharbi → Chergui → Port Sfax',            '22', 'Felouques antérieures à 1980', 'ouvert'),
('Coupe des Gouvernorats — Sélective',            '2025-05-03', '08:00', 'Parcours côtier inter-îles',               '18', 'Séniors confirmés',         'ouvert'),
('Grande Traversée du Golfe',                     '2025-05-17', '06:00', 'Kerkennah → Mahdia aller-retour',          '65', 'Expert / Confirmé',         'ouvert'),
('Championnat Juniors — Manche 2',                '2025-06-07', '09:00', 'Lagune de Gharbi — Sprint',                '6',  'Juniors (–25 ans)',         'ouvert'),
('Nuit des Felouques — Course Nocturne',          '2025-06-21', '21:00', 'Circuit Illuminé — Lagon de Chergui',     '10', 'Séniors (navigation nocturne)', 'bientot'),
('Festival Nautique de Kerkennah',                '2025-07-12', '10:00', 'Festivités & Démonstrations',              NULL, 'Tout public',               'bientot'),
('Championnat Tunisien de Felouque — Sélective',  '2025-08-02', '07:00', 'Parcours officiel FTVAN',                  '25', 'National — Qualifiés',      'bientot'),
('Championnat Tunisien de Felouque — Finale',     '2025-08-23', '08:00', 'Grand Parcours du Golfe de Gabès',        '40', 'National — Élite',          'bientot'),
('Régate de la Rentrée — Coupe d\'Automne',       '2025-09-20', '09:00', 'Tour de l\'Île de Gharbi',                '15', 'Toutes catégories',         'bientot'),
('Clôture de Saison — Trophée du Président',      '2025-10-18', '10:00', 'Parcours de clôture + Cérémonie',         '12', 'Toutes catégories',         'bientot');

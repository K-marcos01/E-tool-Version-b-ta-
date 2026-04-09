-- ═══════════════════════════════════════════════════════════════
-- schema_boutiques.sql
-- Tables à ajouter / compléter dans la base de données existante
-- ─────────────────────────────────────────────────────────────
-- Ce fichier suppose que la table `users` existe déjà dans ta DB.
-- Il crée ou complète les tables manquantes pour la gestion des
-- boutiques, catégories et produits.
--
-- Ordre d'exécution : respecter l'ordre ci-dessous à cause des
-- contraintes de clé étrangère (FOREIGN KEY).
--
-- 1. categories
-- 2. shops        (référence users)
-- 3. products     (référence shops + categories)
-- ═══════════════════════════════════════════════════════════════

-- ────────────────────────────────────────────────────────────
-- PRÉREQUIS : colonnes à ajouter à ta table `users` existante
-- (à n'exécuter QUE si ces colonnes n'existent pas déjà)
-- ────────────────────────────────────────────────────────────
ALTER TABLE users
    ADD COLUMN IF NOT EXISTS bio    TEXT          NULL COMMENT 'Biographie / description du vendeur',
    ADD COLUMN IF NOT EXISTS avatar VARCHAR(500)  NULL COMMENT 'URL de l\'avatar de l\'utilisateur';


-- ════════════════════════════════════════════════════════════
-- TABLE : categories
-- Catégories de produits (Vêtements, Céramique, Épices, etc.)
-- ════════════════════════════════════════════════════════════
CREATE TABLE IF NOT EXISTS categories (
    id         INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    name       VARCHAR(100)    NOT NULL COMMENT 'Nom affiché de la catégorie',
    slug       VARCHAR(100)    NOT NULL COMMENT 'Identifiant URL-friendly (ex: ceramique)',
    icon       VARCHAR(100)        NULL COMMENT 'Nom d\'icône ou classe CSS (optionnel)',
    created_at TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    UNIQUE KEY uq_categories_slug (slug)

) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Catégories de produits de la marketplace';


-- ════════════════════════════════════════════════════════════
-- TABLE : shops
-- Boutiques créées par les vendeurs (users)
-- ════════════════════════════════════════════════════════════
CREATE TABLE IF NOT EXISTS shops (
    id            INT UNSIGNED    NOT NULL AUTO_INCREMENT,

    -- Lien vers le vendeur propriétaire
    user_id       INT UNSIGNED    NOT NULL COMMENT 'ID du vendeur (FK → users.id)',

    -- Informations de la boutique
    name          VARCHAR(150)    NOT NULL COMMENT 'Nom public de la boutique',
    description   TEXT                NULL COMMENT 'Description longue de la boutique',
    location      VARCHAR(200)        NULL COMMENT 'Ville / pays affiché',

    -- Médias
    banner_image  VARCHAR(500)        NULL COMMENT 'URL de l\'image bannière (hero)',
    avatar_image  VARCHAR(500)        NULL COMMENT 'URL de l\'avatar / logo de la boutique',

    -- Métriques
    rating        DECIMAL(3,2)    NOT NULL DEFAULT 0.00 COMMENT 'Note moyenne (0.00 → 5.00)',

    -- Statuts
    is_open       TINYINT(1)      NOT NULL DEFAULT 1 COMMENT '1 = ouvert, 0 = fermé temporairement',
    is_active     TINYINT(1)      NOT NULL DEFAULT 1 COMMENT '1 = visible, 0 = désactivé / banni',

    -- Timestamps
    created_at    TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    KEY idx_shops_user_id  (user_id),
    KEY idx_shops_is_active (is_active),

    CONSTRAINT fk_shops_user
        FOREIGN KEY (user_id) REFERENCES users (id)
        ON DELETE CASCADE          -- Si le compte vendeur est supprimé, ses boutiques aussi
        ON UPDATE CASCADE

) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Boutiques des vendeurs de la marketplace';


-- ════════════════════════════════════════════════════════════
-- TABLE : products
-- Produits listés dans les boutiques
-- ════════════════════════════════════════════════════════════
CREATE TABLE IF NOT EXISTS products (
    id            INT UNSIGNED    NOT NULL AUTO_INCREMENT,

    -- Liens vers boutique et catégorie
    shop_id       INT UNSIGNED    NOT NULL COMMENT 'Boutique propriétaire (FK → shops.id)',
    category_id   INT UNSIGNED        NULL COMMENT 'Catégorie du produit (FK → categories.id)',

    -- Informations produit
    name          VARCHAR(200)    NOT NULL COMMENT 'Nom du produit',
    description   TEXT                NULL COMMENT 'Description détaillée',

    -- Tarification
    price         DECIMAL(10,2)   NOT NULL COMMENT 'Prix de vente actuel (TTC)',
    old_price     DECIMAL(10,2)       NULL COMMENT 'Ancien prix (avant promo), NULL si pas de promo',

    -- Média
    image         VARCHAR(500)        NULL COMMENT 'URL de l\'image principale',

    -- Gestion des stocks
    stock         INT             NOT NULL DEFAULT 0 COMMENT 'Quantité disponible en stock',

    -- Métriques
    rating        DECIMAL(3,2)    NOT NULL DEFAULT 0.00 COMMENT 'Note moyenne du produit (0 → 5)',

    -- Flags
    is_new        TINYINT(1)      NOT NULL DEFAULT 0 COMMENT '1 = badge "Nouveau" affiché',
    is_active     TINYINT(1)      NOT NULL DEFAULT 1 COMMENT '1 = visible, 0 = masqué',

    -- Timestamps
    created_at    TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    KEY idx_products_shop_id     (shop_id),
    KEY idx_products_category_id (category_id),
    KEY idx_products_is_active   (is_active),

    CONSTRAINT fk_products_shop
        FOREIGN KEY (shop_id) REFERENCES shops (id)
        ON DELETE CASCADE          -- Suppression boutique → suppression produits
        ON UPDATE CASCADE,

    CONSTRAINT fk_products_category
        FOREIGN KEY (category_id) REFERENCES categories (id)
        ON DELETE SET NULL         -- Suppression catégorie → category_id = NULL (pas de cascade)
        ON UPDATE CASCADE

) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Produits listés dans les boutiques';


-- ════════════════════════════════════════════════════════════
-- DONNÉES DE DÉMONSTRATION
-- À supprimer / remplacer en production
-- ════════════════════════════════════════════════════════════

-- ── Catégories ───────────────────────────────────────────────
INSERT IGNORE INTO categories (name, slug) VALUES
    ('Décoration',   'decoration'),
    ('Vêtements',    'vetements'),
    ('Bijoux',       'bijoux'),
    ('Épicerie',     'epicerie'),
    ('Céramique',    'ceramique'),
    ('Maroquinerie', 'maroquinerie');

-- ── Boutique de démo (suppose user_id = 1 dans ta table users) ──
-- Remplace user_id = 1 par un vrai ID existant dans ta base
INSERT IGNORE INTO shops
    (id, user_id, name, description, location, rating, is_open, is_active)
VALUES
    (1, 1,
     'Maison & Sens',
     'Boutique spécialisée dans la décoration d\'intérieur artisanale. Chaque pièce est fabriquée à la main avec des matériaux naturels et durables.',
     'Paris, France',
     4.80, 1, 1),

    (2, 1,
     'Atelier du Fil',
     'Créations textiles uniques : vêtements, accessoires et linge de maison tissés à la main par des artisans locaux certifiés.',
     'Lyon, France',
     4.70, 1, 1);

-- ── Produits de démo ─────────────────────────────────────────
INSERT IGNORE INTO products
    (shop_id, category_id, name, description, price, old_price, stock, rating, is_new, is_active)
VALUES
    -- Boutique 1 : Maison & Sens
    (1, 1, 'Vase en terre cuite',         'Vase fait main en terre cuite naturelle, finition mate. Hauteur 28 cm.',                  38.00,  NULL,  12, 4.8, 1, 1),
    (1, 5, 'Bol céramique artisanal',      'Bol en grès émaillé, disponible en 3 coloris. Passe au lave-vaisselle.',                 24.50,  32.00,  8, 4.6, 0, 1),
    (1, 1, 'Photophore en verre soufflé',  'Photophore artisanal en verre soufflé à la bouche. Teintes irisées uniques.',            54.00,  NULL,   5, 4.9, 0, 1),
    (1, 1, 'Plateau en bois flotté',       'Plateau décoratif en bois flotté, traitement naturel. Pièce unique.',                   67.00,  NULL,   3, 5.0, 1, 1),
    (1, 5, 'Set de 4 tasses en grès',      'Tasses en grès artisanal, oreille ergonomique. Idéal pour le thé et le café.',           48.00,  55.00, 15, 4.7, 0, 1),
    (1, 1, 'Bougie parfumée naturelle',    'Bougie 100% cire de soja, parfum bois de cèdre & vanille. 40h de combustion.',           22.00,  NULL,  20, 4.5, 1, 1),

    -- Boutique 2 : Atelier du Fil
    (2, 2, 'Écharpe tissée à la main',     'Écharpe en laine mérinos, motifs géométriques. 180 × 30 cm.',                           85.00,  NULL,   7, 4.9, 1, 1),
    (2, 3, 'Bracelet macramé',             'Bracelet réglable en fil de coton ciré, fermoir en laiton doré.',                        18.00,  NULL,  30, 4.4, 0, 1),
    (2, 2, 'Sac fourre-tout en lin',       'Sac en lin naturel, poignées en corde tressée. Lavable en machine.',                    45.00,  58.00,  9, 4.7, 0, 1),
    (2, 6, 'Pochette en cuir végétal',     'Pochette A5 en cuir végétal tanné. Fermeture pression. Fabriquée en France.',           72.00,  NULL,   4, 4.8, 1, 1),
    (2, 2, 'Pull en alpaga',               'Pull oversize en alpaga péruvien, col rond. Disponible en M/L/XL.',                    129.00, 150.00,  6, 4.6, 0, 1),
    (2, 3, 'Collier ceramique & fil',      'Collier pendentif en céramique peint à la main, fil de soie. Pièce unique.',            35.00,  NULL,   2, 5.0, 1, 1);


-- ════════════════════════════════════════════════════════════
-- RÉSUMÉ DES RELATIONS
-- ════════════════════════════════════════════════════════════
--
--   users (existant)
--     └─< shops         (un vendeur peut avoir plusieurs boutiques)
--           └─< products (une boutique peut avoir plusieurs produits)
--
--   categories
--     └─< products      (une catégorie peut contenir plusieurs produits)
--
-- ════════════════════════════════════════════════════════════

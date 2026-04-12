<?php
/**
 * shops-data.php — Source de données centrale des boutiques partenaires
 * ═══════════════════════════════════════════════════════════════════════
 *
 * CE FICHIER EST L'UNIQUE ENDROIT À MODIFIER pour ajouter, modifier
 * ou supprimer une boutique partenaire. Toute modification ici se
 * répercute automatiquement sur :
 *   → Home.php      (section "Boutiques certifiées")
 *   → Shop-detail.php (page détail de la boutique)
 *
 * COMMENT AJOUTER UN NOUVEAU PARTENAIRE :
 * ─────────────────────────────────────────
 * 1. Copier un bloc existant dans $SHOPS_DATA
 * 2. Incrémenter l'ID (doit être unique)
 * 3. Remplir les champs (voir la description de chaque clé ci-dessous)
 * 4. Ajouter les produits dans $PRODUCTS_DATA avec le bon shop_id
 * C'est tout — les deux pages se mettent à jour automatiquement.
 *
 * STRUCTURE D'UNE BOUTIQUE :
 * ─────────────────────────────────────────
 *  id          (int)     Identifiant unique. Ne pas réutiliser un ancien ID.
 *  name        (string)  Nom affiché de la boutique
 *  vendor      (string)  Nom complet du vendeur propriétaire
 *  vendor_bio  (string)  Courte biographie du vendeur (2-3 phrases)
 *  vendor_since(string)  Année d'adhésion (ex: "2023")
 *  description (string)  Description de la boutique (1-2 phrases)
 *  location    (string)  Ville, Pays
 *  rating      (float)   Note de 0 à 5 (ex: 4.8)
 *  is_open     (bool)    true = ouvert | false = fermé temporairement
 *  image       (string)  URL de l'image carte (Home.php) — idéalement 600×400px
 *  banner      (string)  URL de la bannière hero (Shop-detail.php) — idéalement 1400×500px
 *  avatar      (string)  URL du logo/avatar de la boutique — idéalement 200×200px
 *  vendor_avatar(string) URL de la photo du vendeur — idéalement 150×150px
 *  category    (string)  Catégorie principale (affichée sur la carte Home)
 *
 * STRUCTURE D'UN PRODUIT :
 * ─────────────────────────────────────────
 *  id          (int)     Identifiant unique du produit
 *  shop_id     (int)     ID de la boutique à laquelle appartient ce produit
 *  name        (string)  Nom du produit
 *  description (string)  Description détaillée (3-5 phrases)
 *  price       (float)   Prix en FCFA
 *  old_price   (float|null) Prix barré avant promo — null si pas de promo
 *  image       (string)  URL de l'image produit — idéalement 400×400px
 *  stock       (int)     Quantité disponible (0 = rupture)
 *  rating      (float)   Note de 0 à 5
 *  is_new      (bool)    true = badge "Nouveau" affiché
 *  category    (string)  Catégorie du produit (pour les filtres)
 *  cat_slug    (string)  Slug URL-safe de la catégorie (ex: "ceramique")
 *
 * ═══════════════════════════════════════════════════════════════════════
 */

/* ──────────────────────────────────────────────────────────────────────
   BOUTIQUES PARTENAIRES
   Ajouter une entrée ici = la boutique apparaît partout sur le site.
────────────────────────────────────────────────────────────────────── */
$SHOPS_DATA = [

    /* ── Boutique 1 ─────────────────────────────────────────────────── */
    [
        'id'            => 1,
        'name'          => 'Maison & Sens',
        'vendor'        => 'Aurelie Makosso',
        'vendor_bio'    => 'Créatrice passionnée basée à Pointe-Noire, Aurelie façonne chaque pièce à la main depuis 2018. Sa démarche allie esthétique locale et matériaux naturels durables.',
        'vendor_since'  => '2022',
        'description'   => 'Décoration d\'intérieur et accessoires faits main, inspirés du patrimoine congolais.',
        'location'      => 'Pointe-Noire, Congo',
        'rating'        => 4.8,
        'is_open'       => true,
        'image'         => '../Img/HOME.jpeg',
        'banner'        => 'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?q=80&w=1400',
        'avatar'        => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?q=80&w=200',
        'vendor_avatar' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?q=80&w=150',
        'category'      => 'Décoration',
    ],

    /* ── Boutique 2 ─────────────────────────────────────────────────── */
    [
        'id'            => 2,
        'name'          => 'Atelier du Fil',
        'vendor'        => 'Jean-Pierre Loemba',
        'vendor_bio'    => 'Maître tisserand avec plus de 15 ans d\'expérience, Jean-Pierre perpétue les techniques de tissage traditionnel dans un atelier indépendant à Brazzaville.',
        'vendor_since'  => '2021',
        'description'   => 'Sac à main en tissu local, design moderne et fonctionnel.',
        'location'      => 'Brazzaville, Congo',
        'rating'        => 4.7,
        'is_open'       => true,
        'image'         => '../Img/Bag_home.jpeg',
        'banner'        => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?q=80&w=1400',
        'avatar'        => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=200',
        'vendor_avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=150',
        'category'      => 'Vêtements & Textiles',
    ],

    /* ── Boutique 3 ─────────────────────────────────────────────────── */
    [
        'id'            => 3,
        'name'          => 'Les Épices Dorées',
        'vendor'        => 'Marie Batsimba',
        'vendor_bio'    => 'Fille d\'épicier, Marie a grandi entre les saveurs du monde. Elle sélectionne personnellement chaque épice et condiment qu\'elle propose, garantissant une traçabilité totale.',
        'vendor_since'  => '2023',
        'description'   => 'Épices rares, condiments et saveurs authentiques du monde entier.',
        'location'      => 'Pointe-Noire, Congo',
        'rating'        => 4.9,
        'is_open'       => true,
        'image'         => 'https://images.unsplash.com/photo-1489987707025-afc232f7ea0f?q=80&w=600',
        'banner'        => 'https://images.unsplash.com/photo-1596040033229-a9821ebd058d?q=80&w=1400',
        'avatar'        => 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?q=80&w=200',
        'vendor_avatar' => 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?q=80&w=150',
        'category'      => 'Épicerie Fine',
    ],

    /* ── Boutique 4 ─────────────────────────────────────────────────── */
    [
        'id'            => 4,
        'name'          => 'Lumière Céramique',
        'vendor'        => 'Christophe Nganga',
        'vendor_bio'    => 'Céramiste autodidacte, Christophe a transformé sa passion en métier. Chaque pièce sort de son atelier de Dolisie et porte les traces uniques de son geste artisanal.',
        'vendor_since'  => '2022',
        'description'   => 'Céramiques uniques façonnées à la main en atelier indépendant à Dolisie.',
        'location'      => 'Dolisie, Congo',
        'rating'        => 4.6,
        'is_open'       => false,
        'image'         => 'https://images.unsplash.com/photo-1528360983277-13d401cdc186?q=80&w=600',
        'banner'        => 'https://images.unsplash.com/photo-1565193566173-7a0ee3dbe261?q=80&w=1400',
        'avatar'        => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?q=80&w=200',
        'vendor_avatar' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?q=80&w=150',
        'category'      => 'Céramique',
    ],

    /* ── Boutique 5 ─────────────────────────────────────────────────── */
    [
        'id'            => 5,
        'name'          => 'Couture Libre',
        'vendor'        => 'Sandra Mouyabi',
        'vendor_bio'    => 'Styliste formée à Paris et revenue au Congo pour valoriser les tissus locaux, Sandra crée des pièces éthiques qui allient mode contemporaine et identité africaine.',
        'vendor_since'  => '2021',
        'description'   => 'Prêt-à-porter éthique et couture sur-mesure avec des tissus locaux certifiés.',
        'location'      => 'Pointe-Noire, Congo',
        'rating'        => 4.7,
        'is_open'       => true,
        'image'         => 'https://images.unsplash.com/photo-1509631179647-0177331693ae?q=80&w=600',
        'banner'        => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?q=80&w=1400',
        'avatar'        => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?q=80&w=200',
        'vendor_avatar' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?q=80&w=150',
        'category'      => 'Mode & Couture',
    ],

    /* ── Boutique 6 ─────────────────────────────────────────────────── */
    [
        'id'            => 6,
        'name'          => 'Sole Artisan',
        'vendor'        => 'Paul Ibata',
        'vendor_bio'    => 'Cordonnier de troisième génération, Paul perpétue un savoir-faire familial en fabriquant des chaussures entièrement à la main avec du cuir naturel sourcé localement.',
        'vendor_since'  => '2023',
        'description'   => 'Chaussures artisanales en cuir naturel, fabriquées à la main selon des techniques traditionnelles.',
        'location'      => 'Brazzaville, Congo',
        'rating'        => 4.8,
        'is_open'       => true,
        'image'         => '../Img/Tissu.jpeg',
        'banner'        => 'https://images.unsplash.com/photo-1560769629-975ec94e6a86?q=80&w=1400',
        'avatar'        => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?q=80&w=200',
        'vendor_avatar' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?q=80&w=150',
        'category'      => 'Maroquinerie',
    ],

    /* ── Boutique 7 ─────────────────────────────────────────────────── */
    [
        'id'            => 7,
        'name'          => 'Maison des Souliers & Vêtements',
        'vendor'        => 'MJ',
        'vendor_bio'    => 'Propriétaire de la boutique, MJ est passionné par les chaussures artisanales.',
        'vendor_since'  => '2023',
        'description'   => 'Vente de chaussures de qualité et de vêtements de qualité.',
        'location'      => 'Brazzaville, Congo',
        'rating'        => 4.8,
        'is_open'       => true,
        'image'         => '../Img/Paies.jpeg',
        'banner'        => 'https://images.unsplash.com/photo-1560769629-975ec94e6a86?q=80&w=1400',
        'avatar'        => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?q=80&w=200',
        'vendor_avatar' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?q=80&w=150',
        'category'      => 'Maroquinerie',
    ],

    /* ════════════════════════════════════════════════════════════════
       ✦  ZONE D'AJOUT — Copier-coller le bloc ci-dessous pour
          ajouter un nouveau partenaire. Incrémenter l'ID.
    ════════════════════════════════════════════════════════════════
    [
        'id'            => 7,
        'name'          => 'Nom de la boutique',
        'vendor'        => 'Prénom Nom du vendeur',
        'vendor_bio'    => 'Biographie courte du vendeur (2-3 phrases).',
        'vendor_since'  => '2024',
        'description'   => 'Description courte de la boutique.',
        'location'      => 'Ville, Pays',
        'rating'        => 4.5,
        'is_open'       => true,
        'image'         => 'URL_IMAGE_CARTE',
        'banner'        => 'URL_IMAGE_BANNIERE',
        'avatar'        => 'URL_AVATAR_BOUTIQUE',
        'vendor_avatar' => 'URL_PHOTO_VENDEUR',
        'category'      => 'Catégorie principale',
    ],
    ═══════════════════════════════════════════════════════════════ */
];


/* ──────────────────────────────────────────────────────────────────────
   PRODUITS PAR BOUTIQUE
   shop_id doit correspondre à un id existant dans $SHOPS_DATA.
────────────────────────────────────────────────────────────────────── */
$PRODUCTS_DATA = [

    /* ── Produits Boutique 1 : Maison & Sens ────────────────────────── */
    ['id'=>1,  'shop_id'=>1, 'name'=>'Fauteuil et table',        'description'=>'Fauteuil et table en bois massif, design moderne et confortable.',  'price'=>30000,  'old_price'=>null,  'image'=>'../Img/Deco 4.jpeg', 'stock'=>12, 'rating'=>4.8, 'is_new'=>true,  'category'=>'Décoration',  'cat_slug'=>'decoration'],
    ['id'=>2,  'shop_id'=>1, 'name'=>'Chaise et table',     'description'=>'Chaise et table en bois massif, design moderne et confortable.', 'price'=>25000,  'old_price'=>15500, 'image'=>'../Img/Déco 3.jpeg ', 'stock'=>8,  'rating'=>4.6, 'is_new'=>false, 'category'=>'Décoration',   'cat_slug'=>'decoration'],
    ['id'=>3,  'shop_id'=>1, 'name'=>'Matelas',    'description'=>'Matelas en mousse viscoélastique, design moderne et confortable.', 'price'=>26500,  'old_price'=>null,  'image'=>'../Img/Deco 2.jpeg', 'stock'=>5,  'rating'=>4.9, 'is_new'=>false, 'category'=>'Décoration',  'cat_slug'=>'decoration'],
    ['id'=>4,  'shop_id'=>1, 'name'=>'Réfrigérateur',      'description'=>'Réfrigérateur en acier inoxydable, design moderne et fonctionnel.', 'price'=>32000,  'old_price'=>null,  'image'=>'../Img/Dive 3.jpeg', 'stock'=>3,  'rating'=>5.0, 'is_new'=>true,  'category'=>'Décoration',  'cat_slug'=>'decoration'],
    ['id'=>5,  'shop_id'=>1, 'name'=>'Filtre à eau',  'description'=>'Filtre à eau en plastique recyclé, design moderne et fonctionnel.',    'price'=>23500,  'old_price'=>28000, 'image'=>'../Img/Dive 4.jpeg', 'stock'=>15, 'rating'=>4.7, 'is_new'=>false, 'category'=>'Décoration',   'cat_slug'=>'decoration'],


    /* ── Produits Boutique 2 : Atelier du Fil ───────────────────────── */
    ['id'=>7,  'shop_id'=>2, 'name'=>'Sac Louis',    'description'=>'Sac à main en tissu local, design moderne et fonctionnel.', 'price'=>41500,  'old_price'=>null,  'image'=>'../Img/Sac Louis.jpeg', 'stock'=>7,  'rating'=>4.9, 'is_new'=>true,  'category'=>'Accessoires', 'cat_slug'=>'accessoires'],
    ['id'=>8,  'shop_id'=>2, 'name'=>'Sac fourre-tout en lin',      'description'=>'Grand sac en lin naturel non blanchi, poignées en corde tressée. Compartiment intérieur avec zip. Dimensions : 40×38 cm. Lavable en machine à 30°C. Fabriqué localement.',                           'price'=>22000,  'old_price'=>28500, 'image'=>'../Img/Sac fourre-tout en lin.jpeg', 'stock'=>9,  'rating'=>4.7, 'is_new'=>false, 'category'=>'Maroquinerie','cat_slug'=>'maroquinerie'],
    ['id'=>9,  'shop_id'=>2, 'name'=>'Pull en alpaga naturel',      'description'=>'Pull oversize en alpaga péruvien 100% naturel, col rond légèrement tombant. Tissu ultra-doux, antiallergique. Disponible en S/M/L/XL. Coloris naturel non teint. Certification GOTS.',             'price'=>63000,  'old_price'=>75000, 'image'=>'../Img/Pull.jpeg', 'stock'=>6,  'rating'=>4.6, 'is_new'=>false, 'category'=>'Vêtements',   'cat_slug'=>'vetements'],
    ['id'=>10, 'shop_id'=>2, 'name'=>'Nappe tissée traditionnelle', 'description'=>'Nappe en coton tissé au métier à bras, motifs symboliques du Royaume Kongo. Dimensions : 140×200 cm. Coloris naturels résistants au lavage. Idéale pour les grandes occasions.',                    'price'=>35000,  'old_price'=>null,  'image'=>'../Img/Tissu.jpeg', 'stock'=>4,  'rating'=>4.8, 'is_new'=>true,  'category'=>'Maison',      'cat_slug'=>'maison'],

    /* ── Produits Boutique 3 : Les Épices Dorées ────────────────────── */
    ['id'=>11, 'shop_id'=>3, 'name'=>'Mélange 7 épices du Congo',   'description'=>'Blend exclusif de 7 épices sélectionnées en RDC et au Congo-Brazzaville. Notes fumées, florales et légèrement piquantes. Bocal en verre hermétique 100g. Idéal pour les viandes et poissons.',    'price'=>8500,   'old_price'=>null,  'image'=>'https://images.unsplash.com/photo-1596040033229-a9821ebd058d?q=80&w=400', 'stock'=>30, 'rating'=>5.0, 'is_new'=>true,  'category'=>'Épices',      'cat_slug'=>'epices'],
    ['id'=>12, 'shop_id'=>3, 'name'=>'Piment rouge séché artisanal','description'=>'Piment rouge de Cayenne séché au soleil naturellement, sans additifs. Intensité forte (7/10). Sachet kraft 50g refermable. Récolte manuelle par des producteurs locaux partenaires.',             'price'=>4200,   'old_price'=>5000,  'image'=>'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?q=80&w=400', 'stock'=>50, 'rating'=>4.8, 'is_new'=>false, 'category'=>'Épices',      'cat_slug'=>'epices'],
    ['id'=>13, 'shop_id'=>3, 'name'=>'Huile de palme rouge bio',    'description'=>'Huile de palme rouge première pression à froid, issue d\'une palmeraie certifiée bio. Non raffinée, riche en bêta-carotène et vitamine E. Bidon verre 50cl. Saveur authentique.',                 'price'=>14500,  'old_price'=>null,  'image'=>'https://images.unsplash.com/photo-1474979266404-7eaacbcd87c5?q=80&w=400', 'stock'=>18, 'rating'=>4.9, 'is_new'=>false, 'category'=>'Condiments',  'cat_slug'=>'condiments'],
    ['id'=>14, 'shop_id'=>3, 'name'=>'Coffret dégustation 6 épices','description'=>'Coffret découverte idéal pour offrir : 6 épices phares dans de petits bocaux en verre étiquetés. Inclus : livret de recettes exclusif. Emballage cadeau inclus. Poids total 250g.',              'price'=>32000,  'old_price'=>null,  'image'=>'https://images.unsplash.com/photo-1505275350441-83dcda8eeef1?q=80&w=400', 'stock'=>12, 'rating'=>4.9, 'is_new'=>true,  'category'=>'Coffrets',    'cat_slug'=>'coffrets'],

    /* ── Produits Boutique 4 : Lumière Céramique ────────────────────── */
    ['id'=>15, 'shop_id'=>4, 'name'=>'Lampe céramique sculptée',    'description'=>'Lampe de table en céramique sculptée à la main. Motifs géométriques découpés laissant passer une lumière douce et tamisée. Douille E27 fournie. Hauteur 35 cm. Câble tissu natté 2m.',             'price'=>58000,  'old_price'=>null,  'image'=>'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?q=80&w=400', 'stock'=>2,  'rating'=>5.0, 'is_new'=>true,  'category'=>'Luminaires',  'cat_slug'=>'luminaires'],
    ['id'=>16, 'shop_id'=>4, 'name'=>'Assiette creuse décorée',     'description'=>'Assiette creuse en grès, décorée à l\'engobe avec des motifs floraux peints à la main. Diamètre 22 cm. Vernis alimentaire sans plomb. Chaque pièce est signée par l\'artiste.',                  'price'=>16500,  'old_price'=>19000, 'image'=>'https://images.unsplash.com/photo-1610701596007-11502861dcfa?q=80&w=400', 'stock'=>0,  'rating'=>4.7, 'is_new'=>false, 'category'=>'Vaisselle',   'cat_slug'=>'vaisselle'],
    ['id'=>17, 'shop_id'=>4, 'name'=>'Vase soliflore en faïence',   'description'=>'Vase soliflore élancé en faïence blanche émaillée, col étroit. Hauteur 25 cm. Émaillage turquoise à l\'intérieur. Conçu pour une seule fleur — met en valeur chaque tige.',                       'price'=>13500,  'old_price'=>null,  'image'=>'https://images.unsplash.com/photo-1565193566173-7a0ee3dbe261?q=80&w=400', 'stock'=>7,  'rating'=>4.5, 'is_new'=>false, 'category'=>'Décoration',  'cat_slug'=>'decoration'],

    /* ── Produits Boutique 5 : Couture Libre ────────────────────────── */
    ['id'=>18, 'shop_id'=>5, 'name'=>'Robe wax imprimé moderne',    'description'=>'Robe mi-longue en tissu wax imprimé, coupe trapèze avec manches kimono courtes. Fermeture invisible dos. Doublure légère. Tailles XS à XXL disponibles. Lavage à la main recommandé.',            'price'=>45000,  'old_price'=>null,  'image'=>'https://images.unsplash.com/photo-1509631179647-0177331693ae?q=80&w=400', 'stock'=>11, 'rating'=>4.8, 'is_new'=>true,  'category'=>'Robes',       'cat_slug'=>'robes'],
    ['id'=>19, 'shop_id'=>5, 'name'=>'Veste tailleur kente',        'description'=>'Veste tailleur en tissu kente tissé à la main. Col cranté, deux poches passepoilées. Coupe ajustée. Doublure satin. Disponible en S/M/L. Confection sur-mesure possible sur commande.',          'price'=>82000,  'old_price'=>95000, 'image'=>'https://images.unsplash.com/photo-1594938298603-c8148c4b4389?q=80&w=400', 'stock'=>5,  'rating'=>4.9, 'is_new'=>false, 'category'=>'Vestes',      'cat_slug'=>'vestes'],
    ['id'=>20, 'shop_id'=>5, 'name'=>'Ensemble boubou contemporain','description'=>'Boubou modernisé en coton voile léger, broderies sur encolure et poignets. Pantalon palazzo assorti. L\'ensemble est amovible. Coloris disponibles : blanc, beige, bleu roi et bordeaux.',      'price'=>68000,  'old_price'=>null,  'image'=>'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?q=80&w=400', 'stock'=>8,  'rating'=>4.7, 'is_new'=>true,  'category'=>'Ensembles',   'cat_slug'=>'ensembles'],

    /* ── Produits Boutique 6 : Sole Artisan ─────────────────────────── */
    ['id'=>21, 'shop_id'=>6, 'name'=>'Derby cuir naturel homme',    'description'=>'Derby classique en cuir de veau naturel tanné végétal, semelle cuir cousue Goodyear. Tige en cuir pleine fleur 2mm. Couleur naturelle évoluant avec le temps. Pointures 40 à 46.',               'price'=>95000,  'old_price'=>null,  'image'=>'https://images.unsplash.com/photo-1542291026-7eec264c27ff?q=80&w=400', 'stock'=>6,  'rating'=>4.9, 'is_new'=>false, 'category'=>'Homme',       'cat_slug'=>'homme'],
    ['id'=>22, 'shop_id'=>6, 'name'=>'Mocassin cuir femme tressé',  'description'=>'Mocassin élégant en cuir de chèvre souple, tressage artisanal sur le dessus. Semelle caoutchouc antidérapante. Doublure cuir intérieure. Pointures 36 à 42. Coloris : camel, noir, cognac.',     'price'=>78000,  'old_price'=>88000, 'image'=>'https://images.unsplash.com/photo-1543163521-1bf539c55dd2?q=80&w=400', 'stock'=>9,  'rating'=>4.8, 'is_new'=>true,  'category'=>'Femme',       'cat_slug'=>'femme'],
    ['id'=>23, 'shop_id'=>6, 'name'=>'Sandales cuir tressé unisexe','description'=>'Sandales plates en lanières de cuir naturel tressées à la main. Semelle cuir épais 4mm. Bride de cheville réglable. Unisexe, pointures 37 à 45. Conçues pour l\'été congolais.',                'price'=>42000,  'old_price'=>null,  'image'=>'https://images.unsplash.com/photo-1603487742131-4160ec999306?q=80&w=400', 'stock'=>14, 'rating'=>4.7, 'is_new'=>false, 'category'=>'Sandales',    'cat_slug'=>'sandales'],
    ['id'=>24, 'shop_id'=>6, 'name'=>'Ceinture cuir gravée main',   'description'=>'Ceinture en cuir pleine fleur 3mm d\'épaisseur, gravures décoratives réalisées à la main. Boucle laiton massif. Largeur 3,5 cm. Longueurs sur commande. Coloris naturel, marron ou noir.',       'price'=>28500,  'old_price'=>32000, 'image'=>'https://images.unsplash.com/photo-1585386959984-a4155224a1ad?q=80&w=400', 'stock'=>16, 'rating'=>4.6, 'is_new'=>false, 'category'=>'Accessoires', 'cat_slug'=>'accessoires'],

    /* ── Produits Boutique 7 : Maiso des souliers ─────────────────────────── */
    ['id'=>25, 'shop_id'=>7, 'name'=>'Casquette',    'description'=>'Casquette en tissu respirant, idéale pour les journées en plein air. Design simple et élégant.',               'price'=>9000,  'old_price'=>null,  'image'=>'../Img/Casquette.jpeg', 'stock'=>15,  'rating'=>4.9, 'is_new'=>false, 'category'=>'Mixte',       'cat_slug'=>'homme'],
    ['id'=>26, 'shop_id'=>7, 'name'=>'Jordan',  'description'=>'Chaussures de sport en cuir respirant, idéales pour les entraînements et les sorties. Pointures 36 à 42. Coloris : camel, noir, cognac.',     'price'=>15000,  'old_price'=>25000, 'image'=>'../Img/chemtov.jpeg', 'stock'=>9,  'rating'=>4.8, 'is_new'=>true,  'category'=>'Femme',       'cat_slug'=>'femme'],
    ['id'=>27, 'shop_id'=>7, 'name'=>'Survêtement','description'=>'Tenue de sport en tissu respirant, idéale pour les entraînements et les sorties. . Coloris : camel, noir, cognac.',  'price'=>12000,  'old_price'=>null,  'image'=>'../Img/Pull.jpeg', 'stock'=>14, 'rating'=>4.7, 'is_new'=>false, 'category'=>'Sandales',    'cat_slug'=>'sandales'],
    ['id'=>28, 'shop_id'=>7, 'name'=>'T-shirt',   'description'=>'T-shirt en coton respirant, idéal pour les journées en plein air. Design simple et élégant.',       'price'=>7500,  'old_price'=>null, 'image'=>'../Img/Tish 1.jpeg', 'stock'=>16, 'rating'=>4.6, 'is_new'=>false, 'category'=>'Vêtements', 'cat_slug'=>'vetements'],
    ['id'=>29, 'shop_id'=>7, 'name'=>'Chaussures',   'description'=>'Chaussures, idéales pour les sorties. Pointures 36 à 42.',       'price'=>28500,  'old_price'=>32000, 'image'=>'../Img/Paies 2.jpeg', 'stock'=>16, 'rating'=>4.6, 'is_new'=>false, 'category'=>'Accessoires', 'cat_slug'=>'accessoires'],    
    ['id'=>30, 'shop_id'=>7, 'name'=>'T-shirt',   'description'=>'T-shirt en coton respirant, idéal pour les journées en plein air. Design simple et élégant.',       'price'=>7500,  'old_price'=>null, 'image'=>'../Img/Tish 2.jpeg', 'stock'=>16, 'rating'=>4.6, 'is_new'=>false, 'category'=>'Vêtements', 'cat_slug'=>'vetements'],
    ['id'=>31, 'shop_id'=>7, 'name'=>'T-shirt',   'description'=>'T-shirt en coton respirant, idéal pour les journées en plein air. Design simple et élégant.',       'price'=>7500,  'old_price'=>null, 'image'=>'../Img/Tish 3.jpeg', 'stock'=>16, 'rating'=>4.6, 'is_new'=>false, 'category'=>'Vêtements', 'cat_slug'=>'vetements'],

];


/* ──────────────────────────────────────────────────────────────────────
   FONCTIONS UTILITAIRES (utilisées par Home.php et Shop-detail.php)
────────────────────────────────────────────────────────────────────── */

/**
 * Retourne la boutique correspondant à un ID.
 * @param  int        $id
 * @return array|null  Tableau boutique ou null si introuvable
 */
function getShopById(int $id): ?array {
    global $SHOPS_DATA;
    foreach ($SHOPS_DATA as $shop) {
        if ($shop['id'] === $id) return $shop;
    }
    return null;
}

/**
 * Retourne tous les produits d'une boutique donnée.
 * @param  int   $shopId
 * @return array Tableau (vide si aucun produit)
 */
function getProductsByShop(int $shopId): array {
    global $PRODUCTS_DATA;
    return array_values(array_filter(
        $PRODUCTS_DATA,
        fn($p) => $p['shop_id'] === $shopId
    ));
}

/**
 * Extrait les catégories uniques des produits d'une boutique,
 * avec le nombre de produits par catégorie.
 * @param  array $products  Résultat de getProductsByShop()
 * @return array [ ['name'=>..., 'slug'=>..., 'count'=>...], ... ]
 */
function getCategoriesFromProducts(array $products): array {
    $map = [];
    foreach ($products as $p) {
        $slug = $p['cat_slug'];
        if (!isset($map[$slug])) {
            $map[$slug] = ['name' => $p['category'], 'slug' => $slug, 'count' => 0];
        }
        $map[$slug]['count']++;
    }
    return array_values($map);
}

/**
 * Formate un prix en FCFA.
 * @param  float  $price
 * @return string  Ex: "18 500 FCFA"
 */
function formatPrice(float $price): string {
    return number_format($price, 0, ',', ' ') . ' FCFA';
}

/**
 * Retourne la classe CSS et le libellé de stock selon la quantité.
 * @param  int   $stock
 * @return array ['class'=>..., 'label'=>...]
 */
function getStockInfo(int $stock): array {
    if ($stock <= 0) return ['class' => 'overlay__stock--out', 'label' => 'Rupture de stock'];
    if ($stock <= 5) return ['class' => 'overlay__stock--low', 'label' => 'Plus que ' . $stock . ' en stock'];
    return ['class' => 'overlay__stock--ok', 'label' => 'En stock'];
}

/**
 * Échappe une valeur pour l'affichage HTML sécurisé.
 * @param  mixed $val
 * @return string
 */
function e($val): string {
    return htmlspecialchars((string)($val ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

<?php
/* Home.php — Page d'accueil de l'interface client
 Sections :
 1. Hero
 2. Stats
 3. À propos
 4. Guide d'utilisation (4 étapes)
 5. Boutiques partenaires (dynamique — source : shops-data.php)
  Pour ajouter une boutique → modifier uniquement ../Data/shops-data.php */
session_start();

/* Chargement de la source de données centrale */
require_once './Shops-data.php';

/* Inclusion du Header (ouvre <html>, <head>, <body>, affiche navbar) */
include '../Includes/Header.php';
?>

<!-- CSS spécifiques à Home -->
<link rel="stylesheet" href="../assets/Css/Home_Css/About.css">
<link rel="stylesheet" href="../assets/Css/Home_Css/Guide.css">
<link rel="stylesheet" href="../assets/Css/Home_Css/Hero.css">
<link rel="stylesheet" href="../assets/Css/Home_Css/body.css">
<link rel="stylesheet" href="../assets/Css/Home_Css/global.css">
<link rel="stylesheet" href="../assets/Css/Shop_Css/Shop.css">
<link rel="stylesheet" href="../assets/Css/fix-overflow.css">
<!-- CSS responsive dédié à Home -->
<link rel="stylesheet" href="../assets/Css/responsive-home.css">


<!-- 1. HERO -->
<header class="hero">
    <img src="../Img/Dynamic 'E' Logo for E-tool Project.png"
         class="hero__bg" alt="Bannière Marché">
    <div class="hero__overlay"></div>
    <div class="hero__content">
        <div class="reveal">
            <span class="hero__tag">The place for all activities</span>
            <h1 class="hero__title">
                Discover <span>Unique</span><br>Goods from Local Sellers
            </h1>
            <p class="hero__subtitle">
                Trouvez des trésors artisanaux et soutenez les créateurs locaux
                à travers notre plateforme sécurisée.
            </p>
            <div class="hero__cta">
                <a href="#boutiques" class="btn-primary">Shop Now →</a>
                <a href="#apropos"   class="btn-outline">En savoir plus</a>
            </div>
        </div>
    </div>
</header>

<body>
<!-- 2. STATS -->
<section class="stats">
    <div class="stats__inner reveal-group">
        <div class="reveal">
            <span class="stat__number"><?php echo count($SHOPS_DATA); ?>+</span>
            <span class="stat__label">Boutiques partenaires</span>
        </div>
        <div class="reveal">
            <span class="stat__number">3.4K+</span>
            <span class="stat__label">Vendeurs actifs</span>
        </div>
        <div class="reveal">
            <span class="stat__number">98%</span>
            <span class="stat__label">Clients satisfaits</span>
        </div>
        <div class="reveal">
            <span class="stat__number"><?php echo count($PRODUCTS_DATA); ?>+</span>
            <span class="stat__label">Produits listés</span>
        </div>
    </div>
</section>


<!-- 3. À PROPOS -->
<section class="about-bg" id="apropos">
    <div class="section">
        <div class="about__grid">

            <!-- Visuel gauche -->
            <div class="about__visual reveal">
                <img src="../Img/Statio.jpeg"
                     alt="À propos de la marketplace">
                <div class="about__badge">✦ Sécurisé</div>
            </div>

            <!-- Texte droit -->
            <div class="reveal">
                <span class="section__eyebrow">À propos de nous</span>
                <h2 class="section__title">
                    Une marketplace pensée pour les <span>artisans locaux</span>
                </h2>
                <div class="section-divider"></div>
                <p class="section__desc">
                    Fondée sur la conviction que les talents locaux méritent une vitrine mondiale,
                    notre plateforme connecte acheteurs passionnés et vendeurs créatifs dans un
                    environnement de confiance. Chaque boutique est vérifiée, chaque transaction sécurisée.
                </p>
                <p class="section__desc" style="margin-top:1rem;">
                    Nous croyons au commerce humain : derrière chaque produit se cache une histoire,
                    un savoir-faire, une passion. Notre mission est de rendre ces rencontres possibles.
                </p>

                <!-- 4 cartes de fonctionnalités -->
                <div class="about__features reveal-group" style="margin-top:2rem;">

                    <div class="feature-card reveal">
                        <div class="feature-card__icon">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div class="feature-card__title">Transactions Sécurisées</div>
                        <div class="feature-card__text">Paiements chiffrés et protection acheteur intégrée.</div>
                    </div>

                    <div class="feature-card reveal">
                        <div class="feature-card__icon">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div class="feature-card__title">Vendeurs Vérifiés</div>
                        <div class="feature-card__text">Chaque vendeur passe par notre processus de validation.</div>
                    </div>

                    <div class="feature-card reveal">
                        <div class="feature-card__icon">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                        </div>
                        <div class="feature-card__title">Livraison Rapide</div>
                        <div class="feature-card__text">Suivi en temps réel, livraison sous 48–72h.</div>
                    </div>

                    <div class="feature-card reveal">
                        <div class="feature-card__icon">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <div class="feature-card__title">Support 24/7</div>
                        <div class="feature-card__text">Notre équipe répond à toutes vos questions à toute heure.</div>
                    </div>

                </div><!-- /.about__features -->
            </div><!-- /col texte -->

        </div><!-- /.about__grid -->
    </div>
</section>


<!-- 4. GUIDE D'UTILISATION -->
<section class="guide-bg" id="guide">
    <div class="section">
        <div class="reveal" style="text-align:center; max-width:560px; margin:0 auto;">
            <span class="section__eyebrow">Guide d'utilisation</span>
            <h2 class="section__title">Commencer en <span>4 étapes simples</span></h2>
            <p class="section__desc" style="margin:0 auto;">
                Que vous soyez acheteur ou vendeur, notre processus est conçu pour être intuitif et rapide.
            </p>
        </div>

        <div class="steps reveal-group">

            <div class="step-card reveal">
                <span class="step-number">01</span>
                <div class="step-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div class="step-title">Créez votre compte</div>
                <div class="step-text">Inscrivez-vous gratuitement en moins d'une minute. Aucune carte bancaire requise à l'inscription.</div>
            </div>

            <div class="step-card reveal">
                <span class="step-number">02</span>
                <div class="step-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <div class="step-title">Explorez les boutiques</div>
                <div class="step-text">Parcourez nos boutiques certifiées, filtrez par catégorie, note ou localisation.</div>
            </div>

            <div class="step-card reveal">
                <span class="step-number">03</span>
                <div class="step-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div class="step-title">Ajoutez au panier</div>
                <div class="step-text">Sélectionnez vos articles, comparez les offres et constituez votre panier en toute tranquillité.</div>
            </div>

            <div class="step-card reveal">
                <span class="step-number">04</span>
                <div class="step-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div class="step-title">Commandez & recevez</div>
                <div class="step-text">Payez en sécurité et suivez votre colis en temps réel jusqu'à votre porte.</div>
            </div>

        </div><!-- /.steps -->
    </div>
</section>


<!-- 5. BOUTIQUES PARTENAIRES
     ─ Générées dynamiquement depuis $SHOPS_DATA
     ─ Cliquer sur une carte → Shop-detail.php?id=X -->
<section class="shops-bg" id="boutiques">
    <div class="section">

        <!-- En-tête + barre de recherche -->
        <div class="shops-header">
            <div class="reveal">
                <span class="section__eyebrow">Nos partenaires</span>
                <h2 class="section__title">Boutiques <span>certifiées</span></h2>
                <p class="section__desc">
                    Découvrez l'univers de nos
                    <?php echo count($SHOPS_DATA); ?> vendeurs locaux vérifiés.
                </p>
            </div>

            <!-- Champ de recherche en temps réel (filtrage JS côté client) -->
            <div class="search-wrap reveal">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="search"
                       id="shop-search"
                       placeholder="Rechercher une boutique..."
                       autocomplete="off">
            </div>
        </div><!-- /.shops-header -->


        <!-- GRILLE DES BOUTIQUES
             Chaque carte est un lien vers Shop-detail.php?id=<id>
             Les données (nom, description, image, rating, etc.)
             viennent toutes de $SHOPS_DATA dans shops-data.php -->
        <div class="shops-grid reveal-group" id="shops-grid">

            <?php foreach ($SHOPS_DATA as $shop): ?>

            <!--
                data-name / data-desc : utilisés par le JS de recherche
                data-category         : pourra servir à un filtre futur
            -->
            <div class="shop-card-item reveal"
                 data-name="<?php echo e(strtolower($shop['name'])); ?>"
                 data-desc="<?php echo e(strtolower($shop['description'])); ?>"
                 data-category="<?php echo e(strtolower($shop['category'])); ?>">

                <!--
                    LIEN VERS LA PAGE DÉTAIL
                    L'ID est celui de la boutique dans $SHOPS_DATA.
                    Shop-detail.php récupère cet ID via $_GET['id'].
                -->
                <a href="Shop-detail.php?id=<?php echo (int)$shop['id']; ?>"
                   class="shop-card"
                   title="Voir la boutique <?php echo e($shop['name']); ?>">

                    <!-- Zone image -->
                    <div class="shop-card__img-wrap">

                        <!-- Badge statut ouvert / fermé -->
                        <?php if ($shop['is_open']): ?>
                            <span class="shop-badge shop-badge--open">● Ouvert</span>
                        <?php else: ?>
                            <span class="shop-badge shop-badge--closed">● Fermé</span>
                        <?php endif; ?>

                        <img src="<?php echo e($shop['image']); ?>"
                             alt="<?php echo e($shop['name']); ?>"
                             class="shop-card__img"
                             loading="lazy">
                    </div>

                    <!-- Corps de la carte -->
                    <div class="shop-card__body">
                        <div class="shop-card__top">
                            <span class="shop-card__name"><?php echo e($shop['name']); ?></span>
                            <span class="shop-card__rating">
                                <?php echo number_format($shop['rating'], 1); ?> ★
                            </span>
                        </div>
                        <p class="shop-card__desc"><?php echo e($shop['description']); ?></p>

                        <!-- Catégorie + localisation -->
                        <div class="shop-card__meta">
                            <span class="shop-card__category"><?php echo e($shop['category']); ?></span>
                            <div class="shop-card__location">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <?php echo e($shop['location']); ?>
                            </div>
                        </div>

                    </div><!-- /.shop-card__body -->
                </a><!-- /.shop-card -->
            </div><!-- /.shop-card-item -->

            <?php endforeach; ?>

        </div><!-- /#shops-grid -->

        <!-- Message affiché par JS quand aucune boutique ne correspond à la recherche -->
        <div id="shops-empty" style="display:none; text-align:center; padding:3rem 1rem; color:#6B7280;">
            <svg width="2.5rem" height="2.5rem" fill="none" stroke="currentColor" stroke-width="1.5"
                 viewBox="0 0 24 24" style="margin:0 auto 1rem; display:block; color:#D1D5DB;">
                <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <p style="font-size:1rem; font-weight:500;">Aucune boutique ne correspond à votre recherche.</p>
        </div>

    </div><!-- /.section -->
</section>
</body>



<!-- JS scroll reveal + recherche boutiques -->
<script src="../assets/Js/Scoll.js"></script>
<script>
/* ── Recherche en temps réel sur les boutiques ── */
(function () {
    const input  = document.getElementById('shop-search');
    const items  = document.querySelectorAll('.shop-card-item');
    const empty  = document.getElementById('shops-empty');
    if (!input) return;

    input.addEventListener('input', function () {
        const q = this.value.toLowerCase().trim();
        let visible = 0;

        items.forEach(function (item) {
            /* On recherche dans le nom, la description et la catégorie */
            const match = !q
                || item.dataset.name.includes(q)
                || item.dataset.desc.includes(q)
                || item.dataset.category.includes(q);

            item.style.display = match ? '' : 'none';
            if (match) visible++;
        });

        /* Affiche le message "aucun résultat" si besoin */
        if (empty) empty.style.display = (visible === 0 && q) ? 'block' : 'none';
    });
})();
</script>

<?php include '../Includes/Footerr.php'; ?>


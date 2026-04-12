<?php
/* Shop-detail.php — Page détail d'une boutique (sans base de données)
 URL : Shop-detail.php?id=<shop_id>
 Les données (boutique + produits) sont lues depuis :
 ../Data/shops-data.php
 Pour ajouter une boutique ou ses produits → modifier shops-data.php */
session_start();

require_once './Shops-data.php';

/* ── Validation du paramètre ?id= ── */
$shop_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1],
]);

if (!$shop_id) {
    header('Location: ./Home.php');
    exit();
}

/* ── Récupération de la boutique ── */
$shop = getShopById($shop_id);

if (!$shop) {
    header('Location: Home.php');
    exit();
}

/* ── Produits + catégories de la boutique ── */
$products      = getProductsByShop($shop_id);
$categories    = getCategoriesFromProducts($products);
$totalProducts = count($products);

include '../Includes/Header.php';
?>

<link rel="stylesheet" href="../assets/Css/Shop_Css/Shop-detail.css">
<link rel="stylesheet" href="../assets/Css/Shop_Css/responsive-shop-detail.css">
<link rel="stylesheet" href="../assets/Css/fix-overflow.css">
<link rel="stylesheet" href="../assets/Css/Home_Css/global.css">

<!-- Styles inline pour les éléments d'invitation à la connexion -->
<style>
/* Invite connexion dans l'overlay produit */
.overlay__auth-prompt {
    display: flex;
    flex-direction: column;
    gap: .6rem;
}
.overlay__auth-text {
    display: flex;
    align-items: center;
    gap: .4rem;
    font-size: .75rem;
    color: rgba(255,255,255,.7);
    font-style: italic;
    line-height: 1.4;
}
.overlay__auth-btns {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: .5rem;
}
.overlay__btn-register {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: .7rem .5rem;
    border-radius: 8px;
    background: #F97316;
    color: #fff;
    font-size: .78rem;
    font-weight: 700;
    text-decoration: none;
    text-align: center;
    transition: background .2s, transform .2s;
    box-shadow: 0 3px 10px rgba(249,115,22,.4);
}
.overlay__btn-register:hover {
    background: #C2570B;
    transform: translateY(-1px);
}
.overlay__btn-login {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: .7rem .5rem;
    border-radius: 8px;
    background: rgba(255,255,255,.12);
    color: rgba(255,255,255,.85);
    font-size: .78rem;
    font-weight: 600;
    text-decoration: none;
    text-align: center;
    border: 1px solid rgba(255,255,255,.2);
    transition: background .2s;
}
.overlay__btn-login:hover {
    background: rgba(255,255,255,.22);
}

/* Bannière flottante en bas de page pour les non-connectés */
.guest-order-banner {
    position: fixed;
    bottom: 0; left: 0; right: 0;
    background: linear-gradient(135deg, #0F0F0F 0%, #1E1410 100%);
    border-top: 2px solid rgba(249,115,22,.3);
    z-index: 40;
    padding: 1rem 2rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1.5rem;
    flex-wrap: wrap;
    box-shadow: 0 -4px 30px rgba(0,0,0,.3);
    animation: slideUp .4s cubic-bezier(.22,1,.36,1);
}
@keyframes slideUp {
    from { transform: translateY(100%); opacity: 0; }
    to   { transform: translateY(0);    opacity: 1; }
}
.guest-order-banner__text {
    display: flex;
    align-items: center;
    gap: .85rem;
    flex: 1;
    min-width: 200px;
}
.guest-order-banner__icon {
    width: 2.4rem; height: 2.4rem;
    border-radius: 10px;
    background: rgba(249,115,22,.15);
    border: 1px solid rgba(249,115,22,.3);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.guest-order-banner__icon svg { color: #F97316; }
.guest-order-banner__title {
    font-size: .95rem;
    font-weight: 700;
    color: #fff;
    margin-bottom: .1rem;
    font-family: 'Playfair Display', Georgia, serif;
}
.guest-order-banner__sub {
    font-size: .78rem;
    color: rgba(255,255,255,.5);
}
.guest-order-banner__actions {
    display: flex;
    gap: .75rem;
    align-items: center;
    flex-shrink: 0;
}
.guest-banner-btn-reg {
    display: inline-flex; align-items: center; gap: .5rem;
    padding: .75rem 1.5rem;
    border-radius: 10px;
    background: #F97316; color: #fff;
    font-size: .875rem; font-weight: 700;
    text-decoration: none;
    transition: background .2s, transform .2s;
    box-shadow: 0 4px 16px rgba(249,115,22,.4);
}
.guest-banner-btn-reg:hover { background: #C2570B; transform: translateY(-1px); }
.guest-banner-btn-login {
    display: inline-flex; align-items: center;
    padding: .75rem 1.2rem;
    border-radius: 10px;
    background: rgba(255,255,255,.08);
    border: 1px solid rgba(255,255,255,.15);
    color: rgba(255,255,255,.8);
    font-size: .875rem; font-weight: 600;
    text-decoration: none;
    transition: background .2s;
}
.guest-banner-btn-login:hover { background: rgba(255,255,255,.15); }
.guest-banner-close {
    background: none; border: none; cursor: pointer;
    color: rgba(255,255,255,.35); padding: .3rem;
    transition: color .2s; flex-shrink: 0;
}
.guest-banner-close:hover { color: #fff; }

@media (max-width: 600px) {
    .guest-order-banner { padding: .85rem 1rem; }
    .guest-order-banner__text { gap: .6rem; }
    .guest-order-banner__title { font-size: .85rem; }
    .guest-order-banner__sub { display: none; }
    .guest-banner-btn-reg { padding: .65rem 1.1rem; font-size: .82rem; }
    .guest-banner-btn-login { padding: .65rem .9rem; font-size: .82rem; }
}
</style>


<!-- FIL D'ARIANE -->
<nav class="breadcrumb" aria-label="Fil d'ariane">
    <div class="breadcrumb__inner">
        <a href="Home.php" class="breadcrumb__link">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
            Accueil
        </a>
        <span class="breadcrumb__sep">›</span>
        <span class="breadcrumb__current"><?php echo e($shop['name']); ?></span>
    </div>
</nav>


<!-- HERO DE LA BOUTIQUE -->
<section class="shop-hero">
    <img src="<?php echo e($shop['banner']); ?>"
         alt="Bannière de <?php echo e($shop['name']); ?>"
         class="shop-hero__bg" loading="eager">
    <div class="shop-hero__overlay"></div>

    <div class="shop-hero__content">
        <div class="shop-hero__info reveal">
            <img src="<?php echo e($shop['avatar']); ?>"
                 alt="Logo <?php echo e($shop['name']); ?>"
                 class="shop-hero__avatar">

            <div class="shop-hero__meta">
                <h1 class="shop-hero__name"><?php echo e($shop['name']); ?></h1>
                <p class="shop-hero__vendor">
                    par <strong style="color:rgba(255,255,255,.95);"><?php echo e($shop['vendor']); ?></strong>
                    &nbsp;·&nbsp;
                    <?php if ($shop['is_open']): ?>
                        <span class="badge badge-open">● Ouvert</span>
                    <?php else: ?>
                        <span class="badge badge-closed">● Fermé temporairement</span>
                    <?php endif; ?>
                </p>
                <div class="shop-hero__stats">
                    <span class="shop-stat">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/>
                            <path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/>
                        </svg>
                        <?php echo $totalProducts; ?> produit<?php echo $totalProducts > 1 ? 's' : ''; ?>
                    </span>
                    <span class="shop-stat shop-stat--rating">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        <?php echo number_format($shop['rating'], 1); ?> / 5
                    </span>
                    <span class="shop-stat">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <?php echo e($shop['location']); ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="shop-hero__cta reveal" style="transition-delay:.1s;">
            <a href="Home.php#boutiques" class="btn-outline"
               style="color:#fff; border-color:rgba(255,255,255,.4);">
                ← Toutes les boutiques
            </a>
        </div>
    </div>
</section>


<!-- BARRE DE FILTRES (sticky) -->
<nav class="filters-bar" aria-label="Filtres par catégorie">
    <div class="filters-bar__inner">
        <button class="filter-btn active" data-category="all" aria-pressed="true">
            Tous <span class="filter-btn__count"><?php echo $totalProducts; ?></span>
        </button>
        <?php foreach ($categories as $cat): ?>
        <button class="filter-btn" data-category="<?php echo e($cat['slug']); ?>" aria-pressed="false">
            <?php echo e($cat['name']); ?>
            <span class="filter-btn__count"><?php echo $cat['count']; ?></span>
        </button>
        <?php endforeach; ?>

        <div class="filters-bar__right">
            <div class="products-search">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="search" id="product-search"
                       placeholder="Rechercher..." autocomplete="off">
            </div>
            <select id="sort-select" class="sort-select">
                <option value="default">Tri par défaut</option>
                <option value="price-asc">Prix croissant</option>
                <option value="price-desc">Prix décroissant</option>
                <option value="name-asc">Nom A → Z</option>
                <option value="rating">Mieux notés</option>
            </select>
        </div>
    </div>
</nav>


<!-- GRILLE DE PRODUITS -->
<main class="products-section">

    <div class="products-section__header reveal">
        <div>
            <span class="section-eyebrow">Catalogue</span>
            <h2 class="section-title" style="font-size:clamp(1.3rem,2.5vw,1.9rem);margin-bottom:0;">
                Produits de <span><?php echo e($shop['name']); ?></span>
            </h2>
        </div>
        <p class="products-count" id="products-count">
            <strong><?php echo $totalProducts; ?></strong> produit<?php echo $totalProducts > 1 ? 's' : ''; ?>
        </p>
    </div>

    <div class="products-grid reveal-group" id="products-grid"
             data-register-url="<?php echo '../Auth/Register.php?redirect_to=' . urlencode('Shop-detail.php?id=' . $shop_id); ?>">

        <?php if (empty($products)): ?>
        <div class="products-empty">
            <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/>
                <path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/>
            </svg>
            <p>Cette boutique n'a pas encore de produits.</p>
            <a href="Home.php#boutiques" class="btn-primary" style="margin-top:1.2rem;">
                ← Voir d'autres boutiques
            </a>
        </div>
        <?php else: ?>

        <?php foreach ($products as $index => $product):
            $stockInfo    = getStockInfo((int)$product['stock']);
            $isOutOfStock = ((int)$product['stock'] <= 0);
            $hasDiscount  = !empty($product['old_price']) && $product['old_price'] > $product['price'];
            $discountPct  = $hasDiscount ? round((1 - $product['price'] / $product['old_price']) * 100) : 0;
        ?>
        <div class="product-item reveal"
             data-category="<?php echo e($product['cat_slug']); ?>"
             data-name="<?php echo e(strtolower($product['name'])); ?>"
             data-desc="<?php echo e(strtolower(substr($product['description'], 0, 120))); ?>"
             data-price="<?php echo e($product['price']); ?>"
             data-rating="<?php echo e($product['rating']); ?>"
             data-order="<?php echo $index; ?>">

            <div class="product-card" tabindex="0" role="article"
                 aria-label="<?php echo e($product['name']); ?>">

                <div class="product-card__img-wrap">
                    <span class="product-badge-cat"><?php echo e($product['category']); ?></span>
                    <?php if ($hasDiscount): ?>
                        <span class="product-badge-flag product-badge-flag--promo">-<?php echo $discountPct; ?>%</span>
                    <?php elseif ($product['is_new']): ?>
                        <span class="product-badge-flag">Nouveau</span>
                    <?php endif; ?>

                    <img src="<?php echo e($product['image']); ?>"
                         alt="<?php echo e($product['name']); ?>"
                         class="product-card__img" loading="lazy">

                    <!-- OVERLAY : hover desktop / tap mobile -->
                    <div class="product-card__overlay" aria-hidden="true">
                        <p class="overlay__name"><?php echo e($product['name']); ?></p>

                        <div class="overlay__price">
                            <span class="overlay__price-current"><?php echo formatPrice($product['price']); ?></span>
                            <?php if ($hasDiscount): ?>
                            <span class="overlay__price-old"><?php echo formatPrice($product['old_price']); ?></span>
                            <?php endif; ?>
                        </div>

                        <p class="overlay__desc"><?php echo e($product['description']); ?></p>

                        <div class="overlay__stock <?php echo $stockInfo['class']; ?>">
                            <span class="overlay__stock-dot"></span>
                            <?php echo e($stockInfo['label']); ?>
                        </div>

                        <div class="overlay__rating">
                            <span class="overlay__stars">
                                <?php echo str_repeat('★', round($product['rating'])) . str_repeat('☆', 5 - round($product['rating'])); ?>
                            </span>
                            <span class="overlay__rating-val"><?php echo number_format($product['rating'], 1); ?>/5</span>
                        </div>

                        <?php
                        /* ─────────────────────────────────────────────────────
                           BOUTON "AJOUTER AU PANIER"
                           • Utilisateur connecté  → ajout direct au panier
                           • Explorateur non connecté → redirige vers Register
                             (avec ?redirect_to= pour revenir ici après inscription)
                           ───────────────────────────────────────────────────── */
                        $isLoggedIn   = isset($_SESSION['user_id']);
                        $currentUrl   = 'Shop-detail.php?id=' . $shop_id;
                        $registerUrl  = '../Auth/Register.php?redirect_to=' . urlencode($currentUrl);
                        $loginUrl     = '../Auth/Login.php?redirect_to='    . urlencode($currentUrl);
                        ?>
                        <?php if ($isOutOfStock): ?>
                            <!-- Rupture de stock : bouton désactivé -->
                            <button class="overlay__add-btn" disabled>
                                Rupture de stock
                            </button>

                        <?php elseif ($isLoggedIn): ?>
                            <!-- Connecté : ajout normal au panier -->
                            <button class="overlay__add-btn"
                                    data-product-id="<?php echo (int)$product['id']; ?>"
                                    data-product-name="<?php echo e($product['name']); ?>"
                                    data-product-price="<?php echo (float)$product['price']; ?>">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                Ajouter au panier
                            </button>

                        <?php else: ?>
                            <!-- Non connecté : affiche un mini-panneau d'invite -->
                            <div class="overlay__auth-prompt">
                                <p class="overlay__auth-text">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10"/>
                                        <line x1="12" y1="8" x2="12" y2="12"/>
                                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                                    </svg>
                                    Un compte est requis pour commander
                                </p>
                                <div class="overlay__auth-btns">
                                    <a href="<?php echo $registerUrl; ?>" class="overlay__btn-register">
                                        Créer un compte
                                    </a>
                                    <a href="<?php echo $loginUrl; ?>" class="overlay__btn-login">
                                        Se connecter
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div><!-- /overlay -->
                </div><!-- /img-wrap -->

                <div class="product-card__foot">
                    <p class="product-card__foot-name"><?php echo e($product['name']); ?></p>
                    <p class="product-card__foot-price"><?php echo formatPrice($product['price']); ?></p>
                </div>

            </div><!-- /.product-card -->
        </div><!-- /.product-item -->
        <?php endforeach; ?>

        <div id="products-empty" class="products-empty" style="display:none;">
            <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <p>Aucun produit ne correspond à votre recherche.</p>
        </div>

        <?php endif; ?>
    </div><!-- /#products-grid -->
</main>


<!-- ENCART VENDEUR -->
<section class="vendor-section">
    <div class="vendor-card reveal">
        <img src="<?php echo e($shop['vendor_avatar']); ?>"
             alt="Photo de <?php echo e($shop['vendor']); ?>"
             class="vendor-card__avatar">
        <div class="vendor-card__info">
            <h3 class="vendor-card__name"><?php echo e($shop['vendor']); ?></h3>
            <p class="vendor-card__bio"><?php echo e($shop['vendor_bio']); ?></p>
            <span class="vendor-card__since">Partenaire depuis <?php echo e($shop['vendor_since']); ?></span>
        </div>
        <a href="Home.php#boutiques" class="btn-outline" style="flex-shrink:0;">
            ← Toutes les boutiques
        </a>
    </div>
</section>

<script src="../assets/Js/Shop-detail.js"></script>

<?php
/* ── Bannière flottante pour les visiteurs non connectés ──
   Affichée seulement si l'utilisateur n'a pas de session active.
   Elle invite à créer un compte ou se connecter pour commander.   */
if (!isset($_SESSION['user_id'])):
    $currentUrl  = 'Shop-detail.php?id=' . $shop_id;
    $registerUrl = '../Auth/Register.php?redirect_to=' . urlencode($currentUrl);
    $loginUrl    = '../Auth/Login.php?redirect_to='    . urlencode($currentUrl);
?>
<div class="guest-order-banner" id="guest-banner" role="complementary" aria-label="Invitation à créer un compte">
    <div class="guest-order-banner__text">
        <div class="guest-order-banner__icon">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
                <line x1="3" y1="6" x2="21" y2="6"/>
                <path d="M16 10a4 4 0 01-8 0"/>
            </svg>
        </div>
        <div>
            <p class="guest-order-banner__title">Vous explorez en mode visiteur</p>
            <p class="guest-order-banner__sub">Créez un compte gratuit pour passer commande dans toutes nos boutiques</p>
        </div>
    </div>
    <div class="guest-order-banner__actions">
        <a href="<?php echo $registerUrl; ?>" class="guest-banner-btn-reg">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/>
                <line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/>
            </svg>
            Créer un compte
        </a>
        <a href="<?php echo $loginUrl; ?>" class="guest-banner-btn-login">
            Se connecter
        </a>
        <button class="guest-banner-close" id="close-banner" aria-label="Fermer">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
        </button>
    </div>
</div>
<script>
/* Fermeture de la bannière visiteur */
(function() {
    var btn = document.getElementById('close-banner');
    var banner = document.getElementById('guest-banner');
    if (btn && banner) {
        btn.addEventListener('click', function() {
            banner.style.transition = 'transform .3s ease, opacity .3s ease';
            banner.style.transform  = 'translateY(100%)';
            banner.style.opacity    = '0';
            setTimeout(function() { banner.style.display = 'none'; }, 300);
        });
    }
    /* Espace bas de page pour éviter que le contenu soit caché par la bannière */
    document.body.style.paddingBottom = banner ? (banner.offsetHeight + 16) + 'px' : '80px';
})();
</script>
<?php endif; ?>

<?php include '../Includes/Footerr.php'; ?>

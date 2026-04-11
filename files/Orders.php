<?php
/*
 * Orders.php — Mes commandes
 * Affiche :
 *  - Liste de toutes les commandes de l'utilisateur connecté
 *  - Détail d'une commande spécifique (paramètre ?ref=XXX)
 *    avec reçu numérique complet (articles, totaux, livraison,
 *    date commande, date livraison estimée, statut de suivi)
 *  - Bouton "Contacter le vendeur" pour chaque commande
 */
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../Login & Register/Login.php?redirect_to=' . urlencode('./files/Orders.php'));
    exit();
}

require_once './Shops-data.php';

$user = [
    'name'  => $_SESSION['user_name']  ?? 'Client',
    'email' => $_SESSION['user_email'] ?? '',
];

/* ── Commandes simulées (remplacer par DB plus tard) ──
   Si la session ne contient pas de commandes, on génère des données
   de démo pour illustrer l'interface.                            */
if (empty($_SESSION['orders'])) {
    $_SESSION['orders'] = [
        [
            'ref'           => 'CMD-2024-0001',
            'date'          => '08 Janvier 2025',
            'date_iso'      => '2025-01-08',
            'delivery_est'  => '12 Janvier 2025',
            'delivery_iso'  => '2025-01-12',
            'delivered_at'  => '11 Janvier 2025',
            'status'        => 'delivered',
            'shop_id'       => 1,
            'shop_name'     => 'Maison & Sens',
            'vendor_name'   => 'Aurelie Makosso',
            'payment'       => 'Mobile Money',
            'address'       => 'Quartier Mongo-Poukou, Pointe-Noire, Congo',
            'items' => [
                ['id'=>1, 'name'=>'Vase en terre cuite',      'qty'=>1, 'price'=>18500, 'image'=>'https://images.unsplash.com/photo-1565193566173-7a0ee3dbe261?q=80&w=80'],
                ['id'=>5, 'name'=>'Set de 4 tasses en grès',  'qty'=>1, 'price'=>23500, 'image'=>'https://images.unsplash.com/photo-1514228742587-6b1558fcca3d?q=80&w=80'],
            ],
            'subtotal'      => 42000,
            'shipping'      => 2500,
            'total'         => 44500,
            'note'          => '',
            'tracking'      => [
                ['status'=>'confirmed',  'label'=>'Commande confirmée',     'date'=>'08 Jan. 2025 09:14', 'done'=>true],
                ['status'=>'preparing', 'label'=>'Préparation en cours',    'date'=>'09 Jan. 2025 11:30', 'done'=>true],
                ['status'=>'shipped',   'label'=>'Expédiée par le vendeur', 'date'=>'10 Jan. 2025 14:00', 'done'=>true],
                ['status'=>'delivered', 'label'=>'Livrée',                  'date'=>'11 Jan. 2025 16:45', 'done'=>true],
            ],
        ],
        [
            'ref'           => 'CMD-2024-0002',
            'date'          => '22 Mars 2025',
            'date_iso'      => '2025-03-22',
            'delivery_est'  => '26 Mars 2025',
            'delivery_iso'  => '2025-03-26',
            'delivered_at'  => null,
            'status'        => 'shipped',
            'shop_id'       => 3,
            'shop_name'     => 'Les Épices Dorées',
            'vendor_name'   => 'Marie Batsimba',
            'payment'       => 'Carte bancaire',
            'address'       => 'Quartier Mongo-Poukou, Pointe-Noire, Congo',
            'items' => [
                ['id'=>11, 'name'=>'Mélange 7 épices du Congo',    'qty'=>2, 'price'=>8500,  'image'=>'https://images.unsplash.com/photo-1596040033229-a9821ebd058d?q=80&w=80'],
                ['id'=>14, 'name'=>'Coffret dégustation 6 épices', 'qty'=>1, 'price'=>32000, 'image'=>'https://images.unsplash.com/photo-1505275350441-83dcda8eeef1?q=80&w=80'],
            ],
            'subtotal'      => 49000,
            'shipping'      => 2500,
            'total'         => 51500,
            'note'          => 'Livraison après 17h svp.',
            'tracking'      => [
                ['status'=>'confirmed',  'label'=>'Commande confirmée',     'date'=>'22 Mar. 2025 10:05', 'done'=>true],
                ['status'=>'preparing', 'label'=>'Préparation en cours',    'date'=>'23 Mar. 2025 09:00', 'done'=>true],
                ['status'=>'shipped',   'label'=>'Expédiée par le vendeur', 'date'=>'24 Mar. 2025 13:20', 'done'=>true],
                ['status'=>'delivered', 'label'=>'Livrée',                  'date'=>'Prévue le 26 Mar.',  'done'=>false],
            ],
        ],
    ];
}

$orders = $_SESSION['orders'];

/* Mode détail : ?ref=CMD-... */
$ref         = $_GET['ref'] ?? null;
$currentOrder = null;
if ($ref) {
    foreach ($orders as $o) {
        if ($o['ref'] === $ref) { $currentOrder = $o; break; }
    }
}

/* Labels et couleurs de statut */
$statusMeta = [
    'pending'    => ['label'=>'En attente',  'color'=>'#F59E0B', 'bg'=>'rgba(245,158,11,.12)'],
    'processing' => ['label'=>'En cours',    'color'=>'#3B82F6', 'bg'=>'rgba(59,130,246,.12)'],
    'shipped'    => ['label'=>'Expédiée',    'color'=>'#8B5CF6', 'bg'=>'rgba(139,92,246,.12)'],
    'delivered'  => ['label'=>'Livrée',      'color'=>'#10B981', 'bg'=>'rgba(16,185,129,.12)'],
    'cancelled'  => ['label'=>'Annulée',     'color'=>'#EF4444', 'bg'=>'rgba(239,68,68,.12)'],
];

include '../Includes/Header.php';
?>

<link rel="stylesheet" href="../assets/Css/Commandes/orders.css">
<link rel="stylesheet" href="../assets/Css/fix-overflow.css">
<link rel="stylesheet" href="../assets/Css/Home_Css/global.css">

<div class="orders-page">

    <!-- EN-TÊTE PAGE -->
    <div class="orders-header reveal">
        <div class="orders-header__left">
            <?php if ($currentOrder): ?>
            <a href="../files/Orders.php" class="orders-back">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
                Toutes mes commandes
            </a>
            <h1 class="orders-page-title">Commande #<?php echo htmlspecialchars($currentOrder['ref']); ?></h1>
            <?php else: ?>
            <h1 class="orders-page-title">Mes commandes</h1>
            <p class="orders-page-sub"><?php echo count($orders); ?> commande<?php echo count($orders) > 1 ? 's' : ''; ?> au total</p>
            <?php endif; ?>
        </div>
        <a href="./Shop-detail.php" class="btn-outline" style="flex-shrink:0; font-size:.875rem;">
            Continuer mes achats
        </a>
    </div>


    <?php if ($currentOrder): ?>
    <!-- VUE DÉTAIL D'UNE COMMANDE — REÇU NUMÉRIQUE -->
    <div class="receipt">

        <!-- Bouton impression -->
        <div class="receipt__actions reveal">
            <button onclick="window.print()" class="btn-print">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/>
                    <rect x="6" y="14" width="12" height="8"/>
                </svg>
                Imprimer / Télécharger
            </button>
            <?php
            $shop = getShopById($currentOrder['shop_id']);
            if ($shop):
            ?>
            <a href="./Contact.php?vendor=<?php echo urlencode($currentOrder['vendor_name']); ?>&order=<?php echo urlencode($currentOrder['ref']); ?>"
               class="btn-contact-vendor">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>
                </svg>
                Contacter <?php echo htmlspecialchars($currentOrder['vendor_name']); ?>
            </a>
            <?php endif; ?>
        </div>

        <div class="receipt__body reveal">

            <!-- ── En-tête reçu ── -->
            <div class="receipt__head">
                <div class="receipt__logo">
                    <img src="../Img/Dynamic 'E' Logo for E-tool Project.png" alt="E-tool" class="receipt__logo-img">
                    <span class="receipt__logo-text">E<span>-</span>tool</span>
                </div>
                <div class="receipt__head-meta">
                    <div class="receipt__ref">Reçu #<?php echo htmlspecialchars($currentOrder['ref']); ?></div>
                    <?php
                    $sm = $statusMeta[$currentOrder['status']] ?? $statusMeta['pending'];
                    ?>
                    <span class="receipt__status" style="color:<?php echo $sm['color']; ?>; background:<?php echo $sm['bg']; ?>;">
                        <?php echo $sm['label']; ?>
                    </span>
                </div>
            </div>

            <!-- ── Info colonnes ── -->
            <div class="receipt__info-grid">
                <div class="receipt__info-block">
                    <div class="receipt__info-title">Commandé le</div>
                    <div class="receipt__info-val"><?php echo htmlspecialchars($currentOrder['date']); ?></div>
                </div>
                <div class="receipt__info-block">
                    <div class="receipt__info-title">Livraison estimée</div>
                    <div class="receipt__info-val"><?php echo htmlspecialchars($currentOrder['delivery_est']); ?></div>
                    <?php if ($currentOrder['delivered_at']): ?>
                    <div class="receipt__info-note">Livré le <?php echo htmlspecialchars($currentOrder['delivered_at']); ?></div>
                    <?php endif; ?>
                </div>
                <div class="receipt__info-block">
                    <div class="receipt__info-title">Boutique</div>
                    <div class="receipt__info-val"><?php echo htmlspecialchars($currentOrder['shop_name']); ?></div>
                    <div class="receipt__info-note">par <?php echo htmlspecialchars($currentOrder['vendor_name']); ?></div>
                </div>
                <div class="receipt__info-block">
                    <div class="receipt__info-title">Paiement</div>
                    <div class="receipt__info-val"><?php echo htmlspecialchars($currentOrder['payment']); ?></div>
                </div>
                <div class="receipt__info-block receipt__info-block--full">
                    <div class="receipt__info-title">Adresse de livraison</div>
                    <div class="receipt__info-val"><?php echo htmlspecialchars($currentOrder['address']); ?></div>
                </div>
                <?php if ($currentOrder['note']): ?>
                <div class="receipt__info-block receipt__info-block--full">
                    <div class="receipt__info-title">Note au vendeur</div>
                    <div class="receipt__info-val" style="font-style:italic;">"<?php echo htmlspecialchars($currentOrder['note']); ?>"</div>
                </div>
                <?php endif; ?>
            </div>

            <!-- ── Articles commandés ── -->
            <div class="receipt__section-title">Articles commandés</div>
            <div class="receipt__items">
                <?php foreach ($currentOrder['items'] as $item): ?>
                <div class="receipt__item">
                    <img src="<?php echo htmlspecialchars($item['image']); ?>"
                         alt="<?php echo htmlspecialchars($item['name']); ?>"
                         class="receipt__item-img" loading="lazy">
                    <div class="receipt__item-info">
                        <p class="receipt__item-name"><?php echo htmlspecialchars($item['name']); ?></p>
                        <p class="receipt__item-qty">Qté : <?php echo (int)$item['qty']; ?></p>
                    </div>
                    <div class="receipt__item-price">
                        <?php echo formatPrice($item['price'] * $item['qty']); ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- ── Totaux ── -->
            <div class="receipt__totals">
                <div class="receipt__total-row">
                    <span>Sous-total</span>
                    <span><?php echo formatPrice($currentOrder['subtotal']); ?></span>
                </div>
                <div class="receipt__total-row">
                    <span>Frais de livraison</span>
                    <span><?php echo formatPrice($currentOrder['shipping']); ?></span>
                </div>
                <div class="receipt__total-row receipt__total-row--grand">
                    <span>Total payé</span>
                    <span><?php echo formatPrice($currentOrder['total']); ?></span>
                </div>
            </div>

            <!-- ── Suivi de livraison ── -->
            <div class="receipt__section-title">Suivi de livraison</div>
            <div class="tracking-timeline">
                <?php foreach ($currentOrder['tracking'] as $i => $step): ?>
                <div class="tracking-step <?php echo $step['done'] ? 'tracking-step--done' : ''; ?>">
                    <div class="tracking-step__dot">
                        <?php if ($step['done']): ?>
                        <svg width="10" height="10" fill="white" viewBox="0 0 20 20">
                            <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                        </svg>
                        <?php endif; ?>
                    </div>
                    <?php if ($i < count($currentOrder['tracking']) - 1): ?>
                    <div class="tracking-step__line <?php echo $step['done'] ? 'tracking-step__line--done' : ''; ?>"></div>
                    <?php endif; ?>
                    <div class="tracking-step__info">
                        <span class="tracking-step__label"><?php echo htmlspecialchars($step['label']); ?></span>
                        <span class="tracking-step__date"><?php echo htmlspecialchars($step['date']); ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

        </div><!-- /.receipt__body -->
    </div><!-- /.receipt -->


    <?php else: ?>
    <!-- VUE LISTE DE TOUTES LES COMMANDES -->
    <?php if (empty($orders)): ?>
    <div class="orders-empty reveal">
        <svg width="4rem" height="4rem" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
            <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/>
            <rect x="9" y="3" width="6" height="4" rx="1" ry="1"/>
        </svg>
        <h2>Aucune commande</h2>
        <p>Vous n'avez pas encore passé de commande.</p>
        <a href="./Home.php" class="btn-primary">Découvrir les boutiques</a>
    </div>

    <?php else: ?>
    <div class="orders-list-full reveal-group">
        <?php foreach (array_reverse($orders) as $order):
            $sm = $statusMeta[$order['status']] ?? $statusMeta['pending'];
            $shop = getShopById($order['shop_id']);
        ?>
        <div class="order-row reveal">
            <!-- Icône boutique -->
            <div class="order-row__shop-img">
                <?php if ($shop): ?>
                <img src="<?php echo htmlspecialchars($shop['image']); ?>"
                     alt="<?php echo htmlspecialchars($shop['name']); ?>">
                <?php else: ?>
                <div class="order-row__shop-placeholder">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <?php endif; ?>
            </div>

            <!-- Infos principales -->
            <div class="order-row__main">
                <div class="order-row__top">
                    <span class="order-row__ref">#<?php echo htmlspecialchars($order['ref']); ?></span>
                    <span class="order-row__status"
                          style="color:<?php echo $sm['color']; ?>; background:<?php echo $sm['bg']; ?>;">
                        <?php echo $sm['label']; ?>
                    </span>
                </div>
                <p class="order-row__shop"><?php echo htmlspecialchars($order['shop_name']); ?></p>
                <p class="order-row__meta">
                    <?php echo count($order['items']); ?> article<?php echo count($order['items']) > 1 ? 's' : ''; ?>
                    &nbsp;·&nbsp;
                    <?php echo formatPrice($order['total']); ?>
                    &nbsp;·&nbsp;
                    <?php echo htmlspecialchars($order['date']); ?>
                </p>
                <!-- Aperçu des produits commandés -->
                <div class="order-row__thumbs">
                    <?php foreach (array_slice($order['items'], 0, 3) as $item): ?>
                    <img src="<?php echo htmlspecialchars($item['image']); ?>"
                         alt="<?php echo htmlspecialchars($item['name']); ?>"
                         class="order-thumb" title="<?php echo htmlspecialchars($item['name']); ?>">
                    <?php endforeach; ?>
                    <?php if (count($order['items']) > 3): ?>
                    <span class="order-thumb-more">+<?php echo count($order['items']) - 3; ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Actions -->
            <div class="order-row__actions">
                <a href="Orders.php?ref=<?php echo urlencode($order['ref']); ?>" class="btn-view-order">
                    Voir le reçu
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </a>
                <a href="./Contact.php?vendor=<?php echo urlencode($order['vendor_name']); ?>&order=<?php echo urlencode($order['ref']); ?>"
                   class="btn-contact-small" title="Contacter le vendeur">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>
                    </svg>
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
    <?php endif; /* fin mode liste / détail */ ?>

</div><!-- /.orders-page -->

<script src="../assets/Js/Commandes/orders.js"></script>
<?php include '../Includes/Footerr.php'; ?>

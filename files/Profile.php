<?php
/*
 * Profile.php — Profil utilisateur (client ou vendeur)
 * Accessible uniquement aux utilisateurs connectés.
 * Affiche un profil adapté au rôle :
 *   - client  : infos perso, historique commandes (résumé), messagerie
 *   - vendor  : idem + accès rapide au tableau de bord boutique
 */
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../Login & Register/Login.php?redirect_to=' . urlencode('./files/Profile.php'));
    exit();
}

require_once './Shops-data.php';

/* Données de l'utilisateur connecté */
$user = [
    'id'     => $_SESSION['user_id'],
    'name'   => $_SESSION['user_name']   ?? 'Utilisateur',
    'email'  => $_SESSION['user_email']  ?? '',
    'role'   => $_SESSION['user_role']   ?? 'client',
    'phone'  => $_SESSION['user_phone']  ?? '',
    'avatar' => $_SESSION['user_avatar'] ?? '',
    'since'  => $_SESSION['user_since']  ?? date('Y'),
];

$isVendor = ($user['role'] === 'vendor');

/* Statistiques simulées (remplacer par des requêtes DB plus tard) */
$stats = [
    'orders'    => count($_SESSION['orders']  ?? []),
    'cart'      => count($_SESSION['cart']    ?? []),
    'messages'  => count($_SESSION['messages'] ?? []),
];

/* Boutique du vendeur (si applicable) */
$myShop = null;
if ($isVendor) {
    foreach ($SHOPS_DATA as $s) {
        if (strtolower($s['vendor']) === strtolower($user['name'])) {
            $myShop = $s;
            break;
        }
    }
}

/* Message de succès éventuel */
$success = $_SESSION['profile_success'] ?? '';
unset($_SESSION['profile_success']);

include '../Includes/Header.php';
?>

<link rel="stylesheet" href="../assets/Css/Profil/profile.css">
<link rel="stylesheet" href="../assets/Css/fix-overflow.css">
<div class="profile-page">

    <!--  HERO PROFIL -->
    <section class="profile-hero">
        <div class="profile-hero__inner">

            <!-- Avatar + identité -->
            <div class="profile-identity reveal">
                <div class="profile-avatar-wrap">
                    <?php if (!empty($user['avatar'])): ?>
                        <img src="<?php echo htmlspecialchars($user['avatar']); ?>"
                             alt="Avatar" class="profile-avatar">
                    <?php else: ?>
                        <!-- Avatar généré avec initiale -->
                        <div class="profile-avatar profile-avatar--initials">
                            <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                        </div>
                    <?php endif; ?>
                    <!-- Bouton modifier avatar -->
                    <label class="profile-avatar__edit" title="Changer la photo" for="avatar-input">
                        <svg width="14" height="14" fill="none" stroke="white" stroke-width="2.5" viewBox="0 0 24 24">
                            <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                    </label>
                    <input type="file" id="avatar-input" accept="image/*" class="sr-only">
                </div>

                <div class="profile-identity__text">
                    <div class="profile-role-badge">
                        <?php if ($isVendor): ?>
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                            </svg>
                            Vendeur
                        <?php else: ?>
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
                            </svg>
                            Client
                        <?php endif; ?>
                    </div>
                    <h1 class="profile-name"><?php echo htmlspecialchars($user['name']); ?></h1>
                    <p class="profile-email"><?php echo htmlspecialchars($user['email']); ?></p>
                    <p class="profile-since">Membre depuis <?php echo htmlspecialchars($user['since']); ?></p>
                </div>
            </div>

            <!-- Stats rapides -->
            <div class="profile-stats reveal-group">
                <div class="profile-stat reveal">
                    <span class="profile-stat__num"><?php echo $stats['orders']; ?></span>
                    <span class="profile-stat__label">Commandes</span>
                </div>
                <div class="profile-stat reveal">
                    <span class="profile-stat__num"><?php echo $stats['cart']; ?></span>
                    <span class="profile-stat__label">Au panier</span>
                </div>
                <div class="profile-stat reveal">
                    <span class="profile-stat__num"><?php echo $stats['messages']; ?></span>
                    <span class="profile-stat__label">Messages</span>
                </div>
            </div>
        </div>
    </section>

    <!-- CONTENU PRINCIPAL : onglets -->
    <div class="profile-body">

        <!-- Message succès -->
        <?php if ($success): ?>
        <div class="profile-alert profile-alert--success" role="alert">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
            </svg>
            <?php echo htmlspecialchars($success); ?>
        </div>
        <?php endif; ?>

        <!-- Navigation onglets -->
        <nav class="profile-tabs" role="tablist">
            <button class="profile-tab active" role="tab" data-tab="infos" aria-selected="true">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
                </svg>
                Mes informations
            </button>
            <button class="profile-tab" role="tab" data-tab="orders" aria-selected="false">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/>
                    <rect x="9" y="3" width="6" height="4" rx="1" ry="1"/>
                </svg>
                Commandes
            </button>
            <button class="profile-tab" role="tab" data-tab="messages" aria-selected="false">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>
                </svg>
                Messages
                <?php if ($stats['messages'] > 0): ?>
                <span class="tab-badge"><?php echo $stats['messages']; ?></span>
                <?php endif; ?>
            </button>
            <?php if ($isVendor): ?>
            <button class="profile-tab" role="tab" data-tab="shop" aria-selected="false">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                </svg>
                Ma boutique
            </button>
            <?php endif; ?>
        </nav>

        <!-- ── Onglet : Mes informations ── -->
        <div class="profile-panel active" id="tab-infos" role="tabpanel">
            <form action="../Actions/profile-handler.php" method="POST" class="profile-form" id="profile-form">
                <input type="hidden" name="action" value="update_profile">

                <div class="profile-form__grid">
                    <!-- Nom -->
                    <div class="form-group">
                        <label class="form-label" for="p-name">Nom complet</label>
                        <div class="form-input-wrap">
                            <svg class="form-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
                            </svg>
                            <input type="text" id="p-name" name="name" class="form-input"
                                   value="<?php echo htmlspecialchars($user['name']); ?>" required>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label class="form-label" for="p-email">Adresse e-mail</label>
                        <div class="form-input-wrap">
                            <svg class="form-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                <polyline points="22,6 12,13 2,6"/>
                            </svg>
                            <input type="email" id="p-email" name="email" class="form-input"
                                   value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        </div>
                    </div>

                    <!-- Téléphone -->
                    <div class="form-group">
                        <label class="form-label" for="p-phone">Téléphone</label>
                        <div class="form-input-wrap">
                            <svg class="form-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 014.5 10.83a19.79 19.79 0 01-3.07-8.67A2 2 0 013.4 0h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L7.91 7.91a16 16 0 006.18 6.18l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 14.92z"/>
                            </svg>
                            <input type="tel" id="p-phone" name="phone" class="form-input"
                                   value="<?php echo htmlspecialchars($user['phone']); ?>"
                                   placeholder="+242 06 XXX XX XX">
                        </div>
                    </div>

                    <!-- Adresse de livraison -->
                    <div class="form-group form-group--full">
                        <label class="form-label" for="p-address">Adresse de livraison principale</label>
                        <div class="form-input-wrap">
                            <svg class="form-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/>
                            </svg>
                            <input type="text" id="p-address" name="address" class="form-input"
                                   value="<?php echo htmlspecialchars($_SESSION['user_address'] ?? ''); ?>"
                                   placeholder="Quartier, Ville, Pays">
                        </div>
                    </div>
                </div>

                <!-- Section changer mot de passe -->
                <div class="profile-section-title">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/>
                    </svg>
                    Changer le mot de passe
                </div>
                <div class="profile-form__grid">
                    <div class="form-group">
                        <label class="form-label" for="p-pwd-current">Mot de passe actuel</label>
                        <div class="form-input-wrap">
                            <svg class="form-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/>
                            </svg>
                            <input type="password" id="p-pwd-current" name="password_current"
                                   class="form-input" placeholder="Laisser vide si inchangé">
                            <button type="button" class="form-toggle-pwd" data-target="p-pwd-current" aria-label="Voir">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="icon-eye">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="p-pwd-new">Nouveau mot de passe</label>
                        <div class="form-input-wrap">
                            <svg class="form-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/>
                            </svg>
                            <input type="password" id="p-pwd-new" name="password_new"
                                   class="form-input" placeholder="8 caractères minimum">
                            <button type="button" class="form-toggle-pwd" data-target="p-pwd-new" aria-label="Voir">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="icon-eye">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="profile-form__actions">
                    <button type="submit" class="btn-primary">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/>
                            <polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/>
                        </svg>
                        Enregistrer les modifications
                    </button>
                    <a href="../Actions/auth-handler.php?logout=1" class="btn-logout">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/>
                            <polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
                        </svg>
                        Se déconnecter
                    </a>
                </div>
            </form>
        </div>

        <!-- ── Onglet : Commandes ── -->
        <div class="profile-panel" id="tab-orders" role="tabpanel">
            <?php
            $orders = $_SESSION['orders'] ?? [];
            if (empty($orders)):
            ?>
            <div class="profile-empty">
                <svg width="3rem" height="3rem" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/>
                    <rect x="9" y="3" width="6" height="4" rx="1" ry="1"/>
                </svg>
                <p>Aucune commande pour le moment.</p>
                <a href="./Shop-detail.php" class="btn-primary" style="margin-top:1rem;">Découvrir les boutiques</a>
            </div>
            <?php else: ?>
            <div class="orders-list">
                <?php foreach (array_reverse($orders) as $order): ?>
                <div class="order-card">
                    <div class="order-card__head">
                        <div>
                            <span class="order-ref">Commande #<?php echo htmlspecialchars($order['ref']); ?></span>
                            <span class="order-date"><?php echo htmlspecialchars($order['date']); ?></span>
                        </div>
                        <span class="order-status order-status--<?php echo htmlspecialchars($order['status']); ?>">
                            <?php
                            $statusLabel = ['pending'=>'En attente','processing'=>'En cours','shipped'=>'Expédiée','delivered'=>'Livrée','cancelled'=>'Annulée'];
                            echo $statusLabel[$order['status']] ?? $order['status'];
                            ?>
                        </span>
                    </div>
                    <a href="../files/Orders.php?ref=<?php echo urlencode($order['ref']); ?>" class="order-card__link">
                        Voir le détail →
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- ── Onglet : Messages ── -->
        <div class="profile-panel" id="tab-messages" role="tabpanel">
            <?php
            $messages = $_SESSION['messages'] ?? [];
            if (empty($messages)):
            ?>
            <div class="profile-empty">
                <svg width="3rem" height="3rem" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>
                </svg>
                <p>Aucun message pour le moment.</p>
                <a href="../files/Contact.php" class="btn-primary" style="margin-top:1rem;">Contacter un vendeur</a>
            </div>
            <?php else: ?>
            <div class="messages-list">
                <?php foreach (array_reverse($messages) as $msg): ?>
                <div class="message-card <?php echo $msg['unread'] ? 'message-card--unread' : ''; ?>">
                    <div class="message-card__avatar">
                        <?php echo strtoupper(substr($msg['from'] ?? 'V', 0, 1)); ?>
                    </div>
                    <div class="message-card__body">
                        <div class="message-card__head">
                            <strong><?php echo htmlspecialchars($msg['from']); ?></strong>
                            <span class="message-date"><?php echo htmlspecialchars($msg['date']); ?></span>
                        </div>
                        <p class="message-preview"><?php echo htmlspecialchars(substr($msg['text'], 0, 120)); ?>…</p>
                    </div>
                    <?php if ($msg['unread']): ?>
                    <span class="message-unread-dot"></span>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- ── Onglet : Ma boutique (vendeurs uniquement) ── -->
        <?php if ($isVendor): ?>
        <div class="profile-panel" id="tab-shop" role="tabpanel">
            <?php if ($myShop): ?>
            <div class="vendor-shop-card reveal">
                <img src="<?php echo htmlspecialchars($myShop['banner']); ?>"
                     alt="Bannière" class="vendor-shop-card__banner">
                <div class="vendor-shop-card__body">
                    <img src="<?php echo htmlspecialchars($myShop['avatar']); ?>"
                         alt="Avatar" class="vendor-shop-card__avatar">
                    <div class="vendor-shop-card__info">
                        <h3><?php echo htmlspecialchars($myShop['name']); ?></h3>
                        <p><?php echo htmlspecialchars($myShop['description']); ?></p>
                        <div class="vendor-shop-card__meta">
                            <span>⭐ <?php echo number_format($myShop['rating'], 1); ?>/5</span>
                            <span>📍 <?php echo htmlspecialchars($myShop['location']); ?></span>
                            <span><?php echo $myShop['is_open'] ? '● Ouvert' : '● Fermé'; ?></span>
                        </div>
                    </div>
                    <div class="vendor-shop-card__actions">
                        <a href="../files/Shop-detail.php?id=<?php echo (int)$myShop['id']; ?>" class="btn-primary">
                            Voir ma boutique
                        </a>
                        <a href="../Int_Vendors/Dashboard.php" class="btn-outline">
                            Tableau de bord
                        </a>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <div class="profile-empty">
                <svg width="3rem" height="3rem" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                </svg>
                <p>Vous n'avez pas encore de boutique.</p>
                <a href="../Int_Vendors/Create-shop.php" class="btn-primary" style="margin-top:1rem;">Créer ma boutique</a>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

    </div><!-- /.profile-body -->
</div><!-- /.profile-page -->

<script src="../assets/Js/Profil/profile.js"></script>
<?php include '../Includes/Footerr.php'; ?>

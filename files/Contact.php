<?php
/*
 * Contact.php — Page de contact
 * Double usage :
 *  1. Contact général E-tool (support, partenariat, etc.)
 *  2. Contact d'un vendeur spécifique (depuis Shop-detail ou Orders)
 *     via les paramètres GET : ?vendor=NomVendeur&order=REF
 * Accessible sans compte pour les questions générales.
 * Pour contacter un vendeur, une connexion est recommandée
 * (pré-remplissage du formulaire).
 */
session_start();

require_once './Shops-data.php';

/* Paramètres GET : contact vendeur spécifique */
$vendorName  = $_GET['vendor'] ?? '';
$orderRef    = $_GET['order']  ?? '';
$isVendorMsg = !empty($vendorName);

/* Trouve la boutique du vendeur demandé */
$targetShop = null;
if ($isVendorMsg) {
    foreach ($SHOPS_DATA as $s) {
        if (strtolower($s['vendor']) === strtolower($vendorName)) {
            $targetShop = $s;
            break;
        }
    }
}

/* Données de l'utilisateur connecté pour pré-remplissage */
$loggedIn  = isset($_SESSION['user_id']);
$userName  = $_SESSION['user_name']  ?? '';
$userEmail = $_SESSION['user_email'] ?? '';

/* Message après envoi */
$success = $_SESSION['contact_success'] ?? '';
$error   = $_SESSION['contact_error']   ?? '';
unset($_SESSION['contact_success'], $_SESSION['contact_error']);

include '../Includes/Header.php';
?>

<link rel="stylesheet" href="../assets/Css/Contact/contact.css">
<link rel="stylesheet" href="../assets/Css/fix-overflow.css">
<div class="contact-page">

    <!-- HERO CONTACT -->
    <section class="contact-hero">
        <div class="contact-hero__inner reveal">
            <?php if ($isVendorMsg && $targetShop): ?>
                <span class="contact-eyebrow">Messagerie vendeur</span>
                <h1 class="contact-hero__title">
                    Contacter <span><?php echo htmlspecialchars($targetShop['vendor']); ?></span>
                </h1>
                <p class="contact-hero__sub">
                    Posez vos questions directement à la boutique
                    <strong><?php echo htmlspecialchars($targetShop['name']); ?></strong>.
                    <?php if ($orderRef): ?>
                    Concernant la commande <strong>#<?php echo htmlspecialchars($orderRef); ?></strong>.
                    <?php endif; ?>
                </p>
            <?php else: ?>
                <span class="contact-eyebrow">Nous contacter</span>
                <h1 class="contact-hero__title">
                    Comment pouvons-nous <span>vous aider ?</span>
                </h1>
                <p class="contact-hero__sub">
                    Une question, un problème ou une demande de partenariat ? Notre équipe vous répond sous 24h.
                </p>
            <?php endif; ?>
        </div>
    </section>

    <div class="contact-body">

        <!-- ── Colonne gauche : formulaire ── -->
        <div class="contact-form-col reveal">

            <!-- Messages de retour -->
            <?php if ($success): ?>
            <div class="contact-alert contact-alert--success" role="alert">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
                <?php echo htmlspecialchars($success); ?>
            </div>
            <?php endif; ?>
            <?php if ($error): ?>
            <div class="contact-alert contact-alert--error" role="alert">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <?php echo htmlspecialchars($error); ?>
            </div>
            <?php endif; ?>

            <!-- Profil du vendeur ciblé (si applicable) -->
            <?php if ($isVendorMsg && $targetShop): ?>
            <div class="vendor-contact-card">
                <img src="<?php echo htmlspecialchars($targetShop['vendor_avatar']); ?>"
                     alt="<?php echo htmlspecialchars($targetShop['vendor']); ?>"
                     class="vendor-contact-card__avatar">
                <div>
                    <strong class="vendor-contact-card__name">
                        <?php echo htmlspecialchars($targetShop['vendor']); ?>
                    </strong>
                    <p class="vendor-contact-card__shop">
                        <?php echo htmlspecialchars($targetShop['name']); ?>
                        &nbsp;·&nbsp;
                        <?php echo htmlspecialchars($targetShop['location']); ?>
                    </p>
                    <span class="vendor-contact-card__status <?php echo $targetShop['is_open'] ? 'open' : 'closed'; ?>">
                        <?php echo $targetShop['is_open'] ? '● Répond généralement sous 2h' : '● Actuellement fermé — répond sous 24h'; ?>
                    </span>
                </div>
            </div>
            <?php endif; ?>

            <form action="../Actions/contact-handler.php" method="POST"
                  class="contact-form" id="contact-form" novalidate>

                <input type="hidden" name="is_vendor_msg"  value="<?php echo $isVendorMsg ? '1' : '0'; ?>">
                <input type="hidden" name="vendor_name"    value="<?php echo htmlspecialchars($vendorName); ?>">
                <input type="hidden" name="order_ref"      value="<?php echo htmlspecialchars($orderRef); ?>">
                <input type="hidden" name="shop_id"        value="<?php echo $targetShop ? (int)$targetShop['id'] : ''; ?>">

                <div class="contact-form__grid">

                    <!-- Nom -->
                    <div class="form-group">
                        <label class="form-label" for="c-name">Votre nom</label>
                        <div class="form-input-wrap">
                            <svg class="form-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
                            </svg>
                            <input type="text" id="c-name" name="name" class="form-input"
                                   value="<?php echo htmlspecialchars($userName); ?>"
                                   placeholder="Votre nom complet" required>
                        </div>
                        <span class="form-error" id="err-c-name"></span>
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label class="form-label" for="c-email">Votre e-mail</label>
                        <div class="form-input-wrap">
                            <svg class="form-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                <polyline points="22,6 12,13 2,6"/>
                            </svg>
                            <input type="email" id="c-email" name="email" class="form-input"
                                   value="<?php echo htmlspecialchars($userEmail); ?>"
                                   placeholder="votre@email.com" required>
                        </div>
                        <span class="form-error" id="err-c-email"></span>
                    </div>

                    <!-- Sujet -->
                    <div class="form-group form-group--full">
                        <label class="form-label" for="c-subject">Sujet</label>
                        <div class="form-input-wrap">
                            <svg class="form-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/>
                                <line x1="8" y1="18" x2="21" y2="18"/>
                                <line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/>
                                <line x1="3" y1="18" x2="3.01" y2="18"/>
                            </svg>
                            <select id="c-subject" name="subject" class="form-input form-select" required>
                                <option value="">-- Choisir un sujet --</option>
                                <?php if ($isVendorMsg): ?>
                                <option value="order_question" <?php echo $orderRef ? 'selected' : ''; ?>>
                                    Question sur une commande<?php echo $orderRef ? ' (#'.$orderRef.')' : ''; ?>
                                </option>
                                <option value="product_info">Renseignement sur un produit</option>
                                <option value="delivery">Problème de livraison</option>
                                <option value="return">Retour / remboursement</option>
                                <option value="custom_order">Commande personnalisée</option>
                                <option value="other_vendor">Autre</option>
                                <?php else: ?>
                                <option value="support">Support technique</option>
                                <option value="account">Problème de compte</option>
                                <option value="partnership">Devenir partenaire vendeur</option>
                                <option value="payment">Question de paiement</option>
                                <option value="report">Signaler un problème</option>
                                <option value="other">Autre</option>
                                <?php endif; ?>
                            </select>
                        </div>
                        <span class="form-error" id="err-c-subject"></span>
                    </div>

                    <!-- Message -->
                    <div class="form-group form-group--full">
                        <label class="form-label" for="c-message">
                            Message
                            <span class="form-char-count" id="char-count">0/800</span>
                        </label>
                        <textarea id="c-message" name="message" class="form-textarea"
                                  rows="6" maxlength="800" required
                                  placeholder="<?php echo $isVendorMsg ? 'Décrivez votre demande au vendeur...' : 'Décrivez votre demande...'; ?>"></textarea>
                        <span class="form-error" id="err-c-message"></span>
                    </div>

                    <!-- Pièce jointe (optionnel) -->
                    <div class="form-group form-group--full">
                        <label class="form-label" for="c-file">
                            Pièce jointe
                            <span class="form-optional">(optionnel — photo du produit, capture d'écran…)</span>
                        </label>
                        <div class="form-file-wrap" id="drop-zone">
                            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path d="M21.44 11.05l-9.19 9.19a6 6 0 01-8.49-8.49l9.19-9.19a4 4 0 015.66 5.66l-9.2 9.19a2 2 0 01-2.83-2.83l8.49-8.48"/>
                            </svg>
                            <span id="file-label">Glisser un fichier ici ou <u>choisir</u></span>
                            <input type="file" id="c-file" name="attachment"
                                   accept="image/*,.pdf" class="form-file-input">
                        </div>
                    </div>

                    <!-- Si non connecté : rappel -->
                    <?php if (!$loggedIn): ?>
                    <div class="contact-login-tip form-group--full">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                        <span>
                            <a href="../Login & Register/Login.php">Connectez-vous</a> pour suivre vos messages dans votre espace profil.
                        </span>
                    </div>
                    <?php endif; ?>

                </div><!-- /.contact-form__grid -->

                <button type="submit" class="btn-auth btn-send" id="contact-submit">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <line x1="22" y1="2" x2="11" y2="13"/>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                    </svg>
                    <span><?php echo $isVendorMsg ? 'Envoyer au vendeur' : 'Envoyer le message'; ?></span>
                </button>

            </form>
        </div><!-- /.contact-form-col -->

        <!-- ── Colonne droite : infos & autres boutiques ── -->
        <aside class="contact-aside reveal">

            <?php if (!$isVendorMsg): ?>
            <!-- Infos de contact E-tool -->
            <div class="contact-info-card">
                <h3 class="contact-info-card__title">E-tool Market</h3>
                <ul class="contact-info-list">
                    <li>
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                            <polyline points="22,6 12,13 2,6"/>
                        </svg>
                        <span>support@etool-market.com</span>
                    </li>
                    <li>
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 014.5 10.83a19.79 19.79 0 01-3.07-8.67A2 2 0 013.4 0h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L7.91 7.91a16 16 0 006.18 6.18l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 14.92z"/>
                        </svg>
                        <span>+242 05 054 88 48</span>
                    </li>
                    <li>
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/>
                        </svg>
                        <span>Pointe-Noire, République du Congo</span>
                    </li>
                    <li>
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                        </svg>
                        <span>Lun – Ven : 8h – 18h</span>
                    </li>
                </ul>
            </div>
            <?php endif; ?>

            <!-- Nos vendeurs partenaires (liens rapides) -->
            <div class="contact-shops-card">
                <h3 class="contact-shops-card__title">Nos vendeurs partenaires</h3>
                <div class="contact-shops-list">
                    <?php foreach ($SHOPS_DATA as $s): ?>
                    <a href="./Contact.php?vendor=<?php echo urlencode($s['vendor']); ?>"
                       class="contact-shop-item <?php echo ($targetShop && $targetShop['id'] === $s['id']) ? 'active' : ''; ?>">
                        <img src="<?php echo htmlspecialchars($s['avatar']); ?>"
                             alt="<?php echo htmlspecialchars($s['name']); ?>"
                             class="contact-shop-item__img">
                        <div>
                            <span class="contact-shop-item__name"><?php echo htmlspecialchars($s['name']); ?></span>
                            <span class="contact-shop-item__vendor"><?php echo htmlspecialchars($s['vendor']); ?></span>
                        </div>
                        <span class="contact-shop-item__status <?php echo $s['is_open'] ? 'open' : 'closed'; ?>">●</span>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>

        </aside>

    </div><!-- /.contact-body -->
</div><!-- /.contact-page -->

<script src="../assets/Js/Contact/contact.js"></script>
<?php include '../Includes/Footerr.php'; ?>

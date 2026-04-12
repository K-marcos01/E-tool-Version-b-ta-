<?php
/**
 * Register.php — Page d'inscription E-tool
 * Situé dans : /Login & Register/Register.php
 */
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: ../Int_Clients/Home.php');
    exit();
}

$redirect = htmlspecialchars($_GET['redirect_to'] ?? '../Int_Clients/Home.php', ENT_QUOTES);
$error    = $_SESSION['auth_error']   ?? '';
unset($_SESSION['auth_error']);
$old = $_SESSION['auth_old'] ?? [];
unset($_SESSION['auth_old']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription — E-tool</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/Css/Auth/auth.css">
    <link rel="stylesheet" href="../assets/Css/fix-overflow.css">
</head>
<body class="auth-page">

<div class="auth-split auth-split--register">

    <!-- ════ Colonne visuelle ════ -->
    <div class="auth-visual">
        <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?q=80&w=900"
             alt="Artisans E-tool" class="auth-visual__img">
        <div class="auth-visual__overlay"></div>

        <div class="auth-visual__content">
            <a href="../Int_Clients/Home.php" class="auth-logo">
                <span class="auth-logo__box">
                    <img src="../Img/Dynamic 'E' Logo for E-tool Project.png" alt="E-tool">
                </span>
                <span class="auth-logo__text">E<span style="color:#F97316;">-</span>tool</span>
            </a>

            <div class="auth-visual__middle">
                <h2 class="auth-visual__title">
                    Rejoignez notre<br><span>communauté</span>
                </h2>
                <p class="auth-visual__sub">
                    Créez un compte pour commander chez nos artisans locaux, suivre vos livraisons et communiquer directement avec les vendeurs.
                </p>
                <ul class="auth-perks">
                    <li>
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>
                        Explorer les boutiques librement
                    </li>
                    <li>
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>
                        Commander dans plusieurs boutiques
                    </li>
                    <li>
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>
                        Suivi de livraison en temps réel
                    </li>
                    <li>
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>
                        Messagerie directe vendeurs
                    </li>
                </ul>
                <a href="../Int_Clients/Home.php" class="auth-guest-link">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    Explorer sans créer de compte
                </a>
            </div>

            <div class="auth-visual__quote">
                <blockquote>"Derrière chaque produit se cache une passion."</blockquote>
                <cite>— L'équipe E-tool</cite>
            </div>
        </div>
    </div>

    <!-- ════ Colonne formulaire ════ -->
    <div class="auth-form-col">
        <div class="auth-card auth-card--register">

            <div class="auth-card__head">
                <span class="auth-card__tag">Inscription gratuite</span>
                <h1 class="auth-card__title">Créer un compte</h1>
                <p class="auth-card__sub">Sans engagement · Pas de carte requise</p>
            </div>

            <?php if ($error): ?>
            <div class="auth-alert auth-alert--error" role="alert">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <?php echo htmlspecialchars($error); ?>
            </div>
            <?php endif; ?>

            <!-- Sélecteur de rôle -->
            <div class="role-selector" id="role-selector">
                <button type="button" class="role-btn active" data-role="client">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
                    </svg>
                    <span>Client</span>
                    <small>Acheter des produits</small>
                </button>
                <button type="button" class="role-btn" data-role="vendor">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
                    </svg>
                    <span>Vendeur</span>
                    <small>Ouvrir une boutique</small>
                </button>
            </div>

            <form action="../Actions/auth-handler.php" method="POST"
                  class="auth-form" id="register-form" novalidate>
                <input type="hidden" name="action"      value="register">
                <input type="hidden" name="redirect_to" value="<?php echo $redirect; ?>">
                <input type="hidden" name="role"        id="role-input" value="client">

                <!-- Nom -->
                <div class="form-group">
                    <label class="form-label" for="reg-name">Nom complet</label>
                    <div class="form-input-wrap">
                        <svg class="form-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
                        </svg>
                        <input type="text" id="reg-name" name="name" class="form-input"
                               placeholder="Prénom Nom"
                               value="<?php echo htmlspecialchars($old['name'] ?? ''); ?>"
                               autocomplete="name" required>
                    </div>
                    <span class="form-error" id="err-name"></span>
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label class="form-label" for="reg-email">Adresse e-mail</label>
                    <div class="form-input-wrap">
                        <svg class="form-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                            <polyline points="22,6 12,13 2,6"/>
                        </svg>
                        <input type="email" id="reg-email" name="email" class="form-input"
                               placeholder="votre@email.com"
                               value="<?php echo htmlspecialchars($old['email'] ?? ''); ?>"
                               autocomplete="email" required>
                    </div>
                    <span class="form-error" id="err-reg-email"></span>
                </div>

                <!-- Téléphone -->
                <div class="form-group">
                    <label class="form-label" for="reg-phone">
                        Téléphone <span class="form-optional">(optionnel)</span>
                    </label>
                    <div class="form-input-wrap">
                        <svg class="form-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 014.5 10.83a19.79 19.79 0 01-3.07-8.67A2 2 0 013.4 0h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L7.91 7.91a16 16 0 006.18 6.18l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 14.92z"/>
                        </svg>
                        <input type="tel" id="reg-phone" name="phone" class="form-input"
                               placeholder="+242 06 XXX XX XX"
                               value="<?php echo htmlspecialchars($old['phone'] ?? ''); ?>"
                               autocomplete="tel">
                    </div>
                </div>

                <!-- Mot de passe -->
                <div class="form-group">
                    <label class="form-label" for="reg-pwd">Mot de passe</label>
                    <div class="form-input-wrap">
                        <svg class="form-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/>
                        </svg>
                        <input type="password" id="reg-pwd" name="password" class="form-input"
                               placeholder="8 caractères minimum" autocomplete="new-password" required>
                        <button type="button" class="form-toggle-pwd" data-target="reg-pwd" aria-label="Voir">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="icon-eye">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                    <div class="pwd-strength" id="pwd-strength">
                        <div class="pwd-strength__bars" id="pwd-bars">
                            <span></span><span></span><span></span><span></span>
                        </div>
                        <span class="pwd-strength__label" id="pwd-label"></span>
                    </div>
                    <span class="form-error" id="err-pwd"></span>
                </div>

                <!-- Confirmation -->
                <div class="form-group">
                    <label class="form-label" for="reg-confirm">Confirmer le mot de passe</label>
                    <div class="form-input-wrap">
                        <svg class="form-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/>
                        </svg>
                        <input type="password" id="reg-confirm" name="password_confirm" class="form-input"
                               placeholder="Répétez le mot de passe" autocomplete="new-password" required>
                        <button type="button" class="form-toggle-pwd" data-target="reg-confirm" aria-label="Voir">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="icon-eye">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                    <span class="form-error" id="err-confirm"></span>
                </div>

                <!-- CGV -->
                <div class="form-check">
                    <input type="checkbox" id="terms" name="terms" class="form-checkbox" required>
                    <label for="terms" class="form-check-label">
                        J'accepte les <a href="#">conditions d'utilisation</a> et la <a href="#">politique de confidentialité</a>
                    </label>
                </div>
                <span class="form-error" id="err-terms"></span>

                <button type="submit" class="btn-auth" id="register-submit">
                    <span>Créer mon compte</span>
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </button>
            </form>

            <div class="auth-divider"><span>ou</span></div>

            <p class="auth-switch">
                Déjà un compte ?
                <a href="./Login.php<?php echo ($redirect !== '../Int_Clients/Home.php') ? '?redirect_to='.urlencode($redirect) : ''; ?>">
                    Se connecter
                </a>
            </p>

        </div>
    </div>

</div>

<script src="../assets/Js/Auth/auth.js"></script>
</body>
</html>

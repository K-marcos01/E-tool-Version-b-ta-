<?php
/**
 * Login.php — Page de connexion E-tool
 * Situé dans : /Login & Register/Login.php
 */
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: ../Int_Clients/Home.php');
    exit();
}

$redirect = htmlspecialchars($_GET['redirect_to'] ?? '../Int_Clients/Home.php', ENT_QUOTES);
$error    = $_SESSION['auth_error']   ?? '';
$success  = $_SESSION['auth_success'] ?? '';
unset($_SESSION['auth_error'], $_SESSION['auth_success']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — E-tool</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/Css/Auth/auth.css">
    <link rel="stylesheet" href="../assets/Css/fix-overflow.css">
</head>
<body class="auth-page">

<div class="auth-split">

    <!-- ════ Colonne visuelle ════ -->
    <div class="auth-visual">
        <img src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?q=80&w=900"
             alt="E-tool marketplace" class="auth-visual__img">
        <div class="auth-visual__overlay"></div>

        <div class="auth-visual__content">
            <!-- Logo -->
            <a href="../Int_Clients/Home.php" class="auth-logo">
                <span class="auth-logo__box">
                    <img src="../Img/Dynamic 'E' Logo for E-tool Project.png" alt="E-tool">
                </span>
                <span class="auth-logo__text">E<span style="color:#F97316;">-</span>tool</span>
            </a>

            <!-- Titre central -->
            <div class="auth-visual__middle">
                <h2 class="auth-visual__title">
                    La marketplace des<br><span>créateurs locaux</span>
                </h2>
                <p class="auth-visual__sub">
                    Parcourez librement les boutiques de nos artisans. Connectez-vous pour commander, suivre vos achats et écrire aux vendeurs.
                </p>
                <a href="../Int_Clients/Home.php" class="auth-guest-link">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    Continuer sans compte
                </a>
            </div>

            <!-- Citation -->
            <div class="auth-visual__quote">
                <blockquote>"Chaque achat est une rencontre avec un savoir-faire."</blockquote>
                <cite>— L'équipe E-tool</cite>
            </div>
        </div>
    </div>

    <!-- ════ Colonne formulaire ════ -->
    <div class="auth-form-col">
        <div class="auth-card">

            <div class="auth-card__head">
                <span class="auth-card__tag">Interface client</span>
                <h1 class="auth-card__title">Bon retour 👋</h1>
                <p class="auth-card__sub">Connectez-vous à votre espace E-tool</p>
            </div>

            <?php if ($error): ?>
            <div class="auth-alert auth-alert--error" role="alert">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <?php echo htmlspecialchars($error); ?>
            </div>
            <?php endif; ?>

            <?php if ($success): ?>
            <div class="auth-alert auth-alert--success" role="alert">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
                <?php echo htmlspecialchars($success); ?>
            </div>
            <?php endif; ?>

            <form action="../Actions/auth-handler.php" method="POST"
                  class="auth-form" id="login-form" novalidate>
                <input type="hidden" name="action"      value="login">
                <input type="hidden" name="redirect_to" value="<?php echo $redirect; ?>">

                <!-- Email -->
                <div class="form-group">
                    <label class="form-label" for="login-email">Adresse e-mail</label>
                    <div class="form-input-wrap">
                        <svg class="form-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                            <polyline points="22,6 12,13 2,6"/>
                        </svg>
                        <input type="email" id="login-email" name="email" class="form-input"
                               placeholder="votre@email.com" autocomplete="email" required>
                    </div>
                    <span class="form-error" id="err-email"></span>
                </div>

                <!-- Mot de passe -->
                <div class="form-group">
                    <div class="form-label-row">
                        <label class="form-label" for="login-pwd">Mot de passe</label>
                        <a href="#" class="form-forgot">Mot de passe oublié ?</a>
                    </div>
                    <div class="form-input-wrap">
                        <svg class="form-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/>
                        </svg>
                        <input type="password" id="login-pwd" name="password" class="form-input"
                               placeholder="••••••••" autocomplete="current-password" required>
                        <button type="button" class="form-toggle-pwd" data-target="login-pwd" aria-label="Voir">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="icon-eye">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                    <span class="form-error" id="err-password"></span>
                </div>

                <!-- Se souvenir -->
                <div class="form-check">
                    <input type="checkbox" id="remember" name="remember" class="form-checkbox">
                    <label for="remember" class="form-check-label">Se souvenir de moi</label>
                </div>

                <button type="submit" class="btn-auth" id="login-submit">
                    <span>Se connecter</span>
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </button>
            </form>

            <div class="auth-divider"><span>ou</span></div>

            <p class="auth-switch">
                Pas encore de compte ?
                <a href="./Register.php<?php echo ($redirect !== '../Int_Clients/Home.php') ? '?redirect_to='.urlencode($redirect) : ''; ?>">
                    Créer un compte gratuitement
                </a>
            </p>

        </div>
    </div>
</div>

<script src="../assets/Js/Auth/auth.js"></script>
</body>
</html>

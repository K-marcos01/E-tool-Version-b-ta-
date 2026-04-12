<?php
/**
 * auth-handler.php — Gestionnaire Login / Register
 * ════════════════════════════════════════════════════════════
 * Reçoit les formulaires de Login.php et Register.php (POST).
 * Actuellement : stockage en session (sans DB).
 * À remplacer plus tard par des requêtes PDO sur la table users.
 *
 * Actions supportées :
 *   - login    : vérifie les credentials, ouvre la session
 *   - register : crée un compte simulé, ouvre la session
 *   - logout   : détruit la session et redirige vers Login
 * ════════════════════════════════════════════════════════════
 */
session_start();

/* ── Sécurité : POST uniquement ── */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../Auth/Login.php');
    exit();
}

$action      = $_POST['action']      ?? '';
$redirect_to = $_POST['redirect_to'] ?? '../Int_Clients/Home.php';

/* Nettoie la destination */
$redirect_to = filter_var($redirect_to, FILTER_SANITIZE_URL);

/* ════════════════════════════════════════════════════════════
   ACTION : LOGIN
════════════════════════════════════════════════════════════ */
if ($action === 'login') {

    $email    = trim($_POST['email']    ?? '');
    $password = $_POST['password']      ?? '';
    $remember = isset($_POST['remember']);

    /* ── Validation basique ── */
    if (empty($email) || empty($password)) {
        $_SESSION['auth_error'] = 'Veuillez remplir tous les champs.';
        header('Location: ../Auth/Login.php?redirect_to=' . urlencode($redirect_to));
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['auth_error'] = 'Adresse e-mail invalide.';
        header('Location: ../Auth/Login.php?redirect_to=' . urlencode($redirect_to));
        exit();
    }

    /*
     * ══════════════════════════════════════════════════════
     * TODO (phase DB) : remplacer ce bloc par une requête PDO
     *
     * $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
     * $stmt->execute([$email]);
     * $user = $stmt->fetch(PDO::FETCH_ASSOC);
     * if (!$user || !password_verify($password, $user['password'])) {
     *     $_SESSION['auth_error'] = 'Email ou mot de passe incorrect.';
     *     header('Location: ../Auth/Login.php'); exit();
     * }
     * ══════════════════════════════════════════════════════
     *
     * SIMULATION TEMPORAIRE (sans DB) :
     * On accepte n'importe quelle combinaison valide et on crée
     * une session utilisateur depuis les données saisies.
     * Les comptes sont stockés en session jusqu'à ce que la DB soit prête.
     */

    /* Cherche le compte dans les comptes enregistrés en session */
    $accounts = $_SESSION['registered_accounts'] ?? [];
    $found    = null;

    foreach ($accounts as $account) {
        if (strtolower($account['email']) === strtolower($email)) {
            if (password_verify($password, $account['password_hash'])) {
                $found = $account;
                break;
            }
        }
    }

    if (!$found) {
        $_SESSION['auth_error'] = 'Email ou mot de passe incorrect.';
        header('Location: ../Auth/Login.php?redirect_to=' . urlencode($redirect_to));
        exit();
    }

    /* Ouvre la session utilisateur */
    session_regenerate_id(true);
    $_SESSION['user_id']     = $found['id'];
    $_SESSION['user_name']   = $found['name'];
    $_SESSION['user_email']  = $found['email'];
    $_SESSION['user_role']   = $found['role'];
    $_SESSION['user_phone']  = $found['phone']  ?? '';
    $_SESSION['user_avatar'] = $found['avatar'] ?? '';
    $_SESSION['user_since']  = $found['since']  ?? date('Y');

    /* Cookie "remember me" (30 jours) */
    if ($remember) {
        $token = bin2hex(random_bytes(32));
        setcookie('remember_token', $token, time() + 30 * 86400, '/', '', false, true);
    }

    header('Location: ' . $redirect_to);
    exit();
}


/* ════════════════════════════════════════════════════════════
   ACTION : REGISTER
════════════════════════════════════════════════════════════ */
if ($action === 'register') {

    $name     = trim($_POST['name']             ?? '');
    $email    = trim(strtolower($_POST['email'] ?? ''));
    $phone    = trim($_POST['phone']            ?? '');
    $password = $_POST['password']              ?? '';
    $confirm  = $_POST['password_confirm']      ?? '';
    $role     = in_array($_POST['role'] ?? '', ['client', 'vendor']) ? $_POST['role'] : 'client';
    $terms    = isset($_POST['terms']);

    /* Sauvegarde pour re-remplissage en cas d'erreur */
    $_SESSION['auth_old'] = ['name' => $name, 'email' => $email, 'phone' => $phone];

    /* ── Validations ── */
    $errors = [];

    if (empty($name) || strlen($name) < 2) {
        $errors[] = 'Le nom doit contenir au moins 2 caractères.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Adresse e-mail invalide.';
    }
    if (strlen($password) < 8) {
        $errors[] = 'Le mot de passe doit contenir au moins 8 caractères.';
    }
    if ($password !== $confirm) {
        $errors[] = 'Les mots de passe ne correspondent pas.';
    }
    if (!$terms) {
        $errors[] = 'Veuillez accepter les conditions d\'utilisation.';
    }

    /* Vérifie si l'email est déjà utilisé */
    $accounts = $_SESSION['registered_accounts'] ?? [];
    foreach ($accounts as $acc) {
        if (strtolower($acc['email']) === $email) {
            $errors[] = 'Cette adresse e-mail est déjà utilisée.';
            break;
        }
    }

    if (!empty($errors)) {
        $_SESSION['auth_error'] = implode(' ', $errors);
        header('Location: ../Auth/Register.php?redirect_to=' . urlencode($redirect_to));
        exit();
    }

    /*
     * TODO (phase DB) : remplacer par INSERT INTO users ...
     * $hash = password_hash($password, PASSWORD_BCRYPT);
     * $stmt = $pdo->prepare("INSERT INTO users (name, email, phone, password, role) VALUES (?, ?, ?, ?, ?)");
     * $stmt->execute([$name, $email, $phone, $hash, $role]);
     * $userId = $pdo->lastInsertId();
     */

    /* Crée le nouveau compte en mémoire (session) */
    $newId   = count($accounts) + 1;
    $newUser = [
        'id'            => $newId,
        'name'          => $name,
        'email'         => $email,
        'phone'         => $phone,
        'role'          => $role,
        'avatar'        => '',
        'since'         => date('Y'),
        'password_hash' => password_hash($password, PASSWORD_BCRYPT),
    ];

    $_SESSION['registered_accounts'][] = $newUser;

    /* Connexion automatique après inscription */
    session_regenerate_id(true);
    $_SESSION['user_id']     = $newUser['id'];
    $_SESSION['user_name']   = $newUser['name'];
    $_SESSION['user_email']  = $newUser['email'];
    $_SESSION['user_role']   = $newUser['role'];
    $_SESSION['user_phone']  = $newUser['phone'];
    $_SESSION['user_avatar'] = '';
    $_SESSION['user_since']  = $newUser['since'];

    unset($_SESSION['auth_old']);

    /* Message de bienvenue */
    $_SESSION['auth_success'] = 'Bienvenue ' . htmlspecialchars($name) . ' ! Votre compte a été créé.';

    header('Location: ' . $redirect_to);
    exit();
}


/* ════════════════════════════════════════════════════════════
   ACTION : LOGOUT (GET ou POST)
════════════════════════════════════════════════════════════ */
if ($action === 'logout' || isset($_GET['logout'])) {
    /* Conserve uniquement les comptes enregistrés */
    $accounts = $_SESSION['registered_accounts'] ?? [];
    session_destroy();
    session_start();
    $_SESSION['registered_accounts'] = $accounts;

    setcookie('remember_token', '', time() - 3600, '/');
    header('Location: ../Auth/Login.php');
    exit();
}

/* Action inconnue → retour login */
header('Location: ../Auth/Login.php');
exit();

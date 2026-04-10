<?php
// Sécurité : empêche l'accès direct au fichier include
if (!defined('APP_ROOT')) {
    define('APP_ROOT', dirname(__DIR__));
}

// Récupère le nom de la page courante pour le menu actif
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Marketplace artisanale — Découvrez des créateurs locaux uniques.">
    <title>E-tool</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./navbar.css">

    <!-- CSS Global -->
    <link rel="stylesheet" href="../assets/Css/global.css">

    <!--
        navbar.css : reproduit en CSS pur toutes les classes Tailwind
        du header. Même rendu en ligne ET hors connexion.
    -->
    <link rel="stylesheet" href="../assets/Css/Head.css/navbar.css">

    <!--
        fix-overflow.css : corrige l'espace blanc à droite sur mobile.
        Appliqué sur toutes les pages via ce header.
    -->
    <link rel="stylesheet" href="../assets/Css/fix-overflow.css">

    <!-- Tailwind CDN — complément si connexion disponible -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Extension de la palette Tailwind avec les couleurs du projet
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand-orange': '#F97316',
                        'brand-dark'  : '#111111',
                    },
                    fontFamily: {
                        serif: ['Playfair Display', 'Georgia', 'serif'],
                        sans : ['DM Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-[#F5F3EE] font-sans" style="background:#F5F3EE; font-family:'DM Sans',sans-serif; overflow-x:hidden; width:100%; max-width:100%;">

<!-- NAVBAR
     - Fixe en haut, fond blanc avec légère ombre au scroll
     - Logo à gauche, navigation au centre, user à droite -->
<nav id="main-nav" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300"
     style="background: rgba(255,255,255,0.95); backdrop-filter: blur(12px); border-bottom: 1px solid #E5E1D8;">

    <div class="max-w-[1200px] mx-auto px-6 h-16 flex items-center justify-between">

        <!-- Logo -->
        <a href="../Int_Clients/Home.php" class="flex items-center gap-2 no-underline group logo-link" style="display:flex;align-items:center;gap:.5rem;text-decoration:none;flex-shrink:0;">
            <span style="
                width:2rem; height:2rem;
                background: #F97316;
                border-radius: 8px;
                display:flex; align-items:center; justify-content:center;
                font-family:'Playfair Display',serif;
                font-weight:900; color:#fff; font-size:1rem;
                transition: transform .2s;
            " class="logo-box group-hover:scale-110" style="overflow:hidden;flex-shrink:0;"><img src="../Img/Dynamic 'E' Logo for E-tool Project.png" alt="Logo E-tool"></span>
            <span class="logo-text" style="font-family:'Playfair Display',serif; font-weight:900; font-size:1.2rem; color:#111111; white-space:nowrap;">
                E<span style="color:#F97316;">-</span><span class="logo-text" style="font-family:'Playfair Display',serif; font-weight:900; font-size:1.2rem; color:#111111; white-space:nowrap;">tool</span>
            </span>
        </a>

        <!-- Navigation principale (masqué sur mobile) -->
        <ul class="hidden md:flex items-center gap-8 list-none m-0 p-0">
            <?php
            // Tableau des liens de navigation [label => fichier]
            $nav_links = [
                'Accueil'  => 'Home.php',
                'Boutiques'=> 'Shop-detail.php',
                'Tendances'=> 'Trends.php',
                'Contact'  => 'Contact.php',
            ];
            foreach ($nav_links as $label => $file):
                $is_active = ($current_page === $file);
            ?>
            <li>
                <a href="../Pages/<?php echo $file; ?>"
                   style="
                       font-size:.9rem; font-weight:600;
                       text-decoration:none;
                       color: <?php echo $is_active ? '#F97316' : '#374151'; ?>;
                       position:relative; padding-bottom:2px;
                       transition: color .2s;
                   "
                   class="nav-link <?php echo $is_active ? 'active' : ''; ?>">
                    <?php echo $label; ?>
                    <?php if ($is_active): ?>
                    <span style="
                        position:absolute; bottom:-2px; left:0; right:0;
                        height:2px; background:#F97316; border-radius:2px;
                    "></span>
                    <?php endif; ?>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>

        <!-- Zone utilisateur + burger -->
        <div class="flex items-center gap-4 user-zone" style="display:flex;align-items:center;gap:1rem;">

            <!-- Icône panier -->
            <button class="relative p-2 rounded-lg hover:bg-gray-100 transition cart-btn" title="Panier" aria-label="Panier">
                <svg width="20" height="20" fill="none" stroke="#374151" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/>
                    <path d="M16 10a4 4 0 01-8 0"/>
                </svg>
                <!-- Badge compteur panier -->
                <span id="cart-count" style="
                    position:absolute; top:4px; right:4px;
                    width:16px; height:16px;
                    background:#F97316; color:#fff;
                    border-radius:50%; font-size:0.6rem;
                    font-weight:700; display:none;
                    align-items:center; justify-content:center;
                ">0</span>
            </button>

            <!-- Avatar utilisateur -->
            <div class="relative" id="user-menu-wrap">
                <button id="user-menu-btn"
                        class="flex items-center gap-2 p-1.5 rounded-lg hover:bg-gray-100 transition"
                        aria-haspopup="true" aria-expanded="false">
                    <?php if (!empty($_SESSION['user_avatar'])): ?>
                        <img src="<?php echo htmlspecialchars($_SESSION['user_avatar']); ?>"
                             alt="Avatar" class="w-8 h-8 rounded-full object-cover">
                    <?php else: ?>
                        <!-- Avatar initiale générée -->
                        <span style="
                            width:2rem; height:2rem;
                            background: linear-gradient(135deg,#F97316,#C2570B);
                            border-radius:50%; display:flex;
                            align-items:center; justify-content:center;
                            color:#fff; font-weight:700; font-size:.85rem;
                        ">
                            <?php echo strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)); ?>
                        </span>
                    <?php endif; ?>
                    <span class="hidden md:block text-sm font-semibold text-gray-700 user-name" style="font-size:.875rem;font-weight:600;color:#374151;white-space:nowrap;">
                        <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Mon compte'); ?>
                    </span>
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <polyline points="6 9 12 15 18 9"/>
                    </svg>
                </button>

                <!-- Dropdown menu utilisateur -->
                <div id="user-dropdown" style="
                    display:none; position:absolute; right:0; top:calc(100% + 8px);
                    background:#fff; border:1px solid #E5E1D8;
                    border-radius:12px; box-shadow:0 8px 32px rgba(0,0,0,.1);
                    min-width:180px; overflow:hidden; z-index:100;
                ">
                    <a href="../Pages/Profile.php" style="display:flex;align-items:center;gap:.6rem;padding:.85rem 1.2rem;font-size:.875rem;color:#374151;text-decoration:none;transition:background .15s;" class="dropdown-item">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        Mon profil
                    </a>
                    <a href="../Pages/Orders.php" style="display:flex;align-items:center;gap:.6rem;padding:.85rem 1.2rem;font-size:.875rem;color:#374151;text-decoration:none;transition:background .15s;" class="dropdown-item">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1" ry="1"/></svg>
                        Mes commandes
                    </a>
                    <hr style="border:none;border-top:1px solid #E5E1D8;margin:0;">
                    <a href="../Logout.php" style="display:flex;align-items:center;gap:.6rem;padding:.85rem 1.2rem;font-size:.875rem;color:#DC2626;text-decoration:none;transition:background .15s;" class="dropdown-item">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                        Déconnexion
                    </a>
                </div>
            </div>

            <!-- Bouton burger mobile -->
            <button id="burger-btn"
                    class="md:hidden p-2 rounded-lg hover:bg-gray-100 transition"
                    aria-label="Menu" aria-expanded="false">
                <svg width="22" height="22" fill="none" stroke="#374151" stroke-width="2" viewBox="0 0 24 24">
                    <line x1="3" y1="6" x2="21" y2="6"/>
                    <line x1="3" y1="12" x2="21" y2="12"/>
                    <line x1="3" y1="18" x2="21" y2="18"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Menu mobile déroulant -->
    <div id="mobile-menu" style="display:none; border-top:1px solid #E5E1D8; background:#fff;" class="md:hidden">
        <ul class="list-none m-0 p-4 flex flex-col gap-1">
            <?php foreach ($nav_links as $label => $file): ?>
            <li>
                <a href="../Pages/<?php echo $file; ?>"
                   style="display:block; padding:.75rem 1rem; border-radius:8px; font-weight:600; font-size:.95rem;
                          text-decoration:none; color:<?php echo ($current_page===$file)?'#F97316':'#374151'; ?>;
                          background:<?php echo ($current_page===$file)?'rgba(249,115,22,.08)':'transparent'; ?>;
                          transition:background .15s;">
                    <?php echo $label; ?>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</nav>

<!-- Espaceur pour compenser la navbar fixe -->
<div style="height:64px;"></div>
<script src="../assets/Js/Head.js"></script>
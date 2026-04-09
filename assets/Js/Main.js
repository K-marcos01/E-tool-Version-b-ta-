/*
 * LOGIQUE FRONT-END
 * Gère les interactions utilisateur et les animations dynamiques
 */

document.addEventListener('DOMContentLoaded', () => {
    
    // 1. Gestion de la transparence de la Navbar au scroll
    const navbar = document.querySelector('nav');
    
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            navbar.classList.add('shadow-xl', 'bg-opacity-95');
        } else {
            navbar.classList.remove('shadow-xl', 'bg-opacity-95');
        }
    });

    // 2. Initialisation des boutons (exemple d'alerte ou redirection)
    const shopBtn = document.querySelector('.btn-shop');
    if(shopBtn) {
        shopBtn.addEventListener('click', () => {
            console.log("Navigation vers la boutique...");
        });
    }
});

/**
 * GESTION DU MENU BURGER ET SIDEBAR
 */
document.addEventListener('DOMContentLoaded', () => {
    const burger = document.getElementById('burger-menu');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    const closeBtn = document.getElementById('close-sidebar');

    // Fonction pour basculer la sidebar
    const toggleSidebar = () => {
        sidebar.classList.toggle('active');
        overlay.classList.toggle('active');
    };

    if(burger) burger.addEventListener('click', toggleSidebar);
    if(overlay) overlay.addEventListener('click', toggleSidebar);
    if(closeBtn) closeBtn.addEventListener('click', toggleSidebar);
});

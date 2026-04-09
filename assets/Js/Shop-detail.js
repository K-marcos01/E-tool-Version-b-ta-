/**
 * Shop-detail.js — Comportements interactifs de la page boutique
 * ─────────────────────────────────────────────────────────────
 * Fonctionnalités :
 *   1. Scroll reveal (IntersectionObserver partagé)
 *   2. Overlay produit — tap/click sur mobile (hover géré en CSS)
 *   3. Filtres par catégorie (boutons dans la barre de filtres)
 *   4. Recherche en temps réel dans les produits
 *   5. Tri des produits (prix, nom, popularité)
 *   6. Ajout au panier (simulation + toast notification)
 *   7. Mise à jour du compteur de panier dans la navbar
 * ─────────────────────────────────────────────────────────────
 * Dépendances : global.css (.reveal, .is-open, .toast.show)
 * ─────────────────────────────────────────────────────────────
 */

(function () {
    'use strict';

    /* ════════════════════════════════════════════════════════
       1. SCROLL REVEAL
       ─────────────────────────────────────────────────────
       Observe tous les éléments .reveal et leur ajoute la
       classe .visible quand ils entrent dans le viewport.
       Utilise threshold=0.1 pour déclencher tôt (10% visible).
    ════════════════════════════════════════════════════════ */
    function initScrollReveal() {
        const revealElements = document.querySelectorAll('.reveal');
        if (!revealElements.length) return;

        const observer = new IntersectionObserver(
            function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        observer.unobserve(entry.target); // Déclenche une seule fois
                    }
                });
            },
            {
                threshold: 0.10,
                rootMargin: '0px 0px -30px 0px', // Déclenche légèrement avant d'entrer
            }
        );

        revealElements.forEach(function (el) {
            observer.observe(el);
        });
    }


    /* ════════════════════════════════════════════════════════
       2. OVERLAY PRODUIT — MOBILE (TAP/CLICK)
       ─────────────────────────────────────────────────────
       Sur les écrans tactiles (hover:none), l'overlay CSS ne
       s'affiche pas au :hover. On gère donc l'affichage via
       la classe .is-open au tap.

       Logique :
       - Premier tap sur une card → ouvre l'overlay (.is-open)
       - Deuxième tap sur la même card (ou tap ailleurs) → ferme
       - Le bouton "Ajouter au panier" dans l'overlay est
         intercepté séparément (voir section 6)
    ════════════════════════════════════════════════════════ */
    function initMobileOverlay() {
        // Détecte si le device est tactile
        const isTouchDevice = window.matchMedia('(hover: none)').matches;
        if (!isTouchDevice) return; // Sur desktop, l'overlay est géré en CSS

        const cards = document.querySelectorAll('.product-card');
        let openCard = null; // Référence à la card actuellement ouverte

        cards.forEach(function (card) {
            card.addEventListener('click', function (e) {
                // Si le clic vient du bouton "Ajouter au panier" → laisser
                // la section 6 (addToCart) gérer l'événement
                if (e.target.closest('.overlay__add-btn')) return;

                if (card === openCard) {
                    // Deuxième tap → ferme
                    card.classList.remove('is-open');
                    openCard = null;
                } else {
                    // Ferme la précédente card ouverte
                    if (openCard) openCard.classList.remove('is-open');
                    // Ouvre la nouvelle
                    card.classList.add('is-open');
                    openCard = card;
                }
            });
        });

        // Tap en dehors d'une card → ferme toutes
        document.addEventListener('click', function (e) {
            if (!e.target.closest('.product-card') && openCard) {
                openCard.classList.remove('is-open');
                openCard = null;
            }
        });
    }


    /* ════════════════════════════════════════════════════════
       3. FILTRES PAR CATÉGORIE
       ─────────────────────────────────────────────────────
       Chaque bouton .filter-btn a un attribut data-category.
       Les cards .product-item ont un attribut data-category.
       Quand on clique sur un filtre :
         - Le bouton devient .active
         - On cache les produits qui ne correspondent pas
         - On met à jour le compteur affiché
    ════════════════════════════════════════════════════════ */
    function initFilters() {
        const filterBtns = document.querySelectorAll('.filter-btn');
        if (!filterBtns.length) return;

        filterBtns.forEach(function (btn) {
            btn.addEventListener('click', function () {
                // Désactive tous les boutons
                filterBtns.forEach(function (b) { b.classList.remove('active'); });
                // Active le bouton cliqué
                btn.classList.add('active');

                const selectedCat = btn.dataset.category || 'all';
                filterProducts(selectedCat);
            });
        });
    }

    /**
     * Filtre les produits selon la catégorie et/ou la recherche en cours.
     * @param {string} category - 'all' ou slug de catégorie
     */
    function filterProducts(category) {
        const items    = document.querySelectorAll('.product-item');
        const query    = (document.getElementById('product-search')?.value || '').toLowerCase().trim();
        let   visible  = 0;

        items.forEach(function (item) {
            const itemCat  = item.dataset.category || '';
            const itemName = (item.dataset.name || '').toLowerCase();
            const itemDesc = (item.dataset.desc || '').toLowerCase();

            const matchCat   = (category === 'all') || (itemCat === category);
            const matchQuery = !query || itemName.includes(query) || itemDesc.includes(query);

            if (matchCat && matchQuery) {
                item.style.display = '';
                visible++;
            } else {
                item.style.display = 'none';
            }
        });

        // Met à jour le compteur "X produits"
        updateProductCount(visible);

        // Affiche le message "aucun résultat" si besoin
        toggleEmptyState(visible === 0);
    }

    /** Met à jour l'affichage du nombre de produits visibles */
    function updateProductCount(count) {
        const counter = document.getElementById('products-count');
        if (counter) {
            counter.innerHTML = '<strong>' + count + '</strong> produit' + (count > 1 ? 's' : '');
        }
    }

    /** Affiche ou masque le message "aucun produit trouvé" */
    function toggleEmptyState(show) {
        const empty = document.getElementById('products-empty');
        if (empty) {
            empty.style.display = show ? 'block' : 'none';
        }
    }


    /* ════════════════════════════════════════════════════════
       4. RECHERCHE EN TEMPS RÉEL
       ─────────────────────────────────────────────────────
       Écoute l'input de recherche #product-search.
       À chaque frappe, relance filterProducts() avec la
       catégorie active courante.
    ════════════════════════════════════════════════════════ */
    function initSearch() {
        const searchInput = document.getElementById('product-search');
        if (!searchInput) return;

        // Délai de debounce pour ne pas filtrer à chaque touche
        let debounceTimer;

        searchInput.addEventListener('input', function () {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(function () {
                // Récupère la catégorie actuellement active
                const activeBtn = document.querySelector('.filter-btn.active');
                const activeCat = activeBtn ? (activeBtn.dataset.category || 'all') : 'all';
                filterProducts(activeCat);
            }, 200); // 200ms de délai
        });
    }


    /* ════════════════════════════════════════════════════════
       5. TRI DES PRODUITS
       ─────────────────────────────────────────────────────
       Le sélecteur #sort-select permet de trier les produits
       selon : prix croissant, prix décroissant, nom A→Z,
       popularité (note).
       On réordonne physiquement les éléments dans la grille.
    ════════════════════════════════════════════════════════ */
    function initSort() {
        const sortSelect = document.getElementById('sort-select');
        if (!sortSelect) return;

        sortSelect.addEventListener('change', function () {
            const value = this.value;
            const grid  = document.getElementById('products-grid');
            if (!grid) return;

            // Récupère tous les items (y compris ceux masqués par filtre)
            const items = Array.from(grid.querySelectorAll('.product-item'));

            items.sort(function (a, b) {
                switch (value) {
                    case 'price-asc':
                        return parseFloat(a.dataset.price || 0) - parseFloat(b.dataset.price || 0);
                    case 'price-desc':
                        return parseFloat(b.dataset.price || 0) - parseFloat(a.dataset.price || 0);
                    case 'name-asc':
                        return (a.dataset.name || '').localeCompare(b.dataset.name || '', 'fr');
                    case 'rating':
                        return parseFloat(b.dataset.rating || 0) - parseFloat(a.dataset.rating || 0);
                    default:
                        // 'default' : ordre original (data-order)
                        return parseInt(a.dataset.order || 0) - parseInt(b.dataset.order || 0);
                }
            });

            // Réinjecte les items dans le nouvel ordre
            items.forEach(function (item) {
                grid.appendChild(item);
            });
        });
    }


    /* ════════════════════════════════════════════════════════
       6. AJOUT AU PANIER
       ─────────────────────────────────────────────────────
       Écoute les clics sur les boutons .overlay__add-btn.
       Envoie une requête AJAX à add-to-cart.php.
       Met à jour le badge du panier dans la navbar.
       Affiche un toast de confirmation.

       Données attendues sur le bouton :
         data-product-id  : ID du produit
         data-product-name: Nom du produit (pour le toast)
    ════════════════════════════════════════════════════════ */
    function initAddToCart() {
        // Délégation d'événement depuis la grille (gère les éléments futurs aussi)
        const grid = document.getElementById('products-grid');
        if (!grid) return;

        grid.addEventListener('click', function (e) {
            const btn = e.target.closest('.overlay__add-btn');
            if (!btn || btn.disabled) return;

            e.stopPropagation(); // Empêche l'ouverture/fermeture de la card mobile

            const productId   = btn.dataset.productId;
            const productName = btn.dataset.productName || 'Ce produit';

            // Désactive le bouton pendant la requête (évite double-clic)
            btn.disabled = true;
            btn.innerHTML = '<span>Ajout...</span>';

            // ── Appel AJAX vers le backend ──
            fetch('../Actions/add-to-cart.php', {
                method : 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body   : 'product_id=' + encodeURIComponent(productId) + '&quantity=1',
            })
            .then(function (response) {
                if (!response.ok) throw new Error('Réponse serveur invalide');
                return response.json();
            })
            .then(function (data) {
                if (data.success) {
                    // Met à jour le compteur dans la navbar
                    updateCartBadge(data.cart_count);
                    // Affiche le toast
                    showToast('✓ ' + productName + ' ajouté au panier');
                    // Rétablit le bouton
                    btn.disabled = false;
                    btn.innerHTML = '<svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4"/></svg> Ajouté !';
                    setTimeout(function () {
                        btn.innerHTML = '<svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg> Ajouter au panier';
                    }, 2000);
                } else {
                    showToast('Erreur : ' + (data.message || 'Impossible d\'ajouter le produit'), 'error');
                    btn.disabled = false;
                    btn.textContent = 'Réessayer';
                }
            })
            .catch(function (err) {
                console.error('Erreur panier:', err);
                showToast('Erreur de connexion. Veuillez réessayer.', 'error');
                btn.disabled = false;
                btn.textContent = 'Ajouter au panier';
            });
        });
    }

    /**
     * Met à jour le badge compteur du panier dans la navbar.
     * @param {number} count - Nouveau nombre d'articles
     */
    function updateCartBadge(count) {
        const badge = document.getElementById('cart-count');
        if (!badge) return;

        badge.textContent = count;
        badge.style.display = count > 0 ? 'flex' : 'none';

        // Micro-animation de rebond
        badge.style.transform = 'scale(1.4)';
        setTimeout(function () { badge.style.transform = 'scale(1)'; }, 200);
    }


    /* ════════════════════════════════════════════════════════
       7. TOAST NOTIFICATION
       ─────────────────────────────────────────────────────
       Crée un élément toast (s'il n'existe pas encore),
       l'affiche avec le message donné, puis le masque après
       3 secondes.
    ════════════════════════════════════════════════════════ */

    /**
     * Affiche un toast de notification en bas de l'écran.
     * @param {string} message - Texte à afficher
     * @param {string} [type='success'] - 'success' ou 'error'
     */
    function showToast(message, type) {
        type = type || 'success';

        // Crée le toast s'il n'existe pas déjà dans le DOM
        let toast = document.getElementById('cart-toast');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'cart-toast';
            toast.className = 'toast';
            toast.innerHTML = '<span class="toast__icon"><svg width="8" height="8" fill="white" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg></span><span id="toast-msg"></span>';
            document.body.appendChild(toast);
        }

        // Couleur selon le type
        toast.style.background = (type === 'error') ? '#DC2626' : 'var(--dark)';

        document.getElementById('toast-msg').textContent = message;
        toast.classList.add('show');

        // Masque le toast après 3 secondes
        clearTimeout(toast._hideTimer);
        toast._hideTimer = setTimeout(function () {
            toast.classList.remove('show');
        }, 3000);
    }


    /* ════════════════════════════════════════════════════════
       INITIALISATION
       ─────────────────────────────────────────────────────
       Démarre toutes les fonctionnalités après chargement du DOM
    ════════════════════════════════════════════════════════ */
    document.addEventListener('DOMContentLoaded', function () {
        initScrollReveal();
        initMobileOverlay();
        initFilters();
        initSearch();
        initSort();
        initAddToCart();

        console.log('[Shop-detail.js] Initialisé ✓');
    });

})(); // IIFE — évite la pollution du scope global

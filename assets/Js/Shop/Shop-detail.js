/**
 * Shop-detail.js — Comportements interactifs de la page boutique
 * ─────────────────────────────────────────────────────────────
 * 1. Scroll reveal
 * 2. Overlay produit mobile (tap)
 * 3. Filtres par catégorie
 * 4. Recherche en temps réel
 * 5. Tri des produits
 * 6. Ajout au panier (AJAX) — avec gestion redirection si non connecté
 * 7. Toast notification
 * ─────────────────────────────────────────────────────────────
 */

(function () {
    'use strict';

    /* ════════════════════════════════════════════════════════
       1. SCROLL REVEAL
    ════════════════════════════════════════════════════════ */
    function initScrollReveal() {
        var els = document.querySelectorAll('.reveal');
        if (!els.length) return;
        var obs = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    obs.unobserve(entry.target);
                }
            });
        }, { threshold: 0.10, rootMargin: '0px 0px -30px 0px' });
        els.forEach(function (el) { obs.observe(el); });
    }


    /* ════════════════════════════════════════════════════════
       2. OVERLAY MOBILE (tap)
    ════════════════════════════════════════════════════════ */
    function initMobileOverlay() {
        var isTouch = window.matchMedia('(hover: none)').matches;
        if (!isTouch) return;

        var cards   = document.querySelectorAll('.product-card');
        var openCard = null;

        cards.forEach(function (card) {
            card.addEventListener('click', function (e) {
                if (e.target.closest('.overlay__add-btn') ||
                    e.target.closest('.overlay__btn-register') ||
                    e.target.closest('.overlay__btn-login')) return;

                if (card === openCard) {
                    card.classList.remove('is-open');
                    openCard = null;
                } else {
                    if (openCard) openCard.classList.remove('is-open');
                    card.classList.add('is-open');
                    openCard = card;
                }
            });
        });

        document.addEventListener('click', function (e) {
            if (!e.target.closest('.product-card') && openCard) {
                openCard.classList.remove('is-open');
                openCard = null;
            }
        });
    }


    /* ════════════════════════════════════════════════════════
       3. FILTRES PAR CATÉGORIE
    ════════════════════════════════════════════════════════ */
    function initFilters() {
        var btns = document.querySelectorAll('.filter-btn');
        if (!btns.length) return;
        btns.forEach(function (btn) {
            btn.addEventListener('click', function () {
                btns.forEach(function (b) { b.classList.remove('active'); });
                btn.classList.add('active');
                filterProducts(btn.dataset.category || 'all');
            });
        });
    }

    function filterProducts(category) {
        var items = document.querySelectorAll('.product-item');
        var searchEl = document.getElementById('product-search');
        var query = searchEl ? searchEl.value.toLowerCase().trim() : '';
        var visible = 0;

        items.forEach(function (item) {
            var matchCat   = (category === 'all') || (item.dataset.category === category);
            var matchQuery = !query ||
                             (item.dataset.name || '').includes(query) ||
                             (item.dataset.desc || '').includes(query);
            item.style.display = (matchCat && matchQuery) ? '' : 'none';
            if (matchCat && matchQuery) visible++;
        });

        var counter = document.getElementById('products-count');
        if (counter) {
            counter.innerHTML = '<strong>' + visible + '</strong> produit' + (visible > 1 ? 's' : '');
        }
        var empty = document.getElementById('products-empty');
        if (empty) empty.style.display = (visible === 0) ? 'block' : 'none';
    }


    /* ════════════════════════════════════════════════════════
       4. RECHERCHE EN TEMPS RÉEL
    ════════════════════════════════════════════════════════ */
    function initSearch() {
        var input = document.getElementById('product-search');
        if (!input) return;
        var timer;
        input.addEventListener('input', function () {
            clearTimeout(timer);
            timer = setTimeout(function () {
                var activeBtn = document.querySelector('.filter-btn.active');
                var cat = activeBtn ? (activeBtn.dataset.category || 'all') : 'all';
                filterProducts(cat);
            }, 200);
        });
    }


    /* ════════════════════════════════════════════════════════
       5. TRI
    ════════════════════════════════════════════════════════ */
    function initSort() {
        var sel = document.getElementById('sort-select');
        if (!sel) return;
        sel.addEventListener('change', function () {
            var grid  = document.getElementById('products-grid');
            if (!grid) return;
            var items = Array.from(grid.querySelectorAll('.product-item'));
            items.sort(function (a, b) {
                switch (sel.value) {
                    case 'price-asc':  return +a.dataset.price - +b.dataset.price;
                    case 'price-desc': return +b.dataset.price - +a.dataset.price;
                    case 'name-asc':   return (a.dataset.name||'').localeCompare(b.dataset.name||'', 'fr');
                    case 'rating':     return +b.dataset.rating - +a.dataset.rating;
                    default:           return +a.dataset.order - +b.dataset.order;
                }
            });
            items.forEach(function (item) { grid.appendChild(item); });
        });
    }


    /* ════════════════════════════════════════════════════════
       6. AJOUT AU PANIER
       ─────────────────────────────────────────────────────
       Comportement selon la réponse JSON du serveur :
         data.redirect  → non connecté → redirige vers Register
         data.success   → ajouté → badge + toast vert
         data.message   → erreur → toast rouge
       En cas d'erreur réseau → redirige vers Register (URL lue
       depuis l'attribut data-register-url sur la grille).
    ════════════════════════════════════════════════════════ */
    function initAddToCart() {
        var grid = document.getElementById('products-grid');
        if (!grid) return;

        grid.addEventListener('click', function (e) {
            var btn = e.target.closest('.overlay__add-btn');
            if (!btn || btn.disabled) return;

            e.stopPropagation();

            var productId   = btn.dataset.productId;
            var productName = btn.dataset.productName || 'Ce produit';

            btn.disabled  = true;
            btn.innerHTML = 'Ajout en cours…';

            fetch('../Actions/add-to-cart.php', {
                method : 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body   : 'product_id=' + encodeURIComponent(productId) + '&quantity=1',
            })
            .then(function (response) {
                return response.json();
            })
            .then(function (data) {

                /* Non connecté : le serveur renvoie une URL de redirection */
                if (data.redirect) {
                    window.location.href = data.redirect;
                    return;
                }

                /* Succès */
                if (data.success) {
                    updateCartBadge(data.cart_count);
                    showToast(productName + ' ajouté au panier ✓', 'success');
                    btn.disabled  = false;
                    btn.innerHTML = '✓ Ajouté !';
                    setTimeout(function () {
                        btn.disabled  = false;
                        btn.innerHTML = '<svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">'
                            + '<path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17'
                            + 'm0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg> Ajouter au panier';
                    }, 2200);
                    return;
                }

                /* Erreur avec message */
                showToast(data.message || 'Une erreur est survenue.', 'error');
                btn.disabled  = false;
                btn.innerHTML = 'Réessayer';
            })
            .catch(function () {
                /*
                 * Erreur réseau : impossible de joindre le serveur.
                 * On redirige vers Register (URL stockée sur la grille
                 * via data-register-url, injecté par PHP dans le HTML).
                 */
                var registerUrl = grid.dataset.registerUrl;
                if (registerUrl) {
                    window.location.href = registerUrl;
                } else {
                    showToast('Connexion impossible. Vérifiez votre réseau.', 'error');
                    btn.disabled  = false;
                    btn.innerHTML = 'Réessayer';
                }
            });
        });
    }


    /* ════════════════════════════════════════════════════════
       7. BADGE PANIER + TOAST
    ════════════════════════════════════════════════════════ */
    function updateCartBadge(count) {
        var badge = document.getElementById('cart-count');
        if (!badge) return;
        badge.textContent   = count;
        badge.style.display = count > 0 ? 'flex' : 'none';
        badge.style.transform = 'scale(1.5)';
        setTimeout(function () { badge.style.transform = 'scale(1)'; }, 250);
    }

    function showToast(message, type) {
        type = type || 'success';
        var toast = document.getElementById('shop-toast');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'shop-toast';
            toast.style.cssText = [
                'position:fixed', 'bottom:2rem', 'left:50%',
                'transform:translateX(-50%) translateY(20px)',
                'border-radius:12px', 'padding:.85rem 1.5rem',
                'font-family:inherit', 'font-size:.9rem', 'font-weight:600',
                'color:#fff', 'z-index:9999',
                'display:flex', 'align-items:center', 'gap:.6rem',
                'white-space:nowrap', 'opacity:0', 'pointer-events:none',
                'transition:opacity .3s, transform .3s',
                'box-shadow:0 8px 32px rgba(0,0,0,.25)',
            ].join(';');
            document.body.appendChild(toast);
        }
        toast.style.background    = type === 'error' ? '#DC2626' : '#111111';
        toast.textContent         = message;
        toast.style.opacity       = '1';
        toast.style.transform     = 'translateX(-50%) translateY(0)';
        toast.style.pointerEvents = 'none';
        clearTimeout(toast._timer);
        toast._timer = setTimeout(function () {
            toast.style.opacity   = '0';
            toast.style.transform = 'translateX(-50%) translateY(20px)';
        }, 3200);
    }


    /* ════════════════════════════════════════════════════════
       INIT
    ════════════════════════════════════════════════════════ */
    document.addEventListener('DOMContentLoaded', function () {
        initScrollReveal();
        initMobileOverlay();
        initFilters();
        initSearch();
        initSort();
        initAddToCart();
    });

})();

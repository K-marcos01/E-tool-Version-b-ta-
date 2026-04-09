(function() {
    /* ── Dropdown utilisateur ── */
    const btn  = document.getElementById('user-menu-btn');
    const drop = document.getElementById('user-dropdown');
    if (btn && drop) {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const open = drop.style.display === 'block';
            drop.style.display = open ? 'none' : 'block';
            btn.setAttribute('aria-expanded', String(!open));
        });
        document.addEventListener('click', () => { drop.style.display = 'none'; });
    }

    /* ── Burger mobile ── */
    const burger = document.getElementById('burger-btn');
    const mMenu  = document.getElementById('mobile-menu');
    if (burger && mMenu) {
        burger.addEventListener('click', function() {
            const open = mMenu.style.display === 'block';
            mMenu.style.display = open ? 'none' : 'block';
            burger.setAttribute('aria-expanded', String(!open));
        });
    }

    /* ── Navbar shadow au scroll ── */
    const nav = document.getElementById('main-nav');
    window.addEventListener('scroll', function() {
        if (window.scrollY > 10) {
            nav.style.boxShadow = '0 2px 20px rgba(0,0,0,.08)';
        } else {
            nav.style.boxShadow = 'none';
        }
    }, { passive: true });

    /* ── Hover sur les items dropdown ── */
    document.querySelectorAll('.dropdown-item').forEach(function(el) {
        el.addEventListener('mouseenter', function() { this.style.background = '#F5F3EE'; });
        el.addEventListener('mouseleave', function() { this.style.background = 'transparent'; });
    });
})();
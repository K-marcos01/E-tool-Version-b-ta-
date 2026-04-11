/**
 * profile.js — Onglets profil + toggle mot de passe
 */
(function () {
    'use strict';

    /* ── Onglets ── */
    var tabs   = document.querySelectorAll('.profile-tab');
    var panels = document.querySelectorAll('.profile-panel');

    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            var target = tab.dataset.tab;
            tabs.forEach(function (t) { t.classList.remove('active'); t.setAttribute('aria-selected','false'); });
            panels.forEach(function (p) { p.classList.remove('active'); });
            tab.classList.add('active'); tab.setAttribute('aria-selected','true');
            var panel = document.getElementById('tab-' + target);
            if (panel) panel.classList.add('active');
        });
    });

    /* ── Lien depuis URL (#orders, #messages) ── */
    var hash = window.location.hash.replace('#','');
    if (hash) {
        var triggerTab = document.querySelector('[data-tab="' + hash + '"]');
        if (triggerTab) triggerTab.click();
    }

    /* ── Toggle voir / cacher mot de passe ── */
    document.querySelectorAll('.form-toggle-pwd').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var t = document.getElementById(btn.dataset.target);
            if (t) t.type = t.type === 'password' ? 'text' : 'password';
        });
    });

    /* ── Reveal au scroll ── */
    var reveals = document.querySelectorAll('.reveal');
    if (reveals.length) {
        var obs = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (e.isIntersecting) { e.target.classList.add('visible'); obs.unobserve(e.target); }
            });
        }, { threshold: 0.1 });
        reveals.forEach(function (el) { obs.observe(el); });
    }
})();

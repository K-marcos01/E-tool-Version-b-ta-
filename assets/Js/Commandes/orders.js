/**
 * orders.js — Comportements de Orders.php
 * Reveal au scroll uniquement (le reste est PHP/CSS).
 */
(function () {
    'use strict';
    var reveals = document.querySelectorAll('.reveal');
    if (!reveals.length) return;
    var obs = new IntersectionObserver(function (entries) {
        entries.forEach(function (e) {
            if (e.isIntersecting) { e.target.classList.add('visible'); obs.unobserve(e.target); }
        });
    }, { threshold: 0.08 });
    reveals.forEach(function (el) { obs.observe(el); });
})();

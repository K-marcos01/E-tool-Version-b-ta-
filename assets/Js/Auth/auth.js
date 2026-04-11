/**
 * auth.js — Validation + interactions Login.php & Register.php
 * Gère : toggle pwd, sélecteur rôle, force mot de passe,
 * validation client avant envoi, reveal au scroll.
 */
(function () {
    'use strict';

    /* ── Toggle voir / cacher mot de passe ── */
    document.querySelectorAll('.form-toggle-pwd').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var id  = btn.dataset.target;
            var inp = document.getElementById(id);
            if (!inp) return;
            inp.type = inp.type === 'password' ? 'text' : 'password';
            var eye = btn.querySelector('.icon-eye');
            if (eye) eye.style.opacity = inp.type === 'text' ? '.5' : '1';
        });
    });

    /* ── Sélecteur de rôle (Register) ── */
    var roleBtns  = document.querySelectorAll('.role-btn');
    var roleInput = document.getElementById('role-input');
    roleBtns.forEach(function (btn) {
        btn.addEventListener('click', function () {
            roleBtns.forEach(function (b) { b.classList.remove('active'); });
            btn.classList.add('active');
            if (roleInput) roleInput.value = btn.dataset.role;
        });
    });

    /* ── Indicateur force mot de passe ── */
    var pwdField = document.getElementById('reg-pwd');
    var pwdBars  = document.getElementById('pwd-bars');
    var pwdLabel = document.getElementById('pwd-label');
    var levels   = ['', 'Faible', 'Moyen', 'Fort', 'Très fort'];
    if (pwdField && pwdBars) {
        pwdField.addEventListener('input', function () {
            var val = pwdField.value;
            var score = 0;
            if (val.length >= 8)          score++;
            if (/[A-Z]/.test(val))        score++;
            if (/[0-9]/.test(val))        score++;
            if (/[^A-Za-z0-9]/.test(val)) score++;
            pwdBars.setAttribute('data-level', score > 0 ? score : '');
            if (pwdLabel) pwdLabel.textContent = levels[score] || '';
        });
    }

    /* ── Validation Login ── */
    var loginForm = document.getElementById('login-form');
    if (loginForm) {
        loginForm.addEventListener('submit', function (e) {
            var ok = true;
            clearErr('err-email'); clearErr('err-password');
            var email = document.getElementById('login-email');
            var pwd   = document.getElementById('login-pwd');
            if (!email || !isEmail(email.value)) { showErr('err-email', 'E-mail invalide.'); ok = false; }
            if (!pwd || !pwd.value.trim())        { showErr('err-password', 'Mot de passe requis.'); ok = false; }
            if (!ok) { e.preventDefault(); return; }
            setLoading('login-submit', 'Connexion…');
        });
    }

    /* ── Validation Register ── */
    var regForm = document.getElementById('register-form');
    if (regForm) {
        regForm.addEventListener('submit', function (e) {
            var ok = true;
            ['err-name','err-reg-email','err-pwd','err-confirm','err-terms'].forEach(clearErr);
            var name    = document.getElementById('reg-name');
            var email   = document.getElementById('reg-email');
            var pwd     = document.getElementById('reg-pwd');
            var confirm = document.getElementById('reg-confirm');
            var terms   = document.getElementById('terms');
            if (!name || name.value.trim().length < 2)        { showErr('err-name',      'Nom trop court (2 min).'); ok = false; }
            if (!email || !isEmail(email.value))               { showErr('err-reg-email', 'E-mail invalide.'); ok = false; }
            if (!pwd || pwd.value.length < 8)                  { showErr('err-pwd',       'Minimum 8 caractères.'); ok = false; }
            if (!confirm || confirm.value !== (pwd ? pwd.value : '')) { showErr('err-confirm', 'Mots de passe différents.'); ok = false; }
            if (!terms || !terms.checked)                      { showErr('err-terms',     'Veuillez accepter les CGU.'); ok = false; }
            if (!ok) { e.preventDefault(); return; }
            setLoading('register-submit', 'Création du compte…');
        });
    }

    /* ── Helpers ── */
    function isEmail(v) { return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v); }
    function showErr(id, msg) { var el = document.getElementById(id); if (el) el.textContent = msg; }
    function clearErr(id)     { var el = document.getElementById(id); if (el) el.textContent = ''; }
    function setLoading(id, txt) {
        var btn = document.getElementById(id);
        if (btn) { btn.disabled = true; var sp = btn.querySelector('span'); if (sp) sp.textContent = txt; }
    }

    /* ── Reveal scroll (Login/Register n'ont pas .reveal, mais gardé pour cohérence) ── */
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

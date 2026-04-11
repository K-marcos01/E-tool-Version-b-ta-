/**
 * contact.js — Comportements de Contact.php
 * Validation, compteur de caractères, drag & drop fichier.
 */
(function () {
    'use strict';

    /* ── Compteur de caractères ── */
    var textarea  = document.getElementById('c-message');
    var charCount = document.getElementById('char-count');
    if (textarea && charCount) {
        textarea.addEventListener('input', function () {
            charCount.textContent = textarea.value.length + '/800';
            charCount.style.color = textarea.value.length > 750 ? '#EF4444' : '#9CA3AF';
        });
    }

    /* ── Drag & drop zone fichier ── */
    var dropZone = document.getElementById('drop-zone');
    var fileInput = document.getElementById('c-file');
    var fileLabel = document.getElementById('file-label');
    if (dropZone && fileInput) {
        ['dragover','dragenter'].forEach(function (ev) {
            dropZone.addEventListener(ev, function (e) {
                e.preventDefault(); dropZone.classList.add('drag-over');
            });
        });
        ['dragleave','drop'].forEach(function (ev) {
            dropZone.addEventListener(ev, function (e) {
                e.preventDefault(); dropZone.classList.remove('drag-over');
                if (ev === 'drop' && e.dataTransfer.files.length) {
                    fileInput.files = e.dataTransfer.files;
                    if (fileLabel) fileLabel.textContent = e.dataTransfer.files[0].name;
                }
            });
        });
        fileInput.addEventListener('change', function () {
            if (fileInput.files.length && fileLabel) {
                fileLabel.textContent = fileInput.files[0].name;
            }
        });
    }

    /* ── Validation formulaire ── */
    var form = document.getElementById('contact-form');
    if (form) {
        form.addEventListener('submit', function (e) {
            var ok = true;
            ['err-c-name','err-c-email','err-c-subject','err-c-message'].forEach(function(id){
                var el = document.getElementById(id); if(el) el.textContent='';
            });
            var name    = document.getElementById('c-name');
            var email   = document.getElementById('c-email');
            var subject = document.getElementById('c-subject');
            var msg     = document.getElementById('c-message');

            if (!name || name.value.trim().length < 2) {
                err('err-c-name','Nom requis (2 caractères min).'); ok=false;
            }
            if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
                err('err-c-email','E-mail invalide.'); ok=false;
            }
            if (!subject || !subject.value) {
                err('err-c-subject','Choisissez un sujet.'); ok=false;
            }
            if (!msg || msg.value.trim().length < 10) {
                err('err-c-message','Message trop court (10 caractères min).'); ok=false;
            }
            if (!ok) { e.preventDefault(); return; }
            var btn = document.getElementById('contact-submit');
            if (btn) { btn.disabled=true; btn.querySelector('span').textContent='Envoi en cours…'; }
        });
    }

    /* ── Reveal ── */
    var reveals = document.querySelectorAll('.reveal');
    if (reveals.length) {
        var obs = new IntersectionObserver(function(entries){
            entries.forEach(function(e){
                if(e.isIntersecting){e.target.classList.add('visible');obs.unobserve(e.target);}
            });
        },{threshold:0.1});
        reveals.forEach(function(el){obs.observe(el);});
    }

    function err(id, msg) {
        var el = document.getElementById(id); if(el) el.textContent = msg;
    }
})();

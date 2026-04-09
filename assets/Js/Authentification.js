/**
 * assets/js/auth.js
 * Gestion de l'affichage dynamique pour le rôle Boutique
 */
document.addEventListener('DOMContentLoaded', () => {
    const roleSelector = document.getElementById('role-selector');
    const shopField = document.getElementById('shop-field');

    if (roleSelector) {
        roleSelector.addEventListener('change', function() {
            // Si l'utilisateur choisit d'être une boutique, on affiche le champ
            if (this.value === 'boutique') {
                shopField.classList.remove('hidden');
                shopField.classList.add('animate-slide-down'); // Animation CSS
            } else {
                shopField.classList.add('hidden');
            }
        });
    }
});
/**
 * Fichier JavaScript principal
 * 
 * Je gère les interactions côté client
 */

document.addEventListener('DOMContentLoaded', function() {

    // Je récupère le bouton de déconnexion
    const logoutBtn = document.getElementById('logoutBtn');
    
    // J'ajoute une confirmation avant déconnexion
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function(e) {
            if (!confirm('Êtes-vous sûr de vouloir vous déconnecter ?')) {
                e.preventDefault();
            }
        });
    }

    // Je récupère les formulaires
    const loginForm = document.querySelector('form[action*="login"]');
    const registerForm = document.querySelector('form[action*="register"]');

    // Je valide le formulaire de connexion côté client
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;

            // Je vérifie que les champs sont remplis
            if (!email || !password) {
                e.preventDefault();
                alert('Veuillez remplir tous les champs.');
                return;
            }

            // Je vérifie le format de l'email
            if (!isValidEmail(email)) {
                e.preventDefault();
                alert('Veuillez entrer un email valide.');
                return;
            }
        });
    }

    // Je valide le formulaire d'inscription côté client
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            const username = document.getElementById('username').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;

            // Je vérifie que les champs sont remplis
            if (!username || !email || !password || !confirmPassword) {
                e.preventDefault();
                alert('Veuillez remplir tous les champs.');
                return;
            }

            // Je vérifie la longueur du nom d'utilisateur
            if (username.length < 3 || username.length > 50) {
                e.preventDefault();
                alert('Le nom d\'utilisateur doit contenir entre 3 et 50 caractères.');
                return;
            }

            // Je vérifie le format de l'email
            if (!isValidEmail(email)) {
                e.preventDefault();
                alert('Veuillez entrer un email valide.');
                return;
            }

            // Je vérifie la longueur du mot de passe
            if (password.length < 8) {
                e.preventDefault();
                alert('Le mot de passe doit contenir au moins 8 caractères.');
                return;
            }

            // Je vérifie que les mots de passe correspondent
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Les mots de passe ne correspondent pas.');
                return;
            }
        });
    }

    /**
     * Je vérifie si un email est valide
     *
     * @param {string} email Email à vérifier
     * @return {boolean}
     */
    function isValidEmail(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }

});
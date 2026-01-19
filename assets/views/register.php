<!-- Vue Inscription -->
<div class="row justify-content-center">
    <div class="col-md-5">
        <h1 class="text-center mb-4">Inscription</h1>
        
        <!-- J'affiche le message d'erreur si présent -->
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <!-- J'affiche le message de succès si présent -->
        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        
        <!-- Je crée le formulaire d'inscription -->
        <form action="index.php?page=register" method="POST">
            <!-- Token CSRF -->
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
            
            <!-- Champ nom d'utilisateur -->
            <div class="mb-3">
                <label for="username" class="form-label">Nom d'utilisateur</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            
            <!-- Champ email -->
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            
            <!-- Champ mot de passe -->
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            
            <!-- Champ confirmation mot de passe -->
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirmer le mot de passe</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
            
            <!-- Bouton de soumission -->
            <button type="submit" class="btn btn-dark w-100">S'inscrire</button>
        </form>
        
        <!-- Lien vers la connexion -->
        <p class="text-center mt-3">
            Déjà un compte ? <a href="index.php?page=login">Se connecter</a>
        </p>
    </div>
</div>
<!-- Vue Connexion -->
<div class="row justify-content-center">
    <div class="col-md-5">
        <h1 class="text-center mb-4">Connexion</h1>
        
        <!-- J'affiche le message d'erreur si présent -->
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <!-- Je crée le formulaire de connexion -->
        <form action="index.php?page=login" method="POST">
            <!-- Token CSRF -->
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
            
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
            
            <!-- Bouton de soumission -->
            <button type="submit" class="btn btn-dark w-100">Se connecter</button>
        </form>
        
        <!-- Lien vers l'inscription -->
        <p class="text-center mt-3">
            Pas encore de compte ? <a href="index.php?page=register">S'inscrire</a>
        </p>
    </div>
</div>
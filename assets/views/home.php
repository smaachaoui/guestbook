<!-- Vue Accueil -->
<div class="row justify-content-center">
    <div class="col-md-8 text-center">
        <h1>Bienvenue sur le module de connexion</h1>
        <p class="lead"> Un système d'authentification solide.</p>
        
        <!-- J'affiche un contenu différent selon l'état de connexion -->
        <?php if (isset($_SESSION['user'])): ?>
            <!-- Je suis connecté -->
            <p>Vous êtes connecté en tant que <strong><?= htmlspecialchars($_SESSION['user']['username']) ?></strong></p>
            <a href="index.php?page=profile" class="btn btn-dark">Voir mon profil</a>
        <?php else: ?>
            <!-- Je ne suis pas connecté -->
            <p>Connectez-vous ou créez un compte pour accéder à votre espace.</p>
            <div class="d-flex gap-3 justify-content-center">
                <a href="index.php?page=login" class="btn btn-dark">Connexion</a>
                <a href="index.php?page=register" class="btn btn-outline-dark">Inscription</a>
            </div>
        <?php endif; ?>
    </div>
</div>
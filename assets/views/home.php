<!-- Vue Accueil -->
<div class="row justify-content-center">
    <div class="col-md-8 text-center">
        <h1>Bienvenue sur le livre d'or</h1>
        <p class="lead">Pour mettre sur écrit toutes vos pensées.</p>
        
        <!-- J'affiche un contenu différent selon l'état de connexion -->
        <?php if (isset($_SESSION['user'])): ?>
            <!-- Je suis connecté -->
            <p>Vous êtes connecté en tant que <strong><?= htmlspecialchars($_SESSION['user']['username']) ?></strong></p>
            <a href="index.php?page=profile" class="btn btn-dark">Voir mon profil</a>
        <?php else: ?>
            <!-- Je ne suis pas connecté -->
            <p>Commencez par créer un compte et connectez-vous pour laisser un commentaire dans le livre d'or.</p>
            <div class="d-flex gap-3 justify-content-center">
                <a href="index.php?page=login" class="btn btn-dark">Connexion</a>
                <a href="index.php?page=register" class="btn btn-outline-dark">Inscription</a>
            </div>
        <?php endif; ?>
    </div>
</div>
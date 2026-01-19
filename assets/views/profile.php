<!-- Vue Profil -->
<?php
// Je récupère les infos de l'utilisateur connecté
$user = $_SESSION['user'];
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <h1 class="text-center mb-4">Mon Profil</h1>

            <!-- Je crée la carte utilisateur -->
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <!-- Avatar avec initiale -->
                    <div class="text-center mb-4">
                        <div
                            class="bg-dark text-white rounded-circle d-inline-flex align-items-center justify-content-center"
                            style="width: 80px; height: 80px; font-size: 2rem;"
                            aria-label="Avatar"
                        >
                            <?= strtoupper(substr($user['username'], 0, 1)) ?>
                        </div>
                    </div>

                    <!-- Nom d'utilisateur -->
                    <div class="mb-3">
                        <strong>Nom d'utilisateur :</strong>
                        <p class="mb-0"><?= htmlspecialchars($user['username']) ?></p>
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <strong>Email :</strong>
                        <p class="mb-0"><?= htmlspecialchars($user['email']) ?></p>
                    </div>

                    <!-- Rôle -->
                    <div class="mb-4">
                        <strong>Rôle :</strong>
                        <p class="mb-0"><?= $user['role'] === 'admin' ? 'Administrateur' : 'Utilisateur' ?></p>
                    </div>

                    <!-- Actions -->
                    <div class="d-grid gap-2">
                        <!-- Je propose l'accès admin si l'utilisateur est admin -->
                        <?php if ($user['role'] === 'admin'): ?>
                            <a href="index.php?page=admin" class="btn btn-dark">
                                Accéder à l'administration
                            </a>
                        <?php endif; ?>

                        <a href="index.php?page=edit_profile" class="btn btn-primary">
                            Modifier mon profil
                        </a>

                        <!-- Bouton déconnexion -->
                        <a href="index.php?page=logout" class="btn btn-outline-dark" id="logoutBtn">
                            Se déconnecter
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

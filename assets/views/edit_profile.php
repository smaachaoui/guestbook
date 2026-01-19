<?php
// Je récupère l'utilisateur connecté
$currentUser = $authController->getCurrentUser();
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h1 class="h3 text-center mb-4">Modifier mon profil</h1>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger" role="alert">
                            <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success" role="alert">
                            <?= htmlspecialchars($success) ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="index.php?page=edit_profile" novalidate>
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

                        <div class="mb-3">
                            <label for="username" class="form-label">Nom d'utilisateur</label>
                            <input
                                type="text"
                                class="form-control"
                                id="username"
                                name="username"
                                value="<?= htmlspecialchars($currentUser['username'] ?? '') ?>"
                                required
                                autocomplete="username"
                            >
                        </div>

                        <div class="mb-4">
                            <label for="email" class="form-label">Email</label>
                            <input
                                type="email"
                                class="form-control"
                                id="email"
                                name="email"
                                value="<?= htmlspecialchars($currentUser['email'] ?? '') ?>"
                                required
                                autocomplete="email"
                            >
                        </div>

                        <div class="d-grid gap-2 d-sm-flex justify-content-sm-between">
                            <button type="submit" class="btn btn-primary">
                                Enregistrer
                            </button>

                            <a href="index.php?page=profile" class="btn btn-outline-secondary">
                                Retour à mon profil
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

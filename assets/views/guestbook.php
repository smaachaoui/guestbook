<?php
// Je vérifie si l'utilisateur est connecté
$isLoggedIn = $authController->isLoggedIn();
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <h1 class="text-center mb-4">Livre d'or</h1>

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

            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <h2 class="h5 mb-3">Laisser un témoignage</h2>

                    <?php if (!$isLoggedIn): ?>
                        <div class="alert alert-warning mb-0" role="alert">
                            Vous devez être connecté pour laisser un commentaire.
                        </div>
                    <?php else: ?>
                        <form method="POST" action="index.php?page=guestbook" novalidate>
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

                            <div class="mb-3">
                                <label for="title" class="form-label">Titre</label>
                                <input type="text" class="form-control" id="title" name="title" maxlength="100" placeholder="Facultatif">
                            </div>

                            <div class="mb-3">
                                <label for="message" class="form-label">Message</label>
                                <textarea class="form-control" id="message" name="message" rows="4" maxlength="1000" required></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                Publier
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>

            <h2 class="h5 mb-3">Témoignages</h2>

            <?php if (empty($comments)): ?>
                <div class="alert alert-secondary" role="alert">
                    Aucun message pour le moment.
                </div>
            <?php else: ?>
                <?php foreach ($comments as $comment): ?>
                    <div class="card shadow-sm mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start gap-3">
                                <div>
                                    <?php if (!empty($comment['title'])): ?>
                                        <h3 class="h6 mb-1"><?= htmlspecialchars($comment['title']) ?></h3>
                                    <?php endif; ?>

                                    <p class="mb-2"><?= nl2br(htmlspecialchars($comment['message'])) ?></p>

                                    <small class="text-muted">
                                        Par <?= htmlspecialchars($comment['username']) ?>
                                    </small>
                                </div>

                                <small class="text-muted text-end">
                                    <?= htmlspecialchars($comment['created_at']) ?>
                                </small>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

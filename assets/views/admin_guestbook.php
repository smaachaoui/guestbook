<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <h1 class="text-center mb-4">Administration du livre d'or</h1>

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

            <?php if (empty($adminComments)): ?>
                <div class="alert alert-secondary" role="alert">
                    Aucun commentaire à afficher.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Auteur</th>
                                <th>Titre</th>
                                <th>Message</th>
                                <th>Visible</th>
                                <th>Date</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($adminComments as $comment): ?>
                                <tr>
                                    <td><?= (int) $comment['id'] ?></td>
                                    <td><?= htmlspecialchars($comment['username']) ?></td>
                                    <td><?= htmlspecialchars($comment['title'] ?? '') ?></td>
                                    <td><?= nl2br(htmlspecialchars($comment['message'])) ?></td>
                                    <td><?= (int) $comment['is_visible'] === 1 ? 'Oui' : 'Non' ?></td>
                                    <td><?= htmlspecialchars($comment['created_at']) ?></td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-2">

                                            <form method="POST" action="index.php?page=admin_guestbook">
                                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                                                <input type="hidden" name="action" value="toggle_visibility">
                                                <input type="hidden" name="comment_id" value="<?= (int) $comment['id'] ?>">
                                                <input type="hidden" name="is_visible" value="<?= (int) $comment['is_visible'] === 1 ? 0 : 1 ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-primary">
                                                    <?= (int) $comment['is_visible'] === 1 ? 'Masquer' : 'Afficher' ?>
                                                </button>
                                            </form>

                                            <form method="POST" action="index.php?page=admin_guestbook" onsubmit="return confirm('Confirmer la suppression ?');">
                                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="comment_id" value="<?= (int) $comment['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    Supprimer
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

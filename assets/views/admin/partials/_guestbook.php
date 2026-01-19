<?php if ($currentSection === 'guestbook'): ?>
    <div class="card">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Administration du livre d'or</h5>
        </div>
        <div class="card-body">

            <?php if (!empty($commentToEdit)): ?>
                <div class="card mb-3">
                    <div class="card-header bg-dark text-white">
                        <strong>Modifier le commentaire #<?= (int)$commentToEdit['id'] ?></strong>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="index.php?page=admin&section=guestbook">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                            <input type="hidden" name="action" value="edit_comment">
                            <input type="hidden" name="comment_id" value="<?= (int)$commentToEdit['id'] ?>">

                            <div class="mb-3">
                                <label class="form-label">Titre (optionnel)</label>
                                <input
                                    type="text"
                                    name="title"
                                    class="form-control"
                                    maxlength="100"
                                    value="<?= htmlspecialchars($commentToEdit['title'] ?? '') ?>"
                                >
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Message</label>
                                <textarea
                                    name="message"
                                    class="form-control"
                                    rows="5"
                                    required
                                ><?= htmlspecialchars($commentToEdit['message'] ?? '') ?></textarea>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-dark">Enregistrer</button>
                                <a class="btn btn-outline-secondary" href="index.php?page=admin&section=guestbook">Annuler</a>
                            </div>
                        </form>
                    </div>
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
                                    <td><?= (int)$comment['id'] ?></td>
                                    <td><?= htmlspecialchars($comment['username']) ?></td>
                                    <td><?= htmlspecialchars($comment['title'] ?? '') ?></td>
                                    <td><?= nl2br(htmlspecialchars($comment['message'])) ?></td>
                                    <td><?= (int)$comment['is_visible'] === 1 ? 'Oui' : 'Non' ?></td>
                                    <td><?= htmlspecialchars($comment['created_at']) ?></td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-2">

                                            <!-- EDIT: on câblera l’édition ensuite -->
                                            <a
                                                class="btn btn-sm btn-outline-primary"
                                                href="index.php?page=admin&section=guestbook&edit=<?= (int)$comment['id'] ?>"
                                            >
                                                Modifier
                                            </a>

                                            <!-- TOGGLE VISIBILITY -->
                                            <form method="POST" action="index.php?page=admin&section=guestbook">
                                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                                                <input type="hidden" name="action" value="toggle_comment_visibility">
                                                <input type="hidden" name="comment_id" value="<?= (int)$comment['id'] ?>">
                                                <input type="hidden" name="is_visible" value="<?= (int)$comment['is_visible'] === 1 ? 0 : 1 ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-primary">
                                                    <?= (int)$comment['is_visible'] === 1 ? 'Masquer' : 'Afficher' ?>
                                                </button>
                                            </form>

                                            <!-- DELETE -->
                                            <form
                                                method="POST"
                                                action="index.php?page=admin&section=guestbook"
                                                onsubmit="return confirm('Confirmer la suppression ?');"
                                            >
                                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                                                <input type="hidden" name="action" value="delete_comment">
                                                <input type="hidden" name="comment_id" value="<?= (int)$comment['id'] ?>">
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
<?php endif; ?>
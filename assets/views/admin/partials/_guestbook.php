<?php if ($currentSection === 'guestbook'): ?>
    <div class="card">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Administration du livre d'or</h5>
        </div>

        <div class="card-body">
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

                                            <!-- EDIT (MODAL) -->
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalEditComment"
                                                data-comment-id="<?= (int)$comment['id'] ?>"
                                                data-comment-title="<?= htmlspecialchars(json_encode($comment['title'] ?? '', JSON_UNESCAPED_UNICODE), ENT_QUOTES) ?>"
                                                data-comment-message="<?= htmlspecialchars(json_encode($comment['message'] ?? '', JSON_UNESCAPED_UNICODE), ENT_QUOTES) ?>"
                                                data-comment-username="<?= htmlspecialchars(json_encode($comment['username'] ?? '', JSON_UNESCAPED_UNICODE), ENT_QUOTES) ?>"
                                            >
                                                Modifier
                                            </button>

                                            <!-- TOGGLE VISIBILITY (POST direct) -->
                                            <form method="POST" action="index.php?page=admin&section=guestbook" class="d-inline">
                                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                                                <input type="hidden" name="action" value="toggle_comment_visibility">
                                                <input type="hidden" name="comment_id" value="<?= (int)$comment['id'] ?>">

                                                <button type="submit" class="btn btn-sm btn-outline-primary">
                                                    <?= (int)$comment['is_visible'] === 1 ? 'Masquer' : 'Afficher' ?>
                                                </button>
                                            </form>

                                            <!-- DELETE (MODAL) -->
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-danger"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalDeleteComment"
                                                data-comment-id="<?= (int)$comment['id'] ?>"
                                                data-comment-username="<?= htmlspecialchars(json_encode($comment['username'] ?? '', JSON_UNESCAPED_UNICODE), ENT_QUOTES) ?>"
                                            >
                                                Supprimer
                                            </button>

                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <?php
                // Inclusion des modals commentaires (séparées)
                require_once __DIR__ . '/modals/_guestbook_modals.php';
                ?>

            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

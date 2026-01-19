<!-- Vue Administration -->

<div class="container-fluid py-4">
    <div class="row g-3">

        <!-- ASIDE ADMIN -->
        <aside class="col-12 col-md-3 col-lg-2">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <strong>Menu admin</strong>
                </div>
                <div class="list-group list-group-flush">
                    <a
                        class="list-group-item list-group-item-action <?= ($section ?? 'dashboard') === 'dashboard' ? 'active' : '' ?>"
                        href="index.php?page=admin&section=dashboard">
                        Tableau de bord
                    </a>

                    <a
                        class="list-group-item list-group-item-action <?= ($section ?? '') === 'users' ? 'active' : '' ?>"
                        href="index.php?page=admin&section=users">
                        Utilisateurs
                    </a>

                    <a
                        class="list-group-item list-group-item-action <?= ($section ?? '') === 'guestbook' ? 'active' : '' ?>"
                        href="index.php?page=admin&section=guestbook">
                        Livre d'or
                    </a>
                </div>
            </div>
        </aside>

        <!-- CONTENU -->
        <section class="col-12 col-md-9 col-lg-10">
            <h1 class="mb-4">Administration</h1>

            <!-- Alerts -->
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

            <?php $currentSection = $section ?? 'dashboard'; ?>

            <!-- SECTION: DASHBOARD -->
            <?php if ($currentSection === 'dashboard'): ?>
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card bg-dark text-white">
                            <div class="card-body text-center">
                                <h3><?= (int)($totalUsers ?? 0) ?></h3>
                                <p class="mb-0">Utilisateurs</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card bg-dark text-white">
                            <div class="card-body text-center">
                                <h3><?= (int)($totalGuestbookMessages ?? 0) ?></h3>
                                <p class="mb-0">Messages (livre d'or)</p>
                                <small>
                                    Visibles: <?= (int)($totalGuestbookVisible ?? 0) ?> /
                                    Masqués: <?= (int)($totalGuestbookHidden ?? 0) ?>
                                </small>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-4">
                        <div class="card bg-dark text-white">
                            <div class="card-body text-center">
                                <h3><?= (int)($newUsers ?? 0) ?></h3>
                                <p class="mb-0">Nouveaux (7j)</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">Raccourcis</h5>
                    </div>
                    <div class="card-body d-flex gap-2 flex-wrap">
                        <a class="btn btn-outline-dark" href="index.php?page=admin&section=users">Gérer les utilisateurs</a>
                        <a class="btn btn-outline-dark" href="index.php?page=admin&section=guestbook">Gérer le livre d'or</a>
                    </div>
                </div>
            <?php endif; ?>

            <!-- SECTION: USERS -->
            <?php if ($currentSection === 'users'): ?>
                
                <?php if (!empty($userToEdit)): ?>
                    <div class="card mb-3">
                        <div class="card-header bg-dark text-white">
                            <h5 class="mb-0">Modifier l'utilisateur #<?= (int)$userToEdit['id'] ?></h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="index.php?page=admin&section=users" class="row g-3">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                                <input type="hidden" name="action" value="update_user">
                                <input type="hidden" name="user_id" value="<?= (int)$userToEdit['id'] ?>">

                                <div class="col-md-4">
                                    <label class="form-label">Nom d'utilisateur</label>
                                    <input
                                        type="text"
                                        name="username"
                                        class="form-control"
                                        required
                                        value="<?= htmlspecialchars($userToEdit['username'] ?? '') ?>"
                                    >
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Email</label>
                                    <input
                                        type="email"
                                        name="email"
                                        class="form-control"
                                        required
                                        value="<?= htmlspecialchars($userToEdit['email'] ?? '') ?>"
                                    >
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Nouveau mot de passe (optionnel)</label>
                                    <input type="password" name="password" class="form-control" placeholder="Laisser vide pour ne pas changer">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Rôle</label>
                                    <select name="role" class="form-select">
                                        <option value="user" <?= (($userToEdit['role'] ?? '') === 'user') ? 'selected' : '' ?>>user</option>
                                        <option value="admin" <?= (($userToEdit['role'] ?? '') === 'admin') ? 'selected' : '' ?>>admin</option>
                                    </select>
                                </div>

                                <div class="col-12 d-flex gap-2">
                                    <button class="btn btn-dark" type="submit">Enregistrer</button>
                                    <a class="btn btn-outline-secondary" href="index.php?page=admin&section=users">Annuler</a>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="card mb-3">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">Créer un utilisateur</h5>
                    </div>
                    <div class="card-body">
                        <!-- Form CREATE (sera câblé sur action=create_user dans index.php) -->
                        <form method="POST" action="index.php?page=admin&section=users" class="row g-3">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                            <input type="hidden" name="action" value="create_user">

                            <div class="col-md-4">
                                <label class="form-label">Nom d'utilisateur</label>
                                <input type="text" name="username" class="form-control" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Mot de passe</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Rôle</label>
                                <select name="role" class="form-select">
                                    <option value="user" selected>user</option>
                                    <option value="admin">admin</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <button class="btn btn-dark" type="submit">Créer</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">Liste des utilisateurs</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped align-middle">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nom d'utilisateur</th>
                                        <th>Email</th>
                                        <th>Rôle</th>
                                        <th>Date d'inscription</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($users)): ?>
                                        <tr>
                                            <td colspan="6" class="text-center">Aucun utilisateur trouvé.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($users as $user): ?>
                                            <tr>
                                                <td><?= (int)$user['id'] ?></td>
                                                <td><?= htmlspecialchars($user['username']) ?></td>
                                                <td><?= htmlspecialchars($user['email']) ?></td>
                                                <td>
                                                    <?php if ($user['role'] === 'admin'): ?>
                                                        <span class="badge bg-danger">Admin</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">User</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= date('d/m/Y H:i', strtotime($user['created_at'])) ?></td>
                                                <td class="text-end">
                                                    <div class="d-flex justify-content-end gap-2">
                                                        <!-- EDIT: on câblera via une UI (modal ou page) à l’étape suivante -->
                                                        <a
                                                            class="btn btn-sm btn-outline-primary"
                                                            href="index.php?page=admin&section=users&edit=<?= (int)$user['id'] ?>"
                                                        >
                                                            Modifier
                                                        </a>

                                                        <!-- DELETE -->
                                                        <form
                                                            method="POST"
                                                            action="index.php?page=admin&section=users"
                                                            onsubmit="return confirm('Confirmer la suppression ?');"
                                                        >
                                                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                                                            <input type="hidden" name="action" value="delete_user">
                                                            <input type="hidden" name="user_id" value="<?= (int)$user['id'] ?>">
                                                            <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- SECTION: GUESTBOOK -->
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

        </section>
    </div>
</div>

<?php if ($currentSection === 'users'): ?>
    
    <?php if (!empty($userToEdit)): ?>
        
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
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalEditUser"
                                                data-user-id="<?= (int)$user['id'] ?>"
                                                data-user-username="<?= htmlspecialchars($user['username'], ENT_QUOTES) ?>"
                                                data-user-email="<?= htmlspecialchars($user['email'], ENT_QUOTES) ?>"
                                                data-user-role="<?= htmlspecialchars($user['role'], ENT_QUOTES) ?>">
                                                Modifier
                                            </button>

                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-danger"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalDeleteUser"
                                                data-user-id="<?= (int)$user['id'] ?>"
                                                data-user-username="<?= htmlspecialchars($user['username'], ENT_QUOTES) ?>">
                                                Supprimer
                                            </button>

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

    <?php
    require_once __DIR__ . '/modals/_user_modals.php';
    ?>

<?php endif; ?>
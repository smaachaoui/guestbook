<!-- Vue Administration -->


<div class="row">
    <div class="col-12">
        <h1 class="mb-4">Administration</h1>
        
        <!-- Je crée les cartes de statistiques -->
        <div class="row mb-4">
            <!-- Total utilisateurs -->
            <div class="col-md-4">
                <div class="card bg-dark text-white">
                    <div class="card-body text-center">
                        <h3><?= $totalUsers ?></h3>
                        <p class="mb-0">Utilisateurs</p>
                    </div>
                </div>
            </div>
            
            <!-- Total admins -->
            <div class="col-md-4">
                <div class="card bg-dark text-white">
                    <div class="card-body text-center">
                        <h3><?= $totalAdmins ?></h3>
                        <p class="mb-0">Administrateurs</p>
                    </div>
                </div>
            </div>
            
            <!-- Nouveaux utilisateurs -->
            <div class="col-md-4">
                <div class="card bg-dark text-white">
                    <div class="card-body text-center">
                        <h3><?= $newUsers ?></h3>
                        <p class="mb-0">Nouveaux (7j)</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Je crée le tableau des utilisateurs -->
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">Liste des utilisateurs</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom d'utilisateur</th>
                                <th>Email</th>
                                <th>Rôle</th>
                                <th>Date d'inscription</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($users)): ?>
                                <!-- Aucun utilisateur trouvé -->
                                <tr>
                                    <td colspan="5" class="text-center">Aucun utilisateur trouvé.</td>
                                </tr>
                            <?php else: ?>
                                <!-- J'affiche chaque utilisateur -->
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($user['id']) ?></td>
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
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
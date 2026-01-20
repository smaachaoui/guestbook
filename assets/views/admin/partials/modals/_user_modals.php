<?php
// Modals utilisateurs (admin)
?>

<!-- Modal EDIT USER -->
<div class="modal fade" id="modalEditUser" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="index.php?page=admin&section=users">
        <div class="modal-header">
          <h5 class="modal-title">Modifier un utilisateur</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
          <input type="hidden" name="action" value="update_user">
          <input type="hidden" name="user_id" id="modalEditUser_id">

          <div class="mb-3">
            <label class="form-label">Nom d'utilisateur</label>
            <input type="text" class="form-control" name="username" id="modalEditUser_username" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" id="modalEditUser_email" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Rôle</label>
            <select class="form-select" name="role" id="modalEditUser_role">
              <option value="user">user</option>
              <option value="admin">admin</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Nouveau mot de passe (optionnel)</label>
            <input type="password" class="form-control" name="password" id="modalEditUser_password" placeholder="Laisser vide pour ne pas changer">
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" class="btn btn-primary">Enregistrer</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal DELETE USER -->
<div class="modal fade" id="modalDeleteUser" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="index.php?page=admin&section=users">
        <div class="modal-header">
          <h5 class="modal-title">Supprimer un utilisateur</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
          <input type="hidden" name="action" value="delete_user">
          <input type="hidden" name="user_id" id="modalDeleteUser_id">

          <p class="mb-0">
            Confirmer la suppression de <strong id="modalDeleteUser_username"></strong> ?
          </p>
          <small class="text-muted">Cette action est irréversible.</small>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" class="btn btn-danger">Supprimer</button>
        </div>
      </form>
    </div>
  </div>
</div>

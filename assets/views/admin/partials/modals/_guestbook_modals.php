<?php
// Modals commentaires (admin)
?>

<!-- Modal EDIT COMMENT -->
<div class="modal fade" id="modalEditComment" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form method="POST" action="index.php?page=admin&section=guestbook">
        <div class="modal-header">
          <h5 class="modal-title">
            Modifier le commentaire de <strong id="modalEditComment_username"></strong>
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
          <input type="hidden" name="action" value="edit_comment">
          <input type="hidden" name="comment_id" id="modalEditComment_id">

          <div class="mb-3">
            <label class="form-label">Titre (optionnel)</label>
            <input
              type="text"
              name="title"
              class="form-control"
              id="modalEditComment_title"
              maxlength="100"
            >
          </div>

          <div class="mb-3">
            <label class="form-label">Message</label>
            <textarea
              name="message"
              class="form-control"
              id="modalEditComment_message"
              rows="6"
              required
            ></textarea>
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

<!-- Modal DELETE COMMENT -->
<div class="modal fade" id="modalDeleteComment" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="index.php?page=admin&section=guestbook">
        <div class="modal-header">
          <h5 class="modal-title">Supprimer un commentaire</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
          <input type="hidden" name="action" value="delete_comment">
          <input type="hidden" name="comment_id" id="modalDeleteComment_id">

          <p class="mb-0">
            Confirmer la suppression du commentaire de <strong id="modalDeleteComment_username"></strong> ?
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

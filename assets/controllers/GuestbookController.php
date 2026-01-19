<?php

/**
 * Contrôleur du livre d'or
 * 
 * Je gère l'affichage et les actions liées aux commentaires du livre d'or
 */
class GuestbookController
{
    /**
     * @var GuestbookComment Instance du modèle GuestbookComment
     */
    private GuestbookComment $commentModel;

    /**
     * Je construis le contrôleur du livre d'or
     */
    public function __construct()
    {
        $this->commentModel = new GuestbookComment();
    }

    /**
     * Je récupère les commentaires visibles du livre d'or
     *
     * @return array
     */
    public function getVisibleComments(): array
    {
        return $this->commentModel->findVisible(50);
    }

    /**
     * Je gère l'ajout d'un commentaire au livre d'or
     *
     * @return array Tableau contenant le statut et le message
     */
    public function addComment(): array
    {
        // Je vérifie que l'utilisateur est connecté
        if (!$this->isLoggedIn()) {
            return ['success' => false, 'error' => 'Vous devez être connecté pour laisser un commentaire.'];
        }

        // Je vérifie que la requête est en POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['success' => false, 'error' => null];
        }

        // Je vérifie le token CSRF
        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!$this->verifyCsrfToken($csrfToken)) {
            return ['success' => false, 'error' => 'Session expirée, veuillez réessayer.'];
        }

        // Je récupère et nettoie les données du formulaire
        $title = trim($_POST['title'] ?? '');
        $message = trim($_POST['message'] ?? '');

        // Je normalise le titre
        if ($title === '') {
            $title = null;
        }

        // Je valide le contenu
        $validation = $this->validateComment($title, $message);
        if (!$validation['valid']) {
            return ['success' => false, 'error' => $validation['error']];
        }

        // Je récupère l'utilisateur connecté
        $user = $this->getCurrentUser();
        if ($user === null) {
            return ['success' => false, 'error' => 'Utilisateur introuvable.'];
        }

        // Je crée le commentaire
        $created = $this->commentModel->create((int) $user['id'], $title, $message);
        if (!$created) {
            return ['success' => false, 'error' => 'Une erreur est survenue lors de l\'envoi du commentaire.'];
        }

        return ['success' => true, 'message' => 'Votre commentaire a bien été publié.'];
    }

    /**
     * Je valide les données d'un commentaire
     *
     * @param string|null $title   Titre du commentaire
     * @param string      $message Message du commentaire
     * 
     * @return array
     */
    private function validateComment(?string $title, string $message): array
    {
        // Je vérifie que le message est rempli
        if (empty($message)) {
            return ['valid' => false, 'error' => 'Le message est obligatoire.'];
        }

        // Je limite la taille du message
        if (mb_strlen($message) < 3 || mb_strlen($message) > 1000) {
            return ['valid' => false, 'error' => 'Le message doit contenir entre 3 et 1000 caractères.'];
        }

        // Je limite la taille du titre si présent
        if ($title !== null && mb_strlen($title) > 100) {
            return ['valid' => false, 'error' => 'Le titre ne peut pas dépasser 100 caractères.'];
        }

        return ['valid' => true, 'error' => null];
    }

    /**
     * Je récupère tous les commentaires pour l'administration
     *
     * @return array
     */
    public function getAllCommentsForAdmin(): array
    {
        // Je vérifie que l'utilisateur est admin
        if (!$this->isAdmin()) {
            return [];
        }

        return $this->commentModel->findAllForAdmin();
    }

    /**
     * Je récupère un commentaire par ID pour l'administration
     *
     * @param int $id
     *
     * @return array|null
     */
    public function getCommentForAdmin(int $id): ?array
    {
        // Je vérifie que l'utilisateur est admin
        if (!$this->isAdmin()) {
            return null;
        }

        if ($id <= 0) {
            return null;
        }

        return $this->commentModel->findByIdForAdmin($id);
    }


    /**
     * Je supprime un commentaire en administration
     *
     * @return array Tableau contenant le statut et le message
     */
    public function deleteCommentAdmin(): array
    {
        // Je vérifie que l'utilisateur est admin
        if (!$this->isAdmin()) {
            return ['success' => false, 'error' => 'Accès refusé.'];
        }

        // Je vérifie que la requête est en POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['success' => false, 'error' => null];
        }

        // Je vérifie le token CSRF
        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!$this->verifyCsrfToken($csrfToken)) {
            return ['success' => false, 'error' => 'Session expirée, veuillez réessayer.'];
        }

        // Je récupère l'ID du commentaire
        $id = (int) ($_POST['comment_id'] ?? 0);
        if ($id <= 0) {
            return ['success' => false, 'error' => 'Identifiant invalide.'];
        }

        // Je supprime le commentaire
        if (!$this->commentModel->delete($id)) {
            return ['success' => false, 'error' => 'Une erreur est survenue lors de la suppression.'];
        }

        return ['success' => true, 'message' => 'Commentaire supprimé avec succès.'];
    }

    /**
     * Je change la visibilité d'un commentaire en administration
     *
     * @return array Tableau contenant le statut et le message
     */
    public function toggleVisibilityAdmin(): array
    {
        // Je vérifie que l'utilisateur est admin
        if (!$this->isAdmin()) {
            return ['success' => false, 'error' => 'Accès refusé.'];
        }

        // Je vérifie que la requête est en POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['success' => false, 'error' => null];
        }

        // Je vérifie le token CSRF
        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!$this->verifyCsrfToken($csrfToken)) {
            return ['success' => false, 'error' => 'Session expirée, veuillez réessayer.'];
        }

        // Je récupère l'ID et la visibilité demandée
        $id = (int) ($_POST['comment_id'] ?? 0);
        $isVisible = (int) ($_POST['is_visible'] ?? 1);

        if ($id <= 0) {
            return ['success' => false, 'error' => 'Identifiant invalide.'];
        }

        // Je change la visibilité
        $ok = $this->commentModel->setVisibility($id, $isVisible === 1);
        if (!$ok) {
            return ['success' => false, 'error' => 'Une erreur est survenue lors de la mise à jour.'];
        }

        return ['success' => true, 'message' => 'Visibilité mise à jour avec succès.'];
    }

    /**
     * Je modifie un commentaire en administration
     *
     * @return array Tableau contenant le statut et le message
     */
    public function editCommentAdmin(): array
    {
        // Je vérifie que l'utilisateur est admin
        if (!$this->isAdmin()) {
            return ['success' => false, 'error' => 'Accès refusé.'];
        }

        // Je vérifie que la requête est en POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['success' => false, 'error' => null];
        }

        // Je vérifie le token CSRF
        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!$this->verifyCsrfToken($csrfToken)) {
            return ['success' => false, 'error' => 'Session expirée, veuillez réessayer.'];
        }

        // Je récupère l'ID du commentaire
        $id = (int) ($_POST['comment_id'] ?? 0);
        if ($id <= 0) {
            return ['success' => false, 'error' => 'Identifiant invalide.'];
        }

        // Je récupère et nettoie les données
        $title = trim($_POST['title'] ?? '');
        $message = trim($_POST['message'] ?? '');

        // Je normalise le titre
        if ($title === '') {
            $title = null;
        }

        // Je valide
        $validation = $this->validateComment($title, $message);
        if (!$validation['valid']) {
            return ['success' => false, 'error' => $validation['error']];
        }

        /**
         * Je mets à jour via le modèle.
         * On va ajouter la méthode dans GuestbookComment.php à l'étape suivante :
         * - updateAdmin(int $id, ?string $title, string $message): bool
         */
        $ok = $this->commentModel->updateAdmin($id, $title, $message);
        if (!$ok) {
            return ['success' => false, 'error' => 'Une erreur est survenue lors de la mise à jour.'];
        }

        return ['success' => true, 'message' => 'Commentaire modifié avec succès.'];
    }


    /**
     * Je vérifie si l'utilisateur est connecté
     *
     * @return bool
     */
    private function isLoggedIn(): bool
    {
        return isset($_SESSION['user']);
    }

    /**
     * Je vérifie si l'utilisateur est admin
     *
     * @return bool
     */
    private function isAdmin(): bool
    {
        return $this->isLoggedIn() && ($_SESSION['user']['role'] ?? '') === 'admin';
    }

    /**
     * Je récupère l'utilisateur connecté
     *
     * @return array|null
     */
    private function getCurrentUser(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    /**
     * Je vérifie le token CSRF
     *
     * @param string $token Token à vérifier
     * 
     * @return bool
     */
    private function verifyCsrfToken(string $token): bool
    {
        // Je vérifie que le token existe en session et correspond
        if (!isset($_SESSION['csrf_token'])) {
            return false;
        }

        return hash_equals($_SESSION['csrf_token'], $token);
    }
}

<?php

/**
 * Modèle GuestbookComment
 * 
 * Je gère toutes les opérations liées aux commentaires du livre d'or en base de données
 */
class GuestbookComment
{
    /**
     * @var PDO|null Instance de connexion à la base de données
     */
    private ?PDO $db;

    /**
     * Je construis le modèle GuestbookComment
     */
    public function __construct()
    {
        $this->db = getDatabase();
    }

    /**
     * Je crée un nouveau commentaire en base de données
     *
     * @param int         $userId  ID de l'utilisateur
     * @param string|null $title   Titre du commentaire
     * @param string      $message Message du commentaire
     * 
     * @return bool
     */
    public function create(int $userId, ?string $title, string $message): bool
    {
        // Je vérifie que la connexion existe
        if ($this->db === null) {
            return false;
        }

        try {
            // Je prépare la requête d'insertion
            $sql = 'INSERT INTO guestbook_comments (user_id, title, message, is_visible, created_at)
                    VALUES (:user_id, :title, :message, 1, NOW())';

            $stmt = $this->db->prepare($sql);

            return $stmt->execute([
                ':user_id' => $userId,
                ':title' => $title,
                ':message' => $message
            ]);
        } catch (PDOException $e) {
            error_log('Erreur création commentaire livre d\'or : ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Je récupère les commentaires visibles du livre d'or
     *
     * @param int $limit Nombre maximum de résultats
     * 
     * @return array
     */
    public function findVisible(int $limit = 50): array
    {
        // Je vérifie que la connexion existe
        if ($this->db === null) {
            return [];
        }

        // Je sécurise la limite pour éviter les valeurs incohérentes
        if ($limit < 1) {
            $limit = 1;
        }
        if ($limit > 200) {
            $limit = 200;
        }

        try {
            // Je prépare la requête de sélection avec jointure sur les utilisateurs
            $sql = 'SELECT 
                        gc.id,
                        gc.user_id,
                        gc.title,
                        gc.message,
                        gc.created_at,
                        u.username
                    FROM guestbook_comments gc
                    INNER JOIN users u ON u.id = gc.user_id
                    WHERE gc.is_visible = 1
                    ORDER BY gc.created_at DESC
                    LIMIT ' . (int) $limit;

            $stmt = $this->db->query($sql);

            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log('Erreur récupération commentaires visibles : ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Je récupère tous les commentaires pour l'administration
     *
     * @return array
     */
    public function findAllForAdmin(): array
    {
        // Je vérifie que la connexion existe
        if ($this->db === null) {
            return [];
        }

        try {
            // Je prépare la requête de sélection complète avec jointure
            $sql = 'SELECT 
                        gc.id,
                        gc.user_id,
                        gc.title,
                        gc.message,
                        gc.is_visible,
                        gc.created_at,
                        u.username,
                        u.email
                    FROM guestbook_comments gc
                    INNER JOIN users u ON u.id = gc.user_id
                    ORDER BY gc.created_at DESC';

            $stmt = $this->db->query($sql);

            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log('Erreur récupération commentaires admin : ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Je récupère un commentaire par son ID pour l'administration
     *
     * @param int $id ID du commentaire
     *
     * @return array|null
     */
    public function findByIdForAdmin(int $id): ?array
    {
        // Je vérifie que la connexion existe
        if ($this->db === null) {
            return null;
        }

        try {
            $sql = 'SELECT 
                        gc.id,
                        gc.user_id,
                        gc.title,
                        gc.message,
                        gc.is_visible,
                        gc.created_at,
                        u.username,
                        u.email
                    FROM guestbook_comments gc
                    INNER JOIN users u ON u.id = gc.user_id
                    WHERE gc.id = :id
                    LIMIT 1';

            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);

            $comment = $stmt->fetch();

            return $comment ?: null;
        } catch (PDOException $e) {
            error_log('Erreur findByIdForAdmin : ' . $e->getMessage());
            return null;
        }
    }


    /**
     * Je supprime un commentaire par son ID
     *
     * @param int $id ID du commentaire
     * 
     * @return bool
     */
    public function delete(int $id): bool
    {
        // Je vérifie que la connexion existe
        if ($this->db === null) {
            return false;
        }

        try {
            // Je prépare la requête de suppression
            $sql = 'DELETE FROM guestbook_comments WHERE id = :id';
            $stmt = $this->db->prepare($sql);

            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log('Erreur suppression commentaire : ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Je change la visibilité d'un commentaire
     *
     * @param int  $id        ID du commentaire
     * @param bool $isVisible Nouvelle visibilité
     * 
     * @return bool
     */
    public function setVisibility(int $id, bool $isVisible): bool
    {
        // Je vérifie que la connexion existe
        if ($this->db === null) {
            return false;
        }

        try {
            // Je prépare la requête de mise à jour
            $sql = 'UPDATE guestbook_comments SET is_visible = :is_visible WHERE id = :id';
            $stmt = $this->db->prepare($sql);

            return $stmt->execute([
                ':is_visible' => $isVisible ? 1 : 0,
                ':id' => $id
            ]);
        } catch (PDOException $e) {
            error_log('Erreur mise à jour visibilité commentaire : ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Je modifie un commentaire depuis l'administration
     *
     * @param int         $id      ID du commentaire
     * @param string|null $title   Nouveau titre (null si vide)
     * @param string      $message Nouveau message
     *
     * @return bool
     */
    public function updateAdmin(int $id, ?string $title, string $message): bool
    {
        // Je vérifie que la connexion existe
        if ($this->db === null) {
            return false;
        }

        try {
            // Je prépare la requête de mise à jour
            $sql = 'UPDATE guestbook_comments 
                    SET title = :title, message = :message
                    WHERE id = :id';

            $stmt = $this->db->prepare($sql);

            return $stmt->execute([
                ':title' => $title,
                ':message' => $message,
                ':id' => $id
            ]);
        } catch (PDOException $e) {
            error_log('Erreur update commentaire admin : ' . $e->getMessage());
            return false;
        }
    }


    /**
     * Je compte le nombre total de commentaires
     *
     * @return int
     */
    public function countAll(): int
    {
        // Je vérifie que la connexion existe
        if ($this->db === null) {
            return 0;
        }

        try {
            $sql = 'SELECT COUNT(*) as total FROM guestbook_comments';
            $stmt = $this->db->query($sql);
            $result = $stmt->fetch();

            return (int) ($result['total'] ?? 0);
        } catch (PDOException $e) {
            error_log('Erreur comptage commentaires : ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Je compte le nombre total de commentaires visibles
     *
     * @return int
     */
    public function countVisible(): int
    {
        if ($this->db === null) {
            return 0;
        }

        try {
            $sql = 'SELECT COUNT(*) as total FROM guestbook_comments WHERE is_visible = 1';
            $stmt = $this->db->query($sql);
            $result = $stmt->fetch();

            return (int) ($result['total'] ?? 0);
        } catch (PDOException $e) {
            error_log('Erreur comptage commentaires visibles : ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Je compte le nombre total de commentaires masqués
     *
     * @return int
     */
    public function countHidden(): int
    {
        if ($this->db === null) {
            return 0;
        }

        try {
            $sql = 'SELECT COUNT(*) as total FROM guestbook_comments WHERE is_visible = 0';
            $stmt = $this->db->query($sql);
            $result = $stmt->fetch();

            return (int) ($result['total'] ?? 0);
        } catch (PDOException $e) {
            error_log('Erreur comptage commentaires masqués : ' . $e->getMessage());
            return 0;
        }
    }

}

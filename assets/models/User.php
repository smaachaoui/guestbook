<?php

/**
 * Modèle User
 * 
 * Je gère toutes les opérations liées aux utilisateurs en base de données
 */
class User
{
    /**
     * @var PDO|null Instance de connexion à la base de données
     */
    private ?PDO $db;

    /**
     * Je construis le modèle User
     */
    public function __construct()
    {
        $this->db = getDatabase();
    }

    /**
     * Je crée un nouvel utilisateur en base de données
     *
     * @param string $username Nom d'utilisateur
     * @param string $email    Email de l'utilisateur
     * @param string $password Mot de passe en clair
     * @param string $role     Rôle de l'utilisateur (user ou admin)
     * 
     * @return bool
     */
    public function create(string $username, string $email, string $password, string $role = 'user'): bool
    {
        // Je vérifie que la connexion existe
        if ($this->db === null) {
            return false;
        }

        try {
            // Je prépare la requête d'insertion
            $sql = 'INSERT INTO users (username, email, password, role, created_at) 
                    VALUES (:username, :email, :password, :role, NOW())';
            
            $stmt = $this->db->prepare($sql);
            
            // Je hash le mot de passe avant de l'enregistrer
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            // J'exécute la requête avec les paramètres
            return $stmt->execute([
                ':username' => $username,
                ':email' => $email,
                ':password' => $hashedPassword,
                ':role' => $role
            ]);
        } catch (PDOException $e) {
            error_log('Erreur création utilisateur : ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Je récupère un utilisateur par son email
     *
     * @param string $email Email de l'utilisateur
     * 
     * @return array|null
     */
    public function findByEmail(string $email): ?array
    {
        // Je vérifie que la connexion existe
        if ($this->db === null) {
            return null;
        }

        try {
            // Je prépare la requête de sélection
            $sql = 'SELECT * FROM users WHERE email = :email LIMIT 1';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':email' => $email]);
            
            // Je récupère le résultat
            $user = $stmt->fetch();
            
            return $user ?: null;
        } catch (PDOException $e) {
            error_log('Erreur recherche utilisateur : ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Je récupère un utilisateur par son ID
     *
     * @param int $id ID de l'utilisateur
     * 
     * @return array|null
     */
    public function findById(int $id): ?array
    {
        // Je vérifie que la connexion existe
        if ($this->db === null) {
            return null;
        }

        try {
            // Je prépare la requête de sélection
            $sql = 'SELECT * FROM users WHERE id = :id LIMIT 1';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            
            // Je récupère le résultat
            $user = $stmt->fetch();
            
            return $user ?: null;
        } catch (PDOException $e) {
            error_log('Erreur recherche utilisateur : ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Je vérifie si un email existe déjà en base
     *
     * @param string $email Email à vérifier
     * 
     * @return bool
     */
    public function emailExists(string $email): bool
    {
        return $this->findByEmail($email) !== null;
    }

    /**
     * Je vérifie si un nom d'utilisateur existe déjà en base
     *
     * @param string $username Nom d'utilisateur à vérifier
     * 
     * @return bool
     */
    public function usernameExists(string $username): bool
    {
        // Je vérifie que la connexion existe
        if ($this->db === null) {
            return false;
        }

        try {
            // Je prépare la requête de vérification
            $sql = 'SELECT id FROM users WHERE username = :username LIMIT 1';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':username' => $username]);
            
            return $stmt->fetch() !== false;
        } catch (PDOException $e) {
            error_log('Erreur vérification username : ' . $e->getMessage());
            return false;
        }
    }

        /**
        * Je vérifie si un email existe déjà en base pour un autre utilisateur
     *
     * @param string $email     Email à vérifier
     * @param int    $excludeId ID de l'utilisateur à exclure
     *
     * @return bool
     */
    public function emailExistsExceptId(string $email, int $excludeId): bool
    {
        // Je vérifie que la connexion existe
        if ($this->db === null) {
            return false;
        }

        try {
            // Je prépare la requête de vérification
            $sql = 'SELECT id FROM users WHERE email = :email AND id != :id LIMIT 1';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':email' => $email,
                ':id' => $excludeId
            ]);

            return $stmt->fetch() !== false;
        } catch (PDOException $e) {
            error_log('Erreur vérification email (except id) : ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Je vérifie si un nom d'utilisateur existe déjà en base pour un autre utilisateur
     *
     * @param string $username  Nom d'utilisateur à vérifier
     * @param int    $excludeId ID de l'utilisateur à exclure
     *
     * @return bool
     */
    public function usernameExistsExceptId(string $username, int $excludeId): bool
    {
        // Je vérifie que la connexion existe
        if ($this->db === null) {
            return false;
        }

        try {
            // Je prépare la requête de vérification
            $sql = 'SELECT id FROM users WHERE username = :username AND id != :id LIMIT 1';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':username' => $username,
                ':id' => $excludeId
            ]);

            return $stmt->fetch() !== false;
        } catch (PDOException $e) {
            error_log('Erreur vérification username (except id) : ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Je mets à jour les informations personnelles d'un utilisateur
     *
     * @param int    $id       ID de l'utilisateur
     * @param string $username Nouveau nom d'utilisateur
     * @param string $email    Nouvel email
     *
     * @return bool
     */
    public function updateProfile(int $id, string $username, string $email): bool
    {
        // Je vérifie que la connexion existe
        if ($this->db === null) {
            return false;
        }

        try {
            // Je prépare la requête de mise à jour
            $sql = 'UPDATE users SET username = :username, email = :email WHERE id = :id';
            $stmt = $this->db->prepare($sql);

            return $stmt->execute([
                ':username' => $username,
                ':email' => $email,
                ':id' => $id
            ]);
        } catch (PDOException $e) {
            error_log('Erreur mise à jour profil : ' . $e->getMessage());
            return false;
        }
    }


    /**
     * Je récupère tous les utilisateurs
     *
     * @return array
     */
    public function findAll(): array
    {
        // Je vérifie que la connexion existe
        if ($this->db === null) {
            return [];
        }

        try {
            // Je prépare la requête de sélection
            $sql = 'SELECT id, username, email, role, created_at FROM users ORDER BY created_at DESC';
            $stmt = $this->db->query($sql);
            
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log('Erreur récupération utilisateurs : ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Je compte le nombre total d'utilisateurs
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
            // Je prépare la requête de comptage
            $sql = 'SELECT COUNT(*) as total FROM users';
            $stmt = $this->db->query($sql);
            $result = $stmt->fetch();
            
            return (int) $result['total'];
        } catch (PDOException $e) {
            error_log('Erreur comptage utilisateurs : ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Je compte le nombre d'administrateurs
     *
     * @return int
     */
    public function countAdmins(): int
    {
        // Je vérifie que la connexion existe
        if ($this->db === null) {
            return 0;
        }

        try {
            // Je prépare la requête de comptage
            $sql = "SELECT COUNT(*) as total FROM users WHERE role = 'admin'";
            $stmt = $this->db->query($sql);
            $result = $stmt->fetch();
            
            return (int) $result['total'];
        } catch (PDOException $e) {
            error_log('Erreur comptage admins : ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Je compte les nouveaux utilisateurs des 7 derniers jours
     *
     * @return int
     */
    public function countNewUsers(): int
    {
        // Je vérifie que la connexion existe
        if ($this->db === null) {
            return 0;
        }

        try {
            // Je prépare la requête de comptage
            $sql = 'SELECT COUNT(*) as total FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)';
            $stmt = $this->db->query($sql);
            $result = $stmt->fetch();
            
            return (int) $result['total'];
        } catch (PDOException $e) {
            error_log('Erreur comptage nouveaux utilisateurs : ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Je supprime un utilisateur par son ID
     *
     * @param int $id ID de l'utilisateur
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
            $sql = 'DELETE FROM users WHERE id = :id';
            $stmt = $this->db->prepare($sql);
            
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log('Erreur suppression utilisateur : ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Je mets à jour le rôle d'un utilisateur
     *
     * @param int    $id   ID de l'utilisateur
     * @param string $role Nouveau rôle
     * 
     * @return bool
     */
    public function updateRole(int $id, string $role): bool
    {
        // Je vérifie que la connexion existe
        if ($this->db === null) {
            return false;
        }

        // Je vérifie que le rôle est valide
        if (!in_array($role, ['user', 'admin'])) {
            return false;
        }

        try {
            // Je prépare la requête de mise à jour
            $sql = 'UPDATE users SET role = :role WHERE id = :id';
            $stmt = $this->db->prepare($sql);
            
            return $stmt->execute([':role' => $role, ':id' => $id]);
        } catch (PDOException $e) {
            error_log('Erreur mise à jour rôle : ' . $e->getMessage());
            return false;
        }
    }
}
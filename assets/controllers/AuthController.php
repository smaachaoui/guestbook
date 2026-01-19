<?php

/**
 * Contrôleur d'authentification
 * 
 * Je gère les actions d'inscription, de connexion et de déconnexion
 */
class AuthController
{
    /**
     * @var User Instance du modèle User
     */
    private User $userModel;

    /**
     * Je construis le contrôleur d'authentification
     */
    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * Je gère l'inscription d'un nouvel utilisateur
     *
     * @return array Tableau contenant le statut et le message
     */
    public function register(): array
    {
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
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        // Je valide les données
        $validation = $this->validateRegistration($username, $email, $password, $confirmPassword);
        if (!$validation['valid']) {
            return ['success' => false, 'error' => $validation['error']];
        }

        // Je vérifie si l'email existe déjà
        if ($this->userModel->emailExists($email)) {
            return ['success' => false, 'error' => 'Cet email est déjà utilisé.'];
        }

        // Je vérifie si le nom d'utilisateur existe déjà
        if ($this->userModel->usernameExists($username)) {
            return ['success' => false, 'error' => 'Ce nom d\'utilisateur est déjà utilisé.'];
        }

        // Je crée l'utilisateur
        if ($this->userModel->create($username, $email, $password)) {
            return ['success' => true, 'message' => 'Inscription réussie ! Vous pouvez maintenant vous connecter.'];
        }

        return ['success' => false, 'error' => 'Une erreur est survenue lors de l\'inscription.'];
    }

    /**
     * Je valide les données d'inscription
     *
     * @param string $username        Nom d'utilisateur
     * @param string $email           Email
     * @param string $password        Mot de passe
     * @param string $confirmPassword Confirmation du mot de passe
     * 
     * @return array
     */
    private function validateRegistration(string $username, string $email, string $password, string $confirmPassword): array
    {
        // Je vérifie que tous les champs sont remplis
        if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
            return ['valid' => false, 'error' => 'Tous les champs sont obligatoires.'];
        }

        // Je vérifie la longueur du nom d'utilisateur
        if (strlen($username) < 3 || strlen($username) > 50) {
            return ['valid' => false, 'error' => 'Le nom d\'utilisateur doit contenir entre 3 et 50 caractères.'];
        }

        // Je vérifie le format du nom d'utilisateur
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            return ['valid' => false, 'error' => 'Le nom d\'utilisateur ne peut contenir que des lettres, chiffres et underscores.'];
        }

        // Je vérifie le format de l'email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['valid' => false, 'error' => 'L\'email n\'est pas valide.'];
        }

        // Je vérifie la longueur du mot de passe
        if (strlen($password) < 8) {
            return ['valid' => false, 'error' => 'Le mot de passe doit contenir au moins 8 caractères.'];
        }

        // Je vérifie que les mots de passe correspondent
        if ($password !== $confirmPassword) {
            return ['valid' => false, 'error' => 'Les mots de passe ne correspondent pas.'];
        }

        return ['valid' => true, 'error' => null];
    }

    /**
     * Je gère la connexion d'un utilisateur
     *
     * @return array Tableau contenant le statut et le message
     */
    public function login(): array
    {
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
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // Je vérifie que les champs sont remplis
        if (empty($email) || empty($password)) {
            return ['success' => false, 'error' => 'Tous les champs sont obligatoires.'];
        }

        // Je recherche l'utilisateur par email
        $user = $this->userModel->findByEmail($email);

        // Je vérifie si l'utilisateur existe
        if ($user === null) {
            return ['success' => false, 'error' => 'Email ou mot de passe incorrect.'];
        }

        // Je vérifie le mot de passe
        if (!password_verify($password, $user['password'])) {
            return ['success' => false, 'error' => 'Email ou mot de passe incorrect.'];
        }

        // Je crée la session utilisateur
        $this->createUserSession($user);

        return ['success' => true, 'message' => 'Connexion réussie !'];
    }

        /**
     * Je gère la mise à jour du profil utilisateur (username et email)
     *
     * @return array Tableau contenant le statut et le message
     */
    public function updateProfile(): array
    {
        // Je vérifie que l'utilisateur est connecté
        if (!$this->isLoggedIn()) {
            return ['success' => false, 'error' => 'Vous devez être connecté pour modifier votre profil.'];
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
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');

        // Je vérifie que les champs sont remplis
        if (empty($username) || empty($email)) {
            return ['success' => false, 'error' => 'Tous les champs sont obligatoires.'];
        }

        // Je vérifie la longueur du nom d'utilisateur
        if (strlen($username) < 3 || strlen($username) > 50) {
            return ['success' => false, 'error' => 'Le nom d\'utilisateur doit contenir entre 3 et 50 caractères.'];
        }

        // Je vérifie le format du nom d'utilisateur
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            return ['success' => false, 'error' => 'Le nom d\'utilisateur ne peut contenir que des lettres, chiffres et underscores.'];
        }

        // Je vérifie le format de l'email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'error' => 'L\'email n\'est pas valide.'];
        }

        // Je récupère l'utilisateur connecté
        $currentUser = $this->getCurrentUser();
        if ($currentUser === null) {
            return ['success' => false, 'error' => 'Utilisateur introuvable.'];
        }

        $userId = (int) $currentUser['id'];

        // Je vérifie si l'email est déjà utilisé par un autre utilisateur
        if ($this->userModel->emailExistsExceptId($email, $userId)) {
            return ['success' => false, 'error' => 'Cet email est déjà utilisé.'];
        }

        // Je vérifie si le nom d'utilisateur est déjà utilisé par un autre utilisateur
        if ($this->userModel->usernameExistsExceptId($username, $userId)) {
            return ['success' => false, 'error' => 'Ce nom d\'utilisateur est déjà utilisé.'];
        }

        // Je mets à jour le profil en base
        if (!$this->userModel->updateProfile($userId, $username, $email)) {
            return ['success' => false, 'error' => 'Une erreur est survenue lors de la mise à jour du profil.'];
        }

        // Je mets à jour la session pour refléter les changements immédiatement
        $_SESSION['user']['username'] = $username;
        $_SESSION['user']['email'] = $email;

        return ['success' => true, 'message' => 'Profil mis à jour avec succès !'];
    }


    /**
     * Je crée la session utilisateur
     *
     * @param array $user Données de l'utilisateur
     * 
     * @return void
     */
    private function createUserSession(array $user): void
    {
        // Je régénère l'ID de session pour la sécurité
        session_regenerate_id(true);

        // Je stocke les infos utilisateur en session
        $_SESSION['user'] = [
            'id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'role' => $user['role']
        ];
    }

    /**
     * Je gère la déconnexion d'un utilisateur
     *
     * @return void
     */
    public function logout(): void
    {
        // Je supprime les données de session
        $_SESSION = [];

        // Je détruis le cookie de session
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        // Je détruis la session
        session_destroy();
    }

    /**
     * Je génère un token CSRF
     *
     * @return string
     */
    public function generateCsrfToken(): string
    {
        // Je génère un token aléatoire
        $token = bin2hex(random_bytes(32));
        
        // Je stocke le token en session
        $_SESSION['csrf_token'] = $token;
        
        return $token;
    }

    /**
     * Je vérifie le token CSRF
     *
     * @param string $token Token à vérifier
     * 
     * @return bool
     */
    public function verifyCsrfToken(string $token): bool
    {
        // Je vérifie que le token existe en session et correspond
        if (!isset($_SESSION['csrf_token'])) {
            return false;
        }
        
        return hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Je vérifie si l'utilisateur est connecté
     *
     * @return bool
     */
    public function isLoggedIn(): bool
    {
        return isset($_SESSION['user']);
    }

    /**
     * Je vérifie si l'utilisateur est admin
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->isLoggedIn() && $_SESSION['user']['role'] === 'admin';
    }

    /**
     * Je récupère le modèle User
     *
     * Je l'utilise notamment pour les fonctionnalités d'administration (dashboard, liste des utilisateurs, etc.)
     *
     * @return User Instance du modèle User
     */
    public function getUserModel(): User
    {
        return $this->userModel;
    }


    /**
     * Je récupère l'utilisateur connecté
     *
     * @return array|null
     */
    public function getCurrentUser(): ?array
    {
        return $_SESSION['user'] ?? null;
    }
}
<?php

/**
 * Configuration de la base de données
 * 
 * Je définis les constantes de connexion à la base de données MySQL
 */

// Je définis l'hôte de la base de données
define('DB_HOST', 'localhost');

// Je définis le nom de la base de données
define('DB_NAME', 'auth_module');

// Je définis l'utilisateur de la base de données
define('DB_USER', 'user_sql');

// Je définis le mot de passe de la base de données
define('DB_PASS', 'Avatar123*');

// Je définis le charset
define('DB_CHARSET', 'utf8mb4');

/**
 * Je crée la connexion PDO à la base de données
 *
 * @return PDO|null
 */
function getDatabase(): ?PDO
{
    static $pdo = null;

    // Je vérifie si la connexion existe déjà
    if ($pdo === null) {
        try {
            // Je crée la connexion PDO
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
            $options = [ 
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ];
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // Je gère l'erreur de connexion
            error_log('Erreur de connexion BDD : ' . $e->getMessage());
            return null;
        }
    }

    return $pdo;
}
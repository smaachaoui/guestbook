<?php

/**
 * Point d'entrée de l'application
 * 
 * Je gère le routage et j'inclus les fichiers nécessaires
 */

// Je démarre la session
session_start();

// J'inclus les fichiers nécessaires
require_once '../config/database.php';
require_once '../assets/models/User.php';
require_once '../assets/controllers/AuthController.php';

// J'instancie le contrôleur d'authentification
$authController = new AuthController();

// Je récupère la page demandée via l'URL
$page = $_GET['page'] ?? 'home';

// Je définis les pages autorisées
$allowedPages = ['home', 'login', 'register', 'profile', 'edit_profile', 'admin', 'logout'];

// Je vérifie si la page demandée existe
if (!in_array($page, $allowedPages)) {
    $page = 'home';
}

// J'initialise les variables de messages
$error = null;
$success = null;



// Je gère les actions selon la page
switch ($page) {
    case 'login':
        // Je traite la connexion
        $result = $authController->login();
        if ($result['success']) {
            header('Location: index.php?page=profile');
            exit;
        }
        $error = $result['error'];
        break;

    case 'register':
        // Je traite l'inscription
        $result = $authController->register();
        if ($result['success']) {
            $success = $result['message'];
        } else {
            $error = $result['error'];
        }
        break;

    case 'logout':
        // Je traite la déconnexion
        $authController->logout();
        header('Location: index.php');
        exit;

    case 'profile':
        // Je vérifie si l'utilisateur est connecté
        if (!$authController->isLoggedIn()) {
            header('Location: index.php?page=login');
            exit;
        }
        break;
    
    case 'edit_profile':
    // Je vérifie si l'utilisateur est connecté
    if (!$authController->isLoggedIn()) {
        header('Location: index.php?page=login');
        exit;
    }

    // Je traite la mise à jour du profil
    $result = $authController->updateProfile();
    if ($result['success']) {
        $success = $result['message'];
    } else {
        $error = $result['error'];
    }
    break;


    case 'admin':
    // Je vérifie si l'utilisateur est admin
    if (!$authController->isAdmin()) {
        header('Location: index.php?page=login');
        exit;
    }
    // Je prépare les données pour la vue admin
    $userModel = $authController->getUserModel();
    $totalUsers = $userModel->countAll();
    $totalAdmins = $userModel->countAdmins();
    $newUsers = $userModel->countNewUsers();
    $users = $userModel->findAll();
    break;
}

// Je génère un token CSRF pour les formulaires
$csrfToken = $authController->generateCsrfToken();

// Je définis les titres des pages
$pageTitles = [
    'home' => 'Accueil',
    'login' => 'Connexion',
    'register' => 'Inscription',
    'profile' => 'Mon Profil',
    'edit_profile' => 'Modifier mon Profil',
    'admin' => 'Administration'
];
$pageTitle = $pageTitles[$page] ?? 'Auth Module';

// J'inclus le header
include '../assets/views/components/header.php';

// J'inclus la vue demandée
include '../assets/views/' . $page . '.php';

// J'inclus le footer
include '../assets/views/components/footer.php';
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
require_once '../assets/models/GuestbookComment.php';
require_once '../assets/controllers/GuestbookController.php';


// J'instancie le contrôleur d'authentification
$authController = new AuthController();

// J'instancie le contrôleur du livre d'or
$guestbookController = new GuestbookController();


// Je récupère la page demandée via l'URL
$page = $_GET['page'] ?? 'home';

// Je définis les pages autorisées
$allowedPages = ['home', 'login', 'register', 'profile', 'edit_profile', 'admin', 'guestbook', 'logout'];

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

    // Je récupère la section demandée
    $section = $_GET['section'] ?? 'dashboard';

    // Je limite les sections autorisées
    $allowedSections = ['dashboard', 'users', 'guestbook'];
    if (!in_array($section, $allowedSections)) {
        $section = 'dashboard';
    }

    // Je prépare les données selon la section
    if ($section === 'dashboard') {
    $userModel = $authController->getUserModel();

    $totalUsers = $userModel->countAll();
    $newUsers = $userModel->countNewUsers();

    // Stats livre d'or
    $guestbookModel = new GuestbookComment();
    $totalGuestbookMessages = $guestbookModel->countAll();
    $totalGuestbookVisible = $guestbookModel->countVisible();
    $totalGuestbookHidden = $guestbookModel->countHidden();
    }


    if ($section === 'users') {
        $userModel = $authController->getUserModel();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

            // CREATE user
            if ($_POST['action'] === 'create_user') {
                $result = $authController->createUserAdmin(); 
                if ($result['success']) {
                    $success = $result['message'];
                } else {
                    $error = $result['error'];
                }
            }

            // UPDATE user
            if ($_POST['action'] === 'update_user') {
                $result = $authController->updateUserAdmin(); 
                if ($result['success']) {
                    $success = $result['message'];
                } else {
                    $error = $result['error'];
                }
            }

            // DELETE user
            if ($_POST['action'] === 'delete_user') {
                $result = $authController->deleteUserAdmin(); 
                if ($result['success']) {
                    $success = $result['message'];
                } else {
                    $error = $result['error'];
                }
            }
        }

        // Si on est en mode édition, je récupère l'utilisateur à éditer
        $userToEdit = null;
        if (isset($_GET['edit'])) {
            $editId = (int) $_GET['edit'];
            if ($editId > 0) {
                $userToEdit = $userModel->findById($editId);
                if ($userToEdit === null) {
                    $error = $error ?? 'Utilisateur introuvable.';
                }
            }
        }


        $users = $userModel->findAll();
    }

    if ($section === 'guestbook') {
        // Je traite les actions admin (suppression ou visibilité)
        if (isset($_POST['action']) && $_POST['action'] === 'delete_comment') {
            $result = $guestbookController->deleteCommentAdmin();
            if ($result['success']) {
                $success = $result['message'];
            } else {
                $error = $result['error'];
            }
        }

        if (isset($_POST['action']) && $_POST['action'] === 'toggle_comment_visibility') {
            $result = $guestbookController->toggleVisibilityAdmin();
            if ($result['success']) {
                $success = $result['message'];
            } else {
                $error = $result['error'];
            }
        }

        if (isset($_POST['action']) && $_POST['action'] === 'edit_comment') {
            $result = $guestbookController->editCommentAdmin();
            if ($result['success']) {
                $success = $result['message'];
            } else {
                $error = $result['error'];
            }
        }

        // Je récupère les commentaires pour l'administration
        $adminComments = $guestbookController->getAllCommentsForAdmin();

        // Si on est en mode édition, je récupère le commentaire à éditer
        $commentToEdit = null;
        if (isset($_GET['edit'])) {
            $editId = (int) $_GET['edit'];
            if ($editId > 0) {
                $commentToEdit = $guestbookController->getCommentForAdmin($editId);
                if ($commentToEdit === null) {
                    $error = $error ?? 'Commentaire introuvable.';
                }
            }
        }

    }

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
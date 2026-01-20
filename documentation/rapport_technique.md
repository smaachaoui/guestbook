```markdown
# Documentation technique – Livre d’or PHP

## Objectif du projet

J’ai développé cette application afin de mettre en place un système d’authentification, un livre d’or public et un espace d’administration. Le projet repose sur PHP natif, PDO pour la base de données et Bootstrap pour l’interface.

Je cherche à démontrer une bonne structuration du code, une gestion claire des droits et une séparation logique entre routage, contrôleurs, modèles et vues.

---

## Point d’entrée et routage

### Fichier : `public/index.php`

Le projet repose sur un point d’entrée unique.

```php
session_start();

require_once '../config/database.php';
require_once '../assets/controllers/AuthController.php';
require_once '../assets/controllers/GuestbookController.php';

$page = $_GET['page'] ?? 'home';
```

Je limite volontairement les pages accessibles via une liste blanche afin d’éviter l’inclusion arbitraire de fichiers.

```php
$allowedPages = [
    'home', 'login', 'register',
    'profile', 'edit_profile',
    'guestbook', 'admin', 'logout'
];

if (!in_array($page, $allowedPages)) {
    $page = 'home';
}
```

Chaque page est ensuite traitée dans un `switch ($page)` afin de centraliser la logique de navigation et de sécurité.

---

## Gestion de l’authentification

### Contrôleur : `AuthController`

Je gère l’authentification via des méthodes dédiées.

#### Connexion

```php
public function login()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return ['success' => false];
    }

    $user = $this->userModel->findByEmail($_POST['email']);

    if (!$user || !password_verify($_POST['password'], $user['password'])) {
        return ['success' => false, 'error' => 'Identifiants incorrects'];
    }

    $_SESSION['user'] = [
        'id'       => $user['id'],
        'role'     => $user['role'],
        'username' => $user['username']
    ];

    return ['success' => true];
}
```

Je m’appuie sur `password_hash()` et `password_verify()` afin de respecter les bonnes pratiques de sécurité.

---

## Sécurité et rôles

### Vérification du rôle admin

Dans `index.php`, je protège l’accès à l’administration :

```php
case 'admin':
    if (!$authController->isAdmin()) {
        header('Location: index.php?page=login');
        exit;
    }
```

Cette vérification empêche toute tentative d’accès non autorisée, même via une requête manuelle.

### Protection CSRF

Je génère un token CSRF unique par session.

```php
public function generateCsrfToken()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}
```

Je vérifie ce token dans chaque action sensible (POST) avant de traiter les données.

---

## Livre d’or public

### Accès libre en lecture

La page `guestbook` est volontairement accessible à tous.

```php
case 'guestbook':
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $guestbookController->addComment();
    }

    $comments = $guestbookController->getVisibleComments();
    break;
```

La récupération des commentaires visibles repose uniquement sur `is_visible = 1`.

```php
public function getVisibleComments()
{
    return $this->guestbookModel->findVisible();
}
```

### Ajout d’un commentaire

Seul un utilisateur connecté peut poster un message.

```php
public function addComment()
{
    if (!$this->authController->isLoggedIn()) {
        return ['success' => false, 'error' => 'Connexion requise'];
    }

    return $this->guestbookModel->create(
        $_SESSION['user']['id'],
        $_POST['title'] ?? null,
        $_POST['message']
    );
}
```

---

## Administration – structure générale

La vue `admin.php` agit comme un conteneur principal.

```php
$section = $_GET['section'] ?? 'dashboard';

switch ($section) {
    case 'users':
        include 'partials/_users.php';
        break;

    case 'guestbook':
        include 'partials/_guestbook.php';
        break;
}
```

Chaque section admin est isolée dans un partial dédié.

---

## Administration des utilisateurs

### Vue : `_users.php`

J’affiche la liste des utilisateurs et les boutons d’actions.

```php
<button
  type="button"
  data-bs-toggle="modal"
  data-bs-target="#modalEditUser"
  data-user-id="<?= $user['id'] ?>"
  data-user-username="<?= htmlspecialchars($user['username'], ENT_QUOTES) ?>"
  data-user-email="<?= htmlspecialchars($user['email'], ENT_QUOTES) ?>"
  data-user-role="<?= $user['role'] ?>"
>
  Modifier
</button>
```

Je ne fais aucune modification directe via l’URL.

### Modals utilisateurs

Fichier : `_user_modals.php`

```html
<div class="modal fade" id="modalEditUser">
  <form method="POST" action="index.php?page=admin&section=users">
    <input type="hidden" name="action" value="update_user">
    <input type="hidden" name="user_id" id="modalEditUser_id">
    <input type="text" name="username" id="modalEditUser_username">
  </form>
</div>
```

Les modals sont isolées afin de garder les vues principales lisibles.

### Hydratation des modals (JavaScript)

Fichier : `public/js/main.js`

```js
const modalEditUser = document.getElementById('modalEditUser');

if (modalEditUser) {
  modalEditUser.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;

    document.getElementById('modalEditUser_id').value =
      button.getAttribute('data-user-id');

    document.getElementById('modalEditUser_username').value =
      button.getAttribute('data-user-username');
  });
}
```

Je m’appuie sur les attributs `data-*` afin d’éviter toute requête supplémentaire.

---

## Administration des commentaires

### Vue : `_guestbook.php`

```php
<button
  data-bs-toggle="modal"
  data-bs-target="#modalEditComment"
  data-comment-id="<?= $comment['id'] ?>"
  data-comment-message="<?= htmlspecialchars(json_encode($comment['message']), ENT_QUOTES) ?>"
>
  Modifier
</button>
```

Je passe les messages en JSON afin de préserver les retours à la ligne et caractères spéciaux.

### Modals commentaires

Fichier : `_guestbook_modals.php`

```html
<textarea
  name="message"
  id="modalEditComment_message"
  rows="6"
></textarea>
```

Ces modals permettent à l’admin de modifier ou supprimer un commentaire sans changer d’URL.

---

## Base de données

### Modèle `User`

```php
public function findByEmail(string $email)
{
    $stmt = $this->pdo->prepare(
        "SELECT * FROM users WHERE email = :email"
    );
    $stmt->execute(['email' => $email]);

    return $stmt->fetch();
}
```

### Modèle `GuestbookComment`

```php
public function findVisible()
{
    return $this->pdo
        ->query("SELECT * FROM guestbook_comments WHERE is_visible = 1")
        ->fetchAll();
}
```

---

## Limites connues

Je n’ai pas implémenté :

- pagination,
- recherche,
- requêtes AJAX,
- permissions fines par action.

Ces limites sont assumées afin de garder une base claire et lisible.

---

## Conclusion

Cette application repose sur une architecture simple mais rigoureuse. Chaque choix est volontaire et cohérent avec l’objectif pédagogique du projet. Le code est structuré pour être compris, maintenu et étendu sans refonte majeure.
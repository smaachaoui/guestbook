```markdown
# Livre d’or (PHP)

## Présentation

Je présente ici une application PHP (PDO/MySQL) qui propose une authentification, un profil utilisateur et un livre d’or. Je gère un espace d’administration pour piloter les utilisateurs et modérer les messages. J’ai rendu le livre d’or visible par tous (visiteur, utilisateur connecté, admin), tout en conservant le fait que seul l’admin peut modifier ou supprimer des contenus.

## Fonctionnalités

1. Je peux m’inscrire, me connecter et me déconnecter.  
2. Je peux consulter et modifier mon profil (username, email).  
3. Je peux accéder au livre d’or même si je ne suis pas connecté, et je peux lire tous les messages visibles.  
4. Je peux poster un message dans le livre d’or uniquement si je suis connecté.  
5. En tant qu’admin, je peux accéder au dashboard, gérer les utilisateurs (créer, modifier, supprimer) et modérer les messages (modifier, supprimer, masquer/afficher).  
6. J’utilise des modals Bootstrap pour la modification et la suppression d’un utilisateur ou d’un commentaire.

## Prérequis

Je dois avoir un serveur PHP (Apache recommandé), PHP avec PDO MySQL, et une base MySQL ou MariaDB.

## Installation

1. Je place le projet dans mon répertoire web (par exemple dans `www/guestbook`).  
2. Je configure mon hôte virtuel pour pointer vers le dossier `public` si possible.  
3. Si je ne pointe pas vers `public`, je m’assure que la réécriture est active (`mod_rewrite`) et je laisse les fichiers `.htaccess` en place.

## Configuration de la base de données

Je modifie les identifiants de base de données dans `config/database.php`.

Je crée la base et les tables attendues par l’application.

Voici un SQL minimal compatible avec les modèles `User` et `GuestbookComment` :

```sql
CREATE DATABASE IF NOT EXISTS auth_module CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE auth_module;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  email VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('user','admin') NOT NULL DEFAULT 'user',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS guestbook_comments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  title VARCHAR(100) NULL,
  message TEXT NOT NULL,
  is_visible TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_guestbook_user
    FOREIGN KEY (user_id) REFERENCES users(id)
    ON DELETE CASCADE
);
```

## Premier compte admin

Je dois disposer d’au moins un admin pour accéder à `index.php?page=admin`.

Je peux procéder de deux façons :

1. Je m’inscris via l’interface, puis je passe mon rôle en admin en base de données :

   ```sql
   UPDATE users SET role = 'admin' WHERE email = 'mon.email@example.com';
   ```

2. Je crée directement un admin en base de données (je dois hash le mot de passe comme PHP le fait). Je privilégie la méthode 1, plus simple.

## Démarrage

1. Je lance mon serveur et j’ouvre l’application via le dossier `public`.  
2. Je navigue avec les pages suivantes :

   - `index.php?page=home`  
   - `index.php?page=register`  
   - `index.php?page=login`  
   - `index.php?page=profile`  
   - `index.php?page=guestbook`  
   - `index.php?page=admin` (admin uniquement)

## Règles d’accès

Je laisse le livre d’or en lecture accessible à tous via `page=guestbook`. J’affiche uniquement les commentaires dont `is_visible = 1`.

Je réserve les actions suivantes à l’admin :

- Modifier ou supprimer un utilisateur (`admin > users`).  
- Modifier, supprimer, masquer ou afficher un commentaire (`admin > guestbook`).

## Organisation du projet

Je structure le projet ainsi :

- `public/index.php` est le point d’entrée et gère le routage.  
- `config/database.php` centralise la connexion PDO.  
- `assets/controllers` contient les contrôleurs (`AuthController`, `GuestbookController`).  
- `assets/models` contient les modèles (`User`, `GuestbookComment`).  
- `assets/views` contient les vues et partials (admin, modals, header/footer).  
- `public/js/main.js` gère la validation front et l’hydratation des modals.

## Modals (admin)

Je sépare les modals dans des fichiers dédiés :

- `assets/views/admin/partials/modals/_user_modals.php`  
- `assets/views/admin/partials/modals/_guestbook_modals.php`

Je déclenche ces modals depuis les partials :

- `assets/views/admin/partials/_users.php`  
- `assets/views/admin/partials/_guestbook.php`

Je remplis automatiquement les champs au moment de l’ouverture via `public/js/main.js` en utilisant des attributs `data-*`.

## Notes

Je garde une protection CSRF sur les formulaires via un token injecté dans les vues.

Je considère que les identifiants de base de données dans `config/database.php` sont prévus pour un environnement de développement. Je ne laisse pas ces valeurs telles quelles en production.
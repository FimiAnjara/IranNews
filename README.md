# IranNews - Plateforme d'actualités

Un projet PHP classique avec architecture MVC (Model-View-Controller) sans framework, optimisé pour fonctionner avec Docker et Docker Compose.

## 📋 Caractéristiques

- Architecture MVC simple et directe
- **Routage simple avec paramètres GET** (sans réécriture d'URL)
- Authentification utilisateur (login/register)
- Gestion des articles (Create, Read, Update, Delete)
- Recherche et filtrage par catégorie
- Pagination
- Design responsive
- Support Docker/Docker Compose
- Base de données MySQL avec PDO
- Model.php comme classe de base (héritable pour tous les modèles)

## 🏗️ Structure du projet

```
IranNews/
├── app/
│   ├── config/
│   │   ├── bootstrap.php      # Configuration et classe Database
│   │   └── database.php       # Paramètres de connexion
│   ├── controllers/
│   │   ├── AuthController.php    # Gestion de l'authentification
│   │   ├── FrontController.php   # Pages publiques
│   │   └── BackController.php    # Administration
│   ├── models/
│   │   ├── News.php          # Modèle Article
│   │   └── User.php          # Modèle Utilisateur
│   ├── routes/
│   │   └── (dépréciés - routage direct dans index.php)
│   └── views/
│       ├── front/            # Vues publiques
│       ├── back/             # Vues administration
│       ├── errors/           # Pages d'erreur
│       └── layouts/
│           └── app.php       # Layout principal
├── public/
│   ├── index.php             # Point d'entrée + routage
│   ├── .htaccess             # Réécriture d'URLs (mod_rewrite)
│   └── assets/
│       ├── css/style.css     # Styles
│       └── js/script.js      # JavaScript
├── database.sql              # Script d'initialisation DB
├── Dockerfile
├── docker-compose.yml
└── README.md
```

## 🚀 Démarrage avec Docker

### Prérequis

- Docker
- Docker Compose

### Installation et lancement

1. **Cloner/placer le projet**
```bash
cd IranNews
```

2. **Lancer le projet**
```bash
docker-compose up -d
```

3. **Accéder à l'application**
- Frontend: http://localhost:8000
- phpMyAdmin (optionnel): http://localhost:8001

4. **Arrêter le projet**
```bash
docker-compose down
```

## 📝 Identifiants de test

**Admin:**
- Utilisateur: `admin`
- Mot de passe: `admin123`

## 🔄 Pages disponibles

### Frontend (Public)
- `index.php?page=accueil` - Accueil avec liste des articles
- `index.php?page=article&id={ID}` - Détail d'un article
- `index.php?page=recherche&q=QUERY` - Recherche
- `index.php?page=categorie&category=CATEGORY` - Articles par catégorie
- `index.php?page=a-propos` - À propos
- `index.php?page=contact` - Contact
- `index.php?page=connexion` - Connexion
- `index.php?page=inscription` - Inscription

### Backend (Authentifié)
- `index.php?page=admin&action=dashboard` - Tableau de bord
- `index.php?page=admin&action=news-list` - Gestion des articles
- `index.php?page=admin&action=news-create` - Créer un article
- `index.php?page=admin&action=users-list` - Gestion des utilisateurs

## 🗄️ Base de données

### Tables créées
- `users` - Utilisateurs (id, username, email, password, role, created_at)
- `news` - Articles (id, title, slug, content, user_id, category, image_url, published, views, created_at, updated_at)
- `comments` - Commentaires (id, news_id, user_id, content, approved, created_at)

## 🔐 Authentification

L'authentification utilise PHP sessions et password_hash/password_verify pour sécuriser les mots de passe.

### Rôles disponibles
- `admin` - Accès complet
- `author` - Créer et éditer ses articles
- `user` - Accès à la lecture seule

## 💾 Configuration

Les paramètres de la base de données sont définis via les variables d'environnement Docker :
- `DB_HOST`
- `DB_USER`
- `DB_PASSWORD`
- `DB_NAME`
- `DB_PORT`

## 🛠️ Développement

### Sans Docker (local)

1. Installer PHP 8.2+ avec les extensions :
   - pdo_mysql
   - gd
   - json

2. Créer une base de données MySQL et importer `database.sql`

3. Configurer les paramètres dans `app/config/database.php`

4. Lancer un serveur PHP local :
```bash
php -S localhost:8000 -t public/
```

## 📄 Fichiers principaux

- `public/index.php` - Point d'entrée + routage direct
- `public/.htaccess` - Réécriture des URLs (mod_rewrite)
- `app/config/bootstrap.php` - Classe Database et initialisations
- `Dockerfile` - Configuration Docker (Apache + mod_rewrite)
- `database.sql` - Script d'initialisation de la BDD

## ⚠️ Notes importantes

- **Routage simple** - Le système utilise des paramètres GET simples (pas de réécriture d'URL mod_rewrite)
- **PDO** - Toutes les requêtes utilisent PDO avec prepared statements pour la sécurité
- **Model.php** - Classe de base héritable pour tous les modèles (News, User)
- Les sessions PHP gèrent l'authentification
- Pas de dépendances externes (framework-free)

## 📦 Extensibilité

Pour ajouter une nouvelle page :

1. Créer une méthode dans `FrontController.php` ou `BackController.php`
2. Ajouter une règle RewriteRule dans `.htaccess` si besoin (optionnel)
3. Ajouter un cas dans le switch de `public/index.php`
4. Créer la vue correspondante dans `app/views/`
5. Ajouter le lien dans le menu du layout `app.php`

## 🐛 Dépannage

### Erreur de connexion DB
Vérifier les variables d'environnement dans `docker-compose.yml`

### Page non trouvée
Vérifier que le paramètre `page` est correct dans l'URL

### Permissions refusées
Vérifier les permissions des fichiers avec `chown www-data:www-data`

## 📞 Support

Pour les problèmes, consultez :
- Les logs Docker : `docker-compose logs app`
- Les logs MySQL : `docker-compose logs db`
- Activez le DEBUG dans `app/config/database.php`

## 📜 Licence

Projet pédagogique

## 🎯 Roadmap

- [ ] Ajouter phpMyAdmin au docker-compose
- [ ] Implémenter les commentaires
- [ ] Ajouter la pagination côté admin
- [ ] Système de cache
- [ ] API REST
- [ ] Export PDF des articles
- [ ] Multi-langue

---

**Version:** 1.0.0  
**Dernière mise à jour:** 2026

# 🛣️ Système de routage avec URLs réécrites (.htaccess)

## Vue d'ensemble

Le projet utilise **mod_rewrite** d'Apache via un fichier `.htaccess` pour transformer les URLs en lecturable et SEO-friendly.

---

## 📝 Comment ça fonctionne

### Avant (ancienne méthode)
```
http://localhost:8000/index.php?page=article&id=5
http://localhost:8000/index.php?page=login
http://localhost:8000/index.php?page=admin&action=dashboard
```

### Après (nouvelle méthode)
```
http://localhost:8000/article/5
http://localhost:8000/connexion
http://localhost:8000/admin/tableaux-de-bord
```

---

## 🔧 Configuration technique

### .htaccess (public/.htaccess)

```apache
RewriteEngine On
RewriteBase /

# Ne pas traiter les fichiers et dossiers réels
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d

# Rediriger toutes les requêtes vers index.php
RewriteRule ^(.*)$ index.php?_route=$1 [QSA,L]
```

**Explication:**
- `RewriteEngine On` - Active la réécriture
- `RewriteCond %{REQUEST_FILENAME} !-f` - Ignore les fichiers réels (css, js, images)
- `RewriteCond %{REQUEST_FILENAME} !-d` - Ignore les dossiers réels
- `RewriteRule` - Redirige vers index.php en passant le chemin dans `_route`

### Router.php

```php
$route = $_GET['_route'] ?? 'accueil';  // Ex: "article/5"
$segments = explode('/', $route);       // Ex: ["article", "5"]
$page = $segments[0];                  // Ex: "article"
$param1 = $segments[1];                // Ex: "5"
```

---

## 🗺️ Map des routes

| URL réécrite | Route interne | Contrôleur | Méthode |
|---|---|---|---|
| `/accueil` | `accueil` | FrontController | home() |
| `/article/5` | `article/5` | FrontController | showNews(5) |
| `/article/mon-titre-5` | `article/mon-titre-5` | FrontController | showNews(5) |
| `/recherche?q=iran` | `recherche` | FrontController | search() |
| `/categorie/politique` | `categorie/politique` | FrontController | newsByCategory() |
| `/a-propos` | `a-propos` | FrontController | about() |
| `/contact` | `contact` | FrontController | contact() |
| `/connexion` | `connexion` | AuthController | login() |
| `/inscription` | `inscription` | AuthController | register() |
| `/deconnexion` | `deconnexion` | AuthController | logout() |
| `/admin/tableaux-de-bord` | `admin/tableaux-de-bord` | BackController | dashboard() |
| `/admin/articles` | `admin/articles` | BackController | newsList() |
| `/admin/articles/creer` | `admin/articles/creer` | BackController | newsCreate() |
| `/admin/utilisateurs` | `admin/utilisateurs` | BackController | usersList() |

---

## 🔗 Fonctions d'aide (Helpers)

### Génération d'URLs

```php
url('accueil')  
// Retourne: http://localhost:8000/accueil

articleUrl(5, 'mon-article')
// Retourne: http://localhost:8000/article/mon-article-5

categoryUrl('Politique')
// Retourne: http://localhost:8000/categorie/politique

searchUrl('iran')
// Retourne: http://localhost:8000/recherche?q=iran

adminUrl('dashboard')
// Retourne: http://localhost:8000/admin/tableaux-de-bord

adminUrl('news-list')
// Retourne: http://localhost:8000/admin/articles

adminUrl('news-create')
// Retourne: http://localhost:8000/admin/articles/creer
```

### Redirections

```php
redirect('accueil');
// Redirige vers http://localhost:8000/accueil

redirect('connexion');
// Redirige vers http://localhost:8000/connexion
```

---

## 🔍 Exemple complet

### 1. L'utilisateur clique sur un lien

```html
<a href="<?php echo articleUrl(5, 'nouvelles-iran'); ?>">Article</a>
<!-- Génère: <a href="http://localhost:8000/article/nouvelles-iran-5">Article</a> -->
```

### 2. La requête arrive

```
GET /article/nouvelles-iran-5 HTTP/1.1
```

### 3. Apache applique .htaccess

```
/article/nouvelles-iran-5 → /index.php?_route=article/nouvelles-iran-5
```

### 4. index.php lance le routeur

```php
$_GET['_route'] = 'article/nouvelles-iran-5'
$router->route();  // Analyse et dispatch
```

### 5. Router parse le chemin

```php
$segments = ['article', 'nouvelles-iran-5'];
$page = 'article';
$param1 = 'nouvelles-iran-5';

FrontController::showNews('nouvelles-iran-5');
```

### 6. Le contrôleur extrait l'ID

```php
// Parser: nouvelles-iran-5 → 5
$id = (int) substr($param1, strrpos($param1, '-') + 1);  // = 5
$this->newsModel->getById(5);
```

### 7. La vue est affichée

```html
Article avec ID 5 affiché ✅
```

---

## 📋 Structure du Router.php

```php
class Router {
    public function route() {
        // 1. Récupère le chemin
        $route = $_GET['_route'] ?? 'accueil';
        
        // 2. Parse le chemin
        $segments = explode('/', $route);
        $page = $segments[0];
        $param1 = $segments[1];
        $param2 = $segments[2];
        
        // 3. Dispatch vers le bon contrôleur
        $result = $this->dispatch($page, $param1, $param2);
        
        // 4. Affiche la vue
        $this->render();
    }
    
    private function dispatch($page, $param1, $param2) {
        switch ($page) {
            case 'article':
                $controller = new FrontController();
                return $controller->showNews($param1);
            // ... autres cas
        }
    }
}
```

---

## ⚙️ Configuration Apache requise

### Dockerfile

Le Dockerfile inclut les configurations nécessaires:

```dockerfile
# Activer mod_rewrite
RUN a2enmod rewrite

# Permettre .htaccess (AllowOverride All)
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf
```

### Sans Docker

Si tu runs PHP localement, tu dois:

1. **Activer mod_rewrite:**
   ```bash
   sudo a2enmod rewrite
   ```

2. **Configurer Apache (apache2.conf ou vhost):**
   ```apache
   <Directory /var/www/html>
       AllowOverride All
   </Directory>
   ```

3. **Redémarrer Apache:**
   ```bash
   sudo systemctl restart apache2
   ```

---

## 🧪 Test

### 1. Vérifie que mod_rewrite est actif

```bash
# Dans le conteneur Docker
docker-compose exec app a2query -m rewrite
# Doit afficher: rewrite (enabled by site administrator)
```

### 2. Teste une URL réécrite

```bash
# Via curl
curl http://localhost:8000/accueil
curl http://localhost:8000/article/5
curl http://localhost:8000/connexion
```

### 3. Vérifie que .htaccess fonctionne

- Essaie d'accéder à une route : `http://localhost:8000/accueil`
- Tu dois voir la page d'accueil, pas une erreur 404
- Les fichiers statiques (`/assets/css/style.css`) doivent aussi fonctionner

---

## 🐛 Dépannage

### Erreur 404 sur les URLs réécrites

**Problème:** `.htaccess` ne fonctionne pas

**Solutions:**
1. Vérifie que mod_rewrite est activé:
   ```bash
   docker-compose exec app a2query -m rewrite
   ```

2. Vérifie que `AllowOverride All` est configuré:
   ```bash
   docker-compose exec app grep -n "AllowOverride" /etc/apache2/apache2.conf
   ```

3. Vérifie que `.htaccess` existe:
   ```bash
   docker-compose exec app ls -la /var/www/html/public/.htaccess
   ```

4. Redémarre Apache:
   ```bash
   docker-compose restart app
   ```

### Les fichiers statiques ne charge pas

- Vérifier que les fichiers existent réellement (la condition `!-f` les ignore)
- Vérifier les permissions: `docker-compose exec app ls -la /var/www/html/public/assets/`

### Erreur 500 en backend

- Vérifier que la fonction `redirect()` est chargée
- S'assurer que Router.php est inclus dans index.php
- Vérifier les logs: `docker-compose logs app`

---

## 📊 Avantages des URLs réécrites

✅ **URLs plus lisibles et SEO-friendly:**
- `/article/5` au lieu de `?page=news&id=5`

✅ **Meilleure expérience utilisateur:**
- Les URLs sont faciles à partager et à retenir

✅ **Sécurité légèrement améliorée:**
- Les paramètres techniques sont cachés

✅ **Cohérence:**
- Toutes les URLs suivent le même pattern

---

## 📚 Ressources

- [Tutoriel mod_rewrite Apache](https://httpd.apache.org/docs/current/mod/mod_rewrite.html)
- [.htaccess Documentation](https://httpd.apache.org/docs/current/howto/htaccess.html)
- [SEO URLs Meilleures Pratiques](https://moz.com/learn/seo/url)

---

**Version:** 2.0 (avec réécriture d'URL)  
**Dernière mise à jour:** 2026

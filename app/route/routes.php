<?php

require_once __DIR__ . '/../controllers/FrontController.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/BackController.php';
require_once __DIR__ . '/../controllers/CategoryController.php';

// Les paramètres GET sont gérés par .htaccess
$page = $_GET['page'] ?? 'accueil';
$action = $_GET['action'] ?? null;
$id = $_GET['id'] ?? null;
$category = $_GET['category'] ?? null;
$q = $_GET['q'] ?? null;

try {
    $view = null;
    $data = [];

    switch ($page) {
        // Pages publiques
        case 'accueil':
        case 'home':
            $controller = new FrontController();
            $result = $controller->home();
            $view = $result['view'];
            $data = $result['data'];
            break;

        case 'article':
        case 'news':
            if (!$id) throw new Exception('ID article manquant');
            $controller = new FrontController();
            $result = $controller->showNews($id);
            $view = $result['view'];
            $data = $result['data'];
            break;

        case 'recherche':
        case 'search':
            $controller = new FrontController();
            $result = $controller->search();
            $view = $result['view'];
            $data = $result['data'];
            break;

        case 'categorie':
        case 'category':
            if (!$category) throw new Exception('Catégorie manquante');
            $controller = new FrontController();
            $result = $controller->newsByCategory($category);
            $view = $result['view'];
            $data = $result['data'];
            break;

        case 'a-propos':
        case 'about':
            $controller = new FrontController();
            $result = $controller->about();
            $view = $result['view'];
            $data = $result['data'];
            break;

        case 'contact':
            $controller = new FrontController();
            $result = $controller->contact();
            $view = $result['view'];
            $data = $result['data'];
            break;

        // SEO
        case 'sitemap':
        case 'sitemap.xml':
            // Rediriger vers le fichier PHP
            header('Location: /sitemap.php');
            exit;

        case 'robots':
        case 'robots.txt':
            // robots.txt est un fichier statique, pas besoin de route
            break;

        // Authentification
        case 'connexion':
        case 'login':
            $controller = new AuthController();
            $result = $controller->login();
            $view = $result['view'];
            $data = $result['data'];
            break;

        case 'inscription':
        case 'register':
            $controller = new AuthController();
            $result = $controller->register();
            $view = $result['view'];
            $data = $result['data'];
            break;

        case 'deconnexion':
        case 'logout':
            $controller = new AuthController();
            $controller->logout();
            exit;

        // Administration
        case 'admin':
            if (!isset($_SESSION['user_id'])) {
                header('Location: /');
                exit;
            }
            $controller = new BackController();

            if ($action === 'dashboard' || empty($action)) {
                $result = $controller->dashboard();
            } elseif ($action === 'news-list') {
                $result = $controller->newsList();
            } elseif ($action === 'news-create') {
                $result = $controller->newsCreate();
            } elseif (strpos($action, 'news-edit-') === 0) {
                $newsId = (int)str_replace('news-edit-', '', $action);
                $result = $controller->newsEdit($newsId);
            } elseif (strpos($action, 'news-delete-') === 0) {
                $newsId = (int)str_replace('news-delete-', '', $action);
                $result = $controller->newsDelete($newsId);
            } elseif (strpos($action, 'news-toggle-publish-') === 0) {
                $newsId = (int)str_replace('news-toggle-publish-', '', $action);
                $result = $controller->newsTogglePublish($newsId);
            } elseif ($action === 'users-list') {
                $result = $controller->usersList();
            } elseif ($action === 'categories-list') {
                $catController = new CategoryController();
                $result = $catController->list();
            } elseif ($action === 'categories-create') {
                $catController = new CategoryController();
                $result = $catController->create();
            } elseif (strpos($action, 'categories-edit-') === 0) {
                $catId = (int)str_replace('categories-edit-', '', $action);
                $catController = new CategoryController();
                $result = $catController->edit($catId);
            } elseif (strpos($action, 'categories-delete-') === 0) {
                $catId = (int)str_replace('categories-delete-', '', $action);
                $catController = new CategoryController();
                $result = $catController->delete($catId);
            } else {
                $result = $controller->dashboard();
            }
            $view = $result['view'];
            $data = $result['data'];
            break;

        // Page non trouvée
        default:
            throw new Exception('Page non trouvée: ' . htmlspecialchars($page));
    }

    // Charger la vue
    if ($view && file_exists(__DIR__ . '/../views/' . $view)) {
        extract($data);
        
        // Charger la vue dans un buffer
        ob_start();
        require __DIR__ . '/../views/' . $view;
        $viewContent = ob_get_clean();
        
        // Charger le layout principal avec le contenu de la vue
        include __DIR__ . '/../views/layouts/app.php';
    } else {
        throw new Exception('Vue non trouvée: ' . $view);
    }

} catch (Exception $e) {
    // Afficher l'erreur
    $error = $e->getMessage();
    require __DIR__ . '/../views/errors/500.php';
}


?>
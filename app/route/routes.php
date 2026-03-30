<?php

require_once __DIR__ . '/../controllers/FrontController.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/BackController.php';

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
            } elseif ($action === 'news-delete' && $id) {
                $result = $controller->newsDelete($id);
            } elseif ($action === 'users-list') {
                $result = $controller->usersList();
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